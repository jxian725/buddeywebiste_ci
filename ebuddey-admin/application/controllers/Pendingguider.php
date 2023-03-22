<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pendingguider extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('admin_helper.php');
        $this->load->model('Guidermodel');
        $this->load->model('Guiderpayoutmodel');
        sessionset();
        error_reporting(E_ALL);
        $this->load->library('encryption');
    }
	public function index() {
        $guider_search  = $this->input->get('guider_search');
        $order_by       = $this->input->get('order_by');
        $guider_lists   = $this->Guidermodel->pendingGuiderLists( $guider_search, $order_by );
        $data1[ 'guider_lists' ]     = $guider_lists;
        $content    = $this->load->view( 'guider/pending_guider', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Pending '.HOST_NAME.' lists';
        $data[ 'header' ][ 'metakeyword' ]      = 'Pending '.HOST_NAME.' lists';
        $data[ 'header' ][ 'metadescription' ]  = 'Pending '.HOST_NAME.' lists';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">Pending '.HOST_NAME.' lists</li>';
        $this->template( $data );
	}
    public function view()
    {
        global $permission_arr;
        if( !in_array( 'pendingguider/index', $permission_arr ) ) {
            echo 'No Direct Access Denied';
            return false;
            exit();
        }
        if($this->input->post( 'activity_value' ) == 1){
            $activity_id    = $this->input->post( 'activity_id' );
            $guider_id      = $this->input->post( 'guider_id' );
            $upload_path_url= $this->config->item( 'upload_path_url' );
            $activityInfo   = $this->Guidermodel->activityInfo( $activity_id );
            $data      = array();
            $data['price_type_id'] = $this->input->post( 'price_type_id' );
            if($this->input->post( 'price_type_id' ) == 3){
                $data['rate_per_person']    = '';
                $data['processingFeesType'] = 0;
                $data['processingFeesValue'] = 0; 
            }else{
                $data['rate_per_person'] = $this->input->post( 'rate_per_person' );
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
                $rename     = $guider_id.'_guider_activity'.time() .'_1';
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
                $rename     = $guider_id.'_guider_activity'.time() .'_2';
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
                $rename     = $guider_id.'_guider_activity'.time() .'_3';
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
                $this->Guidermodel->updateActivityInfo( $activity_id, $data );
            }else{
                $data['activity_guider_id']   = $guider_id;
                $this->Guidermodel->insertGuiderActivity( $data );
            }
            $this->session->set_flashdata('successMSG', 'Host Activity updated successfully.');
            redirect(base_url('pendingguider/view/'.$guider_id));
        }

        $field = $this->input->post( 'field' );
        if($field == 'profile_image' || $field == 'id_proof' || $field == 'dbkl_lic') {
            $guider_id  = $this->input->post( 'guider_id' );
            $guiderInfo2= $this->Guidermodel->guiderInfo($guider_id);
            $data3      = array();
            if ($field == 'id_proof') {
                $rename     = $guider_id.'_identity'.time();
                $uploadData = $this->fileUpload( 'id_proof', 'identity', $rename );
                if( $uploadData['status'] == 'error'){
                    $this->session->set_flashdata('errorMSG', $uploadData['msg']);
                }
                if( $uploadData['status'] == 'success'){
                    $data3['id_proof'] = $uploadData['file_name'];
                }
                if($guiderInfo2->id_proof){
                    unlink('uploads/identity/'.$guiderInfo2->id_proof);
                }
            }
            if ($field == 'profile_image') {
                $rename     = $guider_id.'_member_profile'.time();
                $uploadData2= $this->fileUpload( 'profile_image', 'g_profile', $rename );
                if( $uploadData2['status'] == 'error'){
                    $this->session->set_flashdata('errorMSG', $uploadData2['msg']);
                }
                if( $uploadData2['status'] == 'success'){
                    $data3['profile_image'] = $uploadData2['file_name'];
                }
                if($guiderInfo2->profile_image){
                    unlink('uploads/g_profile/'.$guiderInfo2->profile_image);
                }
            }
            if ($field == 'dbkl_lic') {
                $rename3     = $guider_id.'_dbkl_lic_'.time();
                $uploadData3 = $this->fileUpload( 'dbkl_lic', 'dbkl', $rename3 );
                if( $uploadData3['status'] == 'error'){
                    $this->session->set_flashdata('errorMSG', $uploadData3['msg']);
                }
                if( $uploadData3['status'] == 'success'){
                    $data3['dbkl_lic'] = $uploadData3['file_name'];
                    $data3['dbkl_status'] = 2;
                }
                if($guiderInfo2->dbkl_lic){
                    unlink('uploads/dbkl/'.$guiderInfo2->dbkl_lic);
                }
            }
            if(count($data3) > 0){
                $this->Guidermodel->updateGuiderInfo( $guider_id, $data3 );
            }
            redirect(base_url('pendingguider/view/'.$guider_id));
        }

        $guider_id    = $this->uri->segment(3);
        $guiderInfo   = $this->Guidermodel->guiderInfo($guider_id);
        $activityLists= $this->Guidermodel->guiderActivityLists($guider_id);
        if(!$guiderInfo){ redirect( $this->config->item( 'admin_url' ) . 'guider' ); }
        $data1[ 'guiderInfo' ]                  = $guiderInfo;
        $data1[ 'guider_id' ]                   = $guider_id;
        $data1[ 'activityLists' ]               = $activityLists;
        $script     = '';
        $content    = $this->load->view( 'guider/pending_guider_info', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = HOST_NAME.' Profile';
        $data[ 'header' ][ 'metakeyword' ]      = HOST_NAME.' Profile';
        $data[ 'header' ][ 'metadescription' ]  = HOST_NAME.' Profile';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '
                                              <li><a href="'.$this->config->item( 'admin_url' ).'guider">'.HOST_NAME.' List</a></li>
                                              <li class="active">'.HOST_NAME.' Profile</li>';
        $this->template( $data );
    }
    function fileUpload( $filename, $folder, $rename='', $size = '', $width = '', $height = '' ) {
          if( !$_FILES[ "$filename" ][ 'name' ] ) {
            return false;
          } 
          $_FILES[ "$filename" ][ 'name' ]; 
          $config['upload_path']        = './uploads/'.$folder;
          $config['allowed_types']      = 'gif|jpg|jpeg|png';
          $config['max_size']           = 15360; //15MB = 15*1024
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
        $this->load->view( 'common/templatecontent', $data );
    }
}
