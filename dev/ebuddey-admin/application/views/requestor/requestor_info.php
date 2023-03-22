<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl   = $this->config->item( 'admin_url' );
$site_name  = $this->config->item( 'site_name' );
$dirUrl     = $this->config->item( 'dir_url' );

$name       = $requestorInfo->first_name;
?>
<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Requestor Info</h3>
        <div class="box-tools pull-right">
          <a href="<?php echo $assetUrl; ?>requestor" class="btn btn-sm btn-primary">Back</a>
        </div>
      </div>
      <div class="box-body">
        <div class="col-md-2">
          <h3 class="box-title"></h3>
          <?php
          if($requestorInfo->photo){ ?>
            <div class="img-view"><img src="<?=$requestorInfo->photo; ?>" id="client_picture" style="height: auto; width: 100%;" data-src="#" /></div>
          <?php
          }else {
          ?>
            <div class="img-view"><img src="<?=$dirUrl; ?>uploads/no_image.png" id="client_picture" style="height:100px;width: auto;" data-src="#" /></div>
          <?php
          }
          if($requestorInfo->photo1){ ?>
            <div class="img-view"><img src="<?=$requestorInfo->photo1; ?>" id="client_picture" style="height: auto;width: 100%;" data-src="#" /></div>
          <?php
          }else {
          ?>
            <div class="img-view"><img src="<?=$dirUrl; ?>uploads/no_image.png" id="client_picture" style="height:100px;width: auto;" data-src="#" /></div>
          <?php
          }
          if($requestorInfo->photo2){ ?>
            <div class="img-view"><img src="<?=$requestorInfo->photo2; ?>" id="client_picture" style="height: auto;width: 100%;" data-src="#" /></div>
          <?php
          }else {
          ?>
            <div class="img-view"><img src="<?=$dirUrl; ?>uploads/no_image.png" id="client_picture" style="height:100px;width: auto;" data-src="#" /></div>
          <?php
          }
          ?>
        </div>
        <div class="col-md-10">
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label">User Name</label>
              <div><?=$requestorInfo->first_name; ?></div>
            </div>
            <div class="form-group">
              <label class="control-label">Phone Number</label>
              <div><?=$requestorInfo->phone_number; ?></div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label">Email</label>
              <div><?=$requestorInfo->email; ?></div>
            </div>
            <div class="form-group">
              <label class="control-label">Languages Known</label>
              <div><?=$requestorInfo->languages_known; ?></div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <label class="control-label">About Me</label>
              <div><?=$requestorInfo->about_me; ?></div>
            </div>
          </div>
        </div>
        <div class="clearfix"></div>
    </div>
  </div>
</div>