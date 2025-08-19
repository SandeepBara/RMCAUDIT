<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Controllers\SAF\SAFHelper;
use App\Models\model_saf_floor_details;
use App\Models\model_prop_dtl;
use App\Models\model_prop_owner_detail;
use App\Models\model_prop_floor_details;
use App\Models\model_saf_tax;
use App\Models\model_prop_tax;
use App\Models\model_saf_demand;
use App\Models\model_prop_demand;
use App\Models\model_field_verification_dtl;
use App\Models\model_field_verification_floor_details;
use App\Models\model_fy_mstr;
use App\Models\model_view_ward_permission;
use App\Models\model_apartment_details;
use App\Models\model_datatable;

use Exception;

class tools extends AlphaController {
    protected $db;
    protected $dbSystem;
    protected $model_datatable;

    protected $model_saf_tax;
    protected $model_prop_tax;
    protected $model_saf_demand;
    protected $model_prop_dtl;
    protected $model_prop_demand;
    protected $model_level_pending_dtl;
    protected $model_saf_memo_dtl;
    protected $model_prop_floor_details;
    protected $model_prop_owner_detail;
    protected $model_fy_mstr;
    protected $model_field_verification_dtl;
    protected $model_field_verification_floor_details;
    protected $model_view_ward_permission;
    protected $model_apartment_details;


    public function __construct()
    {
		ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
		parent::__construct();
        $Session = Session();
        $emp_mstr = $Session->get("emp_details");
        //print_var($emp_mstr);
        if($emp_mstr['user_type_mstr_id'] != 1)
        {
            echo view('users/login');
    		die();
        }
        helper(['db_helper', 'utility_helper','php_office_helper']);
        $this->db = db_connect('db_rmc_property');
        $this->dbSystem = db_connect('db_system');
        $this->model_datatable = new model_datatable($this->db);

        $this->model_prop_dtl = new model_prop_dtl($this->db);
        $this->model_prop_owner_detail = new model_prop_owner_detail($this->db);
        $this->model_prop_floor_details = new model_prop_floor_details($this->db);
        $this->model_saf_tax = new model_saf_tax($this->db);
        $this->model_prop_tax = new model_prop_tax($this->db);
        $this->model_saf_demand = new model_saf_demand($this->db);
        $this->model_prop_demand = new model_prop_demand($this->db);
        $this->model_field_verification_dtl = new model_field_verification_dtl($this->db);
        $this->model_field_verification_floor_details = new model_field_verification_floor_details($this->db);
        $this->model_fy_mstr = new model_fy_mstr($this->dbSystem);
        $this->model_view_ward_permission = new model_view_ward_permission($this->dbSystem);
        $this->model_apartment_details = new model_apartment_details($this->db);

        
    }

    function __destruct() {
		if($this->db) $this->db->close();
		if($this->dbSystem) $this->dbSystem->close();
	}


    public function getFyID($FY)
	{
		return $this->model_fy_mstr->getFyByFy(['fy' => $FY])['id'];
	}

    public function makeDueDateByFyearQtr($fyear, $qtr)
	{
		list($fyear1, $fyear2) = explode("-", $fyear);
		if ($qtr == 1) {
			return $fyear1 . "-06-30";
		} else if ($qtr == 2) {
			return $fyear1 . "-09-30";
		} else if ($qtr == 3) {
			return $fyear1 . "-12-31";
		} else if ($qtr == 4) {
			return $fyear2 . "-03-31";
		}
	}

    public function onePercentPenalty($prop_dtl_id, $demand_amount, $newFY, $q, $paymentDate)
    {
        $penalty_sql ="select * from prop_get1percentpenalty_month(".$prop_dtl_id.", '".$demand_amount."', '".$newFY."', '".$q."','".$paymentDate."')";
        $penalty = $this->db->query($penalty_sql)->getFirstRow();
        return $penalty->fine_amt;
    }

    public function index()
    {
    
    }


    public function calDiffSafDemand($safTaxDtl, $saf_dtl_id, $prop_dtl_id, $ward_mstr_id)
	{
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

		$currentFY = getFY();
		//$currentFY = "2023-2024";
		$demandDtl = [];
		$tot_paid_demand = 0;
        $penalty_amt_old = 0;
        $penalty_amt_new = 0;
        $total_rebate_amt_old = 0;
        $total_rebate_amt_new = 0;
        $tot_to_be_payable = 0;
        $uwanted_rebate = 0;
        $total_demand_amt_old = 0;
        $total_demand_amt_new = 0;
        $demand_type = '';
        $entry_for = '';
        $trans_id = 0;
        $total_demand_amt_new1 = 0;
        $prop_tax = array();
        
		foreach ($safTaxDtl as $key => $taxDtl) {
			$pymt_frm_qtr = (int)$taxDtl['qtr'];
			$pymt_frm_year = (string)$taxDtl['fyear'];

			$pymt_upto_qtr = (int)4;
			$pymt_upto_year = (string)$currentFY;
			if ($key < sizeof($safTaxDtl) - 1) {
				$pymt_upto_qtr = (int)$safTaxDtl[$key + 1]['qtr'] - 1;
				$pymt_upto_year = (string)$safTaxDtl[$key + 1]['fyear'];
			}
			list($from_y1_new, $from_y2_new) = explode("-", $pymt_frm_year);
			list($upto_y1_new, $upto_y2_new) = explode("-", $pymt_upto_year);

			if ($taxDtl['arv'] >= 0) {
				// tax insert code
				$arv = $holding_tax = $water_tax = $education_cess = $health_cess = $latrine_tax = $additional_tax = $quarterly_tax = 0;
				if ($taxDtl["rule_type"] == "OLD_RULE") {
					$arv = $taxDtl["arv"];
					$holding_tax = $taxDtl["holding_tax"];
					$water_tax = $taxDtl["water_tax"];
					$education_cess = $taxDtl["education_cess"];
					$health_cess = $taxDtl["health_cess"];
					$latrine_tax = $taxDtl["latrine_tax"];
					$additional_tax = $taxDtl["additional_tax"];
					$quarterly_tax = $taxDtl["quarterly_tax"];
				} else if ($taxDtl["rule_type"] != "OLD_RULE") {
					$arv = $taxDtl["arv"];
					$holding_tax = $taxDtl["holding_tax"];
					$additional_tax = $taxDtl["additional_tax"];
					$quarterly_tax = $taxDtl["quarterly_tax"];
				}
                
                $floorDateFromFyID = $this->getFyID($taxDtl['fyear']);
                
				$input = [
					'prop_dtl_id' => $prop_dtl_id, 
                    'fyear' => $taxDtl['fyear'], 
                    'qtr' => $taxDtl['qtr'], 
                    'arv' => $arv, 
                    'holding_tax' => $holding_tax, 
                    'water_tax' => $water_tax, 
                    'education_cess' => $education_cess, 
                    'health_cess' => $health_cess, 
                    'latrine_tax' => $latrine_tax, 
                    'created_on' => date("Y-m-d H:i:s"), 'status' => 1
				];

                while ($from_y1_new <= $upto_y1_new && $from_y2_new <= $upto_y2_new) {
                    $newFY = $from_y1_new . "-" . $from_y2_new;
                    $till_qtr = $newFY == $pymt_upto_year ? $pymt_upto_qtr : 4;
                    for ($q = $pymt_frm_qtr; $q <= $till_qtr; $q++) {
                        $newFY = $from_y1_new . "-" . $from_y2_new;
                        $adjust_amt = 0;
                        $demandAmt = $taxDtl['quarterly_tax'] - $taxDtl['additional_tax'];
                        $amount = $taxDtl['quarterly_tax'];
						$quarterly_tax = $taxDtl['quarterly_tax'];
                        $additional_tax = $taxDtl['additional_tax'];
                        $due_date = $this->makeDueDateByFyearQtr($newFY, $q);
                        $total_demand_amt_new = $total_demand_amt_new + $amount;
                        
                        if($newFY == '2022-2023')
                        {
                            $due_date = $this->makeDueDateByFyearQtr($newFY, $q);
                            $old_demand_sql = "(select tbl_prop_demand.id,tbl_collection.amount,tbl_collection.transaction_id,tbl_collection.created_on::date as payment_date,tbl_prop_demand.paid_status from tbl_prop_demand 
                                join tbl_collection on tbl_collection.prop_demand_id=tbl_prop_demand.id and tbl_collection.collection_type='Property' and tbl_collection.status=1
                                where tbl_prop_demand.prop_dtl_id=".$prop_dtl_id." 
                                and tbl_prop_demand.status=1 
                                and tbl_prop_demand.due_date='".$due_date."'
                                and tbl_prop_demand.fyear='".$newFY."' 
                                order by tbl_prop_demand.id asc)
                                UNION 
                                (select tbl_saf_demand.id,tbl_saf_collection.amount,tbl_saf_collection.transaction_id,tbl_saf_collection.created_on::date as payment_date,tbl_saf_demand.paid_status from tbl_saf_demand 
                                join tbl_saf_collection on tbl_saf_collection.saf_demand_id=tbl_saf_demand.id and tbl_saf_collection.collection_type='SAF' and tbl_saf_collection.status=1
                                where tbl_saf_demand.saf_dtl_id=".$saf_dtl_id." 
                                and tbl_saf_demand.status=1 
                                and tbl_saf_demand.due_date='".$due_date."'
                                and tbl_saf_demand.fyear='".$newFY."' 
                                order by tbl_saf_demand.id asc)";

                                            
                            $old_demand = $this->db->query($old_demand_sql)->getRowArray();
                            
                            if(isset($old_demand) && $old_demand['id'] > 0 && $old_demand['paid_status'] == 1 && $old_demand['amount'] >0)
                            {
                                if($newFY == '2022-2023')
                                {
									$total_demand_amt_old = $total_demand_amt_old + $old_demand['amount'];
									$total_demand_amt_new1 = $total_demand_amt_new1 + $amount;
									$trans_id = $old_demand['transaction_id'];
									$rebate_penalty = $this->db->table('tbl_transaction_fine_rebet_details')
														->where('transaction_id', $trans_id)
														->where('status', 1)
														->orderBy('id', 'DESC')->get()->getResultArray();
									
									foreach($rebate_penalty as $rebP)
									{
										if($rebP['head_name'] == '1% Monthly Penalty' && $rebP['value_add_minus'] == 'Add')
										{
											$paymentDate = $old_demand['payment_date'];
											$penalty_amt_old = $penalty_amt_old + $this->onePercentPenalty($prop_dtl_id, $old_demand['amount'], $newFY, $q, $paymentDate);
											$penalty_amt_new = $penalty_amt_new + $this->onePercentPenalty($prop_dtl_id, $amount, $newFY, $q, $paymentDate);
										}
										if($rebP['head_name'] == 'First Qtr Rebate' && $rebP['value_add_minus'] == 'Minus')
										{
											$total_rebate_amt_old = $total_rebate_amt_old + (($old_demand['amount'])*5/100);
											$total_rebate_amt_new = $total_rebate_amt_new + ($amount*5/100);
										}
										if($rebP['head_name'] == 'Special Rebate'  && $rebP['value_add_minus'] == 'Minus')
										{
											$total_rebate_amt_old = $total_rebate_amt_old + (($old_demand['amount'])*5/100);
											$total_rebate_amt_new = $total_rebate_amt_new + ($amount*5/100);
										}
										if($rebP['head_name'] == 'Online Rebate' && $rebP['value_add_minus'] == 'Minus')
										{
											$total_rebate_amt_old = $total_rebate_amt_old + (($old_demand['amount'])*5/100);
											$total_rebate_amt_new = $total_rebate_amt_new + ($amount*5/100);
										}
										if($rebP['head_name'] == 'JSK (2.5%) Rebate' && $rebP['value_add_minus'] == 'Minus')
										{
											$total_rebate_amt_old = $total_rebate_amt_old + (($old_demand['amount'])*2.5/100);
											$total_rebate_amt_new = $total_rebate_amt_new + ($amount*2.5/100);
										}
										if($rebP['head_name'] == 'Rebate Amount' && $rebP['value_add_minus'] == 'Minus')
										{
											$uwanted_rebate = $rebP['amount'];
										}
										
									}
								}
                            }
                        }

                        $sql = "SELECT COALESCE(SUM(amount), 0) AS total_amount FROM (SELECT fy_mstr_id, qtr, amount, balance, fyear, due_date FROM tbl_prop_demand WHERE prop_dtl_id=" . $prop_dtl_id . " AND status=1 AND paid_status in(1,2) AND due_date='" . $due_date . "'
                                UNION
                                SELECT fy_mstr_id, qtr, amount, balance, fyear, due_date FROM tbl_saf_demand WHERE saf_dtl_id=" . $saf_dtl_id . " AND status=1 AND paid_status=1 AND due_date='" . $due_date . "') AS tbl_demand
                                GROUP BY due_date
                                ORDER BY due_date";
                        $total_result = $this->db->query($sql);
                        if ($total_prev_demand = $total_result->getFirstRow("array")) {
                            $quarterly_tax = $amount - $total_prev_demand["total_amount"];
                            $demandAmt = $demandAmt - $total_prev_demand["total_amount"];
                            $adjust_amt = $total_prev_demand["total_amount"];
                        }
						
						$additional = 0;
                        if($newFY != '2016-2017')
                        {
                            $additional = $additional_tax;
                        }
                        
                        if ($quarterly_tax > 0  && round($demandAmt+$additional) > 0) 
                        {
                            
                            $index = [
                                'prop_dtl_id' => $prop_dtl_id,
                                'fy_mstr_id' => $this->getFyID($newFY),
                                'ward_mstr_id' => $ward_mstr_id,
                                'fyear' => $newFY,
                                'qtr' => $q,
                                'due_date' => $due_date,
                                'amount' => round($amount, 2),
                                'balance' => round($demandAmt+$additional, 2),
                                'fine_tax' => 0,
                                'created_on' => date("Y-m-d H:i:s"),
                                'status' => 1,
                                'paid_status' => 0,
                                'demand_amount' => round($amount-$additional_tax, 2),
                                'additional_amount' => $additional,
                                'adjust_amt' => $adjust_amt,
                            ];
                            
                            $demandDtl[] = $index;
                        }
                          
                    }
                    
                    $pymt_frm_qtr = 1;
                    $from_y1_new++;
                    $from_y2_new++;
                }   
                
                if($pymt_frm_year == '2022-2023')
                {
                    $total_demand_amt_new = ($total_demand_amt_new1>0)?$total_demand_amt_new1:$total_demand_amt_new;
                    $tot_paid_demand = $tot_paid_demand + ($total_demand_amt_old+$penalty_amt_old-$total_rebate_amt_old);
                    $tot_to_be_payable = $tot_to_be_payable + ($total_demand_amt_new+$penalty_amt_new-$total_rebate_amt_new);
                }
                
            }
            
		}
        // echo $total_demand_amt_old."<br/>";
        // echo $penalty_amt_old."<br/>";
        // echo $total_rebate_amt_old."<br/>";

        // echo $tot_paid_demand."<br/>";
        // echo $tot_to_be_payable."<br/>";
        // echo $tot_paid_demand-$uwanted_rebate."<br/>"; 
        $advance_amount = round(($tot_paid_demand-$uwanted_rebate) - $tot_to_be_payable ,2);
        $advance_amount = ($advance_amount>0)?$advance_amount:0;
		return [$demandDtl, $advance_amount, $trans_id];
	}

    public function calBuildingTaxDtl($floorDtlArr, $prop_type_mstr_id, $isAdditionaTaxImplemented)
	{
		$old_rule_arv_sub = [];
		$new_rule_arv_sub = [];
		$cv_rule_arv_sub = [];
		$vacantLandAdded = 0;
		// cal sub old/new rule arv
		foreach ($floorDtlArr as $key => $floorDtl) {
			$yyyyMMDDFloor = $floorDtl['date_from'] . "-01";
			$date_upto_floor = ($floorDtl['date_upto'] == "") ? "" : $floorDtl['date_upto'] . "-01";

			if ("2016-04-01" > $yyyyMMDDFloor) {
				//if ($prop_type_mstr_id!=1 && $prop_type_mstr_id!=5) {
				$old_rule_arv_sub[] = [
					"fyear" => $floorDtl['fyear'],
					"qtr" => $floorDtl['qtr'],
					"arv" => $floorDtl['old_arv'],
					"operator" => $floorDtl['operator']
				];
				//}
				if ($date_upto_floor == "" || $date_upto_floor >= "2016-04-01") {
					$new_rule_arv_sub[] = [
						"fyear" => "2016-2017",
						"qtr" => 1,
						"arv" => $floorDtl['new_arv'],
						"operator" => $floorDtl['operator']
					];
				}
				if ($date_upto_floor == "" || $date_upto_floor >= "2022-04-01") {
					$cv_rule_arv_sub[] = [
						"fyear" => "2022-2023",
						"qtr" => 1,
						"arv" => $floorDtl['cv'],
						"operator" => $floorDtl['operator']
					];
				}
			} else if ($yyyyMMDDFloor >= "2016-04-01" && "2022-04-01" > $yyyyMMDDFloor) {
				$new_rule_arv_sub[] = [
					"fyear" => $floorDtl['fyear'],
					"qtr" => $floorDtl['qtr'],
					"arv" => $floorDtl['new_arv'],
					"operator" => $floorDtl['operator']
				];
				if ($date_upto_floor == "" || $date_upto_floor >= "2022-04-01") {
					$cv_rule_arv_sub[] = [
						"fyear" => "2022-2023",
						"qtr" => 1,
						"arv" => $floorDtl['cv'],
						"operator" => $floorDtl['operator']
					];
				}
			} else if ($yyyyMMDDFloor >= "2022-04-01") {
				if ($floorDtl['type'] == "vacant" && $vacantLandAdded == 0) {
					$vacantLandAdded++;
					$cv_rule_arv_sub[] = [
						"VACANT_TYPE" => "VACANT",
						"fyear" => $floorDtl['fyear'],
						"qtr" => $floorDtl['qtr'],
						"arv" => $floorDtl['cv'],
						"operator" => $floorDtl['operator']
					];
				} else {
					$cv_rule_arv_sub[] = [
						"fyear" => $floorDtl['fyear'],
						"qtr" => $floorDtl['qtr'],
						"arv" => $floorDtl['cv'],
						"operator" => $floorDtl['operator']
					];
				}
			}
		}

		$effectQtr = (int)0;
		$effectFy = "";
		$old_arv_total = 0;
		$safTaxDtl = [];
		$taxCount = (int)0;
		/*foreach ($old_rule_arv_sub as $arvDtl) {
			if ($arvDtl['operator'] == "+") {
				$old_arv_total += round($arvDtl["arv"], 2);
			} else if ($arvDtl['operator'] == "-") {
				$old_arv_total -= round($arvDtl["arv"], 2);
			}
			
			$holding_tax = 0;
			$water_tax = 0;
			$education_cess = 0;
			$health_cess = 0;
			$latrine_tax = 0;
			$holding_tax = $old_arv_total * 0.125;
			if ($holding_tax > 0) $holding_tax = round(($holding_tax / 4), 2);

			$water_tax = $old_arv_total * 0.075;
			if ($water_tax > 0) $water_tax = round(($water_tax / 4), 2);

			$education_cess = $old_arv_total * 0.05;
			if ($education_cess > 0) $education_cess = round(($education_cess / 4), 2);

			$health_cess = $old_arv_total * 0.0625;
			if ($health_cess > 0) $health_cess = round(($health_cess / 4), 2);

			$latrine_tax = $old_arv_total * 0.075;
			if ($latrine_tax > 0) $latrine_tax = round(($latrine_tax / 4), 2);

			$quarterly_tax = round(($holding_tax + $water_tax + $education_cess + $health_cess + $latrine_tax), 2);
			
			
			if ($arvDtl['qtr'] != $effectQtr || $arvDtl['fyear'] != $effectFy) {
				$effectQtr = (int)$arvDtl['qtr'];
				$effectFy = $arvDtl['fyear'];
				if ($quarterly_tax > 0) {
					
					$safTaxDtl[$taxCount++] = ["rule_type" => "OLD_RULE", "fyear" => $effectFy, "qtr" => $effectQtr, "arv" => $old_arv_total, "holding_tax" => $holding_tax, "water_tax" => $water_tax, "education_cess" => $education_cess, "health_cess" => $health_cess, "latrine_tax" => $latrine_tax, "additional_tax" => 0, "quarterly_tax" => $quarterly_tax];
				}
			} else {
				if($taxCount > 0){
					$safTaxDtl[$taxCount-1] = ["rule_type" => "OLD_RULE", "fyear" => $effectFy, "qtr" => $effectQtr, "arv" => $old_arv_total, "holding_tax" => $holding_tax, "water_tax" => $water_tax, "education_cess" => $education_cess, "health_cess" => $health_cess, "latrine_tax" => $latrine_tax, "additional_tax" => 0, "quarterly_tax" => $quarterly_tax];
				}else{
					$safTaxDtl[$taxCount] = ["rule_type" => "OLD_RULE", "fyear" => $effectFy, "qtr" => $effectQtr, "arv" => $old_arv_total, "holding_tax" => $holding_tax, "water_tax" => $water_tax, "education_cess" => $education_cess, "health_cess" => $health_cess, "latrine_tax" => $latrine_tax, "additional_tax" => 0, "quarterly_tax" => $quarterly_tax];
				}
			}
		}*/
		
		$effectQtr = (int)0;
		$effectFy = "";
		$new_arv_total = 0;
		foreach ($new_rule_arv_sub as $key => $arvDtl) {
			if ($arvDtl['operator'] == "+") {
				$new_arv_total += $arvDtl["arv"];
			} else if ($arvDtl['operator'] == "-") {
				$new_arv_total -= $arvDtl["arv"];
			}
			$holding_tax = round((($new_arv_total * 0.02) / 4), 2);
			$additional_tax = 0;
			if ($isAdditionaTaxImplemented == TRUE) {
				$additional_tax = ($new_arv_total * 0.02) / 4;
				if ($additional_tax > 0)
					$additional_tax = round((($additional_tax * 1.5) - $additional_tax), 2);
			}
			$quarterly_tax = $holding_tax + $additional_tax;
			if ($arvDtl['qtr'] != $effectQtr || $arvDtl['fyear'] != $effectFy) {
				$effectQtr = (int)$arvDtl['qtr'];
				$effectFy = $arvDtl['fyear'];
				$safTaxDtl[$taxCount++] = ["rule_type" => "NEW_RULE", "fyear" => $effectFy, "qtr" => $effectQtr, "arv" => $new_arv_total, "holding_tax" => $holding_tax, "additional_tax" => $additional_tax, "quarterly_tax" => $quarterly_tax];
			} else {
				$safTaxDtl[$taxCount - 1] = ["rule_type" => "NEW_RULE", "fyear" => $effectFy, "qtr" => $effectQtr, "arv" => $new_arv_total, "holding_tax" => $holding_tax, "additional_tax" => $additional_tax, "quarterly_tax" => $quarterly_tax];
			}
		}
		$effectQtr = (int)0;
		$effectFy = "";
		$cv_arv_total = 0;
		foreach ($cv_rule_arv_sub as $key => $arvDtl) {
			if ($arvDtl['operator'] == "+") {
				$cv_arv_total += $arvDtl["arv"];
			} else if ($arvDtl['operator'] == "-") {
				$cv_arv_total -= $arvDtl["arv"];
			}
			$holding_tax = round(($cv_arv_total / 4), 2);
			$additional_tax = 0;
			if ($isAdditionaTaxImplemented == TRUE) {
				$additional_tax = $cv_arv_total / 4;
				if ($additional_tax > 0)
					$additional_tax = round((($additional_tax * 1.5) - $additional_tax), 2);
			}
			$quarterly_tax = $holding_tax + $additional_tax;
			if ($arvDtl['qtr'] != $effectQtr || $arvDtl['fyear'] != $effectFy) {
				$effectQtr = (int)$arvDtl['qtr'];
				$effectFy = $arvDtl['fyear'];
				$safTaxDtl[$taxCount++] = ["rule_type" => "CV_RULE", "fyear" => $effectFy, "qtr" => $effectQtr, "arv" => $cv_arv_total, "holding_tax" => $holding_tax, "additional_tax" => $additional_tax, "quarterly_tax" => $quarterly_tax];
			} else {
				$safTaxDtl[$taxCount - 1] = ["rule_type" => "CV_RULE", "fyear" => $effectFy, "qtr" => $effectQtr, "arv" => $cv_arv_total, "holding_tax" => $holding_tax, "additional_tax" => $additional_tax, "quarterly_tax" => $quarterly_tax];
			}
		}

		return [$safTaxDtl, $old_rule_arv_sub, $new_rule_arv_sub, $cv_rule_arv_sub];
	}

    public function calcDiffPanelty($safTaxDtl, $newSafTaxDtl)
	{
		//print_var($safTaxDtl);
		//print_var($newSafTaxDtl);
		$i = 1;
		$diffTax = [];
		foreach ($newSafTaxDtl as $key => $newList) {
			//$_fy_id = $newList['fyID'];
			$_fy = $newList['fyear'];
			$_qtr = $newList['qtr'];
			$_arv = $newList['arv'];

			$_holding_tax = $newList['holding_tax'];
			$_water_tax = 0;
			$_education_cess = 0;
			$_health_cess = 0;
			$_latrine_tax = 0;
			$_additional_tax = $newList['additional_tax'];
			if ($newList["rule_type"] == "OLD_RULE") {
				$_water_tax = $newList['water_tax'];
				$_education_cess = $newList['education_cess'];
				$_health_cess = $newList['health_cess'];
				$_latrine_tax = $newList['latrine_tax'];
			}
			

			foreach ($safTaxDtl as $key => $oldList) {
				if ($oldList['fyear'] == $_fy && $oldList['qtr'] == $_qtr) {
					$_arv -= $oldList['arv'];
					$_holding_tax -= $oldList['holding_tax'];
					$_water_tax -= $oldList['water_tax'];
					$_education_cess -= $oldList['education_cess'];
					$_health_cess -= $oldList['health_cess'];
					$_latrine_tax -= $oldList['latrine_tax'];
					$_additional_tax -= $oldList['additional_tax'];
				}
			}
			if ($_arv > 0 || $_additional_tax > 0) {
				$diffTax[$i]['fyear'] = $_fy;
				$diffTax[$i]['qtr'] = $_qtr;
				$diffTax[$i]['arv'] = $_arv;
				$diffTax[$i]['holding_tax'] = $_holding_tax;
				$diffTax[$i]['water_tax'] = $_water_tax;
				$diffTax[$i]['education_cess'] = $_education_cess;
				$diffTax[$i]['health_cess'] = $_health_cess;
				$diffTax[$i]['latrine_tax'] = $_latrine_tax;
				$diffTax[$i]['additional_tax'] = $_additional_tax;
				//print_var($diffTax[$i]);
				$i++;
			}
		}
		return $diffTax;
	}

    public function demandCorrection()
    {
		
        $data = (array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
		//if(!in_array($emp_mstr['user_type_mstr_id'], [1,2,4]))
		if(!in_array($emp_mstr['user_type_mstr_id'], [1,2]))
		{
			$url=base_url('/');
            return $this->response->redirect($url);
		}
        try {
            
            $result = array();

            if(isset($_POST['btn_verify']))
            {
                $currentFY = getFY();
		        //$currentFY = "2023-2024";
                $data = arrFilterSanitizeString($this->request->getVar());
                $resultArr = json_decode(stripslashes(html_entity_decode($data['encoded_data'])), true);
                
                $this->db->transBegin();
                if($resultArr['prop_dtl_id'])
                {
                   
                    $prop_dtl_id = $resultArr['prop_dtl_id'];
                    $saf_dtl_id = $resultArr['saf_dtl_id'];
                    $ward_mstr_id = $resultArr['ward_mstr_id'];
                    $advance_amount = $resultArr['advance_amount'];
                    $trans_id = $resultArr['trans_id'];
                    $this->model_prop_tax->updateSafDeactiveStatus(['prop_dtl_id'=>$resultArr['prop_dtl_id']]);
                    $this->db->table('tbl_prop_demand')->where('prop_dtl_id',$prop_dtl_id)->where('paid_status',0)->where('status',1)
                    ->update(['status'=>0, 'balance'=>0.00]);
                    $prop_tax = array();
                    
					foreach($resultArr['tax_details'] as $key => $taxDtl)
                    {

                        $pymt_frm_qtr = (int)$taxDtl['qtr'];
                        $pymt_frm_year = (string)$taxDtl['fyear'];

                        $pymt_upto_qtr = (int)4;
                        $pymt_upto_year = (string)$currentFY;
                        if ($key < sizeof($resultArr['tax_details']) - 1) {
                            $pymt_upto_qtr = (int)$resultArr['tax_details'][$key + 1]['qtr'] - 1;
                            $pymt_upto_year = (string)$resultArr['tax_details'][$key + 1]['fyear'];
                        }
                        list($from_y1_new, $from_y2_new) = explode("-", $pymt_frm_year);
                        list($upto_y1_new, $upto_y2_new) = explode("-", $pymt_upto_year);


                        $fy_mstr_id = $this->getFyID($taxDtl['fyear']);
                        $holding_tax = isset($taxDtl['holding_tax'])?$taxDtl['holding_tax']:0;
                        $water_tax = isset($taxDtl['water_tax'])?$taxDtl['water_tax']:0;
                        $education_cess = isset($taxDtl['education_cess'])?$taxDtl['education_cess']:0;
                        $health_cess = isset($taxDtl['health_cess'])?$taxDtl['health_cess']:0;
                        $latrine_tax = isset($taxDtl['latrine_tax'])?$taxDtl['latrine_tax']:0;
                        $additional_tax = isset($taxDtl['additional_tax'])?$taxDtl['additional_tax']:0;
                        $quarterly_tax = $holding_tax+$water_tax+$education_cess+$health_cess+$latrine_tax+$additional_tax;
                        
                        if($taxDtl['arv'] > 0)
                        {
                            $sql = "INSERT INTO tbl_prop_tax (prop_dtl_id, fy_mstr_id, fyear, qtr, arv, holding_tax, water_tax, education_cess, health_cess, latrine_tax, additional_tax, created_on, status, quarterly_tax)
                            VALUES ('$prop_dtl_id', '".$fy_mstr_id."' ,'" . $taxDtl['fyear'] . "', '" . $taxDtl['qtr'] . "', '".$taxDtl['arv']."', '$holding_tax', '$water_tax', '$education_cess', '$health_cess', '$latrine_tax', '$additional_tax', '" . date("Y-m-d H:i:s") . "', 1, '".$quarterly_tax."') returning id";
                            $query = $this->db->query($sql);
                            $return = $query->getFirstRow("array");
                            $prop_tax_id = $return["id"];
                            
                            while ($from_y1_new <= $upto_y1_new && $from_y2_new <= $upto_y2_new) 
                            {
								
                                $newFY = $from_y1_new . "-" . $from_y2_new;
                                $till_qtr = $newFY == $pymt_upto_year ? $pymt_upto_qtr : 4;
                                for ($q = $pymt_frm_qtr; $q <= $till_qtr; $q++) {
									
                                    if(in_array($q.'@'.$newFY, $data['verif_tax']))
                                    {
                                        $newFY = $from_y1_new . "-" . $from_y2_new;
                                        $adjust_amt = 0;
                                        $demandAmt = $taxDtl['quarterly_tax'] - $taxDtl['additional_tax'];
                                        $amount = $taxDtl['quarterly_tax'];
                                        $quarterly_tax = $taxDtl['quarterly_tax'];
                                        $additional_tax = $taxDtl['additional_tax'];
                                        $due_date = $this->makeDueDateByFyearQtr($newFY, $q);
                                        

                                        $sql = "SELECT COALESCE(SUM(amount), 0) AS total_amount FROM (SELECT fy_mstr_id, qtr, amount, balance, fyear, due_date FROM tbl_prop_demand WHERE prop_dtl_id=" . $prop_dtl_id . " AND status=1 AND paid_status in(1,2) AND due_date='" . $due_date . "'
                                                UNION
                                                SELECT fy_mstr_id, qtr, amount, balance, fyear, due_date FROM tbl_saf_demand WHERE saf_dtl_id=" . $saf_dtl_id . " AND status=1 AND paid_status=1 AND due_date='" . $due_date . "') AS tbl_demand
                                                GROUP BY due_date
                                                ORDER BY due_date";
                                        $total_result = $this->db->query($sql);
                                        
                                        if ($total_prev_demand = $total_result->getFirstRow("array")) {
                                            $quarterly_tax = $amount - $total_prev_demand["total_amount"];
                                            $demandAmt = $demandAmt - $total_prev_demand["total_amount"];
                                            $adjust_amt = $total_prev_demand["total_amount"];
                                        }
                                        
                                        $additional = 0;
                                        if($newFY != '2016-2017')
                                        {
                                            $additional = $additional_tax;
                                        }
                                        
                                        if ($quarterly_tax > 0 && round($demandAmt+$additional) > 0) 
                                        {
                                            $index = [
                                                'prop_dtl_id' => $prop_dtl_id,
                                                'prop_tax_id' => $prop_tax_id,
                                                'fy_mstr_id' => $this->getFyID($newFY),
                                                'ward_mstr_id' => $ward_mstr_id,
                                                'fyear' => $newFY,
                                                'qtr' => $q,
                                                'due_date' => $due_date,
                                                'amount' => round($amount, 2),
                                                'balance' => round($demandAmt+$additional, 2),
                                                'fine_tax' => 0,
                                                'created_on' => date("Y-m-d H:i:s"),
                                                'status' => 1,
                                                'paid_status' => 0,
                                                'demand_amount' => round($amount-$additional_tax, 2),
                                                'additional_amount' => $additional,
                                                'adjust_amt' => $adjust_amt
                                            ];
                                            $prop_tax[] = $index;
                                            $this->model_prop_demand->insertData($index);
                                        }
                                    }
                                }
                                $pymt_frm_qtr = 1;
                                $from_y1_new++;
                                $from_y2_new++;       
                            }
                            
                        }
                        
                    }
                    if($advance_amount > 0)
                    {
                        $this->db->table('tbl_advance_mstr')
                        ->where('prop_dtl_id', $prop_dtl_id)
                        ->where('reason', 'Advance Payment')
                        ->where('remarks', 'Advance Payment due to avg. calculation of 2022-2023')
                        ->where('module', 'Property')
                        ->update(['status' => 0]);
                        
                        $advance_index = [
                            'prop_dtl_id' => $prop_dtl_id,
                            'amount' => $advance_amount,
                            'reason' => 'Advance Payment',
                            'remarks' => 'Advance Payment due to avg. calculation of 2022-2023',
                            'module' => 'Property',
                            'user_id' => 1,
                            'transaction_id' => $trans_id
                        ];
                        //print_var($advance_index);
                        $this->db->table('tbl_advance_mstr')->insert($advance_index);
                    }
                    
                    $logentrysql = "INSERT INTO log_tbl_demand_correction (prop_dtl_id, created_on, created_by)
                    VALUES ('$prop_dtl_id', '" . date("Y-m-d H:i:s") ."', '".$login_emp_details_id."') returning id";
                    $logentry = $this->db->query($logentrysql);
                }

                if ($this->db->transStatus() === FALSE) {
                    $this->db->transRollback();
                    flashToast('message', 'Demand not generated');
                } else {
                    $this->db->transCommit();
                    flashToast("message", "Demand Corrected sucessfully");
                    $url=base_url('tools/DemandCorrection/');
                    return $this->response->redirect($url);
                }
                
            }
			
            if(isset($_POST['upto_update']))
            {
                $data = arrFilterSanitizeString($this->request->getVar());
                if($data['verif_floor_id'])
                {
                    foreach($data['verif_floor_id'] as $floor)
                    {
                        $this->db->table('tbl_prop_floor_details')->where('id', $floor)->update(['date_upto'=> null]);
                    }
                    flashToast("message", "Upto date updated sucessfully");
                    $url=base_url('tools/DemandCorrection/');
                    return $this->response->redirect($url);
                }
            }

            if($this->request->getMethod()=='post' && !isset($_POST['btn_verify']))
            {
                $data = arrFilterSanitizeString($this->request->getVar());
                $safHelper = new SAFHelper($this->db);
                $sql = "SELECT 
                    tbl_prop_dtl.id,
                    tbl_prop_dtl.holding_no,
                    tbl_prop_dtl.new_holding_no,
                    tbl_prop_dtl.prop_type_mstr_id,
                    tbl_prop_dtl.road_type_mstr_id,
                    tbl_prop_dtl.area_of_plot,
                    tbl_prop_dtl.ward_mstr_id,
                    tbl_prop_dtl.is_mobile_tower,
                    tbl_prop_dtl.tower_area,
                    tbl_prop_dtl.tower_installation_date,
                    tbl_prop_dtl.is_hoarding_board,
                    tbl_prop_dtl.hoarding_area,
                    tbl_prop_dtl.hoarding_installation_date,
                    tbl_prop_dtl.is_petrol_pump,
                    tbl_prop_dtl.under_ground_area,
                    tbl_prop_dtl.petrol_pump_completion_date,
                    tbl_prop_dtl.is_water_harvesting,
                    tbl_prop_dtl.zone_mstr_id,
                    tbl_field_verification_dtl.percentage_of_property_transfer,
                    tbl_prop_dtl.new_ward_mstr_id,
                    tbl_prop_dtl.occupation_date,
                    tbl_prop_dtl.apartment_details_id,
                    tbl_prop_dtl.saf_dtl_id
                FROM tbl_prop_dtl 
                LEFT JOIN tbl_saf_dtl ON tbl_prop_dtl.saf_dtl_id=tbl_saf_dtl.id
                LEFT JOIN (SELECT max(id) as max_verification_id,saf_dtl_id from tbl_field_verification_dtl where status=1 group by saf_dtl_id) max_verification ON max_verification.saf_dtl_id=tbl_prop_dtl.saf_dtl_id
                LEFT JOIN tbl_field_verification_dtl ON max_verification.max_verification_id=tbl_field_verification_dtl.id
                WHERE (tbl_prop_dtl.new_holding_no='".$data['holding_no']."')";
                $record = $this->db->query($sql)->getRowArray();
                $newSafTaxDtl = array();
                if($record)
                {
                    
                    if(isset($record['apartment_details_id']))
                    {
                        $apt = $this->model_apartment_details->getApartmentDtlById($record['apartment_details_id']);
                        $record["is_water_harvesting"] = ($apt['water_harvesting_status'] == 1)?'t':'f';
                    }

                    $inputs = array();
                    $inputs['ward_mstr_id'] = $record['ward_mstr_id'];
                    $inputs['zone_mstr_id'] = $record['zone_mstr_id']?$record['zone_mstr_id']:2;
                    $inputs["prop_type_mstr_id"] = $record['prop_type_mstr_id'];
                    $inputs['road_type_mstr_id'] = $record['road_type_mstr_id'];
                    $inputs["area_of_plot"] = $record['area_of_plot'];
                    $inputs["tower_installation_date"] = $record['tower_installation_date'];
                    $inputs["tower_area"] = $record['tower_area'];
                    $inputs["hoarding_installation_date"] = $record['hoarding_installation_date'];
                    $inputs["hoarding_area"] = $record['hoarding_area'];
                    $inputs["petrol_pump_completion_date"] = $record['petrol_pump_completion_date'];
                    $inputs["under_ground_area"] = $record['under_ground_area'];
                    //$inputs["percentage_of_property_transfer"] = $record['percentage_of_property_transfer'];

                    if ($record["is_mobile_tower"]=="t") $inputs["is_mobile_tower"] = 1; else $inputs["is_mobile_tower"] = 0;
                    if ($record["is_hoarding_board"]=="t") $inputs["is_hoarding_board"] = 1; else $inputs["is_hoarding_board"] = 0;
                    if ($record["is_petrol_pump"]=="t") $inputs["is_petrol_pump"] = 1; else $inputs["is_petrol_pump"] = 0;
                    if ($record["is_water_harvesting"]=="t") $inputs["is_water_harvesting"] = 1; else $inputs["is_water_harvesting"] = 0;
                    
                    if ($record['prop_type_mstr_id'] == 4) {
                        $inputs["land_occupation_date"] = $record["occupation_date"];

                        if ($inputs['road_type_mstr_id']!=4) {
							$vacantDtlArr = $safHelper->makeVacantFloorDtlArr($inputs);
						} else {
							$vacantDtlArr = $safHelper->makeVacantWithNoRoadFloorDtlArr($inputs);
						}

                        list($newSafTaxDtl, $old_rule_arv_sub, $new_rule_arv_sub, $cv_rule_arv_sub) = $safHelper->calVacantTaxDtl($vacantDtlArr);
                    }else{
                        $floor_dtls = $this->model_prop_floor_details->getViewPropFloorDtlByPropDtlId(['prop_dtl_id' => $record['id']]);

                        $floorKey = 0;
                        foreach ($floor_dtls as $key => $value) {
                            $inputs["floor_mstr_id"][$floorKey] = $value["floor_mstr_id"];
                            $inputs["usage_type_mstr_id"][$floorKey] = $value["usage_type_mstr_id"];
                            $inputs["const_type_mstr_id"][$floorKey] = $value["const_type_mstr_id"];
                            $inputs["occupancy_type_mstr_id"][$floorKey] = $value["occupancy_type_mstr_id"];
                            $inputs["builtup_area"][$floorKey] = $value["builtup_area"];
                            $inputs["date_from"][$floorKey] = date("Y-m", strtotime($value["date_from"]));
                            $inputs["date_upto"][$floorKey] = "";
                            if ($value["date_upto"]!="" && $value["date_upto"]!="null") {
                                $inputs["date_upto"][$floorKey] = date("Y-m", strtotime($value["date_upto"]));
                            }
                            $floorKey++;
                        }
                        
                        
                        $floorDtlArr = $safHelper->makeBuildingFloorDtlArr($inputs);
                        $data['floorDtlArr'] = $floorDtlArr;
                        $isAdditionaTaxImplemented = $safHelper->checkIsAdditionaTaxImplemented($inputs["is_water_harvesting"], $inputs["prop_type_mstr_id"], $inputs["area_of_plot"]);
                        list($newSafTaxDtl, $old_rule_arv_sub, $new_rule_arv_sub, $cv_rule_arv_sub) = $this->calBuildingTaxDtl($floorDtlArr, $record['prop_type_mstr_id'], $isAdditionaTaxImplemented);
                        
                    }
					
                    $prop_tax = $this->model_saf_tax->getJoinBySafDtlIdMd5(md5($record['saf_dtl_id']));

                    $diffTax = $this->calcDiffPanelty($prop_tax, $newSafTaxDtl);

                    list($tax_to_be_generate, $advance_amount, $trans_id) = $this->calDiffSafDemand($newSafTaxDtl, $record['saf_dtl_id'], $record['id'], $record['ward_mstr_id']);
                
                    $result["holding_no"] = $data['holding_no'];
                    $result["prop_dtl_id"] = $record['id'];
                    $result["saf_dtl_id"] = $record['saf_dtl_id'];
                    $result["ward_mstr_id"] = $record['ward_mstr_id'];
                    $result["tax_details"] = $newSafTaxDtl;
                    $result["prop_tax"] = $prop_tax;
                    $result["tax_difference"] = $diffTax;
                    $result["tax_to_be_generate"] = $tax_to_be_generate;
                    $result["advance_amount"] = $advance_amount;
                    $result["trans_id"] = $trans_id;
					$result["floorList"] = isset($floor_dtls)?$floor_dtls:[];
                    $result['encode_data'] = json_encode($result);
                }else{
                    $result["tax_details"] = "";
                }
            }

            return view('demand_correction_new', $result);
        }catch(Exception $e){
            print_r($e->getMessage());
            if($login_emp_details_id==1)
            {
                print_var($e->getLine());
                print_var($e->getFile());
                echo($this->db->getLastQuery());

            }
        }
    }

    public function verificationUpdate()
    {
        $data = (array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
		//if(!in_array($emp_mstr['user_type_mstr_id'], [1,2,4]))
		if(!in_array($emp_mstr['user_type_mstr_id'], [1,2]))
		{
			$url=base_url('/');
            return $this->response->redirect($url);
		}

        try{
            $result = array();
            if($this->request->getMethod()=='post' && !isset($_POST['btn_verify']))
            {
                $data = arrFilterSanitizeString($this->request->getVar());
                $safHelper = new SAFHelper($this->db);
                $sql = "SELECT 
                    tbl_prop_dtl.id,
                    tbl_prop_dtl.holding_no,
                    tbl_prop_dtl.new_holding_no,
                    tbl_field_verification_dtl.prop_type_mstr_id,
                    tbl_field_verification_dtl.road_type_mstr_id,
                    tbl_field_verification_dtl.area_of_plot,
                    tbl_field_verification_dtl.ward_mstr_id,
                    tbl_field_verification_dtl.is_mobile_tower,
                    tbl_field_verification_dtl.tower_area,
                    tbl_field_verification_dtl.tower_installation_date,
                    tbl_field_verification_dtl.is_hoarding_board,
                    tbl_field_verification_dtl.hoarding_area,
                    tbl_field_verification_dtl.hoarding_installation_date,
                    tbl_field_verification_dtl.is_petrol_pump,
                    tbl_field_verification_dtl.under_ground_area,
                    tbl_field_verification_dtl.petrol_pump_completion_date,
                    tbl_field_verification_dtl.is_water_harvesting,
                    tbl_field_verification_dtl.zone_mstr_id,
                    tbl_field_verification_dtl.percentage_of_property_transfer,
                    tbl_field_verification_dtl.new_ward_mstr_id,
                    tbl_field_verification_dtl.land_occupation_date,
                    tbl_field_verification_dtl.apartment_details_id,
                    tbl_prop_dtl.saf_dtl_id
                FROM tbl_prop_dtl 
                LEFT JOIN tbl_saf_dtl ON tbl_prop_dtl.saf_dtl_id=tbl_saf_dtl.id
                LEFT JOIN (SELECT max(id) as max_verification_id,saf_dtl_id from tbl_field_verification_dtl where status=1 group by saf_dtl_id) max_verification ON max_verification.saf_dtl_id=tbl_prop_dtl.saf_dtl_id
                LEFT JOIN tbl_field_verification_dtl ON max_verification.max_verification_id=tbl_field_verification_dtl.id
                WHERE (tbl_prop_dtl.new_holding_no='".$data['holding_no']."')";
                $record = $this->db->query($sql)->getRowArray();

                if($record)
                {
                    $prop_dtl_id = $record['id'];
                    $saf_dtl_id = $record['saf_dtl_id'];

                    $this->model_prop_dtl->update_prop_with_floor($prop_dtl_id, $saf_dtl_id, $login_emp_details_id);
                }
            }

            return view('demand_correction', $result);
        }
        catch(Exception $e){
            print_r($e);
        }
    }

    public function superSqlData($dbname=null)
    {
        $Session = Session();
        $Session->set('dbname', 'db_rmc_property');
        if($this->request->getMethod()=='post')
        {
            $Session->set('dbname', $this->request->getVar('dbname'));
            $this->db = db_connect($Session->get('dbname'));
        }
        
        $data = array();
        $tableSql = "SELECT TABLE_NAME AS table FROM INFORMATION_SCHEMA.TABLES WHERE table_schema='public' order by TABLE_NAME ASC";
        $getTable = $this->db->query($tableSql)->getResultArray();

        $data['getTable'] = $getTable;
        $data['dbname'] = $Session->get('dbname');
        return view('editortool', $data);
    }

    public function Ajax_getDataFromEditor_old()
    {
        $Session = Session();
        $this->db = db_connect($Session->get('dbname'));
        $response = "";
        try{
            if($this->request->getMethod()=='post')
            {   
                $sql = $_POST['query'];
                $qry_array=explode(" ",$sql);
                //print_var($qry_array);
                $results = $this->db->query($sql);
                if($this->db->error($results)['message'] !=""){
                    $response = ['status'=> false, 'data'=> $this->db->error($results)['message']];
                }else{
                    $data = "";
                    if(strtoupper($qry_array[0]) == 'SELECT')
                    {

                        $results = $results->getResultArray();
                        $data .= '<table class="table table-bordered">';
                        $data .= '<thead style="position: sticky; top:0; background-color:#336699; color:#fff;"><tr>';

                        foreach($results[0] as $key=>$value)
                        {
                            $data .= "<th style='color:#fff;'>".$key."</th>";
                        }
                        
                        $data .= '</tr></thead><tbody>';
                        
                        foreach($results as $row){
                            $data .= "<tr>";
                            foreach($row as $key=>$value)
                            {   
                                $data .= "<td>".$row[$key]."</td>";
                            }
                            $data .= "</tr>";
                        }
                        $data .= "</tbody></table>";

                    }else{
                    
                        $results = "UPDATE ". $this->db->affectedRows($results) . " record affected";
                        $data = $results;
                        
                        
                    }

                    $response = ['status'=> true, 'data'=> $data];
                }
            }
        }
        catch(Exception $e){
            
            $response = ["status"=> false, "data"=> [], "message"=> "Exception: ".$e->getMessage()];
        }

        echo json_encode($response);
    }

    public function Ajax_getDataFromEditor()
    {
        $Session = Session();
        $this->db = db_connect($Session->get('dbname'));
        $response = "";
        try{
            if($this->request->getMethod()=='post')
            {   
                set_time_limit(500);
                $sql = $_POST['query'];
                $qry_array=explode(" ",$sql);
                //print_var($qry_array);
                $results = $this->db->query($sql);
                if($this->db->error($results)['message'] !=""){
                    $response = ['status'=> false, 'data'=> $this->db->error($results)['message']];
                }else{
                    $data = "";
                    if(preg_match('/update |insert |delete |alter |create |drop |truncate |call |lock |commit |rollback /i',$sql)){
                        $results = "UPDATE ". $this->db->affectedRows($results) . " record affected";
                        $data = $results;
                    }else{

                        $results = $results->getResultArray();
                        $data .= '<table class="table table-bordered">';
                        $data .= '<thead style="position: sticky; top:0; background-color:#336699; color:#fff;"><tr>';

                        foreach($results[0] as $key=>$value)
                        {
                            $data .= "<th style='color:#fff;'>".$key."</th>";
                        }
                        
                        $data .= '</tr></thead><tbody>';
                        
                        foreach($results as $row){
                            $data .= "<tr>";
                            foreach($row as $key=>$value)
                            {   
                                $data .= "<td>".$row[$key]."</td>";
                            }
                            $data .= "</tr>";
                        }
                        $data .= "</tbody></table>";

                    }

                    $response = ['status'=> true, 'data'=> $data];
                }
            }
        }
        catch(Exception $e){
            
            $response = ["status"=> false, "data"=> [], "message"=> "Exception: ".$e->getMessage()];
        }

        echo json_encode($response);
    }

    private function getColumnLetter($index)
    {
        $letters = '';
        while ($index >= 0) {
            $letters = chr($index % 26 + 65) . $letters;
            $index = floor($index / 26) - 1;
        }
        return $letters;
    }

    public function exportDataExcel(){
        $Session = Session();
        $this->db = db_connect($Session->get('dbname'));
        $response = "";
        try{
            if($this->request->getMethod()=='post')
            {   
                set_time_limit(1500);
                ini_set('memory_limit', '3G');
                $sql = $_POST['query'];
                $qry_array=explode(" ",$sql);
                //print_var($qry_array);
                $results = $this->db->query($sql);
                if($this->db->error($results)['message'] !=""){
                    $response = ['status'=> false, 'data'=> $this->db->error($results)['message']];
                }else{
                    phpOfficeLoad();
                    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                    $activeSheet = $spreadsheet->getActiveSheet();

                    $data = "";

                    $results = $results->getResultArray();
                    $firstRow = (array)$results[0];
                    $headers = array_keys($firstRow);


                    foreach ($headers as $colIndex => $header) {
                        $colLetter = $this->getColumnLetter($colIndex);
                        $activeSheet->setCellValue($colLetter . '1', ucfirst($header));
                    }
                    
                    $rowNum = 2;
                    foreach ($results as $row) {
                        $colIndex = 0;
                        foreach ((array)$row as $value) {
                            $colLetter = $this->getColumnLetter($colIndex);
                            $activeSheet->setCellValue($colLetter . $rowNum, $value);
                            $colIndex++;
                        }
                        $rowNum++;
                    }
                    $filename = 'report_' . time() . '.xlsx';
                    
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="' . $filename . '"');
                    header('Cache-Control: max-age=0');
                    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                    $writer->save('php://output');
                    exit;
                }
            }
        }
        catch(Exception $e){
            
            $response = ["status"=> false, "data"=> [], "message"=> "Exception: ".$e->getMessage()];
        }
    }

    
}