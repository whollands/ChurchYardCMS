<?php if(!defined("ChurchYard_Execute")) die("Access Denied.");

include("templates/dashboard/header.php");


?><h1 class="page-header">Media</h1>

<div class="row">
    <div class="col-md-12">

  <p><a href="<?php echo GetPageURL("admin/media/new"); ?>" class="btn btn-success align-right"><i class="fa fa-upload"></i> Upload Media</a></p>


            <?php

           $Db = new Database();
           
           $Data = $Db -> Select("SELECT MediaID, MediaName, DateUploaded FROM Media");

            if(count($Data) == 0)
            {
              echo AlertWarning("No media could be found.");
            }
            else
            {

              ?>


      <div class="panel panel-default">
        <table class="table">
            <tr>
              <th>Media Name</th>
              <th>Last Edited</th>
              <th>Actions</th>
            </tr>

              <?php

              foreach($Data as $Data)
              {
                  echo "<tr>";
                  echo "<td>" . $Data["MediaName"] . "</td>";
                  echo "<td>" . $Data["DateUploaded"] . "</td>";
                  echo "<td>";
                  echo Button("Edit", GetPageURL("admin/media/edit/" . $Data["MediaID"]), "btn btn-primary btn-xs");
                  echo " ";
                  echo Button("Delete", GetPageURL("admin/media/delete/" . $Data["MediaID"]), "btn btn-danger btn-xs");
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