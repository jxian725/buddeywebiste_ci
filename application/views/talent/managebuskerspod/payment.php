<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 
$adminUrl   = $this->config->item( 'admin_dir_url' );
$site_name  = $this->config->item( 'site_name' );
$dirUrl     = $this->config->item( 'dir_url' );
?>
<link href="<?= $adminUrl; ?>plugins/dataTables/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
<script src="<?= $adminUrl; ?>plugins/dataTables/jquery.dataTables.min.js"></script>
<script src="<?= $adminUrl; ?>plugins/dataTables/dataTables.bootstrap.min.js"></script>
<div class="row">
  <div class="col-xs-12 col-sm-12">
    <div class="box box-primary">
      <div class="box-body">
        <div class="row">
          <div class="clearfix margin_b10"></div>
          <div class="col-md-8">
              <div class="box-body table-responsive no-padding">
                <table id="payment_list" class="table table-bordered data-tbl">
                  <thead>
                    <tr class="tbl_head_bg">
                      <th class="head1 no-sort">#</th>
                      <th class="head1 no-sort">Payment date</th>
                      <th class="head1 no-sort">Amount</th>
                      <th class="head1 no-sort">Transaction ID</th>
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
                    </tr>
                  </tfoot>
                </table>
              </div>
            <div class="clearfix"></div>
          </div>
          <div class="col-md-4">
            <div class="box-body table-responsive no-padding" id="order_summary">
                    
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
  table = $('#payment_list').DataTable({
      language: {
        processing: "<img src='<?php echo $adminUrl;?>img/loading.gif'>",
      },
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "order": [], //Initial no order.
      'autoWidth': false,
      "ajax": {
          "data": function(d) {
            d.startDate  = $('#start').val();
            d.endDate    = $('#end').val();
            d.filterDate = $('#filter').val();
          },
          "url": "<?php echo site_url('/talent/managebuskerspod/paymentTableResponse')?>",
          "type": "POST"
      },
      "dom": "B lrt<'row' <'col-sm-5' i><'col-sm-7' p>>",
      "lengthMenu": [[10, 25, 50, 100, 1000], [10, 25, 50, 100, 1000]],
      //Set column definition initialisation properties.
      "columnDefs": [{
          "targets": [0,4],
          "orderable": false, //set not orderable
      },
      {
          "targets": [0,4],
          "searchable": false, //set orderable
      } ],
  });
  var i = 0;
  $('#payment_list tfoot th').each( function () {
      if(i != 0 && i != 4){
        $(this).html( '<input type="text" class="form-control" placeholder="" />' );
      }
    i++;
  });

  // DataTable
  var table = $('#payment_list').DataTable(); 

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

function viewBookingSummary( transactionID ) {
    var data = 'transactionID='+transactionID;
    $.ajax( {
        type: "POST",
        data: data,
        url: baseurl + 'talent/managebuskerspod/viewBookingSummary',
        success: function( msg ) {
            $( '#order_summary' ).html(msg);
        }
    });
    return false;
}
</script>
