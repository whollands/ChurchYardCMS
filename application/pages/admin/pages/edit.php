<?php if(!defined("ChurchYard_Execute")) die("Access Denied.");

if(isset($_POST["Submitted"]))
{

  $PageID = $_POST["PageID"];
  $PageName = $_POST["PageName"];
  $PageURL = $_POST["PageURL"];
  $PageContent = $_POST["PageContent"];
  // get values submitted

  $AllowedTags = "<p><strong><pre><h1><h2><h3><h4><i><a><img><s><blockquote><li><ul><table><br><hr>";
  $PageContent = strip_tags($PageContent, $AllowedTags);
  // remove banned html

  
  // create new connection

  $PageName = Database::Filter($PageName);
  $PageURL = Database::Filter($PageURL);
  $PageContent = Database::Filter($PageContent);
  // make safe to prevent injection

  $SQL = "UPDATE Pages SET PageName=$PageName, URL=$PageURL, Content=$PageContent, LastEdited=CURRENT_TIMESTAMP WHERE PageID='$PageID'";

  if(!Database::Query($SQL))
  {
    Server::ErrorMessage("Failed to save document.");
  }
  else
  {
    Server::Redirect("admin/pages/edit/" . $PageID . "?saved");
  }

  unset($Db);
  // destory object
}


// create new connection

$PageID = Database::Filter(GetPathPart(3));
// get page being edited, and filter it to prevent injection

$Data = Database::Select("SELECT PageID, PageName, URL, Content FROM Pages WHERE PageID=$PageID")or Server::ErrorMessage($Db->Error());
// query database

if(count($Data) == 1)
{
  $PageID = $Data[0]["PageID"];
  $PageName = $Data[0]["PageName"];
  $PageURL = $Data[0]["URL"];
  $PageContent = $Data[0]["Content"];
}
else
{
  echo "Page not found.";
  exit;
}

unset($Db);
// destory object

include("templates/dashboard/header.php");

?><h1 class="page-header">Editor</h1>


<?php

if($_SERVER["QUERY_STRING"] == "saved")
{
  AlertSuccess("Changes saved");
}

?>

<div class="row">
    <div class="col-md-12">

      <form method="post" action="">
      
        <input type="hidden" name="Submitted" value="true">

        <input type="hidden" name="PageID" value="<?php echo $PageID; ?>">


        <script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>

        <textarea name="PageContent" id="PageContent">      
          <?php echo $PageContent; ?>
        </textarea>

        <script type="text/javascript">
            CKEDITOR.replace( 'PageContent' );
            CKEDITOR.config.height = 300;
        </script>

        <br>

        <button type="submit" class="btn btn-danger"><i class="fa fa-check"></i> Save Changes</button>

         <button type="button" class="btn btn-default" data-toggle="modal" data-target="#page-settings"><i class="fa fa-gear"></i></button>

        <!-- Modal -->
        <div class="modal fade" id="page-settings" tabindex="-1" role="dialog" aria-labelledby="page-settings">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Page Settings</h4>
              </div>
              <div class="modal-body">

                <!-- Text input-->
                <div class="form-group">
                    <input id="PageName" name="PageName" type="text" value="<?php echo $PageName; ?>" class="form-control input-md" required="">
                  <span class="help-block">Human Friendly, appears in the title of your site</span>  
                </div>

                <!-- Text input-->
                <div class="form-group">
                  <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1"><?php echo $GLOBALS["Config"]->URL->Domain; ?>/</span>
                    <input id="PageURL" name="PageURL" type="text" value="<?php echo $PageURL; ?>" class="form-control input-md" required="">
                  </div>
                  <span class="help-block">The location of the page on your site</span>  
                </div>
               
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger"><i class="fa fa-check"></i> Save changes</button>
              </div>
            </div>
          </div>
        </div>

      </form>

    </div><!-- /.col -->
</div><!-- /.row -->

<?php include("templates/dashboard/footer.php");