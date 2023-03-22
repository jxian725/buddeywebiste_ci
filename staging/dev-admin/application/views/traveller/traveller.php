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
                        <table id="traveller_list" class="table table-bordered table-striped data-tbl">
                          <thead>
                            <tr class="tbl_head_bg">
                              <th class="head1 no-sort">#</th>
                              <th class="head1 no-sort">User name</th>
                              <th class="head1 no-sort">Email</th>
                              <th class="head1 no-sort">Phone no</th>
                              <th class="head1 no-sort">Languages Known</th>
                              <th class="head1 no-sort">About me</th>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
  table = $('#traveller_list').DataTable({
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
          "url": "<?php echo site_url('/traveller/travellerTableResponse')?>",
          "type": "POST"
      },
      "dom": "B lrt<'row' <'col-sm-5' i><'col-sm-7' p>>",
      "lengthMenu": [[10, 25, 50, 100, 1000], [10, 25, 50, 100, 1000]],
      //Set column definition initialisation properties.
      "columnDefs": [{
          "targets": [0,7],
          "orderable": false, //set not orderable
      },
      {
          "targets": [0,7],
          "searchable": false, //set orderable
      } ],
  });
  var i = 0;
  $('#traveller_list tfoot th').each( function () {
    if(i != 0 && i != 6 && i != 7){
      $(this).html( '<input type="text" class="form-control" placeholder="" />' );
    } else if(i == 6){
      var statusbox = '<select name="status" id="status" class="form-control">'
                              +'<option value="">All</option>'
                              +'<option value="1">Active</option>'
                              +'<option value="2">Inactive</option>'
      statusbox += '</select>';
      $(this).html( statusbox );
    }
    i++;
  });

  // DataTable
  var table = $('#traveller_list').DataTable();

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
  //Update Password Modal
  function passwordConfirm( traveller_id ) {
      var data = 'traveller_id=' + traveller_id;
      $.ajax( {
          type: "POST",
          data: data,
          url: adminurl + 'traveller/passwordConfirm',
          success: function( msg ) {
            $( '#myModal .modal-title' ).html( 'Password Confirmation' );
            $( '#myModal .modal-body' ).html( msg );
            $( '#myModal .modal-footer' ).html( '' );
            $( '#myModal' ).modal( 'show' );
          }
      });
      return false;
  }
  function update_password_info( traveller_id ) {
    var data = $( '#update_traveller_form' ).serialize();
    //alert( data );
      $.ajax( {
          type    : "POST",
          data    : data,
          url     : adminurl + 'traveller/update_password_info',
          dataType: 'json',
          success: function( msg ) {
              if( msg.Jerror == 2 ) {
                 toastr.success( 'Password confirmation successfully.','Success' );
                 deleteTraveller( traveller_id, 4 ); 
              } else if( msg.Jerror == 3 ) { 
                  toastr.error( msg.Jmsg,'Error' );     
              } else {
                  toastr.error( msg.Jmsg, 'Error' );
              }
          }
      });
      return false;
  }
function deleteTraveller( traveller_id,status ) {
    $( '#myModal .modal-title' ).html( 'Confirm' );
    $( '#myModal .modal-body' ).html( 'Are you sure want to Delete this guest?' );
    $( '#myModal .modal-body' ).append( '<br /><br /><button class="btn btn-info btn-sm" id="continuemodal" data-dismiss="modal">Yes</button>&nbsp;&nbsp;<button aria-hidden="true" data-dismiss="modal" class="btn btn-danger btn-sm">Cancel</button>' );
    $( '#myModal' ).modal({ backdrop: 'static', keyboard: false }); 
    $( "#continuemodal" ).click(function() {
    var data = { 'traveller_id':traveller_id,'status':status }
        $.ajax({
          type: "POST",
          url: adminurl + 'traveller/deleteTraveller',
          data: data,
          success: function( data ) {
            toastr.success( 'Guest Delete Successfully.','Success' );
            setTimeout( function() {
              location.reload();
            }, 2000 );
          }
        });
    });    
    return false;
  }
  //Status Update
  function travellerStatus( traveller_id, status ) {
      $( '#myModal .modal-title' ).html( 'Confirm' );
      if(status == 1){
          $( '#myModal .modal-body' ).html( 'Are you sure want to Activate this guest ?' );
      }else{
          $( '#myModal .modal-body' ).html( 'Are you sure want to Deactive this guest ?' );
      }
      $( '#myModal .modal-body' ).append( '<br /><br /><button class="btn btn-info btn-sm" id="continuemodal" data-dismiss="modal">Yes</button>&nbsp;&nbsp;<button aria-hidden="true" data-dismiss="modal" class="btn btn-danger btn-sm">Cancel</button>' );
      $( '#myModal' ).modal({ backdrop: 'static', keyboard: false }); 
      $( "#continuemodal" ).click(function() {
        var data = { 'traveller_id':traveller_id,'status':status }
        $.ajax({
          type: "POST",
          url: adminurl + 'traveller/travellerStatus',
          data: data,
          success: function( data ) {
            toastr.success( 'Guest Status Updated Successfully.','Success' );
            setTimeout( function() {
              location.reload();
            }, 2000 );
          }
        });
    });    
    return false;
  }
</script>