<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TravellerAvatarUpload extends CI_Controller{

    private $error = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('api/Travellerapimodel');
        header("content-type:application/json");
    }
    public function index() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ){
            $result = array('message' => 'Authorization error', 'result' => 'error');
        } else if( $input != '' ){
            $_POST = get_object_vars($input);
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->validate( $_POST ) ) {
                $traveller_id   = $_POST['traveller_id'];
                $profile_pic    = $_POST['profile_pic'];
                $data           = array( 'profile_image'=> $profile_pic );
                $result1        = $this->Travellerapimodel->updateTravellerByUuid( $data, $traveller_id );
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
                $is_new_user    = 0;
                $result         = array(
                                    'response_code'             => SUCCESS_CODE, 
                                    'response_description'      => 'Profile image updated successfully.', 
                                    'result'                    => 'success',
                                    "is_basic_profile_updated"  => $is_bpu,
                                    "is_full_profile_updated"   => $profile_updated,
                                    "is_profile_pic_updated"    => $is_profile_pic_updated,
                                    "is_new_user"               => $is_new_user,
                                    'data'                      => $res_data
                                    );
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
    function validate($user_input) {
        if( trim($user_input['traveller_id']) == '' ){
            $this->error['warning']    = 'Traveller ID Cannot be empty';
        } else if ( !$this->Travellerapimodel->travellerInfoByUuid( $user_input['traveller_id'] ) ){
            $this->error['warning']    = 'Invalid Traveller ID.';
        } else if( trim($user_input['profile_pic']) == '' ) {
            $this->error['warning']    = 'Profile image Cannot be empty';
        }
        return !$this->error;
    }
}
?>