<?php if(!defined("ChurchYard_Execute")) die("Access Denied.");

if(isset($_POST["Submitted"]))
{

  $Validated = true;
  // assume input is valid until otherwise

  $GraveID = $_POST["GraveID"];
  $XCoord = $_POST["XCoord"];
  $YCoord = $_POST["YCoord"];
  // Get values submitted by form
  
  switch ($_POST['GraveType']) 
  {
    case 'u':
       $GraveType = 'u';
      break;
    case 'f':
       $GraveType = 'f';
      break;
    case 'c':
       $GraveType = 'c';
      break;
    case 'm':
       $GraveType = 'm';
      break;
    default:
       $GraveType = '?';
      break;
  }

  if(is_pos_int($XCoord) == false || is_pos_int($YCoord) == false)
  {
      $LocationError = "Co-ordinates must both be positive whole numbers.";
      $Validated = false;
  }
  else
  {
    if(Grave::CheckCoordsExist($XCoord, $YCoord))
    {
      $LocationError = "Those Co-ordinates are already in use.";
      $Validated = false;
    }
  }


  if($Validated == true)
  {

    $GraveID = Database::Filter($GraveID);
    $GraveType = Database::Filter($GraveType);
    $XCoord = Database::Filter($XCoord);
    $YCoord = Database::Filter($YCoord);

    $SQL = "INSERT INTO Graves (GraveID, Type, XCoord, YCoord)
            VALUES (DEFAULT, $GraveType, $XCoord, $YCoord)
            ";

    Database::Query($SQL);


    Server::Redirect('admin/graves/new');

  }

}


include("templates/dashboard/header.php");

?><h1 class="page-header">New Grave</h1>

<div class="row">
    <div class="col-md-12">

      <form method="post" action="">
        <fieldset class="form-horizontal col-md-6">


        <input type="hidden" name="Submitted" value="true">

        <!-- Text input-->
        <div class="form-group">
          <label class="control-label" for="XCoord">Location Co-Ordinates</label> 
            <div class="row">
              <div class="col-md-2">
                <input id="XCoord" name="XCoord" type="text" placeholder="1" class="form-control input-md" required="true" value="<?php echo $XCoord; ?>">
              </div>
              <div class="col-md-2">
            <input id="YCoord" name="YCoord" type="text" placeholder="1" class="form-control input-md" required="true" value="<?php echo $YCoord; ?>">
              </div>
            </div>
            
            <span class="help-block" style="color:red;"><?php echo $LocationError; ?></span>
        </div>

        <div class="radio">
          <label>
            <input type="radio" name="GraveType" id="GraveType" value="u"<?php if($GraveType == 'u') { echo " checked"; } ?>>
            Upright Headstone
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="GraveType" id="GraveType" value="f"<?php if($GraveType == 'f') { echo " checked"; } ?>>
            Flat Headstone
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="GraveType" id="GraveType" value="c"<?php if($GraveType == 'c') { echo " checked"; } ?>>
            Curbed Headstone
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="GraveType" id="GraveType" value="m"<?php if($GraveType == 'm') { echo " checked"; } ?>>
            Monument
          </label>
        </div>
       
        <br>

        <!-- Button -->
        <div class="form-group">
            <button type="submit" class="btn btn-danger"><i class="fa fa-pencil"></i> Create Grave</button>
        </div>

        </fieldset>
      </form>

    </div><!-- /.col -->
</div><!-- /.row -->

<?php include("templates/dashboard/footer.php");