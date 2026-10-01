<?php
header('Content-Type: application/json');
include_once __DIR__ . '/src/config.php';
include_once __DIR__ . '/src/security.php';
header('Cache-Control: no-store');
header('X-Robots-Tag: noindex, nofollow');

function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function respond($status, $message) {
    echo json_encode(['status' => $status, 'message' => $message]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    http_response_code(405);
    respond(0, 'Invalid request method.');
}

// Reject cross-site submissions: if the browser sent an Origin, it must be this site.
if (!empty($_SERVER['HTTP_ORIGIN'])) {
    $origin = parse_url($_SERVER['HTTP_ORIGIN']);
    $own = parse_url(base_url());
    $sameOrigin = isset($origin['host'], $own['host'])
        && strcasecmp($origin['host'], $own['host']) === 0
        && ($origin['port'] ?? null) === ($own['port'] ?? null);
    if (!$sameOrigin) {
        http_response_code(403);
        respond(0, 'Request blocked.');
    }
}

// Honeypot: real users never see or fill this field. Pretend success so bots move on.
if (!empty($_POST['website'])) {
    respond(1, 'Thank you for reaching out! We have received your message and will reply soon.');
}

// Basic per-IP rate limit (5 submissions / 10 minutes) to protect the mail API.
$rateFile = sys_get_temp_dir() . '/contact_rate_' . md5($_SERVER['REMOTE_ADDR'] ?? 'unknown');
$now = time();
$hits = is_file($rateFile) ? array_filter((array) json_decode((string) @file_get_contents($rateFile), true), function ($t) use ($now) { return $t > $now - 600; }) : [];
if (count($hits) >= 5) {
    http_response_code(429);
    respond(0, 'Too many messages. Please try again in a few minutes.');
}
$hits[] = $now;
@file_put_contents($rateFile, json_encode(array_values($hits)), LOCK_EX);

// Mail settings come from .env; refuse to send until configured
if (!defined('MAIL_API_KEY') || MAIL_API_KEY === '' || !defined('MAIL_TO') || MAIL_TO === '') {
    error_log('contact-send.php: MAIL_API_KEY / MAIL_TO are not configured in .env');
    respond(0, 'Messaging is not configured yet. Please email us directly at ' . CONTACT_EMAIL . '.');
}

$errors = [];
foreach (['name', 'email', 'message'] as $field) {
    if (empty($_POST[$field]) || !is_string($_POST[$field])) {
        $errors[] = ucfirst($field) . ' is required.';
    }
}
if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address.';
}
// Length caps keep the mail API from being used to relay huge payloads.
foreach (['name' => 100, 'email' => 254, 'phone' => 30, 'message' => 5000] as $field => $max) {
    if (isset($_POST[$field]) && (!is_string($_POST[$field]) || mb_strlen($_POST[$field]) > $max)) {
        $errors[] = ucfirst($field) . ' is too long.';
    }
}
if ($errors) {
    respond(0, implode(' ', $errors));
}

$name = sanitizeInput($_POST['name']);
$phone = sanitizeInput($_POST['phone'] ?? '');
$email = sanitizeInput($_POST['email']);
$message = sanitizeInput($_POST['message']);
$site = defined('APP_NAME') ? APP_NAME : 'Timesheet';

$row = function ($label, $value) {
    return '<tr><td style="padding:8px 0;color:#64748b;width:120px;vertical-align:top">' . $label . '</td><td style="padding:8px 0;color:#0f172a">' . $value . '</td></tr>';
};

$emailBody = '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>Contact form</title></head>'
    . '<body style="margin:0;background:#f1f5f9;font-family:Arial,sans-serif;line-height:1.6">'
    . '<div style="max-width:600px;margin:24px auto;background:#fff;border-radius:12px;overflow:hidden">'
    . '<div style="background:#1f8a3e;color:#fff;padding:20px 24px"><h1 style="margin:0;font-size:20px">New contact inquiry</h1><p style="margin:4px 0 0;font-size:13px;opacity:.85">' . $site . '</p></div>'
    . '<div style="padding:24px"><table style="width:100%;border-collapse:collapse">'
    . $row('Name', $name)
    . $row('Email', '<a href="mailto:' . $email . '">' . $email . '</a>')
    . ($phone !== '' ? $row('Phone', '<a href="tel:' . preg_replace('/[^0-9+]/', '', $phone) . '">' . $phone . '</a>') : '')
    . '</table>'
    . '<h2 style="font-size:15px;color:#0f172a;margin:20px 0 8px">Message</h2>'
    . '<div style="background:#f8fafc;border-radius:8px;padding:14px;color:#0f172a">' . nl2br($message) . '</div>'
    . '<p style="margin-top:20px;font-size:12px;color:#94a3b8">Received ' . date('l, F j, Y \a\t g:i A') . '</p>'
    . '</div></div></body></html>';

$data = [
    'to' => MAIL_TO,
    'subject' => 'New contact inquiry - ' . $site,
    'body' => $emailBody,
    'api_key' => MAIL_API_KEY,
    'from_name' => $site . ' Contact Form',
    'from_email' => defined('MAIL_FROM_EMAIL') ? MAIL_FROM_EMAIL : 'noreply@localhost',
    'reply_to' => $email,
];
if (defined('MAIL_BCC') && MAIL_BCC !== '') {
    $data['bcc'] = MAIL_BCC;
}

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => MAIL_API_URL,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_FOLLOWLOCATION => false, // never re-send the API key to a redirect target
    CURLOPT_PROTOCOLS => CURLPROTO_HTTPS,
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
    error_log('CURL Error in contact-send.php: ' . $curlError);
    respond(0, 'Network error occurred. Please try again later.');
}
if ($httpCode !== 200) {
    error_log('HTTP Error in contact-send.php: HTTP ' . $httpCode);
    respond(0, 'Service temporarily unavailable. Please try again later.');
}

$result = json_decode($response, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    error_log('JSON Error in contact-send.php: ' . json_last_error_msg());
    respond(0, 'Invalid response from email service.');
}

if (!empty($result['status'])) {
    respond(1, 'Thank you for reaching out! We have received your message and will reply soon.');
}

error_log('Email API Error in contact-send.php: ' . ($result['message'] ?? 'Unknown error'));
respond(0, 'There was an issue sending your message. Please try again or email ' . CONTACT_EMAIL . '.');
