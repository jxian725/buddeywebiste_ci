<?php
  $dirUrl = $this->config->item( 'dir_url' );  
?>
    <!-- footer part start-->
    <footer class="footer-area">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-sm-6 col-md-6 col-xl-3">
                    <div class="single-footer-widget footer_2">
                        <a href="#"><img src="<?php echo $dirUrl; ?>images/logo_white.png" alt="" style="height: 62px;"></a>
                        <div class="social_icon">
                          <a href="https://www.facebook.com/buddeytf/" target="_blank"> <i class="ti-facebook"></i> </a>
                          <a href="https://www.instagram.com/buddeytf/" target="_blank"> <i class="ti-instagram"></i> </a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-xl-3">
                    <div class="single-footer-widget footer_2">
                        <h4 class="text-uppercase">Company</h4>
                        <ul class="list-unstyled">
                            <li><a href="#about">About us</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-xl-3">
                    <div class="single-footer-widget footer_2">
                        <h4 class="text-uppercase">Help</h4>
                        <ul class="list-unstyled">
                            <li><a href="<?= base_url(); ?>faq">FAQ</a></li>
                            <li><a href="#!">Contact</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-12">
                    <div class="copyright_part_text text-center">

                        <div class="row d-flex align-items-center">
                          <!-- Grid column -->
                          <div class="col-md-7 col-lg-8">
                            <!--Copyright-->
                            <p class="footer-text m-0 text-center text-md-left">Copyright &copy;<script>document.write(new Date().getFullYear());</script> Buddey is a trademark of Buddey Technology
                            </p>
                          </div>
                          <!-- Grid column -->

                          <!-- Grid column -->
                          <div class="col-md-5 col-lg-4 ml-lg-0">
                            <div class="text-center text-md-right">
                              <ul class="list-unstyled list-inline">
                                <li class="list-inline-item footer-vertical-l">
                                  <a href="#" class="btn-floating btn-sm rgba-white-slight mx-1 waves-effect waves-light">
                                    Terms and Conditions
                                  </a>
                                </li>
                                <li class="list-inline-item">
                                  <a href="#" class="btn-floating btn-sm rgba-white-slight mx-1 waves-effect waves-light">
                                    Privacy
                                  </a>
                                </li>
                              </ul>
                            </div>
                          </div>
                          <!-- Grid column -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- footer part end-->

<!-- popper js -->
<script type="text/javascript" src="<?php echo $dirUrl; ?>assets/js/popper.min.js"></script>
<!-- easing js -->
<script type="text/javascript" src="<?php echo $dirUrl; ?>assets/js/jquery.magnific-popup.js"></script>
<!-- swiper js -->
<script type="text/javascript" src="<?php echo $dirUrl; ?>assets/js/swiper.min.js"></script>
<!-- swiper js -->
<script type="text/javascript" src="<?php echo $dirUrl; ?>assets/js/masonry.pkgd.js"></script>
<!-- particles js -->
<script type="text/javascript" src="<?php echo $dirUrl; ?>assets/js/owl.carousel.min.js"></script>
<script type="text/javascript" src="<?php echo $dirUrl; ?>assets/js/jquery.counterup.min.js"></script>
<script type="text/javascript" src="<?php echo $dirUrl; ?>assets/js/waypoints.min.js"></script>
<script type="text/javascript" src="<?php echo $dirUrl; ?>assets/js/owl.carousel2.thumbs.min.js"></script>
<!-- swiper js -->
<script type="text/javascript" src="<?php echo $dirUrl; ?>assets/js/slick.min.js"></script>
<!-- custom js -->
<script type="text/javascript" src="<?php echo $dirUrl; ?>assets/js/custom.js"></script>
</body>
</html>
