<div class="row">
  <form novalidate="" id="update_user_form" role="form" method="post">
  <div class="col-md-6">
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" maxlength="10" class="form-control">
        <input type="hidden" name="user_id" id="user_id" value="<?=$user_id;?>">
        <input type="hidden" name="type" id="type" value="<?=$type;?>">
      </div>
  </div>
  <div class="col-md-6" style="padding-top: 25px;">
    <a href="javascript:;" class="btn btn-success btn-sm" onclick="return verify_password_info( '<?=$user_id;?>' );">Confirm Password</a>
  </div>
</form>
</div>