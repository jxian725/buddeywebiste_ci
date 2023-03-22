<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Requestor extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('admin_helper.php');
        $this->load->model('Travellermodel');
        sessionset();
        error_reporting(E_ALL);
    }
	public function index() {
        $script     = '';
        $requestor_search   = $this->input->get('requestor_search');
        $order_by           = $this->input->get('order_by');
        $requestor_lists = $this->Travellermodel->traveller_lists( $requestor_search, $order_by );
        $data1[ 'requestor_lists' ]     = $requestor_lists;
        $content    = $this->load->view( 'requestor/requestor', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Requestor lists';
        $data[ 'header' ][ 'metakeyword' ]      = 'Requestor lists';
        $data[ 'header' ][ 'metadescription' ]  = 'Requestor lists';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">Requestor lists</li>';
        $this->template( $data );
	}
    public function view()
    {
        $traveller_id    = $this->uri->segment(3);
        $requestorInfo   = $this->Travellermodel->travellerInfo($traveller_id);
        if(!$requestorInfo){ redirect( $this->config->item( 'admin_url' ) . 'requestor' ); }
        $data1[ 'requestorInfo' ]               = $requestorInfo;
        $data1[ 'traveller_id' ]                = $traveller_id;
        $script     = '';
        $content    = $this->load->view( 'requestor/requestor_info', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Requestor Info';
        $data[ 'header' ][ 'metakeyword' ]      = 'Requestor Info';
        $data[ 'header' ][ 'metadescription' ]  = 'Requestor Info';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '
                                              <li><a href="'.$this->config->item( 'admin_url' ).'requestor">Requestor List</a></li>
                                              <li class="active">Requestor Info</li>';
        $this->template( $data );
    }
    function requestorStatus(){
        $requestor_id   = $this->input->post( 'traveller_id' );
        $status         = $this->input->post( 'status' );
        $delete         = $this->Travellermodel->requestorStatus( $requestor_id, $status );
        return true;
    }
	function template( $data ){
        $this->load->view( 'common/templatecontent', $data );
    }
}
