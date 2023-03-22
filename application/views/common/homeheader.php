<?php
$dirUrl = $this->config->item( 'dir_url' );
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="keywords" content="<?= $header['metakeyword']; ?>">
    <meta name="description" content="<?= $header['metadescription']; ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $header['title']; ?></title>
    <link rel="icon" type="image/jpeg" href="<?php echo $dirUrl; ?>assets/img/favicon.png" sizes="16x16">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo $dirUrl; ?>assets/css/bootstrap.min.css">
    <!-- animate CSS -->
    <link rel="stylesheet" href="<?php echo $dirUrl; ?>assets/css/animate.css">
    <!-- owl carousel CSS -->
    <link rel="stylesheet" href="<?php echo $dirUrl; ?>assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="<?php echo $dirUrl; ?>assets/css/owl.theme.default.min.css">
    <!-- themify CSS -->
    <link rel="stylesheet" href="<?php echo $dirUrl; ?>assets/css/themify-icons.css">
    <!-- flaticon CSS -->
    <link rel="stylesheet" href="<?php echo $dirUrl; ?>assets/css/flaticon.css">
    <!-- font awesome CSS -->
    <link rel="stylesheet" href="<?php echo $dirUrl; ?>assets/css/magnific-popup.css">
    <!-- swiper CSS -->
    <link rel="stylesheet" href="<?php echo $dirUrl; ?>assets/css/slick.css"> 
    <link rel="stylesheet" href="<?php echo $dirUrl; ?>assets/css/all.css">
    <link rel="stylesheet" href="<?php echo $dirUrl; ?>assets/css/nice-select.css">
    <!-- style CSS -->
    <link rel="stylesheet" type="text/css" href="<?php echo $dirUrl; ?>assets/css/style.css">
     <!--CUSTOM style -->
    <link rel="stylesheet" type="text/css" href="<?php echo $dirUrl; ?>assets/css/custom.css">
     <!-- style Clander -->
    <link rel="stylesheet" type="text/css" href="<?php echo $dirUrl; ?>assets/css/jquery-ui-cal.css">
    <!-- jquery -->
    <script src="<?php echo $dirUrl; ?>assets/js/jquery-1.12.1.min.js"></script>
    <!-- bootstrap js -->
    <script type="text/javascript" src="<?php echo $dirUrl; ?>assets/js/bootstrap.min.js"></script>
    <script> var baseurl = '<?= $this->config->item('base_url'); ?>'; </script>  
</head>
<body>
    <header class="main_menu home_menu">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <nav class="navbar navbar-expand-lg navbar-light">
                        <a class="navbar-brand" href="<?= base_url(); ?>home">
                            <img src="<?php echo $dirUrl; ?>images/logo_white.png" alt="logo">
                        </a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse"
                            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"  aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse main-menu-item justify-content-end" id="navbarSupportedContent">
                            <ul class="navbar-nav">
                                <li class="nav-item active">
                                    <a class="nav-link js-scroll-trigger" href="<?= base_url(); ?>home#about">ABOUT US</a>
                                </li>
                                <!-- <li class="nav-item">
                                    <a class="nav-link js-scroll-trigger <?php if($this->uri->segment(1)=='gigs'){echo 'active';}?>" href="<?= base_url(); ?>gigs11">GIGS</a>
                                </li> -->
                                <li class="nav-item">
                                    <a class="nav-link js-scroll-trigger" href="<?= base_url(); ?>home#download">DOWNLOAD</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link js-scroll-trigger <?php if($this->uri->segment(1)=='faq'){echo 'active';}?>" href="<?= base_url(); ?>faq">FAQ</a>
                                </li>
                                <?php
                                if( !$this->session->userdata( 'PARTNER_ID' ) ) {
                                ?>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="<?= base_url(); ?>home">Partner</a>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item <?php if($this->uri->segment(1)=='login'){echo 'active';}?>" href="<?= base_url(); ?>partner/login">Login</a> 
                                        <a class="dropdown-item <?php if($this->uri->segment(1)=='register'){echo 'active';}?>" style="color: #fff;" href="<?= base_url(); ?>partner/register">Sign Up</a>
                                    </div>
                                </li>
                                <?php }else{ ?>
                                <li class="nav-item">
                                    <a class="nav-link js-scroll-trigger <?php if($this->uri->segment(1)=='faq'){echo 'active';}?>" href="<?= base_url(); ?>partner/logout">Logout</a>
                                </li>
                                <?php } ?>
                            
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="<?= base_url(); ?>home">Talent</a>
                                    <div class="dropdown-menu">
                                        <?php if( !$this->session->userdata( 'TALENT_ID' ) ) { ?>
                                        <a class="dropdown-item <?php if($this->uri->segment(1)=='login'){echo 'active';}?>" href="<?= base_url(); ?>talent/login">Login</a> 
                                        <a class="dropdown-item <?php if($this->uri->segment(1)=='register'){echo 'active';}?>" style="color: #fff;" href="<?= base_url(); ?>talent/register">Sign Up</a>
                                        <?php }else{ ?>
                                            <a class="nav-link js-scroll-trigger <?php if($this->uri->segment(1)=='faq'){echo 'active';}?>" href="<?= base_url(); ?>talent/forums">Dashboard</a>
                                            <a class="nav-link js-scroll-trigger <?php if($this->uri->segment(1)=='faq'){echo 'active';}?>" href="<?= base_url(); ?>talent/logout">Logout</a>
                                        <?php } ?>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </header>



