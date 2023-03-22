<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl       = $this->config->item( 'admin_url' );
$site_name      = $this->config->item( 'site_name' );

if($allPayout){
    $transactionAmt = $allPayout->transactionAmt;
    $payoutAmt      = $allPayout->guiderAmt;
    $percentageAmt  = $allPayout->serviceAmt;
}else{
    $transactionAmt = '';
    $payoutAmt      = '';
    $percentageAmt  = '';
}
?>
<style type="text/css">
.navbar{ display: contents; }
</style>
<div class="page-body">
    <div class="row">
        <!-- task, page, download counter  start -->
        <div class="col-xl-3 col-md-3">
            <div class="card bg-c-yellow update-card">
                <div class="card-block">
                    <div class="row align-items-end">
                        <div class="col-8">
                            <h3 class="text-white">$30200</h3>
                            <h5 class="text-white m-b-0">All Earnings</h5>
                        </div>
                        <div class="col-4 text-right">
                            <canvas id="update-chart-1" height="50"></canvas>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <p class="text-white m-b-0"><i class="fa fa-clock-o"></i> update : 2:15 am</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-3">
            <div class="card bg-c-green update-card">
                <div class="card-block">
                    <div class="row align-items-end">
                        <div class="col-8">
                            <h3 class="text-white">290+</h3>
                            <h5 class="text-white m-b-0">Page Views</h5>
                        </div>
                        <div class="col-4 text-right">
                            <canvas id="update-chart-2" height="50"></canvas>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <p class="text-white m-b-0"><i class="fa fa-clock-o"></i> update : 2:15 am</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-3">
            <div class="card bg-c-pink update-card">
                <div class="card-block">
                    <div class="row align-items-end">
                        <div class="col-8">
                            <h3 class="text-white">145</h3>
                            <h5 class="text-white m-b-0">Task Completed</h5>
                        </div>
                        <div class="col-4 text-right">
                            <canvas id="update-chart-3" height="50"></canvas>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <p class="text-white m-b-0"><i class="fa fa-clock-o"></i> update : 2:15 am</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-3">
            <div class="card bg-c-lite-green update-card">
                <div class="card-block">
                    <div class="row align-items-end">
                        <div class="col-8">
                            <h3 class="text-white">500</h3>
                            <h5 class="text-white m-b-0">Downloads</h5>
                        </div>
                        <div class="col-4 text-right">
                            <canvas id="update-chart-4" height="50"></canvas>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <p class="text-white m-b-0"><i class="fa fa-clock-o"></i> update : 2:15 am</p>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" type="text/css" href="<?=$this->config->item( 'dir_url' );?>css/bootstrap.min111.css">
<style type="text/css">
.text-white {
    color: #fff!important;
}
.align-items-end {
    -ms-flex-align: end!important;
    align-items: flex-end!important;
}
.update-card {
    color: #fff;
}
.update-card .card-footer {
    background-color: transparent;
    border-top: 1px solid #fff;
}
.bg-c-yellow {
    background: -webkit-gradient(linear, left top, right top, from(#fe9365), to(#feb798));
    background: linear-gradient(to right, #fe9365, #feb798);
}
.bg-c-green {
    background: -webkit-gradient(linear, left top, right top, from(#0ac282), to(#0df3a3));
    background: linear-gradient(to right, #0ac282, #0df3a3);
}
.bg-c-pink {
    background: -webkit-gradient(linear, left top, right top, from(#eb3422), to(#ef5f51));
    background: linear-gradient(to right, #eb3422, #ef5f51);
}
.bg-c-lite-green {
    background: -webkit-gradient(linear, left top, right top, from(#01a9ac), to(#01dbdf));
    background: linear-gradient(to right, #01a9ac, #01dbdf);
}
.card {
    border-radius: 5px;
    -webkit-box-shadow: 0 1px 20px 0 rgba(69,90,100,0.08);
    box-shadow: 0 1px 20px 0 rgba(69,90,100,0.08);
    border: none;
    margin-bottom: 30px;
}
.card-block {
    padding: 1.25rem;
}
.m-b-0 {
    margin-bottom: 0px;
}
</style>

<!-- Chart js -->
<script type="text/javascript" src="<?=$this->config->item( 'dir_url' );?>js/chart.js/js/Chart.js"></script>
<script type="text/javascript">
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
        data: valincome('#fff', [10, 30, 20, 15, 30], '#fff'),
        options: valincomebuildoption(),
    });
    var ctx = document.getElementById('update-chart-3').getContext("2d");
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: valincome('#fff', [25, 10, 20, 15, 20], '#fff'),
        options: valincomebuildoption(),
    });
    var ctx = document.getElementById('update-chart-4').getContext("2d");
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: valincome('#fff', [25, 30, 20, 15, 10], '#fff'),
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


<div class="row">
    <div class="col-md-12">
        <?php
        $attributes = array('method' => 'post', 'id' => 'searchform');
        echo form_open($assetUrl.'dashboard', $attributes);
        /*$service_search  = $this->input->get('service_search');
        $order_by        = $this->input->get('order_by');*/
        ?>
        <!-- Search Name -->
        <div class="form-group">
          <div style="margin-bottom: 10px;padding-left: 0px;" class="search_input col-md-6">
            <label>Date Range</label>
            <div class="input-group form-search">
              <input type="text" style="width:100%;" placeholder="Select date range" class="form-control" id="date" name="date">  
              <span class="input-group-btn">
                <button data-type="last" class="btn btn-black" type="submit">Search</button>
              </span>
            </div>
          </div>
        </div>
        <?php echo form_close(); ?>
        <div class="clearfix"></div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3><?=$host_total;?></h3>
                <p>Total <?= HOST_NAME; ?> Registered</p>
            </div>
            <div class="icon">
                <i class="ion ion-ios-people-outline"></i>
            </div>
            <a href="<?=$assetUrl; ?>guider" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3><?=$guest_total;?></h3>
                <p>Total <?= GUEST_NAME; ?> Registered</p>
            </div>
            <div class="icon">
                <i class="ion ion-ios-people-outline"></i>
            </div>
            <a href="<?=$assetUrl; ?>traveller" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3><?=$host_pending;?></h3>
                <p><?= HOST_NAME; ?> Pending Approval</p>
            </div>
            <div class="icon">
                <i class="ion ion-ios-people-outline"></i>
            </div>
            <a href="<?=$assetUrl; ?>guider" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-blue">
            <div class="inner">
                <h3><?= $activebooking;?></h3>
                <p>Total Activity Bookings</p>
            </div>
            <div class="icon">
                <i class="ion ion-ios-people-outline"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3><?= ($payoutAmt)? CURRENCYCODE.' '.number_format( $payoutAmt, 2 ) : 'n/a'; ?></h3>
                <p>Total <?= HOST_NAME; ?> Earnings</p>
            </div>
            <div class="icon">
                <i class="ion ion-ios-people-outline"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3><?= ($transactionAmt)? CURRENCYCODE.' '.number_format( $transactionAmt, 2 ) : 'n/a'; ?></h3>
                <p>Total Payments</p>
            </div>
            <div class="icon">
                <i class="ion ion-ios-people-outline"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3><?= ($percentageAmt)? CURRENCYCODE.' '.number_format( $percentageAmt, 2 ) : 'n/a'; ?></h3>
                <p>Total Processing Fee</p>
            </div>
            <div class="icon">
                <i class="ion ion-ios-people-outline"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>
<script type="text/javascript">
    $( document ).ready( function() {
        $( '#date').daterangepicker({});
    });
</script>