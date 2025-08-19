<?php
if(!function_exists('isEmail')){
	function isEmail($email){
		if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
		    return true;
		}else{
			return false;
		}
	}
}
if(!function_exists('isMobile')){
	function isMobile($mobile){
		if(!is_numeric($mobile)){
			return false;
		}
		return preg_match('/^[0-9]{10}+$/', $mobile); // 10 digit
	}
}
if(!function_exists('isPhone')){
	function isPhone($phone){
		if(!is_numeric($phone)){
			return false;
		}
		return preg_match('/^[0-9]{11}+$/', $phone); // 11 digit
	}
}
if(!function_exists('isAadhar')){
	function isAadhar($aadhar){
		if(!is_numeric($aadhar)){
			return false;
		}
		return preg_match('/^[0-9]{12}+$/', $aadhar); // 12 digit
	}
}
if(!function_exists('isDateFormat')){
	function isDateFormat($date){
		return preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date); 
		// date format (YYYY-MM-DD)
	}
}
if(!function_exists('isMonthFormat')){
	function isMonthFormat($date){
		return true;
		//return preg_match("/^[0-9]{4}-(0[1-9]|1[0-2]))$/", $date); 
		// date format (YYYY-MM-DD)
	}
}

if(!function_exists('isTimeFormat')){
	function isTimeFormat($time){
		return preg_match("/^(?:2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]$/", $time); 
		// time format (HH:MM:SS)
	}
}