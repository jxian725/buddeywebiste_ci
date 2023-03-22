<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faq extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('admin_helper.php');
        $this->load->model('Faqmodel');
        sessionset();
        error_reporting(E_ALL);
    }
	public function index() {
        $faq_lists = $this->Faqmodel->faq_lists();
        $data1[ 'faq_lists' ]     = $faq_lists;
        $content    = $this->load->view( 'faq/list', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'FAQ lists';
        $data[ 'header' ][ 'metakeyword' ]      = 'FAQ lists';
        $data[ 'header' ][ 'metadescription' ]  = 'FAQ lists';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">FAQ lists</li>';
        $this->template( $data );
    }

    public function add() {
        
        $tplData    = array();
        $content    = $this->load->view( 'faq/add', $tplData, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Add FAQ';
        $data[ 'header' ][ 'metakeyword' ]      = 'Add FAQ';
        $data[ 'header' ][ 'metadescription' ]  = 'Add FAQ';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">Add FAQ</li>';
        $this->template( $data );
    }

    public function addFaq(){
        $this->form_validation->set_rules( 'title', 'Title', 'required' );
        $this->form_validation->set_rules( 'content', 'Content', 'required' );
        if( $this->form_validation->run() == FALSE ) {
            echo validation_errors();
            $this->session->set_flashdata('errorMSG', 'Title or Content Cannot be empty'); 
        } else {
            $title    = $this->input->post('title');
            $content  = $this->input->post('content');
            $data     = array(
                            'title' => trim($title),
                            'content' => $content,
                            'created_on' => date('Y-m-d H:i:s')
                            );
            $this->Faqmodel->addFaq($data);
            $this->session->set_flashdata('successMSG', 'FAQ added successfully.');
        }
        redirect( $this->config->item( 'admin_url' ) . 'faq' );
    }
    public function edit() {
        
        $faq_id  = $this->uri->segment(3);
        $faqInfo = $this->Faqmodel->faqInfo( $faq_id );
        //if(!$faqInfo){ redirect( $this->config->item( 'admin_url' ) . 'faq' ); }
        $tplData['faqInfo']    = $faqInfo;
        $content    = $this->load->view( 'faq/edit', $tplData, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Add FAQ';
        $data[ 'header' ][ 'metakeyword' ]      = 'Add FAQ';
        $data[ 'header' ][ 'metadescription' ]  = 'Add FAQ';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">Add FAQ</li>';
        $this->template( $data );
    }

    public function updateFaq(){
        $this->form_validation->set_rules( 'faq_id', 'ID', 'required' );
        $this->form_validation->set_rules( 'title', 'Title', 'required' );
        $this->form_validation->set_rules( 'content', 'Content', 'required' );
        if( $this->form_validation->run() == FALSE ) {
            echo validation_errors();
            $this->session->set_flashdata('errorMSG', 'Title or Content Cannot be empty.');
        } else {
            $faq_id   = $this->input->post('faq_id');
            $title    = $this->input->post('title');
            $content  = $this->input->post('content');
            $data     = array(
                            'title'   => trim($title),
                            'content' => $content,
                            'created_on' => date('Y-m-d H:i:s')
                            );
            $this->Faqmodel->updateFaq($faq_id, $data);
            $this->session->set_flashdata('successMSG', 'FAQ updated successfully.');
        }
        redirect( $this->config->item( 'admin_url' ) . 'faq' );
    }

    public function faqFileUpload($value='')
    {
        
        if( !$_FILES[ 'file' ][ 'name' ] ) {
            return false;
        }
        $config['upload_path']      = './../faq_img';
        $config['allowed_types']    = 'gif|jpg|jpeg|png';
        $config['max_size']         = 15360; //15MB = 15*1024
        $config['max_width']        = '';
        $config['max_height']       = '';
        $rename     = 'faq_'.time();
        $filetype                   = $_FILES[ 'file' ][ 'type' ];
        $expfiletype                = explode( '.', $_FILES[ 'file' ][ 'name' ] );  
        $expfiletypeEx              = end($expfiletype);
        $config['file_name']        = $rename.'.'.$expfiletypeEx;

        $this->upload->initialize( $config );
        $this->load->library( 'upload', $config );

        $faqimg_url = $this->config->item( 'faqimg_url' );
        if ( ! $this->upload->do_upload( 'file' ) ) {
            $results = array( 'status' => 'error','msg' => $this->upload->display_errors() );
            echo json_encode($results);
        } else {
            $location = $faqimg_url.$config['file_name'];
            $uploadedImage = $this->upload->data();
            $results  = array( 'status' => 'success','location' => $location, 'msg' => 'file upload successfully.' );
            echo json_encode($results);
        }
    }

    function deleteFaq() {
        $faq_id  = $this->input->post('faq_id');
        $faqInfo = $this->Faqmodel->faqInfo( $faq_id );
        if($faqInfo){
            $this->Faqmodel->deleteFaq($faq_id);
            $this->session->set_flashdata('successMSG', 'FAQ deleted successfully.');
        }else{
            $this->session->set_flashdata('errorMSG', 'Delete Faq failed.');
        }
        echo 1;
    }

    function template( $data ){
        $this->load->view( 'common/templatecontent', $data );
    }
}    
?>    