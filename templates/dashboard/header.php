<?php if(!defined("ChurchYard_Execute")) die("Access Denied.");

?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title><?php echo $GLOBALS['Config']->SiteName; ?> :: Dashboard</title>
    <meta name="author" content="Will Hollands">

    <link rel="icon" href="<?php echo GetResourceURL("application/images/Favicon.png"); ?>">
    <link rel="apple-touch-icon" href="<?php echo GetResourceURL("application/images/Apple-Touch-Icon.png"); ?>">

    <link href="<?php echo GetResourceURL("application/css/bootstrap.min.css"); ?>" rel="stylesheet">
    <link href="<?php echo GetResourceURL("application/css/font-awesome.min.css"); ?>" rel="stylesheet">
    <link href="<?php echo GetResourceURL("application/css/admin-overrides.css"); ?>" rel="stylesheet">

  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo GetPageURL("admin/dashboard"); ?>"><?php echo $GLOBALS['Config']->SiteName; ?></a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right hidden-xs">
              <li><a href="#" data-toggle="modal" data-target="#DevModal"><i class="fa fa-code"></i> Developer</a></li> 
              <li><a href="<?php echo GetPageURL(); ?>"><i class="fa fa-globe"></i> Visit Website</a></li>
               <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user"></i> Will Hollands <span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li><a href="#"><i class="fa fa-pencil"></i> Edit profile</a></li>
                  <li><a href="#"><i class="fa fa-key"></i> Change password</a></li>
                  <li role="separator" class="divider"></li>
                  <li><a href="<?php echo GetPageURL("logout"); ?>"><i class="fa fa-lock"></i> Sign Out &rarr;</a></li>
                </ul>
              </li>    
              <li><a href="<?php echo GetPageURL("logout"); ?>"><i class="fa fa-lock"></i> Sign Out &rarr;</a></li>
          </ul>
          <!--
          <form class="navbar-form navbar-right">
            <input type="text" class="form-control" placeholder="Search...">
          </form> -->
        </div>
      </div>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <div id="navbar" class="col-sm-3 col-md-2 sidebar">

        <ul class="nav nav-sidebar">
        <?php

          $Pages = array(

                'dashboard' => "<i class=\"fa fa-dashboard\"></i> Dashboard",
                '1' => "",

                'editor' => "<i class=\"fa fa-pencil\"></i> Editor",
                'media' => "<i class=\"fa fa-folder\"></i> Media",
                '2' => "",

                'records' => "<i class=\"fa fa-book\"></i> Records",
                'relationships' => "<i class=\"fa fa-heart\"></i> Relationships",
                '3' => "",

                'jobs' => "<i class=\"fa fa-server\"></i> Jobs",
                'settings' => "<i class=\"fa fa-gears\"></i> Settings",
                'help' => "<i class=\"fa fa-question-circle\"></i> Help"

            );

          foreach($Pages as $URL=>$Title)
          {
            $FullURL = GetPageURL("admin/" . $URL);

            if($Title == "")
            {
              echo "</ul>";
              echo "<ul class=\"nav nav-sidebar\">";
            }
            else
            {
              if(GetPathPart(1) == $URL)
              {
                echo "<li class=\"active\">";
              }
              else
              {
                echo "<li>";
              }
              echo "<a href=\"$FullURL\">$Title</a></li>";
              }
          }
        ?>
        </ul>
        <ul class="nav nav-sidebar visible-xs">
          <li><a href="<?php echo GetPageURL(); ?>"><i class="fa fa-globe"></i> Visit Website</a></li>
          <li><a href="<?php echo GetPageURL("admin/logout"); ?>"><i class="fa fa-lock"></i> Sign Out &rarr;</a></li>
        </ul>
      </div>
  </div>

  <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

  <noscript>
      <div class="alert alert-warning" role="alert">
        <i class="fa fa-warning"></i>
          You must enable JavaScript in your browser in order for this website to function correctly!
      </div>
  </noscript>