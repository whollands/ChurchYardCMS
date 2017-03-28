<?php if(!defined("ChurchYard_Execute")) die("Access Denied.");

if(isset($_POST['Submitted']))
{

  $Name = $_POST['Name'];
  $Username = $_POST['Username'];
  $EmailAddress = $_POST['EmailAddress'];


  $Validated = true;


  if(strlen($Name) < 4 || strlen($Name) > 30)
  {
    $NameError = "Name must be 4-30 characters in length.";
    $Validated = false;
  }
  // done validating name


  if(User::CheckUsernameExists($Username) == true && User::GetUsername() != $Username)
  {
    $UsernameError = "Username is already in use.";
    $Validated = false;
  }

  if(strlen($Username) < 4 || strlen($Username) > 20)
  {
    $UsernameError = "Username must be 4-20 characters in length.";
    $Validated = false;
  }

  if(!preg_match("/^[\w.]*$/", $Username))
  {
    $UsernameError .= " Username can only contain alphanumeric, underscores and periods.";
    $Validated = false;
  }
  // done validating username

  if(!is_email_address($EmailAddress, FILTER_VALIDATE_EMAIL))
  {
    $EmailAddressError = " Invalid email address.";
    $Validated = false;
  }
  // done validating email address


  if($Validated == true)
  {
    
    $UserID = User::GetUserID();
    
    $Name = Database::Filter($Name);
    $Username = Database::Filter($Username);
    $EmailAddress = Database::Filter($EmailAddress);

    $SQL = "UPDATE Users 
            SET Name=$Name, Username=$Username, EmailAddress=$EmailAddress 
            WHERE UserID=$UserID
            ";

    $Result = Database::Query($SQL);

    if($Result)
    {
      Server::Redirect('admin/profile/edit');
    }
    else
    {
      Server::ErrorMessage('Failed to save user profile.');
    }
  }

}
else
{

  
  $UserID = User::GetUserID();
  $UserID = Database::Filter($UserID);

  $Data = Database::Select("SELECT UserID, Name, Username, EmailAddress, IsAdmin FROM Users WHERE UserID='0'")or Server::ErrorMessage(Database::Error());

  $Name = $Data[0]['Name'];
  $Username = $Data[0]['Username'];
  $EmailAddress = $Data[0]['EmailAddress'];

  if($Data[0]['UserID'] == 0)
  {
    $AccountType = "Super User";
  }
  else if($Data[0]['IsAdmin'] == true)
  {
    $AccountType = "Administrator";
  }
  else
  {
    $AccountType = "Standard User";
  }

}



include("templates/dashboard/header.php");


?><h1 class="page-header">Edit Profile</h1>

<div class="row">
    <div class="col-md-12">

        <form method="post" action="">

          <input type="hidden" name="Submitted" value="true"/>

          <fieldset class="form-horizontal col-md-6">

          <!-- Text input-->
          <div class="form-group">
            <label class="control-label" for="Name">Full Name</label>  
            <input id="Name" name="Name" type="text" value="<?php echo $Name; ?>" class="form-control input-md" required="true">
            <span class="help-block" style="color:red;"><?php echo $NameError; ?></span>
          </div>

          <!-- Text input-->
          <div class="form-group">
            <label class="control-label" for="Username">Username</label>  
            <input id="Username" name="Username" type="text" value="<?php echo $Username; ?>" class="form-control input-md" required="true">
            <span class="help-block" style="color:red;"><?php echo $UsernameError; ?></span>  
          </div>

          <!-- Email input-->
          <div class="form-group">
            <label class="control-label" for="Email">Email Address</label>  
            <input id="EmailAddress" name="EmailAddress" type="email" value="<?php echo $EmailAddress; ?>" class="form-control input-md" required="true">
            <span class="help-block" style="color:red;"><?php echo $EmailAddressError; ?></span>
            <span class="help-block">For notifications and password resets</span>  
          </div>

          <!-- Text input-->
          <div class="form-group">
            <label class="control-label" for="AccountType">Account Type</label>  
            <input id="AccountType" name="AccountType" type="text" value="<?php echo $AccountType; ?>" class="form-control input-md" disabled="true">
            <span class="help-block">You cannot change your account type</span>  
          </div>

         
         <!--  <div class="form-group">
            <label class="control-label" for="Password">Current Password</label>
              <input id="Password" name="Password" type="password" class="form-control input-md" required="true">
              <span class="help-block">Enter current password to save</span>
          </div> -->

          <!-- Button -->
          <div class="form-group">
            <label class="control-label" for=""></label>
              <button type="submit" class="btn btn-danger"><i class="fa fa-pencil"></i> Save Profile</button>
          </div>

          </fieldset>
        </form>

<?php include("templates/dashboard/footer.php");