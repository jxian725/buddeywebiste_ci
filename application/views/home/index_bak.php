<?php
$dirUrl = $this->config->item( 'dir_url' );
$admin_upload_url = $this->config->item( 'admin_upload_url' );
?>
<style type="text/css">
.owl-carousel .nav-btn{
  height: 47px;
  position: absolute;
  width: 26px;
  cursor: pointer;
  top: 200px !important;
  background-color: #51cdfa !important;
}

.owl-carousel .owl-prev.disabled,
.owl-carousel .owl-next.disabled{
    pointer-events: none;
    opacity: 0.2;
}

.owl-carousel .prev-slide{
  background: url(assets/img/nav-icon.png) no-repeat scroll 0 0;
  left: 0px;
}
.owl-carousel .next-slide{
  background: url(assets/img/nav-icon.png) no-repeat scroll -24px 0px;
  right: 0px;
}
.owl-carousel .prev-slide:hover{
    background-position: 0px -53px;
}
.owl-carousel .next-slide:hover{
    background-position: -24px -53px;
}
</style>
<section class="breadcrumb breadcrumb_bg home_banner">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb_iner text-left">
                    <div class="breadcrumb_iner_item">
                        <h1>There's a place</h1>
                         <h1>for every talent.</h1>
                         <h4>Fast. Simple. Fair. Transparent.</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="hero-app-1 custom-animation"><img src="<?php echo $dirUrl; ?>images/animate_icon/icon_1.png" alt=""></div>
        <div class="hero-app-5 custom-animation2"><img src="<?php echo $dirUrl; ?>images/animate_icon/icon_3.png" alt=""></div>
        <div class="hero-app-7 custom-animation3"><img src="<?php echo $dirUrl; ?>images/animate_icon/icon_2.png" alt=""></div>
        <div class="hero-app-8 custom-animation"><img src="<?php echo $dirUrl; ?>images/animate_icon/icon_4.png" alt=""></div>
    </div>
</section>

<section class="about_part" id="about">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-lg-5">
                <div class="about_img">
                    <img src="<?php echo $dirUrl; ?>assets/img/home1.png" class="img_1" alt="">
                    <img src="<?php echo $dirUrl; ?>assets/img/home2.png" class="img_2" alt="">
                </div>
            </div>
            <div class="offset-lg-1 col-lg-4">
                <div class="about_text">
                    <h2>About Us</h2>
                    <?= rawurldecode( $pageInfo->page_content ); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="hero-app-7 custom-animation"><img src="<?php echo $dirUrl; ?>images/animate_icon/icon_7.png" alt=""></div>
    <div class="hero-app-8 custom-animation2"><img src="<?php echo $dirUrl; ?>images/animate_icon/icon_4.png" alt=""></div>
    <div class="hero-app-6 custom-animation3"><img src="<?php echo $dirUrl; ?>images/animate_icon/icon_5.png" alt=""></div>
</section>
<!-- empty-->

<section class="our_latest_work section_padding" id="venue_partner">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="section_tittle text-center">
                    <h2>VENUE PARTNERS</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="owl-carousel owl-theme" id="vp_carousel">
                    <?php
                    if($venuePartnerImgLists){
                        foreach ($venuePartnerImgLists as $key => $value) { ?>
                        <div class="item">
                          <img src="<?php echo $admin_upload_url; ?>cms_venue_partner/<?php echo $value->venue_partner_img; ?>" alt="<?php echo rawurldecode($value->title); ?>" class="img-fluid">
                        </div>
                    <?php } } ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="our_latest_work section_padding" id="download">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="section_tittle text-center">
                    <h2>DOWNLOAD</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="single_work">
                    <div class="row align-items-center">
                        <div class="offset-lg-1 col-lg-4 col-md-6">
                            <div class="single_work_demo">
                                <h3>Buddey Talent</h3>
                                <p>Find and book a place for street performance, fast, easy and safe. You are in control of your own busking activity plan, find your Buddey Buskers Pods, set your own schedule, go busking and make additional income.</p>
                                <p>
                                    <a href="https://apps.apple.com/us/app/buddey-talent/id1499691337?ls=1" target="_blank">
                                        <img src="<?php echo $dirUrl; ?>images/ios-app-store.png" alt="" class="img-fluid"  style="width: 45%;">
                                    </a>
                                   <a href="https://play.google.com/store/apps/details?id=com.buddeyapp.guider" target="_blank">
                                        <img src="<?php echo $dirUrl; ?>images/play.png" alt="" class="img-fluid"  style="width: 45%;padding: 7px;">
                                    </a>
                                </p>
                            </div>
                        </div>
                        <div class="offset-lg-1 col-lg-6 col-md-6">
                            <div class="demo_img">
                                <img src="<?php echo $dirUrl; ?>images/Buddey Talent.png" alt="demo">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- <section class="our_latest_work section_padding" id="download">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                 <div class="single_work">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-md-6">
                            <div class="demo_img">
                                <img src="<?php echo $dirUrl; ?>images/BuddeyFans.png" alt="demo">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="single_work_demo">
                               <h3>Buddey Fans</h3>
                                <p>Find your talents for hire easily. Message them directly.</p>
                                <a href="https://play.google.com/store/apps/details?id=com.buddeyapp.buddey" target="_blank">
                                    <img src="<?php echo $dirUrl; ?>images/play.png" alt="" class="img-fluid"  style="width: 45%;padding: 7px;">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> -->

<section class="blog_part section_padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5">
                <div class="section_tittle text-center">
                    <h2>INSTAGRAM</h2>
                </div>
            </div>
        </div>
        <div class="row new_insta_feed owl-carousel owl-theme" id="new_insta_feed">
        </div>
    </div>
</section>

<!-- footer part start-->
<!--Instagram Feed-->
<script type="text/javascript">
var token = '5854862438.1677ed0.e08458d93f84419a9160eb446bec4943',
num_photos = 10;

$.ajax({
    url: 'https://api.instagram.com/v1/users/5854862438/media/recent',
    dataType: 'jsonp',
    type: 'GET',
    data: {access_token: token, count: num_photos},
    success: function(data){
        //console.log(data);
        for( x in data.data ){
            var date = new Date(data.data[x].created_time * 1000);
            var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            var year = date.getFullYear();
            var month = months[date.getMonth()];
            var date = date.getDate();
            var time = month + ', ' + date + ', ' + year;
            $('#new_insta_feed').append(
                        '<div class="item">'+
                            '<div class="single-home-blog">'+
                                '<div class="team_member_social_icon">'+
                                    '<img class="Profile_img" src="'+data.data[x].user.profile_picture+'" style="height:auto;;width:10%;border-radius: 50%;display: -webkit-inline-box;">'+
                                    '<span class="insta_user">'+data.data[x].user.username+'</span>'+
                                    '<div>'+
                                        '<span class="right" style="padding-right: 5px;float: right;"><i class="ti-instagram"></i></span>'+
                                        '<span class="left insta_date">'+time+'</span>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="card">'+
                                    '<img src="'+data.data[x].images.standard_resolution.url+'" class="card-img-top" alt="" style="min-height: 300px;max-height: 300px;">'+
                                    '<div class="card-body">'+
                                        '<div><ul>'+
                                            '<li><i class="ti-heart"></i> '+data.data[x].likes.count+'</li>'+
                                            '<li class="insta_share"><a href="'+data.data[x].link+'" target="_blank"<i class="ti-share"></i> Share</a></li>'+
                                        '</ul></div>'+
                                        '<div class="insta_text">'+data.data[x].caption.text+'</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</div>'
                        );
        }

        var owl = $("#new_insta_feed");
        owl.owlCarousel({
            items: 4,
            loop: true,
            margin: 0,
            nav: true,
            dots: false,
            navText:["<div class='nav-btn prev-slide'></div>","<div class='nav-btn next-slide'></div>"],
            responsiveClass: true,
            responsive: {
              0: {
                items: 1,
                nav: true
              },
              400: {
                items: 3,
                nav: true
              },
              600: {
                items: 4,
                nav: true,
                loop: false,
                margin: 10
              }
            }
            
        });
    },
    error: function(data){
        console.log(data);
    }
});

$(document).ready(function(){
    // Add smooth scrolling to all links
    $(".js-scroll-trigger").on('click', function(event) {
    // Make sure this.hash has a value before overriding default behavior
        if (this.hash !== "") {
            // Prevent default anchor click behavior
            event.preventDefault();
            // Store hash
            var hash = this.hash;
            // Using jQuery's animate() method to add smooth page scroll
            // The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
            $('html, body').animate({
                scrollTop: $(hash).offset().top
            }, 800, function(){
                // Add hash (#) to URL when done scrolling (default click behavior)
                window.location.hash = hash;
            });
        } // End if
    });
});
</script>
<script>
$(document).ready(function() {
  $('#vp_carousel').owlCarousel({
    loop: true,
    margin: 10,
    nav: false,
    responsiveClass: true,
    responsive: {
      0: {
        items: 1,
        nav: false
      },
      600: {
        items: 3,
        nav: false
      },
      1000: {
        items: 5,
        nav: false,
        loop: false,
        margin: 20
      }
    }
  });
})
</script>
