<?php if(!defined("ChurchYard_Execute")) die("Access Denied.");

include("templates/dashboard/header.php");


?><h1 class="page-header">Editor</h1>

<div class="row">
    <div class="col-md-12">

  <p>
    <a href="<?php echo GetPageURL("admin/pages/new"); ?>" class="btn btn-success align-right">
      <i class="fa fa-plus"></i> Create Page</a>
  </p>

      
    



            <?php

           
           
           $Data = Database::Select("SELECT PageID, PageName, URL, LastEdited FROM Pages");

            if(count($Data) == 0)
            {
              echo AlertWarning("No pages have been created yet.");
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

              foreach($Data as $Data)
              {
                  echo "<tr>";
                  echo "<td><a href=\"".GetPageURL($Data["URL"])."\">" . $Data["PageName"] . "</a></td>";
                  echo "<td>" . $Data["LastEdited"] . "</td>";
                  echo "<td>";
                  echo Button("Edit", GetPageURL("admin/pages/edit/" . $Data["PageID"]), "btn btn-primary btn-xs");
                  echo " ";
                  echo Button("Delete", GetPageURL("admin/pages/delete/" . $Data["PageID"]), "btn btn-danger btn-xs");
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