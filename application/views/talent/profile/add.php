<?php 
$assetUrl   = $this->config->item( 'base_url' );
$upload_path_url = $this->config->item( 'upload_path_url' );
?>
<div class="row">
  <form novalidate="" id="profileForm" role="form" method="post" enctype="multipart/form-data">  
    <div class="col-md-12">     
      <div class="form-group">
        <input type='file' class="form-control" name="profile_image" id="profile_image" accept="image/*" />
        <small>Only JPG, PNG and GIF files are allowed</small>
      </div>
      <div class="box-footer">
        <div class="form-group">
          <input type="hidden" name="id" id="id" value="<?=$id;?>">
          <input type="hidden" name="field" id="field" value="<?=$field;?>">
          <button class="btn btn-success btn-sm" id="updateprofilesubmit" type="submit">Upload</button>
          <button type="button" id="defaultOpen" data-dismiss="modal" class="btn btn-danger btn-sm">Cancel</button>
        </div>
      </div>
  </form>
</div>

<script type="text/javascript">
$("#profileForm").on('submit', function(e){
    var data          = $( 'form#profileForm' ).serialize();
    var field         = $( 'form#profileForm #field' ).val();
    var profile_image = $( 'form#profileForm #profile_image' ).val();
    if(profile_image == ''){
      toastr.error( 'Please select profile image','Error' );
      return false;
    }
    if(profile_image){
      $.ajax({
        type: "POST",
        url: baseurl + 'talent/profile/updateProfile',
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData:false,
        beforeSend: function() { 
            $("#updateprofilesubmit").html('<img src="'+baseurl+'assets/img/input-spinner.gif"> Loading...');
            $("#updateprofilesubmit").prop('disabled', true);
            $('#profileForm').css("opacity",".5");
        },
        success: function( data ) {
          setTimeout( function() {
            location.reload();
          }, 1000 ); 
          // $("#updateprofilesubmit").html('Upload');
          // $("#updateprofilesubmit").prop('disabled', false);
          // $("form#profileForm").trigger("reset");
          // $('#profileForm').css("opacity","");
        }
      });
    }  
    return false;
});
</script>
<!--  img.png -->
<script type="text/javascript">
  $("input[type='file']").on("change", function () {
     if(this.files[0].size > 5000000) {
       alert("Please upload file less than 5MB. Thanks!!");
       $(this).val('');
     }
    });
</script>
<!-- end img -->