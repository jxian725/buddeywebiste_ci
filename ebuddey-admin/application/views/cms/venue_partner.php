<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl       = $this->config->item( 'admin_url' );
$site_name      = $this->config->item( 'site_name' );
global $permission_arr;
?>
<style type="text/css">
#imgspecialization { display:none;}
</style>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box box-primary">
            <div class="box-header">
                <div class="box-tools pull-right">
                    <a href="<?php echo $assetUrl; ?>cms/pages" class="btn btn-sm btn-primary">Back</a>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                      <form novalidate="" id="venue_partner_img_form" role="form" method="post" class="form-horizontal" enctype="multipart/form-data">
                        <div class="box-body">
                          <div class="form-group">
                            <label class="col-md-2 control-label" for="title">Title</label>
                            <div class="col-md-8">
                              <input type="text" required="" placeholder="Enter Title" id="title" class="form-control" name="title">
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-md-2 control-label" for="venue_partner_img">Image <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                              <input class="form-control" type="file" accept="image/*" name="venue_partner_img" id="venue_partner_img">
                            </div>
                          </div>
                          <div class="col-md-offset-2 col-md-8 text-right">
                            <input type="hidden" name="cvp_id" />
                            <input type="hidden" value="1" name="is_submit" />
                            <button class="btn btn-sm btn-success" id="addVenuePartnerSubmit" onClick="return validateVenuePartner();" type="button">Add</button>
                          </div>
                        </div>
                      </form>
                      <div class="box-body table-responsive no-padding">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Image</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="venuePartnerImgLists">
                                <?php
                                    if( $venuePartnerImgLists ) {
                                        foreach ( $venuePartnerImgLists as $key => $value ) {
                                            ?>
                                            <tr>
                                                <td><?=rawurldecode( $value->title );?></td>
                                                <td>
                                                  <?php
                                                  if($value->venue_partner_img){
                                                    $venue_partner_img = $this->config->item( 'dir_url' ).'uploads/cms_venue_partner/'.$value->venue_partner_img;
                                                  }else{
                                                    $venue_partner_img = $this->config->item( 'dir_url' ).'uploads/no_image.png';
                                                  }
                                                  ?>
                                                  <img style="width: 80px;" class="media-object" src="<?= $venue_partner_img; ?>" />
                                                </td>
                                                <td>
                                                  <a href="javascript:;" onClick="return editVenuePartner(<?= $value->cvp_id; ?>);" class="btn btn-warning btn-xs">Edit</a>
                                                  <a class="btn btn-danger btn-xs" href="javascript:;" onclick="return deleteVenuePartner( '<?=$value->cvp_id;?>' );">Delete</a> 
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
    function validateVenuePartner() {
      var partner_img = document.getElementById('venue_partner_img');
      if(partner_img.files.length > 0){
        $( '#venue_partner_img_form' ).submit();
      }else{
        toastr.error('Please select the venue partner image','Error');
      }
    }
    function editVenuePartner( cvp_id ) {
        var data = 'cvp_id=' + cvp_id;
        $.ajax( {
            type: "POST",
            url: adminurl + 'cms/pages/editVenuePartnerForm',
            data    : data,
            success: function( msg ) {
              $( '#myModal .modal-title' ).html( 'Edit Venue Partner Image' );
              $( '#myModal .modal-body' ).html( msg );
              $( '#myModal .modal-footer' ).html( '' );
              $( '#myModal' ).modal( 'show' );
            }
        });
        return false;
    }
    function deleteVenuePartner( cvp_id ) {
      $( '#myModal .modal-title' ).html( 'Confirm' );
      $( '#myModal .modal-body' ).html( 'Are you sure want to delete the Venue Partner ?' );
      $( '#myModal .modal-body' ).append( '<br /><br /><button class="btn btn-info btn-sm" id="continuemodal" data-dismiss="modal">Yes</button>&nbsp;&nbsp;<button aria-hidden="true" data-dismiss="modal" class="btn btn-danger btn-sm">Cancel</button>' );
      $( '#myModal' ).modal({ backdrop: 'static', keyboard: false }); 
      $( "#continuemodal" ).click(function() {
          var account_data = 'cvp_id=' + cvp_id;
          $.ajax({
            type: "POST",
            url: adminurl + 'cms/pages/deleteVenuePartner',
            data: account_data,
            success: function( data ) {
              toastr.success( 'Venue Partner image deleted successfully.','Success' );
              setTimeout( function() {
                location.reload();
              }, 2000 );
            }
          });
      });    
      return false;
    }
</script>