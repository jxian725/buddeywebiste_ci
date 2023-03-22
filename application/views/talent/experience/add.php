<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 
$adminUrl   = $this->config->item( 'admin_dir_url' );
$dirUrl     = $this->config->item( 'dir_url' );
?>
<link rel="stylesheet" href="<?= $adminUrl; ?>plugins/select2/select2.min.css">
<script src="<?= $adminUrl; ?>plugins/select2/select2.full.min.js"></script>
<style type="text/css">
h3{ font-size: 18px; }
.box.box-primary {
    border-top-color: #ffffff;
}
.btn-default.btn-on-1.active{background-color: #4da75b;color: white;}
.btn-default.btn-off-1.active{background-color: #DA4F49;color: white;}
.select2-container--default .select2-selection--single {
    height: 34px;
}
</style>

<div class="count_box row">
    <div class="col-md-12">
        <div class="box-header with-border">
            <h2 class="box-title">Manage Talent Experiences</h2>
            <div class="box-tools pull-right">
                <a href="<?= base_url().'talent/experience/view'; ?>" class="btn btn-info btn-sm">Back</a>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-body">
                <div id="experience_sec">
                    <div class="row">
                        <div class="clearfix"></div>
                        <form id="experience_form" method="post" role="form">
                            <div class="col-md-8">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="experience_title">Give your talent experiences a title (max 80 words)</label>
                                        <input type="text" class="form-control" name="experience_title" id="experience_title" placeholder="Title">
                                    </div>
                                    <div class="form-group">
                                        <label for="skills_category">Category</label>
                                        <select class="form-control select2" name="skills_category" id="skills_category">
                                            <option value="">Select skills category</option>
                                            <?php
                                            if( $specialization_lists ) {
                                               foreach ( $specialization_lists as $key => $value ) {
                                                ?><option value="<?= $value->specialization_id; ?>"><?=rawurldecode( $value->specialization );?></option><?php
                                               }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Where will you be hosting your talent experiences?</label>
                                        <select class="form-control select2" name="city" id="city">
                                            <option value="">Select city</option>
                                            <?php 
                                            if($cityLists) { 
                                              foreach ( $cityLists as $key => $cityInfo ) {
                                                echo '<option value="'. $cityInfo->id .'">'.$cityInfo->name.'</option>';
                                              }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                      <label for="languages_known">Language</label>
                                      <select data-placeholder="Select language" style="width: 100%;" name="languages_known[]" id="languages_known" class="form-control select2" multiple="multiple">
                                        <?php
                                        if($getTalentLangLists){
                                          foreach ($getTalentLangLists as $key => $lang) { ?>
                                            <option value="<?= $lang->lang_id; ?>"><?= $lang->language; ?></option>
                                          <?php
                                          }
                                        }
                                        ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="about_us">Tell us more about the whole experiences you will provided (Max 300 words)</label>
                                        <textarea name="about_us" id="about_us" class="form-control" rows="3" placeholder="Enter ..."></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>What are the requirement or preparation for someone to book your talent experiences?(Max 300 words)</label>
                                        <textarea name="requirement" id="requirement" class="form-control" rows="3" placeholder="Enter ..."></textarea>
                                    </div>
                                    <div class="form-group">
                                      <label for="video_link">Youtube Video Link</label>
                                      <input type="text" class="form-control" name="video_link" id="video_link" placeholder="https://www.youtube.com/watch?v=0LeyrustvcY">
                                      <p class="help-block">We require all talent experience to have an introduction video of 1 to 3 minutes.</p>
                                      <p class="help-block">You must be in the video</p>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="price_rate">What is your price rate per 15 minutes?</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">RM</span>
                                            <input type="text" name="price_rate" id="price_rate" maxlength="6" class="form-control number">
                                            <span class="input-group-addon">.00</span>
                                        </div>
                                        <p class="help-block">Per booking slot is 15 minutes, pricing is RM15.00 minimum and RM50.00 maximum</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group pull-right">
                                    <button id="expSubmitBtn" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
  $('.select2').select2();
});
$(document).ready(function(){
    $("#experience_form").on('submit', function(e){
        e.preventDefault();

        var skills_category = $( '#experience_form #skills_category' ).val();
        var experience_title= $( '#experience_form #experience_title' ).val();
        var video_link      = $( '#experience_form #video_link' ).val();
        var price_rate      = $( '#experience_form #price_rate' ).val();
        var city            = $( '#experience_form #city' ).val();

        if(skills_category == null || skills_category == ''){
          toastr.error('Skills Category cannot be empty','Error');
          return false;
        }
        if(experience_title == ''){
          toastr.error('Experience title cannot be empty','Error');
          return false;
        }
        if(city == ''){
          toastr.error('City cannot be empty','Error');
          return false;
        }
        if(video_link == ''){
          toastr.error('Video link cannot be empty','Error');
          return false;
        }
        if(price_rate == ''){
          toastr.error('Price cannot be empty','Error');
          return false;
        }else{
            if(!$.isNumeric(price_rate)){
                toastr.error('Price must be a numeric','Error');
                return false;
            }else{
                if(price_rate < 15 || price_rate > 50){
                    toastr.error('Price must be between RM15.00 to RM50.00','Error');
                    return false;
                }
            }
        }
        
        if(skills_category && experience_title && video_link && price_rate && city){
            $.ajax({
              type: "POST",
              url: baseurl + '/talent/experience/addExperience',
              data: new FormData(this),
              contentType: false,
              cache: false,
              processData:false,
              beforeSend: function() { 
                  $("#expSubmitBtn").html('<img src="<?php echo $adminUrl;?>img/loading.gif" style="height:20px;"> Loading...');
                  $("#expSubmitBtn").prop('disabled', true);
                  $('#experience_form').css("opacity",".5");
              },
              success: function( data ) {
                if(data == 1){
                  $("#expSubmitBtn").html('Update');
                  $("#expSubmitBtn").prop('disabled', false);
                  toastr.success( 'Talent experience updated successfully.','Success' );
                  //$("form#experience_form").trigger("reset");
                  location.reload();
                }else{
                  toastr.error( data,'Error' );
                  $("#expSubmitBtn").html('Update');
                  $("#expSubmitBtn").prop('disabled', false);
                }
                $('#experience_form').css("opacity","");
              }
            });
        }
    });
});
</script>