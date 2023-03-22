<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl       = $this->config->item( 'admin_url' );
$site_name      = $this->config->item( 'site_name' );
$dirUrl         = $this->config->item( 'dir_url' );
//If Condition
if( $feedbackInfo ) {
    //Login Type
    $role_lists = $this->Settingsmodel->role_lists();
}
?>
<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <div class="box-tools pull-right">
          <div><a href="<?php echo $assetUrl; ?>feedback" class="btn btn-sm btn-primary">Back</a></div>
        </div>
      </div>
    <div class="box-body">
      <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label"><?= HOST_NAME; ?> Name</label>
                <div><?=$feedbackInfo->guiderName; ?></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">App Version</label>
                <div><?=$feedbackInfo->app_version; ?></div>
            </div>
            <?php
            if($feedbackInfo->device_type == 2){
                  $device_type    = 'iOS';
              }else if($feedbackInfo->device_type == 2){
                  $device_type    = 'Androind';
              }else{
                  $device_type    = '';
              }
              ?>
            <div class="form-group">
                <label class="control-label">Device Type</label>
                <div><?=$device_type; ?></div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label">Subject</label>
                <div><?=$feedbackInfo->subject; ?></div>
            </div>
            <div class="form-group">
                <label class="control-label">Description</label>
                <div><?=$feedbackInfo->description; ?></div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>