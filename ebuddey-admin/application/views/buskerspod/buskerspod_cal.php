<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl   = $this->config->item( 'admin_url' );
$site_name  = $this->config->item( 'site_name' );
$datetime   = date('Y-m-d H:i:s');

?>
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

<link rel="stylesheet" href="<?php echo $assetUrl; ?>plugins/bootstrap-datepicker/bootstrap-datepicker.min.css">
<script src="<?php echo $assetUrl; ?>plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="<?= $assetUrl; ?>plugins/select2/select2.min.css">
<script src="<?= $assetUrl; ?>plugins/select2/select2.full.min.js"></script>

<link href="<?= $assetUrl; ?>plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
<script src="<?= $assetUrl; ?>plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>

<style type="text/css">
span.select2-container {
    z-index:10050;
}
</style>

<!-- Notification -->
<div class="alert"></div>
<div class="row clearfix">
    <div class="col-md-12 column">
        <div id='calendar'></div>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add Calendar Event</h4>
      </div>
      <div class="modal-body">
      <?php echo form_open(site_url("buskerspod_cal/addEvent"), array("class" => "form-horizontal", "autocomplete"=>"off")) ?>
            <div class="form-group">
                <label for="partner_id" class="col-sm-4 control-label">Partner <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <select class="form-control select2" style="width: 100%;" data-placeholder="Select Partner" name="partner_id" id="partner_id">
                        <option value="">Select</option>
                        <?php 
                        if( $partnerList ) { 
                            foreach ( $partnerList as $key => $value ) {
                              echo '<option value="'. $value->partner_id .'">'. rawurldecode($value->partner_name) .'</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="host_id" class="col-sm-4 control-label">Host</label>
                <div class="col-sm-8">
                    <select class="form-control select2" style="width: 100%;" data-placeholder="Select Host" name="host_id" id="host_id">
                        <option value="0">Select</option>
                        <?php 
                        if( $hostLists ) { 
                            foreach ( $hostLists as $key => $value ) {
                              echo '<option value="'. intval($value->guider_id) .'">'. $value->first_name .'</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-4 control-label" for="title">Date</label>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="startDate" id="startDate" placeholder="Start Date" width="276">
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="endDate" id="endDate" placeholder="End Date" width="276">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-4 control-label" for="title">Time</label>
                <div class="col-md-4">
                    <input type="text" class="form-control time" name="startTime" id="startTime" placeholder="Start Time" width="276">
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control time" name="endTime" id="endTime" placeholder="End Time" width="276">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-4 control-label" for="message">Add Message</label>
                <div class="col-md-8">
                    <textarea class="form-control" id="message" name="message"></textarea>
                </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-primary" value="Add Event">
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Update Calendar Event</h4>
      </div>
      <div class="modal-body">
        <?php echo form_open(site_url("buskerspod_cal/updateEvent"), array("class" => "form-horizontal", "autocomplete"=>"off")) ?>
            <div class="form-group">
                <label for="partner_id" class="col-sm-4 control-label">Partner <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <select class="form-control select2" style="width: 100%;" data-placeholder="Select Partner" name="partner_id" id="partner_id">
                        <option value="">Select</option>
                        <?php 
                        if( $partnerList ) { 
                            foreach ( $partnerList as $key => $value ) {
                              echo '<option value="'. $value->partner_id .'">'. rawurldecode($value->partner_name) .'</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="host_id" class="col-sm-4 control-label">Host</label>
                <div class="col-sm-8">
                    <select class="form-control select2" style="width: 100%;" data-placeholder="Select Host" name="host_id" id="host_id">
                        <option value="0">Select</option>
                        <?php 
                        if( $hostLists ) { 
                            foreach ( $hostLists as $key => $value ) {
                              echo '<option value="'. intval($value->guider_id) .'">'. $value->first_name .'</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-4 control-label" for="title">Date</label>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="startDate" id="startDate" placeholder="Start Date" width="276">
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="endDate" id="endDate" placeholder="End Date" width="276">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-4 control-label" for="title">Time</label>
                <div class="col-md-4">
                    <input type="text" class="form-control time" name="startTime" id="startTime" placeholder="Start Time" width="276">
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control time" name="endTime" id="endTime" placeholder="End Time" width="276">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-4 control-label" for="message">Add Message</label>
                <div class="col-md-8">
                    <textarea class="form-control" id="message" name="message"></textarea>
                </div>
            </div>
            <input type="hidden" name="eventid" id="event_id" value="0" />
            <div class="row">
                <div class="col-md-12" style="text-align: right;">
                    <button type="button" id="closeBtn" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary" value="Update Event">
                </div>
            </div>
            <?php echo form_close() ?>
            <p></p>
            <div class="row">
                <div id="current_event_list"></div>
            </div>
        </div>
      <!-- <div class="modal-footer">
        <button type="button" id="closeBtn" class="btn btn-default" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-primary" value="Update Event">
      </div> -->
    </div>
  </div>
</div>

<script type="text/javascript">
$('.time').datetimepicker({
    dateFormat: '',
    format: 'hh:ii',
    weekStart: 1,
    todayBtn:  true,
    autoclose: true,
    todayHighlight: true,
    startView: 1,
    minView: 0,
    maxView: 1,
    forceParse: 0
});
$(document).ready(function(){
  $('.select2').select2();
  
});
$(document).ready(function(){

    $("#startDate").datepicker({
        todayBtn:  1,
        autoclose: true,
        format: 'yyyy-mm-dd',
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#endDate').datepicker('setStartDate', minDate);
    });

    $("#endDate").datepicker({autoclose: true, format: 'yyyy-mm-dd'})
        .on('changeDate', function (selected) {
            var maxDate = new Date(selected.date.valueOf());
            $('#startDate').datepicker('setEndDate', maxDate);
    });
});
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
                    url: '<?php echo base_url() ?>buskerspod_cal/get_events',
                    dataType: 'json',
                    data: {
                        // our hypothetical feed requires UNIX timestamps
                        start: start.unix(),
                        end: end.unix()
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
        dayClick: function(date, jsEvent, view) { console.log(date);
            date_last_clicked = $(this);
            $(this).css('background-color', '#bed7f3');
            $('#addModal #startDate').val(moment(date._d).format('YYYY/MM/DD'));
            $('#addModal #endDate').val(moment(date._d).format('YYYY/MM/DD'));
            $('#addModal').modal();
        },
        eventClick: function(event, jsEvent, view) { console.log(event);
            $('#deleteBtn').remove();
            $('#editModal #host_id').val([event.host_id]).trigger('change');
            $('#editModal #partner_id').select2().select2('val',event.partner_id);
            
            $('#editModal #message').val(event.message);
            $('#editModal #startDate').val(moment(event.start).format('YYYY/MM/DD'));
            $('#editModal #startTime').val(event.startTime);
            if(event.end) {
                $('#editModal #endDate').val(moment(event.end).format('YYYY/MM/DD'));
                $('#editModal #endTime').val(event.endTime);
            } else {
                $('#editModal #endDate').val(moment(event.start).format('YYYY/MM/DD'));
                $('#editModal #endTime').val(event.endTime);
            }
            $('#editModal #event_id').val(event.id);
            $("#closeBtn").before('<button type="button" id="deleteBtn" onclick="return deleteEvent('+event.id+');" class="btn btn-danger pull-left">Delete</button>');
            $('#editModal').modal();

            $.ajax({
                type: "POST",
                url: adminurl + 'buskerspod_cal/currentDateEvent',
                data: 'startDate=' + moment(event.start).format('YYYY/MM/DD'),
                success: function( data ) {
                    $('#current_event_list').html(data);
                }
            });

        },
    });
});
function deleteEvent( eventID ) {
  $( '#myModal .modal-title' ).html( 'Confirm' );
  $( '#myModal .modal-body' ).html( 'Are you sure want to delete the event ?' );
  $( '#myModal .modal-body' ).append( '<br /><br /><button class="btn btn-info btn-sm" id="continuemodal" data-dismiss="modal">Yes</button>&nbsp;&nbsp;<button aria-hidden="true" data-dismiss="modal" class="btn btn-danger btn-sm">Cancel</button>' );
  $( '#myModal' ).modal({ backdrop: 'static', keyboard: false }); 
  $( "#continuemodal" ).click(function() {
      var account_data = 'eventID=' + eventID;
      $.ajax({
        type: "POST",
        url: adminurl + 'buskerspod_cal/deleteEvent',
        data: account_data,
        success: function( data ) {
          toastr.success( 'Event deleted successfully.','Success' );
          setTimeout( function() {
            window.location.href = adminurl + 'buskerspod';
          }, 2000 );
        }
      });
  });    
  return false;
}
</script>