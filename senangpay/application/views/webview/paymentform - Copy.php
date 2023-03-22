<?php
defined('BASEPATH') OR exit('No direct script access allowed');
echo $assetUrl     = $this->config->item( 'admin_url' );
$site_name    = $this->config->item( 'site_name' );
$dirUrl       = $this->config->item( 'dir_url' );
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Buddey | Payment Page</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo $dirUrl; ?>plugins/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo $dirUrl; ?>plugins/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo $dirUrl; ?>plugins/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo $dirUrl; ?>css/AdminLTE.min.css">
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <style type="text/css">
  .input-error{
    border-color: #ec2222;
  }
  .btn-flat{
    background: #01c1c1 !important;
    color: #fff;
    border-color: #01c1c1;
  }
  </style>
</head>
<body class="hold-transition register-page">
<div class="register-box">
  <div class="register-logo">
    <a href="http://buddeyapp.com/">
      <img src="<?php echo $dirUrl; ?>img/mobile_logo.png" style="width: 200px !important;">
    </a>
  </div>
  <div class="register-box-body">
    <p class="login-box-msg">Payment Form</p>
    <form action="<?php echo $assetUrl; ?>Makepayment/submit" method="post" id="paymentform">
      <div class="form-group has-feedback">
        <input type="text" name="fullname" id="fullname" maxlength="30" class="form-control" placeholder="Full name">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="email" name="email" id="email" maxlength="60" class="form-control" placeholder="Email">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="text" name="phone" id="phone" maxlength="12" class="form-control number" placeholder="Phone Number">
        <span class="glyphicon glyphicon-phone form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <div><label>Select a top-up value (RM)</label></div>
        <div class="radio">
          <label><input type="radio" name="amount" id="amount1" value="5">5&nbsp;&nbsp;</label>
          <label><input type="radio" name="amount" id="amount2" value="10" checked>10&nbsp;&nbsp;</label>
          <label><input type="radio" name="amount" id="amount3" value="15">15&nbsp;&nbsp;</label>
        </div>
      </div>
      <div class="form-group has-feedback">
        <div class="checkbox">
          <label>
            <input type="checkbox" id="anonymous" name="anonymous" value="1"> I want my donations to remain anonymous
          </label>
        </div>
      </div>
      <div class="row">
        <!-- /.col -->
        <div class="col-xs-12">
          <input type="hidden" value="submit" name="submit"/>
          <input type="hidden" value="<?= $uuID; ?>" name="uuID"/>
          <center><button type="submit" onclick="return formSubmit();" class="btn btn-primary btn-block btn-flat">Continue</button></center>
          <p></p>
          <p>By continuing you agree to our Terms and Conditions</p>
        </div>
        <!-- /.col -->
      </div>
    </form>
  </div>
  <!-- /.form-box -->
</div>
<!-- /.register-box -->
<!-- jQuery 3 -->
<script src="<?php echo $dirUrl; ?>plugins/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo $dirUrl; ?>plugins/bootstrap/dist/js/bootstrap.min.js"></script>

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
    if(amount < 5 || amount > 15){
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