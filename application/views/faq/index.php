<?php
$dirUrl = $this->config->item( 'dir_url' );  
?>
<header  style="background-image: url('<?=$this->config->item( 'dir_url' );?>img/pattern/support.png'); background-position: 50% 2px !important;">
    <div class="layer"></div>
    <div class="container parallax-content text-center breadcrumbs_title">
        <h2>Support</h2>
    </div>
</header>
<style type="text/css">
.panel-group .panel {
    border-radius: 0;
    background-color: transparent;
}
.panel-default > .panel-heading {
    background-color: transparent;
    color: inherit;
    position: relative;
    border: none;
    border-radius: 0;
    padding: 0;
}
.panel-heading .panel-title {
    font-size: 18px;
}
.panel-title {
    font-size: 20px;
    text-transform: none;
    font-weight: 400;
    padding: 0;
    position: relative;
}
#faq_list ul {
    list-style: none;
    padding-left: 22px;
    overflow: hidden;
    margin-left: 10px;
}

.panel-title > a.collapsed {
    background-color: #666;
    text-decoration: none;
}   
.panel-title > a {
    font-size: 14px;
    text-transform: uppercase;
    display: block;
    padding: 14px 40px 14px 30px;
    background-color: #2d86ff;
    color: #fff !important;
}
.panel-title>a {
    color: inherit;
}
a[data-toggle="collapse"] {
    position: relative;
}
.panel-title > a.collapsed:hover {
    background-color: #2d86ff;
    text-decoration: none;
}
.panel-group .panel-heading + .panel-collapse .panel-body {
    padding-top: 15px;
    padding-bottom: 6px;
    padding-left: 25px;
    border-right: 1px solid #cccccc;
    border-left: 1px solid #cccccc;
    border-bottom: 1px solid #cccccc;
    border-top: none;
}
.panel-title > a.collapsed:after {
    content: '+';
    right: 24px;
    top: 14px;
    font-size: 22px;
    text-decoration: none;
}
.panel-title > a:after {
    color: #fff;
    content: '-';
    position: absolute;
    font-size: 22px;
    right: 27px;
    top: 12px;
}

.input-group-btn:last-child>.btn, .input-group-btn:last-child>.btn-group {
    margin-left: -1px;
}
</style>
<section class="cta_part">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12">
                <?php echo form_open( base_url().'faq', 'method="get" id="support_form"' ); ?>
                <div class="cta_part_iner">
                    <div class="cta_part_text center_div">
                        <h1>Tell us how can we help?</h1>
                        <div class="form_center_div">
                            <form class="form-contact contact_form" role="form" method="post" id="gigsForm" novalidate="">
                                <div class="col-12">
                                    <div class="input-group"> 
                                       <input type="text" name="search" class="typeahead form-control" style="height: 45px;" placeholder="Enter Keywords to search answer.." aria-describedby="basic-addon1">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" style="height: 45px; z-index: 9999; background-color: #2d86ff; color: #fff;" type="submit">Search</button>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <p>Check out the topics below for helpfull answer.</p>
                                    </div>
                                </div> 
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="content" style="padding: 10px 0px;">
    <div class="container">
        <div class="row">
            <div class="col-sm-12" id="faq_list">
                <div class="panel-group" id="accordion">
                    <?php
                    if($faq_lists){
                        $i = 1;
                        $open = 'show';
                        $closed = '';
                        foreach ( $faq_lists as $key => $faq ) {
                            ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#contactus_<?=$i;?>" class="<?=$closed;?>">
                                            <?= $faq->title; ?>
                                        </a>
                                    </h4>
                                </div>
                                <div id="contactus_<?=$i;?>" class="panel-collapse collapse <?=$open;?>">
                                    <div class="panel-body">
                                        <?= $faq->content; ?>
                                    </div>
                                </div>
                            </div>
                        <?php
                        $i++;
                        $closed = 'collapsed';
                        $open = '';
                        }
                    }
                    ?>
                </div>
            </div>  
        </div>
    </div>
</section>


