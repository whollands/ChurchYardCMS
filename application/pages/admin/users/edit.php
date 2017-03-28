<?php if(!defined("ChurchYard_Execute")) die("Access Denied.");

if(!User::$IsAdmin)
{
  Server::ErrorMessage("Access Denied: You do not have permission to access this page.");
}

$UserID = GetPathPart(3);

$Data = Database::Filter($UserID);

$SQL = "SELECT UserID, Name, EmailAddress, IsAdmin
        FROM Users
        WHERE UserID=$UserID
      ";

$Data = Database::Select($SQL);

if(count($Data) == 1)
{
  $UserID = $Data[0]['UserID'];
  $Username = $Data[0]['Username'];
  $Name = $Data[0]['Name'];
  $EmailAddress = $Data[0]['EmailAddress'];

}
else if(count($Data) > 1)
{
  Server::ErrorMessage("Multiple users found with PRIMARY KEY: UserID");
}
else
{
  Server::ErrorMessage("User not found.");
}




include("templates/dashboard/header.php");

?><h1 class="page-header">User <?php echo $Name; ?></h1>

<?php Bootstrap::DisplayBreadcrumb(); ?>

<div class="row">
    <div class="col-md-12">


    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#change-password"><i class="fa fa-pencil"></i> Change Password</button>


    <!-- Change password modal -->
        <div class="modal fade" id="change-password" tabindex="-1" role="dialog" aria-labelledby="change-password">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Change User's Password</h4>
              </div>
              <div class="modal-body">

                <!-- Text input-->
                <div class="form-group">
                    <input id="NewPassword" name="NewPassword" type="text" value="<?php echo $PageName; ?>" class="form-control input-md" required="">
                </div>

                <p>Passwords MUST contain:</p>
                <ul>
                  <li>1 or more numbers</li>
                  <li>1 or more lowercase letters</li>
                  <li>1 or more uppercase letters</li>
                  <li>1 or more special characters</li>
                  <li>At least 8 characters in length</li>
                </ul>
               
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger"><i class="fa fa-key"></i> Change Password</button>
              </div>
            </div>
          </div>
        </div>
      <!-- end change password modal -->
    
    </div><!-- /.col -->
</div><!-- /.row -->

<?php include("templates/dashboard/footer.php");