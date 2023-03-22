<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl   = $this->config->item( 'admin_url' );
$dirUrl     = $this->config->item( 'dir_url' );
$upload_path_url = $this->config->item( 'upload_path_url' );
global $permission_arr;
?>
<link rel="stylesheet" href="<?= $dirUrl; ?>plugins/lightbox/css/lightbox.min.css">
<link rel="stylesheet" href="<?= $dirUrl; ?>plugins/iCheck/square/blue.css">
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                      <div id="message_partner"></div>
                      <form novalidate="" id="partner_form" role="form" method="post" class="form-horizontal" enctype="multipart/form-data">
                        <div class="box-body">
                          <div class="form-group">
                            <label class="col-md-2 control-label" for="partner_name">Partner Name <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                              <input type="text" required="" placeholder="Partner Name" id="partner_name" class="form-control" name="partner_name">
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-md-2 control-label" for="city_id">City <span class="text-danger">*</span></label>
                            <div class="col-md-4">
                                <select class="form-control" id="city_id" name="city_id">
                                    <option value="">Select</option>
                                    <?php
                                    if( $stateList ) {
                                        foreach ( $stateList as $key => $value2 ) {
                                            ?><option value="<?=$value2->id;?>"><?=$value2->name;?></option><?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                              <input type="text" placeholder="Fees (RM)" id="fees" class="form-control number" name="fees">
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-md-2 control-label" for="fees">Photo</label>
                            <div class="col-md-8">
                              <input type="file" id="photo" name="photo" class="form-control">
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-md-2 control-label" for="address">Address</label>
                            <div class="col-md-8">
                              <textarea type="text" rows="3" placeholder="Address" id="address" class="form-control" name="address"></textarea>
                            </div>
                          </div>
                          <div class="col-md-offset-2 col-md-10" style="padding-left: 5px;">
                            <div class="checkbox icheck">
                              <label><input type="checkbox" name="dbkl_lic_enable" id="dbkl_lic_enable" value="1">&nbsp;&nbsp;DBKL license Required</label>
                            </div>
                          </div>
                          <div class="col-md-offset-2 col-md-8 text-right" style="padding-right: 5px;">
                            <input type="hidden" name="partner_id" />
                            <button class="btn btn-sm btn-success" id="addpartnersubmit" onClick="return validatePartner();" type="button">Add</button>
                          </div>
                        </div>
                      </form>
                      <div class="box-body table-responsive no-padding">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Partner Name</th>
                                    <th>City Name</th>
                                    <th>Fees (RM)</th>
                                    <th>Photo</th>
                                    <th>Address</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="partner_list">
                                <?php
                                    if( $partnerList ) {
                                        foreach ( $partnerList as $key => $value ) {
                                            ?>
                                            <tr>
                                                <td><?=rawurldecode( $value->partner_name );?></td>
                                                <td><?=$value->cityName;?></td>
                                                <td><?= (($value->fees)? number_format($value->fees, 2) : ''); ?></td>
                                                <td>
                                                  <?php 
                                                  if($value->photo){ 
                                                  $photo = $upload_path_url.'partner/'.$value->photo;
                                                  ?>
                                                  <a class="example-image-link" href="<?= $photo; ?>" data-lightbox="example-set">
                                                    <img class="img-thumbnail" src="<?= $photo; ?>" style="height: auto;width: 60px;" data-src="#" />
                                                  </a>
                                                  <?php 
                                                  }
                                                  ?>
                                                </td>
                                                <td><?=$value->address;?></td>
                                                <td>
                                                  <a href="javascript:;" onClick="return editPartner(<?= $value->partner_id; ?>);" class="btn btn-warning btn-xs">Edit</a>&nbsp;
                                                  <a class="btn btn-danger btn-xs" href="javascript:;" onclick="return deletePartner( '<?=$value->partner_id;?>' );">Delete</a> 
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
<script src="<?= $dirUrl; ?>plugins/lightbox/js/lightbox-plus-jquery.min.js"></script>
<script src="<?= $dirUrl; ?>plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
<script type="text/javascript">
    //partner Validation
    function validatePartner() {
        var $btn  = $( '#addpartnersubmit' );
        $btn.button( 'Posting..' );
        var partner_name = $('#partner_name').val();
        var city_id  = $('#city_id').val();
        if(partner_name == ''){
          toastr.error('The Partner Name field is required.','Error');
        }else if(city_id == ''){
            toastr.error('The City field is required.','Error');
        }else{
          $( '#partner_form' ).submit();
        }
    }
    function editPartner( partner_id ) {
        $.ajax( {
            type: "POST",
            url: adminurl + 'partners/editPartnerForm',
            data    : 'partner_id=' + partner_id,
            success: function( msg ) {
              $( '#myModal .modal-title' ).html( 'Edit Partner' );
              $( '#myModal .modal-body' ).html( msg );
              $( '#myModal .modal-footer' ).html( '' );
              $( '#myModal' ).modal( 'show' );
            }
        });
        return false;
    }
    function deletePartner( partner_id ) {
      $( '#myModal .modal-title' ).html( 'Confirm' );
      $( '#myModal .modal-body' ).html( 'Are you sure want to delete the partner ?' );
      $( '#myModal .modal-body' ).append( '<br /><br /><button class="btn btn-info btn-sm" id="continuemodal" data-dismiss="modal">Yes</button>&nbsp;&nbsp;<button aria-hidden="true" data-dismiss="modal" class="btn btn-danger btn-sm">Cancel</button>' );
      $( '#myModal' ).modal({ backdrop: 'static', keyboard: false }); 
      $( "#continuemodal" ).click(function() {
          var data = 'partner_id=' + partner_id;
          $.ajax({
            type: "POST",
            url: adminurl + 'partners/deletePartner',
            data: data,
            success: function( data ) {
              toastr.success( 'Partner deleted successfully.','Success' );
              setTimeout( function() {
                window.location.href = adminurl + 'partners';
              }, 2000 );
            }
          });
      });    
      return false;
    }
</script>