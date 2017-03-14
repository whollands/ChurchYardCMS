<?php define("ChurchYard_Execute", true);

include("../includes/objects.php");

$Db = new Database();

$Data = $Db -> Select("SELECT GraveID, Type, Location FROM Graves ORDER BY GraveID");


echo json_encode($Data);