<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GuiderProfileUpload extends CI_Controller{

    private $error = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('api/Guiderapimodel');
        $this->load->helper('timezone');
        /*header("content-type:application/json");*/
    }
    public function index() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array('message' => 'Authorization error', 'result' => 'error');
        } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if( (count($_POST) > 0) && $this->validate( $_POST ) ) {
                $upload_path_url    = $this->config->item( 'upload_path_url' );
                $upload_path        = $this->config->item( 'upload_path' );
                $profileImgPath     = $upload_path_url.'g_profile/';
                $profileUploadPath  = $upload_path.'g_profile/';

                $guider_id      = $_POST['guider_id'];
                $guiderinfo     = $this->Guiderapimodel->guiderInfoByUuid( $guider_id );
                $img1           = ($guiderinfo->profile_image) ? $profileImgPath.$guiderinfo->profile_image : '';
                $uploadimg1     = ($guiderinfo->profile_image) ? $profileUploadPath.$guiderinfo->profile_image : '';
                $profile_pic    = $_FILES['profile_pic'];
                
                $config['upload_path']   = './uploads/g_profile/'; 
                $config['allowed_types'] = 'gif|jpg|jpeg|png'; 
                $config['max_size']      = 15360; //15MB = 15*1024
                //$config['encrypt_name']  = true;
                $config['max_width']     = 4800;
                $config['max_height']    = 4800;
                
                if($profile_pic){
                    $name       = $_FILES["profile_pic"]["name"];
                    $ext        = end((explode(".", $name)));
                    $new_name   = $guider_id.'_guider_profile'.time() .'.'.$ext;
                    $config['file_name']     = $new_name;
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('profile_pic')) {
                        $error  = array('error' => $this->upload->display_errors());
                        $result = array('response_code' => ERROR_CODE, 'response_description' => strip_tags($error['error']), 'result' => 'error', 'data'=>array('error' => 1));
                    }else {
                        $upload_data    = $this->upload->data();
                        $photo          = $upload_data['file_name'];
                        $uploadphotourl = $profileUploadPath.$upload_data['file_name'];
                        //COMPRESS IMAGE SIZE
                        compress_image($_FILES["profile_pic"]["tmp_name"], $uploadphotourl, COMPRESS_IMG_SIZE);
                        $img1           = $profileImgPath.$photo;
                        $data1          = array( 'profile_image'=> $photo );
                        $result1        = $this->Guiderapimodel->updateGuiderByUuid( $data1, $guider_id );
                        if( file_exists( $uploadimg1 ) ) {
                            unlink( $uploadimg1 );
                        }
                        $guiderInfo         = $this->Guiderapimodel->guiderInfoByUuid( $guider_id );
                        $upload_path_url    = $this->config->item( 'upload_path_url' );
                        $profileImgPath     = $upload_path_url.'g_profile/';
                        $activityImgPath    = $upload_path_url.'g_activity/';
                        $profile_image      = ($guiderInfo->profile_image) ? $profileImgPath.$guiderInfo->profile_image : '';
                        $photo              = ($guiderInfo->photo) ? $activityImgPath.$guiderInfo->photo : '';
                        $photo1             = ($guiderInfo->photo1) ? $activityImgPath.$guiderInfo->photo1 : '';
                        $photo2             = ($guiderInfo->photo2) ? $activityImgPath.$guiderInfo->photo2 : '';
                        $img_id_proof       = ($guiderInfo->id_proof) ? $upload_path_url.'identity/'.$guiderInfo->id_proof : '';
                        $dbkl_lic           = ($guiderInfo->dbkl_lic) ? $upload_path_url.'dbkl/'.$guiderInfo->dbkl_lic : '';
                        if($guiderInfo->age == '0000-00-00'){
                            $age    = 0;
                        }else{
                            $age    = date_diff(date_create($guiderInfo->age), date_create('today'))->y;
                        }
                        $lang = [];
                        if($guiderInfo->languages_known){
                            $array =  explode(',', $guiderInfo->languages_known);
                            foreach ($array as $item) {
                                $langInfo = $this->Guiderapimodel->guiderLangInfo($item);
                                if($langInfo){ $lang[] = $langInfo->language; }
                            }
                        }
                        $spec = [];
                        if($guiderInfo->guiding_speciality){
                            $array =  explode(',', $guiderInfo->guiding_speciality);
                            foreach ($array as $item) {
                                $specInfo = $this->Guiderapimodel->guiderSpecialityInfo($item);
                                if($specInfo){ $spec[] = $specInfo->specialization; }
                            }
                        }
                        $regionInfo     = $this->Guiderapimodel->stateInfoByid($guiderInfo->service_providing_region);
                        if($regionInfo){
                            $regionName = $regionInfo->name;
                        }else{
                            $regionName = '';
                        }
                        $commentCount1  = $this->Guiderapimodel->feedbackTotalCount('G',$guiderInfo->guider_id);
                        $commentCount2  = $this->Guiderapimodel->commentTotalCount('G',$guiderInfo->guider_id);
                        $commentCount3  = $this->Guiderapimodel->webcommentTotalCount('G',$guiderInfo->guider_id);
                        $commentCount   = $commentCount1 + $commentCount2 + $commentCount3;
                        $res_data       = array(
                                            'guider_id'     => intval($guiderInfo->guider_id),
                                            'g_id'          => $guiderInfo->guider_id,
                                            'device_id'     => "",
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
                                            'what_i_offer'    => $guiderInfo->what_i_offer,
                                            'rate_per_person' => floatval($guiderInfo->rate_per_person),
                                            'price_type_id'   => intval($guiderInfo->price_type_id),
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
                                            'cancellation_policy'    => $guiderInfo->cancellation_policy,
                                            'status'                 => intval($guiderInfo->status),
                                            'country_name'           => $guiderInfo->country_name,
                                            'country_short_code'     => $guiderInfo->country_short_code,
                                            'country_currency_code'  => $guiderInfo->country_currency_code,
                                            'country_currency_symbol'=> $guiderInfo->country_currency_symbol,
                                            'country_time_zone'      => $guiderInfo->country_time_zone
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
                        if( $guiderInfo->rate_per_person == '' || $guiderInfo->guiding_speciality == '' || 
                            $guiderInfo->what_i_offer == '' || $guiderInfo->service_providing_region == '' ){
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
                        if($guiderInfo->photo == '' && $guiderInfo->photo1 == '' && $guiderInfo->photo2 == ''){
                            $is_activity_pic_uploaded = 0;
                        }else{
                            $is_activity_pic_uploaded   = 1;
                        }
                        if($guiderInfo->photo){ $profilePer += 5; }
                        if($guiderInfo->photo1){ $profilePer += 5; }
                        if($guiderInfo->photo2){ $profilePer += 5; }
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
                    }
                }else{
                    $result = array('response_code' => ERROR_CODE, 'response_description' => 'Please select profile image', 'result' => 'error', 'data'=>array('error' => 1));
                }
                
            } else {
                if(count($_POST) == 0) {
                    $result = array('message' => 'No Input Received', 'result' => 'error');
                } else if (isset($this->error['warning'])) {
                    $result = array('response_code' => ERROR_CODE, 'response_description' => $this->error['warning'], 'result' => 'error', 'data'=>array('error' => 1));
                }
            }
        }else {
            $result = array('message' => 'Undefined Request Method', 'result' => 'error');
        }
        echo json_encode($result);
    }
    function validate($user_input) {
        if( trim($user_input['guider_id']) == '' ){
            $this->error['warning']    = 'Talent ID Cannot be empty';
        } else if( !$this->Guiderapimodel->guiderInfoByUuid( $user_input['guider_id'] ) ) {
            $this->error['warning']    = 'Invalid Talent ID.';
        }
        return !$this->error;
    }
}
?>