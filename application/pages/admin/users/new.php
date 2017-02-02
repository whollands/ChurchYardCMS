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

?><h1 class="page-header">New User</h1>

<div class="row">
    <div class="col-md-12">

      <form method="post" action="">
        <fieldset class="form-horizontal col-md-6">


        <input type="hidden" name="Submitted" value="true">

        <!-- Text input-->
        <div class="form-group">
          <label class="control-label" for="PageName">Full Name</label>  
            <input id="Name" name="Name" type="text" placeholder="John Doe" class="form-control input-md" required="">
        </div>

        <!-- Text input-->
        <div class="form-group">
          <label class="control-label" for="PageName">Username</label>  
            <input id="Username" name="Username" type="text" placeholder="john.doe" class="form-control input-md" required="">
            <span class="help-block">Used to sign in</span>  
        </div>

        <!-- Email input-->
        <div class="form-group">
          <label class="control-label" for="PageName">Email Address</label>  
            <input id="Email" name="Email" type="email" placeholder="john.doe@example.com" class="form-control input-md" required="">
        </div>

        <!-- password input-->
        <div class="form-group">
          <label class="control-label" for="PageName">New Password</label>  
          <div class="input-group">
            <input id="Password" name="Password" type="text" class="form-control input-md" required="">
            <span class="input-group-btn">
              <button class="btn btn-default" type="button"><i class="fa fa-random"></i> Random</button>
            </span>
          </div>
        </div>

        <div class="checkbox">
          <label>
            <input type="checkbox" name="Administrator" value="true"/> Administrator?
          </label>
        </div>

        <br>

        <!-- Button -->
        <div class="form-group">
            <button type="submit" class="btn btn-danger"><i class="fa fa-pencil"></i> Create User</button>
        </div>

        </fieldset>
      </form>

    </div><!-- /.col -->
</div><!-- /.row -->

<?php include("templates/dashboard/footer.php");