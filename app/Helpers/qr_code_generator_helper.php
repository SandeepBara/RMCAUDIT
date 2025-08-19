<?php
  // Simple page redirect
/**
 * 
 */
include('qrCodeGenerator/phpqrcode/qrlib.php'); 
if(!function_exists('qrCodeGeneratorFun')){
	function qrCodeGeneratorFun($input){

		$tran_idd = rand();
		$text=$input;
		$folder = WRITEPATH."uploads/qrCodeGenerator/";
		$file_name=$tran_idd.'.png';
		 $folder=$folder.$file_name;
		 QRcode::png($text,$folder);
		 return $file_name;
	}
}

  			