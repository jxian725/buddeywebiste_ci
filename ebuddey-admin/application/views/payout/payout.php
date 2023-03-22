<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl       = $this->config->item( 'admin_url' );
$site_name      = $this->config->item( 'site_name' );
$dirUrl         = $this->config->item( 'dir_url' );
$upload_path_url = $this->config->item( 'upload_path_url' );
?>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">
                <?= HOST_NAME; ?> Payout
              </h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        $attributes = array('method' => 'get', 'id' => 'searchform');
                        echo form_open( $assetUrl.'guiderpayout', $attributes );
                        $guider_id  = $this->input->get('guider_id');
                        ?>
                        <!-- Search Name -->
                        <div class="form-group">
                          <div style="margin-bottom: 10px;padding-left: 0px;" class="search_input col-md-6">
                            <div class="input-group form-search">
                              <select id="guider_id" name="guider_id" class="form-control">
                                <option value="">Select <?= HOST_NAME; ?></option>
                                <?php
                                foreach ($guider_lists as $guiders) {
                                    if($guiders->guider_id == $guider_id){
                                        $selected = 'selected';
                                    }else{
                                        $selected = '';
                                    }
                                    echo '<option '.$selected.' value="'.$guiders->guider_id.'">'.$guiders->first_name.'</option>';
                                }
                                ?>
                              </select>
                              <span class="input-group-btn">
                                <button data-type="last" class="btn btn-black" type="submit">Search</button>
                              </span>
                            </div>
                          </div>
                        </div>
                        <?php echo form_close(); ?>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <?php
                if ($guider_info) {
                    if ($guider_info->profile_image) {
                      $profile_image = $upload_path_url.'g_profile/'.$guider_info->profile_image;
                      $userimage = '<img class="img-circle" src="'.$profile_image.'" alt="'.$guider_info->first_name.'">';
                    }else{
                      $userimage = '<img class="img-circle" src="'.$dirUrl.'img/avatar5.png" alt="User Avatar">';
                    }
                ?>
                <div class="row">
                    <div class="col-md-6">
                      <div class="box box-widget widget-user-2">
                        <div class="widget-user-header bg-purple">
                          <div class="widget-user-image">
                            <?= $userimage; ?>
                          </div>
                          <!-- /.widget-user-image -->
                          <h3 class="widget-user-username"><?= $guider_info->first_name .' '.$guider_info->last_name; ?></h3>
                          <h5 class="widget-user-desc"></h5>
                        </div>
                        <div class="box-footer no-padding">
                          <ul class="nav nav-stacked">
                            <li><a href="#">Settled Payment <span class="pull-right badge bg-green"><?php echo CURRENCYCODE.' '.(($settledPayment)? $settledPayment : '0'); ?></span></a></li>
                            <li><a href="#">Pending Payment <span class="pull-right badge bg-red"><?php echo CURRENCYCODE.' '.(($payoutAmt)? $payoutAmt : '0'); ?></span></a></li>
                          </ul>
                        </div>
                      </div>
                    </div>
                </div>
                <?php
                if($payoutAmt){
                  $btnstatus  = '';
                  $payoutAmt  = $payoutAmt;
                }else{ 
                  $btnstatus  = 'disabled'; 
                  $payoutAmt  = 0;
                }
                ?>
                <div class="row">
                    <div class="col-md-6">
                        <button <?= $btnstatus; ?> class="btn pull-right btn-sm bg-green" onclick="return confirmPayout(<?= $guider_id; ?>,<?= $payoutAmt; ?>,<?= $transactionAmt; ?>,<?= $percentageAmt; ?>,<?= count($pendingPaymentLists); ?>);">
                            Execute Payout
                        </button>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
                <div class="row">
                    <div class="col-md-12">
                      <div class="">
                        <div class="box-header with-border">
                          <h3 class="box-title">Journey Lists(Pending)</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                          <table class="table table-bordered">
                            <tr>
                              <th style="width: 10px">#</th>
                              <th>Trip ID</th>
                              <th>Booking Created datetime</th>
                              <th><?= GUEST_NAME; ?> Name</th>
                              <th>Meeting Date</th>
                              <th>Meeting Time</th>
                              <th>Processing Fees</th>
                              <th>Sub total</th>
                            </tr>
                            <?php
                            $i = 1;
                            if($pendingPaymentLists){
                                foreach ($pendingPaymentLists as $service) {
                                    $ProcessingFees = (PROCESSING_FEE / 100) * $service->transaction_amount;
                                    if($ProcessingFees < 2){ $ProcessingFees = 02.00; }
                                    if($service->transaction_amount){
                                      $payoutAmt = $service->transaction_amount - $ProcessingFees;
                                    }else{
                                      $payoutAmt = 0;
                                    }
                                    echo '<tr>
                                          <td>'.$i.'</td>
                                          <td>'.'BT'.str_pad($service->service_id, 5, '0', STR_PAD_LEFT).'</td>
                                          <td>'.date(getDateFormat(), strtotime($service->jny_createdon)) .' '.date(getTimeFormat(), strtotime($service->jny_createdon)).'</td>
                                          <td>'.$service->travellerName.'</td>
                                          <td>'.date(getDateFormat(), strtotime($service->service_date)).'</td>
                                          <td>'.date(getTimeFormat(), strtotime($service->service_date)).'</td>
                                          <td>'.number_format((float)$ProcessingFees, 2, '.', '').'</td>
                                          <td>'.number_format((float)$payoutAmt, 2, '.', '').'</td>
                                        </tr>';
                                    $i++;
                                }
                            }else{
                                echo '<tr><td colspan="8">No List Found.</td><tr>';
                            }
                            ?>
                          </table>
                        </div>
                      </div>
                      <!-- /.box -->
                    </div>
                </div>
                <!-- /.row -->
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
  function confirmPayout( guider_id, payoutAmt, transactionAmt, percentageAmt, totalTrip ) {
      $( '#myModal .modal-title' ).html( 'Confirm' );
      $( '#myModal .modal-body' ).html( 'Are you sure want to Execute Payout ?' );
      $( '#myModal .modal-body' ).append( '<br /><br /><button class="btn btn-info btn-sm" id="continuemodal" data-dismiss="modal">Yes</button>&nbsp;&nbsp;<button aria-hidden="true" data-dismiss="modal" class="btn btn-danger btn-sm">Cancel</button>' );
      $( '#myModal' ).modal({ backdrop: 'static', keyboard: false }); 
      $( "#continuemodal" ).click(function() {
        var data = { 'guider_id':guider_id,'payoutAmt':payoutAmt,'transactionAmt':transactionAmt,'percentageAmt':percentageAmt,'totalTrip':totalTrip }
        $.ajax({
          type: "POST",
          url: adminurl + 'guiderpayout/excutePayout',
          data: data,
          success: function( data ) {
            toastr.success( 'Payout executed Successfully.','Success' );
            setTimeout( function() {
              location.reload();
            }, 2000 );
          }
        });
    });
    return false;
  }
</script>