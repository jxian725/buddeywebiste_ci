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
.para p{
font-size: 12px;
color: #000;
}
</style>
<body>
<section class="cta_part">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12">
                <div class="cta_part_iner">
                    <div class="cta_part_text center_div">
                        <form class="form-horizontal form" id="talentForm" role="form" method="post" novalidate="">
                            <div class="step">
                                <h1>Why join Buddey Talent ?</h1>
                                    <div class="row">
                                        <div class="column">
                                            <img src="<?= $dirUrl; ?>assets/img/money.png" alt="Snow" style="height: 190px;width:100%">
                                            <h4 class="text-left">Earn more money</h4>
                                            <p class="text-left" style="font-size: 12px;color: #000;">
                                               Earn money when you do busking at our Buddey Buskers Pods. Also when you sign up as Buddey Talent, you will be abel to get more gigs and jobs from our business partners.</p>   
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
                                                <li class="step0" id="step3">Transactions</li>
                                            </ul> 
                                        </div>     
                                        <div class="form-group">
                                            <input class="form-control" name="email" id="email" type="email" placeholder="Enter email address">
                                        </div>
                                        <div class="form-group">
                                          <div class="row">
                                            <div class="col-sm-2" style="padding-right: 0px;">
                                              <select class="form-control" style="width: 100%;" name="countryCode" id="countryCode">
                                                <option selected value="+60">MY+60</option>
                                              </select>
                                            </div>
                                            <div class="col-sm-10">
                                                <input class="number form-control" type="text" class="form-control number" maxlength="12" name="mobile" id="mobile" placeholder="Exampel 123456789">
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
                                            <div class="g-recaptcha" data-sitekey="<?php echo RECAPTCHA_SITE_KEY;?>"></div>
                                          </div>
                                        </div>    
                                    </div>           
                                    <div class="step">
                                        <h1>Talent Sign Up</h1>
                                        <div style="text-align:center;margin-top:40px;">
                                            <ul id="progressbar" class="text-center">
                                                <li class="active step0" id="step1">Login Details</li>
                                                <li class="active step0" id="step2">Talent Profile</li>
                                                <li class="step0" id="step3">Transactions</li>
                                            </ul> 
                                        </div> 
                                        <div class="form-group">
                                            <input class="form-control" required="" name="first_name" id="first_name" type="text" placeholder="Enter full name">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" name="last_name" id="last_name" type="text" placeholder="Enter other name e.g. name that you would like to be known as">
                                        </div>
                                        <div class="form-group">
                                            <select class="form-control" name="city" id="city">
                                                <option value="0">Select City</option>
                                                <?php 
                                                if($cityLists){
                                                    foreach ($cityLists as $cityInfo) {
                                                      ?><option value="<?php echo $cityInfo->id; ?>"><?php echo $cityInfo->name; ?></option><?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Enter area e.g. Mont Kiara,Wangsa Maju" id="area" name="area">
                                        </div>     
                                        <div class="form-group">
                                            <input type="text" placeholder="Enter brithdate" class="form-control datepicker" id="age" name="age">
                                        </div>
                                        <div class="form-group">
                                            <input class="number form-control" required="" name="nric_number" id="nric_number" type="text" placeholder="Enter NRIC e.g. 12345678910">
                                        </div>
                                        <div class="form-group">
                                            <select class="form-control" id="gender" name="gender"> 
                                              <option value="0">Select gender</option>
                                              <option value="1">Male</option>
                                              <option value="2">Female</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <select class="form-control" name="skills_category" id="skills_category">
                                              <option value="0">Select skills category</option>
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
                                            <input text="text" class="form-control" placeholder="Enter sub skills e.g. guitarist, dancer" id="sub_skills" name="sub_skills">
                                        </div>
                                         <div class="form-group">
                                            <input class="number form-control" required="" name="gigs_amount" id="gigs_amount" type="text" placeholder="Charges per gig RM300.00">
                                        </div>      
                                    </div>
                                    <div class="step">
                                        <h1>Talent Sign Up</h1>
                                        <div style="text-align:center;margin-top:40px;">
                                            <ul id="progressbar" class="text-center">
                                                <li class="active step0" id="step1">Login Details</li>
                                                <li class="active step0" id="step2">Talent Profile</li>
                                                <li class="active step0" id="step3">Transactions</li>
                                            </ul> 
                                        </div>
                                        <div class="form-group">
                                            This information is required for making any payments to you. You can skip to update later.
                                        </div> 
                                        <div class="form-group">
                                            <select class="form-control" id="bank_name" name="bank_name"> 
                                              <option value="0">Select Bank Name</option>
                                              <option value="AFFIN BANK">AFFIN BANK</option>
                                              <option value="ALLIANCE BANK">ALLIANCE BANK</option>
                                              <option value="AM BANK">AM BANK</option>
                                              <option value="BANK ISLAM">BANK ISLAM</option>
                                              <option value="BANK RAKYAT">BANK RAKYAT</option>
                                              <option value="BANK MUAMALAT">BANK MUAMALAT</option>
                                              <option value="BSN BANK">BSN BANK</option>
                                              <option value="CIMB BANK">CIMB BANK</option>
                                              <option value="HONGLEONG BANK">HONGLEONG BANK</option>
                                              <option value="HSBC BANK">HSBC BANK</option>
                                              <option value="KUWAIT FINANCE HOUSE">KUWAIT FINANCE HOUSE</option>
                                              <option value="MAY BANK 2E">MAY BANK 2E</option>
                                              <option value="MAY BANK">MAY BANK</option>
                                              <option value="OCBC BANK">OCBC BANK</option>
                                              <option value="PUBLIC BANK">PUBLIC BANK</option>
                                              <option value="RHB BANK">RHB BANK</option>
                                              <option value="STANDARD CHARTERED">STANDARD CHARTERED</option>
                                              <option value="UOB BANK">UOB BANK</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Bank Account Name" id="acc_name" name="acc_name">
                                        </div>
                                        <div class="form-group">
                                            <input text="text" class="form-control" placeholder="Bank Account Number" id="acc_no" name="acc_no">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="float-right">
                                                <button type="button" class="action btn-sky text-capitalize back btn">Back</button>
                                                <button type="button" class="action btn-sky text-capitalize new btn">Sign Up</button>
                                                <button type="button" class="action btn-sky text-capitalize next btn">Next</button>
                                                <button type="button" id="nextBtn" class="action btn-success text-capitalize submit btn">Complete Sign Up</button>
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
  $('.select2').select2();
});
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
      if(current < widget.length){
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
       if(city == ''){ alert('City cannot be empty.'); }
       if(area == ''){ alert('Area cannot be empty.'); }
       if(age == ''){ alert('Age cannot be empty.'); }
       if(nric_number == ''){ alert('NRIC Number cannot be empty.'); }
       if(gender == ''){ alert('Gender cannot be empty.'); }
       if(skills_category == ''){ alert('Skill category cannot be empty.'); }
       if(sub_skills == ''){ alert('Skill cannot be empty.'); }
       if(gigs_amount == ''){ alert('Amount cannot be empty.'); }
       if(bank_name == ''){ alert('Bank Name cannot be empty.'); }
       if(acc_name == ''){ alert('Account Name cannot be empty.'); }
       if(acc_no == ''){ alert('Account Number cannot be empty.'); }
       // Check Password .....
        if( email && countryCode && mobile && first_name && last_name && city && area && age && nric_number && gender && skills_category && sub_skills && gigs_amount && bank_name&& acc_name && acc_no){
            var data = $( "#talentForm" ).find( "select, input" ).serialize();
            $.ajax({
              type: "POST",
              url: baseurl + 'talent/register/addTalent',
              data: data,
              async : false,
              beforeSend: function() { 
                  $("#nextBtn").html('<img src="'+baseurl+'assets/img/loading.gif"> Loading...');
                  $("#nextBtn").prop('disabled', true);
                  $('#talentForm').css("opacity",".5");
              },
              success: function( msg ) {
                $("#nextBtn").html('Complete Sign Up');
                $("#nextBtn").prop('disabled', false);
                var obj = jQuery.parseJSON(msg);
                if( obj.res_status == 'success' ) {
                    $('#talentForm').trigger("reset");
                    toastr.success( obj.message,'Register Success' );
                    setTimeout( function() {
                        location.reload();
                    }, 1000 );
                } else {
                    alert(obj.message);
                }
                $('#talentForm').css("opacity","");
                },error: function() {
                    $("#nextBtn").html('Complete Sign Up');
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
        first_name       : "required",
        email            : {required : true, email:true},
        gender           : "required",
        privacy          : "required",
        city             : "required",
        area             : "required",
        gigs_amount      : "required",
        sub_skills       : "required",
        bank_name        : "required",
        acc_no           : "required",
        acc_name         : "required",
        mobile     : {required : true, minlength: 6},
      },
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
      btnsubmit.show();
    }
  }
</script>



