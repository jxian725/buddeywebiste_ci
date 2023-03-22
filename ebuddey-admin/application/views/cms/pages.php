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
                      <div id="message_cms"></div>
                      
                      <div class="box-body table-responsive no-padding">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>SI No</th>
                                    <th>Page Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="newsletter_lists">
                                <?php
                                if( $page_lists ) {
                                    $i = 1;
                                    foreach ( $page_lists as $key => $value ) {
                                        ?>
                                        <tr>
                                            <td><?=$i;?></td>
                                            <td><?=rawurldecode($value->title);?></td>
                                            <td>
                                                <?php if( in_array( 'cms/add', $permission_arr ) ) {
                                                  if($value->page_key == 'venue_partner'){ ?>
                                                    <a class="btn btn-primary btn-xs" href="<?php echo base_url(); ?>cms/pages/venue_partner">Edit</a>
                                                  <?php }else{ ?>
                                                    <a class="btn btn-primary btn-xs" href="<?php echo base_url(); ?>cms/pages/edit/<?=$value->page_id;?>">Edit</a>
                                                  <?php }
                                                }
                                                if( in_array( 'cms/delete', $permission_arr ) ) { ?>
                                                
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php
                                        $i++;
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