<?php if(!defined("ChurchYard_Execute")) die("Access Denied.");

if(isset($_POST["Submitted"]))
{


  $Validated = false;


  $GraveID = trim($_POST['GraveID']);
  $FirstName = trim($_POST['FirstName']);
  $LastName = trim($_POST['LastName']);
  $DateOfBirth = trim($_POST['DateOfBirth']);
  $DateOfDeath = trim($_POST['DateOfDeath']);

  switch ($_POST['Gender'])
  {
    case 'm':
      $Gender = 'm';
      break;

    case 'f':
      $Gender = 'f';
      break;

    default:
      $Gender = '?';
      break;
  }





  if($Validated == true)
  {
    $db = new Database();



    $SQL = "INSERT INTO Records (RecordID, GraveID, FirstName, LastName, Gender, DateOfDeath, DateOfBirth, MediaID)
            VALUES (DEFAULT, $GraveID, $FirstName, $LastName, $Gender, $DateOfDeath, $DateOfBirth, $MediaID)";
    
    $db -> Query($SQL)or die($db -> Error());
    // perform sql query.

    die("Done.");

  }
}


include("templates/dashboard/header.php");

?><h1 class="page-header">New Record</h1>

<div class="row">
    <div class="col-md-12">

      <form method="post" action="">
        <fieldset class="form-horizontal col-md-6">


        <input type="hidden" name="Submitted" value="true">

        <!-- Number input-->
        <div class="form-group">
          <label class="control-label" for="PageName">Grave ID</label>  
            <input id="GraveID" name="GraveID" type="number" placeholder="01" class="form-control input-md" required="true" value="<?php echo $GraveID; ?>">
            <span class="help-block" style="color:red;"><?php echo $GraveIDError; ?></span>
        </div>

        <!-- Text input-->
        <div class="form-group">
          <label class="control-label" for="PageName">First Name</label>  
            <input id="FirstName" name="FirstName" type="text" placeholder="John" class="form-control input-md" required="true" value="<?php echo $FirstName; ?>">
            <span class="help-block" style="color:red;"><?php echo $FirstNameError; ?></span>

        </div>

        <!-- Text input-->
        <div class="form-group">
          <label class="control-label" for="PageName">Last Name(s)</label>  
            <input id="LastName" name="LastName" type="text" placeholder="Doe" class="form-control input-md" required="true" value="<?php echo $LastName; ?>">
            <span class="help-block" style="color:red;"><?php echo $LastNameError; ?></span>
        </div>

        <!-- Date input-->
        <div class="form-group">
          <label class="control-label" for="PageName">Date Of Birth (optional)</label>  
            <input id="DateOfBirth" name="DateOfBirth" type="text" placeholder="dd/mm/yyyy" class="form-control input-md" required="true" value="<?php echo $DateOfBirth; ?>">
            <span class="help-block" style="color:red;"><?php echo $DateOfBirthError; ?></span>
        </div>

        <!-- Date input-->
        <div class="form-group">
          <label class="control-label" for="PageName">Date Of Death (optional)</label>  
            <input id="DateOfDeath" name="DateOfDeath" type="text" placeholder="dd/mm/yyyy" class="form-control input-md" required="true" value="<?php echo $DateOfDeath; ?>">
            <span class="help-block" style="color:red;"><?php echo $DateOfDeathError; ?></span>
        </div>

        <div class="radio">
          <label>
            <input type="radio" name="Gender" id="Gender" value="m"<?php if($Gender == 'm') { echo " checked"; } ?>>
            <i class="fa fa-male"></i> Male
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="Gender" id="Gender" value="f"<?php if($Gender == 'f') { echo " checked"; } ?>>
            <i class="fa fa-female"></i> Female
          </label>
        </div>

        <br>

        <!-- Button -->
        <div class="form-group">
            <button type="submit" class="btn btn-danger"><i class="fa fa-pencil"></i> Create Record</button>
        </div>

        </fieldset>
      </form>

    </div><!-- /.col -->
</div><!-- /.row -->

<?php include("templates/dashboard/footer.php");