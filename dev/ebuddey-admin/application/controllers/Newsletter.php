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
        $script     = '';
        $newsletter_lists = $this->Newslettermodel->newsletter_lists();
        $data1[ 'newsletter_lists' ]     = $newsletter_lists;
        $content    = $this->load->view( 'newsletter/index', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Newsletter lists';
        $data[ 'header' ][ 'metakeyword' ]      = 'Newsletter lists';
        $data[ 'header' ][ 'metadescription' ]  = 'Newsletter lists';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">Newsletter lists</li>';
        $this->template( $data );
	}
    //Delete Newsletter
    function delete_newsletter() {
        $newsletter_id = $this->input->post( 'newsletterID' );
	//Delete Folder Image
        $newsletter_info = $this->Newslettermodel->newsletterInfo( $newsletter_id );
        $path = IMAGE_ROOT . 'newsletter/' . $newsletter_info->image_src;
        if( file_exists( $path ) ) {
            unlink( IMAGE_ROOT . "newsletter/".$newsletter_info->image_src );
            unlink( IMAGE_ROOT . "newsletter/thumb/".$newsletter_info->image_src );
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
            return false;
        } else {
            $title          = rawurlencode( $this->input->post( 'title' ) );
            $description    = rawurlencode( $this->input->post( 'description' ) );
            $image          = $this->input->post( 'newsletter_img' );
	        $image_src      = $this->input->post( 'newsletter_image' );
            $video_url      = $this->input->post( 'video_url' );
            if($video_url){
                if(!$this->validYURL($video_url)){
                    echo 'Please Enter valid URL.';
                    return false;
                    exit;
                }
            }
            $data           = array(
                                'title'         => $title,
                                'description'   => $description,
                                'image'         => $image,
				                'image_src'     => $image_src,
                                'video_url'     => $video_url,
                                'created_on'    => date('Y-m-d')
                                );
            echo $this->Newslettermodel->addNewsletter($data);
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
