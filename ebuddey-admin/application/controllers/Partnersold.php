<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Partners extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('admin_helper.php');
        $this->load->model('Partnermodel');
        $this->load->model( 'Licensemodel' );
        sessionset();
        error_reporting(E_ALL);
    }
	public function index() {

        $partner_name= $this->input->post( 'partner_name' );
        $city_id     = $this->input->post( 'city_id' );
        $address     = $this->input->post( 'address' );
        $partner_id  = $this->input->post( 'partner_id' );
        $dbkl_lic_enable = $this->input->post( 'dbkl_lic_enable' );
        if($partner_name && $city_id && !$partner_id){
            $license = $this->input->post( 'license' );
            if($license){
                $licenses = implode(',', $license);
                $data['required_license'] = $licenses;
            }
            $data['partner_name'] = rawurlencode( $partner_name );
            $data['city_id']      = $city_id;
            $data['fees']         = $this->input->post( 'fees' );
            $data['address']      = $address;
            $data['dbkl_lic_enable'] = ($dbkl_lic_enable)? 1 : 0;
            $data['created_on']   = date( 'Y-m-d' );
            if ($_FILES['photo']['name']) {
                $rename1    = 'new_partner'.time();
                $uploadData = $this->fileUpload( 'photo', 'partner', $rename1 );
                if( $uploadData['status'] == 'error'){
                    $this->session->set_flashdata('errorMSG', $uploadData['msg']);
                }
                if( $uploadData['status'] == 'success'){
                    $data['photo'] = $uploadData['file_name'];
                }
            }
            $this->Partnermodel->addPartner( $data );
            $this->session->set_flashdata('successMSG', 'Buskers Pod info added successfully.');
            redirect(base_url('partners'));
        }
        if($partner_name && $city_id && $partner_id){
            $partnerInfo = $this->Partnermodel->partnerInfo( $partner_id );
            $license = $this->input->post( 'license' );
            if($license){
                $licenses = implode(',', $license);
                $data2['required_license'] = $licenses;
            }
            $data2['partner_name'] = rawurlencode( $partner_name );
            $data2['city_id']      = $city_id;
            $data2['fees']         = $this->input->post( 'fees' );
            $data2['address']      = $address;
            $data2['dbkl_lic_enable'] = ($dbkl_lic_enable)? 1 : 0;
            if ($_FILES['photo']['name']) {
                $rename2     = 'new_partner'.time();
                $uploadData2 = $this->fileUpload( 'photo', 'partner', $rename2 );
                if( $uploadData2['status'] == 'error'){
                    $this->session->set_flashdata('errorMSG', $uploadData2['msg']);
                }
                if( $uploadData2['status'] == 'success'){
                    $data2['photo'] = $uploadData2['file_name']; 
                    if($partnerInfo->photo){
                        unlink('uploads/partner/'.$partnerInfo->photo);
                    }
                }
            }
            $this->Partnermodel->updatePartner( $partner_id, $data2 );
            $this->session->set_flashdata('successMSG', 'Buskers Pod info updated successfully.');
            redirect(base_url('partners'));
        }

        $tplData[ 'partnerList' ] = $this->Partnermodel->partnerList();
        $tplData[ 'stateList' ]   = $this->Commonmodel->state_list( $country_id=132, 1 );
        $tplData[ 'licenseLists' ]= $this->Licensemodel->licenseLists();
        $content    = $this->load->view( 'partner/list', $tplData, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Buskers Pod Management';
        $data[ 'header' ][ 'metakeyword' ]      = 'Buskers Pod Management';
        $data[ 'header' ][ 'metadescription' ]  = 'Buskers Pod Management';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">Buskers Pod Management</li>';
        $this->template( $data );
	}
    public function edit() {

        $partner_id  = $this->uri->segment(3);
        $partnerInfo = $this->Partnermodel->partnerInfo( $partner_id );
        if(!$partnerInfo){ redirect( base_url() . 'partners' ); }

        $partner_name= $this->input->post( 'partner_name' );
        $city_id     = $this->input->post( 'city_id' );
        $address     = $this->input->post( 'address' );
        $partner_id  = $this->input->post( 'partner_id' );
        $dbkl_lic_enable = $this->input->post( 'dbkl_lic_enable' );
        if($partner_name && $city_id && $partner_id){
            $partnerInfo = $this->Partnermodel->partnerInfo( $partner_id );
            $license = $this->input->post( 'license' );
            if($license){
                $licenses = implode(',', $license);
                $data2['required_license'] = $licenses;
            }
            $data2['partner_name'] = rawurlencode( $partner_name );
            $data2['city_id']      = $city_id;
            $data2['fees']         = $this->input->post( 'fees' );
            $data2['address']      = $address;
            $data2['dbkl_lic_enable'] = ($dbkl_lic_enable)? 1 : 0;
            if ($_FILES['photo']['name']) {
                $rename2     = 'new_partner'.time();
                $uploadData2 = $this->fileUpload( 'photo', 'partner', $rename2 );
                if( $uploadData2['status'] == 'error'){
                    $this->session->set_flashdata('errorMSG', $uploadData2['msg']);
                }
                if( $uploadData2['status'] == 'success'){
                    $data2['photo'] = $uploadData2['file_name']; 
                    if($partnerInfo->photo){
                        unlink('uploads/partner/'.$partnerInfo->photo);
                    }
                }
            }
            $this->Partnermodel->updatePartner( $partner_id, $data2 );
            $this->session->set_flashdata('successMSG', 'Buskers Pod info updated successfully.');
            redirect(base_url('partners'));
        }

        $tplData[ 'partner_id' ]  = $partner_id;
        $tplData[ 'partnerInfo' ] = $partnerInfo;
        $tplData[ 'partnerList' ] = $this->Partnermodel->partnerList();
        $tplData[ 'stateList' ]   = $this->Commonmodel->state_list( $country_id=132, 1 );
        $tplData[ 'licenseLists' ]= $this->Licensemodel->licenseLists();
        $content    = $this->load->view( 'partner/edit_partner', $tplData, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Edit Buskers Pod';
        $data[ 'header' ][ 'metakeyword' ]      = 'Edit Buskers Pod';
        $data[ 'header' ][ 'metadescription' ]  = 'Edit Buskers Pod';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">Edit Buskers Pod</li>';
        $this->template( $data );
    }
    function editPartnerForm() {
        $upload_path_url = $this->config->item( 'upload_path_url' );
        $partner_id  = $this->input->post( 'partner_id' );
        $partnerInfo = $this->Partnermodel->partnerInfo( $partner_id );
        $stateList = $this->Commonmodel->state_list( $country_id=132, 1 );

        $tplData ['partnerInfo'] = $partnerInfo;
        $tplData ['stateList'] = $stateList;
        echo $this->load->view( 'partner/edit_partner', $tplData, true );
    }
    function deletePartner() {
        $partner_id = $this->input->post( 'partner_id' );
        if($partner_id){
            if($this->Partnermodel->checkPartnerEventExists( $partner_id )){
                $data = array('status' => 4, 'deleted_at' => date('Y-m-d H:i:s'));
                $this->Partnermodel->updatePartner( $partner_id, $data );
            }else{
                $this->Partnermodel->deletePartner( $partner_id );
            }
        }
        return true;
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
