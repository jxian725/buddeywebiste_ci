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
                        <?php
                        $attributes = array('method' => 'get', 'id' => 'searchform');
                        echo form_open($assetUrl.'service', $attributes);
                        $service_search  = $this->input->get('service_search');
                        $order_by       = $this->input->get('order_by');
                        ?>
                        <!-- Search Name -->
                        <div class="form-group">
                          <div style="margin-bottom: 10px;padding-left: 0px;" class="search_input col-md-8">
                            <div class="input-group form-search">
                              <input style="width:70%;" type="text" name="service_search" id="service_search" class="search-query form-control" value="<?php echo $service_search; ?>" placeholder="Search by Requestor Name, Guider Name">
                              <select style="width:30%;" id="order_by" name="order_by" class="form-control">
                                <option value="">Order By</option>
                                <option <?php if($order_by == 1){ echo 'selected'; } ?> value="1">Requestor Name ASC</option>
                                <option <?php if($order_by == 2){ echo 'selected'; } ?> value="2">Guider Name DESC</option>
                              </select>
                              <span class="input-group-btn">
                                <button data-type="last" class="btn btn-black" type="submit">Search</button>
                              </span>
                            </div>
                          </div>
                        </div>
                        <?php echo form_close(); ?>
                        <div class="clearfix"></div>
                        <div class="form-group">
                          <label>
                            Show
                          </label>
                          <label>
                            <select name="supplier_length" id="supplier_length" aria-controls="supplier_list" class="form-control input-sm">
                              <option <?php if($this->session->userdata('view_supplier') == 10){ echo 'selected'; } ?> value="10">10</option>
                              <option <?php if($this->session->userdata('view_supplier') == 25){ echo 'selected'; } ?> value="25">25</option>
                              <option <?php if($this->session->userdata('view_supplier') == 50){ echo 'selected'; } ?> value="50">50</option>
                              <option <?php if($this->session->userdata('view_supplier') == 100){ echo 'selected'; } ?> value="100">100</option>
                            </select>
                          </label>
                          <label>
                            entries
                          </label>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="col-md-12">
                        <div class="box-body table-responsive no-padding">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Service Id</th>
                                    <th>Requestor Name</th>
                                    <th>Guider Name</th>
                                    <th>Service Date</th>
                                    <th>Pickup Location</th>
                                    <th>Pickup Time</th>
                                    <th>Total Hours</th>
                                    <th>Amount Charged</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="requestor_lists">
                                <?php
                                if( $serviceLists ) {
                                    foreach ( $serviceLists as $key => $value ) {
                                        if($value->status == 0){
                                            $status     = '<span class="label label-primary">Not Start</span>';
                                        }else if($value->status == 1){
                                            $status     = '<span class="label label-success">Completed</span>';
                                        }else if($value->status == 2){
                                            $status     = '<span class="label label-warning">Ongoing</span>';
                                        }else if($value->status == 3){
                                            $status     = '<span class="label label-danger">Cancelled</span>';
                                        }
                                        ?>
                                        <tr>
                                            <td>S00<?=$value->service_id;?></td>
                                            <td><?=$value->requestorName;?></td>
                                            <td><?=$value->guiderName;?></td>
                                            <td><?=$value->service_date;?></td>
                                            <td><?=$value->pickup_location;?></td>
                                            <td><?=$value->pickup_time;?></td>
                                            <td><?=$value->total_hours;?></td>
                                            <td><?=$value->guider_charged;?></td>
                                            <td><?=$status;?></td>
                                            <td>
                                               <a class="btn btn-primary btn-sm" href="<?=$assetUrl; ?>service/view/<?=$value->service_id; ?>"><i class="fa fa-search"></i></a> 
                                            </td>
                                        </tr>
                                        <?php
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
