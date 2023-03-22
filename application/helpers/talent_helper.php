<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function talent_sessionset() {
    $CI = get_instance();
    if( !$CI->session->userdata( 'TALENT_ID' ) ){ 
        redirect( $CI->config->item('base_url') . 'talent/Login' );
    }else{
      //CHECK SESSION TOKEN
      if($CI->session->userdata( 'TOKEN' )){
        $CI->db->select('guider_id,token');
        $CI->db->where('guider_id', $CI->session->userdata( 'TALENT_ID' ) );
        $query = $CI->db->get('guider');
        $info  = $query->row();
        if($info){
          if($info->token != $CI->session->userdata( 'TOKEN' )){
            redirect( $CI->config->item('base_url') . 'talent/logout' );
          }
        }
      }
      //END CHECK SESSION TOKEN
    }
    return true;
}
function useraccess(){
    $CI = get_instance();
    $guider_id  = $CI->session->userdata( 'TALENT_ID' );
    $talentInfo = $CI->Talentmodel->talentInfo($guider_id);
    if($talentInfo->guider_id != 1){ redirect( $CI->config->item('base_url').'talent/login' ); }
}
function loginNow($phone_number) {
  $CI       = get_instance();
  $datetime = date("Y-m-d H:i:s");

  $query    = "SELECT guider_id, password, first_name, email, phone_number, status
                FROM guider
                WHERE 
                  ( phone_number = ? )
              ";
    $data   = array( $phone_number );         
    $result = $CI->db->query( $query, $data );
    $rows   = $result->num_rows();  
    if( $rows > 0 ) {
        $userInfo  = $result->row();
        if( $userInfo->status == 1 ) {
              $token      = getToken(10);
              $sessionVal = array(
                              'TALENT_ID'       => $userInfo->guider_id,
                              'TALENT_NAME'     => $userInfo->first_name,
                              'TALENT_EMAILID'  => $userInfo->email,
                              'TALENT_MOBILE'   => $userInfo->phone_number,
                              'TOKEN'           => $token
                            );
              $CI->session->set_userdata( $sessionVal );
              
              $update     = "UPDATE guider SET last_login=?, web_active=?, token=? WHERE guider_id=?";
              $data2      = array( $datetime, 1, $token, $userInfo->guider_id );  
              $result2    = $CI->db->query( $update, $data2 );
              $redirect_url = base_url().'talent/forums';
              return json_encode(array('status' => 'success','msg' => 'Login successfully.', 'url' => $redirect_url));
        }else{
            //$CI->session->set_flashdata('err_msg', '<p>Your registration is currently under review, please try again later.</p>');
            return json_encode(array('status' => 'failed', 'msg' => 'Your registration is currently under review, please try again later.'));
        }
    }else{ 
        return json_encode(array('status' => 'failed', 'msg' => 'Invalid credential details'));
    }       
}

// Generate token
function getToken($length){
  $token = "";
  $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
  $codeAlphabet.= "0123456789";
  $max = strlen($codeAlphabet); // edited
  for ($i=0; $i < $length; $i++) {
    $token .= $codeAlphabet[random_int(0, $max-1)];
  }
  return $token;
}
?>
