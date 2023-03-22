<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pendingpayout extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('admin_helper.php');
        $this->load->model('Guidermodel');
        $this->load->model('Guiderpayoutmodel');
        $this->load->model('Pendingpayoutmodel');
        sessionset();
        error_reporting(E_ALL);
    }
	public function index() {
        $payout_btn  = $this->input->post( 'payout_btn' );
        if($payout_btn == 'proceed_payout'){
            $guiderlist     = $this->input->post( 'guiderlist' );
            $payoutlist     = $this->input->post( 'payoutlist' );
            $notriplist     = $this->input->post( 'notriplist' );
            $totalamtlist   = $this->input->post( 'totalamtlist' );
            $servicefeelist = $this->input->post( 'servicefeelist' );
            if($guiderlist){
                foreach ($guiderlist as $key => $guider_id) {
                    $totalTrip          = $notriplist[$key];
                    $payoutAmt          = $payoutlist[$key];
                    $transactionAmt     = $totalamtlist[$key];
                    $percentageAmt      = $servicefeelist[$key];
                    $this->Guiderpayoutmodel->updateExcutePayout($guider_id, $payoutAmt, $transactionAmt, $percentageAmt, $totalTrip);
                }
                $this->session->set_flashdata('successMSG', 'Payout executed Successfully.');
            }
        }
        $script         = '';
        $guider_id      = $this->input->get('guider_id');
        $data1[ 'guider_lists' ]    = $this->Guidermodel->guider_lists( false,false );
        $content    = $this->load->view( 'payout/pendingpayout', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Pending Payout lists';
        $data[ 'header' ][ 'metakeyword' ]      = 'Pending Payout lists';
        $data[ 'header' ][ 'metadescription' ]  = 'Pending Payout lists';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">Pending Payout lists</li>';
        $this->template( $data );
	}
    public function pendingPayoutTableResponse(){

        $data           = array();
        $results        = array();
        $where          = array();
        $serviceLists   = $this->Pendingpayoutmodel->get_datatables($where);
        if($_POST['start']){
            $si_no  = $_POST['start']+1;
        }else{
            $si_no  = 1;
        }
        $status         = '';
        $totalAmount    = 0;
        $data           = array();
        if($serviceLists){
            foreach ($serviceLists as $service) {
                $paybtn     = '<a href="javascript:;" class="btn btn-sm bg-green" onclick="return confirmPayout('.$service->jny_guider_id.','.$service->payoutAmt.','.$service->transactionAmt.','.$service->percentageAmt.','.$service->noTrip.');">Execute Payout</button>';
                $viewbtn    = '<a href="'.base_url().'pendingpayout/view/'.$service->jny_guider_id.'" class="btn btn-info btn-sm"><i class="glyphicon glyphicon-search"></i></a>';
                $lastPayoutInfo = $this->Guiderpayoutmodel->lastPayoutInfo($service->jny_guider_id);
                if($lastPayoutInfo){
                    $payoutDate     = date(getDateFormat(), strtotime($lastPayoutInfo->createdon));
                    $payoutAmount   = number_format((float)$lastPayoutInfo->payoutAmount, 2, '.', '');
                }else{
                    $payoutDate     = '-';
                    $payoutAmount   = '-';
                }
                $radiobtn = '<input class="chkallguiderclass" name="pay_guider_id[]" value="'.$service->jny_guider_id.'" totalamtattr="'.$service->transactionAmt.'" servicefeeattr="'.$service->percentageAmt.'" payoutattr="'.$service->payoutAmt.'" notripattr="'.$service->noTrip.'" type="checkbox" />';
                //CALCULATE SERVICE FEE
                $ServiceFees = (PROCESSING_FEE / 100) * $service->transactionAmt;
                if($ServiceFees < 2){ $ServiceFees = 02.00; }
                $payoutAmt  = $service->transactionAmt - $ServiceFees;
                if($service->service_price_type_id == 3){ 
                    $payoutAmt      = 0;
                    $ServiceFees    = 0;
                }
                $row    = array();
                $row[]  = $si_no;
                $row[]  = $service->guiderName;
                $row[]  = $service->noTrip;
                $row[]  = number_format((float)$service->transactionAmt, 2, '.', '');
                //$row[]  = number_format((float)$service->percentageAmt, 2, '.', '');
                $row[]  = number_format((float)$ServiceFees, 2, '.', '');
                //$row[]  = number_format((float)$service->payoutAmt, 2, '.', '');
                $row[]  = number_format((float)$payoutAmt, 2, '.', '');
                $row[]  = $payoutDate;
                $row[]  = $payoutAmount;
                $row[]  = $radiobtn;
                $row[]  = $paybtn.$viewbtn;

                $data[] = $row;
                $si_no++;
            }
        }
        $results = array(
                        "draw"              => $_POST['draw'],
                        "recordsTotal"      => $this->Pendingpayoutmodel->count_all($where),
                        "recordsFiltered"   => $this->Pendingpayoutmodel->count_filtered($where),
                        "data"              => $data
                );
        echo json_encode($results);
    }
    public function view()
    {
        $script         = '';
        $guider_id      = $this->uri->segment(3);
        $data1[ 'guider_info' ]     = $this->Guidermodel->guiderInfo( $guider_id );
        $data1[ 'pendingPaymentLists' ]  = $this->Guiderpayoutmodel->guiderPendingPaymentLists( $guider_id );
        $content    = $this->load->view( 'payout/pendingpayout_view', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Pending Payout View';
        $data[ 'header' ][ 'metakeyword' ]      = 'Pending Payout View';
        $data[ 'header' ][ 'metadescription' ]  = 'Pending Payout View';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">Pending Payout View</li>';
        $this->template( $data );
    }
    function excutePayout(){
        $guider_id      = $this->input->post( 'guider_id' );
        $payoutAmt      = $this->input->post( 'payoutAmt' );
        $transactionAmt = $this->input->post( 'transactionAmt' );
        $percentageAmt  = $this->input->post( 'percentageAmt' );
        $totalTrip      = $this->input->post( 'totalTrip' );
        $this->Guiderpayoutmodel->updateExcutePayout( $guider_id, $payoutAmt, $transactionAmt, $percentageAmt, $totalTrip );
    }
    function excutePayoutAllForm() {
        
        $guiderlist     = $this->input->post( 'guiderlist' );
        $payoutlist     = $this->input->post( 'payoutlist' );
        $totalamtlist   = $this->input->post( 'totalamtlist' );
        $servicefeelist = $this->input->post( 'servicefeelist' );
        $notriplist     = $this->input->post( 'notriplist' );
        //print_r($pay_guider_id);
        $str = '<div class="row">
                    <form method="post" id="guiderpayoutform">
                    <div class="box-body">
                       <table class="table table-bordered">
                        <tr>
                          <th>#</th>
                          <th>'.HOST_NAME.' Name</th>
                          <th>Total Trip</th>
                          <th>Payout Amount</th>
                        </tr>';
                        $i = 1;
                        if($guiderlist){
                            foreach ($guiderlist as $key => $guider_id) {
                                $guiderInfo = $this->Guidermodel->guiderInfo( $guider_id );
                            $str .= '<tr>
                                      <td>
                                      '.$i.'
                                      <input name="guiderlist[]" value="'.$guider_id.'" type="hidden" />
                                      <input name="notriplist[]" value="'.$notriplist[$key].'" type="hidden" />
                                      <input name="payoutlist[]" value="'.$payoutlist[$key].'" type="hidden" />
                                      <input name="servicefeelist[]" value="'.$servicefeelist[$key].'" type="hidden" />
                                      <input name="totalamtlist[]" value="'.$totalamtlist[$key].'" type="hidden" />
                                      </td>
                                      <td>'.$guiderInfo->first_name.'</td>
                                      <td>'.$notriplist[$key].'</td>
                                      <td>'.$payoutlist[$key].'</td>
                                    </tr>';
                                $i++;
                            }
                        }else{
                            echo '<tr><td colspan="4">No List Found.</td><tr>';
                        }
            $str .= '    </table>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-12 pull-right">
                        <div class="pull-right">
                            <button type="submit" name="payout_btn" id="payout_btn" value="proceed_payout" class="btn btn-success">Confirm Payout</button>
                            <button data-dismiss="modal" class="btn btn-danger">Cancel</button>
                        </div>
                    </div>
                    </form>
                </div>';
        echo $str;
        return true;
    }
	function template( $data ){
        $this->load->view( 'common/templatecontent', $data );
    }
}
