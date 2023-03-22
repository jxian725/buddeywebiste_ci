<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl   = $this->config->item( 'admin_url' );
$site_name  = $this->config->item( 'site_name' );
$datetime   = date('Y-m-d H:i:s');

?>
<link href="<?= $assetUrl; ?>assets/css/bootstrap.min.css" rel="stylesheet">
<link href='<?= $assetUrl; ?>assets/css/fullcalendar.css' rel='stylesheet' />
<link href="<?= $assetUrl; ?>assets/css/bootstrapValidator.min.css" rel="stylesheet" />        
<link href="<?= $assetUrl; ?>assets/css/bootstrap-colorpicker.min.css" rel="stylesheet" />
<!-- Custom css  -->
<link href="<?= $assetUrl; ?>assets/css/custom.css" rel="stylesheet" />

<script src='<?= $assetUrl; ?>assets/js/moment.min.js'></script>
<script src="<?= $assetUrl; ?>assets/js/jquery.min.js"></script>
<script src="<?= $assetUrl; ?>assets/js/bootstrap.min.js"></script>
<script src="<?= $assetUrl; ?>assets/js/bootstrapValidator.min.js"></script>
<script src="<?= $assetUrl; ?>assets/js/fullcalendar.min.js"></script>
<script src='<?= $assetUrl; ?>assets/js/bootstrap-colorpicker.min.js'></script>
<script src='<?= $assetUrl; ?>assets/js/main.js'></script>

<link rel="stylesheet" href="<?php echo $assetUrl; ?>plugins/bootstrap-datepicker/bootstrap-datepicker.min.css">
<script src="<?php echo $assetUrl; ?>plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="<?= $assetUrl; ?>plugins/select2/select2.min.css">
<script src="<?= $assetUrl; ?>plugins/select2/select2.full.min.js"></script>

<link href="<?= $assetUrl; ?>plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
<script src="<?= $assetUrl; ?>plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>

<!-- Notification -->
<div class="alert"></div>
<div class="row clearfix">
    <div class="col-md-12 column">
        <div id='calendar'></div>
    </div>
</div>
<div class="modal fade event_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <div class="error"></div>
                <form class="form-horizontal" id="crud-form">
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
                                <option value="">Select</option>
                                <?php 
                                if( $hostLists ) { 
                                    foreach ( $hostLists as $key => $value ) {
                                      echo '<option value="'. $value->guider_id .'">'. $value->first_name .'</option>';
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
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$('.time').datetimepicker({
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

/*var today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
$('#startDate').datepicker({
    uiLibrary: 'bootstrap4',
    iconsLibrary: 'fontawesome',
    minDate: today,
    maxDate: function () {
        return $('#endDate').val();
    }
});
$('#endDate').datepicker({
    uiLibrary: 'bootstrap4',
    iconsLibrary: 'fontawesome',
    minDate: function () { alert($('#startDate').val());
        return $('#startDate').val();
    }
});*/
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

/*$( ".datepicker" ).datepicker({
    changeYear: true,
    format: 'yyyy-mm-dd',
    autoclose: true,
    maxDate: 0,
    endDate: '<?= $datetime; ?>',
    orientation: 'auto'
});*/
</script>