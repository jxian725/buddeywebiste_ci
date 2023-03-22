<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl       = $this->config->item( 'admin_url' );
$random_pass    = randomPassword();
?>
<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"></h3>
        <div class="box-tools pull-right">
          <a href="<?php echo $assetUrl; ?>venuepartner" class="btn btn-sm btn-primary">Back</a>
        </div>
      </div>
      <div id="message_guider"></div>
      <form novalidate="" autocomplete="off" id="add_venuepartner_form" role="form" method="post" class="form-horizontal">
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="company_name" class="col-sm-4 control-label">Company Name<span class="text-danger">*</span></label>
                <div class="col-sm-8">
                  <input class="form-control" required="" maxlength="30" name="company_name" id="company_name" type="text" placeholder="Enter Company Name">
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
                  <input type="text" class="form-control pnumber" maxlength="13" name="mobile_no" id="mobile_no" placeholder="Mobile number">
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
          
            <div class="col-md-6"></div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="password" class="col-sm-4 control-label">Password<span class="text-danger">*</span></label>
                <div class="col-sm-8">
                  <input type="text" required="" value="<?php echo $random_pass; ?>" name="password" id="password" maxlength="10" placeholder="Password" class="form-control allow_password">
                  <small>Max length 10 letters</small>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <h4>Company Details</h4>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="city" class="col-sm-4 control-label">City<span class="text-danger">*</span></label>
                <div class="col-sm-8">
                  <select class="form-control" name="city" id="city">
                    <option value="">Select City</option>
                    <?php 
                    if($cityLists){
                        foreach ($cityLists as $cityInfo) {
                          ?><option value="<?php echo $cityInfo->id; ?>"><?php echo $cityInfo->name; ?></option><?php
                        }
                    }
                    ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="business_address" class="col-sm-4 control-label">Enter Bussiness Address<span class="text-danger">*</span></label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" maxlength="90" name="business_address" id="business_address" placeholder="Enter Business Address">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="postcode" class="col-sm-4 control-label">Postcode<span class="text-danger">*</span></label>
                <div class="col-sm-8">
                  <input type="text" class="form-control number" maxlength="6" name="postcode" id="postcode" placeholder="Postcode">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="contact_person" class="col-sm-4 control-label">Contact Person<span class="text-danger">*</span></label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" maxlength="30" name="contact_person" id="contact_person" placeholder="Full name of contact person">
                </div>
              </div>
            </div>
            <div class="clearfix"></div>

            <div class="col-md-12">
              <h4>Transaction Details</h4>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="bank_name" class="col-sm-4 control-label">Bank Name</label>
                <div class="col-sm-8">
                  <select class="form-control" id="bank_name" name="bank_name"> 
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
              </div>
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
                <label for="acc_no" class="col-sm-4 control-label">Bank Account Number</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control number" maxlength="16" name="acc_no" id="acc_no" placeholder="Acc Number">
                </div>
              </div>
            </div>
            
            <div class="clearfix"></div>
          </div>
          <!-- /.box-body -->
          <div class="box-footer row">
            <div class="col-md-offset-2 col-md-10">
              <button class="btn btn-info" id="addvenuesubmit" type="submit">Add Venue partner</button>
              <button type="reset" class="btn btn-danger" type="reset">Clear</button>
            </div>
          </div>
      </form>
    </div>
  </div>
</div>

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
$(document).ready(function(){

  $("#add_venuepartner_form").on('submit', function(e){
        e.preventDefault();

        var company_name = $( '#add_venuepartner_form #company_name' ).val();
        var mobile_no    = $( '#add_venuepartner_form #mobile_no' ).val();
        var email       = $( '#add_venuepartner_form #email' ).val();
        var password    = $( '#add_venuepartner_form #password' ).val();

        var city      = $( '#add_venuepartner_form #city' ).val();
        var business_address = $( '#add_venuepartner_form #business_address' ).val();
        var postcode   = $( '#add_venuepartner_form #postcode' ).val();
        var contact_person = $( '#add_venuepartner_form #contact_person' ).val();

        if(company_name == ''){
          toastr.error('Company Name cannot be empty','Error');
          return false;
        }
        if(mobile_no == ''){
          toastr.error( 'Please enter mobile number.','Error' );
          return false;
        }else{
          if (mobile_no.length < 6 || mobile_no.length > 12) {
            toastr.error( 'Enter valid mobile number.','Error' );
            return false;
          }
        }

        if(email == ''){
          toastr.error( 'Please enter email address.','Error' );
          return false;
        }else{
          if (!ValidateEmail(email)) {
            toastr.error( 'Please enter valid email address.','Error' );
            return false;
          }
        }
        if(password == ''){
          toastr.error('Password cannot be empty','Error');
          return false;
        }

        if(city == ''){
          toastr.error('City cannot be empty','Error');
          return false;
        }
        if(business_address == ''){
          toastr.error('Business Address cannot be empty','Error');
          return false;
        }
        if(postcode == ''){
          toastr.error('Postcode cannot be empty','Error');
          return false;
        }
        if(contact_person == ''){
          toastr.error('Contact Person cannot be empty','Error');
          return false;
        }
        if(company_name && mobile_no && email && password){
            $.ajax({
              type: "POST",
              url: adminurl + '/venuepartner/insertVenuePartner',
              data: new FormData(this),
              contentType: false,
              cache: false,
              processData:false,
              beforeSend: function() { 
                  $("#addvenuesubmit").html('<img src="<?php echo $assetUrl;?>img/loading.gif" style="height:20px;"> Loading...');
                  $("#addvenuesubmit").prop('disabled', true);
                  $('#add_venuepartner_form').css("opacity",".5");
              },
              success: function( msg ) {
                var obj = jQuery.parseJSON(msg);
                if( obj.res_status == 'success' ) {
                    $('#add_venuepartner_form').trigger("reset");
                    toastr.success( obj.message,'Register Success' );
                    setTimeout( function() {
                        location.reload();
                    }, 1000 );
                } else {
                    alert(obj.message);
                }
                $('#add_venuepartner_form').css("opacity","");
              }
            });
            return false;
        }
    });
});

</script>