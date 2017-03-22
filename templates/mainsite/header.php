<?php if(!defined("ChurchYard_Execute")) die("Access Denied.");
?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title><?php echo $GLOBALS['Config']->SiteName; ?> :: <?php echo $PageName; ?></title>
    <meta name="author" content="Will Hollands">

    <link rel="icon" href="<?php echo GetResourceURL("application/images/Favicon.png"); ?>">
    <link rel="apple-touch-icon" href="<?php echo GetResourceURL("application/images/Apple-Touch-Icon.png"); ?>">

    <link href="<?php echo GetResourceURL("application/css/bootstrap.min.css"); ?>" rel="stylesheet">
    <link href="<?php echo GetResourceURL("application/css/font-awesome.min.css"); ?>" rel="stylesheet">
    <link href="<?php echo GetResourceURL("application/css/main-overrides.css"); ?>" rel="stylesheet">

  </head>

  <body>

    <div class="container">
      <div class="header clearfix">
        <nav>
          <ul class="nav nav-pills pull-right">
            <li role="presentation" class=""><a href="/">Home</a></li>
            <li role="presentation"><a href="/about">About</a></li>
            <li role="presentation"><a href="/database">Database</a></li>
            <li role="presentation"><a href="/contact">Contact</a></li>
          </ul>
        </nav>
        <h3 class="text-muted"><?php echo $GLOBALS['Config']->SiteName; ?></h3>
      </div>