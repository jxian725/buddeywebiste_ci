<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Request extends CI_Controller {
    private $error = array();
    function __construct() {
        parent::__construct();
        $this->load->model( 'Commonmodel' );
        $this->load->model( 'Requestmodel' );
        $this->load->helper('timezone');
    }
	public function index() {
	}
    //Guider Validation
    function guiderValidate() {
        
        //Validate the event form
        $this->form_validation->set_rules( 'first_name', 'First name', 'required' );
        $this->form_validation->set_rules( 'dob', 'DOB', 'required' );
        $this->form_validation->set_rules( 'mobile_no', 'Mobile', 'required' );
        $this->form_validation->set_rules( 'email', 'Email', 'trim|required|valid_email' );
        
        if( $this->form_validation->run() == FALSE ) {
            echo validation_errors();
        } else {
            echo '1';
        }
    }
    function phonenoVerify() {
        //Validate the duration form
        $mobile_no  = $this->input->post( 'mobile_no' );
        echo $this->Commonmodel->phonenoVerify($mobile_no);
    }
    function postCommentValidate() {
        $json_res = array();
        //Validate the event form
        $this->form_validation->set_rules( 'guest_name', 'First name', 'required' );
        $this->form_validation->set_rules( 'guest_email', 'Email', 'trim|required|valid_email' );
        $this->form_validation->set_rules( 'guest_comment', 'Comment', 'required' );
        if( $this->form_validation->run() == FALSE ) {
            //echo validation_errors();
            $json_res = array('Status' => 'error','html' => 'Some problem found.Please try later.');
            echo json_encode($json_res);
        } else {
            $post_date  = date('F d, Y');
            $post_time  = date('g:i a');
            $guider_id  = $this->input->post( 'guider_id' );
            $name       = $this->input->post( 'guest_name' );
            $email      = $this->input->post( 'guest_email' );
            $comment    = $this->input->post( 'guest_comment' );
            $createdon  = date("Y-m-d H:i:s");
            $data       = array(
                            'guider_id'     => $guider_id,
                            'cmt_name'      => $name,
                            'cmt_email'     => $email,
                            'cmt_messge'    => $comment,
                            'createdon'     => $createdon
                            );
            $this->Commonmodel->postComment($data);
            $guestImg   = $this->config->item( 'dir_url' ).'img/avatar.png';
            $html = '<div class="row">
                        <div class="col-md-1 image_circle ico_left">
                            <img src="'.$guestImg.'">
                        </div>
                        <div class="col-md-11">
                            <h5><div>'.$name.'</div><small>'.$post_date.' at '.$post_time.'</small></h5>
                            <p>'.$comment.'</p>    
                        </div>
                    </div>';
            $json_res = array('Status' => 'success','html' => $html);
            echo json_encode($json_res);
        }
    }
    public function verify_guider() {
        error_reporting(0);
        $phoneNumber   = $this->input->post( 'phone_number' );
        $phone        = str_replace('+', '', $phoneNumber);
        $countryCode  = substr($phone, 0, 2);
        $phone_number = substr($phone, 2);
        $phoneExist   = $this->Requestmodel->travellerPhoneExists( trim($phone_number) );
        //print_r($phoneExist);
        if($phoneExist){
            $traveller_id  = $phoneExist->traveller_id;
            $sessionVal = array(
                            'TRAVELLER_ID'  => $traveller_id,
                            'FIRST_NAME'    => $phoneExist->first_name,
                            'LAST_NAME'     => $phoneExist->last_name,
                            'TRAVELLER_EMAIL'=> $phoneExist->email,
                            'COUNTRYCODE'   => $phoneExist->countryCode,
                            'PHONE_NUMBER'  => trim($phoneExist->phone_number),
                            );
              $this->session->set_userdata( $sessionVal );
              $json_res = array('result' => 'success','is_new_user' => 0,'tinfo' => $sessionVal);
              echo json_encode($json_res);
        }else{
            $sessionVal   = array( 
                            'TRAVELLER_ID'  => '',
                            'FIRST_NAME'    => '',
                            'LAST_NAME'     => '',
                            'TRAVELLER_EMAIL'=> '',
                            'COUNTRYCODE'   => '',
                            'PHONE_NUMBER'  => '',
                            );
            $this->session->unset_userdata( $sessionVal );
            $tinfo      = array( 
                            'TRAVELLER_ID'  => '',
                            'FIRST_NAME'    => '',
                            'LAST_NAME'     => '',
                            'TRAVELLER_EMAIL'=> '',
                            'COUNTRYCODE'   => $countryCode,
                            'PHONE_NUMBER'  => trim($phone_number),
                            );
            $json_res = array('result' => 'success','is_new_user' => 1,'tinfo' => $tinfo);
            echo json_encode($json_res);
        }
    }
    function hostRequest(){
        $this->form_validation->set_rules( 'full_name', 'Full name', 'required' );
        $this->form_validation->set_rules( 'mobile_no', 'Mobile', 'required|min_length[6]' );
        $this->form_validation->set_rules( 'email', 'Email', 'trim|required|valid_email' );
        $this->form_validation->set_rules( 'budget', 'Budget', 'required' );
        $this->form_validation->set_rules( 'occasion', 'Occasion', 'required' );
        $this->form_validation->set_rules( 'venue', 'Venue', 'required' );
        if( $this->form_validation->run() == FALSE ) {
            $result = array('res_status' => 'error', 'message' => validation_errors(), 'res_data'=>array('error' => 1));
        } else {
            $data   = array(
                        'full_name'     => trim($this->input->post( 'full_name' )),
                        'countryCode'   => trim($this->input->post( 'countryCode' )),
                        'mobile_no'     => trim($this->input->post( 'mobile_no' )),
                        'email'         => trim($this->input->post( 'email' )),
                        'skill_id'      => trim(base64_decode($this->input->post( 'skill_id' ))),
                        'city_id'       => trim(base64_decode($this->input->post( 'city_id' ))),
                        'budget'        => trim($this->input->post( 'budget' )),
                        'occasion'      => trim($this->input->post( 'occasion' )),
                        'venue'         => trim($this->input->post( 'venue' )),
                        'time_hour'     => trim($this->input->post( 'time_hour' )),
                        'other_info'    => trim($this->input->post( 'other_info' )),
                        'status'        => 0,
                        'createdon'     => date("Y-m-d H:i:s")
                    );
            $request_id = $this->Requestmodel->insertRequest( $data );
            $result = array('res_status' => 'success', 'message' => 'Request submitted.', 'res_data'=>array('request_id' => strtoupper($request_id)));
        }
        echo json_encode($result);
    }
    function serviceRequest() {
        $traveller_id    = $this->input->post( 'traveller_id' );
        $guider_id       = $this->input->post( 'guider_id' );
        $activity_id     = $this->input->post( 'activity_id' );
        $number_of_person= $this->input->post( 'no_person' );
        $pickup_date     = $this->input->post( 'service_date' );
        $pickup_date     = date('Y-m-d',strtotime($pickup_date));
        $pickup_time     = $this->input->post( 'pickup_time' );
        $additional_info = $this->input->post( 'additional_info' );
        $processing_fee = $this->Commonmodel->siteInfo('_processing_fee');
        if(!$processing_fee){ $processing_fee = PROCESSING_FEE; }else{ $processing_fee = $processing_fee->s_value; }
        if(PROCESSING_FEE_ENABLED == 'NO'){ $processing_fee = 0; }
        $activityInfo   = $this->Requestmodel->guiderActivityInfo( $activity_id );
        $guiderInfo     = $this->Requestmodel->guiderInfoById( $guider_id );
        $travellerInfo  = $this->Requestmodel->travellerInfoByUuid( $traveller_id );
        if($this->verify_validate_pickup( $this->input->post() )) {
            if(!$activityInfo){
                $result     = array('res_status' => 'error', 'message' => 'Invalid Activity', 'res_data'=>array('error' => 1));
            }elseif (!$travellerInfo && $traveller_id) {
                $result     = array('res_status' => 'error', 'message' => 'Invalid Traveller ID', 'res_data'=>array('error' => 1));
            }else{
                if(!$traveller_id){
                    $first_name     = $this->input->post( 'first_name' );
                    $last_name      = $this->input->post( 'last_name' );
                    $phone_number   = $this->input->post( 'mobile_no' );
                    $email          = $this->input->post( 'email' );
                    $countryCode   = $this->input->post( 'countryCode' );
                    $data4   = array( 
                                    'first_name'    => $first_name,
                                    'last_name'     => $last_name,
                                    'email'         => $email,
                                    'phone_number'  => $phone_number,
                                    'countryCode'   => $countryCode,
                                    'reg_device_type'=> 1,
                                    'ratings'       => 5,
                                    'created_on'    => date( 'Y-m-d H:i:s' ) 
                                );
                    $traveller_id   = $this->Requestmodel->insertTraveller( $data4 );
                    $travellerInfo  = $this->Requestmodel->travellerInfoByUuid( $traveller_id );
                    //SET SESSION
                    $sessionVal     = array(
                                        'TRAVELLER_ID'  => $traveller_id,
                                        'FIRST_NAME'    => $travellerInfo->first_name,
                                        'LAST_NAME'     => $travellerInfo->last_name,
                                        'TRAVELLER_EMAIL'=> $travellerInfo->email,
                                        'COUNTRYCODE'   => $travellerInfo->countryCode,
                                        'PHONE_NUMBER'  => trim($travellerInfo->phone_number)
                                    );
                    $this->session->set_userdata( $sessionVal );

                    $data1[ 'firstName' ]  = $travellerInfo->first_name;
                    $mailContent    = $this->load->view( 'mail/reg_traveller', $data1, true );
                    //SEND EMAIL NOTIFICATION
                    $mailData       = $this->Requestmodel->registerTraveller($travellerInfo->email,$mailContent);
                    $data5          = array('verify_email' => 1);
                    $this->Requestmodel->updateTravellerByUuid( $data5, $traveller_id );

                }
                //PUSH NOTIFICATION
                $deviceTokenList  = $this->Requestmodel->guiderDeviceTokenList( $guider_id );
                //PUSH NOTIFICATION GUIDER
                if($deviceTokenList){
                    $push_data  = array(
                                        'title'          => 'Host',
                                        'body'           => 'You have a new request from '.$travellerInfo->first_name.' ('.$travellerInfo->country_name.').',
                                        'action'         => 'create_request',
                                        'notificationId' => 1,
                                        'sound'          => 'notification',
                                        'icon'           => 'icon'
                                        );
                    $device_tokenA = array();
                    $device_tokenI = array();
                    foreach ($deviceTokenList as $tokenList) {
                        $device_token   = trim($tokenList->device_token);
                        $device_type    = trim($tokenList->device_type);
                        if ($device_type == 3) {
                            if (strlen($device_token) > 10){
                                $device_tokenA[] = $device_token;
                            }
                        }else if ($device_type == 2) {
                            if (strlen($device_token) > 10){
                                $device_tokenI[] = $device_token;
                            }
                        }
                    }
                    if (!empty($device_tokenA)) {
                      $this->Requestmodel->android_push_notification($device_tokenA, $push_data, 'G');
                    }
                    if (!empty($device_tokenI)) {
                      //$this->Requestmodel->ios_push_notification($device_tokenI, $message);
                    }
                }
                //END PUSH NOTIFICATION
                $booking_request_id = randomRequestID();
                if($activityInfo->price_type_id == 3){
                    $guider_charged     = 0;
                }else{
                    $guider_charged     = $activityInfo->rate_per_person;
                }
                $data       = array(
                                'service_guider_id'     => $guider_id,
                                'booking_request_id'    => strtoupper($booking_request_id),
                                'service_traveller_id'  => $traveller_id,
                                'number_of_person'      => $number_of_person,
                                'service_date'          => $pickup_date,
                                'pickup_time'           => $pickup_time,
                                'additional_information'=> $additional_info,
                                'current_processing_fee'=> $processing_fee,
                                'guider_charged'        => $guider_charged,
                                'guider_currency_symbol'=> $guiderInfo->country_currency_symbol,
                                'service_price_type_id' => $activityInfo->price_type_id,
                                'activity_desc'         => $activityInfo->what_i_offer,
                                'activity_id'           => $activity_id,
                                'createdon'             => date("Y-m-d H:i:s")
                                );
                $request_id     = '';
                $request_id     = $this->Requestmodel->insertService( $data );
                //SEND EMAIL NOTIFICATION
                $mailData       = $this->Requestmodel->requestToGuider($guiderInfo->email,$guiderInfo->first_name,$travellerInfo->first_name);
                $result         = array('res_status' => 'success', 'message' => 'Request submitted. Please check your email or download Buddey Guest App for further information.', 'res_data'=>array('booking_id' => strtoupper($booking_request_id)));
            }
        } else {
            $result     = array('res_status' => 'error', 'message' => $this->error['warning'], 'res_data'=>array('error' => 1));
        }
        echo json_encode($result);
    }
    function verify_validate_pickup( $post ) {
        $pickup_date    = date('Y-m-d',strtotime($this->input->post( 'service_date' )));
        $pickup_date    = strtotime($pickup_date);
        $today          = strtotime(date('Y-m-d'));
        if ( $this->input->post('no_person') == '' ) {
            $this->error['warning']    = 'Number of person Cannot be empty';
        } else if ( $this->input->post('service_date') == '' ){
            $this->error['warning']    = 'Pickup date Cannot be empty';
        } else if ( $pickup_date < $today ){
            $this->error['warning']    = 'Please enter a valid pickup date.';
        } else if ( $this->input->post('pickup_time') == '' ){
            $this->error['warning']    = 'Pickup time Cannot be empty';
        }
        return !$this->error;
    }
    function logout(){
        $this->session->sess_destroy();
        echo json_encode(array('res_status' => 'success'));
    }
	function template( $data ){
        $this->load->view( 'common/templatecontent', $data );
        return true;
    }
}    
    