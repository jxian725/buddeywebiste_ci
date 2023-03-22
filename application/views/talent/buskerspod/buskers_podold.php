<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 
$assetUrl   = $this->config->item( 'admin_dir_url' );
$dirUrl     = $this->config->item( 'dir_url' );
$countdown  = "03:00";
?>

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script> -->

<link rel="stylesheet" href="<?php echo $assetUrl; ?>plugins/bootstrap-datepicker/bootstrap-datepicker.min.css">

<script src="<?= $assetUrl; ?>assets/js/jquery.min.js"></script>
<link href="<?= $assetUrl; ?>assets/css/bootstrap.min.css" rel="stylesheet">
<link href='<?= $assetUrl; ?>assets/css/fullcalendar.css' rel='stylesheet' />
<link href="<?= $assetUrl; ?>assets/css/bootstrapValidator.min.css" rel="stylesheet" />        
<link href="<?= $assetUrl; ?>assets/css/bootstrap-colorpicker.min.css" rel="stylesheet" />
<!-- Custom css  -->
<link href="<?= $assetUrl; ?>assets/css/custom.css" rel="stylesheet" />

<link rel="stylesheet" href="<?= $assetUrl; ?>assets/css/fullcalendar.css" />
<script src="<?= $assetUrl; ?>assets/js/moment.min.js"></script>
<script src="<?= $assetUrl; ?>assets/js/fullcalendar.min.js"></script>
<script src="<?= $assetUrl; ?>assets/js/gcal.js"></script>
<script src="<?= $assetUrl; ?>plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<style type="text/css">
  /*view more option   */
.show-read-more .more-text{
  display: none;
}
.list-group-item.active a{ color: #fff; }
.fc-day-grid-container{
  height: auto !important;
}
.fc-day-grid-event .fc-time {
  display: none;
}
.fc-center h2{
  font-size: 18px;
  line-height: 1.6;
}
.d-none{ display: none; }
.d-block{ display: block; }
.close{ display: none; }
</style>
<div class="count_box row">
    <div class="col-md-3">
        <div class="col-xl-12 col-sm-14" style="padding-left: 0px;">
            <div class="box box-white">
                <div class="box-header with-border">
                    <h6>Select city</h6>
                    <?php
                      $attributes = array('method' => 'get', 'id' => 'searchform');
                      echo form_open( $dirUrl.'talent/buskerspod', $attributes );
                      $partner_id = $this->input->get('partner_id');
                      //$city     = $this->input->get( 'city' );
                    ?>
                    <div class="form-group">
                      <select class="form-control" name="city_id" id="city_id">
                          <option value="0">Select city</option>
                          <?php 
                          if($cityLists){
                              foreach ($cityLists as $cityInfo) {
                                ?><option value="<?php echo $cityInfo->id; ?>"><?php echo $cityInfo->name; ?></option><?php
                              }
                          }
                          ?>
                      </select>
                    </div>
                    <h6>Select Buskers Pod</h6>
                    <div class="input-group">
                        <input type="text" autocomplete="off" name="partner_search" id="partner_search" class="form-control" value="" placeholder="Search Buskers Pod..">
                        <div class="input-group-btn">
                           <button data-type="last" type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                    <div class="">
                        <ul class="list-group" id="city_lists" style="margin-top: 10px; font-size: 12px">
                        <?php 
                        if($partner_List){
                            foreach ($partner_List as $partner) { ?>
                            <li class="list-group-item <?= (($partner_id == $partner->partner_id)? 'active' : ''); ?>">
                              <a href="javascript:;" onclick="return showPartnerInfo(<?= $partner->partner_id; ?>)" class="" value="<?= $partner->partner_id; ?>" class="" id="partner_id" value="<?= $partner->partner_id; ?>"><?= rawurldecode($partner->partner_name); ?></a>
                            </li>
                            <?php
                            }
                        } ?>
                      </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>                 
    <div class="col-md-5">
        <div class="col-xl-12 col-sm-12" style="padding-left: 0px;">
            <div class="box box-white">
                <div class="box-header with-border">
                  <?php 
                  if($partnerInfo){
                      if($partnerInfo->photo){ ?>
                        <div class="form-group">
                          <img src="<?= $assetUrl; ?>uploads/partner/<?= $partnerInfo->photo; ?>" style="height:40%;width:100%;">
                        </div>
                      <?php } ?>
                    <h4>Important information</h4>
                    <div class="form-group">
                      <div style="font-size: 18px;">
                        <span>Required verification :</span>
                        <?php
                        $talent_id    = $this->session->userdata['TALENT_ID'];
                        $licenseArr   = explode(',', $partnerInfo->required_license);
                        $licenseLists = $this->Talentmodel->getSelectedLicense($licenseArr);
                        if($licenseLists){
                          foreach ($licenseLists as $key => $value) {
                            $licenseInfo = $this->Talentmodel->talentLicenseInfo($talent_id, $value->license_id);
                            if($licenseInfo && $licenseInfo->license_image){
                              if($licenseInfo->status == 1){ $lblcolor = 'label-success'; }else{ $lblcolor = 'label-warning'; }
                              echo ' <span class="label '.$lblcolor.'"><i class="ion-ios-checkmark"></i>&nbsp'.$value->license_name.'</span>&nbsp;';
                            }else{
                              echo '<span class="label label-danger"><i class="ion-close-circled"></i>&nbsp'.$value->license_name.'</span>&nbsp;';
                            }
                          }
                        }else{
                          echo 'NIL';
                        }
                        ?>
                      </div>
                    </div> 
                    <div class="form-group">
                      <div><?= rawurldecode($partnerInfo->partner_name); ?>, <?= $partnerInfo->cityName;?></div>
                    </div> 
                    <div class="form-group">
                      <div class="show-read-more1" style="line-height: 35px;"><?= $partnerInfo->address; ?></div>
                    </div>
                    <?php
                  }else{ ?>
                    <center><span class="text" style="width: 100%;">Please select a Buskers Pod</span></center>
                  <?php } ?>
                </div>
            </div>
        </div>
    </div>    
    <div class="col-md-4">
      <div class="col-xl-12 col-sm-14" style="padding-left: 0px;">

          <div class="box box-white">
              <div class="box-header with-border">
                  <h4>Select date</h4>
                  <div class="form-group">
                   <div class="box box-solid bg-blue1">
                      <!-- /.box-header -->
                      <div class="box-body no-padding">
                        <!--The calendar -->
                        <div class="row clearfix">
                          <div class="col-md-12 column">
                            <div id='calendar'></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div id="selected_date"></div>
                  <h4>Select time</h4>
                  <div class="form-group">
                    <b>Quantity X <span id="total_qty"></span></b>&nbsp;&nbsp;
                    <?php 
                    if($this->session->userdata('booking_qty')){
                      $ccclass = '';
                    }else{
                      $ccclass = 'd-none';
                    }
                    ?>
                    <a href="javascript:;" class="<?= $ccclass; ?>" id="ccBtn" onclick="return resetBookingPage(<?= $partner_id; ?>);">Clear Cart</a>
                  </div>
                  <div class="form-group">
                    <div id="event_time_list"></div>
                  </div>
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th>Start</th>
                          <th>End</th>
                          <th>Status</th>
                          <th>RM</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody id="availablehourslist">
                      <?php 
                      if($eventList){
                        foreach ($eventList as $event) {
                          if($event->status == 1){
                              $status = 'Available';
                              $color  = 'green';
                          }else if($event->status == 2){ //Progress
                              $status = 'Unavailable';
                              $color  = 'red';
                          }else if($event->status == 3){ //Booked
                              $status = 'Unavailable';
                              $color  = 'red';
                          }else if($event->status == 4){ //Locked
                              $status = 'Available';
                              $color  = 'green';
                          }else{
                              $status = '';
                              $color  = '';
                          }
                          $current_select   = 0;
                          $booking_packages = json_decode($this->session->userdata('booking_packages'), true);
                          if($booking_packages){
                            if(count($booking_packages[$partner_id]) != 0){
                              $booking_date = date('Y-m-d', strtotime($event->start));
                              if(count($booking_packages[$partner_id][$booking_date]) != 0){
                                $package_id = $event->id;
                                if(count($booking_packages[$partner_id][$booking_date][$package_id]) != 0){
                                  $current_select = 1;
                                }
                              }
                            }
                          }
                          if($current_select == 1){
                            $status = 'Selected';
                            $color  = 'orange';
                          }
                          $start  = date('H:i', strtotime($event->start));
                          $end    = date('H:i', strtotime($event->end));
                          $amount = ((is_numeric($event->partnerFees))? number_format($event->partnerFees, 2) : ''); 
                      ?>
                      <tr>
                        <td style="color: <?= $color;?>;"><?= $start;?></td>
                        <td style="color: <?= $color;?>;"><?= $end; ?></td>
                        <td style="color: <?= $color;?>;"><?= $status; ?></td>
                        <td style="color: <?= $color;?>;" class="pod_price"><?= $amount;?></td>
                        <td>
                          <?php
                          if(($event->status == 1 || $event->status == 4) && $current_select == 0){ ?>
                            <input type="checkbox" class="select_time" name="event_id[]" value="<?= $event->id;?>" style="font-size:18px;"/>
                            <input type="hidden" name="qnty[]" value="1">
                            <input type="hidden" name="pod_price[]" value="<?= $amount;?>">
                          <?php }else if($current_select == 1){ ?>
                            <input type="checkbox" checked="" disabled class="select_time" name="event_id[]" value="<?= $event->id;?>" style="font-size:18px;"/>
                            <input type="hidden" name="qnty[]" value="1">
                            <input type="hidden" name="pod_price[]" value="<?= $amount;?>">
                          <?php }else{ ?>
                            <input type="checkbox" disabled class="select_time" name="event_id[]" value="<?= $event->id;?>" style="font-size:18px;"/>
                            <input type="hidden" name="qnty[]" value="1">
                            <input type="hidden" name="pod_price[]" value="<?= $amount;?>">
                          <?php } ?>
                        </td>
                      </tr>
                      <?php
                        }
                      }else{ ?>
                        <tr><td colspan="5"><center>Not Available</center></td>
                      <?php } ?>
                      </tbody>
                    </table>
                  <div class="form-group">
                    <b class="text pull-left">Total</b>
                    <p class="text pull-right"><b>RM</b> <span id="total_price_lb"><?= $this->session->userdata('booking_price'); ?></span>.00</p>
                    <input type="hidden" id="total_price" value="">
                  </div> 
                  <div class="clearfix"></div>
                  <div class="form-group">
                    <button type="button" id="addToBookingBtn" class="btn btn-lg btn-block disabled btn-default" onclick="return addToBooking('<?= $partner_id; ?>');">Add to booking</button>
                  </div>
                  <div class="form-group">
                    <!-- <a href="<?= base_url().'talent/buskerspod/review_booking'; ?>" id="reviewBookingBtn" class="btn btn-lg <?= (($booking_packages)? 'btn-primary' : 'disabled btn-default'); ?>" style="width: 100%">Review booking</a> -->
                    <button type="button" id="reviewBookingBtn" class="btn btn-lg btn-block <?= (($booking_packages)? 'btn-primary' : 'disabled btn-default'); ?>" onclick="return reviewBookingModal();">Review booking</button>
                    <div class="box-tools pull-right">
                      <span class="badge bg-yellow countdown_n">00:00</span>
                    </div>
                  </div>   
              </div>
          </div>
      </div>
    </div>
</div>

<div class="modal fade bs-modal-sm" id="small_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Session Expired</h4>
            </div>
            <div class="modal-body">Your booking session is expired.</div>
            <div class="modal-footer">
                <a id="btnExpiredOk" href="javascript:;" onclick="return resetBookingPage(<?= $partner_id; ?>);" class="btn btn-primary" style="padding: 6px 12px; margin-bottom: 0; font-size: 14px; font-weight: normal; border: 1px solid transparent; border-radius: 4px; background-color: #428bca; color: #FFF;">Ok</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script type="text/javascript">
var partner_id = '<?= $partner_id; ?>';

sessionStorage.setItem("orv_counter", '');
//alert(sessionStorage.getItem("counter"));
if(sessionStorage.getItem("counter") == 'NaN:NaN' || sessionStorage.getItem("set_refresh") == 1){
  var timer2 = '<?= $countdown; ?>';
  sessionStorage.setItem("counter", '');
  sessionStorage.setItem("orv_counter", '');
  window.location.href = baseurl + 'talent/buskerspod/forcebookingtimeout';
}
sessionStorage.setItem("set_refresh", '');
if (sessionStorage.getItem("counter")) {

  var timer   = sessionStorage.getItem("counter").split(':');
  var minutes = parseInt(timer[0], 10);
  var seconds = parseInt(timer[1], 10);
  //console.log(minutes);
  //console.log(seconds);
  if ((minutes <= 0 && seconds <= 0) || minutes == '-1' ){
    clearInterval(counter);
    var timer2 = '00:00';
  } else {
    var timer2 = sessionStorage.getItem("counter");
  }
} else {
  var timernew = $('.countdown_n').html();
  if(timernew != '00:00'){ timer2 = timernew; }else{ var timer2 = '<?= $countdown; ?>'; }
}
$('.countdown_n').html(timer2);

var interval = 0;

var counter  = function () {

    var timer   = timer2.split(':');
    //by parsing integer, I avoid all extra string processing
    var minutes = parseInt(timer[0], 10);
    var seconds = parseInt(timer[1], 10);
    --seconds;
    minutes = (seconds < 0) ? --minutes : minutes;
    
    if ((minutes <= 0 && seconds == 0) ){
      clearInterval(counter);
      sessionStorage.setItem("counter", '');
      sessionStorage.setItem("orv_counter", '');
      sessionStorage.setItem("set_refresh", 1);
      $('#myModal').modal('hide');
      $( '#small_modal' ).modal({ backdrop: 'static', keyboard: false });
      $('.countdown_n').html('00:00');
      return false;
    }

    seconds = (seconds < 0) ? 59 : seconds;
    seconds = (seconds < 10) ? '0' + seconds : seconds;
    $('.countdown_n').html(minutes + ':' + seconds);
    sessionStorage.setItem("counter", minutes + ':' + seconds);
    timer2 = minutes + ':' + seconds;
    
    $('.countdown_n').html(timer2);
};

if (sessionStorage.getItem("counter")) {
  interval = setInterval(counter, 1000);
}
</script>

<script type="text/javascript">
$(document).ready(function() {

    var date_last_clicked = null;

    $('#calendar').fullCalendar({
        header: {
            left: '', //prev, next, today
            center: 'prev title next',
            right: '' //month, basicWeek, basicDay
        },
        eventLimit: true, // allow "more" link when too many events
        selectable: true,
        selectHelper: true,
        editable: true, // Make the event resizable true 
        eventSources: [
           {
           events: function(start, end, timezone, callback) {
                $.ajax({
                    url: '<?php echo base_url() ?>talent/buskerspod/get_events',
                    dataType: 'json',
                    data: {
                        // our hypothetical feed requires UNIX timestamps
                        start: start.unix(),
                        end: end.unix(),
                        partner_id: partner_id
                    },
                    success: function(msg) {
                        var events = msg.events;
                        callback(events);
                    }
                });
              }
            },
        ],
        // Event Mouseover
        eventMouseover: function(event, jsEvent, view){

            var tooltip = '<div class="event-tooltip">' + event.message + '</div>';
            $("body").append(tooltip);

            $(this).mouseover(function(e) {
                $(this).css('z-index', 10000);
                $('.event-tooltip').fadeIn('500');
                $('.event-tooltip').fadeTo('10', 1.9);
            }).mousemove(function(e) {
                $('.event-tooltip').css('top', e.pageY + 10);
                $('.event-tooltip').css('left', e.pageX + 20);
            });
        },
        eventMouseout: function(event, jsEvent) {
            $(this).css('z-index', 8);
            $('.event-tooltip').remove();
        },
        dayClick: function(date, jsEvent, view) { 
            //console.log(date);console.log('date clicked');
            date_last_clicked = $(this);
            //console.log(moment(date._d).format('Do MMM YYYY'));
            $('.fc-bg tbody tr td').css('background-color', '#fff');
            $(this).css('background-color', '#eaf8fa');
            $('#selected_date').html('Selected <b>'+moment(date._d).format('ddd, Do MMM YYYY')+'</b>');
            // $('#addModal #startDate').val(moment(date._d).format('YYYY/MM/DD'));
            // $('#addModal #endDate').val(moment(date._d).format('YYYY/MM/DD'));
            // $('#addModal').modal();
            
            $('#total_price').val(0);
            $('#total_price_lb').html(0);
            //$('#total_qty').html(0);
            $("#addToBookingBtn").removeClass("btn-primary");
            $("#addToBookingBtn").addClass("disabled btn-default");
            
            showAvailableTime(partner_id, moment(date._d).format('YYYY-MM-DD'));
        },
        eventClick: function(event, jsEvent, view) { 
            //console.log(event);console.log('event clicked');
            $('.fc-bg tbody tr td').css('background-color', '#fff');
            $(this).css('background-color', '#eaf8fa');
            $('#selected_date').html('Selected <b>'+moment(event.start).format('ddd, Do MMM YYYY')+'</b>');
            // $('#deleteBtn').remove();
            // $('#editModal #host_id').val([event.host_id]).trigger('change');
            // $('#editModal #partner_id').select2().select2('val',event.partner_id);
            
            // $('#editModal #message').val(event.message);
            // $('#editModal #startDate').val(moment(event.start).format('YYYY/MM/DD'));
            // $('#editModal #startTime').val(event.startTime);
            // if(event.end) {
            //     $('#editModal #endDate').val(moment(event.end).format('YYYY/MM/DD'));
            //     $('#editModal #endTime').val(event.endTime);
            // } else {
            //     $('#editModal #endDate').val(moment(event.start).format('YYYY/MM/DD'));
            //     $('#editModal #endTime').val(event.endTime);
            // }
            // $('#editModal #event_id').val(event.id);
            // $("#closeBtn").before('<button type="button" id="deleteBtn" onclick="return deleteEvent('+event.id+');" class="btn btn-danger pull-left">Delete</button>');
            // $('#editModal').modal();

            // $.ajax({
            //     type: "POST",
            //     url: adminurl + 'buskerspod_cal/currentDateEvent',
            //     data: 'startDate=' + moment(event.start).format('YYYY/MM/DD'),
            //     success: function( data ) {
            //         $('#current_event_list').html(data);
            //     }
            // });

            $('#total_price').val(0);
            $('#total_price_lb').html(0);
            //$('#total_qty').html(0);

            $("#addToBookingBtn").removeClass("btn-primary");
            $("#addToBookingBtn").addClass("disabled btn-default");

            showAvailableTime(partner_id, moment(event.start).format('YYYY-MM-DD'));

        },
    });
});
function showAvailableTime(partner_id, date){
  if(partner_id){
    $.ajax({
         type: "POST",
         url: baseurl + 'talent/buskerspod/showAvailableTime',
         async : false,
         data: {
            partner_id : partner_id, date : date
         },
         beforeSend: function() { 
            $("#availablehourslist").html('<tr><td colspan="5"><center><img src="'+baseurl+'assets/img/loading.gif"></center></td></tr>');
        },
        success: function(html) {
            $("#availablehourslist").html(html).show();
        }
    });
    return false;
  }
}

function addToBooking(partner_id){
  checkSession();

  var eventids = [];
  var isEmpty  = 0;
  $("input[name='event_id[]']:checked").each(function() {
     eventids.push($(this).val());
     isEmpty = 1;
  });
  if(isEmpty == 0){
    toastr.error( 'please select .','Error' );
  }
  if(partner_id){
    $.ajax({
        type: "POST",
        url: baseurl + 'talent/buskerspod/addToBooking',
        async : false,
        dataType: 'json',
        data: {
          partner_id : partner_id, eventids : eventids
        },
        success: function(res) {
          if(res.status == 'error'){
            toastr.error( res.msg,'Error' );
          }
          if(res.status == 'success'){
            toastr.success( res.msg,'Success' );
            $("#reviewBookingBtn").addClass("btn-primary");
            $("#reviewBookingBtn").removeClass("disabled");

            if(sessionStorage.getItem("counter") == '' || sessionStorage.getItem("counter") == null){
              $('#ccBtn').show();
              clearInterval(interval);
              var interval = setInterval(counter, 1000);
            }
          }
        },
        error: function(xhr, statusText, err){
        }
    });
    return false;
  }
}

function showPartnerInfo(partner_id){
  if(partner_id){
    window.location.href = baseurl + 'talent/buskerspod?partner_id='+partner_id;
  }
}
function resetBookingPage(partner_id=''){

  $.ajax({
    type: "GET",
    data: {},
    url: baseurl + 'talent/buskerspod/bookingtimeout',
    success: function( html ) {
      sessionStorage.setItem("counter", "");
      sessionStorage.setItem("orv_counter", "");
      sessionStorage.setItem("set_refresh", "");
      if(partner_id){
        window.location.href = baseurl + 'talent/buskerspod?partner_id='+partner_id;
      }else{
        window.location.href = baseurl + 'talent/buskerspod';
      }
    }
  });
}

// count the total amount....
$(document).ready(function() {
  function calculateSum(){
    var sumTotal   = 0;
    var totalCount = 0;
    $('table tbody tr').each(function() {
      var $tr = $(this);
      if ($tr.find('input[type="checkbox"]').is(':checked')) {
        var $columns = $tr.find('td').next('td').next('td');
        var $Qnty = parseInt($tr.find('input[name="qnty[]"]').val());
        //var $Cost = parseInt($columns.next('td').html().split('$')[1]);
        //var $Cost = parseInt($columns.next('td.pod_price').html();
        var $Cost = parseInt($tr.find('input[name="pod_price[]"]').val());
        sumTotal += $Qnty*$Cost;
        totalCount +=1;
      }
    });
    $("#total_price").val(sumTotal);
    document.getElementById("total_price_lb").innerHTML = sumTotal;

    if(totalCount > 0){
      $("#addToBookingBtn").addClass("btn-primary");
      $("#addToBookingBtn").removeClass("disabled");
    }else{
      $("#addToBookingBtn").addClass("disabled btn-default");
      $("#addToBookingBtn").removeClass("btn-primary");
    }
  }
  $(document).on('change', '.select_time', function() {
    calculateSum();
    var c_total_qty = $('#total_qty').html();
    //document.getElementById("total_qty").textContent = + document.querySelectorAll("input[name='event_id[]']:checked").length;
    var total_qty = parseInt(document.querySelectorAll("input[name='event_id[]']:checked").length);
    $('#total_qty').html(total_qty);
  });

  // $("input[type='checkbox']").change(function() {
  //   calculateSum();
  // });
});
</script>
<script type="text/javascript">
$(document).ready(function(){
  var maxLength = 300;
  $(".show-read-more").each(function(){
    var myStr = $(this).text();
    if($.trim(myStr).length > maxLength){
      var newStr = myStr.substring(0, maxLength);
      var removedStr = myStr.substring(maxLength, $.trim(myStr).length);
      $(this).empty().html(newStr);
      $(this).append(' <a href="javascript:void(0);" class="read-more">read more...</a>');
      $(this).append('<span class="more-text">' + removedStr + '</span>');
    }
  });
  $(".read-more").click(function(){
    $(this).siblings(".more-text").contents().unwrap();
    $(this).remove();
  });
});
</script>
<script>
$(document).ready(function() {
  //On pressing a key on "Search box" in "search.php" file. This function will be called.
  $("#partner_search").keyup(function() {
       var name    = $('#partner_search').val();
       var city_id = $('#city_id').val();
       $.ajax({
           type: "POST",
           url: baseurl + 'talent/buskerspod/getPartnerLists',
           data: {
              search: name, city_id: city_id
           },
           success: function(html) {
               $("#city_lists").html(html).show();
           }
       });
  });

  $('select#city_id').on('change', function() {
    if(this.value){
      $.ajax({
         type: "POST",
         url: baseurl + 'talent/buskerspod/getPartnerLists',
         data: {
            search: '', city_id: this.value
         },
         success: function(html) {
            $("#city_lists").html(html).show();
         }
      });
    }
  });
});

function reviewBookingModal() {
  checkSession();
  
  var interval;
  clearInterval(interval);

  $( '#myModal' ).modal({ backdrop: 'static', keyboard: false });
  $( '#myModal #mymodalBody' ).html('<center><img src="'+baseurl+'assets/img/loading.gif"></center>');
  $( '#myModal #my_modal_footer' ).hide();
  $( '#myModal #mymodalTitle' ).html( 'Review Booking' );
  //var data = 'id='+id+'&field='+field;
  $.ajax({
    type: "POST",
    data: {},
    url: baseurl + 'talent/buskerspod/reviewBookingModal',
    success: function( data, textStatus, xhr ) {
      $( '#myModal #mymodalBody' ).html(data);

      if (sessionStorage.getItem("orv_counter") == '') {
          sessionStorage.setItem("orv_counter", 1);
          var counter  = function () {
            var timer2 = sessionStorage.getItem("counter");
            var timer  = timer2.split(':');
            //by parsing integer, I avoid all extra string processing
            var minutes = parseInt(timer[0], 10);
            var seconds = parseInt(timer[1], 10);
            --seconds;
            minutes = (seconds < 0) ? --minutes : minutes;
            
            if ((minutes <= 0 && seconds <= 0) || minutes == '-1' ){
              clearInterval(counter);
              sessionStorage.setItem("counter", '');
              sessionStorage.setItem("orv_counter", '');
              $('.orv_countdown').html('00:00');
              return false;
            }

            seconds = (seconds < 0) ? 59 : seconds;
            seconds = (seconds < 10) ? '0' + seconds : seconds;
            $('.orv_countdown').html(minutes + ':' + seconds);
            sessionStorage.setItem("counter", minutes + ':' + seconds);
            timer2 = minutes + ':' + seconds;
            
            $('.orv_countdown').html(timer2);
          };
          interval = setInterval(counter, 1000);
      }
    },
    error: function(xhr, textStatus, errorThrown) { 
      alert('Error!  Status = ' + xhr.status); 
    }
  });
  return false;
}

function checkSession(){
  $.ajax({
    type: "POST",
    data: {},
    url: baseurl + 'talent/buskerspod/checkSessionExpiry',
    success: function( data, textStatus, xhr ) {
      if(data != 2){
        window.location.href = baseurl + 'talent/logout';
      }
    }
  });
  return false;
}

</script>