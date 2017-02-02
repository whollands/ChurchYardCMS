<?php if(!defined("ChurchYard_Execute")) die("Access Denied.");

if(isset($_POST["Submitted"]))
{

  $db = new Database();

  $UserID = "'0'";
  $PageName = $db -> Filter($_POST["PageName"]);
  $URL = $db -> Filter($_POST["URL"]);
  $Content = "'No content has been added to this page yet'";

  $SQL = "INSERT INTO Pages (UserID, PageName, URL, Content) VALUES ($UserID, $PageName, $URL, $Content)";
  
  $db -> Query($SQL)or die($db -> Error());
  


  die("Done.");
}


include("templates/dashboard/header.php");

?><h1 class="page-header">Create Page</h1>

<div class="row">
    <div class="col-md-12">

      <form method="post" action="">
        <fieldset class="col-md-6 form-horizontal">


        <input type="hidden" name="Submitted" value="true">

        <!-- Text input-->
        <div class="form-group">
          <label class="control-label" for="PageName">Page Name</label>  
            <input id="PageName" name="PageName" type="text" placeholder="My First Page" class="form-control input-md" required="">
            <span class="help-block">Human Friendly, appears in the title of your site</span>  
        </div>

        <!-- Text input-->
        <div class="form-group">
          <label class="control-label" for="URL">Page URL</label>  
          <div class="input-group">
            <span class="input-group-addon" id="basic-addon1"><?php echo $GLOBALS["Config"]->URL->Domain; ?>/</span>
            <input id="URL" name="URL" type="text" placeholder="my-first-page" class="form-control input-md" required="">
          </div>
          <span class="help-block">The location of the page on your site</span>  
        </div>

        <!-- Button -->
        <div class="form-group">
            <button type="submit" class="btn btn-danger"><i class="fa fa-pencil"></i> Create Page</button>
        </div>

        </fieldset>
      </form>

    </div><!-- /.col -->
</div><!-- /.row -->

<?php include("templates/dashboard/footer.php");