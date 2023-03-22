<?php
defined('BASEPATH') OR exit('No direct script access allowed');             

class Feedback extends CI_Controller {  

    function __construct() {
        parent::__construct();
        $this->load->model( 'talent/Talentmodel');  
        $this->load->helper('talent_helper.php');
        $this->load->helper('timezone');
        talent_sessionset();
    }
    // Feedback
    function index(){ 
        $tplData  = array();
        $talent_id = $this->session->userdata['TALENT_ID'];
        $feedback_status = ('1,2');
        $status   = explode(',',$feedback_status);
        $tplData['talentFeedbackLists']   = $this->Talentmodel->talentFeedbackLists($talent_id, $status);
        $content = $this->load->view( 'talent/feedback/index', $tplData, true );
		$data['inboxreadinfo']  = $this->Talentmodel->talentInboxReadinfo($talent_id);
        $data[ 'navigation' ]                   = 'feedback';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Feedback';
        $data[ 'header' ][ 'metakeyword' ]      = 'Feedback';
        $data[ 'header' ][ 'metadescription' ]  = 'Feedback';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">Feedback</li>';
        $this->template( $data );
    }
    function addFeedback(){
        error_reporting(0);
        $this->form_validation->set_rules( 'subject', 'Partner Name', 'required|min_length[3]' );
        $this->form_validation->set_rules('description', 'Description', 'required');
        if( $this->form_validation->run() == FALSE ) {   
            echo validation_errors();
        }else{
            $talent_id = $this->session->userdata['TALENT_ID'];
            $data   = array(
                        'fb_guider_id'      => $talent_id,
                        'subject'           => trim($this->input->post( 'subject' )),
                        'description'       => trim($this->input->post( 'description' )),
                        'device_type'       => 1,
                        'is_read'           => 0,
                        'feedback_status'   => 1,
                        'createdon'         => date("Y-m-d H:i:s")
                    );
            $feedback_id  = $this->Talentmodel->addFeedback($data);
            if($feedback_id){
                $this->session->set_flashdata('successMSG', 'Feedback Added successfully.');
                echo 1;
            }else{
                echo 'Some error has occurred. Please try again later.';
            }
        }
    }
	function template( $data ){
        $this->load->view( 'talent/common/talent_content', $data );
        return true;
    }
}    
   