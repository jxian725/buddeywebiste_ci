<div class="row">
  <form novalidate="" id="update_guider_form" role="form" method="post">
  <div class="col-md-6">
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" class="form-control">
        <input type="hidden" name="guider_id" id="guider_id" value="<?=$guider_id;?>" class="form-control">
      </div>
  </div>
  <div class="col-md-6" style="padding-top: 25px;">
    <a href="javascript:;" class="btn btn-success btn-sm" onclick="return update_password_info( '<?=$guider_id;?>' );">Confirm Password</a>
  </div>
</form>
</div>