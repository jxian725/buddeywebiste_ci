<?php
defined('BASEPATH') OR exit('No direct script access allowed');   

class Dashboard extends CI_Controller {   
    function __construct() {
        parent::__construct();
        $this->load->helper('admin_helper.php');
        $this->load->model('Guidermodel');
        $this->load->model('Travellermodel');
        $this->load->model('Completedbookingsmodel'); 
        $this->load->model('Dashboardmodel'); 
        $this->load->helper('timezone');
        sessionset();
    }
	public function index()
	{

        $tplData[ 'host_total' ]        = $this->Commonmodel->host_total_count(1);
        $tplData[ 'guest_total' ]       = $this->Commonmodel->guest_total_count();
        $tplData[ 'host_pending' ]      = $this->Commonmodel->host_total_pending(0);
        $tplData[ 'patner_total' ]      = $this->Dashboardmodel->patner_total_count();
        $tplData[ 'webRequest' ]        = $this->Dashboardmodel->webRequest_pending(0);
        $tplData[ 'talent_feedback' ]   = $this->Dashboardmodel->talent_feedback(0);
        $tplData[ 'fans_feedback' ]     = $this->Dashboardmodel->fans_feedback(0);
        $tplData[ 'latest_patnerInfo' ] = $this->Dashboardmodel->latest_patnerInfo();
        $tplData[ 'latest_guestInfo' ]  = $this->Dashboardmodel->latest_guestInfo();
        $tplData[ 'latest_hostInfo' ]   = $this->Dashboardmodel->latest_hostInfo();
        $tplData[ 'booked_amount' ]     = $this->Dashboardmodel->booked_amount();
        $tplData[ 'available_amount' ]  = $this->Dashboardmodel->available_amount();

        $tplData[ 'Booked_hours' ]      = $this->Dashboardmodel->booked_hours();        
        $tplData[ 'Available_hours' ]   = $this->Dashboardmodel->available_hours();

        $monthYear  = array();
        $monthArr   = array();
        for ($i = 0; $i < 12; $i++) {
          $monthArr[] = date("M Y", strtotime( date( 'Y-m-01' )." -$i months"));
          $month    = date("m", strtotime( date( 'Y-m-01' )." -$i months"));
          $monthYear[$month] = date("Y", strtotime( date( 'Y-m-01' )." -$i months"));
        }
        $totalBookedHours = $this->Dashboardmodel->totalBookedHoursForChart($monthYear);
        $totalSale  = $this->Dashboardmodel->totalSaleForChart($monthYear);
        $oneDay     = array();
        $oneDay[]   = date('Y-m-d');
        $oneDayBookedHours = $this->Dashboardmodel->oneDayBookedHoursForChart($oneDay);
        $oneDaySale = $this->Dashboardmodel->oneDaySaleForChart($oneDay);
        $year   = date('Y');
        $month  = date('m');
        $number = cal_days_in_month(CAL_GREGORIAN,$month, $year);
        $oneMonth = array();
        for ($i=1; $i <= $number ; $i++) {
            $Month = date("d", strtotime( date('Y-m')."-$i"));
            $oneMonth[$Month] = date("Y-m-d", strtotime( date('Y-m')."-$i"));
        }
        $oneMonth_hours = $this->Dashboardmodel->oneMonthBookedHoursForChart($oneMonth);
        $monthSales     = $this->Dashboardmodel->oneMonthSaleForChart($oneMonth);
        $monthDayArr    = array();
        $currentDay     = date('j');
        for ($i=1; $i <= $currentDay ; $i++) {
            $monthDayArr[] = date("d", strtotime( date('Y-m')."-$i"));
        }
        $tplData[ 'chart1_label' ] = array_reverse(array_values($monthArr));
        $tplData[ 'chart2_label' ] = array_values($monthDayArr);
        $tplData[ 'chart1_data' ]  = array_reverse($totalBookedHours);
        $tplData[ 'chart2_data' ]  = array_reverse($totalSale);
        $tplData[ 'barChartDaydata' ]    = array_reverse($oneDayBookedHours);
        $tplData[ 'lineChartDaydata' ]   = array_reverse($oneDaySale);
        $tplData[ 'barChartMonthdata' ]  = array_values($oneMonth_hours);
        $tplData[ 'lineChartMonthdata' ] = array_values($monthSales);
        $tplData[ 'talent_booking' ]    = $this->Dashboardmodel->talent_latest_booking(3);
        $tplData[ 'guider_activity' ]   = $this->Dashboardmodel->guider_activity();
        $tplData[ 'fans_activity' ]     = $this->Dashboardmodel->fans_activity();
        $tplData[ 'fansFeedback' ]      = $this->Dashboardmodel->fans_feedback_joinned();
        $tplData[ 'guider_lists' ]      = $this->Guidermodel->guider_lists('', 1);
		$content      = $this->load->view( 'common/dashboard', $tplData, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Dashboard';
        $data[ 'header' ][ 'metakeyword' ]      = 'Dashboard';
        $data[ 'header' ][ 'metadescription' ]  = 'Dashboard';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ] = '<li class="active">Dashboard</li>';
        $this->template( $data );
	}
    public function completedtripTableResponse(){

        $data           = array();
        $results        = array();
        $where          = array();
        $bookingLists   = $this->Dashboardmodel->get_datatables($where);
        if($_POST['start']){
            $si_no  = $_POST['start']+1;
        }else{
            $si_no  = 1;
        }
        if($bookingLists){
            foreach ($bookingLists as $booking) {
                if($_POST['talent_filter']){
                    $talentBookedInfo = $this->Dashboardmodel->talentBookedInfo($booking->host_id, $_POST['talent_filter']);
                    if($talentBookedInfo){
                        $st_date    = new DateTime($talentBookedInfo->start);
                        $ed_date    = new DateTime($talentBookedInfo->end);
                        $diff_date  = $ed_date->diff($st_date);
                        $hours      = $diff_date->h;
                        $bookedHrs  = $hours + ($diff_date->days*24);
                        $totalHrs   = ($talentBookedInfo->total * $bookedHrs);
                        $totalHours = ($totalHrs)? $totalHrs : 0;
                        $partnerFees= ($talentBookedInfo->partnerFees)? $talentBookedInfo->partnerFees : 0;
                    }else{
                        $totalHours = 0;
                        $partnerFees= 0;
                    }
                }else{
                    $date1      = new DateTime($booking->start);
                    $date2      = new DateTime($booking->end);
                    $diff       = $date2->diff($date1);
                    $hours      = $diff->h;
                    $bookedHrs  = $hours + ($diff->days*24);
                    $totalHrs   = ($booking->total * $bookedHrs);
                    $totalHours = ($totalHrs)? $totalHrs : 0;
                    $partnerFees= ($booking->partnerFees)? $booking->partnerFees : 0;
                }
                
                
                $row    = array();
                $row[]  = $si_no;
                $row[]  = $booking->email;
                $row[]  = $totalHours;
                $row[]  = $partnerFees;
                $data[] = $row;
                $si_no++;
            }
        }
        $results = array(
                        "draw"              => $_POST['draw'],
                        "recordsTotal"      => $this->Dashboardmodel->count_all($where),
                        "recordsFiltered"   => $this->Dashboardmodel->count_filtered($where),
                        "data"              => $data
                );
        echo json_encode($results);
    }
    function bookingHoursFilter(){
        $filter_type     = $this->input->post( 'filter_type' );
        $Booked_hours    = $this->Dashboardmodel->booked_hours($filter_type);
        $total_hours     = $this->Dashboardmodel->total_hours($filter_type);
        //$percentContv    = $Booked_hours/$total_hours;
        $totalHrs        = $Booked_hours + $total_hours;
        $percentContv    = $Booked_hours / $totalHrs;
        $percentHrs = round( $percentContv * 100 ) . '%';
        $str = '<div class="row align-items-center">
                            <div class="box-header">
                               <h5 class="box-title">'. number_format($Booked_hours) .'/'. number_format($total_hours) .'</h5>
                            </div>
                        </div>    
                        <div class="box-body">
                            <div class="clearfix">
                                <small class="pull-right">'. $percentHrs .'</small>
                            </div>
                            <div class="progress xs">
                                <div class="progress-bar progress-bar-green" style="width: '. $percentHrs .';"></div>
                            </div>
                        </div>';
        echo $str;
    }

    function totalSalesFilter(){
        $filter_type       = $this->input->post( 'filter_type' );
        $booked_amount     = $this->Dashboardmodel->booked_amount($filter_type);
        $total_amount  = $this->Dashboardmodel->total_amount($filter_type);

        //$percentContv2 = $booked_amount/$total_amount;
        $totalAmt = $booked_amount+$total_amount;
        $percentContv2 = $booked_amount/$totalAmt;
        $percentAmt = round( $percentContv2 * 100 ) . '%';
        $str = '<div class="row align-items-center">
                            <div class="box-header">
                               <h5 class="box-title">'. number_format($booked_amount) .'/'. number_format($total_amount) .'</h5>
                            </div>
                        </div>    
                        <div class="box-body">
                            <div class="clearfix">
                                <small class="pull-right">'. $percentAmt .'</small>
                            </div>
                            <div class="progress xs">
                                <div class="progress-bar progress-bar-green" style="width: '. $percentAmt .';"></div>
                            </div>
                        </div>';
        echo $str;
    }

	function template( $data ){
        $this->load->view( 'common/templatecontent', $data );
    }
}
