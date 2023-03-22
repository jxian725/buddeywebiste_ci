<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl   = $this->config->item( 'admin_url' );
$site_name  = $this->config->item( 'site_name' );
$dirUrl     = $this->config->item( 'dir_url' );
$upload_path_url = $this->config->item( 'upload_path_url' );
$name       = $travellerInfo->first_name;
?>
<link rel="stylesheet" href="<?= $dirUrl; ?>plugins/lightbox/css/lightbox.min.css">
<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header">
        <div class="box-tools pull-right">
          <a href="<?php echo $assetUrl; ?>traveller" class="btn btn-sm btn-primary">Back</a>
        </div>
      </div>
      <div class="box-body">
        <div class="row">
            <div class="col-md-3">
                <div class="col-md-10">
                  <h5 class="box-title"><b>Profile Image</b></h5>
                  <div style="width:100px; margin: 0 auto;">
                      <div class="img-view">
                          <?php if($travellerInfo->profile_image){ 
                            $profile_image = $upload_path_url.'t_profile/'.$travellerInfo->profile_image;
                            ?>
                              <a class="example-image-link" href="<?= $profile_image; ?>" data-lightbox="example-set">
                                  <img class="img-thumbnail" src="<?=$profile_image; ?>" id="client_picture" style="height: auto;width: 100%;" data-src="#" />
                              </a>
                          <?php } else { ?>
                              <img class="img-thumbnail" src="<?=$dirUrl; ?>uploads/no_image.png" id="client_picture" style="height:100px;width: auto;" data-src="#" />
                          <?php } ?>
                      </div>
                  </div>
                </div>
                <div class="col-md-10">
                    <h5 class="box-title"><b>Photo Activities</b></h5>
                    <div style="width: 100px; margin: 0 auto;">
                        <div class="img-view">
                          <?php
                          if($travellerInfo->photo){ 
                            $photo = $upload_path_url.'t_activity/'.$travellerInfo->photo;
                            ?>
                              <a class="example-image-link" href="<?= $photo; ?>" data-lightbox="example-set">
                                  <img class="img-thumbnail" src="<?=$photo; ?>" id="client_picture" style="height: auto;width: 100%;" data-src="#" />
                              </a>
                          <?php } else { ?>
                              <img class="img-thumbnail" src="<?=$dirUrl; ?>uploads/no_image.png" id="client_picture" style="height:100px;width: auto;" data-src="#" />
                          <?php
                          } 
                          ?>
                        </div>
                    </div>    
                </div>
                <div class="col-md-10">
                    <h5 class="box-title" style="padding-top: 10px;"></h5>
                    <div style="width: 100px; margin: 0 auto;">
                        <div class="img-view">
                          <?php if($travellerInfo->photo1){ 
                            $photo1 = $upload_path_url.'t_activity/'.$travellerInfo->photo1;
                            ?>
                              <a class="example-image-link" href="<?= $photo1; ?>" data-lightbox="example-set">
                                  <img class="img-thumbnail" src="<?= $photo1; ?>" id="client_picture" style="height: auto;width: 100%;" data-src="#" />
                              </a>
                          <?php } else { ?>
                              <img class="img-thumbnail" src="<?=$dirUrl; ?>uploads/no_image.png" id="client_picture" style="height:100px;width: auto;" data-src="#" />
                          <?php } ?>
                        </div>
                    </div>    
                </div>
                <div class="col-md-10">
                    <h5 class="box-title" style="padding-top: 10px;"></h5>
                    <div style="width: 100px; margin: 0 auto;">
                        <div class="img-view">
                          <?php if($travellerInfo->photo2){ 
                            $photo2 = $upload_path_url.'t_activity/'.$travellerInfo->photo2;
                            ?>
                              <a class="example-image-link" href="<?= $photo2; ?>" data-lightbox="example-set">
                                  <img class="img-thumbnail" src="<?= $photo2; ?>" id="client_picture" style="height: auto;width: 100%;" data-src="#" />
                              </a>
                          <?php } else { ?>
                              <img class="img-thumbnail" src="<?=$dirUrl; ?>uploads/no_image.png" id="client_picture" style="height:100px;width: auto;" data-src="#" />
                          <?php } ?>
                        </div>
                    </div>    
                </div>
            </div>
            <div class="col-md-9">
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label col-form-label-sm">First Name</label>
                    <div class="col-sm-9">
                        <div><?=$travellerInfo->first_name; ?></div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label col-form-label-sm">Last Name</label>
                    <div class="col-sm-9">
                        <div><?=$travellerInfo->last_name; ?></div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label col-form-label-sm">Mobile Number</label>
                    <div class="col-sm-9">
                        <div><?=$travellerInfo->mobile; ?></div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label col-form-label-sm">Phone Number</label>
                    <div class="col-sm-9">
                        <div><?=$travellerInfo->phone_number; ?></div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label col-form-label-sm">Region</label>
                    <div class="col-sm-9">
                        <div><?=$travellerInfo->city; ?></div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label col-form-label-sm">DOB</label>
                    <div class="col-sm-9">
                        <div>
                            <?php
                            if($travellerInfo->age != '0000-00-00'){
                              echo date(getDateFormat(), strtotime($travellerInfo->age)); 
                            }else{
                              echo '--';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label col-form-label-sm">Email</label>
                    <div class="col-sm-9">
                        <div><?=$travellerInfo->email; ?></div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label col-form-label-sm">Languages Known</label>
                    <div class="col-sm-9">
                        <?php
                        $lang = [];
                        if($travellerInfo->languages_known){
                            $array  = explode(',', $travellerInfo->languages_known);
                            foreach ($array as $item) {
                                $langInfo = $this->Travellermodel->travellerLangInfo($item);
                                if($langInfo){ $lang[] = $langInfo->language; }
                            }
                        }
                        ?>
                        <div><?= implode(',', $lang); ?></div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label col-form-label-sm">About Me</label>
                    <div class="col-sm-9">
                        <div><?=$travellerInfo->about_me; ?></div>
                    </div>
                </div>
            </div>

        </div>
        <div class="clearfix"></div>
    </div>
  </div>
</div>
<script src="<?= $dirUrl; ?>plugins/lightbox/js/lightbox-plus-jquery.min.js"></script>