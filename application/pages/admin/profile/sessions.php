<?php if(!defined("ChurchYard_Execute")) die("Access Denied.");

include("templates/dashboard/header.php");


?><h1 class="page-header">Sessions</h1>

<div class="row">
    <div class="col-md-12">
      
            <?php

           $Db = new Database();
           
           $Data = $Db -> Select("SELECT SessionID, DateCreated, IP FROM Sessions");

            if(count($Data) == 0)
            {
              echo AlertWarning("No sessions could be found.");
            }
            else
            {

              ?>

              <p>Devices and web browsers you are signed in on are shown below. Click "Revoke" to sign a device out of your account.</p>


      <div class="panel panel-default">
        <table class="table">
            <tr>
              <th>Session ID</th>
              <th>Date Created</th>
              <th>Actions</th>
            </tr>

              <?php

              foreach($Data as $Data)
              {
                  echo "<tr>";
                  echo "<td>" . $Data["SessionID"] . "</td>";
                  echo "<td>" . $Data["DateCreated"] . "</td>";
                  echo "<td>";
                  echo Button("Revoke", GetPageURL("admin/users/delete/" . $Data["PageID"]), "btn btn-default btn-xs");
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