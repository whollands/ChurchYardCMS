<?php if(!defined("ChurchYard_Execute")) die("Access Denied.");

include("templates/dashboard/header.php");


?><h1 class="page-header">Settings</h1>

<div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h2 class="panel-title">General</h2>
        </div>
        <div class="panel-body">

        <?php echo AlertInfo("Settings currently cannot be changed"); ?>
        
          <div class="col-md-4">

              <div class="form-group">
                <label class="control-label" for="">Site Name</label>
                <input type="text" class="form-control" name="" value="St. Peter's Church Rendcomb">
                <span class="help-block">Appears in HTML title tag and at top of admin interface.</span>
              </div>

              <div class="form-group">
                <label class="control-label" for="">Database Salt</label>
                <input type="text" class="form-control" name="" value="<?php echo $GLOBALS["Config"]->MasterSalt; ?>" disabled>
                <span class="help-block">Used to protect user's passwords</span>
              </div>

              <div class="form-group">
                <label class="control-label" for="checkboxes">Options</label>

                <div class="checkbox">
                  <label for="checkboxes-0">
                    <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1">
                    Enable Clean URL's
                  </label>
                </div>
                <div class="checkbox">
                  <label for="checkboxes-1">
                    <input type="checkbox" name="checkboxes" id="checkboxes-1" value="2">
                    Enable debug
                  </label>
                </div>
              </div><!-- /.form-group -->


              <button type="submit" class="btn btn-danger">Save Changes</button>

          </div><!-- /.col-md-4 -->

        </div>
      </div><!-- /.panel-->
    </div><!-- /.col -->
</div><!-- /.row -->

<?php include("templates/dashboard/footer.php");