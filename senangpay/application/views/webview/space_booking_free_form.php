<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$base_url   = $this->config->item( 'base_url' );
$front_url  = $this->config->item( 'front_url' );
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
          <h4 style="margin-bottom: 15px;">Space Booking Form</h4>
          <form action="<?php echo $base_url; ?>space/Makepayment/freeBooking" method="post" id="paymentform">
            <div class="checkmystatus">
            <div class="checkmystatus-listinf">
            <input type="radio" checked value="<?= $spaceInfo->partnerFees; ?>" name="amount" id="amount" />
            <label for="amount">RM <?= $spaceInfo->partnerFees; ?></label>
            </div>
            </div>
            <!-- <div class="myform-checkbox">
              <input type="checkbox" id="anonymous" name="anonymous" value="1" />
              <label for="anonymous">I want to remain anonymous.</label>
            </div> -->
            <div class="myform-group-input">
              <input type="submit" onclick="return formSubmit();" value="Complete Booking" />
              <input type="hidden" value="submit" name="submit"/>
              <input type="hidden" name="form_request" id="form_request" value="space"/>
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
function formSubmit() {
  $( '#paymentform' ).submit();
  return true;
}
</script>
</body>
</html>