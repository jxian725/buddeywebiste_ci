<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 
$assetUrl   = $this->config->item( 'admin_url' );
$adminUrl   = $this->config->item( 'admin_dir_url' );
$dirUrl     = $this->config->item( 'dir_url' );

?>
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
#frame .content .contact-profile .social-media i:nth-last-child(1) {
  margin-right: 20px;
}
#frame .content .contact-profile .social-media i:hover {
  color: #435f7a;
}
#frame .content .messages {
  height: 500px;
  overflow-y: auto;
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
	color:#fff !important;
	display:none;
	cursor:pointer;
}
li.sent{
	cursor:pointer;
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
    <div class="col-md-12 col-sm-12"> 
        <div class="box box-primary">
		
		<div id="frame">	
		<div class="content">
		<input type="hidden" id="deletemsgid">
			<div class="contact-profile">
				<?php 
				
				if($imageLists){ 
					foreach ($imageLists as $img) { 
						$photo = ($img->profile_image);
				 } ?>
				<img src="<?= $adminUrl;?>uploads/g_profile/<?= $photo; ?>" alt="" />
				<?php } ?>
				<p><?= $talentInfo->first_name; ?></p>
				<div class="social-media">
				<i id="trash" class="fa fa-trash fa-2x" aria-hidden="true" onclick="deletemsgbyid()" title="Delete Message"></i>
				
				</div>
			</div>
			
			<div class="messages" id="messages">
			<img id="loader" src='http://opengraphicdesign.com/wp-content/uploads/2009/01/loader64.gif'>

				<ul id="chatbox">
				 <?php 
				 
                      if($inboxinfo){
						  $arr = array();
                        foreach (array_reverse($inboxinfo) as $inboxinfos) { 
						$message=$inboxinfos->message;
						$istalent_message=$inboxinfos->istalent_message;
						$is_admin_message=$inboxinfos->is_admin_message;
					    $msgid=$inboxinfos->id;
						//print_r($arr);
						
						$phpdate = strtotime( $inboxinfos->created_at );
						$mysqldate = date( 'd/m/Y', $phpdate );
						
						if(!in_array($mysqldate,$arr)) {
														
						$arr[]=$mysqldate;
						?>
						<li><p id="msgdate"><?php echo $mysqldate;?></p></li>
						<?php }
	
						if($istalent_message){
						?>

						<li class="sent msg" id="<?=$msgid;?>" onclick="deletemsg(<?=$msgid;?>)">
						<img src="<?= $adminUrl;?>uploads/g_profile/<?= $photo; ?>" alt="" />
						<p><?= $message; ?><small><sub><?php echo date('h:i A', strtotime($inboxinfos->created_at)); ?></sub></small></p>
						</li>
						<?php }if($is_admin_message){?>
						<li class="replies msg" id="<?=$msgid;?>">
						<img src="<?= $adminUrl;?>img/avatar5.png" alt="" />
						<p><?= $message; ?><sub><small>
						<?php echo date('h:i A', strtotime($inboxinfos->created_at)); ?></sub></small></p>
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
				
				<!--<i class="fa fa-paperclip attachment" aria-hidden="true"></i>-->
				<input type="text" name="" class="one uk-textarea uk-margin" placeholder="Write your message..."/>
				<i class="fa fa-smile-o first-btn" aria-hidden="true"style="font-size:35px;cursor:pointer"></i>
				<button class="submit" id="inboxsubmit"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
				</div>
			</div>
			
		</div>
		</div>
        </div>
    </div>
</div>
<script >

const messages = document.getElementById('messages');
messages.scrollTop = messages.scrollHeight;
var unread=$("#badge").text();

	if(unread)
	{	
	$.ajax({
          type: "POST",
          url: baseurl + 'talent/inbox//updatereadStatus',
        //  data: {guider_id:guider_id},
          success: function( data ) {
			  //document.location.reload();
			  $("#badge").hide();
			   
		  }
	});
	}
//$(".messages").animate({ scrollTop: $(document).height() }, "fast");
function newMessage() {
	message = $.trim($(".message-input input").val());
	if($.trim(message) == '') {
		return false;
	}
	else
	{
		$('<li class="sent"><img src="<?= $adminUrl;?>uploads/g_profile/<?= $photo; ?>" alt="" /><p>' + message + '</p></li>').appendTo($('.messages ul'));
		$('.message-input input').val(null);
		$('.contact.active .preview').html('<span>You: </span>' + message);
		$(".messages").animate({ scrollTop: $(document).height() }, "fast");
		$.ajax({
          type: "POST",
          url: baseurl + 'talent/inbox/addMessage',
          data: {message:message},
          success: function( data ) {
            if(data == 1){           
              setTimeout( function() {                
                  location.reload();
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
	$("li.sent").css("background", "none");
	$("#"+msgid).css("background", "darkgray");
	
	$("#deletemsgid").val(msgid);
	$(".fa-trash").show();
		
}
function deletemsgbyid()
{
	var msgid=$("#deletemsgid").val();
	$.ajax({
          type: "POST",
          url: baseurl + 'talent/inbox/deleteMessage',
          data: {msgid:msgid},
          success: function( data ) {
            if(data == 1){
			$(".fa-trash").hide();				
              setTimeout( function() {                
                  location.reload();
              }, 1000 );
            }else{
              toastr.error( data,'Error' );             
            }            
          }
        });
}

</script>
<script>
// Assign scroll function to chatBox DIV
$('#messages').scroll(function(){
    if ($('#messages').scrollTop() == 0){
		// id=$("ul#chatbox:first-child").attr("id")
		 var msgid = $("ul#chatbox li.msg:first").attr("id");
		 var datearr = <?php echo json_encode($arr); ?>;
        // Display AJAX loader animation
         $('#loader').show();
  
      // Youd do Something like this here
      // Query the server and paginate results
      // Then prepend
        $.ajax({
          type: "POST",
		  url: baseurl + 'talent/inbox/loadMessage',
		  data: {msgid:msgid,datearr:datearr},
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
