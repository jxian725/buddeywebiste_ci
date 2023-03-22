<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl       = $this->config->item( 'admin_url' );
$site_name      = $this->config->item( 'site_name' );
global $permission_arr;
?>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box box-primary">
            <div class="box-header">
                <div class="box-tools pull-right">
                    <a href="<?php echo base_url(); ?>cms/pages" class="btn btn-sm btn-primary">Back</a>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                      <div id="message_cms"></div>
                      <?php if( in_array( 'cms/add', $permission_arr ) ) { ?>
                      <form novalidate="" id="add_page_form" role="form" method="post" class="form-horizontal">
                        <div class="box-body">
                          <div class="form-group">
                              <textarea class="form-control" name="page_content" id="page_content"><?= rawurldecode( $pageInfo->page_content ); ?></textarea>
                          </div>
                          <div class="row">
                            <input type="hidden" name="pageID" id="pageID" value="<?= $pageInfo->page_id; ?>" />
                            <button class="btn btn-sm btn-info" id="addpagesubmit" type="submit">Update Content</button>
                          </div>
                        </div>
                      </form>
                      <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?=$this->config->item( 'dir_url' );?>js/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
    tinymce.init({
    selector: '#page_content',
    height: 700,
    menubar: false,
    plugins: [
        'advlist autolink lists link image charmap print preview anchor textcolor',
        'searchreplace visualblocks code fullscreen',
        'insertdatetime media table contextmenu paste code help wordcount'
    ],
    toolbar: 'insert | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
    content_css: [
        '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
        '//www.tinymce.com/css/codepen.min.css']
    });
</script>