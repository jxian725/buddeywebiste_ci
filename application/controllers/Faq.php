<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faq extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model( 'Commonmodel' );
    }
	public function index() {

        $search = ($this->input->get('search'))? $this->input->get('search') : '';
        $processing_fee = $this->Commonmodel->siteInfo('_processing_fee');
        if(!$processing_fee){ $processing_fee   = PROCESSING_FEE; }else{ $processing_fee = $processing_fee->s_value; }
        if(PROCESSING_FEE_ENABLED == 'NO'){ $processing_fee = 0; }
        $tplData[ 'faq_lists' ]     = $this->Commonmodel->faq_lists($search);
        $tplData[ 'processing_fee' ]            = $processing_fee;
		$content     = $this->load->view( 'faq/index', $tplData, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Buddey | FAQ';
        $data[ 'header' ][ 'metakeyword' ]      = 'FAQ';
        $data[ 'header' ][ 'metadescription' ]  = 'FAQ';
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
    