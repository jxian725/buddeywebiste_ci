<?php
defined('BASEPATH') OR exit('No direct script access allowed');  
$assetUrl   = $this->config->item( 'admin_url' );
$adminUrl   = $this->config->item( 'admin_dir_url' );
$dirUrl     = $this->config->item( 'dir_url' );

?>
<link rel="stylesheet" href="<?php echo $adminUrl; ?>plugins/bootstrap-datepicker/bootstrap-datepicker.min.css">
<script src="<?php echo $adminUrl; ?>plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="<?= $adminUrl; ?>plugins/select2/select2.min.css">
<script src="<?= $adminUrl; ?>plugins/select2/select2.full.min.js"></script>

<link href="<?= $assetUrl; ?>assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
<link href="<?= $assetUrl; ?>assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
<script src="<?= $assetUrl; ?>assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
<script src="<?= $assetUrl; ?>assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
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
.btn.disable, .btn[disable], fieldset[disable] .btn {
/* cursor: not-allowed; */
filter: alpha(opacity=65);
-webkit-box-shadow: none;
box-shadow: none;
opacity: .65;
}
.buttons-excel{
  background: #3F51B5 !important;
  border-color: #9da8e2 !important;
  color: #fff !important;
  border: 1px solid transparent !important;
  border-radius: 4px !important;
}
.dataTables_length{ float: right !important; }
</style>
<div class="row">
<div class="col-xs-12 col-sm-12">
  <div class="box box-primary">
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
                      <th class="head1 no-sort">Venues</th>
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


$(document).ready(function(){
  $('.select2').select2();
  //$("#monthDatePicker").datepicker({});
  $( '#customFilter').daterangepicker({});

  $(".applyBtn").click(function(){
    var start = $("input[name=daterangepicker_start]").val();
    var end   = $("input[name=daterangepicker_end]").val();
    if(start && end){
      window.location.href = baseurl + 'partner/venue/buskerspod?filter=custom&start='+start+'&end='+end;
    }
  });
  $(".filterBtn").click(function(){
    var value = $(this).val();
    if(value=='all'){ 
      window.location.href = baseurl + 'partner/venue/buskerspod'; 
    }else{
      window.location.href = baseurl + 'partner/venue/buskerspod?filter='+value;
    }
  });
});
$(document).ready(function() {
  table = $('#pod_list').DataTable({
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
          "url": "<?php echo site_url('/partner/venue/buskerspodTableResponse')?>",
          "type": "POST"
      },
      "dom": "B lrt<'row' <'col-sm-5' i><'col-sm-7' p>>",
      buttons: [
                {
                extend: 'excel',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7]
                }
            },
      ],
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
                                +'<option value="">Show All</option>';
        $.each(partnerList, function (i, elem) {
            part_selectbox += '<option value="'+ elem['partner_id'] +'">'+ decodeURI(elem['partner_name']) +'</option>';
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
function likePartner(id,status ) {
    $(document).ready(function() {
        var data = { 'id':id,'status':status }
        $.ajax({
          type: "POST",
           url: baseurl + 'partner/Venue/updatePartnerLike',
          data: data,
          success: function( data ) {
            toastr.success( 'Like.','Success' );
            var table = $('#pod_list').DataTable();
            table.draw('page');
          }
        });
    });    
    return false;
}
function unLikePartner(id,status ) {
    $(document).ready(function() {
        var data = { 'id':id,'status':status }
        $.ajax({
          type: "POST",
           url: baseurl + 'partner/Venue/updatePartnerUnlike',
          data: data,
          success: function( data ) {
            toastr.success( 'Dislike .','Success' );
            var table = $('#pod_list').DataTable();
            table.draw('page');
          }
        });
    });    
    return false;
}
function commandPartner(id,field) {
    $( '#myModal' ).modal( 'show' );
    $( '#myModal .modal-body' ).html('<img src="<?php echo $adminUrl;?>img/loading.gif">');
    var title = '';
    if(field == 0){
      title = 'Comment';
    }
    $( '#myModal .modal-title' ).html( title );
    var data = 'id='+id+'&field='+field;
      $.ajax({
        type: "POST",
        data: data,
        url: baseurl + 'partner/Venue/commandPartner',
        success: function( data ) {
          $( '#myModal .modal-body' ).html(data);
          $( '#myModal .modal-footer' ).html('');
        }
      });
    return false;
}
</script>