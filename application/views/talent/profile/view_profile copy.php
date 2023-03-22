<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 
$adminUrl   = $this->config->item( 'admin_dir_url' );
$dirUrl     = $this->config->item( 'dir_url' );
?>
<style type="text/css">
.follow-list{
    list-style: none;
    display: -webkit-inline-box;
}
.follow-box-content{
    padding: 20px 20px;
}
.info-box-no {
    font-size: 18px;
    text-align: center;
    display: block;
}
.profile-user-img {
    width: auto;
    height: 160px;
}
</style>

<div class="count_box row">
    <div class="col-md-12">
        <div class="col-xl-8 col-sm-8" style="padding-left: 0px;">

            <div class="">
                <div class="box-body box-profile">
                    <div class="col-sm-4">
                        <?php
                        if($talentInfo->profile_image){
                            $profileImg = $adminUrl.'uploads/g_profile/'.$talentInfo->profile_image;
                        }else{
                            $profileImg = $adminUrl.'uploads/default_img.png';
                        }
                        ?>
                        <img class="profile-user-img img-responsive img-circle" src="<?= $profileImg; ?>" alt="User profile picture">
                    </div>
                    <div class="col-sm-8">
                        <div class="text-center"><a href="<?= base_url().'talent/profile/edit'; ?>" class="btn btn-info btn-sm">Edit Profile</a></div>
                        <div class="text-center">
                            <ul class="follow-list">
                                <li>
                                    <div class="follow-box-content">
                                      <span class="info-box-text">Following</span>
                                      <span class="info-box-no">20</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="follow-box-content">
                                      <span class="info-box-text">Followers</span>
                                      <span class="info-box-no">34</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <h4><?=$talentInfo->last_name; ?></h4>
                        <p><?= ($talentInfo->about_me)? $talentInfo->about_me : '-'; ?></p>
                    </div>
                    <div class="col-sm-12">
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b><?= ($talentInfo->sub_skills)? $talentInfo->sub_skills : '-'; ?></b> <span class="pull-right"><?=$talentInfo->area; ?> <?=$talentInfo->cityName; ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <div class="col-xl-4 col-sm-4" style="padding-left: 0px;">
            <div class="panel panel-default">
              <div class="panel-heading">Comments</div>
              <div class="panel-body" id="view_comments" style="max-height: 350px;overflow-y: scroll;">
                <?php
                if($comments_list){
                  foreach ($comments_list as $key => $comments) {
                    if(!$comments->message){ continue; }
                    ?>
                    <p><b><?= ($comments->fullName)? $comments->fullName : 'Anonymous'; ?>:</b> <?= $comments->message; ?></p>
                    <?php
                  }
                }
                ?>
              </div>
            </div>
            <div class="panel panel-default">
              <div class="panel-heading">Connect with me on</div>
              <div class="panel-body" id="view_social_link">
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

<script type="text/javascript">
</script>