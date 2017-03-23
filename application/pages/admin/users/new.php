<?php if(!defined("ChurchYard_Execute")) die("Access Denied.");

if(isset($_POST["Submitted"]))
{


  $Name = trim($_POST['Name']);
  $Username = trim($_POST['Username']);
  $EmailAddress = trim($_POST['EmailAddress']);
  $Password = trim($_POST['Password']);

  $Validated = false;


  if(strlen($Name) < 2 || strlen($Name) > 30)
  {
    $NameError = "Name must be 2-30 characters in length";
    $Validated = false;
  }

  if(!preg_match("/^[\w- ]*$/", $Name))
  {
    $NameError = "Name can only contain alphanumeric characters.";
    $Validated = false;
  }

  if(strlen($Username) < 2 || strlen($Username) > 20)
  {
    $UsernameError = "Username must be 2-20 characters in length";
    $Validated = false;
  }

  if(!preg_match("/^[\w.-]*$/", $Username))
  {
    $UsernameError = "Username can only contain alphanumeric, dashes, underscores and periods.";
    $Validated = false;
  }

  if(strlen($Name) < 8 || strlen($Name) > 50)
  {
    $PasswordError = "Password must be 8-50 characters in length";
    $Validated = false;
  }

  if(!is_email_address($EmailAddress))
  {
    $EmailAddressError = "Invalid email format.";
    $Validated = false;
  }
  

  if(!preg_match("/^[\w.-]*$/", $Password))
  {
    $PasswordError = "Username can only contain alphanumeric, dashes, underscores and periods.";
    $Validated = false;
  }


  if($Validated)
  {

    $Salt = RandomToken();

    $Password = md5(str);
    $SQL = "INSERT INTO Users (UserID, Name, Username, EmailAddress, Password, Salt, IsAdmin) VALUES (DEFAULT, $Name, $Username, $EmailAddress, $Password, $Salt, $IsAdmin";

  }

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
            <input id="Name" name="Name" type="text" placeholder="John Doe" class="form-control input-md" required="true" value="<?php echo $Name; ?>">
            <span class="help" style="color:red;"><?php echo $NameError; ?></span>
        </div>

        <!-- Text input-->
        <div class="form-group">
          <label class="control-label" for="PageName">Username</label>  
            <input id="Username" name="Username" type="text" placeholder="john.doe" class="form-control input-md" required="true" value="<?php echo $Username; ?>">
            <span class="help" style="color:red;"><?php echo $UsernameError; ?></span>
            <span class="help-block">Used to sign in</span>  
        </div>

        <!-- Email input-->
        <div class="form-group">
          <label class="control-label" for="PageName">Email Address</label>  
            <input id="EmailAddress" name="EmailAddress" type="email" placeholder="john.doe@example.com" class="form-control input-md"  required="true" value="<?php echo $EmailAddress; ?>">
            <span class="help" style="color:red;"><?php echo $EmailAddressError; ?></span>
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
          <span class="help" style="color:red;"><?php echo $PasswordError; ?></span>
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