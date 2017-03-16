<?php if(!defined("ChurchYard_Execute")) die("Access Denied.");

if(isset($_POST["Submitted"]))
// check if form is submitted
{
    
  $PageName = trim($_POST["PageName"]);
  $PageURL = trim(strtolower($_POST["PageURL"]));
  // Get values submitted by form

  $Validated = true;
  // assume data is valid until otherwise

  $BannedURLs = array("login", "logout", "database", "admin");
  // keywords already used in URLs by the main application.

  if(strlen($PageName) < 3)
  // check length
  {
    $PageNameError = "Page name is too short. ";
    $Validated = false;
  }

  if(strlen($PageName) > 60)
  // check length
  {
    $PageNameError = "Page name is too long. ";
    $Validated = false;
  }

  if(!preg_match("/^[\w-.!()@+&;:, ]*$/", $PageName))
  // check character types
  {
    $PageNameError .= "Standard keyboard characters only.";
    $Validated = false;
  }
  // done validating PageName

  if(strlen($PageURL) < 3)
  // check length
  {
    $PageURLError = "Page URL is too short. ";
    $Validated = false;
  }

  if(strlen($PageURL) > 60)
  // check length
  {
    $PageURLError = "Page URL is too long. ";
    $Validated = false;
  }

  if(!preg_match("/^[\w-]*$/", $PageURL))
  // check character types
  {
    $PageURLError .= "Alphanumeric, dashes and underscores only. ";
    $Validated = false;
  }

  if(in_array($PageURL, $BannedURLs))
  // check if URL is application page
  {
    $PageURLError .= "This URL is not available. ";
    $Validated = false;
  }

  if(1 == 2)
  // check if already exists in database.
  {
    $PageURLError .= "This URL is already in use on another page. ";
    $Validated = false;
  }
  // done validating PageURL

  if($Validated == true)
  {
    $Db = new Database();
    // create database connection

    $PageURL = strtolower($PageURL);
    // lowercase only for PageURL

    $PageContent = "'No content has been added to this page yet'";
    $UserID = "'0'";

    $PageName = $Db->Filter($_POST["PageName"]);
    $PageURL = $Db->Filter($PageURL);
    // prevent injection

    $SQL = "INSERT INTO Pages (UserID, PageName, URL, Content) VALUES ($UserID, $PageName, $PageURL, $PageContent)";
    // prepare query

    if(!$Db->Query($SQL))
    {
      $Db->Error();
      // If error, output error message
    }
    else
    {
       Redirect("admin/pages");
    }
  }
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
            <input id="PageName" name="PageName" type="text" placeholder="My First Page" class="form-control input-md" required="true" value="<?php echo $PageName; ?>">
            <span class="help-block" style="color:red;"><?php echo $PageNameError; ?></span>  
            <span class="help-block">Human Readable, appears in the title of your site</span>  
        </div>

        <!-- Text input-->
        <div class="form-group">
          <label class="control-label" for="URL">Page URL</label>  
          <div class="input-group">
            <span class="input-group-addon" id="basic-addon1"><?php echo $GLOBALS["Config"]->URL->Domain; ?>/</span>
            <input id="PageURL" name="PageURL" type="text" placeholder="my-first-page" class="form-control input-md" required="true" value="<?php echo $PageURL; ?>">
          </div>
          <span class="help-block" style="color:red;"><?php echo $PageURLError; ?></span>  
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