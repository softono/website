<?php /* Contact email. Vars (already escaped): $site, $color, $name, $email, $phone, $message, $received. */ ?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Contact form</title></head>
<body style="margin:0;background:#f1f5f9;font-family:Arial,sans-serif;line-height:1.6">
<div style="max-width:600px;margin:24px auto;background:#fff;border-radius:12px;overflow:hidden">
    <div style="background:<?= e($color); ?>;color:#fff;padding:20px 24px">
        <h1 style="margin:0;font-size:20px">New contact inquiry</h1>
        <p style="margin:4px 0 0;font-size:13px;opacity:.85"><?= e($site); ?></p>
    </div>
    <div style="padding:24px">
        <table style="width:100%;border-collapse:collapse">
            <tr><td style="padding:8px 0;color:#64748b;width:120px;vertical-align:top">Name</td><td style="padding:8px 0;color:#0f172a"><?= $name; ?></td></tr>
            <tr><td style="padding:8px 0;color:#64748b;width:120px;vertical-align:top">Email</td><td style="padding:8px 0;color:#0f172a"><a href="mailto:<?= $email; ?>"><?= $email; ?></a></td></tr>
            <?php if ($phone !== ''): ?>
            <tr><td style="padding:8px 0;color:#64748b;width:120px;vertical-align:top">Phone</td><td style="padding:8px 0;color:#0f172a"><a href="tel:<?= $phone; ?>"><?= $phone; ?></a></td></tr>
            <?php endif; ?>
        </table>
        <h2 style="font-size:15px;color:#0f172a;margin:20px 0 8px">Message</h2>
        <div style="background:#f8fafc;border-radius:8px;padding:14px;color:#0f172a"><?= nl2br($message); ?></div>
        <p style="margin-top:20px;font-size:12px;color:#94a3b8">Received <?= e($received); ?></p>
    </div>
</div>
</body>
</html>
