<?php 
$assetUrl   = $this->config->item( 'base_url' ); 
?>
<div class="row">
  <form novalidate="" id="urlForm" role="form" method="post" enctype="multipart/form-data">  
    <div class="col-md-12">     
      <?php 
          if($field == 0){ ?>
            <div class="form-group">
              <input type="text" name="url_id" id="url_id" class="form-control">
            </div>
            <div class="form-group">
                 <b>e.g.</b> <span class="label label-primary">https://www.youtube.com/embed/tgbNymZ7vqY</span>
            </div>
            <div class="form-group">
              <textarea name="description" id="description" class="form-control" rows="3"></textarea>
            </div>
            <div class="box-footer">
      <?php
        }
      ?>       
      <?php
      if($field == 0){ 
      ?>
      <div class="form-group">
        <input type="hidden" name="id" id="id" value="<?=$id;?>">
        <input type="hidden" name="field" id="field" value="<?=$field;?>">
        <a href="javascript:;" class="btn btn-success btn-sm" onclick="return updateTalentField();">Add</a>
        <button type="button" id="defaultOpen" data-dismiss="modal" class="btn btn-danger btn-sm">Cancel</button>
      </div>
      <?php
      }
      ?> 
    </div>
  </form>
</div>
<script type="text/javascript">
function updateTalentField() {
  var data          = $( 'form#urlForm' ).serialize();
  var field         = $( 'form#urlForm #field' ).val();
  var url_id        = $( 'form#urlForm #url_id' ).val();
  var description   = $( 'form#urlForm #description' ).val();
  if(url_id == ''){
    toastr.error( 'Please Enter Youtube Url','Error' );
    return false;
  }else if(description == ''){
    toastr.error( 'Please Enter Description','Error' );
    return false;
  }
  if(url_id && description){
    $.ajax( {
        type    : "POST",
        data    : data,
        url: baseurl + 'talent/Venue/updateUrl',
        success: function( data ) {
          toastr.success( 'Youtube Url Updated Successfully.','Success' );
          setTimeout( function() {
            location.reload();
          }, 1000 ); 
        }
    });
  }  
  return false;
}
</script>