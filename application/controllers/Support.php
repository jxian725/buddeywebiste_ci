<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Support extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model( 'Commonmodel' );
    }
	public function index() {

		$script      = '';
        $processing_fee = $this->Commonmodel->siteInfo('_processing_fee');
        if(!$processing_fee){ $processing_fee   = PROCESSING_FEE; }else{ $processing_fee = $processing_fee->s_value; }
        if(PROCESSING_FEE_ENABLED == 'NO'){ $processing_fee = 0; }
        
        $data_db[ 'faq_lists' ]     = $this->Commonmodel->faq_lists();
        $data_db[ 'processing_fee' ]            = $processing_fee;
		$content     = $this->load->view( 'home/faq', $data_db, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Buddey | FAQ';
        $data[ 'header' ][ 'metakeyword' ]      = 'FAQ';
        $data[ 'header' ][ 'metadescription' ]  = 'FAQ';
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
    