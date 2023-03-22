<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl   = $this->config->item( 'admin_dir_url' );
$dir_url    = $this->config->item( 'dir_url' );
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Buddey | Payment Failed</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo $assetUrl; ?>plugins/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo $assetUrl; ?>plugins/font-awesome/css/font-awesome.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo $assetUrl; ?>css/AdminLTE.min.css">
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition register-page">
<div class="register-box">
  <div class="register-logo">
    <a href="<?= base_url(); ?>">
      <img src="<?php echo $dir_url; ?>images/talent_login.png" style="width: 200px !important;">
    </a>
  </div>
  <section class="content">
      <div class="row">
          <div class="col-md-12">
              <div class="box box-primary">
                    <div class="box box-solid">
                      <div class="box-header with-border">
                        <h3 class="box-title">Tranasction Failed</h3>
                      </div>
                      <!-- /.box-header -->
                      <div class="box-body">
                        <?php
                        if( $this->session->flashdata( 'payError' ) ) { ?>
                          <p class="text-red">Your Tranasction was failed</p>
                        <?php
                        } ?>
                        <center><a href="<?= base_url(); ?>talent/buskerspod" class="btn btn-block btn-primary btn-sm">Try again</a></center>
                      </div>
                      <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
              </div>
          </div>
      </div>
  </section>
</div>
</body>
</html>