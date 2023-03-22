<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 
$adminUrl   = $this->config->item( 'admin_dir_url' );
$dirUrl     = $this->config->item( 'dir_url' );
?>
<style type="text/css">
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
            <h2 class="box-title">Manage Talent Experiences</h2>
            <div class="box-tools pull-right">
                <a href="<?= base_url().'talent/experience/manage'; ?>" class="btn btn-info btn-sm">Back</a>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-body">
                <div id="experience_sec">
                    <div class="row">
                        <div class="clearfix"></div>
                        <div class="col-md-8">
                            <div class="box-body">
                                <?php
                                if($talentExpInfo){ ?>
                                <div class="form-group">
                                    <label for="experience_title">Status</label>
                                    <div>
                                    <?php
                                    if($talentExpInfo->status == 2){
                                        echo '<span class="label label-warning">Under review</span>';
                                    }elseif ($talentExpInfo->status == 1) {
                                        echo '<span class="label label-success">Approved</span>';
                                    }else{
                                        echo '<span class="label label-default">Edit Mode</span>';
                                    }
                                    ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="experience_title">Give your talent experiences a title (max 80 words)</label>
                                    <div><?= $talentExpInfo->experience_title; ?></div>
                                </div>
                                <div class="form-group">
                                    <label for="skills_category">Category</label>
                                    <div>
                                        <?php
                                        $specInfo = $this->Talentmodel->specializationInfo($talentExpInfo->skills_category);
                                        echo (($specInfo)? rawurldecode($specInfo->specialization) : '');
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Where will you be hosting your talent experiences?</label>
                                    <div>
                                        <?php
                                        $stateInfo = $this->Talentmodel->stateInfo($talentExpInfo->city);
                                        echo (($stateInfo)? rawurldecode($stateInfo->name) : '');
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="languages_known">Language</label>
                                    <?php
                                    $lang = [];
                                    if($talentExpInfo->languages_known){
                                        $array  = explode(',', $talentExpInfo->languages_known);
                                        foreach ($array as $item) {
                                            $langInfo = $this->Talentmodel->langInfo($item);
                                            if($langInfo){ $lang[] = $langInfo->language; }
                                        }
                                    }
                                    ?>
                                    <div><?=ucfirst( implode(',', $lang) );?></div>
                                </div>
                                <div class="form-group">
                                    <label for="about_us">Tell us more about the whole experiences you will provided (Max 300 words)</label>
                                    <div><?= $talentExpInfo->about_us; ?></div>
                                </div>
                                <div class="form-group">
                                    <label>What are the requirement or preparation for someone to book your talent experiences?(Max 300 words)</label>
                                    <div><?= $talentExpInfo->requirement; ?></div>
                                </div>
                                <div class="form-group">
                                    <label for="video_link">Uploaded media</label>
                                    <div><?= $talentExpInfo->video_link; ?></div>
                                    <?php
                                    $video_id = explode("?v=", $talentExpInfo->video_link);
                                    $video_id = $video_id[1];
                                    $embed_url= 'https://www.youtube.com/embed/'.$video_id;
                                    if($embed_url){ ?>
                                    <a href="<?= $embed_url;?>" target="_blank">
                                        <span class="handle ui-sortable-handle">
                                            <iframe width="auto" height="120" src="<?= $embed_url;?>"></iframe>
                                            <p class="text"></p>
                                        </span>
                                    </a>
                                    <?php } ?>
                                </div>
                                <div class="form-group">
                                  <label for="price_rate">What is your price rate per 15 minutes?</label>
                                  <div>RM<?= floatval($talentExpInfo->price_rate); ?></div>
                                </div>
                                <?php }else{ ?>
                                    <div class="form-group">
                                        <label for="experience_title">Status</label>
                                        <div><span class="label label-danger">N/A</span></div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group pull-right">
                                <a href="<?= base_url().'talent/experience/add'; ?>" class="btn btn-primary">Create</a>
                                <?php if($talentExpInfo){ ?>
                                    <a href="<?= base_url().'talent/experience/edit/'.(($talentExpInfo)? $talentExpInfo->te_id : ''); ?>" class="btn btn-success">Update</a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>