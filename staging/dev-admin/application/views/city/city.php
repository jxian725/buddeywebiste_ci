<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl       = $this->config->item( 'admin_url' );
$site_name      = $this->config->item( 'site_name' );
?>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                  <div class="col-md-12">
                    <div class="pull-right margin">
                      <div class="btn-group">
                        <button type="button" class="btn btn-info">Malaysia</button>
                        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                          <span class="caret"></span>
                          <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                          <li><a href="#">Malaysia</a></li>
                        </ul>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="box-body table-responsive no-padding">
                      <table id="example1" class="table table-bordered">
                        <thead>
                          <tr>
                            <th>ID</th>
                            <th>City</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody id="city_lists">
                          <?php
                          $i = 1;
                          if($state_list) {
                            foreach ( $state_list as $key => $value ) {
                              if($value->status == 0){
                                  $status     = '<span class="label label-warning">Inactive</span>';
                                  $statusbtn  = '<a href="javascript:;" onClick="return cityStatus('.$value->id.', 1);" class="btn btn-warning btn-xs" data-toggle="tooltip" data-original-title="Click to Activate"><i class="fa fa-toggle-off" aria-hidden="true"></i></a>';
                              }else if($value->status == 1){
                                  $status     = '<span class="label label-success">Active</span>';
                                  $statusbtn  = '<a href="javascript:;" onClick="return cityStatus('.$value->id.', 0);" class="btn btn-success btn-xs" data-toggle="tooltip" data-original-title="Click to Inactive"><i class="fa fa-toggle-on" aria-hidden="true"></i></a>';
                              }
                              ?>
                              <tr>
                                <td><?=$i;?></td>
                                <td><?=$value->name;?></td>
                                <td><?=$status;?></td>
                                <td><?=$statusbtn;?></td>
                              </tr>
                              <?php
                              $i++;
                            }
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
  function cityStatus( city_id,status ) {
      $( '#myModal .modal-title' ).html( 'Confirm' );
      if(status == 1){
          $( '#myModal .modal-body' ).html( 'Are you sure want to Activate this City ?' );
      }else{
          $( '#myModal .modal-body' ).html( 'Are you sure want to Deactive this City ?' );
      }
      $( '#myModal .modal-body' ).append( '<br /><br /><button class="btn btn-info btn-sm" id="continuemodal" data-dismiss="modal">Yes</button>&nbsp;&nbsp;<button aria-hidden="true" data-dismiss="modal" class="btn btn-danger btn-sm">Cancel</button>' );
      $( '#myModal' ).modal({ backdrop: 'static', keyboard: false }); 
      $( "#continuemodal" ).click(function() {
        var data = { 'city_id':city_id,'status':status }
        $.ajax({
          type: "POST",
          url: adminurl + 'cities/cityStatus',
          data: data,
          success: function( data ) {
            toastr.success( 'City Status Updated Successfully.','Success' );
            setTimeout( function() {
              location.reload();
            }, 2000 );
          }
        });
    });    
    return false;
  }
</script>