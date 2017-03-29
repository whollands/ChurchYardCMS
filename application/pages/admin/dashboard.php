<?php if(!defined("ChurchYard_Execute")) die("Access Denied.");




include("templates/dashboard/header.php");

?><h1 class="page-header">Dashboard</h1>

 <?php echo Server::DisplayMessage(); ?>

<div class="row">

    <div class="col-md-8">
      <div class="panel panel-default">
          <div class="panel-heading">
              <h2 class="panel-title"><i class="fa fa-bar-chart"></i> Statistics</h2>
          </div>
          <div class="panel-body">
            <div class="row placeholders">
              <div class="col-md-3">
                <a href="<?php echo GetPageURL("admin/records"); ?>" title="Records">
                  <i class="fa fa-book fa-5x"></i>
                  <p>Records</p>
                </a>
                <br>
              </div>
              <div class="col-md-3">
                <a href="<?php echo GetPageURL("admin/media"); ?>" title="Media">
                  <i class="fa fa-folder fa-5x"></i>
                  <p>Media</p>
                </a>
              </div>
              <div class="col-md-3">
                <a href="<?php echo GetPageURL("admin/pages"); ?>" title="Pages">
                  <i class="fa fa-pencil fa-5x"></i>
                  <p>Editor</p>
                </a>
              </div>
              <div class="col-md-3">
                <a href="<?php echo GetPageURL("admin/users"); ?>" title="Users">
                  <i class="fa fa-users fa-5x"></i>
                  <p>Users</p>
                </a>
              </div>

            </div>
          </div>
      </div>
    </div>

        <div class="col-md-4">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h2 class="panel-title"><i class="fa fa-question-circle"></i> System Information</h2>
            </div>
            <div class="panel-body">
              <p><strong>Version: </strong><?php echo Version; ?></p>
              <p><strong>Domain: </strong><?php echo $GLOBALS["Config"]->URL->Domain; ?></p>
              <p><strong>SSL: </strong><?php if($GLOBALS["Config"]->URL->SSL) { echo "<span class=\"label label-success\">Enabled</span>"; } else { echo "<span class=\"label label-warning\">Disabled</span>"; } ?></p>
              <p><strong>Debug: </strong><?php if($GLOBALS["Config"]->Dev->EnableDebug) { echo "<span class=\"label label-danger\">Enabled</span>"; } else { echo "<span class=\"label label-success\">Disabled</span>"; } ?></p>
            </div>
          </div>
        </div>

</div>

<?php include("templates/dashboard/footer.php");