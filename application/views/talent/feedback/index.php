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
		    
		        <div class="box-body">
              <div class="row">
                <form novalidate="" id="feedback_form" role="form" method="post" class="form-horizontal">
	                <div class="col-md-10">
		                <div class="form-group">
		                    <label class="col-md-2 control-label" for="subject">Subject</label>
			                <div class="col-md-10">
			                    <input type="text" required="" id="subject" maxlength="100" class="form-control" name="subject">
			                </div>
			              </div>
		                <div class="form-group">
		                    <label class="col-md-2 control-label" for="description">Feedback</label>
		                    <div class="col-md-10">
		                        <textarea type="text" row="5" id="description" class="form-control" name="description"></textarea>
		                    </div>
		                </div>
                    <div class="footer-div">
                      <button class="btn btn-info pull-right" id="feedbacksubmit" type="submit">Sent</button>
                    </div>
	                </div>
                </form>
              </div>

              <!-- Conversations are loaded here -->
              <div class="direct-chat-messages" style="height: initial;overflow: initial;">
                <!-- Message. Default to the left -->
                <?php 
                if($talentFeedbackLists){
                  foreach ($talentFeedbackLists as $key => $feedback) { ?>
                    <?php 
                    if($feedback->feedback_status == 1){ ?>
                      <div class="direct-chat-msg">
                        <div class="direct-chat-info clearfix">
                          <span class="direct-chat-name pull-left"><?=$feedback->subject; ?></span>
                          <span class="direct-chat-timestamp pull-right"><?= date('d M Y H:i A', strtotime($feedback->createdon)); ?></span>
                        </div>
                        <!-- /.direct-chat-info -->
                        <img class="direct-chat-img" src="<?= base_url(); ?>assets/img/profile_place.png" alt="message user image">
                        <!-- /.direct-chat-img -->
                        <div class="direct-chat-text"><?=$feedback->description; ?></div>
                        <!-- /.direct-chat-text -->
                      </div>
                    <?php 
                    }
                    if($feedback->feedback_status == 2){ ?>
                      <div class="direct-chat-msg right">
                        <div class="direct-chat-info clearfix">
                          <span class="direct-chat-name pull-right"><?=$feedback->subject; ?></span>
                          <span class="direct-chat-timestamp pull-left"><?= date('d M Y H:i A', strtotime($feedback->createdon)); ?></span>
                        </div>
                        <!-- /.direct-chat-info -->
                        <img class="direct-chat-img" src="<?= base_url(); ?>assets/img/Feedback.png" alt="message user image">
                        <!-- /.direct-chat-img -->
                        <div class="direct-chat-text"><?=$feedback->description; ?></div>
                        <!-- /.direct-chat-text -->
                      </div>
                    <?php
                    }
                  }
                }else{ ?>
                  <center>No Feedback Found</center>
                <?php } ?>
              </div>
              <!--/.direct-chat-messages-->
            </div>

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
          url: baseurl + 'talent/feedback/addFeedback',
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
              $("form#feedback_form").trigger("reset");
              setTimeout( function() {
                  toastr.success( 'Feedback Added successfully.','Success' );
                  location.reload();
              }, 1000 );
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
