<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Newsletter extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('admin_helper.php');
        $this->load->model('Newslettermodel');
        sessionset();
        error_reporting(E_ALL);
    }
	public function index() {
        $newsletterInfo = array();
        $newsletter_id  = $this->input->get('newsletter_id');
        if($newsletter_id){
            $newsletterInfo = $this->Newslettermodel->newsletterInfo( $newsletter_id );
            if(!$newsletterInfo){ redirect( $this->config->item( 'admin_url' ) . 'newsletter' ); }
        }
        $data1[ 'newsletterInfo' ]     = $newsletterInfo;
        $data1[ 'newsletter_lists' ]   = $this->Newslettermodel->newsletter_lists();
        $content    = $this->load->view( 'newsletter/index', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Newsletter lists';
        $data[ 'header' ][ 'metakeyword' ]      = 'Newsletter lists';
        $data[ 'header' ][ 'metadescription' ]  = 'Newsletter lists';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">Newsletter lists</li>';
        $this->template( $data );
	}
    function view() {
        $newsletter_id  = $this->uri->segment(3);
        $newsletterInfo = $this->Newslettermodel->newsletterInfo( $newsletter_id );
        if(!$newsletterInfo){ redirect( $this->config->item( 'admin_url' ) . 'newsletter' ); }
        $data1[ 'newsletterInfo' ]              = $newsletterInfo;
        $data1[ 'newsletter_id' ]               = $newsletter_id;
        $content    = $this->load->view( 'newsletter/view', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'View Newsletter';
        $data[ 'header' ][ 'metakeyword' ]      = 'View Newsletter';
        $data[ 'header' ][ 'metadescription' ]  = 'View Newsletter';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '
                                              <li><a href="'.$this->config->item( 'admin_url' ).'newsletter">Newsletter List</a></li>
                                              <li class="active">View Newsletter</li>';
        $this->template( $data );
    }
    //Delete Newsletter
    function delete_newsletter() {
        $newsletter_id = $this->input->post( 'newsletterID' );
	//Delete Folder Image
        $newsletterInfo = $this->Newslettermodel->newsletterInfo( $newsletter_id );
        if($newsletterInfo){
            if($newsletterInfo->image){
                $tmp1 = explode('/', $newsletterInfo->image);
                $existing_file = end($tmp1);
                if (file_exists('uploads/newsletter/'.$existing_file)) {
                    unlink('uploads/newsletter/'.$existing_file);
                }
                if (file_exists('uploads/newsletter/thumb/'.$existing_file)) {
                    unlink('uploads/newsletter/thumb/'.$existing_file);
                }
            }
        }
        $this->db->where( 'newsletter_id', $newsletter_id );
        $this->db->delete( 'newsletter' );
        echo 1;
        return true;
    }
    public function validYURL($url){
        $rx = '~
              ^(?:https?://)?                           # Optional protocol
               (?:www[.])?                              # Optional sub-domain
               (?:youtube[.]com/watch[?]v=|youtu[.]be/) # Mandatory domain name (w/ query string in .com)
               ([^&]{11})                               # Video id of 11 characters as capture group 1
                ~x';
        $has_match = preg_match($rx, $url, $matches);
        return $has_match;
    }
    //Validation
    function newsletterValidate() {
        //Validate the form
        $this->form_validation->set_rules( 'title', 'Title', 'required' );
        /*$this->form_validation->set_rules( 'description', 'Description', 'required' );*/
        if( $this->form_validation->run() == FALSE ) {
            echo validation_errors();
        } else {
            $newsletter_id  = $this->input->post( 'newsletter_id' );
            $title          = rawurlencode( $this->input->post( 'title' ) );
            $description    = rawurlencode( $this->input->post( 'description' ) );
            $video_url      = $this->input->post( 'video_url' );
            if($video_url && !$this->validYURL($video_url)){
                echo 'Please Enter valid URL.';
            }else{
                $updateData = array();
                $upload_path_url = $this->config->item( 'upload_path_url' );
                $newsletterInfo  = $this->Newslettermodel->newsletterInfo( $newsletter_id );
                if ($_FILES['newsletterImg']['name']) {
                    $rename     = 'newsletter_'.time();
                    $allowedTypes = '*';
                    $uploadData = $this->Commonmodel->newFileUpload( 'newsletterImg', 'newsletter', $rename, $allowedTypes );
                    if( $uploadData['status'] == 'error'){
                        $this->session->set_flashdata('errorMSG', $uploadData['msg']);
                    }
                    if( $uploadData['status'] == 'success'){
                        $newsletterImg = $upload_path_url.'newsletter/'.$uploadData['file_name'];
                        $updateData['image'] = $newsletterImg;
                        if($newsletterInfo){
                            if($newsletterInfo->image){
                                $tmp1 = explode('/', $newsletterInfo->image);
                                $existing_file = end($tmp1);
                                if (file_exists('uploads/newsletter/'.$existing_file)) {
                                    unlink('uploads/newsletter/'.$existing_file);
                                }
                                if (file_exists('uploads/newsletter/thumb/'.$existing_file)) {
                                    unlink('uploads/newsletter/thumb/'.$existing_file);
                                }
                            }
                        }
                    }
                }
                if($newsletter_id){
                    $updateData['title'] = $title;
                    $updateData['description'] = $description;
                    $updateData['video_url'] = $video_url;
                    echo $this->Newslettermodel->updateNewsletter($newsletter_id, $updateData);
                }else{
                    $updateData['title'] = $title;
                    $updateData['description'] = $description;
                    $updateData['video_url'] = $video_url;
                    $updateData['created_on'] = date('Y-m-d');
                    echo $this->Newslettermodel->addNewsletter($updateData);
                }
            }
        }
    }
    public function profileUpload(){
        $allowedTypes   = 'gif|jpg|jpeg|png';
        $results        = $this->Commonmodel->fileUpload( 'newsletter', $allowedTypes );
        echo json_encode($results);
    }

	function template( $data ){
        $this->load->view( 'common/templatecontent', $data );
    }
}
