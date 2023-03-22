<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 
$assetUrl   = $this->config->item( 'admin_dir_url' );
$dirUrl     = $this->config->item( 'dir_url' );
?>

<style type="text/css">
  /*view more option   */
.show-read-more .more-text{
  display: none;
}
.list-group-item.active a{ color: #fff; }
.fc-day-grid-container{
  height: auto !important;
}
</style>

<div class="count_box row">            
    <div class="col-md-6">
      <div class="col-xl-12 col-sm-14" style="padding-left: 0px;">
          <div class="box box-white">
              <div class="box-header with-border">
                <form name="order" method="post" action="<?= base_url().'talent/buskerspod/paynow'; ?>">
                  <h4>Booking Summary</h4>
                    <table class="table table-bordered">
                      <thead>
                      <tr>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Action</th>
                      </tr>
                      </thead>
                      <tbody>
                      <?php
                      $totalAmount = 0;
                      if($booking_packages){
                        foreach ($booking_packages as $key => $packagedatelist) {
                          $partner_id  = $key;
                          $partnerInfo = $this->Talentmodel->partnerInfo( $partner_id );
                          if($partnerInfo){
                              $partner_name = rawurldecode( $partnerInfo->partner_name );
                          }else{
                              $partner_name = '';
                          }
                          echo '<tr><td colspan="3">'.$partner_name.'</td></tr>';
                          if($packagedatelist){
                            foreach ($packagedatelist as $key2 => $packagelist) {
                              $booking_date  = $key2;
                              echo '<tr><td colspan="3">'.$booking_date.'</td></tr>';
                              foreach ($packagelist as $key3 => $package) {
                                $totalAmount += $package['amount'];
                                echo '<tr>
                                        <td>'.$package['start'].' to '.$package['end'].'</td>
                                        <td>
                                          RM'.$package['amount'].'
                                          <input type="hidden" name="package_id[]" value="'.$package['package_id'].'">
                                        </td>
                                        <td><a onclick="return removeToBooking('.$partner_id.',\''.$booking_date.'\', '.$package['package_id'].');" href="javascript:;">Delete</a></td>
                                      </tr>';
                              }
                            }
                          }
                        }
                      }else{
                        echo '<tr><td colspan="3">No package selected.</td></tr>';
                      }
                      ?>
                      </tbody>
                    </table>
                    <div class="form-group">
                      <b class="text pull-left">Total</b>
                      <p class="text pull-right"><b>RM</b>
                        <?php
                        echo $totalAmount = number_format($totalAmount, 2);
                        ?>
                      </p>
                    </div>
                    <div class="form-group">
                      <a href="<?= $_SERVER['HTTP_REFERER']; ?>" class="btn btn-default" style="width: 100%">Back</a>
                    </div>
                    <?php
                    if($booking_packages){ ?>
                    <div class="form-group">
                      <button type="submit" class="btn btn-primary" style="width: 100%">PROCEED PAYMENT</button>
                    </div>
                    <?php } ?>
                    <div class="form-group">
                      <center><p>All purchases are non refundable, please ensure you practice responsible booking.</p></center> 
                    </div>
                  </form>
              </div>
          </div>
      </div>
    </div>             
</div>

<script type="text/javascript">
function removeToBooking(partner_id, booking_date, package_id){

  if(partner_id && booking_date && package_id){
    $.ajax({
        type: "POST",
        url: baseurl + 'talent/buskerspod/removeToBooking',
        async : false,
        data: {
          partner_id : partner_id, booking_date : booking_date, package_id : package_id
        },
        success: function(html) {
          toastr.success( 'Remove to booking successfully.','Success' );
          location.reload();
        }
    });
    return false;
  }
}
</script>