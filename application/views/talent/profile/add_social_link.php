<?php 
$assetUrl = $this->config->item( 'base_url' ); 
?>
<div class="row">
  <form novalidate="" id="urlForm" role="form" method="post" enctype="multipart/form-data">  
    <div class="col-md-12">
      <div class="form-group">
        <label for="website_link">Enter Website Link</label>
        <input type="text" name="website_link" id="website_link" value="<?=($socialLinkInfo)? $socialLinkInfo->website_link : '' ?>" class="form-control">
      </div>
      <div class="form-group">
        <label for="fb_link">Enter Facebook Link</label>
        <input type="text" name="fb_link" id="fb_link" value="<?=($socialLinkInfo)? $socialLinkInfo->fb_link : '' ?>" class="form-control">
      </div>
      <div class="form-group">
        <label for="twitter_link">Enter Twitter Link</label>
        <input type="text" name="twitter_link" id="twitter_link" value="<?=($socialLinkInfo)? $socialLinkInfo->twitter_link : '' ?>" class="form-control">
      </div>
      <div class="form-group">
        <label for="gplus_link">Enter Google+ Link</label>
        <input type="text" name="gplus_link" id="gplus_link" value="<?=($socialLinkInfo)? $socialLinkInfo->gplus_link : '' ?>" class="form-control">
      </div>
      <div class="form-group">
        <label for="behance_link">Enter Behance Link</label>
        <input type="text" name="behance_link" id="behance_link" value="<?=($socialLinkInfo)? $socialLinkInfo->behance_link : '' ?>" class="form-control">
      </div>
      <div class="form-group">
        <label for="pinterest_link">Enter Pinterest Link</label>
        <input type="text" name="pinterest_link" id="pinterest_link" value="<?=($socialLinkInfo)? $socialLinkInfo->pinterest_link : '' ?>" class="form-control">
      </div>
      <div class="form-group">
        <label for="instagram_link">Enter Instagram Link</label>
        <input type="text" name="instagram_link" id="instagram_link" value="<?=($socialLinkInfo)? $socialLinkInfo->instagram_link : '' ?>" class="form-control">
      </div>
      <div class="form-group">
        <label for="youtube_link">Enter Youtube Link</label>
        <input type="text" name="youtube_link" id="youtube_link" value="<?=($socialLinkInfo)? $socialLinkInfo->youtube_link : '' ?>" class="form-control">
      </div>
      <div class="box-footer">
        <div class="form-group">
          <input type="hidden" name="id" id="id" value="<?=($socialLinkInfo)? $socialLinkInfo->id : '' ?>">
          <a href="javascript:;" class="btn btn-success btn-sm" onclick="return updateSocialLink();">Update</a>
          <button type="button" id="defaultOpen" data-dismiss="modal" class="btn btn-danger btn-sm">Cancel</button>
        </div>
      </div>
  </form>
</div>
<script type="text/javascript">
function updateSocialLink() {
  var data          = $( 'form#urlForm' ).serialize();
  // var url_link      = $( 'form#urlForm #url_link' ).val();
  // if(url_link == ''){
  //   toastr.error( 'Please Enter Youtube Url','Error' );
  //   return false;
  // }
  $.ajax( {
      type    : "POST",
      data    : data,
      url: baseurl + 'talent/profile/updateSocialLink',
      success: function( data ) {
        location.reload();
      }
  });
  return false;
}
</script>