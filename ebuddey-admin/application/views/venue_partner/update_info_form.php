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
    }elseif ($field == 'company_name') { ?>
      <div class="form-group">
        <input type="text" name="company_name" id="company_name" class="form-control" value="<?=$partnerInfo->company_name;?>">
      </div>  
    <?php
    }elseif ($field == 'business_address') { ?>
      <div class="form-group">
        <input type="text" name="business_address" id="business_address" class="form-control" value="<?=$partnerInfo->business_address;?>">
      </div>
    <?php
    }elseif ($field == 'bank_name') { ?>
      <div class="form-group">
        <select class="form-control" id="bank_name" name="bank_name">
          <option value="<?=$partnerInfo->bank_name;?>"><?=$partnerInfo->bank_name;?></option>
          <option value="0">Select Bank Name</option>
          <option value="AFFIN BANK">AFFIN BANK</option>
          <option value="ALLIANCE BANK">ALLIANCE BANK</option>
          <option value="AM BANK">AM BANK</option>
          <option value="BANK ISLAM">BANK ISLAM</option>
          <option value="BANK RAKYAT">BANK RAKYAT</option>
          <option value="BANK MUAMALAT">BANK MUAMALAT</option>
          <option value="BSN BANK">BSN BANK</option>
          <option value="CIMB BANK">CIMB BANK</option>
          <option value="HONGLEONG BANK">HONGLEONG BANK</option>
          <option value="HSBC BANK">HSBC BANK</option>
          <option value="KUWAIT FINANCE HOUSE">KUWAIT FINANCE HOUSE</option>
          <option value="MAY BANK 2E">MAY BANK 2E</option>
          <option value="MAY BANK">MAY BANK</option>
          <option value="OCBC BANK">OCBC BANK</option>
          <option value="PUBLIC BANK">PUBLIC BANK</option>
          <option value="RHB BANK">RHB BANK</option>
          <option value="STANDARD CHARTERED">STANDARD CHARTERED</option>
          <option value="UOB BANK">UOB BANK</option>
        </select>
      </div>
    <?php
    }elseif ($field == 'account_name') { ?>
      <div class="form-group">
        <input type="text" name="account_name" id="account_name" class="form-control" value="<?=$partnerInfo->account_name;?>">
      </div>
    <?php
    }elseif ($field == 'account_number') { ?>
      <div class="form-group">
        <input type="text" name="account_number" id="account_number" class="form-control" value="<?=$partnerInfo->account_number;?>">
      </div>
    <?php
    }elseif ($field == 'password') { ?>
      <div class="form-group">
         <div><?php $password = $this->encryption->decrypt($partnerInfo->password); ?></div>
        <input type="text" name="password" id="password" class="form-control" value="<?=$password;?>">
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
         <!-- <div class="col-sm-2" style="padding-right: 0px;">
            <select class="form-control" style="width: 100%;" name="countryCode" id="countryCode">
             <?php
              //if(($partnerInfo->countryCode == '60')){ ?>
                //<option selected value="60">60</option>
              <?php //}
              //if(($partnerInfo->countryCode == '91')){ ?>
                <option selected value="91">91</option>
              <?php //} ?>
            </select>
          </div>-->
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
            if($cityLists) { 
              foreach ( $cityLists as $key => $cityInfo ) {
                $selected = '';
                if( $cityInfo->id == $partnerInfo->city ) {
                  $selected = 'selected';
                }
                echo '<option '. $selected .' value="'. $cityInfo->id .'">'.$cityInfo->name.'</option>';
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
      <input type="hidden" name="venuepartnerId" id="venuepartnerId" value="<?=$venuepartnerId;?>">
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
      if (mobile_no.length < 6 || mobile_no.length > 15) {
        toastr.error( 'Your mobile number should be minimum 6 characters.','Error' );
        return false;
      }
    }
  }
  if(field == 'password'){
    var password = $( 'form#updatePartnerForm #password' ).val();
    if(password == ''){
      toastr.error( 'Please enter Password.','Error' );
      return false;
    }else{
      if (password.length < 8 || password.length > 30) {
        toastr.error( 'Your password should be minimum 8 characters.','Error' );
        return false;
      }
    }
  }
  if(field == 'account_number'){
    var account_number = $( 'form#updatePartnerForm #account_number' ).val();
    if(account_number == ''){
      toastr.error( 'Please enter Account Number.','Error' );
      return false;
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
        url     : adminurl + 'Venuepartner/updatePartnerField',
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