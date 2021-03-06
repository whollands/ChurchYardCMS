<?php if(!defined("ChurchYard_Execute")) die("Access Denied.");

if(!User::$IsAdmin)
{
  Server::ErrorMessage("Access Denied: You do not have permission to access this page.");
}

$SiteName = $GLOBALS['Config']->SiteName;
$SupportEmail = $GLOBALS['Config']->SupportEmail;

$Domain = $GLOBALS['Config']->URL->Domain;

$EnableCleanURLs = $GLOBALS['Config']->URL->CleanURLs;
$EnableSSL = $GLOBALS['Config']->URL->SSL;
$EnableDebug = $GLOBALS['Config']->Dev->EnableDebug;


include("templates/dashboard/header.php");


?><h1 class="page-header">Settings</h1>

<div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h2 class="panel-title">General</h2>
        </div>
        <div class="panel-body">

        <?php echo AlertInfo("Settings have not been implemented in V.1.0 Alpha. To change these settings, modify the configuration file directly."); ?>
        
          <div class="col-md-4">

              <div class="form-group">
                <label class="control-label" for="">Site Name</label>
                <input type="text" class="form-control" name="SiteName" value="<?php echo $SiteName; ?>" disabled>
                <span class="help-block">Appears in HTML title tag and at top of admin interface.</span>
              </div>

              <div class="form-group">
                <label class="control-label" for="">Support Email</label>
                <input type="email" class="form-control" name="SupportEmail" value="<?php echo $SupportEmail; ?>" disabled>
                <span class="help-block">Shown to users when they need help.</span>
              </div>

              <div class="form-group">
                <label class="control-label" for="">Domain</label>
                <input type="email" class="form-control" name="Domain" value="<?php echo $Domain; ?>" disabled>
                <span class="help-block">Domain at which your site can be accessed on.</span>
              </div>

              <div class="form-group">
                <label class="control-label" for="checkboxes">Options</label>

                <div class="checkbox">
                  <label for="checkboxes-0">
                    <input type="checkbox" name="checkboxes" id="checkboxes-0" value="1"<?php if($EnableCleanURLs == true) { echo " checked"; } ?> disabled>
                    Enable Clean URL's
                  </label>
                </div>
                <div class="checkbox">
                  <label for="checkboxes-1">
                    <input type="checkbox" name="checkboxes" id="checkboxes-1" value="2"<?php if($EnableSSL == true) { echo " checked"; } ?> disabled>
                    Enable SSL
                  </label>
                </div>
                <div class="checkbox">
                  <label for="checkboxes-1">
                    <input type="checkbox" name="checkboxes" id="checkboxes-2" value="2"<?php if($EnableDebug == true) { echo " checked"; } ?> disabled>
                    Enable debug
                  </label>
                </div>
              </div><!-- /.form-group -->


              <button type="submit" class="btn btn-danger" disabled>Save Changes</button>

          </div><!-- /.col-md-4 -->

        </div>
      </div><!-- /.panel-->
    </div><!-- /.col -->
</div><!-- /.row -->

<?php include("templates/dashboard/footer.php");