<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function partner_sessionset() {
    $CI = get_instance();
    if( !$CI->session->userdata( 'PARTNER_ID' ) ){ 
        redirect( $CI->config->item('base_url') . 'Login' );
    }
    return true;
}
function useraccess(){
    $CI = get_instance();
    $venuepartnerId = $CI->session->userdata( 'PARTNER_ID' );
    $partnerInfo   = $CI->Partnermodel->partnerInfo($venuepartnerId);
    if($partnerInfo->venuepartnerId != 1){ redirect( $CI->config->item('base_url').'partner/login' ); }
}
function login_Now($email, $password ) {
  $CI     = get_instance();
  $datetime = date("Y-m-d H:i:s");
  $query  = "SELECT venuepartnerId,password,company_name,email,status
              FROM venue_partners
              WHERE 
                ( email = ? OR company_name = ? )  
             ";
    $data   = array( $email, $email );         
    $result = $CI->db->query( $query, $data );
    $rows   = $result->num_rows();  
    if( $rows > 0 ) {
        $userInfo  = $result->row();
        if( $userInfo->status == 1 ) {
            $currentPass = $CI->encryption->decrypt($userInfo->password);
            if ($currentPass == trim($password)) {
              $sessionVal = array( 
                    'PARTNER_ID'          => $userInfo->venuepartnerId,
                    'PARTNER_NAME'        => $userInfo->company_name,
                    'USER_EMAILID'        => $userInfo->email,
                    'USER_NOTIFICATIONS'  => 'TRUE' );
              $CI->session->set_userdata( $sessionVal );
              $update   = "UPDATE venue_partners SET last_login=?,web_active=? WHERE venuepartnerId=?";
              $data2    = array( $datetime,1, $userInfo->venuepartnerId );  
              $result2  = $CI->db->query( $update, $data2 );
              return 1;
            } else {
              //$CI->session->set_flashdata('err_msg', '<p>Invalid credential details.</p>');
              return '<p>Invalid Password.</p>';
            }
        }else{
            //$CI->session->set_flashdata('err_msg', '<p>Your account not Activated.</p>');
            return '<p>Your account not Activated.</p>';
        }
    }else{ 
        //$CI->session->set_flashdata('err_msg', '<p>Invalid credential details.</p>');
        return '<p>Invalid credential details.</p>'; 
    }       
}
 // Forgot Password
 function forgotpassword( $email ) {
    $CI     = get_instance();
    $query  = "SELECT venuepartnerId, company_name, password,email,status
              FROM venue_partners
              WHERE 
                ( email = ? )  
             ";
    $data   = array( $email );         
    $result = $CI->db->query( $query, $data );
    $rows   = $result->num_rows();   
    if( $rows > 0 ) {
        $partnerInfo  = $result->row();
        $name         = $partnerInfo->company_name;
        $Pass         = $CI->encryption->decrypt($partnerInfo->password);
        $message      = '<style type="text/css">
                        .custom-p {
                          line-height: 0.5em !important;
                          margin: 0px 0px 10px 0px;
                        }
                    </style>
                    <body style="margin: 0; padding: 0;">
                    <table align="center" border="1" cellpadding="0" cellspacing="0" width="750" style="border-collapse: collapse;">
                        <tr>
                            <td align="center" bgcolor="#3c8dbc" style="padding: 40px 0 30px 0;">
                                <span style="color:#fff;height: 50px;font-size: 20px;line-height: 50px;text-align: center;width: 230px;"><b>COMPANY NAME</b></span>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 10px;" width="330" align="left" valign="top" bgcolor="#f9f9f9"
                                class="mainbar">
                              <h2 style="text-align: center;">Forgot Password</h2>
                              <p class="custom-p">Hi, ' . $name . '</p><br>
                              <p class="custom-p">Your Password ' . $Pass . '</p>
                              <br>
                                Regards,<br>
                                Admin<br>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" bgcolor="#3c8dbc" style="color:#fff; padding: 15px 0 0px 0;">
                                <p class="custom-p">2017 © midascom.com.my</p>
                            </td>
                        </tr>
                    </table>
                </body>';
        $adminEmail = 'support@midascom.com.my';
        $to      = $email;
        $subject = 'Forgot Password';
        // Always set content-type when sending HTML email
        $headers  = "From: contactus@midascom.com.my\r\n";
        $headers .= "Reply-To: ". strip_tags($adminEmail) . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        if(mail($to, $subject, $message, $headers)){
            echo 1;
        }else{
            echo 2;
        }
    }else{ echo 3; }
}   
?>
