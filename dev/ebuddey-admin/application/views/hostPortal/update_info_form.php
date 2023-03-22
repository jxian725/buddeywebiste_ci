<?php 
$dirUrl = $this->config->item( 'dir_url' );
$upload_path_url = $this->config->item( 'upload_path_url' );
?>
<link rel="stylesheet" href="<?php echo $dirUrl; ?>plugins/bootstrap-datepicker/bootstrap-datepicker.min.css">
<script src="<?php echo $dirUrl; ?>plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="<?= $dirUrl; ?>plugins/select2/select2.min.css">
<script src="<?= $dirUrl; ?>plugins/select2/select2.full.min.js"></script>
<div class="row">
  <form novalidate="" id="updateGuiderForm" class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
  <div class="col-md-12">
    <?php
    if($field == 'about'){ ?>
      <div class="col-md-12">
        <div class="form-group">
          <textarea type="text" name="about_me" id="about_me" rows="10" class="form-control"><?=$guiderInfo->about_me;?></textarea>
        </div>
      </div>
      <?php 
    }elseif ($field == 'first_name') { ?>
      <div class="col-md-12">
        <div class="form-group">
          <input type="text" name="first_name" id="first_name" class="form-control" value="<?=$guiderInfo->first_name;?>">
        </div>
      </div>
      <?php
    }elseif ($field == 'last_name') { ?>
      <div class="col-md-12">
        <div class="form-group">
          <input type="text" name="last_name" id="last_name" class="form-control" value="<?=$guiderInfo->last_name;?>">
        </div>
      </div>
      <?php
    }elseif ($field == 'email') { ?>
      <div class="col-md-12">
        <div class="form-group">
          <input type="email" name="email" id="email" class="form-control" value="<?=$guiderInfo->email;?>">
        </div>
      </div>
      <?php
    }elseif ($field == 'age') { ?>
      <div class="col-md-12">
        <div class="form-group">
          <input type="text" name="dob" id="dob" class="form-control datepicker" value="<?=$guiderInfo->age;?>">
        </div>
      </div>
      <?php
    }elseif ($field == 'bank') { ?>
      <div class="col-md-12">
        <div class="form-group">
          <label for="acc_name" class="col-sm-6 control-label">Bank Account Name<span class="text-danger">*</span></label>
          <div class="col-sm-6">
            <input type="text" class="form-control" maxlength="30" name="acc_name" id="acc_name" value="<?=$guiderInfo->acc_name;?>" placeholder="Account Name">
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-group">
          <label for="bank_name" class="col-sm-6 control-label">Bank Name<span class="text-danger">*</span></label>
          <div class="col-sm-6">
            <input type="text" class="form-control" maxlength="40" name="bank_name" id="bank_name" value="<?=$guiderInfo->bank_name;?>" placeholder="Bank Name">
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-group">
          <label for="acc_no" class="col-sm-6 control-label">Bank Account Number<span class="text-danger">*</span></label>
          <div class="col-sm-6">
            <input type="text" class="form-control number" maxlength="16" name="acc_no" id="acc_no" value="<?=$guiderInfo->acc_no;?>" placeholder="Acc Number">
          </div>
        </div>
      </div>
      <?php
    }elseif ($field == 'offer') { ?>
      <div class="col-md-12">
        <div class="form-group">
          <textarea type="text" name="what_i_offer" id="what_i_offer" rows="8" class="form-control"><?=$guiderInfo->what_i_offer;?></textarea>
        </div>
      </div>
      <?php
    }elseif ($field == 'region') { ?>
      <div class="col-md-12">
        <div class="form-group">
          <select class="form-control" name="service_providing_region" id="service_providing_region">
              <option value="">Service Region</option>
              <?php 
              if( $serviceRegionLists ) { 
                foreach ( $serviceRegionLists as $key => $value ) {
                  $selected = '';
                  if( $value->id == $guiderInfo->service_providing_region ) {
                    $selected = 'selected';
                  }
                  echo '<option '. $selected .' value="'. $value->id .'">'.$value->name.'</option>';
                }
              }
              ?>
          </select>
        </div>
      </div>
      <?php
    }elseif ($field == 'category') {
      if($guiderInfo->guiding_speciality){
        $categoryIDs  = explode(',',$guiderInfo->guiding_speciality);
      }else{
        $categoryIDs  = '';
      }
      ?>
      <div class="col-md-12">
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
      </div>
      <?php
    }elseif ($field == 'id_proof') {
      ?>
      <div class="col-md-12">
        <div class="col-md-2">
          <label for="id_proof" style="padding-top: 10px;">ID Proof</label>
        </div>
        <div class="col-md-10">
          <div class="form-group">
            <input class="form-control" type="file" name="id_proof" id="id_proof" accept="image/*">
            <small>Only JPG, PNG and GIF files are allowed</small>
          </div>
        </div>
      </div>
    <?php
    }elseif ($field == 'profile_image') {
    ?>
      <div class="col-md-12">
        <div class="col-md-3">
          <label for="profile_image" style="padding-top: 10px;">Profile Image</label>
        </div>
        <div class="col-md-9">
          <div class="form-group">
            <input class="form-control" type="file" name="profile_image" id="profile_image" accept="image/*">
            <small>Only JPG, PNG and GIF files are allowed</small>
          </div>
        </div>
      </div>
    <?php
    }elseif ($field == 'password') {
      if($guiderInfo->password){
        $password = $this->encryption->decrypt($guiderInfo->password);
      }else{
        $password = '';
      }
      ?>
      <div class="col-md-12">
        <div class="form-group">
          <input type="text" maxlength="10" name="password" id="password" class="form-control allow_password" value="<?=$password;?>">
        </div>
      </div>
      <?php
    }elseif ($field == 'language') {
      if($guiderInfo->password){
        $password = $this->encryption->decrypt($guiderInfo->password);
      }else{
        $password = '';
      }
      ?>
      <div class="col-md-12">
        <div class="form-group">
          <select class="form-control select2" multiple="multiple" data-placeholder="Select language" style="width: 100%;" name="languages_known[]" id="languages_known">
            <?php
            if($getHostLangLists){
              foreach ($getHostLangLists as $key => $lang) { ?>
                <option value="<?= $lang->lang_id; ?>"><?= $lang->language; ?></option>
              <?php
              }
            }
            ?>
          </select>
        </div>
      </div>
      <?php
    }elseif ($field == 'dbkl_lic_no') { ?>
      <div class="col-md-12">
        <div class="form-group">
          <input type="text" name="dbkl_lic_no" id="dbkl_lic_no" class="form-control" value="<?=$guiderInfo->dbkl_lic_no;?>">
        </div>
      </div>
      <?php
    }elseif ($field == 'dbkl_lic') {
      ?>
      <div class="col-md-12">
        <div class="col-md-3">
          <label for="dbkl_lic" style="padding-top: 10px;">DBKL license</label>
        </div>
        <div class="col-md-9">
          <div class="form-group">
            <input class="form-control" type="file" name="dbkl_lic" id="dbkl_lic" accept="image/*">
            <small>Only JPG, PNG and GIF files are allowed</small>
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
      <input type="hidden" name="guider_id" id="guider_id" value="<?=$guider_id;?>">
      <input type="hidden" name="field" id="field" value="<?=$field;?>">
      <a href="javascript:;" class="btn btn-success btn-sm" onclick="return updateGuiderField();">Update</a>
    </div>
    <?php
  }
  ?>
</form>
</div>
<?php
$dobminyear = (date('Y')-13).'-'.date('m').'-'.date('d');
?>
<script type="text/javascript">
$(document).ready(function(){
  $('.select2').select2();
});
$( ".datepicker" ).datepicker({
  changeYear: true,
  format: 'yyyy-mm-dd',
  autoclose: true,
  maxDate: 0,
  endDate: '<?= $dobminyear; ?>',
  orientation: 'auto'
});
function updateGuiderField() {
  var data    = $( 'form#updateGuiderForm' ).serialize();
  var field   = $( 'form#updateGuiderForm #field' ).val();
  if(field == 'profile_image'|| field == 'id_proof' || field == 'dbkl_lic'){
    $( '#updateGuiderForm' ).submit();
  }else{
    $( '#myModal .modal-body' ).html('<img src="<?=$dirUrl; ?>img/ajax-loader.gif" id="gif" style="display: block; margin: 0 auto; width: 100px; visibility:visible;">');
    $.ajax( {
        type    : "POST",
        data    : data,
        url     : hosturl + 'editprofile/updateGuiderField',
        dataType: 'json',
        success: function( msg ) {
          if(msg['success'] == 1){
            toastr.success( '<?= HOST_NAME; ?> Information updated successfully.','Success' );
          }else{
            toastr.error( msg['msg'],'Error' );
          }
          setTimeout( function() {
            location.reload();
          }, 500 );
        }
    });
    return false;
  }
}
</script>