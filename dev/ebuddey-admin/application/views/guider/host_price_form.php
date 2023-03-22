<?php 
$dirUrl = $this->config->item( 'dir_url' );
$upload_path_url = $this->config->item( 'upload_path_url' );
if($activityInfo){
  $price_type_id        = $activityInfo->price_type_id;
  $processingFeesType   = $activityInfo->processingFeesType;
  $processingFeesValue  = $activityInfo->processingFeesValue;
}else{
  $price_type_id        = '';
  $processingFeesType   = '';
  $processingFeesValue  = '';
}
if($processingFeesType == 2){
  $fixed_rate_value = '';
  $percentage_value = $processingFeesValue;
  $disabled1 = '';
  $disabled2 = 'disabled';
}elseif ($processingFeesType == 3) {
  $fixed_rate_value = $processingFeesValue;
  $percentage_value = '';
  $disabled1 = 'disabled';
  $disabled2 = '';
}else{
  $fixed_rate_value = '';
  $percentage_value = '';
  $disabled1 = '';
  $disabled2 = '';
}

?>
<div class="row">
  <form novalidate="" id="activityPriceSettingForm" class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
    <div class="box-body">
      <!-- <div class="form-group">
        <div class="col-md-12">
          <div class="checkbox">
            <label>
              <input type="checkbox" name="default_fees" id="default_fees" value="1">
              Default Processing Fees 
              <span style="padding-left: 20px;"class="control-label text-success" for="inputSuccess"><i class="fa fa-check"></i> 5% per Activity or minimum 2RM per Activity</span>
            </label>
          </div>
        </div>
      </div> -->
      <div class="form-group" id="processingFeesDiv">
        <div class="col-md-6">
          <label class="control-label" for="processingFeesType">Processing Fee <span class="text-danger">*</span></label>
          <select class="form-control" name="processingFeesType" id="processingFeesType">
            <option <?php if($processingFeesType == 2){ echo 'selected'; } ?> value="2">Percentage</option>
            <option <?php if($processingFeesType == 3){ echo 'selected'; } ?> value="3">Fixed Price</option>
          </select>
        </div>
        <div class="col-md-3 col-nopadding-l">
          <div class="text-center"><label class="control-label" for="percentage_value"><b>%</b></label></div>
          <input type="text" name="percentage_value" <?= $disabled1; ?> maxlength="2" id="percentage_value" value="<?=$percentage_value;?>" class="form-control number text-center" />
        </div>
        <div class="col-md-3 col-nopadding-l">
          <div class="text-center"><label class="control-label" for="fixed_rate_value">$</label></div>
          <input type="text" name="fixed_rate_value" <?= $disabled2; ?> maxlength="3" id="fixed_rate_value" value="<?=$fixed_rate_value;?>" class="form-control number text-center" />
        </div>
      </div>
      <div>
        <input type="hidden" name="price_value" id="price_value" value="1">
        <input type="hidden" name="activity_id" id="activity_id" value="<?=$activity_id;?>">
        <a href="javascript:;" class="btn btn-success btn-sm" onclick="return updateProcessingFee();">Update</a>
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-hidden="true">Cancel</button>
      </div>
    </div>
  </form>
</div>
<script type="text/javascript">
$(document).ready(function(){
  $('#default_fees').change(function() {
    if($(this).is(":checked")) {
      $("#processingFeesDiv").hide();
    }else{
      $("#processingFeesDiv").show();
    }
  });
  $('.number').keypress(function(event) {
    if (event.which == 8 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46) {
      return true;
    }else if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
      event.preventDefault();
    }
  });
  $('#processingFeesType').on('change', function() {
    if(this.value == 2){
      $("#fixed_rate_value").prop('disabled', true);
      $("#percentage_value").prop('disabled', false);
    }else{
      $("#percentage_value").prop('disabled', true);
      $("#fixed_rate_value").prop('disabled', false);
    }
  });
  $('#price_method').on('change', function() {
    var rate_per_person = $( '#updateGuiderActivityForm #rate_per_person' ).val();
    var activity_id = $('#activity_id').val();
    if(this.value == 1){
      if(activity_id == 0){
        $( '#updateGuiderActivityForm #rate_per_person' ).val(<?= DEFAULT_FIXED; ?>);
      }
    }else{
      if(activity_id == 0){
        $( '#updateGuiderActivityForm #rate_per_person' ).val(<?= DEFAULT_PERCENTAGE; ?>);
      }
    }
  });
});
function updateProcessingFee() {
  var data             = $( 'form#activityPriceSettingForm' ).serialize();
  var processingType   = $( '#activityPriceSettingForm #processingFeesType' ).val();
  var percentage_value = $( '#activityPriceSettingForm #percentage_value' ).val();
  var fixed_rate_value = $( '#activityPriceSettingForm #fixed_rate_value' ).val();
  if(processingType == ''){
    toastr.error( 'Please select Processing fees Type.','Error' );
    return false;
  }
  if(processingType == 2){
    if(!$.isNumeric(percentage_value)){
      toastr.error( 'Invalid percentage value','Error' );
      return false;
    }
    if (percentage_value < 5 || percentage_value > 40) {
        var errmsg = 'Minimum percentage 5 and maximum 40';
        toastr.error( errmsg,'Error' );
        return false;
    }
  }
  if(processingType == 3){
    if(!$.isNumeric(fixed_rate_value)){
      toastr.error( 'Invalid fixed price','Error' );
      return false;
    }
    if (fixed_rate_value < 2) {
        var errmsg = 'Minimum fixed price 2';
        toastr.error( errmsg,'Error' );
        return false;
    }
  }
  $( '#myModal .modal-title' ).html( 'Confirm' );
  $( '#myModal .modal-body' ).html( 'Are you sure want to Save this Setting ?' );
  $( '#myModal .modal-body' ).append( '<br /><br /><button class="btn btn-info btn-sm" id="continuemodal" data-dismiss="modal">Yes</button>&nbsp;&nbsp;<button aria-hidden="true" data-dismiss="modal" class="btn btn-danger btn-sm">Cancel</button>' );
  $( '#myModal' ).modal({ backdrop: 'static', keyboard: false }); 
  $( "#continuemodal" ).click(function() {
  $.ajax({
      type: "POST",
      url: adminurl + 'guider/updateProcessingFee',
      data: data,
      success: function( data ) {
          if(data == 1){
              toastr.success( 'Activity pricing setting Updated Successfully.','Success' );
          }else{
              toastr.error( 'Some problem found Please try again.','Error' );
          }
          setTimeout( function() {
          location.reload();
          }, 2000 );
      }
      });
  }); 
}
</script>