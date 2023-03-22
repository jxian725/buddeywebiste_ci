<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GuiderActivityUpload extends CI_Controller{

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
                $activityImgPath    = $upload_path_url.'g_activity/';
                $activityUploadPath = $upload_path.'g_activity/';

                $guider_id      = $_POST['guider_id'];
                $guiderinfo     = $this->Guiderapimodel->guiderInfoByUuid( $guider_id );
                $img1           = ($guiderinfo->photo) ? $activityImgPath.$guiderinfo->photo : '';
                $img2           = ($guiderinfo->photo1) ? $activityImgPath.$guiderinfo->photo1 : '';
                $img3           = ($guiderinfo->photo2) ? $activityImgPath.$guiderinfo->photo2 : '';
                $uploadimg1     = ($guiderinfo->photo) ? $activityUploadPath.$guiderinfo->photo : '';
                $uploadimg2     = ($guiderinfo->photo1) ? $activityUploadPath.$guiderinfo->photo1 : '';
                $uploadimg3     = ($guiderinfo->photo2) ? $activityUploadPath.$guiderinfo->photo2 : '';
                
                $activity_img1  = $_FILES['activity_img1'];
                $activity_img2  = $_FILES['activity_img2'];
                $activity_img3  = $_FILES['activity_img3'];
                
                $config['upload_path']   = './uploads/g_activity/'; 
                $config['allowed_types'] = 'gif|jpg|jpeg|png'; 
                $config['max_size']      = 400000; //4MB
                //$config['encrypt_name']  = true;
                $config['max_width']     = 4800;
                $config['max_height']    = 4800;
                $isImg = 0;
                if($activity_img1['name']){
                    $name       = $_FILES["activity_img1"]["name"];
                    $ext        = end((explode(".", $name)));
                    $new_name   = $guider_id.'_guider_activity'.time() .'_1.'.$ext;
                    $config['file_name']     = $new_name;
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('activity_img1')) {
                        $isImg = 2;
                        $error  = array('error' => $this->upload->display_errors());
                        $result = array('response_code' => ERROR_CODE, 'response_description' => strip_tags($error['error']), 'result' => 'error', 'data'=>array('error' => 1));
                    }else {
                        $isImg = 1;
                        $upload_data    = $this->upload->data();
                        $photo          = $upload_data['file_name'];
                        $uploadphotourl = $activityUploadPath.$upload_data['file_name'];
                        //COMPRESS IMAGE SIZE
                        //compress_image($_FILES["activity_img1"]["tmp_name"], $uploadphotourl, COMPRESS_IMG_SIZE);
                        $img1           = $activityImgPath.$photo;
                        $data1          = array( 'photo'=> $photo );
                        $result1        = $this->Guiderapimodel->updateGuiderByUuid( $data1, $guider_id );
                        if( file_exists( $uploadimg1 ) ) {
                            unlink( $uploadimg1 );
                        }
                    }
                }
                if($activity_img2['name']){
                    $name       = $_FILES["activity_img2"]["name"];
                    $ext        = end((explode(".", $name)));
                    $new_name   = $guider_id.'_guider_activity'.time() .'_2.'.$ext;
                    $config['file_name']     = $new_name;
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('activity_img2')) {
                        $isImg = 2;
                        $error  = array('error' => $this->upload->display_errors());
                        $result = array('response_code' => ERROR_CODE, 'response_description' => strip_tags($error['error']), 'result' => 'error', 'data'=>array('error' => 1));
                    }else {
                        $isImg = 1;
                        $upload_data    = $this->upload->data();
                        $photo1         = $upload_data['file_name'];
                        $uploadphotourl1= $activityUploadPath.$upload_data['file_name'];
                        //COMPRESS IMAGE SIZE
                        //compress_image($_FILES["activity_img2"]["tmp_name"], $uploadphotourl1, COMPRESS_IMG_SIZE);
                        $img2           = $activityImgPath.$photo1;
                        $data2          = array( 'photo1'=> $photo1 );
                        $result1        = $this->Guiderapimodel->updateGuiderByUuid( $data2, $guider_id );
                        if( file_exists( $uploadimg2 ) ) {
                            unlink( $uploadimg2 );
                        }
                    }
                }
                if($activity_img3['name']){ echo 33;
                    $name       = $_FILES["activity_img3"]["name"];
                    $ext        = end((explode(".", $name)));
                    $new_name   = $guider_id.'_guider_activity'.time() .'_3.'.$ext;
                    $config['file_name']     = $new_name;
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('activity_img3')) {
                        $isImg = 2;
                        $error  = array('error' => $this->upload->display_errors());
                        $result = array('response_code' => ERROR_CODE, 'response_description' => strip_tags($error['error']), 'result' => 'error', 'data'=>array('error' => 1));
                    }else {
                        $isImg = 1;
                        $upload_data    = $this->upload->data();
                        $photo2         = $upload_data['file_name'];
                        $uploadphotourl2= $activityUploadPath.$upload_data['file_name'];
                        //COMPRESS IMAGE SIZE
                        //compress_image($_FILES["activity_img3"]["tmp_name"], $uploadphotourl2, COMPRESS_IMG_SIZE);
                        $img3           = $activityImgPath.$photo2;
                        $data3          = array( 'photo2'=> $photo2 );
                        $result1        = $this->Guiderapimodel->updateGuiderByUuid( $data3, $guider_id );
                        if( file_exists( $uploadimg3 ) ) {
                            unlink( $uploadimg3 );
                        }
                    }
                }
                if($isImg == 1){
                    $res_msg = 'Activity image uploaded successfully.';
                    $result  = $this->Guiderapimodel->guiderProfileInfo($guider_id, $new = 0, $res_msg, $verify_email = 0);

                } else if($isImg == 0){
                    $result = array('response_code' => ERROR_CODE, 'response_description' => 'Please select one activity image', 'result' => 'error', 'data'=>array('error' => 1));
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
            $this->error['warning']    = 'Guider ID Cannot be empty';
        } else if( !$this->Guiderapimodel->guiderInfoByUuid( $user_input['guider_id'] ) ) {
            $this->error['warning']    = 'Invalid Guider ID.';
        }
        return !$this->error;
    }
}
?>