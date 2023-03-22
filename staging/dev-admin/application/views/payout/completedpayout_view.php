<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl       = $this->config->item( 'admin_url' );
$site_name      = $this->config->item( 'site_name' );
$dirUrl         = $this->config->item( 'dir_url' );
?>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box box-primary">
            <div class="box-body">
                <div class="clearfix"></div>
                <div class="row">
                    <div class="col-md-12">
                      <div class="">
                        <div class="box-header with-border">
                          <h3 class="box-title">Journey Lists</h3>
                          <div class="pull-right box-tools">
                            <a class="btn btn-sm btn-primary" href="<?= $assetUrl; ?>completedpayout">Back</a>
                          </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                          <table class="table table-bordered">
                            <tr>
                              <th style="width: 10px">#</th>
                              <th>Trip ID</th>
                              <th>Booking created datetime</th>
                              <th><?= GUEST_NAME; ?> Name</th>
                              <th>Meeting Date</th>
                              <th>Meeting Time</th>
                              <th>Processing Fees</th>
                              <th>Sub total</th>
                            </tr>
                            <?php
                            $i = 1;
                            
                            if($payouttransInfo){
                              $journeyList = unserialize($payouttransInfo->journeyList);
                                foreach ($journeyList as $journeyID) {
                                  $service = $this->Completedpayoutmodel->journeyAllInfo($journeyID);
                                  echo '<tr>
                                          <td>'.$i.'</td>
                                          <td>'.'BT'.str_pad($service->service_id, 5, '0', STR_PAD_LEFT).'</td>
                                          <td>'.date(getDateFormat(), strtotime($service->jny_createdon)) .' '.date(getTimeFormat(), strtotime($service->jny_createdon)).'</td>
                                          <td>'.$service->travellerName.'</td>
                                          <td>'.date(getDateFormat(), strtotime($service->service_date)).'</td>
                                          <td>'.date(getTimeFormat(), strtotime($service->service_date)).'</td>
                                          <td>'.number_format((float)$service->percentage_amount, 2, '.', '').'</td>
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
        </div>
    </div>
</div>
<script type="text/javascript">
</script>