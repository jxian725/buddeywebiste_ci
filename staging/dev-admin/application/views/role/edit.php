<div class="form-group">
    <label class="col-md-2 control-label" for="role">Role Name <span class="text-danger">*</span></label>
    <div class="col-md-8">
        <input type="text" required="" placeholder="Enter Role Name" value="<?=$role_info->role;?>" id="role" class="form-control" name="role">
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 control-label" for="role_code">Role Code <span class="text-danger">*</span></label>
    <div class="col-md-8">
        <input type="text" required="" placeholder="Enter Role Code" value="<?=$role_info->role_code;?>" id="role_code" class="form-control" name="role_code">
    </div>
</div>
<div class="col-md-offset-2 col-md-8 text-right">
    <input type="hidden" name="role_id" id="role_id" value="<?=$role_id;?>">
    <button class="btn btn-sm btn-warning" id="updaterolesubmit" onClick="return updatevalidaterole();" type="button">Update</button>
</div>
<script type="text/javascript">
    function updatevalidaterole() {
        var $btn  = $( '#updaterolesubmit' );
        $btn.button( 'Posting..' );
        var data    = $( "#role_form" ).find( "select, textarea, input" ).serialize();
        $.ajax({
            type    : 'POST',
            url     : adminurl + 'settings/updatevalidaterole',
            data    : data,
            success : function( msg ) {
                if( msg == 1 ) {
                    toastr.success('Role updated Successfully.');
                    location.reload();
                } else {
                    toastr.error(msg,'Error');
                }
                $btn.button( 'reset' );
            }
        });
        return false;
    }
</script>