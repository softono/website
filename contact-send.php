<?php
header('Content-Type: application/json');
include_once __DIR__ . '/layout/config.php';

function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function respond($status, $message) {
    echo json_encode(['status' => $status, 'message' => $message]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(0, 'Invalid request method.');
}

// Mail settings come from .env; refuse to send until configured
if (!defined('MAIL_API_KEY') || MAIL_API_KEY === '' || !defined('MAIL_TO') || MAIL_TO === '') {
    error_log('contact-send.php: MAIL_API_KEY / MAIL_TO are not configured in .env');
    respond(0, 'Messaging is not configured yet. Please email us directly at ' . CONTACT_EMAIL . '.');
}

$errors = [];
foreach (['name', 'phone', 'email', 'message'] as $field) {
    if (empty($_POST[$field])) {
        $errors[] = ucfirst($field) . ' is required.';
    }
}
if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address.';
}
if ($errors) {
    respond(0, implode(' ', $errors));
}

$name = sanitizeInput($_POST['name']);
$phone = sanitizeInput($_POST['phone']);
$email = sanitizeInput($_POST['email']);
$message = sanitizeInput($_POST['message']);
$site = defined('SITE_NAME') ? SITE_NAME : 'Timesheet';

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
    . $row('Phone', '<a href="tel:' . $phone . '">' . $phone . '</a>')
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
