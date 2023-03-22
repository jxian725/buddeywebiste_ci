<?php
$base_url   = $this->config->item( 'base_url' );
$admin_url  = $this->config->item( 'admin_url' );
$dir_url    = $this->config->item( 'dir_url' );
$this->load->library('encrypt');
error_reporting(0);
$key    = $this->config->item( 'encryption_key' );
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?=$this->config->item('site_name');?> | <?= HOST_NAME; ?> Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?=$this->config->item( 'dir_url' );?>plugins/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?=$this->config->item( 'dir_url' );?>plugins/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?=$this->config->item( 'dir_url' );?>plugins/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?=$this->config->item( 'dir_url' );?>css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?=$this->config->item( 'dir_url' );?>plugins/iCheck/square/blue.css">
  <link rel="stylesheet" href="<?=$this->config->item( 'dir_url' );?>css/custom.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page-2">
    <div class="login-box">
        <div class="login-logo">
            <a href="<?=$this->config->item( 'dir_url' );?>"><b><?= HOST_NAME; ?> Login</b></a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">Sign in to start your session</p>
            <?php echo form_open( '#', 'class="form-signin" autocomplete="off"' ); ?>
                <?php 
                if( $this->session->flashdata( 'err_msg' ) ) { ?>
                    <div class="text-danger">
                      <?php echo $this->session->flashdata( 'err_msg' ); ?>
                    </div>  
                <?php 
                } ?>
                <div class="form-group has-feedback">
                    <?php
                    $attr = array(
                                'class'         => 'form-control number',
                                'autocomplete'  => 'off',
                                'id'            => 'phone_number',
                                'name'          => 'phone_number',
                                'placeholder'   => 'Enter mobile no. (Example 0121234567)',
                                'value'         => set_value('phone_number'),
                            );
                    echo form_input( $attr );
                    ?>
                    <span class="glyphicon glyphicon-phone form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <?php
                    $attr = array(
                                'class'         => 'form-control',
                                'id'            => 'password',
                                'autocomplete'  => 'off',
                                'name'          => 'password',
                                'placeholder'   => 'Password',
                                'type'          => 'password'
                            );
                    echo form_input( $attr );
                    ?>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="row">
                  <div class="col-xs-8">
                    <div class="checkbox icheck">
                      <label><input type="checkbox"> Remember Me</label>
                    </div>
                  </div>
                    <!-- /.col -->
                  <div class="col-xs-4">
                      <?php
                      $attr = array(
                                    'class'   => 'btn btn-primary btn-block btn-flat',
                                    'id'      => 'login_submit',
                                    'name'    => 'login_submit',
                                    'content' => 'Sign In',
                                    'onClick' => 'loginValidate();'
                                );
                      echo form_button( $attr );
                      ?>
                  </div>
        <!-- /.col -->
      </div>
    <?php echo form_close(); ?>

    <a href="#">I forgot my password</a>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
<script> var adminurl = '<?php echo $this->config->item('admin_url'); ?>'; </script>
<!-- jQuery 3 -->
<script src="<?php echo $this->config->item('dir_url'); ?>plugins/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo $this->config->item('dir_url'); ?>plugins/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="<?php echo $this->config->item('dir_url'); ?>plugins/iCheck/icheck.min.js"></script>
<script src="<?php echo $this->config->item('dir_url'); ?>js/host_custom.js" type="text/javascript"></script>

<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>

