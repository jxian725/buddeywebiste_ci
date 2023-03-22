<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 
$adminUrl   = $this->config->item( 'admin_dir_url' );
$site_name  = $this->config->item( 'site_name' );
$dirUrl     = $this->config->item( 'dir_url' );
?>
<link href="<?= $adminUrl; ?>plugins/dataTables/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
<script src="<?= $adminUrl; ?>plugins/dataTables/jquery.dataTables.min.js"></script>
<script src="<?= $adminUrl; ?>plugins/dataTables/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="<?php echo $adminUrl; ?>plugins/bootstrap-datepicker/bootstrap-datepicker.min.css">
<script type="text/javascript" src="<?php echo $adminUrl; ?>plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<div class="row">
  <div class="col-xs-12 col-sm-12">
    <div class="box box-primary">
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
              <div class="box-body table-responsive no-padding">
                <table id="donation_list" class="table table-bordered data-tbl">
                  <thead>
                    <tr class="tbl_head_bg">
                      <th class="head1 no-sort">#</th>
                      <th class="head1 no-sort">Gift date</th>
                      <th class="head1 no-sort">Donors name</th>
                      <th class="head1 no-sort">Transaction ID</th>
                      <th class="head1 no-sort">Amount (RM)</th>
                      <th class="head1 no-sort">Transaction fee (RM)</th>
                      <th class="head1 no-sort">Total Gifts (RM)</th>
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
                    </tr>
                  </tfoot>
                </table>
              </div>
            <div class="clearfix"></div>
          </div>

        </div>
      </div>
    </div>
  </div>
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
        processing: "<img src='<?php echo $adminUrl;?>img/loading.gif'>",
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
          "url": "<?php echo site_url('/talent/managebuskerspod/donationTableResponse')?>",
          "type": "POST"
      },
      "dom": "B lrt<'row' <'col-sm-5' i><'col-sm-7' p>>",
      "lengthMenu": [[10, 25, 50, 100, 1000], [10, 25, 50, 100, 1000]],
      //Set column definition initialisation properties.
      "columnDefs": [{
          "targets": [0,8],
          "orderable": false, //set not orderable
      },
      {
          "targets": [0,8],
          "searchable": false, //set orderable
      } ],
  });
  var i = 0;
  $('#donation_list tfoot th').each( function () {
      if(i != 0 && i != 8){
        $(this).html( '<input type="text" class="form-control" placeholder="" />' );
      }
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
function viewMessage(payment_id) {
    $( '#myModal' ).modal( 'show' );
    $( '#my_modal_footer' ).hide();
    $( '#myModal #mymodalBody' ).html('<img src="<?php echo $adminUrl;?>img/loading.gif">');
    $( '#myModal #mymodalTitle' ).html( 'View Message' );
    var data = 'payment_id='+payment_id;
    $.ajax({
      type: "POST",
      data: data,
      url: baseurl + 'talent/managebuskerspod/viewMessage',
      success: function( data ) {
        $( '#myModal #mymodalBody' ).html(data);
      }
    });
    return false;
}
</script>
