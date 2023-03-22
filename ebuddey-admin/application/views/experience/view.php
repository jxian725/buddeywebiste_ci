<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl   = $this->config->item( 'admin_url' );
$site_name  = $this->config->item( 'site_name' );
$dirUrl     = $this->config->item( 'dir_url' );
?>
<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header">
        <div class="box-tools pull-right">
          <a href="<?php echo base_url(); ?>experience" class="btn btn-sm btn-primary">Back</a>
        </div>
      </div>
      <div class="box-body">
        <div class="row">
            <div class="col-md-9">
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
                    <label for="experience_title">Talent Name</label>
                    <div><?= $talentExpInfo->first_name; ?></div>
                </div>
                <div class="form-group">
                    <label for="experience_title">Talent Email</label>
                    <div><?= $talentExpInfo->email; ?></div>
                </div>
                <div class="form-group">
                    <label for="experience_title">Phone Number</label>
                    <div><?= $talentExpInfo->phone_number; ?></div>
                </div>
                <div class="form-group">
                    <label for="experience_title">Give your talent experiences a title (max 80 words)</label>
                    <div><?= $talentExpInfo->experience_title; ?></div>
                </div>
                <div class="form-group">
                    <label for="skills_category">Category</label>
                    <div><?php echo $talentExpInfo->categoryName;?></div>
                </div>
                <div class="form-group">
                    <label>Where will you be hosting your talent experiences?</label>
                    <div><?php echo $talentExpInfo->cityName;?></div>
                </div>
                <div class="form-group">
                    <label for="languages_known">Language</label>
                    <?php
                    $lang = [];
                    if($talentExpInfo->languages_known){
                        $array  = explode(',', $talentExpInfo->languages_known);
                        foreach ($array as $item) {
                            $langInfo = $this->Guidermodel->guiderLangInfo($item);
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
        </div>
        <div class="clearfix"></div>
    </div>
  </div>
</div>
