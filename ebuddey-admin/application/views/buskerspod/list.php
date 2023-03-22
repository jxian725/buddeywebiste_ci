<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl   = $this->config->item( 'admin_url' );
$dirUrl     = $this->config->item( 'dir_url' );

?>
<link rel="stylesheet" href="<?php echo $dirUrl; ?>plugins/bootstrap-datepicker/bootstrap-datepicker.min.css">
<script src="<?php echo $dirUrl; ?>plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="<?= $dirUrl; ?>plugins/select2/select2.min.css">
<script src="<?= $dirUrl; ?>plugins/select2/select2.full.min.js"></script>
<link href="<?= $dirUrl; ?>plugins/dataTables/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
<script src="<?= $dirUrl; ?>plugins/dataTables/jquery.dataTables.min.js"></script>
<script src="<?= $dirUrl; ?>plugins/dataTables/dataTables.bootstrap.min.js"></script>
<style type="text/css">
.data-tbl .head1{ width: 1px; }
.data-tbl .head2{ width: auto; }
.data-tbl .head2 input, .data-tbl .head2 select{ width: 100%; }
.table-striped>tbody>tr:nth-of-type(odd) {
  background-color: #333f4f;
  color: #fff;
}
.table-striped>tbody>tr:nth-of-type(even) {
  background-color: #222a35;
  color: #fff;
}
table thead tr.tbl_head_bg{ background: #222a35;color: #fff; }
table tfoot tr.tbl_head_bg{ background: #607d8b;color: #fff; }
.select2-container .select2-selection--single {
  height: 34px;
}
</style>
<div class="row">
<div class="col-xs-12 col-sm-12">
  <div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title">
        Buskers Pod Tracker
      </h3>
      <div class="pull-right box-tools">
        <a href="javascript:;" onclick="return addEventFormModal();" class="btn btn-primary btn-xs pull-right" data-toggle="tooltip" title="" style="margin-right: 5px;" data-original-title="Add New">
          <i class="fa fa-plus"></i>
        </a>
      </div>
    </div>
    <div class="box-body">
        <div class="box">
            <div class="box-body">
              <p>
                <div class="col-xs-6 col-sm-6" style="text-align: -webkit-right;">
                  <div style="padding: 5px;font-size: 20px;font-weight: bolder;text-transform: uppercase;">
                    <?php
                    $allActiveClass    = 'btn-default';
                    $monthActiveClass  = 'btn-default';
                    $weekActiveClass   = 'btn-default';
                    $customActiveClass = 'btn-default';
                    if($filter=='month'){
                      echo date('F Y');
                      $monthActiveClass = 'btn-primary';
                    }elseif ($filter=='week') {
                      echo date("d F Y", strtotime($start)).' - '.date("d F Y", strtotime($end));
                      $weekActiveClass = 'btn-primary';
                    }elseif ($filter=='custom') {
                      echo date("d F Y", strtotime($start)).' - '.date("d F Y", strtotime($end));
                      $customActiveClass = 'btn-primary';
                    }else{
                      echo 'All Lists';
                      $allActiveClass = 'btn-primary';
                    }
                    ?>
                  </div>
                </div>
                <div class="col-xs-6 col-sm-6" style="text-align: -webkit-right;">
                  <button type="button" id="allFilter" value="all" class="btn <?=$allActiveClass;?> filterBtn">All</button>
                  <button type="button" id="monthDatePicker" value="month" class="btn <?=$monthActiveClass;?> filterBtn">Month</button>
                  <button type="button" id="weekFilter" value="week" class="btn <?=$weekActiveClass;?> filterBtn">Week</button>
                  <button type="button" id="customFilter" class="btn <?=$customActiveClass;?>">Custom</button>
                  <input type="hidden" name="start" id="start" value="<?=$start; ?>">
                  <input type="hidden" name="end" id="end" value="<?=$end; ?>">
                  <input type="hidden" name="filter" id="filter" value="<?=$filter; ?>">
                </div>
              </p>
            </div>
          </div>
        <div class="row">
          <div class="clearfix margin_b10"></div>
          <div class="col-md-12">
              <div class="box-body table-responsive no-padding">
                <table id="pod_list" class="table table-striped data-tbl">
                  <thead>
                    <tr class="tbl_head_bg">
                      <th class="head1 no-sort">#</th>
                      <th class="head1 no-sort">Partner Name</th>
                      <!-- <th class="head1 no-sort">Additional Info</th> -->
                      <th class="head1 no-sort">City</th>
                      <th class="head1 no-sort">Date</th>
                      <th class="head1 no-sort">Start</th>
                      <th class="head1 no-sort">End</th>
                      <th class="head1 no-sort">Price</th>
                      <th class="head1 no-sort">Status</th>
                      <th class="head1 no-sort">Action</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr class="tbl_head_bg">
                      <th class="head2 no-sort"></th>
                      <th class="head2 no-sort"></th>
                      <!-- <th class="head2 no-sort"></th> -->
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

function addEventFormModal() {
    $.ajax( {
        type: "POST",
        url: adminurl + 'buskerspod/addEventFormModal',
        data    : '',
        success: function( msg ) {
          $( '#myModal .modal-title' ).html( 'Add Calendar Event' );
          $( '#myModal .modal-body' ).html( msg );
          $( '#myModal .modal-footer' ).html( '' );
          $( '#myModal' ).modal( 'show' );
        }
    });
    return false;
}
function editEventFormModal(event_id) {
    $.ajax( {
        type: "POST",
        url: adminurl + 'buskerspod/editEventFormModal',
        data    : 'event_id=' + event_id,
        success: function( msg ) {
          $( '#myModal .modal-title' ).html( 'Edit Calendar Event' );
          $( '#myModal .modal-body' ).html( msg );
          $( '#myModal .modal-footer' ).html( '' );
          $( '#myModal' ).modal( 'show' );
        }
    });
    return false;
}
function addHostForm(event_id) {
    $.ajax( {
        type: "POST",
        url: adminurl + 'buskerspod/addHostForm',
        data    : 'event_id=' + event_id,
        success: function( msg ) {
          $( '#myModal .modal-title' ).html( 'Update <?= HOST_NAME; ?>' );
          $( '#myModal .modal-body' ).html( msg );
          $( '#myModal .modal-footer' ).html( '' );
          $( '#myModal' ).modal( 'show' );
        }
    });
    return false;
}
function addHost(event_id) {
    var isvalid  = 1;
    var host_id  = $( '#addhostform #host_id' ).val();
    if(host_id == ''){
        toastr.error( 'Please select the Host.','Error' );
        isvalid = 0;
    }
    if(isvalid==1){
        $.ajax( {
            type    : "POST",
            data    : 'event_id=' + event_id+'&host_id=' + host_id,
            url     : adminurl + 'buskerspod/addHost',
            beforeSend: function() { 
                $("#addhosttbtn").html('Loading...');
                $("#addhosttbtn").prop('disabled', true);
                $('#addhostform').css("opacity",".5");
            },
            success: function( msg ) {
                if(msg == 1){
                    $("#addhosttbtn").html('Update Host');
                    $("#addhosttbtn").prop('disabled', false);
                    $("form#addhostform").trigger("reset");
                    toastr.success( 'Host added successfully.','Success' );
                    var table = $('#pod_list').DataTable();
                    table.draw('page');
                    document.getElementById("defaultOpen").click();
                }else{
                    toastr.error( msg,'Error' );
                    $("#addhosttbtn").html('Update Host');
                    $("#addhosttbtn").prop('disabled', false);
                }
            }
        });
    }
    return false;
}
$(document).ready(function(){
  $('.select2').select2();
  //$("#monthDatePicker").datepicker({});
  $( '#customFilter').daterangepicker({});

  $(".applyBtn").click(function(){
    var start = $("input[name=daterangepicker_start]").val();
    var end   = $("input[name=daterangepicker_end]").val();
    if(start && end){
      window.location.href = adminurl + 'buskerspod?filter=custom&start='+start+'&end='+end;
    }
  });
  $(".filterBtn").click(function(){
    var value = $(this).val();
    if(value=='all'){ 
      window.location.href = adminurl + 'buskerspod'; 
    }else{
      window.location.href = adminurl + 'buskerspod?filter='+value;
    }
  });
});
$(document).ready(function() {
  table = $('#pod_list').DataTable({
      language: {
        processing: "<img src='<?php echo $dirUrl;?>img/loading.gif'>",
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
          "url": "<?php echo site_url('/buskerspod/buskerspodTableResponse')?>",
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
  var partnerList   = <?php echo json_encode($partnerList) ?>;
  var stateList     = <?php echo json_encode($stateList) ?>;
  var skillsList    = <?php echo json_encode($skillsList) ?>;
  var i = 0;
  $('#pod_list tfoot th').each( function () {
      var title = $(this).text();
      
      if(i == 1){
        var part_selectbox = '<select name="partner_name" id="partner_name" style="width: 100%;" data-placeholder="Show All" class="form-control select2">'
                                +'<option value=" ">Show All</option>';
        $.each(partnerList, function (i, elem) {
            part_selectbox += '<option value="'+ elem['partner_id'] +'">'+ decodeURIComponent(elem['partner_name']) +'</option>';
        });
        part_selectbox += '</select>';
        $(this).html( part_selectbox );
        $('.select2').select2();
      }else if(i == 2){
        var city_selectbox = '<select name="city_id" id="city_id" class="form-control">'
                                +'<option value="">Show All</option>';
        $.each(stateList, function (i, elem) {
            city_selectbox += '<option value="'+ elem['name'] +'">'+ elem['name'] +'</option>';
        });
        city_selectbox += '</select>';
        $(this).html( city_selectbox );
      }else if(i == 7){
        var status_sel = '<select name="status" id="status" class="form-control">'
                                +'<option value="">Show All</option>'
                                +'<option value="1">Available</option>'
                                +'<option value="2">Progress</option>'
                                +'<option value="4">Locked</option>'
                                +'<option value="3">Booked</option>'
        status_sel += '</select>';
        $(this).html( status_sel );
      }
    i++;
  });

  // DataTable
  var table = $('#pod_list').DataTable();

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

function deleteBuskerspod( id ) {
  $( '#myModal .modal-title' ).html( 'Confirm' );
  $( '#myModal .modal-body' ).html( 'Are you sure want to delete the buskerspod ?' );
  $( '#myModal .modal-body' ).append( '<br /><br /><button class="btn btn-info btn-sm" id="continuemodal" data-dismiss="modal">Yes</button>&nbsp;&nbsp;<button aria-hidden="true" data-dismiss="modal" class="btn btn-danger btn-sm">Cancel</button>' );
  $( '#myModal' ).modal({ backdrop: 'static', keyboard: false }); 
  $( "#continuemodal" ).click(function() {
      var data = 'id=' + id;
      $.ajax({
        type: "POST",
        url: adminurl + 'buskerspod/deleteBuskerspod',
        data: data,
        success: function( data ) {
          toastr.success( 'Buskers pod deleted successfully.','Success' );
          var table2 = $('#pod_list').DataTable();
          table2.draw('page');
          //setTimeout( function() { window.location.href = adminurl + 'buskerspod'; }, 1000 );
        }
      });
  });    
  return false;
}
function updateBuskerspodStatus( id,status ) { 
    $( '#myModal .modal-title' ).html( 'Confirm' );
    if(status == 1){
        $( '#myModal .modal-body' ).html( 'Are you sure want to Activate this Buskers pod ?' );
    }else{
        $( '#myModal .modal-body' ).html( 'Are you sure want to Disabled this Buskers pod ?' );
    }
    $( '#myModal .modal-body' ).append( '<br /><br /><button class="btn btn-info btn-sm" id="continuemodal" data-dismiss="modal">Yes</button>&nbsp;&nbsp;<button aria-hidden="true" data-dismiss="modal" class="btn btn-danger btn-sm">Cancel</button>' );
    $( '#myModal' ).modal({ backdrop: 'static', keyboard: false }); 
    $( "#continuemodal" ).click(function() {
        var data = { 'id':id,'status':status }
        $.ajax({
          type: "POST",
          url: adminurl + 'buskerspod/updateBuskerspodStatus',
          data: data,
          success: function( data ) {
            toastr.success( 'Buskers pod Status Updated Successfully.','Success' );
            var table2 = $('#pod_list').DataTable();
            table2.draw('page');
          }
        });
    });    
    return false;
}
function revokeEvent( id ) {
  $( '#myModal .modal-title' ).html( 'Confirm' );
  $( '#myModal .modal-body' ).html( 'Are you sure want to revoke the buskerspod ?' );
  $( '#myModal .modal-body' ).append( '<br /><br /><button class="btn btn-info btn-sm" id="continuemodal" data-dismiss="modal">Yes</button>&nbsp;&nbsp;<button aria-hidden="true" data-dismiss="modal" class="btn btn-danger btn-sm">Cancel</button>' );
  $( '#myModal' ).modal({ backdrop: 'static', keyboard: false }); 
  $( "#continuemodal" ).click(function() {
      var data = 'id=' + id;
      $.ajax({
        type: "POST",
        url: adminurl + 'buskerspod/revokeEvent',
        data: data,
        success: function( data ) {
          toastr.success( 'Buskers pod revoked successfully.','Success' );
          var table2 = $('#pod_list').DataTable();
          table2.draw('page');
          //setTimeout( function() { window.location.href = adminurl + 'buskerspod'; }, 1000 );
        }
      });
  });    
  return false;
}
</script>