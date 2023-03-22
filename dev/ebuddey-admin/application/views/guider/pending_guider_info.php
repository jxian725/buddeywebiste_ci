<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl   = $this->config->item( 'admin_url' );
$site_name  = $this->config->item( 'site_name' );
$dirUrl     = $this->config->item( 'dir_url' );
$upload_path_url = $this->config->item( 'upload_path_url' );
$name       = $guiderInfo->first_name;
?>
<link rel="stylesheet" href="<?= $dirUrl; ?>plugins/lightbox/css/lightbox.min.css">
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header">
                <div class="box-tools pull-right">
                    <a href="<?php echo $assetUrl; ?>pendingguider" class="btn btn-sm btn-primary">Back</a>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="col-md-10">
                            <h5 class="box-title"><b>Profile Image</b></h5>
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
                            <h5 class="box-title"><b>ID Proof</b></h5>
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
                            <h5 class="box-title"><b>DBKL License </b></h5>
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
                            <label class="col-sm-3 col-form-label col-form-label-sm">Full Name</label>
                            <div class="col-sm-9">
                                <div><?=$guiderInfo->first_name; ?></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Other Name</label>
                            <div class="col-sm-9">
                                <div><?=$guiderInfo->last_name; ?></div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Email</label>
                            <div class="col-sm-9">
                                <div><?=$guiderInfo->email; ?></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Phone Number</label>
                            <div class="col-sm-9">
                                <div><?=$guiderInfo->phone_number; ?></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Password</label>
                            <div class="col-sm-9">
                                <div><?= ($guiderInfo->password)? $this->encryption->decrypt($guiderInfo->password) : '-'; ?></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">DOB</label>
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
                            <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Languages Known</label>
                            <div class="col-sm-9">
                                <div><?= implode(',', $lang); ?></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">DBKL license No</label>
                            <div class="col-sm-9">
                                <div><?= ($guiderInfo->dbkl_lic_no)? $guiderInfo->dbkl_lic_no : '-'; ?></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">NRIC Number</label>
                            <div class="col-sm-9">
                                <div><?= ($guiderInfo->nric_number)? $guiderInfo->nric_number : '-'; ?></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Ratings</label>
                            <div class="col-sm-9">
                                <div><?= ($guiderInfo->rating)? $guiderInfo->rating : '-'; ?></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Bank Account Name</label>
                            <div class="col-sm-9">
                                <div><?= ($guiderInfo->acc_name)? $guiderInfo->acc_name : '-'; ?></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Bank Name</label>
                            <div class="col-sm-9">
                                <div><?= ($guiderInfo->bank_name)? $guiderInfo->bank_name : '-'; ?></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Bank Account Number</label>
                            <div class="col-sm-9">
                                <div><?= ($guiderInfo->acc_no)? $guiderInfo->acc_no : '-'; ?></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">About Me</label>
                            <div class="col-sm-9">
                                <div><?= ($guiderInfo->about_me)? $guiderInfo->about_me : '-'; ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
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
                                        if($value->price_type_id == 1){
                                            $type = 'Pricing Per Person';
                                        }elseif($value->price_type_id == 2){
                                            $type = 'Pricing Per Booking';
                                        }elseif($value->price_type_id == 3){
                                            $type = 'Free Booking';
                                        }
                                        $spec = [];
                                        if($value->guiding_speciality){
                                            $array  = explode(',', $value->guiding_speciality);
                                            foreach ($array as $item) {
                                                $specInfo = $this->Guidermodel->guiderSpecialityInfo($item);
                                                if($specInfo){ $spec[] = $specInfo->specialization; }
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
                                            <td><?= $type; ?></td>
                                            <td><?= ($value->rate_per_person)? number_format( $value->rate_per_person, 2 ) : 'n/a'; ?></td>
                                            <td><?= implode(',', $spec); ?></td>
                                            <td><?= $regionName; ?></td>
                                            <td><?= $value->cancellation_policy; ?></td>
                                            <td><?= nl2br(htmlentities($value->what_i_offer)); ?></td>
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
                                                <a class="btn btn-warning btn-xs" href="javascript:;" title="Edit" onclick="return updateGuiderActivityForm(<?= $value->activity_id; ?>,<?= $value->activity_guider_id; ?>);"><i class="fa fa-edit"></i></a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
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
    </div>
</div>
<script src="<?= $dirUrl; ?>plugins/lightbox/js/lightbox-plus-jquery.min.js"></script>