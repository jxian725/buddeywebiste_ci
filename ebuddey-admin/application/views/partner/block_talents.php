<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl   = $this->config->item( 'admin_url' );
$dirUrl     = $this->config->item( 'dir_url' );
$upload_path_url = $this->config->item( 'upload_path_url' );

if($partnerInfo->photo){
  $photo = '<img class="img-thumbnail" src="'.$upload_path_url.'partner/'.$partnerInfo->photo.'" style="height: auto;width: 60px;" data-src="#" />';
}else{
  $photo = '';
}
if($partnerInfo->dbkl_lic_enable == 1){
    $dbkl_checked = 'checked';
}else{
    $dbkl_checked = '';
}

?>

<link rel="stylesheet" href="<?= $dirUrl; ?>plugins/iCheck/square/blue.css">
<script src="<?=$dirUrl;?>js/tinymce/tinymce.min.js"></script>


<link rel="stylesheet" href="<?= $assetUrl; ?>plugins/select2/select2.min.css">
<script src="<?= $assetUrl; ?>plugins/select2/select2.full.min.js"></script>

	</head>
	<style>
	
ul#select2-talents-results .select2-container {
  min-width: 400px;
}

ul#select2-talents-results .select2-results__option {
  padding-right: 20px;
  vertical-align: middle;
}
ul#select2-talents-results .select2-results__option:before {
  content: "";
  display: inline-block;
  position: relative;
  height: 20px;
  width: 20px;
  border: 2px solid #e9e9e9;
  background-color: #fff;
  margin-right: 20px;
  vertical-align: middle;
}
ul#select2-talents-results .select2-results__option[aria-selected=true]:before {
  font-family:fontAwesome;
  content: "\f00c";
  color: #fff;
  background-color: #f77750;
  border: 0;
  display: inline-block;
  padding-left: 3px;
}
ul#select2-talents-results .select2-container--default .select2-results__option[aria-selected=true] {
	background-color: #fff;
}
ul#select2-talents-results .select2-container--default .select2-results__option--highlighted[aria-selected] {
	background-color: #eaeaeb;
	color: #272727;
}
ul#select2-talents-results .select2-container--default .select2-selection--multiple {
	margin-bottom: 10px;
}

ul#select2-talents-results .select2-container--default.select2-container--open.select2-container--below .select2-selection--multiple {
	border-radius: 4px;
}
ul#select2-talents-results .select2-container--default.select2-container--focus .select2-selection--multiple {
	border-color: #f77750;
	border-width: 1px;
}
ul#select2-talents-results .select2-container--default .select2-selection--multiple {
	border-width: 1px;
}
ul#select2-talents-results .select2-container--open .select2-dropdown--below {
	
	
	box-shadow: 0 0 10px rgba(0,0,0,0.5);

}
ul#select2-talents-results .select2-selection .select2-selection--multiple:after {
	content: 'hhghgh';
}

</style>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-8">
                      <div id="message_partner"></div>
                      <form novalidate="" id="partner_form" role="form" method="post" enctype="multipart/form-data">
                        <div class="row">
                          <div class="col-sm-12">  
                            <div class="form-group">
							
                              <label for="partner_name">Partner Name</label><b class="text-danger">*</b>
                              <!--<input type="text" value="<?= rawurldecode( $partnerInfo->partner_name ); ?>" class="form-control" name="partner_name" id="partner_name"  readonly>-->
							  	
							<select class="form-control "  data-placeholder="Select Partner" name="partner_id" id="partner_id">
								<option value="">Select</option>
								<?php
								if( $partnerList ) {
									foreach ( $partnerList as $key => $value ) {
										echo '<option '.(($value->partner_id==$partnerInfo->partner_id)? 'selected' : '').' value="'. $value->partner_id .'">'. rawurldecode($value->partner_name) .'</option>';
									}
								}
								?>
							</select>
							  
                            </div>
                          </div>
						
						<div class="col-sm-12">  
                            <div class="form-group">
                              <label for="partner_name">Select Talents</label><b class="text-danger">*</b>
							
								<select name="talents" id="talents"  class="form-control js-select2" multiple="multiple">
								 <?php if( $guiderInfo ) {
                                  foreach ( $guiderInfo as $key => $value ) {
                                    echo'<option  value="'. intval($value->guider_id) .'" data-badge="" id="'. intval($value->guider_id) .'">'. $value->first_name.' '.$value->last_name .' ('.$value->email .')</option>';
                                  }
                                }
                                ?>
								</select>
								  
								
                            </div>
                          </div>
                         
                          <div class="col-sm-12">  
                            <div class="clearfix"></div>
                            <input type="hidden" name="partner_id" value="<?= $partnerInfo->partner_id; ?>" />
                            <input type="button" id="block_talent" value="Update" class="btn btn-success" onclick="blockTalent()">
                            <a href="<?= base_url();?>partners" class="btn btn-danger">Back</a>
                          </div>  
                        </div>
                      </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= $dirUrl; ?>plugins/iCheck/icheck.min.js"></script>
<script>
	
			$("#talents.js-select2").select2({
			closeOnSelect : false,
			placeholder : "Placeholder",
			allowHtml: true,
			allowClear: true,
			tags: true // создает новые опции на лету
		});

			// $('.icons_select2').select2({
				// width: "100%",
				// templateSelection: iformat,
				// templateResult: iformat,
				// allowHtml: true,
				// placeholder: "Placeholder",
				// dropdownParent: $( '.select-icon' ),//обавили класс
				// allowClear: true,
				// multiple: false
			// });
	

				// function iformat(icon, badge,) {
					// var originalOption = icon.element;
					// var originalOptionBadge = $(originalOption).data('badge');
				 
					// return $('<span><i class="fa ' + $(originalOption).data('icon') + '"></i> ' + icon.text + '<span class="badge">' + originalOptionBadge + '</span></span>');
				// }

	</script>
<script type="text/javascript">
$( document ).ready(function() {
	 $('#partner_id').select2();
	 $('#partner_id').change(function(e) {
        var partner_id = $(this).val();
        if(partner_id){
           window.location.href="<?= base_url(); ?>partners/blocktalent/"+partner_id;
        }
    });
var users = <?php echo json_encode($blockedtalentList); ?>;
for(var i=0; i< users.length; i++){
    $('#talents option[value='+users[i].talent_id+']').attr("selected", "selected");
	$('.ms-options-wrap  ul li label input[type="checkbox"][value=' + users[i].talent_id +']').prop('checked', true);
	//prop('checked', true);
}


// var i;
// for(i=0;i<=users.length;i++)
// {
// var optionid=users[i]['talent_id'];	
// $("#talents").val(optionid)
// .find("option[value=" + optionid +"]").attr('selected', true);
// $(".ms-options-wrap  ul label input[type='checkbox'][value=" + optionid +"]").parent().find('li').addClass("hi");

//prop('checked', true);
//}
});

var data=[];
function blockTalent(){		
	
	var $el=$("#talents");
	$el.find('option:selected').each(function(){
		data.push({value:$(this).val()});
	});
	var partnerid='<?php echo $partnerInfo->partner_id ; ?>';
	
	if(data.length==0){
		 $.ajax( {
            type    : "POST",
            data    : {partnerid:partnerid},
            url     : adminurl + 'partners/deleteBlockTalent',
            success: function( msg ) {
				 if(msg == 1){
                  
                    toastr.success( 'Talent Unblocked successfully.','Success' );
					window.location.href = '<?= base_url();?>partners';
				 }
			}
		});	
	}
	else{
		$.ajax( {
            type    : "POST",
            data    : {partnerid:partnerid,talents_id:data},
            url     : adminurl + 'partners/addBlockTalent',
            success: function( msg ) {
				 if(msg == 1){
                  
                    toastr.success( 'Talent blocked successfully.','Success' );
					window.location.href = '<?= base_url();?>partners';
				 }
			}
		});	
	}	 

}
</script>