<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UploadIdentity extends CI_Controller{

    private $error = array();
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/Guiderapimodel');
        $this->load->helper('timezone');
    }
    public function index() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array('message' => 'Authorization error', 'result' => 'error');
        } else if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            if( (count($_POST) > 0) && $this->validate( $_POST ) ){
                $upload_path_url    = $this->config->item( 'upload_path_url' );
                $upload_path        = $this->config->item( 'upload_path' );
                $profileImgPath     = $upload_path_url.'identity/';
                $identityUploadPath = $upload_path.'identity/';

                $guider_id      = $_POST['guider_id'];
                $guiderinfo     = $this->Guiderapimodel->guiderInfoByUuid( $guider_id );
                $uploadimg1     = ($guiderinfo->id_proof) ? $identityUploadPath.$guiderinfo->id_proof : '';
                $identity_document  = $_FILES['identity_document'];
                
                $config['upload_path']   = './uploads/identity/'; 
                $config['allowed_types'] = 'gif|jpg|jpeg|png'; 
                $config['max_size']      = 15360; //15MB = 15*1024
                //$config['encrypt_name']  = true;
                $config['max_width']     = 4800;
                $config['max_height']    = 4800;
                
                if($identity_document){
                    $name       = $_FILES["identity_document"]["name"];
                    $ext        = end((explode(".", $name)));
                    $new_name   = $guider_id.'_host_id_'.time() .'.'.$ext;
                    $config['file_name']     = $new_name;
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('identity_document')) {
                        $error  = array('error' => $this->upload->display_errors());
                        $result = array('response_code' => ERROR_CODE, 'response_description' => strip_tags($error['error']), 'result' => 'error', 'data'=>array('error' => 1));
                    }else {
                        $upload_data    = $this->upload->data();
                        $photo          = $upload_data['file_name'];
                        $uploadphotourl = $identityUploadPath.$upload_data['file_name'];
                        //COMPRESS IMAGE SIZE
                        compress_image($_FILES["identity_document"]["tmp_name"], $uploadphotourl, COMPRESS_IDIMG_SIZE);
                        $img1           = $profileImgPath.$photo;
                        $data1          = array( 'id_proof'=> $photo );
                        $result1        = $this->Guiderapimodel->updateGuiderByUuid( $data1, $guider_id );
                        if( file_exists( $uploadimg1 ) ) {
                            unlink( $uploadimg1 );
                        }
                        
                        $res_msg = 'Upload document updated successfully.';
                        $result  = $this->Guiderapimodel->guiderProfileInfo($guider_id, $new = 0, $res_msg, $verify_email = 0);
                    }
                }else{
                    $result = array('response_code' => ERROR_CODE, 'response_description' => 'Please select upload document', 'result' => 'error', 'data'=>array('error' => 1));
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