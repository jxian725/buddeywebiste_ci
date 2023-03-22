<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl       = $this->config->item( 'admin_url' );
$site_name      = $this->config->item( 'site_name' );
global $permission_arr;

$image_url = $this->config->item( 'dir_url' ).'uploads/default_img.png';
$newsletter_id = '';
if($newsletterInfo){
  $newsletter_id = $newsletterInfo->newsletter_id;
  if($newsletterInfo->image){
    $image_url = $newsletterInfo->image;
  }
}
?>
<style type="text/css">
#newsletterImg { display:none;}
</style>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                      <div id="message_newsletter"></div>
                      <?php if( in_array( 'newsletter/add', $permission_arr ) ) { ?>
                      <form novalidate="" id="newsletter_form" role="form" method="post" class="form-horizontal">
                        <div class="box-body">
                          <div class="form-group">
                            <label class="col-md-2 control-label" for="title">Title <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                              <input type="text" required="" maxlength="20" placeholder="Max 20 characters" id="title" class="form-control" name="title" value="<?= ($newsletterInfo)? rawurldecode($newsletterInfo->title) : ''; ?>">
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-md-2 control-label">Upload image</label>
                            <div class="col-md-3">
                              <a href="javascript:void(0);">
                                  <img src="<?=$image_url; ?>" id="newsletterPlaceImg" style="height: 75px;width: 50%;" data-src="#" />
                                <input type='file' onchange="displayUploadImg(this, 'newsletterPlaceImg');" name="newsletterImg" id="newsletterImg" accept="image/*" />
                              </a>
                              <input type="hidden" id="newsletter_img" name="newsletter_img"/>
			                        <input type="hidden" id="newsletter_image" name="newsletter_image"/>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-md-2 control-label" for="video_url">Video Url</label>
                            <div class="col-md-8">
                              <input type="text" required="" placeholder="Enter Video Url" maxlength="100" id="video_url" class="form-control" name="video_url" value="<?= ($newsletterInfo)? rawurldecode($newsletterInfo->video_url) : ''; ?>">
				                      <span class="label label-primary">https://www.youtube.com/watch?v=9Wvf5GTfNew</span>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-md-2 control-label" for="description">Description</label>
                            <div class="col-md-8">
                              <textarea id="description" class="form-control" name="description"><?= ($newsletterInfo)? rawurldecode($newsletterInfo->description) : ''; ?></textarea>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-offset-2 col-md-8 text-right">
                              <input type="hidden" id="newsletter_id" name="newsletter_id" value="<?=$newsletter_id;?>">
                              <button class="btn btn-sm btn-info" id="addnewslettersubmit" type="submit"><?= ($newsletterInfo)? 'Update Newsletter' : 'Add Newsletter'; ?></button>
                            </div>
                          </div>
                        </div>
                      </form>
                      <?php } ?>
                      <div class="box-body table-responsive no-padding">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Video Url</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="newsletter_lists">
                                <?php
                                if( $newsletter_lists ) {
                                    foreach ( $newsletter_lists as $key => $value ) {
                                        if( $value->image ) {
                                            $image = $value->image;
                                        } else {
                                            $image = $this->config->item( 'dir_url' ).'uploads/no_image.png';
                                        } 
                                        ?>
                                        <tr>
                                            <td>
                                               <img src="<?=$image;?>" alt="Buddey" style="width: 80px;"> 
                                            </td>
                                            <td><?=rawurldecode($value->title);?></td>
                                            <td><div class="newsletter_desc"><?=rawurldecode( $value->description );?></div></td>
                                            <td><a href="<?=rawurldecode( $value->video_url );?>" target="_blank"><?=rawurldecode( $value->video_url );?></a></td>
                                            <td>
                                                <?php
                                                if( in_array( 'newsletter', $permission_arr ) ) { ?>
                                                <a class="btn btn-success btn-xs" href="<?php echo $assetUrl; ?>newsletter/view/<?=$value->newsletter_id;?>"><i class="fa fa-eye"></i></a> 
                                                <?php 
                                                }
                                                if( in_array( 'newsletter/delete_newsletter', $permission_arr ) ) { ?>
                                                <a class="btn btn-danger btn-xs" href="javascript:;" onclick="return delete_newsletter( '<?=$value->newsletter_id;?>' );"><i class="fa fa-trash-o"></i></a> 
                                                <?php
                                                }
                                                if( in_array( 'newsletter/delete_newsletter', $permission_arr ) ) { ?>
                                                <a class="btn btn-info btn-xs" href="<?php echo $assetUrl; ?>newsletter?newsletter_id=<?=$value->newsletter_id;?>"><i class="fa fa-edit"></i></a> 
                                                <?php 
                                                } ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?=$this->config->item( 'dir_url' );?>js/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
    tinymce.init({
    selector: '#description',
    height: 100,
    menubar: false,
    plugins: [
        'advlist autolink lists link image charmap print preview anchor textcolor',
        'searchreplace visualblocks code fullscreen',
        'insertdatetime media table contextmenu paste code help wordcount'
    ],
    toolbar: 'insert | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
    content_css: [
        '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
        '//www.tinymce.com/css/codepen.min.css']
    });
</script>
<script type="text/javascript">
    function delete_newsletter( newsletterID ) {
      $( '#myModal .modal-title' ).html( 'Confirm' );
      $( '#myModal .modal-body' ).html( 'Are you sure want to delete the newsletter ?' );
      $( '#myModal .modal-body' ).append( '<br /><br /><button class="btn btn-info btn-sm" id="continuemodal" data-dismiss="modal">Yes</button>&nbsp;&nbsp;<button aria-hidden="true" data-dismiss="modal" class="btn btn-danger btn-sm">Cancel</button>' );
      $( '#myModal' ).modal({ backdrop: 'static', keyboard: false }); 
      $( "#continuemodal" ).click(function() {
          var account_data    = 'newsletterID=' + newsletterID;
          $.ajax({
            type: "POST",
            url: adminurl + 'newsletter/delete_newsletter',
            data: account_data,
            success: function( data ) {
              toastr.success( 'Newsletter deleted successfully.','Success' );
              setTimeout( function() {
                window.location.href = adminurl + 'newsletter';
              }, 2000 );
            }
          });
      });    
      return false;
    }
</script>
<script type="text/javascript">
function displayUploadImg(input, PlaceholderID) {
  if (input.files && input.files[0]) {
    var upfile = input.files[0];
    var imagefile = upfile.type;
    var match= ["image/jpeg","image/png","image/jpg"];
    if(!((imagefile==match[0]) || (imagefile==match[1]) || (imagefile==match[2]))){
        alert('Please select a valid image file (JPEG/JPG/PNG).');
        $("#"+input.id).val('');
        return false;
    }
    var file_size = upfile.size/1024/1024;
    if(file_size < 5){
      var reader = new FileReader();
      reader.onload = function (e) {
        $('#'+PlaceholderID)
            .attr('src', e.target.result)
            .width('auto')
            .height(160);
        };
      reader.readAsDataURL(upfile);
    }else{
      alert('File too large. File must be less than 5 MB.');
      $("#"+input.id).val('');
      return false;
    }
  }
}

$(document).ready(function(){
  $("#newsletterPlaceImg").on('click', function() {
    $("#newsletterImg").trigger('click');
  });

  $("#newsletter_form").on('submit', function(e){
    e.preventDefault();
    var title = $( '#newsletter_form #title' ).val();
    var newsletter_id = $( '#newsletter_form #newsletter_id' ).val();
    if(title == ''){
      toastr.error('Title Cannot be empty','Error');
      return false;
    }
    if(title){
        $.ajax({
          type: "POST",
          url: adminurl + 'newsletter/newsletterValidate',
          data: new FormData(this),
          contentType: false,
          cache: false,
          processData:false,
          beforeSend: function() { 
              $("#addnewslettersubmit").html('<img src="'+adminurl+'assets/img/input-spinner.gif"> Loading...');
              $("#addnewslettersubmit").prop('disabled', true);
              $('#newsletter_form').css("opacity",".5");
          },
          success: function( data ) {
            if(data == 1){
              toastr.success('Newsletter Posted Successfully.','Success');
              $("form#newsletter_form").trigger("reset");
              window.location.href = adminurl + "newsletter";
            }else{
              toastr.error( data,'Error' );
            }
            $("#addnewslettersubmit").prop('disabled', false);
            if(newsletter_id){
              $("#addnewslettersubmit").html('Update Newsletter');
            }else{
              $("#addnewslettersubmit").html('Add Newsletter');
            }
            $('#newsletter_form').css("opacity","");
          }
        });
    }
  });
});
</script>