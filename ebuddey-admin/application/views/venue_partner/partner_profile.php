<?php
defined('BASEPATH') OR exit('No direct script access allowed');   
$dirUrl     = $this->config->item( 'dir_url' );
$name       = $partnerInfo->company_name;
?>
<link rel="stylesheet" href="<?= $dirUrl; ?>plugins/lightbox/css/lightbox.min.css">
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header">
                <div class="box-tools pull-right">
                    <a href="<?php echo $dirUrl; ?>Venuepartner" class="btn btn-sm btn-primary">Back</a>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-9">
                        <div class="form-group row">
                            <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Company Name&nbsp;&nbsp;
                                <a href="javascript:;" title="Edit" onclick="return updatePartnerForm(<?= $partnerInfo->venuepartnerId; ?>,'company_name');"><i class="fa fa-edit"></i></a>
                            </label>
                            <div class="col-sm-9">
                                <div><?=$partnerInfo->company_name; ?></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Email&nbsp;&nbsp;
                                <a href="javascript:;" title="Edit" onclick="return updatePartnerForm(<?= $partnerInfo->venuepartnerId; ?>,'email');"><i class="fa fa-edit"></i></a>
                            </label>
                            <div class="col-sm-9">
                                <div><?=$partnerInfo->email; ?></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Address&nbsp;&nbsp;
                                <a href="javascript:;" title="Edit" onclick="return updatePartnerForm(<?= $partnerInfo->venuepartnerId; ?>,'business_address');"><i class="fa fa-edit"></i></a>
                            </label>
                            <div class="col-sm-9">
                                <div><?= $partnerInfo->business_address; ?></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Postcode&nbsp;&nbsp;
                                <a href="javascript:;" title="Edit" onclick="return updatePartnerForm(<?= $partnerInfo->venuepartnerId; ?>,'postcode');"><i class="fa fa-edit"></i></a>
                            </label>
                            <div class="col-sm-9">
                                <div><?=$partnerInfo->postcode; ?></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">City&nbsp;&nbsp;
                                <a href="javascript:;" title="Edit" onclick="return updatePartnerForm(<?= $partnerInfo->venuepartnerId; ?>,'city');"><i class="fa fa-edit"></i></a>
                            </label>
                            <div class="col-sm-9">
                                <div><?=$partnerInfo->cityName; ?></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Person in Charge&nbsp;&nbsp;
                                <a href="javascript:;" title="Edit" onclick="return updatePartnerForm(<?= $partnerInfo->venuepartnerId; ?>,'contact_person');"><i class="fa fa-edit"></i></a>
                            </label>
                            <div class="col-sm-9">
                                <div><?=$partnerInfo->contact_person; ?></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Phone Number&nbsp;&nbsp;
                                <a href="javascript:;" title="Edit" onclick="return updatePartnerForm(<?= $partnerInfo->venuepartnerId; ?>,'mobile_no');"><i class="fa fa-edit"></i></a>
                            </label>
                            <div class="col-sm-9">
                                <div><?=$partnerInfo->mobile_no; ?></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Bank Name&nbsp;&nbsp;
                                <a href="javascript:;" title="Edit" onclick="return updatePartnerForm(<?= $partnerInfo->venuepartnerId; ?>,'bank_name');"><i class="fa fa-edit"></i></a>
                            </label>
                            <div class="col-sm-9">
                                <div><?= $partnerInfo->bank_name; ?></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Bank Account Name&nbsp;&nbsp;
                                <a href="javascript:;" title="Edit" onclick="return updatePartnerForm(<?= $partnerInfo->venuepartnerId; ?>,'account_name');"><i class="fa fa-edit"></i></a>
                            </label>
                            <div class="col-sm-9">
                                <div><?= $partnerInfo->account_name; ?></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Bank Account Number&nbsp;&nbsp;
                                <a href="javascript:;" title="Edit" onclick="return updatePartnerForm(<?= $partnerInfo->venuepartnerId; ?>,'account_number');"><i class="fa fa-edit"></i></a>
                            </label>
                            <div class="col-sm-9">
                                <div><?= $partnerInfo->account_number; ?></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Password&nbsp;&nbsp;
                                <a href="javascript:;" title="Edit" onclick="return updatePartnerForm(<?= $partnerInfo->venuepartnerId; ?>,'password');"><i class="fa fa-edit"></i></a>
                            </label>
                            <div class="col-sm-9">
                                <div><?= $this->encryption->decrypt($partnerInfo->password); ?></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Buskers Pod</label>
                            <div class="col-sm-9">
                                <?php
                                if($partnerInfo->partner_id){
                                    $array  = explode(',', $partnerInfo->partner_id);
                                    foreach ($array as $item) {
                                        $langInfo = $this->Venue_partnermodel->partner_Info($item);
                                        if($langInfo){ $partner_name = $langInfo->partner_name; } ?>
                                        <?php echo "*"; ?><?php echo rawurldecode($partner_name); ?><br>
                                    <?php     
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>       
        </div>
    </div>
</div>
<script src="<?= $dirUrl; ?>plugins/lightbox/js/lightbox-plus-jquery.min.js"></script>
<script type="text/javascript">
function updatePartnerForm( venuepartnerId, field ) {
    $( '#myModal' ).modal( 'show' );
    $( '#myModal .modal-body' ).html('<img src="<?=$dirUrl; ?>img/ajax-loader.gif" style="display: block; margin: 0 auto; width: 100px;" alt="loading..."/>');
    var title = '';
    if(field == 'business_address'){
        title = 'Address';
    }else if(field == 'email'){
        title = 'Email';
    }else if(field == 'company_name'){
        title = 'Company Name';    
    }else if(field == 'postcode'){
        title = 'Postcode';
    }else if(field == 'city'){
        title = 'City';
    }else if(field == 'contact_person'){
        title = 'Person in Charge';
    }else if(field == 'mobile_no'){
        title = 'Phone Number';
    }else if(field == 'bank_name'){
        title = 'Bank Name';
    }else if(field == 'account_name'){
        title = 'Account Name';
    }else if(field == 'account_number'){
        title = 'Account Number';
    }else if(field == 'password'){
        title = 'Password';                
    }
    $('#myModal .modal-title').html( title );
    var data = 'venuepartnerId='+venuepartnerId+'&field='+field;
    $.ajax( {
        type: "POST",
        data: data,
        url: adminurl + 'Venuepartner/updatePartnerForm',
        success: function( msg ) {
            $( '#myModal .modal-body' ).html(msg);
            $( '#myModal .modal-footer' ).html('');
        }
    });
    return false;
}
</script>