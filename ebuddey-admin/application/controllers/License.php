<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class License extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper( 'admin_helper.php' );
        $this->load->model( 'Licensemodel' );
        sessionset();
        error_reporting(E_ALL);
    }
	public function index() {

        $license_name = $this->input->post( 'license_name' );
        $license_id   = $this->input->post( 'license_id' );
        if($license_name && !$license_id ){
            $data['license_name']   = trim($license_name);
            $data['created_by']     = $this->session->userdata( 'USER_ID' );
            $data['created_at']     = date("Y-m-d H:i:s");
            $this->Licensemodel->addLicense( $data );
            $this->session->set_flashdata('successMSG', 'Verification added successfully.');
            redirect(base_url('license'));
        }
        if($license_name && $license_id  ){
            $data2['license_name'] = trim($license_name);
            $this->Licensemodel->updateLicense( $data2, $license_id );
            $this->session->set_flashdata('successMSG', 'Verification updated successfully.');
            redirect(base_url('license'));
        }
        $tplData[ 'licenseLists' ] = $this->Licensemodel->licenseLists();
        $content = $this->load->view( 'license/index', $tplData, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Verification Lists';
        $data[ 'header' ][ 'metakeyword' ]      = 'Verification Lists';
        $data[ 'header' ][ 'metadescription' ]  = 'Verification Lists';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">Verification Lists</li>';
        $this->template( $data );
	}
    //Delete license
    function delete_license() {
        $license_id = $this->input->post( 'license_id' );
        $data = array('status' => 4);
        $this->Licensemodel->updateLicense( $data, $license_id );
        echo 1;
    }
    function editLicenseForm() {

        $license_id  = $this->input->post( 'license_id' );
        $licenseInfo = $this->Licensemodel->licenseInfo($license_id);
        $str   = form_open_multipart( base_url(). 'license', 'id="edit_license_form"' );
        $str .= '<div class="row">
                    <div class="col-sm-12">  
                      <div class="form-group">
                          <label for="license_name">Verification Name</label><b class="text-danger">*</b>
                          <input type="text" value="' . $licenseInfo->license_name . '" class="form-control" name="license_name" id="license_name" />
                      </div>
                    </div>
                    <div class="col-sm-12">  
                      <div class="clearfix"></div>
                      <input type="hidden" name="license_id" value="' . $licenseInfo->license_id . '" />
                      <input type="submit" id="update-license" value="Update Verification" class="btn btn-success">
                      <span data-dismiss="modal" class="btn btn-danger">Cancel</span>
                    </div>  
                </div>';
        $str .= form_close();        
        echo $str;
    }
	function template( $data ){
        $this->load->view( 'common/templatecontent', $data );
    }
}
