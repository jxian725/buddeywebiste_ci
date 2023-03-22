<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 
$adminUrl   = $this->config->item( 'admin_dir_url' );
$dirUrl     = $this->config->item( 'dir_url' );
?>
<?php
$talent_id = $this->session->userdata['TALENT_ID'];
if($getLicenseList){
    foreach ($getLicenseList as $key => $value) {
        $licenseInfo = $this->Talentmodel->talentLicenseInfo($talent_id, $value->license_id);
        if($licenseInfo){
            $licenseImg = $adminUrl.'uploads/license/'.$licenseInfo->license_image;
            $license_no = $licenseInfo->license_number;
        }else{
            $licenseImg = $dirUrl.'assets/img/license_place.jpg';
            $license_no = 'XXXXXXXXXX';
        }
        ?>
        <div class="col-md-4 col-xs-12"> 
            <div class="card"> 
                <div class="overlay">
                    <a href="javascript:;" onclick="return licenseUploadForm(<?= $value->license_id; ?>);" class="btn btn-primary btn-xs">Upload</a>
                </div>
                <img class="card-img-top" src="<?= $licenseImg; ?>"> 
                <div class="card-body text-center"> 
                    <p class="card-text"><?= $license_no; ?></p>
                    <h4 class="card-title"><?= $value->license_name; ?></h4> 
                </div>
            </div>
        </div>
    <?php
    }
}
?>