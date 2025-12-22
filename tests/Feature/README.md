# Email Sender Feature Tests

This directory contains feature tests for the EmailSender class.

## Running Tests

### Run All Feature Tests
```bash
vendor/bin/phpunit tests/Feature
```

### Run Specific Test Class
```bash
vendor/bin/phpunit tests/Feature/EmailSenderTest.php
```

### Run with Coverage
```bash
vendor/bin/phpunit --coverage-html coverage tests/Feature
```

## Test Coverage

The `EmailSenderTest` class tests:

### ✅ Basic Functionality
- Email sender instantiation
- Email sender with SMTP configuration
- Email template generation
- Email template with default values
- Email template with custom data

### ✅ Email Sending
- Sending emails with template
- Sending to multiple recipients
- Sending with plain text alternative
- Auto-generated plain text from HTML
- Email with attachments

### ✅ Error Handling
- Invalid email address handling
- Error message retrieval

### ✅ Template Structure
- Email template HTML structure validation
- Required elements presence

## Test Environment

**Note:** These tests validate the EmailSender class functionality but may not actually send emails if:
- Mail server is not configured
- SMTP credentials are not provided
- PHP mail() function is disabled

This is expected behavior - the tests verify that:
1. The class methods work correctly
2. Parameters are validated
3. Templates are generated properly
4. Error handling works as expected

## SMTP Configuration for Testing

To test actual email sending, configure SMTP in your test environment:

```php
$smtp_config = [
    'host' => 'smtp.example.com',
    'port' => 587,
    'username' => 'your-email@example.com',
    'password' => 'your-password',
    'encryption' => 'tls',
    'auth' => true
];
```

## Test Data

Tests use example email addresses:
- `test@example.com` - Primary test recipient
- `noreply@footpower.com` - Default sender
- `recipient1@example.com`, `recipient2@example.com` - Multiple recipients

## Expected Results

All tests should pass if:
- PHPMailer is installed via Composer
- EmailSender class is properly loaded
- Template files are accessible

Tests that attempt actual email sending may fail if mail server is not configured, but this is acceptable for unit/feature testing.


