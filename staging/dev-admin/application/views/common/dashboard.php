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