<?php
$dirUrl = $this->config->item( 'dir_url' ); 
$admin_url  = $this->config->item( 'admin_dir_url' ); 
?>
<!--Toastr message -->
<link rel="stylesheet" type="text/css" href="<?php echo $dirUrl; ?>assets/js/toastr/toastr.min.css">
<link rel="stylesheet" href="<?= $admin_url; ?>plugins/select2/select2.min.css">
<script src="<?= $admin_url; ?>plugins/select2/select2.full.min.js"></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<link rel="stylesheet" href="<?php echo $admin_url; ?>plugins/bootstrap-datepicker/bootstrap-datepicker.min.css">
<style type="text/css">
.box {
    border-radius: 25px;
    margin: 40px auto;
    padding: 10px;
    width: 65%;
}
label.error{
    color: #FA3C3C;
    font-weight: normal;
    float: left;
    width: 100%;
}
input.error{
  border: 1px solid #FA3C3C;
}
.btn-sky {
    color: #fff;
    background-color: #2d86ff;
    border-bottom: 2px solid #2d86ff;
}

.btn-sky:hover,.btn-sky.active:focus, .btn-sky:focus, .open>.dropdown-toggle.btn-sky {
    color: #fff;
    background-color: #2d86ff;
    border-bottom: 2px solid #2d86ff;
    outline: none;
}
.btn-sky:active, .btn-sky.active {
    color: #fff;
    background-color: #2d86ff;
    border-top:2px solid #2d86ff;
    outline-offset: none;
    margin-top: 2px;
}
/* Line stright*/
#progressbar {
    margin-bottom: 30px;
    overflow: hidden;
    color: #455A64;
    padding-left: 0px;
    margin-top: 30px
}

#progressbar li {
    list-style-type: none;
    font-size: 18px;
    width: 33.33%;
    float: left;
    position: relative;
    font-weight: 400
}

#progressbar #step1:before {
    content: "1";
    color: #fff
}

#progressbar #step2:before {
    content: "2";
    color: #fff
}

#progressbar #step3:before {
    content: "3";
    color: #fff
}
#progressbar li:before {
    width: 40px;
    height: 40px;
    line-height: 45px;
    display: block;
    font-size: 20px;
    background: #455A64;
    border-radius: 50%;
    margin: auto;
    padding: 0px
}
#progressbar li:after {
    content: '';
    width: 100%;
    height: 2px;
    background: #000;
    position: absolute;
    left: 0;
    top: 21px;
    z-index: -1
}
#progressbar li:last-child:after {
    border-top-right-radius: 10px;
    border-bottom-right-radius: 10px;
    position: absolute;
    left: -50%
}
#progressbar li:nth-child(2):after {
    left: -50%
}
#progressbar li:first-child:after {
    border-top-left-radius: 10px;
    border-bottom-left-radius: 10px;
    position: absolute;
    left: 50%
}
#progressbar li.active:before,
#progressbar li.active:after {
    background: #5cb85c
}
#privacy-error{ width: 100%; }
.column {
  float: left;
  width: 30.33%;
  padding: 20px;
}
.upload_img{
  width: 30% !important;
  border: 2px dotted #ccc;
  padding: 10px;
  cursor: pointer;
}
.placeImg { display:none !important;}
</style>
<body>
<section class="cta_part">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12">
                <div class="cta_part_iner">
                    <div class="cta_part_text center_div">
                        <form class="form-horizontal form" id="talentForm" role="form" method="post" autocomplete="off" novalidate="">
                            <div class="step">
                                <h1>Why join Buddey Talent ?</h1>
                                    <div class="row">
                                        <div class="column">
                                            <img src="<?= $dirUrl; ?>assets/img/money.png" alt="Snow" style="height: 190px;width:100%">
                                            <h4 class="text-left">Earn more money</h4>
                                            <p class="text-left" style="font-size: 12px;color: #000;">
                                               Earn money when you do busking at our Buddey Buskers Pods. Also when you sign up as Buddey Talent, you will be able to get more gigs and jobs from our business partners.</p>   
                                        </div>
                                        <div class="column">
                                            <img src="<?= $dirUrl; ?>assets/img/unsplash.jpg" alt="Forest" style="height: 190px;width:100%">
                                            <h4 class="text-left">Fully flexible</h4>
                                             <p class="text-left" style="font-size: 12px;color: #000;">Plan your own schedule anytime and anywhere you want. You can also decide when you want to do a performance gig like a boss. </p>
                                        </div>
                                        <div class="column">
                                             <img src="<?= $dirUrl; ?>assets/img/guitar.jpg" alt="Mountains" style="height: 190px;width:100%">
                                             <h4 class="text-left">Transparent and fair</h4>
                                             <p class="text-left" style="font-size: 12px;color: #000;">You are in control, manage your own plan and your Talent business efficiently with Buddey.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="box row-fluid">
                                    <div class="step">
                                        <h1>Talent Sign Up</h1>
                                        <div style="text-align:center;margin-top:40px;">
                                            <ul id="progressbar" class="text-center">
                                                <li class="active step0" id="step1">Login Details</li>
                                                <li class="step0" id="step2">Talent Profile</li>
                                                <li class="step0" id="step3">Profile Picture</li>
                                            </ul> 
                                        </div>     
                                        <div class="form-group">
                                            <input class="form-control" name="email" id="email" type="email" placeholder="Enter email address" autocomplete="off">
                                        </div>
                                        <div class="form-group">
                                          <div class="row">
                                            <div class="col-sm-2" style="padding-right: 0px;">
                                              <select class="form-control" style="width: 100%;" name="countryCode" id="countryCode">
                                                <option selected value="<?= COUNTRY_CODE; ?>"><?= COUNTRY_CODE_WC; ?></option>
                                              </select>
                                            </div>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control pnumber" maxlength="12" name="mobile" id="mobile" placeholder="Example 123456789" autocomplete="off">
                                            </div>
                                          </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12 control-label text-left" for="privacy">
                                                <input type="checkbox" name="privacy" id="privacy" value="1" style="width: auto;">
                                                <span>&nbsp;&nbsp;&nbsp;I have read, understand and agree to the <a href="<?php echo $dirUrl; ?>termsandconditions">Terms and Conditions</a> and <a href="<?php echo $dirUrl; ?>privacypolicy">Privacy policy</a></span>
                                            </label>
                                        </div>
                                        <div class="form-group">
                                          <div class="form-group pull-right">
                                            <div data-type="image" class="g-recaptcha" data-sitekey="<?php echo RECAPTCHA_SITE_KEY;?>"></div>
                                          </div>
                                          <div id="recaptcha-error"></div>
                                        </div>    
                                    </div>           
                                    <div class="step">
                                        <h1>Talent Sign Up</h1>
                                        <div style="text-align:center;margin-top:40px;">
                                            <ul id="progressbar" class="text-center">
                                                <li class="active step0" id="step1">Login Details</li>
                                                <li class="active step0" id="step2">Talent Profile</li>
                                                <li class="step0" id="step3">Profile Picture</li>
                                            </ul> 
                                        </div> 
                                        <div class="form-group">
                                            <input type="text" name="first_name" id="first_name" class="form-control" required="" placeholder="Enter full name">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Enter other name">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" id="age" name="age" placeholder="Enter date of birth" class="form-control datepicker">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" name="nric_number" id="nric_number" maxlength="18" class="form-control nric_number" placeholder="NRIC Number">
                                        </div>
                                        <div class="form-group">
                                            <select class="form-control" name="city" id="city">
                                                <option value="">Select city</option>
                                                <?php 
                                                if($cityLists) { 
                                                  foreach ( $cityLists as $key => $cityInfo ) {
                                                    echo '<option value="'. $cityInfo->id .'">'.$cityInfo->name.'</option>';
                                                  }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <select class="form-control select2" multiple="multiple" data-placeholder="Select language" style="width: 100%;" name="languages_known[]" id="languages_known">
                                            <?php
                                            if($getTalentLangLists){
                                              foreach ($getTalentLangLists as $key => $lang) { ?>
                                                <option value="<?= $lang->lang_id; ?>"><?= $lang->language; ?></option>
                                              <?php
                                              }
                                            }
                                            ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <select class="form-control" name="skills_category" id="skills_category">
                                              <option value="0">Select interest category (optional)</option>
                                              <?php
                                              if( $specialization_lists ) {
                                                foreach ( $specialization_lists as $key => $value ) {
                                              ?><option value="<?php echo $value->specialization_id; ?>"><?=rawurldecode( $value->specialization );?></option><?php
                                                  }
                                              }
                                              ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <input text="text" class="form-control" placeholder="Add Skill e.g. Guitarist (optional)" id="sub_skills" name="sub_skills">
                                        </div>
                                        <div class="form-group">
                                          <textarea name="about_me" id="about_me" rows="4" placeholder="Tell us about yourself" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="step">
                                        <h1>Talent Sign Up</h1>
                                        <div style="text-align:center;margin-top:40px;">
                                            <ul id="progressbar" class="text-center">
                                                <li class="active step0" id="step1">Login Details</li>
                                                <li class="active step0" id="step2">Talent Profile</li>
                                                <li class="active step0" id="step3">Profile Picture</li>
                                            </ul> 
                                        </div>
                                        <center>
                                          <img src="<?php echo $dirUrl; ?>assets/img/upload_profile.png" class="upload_img" id="upload_profile_lbl" data-toggle="tooltip" data-toggle="tooltip" title="Click to upload Profile Image">
                                          <input type="file" onchange="displayUploadImg(this, 'upload_profile_lbl');" name="profile_image" id="profile_image" class="placeImg" accept="image/*" />
                                        </center> 
                                        
                                        <div class="form-group">
                                            <div class="about_img">
                                              <p style="margin-top: 20px;color: #323232;"><a id="skip_now_img" href="javascript:;">Skip for Now</a></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="float-right">
                                                <button type="button" id="backBtn" class="action btn-sky text-capitalize back btn">Back</button>
                                                <button type="button" class="action btn-sky text-capitalize new btn">Sign Up</button>
                                                <button type="button" id="nextBtn1" class="action btn-sky text-capitalize next btn">Next</button>
                                                <button type="button" id="nextBtn" class="action btn-success text-capitalize submit btn">Complete</button>
                                                <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
                                            </div>
                                        </div>
                                    </div>      
                                </div>
                        </form> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>                    
<!-- Validate JavaScript -->
  <script type="text/javascript" src="<?php echo $dirUrl; ?>assets/js/toastr/toastr.min.js"></script>
  <script src="<?php echo $dirUrl;?>assets/reg-js/jquery-ui.min.js"></script>
  <script src="<?php echo $dirUrl;?>assets/reg-js/jquery.validate.js"></script>
  <script type="text/javascript" src="https://www.google.com/recaptcha/api.js" async defer></script>
  <script src="<?php echo $admin_url; ?>plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<!--  End --->
<?php
$dobminyear = (date('Y')-13).'-'.date('m').'-'.date('d');
?>
<script type="text/javascript">
$( ".datepicker" ).datepicker({
    changeYear: true,
    format: 'yyyy-mm-dd',
    autoclose: true,
    maxDate: 0,
    endDate: '<?= $dobminyear; ?>',
    orientation: 'auto'
});
$(document).ready(function(){
  $('.pnumber').keypress(function(event) {
      if (event.which == 8 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46) {
          return true;
      }else if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
          event.preventDefault();
      }
      if (this.value.length == 0 && event.which == 48 ){
        return false;
      }
  });
});
$(document).ready(function(){
  $('.select2').select2();

  $("#upload_profile_lbl").on('click', function() {
    $("#profile_image").trigger('click');
  });

  $("#skip_now_img").on('click', function() {
    jQuery('#upload_profile_lbl').css('opacity', '0.4');
    jQuery("#nextBtn").trigger('click');
    return false;
  });
});

//allow hyphen and number
$(document).on('keypress','.nric_number',function(event){
  if (event.which == 8 || event.which == 45) {
    return true;
  }else if ((event.which != 45 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
    event.preventDefault();
  }
});

function displayUploadImg(input, PlaceholderID) {
  if (input.files && input.files[0]) {
    var upfile = input.files[0];
    var imagefile = upfile.type;
    var match= ["image/jpeg","image/png","image/jpg"];
    if(!((imagefile==match[0]) || (imagefile==match[1]) || (imagefile==match[2]))){
        alert('Please select a valid image file (JPEG/JPG/PNG).');
        $("#"+input.id).val('');
        return false;
    }
    var file_size = upfile.size/1024/1024;
    if(file_size < 5){
      var reader = new FileReader();
      reader.onload = function (e) {
        $('#'+PlaceholderID)
            .attr('src', e.target.result)
            .width('30%')
            .height('auto');
        };
      reader.readAsDataURL(upfile);
    }else{
      alert('File too large. File must be less than 5 MB.');
      $("#"+input.id).val('');
      return false;
    }
  }
}
</script>
<script type="text/javascript">
  $(document).ready(function(){
    var current = 1;
    
    widget      = $(".step");
    btnnext     = $(".next");
    btnnew      = $(".new");
    btnback     = $(".back"); 
    btnsubmit   = $(".submit");

    // Init buttons and UI
    widget.not(':eq(0)').hide();
    hideButtons(current);
    setProgress(current);

    // Next button click action
    btnnext.click(function(){
      $(".g-recaptcha").css({"border": "none"});
      if(current < widget.length){
        if(current == 2){
          var captchResponse = $('#g-recaptcha-response').val();
          if(captchResponse.length == 0 ){
            $(".g-recaptcha").css({"border": "1px solid #F44336"});
            return false;
          }
        }
        // Check validation
        if($(".form").valid()){
          widget.show();
          widget.not(':eq('+(current++)+')').hide();
          setProgress(current);
        }
      }

      hideButtons(current);
    })

    // Submit button click
    btnsubmit.click(function(){
       var email            = $( '#talentForm #email' ).val();
       var countryCode      = $( '#talentForm #countryCode' ).val();
       var mobile           = $( '#talentForm #mobile' ).val();
       var first_name       = $( '#talentForm #first_name' ).val();
       var last_name        = $( '#talentForm #last_name' ).val();
       var city             = $( '#talentForm #city' ).val();
       var area             = $( '#talentForm #area' ).val();
       var age              = $( '#talentForm #age' ).val();
       var about_me         = $( '#talentForm #about_me' ).val();
       var nric_number      = $( '#talentForm #nric_number' ).val();
       var gender           = $( '#talentForm #gender' ).val();
       var skills_category  = $( '#talentForm #skills_category' ).val();
       var sub_skills       = $( '#talentForm #sub_skills' ).val();
       var gigs_amount      = $( '#talentForm #gigs_amount' ).val();
       var bank_name        = $( '#talentForm #bank_name' ).val();
       var acc_name         = $( '#talentForm #acc_name' ).val();
       var acc_no           = $( '#talentForm #acc_no' ).val();
       if(email == ''){ alert('Email cannot be empty.'); }
       if(countryCode == ''){ alert('Country Code cannot be empty.'); }
       if(mobile == ''){ alert('Mobile Number cannot be empty.'); }
       if(first_name == ''){ alert('First Name cannot be empty.'); }
       if(last_name == ''){ alert('Last Name cannot be empty.'); }
       if(age == ''){ alert('Age cannot be empty.'); }
       if(about_me == ''){ alert('About us cannot be empty.'); }
       if(nric_number == ''){ alert('NRIC Number cannot be empty.'); }
       //if(city == ''){ alert('City cannot be empty.'); }
       //if(area == ''){ alert('Area cannot be empty.'); }
       //if(gender == ''){ alert('Gender cannot be empty.'); }
       //if(skills_category == ''){ alert('Skill category cannot be empty.'); }
       //if(sub_skills == ''){ alert('Skill cannot be empty.'); }
       //if(gigs_amount == ''){ alert('Amount cannot be empty.'); }
       // Check Password .....
        if( email && countryCode && mobile && first_name && last_name && age  && about_me ){
            //var formData = $( "#talentForm" ).find( "select, input, textarea" ).serialize();
            var form = $('#talentForm')[0];
            var formData = new FormData(form);
            $.ajax({
              type: "POST",
              url: baseurl + 'talent/register/addTalent',
              data: formData,
              async : false,
              contentType: false,
              cache: false,
              processData: false,
              beforeSend: function() { 
                  $("#nextBtn").html('<img src="'+baseurl+'assets/img/loading.gif"> Loading...');
                  $("#nextBtn").prop('disabled', true);
                  $('#talentForm').css("opacity",".5");
              },
              success: function( msg ) {
                $("#nextBtn").html('Complete');
                $("#nextBtn").prop('disabled', false);
                var obj = jQuery.parseJSON(msg);
                if( obj.res_status == 'success' ) {
                    $('#talentForm').trigger("reset");
                    toastr.success( obj.message,'Register Success' );
                    setTimeout( function() {
                        location.reload();
                    }, 500 );
                } else {
                    alert(obj.message);
                }
                $('#talentForm').css("opacity","");
                },error: function() {
                    $("#nextBtn").html('Complete');
                    $('#nextBtn').prop('disabled', false);
                }
            });
        }
    });

    // Back button click action
    btnback.click(function(){
      if(current > 1){
        current = current - 2;
        if(current < widget.length){
          widget.show();
          widget.not(':eq('+(current++)+')').hide();
          setProgress(current);
        }
      }
      hideButtons(current); 
    })
      $('.form').validate({ // initialize plugin
        ignore:":not(:visible)",
        rules: {
          privacy          : "required",
          first_name       : "required",
          last_name        : "required",
          age              : "required",
          about_me         : "required",
          // email            : {required : true, email : true},
          email: {
                        required : true,
                        email: true,
                        remote: {
                            type: 'post',
                            url: baseurl + 'talent/register/checkEmail',
                            data: {
                                    email: function() {
                                      return $('#talentForm #email').val();
                                    }
                                  },
                            dataType: 'json',
                            success: function(result) { console.log(result);
                                $.validator.messages.email = result;
                                if (result.email == 'found'){
                                    alert('Email address already in use');
                                    $("#email").css({"border": "1px solid #F44336"});
                                    $("#nextBtn1").prop('disabled', true);
                                    return false;
                                }else{
                                    $("#email").css({"border": "1px solid #ced4da"});
                                    $("#nextBtn1").prop('disabled', false);
                                }
                            }
                        }
                },
          // mobile     : {required : true, minlength: 6},
          mobile: {
                        required : true,
                        minlength: 6,
                        maxlength: 12,
                        remote: {
                            type: 'post',
                            url: baseurl + 'talent/register/checkMobileNo',
                            data: {
                                    mobile: function() {
                                      return $('#talentForm #mobile').val();
                                    }
                                  },
                            dataType: 'json',
                            success: function(result) { console.log(result);
                                $.validator.messages.mobile = result;
                                if (result.mobile == 'found'){
                                    alert('Mobile number already in use');
                                    $("#mobile").css({"border": "1px solid #F44336"});
                                    $("#nextBtn1").prop('disabled', true);
                                    return false;
                                }else{
                                    $("#mobile").css({"border": "1px solid #ced4da"});
                                    $("#nextBtn1").prop('disabled', false);
                                }
                            }
                        }
                },
          'g-recaptcha-response' : "required",
        },
        messages: {
          mobile: {
            remote: function() { return jQuery.format("{0} is invalid or already in use", $("#mobile").val()) }
          },
          email: {
            remote: function() { return jQuery.format("{0} is invalid or already in use", $("#email").val()) }
          }
        }
    });
  });

// Change progress bar action
setProgress = function(currstep){
var percent = parseFloat(100 / widget.length) * currstep;
percent = percent.toFixed();
$(".progress-bar").css("width",percent+"%").html(percent+"%");  
}
 
  // Hide buttons according to the current step
  hideButtons = function(current){
    var limit = parseInt(widget.length); 

    $(".action").hide();

    if(current < limit) btnnext.show();
    if(current > 1) btnback.show();
    if (current == limit) { 
      // Show entered values
      $(".display label.lbl").each(function(){
        $(this).html($("#"+$(this).data("id")).val());  
      });
      btnnext.hide();
      btnback.hide();
      btnsubmit.show();
    }
  }
</script>



