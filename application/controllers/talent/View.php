<?php
defined('BASEPATH') OR exit('No direct script access allowed');            

class View extends CI_Controller {  
    private $skillsArr = array('1'=>'Musician', '2'=>'Singer', '3'=>'Emcee', '4'=>'Clown', '5'=>'Magician', '6'=>'Statue', '7'=>'Performing arts', '8'=>'Drummer', '9'=>'Comedian');
    function __construct() {
        parent::__construct();
        $this->load->model( 'talent/Talentmodel' );  
        $this->load->helper('partner');
        $this->load->helper('timezone');
    }
	public function index() {

        $data_db     = array();
        $guider_id   = (124);
        $data_db ['imageLists']   = $this->Talentmodel->imageLists($guider_id);
        $data_db ['talentInfo']   = $this->Talentmodel->talentsInfo($guider_id);
        $data_db ['reviewList']   = $this->Talentmodel->reviewInfo(1,$guider_id);
        $data_db ['totalLike']    = $this->Talentmodel->ratingInfo($guider_id);
        $content     = $this->load->view( 'talent/common/view_profile', $data_db, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Townhall';
        $data[ 'header' ][ 'metakeyword' ]      = 'Townhall';
        $data[ 'header' ][ 'metadescription' ]  = 'Townhall';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ] = '<li class="active">Townhall</li>'; 
        $this->template( $data );
        return true;
	}
    function template( $data ){
        $this->load->view( 'talent/common/talent_content', $data );
        return true;
    }
}    