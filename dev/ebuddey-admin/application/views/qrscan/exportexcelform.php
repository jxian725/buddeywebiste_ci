<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$dirUrl     = $this->config->item( 'dir_url' );
$assetUrl   = $this->config->item( 'admin_url' );
?>
<link rel="stylesheet" href="<?php echo $dirUrl; ?>plugins/bootstrap-datepicker/bootstrap-datepicker.min.css">
<script src="<?php echo $dirUrl; ?>plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="<?= $dirUrl; ?>plugins/select2/select2.min.css">
<script src="<?= $dirUrl; ?>plugins/select2/select2.full.min.js"></script>
<div class="row">
  <form novalidate="" action="<?= $assetUrl; ?>qrscandonate/bookings_export" id="exportexcelform" role="form" method="get">
    <div class="col-md-12">
      <div class="form-group">
        <label for="guider_id">Select Host Name</label>
        <select name="guider_id" id="guider_id" class="form-control select2">
          <option value="">Select</option>
          <?php
          if($guider_lists){
            foreach ($guider_lists as $key => $value) {
              $title = $value->first_name. ' '.$value->last_name.'('.$value->phone_number.')';
              ?>
              <option value="<?php echo $value->guider_id ?>"><?php echo $title; ?></option>
              <?php
            }
          }
          ?>
        </select>
      </div>
  </div>
  <div class="col-md-12">
      <div class="form-group">
        <div><label for="start_date">Date Filter</label></div>
        <div class="row">
          <div class="col-md-6">
            <input type="text" name="start_date" id="start_date" placeholder="Start Date" class="form-control datepicker3">
          </div>
          <div class="col-md-6">
            <input type="text" name="end_date" id="end_date" placeholder="End Date" class="form-control datepicker3">
          </div>
        </div>
      </div>
  </div>
  <div class="col-md-12">
      <div class="form-group">
        <label for="activity_id">User ID</label>
        <input type="text" name="user_random_id" id="user_random_id" class="form-control">
      </div>
  </div>
  <div class="col-md-12">
    <a href="javascript:;" class="btn btn-success btn-sm" onclick="return submitExportExcel();">Confirm Export</a>
  </div>
</form>
</div>
<script type="text/javascript">
$(document).ready(function(){
  $('.select2').select2();
});
$( ".datepicker3" ).datepicker({
    format: 'dd-mm-yyyy',
    maxDate: 0,
    endDate: '+0d',
    autoclose: true,
    orientation: 'auto'
});
function submitExportExcel() {
  var data    = $( 'form#exportexcelform' ).serialize();
  var field   = $( 'form#exportexcelform #user_random_id' ).val();
  if(field == ''){
    toastr.error( 'Please enter ID.','Error' );
    return false;
  }else{
    $( '#exportexcelform' ).submit();
    $( '#myModal' ).modal( 'hide' );
  }
  $( '#myModal .modal-body' ).html('<img src="<?=$dirUrl; ?>img/ajax-loader.gif" id="gif" style="display: block; margin: 0 auto; width: 100px; visibility:visible;">');
}
</script>