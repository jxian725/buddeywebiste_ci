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
                        <th class="head1 no-sort"><?= HOST_NAME; ?> Name</th>
                        <th class="head1 no-sort">Transaction ID</th>
                        <th class="head1 no-sort"><?= GUEST_NAME; ?> Name</th>
                        <th class="head1 no-sort"><?= GUEST_NAME; ?> Gender</th>
                        <th class="head1 no-sort"><?= GUEST_NAME; ?> Email</th>
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
                echo $this->load->view( 'hostPortal/freebooking', $traveller_lists, true );
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
          "url": "<?php echo site_url('/hostPortal/completedtrip/completedtripTableResponse')?>",
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
  var i = 0;
  $('#completedtrip_list tfoot th').each( function () {
      var title = $(this).text();
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