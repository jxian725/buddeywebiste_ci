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
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <!-- Google Font -->
  <link rel="stylesheet" href="<?php echo $admin_url; ?>plugins/bootstrap-datepicker/bootstrap-datepicker.min.css">
</head>
<style type="text/css">
.box {
position: relative;
border-radius: 0px; 
border-top: 0px solid #d2d6de;
margin: 200px auto;
padding: 10px;
width: 75%;
box-shadow: 0 0px 0px rgba(0,0,0,0.1);
}
input.error{
  border: 1px solid #FA3C3C;
}
.talent-login{
  background: url("../img/talent_screen.png") no-repeat center center fixed; 
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
}
body {
font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
font-size: 14px;
line-height: 1.42857143;
color: #333;
/background-color: unset;
}
</style>
<section class="cta_part talent-login">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-12">
        <div class="cta_part_iner">
          <div class="cta_part_text center_div">
            <form class="form-horizontal form" id="talentForm" role="form" method="post" novalidate="">
              <div class="box row-fluid"> 
                <center><h1 style="color: #2ECCFA;">TALENT LOGIN</b></h1></center>
                <!-- /.login-logo -->
                <div class="step">
                  <div class="form-group">
                    <div class="col-md-12">
                      <p>Please wait a moment while we sent the OTP to your registered number.</p>
                    </div>  
                  </div>  
                  <div class="form-group">
                    <div class="col-md-12">
                        <input class="number form-control" type="text" class="form-control number" maxlength="12" name="otp" id="otp" placeholder="Enter the 6 digit OTP e.g. 000000">
                    </div>    
                  </div>  
                    <?php 
                      if( $this->session->flashdata( 'err_msg' ) ) { ?>
                          <div class="text-danger">
                              <?php echo $this->session->flashdata( 'err_msg' ); ?>
                          </div>  
                    <?php } ?>  
                  <div class="form-group">
                    <div class="row">
                      <label class="col-md-10 text-left" for="privacy">
                        <br><b>Didn't receive ?</b></br>
                        <?php $mobile = $this->session->userdata['TALENT_MOBILE']; ?>
                        <input type="hidden" name="mobile" id="mobile" value="<?= $mobile; ?>">
                        <a href="javascript:;" onclick="return loginValidate();">Request again <span id="timer"></span></a>
                      </label>
                      <div class="col-sm-2" style="padding-right: 0px;">
                        <button type="button" id="login" class="btn btn-primary" onclick="return otpValidate();" style="background-color: #1215fa;" >Verify</button>
                      </div>
                    </div>    
                  </div>   
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- /.login-box -->
<script> var baseurl = '<?php echo $this->config->item('base_url'); ?>'; </script>
<!-- jQuery 3 -->
<script src="<?php echo $admin_url; ?>plugins/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo $admin_url; ?>plugins/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="<?php echo $admin_url; ?>plugins/iCheck/icheck.min.js"></script>
<script type="text/javascript">
//LOGIN VALIDATION
function loginValidate() {
    var mobile = $( '#mobile' ).val();
    var data = { 'mobile' : mobile }
    $.ajax({
        type    : 'POST',
        url     : baseurl + 'talent/login/validate',
        data    : data,
        success : function( msg ) {
            if( msg == 1 ) {
                window.location.href = baseurl + "talent/venue";
            }else{
                alert( msg );
                location.reload();
            }
        }
    });
}
function otpValidate() {
    var otp    = $( '#otp' ).val();
    var mobile = $( '#mobile' ).val();
    var data = { 'otp' : otp, 'mobile' : mobile}
    $.ajax({
        type    : 'POST',
        url     : baseurl + 'talent/otp/validate',
        data    : data,
        success : function( msg ) {
            if( msg == 1 ) {
                window.location.href = baseurl + "talent/venue";
            }else{
                alert( msg );
                location.reload();
            }
        }
    });
}
</script>
<script type="text/javascript">
 let timerOn = true;

function timer(remaining) {
  var m = Math.floor(remaining / 60);
  var s = remaining % 60;
  
  m = m < 10 ? '0' + m : m;
  s = s < 10 ? '0' + s : s;
  document.getElementById('timer').innerHTML = m + ':' + s;
  remaining -= 1;
  
  if(remaining >= 0 && timerOn) {
    setTimeout(function() {
        timer(remaining);
    }, 1000);
    return;
  }

  if(!timerOn) {
    // Do validate stuff here
    return;
  }
  
  // Do timeout stuff here
  alert('Timeout for otp');
}

timer(59);
</script>
</body>
</html>

