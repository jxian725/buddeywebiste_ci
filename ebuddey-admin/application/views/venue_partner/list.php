<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 
$assetUrl   = $this->config->item( 'base_url' );
global $permission_arr;
?>
<link rel="stylesheet" href="<?php echo $assetUrl; ?>plugins/bootstrap-datepicker/bootstrap-datepicker.min.css">
<script src="<?php echo $assetUrl; ?>plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<link href="<?= $assetUrl; ?>plugins/dataTables/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
<script src="<?= $assetUrl; ?>plugins/dataTables/jquery.dataTables.min.js"></script>
<script src="<?= $assetUrl; ?>plugins/dataTables/dataTables.bootstrap.min.js"></script>
<div class="row">
  <div class="col-xs-12 col-sm-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"></h3>
        <div class="pull-right box-tools">
          <!-- <a href="<?php echo base_url(); ?>venuepartner/add" class="btn btn-primary btn-sm pull-right" data-toggle="tooltip" title="" style="margin-right: 5px;" data-original-title="Add New">
            <i class="fa fa-plus"></i>
          </a> -->
          </div>
      </div>
      <div class="box-body">
          <div class="row">
            <div class="col-md-12 table-container">
                <div class="box-body no-padding">
                  <table id="partner_list" class="table table-striped table-bordered table-hover table-checkable dataTable data-tbl">
                    <thead>
                      <tr class="tbl_head_bg">
                        <th class="head1 no-sort">#</th>
                        <th class="head1 no-sort">Company Name</th>
                        <th class="head1 no-sort">City</th>
                        <th class="head1 no-sort">Email</th>
                        <th class="head1 no-sort">Mobile Number</th>
                        <th class="head1 no-sort">Business Address</th>
                        <th class="head1 no-sort">Post Code</th>
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
        <div class="clearfix"></div>
      </div><!-- panel body -->
    </div>
  </div><!-- content -->
</div>
<script type="text/javascript">
$(document).ready(function() {
  table = $('#partner_list').DataTable({
      language: {
        processing: "<img src='<?php echo base_url();?>img/loading.gif'>",
      },
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "order": [], //Initial no order.
      'autoWidth': false,
      "ajax": {
          "data": function(d) {
          },
          "url": "<?php echo site_url('/Venuepartner/partnerTableResponse')?>",
          "type": "POST"
      },
      "dom": "B lrt<'row' <'col-sm-5' i><'col-sm-7' p>>",
      "lengthMenu": [[10, 25, 50, 100, 1000, -1], [10, 25, 50, 100, 1000, "All"]],
      //Set column definition initialisation properties.
      "columnDefs": [{
          "targets": [0,8],
          "orderable": false, //set not orderable
      },
      {
          "targets": [0,8],
          "searchable": false, //set orderable
      } ],
      buttons: []
  });
  var i = 0;
  $('#partner_list tfoot th').each( function () {
    if( i == 1 || i == 2 || i == 3 || i == 4 ){
      $(this).html( '<input type="text" class="form-control" placeholder="" />' );
    } else if(i == 7){
      var statusBox = '<select name="partnerStatus" id="partnerStatus" class="form-control">'
                              +'<option value="">All</option>'
                              +'<option value="1">Active</option>'
                              +'<option value="0">Inactive</option>';
        statusBox += '</select>';
        $(this).html( statusBox );
    }
    i++;
  });

  // DataTable
  var table = $('#partner_list').DataTable();

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
function updatePartnerStatus( venuepartnerId,status ) { 
    $( '#myModal .modal-title' ).html( 'Confirm' );
    if(status == 1){
        $( '#myModal .modal-body' ).html( 'Are you sure want to Activate this partner ?' );
    }else{
        $( '#myModal .modal-body' ).html( 'Are you sure want to Deactive this partner ?' );
    }
    $( '#myModal .modal-body' ).append( '<br /><br /><button class="btn btn-info btn-sm" id="continuemodal" data-dismiss="modal">Yes</button>&nbsp;&nbsp;<button aria-hidden="true" data-dismiss="modal" class="btn btn-danger btn-sm">Cancel</button>' );
    $( '#myModal' ).modal({ backdrop: 'static', keyboard: false }); 
    $( "#continuemodal" ).click(function() {
        var data = { 'venuepartnerId':venuepartnerId,'status':status }
        $.ajax({
          type: "POST",
          url: adminurl + 'Venuepartner/updatePartnerStatus',
          data: data,
          success: function( data ) {
            toastr.success( 'Partner Status Updated Successfully.','Success' );
            setTimeout( function() {
              location.reload();
            }, 2000 );
          }
        });
    });    
    return false;
}
function deletePartner(venuepartnerId) { 
    $( '#myModal .modal-title' ).html( 'Confirm' );
       // delete staus
        $( '#myModal .modal-body' ).html( 'Are you sure want to Delete this partner ?' );
       // status
    $( '#myModal .modal-body' ).append( '<br /><br /><button class="btn btn-info btn-sm" id="continuemodal" data-dismiss="modal">Yes</button>&nbsp;&nbsp;<button aria-hidden="true" data-dismiss="modal" class="btn btn-danger btn-sm">Cancel</button>' );
    $( '#myModal' ).modal({ backdrop: 'static', keyboard: false }); 
    $( "#continuemodal" ).click(function() {
        var data = { 'venuepartnerId':venuepartnerId }
        $.ajax({
          type: "POST",
          url: adminurl + 'Venuepartner/deletePartner',
          data: data,
          success: function( data ) {
            toastr.success( 'Partner Details Deleted.','Success' );
            setTimeout( function() {
              location.reload();
            }, 2000 );
          }
        });
    });    
    return false;
}
function addPartner(venuepartnerId,field) {
    $( '#myModal' ).modal( 'show' );
    $( '#myModal .modal-body' ).html('<img src="<?php echo base_url();?>img/loading.gif">');
    var title = '';
    if(field == 0){
      title = 'Add Partner';
    }
    $( '#myModal .modal-title' ).html( title );
    var data = 'venuepartnerId='+venuepartnerId+'&field='+field;
      $.ajax({
        type: "POST",
        data: data,
        url: adminurl + 'Venuepartner/addPartner',
        success: function( data ) {
          $( '#myModal .modal-body' ).html(data);
          $( '#myModal .modal-footer' ).html('');
        }
      });
    return false;
}

</script>