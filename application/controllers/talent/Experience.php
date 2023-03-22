<?php
defined('BASEPATH') OR exit('No direct script access allowed');             

class Experience extends CI_Controller {  

    function __construct() {
        parent::__construct();
        $this->load->model( 'talent/Talentmodel');  
        $this->load->helper('talent_helper.php');
        $this->load->library('phpqrcode/qrlib');
        $this->load->helper('timezone');
        talent_sessionset();
    }
    public function index() {
        $tplData = array();
        $data[ 'content' ] = $this->load->view( 'talent/experience/index', $tplData, true );
        $data[ 'navigation' ]        = 'experience';
        $data[ 'header' ][ 'title' ] = 'Talent Experiences';
        $data[ 'breadcrumb' ] = '<li class="active">Talent Experiences</li>'; 
        $this->template( $data );
    }

    public function manage() {
        $tplData = array();
        $data[ 'content' ] = $this->load->view( 'talent/experience/manage', $tplData, true );
        $data[ 'navigation' ]        = 'experience';
        $data[ 'header' ][ 'title' ] = 'Manage Talent Experiences';
        $data[ 'breadcrumb' ] = '<li class="active">Talent Experiences</li>'; 
        $this->template( $data );
    }

    public function view() {
        $tplData = array();
        $tplData[ 'talentExpInfo' ] = $this->Talentmodel->ltalentExperienceInfo();
        $data[ 'content' ] = $this->load->view( 'talent/experience/view', $tplData, true );
        $data[ 'navigation' ]        = 'experience';
        $data[ 'header' ][ 'title' ] = 'Manage Talent Experiences';
        $data[ 'breadcrumb' ] = '<li class="active">Talent Experiences</li>'; 
        $this->template( $data );
    }

    public function add() {
        $tplData = array();
        $tplData['specialization_lists'] = $this->Talentmodel->specialization_lists();
        $tplData[ 'cityLists' ]  = $this->Talentmodel->state_list( $country_id=132, 0 );
        $tplData[ 'getTalentLangLists' ] = $this->Talentmodel->getTalentLangLists();
        $data[ 'content' ] = $this->load->view( 'talent/experience/add', $tplData, true );
        $data[ 'navigation' ]        = 'experience';
        $data[ 'header' ][ 'title' ] = 'Add Talent Experiences';
        $data[ 'breadcrumb' ] = '<li class="active">Talent Experiences</li>'; 
        $this->template( $data );
    }
    public function edit() {
        $te_id    = $this->uri->segment(4);
        $talentExperienceInfo   = $this->Talentmodel->talentExperienceInfo($te_id);
        if(!$talentExperienceInfo){ redirect( base_url() . 'talent/experience/view' ); }
        $tplData[ 'talentExpInfo' ] = $talentExperienceInfo;
        $tplData['specialization_lists'] = $this->Talentmodel->specialization_lists();
        $tplData[ 'cityLists' ]  = $this->Talentmodel->state_list( $country_id=132, 0 );
        $tplData[ 'getTalentLangLists' ] = $this->Talentmodel->getTalentLangLists();
        $data[ 'content' ] = $this->load->view( 'talent/experience/edit', $tplData, true );
        $data[ 'navigation' ]        = 'experience';
        $data[ 'header' ][ 'title' ] = 'Update Talent Experiences';
        $data[ 'breadcrumb' ] = '<li class="active">Talent Experiences</li>'; 
        $this->template( $data );
    }

    function addExperience(){
        if( !$this->input->is_ajax_request() ) {
            echo 'No Direct Access Denied';
            return false;
            exit();
        }
        $this->form_validation->set_rules( 'skills_category', 'Category', 'required' );
        $this->form_validation->set_rules('experience_title', 'Title', 'required');
        $this->form_validation->set_rules('city', 'City', 'required');
        $this->form_validation->set_rules('video_link', 'Video link', 'required');
        $this->form_validation->set_rules('price_rate', 'Price', 'required|numeric');
        if( $this->form_validation->run() == FALSE ) {   
            echo validation_errors();
        }else{
            $video_link = $this->input->post( 'video_link' );
            if($video_link && !$this->validYURL($video_link)){
                echo 'Please Enter valid Youtube URL';
            }else{
                $talent_id = $this->session->userdata['TALENT_ID'];
                $languages_known = $this->input->post( 'languages_known' );
                if($languages_known){
                    $languages = implode(',', $languages_known);
                    $languagesKnown = $languages;
                }else{
                    $languagesKnown = '';
                }
                $insertData= array(
                                'talent_id'     => $talent_id,
                                'languages_known' => $languagesKnown,
                                'experience_title'=> trim($this->input->post('experience_title')),
                                'city'          => trim($this->input->post('city')),
                                'about_us'      => trim($this->input->post('about_us')),
                                'requirement'   => trim($this->input->post('requirement')),
                                'skills_category'=> trim($this->input->post('skills_category')),
                                'video_link'    => trim($this->input->post('video_link')),
                                'price_rate'    => trim($this->input->post('price_rate')),
                                'created_by'    => $talent_id,
                                'created_at'    => date("Y-m-d H:i:s")
                            );
                $insert = $this->Talentmodel->addTalentExperience($insertData);
                if($insert){
                    echo 1;
                }else{
                    echo 'Some error has occurred. Please try again later.';
                }
            }
        }
    }
    function updateExperience(){
        if( !$this->input->is_ajax_request() ) {
            echo 'No Direct Access Denied';
            return false;
            exit();
        }
        $this->form_validation->set_rules( 'skills_category', 'Category', 'required' );
        $this->form_validation->set_rules('experience_title', 'Title', 'required');
        $this->form_validation->set_rules('city', 'City', 'required');
        $this->form_validation->set_rules('video_link', 'Video link', 'required');
        $this->form_validation->set_rules('price_rate', 'Price', 'required|numeric');
        if( $this->form_validation->run() == FALSE ) {   
            echo validation_errors();
        }else{
            $video_link = $this->input->post( 'video_link' );
            if($video_link && !$this->validYURL($video_link)){
                echo 'Please Enter valid Youtube URL';
            }else{
                $talent_id  = $this->session->userdata['TALENT_ID'];
                $te_id      = $this->input->post( 'te_id' );
                $languages_known = $this->input->post( 'languages_known' );
                if($languages_known){
                    $languages = implode(',', $languages_known);
                    $languagesKnown = $languages;
                }else{
                    $languagesKnown = '';
                }
                $updateData = array(
                                'languages_known' => $languagesKnown,
                                'experience_title'=> trim($this->input->post('experience_title')),
                                'city'          => trim($this->input->post('city')),
                                'about_us'      => trim($this->input->post('about_us')),
                                'requirement'   => trim($this->input->post('requirement')),
                                'skills_category'=> trim($this->input->post('skills_category')),
                                'video_link'    => $video_link,
                                'price_rate'    => trim($this->input->post('price_rate')),
                                'status' => 0
                            );
                $update = $this->Talentmodel->updateTalentExperience($talent_id, $te_id, $updateData);
                if($update){
                    echo 1;
                }else{
                    echo 'Some error has occurred. Please try again later.';
                }
            }
        }
    }
    public function validYURL($url){
        $rx = '~
              ^(?:https?://)?                           # Optional protocol
               (?:www[.])?                              # Optional sub-domain
               (?:youtube[.]com/watch[?]v=|youtu[.]be/) # Mandatory domain name (w/ query string in .com)
               ([^&]{11})                               # Video id of 11 characters as capture group 1
                ~x';
            //com/embed/
        $has_match = preg_match($rx, $url, $matches);
        return $has_match;
    }
    function expApproval() {
        if( !$this->input->is_ajax_request() ) {
            echo 'No Direct Access Denied';
            return false;
            exit();
        }
        $talent_id  = $this->session->userdata['TALENT_ID'];
        $te_id   = $this->input->post( 'te_id' );
        $updateData[ 'status' ] = 2;
        $this->Talentmodel->updateTalentExperience($talent_id, $te_id, $updateData);
        echo json_encode( array('status' => 1, 'message' => 'Talent experience submitted successfully!') );
    }
    
	function template( $data ){
        $this->load->view( 'talent/common/talent_content', $data );
        return true;
    }
}    
   