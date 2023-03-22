<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl       = $this->config->item( 'admin_url' );
$site_name      = $this->config->item( 'site_name' );
$dirUrl         = $this->config->item( 'dir_url' );
//If Condition
if( $guiderInfo ) {
    $service_region       = $guiderInfo->service_providing_region;
    $guiding_speciality   = $guiderInfo->guiding_speciality;
    $what_i_offer         = $guiderInfo->what_i_offer;
    $cancellation_policy  = $guiderInfo->cancellation_policy;
    $about_me             = $guiderInfo->about_me;
    $photo                = $guiderInfo->photo;
    $photo1               = $guiderInfo->photo1;
    $photo2               = $guiderInfo->photo2;
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
        <h3 class="box-title">Edit Guider</h3>
        <div class="box-tools pull-right">
          <a href="<?php echo $assetUrl; ?>guider" class="btn btn-sm btn-primary">Back</a>
        </div>
      </div>
      <div id="message_guider"></div>
      <form novalidate="" id="edit_user_form" role="form" method="post" class="form-horizontal">
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="user_name" class="col-sm-4 control-label">service_region<span class="text-danger">*</span></label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" value="<?=$service_region;?>" name="user_name" id="user_name" placeholder="User Name">
                </div>
              </div>
              
            </div>
            <div class="col-md-6">
              
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label for="address" class="col-sm-2 control-label">What I Offer</label>
                <div class="col-sm-10">
                  <textarea class="form-control" name="address" id="address"><?=$what_i_offer;?></textarea>
                </div>
              </div>
            </div>
            
          </div>
          <!-- /.box-body -->
          <div class="box-footer">
            <div class="col-md-offset-2 col-md-10">
                <input type="hidden" name="guider_id" id="guider_id" value="<?=$guider_id;?>">
              <button class="btn btn-warning" id="updateguidersubmit" onClick="return updateValidate1();" type="button">Update Guider</button>
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