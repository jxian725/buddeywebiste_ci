<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl       = $this->config->item( 'admin_url' );
$site_name      = $this->config->item( 'site_name' );

?>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        $attributes = array('method' => 'get', 'id' => 'searchform');
                        echo form_open($assetUrl.'requestor', $attributes);
                        $requestor_search   = $this->input->get('requestor_search');
                        $order_by           = $this->input->get('order_by');
                        ?>
                        <!-- Search Name -->
                        <div class="form-group">
                          <div style="margin-bottom: 10px;padding-left: 0px;" class="search_input col-md-8">
                            <div class="input-group form-search">
                              <input style="width:70%;" type="text" name="requestor_search" id="requestor_search" class="search-query form-control" value="<?php echo $requestor_search; ?>" placeholder="Search by User Name, Mobile Number, Email">
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
                            <select name="requestor_length" id="requestor_length" aria-controls="requestor_list" class="form-control input-sm">
                              <option <?php if($this->session->userdata('view_requestor') == 10){ echo 'selected'; } ?> value="10">10</option>
                              <option <?php if($this->session->userdata('view_requestor') == 25){ echo 'selected'; } ?> value="25">25</option>
                              <option <?php if($this->session->userdata('view_requestor') == 50){ echo 'selected'; } ?> value="50">50</option>
                              <option <?php if($this->session->userdata('view_requestor') == 100){ echo 'selected'; } ?> value="100">100</option>
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
                                    <th>User name</th>
                                    <th>Email</th>
                                    <th>Phone no</th>
                                    <th>Languages Known</th>
                                    <th>About me</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="requestor_lists">
                                <?php
                                if( $requestor_lists ) {
                                    foreach ( $requestor_lists as $key => $value ) {
                                        if($value->status == 0){
                                            $status = '<span class="label label-warning">Pending</span>';
                                        }else if($value->status == 1){
                                            $status = '<span class="label label-success">Active</span>';
                                        }else if($value->status == 2){
                                            $status = '<span class="label label-danger">Inactive</span>';
                                        }
                                        ?>
                                        <tr>
                                            <td><?=$value->first_name;?></td>
                                            <td><?=$value->email;?></td>
                                            <td><?=$value->phone_number;?></td>
                                            <td><?=ucfirst( $value->languages_known );?></td>
                                            <td><?=mb_substr($value->about_me, 0, 25);?></td>
                                            <td><?=$status;?></td>
                                            <td>
                                               <a class="btn btn-primary btn-sm" href="<?=$assetUrl; ?>requestor/view/<?=$value->traveller_id; ?>"><i class="fa fa-search"></i></a>
                                               <a class="btn btn-danger btn-sm" href="javascript:;" onClick="return deleteRequestor(<?=$value->traveller_id; ?>, 4);"><i class="fa fa-remove" data-toggle="tooltip" data-original-title="delete"></i></a>
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
function deleteRequestor( traveller_id,status ) {
    $( '#myModal .modal-title' ).html( 'Confirm' );
    $( '#myModal .modal-body' ).html( 'Are you sure want to Delete this requestor ?' );
    $( '#myModal .modal-body' ).append( '<br /><br /><button class="btn btn-info btn-sm" id="continuemodal" data-dismiss="modal">Yes</button>&nbsp;&nbsp;<button aria-hidden="true" data-dismiss="modal" class="btn btn-danger btn-sm">Cancel</button>' );
    $( '#myModal' ).modal({ backdrop: 'static', keyboard: false }); 
    $( "#continuemodal" ).click(function() {
    var data = { 'traveller_id':traveller_id,'status':status }
        $.ajax({
          type: "POST",
          url: adminurl + 'requestor/requestorStatus',
          data: data,
          success: function( data ) {
            toastr.success( 'Requestor Delete Successfully.','Success' );
            setTimeout( function() {
              location.reload();
            }, 2000 );
          }
        });
    });    
    return false;
  }
</script>