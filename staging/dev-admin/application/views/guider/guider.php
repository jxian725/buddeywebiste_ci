<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl       = $this->config->item( 'admin_url' );
$site_name      = $this->config->item( 'site_name' );
global $permission_arr;
?>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"></h3>
              <div class="pull-right box-tools">
                <?php if( in_array( 'guider/add', $permission_arr ) ) { ?>
                <a href="<?php echo $assetUrl; ?>guider/add" class="btn btn-primary btn-sm pull-right" data-toggle="tooltip" title="" style="margin-right: 5px;" data-original-title="Add New">
                  <i class="fa fa-plus"></i>
                </a>
                <?php } ?>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        $attributes = array('method' => 'get', 'id' => 'searchform');
                        echo form_open( $assetUrl.'guider', $attributes );
                        $guider_search  = $this->input->get('guider_search');
                        $order_by       = $this->input->get('order_by');
                        ?>
                        <!-- Search Name -->
                        <div class="form-group">
                          <div style="margin-bottom: 10px;padding-left: 0px;" class="search_input col-md-8">
                            <div class="input-group form-search">
                              <input style="width:70%;" type="text" name="guider_search" id="guider_search" class="search-query form-control" value="<?php echo $guider_search; ?>" placeholder="Search by User Name, Mobile Number, Email">
                              <select style="width:30%;" id="order_by" name="order_by" class="form-control">
                                <option value="">Order By</option>
                                <option <?php if($order_by == 1){ echo 'selected'; } ?> value="1">User Name ASC</option>
                                <option <?php if($order_by == 2){ echo 'selected'; } ?> value="2">User Name DESC</option>
                                <option <?php if($order_by == 3){ echo 'selected'; } ?> value="3">Email ASC</option>
                                <option <?php if($order_by == 4){ echo 'selected'; } ?> value="4">Email DESC</option>
                              </select>
                              <span class="input-group-btn">
                                <button data-type="last" class="btn btn-black" type="submit">Search</button>
                              </span>
                            </div>
                          </div>
                        </div>
                        <?php echo form_close(); ?>
                        <div class="clearfix"></div>
                        <div class="form-group">
                          <label>
                            Show
                          </label>
                          <label>
                            <select name="guider_length" id="guider_length" aria-controls="guider_list" class="form-control input-sm">
                              <option <?php if($this->session->userdata('view_guider') == 10){ echo 'selected'; } ?> value="10">10</option>
                              <option <?php if($this->session->userdata('view_guider') == 25){ echo 'selected'; } ?> value="25">25</option>
                              <option <?php if($this->session->userdata('view_guider') == 50){ echo 'selected'; } ?> value="50">50</option>
                              <option <?php if($this->session->userdata('view_guider') == 100){ echo 'selected'; } ?> value="100">100</option>
                            </select>
                          </label>
                          <label>
                            entries
                          </label>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="col-md-12">
                        <div class="box-body table-responsive no-padding">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>User Name</th>
                                    <th>Email</th>
                                    <th>Phone No</th>
                                    <th>Languages Known</th>
                                    <th>About Me</th>
                                    <th>DBKL License</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="guider_lists">
                                <?php
                                if($guider_lists) {
                                    foreach ( $guider_lists as $key => $value ) {
                                        if($value->status == 0){
                                            $status     = '<span class="label label-warning">Pending</span>';
                                            $statusbtn  = '<a href="javascript:;" onClick="return guiderStatus('.$value->guider_id.', 1);" class="btn btn-warning btn-xs" data-toggle="tooltip" data-original-title="Click to Activate"><i class="fa fa-toggle-off" aria-hidden="true"></i></a>';
                                        }else if($value->status == 1){
                                            $status     = '<span class="label label-success">Active</span>';
                                            $statusbtn  = '<a href="javascript:;" onClick="return guiderStatus('.$value->guider_id.', 2);" class="btn btn-success btn-xs" data-toggle="tooltip" data-original-title="Click to Inactive"><i class="fa fa-toggle-on" aria-hidden="true"></i></a>';
                                        }else if($value->status == 2){
                                            $status     = '<span class="label label-danger">Inactive</span>';
                                            $statusbtn  = '<a href="javascript:;" onClick="return guiderStatus('.$value->guider_id.', 1);" class="btn btn-warning btn-xs" data-toggle="tooltip" data-original-title="Click to Activate"><i class="fa fa-toggle-off" aria-hidden="true"></i></a>';
                                        }
                                        $lang = [];
                                        if($value->languages_known){
                                            $array  = explode(',', $value->languages_known);
                                            foreach ($array as $item) {
                                                $langInfo = $this->Guidermodel->guiderLangInfo($item);
                                                if($langInfo){ $lang[] = $langInfo->language; }
                                            }
                                        }
                                        if($value->qr_image){
                                          $qr_image = '<a href="javascript:;" onClick="return get_qr_code('.$value->guider_id.');"  data-toggle="tooltip" title="Get QR Code" class="btn btn-xs btn-primary"> <i class="fa fa-qrcode"></i></a>';
                                        }else{
                                          $qr_image = '<a href="javascript:;" onClick="return generate_qr_code('.$value->guider_id.');"  data-toggle="tooltip" title="Generate QR Code" class="btn btn-xs btn-warning"> <i class="fa fa-qrcode"></i></a>';
                                        }
                                        if($value->dbkl_status == 1){
                                            $dbklStatusBtn  = '<a href="javascript:;" onClick="return updatedbklstatusModal('.$value->guider_id.', 1);" class="btn btn-success btn-xs" data-toggle="tooltip" data-original-title="Click to View License">Approved</a>';
                                        }else if($value->dbkl_status == 2){
                                            $dbklStatusBtn  = '<a href="javascript:;" onClick="return updatedbklstatusModal('.$value->guider_id.', 2);" class="btn btn-warning btn-xs" data-toggle="tooltip" data-original-title="Click to View License">In Review</a>';
                                        }else if($value->dbkl_status == 3){
                                            $dbklStatusBtn  = '<a href="javascript:;" onClick="return updatedbklstatusModal('.$value->guider_id.', 3);" class="btn btn-danger btn-xs" data-toggle="tooltip" data-original-title="Click to View License">Rejected</a>';
                                        }else{
                                            $dbklStatusBtn  = '<a href="javascript:;" class="btn btn-default btn-xs">NIL</a>';
                                        }
                                        ?>
                                        <tr>
                                            <td><a href="<?=$assetUrl; ?>guider/payout_info/<?=$value->guider_id; ?>" data-toggle="tooltip" data-original-title="View Payout"><?=$value->first_name;?></a></td>
                                            <td><?=$value->email;?></td>
                                            <td><?=$value->phone_number;?></td>
                                            <td><?=ucfirst( implode(',', $lang) );?></td>
                                            <td><?=mb_substr($value->about_me, 0, 25);?></td>
                                            <td><?=$dbklStatusBtn;?></td>
                                            <td><?=$status;?></td>
                                            <td>
                                                <?php if( in_array( 'guider/status', $permission_arr ) ) { ?>
                                                <?=$statusbtn;?>
                                                <?php } ?>
                                                <?php if( in_array( 'guider/index', $permission_arr ) ) { ?>
                                                <a class="btn btn-primary btn-xs" href="<?=$assetUrl; ?>guider/view/<?=$value->guider_id; ?>" data-toggle="tooltip" data-original-title="View"><i class="fa fa-search"></i></a>
                                                <?php } ?>
                                                <?php if( in_array( 'guider/deleteGuider', $permission_arr ) ) { ?>
                                                <a class="btn btn-danger btn-xs" href="javascript:;" onClick="return passwordConfirm(<?=$value->guider_id; ?>);"><i class="fa fa-remove" data-toggle="tooltip" data-original-title="delete"></i></a>
                                                <?php } ?>
                                                <?= $qr_image; ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
function guiderStatus( guider_id,status ) {
    $( '#myModal .modal-title' ).html( 'Confirm' );
    if(status == 1){
        $( '#myModal .modal-body' ).html( 'Are you sure want to Activate this guider ?' );
    }else{
        $( '#myModal .modal-body' ).html( 'Are you sure want to Deactive this guider ?' );
    }
    $( '#myModal .modal-body' ).append( '<br /><br /><button class="btn btn-info btn-sm" id="continuemodal" data-dismiss="modal">Yes</button>&nbsp;&nbsp;<button aria-hidden="true" data-dismiss="modal" class="btn btn-danger btn-sm">Cancel</button>' );
    $( '#myModal' ).modal({ backdrop: 'static', keyboard: false }); 
    $( "#continuemodal" ).click(function() {
        var data = { 'guider_id':guider_id,'status':status }
        $.ajax({
          type: "POST",
          url: adminurl + 'guider/guiderStatus',
          data: data,
          success: function( data ) {
            toastr.success( 'Guider Status Updated Successfully.','Success' );
            setTimeout( function() {
              location.reload();
            }, 2000 );
          }
        });
    });    
    return false;
}

//Update Password Modal
function passwordConfirm( guider_id ) {
    var data = 'guider_id=' + guider_id;
    $.ajax( {
        type: "POST",
        data: data,
        url: adminurl + 'guider/passwordConfirm',
        success: function( msg ) {
            $( '#myModal .modal-title' ).html( 'Password Confirmation' );
            $( '#myModal .modal-body' ).html( msg );
            $( '#myModal .modal-footer' ).html( '' );
            $( '#myModal' ).modal( 'show' );
        }
    });
    return false;
}

function update_password_info( guider_id ) {
    var data = $( '#update_guider_form' ).serialize();

    $.ajax({
        type    : "POST",
        data    : data,
        url     : adminurl + 'guider/update_password_info',
        dataType: 'json',
        success: function( msg ) {
            if( msg.Jerror == 2 ) {
                toastr.success( 'Password confirmation successfully.','Success' );
                deleteGuider( guider_id, 4 ); 
            } else if( msg.Jerror == 3 ) { 
                toastr.error( msg.Jmsg,'Error' );     
            } else {
                toastr.error( msg.Jmsg, 'Error' );
            }
        }
    });
  return false;
}

function deleteGuider( guider_id,status ) {
    $( '#myModal .modal-title' ).html( 'Confirm' );
    $( '#myModal .modal-body' ).html( 'Are you sure want to Delete this guider ?' );
    $( '#myModal .modal-body' ).append( '<br /><br /><button class="btn btn-info btn-sm" id="continuemodal" data-dismiss="modal">Yes</button>&nbsp;&nbsp;<button aria-hidden="true" data-dismiss="modal" class="btn btn-danger btn-sm">Cancel</button>' );
    $( '#myModal' ).modal({ backdrop: 'static', keyboard: false }); 
    $( "#continuemodal" ).click(function() {
        var data = { 'guider_id':guider_id,'status':status }
        $.ajax({
            type: "POST",
            url: adminurl + 'guider/deleteGuider',
            data: data,
            success: function( data ) {
                toastr.success( 'Guider Delete Successfully.','Success' );
                setTimeout( function() {
                    location.reload();
                }, 2000 );
            }
        });
    });
return false;
}

function generate_qr_code(guider_id) {
    var data = { 'guider_id':guider_id }
    $.ajax({
      type: "POST",
      url: adminurl + 'guider/generate_qr_code',
      data: data,
      success: function( data ) {
        toastr.success( 'QR Image Generated Successfully.','Success' );
        setTimeout( function() {
          location.reload();
        }, 2000 );
      }
    });
}

function get_qr_code( guider_id ) {
    var data = { 'guider_id':guider_id }
    $.ajax({
        type  : 'POST',
        url   : adminurl + 'guider/get_qr_code',
        data  : data,
        async : false,
        success     : function( msg ) {
            $( '#myModal' ).modal( 'show' );
            $('#myModal .modal-title').html('Event QR Code');
            $( '#myModal .modal-body' ).html(msg);
        }
    });
  return false;
}
//UPDATE DBKL STATUS
function updatedbklstatusModal( guider_id ) {
    var data = 'guider_id=' + guider_id;
    $.ajax( {
        type: "POST",
        data: data,
        url: adminurl + 'guider/updatedbklStatusForm',
        success: function( msg ) {
            $( '#myModal .modal-title' ).html( 'Update DBKL License Status' );
            $( '#myModal .modal-body' ).html( msg );
            $( '#myModal .modal-footer' ).html( '' );
            $( '#myModal' ).modal( 'show' );
        }
    });
    return false;
}
</script>