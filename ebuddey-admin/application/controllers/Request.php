<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Request extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('admin_helper.php');
        $this->load->model('Requestmodel');
        $this->load->model('Servicemodel');
        $this->load->library('phpqrcode/qrlib');
        sessionset();
        error_reporting(E_ALL);
    }
	public function index() {
        $script     = '';
        $data1      = array();
        $content    = $this->load->view( 'request/request', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Request List';
        $data[ 'header' ][ 'metakeyword' ]      = 'Request List';
        $data[ 'header' ][ 'metadescription' ]  = 'Request List';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">Request List</li>';
        $this->template( $data );
	}
    public function pendingtripTableResponse(){

        $data           = array();
        $results        = array();
        $where          = array();
        $serviceLists   = $this->Requestmodel->get_datatables($where);
        if($_POST['start']){
            $si_no  = $_POST['start']+1;
        }else{
            $si_no  = 1;
        }
        $status         = '';
        $totalAmount    = 0;
        $data           = array();
        if($serviceLists){
            foreach ($serviceLists as $request) {
                $requestID  = 'RQT'.str_pad($request->activity_request_id, 5, '0', STR_PAD_LEFT);
                $createdon  = date(getDateFormat(), strtotime($request->createdon)) .' '.date(getTimeFormat(), strtotime($request->createdon));
                if ($request->status == 1) {
                    $status     = '<span class="label label-success">Approved</span>';
                    $cancelbtn  = '';
                    $activebtn  = '';
                    $copybtn    = '';
                }elseif($request->status == 2){
                    $status     = '<span class="label label-primary">Payment Pending</span>';
                    $cancelbtn  = '';
                    $activebtn  = '';
                    $paymenturl = $this->config->item( 'paynow_url' ).'Makepayment/form/request_'.$request->request_uuid;
                    if($request->payment_type == 2){
                        $copybtn= '<a href="javascript:;" onClick="return showLinkModal('.$request->activity_request_id.');"  data-toggle="tooltip" title="Copy to Clipboard" class="btn btn-xs btn-primary"> <i class="fa fa-copy"></i></a>';
                    }else{
                        $copybtn= '<a href="javascript:;" onClick="return showCompletePayModal('.$request->activity_request_id.');"  data-toggle="tooltip" title="Click to Complete" class="btn btn-xs btn-success"> <i class="fa fa-edit"></i></a>';
                    }
                }elseif($request->status == 3){
                    $status     = '<span class="label label-danger">Cancelled</span>';
                    $cancelbtn  = '';
                    $activebtn  = '';
                    $copybtn    = '';
                }elseif($request->status == 0){ //PENDING
                    $status     = '<span class="label label-warning">Pending</span>';
                    $activebtn  = '<a href="javascript:;" onclick="return activeRequest('.$request->activity_request_id.', 1);" class="btn btn-info btn-xs">Approve</a>';
                    $cancelbtn  = '<a href="javascript:;" onclick="return requestStatus('.$request->activity_request_id.', 3);" class="btn btn-danger btn-xs">Cancel</a>';
                    $copybtn    = '';
                }
                if($request->payment_type == 1){
                    $paymentType= 'Cash';
                }else if($request->payment_type == 2){
                    $paymentType= 'SenangPay';
                }else{
                    $paymentType = 'n/a';
                }
                $row    = array();
                $row[]  = $requestID;
                $row[]  = $request->full_name;
                $row[]  = $request->countryCode.' '.$request->mobile_no;
                $row[]  = $request->email;
                $row[]  = rawurldecode($request->skillName);
                $row[]  = rawurldecode($request->cityName);
                $row[]  = $request->budget;
                $row[]  = $request->confirm_budget;
                $row[]  = $request->occasion;
                $row[]  = $request->venue;
                $row[]  = $request->time_hour;
                $row[]  = $createdon;
                $row[]  = $request->other_info;
                $row[]  = $paymentType;
                $row[]  = $status;
                $row[]  = $activebtn.$cancelbtn.$copybtn;

                $data[] = $row;
                $si_no++;
            }
        }
        $results = array(
                        "draw"              => $_POST['draw'],
                        "recordsTotal"      => $this->Requestmodel->count_all($where),
                        "recordsFiltered"   => $this->Requestmodel->count_filtered($where),
                        "data"              => $data
                );
        echo json_encode($results);
    }
    public function requestStatus(){
        $request_id = $this->input->post( 'request_id' );
        if($this->Requestmodel->requestInfo($request_id)){
            $data   = array(
                        'status'    => trim($this->input->post( 'status' )),
                        'updatedon' => date("Y-m-d H:i:s")
                        );
            $this->Requestmodel->updateRequest( $request_id, $data );
            echo 1;
        }
    }
    function confirmRequestForm() {
        $request_id  = $this->input->post( 'request_id' );
        $data1[ 'request_id' ]  = $request_id;
        $data1[ 'requestInfo' ] = $this->Requestmodel->requestInfo($request_id);
        echo $this->load->view( 'request/confirm_request', $data1, true );
        return true;
    }
    public function confirmRequest(){
        $request_id    = $this->input->post( 'request_id' );
        $paymentType   = $this->input->post( 'paymentType' );
        $confirmBudget = $this->input->post( 'confirm_budget' );
        if($this->Requestmodel->requestInfo($request_id)){
            $data   = array(
                        'status'        => 2,
                        'payment_type'  => $paymentType,
                        'confirm_budget'=> $confirmBudget,
                        'request_uuid'  => gen_uuid(),
                        'updatedon'     => date("Y-m-d H:i:s")
                        );
            $this->Requestmodel->updateRequest( $request_id, $data );
            echo json_encode( array('res'=>1) );
        }else{
            echo json_encode( array('Jmsg'=>'Invalid Request ID') );
        }
    }
    public function completePayment(){
        $request_id    = $this->input->post( 'request_id' );
        $ref_number    = $this->input->post( 'ref_number' );
        if($this->Requestmodel->requestInfo($request_id)){
            $data   = array( 
                        'status'        => 1, 
                        'ref_number'    => $ref_number, 
                        'updatedon'     => date("Y-m-d H:i:s")
                        );
            $this->Requestmodel->updateRequest( $request_id, $data );
            echo json_encode( array('res'=>1) );
        }else{
            echo json_encode( array('Jmsg'=>'Invalid Request ID') );
        }
    }
    function showLinkModal() {
        if( !$this->input->is_ajax_request() ) {
            echo 'No Direct Access Denied';
            return false;
            exit();
        }
        $request_id   = $this->input->post( 'request_id' );
        $requestInfo  = $this->Requestmodel->requestInfo( $request_id );
        if($requestInfo){
            $paymenturl = $this->config->item( 'paynow_url' ).'Makepayment/form/request_'.$requestInfo->request_uuid;
            $data_content[ 'paymenturl' ]  = $paymenturl;
            echo $this->load->view( 'request/show_link', $data_content, true );
        }
        return true;
    }
    function showCompletePayModal() {
        if( !$this->input->is_ajax_request() ) {
            echo 'No Direct Access Denied';
            return false;
            exit();
        }
        $request_id   = $this->input->post( 'request_id' );
        $requestInfo  = $this->Requestmodel->requestInfo( $request_id );
        if($requestInfo){
            $tplData['request_id']  = $request_id;
            $tplData['requestInfo'] = $requestInfo;
            echo $this->load->view( 'request/show_complete_pay', $tplData, true );
        }
        return true;
    }
    public function view()
    {
        $service_id    = $this->uri->segment(3);
        $serviceInfo   = $this->Servicemodel->serviceInfo($service_id);
        if(!$serviceInfo){ redirect( $this->config->item( 'admin_url' ) . 'pendingtrip' ); }
        $data1[ 'serviceInfo' ]                 = $serviceInfo;
        $data1[ 'service_id' ]                  = $service_id;
        $script     = '';
        $content    = $this->load->view( 'trip/pendingtrip_view', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Pending Bookings Information';
        $data[ 'header' ][ 'metakeyword' ]      = 'Pending Bookings Information';
        $data[ 'header' ][ 'metadescription' ]  = 'Pending Bookings Information';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '
                                              <li><a href="'.$this->config->item( 'admin_url' ).'pendingtrip">trip List</a></li>
                                              <li class="active">Pending Bookings Information</li>';
        $this->template( $data );
    }
	function template( $data ){
        $this->load->view( 'common/templatecontent', $data );
    }
}
