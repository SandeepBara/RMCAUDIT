<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\model_prop_dtl;
use App\Models\model_prop_owner_detail;
use App\Models\model_prop_tax;
use App\Models\model_prop_demand;
use App\Models\model_fy_mstr;
use App\Models\model_prop_type_mstr;
use App\Models\model_road_type_mstr;
use App\Models\model_ownership_type_mstr;
use App\Models\model_ulb_mstr;
use App\Models\model_legacy_demand_update;

class LegacyEntry extends AlphaController
{
    protected $db;
    protected $dbSystem;
    public function __construct(){
        parent::__construct();
    	helper(['db_helper','validation_helper','validate_legacy_helper']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name);            
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        }
        $this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_fy_mstr = new model_fy_mstr($this->dbSystem);
        $this->model_prop_dtl = new model_prop_dtl($this->db);
        $this->model_prop_owner_detail = new model_prop_owner_detail($this->db);
        $this->model_prop_tax = new model_prop_tax($this->db);
        $this->model_prop_demand = new model_prop_demand($this->db);
        $this->model_prop_type_mstr = new model_prop_type_mstr($this->db);
        $this->model_road_type_mstr = new model_road_type_mstr($this->db);  
        $this->model_ownership_type_mstr = new model_ownership_type_mstr($this->db); 
        $this->model_legacy_demand_update = new model_legacy_demand_update($this->db); 
    }

    function __destruct() {
		$this->db->close();
		$this->dbSystem->close();
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

    // public function form()
	// {
    //     $data =(array)null;
    //     helper(['form']);
    //     date_default_timezone_set('Asia/Kolkata');
    //     $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
	// 	$emp_details_id = $_SESSION['emp_details']['id'];
    //     $ulb_address = $this->model_ulb_mstr->getAddressById(['ulb_mstr_id'=>$ulb_mstr_id]);
    //     $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
    //     $ownershipTypeList = $this->model_ownership_type_mstr->getOwnershipTypeList();
    //     $propTypeList = $this->model_prop_type_mstr->getPropTypeList();
	// 	$roadTypeList = $this->model_road_type_mstr->getRoadTypeList();
    //     $data['wardList'] = $wardList;
    //     $data['ownershipTypeList'] = $ownershipTypeList;
    //     $data['propTypeList'] = $propTypeList;
    //     $data['roadTypeList'] = $roadTypeList;
    //     $data['ulb_address'] = $ulb_address;
    //     if($this->request->getMethod()=='post'){
    //         $inputs = arrFilterSanitizeString($this->request->getVar());
    //         $errMsg = validateLegacyEntry($inputs);
    //         if (empty($errMsg)){
    //             $data = $inputs;
    //             $data['wardList'] = $wardList;
    //             $data['ownershipTypeList'] = $ownershipTypeList;
    //             $data['propTypeList'] = $propTypeList;
    //             $data['roadTypeList'] = $roadTypeList;
    //             $data['ulb_address'] = $ulb_address;
    //             if(isset($_POST['btn_submit'])){

    //                 $data_input = [
    //                     'ward_mstr_id'=>$inputs['ward_mstr_id'],
    //                     'holding_no'=>strtoupper($inputs['holding_no']), 
    //                     'ownership_type_mstr_id'=>$inputs['ownership_type_mstr_id'], 
	// 			        'prop_type_mstr_id'=>$inputs['prop_type_mstr_id'], 
    //                     'road_type_mstr_id'=>$inputs['road_type_mstr_id'], 
    //                     'prop_address'=>strtoupper($inputs['prop_address']), 
	// 			        'prop_city'=>$inputs['prop_city'], 
	// 			        'prop_dist'=>$inputs['prop_dist'], 
	// 			        'prop_pin_code'=>$inputs['prop_pin_code'],
    //                      'emp_details_id' => $emp_details_id,
    //                      'created_on' =>date('Y-m-d H:i:s'),
    //                     'entry_type' =>'Legacy'
    //                 ];
    //                 $prop_dtl_last_id = $this->model_prop_dtl->insertData($data_input);
    //                 $sql_json = "INSERT INTO tbl_prop_json_dtl (prop_dtl_id, prop_dtl, status) VALUES (".$prop_dtl_last_id.", get_json_prop_dtl(".$prop_dtl_last_id."), 1);";
    //                 $this->db->query($sql_json);
    //                // die();
    //                 //$prop_dtl_last_id='55';
    //                 for ( $i=0; $i < sizeof($inputs['owner_name']); $i++ ) {
	// 				$input = [
	// 					'prop_dtl_id'=>$prop_dtl_last_id,
	// 					'owner_name'=>$inputs['owner_name'][$i],
	// 					'guardian_name'=>$inputs['guardian_name'][$i],
	// 					'relation_type'=>$inputs['relation_type'][$i],
	// 					'mobile_no'=>$inputs['mobile_no'][$i],
	// 					'aadhar_no'=>($inputs['aadhar_no'][$i]!="")?$inputs['aadhar_no'][$i]:null,
	// 					'pan_no'=>$inputs['pan_no'][$i],
	// 					'email'=>$inputs['email'][$i],
	// 					'emp_details_id'=>$emp_details_id,
	// 					'created_on'=>date('Y-m-d H:i:s'),
	// 					'status'=>1
	// 				];
	// 				$this->model_prop_owner_detail->insertData($input);
	// 			}
    //             $next_qtr = 0;
    //             for ( $im=0; $im < sizeof($inputs['date_from']); $im++ ) {
    //                 //echo $inputs['date_from'][$im];
    //                 //get fi yr qtr
    //                 $fromMM = date("m", strtotime($inputs['date_from'][$im]));
	// 				if($fromMM>=1 && 3>=$fromMM){ // X1
	// 					$from_qtr = 4;
	// 				}else if($fromMM>=4 && 6>=$fromMM){ // X4
	// 					$from_qtr = 1;
	// 				}else if($fromMM>=7 && 9>=$fromMM){ // X3
	// 					$from_qtr = 2;
	// 				}else if($fromMM>=10 && 12>=$fromMM){ // X2
	// 					$from_qtr = 3;
	// 				}
    //                 //get financial year
    //                 $from_month=date('n',strtotime($inputs['date_from'][$im].'-01'));
    //                 $from_year=date('Y',strtotime($inputs['date_from'][$im].'-01'));
    //                 if($from_month>3)
    //                 {
    //                     $from_fi_yr=$from_year."-".($from_year+1);
    //                 }
    //                 else{
    //                     $from_fi_yr=($from_year-1)."-".$from_year;
    //                 }
    //                 //get fi yr id
    //                 $data['from_fi'] = $this->model_fy_mstr->getFiidByfyyr($from_fi_yr);
    //                 $from_fi_yr_id=$data['from_fi']['id'];

    //                 $new_holding_tax=round(((($inputs['arv'][$im])*0.02)/4), 2);
    //                 $holding_tax=round(((($inputs['arv'][$im])*0.125)/4), 2);
    //                 $water_tax=round(((($inputs['arv'][$im])*0.075)/4), 2);
    //                 $education_cess=round(((($inputs['arv'][$im])*0.05)/4), 2);
    //                 $health_cess=round(((($inputs['arv'][$im])*0.0625)/4), 2);
    //                 $latrine_tax=round(((($inputs['arv'][$im])*0.075)/4), 2);
    //                 $additional_tax=0;
    //                 $prop_total_amount=$new_holding_tax;
    //                 $total_amount=$holding_tax + $water_tax + $education_cess + $health_cess +$latrine_tax + $additional_tax;

    //                 /******calculation********/
    //                 //get fi yr qtr of to date
    //                     $toMM = date("m", strtotime($inputs['date_upto'][$im]));
    //                     if($toMM>=1 && 3>=$toMM){ // X1
    //                         $to_qtr = 4;
    //                     }else if($toMM>=4 && 6>=$toMM){ // X4
    //                         $to_qtr = 1;
    //                     }else if($toMM>=7 && 9>=$toMM){ // X3
    //                         $to_qtr = 2;
    //                     }else if($toMM>=10 && 12>=$toMM){ // X2
    //                         $to_qtr = 3;
    //                     }
    //                     //get financial year of to date
    //                     $to_month=date('n',strtotime($inputs['date_upto'][$im].'-01'));
    //                     $to_year=date('Y',strtotime($inputs['date_upto'][$im].'-01'));
    //                     if($to_month>3)
    //                     {
    //                         $to_fi_yr=$to_year."-".($to_year+1);
    //                     }
    //                     else{
    //                         $to_fi_yr=($to_year-1)."-".$to_year;
    //                     }

    //                     //get financial year of current year
    //                     $curr_to_month=date('n');
    //                     $curr_to_year=date('Y');
    //                     if($curr_to_month>3)
    //                     {
    //                         $curr_to_fi_yr=$curr_to_year."-".($curr_to_year+1);
    //                     }
    //                     else{
    //                         $curr_to_fi_yr=($curr_to_year-1)."-".$curr_to_year;
    //                     }

    //                     //get fi yr id of to date
    //                     $data['upto_fi'] = $this->model_fy_mstr->getFiidByfyyr($to_fi_yr);
    //                     $to_fi_yr_id=$data['upto_fi']['id'];

    //                     //get fi yr id of current date
    //                     $data['curr_upto_fi'] = $this->model_fy_mstr->getFiidByfyyr($curr_to_fi_yr);
    //                     $curr_to_fi_yr_id=$data['curr_upto_fi']['id'];
    //                     if($from_fi_yr_id<17)
    //                     {
    //                         //first
    //                         if($inputs['date_upto'][$im]==""){
    //                             $fy_input1=[
    //                                 'fromId'=>$from_fi_yr_id,
    //                                 'toId'=>16,
    //                             ];
    //                             $fi_to_qtr1=4;
    //                         }
    //                         else{
    //                             $fy_input1=[
    //                                 'fromId'=>$from_fi_yr_id,
    //                                 'toId'=>16,
    //                             ];
    //                             $fi_to_qtr1=4;
    //                         }
    //                         //second
    //                         if($inputs['date_upto'][$im]==""){
    //                             $fy_input2=[
    //                                 'fromId'=>17,
    //                                 'toId'=>$curr_to_fi_yr_id,
    //                             ];
    //                             $fi_to_qtr2=4;
    //                         }
    //                         else{
    //                             $fy_input2=[
    //                                 'fromId'=>17,
    //                                 'toId'=>$to_fi_yr_id,
    //                             ];
    //                             $fi_to_qtr2=$to_qtr;
    //                         }
    //                     }
    //                     else{
    //                         if($inputs['date_upto'][$im]==""){
    //                             $fy_input=[
    //                                 'fromId'=>$from_fi_yr_id,
    //                                 'toId'=>$curr_to_fi_yr_id,
    //                             ];
    //                             $fi_to_qtr=4;
    //                         }
    //                         else{
    //                             $fy_input=[
    //                                 'fromId'=>$from_fi_yr_id,
    //                                 'toId'=>$to_fi_yr_id,
    //                             ];
    //                             $fi_to_qtr=$to_qtr;
    //                         }
    //                     }

    //                     //get all quarters with year
    //                 $FY = $this->model_fy_mstr->getFyByIdRangeAsc($fy_input);
    //                 $FY1 = $this->model_fy_mstr->getFyByIdRangeAsc($fy_input1);
    //                 $FY2 = $this->model_fy_mstr->getFyByIdRangeAsc($fy_input2);

    //                 /**************/

    //                 if($from_fi_yr_id<17)
    //                 {
    //                     //first tax before 2016-17
    //                     $tax_input1 = [
    //                         'prop_dtl_id'=>$prop_dtl_last_id,
    //                         'fy_mstr_id'=>$from_fi_yr_id,
    //                         'qtr'=>$from_qtr,
    //                         'arv'=>$inputs['arv'][$im],
    //                         'holding_tax'=>$holding_tax,
    //                         'water_tax'=>$water_tax,
    //                         'education_cess'=>$education_cess,
    //                         'health_cess'=>$health_cess,
    //                         'latrine_tax'=>$latrine_tax,
    //                         'additional_tax'=>$additional_tax,
    //                         'created_on'=>date('Y-m-d H:i:s'),
    //                         'status'=>1
    //                     ];
    //                     $first_prop_tax_last_id=$this->model_prop_tax->insertData($tax_input1);
    //                     //$first_prop_tax_last_id='127';


    //                     //insert into prop demand according to first tax
    //                     foreach($FY1 as $key=>$value){
    //                       $first_to_fy='2015-2016';

    //                     if(($value['fy']==$from_fi_yr) || ($value['fy']==$first_to_fy))
    //                     {
    //                         if($value['fy']==$from_fi_yr)
    //                         {
    //                             for($iq=$from_qtr; $iq<=4; $iq++)
    //                             {
    //                                 //insert code
    //                                 $d1_input=[
    //                                     'prop_dtl_id'=>$prop_dtl_last_id,
	// 					                'prop_tax_id'=>$first_prop_tax_last_id,
    //                                     'fy_mstr_id'=>$value['id'],
    //                                     'ward_mstr_id'=>$inputs['ward_mstr_id'],
	// 					                'qtr'=>$iq,
    //                                     'amount'=>$total_amount,
    //                                     'balance'=>$total_amount,
    //                                     'fine_tax'=>0,
    //                                     'paid_status'=>0,
    //                                     'created_on'=>date('Y-m-d H:i:s'),
	// 					                'status'=>1
    //                                 ];
    //                                 //print_r($d1_input);
    //                                 //echo $iq;
    //                                 $this->model_prop_demand->insertData($d1_input);
    //                             }
    //                         }
    //                         if($value['fy']==$first_to_fy && $from_fi_yr!=$first_to_fy)
    //                         {

    //                             for($iqq=1; $iqq<=$fi_to_qtr1; $iqq++)
    //                             {
    //                                 //insert code
    //                                 $d2_input=[
    //                                     'prop_dtl_id'=>$prop_dtl_last_id,
	// 					                'prop_tax_id'=>$first_prop_tax_last_id,
    //                                     'fy_mstr_id'=>$value['id'],
    //                                     'ward_mstr_id'=>$inputs['ward_mstr_id'],
	// 					                'qtr'=>$iqq,
    //                                     'amount'=>$total_amount,
    //                                     'balance'=>$total_amount,
    //                                     'fine_tax'=>0,
    //                                     'paid_status'=>0,
    //                                     'created_on'=>date('Y-m-d H:i:s'),
	// 					                'status'=>1
    //                                 ];
    //                                 //print_r($d2_input);
    //                                //echo $iqq;
    //                                 $this->model_prop_demand->insertData($d2_input);
    //                             }
    //                         }
    //                     }
    //                     else{
    //                         for($iqqq=1; $iqqq<=4; $iqqq++)
    //                         {
    //                             //insert code
    //                             $d3_input=[
    //                                     'prop_dtl_id'=>$prop_dtl_last_id,
	// 					                'prop_tax_id'=>$first_prop_tax_last_id,
    //                                     'fy_mstr_id'=>$value['id'],
    //                                     'ward_mstr_id'=>$inputs['ward_mstr_id'],
	// 					                'qtr'=>$iqqq,
    //                                     'amount'=>$total_amount,
    //                                     'balance'=>$total_amount,
    //                                     'fine_tax'=>0,
    //                                     'paid_status'=>0,
    //                                     'created_on'=>date('Y-m-d H:i:s'),
	// 					                'status'=>1
    //                                 ];
    //                             //echo $iqqq;
    //                             $this->model_prop_demand->insertData($d3_input);
    //                         }
    //                     }
    //                 }


    //                     //second tax 
    //                     $tax_input1 = [
    //                         'prop_dtl_id'=>$prop_dtl_last_id,
    //                         'fy_mstr_id'=>17,
    //                         'qtr'=>1,
    //                         'arv'=>$inputs['arv'][$im],
    //                         'holding_tax'=>$new_holding_tax,
    //                         'water_tax'=>0,
    //                         'education_cess'=>0,
    //                         'health_cess'=>0,
    //                         'latrine_tax'=>0,
    //                         'additional_tax'=>0,
    //                         'created_on'=>date('Y-m-d H:i:s'),
    //                         'status'=>1
    //                     ];
    //                     $second_prop_tax_last_id=$this->model_prop_tax->insertData($tax_input1);
    //                     //$second_prop_tax_last_id='128';

    //                     //insert into prop demand according to second tax

    //                     foreach($FY2 as $key=>$value){
    //                     $first_from_fy='2016-2017';
    //                     if(($value['fy']==$first_from_fy) || ($value['fy']==$to_fi_yr))
    //                     {
    //                         if($value['fy']==$first_from_fy)
    //                         {
    //                             for($iq=1; $iq<=4; $iq++)
    //                             {
    //                                 //insert code
    //                                 $d1_input=[
    //                                     'prop_dtl_id'=>$prop_dtl_last_id,
	// 					                'prop_tax_id'=>$second_prop_tax_last_id,
    //                                     'fy_mstr_id'=>$value['id'],
    //                                     'ward_mstr_id'=>$inputs['ward_mstr_id'],
	// 					                'qtr'=>$iq,
    //                                     'amount'=>$prop_total_amount,
    //                                     'balance'=>$prop_total_amount,
    //                                     'fine_tax'=>0,
    //                                     'paid_status'=>0,
    //                                     'created_on'=>date('Y-m-d H:i:s'),
	// 					                'status'=>1
    //                                 ];

    //                                 $this->model_prop_demand->insertData($d1_input);
    //                             }
    //                         }
    //                         if($value['fy']==$to_fi_yr)
    //                         {
    //                             for($iqq=1; $iqq<=$fi_to_qtr2; $iqq++)
    //                             {
    //                                 //insert code
    //                                 $d2_input=[
    //                                     'prop_dtl_id'=>$prop_dtl_last_id,
	// 					                'prop_tax_id'=>$second_prop_tax_last_id,
    //                                     'fy_mstr_id'=>$value['id'],
    //                                     'ward_mstr_id'=>$inputs['ward_mstr_id'],
	// 					                'qtr'=>$iqq,
    //                                     'amount'=>$prop_total_amount,
    //                                     'balance'=>$prop_total_amount,
    //                                     'fine_tax'=>0,
    //                                     'paid_status'=>0,
    //                                     'created_on'=>date('Y-m-d H:i:s'),
	// 					                'status'=>1
    //                                 ];
    //                                 //echo $iqq;

    //                                 $this->model_prop_demand->insertData($d2_input);
    //                             }
    //                         }
    //                     }
    //                     else{
    //                         for($iqqq=1; $iqqq<=4; $iqqq++)
    //                         {
    //                             //insert code
    //                             $d3_input=[
    //                                     'prop_dtl_id'=>$prop_dtl_last_id,
	// 					                'prop_tax_id'=>$second_prop_tax_last_id,
    //                                     'fy_mstr_id'=>$value['id'],
    //                                     'ward_mstr_id'=>$inputs['ward_mstr_id'],
	// 					                'qtr'=>$iqqq,
    //                                     'amount'=>$prop_total_amount,
    //                                     'balance'=>$prop_total_amount,
    //                                     'fine_tax'=>0,
    //                                     'paid_status'=>0,
    //                                     'created_on'=>date('Y-m-d H:i:s'),
	// 					                'status'=>1
    //                                 ];
    //                             //echo $iqqq;
    //                             $this->model_prop_demand->insertData($d3_input);
    //                         }
    //                     }
    //                 }


    //                 }
    //                 else
    //                 {
    //                     $tax_input = [
    //                         'prop_dtl_id'=>$prop_dtl_last_id,
    //                         'fy_mstr_id'=>$from_fi_yr_id,
    //                         'qtr'=>$from_qtr,
    //                         'arv'=>$inputs['arv'][$im],
    //                         'holding_tax'=>$new_holding_tax,
    //                         'water_tax'=>0,
    //                         'education_cess'=>0,
    //                         'health_cess'=>0,
    //                         'latrine_tax'=>0,
    //                         'additional_tax'=>0,
    //                         'created_on'=>date('Y-m-d H:i:s'),
    //                         'status'=>1
    //                     ];
    //                     $prop_tax_last_id=$this->model_prop_tax->insertData($tax_input);


    //                     //insert into prop demand
    //                     foreach($FY as $key=>$value){
    //                     if(($value['fy']==$from_fi_yr) || ($value['fy']==$to_fi_yr))
    //                     {
    //                         if($value['fy']==$from_fi_yr)
    //                         {
    //                             for($iq=$from_qtr; $iq<=4; $iq++)
    //                             {
    //                                 //insert code
    //                                 $d1_input=[
    //                                     'prop_dtl_id'=>$prop_dtl_last_id,
	// 					                'prop_tax_id'=>$prop_tax_last_id,
    //                                     'fy_mstr_id'=>$value['id'],
    //                                     'ward_mstr_id'=>$inputs['ward_mstr_id'],
	// 					                'qtr'=>$iq,
    //                                     'amount'=>$prop_total_amount,
    //                                     'balance'=>$prop_total_amount,
    //                                     'fine_tax'=>0,
    //                                     'paid_status'=>0,
    //                                     'created_on'=>date('Y-m-d H:i:s'),
	// 					                'status'=>1
    //                                 ];
    //                                 $this->model_prop_demand->insertData($d1_input);
    //                             }
    //                         }
    //                         if($value['fy']==$to_fi_yr)
    //                         {
    //                             for($iqq=1; $iqq<=$fi_to_qtr; $iqq++)
    //                             {
    //                                 //insert code
    //                                 $d2_input=[
    //                                     'prop_dtl_id'=>$prop_dtl_last_id,
	// 					                'prop_tax_id'=>$prop_tax_last_id,
    //                                     'fy_mstr_id'=>$value['id'],
    //                                     'ward_mstr_id'=>$inputs['ward_mstr_id'],
	// 					                'qtr'=>$iqq,
    //                                     'amount'=>$prop_total_amount,
    //                                     'balance'=>$prop_total_amount,
    //                                     'fine_tax'=>0,
    //                                     'paid_status'=>0,
    //                                     'created_on'=>date('Y-m-d H:i:s'),
	// 					                'status'=>1
    //                                 ];
    //                                 $this->model_prop_demand->insertData($d2_input);
    //                             }
    //                         }
    //                     }
    //                     else{
    //                         for($iqqq=1; $iqqq<=4; $iqqq++)
    //                         {
    //                             //insert code
    //                             $d3_input=[
    //                                     'prop_dtl_id'=>$prop_dtl_last_id,
	// 					                'prop_tax_id'=>$prop_tax_last_id,
    //                                     'fy_mstr_id'=>$value['id'],
    //                                     'ward_mstr_id'=>$inputs['ward_mstr_id'],
	// 					                'qtr'=>$iqqq,
    //                                     'amount'=>$prop_total_amount,
    //                                     'balance'=>$prop_total_amount,
    //                                     'fine_tax'=>0,
    //                                     'paid_status'=>0,
    //                                     'created_on'=>date('Y-m-d H:i:s'),
	// 					                'status'=>1
    //                                 ];
    //                             $this->model_prop_demand->insertData($d3_input);
    //                         }
    //                     }
    //                 }

    //                 }

    //         }

    //                 return $this->response->redirect(base_url('LegacyEntry/form_view/'.md5($prop_dtl_last_id).''));
    //     }
    //             }
    //         else{
    //             $data = $inputs;
	// 		    $data['validation'] = $errMsg;
    //             $data['wardList'] = $wardList;
    //             $data['ownershipTypeList'] = $ownershipTypeList;
    //             $data['propTypeList'] = $propTypeList;
    //             $data['roadTypeList'] = $roadTypeList;
    //             $data['ulb_address'] = $ulb_address;
    //             return view('property/legacy_entry', $data);
    //         }
    //     }
    //     else{
    //         return view('property/legacy_entry', $data);
    //     }
    //    }

    public function form()
	{
        $data =(array)null;
        helper(['form']);
        date_default_timezone_set('Asia/Kolkata');
        $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
		$emp_details_id = $_SESSION['emp_details']['id'];
        $ulb_address = $this->model_ulb_mstr->getAddressById(['ulb_mstr_id'=>$ulb_mstr_id]);
        $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        $ownershipTypeList = $this->model_ownership_type_mstr->getOwnershipTypeList();
        $propTypeList = $this->model_prop_type_mstr->getPropTypeList();
		$roadTypeList = $this->model_road_type_mstr->getRoadTypeList();
        $data['wardList'] = $wardList;
        $data['ownershipTypeList'] = $ownershipTypeList;
        $data['propTypeList'] = $propTypeList;
        $data['roadTypeList'] = $roadTypeList;
        $data['ulb_address'] = $ulb_address;
        if($this->request->getMethod()=='post')
		{
            $inputs = arrFilterSanitizeString($this->request->getVar());
            $errMsg = validateLegacyEntry($inputs);
            if (empty($errMsg))
			{
                $data = $inputs;
                $data['wardList'] = $wardList;
                $data['ownershipTypeList'] = $ownershipTypeList;
                $data['propTypeList'] = $propTypeList;
                $data['roadTypeList'] = $roadTypeList;
                $data['ulb_address'] = $ulb_address;
                if(isset($_POST['btn_submit']))
				{

                    $data_input = [
                        'ward_mstr_id'=>$inputs['ward_mstr_id'],
                        'holding_no'=>strtoupper($inputs['holding_no']), 
                        'ownership_type_mstr_id'=>$inputs['ownership_type_mstr_id'], 
				        'prop_type_mstr_id'=>$inputs['prop_type_mstr_id'], 
                        'road_type_mstr_id'=>$inputs['road_type_mstr_id'], 
                        'prop_address'=>strtoupper($inputs['prop_address']), 
				        'prop_city'=>$inputs['prop_city'], 
				        'prop_dist'=>$inputs['prop_dist'], 
				        'prop_pin_code'=>$inputs['prop_pin_code'],
                         'emp_details_id' => $emp_details_id,
                         'created_on' =>date('Y-m-d H:i:s'),
                        'entry_type' =>'Legacy'
                    ];
                    $prop_dtl_last_id = $this->model_prop_dtl->insertData($data_input);
                    $sql_json = "INSERT INTO tbl_prop_json_dtl (prop_dtl_id, prop_dtl, status) VALUES (".$prop_dtl_last_id.", get_json_prop_dtl(".$prop_dtl_last_id."), 1);";
                    $this->db->query($sql_json);
                   // die();
                    //$prop_dtl_last_id='55';
                    for ( $i=0; $i < sizeof($inputs['owner_name']); $i++ ) 
					{
						$input = [
							'prop_dtl_id'=>$prop_dtl_last_id,
							'owner_name'=>$inputs['owner_name'][$i],
							'guardian_name'=>$inputs['guardian_name'][$i],
							'relation_type'=>$inputs['relation_type'][$i],
							'mobile_no'=>$inputs['mobile_no'][$i],
							'aadhar_no'=>($inputs['aadhar_no'][$i]!="")?$inputs['aadhar_no'][$i]:null,
							'pan_no'=>$inputs['pan_no'][$i],
							'email'=>$inputs['email'][$i],
							'emp_details_id'=>$emp_details_id,
							'created_on'=>date('Y-m-d H:i:s'),
							'status'=>1
						];
						$this->model_prop_owner_detail->insertData($input);
					}
					$next_qtr = 0;
                    $prop_tax = [];
					for ( $im=0; $im < sizeof($inputs['date_from']); $im++ ) 
					{
						//echo $inputs['date_from'][$im];
						//get fi yr qtr
						$fromMM = date("m", strtotime($inputs['date_from'][$im]));
						if($fromMM>=1 && 3>=$fromMM){ // X1
							$from_qtr = 4;
						}else if($fromMM>=4 && 6>=$fromMM){ // X4
							$from_qtr = 1;
						}else if($fromMM>=7 && 9>=$fromMM){ // X3
							$from_qtr = 2;
						}else if($fromMM>=10 && 12>=$fromMM){ // X2
							$from_qtr = 3;
						}
						//get financial year
						$from_month=date('n',strtotime($inputs['date_from'][$im].'-01'));
						$from_year=date('Y',strtotime($inputs['date_from'][$im].'-01'));
						if($from_month>3)
						{
							$from_fi_yr=$from_year."-".($from_year+1);
						}
						else{
							$from_fi_yr=($from_year-1)."-".$from_year;
						}
						
						//get fi yr id
						$data['from_fi'] = $this->model_fy_mstr->getFiidByfyyr($from_fi_yr);
						$from_fi_yr_id=$data['from_fi']['id'];
                        $from_fy_year = $data['from_fi']['fy'];
                        
                        $water_tax_percentage = $inputs['water_tax_per']/100;

						$new_holding_tax=round(((($inputs['arv'][$im])*0.02)/4), 2);
						$holding_tax=round(((($inputs['arv'][$im])*0.125)/4), 2);
						$water_tax=round(((($inputs['arv'][$im])*$water_tax_percentage)/4), 2);
						$education_cess=round(((($inputs['arv'][$im])*0.05)/4), 2);
						$health_cess=round(((($inputs['arv'][$im])*0.0625)/4), 2);
						$latrine_tax=round(((($inputs['arv'][$im])*0.075)/4), 2);
						$additional_tax=0;
						$prop_total_amount=$new_holding_tax;
						$total_amount=$holding_tax + $water_tax + $education_cess + $health_cess +$latrine_tax + $additional_tax;

						/******calculation********/
						//get fi yr qtr of to date
						$toMM = date("m", strtotime($inputs['date_upto'][$im]));
						if($toMM>=1 && 3>=$toMM){ // X1
							$to_qtr = 4;
						}else if($toMM>=4 && 6>=$toMM){ // X4
							$to_qtr = 1;
						}else if($toMM>=7 && 9>=$toMM){ // X3
							$to_qtr = 2;
						}else if($toMM>=10 && 12>=$toMM){ // X2
							$to_qtr = 3;
						}

						//get financial year of to date
						$to_month=date('n',strtotime($inputs['date_upto'][$im].'-01'));
						$to_year=date('Y',strtotime($inputs['date_upto'][$im].'-01'));
						if($to_month>3)
						{
							$to_fi_yr=$to_year."-".($to_year+1);
						}
						else{
							$to_fi_yr=($to_year-1)."-".$to_year;
						}

						//get financial year of current year
						$curr_to_month=date('n');
						$curr_to_year=date('Y');
						if($curr_to_month>3)
						{
							$curr_to_fi_yr=$curr_to_year."-".($curr_to_year+1);
						}
						else{
							$curr_to_fi_yr=($curr_to_year-1)."-".$curr_to_year;
						}

						//get fi yr id of to date
						$data['upto_fi'] = $this->model_fy_mstr->getFiidByfyyr($to_fi_yr);
						$to_fi_yr_id=$data['upto_fi']['id'];

						//get fi yr id of current date
						$data['curr_upto_fi'] = $this->model_fy_mstr->getFiidByfyyr($curr_to_fi_yr);
						$curr_to_fi_yr_id=$data['curr_upto_fi']['id'];
							
						if($inputs['date_upto'][$im]==""){
                            $fy_input=[
                                'fromId'=>$from_fi_yr_id,
                                'toId'=>$curr_to_fi_yr_id,
                            ];
                            $fi_to_qtr=4;
                        }
                        else{
                            $fy_input=[
                                'fromId'=>$from_fi_yr_id,
                                'toId'=>$to_fi_yr_id,
                            ];
                            $fi_to_qtr=$to_qtr;
                        }

						//get all quarters with year
						$FY = $this->model_fy_mstr->getFyByIdRangeAsc($fy_input);
						//$FY1 = $this->model_fy_mstr->getFyByIdRangeAsc($fy_input1);
						//$FY2 = $this->model_fy_mstr->getFyByIdRangeAsc($fy_input2);


                        //first tax before 2016-17
                        $tax_input1 = [
                            'prop_dtl_id'=>$prop_dtl_last_id,
                            'fy_mstr_id'=>$from_fi_yr_id,
                            'qtr'=>$from_qtr,
                            'arv'=>$inputs['arv'][$im],
                            'holding_tax'=>$holding_tax,
                            'water_tax'=>$water_tax,
                            'education_cess'=>$education_cess,
                            'health_cess'=>$health_cess,
                            'latrine_tax'=>$latrine_tax,
                            'additional_tax'=>$additional_tax,
                            'created_on'=>date('Y-m-d H:i:s'),
                            'status'=>1,
                            'quarterly_tax' => $total_amount,
                            'fyear' => $from_fy_year
                            
                        ];
                        $first_prop_tax_last_id=$this->model_prop_tax->insertData($tax_input1);
                        //$first_prop_tax_last_id='127';


                        foreach($FY as $key=>$value)
                        {
                            if($from_fi_yr_id == $value['id'])
                            {
                                $from_qtr = $from_qtr;
                            }else{
                                $from_qtr = 1;
                            }

                            for($iq=$from_qtr; $iq<=4; $iq++)
                            {
                                //insert code
                                $d1_input=[
                                    'prop_dtl_id'=>$prop_dtl_last_id,
                                    'prop_tax_id'=>$first_prop_tax_last_id,
                                    'fy_mstr_id'=>$value['id'],
                                    'fyear' => $value['fy'],
                                    'ward_mstr_id'=>$inputs['ward_mstr_id'],
                                    'qtr'=>$iq,
                                    'amount'=>$total_amount,
                                    'balance'=>$total_amount,
                                    'fine_tax'=>0,
                                    'paid_status'=>0,
                                    'created_on'=>date('Y-m-d H:i:s'),
                                    'status'=>1,
                                    'due_date'=>$this->makeDueDateByFyearQtr($value['fy'], $iq),
                                    'demand_amount' => $total_amount,
                                    'additional_amount' => '0.00'
                                ];
                                //$prop_tax[] =  $d1_input;
                                //print_r($d1_input);
                                //echo $iq;
                                $this->model_prop_demand->insertData($d1_input);
                            }
                        }
                        

					}
                    //print_var($prop_tax);
                    //exit();
					return $this->response->redirect(base_url('LegacyEntry/form_view/'.md5($prop_dtl_last_id).''));
				}
            }
            else{
                $data = $inputs;
			    $data['validation'] = $errMsg;
                $data['wardList'] = $wardList;
                $data['ownershipTypeList'] = $ownershipTypeList;
                $data['propTypeList'] = $propTypeList;
                $data['roadTypeList'] = $roadTypeList;
                $data['ulb_address'] = $ulb_address;
                return view('property/legacy_entry', $data);
            }
        }
        else{
            return view('property/legacy_entry', $data);
        }
    }

    public function form_view($id=null)
	{
        $data =(array)null;
        $data['id']=$id;
        $Session = Session();
        date_default_timezone_set('Asia/Kolkata');
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
        $data['basic_details'] = $this->model_prop_dtl->prop_basic_details($data);
        $data['ward_mstr_id']=$data['basic_details']['old_ward_mstr_id'];
        $data['old_ward'] = $this->model_ward_mstr->getWardNoById($data);
        $data['owner_details'] = $this->model_prop_owner_detail->propownerdetails($data['basic_details']['prop_dtl_id']);
        $data['tax_list'] = $this->model_prop_tax->tax_list($data['basic_details']['prop_dtl_id']);
        if ($demand_detail = $this->model_prop_demand->all_demand_detail($data) ) {
			$data['demand_detail'] = $demand_detail;
		}
        $data['paid_demand'] = $this->model_prop_demand->getpaidid_by_propdtlid($data['basic_details']['prop_dtl_id']);
        $data['demand_upd_exist'] = $this->model_legacy_demand_update->getid_by_propdtlid($data['basic_details']['prop_dtl_id']);
        return view('property/legacy_view', $data);
    }

    public function demand_update($id=null)
	{
        $data =(array)null;
        $data['id']=$id;
        $Session = Session();
        date_default_timezone_set('Asia/Kolkata');
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
        $data['basic_details'] = $this->model_prop_dtl->prop_basic_details($data);
        $data['ward_mstr_id']=$data['basic_details']['old_ward_mstr_id'];
        $data['old_ward'] = $this->model_ward_mstr->getWardNoById($data);
        $data['owner_details'] = $this->model_prop_owner_detail->propownerdetails($data['basic_details']['prop_dtl_id']);
        $data['tax_list'] = $this->model_prop_tax->tax_list($data['basic_details']['prop_dtl_id']);
        //print_r($data['tax_list']);

        if ($demand_detail = $this->model_prop_demand->all_demand_detail($data) ) {
			$data['demand_detail'] = $demand_detail;
		}
        if($this->request->getMethod()=='post'){
            if(isset($_POST['btn_submit']))
            {
                $inputs = arrFilterSanitizeString($this->request->getVar());
                foreach($data['tax_list'] as $key => $value){
                    if($value['fy_mstr_id']<17)
                    {
                        $input_data = [
                            'prop_tax_id' => $value['id'],
                            'previous_arv' => $value['arv'],
                            'new_arv' => $inputs['arv'],
                            'remarks' => $inputs['remarks'],
                            'prop_dtl_id' => $data['basic_details']['prop_dtl_id'],
                            'created_by_emp_details_id' => $login_emp_details_id,
                            'created_on' =>date('Y-m-d H:i:s')
                        ];
                        $holding_tax=round(((($inputs['arv'])*0.125)/4), 2);
                        $water_tax=round(((($inputs['arv'])*0.075)/4), 2);
                        $education_cess=round(((($inputs['arv'])*0.05)/4), 2);
                        $health_cess=round(((($inputs['arv'])*0.0625)/4), 2);
                        $latrine_tax=round(((($inputs['arv'])*0.075)/4), 2);
                        $additional_tax=0;
                        $total_amount=$holding_tax + $water_tax + $education_cess + $health_cess +$latrine_tax + $additional_tax;
                        if($demand_upd_detail = $this->model_legacy_demand_update->insertData($input_data) ){
                            $input_tax_data = [
                                'prop_dtl_id' => $data['basic_details']['prop_dtl_id'],
                                'arv' => $inputs['arv'],
                                'prop_tax_id' => $value['id'],
                                'holding_tax' => $holding_tax,
                                'water_tax' => $water_tax,
                                'education_cess' => $education_cess,
                                'health_cess' => $health_cess,
                                'latrine_tax' => $latrine_tax                         
                            ];
                            $this->model_prop_tax->updatetaxByproptaxdtlId($input_tax_data);
                            $input_demand_data = [
                                'prop_dtl_id' => $data['basic_details']['prop_dtl_id'],
                                'prop_tax_id' => $value['id'],
                                'amount' => $total_amount,
                                'balance' => $total_amount
                            ];
                            $this->model_prop_demand->updatedemandByproptaxdtlId($input_demand_data);

                        }
                    }
                    else
                    {
                        $input_data1 = [
                            'prop_tax_id' => $value['id'],
                            'previous_arv' => $value['arv'],
                            'new_arv' => $inputs['arv'],
                            'remarks' => $inputs['remarks'],
                            'prop_dtl_id' => $data['basic_details']['prop_dtl_id'],
                            'created_by_emp_details_id' => $login_emp_details_id,
                            'created_on' =>date('Y-m-d H:i:s')
                        ];
                        $new_holding_tax=round(((($inputs['arv'])*0.02)/4), 2);

                        $new_total_amount=$new_holding_tax;
                        if($demand_upd_detail = $this->model_legacy_demand_update->insertData($input_data1) ){
                            $input_tax_data1 = [
                                'prop_dtl_id' => $data['basic_details']['prop_dtl_id'],
                                'prop_tax_id' => $value['id'],
                                'arv' => $inputs['arv'],
                                'holding_tax' => $new_holding_tax,
                                'water_tax' => 0,
                                'education_cess' => 0,
                                'health_cess' => 0,
                                'latrine_tax' => 0
                            ];
                            $this->model_prop_tax->updatetaxByproptaxdtlId($input_tax_data1);
                            $input_demand_data = [
                                'prop_dtl_id' => $data['basic_details']['prop_dtl_id'],
                                'prop_tax_id' => $value['id'],
                                'amount' => $new_total_amount,
                                'balance' => $new_total_amount
                            ];
                            $this->model_prop_demand->updatedemandByproptaxdtlId($input_demand_data);
                        }
                    }
                }
                return $this->response->redirect(base_url('LegacyEntry/form_view/'.$id.''));
            }
        }
        else{
            return view('property/legacy_demand_update', $data);
        }

    }

    public function getTax()
    {
        if($this->request->getMethod()=='post'){
            $inputs = arrFilterSanitizeString($this->request->getVar());
            $from_month=date('n',strtotime($inputs['date_from'].'-01'));
            $from_year=date('Y',strtotime($inputs['date_from'].'-01'));

            $fromMM = date("m", strtotime($inputs['date_from']));
            if($fromMM>=1 && 3>=$fromMM){ // X1
                $from_qtr = 4;
            }else if($fromMM>=4 && 6>=$fromMM){ // X4
                $from_qtr = 1;
            }else if($fromMM>=7 && 9>=$fromMM){ // X3
                $from_qtr = 2;
            }else if($fromMM>=10 && 12>=$fromMM){ // X2
                $from_qtr = 3;
            }

            if($from_month>3)
            {
                $from_fi_yr=$from_year."-".($from_year+1);
            }
            else{
                $from_fi_yr=($from_year-1)."-".$from_year;
            }
            
            //get fi yr id
            $data['from_fi'] = $this->model_fy_mstr->getFiidByfyyr($from_fi_yr);
            $from_fi_yr_id=$data['from_fi']['id'];
            $from_fy_year = $data['from_fi']['fy'];
            
            $water_tax_percentage = $inputs['water_tax_per']/100;

            $new_holding_tax=round(((($inputs['arv'])*0.02)/4), 2);
            $holding_tax=round(((($inputs['arv'])*0.125)/4), 2);
            $water_tax=round(((($inputs['arv'])*$water_tax_percentage)/4), 2);
            $education_cess=round(((($inputs['arv'])*0.05)/4), 2);
            $health_cess=round(((($inputs['arv'])*0.0625)/4), 2);
            $latrine_tax=round(((($inputs['arv'])*0.075)/4), 2);
            $additional_tax=0;
            $prop_total_amount=$new_holding_tax;
            $total_amount=$holding_tax + $water_tax + $education_cess + $health_cess +$latrine_tax + $additional_tax;

            $fyear = $from_year."-".($from_year+1);

            $data_record = "<tr><th>".$inputs['arv']."</th><th>Quater : ".$from_qtr." / Year : ".$fyear."</th><th>".$holding_tax."</th><th>$water_tax</th><th>$latrine_tax</th><th>$education_cess</th><th>$health_cess</th><th>$total_amount</th><tr>";
            $response = ['status'=> true, 'data'=> $data_record];

            echo json_encode($response);
        }
    }

    }