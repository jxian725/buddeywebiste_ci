<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl       = $this->config->item( 'admin_url' );
$site_name      = $this->config->item( 'site_name' );
$dirUrl         = $this->config->item( 'dir_url' );

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
      <form novalidate="" id="add_user_form" role="form" method="post" class="form-horizontal">
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="user_name" class="col-sm-4 control-label">User Name<span class="text-danger">*</span></label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="user_name" id="user_name" placeholder="User Name">
                </div>
              </div>
              <div class="form-group">
                <label for="mobile_number" class="col-sm-4 control-label">Mobile number</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control number" maxlength="12" name="mobile_number" id="mobile_number" placeholder="Mobile number">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="contact_email" class="col-sm-4 control-label">Contact Email</label>
                <div class="col-sm-8">
                  <input type="email" placeholder="Contact Email" name="contact_email" id="contact_email" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <label for="contact_full" class="col-sm-4 control-label">Full Name</label>
                <div class="col-sm-8">
                  <input type="text" placeholder="Full name" name="contact_full" id="contact_full" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label for="address" class="col-sm-2 control-label">Address</label>
                <div class="col-sm-10">
                  <textarea class="form-control" name="address" id="address"></textarea>
                </div>
              </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="password" class="col-sm-4 control-label">Password</label>
                    <div class="col-sm-8">
                        <input type="password" id="password" name="password" class="form-control" value="" placeholder="Password" />
                    </div>    
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="confirm_password" class="col-sm-4 control-label">Confirm password</label>
                    <div class="col-sm-8">
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" value="" placeholder="Confirm password" />
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
                                      # code...
                                      echo '<option value="'. $value->role_id .'">'. rawurldecode( $value->role ) .'</option>';
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
              <button class="btn btn-info" id="addusersubmit" onClick="return userValidate();" type="button">Create User</button>
              <button type="reset" class="btn btn-danger" type="reset">Clear</button>
            </div>
          </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">
    //function create user
    function userValidate() {
        var $btn  = $( '#addusersubmit' );
        $btn.button( 'Posting..' );
        var data    = $( "#add_user_form" ).find( "select, textarea, input" ).serialize();
        $.ajax({
            type    : 'POST',
            url     : adminurl + 'user/add_user',
            data    : data,
            success : function( msg ) {
                if( msg == 1 ) {
                    toastr.success('User added Successfully.');
                    setTimeout( function() {
                      window.location.href = adminurl + 'settings';
                    }, 700 );
                } else {
                    toastr.error(msg,'Error');
                }
                $btn.button( 'reset' );
            }
        });
        return false;
    }
</script>