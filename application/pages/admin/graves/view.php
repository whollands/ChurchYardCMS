<?php if(!defined("ChurchYard_Execute")) die("Access Denied.");

include("templates/dashboard/header.php");


?><h1 class="page-header">Graves</h1>

<div class="row">
    <div class="col-md-12">

  <p><a href="<?php echo GetPageURL("admin/graves/new"); ?>" class="btn btn-success align-right"><i class="fa fa-plus"></i> Create Grave</a></p>

      
    



            <?php

           
           
           $Data = Database::Select("SELECT GraveID, Type FROM Graves");

            if(count($Data) == 0)
            {
              echo AlertWarning("No graves could be found.");
            }
            else
            {

              ?>


      <div class="panel panel-default">
        <table class="table">
            <tr>
              <th>Grave ID</th>
              <th>Type</th>
              <th>Actions</th>
            </tr>

              <?php

              foreach($Data as $Data)
              {
                  echo "<tr>";
                  echo "<td>" . $Data["GraveID"] . "</td>";
                  echo "<td>" . $Data["Type"] . "</td>";
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