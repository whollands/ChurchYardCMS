<?php if(!defined("ChurchYard_Execute")) die("Access Denied.");

include("templates/dashboard/header.php");


?><h1 class="page-header">Media</h1>

<div class="row">
    <div class="col-md-12">

      <p><a href="<?php echo GetPageURL("admin/media/upload"); ?>" class="btn btn-success align-right"><i class="fa fa-upload"></i> Upload files</a></p>



      <div class="panel panel-default">
    
            <table class="table">
          
            <tr>
              <th>Name</th>
              <th>Action</th>
            </tr>

            <tr>
              <td>Some media</td>
              <td>Some action</td>
            </tr>
              

            </table>
      </div><!-- /.panel-->
    </div><!-- /.col -->
</div><!-- /.row -->

<?php include("templates/dashboard/footer.php");