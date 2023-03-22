<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Termsandconditions extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model( 'Commonmodel' );
    }
	public function index() {
        $tplData[ 'pageInfo' ] = $this->Commonmodel->pageInfo('term_and_condition');
		$content = $this->load->view( 'common/terms', $tplData, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Buddey | Terms and conditions';
        $data[ 'header' ][ 'metakeyword' ]      = 'Terms and conditions';
        $data[ 'header' ][ 'metadescription' ]  = 'Terms and conditions';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
		
        $this->template( $data );
        return true;
	}
	
	function template( $data ){
        $this->load->view( 'common/homecontent', $data );
        return true;
    }
}    
?>    