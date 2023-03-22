<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 
$assetUrl   = $this->config->item( 'admin_url' );
$adminUrl   = $this->config->item( 'admin_dir_url' );
$dirUrl     = $this->config->item( 'dir_url' );

?>
<style type="text/css">
.placeImg { display:none !important;}
</style>
<div class="row">
    <div class="col-md-12 col-sm-12"> 
        <div class="box box-primary">
            <div class="box-header">
            </div>
		    <form novalidate="" id="feedback_form" role="form" method="post" class="form-horizontal">
		        <div class="box-body">
                    <div class="row">
		                <div class="col-md-10">
			                <div class="form-group">
			                    <label class="col-md-2 control-label" for="subject">Subject</label>
				                <div class="col-md-10">
				                    <input type="text" required="" id="subject" maxlength="60" class="form-control" name="subject">
				                </div>
				              </div>
			                <div class="form-group">
			                    <label class="col-md-2 control-label" for="description">Feedback</label>
			                    <div class="col-md-10">
			                        <textarea type="text" row="5" id="description" class="form-control" name="description"></textarea>
			                    </div>
			                </div>
                      <div class="col-md-offset-11 col-md-12">
                        <button class="btn btn-info" id="feedbacksubmit" type="submit">Sent</button>
                      </div>
		                </div>
		            </div>
		        </div>
		        <!-- /.box-body -->
            <?php if($venuepartnerInfo){ 
             foreach ($venuepartnerInfo as $venuepartner) {
            ?>
            <?php if($venuepartner->feedback_status == 1){ ?>
              <div class="box-footer">
                <div class="col-md-offset-1 col-md-8"> 
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <div><?=$venuepartner->venuepartner_name; ?></div>
                        <div><?= date('d M Y H:i A', strtotime($venuepartner->createdon)); ?></div>
                        <div><?=$venuepartner->description; ?></div>
                      </div>
                    </div>
                  </div>
                </div>  
              </div>
            <?php }elseif($venuepartner->feedback_status == 2){ ?>
              <div class="box-body">
                <div class="col-md-offset-1 col-md-8"> 
                  <div class="direct-chat-messages">
                    <div class="direct-chat-msg">
                      <div class="direct-chat-info clearfix">
                      </div>
                      <div class="direct-chat-text">
                        <div><?=$venuepartner->subject; ?></div>
                        <div><?= date('d M Y H:i A', strtotime($venuepartner->createdon)); ?></div>
                        <div><?=$venuepartner->description; ?></div>
                      </div>
                    </div>
                  </div>
                </div>  
              </div>  
            <?php } ?> 
            <?php } ?> 
            <?php }else{ ?>
              <center><span class="label label-success">No Feedback Found</span></center>
            <?php } ?> 
		    </form>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
  $("#feedback_form").on('submit', function(e){
    e.preventDefault();
    var subject           = $( '#feedback_form #subject' ).val();
    var description       = $( '#feedback_form #description' ).val();
    if(subject == null || subject == ''){
      toastr.error('Subject cannot be empty','Error');
      return false;
    }
    if(description == ''){
      toastr.error('Description cannot be empty','Error');
      return false;
    }
    if(subject && description){
        $.ajax({
          type: "POST",
          url: baseurl + 'partner/venue/addFeedback',
          data: new FormData(this),
          contentType: false,
          cache: false,
          processData:false,
          beforeSend: function() { 
              $("#feedbacksubmit").html('<img src="<?php echo $adminUrl;?>img/loading.gif" style="height:20px;"> Loading...');
              $("#feedbacksubmit").prop('disabled', true);
              $('#feedback_form').css("opacity",".5");
          },
          success: function( data ) {
            if(data == 1){
              $("#feedbacksubmit").html('Add');
              $("#feedbacksubmit").prop('disabled', false);
              toastr.success( 'Feedback Added successfully.','Success' );
              $("form#feedback_form").trigger("reset");
            }else{
              toastr.error( data,'Error' );
              $("#feedbacksubmit").html('Add');
              $("#feedbacksubmit").prop('disabled', false);
            }
            $('#feedback_form').css("opacity","");
          }
        });
    }
  });
});
</script>
