<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 
$adminUrl   = $this->config->item( 'admin_dir_url' );
$dirUrl     = $this->config->item( 'dir_url' );
?>
<style type="text/css">
.card{
    position: relative;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-direction: column;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid rgba(0,0,0,.125);
    border-radius: .25rem;
    padding: 10px;
    margin: 10px;
}
.card-img-top {
    width: 100%;
    border-top-left-radius: calc(.25rem - 1px);
    border-top-right-radius: calc(.25rem - 1px);
    height: 300px;
}
.card-body {
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
    padding: 0.75rem;
}
.card-text{ font-weight: bold; }
.card .overlay{
    font-size: 18px;
    font-family: tahoma;
    margin-top: 0px;
    margin-right: 10px;
    position: absolute;
    top: 10px;
    right: 0;
}
</style>
<link rel="stylesheet" type="text/css" href="<?php echo $adminUrl; ?>plugins/sweetalert/sweetalert.css">
<script src="<?php echo $adminUrl; ?>plugins/sweetalert/sweetalert.js"></script>
<div class="count_box row">
    <div class="col-md-12">
        <div class="box-header with-border">
            <h2 class="box-title">Manage verification</h2>
            <div class="box-tools pull-right">
                <a href="<?= base_url().'talent/profile'; ?>" class="btn btn-info btn-sm">Back to Account</a>
            </div>
        </div>
    </div>

    <div class="container"> 
        <div class="col-md-12"> 
            <div class="row" id="license_data">
                <?php
                $talent_id = $this->session->userdata['TALENT_ID'];
                if($getLicenseList){
                    foreach ($getLicenseList as $key => $value) {
                        $licenseInfo = $this->Talentmodel->talentLicenseInfo($talent_id, $value->license_id);
                        if($licenseInfo){
                            if($licenseInfo->license_image){
                                $licenseImg = $adminUrl.'uploads/license/'.$licenseInfo->license_image;
                            }else{
                                $licenseImg = $dirUrl.'assets/img/license_place.jpg';
                            }
                            $license_no = $licenseInfo->license_number;
                        }else{
                            $licenseImg = $dirUrl.'assets/img/license_place.jpg';
                            $license_no = 'XXXXXXXXXX';
                        }
                        ?>
                        <div class="col-md-4 col-xs-12">
                            <div class="card"> 
                                <div class="overlay">
                                    <a href="javascript:;" onclick="return licenseUploadForm(<?= $value->license_id; ?>);" class="btn btn-primary btn-xs"><i class="fa fa-upload"></i> Upload</a>
                                </div>
                                <img class="card-img-top" src="<?= $licenseImg; ?>" id="license_img_<?= $value->license_id; ?>"> 
                                <div class="card-body text-center"> 
                                    <p class="card-text" id="license_no_<?= $value->license_id; ?>"><?= $license_no; ?></p>
                                    <h4 class="card-title"><?= $value->license_name; ?></h4> 
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                }?>
            </div> 
        </div> 
    </div>
</div>

<script type="text/javascript">

function licenseUploadForm(license_id) {

    $( '#myModal' ).modal( 'show' );
    $( '#myModal #mymodalBody' ).html('');
    $( '#myModal #mymodalTitle' ).html( 'Upload verification' );
    $.ajax({
        type: "POST",
        data: {license_id:license_id},
        url: baseurl + 'talent/license/getUploadForm',
        success: function( data ) {
          $( '#myModal #mymodalBody' ).html(data);
        }
    });

    //$( '#myModal #mymodalBody' ).html( '');
    $( '#my_modal_footer' ).html( '<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button><button type="button" id="continuemodal_'+license_id+'" class="btn btn-primary">Update</button>' );
    $( '#continuemodal_'+license_id ).click( function(e) {

        e.preventDefault();

        var license_number = $('#update_license_form #license_number').val();
        var talent_license_id = $('#update_license_form #talent_license_id').val();
        if( $("#license_image").get(0).files.length === 0 && talent_license_id == '' ){
            toastr.error('No verification file selected','Error');
            return false;
        }
        if(license_number == '' || license_number == 0){
          toastr.error('License Number Cannot be empty','Error');
          return false;
        }

        var form = $('#licenseForm')[0];
        var formData = new FormData(form);
        $.ajax({
            url: baseurl + 'talent/license/ajaxUploadLicense',
            type: "POST",
            data:  formData,
            contentType: false,
            cache: false,
            processData:false,
            beforeSend : function(){
                $("#licenseForm").css("opacity", ".7");
            },
            success: function(data){
                $("#licenseForm").css("opacity", "");
                if(data.result == 'success'){
                    $("#licenseForm")[0].reset();
                    $("#license_data #license_img_"+license_id).attr("src", data.img_url);
                    $("#license_data #license_no_"+license_id).html(data.license_no);
                    $("#myModal").modal( 'hide' );
                    swal("Verification", data.message, "success");
                }else{
                    //$("#alert-msg").html(data.message).fadeIn();
                    toastr.error(data.message,'Error');
                    return false;
                }
            },
            error: function(e){
                $("#alert-msg").html(e).fadeIn();
            }
        });
        return false;
    });
}

function ajax_license_data(){
    $.ajax({
        type: "POST",
        url: baseurl + 'talent/license/ajax_license_data',
        data : {},
        success : function( html ) {
            $('#license_data').html(html);
        }
    });
}
</script>