<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl   = $this->config->item( 'admin_url' );
$site_name  = $this->config->item( 'site_name' );
$dirUrl     = $this->config->item( 'dir_url' );

?>
<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Requestor Info - S00<?=$serviceInfo->service_id; ?></h3>
        <div class="box-tools pull-right">
          <a href="<?php echo $assetUrl; ?>service" class="btn btn-sm btn-primary">Back</a>
        </div>
      </div>
      <div class="box-body">
        <div class="col-md-12">
          <div class="col-md-4">
            <div class="form-group">
              <label class="control-label">Requestor Name</label>
              <div><?=$serviceInfo->requestorName; ?></div>
            </div>
            <div class="form-group">
              <label class="control-label">Trip Type</label>
              <div>Round</div>
            </div>
            <div class="form-group">
              <label class="control-label">Pickup Location</label>
              <div><?=$serviceInfo->pickup_location; ?></div>
            </div>
            <div class="form-group">
              <label class="control-label">Visting Location</label>
              <div><?=$serviceInfo->visitor_location; ?></div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label class="control-label">Guider Name</label>
              <div><?=$serviceInfo->guiderName; ?></div>
            </div>
            <div class="form-group">
              <label class="control-label">How Many Person</label>
              <div>3</div>
            </div>
            <div class="form-group">
              <label class="control-label">Service Date</label>
              <div><?=$serviceInfo->service_date; ?></div>
            </div>
            <div class="form-group">
              <label class="control-label">Total Hours</label>
              <div><?=$serviceInfo->total_hours; ?></div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label class="control-label">Request Date/Time</label>
              <div>2017-12-10/ 4.00 am</div>
            </div>
            <div class="form-group">
              <label class="control-label">Pickup Time</label>
              <div><?=$serviceInfo->pickup_time; ?></div>
            </div>
            <div class="form-group">
              <label class="control-label">Amount Charged</label>
              <div><?=$serviceInfo->guider_charged; ?></div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <label class="control-label">Customer Feedback</label>
              <div><?=$serviceInfo->feedback; ?></div>
            </div>
          </div>
        </div>
        <div class="clearfix"></div>
        <div class="box-body table-responsive no-padding">
          <div class="box-header with-border">
            <h3 class="box-title">Service Timeline</h3>
          </div>
          <div class="col-md-12">
            <!-- The time line -->
            <ul class="timeline">
              <!-- timeline time label -->
              <li>
                <i class="fa fa-cubes bg-blue"></i>
                <div class="timeline-item">
                  <span class="time"><i class="fa fa-clock-o"></i> 4:04 AM</span>
                  <span class="time"><i class="fa fa-calendar"></i> 10 Dec 17</span>
                  <h3 class="timeline-header">Visitor Send Service Request.</h3>
                </div>
              </li>
              <li>
                <i class="fa fa-truck bg-maroon"></i>
                <div class="timeline-item">
                  <span class="time"><i class="fa fa-clock-o"></i> 4:15 AM</span>
                  <span class="time"><i class="fa fa-calendar"></i> 10 Dec 17</span>
                  <h3 class="timeline-header">Guider Request Accepted</h3>
                </div>
              </li>
              <li>
                <i class="fa fa-truck bg-green"></i>
                <div class="timeline-item">
                  <span class="time"><i class="fa fa-clock-o"></i> 6:00 AM</span>
                  <span class="time"><i class="fa fa-calendar"></i> 10 Dec 17</span>
                  <h3 class="timeline-header">Guider Pickup the Visitor.</h3>
                </div>
              </li>
              <li>
                <i class="fa fa-truck bg-green"></i>
                <div class="timeline-item">
                  <span class="time"><i class="fa fa-clock-o"></i> 7:00 AM</span>
                  <span class="time"><i class="fa fa-calendar"></i> 10 Dec 17</span>
                  <h3 class="timeline-header">Guider Reach Visting Location.</h3>
                </div>
              </li>
              <li>
                <i class="fa fa-truck bg-green"></i>
                <div class="timeline-item">
                  <span class="time"><i class="fa fa-clock-o"></i> 11:00 AM</span>
                  <span class="time"><i class="fa fa-calendar"></i> 10 Dec 17</span>
                  <h3 class="timeline-header">Trip Completed.</h3>
                </div>
              </li>
            </ul>
          </div>
        </div>

    </div>
  </div>
</div>