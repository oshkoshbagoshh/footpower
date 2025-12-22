<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * Email Sender Class
 * Handles sending HTML emails using PHPMailer library
 * 
 * @Author: AJ Javadi
 * @Email: amirjavadi25@gmail.com
 * @Date: 2025-01-XX
 * @Description: Email sending utility class for FootPower system using PHPMailer
 */
class EmailSender
{
    private $mailer;
    private $from_email;
    private $from_name;
    private $reply_to;
    private $smtp_config;

    /**
     * Constructor
     * 
     * @param string $from_email Sender email address
     * @param string $from_name Sender name
     * @param string $reply_to Reply-to email address (optional)
     * @param array $smtp_config SMTP configuration (optional)
     *   - 'host': SMTP server hostname
     *   - 'port': SMTP port (default: 587)
     *   - 'username': SMTP username
     *   - 'password': SMTP password
     *   - 'encryption': 'tls' or 'ssl' (default: 'tls')
     *   - 'auth': Enable SMTP authentication (default: true)
     */
    public function __construct($from_email = 'noreply@footpower.com', $from_name = 'FootPower', $reply_to = null, $smtp_config = null)
    {
        // Load PHPMailer
        require_once __DIR__ . '/../../vendor/autoload.php';
        
        $this->from_email = $from_email;
        $this->from_name = $from_name;
        $this->reply_to = $reply_to ?? $from_email;
        $this->smtp_config = $smtp_config;
        
        // Initialize PHPMailer
        $this->mailer = new PHPMailer(true);
        $this->configureMailer();
    }

    /**
     * Configure PHPMailer instance
     * 
     * @return void
     */
    private function configureMailer()
    {
        try {
            // Server settings
            if ($this->smtp_config && !empty($this->smtp_config['host'])) {
                // Use SMTP
                $this->mailer->isSMTP();
                $this->mailer->Host = $this->smtp_config['host'];
                $this->mailer->SMTPAuth = $this->smtp_config['auth'] ?? true;
                $this->mailer->Username = $this->smtp_config['username'] ?? '';
                $this->mailer->Password = $this->smtp_config['password'] ?? '';
                $this->mailer->SMTPSecure = $this->smtp_config['encryption'] ?? PHPMailer::ENCRYPTION_STARTTLS;
                $this->mailer->Port = $this->smtp_config['port'] ?? 587;
            } else {
                // Use PHP mail() function (fallback)
                $this->mailer->isMail();
            }
            
            // Character set
            $this->mailer->CharSet = 'UTF-8';
            
            // From address
            $this->mailer->setFrom($this->from_email, $this->from_name);
            
            // Reply-To address
            $this->mailer->addReplyTo($this->reply_to, $this->from_name);
            
            // Email format
            $this->mailer->isHTML(true);
            
        } catch (Exception $e) {
            error_log("EmailSender configuration error: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Send HTML email
     * 
     * @param string|array $to Recipient email address(es) - string or array
     * @param string $subject Email subject
     * @param string $html_body HTML email body
     * @param string $text_body Plain text version (optional, auto-generated if not provided)
     * @param array $attachments Optional array of file paths to attach
     * @return bool True on success, false on failure
     * @throws Exception If email sending fails
     */
    public function sendEmail($to, $subject, $html_body, $text_body = null, $attachments = [])
    {
        try {
            // Reset recipients for new email
            $this->mailer->clearAddresses();
            $this->mailer->clearAttachments();
            
            // Validate and add recipients
            $recipients = is_array($to) ? $to : [$to];
            foreach ($recipients as $recipient) {
                if (!filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
                    error_log("Invalid email address: $recipient");
                    return false;
                }
                $this->mailer->addAddress($recipient);
            }
            
            // Set subject
            $this->mailer->Subject = $subject;
            
            // Set body
            $this->mailer->Body = $html_body;
            
            // Generate plain text version if not provided
            if ($text_body === null) {
                $text_body = $this->htmlToText($html_body);
            }
            $this->mailer->AltBody = $text_body;
            
            // Add attachments if provided
            foreach ($attachments as $attachment) {
                if (is_array($attachment)) {
                    $this->mailer->addAttachment(
                        $attachment['path'],
                        $attachment['name'] ?? basename($attachment['path'])
                    );
                } else {
                    $this->mailer->addAttachment($attachment);
                }
            }
            
            // Send email
            $result = $this->mailer->send();
            
            if (!$result) {
                error_log("Failed to send email. Error: " . $this->mailer->ErrorInfo);
            }
            
            return $result;
            
        } catch (Exception $e) {
            error_log("Email sending exception: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Send email using the email template
     * 
     * @param string|array $to Recipient email address(es) - string or array
     * @param string $subject Email subject
     * @param array $template_data Data for the email template
     * @param array $attachments Optional array of file paths to attach
     * @return bool True on success, false on failure
     * @throws Exception If email sending fails
     */
    public function sendTemplateEmail($to, $subject, $template_data = [], $attachments = [])
    {
        // Include the template file
        require_once __DIR__ . '/../templates/email_template.php';
        
        // Get HTML from template
        $html_body = getEmailTemplate($template_data);
        
        // Send email
        return $this->sendEmail($to, $subject, $html_body, null, $attachments);
    }

    /**
     * Get PHPMailer instance (for advanced usage)
     * 
     * @return PHPMailer PHPMailer instance
     */
    public function getMailer()
    {
        return $this->mailer;
    }

    /**
     * Get last error message
     * 
     * @return string Last error message
     */
    public function getLastError()
    {
        return $this->mailer->ErrorInfo;
    }

    /**
     * Convert HTML to plain text
     * 
     * @param string $html HTML content
     * @return string Plain text content
     */
    private function htmlToText($html)
    {
        // Remove HTML tags
        $text = strip_tags($html);
        
        // Decode HTML entities
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        
        // Clean up whitespace
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);
        
        return $text;
    }
}

