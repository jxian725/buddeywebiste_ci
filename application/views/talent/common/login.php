<?php
$base_url   = $this->config->item( 'base_url' );  
$admin_url  = $this->config->item( 'admin_dir_url' );
$dir_url    = $this->config->item( 'dir_url' );
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
  <title>Buddey Talent | Login</title>
  <link rel="icon" type="image/jpeg" href="<?php echo $dir_url; ?>assets/img/favicon.png" sizes="16x16">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo $admin_url; ?>plugins/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo $admin_url; ?>plugins/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo $admin_url; ?>plugins/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo $admin_url; ?>css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo $admin_url; ?>plugins/iCheck/square/blue.css">
  <link rel="stylesheet" href="<?php echo $admin_url; ?>js/toastr/toastr.min.css">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <!-- Google Font -->
</head>
<style type="text/css">
.login-page, .register-page {
    background: transparent;
}
.phone-input{ margin-bottom: 20px; }
input.error{
  border: 1px solid #FA3C3C;
}
.has-error-2 {
  border-color: #DA4453 !important;
}
</style>
<body class="hold-transition login-page">

<div class="login-box">
  <div class="login-logo">
    <a href="<?= base_url();?>"><img src="<?= $dir_url;?>images/talent_login.png" style="height: 80px;"></a>
    <h3 class="text-center" style="color: #2ECCFA;">Talent login here</h3>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">

    <form class="form-horizontal form" id="talentForm" role="form" method="post" novalidate="">
      <div class="form-group">
          <?php 
          if( $this->session->flashdata( 'err_msg' ) ) { ?>
            <div class="text-danger">
                <?php echo $this->session->flashdata( 'err_msg' ); ?>
            </div>  
          <?php } ?>
      </div>

      <div id="step1">
        <div class="row">
          <div class="col-sm-12">
            <div class="input-group phone-input">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="type-text"><?= COUNTRY_CODE; ?></span></button>
                </span>
                <input type="hidden" name="country_code" id="country_code" value="<?= COUNTRY_CODE; ?>" />
                <input type="text" class="form-control pnumber" autocomplete="off" maxlength="12" name="mobile_number" id="mobile_number" placeholder="Example 123456789">
            </div>
          </div>
          <div class="col-xs-12">
            <div class="checkbox icheck">
              <label class="text-left" for="privacy">
                  <input type="checkbox" class="privacy" name="privacy" id="privacy" value="1" style="width: auto;">
                  <span>&nbsp;&nbsp;&nbsp;I have read, understand and agree to the <a href="<?php echo $dir_url; ?>termsandconditions">Terms and Conditions</a> and <a href="<?php echo $dir_url; ?>privacypolicy">Privacy policy</a></span>
              </label>
            </div>
          </div>
        </div>
      </div>

      <div><div id="recaptcha-container"></div></div>
      <div class="form-group" id="otp_sec" style="display: none;">
          <label for="otp" class="control-label">Enter OTP</label>
          <input type="text" name="otp" id="otp" class="form-control number" autocomplete="off" maxlength="6" placeholder="Enter the 6 digits OTP Number">
          <div class="otptime" id="otptime">
            <h5><b>Didn't Receive?</b></h5>
            <div id="resendOtpTime" style="color: #51cdfa;"></div>
          </div>
      </div>

      <div class="row">
        <!-- /.col -->
        <div class="form-group pull-right" style="padding-top: 15px;">
          <div class="col-xs-12 pull-right">
            <input type="hidden" name="recaptcha_verify" id="recaptcha_verify" value="0">
            <button class="btn btn-default btn-login" disabled id="sendotp-button" onclick="return sendOtp();" type="button">Send OTP</button>
            <button class="btn btn-success btn-login" style="display: none;" id="verify-button" onclick="return verifyOtp();" type="button">Verify OTP</button>
          </div>
        </div>
        <!-- /.col -->
      </div>
    </form>


    <p class="text-center">New Talent account sign up <a href="<?= $dir_url;?>talent/register">here</a></p>
    <p class="text-center">New Venue Partner account sign up <a href="<?= $dir_url;?>partner/register">here</a></p>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
<script> var baseurl = '<?= $base_url; ?>'; </script>
<script src="<?php echo $admin_url; ?>plugins/jquery/dist/jquery.min.js"></script>
<script src="<?php echo $admin_url; ?>plugins/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo $admin_url; ?>plugins/iCheck/icheck.min.js"></script>
<script src="<?php echo $admin_url; ?>js/toastr/toastr.min.js"></script> 
<!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/7.14.0/firebase-app.js"></script>
<!-- Add additional services that you want to use -->
<script src="https://www.gstatic.com/firebasejs/7.14.0/firebase-auth.js"></script>
<!-- TODO: Add SDKs for Firebase products that you want to use
     https://firebase.google.com/docs/web/setup#available-libraries -->
<script src="https://www.gstatic.com/firebasejs/7.14.0/firebase-analytics.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>
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

$("#privacy").on("ifChanged", function(){
    if($(this).is(':checked')) {
      $("#sendotp-button").addClass("btn-primary");
      $("#sendotp-button").removeClass("btn-default");
      $("#sendotp-button").prop('disabled', false);
    }else{
      $("#sendotp-button").addClass("btn-default");
      $("#sendotp-button").removeClass("btn-primary");
      $("#sendotp-button").prop('disabled', true);
    }
});
$(document).on('change', '.privacy', function() {
  if ($('input[name="privacy"]').is(':checked')) {
    $("#sendotp-button").addClass("btn-primary");
    $("#sendotp-button").removeClass("disabled");
  }else{
    $("#sendotp-button").addClass("disabled btn-default");
    $("#sendotp-button").removeClass("btn-primary");
  }
});
</script>

<script type="text/javascript">
  // Your web app's Firebase configuration

  var firebaseConfig = {
    apiKey: "AIzaSyALZgtXjM9zRUQ6TTxLRcqffrkHd3dBSU8",
    authDomain: "buddeyguider-3f490.firebaseapp.com",
    databaseURL: "https://buddeyguider-3f490.firebaseio.com",
    projectId: "buddeyguider-3f490",
    storageBucket: "buddeyguider-3f490.appspot.com",
    messagingSenderId: "77166851485",
    appId: "1:77166851485:web:84768db672974ea59beb74",
    measurementId: "G-1GDYRWLFD2"
  };
  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);
  firebase.analytics();
</script>

<script>
firebase.auth().languageCode = 'en';
// To apply the default browser preference instead of explicitly setting it.
// firebase.auth().useDeviceLanguage();

window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('sendotp-button', {
  'size': 'invisible',
  'callback': function(response) {
    // reCAPTCHA solved, allow signInWithPhoneNumber.
    onSignInSubmit();
  }
});

//window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container');

window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
  'size': 'invisible', //normal
  'callback': function(response) {
    $('#recaptcha_verify').val(1);
    console.log('reCAPTCHA solved');
    // reCAPTCHA solved, allow signInWithPhoneNumber.
  },
  'expired-callback': function() { console.log('reCAPTCHA expired');
    // Response expired. Ask user to solve reCAPTCHA again.
  }
});

recaptchaVerifier.render().then(function(widgetId) {
  window.recaptchaWidgetId = widgetId;
});

// Or, if you haven't stored the widget ID:
window.recaptchaVerifier.render().then(function(widgetId) {
  grecaptcha.reset(widgetId);
});

</script>

<script type="text/javascript">

function sendOtp(){
    var mobile_number = $('#mobile_number').val();
    var country_code  = $('#country_code').val();

    $('#mobile_number').removeClass('has-error-2');
    if(mobile_number == ''){
      //alert('Contact Number Cannot be empty');
      $('#mobile_number').addClass('has-error-2');
      $('#mobile_number').focus();
      return false;
    }else{
      if(mobile_number.substr(0, 1) == 0){
        //alert('Please enter valid Contact Number');
        $('#mobile_number').addClass('has-error-2');
        $('#mobile_number').focus();
        return false;
      }else if(mobile_number.length < 8 || mobile_number.length > 12){
        //alert('Please enter valid Contact Number');
        $('#mobile_number').addClass('has-error-2');
        $('#mobile_number').focus();
        return false;
      }
    }
    if(!$('#privacy').is(':checked')) {
        toastr.error( 'Please indicate that you have read and agree to the Terms and Conditions','Error' );
        return false;
    }
    if(mobile_number){
        var phoneNumber = country_code + mobile_number;
        $.ajax({
            url: baseurl + 'talent/login/validate',
            data: { "mobile_number": mobile_number },
            type: "post",
            dataType: "json",
            success: function (data) {
                if(data.status == 1){
                    var appVerifier = window.recaptchaVerifier;
                    //if($('#recaptcha_verify').val() == 0){ alert('reCAPTCHA Verification failed.'); }
                    
                    firebase.auth().signInWithPhoneNumber(phoneNumber, appVerifier)
                    .then(function (confirmationResult) {
                        $('#step1').hide();
                        $('#otp_sec').show();
                        //$('#sendotp-button').hide();
                        $('#sendotp-button').prop('disabled', true);
                        $('#verify-button').show();
                        
                        $('#sendotp-button').html('Resend OTP');
                        $('#resendOtpTime').html('');
                        var timeleft = 60;
                        setInterval(function(){
                          if (timeleft > 0) {
                            timeleft--;
                            $('#resendOtpTime').html('Request again '+timeleft+'s');
                          }else{
                            $('#sendotp-button').prop('disabled', false);
                          }
                        }, 1000)
                        //$('#recaptcha_verify').val(0);
                        //grecaptcha.reset(window.recaptchaWidgetId);
                        window.confirmationResult = confirmationResult;
                    }).catch(function (error) {
                      toastr.error( error.message,'Error' );
                    });
                }else{
                    toastr.error( data.message,'Error' );
                    return false;
                }
            }
        });
    }
}
function verifyOtp(){
    var code = $('#otp').val();
    var phoneNumber = $('#mobile_number').val();
    if(code.length != 6){
      toastr.error( 'Enter valid OTP.','Error' );
      return false;
    }
    const credential = firebase.auth.PhoneAuthProvider.credential(confirmationResult.verificationId, code);
    let user;
    firebase
        .auth()
        .signInWithCredential(credential)
        .then(result => {
            // User signed in successfully.
            user = result.user;
            //LOGIN NOW
            //location.reload();
            //window.location.href = baseurl + "talent/venue/otp";
            $.ajax({
              url: baseurl + 'talent/login/login_now',
              data: { "mobile_number": phoneNumber, "uid": user.uid, "refreshToken": user.refreshToken },
              type: "post",
              dataType: "json",
              beforeSend: function() { 
                  $("#verify-button").html('<img src="'+baseurl+'assets/img/input-spinner.gif"> Loading...');
                  $("#verify-button").prop('disabled', true);
                  $('#talentForm').css("opacity",".5");
              },
              success: function (data) {
                $("#verify-button").html('Verify OTP');
                $("#verify-button").prop('disabled', false);
                $('#talentForm').css("opacity","");
                
                if(data.status == 'success'){
                  sessionStorage.setItem("counter", '');
                    toastr.success( data.msg,'Success' );
                    setTimeout(function(){
                        window.location.href = data.url;
                    }, 1000);
                }else{
                  toastr.error( data.msg,'Error' );
                }
              }
            });
        })
        .catch(error => { toastr.error( error.message,'Error' ); });
}

sessionStorage.setItem("counter", "");
sessionStorage.setItem("orv_counter", "");
sessionStorage.setItem("set_refresh", "");
</script>
</body>
</html>
