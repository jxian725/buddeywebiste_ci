<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Traveller extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('admin_helper.php');
        $this->load->model('Travellermodel');
        $this->load->library('encryption');
        sessionset();
        error_reporting(E_ALL);
    }

	public function index() {
        $script     = '';
        $requestor_search   = $this->input->get('traveller_search');
        $order_by           = $this->input->get('order_by');
        $traveller_lists = $this->Travellermodel->traveller_lists( $requestor_search, $order_by );
        $data1[ 'traveller_lists' ]     = $traveller_lists;
        $content    = $this->load->view( 'traveller/traveller', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = GUEST_NAME.' lists';
        $data[ 'header' ][ 'metakeyword' ]      = GUEST_NAME.' lists';
        $data[ 'header' ][ 'metadescription' ]  = GUEST_NAME.' lists';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">'.GUEST_NAME.' lists</li>';
        $this->template( $data );
	}
    public function travellerTableResponse(){
        global $permission_arr;
        $data           = array();
        $where          = array();
        $travellerLists = $this->Travellermodel->get_datatables($where);
        if($_POST['start']){
            $si_no  = $_POST['start']+1;
        }else{
            $si_no  = 1;
        }
        $status         = '';
        $totalAmount    = 0;
        if($travellerLists){
            $statusbtn  = '';
            foreach ($travellerLists as $traveller) {
                if( $traveller->status == 0 ){
                    $status     = '<span class="label label-warning">Pending</span>';
                    $statusbtn  = '<a href="javascript:;" onClick="return travellerStatus('.$traveller->traveller_id.', 1);" class="btn btn-warning btn-xs" data-toggle="tooltip" data-original-title="Click to Activate"><i class="fa fa-toggle-off" aria-hidden="true"></i></a>';
                }else if($traveller->status == 1){
                    $status     = '<span class="label label-success">Active</span>';
                    $statusbtn  = '<a href="javascript:;" onClick="return travellerStatus('.$traveller->traveller_id.', 2);" class="btn btn-success btn-xs" data-toggle="tooltip" data-original-title="Click to Inactive"><i class="fa fa-toggle-on" aria-hidden="true"></i></a>';
                }else if($traveller->status == 2){
                    $status     = '<span class="label label-danger">Inactive</span>';
                    $statusbtn  = '<a href="javascript:;" onClick="return travellerStatus('.$traveller->traveller_id.', 1);" class="btn btn-warning btn-xs" data-toggle="tooltip" data-original-title="Click to Activate"><i class="fa fa-toggle-off" aria-hidden="true"></i></a>';
                }
                if( in_array( 'traveller/status', $permission_arr ) ) {
                    $statusbtnAp = $statusbtn;
                }else{
                    $statusbtnAp = '';
                }
                if( in_array( 'traveller/index', $permission_arr ) ) {
                    $viewbtn = '<a class="btn btn-primary btn-xs" href="'.$this->config->item( 'admin_url' ).'traveller/view/'.$traveller->traveller_id.'"><i class="fa fa-search"></i></a>';
                }else{
                    $viewbtn = '';
                }
                if( in_array( 'traveller/deleteTraveller', $permission_arr ) ) {
                    $deletebtn = '<a class="btn btn-danger btn-xs" href="javascript:;" onClick="return passwordConfirm('.$traveller->traveller_id.');"><i class="fa fa-remove" data-toggle="tooltip" data-original-title="delete"></i></a>';
                }else{
                    $deletebtn = '';
                }
                $lang = [];
                if($traveller->languages_known){
                    $array =  explode(',', $traveller->languages_known);
                    foreach ($array as $item) {
                        $langInfo = $this->Travellermodel->travellerLangInfo($item);
                        if($langInfo){ $lang[] = $langInfo->language; }
                    }
                }
                $row    = array();
                $row[]  = $si_no;
                $row[]  = $traveller->first_name;
                $row[]  = $traveller->email;
                $row[]  = $traveller->phone_number;
                $row[]  = ucfirst( implode(',', $lang) );
                $row[]  = mb_substr($traveller->about_me, 0, 25);
                $row[]  = $status;
                $row[]  = $statusbtnAp.$viewbtn.$deletebtn;

                $data[] = $row;
                $si_no++;
            }
        }
        $results = array(
                        "draw"              => $_POST['draw'],
                        "recordsTotal"      => $this->Travellermodel->count_all($where),
                        "recordsFiltered"   => $this->Travellermodel->count_filtered($where),
                        "data"              => $data
                );
        echo json_encode($results);
    }

    public function view()
    {
        global $permission_arr;
        if( !in_array( 'traveller/index', $permission_arr ) ) {
            echo 'No Direct Access Denied';
            return false;
            exit();
        }
        $traveller_id    = $this->uri->segment(3);
        $travellerInfo   = $this->Travellermodel->travellerInfo($traveller_id);
        if(!$travellerInfo){ redirect( $this->config->item( 'admin_url' ) . 'traveller' ); }
        $data1[ 'travellerInfo' ]               = $travellerInfo;
        $data1[ 'traveller_id' ]                = $traveller_id;
        $script     = '';
        $content    = $this->load->view( 'traveller/traveller_info', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = GUEST_NAME.' Profile';
        $data[ 'header' ][ 'metakeyword' ]      = GUEST_NAME.' Profile';
        $data[ 'header' ][ 'metadescription' ]  = GUEST_NAME.' Profile';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '
                                              <li><a href="'.$this->config->item( 'admin_url' ).'traveller">'.GUEST_NAME.' List</a></li>
                                              <li class="active">'.GUEST_NAME.' Profile</li>';
        $this->template( $data );
    }
    function travellerStatus(){
        $traveller_id  = $this->input->post( 'traveller_id' );
        $status     = $this->input->post( 'status' );
        $delete     = $this->Travellermodel->travellerStatus( $traveller_id, $status );
        return true;
    }
    function deleteTraveller(){
        $traveller_id   = $this->input->post( 'traveller_id' );
        $status         = $this->input->post( 'status' );

        $travellerInfo = $this->Travellermodel->travellerInfo($traveller_id);
        $del_phone_number = $travellerInfo->phone_number.'_RM'.$traveller_id;
        $data       = array('status' => $status, 'phone_number' => $del_phone_number);

        $delete     = $this->Travellermodel->deleteTraveller( $traveller_id, $data );
        return true;
    }
    //Password Update
    function passwordConfirm() {
        $traveller_id  = $this->input->post( 'traveller_id' );
        $data1[ 'traveller_id' ] = $traveller_id;
        $traveller_info = $this->Travellermodel->travellerInfo( $traveller_id );
        $data1[ 'traveller_info' ] = $traveller_info;
        echo $this->load->view( 'traveller/password_update_form', $data1, true );
        return true;
    }
    //Check Password Exists
    function update_password_info() {
        $traveller_id  = $this->input->post( 'traveller_id' );
        $user_id    = $this->session->userdata( 'USER_ID' );
        $password   = $this->input->post( 'password' );
        $this->form_validation->set_rules( 'password', 'Password', 'required' );
        if( $this->form_validation->run() == FALSE ) {
            $data[ 'Jmsg' ]     = validation_errors();
            $data[ 'Jerror' ]   = 1;
            echo json_encode( $data );
            return false;
        } else {
            if( !$this->Travellermodel->check_password( $user_id, $password ) ) {
                $data[ 'Jmsg' ]     = 'Enter password not matched.';
                $data[ 'Jerror' ]   = 3;
                echo json_encode( $data );
                return false;
            } else {
                $data[ 'Jerror' ]   = 2;
                echo json_encode( $data );
                return true;
            }
        }
    }
	function template( $data ){
        $this->load->view( 'common/templatecontent', $data );
    }
}
