<?php 
$assetUrl   = $this->config->item( 'base_url' ); 
?>
<style type="text/css">
.row > .column {
  padding: 5px;
}

.row:after {
  content: "";
  display: table;
  clear: both;
}

.column {
  float: left;
  width: 15%;
}
</style>
<div class="row">
  <form novalidate="" id="profileForm" role="form" method="post" enctype="multipart/form-data">  
    <div class="col-md-12">     
      <?php 
          if($field == '0'){ ?>
            <div class="form-group">
                <div class="row">
                    <div class="column">
                      <a href="https://www.facebook.com/" target="_blank"><img src="<?= $assetUrl;?>img/facebook.png" style="width:50%"></a>
                    </div>
                    <div class="column">
                       <a href="https://web.whatsapp.com/" target="_blank"><img src="<?= $assetUrl;?>img/whatsapp.jpg" style="width:90%"></a>
                    </div>
                    <div class="column">
                       <a href="https://twitter.com/" target="_blank"><img src="<?= $assetUrl;?>img/twitter.png" style="width:80%"></a>
                    </div>
                    <div class="column">
                       <a href="https://mail.google.com/mail/u/0/#inbox"  target="_blank"><img src="<?= $assetUrl;?>img/email.jpg" style="width:50%"></a>
                    </div>
                    <div class="column">
                       <a href="https://instagram.com/" target="_blank"><img src="<?= $assetUrl;?>img/instagram.jpg" style="width:100%"></a>
                    </div>
                    <div class="column">
                       <a href="https://skype.com/" target="_blank"><img src="<?= $assetUrl;?>img/skype.jpg" style="width:100%"></a>
                    </div>
                </div>  
            </div> 
            <?php $url = ("http://www.buddeytf.com/talent/view/$id"); ?>     
            <div class="input-group">
              <input type='text' class="form-control" name="url" id="url" value="<?=$url;?>"/>
              <span class="input-group-btn">
                <button type="button" class="btn btn-primary" onclick="GeeksForGeeks()"><i class="fa fa-copy"></i></button>
              </span>
            </div>
            <div class="box-footer">
      <?php
        }
      ?>       
    </div>
  </form>
</div>
<!-- end img -->
<script> 
  function GeeksForGeeks() { 
    var copyGfGText = document.getElementById("url"); 
    copyGfGText.select(); 
    document.execCommand("copy"); 
    alert("Copied the text: " + copyGfGText.value); 
  }  
</script> 