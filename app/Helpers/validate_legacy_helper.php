<?php
if(!function_exists('validateLegacyEntry')){
   	function validateLegacyEntry($validate){
   		$errMsg = (array)null;

   		if(!isset($validate["ward_mstr_id"]) || $validate["ward_mstr_id"]==""){
            $errMsg["ward_mstr_id"] = "ward name is required";
        }
        if(!isset($validate["ownership_type_mstr_id"]) || $validate["ownership_type_mstr_id"]==""){
            $errMsg["ownership_type_mstr_id"] = "ownership type is required";
        }
        if(!isset($validate["prop_type_mstr_id"]) || $validate["prop_type_mstr_id"]==""){
            $errMsg["prop_type_mstr_id"] = "property type is required";
        }


		        if(isset($validate["arv"])
		        		&& isset($validate["date_from"])
		        		&& isset($validate["date_upto"])){

		        	for($i=0; $i<sizeof($validate["date_from"]); $i++){

			        	if($validate["arv"][$i]==""){
			        		$errMsg["arv"] = "arv is required";
			        	}
			        	if($validate["date_from"][$i]==""){
			        		$errMsg["date_from"] = "date from is required";
			        	}else{
			        		if(!isMonthFormat($validate["date_from"][$i])){
			        			$errMsg["date_from"] = "date from is invalid";
			        		}else{
								$date_from = $validate["date_from"][$i]."-01";
								if($date_from > date("Y-m-d")){
									$errMsg["date_from"] = "date from is invalid";
								}
							}
						}
						if($validate["date_upto"][$i]!=""){
			        		if(!isMonthFormat($validate["date_upto"][$i])){
			        			$errMsg["date_upto"] = "date upto is invalid";
			        		}else{
								$date_from = $validate["date_from"][$i]."-01";
								$date_upto = $validate["date_upto"][$i]."-01";
								if($date_from >= $date_upto || $date_upto > date("Y-m-d")){
									$errMsg["date_upto"] = "date upto is invalid";
								}
							}
			        	}
			        }
		        }else{
		        	$errMsg["demand_details"] = "demand details is invalid";
		        }

        if(!isset($validate["road_type_mstr_id"]) || $validate["road_type_mstr_id"]==""){
            $errMsg["road_type_mstr_id"] = "road type mstr is required";
        }
			if(isset($validate["owner_name"])
					&& isset($validate["guardian_name"])
					&& isset($validate["relation_type"])
					&& isset($validate["mobile_no"])
					&& isset($validate["aadhar_no"])
					&& isset($validate["pan_no"])
					&& isset($validate["email"])){
				for($i=0; $i<sizeof($validate["owner_name"]); $i++){
					if($validate["owner_name"][$i]==""){
						$errMsg["owner_name"] = "owner name is required";
					}
					if($validate["mobile_no"][$i]==""){
						$errMsg["mobile_no"] = "mobile no is required";
					}else{
						if(!isMobile($validate["mobile_no"][$i])){
							$errMsg["mobile_no"] = "mobile no is invalid";
						}
					}
				}
			}else{
				$errMsg["owner_details"] = "owner details invalid";
			}

        if(!isset($validate["prop_address"]) || $validate["prop_address"]==""){
            $errMsg["prop_address"] = "property address is required";
        }
        if(!isset($validate["prop_city"]) || $validate["prop_city"]==""){
            $errMsg["prop_city"] = "property city is required";
        }
        if(!isset($validate["prop_dist"]) || $validate["prop_dist"]==""){
            $errMsg["prop_dist"] = "property dist is required";
        }
        if(!isset($validate["prop_pin_code"]) || $validate["prop_pin_code"]==""){
            $errMsg["prop_pin_code"] = "property pin code is required";
        }

   		return $errMsg;
	}
}