<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl   = $this->config->item( 'admin_url' );
$site_name  = $this->config->item( 'site_name' );
$dirUrl     = $this->config->item( 'dir_url' );
$upload_path_url = $this->config->item( 'upload_path_url' );
$name       = $guiderInfo->first_name;
?>

<!-- UIkit CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.9.4/dist/css/uikit.min.css" />

    <!-- UIkit JS -->
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.9.4/dist/js/uikit.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.9.4/dist/js/uikit-icons.min.js"></script>
	<link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css'>
<style class="cp-pen-styles">
.placeImg { display:none !important;}
#frame {
  width: 100%;
  
  height: 87vh;
  min-height: 295px;
  max-height: 715px;
  background: #E6EAEA;
}
@media screen and (max-width: 360px) {
  #frame {
    width: 100%;
    height: 100vh;
  }
}
#frame .content {
  float: right;
  width: 100%;
  height: 100%;
  overflow: hidden;
  position: relative;
}
@media screen and (max-width: 735px) {
  #frame .content {
    width: 100%;
    min-width: 300px !important;
  }
}
@media screen and (min-width: 900px) {
  #frame .content {
    width: 100%;
  }
}
#frame .content .contact-profile {
  width: 100%;
  height: 60px;
  line-height: 60px;
  background: #32465a;
  color:#ffff;
}
#frame .content .contact-profile img {
  width: 40px;
  border-radius: 50%;
  float: left;
  margin: 9px 12px 0 9px;
}
#frame .content .contact-profile p {
  float: left;
}
#frame .content .contact-profile .social-media {
  float: right;
}
#frame .content .contact-profile .social-media i {
  margin-left: 14px;
  cursor: pointer;
}
#frame .content .messages {
  height: 200px;
  overflow-y: auto;
}
#frame .content .contact-profile .social-media i:nth-last-child(1) {
  margin-right: 20px;
}
#frame .content .contact-profile .social-media i:hover {
  color: #435f7a;
}
#frame .content .messages {
  height: auto;
  min-height: calc(100% - 93px);
  max-height: calc(100% - 93px);
  overflow-y: scroll;
  overflow-x: hidden;
}
@media screen and (max-width: 735px) {
  #frame .content .messages {
    max-height: calc(100% - 105px);
  }
}
#frame .content .messages::-webkit-scrollbar {
  width: 8px;
  background: transparent;
}
#frame .content .messages::-webkit-scrollbar-thumb {
  background-color: rgba(0, 0, 0, 0.3);
}
#frame .content .messages ul li {
  display: inline-block;
  clear: both;
  float: left;
  margin: 15px 15px 5px 15px;
  width: calc(100% - 25px);
  font-size: 0.9em;
}
#frame .content .messages ul li:nth-last-child(1) {
  margin-bottom: 20px;
}
#frame .content .messages ul li.sent img {
  margin: 6px 8px 0 0;
}
#frame .content .messages ul li.sent p {
  background: lightblue;
  color: #333;
  float:right;
}
#frame .content .messages ul li.replies img {
  float: left;
  margin: 6px 0 0 8px;
}
#frame .content .messages ul li.replies p {
  background: #f5f5f5;
  float: left;
}
#frame .content .messages ul li img {
  width: 22px;
  border-radius: 50%;
  float: right;
}
#frame .content .messages ul li p {
  display: inline-block;
  padding: 10px 15px;
  border-radius: 5px;
  max-width: 205px;
  line-height: 130%;
}
@media screen and (min-width: 735px) {
  #frame .content .messages ul li p {
    max-width: 300px;
  }
}
#frame .content .message-input {
  position: absolute;
  bottom: 0;
  width: 100%;
  z-index: 99;
}
#frame .content .message-input .wrap {
  position: relative;
}
#frame .content .message-input .wrap input {
  font-family: "proxima-nova",  "Source Sans Pro", sans-serif;
  float: left;
  border: none;
  width: calc(100% - 90px);
  padding: 11px 32px 10px 8px;
  font-size: 0.8em;
  color: #32465a;
}
@media screen and (max-width: 735px) {
  #frame .content .message-input .wrap input {
    padding: 15px 32px 16px 8px;
  }
}
#frame .content .message-input .wrap input:focus {
  outline: none;
}
#frame .content .message-input .wrap .attachment {
  position: absolute;
  right: 60px;
  z-index: 4;
  margin-top: 10px;
  font-size: 1.1em;
  color: #435f7a;
  opacity: .5;
  cursor: pointer;
}
@media screen and (max-width: 735px) {
  #frame .content .message-input .wrap .attachment {
    margin-top: 17px;
    right: 65px;
  }
}
#frame .content .message-input .wrap .attachment:hover {
  opacity: 1;
}
#frame .content .message-input .wrap button {
  float: right;
  border: none;
  width: 50px;
  padding: 12px 0;
  cursor: pointer;
  background: #32465a;
  color: #f5f5f5;
}
@media screen and (max-width: 735px) {
  #frame .content .message-input .wrap button {
    padding: 16px 0;
  }
}
#frame .content .message-input .wrap button:hover {
  background: #435f7a;
}
#frame .content .message-input .wrap button:focus {
  outline: none;
}
.fa-trash
{
	color:#fff;
	display:none;
	cursor:pointer;
}
.fa-trash:hover {
	color:#fff !important;
	cursor:pointer;
}
li.sent{
	cursor:pointer;
}
div.boxs{
  width: 200px;
  height: 200px;
  overflow: scroll;
}
.fg-emoji-container
{
	left:1147px !important;
}
#chatbox
{
	margin-top:25px;
}
small
{
	font-size: 12px;
    color: gray;
}
#msgdate{
    text-align: center;
    background: #32465a;;
    width: 8%;
    padding: 10px;
    border-radius: 10px;
    height: 35px;
    margin-left: 46%;
    margin-right: 46%;
	color:#ffff;
	}
#loader
{
height:50px;
width:50px;
margin-left:46%;
margin-right:46%;
display:none;
}	
</style>
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Guider Payout Info</h3>
				
                <div class="box-tools pull-right">
                    <a href="<?php echo $assetUrl; ?>guider" class="btn btn-sm btn-primary">Back</a>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                      <!-- Custom Tabs -->
                      <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                          <li class="active" id="1"><a href="#tab_1" data-toggle="tab">Payout</a></li>
                          <li id="2"><a href="#tab_2" data-toggle="tab">Guider Info</a></li>
						
						  <li id="3" onclick="scrollbottom()"><a href="#tab_3" data-toggle="tab">Guider Inbox Info
						 <?php if($inboxreadinfo){?>						  
						  <div class="badge" id="badge" style="background-color:#32465a"><?php echo $inboxreadinfo; ?></div>
						 <?php } ?>
						  </a></li>
                        </ul>
                        <div class="tab-content">
                          <div class="tab-pane active" id="tab_1">
                            <?php
                            if ($guiderInfo->profile_image) {
                                $profile_image     = $upload_path_url.'g_profile/'.$guiderInfo->profile_image;
                                $userimage = '<img class="img-circle" src="'.$profile_image.'" alt="'.$guiderInfo->first_name.'">';
                            }else{
                                $userimage = '<img class="img-circle" src="'.$dirUrl.'img/avatar5.png" alt="User Avatar">';
                            }
                            ?>
                            <div class="row">
                                <div class="col-md-6">
                                  <div class="box box-widget widget-user-2">
                                    <div class="widget-user-header bg-purple">
                                      <div class="widget-user-image">
                                        <?= $userimage; ?>
                                      </div>
                                      <!-- /.widget-user-image -->
                                      <h3 class="widget-user-username"><?= $guiderInfo->first_name .' '.$guiderInfo->last_name; ?></h3>
                                    </div>
                                    <div class="box-footer no-padding">
                                      <ul class="nav nav-stacked">
                                        <li><a href="#">Settled Payment <span class="pull-right badge bg-green"><?php echo CURRENCYCODE.' '.(($settledPayment)? $settledPayment : '0'); ?></span></a></li>
                                        <li><a href="#">Pending Payment <span class="pull-right badge bg-red"><?php echo CURRENCYCODE.' '.(($payoutAmt)? $payoutAmt : '0'); ?></span></a></li>
                                      </ul>
                                    </div>
                                  </div>
                                </div>
                            </div>
                            <?php
                            if($payoutAmt){
                              $btnstatus  = '';
                              $payoutAmt  = $payoutAmt;
                            }else{ 
                              $btnstatus  = 'disabled'; 
                              $payoutAmt  = 0;
                            }
                            ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <button <?= $btnstatus; ?> class="btn pull-right btn-sm bg-green" onclick="return confirmPayout(<?= $guider_id; ?>,<?= $payoutAmt; ?>,<?= $transactionAmt; ?>,<?= $percentageAmt; ?>,<?= count($pendingPaymentLists); ?>);">
                                        Execute Payout
                                    </button>
									
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="row">
                                <div class="col-md-12">
                                  <div class="">
                                    <div class="box-header with-border">
                                      <h3 class="box-title">Journey Lists(Pending)</h3>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body">
                                      <table class="table table-bordered">
                                        <tr>
                                          <th style="width: 10px">#</th>
                                          <th>Trip ID</th>
                                          <th>Booking Created datetime</th>
                                          <th>Guest Name</th>
                                          <th>Meeting Date</th>
                                          <th>Meeting Time</th>
                                          <th>Processing Fees</th>
                                          <th>Sub total</th>
                                        </tr>
                                        <?php
                                        $i = 1;
                                        if($pendingPaymentLists){
                                            foreach ($pendingPaymentLists as $service) {
                                                echo '<tr>
                                                      <td>'.$i.'</td>
                                                      <td>'.'BT'.str_pad($service->service_id, 5, '0', STR_PAD_LEFT).'</td>
                                                      <td>'.date(getDateFormat(), strtotime($service->jny_createdon)) .' '.date(getTimeFormat(), strtotime($service->jny_createdon)).'</td>
                                                      <td>'.$service->travellerName.'</td>
                                                      <td>'.date(getDateFormat(), strtotime($service->service_date)).'</td>
                                                      <td>'.date(getTimeFormat(), strtotime($service->service_date)).'</td>
                                                      <td>'.number_format((float)$service->percentageAmount, 2, '.', '').'</td>
                                                      <td>'.number_format((float)$service->guiderPayment, 2, '.', '').'</td>
                                                    </tr>';
                                                $i++;
                                            }
                                        }else{
                                            echo '<tr><td colspan="8">No List Found.</td><tr>';
                                        }
                                        ?>
                                      </table>
                                    </div>
                                  </div>
                                  <!-- /.box -->
                                </div>
                            </div>
                            <!-- /.row -->
                          </div>
                          <!-- /.tab-pane -->
                          <div class="tab-pane" id="tab_2">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">User Name</label>
                                        <div><?=$guiderInfo->first_name; ?></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Phone Number</label>
                                        <div><?=$guiderInfo->phone_number; ?></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Account Details</label>
                                        <div>Bank Account Name: <?=$guiderInfo->acc_name; ?></div>
                                        <div>Bank Name: <?=$guiderInfo->bank_name; ?></div>
                                        <div>Bank Account Number : <?=$guiderInfo->acc_no; ?></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Email</label>
                                        <div><?=$guiderInfo->email; ?></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Languages Known</label>
                                        <div><?=$guiderInfo->languages_known; ?></div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="control-label">Ratings</label>
                                        <div><?=$guiderInfo->rating; ?></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Mobile Number</label>
                                        <div><?= ($guiderInfo->mobile)? $guiderInfo->mobile : 'n/a'; ?></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">DOB</label>
                                        <div><?= ($guiderInfo->age != '0000-00-00')? date(getDateFormat(), strtotime($guiderInfo->age)) : 'n/a'; ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">About Me</label>
                                        <div><?=$guiderInfo->about_me; ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <h5 class="box-title"><b>ID Proof</b></h5>
                                    <div style="width:100px; margin: 0 auto;">
                                        <?php 
                                            if($guiderInfo->id_proof){ 
                                            $id_proof = $upload_path_url.'identity/'.$guiderInfo->id_proof;
                                            ?>
                                            <div class="img-view"><img class="img-thumbnail" src="<?=$id_proof; ?>" id="client_picture" style="height: auto;width: 100%;" data-src="#" /></div>
                                        <?php } else { ?>
                                            <div class="img-view"><img class="img-thumbnail" src="<?=$dirUrl; ?>uploads/no_image.png" id="client_picture" style="height:100px;width: auto;" data-src="#" /></div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                          </div>
						   <div class="tab-pane" id="tab_3">
						  
						   <div id="frame">	
							<div class="content">
							<input type="hidden" id="deletemsgid">
								<div class="contact-profile">
									<?php 
								
									if($guiderInfo){ 
									$photo = ($guiderInfo->profile_image); ?>
									<?php } ?>
									<img src="<?= $dirUrl;?>uploads/g_profile/<?= $photo; ?>" alt="" />
									
									<p><?= $guiderInfo->first_name; ?></p>
									<div class="social-media">
									<i class="fa fa-trash fa-2x" aria-hidden="true" title="Delete Message" onclick="deletemsgbyid()"></i>
									</div>
								</div>
								<div class="messages" id="messages">
								<img id="loader" src='http://opengraphicdesign.com/wp-content/uploads/2009/01/loader64.gif'>
									<ul id="chatbox">
									 <?php 
									if($inboxinfo){
									$arr = array();
											foreach ($inboxinfo as $inboxinfos) { 
											$message=$inboxinfos->message;
											$istalent_message=$inboxinfos->istalent_message;
											$istalent_delete=$inboxinfos->istalent_delete;
											$is_admin_message=$inboxinfos->is_admin_message;
$msgid=$inboxinfos->id;
$phpdate = strtotime( $inboxinfos->created_at );
						$mysqldate = date( 'd/m/Y', $phpdate );
						
						if(!in_array($mysqldate,$arr)) {
														
						$arr[]=$mysqldate;
						?>
						<li><p id="msgdate"><?php echo $mysqldate;?></p></li>
						<?php }
											if($is_admin_message){
											?>
											<li class="sent msg" id="<?=$msgid;?>" onclick="deletemsg(<?=$msgid;?>)">
											<img src="<?= $dirUrl;?>img/avatar5.png" alt="" />
											<p><?= $message; ?><small><sub>
						<?php echo date('h:i A', strtotime($inboxinfos->created_at)); ?></sub></small></p>
											</li>
											<?php }if($istalent_message && $istalent_delete){?>
<li id="<?=$msgid;?>" class="replies msg" onclick="deletemsg(<?=$msgid;?>)" >
											<img src="<?= $dirUrl;?>uploads/g_profile/<?= $photo; ?>" alt="" />
											<p><span style="font-style:italic;color:lightgray">Deleted by Talent </span><?= $message; ?><small><sub><?php echo date('h:i A', strtotime($inboxinfos->created_at)); ?></sub></small></p>
											
											</li>
											
											<?php 
											}else if($istalent_message){?>
											<li class="replies msg" id="<?=$msgid;?>" onclick="deletemsg(<?=$msgid;?>)">
											<img src="<?= $dirUrl;?>uploads/g_profile/<?= $photo; ?>" alt="" />
											<p><?= $message; ?><small><sub><?php echo date('h:i A', strtotime($inboxinfos->created_at)); ?></sub></small></p>
											</li>
											
											<?php 
											}
											}
										  }
										  ?>
										
									</ul>
								</div>
								<div class="message-input">
									<div class="wrap">
									<!--<input type="text" placeholder="Write your message..." />-->
									
									<input type="text" name="" placeholder="Write your message..." class="one uk-textarea uk-margin"/>
									<i class="fa fa-smile-o first-btn" aria-hidden="true"style="font-size:35px;cursor:pointer"></i>
       
									<button class="submit" id="inboxsubmit"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
									</div>
								</div>
							</div>
							</div>
							 <input type="hidden" id="guider_id" value="<?= $guider_id; ?>">
							 
							 <!--<div class="boxs">
     All your text content...<br>
     All your text content...<br>
     All your text content...<br>
     All your text content...<br>
     All your text content...<br>
     All your text content...<br>
     All your text content...<br>
     All your text content... <br>
     All your text content...<br>
     All your text content...<br>
     All your text content...<br>
     All your text content...<br>
     All your text content...<br>
     All your text content...<br>
     All your text content...<br>
     All your text content...<br>
     All your text content...<br>
     All your text content...
</div>
<span>Reached bottom</span>-->
						   </div>
                          <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                      </div>
                      <!-- nav-tabs-custom -->
                    </div>
                </div>
					
                
                <div class="clearfix"></div>    
            </div>       
        </div>
    </div>
</div>
<script>

$(".messages").animate({ scrollTop: $(document).height() }, "fast");

function scrollbottom()
{
	var guider_id=$("#guider_id").val();
	var unread=$("#badge").text();
	if(unread)
	{	
	$.ajax({
          type: "POST",
          url: adminurl + 'guider/updatereadStatus',
          data: {guider_id:guider_id},
          success: function( data ) {
			  //document.location.reload();
			  $("#badge").hide();
			   
		  }
	});
	}
	//const messages = document.getElementById('messages');
//messages.scrollTop = messages.scrollHeight;
	$(".messages").animate({ scrollTop: $(document).height() }, "fast");
	//window.scrollTo(0, document.body.scrollHeight);
}
function myFunction()
{
	
$("#1").removeClass("active");
$("#tab_1").removeClass("active");
$("#3").addClass("active");
$("#tab_3").addClass("active");
$(".messages").animate({ scrollTop: $(document).height() }, "fast");             
}
</script>
<script type="text/javascript">
  function confirmPayout( guider_id, payoutAmt, transactionAmt, percentageAmt, totalTrip ) {
      $( '#myModal .modal-title' ).html( 'Confirm' );
      $( '#myModal .modal-body' ).html( 'Are you sure want to Execute Payout ?' );
      $( '#myModal .modal-body' ).append( '<br /><br /><button class="btn btn-info btn-sm" id="continuemodal" data-dismiss="modal">Yes</button>&nbsp;&nbsp;<button aria-hidden="true" data-dismiss="modal" class="btn btn-danger btn-sm">Cancel</button>' );
      $( '#myModal' ).modal({ backdrop: 'static', keyboard: false }); 
      $( "#continuemodal" ).click(function() {
        var data = { 'guider_id':guider_id,'payoutAmt':payoutAmt,'transactionAmt':transactionAmt,'percentageAmt':percentageAmt,'totalTrip':totalTrip }
        $.ajax({
          type: "POST",
          url: adminurl + 'guider/excutePayout',
          data: data,
          success: function( data ) {
            toastr.success( 'Payout executed Successfully.','Success' );
            setTimeout( function() {
              location.reload();
            }, 2000 );
          }
        });
    });
    return false;
  }
  
</script>

<script >

function newMessage() {
	message = $.trim($(".message-input input").val());
	guiderid=$.trim($("#guider_id").val());
	if($.trim(message) == '') {
		return false;
	}
	else
	{
		$('<li class="sent"><img src="<?= $dirUrl;?>img/avatar5.png" alt="" /><p>' + message + '</p></li>').appendTo($('.messages ul'));
		$('.message-input input').val(null);
		$('.contact.active .preview').html('<span>You: </span>' + message);
		$(".messages").animate({ scrollTop: $(document).height() }, "fast");
		$.ajax({
          type: "POST",
          url: adminurl + 'guider/addMessage',
          data: {message:message,guiderid:guiderid},
          success: function( data ) {
            if(data == 1){           
              setTimeout( function() {                
                 
				  $("#1").removeClass("active");
				  $("#tab_1").removeClass("active");
				  $("#3").addClass("active");
				  $("#tab_3").addClass("active");
				  $(".messages").animate({ scrollTop: $(document).height() }, "fast");
              }, 1000 );
            }else{
              toastr.error( data,'Error' );             
            }            
          }
        });	
	}
};

$('.submit').click(function() {
  newMessage();
});

$(window).on('keydown', function(e) {
  if (e.which == 13) {
    newMessage();
    return false;
  }
});
function deletemsg(msgid)
{
	$("li").css("background", "none");
	$("#"+msgid).css("background", "darkgray");
	
	$("#deletemsgid").val(msgid);
	$(".fa-trash").show();
		
}
function deletemsgbyid()
{
	var msgid=$("#deletemsgid").val();
	$.ajax({
          type: "POST",
          url: adminurl + 'guider/deleteMessage',
          data: {msgid:msgid},
          success: function( data ) {
            if(data == 1){
		   toastr.success( 'Message Deleted.','Success' );
		   location.reload(true);
			$(".fa-trash").hide();				
              setTimeout( function() {                
                 $("#1").removeClass("active");
				  $("#tab_1").removeClass("active");
				  $("#3").addClass("active");
				  $("#tab_3").addClass("active");
              }, 1000 );
            }else{
              toastr.error( data,'Error' );             
            }            
          }
        });
}

// var page =1;
// $(".boxs").scroll(function() {
    // if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
       // page++; 
 // alert(page);	   
    // } else {
        // alert(page);
    // }
// });
</script>
<script>
// Assign scroll function to chatBox DIV
$('#messages').scroll(function(){
    if ($('#messages').scrollTop() == 0){
		// id=$("ul#chatbox:first-child").attr("id")
		 var msgid = $("ul#chatbox li.msg:first").attr("id");
		 var datearr = <?php echo json_encode($arr); ?>;
		 var guider_id=$("#guider_id").val();
        // Display AJAX loader animation
         $('#loader').show();
  
      // Youd do Something like this here
      // Query the server and paginate results
      // Then prepend
        $.ajax({
          type: "POST",
		  url: adminurl + 'guider/loadMessage',
		  data: {msgid:msgid,datearr:datearr,guider_id:guider_id},
		  success: function( data ) {
			  //alert(data);
                $('#chatbox').prepend(data);
				//$('#loader').hide();
            }
        });
        //BUT FOR EXAMPLE PURPOSES......
        // We'll just simulate generation on server

       
        //Simulate server delay;
        setTimeout(function(){
        // Simulate retrieving 4 messages
        // for(var i=0;i<4;i++){
        // $('.messages').prepend('<div class="messages">Newly Loaded messages<br/><span class="date">'+Date()+'</span> </div>');
            // }
            // Hide loader on success
            $('#loader').hide();
            // Reset scroll
            $('#messages').scrollTop(30);
        },780); 
    }
});


</script>
<script>

        new EmojiPicker({
            trigger: [
                {
                    selector: '.first-btn',
                    insertInto: ['.one', '.two'] // '.selector' can be used without array
                },
                {
                    selector: '.second-btn',
                    insertInto: '.two'
                }
            ],
            closeButton: true,
            //specialButtons: green
        });

    </script>