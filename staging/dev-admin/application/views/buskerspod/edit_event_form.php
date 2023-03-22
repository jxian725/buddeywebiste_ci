<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl   = $this->config->item( 'admin_url' );
$site_name  = $this->config->item( 'site_name' );
$datetime   = date('Y-m-d H:i:s');

?>
<link rel="stylesheet" href="<?php echo $assetUrl; ?>plugins/bootstrap-datepicker/bootstrap-datepicker.min.css">
<script src="<?php echo $assetUrl; ?>plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="<?= $assetUrl; ?>plugins/select2/select2.min.css">
<script src="<?= $assetUrl; ?>plugins/select2/select2.full.min.js"></script>

<!-- include input widgets; this is independent of Datepair.js -->
<link rel="stylesheet" type="text/css" href="<?= $assetUrl; ?>plugins/datepair/css/jquery.timepicker.css" />
<script type="text/javascript" src="<?= $assetUrl; ?>plugins/datepair/js/jquery.timepicker.js"></script>
<script type="text/javascript" src="<?= $assetUrl; ?>plugins/datepair/js/jquery.ptTimeSelect.js"></script>
<script type="text/javascript" src="<?= $assetUrl; ?>plugins/datepair/js/datepair.js"></script>
<script type="text/javascript" src="<?= $assetUrl; ?>plugins/datepair/js/jquery.datepair.js"></script>

<?php echo form_open(site_url("buskerspod/addEvent"), array("id" => "editeventform", "class" => "form-horizontal", "autocomplete"=>"off")) ?>
    <div class="form-group">
        <label for="partner_id" class="col-sm-4 control-label">Partner <span class="text-danger">*</span></label>
        <div class="col-sm-8">
            <select class="form-control select2" style="width: 100%;" data-placeholder="Select Partner" name="partner_id" id="partner_id">
                <option value="">Select</option>
                <?php 
                if( $partnerList ) { 
                    foreach ( $partnerList as $key => $value ) {
                    	echo '<option '.(($value->partner_id==$eventInfo->partner_id)? 'selected' : '').' value="'. $value->partner_id .'">'. rawurldecode($value->partner_name) .'</option>';
                    }
                }
                ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label" for="partnerFees">Partner Fees</label>
        <div class="col-md-8">
        	<input type="text" class="form-control number" id="partnerFees" name="partnerFees" value="<?= $partnerFees; ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label" for="title">Date <span class="text-danger">*</span></label>
        <div class="col-md-4">
            <input type="text" class="form-control" name="startDate" id="startDate" placeholder="Start Date" width="276" value="<?= date('Y-m-d', strtotime($eventInfo->start)); ?>">
        </div>
        <div class="col-md-4">
            <input type="text" class="form-control" name="endDate" id="endDate" placeholder="End Date" width="276" value="<?= date('Y-m-d', strtotime($eventInfo->end)); ?>">
        </div>
    </div>
    <div class="form-group" id="time_range">
          <label class="col-md-4 control-label" for="title">Time <span class="text-danger">*</span></label>
          <div class="col-md-4">
              <input type="text" class="form-control time start" name="startTime" id="startTime" placeholder="Start Time" width="276" value="<?= date('H:i', strtotime($eventInfo->start)); ?>">
          </div>
          <div class="col-md-4">
              <input type="text" class="form-control time end" name="endTime" id="endTime" placeholder="End Time" width="276" value="<?= date('H:i', strtotime($eventInfo->end)); ?>">
          </div>
    </div>
    <div class="form-group">
          <label class="col-md-4 control-label" for="message">Add Message</label>
          <div class="col-md-8">
              <textarea class="form-control" id="message" name="message"><?= $eventInfo->message; ?></textarea>
          </div>
    </div>
    <div class="row">
        <div class="col-md-12">
          <div class="pull-right">
            <input type="hidden" name="event_id" id="event_id" value="<?= $event_id; ?>">
            <button class="btn btn-primary" id="editeventbtn" value="Edit Event" onclick="return editEventForm();">Edit Event</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
    </div>
<?php echo form_close() ?>
<?php
$currentDate = date('Y-m-d');
?>
<script>
$('#time_range .time').timepicker({
    'showDuration': true,
    'timeFormat': 'H:i',
    'minTime': '06:00:00',
    'interval': 15
});

var time_rangeEl = document.getElementById('time_range');
var timeOnlyDatepair = new Datepair(time_rangeEl);
</script>
<script type="text/javascript">
$(document).ready(function() {
    $('.select2').select2();
    $('#partner_id').change(function(e) {
        var partner_id = $(this).val();
        if(partner_id){
            $.ajax({
                type    : "POST",
                data    : {'partner_id':partner_id},
                url     : adminurl + 'buskerspod/getPartnerFees',
                dataType: 'json',
                success: function( data ) {
                    $('#partnerFees').val(data);
                }
            });
            return false;
        }
    });
});
$(document).ready(function(){

    $("#startDate").datepicker({
        todayBtn:  1,
        maxDate: 0,
        startDate: '<?= $currentDate; ?>',
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
function editEventForm() {
    var isvalid     = 1;
    var partner_id  = $( '#editeventform #partner_id' ).val();
    var startDate   = $( '#editeventform #startDate' ).val();
    var endDate     = $( '#editeventform #endDate' ).val();
    var startTime   = $( '#editeventform #startTime' ).val();
    var endTime     = $( '#editeventform #endTime' ).val();
    var message     = $( '#editeventform #message' ).val();
    if(partner_id == ''){
        toastr.error( 'Please select the partner.','Error' );
        isvalid = 0;
    }
    if(startDate == ''){
        toastr.error( 'Please select the start date.','Error' );
        isvalid = 0;
    }
    if(endDate == ''){
        toastr.error( 'Please select the end date.','Error' );
        isvalid = 0;
    }
    if(startTime == ''){
        toastr.error( 'Please select the start time.','Error' );
        isvalid = 0;
    }
    if(endTime == ''){
        toastr.error( 'Please select the end time.','Error' );
        isvalid = 0;
    }
    if(startTime >= endTime){
        toastr.error( 'Please select valid start and end time.','Error' );
        isvalid = 0;
    }
    if(isvalid==1){
        var data    = $( 'form#editeventform' ).serialize();
        $.ajax( {
            type    : "POST",
            data    : data,
            url     : adminurl + 'buskerspod/editEventForm',
            dataType: 'json',
            beforeSend: function() { 
                $("#editeventbtn").html('Loading...');
                $("#editeventbtn").prop('disabled', true);
                $('#editeventform').css("opacity",".5");
            },
            success: function( data ) {
                if(data['success'] == 1){
                    $("#editeventbtn").html('Edit Event');
                    $("#editeventbtn").prop('disabled', false);
                    $("form#editeventform").trigger("reset");
                    toastr.success( 'Event Information updated successfully.','Success' );
                }else{
                    toastr.error( data['msg'],'Error' );
                    $("#editeventbtn").html('Edit Event');
                    $("#editeventbtn").prop('disabled', false);
                }
                $('#editeventform').css("opacity","");
                setTimeout( function() {
                location.reload();
              }, 1000 );
            }
        });
    }
    return false;
}
</script>