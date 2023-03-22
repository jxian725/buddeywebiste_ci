<?php
$dirUrl     = $this->config->item( 'dir_url' );
global $permission_arr;
$host_total     = $this->Commonmodel->DHostTotalCount();
$pending_host_total = $this->Commonmodel->DPendingHostTotalCount();
$guest_total      = $this->Commonmodel->guest_total_count();
$newsletter_total = $this->Commonmodel->newsletter_total_count();

$page             = $this->uri->segment(1);
$dashactive       = ''; $cityactive    = ''; $feedbackactive = ''; $user_tree = ''; $faqactive = '';
$requestoractive  = ''; $guideractive  = ''; $ptripactive    = ''; $ctripactive = ''; $qractive = '';
$newsletteractive = ''; $serviceactive = ''; $specialization = ''; $journey     = ''; $podractive = '';
$ppayoutactive    = ''; $gpayoutactive = ''; $cpayoutactive  = ''; $user        = ''; $role = ''; $cms = '';
$senangpayactive  = ''; $pguideractive = ''; $requestactive  = ''; $partneractive= ''; $podactive = '';
$license = ''; $experienceactive = '';
$news_tree = ''; $mgmnt_tree = ''; $prod_serv_tree = ''; $trans_pay_tree = ''; $venueactive = '';
if($page == '' || $page == 'client'){
  $dashactive       = 'active';
} else if($page == 'traveller'){
  $requestoractive = 'active';
  $user_tree = 'active';
} else if($page == 'guider'){
  $guideractive    = 'active';
  $user_tree = 'active';
} else if($page == 'venuepartner'){
  $venueactive    = 'active';
  $user_tree = 'active';
} else if($page == 'pendingguider'){
  $pguideractive   = 'active';
  $user_tree = 'active';
} else if($page == 'experience'){
  $experienceactive = 'active';
  $user_tree = 'active';
} else if($page == 'pendingtrip'){
  $ptripactive     = 'active';
  $trans_pay_tree  = 'active';
} else if($page == 'completedtrip'){
  $ctripactive     = 'active';
  $trans_pay_tree  = 'active';
} else if($page == 'qrscandonate'){
  $qractive        = 'active';
  $trans_pay_tree  = 'active';
} else if($page == 'senangpay_transaction'){
  $senangpayactive = 'active';
  $trans_pay_tree  = 'active';
} else if($page == 'guiderpayout'){
  $gpayoutactive   = 'active';
  $trans_pay_tree  = 'active';
} else if($page == 'pendingpayout'){
  $ppayoutactive   = 'active';
  $trans_pay_tree  = 'active';
} else if($page == 'completedpayout'){
  $cpayoutactive   = 'active';
  $trans_pay_tree  = 'active';
} else if( $page == 'buskerspod_report' ) {
  $podractive      = 'active';
  $trans_pay_tree  = 'active';
} else if($page == 'newsletter'){
  $newsletteractive = 'active';
  $news_tree        = 'active';
} else if( $page == 'journeys' ) {
  $journey          = 'active';
  $news_tree        = 'active';
} else if($page == 'service'){
  $serviceactive    = 'active';
} else if( $page == 'category' ) {
  $specialization   = 'active';
} else if( $page == 'license' ) {
  $license        = 'active';
} else if( $page == 'buskerspodlist' ) {
  $podactive      = 'active';
  $prod_serv_tree = 'active';
} else if($page == 'request'){
  $requestactive  = 'active';
  $prod_serv_tree = 'active';
} else if( $page == 'partners' ) {
  $partneractive  = 'active';
  $prod_serv_tree = 'active';
} else if( $page == 'cities' ) {
  $cityactive     = 'active';
  $prod_serv_tree = 'active';
} else if( $page == 'cms' ) {
  $cms            = 'active';
  $prod_serv_tree = 'active';
} else if( $page == 'feedback' ) {
  $feedbackactive   = 'active';
} else if( $page == 'faq' ) {
  $faqactive   = 'active';
} else if( $page == 'user' ) {
  $user        = 'active';
  $mgmnt_tree  = 'active';
} else if( $page == 'settings' ) {
  $role        = 'active';
  $mgmnt_tree  = 'active';
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
          <a href="<?=$this->config->item( 'admin_url' );?>">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>

        <li class="treeview <?=$user_tree;?>">
          <a href="#">
            <i class="fa fa-users"></i> <span>User Management</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
          <?php if( in_array( 'pendingguider', $permission_arr ) ) { ?>
            <li class="<?=$pguideractive;?>">
              <a href="<?=$this->config->item( 'admin_url' );?>pendingguider">
                <i class="fa fa-users"></i> <span>Pending <?= HOST_NAME; ?></span>
                <span class="pull-right-container">
                  <small class="label pull-right bg-green"><?=$pending_host_total;?></small>
                </span>
              </a>
            </li>
            <?php } ?>
            <?php if( in_array( 'experience', $permission_arr ) ) { ?>
            <li class="<?=$experienceactive;?>">
              <a href="<?=$this->config->item( 'admin_url' );?>experience">
                <i class="fa fa-users"></i> <span><?= HOST_NAME; ?> Experience</span>
              </a>
            </li>
            <?php } ?>
            
            <?php if( in_array( 'guider', $permission_arr ) ) { ?>
            <li class="<?=$guideractive;?>">
              <a href="<?=$this->config->item( 'admin_url' );?>guider">
                <i class="fa fa-user-o"></i> <span><?= HOST_NAME; ?></span>
                <span class="pull-right-container">
                  <small class="label pull-right bg-green"><?=$host_total;?></small>
                </span>
              </a>
            </li>
            <?php } ?>
            <?php if( in_array( 'traveller', $permission_arr ) ) { ?>
            <li class="<?=$requestoractive;?>">
              <a href="<?=$this->config->item( 'admin_url' );?>traveller">
                <i class="fa fa-user"></i> <span><?= GUEST_NAME; ?></span>
                <span class="pull-right-container">
                  <small class="label label-primary pull-right"><?=$guest_total;?></small>
                </span>
              </a>
            </li>
            <?php } ?>
            <li class="<?=$venueactive;?>">
              <a href="<?=$this->config->item( 'admin_url' );?>venuepartner">
                <i class="fa fa-building-o"></i> <span>Venue Partner</span>
                <span class="pull-right-container">
                  <small class="label label-primary pull-right"></small>
                </span>
              </a>
            </li>
          </ul>
        </li>

        <li class="treeview <?=$prod_serv_tree;?>">
          <a href="#">
            <i class="fa fa-cubes"></i> <span>Products & Services</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?=$podactive;?>">
              <a href="<?=$this->config->item( 'admin_url' );?>buskerspodlist">
                <i class="fa fa-music"></i> <span>Buskers Pod Tracker</span>
                <span class="pull-right-container">
                  <small class="label pull-right bg-green"></small>
                </span>
              </a>
            </li>
            <li class="<?=$requestactive;?>">
              <a href="<?=$this->config->item( 'admin_url' );?>request">
                <i class="fa fa-arrow-circle-o-right"></i> <span>Website Requests</span>
              </a>
            </li>
            <li class="<?=$partneractive;?>">
              <a href="<?=$this->config->item( 'admin_url' );?>partners">
                <i class="fa fa-building-o"></i> <span>Buskers Pod Management</span>
                <span class="pull-right-container">
                  <small class="label pull-right bg-green"></small>
                </span>
              </a>
            </li>
            <li class="<?=$cityactive;?>">
              <a href="<?=$this->config->item( 'admin_url' );?>cities">
                <i class="fa fa-map-marker"></i> <span>Manage Cities</span>
                <span class="pull-right-container">
                  <small class="label pull-right bg-green"></small>
                </span>
              </a>
            </li>
            <?php if( in_array( 'category', $permission_arr ) ) { ?>
            <li class="<?=$specialization;?>">
                <a href="<?=$this->config->item( 'admin_url' );?>category">
                  <i class="fa fa-cubes"></i> <span>Manage Category</span>
                  <span class="pull-right-container">
                    <small class="label pull-right bg-green"></small>
                  </span>
                </a>
            </li>
            <?php } ?>
            <li class="<?=$license;?>">
                <a href="<?=$this->config->item( 'admin_url' );?>license">
                  <i class="fa fa-cubes"></i> <span>Manage Verification</span>
                  <span class="pull-right-container">
                    <small class="label pull-right bg-green"></small>
                  </span>
                </a>
            </li>
            <?php if( in_array( 'cms', $permission_arr ) ) { ?>
            <li class="<?=$cms;?>">
              <a href="<?=$this->config->item( 'admin_url' );?>cms/pages">
                <i class="fa fa-book"></i> <span>CMS</span>
                <span class="pull-right-container">
                  <small class="label pull-right bg-green"></small>
                </span>
              </a>
            </li>
            <?php } ?>
          </ul>
        </li>

        <li class="treeview <?=$trans_pay_tree;?>">
          <a href="#">
            <i class="fa fa-money"></i> <span>Transaction & Payments</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?=$ptripactive;?>">
              <a href="<?=$this->config->item( 'admin_url' );?>pendingtrip">
                <i class="fa fa-user-o"></i> <span>Pending Bookings</span>
                <span class="pull-right-container">
                  <small class="label pull-right bg-green"></small>
                </span>
              </a>
            </li>
            <li class="<?=$ctripactive;?>">
              <a href="<?=$this->config->item( 'admin_url' );?>completedtrip">
                <i class="fa fa-user-o"></i> <span>Completed Bookings</span>
                <span class="pull-right-container">
                  <small class="label pull-right bg-green"></small>
                </span>
              </a>
            </li>
            <li class="<?=$qractive;?>">
              <a href="<?=$this->config->item( 'admin_url' );?>qrscandonate">
                <i class="fa fa-qrcode"></i> <span>Donations</span>
                <span class="pull-right-container">
                  <small class="label pull-right bg-green"></small>
                </span>
              </a>
            </li>
            <li class="<?=$senangpayactive;?>">
              <a href="<?=$this->config->item( 'admin_url' );?>senangpay_transaction">
                <i class="fa fa-history"></i> <span>Senangpay Transaction history</span>
                <span class="pull-right-container">
                  <small class="label pull-right bg-green"></small>
                </span>
              </a>
            </li>
            <li class="<?=$gpayoutactive;?>">
              <a href="<?=$this->config->item( 'admin_url' );?>guiderpayout">
                <i class="fa fa-money"></i> <span><?= HOST_NAME; ?> Payout</span>
                <span class="pull-right-container">
                  <small class="label pull-right bg-green"></small>
                </span>
              </a>
            </li>
            <li class="<?=$ppayoutactive;?>">
              <a href="<?=$this->config->item( 'admin_url' );?>pendingpayout">
                <i class="fa fa-money"></i> <span>Pending Payout</span>
                <span class="pull-right-container">
                  <small class="label pull-right bg-green"></small>
                </span>
              </a>
            </li>
            <li class="<?=$cpayoutactive;?>">
              <a href="<?=$this->config->item( 'admin_url' );?>completedpayout">
                <i class="fa fa-money"></i> <span>Completed Payout</span>
                <span class="pull-right-container">
                  <small class="label pull-right bg-green"></small>
                </span>
              </a>
            </li>
            <li class="<?=$podractive;?>">
              <a href="<?=$this->config->item( 'admin_url' );?>buskerspod_report">
                <i class="fa fa-music"></i> <span>Buskers Pod Report</span>
                <span class="pull-right-container">
                  <small class="label pull-right bg-green"></small>
                </span>
              </a>
            </li>

          </ul>
        </li>

        <li class="treeview <?=$news_tree;?>">
          <a href="#">
            <i class="fa fa-bullhorn"></i> <span>Newsroom</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <?php if( in_array( 'newsletter', $permission_arr ) ) { ?>
            <li class="<?=$newsletteractive;?>">
              <a href="<?=$this->config->item( 'admin_url' );?>newsletter">
                <i class="fa fa-newspaper-o"></i> <span>Newsletter</span>
                <span class="pull-right-container">
                  <small class="label pull-right bg-green"><?=$newsletter_total;?></small>
                </span>
              </a>
            </li>
            <?php } ?>
            <?php if( in_array( 'journey', $permission_arr ) ) { ?>
            <li class="<?=$journey;?>">
              <a href="<?=$this->config->item( 'admin_url' );?>journeys">
                <i class="fa fa-picture-o"></i> <span>Blog</span>
                <span class="pull-right-container">
                  <small class="label pull-right bg-green"></small>
                </span>
              </a>
            </li>
            <?php } ?>
          </ul>
        </li>

        <li class="<?=$feedbackactive;?>">
          <a href="<?=$this->config->item( 'admin_url' );?>feedback">
            <i class="fa fa-life-ring"></i> <span>Feedback</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-green"></small>
            </span>
          </a>
        </li>

        <li class="<?=$faqactive;?>">
          <a href="<?=$this->config->item( 'admin_url' );?>faq">
            <i class="fa fa-question-circle"></i><span>FAQ</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-green"></small>
            </span>
          </a>
        </li>

        <li class="treeview <?=$mgmnt_tree;?>">
          <a href="#">
            <i class="fa fa-user-circle-o"></i> <span>Management</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?=$user;?>">
              <a href="<?=$this->config->item( 'admin_url' );?>user">
                <i class="fa fa-users"></i> <span>Users</span>
                <span class="pull-right-container">
                  <small class="label pull-right bg-green"></small>
                </span>
              </a>
            </li>
            <?php if( in_array( 'role', $permission_arr ) ) { ?>
            <li class="<?=$role;?>">
              <a href="<?=$this->config->item( 'admin_url' );?>settings">
                <i class="fa fa-user-secret"></i> <span>Role</span>
                <span class="pull-right-container">
                  <small class="label pull-right bg-green"></small>
                </span>
              </a>
            </li>
            <?php } ?>
          </ul>
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>