<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Payment Form</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="<?=$this->config->item( 'dir_url' );?>plugins/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?=$this->config->item( 'dir_url' );?>css/AdminLTE.min.css">
</head>
<body class="hold-transition skin-blue sidebar-mini">
	<div class="wrapper">
		<div class="clearfix"></div>
		<section class="content">
			<div class="row">
	  			<div class="col-md-12">
	    			<div class="box box-primary">
	      				<div class="col-sm-12">
                  <div class="box box-solid">
                    <div class="box-header with-border">
                      <i class="fa fa-text-width"></i>
                      <h3 class="box-title">Alert</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <p class="lead"><?= $msg; ?></p>
                    </div>
                    <!-- /.box-body -->
                  </div>
                  <!-- /.box -->
                </div>
	    			</div>
	  			</div>
			</div>
		</section>
	<div class="control-sidebar-bg"></div>
	</div>
</body>
</html>