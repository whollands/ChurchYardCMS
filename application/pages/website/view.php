<?php if(!defined("ChurchYard_Execute")) die("Access Denied.");


$Path = GetCurrentPath();

$PageURL = implode("/", $Path["call_parts"]);

if($PageURL == null)
{
    $PageURL = "homepage";
    // load homepage if no URL specified
}

$PageURL = $db -> Filter($PageURL);

$Data = $db -> Select("SELECT PageName, Content FROM Pages WHERE URL=$PageURL");


if(count($Data) != 1)
{
  include("application/pages/errors/404Error.php");
  exit;
}
else
{
  $PageName = $Data[0]['PageName'];
  $PageContent = $Data[0]['Content'];
}

include("templates/mainsite/header.php");

echo $PageContent;

include("templates/mainsite/footer.php");
      