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
                      <th class="head1 no-sort">Order Id</th>
                      <th class="head1 no-sort"><?= HOST_NAME; ?> Name</th>
                      <th class="head1 no-sort">senangpay TaransactionID</th>
                      <th class="head1 no-sort">senangpay Paid Amount(<?= CURRENCYCODE; ?>)</th>
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
          "url": "<?php echo site_url('/senangpay_transaction/senangpay_transTableResponse')?>",
          "type": "POST"
      },
      "dom": "B lrt<'row' <'col-sm-5' i><'col-sm-7' p>>",
      "lengthMenu": [[10, 25, 50, 100, 1000], [10, 25, 50, 100, 1000]],
      //Set column definition initialisation properties.
      "columnDefs": [{
          "targets": [0,6,7],
          "orderable": false, //set not orderable
      },
      {
          "targets": [0,7],
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
        var invet_selectbox = '<select name="guider_id" id="guider_id" class="form-control">'
                                +'<option value="">All</option>';
        $.each(guider_lists, function (i, elem) {
            invet_selectbox += '<option value="'+ elem['guider_id'] +'">'+ elem['first_name'] +'</option>';
        });
        invet_selectbox += '</select>';
        $(this).html( invet_selectbox );
      }else if(i == 6){
        var invet_selectbox = '<select name="status" id="status" class="form-control">'
                                +'<option value="">All</option>'
                                +'<option value="0">Failure</option>'
                                +'<option value="1">Success</option>'
                                +'<option value="2">Initiated</option>'
                                +'<option value="3">Cancelled</option>'
                                +'<option value="4">Pending</option>'
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
</script>