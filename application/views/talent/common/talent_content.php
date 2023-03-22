<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view( 'talent/common/talent_header', $header ); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- <section class="content-header">
      <h1><?php echo $header[ 'title' ]; ?></h1>
      <ol class="breadcrumb">
        <?php echo $breadcrumb; ?>
      </ol>
    </section> -->
    <div class="clearfix"></div>
    <!-- END BREADCRUMBS -->
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <?php 
                if( $this->session->flashdata( 'errorMSG' ) ) { ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo $this->session->flashdata( 'errorMSG' ); ?>
                    </div>    
                <?php } ?>
                <?php 
                if( $this->session->flashdata( 'successMSG' ) ) { ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo $this->session->flashdata( 'successMSG' ); ?>
                    </div>
                <?php } ?>
                <?php 
                if( $this->session->flashdata( 'errorMSG2' ) ) { ?>
                    <script type="text/javascript">
                        toastr.error("<?php echo $this->session->flashdata( 'errorMSG2' ); ?>",'Error');
                    </script>
                <?php 
                }
                if( $this->session->flashdata( 'successMSG2' ) ) { ?>
                    <script type="text/javascript">
                        toastr.success("<?php echo $this->session->flashdata( 'successMSG2' ); ?>",'Success');
                    </script>
                <?php 
                } ?>
                <?php
                if( $this->session->flashdata( 'paySuccess' ) ) { ?>
                    <div class="alert alert-success alert-dismissible">
                        <p class="lead">Thank you! Your payment was successful.
                            <span class="text-navy">Transaction ID : <?php echo $this->session->flashdata( 'paySuccess' ); ?></span>
                        </p>
                    </div>
                <?php
                } ?>
                <div class="alert_msg" id="alert_msg"></div>
            </div>
        </div>
        <?php echo $content; ?>
    </section>
    <!-- /.content -->
</div>
    <!-- END CONTENT -->
<?php $this->load->view( 'talent/common/talent_footer' ); ?>