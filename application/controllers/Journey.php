<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Journey extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model( 'Commonmodel' );
    }
	public function index() {

		$script      = '';
        $data_db[ 'journey_list' ] = $this->Commonmodel->journey_list();
		$content     = $this->load->view( 'home/journey', $data_db, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Journey';
        $data[ 'header' ][ 'metakeyword' ]      = 'Journey';
        $data[ 'header' ][ 'metadescription' ]  = 'Journey';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
		
        $this->template( $data );
        return true;
	}
	//View Journey Info
    function get_journey_info() {
        $journey_id  = $this->input->post( 'journey_id' );
        $data_db['get_journey']  = $this->Commonmodel->get_journey_view( $journey_id );
        $data_db[ 'journey_id' ] = $journey_id;
        $data_db[ 'type' ]       = 'ajax';
        echo $this->load->view( 'home/journey_view', $data_db, true );
        return true;
    }
	function template( $data ){
        $this->load->view( 'common/templatecontent', $data );
        return true;
    }
}    
?>    