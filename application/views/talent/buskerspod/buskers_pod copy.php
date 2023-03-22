<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 
$assetUrl   = $this->config->item( 'admin_dir_url' );
$site_name  = $this->config->item( 'site_name' );
$dirUrl     = $this->config->item( 'dir_url' );
?>

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script> -->

<link rel="stylesheet" href="<?php echo $assetUrl; ?>plugins/bootstrap-datepicker/bootstrap-datepicker.min.css">
<script src="<?php echo $assetUrl; ?>plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<style type="text/css">
  /*view more option   */
.show-read-more .more-text{
  display: none;
}
</style>
<div class="count_box row">
    <div class="col-md-3">
        <div class="col-xl-12 col-sm-14" style="padding-left: 0px;">
            <div class="box box-white">
                <div class="box-header with-border">
                    <h4>Select city</h4>
                    <?php
                      $attributes = array('method' => 'get', 'id' => 'searchform');
                      echo form_open( $dirUrl.'talent/buskerspod', $attributes );
                      $partner_search = $this->input->get('partner_search');
                      $partner_id     = $this->input->get('partner_id');
                      //$city           = $this->input->get( 'city' );
                    ?>
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
                    <h4>Select Busker pod</h4>
                    <div class="input-group">
                        <input type="text" name="partner_search" id="partner_search" class="search-query form-control" value="<?php echo $partner_search; ?>" placeholder="search busker pod..">
                        <div class="input-group-btn">
                           <button data-type="last" type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                        </div>
                    </div>

                    <div class="">
                        <ul class="list-group" id="city_lists" style="margin-top: 10px;">
                        <?php 
                        if($partner_List){
                            foreach ($partner_List as $partner) { ?>
                            <li class="list-group-item">
                              <a href="javascript:;" onclick="return showPartnerInfo(<?= $partner->partner_id; ?>)" class="" value="'.$partner->partner_id.'" class="" id="partner_id" value="<?= $partner->partner_id; ?>"><?= rawurldecode($partner->partner_name); ?></a>
                            </li>
                            <?php
                            }
                        } ?>
                      </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>                 
    <div class="col-md-5">
        <div class="col-xl-12 col-sm-12" style="padding-left: 0px;">
            <div class="box box-white">
                <div class="box-header with-border">
                  <?php 
                      if($partner_search || $partner_id){
                        foreach ($partnerList as $partner) { 
                        $photo = ($partner->photo);  ?>
                    <div class="form-group">
                      <img src="<?= $assetUrl; ?>uploads/partner/<?= $photo; ?>" style="height:40%;width:100%;">
                    </div> 
                    <h4>Important Information</h4>
                    <div class="form-group">
                      <div></div>
                    </div> 
                    <div class="form-group">
                      <div><?= rawurldecode($partner->partner_name); ?>, <?= $partner->cityName;?></div>
                    </div> 
                    <h4>CHECK IN/CHECK OUT</h4>
                    <div class="form-group">
                      <p class="show-read-more"><?= $partner->address; ?></p>
                    </div>
                    <?php
                        }
                    }else{ ?>
                      <center><span class="label label-info" style="width: 100%;">Please Select Busker pod</span></center>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>    
    <div class="col-md-4">
      <div class="col-xl-12 col-sm-14" style="padding-left: 0px;">
          <div class="box box-white">
              <div class="box-header with-border">
                  <h4>Select date</h4>
                  <div class="form-group">
                    <div class="box-body no-padding">
                       <input type="text" name="start_date" id ="datepicker-13" style="width: 100%;">
                    </div>
                  </div>
                  <div class="form-group">
                   <div class="box box-solid bg-green-gradient">
                     <div class="box-header">
                        <i class="fa fa-calendar"></i>
                        <h3 class="box-title">Calendar</h3>
                        <!-- tools box -->
                        <div class="pull-right box-tools">
                        <!-- button with a dropdown -->
                          <button type="button" class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
                           </button>
                        </div>
                        <!-- /. tools -->
                      </div>
                      <!-- /.box-header -->
                      <div class="box-body no-padding">
                        <!--The calendar -->
                        <div id="calendar" style="width: 100%"></div>
                      </div>
                    </div>
                  </div>  
                  <h4>Select Time</h4>
                  <div class="form-group">
                    <b>Quantity X <span id="result"></span></b>
                  </div>
                    <table class="table table-bordered">
                      <tr>
                        <th>Start</th>
                        <th>End</th>
                        <th>Status</th>
                        <th>RM</th>
                        <th>Action</th>
                      </tr>
                      <?php 
                      if($eventList){
                        foreach ($eventList as $event) { 
                        $start  = date('H:i', strtotime($event->start));
                        $end    = date('H:i', strtotime($event->end));
                        $amount = ((is_numeric($event->partnerFees))? number_format($event->partnerFees, 2) : ''); 
                      ?>
                      <tr>
                        <td style="color: #32CD32; font-size:18px;"><?= $start;?></td>
                        <td style="color: #32CD32; font-size:18px;"><?= $end; ?></td>
                        <td style="color: #32CD32; font-size:18px;">Available</td>
                        <td style="color: #32CD32; font-size:18px;">$<?= $amount;?></td>
                        <td><input type="checkbox" name="event_id" id="event_id" value="<?= $event->id;?>" style="color: #32CD32; font-size:18px;"/></td>
                        <input type="hidden" name="qnty" value="1">
                      </tr>
                      <?php
                        }
                      }else{ ?>
                      <center><span class="label label-info" style="width: 100%;">No data Found</span></center>
                      <?php } ?>
                    </table>  
                  <div class="form-group">
                    <b class="text pull-left">Total</b>
                    <p class="text pull-right"><b>RM</b> <span id="price"></span>.00</p>
                  </div> 
                  <div class="form-group">
                    <button class="btn btn-primary" style="width: 100%">Add to booking</button>
                  </div>
                  <div class="form-group">
                    <button class="btn btn-primary" style="width: 100%">Review booking</button>
                  </div>   
              </div>
          </div>
      </div>
    </div>         
</div>
<div class="count_box row">            
    <div class="col-md-6">
      <div class="col-xl-12 col-sm-14" style="padding-left: 0px;">
          <div class="box box-white">
              <div class="box-header with-border">
                  <h4>Booking Summary</h4>
                    <table class="table table-bordered">
                      <tr>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Action</th>
                      </tr>
                      <!--<?php 
                     // if($eventList){
                        //foreach ($eventList as $event) { 
                       // $start  = date('H:i', strtotime($event->start));
                       // $end    = date('H:i', strtotime($event->end));
                        //$amount = ((is_numeric($event->partnerFees))? number_format($event->partnerFees, 2) : ''); 
                     // ?>-->
                      <tr>
                        <td>raasik</td>
                        <td>120</td>
                        <td><a href="#">Delete</a></td>
                      </tr>
                     <!--// <?php
                        //}
                      //}else{ ?>
                      <center><span class="label label-info" style="width: 100%;">No data Found</span></center>
                      //<?php// } ?>-->
                    </table>
                    <div class="form-group">
                      <b class="text pull-left">Total</b>
                      <p class="text pull-right"><b>RM</b> <span id="price"></span>.00</p>
                    </div>
                    <div class="form-group">
                      <button class="btn btn-default" style="width: 100%">Back</button>
                    </div>
                    <div class="form-group">
                      <button class="btn btn-primary" style="width: 100%">PROCEED PAYMENT</button>
                    </div>
                    <div class="form-group">
                      <center><p>All purchases are non refundable, please ensure you practice responsible booking.</p></center> 
                    </div>    
              </div>
          </div>
      </div>
    </div>
    <div class="col-md-6">
        <div class="col-xl-12 col-sm-14" style="padding-left: 0px;">
            <div class="box box-white">
                <div class="box-header with-border">
                    <h4>SenangPay</h4>
                    <div class="form-group">
                      <b>Buddey Technology</b>
                    </div>
                    <div class="form-group">
                      <p>Payment date</p>
                      <p>rr</p>
                      <p>rrr</p>
                    </div>
                    <h4>Order Summary</h4>
                    <div class="form-group">
                      <p>Buddeysenangpayspace</p> <b class="text pull-right">RM <span>100.00</span></b>
                      <p>Team Buddey</p>
                    </div>
                    <div class="form-group">
                      <button class="btn btn-default" style="width: 100%">Total Price 100</button>
                      <button class="btn btn-default" style="width: 100%">Grand Total (RM) 100</button>
                    </div>
                    <h4>Your Contact Information</h4>
                    <div class="form-group">
                      <div class="input-group">
                          <input type="text" name="name" id="name" class="search-query form-control" value="" placeholder="">
                          <div class="input-group-btn">
                             <button data-type="last" type="submit" class="btn btn-default"><i class="fa fa-compass"></i></button>
                          </div>
                      </div>
                      <div class="input-group">
                          <input type="text" name="name" id="name" class="search-query form-control" value="" placeholder="">
                          <div class="input-group-btn">
                             <button data-type="last" type="submit" class="btn btn-default"><i class="fa fa-compass"></i></button>
                          </div>
                      </div>
                      <div class="input-group">
                          <input type="text" name="name" id="name" class="search-query form-control" value="" placeholder="">
                          <div class="input-group-btn">
                             <button data-type="last" type="submit" class="btn btn-default"><i class="fa fa-compass"></i></button>
                          </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <button class="btn btn-success" style="width: 100%">Credit / Debit Card</button>
                      <button class="btn btn-default" style="width: 100%">Internet Banking FPX</button>
                    </div>
                    <div class="form-group">
                      <input type="text" name="name" id="name" class="search-query form-control" value="" placeholder="Card Holder Name...">
                    </div>
                    <div class="form-group">
                      <input type="text" name="name" id="name" class="search-query form-control" value="" placeholder="Credit / Debit Card Number">
                    </div>
                    <div class="form-group">
                      <tr><select class="col-md-4" style="height: 40px;">
                       <option>Year</option>
                        <?php
                          $firstYear = (int)date('Y') - 20;
                          $lastYear = $firstYear + 24;
                          for($i=$firstYear;$i<=$lastYear;$i++)
                          {
                              echo '<option value='.$i.'>'.$i.'</option>';
                          }
                        ?>   
                      </select></tr>
                      <select class="col-md-4" style="height: 40px;">
                       <option>Month</option>
                        <?php
                          for($m=1; $m<=12; ++$m){
                            $label = date('m', mktime(0, 0, 0, $m, 1));  
                            echo "<option value='$label'>$label</option>";
                          }
                        ?>
                      </select>
                      <input type="text" name="name" id="name" style="height: 40px;" class="col-md-3" value="" placeholder="CVV">
                    </div>
                </div>
            </div>
        </div>
    </div>              
</div>
<!-- daterangepicker -->
<script src="<?= $assetUrl; ?>assets/js/moment.min.js"></script>
<!-- datepicker -->
<!-- Bootstrap WYSIHTML5 -->
<script type="text/javascript">
function showPartnerInfo(partner_id){
  if(partner_id){
    window.location.href = baseurl + 'talent/buskerspod?partner_id='+partner_id;
  }
}
$(".filterBtn").click(function(){
  var value = $(this).val();
  if(value=='all'){ 
    window.location.href = baseurl + 'talent/buskerspod'; 
  }else{
    window.location.href = baseurl + 'talent/buskerspod?filter='+value;
  }
});
// count the total amount....
$(document).ready(function() {
function calculateSum(){
 var sumTotal=0;
    $('table tbody tr').each(function() {
      var $tr = $(this);

      if ($tr.find('input[type="checkbox"]').is(':checked')) {
          
        var $columns = $tr.find('td').next('td').next('td');
        var $Qnty=parseInt($tr.find('input[type="hidden"]').val());
        var $Cost=parseInt($columns.next('td').html().split('$')[1]);
        sumTotal+=$Qnty*$Cost;
      }
    });
       $("#price").val(sumTotal);
        document.getElementById("price").innerHTML =
              sumTotal;
}
  $('#sum').on('click', function() {
    calculateSum();
  });
  $("input[type='text']").keyup(function() {
     calculateSum();
  });
   $("input[type='checkbox']").change(function() {
     calculateSum();

  });
});
// Count quantity function
showChecked();
function showChecked(){
  document.getElementById("result").textContent = + document.querySelectorAll("input:checked").length;
}
document.querySelectorAll("input[name=event_id]").forEach(i=>{
 i.onclick = function(){
  showChecked();
 }
});
</script>
<script type="text/javascript">
$(document).ready(function(){
  var maxLength = 300;
  $(".show-read-more").each(function(){
    var myStr = $(this).text();
    if($.trim(myStr).length > maxLength){
      var newStr = myStr.substring(0, maxLength);
      var removedStr = myStr.substring(maxLength, $.trim(myStr).length);
      $(this).empty().html(newStr);
      $(this).append(' <a href="javascript:void(0);" class="read-more">read more...</a>');
      $(this).append('<span class="more-text">' + removedStr + '</span>');
    }
  });
  $(".read-more").click(function(){
    $(this).siblings(".more-text").contents().unwrap();
    $(this).remove();
  });
});
</script>
<script>
 $(function() {
    $( "#datepicker-13" ).datepicker();
    $( "#datepicker-13" ).datepicker("show");
 });


 $(document).ready(function() {
   //On pressing a key on "Search box" in "search.php" file. This function will be called.
   $("#partner_search").keyup(function() {
       var name = $('#partner_search').val();
       $.ajax({
           type: "POST",
           url: baseurl + 'talent/buskerspod/getPartnerLists',
           data: {
              search: name
           },
           success: function(html) {
               $("#city_lists").html(html).show();
           }
       });
   });
});
</script>