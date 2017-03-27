<?php if(!defined("ChurchYard_Execute")) die("Access Denied.");


include("templates/mainsite/header.php");



?>


<h1>Database</h1>

<ul class="nav nav-tabs nav-justified">
  <li role="presentation" class="active"><a href="<?php echo GetPageURL("database/map"); ?>">Map</a></li>
  <li role="presentation" ><a href="<?php echo GetPageURL("database/view"); ?>">Search Records</a></li>
  <li role="presentation"><a href="<?php echo GetPageURL("database/tree"); ?>">Family Trees</a></li>
</ul>

<noscript>
	<div class="alert alert-warning" role="alert"><i class="fa fa-warning"></i> JavaScript must be enabled in your browser in order to display the interactive map.</div>
</noscript>


<style type="text/css">
	
	table {
  width: 100%;
  background:url('/application/images/grass.png');
}
td {
  width: 1%;
  position: relative;
}
td:after {
  content: '';
  display: block;
  margin-top: 100%;
}
td .content {
  position: absolute;
  background-image:url('/application/images/grave.png');
  background-position: center;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
}
</style>
<div class="visible-xs">
  <div class="alert alert-warning" role="alert"><i class="fa fa-warning"></i> Interactive map is only viewable on desktop and tablet devices.</div>
</div>
<div id="map" class="hidden-xs">
	
<?php Map::DisplayMap(); ?>


</div>


<?php
include("templates/mainsite/footer.php");