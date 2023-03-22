<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                      <div id="message_license"></div>
                      <form novalidate="" id="add_license_form" role="form" method="post" class="form-horizontal" enctype="multipart/form-data">
                        <div class="box-body">
                          <div class="form-group">
                            <label class="col-md-2 control-label" for="license_name">Verification Name <span class="text-danger">*</span></label>
                            <div class="col-md-6">
                              <input type="text" required="" placeholder="Enter license" id="license_name" class="form-control" name="license_name">
                            </div>
                          </div>
                          <div class="col-md-8 text-right">
                            <input type="hidden" name="license_id" />
                            <button class="btn btn-sm btn-success" id="addlicensesubmit" onClick="return validateLicense();" type="button">Add</button>
                          </div>
                        </div>
                      </form>
                      <div class="box-body table-responsive no-padding">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Verification Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="licenseLists">
                                <?php
                                    if( $licenseLists ) {
                                        foreach ( $licenseLists as $key => $value ) {
                                            ?>
                                            <tr>
                                                <td><?=$value->license_name;?></td>
                                                <td>
                                                  <a href="javascript:;" onClick="return editLicense(<?= $value->license_id; ?>);" class="btn btn-warning btn-xs">Edit</a>
                                                  <a class="btn btn-danger btn-xs" href="javascript:;" onclick="return deleteLicense( '<?=$value->license_id;?>' );">Delete</a> 
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <tr><td colspan="3">No data to display.</td></tr>
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
    function validateLicense() {
        var $btn  = $( '#addlicensesubmit' );
        $btn.button( 'Posting..' );
        var license_name = $('#license_name').val();
        if(license_name == ''){
          toastr.error('The verification Name field is required.','Error');
        }else{
          $( '#add_license_form' ).submit();
        }
    }
    function editLicense( license_id ) {
        var data = 'license_id=' + license_id;
        $.ajax( {
            type: "POST",
            url: adminurl + 'license/editLicenseForm',
            data : data,
            success: function( msg ) {
              $( '#myModal .modal-title' ).html( 'Edit verification' );
              $( '#myModal .modal-body' ).html( msg );
              $( '#myModal .modal-footer' ).html( '' );
              $( '#myModal' ).modal( 'show' );
            }
        });
        return false;
    }
    function deleteLicense( license_id ) {
      $( '#myModal .modal-title' ).html( 'Confirm' );
      $( '#myModal .modal-body' ).html( 'Are you sure want to delete the license ?' );
      $( '#myModal .modal-body' ).append( '<br /><br /><button class="btn btn-info btn-sm" id="continuemodal" data-dismiss="modal">Yes</button>&nbsp;&nbsp;<button aria-hidden="true" data-dismiss="modal" class="btn btn-danger btn-sm">Cancel</button>' );
      $( '#myModal' ).modal({ backdrop: 'static', keyboard: false }); 
      $( "#continuemodal" ).click(function() {
          var data = 'license_id=' + license_id;
          $.ajax({
            type: "POST",
            url: adminurl + 'license/delete_license',
            data: data,
            success: function( data ) {
              toastr.success( 'Verification deleted successfully.','Success' );
              setTimeout( function() {
                window.location.href = adminurl + 'license';
              }, 1000 );
            }
          });
      });    
      return false;
    }
</script>