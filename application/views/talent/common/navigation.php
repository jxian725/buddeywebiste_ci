<?php
$dirUrl     = $this->config->item( 'dir_url' ); 

$page  = $this->uri->segment(2);
$dashactive = ''; $feedbackactive = ''; $inboxactive='';$podractive = ''; $profactive = ''; $payment = ''; $donation = ''; $privacy = ''; $terms = ''; $buskeractive = ''; $logout = ''; $gigsactive = ''; $gigs = ''; $transaction_tree = '';
if($page == '' || $page == 'client'){
  $dashactive       = 'active';
} else if($page == 'buskerspod'){
  $podractive = 'active';
} else if( $page == 'feedback' ) {
  $feedbackactive   = 'active';
}
else if( $page == 'inbox' ) {
  $inboxactive   = 'active';
}
 else if( $page == 'profile' || $page == 'license' ) {
  $profactive   = 'active';
} else if( $page == 'managebuskerspod' ) {
  $transaction_tree = 'active';
  $page2 = $this->uri->segment(3);
  if($page2 == 'payment'){
    $payment = 'active';
  }elseif ($page2 == 'donations') {
    $donation = 'active';
  }else{
    $buskeractive = 'active';
  }
} else if( $page == 'gigs' ) {
  $gigsactive   = 'active';
} else if( $page == 'privacypolicy' ) {
  $privacy   = 'active';
} else if( $page == 'termsandconditions' ) {
  $terms   = 'active';
} else if( $page == 'logout' ) {
  $logout   = 'active';      
} else{
  $dashactive    = 'active';
}
?>
<style type="text/css">
.content-wrapper { background-color: #ffffff; }
.skin-blue .main-sidebar, .skin-blue .left-side {
  background-color: #fff;
}
</style>
<!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->

      <ul class="sidebar-menu" data-widget="tree">
        <li class="<?=$dashactive;?>">
          <a href="<?= base_url(); ?>talent/forums">
            &nbsp;&nbsp;&nbsp;<span>News</span>
          </a>
        </li>
        <li class="<?=$profactive;?>"> 
          <a href="<?=$dirUrl;?>talent/profile">
            &nbsp;&nbsp;&nbsp;<span>Account</span>
          </a>
        </li>
        <li class="<?=$podractive;?>">
          <a href="<?=$dirUrl;?>talent/buskerspod">
            &nbsp;&nbsp;&nbsp;<span>Buskers Pod</span>
          </a>
        </li>
         <li class="treeview <?=$transaction_tree;?>">
          <a href="#">
            &nbsp;&nbsp;&nbsp;<span>Transaction History</span>
          </a>
          <ul class="treeview-menu">
            <li class="<?=$buskeractive;?>">
              <a href="<?=$dirUrl;?>talent/managebuskerspod">
                <span>Buskers Pod Tracker</span>
                <span class="pull-right-container">
                  <small class="label pull-right bg-green"></small>
                </span>
              </a>
            </li>
            <li class="<?=$payment;?>">
              <a href="<?=$dirUrl;?>talent/managebuskerspod/payment">
                <span>Payments</span>
                <span class="pull-right-container">
                  <small class="label pull-right bg-green"></small>
                </span>
              </a>
            </li>
            <li class="<?=$donation;?>">
              <a href="<?=$dirUrl;?>talent/managebuskerspod/donations">
                <span>Donations</span>
                <span class="pull-right-container">
                  <small class="label pull-right bg-green"></small>
                </span>
              </a>
            </li>
          </ul>
        </li>
        <li class="<?=$feedbackactive;?>">
          <a href="<?=$dirUrl;?>talent/feedback">
            &nbsp;&nbsp;&nbsp;<span>Feedback</span>
          </a>
        </li>
        <li class="treeview">
          <a href="#">
            &nbsp;&nbsp;&nbsp;<span>Settings</span>
          </a>
          <ul class="treeview-menu">
            <li class="<?= $terms; ?>">
              <a href="<?= $dirUrl; ?>termsandconditions">
                <span>Terms</span>
                <span class="pull-right-container">
                  <small class="label pull-right bg-green"></small>
                </span>
              </a>
            </li>
            <li class="<?=$privacy;?>">
              <a href="<?=$dirUrl;?>privacypolicy">
                <span>Privacy</span>
                <span class="pull-right-container">
                  <small class="label pull-right bg-green"></small>
                </span>
              </a>
            </li>
            <li class="<?=$logout;?>">
              <a href="<?=$dirUrl;?>talent/logout">
                <span>Logout</span>
                <span class="pull-right-container">
                  <small class="label pull-right bg-green"></small>
                </span>
              </a>
            </li>
          </ul>
        </li> 
		<li class="<?=$inboxactive;?>">
    <?php if (!empty($inboxreadinfo)) { ?>
          <a href="<?=$dirUrl;?>talent/inbox">
            &nbsp;&nbsp;&nbsp;<span>Inbox <div class="badge" style="background-color:#32465a" id="badge"><?php echo $inboxreadinfo; ?></div></span>
          </a>
          <?php } ?>
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>