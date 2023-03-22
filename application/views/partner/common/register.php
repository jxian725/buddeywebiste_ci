<?php
$dirUrl = $this->config->item( 'dir_url' );
$admin_url  = $this->config->item( 'admin_dir_url' ); 
?>
<!--Toastr message -->
<link rel="stylesheet" type="text/css" href="<?php echo $dirUrl; ?>assets/js/toastr/toastr.min.css">
<link rel="stylesheet" href="<?= $admin_url; ?>plugins/select2/select2.min.css">
<script src="<?= $admin_url; ?>plugins/select2/select2.full.min.js"></script>
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
</style>
<body>
<section class="cta_part">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12">
                <div class="cta_part_iner">
                    <div class="cta_part_text center_div">
                        <form class="form-horizontal form" id="partnerForm" role="form" method="post" novalidate="">
                                <div class="box row-fluid"> 
                                    <h1>Venue Partner Sign Up</h1>
                                    <div class="step">
                                        <div style="text-align:center;margin-top:40px;">
                                            <ul id="progressbar" class="text-center">
                                                <li class="active step0" id="step1">Register</li>
                                                <li class="step0" id="step2">Company Details</li>
                                                <li class="step0" id="step3">Transaction</li>
                                            </ul> 
                                        </div>     
                                        <div class="form-group">
                                            <input class="form-control" required="" name="company_name" id="company_name" type="text" placeholder="Enter Company Name">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" name="email" id="email" type="email" placeholder="Enter Email Address">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="password" class="form-control" id="password" placeholder="Enter Password">
                                            <div><small>Your password should be minimum 8 characters with at least 1 Uppercase, 1 lower, 1 number </small></div>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="rpassword" class="form-control" id="rpassword" placeholder="Re-enter Password">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control pnumber" maxlength="15" required="" name="mobile_no" id="mobile_no" type="text" placeholder="Enter Mobile Number">
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12 control-label text-left" for="privacy">
                                                <input type="checkbox" name="privacy" id="privacy" value="1" style="width: auto;">
                                                <span>&nbsp;&nbsp;&nbsp;I have read, understand and agree to the <a href="<?php echo $dirUrl; ?>privacypolicy">privacy policy</a></span>
                                            </label>
                                            
                                        </div>           
                                    </div>
                                    <div class="step">
                                        <div style="text-align:center;margin-top:40px;">
                                            <ul id="progressbar" class="text-center">
                                                <li class="active step0" id="step1">Register</li>
                                                <li class="active step0" id="step2">Company Details</li>
                                                <li class="step0" id="step3">Transaction</li>
                                            </ul> 
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
                                            <input type="text" class="form-control" placeholder="Enter Business Address" id="business_address" name="business_address">
                                        </div>     
                                        <div class="form-group">
                                            <input type="text" placeholder="Postcode" class="form-control" id="postcode" name="postcode">
                                        </div>    
                                        <div class="form-group">
                                            <input text="text" class="form-control" placeholder="Full name of contact person" id="contact_person" name="contact_person">
                                        </div>
                                    </div>
                                    <div class="step">
                                        <div style="text-align:center;margin-top:40px;">
                                            <ul id="progressbar" class="text-center">
                                                <li class="active step0" id="step1">Register</li>
                                                <li class="active step0" id="step2">Company Details</li>
                                                <li class="active step0" id="step3">Transaction</li>
                                            </ul> 
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
                                            <input type="text" class="form-control" placeholder="Bank Account Name" id="account_name" name="account_name">
                                        </div>
                                        <div class="form-group">
                                            <input text="text" class="form-control" placeholder="Bank Account Number" id="account_number" name="account_number">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="float-right">
                                                <button type="button" class="action btn-sky text-capitalize back btn">Back</button>
                                                <button type="button" class="action btn-sky text-capitalize new btn">Sign Up</button>
                                                <button type="button" class="action btn-sky text-capitalize next btn">Next</button>
                                                <button type="button" id="nextBtn" class="action btn-success text-capitalize submit btn">Complete Sign Up</button>
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
<!--  End --->
<script type="text/javascript">
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
       var company_name     = $( '#partnerForm #company_name' ).val();
       var mobile_no        = $( '#partnerForm #mobile_no' ).val();
       var email            = $( '#partnerForm #email' ).val();
       var password         = $( '#partnerForm #password' ).val();
       var city             = $( '#partnerForm #city' ).val();
       var business_address = $( '#partnerForm #business_address' ).val();
       var postcode         = $( '#partnerForm #postcode' ).val();
       var contact_person   = $( '#partnerForm #contact_person' ).val();
       var bank_name        = $( '#partnerForm #bank_name' ).val();
       var account_name     = $( '#partnerForm #account_name' ).val();
       var account_number   = $( '#partnerForm #account_number' ).val();
       // Check Password .....
      if(company_name && mobile_no && email && password && city && business_address && postcode && contact_person && bank_name && account_name && account_number){
       var data = $( "#partnerForm" ).find( "select, input" ).serialize();
        $.ajax({
          type: "POST",
          url: baseurl + 'partner/register/addPartner',
          data: data,
          async : false,
          beforeSend: function() { 
              $("#nextBtn").html('<img src="'+baseurl+'assets/img/loading.gif"> Loading...');
              $("#nextBtn").prop('disabled', true);
              $('#partnerForm').css("opacity",".5");
          },
          success: function( msg ) {
            $("#nextBtn").html('Complete Sign Up');
            $("#nextBtn").prop('disabled', false);
            var obj = jQuery.parseJSON(msg);
            if( obj.res_status == 'success' ) {
                $('#partnerForm').trigger("reset");
                toastr.success( obj.message,'Register Success' );
                setTimeout( function() {
                    location.reload();
                }, 1000 );
            } else {
                alert(obj.message);
            }
            $('#partnerForm').css("opacity","");
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
        company_name     : "required",
        email            : {required : true, email:true},
        privacy          : "required",
        city             : "required",
        business_address : "required",
        postcode         : "required",
        contact_person   : "required",
        bank_name        : "required",
        account_number   : "required",
        account_name     : "required",
        password         : {required : true, minlength: 8, password:true},
        mobile_no        : {required : true, minlength: 6},
        rpassword: { required : true, equalTo: "#password"},
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



