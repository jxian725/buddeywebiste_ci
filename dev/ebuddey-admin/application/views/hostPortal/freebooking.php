<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl   = $this->config->item( 'admin_url' );
$dirUrl     = $this->config->item( 'dir_url' );

?>
<div class="box-body table-responsive no-padding">
  <table id="freeCompletedTripList" class="table table-bordered  data-tbl">
    <thead>
      <tr class="tbl_head_bg">
        <th class="head1 no-sort">#</th>
        <th class="head1 no-sort">Trip Id</th>
        <th class="head1 no-sort">Booking created datetime</th>
        <th class="head1 no-sort"><?= GUEST_NAME; ?> Name</th>
        <th class="head1 no-sort">Meeting Date</th>
        <th class="head1 no-sort">Meeting Time</th>
        <th class="head1 no-sort">No.of people</th>
        <th class="head1 no-sort"><?= HOST_NAME; ?> Name</th>
        <th class="head1 no-sort"><?= HOST_NAME; ?> Supporting location</th>
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
      </tr>
    </tfoot>
  </table>
</div>
<script type="text/javascript">
$(document).ready(function() {
  table = $('#freeCompletedTripList').DataTable({
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
          "url": "<?php echo site_url('/hostPortal/completedtrip/completedFreetripTableResponse')?>",
          "type": "POST"
      },
      "dom": "lrt<'row' <'col-sm-5' i><'col-sm-7' p>>",
      "lengthMenu": [[10, 25, 50, 100, 1000], [10, 25, 50, 100, 1000]],
      //Set column definition initialisation properties.
      "columnDefs": [{
          "targets": [0,10],
          "orderable": false, //set not orderable
      },
      {
          "targets": [0,10],
          "searchable": false, //set orderable
      } ],
  });
  var guider_lists      = <?php echo json_encode($guider_lists) ?>;
  var traveller_lists   = <?php echo json_encode($traveller_lists) ?>;
  var i = 0;
  $('#freeCompletedTripList tfoot th').each( function () {
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
      }else if(i == 7){
        var invet_selectbox = '<select name="guider_id" id="guider_id" class="form-control">'
                                +'<option value="">All</option>';
        $.each(guider_lists, function (i, elem) {
            invet_selectbox += '<option value="'+ elem['guider_id'] +'">'+ elem['first_name'] +'</option>';
        });
        invet_selectbox += '</select>';
        $(this).html( invet_selectbox );
      }else if(i == 9){
        var invet_selectbox = '<select name="guider_id" id="guider_id" class="form-control">'
                                +'<option value="">All</option>'
                                +'<option value="1">Upcoming</option>'
                                +'<option value="2">Ongoing</option>'
                                +'<option value="3">Completed</option>'
        invet_selectbox += '</select>';
        $(this).html( invet_selectbox );
      }
    i++;
  });

  // DataTable
  var table = $('#freeCompletedTripList').DataTable();

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