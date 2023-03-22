<?php
defined('BASEPATH') OR exit('No direct script access allowed');             

class Managebuskerspod extends CI_Controller {  
    private $skillsArr = array('1'=>'Musician', '2'=>'Singer', '3'=>'Emcee', '4'=>'Clown', '5'=>'Magician', '6'=>'Statue', '7'=>'Performing arts', '8'=>'Drummer', '9'=>'Comedian');
    function __construct() {
        parent::__construct();
        $this->load->model( 'talent/Talentmodel');  
        $this->load->helper('talent_helper.php');
        $this->load->helper('timezone');
        talent_sessionset();
    }
    //Buskerspod Tabel data
    public function index(){
        $start   = '';
        $end     = '';
        $filter  = $this->input->get( 'filter' );
        if($filter=='month'){
            $start = date("Y-m").'-01';
            $end   = date("Y-m-t", strtotime($start));
        }elseif($filter=='week'){
            $start = date("Y-m-d");
            $end   = date('Y-m-d', strtotime($start. ' + 6 days'));
        }elseif($filter=='custom'){
            $start = date("Y-m-d", strtotime($this->input->get( 'start' )));
            $end   = date("Y-m-d", strtotime($this->input->get( 'end' )));
        }
        $tplData['start']        = $start;
        $tplData['end']          = $end;
        $tplData['filter']       = $filter;
        $talent_id               = $this->session->userdata['TALENT_ID'];
        $tplData['partnerList']  = $this->Talentmodel->partnerList($talent_id);
        $tplData[ 'stateList' ]  = $this->Talentmodel->state_list( $country_id=132 );
        $tplData['skillsList']   = $this->skillsArr;
        $content = $this->load->view( 'talent/managebuskerspod/list', $tplData, true );
		$data['inboxreadinfo']  = $this->Talentmodel->talentInboxReadinfo($talent_id);
        $data[ 'navigation' ]                   = 'managebuskerspod';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Buskers Pod Tracker';
        $data[ 'header' ][ 'metakeyword' ]      = 'Buskers Pod Tracker';
        $data[ 'header' ][ 'metadescription' ]  = 'Buskers Pod Tracker';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">Buskers Pod Tracker</li>';
        $this->template( $data );
    }
    public function buskerspodTableResponse(){ 

        $data        = array();
        $results     = array();
        $where       = array();
        $startDate   = $this->input->post( 'startDate' );
        $endDate     = $this->input->post( 'endDate' );
        $talent_id  = $this->session->userdata['TALENT_ID'];
        $podLists    = $this->Talentmodel->get_datatables($where, $startDate, $endDate, $talent_id);
        if($_POST['start']){
            $si_no  = $_POST['start']+1;
        }else{
            $si_no  = 1;
        }
        $status = '';
        if($podLists){
            foreach ($podLists as $podInfo) {
                if ($podInfo->status == 0) {
                    $status = '<div style="padding-left: 5px;background: #d9534f;" class="un_available">Disabled</div>';
                }elseif($podInfo->status == 1){
                    $status = '<div style="padding-left: 5px;background: #398439;" class="available">Available</div>';    
                }elseif($podInfo->status == 2){
                    $status = '<div style="padding-left: 5px;background:#00c0ef;" class="available">Progress</div>';
                }elseif($podInfo->status == 3){
                    $guiderInfo = $this->Talentmodel->guiderInfo($podInfo->host_id);
                    if($guiderInfo){
                        $status = '<div style="padding-left: 5px;background: #9c27b0;" class="booked">'.$guiderInfo->first_name.'</div>';
                    }else{
                        $status = '';
                    }
                }elseif($podInfo->status == 4){
                    $hostInfo = $this->Talentmodel->guiderInfo($podInfo->lockedBy);
                    if($hostInfo){
                        $status = '<div style="padding-left: 5px;background:#f39c12;" class="locked">'.$hostInfo->first_name.'</div>';
                    }else{
                        $status = '';
                    }
                }
                
                $row    = array();
                $row[]  = $si_no;
                $row[]  = rawurldecode($podInfo->partner_name);
                $row[]  = $podInfo->cityName;
                $row[]  = date('d M Y', strtotime($podInfo->start));
                $row[]  = date('H:i', strtotime($podInfo->start));
                $row[]  = date('H:i', strtotime($podInfo->end));

                $data[] = $row;
                $si_no++;
            }
        }
        $results = array(
                        "draw"              => $_POST['draw'],
                        "recordsTotal"      => $this->Talentmodel->count_all($where, $startDate, $endDate, $talent_id),
                        "recordsFiltered"   => $this->Talentmodel->count_filtered($where, $startDate, $endDate, $talent_id),
                        "data"              => $data
                );
        echo json_encode($results);
    }

    public function payment() {
        $tplData     = array();
        $content     = $this->load->view( 'talent/managebuskerspod/payment', $tplData, true );
		 $talent_id               = $this->session->userdata['TALENT_ID'];
		$data['inboxreadinfo']  = $this->Talentmodel->talentInboxReadinfo($talent_id);
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Payments';
        $data[ 'header' ][ 'metakeyword' ]      = 'Payments';
        $data[ 'header' ][ 'metadescription' ]  = 'Payments';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ] = '<li class="active">Payments</li>'; 
        $this->template( $data );
        return true;
    }
    public function paymentTableResponse(){ 

        $data        = array();
        $results     = array();
        $where       = array();
        $talent_id   = $this->session->userdata['TALENT_ID'];
        $podLists    = $this->Talentmodel->get_payment_datatables($where, $talent_id);
        if($_POST['start']){
            $si_no  = $_POST['start']+1;
        }else{
            $si_no  = 1;
        }
        $status = '';
        if($podLists){
            foreach ($podLists as $podInfo) {
                
                $viewBtn = '<a href="javascript:;" title="View" onclick="return viewBookingSummary(\''.$podInfo->transactionID.'\');">View</a>';
                
                $row    = array();
                $row[]  = $si_no;
                $row[]  = date('d M Y', strtotime($podInfo->paidDatetime));
                $row[]  = $podInfo->totalAmt;
                $row[]  = $podInfo->transactionID;
                $row[]  = $viewBtn;

                $data[] = $row;
                $si_no++;
            }
        }
        $results = array(
                        "draw"              => $_POST['draw'],
                        "recordsTotal"      => $this->Talentmodel->count_payment_all($where, $talent_id),
                        "recordsFiltered"   => $this->Talentmodel->count_payment_filtered($where, $talent_id),
                        "data"              => $data
                );
        echo json_encode($results);
    }

    function viewBookingSummary(){
        $str = '';
        $transactionID  = $this->input->post( 'transactionID' );
        $transactionInfoByTxn = $this->Talentmodel->transactionInfoByTxn($transactionID);
        if($transactionInfoByTxn){
            $str .= '   <h4>Booking Summary</h4>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm">Transaction ID</label>
                            <div class="col-sm-8">
                                <div>'.$transactionInfoByTxn->transaction_id.'</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm">Payment date</label>
                            <div class="col-sm-8">
                                <div>'.date('d M Y', strtotime($transactionInfoByTxn->pay_updated)).'</div>
                            </div>
                        </div>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                              <th>Description</th>
                              <th>price</th>
                            </tr>
                          </thead>
                          <tbody>';
                    $bookedOrderSummaryList = $this->Talentmodel->bookedOrderSummaryByPartner($transactionID);
                    $totalAmt = 0;
                    if($bookedOrderSummaryList){
                        foreach ($bookedOrderSummaryList as $key => $value) {

                            $str .= '<tr><td colspan="2"><b><i class="fa fa-map-marker"></i> '.rawurldecode( $value->partner_name ).'</b></td></tr>';
                            $bookedDateSummaryList = $this->Talentmodel->bookedOrderSummaryByDate($value->partner_id, $transactionID);
                            foreach ($bookedDateSummaryList as $key2 => $value2) {
                                $str .= '<tr><td colspan="2"><b> '.date('D, jS M Y', strtotime($value2->start)).'</b></td></tr>';
                                $eventDate = date('Y-m-d', strtotime($value2->start));
                                $bookedOrderSummary = $this->Talentmodel->bookedOrderSummary($eventDate, $value->partner_id, $transactionID);
                                if($bookedOrderSummary){
                                    foreach ($bookedOrderSummary as $key3 => $value3) {
                                        $start_time = date('H:i', strtotime($value3->start));
                                        $end_time   = date('H:i', strtotime($value3->end));
                                        $str .= '<tr>
                                                  <td>'.$start_time.' to '.$end_time.'</td>
                                                  <td>'.CURRENCYCODE.' '.$value3->partnerFees.'</td>
                                                </tr>';
                                        $totalAmt += $value3->partnerFees;
                                    }
                                }
                            }
                        }
                    }
                    $str .= '</tbody>
                        </table>
                        <div class="form-group">
                        <b class="text pull-left">Total</b>
                        <p class="text pull-right"><b>'.CURRENCYCODE.'</b> <span id="price">'.$totalAmt.'</span>.00</p>
                      </div>';

            $revokeBookedList = $this->Talentmodel->revokeBookedList($transactionInfoByTxn->order_id);
            if($revokeBookedList){
                $str .='<div class="row col-md-12"><h4>Revoke Booking History</h4></div>
                            <table class="table table-bordered">
                            <thead>
                            <tr>
                              <th>Partner Name</th>
                              <th>Date</th>
                              <th>Time</th>
                              <th>Price</th>
                            </tr>
                          </thead>
                          <tbody>';
                
                foreach ($revokeBookedList as $key3 => $value3) {
                    $start_time = date('H:i', strtotime($value3->start));
                    $end_time   = date('H:i', strtotime($value3->end));
                    $partnerInfo= $this->Talentmodel->partnerInfo($value3->partner_id);
                    $str .= '<tr>
                                <td>'.(($partnerInfo)? rawurldecode($partnerInfo->partner_name) : '').'</td>
                                <td>'.date('D, jS M Y', strtotime($value3->start)).'</td>
                                <td>'.$start_time.' to '.$end_time.'</td>
                                <td>'.CURRENCYCODE.' '.$value3->amount.'</td>
                            </tr>';
                }
                $str .='</tbody>
                        </table>';
            }
            
        }else{
            $str .= '<h4>Booking Summary</h4>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <div>No Booking Summary found.</div>
                        </div>
                    </div>';
        }
        echo $str;
    }

    public function donations() {
        $tplData = array();
        $content = $this->load->view( 'talent/managebuskerspod/donation', $tplData, true );
		$talent_id               = $this->session->userdata['TALENT_ID'];
		$data['inboxreadinfo']  = $this->Talentmodel->talentInboxReadinfo($talent_id);
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Donations';
        $data[ 'header' ][ 'metakeyword' ]      = 'Donations';
        $data[ 'header' ][ 'metadescription' ]  = 'Donations';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ] = '<li class="active">Donations</li>'; 
        $this->template( $data );
        return true;
    }
    public function donationTableResponse(){ 

        $data        = array();
        $results     = array();
        $where       = array();
        $talent_id   = $this->session->userdata['TALENT_ID'];
        $donationLists = $this->Talentmodel->get_donation_datatables($where, $talent_id);
        if($_POST['start']){
            $si_no  = $_POST['start']+1;
        }else{
            $si_no  = 1;
        }
        if($donationLists){
            foreach ($donationLists as $donation) {
                
                $viewBtn = '<a href="javascript:;" title="View" onclick="return viewMessage('.$donation->payment_id.');">View message</a>';
                if ($donation->is_paid == 0) {
                    $status = 'Pending';
                }elseif($donation->is_paid == 1){
                    $status = 'Paid';
                }else{
                    $status = '';
                }
                $row    = array();
                $row[]  = $si_no;
                $row[]  = date('d M Y', strtotime($donation->pay_updated));
                $row[]  = $donation->fullName;
                $row[]  = $donation->transaction_id;
                $row[]  = number_format((float)$donation->transaction_amount, 2, '.', '');
                $row[]  = '0.00';
                $row[]  = number_format((float)$donation->sub_total, 2, '.', '');
                $row[]  = $status;
                $row[]  = $viewBtn;

                $data[] = $row;
                $si_no++;
            }
        }
        $results = array(
                        "draw"              => $_POST['draw'],
                        "recordsTotal"      => $this->Talentmodel->count_donation_all($where, $talent_id),
                        "recordsFiltered"   => $this->Talentmodel->count_donation_filtered($where, $talent_id),
                        "data"              => $data
                );
        echo json_encode($results);
    }

    function viewMessage(){
        $str = '';
        $payment_id  = $this->input->post( 'payment_id' );
        $paymentInfo = $this->Talentmodel->paymentInfo($payment_id);
        if($paymentInfo){
            $str .= '<div class="form-group row">
                        <div class="col-sm-12">
                            <div>'.$paymentInfo->message.'</div>
                        </div>
                    </div>';
                    
        }
        echo $str;
    }

	function template( $data ){
        $this->load->view( 'talent/common/talent_content', $data );
        return true;
    }
}    
   