<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Privacypolicy extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model( 'Commonmodel' );
    }
	public function index() {

		$script      = '';
        
		$content     = $this->load->view( 'home/privacy', '', true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Privacy Policy';
        $data[ 'header' ][ 'metakeyword' ]      = 'Privacy Policy';
        $data[ 'header' ][ 'metadescription' ]  = 'Privacy Policy';
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
?>    