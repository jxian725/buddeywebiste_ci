<?php
$dirUrl  = $this->config->item( 'dir_url' );
?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8"> <!-- utf-8 works for most cases -->
    <meta name="viewport" content="width=device-width"> <!-- Forcing initial-scale shouldn't be necessary -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- Use the latest (edge) version of IE rendering engine -->
    <meta name="x-apple-disable-message-reformatting">  <!-- Disable auto-scale in iOS 10 Mail entirely -->
    <title>Guider Registration</title> <!-- The title tag shows in email notifications, like Android 4.4. -->

    <!-- Web Font / @font-face : BEGIN -->
    <!-- NOTE: If web fonts are not required, lines 10 - 27 can be safely removed. -->

    <!-- Desktop Outlook chokes on web font references and defaults to Times New Roman, so we force a safe fallback font. -->
    <!--[if mso]>
        <style>
            * {
                font-family: sans-serif !important;
            }
        </style>
    <![endif]-->

    <!-- All other clients get the webfont reference; some will render the font and others will silently fail to the fallbacks. More on that here: http://stylecampaign.com/blog/2015/02/webfont-support-in-email/ -->
    <!--[if !mso]><!-->
    <!-- insert web font reference, eg: <link href='https://fonts.googleapis.com/css?family=Roboto:400,700' rel='stylesheet' type='text/css'> -->
    <!--<![endif]-->

    <!-- Web Font / @font-face : END -->

    <!-- CSS Reset : BEGIN -->
    <style>

        /* What it does: Remove spaces around the email design added by some email clients. */
        /* Beware: It can remove the padding / margin and add a background color to the compose a reply window. */
        html,
        body {
            margin: 0 auto !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
        }

        /* What it does: Stops email clients resizing small text. */
        * {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        /* What it does: Centers email on Android 4.4 */
        div[style*="margin: 16px 0"] {
            margin: 0 !important;
        }

        /* What it does: Stops Outlook from adding extra spacing to tables. */
        table,
        td {
            mso-table-lspace: 0pt !important;
            mso-table-rspace: 0pt !important;
        }

        /* What it does: Fixes webkit padding issue. Fix for Yahoo mail table alignment bug. Applies table-layout to the first 2 tables then removes for anything nested deeper. */
        table {
            border-spacing: 0 !important;
            border-collapse: collapse !important;
            table-layout: fixed !important;
            margin: 0 auto !important;
        }
        table table table {
            table-layout: auto;
        }

        /* What it does: Uses a better rendering method when resizing images in IE. */
        img {
            -ms-interpolation-mode:bicubic;
        }

        /* What it does: A work-around for email clients meddling in triggered links. */
        *[x-apple-data-detectors],  /* iOS */
        .unstyle-auto-detected-links *,
        .aBn {
            border-bottom: 0 !important;
            cursor: default !important;
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        /* What it does: Prevents Gmail from displaying a download button on large, non-linked images. */
        .a6S {
           display: none !important;
           opacity: 0.01 !important;
       }
       /* If the above doesn't work, add a .g-img class to any image in question. */
       img.g-img + div {
           display: none !important;
       }

       /* What it does: Prevents underlining the button text in Windows 10 */
        .button-link {
            text-decoration: none !important;
        }

        /* What it does: Removes right gutter in Gmail iOS app: https://github.com/TedGoas/Cerberus/issues/89  */
        /* Create one of these media queries for each additional viewport size you'd like to fix */

        /* iPhone 4, 4S, 5, 5S, 5C, and 5SE */
        @media only screen and (min-device-width: 320px) and (max-device-width: 374px) {
            .email-container {
                min-width: 320px !important;
                background: #fff;
            }
        }
        /* iPhone 6, 6S, 7, 8, and X */
        @media only screen and (min-device-width: 375px) and (max-device-width: 413px) {
            .email-container {
                min-width: 375px !important;
                background: #fff;
            }
        }
        /* iPhone 6+, 7+, and 8+ */
        @media only screen and (min-device-width: 414px) {
            .email-container {
                min-width: 414px !important;
                background: #fff;
            }
        }

    </style>
    <!-- CSS Reset : END -->
    <!-- Reset list spacing because Outlook ignores much of our inline CSS. -->
    <!--[if mso]>
    <style type="text/css">
        ul,
        ol {
            margin: 0 !important;
        }
        li {
            margin-left: 30px !important;
        }
        li.list-item-first {
            margin-top: 0 !important;
        }
        li.list-item-last {
            margin-bottom: 10px !important;
        }
    </style>
    <![endif]-->

    <!-- Progressive Enhancements : BEGIN -->
    <style>

        /* What it does: Hover styles for buttons */
        .button-td,
        .button-a {
            transition: all 100ms ease-in;
        }
        .button-td:hover,
        .button-a:hover {
            background: #555555 !important;
            border-color: #555555 !important;
        }

        /* Media Queries */
        @media screen and (max-width: 600px) {

            .email-container {
                width: 100% !important;
                margin: auto !important;
            }

            /* What it does: Forces elements to resize to the full width of their container. Useful for resizing images beyond their max-width. */
            .fluid {
                max-width: 100% !important;
                height: auto !important;
                margin-left: auto !important;
                margin-right: auto !important;
            }

            /* What it does: Forces table cells into full-width rows. */
            .stack-column,
            .stack-column-center {
                display: block !important;
                width: 100% !important;
                max-width: 100% !important;
                direction: ltr !important;
            }
            /* And center justify these ones. */
            .stack-column-center {
                text-align: center !important;
            }

            /* What it does: Generic utility class for centering. Useful for images, buttons, and nested tables. */
            .center-on-narrow {
                text-align: center !important;
                display: block !important;
                margin-left: auto !important;
                margin-right: auto !important;
                float: none !important;
            }
            table.center-on-narrow {
                display: inline-block !important;
            }

            /* What it does: Adjust typography on small screens to improve readability */
            .email-container p {
                font-size: 17px !important;
            }
        }

    </style>
    <!-- Progressive Enhancements : END -->

    <!-- What it does: Makes background images in 72ppi Outlook render at correct size. -->
    <!--[if gte mso 9]>
    <xml>
        <o:OfficeDocumentSettings>
            <o:AllowPNG/>
            <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
    </xml>
    <![endif]-->

</head>
<!--
    The email background color (#222222) is defined in three places:
    1. body tag: for most email clients
    2. center tag: for Gmail and Inbox mobile apps and web versions of Gmail, GSuite, Inbox, Yahoo, AOL, Libero, Comcast, freenet, Mail.ru, Orange.fr
    3. mso conditional: For Windows 10 Mail
-->
<body width="100%" style="margin: 0; mso-line-height-rule: exactly; background-color: #222222;">
    <center style="width: 100%; background-color: #f5f5f5; text-align: left; padding-top: 40px;">
    <!--[if mso | IE]>
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #222222;">
    <tr>
    <td>
    <![endif]-->

        <!-- Create white space after the desired preview text so email clients don’t pull other distracting text into the inbox preview. Extend as necessary. -->
        <!-- Preview Text Spacing Hack : BEGIN -->
        <div style="display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">
            &zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;
        </div>
        <!-- Preview Text Spacing Hack : END -->

        <!-- Email Header : BEGIN -->
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="600" style="margin: auto;" class="email-container">
            <tr>
                <td style="padding: 20px 0px 0px 0px; text-align: center">
                    <img src="<?=$dirUrl;?>img/logo.png" width="170" height="50" alt="alt_text" border="0" style="height: auto; font-family: sans-serif; font-size: 15px; line-height: 120%; color: #555555;">
                </td>
            </tr>
            <tr>
                <td style="text-align: center; font-family: sans-serif;">
                    <h3 style="font-size: 24px; color: #696666;">Welcome to Buddey</h3>
                </td>
            </tr>
        </table>
        <!-- Email Header : END -->

        <!-- Email Body : BEGIN -->
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="600" style="margin: auto;" class="email-container">

            <!-- 1 Column Text + Button : BEGIN -->
            <tr>
                <td style="background-color: #ffffff; padding: 0px 20px;">
                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tr>
                            <td style="padding: 20px 0px 10px; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555; border-top: 1px solid #ddd; border-bottom: 1px solid #ddd;">
                                <h1 style="margin: 0 0 10px; font-size: 16px; line-height: 125%; color: #8c8888; font-weight: normal; text-align: center;">
                                    Hi <?= $firstName; ?>, thank you for your registration.
                                </h1>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center; font-family: sans-serif;">
                                <h3 style="font-size: 24px; color: #696666;">Before you get started</h3>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <!-- 1 Column Text + Button : END -->

            <!-- Thumbnail Left, Text Right : BEGIN -->
            <tr>
                <td dir="ltr" align="center" valign="top" width="100%" style="padding: 10px; background-color: #ffffff;">
                    <table role="presentation" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <!-- Column : BEGIN -->
                            <td width="33.33%" class="stack-column-center">
                                <table role="presentation" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <td dir="ltr" valign="top" style="padding: 0 10px;">
                                            <img src="<?=$dirUrl;?>img/email/mobileapp.jpg" width="170" height="170" alt="alt_text" border="0" class="center-on-narrow" style="height: auto; background: #dddddd; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555;">
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <!-- Column : END -->
                            <!-- Column : BEGIN -->
                            <td width="66.66%" class="stack-column-center">
                                <table role="presentation" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <td dir="ltr" valign="top" style="font-family: sans-serif; font-size: 15px; line-height: 140%; color: #8c8888; padding: 10px; text-align: left;" class="center-on-narrow">
                                            <h2 style="margin: 0 0 10px 0; font-family: sans-serif; font-size: 18px; line-height: 125%; color: #696666; font-weight: bold;">Mobile Application</h2>
                                            <p style="margin: 0 0 10px 0;">Download the application Buddey Guider</p>
                                            <!-- Button : BEGIN -->
                                          <!-- Button : END -->
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <!-- Column : END -->
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td dir="ltr" align="center" valign="top" width="100%" style="padding: 10px; background-color: #ffffff;">
                    <table role="presentation" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <!-- Column : BEGIN -->
                            <td width="33.33%" class="stack-column-center">
                                <table role="presentation" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <td dir="ltr" valign="top" style="padding: 0 10px;">
                                            <img src="<?=$dirUrl;?>img/email/about-me.jpg" width="170" height="170" alt="alt_text" border="0" class="center-on-narrow" style="height: auto; background: #dddddd; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555;">
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <!-- Column : END -->
                            <!-- Column : BEGIN -->
                            <td width="66.66%" class="stack-column-center">
                                <table role="presentation" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <td dir="ltr" valign="top" style="font-family: sans-serif; font-size: 15px; line-height: 140%; color: #8c8888; padding: 10px; text-align: left;" class="center-on-narrow">
                                            <h2 style="margin: 0 0 10px 0; font-family: sans-serif; font-size: 18px; line-height: 125%; color: #696666; font-weight: bold;">About Me</h2>
                                            <p style="margin: 0 0 10px 0;">
                                                If you haven't yet, tell us more about yourself at your profile.
                                            </p>
                                            <!-- Button : BEGIN -->
                                          <!-- Button : END -->
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <!-- Column : END -->
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td dir="ltr" align="center" valign="top" width="100%" style="padding: 10px; background-color: #ffffff;">
                    <table role="presentation" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <!-- Column : BEGIN -->
                            <td width="33.33%" class="stack-column-center">
                                <table role="presentation" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <td dir="ltr" valign="top" style="padding: 0 10px;">
                                            <img src="<?=$dirUrl;?>img/email/accounts.jpg" width="170" height="170" alt="alt_text" border="0" class="center-on-narrow" style="height: auto; background: #dddddd; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555;">
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <!-- Column : END -->
                            <!-- Column : BEGIN -->
                            <td width="66.66%" class="stack-column-center">
                                <table role="presentation" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <td dir="ltr" valign="top" style="font-family: sans-serif; font-size: 15px; line-height: 140%; color: #8c8888; padding: 10px; text-align: left;" class="center-on-narrow">
                                            <h2 style="margin: 0 0 10px 0; font-family: sans-serif; font-size: 18px; line-height: 125%; color: #696666; font-weight: bold;">Accounts</h2>
                                            <p style="margin: 0 0 10px 0;">Ensure your bank account details are correct to received payments. Bank Account Name (Beneficiary name), Bank Name (e.g Citibank) and Account Number</p>
                                            <!-- Button : BEGIN -->
                                          <!-- Button : END -->
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <!-- Column : END -->
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td dir="ltr" align="center" valign="top" width="100%" style="padding: 10px; background-color: #ffffff;">
                    <table role="presentation" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <!-- Column : BEGIN -->
                            <td width="33.33%" class="stack-column-center">
                                <table role="presentation" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <td dir="ltr" valign="top" style="padding: 0 10px;">
                                            <img src="<?=$dirUrl;?>img/email/service.jpg" width="170" height="170" alt="alt_text" border="0" class="center-on-narrow" style="height: auto; background: #dddddd; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555;">
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <!-- Column : END -->
                            <!-- Column : BEGIN -->
                            <td width="66.66%" class="stack-column-center">
                                <table role="presentation" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <td dir="ltr" valign="top" style="font-family: sans-serif; font-size: 15px; line-height: 140%; color: #8c8888; padding: 10px; text-align: left;" class="center-on-narrow">
                                            <h2 style="margin: 0 0 10px 0; font-family: sans-serif; font-size: 18px; line-height: 125%; color: #696666; font-weight: bold;">Service</h2>
                                            <p style="margin: 0 0 10px 0;">If you haven't yet, tell us about your service or activity information and also how much are you charging for requests.</p>
                                            <!-- Button : BEGIN -->
                                          <!-- Button : END -->
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <!-- Column : END -->
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td dir="ltr" align="center" valign="top" width="100%" style="padding: 10px; background-color: #ffffff;">
                    <table role="presentation" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <!-- Column : BEGIN -->
                            <td width="33.33%" class="stack-column-center">
                                <table role="presentation" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <td dir="ltr" valign="top" style="padding: 0 10px;">
                                            <img src="<?=$dirUrl;?>img/email/photo.jpg" width="170" height="170" alt="alt_text" border="0" class="center-on-narrow" style="height: auto; background: #dddddd; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555;">
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <!-- Column : END -->
                            <!-- Column : BEGIN -->
                            <td width="66.66%" class="stack-column-center">
                                <table role="presentation" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <td dir="ltr" valign="top" style="font-family: sans-serif; font-size: 15px; line-height: 140%; color: #8c8888; padding: 10px; text-align: left;" class="center-on-narrow">
                                            <h2 style="margin: 0 0 10px 0; font-family: sans-serif; font-size: 18px; line-height: 125%; color: #696666; font-weight: bold;">
                                                Photos
                                            </h2>
                                            <p style="margin: 0 0 10px 0;">
                                                If you haven't yet, upload photos about your activity at your profile.
                                            </p>
                                            <!-- Button : BEGIN -->
                                          <!-- Button : END -->
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <!-- Column : END -->
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td dir="ltr" align="center" valign="top" width="100%" style="padding: 10px; background-color: #ffffff;">
                    <table role="presentation" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <!-- Column : BEGIN -->
                            <td width="33.33%" class="stack-column-center">
                                <table role="presentation" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <td dir="ltr" valign="top" style="padding: 0 10px;">
                                            <img src="<?=$dirUrl;?>img/email/support.jpg" width="170" height="170" alt="alt_text" border="0" class="center-on-narrow" style="height: auto; background: #dddddd; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555;">
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <!-- Column : END -->
                            <!-- Column : BEGIN -->
                            <td width="66.66%" class="stack-column-center">
                                <table role="presentation" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <td dir="ltr" valign="top" style="font-family: sans-serif; font-size: 15px; line-height: 140%; color: #8c8888; padding: 10px; text-align: left;" class="center-on-narrow">
                                            <h2 style="margin: 0 0 10px 0; font-family: sans-serif; font-size: 18px; line-height: 125%; color: #696666; font-weight: bold;">Support</h2>
                                            <p style="margin: 0 0 10px 0;">
                                                FB: www.facebook.com/buddeyapp<br/>
                                                Email: <a href="mailto:support@buddey.com">support@buddey.com</a><br/>
                                                Tel: <a href="tel:+6017 4230096">+6017 4230096</a>
                                            </p>
                                            <!-- Button : BEGIN -->
                                          <!-- Button : END -->
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <!-- Column : END -->
                        </tr>
                    </table>
                </td>
            </tr>
            <!-- Thumbnail Left, Text Right : END -->
            <!-- Clear Spacer : END -->

            <!-- 1 Column Text : BEGIN -->
            <tr>
                <td style="background-color: #ffffff;">
                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tr>
                            <td style="padding: 0px 40px; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555;">
                                <img src="<?=$dirUrl;?>img/email/google.jpeg" style="width: 100%;" alt="alt_text" border="0" class="center-on-narrow">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <!-- 1 Column Text : END -->

        </table>
        <!-- Email Body : END -->

        <!-- Email Footer : BEGIN -->
        <table style="border-collapse: collapse; border-spacing: 0; padding: 0;" align="center" width="256" cellspacing="0" cellpadding="0" border="0">
            <tbody><tr>

                <!-- ICON 1 -->
                <td style="margin: 0; padding-top: 20px; padding-left: 10px; padding-right: 10px; border-collapse: collapse; border-spacing: 0;" align="center" valign="middle"><a target="_blank" href="https://raw.githubusercontent.com/konsav/email-templates/" style="text-decoration: none;"><img style="padding: 0; margin: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; border: none; display: inline-block;
                    color: #fff; background: #fafafa;" alt="F" title="Facebook" src="<?=$dirUrl;?>img/email/facebookicon.png" width="35" vspace="0" border="0" hspace="0" height="35"></a></td>
            </tr>
            </tbody>
        </table>
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 600px;">
            <tr>
                <td style="font-family: sans-serif; font-size: 12px; line-height: 140%; text-align: center; color: #888888;">
                    <p>You have received this email because you signed up for Buddey | <a href="">Unsubscribe</a> | <br/>The links in this email will always direct to <a href="">http://www.buddeyapp.com</a><br/>&copy; Buddey Technology <script type="text/javascript">var year = new Date();document.write(year.getFullYear());</script></p>
                    <br><br>
                </td>
            </tr>
        </table>
        <!-- Email Footer : END -->

    <!--[if mso | IE]>
    </td>
    </tr>
    </table>
    <![endif]-->
    </center>
</body>
</html>