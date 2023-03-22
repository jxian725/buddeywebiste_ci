<?php
$base_url  = $this->config->item( 'base_url' );
$admin_url = $this->config->item( 'admin_url' );
$this->load->library('encrypt');
error_reporting(0);
$key    = $this->config->item( 'encryption_key' );

?>


<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->

    <head>
        <meta charset="utf-8" />
        <title>MIDASCOM | Forgot Password</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="../assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $this->config->item('admin_url'); ?>l_asset/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
        <link href="../assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="../assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="../assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="<?php echo $this->config->item('admin_url'); ?>l_asset/css/login.min.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="<?php echo $this->config->item('admin_url'); ?>l_asset/css/custom.css" rel="stylesheet">
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="<?php echo $this->config->item('admin_url'); ?>l_asset/img/logo_favi.png">
      </head>
    <!-- END HEAD -->

    <body class="form-signin-wrapper login">
        <!-- BEGIN LOGO -->
        <div class="logo">
            <a href="javascript:;">
                <img style="display: inline;" class="login-logo img-responsive" src="<?php echo $base_url; ?>l_asset/img/logo-login.png" alt="" />
            </a>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN LOGIN -->
        <div class="content">
            <?php echo form_open( '#', 'class="forget-form" autocomplete="off"' ); ?>
            <form class="forget-form" action="index.html" method="post" novalidate="novalidate" style="display: block;">
                <h3 class="font-green">Forget Password ?</h3>
                <p> Enter your e-mail address below to reset your password. </p>
                <div class="form-group">
                    <?php
                      $attr = array(
                                  'class'         => 'form-control placeholder-no-fix',
                                  'autocomplete'  => 'off',
                                  'id'            => 'useremail',
                                  'name'          => 'useremail',
                                  'type'          => 'email'
                              );
                      echo form_input( $attr );
                    ?>
                <div class="form-actions">
                    <a href="<?php echo $assetUrl; ?>login" id="back-btn" class="btn btn-default">Back</a>
                    <?php
                    $attr = array(
                                  'class'         => 'btn btn-success uppercase pull-right',
                                  'id'            => 'login_submit',
                                  'name'          => 'forgot_submit',
                                  'content'       => 'Reset',
                                  'onClick'       => 'forgotValidate();'
                              );
                    echo form_button( $attr );
                    ?>
                </div>
            <?php echo form_close(); ?>
        </div>
        <div class="copyright"> 2017 © Chinlai. </div>
        <!-- end: Content -->
      <script> var adminurl = '<?php echo $this->config->item('admin_url'); ?>'; </script>
      <!-- start: Javascript -->
      <script src="<?php echo $this->config->item('admin_url'); ?>l_asset/js/jquery.min.js"></script>

      <!-- custom -->
      <!--<script src="l_asset/js/main.js"></script>-->
      <script src="<?php echo $this->config->item('admin_url'); ?>l_asset/js/custom.js" type="text/javascript"></script>
      
     <!-- end: Javascript -->
    </body>
</html>