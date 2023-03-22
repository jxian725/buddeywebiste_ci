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
  <title>Buddey Talent | <?php echo $title; ?></title>
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
  <script src="<?php echo $assetUrl; ?>plugins/jquery/dist/emoj.js"></script>
  <script src="<?php echo $assetUrl; ?>js/toastr/toastr.min.js"></script> 
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
.sidebar span, .sidebar a{
  color: #000;
  font-size: 12px !important;
}
.sidebar-toggle{
  color: #fff;
}
.pull-left {
  float: left!important;
  margin-left: 11px;
}
.info{
  font-size: 15px;
  color: #fff;
}
.partner-name{
  font-size: 16px;
  color: #2ECCFA;
}
.skin-blue .main-sidebar, .skin-blue .left-side{
background-color: #ddd;
}
.skin-blue .main-header .navbar{
background-color: #007bff;
}
.skin-blue .main-header .logo{
background-color: #007bff;
}
.main-header .sidebar-toggle:hover{
color: #2ECCFA;
}
.main-header .sidebar-toggle{
background-color: #007bff;
}
.skin-blue .main-header.logo>a:hover {
color: #007bff;
}
.sidebar li.active>a>span{
  color: #2ECCFA;
}
.sidebar .active img{
  color: #2ECCFA;
}
.skin-blue .sidebar-menu>li:hover>a, .skin-blue .sidebar-menu>li.active>a, .skin-blue .sidebar-menu>li.menu-open>a {
color: #2ECCFA;
background: #ddd;
}
.skin-blue .sidebar-menu .treeview-menu>li>a {
background-color: #fff;
}
.skin-blue .sidebar-menu>li>.treeview-menu {
margin: 0 1px;
background: #2ECCFA;
}
.sidebar-menu>li>a {
    padding: 12px 5px 12px 3px;
    display: block;
}
.main-header .logo2 {
    -webkit-transition: width .3s ease-in-out;
    -o-transition: width .3s ease-in-out;
    transition: width .3s ease-in-out;
    display: block;
    float: left;
    height: 50px;
    font-size: 20px;
    line-height: 50px;
}
.main-header .sidebar-toggle:focus, .main-header .sidebar-toggle:active {
    background: #037afb;
    color: #fff;
}
@media (min-width: 768px){
  .sidebar-mini.sidebar-collapse .sidebar-menu>li {
    padding: 5px 0px 5px 0px;
  }
}
</style>
<body class="hold-transition skin-blue sidebar-mini">

<div class="wrapper">

  <header class="main-header">

    <!-- Logo -->
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button" style="">
      <span class="sr-only">Toggle navigation</span>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" style="margin-left: 42px;">
      <!-- Sidebar toggle button-->
      
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu" style="height: 50px;float: left;">
        <span class="logo-lg">
          <a href="<?php echo $dirUrl; ?>talent/forums" class="logo2">
            <img src="<?=$dirUrl;?>images/talent_logo.png" class="img" alt="Logo">
          </a>
        </span>
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"></a>
          </li>
        </ul>
      </div>

    </nav>
  </header>

  <!-- BEGIN SIDEBAR -->
  <?php $this->load->view( 'talent/common/navigation', $navigation ); ?>
  <!-- END SIDEBAR -->