<?php if(!defined("ChurchYard_Execute")) die("Access Denied.");
	?></div>
  </div>
</div>

<!-- Developer Modal -->
<div class="modal fade" id="DevModal" tabindex="-1" role="dialog" aria-labelledby="DevButton">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="DevModalLabel"><i class="fa fa-code"></i> Developer</h4>
      </div>
      <div class="modal-body">
        
      		<?php echo '<pre>'.print_r(GetCurrentPath(), true).'</pre>'; ?>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Done</button>
      </div>
    </div>
  </div>
</div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php echo GetResourceURL("application/js/jquery.min.js"); ?>"></script>
    <script src="<?php echo GetResourceURL("application/js/bootstrap.min.js"); ?>"></script>
  </body>
</html>