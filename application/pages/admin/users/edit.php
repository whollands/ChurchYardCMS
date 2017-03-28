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

     

    </div><!-- /.col -->
</div><!-- /.row -->

<?php include("templates/dashboard/footer.php");