<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl       = $this->config->item( 'admin_url' );
$site_name      = $this->config->item( 'site_name' );

?>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">
                User lists
              </h3>
              <div class="pull-right box-tools">
                <a href="<?php echo $assetUrl; ?>user/add" class="btn btn-primary btn-sm pull-right" data-toggle="tooltip" title="" style="margin-right: 5px;" data-original-title="Add New">
                  <i class="fa fa-plus"></i>
                </a>
              </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        $attributes = array('method' => 'get', 'id' => 'searchform');
                        echo form_open( $assetUrl.'user', $attributes );
                        $user_search  = $this->input->get('user_search');
                        $order_by     = $this->input->get('order_by');
                        ?>
                        <!-- Search Name -->
                        <div class="form-group">
                          <div style="margin-bottom: 10px;padding-left: 0px;" class="search_input col-md-8">
                            <div class="input-group form-search">
                              <input style="width:70%;" type="text" name="user_search" id="user_search" class="search-query form-control" value="<?php echo $user_search; ?>" placeholder="Search by User Name, Mobile Number, Email">
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
                            <select name="user_length" id="user_length" aria-controls="user_list" class="form-control input-sm">
                              <option <?php if($this->session->userdata('view_user') == 10){ echo 'selected'; } ?> value="10">10</option>
                              <option <?php if($this->session->userdata('view_user') == 25){ echo 'selected'; } ?> value="25">25</option>
                              <option <?php if($this->session->userdata('view_user') == 50){ echo 'selected'; } ?> value="50">50</option>
                              <option <?php if($this->session->userdata('view_user') == 100){ echo 'selected'; } ?> value="100">100</option>
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
                                    <th>User Type</th>
                                    <th>Email</th>
                                    <th>Phone No</th>
                                    <th>Password</th>
                                    <th>Address</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="user_lists">
                                <?php
                                if($user_lists) {
                                    foreach ( $user_lists as $key => $value ) {
                                      $roleInfo   = $this->Settingsmodel->roleInfo($value->account_type);
                                        ?>
                                        <tr>
                                            <td><?=$value->username;?></td>
                                            <td><?=(($roleInfo)? urldecode($roleInfo->role) : '');?></td>
                                            <td><?=$value->user_email;?></td>
                                            <td><?=$value->contact_number;?></td>
                                            <td><?=$this->encryption->decrypt($value->password); ?></td>
                                            <td><?=ucfirst( $value->address );?></td>
                                            <td>
                                                <a class="btn btn-warning btn-xs" href="<?=$assetUrl; ?>user/edit/<?=$value->user_id; ?>" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-edit"></i></a>
                                                <a class="btn btn-primary btn-xs" href="javascript:;" onClick="return changePassword(<?=$value->user_id; ?>);"><i class="fa fa-key" data-toggle="tooltip" data-original-title="Change Password"></i></a>
                                                <?php
                                                if($this->session->userdata( 'USER_ID' ) != $value->user_id){ ?>
                                                  <a class="btn btn-danger btn-xs" href="javascript:;" onClick="return passwordConfirm(<?=$value->user_id; ?>);"><i class="fa fa-remove" data-toggle="tooltip" data-original-title="delete"></i></a>
                                                <?php } ?>
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
  function changePassword( user_id ) {
      var data = 'user_id=' + user_id+'&type=change';
      $.ajax( {
          type: "POST",
          data: data,
          url: adminurl + 'user/passwordConfirm',
          success: function( msg ) {
            $( '#myModal .modal-title' ).html( 'Change Password' );
            $( '#myModal .modal-body' ).html( msg );
            $( '#myModal .modal-footer' ).html( '' );
            $( '#myModal' ).modal( 'show' );
          }
      });
      return false;
  }
  //Update Password Modal
  function passwordConfirm( user_id ) {
      var data = 'user_id=' + user_id+'&type=verify';
      $.ajax( {
          type: "POST",
          data: data,
          url: adminurl + 'user/passwordConfirm',
          success: function( msg ) {
            $( '#myModal .modal-title' ).html( 'Password Confirmation' );
            $( '#myModal .modal-body' ).html( msg );
            $( '#myModal .modal-footer' ).html( '' );
            $( '#myModal' ).modal( 'show' );
          }
      });
      return false;
  }
  function verify_password_info( user_id ) {
    var data = $( '#update_user_form' ).serialize();
    //alert( data );
      $.ajax( {
          type    : "POST",
          data    : data,
          url     : adminurl + 'user/verify_password_info',
          dataType: 'json',
          success: function( msg ) {
              if( msg.res == 2 ) {
                 toastr.success( 'Password confirmation successfully.','Success' );
                 deleteUser( user_id, 2 ); 
              } else if( msg.res == 3 ) { 
                  toastr.error( msg.Jmsg,'Error' );     
              } else if( msg.res == 4 ) {
                  toastr.success( msg.Jmsg,'Success' );
                  setTimeout( function() {
                    location.reload();
                  }, 2000 );
              } else {
                  toastr.error( msg.Jmsg, 'Error' );
              }
              $( '#update_user_form #password' ).val('');
          }
      });
      return false;
  }
  function deleteUser( user_id,status ) {
      $( '#myModal .modal-title' ).html( 'Confirm' );
      $( '#myModal .modal-body' ).html( 'Are you sure want to Delete this User ?' );
      $( '#myModal .modal-body' ).append( '<br /><br /><button class="btn btn-info btn-sm" id="continuemodal" data-dismiss="modal">Yes</button>&nbsp;&nbsp;<button aria-hidden="true" data-dismiss="modal" class="btn btn-danger btn-sm">Cancel</button>' );
      $( '#myModal' ).modal({ backdrop: 'static', keyboard: false }); 
      $( "#continuemodal" ).click(function() {
        var data = { 'user_id':user_id,'status':status }
        $.ajax({
          type: "POST",
          url: adminurl + 'user/deleteUser',
          data: data,
          success: function( data ) {
            toastr.success( 'User Delete Successfully.','Success' );
            setTimeout( function() {
              location.reload();
            }, 2000 );
          }
        });
    });    
    return false;
  }
</script>