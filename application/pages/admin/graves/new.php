<?php if(!defined("ChurchYard_Execute")) die("Access Denied.");

if(isset($_POST["Submitted"]))
{

  $GraveID = trim($_POST["GraveID"]);
  $XCoord = trim(strtolower($_POST["XCoord"]));
  $YCoord = trim(strtolower($_POST["YCoord"]));
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


  if($Validated == true)
  {

  }

}


include("templates/dashboard/header.php");

?><h1 class="page-header">New Grave</h1>

<div class="row">
    <div class="col-md-12">

      <form method="post" action="">
        <fieldset class="form-horizontal col-md-6">


        <input type="hidden" name="Submitted" value="true">

        <!-- Number input-->
        <div class="form-group">
          <label class="control-label" for="GraveID">Grave ID</label>  
            <input id="GraveID" name="GraveID" type="number" placeholder="01" class="form-control input-md" required="true" value="<?php echo $GraveID; ?>">
            <span class="help-block" style="color:red;"><?php echo $GraveIDError; ?></span>
        </div>


        <!-- Text input-->
        <div class="form-group">
          <label class="control-label" for="XCoord">Location Co-Ordinates</label>  
            <input id="XCoord" name="XCoord" type="text" placeholder="1" class="form-control input-md" required="true" value="<?php echo $XCoord; ?>">
            <input id="YCoord" name="YCoord" type="text" placeholder="1" class="form-control input-md" required="true" value="<?php echo $YCoord; ?>">
            <span class="help-block" style="color:red;"><?php echo $LocationError; ?></span>
        </div>

        <div class="radio">
          <label>
            <input type="radio" name="GraveType" id="GraveType" value="u">
            Upright Headstone
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="GraveType" id="GraveType" value="f">
            Flat Headstone
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="GraveType" id="GraveType" value="c">
            Curbed Headstone
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="GraveType" id="GraveType" value="m">
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