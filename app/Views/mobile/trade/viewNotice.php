<?php //print_var($ulb_dtl); 
?>
<?php $session = session(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Municipal Corporation</title>
	<link href="<?= base_url(); ?>/public/assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?= base_url(); ?>/public/assets/css/nifty.min.css" rel="stylesheet">
	<link href="<?= base_url(); ?>/public/assets/css/demo/nifty-demo-icons.min.css" rel="stylesheet">
	<link href="<?= base_url(); ?>/public/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<link href="<?= base_url(); ?>/public/assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
	<link href="<?= base_url(); ?>/public/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
	<link href="<?= base_url(); ?>/public/assets/bootstrap4-toggle/css/bootstrap4-toggle.min.css" rel="stylesheet">
	<link href="<?= base_url(); ?>/public/assets/css/common.css" rel="stylesheet">
	<style>
		.row {
			line-height: 25px;
		}

		@media print {
			#content-container {
				padding-top: 0px;
			}

			.water_mark {
				display: none;
				width: 99%;
				position: absolute;
				top: 33%;
				/*z-index: -1;*/
				text-align: center;
			}

			.water_mark img {
				opacity: 0.31;
			}

			#content-container {
				/* background-image: url("<?= base_url(); ?>/public/assets/img/logo/<?= $ulb_dtl['ulb_mstr_id'] ?>.png"); */
			}
		}

		.water_mark {
			display: inline-block;
			width: 99%;
			position: absolute;
			top: 33%;
			/*z-index: -1;*/
			text-align: center;
		}

		.water_mark img {
			opacity: 0.31;
		}
	</style>
</head>

<body>


	<!-- <?php print_var($denialNoticeDetails) ?> -->

	<!-- ======= Cta Section ======= -->

	<div id="page-content">
		<div class="row">
			<div class="col-sm-12">
				<div class="panel">

					<!-- ======= Cta Section ======= -->
					<div class="panel panel-bordered panel-dark">
						<div class="panel-body" id="print_watermark">
							<div class="col-sm-1"></div>
							<div class="col-sm-10" style="text-align: center;">
							</div>
							<div class="col-sm-1 noprint text-right">
								<button class="btn btn-mint btn-icon" onclick="print()"><i class="demo-pli-printer icon-lg"></i></button>
							</div>

							<div class="col-sm-12" style="text-align: center; text-transform: uppercase; font-size: 23px; font-weight: bold; font-family: Arial, Helvetica, sans-serif; line-height: 30px; ">
								<!-- <img style="height:80px;width:80px;float:left" src='<?php echo base_url('public/assets/img/ulb_logo/1.png'); ?>'> -->
								<!-- <div class="col-sm-10" style="text-align: center;"> -->
								<img style="height:80px;width:80px; " src='<?php echo base_url('public/assets/img/logo1.png'); ?>'>
								<!-- </div> <br> -->
								<!-- <img style="height:80px;width:80px;float:left" src='<?php echo base_url('public/assets/img/logo1.png'); ?>'> -->
								<div>
									<?= isset($ulb_dtl) ? $ulb_dtl['ulb_name_hindi'] : "राँची नगर निगम राँची" ?>
									राजस्व शाखा <br>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;नोटिस
								</div>
							</div>

							<div class="col-sm-12" style="text-align:right; text-transform: uppercase; font-size:18px; font-weight: bold; font-family: Arial, Helvetica, sans-serif; ">
								<a style="font-weight:500;">पत्रांक</a> :<b><?= $denialNoticeDetails['notice_no'] ?></b><br>
								<a style="font-weight:500;">दिनांक </a>:<b><?= date("d-m-Y") ?></b>
							</div>

							<div class="col-sm-12" style="text-align:left; text-transform: uppercase; font-size:18px; font-weight: bold; font-family: Arial, Helvetica, sans-serif; ">

								<a style="font-weight:500;">प्रेषित</a>,<br>
								<a style="font-weight:500;"> व्यवसाय का नाम </a>: <?= $denialNoticeDetails['firm_name'] ?><br>
								<a style="font-weight:500;"> नाम </a>: <?= $denialNoticeDetails['applicant_name'] ?><br>
								<a style="font-weight:500;"> पता </a>: <?= $denialNoticeDetails['address'] ?><br>
								<a style="font-weight:500;"> मो० न० </a>: <?= $denialNoticeDetails['mob_no'] != '' ? $denialNoticeDetails['mob_no'] : 'N/A' ?>
							</div>

							<div class="col-sm-12" style="text-transform: uppercase; font-size:16px;  font-family: Arial, Helvetica, sans-serif; line-height:20px;">
								<br>
								&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;बजरिये नोटिस आपको सूचित किया जाता है कि <b><?= isset($ulb_dtl) ? $ulb_dtl['ulb_name_hindi'] : "राँची नगर निगम राँची" ?></b> क्षेत्र में किसी भी भवन का गैर आवासीय उपयोग करने के लिए झारखण्ड नगरपालिका अधिनियम, 2011 की धारा 455 के तहत म्यूनिसिपल अनुज्ञप्ति प्राप्त करना अनिवार्य है।
							</div>

							<div class="col-sm-12" style="text-transform: uppercase; font-size:16px;  font-family: Arial, Helvetica, sans-serif; line-height:20px;">
								<br>
								&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;अधोहस्ताक्षरी के संज्ञान में यह लाया गया है कि आपके द्वारा उपर्युक्त भवन का गैर आवासीय उपयोग बिना म्यूनिसिपल अनुज्ञप्ति प्राप्त किया जा रहा है, जो कि झारखण्ड नगरपालिका अधिनियम, 2011 की धारा 455 का उल्लंघन है। यदि आपके पास भवन के 
								गैर-आवासीय उपयोग हेतु म्यूनिसिपल अनुज्ञप्ति प्राप्त है तो जन सुविधा केन्द्र या निगम समर्थित Sri Publication & Stationers Pvt. Ltd. के प्रतिनिधि को उपलब्ध करायें।
								<b> (कृपया नोटिस की छायाप्रति भी साथ में संलग्न करें)</b>
							</div>

							<div class="col-sm-12" style="text-transform: uppercase; font-size:16px;  font-family: Arial, Helvetica, sans-serif; line-height:20px;">
								<br>
								&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;अतएव आपको निर्देशित किया जाता है कि नोटिस प्राप्ति के तीन दिनों के अन्दर उपर्युक्त भवन के लिए म्यूनिसिपल अनुज्ञप्ति प्राप्त कर लें अथवा भवन का गैर आवासीय उपयोग बंद कर लें तथा अधोहस्ताक्षरी को सूचित करें , अन्यथा झारखण्ड नगरपालिका अधिनियम की धारा 187 एवं 600 के तहत कार्रवाई प्रारम्भ की जायेगी एवं झारखण्ड नगरपालिका व्यापार अनुज्ञप्ति नियमावली 2017 के नियम 19 तथा 20 के तहत कार्रवाई की जायेगी। 
							</div>

							<div class="col-sm-12" style="text-transform: uppercase; font-size:16px;  font-family: Arial, Helvetica, sans-serif; line-height:20px; text-align:center;">
								<br>
								&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<b>नोटः 
									<!-- -<?= isset($ulb_dtl) ? $ulb_dtl['ulb_name_hindi'] : "राँची नगर निगम राँची" ?> -->
									 इसे अति आवश्यक समझें।
									</b>
							</div>
							<div class="col-sm-8" style="text-align:right; text-transform: uppercase; font-size:16px; font-weight: bold; font-family: Arial, Helvetica, sans-serif; line-height:43px;">
							</div>
							<div class="col-sm-1" style="text-align:right; text-transform: uppercase; font-size:16px; font-weight: bold; font-family: Arial, Helvetica, sans-serif; line-height:43px;">
							</div>

							<div class="col-sm-3" style="text-align:right;text-transform: uppercase; font-size:16px; font-weight: bold; font-family: Arial, Helvetica, sans-serif; line-height:20px;">
								<br>
								<!-- <img style="height:80px;width:80px;margin-right: 80px;" src='<?= base_url('/writable/eo_sign/notice_signature.png') ?>'><br> -->
								<img style="height:80px;width:80px;margin-right: 80px;" src='<?= $signature_path; ?>'><br>
								<!-- <p style="font-weight:500;margin-right: 50px;"><b>निबंधन पदाधिकारी,</b></p>
								<p style="font-weight:500;margin-right: 100px;">-सह-</p> -->
								<!-- <p style="font-weight:500; margin-right: 50px;"><b>अपर प्रशासक,</b></p> -->
								<!-- <p style="font-weight:500; margin-right: 50px;"><b>उप प्रशासक,</b></p> -->
								<p style="font-weight:500; margin-right: 50px;"><b><?=$degignation?>,</b></p>
								<p style="font-weight:500;margin-right: 50px;"><?= isset($ulb_dtl) ? $ulb_dtl['ulb_name_hindi'] : "राँची नगर निगम राँची" ?></p>
							</div>

						</div>
						<!-- <div class="water_mark"><img src="<?= base_url(); ?>/public/assets/img/logo/<?= $ulb_dtl['ulb_mstr_id'] ?>.png"/></div> -->
					</div>
				</div>
			</div>
		</div>
	</div>