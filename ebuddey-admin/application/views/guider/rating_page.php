<?php
defined('BASEPATH') OR exit('No direct script access allowed');  
$assetUrl   = $this->config->item( 'admin_url' );
$site_name  = $this->config->item( 'site_name' );
$dirUrl     = $this->config->item( 'dir_url' );
?>
<style type="text/css">
.box.box-review{
border-top-color: #ddd;
}
</style>
<link rel="stylesheet" href="<?= $dirUrl; ?>plugins/lightbox/css/lightbox.min.css">
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header">
                <ul class="nav nav-tabs">
                    <li class="Inactive"><a href="<?php echo $assetUrl; ?>guider/view"><?= HOST_NAME; ?> Profile</a></li>
                    <li class="active"><a href="<?php echo $assetUrl; ?>guider/ratingPage">Ratings and Reviews</a></li>
                </ul>
                <div class="box-tools pull-right">
                    <a href="<?php echo $assetUrl; ?>guider" class="btn btn-sm btn-primary">Back</a>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-10">
                        <h4 class="box-title">Ratings</h4>
                        <div class="form-group">
                            <a class="btn btn-success btn-xs" title="Like"><i class="fa fa-thumbs-up"></i></a>
                            <progress class="chart-bar" value="50" max="100" style="height: auto;width: 50%;"></progress>
                            <span>50 Like</span>
                        </div> 
                        <div class="form-group">
                            <a class="btn btn-danger btn-xs" title="Dis Like"><i class="fa fa-thumbs-down"></i></a>
                            <progress class="chart-bar" value="20" max="100" style="height: auto;width: 50%;"></progress>
                            <span>20 DisLike</span>
                        </div>     
                    </div>

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
                                        ?>
                                        <?php
                                        $disLikeDate = (date('d M Y',strtotime($reviewInfo->disLike_date )));
                                        $likeDate    = (date('d M Y',strtotime($reviewInfo->like_date )));
                                        ?>
                                       
                                        <div class="form-group">
                                        <?php  if($reviewInfo->is_like == 1){ ?>   
                                            <a class="btn btn-success btn-xs" title="Like"><i class="fa fa-thumbs-up"></i></a>
                                        <?php }else{ ?> 
                                            <a class="btn btn-danger btn-xs" title="Like"><i class="fa fa-thumbs-down"></i></a>
                                        <?php } ?>     
                                        </div>
                                        <div class="form-group">     
                                            <span><?php echo rawurldecode($reviewInfo->partner_name); ?></span>
                                        <div class="form-group">
                                        <?php  if($reviewInfo->is_like == 1){ ?>   
                                            <span><?= $likeDate; ?></span>
                                        <?php }else{ ?> 
                                            <span><?= $disLikeDate; ?></span>
                                        <?php } ?>    
                                        <div class="form-group">
                                            <span><?= $reviewInfo->command; ?></span>
                                        </div>
                                        <?php
                                            }
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
