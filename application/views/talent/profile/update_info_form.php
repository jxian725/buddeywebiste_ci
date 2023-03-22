<?php  
$dirUrl = $this->config->item( 'dir_url' );
?>
<link rel="stylesheet" href="<?= $dirUrl; ?>plugins/select2/select2.min.css"> 
<script src="<?= $dirUrl; ?>plugins/select2/select2.full.min.js"></script>
<div class="row">
  <div id="loading-img"></div>
  <form novalidate="" id="updateGuiderForm" role="form" method="post" enctype="multipart/form-data">
  <div class="col-md-12">
    <?php
    if($field == 'about'){ ?>
      <div class="form-group">
        <textarea type="text" name="about_me" id="about_me" rows="10" class="form-control"><?=$talentInfo->about_me;?></textarea>
      </div>
      <?php 
    }elseif ($field == 'policy') { ?>
      <div class="form-group">
        <textarea name="cancellation_policy" id="cancellation_policy" rows="10" class="form-control"><?=$talentInfo->cancellation_policy;?></textarea>
      </div>
      <?php
    }elseif ($field == 'area') { ?>
      <div class="form-group">
        <input type="text" name="area" id="area" class="form-control" value="<?=$talentInfo->area;?>">
      </div>
      <?php
    }elseif ($field == 'last_name') { ?>
      <div class="form-group">
        <input type="text" name="last_name" id="last_name" class="form-control" value="<?=$talentInfo->last_name;?>">
      </div>
      <?php
    }elseif ($field == 'offer') { ?>
      <div class="form-group">
        <textarea type="text" name="what_i_offer" id="what_i_offer" rows="8" class="form-control"><?=$talentInfo->what_i_offer;?></textarea>
      </div>
      <?php
    }elseif ($field == 'region') { ?>
      <div class="form-group">
        <select class="form-control" name="service_providing_region" id="service_providing_region">
            <option value="">Service Region</option>
            <?php 
            if( $serviceRegionLists ) { 
              foreach ( $serviceRegionLists as $key => $value ) {
                $selected = '';
                if( $value->id == $talentInfo->service_providing_region ) {
                  $selected = 'selected';
                }
                echo '<option '. $selected .' value="'. $value->id .'">'.$value->name.'</option>';
              }
            }
            ?>
        </select>
      </div>
      <?php
    }elseif ($field == 'category') {
      if($talentInfo->guiding_speciality){
        $categoryIDs  = explode(',',$talentInfo->guiding_speciality);
      }else{
        $categoryIDs  = '';
      }
      ?>
      <div class="form-group">
        <select class="form-control select2" multiple="multiple" data-placeholder="Service Region" style="width: 100%;" name="guiding_speciality[]" id="guiding_speciality">
            <option value="">Service Region</option>
            <?php
            if( $specializationLists ) { 
              foreach ( $specializationLists as $key => $value ) {
                ?>
                <option <?php if($categoryIDs){ if(in_array($value->specialization_id, $categoryIDs)){ echo 'selected'; } } ?> value="<?= $value->specialization_id; ?>"><?= $value->specialization; ?></option>
              <?php
              }
            }
            ?>
        </select>
      </div>
      <?php
    }elseif ($field == 'id_proof') {
      ?>
      <div class="col-md-2">
        <label for="id_proof" style="padding-top: 10px;">ID Proof</label>
      </div>
      <div class="col-md-10">
        <div class="form-group">
          <input class="form-control" type="file" name="id_proof" id="id_proof" accept="image/*">
          <small>Only JPG, PNG and GIF files are allowed</small>
        </div>
      </div>
    <?php
    }elseif ($field == 'profile_image') {
    ?>
      <div class="col-md-3">
        <label for="profile_image" style="padding-top: 10px;">Profile Image</label>
      </div>
      <div class="col-md-9">
        <div class="form-group">
          <input class="form-control" type="file" name="profile_image" id="profile_image" accept="image/*">
          <small>Only JPG, PNG and GIF files are allowed</small>
        </div>
      </div>
    <?php
    }elseif ($field == 'password') {
      if($talentInfo->password){
        $password = $this->encryption->decrypt($talentInfo->password);
      }else{
        $password = '';
      }
      ?>
      <div class="form-group">
        <input type="text" maxlength="10" name="password" id="password" class="form-control allow_password" value="<?=$password;?>">
      </div>
      <?php
    }elseif ($field == 'dbkl_lic_no') { ?>
      <div class="form-group">
        <input type="text" name="dbkl_lic_no" id="dbkl_lic_no" class="form-control" value="<?=$talentInfo->dbkl_lic_no;?>">
      </div>
      <?php
    }elseif ($field == 'dbkl_lic') {
      ?>
      <div class="col-md-3">
        <label for="dbkl_lic" style="padding-top: 10px;">DBKL license</label>
      </div>
      <div class="col-md-9">
        <div class="form-group">
          <input class="form-control" type="file" name="dbkl_lic" id="dbkl_lic" accept="image/*">
          <small>Only JPG, PNG and GIF files are allowed</small>
        </div>
      </div>
    <?php
    }elseif ($field == 'nric_number') { ?>
      <div class="form-group">
        <input type="text" name="nric_number" id="nric_number" class="form-control nric_number" value="<?=$talentInfo->nric_number;?>">
      </div>
    <?php
    }elseif ($field == 'gigs_amount') { ?>
      <div class="form-group">
        <input type="text" name="gigs_amount" id="gigs_amount" class="form-control" value="<?=$talentInfo->gigs_amount;?>">
      </div> 
    <?php
    }elseif ($field == 'sub_skills') { ?>
      <div class="form-group">
        <input type="text" name="sub_skills" id="sub_skills" class="form-control" value="<?=$talentInfo->sub_skills;?>">
      </div>   
    <?php
    }elseif ($field == 'acc_no') { ?>
      <div class="form-group">
        <input type="text" name="acc_no" id="acc_no" class="form-control" value="<?=$talentInfo->acc_no;?>">
      </div>
    <?php
    }elseif ($field == 'acc_name') { ?>
      <div class="form-group">
        <input type="text" name="acc_name" id="acc_name" class="form-control" value="<?=$talentInfo->acc_name;?>">
      </div>
    <?php
    }elseif ($field == 'gender') { ?> 
      <div class="form-group"> 
        <select class="form-control" id="gender" name="gender"> 
          <option value="0">Select gender</option>
          <option <?php if ($talentInfo->gender == 1 ) echo 'selected' ; ?> value="1">Male</option>
          <option <?php if ($talentInfo->gender == 2 ) echo 'selected' ; ?> value="2">Female</option>
        </select>
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
                if( $cityInfo->id == $talentInfo->city ) {
                  $selected = 'selected';
                }
                echo '<option '. $selected .' value="'. $cityInfo->id .'">'.$cityInfo->name.'</option>';
              }
            }
            ?>
        </select>
      </div>
    <?php
    }elseif ($field == 'skills_category') { ?>  
    <div class="form-group">
        <select class="form-control" name="skills_category" id="skills_category">
            <option value="">Select Skills</option>
            <?php 
            if($specializationLists) { 
              foreach ( $specializationLists as $key => $specialization ) {
                $selected = '';
                if( $specialization->specialization_id == $talentInfo->skills_category ) {
                  $selected = 'selected';
                }
                echo '<option '. $selected .' value="'. $specialization->specialization_id .'">'.$specialization->specialization.'</option>';
              }
            }
            ?>
        </select>
      </div>      
    <?php
    }elseif ($field == 'bank_name') { ?>
      <div class="form-group">
        <select class="form-control" id="bank_name" name="bank_name">
          <option value="<?=$talentInfo->bank_name;?>"><?=$talentInfo->bank_name;?></option>
          <option value="">Select Bank Name</option>
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
    }elseif ($field == 'phone_number') { ?>
      <div class="form-group">
        <div class="row">
          <div class="col-sm-2" style="padding-right: 0px;">
            <select class="form-control" style="width: 100%;" name="countryCode" id="countryCode">
              <?php
              if(($talentInfo->countryCode == '60')){ ?>
                <option selected value="60">60</option>
              <?php }
              if(($talentInfo->countryCode == '91')){ ?>
                <option selected value="91">91</option>
              <?php } ?>
            </select>
          </div>
          <div class="col-sm-10">
            <input type="text" class="form-control number" maxlength="12" name="phone_number" id="phone_number" value="<?=$talentInfo->phone_number;?>">
          </div>
        </div>
      </div>
      <?php
    }
    ?>
  </div>
  <?php
  if($field){
    ?>
    <div class="col-md-12">
      <input type="hidden" name="talent_id" id="talent_id" value="<?=$talent_id;?>">
      <input type="hidden" name="field" id="field" value="<?=$field;?>">
      <a href="javascript:;" class="btn btn-success btn-sm" onclick="return updateGuiderField();">Update</a>
    </div>
    <?php
  }
  ?>
</form>
</div>
<script type="text/javascript">
$(document).ready(function(){
  $('.select2').select2();
});
//allow hyphen and number
$(document).on('keypress','.nric_number',function(event){
  if (event.which == 8 || event.which == 45) {
    return true;
  }else if ((event.which != 45 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
    event.preventDefault();
  }
});
function updateGuiderField() {
  var data    = $( 'form#updateGuiderForm' ).serialize();
  var field   = $( 'form#updateGuiderForm #field' ).val();

  if(field == 'phone_number'){
    var phone_number = $( 'form#updateGuiderForm #phone_number' ).val();
    if(phone_number == ''){
      toastr.error( 'Please enter mobile number.','Error' );
      return false;
    }else{
      if (phone_number.length < 6 || phone_number.length > 12) {
        toastr.error( 'Enter valid mobile number.','Error' );
        return false;
      }
    }
  }

  $( '#myModal #loading-img' ).html('<img src="<?=$dirUrl; ?>img/ajax-loader.gif" id="gif" style="display: block; margin: 0 auto; width: 100px; visibility:visible;">');
  $('form#updateGuiderForm').hide();
  $.ajax( {
      type    : "POST",
      data    : data,
      url     : baseurl + 'talent/profile/updateGuiderField',
      dataType: 'json',
      success: function( res ) {
        if(res.status == 1){
          toastr.success( '<?= HOST_NAME; ?> Information updated successfully.','Success' );
          setTimeout( function() {
            location.reload();
          }, 500 );
        }else{
          toastr.error( res.message,'Error' );
        }
        $( '#myModal #loading-img' ).html('');
        $('form#updateGuiderForm').show();
      }
  });
  return false;
}
</script>