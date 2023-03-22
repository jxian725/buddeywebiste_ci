<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl       = $this->config->item( 'admin_url' );
$site_name      = $this->config->item( 'site_name' );
$dirUrl         = $this->config->item( 'dir_url' );
$random_pass    = randomPassword();
?>
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo $dirUrl; ?>plugins/bootstrap-datepicker/bootstrap-datepicker.min.css">
<script src="<?php echo $dirUrl; ?>plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="<?= $dirUrl; ?>plugins/select2/select2.min.css">
<script src="<?= $dirUrl; ?>plugins/select2/select2.full.min.js"></script>
<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"></h3>
        <div class="box-tools pull-right">
          <a href="<?php echo $assetUrl; ?>guider" class="btn btn-sm btn-primary">Back</a>
        </div>
      </div>
      <div id="message_guider"></div>
      <form novalidate="" autocomplete="off" id="add_guider_form" role="form" method="post" class="form-horizontal" enctype="multipart/form-data">
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="full_name" class="col-sm-4 control-label">Full Name<span class="text-danger">*</span></label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" maxlength="30" name="full_name" id="full_name" placeholder="Full Name">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="other_name" class="col-sm-4 control-label">Other Name</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" maxlength="20" name="other_name" id="other_name" placeholder="Other Name">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="countryCode" class="col-sm-4 control-label">Mobile number<span class="text-danger">*</span></label>
                <div class="col-sm-2" style="padding-right: 0px;">
                  <select class="form-control" style="width: 100%;" name="countryCode" id="countryCode">
                    <option value="+60">+60</option>
                    <option value="+91">+91</option>
                  </select>
                </div>
                <div class="col-sm-6">
                  <input type="text" class="form-control pnumber" maxlength="13" name="phone_number" id="phone_number" placeholder="Mobile number">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="email" class="col-sm-4 control-label">Email<span class="text-danger">*</span></label>
                <div class="col-sm-8">
                  <input type="email" placeholder="Email" maxlength="80" name="email" id="email" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="languages_known" class="col-sm-4 control-label">Languages Known<span class="text-danger">*</span></label>
                <div class="col-sm-8">
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
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="nric_number" class="col-sm-4 control-label">NRIC Number</label>
                <div class="col-sm-8">
                  <input type="text" name="nric_number" id="nric_number" class="form-control nric_number" placeholder="Nric Number" maxlength="18">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="dob" class="col-sm-4 control-label">DOB</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control datepicker" name="dob" id="dob" placeholder="DOB">
                </div>
              </div>
            </div>
            <div class="col-md-6"></div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="password" class="col-sm-4 control-label">Password</label>
                <div class="col-sm-8">
                  <input type="text" required="" value="<?php echo $random_pass; ?>" maxlength="10" placeholder="Password" id="password" class="form-control allow_password" name="password">
                  <small>Max length 10 letters</small>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <h4>Account Details</h4>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="acc_name" class="col-sm-4 control-label">Bank Account Name</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" maxlength="30" name="acc_name" id="acc_name" placeholder="Account Name">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="bank_name" class="col-sm-4 control-label">Bank Name</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" maxlength="40" name="bank_name" id="bank_name" placeholder="Bank Name">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="acc_no" class="col-sm-4 control-label">Bank Account Number</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control number" maxlength="16" name="acc_no" id="acc_no" placeholder="Acc Number">
                </div>
              </div>
            </div>
            
            <div class="clearfix"></div>
            <div class="col-md-12">
              <h4>DBKL</h4>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-md-4 control-label" for="dbkl_lic">DBKL License</label>
                <div class="col-sm-8">
                  <input type="file" class="form-control" name="dbkl_lic" id="dbkl_lic" accept="image/*" />
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-sm-4 control-label" for="dbkl_lic_no">DBKL License Number</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" maxlength="16" name="dbkl_lic_no" id="dbkl_lic_no" placeholder="DBKL License Number">
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label for="about_me" class="col-sm-2 control-label">About Me</label>
                <div class="col-sm-10">
                  <textarea class="form-control" name="about_me" rows="5" id="about_me"></textarea>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label class="col-md-2 control-label">Profile Image</label>
                <div class="col-sm-4">
                    <input type='file' class="form-control imghost" name="profile_image" id="profile_image" accept="image/*" />
                    <small>Only JPG, PNG and GIF files are allowed</small>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-2 control-label">ID Proof</label>
                <div class="col-sm-4">
                    <input type='file' class="form-control imgGuider" name="id_proof" id="id_proof" accept="image/*" />
                    <small>Only JPG, PNG and GIF files are allowed</small>
                </div>
              </div>
            </div>
          </div>
          <!-- /.box-body -->
          <div class="box-footer">
            <div class="col-md-offset-2 col-md-10">
              <input type='hidden' class="form-control" name="submit" id="submit" value="new_host" />
              <button class="btn btn-info" id="addguidersubmit" onClick="return guiderValidate();" type="submit">Create Account</button>
              <button type="reset" class="btn btn-danger" type="reset">Clear</button>
            </div>
          </div>
      </form>
    </div>
  </div>
</div>
<?php
$dobminyear = (date('Y')-13).'-'.date('m').'-'.date('d');
?>
<script type="text/javascript">
$(document).ready(function(){
  $('.pnumber').keypress(function(event) {
      if (event.which == 8 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46) {
          return true;
      }else if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
          event.preventDefault();
      }
      if (this.value.length == 0 && event.which == 48 ){
        return false;
      }
  });
});
//allow hyphen and number
$(document).on('keypress','.nric_number',function(event){
  if (event.which == 8 || event.which == 45) {
    return true;
  }else if ((event.which != 45 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
    event.preventDefault();
  }
});
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
function guiderValidate() {
  var data          = $( 'form#add_guider_form' ).serialize();
  var full_name     = $( '#add_guider_form #full_name' ).val();
  var phone_number  = $( '#add_guider_form #phone_number' ).val();
  var email         = $( '#add_guider_form #email' ).val();
  var languages     = $( '#add_guider_form #languages_known' ).val();
  var isvalid       = 1;
  if(full_name == ''){
    toastr.error( 'Please enter full name.','Error' );
    isvalid = 0;
  }
  if(phone_number == ''){
    toastr.error( 'Please enter mobile number.','Error' );
    isvalid = 0;
  }
  if(email == ''){
    toastr.error( 'Please enter email address.','Error' );
    isvalid = 0;
  }else{
    if (!ValidateEmail(email)) {
      toastr.error( 'Please enter valid email address.','Error' );
      isvalid = 0;
    }
  }
  if(phone_number){
    if (phone_number.length < 6 || phone_number.length > 12) {
      var errmsg = 'Enter valid mobile number.';
      toastr.error( errmsg,'Error' );
      isvalid = 0;
    }
  }
  if(isvalid == 1){
    return true;
  }else{
    return false;
  }
}
</script>