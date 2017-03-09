<?php if(!defined("ChurchYard_Execute")) die("Access Denied.");


include("templates/mainsite/header.php");

?>

<h1>Database</h1>

<ul class="nav nav-tabs nav-justified">
  <li role="presentation"><a href="<?php echo GetPageURL("database/map"); ?>">Map</a></li>
  <li role="presentation" class="active"><a href="<?php echo GetPageURL("database/view"); ?>">Search Records</a></li>
  <li role="presentation"><a href="<?php echo GetPageURL("database/tree"); ?>">Family Trees</a></li>
</ul>



    <div class="input-group col-md-5">
      <input type="text" class="form-control" placeholder="Search for...">
      <span class="input-group-btn">
        <button class="btn btn-default" type="button">Go!</button>
      </span>
    </div><!-- /input-group -->


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


      
        <table class="table">
            <tr>
              <th>Date Of Death</th>
              <th>First / Last Name(s)</th>
            </tr>

              <?php

              foreach($Data as $Data)
              {
                  echo "<tr>";
                  echo "<td>" . $Data["DateOfDeath"] . "</td>";
                  echo "<td>" . $Data["FirstName"] . " " . $Data["LastName"] . "</td>";
                  echo "</tr>";
              }

              echo "</table>";


            }

            ?>


<?php
include("templates/mainsite/footer.php");