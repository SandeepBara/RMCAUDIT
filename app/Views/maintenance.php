<?php //defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Site Under Maintenance</title>
</head>
<style>
      body { text-align: center; padding: 20px; font: 20px Helvetica, sans-serif; color: #333; }
      @media (min-width: 768px){
        body{ padding-top: 150px; }
      }
      h1 { font-size: 50px; }
      article { display: block; text-align: left; max-width: 650px; margin: 0 auto; }
      a { color: #dc8100; text-decoration: none; }
      a:hover { color: #333; text-decoration: none; }
    </style>
<body class="bg">
    <h1 class="head text-center">Site Under Maintenance <?=($fromMaintenance??"").(isset($uptoMaintenance)?" To ":"" ).($uptoMaintenance??"");?></h1>
    <div class="container">
        <div class="content1"> 
            <!--img src="http://localhost/codeigniter/assets/images/2.png" alt="under-construction"-->
            <p class="text-center">Sorry for the inconvenience. To improve our services, we have momentarily shutdown our site.</p>
            <a href="<?=base_url();?>" class="btn btn-primary">Go to home</a>
        </div>
    </div>
</body>
</html>