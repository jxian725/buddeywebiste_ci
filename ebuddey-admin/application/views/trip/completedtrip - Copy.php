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
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">Booking Per Pricing/Person</a></li>
              <li><a href="#tab_2" data-toggle="tab">Free Booking</a></li>
              <li><a href="#tab_3" data-toggle="tab">Web Request</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                <div class="box-body table-responsive no-padding">
                  <a href="javascript:;" class="btn btn-primary btn-sm excel_btn" title="Export" onclick="return exportExcelForm();"><i class="fa fa-file-excel-o"></i> Export to Excel</a>
                  <table id="completedtrip_list" class="table table-bordered  data-tbl">
                    <thead>
                      <tr class="tbl_head_bg">
                        <th class="head1 no-sort">#</th>
                        <th class="head1 no-sort">Date</th>
                        <th class="head1 no-sort">Category Name</th>
                        <th class="head1 no-sort">Host Name</th>
                        <th class="head1 no-sort">Transaction ID</th>
                        <th class="head1 no-sort">Guest Name</th>
                        <th class="head1 no-sort">Guest Gender</th>
                        <th class="head1 no-sort">Guest Email</th>
                        <th class="head1 no-sort">Additional Information</th>
                        <th class="head1 no-sort">Amount Paid(<?= CURRENCYCODE; ?>)</th>
                        <th class="head1 no-sort">Buddey Fee(<?= CURRENCYCODE; ?>)</th>
                        <th class="head1 no-sort">Balace(<?= CURRENCYCODE; ?>)</th>
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
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
                <?php 
                echo $this->load->view( 'trip/freebooking', $traveller_lists, true );
                ?>
              </div>
              <div class="tab-pane" id="tab_3">
                <?php 
                echo $this->load->view( 'trip/webrequest', $traveller_lists, true );
                ?>
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
$(document).ready(function() {
    table = $('#completedtrip_list').DataTable({
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
          "url": "<?php echo site_url('/completedtrip/completedtripTableResponse')?>",
          "type": "POST"
      },
      "dom": "B lrt<'row' <'col-sm-5' i><'col-sm-7' p>>",
      "lengthMenu": [[10, 25, 50, 100, 1000], [10, 25, 50, 100, 1000]],
      //Set column definition initialisation properties.
      "columnDefs": [{
          "targets": [0,10,11,12],
          "orderable": false, //set not orderable
      },
      {
          "targets": [0,10,11,12],
          "searchable": false, //set orderable
      }]
  });
  var guider_lists      = <?php echo json_encode($guider_lists) ?>;
  var traveller_lists   = <?php echo json_encode($traveller_lists) ?>;
  var i = 0;
  $('#completedtrip_list tfoot th').each( function () {
      var title = $(this).text();
      /*if( i == 1 ){
        $(this).html( '<input type="text" name="trip_id" id="trip_id" class="form-control" placeholder="" />' );
      }else if(i == 3){
        var asset_selectbox = '<select name="traveller_id" id="traveller_id" class="form-control">'
                                +'<option value="">All</option>';
        $.each(traveller_lists, function (i, elem) {
            asset_selectbox += '<option value="'+ elem['traveller_id'] +'">'+ elem['first_name'] +'</option>';
        });
        asset_selectbox += '</select>';
        $(this).html( asset_selectbox );
      }else if(i == 7){
        var invet_selectbox = '<select name="guider_id" id="guider_id" class="form-control">'
                                +'<option value="">All</option>';
        $.each(guider_lists, function (i, elem) {
            invet_selectbox += '<option value="'+ elem['guider_id'] +'">'+ elem['first_name'] +'</option>';
        });
        invet_selectbox += '</select>';
        $(this).html( invet_selectbox );
      }*/
    i++;
  });

  // DataTable
  var table = $('#completedtrip_list').DataTable();
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
</script>
<script type="text/javascript">
function exportExcelForm( guider_id, field ) {
  $( '#myModal' ).modal( 'show' );
  $( '#myModal .modal-body' ).html('<img src="<?=$dirUrl; ?>img/ajax-loader.gif" style="display: block; margin: 0 auto; width: 100px;" alt="loading..."/>');
  $('#myModal .modal-title').html( 'Export Booking' );
  var traveller_id  = $('#traveller_id').val();
  var guider_id     = $('#guider_id').val();
  var data = 'traveller_id='+traveller_id+'&guider_id='+guider_id;
  $.ajax( {
    type: "POST",
    data: data,
    url: adminurl + 'completedtrip/exportExcelForm',
    success: function( msg ) {
      $( '#myModal .modal-body' ).html(msg);
      $( '#myModal .modal-footer' ).html('');
    }
  });
  return false;
}
</script>