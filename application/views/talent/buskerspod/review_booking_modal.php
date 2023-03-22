<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 
?>
<div class="count_box row">            
    <div class="col-md-12">
      <form name="order" id="confirmPaynowForm" method="post" action="<?= base_url().'talent/buskerspod/paynow'; ?>">
        <div class="box-header with-border">
          <h4 class="box-title">Booking Summary</h4>

          <div class="box-tools pull-right">
            <span class="badge bg-yellow orv_countdown">00:00</span>
          </div>
        </div>
        <h4></h4>
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
                echo '<tr><td colspan="3"><b><i class="fa fa-map-marker"></i> '.$partner_name.'</b></td></tr>';
                if($packagedatelist){
                  foreach ($packagedatelist as $key2 => $packagelist) {
                    $booking_date  = $key2;
                    echo '<tr><td colspan="3"><i class="fa fa-calendar"></i> '.date('D, jS M Y', strtotime( $booking_date ) ).'</td></tr>';
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
            <button type="button" class="btn btn-lg btn-block btn-default" class="close" data-dismiss="modal" aria-hidden="true">Close</button>
          </div>
          <?php
          if($booking_packages){ ?>
          <div class="form-group">
            <button type="button" onclick="return ConfirmPayment()" class="btn btn-lg btn-block btn-primary">PROCEED PAYMENT</button>
          </div>
          <?php } ?>
          <div class="form-group">
            <center><p>All purchases are non refundable, please ensure you practice responsible booking.</p></center> 
          </div>
        </form>
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
          getUpdatedreviewBooking();
          checkCartIsEmpty();
          //location.reload();
        }
    });
    return false;
  }
}
function getUpdatedreviewBooking() {
  $.ajax({
    type: "POST",
    data: {},
    url: baseurl + 'talent/buskerspod/reviewBookingModal',
    success: function( html ) {
      $( '#myModal #mymodalBody' ).html(html);
    }
  });
  return false;
}
function checkCartIsEmpty() {
  $.ajax({
    type: "POST",
    data: {},
    url: baseurl + 'talent/buskerspod/checkCartIsEmpty',
    success: function( msg ) {
      if(msg == 1){
        sessionStorage.setItem("counter", "");
        sessionStorage.setItem("orv_counter", "");
        location.reload();
      }
    }
  });
  return false;
}
function ConfirmPayment(){
  $.ajax({
      type: "POST",
      url: baseurl + 'talent/buskerspod/checkSpaceBfPayment',
      async : false,
      dataType: 'json',
      data: {},
      success: function(res) {
        if(res.status == 'error'){
          toastr.error( res.msg,'Error' );
        }
        if(res.status == 'success'){
          $( '#confirmPaynowForm' ).submit();
        }
      }
  });
  return false;
}
// function closeModal(){
//   $('.countdown_n').show();
//   var interval;
//   //clearInterval(interval);
//   $('.countdown_n').html('00:00');
//   var timer2 = $('.countdown').html();
//   //var timer2    = "00:10";
//   var interval  = setInterval(function() {
//     var timer   = timer2.split(':');
//     //by parsing integer, I avoid all extra string processing
//     var minutes = parseInt(timer[0], 10);
//     var seconds = parseInt(timer[1], 10);
//     --seconds;
//     minutes = (seconds < 0) ? --minutes : minutes;
//     if (minutes == 0 && seconds == 0){
//       clearInterval(interval);
//       $('#myModal').modal('hide');
//       $.get(baseurl + "talent/buskerspod/setBookingSession", function(data, status){ });
//       $( '#small_modal' ).modal({ backdrop: 'static', keyboard: false });
//       //window.location.href = baseurl + 'talent/buskerspod/bookingtimeout';
//     }
//     seconds = (seconds < 0) ? 59 : seconds;
//     seconds = (seconds < 10) ? '0' + seconds : seconds;
//     //minutes = (minutes < 10) ?  minutes : minutes;
//     $('.countdown_n').html(minutes + ':' + seconds);
//     timer2 = minutes + ':' + seconds;
//   }, 1000);

// }
</script>