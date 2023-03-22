<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl       = $this->config->item( 'admin_url' );
$site_name      = $this->config->item( 'site_name' );
$dirUrl         = $this->config->item( 'dir_url' );
//If Condition
if( $userInfo ) {
    $full_name = $userInfo->full_name;
    $username  = $userInfo->username;
    $user_email = $userInfo->user_email;
    $contact_number = $userInfo->contact_number;
    $address = $userInfo->address;
    //Login Type
    $role_lists = $this->Settingsmodel->role_lists();
}
?>
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo $assetUrl; ?>assets/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<style type="text/css">
#imgGuider1,#imgGuider2,#imgGuider3 { display:none;}
</style>
<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"></h3>
        <div class="box-tools pull-right">
          <a href="<?php echo $assetUrl; ?>user" class="btn btn-sm btn-primary">Back</a>
        </div>
      </div>
      <div id="message_guider"></div>
      <form novalidate="" id="edit_user_form" role="form" method="post" class="form-horizontal">
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="user_name" class="col-sm-4 control-label">User Name<span class="text-danger">*</span></label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" value="<?=$username;?>" name="user_name" id="user_name" placeholder="User Name">
                </div>
              </div>
              <div class="form-group">
                <label for="mobile_number" class="col-sm-4 control-label">Mobile number</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control number" value="<?=$contact_number;?>" maxlength="12" name="mobile_number" id="mobile_number" placeholder="Mobile number">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="contact_email" class="col-sm-4 control-label">Contact Email</label>
                <div class="col-sm-8">
                  <input type="email" placeholder="Contact Email" value="<?=$user_email;?>" name="contact_email" id="contact_email" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <label for="contact_full" class="col-sm-4 control-label">Full Name</label>
                <div class="col-sm-8">
                  <input type="text" placeholder="Full name" name="contact_full" value="<?=$full_name;?>" id="contact_full" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label for="address" class="col-sm-2 control-label">Address</label>
                <div class="col-sm-10">
                  <textarea class="form-control" name="address" id="address"><?=$address;?></textarea>
                </div>
              </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="role_type" class="col-sm-4 control-label">Role Type</label>
                    <div class="col-sm-8">
                        <select class="form-control" name="role_type" id="role_type">
                            <option value="">Select role type</option>
                            <?php if( $role_lists ) { 
                                    foreach ( $role_lists as $key => $value ) {
                                      $selected = '';
                                      if( $value->role_id == $userInfo->account_type ) {
                                        $selected = 'selected';
                                      }
                                      # code...
                                      echo '<option '. $selected .' value="'. $value->role_id .'">'. rawurldecode( $value->role ) .'</option>';
                                    }
                                  }
                              ?>
                        </select>
                    </div>    
                </div>
            </div>
          </div>
          <!-- /.box-body -->
          <div class="box-footer">
            <div class="col-md-offset-2 col-md-10">
                <input type="hidden" name="user_id" id="user_id" value="<?=$user_id;?>">
              <button class="btn btn-warning" id="updateusersubmit" onClick="return updateValidate();" type="button">Update User</button>
            </div>
          </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">
    //function update user
    function updateValidate() {
        var $btn  = $( '#updateusersubmit' );
        $btn.button( 'Posting..' );
        var data    = $( "#edit_user_form" ).find( "select, textarea, input" ).serialize();
        $.ajax({
            type    : 'POST',
            url     : adminurl + 'user/edit_user',
            data    : data,
            success : function( msg ) {
                if( msg == 1 ) {
                    toastr.success('User update Successfully.');
                    window.location.href( 'user' );
                } else {
                    toastr.error(msg,'Error');
                }
                $btn.button( 'reset' );
            }
        });
        return false;
    }
</script>