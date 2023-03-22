<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function host_sessionset() {
    $CI = get_instance();
    if( $CI->session->userdata( 'HOST_ID' ) ) {}
    else { redirect( $CI->config->item( 'admin_url' ) . 'hostPortal/login' ); }
    return true;
}
function hostaccess(){
    $CI = get_instance();
    $host_id    = $CI->session->userdata( 'HOST_ID' );
    $userInfo   = $CI->Loginmodel->userInfo( $host_id );
    if( $userInfo->account_type != 1 ){ redirect( $CI->config->item( 'admin_url' ).'hostPortal' ); }
}
//Login
function hostLoginNow( $phone_number, $pass ) {
    $CI     = get_instance();
    $query  = "SELECT guider_id, first_name, last_name, password, phone_number, email, status
              FROM guider
              WHERE 
                ( phone_number = ? AND status != ? )
             ";
    $data   = array( $phone_number, 4 );
    $result = $CI->db->query( $query, $data );
    $rows   = $result->num_rows();   
    if( $rows > 0 ) {
        $hostInfo   = $result->row();
        $dbPass   = $CI->encryption->decrypt($hostInfo->password);
        if ($dbPass == $pass) {
          $sessionVal = array( 'HOST_ID' => $hostInfo->guider_id
            , 'HOST_NAME' => $hostInfo->first_name, 'HOST_EMAIL' => $hostInfo->email, 'SHOW_NOTIFICATIONS' => 'TRUE' );
          $CI->session->set_userdata( $sessionVal );
          $data2 = array('web_active' => 1);
          $CI->db->where( 'guider_id', $hostInfo->guider_id );
          $CI->db->update( 'guider', $data2 );
          return 1;
        } else {
          $CI->session->set_flashdata('err_msg', '<p>Invalid credential details.</p>');
          return '<p>Invalid credential details.</p>';
        }
    }else{
        $CI->session->set_flashdata('err_msg', '<p>Invalid credential details.</p>');
        return '<p>Invalid credential details.</p>'; 
    }       
}
function hostServicesFee($price_type_id,$amount,$number_of_person,$processingFeesType,$processingFeesValue){
  if($processingFeesValue == ''){ $processingFeesValue = PROCESSING_FEES_VALUE; }
  $hostFees   = 0;
  if($price_type_id == 1){ //price per person
    $total   = $amount * $number_of_person;
    if($processingFeesType == 1){ //default
      $hostFees = $processingFeesValue;
    }elseif ($processingFeesType == 2) { //percentage
      $hostFees = ($total / 100) * $processingFeesValue;
    }elseif ($processingFeesType == 3) { //fixed
      $hostFees = $processingFeesValue;
    }elseif ($processingFeesType == 0) {
      $hostFees = $processingFeesValue;
    }
    return number_format((float)$hostFees, 2, '.', '');
  }elseif ($price_type_id == 2) { //price per booking
    $total   = $amount;
    if($processingFeesType == 1){ //default
      $hostFees = $processingFeesValue;
    }elseif ($processingFeesType == 2) { //percentage
      $hostFees = ($total / 100) * $processingFeesValue;
    }elseif ($processingFeesType == 3) { //fixed
      $hostFees = $processingFeesValue;
    }elseif ($processingFeesType == 0) {
      $hostFees = $processingFeesValue;
    }
    return number_format((float)$hostFees, 2, '.', '');
  }elseif ($price_type_id == 3) { //free
    return '';
  }
}
