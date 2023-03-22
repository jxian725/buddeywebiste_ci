<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl       = $this->config->item( 'admin_url' );
$site_name      = $this->config->item( 'site_name' );
$dirUrl         = $this->config->item( 'dir_url' );
?>
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo $assetUrl; ?>assets/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Add Buskerspod</h3>
        <div class="box-tools pull-right">
          <a href="<?php echo $assetUrl; ?>buskerspod" class="btn btn-sm btn-primary">Back</a>
        </div>
      </div>
      <form novalidate="" id="add_buskerspod_form" role="form" method="post" class="form-horizontal">
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="full_name" class="col-sm-4 control-label">Full Name<span class="text-danger">*</span></label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="full_name" id="full_name" placeholder="User Name">
                </div>
              </div>
              <div class="form-group">
                <label for="contact_number" class="col-sm-4 control-label">Contact number</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control number" maxlength="12" name="contact_number" id="contact_number" placeholder="Contact number">
                </div>
              </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="other_name" class="col-sm-4 control-label">Other Name</label>
                    <div class="col-sm-8">
                      <input type="text" placeholder="Full name" name="other_name" id="other_name" class="form-control">
                    </div>
                  </div>
                <div class="form-group">
                    <label for="email" class="col-sm-4 control-label">Contact Email</label>
                    <div class="col-sm-8">
                      <input type="email" placeholder="Contact Email" name="email" id="email" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="identification" class="col-sm-4 control-label">Identification</label>
                    <div class="col-sm-8">
                        <input type="text" id="identification" name="identification" class="form-control" value="" placeholder="identification" />
                    </div>    
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="gender" class="col-sm-4 control-label">Gender</label>
                    <div class="col-sm-8">
                        <select class="form-control" name="gender" id="gender">
                            <option value="">Select</option>
                            <?php
                            if( $genderLists ) {
                                foreach ( $genderLists as $key => $value ) {
                                  echo '<option value="'. $key .'">'. $value .'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>    
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="skills" class="col-sm-4 control-label">Skill</label>
                    <div class="col-sm-8">
                        <select class="form-control" name="skills" id="skills">
                            <option value="">Select</option>
                            <?php 
                            if( $skillsLists ) { 
                                foreach ( $skillsLists as $key => $value ) {
                                  echo '<option value="'. $value .'">'. $value .'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>    
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="partner_id" class="col-sm-4 control-label">Partner <span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <select class="form-control" name="partner_id" id="partner_id">
                            <option value="">Select</option>
                            <?php 
                            if( $partnerList ) { 
                                foreach ( $partnerList as $key => $value ) {
                                  echo '<option value="'. $value->partner_id .'">'. rawurldecode($value->partner_name) .'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>    
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="status" class="col-sm-4 control-label">Status <span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <select class="form-control" name="status" id="status">
                            <option value="">Select</option>
                            <option value="1">Active</option>
                            <option value="2">Non Active</option>
                        </select>
                    </div>    
                </div>
            </div>
          </div>
          <!-- /.box-body -->
          <div class="box-footer">
            <div class="col-md-offset-2 col-md-10">
              <button class="btn btn-info" id="addbuskerspodsubmit" onClick="return buskerspodValidate();" type="button">Create</button>
              <button type="reset" class="btn btn-danger" type="reset">Clear</button>
            </div>
          </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">
    //function create user
    function buskerspodValidate() {
        var $btn  = $( '#addbuskerspodsubmit' );
        $btn.button( 'Posting..' );
        var data    = $( "#add_buskerspod_form" ).find( "select, textarea, input" ).serialize();
        $.ajax({
            type    : 'POST',
            url     : adminurl + 'buskerspod/buskerspodValidate',
            data    : data,
            success : function( msg ) {
                if( msg == 1 ) {
                    toastr.success('Buskerspod added Successfully.');
                    setTimeout( function() {
                      window.location.href = adminurl + 'buskerspod';
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