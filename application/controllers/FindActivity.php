<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FindActivity extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model( 'Commonmodel' );
        $this->load->model( 'Requestmodel' );
        $this->load->helper('timezone');
    }
	public function index() {
        $script     = '';
        $skill_id   = base64_decode($this->input->get( 'skill_id' ));
        $city_id    = base64_decode($this->input->get( 'city_id' ));
        $skillInfo  = $this->Commonmodel->skillInfo( $skill_id );
        $cityInfo   = $this->Commonmodel->cityInfo( $city_id );
        if($skillInfo){ $search_skill = rawurldecode($skillInfo->specialization); }else{ $search_skill = ''; }
        if($cityInfo){ $search_city   = rawurldecode($cityInfo->name); }else{ $search_city = ''; }
        $data_db[ 'guiderlists' ]   = $this->Commonmodel->get_guider_activity_lists( $skill_id, $city_id );
        $data_db[ 'skill_id' ]      = base64_encode($skill_id);
        $data_db[ 'city_id' ]       = base64_encode($city_id);
        $data_db[ 'search_skill' ]  = $search_skill;
        $data_db[ 'search_city' ]   = $search_city;
        //$content     = $this->load->view( 'guider/find_activity', $data_db, true );
        $content     = $this->load->view( 'guider/search_activity', $data_db, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Buddey | Find Activity';
        $data[ 'header' ][ 'metakeyword' ]      = 'Find Activity';
        $data[ 'header' ][ 'metadescription' ]  = 'Find Activity';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
        
        $this->template( $data );
        return true;
    }
	function template( $data ){
        $this->load->view( 'common/templatecontent', $data );
        return true;
    }
}    
    