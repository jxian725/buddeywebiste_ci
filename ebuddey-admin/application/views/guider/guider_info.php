<?php
defined('BASEPATH') OR exit('No direct script access allowed');  
$assetUrl   = $this->config->item( 'admin_url' );
$site_name  = $this->config->item( 'site_name' );
$dirUrl     = $this->config->item( 'dir_url' );
$upload_path_url = $this->config->item( 'upload_path_url' );
?>
<link rel="stylesheet" href="<?= $dirUrl; ?>plugins/lightbox/css/lightbox.min.css">
<style type="text/css">
/* Create an active/current nav-tabslink class */
.tablinks a.active {
  background-color: #f4f4f4;
}
/*Rating And Review style */
.box.box-review{
   border-top-color: #ddd;
}
/*progress bar*/
progress {
    background: #EEE;
    border: none;
    width: 40%; height: 5px;
    box-shadow: 0 2px 3px rgba(0,0,0,0.2) inset;
    border-radius: 3px;
}

progress::-moz-progress-bar {
    background-color: #CC0000;
    border-radius: 3px;
}
/*license style*/
.card{
    position: relative;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-direction: column;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid rgba(0,0,0,.125);
    border-radius: .25rem;
    padding: 10px;
    margin: 10px;
}
.card-img-top {
    width: 100%;
    border-top-left-radius: calc(.25rem - 1px);
    border-top-right-radius: calc(.25rem - 1px);
    height: 300px;
}
.card-body {
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
    padding: 0.75rem;
}
.card-text{ font-weight: bold; }
.card .overlay{
    font-size: 18px;
    font-family: tahoma;
    margin-top: 0px;
    margin-right: 10px;
    position: absolute;
    top: 10px;
    right: 0;
}
/*license style*/
</style>
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header">
                <!-- Classic tabs -->
                <ul class="nav nav-tabs">
                    <li class="tablinks"><a href="javascript:;" class="tablinks" onclick="openTab(event, 'profileTab')" id="dflt_open"><?= HOST_NAME; ?> Profile</a></li>
                    <li class="tablinks"><a href="javascript:;" class="tablinks" onclick="openTab(event, 'ratingTab')" id="dflt_close1">Ratings and Reviews</a></li>
                    <li class="tablinks"><a href="javascript:;" class="tablinks" onclick="openTab(event, 'licenseTab')" id="dflt_close2">Verification</a></li>
                </ul>
                <div class="box-tools pull-right">
                    <a href="<?php echo $assetUrl; ?>guider" class="btn btn-sm btn-primary">Back</a>
                </div>
            </div>
            <div id="profileTab" class="tabcontent">       
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="col-md-10">
                                <h5 class="box-title"><b>Profile Image</b>&nbsp;&nbsp;<a href="javascript:;" title="Update Profile" onclick="return updateGuiderForm(<?= $guiderInfo->guider_id; ?>,'profile_image');"><i class="fa fa-edit"></i></a></h5>
                                <div class="img-view">
                                    <?php
                                    if($guiderInfo->profile_image){ 
                                        $profile_image = $upload_path_url.'g_profile/'.$guiderInfo->profile_image;
                                    ?>
                                        <a class="example-image-link" href="<?= $profile_image; ?>" data-lightbox="example-set">
                                            <img class="img-thumbnail" src="<?=$profile_image; ?>" id="client_picture" style="height: auto;width: 100%;" data-src="#" />
                                        </a>
                                    <?php } else { ?>
                                        <img class="img-thumbnail" src="<?=$dirUrl; ?>uploads/no_image.png" id="client_picture" style="height:100px;width: auto;" data-src="#" />
                                    <?php
                                    } 
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <h5 class="box-title">
                                    <b>ID Proof</b>&nbsp;&nbsp;
                                    <a href="javascript:;" title="Edit" onclick="return updateGuiderForm(<?= $guiderInfo->guider_id; ?>,'id_proof');"><i class="fa fa-edit"></i></a>
                                </h5>
                                <div class="img-view">
                                    <?php if($guiderInfo->id_proof){ 
                                        $id_proof = $upload_path_url.'identity/'.$guiderInfo->id_proof;
                                        ?>
                                        <a class="example-image-link" href="<?= $id_proof; ?>" data-lightbox="example-set">
                                            <img class="img-thumbnail" src="<?=$id_proof; ?>" id="client_picture" style="height: auto;width: 100%;" data-src="#" />
                                        </a>
                                    <?php } else { ?>
                                        <img class="img-thumbnail" src="<?=$dirUrl; ?>uploads/no_image.png" id="client_picture" style="height:100px;width: auto;" data-src="#" />
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <h5 class="box-title">
                                    <b>DBKL License</b>&nbsp;&nbsp;
                                    <a href="javascript:;" title="Edit" onclick="return updateGuiderForm(<?= $guiderInfo->guider_id; ?>,'dbkl_lic');"><i class="fa fa-edit"></i></a>
                                </h5>
                                <div class="img-view">
                                    <?php if($guiderInfo->dbkl_lic){ 
                                        $dbkl_lic = $upload_path_url.'dbkl/'.$guiderInfo->dbkl_lic;
                                        ?>
                                        <a class="example-image-link" href="<?= $dbkl_lic; ?>" data-lightbox="example-set">
                                            <img class="img-thumbnail" src="<?=$dbkl_lic; ?>" id="client_picture" style="height: auto;width: 100%;" data-src="#" />
                                        </a>
                                    <?php } else { ?>
                                        <img class="img-thumbnail" src="<?=$dirUrl; ?>uploads/no_image.png" id="client_picture" style="height:100px;width: auto;" data-src="#" />
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <h5 class="box-title"><b>Photo Activities</b>&nbsp;&nbsp;</h5>
                            <?php
                            $isServiceImg = 0;
                            if( $activityLists ) {
                                foreach( $activityLists as $key => $value ) {
                                    if($value->photo_1){
                                        $photo_1 = $value->photo_1;
                                        ?>
                                        <div class="col-md-10">
                                        <a class="example-image-link" href="<?= $photo_1; ?>" data-lightbox="example-set">
                                            <img class="img-thumbnail" src="<?=$photo_1; ?>" id="client_picture" style="height: auto;width: 100%;" data-src="#" />
                                        </a>
                                        </div>
                                        <?php
                                        $isServiceImg = 1;
                                    }
                                    if($value->photo_2){
                                        $photo_2 = $value->photo_2;
                                        ?>
                                        <div class="col-md-10">
                                        <a class="example-image-link" href="<?= $photo_2; ?>" data-lightbox="example-set">
                                            <img class="img-thumbnail" src="<?=$photo_2; ?>" id="client_picture" style="height: auto;width: 100%;" data-src="#" />
                                        </a>
                                        </div>
                                        <?php
                                        $isServiceImg = 1;
                                    }
                                    if($value->photo_3){
                                        $photo_3 = $value->photo_3;
                                        ?>
                                        <div class="col-md-10">
                                        <a class="example-image-link" href="<?= $photo_3; ?>" data-lightbox="example-set">
                                            <img class="img-thumbnail" src="<?=$photo_3; ?>" id="client_picture" style="height: auto;width: 100%;" data-src="#" />
                                        </a>
                                        </div>
                                        <?php
                                        $isServiceImg = 1;
                                    }
                                }
                            }
                            if($isServiceImg==0){
                                echo '<img class="img-thumbnail" src="'.$upload_path_url.'default_service.png" id="client_picture" style="height:100px;width: auto;" data-src="#" />';
                            }
                            ?>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label col-form-label-sm">Full Name&nbsp;&nbsp;
                                    <a href="javascript:;" title="Edit" onclick="return updateGuiderForm(<?= $guiderInfo->guider_id; ?>,'first_name');"><i class="fa fa-edit"></i></a>
                                </label>
                                <div class="col-sm-9">
                                    <div><?=$guiderInfo->first_name; ?></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Other Name&nbsp;&nbsp;
                                    <a href="javascript:;" title="Edit" onclick="return updateGuiderForm(<?= $guiderInfo->guider_id; ?>,'last_name');"><i class="fa fa-edit"></i></a>
                                </label>
                                <div class="col-sm-9">
                                    <div><?=$guiderInfo->last_name; ?></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">City&nbsp;&nbsp;
                                    <a href="javascript:;" title="Edit" onclick="return updateGuiderForm(<?= $guiderInfo->guider_id; ?>,'city');"><i class="fa fa-edit"></i></a>
                                </label>
                                <div class="col-sm-9">
                                    <div><?=$guiderInfo->cityName; ?></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Area&nbsp;&nbsp;
                                    <a href="javascript:;" title="Edit" onclick="return updateGuiderForm(<?= $guiderInfo->guider_id; ?>,'area');"><i class="fa fa-edit"></i></a>
                                </label>
                                <div class="col-sm-9">
                                    <div><?=$guiderInfo->area; ?></div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Email</label>
                                <div class="col-sm-9">
                                    <div><?=$guiderInfo->email; ?></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Phone Number&nbsp;&nbsp;
                                    <a href="javascript:;" title="Edit" onclick="return updateGuiderForm(<?= $guiderInfo->guider_id; ?>,'phone_number');"><i class="fa fa-edit"></i></a>
                                </label>
                                <div class="col-sm-9">
                                    <div><?=$guiderInfo->phone_number; ?></div> 
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Password&nbsp;&nbsp;
                                    <a href="javascript:;" title="Edit" onclick="return updateGuiderForm(<?= $guiderInfo->guider_id; ?>,'password');"><i class="fa fa-edit"></i></a>
                                </label>
                                <div class="col-sm-9">
                                    <div><?= ($guiderInfo->password)? $this->encryption->decrypt($guiderInfo->password) : '-'; ?></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">DOB&nbsp;&nbsp;
                                    <a href="javascript:;" title="Edit" onclick="return updateGuiderForm(<?= $guiderInfo->guider_id; ?>,'age');"><i class="fa fa-edit"></i></a>
                                </label>
                                <div class="col-sm-9">
                                    <div><?= ($guiderInfo->age != '0000-00-00')? date(getDateFormat(), strtotime($guiderInfo->age)) : 'n/a'; ?></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <?php
                                $lang = [];
                                if($guiderInfo->languages_known){
                                    $array  = explode(',', $guiderInfo->languages_known);
                                    foreach ($array as $item) {
                                        $langInfo = $this->Guidermodel->guiderLangInfo($item);
                                        if($langInfo){ $lang[] = $langInfo->language; }
                                    }
                                }
                                ?>
                                <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Languages Known&nbsp;&nbsp;
                                    <a href="javascript:;" title="Edit" onclick="return updateGuiderForm(<?= $guiderInfo->guider_id; ?>,'language');"><i class="fa fa-edit"></i></a>
                                </label>
                                <div class="col-sm-9">
                                    <div><?= implode(',', $lang); ?></div>
                                </div>
                            </div>
                            <!-- <div class="form-group row">
                                <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">DBKL license No&nbsp;&nbsp;
                                    <a href="javascript:;" title="Edit" onclick="return updateGuiderForm(<?= $guiderInfo->guider_id; ?>,'dbkl_lic_no');"><i class="fa fa-edit"></i></a>
                                </label>
                                <div class="col-sm-9">
                                    <div><?= ($guiderInfo->dbkl_lic_no)? $guiderInfo->dbkl_lic_no : '-'; ?></div>
                                </div>
                            </div> -->
                            <div class="form-group row">
                                <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">NRIC Number&nbsp;&nbsp;
                                    <a href="javascript:;" title="Edit" onclick="return updateGuiderForm(<?= $guiderInfo->guider_id; ?>,'nric_number');"><i class="fa fa-edit"></i></a>
                                </label>
                                <div class="col-sm-9">
                                    <div><?= ($guiderInfo->nric_number)? $guiderInfo->nric_number : '-'; ?></div>
                                </div>
                            </div>
                            <!-- <div class="form-group row">
                                <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Ratings</label>
                                <div class="col-sm-9">
                                    <div><?= ($guiderInfo->rating)? $guiderInfo->rating : '-'; ?></div>
                                </div>
                            </div> -->

                            <!-- <div class="form-group row">
                                <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Gender&nbsp;&nbsp;
                                    <a href="javascript:;" title="Edit" onclick="return updateGuiderForm(<?= $guiderInfo->guider_id; ?>,'gender');"><i class="fa fa-edit"></i></a>
                                </label>
                                <div class="col-sm-9">
                                    <?php if($guiderInfo->gender == 1){ ?>
                                        <div>Male</div>
                                    <?php }else if($guiderInfo->gender == 2){ ?>
                                        <div>Female</div>
                                    <?php }else{ ?>
                                         <div>N/A</div>
                                    <?php } ?>
                                </div>
                            </div> -->
                            <div class="form-group row">
                                <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Skills category&nbsp;&nbsp;
                                    <a href="javascript:;" title="Edit" onclick="return updateGuiderForm(<?= $guiderInfo->guider_id; ?>,'skills_category');"><i class="fa fa-edit"></i></a>
                                </label>
                                <div class="col-sm-9">
                                    <div><?=$guiderInfo->categoryName; ?></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Sub skills&nbsp;&nbsp;
                                    <a href="javascript:;" title="Edit" onclick="return updateGuiderForm(<?= $guiderInfo->guider_id; ?>,'sub_skills');"><i class="fa fa-edit"></i></a>
                                </label>
                                <div class="col-sm-9">
                                    <div><?=$guiderInfo->sub_skills; ?></div>
                                </div>
                            </div>
                            <!-- <div class="form-group row">
                                <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Hire me&nbsp;&nbsp;
                                    <a href="javascript:;" title="Edit" onclick="return updateGuiderForm(<?= $guiderInfo->guider_id; ?>,'gigs_amount');"><i class="fa fa-edit"></i></a>
                                </label>
                                <div class="col-sm-9">
                                    <div><?=$guiderInfo->gigs_amount; ?></div>
                                </div>
                            </div> -->

                            <div class="form-group row">
                                <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Bank Account Name&nbsp;&nbsp;
                                    <a href="javascript:;" title="Edit" onclick="return updateGuiderForm(<?= $guiderInfo->guider_id; ?>,'acc_name');"><i class="fa fa-edit"></i></a></label>
                                <div class="col-sm-9">
                                    <div><?= ($guiderInfo->acc_name)? $guiderInfo->acc_name : '-'; ?></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Bank Name&nbsp;&nbsp;
                                    <a href="javascript:;" title="Edit" onclick="return updateGuiderForm(<?= $guiderInfo->guider_id; ?>,'bank_name');"><i class="fa fa-edit"></i></a></label>
                                <div class="col-sm-9">
                                    <div><?= ($guiderInfo->bank_name)? $guiderInfo->bank_name : '-'; ?></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Bank Account Number&nbsp;&nbsp;
                                    <a href="javascript:;" title="Edit" onclick="return updateGuiderForm(<?= $guiderInfo->guider_id; ?>,'acc_no');"><i class="fa fa-edit"></i></a></label>
                                <div class="col-sm-9">
                                    <div><?= ($guiderInfo->acc_no)? $guiderInfo->acc_no : '-'; ?></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">About Me&nbsp;&nbsp;
                                    <a href="javascript:;" title="Edit" onclick="return updateGuiderForm(<?= $guiderInfo->guider_id; ?>,'about');"><i class="fa fa-edit"></i></a>
                                </label>
                                <div class="col-sm-9">
                                    <div><?= ($guiderInfo->about_me)? $guiderInfo->about_me : '-'; ?></div>
                                </div>
                            </div>
							<div class="form-group row">
                                <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Date Of Account Register&nbsp;&nbsp;
                                   
                                </label>
                                <div class="col-sm-9">
                                    <div><?= ($guiderInfo->created_on != '0000-00-00')? date(getDateFormat(), strtotime($guiderInfo->created_on)) : 'n/a'; ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 pull-right">
                            <a class="btn btn-success btn-xs pull-right" href="javascript:;" data-toggle="tooltip" data-original-title="Add" onclick="return updateHostActivityForm(0, <?= $guiderInfo->guider_id; ?>);"><i class="fa fa-plus"></i> Add Activity</a>
                        </div>
                        <div class="col-md-12">
                            <div class="box-body table-responsive no-padding">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>SI No</th>
                                            <th>Pricing Type</th>
                                            <th>Price</th>
                                            <th>Category</th>
                                            <th>Service Region</th>
                                            <th>cancellation Policy</th>
                                            <th>What I Offer</th>
                                            <th>Activity Images</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="activity_lists">
                                    <?php
                                    $i = 0;
                                    if( $activityLists ) {
                                        foreach( $activityLists as $key => $value ) {
                                            $i++;
                                            if($value->processingFeesType == 1){
                                                $processingFeesType = ' (Default)';
                                            }elseif($value->processingFeesType == 2){
                                                $processingFeesType = ' (Percentage)';
                                            }elseif($value->processingFeesType == 3){
                                                $processingFeesType = ' (Fixed)';
                                            }else{
                                                $processingFeesType = '';
                                            }
                                            if($value->price_type_id == 1){
                                                $type = 'Pricing Per Person';
                                            }elseif($value->price_type_id == 2){
                                                $type = 'Pricing Per Booking';
                                            }elseif($value->price_type_id == 3){
                                                $type = 'Free Booking';
                                            }else{
                                                $type = '';
                                            }
                                            $spec = [];
                                            if($value->guiding_speciality){
                                                $array  = explode(',', $value->guiding_speciality);
                                                foreach ($array as $item) {
                                                    $specInfo = $this->Guidermodel->guiderSpecialityInfo($item);
                                                    if($specInfo){ $spec[] = urldecode($specInfo->specialization); }
                                                }
                                            }
                                            $regionInfo     = $this->Guidermodel->stateInfoByid($value->service_providing_region);
                                            if($regionInfo){
                                                $regionName = $regionInfo->name;
                                            }else{
                                                $regionName = '';
                                            }
                                            if($value->activity_status == 1){
                                                $status     = '<span class="label label-success">Active</span>';
                                                $statusbtn  = '<a href="javascript:;" onClick="return guiderActivityStatus('.$value->activity_id.', 2);" class="btn btn-success btn-xs" data-toggle="tooltip" data-original-title="Click to Inactive"><i class="fa fa-toggle-off" aria-hidden="true"></i></a>';
                                            }else if($value->activity_status == 2){
                                                $status     = '<span class="label label-danger">Inactive</span>';
                                                $statusbtn  = '<a href="javascript:;" onClick="return guiderActivityStatus('.$value->activity_id.', 1);" class="btn btn-danger btn-xs" data-toggle="tooltip" data-original-title="Click to Active"><i class="fa fa-toggle-on" aria-hidden="true"></i></a>';
                                            }
                                            ?>
                                            <tr>
                                                <td><?= $i; ?></td>
                                                <td><?= $type .$processingFeesType; ?></td>
                                                <td><?= ($value->rate_per_person)? number_format( $value->rate_per_person, 2 ) : 'n/a'; ?></td>
                                                <td><?= implode(',', $spec); ?></td>
                                                <td><?= $regionName; ?></td>
                                                <td><?=mb_substr($value->cancellation_policy, 0, 25);?>..</td>
                                                <td><?= nl2br(htmlentities(mb_substr($value->what_i_offer, 0, 25))); ?>..</td>
                                                <td>
                                                    <?php
                                                    if($value->photo_1){ 
                                                    $photo_1 = $value->photo_1;
                                                    ?>
                                                    <a class="example-image-link" href="<?= $photo_1; ?>" data-lightbox="example-set">
                                                        <img class="img-thumbnail" src="<?=$photo_1; ?>" id="client_picture" style="height: auto;width: 45px;" data-src="#" />
                                                    </a>
                                                    <?php 
                                                    }
                                                    if($value->photo_2){ 
                                                    $photo_2 = $value->photo_2;
                                                    ?>
                                                    <a class="example-image-link" href="<?= $photo_2; ?>" data-lightbox="example-set">
                                                        <img class="img-thumbnail" src="<?=$photo_2; ?>" id="client_picture" style="height: auto;width: 45px;" data-src="#" />
                                                    </a>
                                                    <?php 
                                                    }
                                                    if($value->photo_3){ 
                                                    $photo_3 = $value->photo_3;
                                                    ?>
                                                    <a class="example-image-link" href="<?= $photo_3; ?>" data-lightbox="example-set">
                                                        <img class="img-thumbnail" src="<?=$photo_3; ?>" id="client_picture" style="height: auto;width: 45px;" data-src="#" />
                                                    </a>
                                                    <?php 
                                                    }
                                                    ?>
                                                </td>
                                                <td><?= $status; ?></td>
                                                <td>
                                                    <?= $statusbtn; ?>
                                                    <a class="btn btn-warning btn-xs" href="javascript:;" title="Edit" onclick="return updateHostActivityForm(<?= $value->activity_id; ?>,<?= $value->activity_guider_id; ?>);"><i class="fa fa-edit"></i></a>
                                                    <?php if($value->price_type_id != 3){ ?>
                                                        <a href="javascript:;" onClick="return hostActivityPricing(<?= $value->activity_id; ?>, <?= $value->price_type_id; ?>);" class="btn btn-primary btn-xs" data-toggle="tooltip" data-original-title="Click to Setup processing fee"><i class="fa fa-gear" aria-hidden="true"></i></a>
                                                    <?php } ?>
                                                    <a href="javascript:;" onClick="return guiderActivityStatus(<?= $value->activity_id; ?>, 4);" class="btn btn-danger btn-xs" data-toggle="tooltip" data-original-title="Click to Delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }else{ ?>
                                        <tr><td colspan="10">No data found</td></tr>
                                    <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>     
                </div>
            </div>
            <div id="ratingTab" class="tabcontent">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-10">
                                    <h4 class="box-title">Ratings</h4>
                                    <div class="form-group">
                                        <?php $total_Like = ($totalLike->is_Like + $totalLike->dis_Like);?>
                                        <a class="btn btn-success btn-xs" title="Like"><i class="fa fa-thumbs-up"></i></a>
                                        <progress max="<?= $total_Like; ?>" value="<?= $totalLike->is_Like; ?>" class="html5">
                                            <progress class="progress-bar"></progress>
                                        </progress>
                                        <span><?= $totalLike->is_Like; ?> Like</span>
                                    </div> 
                                    <div class="form-group">
                                        <a class="btn btn-danger btn-xs" title="Dislike"><i class="fa fa-thumbs-down"></i></a>
                                        <progress max="<?= $total_Like; ?>" value="<?= $totalLike->dis_Like; ?>" class="html5">
                                            <progress class="progress-bar"></progress>
                                        </progress>
                                        <span><?= $totalLike->dis_Like; ?> Dislike</span>
                                    </div>     
                                </div>
                                <div class="clearfix"></div>

                                <!-- Reviews   -->
                                <div class="col-md-12">
                                    <div class="box box-review">
                                        <div class="box-header">
                                            <h4 class="box-title">Reviews</h4> 
                                            <div class="box-body">
                                                <div class="row"> 
                                                    <?php 
                                                    if ($reviewList) {
                                                        foreach ($reviewList as $reviewInfo) { 
                                                            $disLikeDate = (date('d M Y',strtotime($reviewInfo->disLike_date )));
                                                            $likeDate    = (date('d M Y',strtotime($reviewInfo->like_date )));
                                                            ?>
                                                   
                                                            <div class="form-group">
                                                                <?php  if($reviewInfo->is_like == 1){ ?>   
                                                                    <a class="btn btn-success btn-xs" title="Like"><i class="fa fa-thumbs-up"></i></a>
                                                                <?php }else{ ?> 
                                                                    <a class="btn btn-danger btn-xs" title="Dislike"><i class="fa fa-thumbs-down"></i></a>
                                                                <?php } ?>
                                                            </div>
                                                            <div class="form-group">     
                                                                <span><?= $reviewInfo->company_name; ?></span>
                                                                <div class="form-group">
                                                                    <?php  if($reviewInfo->is_like == 1){ ?>   
                                                                        <span><?= $likeDate; ?></span>
                                                                    <?php }else{ ?> 
                                                                        <span><?= $disLikeDate; ?></span>
                                                                    <?php } ?>    
                                                                    <div class="form-group">
                                                                        <span><?= $reviewInfo->command; ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php
                                                        }
                                                    }else{ ?>
                                                        <center><span class="label label-primary">No Reviews Found</span></center> 
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>       
                                        </div>    
                                    </div>         
                                </div>
                                <!-- End Reviews   -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- start license tab -->
            <div id="licenseTab" class="tabcontent">
                <div class="row">
                    <div class="col-md-12"> 
                        <div id="license_data">
                            <?php
                            if($talentLicenseList){
                                foreach ($talentLicenseList as $key => $value) {
                                    if($value->license_image){
                                        $licenseImg = $dirUrl.'uploads/license/'.$value->license_image;
                                    }else{
                                        $licenseImg = $dirUrl.'uploads/no_image.png';
                                    }
                                    ?>
                                    <div class="col-md-4 col-xs-12">
                                        <div class="card"> 
                                            <div class="overlay">
                                            </div>
                                            <a class="example-image-link" href="<?= $licenseImg; ?>" data-lightbox="example-set">
                                                <img class="card-img-top" src="<?= $licenseImg; ?>" id="license_img_<?= $value->license_id; ?>">
                                            </a>
                                            <div class="card-body text-center"> 
                                                <p class="card-text" id="license_no_5"><?= $value->license_number; ?></p>
                                                <h4 class="card-title"><?= $value->license_name; ?></h4> 
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                            }else{
                                echo '<div class="col-md-12 col-xs-12"><p>No data found</p></div>';
                            }
                            ?>
                            
                        </div> 
                    </div>
                </div>
            </div>
            <!-- close license tab -->
        </div>
    </div>
</div>
<script src="<?= $dirUrl; ?>plugins/lightbox/js/lightbox-plus-jquery.min.js"></script>
<script type="text/javascript">
function updateGuiderForm( guider_id, field ) {
    $( '#myModal' ).modal( 'show' );
    $( '#myModal .modal-body' ).html('<img src="<?=$dirUrl; ?>img/ajax-loader.gif" style="display: block; margin: 0 auto; width: 100px;" alt="loading..."/>');
    var title = '';
    if(field == 'about'){
        title = 'About Us';
    }else if(field == 'policy'){
        title = 'Cancellation Policy';
    }else if(field == 'city'){
        title = 'City';
    }else if(field == 'area'){
        title = 'Area';    
    }else if(field == 'gender'){
        title = 'Gender';   
    }else if(field == 'sub_skills'){
        title = 'Sub skills';   
    }else if(field == 'skills_category'){
        title = 'Skills category';   
    }else if(field == 'gigs_amount'){
        title = 'Hire me';
    }else if(field == 'acc_name'){
        title = 'Bank account name'; 
    }else if(field == 'acc_no'){
        title = 'Bank account number'; 
    }else if(field == 'bank_name'){
        title = 'Bank name';                                
    }else if(field == 'first_name'){
        title = 'Other Name';
    }else if(field == 'last_name'){
        title = 'Other Name';
    }else if(field == 'age'){
        title = 'Date Of Birth';
    }else if(field == 'id_proof'){
        title = 'ID Proof';
    }else if(field == 'profile_image'){
        title = 'Profile Image';
    }else if(field == 'region'){
        title = 'Service Region';
    }else if(field == 'category'){
        title = 'Category';
    }else if(field == 'offer'){
        title = 'What I Offer';
    }else if(field == 'password'){
        title = 'Change Password';
    }else if(field == 'dbkl_lic_no'){
        title = 'DBKL license No';
    }else if(field == 'dbkl_lic'){
        title = 'DBKL license';
    }else if(field == 'nric_number'){
        title = 'NRIC Number';
    }else if(field == 'phone_number'){
        title = 'Phone Number';
    }
    $('#myModal .modal-title').html( title );
    var data = 'guider_id='+guider_id+'&field='+field;
    $.ajax( {
        type: "POST",
        data: data,
        url: adminurl + 'guider/updateGuiderForm',
        success: function( msg ) {
            $( '#myModal .modal-body' ).html(msg);
            $( '#myModal .modal-footer' ).html('');
        }
    });
    return false;
}
function updateHostActivityForm( activity_id, guider_id ) {
    $( '#myModal' ).modal( 'show' );
    $( '#myModal .modal-body' ).html('<img src="<?=$dirUrl; ?>img/ajax-loader.gif" style="display: block; margin: 0 auto; width: 100px;" alt="loading..."/>');
    $('#myModal .modal-title').html('Update Host Activity');
    var data = 'activity_id='+activity_id+'&guider_id='+guider_id;
    $.ajax( {
        type: "POST",
        data: data,
        url: adminurl + 'guider/updateHostActivityForm',
        success: function( msg ) {
            $( '#myModal .modal-body' ).html(msg);
            $( '#myModal .modal-footer' ).html('');
        }
    });
    return false;
}
function guiderActivityStatus( activity_id,status ) {
    $( '#myModal .modal-title' ).html( 'Confirm' );
    if(status == 1){
        $( '#myModal .modal-body' ).html( 'Are you sure want to Activate this Activity ?' );
    }else if(status == 4){
        $( '#myModal .modal-body' ).html( 'Are you sure want to Delete this Activity ?' );
    }else{
        $( '#myModal .modal-body' ).html( 'Are you sure want to Deactive this Activity ?' );
    }
    $( '#myModal .modal-body' ).append( '<br /><br /><button class="btn btn-info btn-sm" id="continuemodal" data-dismiss="modal">Yes</button>&nbsp;&nbsp;<button aria-hidden="true" data-dismiss="modal" class="btn btn-danger btn-sm">Cancel</button>' );
    $( '#myModal' ).modal({ backdrop: 'static', keyboard: false }); 
    $( "#continuemodal" ).click(function() {
    var data = { 'activity_id':activity_id,'status':status }
    $.ajax({
        type: "POST",
        url: adminurl + 'guider/guiderActivityStatus',
        data: data,
        success: function( data ) {
            if(status == 4){
                toastr.success( 'Guider Activity deleted Successfully.','Success' );
            }else{
                toastr.success( 'Guider Activity Status Updated Successfully.','Success' );
            }
            setTimeout( function() {
            location.reload();
            }, 2000 );
        }
        });
    });    
    return false;
}
function hostActivityPricing( activity_id, price_type_id ) {
    $( '#myModal' ).modal( 'show' );
    $( '#myModal .modal-body' ).html('<img src="<?=$dirUrl; ?>img/ajax-loader.gif" style="display: block; margin: 0 auto; width: 100px;" alt="loading..."/>');
    $('#myModal .modal-title').html('Price Setting');
    var data = 'activity_id='+activity_id+'&price_type_id='+price_type_id;
    $.ajax( {
        type: "POST",
        data: data,
        url: adminurl + 'guider/hostActivityPricing',
        success: function( msg ) {
            $( '#myModal .modal-body' ).html(msg);
            $( '#myModal .modal-footer' ).html('');
        }
    });
    return false;
}
</script>
<script type="text/javascript">
function openTab(evt, tabID) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabID).style.display = "block";
    evt.currentTarget.className += " active";
}
document.getElementById("dflt_open").click();
</script>
