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
    <div class="box-header with-border">
      <div class="pull-right box-tools">
	    </div>
    </div>
    <div class="box-body">
        <div class="row">
          <div class="clearfix margin_b10"></div>
          <div class="col-md-12">
              <div class="box-body table-responsive no-padding">
                <table id="request_list" class="table table-bordered  data-tbl">
                  <thead>
                    <tr class="tbl_head_bg">
                      <th class="head1 no-sort">#</th>
                      <th class="head1 no-sort">Full name</th>
                      <th class="head1 no-sort">Phone Number</th>
                      <th class="head1 no-sort">Email</th>
                      <th class="head1 no-sort">Skill</th>
                      <th class="head1 no-sort">City</th>
                      <th class="head1 no-sort">Initial Budget(<?= CURRENCYCODE; ?>)</th>
                      <th class="head1 no-sort">Confirmed Budget(<?= CURRENCYCODE; ?>)</th>
                      <th class="head1 no-sort">Occasion</th>
                      <th class="head1 no-sort">Venue</th>
                      <th class="head1 no-sort">Time/Hours:</th>
                      <th class="head1 no-sort">Created</th>
                      <th class="head1 no-sort">Other Info</th>
                      <th class="head1 no-sort">Payment Type</th>
                      <th class="head1 no-sort">Status</th>
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
            <div class="clearfix"></div>
          </div>
        </div>
      <div class="clearfix"></div>
    </div><!-- panel body -->
  </div>
</div><!-- content -->
</div>
<script type="text/javascript">
$(document).ready(function() {
  table = $('#request_list').DataTable({
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
          "url": "<?php echo site_url('/request/pendingtripTableResponse')?>",
          "type": "POST"
      },
      "dom": "B lrt<'row' <'col-sm-5' i><'col-sm-7' p>>",
      "lengthMenu": [[10, 25, 50, 100, 1000], [10, 25, 50, 100, 1000]],
      //Set column definition initialisation properties.
      "columnDefs": [{
          "targets": [0,14,15],
          "orderable": false, //set not orderable
      },
      {
          "targets": [0,14,15],
          "searchable": false, //set orderable
      } ],
  });
  var i = 0;
  $('#request_list tfoot th').each( function () {
      var title = $(this).text();
      if( i == 1 || i == 2 || i == 3 || i == 4 ||i == 5 || i == 6 || i == 7 ){
        $(this).html( '<input type="text" class="form-control" placeholder="" />' );
      }
    i++;
  });
  // DataTable
  var table = $('#request_list').DataTable();
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
});
function requestStatus( request_id, status ) {
  var table2 = $('#request_list').DataTable();
  $( '#myModal .modal-title' ).html( 'Confirm' );
  $( '#myModal .modal-body' ).html( 'Are you sure want to Cancel this request ?' );
  $( '#myModal .modal-body' ).append( '<br /><br /><button class="btn btn-info btn-sm" id="continuemodal" data-dismiss="modal">Yes</button>&nbsp;&nbsp;<button aria-hidden="true" data-dismiss="modal" class="btn btn-danger btn-sm">Cancel</button>' );
  $( '#myModal' ).modal({ backdrop: 'static', keyboard: false }); 
  $( "#continuemodal" ).click(function() {
    var data = { 'request_id':request_id,'status':status }
    $.ajax({
      type: "POST",
      url: adminurl + 'request/requestStatus',
      data: data,
      success: function( data ) {
        if(data == 1){
          toastr.success( 'Request Status Updated Successfully.','Success' );
          table2.ajax.reload();
        }else{
          toastr.error( 'Operation Failed.','Error' );
        }
      }
    });
});
return false;
}
function activeRequest( request_id ) {
  $.ajax( {
      type: "POST",
      data: {'request_id':request_id},
      url: adminurl + 'request/confirmRequestForm',
      success: function( msg ) {
        $( '#myModal .modal-title' ).html( 'Confirm Request' );
        $( '#myModal .modal-body' ).html( msg );
        $( '#myModal .modal-footer' ).html( '' );
        $( '#myModal' ).modal( 'show' );
      }
  });
  return false;
}
function showLinkModal( request_id ) {
  $.ajax({
      type  : 'POST',
      url   : adminurl + 'request/showLinkModal',
      data  : { 'request_id':request_id },
      async : false,
      success     : function( msg ) {
        $( '#myModal' ).modal( 'show' );
        $('#myModal .modal-title').html('Show Payment Link');
        $( '#myModal .modal-body' ).html(msg);
      }
  });
  return false;
}
function showCompletePayModal( request_id ) {
  $.ajax({
      type  : 'POST',
      url   : adminurl + 'request/showCompletePayModal',
      data  : { 'request_id':request_id },
      async : false,
      success     : function( msg ) {
        $( '#myModal' ).modal( 'show' );
        $('#myModal .modal-title').html('Completed Cash Payment');
        $( '#myModal .modal-body' ).html(msg);
      }
  });
  return false;
}
</script>