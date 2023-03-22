<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model( 'Commonmodel' );
    }
	public function index() {

        $tplData[ 'pageInfo' ] = $this->Commonmodel->pageInfo('about_us');
        $tplData[ 'venuePartnerImgLists' ] = $this->Commonmodel->venuePartnerImgLists();
        $content = $this->load->view( 'home/index', $tplData, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Buddey: Busker busking for every talent';
        $data[ 'header' ][ 'metakeyword' ]      = 'Buddey: Busker busking for every talent';
        $data[ 'header' ][ 'metadescription' ]  = 'Buddey is a platform for every musicians and performing arts talents to facilitates performances easy, fast, anywhere and anytime. Be in control, gain exposures and make money. Talents can also find part time jobs, no agents, no bias selection, secured payment and transparent.';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ] = '';
        $this->template( $data );
        return true;
	}
    public function searchCategoryLists()
    {
        $searchVal = $this->input->post('search');
        $Lists = $this->Commonmodel->searchCategoryLists($searchVal);
        if($Lists){
            echo '<ul>';
            foreach ($Lists as $key => $categoryInfo) {
                echo '<li onclick=\'fillValue("'.rawurldecode(trim($categoryInfo->specialization)).'","'.base64_encode($categoryInfo->specialization_id).'")\'>'.rawurldecode($categoryInfo->specialization).'</li>';
            }
            echo '<ul>';
        }
    }
    public function searchCityLists()
    {
        $searchVal = $this->input->post('search');
        $Lists = $this->Commonmodel->searchCityLists($searchVal);
        if($Lists){
            echo '<ul>';
            foreach ($Lists as $key => $cityInfo) {
                echo '<li onclick=\'fillValue2("'.rawurldecode(trim($cityInfo->name)).'","'.base64_encode($cityInfo->id).'")\'>'.rawurldecode($cityInfo->name).'</li>';
            }
            echo '<ul>';
        }
    }
	
	function template( $data ){
        $this->load->view( 'common/homecontent', $data );
        return true;
    }
}    
   