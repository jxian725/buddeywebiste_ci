<?php
$permission_arr     = array(); 
if( isset( $permission_list ) && !empty( $permission_list )  ) {
	foreach ( $permission_list as $key => $value ) {
		$permission_arr[ $value->module ] = $value->module;
	} 
}					  
?>
<div class="row add-permission-form">
    <div class="col-xs-12 col-sm-12">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                    	<h4>Permission</h4>
                      <div id="message_role"></div>
                      <div class="box-body table-responsive no-padding">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Module</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            	<tr>
			                    	<td>
			                    		<input type="checkbox" name="pendingguider[]" id="pendingguider" class="allPLCheck0" <?php if( in_array( 'pendingguider', $permission_arr ) ) { ?> checked <?php } ?> value="pendingguider"> Pending Host
			                    	</td>
			                    	<td>
			                    		<input type="checkbox" <?php if( in_array( 'pendingguider/index', $permission_arr ) ) { ?> checked <?php } ?> name="pendingguider[]" id="pendingguider_index" class="indPLCheck0" value="pendingguider/index"> View
			                    		<input type="checkbox" <?php if( in_array( 'pendingguider/status', $permission_arr ) ) { ?> checked <?php } ?> name="pendingguider[]" id="pendingguider_status" class="indPLCheck0" value="pendingguider/status"> Approve Status 
			                    		<input type="checkbox" <?php if( in_array( 'pendingguider/delete', $permission_arr ) ) { ?> checked <?php } ?> name="pendingguider[]" id="pendingguider_delete" class="indPLCheck0" value="pendingguider/delete"> Delete
			                    	</td>
			                    </tr>
			                    <tr>
			                    	<td>
			                    		<input type="checkbox" name="guider[]" id="guider" class="allPLCheck" <?php if( in_array( 'guider', $permission_arr ) ) { ?> checked <?php } ?> value="guider"> Host
			                    	</td>
			                    	<td>
			                    		<input type="checkbox" <?php if( in_array( 'guider/index', $permission_arr ) ) { ?> checked <?php } ?> name="guider[]" id="guider_index" class="indPLCheck" value="guider/index"> View
			                    		<input type="checkbox" <?php if( in_array( 'guider/add', $permission_arr ) ) { ?> checked <?php } ?> name="guider[]" id="guider_add" class="indPLCheck" value="guider/add"> Add
			                    		<input type="checkbox" <?php if( in_array( 'guider/status', $permission_arr ) ) { ?> checked <?php } ?> name="guider[]" id="guider_status" class="indPLCheck" value="guider/status"> Status 
			                    		<input type="checkbox" <?php if( in_array( 'guider/deleteGuider', $permission_arr ) ) { ?> checked <?php } ?> name="guider[]" id="guider_delete" class="indPLCheck" value="guider/deleteGuider"> Delete

			                    	</td>
			                    </tr>
			                    <tr>
			                    	<td>
			                    		<input type="checkbox" name="traveller[]" id="traveller" class="allPLCheck2" <?php if( in_array( 'traveller', $permission_arr ) ) { ?> checked <?php } ?> value="traveller"> Guest
			                    	</td>
			                    	<td>
			                    		<input type="checkbox" <?php if( in_array( 'traveller/index', $permission_arr ) ) { ?> checked <?php } ?> name="traveller[]" id="traveller_index" class="indPLCheck2" value="traveller/index"> View
			                    		<input type="checkbox" <?php if( in_array( 'traveller/status', $permission_arr ) ) { ?> checked <?php } ?> name="traveller[]" id="traveller_status" class="indPLCheck2" value="traveller/status"> Status 
			                    		<input type="checkbox" <?php if( in_array( 'traveller/deleteTraveller', $permission_arr ) ) { ?> checked <?php } ?> name="traveller[]" id="traveller_delete" class="indPLCheck2" value="traveller/deleteTraveller"> Delete
			                    	</td>
			                    </tr>
			                    <tr>
			                    	<td>
			                    		<input type="checkbox" name="newsletter[]" id="newsletter" class="allPLCheck3" <?php if( in_array( 'newsletter', $permission_arr ) ) { ?> checked <?php } ?> value="newsletter"> Newsletter
			                    	</td>
			                    	<td>
			                    		<input type="checkbox" class="indPLCheck3" <?php if( in_array( 'newsletter/index', $permission_arr ) ) { ?> checked <?php } ?> name="newsletter[]" id="newsletter_index" value="newsletter/index"> View
			                    		<input type="checkbox" class="indPLCheck4" <?php if( in_array( 'newsletter/add', $permission_arr ) ) { ?> checked <?php } ?> name="newsletter[]" id="newsletter_add" value="newsletter/add"> Add
			                    		<input type="checkbox" class="indPLCheck3" <?php if( in_array( 'newsletter/delete_newsletter', $permission_arr ) ) { ?> checked <?php } ?> name="newsletter[]" id="newsletter_delete" value="newsletter/delete_newsletter"> Delete
			                    	</td>
			                    </tr>
			                    <tr>
			                    	<td>
			                    		<input type="checkbox" name="category[]" id="category" class="allPLCheck4" <?php if( in_array( 'category', $permission_arr ) ) { ?> checked <?php } ?> value="category"> Category
			                    	</td>
			                    	<td>
			                    		<input type="checkbox" class="indPLCheck4" <?php if( in_array( 'category/index', $permission_arr ) ) { ?> checked <?php } ?> name="category[]" id="category_index" value="category/index"> View
			                    		<input type="checkbox" class="indPLCheck4" <?php if( in_array( 'category/add', $permission_arr ) ) { ?> checked <?php } ?> name="category[]" id="category_add" value="category/add"> Add
			                    		<input type="checkbox" class="indPLCheck4" <?php if( in_array( 'category/delete', $permission_arr ) ) { ?> checked <?php } ?> name="category[]" id="category_delete" value="category/delete"> Delete
			                    	</td>
			                    </tr>
			                    <tr>
			                    	<td>
			                    		<input type="checkbox" name="journey[]" id="journey" class="allPLCheck5" <?php if( in_array( 'journey', $permission_arr ) ) { ?> checked <?php } ?> value="journey"> Journeys
			                    	</td>
			                    	<td>
			                    		<input type="checkbox" class="indPLCheck5" <?php if( in_array( 'journey/index', $permission_arr ) ) { ?> checked <?php } ?> name="journey[]" id="journey_index" value="journey/index"> View
			                    		<input type="checkbox" class="indPLCheck5" <?php if( in_array( 'journey/add', $permission_arr ) ) { ?> checked <?php } ?> name="journey[]" id="journey_add" value="journey/add"> Add
			                    		<input type="checkbox" class="indPLCheck5" <?php if( in_array( 'journey/delete', $permission_arr ) ) { ?> checked <?php } ?> name="journey[]" id="journey_delete" value="journey/delete"> Delete
			                    	</td>
			                    </tr>
			                    <tr>
			                    	<td>
			                    		<input type="checkbox" name="role[]" id="role" class="allPLCheck6" <?php if( in_array( 'role', $permission_arr ) ) { ?> checked <?php } ?> value="role"> Role
			                    	</td>
			                    	<td>
			                    		<input type="checkbox" class="indPLCheck6" <?php if( in_array( 'role/add', $permission_arr ) ) { ?> checked <?php } ?> name="role[]" id="role_add" value="role/add"> Add
			                    		<input type="checkbox" class="indPLCheck6" <?php if( in_array( 'role/delete', $permission_arr ) ) { ?> checked <?php } ?> name="role[]" id="role_delete" value="role/delete"> Delete
			                    		<input type="checkbox" class="indPLCheck6" <?php if( in_array( 'role/permission', $permission_arr ) ) { ?> checked <?php } ?> name="role[]" id="role_permission" value="role/permission"> Add Permission
			                    	</td>
			                    </tr>
			                    <tr>
			                    	<td>
			                    		<input type="checkbox" name="cms[]" id="cms" class="allPLCheck7" <?php if( in_array( 'cms', $permission_arr ) ) { ?> checked <?php } ?> value="cms"> CMS
			                    	</td>
			                    	<td>
			                    		<input type="checkbox" class="indPLCheck7" <?php if( in_array( 'cms/add', $permission_arr ) ) { ?> checked <?php } ?> name="cms[]" id="cms_add" value="cms/add"> Add
			                    		<input type="checkbox" class="indPLCheck7" <?php if( in_array( 'cms/delete', $permission_arr ) ) { ?> checked <?php } ?> name="cms[]" id="cms_delete" value="cms/delete"> Delete
			                    		<input type="checkbox" class="indPLCheck7" <?php if( in_array( 'cms/permission', $permission_arr ) ) { ?> checked <?php } ?> name="cms[]" id="cms_permission" value="cms/permission"> Add Permission
			                    	</td>
			                    </tr>
		                	</tbody>
                        </table>
                      </div>
                      <div style="padding-top: 10px;">
                      	<input type="hidden" name="role_id" id="role_id" value="<?=$role_id;?>">
                    	<button type="button" onClick="return add_permission();" class="btn btn-success btn-sm"><i class="fa fa-check"></i> Submit</button>
		    			<a class="btn btn-danger btn-sm" href="<?php echo base_url(); ?>settings">Cancel</a>
		    		  </div>	
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

	$( '.allPLCheck0' ).on( 'click', function() {
		if( $( this ).is( ':checked' ) ) {
			$( '.indPLCheck0' ).prop( "checked", true );
		} else {
			$( '.indPLCheck0' ).prop( 'checked', false );
		}
	});
	$( '.allPLCheck' ).on( 'click', function() {
		if( $( this ).is( ':checked' ) ) {
			$( '.indPLCheck' ).prop( "checked", true );
		} else {
			$( '.indPLCheck' ).prop( 'checked', false );
		}
	});
	$( '.allPLCheck1' ).on( 'click', function() {
		if( $( this ).is( ':checked' ) ) {
			$( '.indPLCheck1' ).prop( "checked", true );
		} else {
			$( '.indPLCheck1' ).prop( 'checked', false );
		}
	});
	$( '.allPLCheck2' ).on( 'click', function() {
		if( $( this ).is( ':checked' ) ) {
			$( '.indPLCheck2' ).prop( "checked", true );
		} else {
			$( '.indPLCheck2' ).prop( 'checked', false );
		}
	});
	$( '.allPLCheck3' ).on( 'click', function() {
		if( $( this ).is( ':checked' ) ) {
			$( '.indPLCheck3' ).prop( "checked", true );
		} else {
			$( '.indPLCheck3' ).prop( 'checked', false );
		}
	});
	$( '.allPLCheck4' ).on( 'click', function() {
		if( $( this ).is( ':checked' ) ) {
			$( '.indPLCheck4' ).prop( "checked", true );
		} else {
			$( '.indPLCheck4' ).prop( 'checked', false );
		}
	});
	$( '.allPLCheck5' ).on( 'click', function() {
		if( $( this ).is( ':checked' ) ) {
			$( '.indPLCheck5' ).prop( "checked", true );
		} else {
			$( '.indPLCheck5' ).prop( 'checked', false );
		}
	});
	$( '.allPLCheck6' ).on( 'click', function() {
		if( $( this ).is( ':checked' ) ) {
			$( '.indPLCheck6' ).prop( "checked", true );
		} else {
			$( '.indPLCheck6' ).prop( 'checked', false );
		}
	});
	$( '.allPLCheck7' ).on( 'click', function() {
		if( $( this ).is( ':checked' ) ) {
			$( '.indPLCheck7' ).prop( "checked", true );
		} else {
			$( '.indPLCheck7' ).prop( 'checked', false );
		}
	});
   function add_permission() {

        var form_name            = $( '.add-permission-form' ).find( 'input, select, button, textarea' );
        var permission_form_data = form_name.serialize();
        $.ajax({
            type        : 'POST',
            url         : adminurl + 'settings/add_permission',
            data        : permission_form_data,
            async       : false,
            dataType    : 'json',
            beforeSend: function () {
            },    
            success     : function( msg ) {
                if( msg == 1 ) {
                    toastr.success(  'Permission added successfully' );
                } else if ( msg == 2 ) {
                    toastr.error(  'Already added for permission' );
                } else {
                    toastr.error(  'Error while adding Permission' );
                }
            }
        });
        return false;
    }
</script>