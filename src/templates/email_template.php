<?php
/**
 * Email Template
 * Recreates the email structure from the provided DOM paths
 * 
 * @Author: AJ Javadi
 * @Email: amirjavadi25@gmail.com
 * @Date: 2025-01-XX
 * @Description: Email template for PHP email sending
 * 
 * Usage:
 *   $email_html = include 'src/templates/email_template.php';
 *   or use getEmailTemplate() function
 */

/**
 * Get the email template HTML
 * 
 * @param array $data Optional data array with:
 *   - 'image_url' => Image URL (default: provided URL)
 *   - 'list_items' => Array of list items (default: ['David'])
 *   - 'width' => Container width (default: 765px)
 * @return string HTML email template
 */
function getEmailTemplate($data = [])
{
    // Default values
    $image_url = $data['image_url'] ?? 'https://d1a8dioxuajlzs.cloudfront.net/accounts/8014/original/1435862577978-ylgi84uxvpshsemi-a137023729a110c9e70a2bfe2f765847.png?1435862581';
    $list_items = $data['list_items'] ?? ['David'];
    $container_width = $data['width'] ?? '765';
    
    ob_start();
    ?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <meta name="format-detection" content="telephone=no,address=no,email=no,date=no,url=no">
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <title>Email Template</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    <style type="text/css">
        /* Reset styles */
        body, table, td, p, a, li, blockquote {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        table, td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }
        img {
            -ms-interpolation-mode: bicubic;
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }
        /* Prevent iOS blue links */
        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }
        /* Desktop styles */
        @media only screen and (min-width: 600px) {
            .nl-container {
                width: <?php echo $container_width; ?>px !important;
            }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #ffffff; font-family: Arial, sans-serif; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;">
    <!-- Main Container Table -->
    <table role="presentation" class="nl-container" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 0 auto; max-width: <?php echo $container_width; ?>px; background-color: #ffffff;">
        <tbody>
            <tr>
                <td>
                    <!-- Row 1 -->
                    <table role="presentation" class="row row-1" width="100%" cellpadding="0" cellspacing="0" border="0" style="width: 100%;">
                        <tbody>
                            <tr>
                                <td style="width: 100%; padding: 0;">
                                    <!-- Row Content -->
                                    <table role="presentation" class="row-content tack" width="100%" cellpadding="0" cellspacing="0" border="0" style="width: 100%;">
                                        <tbody>
                                            <tr>
                                                <!-- Column 1 -->
                                                <td class="column column-1" width="100%" style="mso-table-lspace: 0; mso-table-rspace: 0; font-weight: 400; text-align: left; padding-bottom: 5px; padding-top: 5px; vertical-align: top; width: 100%;">
                                                    <!-- Image Block -->
                                                    <table role="presentation" class="image_block block-1" width="100%" cellpadding="0" cellspacing="0" border="0" style="width: 100%;">
                                                        <tbody>
                                                            <tr>
                                                                <td class="pad" style="padding: 5px 0;">
                                                                    <div class="alignment" align="center" style="line-height: 10px;">
                                                                        <div style="max-width: 464px;">
                                                                            <img src="<?php echo htmlspecialchars($image_url); ?>" alt="" title="" width="500" height="auto" style="display: block; height: auto; border: 0; width: 100%; max-width: 464px; height: auto;" />
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <!-- Row 2 -->
                    <table role="presentation" class="row row-2" width="100%" cellpadding="0" cellspacing="0" border="0" style="width: 100%;">
                        <tbody>
                            <tr>
                                <td style="width: 100%; padding: 0;">
                                    <!-- Row Content -->
                                    <table role="presentation" class="row-content tack" width="100%" cellpadding="0" cellspacing="0" border="0" style="width: 100%;">
                                        <tbody>
                                            <tr>
                                                <!-- Column 1 -->
                                                <td class="column column-1" width="100%" style="mso-table-lspace: 0; mso-table-rspace: 0; font-weight: 400; text-align: left; padding-bottom: 5px; padding-top: 5px; vertical-align: top; width: 100%;">
                                                    <!-- Text Block -->
                                                    <table role="presentation" class="text_block block-1" width="100%" cellpadding="0" cellspacing="0" border="0" style="width: 100%;">
                                                        <tbody>
                                                            <tr>
                                                                <td class="pad" style="padding: 0 40px;">
                                                                    <div style="font-family: Arial, sans-serif;">
                                                                        <div style="font-size: 14px; line-height: 1.5; color: #000000; font-family: Arial, sans-serif;">
                                                                            <ul style="margin: 0; padding-left: 20px; list-style-type: disc;">
                                                                                <?php foreach ($list_items as $item): ?>
                                                                                <li style="margin: 0 0 10px 0; padding: 0; color: #000000; font-size: 14px; line-height: 21px;"><?php echo htmlspecialchars($item); ?></li>
                                                                                <?php endforeach; ?>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>
    <?php
    return ob_get_clean();
}

// If called directly, output the template with defaults
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    echo getEmailTemplate();
}
?>

