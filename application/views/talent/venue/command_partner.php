<?php 
$assetUrl   = $this->config->item( 'base_url' ); 
?>
<div class="row">
  <form novalidate="" id="partnerForm" role="form" method="post" enctype="multipart/form-data">  
    <div class="col-md-12">     
      <?php 
          if($field == 0){ ?>
            <div class="form-group">
              <textarea name="command" id="command" class="form-control" rows="3"></textarea>
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
        <a href="javascript:;" class="btn btn-success btn-sm" onclick="return updatePartnerField();">Done</a>
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
  var command       = $( 'form#partnerForm #command' ).val();
  if(command == ''){
    toastr.error( 'Please Comment Your Partner ','Error' );
    return false;
  }
  if(command){
    $.ajax( {
        type    : "POST",
        data    : data,
        url: baseurl + 'partner/Venue/updatePartner',
        success: function( data ) {
          toastr.success( 'Comment Updated Successfully.','Success' );
          document.getElementById("defaultOpen").click();
          var table = $('#pod_list').DataTable();
          table.draw('page');
        }
    });
  }  
  return false;
}
</script>