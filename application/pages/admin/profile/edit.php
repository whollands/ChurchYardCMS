<?php if(!defined("ChurchYard_Execute")) die("Access Denied.");

include("templates/dashboard/header.php");


$db = new Database();

$UserID = $db -> Filter('0');

$Data = $db -> Select("SELECT Name, Username, EmailAddress FROM Users WHERE UserID='0'")or die($db -> Error());

$Name = $Data[0]['Name'];
$Username = $Data[0]['Username'];
$EmailAddress = $Data[0]['EmailAddress'];



?><h1 class="page-header">Edit Profile</h1>

<div class="row">
    <div class="col-md-12">

        <form method="post" action="">
          <fieldset class="form-horizontal col-md-6">

          <!-- Text input-->
          <div class="form-group">
            <label class="control-label" for="Name">Full Name</label>  
            <input id="Name" name="Name" type="text" value="<?php echo $Name; ?>" class="form-control input-md" required="true">
          </div>

          <!-- Text input-->
          <div class="form-group">
            <label class="control-label" for="Username">Username</label>  
            <input id="Username" name="Username" type="text" value="<?php echo $Username; ?>" class="form-control input-md" required="true">
            <span class="help-block"><span style="color:green;">Current</span></span>  
          </div>

          <!-- Email input-->
          <div class="form-group">
            <label class="control-label" for="Email">Email Address</label>  
            <input id="Email" name="Email" type="email" value="<?php echo $EmailAddress; ?>" class="form-control input-md" required="true">
            <span class="help-block">For notifications and password resets</span>  
          </div>

          <!-- Password input-->
          <div class="form-group">
            <label class="control-label" for="Password">Current Password</label>
              <input id="Password" name="Password" type="password" class="form-control input-md" required="true">
              <span class="help-block">Enter current password to save</span>
          </div>

          <!-- Button -->
          <div class="form-group">
            <label class="control-label" for=""></label>
              <button type="submit" class="btn btn-danger"><i class="fa fa-pencil"></i> Save Profile</button>
          </div>

          </fieldset>
        </form>

<?php include("templates/dashboard/footer.php");