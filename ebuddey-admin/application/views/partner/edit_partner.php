<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl   = $this->config->item( 'admin_url' );
$dirUrl     = $this->config->item( 'dir_url' );
$upload_path_url = $this->config->item( 'upload_path_url' );

if($partnerInfo->photo){
  $photo = '<img class="img-thumbnail" src="'.$upload_path_url.'partner/'.$partnerInfo->photo.'" style="height: auto;width: 60px;" data-src="#" />';
}else{
  $photo = '';
}
if($partnerInfo->dbkl_lic_enable == 1){
    $dbkl_checked = 'checked';
}else{
    $dbkl_checked = '';
}

?>
<link rel="stylesheet" href="<?= $dirUrl; ?>plugins/select2/select2.min.css">
<script src="<?= $dirUrl; ?>plugins/select2/select2.full.min.js" defer></script>
<link rel="stylesheet" href="<?= $dirUrl; ?>plugins/iCheck/square/blue.css">
<script src="<?=$dirUrl;?>js/tinymce/tinymce.min.js"></script>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-8">
                      <div id="message_partner"></div>
                      <form novalidate="" id="partner_form" role="form" method="post" enctype="multipart/form-data">
                        <div class="row">
                          <div class="col-sm-12">  
                            <div class="form-group">
                              <label for="partner_name">Partner Name</label><b class="text-danger">*</b>
                              <input type="text" value="<?= rawurldecode( $partnerInfo->partner_name ); ?>" class="form-control" name="partner_name" id="partner_name" />
                            </div>
                          </div>

                          <div class="col-sm-6">
                            <div class="form-group">
                              <label class="control-label" for="city_id">City <span class="text-danger">*</span></label>
                              <select class="form-control" id="city_id" name="city_id">
                                <option value="">Select</option>
                                <?php if( $stateList ) {
                                  foreach ( $stateList as $key => $value2 ) {
                                    echo '<option '.(($value2->id==$partnerInfo->city_id)? 'selected':'').' value="'.$value2->id.'">'.$value2->name.'</option>';
                                  }
                                }
                                ?>
                              </select>
                            </div>
                          </div>

                          <div class="col-sm-6">
                            <div class="form-group">
                              <label class="control-label" for="fees">Fees (RM)</label>
                              <input type="text" placeholder="Fees" id="fees" class="form-control number" name="fees" value="<?= $partnerInfo->fees; ?>">
                            </div>
                          </div>

                          <div class="col-sm-6">
                            <div class="form-group">
                              <label class="control-label" for="photo">Photo</label>
                              <input type="file" id="photo" name="photo" class="form-control">
                              <div><?= $photo; ?></div>
                            </div>
                          </div>

                          <div class="col-sm-6">
                            <div class="form-group">
                              <label class="control-label" for="license">Required verification</label>
                              <select class="form-control select2" multiple="multiple" data-placeholder="Select verification" name="license[]" id="license" style="width: 100%;" tabindex="-1">
                                <?php 
                                if($licenseLists){
                                  $licenseArr  = explode(',', $partnerInfo->required_license);
                                  foreach ($licenseLists as $license) {
                                    ?>
                                    <option <?php if(in_array($license->license_id, $licenseArr)){ echo 'selected'; } ?> value="<?php echo $license->license_id; ?>"><?php echo $license->license_name; ?></option>
                                    <?php
                                  }
                                }
                                ?>
                              </select>
                            </div>
                          </div>

                          <div class="col-sm-12">
                            <div class="form-group">
                              <label class="control-label" for="address">Address</label>
                              <textarea type="text" rows="6" placeholder="Address" id="address" class="form-control address2" name="address"><?= $partnerInfo->address; ?></textarea>
                            </div>
                          </div>

                          <div class="col-sm-12">
                            <div class="form-group">
                              <div class="checkbox">
                              <label><input type="checkbox" <?= $dbkl_checked; ?> name="dbkl_lic_enable" id="dbkl_lic_enable" value="1">&nbsp;&nbsp;DBKL license Required</label>
                            </div>
                            </div>
                          </div>
                          <div class="col-sm-12">  
                            <div class="clearfix"></div>
                            <input type="hidden" name="partner_id" value="<?= $partnerInfo->partner_id; ?>" />
                            <input type="submit" id="update-category" value="Update Partner" class="btn btn-success">
                            <a href="<?= base_url();?>partners" class="btn btn-danger">Back</a>
                          </div>  
                        </div>
                      </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= $dirUrl; ?>plugins/iCheck/icheck.min.js"></script>
<script>
// Prevent Bootstrap dialog from blocking focusin
$(document).on('focusin', function(e) {
  if ($(e.target).closest(".tox-tinymce-aux, .moxman-window, .tam-assetmanager-root").length) {
    e.stopImmediatePropagation();
  }
});
tinymce.init({
    selector: 'textarea#address',
    height: 250,
    menubar: false,
    plugins: 'code',
    toolbar: 'styleselect | undo redo | code | bold italic backcolor'
});

  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
<script type="text/javascript">
    //partner Validation
    function validatePartner() {
        var $btn  = $( '#addpartnersubmit' );
        $btn.button( 'Posting..' );
        var partner_name = $('#partner_name').val();
        var city_id  = $('#city_id').val();
        if(partner_name == ''){
          toastr.error('The Partner Name field is required.','Error');
        }else if(city_id == ''){
            toastr.error('The City field is required.','Error');
        }else{
          $( '#partner_form' ).submit();
        }
    }
    function editPartner( partner_id ) {
        $(document).on('focusin', function(e) {
          if ($(e.target).closest(".mce-window").length) {
            e.stopImmediatePropagation();
          }
        });
        $.ajax( {
            type: "POST",
            url: adminurl + 'partners/editPartnerForm',
            data    : 'partner_id=' + partner_id,
            success: function( msg ) {
              $( '#myModal .modal-title' ).html( 'Edit Partner' );
              $( '#myModal .modal-body' ).html( msg );
              $( '#myModal .modal-footer' ).html( '' );
              $( '#myModal' ).modal('show');
            }
        });
        return false;
    }
    $(document).ready(function(){
      $('.select2').select2();
    });
</script>