<?php if(!defined("ChurchYard_Execute")) die("Access Denied.");

if(isset($_POST["Submitted"]))
{

  $Db = new Database();

  $UserID = "'0'";
  $PageName = $Db -> Filter($_POST["PageName"]);
  $URL = $Db -> Filter($_POST["URL"]);
  $Content = "'No content has been added to this page yet'";

  $SQL = "INSERT INTO Pages (UserID, PageName, URL, Content) VALUES ($UserID, $PageName, $URL, $Content)";
  
  $Db -> Query($SQL)or die($Db -> Error());
  


  die("Done.");
}


include("templates/dashboard/header.php");

?><h1 class="page-header">New Grave</h1>

<div class="row">
    <div class="col-md-12">

      <form method="post" action="">
        <fieldset class="form-horizontal col-md-6">


        <input type="hidden" name="Submitted" value="true">

        <!-- Number input-->
        <div class="form-group">
          <label class="control-label" for="PageName">Grave ID</label>  
            <input id="GraveID" name="GraveID" type="number" placeholder="01" class="form-control input-md" required="">
        </div>

        <!-- Text input-->
        <div class="form-group">
          <label class="control-label" for="PageName">Location Co-Ordinates</label>  
            <input id="Username" name="Username" type="text" placeholder="1, 4" class="form-control input-md" required="">
        </div>

        <div class="radio">
          <label>
            <input type="radio" name="GraveType" id="Gender" value="m">
            Upright Headstone
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="GraveType" id="Gender" value="f">
            Flat Headstone
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="GraveType" id="Gender" value="f">
            Curbed Headstone
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="GraveType" id="Gender" value="f">
            Monument
          </label>
        </div>
       
        <br>

        <!-- Button -->
        <div class="form-group">
            <button type="submit" class="btn btn-danger"><i class="fa fa-pencil"></i> Create Grave</button>
        </div>

        </fieldset>
      </form>

    </div><!-- /.col -->
</div><!-- /.row -->

<?php include("templates/dashboard/footer.php");