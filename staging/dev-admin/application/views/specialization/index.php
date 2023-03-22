<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl       = $this->config->item( 'admin_url' );
$site_name      = $this->config->item( 'site_name' );
?>
<style type="text/css">
#imgspecialization { display:none;}
</style>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                      <div id="message_specialization"></div>
                      <form novalidate="" id="specialization_form" role="form" method="post" class="form-horizontal">
                        <div class="box-body">
                          <div class="form-group">
                            <label class="col-md-2 control-label" for="specialization">Specialization <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                              <input type="text" required="" placeholder="Enter Specialization" id="specialization" class="form-control" name="specialization">
                            </div>
                          </div>
                          <div class="col-md-offset-2 col-md-8 text-right">
                            <button class="btn btn-sm btn-success" id="addspecializationsubmit" onClick="return validatespecialization();" type="button">Add</button>
                          </div>
                        </div>
                      </form>
                      <div class="box-body table-responsive no-padding">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Specialization</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="specialization_lists">
                                <?php
                                    if( $specialization_lists ) {
                                        foreach ( $specialization_lists as $key => $value ) {
                                            ?>
                                            <tr>
                                                <td><?=rawurldecode( $value->specialization );?></td>
                                                <td>
                                                   <a class="btn btn-danger btn-sm" href="javascript:;" onclick="return delete_specialization( '<?=$value->specialization_id;?>' );">Delete</a> 
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                            <tr><td colspan="2">No data to display.</td></tr>
                                        <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    //Specialization Validation
    function validatespecialization() {
        var $btn  = $( '#addspecializationsubmit' );
        $btn.button( 'Posting..' );
        var data    = $( "#specialization_form" ).find( "select, textarea, input" ).serialize();
        $.ajax({
            type    : 'POST',
            url     : adminurl + 'specialization/specializationValidate',
            data    : data,
            success : function( msg ) {
                if( msg == 1 ) {
                    toastr.success('Specialization added Successfully.');
                    location.reload();
                } else {
                    toastr.error(msg,'Error');
                }
                $btn.button( 'reset' );
            }
        });
        return false;
    }
    function delete_specialization( specializationID ) {
      $( '#myModal .modal-title' ).html( 'Confirm' );
      $( '#myModal .modal-body' ).html( 'Are you sure want to delete the specialization ?' );
      $( '#myModal .modal-body' ).append( '<br /><br /><button class="btn btn-info btn-sm" id="continuemodal" data-dismiss="modal">Yes</button>&nbsp;&nbsp;<button aria-hidden="true" data-dismiss="modal" class="btn btn-danger btn-sm">Cancel</button>' );
      $( '#myModal' ).modal({ backdrop: 'static', keyboard: false }); 
      $( "#continuemodal" ).click(function() {
          var account_data    = 'specializationID=' + specializationID;
          $.ajax({
            type: "POST",
            url: adminurl + 'specialization/delete_specialization',
            data: account_data,
            success: function( data ) {
              toastr.success( 'Specialization deleted successfully.','Success' );
              setTimeout( function() {
                window.location.href = adminurl + 'specialization';
              }, 2000 );
            }
          });
      });    
      return false;
    }
</script>