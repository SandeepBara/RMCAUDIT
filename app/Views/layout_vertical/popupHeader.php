<?php $session = session(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Municipal Corporation</title>
    <link href="<?=base_url();?>/public/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=base_url();?>/public/assets/css/nifty.min.css" rel="stylesheet">
    <link href="<?=base_url();?>/public/assets/css/demo/nifty-demo-icons.min.css" rel="stylesheet">
    <link href="<?=base_url();?>/public/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?=base_url();?>/public/assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
    <link href="<?=base_url();?>/public/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="<?=base_url();?>/public/assets/bootstrap4-toggle/css/bootstrap4-toggle.min.css" rel="stylesheet">
    <link href="<?=base_url();?>/public/assets/css/common.css" rel="stylesheet">
    <style>
.row{line-height:25px;} 

/* print  */
@media print {
	#customer_view_detail;
	#print_watermark {
        background-image:url(<?=base_url(); ?>/public/assets/img/rmcWATERlogo.png) !important;
		background-repeat:no-repeat !important;
		background-position:center !important;
        -webkit-print-color-adjust: exact; 
    }
}
#print_watermark{
	background-color:#FFFFFF;
	background-image:url(<?=base_url(); ?>/public/assets/img/rmcWATERlogo.png) !important;
	background-repeat:no-repeat;
	background-position:center;
	
}
 </style>
</head>
<body>