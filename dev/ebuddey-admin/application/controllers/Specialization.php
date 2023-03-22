<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Specialization extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper( 'admin_helper.php' );
        $this->load->model( 'Specializationmodel' );
        sessionset();
        error_reporting(E_ALL);
    }
	public function index() {
        $script     = '';
        $specialization_lists = $this->Specializationmodel->specialization_lists();
        $data1[ 'specialization_lists' ]     = $specialization_lists;
        $content    = $this->load->view( 'specialization/index', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Specialization lists';
        $data[ 'header' ][ 'metakeyword' ]      = 'Specialization lists';
        $data[ 'header' ][ 'metadescription' ]  = 'Specialization lists';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">Specialization lists</li>';
        $this->template( $data );
	}
    //Delete specialization
    function delete_specialization() {
        $specialization_id = $this->input->post( 'specializationID' );
        $this->db->where( 'specialization_id', $specialization_id );
        $this->db->delete( 'specialization' );
        echo 1;
        return true;
    }
    //Validation
    function specializationValidate() {
        //Validate the form
        $this->form_validation->set_rules( 'specialization', 'Specialization', 'required' );
        if( $this->form_validation->run() == FALSE ) {
            echo validation_errors();
            return false;
        } else {
            $specialization = rawurlencode( $this->input->post( 'specialization' ) );
            $data           = array(
                                'specialization'=> $specialization,
                                'created_on'    => date( 'Y-m-d' )
                                );
            echo $this->Specializationmodel->addspecialization( $data );
        }
        return true;
    }
    

	function template( $data ){
        $this->load->view( 'common/templatecontent', $data );
    }
}
