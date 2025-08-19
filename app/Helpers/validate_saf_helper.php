<?php
if(!function_exists('validateSafAddUpdate')){
   	function validateSafAddUpdate($validate){
   		$errMsg = (array)null;

   		if(!isset($validate["has_previous_holding_no"]) || $validate["has_previous_holding_no"]==""){
            $errMsg["has_previous_holding_no"] = "has previous holding no is required";
        }else{
        	if($validate["has_previous_holding_no"]==1){
        		if(!isset($validate["previous_holding_no"]) || $validate["previous_holding_no"]==""){
		            $errMsg["previous_holding_no"] = "previous holding no is required";
				}

				if(!isset($validate["is_owner_changed"]) || $validate["is_owner_changed"]==""){
					$errMsg["is_owner_changed"] = "is owner changed is required";
				}else{
					if($validate["is_owner_changed"]==1){
						if(!isset($validate["transfer_mode_mstr_id"]) || $validate["transfer_mode_mstr_id"]==""){
							$errMsg["transfer_mode_mstr_id"] = "transfer mode is required";
						}
					}
				}
			}
        }

   		if(!isset($validate["ward_mstr_id"]) || $validate["ward_mstr_id"]==""){
            $errMsg["ward_mstr_id"] = "old ward name is required";
		}
		if(!isset($validate["new_ward_mstr_id"]) || $validate["new_ward_mstr_id"]==""){
            $errMsg["new_ward_mstr_id"] = "new ward name is required";
        }
        if(!isset($validate["ownership_type_mstr_id"]) || $validate["ownership_type_mstr_id"]==""){
            $errMsg["ownership_type_mstr_id"] = "ownership type is required";
        }
        if(!isset($validate["prop_type_mstr_id"]) || $validate["prop_type_mstr_id"]==""){
            $errMsg["prop_type_mstr_id"] = "property type is required";
        }else{
        	if($validate["prop_type_mstr_id"]==3){
	        	if(!isset($validate["appartment_name"]) || $validate["appartment_name"]==""){
		            $errMsg["appartment_name"] = "appartment name is required";
		        }
		        if(!isset($validate["flat_registry_date"]) || $validate["flat_registry_date"]==""){
		            $errMsg["flat_registry_date"] = "flat registry date is required";
		        }else{
					if(!isDateFormat($validate["flat_registry_date"]) || $validate["flat_registry_date"] > date("Y-m-d")){
						$errMsg["flat_registry_date"] = "flat registry date is invalid";
					}
				}
		    }

		    if($validate["prop_type_mstr_id"]!=4){
		        if(isset($validate["floor_mstr_id"])
		        		&& isset($validate["usage_type_mstr_id"])
		        		&& isset($validate["occupancy_type_mstr_id"])
		        		&& isset($validate["const_type_mstr_id"])
		        		&& isset($validate["builtup_area"])
		        		&& isset($validate["date_from"])
		        		&& isset($validate["date_upto"])){

		        	for($i=0; $i<sizeof($validate["floor_mstr_id"]); $i++){
			        	if($validate["floor_mstr_id"][$i]==""){
			        		$errMsg["floor_mstr_id"] = "floor no is required";
			        	}
			        	if($validate["usage_type_mstr_id"][$i]==""){
			        		$errMsg["usage_type_mstr_id"] = "usage type is required";
			        	}
			        	if($validate["occupancy_type_mstr_id"][$i]==""){
			        		$errMsg["occupancy_type_mstr_id"] = "occupancy type is required";
			        	}
			        	if($validate["const_type_mstr_id"][$i]==""){
			        		$errMsg["const_type_mstr_id"] = "construction type is required";
			        	}
			        	if($validate["builtup_area"][$i]==""){
			        		$errMsg["builtup_area"] = "builtup area is required";
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
			        			$errMsg["date_upto"] = "date from is invalid";
			        		}else{
								$date_from = $validate["date_from"][$i]."-01";
								$date_upto = $validate["date_upto"][$i]."-01";
								if($date_from >= $date_upto || $date_upto > date("Y-m-d")){
									$errMsg["date_upto"] = "date from is invalid";
								}
							}
			        	}
			        }
		        }else{
		        	$errMsg["floors_details"] = "floors details is invalid";
		        }
		    }
		    if($validate["prop_type_mstr_id"]==4){
		    	if(!isset($validate["land_occupation_date"]) || $validate["land_occupation_date"]==""){
		            $errMsg["land_occupation_date"] = "date of possession / purchase / acquisition is required";
		        }else{
		        	if(!isDateFormat($validate["land_occupation_date"]) || $validate["land_occupation_date"] > date("Y-m-d")){
		        		$errMsg["land_occupation_date"] = "date of possession / purchase / acquisition is invalid";
		        	}
		        }
		    }

        }

        if(!isset($validate["road_type_mstr_id"]) || $validate["road_type_mstr_id"]==""){
            $errMsg["road_type_mstr_id"] = "road type mstr is required";
        }

        if(!isset($validate["zone_mstr_id"]) || $validate["zone_mstr_id"]==""){
            $errMsg["zone_mstr_id"] = "zone type mstr is required";
        }

		if ($validate["has_previous_holding_no"]==1) {
			if( !isset($validate["prev_owner_name"]) || sizeof($validate["prev_owner_name"]) == 0 ) {
				$errMsg["holding_no"] = "holding no does not exist !!!";
			}
		}
		if ($validate["has_previous_holding_no"]==0 || ($validate["has_previous_holding_no"]==1 && $validate["is_owner_changed"]==1)) {

			if(isset($validate["owner_name"])
					&& isset($validate["guardian_name"])
					&& isset($validate["relation_type"])
					&& isset($validate["mobile_no"])
					&& isset($validate["aadhar_no"])
					&& isset($validate["pan_no"])
					&& isset($validate["email"]))
			{
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
			}
			else
			{
				$errMsg["owner_details"] = "owner details invalid";
			}
		}
		
        if(!isset($validate["building_plan_approval_no"])){
            $errMsg["building_plan_approval_no"] = "building plan approval no is required";
        }
        if(!isset($validate["building_plan_approval_date"])){
            $errMsg["building_plan_approval_date"] = "building plan approval date is required";
        }else{
        	if($validate["building_plan_approval_date"]!=""){
	        	if(!isDateFormat($validate["building_plan_approval_date"]) || $validate["building_plan_approval_date"] > date("Y-m-d")){
	        		$errMsg["building_plan_approval_date"] = "building plan approval date is invalid";
	        	}
        	}
        }
        if(!isset($validate["water_conn_no"])){
            $errMsg["water_conn_no"] = "water consumer no is required";
        }
        if(!isset($validate["water_conn_date"])){
            $errMsg["water_conn_date"] = "water consumer date is required";
        }else{
        	if($validate["water_conn_date"]!=""){
	        	if(!isDateFormat($validate["water_conn_date"]) || $validate["water_conn_date"] > date("Y-m-d")){
	        		$errMsg["water_conn_date"] = "water connection date is invalid";
	        	}
	        }
        }

		if(!isset($validate["khata_no"]) || $validate["khata_no"]==""){
			$errMsg["khata_no"] = "khata no is required";
		}
		if(!isset($validate["plot_no"]) || $validate["plot_no"]==""){
			$errMsg["plot_no"] = "plot no is required";
		}
        if(!isset($validate["village_mauja_name"]) || $validate["village_mauja_name"]==""){
            $errMsg["village_mauja_name"] = "village mauja name is required";
        }
        if(!isset($validate["area_of_plot"]) || $validate["area_of_plot"]=="" || $validate["area_of_plot"]<0.1){
            $errMsg["area_of_plot"] = "area of plot is required";
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
        if(isset($validate['is_corr_add_differ']) && $validate['is_corr_add_differ']==1){
        	if(!isset($validate["corr_address"]) || $validate["corr_address"]==""){
            	$errMsg["corr_address"] = "correspondence address is required";
	        }
	        if(!isset($validate["corr_city"]) || $validate["corr_city"]==""){
	            $errMsg["corr_city"] = "correspondence city is required";
	        }
	        if(!isset($validate["corr_dist"]) || $validate["corr_dist"]==""){
	            $errMsg["corr_dist"] = "correspondence dist is required";
	        }
	        if(!isset($validate["corr_pin_code"]) || $validate["corr_pin_code"]==""){
	            $errMsg["corr_pin_code"] = "correspondence pin code is required";
	        }
        }
        if(!isset($validate["is_mobile_tower"]) || $validate["is_mobile_tower"]=="" || $validate["is_mobile_tower"]=="-"){
            $errMsg["is_mobile_tower"] = "is mobile tower is required";
        }else{
        	if($validate["is_mobile_tower"]==1){
	        	if(!isset($validate["tower_area"]) || $validate["tower_area"]=="" || $validate["tower_area"]==0){
		            $errMsg["tower_area"] = "tower area is required";
		        }
		        if(!isset($validate["tower_installation_date"]) || $validate["tower_installation_date"]==""){
		            $errMsg["tower_installation_date"] = "tower installation date is required";
		        }else{
		        	if(!isDateFormat($validate["tower_installation_date"]) || $validate["tower_installation_date"] > date("Y-m-d")){
		        		$errMsg["tower_installation_date"] = "tower installation date is invalid";
		        	}
		        }
		    }
        }

        if(!isset($validate["is_hoarding_board"]) || $validate["is_hoarding_board"]=="" || $validate["is_hoarding_board"]=="-"){
            $errMsg["is_hoarding_board"] = "is hoarding board is required";
        }else{
        	if($validate["is_hoarding_board"]==1){
	        	if(!isset($validate["hoarding_area"]) || $validate["hoarding_area"]=="" || $validate["hoarding_area"]==0){
		            $errMsg["hoarding_area"] = "hoarding area is required";
		        }
		        if(!isset($validate["hoarding_installation_date"]) || $validate["hoarding_installation_date"]=="" || $validate["hoarding_installation_date"] > date("Y-m-d")){
		            $errMsg["hoarding_installation_date"] = "hoarding installation date is required";
		        }else{
		        	if(!isDateFormat($validate["hoarding_installation_date"])){
		        		$errMsg["hoarding_installation_date"] = "hoarding installation date is invalid";
		        	}
		        }
		    }
        }

        

		if($validate["prop_type_mstr_id"]!=4 && $validate["prop_type_mstr_id"]!=3){
			if(!isset($validate["is_petrol_pump"]) || $validate["is_petrol_pump"]=="" || $validate["is_petrol_pump"]=="-"){
				$errMsg["is_petrol_pump"] = "is petrol pump is required";
			}else{
				if($validate["is_petrol_pump"]==1){
					if(!isset($validate["under_ground_area"]) || $validate["under_ground_area"]=="" || $validate["under_ground_area"]==0){
						$errMsg["under_ground_area"] = "under ground area is required";
					}
					if(!isset($validate["petrol_pump_completion_date"]) || $validate["petrol_pump_completion_date"]=="" || $validate["petrol_pump_completion_date"] > date("Y-m-d")){
						$errMsg["petrol_pump_completion_date"] = "petrol pump completion date required";
					}else{
						if(!isDateFormat($validate["petrol_pump_completion_date"])){
							$errMsg["petrol_pump_completion_date"] = "petrol pump completion date is invalid";
						}
					}
				}
			}
			
		}
		if($validate["prop_type_mstr_id"]!=4){
			if(!isset($validate["is_water_harvesting"]) || $validate["is_water_harvesting"]=="" || $validate["is_water_harvesting"]=="-"){
				$errMsg["is_water_harvesting"] = "is water harvesting is required";
			}
		}


   		return $errMsg;
	}
}

if(!function_exists('validateSafMobileAddUpdate')){
	function validateSafMobileAddUpdate($validate){
		$errMsg = (array)null;

	if(!isset($validate["has_previous_holding_no"]) || $validate["has_previous_holding_no"]==""){
		 $errMsg["has_previous_holding_no"] = "has previous holding no is required";
	}else{
		if($validate["has_previous_holding_no"]==1){
			 if(!isset($validate["previous_holding_no"]) || $validate["previous_holding_no"]==""){
				 $errMsg["previous_holding_no"] = "previous holding no is required";
			 }

			 if(!isset($validate["isPrevPaymentCleared"])){
				 $errMsg["isPrevPaymentCleared"] = "is prev payment cleared is required or something wrong!!";
			 }else{
				 $array = array("NO", "YES");
				 if (!in_array($validate["isPrevPaymentCleared"], $array)){
					 $errMsg["isPrevPaymentCleared"] = "is prev payment cleared is invalid or something wrong!!";
				 }
			 }


			 if(!isset($validate["is_owner_changed"]) || $validate["is_owner_changed"]==""){
				 $errMsg["is_owner_changed"] = "is owner changed is required";
			 }else{
				 if($validate["is_owner_changed"]==1){
					 if(!isset($validate["transfer_mode_mstr_id"]) || $validate["transfer_mode_mstr_id"]==""){
						 $errMsg["transfer_mode_mstr_id"] = "transfer mode is required";
					 }
				 }
			 }
		}
	}

	if(!isset($validate["ward_mstr_id"]) || $validate["ward_mstr_id"]==""){
		 $errMsg["ward_mstr_id"] = "ward name is required";
	}
	if(!isset($validate["new_ward_mstr_id"]) || $validate["new_ward_mstr_id"]==""){
		$errMsg["new_ward_mstr_id"] = "new ward name is required";
	}
	 if(!isset($validate["ownership_type_mstr_id"]) || $validate["ownership_type_mstr_id"]==""){
		 $errMsg["ownership_type_mstr_id"] = "ownership type is required";
	 }
	 if(!isset($validate["prop_type_mstr_id"]) || $validate["prop_type_mstr_id"]==""){
		 $errMsg["prop_type_mstr_id"] = "property type is required";
	 }else{
		 if($validate["prop_type_mstr_id"]==3){
			 if(!isset($validate["appartment_name"]) || $validate["appartment_name"]==""){
				 $errMsg["appartment_name"] = "appartment name is required";
			 }
			 if(!isset($validate["flat_registry_date"]) || $validate["flat_registry_date"]==""){
				 $errMsg["flat_registry_date"] = "flat registry date is required";
			 }else{
				 if(!isDateFormat($validate["flat_registry_date"]) || $validate["flat_registry_date"] > date("Y-m-d")){
					 $errMsg["flat_registry_date"] = "flat registry date is invalid";
				 }
			 }
		 }

		 if($validate["prop_type_mstr_id"]!=4){
			 if(isset($validate["floor_mstr_id"])
					 && isset($validate["usage_type_mstr_id"])
					 && isset($validate["occupancy_type_mstr_id"])
					 && isset($validate["const_type_mstr_id"])
					 && isset($validate["builtup_area"])
					 && isset($validate["date_from"])
					 && isset($validate["date_upto"])){

				 for($i=0; $i<sizeof($validate["floor_mstr_id"]); $i++){
					 if($validate["floor_mstr_id"][$i]==""){
						 $errMsg["floor_mstr_id"] = "floor no is required";
					 }
					 if($validate["usage_type_mstr_id"][$i]==""){
						 $errMsg["usage_type_mstr_id"] = "usage type is required";
					 }
					 if($validate["occupancy_type_mstr_id"][$i]==""){
						 $errMsg["occupancy_type_mstr_id"] = "occupancy type is required";
					 }
					 if($validate["const_type_mstr_id"][$i]==""){
						 $errMsg["const_type_mstr_id"] = "construction type is required";
					 }
					 if($validate["builtup_area"][$i]==""){
						 $errMsg["builtup_area"] = "builtup area is required";
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
							 $errMsg["date_upto"] = "date from is invalid";
						 }else{
							 $date_from = $validate["date_from"][$i]."-01";
							 $date_upto = $validate["date_upto"][$i]."-01";
							 if($date_from >= $date_upto || $date_upto > date("Y-m-d")){
								 $errMsg["date_upto"] = "date from is invalid";
							 }
						 }
					 }
				 }
			 }else{
				 $errMsg["floors_details"] = "floors details is invalid";
			 }
		 }
		 if($validate["prop_type_mstr_id"]==4){
			 if(!isset($validate["land_occupation_date"]) || $validate["land_occupation_date"]==""){
				 $errMsg["land_occupation_date"] = "date of possession / purchase / acquisition is required";
			 }else{
				 if(!isDateFormat($validate["land_occupation_date"]) || $validate["land_occupation_date"] > date("Y-m-d")){
					 $errMsg["land_occupation_date"] = "date of possession / purchase / acquisition is invalid";
				 }
			 }
		 }

	 }

	 if(!isset($validate["road_type_mstr_id"]) || $validate["road_type_mstr_id"]==""){
		 $errMsg["road_type_mstr_id"] = "road type mstr is required";
	 }

	 if(!isset($validate["zone_mstr_id"]) || $validate["zone_mstr_id"]==""){
		 $errMsg["zone_mstr_id"] = "zone type mstr is required";
	 }

	 if ($validate["has_previous_holding_no"]==1) {
		 if ($validate["hasPrevOwnerDtlCount"]==0) {
			 $errMsg["holding_no"] = "holding no does not exist !!!";
		 }else if( !isset($validate["prev_owner_name"]) || sizeof($validate["prev_owner_name"]) == 0 ) {
			 $errMsg["holding_no"] = "Previous Owner Detail is not available, please check Holding No !!!";
		 }
		 if( $validate["is_owner_changed"]==1 ){
			 if ( $validate["isPrevPaymentCleared"]=="NO" ) {
				 $errMsg["isPrevPaymentCleared"] = $validate["previous_holding_no"].", please clear tax !!!";
			 }
		 }
	 }
	 if ($validate["has_previous_holding_no"]==0 || ($validate["has_previous_holding_no"]==1 && $validate["is_owner_changed"]==1)) {

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
	 }
	 

	 if(!isset($validate["area_of_plot"]) || $validate["area_of_plot"]=="" || $validate["area_of_plot"]<0.1){
		 $errMsg["area_of_plot"] = "area of plot is required";
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

	 if(!isset($validate["is_mobile_tower"]) || $validate["is_mobile_tower"]=="" || $validate["is_mobile_tower"]=="-"){
		 $errMsg["is_mobile_tower"] = "is mobile tower is required";
	 }else{
		 if($validate["is_mobile_tower"]==1){
			 if(!isset($validate["tower_area"]) || $validate["tower_area"]=="" || $validate["tower_area"]==0){
				 $errMsg["tower_area"] = "tower area is required";
			 }
			 if(!isset($validate["tower_installation_date"]) || $validate["tower_installation_date"]==""){
				 $errMsg["tower_installation_date"] = "tower installation date is required";
			 }else{
				 if(!isDateFormat($validate["tower_installation_date"]) || $validate["tower_installation_date"] > date("Y-m-d")){
					 $errMsg["tower_installation_date"] = "tower installation date is invalid";
				 }
			 }
		 }
	 }

	 if(!isset($validate["is_hoarding_board"]) || $validate["is_hoarding_board"]=="" || $validate["is_hoarding_board"]=="-"){
		 $errMsg["is_hoarding_board"] = "is hoarding board is required";
	 }else{
		 if($validate["is_hoarding_board"]==1){
			 if(!isset($validate["hoarding_area"]) || $validate["hoarding_area"]=="" || $validate["hoarding_area"]==0){
				 $errMsg["hoarding_area"] = "hoarding area is required";
			 }
			 if(!isset($validate["hoarding_installation_date"]) || $validate["hoarding_installation_date"]=="" || $validate["hoarding_installation_date"] > date("Y-m-d")){
				 $errMsg["hoarding_installation_date"] = "hoarding installation date is required";
			 }else{
				 if(!isDateFormat($validate["hoarding_installation_date"])){
					 $errMsg["hoarding_installation_date"] = "hoarding installation date is invalid";
				 }
			 }
		 }
	 }

	 

	if($validate["prop_type_mstr_id"]==2){
		 if(!isset($validate["is_petrol_pump"]) || $validate["is_petrol_pump"]=="" || $validate["is_petrol_pump"]=="-"){
			 $errMsg["is_petrol_pump"] = "is petrol pump is required";
		 }else{
			 if($validate["is_petrol_pump"]==1){
				 if(!isset($validate["under_ground_area"]) || $validate["under_ground_area"]=="" || $validate["under_ground_area"]==0){
					 $errMsg["under_ground_area"] = "under ground area is required";
				 }
				 if(!isset($validate["petrol_pump_completion_date"]) || $validate["petrol_pump_completion_date"]=="" || $validate["petrol_pump_completion_date"] > date("Y-m-d")){
					 $errMsg["petrol_pump_completion_date"] = "petrol pump completion date required";
				 }else{
					 if(!isDateFormat($validate["petrol_pump_completion_date"])){
						 $errMsg["petrol_pump_completion_date"] = "petrol pump completion date is invalid";
					 }
				 }
			 }
		 }
	}
	if($validate["prop_type_mstr_id"]!=4){
		 if(!isset($validate["is_water_harvesting"]) || $validate["is_water_harvesting"]=="" || $validate["is_water_harvesting"]=="-"){
			 $errMsg["is_water_harvesting"] = "is water harvesting is required";
		 }
	 }


		return $errMsg;
 }
}