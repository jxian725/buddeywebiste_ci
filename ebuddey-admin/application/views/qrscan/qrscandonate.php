<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl   = $this->config->item( 'admin_url' );
$dirUrl     = $this->config->item( 'dir_url' );

?>
<link href="<?= $dirUrl; ?>plugins/dataTables/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
<script src="<?= $dirUrl; ?>plugins/dataTables/jquery.dataTables.min.js"></script>
<script src="<?= $dirUrl; ?>plugins/dataTables/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="<?php echo $dirUrl; ?>plugins/bootstrap-datepicker/bootstrap-datepicker.min.css">
<script type="text/javascript" src="<?php echo $dirUrl; ?>plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<div class="row">
<div class="col-xs-12 col-sm-12">
  <div class="box box-primary">
    <div class="box-header with-border">
      <div class="pull-right box-tools">
	    </div>
    </div>
    <div class="box-body">
        <div class="row">
          <div class="col-md-12">
            <form class="form-inline mr-auto input-daterange text-center" autocomplete="off" style="margin-bottom: 10px;">
              <input type="text" name="date_from" id="date_from" class="form-control" placeholder="Date From" aria-label="Date From">
              <input type="text" name="date_to" id="date_to" class="form-control" placeholder="Date To" aria-label="Date To">
              <button class="btn btn-primary btn-sm" id="filterbtn" type="button">Filter</button>
            </form>
          </div>
          <div class="clearfix margin_b10"></div>
          <div class="col-md-12">
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                <div class="box-body table-responsive no-padding">
                  <a href="javascript:;" class="btn btn-primary btn-sm excel_btn" title="Export" onclick="return exportExcelForm();"><i class="fa fa-file-excel-o"></i> Export to Excel</a>
                  <table id="donation_list" class="table table-bordered  data-tbl">
                    <thead>
                      <tr class="tbl_head_bg">
                        <th class="head1 no-sort">#</th>
                        <th class="head1 no-sort">Talents</th>
                        <th class="head1 no-sort">Email</th>
                        <th class="head1 no-sort">Gift date</th>
                        <th class="head1 no-sort">Donors name</th>
                        <th class="head1 no-sort">Transaction ID</th>
                        <th class="head1 no-sort">Amount (<?= CURRENCYCODE; ?>)</th>
                        <th class="head1 no-sort">Transaction fee (<?= CURRENCYCODE; ?>)</th>
                        <th class="head1 no-sort">Total Gifts (<?= CURRENCYCODE; ?>)</th>
                        <th class="head1 no-sort">Status</th>
                        <th class="head1 no-sort">Action</th>
                        <th class="head1 no-sort">Last Change</th>
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
          <div class="col-md-12">
            <div class="clearfix"></div>
          </div>
        </div>
      <div class="clearfix"></div>
    </div><!-- panel body -->
  </div>
</div><!-- content -->
</div>
<script type="text/javascript">
$(document).ready(function(){
  $(".input-daterange").datepicker({
      format: 'dd-mm-yyyy',
      orientation: 'top',
      autoclose: true
  });
});
$(document).ready(function() {
    table = $('#donation_list').DataTable({
      language: {
        processing: "<img src='<?php echo $dirUrl;?>img/loading.gif'>",
      },
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "order": [], //Initial no order.
      'autoWidth': false,
      "ajax": {
          "data": function(d) {
            d.date_from   = $.trim($('#date_from').val());
            d.date_to     = $.trim($('#date_to').val());
          },
          "url": "<?php echo site_url('/qrscandonate/donationTableResponse')?>",
          "type": "POST"
      },
      "dom": "B lrt<'row' <'col-sm-5' i><'col-sm-7' p>>",
      "lengthMenu": [[10, 25, 50, 100, 1000], [10, 25, 50, 100, 1000]],
      //Set column definition initialisation properties.
      "columnDefs": [{
          "targets": [0,7,8,10],
          "orderable": false, //set not orderable
      },
      {
          "targets": [0,7,8,10],
          "searchable": false, //set orderable
      }]
  });
  var guider_lists  = <?php echo json_encode($guider_lists) ?>;
  var i = 0;
  $('#donation_list tfoot th').each( function () {
      var title = $(this).text();
    i++;
  });

  // DataTable
  var table = $('#donation_list').DataTable();
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
    $('#filterbtn').on('click', function(){
      table.draw();
    });
  });
});
</script>
<script type="text/javascript">
function updateDonationStatus( payment_id, status ) {
    $( '#myModal .modal-title' ).html( 'Confirm' );
    if(status == 1){
      $( '#myModal .modal-body' ).html( 'Are you sure want to confirm payment this donation ?' );
    }else{
      $( '#myModal .modal-body' ).html( 'Are you sure want to pending this donation ?' );
    }
    $( '#myModal .modal-body' ).append( '<br /><br /><button class="btn btn-info btn-sm" id="continuemodal" data-dismiss="modal">Yes</button>&nbsp;&nbsp;<button aria-hidden="true" data-dismiss="modal" class="btn btn-danger btn-sm">Cancel</button>' );
    $( '#myModal' ).modal({ backdrop: 'static', keyboard: false }); 
    $( "#continuemodal" ).click(function() {
        var data = { 'payment_id':payment_id,'status':status }
        $.ajax({
          type: "POST",
          url: adminurl + 'qrscandonate/updateDonationStatus',
          data: data,
          success: function( data ) {
            toastr.success( 'Donation Status Updated Successfully.','Success' );
            setTimeout( function() {
              location.reload();
            }, 1000 );
          }
        });
    });    
    return false;
}
function exportExcelForm() {
  $( '#myModal' ).modal( 'show' );
  $( '#myModal .modal-body' ).html('<img src="<?=$dirUrl; ?>img/ajax-loader.gif" style="display: block; margin: 0 auto; width: 100px;" alt="loading..."/>');
  $('#myModal .modal-title').html( 'Export Booking' );
  var data = '';
  $.ajax( {
    type: "POST",
    data: data,
    url: adminurl + 'qrscandonate/exportExcelForm',
    success: function( msg ) {
      $( '#myModal .modal-body' ).html(msg);
      $( '#myModal .modal-footer' ).html('');
    }
  });
  return false;
}
</script>