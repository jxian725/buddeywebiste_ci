<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pendingguider extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('admin_helper.php');
        $this->load->model('Guidermodel');
        $this->load->model('Guiderpayoutmodel');
        sessionset();
        error_reporting(E_ALL);
        $this->load->library('encryption');
    }
	public function index() {
        $script     = '';
        $guider_search  = $this->input->get('guider_search');
        $order_by       = $this->input->get('order_by');
        $guider_lists = $this->Guidermodel->pendingGuiderLists( $guider_search, $order_by );
        $data1[ 'guider_lists' ]     = $guider_lists;
        $content    = $this->load->view( 'guider/pending_guider', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Pending '.HOST_NAME.' lists';
        $data[ 'header' ][ 'metakeyword' ]      = 'Pending '.HOST_NAME.' lists';
        $data[ 'header' ][ 'metadescription' ]  = 'Pending '.HOST_NAME.' lists';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">Pending '.HOST_NAME.' lists</li>';
        $this->template( $data );
	}
    public function view()
    {
        global $permission_arr;
        if( !in_array( 'pendingguider/index', $permission_arr ) ) {
            echo 'No Direct Access Denied';
            return false;
            exit();
        }
        $guider_id    = $this->uri->segment(3);
        $guiderInfo   = $this->Guidermodel->guiderInfo($guider_id);
        $activityLists= $this->Guidermodel->guiderActivityLists($guider_id);
        if(!$guiderInfo){ redirect( $this->config->item( 'admin_url' ) . 'guider' ); }
        $data1[ 'guiderInfo' ]                  = $guiderInfo;
        $data1[ 'guider_id' ]                   = $guider_id;
        $data1[ 'activityLists' ]               = $activityLists;
        $script     = '';
        $content    = $this->load->view( 'guider/pending_guider_info', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = HOST_NAME.' Profile';
        $data[ 'header' ][ 'metakeyword' ]      = HOST_NAME.' Profile';
        $data[ 'header' ][ 'metadescription' ]  = HOST_NAME.' Profile';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '
                                              <li><a href="'.$this->config->item( 'admin_url' ).'guider">'.HOST_NAME.' List</a></li>
                                              <li class="active">'.HOST_NAME.' Profile</li>';
        $this->template( $data );
    }
	function template( $data ){
        $this->load->view( 'common/templatecontent', $data );
    }
}
