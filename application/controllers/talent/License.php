<?php
defined('BASEPATH') OR exit('No direct script access allowed');             

class License extends CI_Controller {  
    function __construct() {
        parent::__construct();
        $this->load->model( 'talent/Talentmodel');
        $this->load->helper('talent_helper.php');
        $this->load->helper('timezone');
        talent_sessionset();
    }
    public function index() {

        $talent_id   = $this->session->userdata['TALENT_ID'];
        $tplData['getLicenseList']  = $this->Talentmodel->getLicenseList();
        $content     = $this->load->view( 'talent/license/index', $tplData, true );
		$data['inboxreadinfo']  = $this->Talentmodel->talentInboxReadinfo($talent_id);
        $data[ 'navigation' ]                   = 'profile';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Verification List';
        $data[ 'header' ][ 'metakeyword' ]      = 'Verification List';
        $data[ 'header' ][ 'metadescription' ]  = 'Verification List';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ] = '<li class="active">Verification List</li>'; 
        $this->template( $data );
    }

    function ajaxUploadLicense(){

        $this->form_validation->set_rules( 'license_number', 'Verification Number', 'required|min_length[5]' );
        if( $this->form_validation->run() == FALSE ) {
            echo validation_errors();
        } else {

            $upload_path_url = $this->config->item( 'upload_path_url' );
            $admin_url  	 = $this->config->item( 'admin_dir_url' );

            $talent_id   	= $this->session->userdata['TALENT_ID'];
            $license_id 	= trim($this->input->post( 'license_id' ));
            $license_number = trim($this->input->post( 'license_number' ));
            
            $licenseInfo    = $this->Talentmodel->talentLicenseInfo($talent_id, $license_id);
            //UPDATE
            if($licenseInfo){
            	$data = array('license_number'=> $license_number);
            	$licenseImg   = $licenseInfo->license_image;
            	$imguploadErr = 0;
            	if ($_FILES['license_image']['name']) {
	                $rename     = $talent_id.'_'.$license_id.'_license_'.time();
	                $uploadData = $this->fileUpload( 'license_image', 'license', $rename );
	                if( $uploadData['status'] == 'error'){
	                    $resData['message'] = $uploadData['msg'];
	                    $resData['result']  = 'failed';
	                    $imguploadErr = 1;
	                }
	                if( $uploadData['status'] == 'success'){
	                	$licenseImg = $uploadData['file_name'];
	                	$data['license_image'] = $licenseImg;
	                	if($licenseInfo->license_image){
	                		if(file_exists('ebuddey-admin/uploads/license/'.$licenseInfo->license_image)){
			                	unlink('ebuddey-admin/uploads/license/'.$licenseInfo->license_image);
			                }
			            }
	                }
	            }
	            if(!$imguploadErr){
	                $this->Talentmodel->updateTalentLicense($talent_id, $license_id, $data);
	                $resData['message'] 	= 'Verification update successfully.';
	                $resData['img_url'] 	= $admin_url.'uploads/license/'.$licenseImg;
	                $resData['license_no'] 	= $license_number;
	                $resData['result']  	= 'success';
	            }
            	header('Content-Type: application/json');
            	echo json_encode( $resData );
            	//INSERT
            }else{
            	$resData['result']  = 'failed';
            	if ($_FILES['license_image']['name']) {
	                $rename     = $talent_id.'_'.$license_id.'_license_'.time();
	                $uploadData = $this->fileUpload( 'license_image', 'license', $rename );
	                if( $uploadData['status'] == 'error'){
	                    $resData['message'] = $uploadData['msg'];
	                    $resData['result']  = 'failed';
	                }
	                if( $uploadData['status'] == 'success'){

	                	$data   = array( 
	                			'license_image'  => $uploadData['file_name'],
	                            'license_number' => $license_number,
	                            'license_id'     => $license_id,
	                            'talent_id'      => $talent_id,
	                            'created_by'     => $talent_id,
	                            'created_at'     => date("Y-m-d H:i:s")
	                            );
	                	$this->Talentmodel->addTalentLicense($data);

	                    $resData['message'] 	= 'Verification added successfully.';
	                    $resData['img_url'] 	= $admin_url.'uploads/license/'.$uploadData['file_name'];
	                    $resData['license_no'] 	= $license_number;
	                    $resData['result']  	= 'success';
	                }
	            }
	            header('Content-Type: application/json');
            	echo json_encode( $resData );
            }
        }
    }

    function getUploadForm(){
        if( !$this->input->is_ajax_request() ) {
            echo 'No Direct Access Denied';
            return false;
            exit();
        }
        $license_id  = $this->input->post( 'license_id' );
        $talent_id   = $this->session->userdata['TALENT_ID'];
        $tplData[ 'licenseInfo' ] = $this->Talentmodel->licenseInfo($license_id);
        $tplData[ 'talentLicenseInfo' ] = $this->Talentmodel->talentLicenseInfo($talent_id, $license_id);
        echo  $this->load->view( 'talent/license/license_upload_form', $tplData, true ); 
    }
    function ajax_license_data(){
        if( !$this->input->is_ajax_request() ) {
            echo 'No Direct Access Denied';
            return false;
            exit();
        }
        $tplData['getLicenseList'] = $this->Talentmodel->getLicenseList();
        echo  $this->load->view( 'talent/license/ajax_license_data', $tplData, true ); 
    }
    // END
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
        $this->load->view( 'talent/common/talent_content', $data );
        return true;
    }
}    
   