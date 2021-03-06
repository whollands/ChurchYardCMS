<?php if(!defined("ChurchYard_Execute")) die("Access Denied."); ?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    
    <title><?php echo $GLOBALS['Config']->SiteName; ?> :: Page not found</title>
    <meta name="author" content="Will Hollands">

    <link rel="icon" href="<?php echo GetResourceURL("application/images/Favicon.png"); ?>">
    <link rel="apple-touch-icon" href="<?php echo GetResourceURL("application/images/Apple-Touch-Icon.png"); ?>">

    <link href="<?php echo GetResourceURL("application/css/bootstrap.min.css"); ?>" rel="stylesheet">
    <link href="<?php echo GetResourceURL("application/css/font-awesome.min.css"); ?>" rel="stylesheet">

    <style type="text/css">

        body {
            padding-bottom: 40px;
            background-color: #eee;
        }
        
        .panel {
            max-width: 420px;
            margin: 0 auto;
        }

    </style>

  </head>
 
<body>
    <div class="container">
        <div class="panel panel-default text-center" style="margin-top:100px;">
            <div class="panel-body">
                <h1><i class="fa fa-warning"></i> An error occurred</h1>
                <p>