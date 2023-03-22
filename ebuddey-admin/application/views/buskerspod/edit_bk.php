<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl       = $this->config->item( 'admin_url' );
$site_name      = $this->config->item( 'site_name' );
$dirUrl         = $this->config->item( 'dir_url' );

?>
<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Update Buskerspod</h3>
        <div class="box-tools pull-right">
          <a href="<?php echo $assetUrl; ?>buskerspod" class="btn btn-sm btn-primary">Back</a>
        </div>
      </div>
      <form novalidate="" id="edit_buskerspod_form" role="form" method="post" class="form-horizontal">
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="full_name" class="col-sm-4 control-label">Full Name<span class="text-danger">*</span></label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="full_name" id="full_name" value="<?= $buskerspodInfo->full_name; ?>" placeholder="User Name">
                </div>
              </div>
              <div class="form-group">
                <label for="contact_number" class="col-sm-4 control-label">Contact number</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control number" maxlength="12" value="<?= $buskerspodInfo->contact_no; ?>" name="contact_number" id="contact_number" placeholder="Contact number">
                </div>
              </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="other_name" class="col-sm-4 control-label">Other Name</label>
                    <div class="col-sm-8">
                      <input type="text" placeholder="Full name" name="other_name" value="<?= $buskerspodInfo->other_name; ?>" id="other_name" class="form-control">
                    </div>
                  </div>
                <div class="form-group">
                    <label for="email" class="col-sm-4 control-label">Contact Email</label>
                    <div class="col-sm-8">
                      <input type="email" placeholder="Contact Email" name="email" id="email" value="<?= $buskerspodInfo->email; ?>" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="identification" class="col-sm-4 control-label">Identification</label>
                    <div class="col-sm-8">
                        <input type="text" id="identification" name="identification" class="form-control" value="<?= $buskerspodInfo->full_name; ?>" placeholder="identification" />
                    </div>    
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="gender" class="col-sm-4 control-label">Gender</label>
                    <div class="col-sm-8">
                        <select class="form-control" name="gender" id="gender">
                            <option value="">Select</option>
                            <?php
                            if( $genderLists ) {
                                foreach ( $genderLists as $key => $value ) {
                                  echo '<option '.(($key==$buskerspodInfo->gender)? 'selected':'').' value="'. $key .'">'. $value .'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>    
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="skills" class="col-sm-4 control-label">Skill</label>
                    <div class="col-sm-8">
                        <select class="form-control" name="skills" id="skills">
                            <option value="">Select</option>
                            <?php 
                            if( $skillsLists ) { 
                                foreach ( $skillsLists as $key => $value ) {
                                  echo '<option '.(($value==$buskerspodInfo->skills)? 'selected':'').' value="'. $value .'">'. $value .'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>    
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="partner_id" class="col-sm-4 control-label">Partner <span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <select class="form-control" name="partner_id" id="partner_id">
                            <option value="">Select</option>
                            <?php 
                            if( $partnerList ) { 
                                foreach ( $partnerList as $key => $value ) {
                                  echo '<option '.(($value->partner_id==$buskerspodInfo->partner_id)? 'selected':'').' value="'. $value->partner_id .'">'. rawurldecode($value->partner_name) .'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>    
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="status" class="col-sm-4 control-label">Status <span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <select class="form-control" name="status" id="status">
                            <option value="">Select</option>
                            <option <?php if($buskerspodInfo->status==1){ echo 'selected'; } ?> value="1">Active</option>
                            <option <?php if($buskerspodInfo->status==2){ echo 'selected'; } ?> value="2">Non Active</option>
                        </select>
                    </div>    
                </div>
            </div>
          </div>
          <!-- /.box-body -->
          <div class="box-footer">
            <div class="col-md-offset-2 col-md-10">
              <input type="hidden" name="pod_id" value="<?= $buskerspodInfo->pod_id; ?>" id="pod_id">
              <button class="btn btn-info" id="editbuskerspodsubmit" onClick="return updateBuskerspodValidate();" type="button">Update</button>
              <a href="<?php echo $assetUrl; ?>buskerspod" class="btn btn-danger" type="reset">Back</a>
            </div>
          </div>
      </form>
    </div>
  </div>
</div>

    <div class="col-xs-12 col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"></h3>
              <div class="box-tools pull-right">
                <a href="javascript:;" onClick="return addEvent(<?= $buskerspodInfo->pod_id; ?>);" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-plus"></i></a>
                <a href="<?php echo $assetUrl; ?>buskerspod/calendar/<?= $pod_id; ?>" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-calendar"></i></a>
              </div>
            </div>
            <div class="box-body">
                <div class="box-body table-responsive no-padding">
                  <table id="example1" class="table table-bordered table-striped">
                      <thead>
                          <tr>
                              <th>Date</th>
                              <th>Start Time</th>
                              <th>End Time</th>
                              <th>Status</th>
                              <th>Action</th>
                          </tr>
                      </thead>
                      <tbody id="partner_list">
                          <?php
                              if( $eventList ) {
                                  foreach ( $eventList as $key => $value ) {
                                    if($value->status == 0){
                                      $status     = '<span class="label label-warning">Inactive</span>';
                                      $statusbtn  = '<a href="javascript:;" onClick="return eventStatus('.$value->id.', 1);" class="btn btn-warning btn-xs" data-toggle="tooltip" data-original-title="Click to Activate"><i class="fa fa-toggle-off" aria-hidden="true"></i></a>';
                                    }else if($value->status == 1){
                                      $status     = '<span class="label label-success">Active</span>';
                                      $statusbtn  = '<a href="javascript:;" onClick="return eventStatus('.$value->id.', 0);" class="btn btn-success btn-xs" data-toggle="tooltip" data-original-title="Click to Inactive"><i class="fa fa-toggle-on" aria-hidden="true"></i></a>';
                                    }
                                    ?>
                                      <tr>
                                          <td><?= date('d/m/Y', strtotime($value->start_date));?></td>
                                          <td><?= date('h:i a', strtotime($value->start_date));?></td>
                                          <td><?= date('h:i a', strtotime($value->end_date));?></td>
                                          <td><?= $status; ?></td>
                                          <td>
                                            <?= $statusbtn; ?>
                                            <a href="javascript:;" onClick="return editPartner(<?= $value->id; ?>);" class="btn btn-warning btn-xs">Edit</a>
                                            <a class="btn btn-danger btn-xs" href="javascript:;" onclick="return deleteEvent( '<?=$value->id;?>' );">Delete</a>
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
<script type="text/javascript">
//function update user
function updateBuskerspodValidate() {
    var $btn  = $( '#editbuskerspodsubmit' );
    $btn.button( 'Posting..' );
    var data    = $( "#edit_buskerspod_form" ).find( "select, textarea, input" ).serialize();
    $.ajax({
        type    : 'POST',
        url     : adminurl + 'buskerspod/editBuskerspodValidate',
        data    : data,
        success : function( msg ) {
            if( msg == 1 ) {
                toastr.success('Buskerspod update Successfully.');
                //window.location.href( 'buskerspod' );
            } else {
                toastr.error(msg,'Error');
            }
            $btn.button( 'reset' );
        }
    });
    return false;
}
function addEvent( pod_id ) {
    $.ajax( {
        type: "POST",
        url: adminurl + 'buskerspod/addEventForm',
        data    : 'pod_id=' + pod_id,
        success: function( msg ) {
          $( '#myModal .modal-title' ).html( 'Add Event' );
          $( '#myModal .modal-body' ).html( msg );
          $( '#myModal .modal-footer' ).html( '' );
          $( '#myModal' ).modal( 'show' );
        }
    });
    return false;
}
function deleteEvent( id ) {
  $( '#myModal .modal-title' ).html( 'Confirm' );
  $( '#myModal .modal-body' ).html( 'Are you sure want to delete the date ?' );
  $( '#myModal .modal-body' ).append( '<br /><br /><button class="btn btn-info btn-sm" id="continuemodal" data-dismiss="modal">Yes</button>&nbsp;&nbsp;<button aria-hidden="true" data-dismiss="modal" class="btn btn-danger btn-sm">Cancel</button>' );
  $( '#myModal' ).modal({ backdrop: 'static', keyboard: false }); 
  $( "#continuemodal" ).click(function() {
      var data = 'id='+id;
      $.ajax({
        type: "POST",
        url: adminurl + 'buskerspod/deleteEvent',
        data: data,
        success: function( data ) {
          toastr.success( 'Date deleted successfully.','Success' );
          setTimeout( function() {
            location.reload();
          }, 1000 );
        }
      });
  });    
  return false;
}
function eventStatus( id,status ) {
    $( '#myModal .modal-title' ).html( 'Confirm' );
    if(status == 1){
        $( '#myModal .modal-body' ).html( 'Are you sure want to Activate this date ?' );
    }else{
        $( '#myModal .modal-body' ).html( 'Are you sure want to Deactive this date ?' );
    }
    $( '#myModal .modal-body' ).append( '<br /><br /><button class="btn btn-info btn-sm" id="continuemodal" data-dismiss="modal">Yes</button>&nbsp;&nbsp;<button aria-hidden="true" data-dismiss="modal" class="btn btn-danger btn-sm">Cancel</button>' );
    $( '#myModal' ).modal({ backdrop: 'static', keyboard: false }); 
    $( "#continuemodal" ).click(function() {
      var data = { 'id':id,'status':status }
      $.ajax({
        type: "POST",
        url: adminurl + 'buskerspod/eventStatus',
        data: data,
        success: function( data ) {
          toastr.success( 'Date Status Updated Successfully.','Success' );
          setTimeout( function() {
            location.reload();
          }, 2000 );
        }
      });
  });    
  return false;
}
</script>