<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl       = $this->config->item( 'admin_url' );
$site_name      = $this->config->item( 'site_name' );
$dirUrl         = $this->config->item( 'dir_url' );
//If Condition
if( $venuepartnerInfo ) {
    //Login Type
    $role_lists = $this->Settingsmodel->role_lists();
}
?>
<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header">
        <div class="box-tools pull-right">
          <a href="<?php echo $assetUrl; ?>feedback" class="btn btn-sm btn-primary">Back</a>
        </div>
      </div>
    <div class="box-body">
      <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Venue partner Name</label>
                <div><?=$venuepartnerInfo->venuepartner_name; ?></div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label">Subject</label>
                <div><?=$venuepartnerInfo->subject; ?></div>
            </div>
            <div class="form-group">
                <label class="control-label">Description</label>
                <div><?=$venuepartnerInfo->description; ?></div>
            </div>
            <div class="form-group">
                <label class="control-label">Date Time</label>
                <div><?= date('d M Y H:i A', strtotime($venuepartnerInfo->createdon)); ?></div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>