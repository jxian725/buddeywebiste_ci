<?php 
$dirUrl = $this->config->item( 'dir_url' );
?>
<link rel="stylesheet" type="text/css" href="<?php echo $dirUrl; ?>assets/js/toastr/toastr.min.css">
<script type="text/javascript" src="<?php echo $dirUrl; ?>assets/js/toastr/toastr.min.js"></script>
<div class="row">
  <div id="loading-img"></div>
  <form novalidate="" id="updatePartnerForm" role="form" method="post" enctype="multipart/form-data">
  <div class="col-md-12">
    <?php
    if ($field == 'email') { ?>
      <div class="form-group">
        <input type="text" name="email" id="email" class="form-control" value="<?=$partnerInfo->email;?>">
      </div>
    <?php
    }elseif ($field == 'business_address') { ?>
      <div class="form-group">
        <input type="text" name="business_address" id="business_address" class="form-control" value="<?=$partnerInfo->business_address;?>">
      </div>
      <?php
    }elseif ($field == 'postcode') { ?>
      <div class="form-group">
        <input type="text" name="postcode" id="postcode" class="form-control" value="<?=$partnerInfo->postcode;?>">
      </div>
      <?php
    }elseif ($field == 'contact_person') { ?>
      <div class="form-group">
        <input type="text" name="contact_person" id="contact_person" class="form-control" value="<?=$partnerInfo->contact_person;?>">
      </div>
      <?php
     }elseif ($field == 'mobile_no') { ?>
      <div class="form-group">
        <div class="row">
          <div class="col-sm-10">
            <input type="text" class="form-control number" maxlength="12" name="mobile_no" id="mobile_no" value="<?=$partnerInfo->mobile_no;?>">
          </div>
        </div>
      </div>
      <?php
      }elseif ($field == 'city') { ?>
      <div class="form-group">
        <select class="form-control" name="city" id="city">
            <option value="">Select city</option>
            <?php 
            if($cityLists){ 
              foreach ( $cityLists as $cityInfo) {
            ?>     
              <option value="<?php echo $cityInfo->id; ?>"><?php echo $cityInfo->name; ?></option>
            <?php   
              }
            }
            ?>
        </select>
      </div>
      <?php
    }
    ?>
  </div>
  <?php
  if($field){
    ?>
    <div class="col-md-12">
      <input type="hidden" name="partner_id" id="partner_id" value="<?=$partner_id;?>">
      <input type="hidden" name="field" id="field" value="<?=$field;?>">
      <a href="javascript:;" class="btn btn-success btn-sm" onclick="return updatePartnerField();">Update</a>
    </div>
    <?php
  }
  ?>
</form>
</div>
<script type="text/javascript">
function updatePartnerField() {
  var data    = $( 'form#updatePartnerForm' ).serialize();
  var field   = $( 'form#updatePartnerForm #field' ).val();

  if(field == 'mobile_no'){
    var mobile_no = $( 'form#updatePartnerForm #mobile_no' ).val();
    if(mobile_no == ''){
      toastr.error( 'Please enter mobile number.','Error' );
      return false;
    }else{
      if (mobile_no.length < 6 || mobile_no.length > 12) {
        toastr.error( 'Enter valid mobile number.','Error' );
        return false;
      }
    }
  }
  if(field == 'profile_image' || field == 'id_proof' || field == 'dbkl_lic'){
    $( '#updatePartnerForm' ).submit();
  }else{
    //$( '#myModal .modal-body' ).html('<img src="<?=$dirUrl; ?>img/ajax-loader.gif" id="gif" style="display: block; margin: 0 auto; width: 100px; visibility:visible;">');
    $( '#myModal #loading-img' ).html('<img src="<?=$dirUrl; ?>ebuddey-admin/img/ajax-loader.gif" id="gif" style="display: block; margin: 0 auto; width: 100px; visibility:visible;">');
    $('form#updatePartnerForm').hide();
    $.ajax( {
        type    : "POST",
        data    : data,
        url     : baseurl + 'partner/venue/updatePartnerField',
        dataType: 'json',
        success: function( res ) {
          if(res.status == 1){
            toastr.success( 'Information updated successfully.','Success' );
            setTimeout( function() {
              location.reload();
            }, 500 );
          }else{
            toastr.error( res.message,'Error' );
          }
          $( '#myModal #loading-img' ).html('');
          $('form#updatePartnerForm').show();
        }
    });
    return false;
  }
}
</script>