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

<?php echo form_open(site_url("buskerspod/addEvent"), array("id" => "addeventform", "class" => "form-horizontal", "autocomplete"=>"off")) ?>
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
        <label class="col-md-4 control-label" for="partnerFees">Partner Fees</label>
        <div class="col-md-8">
            <input type="text" class="form-control number" id="partnerFees" name="partnerFees">
        </div>
    </div>
    <div class="form-group">
          <label class="col-md-4 control-label" for="title">Date <span class="text-danger">*</span></label>
          <div class="col-md-4">
              <input type="text" class="form-control" name="startDate" id="startDate" placeholder="Start Date" width="276">
          </div>
          <div class="col-md-4">
              <input type="text" class="form-control" name="endDate" id="endDate" placeholder="End Date" width="276">
          </div>
    </div>
    <div class="form-group" id="time_range">
          <label class="col-md-4 control-label" for="title">Time <span class="text-danger">*</span></label>
          <div class="col-md-4">
              <input type="text" class="form-control time start" name="startTime" id="startTime" placeholder="Start Time">
          </div>
          <div class="col-md-4">
              <input type="text" class="form-control time end" name="endTime" id="endTime" placeholder="End Time">
          </div>
        <div class="col-md-offset-4 col-md-8" style="margin-top: 5px;">
            <input type="checkbox" name="split_time" id="split_time" value="1"> <label for="split_time">Split Time By one hour <label>
        </div>
    </div>
    <div class="form-group">
          <label class="col-md-4 control-label" for="message">Add Message</label>
          <div class="col-md-8">
              <textarea class="form-control" id="message" name="message"></textarea>
          </div>
    </div>
    <div class="row">
        <div class="col-md-12">
          <div class="pull-right">
            <button class="btn btn-primary" id="addeventbtn" value="Add Event" onclick="return addEventForm();">Add Event</button>
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
/*$('.time').datetimepicker({
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
});*/

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
        todayBtn: 1,
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
function addEventForm() {
    var isvalid     = 1;
    var partner_id  = $( '#addeventform #partner_id' ).val();
    var startDate   = $( '#addeventform #startDate' ).val();
    var endDate     = $( '#addeventform #endDate' ).val();
    var startTime   = $( '#addeventform #startTime' ).val();
    var endTime     = $( '#addeventform #endTime' ).val();
    var message     = $( '#addeventform #message' ).val();
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
        toastr.error( 'Please select valid start time.','Error' );
        isvalid = 0;
    }
    if(isvalid==1){
        var data    = $( 'form#addeventform' ).serialize();
        $.ajax( {
            type    : "POST",
            data    : data,
            url     : adminurl + 'buskerspod/addEventForm',
            dataType: 'json',
            beforeSend: function() { 
                $("#addeventbtn").html('Loading...');
                $("#addeventbtn").prop('disabled', true);
                $('#addeventform').css("opacity",".5");
            },
            success: function( data ) {
                if(data['success'] == 1){
                    $("#addeventbtn").html('Add Event');
                    $("#addeventbtn").prop('disabled', false);
                    $("form#addeventform").trigger("reset");
                    toastr.success( 'Event Information added successfully.','Success' );
                }else{
                    toastr.error( data['msg'],'Error' );
                    $("#addeventbtn").html('Add Event');
                    $("#addeventbtn").prop('disabled', false);
                }
                $('#addeventform').css("opacity","");
                setTimeout( function() {
                location.reload();
              }, 1000 );
            }
        });
    }
    return false;
}
</script>