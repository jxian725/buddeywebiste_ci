<div class="row">
  <form novalidate="" class="form-horizontal" id="confirm_request_form" role="form" method="post">
    <div class="col-md-12">
      <div class="form-group">
        <div class="col-sm-12">
          <input type="text" class="form-control" value="<?= $paymenturl;?>" maxlength="100" name="copyTextLbl" id="copyTextLbl" placeholder="Payment URL">
          <input type="hidden" value="<?= $paymenturl;?>" name="copyText" id="copyText">
        </div>
      </div>
    </div>
    <div class="col-md-6 col-md-offset-4" style="padding-top: 25px;">
      <a href="javascript:;" class="btn btn-success btn-sm" onclick="return copyLink();">Copy Link</a>
    </div>
</form>
</div>
<script type="text/javascript">
function copyLink() {
  var copyText = document.getElementById("copyText");
  copyTextToClipboard(copyText.value);
}
function copyTextToClipboard(text) {
  var textArea = document.createElement("textarea");

  textArea.style.position = 'fixed';
  textArea.style.top = 0;
  textArea.style.left = 0;
  textArea.style.width = '2em';
  textArea.style.height = '2em';
  textArea.style.padding = 0;
  textArea.style.border = 'none';
  textArea.style.outline = 'none';
  textArea.style.boxShadow = 'none';
  textArea.style.background = 'transparent';
  textArea.value = text;
  document.body.appendChild(textArea);
  textArea.select();
  try {
    var successful = document.execCommand('copy');
    var msg = successful ? 'successful' : 'unsuccessful';
    console.log('Copying text command was ' + msg);
  } catch (err) {
    console.log('Oops, unable to copy');
  }
  document.body.removeChild(textArea);
}
</script>