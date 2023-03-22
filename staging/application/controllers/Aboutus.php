<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Aboutus extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model( 'Commonmodel' );
    }
	public function index() {

		$script      = '';
        
		$content     = $this->load->view( 'home/aboutus', '', true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'About us';
        $data[ 'header' ][ 'metakeyword' ]      = 'About us';
        $data[ 'header' ][ 'metadescription' ]  = 'About us';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
		
        $this->template( $data );
        return true;
	}
	
	function template( $data ){
        $this->load->view( 'common/templatecontent', $data );
        return true;
    }
}    
    