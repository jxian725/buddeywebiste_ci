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
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                      <div id="message_specialization"></div>
                      <?php if( in_array( 'category/add', $permission_arr ) ) { ?>
                      <form novalidate="" id="specialization_form" role="form" method="post" class="form-horizontal" enctype="multipart/form-data">
                        <div class="box-body">
                          <div class="form-group">
                            <label class="col-md-2 control-label" for="specialization">Category <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                              <input type="text" required="" placeholder="Enter Category" id="specialization" class="form-control" name="specialization">
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-md-2 control-label" for="category_img">Image</label>
                            <div class="col-md-8">
                              <input class="form-control" type="file" name="category_img" id="category_img">
                            </div>
                          </div>
                          <div class="col-md-offset-2 col-md-8 text-right">
                            <input type="hidden" name="specialization_id" />
                            <button class="btn btn-sm btn-success" id="addspecializationsubmit" onClick="return validatespecialization();" type="button">Add</button>
                          </div>
                        </div>
                      </form>
                      <?php } ?>
                      <div class="box-body table-responsive no-padding">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Image</th>
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
                                                  <?php
                                                  if($value->category_img){
                                                    $category_img = $this->config->item( 'dir_url' ).'uploads/category/'.$value->category_img;
                                                  }else{
                                                    $category_img = $this->config->item( 'dir_url' ).'uploads/no_image.png';
                                                  }
                                                  ?>
                                                  <img style="width: 80px;" class="media-object" src="<?= $category_img; ?>" />
                                                </td>
                                                <td>
                                                  <?php if( in_array( 'category/add', $permission_arr ) ) { ?>
                                                  <a href="javascript:;" onClick="return editCategory(<?= $value->specialization_id; ?>);" class="btn btn-warning btn-xs">Edit</a>
                                                  <?php } ?>
                                                  <?php if( in_array( 'category/delete', $permission_arr ) ) { ?>
                                                  <a class="btn btn-danger btn-xs" href="javascript:;" onclick="return delete_specialization( '<?=$value->specialization_id;?>' );">Delete</a> 
                                                  <?php } ?>
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
        var specialization = $('#specialization').val();
        if(specialization == ''){
          toastr.error('The Category field is required.','Error');
        }else{
          $( '#specialization_form' ).submit();
        }
    }
    function editCategory( specialization_id ) {
        var data = 'specialization_id=' + specialization_id;
        $.ajax( {
            type: "POST",
            url: adminurl + 'category/editCategoryForm',
            data    : data,
            success: function( msg ) {
              $( '#myModal .modal-title' ).html( 'Edit Category' );
              $( '#myModal .modal-body' ).html( msg );
              $( '#myModal .modal-footer' ).html( '' );
              $( '#myModal' ).modal( 'show' );
            }
        });
        return false;
    }
    function delete_specialization( specializationID ) {
      $( '#myModal .modal-title' ).html( 'Confirm' );
      $( '#myModal .modal-body' ).html( 'Are you sure want to delete the category ?' );
      $( '#myModal .modal-body' ).append( '<br /><br /><button class="btn btn-info btn-sm" id="continuemodal" data-dismiss="modal">Yes</button>&nbsp;&nbsp;<button aria-hidden="true" data-dismiss="modal" class="btn btn-danger btn-sm">Cancel</button>' );
      $( '#myModal' ).modal({ backdrop: 'static', keyboard: false }); 
      $( "#continuemodal" ).click(function() {
          var account_data    = 'specializationID=' + specializationID;
          $.ajax({
            type: "POST",
            url: adminurl + 'category/delete_specialization',
            data: account_data,
            success: function( data ) {
              toastr.success( 'Category deleted successfully.','Success' );
              setTimeout( function() {
                window.location.href = adminurl + 'category';
              }, 2000 );
            }
          });
      });    
      return false;
    }
</script>