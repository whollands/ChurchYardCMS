<?php if(!defined("ChurchYard_Execute")) die("Access Denied.");

if(!User::$IsAdmin)
{
  Server::ErrorMessage("Access Denied: You do not have permission to access this page.");
}

if(GetPathPart(2) != null)
{
  switch(GetPathPart(2))
  {
    default:
      Server::Error404();
    break;

    case "update_sitemap":
      Jobs::GenerateSitemap();
      Server::OutputMessage(AlertSuccess('Generated sitemap.xml file'));
      Server::Redirect('admin/jobs');
    break;

    case "update_robots":
      Jobs::GenerateRobots();
      Server::OutputMessage(AlertSuccess('Generated robots.txt file'));
      Server::Redirect('admin/jobs');
    break;

    case "clear_cache":
      Jobs::ClearCache();
      Server::OutputMessage(AlertSuccess('Cleared cache directory'));
      Server::Redirect('admin/jobs');
    break;
  }
}


include("templates/dashboard/header.php");


?><h1 class="page-header">Jobs</h1>

 <?php echo Server::DisplayMessage(); ?>

<div class="row">
    <div class="col-md-4">
      <div class="panel panel-default">
          <div class="panel-heading">
              <h2 class="panel-title"><i class="fa fa-map"></i> Sitemap</h2>
          </div>
          <div class="panel-body">
              <p>Updates sitemap.xml file. This is used by search engines to index your site.</p>
              <a class="btn btn-danger" href="<?php echo GetPageURL('admin/jobs/update_sitemap'); ?>" role="button">Update Sitemap.xml</a>
          </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="panel panel-default">
          <div class="panel-heading">
              <h2 class="panel-title"><i class="fa fa-bug"></i> Robots</h2>
          </div>
          <div class="panel-body">
              <p>Updates robots.txt file. This is used to control what search engines can access.</p>
              <a class="btn btn-danger" href="<?php echo GetPageURL('admin/jobs/update_robots'); ?>" role="button">Update Robots.txt</a>
          </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="panel panel-default">
          <div class="panel-heading">
              <h2 class="panel-title"><i class="fa fa-trash"></i> Cache</h2>
          </div>
          <div class="panel-body">
              <p>Clearing the cache may refresh outdated content that is not updating properly.</p>
              <a class="btn btn-danger" href="<?php echo GetPageURL('admin/jobs/clear_cache'); ?>" role="button" disabled>Clear Cache</a>
          </div>
      </div>
    </div>      
</div><!-- /.row -->

<?php include("templates/dashboard/footer.php");