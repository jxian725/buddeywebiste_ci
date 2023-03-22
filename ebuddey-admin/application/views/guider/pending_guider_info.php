<?php
defined('BASEPATH') OR exit('No direct script access allowed');  
$assetUrl   = $this->config->item( 'admin_url' );
$site_name  = $this->config->item( 'site_name' );
$dirUrl     = $this->config->item( 'dir_url' );
$upload_path_url = $this->config->item( 'upload_path_url' );
$name       = $guiderInfo->first_name;
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
</style>
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header">
                <!-- Classic tabs -->
                <ul class="nav nav-tabs">
                    <li class="tablinks"><a href="javascript:;" class="tablinks active" id="defaultOpen"><?= HOST_NAME; ?> Profile</a></li>
                </ul>
                <div class="box-tools pull-right">
                    <a href="<?php echo $assetUrl; ?>pendingguider" class="btn btn-sm btn-primary">Back</a>
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
                        </div>
                    </div>
                    <div class="clearfix"></div>     
                </div>
            </div>
            
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
