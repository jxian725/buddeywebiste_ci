<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$admin_url    = $this->config->item( 'admin_url' );
$base_url     = $this->config->item( 'base_url' );
$front_url    = $this->config->item( 'front_url' );
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Buddey - Donation</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 , user-scalable=no">
    <link rel="shortcut icon" type="image/jpeg" href="<?= $front_url; ?>assets/img/favicon.png" sizes="16x16">
    <link rel="stylesheet" href="<?= $base_url; ?>css/font-awesome.min.css">
    <link rel="stylesheet" href="<?= $base_url; ?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= $base_url; ?>css/style.css">
    <link rel="stylesheet" href="<?= $base_url; ?>css/responsive.css">
    <script src="<?= $base_url; ?>js/jquery-1.12.1.min.js"></script>
    <script src="<?= $base_url; ?>js/bootstrap.min.js"></script>
    <script src="<?= $base_url; ?>js/custom.js"></script>
    <style type="text/css">
    .input-error{
      /*border-bottom: 1px solid #ec2222 !important;*/
      border: 1px solid #ec2222 !important;
    }
    .center_div{
        margin: 0 auto;
        width:60%;
    }
    .center {
      display: block;
      margin-left: auto;
      margin-right: auto;
      width: 35%;
    }
    .form-control {
      height: 42px;
    }
    #custom_amount{ font-size: 23px; }
    label {
      font-size: 20px;
    }
    .users-list-name
    {
      font-weight: bold;
      padding: 10px;
      font-size: 16px;
    }
    .show-mobile{ display: none; }
    #top_supporters_tbl tr td{
      border-top: none;
    }
    .img-circle{
      height: 150px;
      width: 150px;
    }
    @media screen and (max-width: 992px) {
      .center_div {
        width: 90%;
      }
      .center {
        width: 40%;
      }
      .hide-mobile{ display: none; }
      .show-mobile{ display: block; }
    }
    </style>
</head>
<?php
if($guiderInfo->profile_image){
    $profileImg = $admin_url.'uploads/g_profile/'.$guiderInfo->profile_image;
}else{
    $profileImg = $admin_url.'uploads/default_img.png';
}
?>
<body>
  <div class="wrapper">
    <div class="container-fluid">
      <div class="center_div">
        <div class="login-logo" style="margin-bottom: 20px;">
          <img class="center" src="<?= $base_url; ?>images/talent_login.png" alt="logo" />
        </div>
      </div>

      <div class="box-body show-mobile" style="padding: 15px 0px;">
        <center>
          <img src="<?= $profileImg; ?>" class="img-circle" alt="<?= (($guiderInfo)? $guiderInfo->last_name : ''); ?>">
          <div class="users-list-name"><?= (($guiderInfo)? $guiderInfo->last_name : ''); ?></div>
        </center>

        <div class="btn-pref btn-group btn-group-justified btn-group-lg" role="group" aria-label="...">
          <div class="btn-group" role="group">
              <button type="button" onclick="return viewAbout();" class="btn btn-default" data-toggle="tab">
                <div class="hidden-lg"><i class="fa fa-user" aria-hidden="true"></i> About</div>
              </button>
          </div>
          <!-- <div class="btn-group" role="group">
              <button type="button" onclick="return viewTopSupporters();" class="btn btn-default" data-toggle="tab">
                <div class="hidden-lg"><i class="fa fa-users" aria-hidden="true"></i>Top Supporters</div>
              </button>
          </div> -->
          <div class="btn-group" role="group">
              <button type="button" onclick="return viewComments();" class="btn btn-default" data-toggle="tab">
                <div class="hidden-lg"><i class="fa fa-comment" aria-hidden="true"></i> Comments</div>
              </button>
          </div>
        </div>
      </div>


      <div class="row">
        <div class="col-md-3 hide-mobile">
          <div class="thumbnail">
              <img src="<?= $profileImg; ?>" alt="Lights" style="width:100%">
              <div class="caption">
                <h5 style="font-size: 16px;"><b><?= (($guiderInfo)? $guiderInfo->last_name : ''); ?></b></h5>
                <div id="talent_aboutus"><p><?= $guiderInfo->about_me; ?></p></div>
              </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="mainter-form1">
            <form action="<?= $base_url; ?>donate/Makepayment/submit" method="post" id="paymentform" autocomplete="off">
              <!-- <h3 style="margin-bottom: 30px;">Select your preferred donation amount</h3> -->
              <div class="form-group">
                  <label class="form-label">Enter your preferred donation amount <small>(Min RM5.00 - Max RM 100.00)</small></label>
                  <input type="text" name="custom_amount" id="custom_amount" class="form-control number" maxlength="5" value="10" placeholder="RM0.00" />
              </div>
              <div class="checkmystatus">
                <div class="checkmystatus-listinf">
                  <input type="radio" value="5" name="amount" id="amount1" />
                  <label for="amount1">RM 5</label>
                </div>
                <div class="checkmystatus-listinf">
                  <input type="radio" value="10" checked name="amount" id="amount2" />
                  <label for="amount2">RM 10</label>
                </div>
                <div class="checkmystatus-listinf">
                  <input type="radio" value="20" name="amount" id="amount3" />
                  <label for="amount3">RM 20</label>
                </div>
                <div class="checkmystatus-listinf">
                  <input type="radio" value="30" name="amount" id="amount4" />
                  <label for="amount4">RM 30</label>
                </div>
                <div class="checkmystatus-listinf">
                  <input type="radio" value="50" name="amount" id="amount5" />
                  <label for="amount5">RM 50</label>
                </div>
              </div>
              <div class="form-row">
                <!-- <div class="form-group show-mobile">
                    <label class="form-label" style="margin-top: 10px;">Gift To</label>
                    <input type="text" name="gift_to" id="gift_to" disabled class="form-control" maxlength="30" value="<?= (($guiderInfo)? $guiderInfo->last_name : ''); ?>" placeholder="Talent other Name">
                </div> -->
                <div class="form-group">
                    <label class="form-label" style="margin-top: 10px;">Gift From</label>
                    <input type="text" name="fullname" id="fullname" class="form-control" maxlength="30" value="" placeholder="From Name (Optional)" />
                </div>
                <div class="form-group">
                    <textarea name="message" id="message" rows="4" class="form-control" placeholder="Enter a message (Optional)"></textarea>
                </div>
                <div class="form-group">
                  <input type="hidden" id="anonymous" name="anonymous" value="0" />
                  <input type="submit" class="btn btn-primary btn-lg btn-block" onclick="return formSubmit();" value="Proceed to donate" />
                  <input type="hidden" value="submit" name="submit"/>
                  <input type="hidden" name="form_request" id="form_request" value="qrpay"/>
                  <input type="hidden" value="<?= $uuID; ?>" name="uuID"/>
                </div>
            </div>

            <input type="hidden" name="email" id="email" maxlength="60" placeholder="Email" />
            <input type="hidden" name="phone" id="phone" maxlength="12" class="number" placeholder="Phone Number" />
            </form>
            <div class="importsocial">
              <a target="_blank" href="#">
                <img src="<?php echo $base_url; ?>images/sectigo_trust.png" alt="icon" style="width: 100px;" />
              </a>
              <a target="_blank" href="#">
                <img src="<?php echo $base_url; ?>images/senangpay.png" alt="icon" style="width: 100px;" />
              </a>
            </div>
            <div class="importsocial">
              <a target="_blank" href="http://www.buddeytf.com">Powered by www.buddeytf.com</a>
              <a target="_blank" href="<?php echo $front_url; ?>"><img src="<?php echo $base_url; ?>images/insta.png" alt="icon" /></a>
              <a target="_blank" href="https://www.facebook.com/buddeyapp/"><img style="width: 36px;" src="<?php echo $base_url; ?>images/fb.png" alt="icon" /></a>
            </div>
          </div>
        </div>
        <div class="col-md-3 hide-mobile">
          <div class="panel-group">
            <!-- <div class="panel panel-default">
              <div class="panel-heading">Top Supporters</div>
              <div class="panel-body" id="top_supporters_tbl">
                <table class="table">
                  <tbody>
                    <?php
                    $top_supporters_list111 = '';
                    if($top_supporters_list111){
                      $i = 1;
                      foreach ($top_supporters_list as $key => $supporters) {
                        ?>
                        <tr>
                          <td><?= $i; ?></td>
                          <td><?= ($supporters->fullName)? $supporters->fullName : 'Anonymous'; ?></td>
                          <td align="right">RM<?= number_format((float)$supporters->sub_total, 2, '.', ''); ?></td>
                        </tr>
                        <tr>
                        <?php
                        $i++;
                      }
                    }
                    ?>
                </tbody>
                </table>
              </div>
            </div> -->

            <div class="panel panel-default">
              <div class="panel-heading">Comments</div>
              <div class="panel-body" id="view_comments" style="max-height: 350px;overflow-y: scroll;">
                <?php
                if($comments_list){
                  foreach ($comments_list as $key => $comments) {
                    if(!$comments->message){ continue; }
                    ?>
                    <p><b><?= ($comments->fullName)? $comments->fullName : 'Anonymous'; ?>:</b> <?= $comments->message; ?></p>
                    <?php
                  }
                }
                ?>
              </div>
            </div>
          </div>
        </div>
      </div>
        
    </div>
  </div>

<script type="text/javascript">
$('.number').keypress(function(event) {
  if (event.which == 8 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46) {
      return true;
  }else if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
      event.preventDefault();
  }
});
$("form#paymentform input:radio").change(function () {
  $('form#paymentform #custom_amount').val($(this).val());
});
$('#custom_amount').keyup(function() {
  var txtVal = this.value;
  if(txtVal == 5){
    $("#amount1").attr('checked', true).trigger('click');
  }
  if(txtVal == 10){
    $("#amount2").attr('checked', true).trigger('click');
  }
  if(txtVal == 20){
    $("#amount3").attr('checked', true).trigger('click');
  }
  if(txtVal == 30){
    $("#amount4").attr('checked', true).trigger('click');
  }
  if(txtVal == 50){
    $("#amount5").attr('checked', true).trigger('click');
  }
});
function formSubmit() {
  var next_step = 1;
  var data      = $( 'form#paymentform' ).serialize();
  //var gift_to   = $( 'form#paymentform #gift_to' ).val();
  var fullname  = $( 'form#paymentform #fullname' ).val();
  var email     = $( 'form#paymentform #email' ).val();
  var phone     = $( 'form#paymentform #phone' ).val();
  var custom_amount = $( 'form#paymentform #custom_amount' ).val();
  var amount    = $('input[name="amount"]:checked').val();
  var anonymous = $( 'form#paymentform #anonymous' ).is(":checked");
  //$("#gift_to").removeClass('input-error');
  $("#fullname").removeClass('input-error');
  $("#email").removeClass('input-error');
  $("#phone").removeClass('input-error');
  $("#amount").removeClass('input-error');

  // if(gift_to == ''){
  //   $("#gift_to").addClass('input-error');
  //   next_step = 0;
  // }
  if(custom_amount){
    if(!$.isNumeric(custom_amount)){
      $("#custom_amount").addClass('input-error');
      next_step = 0;
    }
    if(custom_amount < 5 || custom_amount > 100){
      $("#custom_amount").addClass('input-error');
      next_step = 0;
    }
  }else{
    if(!$.isNumeric(amount)){
      $("#amount").addClass('input-error');
      next_step = 0;
    }
    if(amount < 5 || amount > 50){
      $("#amount").addClass('input-error');
      next_step = 0;
    }
  }
  if(next_step == 0){ return false; }
  if(next_step){
    $( '#paymentform' ).submit();
    return true;
  }

  // if(anonymous == 1){
  // }else{
  //   if(fullname == ''){
  //     $("#fullname").addClass('input-error');
  //     next_step = 0;
  //   }
  //   if (!ValidateEmail(email)) {
  //     $("#email").addClass('input-error');
  //     next_step = 0;
  //   }
  //   if(phone == ''){
  //     $("#phone").addClass('input-error');
  //     next_step = 0;
  //   }else{
  //     if(!$.isNumeric(phone)){
  //       $("#phone").addClass('input-error');
  //       next_step = 0;
  //     }
  //   }
  // }
  // if ($('input[name="amount"]:checked').length == 0) {
  //   $("#amount").addClass('input-error');
  //   next_step = 0;
  // }else{
  //   if(!$.isNumeric(amount)){
  //     $("#amount").addClass('input-error');
  //     next_step = 0;
  //   }
  //   if(amount < 5 || amount > 15){
  //     $("#amount").addClass('input-error');
  //     next_step = 0;
  //   }
  // }
  // if(next_step == 0){ return false; }
  // if(next_step){
  //   $( '#paymentform' ).submit();
  //   return true;
  // }
}
function ValidateEmail(email) {
  var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
  return expr.test(email);
}

function viewAbout(){
  $( '#myModal' ).modal( 'show' );
  $( '#modal-title' ).html('About Us');
  var aboutus = $("#talent_aboutus").html();
  $( '#modal-body' ).html(aboutus);
}
function viewTopSupporters(){
  $( '#myModal' ).modal( 'show' );
  $( '#modal-title' ).html('Top Supporters');
  var supporters = $("#top_supporters_tbl").html();
  $( '#modal-body' ).html(supporters);
}
function viewComments(){
  $( '#myModal' ).modal( 'show' );
  $( '#modal-title' ).html('Comments');
  var comments = $("#view_comments").html();
  $( '#modal-body' ).html(comments);
}

</script>
</body>

<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="modal-title"></h4>
      </div>
      <div class="modal-body" id="modal-body"></div>
    </div>
  </div>
</div>

</html>