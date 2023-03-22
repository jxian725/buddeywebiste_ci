<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cities extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('admin_helper.php');
        $this->load->model('Guidermodel');
        $this->load->model('Guiderpayoutmodel');
        sessionset();
        error_reporting(E_ALL);
    }
	public function index() {
        $script     = '';
        $country_id = 132;//MY COUNTRY ID
        $guider_search  = $this->input->get('guider_search');
        $order_by       = $this->input->get('order_by');
        $data1[ 'state_list' ]      = $this->Commonmodel->state_list( $country_id );
        $content    = $this->load->view( 'city/city', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Cities lists';
        $data[ 'header' ][ 'metakeyword' ]      = 'Cities lists';
        $data[ 'header' ][ 'metadescription' ]  = 'Cities lists';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">Cities lists</li>';
        $this->template( $data );
	}
    function cityStatus(){
        $city_id    = $this->input->post( 'city_id' );
        $status     = $this->input->post( 'status' );
        $this->Commonmodel->cityStatus( $city_id, $status );
        /*if($status == 1){
            $this->session->set_flashdata('successMSG', 'City Activate successfully.');
        }else{
            $this->session->set_flashdata('successMSG', 'City Deactivate successfully.');
        }*/
    }
	function template( $data ){
        $this->load->view( 'common/templatecontent', $data );
    }
}
