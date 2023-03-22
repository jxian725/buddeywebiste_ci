<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl = $this->config->item( 'admin_url' );
global $permission_arr;
?>
<div class="row">
    <?php 
    if( $this->session->flashdata( 'errorMSG2' ) ) { ?>
        <div class="col-xs-12 col-sm-12 margin_t10">
            <div class="alert alert-danger">
                <?php echo $this->session->flashdata( 'errorMSG2' ); ?>
            </div>
        </div>
    <?php 
    }
    if( $this->session->flashdata( 'successMSG2' ) ) { ?>
        <div class="col-xs-12 col-sm-12 margin_t10">
            <div class="alert alert-success">
                <?php echo $this->session->flashdata( 'successMSG2' ); ?>
            </div>
        </div>
    <?php 
    } ?>
    <div class="col-xs-12 col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-">
                <h3 class="box-title"></h3>
                <div class="pull-right box-tools">
                    <?php if( in_array( 'journey/add', $permission_arr ) ) { ?>
                    <a href="<?= $assetUrl; ?>faq/add" class="btn btn-success btn-sm pull-right" data-toggle="tooltip" title="" style="margin-right: 5px;" data-original-title="Add New">
                        <i class="fa fa-plus"></i>&nbsp; Add
                    </a>
                    <?php } ?>
                </div>
            </div>
            <div class="box-body">
                <section id="no-more-tables">
                    <table class="table table-bordered table-striped table-condensed cf">
                        <thead class="cf">
                            <tr>
                                <th>Title</th>
                                <th>Description</th> 
                                <th style="width: 6%;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="faq_lists">
                            <?php 
                            if( $faq_lists ) {
                                foreach ( $faq_lists as $key => $value ) {
                                    ?>
                                    <tr>
                                        <td><?= $value->title; ?></td>
                                        <td><?= $value->content; ?></td>
                                        <td>
                                            <?php
                                        if( in_array( 'journey/add', $permission_arr ) ) {
                                          echo '<a href="'.$assetUrl.'faq/edit/'.$value->faq_id.'" class="btn btn-info btn-xs"><i class="fa fa-edit"></i></a>&nbsp;';
                                        }
                                        if( in_array( 'journey/delete', $permission_arr ) ) {
                                            echo '<span onClick="return deleteFaq( ' . $value->faq_id . ' );" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></span>';
                                        }
                                        ?>
                                        </td>
                                    </tr>
                                <?php
                                }
                            } else { ?>
                                <tr><td colspan="3">No data to display.</td></tr>
                                <?php
                            }
                        ?>
                        </tbody>
                    </table>
                </section>
            </div>
        </div>
    </div>        
</div>
<script type="text/javascript">
//DELETE FAQ
function deleteFaq( faq_id ) {
    
    $( '#small_modal' ).modal();
    $( '#sm_modal_title' ).html( 'Are you Sure?' );
    $( '#sm_modal_body' ).html( 'Do you really want to delete this record?' );
    $( '#sm_modal_footer' ).html( '<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Cancel</button><button type="button" id="continuemodal'+faq_id+'" class="btn btn-sm btn-success">Yes</button>' );
    $( '#continuemodal'+faq_id ).click( function() {
        $.ajax({
            type : 'POST',
            url  : adminurl + 'faq/deleteFaq',
            data : { 'faq_id' : faq_id },
            beforeSend: function() { 
                $("#continuemodal"+faq_id).html('<img src="'+adminurl+'assets/img/input-spinner.gif"> Loading...');
                $("#continuemodal"+faq_id).prop('disabled', true);
            },
            success : function( msg ) {
                window.location.reload();
            }
        });
    });
}
</script>