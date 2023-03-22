<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl       = $this->config->item( 'admin_url' );
$site_name      = $this->config->item( 'site_name' );
global $permission_arr;
?>
<div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">Schedule of the week</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <table id="example1" class="table table-bordered table-striped">
          <thead>
              <tr>
                  <th>Venue</th>
                  <th>City</th>
                  <th>Date</th>
                  <th>Start</th>
                  <th>End</th>
                  <th>Status</th>
              </tr>
          </thead>
          <tbody id="event_list">
            <?php
              if( $eventLists ) {
                  foreach ( $eventLists as $key => $value ) {
                    if($value->host_id){ 
                        $guiderInfo = $this->Guidermodel->guiderInfo($value->host_id);
                        if($guiderInfo){
                            $hostName = $guiderInfo->first_name;
                        }else{ $hostName = 'Available'; }
                    }else{ 
                        $hostName = 'Available';
                    }
                    $cityInfo = $this->Commonmodel->cityInfo( $value->city_id );
                    if($cityInfo){
                        $cityName = $cityInfo->name;
                    }else{
                        $cityName = '';
                    }
                    ?>
                    <tr>
                        <td><?=rawurldecode( $value->partner_name );?></td>
                        <td><?=$cityName;?></td>
                        <td><?=date('Y-m-d', strtotime($value->start));?></td>
                        <td><?=date('H:i:s', strtotime($value->start));?></td>
                        <td><?=date('H:i:s', strtotime($value->end));?></td>
                        <td style="color:#fff; background: <?=$value->color;?>;"><?=$hostName;?></td>
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