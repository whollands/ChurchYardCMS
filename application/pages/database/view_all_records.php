<?php if(!defined("ChurchYard_Execute")) die("Access Denied.");


include("templates/mainsite/header.php");

?>

<h1>Database</h1>

<ul class="nav nav-tabs nav-justified">
  <li role="presentation"><a href="<?php echo GetPageURL("database/map"); ?>">Map</a></li>
  <li role="presentation" class="active"><a href="<?php echo GetPageURL("database/view"); ?>">Search Records</a></li>
  <li role="presentation"><a href="<?php echo GetPageURL("database/tree"); ?>">Family Trees</a></li>
</ul>

<br>

  <form action="<?php echo GetPageURL('database/view'); ?>" method="get">
    <div class="input-group col-md-5">
      
        <input type="text" class="form-control" name="search_query" placeholder="Search for..." value="<?php echo $SearchQuery; ?>">
        <span class="input-group-btn">
          <button class="btn btn-default" type="submit"><i class="fa fa-arrow-right"></i></button>
        </span>  
    </div><!-- /input-group -->
  </form>
<br>

 <?php

   
   
   $SearchQuery = $_GET["search_query"];

   if($SearchQuery == null)
   {
    $SQL = "SELECT * FROM Records";
   }
   else
   {
    $SQL = "SELECT * FROM Records 
            WHERE FirstName LIKE '%$SearchQuery%'
            OR LastName LIKE '%$SearchQuery%'
            OR FirstName + LastName LIKE '%$SearchQuery%'
            ";
   }

   $Data = Database::Select($SQL);

    if(count($Data) == 0)
    {
      echo AlertWarning("No records could be found.");
    }
    else
    {

              ?>


      
        <table class="table">
            <tr>
              <th>First / Last Name(s)</th>
              <th>Date Of Birth</th>
              <th>Date Of Death</th>
            </tr>

              <?php

              foreach($Data as $Data)
              {
                  echo "<tr>";
                  echo "<td><a href=\"";
                  echo GetPageURL("database/view/".$Data['RecordID']) . "\">";
                  echo $Data["FirstName"] . " " . $Data["LastName"] . "</a></td>";
                  echo "<td>" . ConvertDate($Data["DateOfBirth"]) . "</td>";
                  echo "<td>" . ConvertDate($Data["DateOfDeath"]) . "</td>";
                  echo "</tr>";
              }

              echo "</table>";


            }

            ?>


<?php
include("templates/mainsite/footer.php");