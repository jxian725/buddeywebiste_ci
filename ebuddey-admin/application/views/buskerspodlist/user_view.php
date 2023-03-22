

<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl   = $this->config->item( 'admin_url' );
$dirUrl     = $this->config->item( 'dir_url' );

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
<link rel="stylesheet" href="<?php echo $dirUrl; ?>plugins/bootstrap-datepicker/bootstrap-datepicker.min.css">
<script src="<?php echo $dirUrl; ?>plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="<?= $dirUrl; ?>plugins/select2/select2.min.css">
<script src="<?= $dirUrl; ?>plugins/select2/select2.full.min.js"></script>
	<style type="text/css">
	a {
		padding-left: 5px;
		padding-right: 5px;
		margin-left: 5px;
		margin-right: 5px;
	}
	div#pagination a{
		border:1px solid #3333;
		padding:6px 12px;
	}
	strong{
		border:1px solid #3333;
		padding:6px 12px;
	color: #fff;
	cursor: default;
	background-color: #337ab7;
	border-color: #337ab7;
	}
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
</head>
<body>
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
				<div class="col-xs-3 col-sm-3" style="text-align: -webkit-right;" id="select">
				</div>
                <div class="col-xs-3 col-sm-3" style="text-align: -webkit-right;">

                  <button type="button" id="allFilter" value="all" class="btn <?=$allActiveClass;?> filterBtn">All</button>
                  <button type="button" id="monthDatePicker" value="month" class="btn <?=$monthActiveClass;?> filterBtn">Month</button>
                  <button type="button" id="weekFilter" value="week" class="btn <?=$weekActiveClass;?> filterBtn">Week</button>
                  <button type="button" id="customFilter" class="btn <?=$customActiveClass;?>">Custom</button>
                  <input type="hidden" name="start" id="start" value="<?=$start; ?>">
                  <input type="hidden" name="end" id="end" value="<?=$end; ?>">
                  <input type="hidden" name="filter" id="filter" value="<?=$filter; ?>">
				  <input type="hidden" name="partnerid" id="partnerid" value="<?=$partnerid; ?>">
                </div>
              </p>
            </div>
          </div>
	<!-- Posts List -->
	<table  id='postsList' class="table table-striped data-tbl dataTable">
		<thead>
		<tr class="tbl_head_bg">
		  <th class="head1 no-sort">#</th>
		  <th class="head1 no-sort" nowrap>Partner Name</th>
		  <!-- <th class="head1 no-sort">Additional Info</th> -->
		  <th class="head1 no-sort" nowrap>City</th>
		  <th class="head1 no-sort" nowrap>Date</th>
		  <th class="head1 no-sort" nowrap>Start</th>
		  <th class="head1 no-sort" nowrap>End</th>
		  <th class="head1 no-sort" nowrap>Price</th>
		  <th class="head1 no-sort" nowrap>Status</th>
		  <th class="head1 no-sort" nowrap>Action</th>
		</tr>
		</thead>
		<tbody></tbody>
	</table>

	<!-- Paginate -->
	<div style='margin-top: 10px;text-align:end; !important' id='pagination' ></div>

	</div>	</div>
	</div>
	</div>

	<script type='text/javascript'>
		$(document).ready(function(){

			var partnerList   = <?php echo json_encode($partnerList) ?>;
						 var part_selectbox = '<select name="partner_name" id="partner_name" style="width: 100%;" data-placeholder="Show All" class="form-control select2" onchange="getpartnerfilter(this.value)">'
                                +'<option value=" ">Show All</option>';
						$.each(partnerList, function (i, elem) {
							part_selectbox += '<option value="'+ elem['partner_id'] +'">'+ decodeURIComponent(elem['partner_name']) +'</option>';
						});
						part_selectbox += '</select>';
						$("#select").html( part_selectbox );
						$('.select2').select2();
			var name="<?=$partnerid; ?>";
			$("#partner_name").val(name);
			// Detect pagination click
			$('#pagination').on('click','a',function(e){
				e.preventDefault(); 
				var pageno = $(this).attr('data-ci-pagination-page');
				loadPagination(pageno);
			});

			loadPagination(0);

			
			
		});
	</script>
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
					$( '#myModal' ).modal( 'hide' );
                    loadPagination(0);
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
      window.location.href = adminurl + 'buskerspodlist?filter=custom&start='+start+'&end='+end;
    }
  });
  $(".filterBtn").click(function(){
    var value = $(this).val();
    if(value=='all'){ 
      window.location.href = adminurl + 'buskerspodlist'; 
    }else{
      window.location.href = adminurl + 'buskerspodlist?filter='+value;
    }
  });
  
  
});
function getpartnerfilter(value)
{
	if(value)
	{
		window.location.href = adminurl + 'buskerspodlist?partnerid='+value;
	}
	else
	{
		loadPagination(0);
	}
	   

}
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
		  $( '#myModal' ).modal( 'hide' );
          loadPagination(0);
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
		  $( '#myModal' ).modal( 'hide' );
          loadPagination(0);
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
		  $( '#myModal' ).modal( 'hide' );
          loadPagination(0);
          //setTimeout( function() { window.location.href = adminurl + 'buskerspod'; }, 1000 );
        }
      });
  });    
  return false;
}
// Load pagination
			function loadPagination(pagno){
			var startDate  = $('#start').val();
			var endDate    = $('#end').val();
            var filterDate = $('#filter').val();
			var partnerid = $('#partnerid').val();
				$.ajax({
					url: '<?=base_url()?>index.php/Buskerspodlist/loadRecord/'+pagno,
					type: 'post',
					data:{startDate:startDate,endDate:endDate,filterDate:filterDate,partnerid:partnerid},
					dataType: 'json',
					success: function(response){
						
						$('#pagination').html(response.pagination);
						createTable(response.result,response.row);
					}
				});
			}
// Create table list
			function createTable(result,sno){
				
				if(result!="")
				{
				sno = Number(sno);
				$('#postsList tbody').empty();
				for(index in result){
					var id = result[index].id;
					var pname = result[index].partner_name;
					var city = result[index].cityName;
					//content = content.substr(0, 60) + " ...";
					var start = result[index].start;
					var end = result[index].end;
					sno+=1;

					var tr = "<tr>";
					tr += "<td>"+ sno +"</td>";
					tr += "<td nowrap>"+ pname +"</td>";
					tr += "<td nowrap>"+ city +"</td>";
					tr += "<td nowrap>"+ result[index].startdate +"</td>";
					tr += "<td>"+ start +"</td>";
					tr += "<td>"+ end +"</td>";
					tr += "<td>"+ result[index].partnerFees +"</td>";
					tr += "<td>"+ result[index].status +"</td>";
					tr += "<td>"+ result[index].action +"</td>";
					tr += "</tr>";
					$('#postsList tbody').append(tr);
					
				}
				}
				else
				{
					var tr = "<tr>";
					tr += "<td colspan='9' style='text-align:center'>No data available in table</td>";
					tr += "</tr>";
					$('#postsList tbody').append(tr);
				}
			}
</script>
</body>
</html>