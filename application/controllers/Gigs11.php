<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 

class Gigs extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model( 'Commonmodel' );
        $this->load->model( 'Requestmodel' );
    }
	public function index() {

        $dataData[ 'cityLists' ] = $this->Commonmodel->searchCityLists();
        $dataData[ 'categoryLists' ] = $this->Commonmodel->searchCategoryLists();
        $content     = $this->load->view( 'gigs/index', $dataData, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Buddey | Gigs';
        $data[ 'header' ][ 'metakeyword' ]      = 'Gigs';
        $data[ 'header' ][ 'metadescription' ]  = 'Gigs';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $this->template( $data );
        return true;
	}

    function  Add_Gigs(){

        $this->form_validation->set_rules( 'full_name', 'Full name', 'required' );
        $this->form_validation->set_rules( 'mobile_no', 'Mobile', 'required|min_length[6]' );
        $this->form_validation->set_rules( 'email', 'Email', 'trim|required' );
        $this->form_validation->set_rules( 'budget', 'Budget', 'required' );
        $this->form_validation->set_rules( 'date', 'Date', 'required' );
        if( $this->form_validation->run() == FALSE ) {
            $result = array('res_status' => 'error', 'message' => validation_errors(), 'res_data'=>array('error' => 1));
        } else {
            $data   = array(
                        'full_name'     => trim($this->input->post( 'full_name' )),
                        'countryCode'   => trim($this->input->post( 'countryCode' )),
                        'mobile_no'     => trim($this->input->post( 'mobile_no' )),
                        'email'         => trim($this->input->post( 'email' )),
                        'skill_id'      => trim(base64_decode($this->input->post( 'skill_id' ))),
                        'city_id'       => trim(base64_decode($this->input->post( 'city_id' ))),
                        'budget'        => trim($this->input->post( 'budget' )),
                        'occasion'      => '',
                        'venue'         => '',
                        'time_hour'     => trim($this->input->post( 'time_hour' )),
                        'date'          => date('Y-m-d',strtotime($this->input->post( 'date' ))),
                        'other_info'    => trim($this->input->post( 'other_info' )),
                        'status'        => 0,
                        'createdon'     => date("Y-m-d H:i:s")
                    );
            $request_id = $this->Requestmodel->insertRequest( $data );
            //$this->session->set_flashdata('successMSG', 'Gigs Created successfully.');
            $result = array('res_status' => 'success', 'message' => 'Request submitted.', 'res_data'=>array('request_id' => strtoupper($request_id)));
        }
        echo json_encode($result);
    }
	
	function template( $data ){
        $this->load->view( 'common/homecontent', $data );
        return true;
    }
}    
   