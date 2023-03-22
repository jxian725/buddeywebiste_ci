<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl = $this->config->item( 'admin_url' );

?>
<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header">
        <div class="box-tools pull-right">
          <a href="<?php echo $assetUrl; ?>newsletter" class="btn btn-sm btn-primary">Back</a>
        </div>
      </div>
    <div class="box-body">
      <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label">Image</label>
                <div>
                  <?php
                  if( $newsletterInfo->image ) {
                    echo '<img src="'.$newsletterInfo->image.'" alt="Buddey" style="width: 80px;">';
                  }else{
                    echo '-';
                  }
                  ?>
                  </div>
            </div>
            <div class="form-group">
                <label class="control-label">Title</label>
                <div><?=rawurldecode($newsletterInfo->title); ?></div>
            </div>
            <div class="form-group">
                <label class="control-label">Description</label>
                <div><?=rawurldecode($newsletterInfo->description); ?></div>
            </div>
            <div class="form-group">
                <label class="control-label">Video url</label>
                <div><?=$newsletterInfo->video_url; ?></div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>