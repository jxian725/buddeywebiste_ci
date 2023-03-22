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
                <table id="pendingtrip_list" class="table table-bordered  data-tbl">
                  <thead>
                    <tr class="tbl_head_bg">
                      <th class="head1 no-sort">#</th>
                      <th class="head1 no-sort">Trip Id</th>
                      <th class="head1 no-sort">Booking created datetime</th>
                      <th class="head1 no-sort"><?= GUEST_NAME; ?> Name</th>
                      <th class="head1 no-sort">Meeting Date</th>
                      <th class="head1 no-sort">Meeting Time</th>
                      <th class="head1 no-sort">No.of people</th>
                      <th class="head1 no-sort"><?= HOST_NAME; ?> Supporting location</th>
                      <th class="head1 no-sort"><?= HOST_NAME; ?> Charges (Per Person)(<?= CURRENCYCODE; ?>)</th>
                      <th class="head1 no-sort">Sub total(<?= CURRENCYCODE; ?>)</th>
                      <th class="head1 no-sort">Processing Fees(<?= CURRENCYCODE; ?>)</th>
                      <th class="head1 no-sort">Total Amount(<?= CURRENCYCODE; ?>)</th>
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
  table = $('#pendingtrip_list').DataTable({
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
          "url": "<?php echo site_url('/hostPortal/pendingrequest/pendingtripTableResponse')?>",
          "type": "POST"
      },
      "dom": "B lrt<'row' <'col-sm-5' i><'col-sm-7' p>>",
      "lengthMenu": [[10, 25, 50, 100, 1000], [10, 25, 50, 100, 1000]],
      //Set column definition initialisation properties.
      "columnDefs": [{
          "targets": [0,9,10,11,13],
          "orderable": false, //set not orderable
      },
      {
          "targets": [0,9,10,11,13],
          "searchable": false, //set orderable
      } ],
  });
  var guider_lists      = <?php echo json_encode($guider_lists) ?>;
  var traveller_lists   = <?php echo json_encode($traveller_lists) ?>;
  var i = 0;
  $('#pendingtrip_list tfoot th').each( function () {
      var title = $(this).text();
      if( i == 1 ){
        $(this).html( '<input type="text" class="form-control" placeholder="" />' );
      }else if(i == 3){
        var asset_selectbox = '<select name="traveller_id" id="traveller_id" class="form-control">'
                                +'<option value="">All</option>';
        $.each(traveller_lists, function (i, elem) {
            asset_selectbox += '<option value="'+ elem['traveller_id'] +'">'+ elem['first_name'] +'</option>';
        });
        asset_selectbox += '</select>';
        $(this).html( asset_selectbox );
      }else if(i == 12){
        var invet_selectbox = '<select name="guider_id" id="guider_id" class="form-control">'
                                +'<option value="">All</option>'
                                +'<option value="1">New Request</option>'
                                +'<option value="2">Pending Payment</option>'
                                +'<option value="3">Cancelled</option>'
        invet_selectbox += '</select>';
        $(this).html( invet_selectbox );
      }
    i++;
  });

  // DataTable
  var table = $('#pendingtrip_list').DataTable();

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
function requestAction( service_id,status ) {
  $( '#myModal .modal-title' ).html( 'Confirm' );
  if(status == 2){
      $( '#myModal .modal-body' ).html( 'Are you sure want to Accept this Request ?' );
  }else{
      $( '#myModal .modal-body' ).html( 'Are you sure want to Cancel this Request ?' );
  }
  $( '#myModal .modal-body' ).append( '<br /><br /><button class="btn btn-info btn-sm" id="continuemodal" data-dismiss="modal">Yes</button>&nbsp;&nbsp;<button aria-hidden="true" data-dismiss="modal" class="btn btn-danger btn-sm">Cancel</button>' );
  $( '#myModal' ).modal({ backdrop: 'static', keyboard: false }); 
  $( "#continuemodal" ).click(function() {
  var data = { 'service_id':service_id,'status':status }
  $.ajax({
      type: "POST",
      url: hosturl + 'pendingrequest/requestAction',
      data: data,
      success: function( data ) {
          toastr.success( 'Service request Status Updated Successfully.','Success' );
          setTimeout( function() {
          location.reload();
          }, 2000 );
      }
      });
  });    
  return false;
}
</script>