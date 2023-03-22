<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Guider extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model( 'Commonmodel' );
        $this->load->model( 'Requestmodel' );
        $this->load->helper('timezone');
    }
	public function index() {
        error_reporting(0);
        $script      = '';
        //Insert Guider Info
        //Post Data
        if( $this->input->post( 'first_name' ) ) {
            $languagearr        = array();
            $admin_upload_path  = $this->config->item( 'admin_upload_path' );
            $activityUploadPath = $admin_upload_path.'g_activity/';
            $first_name         = $this->input->post( 'first_name' );
            $last_name          = $this->input->post( 'last_name' );
            $dob                = $this->input->post( 'dob' );
            $mobile_no          = $this->input->post( 'mobile_no' );
            $email              = $this->input->post( 'email' );
            $languagearr        = $this->input->post( 'language' );
            $about_me           = $this->input->post( 'about_me' );
            //BANK INFO
            $bank_account_name  = $this->input->post( 'bank_account_name' );
            $bank_name          = $this->input->post( 'bank_name' );
            $account_number     = $this->input->post( 'account_number' );
            //Service Info
            $category            = $this->input->post( 'category' );
            $service_city        = $this->input->post( 'service_city' );
            $abount_the_activity = $this->input->post( 'abount_the_activity' );
            $pricing             = $this->input->post( 'pricing' );
            $price_type_id       = $this->input->post( 'price_type_id' );
            $cancellation_policy = $this->input->post( 'cancellation_policy' );
            $id_proof            = '';
            $upload_photo        = '';
            $upload_photo1       = '';
            $upload_photo2       = '';
            if(!$this->Commonmodel->phoneno2Verify($mobile_no)){
                //Image Upload
                if( $_FILES ) {
                    $upload_document  = $_FILES['upload_document'];
                    $photo1 = $_FILES['photo1'];
                    $photo2 = $_FILES['photo2'];
                    $photo3 = $_FILES['photo3'];
                    $count  = 0;
                    //Document
                    $config['upload_path']   = './'.ADMIN_FOLDER.'/uploads/identity/'; 
                    $config['allowed_types'] = 'gif|jpg|jpeg|png'; 
                    $config['max_size']      = 400000; 
                    //$config['encrypt_name']  = true;
                    $config['max_width']     = 4800;
                    $config['max_height']    = 4800;
                    if($upload_document["name"]){
                        $name   = $_FILES["upload_document"]["name"];
                        $tmp    = explode('.', $name);
                        $ext    = end($tmp);

                        $new_name   = 'guider_docs'.time() .'.'.$ext;
                        $config['file_name']     = $new_name;
                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);
                        if (!$this->upload->do_upload('upload_document')) {
                            $error  = array('error' => $this->upload->display_errors());
                        }else {
                            $upload_data    = $this->upload->data();
                            $id_proof       = $upload_data['file_name'];
                            $uploadphotourl = $admin_upload_path.'identity/'.$upload_data['file_name'];
                            //COMPRESS IMAGE SIZE
                            compress_image($_FILES["upload_document"]["tmp_name"], $uploadphotourl, COMPRESS_IMG_SIZE);
                        }
                    }
                    //Photo
                    $config['upload_path']   = './'.ADMIN_FOLDER.'/uploads/g_activity/'; 
                    $config['allowed_types'] = 'gif|jpg|jpeg|png'; 
                    $config['max_size']      = 400000; 
                    //$config['encrypt_name']  = true;
                    $config['max_width']     = 4800;
                    $config['max_height']    = 4800;

                    if($photo1["name"]){
                        $name       = $_FILES["photo1"]["name"];
                        $tmp    = explode('.', $name);
                        $ext    = end($tmp);
                        $new_name   = '_guider_activity'.time() .'_1.'.$ext;
                        $config['file_name']     = $new_name;
                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);
                        if (!$this->upload->do_upload('photo1')) {
                            $error  = array('error' => $this->upload->display_errors());
                        }else {
                            $upload_data    = $this->upload->data();
                            $upload_photo   = $upload_data['file_name'];
                            $uploadphotourl = $activityUploadPath.$upload_data['file_name'];
                            //COMPRESS IMAGE SIZE
                            compress_image($_FILES["photo1"]["tmp_name"], $uploadphotourl, COMPRESS_IMG_SIZE);
                        }
                    }
                    if($photo2["name"]){
                        $name       = $_FILES["photo2"]["name"];
                        $tmp    = explode('.', $name);
                        $ext    = end($tmp);

                        $new_name   = '_guider_activity'.time() .'_2.'.$ext;
                        $config['file_name']  = $new_name;
                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);
                        if (!$this->upload->do_upload('photo2')) {
                            $error  = array('error' => $this->upload->display_errors());
                        }else {
                            $upload_data    = $this->upload->data();
                            $upload_photo1  = $upload_data['file_name'];
                            $uploadphotourl = $activityUploadPath.$upload_data['file_name'];
                            //COMPRESS IMAGE SIZE
                            compress_image($_FILES["photo2"]["tmp_name"], $uploadphotourl, COMPRESS_IMG_SIZE);
                        }
                    }
                    if($photo3["name"]){
                        $name       = $_FILES["photo3"]["name"];
                        $tmp        = explode('.', $name);
                        $ext        = end($tmp);
                        $new_name   = '_guider_activity'.time() .'_3.'.$ext;
                        $config['file_name']     = $new_name;
                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);
                        if (!$this->upload->do_upload('photo3')) {
                            $error  = array('error' => $this->upload->display_errors());
                        }else {
                            $upload_data    = $this->upload->data();
                            $upload_photo2  = $upload_data['file_name'];
                            $uploadphotourl = $activityUploadPath.$upload_data['file_name'];
                            //COMPRESS IMAGE SIZE
                            compress_image($_FILES["photo3"]["tmp_name"], $uploadphotourl, COMPRESS_IMG_SIZE);
                        }
                    }
                }
                $checkBox = '';
                $language = '';
                if($category){ $checkBox    = implode(',', $category); }
                if($languagearr){ $language = implode(',', $languagearr); }
                $country_code   = 60;
                //Data
                $db_data[ 'reg_device_type' ]   = 1;
                $db_data[ 'countryCode' ]       = $country_code;
                $db_data[ 'first_name' ]        = $first_name;
                $db_data[ 'last_name' ]         = $last_name;
                $db_data[ 'phone_number' ]      = $mobile_no;
                $db_data[ 'age' ]               = date('Y-m-d', strtotime( $dob ) );
                $db_data[ 'email' ]             = $email;
                $db_data[ 'languages_known' ]   = $language;
                $db_data[ 'about_me' ]          = $about_me;
                $db_data[ 'acc_name' ]          = $bank_account_name;
                $db_data[ 'bank_name' ]         = $bank_name;
                $db_data[ 'acc_no' ]            = $account_number;
                $db_data[ 'id_proof' ]          = $id_proof;
                $db_data[ 'rating' ]            = 5;
                $db_data[ 'created_on' ]        = date( 'Y-m-d H:i:s' );
                
                $db_data2[ 'photo_1' ]          = $upload_photo;
                $db_data2[ 'photo_2' ]          = $upload_photo1;
                $db_data2[ 'photo_3' ]          = $upload_photo2;
                $db_data2[ 'rate_per_person' ]  = $pricing;
                $db_data2[ 'price_type_id' ]    = $price_type_id;
                $db_data2[ 'service_providing_region' ] = $service_city;
                $db_data2[ 'guiding_speciality' ]= $checkBox;
                $db_data2[ 'what_i_offer' ]      = $abount_the_activity;
                $db_data2[ 'cancellation_policy' ]= $cancellation_policy;
                $db_data2[ 'createdon' ]        = date( 'Y-m-d H:i:s' );
            
                $this->db->insert( 'guider', $db_data );
                $guider_id = $this->db->insert_id();
                $this->Commonmodel->updateGuiderId( $guider_id );
                if($guider_id) {
                    $db_data2[ 'activity_guider_id' ]   = $guider_id;
                    if($service_city){
                        if($price_type_id == 3){ $db_data2[ 'rate_per_person' ]  = 0; }
                        $this->Commonmodel->insertActivity( $db_data2 );
                    }
                    $data1[ 'firstName' ]  = $first_name;
                    $mailContent    = $this->load->view( 'mail/reg_guider', $data1, true );
                    $mailData       = $this->Commonmodel->send_mail_to_guider($email,$mailContent);
                    $data5          = array('verify_email' => 1);
                    $this->Commonmodel->updateGuiderInfoById( $guider_id, $data5 );
                }
                $this->session->set_flashdata( 'Smsg', 'Your request has been successfully submitted. Please download the app from playstore to avail the eBuddey services.' );
                $this->session->set_flashdata( 'complete_msg', '1' );
            }else{
                $this->session->set_flashdata( 'Emsg', 'Operation failed. Please try after some time.' );
                $this->session->set_flashdata( 'complete_msg', '1' );
            }
        }
        $category               = $this->Commonmodel->category_list();
        $service_city           = $this->Commonmodel->get_service_city();
        $db_data[ 'category' ]  = $category;
        $db_data[ 'service_city' ]   = $service_city;
        $db_data[ 'guiderLangList' ] = $this->Commonmodel->get_guiderlanguage_lists();
		$content     = $this->load->view( 'guider/guider', $db_data, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Guider';
        $data[ 'header' ][ 'metakeyword' ]      = 'Guider';
        $data[ 'header' ][ 'metadescription' ]  = 'Guider';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
		
        $this->template( $data );
        return true;
	}
    //Validation
    function cityValidate() {
        //Validate the duration form
        $this->form_validation->set_rules( 'hidden_state_id', 'City name', 'required' );
        if( $this->form_validation->run() == FALSE ) {
            echo validation_errors();
            return false;
        } else {
            echo 1;
            return true;
        }
    }
    //Get Guider State
    function get_guider_state() {
        $c_query        = $this->input->get( 'query' );
        $guider_info    = $this->Commonmodel->get_state_list( $c_query );
        if( $guider_info ) {
            foreach ( $guider_info as $key => $value ) {
                $data[ $key ][ 'name' ] = rawurldecode( $value->name );
                $data[ $key ][ 'id' ]   = $value->name;
            }
        } else {
            $data[0]['name']    = 'No data found';
            $data[0]['id']      = 0;
        }
        echo json_encode( $data );
        return true;
    }
	//Get City Info
    function get_state_guider_info() {
        $service_providing_region  = $this->input->post( 'service_providing_region' );
        //Get Guider Info
        $get_guider = $this->Commonmodel->get_result_guider( $service_providing_region );
        if( $get_guider ) {
            //Json Data              
            $message = array( 'state_name' => rawurldecode( $get_guider->service_providing_region ), 'success' => 1 );        
            echo json_encode( $message );
        } else {
            $message = array( 'Jerror' => 'No data found for this city.', 'success' => 0 );        
            echo json_encode( $message );
        }  
        return true;
    }
	//Get Guider Information
    function view() {
        $activity_id    = $this->uri->segment(3);
        $script         = '';
        $processing_fee = $this->Commonmodel->siteInfo('_processing_fee');
        if(!$processing_fee){ $processing_fee = PROCESSING_FEE; }else{ $processing_fee = $processing_fee->s_value; }
        if(PROCESSING_FEE_ENABLED == 'NO'){ $processing_fee = 0; }
        $activityInfo        = $this->Commonmodel->get_guider_view( $activity_id );
        $data_db[ 'guiderInfo' ]        = $activityInfo;
        $data_db[ 'activity_id' ]       = $activity_id;
        $data_db[ 'processing_fee' ]    = $processing_fee;
        $data_db[ 'comment_list' ]      = $this->Commonmodel->get_guider_comment_list( $activityInfo->activity_guider_id );
        $content     = $this->load->view( 'guider/guider_view', $data_db, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Guider List';
        $data[ 'header' ][ 'metakeyword' ]      = 'Guider List';
        $data[ 'header' ][ 'metadescription' ]  = 'Guider List';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
        $this->template( $data );
        return true;
    }
    //Guider Validation
    function guiderValidate() {
        
        //Validate the event form
        $this->form_validation->set_rules( 'first_name', 'First name', 'required' );
        $this->form_validation->set_rules( 'dob', 'DOB', 'required' );
        $this->form_validation->set_rules( 'mobile_no', 'Mobile', 'required' );
        $this->form_validation->set_rules( 'email', 'Email', 'trim|required|valid_email' );
        //Bank Details
        /*$this->form_validation->set_rules( 'bank_account_name', 'Bank Account Name', 'required' );
        $this->form_validation->set_rules( 'bank_name', 'Bank name', 'required' );
        $this->form_validation->set_rules( 'account_number', 'Account number', 'required' );
        //Service Info
        $this->form_validation->set_rules( 'category[]', 'Category', 'required' );
        $this->form_validation->set_rules( 'service_city', 'Service City', 'required' );
        $this->form_validation->set_rules( 'pricing', 'Pricing', 'required' );*/
        if( $this->form_validation->run() == FALSE ) {
            echo validation_errors();
        } else {
            echo '1';
        }
    }
    function phonenoVerify() {
        //Validate the duration form
        $mobile_no  = $this->input->post( 'mobile_no' );
        echo $this->Commonmodel->phonenoVerify($mobile_no);
    }
    function postCommentValidate() {
        $json_res = array();
        //Validate the event form
        $this->form_validation->set_rules( 'guest_name', 'First name', 'required' );
        $this->form_validation->set_rules( 'guest_email', 'Email', 'trim|required|valid_email' );
        $this->form_validation->set_rules( 'guest_comment', 'Comment', 'required' );
        if( $this->form_validation->run() == FALSE ) {
            //echo validation_errors();
            $json_res = array('Status' => 'error','html' => 'Some problem found.Please try later.');
            echo json_encode($json_res);
        } else {
            $post_date  = date('F d, Y');
            $post_time  = date('g:i a');
            $guider_id  = $this->input->post( 'guider_id' );
            $name       = $this->input->post( 'guest_name' );
            $email      = $this->input->post( 'guest_email' );
            $comment    = $this->input->post( 'guest_comment' );
            $createdon  = date("Y-m-d H:i:s");
            $data       = array(
                            'guider_id'     => $guider_id,
                            'cmt_name'      => $name,
                            'cmt_email'     => $email,
                            'cmt_messge'    => $comment,
                            'createdon'     => $createdon
                            );
            $this->Commonmodel->postComment($data);
            $guestImg   = $this->config->item( 'dir_url' ).'img/avatar.png';
            $html = '<div class="row">
                        <div class="col-md-1 image_circle ico_left">
                            <img src="'.$guestImg.'">
                        </div>
                        <div class="col-md-11">
                            <h5><div>'.$name.'</div><small>'.$post_date.' at '.$post_time.'</small></h5>
                            <p>'.$comment.'</p>    
                        </div>
                    </div>';
            $json_res = array('Status' => 'success','html' => $html);
            echo json_encode($json_res);
        }
    }

	function template( $data ){
        $this->load->view( 'common/templatecontent', $data );
        return true;
    }
}    
    