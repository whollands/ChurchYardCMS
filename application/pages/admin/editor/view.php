<?php if(!defined("ChurchYard_Execute")) die("Access Denied.");

include("templates/dashboard/header.php");


?><h1 class="page-header">Editor</h1>

<div class="row">
    <div class="col-md-12">

  <p>
    <a href="<?php echo GetPageURL("admin/editor/new"); ?>" class="btn btn-success align-right">
      <i class="fa fa-plus"></i> Create Page</a>
  </p>

      
    



            <?php

           $db = new Database();
           
           $rows = $db -> Select("SELECT PageID, PageName, LastEdited FROM Pages");

            if(count($rows) == 0)
            {
              echo AlertWarning("No pages could be found.");
            }
            else
            {

              ?>


      <div class="panel panel-default">
        <table class="table">
            <tr>
              <th>Page Name</th>
              <th>Last Edited</th>
              <th>Actions</th>
            </tr>

              <?php

              foreach($rows as $Data)
              {
                  echo "<tr>";
                  echo "<td>" . $Data["PageName"] . "</td>";
                  echo "<td>" . $Data["LastEdited"] . "</td>";
                  echo "<td>";
                  echo Button("Edit", GetPageURL("admin/editor/edit/" . $Data["PageID"]), "btn btn-primary btn-xs");
                  echo " ";
                  echo Button("Delete", GetPageURL("admin/editor/delete/" . $Data["PageID"]), "btn btn-danger btn-xs");
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