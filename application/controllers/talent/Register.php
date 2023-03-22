<?php
defined('BASEPATH') OR exit('No direct script access allowed');  

class Register extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model( 'talent/Talentmodel' );
        $this->load->library('encryption');
    }
    public function index() {

        $tplData[ 'specialization_lists' ] = $this->Talentmodel->specialization_lists();
        $tplData[ 'getTalentLangLists' ]   = $this->Talentmodel->getTalentLangLists();
        $tplData[ 'cityLists' ]  = $this->Talentmodel->state_list( $country_id=132, 0 );
        $content = $this->load->view( 'talent/common/register', $tplData, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Buddey Talent | Sign Up';
        $data[ 'header' ][ 'metakeyword' ]      = 'Sign Up';
        $data[ 'header' ][ 'metadescription' ]  = 'Sign Up';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $this->template( $data );
        return true;
    }

    function checkMobileNo(){
        $mobile  = trim($this->input->post('mobile'));
        $mobile  = ltrim($mobile, '0');
        if($this->Talentmodel->PhoneExists( $mobile )){
            echo json_encode(array('mobile' => 'found'));
            //echo json_encode(array("false"));
            //echo "false";
        }else{
            echo json_encode(array('mobile' => ''));
            //echo json_encode(array("true"));
            //echo "true";
        }
    }
    function checkEmail(){
        $email = trim($this->input->post('email'));
        if($this->Talentmodel->EmailExists( $email )){
            echo json_encode(array('email' => 'found'));
        }else{
            echo json_encode(array('email' => ''));
        }
    }

    function  addTalent(){
    
        $email   = trim($this->input->post('email'));
        $mobile  = trim($this->input->post('mobile'));
        $mobile  = ltrim($mobile, '0');
        $this->form_validation->set_rules( 'first_name', 'Full Name', 'required' );
        $this->form_validation->set_rules( 'last_name', 'Other Name', 'required' );
        $this->form_validation->set_rules( 'mobile', 'Mobile Number', 'required|numeric|min_length[8]|max_length[12]|is_unique[guider.phone_number]' );
        $this->form_validation->set_rules( 'email', 'Email', 'trim|required' );
        $this->form_validation->set_rules( 'age', 'Brithdate', 'required' );
        $this->form_validation->set_rules( 'about_me', 'About Me', 'required' );
        if( $this->form_validation->run() == FALSE ) {
            $result = array('res_status' => 'error', 'message' => validation_errors(), 'res_data'=>array('error' => 1));
        }elseif($this->Talentmodel->EmailExists( $email ) && $email){
            $result = array('res_status' => 'error', 'message' => 'Email already exists .', 'res_data'=>array('error' => 2)); 
        }elseif($this->Talentmodel->PhoneExists( $mobile ) && $mobile){
            $result = array('res_status' => 'error', 'message' => 'Mobile Number already exists .', 'res_data'=>array('error' => 3));       
        } else {
            $languages_known = $this->input->post( 'languages_known' );
            if($languages_known){
                $languages = implode(',', $languages_known);
                $languagesKnown = $languages;
            }else{
                $languagesKnown = '';
            }
            $data   = array(
                        'email'            => $email,
                        'countryCode'      => trim($this->input->post( 'countryCode' )),
                        'phone_number'     => $mobile,
                        'first_name'       => trim($this->input->post( 'first_name' )),
                        'last_name'        => trim($this->input->post( 'last_name' )),
                        'nric_number'      => trim($this->input->post( 'nric_number' )),
                        'city'             => trim($this->input->post( 'city' )),
                        'area'             => trim($this->input->post( 'area' )),
                        'age'              => trim($this->input->post( 'age' )),
                        'languages_known'  => $languagesKnown,
                        'skills_category'  => trim($this->input->post( 'skills_category' )),
                        'sub_skills'       => trim($this->input->post( 'sub_skills' )),
                        'about_me'         => trim($this->input->post( 'about_me' )),
                        'reg_device_type'  => 1,
                        'created_type'     => 'web',
                        'status'           => 0,
                        'host_uuid'        => $this->gen_uuid(),
                        'created_on'       => date("Y-m-d H:i:s")
                    );
            if ($_FILES['profile_image']['name']) {
                $rename     = 'talent_profile_'.time() .rand(10,1000);
                $uploadData = $this->fileUpload( 'profile_image', 'g_profile', $rename );
                if( $uploadData['status'] == 'error'){
                    $this->session->set_flashdata('errorMSG', $uploadData['msg']);
                }
                if( $uploadData['status'] == 'success'){
                    $data['profile_image'] = $uploadData['file_name'];
                }
            }
            $id = $this->Talentmodel->insertRegister( $data );
            //$this->session->set_flashdata('successMSG', 'Gigs Created successfully.');
            $result = array('res_status' => 'success', 'message' => 'Register successfully.', 'res_data'=>array('id' => strtoupper($id)));
        }
        echo json_encode($result);
    }

    function gen_uuid() {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

            // 16 bits for "time_mid"
            mt_rand( 0, 0xffff ),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand( 0, 0x0fff ) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand( 0, 0x3fff ) | 0x8000,

            // 48 bits for "node"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }

    function fileUpload( $filename, $folder, $rename='', $size='', $width='', $height='', $allowed_types='' ) {
          if( !$_FILES[ "$filename" ][ 'name' ] ) {
            return false;
          } 
          $_FILES[ "$filename" ][ 'name' ]; 
          $config['upload_path']        = './ebuddey-admin/uploads/'.$folder;
          $config['allowed_types']      = (($allowed_types)? $allowed_types : 'gif|jpg|jpeg|png');
          $config['max_size']           = 500000;
          $config['max_width']          = $width;
          $config['max_height']         = $height;

          $filetype                     = $_FILES[ "$filename" ][ 'type' ];
          $expfiletype                  = explode( '.', $_FILES[ "$filename" ][ 'name' ] );  
          $config['file_name']          = $rename.'.'.$expfiletype[ 1 ];

          $this->upload->initialize( $config );
          $this->load->library( 'upload', $config );

          if ( ! $this->upload->do_upload( $filename ) ) {
              $res = array( 'status' => 'error','msg' => $this->upload->display_errors() );
              return $res;
          } else {
              $data = array( 'upload_data' => $this->upload->data() );
              $res  = array( 'status' => 'success','file_name' => $config['file_name'],'msg' => 'file upload successfully.' );
              return $res;
          }
    }
    
    function template( $data ){
        $this->load->view( 'talent/common/homecontent', $data );
        return true;
    }
}    
   