<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl   = $this->config->item( 'admin_url' );
$site_name  = $this->config->item( 'site_name' );
$dirUrl     = $this->config->item( 'dir_url' );

$guider_charged = $serviceInfo->guider_charged;
//$totalPassenger = $serviceInfo->number_of_person;
//$processing_fee = $serviceInfo->current_processing_fee;
//$subTotal       = $totalPassenger * $guider_charged;
//$ProcessingFees = ($processing_fee / 100) * $subTotal;
$ServiceFees    = $serviceInfo->service_fees;
$totalAmount    = $serviceInfo->transaction_amount;
$subTotal       = $serviceInfo->sub_total;
if ($serviceInfo->pay_status == 0) {
    $status     = 'Failure';
}elseif($serviceInfo->pay_status == 1){
    $status     = 'Success';
}elseif($serviceInfo->pay_status == 2){
    $status     = 'Initiated';
}elseif($serviceInfo->pay_status == 3){
    $status     = 'Cancelled';
}elseif($serviceInfo->pay_status == 4){
    $status     = 'Pending';
}
$regionInfo     = $this->Guidermodel->stateInfoByid($serviceInfo->service_region_id);
if($regionInfo){
    $regionName = $regionInfo->name;
}else{
    $regionName = '';
}
if($serviceInfo->service_price_type_id == 1){
  $price_type = 'Per Person';
}elseif ($serviceInfo->service_price_type_id == 2) {
  $price_type = 'Per Booking';
}else{
  $price_type = 'N/A';
}
?>
<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"></h3>
        <div class="box-tools pull-right">
          <a href="<?php echo $assetUrl; ?>senangpay_transaction" class="btn btn-sm btn-primary">Back</a>
        </div>
      </div>
      <div class="box-body">
        <div class="col-md-12">
          <div class="col-md-4">
            <div class="form-group">
              <label class="control-label">Transaction Ref ID</label>
              <div><?=$serviceInfo->order_id; ?></div>
            </div>
            <div class="form-group">
              <label class="control-label">Sub Total</label>
              <div><?=$serviceInfo->sub_total; ?></div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label class="control-label">Transaction Amount (<?=$serviceInfo->guider_currency_symbol; ?>)</label>
              <div><?=$serviceInfo->transaction_amount; ?></div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label class="control-label">Percentage Amount</label>
              <div><?=$ServiceFees; ?></div>
            </div>
            <div class="form-group">
              <label class="control-label">Status</label>
              <div><?=$status; ?></div>
            </div>
          </div>
        </div>
        <div class="clearfix" style="border-bottom: 1px solid #d2d6de;margin-bottom: 5px;"></div>
        <div class="col-md-12">
          <div class="col-md-4">
            <div class="form-group">
              <label class="control-label"><?= GUEST_NAME; ?> Name</label>
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
              <label class="control-label"><?= HOST_NAME; ?> Name</label>
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
              <label class="control-label">Total Hours</label>
              <div><?=$serviceInfo->total_hours; ?></div>
            </div>
            <div class="form-group">
              <label class="control-label">Amount Charged</label>
              <div><?=$serviceInfo->guider_charged; ?></div>
            </div>
            <div class="form-group">
              <label class="control-label">Transaction ID</label>
              <div><?=$serviceInfo->transactionID; ?></div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label class="control-label"><?= HOST_NAME; ?> Supporting location</label>
              <div><?=$regionName; ?></div>
            </div>
            <div class="form-group">
              <label class="control-label"><?= HOST_NAME; ?> Charges (<?= $price_type; ?>)(<?=$serviceInfo->guider_currency_symbol; ?>)</label>
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