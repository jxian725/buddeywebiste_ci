<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 
$assetUrl   = $this->config->item( 'admin_dir_url' );
$site_name  = $this->config->item( 'site_name' );
$dirUrl     = $this->config->item( 'dir_url' );
$name       = $partnerInfo->company_name;
?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-9">
                        <div class="form-group row">
                            <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Company Name</label>
                            <div class="col-sm-9">
                                <div><?=$partnerInfo->company_name; ?></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Email</label>
                            <div class="col-sm-9">
                                <div><?=$partnerInfo->email; ?></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Address</label>
                            <div class="col-sm-9">
                                <div><?= $partnerInfo->business_address; ?></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Postcode</label>
                            <div class="col-sm-9">
                                <div><?=$partnerInfo->postcode; ?></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">City</label>
                            <div class="col-sm-9">
                                <div><?=$partnerInfo->cityName; ?></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Person in Charge</label>
                            <div class="col-sm-9">
                                <div><?=$partnerInfo->contact_person; ?></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Phone Number</label>
                            <div class="col-sm-9">
                                <div><?=$partnerInfo->mobile_no; ?></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Bank Name</label>
                            <div class="col-sm-9">
                                <div><?= $partnerInfo->bank_name; ?></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Bank Account Name</label>
                            <div class="col-sm-9">
                                <div><?= $partnerInfo->account_name; ?></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Bank Account Number</label>
                            <div class="col-sm-9">
                                <div><?= $partnerInfo->account_number; ?></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Password</label>
                            <div class="col-sm-9">
                                <div><?php $password = $this->encryption->decrypt($partnerInfo->password); ?></div>
                                <input type="password" id="password" value="<?= $password ;?>"/>
                                <button onclick="if (password.type == 'text') password.type = 'password';
                                else password.type = 'text';"><i class="fa fa-eye"></i></button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Buskers Pod</label>
                            <div class="col-sm-9">
                                <?php
                                if($partnerInfo->partner_id){
                                    $array  = explode(',', $partnerInfo->partner_id);
                                    foreach ($array as $item) {
                                        $langInfo = $this->Partnermodel->partner_Info($item);
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
<script type="text/javascript">
function myFunction() {
  var x = document.getElementById("myPsw").value;
  document.getElementById("demo").innerHTML = x;
}
</script>