# Email Templates

This directory contains email templates for the FootPower system.

## Files

- `email_template.php` - PHP function-based email template (reusable)
- `email_preview.html` - Standalone HTML preview file for testing

## Usage

### Method 1: Using EmailSender Class (Recommended)

```php
<?php
require_once __DIR__ . '/../src/common/initialization.php';
require_once __DIR__ . '/../src/classes/EmailSender.php';

$email_sender = new EmailSender(
    'noreply@footpower.com',
    'FootPower System',
    'support@footpower.com'
);

$template_data = [
    'image_url' => 'https://example.com/your-image.png',
    'list_items' => ['David', 'John', 'Jane'],
    'width' => '765'
];

$result = $email_sender->sendTemplateEmail(
    'recipient@example.com',
    'Your Email Subject',
    $template_data
);
```

### Method 2: Get Template HTML Directly

```php
<?php
require_once __DIR__ . '/../src/templates/email_template.php';

$html = getEmailTemplate([
    'image_url' => 'https://example.com/image.png',
    'list_items' => ['Item 1', 'Item 2'],
    'width' => '765'
]);

// Use $html as needed (save to file, send via API, etc.)
```

### Method 3: Preview in Browser

Open `email_preview.html` in your browser to see how the email will look.

## Template Parameters

The `getEmailTemplate()` function accepts an array with the following optional parameters:

- `image_url` (string): URL of the image to display. Default: Original image URL from template
- `list_items` (array): Array of list items to display. Default: `['David']`
- `width` (string): Container width in pixels. Default: `'765'`

## Email Structure

The template recreates the following structure:

```
table.nl-container
  └── tbody
      └── tr
          └── td
              ├── table.row.row-1
              │   └── table.row-content.tack
              │       └── td.column.column-1
              │           └── table.image_block.block-1
              │               └── img
              └── table.row.row-2
                  └── table.row-content.tack
                      └── td.column.column-1
                          └── table.text_block.block-1
                              └── ul > li (list items)
```

## Email Client Compatibility

The template uses:
- Table-based layout (required for email clients)
- Inline CSS styles
- MSO conditional comments for Outlook
- Responsive design with media queries
- Proper MIME multipart formatting

## Notes

- All styles are inline for maximum email client compatibility
- The template is designed to work with common email clients (Gmail, Outlook, Apple Mail, etc.)
- Images should be hosted on a public URL (not local files)
- Test emails in multiple clients before sending to users


