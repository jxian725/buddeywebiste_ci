<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 
$assetUrl   = $this->config->item( 'admin_dir_url' );
$site_name  = $this->config->item( 'site_name' );
$dirUrl     = $this->config->item( 'dir_url' );
?>
<div class="count_box row">            
    <div class="col-md-8">
      <div class="col-xl-12 col-sm-14" style="padding-left: 0px;">
          <div class="box box-white">
              <div class="box-header with-border">
                  <h4>Payments</h4>
                    <table class="table table-bordered">
                      <tr>
                        <th>Payment date</th>
                        <th>Amount</th>
                        <th>Transaction ID</th>
                        <th>Action</th>
                      </tr>
                      <!--<?php 
                     // if($eventList){
                        //foreach ($eventList as $event) { 
                       // $start  = date('H:i', strtotime($event->start));
                       // $end    = date('H:i', strtotime($event->end));
                        //$amount = ((is_numeric($event->partnerFees))? number_format($event->partnerFees, 2) : ''); 
                     // ?>-->
                      <tr>
                        <td style="color: #32CD32; font-size:18px;"></td>
                        <td style="color: #32CD32; font-size:18px;"></td>
                        <td style="color: #32CD32; font-size:18px;"></td>
                        <td><a href="#">View</a></td>
                      </tr>
                     <!--// <?php
                        //}
                      //}else{ ?>
                      <center><span class="label label-info" style="width: 100%;">No data Found</span></center>
                      //<?php// } ?>-->
                    </table>  
              </div>
          </div>
      </div>
    </div>
    <div class="col-md-4">
        <div class="col-xl-12 col-sm-14" style="padding-left: 0px;">
            <div class="box box-white">
                <div class="box-header with-border">
                    <h4>Booking Summary</h4>
                    <div class="form-group">
                      <b>Transaction ID</b> 11111111 
                    </div>
                    <div class="form-group">
                      <b>Payment date</b>  2020-03-12 
                    </div>
                    <table class="table table-bordered">
                      <tr>
                        <th>Description</th>
                        <th>price</th>
                      </tr>
                    </table>
                    <div class="form-group">
                    <b class="text pull-left">Total</b>
                    <p class="text pull-right"><b>RM</b> <span id="price"></span>.00</p>
                  </div>  
                </div>
            </div>
        </div>
    </div>              
</div>
