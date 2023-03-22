<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl       = $this->config->item( 'admin_url' );
$site_name      = $this->config->item( 'site_name' );
global $permission_arr;
?>
<style type="text/css">
#imgspecialization { display:none;}
</style>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                      <div id="message_role"></div>
                      <?php if( in_array( 'role/add', $permission_arr ) ) { ?>
                      <form novalidate="" id="role_form" role="form" method="post" class="form-horizontal">
                        <div class="box-body" id="add_role_form">
                          <div class="form-group">
                            <label class="col-md-2 control-label" for="role">Role Name <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                              <input type="text" required="" placeholder="Enter Role Name" id="role" class="form-control" name="role">
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-md-2 control-label" for="role_code">Role Code <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                              <input type="text" required="" placeholder="Enter Role Code" id="role_code" class="form-control" name="role_code">
                            </div>
                          </div>
                          <div class="col-md-offset-2 col-md-8 text-right">
                            <button class="btn btn-sm btn-success" id="addrolesubmit" onClick="return validaterole();" type="button">Add</button>
                          </div>
                        </div>
                        <div class="box-body">
                            <div id="update_role_form"></div>
                        </div>
                      </form>
                      <?php } ?>
                      <div class="box-body table-responsive no-padding">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Role Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="role_lists">
                                <?php
                                if( $role_lists ) {
                                    foreach ( $role_lists as $key => $value ) {
                                        ?>
                                        <tr>
                                            <td><?=rawurldecode( $value->role );?></td>
                                            <td>
                                                <?php if( in_array( 'role/add', $permission_arr ) ) { ?>
                                                <a class="btn btn-warning btn-xs" href="javascript:;" onclick="return edit_role( '<?=$value->role_id;?>' );"><i class="fa fa-edit"></i></a> 
                                                <?php } ?>
                                                <?php if( in_array( 'role/delete', $permission_arr ) ) { ?>
                                               <a class="btn btn-danger btn-xs" href="javascript:;" onclick="return delete_role( '<?=$value->role_id;?>' );"><i class="fa fa-trash"></i></a> 
                                               <?php } ?>
                                               <?php if( in_array( 'role/permission', $permission_arr ) ) { ?>
                                               <a class="btn btn-success btn-xs" href="<?=$assetUrl;?>settings/permission/<?=$value->role_id;?>">Permission <i class="fa fa-plus"></i></a> 
                                               <?php } ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr><td colspan="2">No data to display.</td></tr>
                                    <?php
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
    //Role Validation
    function validaterole() {
        var $btn  = $( '#addrolesubmit' );
        $btn.button( 'Posting..' );
        var data    = $( "#role_form" ).find( "select, textarea, input" ).serialize();
        $.ajax({
            type    : 'POST',
            url     : adminurl + 'settings/roleValidate',
            data    : data,
            success : function( msg ) {
                if( msg == 1 ) {
                    toastr.success('Role added Successfully.');
                    location.reload();
                } else {
                    toastr.error(msg,'Error');
                }
                $btn.button( 'reset' );
            }
        });
        return false;
    }
    function delete_role( role_id ) {
      $( '#myModal .modal-title' ).html( 'Confirm' );
      $( '#myModal .modal-body' ).html( 'Are you sure want to delete the role ?' );
      $( '#myModal .modal-body' ).append( '<br /><br /><button class="btn btn-info btn-sm" id="continuemodal" data-dismiss="modal">Yes</button>&nbsp;&nbsp;<button aria-hidden="true" data-dismiss="modal" class="btn btn-danger btn-sm">Cancel</button>' );
      $( '#myModal' ).modal({ backdrop: 'static', keyboard: false }); 
      $( "#continuemodal" ).click(function() {
          var account_data    = 'role_id=' + role_id;
          $.ajax({
            type: "POST",
            url: adminurl + 'settings/delete_role',
            data: account_data,
            success: function( data ) {
              toastr.success( 'Role deleted successfully.','Success' );
              setTimeout( function() {
                window.location.href = adminurl + 'settings';
              }, 2000 );
            }
          });
      });    
      return false;
    }
    //Edit Role
    function edit_role( role_id ) {
        var account_data    = 'role_id=' + role_id;
        $.ajax({
            type : "POST",
            url  : adminurl + 'settings/edit_role',
            data : account_data,
            success: function( data ) { 
                $( '#add_role_form' ).hide();
                $( '#update_role_form' ).html( data );
            }
        });
    }
</script>