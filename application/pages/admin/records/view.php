<?php if(!defined("ChurchYard_Execute")) die("Access Denied.");

include("templates/dashboard/header.php");


?><h1 class="page-header">Records</h1>

<div class="row">
    <div class="col-md-12">

  <p><a href="<?php echo GetPageURL("admin/records/new"); ?>" class="btn btn-success align-right"><i class="fa fa-plus"></i> Create Record</a></p>

      
    



            <?php

           $Db = new Database();
           
           $Data = $Db->Select("SELECT FirstName, LastName, DateOfDeath, DateOfBirth FROM Records");

            if(count($Data) == 0)
            {
              echo AlertWarning("No records could be found.");
            }
            else
            {

              ?>


      <div class="panel panel-default">
        <table class="table">
            <tr>
              <th>First / Last Name(s)</th>
              <th>Date Of Death</th>
              <th>Date Of Birth</th>
              <th>Actions</th>
            </tr>

              <?php

              foreach($Data as $Data)
              {
                  $DateOfDeath = strtotime($Data['DateOfDeath']);
                  $DateOfDeath = date('n M Y', $DateOfDeath);

                  $DateOfBirth = strtotime($Data['DateOfBirth']);
                  $DateOfBirth = date('n M Y', $DateOfBirth);

                  echo "<tr>";
                  echo "<td>" . $Data["FirstName"] . " " . $Data["LastName"] . "</td>";
                  echo "<td>" . $DateOfDeath . "</td>";
                  echo "<td>" . $DateOfBirth . "</td>";
                  echo "<td>";
                  echo Button("Edit", "#", "btn btn-primary btn-xs");
                  echo " ";
                  echo Button("Delete", "#", "btn btn-danger btn-xs");
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