<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$dirUrl     = $this->config->item( 'dir_url' );
$upload_path_url = $this->config->item( 'upload_path_url' );
?>
<div class="portlet-body form add_reward_form">
    <div class="form-body">
        <div class="row">
            <div class="col-md-12">
                <?php
                if($guiderInfo->dbkl_status == 1){
                    echo '<span class="label label-success pull-right">Approved</span>';
                }else if($guiderInfo->dbkl_status == 2){
                    echo '<span class="label label-warning pull-right">In Review</span>';
                }else if($guiderInfo->dbkl_status == 3){
                    echo '<span class="label label-danger pull-right">Rejected</span>';
                }else{
                    echo '<span class="label label-default pull-right">NIL</span>';
                }
                ?>
            </div>
            <div class="col-md-12">
                <h5 class="box-title"><b>DBKL License</b></h5>
                <div class="img-view">
                    <?php if($guiderInfo->dbkl_lic){ 
                        $dbkl_lic = $upload_path_url.'dbkl/'.$guiderInfo->dbkl_lic;
                        ?>
                        <a class="example-image-link" data-lightbox="example-set">
                            <img class="img-thumbnail" src="<?=$dbkl_lic; ?>" id="client_picture" style="height: auto;width: auto;" data-src="#" />
                        </a>
                    <?php } else { ?>
                        <img class="img-thumbnail" src="<?=$dirUrl; ?>uploads/no_image.png" id="client_picture" style="height:100px;width: auto;" data-src="#" />
                    <?php } ?>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group row">
                    <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">DBKL license No</label>
                    <div class="col-sm-9">
                        <div><span class="label label-primary label-sm"><?= ($guiderInfo->dbkl_lic_no)? $guiderInfo->dbkl_lic_no : '-'; ?></span></div>
                    </div>
                </div>
            </div>
            <?php
            if($guiderInfo->dbkl_status == 1 || $guiderInfo->dbkl_status == 2){
            ?>
            <div class="col-md-12 form-actions">
                <div class="pull-right">
                    <a href="javascript:;" class="btn btn-success btn-sm" onclick="return updateDbklStatus(<?=$guider_id;?>,1);">Approve</a>
                    <a href="javascript:;" class="btn btn-danger btn-sm" onclick="return updateDbklStatus(<?=$guider_id;?>,3);">Reject</a>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
    
</div>
<script type="text/javascript">
function updateDbklStatus( guider_id, status ){
    var data = 'guider_id='+guider_id+'&status='+status;
    $.ajax( {
        type: "POST",
        data: data,
        url: adminurl + 'guider/updateDbklStatus',
        success: function( msg ) {
            window.location.reload();
        }
    });
    return false;
}
</script>