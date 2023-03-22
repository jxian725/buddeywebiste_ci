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
  <title>Buddey Partner | Login</title>
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
  <link rel="stylesheet" href="<?php echo $admin_url; ?>css/custom.css">
 
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition partner-login">
    <div class="login-box">
        <div class="login-logo">
            <a href="<?= base_url();?>"><img src="<?= $dir_url;?>images/logo_white.png" style="height: 70px;"></a>
            <h3 style="color: #fff;">Venue Partner login here</h3>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <?php echo form_open( '#', 'class="form-signin" autocomplete="off"' ); ?>
                <?php 
                    if( $this->session->flashdata( 'err_msg' ) ) { ?>
                        <div class="text-danger">
                            <?php echo $this->session->flashdata( 'err_msg' ); ?>
                        </div>  
                    <?php 
                    } ?>
                <div class="form-group has-feedback">
                    <?php
                    $attr = array(
                                'class'         => 'form-control',
                                'autocomplete'  => 'off',
                                'id'            => 'email',
                                'name'          => 'email',
                                'placeholder'   => 'email',
                                'value'         => set_value('email'),
                            );
                    echo form_input( $attr );
                    ?>
                    <span class="fa fa-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <?php
                    $attr = array(
                                'class'         => 'form-control',
                                'id'            => 'password',
                                'autocomplete'  => 'off',
                                'name'          => 'password',
                                'placeholder'   => 'Password',
                                'type'          => 'password'
                            );
                    echo form_input( $attr );
                    ?>
                    <span class="fa fa-key form-control-feedback"></span>
                </div>
                <div class="row">
                  <div class="col-xs-12">
                      <a href="<?php echo $base_url; ?>partner/forgot">Forgot Password ?</a>
                  </div>
                </div>
                <div class="form-group has-feedback"></div>
                <div class="row">
                  <div class="col-xs-8">
                  </div>
                    <!-- /.col -->
                  <div class="col-xs-4">
                      <?php
                      $attr = array(
                                    'class'   => 'btn btn-primary pull-right',
                                    'id'      => 'login_submit',
                                    'name'    => 'login_submit',
                                    'content' => 'Login',
                                    'onClick' => 'loginValidate();'
                                );
                      echo form_button( $attr );
                      ?>
                  </div>
                  
                </div>    
        <!-- /.col -->
      </div>      
    <?php echo form_close(); ?>
  </div>
  <div class="col-xs-12">
      <p class="text-center" style="color: #fff;">New Talent account sign up <a href="<?= $dir_url;?>talent/register" style="color: #2ECCFA;">here</a></p>
      <p class="text-center" style="color: #fff;">New Venue Partner account sign up <a href="<?= $dir_url;?>partner/register" style="color: #2ECCFA;">here</a></p>
  </div>
  <!-- /.login-box-body -->
</div>
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
    var email    = $( '#email' ).val();
    var password = $( '#password' ).val();
    var data = { 'email' : email, 'password' : password }
    $.ajax({
        type    : 'POST',
        url     : baseurl + 'partner/login/validate',
        data    : data,
        success : function( msg ) {
            if( msg == 1 ) {
                window.location.href = baseurl + "partner/venue";
            }else{
                alert( msg );
                location.reload();
            }
        }
    });
}
</script>
</body>
</html>

