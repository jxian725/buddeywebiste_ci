<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 
$adminUrl   = $this->config->item( 'admin_dir_url' );
$dirUrl     = $this->config->item( 'dir_url' );
?>
<style type="text/css">
.follow-list{
    list-style: none;
    display: -webkit-inline-box;
}
.follow-box-content{
    padding: 20px 20px;
}
.info-box-no {
    font-size: 18px;
    text-align: center;
    display: block;
}
.profile-user-img {
    width: auto;
    height: 160px;
}
.card-body {
  padding: 4px 12px;
}
.w3-panel{ 
  min-height: 200px;
  background: #fff;
  box-shadow: 0 2px 5px 0 rgba(0,0,0,0.16), 0 2px 10px 0 rgba(0,0,0,0.12);
  transition: 0.3s;
  border-radius: 10px;
  margin: 20px 5px 30px 5px;
}
.w3-panel:hover{
  -webkit-transform: scale(1.1); 
  -moz-transform: scale(1.1); 
  -ms-transform: scale(1.1); 
  -o-transform: scale(1.1); 
  transform:rotate scale(1.1); 
  -webkit-transition: all 0.4s ease-in-out; 
  -moz-transition: all 0.4s ease-in-out; 
  -o-transition: all 0.4s ease-in-out;
  transition: all 0.4s ease-in-out;
}
.card-icon{
  font-size: 50px;
  display: flex;
  padding: 10px 0 5px 10px;
  margin-bottom: 10px;
}
</style>

<div class="row">

<div class="col-xs-12 col-md-10">

  <div class="box-header with-border">
    <h1 class="box-title">Account</h1>
  </div>

  <div class="col-xs-6">
    <div class="w3-panel w3-card">
      <div class="card-body">
        <div class="card-icon"><i class="ion ion-ios-person"></i></div>
        <div class="c-content">
          <p><a href="<?= base_url().'talent/profile/edit'; ?>">My Profile</a></p>
          <p class="card-text">Manage personal details on Buddey Platform</p>
        </div>
      </div>
    </div>
  </div>
  <!-- <div class="col-xs-6">
    <div class="w3-panel w3-card-2">
      <div class="card-body">
        <div class="card-icon"><i class="ion ion-mic-c"></i></div>
        <div class="c-content">
          <p><a href="<?= base_url().'talent/experience'; ?>">Talent Experiences</a></p>
          <p class="card-text">Manage hosting of Talent Experiences</p>
        </div>
      </div>
    </div>
  </div> -->
  <div class="col-xs-6">
    <div class="w3-panel w3-card-4">
      <div class="card-body">
        <div class="card-icon"><i class="ion ion-cash"></i></div>
        <div class="c-content">
          <p><a href="javascript:;">Payments</a></p>
          <p class="card-text">Manage payment details to received payments for your services</p>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xs-6">
    <div class="w3-panel w3-card-4">
      <div class="card-body">
        <div class="card-icon"><i class="ion ion-chatboxes"></i></div>
        <div class="c-content">
          <p><a href="javascript:;">Buskers Pod reviews</a></p>
          <p class="card-text">Review feedbacks for my performances</p>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xs-6">
    <div class="w3-panel w3-card-4">
      <div class="card-body">
        <div class="card-icon"><i class="ion ion-card"></i></div>
        <div class="c-content">
          <p><a href="<?= base_url().'talent/license'; ?>">Verification</a></p>
          <p class="card-text">Manage required verification</p>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="clearfix"></div>

</div>