<?php 
$dirUrl = $this->config->item( 'dir_url' );
$upload_path_url = $this->config->item( 'upload_path_url' );
if($activityInfo){
  $cancellation_policy  = $activityInfo->cancellation_policy;
  $what_i_offer         = $activityInfo->what_i_offer;
  $service_region       = $activityInfo->service_providing_region;
  $categoryIDs          = explode(',',$activityInfo->guiding_speciality);
  $guider_id            = $activityInfo->activity_guider_id;
  $price_type_id        = $activityInfo->price_type_id;
  $processingFeesType   = $activityInfo->processingFeesType;
  $rate_per_person      = $activityInfo->rate_per_person;
  $photo_1              = $activityInfo->photo_1;
  $photo_2              = $activityInfo->photo_2;
  $photo_3              = $activityInfo->photo_3;
  $additional_info_label= $activityInfo->additional_info_label;
  $maximum_booking      = $activityInfo->maximum_booking;
  $date_time_needed     = $activityInfo->date_time_needed;
}else{
  $cancellation_policy  = '';
  $what_i_offer         = '';
  $service_region       = '';
  $categoryIDs          = '';
  $price_type_id        = '';
  $processingFeesType   = 0;
  $rate_per_person      = '';
  $photo_1              = '';
  $photo_2              = '';
  $photo_3              = '';
  $additional_info_label= 'Additional Information';
  $maximum_booking      = '';
  $date_time_needed     = 1;
}
if($additional_info_label == ''){
  $additional_info_label= 'Additional Information';
}
?>
<link rel="stylesheet" href="<?= $dirUrl; ?>plugins/select2/select2.min.css">
<script src="<?= $dirUrl; ?>plugins/select2/select2.full.min.js"></script>
<div class="row">
  <form novalidate="" id="updateGuiderActivityForm" class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
    <div class="box-body">
      <div class="form-group">
        <div class="col-md-6">
          <label class="control-label" for="price_type_id">Pricing Type <span class="text-danger">*</span></label>
          <select class="form-control" name="price_type_id" id="price_type_id">
            <option <?php if($price_type_id == 1){ echo 'selected'; } ?> value="1">Pricing Per Person</option>
            <option <?php if($price_type_id == 2){ echo 'selected'; } ?> value="2">Pricing Per Booking</option>
            <option <?php if($price_type_id == 3){ echo 'selected'; } ?> value="3">Free Booking</option>
          </select>
        </div>
        <div class="col-md-6">
          <label class="control-label" for="rate_per_person">Price</label>
          <input type="text" name="rate_per_person" id="rate_per_person" value="<?=$rate_per_person;?>" class="form-control number" />
        </div>
        <?php
        if(!$activity_id || $processingFeesType == 1){ ?>
        <div class="col-md-12">
          <span class="control-label text-success" for="inputSuccess"><i class="fa fa-check"></i>Processing Fees - 5% of Activity price or minimum 2RM of Activity price</span>
        </div>
        <?php } ?>
      </div>
      <div class="form-group">
        <div class="col-md-6">
          <label class="control-label">Category</label>
          <select class="form-control select2" multiple="multiple" data-placeholder="Select Category" style="width: 100%;" name="guiding_speciality[]" id="guiding_speciality">
              <option value="">Select Category</option>
              <?php
              if( $specializationLists ) { 
                foreach ( $specializationLists as $key => $value ) {
                  ?>
                  <option <?php if($categoryIDs){ if(in_array($value->specialization_id, $categoryIDs)){ echo 'selected'; } } ?> value="<?= $value->specialization_id; ?>"><?= rawurldecode($value->specialization); ?></option>
                <?php
                }
              }
              ?>
          </select>
        </div>
        <div class="col-md-6">
          <label class="control-label">Service Region <span class="text-danger">*</span></label>
          <select class="form-control" name="service_providing_region" id="service_providing_region">
              <option value="">Select Service Region</option>
              <?php
              if( $serviceRegionLists ) {
                foreach ( $serviceRegionLists as $key => $value ) {
                  $selected = '';
                  if( $value->id == $service_region ) {
                    $selected = 'selected';
                  }
                    echo '<option '. $selected .' value="'. $value->id .'">'.$value->name.'</option>';
                }
              }
              ?>
          </select>
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-group">
          <label class="control-label">Cancellation Policy</label>
          <textarea name="cancellation_policy" id="cancellation_policy" rows="6" class="form-control"><?=$cancellation_policy;?></textarea>
        </div>
        <div class="form-group">
          <label class="control-label">What I Offer</label>
          <textarea type="text" name="what_i_offer" id="what_i_offer" rows="6" class="form-control"><?=$what_i_offer;?></textarea>
        </div>
        <div class="col-md-2">
          <label for="photo" style="padding-top: 10px;">Image 1</label>
        </div>
        <div class="col-md-10">
          <div class="form-group">
            <input class="form-control" type="file" name="photo" id="photo">
            <?php
            if($photo_1){ 
            ?>
            <a class="example-image-link" href="<?= $photo_1; ?>" data-lightbox="example-set">
                <img class="img-thumbnail" src="<?=$photo_1; ?>" id="client_picture" style="height: auto;width: 45px;" data-src="#" />
            </a>
            <?php 
            } ?>
          </div>
        </div>
        <div class="col-md-2">
          <label for="photo" style="padding-top: 10px;">Image 2</label>
        </div>
        <div class="col-md-10">
          <div class="form-group">
            <input class="form-control" type="file" name="photo1" id="photo1">
            <?php
            if($photo_2){ 
            ?>
            <a class="example-image-link" href="<?= $photo_2; ?>" data-lightbox="example-set">
                <img class="img-thumbnail" src="<?=$photo_2; ?>" id="client_picture" style="height: auto;width: 45px;" data-src="#" />
            </a>
            <?php 
            } ?>
          </div>
        </div>
        <div class="col-md-2">
          <label for="photo" style="padding-top: 10px;">Image 3</label>
        </div>
        <div class="col-md-10">
          <div class="form-group">
            <input class="form-control" type="file" name="photo2" id="photo2">
            <?php
            if($photo_3){ 
            ?>
            <a class="example-image-link" href="<?= $photo_3; ?>" data-lightbox="example-set">
                <img class="img-thumbnail" src="<?=$photo_3; ?>" id="client_picture" style="height: auto;width: 45px;" data-src="#" />
            </a>
            <?php 
            } ?>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-group" for="additional_info_label">
          <label class="control-label">Additional Info Label</label>
          <textarea type="text" name="additional_info_label" id="additional_info_label" rows="6" class="form-control"><?=$additional_info_label;?></textarea>
        </div>
      </div>
      <div class="form-group">
        <div class="col-md-6">
          <label class="control-label" for="maximum_booking">Maximum Booking</label>
          <input type="text" name="maximum_booking" id="maximum_booking" maxlength="5" value="<?=$maximum_booking;?>" class="form-control number" />
        </div>
        <div class="col-md-6">
          <label class="control-label" for="date_time_needed">Date Time Needed</label>
          <select class="form-control" name="date_time_needed" id="date_time_needed">
            <option value="1" <?php if($date_time_needed == 1){ echo 'selected'; } ?> >Yes</option>
            <option value="0" <?php if($date_time_needed == 0){ echo 'selected'; } ?> >No</option>
          </select>
        </div>
      </div>
      <div>
        <input type="hidden" name="activity_value" id="activity_value" value="1">
        <input type="hidden" name="processingFeesType" id="processingFeesType" value="<?=$processingFeesType;?>">
        <input type="hidden" name="activity_id" id="activity_id" value="<?=$activity_id;?>">
        <input type="hidden" name="guider_id" id="guider_id" value="<?=$guider_id;?>">
        <a href="javascript:;" class="btn btn-success btn-sm" onclick="return updateActivityField();">Update</a>
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-hidden="true">Cancel</button>
      </div>
    </div>
  </form>
</div>
<script type="text/javascript">
$(document).ready(function(){
  $('.number').keypress(function(event) {
    if (event.which == 8 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46) {
      return true;
    }else if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
      event.preventDefault();
    }
  });
  $('.select2').select2();
  $('#price_type_id').on('change', function() {
    if(this.value == 3){
      $("#rate_per_person").val(0);
    }else{
      $("#rate_per_person").val('');
    }
  });
});
function updateActivityField() {
  var data        = $( 'form#updateGuiderActivityForm' ).serialize();
  var min_rate    = <?= MIN_RATE; ?>;
  var max_rate    = <?= MAX_RATE; ?>;
  var price_type_id = $( '#updateGuiderActivityForm #price_type_id' ).val();
  var rate        = $( '#updateGuiderActivityForm #rate_per_person' ).val();
  var region      = $( '#updateGuiderActivityForm #service_providing_region' ).val();
  if(price_type_id == ''){
    toastr.error( 'Please select Pricing Type.','Error' );
    return false;
  }
  if(price_type_id != 3){
    if(!$.isNumeric(rate)){
      toastr.error( 'Invalid pricing value','Error' );
      return false;
    }
    if (rate < min_rate || rate > max_rate) {
      var errmsg = 'Minimum pricing '+min_rate+' and maximum '+max_rate;
      toastr.error( errmsg,'Error' );
      return false;
    }
  }
  if(region == ''){
    toastr.error( 'Please select service region.','Error' );
    return false;
  }
  $( '#updateGuiderActivityForm' ).submit();
}
</script>