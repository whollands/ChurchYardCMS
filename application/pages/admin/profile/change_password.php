<?php if(!defined("ChurchYard_Execute")) die("Access Denied.");



$PasswordErrors = "";

if(isset($_POST['Submitted']))
{

  $NewPassword = $_POST['NewPassword'];
  $UserID = User::$UserID;

  $Validated = true;

  if($NewPassword != $_POST['ConfirmNewPassword'])
  {
       $PasswordErrors .= "<li>Passwords did not match</li>";
       $Validated = false;
  }

  if(strlen($NewPassword) < 8)
  {
       $PasswordErrors .= "<li>Not long enough</li>";
       $Validated = false;
  }

  if(!preg_match('/[A-Z]/', $NewPassword))
  {
    $PasswordErrors .= "<li>Password must contain at least one UPPERCASE letter</li>";
    $Validated = false;
  }

  if(!preg_match('/[a-z]/', $NewPassword))
  {
    $PasswordErrors .= "<li>Password must contain at least one LOWERCASE letter</li>";
    $Validated = false;
  }

  if(!preg_match('/\d/', $NewPassword))
  {
    $PasswordErrors .= "<li>Password must contain at least one number</li>";
    $Validated = false;
  }

  if(!preg_match('/[^a-zA-Z\d]/', $NewPassword))
  {
    $PasswordErrors .= "<li>Password must contain at least one special character</li>";
    $Validated = false;
  }

  if($Validated == true)
  {
     User::ChangePassword($UserID, $NewPassword);
     die('Changed password.');
  }

}

if($PasswordErrors != null)
{
  $PasswordErrors = AlertWarning("<ul>There are problems with your password: " . $PasswordErrors . "</ul>");
}

include("templates/dashboard/header.php");


?><h1 class="page-header">Change Password</h1>

<?php echo $PasswordErrors; ?>

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

         <!-- 
          <div class="form-group">
            <label class=" control-label" for="CurrentPassword">Current Password</label>
              <input id="CurrentPassword" name="CurrentPassword" type="password" placeholder="" class="form-control input-md" required="">
          </div> -->

          <!-- Button -->
          <div class="form-group">
              <button type="submit" class="btn btn-danger"><i class="fa fa-key"></i> Change Password</button>
          </div>

        </fieldset>
      </form>
            
    </div><!-- /.col -->
</div><!-- /.row -->

<?php include("templates/dashboard/footer.php");