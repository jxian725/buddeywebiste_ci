<?php
$dirUrl     = $this->config->item( 'dir_url' ); 
$assetUrl   = $this->config->item( 'admin_dir_url' );
global $permission_arr;

$page = $this->uri->segment(3);
$dashactive = ''; $feedbackactive = ''; $podractive = '';
if($page == '' || $page == 'client'){
  $dashactive       = 'active';
} else if($page == 'buskerspod'){
  $podractive = 'active';
} else if( $page == 'feedback' ) {
  $feedbackactive   = 'active';
} else{
  $dashactive    = 'active';
}
?>
<!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <p class="info">Welcome</p>
          <p class="partner-name"><?=$this->session->userdata( 'PARTNER_NAME' );?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="<?=$dashactive;?>">
          <a href="<?=$dirUrl;?>partner/venue">
            <i class="fa fa-user"></i> <span>Profile</span>
          </a>
        </li>
        <li class="<?=$podractive;?>">
          <a href="<?=$dirUrl;?>partner/venue/buskerspod">
            <i class="fa fa-cubes"></i> <span>Buskers Pod Management</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-green"></small>
            </span>
          </a>
        </li>
        <li class="<?=$feedbackactive;?>">
          <a href="<?=$dirUrl;?>partner/venue/feedback">
            <i class="fa fa-life-ring"></i> <span>Feedback</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-green"></small>
            </span>
          </a>
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>