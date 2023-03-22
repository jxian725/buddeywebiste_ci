<?php
$dirUrl = $this->config->item( 'dir_url' );
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="keywords" content="<?= $header['metakeyword']; ?>">
    <meta name="description" content="<?= $header['metakeyword']; ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Buddey | <?= $header['title']; ?></title>
    <link rel="icon" type="image/jpeg" href="<?php echo $dirUrl; ?>assets/img/favicon.png" sizes="16x16">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo $dirUrl; ?>assets/css/bootstrap.min.css">
    <!-- animate CSS -->
    <link rel="stylesheet" href="<?php echo $dirUrl; ?>assets/css/animate.css">
    <!-- owl carousel CSS -->
    <link rel="stylesheet" href="<?php echo $dirUrl; ?>assets/css/owl.carousel.min.css">
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
                                <li class="nav-item">
                                    <a class="nav-link js-scroll-trigger <?php if($this->uri->segment(1)=='gigs'){echo 'active';}?>" href="<?= base_url(); ?>gigs">GIGS</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link js-scroll-trigger" href="<?= base_url(); ?>home#download">DOWNLOAD</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link js-scroll-trigger <?php if($this->uri->segment(1)=='faq'){echo 'active';}?>" href="<?= base_url(); ?>faq">FAQ</a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </header>



