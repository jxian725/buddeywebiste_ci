<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$adminUrl = $this->config->item( 'admin_dir_url' );
?>
<style type="text/css">
.popup_qrImg{ text-align: center; }
</style>
<?php if($donation_type == 1){ ?>
<div class="portlet-body form add_reward_form">
    <div class="form-body">
        <div class="row">
            <div class="col-md-12">
                <div class="popup_qrImg"><img style="width: 50%;" src="<?= $adminUrl; ?>uploads/qrscan/<?=$talentInfo->qr_image;?>"></div>
            </div>
        </div>
    </div>
    <div class="form-actions">
        <a href="javascript:;" class="btn btn-info" onclick="return print_qr_code( 'printableArea', '<?=$talent_id;?>' );">Print</a>
        <button data-dismiss="modal" type="button" class="btn btn-danger">Cancel</button>
    </div>
</div>
<?php }elseif ($donation_type == 2) { ?>
<div class="portlet-body form add_reward_form">
    <div class="form-body">
        <div class="row">
            <div class="col-md-12">
                <input type="text" class="form-control" value="<?=$donateurl;?>" id="donateurl">
                <div class="form-actions box-footer pull-right">
                  <button class="btn btn-sm btn-primary" onclick="copyLink()">Copy text</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<div id="printableArea" style="display: none;" class="printableArea">
<style type="text/css">
#print_sec .clearfix:after {
  content: "";
  display: table;
  clear: both;
}
#print_sec header {
  padding: 10px 0;
  margin-bottom: 30px;
}

#print_sec #logo {
  text-align: center;
  margin-bottom: 10px;
}
#print_sec h1 {
  border-top: 1px solid  #5D6975;
  border-bottom: 1px solid  #5D6975;
  color: #5D6975;
  font-size: 2.4em;
  line-height: 1.4em;
  font-weight: normal;
  text-align: center;
  margin: 0 0 20px 0;
}
#print_sec h2 {
  color: #333;
  font-size: 2.2em;
  line-height: 1.2em;
  font-weight: normal;
  text-align: center;
  margin: 0 0 20px 0;
}
#print_sec h3 {
  color: #000;
  font-size: 1.8em;
  line-height: 0.5em;
  font-weight: normal;
  text-align: center;
  margin: 20px 0 0 0;
}
#print_sec #project {
  float: left;
}
#print_sec #project span {
  color: #5D6975;
  text-align: right;
  width: 52px;
  margin-right: 10px;
  display: inline-block;
  font-size: 0.8em;
}
#print_sec #company {
  float: right;
  text-align: right;
}
#print_sec #project div,
#print_sec #company div {
  white-space: nowrap;        
}
#print_sec table {
  width: 100%;
  border-collapse: collapse;
  border-spacing: 0;
  margin-bottom: 20px;
}
#print_sec table tr:nth-child(2n-1) td {
  background: #F5F5F5;
}
#print_sec table th,
#print_sec table td {
  text-align: center;
}
#print_sec table th {
  padding: 5px 20px;
  color: #5D6975;
  border-bottom: 1px solid #C1CED9;
  white-space: nowrap;        
  font-weight: normal;
}
#print_sec table td {
  padding: 20px;
  text-align: right;
}
#print_sec footer {
  color: #5D6975;
  width: 100%;
  height: 30px;
  position: absolute;
  bottom: 0;
  border-top: 1px solid #C1CED9;
  padding: 8px 0;
  text-align: center;
}
</style>
    <div id="print_sec">
        <header class="clearfix">
          <h2>Buddey App</h2>
          <div id="project"></div>
        </header>
        <main>
          <table>
                <div id="logo">
                    <img src="<?= $adminUrl; ?>uploads/qrscan/<?=$talentInfo->qr_image;?>">
                    <div style="text-align: center;"><h3>Host: <b><?= $talentInfo->first_name;?></b></h3></div>
                </div>
          </table>
        </main>
        <footer></footer>
    </div>
</div>
<script type="text/javascript">
//Print QR Code
function print_qr_code( value, talent_id ){
    if( value == 'printableArea' ) {
        PrintMydetailReprint( 'printableArea' );
    }
    return false;
}
function PrintMydetailReprint( divName ) {
    var printContents = document.getElementById( divName ).innerHTML;
    var originalContents = document.body.innerHTML;
    var myWindow = window.open( "", "printwindow" );
    myWindow.document.write( printContents );
    myWindow.print();
    myWindow.close();
}

function copyLink() {
  /* Get the text field */
  var copyText = document.getElementById("donateurl");

  /* Select the text field */
  copyText.select();
  copyText.setSelectionRange(0, 99999); /*For mobile devices*/

  /* Copy the text inside the text field */
  document.execCommand("copy");

  /* Alert the copied text */
  alert("Copied the text: " + copyText.value);
}
</script>