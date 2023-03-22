<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl   = $this->config->item( 'admin_url' );
$site_name  = $this->config->item( 'site_name' );
$dirUrl     = $this->config->item( 'dir_url' );
$upload_path_url = $this->config->item( 'upload_path_url' );
$name       = $guiderInfo->first_name;
?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Guider Payout Info</h3>
                <div class="box-tools pull-right">
                    <a href="<?php echo $assetUrl; ?>guider" class="btn btn-sm btn-primary">Back</a>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                      <!-- Custom Tabs -->
                      <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                          <li class="active"><a href="#tab_1" data-toggle="tab">Payout</a></li>
                          <li><a href="#tab_2" data-toggle="tab">Guider Info</a></li>
                        </ul>
                        <div class="tab-content">
                          <div class="tab-pane active" id="tab_1">
                            <?php
                            if ($guiderInfo->profile_image) {
                                $profile_image     = $upload_path_url.'g_profile/'.$guiderInfo->profile_image;
                                $userimage = '<img class="img-circle" src="'.$profile_image.'" alt="'.$guiderInfo->first_name.'">';
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
                                      <h3 class="widget-user-username"><?= $guiderInfo->first_name .' '.$guiderInfo->last_name; ?></h3>
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
                                          <th>Guest Name</th>
                                          <th>Meeting Date</th>
                                          <th>Meeting Time</th>
                                          <th>Processing Fees</th>
                                          <th>Sub total</th>
                                        </tr>
                                        <?php
                                        $i = 1;
                                        if($pendingPaymentLists){
                                            foreach ($pendingPaymentLists as $service) {
                                                echo '<tr>
                                                      <td>'.$i.'</td>
                                                      <td>'.'BT'.str_pad($service->service_id, 5, '0', STR_PAD_LEFT).'</td>
                                                      <td>'.date(getDateFormat(), strtotime($service->jny_createdon)) .' '.date(getTimeFormat(), strtotime($service->jny_createdon)).'</td>
                                                      <td>'.$service->travellerName.'</td>
                                                      <td>'.date(getDateFormat(), strtotime($service->service_date)).'</td>
                                                      <td>'.date(getTimeFormat(), strtotime($service->service_date)).'</td>
                                                      <td>'.number_format((float)$service->percentageAmount, 2, '.', '').'</td>
                                                      <td>'.number_format((float)$service->guiderPayment, 2, '.', '').'</td>
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
                          </div>
                          <!-- /.tab-pane -->
                          <div class="tab-pane" id="tab_2">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">User Name</label>
                                        <div><?=$guiderInfo->first_name; ?></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Phone Number</label>
                                        <div><?=$guiderInfo->phone_number; ?></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Account Details</label>
                                        <div>Bank Account Name: <?=$guiderInfo->acc_name; ?></div>
                                        <div>Bank Name: <?=$guiderInfo->bank_name; ?></div>
                                        <div>Bank Account Number : <?=$guiderInfo->acc_no; ?></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Email</label>
                                        <div><?=$guiderInfo->email; ?></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Languages Known</label>
                                        <div><?=$guiderInfo->languages_known; ?></div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="control-label">Ratings</label>
                                        <div><?=$guiderInfo->rating; ?></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Mobile Number</label>
                                        <div><?= ($guiderInfo->mobile)? $guiderInfo->mobile : 'n/a'; ?></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">DOB</label>
                                        <div><?= ($guiderInfo->age != '0000-00-00')? date(getDateFormat(), strtotime($guiderInfo->age)) : 'n/a'; ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">About Me</label>
                                        <div><?=$guiderInfo->about_me; ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <h5 class="box-title"><b>ID Proof</b></h5>
                                    <div style="width:100px; margin: 0 auto;">
                                        <?php 
                                            if($guiderInfo->id_proof){ 
                                            $id_proof = $upload_path_url.'identity/'.$guiderInfo->id_proof;
                                            ?>
                                            <div class="img-view"><img class="img-thumbnail" src="<?=$id_proof; ?>" id="client_picture" style="height: auto;width: 100%;" data-src="#" /></div>
                                        <?php } else { ?>
                                            <div class="img-view"><img class="img-thumbnail" src="<?=$dirUrl; ?>uploads/no_image.png" id="client_picture" style="height:100px;width: auto;" data-src="#" /></div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                          </div>
                          <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                      </div>
                      <!-- nav-tabs-custom -->
                    </div>
                </div>
                
                <div class="clearfix"></div>    
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
          url: adminurl + 'guider/excutePayout',
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