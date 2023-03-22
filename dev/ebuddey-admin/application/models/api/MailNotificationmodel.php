<?php
if (!defined('BASEPATH'))
exit('No direct script access allowed');

class MailNotificationmodel extends CI_Model{

    function __construct()
    {
        parent::__construct();
    }
    function requestToGuider( $useremail, $toname=false, $fromname=false ) {
        $message    = '<style type="text/css">
                        .custom-p {
                          line-height: 0.5em !important;
                          margin: 0px 0px 10px 0px;
                        }
                    </style>
                    <body style="margin: 0; padding: 0;">
                    <table align="center" border="1" cellpadding="0" cellspacing="0" width="750" style="border-collapse: collapse;">
                        <tr>
                            <td align="center" bgcolor="#3c8dbc" style="padding: 40px 0 30px 0;">
                                <span style="color:#fff;height: 50px;font-size: 20px;line-height: 50px;text-align: center;width: 230px;"><b>BUDDEY</b></span>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 10px;" width="330" align="left" valign="top" bgcolor="#f9f9f9"
                                class="mainbar">
                              <p class="custom-p">Hi '.$toname.',</p><br>
                              <h2 style="text-align: center;">You have a new booking request.</h2>
                              <br>
                                Hope this informs,<br>
                                Admin<br>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" bgcolor="#3c8dbc" style="color:#fff; padding: 15px 0 0px 0;">
                                <p class="custom-p">2017 © ebuddey</p>
                            </td>
                        </tr>
                    </table>
                </body>';
                
        $adminEmail = 'support@buddey.com.my';
        $to         = $useremail;
        $subject    = 'New Booking Request';
        // Always set content-type when sending HTML email
        $headers    = "From: admin@buddey.com.my\r\n";
        $headers    .= "Reply-To: ". strip_tags($adminEmail) . "\r\n";
        $headers    .= "MIME-Version: 1.0\r\n";
        $headers    .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        mail($to, $subject, $message, $headers);
    }
    function registerGuider( $toEmail, $message ) {
                
        $adminEmail = 'support@buddeyapp.com';
        $subject    = 'Welcome to Buddey';
        // Always set content-type when sending HTML email
        $headers    = "From: Buddey Admin <admin@buddeyapp.com>\r\n";
        $headers    .= "Reply-To: ". strip_tags($adminEmail) . "\r\n";
        $headers    .= "MIME-Version: 1.0\r\n";
        $headers    .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        mail($toEmail, $subject, $message, $headers);
    }
    function registerTraveller( $toEmail, $message ) {
                
        $adminEmail = 'support@buddeyapp.com';
        $subject    = 'Welcome to Buddey';
        // Always set content-type when sending HTML email
        $headers    = "From: Buddey Admin <admin@buddeyapp.com>\r\n";
        $headers    .= "Reply-To: ". strip_tags($adminEmail) . "\r\n";
        $headers    .= "MIME-Version: 1.0\r\n";
        $headers    .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        mail($toEmail, $subject, $message, $headers);
    }
}
?>