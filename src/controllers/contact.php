<?php
/** Contact form handler (POST /contact-send). Sends the message through the mail API configured in .env. */

const CONTACT_THANKS = 'Thank you for reaching out! We have received your message and will reply soon.';

function contact_respond($status, $message) {
    echo json_encode(['status' => $status, 'message' => $message]);
    exit;
}

function contact_clean($data) {
    return htmlspecialchars(strip_tags(trim((string) $data)));
}

/** Returns a list of error messages (empty when the input is valid). */
function contact_validate(array $in) {
    $errors = [];
    foreach (['name', 'email', 'message'] as $field) {
        if (empty($in[$field])) {
            $errors[] = ucfirst($field) . ' is required.';
        }
    }
    if (!empty($in['email']) && !filter_var($in['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }
    return $errors;
}

function contact_mail_configured() {
    return config('MAIL_API_URL') !== '' && config('MAIL_API_KEY') !== '' && config('MAIL_TO') !== '';
}

/** HTML body from views/emails/contact.php. $d holds the cleaned name, email, phone and message. */
function contact_email_body(array $d) {
    require_once ROOT_PATH . '/system/view.php';
    return render_view('emails/contact', $d + [
        'site' => config('APP_NAME'),
        'color' => config('THEME_COLOR'),
        'received' => date('l, F j, Y \a\t g:i A'),
    ]);
}

/** Sends through the mail API. Returns null on success or an error message for the visitor. */
function contact_send_mail($replyTo, $body) {
    $data = [
        'to' => config('MAIL_TO'),
        'subject' => 'New contact inquiry - ' . config('APP_NAME'),
        'body' => $body,
        'api_key' => config('MAIL_API_KEY'),
        'from_name' => config('APP_NAME') . ' Contact Form',
        'from_email' => config('MAIL_FROM_EMAIL'),
        'reply_to' => $replyTo,
    ];
    if (config('MAIL_BCC') !== '') {
        $data['bcc'] = config('MAIL_BCC');
    }

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => config('MAIL_API_URL'),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
    ]);
    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $curlError = curl_error($curl);
    curl_close($curl);

    if ($curlError) {
        error_log('contact: CURL error: ' . $curlError);
        return 'Network error occurred. Please try again later.';
    }
    if ($httpCode !== 200) {
        error_log('contact: HTTP error ' . $httpCode);
        return 'Service temporarily unavailable. Please try again later.';
    }
    $result = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log('contact: JSON error: ' . json_last_error_msg());
        return 'Invalid response from email service.';
    }
    if (empty($result['status'])) {
        error_log('contact: email API error: ' . ($result['message'] ?? 'Unknown error'));
        return 'There was an issue sending your message. Please try again or email ' . config('CONTACT_EMAIL') . '.';
    }
    return null;
}

function handle_contact() {
    load('helper/security');
    header('Content-Type: application/json');
    header('X-Robots-Tag: noindex, nofollow');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        contact_respond(0, 'Invalid request method.');
    }

    // Honeypot: bots fill the hidden field. Pretend success so they do not retry.
    if (!empty($_POST['website'])) {
        contact_respond(1, CONTACT_THANKS);
    }
    if (!csrf_valid($_POST['csrf'] ?? null)) {
        contact_respond(0, 'Your session expired. Please reload the page and try again.');
    }
    if (rate_limited('contact', 5, 600)) {
        contact_respond(0, 'Too many messages. Please try again in a few minutes.');
    }

    if ($errors = contact_validate($_POST)) {
        contact_respond(0, implode(' ', $errors));
    }
    if (!contact_mail_configured()) {
        error_log('contact: MAIL_API_URL / MAIL_API_KEY / MAIL_TO are not configured in .env');
        contact_respond(0, 'Messaging is not configured yet. Please email us directly at ' . config('CONTACT_EMAIL') . '.');
    }

    $body = contact_email_body([
        'name' => contact_clean($_POST['name']),
        'email' => contact_clean($_POST['email']),
        'phone' => contact_clean($_POST['phone'] ?? ''),
        'message' => contact_clean($_POST['message']),
    ]);
    $error = contact_send_mail($_POST['email'], $body);
    contact_respond($error === null ? 1 : 0, $error ?? CONTACT_THANKS);
}
