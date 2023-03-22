<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl   = $this->config->item( 'admin_dir_url' );
$dirUrl     = $this->config->item( 'dir_url' );
$site_name  = $this->config->item( 'site_name' );
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Buddey Partner | <?php echo $title; ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo $assetUrl; ?>plugins/bootstrap/dist/css/bootstrap.min.css">

  <link href="<?php echo $assetUrl; ?>plugins/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo $assetUrl; ?>plugins/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?php echo $assetUrl; ?>js/toastr/toastr.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo $assetUrl; ?>plugins/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo $assetUrl; ?>css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo $assetUrl; ?>css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="<?php echo $assetUrl; ?>css/daterangepicker-bs3.css">
  <link rel="stylesheet" href="<?php echo $assetUrl; ?>css/custom.css">
  <!-- jQuery 3 -->
  <script src="<?php echo $assetUrl; ?>plugins/jquery/dist/jquery.min.js"></script>
  <script> var baseurl = '<?php echo $this->config->item('base_url'); ?>'; </script>
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<style type="text/css">
.logo-mini{
  width: 0.5px;
}
.logo-lg{
  width: 0.5px;
}
.sidebar-toggle{
  color: #fff;
}
.pull-left {
  float: left!important;
  margin-left: 11px;
}
.info{
  font-size: 18px;
  color: #fff;
}
.partner-name{
  font-size: 16px;
  color: #2ECCFA;
}
</style>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <header class="main-header">
     <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
    <!-- Logo -->
    <a href="<?php echo $dirUrl; ?>partner/venue" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><img src="<?=$dirUrl;?>images/partner_logo.png" class="img" alt="User Image"></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><img src="<?=$dirUrl;?>images/partner_logo.png" class="img" alt="User Image"></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!--<img src="<?=$assetUrl;?>img/avatar5.png" class="user-image" alt="User Image">-->
              <span class="hidden-xs"><?=$this->session->userdata( 'PARTNER_NAME' );?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
               <!-- <img src="<?=$assetUrl;?>img/avatar5.png" class="img-circle" alt="User Image">-->
                <p>
                  <?=$this->session->userdata( 'PARTNER_NAME' );?>
                  <small><?=date('jS \of M Y h:i:s A'); ?></small>
                </p>
              </li>
              <!-- Menu Body -->
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="<?= base_url(); ?>partner/logout" class="btn btn-default btn-flat">Logout</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  <!-- BEGIN SIDEBAR -->
  <?php $this->load->view( 'partner/common/navigation', $navigation ); ?>
  <!-- END SIDEBAR -->