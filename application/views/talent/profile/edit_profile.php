<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 
$adminUrl   = $this->config->item( 'admin_dir_url' );
$dirUrl     = $this->config->item( 'dir_url' );
?>
<style type="text/css">
/* Create an active/current nav-tabslink class */
.tablinks a.active {
  background-color: #f4f4f4;
  color: #2ECCFA;
}
.tablinks{
    font-size: 18px;
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
.fileuploadbtn {
    position: absolute;
    font-size: 29px;
    height: 32px;
    opacity: 0;
    right: 0;
    top: 0;
}
h3{ font-size: 18px; }
.box.box-primary {
    border-top-color: #ffffff;
}
.btn-default.btn-on-1.active{background-color: #4da75b;color: white;}
.btn-default.btn-off-1.active{background-color: #DA4F49;color: white;}
</style>

<div class="count_box row">
    <div class="col-md-12">
        <div class="box-header with-border">
            <h2 class="box-title">Profile - Status :  
			<?php 
			
			if(trim($talentInfo->strength)=="Good")
			{?>
				<span style="color:#00a65a">
			<?php
			echo $talentInfo->strength;
			?>
			</span>
			<?php } else if(trim($talentInfo->strength)=="Alert")
			{?>	
			<span style="color:#f39c12">
			<?php
			echo $talentInfo->strength;
			?>
			</span>
			<?php } else if(trim($talentInfo->strength)=="Suspended")
			{?>				
			<span style="color:#dd4b39;">
			<?php
			echo $talentInfo->strength;
			?>
			</span>
			<?php 
			}
			?>											
														</h2>
            <div class="box-tools pull-right">
                <a href="<?= base_url().'talent/profile'; ?>" class="btn btn-info btn-sm">Back to Account</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="col-xl-12 col-sm-12" style="padding-left: 0px;">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <div id="message_profile"></div>
                    <?php
                    if($talentInfo->profile_image){
                        $profileImg = $adminUrl.'uploads/g_profile/'.$talentInfo->profile_image;
                    }else{
                        $profileImg = $adminUrl.'uploads/default_img.png';
                    }
                    ?>
                    <div class="form-group">
                        <center><img src="<?php echo $profileImg; ?>" class="pull-center profileImgPlace" id="profileImgPlace" style="height:270px;width: 270px;cursor: copy;" data-src="#" data-toggle="tooltip" data-toggle="tooltip" title="Click to upload Profile Image" />
                        <input type="file" name="profileImg" id="profileImg" class="placeImg" style="display: none;" accept="image/*" /></center>
                    </div>
                
                    <div class="form-group">
                        <a href="javascript:;" class="btn btn-primary btn-sm  pull-right" style="height: 30px;" onclick="return addMystory();">Edit</a>
                    </div>
                    <div class="form-group">
                        <h4>About me</h4>
                        <div class="text">
                            <p><?= $talentInfo->about_me; ?></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <b>Add Social network and link</b> 
                        <a href="javascript:;" class="btn btn-primary btn-sm  pull-right" style="height: 30px;" onclick="return addSocialLink();">Edit</a>
                    </div>
                    <div class="form-group">
                        <ul class="list-group list-group-unbordered">
                            <?php
                            if($socialLinkInfo){ ?>
                            <li class="list-group-item">
                              <a target="_blank" href="<?= $socialLinkInfo->website_link; ?>"><img src="<?= $dirUrl; ?>assets/img/social/website.png" class="pull-center" style="height:24px;" /> <?= $socialLinkInfo->website_link; ?></a>
                            </li>
                            <li class="list-group-item">
                              <a target="_blank" href="<?= $socialLinkInfo->fb_link; ?>"><img src="<?= $dirUrl; ?>assets/img/social/facebook.png" class="pull-center" style="height:24px;" /> <?= $socialLinkInfo->fb_link; ?></a>
                            </li>
                            <li class="list-group-item">
                              <a target="_blank" href="<?= $socialLinkInfo->twitter_link; ?>"><img src="<?= $dirUrl; ?>assets/img/social/twitter.png" class="pull-center" style="height:24px;" /> <?= $socialLinkInfo->twitter_link; ?></a>
                            </li>
                            <li class="list-group-item">
                              <a target="_blank" href="<?= $socialLinkInfo->gplus_link; ?>"><img src="<?= $dirUrl; ?>assets/img/social/google-plus.png" class="pull-center" style="height:24px;" /> <?= $socialLinkInfo->gplus_link; ?></a>
                            </li>
                            <li class="list-group-item">
                              <a target="_blank" href="<?= $socialLinkInfo->behance_link; ?>"><img src="<?= $dirUrl; ?>assets/img/social/behance.png" class="pull-center" style="height:24px;" /> <?= $socialLinkInfo->behance_link; ?></a>
                            </li>
                            <li class="list-group-item">
                              <a target="_blank" href="<?= $socialLinkInfo->pinterest_link; ?>"><img src="<?= $dirUrl; ?>assets/img/social/pinterest.png" class="pull-center" style="height:24px;" /> <?= $socialLinkInfo->pinterest_link; ?></a>
                            </li>
                            <li class="list-group-item">
                              <a target="_blank" href="<?= $socialLinkInfo->instagram_link; ?>"><img src="<?= $dirUrl; ?>assets/img/social/instagram.png" class="pull-center" style="height:24px;" /> <?= $socialLinkInfo->instagram_link; ?></a>
                            </li>
                            <li class="list-group-item">
                              <a target="_blank" href="<?= $socialLinkInfo->youtube_link; ?>"><img src="<?= $dirUrl; ?>assets/img/social/youtube.png" class="pull-center" style="height:24px;" /> <?= $socialLinkInfo->youtube_link; ?></a>
                            </li>
                            <?php } ?>
                        </ul>
                         
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="box box-primary">
            
            <div class="tabcontent">       
                <div class="box-body">
                    <div class="tab-content">
                        <div class="row">
                            <div class="clearfix"></div>
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-sm-3">Full name</label>
                                        <div class="col-sm-9">
                                            <div><?= ($talentInfo->first_name)? $talentInfo->first_name : 'My name is here'; ?></div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group row">
                                        <label class="col-sm-3">Other name&nbsp;&nbsp;
                                            <a href="javascript:;" title="Edit" onclick="return updateGuiderForm(<?= $talentInfo->guider_id; ?>,'last_name');"><i class="fa fa-edit"></i></a>
                                        </label>
                                        <div class="col-sm-9">
                                            <div><?= ($talentInfo->last_name)? $talentInfo->last_name : 'Nickname is here'; ?></div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3">Email</label>
                                        <div class="col-sm-9">
                                            <div><?= $talentInfo->email; ?></div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3">Mobile no.</label>
                                        <div class="col-sm-9">
                                            <div><img src="<?= $dirUrl; ?>images/my-flag-16.png"><?= COUNTRY_CODE; ?> <?= $talentInfo->phone_number; ?></div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group row">
                                        <label class="col-sm-3">Date of Birth</label>
                                        <div class="col-sm-9">
                                            <div><?= date('d M Y', strtotime($talentInfo->age)); ?></div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h3>Buskers pod</h3>
                                    </div>
                                    <form class="form-horizontal" id="gigs_info_form" role="form">
                                        <div class="form-body">
                                            <div class="form-group">
                                                <label class="col-sm-3" for="nric_number">Enter NRIC</label>
                                                <div class="col-md-5">
                                                    <input type="text" class="form-control" name="nric_number" id="nric_number" value="<?= $talentInfo->nric_number; ?>">
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="form-group">
                                                <label class="col-sm-3" for="city">Select City</label>
                                                <div class="col-md-5">
                                                    <select class="form-control" name="city" id="city">
                                                        <option value="">Select city</option>
                                                        <?php 
                                                        if($cityLists) { 
                                                          foreach ( $cityLists as $key => $cityInfo ) {
                                                            echo '<option '. (($cityInfo->id == $talentInfo->city)? 'selected' : '') .' value="'. $cityInfo->id .'">'.$cityInfo->name.'</option>';
                                                          }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="form-group">
                                                <label class="col-sm-3" for="area">Enter Area</label>
                                                <div class="col-md-5">
                                                    <input type="text" class="form-control" name="area" id="area" value="<?= $talentInfo->area; ?>">
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="form-group">
                                                <label class="col-sm-3">Skills category</label>
                                                <div class="col-md-5">
                                                    <select class="form-control" name="skills_category" id="skills_category">
                                                        <option value="">Select skills category</option>
                                                        <?php
                                                        if( $specialization_lists ) {
                                                           foreach ( $specialization_lists as $key => $value ) {
                                                            ?><option <?= (($value->specialization_id == $talentInfo->skills_category)? 'selected' : ''); ?> value="<?= $value->specialization_id; ?>"><?=rawurldecode( $value->specialization );?></option><?php
                                                           }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="form-group">
                                                <label class="col-sm-3">Sub skills</label>
                                                <div class="col-md-5">
                                                    <input type="text" class="form-control" name="sub_skills" id="sub_skills" value="<?= $talentInfo->sub_skills; ?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-3">Status</label>
                                                <div class="col-sm-9">
                                                    <div>
                                                        <?php
                                                        if($talentInfo->status == 0){
                                                            echo '<span class="text-yellow">Pending</span>';
                                                        }elseif($talentInfo->status == 1){
                                                            echo '<span class="text-green">Active</span>';
                                                        }elseif($talentInfo->status == 2){
                                                            echo '<span class="text-red">Inactive</span>';
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
											
                                            <div class="clearfix"></div>
                                            <div class="form-actions col-sm-6 pull-right">
                                                <div class="col-md-offset-1" style="padding-left: 15px;">
                                                    <button type="submit" id="gigsInfoSubmitBtn" class="btn btn-sm btn-primary">Update</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="clearfix"></div>
                                
                                <div class="clearfix"></div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h3>Transaction</h3>
                                    </div>
                                    <form class="form-horizontal" id="bank_info_form" role="form">
                                        <div class="form-body">
                                            <div class="form-group">
                                                <label class="col-sm-3">Select Bank</label>
                                                <div class="col-md-5">
                                                    <select class="form-control" id="bank_name" name="bank_name">
                                                      <option value="<?=$talentInfo->bank_name;?>"><?=$talentInfo->bank_name;?></option>
                                                      <option value="">Select Bank Name</option>
                                                      <option value="AFFIN BANK">AFFIN BANK</option>
                                                      <option value="ALLIANCE BANK">ALLIANCE BANK</option>
                                                      <option value="AM BANK">AM BANK</option>
                                                      <option value="BANK ISLAM">BANK ISLAM</option>
                                                      <option value="BANK RAKYAT">BANK RAKYAT</option>
                                                      <option value="BANK MUAMALAT">BANK MUAMALAT</option>
                                                      <option value="BSN BANK">BSN BANK</option>
                                                      <option value="CIMB BANK">CIMB BANK</option>
                                                      <option value="HONGLEONG BANK">HONGLEONG BANK</option>
                                                      <option value="HSBC BANK">HSBC BANK</option>
                                                      <option value="KUWAIT FINANCE HOUSE">KUWAIT FINANCE HOUSE</option>
                                                      <option value="MAY BANK 2E">MAY BANK 2E</option>
                                                      <option value="MAY BANK">MAY BANK</option>
                                                      <option value="OCBC BANK">OCBC BANK</option>
                                                      <option value="PUBLIC BANK">PUBLIC BANK</option>
                                                      <option value="RHB BANK">RHB BANK</option>
                                                      <option value="STANDARD CHARTERED">STANDARD CHARTERED</option>
                                                      <option value="UOB BANK">UOB BANK</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="form-group">
                                                <label class="col-sm-3">Enter Account Name</label>
                                                <div class="col-md-5">
                                                    <input type="text" name="acc_name" id="acc_name" class="form-control" value="<?=$talentInfo->acc_name;?>">
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="form-group">
                                                <label class="col-sm-3">Enter Account Number</label>
                                                <div class="col-md-5">
                                                    <input type="text" name="acc_no" id="acc_no" class="form-control number" value="<?=$talentInfo->acc_no;?>">
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                            <!-- <div class="form-group">
                                                <label class="col-sm-3">Activate donation code</label>
                                                <div class="col-md-5">
                                                    <div class="btn-group" id="status" data-toggle="buttons">
                                                          <label class="btn btn-default btn-on-1 btn-sm <?= (($talentInfo->activate_donation_code == 1)? 'active' : ''); ?>">
                                                          <input type="radio" value="1" id="activate_donation_1" name="activate_donation" <?= (($talentInfo->activate_donation_code == 1)? 'checked="checked"' : ''); ?>>ON</label>
                                                          <label class="btn btn-default btn-off-1 btn-sm <?= (($talentInfo->activate_donation_code == 0)? 'active' : ''); ?>">
                                                          <input type="radio" value="0" id="activate_donation_2" name="activate_donation" <?= (($talentInfo->activate_donation_code == 0)? 'checked="checked"' : ''); ?>>OFF</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label col-form-label-sm" style="font-size: 16px;">Donation Code</label>
                                                <div class="col-md-5">
                                                    <select class="form-control" name="donation_type" id="donation_type">
                                                        <option value="">Select</option>
                                                        <option value="1">Generate QR pay</option>
                                                        <option value="2">Generate payment link</option>
                                                    </select>
                                                </div>
                                            </div> -->
                                            <div class="clearfix"></div>
                                            <div class="form-actions col-sm-6 pull-right">
                                                <div class="col-md-offset-1" style="padding-left: 15px;">
                                                    <button type="submit" id="bankInfoSubmitBtn" class="btn btn-sm btn-primary"> Update</button>
                                                </div>
                                            </div>
                                            <!-- <p>Please complete the transaction details to generate code. Processing Fee chargeable at 5% or minimum RM1.50 on each donations. See <a href="<?= base_url().'faq';?>" target="_blank" /><b>FAQ</b></a> for more info.</p> -->
                                        </div>
                                    </form>
                                </div>
                                <div class="clearfix"></div>
                                <!-- <div class="col-md-12">
                                    <form class="form-horizontal" id="generate_qr_form" role="form" style="margin-top: 10px;">
                                        <div class="form-body">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label col-form-label-sm" style="font-size: 16px;">Donation Code</label>
                                                <div class="col-md-5">
                                                    <select class="form-control" name="donation_type" id="donation_type">
                                                        <option value="">Select</option>
                                                        <option value="1">Generate QR pay</option>
                                                        <option value="2">Generate payment link</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="form-actions col-md-6 offset-md-3 pull-right">
                                                <div class="">
                                                <?php
                                                if($talentInfo->bank_name && $talentInfo->acc_no && $talentInfo->acc_name){ ?>
                                                    <button type="button" onClick="return get_qr_code();" data-toggle="tooltip" id="generateQrBtn" class="btn btn-sm btn-primary"><i class="fa fa-qrcode"></i> Generate QR</button>
                                                <?php }else{ ?>
                                                    <button type="button" disabled data-toggle="tooltip" class="btn btn-sm btn-default"><i class="fa fa-qrcode"></i> Generate QR</button>
                                                <?php } ?>
                                                </div>
                                            </div>
                                            <p>Please complete the transaction details to generate code. Processing Fee chargeable at 5% or minimum RM1.50 on each donations. See <a href="<?= base_url().'faq';?>" target="_blank" /><b>FAQ</b></a> for more info.</p>
                                        </div>
                                    </form>

                                </div> -->
                                <div class="col-md-12">
                                    <h3>Ratings</h3>
                                    
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <?php $total_Like = ($totalLike->is_Like + $totalLike->dis_Like);?>
                                            <a class="btn btn-success btn-xs" title="Like"><i class="fa fa-thumbs-up"></i></a>
                                            <progress max="<?= $total_Like; ?>" value="<?= $totalLike->is_Like; ?>" class="html5">
                                                <progress class="progress-bar"></progress>
                                            </progress>
                                            <span><?= $totalLike->is_Like; ?> Like</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <a class="btn btn-danger btn-xs" title="Dislike"><i class="fa fa-thumbs-down"></i></a>
                                            <progress max="<?= $total_Like; ?>" value="<?= $totalLike->dis_Like; ?>" class="html5">
                                                <progress class="progress-bar"></progress>
                                            </progress>
                                            <span><?= $totalLike->dis_Like; ?> Dislike</span>
                                        </div>
                                    </div>   
                                </div>
                                <!-- Reviews   -->
                                <div class="col-md-12">
                                    <h3>Reviews</h3>
                                </div>
                                <div class="col-md-12">      
                                    <div class="">
                                        <div class="row"> 
                                            <?php 
                                            if ($reviewList) {
                                                foreach ($reviewList as $reviewInfo) { 
                                            ?>
                                            <?php
                                            $disLikeDate = (date('d M Y',strtotime($reviewInfo->disLike_date )));
                                            $likeDate    = (date('d M Y',strtotime($reviewInfo->like_date )));
                                            ?>
                                            <div class="form-group row">
                                                <div class="col-sm-12">
                                                    <?php  if($reviewInfo->is_like == 1){ ?>   
                                                        <a class="btn btn-success btn-xs" title="Like"><i class="fa fa-thumbs-up"></i></a>
                                                    <?php }else{ ?> 
                                                        <a class="btn btn-danger btn-xs" title="Dislike"><i class="fa fa-thumbs-down"></i></a>
                                                    <?php } ?>
                                                </div>
                                                <div class="col-sm-3 col-form-label col-form-label-sm">
                                                    <div><?= $reviewInfo->company_name; ?></div>
                                                    <div>
                                                        <?php if($reviewInfo->is_like == 1){ ?>   
                                                            <span><?= $likeDate; ?></span>
                                                        <?php }else{ ?> 
                                                            <span><?= $disLikeDate; ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="col-sm-9">
                                                    <div><p><?= $reviewInfo->command; ?></p></div>
                                                </div>
                                            </div>
                                            <?php
                                                }
                                            }else{ ?>
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <p>No Reviews Found</p>
                                                    </div>
                                                </div>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>

                    
                </div>
            </div>
                             
        </div>
    </div>            
</div>

<script type="text/javascript">
$(function() {
    $('#activate_donation_1').change(function(event) {

        // event.stopPropagation();
        // alert($("input[name='activate_donation']").prop('checked'));
        // $('#activate_donation_2').trigger("change");
        // $( "#activate_donation_2").prop('checked', true);
        // if($("input[name='activate_donation']").prop('checked') == true){
        //     $('#activate_donation_2').trigger("change");
        //     return false;
        // }
        
        // $( "#activate_donation_1").prop('checked', false);
        // $( "#activate_donation_2").prop('checked', true);
        // return false;
        // var acc_no      = $( '#bank_info_form #acc_no' ).val();
        // var acc_name    = $( '#bank_info_form #acc_name' ).val();
        // var bank_name   = $( '#bank_info_form #bank_name' ).val();
        // if(acc_no == '' || acc_name == '' || bank_name == ''){
        //     toastr.error('Please fill all the bank details','Error');
        //     return false;
        // }
    })
});

function updateGuiderForm( talent_id, field ) {
    $( '#myModal' ).modal( 'show' );
    $( '#myModal .modal-body' ).html('<img src="<?=$adminUrl; ?>img/ajax-loader.gif" style="display: block; margin: 0 auto; width: 100px;" alt="loading..."/>');
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
    }else if(field == 'last_name'){
        title = 'Other Name';
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
        title = 'Nric Number';
    }else if(field == 'phone_number'){
        title = 'Phone Number';
    }
    $('#myModal .modal-title').html( title );
    var data = 'talent_id='+talent_id+'&field='+field;
    $.ajax( {
        type: "POST",
        data: data,
        url: baseurl + 'talent/profile/updateGuiderForm',
        success: function( msg ) {
            $( '#myModal .modal-body' ).html(msg);
            $( '#myModal .modal-footer' ).html('');
        }
    });
    return false;
}

function addMystory() {

    $( '#myModal' ).modal( 'show' );
    $( '#myModal #mymodalBody' ).html('');
    $( '#myModal #mymodalTitle' ).html( 'Update My Story' );
    $.ajax({
        type: "POST",
        data: {},
        url: baseurl + 'talent/profile/getMystory',
        success: function( data ) {
          $( '#myModal #mymodalBody' ).html(data);
        }
    });

    //$( '#myModal #mymodalBody' ).html( '');
    $( '#my_modal_footer' ).html( '<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button><button type="button" id="continuemodal_1" class="btn btn-primary">Update</button>' );
    $( '#continuemodal_1' ).click( function() {

        var my_story = $('#update_mystory_form #my_story').val();
        if(my_story == '' || my_story == 0){
          toastr.error('My Story Cannot be empty','Error');
          return false;
        }
        $.ajax({
            type: "POST",
            url: baseurl + 'talent/profile/updateMystory',
            data : { 'my_story' : my_story },
            beforeSend: function() {
                $("#continuemodal_1").html('loading...');
                $("#continuemodal_1").prop('disabled', true);
            },
            success : function( msg ) {
                location.reload();
            }
        });
        return false;
    });
}

function addProfile(id,field) {
    $( '#myModal' ).modal( 'show' );
    $( '#myModal #mymodalBody' ).html('<img src="<?php echo $adminUrl;?>img/loading.gif">');
    var title = '';
    if(field == 'profile_image'){
      title = 'Profile Image';
    }
    $( '#myModal #mymodalTitle' ).html( title );
    var data = 'id='+id+'&field='+field;
      $.ajax({
        type: "POST",
        data: data,
        url: baseurl + 'talent/profile/addProfile',
        success: function( data ) {
          $( '#myModal #mymodalBody' ).html(data);
          $( '#myModal .modal-footer' ).html('');
        }
      });
    return false;
}
function addSocialLink() {
    $( '#myModal' ).modal( 'show' );
    $( '#myModal #mymodalBody' ).html('<img src="<?php echo $adminUrl;?>img/loading.gif">');
    $( '#myModal #mymodalTitle' ).html('Update Social Link');
    $('#myModal #my_modal_footer').hide();
    $.ajax({
        type: "POST",
        data: {},
        url: baseurl + 'talent/profile/addSocialLink',
        success: function( data ) {
          $( '#myModal #mymodalBody' ).html(data);
        }
    });
    return false;
}

$(document).ready(function(){
  $(".profileImgPlace").on('click', function() {
    $("#profileImg").trigger('click');
  });
    
    formdata = new FormData();
    $("#profileImg").change(function(){
        var file = this.files[0];
        var file_size = file.size/1024/1024;
        if(file_size < 4){
            formdata.append("profile_image", file);
            var xhr = new XMLHttpRequest();
            $('#message_profile').empty();
            xhr.onreadystatechange = function() {

                if (xhr.readyState == 4) {
                    var res = JSON.parse(xhr.responseText);
                    if(res) {
                        if(res['error']){
                            $('#message_profile').append( '<span class="text-danger">'+res['error']+'<span>' );
                            $('#profileImg').focus();
                            return false;
                        }
                        if(res['success']){
                            $('#message_profile').empty();
                            $("#profileImgPlace").attr("src", res['ProfilePic']);
                        }
                    }
                }
            }
            xhr.open("POST", baseurl +"talent/profile/updateAjaxProfile");
            xhr.send(formdata);
        }else{
            alert('File too large. File must be less than 4 MB.');
        }
    });
    formdata2 = new FormData();
    $(".fileuploadbtn").change(function(){
        var type = $(this).attr('ftype');
        var file = this.files[0];
        var file_size = file.size/1024/1024;
        if(file_size < 4){
            formdata2.append("attachment_file", file);
            formdata2.append("type", type);
            var xhr = new XMLHttpRequest();
            $('#upload_message').empty();
            xhr.onreadystatechange = function() {

                if (xhr.readyState == 4) {
                    var res = JSON.parse(xhr.responseText);
                    if(res) {
                        if(res['error']){
                            $('#upload_message').append( '<span class="text-danger">'+res['error']+'<span>' );
                            $('#profileImg').focus();
                            return false;
                        }
                        if(res['success']){
                            $('#upload_message').empty();
                            toastr.success( 'Attachment uploaded successfully.','Success' );
                            $("#uploadlink_"+type).html('<a href="'+res['attachment_url']+'" target="_blank" class="label label-info">'+res['attachment_name']+'<a>');
                        }
                    }
                }
            }
            xhr.open("POST", baseurl +"talent/profile/uploadAttachmentFile");
            xhr.send(formdata2);
        }else{
            alert('File too large. File must be less than 4 MB.');
        }
    });
});
</script>
<script type="text/javascript">
$(document).ready(function(){
    $("#gigs_info_form").on('submit', function(e){
        e.preventDefault();

        var skills_category   = $( '#gigs_info_form #skills_category' ).val();
        var nric_number       = $( '#gigs_info_form #nric_number' ).val();
        if(skills_category == null || skills_category == ''){
          toastr.error('Skills Category cannot be empty','Error');
          return false;
        }
        if(nric_number == ''){
          toastr.error('NRIC Number cannot be empty','Error');
          return false;
        }
        if(skills_category && nric_number){
            $.ajax({
              type: "POST",
              url: baseurl + '/talent/profile/updateGigsInfo',
              data: new FormData(this),
              contentType: false,
              cache: false,
              processData:false,
              beforeSend: function() { 
                  $("#gigsInfoSubmitBtn").html('<img src="<?php echo $adminUrl;?>img/loading.gif" style="height:20px;"> Loading...');
                  $("#gigsInfoSubmitBtn").prop('disabled', true);
                  $('#gigs_info_form').css("opacity",".5");
              },
              success: function( data ) {
                if(data == 1){
                  $("#gigsInfoSubmitBtn").html('Update');
                  $("#gigsInfoSubmitBtn").prop('disabled', false);
                  toastr.success( 'Gigs info updated successfully.','Success' );
                  //$("form#gigs_info_form").trigger("reset");
                  location.reload();
                }else{
                  toastr.error( data,'Error' );
                  $("#gigsInfoSubmitBtn").html('Update');
                  $("#gigsInfoSubmitBtn").prop('disabled', false);
                }
                $('#gigs_info_form').css("opacity","");
              }
            });
        }
    });

    $("#bank_info_form").on('submit', function(e){
        e.preventDefault();

        var acc_no      = $( '#bank_info_form #acc_no' ).val();
        var acc_name    = $( '#bank_info_form #acc_name' ).val();
        var bank_name   = $( '#bank_info_form #bank_name' ).val();
        var donation_type = $( '#bank_info_form #donation_type' ).val();

        if(bank_name == ''){
          toastr.error('Bank Name cannot be empty','Error');
          return false;
        }
        if(acc_no == null || acc_no == ''){
          toastr.error('Account Number cannot be empty','Error');
          return false;
        }else{
            if ( acc_no.length < 5 ){
                toastr.error('Invalid Account Number','Error');
                return false;
            }
        }
        if(acc_name == ''){
          toastr.error('Account Name cannot be empty','Error');
          return false;
        }
        if($("input[name='activate_donation']").prop('checked') == false){
            toastr.error('Donation activation code not enabled','Error');
            return false;
        }
        // if(donation_type == ''){
        //   toastr.error('Donation Type cannot be empty','Error');
        //   return false;
        // }
        if(bank_name && acc_no && acc_name){
            $.ajax({
              type: "POST",
              url: baseurl + '/talent/profile/updateBankInfo',
              data: new FormData(this),
              contentType: false,
              cache: false,
              processData:false,
              beforeSend: function() { 
                  $("#bankInfoSubmitBtn").html('<img src="<?php echo $adminUrl;?>img/loading.gif" style="height:20px;"> Loading...');
                  $("#bankInfoSubmitBtn").prop('disabled', true);
                  $('#bank_info_form').css("opacity",".5");
              },
              success: function( data ) {
                if(data == 1){
                    //get_qr_code(donation_type);
                    $("#bankInfoSubmitBtn").html('Update');
                    $("#bankInfoSubmitBtn").prop('disabled', false);
                    toastr.success( 'Bank info updated successfully.','Success' );
                    //$("form#bank_info_form").trigger("reset");
                    //location.reload();
                }else{
                    toastr.error( data,'Error' );
                    $("#bankInfoSubmitBtn").html('Update');
                    $("#bankInfoSubmitBtn").prop('disabled', false);
                }
                $('#bank_info_form').css("opacity","");
              }
            });
            return false;
        }
    });

});
</script>
<script type="text/javascript">
function deleteUrl(id) {
  $( '#myModal .modal-title' ).html( 'Confirm' );
  $( '#myModal .modal-body' ).html( 'Are you sure want to delete the Youtube events ?' );
  $( '#myModal .modal-body' ).append( '<br /><br /><button class="btn btn-info btn-sm" id="continuemodal" data-dismiss="modal">Yes</button>&nbsp;&nbsp;<button aria-hidden="true" data-dismiss="modal" class="btn btn-danger btn-sm">Cancel</button>' );
  $( '#myModal' ).modal({ backdrop: 'static', keyboard: false }); 
  $( "#continuemodal" ).click(function() {
      var data = 'id=' + id;
      $.ajax({
        type: "POST",
        url: baseurl + 'talent/profile/deleteUrl',
        data: data,
        success: function( data ) {
          toastr.success( 'Youtube details deleted successfully.','Success' );
          setTimeout( function() {
            window.location.href = baseurl + 'talent/profile';
          }, 1000 );
        }
      });
  });    
  return false;
}

function get_qr_code(donation_type) {
    if(donation_type == 1){
        $('#myModal .modal-title').html('Profile QR Code');
    }else{
        $('#myModal .modal-title').html('Profile Link');
    }
    $.ajax({
        type  : 'POST',
        url   : baseurl + 'talent/profile/get_qr_code',
        data  : { 'donation_type':donation_type },
        async : false,
        success     : function( msg ) {
            $('#myModal').modal( 'show' );
            $('#myModal #my_modal_footer').hide();
            $( '#myModal .modal-body' ).html(msg);
        }
    });
    return false;
}

</script>