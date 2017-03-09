<?php if(!defined("ChurchYard_Execute")) die("Access Denied.");


include("templates/mainsite/header.php");


?>

<h1>Database</h1>

<ul class="nav nav-tabs nav-justified">
  <li role="presentation"><a href="<?php echo GetPageURL("database/map"); ?>">Map</a></li>
  <li role="presentation"><a href="<?php echo GetPageURL("database/view"); ?>">Search Records</a></li>
  <li role="presentation" class="active"><a href="<?php echo GetPageURL("database/tree"); ?>">Family Trees</a></li>
</ul>



<?php
include("templates/mainsite/footer.php");