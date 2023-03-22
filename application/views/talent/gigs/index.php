<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 
$assetUrl   = $this->config->item( 'admin_url' );
$adminUrl   = $this->config->item( 'admin_dir_url' );
$dirUrl     = $this->config->item( 'dir_url' );
?>
<style type="text/css">
.box-primary{
    color: #fff;
}
</style>
<div class="count_box row">
    <div class="col-md-6">
        <div class="col-xl-12 col-sm-14" style="padding-left: 0px;">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="col-sm-3">Category</label>
                                <select name="category" id="" class="form-group">
                                    <option>Select Category</option>
                                </select>
                            </div> 
                        </div>
                    </div>         
                </div>
            </div>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="col-sm-4">Filter by</label>
                                <label class="col-sm-3">All</label>
                                <label class="col-sm-3">Latest</label>
                                <label class="col-sm-2">Oldest</label>
                            </div> 
                        </div>
                    </div>         
                </div>
            </div> 
            <div class="box box-primary">
                <div class="box-header with-border">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                               
                            </div> 
                        </div>
                    </div>         
                </div>
            </div>        
        </div>            
    </div>
    <div class="col-md-6">
        <div class="col-xl-12 col-sm-14" style="padding-left: 0px;">
            <div class="box box-primary">
                <div class="box-header with-border">
                   Gigs.....
                </div>
            </div>
        </div>  
    </div>                
</div>
