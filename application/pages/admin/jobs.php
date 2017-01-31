<?php if(!defined("ChurchYard_Execute")) die("Access Denied.");

include("templates/dashboard/header.php");


?><h1 class="page-header">Jobs</h1>
<div class="row">
    <div class="col-md-4">
      <div class="panel panel-default">
          <div class="panel-heading">
              <h2 class="panel-title"><i class="fa fa-map"></i> Sitemap</h2>
          </div>
          <div class="panel-body">
              <p>Updates sitemap.xml file. This is used by search engines to index your site.</p>
              <a class="btn btn-danger" href="#" role="button">Update Sitemap.xml</a>
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
              <a class="btn btn-danger" href="#" role="button">Update Robots.txt</a>
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
              <a class="btn btn-danger" href="#" role="button">Clear Cache</a>
          </div>
      </div>
    </div>      
</div><!-- /.row -->

<?php include("templates/dashboard/footer.php");