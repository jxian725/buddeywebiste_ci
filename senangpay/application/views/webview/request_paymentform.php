<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$base_url     = $this->config->item( 'base_url' );
$front_url    = $this->config->item( 'front_url' );
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>buddey</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 , user-scalable=no">
    <link rel="shortcut icon" href="images/8k.png" />
    <link rel="stylesheet" href="<?php echo $base_url; ?>css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>css/style.css">  
    <link rel="stylesheet" href="<?php echo $base_url; ?>css/responsive.css">
    <script src="<?php echo $base_url; ?>js/jquery-1.12.1.min.js"></script>
    <script src="<?php echo $base_url; ?>js/bootstrap.min.js"></script>
    <script src="<?php echo $base_url; ?>js/custom.js"></script>
    <style type="text/css">
    .input-error{
      border-bottom: 1px solid #ec2222 !important;
    }
    </style>
</head>

<body>
  <div class="wrapper">
    <div class="inner-wrapper">
      <div class="mainform-format">
        <img src="<?php echo $base_url; ?>images/logo.png" alt="logo" />
        <div class="mainter-form">
          <h4>Request Payment Form</h4>
          <form action="<?php echo $base_url; ?>paynow/Makepayment/submit" method="post" id="paymentform">
            <div class="myform-group">
              <label>Full Name</label>
              <input type="text" name="fullname" id="fullname" maxlength="40" value="<?= $requestInfo->full_name; ?>" placeholder="Full Name" />
              <img src="<?php echo $base_url; ?>images/name-icon.png" alt="" />
            </div>
            <div class="myform-group">
              <label>Email</label>
              <input type="email" name="email" id="email" maxlength="60" value="<?= $requestInfo->email; ?>" placeholder="Email" />
              <img src="<?php echo $base_url; ?>images/emial-icon.png" alt="" />
            </div>
            <div class="myform-group">
            <label>Phone Number</label>
            <input type="text" name="phone" id="phone" maxlength="12" value="<?= $requestInfo->mobile_no; ?>" class="number" placeholder="Phone Number" />
            <img src="<?php echo $base_url; ?>images/phone-num.png" alt="" />
            </div>
            <div class="checkmystatus">
            <div class="checkmystatus-listinf">
            <input type="radio" checked value="<?= $requestInfo->confirm_budget; ?>" name="amount" id="amount" />
            <label for="amount">RM <?= $requestInfo->confirm_budget; ?></label>
            </div>
            </div>
            <!-- <div class="myform-checkbox">
              <input type="checkbox" id="anonymous" name="anonymous" value="1" />
              <label for="anonymous">I want to remain anonymous.</label>
            </div> -->
            <div class="myform-group-input">
              <input type="submit" onclick="return formSubmit();" value="Continue" />
              <input type="hidden" value="submit" name="submit"/>
              <input type="hidden" name="form_request" id="form_request" value="request"/>
              <input type="hidden" value="<?= $uuID; ?>" name="uuID"/>
            </div>  
          </form>
          <div class="importsocial">
            <p>By continuing, you agree to our <a target="_blank" href="<?php echo $front_url; ?>termsandconditions">Terms & Conditions. </a></p>
            <a target="_blank" href="https://www.facebook.com/buddeyapp/"><img src="<?php echo $base_url; ?>images/fb-form.png" alt="icon" /></a>
            <a target="_blank" href="<?php echo $front_url; ?>"><img src="<?php echo $base_url; ?>images/loco-form.png" alt="icon" /></a>
          </div>
        </div>
      </div>
      <div class="footer">
        <p>We accept payment from Credit/Debit card and Internet banking (FPX)</p>
        <a href="#"><img src="<?php echo $base_url; ?>images/footer-logo.png" alt="bank-master" /></a>
      </div>
    </div>
  </div>

<script type="text/javascript">
$('.number').keypress(function(event) {
  if (event.which == 8 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46) {
      return true;
  }else if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
      event.preventDefault();
  }
});
function formSubmit() {
  var next_step = 1;
  var data      = $( 'form#paymentform' ).serialize();
  var fullname  = $( 'form#paymentform #fullname' ).val();
  var email     = $( 'form#paymentform #email' ).val();
  var phone     = $( 'form#paymentform #phone' ).val();
  var amount    = $('input[name="amount"]:checked').val();
  var anonymous = $( 'form#paymentform #anonymous' ).is(":checked");
  $("#fullname").removeClass('input-error');
  $("#email").removeClass('input-error');
  $("#phone").removeClass('input-error');
  $("#amount").removeClass('input-error');
  if(anonymous == 1){
  }else{
    if(fullname == ''){
      $("#fullname").addClass('input-error');
    }
    if (!ValidateEmail(email)) {
      $("#email").addClass('input-error');
      next_step = 0;
    }
    if(phone == ''){
      $("#phone").addClass('input-error');
      next_step = 0;
    }else{
      if(!$.isNumeric(phone)){
        $("#phone").addClass('input-error');
        next_step = 0;
      }
    }
  }
  if ($('input[name="amount"]:checked').length == 0) {
    $("#amount").addClass('input-error');
    next_step = 0;
  }else{
    if(!$.isNumeric(amount)){
      $("#amount").addClass('input-error');
      next_step = 0;
    }
    if(amount < 5 || amount > 10000){
      $("#amount").addClass('input-error');
      next_step = 0;
    }
  }
  if(next_step == 0){ return false; }
  if(next_step){
    $( '#paymentform' ).submit();
    return true;
  }
}
function ValidateEmail(email) {
  var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
  return expr.test(email);
}
</script>
</body>
</html>