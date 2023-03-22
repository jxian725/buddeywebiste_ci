<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl       = $this->config->item( 'admin_url' );
$site_name      = $this->config->item( 'site_name' ); 

?>
<link href="<?= $dirUrl; ?>plugins/dataTables/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
<script src="<?= $dirUrl; ?>plugins/dataTables/jquery.dataTables.min.js"></script>
<script src="<?= $dirUrl; ?>plugins/dataTables/dataTables.bootstrap.min.js"></script>
<style type="text/css">
/* Tree box */
.count_box .h4, h4 {
    font-size: 2.75rem;
}
.count_box .h6, h6 {
    font-size: 2.00rem;
}
.bg-c-yellow {
    background: -webkit-gradient(linear, left top, right top, from(#fe9365), to(#feb798));
    background: linear-gradient(to right, #fe9365, #feb798);
}
.bg-c-lite-green {
    background: -webkit-gradient(linear, left top, right top, from(#01a9ac), to(#01dbdf));
    background: linear-gradient(to right, #01a9ac, #01dbdf);
}
.bg-c-pink {
    background: -webkit-gradient(linear, left top, right top, from(#eb3422), to(#ef5f51));
    background: linear-gradient(to right, #eb3422, #ef5f51);
}
.card {
    border-radius: 5px;
    box-shadow: 0 1px 20px 0 rgba(69,90,100,0.08);
    border: none;
    margin-bottom: 30px;
}
.update-card {
    color: #fff;
}
.count_box .row, .progress_bar .row {
    display: flex;
    flex-wrap: wrap;
    margin-right: -0px;
    margin-left: -0px;
}
.card-block {
    padding: 10px;
    height: 100px;
}
.card-footer {
    padding: .75rem 1.25rem;
    background-color: rgba(0,0,0,.03);
    border-top: 1px solid rgba(0,0,0,.125);
}
.update-card .card-footer {
    background-color: transparent;
    border-top: 1px solid #fff;
}
.m-b-0 {
    margin-bottom: 0px;
}
.text-right, canvas{
    display: block;
    width: 51px;
    height: 50px;
    text-align: right!important;
}
.col-4 {
    flex: 0 0 22.222222%;
    max-width: 33.333333%;
    margin-top: 20px;
}
.col-8 {
    flex: 0 0 66.666667%;
    max-width: 66.666667%;
}

/*pending approvel and slide bar */
ul.timeline {
    list-style-type: none;
    position: relative;
}
ul.timeline:before {
    content: ' ';
    background: #d4d9df;
    display: inline-block;
    position: absolute;
    left: 26px;
    width: 2px;
    height: 100%;
    z-index: 200;
}
ul.timeline > li {
    margin: 20px 0;
    padding-left: 45px;
}
ul.timeline > li:before {
    content: ' ';
    background: white;
    display: inline-block;
    position: absolute;
    border-radius: 50%;
    border: 3px solid #01a9ac;
    left: 20px;
    width: 15px;
    height: 15px;
    z-index: 400;
}

.filter-box {
    list-style-type:none;
}
.filter-box li {
    float:left;
    margin:0 5px 0 0;
    width:50px;
    height:38px;
    position:relative;
}
.filter-box label, .filter-box input {
    display:block;
    position:absolute;
    top:0;
    left:0;
    right:0;
    bottom:0;
}

.filter-box input[type="radio"] {
    opacity:0.011;
    z-index:100;
}

.filter-box input[type="radio"]:checked + label {
    background: #3c8dbc;
    color: #fff;
}

.filter-box label {
    padding:5px;
    border:1px solid #CCC;
    background: #f4f4f4;
    text-align: center;
    cursor:pointer;
    z-index:90;
}

.filter-box label:hover {
     background:#DDD;
}
</style>

<div class="count_box row">
    <div class="col-sm-8">
        <div class="col-xl-12 col-sm-4" style="padding-left: 0px;">
            <div class="card bg-c-lite-green update-card">
                <div class="card-block">
                    <div class="row align-items-end">
                        <div class="col-8">
                            <h4 class="text-white"><?=$host_total;?></h4>
                            <h6 class="text-white m-b-0">Total <?= HOST_NAME; ?></h6>
                        </div>
                        <div class="col-4 text-right">
                            <canvas id="update-chart-1" height="50" width="51"></canvas>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <p class="text-white m-b-0"><i class="fa fa-clock-o"></i> update : <?= rawurldecode(date(getTimeFormat('H:i A'), strtotime( $latest_hostInfo->updated_on ) ) ); ?></p>
                </div>
            </div>
        </div>
        <div class="col-xl-12 col-md-4">
            <div class="card bg-c-pink update-card">
                <div class="card-block">
                    <div class="row align-items-end">
                        <div class="col-8">
                            <h4 class="text-white"><?=$guest_total;?></h4>
                            <h6 class="text-white m-b-0">Total <?= GUEST_NAME; ?></h6>
                        </div>
                        <div class="col-4 text-right">
                            <canvas id="update-chart-2" height="50"></canvas>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <p class="text-white m-b-0"><i class="fa fa-clock-o"></i> update : <?= rawurldecode(date(getTimeFormat('H:i A'), strtotime( $latest_guestInfo->updated_on ) ) ); ?></p>
                </div>
            </div>
        </div>
        <div class="col-xl-12 col-md-4" style="padding-right: 0px;">
            <div class="card bg-c-yellow update-card">
                <div class="card-block">
                    <div class="row align-items-end">
                        <div class="col-8">
                            <h4 class="text-white"><?=$patner_total;?></h4>
                            <h6 class="text-white m-b-0">Buskers Pods</h6>
                        </div>
                        <div class="col-4 text-right">
                            <canvas id="update-chart-3" height="50"></canvas>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <p class="text-white m-b-0"><i class="fa fa-clock-o"></i> update : <?= rawurldecode(date(getTimeFormat('H:i A'), strtotime( $latest_patnerInfo->updated_on ) ) ); ?></p>
                </div>
            </div>
        </div>

        <div class="box-header with-border">
            <h3 class="box-title">Performance Overview</h3>
        </div>        
        <div class="box box-primary">
            <div class="box-header with-border">
                <i class="fa fa-bar-chart-o"></i>
                <h3 class="box-title">Filter by</h3>
                <div class="box-tools">
                    <select name="graph_type" id="graph_type" class="btn btn-flat" style="height: 35px;width: auto;">
                        <option value="1">Hours of booking</option>
                        <option value="2">Total Sales (RM)</option>
                    </select>    
                    <ul class="filter-box" style="float: right;padding-left: 10px;">
                        <li>
                            <input type="radio" id="today_filter" class="performance_filter" name="performance_filter" value="1" />
                            <label for="today_filter">Today</label>
                        </li>
                        <li>
                            <input type="radio" id="month_filter" class="performance_filter" name="performance_filter" value="2" />
                            <label for="month_filter">1M</label>
                        </li>
                        <li>
                            <input type="radio" id="year_filter" class="performance_filter" name="performance_filter" value="3" />
                            <label for="year_filter">1Y</label>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="box-body">
                <div class="card-body">
                    <div class="chart" id="barChart1Div">
                        <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                    <div class="chart" id="lineChart1Div" style="display:none;">
                        <canvas id="lineChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                    <div class="chart" id="barChart3Div" style="display:none;">
                        <canvas id="barChart_D" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                    <div class="chart" id="barChart2Div" style="display:none;">
                        <canvas id="barChart_M" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                    <div class="chart" id="lineChart3Div" style="display:none;">
                        <canvas id="lineChart_D" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                    <div class="chart" id="lineChart2Div" style="display:none;">
                        <canvas id="lineChart_M" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-6 col-sm-6" style="padding-left: 0px;">
                <div class="box box-solid">
                    <div class="box-header with-border">
                       <h3 class="box-title">Hours of Booking (Hours)</h3>
                    </div>
                    <div id="hours_content">
                        <div class="row align-items-center">
                            <div class="box-header">
                               <h5 class="box-title"><?= number_format($Booked_hours); ?>/<?= number_format($Available_hours);?></h5>
                            </div>
                        </div>    
                        <div class="box-body">
                            <?php
                            $totalHrs     = $Booked_hours + $Available_hours;
                            $percentContv = $Booked_hours/$totalHrs;
                            $percentHrs   = round( $percentContv * 100 ) . '%';
                            ?>
                            <div class="clearfix">
                                <!-- <span class="pull-left">Task #1</span> -->
                                <small class="pull-right"><?= $percentHrs; ?></small>
                            </div>
                            <div class="progress xs">
                                <div class="progress-bar progress-bar-green" style="width: <?= $percentHrs; ?>;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-sm-6" style="padding-right: 0px;">
                <div class="box box-solid">
                    <div class="box-header with-border">
                       <h3 class="box-title">Total Sales (RM)</h3>
                    </div>
                    <div id="sales_content">
                        <div class="row align-items-center">
                            <div class="box-header">
                                <h5 class="box-title"><?= number_format($booked_amount); ?>/<?= number_format($available_amount); ?></h5>
                            </div>
                        </div> 
                        <div class="box-body">
                            <?php
                            $totalAmt      = $booked_amount+$available_amount;
                            $percentContv2 = $booked_amount/$totalAmt;
                            $percentAmt    = round( $percentContv2 * 100 ) . '%';
                            ?>
                            <div class="clearfix">
                                <!-- <span class="pull-left">Task #1</span> -->
                                <small class="pull-right"><?= $percentAmt; ?></small>
                            </div>
                            <div class="progress xs">
                                <div class="progress-bar progress-bar-green" style="width: <?= $percentAmt; ?>;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TALENT DATA TABLE -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <p class="title">Booking Activities</p>
            </div>
            <div class="box-header with-border">
                <p class="box-title">Filter by :&nbsp;<span id="filter_by_name">All</span></p>
                <div class="box-tools">
                    <?php
                    $filter_t1 = date('d M Y');
                    $filter_t2 = '1st '.date('M') .' - '.date('dS M Y');
                    $filter_t3 = 'Jan - '.date('M Y');
                    ?>
                    <ul class="filter-box">
                        <li>
                            <input type="radio" id="filter1" class="talent_filter" name="talent_filter" value="1" />
                            <label for="filter1">Today</label>
                        </li>
                        <li>
                            <input type="radio" id="filter2" class="talent_filter" name="talent_filter" value="2" />
                            <label for="filter2">1M</label>
                        </li>
                        <li>
                            <input type="radio" id="filter3" class="talent_filter" name="talent_filter" value="3" />
                            <label for="filter3">1Y</label>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="box-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">
                        <div class="box-body table-responsive no-padding">
                            <table id="talent_list" class="table table-bordered data-tbl">
                                <thead>
                                    <tr class="tbl_head_bg">
                                        <th class="head1 no-sort">#</th>
                                        <th class="head1 no-sort">Talents</th>
                                        <th class="head1 no-sort">Hours</th>
                                        <th class="head1 no-sort">Sales(<?= CURRENCYCODE; ?>)</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr class="tbl_head_bg">
                                        <th class="head2 no-sort"></th>
                                        <th class="head2 no-sort"></th>
                                        <th class="head2 no-sort"></th>
                                        <th class="head2 no-sort"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="clearfix"></div>
                    </div>
                </div> 
            </div>
        </div>

    </div>

    <div class="col-sm-4">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Latest Activity</h3>
            </div>
             <div class="box-body">
                <ul class="timeline">
                <?php
                $today = date('Y-m-d');
                if ($guider_activity) {
                    foreach ($guider_activity as $activity) {
                        $signupDate = (date('Y-m-d',strtotime($activity->created_on )));
                        if($signupDate == $today){
                        ?>     
                            <li>
                                New Talents sign up (<a href="#"><?= $activity->first_name; ?></a>)
                                <span class="pull-right"><?= date(getTimeFormat('H:i A'), strtotime($activity->created_on ));?></span>
                            </li>
                        <?php 
                        }else{
                        ?>
                            <li>
                                New Talents sign up (<a href="#"><?= $activity->first_name; ?></a>)
                                <span class="pull-right"><?= date('Y-m-d',strtotime($activity->created_on ));?></span>
                            </li>
                        <?php
                        }
                    }
                } 

                if ($talent_booking) {
                    foreach ($talent_booking as $booking) { 
                        $bookingDate = (date('Y-m-d',strtotime($booking->paidDatetime )));
                        if($bookingDate == $today){
                        ?>     
                            <li>
                                Talent <?= $booking->first_name; ?> pay to book the <?= rawurldecode($booking->partner_name); ?>
                                <div style="padding-left: 5px;"><i class="fa fa-angle-right"></i> New Buskers Pod Booking (<a href="#"><?= rawurldecode($booking->partner_name); ?>,<?= $booking->first_name; ?></a>)</div>
                                <span class="pull-right"><?= date(getTimeFormat('H:i A'), strtotime($booking->start ));?></span>
                            </li>
                        <?php 
                        }else{
                        ?>
                            <li>
                                Talent <?= $booking->first_name; ?> pay to book the <?= rawurldecode($booking->partner_name); ?>
                                <div style="padding-left: 5px;"><i class="fa fa-angle-right"></i> New Buskers Pod Booking (<a href="#"><?= rawurldecode($booking->partner_name); ?>,<?= $booking->first_name; ?></a>)</div>
                                <span class="pull-right"><?= date('Y-m-d',strtotime($booking->start ));?></span>
                            </li>
                        <?php
                        }
                    }
                }

                if ($fans_activity) {
                    foreach ($fans_activity as $activity) {
                        $date = (date('Y-m-d',strtotime($activity->created_on )));
                        if($date == $today){
                        ?>     
                            <li>
                                New Fans Joined (<a href="#"><?= $activity->email; ?></a>)
                                <span class="pull-right"><?= date(getTimeFormat('H:i A'), strtotime($activity->created_on ));?></span>
                            </li>
                        <?php 
                        }else{
                        ?>
                            <li>
                                New Fans Joined (<a href="#"><?= $activity->email; ?></a>)
                                <span class="pull-right"><?= date('Y-m-d',strtotime($activity->created_on ));?></span>
                            </li>
                        <?php
                        }
                    }
                }
                if ($fansFeedback) {
                    foreach ($fansFeedback as $feedback) {
                        $date = (date('Y-m-d',strtotime($feedback->createdon )));
                        if($date == $today){
                        ?>     
                            <li>
                                New Feedback (<a href="#"><?= $feedback->subject; ?></a>)
                                <span class="pull-right"><?= date(getTimeFormat('H:i A'), strtotime($feedback->createdon ));?></span>
                            </li>
                        <?php 
                        }else{
                        ?>
                            <li>
                               New Feedback (<a href="#"><?= $feedback->subject; ?></a>)
                                <span class="pull-right"><?= date('Y-m-d',strtotime($feedback->createdon ));?></span>
                            </li>
                        <?php
                        }
                    }
                } 
                ?>
                </ul>
            </div> 
            <div class="box-footer text-center">
                <button class="btn-shadow btn-wide btn-pill btn btn-focus"> View All Messages</button>
            </div>
        </div>

        <div class="box box-primary"> 
            <div class="box-header with-border">
                <i class="fa fa-database" style="color: #81DAF5;"></i>
                <h3 class="box-title">Latest Pending Action</h3>
                <div class="box-tools pull-right">
                    <div class="btn-group"></div>
                </div>
            </div>
            <div class="box-body">
                <ul class="todo-list">
                    <li>
                        <span class="handle ui-sortable-handle">
                            <i class="fa fa-ellipsis-v" style="color: #0000FF;"></i>
                        </span>
                        <input type="checkbox" value="">
                        <?php $Feedback = ($fans_feedback  + $talent_feedback); ?>
                        <span class="text"><?=$Feedback;?> Feedback pending respond</span>
                        <small class="label label-danger"></small>
                    </li>
                    <li>
                        <span class="handle ui-sortable-handle">
                            <i class="fa fa-ellipsis-v" style="color: #CC2EFA;"></i>
                        </span>
                        <input type="checkbox" value="">
                        <span class="text"><?=$host_pending;?> new Talent pending approvel</span>
                        <small class="label label-info"></small>
                    </li>
                    <li>
                        <span class="handle ui-sortable-handle">
                            <i class="fa fa-ellipsis-v" style="color: #B40431;"></i>
                        </span>
                        <input type="checkbox" value="">
                        <span class="text"><?=$webRequest;?> new Website requests</span>
                        <small class="label label-warning"></small>
                        <div class="tool pull-right">
                            <span class="label label-success">Latest Request</span>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="box-footer text-center">
               <!-- <button class="btn btn-link pull-right" >Cancel</button>-->
            </div>
        </div>

    </div>
</div>
<!-- Chart ( 17 - 02 - 2020)-->
<script type="text/javascript" src="<?=$this->config->item( 'dir_url' );?>plugins/chart.js/js/Chart.js"></script>

<!-- Dashboard Chart-->
<script type="text/javascript">
function bookingHoursFilter(filter_type){

    if(filter_type){
        $.ajax({
            type: "POST",
            url: adminurl + 'dashboard/bookingHoursFilter',
            data: { 'filter_type':filter_type },
            success: function( html ) {
                $('#hours_content').html(html);
            }
        });
    }
}
function totalSalesFilter(filter_type){
    if(filter_type){
        $.ajax({
            type: "POST",
            url: adminurl + 'dashboard/totalSalesFilter',
            data: { 'filter_type':filter_type },
            success: function( html ) {
                $('#sales_content').html(html);
            }
        });
    }
}
    $(document).ready(function(){
        $('#today_filter').click(function(){
            var graph_type = $('#graph_type').val();
            if(graph_type == 2){
                $('#lineChart1Div').hide();
                $('#lineChart2Div').hide();
                $('#lineChart3Div').show();
                $('#barChart1Div').hide();
                $('#barChart2Div').hide();
                $('#barChart3Div').hide();
            }else{
                $('#barChart1Div').hide();
                $('#barChart2Div').hide();
                $('#barChart3Div').show();
                $('#lineChart1Div').hide();
                $('#lineChart2Div').hide();
                $('#lineChart3Div').hide();
            }
            bookingHoursFilter(this.value);
            totalSalesFilter(this.value);
        });
        $('#month_filter').click(function(){
            var graph_type = $('#graph_type').val();
            if(graph_type == 2){
                $('#lineChart1Div').hide();
                $('#lineChart2Div').show();
                $('#lineChart3Div').hide();
                $('#barChart1Div').hide();
                $('#barChart2Div').hide();
                $('#barChart3Div').hide();
            }else{ 
                $('#barChart1Div').hide();
                $('#barChart2Div').show();
                $('#barChart3Div').hide();
                $('#lineChart1Div').hide();
                $('#lineChart2Div').hide();
                $('#lineChart3Div').hide();
            }
            bookingHoursFilter(this.value);
            totalSalesFilter(this.value);
        });
        $('#year_filter').click(function(){
            var graph_type = $('#graph_type').val();
            if(graph_type == 2){
                $('#lineChart1Div').show();
                $('#lineChart2Div').hide();
                $('#lineChart3Div').hide();
                $('#barChart1Div').hide();
                $('#barChart2Div').hide();
                $('#barChart3Div').hide();
            }else{
                $('#barChart1Div').show();
                $('#barChart2Div').hide();
                $('#barChart3Div').hide();
                $('#lineChart1Div').hide();
                $('#lineChart2Div').hide();
                $('#lineChart3Div').hide();
            }
            bookingHoursFilter(this.value);
            totalSalesFilter(this.value);
        });

        $('#graph_type').on('change', function() {
            //alert($("input[name=performance_filter]:checked").val());
            

            if(this.value == 1){
                $('#barChart1Div').show();
                $('#barChart2Div').hide();
                $('#barChart3Div').hide();
                $('#lineChart1Div').hide();
                $('#lineChart2Div').hide();
                $('#lineChart3Div').hide();
            }
            if(this.value == 2){
                $('#lineChart1Div').show();
                $('#lineChart2Div').hide();
                $('#lineChart3Div').hide();
                $('#barChart1Div').hide();
                $('#barChart2Div').hide();
                $('#barChart3Div').hide();
            }
        });
    });
    
    $(function () {

        var chart1_label = <?php echo json_encode($chart1_label) ?>;
        var chart2_label = <?php echo json_encode($chart2_label) ?>;
        var chart1_data  = <?php echo json_encode($chart1_data) ?>;
        var chart2_data  = <?php echo json_encode($chart2_data) ?>;
        var barChartDaydata    = <?php echo json_encode($barChartDaydata) ?>;
        var lineChartDaydata   = <?php echo json_encode($lineChartDaydata) ?>;
        var barChartMonthdata  = <?php echo json_encode($barChartMonthdata) ?>;
        var lineChartMonthdata = <?php echo json_encode($lineChartMonthdata) ?>;
        
        //-------------
        //- BAR CHART -
        //-------------

        // Bar chart yearly
        new Chart(document.getElementById("barChart"), {
            type: 'bar',
            data: {
              labels: chart1_label,
              datasets: [
                {
                  label: "Total Bookings",
                  backgroundColor: '#3e95cd',
                  //backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850","#6610f2","#3c8dbc","#001f3f","#01ff70","#ffc107","#3d9970","#f012be"],
                  borderColor: '#71B37C',
                  data: chart1_data
                }
              ]
            },
            options: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Hours of Bookings '
                }
            }
        });
        // Bar chart Day
        new Chart(document.getElementById("barChart_D"), {
            type: 'bar',
            data: {
              labels: ['Today'],
              datasets: [
                {
                  label: "Today Bookings",
                  backgroundColor: '#3e95cd',
                  //backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850","#6610f2","#3c8dbc","#001f3f","#01ff70","#ffc107","#3d9970","#f012be"],
                  borderColor: '#71B37C',
                  data: barChartDaydata
                }
              ]
            },
            options: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Today Bookings'
                }
            }
        });
        // Bar chart monthly
        new Chart(document.getElementById("barChart_M"), {
            type: 'bar',
            data: {
              labels: chart2_label,
              datasets: [
                {
                  label: "Monthly Bookings",
                  backgroundColor: '#3e95cd',
                  //backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850","#6610f2","#3c8dbc","#001f3f","#01ff70","#ffc107","#3d9970","#f012be"],
                  borderColor: '#71B37C',
                  data: barChartMonthdata
                }
              ]
            },
            options: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Current Month Bookings'
                }
            }
        });
        //Line chart yearly
        var graph = new Chart(document.getElementById("lineChart"), {
            type: 'line',
            data: {
                labels: chart1_label,
                datasets: [{
                    label: "Sales",
                    data: chart2_data,
                    fill: false,
                    borderColor: '#007bff',
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            // Include a dollar sign in the ticks
                            callback: function(value, index, values) {
                                return 'RM ' + value;
                            }
                        }
                    }]
                },
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Sale'
                }
            }
        });
        //Line chart day
        var graph = new Chart(document.getElementById("lineChart_D"), {
            type: 'line',
            data: {
                labels: ['Today Sales'],
                datasets: [{
                    label: "Sales Today",
                    data: lineChartDaydata,
                    fill: false,
                    borderColor: '#007bff',
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            // Include a dollar sign in the ticks
                            callback: function(value, index, values) {
                                return 'RM ' + value;
                            }
                        }
                    }]
                },
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Sale Today'
                }
            }
        });
        //Line chart monthly
        var graph = new Chart(document.getElementById("lineChart_M"), {
            type: 'line',
            data: {
                labels: chart2_label,
                datasets: [{
                    label: "Current Month Sales",
                    data: lineChartMonthdata,
                    fill: false,
                    borderColor: '#007bff',
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            // Include a dollar sign in the ticks
                            callback: function(value, index, values) {
                                return 'RM ' + value;
                            }
                        }
                    }]
                },
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Current Month Sales'
                }
            }
        });
    });

$(document).ready(function() {
    var ctx = document.getElementById('update-chart-1').getContext("2d");
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: valincome('#fff', [25, 30, 20, 15, 20], '#fff'),
        options: valincomebuildoption(),
    });
    var ctx = document.getElementById('update-chart-2').getContext("2d");
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: valincome('#fff', [25, 10, 20, 15, 20], '#fff'),
        options: valincomebuildoption(),
    });
    var ctx = document.getElementById('update-chart-3').getContext("2d");
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: valincome('#fff', [10, 30, 20, 15, 30], '#fff'),
        options: valincomebuildoption(),
    });
});
function valincome(a, b, f) {
    if (f == null) {
        f = "rgba(0,0,0,0)";
    }
    return {
        labels: ["1", "2", "3", "4", "5"],
        datasets: [{
            label: "",
            borderColor: a,
            borderWidth: 0,
            hitRadius: 30,
            pointRadius: 0,
            pointHoverRadius: 4,
            pointBorderWidth: 2,
            pointHoverBorderWidth: 12,
            pointBackgroundColor: Chart.helpers.color("#000000").alpha(0).rgbString(),
            pointBorderColor: a,
            pointHoverBackgroundColor: a,
            pointHoverBorderColor: Chart.helpers.color("#000000").alpha(.1).rgbString(),
            fill: true,
            backgroundColor: Chart.helpers.color(f).alpha(1).rgbString(),
            data: b,
        }]
    };
}

function valincomebuildoption() {
    return {
        maintainAspectRatio: false,
        title: {
            display: false,
        },
        tooltips: {
            enabled: false,
        },
        legend: {
            display: false
        },
        hover: {
            mode: 'index'
        },
        scales: {
            xAxes: [{
                display: false,
                gridLines: false,
                scaleLabel: {
                    display: true,
                    labelString: 'Month'
                }
            }],
            yAxes: [{
                display: false,
                gridLines: false,
                scaleLabel: {
                    display: true,
                    labelString: 'Value'
                },
                ticks: {
                    min: 1,
                }
            }]
        },
        elements: {
            point: {
                radius: 4,
                borderWidth: 12 
            }
        },
        layout: {
            padding: {
                left: 10,
                right: 0,
                top: 15,
                bottom: 0
            }
        }
    };
}
</script>
<!-- Booking Activities Tabel -->
<script type="text/javascript">
$(document).ready(function() {
    table = $('#talent_list').DataTable({
      language: {
        processing: "<img src='<?php echo $dirUrl;?>img/loading.gif'>",
      },
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "order": [], //Initial no order.
      'autoWidth': false,
      "ajax": {
          "data": function(d) {
            d.talent_filter = $('input:radio[name=talent_filter]:checked').val();
          },
          "url": "<?php echo site_url('/dashboard/completedtripTableResponse')?>",
          "type": "POST"
      },
      "dom": "B lrt<'row' <'col-sm-5' i><'col-sm-7' p>>",
      "lengthMenu": [[5, 25, 50, 100, 1000], [5, 25, 50, 100, 1000]],
      //Set column definition initialisation properties.
      "columnDefs": [{
          "targets": [0],
          "orderable": false, //set not orderable
      },
      {
          "targets": [0],
          "searchable": false, //set orderable
      }]
  });
    var guider_lists      = <?php echo json_encode($guider_lists) ?>;
    var i = 0;
    $('#talent_list tfoot th').each( function () {
        var title = $(this).text();
        if( i == 1 ){
            var invet_selectbox = '<select name="guider_id" id="guider_id" class="form-control">'
                                +'<option value="">Filter by talents</option>';
            $.each(guider_lists, function (i, elem) {
                if(elem['first_name']){
                    invet_selectbox += '<option value="'+ $.trim(elem['email']) +'">'+ elem['first_name'] +'</option>';
                }
            });
            invet_selectbox += '</select>';
            $(this).html( invet_selectbox );
        }
        i++;
    });

    // DataTable
    var table = $('#talent_list').DataTable();
    // Apply the search
    table.columns().every( function () {
        var that = this;
        $( 'input', this.footer() ).on( 'keyup change', function () {
          if ( that.search() !== this.value ) {
            that
              .search( this.value )
              .draw();
          }
        });
        $( 'select', this.footer() ).on( 'change', function () {
          if ( that.search() !== this.value ) {
            that
              .search( this.value )
              .draw();
          }
        });
    });
    $('.talent_filter').on('click', function(){
        table.draw();
    });

    $(".filter-box input[name='talent_filter']").click(function(){
        if($('input:radio[name=talent_filter]:checked').val() == 1){
            $('#filter_by_name').html('<?= $filter_t1; ?>');
        }else if($('input:radio[name=talent_filter]:checked').val() == 2){
            $('#filter_by_name').html('<?= $filter_t2; ?>');
        }else if($('input:radio[name=talent_filter]:checked').val() == 3){
            $('#filter_by_name').html('<?= $filter_t3; ?>');
        }
    });
});
</script>
<!-- Chart Js  -->


