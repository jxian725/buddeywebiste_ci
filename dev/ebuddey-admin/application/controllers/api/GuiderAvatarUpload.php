<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GuiderAvatarUpload extends CI_Controller{

    private $error = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('api/Guiderapimodel');
        header("content-type:application/json");
    }
    public function index() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ){
            $result = array('message' => 'Authorization error', 'result' => 'error');
        } else if( $input != '' ){
            $_POST = get_object_vars($input);
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->validate( $_POST ) ) {
                $guider_id      = $_POST['guider_id'];
                $profile_pic    = $_POST['profile_pic'];
                $data           = array( 'profile_image'=> $profile_pic );
                $result1        = $this->Guiderapimodel->updateGuiderByUuid( $data, $guider_id );
                $guiderInfo     = $this->Guiderapimodel->guiderInfoByUuid( $guider_id );
                $upload_path_url    = $this->config->item( 'upload_path_url' );
                $profileImgPath     = $upload_path_url.'g_profile/';
                $activityImgPath    = $upload_path_url.'g_activity/';
                $profile_image      = ($guiderInfo->profile_image)?(filter_var($guiderInfo->profile_image, FILTER_VALIDATE_URL) === FALSE) ? $profileImgPath.$guiderInfo->profile_image : $guiderInfo->profile_image : '';
                $img_id_proof       = ($guiderInfo->id_proof)?(filter_var($guiderInfo->id_proof, FILTER_VALIDATE_URL) === FALSE) ? $upload_path_url.'identity/'.$guiderInfo->id_proof : $guiderInfo->id_proof : '';
                $dbkl_lic           = ($guiderInfo->dbkl_lic)?(filter_var($guiderInfo->dbkl_lic, FILTER_VALIDATE_URL) === FALSE) ? $upload_path_url.'dbkl/'.$guiderInfo->dbkl_lic : $guiderInfo->dbkl_lic : '';
                $randActivityIfo    = $this->Guiderapimodel->getGuiderRandomActivity($guiderInfo->guider_id);
                if(count($randActivityIfo) > 0){
                    $photo          = ($randActivityIfo['photo_1'])?(filter_var($randActivityIfo['photo_1'], FILTER_VALIDATE_URL) === FALSE) ? $activityImgPath.$randActivityIfo['photo_1'] : $randActivityIfo['photo_1'] : '';
                    $photo1         = ($randActivityIfo['photo_2'])?(filter_var($randActivityIfo['photo_2'], FILTER_VALIDATE_URL) === FALSE) ? $activityImgPath.$randActivityIfo['photo_2'] : $randActivityIfo['photo_2'] : '';
                    $photo2         = ($randActivityIfo['photo_3'])?(filter_var($randActivityIfo['photo_3'], FILTER_VALIDATE_URL) === FALSE) ? $activityImgPath.$randActivityIfo['photo_3'] : $randActivityIfo['photo_3'] : '';
                    $what_i_offer        = $randActivityIfo['what_i_offer'];
                    $cancellation_policy = $randActivityIfo['cancellation_policy'];
                    $spec                = $randActivityIfo['guiding_speciality'];
                    $regionName          = $randActivityIfo['service_providing_region'];
                    $rate_per_person     = $randActivityIfo['rate_per_person'];
                    $price_type_id       = $randActivityIfo['price_type_id'];
                }else{
                    $photo          = '';
                    $photo1         = '';
                    $photo2         = '';
                    $what_i_offer           = '';
                    $cancellation_policy    = '';
                    $spec                   = [];
                    $regionName             = '';
                    $rate_per_person        = '';
                    $price_type_id          = 0;
                }
                if(!$photo && !$photo1 && !$photo2){
                    $photo  = $upload_path_url.'default_service.png';
                }
                if($guiderInfo->age == '0000-00-00'){
                    $age    = 0;
                }else{
                    $age    = date_diff(date_create($guiderInfo->age), date_create('today'))->y;
                }
                $lang = [];
                if($guiderInfo->languages_known){
                    $array  = explode(',', $guiderInfo->languages_known);
                    foreach ($array as $item) {
                        $langInfo = $this->Guiderapimodel->guiderLangInfo($item);
                        if($langInfo){ $lang[] = $langInfo->language; }
                    }
                }
                $commentCount1  = $this->Guiderapimodel->feedbackTotalCount('G',$guiderInfo->guider_id);
                $commentCount2  = $this->Guiderapimodel->commentTotalCount('G',$guiderInfo->guider_id);
                $commentCount3  = $this->Guiderapimodel->webcommentTotalCount('G',$guiderInfo->guider_id);
                $commentCount   = $commentCount1 + $commentCount2 + $commentCount3;
                $res_data       = array(
                                    'guider_id'     => intval($guiderInfo->guider_id),
                                    'g_id'          => $guiderInfo->g_id,
                                    'first_name'    => $guiderInfo->first_name,
                                    'last_name'     => $guiderInfo->last_name,
                                    'email'         => $guiderInfo->email,
                                    'phone_number'  => $guiderInfo->phone_number,
                                    'country_code'  => $guiderInfo->countryCode,
                                    'about_me'      => $guiderInfo->about_me,
                                    'age'           => $age,
                                    'DOB'           => $guiderInfo->age,
                                    'languages_known'=> $lang,
                                    'acc_no'        => $guiderInfo->acc_no,
                                    'acc_name'      => $guiderInfo->acc_name,
                                    'bank_name'     => $guiderInfo->bank_name,
                                    'branch_name'   => $guiderInfo->branch_name,
                                    'service_providing_region'=> $regionName,
                                    'guiding_speciality' => $spec,
                                    'what_i_offer'  => $what_i_offer,
                                    'rate_per_person' => floatval($rate_per_person),
                                    'price_type_id'   => intval($price_type_id),
                                    'id_proof'        => $img_id_proof,
                                    'activity_photo_1'=> $photo,
                                    'activity_photo_2'=> $photo1,
                                    'activity_photo_3'=> $photo2,
                                    'profile_pic'     => $profile_image,
                                    'dbkl_lic'        => $dbkl_lic,
                                    'dbkl_lic_no'     => $guiderInfo->dbkl_lic_no,
                                    'nric_number'     => $guiderInfo->nric_number,
                                    'device_id'       => '',
                                    'created_on'      => $guiderInfo->created_on,
                                    'rating'          => floatval($guiderInfo->rating),
                                    'comment_count'   => intval($commentCount),
                                    'cancellation_policy' => $cancellation_policy,
                                    'status'            => intval($guiderInfo->status),
                                    'country_name'              => $guiderInfo->country_name,
                                    'country_short_code'        => $guiderInfo->country_short_code,
                                    'country_currency_code'     => $guiderInfo->country_currency_code,
                                    'country_currency_symbol'   => $guiderInfo->country_currency_symbol,
                                    'country_time_zone'         => $guiderInfo->country_time_zone
                                    );
                $profilePer = 0;
                if($guiderInfo->first_name == '' || $guiderInfo->last_name == '' || $guiderInfo->age == '0000-00-00' || 
                    $guiderInfo->phone_number == '' || $guiderInfo->email == '' || $guiderInfo->about_me == '' ||
                    $guiderInfo->languages_known == ''){
                    $profile_updated    = 0;
                }else{
                    $profilePer         += 17;
                    $profile_updated    = 1;
                }
                if($guiderInfo->acc_no == '' || $guiderInfo->acc_name == '' || $guiderInfo->bank_name == ''){
                    $bank_updated    = 0;
                }else{
                    $profilePer     += 17;
                    $bank_updated    = 1;
                }
                if( $rate_per_person == '' || $spec == '' || $what_i_offer == '' || $service_providing_region == '' ){
                    $service_updated = 0;
                }else{
                    $profilePer     += 17;
                    $service_updated = 1;
                }
                if($guiderInfo->id_proof){
                    $profilePer          += 17;
                    $is_identity_uploaded = 1;
                }else{ 
                    $is_identity_uploaded = 0; 
                }
                if($photo == '' && $photo1 == '' && $photo2 == ''){
                    $is_activity_pic_uploaded = 0;
                }else{
                    $is_activity_pic_uploaded   = 1;
                }
                if($photo){ $profilePer += 5; }
                if($photo1){ $profilePer += 5; }
                if($photo2){ $profilePer += 5; }
                if($guiderInfo->profile_image == ''){
                    $is_profile_pic_uploaded  = 0;
                }else{
                    $profilePer                 += 17;
                    $is_profile_pic_uploaded    = 1;
                }
                $profile_strength = $profilePer;
                $result         = array(
                                        'response_code'     => SUCCESS_CODE, 
                                        'response_description' => 'Profile image updated successfully.',
                                        'result'            => 'success',
                                        'is_profile_updated'=> $profile_updated,
                                        'is_bank_updated'   => $bank_updated,
                                        'is_service_updated'=> $service_updated,
                                        'is_identity_uploaded'=> $is_identity_uploaded,
                                        'is_activity_pic_uploaded' => $is_activity_pic_uploaded,
                                        'is_profile_pic_uploaded'=> $is_profile_pic_uploaded,
                                        'is_dbkl_uploaded'  => intval($guiderInfo->dbkl_status),
                                        'profile_strength'  => $profile_strength,
                                        'is_new_user'       => 0,
                                        'data'              => $res_data
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
        if( trim($user_input['guider_id']) == '' ){
            $this->error['warning']    = 'Guider ID Cannot be empty';
        } else if( !$this->Guiderapimodel->guiderInfoByUuid( $user_input['guider_id'] ) ) {
            $this->error['warning']    = 'Guider id not exist.';
        } else if( trim($user_input['profile_pic']) == '' ) {
            $this->error['warning']    = 'Profile image Cannot be empty';
        }
        return !$this->error;
    }
}
?>