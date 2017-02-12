<?php define("ChurchYard_Execute", true);

include("../includes/objects.php");

$db = new Database();

$Data = $db -> Select("SELECT GraveID, Type, Location FROM Graves ORDER BY GraveID");


echo json_encode($Data);