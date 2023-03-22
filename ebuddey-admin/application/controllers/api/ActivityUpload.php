<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ActivityUpload extends CI_Controller{

    private $error = array();
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/Guiderapimodel');
        $this->load->helper('timezone');
    }
    public function index() {
        //error_reporting(E_ALL);
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array('message' => 'Authorization error', 'result' => 'error');
        } else if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            if( (count($_POST) > 0) && $this->validate( $_POST ) ){
                $Photourl       = '';
                $data1          = array();
                $data2          = array();
                $data3          = array();
                $activity_id    = $_POST['activity_id'];
                $guider_id      = $_POST['guider_id'];
                $upload_path_url= $this->config->item( 'upload_path_url' );
                $upload_path    = $this->config->item( 'upload_path' );
                $activity_img1  = $_FILES['activity_img1'];
                $activity_img2  = $_FILES['activity_img2'];
                $activity_img3  = $_FILES['activity_img3'];
                
                $config['upload_path']  = './uploads/g_activity/'; 
                $config['allowed_types']= 'gif|jpg|jpeg|png'; 
                $config['max_size']     = 15360; //15MB = 15*1024
                //$config['encrypt_name']  = true;
                $config['max_width']    = 4800;
                $config['max_height']   = 4800;
                $file_rename            = $guider_id.'_'.$activity_id.'_'.'guider_activity'.time();
                $uploadErr              = 'Please select any one image file.';
                if($activity_img1){
                    $file_name  = $_FILES["activity_img1"]["name"];
                    $tmp        = explode('.', $file_name);
                    $file_extension = end($tmp);
                    $new_name   = $file_rename.'_1.'.$file_extension;
                    $config['file_name']    = $new_name;
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('activity_img1')) {
                        $error      = array('error' => $this->upload->display_errors());
                        $uploadErr  = strip_tags($error['error']);
                        $result = array('response_code' => ERROR_CODE, 'response_description' => strip_tags($error['error']), 'result' => 'error', 'data'=>array('error' => 1));
                    }else {
                        //$upload_data  = array('upload_data' => $this->upload->data());
                        $upload_data    = $this->upload->data();
                        $Photourl       = $upload_path_url.'g_activity/'.$upload_data['file_name'];
                        $Photourl2      = $upload_path.'g_activity/'.$upload_data['file_name'];
                        compress_image($_FILES["activity_img1"]["tmp_name"], $Photourl2, COMPRESS_IMG_SIZE);
                        $data1          = array( 'photo_1' => $Photourl );
                        $result1        = $this->Guiderapimodel->updateGuiderActivtyByUuid( $data1, $activity_id );
                    }
                }
                if($activity_img2){
                    $file_name  = $_FILES["activity_img2"]["name"];
                    $tmp        = explode('.', $file_name);
                    $file_extension = end($tmp);
                    $new_name   = $file_rename.'_2.'.$file_extension;
                    $config['file_name']     = $new_name;
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('activity_img2')) {
                        $error      = array('error' => $this->upload->display_errors());
                        $uploadErr  = strip_tags($error['error']);
                        $result = array('response_code' => ERROR_CODE, 'response_description' => strip_tags($error['error']), 'result' => 'error', 'data'=>array('error' => 1));
                    }else {
                        //$upload_data  = array('upload_data' => $this->upload->data());
                        $upload_data    = $this->upload->data();
                        $Photourl       = $upload_path_url.'g_activity/'.$upload_data['file_name'];
                        $Photourl2      = $upload_path.'g_activity/'.$upload_data['file_name'];
                        compress_image($_FILES["activity_img2"]["tmp_name"], $Photourl2, COMPRESS_IMG_SIZE);
                        $data2          = array( 'photo_2' => $Photourl );
                        $result1        = $this->Guiderapimodel->updateGuiderActivtyByUuid( $data2, $activity_id );
                    }
                }
                if($activity_img3){
                    $file_name  = $_FILES["activity_img3"]["name"];
                    $tmp        = explode('.', $file_name);
                    $file_extension = end($tmp);
                    $new_name   = $file_rename.'_3.'.$file_extension;
                    $config['file_name']     = $new_name;
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('activity_img3')) {
                        $error      = array('error' => $this->upload->display_errors());
                        $uploadErr  = strip_tags($error['error']);
                        $result = array('response_code' => ERROR_CODE, 'response_description' => strip_tags($error['error']), 'result' => 'error', 'data'=>array('error' => 1));
                    }else {
                        //$upload_data  = array('upload_data' => $this->upload->data());
                        $upload_data    = $this->upload->data();
                        $Photourl       = $upload_path_url.'g_activity/'.$upload_data['file_name'];
                        $Photourl3      = $upload_path.'g_activity/'.$upload_data['file_name'];
                        compress_image($_FILES["activity_img3"]["tmp_name"], $Photourl3, COMPRESS_IMG_SIZE);
                        $data3          = array( 'photo_3' => $Photourl );
                        $result1        = $this->Guiderapimodel->updateGuiderActivtyByUuid( $data3, $activity_id );
                    }
                }
                $data   = array_merge($data1,$data2,$data3);
                if(count($data) > 0){
                    $guiderInfo         = $this->Guiderapimodel->guiderInfoByUuid( $guider_id );
                    $randActivityIfo    = $this->Guiderapimodel->getGuiderRandomActivity($guider_id);
                    $upload_path_url    = $this->config->item( 'upload_path_url' );
                    $profileImgPath     = $upload_path_url.'g_profile/';
                    $activityImgPath    = $upload_path_url.'g_activity/';
                    $profile_image      = ($guiderInfo->profile_image) ? $profileImgPath.$guiderInfo->profile_image : '';
                    if(count($randActivityIfo) > 0){
                        $photo          = $randActivityIfo['photo_1'];
                        $photo1         = $randActivityIfo['photo_2'];
                        $photo2         = $randActivityIfo['photo_3'];
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
                    $img_id_proof  = ($guiderInfo->id_proof) ? $upload_path_url.'identity/'.$guiderInfo->id_proof : '';
                    $dbkl_lic      = ($guiderInfo->dbkl_lic) ? $upload_path_url.'dbkl/'.$guiderInfo->dbkl_lic : '';
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
                                        'what_i_offer'    => $what_i_offer,
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
                                        'status'        => intval($guiderInfo->status),
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
                                            'response_description' => 'Your Guider application has been submitted.',
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
                }else{
                    $result = array(
                                    'response_code'         => ERROR_CODE,
                                    'response_description'  => $uploadErr,
                                    'result'                => 'error',
                                    'data'                  => array('error' => 1)
                                    );
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
        if($user_input['guider_id'] == ''){
            $this->error['warning']    = 'Talent ID Cannot be empty';
        } else if( !$this->Guiderapimodel->guiderInfoByUuid( $user_input['guider_id'] ) ) {
            $this->error['warning']    = 'Talent ID not exist.';
        } else if($user_input['activity_id'] == ''){
            $this->error['warning']    = 'Activity ID Cannot be empty';
        } else if( !$this->Guiderapimodel->guiderActivtyInfoByUuid( $user_input['activity_id'] ) ) {
            $this->error['warning']    = 'Activity ID not exist.';
        }
        return !$this->error;
    }
}
?>