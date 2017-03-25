<?php if(!defined("ChurchYard_Execute")) die("Access Denied.");


include("templates/mainsite/header.php");


?>

<h1>Database</h1>

<ul class="nav nav-tabs nav-justified">
  <li role="presentation"><a href="<?php echo GetPageURL("database/map"); ?>">Map</a></li>
  <li role="presentation"><a href="<?php echo GetPageURL("database/view"); ?>">Search Records</a></li>
  <li role="presentation" class="active"><a href="<?php echo GetPageURL("database/tree"); ?>">Family Trees</a></li>
</ul>

<link rel="stylesheet" type="text/css" href="<?php echo GetResourceURL("application/css/family-tree.css"); ?>">

<p>&nbsp;</p>

<div class="container">
	<div class="family-tree">

	
<?php

$Search = GetPathPart(2);

if($Search == null)
{
	echo AlertInfo('To view a family tree, search for a record and click the \'View Family Tree\' button.');
}
else
{
	 //FamilyTree::DisplayTree($Search);
	echo FamilyTree::FindOldestRelative($Search);
}


// echo FamilyTree::FindOldestRelative(11);

?>


	</div>
</div>
<p>&nbsp;</p>
<?php

include("templates/mainsite/footer.php");