<?php
$dirUrl = $this->config->item( 'dir_url' );
?>
<!--Toastr message -->
<link rel="stylesheet" type="text/css" href="<?php echo $dirUrl; ?>assets/js/toastr/toastr.min.css">
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<style type="text/css">
.form-contact .form-control {
    border: 1px solid #9E9E9E;
}
</style>
<section class="cta_part">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12">
                <div class="cta_part_iner">
                    <div class="cta_part_text center_div">
                        <h1>Create a gig here</h1>
                        <p>Post a gig here to find your talents easily and fast</p>
                        <div class="form_center_div">
                            <form class="form-contact contact_form" role="form" method="post" id="gigsForm" novalidate="">
                                <div class="col-12">
                                    <div class="form-group"> 
                                        <input class="form-control" required="" name="full_name" id="full_name" type="text" placeholder="Full Name">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <input class="number form-control" required="" name="mobile_no" id="mobile_no" type="text" placeholder="Enter Phone Number">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <input class="form-control" name="email" id="email" type="email" placeholder="Enter Email Address">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <select class="form-control" name="skill_id" id="skill_id" placeholder="What skill your looking for hire?">
                                            <option value="0">Select Skill</option>
                                            <?php 
                                            if($categoryLists){
                                                foreach ($categoryLists as $catInfo) {
                                                  ?><option value="<?php echo base64_encode($catInfo->specialization_id); ?>"><?php echo $catInfo->specialization; ?></option><?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <select class="form-control" name="city_id" id="city_id" placeholder="Select city">
                                            <option value="0">Select City</option>
                                            <?php 
                                            if($cityLists){
                                                foreach ($cityLists as $cityInfo) {
                                                  ?><option value="<?php echo base64_encode($cityInfo->id); ?>"><?php echo $cityInfo->name; ?></option><?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <input class="form-control" name="budget" id="budget" type="text" placeholder="What is your budget?">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input class="form-control" name="time_hour" id="time_hour" type="text" placeholder="When do you need the talent?">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input class="form-control datepicker" name="date" id="date" type="text" placeholder="Date">
                                    </div>
                                </div>
                                <div class="row">
                                  <div class="col-12">
                                    <div class="form-group">
                                        <textarea class="form-control w-100" name="other_info" id="other_info" cols="30" rows="4" placeholder="Other information if available"></textarea>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="col-md-offset-4 col-md-8 control-label text-left" for="privacy">
                                            <input type="checkbox" name="privacy" id="privacy" value="1">
                                            I have read, understand and agree to the <a href="<?php echo $dirUrl; ?>privacypolicy">privacy policy</a>
                                        </label>
                                    </div>
                                </div> 
                                <div class="col-12">
                                    <div class="form-group pull-right">
                                        <div class="g-recaptcha" data-sitekey="<?php echo RECAPTCHA_SITE_KEY;?>"></div>
                                    </div>
                                </div>       
                                <div class="form-group">
                                  <button type="button" id="createGigBtn" onclick="return createGigs();" class="button button-gigsForm btn_4">Create Gig</button>
                                  <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- full calander js -->
<script type="text/javascript" src="<?php echo $dirUrl; ?>assets/js/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo $dirUrl; ?>assets/js/toastr/toastr.min.js"></script>
 <!-- Re Captcha code -->
<script type="text/javascript" src="https://www.google.com/recaptcha/api.js" async defer></script>
<script type="text/javascript">
$( ".datepicker" ).datepicker({
    minDate: 0,
    maxDate: '+1Y+6M',
    onSelect: function (dateStr) {
        var min = $(this).datepicker('getDate');
    }
});
function ValidateEmail(email) {
  var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
  return expr.test(email);
}
function createGigs(){

    var full_name   = $( '#gigsForm #full_name' ).val();
    var mobile_no   = $( '#gigsForm #mobile_no' ).val();
    var email       = $( '#gigsForm #email' ).val();
    var skill_id    = $( '#gigsForm #skill_id' ).val();
    var city_id     = $( '#gigsForm #city_id' ).val();
    var budget      = $( '#gigsForm #budget' ).val();
    var time_hour   = $( '#gigsForm #time_hour' ).val();
    var date        = $( '#gigsForm #date' ).val();
    var other_info  = $( '#gigsForm #other_info' ).val();

    if(full_name == ''){
        toastr.error('Full Name Cannot be empty','Error');
    }
    if(mobile_no == ''){
        toastr.error('Mobile Number Cannot be empty','Error');
    }
    if(email == ''){
        toastr.error('Email Cannot be empty','Error');
    }
    if(skill_id == ''){
        toastr.error('Skill Cannot be empty','Error');
    }
    if(city_id == ''){
        toastr.error('City Cannot be empty','Error');
    }
    if(budget == ''){
        toastr.error('Budget Cannot be empty','Error');
    }
    if(time_hour == ''){
        toastr.error('Hours Cannot be empty','Error');
    }
    if(date == ''){
        toastr.error('Date Cannot be empty','Error');
    }
    if (!$('#privacy').is(':checked')) {
        toastr.error('You must agree to the privacy policy','Error');
        return false;
    }
    if(full_name && mobile_no && email && skill_id && city_id && budget && time_hour && date){
      var data = $( "#gigsForm" ).find( "select, textarea, input" ).serialize();
        $.ajax({
          type: "POST",
          url: baseurl + 'gigs/Add_Gigs',
          data: data,
          async : false,
          beforeSend: function() { 
              $("#createGigBtn").html('<img src="'+baseurl+'assets/img/loading.gif"> Loading...');
              $("#createGigBtn").prop('disabled', true);
              $('#gigsForm').css("opacity",".5");
          },
          success: function( msg ) {
            $("#createGigBtn").html('Create Gigs');
            $("#createGigBtn").prop('disabled', false);
            var obj = jQuery.parseJSON(msg);
            if( obj.res_status == 'success' ) {
                toastr.success( obj.message,'Success' );
                setTimeout( function() {
                    location.reload();
                }, 1000 );
            } else {
                alert(obj.message);
            }

            $('#gigsForm').css("opacity","");
            },error: function() {
                $("#createGigBtn").html('Create Gigs');
                $('#createGigBtn').prop('disabled', false);
            }
        });
    }
}
</script> 
