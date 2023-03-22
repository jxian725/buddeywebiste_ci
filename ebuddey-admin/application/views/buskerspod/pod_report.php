<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl   = $this->config->item( 'base_url' );
global $permission_arr;
?>
<style type="text/css">
  .buttons-excel{
    background: #3F51B5 !important;
    border-color: #9da8e2 !important;
    color: #fff !important;
    border: 1px solid transparent !important;
    border-radius: 4px !important;
  }
  .dataTables_length{ float: right !important; }
</style>
<link href="<?= $assetUrl; ?>assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
<link href="<?= $assetUrl; ?>assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
<script src="<?= $assetUrl; ?>assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
<script src="<?= $assetUrl; ?>assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo $assetUrl; ?>plugins/bootstrap-datepicker/bootstrap-datepicker.min.css">
<script type="text/javascript" src="<?php echo $assetUrl; ?>plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<div class="row">
  <div class="col-xs-12 col-sm-12">
    <div class="box box-primary">
      <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <!-- Search Name -->

              <form class="form-inline mr-auto input-daterange text-center" autocomplete="off" style="margin-bottom: 10px;">
                <input type="text" name="date_from" id="date_from" class="form-control" placeholder="Date From" aria-label="Date From">
                <input type="text" name="date_to" id="date_to" class="form-control" placeholder="Date To" aria-label="Date To">
                <button class="btn btn-primary btn-sm" id="filterbtn" type="button">Filter</button>
              </form>
            </div>
            <div class="col-md-12 table-container">
                <div class="box-body no-padding">
                  <div class="">
                    <table id="favorite_message_list" class="table table-striped table-bordered table-hover table-checkable dataTable data-tbl">
                      <thead>
                        <tr class="tbl_head_bg">
                          <th class="head1 no-sort">#</th>
                          <th class="head1 no-sort">Partner Name</th>
                          <th class="head1 no-sort">Additional Info</th>
                          <th class="head1 no-sort">City</th>
                          <th class="head1 no-sort" style="width: 70px;">Event Date</th>
                          <th class="head1 no-sort">Time Start</th>
                          <th class="head1 no-sort">Time End</th>
                          <th class="head1 no-sort">PRICE PER HOUR</th>
                          <th class="head1 no-sort">Paid</th>
                          <th class="head1 no-sort">transactionID</th>
                          <th class="head1 no-sort">PAYMENT DATE</th>
                          <th class="head1 no-sort">SENANG PAY TRANSACTION REF</th>
                          <th class="head1 no-sort"><?= HOST_NAME; ?> Name</th>
                          <th class="head1 no-sort"><?= HOST_NAME; ?> Email</th>
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
$(document).ready(function(){
  $(".input-daterange").datepicker({
      format: 'dd-mm-yyyy',
      orientation: 'top',
      autoclose: true
  });
});

$(document).ready(function() {
  var csrf_test_name = '<?php echo $this->security->get_csrf_token_name(); ?>';
  var csrf_hash  = '<?php echo $this->security->get_csrf_hash(); ?>';
  table = $('#favorite_message_list').DataTable({
      language: {
        processing: "<img src='<?php echo base_url();?>img/loading.gif'>",
      },
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "order": [], //Initial no order.
      'autoWidth': false,
      "ajax": {
          "data": function(d) {
            d.csrf_test_name = csrf_hash;
            d.date_from   = $.trim($('#date_from').val());
            d.date_to     = $.trim($('#date_to').val());
          },
          "url": "<?php echo site_url('/buskerspod_report/podReportTableResponse')?>",
          "type": "POST"
      },
      "dom": "B lrt<'row' <'col-sm-5' i><'col-sm-7' p>>",
      //"dom": "B lrt<'row' <'col-sm-5' i><'col-sm-7' p>>",
      buttons: [
                {
                extend: 'excel',
                exportOptions: {
                    columns: [ 1,3,4,5,6,7,8,10,11,12,13]
                }
            },
      ],
      "lengthMenu": [[10, 25, 50, 100, 1000, -1], [10, 25, 50, 100, 1000, "All"]],
      //Set column definition initialisation properties.
      "columnDefs": [{
          "targets": [0,13],
          "orderable": false, //set not orderable
      },
      {
          "targets": [0,13],
          "searchable": false, //set orderable
      },
      {
          "targets": [7,10,11],
          "visible": false, //set orderable
      }],
  });
  var i = 0;
  $('#favorite_message_list tfoot th').each( function () {
    if( i == 1 || i == 2 ){
      $(this).html( '<input type="text" class="form-control" placeholder="" />' );
    } else if(i == 4){
      $(this).html( '<input type="text" class="form-control datepicker" placeholder="YYYY-MM-DD" />' );
    }
    i++;
  });

  // DataTable
  var table = $('#favorite_message_list').DataTable();

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
  $('#filterbtn').on('click', function(){
      table.draw();
  });
});
</script>