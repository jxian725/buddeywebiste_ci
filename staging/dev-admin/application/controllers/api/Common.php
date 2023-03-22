<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common extends CI_Controller{

    private $error = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('api/Guiderapimodel');
        $this->load->model('api/Travellerapimodel');
        $this->load->model('api/Serviceapimodel');
        $this->load->model('api/Commonapimodel');
        $this->load->model('api/pushNotificationmodel');
        $this->load->model('api/MailNotificationmodel');
        $this->load->helper('timezone');
        header("content-type:application/json");
    }
    
    public function send_message() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->send_message_validate( $user_input ) ) {
                $msg_receive_user_id    = trim( $user_input['message_receive_user_id'] );
                $msg_post_user_id       = trim( $user_input['message_post_user_id'] );
                $msg_post_user_type     = trim( $user_input['message_post_user_type'] );
                $msg_receive_user_type  = trim( $user_input['message_receive_user_type'] ); //T,G
                $message                = trim( $user_input['message'] );
                $createdon              = date("Y-m-d H:i:s");
                
                if(strtoupper($msg_receive_user_type) == 'T'){
                    //PUSH NOTIFICATION
                    $deviceTokenList1  = $this->Travellerapimodel->travellerDeviceTokenList( $msg_receive_user_id );
                    //PUSH NOTIFICATION GUIDER
                    if($deviceTokenList1){
                        $push_data     = array(
                                        'title'             => 'Guest',
                                        'body'              => 'You have a new message from host.',
                                        'action'            => 'post_message',
                                        'notificationId'    => 8,
                                        'sound'             => 'notification',
                                        'icon'              => 'icon'
                                        );
                        $device_tokenA = array();
                        $device_tokenI = array();
                        foreach ($deviceTokenList1 as $tokenList) {
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
                          $this->pushNotificationmodel->android_push_notification($device_tokenA, $push_data, 'T');
                        }
                        if (!empty($device_tokenI)) {
                          $this->pushNotificationmodel->sendPushNotification_ios($device_tokenI, $push_data, 'T');
                        }
                    }
                    //END PUSH NOTIFICATION
                }
                if(strtoupper($msg_receive_user_type) == 'G'){
                    //PUSH NOTIFICATION
                    $deviceTokenList2  = $this->Guiderapimodel->guiderDeviceTokenList( $msg_receive_user_id );
                    //PUSH NOTIFICATION GUIDER
                    if($deviceTokenList2){
                        $push_data     = array(
                                        'title'             => 'Host',
                                        'body'              => 'You have a new message from guest.',
                                        'action'            => 'post_message',
                                        'notificationId'    => 8,
                                        'sound'             => 'notification',
                                        'icon'              => 'icon'
                                        );
                        $device_tokenA = array();
                        $device_tokenI = array();
                        foreach ($deviceTokenList2 as $tokenList) {
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
                          $this->pushNotificationmodel->android_push_notification($device_tokenA, $push_data, 'G');
                        }
                        if (!empty($device_tokenI)) {
                            $this->pushNotificationmodel->sendPushNotification_ios($device_tokenI, $push_data, 'G');
                        }
                    }
                }
                $data       = array(
                                    'msg_receive_user_id'   => $msg_receive_user_id,
                                    'msg_post_user_id'      => $msg_post_user_id,
                                    'msg_post_user_type'    => $msg_post_user_type,
                                    'msg_receive_user_type' => $msg_receive_user_type,
                                    'message'               => $message,
                                    'createdon'             => $createdon,
                                    'msg_status'            => 1
                                    );
                $result1    = $this->Commonapimodel->insertMessage( $data );
                $result         = array(
                                        'response_code'     => SUCCESS_CODE, 
                                        'response_description' => 'Your message has been sent Successfully.', 
                                        'result'            => 'success',
                                        'data'              => $data );
            } else {
                if( $_SERVER['REQUEST_METHOD'] != 'POST' )  {
                    $result = array( 'message' => 'Undefined Request Method', 'result' => 'error' );
                } else if ( isset( $this->error['warning'] ) ) {
                    $result = array('response_code' => ERROR_CODE, 'response_description' => $this->error['warning'], 'result' => 'error', 'data'=>array('error' => 1));
                }
            }
        } else {
            $result = array('message' => 'No Input Received', 'result' => 'error');
        }
        echo json_encode($result);
    }
    function send_message_validate( $user_input ) {
        if( trim($user_input['message_receive_user_id']) == '' ){
            $this->error['warning']    = 'Receiver ID Cannot be empty.';
        } else if( trim($user_input['message_post_user_id']) == '' ){
            $this->error['warning']    = 'Post ID Cannot be empty.';
        } else if( trim($user_input['message_post_user_type']) == '' ){
            $this->error['warning']    = 'Post User type Cannot be empty.';
        } else if( trim($user_input['message_receive_user_type']) == '' ){
            $this->error['warning']    = 'Receiver User type Cannot be empty.';
        }
        return !$this->error;
    }
    function retrieve_messages() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
            if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
                $cuser_type = trim($user_input['current_user_type']);
                $ruser_type = trim( $user_input['retrieve_user_type'] );
                $ruser_id   = trim($user_input['retrieve_user_id']);
                $puser_type = trim( $user_input['post_user_type'] );
                $puser_id   = trim($user_input['post_user_id']);
                $page_no    = trim( $user_input['page_no'] );
                $page_total = trim( $user_input['page_total'] );
                if(!$page_no){
                    $page_no = 1;
                }
                $page_number    = ($page_no) ? $page_no : 1;
                $offset         = ($page_number  == 1) ? 0 : ($page_number * $page_total) - $page_total;

                $result     = $this->Commonapimodel->retrieveMessageList( $page_total, $offset, $ruser_type, $ruser_id, $puser_type, $puser_id, $cuser_type );
            } else {
                if( $_SERVER['REQUEST_METHOD'] != 'POST' )  {
                    $result = array( 'message' => 'Undefined Request Method', 'result' => 'error' );
                } else if ( isset( $this->error['warning'] ) ) {
                    $result = array('response_code' => ERROR_CODE, 'response_description' => $this->error['warning'], 'result' => 'error', 'data'=>array('error' => 1));
                }
            }
        } else {
            $result = array( 'message' => 'Undefined Request Method', 'result' => 'error' );
        }
        echo json_encode( $result );
    }
    public function send_feedback() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->send_feedback_validate( $user_input ) ) {
                $user_type      = trim( $user_input['user_type'] );
                $service_id     = trim( $user_input['service_id'] );
                $user_id        = trim( $user_input['user_id'] );
                $rating         = trim( $user_input['rating'] );
                $feedback       = trim( $user_input['feedback'] );
                $journeyInfo    = $this->Serviceapimodel->journeyInfo( $service_id );
                $data           = array();
                if(strtoupper($user_type) == 'G'){
                    $guider_id      = $journeyInfo->service_guider_id;
                    $guiderInfo     = $this->Travellerapimodel->guiderInfoById( $guider_id );
                    $ratings        = $guiderInfo->rating;
                    if($ratings){
                        $total      = ($ratings + $rating)/2;
                    }else{
                        $total      = $rating;
                    }
                    $data4          = array('rating' => $total);
                    $result1        = $this->Guiderapimodel->updateGuiderByUuid( $data4, $guider_id );
                    //JOURNEY COMPLETE STATUS
                    $data5          = array('jny_status' => 3);
                    $update         = $this->Serviceapimodel->updateJourneyByServiceid( $data5, $service_id );
                    $data           = array(
                                        'guider_rating'     => $rating,
                                        'guider_feedback'   => $feedback,
                                        'guider_feedbackon' => date("Y-m-d H:i:s")
                                        );
                }else if(strtoupper($user_type) == 'T'){
                    $traveller_id   = $journeyInfo->service_traveller_id;
                    $travellerInfo  = $this->Guiderapimodel->travellerInfoByUuid( $traveller_id );
                    $ratings        = $travellerInfo->ratings;
                    if($ratings){
                        $total      = ($ratings + $rating)/2;
                    }else{
                        $total      = $rating;
                    }
                    $data4          = array('ratings' => $total);
                    $this->Travellerapimodel->updateTravellerByUuid( $data4, $traveller_id );
                    //JOURNEY COMPLETE STATUS
                    $data5          = array('jny_status' => 3);
                    $update         = $this->Serviceapimodel->updateJourneyByServiceid( $data5, $service_id );
                    $data           = array(
                                        'traveller_rating'      => $rating,
                                        'traveller_feedback'    => $feedback,
                                        'traveller_feedbackon'  => date("Y-m-d H:i:s")
                                        );
                }
                $result1    = $this->Serviceapimodel->updateJourney( $data, $service_id );
                $result         = array(
                                        'response_code'     => SUCCESS_CODE, 
                                        'response_description' => 'Your feedback has been sent Successfully.', 
                                        'result'            => 'success',
                                        'data'              => $data );
            } else {
                if( $_SERVER['REQUEST_METHOD'] != 'POST' )  {
                    $result = array( 'message' => 'Undefined Request Method', 'result' => 'error' );
                } else if ( isset( $this->error['warning'] ) ) {
                    $result = array('response_code' => ERROR_CODE, 'response_description' => $this->error['warning'], 'result' => 'error', 'data'=>array('error' => 1));
                }
            }
        } else {
            $result = array('message' => 'No Input Received', 'result' => 'error');
        }
        echo json_encode($result);
    }
    function send_feedback_validate( $user_input ) {
        if( trim($user_input['service_id']) == '' ){
            $this->error['warning']    = 'Service ID Cannot be empty';
        } else if ( !$this->Serviceapimodel->journeyInfo( $user_input['service_id'] ) ){
            $this->error['warning']    = 'Invalid Service ID or journey ID.';
        } else if( trim($user_input['user_id']) == '' ){
            $this->error['warning']    = 'User ID Cannot be empty.';
        } else if( trim($user_input['user_type']) == '' ){
            $this->error['warning']    = 'User Type Cannot be empty.';
        } else if((strtoupper($user_input['user_type']) != 'G') && (strtoupper($user_input['user_type']) != 'T')){
            $this->error['warning']    = 'Invalid User type be empty.';
        }
        return !$this->error;
    }
    public function getAllfeedbacks() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->getfeedback_validate( $user_input ) ) {
                $user_type      = trim( $user_input['user_type'] );
                $user_id        = trim( $user_input['user_id'] );
                if(strtoupper($user_type) == 'G'){
                    $result    = $this->Serviceapimodel->getTravellerFeedback( $user_id );
                }else{
                    $result    = $this->Serviceapimodel->getGuiderAllFeedback( $user_id );
                }
            } else {
                if( $_SERVER['REQUEST_METHOD'] != 'POST' )  {
                    $result = array( 'message' => 'Undefined Request Method', 'result' => 'error' );
                } else if ( isset( $this->error['warning'] ) ) {
                    $result = array('response_code' => ERROR_CODE, 'response_description' => $this->error['warning'], 'result' => 'error', 'data'=>array('error' => 1));
                }
            }
        } else {
            $result = array('message' => 'No Input Received', 'result' => 'error');
        }
        echo json_encode($result);
    }
    function getfeedback_validate( $user_input ) {
        if( trim($user_input['user_type']) == '' ){
            $this->error['warning']    = 'User Type Cannot be empty';
        } else if( trim($user_input['user_id']) == '' ){
            $this->error['warning']    = 'User ID Cannot be empty.';
        }
        return !$this->error;
    }
    public function update_profile_image() {
        $input  = json_decode(file_get_contents("php://input"));
        
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array('message' => 'Authorization error', 'result' => 'error');
        } else if( $input != '' ) {
            $user_input = get_object_vars($input);
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->updatePPvalidate( $user_input ) ) {
                $upload_path_url    = $this->config->item( 'upload_path_url' );
                $upload_path        = $this->config->item( 'upload_path' );
                $user_profile       = trim($user_input['user_profile']);
                $user_id            = trim($user_input['user_id']);
                $user_type          = trim($user_input['user_type']);
                $data_res           = array('success' => 1);
                $image              = $user_profile;
                if( strlen( $user_profile ) > 10 ) {
                    if(strtolower($user_type) == 'guider'){
                        $profileUploadPath  = $upload_path.'g_profile/';
                        $profileImgPath     = $upload_path_url.'g_profile/';
                        $guiderInfo         = $this->Travellerapimodel->guiderInfoById( $user_id );
                        $oldUploadimg1      = ($guiderInfo->profile_image) ? $profileUploadPath.$guiderInfo->profile_image : '';
                        $image_1            = $user_id.'_guider_profile'.time().'.png';
                        $path               = $upload_path .'g_profile/'.$image_1;
                        $profile_url        = $this->base64_to_jpeg( $image, $path );
                        if($image_1){
                            $data           = array( 'profile_image' => $image_1 );
                            $data_res       = array( 'profile_image' => $profileImgPath.$image_1 );
                            $result1        = $this->Guiderapimodel->updateGuiderByUuid( $data, $user_id );
                            if( file_exists( $oldUploadimg1 ) ) {
                                unlink( $oldUploadimg1 );
                            }
                        }
                    }else{
                        $profileUploadPath  = $upload_path.'t_profile/';
                        $profileImgPath     = $upload_path_url.'t_profile/';
                        $travellerInfo      = $this->Travellerapimodel->travellerInfoByUuid( $user_id );
                        $oldUploadimg1      = ($travellerInfo->profile_image) ? $profileUploadPath.$travellerInfo->profile_image : '';
                        $image_1            = $user_id.'_traveller_profile'.time() .'.png';
                        $path               = $upload_path .'t_profile/'.$image_1;
                        $profile_url        = $this->base64_to_jpeg( $image, $path );
                        if($image_1){
                            $data           = array('profile_image' => $image_1);
                            $data_res       = array( 'profile_image' => $profileImgPath.$image_1 );
                            $result1        = $this->Travellerapimodel->updateTravellerByUuid( $data, $user_id );
                            if( file_exists( $oldUploadimg1 ) ) {
                                unlink( $oldUploadimg1 );
                            }
                        }
                    }
                    $result     = array( 'response_code' => SUCCESS_CODE, 'response_description' => 'Profile image updated successfully.', 'result' => 'success', 'data' => $data_res );
                }else{
                    $result     = array( 'response_code' => ERROR_CODE, 'response_description' => 'Operation failed.', 'result' => 'error', 'data' => array( 'error' => 1 ) );
                }
            } else {
                if( $_SERVER['REQUEST_METHOD'] != 'POST' ) {
                    $result = array('message' => 'Undefined Request Method', 'result' => 'error');
                } else if (isset($this->error['warning'])) {
                    $result = array('response_code' => ERROR_CODE, 'response_description' => $this->error['warning'], 'result' => 'error', 'data'=>array( 'error' => 1 ));
                }
            }
        } else {
            $result = array('message' => 'No Input Received', 'result' => 'error');
        }
        echo json_encode($result);
    }
    //Update Validation
    function updatePPvalidate( $user_input ) {
        if( trim($user_input['user_profile']) == '' ){
            $this->error['warning']    = 'User Image Cannot be empty';
        }else if( trim($user_input['user_type']) == '' ){
            $this->error['warning']    = 'User Type Cannot be empty';
        } else if( trim($user_input['user_id']) == '' ){
            $this->error['warning']    = 'User ID Cannot be empty.';
        }
        return !$this->error;
    }
    function getServiceRequest() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
            if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
                $ruser_type = trim( $user_input['request_user_type'] );
                $ruser_id   = trim($user_input['request_user_id']);
                $filtertype = trim( $user_input['filtertype'] );
                $page_no    = trim( $user_input['page_no'] );
                $page_total = trim( $user_input['page_total'] );
                if(!$page_no){
                    $page_no = 1;
                }
                $page_number    = ($page_no) ? $page_no : 1;
                $offset         = ($page_number  == 1) ? 0 : ($page_number * $page_total) - $page_total;
                if(strtoupper($ruser_type) == 'G'){
                    $result     = $this->Commonapimodel->getGuiderServiceRequest( $page_total, $offset, $ruser_id, $filtertype );
                }else if(strtoupper($ruser_type) == 'T'){
                    $result     = $this->Commonapimodel->getTravellerServiceRequest( $page_total, $offset, $ruser_id, $filtertype );
                }else{
                    $result = array('response_code' => ERROR_CODE, 'response_description' => 'Invalid User Type', 'result' => 'error', 'data'=>array('error' => 1));
                }
            } else {
                if( $_SERVER['REQUEST_METHOD'] != 'POST' )  {
                    $result = array( 'message' => 'Undefined Request Method', 'result' => 'error' );
                } else if ( isset( $this->error['warning'] ) ) {
                    $result = array('response_code' => ERROR_CODE, 'response_description' => $this->error['warning'], 'result' => 'error', 'data'=>array('error' => 1));
                }
            }
        } else {
            $result = array( 'message' => 'Undefined Request Method', 'result' => 'error' );
        }
        echo json_encode( $result );
    }
    function getJourneys() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
            if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
                $ruser_type = trim($user_input['request_user_type']);
                $ruser_id   = trim($user_input['request_user_id']);
                $filtertype = trim($user_input['filtertype']);
                $page_no    = trim($user_input['page_no']);
                $page_total = trim($user_input['page_total']);
                if(!$page_no){
                    $page_no = 1;
                }
                $page_number    = ($page_no) ? $page_no : 1;
                $offset         = ($page_number  == 1) ? 0 : ($page_number * $page_total) - $page_total;
                if(strtoupper($ruser_type) == 'G'){
                    $select     = $this->Commonapimodel->updateGuiderJourneyStatus( $ruser_id );
                    $result     = $this->Commonapimodel->getGuiderJourneyList( $page_total, $offset, $ruser_id, $filtertype );
                }else if(strtoupper($ruser_type) == 'T'){
                    $select     = $this->Commonapimodel->updateTravellerJourneyStatus( $ruser_id );
                    $result     = $this->Commonapimodel->getTravellerJourneyList( $page_total, $offset, $ruser_id, $filtertype );
                }else{
                    $result = array('response_code' => ERROR_CODE, 'response_description' => 'Invalid User Type', 'result' => 'error', 'data'=>array('error' => 1));
                }
            } else {
                if( $_SERVER['REQUEST_METHOD'] != 'POST' )  {
                    $result = array( 'message' => 'Undefined Request Method', 'result' => 'error' );
                } else if ( isset( $this->error['warning'] ) ) {
                    $result = array('response_code' => ERROR_CODE, 'response_description' => $this->error['warning'], 'result' => 'error', 'data'=>array('error' => 1));
                }
            }
        } else {
            $result = array( 'message' => 'Undefined Request Method', 'result' => 'error' );
        }
        echo json_encode( $result );
    }
    function GetListOfMessagedUsers() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
            if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
                
                $user_type  = trim( $user_input['user_type'] );
                $user_id    = trim($user_input['user_id']);
                $page_no    = trim( $user_input['page_no'] );
                $page_total = trim( $user_input['page_total'] );
                if(!$page_no){
                    $page_no = 1;
                }
                $page_number = ($page_no) ? $page_no : 1;
                $offset      = ($page_number  == 1) ? 0 : ($page_number * $page_total) - $page_total;
                $result      = $this->Commonapimodel->GetListOfMessagedUsers( $page_total, $offset, $user_type, $user_id );
            } else {
                if( $_SERVER['REQUEST_METHOD'] != 'POST' )  {
                    $result = array( 'message' => 'Undefined Request Method', 'result' => 'error' );
                } else if ( isset( $this->error['warning'] ) ) {
                    $result = array('response_code' => ERROR_CODE, 'response_description' => $this->error['warning'], 'result' => 'error', 'data'=>array('error' => 1));
                }
            }
        } else {
            $result = array( 'message' => 'Undefined Request Method', 'result' => 'error' );
        }
        echo json_encode( $result );
    }
	function getPrivacyPolicy() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else{
            $user_input = get_object_vars( $input );
            if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) {
				$privacypolicyURL = 'http://18.216.41.0/ebuddey-web/index.php/privacypolicy';
				$result = array('response_code' => SUCCESS_CODE, 'response_description' => 'Get URL Successfully.', 'result' => 'success', 'privacypolicy' => $privacypolicyURL);
            } else {
                if( $_SERVER['REQUEST_METHOD'] != 'GET' )  {
                    $result = array( 'message' => 'Undefined Request Method', 'result' => 'error' );
                } else if ( isset( $this->error['warning'] ) ) {
                    $result = array('response_code' => ERROR_CODE, 'response_description' => $this->error['warning'], 'result' => 'error', 'data'=>array('error' => 1));
                }
            }
        }
        echo json_encode( $result );
    }
    public function base64_to_jpeg( $base64_string, $output_file ) {
        $ifp = fopen($output_file, "wb");
        $data = explode(',', $base64_string);
        fwrite($ifp,base64_decode($data[0]));
        fclose($ifp);
        return $output_file;
    }
}
?>