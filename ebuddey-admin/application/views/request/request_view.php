<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl   = $this->config->item( 'admin_url' );
$site_name  = $this->config->item( 'site_name' );
$dirUrl     = $this->config->item( 'dir_url' );

$guider_charged = $serviceInfo->guider_charged;
$totalPassenger = $serviceInfo->number_of_person;
$processing_fee = $serviceInfo->current_processing_fee;
//$ProcessingFees = ($processing_fee / 100) * $subTotal;
$regionInfo     = $this->Guidermodel->stateInfoByid($serviceInfo->service_region_id);
if($regionInfo){
  $regionName   = $regionInfo->name;
}else{
  $regionName   = '';
}
if($serviceInfo->service_price_type_id == 1){
  $price_type   = 'Per Person';
  $subTotal     = $totalPassenger * $guider_charged;
}elseif ($serviceInfo->service_price_type_id == 2) {
  $price_type   = 'Per Booking';
  $subTotal     = $guider_charged;
}else{
  $price_type   = 'N/A';
  $subTotal     = 0;
}
$totalAmount    = $subTotal;
//CALCULATE SERVICE FEE
$ServiceFees    = (PROCESSING_FEE / 100) * $totalAmount;
if($ServiceFees < 2){ $ServiceFees = 02.00; }
?>
<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"></h3>
        <div class="box-tools pull-right">
          <a href="<?php echo $assetUrl; ?>pendingtrip" class="btn btn-sm btn-primary">Back</a>
        </div>
      </div>
      <div class="box-body">
        <div class="col-md-12">
          <div class="col-md-4">
            <div class="form-group">
              <label class="control-label">Guest Name</label>
              <div><?=$serviceInfo->requestorName; ?></div>
            </div>
            <div class="form-group">
              <label class="control-label">Trip ID</label>
              <div><?= bookingTicketFormat($serviceInfo->service_id); ?></div>
            </div>
            <div class="form-group">
              <label class="control-label">No.of people</label>
              <div><?=$serviceInfo->number_of_person; ?></div>
            </div>
            <div class="form-group">
              <label class="control-label">Booking Created Date</label>
              <div><?=date(getDateFormat(), strtotime($serviceInfo->createdon)) .' '.date(getTimeFormat(), strtotime($serviceInfo->createdon)); ?></div>
            </div>
            <div class="form-group">
              <label class="control-label">Meeting Time</label>
              <div><?=$serviceInfo->pickup_time; ?></div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label class="control-label">Guider Name</label>
              <div><?=$serviceInfo->guiderName; ?></div>
            </div>
            <div class="form-group">
              <label class="control-label">Meeting Date</label>
              <div><?=date(getDateFormat(), strtotime($serviceInfo->service_date)); ?></div>
            </div>
            <div class="form-group">
              <label class="control-label">Meeting Date</label>
              <div><?=date(getTimeFormat(), strtotime($serviceInfo->pickup_time)); ?></div>
            </div>
            <div class="form-group">
              <label class="control-label">Amount Charged</label>
              <div><?=$serviceInfo->guider_charged; ?></div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label class="control-label">Guider Supporting location</label>
              <div><?=$regionName; ?></div>
            </div>
            <div class="form-group">
              <label class="control-label">Guider Charges (<?= $price_type; ?>)(<?=$serviceInfo->guider_currency_symbol; ?>)</label>
              <div><?= number_format((float)$guider_charged, 2, '.', ''); ?></div>
            </div>
            <div class="form-group">
              <label class="control-label">Sub total(<?=$serviceInfo->guider_currency_symbol; ?>)</label>
              <div><?=number_format((float)$subTotal, 2, '.', ''); ?></div>
            </div>
            <div class="form-group">
              <label class="control-label">Processing Fees(<?=$serviceInfo->guider_currency_symbol; ?>)</label>
              <div><?=number_format((float)$ServiceFees, 2, '.', ''); ?></div>
            </div>
            <div class="form-group">
              <label class="control-label">Total Amount(<?=$serviceInfo->guider_currency_symbol; ?>)</label>
              <div><?=number_format((float)$totalAmount, 2, '.', ''); ?></div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <label class="control-label">Customer Feedback</label>
              <div><?=$serviceInfo->feedback; ?></div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <label class="control-label">Additional Information</label>
              <div><?=$serviceInfo->additional_information; ?></div>
            </div>
          </div>
        </div>
        <div class="clearfix"></div>
    </div>
  </div>
</div>