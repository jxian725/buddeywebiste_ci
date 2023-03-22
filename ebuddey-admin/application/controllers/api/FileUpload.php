<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FileUpload extends CI_Controller{

    private $error = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('api/Guiderapimodel');
        /*$this->load->model('api/Serviceapimodel');
        $this->load->model('api/pushNotificationmodel');
        $this->load->model('api/MailNotificationmodel');
        $this->load->helper('timezone');
        header("content-type:application/json");*/
    }
    public function test() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array('message' => 'Authorization error', 'result' => 'error');
        } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if( (count($_POST) > 0) && $this->validate( $_POST ) ) {
                $guider_id      = $_POST['guider_id'];
                $guiderinfo     = $this->Guiderapimodel->guiderInfoByUuid( $guider_id );
                $data1          = array('photo' => $guiderinfo->photo);
                $data2          = array('photo1' => $guiderinfo->photo1);
                $data3          = array('photo2' => $guiderinfo->photo2);
                $Photourl       = '';
                $upload_path_url= $this->config->item( 'upload_path_url' );
                $upload_path    = $this->config->item( 'upload_path' );
                $activity_img1  = $_FILES['activity_img1'];
                $activity_img2  = $_FILES['activity_img2'];
                $activity_img3  = $_FILES['activity_img3'];
                
                $config['upload_path']   = './uploads/guider_user/'; 
                $config['allowed_types'] = 'gif|jpg|jpeg|png'; 
                $config['max_size']      = 15360; //15MB = 15*1024
                //$config['encrypt_name']  = true;
                $config['max_width']     = 4800;
                $config['max_height']    = 4800;
                
                if($activity_img1){
                    $name       = $_FILES["activity_img1"]["name"];
                    $ext        = end((explode(".", $name)));
                    $new_name   = 'guider_activity'.time() .'_1.'.$ext;
                    $config['file_name']     = $new_name;
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('activity_img1')) {
                        $error  = array('error' => $this->upload->display_errors());
                        $result = array('response_code' => ERROR_CODE, 'response_description' => strip_tags($error['error']), 'result' => 'error', 'data'=>array('error' => 1));
                    }else {
                        //$upload_data    = array('upload_data' => $this->upload->data());
                        $upload_data    = $this->upload->data();
                        $Photourl       = $upload_path_url.'guider_user/'.$upload_data['file_name'];
                        $Photourl2      = $upload_path.'guider_user/'.$upload_data['file_name'];
                        compress_image($_FILES["activity_img1"]["tmp_name"], $Photourl2, COMPRESS_IMG_SIZE);
                        $data1          = array( 'photo'=> $Photourl );
                        $result1        = $this->Guiderapimodel->updateGuiderByUuid( $data1, $guider_id );
                    }
                }
                if($activity_img2){
                    $name       = $_FILES["activity_img2"]["name"];
                    $ext        = end((explode(".", $name)));
                    $new_name   = 'guider_activity'.time() .'_1.'.$ext;
                    $config['file_name']     = $new_name;
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('activity_img2')) {
                        $error  = array('error' => $this->upload->display_errors());
                        $result = array('response_code' => ERROR_CODE, 'response_description' => strip_tags($error['error']), 'result' => 'error', 'data'=>array('error' => 1));
                    }else {
                        //$upload_data    = array('upload_data' => $this->upload->data());
                        $upload_data    = $this->upload->data();
                        $Photourl       = $upload_path_url.'guider_user/'.$upload_data['file_name'];
                        $Photourl2      = $upload_path.'guider_user/'.$upload_data['file_name'];
                        compress_image($_FILES["activity_img2"]["tmp_name"], $Photourl2, COMPRESS_IMG_SIZE);
                        $data2          = array( 'photo1'=> $Photourl );
                        $result1        = $this->Guiderapimodel->updateGuiderByUuid( $data2, $guider_id );
                    }
                }
                if($activity_img3){
                    $name       = $_FILES["activity_img3"]["name"];
                    $ext        = end((explode(".", $name)));
                    $new_name   = 'guider_activity'.time() .'_1.'.$ext;
                    $config['file_name']     = $new_name;
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('activity_img3')) {
                        $error  = array('error' => $this->upload->display_errors());
                        $result = array('response_code' => ERROR_CODE, 'response_description' => strip_tags($error['error']), 'result' => 'error', 'data'=>array('error' => 1));
                    }else {
                        //$upload_data    = array('upload_data' => $this->upload->data());
                        $upload_data    = $this->upload->data();
                        $Photourl       = $upload_path_url.'guider_user/'.$upload_data['file_name'];
                        $Photourl3      = $upload_path.'guider_user/'.$upload_data['file_name'];
                        compress_image($_FILES["activity_img3"]["tmp_name"], $Photourl3, COMPRESS_IMG_SIZE);
                        $data3          = array( 'photo2'=> $Photourl );
                        $result1        = $this->Guiderapimodel->updateGuiderByUuid( $data3, $guider_id );
                    }
                }
                $data       = array_merge($data1,$data2,$data3);
                $result     = array(
                                    'response_code'     => SUCCESS_CODE, 
                                    'response_description' => 'Your Talent application has been submitted.', 
                                    'result'            => 'success',
                                    'data'              => $data
                                    );
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
            $this->error['warning']    = 'Talent id not exist.';
        }
        return !$this->error;
    }
}
?>