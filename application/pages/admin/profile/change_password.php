<?php if(!defined("ChurchYard_Execute")) die("Access Denied.");

include("templates/dashboard/header.php");


?><h1 class="page-header">Change Password</h1>

<div class="row">
    <div class="col-md-12">

      <form >
        <fieldset class="col-md-6 form-horizontal">
          <!-- Password input-->
          <div class="form-group">
            <label class=" control-label" for="NewPassword">Enter New Password</label>
              <input id="NewPassword" name="NewPassword" type="password" placeholder="" class="form-control input-md" required="">
              
          </div>

          <!-- Password input-->
          <div class="form-group">
            <label class="control-label" for="ConfirmNewPassword">Confirm New Password</label>
              <input id="ConfirmNewPassword" name="ConfirmNewPassword" type="password" placeholder="" class="form-control input-md" required="">
          </div>

          <!-- Password input-->
          <div class="form-group">
            <label class=" control-label" for="CurrentPassword">Current Password</label>
              <input id="CurrentPassword" name="CurrentPassword" type="password" placeholder="" class="form-control input-md" required="">
          </div>

          <!-- Button -->
          <div class="form-group">
              <button type="submit" class="btn btn-danger"><i class="fa fa-key"></i> Change Password</button>
          </div>

        </fieldset>
      </form>
            
      </div><!-- /.panel-->
    </div><!-- /.col -->
</div><!-- /.row -->

<?php include("templates/dashboard/footer.php");