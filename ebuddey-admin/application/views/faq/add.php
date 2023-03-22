<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl = $this->config->item( 'admin_url' );
global $permission_arr;
?>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-">
                <h3 class="box-title"></h3>
                <div class="pull-right box-tools">
                    <a href="<?= $assetUrl; ?>faq" class="btn btn-primary btn-sm pull-right" data-toggle="tooltip" title="" style="margin-right: 5px;" data-original-title="Back">
                        &nbsp; Back
                    </a>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <form novalidate="" action="<?= $assetUrl; ?>faq/addFaq" id="add_page_form" role="form" method="post" class="form-horizontal">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="title">Title <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" maxlength="60" name="title" id="title" placeholder="Title">
                                    </div>
                                    <div class="form-group">
                                        <label for="content">Content <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="content" id="content"></textarea>
                                    </div>
                                    <div class="row">
                                        <button class="btn btn-sm btn-info" id="addfaqsubmit" type="submit">Add FAQ</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>        
</div>
<script src="<?=$this->config->item( 'dir_url' );?>js/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
    tinymce.init({
      selector: 'textarea#content',
      height: 300,
      plugins: 'image code',
      toolbar: 'undo redo | image code | bold italic backcolor ',

      /* without images_upload_url set, Upload tab won't show up*/
      files_upload_url: adminurl + 'faq/faqFileUpload',
      automatic_uploads: true,

      /* we override default upload handler to simulate successful upload*/
      images_upload_handler: function (blobInfo, success, failure) {
        var xhr, formData;

        xhr = new XMLHttpRequest();
        xhr.withCredentials = false;
        xhr.open('POST', adminurl + 'faq/faqFileUpload');

        xhr.onload = function() {
          var json;

          if (xhr.status != 200) {
            failure('HTTP Error: ' + xhr.status);
            return;
          }

          json = JSON.parse(xhr.responseText);

          if (!json || typeof json.location != 'string') {
            failure('Invalid JSON: ' + xhr.responseText);
            return;
          }
          success(json.location);
        };

        formData = new FormData();
        formData.append('file', blobInfo.blob(), blobInfo.filename());

        xhr.send(formData);
      }
    });
</script>