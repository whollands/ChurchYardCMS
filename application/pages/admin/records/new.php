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

?><h1 class="page-header">New Record</h1>

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
          <label class="control-label" for="PageName">First Name</label>  
            <input id="FirstName" name="FirstName" type="text" placeholder="John" class="form-control input-md" required="">
        </div>

        <!-- Text input-->
        <div class="form-group">
          <label class="control-label" for="PageName">Last Name(s)</label>  
            <input id="Username" name="Username" type="text" placeholder="Doe" class="form-control input-md" required="">
        </div>

        <!-- Date input-->
        <div class="form-group">
          <label class="control-label" for="PageName">Date Of Birth (optional)</label>  
            <input id="DateOfBirth" name="DateOfBirth" type="text" placeholder="Doe" class="form-control input-md" required="">
        </div>

        <!-- Date input-->
        <div class="form-group">
          <label class="control-label" for="PageName">Date Of Death (optional)</label>  
            <input id="DateOfDeath" name="DateOfDeath" type="text" placeholder="Doe" class="form-control input-md" required="">
        </div>

        <div class="radio">
          <label>
            <input type="radio" name="Gender" id="Gender" value="m">
            Male
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="Gender" id="Gender" value="f">
            Female
          </label>
        </div>
       


        <br>

        <!-- Button -->
        <div class="form-group">
            <button type="submit" class="btn btn-danger"><i class="fa fa-pencil"></i> Create Record</button>
        </div>

        </fieldset>
      </form>

    </div><!-- /.col -->
</div><!-- /.row -->

<?php include("templates/dashboard/footer.php");