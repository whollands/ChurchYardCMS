<?php if(!defined("ChurchYard_Execute")) die("Access Denied.");

include("templates/dashboard/header.php");


?><h1 class="page-header">Records</h1>

<div class="row">
    <div class="col-md-12">

  <p><a href="<?php echo GetPageURL("admin/records/new"); ?>" class="btn btn-success align-right"><i class="fa fa-plus"></i> Create Record</a></p>

      
    



            <?php

           $db = new Database();
           
           $Data = $db -> Select("SELECT FirstName, DateOfDeath FROM Records");

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
              <th>Date Of Death</th>
              <th>First / Last Name(s)</th>
              <th>Actions</th>
            </tr>

              <?php

              foreach($Data as $Data)
              {
                  echo "<tr>";
                  echo "<td>" . $Data["DateOfDeath"] . "</td>";
                  echo "<td>" . $Data["FirstName"] . " " . $Data["LastName"] . "</td>";
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