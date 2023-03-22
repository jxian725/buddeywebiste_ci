<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl   = $this->config->item( 'admin_url' );
$dirUrl     = $this->config->item( 'dir_url' );
?>
<link href="<?= $dirUrl; ?>plugins/dataTables/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
<script src="<?= $dirUrl; ?>plugins/dataTables/jquery.dataTables.min.js"></script>
<script src="<?= $dirUrl; ?>plugins/dataTables/dataTables.bootstrap.min.js"></script>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                      <!-- Custom Tabs -->
                      <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                          <li class="active"><a href="#tab_1" data-toggle="tab"><?= HOST_NAME; ?> Feedback</a></li>
                          <li><a href="#tab_2" data-toggle="tab"><?= GUEST_NAME; ?> Feedback</a></li>
                          <li><a href="#tab_3" data-toggle="tab">Venue Partner Feedback</a></li>
                        </ul>
                        <div class="tab-content">
                          <div class="tab-pane active" id="tab_1">
                            <div class="box-body table-responsive no-padding">
                              <table id="guider_feedback_lists" class="table table-bordered  data-tbl">
                                <thead>
                                  <tr class="tbl_head_bg">
                                    <th class="head1 no-sort">#</th>
                                    <th class="head1 no-sort"><?= HOST_NAME; ?> Name</th>
                                    <th class="head1 no-sort">subject</th>
                                    <th class="head1 no-sort">description</th>
                                    <th class="head1 no-sort">app_version</th>
                                    <th class="head1 no-sort">Device Type</th>
                                    <th class="head1 no-sort">Date Time</th>
                                    <th class="head1 no-sort">Action</th>
                                  </tr>
                                </thead>
                                <tfoot>
                                  <tr class="tbl_head_bg">
                                    <th class="head2 no-sort"></th>
                                    <th class="head2 no-sort"></th>
                                    <th class="head2 no-sort"></th>
                                    <th class="head2 no-sort"></th>
                                    <th class="head2 no-sort"></th>
                                    <th class="head2 no-sort"></th>
                                    <th class="head2 no-sort"></th>
                                    <th class="head2 no-sort"></th>
                                  </tr>
                                </tfoot>
                              </table>
                            </div>
                          </div>
                          <!-- /.tab-pane -->
                          <div class="tab-pane" id="tab_2">
                            <div class="box-body table-responsive no-padding">
                              <table id="traveller_feedback_lists" class="table table-bordered  data-tbl">
                                <thead>
                                  <tr class="tbl_head_bg">
                                    <th class="head1 no-sort">#</th>
                                    <th class="head1 no-sort"><?= GUEST_NAME; ?> Name</th>
                                    <th class="head1 no-sort">subject</th>
                                    <th class="head1 no-sort">description</th>
                                    <th class="head1 no-sort">app_version</th>
                                    <th class="head1 no-sort">Device Type</th>
                                    <th class="head1 no-sort">Date Time</th>
                                    <th class="head1 no-sort">Action</th>
                                  </tr>
                                </thead>
                                <tfoot>
                                  <tr class="tbl_head_bg">
                                    <th class="head2 no-sort"></th>
                                    <th class="head2 no-sort"></th>
                                    <th class="head2 no-sort"></th>
                                    <th class="head2 no-sort"></th>
                                    <th class="head2 no-sort"></th>
                                    <th class="head2 no-sort"></th>
                                    <th class="head2 no-sort"></th>
                                    <th class="head2 no-sort"></th>
                                  </tr>
                                </tfoot>
                              </table>
                            </div>
                          </div>
                        <!-- /.tab-content -->
                        <!-- /.tab-pane -->
                          <div class="tab-pane" id="tab_3">
                            <div class="box-body table-responsive no-padding">
                              <table id="venuepartner_feedback_lists" class="table table-bordered  data-tbl">
                                <thead>
                                  <tr class="tbl_head_bg">
                                    <th class="head1 no-sort">#</th>
                                    <th class="head1 no-sort">Venue Partner Name</th> 
                                    <th class="head1 no-sort">subject</th>
                                    <th class="head1 no-sort">description</th>
                                    <th class="head1 no-sort">Date Time</th>
                                    <th class="head1 no-sort">Action</th>
                                  </tr>
                                </thead>
                                <tfoot>
                                  <tr class="tbl_head_bg">
                                    <th class="head2 no-sort"></th>
                                    <th class="head2 no-sort"></th>
                                    <th class="head2 no-sort"></th>
                                    <th class="head2 no-sort"></th>
                                    <th class="head2 no-sort"></th>
                                    <th class="head2 no-sort"></th>
                                  </tr>
                                </tfoot>
                              </table>
                            </div>
                          </div>
                        </div>
                        <!-- /.tab-content -->
                      </div>
                      <!-- nav-tabs-custom -->
                    </div>  
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
  table = $('#guider_feedback_lists').DataTable({
      language: {
        processing: "<img src='<?php echo $dirUrl;?>img/loading.gif'>",
      },
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "order": [], //Initial no order.
      'autoWidth': false,
      "ajax": {
          "data": function(d) {
          },
          "url": "<?php echo site_url('/feedback/guiderFeedbackTableResponse')?>",
          "type": "POST"
      },
      "dom": "B lrt<'row' <'col-sm-5' i><'col-sm-7' p>>",
      "lengthMenu": [[10, 25, 50, 100, 1000], [10, 25, 50, 100, 1000]],
      //Set column definition initialisation properties.
      "columnDefs": [{
          "targets": [0,7],
          "orderable": false, //set not orderable
      },
      {
          "targets": [0,7],
          "searchable": false, //set orderable
      } ],
  });
  var guider_lists      = <?php echo json_encode($guider_lists) ?>;
  var i = 0;
  $('#guider_feedback_lists tfoot th').each( function () {
      if(i == 1){
        var invet_selectbox = '<select name="guider_id" id="guider_id" class="form-control">'
                                +'<option value="">All</option>';
        $.each(guider_lists, function (i, elem) {
            invet_selectbox += '<option value="'+ elem['guider_id'] +'">'+ elem['first_name'] +'</option>';
        });
        invet_selectbox += '</select>';
        $(this).html( invet_selectbox );
      }
    i++;
  });
  // DataTable
  var table = $('#guider_feedback_lists').DataTable();
  // Apply the search
  table.columns().every( function () {
    var that = this;
    $( 'input', this.footer() ).on( 'keyup change', function () {
      if ( that.search() !== this.value ) {
        that
          .search( this.value )
          .draw();
      }
    });
    $( 'select', this.footer() ).on( 'change', function () {
      if ( that.search() !== this.value ) {
        that
          .search( this.value )
          .draw();
      }
    });
  });
  $('#findReplacement').on('click', function(){
      table.draw();
  });
});
</script>
<script type="text/javascript">
$(document).ready(function() {
  table = $('#traveller_feedback_lists').DataTable({
      language: {
        processing: "<img src='<?php echo $dirUrl;?>img/loading.gif'>",
      },
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "order": [], //Initial no order.
      'autoWidth': false,
      "ajax": {
          "data": function(d) {
          },
          "url": "<?php echo site_url('/feedback/travellerFeedbackTableResponse')?>",
          "type": "POST"
      },
      "dom": "B lrt<'row' <'col-sm-5' i><'col-sm-7' p>>",
      "lengthMenu": [[10, 25, 50, 100, 1000], [10, 25, 50, 100, 1000]],
      //Set column definition initialisation properties.
      "columnDefs": [{
          "targets": [0,7],
          "orderable": false, //set not orderable
      },
      {
          "targets": [0,7],
          "searchable": false, //set orderable
      } ],
  });
  var traveller_lists  = <?php echo json_encode($traveller_lists) ?>;
  var i = 0;
  $('#traveller_feedback_lists tfoot th').each( function () {
      if(i == 1){
        var invet_selectbox = '<select name="traveller_id" id="traveller_id" class="form-control">'
                                +'<option value="">All</option>';
        $.each(traveller_lists, function (i, elem) {
            invet_selectbox += '<option value="'+ elem['traveller_id'] +'">'+ elem['first_name'] +'</option>';
        });
        invet_selectbox += '</select>';
        $(this).html( invet_selectbox );
      }
    i++;
  });
  // DataTable
  var table = $('#traveller_feedback_lists').DataTable();
  // Apply the search
  table.columns().every( function () {
    var that = this;
    $( 'input', this.footer() ).on( 'keyup change', function () {
      if ( that.search() !== this.value ) {
        that
          .search( this.value )
          .draw();
      }
    });
    $( 'select', this.footer() ).on( 'change', function () {
      if ( that.search() !== this.value ) {
        that
          .search( this.value )
          .draw();
      }
    });
  });
  $('#findReplacement').on('click', function(){
      table.draw();
  });
});
</script>
<!-- VENUE PARTNER TABEL RESPONSE -->
<script type="text/javascript">
$(document).ready(function() {
  table = $('#venuepartner_feedback_lists').DataTable({
      language: {
        processing: "<img src='<?php echo $dirUrl;?>img/loading.gif'>",
      },
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "order": [], //Initial no order.
      'autoWidth': false,
      "ajax": {
          "data": function(d) {
          },
          "url": "<?php echo site_url('/feedback/venuepartnerFeedbackTableResponse')?>",
          "type": "POST"
      },
      "dom": "B lrt<'row' <'col-sm-5' i><'col-sm-7' p>>",
      "lengthMenu": [[10, 25, 50, 100, 1000], [10, 25, 50, 100, 1000]],
      //Set column definition initialisation properties.
      "columnDefs": [{
          "targets": [0,5],
          "orderable": false, //set not orderable
      },
      {
          "targets": [0,5],
          "searchable": false, //set orderable
      } ],
  });
  var guider_lists      = <?php echo json_encode($guider_lists) ?>;
  var i = 0;
  $('#venuepartner_feedback_lists tfoot th').each( function () {
      if(i == 1){
        var invet_selectbox = '<select name="guider_id" id="guider_id" class="form-control">'
                                +'<option value="">All</option>';
        $.each(guider_lists, function (i, elem) {
            invet_selectbox += '<option value="'+ elem['guider_id'] +'">'+ elem['first_name'] +'</option>';
        });
        invet_selectbox += '</select>';
        $(this).html( invet_selectbox );
      }
    i++;
  });
  // DataTable
  var table = $('#venuepartner_feedback_lists').DataTable();
  // Apply the search
  table.columns().every( function () {
    var that = this;
    $( 'input', this.footer() ).on( 'keyup change', function () {
      if ( that.search() !== this.value ) {
        that
          .search( this.value )
          .draw();
      }
    });
    $( 'select', this.footer() ).on( 'change', function () {
      if ( that.search() !== this.value ) {
        that
          .search( this.value )
          .draw();
      }
    });
  });
  $('#findReplacement').on('click', function(){
      table.draw();
  });
});
</script>
<!-- END  -->
<script type="text/javascript">
function deleteGuiderFeedback( support_id ) {
    $( '#myModal .modal-title' ).html( 'Confirm' );
    $( '#myModal .modal-body' ).html( 'Are you sure want to Delete this feedback ?' );
    $( '#myModal .modal-body' ).append( '<br /><br /><button class="btn btn-info btn-sm" id="continuemodal" data-dismiss="modal">Yes</button>&nbsp;&nbsp;<button aria-hidden="true" data-dismiss="modal" class="btn btn-danger btn-sm">Cancel</button>' );
    $( '#myModal' ).modal({ backdrop: 'static', keyboard: false }); 
    $( "#continuemodal" ).click(function() {
      var data = { 'support_id':support_id }
      $.ajax({
        type: "POST",
        url: adminurl + 'feedback/deleteGuiderFeedback',
        data: data,
        success: function( data ) {
          toastr.success( 'Host Feedback Delete Successfully.','Success' );
          setTimeout( function() { 
            location.reload();
          }, 2000 );
        }
      });
  });    
  return false;
}
function deleteTravellerFeedback( support_id ) {
    $( '#myModal .modal-title' ).html( 'Confirm' );
    $( '#myModal .modal-body' ).html( 'Are you sure want to Delete this feedback ?' );
    $( '#myModal .modal-body' ).append( '<br /><br /><button class="btn btn-info btn-sm" id="continuemodal" data-dismiss="modal">Yes</button>&nbsp;&nbsp;<button aria-hidden="true" data-dismiss="modal" class="btn btn-danger btn-sm">Cancel</button>' );
    $( '#myModal' ).modal({ backdrop: 'static', keyboard: false }); 
    $( "#continuemodal" ).click(function() {
      var data = { 'support_id':support_id }
      $.ajax({
        type: "POST",
        url: adminurl + 'feedback/deleteTravellerFeedback',
        data: data,
        success: function( data ) {
          toastr.success( 'Guest Feedback Delete Successfully.','Success' );
          setTimeout( function() {
            location.reload();
          }, 2000 );
        }
      });
  });    
  return false;
}
function deletevenuepartnerFeedback( support_id ) {
    $( '#myModal .modal-title' ).html( 'Confirm' );
    $( '#myModal .modal-body' ).html( 'Are you sure want to Delete this feedback ?' );
    $( '#myModal .modal-body' ).append( '<br /><br /><button class="btn btn-info btn-sm" id="continuemodal" data-dismiss="modal">Yes</button>&nbsp;&nbsp;<button aria-hidden="true" data-dismiss="modal" class="btn btn-danger btn-sm">Cancel</button>' );
    $( '#myModal' ).modal({ backdrop: 'static', keyboard: false }); 
    $( "#continuemodal" ).click(function() {
      var data = { 'support_id':support_id }
      $.ajax({
        type: "POST",
        url: adminurl + 'feedback/deletevenuepartnerFeedback',
        data: data,
        success: function( data ) {
          toastr.success( 'Venuepartner Feedback Delete Successfully.','Success' );
          setTimeout( function() {
            location.reload();
          }, 2000 );
        }
      });
  });    
  return false;
}
function addFeedback(support_id,field) {
    $( '#myModal' ).modal( 'show' );
    $( '#myModal .modal-body' ).html('<img src="<?php echo $dirUrl;?>img/loading.gif">');
    var title = '';
    if(field == 0){
      title = 'Feedback Response';
    }
    $( '#myModal .modal-title' ).html( title );
    var data = 'support_id='+support_id+'&field='+field;
      $.ajax({
        type: "POST",
        data: data,
        url: adminurl + 'feedback/commandFeedback',
        success: function( data ) {
          $( '#myModal .modal-body' ).html(data);
          $( '#myModal .modal-footer' ).html('');
        }
      });
    return false;
}
</script>