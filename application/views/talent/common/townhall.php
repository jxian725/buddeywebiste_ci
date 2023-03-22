<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 
$assetUrl   = $this->config->item( 'admin_dir_url' );
$site_name  = $this->config->item( 'site_name' );
$dirUrl     = $this->config->item( 'dir_url' );
?>
<style type="text/css">
.direct-chat-text{
    background-color: #FFF;
}    
</style>
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div>
                                    <?php 
                                    if($newsLists){ 
                                        foreach ($newsLists as $news) { 
                                    ?>
                                    <div class="direct-chat-text">
                                        <div class="form-group">
                                            <h4>News update here
                                               <p class="text pull-right"><?=  date('d M Y', strtotime($news->created_on )); ?></p>
                                            </h4>
                                        </div>
                                        <div><?= rawurldecode($news->title); ?></div>
                                    </div>    
                                        <?php } ?>
                                        <?php }else{ ?> 
                                        <center><span class="label label-primary">No Reviews Found</span></center>
                                        <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>       
        </div>
    </div>
</div>
