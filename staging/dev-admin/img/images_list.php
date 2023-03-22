<?php
    $type = 'events';
?>
<div class="row">
    <div class="col-sm-12 event_add_form_data">
        <?php echo form_open_multipart( '', 'id="event_add_form"' ); ?>
        <h4>Upload Images</h4>
        <!-- Change /upload-target to your upload address -->
        <div class="box box-solid">
            <div class="row">
                <div class="form-group">
                    <div class="col-sm-3">
                        <input type="file" name="images[]" id="upload_images" class="control-label" multiple="multiple" style="padding-top: 8px;">
                    </div>
                    <div class="col-sm-2">
                        <a href="javascript:;" onclick="return update_event_images('<?php echo $event_id;?>');" class="btn btn-success btn-sm">Add Event Images</a>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <input type="hidden" name="event_id" id="event_id" value="<?=$event_id;?>">
        <?php echo form_close(); ?> 
    </div>
</div>
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Event Image</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="ajax_images_list">  
            <?php
                $data_content[ 'event_id' ]   = $event_id;
                echo $this->load->view( 'events/ajax_images_list', $data_content, true );
            ?> 
        </tbody>
    </table>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
    //Update images Form
    function update_event_images( event_id ) {
        var user_form   = $( '.event_add_form_data' ).find('input, textarea, button, select');
        var data        = new FormData( $('#event_add_form')[0]);
        $.ajax({
            type        : 'POST',
            url         : '<?=site_url( 'events/update_event_images' );?>',
            data        : data,
            async       : false,
            contentType : false,
            processData : false,
            success     : function( msg ) {
                if( msg == 1 ) {
                    toastr.success( 'Event images added successfully.', 'Event' );
                    $( "#upload_images" ).val("");
                    event_image_list( event_id );
                } else {
                    toastr.error( msg, 'Event' );
                }
            }
        });
        return false;
    }
    //images Ajax List
    function event_image_list( event_id ) {
        var images_form_data     = '<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>&event_id=' + event_id;
        $.ajax({
            type        : 'POST',
            url         : '<?=site_url( 'events/event_image_list' );?>',
            data        : images_form_data,
            async       : false,
            success     : function( msg ) {
                $( '#ajax_images_list' ).html( msg );
            }
        });
        return false;   
    }
    function delete_event_image( event_id, event_image_id ) {
        $( '#myModal .modal-title' ).html( 'Confirm' );
        $( '#myModal .modal-body' ).html( 'Are you sure want to delete this event image?' );
        $( '#myModal .modal-body' ).append( '<br /><br /><button class="btn btn-info btn-sm" id="continuemodal" data-dismiss="modal">Yes</button>&nbsp;&nbsp;<button aria-hidden="true" data-dismiss="modal" class="btn btn-danger btn-sm">Cancel</button>' );
        $( '#myModal' ).modal({ backdrop: 'static', keyboard: false }); 
        $( "#continuemodal" ).click(function() {
            var user_data     = '<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>&event_image_id=' + event_image_id;
            $.ajax({
                type        : 'POST',
                url         : '<?=site_url( 'events/delete_event_image' );?>',
                data        : user_data,
                async       : false,
                success     : function( msg ) {
                    if( msg == 1 ) {
                        toastr.success(  'Event image deleted successfully' );
                        event_image_list( event_id );
                    } else {
                        toastr.error(  'Error while deleting event image' );
                    }
                    
                }
            });
        });    
        return false;
    }
</script>