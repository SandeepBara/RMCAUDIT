<?php
if(!function_exists('isLogin')){
   function isLogin(){
		$session = session();
		if(!$session->has('emp_details')){
			return false;
		}else{
			return true;
		}
	}
}

/*if(!function_exists('dbConfig')){
   function dbConfig($db_type = null){
    	$session = session();
		if(!$session->has('ulb_dtl')){
			return false;
		}else{
			$db_array = array("property", "water", "trade", "advertisement");
			if (!in_array($db_type, $db_array)){
				return false;
			}else{
				$db_name = $session->get('ulb_dtl');
				return $db_name[$db_type];
			}
		}
   }
}*/

if(!function_exists('dbConfig')){
   function dbConfig($db_type = null){
   		$db_array = array("property", "water", "trade", "advertisement");
		if (!in_array($db_type, $db_array)){
			return false;
		}else{
			if ($db_type=="property") {
				return "db_rmc_property";
			}
			if ($db_type=="water") {
				return "db_rmc_water";
			}
			if ($db_type=="trade") {
				return "db_rmc_trade";
			}
			if ($db_type=="advertisement") {
				return "db_rmc_advertisement";
			}
		}
   }
}

if(!function_exists('dbSystem')){
   function dbSystem(){
      return "db_system";
   }
}

if(!function_exists('getEmpDetails')){
	function getEmpDetails(){
		 $session = session();
		 if($session->has('emp_details')){
			return $session->get('emp_details');
		 } else {
			return false;
		 }
	}
}
 
if(!function_exists('escapeData')){
   function escapeData($escapeData){
      return preg_replace("/[^a-zA-Z]+/", "", $escapeData);
   }
}
if(!function_exists('sanitizeString')){
	function sanitizeString($input){
		return  pg_escape_string(filter_var(trim($input), FILTER_SANITIZE_STRING));
	}
}
if(!function_exists('arrFilterSanitizeString')){
   function arrFilterSanitizeString($arrInputs){
   		$inputs = [];
   		foreach ($arrInputs as $key => $value) {
			if(is_array($value)){
				foreach ($value as $arrKey => $ArrVal) {
					$inputs[$key][$arrKey] = pg_escape_string(filter_var(trim($ArrVal), FILTER_SANITIZE_STRING));
				}
			}else{
				$inputs[$key] = pg_escape_string(filter_var(trim($value), FILTER_SANITIZE_STRING));
			}
		}
	  	return $inputs;
   	}
}

if(!function_exists('filterSanitizeStringtoUpper'))
{
   function filterSanitizeStringtoUpper($arrInputs){
   		$inputs = [];
   		foreach ($arrInputs as $key => $value) {
			if(is_array($value)){
				foreach ($value as $arrKey => $ArrVal) {
					$inputs[$key][$arrKey] = strtoupper(pg_escape_string(filter_var(trim($ArrVal), FILTER_SANITIZE_STRING)));
				}
			}else{
				$inputs[$key] = strtoupper(pg_escape_string(filter_var(trim($value), FILTER_SANITIZE_STRING)));
			}
		}
	  	return $inputs;
   	}
}

if(!function_exists('in_num_format'))
{
   function in_num_format($number){
		return preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $number);
   	}
}

if(!function_exists('getFY')){
   	function getFY($date = null){
   		if(is_null($date)){
   			$MM = date("m");
   			$YY = date("Y");
   		}else{
   			$MM = date("m", strtotime($date));
   			$YY = date("Y", strtotime($date));
   		}
   		if ($MM > 3) {
			return ($YY)."-".($YY+1);
		}else{
			return ($YY-1)."-".($YY);
		}
   	}
}

if(!function_exists("getQtr")){
	function getQtr($date = null){
		if(is_null($date)){
			$MM = date("m");
			$YY = date("Y");
		}else{
			$MM = date("m", strtotime($date));
			$YY = date("Y", strtotime($date));
		}if ($MM >=1 && $MM <= 3) {
			return 4;
		}elseif($MM >=4 && $MM <= 6){
			return 1;
		}elseif($MM >=7 && $MM <= 9){
			return 2;
		}else{
			return 3;
		}
	}
}

/*if(!function_exists('flashToast')){
	function flashToast($name = '', $message = ''){
		$session = session();
		if(!empty($name)){
			if(!empty($message) && empty($_SESSION[$name]))
			{
				if(!empty($_SESSION[$name]))
				{
					unset($_SESSION[$name]);
				}
				$_SESSION[$name] = $message;
				$returnData = true;

			}
			elseif(empty($message) && !empty($_SESSION[$name]))
			{
				$returnData = $_SESSION[$name];
				unset($_SESSION[$name]);
				return $returnData;
			}
			else
			{
				$returnData = false;
			}
		}
		else
		{
			$returnData = false;
		}
		return false;
	}
}*/

if(!function_exists('flashToast')){
	function flashToast($name = '', $message = ''){	
		if($_REQUEST["cmd"]??false){
			return false;
		}
		if(!empty($name)){
			if(!empty($message) && empty(cGetCookie($name)))
			{
				if(!empty(cGetCookie($name)))
				{
					cDeleteCookie($name);
				}
				cSetCookie($name, $message);
				$returnData = true;

			}
			elseif(empty($message) && !empty(cGetCookie($name)))
			{
				$returnData = cGetCookie($name);
				cDeleteCookie($name);
				return $returnData;
			}
			else
			{
				cDeleteCookie($name);
				$returnData = false;
			}
		}
		else
		{
			$returnData = false;
		}
		return false;
	}
}

if(!function_exists('floor_date_compare')){
	function floor_date_compare($element1, $element2) { 
		$datetime1 = strtotime($element1['date_from']."-01"); 
		$datetime2 = strtotime($element2['date_from']."-01"); 
		return $datetime1 - $datetime2; 
	} 
}

if(!function_exists('vacant_date_compare')){
	function vacant_date_compare($element1, $element2) { 
		$datetime1 = strtotime($element1['installation_date']); 
		$datetime2 = strtotime($element2['installation_date']); 
		return $datetime1 - $datetime2; 
	} 
}

if(!function_exists('vacant_date_compare2')){
	function vacant_date_compare2($element1, $element2) { 
		$datetime1 = strtotime($element1['effected_from']); 
		$datetime2 = strtotime($element2['effected_from']); 
		return $datetime1 - $datetime2; 
	} 
}
if(!function_exists('effective_date_water')){
	function effective_date_water($element1, $element2) { 
		$datetime1 = strtotime($element1['effective_date']."-01"); 
		$datetime2 = strtotime($element2['effective_date']."-01"); 
		return $datetime1 - $datetime2; 
	} 
}
if(!function_exists('hashEncrypt')){
	function hashEncrypt($encData){
		$secret_key = 'key=@MVC';
		$secret_iv = 'secret_iv';
		$encrypt_method = "AES-256-CBC";
		$key = hash('sha256', $secret_key);
		$iv = substr(hash('sha256', $secret_iv), 0, 16);
		return base64_encode(openssl_encrypt($encData, $encrypt_method, $key, 0, $iv));
	}
}
if(!function_exists('hashDecrypt')){
	function hashDecrypt($decData){
		$secret_key = 'key=@MVC';
		$secret_iv = 'secret_iv';
		$encrypt_method = "AES-256-CBC";
		$key = hash('sha256', $secret_key);
		$iv = substr(hash('sha256', $secret_iv), 0, 16);
		return openssl_decrypt(base64_decode($decData), $encrypt_method, $key, 0, $iv);
	}
}

if(!function_exists('httpPost')) {
	function httpPost($url, $params = null, $http = null) {
		if($http==null)
		$url = base_url().'/'.ltrim($url, '/');
		else{
		$url = $http.$url;
		}
		
		$postData = '';
		if($params!=null){
		foreach($params as $k => $v) { 
			$postData .= '&'.$k . '='.$v; 
		}
		}
		$postData = rtrim($postData, '&');
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, false); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);    
		$output=curl_exec($ch);
		if($output === false){
			echo "<script>console.log('".curl_errno($ch)."');</script>";
			echo "<script>console.log('".curl_error($ch)."');</script>";
		}
		curl_close($ch);
		return $output;
	}
}

if(!function_exists('httpPostJson')) {
	function httpPostJson($url, $params = null, $http = null) {
		if($http==null)
		$url = base_url().'/'.ltrim($url, '/');
		else{
		$url = $http.$url;
		}		
		$postData = '';
		if($params!=null){
			$postData = json_encode($params);
		/* 	foreach($params as $k => $v) { 
				$postData .= '&'.$k . '='.$v; 
			} */
		}
		$postData = rtrim($postData, '&');
		print_var($postData);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, false); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$output=curl_exec($ch);
		if($output === false){
			echo "<script>console.log('".curl_errno($ch)."');</script>";
			echo "<script>console.log('".curl_error($ch)."');</script>";
			
		}
		curl_close($ch);
		return $output;
	}
}
if(!function_exists('httpPostHeaderJson')) {
	function httpPostHeaderJson($url, $params = null, $token_no = null, $http = null) {
		if($http==null)
		$url = base_url().'/'.ltrim($url, '/');
		else{
		$url = $http.$url;
		}		
		$postData = '';
		if($params!=null){
			$postData = json_encode($params);
		/* 	foreach($params as $k => $v) { 
				$postData .= '&'.$k . '='.$v; 
			} */
		}
		$postData = rtrim($postData, '&');
		// print_var($postData);
		/* $headers = [];
		if ($token_no!=null) {
			$headers[] = "Authorization: Bearer ".$token_no;
			$headers[] = "Accept: application/json";
		} */
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , "Authorization: Bearer ".$token_no));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$output=curl_exec($ch);
		if($output === false){
			echo "<script>console.log('".curl_errno($ch)."');</script>";
			echo "<script>console.log('".curl_error($ch)."');</script>";
			
		}
		curl_close($ch);
		return $output;
	}
}
if(!function_exists('loginCaptchaCitizen')){
	function loginCaptchaCitizen(){
		//$session = session();
		$rand = rand(1111, 9999);
		//$session->set('loginCaptchaSession', $rand);
		cSetCookie("loginCaptchaSessionCitizen", $rand);
		
		$font = ROOTPATH."public/assets/fonts/ransom/RansomItalic.ttf";
		//$font = ROOTPATH."public/assets/fonts/ransom/font.ttf";
		$bgColor=array('r'=>224, 'g'=>255, 'b'=>255);
		$textColor=array('r'=>36, 'g'=>110, 'b'=>183);
		//$textColor=array('r'=>37, 'g'=>71, 'b'=>106);
		
        $tmp=tempnam( sys_get_temp_dir(), 'img' );
		
		$w=120; $h=40;
        $image = imagecreate( $w, $h );
        $bck = imagecolorallocate( $image, $bgColor['r'], $bgColor['g'], $bgColor['b'] );
        $txt = imagecolorallocate( $image, $textColor['r'], $textColor['g'], $textColor['b'] );
		

		for($i=0; $i < 10; $i++) {
			$linecolor = imagecolorallocate($image, rand(0, 250), rand(50, 250), rand(100, 255));
			imageline($image, 0, rand(10,40), rand(100, 250), rand(5,50), $linecolor);
		}
        //imagestring( $image, 5, 10, 2, $str, $txt );
		imagettftext($image, 28, rand(-7, 7), 25, 35, $txt, $font, $rand);
        imagepng( $image, $tmp );
        imagedestroy( $image );

        $data=base64_encode( file_get_contents( $tmp ) );
        @unlink( $tmp );
        return 'data:image/png;base64,'.$data;
    }
}

if(!function_exists('loginCaptcha')){
	function loginCaptcha(){
		//$session = session();
		$rand = rand(1111, 9999);
		//$session->set('loginCaptchaSession', $rand);
		helper(["cookie"]);
		cSetCookie("loginCaptchaSession", $rand);
		$font = ROOTPATH."public/assets/fonts/ransom/RansomItalic.ttf";
		//$font = ROOTPATH."public/assets/fonts/ransom/font.ttf";
		$bgColor=array('r'=>224, 'g'=>255, 'b'=>255);
		$textColor=array('r'=>36, 'g'=>110, 'b'=>183);
		//$textColor=array('r'=>37, 'g'=>71, 'b'=>106);
		
        $tmp=tempnam( sys_get_temp_dir(), 'img' );
		
		$w=120; $h=40;
        $image = imagecreate( $w, $h );
        $bck = imagecolorallocate( $image, $bgColor['r'], $bgColor['g'], $bgColor['b'] );
        $txt = imagecolorallocate( $image, $textColor['r'], $textColor['g'], $textColor['b'] );
		

		for($i=0; $i < 10; $i++) {
			$linecolor = imagecolorallocate($image, rand(0, 250), rand(50, 250), rand(100, 255));
			imageline($image, 0, rand(10,40), rand(100, 250), rand(5,50), $linecolor);
		}
        //imagestring( $image, 5, 10, 2, $str, $txt );
		imagettftext($image, 28, rand(-7, 7), 25, 35, $txt, $font, $rand);
        imagepng( $image, $tmp );
        imagedestroy( $image );

        $data=base64_encode( file_get_contents( $tmp ) );
        @unlink( $tmp );
        return 'data:image/png;base64,'.$data;
    }
}



if(!function_exists('calculate_duration')){
	function calculate_duration($fromdate, $todate=NULL){
		
		if($todate==NULL || $todate == "")
			$todate = date('Y-m-d h:i:s');
		
		$date1=date_create(date("Y-m-d h:i:s", strtotime($fromdate)));
      $date2=date_create(date("Y-m-d h:i:s", strtotime($todate)));
      return $diff=date_diff($date1, $date2)->format("%a Days %h Hour %i Minute");
    }
}

if(!function_exists('print_var')){
	function print_var($var){
		print('<pre>'. print_r($var, true) . '</pre>');
    }
}

if(!function_exists('get_client_ip'))
{
	function get_client_ip()
	{
		//echo file_get_contents("https://jsonip.com/");
		return $_SERVER['REMOTE_ADDR'];
    }
}

if(!function_exists('receipt_url'))
{
	function receipt_url()
	{
		return 'For Details Please Visit : udhd.jharkhand.gov.in <br>
		OR Call us at 1800 8904115 or 0651-3500700';
    }
}

if(!function_exists('Collaboration'))
{
	function Collaboration ()
	{
		return 'Sri Publication & Stationers Pvt. Ltd.<br>
				Ashok Nagar,<br>
				Ranchi - 834002';
    }
}

function getIndianCurrency(float $number)
{
    $decimal = round($number - ($no = floor($number)), 2) * 100;
    $hundred = null;
    $digits_length = strlen($no);
    $i = 0;
    $str = array();
    $words = array(0 => '', 1 => 'One', 2 => 'Two',
        3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
        7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
        10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
        13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
        16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
        19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
        40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
        70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
    $digits = array('', 'hundred','thousand','lakh', 'crore');
    while( $i < $digits_length ) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
        } else $str[] = null;
    }
    $Rupees = implode('', array_reverse($str));
    $paise = ($decimal > 0) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
    return ($Rupees ? $Rupees . 'Rupees ' : 'Zero Rupee') . $paise;
}

if(!function_exists('cSetCookie')){
    function cSetCookie($cookie_name, $cookie_value) { 
		if (is_array($cookie_value)) {
			setcookie($cookie_name, json_encode($cookie_value), time()+(86400*30), "/");
		} else {
			setcookie($cookie_name, $cookie_value, time()+(86400*30), "/");
		}
        
        if (!isset($_COOKIE[$cookie_name])) {
            return false;
        }
        return true;
    } 
}
if(!function_exists('cGetCookie')){
    function cGetCookie($cookie_name) { 
        if (!isset($_COOKIE[$cookie_name])) {
            return false;
        }
		if (isJson($_COOKIE[$cookie_name])) {
			return json_decode($_COOKIE[$cookie_name], true);
		} else {
			return $_COOKIE[$cookie_name];
		}
        
    } 
}
if(!function_exists('cDeleteCookie')){
    function cDeleteCookie($cookie_name) { 
        if (isset($_COOKIE[$cookie_name])) {
			unset($_COOKIE[$cookie_name]); 
    		setcookie($cookie_name, null, -1, '/'); 
			return true;
        }
		return true;
    } 
}
if(!function_exists('cHasCookie')){
    function cHasCookie($cookie_name) { 
        if (!isset($_COOKIE[$cookie_name])) {
            return false;
        }
		return true;
    } 
}
if(!function_exists('isJson')){
	function isJson($string) {
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
   	}
}

if(!function_exists('logger_on_off')){
    function logger_on_off($on_off = false) { 
        if ($on_off) {
			$info = [
				'time'		 => date('Y-m-d H:i:s'),
				'ip_address' => getClientIpAddress(),
				'url'		 => current_url()
			];
			log_message('info', '{time}, {ip_address}, {url}', $info);
		}
    } 
}

if(!function_exists('getClientIpAddress')){
	function getClientIpAddress()
	{
		if (!empty($_SERVER['HTTP_CLIENT_IP']))   //Checking IP From Shared Internet
		{
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //To Check IP is Pass From Proxy
		{
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		return $ip;
	}
}
if(!function_exists('getUlbDtl')){
	function getUlbDtl() {
		return [
			"ulb_mstr_id" => 1,
			"logo_path" => "/muncipalicon/RMC_LOGO.jpg",
			"watermark_path" => "/img/logo/1.png",
			"property" => "db_rmc_property",
			"water" => "db_rmc_water",
			"trade" => "db_rmc_trade",
			"advertisement" => "db_rmc_advertisement",
			"state" => "JHARKHAND",
			"district" => "RANCHI",
			"city" => "RANCHI",
			"ulb_type_id" => "1",
			"short_ulb_name" => "RMC",
			"ulb_name" => "Ranchi Municipal Corporation",
			"ulb_name_hindi" => "राँची नगर निगम"
		];
	}
}

#document server 
if(!function_exists("DocServer"))
{
	function DocServer(string $folders)
	{
		$waterFolders = ["AverageBilling", "basic_dtl_update", "consumer_doc", "consumer_update", "declaration_doc_dtl", "Demand_deactivation", "meter_image", "meter_reading", "name_transfer", "SSPL_WATER_DOC", "sspl_water_doc_new", "water_consumer_deactivation", "water_doc_dtl", "water_new_apply_connection_deactivation", "water_payment_mode_update", "water_transaction_deactivation", "WaterServey","water_request_doc" ];	
		$tradeFolders = ["denial", "denial_approved_notice", "denial_image", "denial_notice", "denialApprovedImage", "EoSign", "trade_doc_dtl", "trade_licence_deactivate", "trade_new_connection", "trade_transaction_deactivation", "trade_update_apply_licence", "trade_water", "Trade" ];
		$subForder = ((explode("RANCHI",$folders)[1])??"");
		$subForder = trim($subForder,"/");
		$subForder = ((explode("/",$subForder)[0])??"");
		// print_var($subForder);
		$drive = "D";#default;
		if(in_array($subForder,$waterFolders) || in_array($subForder,$tradeFolders))
		{
			$drive = "E";
		}
		return ["drive"=>$drive,"server"=>"http://10.92.197.186/RMCDMC/public/api/doc-server/"];
	}
}

if(!function_exists("deleteFile"))
{
	function deleteFile(string $folders)
	{
		$server = $target_url = (DocServer($folders));
		$target_url =$server["server"] ."delete";
		$drive = $server["drive"];
		$postData = ["targetPath"=>$folders,"drive"=>$drive];
		$curl = curl_init();		
		curl_setopt($curl, CURLOPT_URL, $target_url);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		$response = json_decode(curl_exec($curl),true);
		// print_var($response);die;
		if ($response["status"] == false) {
			return false;
		} 
		return true;
	}
}


if(!function_exists("getQtrFistDate")){
	function getQtrFistDate($fyear,$qtr){
		list($fromYear,$uptoYear) = explode("-",$fyear);
		$fromdate="";
		switch($qtr){
			case 1 : $fromdate=$fromYear."-04-01";
				break;
			case 2 : $fromdate=$fromYear."-07-01";
				break;
			case 3 : $fromdate=$fromYear."-10-01";
				break;
			case 4 : $fromdate=$uptoYear."-01-01";
				break;
		}		
		return $fromdate;
	}
}
if(!function_exists("getQtrLastDate")){
	function getQtrLastDate($fyear,$qtr){
		list($fromYear,$uptoYear) = explode("-",$fyear);
		$fromdate="";
		switch($qtr){
			case 1 : $fromdate=$fromYear."-06-30";
				break;
			case 2 : $fromdate=$fromYear."-09-30";
				break;
			case 3 : $fromdate=$fromYear."-12-31";
				break;
			case 4 : $fromdate=$uptoYear."-03-31";
				break;
		}
		return $fromdate;
	}
}


if(!function_exists("inMaintenanceServer")){
	function inMaintenanceServer(){
		if(is_cli()){
			return true;
		}
		$allowControllers= [
			'Login', 'Prop_report', 'AllModuleDCBReport', 'Dashboard', 'test', 'AllModuleCollection', 'AllModuleCollectionReport',"jsk",
			'AllmoduleCollectionSummary_TCwise', 'MiniDashboard', 'water_report', 'WaterConsumerWiseDCBReport', 'Trade_report', 
			'BOC_SAF', 'SafDoc', 'BO_SAF', 'Safdtl', 'SAF', 'WaterCollectionReport', 
			'BankReconciliationAllModuleList','WaterWardWiseDCBReport', 
			'TradeTCWiseCollectionReports',"tools"
		];

		$blockController=[
			"JSK"=>[
				"propertyPaymentProceed",
			],
			"SAF"=>[
				"AddUpdate2"
			],
			"safDemandPayment"=>[
				"safPaymentProceed",
			]
		];


		$allowControllers = array_map(function($val){
			return strtoupper($val);
		},$allowControllers);

		$blockController = arrayToUppercaseRecursive($blockController);

		$router = service('router'); 
		$controller  = $router->controllerName();
		$app_path = explode("\\",$controller);
		$controllerName = $app_path[3];	
		$methodName = $router->methodName();	
		$controller_arr = $allowControllers;
		$fyear = getFY();
		list($fromYear,$uptoYear)=explode("-",$fyear);
		$fromDate = $fromYear."-04-01";
		$uptoDate = $fromYear."-04-15";
		$cr_date = strtotime(date('d-m-Y'));
		$db_system = db_connect(dbSystem());
		
		$test = $db_system->query("select is_site_up from site_maintenance")->getFirstRow("array");
		if($test["is_site_up"]=="t" && (!(strtoupper($controllerName)==strtoupper("tools")))){
			// echo view('maintenance',["fromMaintenance"=>$fromDate,"uptoMaintenance"=>$uptoDate]);
			// die;
		}

		elseif(!in_array(strtoupper($controllerName), $controller_arr) && $fromDate<=date('Y-m-d') && $uptoDate>=date('Y-m-d')){
			// echo view('maintenance',["fromMaintenance"=>$fromDate,"uptoMaintenance"=>$uptoDate]);
			// die();
		}

		$methodList= $blockController[strtoupper($controllerName)]??[];
		if($methodList && $fromDate<=date('Y-m-d') && $uptoDate>=date('Y-m-d')){
			if(in_array(strtoupper($methodName),$methodList)){
				// echo view('maintenance',["fromMaintenance"=>$fromDate,"uptoMaintenance"=>$uptoDate]);
				// die();
			}
		}
	}
}

if (!function_exists("arrayToUppercaseRecursive")){
	function arrayToUppercaseRecursive($array) {
		$array = array_change_key_case($array, CASE_UPPER);
		return array_map(function ($value) {
			if (is_array($value)) {
				return arrayToUppercaseRecursive($value);
			}
			return strtoupper($value);
		}, $array);
	}
}



if(!function_exists("blockPaymentApp")){
	function blockPaymentApp(){
		if(is_cli()){
			return true;
		}
		$router = service('router'); 
		$request = service('request');
        $response = service('response');
		$uri     = service('uri');
		$modelApplicationBlockPayment = new \App\Models\DbSystem\ModelApplicationBlockPayments(db_connect("db_system"));
		$blockController=[
			"Property"=>[
				"JSK"=>[
					"propertyPaymentProceed",
					// "jsk_due_details",
					"holding_demand_print",
				],
				"CitizenProperty"=>[
					// "Citizen_confirm_payment",
					"Citizen_due_details",
				],
				"PayOnline"=>[
					"propertyPaymentProceed",
				],
				"Mobi"=>[
					"confirm_payment",
				],
			],
		];
		$blockController = arrayToUppercaseRecursive($blockController);
		$controller  = $router->controllerName();
		$app_path = explode("\\",$controller);
		$controllerName = $app_path[3];	
		$methodName = $router->methodName();
		$segments = $uri->getSegments(); 
		$extraParams = array_slice($segments, 2);
		$appId = $extraParams[0]??null;
		$module=null;
		// dd($controllerName,$methodName,$app_path,$extraParams,$blockController);
		$propertyMethodList= $blockController["PROPERTY"][strtoupper($controllerName)]??[];
		if($propertyMethodList && in_array(strtoupper($methodName),$propertyMethodList)){
			$module="PROPERTY";			
		}
		if($module){			
			if(!is_numeric($appId)){				
				$builder=$modelApplicationBlockPayment->where(["md5(app_id::text)"=>$appId,"upper(modules)"=>strtoupper($module)])->get()->getFirstRow('array');
			}else{
				$builder=$modelApplicationBlockPayment->where(["app_id"=>$appId,"upper(modules)"=>strtoupper($module)])->get()->getFirstRow('array');
			}
			if($builder){
				echo view("errors/html/blockPayment");
				die;
			}

		}
	}
}

if (!function_exists("checkMultipleLogin")){
	function checkMultipleLogin(){
		if(is_cli()){
			return true;
		}

		return true;
		$request = service('request');
        $response = service('response');
		$session = session();
		$userId = $session->get('emp_details')["id"]??null;
		$currentSessionId = $session->get('session_id');
		$token = $session->get("login_token");

		if ($userId) {
			$empDtl = new \App\Models\model_emp_details (db_connect("db_system"));
			$login_details = new \App\Models\model_login_details(db_connect("db_system"));
			// $user = $login_details->where(["emp_details_id"=>$userId])->orderBy("id","DESC")->get()->getFirstRow('array');
			// ✅ Step 1: Get user login limit (you must define how/where this is set)
			$user = $empDtl->where(["id"=>$userId])->get()->getFirstRow('array');
			$loginLimit = $user['login_limit'] ?? 1; // default 1 login if not defined

			// ✅ Step 2: Get all active sessions for this user
			$activeSessions = $login_details
				->where(["emp_details_id" => $userId])
				->orderBy("id", "ASC") // oldest first
				->limit(300)
				->get()
				->getResultArray();

			$activeCount = count($activeSessions);

			// ✅ Step 3: If current session is missing or invalid
			$found = false;
			foreach ($activeSessions as $s) {
				if ($s['token'] === $token) {
					$found = true;
					break;
				}
			}

			// Session is invalid
			if (!$found && !$request->isAJAX()) {
				archive_login_session($currentSessionId);
				$session->destroy();				
				echo view('users/login');
				exit;
			}

			// ✅ Step 4: If login count exceeds limit, logout oldest sessions
			if ($activeCount > $loginLimit) {
				$sessionsToLogout = array_slice($activeSessions, 0, $activeCount - $loginLimit);
				foreach ($sessionsToLogout as $s) {
					archive_login_session($s['session_id']); // archive old
					$login_details->where('id', $s['id'])->delete(); // remove from active table
				}
			}





			// if ((!$user || $user['session_id'] !== $currentSessionId) && !$request->isAJAX()) {
			// 	archive_login_session($currentSessionId);
			// 	$session->destroy();
			// 	echo"<script> 
			// 	document.addEventListener('DOMContentLoaded', function () {
			// 		alert('You are logout due to login in another system');
			// 	});
			// 	</script>";
			// 	echo view('users/login');
			// 	exit;
			// }
		}
	}
}

if(!function_exists("archive_login_session")){
	function archive_login_session($sessionId,$removeFile=true)
	{
		$db = db_connect("db_system");
		$builder = $db->table('tbl_login_details');
		$historyBuilder = $db->table('tbl_login_detail_history');
	
		// Get active session data
		$sessionData = $builder->where('session_id', $sessionId)->get()->getRowArray();
	
		if ($sessionData) {
			// Add logout time and reason before inserting to history
			$sessionData['logout_time'] = date('Y-m-d H:i:s');
			$sessionData['logout_reason'] = 'Logged in from another system';
	
			// Insert into history table
			$historyBuilder->insert($sessionData);
	
			// Delete from active login table
			$builder->where('session_id', $sessionId)->delete();
		}
		$sessionDriver = config('App')->sessionDriver;

		if ($sessionDriver === 'CodeIgniter\Session\Handlers\FileHandler') {
            $sessionFile = WRITEPATH . 'session/'.$sessionId;
            if (file_exists($sessionFile) && $removeFile) {
                @unlink($sessionFile); // ⚠️ Only works if PHP is managing those sessions locally
            }
        }

	}
}


if(!function_exists("certificateType")){
	function certificateType($paymentMode,$qtr){
		if($qtr==1 && strtoupper($paymentMode)=="ONLINE"){
			$certiFicate="Platinum";
		}
		elseif($qtr==1){
			$certiFicate="Golden";
		}
		elseif($qtr==2){
			$certiFicate="Silver";
		}
		elseif($qtr==3){
			$certiFicate="Bronze";
		}else{
			$certiFicate="-----";
		}
		return $certiFicate;
	}
}

if(!function_exists("countingNumber")){
	function countingNumber(int $num) {
        // Handle numbers ending in 11, 12, or 13
        if (($num % 100) >= 11 && ($num % 100) <= 13) {
            return "th";
        }

        // Handle other numbers
        switch ($num % 10) {
            case 1:
                return "st";
            case 2:
                return "nd";
            case 3:
                return "rd";
            default:
                return "th";
        }
    }
}

if(!function_exists("PropCertificatList")){
    function PropCertificatList($appId,$type="Property"){
        $db_prop = db_connect(dbConfig("property"));
        $sql = "select md5(tbl_certificate.id::text) as encrypt_id,tbl_certificate.*,tbl_transaction.tran_no
                from tbl_certificate
                join  tbl_transaction on tbl_transaction.id = tbl_certificate.tran_id
                where tbl_transaction.status in(1) and tbl_transaction.tran_type='$type' ";
        if(!is_numeric($appId)){
            $sql.=" and md5(tbl_transaction.prop_dtl_id::text) = '$appId'";
        }else{
            $sql.=" and tbl_transaction.prop_dtl_id = '$appId'";
        }
		$sql.=" order by tbl_certificate.id ASC ";
        $data = $db_prop->query($sql)->getResultArray();		
        if($data){
			$newTree = '
			<style>
				#certificateTable th{
					position: sticky;
					top: 0;
					background: #f2f2f2; /* background is required */
					z-index: 1;
				}
			</style>
				<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#certificateModel">Certificate List</button>
				<div id="certificateModel" class="modal fade" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header" style="background-color: #25476a;">
								<button type="button" class="close" style="color: white; font-size:30px;" data-dismiss="modal">&times;</button>
								<h4 class="modal-title" style="color: white;">Certificate</h4>
							</div>
							<div class="modal-body">
								<div class="table-responsive" style="max-height:70vh;overflow:scroll;">
									<table id="certificateTable" class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
										<thead>
											<tr>
												<th> #</th>
												<th> Type</th>
												<th> Certificate Id</th>
												<th> Isu Date</th>
												<th> Fyear</th>
												<th> View</th>
											</tr>
										</thead>
										<tbody>


			';
			foreach ($data as $key=>$row) {
				$link = "PopupCenter('".(base_url("CitizenProperty/propPaymentCertificate/".$row["encrypt_id"]))."', 'Payment Receipt', 1024, 786)";
				$newTree.='<tr>
								<td>'.($key+1).'</td>
								<td>'.($row['type']??"").'</td>
								<td>'.($row['certificate_id']??"").'</td>
								<td>'.($row['is_date']?date('d-m-Y',strtotime($row['is_date'])):"").'</td>
								<td>'.($row['fyear']??"").'</td>
								<td> <a onclick="'.$link.'" href="#" class="btn btn-primary">View</a></td>
							<tr>
				';
			}
			$newTree.="
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			";			
			echo($newTree);
        }
    }
}


if(!function_exists("getClientIp")){
	function getClientIp() {
		$ipKeys = [
			'HTTP_CLIENT_IP',
			'HTTP_X_FORWARDED_FOR',
			'HTTP_X_FORWARDED',
			'HTTP_X_CLUSTER_CLIENT_IP',
			'HTTP_FORWARDED_FOR',
			'HTTP_FORWARDED',
			'REMOTE_ADDR'
		];
	
		foreach ($ipKeys as $key) {
			if (!empty($_SERVER[$key])) {
				$ipList = explode(',', $_SERVER[$key]);
				foreach ($ipList as $ip) {
					$cleanIp = trim($ip);
					if (filter_var($cleanIp, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
						return $cleanIp;
					}return $cleanIp;
				}
			}
		}
	
		return 'UNKNOWN';
	}
}

if(!function_exists("safList")){
	function safList($propId){
		$db_prop = db_connect(dbConfig("property"));
		$where="";
		if(!is_numeric($propId)){
			$where=" md5(id::text)='".$propId."'";
		}else{
			$where=" id='".$propId."'";
		}
		$propSql ="select * from tbl_prop_dtl where 1=1 and ".$where;
		$prop =  $db_prop->query($propSql)->getFirstRow("array");
		$safList = $db_prop->query("select *,'current' as type from tbl_saf_dtl where id = ".$prop["saf_dtl_id"])->getResultArray();
		$oldSaf = $db_prop->query("select *,'old' as type from tbl_saf_dtl where (prop_dtl_id =".$prop["id"] ." or previous_holding_id='".$prop["id"]."' ) and id != ".$prop["saf_dtl_id"])->getResultArray();
		
		$safList = array_merge($safList,$oldSaf);
		return $safList;
	}
}
