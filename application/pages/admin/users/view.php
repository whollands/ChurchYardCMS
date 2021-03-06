<?php if(!defined("ChurchYard_Execute")) die("Access Denied.");

if(!User::$IsAdmin)
{
  Server::ErrorMessage("Access Denied: You do not have permission to access this page.");
}

include("templates/dashboard/header.php");


?><h1 class="page-header">Users</h1>

<div class="row">
    <div class="col-md-12">

  <p>
    <a href="<?php echo GetPageURL("admin/users/new"); ?>" class="btn btn-success align-right">
      <i class="fa fa-plus"></i> Create User</a>
  </p>

      
    



            <?php

           
           
           $Data = Database::Select("SELECT UserID, Username, Name FROM Users");

            if(count($Data) == 0)
            {
              echo AlertWarning("No users could be found.");
            }
            else
            {

              ?>


      <div class="panel panel-default">
        <table class="table">
            <tr>
              <th>Name</th>
              <th>Username</th>
              <th>Actions</th>
            </tr>

              <?php

              foreach($Data as $Data)
              {
                  echo "<tr>";
                  echo "<td>" . $Data["Name"] . "</td>";
                  echo "<td>" . $Data["Username"] . "</td>";
                  echo "<td>";
                  echo Button("Edit", GetPageURL("admin/users/edit/" . $Data["UserID"]), "btn btn-primary btn-xs");
                  echo " ";
                  if($Data['UserID'] != 0)
                  // don't show delete button for SuperUser of ID 0
                  {
                    echo Button("Delete", GetPageURL("admin/users/delete/" . $Data["UserID"]), "btn btn-danger btn-xs");
                  }
                  echo "</td>";
                  echo "</tr>";
              }

              echo "</table>";


            }

            ?>
            
      </div><!-- /.panel-->
    </div><!-- /.col -->
</div><!-- /.row -->

<?php include("templates/dashboard/footer.php");