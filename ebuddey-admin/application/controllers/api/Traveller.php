<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Traveller extends CI_Controller{

    private $error = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('api/Travellerapimodel');
        $this->load->model('api/Guiderapimodel');
        $this->load->model('api/Serviceapimodel');
        $this->load->model('api/Commonapimodel');
        $this->load->model('api/pushNotificationmodel');
        $this->load->model('api/MailNotificationmodel');
        $this->load->helper('timezone');
        header("content-type:application/json");
    }
    public function index() {
        
    }
    public function verify_traveller() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->verify_validate( $user_input ) ) {
                $email          = trim( $user_input['email'] );
                $phone_number   = trim( $user_input['phone_number'] );
                $country_code   = trim( $user_input['country_code'] );
                $country_code   = str_replace("+", "", $country_code);
                $phoneExist     = $this->Travellerapimodel->travellerPhoneExists( $phone_number );
                if ( $phoneExist ){
                    $traveller_id   = $phoneExist->traveller_id;
                    $emailExist     = $this->Travellerapimodel->travellerPhoneEmailExists( $phone_number, $email );
                    if($phoneExist->status == 4){
                        $data1      = array( 'email' => $email, 'phone_number' => $phone_number, 'status'=> 1 );
                    }else{
                        $data1      = array( 'email' => $email, 'phone_number' => $phone_number );
                    }
                    $this->Travellerapimodel->updateTravellerByUuid( $data1, $traveller_id );
                    $updateDevice   = $this->Travellerapimodel->updateDeviceInfo( $_SERVER, $traveller_id );
                    $is_new_user = 0;
                } else {
                    $data           = array( 
                                        'email'         => $email, 
                                        'phone_number'  => $phone_number,
                                        'countryCode'   => $country_code,
                                        'ratings'       => 5,
                                        'created_on'    => date( 'Y-m-d' ) );
                    $traveller_id   = $this->Travellerapimodel->insertTraveller( $data );
                    $insert1        = $this->Travellerapimodel->insertDeviceInfo( $_SERVER, $traveller_id );
                    $is_new_user    = 1;
                }
                $travellerInfo      = $this->Travellerapimodel->travellerPhoneExists( $phone_number );
                $upload_path_url    = $this->config->item( 'upload_path_url' );
                $profileImgPath     = $upload_path_url.'t_profile/';
                $activityImgPath    = $upload_path_url.'t_activity/';
                $profile_image      = ($travellerInfo->profile_image)?(filter_var($travellerInfo->profile_image, FILTER_VALIDATE_URL) === FALSE) ? $profileImgPath.$travellerInfo->profile_image : $travellerInfo->profile_image : '';
                $photo              = ($travellerInfo->photo)?(filter_var($travellerInfo->photo, FILTER_VALIDATE_URL) === FALSE) ? $activityImgPath.$travellerInfo->photo : $travellerInfo->photo : '';
                $photo1             = ($travellerInfo->photo1)?(filter_var($travellerInfo->photo1, FILTER_VALIDATE_URL) === FALSE) ? $activityImgPath.$travellerInfo->photo1 : $travellerInfo->photo1 : '';
                $photo2             = ($travellerInfo->photo2)?(filter_var($travellerInfo->photo2, FILTER_VALIDATE_URL) === FALSE) ? $activityImgPath.$travellerInfo->photo2 : $travellerInfo->photo2 : '';
                if($travellerInfo->age == '0000-00-00'){
                    $age    = 0;
                }else{
                    $age    = date_diff(date_create($travellerInfo->age), date_create('today'))->y;
                }
                $lang = [];
                if($travellerInfo->languages_known){
                    $array =  explode(',', $travellerInfo->languages_known);
                    foreach ($array as $item) {
                        $langInfo = $this->Travellerapimodel->travellerLangInfo($item);
                        if($langInfo){ $lang[] = $langInfo->language; }
                    }
                }
                $profilePer = 0;
                if( $travellerInfo->first_name == '' || $travellerInfo->last_name == '' || $travellerInfo->gender == 0 || $travellerInfo->age == '0000-00-00' ||
                 $travellerInfo->about_me == '' || $travellerInfo->languages_known == '' || $travellerInfo->city == '' ) {
                    $profile_updated    = 0;
                } else {
                    $profile_updated    = 1;
                }
                if( $travellerInfo->first_name == '' || $travellerInfo->last_name == '' || $travellerInfo->gender == 0 || $travellerInfo->age == '0000-00-00') {
                    $is_bpu    = 0;
                } else {
                    $is_bpu    = 1;
                    $profilePer += 35;
                }
                if( $travellerInfo->profile_image == '') {
                    $is_profile_pic_updated = 0;
                } else {
                    $is_profile_pic_updated = 1;
                    $profilePer             += 35;
                }
                if($travellerInfo->languages_known) { $profilePer += 15; }
                if($travellerInfo->about_me) { $profilePer += 10; }
                if($travellerInfo->city) { $profilePer += 5; }
                $serviceCount   = $this->Travellerapimodel->travellerServiceCount($travellerInfo->traveller_id);
                $serviceIDs     = $this->Travellerapimodel->travellerServiceIDs($travellerInfo->traveller_id);
                $messageCount   = $this->Travellerapimodel->travellerMessageCount($travellerInfo->traveller_id);
                $commentCount1  = $this->Travellerapimodel->feedbackTotalCount('T',$userInfo->traveller_id);
                $commentCount2  = $this->Travellerapimodel->commentTotalCount('T',$userInfo->traveller_id);
                $commentCount3  = $this->Travellerapimodel->webcommentTotalCount('T',$userInfo->traveller_id);
                $commentCount   = $commentCount1 + $commentCount2 + $commentCount3;
                $res_data       = array(
                                    'traveller_id'  => intval($travellerInfo->traveller_id),
                                    't_id'          => $travellerInfo->t_id,
                                    'first_name'    => $travellerInfo->first_name,
                                    'last_name'     => $travellerInfo->last_name,
                                    'email'         => $travellerInfo->email,
                                    'phone_number'  => $travellerInfo->phone_number,
                                    'country_code'  => $travellerInfo->countryCode,
                                    'city'          => $travellerInfo->city,
                                    'languages_known'=> $lang,
                                    "gender"        => intval($travellerInfo->gender),
                                    'age'           => $age,
                                    'DOB'           => $travellerInfo->age,
                                    'about_me'      => $travellerInfo->about_me,
                                    'rating_strength' => floatval($travellerInfo->ratings),
                                    'profile_strength'=> $profilePer,
                                    'photo'         => $photo,
                                    'photo1'        => $photo1,
                                    'photo2'        => $photo2,
                                    'profile_pic'   => $profile_image,
                                    'service_count' => intval($serviceCount),
                                    'message_count' => intval($messageCount),
                                    'service_ids'   => $serviceIDs,
                                    'device_type'   => '',
                                    'app_version'   => '',
                                    'device_brand'  => '',
                                    'device_model'  => '',
                                    'device_OS_version' => '',
                                    'device_id'     => '',
                                    'created_on'    => $travellerInfo->created_on,
                                    'status'        => intval($travellerInfo->status),
                                    'comment_count' => intval($commentCount)
                                    );
                $result     = array(
                                    'response_code'     => SUCCESS_CODE, 
                                    'response_description' => 'Your '.GUEST_NAME.' account has been successfully created.', 
                                    'result'            => 'success',
                                    "is_basic_profile_updated"  => $is_bpu,
                                    "is_full_profile_updated"   => $profile_updated,
                                    "is_profile_pic_updated"    => $is_profile_pic_updated,
                                    "is_new_user"               => $is_new_user,
                                    'data'                      => $res_data
                                    );
            } else {
                if( $_SERVER['REQUEST_METHOD'] != 'POST' )  {
                    $result = array( 'message' => 'Undefined Request Method', 'result' => 'error' );
                } else if ( isset( $this->error['warning'] ) ) {
                    $result = array('response_code' => ERROR_CODE, 'response_description' => $this->error['warning'], 'result' => 'error', 'data'=>array( 'error' => 1 ));
                }
            }
        } else {
            $result = array('message' => 'No Input Received', 'result' => 'error');
        }
        echo json_encode($result);
    }
    //Validation here
    private function verify_validate( $user_input ) {
        
        if ( $user_input['email'] == '' ){
            $this->error['warning']    = 'Email Cannot be empty';
        } else if ( ( ( strlen( $user_input['email'] ) ) > 96 ) || !preg_match( '/^[^\@]+@.*.[a-z]{2,15}$/i', $user_input['email'] ) ) {
            $this->error['warning'] = "Invalid Email Address.";
        } else if ( strlen( $user_input['phone_number'] ) == '' ) {
            $this->error['warning']    = 'Phone Number Cannot be empty';
        } else if ( (strlen( $user_input['phone_number'] ) < 8) || (strlen( $user_input['phone_number'] ) > 13) ){
            $this->error['warning']    = 'Phone Number Length between 8-13 characters';
        } else if ( strlen( $user_input['country_code'] ) == '' ) {
            $this->error['warning']    = 'Country Code Cannot be empty';
        }
        return !$this->error;
    }
    //Edit Requestor info //updateprofile
    public function update_traveller_profile() {
        $input  = json_decode(file_get_contents("php://input"));
        
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array('message' => 'Authorization error', 'result' => 'error');
        } else if( $input != '' ) {
            $user_input = get_object_vars($input);
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->updatevalidate( $user_input ) ) {
                $traveller_id   = trim($user_input['traveller_id']);
                $travellerInfo  = $this->Travellerapimodel->travellerInfoByUuid( $traveller_id );
                $first_name     = trim( $user_input['first_name'] );
                $last_name      = trim( $user_input['last_name'] );
                $about_me       = trim( $user_input['about_me'] );
                $DOB            = trim( $user_input['DOB'] );
                $gender         = trim( $user_input['gender'] );
                $city           = trim( $user_input['city'] );
                $email          = trim( $user_input['email'] );
                $knownLanguage  = $user_input['languages_known'];
                $knownLanguage  = implode(',', $knownLanguage);
                $profile_pic    = trim( $user_input['profile_pic'] );
                $app_version    = trim( $user_input['app_version'] );
                $device_id      = trim( $user_input['device_id'] );
                
                $data   = array( 
                            'first_name' => $first_name, 
                            'last_name'  => $last_name, 
                            'city'       => $city,
                            'gender'     => $gender, 
                            'about_me'   => $about_me, 
                            'age'        => $DOB, 
                            'languages_known' => $knownLanguage,
                            'app_version'     => $app_version
                            );
                if(preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $email)){ $data['email'] = $email; }
                $result1        = $this->Travellerapimodel->updateTravellerByUuid( $data, $traveller_id );
                $updateDevice   = $this->Travellerapimodel->updateDeviceInfo( $_SERVER, $traveller_id );

                $travellerInfo      = $this->Travellerapimodel->travellerInfoByUuid( $traveller_id );
                if($travellerInfo->verify_email == 0){
                    $data1[ 'firstName' ]  = $travellerInfo->first_name;
                    $mailContent    = $this->load->view( 'mail/reg_traveller', $data1, true );
                    $mailData       = $this->MailNotificationmodel->registerTraveller($travellerInfo->email,$mailContent);
                    $data5          = array('verify_email' => 1);
                    $this->Travellerapimodel->updateTravellerByUuid( $data5, $traveller_id );
                }
                $upload_path_url    = $this->config->item( 'upload_path_url' );
                $profileImgPath     = $upload_path_url.'t_profile/';
                $activityImgPath    = $upload_path_url.'t_activity/';
                $profile_image      = ($travellerInfo->profile_image)?(filter_var($travellerInfo->profile_image, FILTER_VALIDATE_URL) === FALSE) ? $profileImgPath.$travellerInfo->profile_image : $travellerInfo->profile_image : '';
                $photo              = ($travellerInfo->photo)?(filter_var($travellerInfo->photo, FILTER_VALIDATE_URL) === FALSE) ? $activityImgPath.$travellerInfo->photo : $travellerInfo->photo : '';
                $photo1             = ($travellerInfo->photo1)?(filter_var($travellerInfo->photo1, FILTER_VALIDATE_URL) === FALSE) ? $activityImgPath.$travellerInfo->photo1 : $travellerInfo->photo1 : '';
                $photo2             = ($travellerInfo->photo2)?(filter_var($travellerInfo->photo2, FILTER_VALIDATE_URL) === FALSE) ? $activityImgPath.$travellerInfo->photo2 : $travellerInfo->photo2 : '';
                if($travellerInfo->age == '0000-00-00'){
                    $age    = 0;
                }else{
                    $age    = date_diff(date_create($travellerInfo->age), date_create('today'))->y;
                }
                $lang = [];
                if($travellerInfo->languages_known){
                    $array =  explode(',', $travellerInfo->languages_known);
                    foreach ($array as $item) {
                        $langInfo = $this->Travellerapimodel->travellerLangInfo($item);
                        if($langInfo){ $lang[] = $langInfo->language; }
                    }
                }
                $profilePer = 0;
                if( $travellerInfo->first_name == '' || $travellerInfo->last_name == '' || $travellerInfo->gender == 0 || $travellerInfo->age == '0000-00-00' ||
                 $travellerInfo->about_me == '' || $travellerInfo->languages_known == '' || $travellerInfo->city == '' ) {
                    $profile_updated    = 0;
                } else {
                    $profile_updated    = 1;
                }
                if( $travellerInfo->first_name == '' || $travellerInfo->last_name == '' || $travellerInfo->gender == 0 || $travellerInfo->age == '0000-00-00') {
                    $is_bpu    = 0;
                } else {
                    $is_bpu    = 1;
                    $profilePer += 35;
                }
                if( $travellerInfo->profile_image == '') {
                    $is_profile_pic_updated = 0;
                } else {
                    $is_profile_pic_updated = 1;
                    $profilePer             += 35;
                }
                if($travellerInfo->languages_known) { $profilePer += 15; }
                if($travellerInfo->about_me) { $profilePer += 10; }
                if($travellerInfo->city) { $profilePer += 5; }
                $serviceCount   = $this->Travellerapimodel->travellerServiceCount($travellerInfo->traveller_id);
                $serviceIDs     = $this->Travellerapimodel->travellerServiceIDs($travellerInfo->traveller_id);
                $messageCount   = $this->Travellerapimodel->travellerMessageCount($travellerInfo->traveller_id);
                $commentCount1  = $this->Travellerapimodel->feedbackTotalCount('T',$travellerInfo->traveller_id);
                $commentCount2  = $this->Travellerapimodel->commentTotalCount('T',$travellerInfo->traveller_id);
                $commentCount3  = $this->Travellerapimodel->webcommentTotalCount('T',$travellerInfo->traveller_id);
                $commentCount   = $commentCount1 + $commentCount2 + $commentCount3;
                $res_data       = array(
                                    'traveller_id'  => intval($travellerInfo->traveller_id),
                                    't_id'          => $travellerInfo->t_id,
                                    'first_name'    => $travellerInfo->first_name,
                                    'last_name'     => $travellerInfo->last_name,
                                    'email'         => $travellerInfo->email,
                                    'phone_number'  => $travellerInfo->phone_number,
                                    'country_code'  => $travellerInfo->countryCode,
                                    'city'          => $travellerInfo->city,
                                    'languages_known'=> $lang,
                                    "gender"        => intval($travellerInfo->gender),
                                    'age'           => $age,
                                    'DOB'           => $travellerInfo->age,
                                    'about_me'      => $travellerInfo->about_me,
                                    'rating_strength' => floatval($travellerInfo->ratings),
                                    'profile_strength'=> $profilePer,
                                    'photo'         => $photo,
                                    'photo1'        => $photo1,
                                    'photo2'        => $photo2,
                                    'profile_pic'   => $profile_image,
                                    'service_count' => intval($serviceCount),
                                    'message_count' => intval($messageCount),
                                    'service_ids'   => $serviceIDs,
                                    'device_type'   => '',
                                    'app_version'   => '',
                                    'device_brand'  => '',
                                    'device_model'  => '',
                                    'device_OS_version' => '',
                                    'device_id'     => '',
                                    'created_on'    => $travellerInfo->created_on,
                                    'status'        => intval($travellerInfo->status),
                                    'comment_count' => intval($commentCount)
                                    );
                //If Condition for Active and Inactive
                if( $travellerInfo->status == 2 ) {
                    $msg     = GUEST_NAME.' profile updated successfully and your account is inactive.';
                } else {
                    $msg     = GUEST_NAME.' profile updated successfully.';
                }
                $is_new_user = 0;
                $result     = array(
                                    'response_code'     => SUCCESS_CODE, 
                                    'response_description' => $msg, 
                                    'result'            => 'success',
                                    "is_basic_profile_updated"  => $is_bpu,
                                    "is_full_profile_updated"   => $profile_updated,
                                    "is_profile_pic_updated"    => $is_profile_pic_updated,
                                    "is_new_user"               => $is_new_user,
                                    'data'                      => $res_data
                                    );
                
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
    function updatevalidate( $user_input ) {
        $year       = date('Y', strtotime($user_input['DOB']));
        $current    = date('Y', strtotime(date('Y-m-d')));
        $age        = $current - $year;
        if( trim($user_input['traveller_id']) == '' ){
            $this->error['warning']    = GUEST_NAME.' ID Cannot be empty';
        } else if ( !$this->Travellerapimodel->travellerInfoByUuid( $user_input['traveller_id'] ) ){
            $this->error['warning']    = 'Invalid '.GUEST_NAME.' ID.';
        } else if ( trim( $user_input['first_name'] ) == '' ) {
            $this->error['warning']    = 'First Name Cannot be empty';
        } else if (strlen( $user_input['first_name'] ) < 3 || (strlen( $user_input['first_name'] ) > 40)){
            $this->error['warning']    = 'First Name Minimum 3 & maximum 40 characters';
        } else if ( trim( $user_input['last_name'] ) == '' ){
            $this->error['warning']    = 'Last Name Cannot be empty';
        } else if ( strlen( $user_input['last_name'] ) < 3 || (strlen( $user_input['last_name'] ) > 40)){
            $this->error['warning']    = 'Last Name Minimum 3 & maximum 40 characters';
        }/* else if ( trim( $user_input['gender'] ) == '' || trim( $user_input['gender'] ) == 0 ){
            $this->error['warning']    = 'Gender Cannot be empty';
        } else if ( intval($user_input['gender']) != 1 && intval($user_input['gender']) != 2 ){
            $this->error['warning']    = 'Invalid Gender type';
        } else if ( trim( $user_input['DOB'] ) == '' || ( $user_input['DOB'] ) == '0000-00-00' ){
            $this->error['warning']    = 'Date of Birth Cannot be empty';
        } else if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",trim( $user_input['DOB'] ))) {
            $this->error['warning']    = 'Invalid Date format';
        } else if ( $age < 13 ){
            $this->error['warning']    = 'Minimum age required 13 years old.';
        }*/
        if (trim( $user_input['about_me'] )){
            if ( strlen( $user_input['about_me'] ) < 10 ){
                $this->error['warning']    = 'About Me minimum Length 10 characters';
            }
        }
        if (trim( $user_input['city'] )){
            if (strlen( $user_input['city'] ) < 3 || (strlen( $user_input['city'] ) > 40)){
                $this->error['warning']    = 'City Length Minimum 3 & maximum 40 characters';
            }
        }
        return !$this->error;
    }
    //Search nearby city for Guider List/Get activity list
    function get_guider_list() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->getGuiderListValidate( $user_input ) ) {
                $page_no    = trim( $user_input['page_no'] );
                $page_total = trim( $user_input['page_total'] );
                if(!$page_no){
                    $page_no = 1;
                }
                $page_number    = ($page_no) ? $page_no : 1;
                $offset         = ($page_number  == 1) ? 0 : ($page_number * $page_total) - $page_total;

                $service_providing_region= trim( $user_input['service_providing_region'] );
                $region                 = $user_input['region'];
                $speciality_category    = $user_input['speciality_category'];
                $what_i_offer           = trim( $user_input['what_i_offer'] );
                $country_code           = trim( $user_input['country_code'] );
                $traveller_id           = trim($user_input['traveller_id']);
                $travellerInfo  = $this->Travellerapimodel->travellerInfoByUuid( $traveller_id );
                if(!$country_code){ $country_code = $travellerInfo->countryCode; }
                $result         = $this->Travellerapimodel->getGuiderActivityLists( $page_total, $offset, $service_providing_region, $speciality_category, $what_i_offer, $country_code, $region );
                $updateDevice   = $this->Travellerapimodel->updateDeviceInfo( $_SERVER, $traveller_id );
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
    function getGuiderListValidate( $user_input ) {
        if( trim($user_input['traveller_id']) == '' ){
            $this->error['warning']    = ''.GUEST_NAME.' ID Cannot be empty';
        } else if ( !$this->Travellerapimodel->travellerInfoByUuid( $user_input['traveller_id'] ) ){
            $this->error['warning']    = 'Invalid '.GUEST_NAME.' ID.';
        }
        return !$this->error;
    }
    public function validate_email() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->validateEmail( $user_input ) ) {
                $email          = trim( $user_input['email'] );
                $phone_number   = trim( $user_input['phone_number'] );
                $country_code   = trim( $user_input['country_code'] );

                $phoneExist     = $this->Travellerapimodel->travellerEmailExists( $email );
                if($phoneExist){
                    if($phoneExist->phone_number == $phone_number){
                        $result = array('response_code' => SUCCESS_CODE, 'response_description' => 'This is valid email address.', 'result' => 'success', 'data'=>array('success' => 1));
                    }else{
                        $result = array('response_code' => ERROR_CODE, 'response_description' => 'The email address is already registered with another account.', 'result' => 'error', 'data'=>array('error' => 1));
                    }
                }else{
                    $result = array('response_code' => SUCCESS_CODE, 'response_description' => 'This is valid email address.', 'result' => 'success', 'data'=>array('success' => 1));
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
    private function validateEmail( $user_input ) {
        if ( $user_input['email'] == '' ){
            $this->error['warning']    = 'Email Cannot be empty';
        }else if ( ( ( strlen( $user_input['email'] ) ) > 96 ) || !preg_match( '/^[^\@]+@.*.[a-z]{2,15}$/i', $user_input['email'] ) ) {
            $this->error['warning'] = "Invalid Email Address.";
        } else if ( strlen( $user_input['phone_number'] ) == '' ) {
            $this->error['warning']    = 'Phone Number Cannot be empty';
        } else if ( (strlen( $user_input['phone_number'] ) < 8) || (strlen( $user_input['phone_number'] ) > 13) ){
            $this->error['warning']    = 'Phone Number Length between 8-13 characters';
        } else if ( strlen( $user_input['country_code'] ) == '' ) {
            $this->error['warning']    = 'Country Code Cannot be empty';
        }
        return !$this->error;
    }
    //CREATE NEW SERVICE
    function request_service() {

        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->verify_validate_pickup( $user_input ) ) {
                $traveller_id    = trim( $user_input['traveller_id'] );
                $guider_id       = trim( $user_input['guider_id'] );
                $activity_id     = trim( $user_input['activity_id'] );
                $number_of_person= trim( $user_input['number_of_person'] );
                $pickup_date     = trim( $user_input['pickup_date'] );
                if($pickup_date == '' || $pickup_date == '-'){
                    $pickup_date = '0000-00-00';
                }else{
                    $pickup_date = date('Y-m-d',strtotime($pickup_date));
                }
                $pickup_time     = trim( $user_input['pickup_time'] );
                $additional_information = trim( $user_input['additional_information'] );
                $processing_fee = $this->Serviceapimodel->siteInfo('_processing_fee');
                if(!$processing_fee){ $processing_fee = PROCESSING_FEE; }else{ $processing_fee = $processing_fee->s_value;}
                if(PROCESSING_FEE_ENABLED == 'NO'){ $processing_fee = 0; }
                //UPDATE DEVICE INFO
                $updateDevice   = $this->Travellerapimodel->updateDeviceInfo( $_SERVER, $traveller_id );
                $guiderInfo     = $this->Travellerapimodel->guiderInfoById( $guider_id );
                $activityInfo   = $this->Guiderapimodel->guiderActivtyInfoByUuid( $activity_id );
                $travellerInfo  = $this->Travellerapimodel->travellerInfoByUuid( $traveller_id );

                //PUSH NOTIFICATION
                $deviceTokenList  = $this->Guiderapimodel->guiderDeviceTokenList( $guider_id );
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
                      $this->pushNotificationmodel->android_push_notification($device_tokenA, $push_data, 'G');
                    }
                    if (!empty($device_tokenI)) {
                        $this->pushNotificationmodel->sendPushNotification_ios($device_tokenI, $push_data, 'G');
                    }
                }
                //END PUSH NOTIFICATION
                $booking_request_id = randomRequestID();
                $data       = array(
                                'service_guider_id'     => $guider_id,
                                'booking_request_id'    => strtoupper($booking_request_id),
                                'service_traveller_id'  => $traveller_id,
                                'number_of_person'      => $number_of_person,
                                'service_date'          => $pickup_date,
                                'pickup_time'           => $pickup_time,
                                'additional_information'=> $additional_information,
                                'current_processing_fee'=> $processing_fee,
                                'guider_charged'        => $activityInfo->rate_per_person,
                                'activity_id'           => $activity_id,
                                'activity_desc'         => $activityInfo->what_i_offer,
                                'service_price_type_id' => $activityInfo->price_type_id,
                                'processing_FeesType'   => $activityInfo->processingFeesType,
                                'processing_FeesValue'  => $activityInfo->processingFeesValue,
                                'service_region_id'     => $activityInfo->service_providing_region,
                                'guider_currency_symbol'=> $guiderInfo->country_currency_symbol,
                                'createdon'             => date("Y-m-d H:i:s")
                                );
                $request_id     = '';
                $request_id     = $this->Travellerapimodel->insertService( $data );
                //$mailData = $this->MailNotificationmodel->requestToGuider($guiderInfo->email,$guiderInfo->first_name,$travellerInfo->first_name);
                $res_data       = array(
                                    'request_primary_id'    => intval($request_id),
                                    'booking_request_id'    => strtoupper($booking_request_id),
                                    'service_guider_id'     => intval($guider_id),
                                    'service_traveller_id'  => intval($traveller_id),
                                    'number_of_person'      => intval($number_of_person),
                                    'pickup_date'           => $pickup_date,
                                    'pickup_time'           => $pickup_time,
                                    'additional_information'=> $additional_information,
                                    'service_charge_percentage'=> $processing_fee,
                                    'per_person_charge'     => $activityInfo->rate_per_person,
                                    'createdon'             => date("Y-m-d H:i:s")
                                  );
                $result     = array(
                                    'response_code' => SUCCESS_CODE, 
                                    'response_description' => 'Request submitted. Please check your message or request for further information.',
                                    'result'    => 'success',
                                    'data'      => $res_data );
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
    //Pickup validation
    function verify_validate_pickup( $user_input ) {
        $pickup_date = date('Y-m-d',strtotime($user_input['pickup_date']));
        $pickup_date = strtotime($pickup_date);
        $today       = strtotime(date('Y-m-d'));
        $guiderInfo  = $this->Travellerapimodel->guiderInfoById( $user_input['guider_id'] );
        if ( strlen( $user_input['number_of_person'] ) == '' ) {
            $this->error['warning']    = 'Number of person Cannot be empty';
        }/* else if ( $user_input['pickup_date'] == '' ){
            $this->error['warning']    = 'Pickup date Cannot be empty';
        } else if ( $pickup_date < $today ){
            $this->error['warning']    = 'Please enter a valid pickup date.';
        } else if ( $user_input['pickup_time'] == '' ){
            $this->error['warning']    = 'Pickup time Cannot be empty';
        }*/ else if ( !$this->Travellerapimodel->travellerInfoByUuid( $user_input['traveller_id'] ) ){
            $this->error['warning']    = 'Invalid '.GUEST_NAME.' ID.';
        } else if ( !$guiderInfo ){
            $this->error['warning']    = 'Invalid '.HOST_NAME.' ID.';
        } else if ( $user_input['activity_id'] == '' ){
            $this->error['warning']    = 'Activity ID Cannot be empty';
        } else if ( !$this->Guiderapimodel->guiderActivtyInfoByUuid( $user_input['activity_id'] ) ){
            $this->error['warning']    = 'Invalid Activity ID.';
        }
        return !$this->error;
    }
    public function getMyRequests() {

        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array('message' => 'Authorization error', 'result' => 'error');
        } else if( $input != '' ) {
            $user_input = get_object_vars($input);
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->myRequestValidate( $user_input ) ) {

                $traveller_id = trim( $user_input['traveller_id'] );
                $filter_type  = trim( $user_input['filter_type'] );
                $page_no      = trim( $user_input['page_no'] );
                $page_total   = trim( $user_input['page_total'] );
                if(!$page_no){
                    $page_no = 1;
                }
                $page_number = ($page_no) ? $page_no : 1;
                $offset      = ($page_number  == 1) ? 0 : ($page_number * $page_total) - $page_total;
                $this->Travellerapimodel->autoCompletedExpiryRequest($traveller_id);
                $result      = $this->Travellerapimodel->getTravellerRequest( $page_total, $offset, $traveller_id, $filter_type );
            } else {
                if( $_SERVER['REQUEST_METHOD'] != 'POST' ) {
                    $result = array('message' => 'Undefined Request Method', 'result' => 'error');
                } else if (isset($this->error['warning'])) {
                    $result = array('response_code' => ERROR_CODE, 'response_description' => $this->error['warning'], 'result' => 'error', 'data'=>array('error' => 1));
                }
            }
        } else {
            $result = array('message' => 'No Input Received', 'result' => 'error');
        }
        echo json_encode($result);
    }
    function myRequestValidate( $user_input ) {
        
        if ( !$this->Travellerapimodel->travellerInfoByUuid( $user_input['traveller_id'] ) ){
            $this->error['warning']    = 'Invalid Traveller ID.';
        }
        return !$this->error;
    }
    //Guider accept and traveller accept
    function cancelRequest() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->cancelRequestValidate( $user_input ) ) {
                $service_id     = trim( $user_input['request_primary_id'] );
                $traveller_id   = trim( $user_input['traveller_id'] );
                $status         = trim( $user_input['status'] );
                $serviceInfo    = $this->Serviceapimodel->serviceInfo( $service_id );
                
                $serviceStatus  = $serviceInfo->status;
                if($serviceStatus == 2){
                    $cancelled_type = 3;
                }else{
                    $cancelled_type = 1;
                }
                $data           = array( 
                                    'status'        => 3,
                                    'cancelled_by'  => 'T',
                                    'cancelled_type'=> $cancelled_type,
                                    'view_by_guider'=> 'N',
                                    'view_by_traveller' => 'N'
                                    );
                $result1    = $this->Serviceapimodel->updateServiceRequest( $data, $service_id );
                $result     = array(
                                    'response_code'     => SUCCESS_CODE, 
                                    'response_description' => 'You Have Successfully Cancelled Your Request.', 
                                    'result'            => 'success',
                                    'data'              => $data 
                                    );
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
    function cancelRequestValidate( $user_input ) {
        if( trim($user_input['request_primary_id']) == '' ){
            $this->error['warning']    = 'Service ID Cannot be empty';
        } else if ( !$this->Serviceapimodel->serviceInfo( $user_input['request_primary_id'] ) ){
            $this->error['warning']    = 'Invalid Service ID.';
        } else if( trim($user_input['traveller_id']) == '' ){
            $this->error['warning']    = ''.GUEST_NAME.' ID Cannot be empty';
        } else if ( !$this->Travellerapimodel->travellerInfoByUuid( $user_input['traveller_id'] ) ){
            $this->error['warning']    = 'Invalid '.GUEST_NAME.' ID.';
        } else if ( !$this->Serviceapimodel->travellerServiceInfo( $user_input['request_primary_id'], $user_input['traveller_id'] ) ){
            $this->error['warning']    = 'Cannot update this Service ID.';
        }
        return !$this->error;
    }
    public function getMyJourneys() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array('message' => 'Authorization error', 'result' => 'error');
        } else if( $input != '' ) {
            $user_input = get_object_vars($input);
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->myJourneyValidate( $user_input ) ) {

                $traveller_id = trim( $user_input['traveller_id'] );
                $filter_type  = trim( $user_input['filter_type'] );
                $page_no      = trim( $user_input['page_no'] );
                $page_total   = trim( $user_input['page_total'] );
                if(!$page_no){
                    $page_no = 1;
                }
                $page_number = ($page_no) ? $page_no : 1;
                $offset      = ($page_number  == 1) ? 0 : ($page_number * $page_total) - $page_total;
                $this->Travellerapimodel->autoCompletedExpiryJourney($traveller_id);
                $result      = $this->Travellerapimodel->getMyJourneyList( $page_total, $offset, $traveller_id, $filter_type );
            } else {
                if( $_SERVER['REQUEST_METHOD'] != 'POST' ) {
                    $result = array('message' => 'Undefined Request Method', 'result' => 'error');
                } else if (isset($this->error['warning'])) {
                    $result = array('response_code' => ERROR_CODE, 'response_description' => $this->error['warning'], 'result' => 'error', 'data'=>array('error' => 1));
                }
            }
        } else {
            $result = array('message' => 'No Input Received', 'result' => 'error');
        }
        echo json_encode($result);
    }
    function myJourneyValidate( $user_input ) {
        
        if ( !$this->Travellerapimodel->travellerInfoByUuid( $user_input['traveller_id'] ) ){
            $this->error['warning']    = 'Invalid '.GUEST_NAME.' ID.';
        }
        return !$this->error;
    }
    public function postComment() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->send_comment_validate( $user_input ) ) {
                $guider_id          = trim( $user_input['guider_id'] );
                $traveller_id       = trim( $user_input['traveller_id'] );
                $message            = trim( $user_input['message'] );
                $rating             = trim( $user_input['rating'] );
                $sender_type        = 2;
                $receiver_type      = 1;
                $createdon          = date("Y-m-d H:i:s");
                
                $data       = array(
                                    'cmt_guider_id'     => $guider_id,
                                    'cmt_traveller_id'  => $traveller_id,
                                    'comments'          => $message,
                                    'sender_type'       => $sender_type,
                                    'receiver_type'     => $receiver_type,
                                    'createdon'         => $createdon
                                    );
                $comment_id     = $this->Travellerapimodel->insertComments( $data );
                
                $travellerInfo  = $this->Travellerapimodel->travellerInfoByUuid( $traveller_id );
                $profileImgPath = $this->config->item( 'upload_path_url' ).'t_profile/';
                $profile_image  = ($travellerInfo->profile_image)?(filter_var($travellerInfo->profile_image, FILTER_VALIDATE_URL) === FALSE) ? $profileImgPath.$travellerInfo->profile_image : $travellerInfo->profile_image : '';
                if($rating){ $is_rated = 1; }else{ $is_rated = 0; }
                $res_data   = array(
                                    'comment_id'        => intval($comment_id),
                                    'guider_id'         => intval($guider_id),
                                    'traveller_id'      => intval($traveller_id),
                                    'comments'          => $message,
                                    'first_name'        => $travellerInfo->first_name,
                                    'last_name'         => $travellerInfo->last_name,
                                    'profile_pic'       => $profile_image,
                                    'country'           => $travellerInfo->country_name,
                                    'city'              => $travellerInfo->city,
                                    'is_from_ratings'   => $is_rated,
                                    'created_on'        => $createdon
                                    );
                $result     = array(
                                    'response_code'     => SUCCESS_CODE, 
                                    'response_description' => 'Comments posted Successfully.', 
                                    'result'            => 'success',
                                    'data'              => $res_data
                                    );
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
    function send_comment_validate( $user_input ) {
        if( trim($user_input['guider_id']) == '' ){
            $this->error['warning']    = ''.HOST_NAME.' ID Cannot be empty';
        } else if( !$this->Guiderapimodel->guiderInfoByUuid( $user_input['guider_id'] ) ) {
            $this->error['warning']    = ''.HOST_NAME.' id not exist.';
        } else if( trim($user_input['traveller_id']) == '' ){
            $this->error['warning']    = ''.GUEST_NAME.' ID Cannot be empty';
        } else if ( !$this->Travellerapimodel->travellerInfoByUuid( $user_input['traveller_id'] ) ){
            $this->error['warning']    = 'Invalid '.GUEST_NAME.' ID.';
        } else if( trim($user_input['message']) == '' ){
            $this->error['warning']    = 'Receiver User type Cannot be empty.';
        }
        return !$this->error;
    }
    public function getCommentsList() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->get_comment_validate( $user_input ) ) {
                $traveller_id = trim( $user_input['traveller_id'] );
                $page_no      = trim( $user_input['page_no'] );
                $page_total   = trim( $user_input['page_total'] );
                if(!$page_no){
                    $page_no = 1;
                }
                $page_number = ($page_no) ? $page_no : 1;
                $offset      = ($page_number  == 1) ? 0 : ($page_number * $page_total) - $page_total;

                $result      = $this->Travellerapimodel->getCommentsList( $page_total, $offset, $traveller_id );
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
    function get_comment_validate( $user_input ) {
        if( trim($user_input['traveller_id']) == '' ){
            $this->error['warning']    = ''.GUEST_NAME.' ID Cannot be empty';
        } else if ( !$this->Travellerapimodel->travellerInfoByUuid( $user_input['traveller_id'] ) ){
            $this->error['warning']    = 'Invalid '.GUEST_NAME.' ID.';
        }
        return !$this->error;
    }
    public function giveRatingsForGuider() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->giveRatingValidate( $user_input ) ) {
                $data           = array();
                $service_id     = trim( $user_input['request_primary_id'] );
                //$traveller_id   = trim( $user_input['traveller_id'] );
                //$guider_id      = trim( $user_input['guider_id'] );
                $ratings        = trim( $user_input['ratings'] );
                $message        = trim( $user_input['message'] );
                $journeyInfo    = $this->Serviceapimodel->journeyInfo( $service_id );
                $guider_id      = $journeyInfo->service_guider_id;
                $traveller_id   = $journeyInfo->jny_traveller_id;
                $guiderInfo     = $this->Guiderapimodel->guiderInfoByUuid( $guider_id );
                if($ratings == 1){
                   $ratingval   = 1; 
                }else{
                    $ratingval  = 5;
                }
                $totalrating    = $guiderInfo->rating;
                if($totalrating){
                    $total      = ($totalrating + $ratingval)/2;
                }else{
                    $total      = $ratingval;
                }
                $data4          = array('rating' => $total);
                $result1        = $this->Guiderapimodel->updateGuiderByUuid( $data4, $guider_id );
                //JOURNEY COMPLETE STATUS
                if($journeyInfo->jny_status != 3){
                    $data5      = array('jny_status' => 3);
                    $update     = $this->Serviceapimodel->updateJourneyByServiceid( $data5, $service_id );
                }
                $data           = array(
                                        'guider_rating'      => $ratingval,
                                        'guider_feedback'    => $message,
                                        'guider_feedbackon'  => date("Y-m-d H:i:s")
                                        );
                
                $result1        = $this->Serviceapimodel->updateJourney( $data, $service_id );
                $result         = array(
                                        'response_code'     => SUCCESS_CODE, 
                                        'response_description' => 'Thanks for your ratings.',  
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
    function giveRatingValidate( $user_input ) {
        $journeyInfo    = $this->Serviceapimodel->journeyInfo( $user_input['request_primary_id'] );
        if( trim($user_input['request_primary_id']) == '' ){
            $this->error['warning']    = 'Request Primary ID Cannot be empty';
        } else if ( !$journeyInfo ){
            $this->error['warning']    = 'Invalid Request Primary ID.';
        } else if( trim($user_input['ratings']) != 1 && trim($user_input['ratings']) != 2 ){
            $this->error['warning']    = 'Invalid Rating Type';
        } else if( $journeyInfo->jny_status == 1 ){
            $this->error['warning']    = 'Journey does not start';
        } else if( $journeyInfo->guider_rating != 0 ){
            $this->error['warning']    = 'You have already rated this journey.';
        } else if( strlen($user_input['message']) > 200 ){
            $this->error['warning']    = 'Comment message allowed maximum 200 characters.';
        }
        return !$this->error;
    }
    function deleteMyComment() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->delete_comment_validate( $user_input ) ) {
                
                $traveller_id   = trim( $user_input['traveller_id'] );
                $comment_id     = trim( $user_input['comment_id'] );
                $comment_type   = trim( $user_input['comments_type'] ); //1->Ratings,2->InApp,3->webComments
                if($comment_type == 1){
                    $journeyInfo = $this->Serviceapimodel->journeyInfo( $comment_id );
                    if($journeyInfo->traveller_rating == 0){
                        $status = 2;
                    }else{
                        $status = 1;
                        $data   = array('traveller_feedback' => '');
                        $this->Serviceapimodel->updateJourney( $data, $comment_id );
                    }
                } else if ($comment_type == 2) {
                    $commentInfo = $this->Commonapimodel->commentInfo( $comment_id );
                    if($commentInfo){
                        if($commentInfo->rating != ''){
                            $status = 2;
                        }else{
                            $status = 1;
                            $this->Commonapimodel->deleteComment( $comment_id );
                        }
                    }else{
                        $status = 3;
                    }
                }elseif ($comment_type == 3) {
                    $status = 3;
                }
                if($status == 1){
                    $result = array(
                                    'response_code'     => SUCCESS_CODE, 
                                    'response_description' => 'Comment deleted successfully.', 
                                    'result'            => 'success',
                                    'data'              => array('success' => 1)
                                    );
                }else if($status == 3){
                    $result = array(
                                    'response_code'     => ERROR_CODE, 
                                    'response_description' => 'Invalid Comment ID', 
                                    'result'            => 'error',
                                    'data'              => array('error' => 1)
                                    );
                }else{
                    $result = array(
                                    'response_code'     => ERROR_CODE, 
                                    'response_description' => 'Cannot delete this Comment', 
                                    'result'            => 'error',
                                    'data'              => array('error' => 1)
                                    );
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
    function delete_comment_validate( $user_input ) {
        if( trim($user_input['traveller_id']) == '' ){
            $this->error['warning']    = ''.HOST_NAME.' ID Cannot be empty';
        } else if( !$this->Guiderapimodel->travellerInfoByUuid( $user_input['traveller_id'] ) ) {
            $this->error['warning']    = ''.HOST_NAME.' id not exist.';
        } else if( trim($user_input['comments_type']) != 1 && trim($user_input['comments_type']) != 2 && trim($user_input['comments_type']) != 3 ){
            $this->error['warning']    = 'Invalid Comments Type';
        } else if ( $user_input['comment_id'] == ''){
            $this->error['warning']    = 'Comment ID Cannot be empty';
        }
        return !$this->error;
    }
    public function updateReadmessageCount() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->update_msg_validate( $user_input ) ) {
                $guider_id      = trim( $user_input['guider_id'] );
                $traveller_id   = trim( $user_input['traveller_id'] );
                $result         = $this->Travellerapimodel->updateMessageList($traveller_id, $guider_id);
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
    function update_msg_validate( $user_input ) {
        if( trim($user_input['guider_id']) == '' ){
            $this->error['warning']    = ''.HOST_NAME.' ID Cannot be empty';
        } else if( !$this->Guiderapimodel->guiderInfoByUuid( $user_input['guider_id'] ) ) {
            $this->error['warning']    = ''.HOST_NAME.' id not exist.';
        } else if( trim($user_input['traveller_id']) == '' ){
            $this->error['warning']    = ''.GUEST_NAME.' ID Cannot be empty';
        } else if ( !$this->Travellerapimodel->travellerInfoByUuid( $user_input['traveller_id'] ) ){
            $this->error['warning']    = 'Invalid '.GUEST_NAME.' ID.';
        }
        return !$this->error;
    }
    public function get_traveller_count() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->get_traveller_count_validate( $user_input ) ) {
                $traveller_id   = trim( $user_input['traveller_id'] );
                $result         = $this->Travellerapimodel->get_traveller_count($traveller_id);
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
    function get_traveller_count_validate( $user_input ) {
        if( trim($user_input['traveller_id']) == '' ){
            $this->error['warning']    = ''.GUEST_NAME.' ID Cannot be empty';
        } else if ( !$this->Travellerapimodel->travellerInfoByUuid( $user_input['traveller_id'] ) ){
            $this->error['warning']    = 'Invalid '.GUEST_NAME.' ID.';
        }
        return !$this->error;
    }
    public function updateReadServiceCount() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->update_servicecount_validate( $user_input ) ) {
                $traveller_id   = trim( $user_input['traveller_id'] );
                $service_id     = trim( $user_input['request_primary_id'] );
                $filter_type    = trim( $user_input['filter_type'] );
                $result         = $this->Travellerapimodel->updateReadServiceCount($traveller_id, $service_id, $filter_type);
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
    function update_servicecount_validate( $user_input ) {

        if( trim($user_input['traveller_id']) == '' ){
            $this->error['warning']    = ''.GUEST_NAME.' ID Cannot be empty';
        } else if ( !$this->Travellerapimodel->travellerInfoByUuid( $user_input['traveller_id'] ) ){
            $this->error['warning']    = 'Invalid '.GUEST_NAME.' ID.';
        } else if( trim($user_input['request_primary_id']) == '' ){
            $this->error['warning']    = 'Service ID Cannot be empty';
        } else if ( !$this->Serviceapimodel->serviceInfo( $user_input['request_primary_id'] ) ){
            $this->error['warning']    = 'Invalid Service ID.';
        } else if( trim($user_input['filter_type']) == '' ){
            $this->error['warning']    = 'Filter Type Cannot be empty';
        }
        return !$this->error;
    }
    public function updateReadJourneyCount() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->update_jnycount_validate( $user_input ) ) {
                $traveller_id   = trim( $user_input['traveller_id'] );
                $service_id     = trim( $user_input['request_primary_id'] );
                $filter_type    = trim( $user_input['filter_type'] );
                $result         = $this->Travellerapimodel->updateReadJourneyCount($traveller_id, $service_id, $filter_type);
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
    function update_jnycount_validate( $user_input ) {

        if( trim($user_input['traveller_id']) == '' ){
            $this->error['warning']    = ''.GUEST_NAME.' ID Cannot be empty';
        } else if ( !$this->Travellerapimodel->travellerInfoByUuid( $user_input['traveller_id'] ) ){
            $this->error['warning']    = 'Invalid '.GUEST_NAME.' ID.';
        } else if( trim($user_input['request_primary_id']) == '' ){
            $this->error['warning']    = 'Service ID Cannot be empty';
        } else if ( !$this->Serviceapimodel->journeyInfo( $user_input['request_primary_id'] ) ){
            $this->error['warning']    = 'Invalid Service ID.';
        } else if( trim($user_input['filter_type']) == '' ){
            $this->error['warning']    = 'Filter Type Cannot be empty';
        }
        return !$this->error;
    }











    //Get Language List
    function get_list_of_languages() {
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) {
            $result         = $this->Travellerapimodel->get_language_lists();
            if( $_SERVER['REQUEST_METHOD'] != 'GET' )  {
                $result = array( 'message' => 'Undefined Request Method', 'result' => 'error' );
            } else if ( isset( $this->error['warning'] ) ) {
                $result = array('response_code' => ERROR_CODE, 'response_description' => $this->error['warning'], 'result' => 'error', 'data'=>array('error' => 1));
            }
        } else {
            $result = array( 'message' => 'Undefined Request Method', 'result' => 'error' );
        }
        echo json_encode( $result );
    }

    function servicevalidate( $user_input ) {
        if( trim($user_input['traveller_id']) == '' ){
            $this->error['warning']    = 'Traveller ID Cannot be empty';
        } else if ( !$this->Travellerapimodel->travellerInfoByUuid( $user_input['traveller_id'] ) ){
            $this->error['warning']    = 'Invalid Traveller ID.';
        }
        return !$this->error;
    }
    function service_payment() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->payment_validate( $user_input ) ) {
                $createdon      = date("Y-m-d H:i:s");
                $service_id     = trim( $user_input['service_id'] );
                $serviceInfo    = $this->Serviceapimodel->serviceInfo( $service_id );
               
                $traveller_id   = $serviceInfo->service_traveller_id;
                $guider_id      = $serviceInfo->service_guider_id;
                $activity_id    = $serviceInfo->activity_id;
                $guiderInfo     = $this->Travellerapimodel->guiderInfoById( $guider_id );
                if($guiderInfo){
                    
                    $push_data      = array(
                                        'title'             => 'Host',
                                        'body'              => 'Guest has paid the amount on his booking.',
                                        'action'            => 'payment',
                                        'notificationId'    => 5,
                                        'sound'             => 'notification',
                                        'icon'              => 'icon'
                                        );
                    /*if (strlen($guiderInfo->device_id) > 10){
                        $device_token = array($guiderInfo->device_id);
                        $pushData = $this->pushNotificationmodel->android_push_notification($device_token, $push_data, 'G');
                    }*/
                }
                if ( $this->Serviceapimodel->journeyInfo( $service_id ) ){
                    $data   = array('jny_status' => 1);
                    $this->Serviceapimodel->updateJourney($data, $service_id);
                }else{
                    $data2  = array(
                                'jny_traveller_id'  => $traveller_id,
                                'jny_guider_id'     => $guider_id,
                                'jny_service_id'    => $service_id,
                                'jny_activity_id'   => $activity_id,
                                'createdon'         => $createdon,
                                'payment_status'    => 'paid',
                                'jny_status'        => 1
                                );
                    $this->Serviceapimodel->insertJourney($data2);
                }
                $data3  = array('status' => 4);
                $this->Serviceapimodel->updateServiceRequest($data3, $service_id);
                $result     = array(
                                'response_code'     => SUCCESS_CODE, 
                                'response_description' => 'Thank you. Your payment has been successfully.', 
                                'result'            => 'success',
                                'data'              => $data3 
                                );
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
    function payment_validate( $user_input ) {
        if( trim($user_input['service_id']) == '' ){
            $this->error['warning']    = 'Service ID Cannot be empty';
        } else if ( !$this->Serviceapimodel->serviceInfo( $user_input['service_id'] ) ){
            $this->error['warning']    = 'Invalid Service ID.';
        }
        return !$this->error;
    }
    function cancelJourney() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
            if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
                $traveller_id = trim( $user_input['traveller_id'] );
                $service_id   = trim( $user_input['service_id'] );

                $result       = $this->Travellerapimodel->getJourneyList( $traveller_id, $service_id );
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
    public function travellerinfo() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array('message' => 'Authorization error', 'result' => 'error');
        } else if( $input != '' ) {
            $user_input = get_object_vars($input);
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->infovalidate( $user_input ) ) {
                $traveller_id   = trim( $user_input['traveller_id'] );
                $result         = $this->Travellerapimodel->travellerinfo( $traveller_id );
                $updateDevice   = $this->Travellerapimodel->updateDeviceInfo( $_SERVER, $traveller_id );
            } else {
                if( $_SERVER['REQUEST_METHOD'] != 'POST' ) {
                    $result = array('message' => 'Undefined Request Method', 'result' => 'error');
                } else if (isset($this->error['warning'])) {
                    $result = array('response_code' => ERROR_CODE, 'response_description' => $this->error['warning'], 'result' => 'error', 'data'=>array('error' => 1));
                }
            }
        } else {
            $result = array('message' => 'No Input Received', 'result' => 'error');
        }
        echo json_encode($result);
    }
    function infovalidate( $user_input ) {
        if( trim($user_input['traveller_id']) == '' ){
            $this->error['warning']    = 'Traveller ID Cannot be empty';
        } else if ( !$this->Travellerapimodel->travellerInfoByUuid( $user_input['traveller_id'] ) ){
            $this->error['warning']    = 'Invalid Traveller ID.';
        }
        return !$this->error;
    }
    function send_feedback() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->feedback_validate( $user_input ) ) {
                $createdon      = date("Y-m-d H:i:s");
                $traveller_id   = trim( $user_input['traveller_id'] );
                $subject        = trim( $user_input['subject'] );
                $message        = trim( $user_input['message'] );
                $device_token   = $_SERVER['HTTP_DEVICE_TOKEN'];
                $device_id      = $_SERVER['HTTP_DEVICE_ID'];
                $app_version    = $_SERVER['HTTP_APP_VERSION'];
                $device_type    = $_SERVER['HTTP_DEVICE_TYPE'];
                $build_no       = $_SERVER['HTTP_BUILD_NO'];
                $data = array();
                $data['fb_traveller_id']= $traveller_id;
                $data['subject']        = $subject;
                $data['description']    = $message;
                if($device_token){ $data['device_token'] = $device_token; }
                if($device_id){ $data['device_id'] = $device_id; }
                if($app_version){ $data['app_version'] = $app_version; }
                if($device_type){ $data['device_type'] = $device_type; }
                if($build_no){ $data['build_no'] = $build_no; }
                $data['createdon']    = $createdon;
                $this->Commonapimodel->insertTravellerFeedback($data);
                $result     = array(
                                'response_code'     => SUCCESS_CODE, 
                                'response_description' => 'Thank you. your feedback has been successfully submitted.', 
                                'result'            => 'success',
                                'data'              => array() 
                                );
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
    function feedback_validate( $user_input ) {
        if( trim($user_input['traveller_id']) == '' ){
            $this->error['warning']    = 'Traveller ID Cannot be empty';
        } else if ( !$this->Travellerapimodel->travellerInfoByUuid( $user_input['traveller_id'] ) ){
            $this->error['warning']    = 'Invalid Traveller ID.';
        } else if ( trim( $user_input['subject'] ) == '' ){
            $this->error['warning']    = 'Subject Cannot be empty';
        } else if ( strlen( $user_input['subject'] ) < 3 || (strlen( $user_input['subject'] ) > 60)){
            $this->error['warning']    = 'Subject Minimum 3 & maximum 60 characters';
        } else if ( trim( $user_input['message'] ) == '' ){
            $this->error['warning']    = 'Message Cannot be empty';
        }
        return !$this->error;
    }
    public function uploadInterestImages() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->validateuploadImages( $user_input ) ) {
                $traveller_id   = $user_input['traveller_id'];
                $interest_imgs  = $user_input['interest_pictures'];
                $data = array();
                $i = '';
                foreach ($interest_imgs as $key => $value) {
                    $data['photo'.$i] = $value;
                    $i++;
                }
                $result1        = $this->Travellerapimodel->updateTravellerByUuid( $data, $traveller_id );
                $is_new_user        = 0;
                $travellerInfo      = $this->Travellerapimodel->travellerInfoByUuid( $traveller_id );
                $upload_path_url    = $this->config->item( 'upload_path_url' );
                $profileImgPath     = $upload_path_url.'t_profile/';
                $activityImgPath    = $upload_path_url.'t_activity/';
                $profile_image      = ($travellerInfo->profile_image)?(filter_var($travellerInfo->profile_image, FILTER_VALIDATE_URL) === FALSE) ? $profileImgPath.$travellerInfo->profile_image : $travellerInfo->profile_image : '';
                $photo              = ($travellerInfo->photo)?(filter_var($travellerInfo->photo, FILTER_VALIDATE_URL) === FALSE) ? $activityImgPath.$travellerInfo->photo : $travellerInfo->photo : '';
                $photo1             = ($travellerInfo->photo1)?(filter_var($travellerInfo->photo1, FILTER_VALIDATE_URL) === FALSE) ? $activityImgPath.$travellerInfo->photo1 : $travellerInfo->photo1 : '';
                $photo2             = ($travellerInfo->photo2)?(filter_var($travellerInfo->photo2, FILTER_VALIDATE_URL) === FALSE) ? $activityImgPath.$travellerInfo->photo2 : $travellerInfo->photo2 : '';
                if($travellerInfo->age == '0000-00-00'){
                    $age    = 0;
                }else{
                    $age    = date_diff(date_create($travellerInfo->age), date_create('today'))->y;
                }
                $lang = [];
                if($travellerInfo->languages_known){
                    $array =  explode(',', $travellerInfo->languages_known);
                    foreach ($array as $item) {
                        $langInfo = $this->Travellerapimodel->travellerLangInfo($item);
                        if($langInfo){ $lang[] = $langInfo->language; }
                    }
                }
                $profilePer = 0;
                if( $travellerInfo->first_name == '' || $travellerInfo->last_name == '' || $travellerInfo->gender == 0 || $travellerInfo->age == '0000-00-00' ||
                 $travellerInfo->about_me == '' || $travellerInfo->languages_known == '' || $travellerInfo->city == '' ) {
                    $profile_updated    = 0;
                } else {
                    $profile_updated    = 1;
                }
                if( $travellerInfo->first_name == '' || $travellerInfo->last_name == '' || $travellerInfo->gender == 0 || $travellerInfo->age == '0000-00-00') {
                    $is_bpu    = 0;
                } else {
                    $is_bpu    = 1;
                    $profilePer += 35;
                }
                if( $travellerInfo->profile_image == '') {
                    $is_profile_pic_updated = 0;
                } else {
                    $is_profile_pic_updated = 1;
                    $profilePer             += 35;
                }
                if($travellerInfo->languages_known) { $profilePer += 15; }
                if($travellerInfo->about_me) { $profilePer += 10; }
                if($travellerInfo->city) { $profilePer += 5; }
                $serviceCount   = $this->Travellerapimodel->travellerServiceCount($travellerInfo->traveller_id);
                $serviceIDs     = $this->Travellerapimodel->travellerServiceIDs($travellerInfo->traveller_id);
                $messageCount   = $this->Travellerapimodel->travellerMessageCount($travellerInfo->traveller_id);
                $commentCount1  = $this->Travellerapimodel->feedbackTotalCount('T',$userInfo->traveller_id);
                $commentCount2  = $this->Travellerapimodel->commentTotalCount('T',$userInfo->traveller_id);
                $commentCount3  = $this->Travellerapimodel->webcommentTotalCount('T',$userInfo->traveller_id);
                $commentCount   = $commentCount1 + $commentCount2 + $commentCount3;
                $res_data       = array(
                                    'traveller_id'  => intval($travellerInfo->traveller_id),
                                    't_id'          => $travellerInfo->t_id,
                                    'first_name'    => $travellerInfo->first_name,
                                    'last_name'     => $travellerInfo->last_name,
                                    'email'         => $travellerInfo->email,
                                    'phone_number'  => $travellerInfo->phone_number,
                                    'country_code'  => $travellerInfo->countryCode,
                                    'city'          => $travellerInfo->city,
                                    'languages_known'=> $lang,
                                    "gender"        => intval($travellerInfo->gender),
                                    'age'           => $age,
                                    'DOB'           => $travellerInfo->age,
                                    'about_me'      => $travellerInfo->about_me,
                                    'rating_strength' => floatval($travellerInfo->ratings),
                                    'profile_strength'=> $profilePer,
                                    'photo'         => $photo,
                                    'photo1'        => $photo1,
                                    'photo2'        => $photo2,
                                    'profile_pic'   => $profile_image,
                                    'service_count' => intval($serviceCount),
                                    'message_count' => intval($messageCount),
                                    'service_ids'   => $serviceIDs,
                                    'device_type'   => '',
                                    'app_version'   => '',
                                    'device_brand'  => '',
                                    'device_model'  => '',
                                    'device_OS_version' => '',
                                    'device_id'     => '',
                                    'created_on'    => $travellerInfo->created_on,
                                    'status'        => intval($travellerInfo->status),
                                    'comment_count' => intval($commentCount)
                                    );
                $result     = array(
                                    'response_code'     => SUCCESS_CODE, 
                                    'response_description' => 'Activity images updated successfully.', 
                                    'result'            => 'success',
                                    "is_basic_profile_updated"  => $is_bpu,
                                    "is_full_profile_updated"   => $profile_updated,
                                    "is_profile_pic_updated"    => $is_profile_pic_updated,
                                    "is_new_user"               => $is_new_user,
                                    'data'                      => $res_data
                                    );
            } else {
                if( $_SERVER['REQUEST_METHOD'] != 'POST' )  {
                    $result = array( 'message' => 'Undefined Request Method', 'result' => 'error' );
                } else if ( isset( $this->error['warning'] ) ) {
                    $result = array('response_code' => ERROR_CODE, 'response_description' => $this->error['warning'], 'result' => 'error', 'data'=>array( 'error' => 1 ));
                }
            }
        } else {
            $result = array('message' => 'No Input Received', 'result' => 'error');
        }
        echo json_encode($result);
    }
    //Validation here
    private function validateuploadImages( $user_input ) {
        
        if( trim($user_input['traveller_id']) == '' ){
            $this->error['warning']    = 'Traveller ID Cannot be empty';
        } else if ( !$this->Travellerapimodel->travellerInfoByUuid( $user_input['traveller_id'] ) ){
            $this->error['warning']    = 'Invalid Traveller ID.';
        } else if( count($user_input['interest_pictures']) <= 0 ) {
            $this->error['warning']    = 'Interest Pictures Cannot be empty';
        }
        return !$this->error;
    }
    function version_control(){
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else {
            $app_version    = $_SERVER['HTTP_APP_VERSION'];
            $device_type    = $_SERVER['HTTP_DEVICE_TYPE'];
            if($device_type){
                $appInfo = $this->Commonapimodel->getTravellerAppversion($device_type);
                if($appInfo){
                    $current_version_no = $appInfo->current_version_no;
                    $redirect_link      = $appInfo->redirect_link;
                    $update_message     = $appInfo->update_message;
                    $is_force_update    = $appInfo->is_force_update;
                    if($current_version_no == $app_version){
                        $is_new_version = 0;
                    }else{
                        $is_new_version = 1;
                    }
                    $result     = array(
                                    'response_code'     => SUCCESS_CODE, 
                                    'redirect_link'     => $redirect_link,
                                    'is_force_update'   => intval($is_force_update),
                                    'is_new_version'    => $is_new_version,
                                    'current_version_no'=> $current_version_no,
                                    'update_message'    => $update_message,
                                    'result'            => 'success',
                                    'data'              => array() 
                                    );
                }else{
                   $result = array('response_code' => ERROR_CODE, 'response_description' => 'Invalid Device Type.', 'result' => 'error', 'data'=>array('error' => 1)); 
                }
            }else{
                $result = array('response_code' => ERROR_CODE, 'response_description' => 'Device Type Cannot be empty', 'result' => 'error', 'data'=>array('error' => 1));
            }
        }
        echo json_encode($result);
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