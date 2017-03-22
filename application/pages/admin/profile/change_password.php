<?php if(!defined("ChurchYard_Execute")) die("Access Denied.");

include("templates/dashboard/header.php");


$InfoMessage = "";

if(isset($_POST['Submitted']))
{

  $NewPassword = $_POST['NewPassword'];

  if($NewPassword != $_POST['ConfirmNewPassword'])
  {
      Server::ErrorMessage("Passwords did not match");
  }

  if(strlen($NewPassword) < 8)
  {
      Server::ErrorMessage("Not long enough");
  }

  if(!preg_match('/[A-Z]/', $NewPassword))
  {
    $InfoMessage .= AlertWarning("Password must contain at least one UPPERCASE letter");
  }

  if(!preg_match('/[a-z]/', $NewPassword))
  {
    $InfoMessage .= AlertWarning("Password must contain at least one LOWERCASE letter");
  }

  if(!preg_match('/\d/', $NewPassword))
  {
    $InfoMessage .= AlertWarning("Password must contain at least one number");
  }

  if(!preg_match('/[^a-zA-Z\d]/', $NewPassword))
  {
    $InfoMessage .= AlertWarning("Password must contain at least one special character");
  }

}


?><h1 class="page-header">Change Password</h1>

<?php echo $InfoMessage; ?>

<div class="row">
    <div class="col-md-12">

      <form method="post" action="">
        <fieldset class="col-md-6 form-horizontal">

        <input type="hidden" name="Submitted" value="true"/>

          <!-- Password input-->
          <div class="form-group">
            <label class=" control-label" for="NewPassword">Enter New Password</label>
              <input id="NewPassword" name="NewPassword" type="password" placeholder="" class="form-control input-md" required="">
          </div>

          <!-- Password input-->
          <div class="form-group">
            <label class="control-label" for="ConfirmNewPassword">Confirm New Password</label>
              <input id="ConfirmNewPassword" name="ConfirmNewPassword" type="password" placeholder="" class="form-control input-md" required="">
          </div>

          <!-- Password input-->
          <div class="form-group">
            <label class=" control-label" for="CurrentPassword">Current Password</label>
              <input id="CurrentPassword" name="CurrentPassword" type="password" placeholder="" class="form-control input-md" required="">
          </div>

          <!-- Button -->
          <div class="form-group">
              <button type="submit" class="btn btn-danger"><i class="fa fa-key"></i> Change Password</button>
          </div>

        </fieldset>
      </form>
            
    </div><!-- /.col -->
</div><!-- /.row -->

<?php include("templates/dashboard/footer.php");