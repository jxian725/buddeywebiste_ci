<?php 
$assetUrl   = $this->config->item( 'base_url' ); 
?>
<div class="row">
  <form novalidate="" id="partnerForm" role="form" method="post" enctype="multipart/form-data">  
    <div class="col-md-12">     
      <?php 
          if($field == 0){ ?>
             <div class="form-group">
              <label class="control-label" for="subject">Subject</label>
              <input name="subject" id="subject" class="form-control" >
            </div>
            <div class="form-group">
              <label class="control-label" for="description">Description</label>
              <textarea name="description" id="description" class="form-control" rows="3"></textarea>
            </div>
      <?php
        }
      ?>       
      <?php
      if($field == 0){ 
      ?>
      <div class="form-group">
        <input type="hidden" name="support_id" id="support_id" value="<?=$support_id;?>">
        <input type="hidden" name="venuepartner_id" id="venuepartner_id" value="<?= $responseInfo->venuepartner_id; ?>">
        <input type="hidden" name="field" id="field" value="<?=$field;?>">
        <a href="javascript:;" class="btn btn-success btn-sm" onclick="return updatePartnerField();">Sent</a>
        <button type="button" id="defaultOpen" data-dismiss="modal" class="btn btn-danger btn-sm">Cancel</button>
      </div>
      <?php
      }
      ?> 
    </div>
  </form>
</div>
<script type="text/javascript">
function updatePartnerField() {
  var data          = $( 'form#partnerForm' ).serialize();
  var field         = $( 'form#partnerForm #field' ).val();
  var description   = $( 'form#partnerForm #description' ).val();
  var subject       = $( 'form#partnerForm #subject' ).val();
  if(subject == null || subject == ''){
      toastr.error('Subject cannot be empty','Error');
      return false;
    }
    if(description == ''){
      toastr.error('Description cannot be empty','Error');
      return false;
    }
  if(subject && description){
    $.ajax( {
        type    : "POST",
        data    : data,
        url: adminurl + 'feedback/updateFeedback',
        success: function( data ) {
          toastr.success( 'Feedback Response Sent Successfully.','Success' );
          setTimeout( function() {
            location.reload();
          }, 2000 );
        }
    });
  }  
  return false;
}
</script>