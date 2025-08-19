<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_datatable;
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

class demandCorrection extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $model_saf_dtl;
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
        parent::__construct();

        

        helper(['db_helper', 'utility_helper']);
        date_default_timezone_set("Asia/Calcutta");
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
		$this->db->close();
		$this->dbSystem->close();
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

    public function calDiffSafDemand($safTaxDtl, $saf_dtl_id, $prop_dtl_id, $ward_mstr_id)
	{
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

		$currentFY = getFY();
		// $currentFY = "2024-2025";
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
											// //$dueDate = date_create();
											// //$interval = date_diff($dueDate, $paymentDate);
											// $interval = abs(strtotime($paymentDate)-strtotime($due_date));
											// print_var($interval);
											// $years = floor($interval / (365*60*60*24));
											// //echo $diffmonth = $interval->format('%R%m');
											// echo $diffmonth = floor(($interval - $years * 365*60*60*24) / (30*60*60*24));
											// die();
											// //if($diffmonth > 0){$diffmonth = $diffmonth + 1;}
											// $penalty_amt_old = $penalty_amt_old + (($old_demand['amount']*$diffmonth)/100);
											// $penalty_amt_new = $penalty_amt_new + (($amount*$diffmonth)/100);
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

                        $sql = "SELECT COALESCE(SUM(amount), 0) AS total_amount FROM (SELECT fy_mstr_id, qtr, amount, balance, fyear, due_date FROM tbl_prop_demand WHERE prop_dtl_id=" . $prop_dtl_id . " AND status=1 AND paid_status=1 AND due_date='" . $due_date . "'
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

    public function getDataBymstrId($field_verification_dtl_id){
        try{
            $sql = "SELECT sfd.id,
            sfd.field_verification_dtl_id,
            sfd.saf_dtl_id,
            sfd.saf_floor_dtl_id,
            sfd.floor_mstr_id,
            floor.floor_name,
            sfd.usage_type_mstr_id,
            usage.usage_type,
            sfd.const_type_mstr_id,
            const.construction_type,
            sfd.occupancy_type_mstr_id,
            occup.occupancy_name,
            sfd.builtup_area,
            sfd.date_from,
            sfd.date_upto,
            sfd.emp_details_id,
            sfd.created_on,
            sfd.status,
            sfd.carpet_area
        FROM tbl_field_verification_floor_details sfd
            JOIN tbl_floor_mstr floor ON sfd.floor_mstr_id = floor.id AND floor.status = 1
            JOIN tbl_usage_type_mstr usage ON sfd.usage_type_mstr_id = usage.id
            JOIN tbl_const_type_mstr const ON sfd.const_type_mstr_id = const.id
            JOIN tbl_occupancy_type_mstr occup ON sfd.occupancy_type_mstr_id = occup.id
            where sfd.field_verification_dtl_id=?";
                $ql= $this->db->query($sql, [$field_verification_dtl_id]);
                $result =$ql->getResultArray();
                return $result;
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function calBuildingTaxDtl($floorDtlArr, $prop_type_mstr_id, $isAdditionaTaxImplemented)
	{
		$old_rule_arv_sub = [];
		$new_rule_arv_sub = [];
		$cv_rule_arv_sub = [];
		$vacantLandAdded = 0;
        $cv_rule_arv_sub24 = [];
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
                if ($date_upto_floor == "" || $date_upto_floor >= "2024-04-01") {
                    $cv_rule_arv_sub24[] = [
                        "fyear" => "2024-2025",
                        "qtr" => 1,
                        "arv" => $floorDtl['cv24'],
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
				if ($date_upto_floor=="" || $date_upto_floor>="2022-04-01") {
					$cv_rule_arv_sub[] = [
										"fyear"=>"2022-2023",
										"qtr"=>1,
										"arv"=>$floorDtl['cv'],
										"operator"=>$floorDtl['operator']
									];
				}
                if ($date_upto_floor == "" || $date_upto_floor >= "2024-04-01") {
                    $cv_rule_arv_sub24[] = [
                        "fyear" => "2024-2025",
                        "qtr" => 1,
                        "arv" => $floorDtl['cv24'],
                        "operator" => $floorDtl['operator']
                    ];
                }
			} else if ($yyyyMMDDFloor >= "2022-04-01" && "2024-04-01" > $yyyyMMDDFloor) { //2nd
				if ($floorDtl['type']=="vacant" && $vacantLandAdded==0) {
					$vacantLandAdded++;
					$cv_rule_arv_sub[] = [
									"VACANT_TYPE"=>"VACANT",
									"fyear"=>$floorDtl['fyear'],
									"qtr"=>$floorDtl['qtr'],
									"arv"=>$floorDtl['cv'],
									"operator"=>$floorDtl['operator']
								];
				} else {
                    if ($date_upto_floor == "" || $date_upto_floor >= "2022-04-01") {
                        $cv_rule_arv_sub[] = [
                            "fyear" => $floorDtl['fyear'],
                            "qtr" => $floorDtl['qtr'],
                            "arv" => $floorDtl['cv'],
                            "operator" => $floorDtl['operator']
                        ];
                    }
                    if ($date_upto_floor == "" || $date_upto_floor >= "2024-04-01") {
                        $cv_rule_arv_sub24[] = [
                            "fyear" => "2024-2025",
                            "qtr" => 1,
                            "arv" => $floorDtl['cv24'],
                            "operator" => $floorDtl['operator']
                        ];
                    }
				}
            } else if ($yyyyMMDDFloor >= "2024-04-01") {
                if ($floorDtl['type'] == "vacant" && $vacantLandAdded == 0) {
                    $vacantLandAdded++;
                    $cv_rule_arv_sub24[] = [
                        "VACANT_TYPE" => "VACANT",
                        "fyear" => $floorDtl['fyear'],
                        "qtr" => $floorDtl['qtr'],
                        "arv" => $floorDtl['cv24'],
                        "operator" => $floorDtl['operator']
                    ];
                } else {
                    $cv_rule_arv_sub24[] = [
                        "fyear" => $floorDtl['fyear'],
                        "qtr" => $floorDtl['qtr'],
                        "arv" => $floorDtl['cv24'],
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

        //new rule 2024
        $effectQtr = (int)0;
        $effectFy = "";
        $cv_arv_total = 0;
        foreach ($cv_rule_arv_sub24 as $key => $arvDtl) {
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
                $safTaxDtl[$taxCount++] = ["rule_type" => "CV_RULE2024", "fyear" => $effectFy, "qtr" => $effectQtr, "arv" => $cv_arv_total, "holding_tax" => $holding_tax, "additional_tax" => $additional_tax, "quarterly_tax" => $quarterly_tax];
            } else {
                $safTaxDtl[$taxCount - 1] = ["rule_type" => "CV_RULE2024", "fyear" => $effectFy, "qtr" => $effectQtr, "arv" => $cv_arv_total, "holding_tax" => $holding_tax, "additional_tax" => $additional_tax, "quarterly_tax" => $quarterly_tax];
            }
        }

		return [$safTaxDtl, $old_rule_arv_sub, $new_rule_arv_sub, $cv_rule_arv_sub, $cv_rule_arv_sub24];
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


    public function index()
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
		        // $currentFY = "2024-2025";
                $data = arrFilterSanitizeString($this->request->getVar());
                $resultArr = json_decode(stripslashes(html_entity_decode($data['encoded_data'])), true);
                $this->db->transBegin();
                if($resultArr['prop_dtl_id'])
                {
                   
                    $prop_dtl_id = $resultArr['prop_dtl_id'];
                    $saf_dtl_id = $resultArr['saf_dtl_id'];
                    $ward_mstr_id = $resultArr['ward_mstr_id'];
                    $new_ward_mstr_id = $resultArr['new_ward_mstr_id'];
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
									
                                    $newFY = $from_y1_new . "-" . $from_y2_new;
                                    $adjust_amt = 0;
                                    $demandAmt = $taxDtl['quarterly_tax'] - $taxDtl['additional_tax'];
                                    $amount = $taxDtl['quarterly_tax'];
									$quarterly_tax = $taxDtl['quarterly_tax'];
                                    $additional_tax = $taxDtl['additional_tax'];
                                    $due_date = $this->makeDueDateByFyearQtr($newFY, $q);
									

                                    $sql = "SELECT COALESCE(SUM(amount), 0) AS total_amount FROM (SELECT fy_mstr_id, qtr, amount, balance, fyear, due_date FROM tbl_prop_demand WHERE prop_dtl_id=" . $prop_dtl_id . " AND status=1 AND paid_status=1 AND due_date='" . $due_date . "'
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
                    $url=base_url('DemandCorrection/index/');
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
                        $this->db->table('tbl_field_verification_floor_details')->where('id', $floor)->update(['date_upto'=> null]);
                    }
                    flashToast("message", "Upto date updated sucessfully");
                    $url=base_url('DemandCorrection/index/');
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
                    tbl_field_verification_dtl.id as verification_id,
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
                    tbl_saf_dtl.land_occupation_date,
                    tbl_prop_dtl.apartment_details_id,
                    tbl_prop_dtl.saf_dtl_id
                FROM tbl_prop_dtl 
                LEFT JOIN tbl_saf_dtl ON tbl_prop_dtl.saf_dtl_id=tbl_saf_dtl.id
                LEFT JOIN (SELECT max(id) as max_verification_id,saf_dtl_id from tbl_field_verification_dtl where status=1 group by saf_dtl_id) max_verification ON max_verification.saf_dtl_id=tbl_prop_dtl.saf_dtl_id
                LEFT JOIN tbl_field_verification_dtl ON max_verification.max_verification_id=tbl_field_verification_dtl.id
                WHERE (tbl_prop_dtl.new_holding_no='".$data['holding_no']."') and  tbl_saf_dtl.saf_pending_status=1";
                $record = $this->db->query($sql)->getRowArray();
                //print_var($result);
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
                    $inputs['new_ward_mstr_id'] = $record['new_ward_mstr_id'];
                    $inputs['zone_mstr_id'] = $record['zone_mstr_id'];
                    $inputs["prop_type_mstr_id"] = $record['prop_type_mstr_id'];
                    $inputs['road_type_mstr_id'] = $record['road_type_mstr_id'];
                    $inputs["area_of_plot"] = $record['area_of_plot'];
                    $inputs["tower_installation_date"] = $record['tower_installation_date'];
                    $inputs["tower_area"] = $record['tower_area'];
                    $inputs["hoarding_installation_date"] = $record['hoarding_installation_date'];
                    $inputs["hoarding_area"] = $record['hoarding_area'];
                    $inputs["petrol_pump_completion_date"] = $record['petrol_pump_completion_date'];
                    $inputs["under_ground_area"] = $record['under_ground_area'];
                    $inputs["percentage_of_property_transfer"] = $record['percentage_of_property_transfer'];

                    if ($record["is_mobile_tower"]=="t") $inputs["is_mobile_tower"] = 1; else $inputs["is_mobile_tower"] = 0;
                    if ($record["is_hoarding_board"]=="t") $inputs["is_hoarding_board"] = 1; else $inputs["is_hoarding_board"] = 0;
                    if ($record["is_petrol_pump"]=="t") $inputs["is_petrol_pump"] = 1; else $inputs["is_petrol_pump"] = 0;
                    if ($record["is_water_harvesting"]=="t") $inputs["is_water_harvesting"] = 1; else $inputs["is_water_harvesting"] = 0;
                    
                    if ($record['prop_type_mstr_id'] == 4) {
                        $inputs["land_occupation_date"] = $record["land_occupation_date"];
                        $vacantDtlArr = $safHelper->makeVacantFloorDtlArr($inputs);
                        list($newSafTaxDtl, $old_rule_arv_sub, $new_rule_arv_sub, $cv_rule_arv_sub) = $safHelper->calVacantTaxDtl($vacantDtlArr);
                    }else{
                        $field_verifcation_floor_dtl = $this->getDataBymstrId($record['verification_id']);

                        $floorKey = 0;
                        foreach ($field_verifcation_floor_dtl as $key => $value) {
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
                        list($newSafTaxDtl, $old_rule_arv_sub, $new_rule_arv_sub, $cv_rule_arv_sub,$cv_rule_arv_sub24) = $this->calBuildingTaxDtl($floorDtlArr, $record['prop_type_mstr_id'], $isAdditionaTaxImplemented);
                        
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
					$result["floorList"] = $field_verifcation_floor_dtl??[];
                    $result['encode_data'] = json_encode($result);
                }else{
                    $result["tax_details1"] = "";
                }
            }

            

            return view('demand_correction', $result);
        }catch(Exception $e){
            print_r($e);
        }
    }

    public function olderDemand($prop_dtl_id)
    {
        $sql="SELECT sum(balance) as demand_amount, string_agg(tbl_prop_demand.id::text, ',') as demand_ids FROM tbl_prop_demand where status=1 and paid_status=0 and prop_dtl_id=".$prop_dtl_id."";
        $resultArr = $this->db->query($sql)->getRowArray();
        
        $balance_amount = $resultArr['demand_amount'];
        $demand_ids = $resultArr['demand_ids'];

        return [$balance_amount, $demand_ids];
    }

    public function index2()
    {
        exit();
        $safHelper = new SAFHelper($this->db);
        $sql="SELECT tbl_prop_dtl.*,tbl_saf_dtl.saf_pending_status,tbl_prop_dtl.id as prop_dtl_id FROM tbl_prop_dtl
                        JOIN tbl_saf_dtl on tbl_prop_dtl.saf_dtl_id=tbl_saf_dtl.id
                        WHERE tbl_prop_dtl.status=1 and tbl_prop_dtl.prop_type_mstr_id!=4 and tbl_prop_dtl.id in(516,
611,
613,
670,
678,
706,
743,
924,
928,
998,
1063,
1115,
1286,
1323,
1329,
1414,
1431,
1498,
1563,
1571,
1612,
1656,
1675,
1680,
1690,
1816,
1860,
2625,
2626,
3025,
3166,
3357,
3501,
3519,
3837,
3924,
3999,
4472,
4809,
4819,
4856,
4941,
5241,
5343,
6367,
6848,
7183,
7187,
7207,
7208,
7230,
7270,
7311,
7358,
7413,
7453,
7477,
7499,
7505,
7690,
7788,
7802,
7869,
8009,
8033,
8035,
8110,
8115,
8144,
8389,
8437,
8448,
8466,
8469,
8472,
8496,
8502,
8566,
8605,
8606,
8653,
8745,
8809,
8829,
8961,
9089,
9110,
9144,
9294,
9322,
9451,
9576,
9590,
9610,
9644,
9744,
9749,
9880,
9883,
9950,
9992,
10000,
10008,
10011,
10078,
10101,
10178,
10300,
10408,
10434,
10443,
10469,
10504,
10527,
10549,
10556,
10684,
10706,
10743,
10800,
10862,
10873,
10945,
10969,
11074,
11115,
11123,
11163,
11259,
11378,
11393,
11446,
11449,
11572,
11625,
11628,
11643,
11674,
11730,
11765,
11798,
11816,
11897,
11899,
12019,
12061,
12066,
12148,
12171,
12175,
12213,
12243,
12264,
12321,
12368,
12464,
12645,
13101,
13713,
13724,
13867,
13906,
13938,
13945,
13972,
13992,
14048,
14093,
14097,
14189,
14225,
14267,
14280,
14417,
14457,
14574,
15050,
15077,
15106,
15178,
15217,
15243,
15312,
15482,
15524,
16429,
16538,
16630,
16682,
16708,
16854,
17130,
17173,
17306,
17322,
17334,
17354,
17398,
17861,
17865,
18153,
18203,
18227,
18498,
18708,
18868,
19413,
19424,
19425,
19473,
19484,
19531,
19644,
19891,
19894,
19897,
20137,
20198,
20456,
20782,
21324,
21326,
21327,
22447,
23312,
23422,
23765,
23783,
24544,
24733,
24835,
24977,
25109,
25181,
25359,
25489,
25515,
25522,
25532,
25669,
25703,
25717,
25908,
26014,
26022,
26106,
26195,
26419,
26433,
26537,
26656,
26701,
27030,
27114,
27291,
27377,
27382,
27507,
27931,
27962,
28500,
28501,
28509,
28526,
28717,
28731,
28847,
28901,
28946,
28952,
29045,
29100,
29345,
29377,
29489,
29511,
29607,
29609,
29611,
29613,
29626,
29631,
29676,
29748,
29797,
29824,
30102,
30249,
30255,
30563,
30714,
30720,
30963,
30982,
31040,
31348,
31418,
31586,
31721,
31775,
31776,
31825,
31893,
31905,
32087,
32249,
32260,
32337,
32479,
32723,
32768,
32794,
32961,
33116,
33142,
33143,
33159,
33195,
33199,
33214,
33222,
33311,
33312,
33360,
33373,
33413,
33417,
33492,
33510,
33593,
33598,
33610,
33654,
33769,
33802,
33902,
33937,
34025,
34050,
34089,
34116,
34131,
34283,
34289,
34355,
34536,
34545,
34578,
34604,
34606,
34658,
34660,
34677,
34678,
34777,
34906,
35008,
35041,
35522,
35803,
35807,
35834,
35967,
36028,
36120,
36212,
36288,
36896,
36939,
36946,
37171,
37358,
37499,
39036,
39557,
40037,
42755,
42861,
42904,
43272,
43335,
43687,
43746,
44053,
44558,
44653,
44785,
45011,
45027,
45033,
45064,
45068,
45210,
45284,
45615,
45693,
45739,
45855,
46081,
46125,
46463,
46942,
47311,
47477,
48460,
48699,
48713,
48726,
48776,
48877,
49130,
49345,
49418,
49420,
49421,
49423,
49469,
49529,
49719,
49967,
50186,
50556,
50688,
51471,
51518,
51606,
51768,
51780,
51830,
51954,
52079,
52087,
52099,
52144,
52231,
52396,
53813,
55955,
56666,
56821,
57050,
57090,
57378,
57474,
57607,
57614,
57654,
57679,
57833,
57959,
58024,
59280,
59302,
59306,
59307,
59309,
59310,
59312,
59317,
59319,
60019,
60282,
60353,
60398,
60524,
60772,
60817,
61431,
61947,
62233,
62481,
62537,
62718,
63755,
64165,
64711,
64791,
65274,
65380,
65802,
65838,
66396,
66929,
67049,
67188,
67387,
67417,
67483,
68059,
68076,
68091,
68111,
68167,
68531,
68702,
69060,
69195,
69421,
69569,
69585,
69841,
69859,
70005,
70124,
70300,
70329,
70332,
70364,
70367,
70368,
70373,
70560,
70578,
70673,
71276,
71812,
72079,
72429,
72634,
73974,
74171,
74225,
74988,
75075,
75321,
75554,
75770,
75867,
75873,
76154,
76238,
76468,
76576,
76637,
76649,
76650,
76656,
76705,
76731,
76741,
76771,
76859,
76895,
77263,
77866,
78462,
78629,
79499,
79615,
79622,
79658,
79874,
79891,
80254,
81325,
81555,
81908,
82199,
82425,
82643,
82768,
83009,
83061,
83233,
83313,
83319,
85177,
87036,
87065,
87777,
88047,
88064,
88416,
88622,
89179,
89942,
90276,
90924,
91766,
91779,
91808,
91958,
92030,
92037,
92119,
92228,
92462,
92833,
92839,
92934,
93259,
93345,
93393,
93659,
94092,
94138,
94806,
95131,
95135,
95379,
95412,
95459,
95557,
95585,
95672,
95678,
95819,
95877,
95927,
96069,
96159,
96384,
96618,
96739,
96840,
96892,
97063,
97196,
97284,
97318,
97364,
97408,
97446,
97529,
97856,
98047,
98115,
98139,
98167,
98205,
98221,
98279,
98394,
98438,
98444,
98544,
98842,
99047,
99126,
99357,
99376,
99588,
99647,
99682,
99757,
99770,
99881,
99964,
100243,
100251,
100342,
100371,
100453,
100572,
100628,
100696,
100728,
100729,
100795,
100806,
100811,
100867,
100898,
101089,
101153,
101512,
101553,
101579,
101588,
101625,
101819,
101868,
101962,
102046,
102080,
102122,
102169,
102171,
102259,
102556,
102856,
103362,
103825,
104351,
104397,
104410,
104457,
105243,
106490,
106646,
107105,
107256,
107606,
108379,
108463,
108477,
108519,
108598,
108733,
108791,
108941,
109121,
109230,
109279,
109569,
109740,
110202,
110352,
110386,
110428,
111064,
111140,
111254,
111571,
111812,
112008,
112151,
112175,
112323,
112332,
112370,
112697,
113034,
113053,
113473,
113487,
113532,
113568,
113787,
114078,
114138,
114416,
114674,
114741,
114910,
114918,
114937,
115011,
115036,
115090,
115105,
115194,
115297,
115908,
115938,
115971,
116202,
116208,
116249,
116605,
116814,
116936,
116961,
117280,
117447,
117449,
117900,
117975,
118095,
118137,
118465,
118480,
118690,
118759,
118768,
118838,
118952,
119133,
119246,
119291,
119364,
119594,
119801,
119903,
120059,
120254,
120299,
120484,
120486,
120716,
121119,
121301,
121349,
121460,
121611,
121618,
121670,
121675,
121691,
121755,
121806,
121810,
121842,
121847,
121861,
121874,
121889,
121939,
121965,
122186,
122322,
122454,
122457,
122513,
122613,
122661,
122691,
123141,
123501,
123552,
123605,
123612,
123702,
123795,
123898,
123944,
123965,
124009,
124153,
124377,
124410,
124499,
124702,
125145,
125148,
125168,
125391,
125403,
125412,
125601,
125644,
125676,
125688,
125719,
125749,
125752,
125753,
125911,
126017,
126128,
126168,
126356,
126393,
126399,
126787,
126819,
127029,
127239,
127283,
127332,
127374,
127397,
127472,
127487,
127598,
127823,
127911,
128044,
128105,
128118,
128169,
128200,
128206,
128246,
128376,
128630,
128880,
128900,
129145,
129229,
129521,
129545,
129564,
129709,
129879,
129955,
129960,
130191,
130224,
130409,
130833,
131147,
131208,
131352,
131527,
131745,
131965,
132113,
132180,
132239,
132316,
132324,
132360,
132774,
132838,
132965,
133071,
133117,
133307,
133308,
133310,
133461,
133493,
133501,
133550,
133595,
133684,
133829,
133896,
133898,
134177,
134622,
134689,
134702,
135040,
135046,
135116,
135167,
135401,
135403,
135458,
135524,
135549,
135659,
135671,
135677,
135853,
135861,
135927,
136002,
136171,
136227,
136282,
136552,
136615,
136924,
137169,
137196,
138149,
138332,
138694,
138725,
139035,
139168,
139190,
139213,
139534,
139564,
139618,
139678,
139733,
139737,
139746,
139817,
139828,
139833,
139858,
139860,
139861,
139916,
140476,
140853,
140939,
141194,
141464,
141619,
141626,
141657,
141940,
141981,
142208,
142280,
142368,
142549,
142554,
142602,
142611,
143076,
143078,
143130,
143146,
143555,
143606,
143611,
143660,
143949,
144141,
144157,
144177,
144542,
144646,
144911,
144935,
144943,
144959,
145241,
145478,
145593,
145673,
145754,
146204,
146250,
146804,
146963,
147667,
147759,
147889,
148154,
148277,
148450,
148670,
148817,
148916,
149673,
149711,
149722,
149955,
150102,
150126,
150678,
150720,
150944,
150966,
151030,
152337,
152399,
152500,
152534,
152885,
152986,
153271,
153283,
153288,
153618,
153758,
153894,
154207,
154273,
154274,
154287,
154292,
154294,
154295,
154298,
154863,
154999,
155147,
155259,
155629,
155649,
155665,
155711,
155734,
155778,
155829,
156060,
156544,
156989,
157308,
157327,
157521,
157575,
157935,
158742,
159008,
159237,
159303,
159547,
159745,
159858,
159975,
160047,
160094,
160101,
160141,
160144,
160156,
160160,
160257,
160287,
160399,
160437,
160627,
160771,
161338,
161352,
161536,
161683,
161805,
161856,
162354,
162595,
162693,
163068,
163130,
163423,
163640,
163821,
163870,
164089,
164212,
164284,
164343,
164362,
164394,
164421,
164426,
164578,
164660,
164692,
164767,
164914,
164922,
164962,
165098,
165145,
165174,
165279,
165340,
165460,
165742,
165758,
165761,
165769,
165836,
165937,
166067,
166088,
166563,
166879,
166993,
167082,
167354,
167517,
168608,
168657,
168726,
168886,
168953,
169317,
169589,
169658,
169662,
169719,
169918,
170117,
170138,
170327,
170503,
170614,
170615,
170638,
170786,
170870,
170901,
171444,
171501,
171605,
171957,
171999,
172036,
172508,
172513,
172554,
172561,
172758,
173057,
173128,
173560,
173823,
174111,
174533,
174925,
174938,
174939,
174940,
174941,
175134,
175138,
175737,
175963,
176583,
176644,
176784,
177913,
178288,
178773,
178782,
179112,
179152,
179156,
179232,
179432,
181769,
181781,
181842,
181856,
182116,
182268,
182343,
182399,
182884,
182885,
183087,
183108,
183159,
183169,
183332,
183368,
183440,
183525,
183527,
183529,
183690,
183732,
183852,
183907,
183982,
184049,
184080,
184143,
184246,
184331,
184796,
184888,
184913,
185017,
185233,
185321,
185400,
185422,
185840,
186011,
186169,
186213,
186660,
186815,
186882,
187106,
187311,
187461,
187781,
188375,
188379,
188431,
188672,
188673,
188675,
188782,
188954,
189089,
189138,
189582,
189793,
189821,
189823,
189824,
189884,
189885,
189886,
189887,
189888,
189918,
190069,
190402,
190468,
190840,
190847,
190854,
190888,
190979,
190983,
191145,
191244,
191398,
191460,
191464,
191514,
191524,
191961,
192286,
192295,
192392,
192401,
192445,
192685,
192722,
193038,
193115,
193150,
193240,
193268,
193269,
193577,
193623,
193631,
193637,
193650,
193969,
194308,
194418,
194510,
194734,
194817,
194897,
194931,
195188,
195211,
195257,
195283,
195301,
195590,
195757,
195785,
195907,
195981,
195997,
196013,
196111,
196162,
196647,
196675,
196744,
196962,
197115,
197182,
197196,
197232,
197249,
197436,
197793,
197807,
197890,
197891,
197918,
198037,
198190,
198255,
198327,
198598,
198741,
198826,
199212,
199459,
199742,
200448,
200544,
200881,
200882,
201151,
201185,
201502,
201625,
201641,
201890,
201893,
201902,
201916,
202077,
202357,
202482,
202485,
202970,
203138,
203748,
203755,
203771,
203873,
203875,
203921,
204043,
204076,
204083,
204809,
204820,
205177,
205329,
205718,
205808,
206183,
206297,
206506,
206518,
206583,
206598,
206616,
206649,
206681,
206820,
206826,
206827,
206997,
207062,
207384,
208502,
208715,
208910,
209263,
209379,
209568,
209604,
209661,
209668,
209674,
209695,
209698,
210020,
210223,
210846,
211208,
211960,
212474,
212525,
212589,
213362,
213838,
213861,
215085,
215391,
215676,
215698,
215873,
217636,
217808,
218228,
219615,
221945,
222108,
222897,
223258,
223401,
223751,
223964,
226498,
226799,
226842,
226872,
227274,
227284,
227316,
227427,
227465,
227850,
227894,
227899,
227937,
227946,
228061,
228071,
228141,
228174,
228594,
228646,
229289,
230450,
231026,
231850,
232917,
233051,
233118,
234194,
235115,
235183,
246577,
246684,
248407,
253804,
262590,
262591,
262592)";

        $resultArrs = $this->db->query($sql)->getResultArray();
        $newSafTaxDtl = array();
        $currentFY = '2023-2024';
        foreach($resultArrs as $resultArr)
        {
            $prop_dtl_id = $resultArr['prop_dtl_id'];
            $ward_mstr_id = $resultArr['ward_mstr_id'];
            $saf_dtl_id = $resultArr['saf_dtl_id'];
            list($old_demand, $old_demand_ids) = $this->olderDemand($prop_dtl_id);
            $new_demand_ids = null;

            if($resultArr['saf_pending_status'] == 1)
            {
                $record = $this->model_field_verification_dtl->getdatabysafid($resultArr['saf_dtl_id']);
                $record['verification_id'] = $record['id'];
                $record["occupation_date"] = $resultArr["occupation_date"];
            }else{
                $record = $resultArr;
                $record["percentage_of_property_transfer"] = null;
            }
            if(isset($record['apartment_details_id']))
            {
                $apt = $this->model_apartment_details->getApartmentDtlById($record['apartment_details_id']);
                $record["is_water_harvesting"] = ($apt['water_harvesting_status'] == 1)?'t':'f';
            }

            $old_rwh = "select * from tbl_water_hrvesting_declaration_dtl_olddata where approval_status=1 and prop_dtl_id=".$prop_dtl_id;
            $rwh_records = $this->db->query($old_rwh)->getFirstRow();
            if($rwh_records)
            {
                $record["is_water_harvesting"] = 't';
            }

            $inputs = array();
            $inputs['ward_mstr_id'] = $record['ward_mstr_id'];
            $inputs['zone_mstr_id'] = $resultArr['zone_mstr_id'];
            $inputs["prop_type_mstr_id"] = $record['prop_type_mstr_id'];
            $inputs['road_type_mstr_id'] = $record['road_type_mstr_id'];
            $inputs["area_of_plot"] = $record['area_of_plot'];
            $inputs["tower_installation_date"] = $record['tower_installation_date'];
            $inputs["tower_area"] = $record['tower_area'];
            $inputs["hoarding_installation_date"] = $record['hoarding_installation_date'];
            $inputs["hoarding_area"] = $record['hoarding_area'];
            $inputs["petrol_pump_completion_date"] = $record['petrol_pump_completion_date'];
            $inputs["under_ground_area"] = $record['under_ground_area'];
            $inputs["percentage_of_property_transfer"] = $record['percentage_of_property_transfer'];

            if ($record["is_mobile_tower"]=="t") $inputs["is_mobile_tower"] = 1; else $inputs["is_mobile_tower"] = 0;
            if ($record["is_hoarding_board"]=="t") $inputs["is_hoarding_board"] = 1; else $inputs["is_hoarding_board"] = 0;
            if ($record["is_petrol_pump"]=="t") $inputs["is_petrol_pump"] = 1; else $inputs["is_petrol_pump"] = 0;
            if ($record["is_water_harvesting"]=="t") $inputs["is_water_harvesting"] = 1; else $inputs["is_water_harvesting"] = 0;
            
            if ($record['prop_type_mstr_id'] == 4) {
                //$inputs["land_occupation_date"] = $record["occupation_date"];
                //$vacantDtlArr = $safHelper->makeVacantFloorDtlArr($inputs);
                //$field_verifcation_floor_dtl = array();
                //list($newSafTaxDtl, $old_rule_arv_sub, $new_rule_arv_sub, $cv_rule_arv_sub) = $safHelper->calVacantTaxDtl($vacantDtlArr);
            }else{
                if($resultArr['saf_pending_status'] == 1){
                    $sqlveri = "select tbl_field_verification_dtl.id from tbl_field_verification_dtl 
                                left join (select field_verification_dtl_id from tbl_field_verification_floor_details where date_upto is not null group by field_verification_dtl_id) tbl_field_verification_floor_details on tbl_field_verification_floor_details.field_verification_dtl_id=tbl_field_verification_dtl.id
                                where tbl_field_verification_floor_details.field_verification_dtl_id is null and id=".$record['verification_id']."
                                ";
                    $checkfield_verification =  $this->db->query($sqlveri)->getRowArray();
                    if($checkfield_verification && $checkfield_verification['id'])
                    { 
                        $field_verifcation_floor_dtl = $this->model_field_verification_floor_details->getFloorDataBymstrId($record['verification_id']);
                        if(count($field_verifcation_floor_dtl)==0)
                        {
                            $field_verifcation_floor_dtl = $this->model_prop_floor_details->getViewPropFloorDtlByPropDtlId(['prop_dtl_id'=>$prop_dtl_id]);
                        }
                    }else{
                        
                        $field_verifcation_floor_dtl = $this->model_prop_floor_details->getViewPropFloorDtlByPropDtlId(['prop_dtl_id'=>$prop_dtl_id]);
                    }
                }else{
                    $field_verifcation_floor_dtl = $this->model_prop_floor_details->getViewPropFloorDtlByPropDtlId(['prop_dtl_id'=>$prop_dtl_id]);
                }
                //$field_verifcation_floor_dtl = $this->getDataBymstrId($record['verification_id']);

                $floorKey = 0;
                foreach ($field_verifcation_floor_dtl as $key => $value) {
                    $date_fromarra = explode('-', $value["date_from"]);

                    if($date_fromarra[0] <= 1970){
                        $date_from = '1970-04-01';
                    }else{
                        $date_from = $value["date_from"];
                    }

                    $inputs["floor_mstr_id"][$floorKey] = $value["floor_mstr_id"];
                    $inputs["usage_type_mstr_id"][$floorKey] = $value["usage_type_mstr_id"];
                    $inputs["const_type_mstr_id"][$floorKey] = $value["const_type_mstr_id"];
                    $inputs["occupancy_type_mstr_id"][$floorKey] = $value["occupancy_type_mstr_id"];
                    $inputs["builtup_area"][$floorKey] = $value["builtup_area"];
                    $inputs["date_from"][$floorKey] = date("Y-m", strtotime($date_from));
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

            list($tax_to_be_generate, $advance_amount, $trans_id) = $this->calDiffSafDemand($newSafTaxDtl, $record['saf_dtl_id'], $prop_dtl_id, $record['ward_mstr_id']);

            $this->db->transBegin();
            $this->model_prop_tax->updateSafDeactiveStatus(['prop_dtl_id'=>$prop_dtl_id]);
            $this->db->table('tbl_prop_demand')->where('prop_dtl_id',$prop_dtl_id)->where('paid_status',0)->where('status',1)
            ->update(['status'=>0, 'balance'=>0.00]);
            if($newSafTaxDtl)
            {
                $payable_amount = 0;
                foreach($newSafTaxDtl as $key => $taxDtl)
                {
                    $pymt_frm_qtr = (int)$taxDtl['qtr'];
                    $pymt_frm_year = (string)$taxDtl['fyear'];

                    $pymt_upto_qtr = (int)4;
                    $pymt_upto_year = (string)$currentFY;
                    if ($key < sizeof($newSafTaxDtl) - 1) {
                        $pymt_upto_qtr = (int)$newSafTaxDtl[$key + 1]['qtr'] - 1;
                        $pymt_upto_year = (string)$newSafTaxDtl[$key + 1]['fyear'];
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
                                
                                $newFY = $from_y1_new . "-" . $from_y2_new;
                                $adjust_amt = 0;
                                $demandAmt = $taxDtl['quarterly_tax'] - $taxDtl['additional_tax'];
                                $amount = $taxDtl['quarterly_tax'];
                                $quarterly_tax = $taxDtl['quarterly_tax'];
                                $additional_tax = $taxDtl['additional_tax'];
                                $due_date = $this->makeDueDateByFyearQtr($newFY, $q);
                                

                                $sql = "SELECT COALESCE(SUM(amount), 0) AS total_amount FROM (SELECT fy_mstr_id, qtr, amount, balance, fyear, due_date FROM tbl_prop_demand WHERE prop_dtl_id=" . $prop_dtl_id . " AND status=1 AND paid_status=1 AND due_date='" . $due_date . "'
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
                                
                                if($quarterly_tax > 0 && round($demandAmt+$additional) > 0) 
                                {
                                    $payable_amount = $payable_amount + round($demandAmt+$additional, 2);
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
                                    $new_demand_ids .=",". $this->db->insertID();

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
                    $advance_amount = $advance_amount;
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
                
                
                $new_demand = $payable_amount;
                $new_demand_ids = trim($new_demand_ids, ',');

                $logentrysql = "INSERT INTO log_tbl_demand_correction (prop_dtl_id, created_on, created_by, old_demand, new_demand, advance_amount, old_demand_ids, new_demand_ids)
                VALUES ('$prop_dtl_id', '" . date("Y-m-d H:i:s") ."', '1', '$old_demand', '$new_demand', '$advance_amount', '$old_demand_ids', '$new_demand_ids') returning id";
                $logentry = $this->db->query($logentrysql);
            }
            //$this->db->transRollback();
            if($this->db->transStatus() == FALSE)
            {
                $this->db->transRollback();
            }else{
                $this->db->transCommit();
                echo 'Advance or demand generated sucessfully';
            }
        }
    }
    
}