<?php if(!defined("ChurchYard_Execute")) die("Access Denied.");


if(isset($_POST["username"]) || isset($_POST["password"]))
{

  $User = new User();

  if($User->CheckCredentials($_POST["username"], $_POST["password"]))
  {
    header("Location: /admin");
    exit;
  }
  else
  {
    $InfoMessage = AlertDanger("Invalid username or password.");
  }

}

?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title><?php echo $GLOBALS['Config']->SiteName; ?> :: Sign In</title>
    <meta name="author" content="Will Hollands">

    <meta name="robots" content="noindex">
    <!-- hide login page from search engines -->

    <link rel="icon" href="<?php echo GetResourceURL("application/images/Favicon.png"); ?>">
    <link rel="apple-touch-icon" href="<?php echo GetResourceURL("application/images/Apple-Touch-Icon.png"); ?>">

    <link href="<?php echo GetResourceURL("application/css/bootstrap.min.css"); ?>" rel="stylesheet">
    <link href="<?php echo GetResourceURL("application/css/font-awesome.min.css"); ?>" rel="stylesheet">

    <style>
            body {
              padding-top: 100px;
              padding-bottom: 40px;
              background-color: #eee;
            }

            .panel {
                max-width: 340px;
                margin: 0 auto;
            }
    </style>
  </head>
 
<body>

    <div class="container">
        
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="panel-title">Sign In</h2>
        </div>
        <div class="panel-body">
        
            <form method="post" action="<?php echo GetPageURL("login"); ?>"> 
                
                <div class="input-group input-group-md" style="margin-bottom: 10px;">
                  <span class="input-group-addon" id="basic-addon3"><i class="fa fa-envelope"></i></span>
                  <input type="text" class="form-control" id="username" name="username" aria-describedby="basic-addon3" placeholder="Username or Email" required>
                </div>

                <div class="input-group">
                  <span class="input-group-addon" id="basic-addon3"><i class="fa fa-key"></i></span>
                  <input type="password" class="form-control" id="password" name="password" aria-describedby="basic-addon3" placeholder="Password" required="">
                </div>

                <div class="checkbox">
                    <label>
                        <input type="checkbox" value="remember-me"> Keep me signed in for 30 days
                    </label>
                </div>

            <button class="btn btn-md btn-primary btn-block" type="submit">Sign In</button>

          </form>
          <br>
          <?php echo $InfoMessage; ?>

        </div>
    </div>

</body>
</html>