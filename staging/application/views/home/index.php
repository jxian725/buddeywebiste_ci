<?php
$dirUrl = $this->config->item( 'dir_url' );
?>
<style type="text/css">
#rudr_instafeed{
  list-style:none  
}
#rudr_instafeed li{
  float:left;
  width:200px;
  height:200px;
  margin:10px
}
#rudr_instafeed li img{
  max-width:100%;
  max-height:100%;
}
.insta_user{
    padding-left: 10px;
}
.insta_date{
    color: #646464;
}
.new_insta_feed .card-body{
    padding: 10px !important;
}
.new_insta_feed .single-home-blog {
    border: 1px solid #ece9e9;
}
.new_insta_feed .team_member_social_icon {
    padding: 5px;
}
.insta_text {
    min-height: 150px;
}
</style>
	<section class="banner_part">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-xl-6 col-md-6">
                    <div class="banner_text">
                        <div class="banner_text_iner text-center">
                             <h1>THERE'S A PLACE</h1>
                             <h1>FOR EVERY TALENT.</h1>
                             <h4>Fast. Simple. Fair. Transparent.</h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6"> 
                    <div class="banner_bg">
                        <img src="<?php echo $dirUrl; ?>images/banner_img.png" alt="banner">
                    </div>
                </div>
            </div>
            <div class="hero-app-1 custom-animation"><img src="<?php echo $dirUrl; ?>images/animate_icon/icon_1.png" alt=""></div>
            <div class="hero-app-5 custom-animation2"><img src="<?php echo $dirUrl; ?>images/animate_icon/icon_3.png" alt=""></div>
            <div class="hero-app-7 custom-animation3"><img src="<?php echo $dirUrl; ?>images/animate_icon/icon_2.png" alt=""></div>
            <div class="hero-app-8 custom-animation"><img src="<?php echo $dirUrl; ?>images/animate_icon/icon_4.png" alt=""></div>
        </div>
    </section>
	<!-- End Sample Area -->

	<!-- Start Button -->
	<!-- End Button -->
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
                                        <a href="https://apps.apple.com/my/app/buddey-guest/id1439737991" target="_blank">
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
    <section class="our_latest_work section_padding" id="download">
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
    </section>
    
    <section class="blog_part section_padding">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-5">
                    <div class="section_tittle text-center">
                        <h2>Instagram</h2>
                    </div>
                </div>
            </div>
            <div class="row new_insta_feed" id="new_insta_feed">

            </div>
        </div>
    </section>
        
	<!-- footer part start-->
    <!--Instagram Feed-->
    <script type="text/javascript">
    var token = '5854862438.1677ed0.e08458d93f84419a9160eb446bec4943',
    num_photos = 4;
 
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
                            '<div class="col-sm-6 col-lg-3 col-xl-3">'+
                                '<div class="single-home-blog">'+
                                    '<div class="team_member_social_icon">'+
                                        '<img class="Profile_img" src="'+data.data[x].user.profile_picture+'" style="height:auto;;width:10%;border-radius: 50%;">'+
                                        '<span class="insta_user">'+data.data[x].user.username+'</span>'+
                                        '<div>'+
                                            '<span class="right" style="padding-right: 5px;float: right;"><i class="ti-instagram"></i></span>'+
                                            '<span class="left insta_date">'+time+'</span>'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="card">'+
                                        '<img src="'+data.data[x].images.standard_resolution.url+'" class="card-img-top" alt="" style="min-height: 260px;">'+
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
