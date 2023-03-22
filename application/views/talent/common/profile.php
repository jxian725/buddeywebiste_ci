<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 
$assetUrl   = $this->config->item( 'admin_url' );
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
</style>
<div class="count_box row">
    <div class="col-md-4">
        <div class="col-xl-12 col-sm-14" style="padding-left: 0px;">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <?php 
                    if($imageLists){ 
                        foreach ($imageLists as $img) { 
                            $photo = ($img->profile_image); ?>
                    <?php } ?>
                    <div class="form-group">
                        <img src="<?= $adminUrl;?>uploads/g_profile/<?= $photo; ?>" class="pull-center" style="height: 250px;width: 400px;" align="center"/>
                    </div>
                    <?php } ?>
                    <div class="form-group">
                        <?php $guider_id = ($talentInfo->guider_id);?> 
                        <a href="javascript:;" class="btn btn-success btn-sm  pull-right" style="height: 30px;" onclick="return addProfile(<?= $guider_id; ?>,'profile_image');">Edit</a>
                    </div>
                    <div class="form-group">
                        <b>My story</b>
                        <?php 
                        if($imageLists){ 
                            foreach ($imageLists as $talent) { 
                                $story = ($talent->about_me); ?>
                                <div class="text">
                                    <div><?= $story; ?></div>
                                </div>
                        <?php } } ?>
                    </div>
                    <div class="form-group">
                        <b>My work</b> 
                        <?php $guider_id = ($talentInfo->guider_id);?> 
                        <a href="javascript:;" class="btn btn-info btn-sm  pull-right" style="height: 30px;" onclick="return addUrl(<?= $guider_id; ?>,'0');">Add</a>
                    </div>
                    <div class="form-group">
                        <ul class="todo-list">
                            <?php 
                            if($urlLists){ 
                                foreach ($urlLists as $url) { ?>
                                <a href="<?= $url->url_link; ?>"  target="_blank">
                                    <div class="attachment-block clearfix">
                                        <iframe class="attachment-img" src="<?= $url->url_link; ?>" style="height: 50px;"></iframe>
                                        <div class="attachment-pushed">
                                            <div class="attachment-text">
                                                <?= $url->description; ?>
                                            </div>
                                        </div>
                                    </div>
                                </a>    
                            <?php } } ?> 
                        </ul>
                    </div>
                    <div class="form-group">
                        <b>Spotify</b> 
                    </div>         
                </div>
            </div>
        </div>        
    </div>
    <div class="col-md-8">
        <div class="box box-primary">
            <div class="box-header">
                <!-- Classic tabs -->
                <ul class="nav nav-tabs">
                    <li class="tablinks"><a href="javascript:;" class="tablinks" onclick="openCity(event, 'London')" id="defaultOpen">Profile</a></li>
                    <li class="tablinks"><a href="javascript:;" class="tablinks" onclick="openCity(event, 'Paris')" id="defaultclose">Public Profile</a></li>
                    <li class="tablinks"><a href="javascript:;" class="tablinks" onclick="openCity(event, 'Chennai')" id="defaultclose">Attachment</a></li>
                </ul>
            </div>
            <div id="London" class="tabcontent">       
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="col-sm-3">Full Name</label>
                                <div><?= $talentInfo->first_name; ?></div>
                            </div>   
                            <div class="form-group">
                                <label class="col-sm-3">Other Name</label>
                                <div><?= $talentInfo->last_name; ?></div>
                            </div> 
                            <div class="form-group">
                                <label class="col-sm-3">City</label>
                                <div><?= $talentInfo->cityName; ?></div>
                            </div> 
                            <div class="form-group">
                                <label class="col-sm-3">Area</label>
                                <div><?= $talentInfo->area; ?></div>
                            </div> 
                            <div class="form-group">
                                <label class="col-sm-3">Brithdate</label>
                                <div><?= date('d M Y', strtotime($talentInfo->age)); ?></div>
                            </div> 
                            <div class="form-group">
                                <label class="col-sm-3">NRIC</label>
                                <div><?= $talentInfo->nric_number; ?></div>
                            </div> 
                            <div class="form-group">
                                <label class="col-sm-3">Gender</label>
                                <select class="col-sm-3" name="gender" id="gender">
                                    <option value="$talentInfo->gender" <?php if($talentInfo->gender == "1"){?> selected="selected" <?php }?>>Male</option>
                                    <option value="$talentInfo->gender" <?php if($talentInfo->gender == "2"){?> selected="selected" <?php }?>>Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <h3 class="col-sm-2">Gigs</h3>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="col-sm-3">Skills category</label>
                                <select class="col-sm-4" name="skills_category" id="skills_category">
                                    <option value="0">Select skills category</option>
                                    <?php
                                    if( $specialization_lists ) {
                                       foreach ( $specialization_lists as $key => $value ) {
                                        echo '<option '.(($value->specialization_id==$talentInfo->skills_category)? 'selected' : '').' value="'. $value->specialization_id .'">'. rawurldecode($value->specialization) .'</option>';
                                       }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8">     
                            <div class="form-group">
                                <label class="col-sm-3">Sub Skills</label>
                                <div><?= $talentInfo->sub_skills; ?></div>
                            </div>
                        </div>
                        <div class="col-md-8">    
                            <div class="form-group">
                                <label class="col-sm-3">Hire me</label>
                                <input name="" placeholder="0.00" value="<?= $talentInfo->gigs_amount; ?>.00"> per gigs
                            </div>  
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <h3 class="col-sm-2">Contacts</h3>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="col-sm-3">Phone</label>
                                <div><img src="<?= $dirUrl; ?>images/my-flag-16.png">+60 <?= $talentInfo->mobile; ?></div>
                            </div>
                        </div>
                        <div class="col-md-8">     
                            <div class="form-group">
                                <label class="col-sm-3">Email</label>
                                <div><?= $talentInfo->email; ?></div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <h3 class="col-sm-2">Transaction</h3>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="col-sm-3">Bank</label>
                                <div><?= $talentInfo->bank_name; ?></div>
                            </div> 
                        </div>
                        <div class="col-md-8">    
                            <div class="form-group">
                                <label class="col-sm-3">Account Name</label>
                                <div><?= $talentInfo->acc_name; ?></div>
                            </div>
                        </div>
                        <div class="col-md-8">    
                            <div class="form-group">
                                <label class="col-sm-3">Account Number</label>
                                <div><?= $talentInfo->acc_no; ?></div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h3 class="col-sm-2">Ratings</h3>
                        </div>
                        <div class="col-md-8">    
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
                        <!-- Reviews   -->
                        <div class="col-md-8">
                            <h3 class="col-sm-2">Reviews</h3>
                        </div>
                        <div class="col-md-8">      
                            <div class="box-body">
                                <div class="row"> 
                                    <?php 
                                    if ($reviewList) {
                                        foreach ($reviewList as $reviewInfo) { 
                                    ?>
                                    <?php
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
                                    </div>    
                                    <div class="form-group">
                                        <?php  if($reviewInfo->is_like == 1){ ?>   
                                            <span><?= $likeDate; ?></span>
                                        <?php }else{ ?> 
                                            <span><?= $disLikeDate; ?></span>
                                        <?php } ?>
                                    </div>        
                                    <div class="form-group">
                                        <span><?= $reviewInfo->command; ?></span>
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
            </div>
            <div id="Paris" class="tabcontent">
                <div class="box-body">
                    <div class="row">
                          
                        <div class="col-md-8">    
                            <div class="form-group">
                                <label class="col-sm-4">Known as</label>
                                <div><?= $talentInfo->last_name; ?></div>
                            </div>   
                            <div class="form-group">
                                <label class="col-sm-4">Category</label>
                                <div><?= $talentInfo->categoryName; ?></div>
                            </div> 
                            <div class="form-group">
                                <label class="col-sm-4">Sub Skills</label>
                                <div><?= $talentInfo->sub_skills; ?></div>
                            </div> 
                            <div class="form-group">
                                <label class="col-sm-4">City</label>
                                <div><?= $talentInfo->cityName; ?></div>
                            </div> 
                            <div class="form-group">
                                <label class="col-sm-4">Gigs Completed</label>
                                <div><?= $talentInfo->gigs_amount; ?></div>
                            </div> 
                            <div class="form-group">
                                <label class="col-sm-4">Busking Completed</label>
                                <div><?= $talentInfo->nric_number; ?></div>
                            </div> 
                            <div class="form-group">
                                <label class="col-sm-4">Hire me</label>
                                <div><b>RM</b><?= $talentInfo->gigs_amount; ?> per gigs</div>
                            </div>
                            <div class="form-group">
                                <a href="<?= $dirUrl; ?>talent/venue/message"><button  class="btn btn-info btn-sm">Message</button></a>
                                <button  class="btn btn-primary btn-sm" onclick="return Share(<?= $guider_id; ?>,'0');">Share</button>
                                <button  class="btn btn-warning btn-sm"  onclick="return hire(<?= $guider_id; ?>);">Hire me</button>
                            </div>    
                        </div>
                        <div class="col-md-8">
                            <h3 class="col-sm-2">Ratings</h3>
                        </div>
                        <div class="col-sm-8">    
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
                        <!-- Reviews   -->
                        <div class="col-md-8">
                            <h3 class="col-sm-2">Reviews</h3>
                        </div>
                        <div class="col-md-8">      
                            <div class="box-body">
                                <div class="row"> 
                                    <?php 
                                    if ($reviewList) {
                                        foreach ($reviewList as $reviewInfo) { 
                                    ?>
                                    <?php
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
                                    </div>    
                                    <div class="form-group">
                                        <?php  if($reviewInfo->is_like == 1){ ?>   
                                            <span><?= $likeDate; ?></span>
                                        <?php }else{ ?> 
                                            <span><?= $disLikeDate; ?></span>
                                        <?php } ?>
                                    </div>        
                                    <div class="form-group">
                                        <span><?= $reviewInfo->command; ?></span>
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
            </div>
            <div id="Chennai" class="tabcontent">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-8">
                           <div class="col-md-4">
                                <div class="form-group">
                                    <input type="text" name="" id="" placeholder="e.g. license, certificates">
                                </div>
                                <div class="form-group">
                                     <p>Formate PDF only</p>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="" id="" placeholder="e.g. license, certificates">
                                </div>
                                <div class="form-group">
                                     <p>Formate PDF only</p>
                                </div>
                            </div>
                            <div class="col-md-4">    
                                <div class="form-group">
                                    <div class="btn btn-default btn-file">
                                        <i class="fa fa-paperclip"></i> Upload
                                        <input type="file" name="attachment1">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="btn btn-default btn-file">
                                        <i class="fa fa-paperclip"></i> Upload
                                        <input type="file" name="attachment2">
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
<!--java script --> 
<script type="text/javascript">
function openCity(evt, cityName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}
document.getElementById("defaultOpen").click();
</script>
<script type="text/javascript">
function addProfile(id,field) {
    $( '#myModal' ).modal( 'show' );
    $( '#myModal .modal-body' ).html('<img src="<?php echo $adminUrl;?>img/loading.gif">');
    var title = '';
    if(field == 'profile_image'){
      title = 'Profile Image';
    }
    $( '#myModal .modal-title' ).html( title );
    var data = 'id='+id+'&field='+field;
      $.ajax({
        type: "POST",
        data: data,
        url: baseurl + 'talent/Venue/addProfile',
        success: function( data ) {
          $( '#myModal .modal-body' ).html(data);
          $( '#myModal .modal-footer' ).html('');
        }
      });
    return false;
}
function addUrl(id,field) {
    $( '#myModal' ).modal( 'show' );
    $( '#myModal .modal-body' ).html('<img src="<?php echo $adminUrl;?>img/loading.gif">');
    var title = '';
    if(field == 0){
      title = 'Add url';
    }
    $( '#myModal .modal-title' ).html( title );
    var data = 'id='+id+'&field='+field;
      $.ajax({
        type: "POST",
        data: data,
        url: baseurl + 'talent/Venue/addUrl',
        success: function( data ) {
          $( '#myModal .modal-body' ).html(data);
          $( '#myModal .modal-footer' ).html('');
        }
      });
    return false;
}
function hire( id ) {
  $( '#myModal .modal-body' ).html( 'Login or Sign up to connect with Talent ?' );
  $( '#myModal .modal-body' ).append( '<br /><br /><a href="<?= $dirUrl;?>talent/registe"><button class="btn btn-info btn-sm" id="signup" data-dismiss="modal">Sign Up</button></a>&nbsp;&nbsp;<a href="<?= $dirUrl;?>talent/login"><button class="btn btn-primary btn-sm" id="login" data-dismiss="modal">Login</button></a>' );
  $( '#myModal' ).modal({ backdrop: 'static', keyboard: false }); 
  return false;
}
function Share(id,field) {
    $( '#myModal' ).modal( 'show' );
    $( '#myModal .modal-body' ).html('<img src="<?php echo $adminUrl;?>img/loading.gif">');
    var title = '';
    if(field == 0){
      title = 'Share';
    }
    $( '#myModal .modal-title' ).html( title );
    var data = 'id='+id+'&field='+field;
      $.ajax({
        type: "POST",
        data: data,
        url: baseurl + 'talent/Venue/shareUrl',
        success: function( data ) {
          $( '#myModal .modal-body' ).html(data);
          $( '#myModal .modal-footer' ).html('');
        }
      });
    return false;
}         
</script>