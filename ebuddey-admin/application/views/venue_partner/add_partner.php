<?php 
$assetUrl   = $this->config->item( 'base_url' ); 
?>
<link rel="stylesheet" href="<?= $assetUrl; ?>plugins/select2/select2.min.css">
<script src="<?= $assetUrl; ?>plugins/select2/select2.full.min.js"></script>
<div class="row">
  <form novalidate="" id="partnerForm" role="form" method="post" enctype="multipart/form-data">  
    <div class="col-md-12">     
      <?php 
          if($field == 0){ ?>
            <div class="form-group">
              
              <select class="form-control select2" multiple="multiple" data-placeholder="Select Partner" name="partner_id[]" id="partner_id" style="width: 100%;" tabindex="-1">
                  <?php 
                    if($partnerList){
                      $partnerArr  = explode(',', $partnerInfo->partner_id);
                    foreach ($partnerList as $partner) {
                  ?>
                  <option value="<?php echo $partner->partner_id; ?>" <?php if(in_array($partner->partner_id, $partnerArr)){ echo 'selected'; } ?>><?php echo rawurldecode($partner->partner_name); ?></option>
                  <?php
                    }
                  }
                  ?>
              </select>
            </div>
        <?php 
        }
        ?>
      <?php
      if($field == 0){ 
      ?>
      <div class="form-group">
        <input type="hidden" name="venuepartnerId" id="venuepartnerId" value="<?=$venuepartnerId;?>">
        <input type="hidden" name="field" id="field" value="<?=$field;?>">
        <a href="javascript:;" class="btn btn-success btn-sm" onclick="return updatePartnerField();">Update</a>
        <button type="button" data-dismiss="modal" class="btn btn-danger btn-sm">Cancel</button>
      </div>
      <?php
      }
      ?> 
    </div>
  </form>
</div>
<script type="text/javascript">
$(document).ready(function(){
  $('.select2').select2();
});
</script>
<script type="text/javascript">
function updatePartnerField() {
  var data          = $( 'form#partnerForm' ).serialize();
  var field         = $( 'form#partnerForm #field' ).val();
  var partner_id    = $( 'form#partnerForm #partner_id' ).val();
  if(partner_id == ''){
    toastr.error( 'Please select Partner ','Error' );
    return false;
  }
  if(partner_id){
    $.ajax( {
        type    : "POST",
        data    : data,
        url     : adminurl + 'Venuepartner/updatePartner',
        success: function( data ) {
          toastr.success( 'Partner Updated Successfully.','Success' );
          setTimeout( function() {
            location.reload();
          }, 2000 );
        }
    });
  }  
  return false;
}
</script>