<?php 
  $assetUrl  = $this->config->item( 'dir_url' ); 
  $site_name = $this->config->item( 'site_name' ); 
?>
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
<!-- Bootstrap 3.3.7 -->
<script src="<?=$this->config->item( 'dir_url' );?>plugins/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo $assetUrl; ?>js/toastr/toastr.min.js"></script> 
<!-- AdminLTE App -->
<script src="<?=$this->config->item( 'dir_url' );?>js/adminlte.min.js"></script>

<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!--<script src="<?=$this->config->item( 'dir_url' );?>js/pages/dashboard2.js"></script> -->
<!-- AdminLTE for demo purposes -->
<script src="<?=$this->config->item( 'dir_url' );?>js/demo.js"></script>
<script src="<?=$this->config->item( 'dir_url' );?>js/daterangepicker.js"></script>
<script src="<?php echo $this->config->item('dir_url'); ?>js/custom.js" type="text/javascript"></script>

<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h4 class="modal-title" id="modalTitle"></h4>
             </div>
             <div class="modal-body" id="modalBody"><div class="te"></div></div>
         </div>
         <!-- /.modal-content -->
      </div>
 <!-- /.modal-dialog -->
 </div>
</body>
</html>