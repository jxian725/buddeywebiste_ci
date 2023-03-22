<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VerificationUpload extends CI_Controller{

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
                $upload_path        = $this->config->item( 'upload_path' );
                $identityUploadPath = $upload_path.'license/';

                $license_id     = trim($_POST['license_id']);
                $talent_id      = trim($_POST['guider_id']);
                $license_number = trim($_POST['license_number']);
                $upload_path_url= $this->config->item( 'upload_path_url' );
                $upload_path    = $this->config->item( 'upload_path' );
                $license_image  = $_FILES['license_image'];
                
                $config['upload_path']  = './uploads/license/'; 
                $config['allowed_types']= 'gif|jpg|jpeg|png'; 
                $config['max_size']     = 15360; //15MB = 15*1024
                //$config['encrypt_name']  = true;
                $config['max_width']    = 4800;
                $config['max_height']   = 4800;
                $file_rename            = $talent_id.'_'.$license_id.'_license_'.time();

                $licenseInfo = $this->Guiderapimodel->talentLicenseInfo($talent_id, $license_id);
                if(!$licenseInfo && !$license_image){
                    $result = array('response_code' => ERROR_CODE, 'response_description' => 'Please upload verification document', 'result' => 'error', 'data'=>array('error' => 1));
                    echo json_encode($result);
                        exit;
                }
                if($license_image){
                    $file_name  = $_FILES["license_image"]["name"];
                    $tmp        = explode('.', $file_name);
                    $file_ext   = end($tmp);
                    $new_name   = $file_rename .'.'.$file_ext;
                    $config['file_name'] = $new_name;
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('license_image')) {
                        $error      = array('error' => $this->upload->display_errors());
                        $uploadErr  = strip_tags($error['error']);
                        $result     = array('response_code' => ERROR_CODE, 'response_description' => strip_tags($error['error']), 'result' => 'error', 'data'=>array('error' => 1));
                        echo json_encode($result);
                        exit;
                    }else {
                        //$upload_data  = array('upload_data' => $this->upload->data());
                        $upload_data   = $this->upload->data();
                        $Photourl      = $upload_path.'license/'.$upload_data['file_name'];
                        compress_image($_FILES["license_image"]["tmp_name"], $Photourl, COMPRESS_IMG_SIZE);
                        $dbData['license_image'] = $upload_data['file_name'];
                        if($licenseInfo && $licenseInfo->license_image){
                            $uploadimg = $identityUploadPath.$licenseInfo->license_image;
                            if(file_exists($uploadimg)){
                                unlink($uploadimg);
                            }
                        }
                    }
                }
                if($this->Guiderapimodel->talentLicenseInfo($talent_id, $license_id)){
                    $dbData['license_number'] = $license_number;
                    $this->Guiderapimodel->updateTalentLicense($talent_id, $license_id, $dbData);
                }else{
                    $dbData['license_number'] = $license_number;
                    $dbData['license_id']   = $license_id;
                    $dbData['talent_id']    = $talent_id;
                    $dbData['created_by']   = $talent_id;
                    $dbData['created_at']   = date("Y-m-d H:i:s");
                    $this->Guiderapimodel->addTalentLicense($dbData);
                }
                $result = array(
                                'response_code'     => SUCCESS_CODE, 
                                'response_description' => 'Your Talent verification has been submitted.', 
                                'result'            => 'success',
                                'data'              => array()
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
        if($user_input['guider_id'] == ''){
            $this->error['warning']    = 'Talent ID Cannot be empty';
        } else if( !$this->Guiderapimodel->guiderInfoByUuid( $user_input['guider_id'] ) ) {
            $this->error['warning']    = 'Talent ID not exist.';
        } else if($user_input['license_id'] == ''){
            $this->error['warning']    = 'Verification ID Cannot be empty';
        } else if( !$this->Guiderapimodel->licenseInfo( $user_input['license_id']) ) {
            $this->error['warning']    = 'Verification ID not exist.';
        } else if($user_input['license_number'] == ''){
            $this->error['warning']    = 'Verification Number Cannot be empty';
        }
        return !$this->error;
    }
}
?>