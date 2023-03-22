<?php
defined('BASEPATH') OR exit('No direct script access allowed');             

class Forums extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model( 'talent/Talentmodel');  
        $this->load->helper('talent_helper.php');
        $this->load->helper('timezone');
        talent_sessionset();
    }
	public function index() {

        $tplData     = array();
        $tplData ['newsLists']   = $this->Talentmodel->newsletter_lists();
        $content     = $this->load->view( 'talent/forums/index', $tplData, true );
		$talent_id   = $this->session->userdata['TALENT_ID'];
		$data['inboxreadinfo']  = $this->Talentmodel->talentInboxReadinfo($talent_id);
        $data[ 'navigation' ]                   = 'forums';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Forums';
        $data[ 'header' ][ 'metakeyword' ]      = 'Forums';
        $data[ 'header' ][ 'metadescription' ]  = 'Forums';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ] = '<li class="active">Forums</li>'; 
        $this->template( $data );
        return true;
	}
	function template( $data ){
        $this->load->view( 'talent/common/talent_content', $data );
        return true;
    }
}    
   