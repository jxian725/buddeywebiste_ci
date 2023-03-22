<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Guider extends CI_Controller{

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
    public function verify_guider() {
        //error_reporting(E_ALL);
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->verify_validate( $user_input ) ) {
                $email          = trim( $user_input['email'] );
                $phone_number   = trim( $user_input['phone_number'] );
                $phone_number   = ltrim($phone_number, '0');
                $country_code   = trim( $user_input['country_code'] );
                $country_code   = ltrim($country_code, '+');
                //$device_id      = trim( $user_input['device_id'] );

                $phoneExist     = $this->Guiderapimodel->guiderPhoneExists( $phone_number );
                if ( $phoneExist ){
                    $guider_id      = $phoneExist->guider_id;
                    //$emailExist     = $this->Guiderapimodel->guiderPhoneEmailExists( $phone_number, $email );
                    if($phoneExist->status == 4){
                        $res_msg    = 'Your account has been successfully Recreated';
                        $data1      = array( 'email' => $email, 'phone_number' => $phone_number, 'status'=> 0 );
                    }else{
                        $res_msg    = 'Profile updated successfully';
                        $data1      = array( 'email' => $email, 'phone_number' => $phone_number );
                    }
                    $this->Guiderapimodel->updateGuiderByUuid( $data1, $guider_id );
                    $updateDevice   = $this->Guiderapimodel->updateDeviceInfo( $_SERVER, $guider_id );
                    $is_new_user    = 0;
                } else {
                    $uuid   = gen_uuid();
                    $data   = array( 
                                    'email'         => $email, 
                                    'phone_number'  => $phone_number,
                                    'countryCode'   => $country_code,
                                    'rating'        => 5,
                                    'host_uuid'     => $uuid,
                                    'reg_device_type'=> 2,
                                    'created_on'    => date( 'Y-m-d' ) 
                                );
                    $guider_id      = $this->Guiderapimodel->insertGuider( $data );
                    $insert1        = $this->Guiderapimodel->insertDeviceInfo( $_SERVER, $guider_id );
                    $is_new_user    = 1;
                    $res_msg        = 'Your account has been successfully created.';
                }
                $result  = $this->Guiderapimodel->guiderProfileInfo($guider_id, $is_new_user, $res_msg);
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
    //Validation here
    private function verify_validate( $user_input ) {
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
    public function registerProfile() {
        
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->profile_validate( $user_input ) ) {

                $first_name     = trim( $user_input['first_name'] );
                $last_name      = trim( $user_input['last_name'] );
                $DOB            = trim( $user_input['DOB'] );
                $guider_id      = trim( $user_input['guider_id'] );
                $knownLanguage  = $user_input['known_languages'];
                $knownLanguage  = implode(',', $knownLanguage);
                $email          = trim( $user_input['email'] );
                $about_me       = trim( $user_input['about_me'] );
                $nric_number    = trim( $user_input['nric_number'] );
                $city_id        = trim( $user_input['city_id'] );
                $category_id    = trim( $user_input['category_id'] );
                $skill_name     = trim( $user_input['skill_name'] );
                $profileData    = array(
                                    'first_name'    => $first_name,
                                    'last_name'     => $last_name,
                                    'age'           => $DOB,
                                    'about_me'      => $about_me,
                                    'languages_known'=> $knownLanguage,
                                    'nric_number'   => $nric_number
                                  );
                if(preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $email)){ $profileData['email'] = $email; }
                if($city_id){ $profileData['city'] = $city_id; }
                if($category_id){ $profileData['skills_category'] = $category_id; }
                if($skill_name){ $profileData['sub_skills'] = $skill_name; }
                
                $this->Guiderapimodel->updateGuiderByUuid( $profileData, $guider_id );
                $updateDevice   = $this->Guiderapimodel->updateDeviceInfo( $_SERVER, $guider_id );
    
                $res_msg = 'Profile updated successfully';
                $result  = $this->Guiderapimodel->guiderProfileInfo($guider_id, $new = 0, $res_msg, $verify_email = 1);

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
    //Validation here
    private function profile_validate( $user_input ) {
        $year = date('Y', strtotime($user_input['DOB']));
        $current = date('Y', strtotime(date('Y-m-d')));
        $age = $current - $year;
        if ( trim( $user_input['first_name'] ) == '' ) {
            $this->error['warning']    = 'First Name Cannot be empty';
        } else if ( strlen( $user_input['first_name'] ) < 3 || (strlen( $user_input['first_name'] ) > 40)){
            $this->error['warning']    = 'First Name Minimum 3 & maximum 40 characters';
        } else if ( trim( $user_input['last_name'] ) == '' ){
            $this->error['warning']    = 'Last Name Cannot be empty';
        } else if ( strlen( $user_input['last_name'] ) < 3 || (strlen( $user_input['last_name'] ) > 40)){
            $this->error['warning']    = 'Last Name Minimum 3 & maximum 40 characters';
        } else if ( count($user_input['known_languages']) <= 0 ) {
            $this->error['warning']    = 'Language Cannot be empty';
        } else if ( $user_input['guider_id'] == '' ) {
            $this->error['warning']    = HOST_NAME.' ID Cannot be empty';
        } else if( !$this->Guiderapimodel->guiderInfoByUuid( $user_input['guider_id'] ) ) {
            $this->error['warning']    = 'Please enter valid '.HOST_NAME.' ID';
        } else if ( trim( $user_input['about_me'] ) == '' ){
            $this->error['warning']    = 'About Me Cannot be empty';
        } else if ( strlen( $user_input['about_me'] ) < 10 ){
            $this->error['warning']    = 'About Me minimum Length 10 characters';
        } else if ( trim( $user_input['DOB'] ) == '' || ( $user_input['DOB'] ) == '0000-00-00' ){
            $this->error['warning']    = 'Date of Birth Cannot be empty';
        } else if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",trim( $user_input['DOB'] ))) {
            $this->error['warning']    = 'Invalid Date format';
        } else if ( $age < 13 ){
            $this->error['warning']    = 'Minimum age required 13 years old.';
        }
        return !$this->error;
    }
    public function updateBankInfo() {

        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->bank_validate( $user_input ) ) {
                $guider_id      = trim( $user_input['guider_id'] );
                $acc_name       = trim( $user_input['acc_name'] );
                $bank_name      = trim( $user_input['bank_name'] );
                $acc_no         = trim( $user_input['acc_no'] );

                $data           = array(
                                        'acc_name'  => $acc_name,
                                        'bank_name' => $bank_name,
                                        'acc_no'    => $acc_no
                                    );
                $this->Guiderapimodel->updateGuiderByUuid( $data, $guider_id );
                $updateDevice = $this->Guiderapimodel->updateDeviceInfo( $_SERVER, $guider_id );

                $res_msg = 'Bank Information updated successfully.';
                $result  = $this->Guiderapimodel->guiderProfileInfo($guider_id, $new = 0, $res_msg, $verify_email = 0);
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
    //Validation BANK INFO here
    private function bank_validate( $user_input ) {
        if( trim($user_input['guider_id']) == '' ){
            $this->error['warning']    = HOST_NAME.' ID Cannot be empty';
        } else if( !$this->Guiderapimodel->guiderInfoByUuid( $user_input['guider_id'] ) ) {
            $this->error['warning']    = HOST_NAME.' ID not exist.';
        } else if ( trim( $user_input['acc_name'] ) == '' ){
            $this->error['warning']    = 'Account Name Cannot be empty';
        } else if ( strlen( $user_input['acc_name'] ) < 2 || (strlen( $user_input['acc_name'] ) > 40)){
            $this->error['warning']    = 'Account Name Minimum 2 & maximum 40 characters';
        } else if (preg_match('/\\d/', $user_input['acc_name']) > 0){
            $this->error['warning']    = 'Account Number must be string format.';
        } else if ( trim( $user_input['bank_name'] ) == '' ){
            $this->error['warning']    = 'Bank Name Cannot be empty';
        } else if ( strlen( $user_input['bank_name'] ) < 2 || (strlen( $user_input['bank_name'] ) > 40)){
            $this->error['warning']    = 'Bank Name Minimum 2 & maximum 40 characters';
        } else if ( trim( $user_input['acc_no'] ) == '' ){
            $this->error['warning']    = 'Account Number Cannot be empty';
        } else if ( strlen( $user_input['acc_no'] ) < 2 || (strlen( $user_input['acc_no'] ) > 40)){
            $this->error['warning']    = 'Account Number Minimum 2 & maximum 40 characters';
        } else if (!is_numeric($user_input['acc_no'])){
            $this->error['warning']    = 'Account Number must be numeric format.';
        }
        return !$this->error;
    }
    public function updateServiceInfo() {
        
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->service_validate( $user_input ) ) {
                $guider_id           = trim( $user_input['guider_id'] );
                $whatIOffer          = $user_input['what_i_offer'];
                $price_type_id       = trim( $user_input['price_type_id'] );
                if($price_type_id == 3){ //FOR FREE SERVICE
                    $rate_per_person = 0;
                }else{
                    $rate_per_person = trim( $user_input['rate_per_person'] );
                }
                $guiding_speciality  = $user_input['guiding_speciality'];
                $support_region      = trim( $user_input['support_region'] );
                $cancellation_policy = $user_input['cancellation_policy'];
                $maximum_booking     = $user_input['maximum_booking'];
                $additional_info_label = $user_input['additional_info_label'];
                $date_time_needed    = ($user_input['date_time_needed'])? $user_input['date_time_needed'] : 0;

                $data               = array( 
                                            'what_i_offer'          => $whatIOffer,
                                            'cancellation_policy'   => $cancellation_policy
                                        );
                if (count($guiding_speciality) > 0){
                    $guiding_speciality         = implode(',', $guiding_speciality);
                    $data['guiding_speciality'] = $guiding_speciality;
                }
                if ($rate_per_person){
                    $data['rate_per_person'] = $rate_per_person;
                }
                if ($support_region){
                    $data['service_providing_region'] = $support_region;
                }
                if ($price_type_id){
                    $data['price_type_id'] = $price_type_id;
                }
                if ($maximum_booking){
                    $data['maximum_booking'] = $maximum_booking;
                }
                if ($additional_info_label){
                    $data['additional_info_label'] = $additional_info_label;
                }
                if ($date_time_needed == 0 || $date_time_needed == 1){
                    $data['date_time_needed'] = $date_time_needed;
                }
                if($this->Guiderapimodel->guiderActivtyExists($guider_id, $support_region)){
                    $this->Guiderapimodel->updateGuiderActivtyByService( $data, $guider_id, $support_region );
                }else{
                    $data['activity_guider_id'] = $guider_id;
                    $data['createdon']          = date('Y-m-d h:i:s');
                    $this->Guiderapimodel->insertGuiderActivity( $data );
                }
                $updateDevice  = $this->Guiderapimodel->updateDeviceInfo( $_SERVER, $guider_id );

                $res_msg = 'Service Information updated successfully.';
                $result  = $this->Guiderapimodel->guiderProfileInfo($guider_id, $new = 0, $res_msg);
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
    //Validation BANK INFO here
    private function service_validate( $user_input ) {
        if( trim($user_input['guider_id']) == '' ){
            $this->error['warning']    = HOST_NAME.' ID Cannot be empty';
        } else if( trim($user_input['support_region']) == '' ){
            $this->error['warning']    = 'Support Region ID Cannot be empty';
        } else if( trim($user_input['price_type_id']) == '' ){
            $this->error['warning']    = 'Price Type ID Cannot be empty';
        } else if(!is_numeric($user_input['price_type_id'])){
            $this->error['warning']    = 'Invalid Price Type ID';
        } else if( !$this->Guiderapimodel->guiderInfoByUuid( $user_input['guider_id'] ) ) {
            $this->error['warning']    = HOST_NAME.' id not exist.';
        } /*else if ( count($user_input['guiding_speciality']) <= 0 ) {
            $this->error['warning']    = 'Guiding Speciality Cannot be empty';
        }*/
        
        if (trim($user_input['rate_per_person'])){
            if(trim($user_input['price_type_id']) != 3){
                if ( intval($user_input['rate_per_person']) < MIN_RATE || intval($user_input['rate_per_person']) > MAX_RATE ) {
                    $this->error['warning']    = 'Minimum pricing '.MIN_RATE.' and maximum '.MAX_RATE.' '.CURRENCYCODE;
                }
            }
        }
        if (trim( $user_input['support_region'] )){
            if( !$this->Guiderapimodel->stateInfoByid( $user_input['support_region'] ) ) {
                $this->error['warning']    = 'Invalid Support Region ID';
            }
        }
        if (trim( $user_input['what_i_offer'] )){
            if ( strlen( $user_input['what_i_offer'] ) < 10 ){
                $this->error['warning']    = 'What I Offer minimum Length 10 characters';
            }
        }
        if (trim($user_input['cancellation_policy'])){
            if ( strlen( $user_input['cancellation_policy'] ) < 10 ){
                $this->error['warning']    = 'Cancellation Policy minimum Length 10 characters';
            }
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
                $phone_number   = preg_replace('/[^a-zA-Z0-9]+/','', $phone_number);
                $country_code   = trim( $user_input['country_code'] );

                $phoneExist     = $this->Guiderapimodel->guiderEmailExists( $email );
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
                if( $_SERVER['REQUEST_METHOD'] != 'POST' ){
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
    public function getMyRequests() {
        
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array('message' => 'Authorization error', 'result' => 'error');
        } else if( $input != '' ) {
            $user_input = get_object_vars($input);
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->myRequestValidate( $user_input ) ) {

                $guider_id    = trim( $user_input['guider_id'] );
                $filter_type  = trim( $user_input['filter_type'] );
                $page_no      = trim( $user_input['page_no'] );
                $page_total   = trim( $user_input['page_total'] );
                if(!$page_no){
                    $page_no = 1;
                }
                $page_number = ($page_no) ? $page_no : 1;
                $offset      = ($page_number  == 1) ? 0 : ($page_number * $page_total) - $page_total;
                $this->Guiderapimodel->autoCompletedExpiryRequest($guider_id);
                $result      = $this->Guiderapimodel->getGuiderRequest( $page_total, $offset, $guider_id, $filter_type );
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
        if( !$this->Guiderapimodel->guiderInfoByUuid( $user_input['guider_id'] ) ) {
            $this->error['warning']    = HOST_NAME.' ID Not Exist.';
        }
        return !$this->error;
    }
    function acceptRequest() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->acceptRequestValidate( $user_input ) ) {
                $service_id     = trim( $user_input['request_primary_id'] );
                $guider_id      = trim( $user_input['guider_id'] );
                $serviceInfo    = $this->Serviceapimodel->serviceInfo( $service_id );
                $traveller_id   = $serviceInfo->service_traveller_id;
                $service_date   = $serviceInfo->service_date;
                $guiderID       = $serviceInfo->service_guider_id;
                $activity_id    = $serviceInfo->activity_id;
                
                $today          = date("Y-m-d");
                $createdon      = date("Y-m-d H:i:s");
                if($serviceInfo->service_price_type_id == 3){
                    //CREATE NEW JOURNEY
                    $data       =   array(
                                    'status'         => 4,
                                    'cancelled_by'   => '',
                                    'cancelled_type' => 0,
                                    'view_by_guider' => 'N',
                                    'view_by_traveller' => 'N'
                                );
                    $result1    = $this->Serviceapimodel->updateServiceRequest( $data, $service_id );
                    if($today == $service_date){
                        $jny_status = 2; //ONGOING
                    }else{
                        $jny_status = 1; //UPCOMING
                    }
                    /******GET SERVICE DATE,GUIDER ID TRAVELLER ID FOR NEW JOURNEY********/
                    if ( $this->Serviceapimodel->journeyInfo( $service_id ) ){
                        $data11   = array('jny_status' => $jny_status);
                        $this->Serviceapimodel->updateJourney($data11, $service_id);
                    }else{
                        $data2  = array(
                                    'jny_traveller_id'  => $traveller_id,
                                    'jny_guider_id'     => $guiderID,
                                    'jny_service_id'    => $service_id,
                                    'jny_activity_id'   => $activity_id,
                                    'createdon'         => $createdon,
                                    'payment_status'    => 'paid',
                                    'jny_transactionID' => 'FREE_BOOKING_'.$service_id,
                                    'jny_status'        => $jny_status
                                    );
                        $this->Serviceapimodel->insertJourney($data2);
                    }
                    $push_data  = array(
                                        'title'         => 'Guest',
                                        'body'          => 'Congrats your booking confirmed',
                                        'action'        => 'complete_payment',
                                        'notificationId'=> 4,
                                        'sound'         => 'notification',
                                        'icon'          => 'icon'
                                    );
                    $res_msg = 'Congrats your booking confirmed.';
                }else{
                    $data       =   array(
                                    'status'         => 2,
                                    'cancelled_by'   => '',
                                    'cancelled_type' => 0,
                                    'view_by_guider' => 'N',
                                    'view_by_traveller' => 'N'
                                );
                    $result1    = $this->Serviceapimodel->updateServiceRequest( $data, $service_id );
                    $push_data  = array(
                                    'title'         => 'Guest',
                                    'body'          => 'Your request has been accepted. Please proceed to payment to complete booking.',
                                    'action'        => 'accept_request',
                                    'notificationId'=> 2,
                                    'sound'         => 'notification',
                                    'icon'          => 'icon'
                                  );
                    $res_msg = 'Service request accepted successfully.';
                }
                //PUSH NOTIFICATION
                $deviceTokenList  = $this->Travellerapimodel->travellerDeviceTokenList( $traveller_id );
                //PUSH NOTIFICATION GUIDER
                if($deviceTokenList){
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
                      $this->pushNotificationmodel->android_push_notification($device_tokenA, $push_data, 'T');
                    }
                    if (!empty($device_tokenI)) {
                        $this->pushNotificationmodel->sendPushNotification_ios($device_tokenI, $push_data, 'T');
                    }
                }
                //END PUSH NOTIFICATION
                $result     = array(
                                        'response_code'     => SUCCESS_CODE, 
                                        'response_description' => $res_msg,
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
    function acceptRequestValidate( $user_input ) {
        if( trim($user_input['request_primary_id']) == '' ){
            $this->error['warning']    = 'Service ID Cannot be empty';
        } else if ( !$this->Serviceapimodel->serviceInfo( $user_input['request_primary_id'] ) ){
            $this->error['warning']    = 'Invalid Service ID.';
        } else if ( $user_input['guider_id'] == '' ) {
            $this->error['warning']    = HOST_NAME.' ID Cannot be empty';
        } else if( !$this->Guiderapimodel->guiderInfoByUuid( $user_input['guider_id'] ) ) {
            $this->error['warning']    = 'Please enter valid '.HOST_NAME.' ID';
        } else if ( !$this->Serviceapimodel->guiderServiceInfo( $user_input['request_primary_id'], $user_input['guider_id'] ) ){
            $this->error['warning']    = 'Invalid Service ID.';
        }
        return !$this->error;
    }
    function cancelRequest() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->cancelRequestValidate( $user_input ) ) {
                $service_id     = trim( $user_input['request_primary_id'] );
                $serviceInfo    = $this->Serviceapimodel->serviceInfo( $service_id );
                $guider_id      = trim( $user_input['guider_id'] );
                $guiderInfo     = $this->Guiderapimodel->guiderInfoByUuid( $guider_id );
                $traveller_id   = $serviceInfo->service_traveller_id;
                //PUSH NOTIFICATION
                $deviceTokenList  = $this->Travellerapimodel->travellerDeviceTokenList( $traveller_id );
                //PUSH NOTIFICATION GUIDER
                $regionInfo     = $this->Guiderapimodel->stateInfoByid($guiderInfo->service_providing_region);
                if($regionInfo){
                    $regionName = $regionInfo->name;
                }else{
                    $regionName = '';
                }
                if($deviceTokenList){
                    $push_data      = array(
                                    'title'             => 'Guest',
                                    'body'              => 'Your request has been cancelled. '.$guiderInfo->first_name.','.$regionName.','.$serviceInfo->service_date.','.$serviceInfo->pickup_time,
                                    'action'            => 'cancel_request',
                                    'notificationId'    => 3,
                                    'sound'             => 'notification',
                                    'icon'              => 'icon'
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
                      $this->pushNotificationmodel->android_push_notification($device_tokenA, $push_data, 'T');
                    }
                    if (!empty($device_tokenI)) {
                        $this->pushNotificationmodel->sendPushNotification_ios($device_tokenI, $push_data, 'T');
                    }
                }
                //END PUSH NOTIFICATION

                $data       = array(
                                    'status'            => 3,
                                    'cancelled_by'      => 'G',
                                    'cancelled_type'    => 2,
                                    'view_by_guider'    => 'N',
                                    'view_by_traveller' => 'N'
                                    );
                $result1    = $this->Serviceapimodel->updateServiceRequest( $data, $service_id );
                $result     = array(
                                        'response_code'     => SUCCESS_CODE, 
                                        'response_description' => 'Service request cancelled successfully.',
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
    function cancelRequestValidate( $user_input ) {
        if( trim($user_input['request_primary_id']) == '' ){
            $this->error['warning']    = 'Service ID Cannot be empty';
        } else if ( !$this->Serviceapimodel->serviceInfo( $user_input['request_primary_id'] ) ){
            $this->error['warning']    = 'Invalid Service ID.';
        } else if ( $user_input['guider_id'] == '' ) {
            $this->error['warning']    = HOST_NAME.' ID Cannot be empty';
        } else if( !$this->Guiderapimodel->guiderInfoByUuid( $user_input['guider_id'] ) ) {
            $this->error['warning']    = 'Please enter valid '.HOST_NAME.' ID';
        } else if ( !$this->Serviceapimodel->guiderServiceInfo( $user_input['request_primary_id'], $user_input['guider_id'] ) ){
            $this->error['warning']    = 'Cannot update this Service ID';
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

                $guider_id    = trim( $user_input['guider_id'] );
                //$filter_type  = trim( $user_input['filter_type'] );
                $page_no      = trim( $user_input['page_no'] );
                $page_total   = trim( $user_input['page_total'] );
                if(!$page_no){
                    $page_no = 1;
                }
                $page_number = ($page_no) ? $page_no : 1;
                $offset      = ($page_number  == 1) ? 0 : ($page_number * $page_total) - $page_total;
                $this->Guiderapimodel->autoCompletedExpiryJourney($guider_id);
                $result      = $this->Guiderapimodel->getMyJourneyList( $page_total, $offset, $guider_id );
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
        if( !$this->Guiderapimodel->guiderInfoByUuid( $user_input['guider_id'] ) ) {
            $this->error['warning']    = HOST_NAME.' ID Not Exist.';
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
                $sender_type        = 1;
                $receiver_type      = 2;
                $createdon          = date("Y-m-d H:i:s");
                
                $data       = array(
                                    'cmt_guider_id'     => $guider_id,
                                    'cmt_traveller_id'  => $traveller_id,
                                    'comments'          => $message,
                                    'sender_type'       => $sender_type,
                                    'receiver_type'     => $receiver_type,
                                    'createdon'         => $createdon
                                    );
                $comment_id     = $this->Guiderapimodel->insertComments( $data );
                
                $guiderInfo     = $this->Guiderapimodel->guiderInfoByUuid( $guider_id );
                $profileImgPath = $this->config->item( 'upload_path_url' ).'g_profile/';
                $profile_image  = ($guiderInfo->profile_image) ? $profileImgPath.$guiderInfo->profile_image : '';
                $regionInfo     = $this->Guiderapimodel->stateInfoByid($guiderInfo->service_providing_region);
                if($regionInfo){
                    $regionName = $regionInfo->name;
                }else{
                    $regionName = '';
                }
                if($rating){ $is_rated = 1; }else{ $is_rated = 0; }
                $res_data   = array(
                                    'comment_id'        => intval($comment_id),
                                    'guider_id'         => intval($guider_id),
                                    'traveller_id'      => intval($traveller_id),
                                    'comments'          => $message,
                                    'first_name'        => $guiderInfo->first_name,
                                    'last_name'         => $guiderInfo->last_name,
                                    'profile_pic'       => $profile_image,
                                    'country'           => $guiderInfo->country_name,
                                    'city'              => $regionName,
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
            $this->error['warning']    = HOST_NAME.' ID Cannot be empty';
        } else if( !$this->Guiderapimodel->guiderInfoByUuid( $user_input['guider_id'] ) ) {
            $this->error['warning']    = HOST_NAME.' id not exist.';
        } else if( trim($user_input['traveller_id']) == '' ){
            $this->error['warning']    = GUEST_NAME.' ID Cannot be empty';
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
                $guider_id    = trim( $user_input['guider_id'] );
                $page_no      = trim( $user_input['page_no'] );
                $page_total   = trim( $user_input['page_total'] );
                if(!$page_no){
                    $page_no = 1;
                }
                $page_number = ($page_no) ? $page_no : 1;
                $offset      = ($page_number  == 1) ? 0 : ($page_number * $page_total) - $page_total;

                $result      = $this->Guiderapimodel->getCommentsList( $page_total, $offset, $guider_id );
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
        if( trim($user_input['guider_id']) == '' ){
            $this->error['warning']    = HOST_NAME.' ID Cannot be empty';
        } else if( !$this->Guiderapimodel->guiderInfoByUuid( $user_input['guider_id'] ) ) {
            $this->error['warning']    = HOST_NAME.' ID not exist.';
        }
        return !$this->error;
    }
    public function giveRatingsForTraveller() {
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
                $travellerInfo  = $this->Travellerapimodel->travellerInfoByUuid( $traveller_id );
                if($ratings == 1){
                   $ratingval   = 1; 
                }else{
                    $ratingval  = 5;
                }
                $totalrating    = $travellerInfo->ratings;
                if($totalrating){
                    $total      = ($totalrating + $ratingval)/2;
                }else{
                    $total      = $ratingval;
                }
                $data4          = array('ratings' => $total);
                $result1        = $this->Travellerapimodel->updateTravellerByUuid( $data4, $traveller_id );
                //JOURNEY COMPLETE STATUS
                if($journeyInfo->jny_status != 3){
                    $data5      = array('jny_status' => 3);
                    $update     = $this->Serviceapimodel->updateJourneyByServiceid( $data5, $service_id );
                }
                $data           = array(
                                        'traveller_rating'      => $ratingval,
                                        'traveller_feedback'    => $message,
                                        'traveller_feedbackon'  => date("Y-m-d H:i:s")
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
        } else if( $journeyInfo->traveller_rating != 0 ){
            $this->error['warning']    = 'You have already rated this journey.';
        } else if( strlen($user_input['message']) > 200 ){
            $this->error['warning']    = 'Comment message allowed maximum 200 characters.';
        }
        return !$this->error;
    }
    public function deleteMyComment() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->delete_comment_validate( $user_input ) ) {
                $guider_id      = trim( $user_input['guider_id'] );
                $comment_id     = trim( $user_input['comment_id'] );
                $comment_type   = trim( $user_input['comments_type'] ); //1->Ratings,2->InApp,3->webComments
                if($comment_type == 1){
                    $journeyInfo = $this->Serviceapimodel->journeyInfo( $comment_id );
                    if($journeyInfo->guider_rating == 0){
                        $status = 2;
                    }else{
                        $status = 1;
                        $data   = array('guider_feedback' => '');
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
        if( trim($user_input['guider_id']) == '' ){
            $this->error['warning']    = HOST_NAME.' ID Cannot be empty';
        } else if( !$this->Guiderapimodel->guiderInfoByUuid( $user_input['guider_id'] ) ) {
            $this->error['warning']    = HOST_NAME.' id not exist.';
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
                $result         = $this->Guiderapimodel->updateMessageList($guider_id, $traveller_id);
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
            $this->error['warning']    = HOST_NAME.' ID Cannot be empty';
        } else if( !$this->Guiderapimodel->guiderInfoByUuid( $user_input['guider_id'] ) ) {
            $this->error['warning']    = HOST_NAME.' id not exist.';
        } else if( trim($user_input['traveller_id']) == '' ){
            $this->error['warning']    = GUEST_NAME.' ID Cannot be empty';
        } else if ( !$this->Travellerapimodel->travellerInfoByUuid( $user_input['traveller_id'] ) ){
            $this->error['warning']    = 'Invalid '.GUEST_NAME.' ID.';
        }
        return !$this->error;
    }
    public function get_guider_count() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->get_guider_count_validate( $user_input ) ) {
                $guider_id      = trim( $user_input['guider_id'] );
                $result         = $this->Guiderapimodel->get_guider_count($guider_id);
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
    function get_guider_count_validate( $user_input ) {
        if( trim($user_input['guider_id']) == '' ){
            $this->error['warning']    = HOST_NAME.' ID Cannot be empty';
        } else if( !$this->Guiderapimodel->guiderInfoByUuid( $user_input['guider_id'] ) ) {
            $this->error['warning']    = HOST_NAME.' ID not exist.';
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
                $guider_id      = trim( $user_input['guider_id'] );
                $service_id     = trim( $user_input['request_primary_id'] );
                $filter_type    = trim( $user_input['filter_type'] );
                $result         = $this->Guiderapimodel->updateReadServiceCount($guider_id, $service_id, $filter_type);
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
        if( trim($user_input['guider_id']) == '' ){
            $this->error['warning']    = HOST_NAME.' ID Cannot be empty';
        } else if( !$this->Guiderapimodel->guiderInfoByUuid( $user_input['guider_id'] ) ) {
            $this->error['warning']    = HOST_NAME.' id not exist.';
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
                $guider_id      = trim( $user_input['guider_id'] );
                $service_id     = trim( $user_input['request_primary_id'] );
                $filter_type    = trim( $user_input['filter_type'] );
                $result         = $this->Guiderapimodel->updateReadJourneyCount($guider_id, $service_id, $filter_type);
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

        if( trim($user_input['guider_id']) == '' ){
            $this->error['warning']    = HOST_NAME.' ID Cannot be empty';
        } else if( !$this->Guiderapimodel->guiderInfoByUuid( $user_input['guider_id'] ) ) {
            $this->error['warning']    = HOST_NAME.' id not exist.';
        } else if( trim($user_input['request_primary_id']) == '' ){
            $this->error['warning']    = 'Service ID Cannot be empty';
        } else if ( !$this->Serviceapimodel->journeyInfo( $user_input['request_primary_id'] ) ){
            $this->error['warning']    = 'Invalid Service ID.';
        } else if( trim($user_input['filter_type']) == '' ){
            $this->error['warning']    = 'Filter Type Cannot be empty';
        }
        return !$this->error;
    }
    public function guider_history() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->guider_history_validate( $user_input ) ) {
                $guider_id      = trim( $user_input['guider_id'] );
                $report_type    = trim( $user_input['report_type'] );
                $result         = $this->Guiderapimodel->guiderHistoryLists($guider_id, $report_type);
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
    function guider_history_validate( $user_input ) {

        if( trim($user_input['guider_id']) == '' ){
            $this->error['warning']    = HOST_NAME.' ID Cannot be empty';
        } else if( !$this->Guiderapimodel->guiderInfoByUuid( $user_input['guider_id'] ) ) {
            $this->error['warning']    = HOST_NAME.' id not exist.';
        } else if( trim($user_input['report_type']) == '' ){
            $this->error['warning']    = 'Report Type Cannot be empty';
        }
        return !$this->error;
    }
    public function guiderinfo() {
        //error_reporting(E_ALL);
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array('message' => 'Authorization error', 'result' => 'error');
        } else if( $input != '' ) {
            $user_input = get_object_vars($input);
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->infovalidate( $user_input ) ) {
                $guider_id  = trim( $user_input['guider_id'] );
                
                $result         = $this->Guiderapimodel->guiderInfo( $guider_id );
                $updateDevice   = $this->Guiderapimodel->updateDeviceInfo( $_SERVER, $guider_id );
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
    //GET GUIDER INFO Validation
    function infovalidate( $user_input ) {
        if( trim($user_input['guider_id']) == '' ){
            $this->error['warning']    = HOST_NAME.' ID Cannot be empty';
        } else if( !$this->Guiderapimodel->guiderInfoByUuid( $user_input['guider_id'] ) ) {
            $this->error['warning']    = HOST_NAME.' id not exist.';
        }
        return !$this->error;
    }



    function getCompletedJourneys() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
            if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
                $guider_id  = trim( $user_input['guider_id'] );
                $filtertype = trim($user_input['filtertype']);
                $date       = trim( $user_input['date'] );
                $result     = $this->Guiderapimodel->getCompletedJourneyList( $guider_id, $filtertype, $date );
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
    //Get Language List
    function get_list_of_languages() {
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) {
            $result         = $this->Guiderapimodel->get_language_lists();
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
    function send_feedback() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->feedback_validate( $user_input ) ) {
                $createdon      = date("Y-m-d H:i:s");
                $guider_id      = trim( $user_input['guider_id'] );
                $subject        = trim( $user_input['subject'] );
                $message        = trim( $user_input['message'] );
                $device_token   = $_SERVER['HTTP_DEVICE_TOKEN'];
                $device_id      = $_SERVER['HTTP_DEVICE_ID'];
                $app_version    = $_SERVER['HTTP_APP_VERSION'];
                $device_type    = $_SERVER['HTTP_DEVICE_TYPE'];
                $build_no       = $_SERVER['HTTP_BUILD_NO'];
                $data = array();
                $data['fb_guider_id'] = $guider_id;
                $data['subject']      = $subject;
                $data['description']  = $message;
                if($device_token){ $data['device_token'] = $device_token; }
                if($device_id){ $data['device_id'] = $device_id; }
                if($app_version){ $data['app_version'] = $app_version; }
                if($device_type){ $data['device_type'] = $device_type; }
                if($build_no){ $data['build_no'] = $build_no; }
                $data['createdon']    = $createdon;
                $this->Commonapimodel->insertGuiderFeedback($data);
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
        if( trim($user_input['guider_id']) == '' ){
            $this->error['warning']    = HOST_NAME.' ID Cannot be empty';
        } else if( !$this->Guiderapimodel->guiderInfoByUuid( $user_input['guider_id'] ) ) {
            $this->error['warning']    = HOST_NAME.' ID not exist.';
        } else if ( trim( $user_input['subject'] ) == '' ){
            $this->error['warning']    = 'Subject Cannot be empty';
        } else if ( strlen( $user_input['subject'] ) < 3 || (strlen( $user_input['subject'] ) > 60)){
            $this->error['warning']    = 'Subject Minimum 3 & maximum 60 characters';
        } else if ( trim( $user_input['message'] ) == '' ){
            $this->error['warning']    = 'Message Cannot be empty';
        }
        return !$this->error;
    }
    public function updateActivity(){
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->activity_validate( $user_input ) ) {
                $guider_id           = trim( $user_input['guider_id'] );
                $whatIOffer          = $user_input['what_i_offer'];
                $price_type_id       = trim( $user_input['price_type_id'] );
                if($price_type_id == 3){
                    $rate_per_person = 0;
                }else{
                    $rate_per_person = trim( $user_input['rate_per_person'] );
                }
                $guiding_speciality  = $user_input['guiding_speciality'];
                $support_region      = trim( $user_input['support_region'] );
                $cancellation_policy = $user_input['cancellation_policy'];
                $activity_id         = trim( $user_input['activity_id'] );
                $maximum_booking     = $user_input['maximum_booking'];
                $additional_info_label = $user_input['additional_info_label'];
                $date_time_needed    = ($user_input['date_time_needed'])? $user_input['date_time_needed'] : 0;

                $data               = array( 
                                        'activity_guider_id'    => intval($guider_id),
                                        'currency_preferrable'  => '',
                                        'what_i_offer'          => $whatIOffer,
                                        'cancellation_policy'   => $cancellation_policy
                                    );
                if (count($guiding_speciality) > 0){
                    $guiding_speciality         = implode(',', $guiding_speciality);
                    $data['guiding_speciality'] = $guiding_speciality;
                }
                $data['rate_per_person'] = $rate_per_person;
                if ($support_region){
                    $data['service_providing_region'] = $support_region;
                }
                if ($price_type_id){
                    $data['price_type_id'] = $price_type_id;
                }
                if ($maximum_booking){
                    $data['maximum_booking'] = $maximum_booking;
                }
                if ($additional_info_label){
                    $data['additional_info_label'] = $additional_info_label;
                }
                if ($date_time_needed == 0 || $date_time_needed == 1){
                    $data['date_time_needed'] = $date_time_needed;
                }
                if($activity_id){
                    $this->Guiderapimodel->updateGuiderActivtyByUuid( $data, $activity_id );
                }else{
                    $data['createdon'] = date('Y-m-d h:i:s');
                    $this->Guiderapimodel->insertGuiderActivity( $data );
                }
                //$updateDevice   = $this->Guiderapimodel->updateDeviceInfo( $_SERVER, $guider_id );
                $result         = array(
                                        'response_code'     => SUCCESS_CODE, 
                                        'response_description' => 'Service Information updated successfully.',
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
    //Validation here
    private function activity_validate( $user_input ) {
        if( trim($user_input['guider_id']) == '' ){
            $this->error['warning']    = HOST_NAME.' ID Cannot be empty';
        } else if( !$this->Guiderapimodel->guiderInfoByUuid( $user_input['guider_id'] ) ) {
            $this->error['warning']    = HOST_NAME.' ID not exist.';
        }
        if(trim($user_input['activity_id'])){
            if( !$this->Guiderapimodel->guiderActivtyInfoByUuid( $user_input['activity_id'] ) ) {
                $this->error['warning']    = HOST_NAME.' Activity ID not exist.';
            }
        }
        if (trim($user_input['rate_per_person'])){
            if(trim($user_input['price_type_id']) != 3){
                if ( intval($user_input['rate_per_person']) < MIN_RATE || intval($user_input['rate_per_person']) > MAX_RATE ) {
                    $this->error['warning']    = 'Minimum pricing '.MIN_RATE.' and maximum '.MAX_RATE.' '.CURRENCYCODE;
                }
            }
        }
        if (trim( $user_input['support_region'] )){
            if( !$this->Guiderapimodel->stateInfoByid( $user_input['support_region'] ) ) {
                $this->error['warning']    = 'Invalid Support Region ID';
            }
        }
        if (trim( $user_input['what_i_offer'] )){
            if ( strlen( $user_input['what_i_offer'] ) < 10 ){
                $this->error['warning']    = 'What I Offer minimum Length 10 characters';
            }
        }
        if (trim($user_input['cancellation_policy'])){
            if ( strlen( $user_input['cancellation_policy'] ) < 10 ){
                $this->error['warning']    = 'Cancellation Policy minimum Length 10 characters';
            }
        }
        return !$this->error;
    }
    public function updateActivityStatus(){
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->activitystatus_validate( $user_input ) ) {
                $status         = $user_input['status'];
                $activity_id    = trim( $user_input['activity_id'] );
                $guider_id      = trim( $user_input['guider_id'] );

                $data           = array('activity_status' => $status);
                $this->Guiderapimodel->updateGuiderActivtyStatus( $data, $activity_id, $guider_id );
                $result         = array(
                                        'response_code'     => SUCCESS_CODE, 
                                        'response_description'=> 'Service Information updated successfully.',
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
    //Validation here
    private function activitystatus_validate( $user_input ) {
        if( trim($user_input['activity_id']) == '' ){
            $this->error['warning']    = HOST_NAME.' Activity ID Cannot be empty';
        } else if( !$this->Guiderapimodel->guiderActivtyInfoByUuid( $user_input['activity_id'] ) ) {
            $this->error['warning']    = HOST_NAME.' Activity ID not exist.';
        }else if( trim($user_input['guider_id']) == '' ){
            $this->error['warning']    = HOST_NAME.' ID Cannot be empty';
        } else if( !$this->Guiderapimodel->guiderInfoByUuid( $user_input['guider_id'] ) ) {
            $this->error['warning']    = HOST_NAME.' ID not exist.';
        } else if(trim($user_input['status']) != 1 && trim($user_input['status']) != 2) {
            $this->error['warning']    = 'Invalid Status';
        }
        return !$this->error;
    }
    public function getMyActivity() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ){
            $result = array('message' => 'Authorization error', 'result' => 'error');
        } else if( $input != '' ){
            $user_input = get_object_vars($input);
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->activityValidate( $user_input ) ) {
                $guider_id  = trim( $user_input['guider_id'] );
                $result     = $this->Guiderapimodel->getMyActivity( $guider_id );
            } else {
                if( $_SERVER['REQUEST_METHOD'] != 'POST' ){
                    $result = array('message' => 'Undefined Request Method', 'result' => 'error');
                } else if (isset($this->error['warning'])){
                    $result = array('response_code' => ERROR_CODE, 'response_description' => $this->error['warning'], 'result' => 'error', 'data'=>array('error' => 1));
                }
            }
        } else {
            $result = array('message' => 'No Input Received', 'result' => 'error');
        }
        echo json_encode($result);
    }
    function activityValidate( $user_input ) {
        if( !$this->Guiderapimodel->guiderInfoByUuid( $user_input['guider_id'] ) ) {
            $this->error['warning']    = HOST_NAME.' ID Not Exist.';
        }
        return !$this->error;
    }
    public function uploadproof() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ){
            $result = array('message' => 'Authorization error', 'result' => 'error');
        } else if( $input != '' ){
            $_POST = get_object_vars($input);
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->validateproof( $_POST ) ) {
                $guider_id  = $_POST['guider_id'];
                $id_proof   = $_POST['identity_document'];
                $data       = array( 'id_proof'=> $id_proof );
                $updateDate = $this->Guiderapimodel->updateGuiderByUuid( $data, $guider_id );

                $res_msg = 'Identity Proof updated successfully.';
                $result  = $this->Guiderapimodel->guiderProfileInfo($guider_id, $new = 0, $res_msg, $verify_email = 0);
            } else {
                if( $_SERVER['REQUEST_METHOD'] != 'POST' ){
                    $result = array('message' => 'Undefined Request Method', 'result' => 'error');
                } else if (isset($this->error['warning'])){
                    $result = array('response_code' => ERROR_CODE, 'response_description' => $this->error['warning'], 'result' => 'error', 'data'=>array('error' => 1));
                }
            }
        } else {
            $result = array('message' => 'No Input Received', 'result' => 'error');
        }
        echo json_encode($result);
    }
    function validateproof($user_input) {
        if( trim($user_input['guider_id']) == '' ){
            $this->error['warning']    = HOST_NAME.' ID Cannot be empty';
        } else if( !$this->Guiderapimodel->guiderInfoByUuid( $user_input['guider_id'] ) ) {
            $this->error['warning']    = HOST_NAME.' id not exist.';
        } else if( trim($user_input['identity_document']) == '' ) {
            $this->error['warning']    = 'Identity Document Cannot be empty';
        }
        return !$this->error;
    }
    public function firstActivityUpload() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ){
            $result = array('message' => 'Authorization error', 'result' => 'error');
        } else if( $input != '' ){
            $_POST = get_object_vars($input);
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->validateActivity( $_POST ) ) {
                $guider_id      = $_POST['guider_id'];
                $activity_imgs  = $_POST['activity_pictures'];
                $data = array();
                $i = 1;
                foreach ($activity_imgs as $key => $value) {
                    $data['photo_'.$i] = $value;
                    $i++;
                }
                $data['activity_guider_id'] = $guider_id;
                $data['createdon']          = date('Y-m-d h:i:s');
                $insert  = $this->Guiderapimodel->insertGuiderActivity( $data );
                
                $res_msg = 'Activity images updated successfully.';
                $result  = $this->Guiderapimodel->guiderProfileInfo($guider_id, $new = 0, $res_msg, $verify_email = 0);
            } else {
                if( $_SERVER['REQUEST_METHOD'] != 'POST' ){
                    $result = array('message' => 'Undefined Request Method', 'result' => 'error');
                } else if (isset($this->error['warning'])){
                    $result = array('response_code' => ERROR_CODE, 'response_description' => $this->error['warning'], 'result' => 'error', 'data'=>array('error' => 1));
                }
            }
        } else {
            $result = array('message' => 'No Input Received', 'result' => 'error');
        }
        echo json_encode($result);
    }
    function validateActivity($user_input) {
        if( trim($user_input['guider_id']) == '' ){
            $this->error['warning']    = HOST_NAME.' ID Cannot be empty';
        } else if( !$this->Guiderapimodel->guiderInfoByUuid( $user_input['guider_id'] ) ) {
            $this->error['warning']    = HOST_NAME.' ID not exist.';
        } else if( count($user_input['activity_pictures']) <= 0 ) {
            $this->error['warning']    = 'Activity Pictures Cannot be empty';
        }
        return !$this->error;
    }
    public function updateActivitiesPhotos() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ){
            $result = array('message' => 'Authorization error', 'result' => 'error');
        } else if( $input != '' ){
            $_POST = get_object_vars($input);
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->validateUpdateActivity( $_POST ) ) {
                $guider_id          = $_POST['guider_id'];
                $activity_imgs      = $_POST['activity_pictures'];
                $activity_id        = $_POST['activity_id'];
                $data = array();
                $i = 1;
                foreach ($activity_imgs as $key => $value) {
                    $data['photo_'.$i] = $value;
                    $i++;
                }
                $data['activity_guider_id'] = $guider_id;
                $data['createdon']          = date('Y-m-d h:i:s');
                $update  = $this->Guiderapimodel->updateGuiderActivtyByUuid( $data, $activity_id );

                $res_msg = 'Activity images updated successfully.';
                $result  = $this->Guiderapimodel->guiderProfileInfo($guider_id, $new = 0, $res_msg, $verify_email = 0);
            } else {
                if( $_SERVER['REQUEST_METHOD'] != 'POST' ){
                    $result = array('message' => 'Undefined Request Method', 'result' => 'error');
                } else if (isset($this->error['warning'])){
                    $result = array('response_code' => ERROR_CODE, 'response_description' => $this->error['warning'], 'result' => 'error', 'data'=>array('error' => 1));
                }
            }
        } else {
            $result = array('message' => 'No Input Received', 'result' => 'error');
        }
        echo json_encode($result);
    }
    function validateUpdateActivity($user_input) {
        if( trim($user_input['guider_id']) == '' ){
            $this->error['warning']    = HOST_NAME.' ID Cannot be empty';
        } else if( !$this->Guiderapimodel->guiderInfoByUuid( $user_input['guider_id'] ) ) {
            $this->error['warning']    = HOST_NAME.' id not exist.';
        } else if( count($user_input['activity_pictures']) <= 0 ) {
            $this->error['warning']    = 'Activity Pictures Cannot be empty';
        } else if( trim($user_input['activity_id']) == '' ){
            $this->error['warning']    = 'Activity ID Cannot be empty';
        } else if( !$this->Guiderapimodel->guiderActivtyInfoByUuid( $user_input['activity_id'] ) ) {
            $this->error['warning']    = HOST_NAME.' Activity ID not exist.';
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
                $appInfo = $this->Commonapimodel->getGuiderAppversion($device_type);
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