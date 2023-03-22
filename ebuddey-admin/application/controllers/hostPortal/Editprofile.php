<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Editprofile extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('host_helper.php');
        $this->load->model('hostPortal/Hostmodel');
        $this->load->library('encryption');
        host_sessionset();
        error_reporting(E_ALL);
    }
	public function index() {
        $host_id    = $this->session->userdata['HOST_ID'];
        $guiderInfo = $this->Hostmodel->guiderInfo($host_id);
        if($this->input->post( 'activity_value' ) == 1){
            $activity_id    = $this->input->post( 'activity_id' );
            $upload_path_url= $this->config->item( 'upload_path_url' );
            $activityInfo   = $this->Hostmodel->activityInfo( $activity_id );
            $data      = array();
            $data['price_type_id']        = $this->input->post( 'price_type_id' );
            if($this->input->post( 'price_type_id' ) == 3){
                $data['rate_per_person']  = '';
                $data['processingFeesType'] = 0;
                $data['processingFeesValue'] = 0;
            }else{
                $data['rate_per_person']  = $this->input->post( 'rate_per_person' );
                if($this->input->post('processingFeesType') == 0){
                    $data['processingFeesType']  = 1; //DEFAULT PROCESSING FEES
                    $data['processingFeesValue'] = 0;
                }
            }
            $data['cancellation_policy'] = $this->input->post( 'cancellation_policy' );
            $data['service_providing_region'] = $this->input->post( 'service_providing_region' );
            $data['additional_info_label'] = $this->input->post( 'additional_info_label' );
            $data['maximum_booking']    = $this->input->post( 'maximum_booking' );
            $data['date_time_needed']   = $this->input->post( 'date_time_needed' );
            $guiding_speciality         = $this->input->post( 'guiding_speciality' );
            if($guiding_speciality){ $data['guiding_speciality'] = implode(',', $this->input->post( 'guiding_speciality' )); }
            $data['what_i_offer'] = $this->input->post( 'what_i_offer' );
            if ($_FILES['photo']['name']) {
                $rename     = $host_id.'_guider_activity'.time() .'_1';
                $uploadData = $this->fileUpload( 'photo', 'g_activity', $rename );
                if( $uploadData['status'] == 'error'){
                    $this->session->set_flashdata('errorMSG', $uploadData['msg']);
                }
                if( $uploadData['status'] == 'success'){
                    $photo_1         = $upload_path_url.'g_activity/'.$uploadData['file_name'];
                    $data['photo_1'] = $photo_1;
                    if($activityInfo->photo_1){
                        $existing_file   = end((explode("/", $activityInfo->photo_1)));
                        unlink('uploads/g_activity/'.$existing_file);
                    }
                }
            }
            if ($_FILES['photo1']['name']) {
                $rename     = $host_id.'_guider_activity'.time() .'_2';
                $uploadData2= $this->fileUpload( 'photo1', 'g_activity', $rename );
                if( $uploadData2['status'] == 'error'){
                    $this->session->set_flashdata('errorMSG', $uploadData2['msg']);
                }
                if( $uploadData2['status'] == 'success'){
                    $photo_2         = $upload_path_url.'g_activity/'.$uploadData2['file_name'];
                    $data['photo_2'] = $photo_2;
                    if($activityInfo->photo_2){
                        $existing_file   = end((explode("/", $activityInfo->photo_2)));
                        unlink('uploads/g_activity/'.$existing_file);
                    }
                }
            }
            if ($_FILES['photo2']['name']) {
                $rename     = $host_id.'_guider_activity'.time() .'_3';
                $uploadData3= $this->fileUpload( 'photo2', 'g_activity', $rename );
                if( $uploadData3['status'] == 'error'){
                    $this->session->set_flashdata('errorMSG', $uploadData3['msg']);
                }
                if( $uploadData3['status'] == 'success'){
                    $photo_3         = $upload_path_url.'g_activity/'.$uploadData3['file_name'];
                    $data['photo_3'] = $photo_3;
                    if($activityInfo->photo_3){
                        $existing_file   = end((explode("/", $activityInfo->photo_3)));
                        unlink('uploads/g_activity/'.$existing_file);
                    }
                }
            }
            if($activity_id){
                $this->Hostmodel->updateActivityInfo( $activity_id, $data );
            }else{
                $data['activity_guider_id'] = $host_id;
                $this->Hostmodel->insertGuiderActivity( $data );
            }
            $this->session->set_flashdata('successMSG', 'Guider Activity updated successfully.');
            redirect($this->config->item( 'hostportal_url' ).'editprofile');
        }
        $field = $this->input->post( 'field' );
        if($field == 'profile_image' || $field == 'id_proof' || $field == 'dbkl_lic') {
            $data3      = array();
            if ($field == 'id_proof') {
                $rename     = $host_id.'_identity'.time();
                $uploadData = $this->fileUpload( 'id_proof', 'identity', $rename );
                if( $uploadData['status'] == 'error'){
                    $this->session->set_flashdata('errorMSG', $uploadData['msg']);
                }
                if( $uploadData['status'] == 'success'){
                    $data3['id_proof'] = $uploadData['file_name'];
                }
                if($guiderInfo->id_proof){
                    unlink('uploads/identity/'.$guiderInfo->id_proof);
                }
            }
            if ($field == 'profile_image') {
                $rename     = $host_id.'_member_profile'.time();
                $uploadData2= $this->fileUpload( 'profile_image', 'g_profile', $rename );
                if( $uploadData2['status'] == 'error'){
                    $this->session->set_flashdata('errorMSG', $uploadData2['msg']);
                }
                if( $uploadData2['status'] == 'success'){
                    $data3['profile_image'] = $uploadData2['file_name'];
                }
                if($guiderInfo->profile_image){
                    unlink('uploads/g_profile/'.$guiderInfo->profile_image);
                }
            }
            if ($field == 'dbkl_lic') {
                $rename3     = $host_id.'_dbkl_lic_'.time();
                $uploadData3 = $this->fileUpload( 'dbkl_lic', 'dbkl', $rename3 );
                if( $uploadData3['status'] == 'error'){
                    $this->session->set_flashdata('errorMSG', $uploadData3['msg']);
                }
                if( $uploadData3['status'] == 'success'){
                    $data3['dbkl_lic'] = $uploadData3['file_name'];
                    $data3['dbkl_status'] = 2;
                }
                if($guiderInfo->dbkl_lic){
                    unlink('uploads/dbkl/'.$guiderInfo->dbkl_lic);
                }
            }
            if(count($data3) > 0){
                $this->Hostmodel->updateGuiderInfo( $host_id, $data3 );
            }
            redirect($this->config->item( 'hostportal_url' ).'editprofile');
        }
        $activityLists= $this->Hostmodel->guiderActivityLists($host_id);
        if(!$guiderInfo){ redirect( $this->config->item( 'admin_url' ) . 'guider' ); }
        $data1[ 'guiderInfo' ]                  = $guiderInfo;
        $data1[ 'guider_id' ]                   = $host_id;
        $data1[ 'activityLists' ]               = $activityLists;
        $script     = '';
        $content    = $this->load->view( 'hostPortal/editprofile', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Edit Profile';
        $data[ 'header' ][ 'metakeyword' ]      = 'Edit Profile';
        $data[ 'header' ][ 'metadescription' ]  = 'Edit Profile';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">Edit Profile</li>';
        $this->template( $data );
	}
    //UPDATE HOST INFO
    function updateGuiderForm() {
        $host_id    = $this->session->userdata['HOST_ID'];
        $data1[ 'guider_id' ]  = $host_id;
        $data1[ 'field' ]      = $this->input->post( 'field' );
        $data1[ 'guiderInfo' ] = $this->Hostmodel->guiderInfo( $host_id );
        $data1[ 'serviceRegionLists' ] = $this->Hostmodel->serviceRegionLists();
        $data1[ 'specializationLists' ] = $this->Hostmodel->getSpecializationLists();
        $data1[ 'getHostLangLists' ]  = $this->Hostmodel->getHostLangLists();
        echo $this->load->view( 'hostPortal/update_info_form', $data1, true );
    }
    function updateGuiderActivityForm() {
        $activity_id  = $this->input->post( 'activity_id' );
        $host_id    = $this->session->userdata['HOST_ID'];
        $data1[ 'activity_id' ]  = $this->input->post( 'activity_id' );
        $data1[ 'guider_id' ]    = $host_id;
        $data1[ 'serviceLists' ] = $this->Hostmodel->getGuiderActiveServiceRegionLists( $host_id );
        $data1[ 'activityInfo' ] = $this->Hostmodel->activityInfo( $activity_id );
        $data1[ 'serviceRegionLists' ] = $this->Hostmodel->serviceRegionLists();
        $data1[ 'specializationLists' ] = $this->Hostmodel->getSpecializationLists();
        echo $this->load->view( 'hostPortal/guider_activity_form', $data1, true );
    }
    function guiderActivityStatus(){
        $activity_id    = $this->input->post( 'activity_id' );
        $status         = $this->input->post( 'status' );
        $data['activity_status'] = $status;
        $this->Hostmodel->updateActivityInfo( $activity_id, $data );
        if($status == 4){
            $data2      = array('status' => 3);
            $this->Hostmodel->cancelPendingRequests($activity_id, $data2);
            $host_id    = $this->session->userdata['HOST_ID'];
            $data3      = array(
                            'log_type'      => 'ACTIVITY',
                            'log_action'    => 'DELETE',
                            'activity_id'   => $activity_id,
                            'user_id'       => $host_id,
                            'user_type'     => 'HOST',
                            'log_createdon' => date( 'Y-m-d H:i:s' )
                            );
            $this->Hostmodel->insertServiceLog( $data3 );
        }
    }
    function updateGuiderField() {
        $host_id    = $this->session->userdata['HOST_ID'];
        $field      = $this->input->post( 'field' );
        $imageType  = '';
        $data       = array();
        $this->form_validation->set_rules( 'field', 'field', 'required' );
        if($field == 'about'){
            $data['about_me'] = $this->input->post( 'about_me' );
        }elseif ($field == 'first_name') {
            $this->form_validation->set_rules( 'first_name', 'First Name', 'required|min_length[3]' );
            $data['first_name'] = $this->input->post( 'first_name' );
        }elseif ($field == 'last_name') {
            $this->form_validation->set_rules( 'last_name', 'Last Name', 'required|min_length[1]' );
            $data['last_name'] = $this->input->post( 'last_name' );
        }elseif ($field == 'email') {
            $this->form_validation->set_rules( 'email', 'Email', 'trim|required|valid_email' );
            $data['email'] = $this->input->post( 'email' );
        }elseif ($field == 'age') {
            $data['age'] = $this->input->post( 'dob' );
        }elseif ($field == 'region') {
            $data['service_providing_region'] = $this->input->post( 'service_providing_region' );
        }elseif ($field == 'bank') {
            $data['acc_name']   = $this->input->post( 'acc_name' );
            $data['bank_name']  = $this->input->post( 'bank_name' );
            $data['acc_no']     = $this->input->post( 'acc_no' );
            $this->form_validation->set_rules( 'acc_name', 'Account Name', 'required|min_length[2]' );
            $this->form_validation->set_rules( 'bank_name', 'Bank Name', 'required|min_length[2]' );
            $this->form_validation->set_rules( 'acc_no', 'Account Number', 'required|min_length[5]' );
        }elseif ($field == 'category') {
            $guiding_speciality     = implode(',', $this->input->post( 'guiding_speciality' ));
            $data['guiding_speciality'] = $guiding_speciality;
        }elseif ($field == 'language') {
            $languages_known     = implode(',', $this->input->post( 'languages_known' ));
            $data['languages_known'] = $languages_known;
        }elseif ($field == 'offer') {
            $data['what_i_offer'] = $this->input->post( 'what_i_offer' );
        }elseif ($field == 'password') {
            $this->form_validation->set_rules( 'password', 'Password', 'required|min_length[6]' );
            $new_password = $this->encryption->encrypt($this->input->post( 'password' ));
            $data['password'] = $new_password;
        }elseif ($field == 'dbkl_lic_no') {
            $data['dbkl_lic_no'] = $this->input->post( 'dbkl_lic_no' );
        }elseif ($field == 'nric_number') {
            $data['nric_number'] = $this->input->post( 'nric_number' );
        }
        if( $this->form_validation->run() == FALSE ) {
            $res[ 'success' ]  = 0;
            $res[ 'msg' ]  = validation_errors();
            echo json_encode( $res );
        }else {
            if(count($data) > 0){
                $this->Hostmodel->updateGuiderInfo( $host_id, $data );
            }
            $res[ 'success' ]  = 1;
            echo json_encode( $res );
        }
    }
    function fileUpload( $filename, $folder, $rename='', $size = '', $width = '', $height = '' ) {
        if( !$_FILES[ "$filename" ][ 'name' ] ) {
            return $res = array( 'status' => 'error','msg' => 'Please select a file' );
        } 
        $_FILES[ "$filename" ][ 'name' ]; 
        $config['upload_path']        = './uploads/'.$folder;
        $config['allowed_types']      = 'gif|jpg|jpeg|png';
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
        $this->load->view( 'hostPortal/templatecontent', $data );
    }
}
