<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Termsandconditions extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model( 'Commonmodel' );
    }
	public function index() {

		$script      = '';
        $processing_fee = $this->Commonmodel->siteInfo('_processing_fee');
        if(!$processing_fee){ $processing_fee   = PROCESSING_FEE; }else{ $processing_fee = $processing_fee->s_value; }
        if(PROCESSING_FEE_ENABLED == 'NO'){ $processing_fee = 0; }
        $data_db[ 'processing_fee' ]            = $processing_fee;
		$content     = $this->load->view( 'home/terms', $data_db, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Terms and conditions';
        $data[ 'header' ][ 'metakeyword' ]      = 'Terms and conditions';
        $data[ 'header' ][ 'metadescription' ]  = 'Terms and conditions';
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