<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl   = $this->config->item( 'admin_url' );
$dirUrl     = $this->config->item( 'dir_url' );
global $permission_arr;
?>
<link href="<?= $dirUrl; ?>plugins/dataTables/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
<script src="<?= $dirUrl; ?>plugins/dataTables/jquery.dataTables.min.js"></script>
<script src="<?= $dirUrl; ?>plugins/dataTables/dataTables.bootstrap.min.js"></script>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                      <div class="box-body table-responsive no-padding">
                        <table id="experience_list" class="table table-bordered table-striped data-tbl">
                          <thead>
                            <tr class="tbl_head_bg">
                              <th class="head1 no-sort">#</th>
                              <th class="head1 no-sort">Talent name</th>
                              <th class="head1 no-sort">Title</th>
                              <th class="head1 no-sort">City</th>
                              <th class="head1 no-sort">Price</th>
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
                            </tr>
                          </tfoot>
                        </table>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
  table = $('#experience_list').DataTable({
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
          "url": "<?php echo site_url('/experience/experienceTableResponse')?>",
          "type": "POST"
      },
      "dom": "B lrt<'row' <'col-sm-5' i><'col-sm-7' p>>",
      "lengthMenu": [[10, 25, 50, 100, 1000], [10, 25, 50, 100, 1000]],
      //Set column definition initialisation properties.
      "columnDefs": [{
          "targets": [0,6],
          "orderable": false, //set not orderable
      },
      {
          "targets": [0,6],
          "searchable": false, //set orderable
      } ],
  });
  var guider_lists = <?php echo json_encode($guider_lists) ?>;
  var i = 0;
  $('#experience_list tfoot th').each( function () {
    if(i != 0 && i != 1 && i != 5 && i != 6){
      $(this).html( '<input type="text" class="form-control" placeholder="" />' );
    }else if(i == 1){
        var invet_selectbox = '<select name="guider_id" id="guider_id" class="form-control">'
                                +'<option value="">All</option>';
        $.each(guider_lists, function (i, elem) {
            invet_selectbox += '<option value="'+ elem['guider_id'] +'">'+ elem['first_name'] +'</option>';
        });
        invet_selectbox += '</select>';
        $(this).html( invet_selectbox );
    } else if(i == 5){
      var statusbox = '<select name="status" id="status" class="form-control">'
                              +'<option value="">All</option>'
                              +'<option value="1">Approve</option>'
                              +'<option value="2">Under Review</option>'
                              +'<option value="3">Reject</option>'
      statusbox += '</select>';
      $(this).html( statusbox );
    }
    i++;
  });

  // DataTable
  var table = $('#experience_list').DataTable();

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
//Status Update
function experienceStatus( te_id, status ) {
  var table = $('#experience_list').DataTable();

  $( '#myModal .modal-title' ).html( 'Confirm' );
  if(status == 1){
    $( '#myModal .modal-body' ).html( 'Are you sure want to Approve this experience ?' );
  }else{
    $( '#myModal .modal-body' ).html( 'Are you sure want to reject this experience  ?' );
  }
  $( '#myModal .modal-body' ).append( '<br /><br /><button class="btn btn-info btn-sm" id="continuemodal" data-dismiss="modal">Yes</button>&nbsp;&nbsp;<button aria-hidden="true" data-dismiss="modal" class="btn btn-danger btn-sm">Cancel</button>' );
  $( '#myModal' ).modal({ backdrop: 'static', keyboard: false }); 
  $( "#continuemodal" ).click(function() {
    var data = { 'te_id':te_id,'status':status }
    $.ajax({
      type: "POST",
      url: adminurl + 'experience/experienceStatus',
      data: data,
      success: function( data ) {
        table.ajax.reload();
        toastr.success( 'Talent experience status updated Successfully.','Success' );
      }
    });
  });    
  return false;
}
</script>