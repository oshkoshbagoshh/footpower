<?php
/**
 * Email Sending Example
 * Demonstrates how to use the EmailSender class with PHPMailer and the email template
 * 
 * @Author: AJ Javadi
 * @Email: amirjavadi25@gmail.com
 * @Date: 2025-01-XX
 * @Description: Example usage of email template and EmailSender class with PHPMailer
 */

// Include initialization
require_once __DIR__ . '/../src/common/initialization.php';

// Include EmailSender class (autoloaded via autoload.php)
require_once __DIR__ . '/../src/classes/EmailSender.php';

// ============================================
// Example 1: Send email using PHP mail() (default)
// ============================================
echo "<h2>Example 1: Using PHP mail()</h2>";

$email_sender = new EmailSender(
    'noreply@footpower.com',
    'FootPower System',
    'support@footpower.com'
);

// Template data
$template_data = [
    'image_url' => 'https://d1a8dioxuajlzs.cloudfront.net/accounts/8014/original/1435862577978-ylgi84uxvpshsemi-a137023729a110c9e70a2bfe2f765847.png?1435862581',
    'list_items' => ['David', 'John', 'Jane'], // Customize list items
    'width' => '765' // Container width in pixels
];

// Send email
$to = 'recipient@example.com'; // Change this to actual recipient
$subject = 'Your Email Subject Here';

try {
    $result = $email_sender->sendTemplateEmail($to, $subject, $template_data);
    
    if ($result) {
        echo "<p style='color: green;'>✓ Email sent successfully to $to</p>";
    } else {
        echo "<p style='color: red;'>✗ Failed to send email to $to</p>";
        echo "<p>Error: " . htmlspecialchars($email_sender->getLastError()) . "</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Exception: " . htmlspecialchars($e->getMessage()) . "</p>";
}

// ============================================
// Example 2: Send email using SMTP
// ============================================
echo "<h2>Example 2: Using SMTP</h2>";

// SMTP configuration (update with your SMTP settings)
$smtp_config = [
    'host' => 'smtp.gmail.com', // Your SMTP server
    'port' => 587,
    'username' => 'your-email@gmail.com', // Your SMTP username
    'password' => 'your-app-password', // Your SMTP password or app password
    'encryption' => 'tls', // 'tls' or 'ssl'
    'auth' => true
];

$smtp_sender = new EmailSender(
    'noreply@footpower.com',
    'FootPower System',
    'support@footpower.com',
    $smtp_config
);

try {
    $result = $smtp_sender->sendTemplateEmail($to, $subject, $template_data);
    
    if ($result) {
        echo "<p style='color: green;'>✓ Email sent successfully via SMTP to $to</p>";
    } else {
        echo "<p style='color: red;'>✗ Failed to send email via SMTP</p>";
        echo "<p>Error: " . htmlspecialchars($smtp_sender->getLastError()) . "</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: orange;'>⚠ SMTP not configured: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><em>Update SMTP configuration in this file to test SMTP sending.</em></p>";
}

// ============================================
// Example 3: Send to multiple recipients
// ============================================
echo "<h2>Example 3: Multiple Recipients</h2>";

$recipients = [
    'recipient1@example.com',
    'recipient2@example.com'
];

try {
    $result = $email_sender->sendTemplateEmail($recipients, $subject, $template_data);
    
    if ($result) {
        echo "<p style='color: green;'>✓ Email sent successfully to multiple recipients</p>";
    } else {
        echo "<p style='color: red;'>✗ Failed to send email</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Exception: " . htmlspecialchars($e->getMessage()) . "</p>";
}

// ============================================
// Example 4: Send with attachments
// ============================================
echo "<h2>Example 4: Email with Attachments</h2>";

$attachments = [
    // Simple file path
    // __DIR__ . '/../public/images/footpower-logo.svg',
    // Or with custom name
    // ['path' => __DIR__ . '/../public/images/footpower-logo.svg', 'name' => 'logo.svg']
];

try {
    $result = $email_sender->sendTemplateEmail($to, $subject, $template_data, $attachments);
    
    if ($result) {
        echo "<p style='color: green;'>✓ Email with attachments sent successfully</p>";
    } else {
        echo "<p style='color: red;'>✗ Failed to send email</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Exception: " . htmlspecialchars($e->getMessage()) . "</p>";
}

// ============================================
// Example 5: Get template HTML directly (for preview)
// ============================================
echo "<h2>Example 5: Get Template HTML</h2>";

require_once __DIR__ . '/../src/templates/email_template.php';

$custom_data = [
    'image_url' => 'https://example.com/your-image.png',
    'list_items' => ['Item 1', 'Item 2', 'Item 3'],
    'width' => '765'
];

$html_content = getEmailTemplate($custom_data);

// Save to file for preview
$preview_file = __DIR__ . '/../src/templates/email_preview_custom.html';
file_put_contents($preview_file, $html_content);
echo "<p>Template HTML saved to: <code>" . htmlspecialchars($preview_file) . "</code></p>";
echo "<p><a href='../src/templates/email_preview_custom.html' target='_blank'>View Preview</a></p>";

// ============================================
// Example 6: Custom HTML email (not using template)
// ============================================
echo "<h2>Example 6: Custom HTML Email</h2>";

$custom_html = '<html><body><h1>Custom Email</h1><p>This is a custom HTML email.</p></body></html>';
$custom_text = 'Custom Email - This is a custom HTML email.';

try {
    $result = $email_sender->sendEmail($to, 'Custom Email Subject', $custom_html, $custom_text);
    
    if ($result) {
        echo "<p style='color: green;'>✓ Custom email sent successfully</p>";
    } else {
        echo "<p style='color: red;'>✗ Failed to send custom email</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Exception: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>

