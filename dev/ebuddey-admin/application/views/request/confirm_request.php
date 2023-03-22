<div class="row">
  <form novalidate="" class="form-horizontal" id="confirm_request_form" role="form" method="post">
    <div class="col-md-12">
      <div class="form-group">
        <label for="budget" class="col-sm-4 control-label">Initial Budget<span class="text-danger">*</span></label>
        <div class="col-sm-8">
          <input type="text" class="form-control number" disabled value="<?= $requestInfo->budget;?>" maxlength="5" name="budget" id="budget" placeholder="Budget">
        </div>
      </div>
      <div class="form-group">
        <label for="confirm_budget" class="col-sm-4 control-label">Confirmed Budget<span class="text-danger">*</span></label>
        <div class="col-sm-8">
          <input type="text" class="form-control number" value="<?= $requestInfo->budget;?>" maxlength="5" name="confirm_budget" id="confirm_budget" placeholder="Budget">
        </div>
      </div>
      <div class="form-group">
        <label for="mobile_number" class="col-sm-4 control-label">Payment Type</label>
        <div class="col-sm-8">
          <div class="col-md-3">
            <label class="control-label" for="paymentType1">
              <input type="radio" name="paymentType" id="paymentType1" value="1">
              Cash
            </label>
          </div>
          <div class="col-md-6">
            <label class="control-label" for="paymentType2">
              <input type="radio" name="paymentType" id="paymentType2" value="2">
              Senangpay Pay
            </label>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-md-offset-4" style="padding-top: 25px;">
      <input type="hidden" name="request_id" id="request_id" value="<?=$request_id;?>">
      <a href="javascript:;" class="btn btn-success btn-sm" onclick="return confirmRequest( '<?=$request_id;?>' );">Confirm Request</a>
    </div>
</form>
</div>
<script type="text/javascript">
$('.number').keypress(function(event) {
  if (event.which == 8 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46) {
      return true;
  }else if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
      event.preventDefault();
  }
});
function confirmRequest( request_id ) {
  var table2 = $('#request_list').DataTable();
  var data = $( '#confirm_request_form' ).serialize();
  var confirm_budget = $('#confirm_budget').val();
  var paymentType = $('input[name=paymentType]:checked').val();
  if(confirm_budget == ""){
    toastr.error( 'Please enter the confirmed budget.','Error' );
    return false;
  }else if(typeof paymentType == "undefined" || paymentType == ''){
    toastr.error( 'Please select the payment type.','Error' );
    return false;
  }else{
    $.ajax( {
        type    : "POST",
        data    : data,
        url     : adminurl + 'request/confirmRequest',
        dataType: 'json',
        success: function( msg ) {
            if( msg.res == 1 ) {
               toastr.success( 'Request confirmation successfully.','Success' );
               table2.ajax.reload();
            } else {
                toastr.error( msg.Jmsg, 'Error' );
            }
            $( '#myModal' ).modal( 'hide' );
            $( '#confirm_request_form #confirm_budget' ).val('');
        }
    });
  }
  return false;
}
</script>