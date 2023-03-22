<?php
defined('BASEPATH') OR exit('No direct script access allowed');             

class Gigs extends CI_Controller {  
    function __construct() {
        parent::__construct();
        $this->load->model( 'talent/Talentmodel');  
        $this->load->helper('talent_helper.php');
        $this->load->helper('timezone');
        talent_sessionset();
    }
	public function index() {

        $data_db     = array();
        $data_db ['newsLists']   = $this->Talentmodel->newsletter_lists();
        $content     = $this->load->view( 'talent/gigs/index', $data_db, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Townhall';
        $data[ 'header' ][ 'metakeyword' ]      = 'Townhall';
        $data[ 'header' ][ 'metadescription' ]  = 'Townhall';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ] = '<li class="active">Townhall</li>'; 
        $this->template( $data );
        return true;
	}
	function template( $data ){
        $this->load->view( 'talent/common/talent_content', $data );
        return true;
    }
}    
   