<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Service extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('admin_helper.php');
        $this->load->model('Servicemodel');
        sessionset();
        error_reporting(E_ALL);
    }
	public function index() {
        $script         = '';
        $service_search     = $this->input->get('service_search');
        $order_by           = $this->input->get('order_by');
        $serviceLists   = $this->Servicemodel->serviceListData( '', '', '', '', $service_search, $order_by );
        $data1[ 'serviceLists' ]                = $serviceLists;
        $content    = $this->load->view( 'service/service', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Service lists';
        $data[ 'header' ][ 'metakeyword' ]      = 'Service lists';
        $data[ 'header' ][ 'metadescription' ]  = 'Service lists';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">Service lists</li>';
        $this->template( $data );
	}
    public function view()
    {
        $service_id    = $this->uri->segment(3);
        $serviceInfo   = $this->Servicemodel->serviceInfo($service_id);
        if(!$serviceInfo){ redirect( $this->config->item( 'admin_url' ) . 'service' ); }
        $data1[ 'serviceInfo' ]                 = $serviceInfo;
        $data1[ 'service_id' ]                  = $service_id;
        $script     = '';
        $content    = $this->load->view( 'service/service_info', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Service Info';
        $data[ 'header' ][ 'metakeyword' ]      = 'Service Info';
        $data[ 'header' ][ 'metadescription' ]  = 'Service Info';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '
                                              <li><a href="'.$this->config->item( 'admin_url' ).'service">Service List</a></li>
                                              <li class="active">Service Info</li>';
        $this->template( $data );
    }
	function template( $data ){
        $this->load->view( 'common/templatecontent', $data );
    }
}
