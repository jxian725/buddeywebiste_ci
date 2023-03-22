<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl       = $this->config->item( 'admin_url' );
$site_name      = $this->config->item( 'site_name' );
global $permission_arr;
?>
<style type="text/css">
#imgNewsletter { display:none;}
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
                              <input type="text" required="" maxlength="20" placeholder="Max 20 characters" id="title" class="form-control" name="title">
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-md-2 control-label">Upload image</label>
                            <div class="col-md-3">
                              <a href="javascript:void(0);">
                                <img src="<?=$this->config->item( 'dir_url' ); ?>uploads/default_img.png" id="newsletter_picture" style="height: 75px;width: 50%;" data-src="#" /> <br />
                                <input type='file' id="imgNewsletter" accept="image/*" />
                              </a>
                              <input type="hidden" id="newsletter_img" name="newsletter_img"/>
			                        <input type="hidden" id="newsletter_image" name="newsletter_image"/>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-md-2 control-label" for="video_url">Video Url</label>
                            <div class="col-md-8">
                              <input type="text" required="" placeholder="Enter Video Url" maxlength="100" id="video_url" class="form-control" name="video_url">
				                      <span class="label label-primary">https://www.youtube.com/watch?v=9Wvf5GTfNew</span>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-md-2 control-label" for="description">Description</label>
                            <div class="col-md-8">
                              <textarea id="description" class="form-control" name="description"></textarea>
                            </div>
                          </div>
                          <div class="col-md-offset-2 col-md-8 text-right">
                            <button class="btn btn-sm btn-info" id="addnewslettersubmit" onClick="return validateNewsletter();" type="button">Post</button>
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
                                                <?php if( in_array( 'newsletter/delete_newsletter', $permission_arr ) ) { ?>
                                                <a class="btn btn-danger btn-xs" href="javascript:;" onclick="return delete_newsletter( '<?=$value->newsletter_id;?>' );">Delete</a> 
                                                <?php } ?>
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
$(document).ready(function(){
  $("#newsletter_picture").on('click', function() {
    $("#imgNewsletter").trigger('click');
  });
  var base_url = "<?php echo $assetUrl; ?>";
    
  formdata = new FormData();
  $("#imgNewsletter").change(function(){
    // readURL(this);
    var file = this.files[0];
    var file_size = file.size/1024/1024;
    if(file_size < 4){
      formdata.append("image", file);
      var xhr = new XMLHttpRequest();
      $('#message_newsletter').empty();
      xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) {
          
          var myArr12 = JSON.parse(xhr.responseText);
          if(myArr12) {
            if(myArr12['error']){
              $('#message_newsletter').append( '<span class="text-danger">'+myArr12['error']+'<span>' );
              $('#imgNewsletter').focus();
              return false;
            }
            if(myArr12['success']){
              $('#message_newsletter').empty();
              $("#newsletter_img").val(myArr12['ProfilePic']);
              $("#newsletter_image").val(myArr12['ProfilePicture']);
              $("#newsletter_picture").attr("src", myArr12['ProfilePic']);
            }
          }
        }
      }
      xhr.open("POST", adminurl +"newsletter/profileUpload");
      xhr.send(formdata);
    }else{
      alert('File too large. File must be less than 4 MB.');
    }
    });
});
</script>