<div class="row">
  <form novalidate="" class="form-horizontal" id="confirm_payment_form" role="form" method="post">
    <div class="col-md-12">
      <div class="form-group">
        <label for="budget" class="col-sm-4 control-label">Initial Budget<span class="text-danger">*</span></label>
        <div class="col-sm-8">
          <input type="text" class="form-control number" maxlength="5" disabled value="<?= $requestInfo->budget;?>" name="budget" id="budget" placeholder="Budget">
        </div>
      </div>
      <div class="form-group">
        <label for="confirm_budget" class="col-sm-4 control-label">Confirmed Budget<span class="text-danger">*</span></label>
        <div class="col-sm-8">
          <input type="text" class="form-control number" maxlength="5" disabled value="<?= $requestInfo->confirm_budget;?>" name="confirm_budget" id="confirm_budget" placeholder="Budget">
        </div>
      </div>
      <div class="form-group">
        <label for="ref_number" class="col-sm-4 control-label">Ref Number</label>
        <div class="col-sm-8">
          <input type="text" class="form-control" value="" maxlength="10" name="ref_number" id="ref_number" placeholder="Ref Number">
          
        </div>
      </div>
    </div>
    <div class="col-md-6 col-md-offset-4" style="padding-top: 25px;">
      <input type="hidden" value="<?= $request_id;?>" name="request_id" id="request_id">
      <a href="javascript:;" class="btn btn-success btn-sm" onclick="return completePayment();">Complete Payment</a>
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
function completePayment() {
  var table2 = $('#request_list').DataTable();
  var data   = $( '#confirm_payment_form' ).serialize();
  var amount = $('#confirm_budget').val();
  if(amount == ""){
    toastr.error( 'Please enter amount.','Error' );
    return false;
  }else{
    $.ajax( {
        type    : "POST",
        data    : data,
        url     : adminurl + 'request/completePayment',
        dataType: 'json',
        success: function( msg ) {
            if( msg.res == 1 ) {
               toastr.success( 'Payment Completed successfully.','Success' );
               table2.ajax.reload();
            } else {
                toastr.error( msg.Jmsg, 'Error' );
            }
            $( '#myModal' ).modal( 'hide' );
            $( '#confirm_payment_form #confirm_budget' ).val('');
        }
    });
  }
  return false;
}
</script>