<?php
$dirUrl     = $this->config->item( 'dir_url' );

$host_total       = $this->Commonmodel->DHostTotalCount();
$pending_host_total = $this->Commonmodel->DPendingHostTotalCount();
$guest_total      = $this->Commonmodel->guest_total_count();
$newsletter_total = $this->Commonmodel->newsletter_total_count();

$page           = $this->uri->segment(2);
$dashactive     = '';
$ptripactive    = '';
$ctripactive    = '';
$profileactive  = '';
if($page == 'pendingrequest'){
  $ptripactive      = 'active';
}else if($page == 'completedtrip'){
  $ctripactive      = 'active';
}else if($page == 'editprofile'){
  $profileactive    = 'active';
}else{
  $dashactive       = 'active';
}
?>
<!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?=$dirUrl;?>img/avatar5.png" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?=$this->session->userdata( 'USER_NAME' );?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="<?=$dashactive;?>">
          <a href="<?=$this->config->item( 'hostportal_url' );?>">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>
        <li class="<?=$ptripactive;?>">
          <a href="<?=$this->config->item( 'hostportal_url' );?>pendingrequest">
            <i class="fa fa-user-o"></i> <span>Pending Requests</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-green"></small>
            </span>
          </a>
        </li>
        <li class="<?=$ctripactive;?>">
          <a href="<?=$this->config->item( 'hostportal_url' );?>completedtrip">
            <i class="fa fa-user-o"></i> <span>Completed Bookings</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-green"></small>
            </span>
          </a>
        </li>
        <li class="<?=$profileactive;?>">
          <a href="<?=$this->config->item( 'hostportal_url' );?>editprofile">
            <i class="fa fa-user-o"></i> <span>Profile</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-green"></small>
            </span>
          </a>
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>