<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl   = $this->config->item( 'admin_url' );
$dirUrl     = $this->config->item( 'dir_url' );

?>
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
      </tr>
    </tfoot>
  </table>
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
          "url": "<?php echo site_url('/completedtrip/completedWebRequestTableResponse')?>",
          "type": "POST"
      },
      "dom": "B lrt<'row' <'col-sm-5' i><'col-sm-7' p>>",
      "lengthMenu": [[10, 25, 50, 100, 1000], [10, 25, 50, 100, 1000]],
      //Set column definition initialisation properties.
      "columnDefs": [{
          "targets": [0,14],
          "orderable": false, //set not orderable
      },
      {
          "targets": [0,14],
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
</script>