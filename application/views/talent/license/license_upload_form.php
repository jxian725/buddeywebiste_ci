<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$adminUrl = $this->config->item( 'admin_dir_url' );
?>
<style type="text/css">
#img_preview img{
    height: 45px;
}
</style>
<div id="update_license_form" class="box-body">
    <div class="row">
        <p class="text-danger" id="alert-msg"></p>
        <div class="col-md-12">
            <div class="form-group text-right"><span class="label label-primary"><?= $licenseInfo->license_name; ?></span></div>
        </div>
        <form novalidate="" id="licenseForm" role="form" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <div class="col-md-4">
                    <label for="license_image" style="padding-top: 10px;">Select verification <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                      <input type="file" class="form-control" name="license_image" id="license_image" accept="image/*">
                      <small>Only JPG, PNG and GIF files are allowed</small>
                        <?php
                        if($talentLicenseInfo && $talentLicenseInfo->license_image){
                        echo '<div id="img_preview"><img src="'.$adminUrl.'uploads/license/'.$talentLicenseInfo->license_image.'" /></div>';
                        } ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-4">
                    <label for="license_number" style="padding-top: 10px;">Verification Number <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                      <input type="text" class="form-control" name="license_number" id="license_number" value="<?php echo ($talentLicenseInfo)? $talentLicenseInfo->license_number : '';?>" maxlength="30">
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <input type="hidden" name="talent_license_id" id="talent_license_id" value="<?php echo ($talentLicenseInfo)? $talentLicenseInfo->tl_id : '';?>">
                <input type="hidden" name="license_id" id="license_id" value="<?= $licenseInfo->license_id; ?>">
                <input type="hidden" name="is_submit" id="is_submit" value="1">
            </div>
        </form>
    </div>
</div>