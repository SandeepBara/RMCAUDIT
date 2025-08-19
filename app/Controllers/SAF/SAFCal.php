<?php
namespace App\Controllers\SAF;
use CodeIgniter\Controller;

use App\Controllers\BO_SAF;
use App\Models\model_ulb_mstr;
use App\Models\model_fy_mstr;
use App\Models\model_ward_mstr;
use App\Models\model_saf_dtl;
use App\Models\model_view_saf_dtl;
use App\Models\model_prop_dtl;
use App\Models\model_saf_owner_detail;
use App\Models\model_view_saf_floor_details;
use App\Models\model_saf_floor_arv_dtl;
use App\Models\model_saf_tax;
use App\Models\model_saf_demand;
use App\Models\model_saf_doc_dtl;
use App\Models\model_saf_distributed_dtl;
use App\Models\model_transaction;
use App\Models\model_level_pending_dtl;
use App\Models\model_field_verification_dtl;
use App\Models\model_field_verification_floor_details;
use App\Models\model_saf_geotag_upload_dtl;
use App\Models\model_saf_memo_dtl;
use App\Models\model_view_saf_receive_list;
use App\Models\model_prop_owner_detail;
use App\Models\model_datatable;
use App\Models\model_occupancy_type_mstr;
use App\Models\model_usage_type_dtl;
use App\Models\model_arr_building_mstr;
use App\Models\model_arr_old_building_mstr;
use App\Models\model_arr_vacant_mstr; 

class SAFCal extends Controller
{
	protected $db;
	protected $dbSystem;
	protected $model_ulb_mstr;
    protected $model_ward_mstr;
	protected $model_fy_mstr;
    protected $model_saf_dtl;
    protected $model_view_saf_dtl;
    protected $model_prop_dtl;
	protected $model_saf_owner_detail;
	protected $model_view_saf_floor_details;
	protected $model_saf_floor_arv_dtl;
	protected $model_saf_tax;
    protected $model_saf_demand;
    protected $model_saf_doc_dtl;
	protected $model_saf_distributed_dtl;
    protected $model_transaction;
    protected $model_level_pending_dtl;
    protected $model_field_verification_dtl;
    protected $model_field_verification_floor_details;
    protected $model_saf_geotag_upload_dtl;
    protected $model_saf_memo_dtl;
	protected $model_view_saf_receive_list;
    protected $BO_SAF_Controller;
    protected $model_prop_owner_detail;
	protected $model_datatable;
    protected $model_occupancy_type_mstr;
    protected $model_usage_type_dtl;
    protected $model_arr_building_mstr;
    protected $model_arr_old_building_mstr;
    protected $model_arr_vacant_mstr;

    public function __construct()
    {
    	helper(['url', 'db_helper', 'validation_helper', 'validate_saf_helper']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name);
        }
        if ($db_name = dbSystem()) {
            $this->dbSystem = db_connect($db_name); 
        }

        
		$this->model_fy_mstr = new model_fy_mstr($this->dbSystem);
        $this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_prop_dtl = new model_prop_dtl($this->db);
        $this->model_saf_dtl = new model_saf_dtl($this->db);
        $this->model_view_saf_dtl = new model_view_saf_dtl($this->db);
        $this->model_saf_owner_detail = new model_saf_owner_detail($this->db);
		$this->model_view_saf_floor_details = new model_view_saf_floor_details($this->db);
		$this->model_saf_floor_arv_dtl = new model_saf_floor_arv_dtl($this->db);
        $this->model_saf_tax = new model_saf_tax($this->db);
        $this->model_saf_demand = new model_saf_demand($this->db);
        $this->model_saf_doc_dtl = new model_saf_doc_dtl($this->db);
        $this->model_transaction = new model_transaction($this->db);
        $this->model_level_pending_dtl = new model_level_pending_dtl($this->db);
        $this->model_field_verification_dtl = new model_field_verification_dtl($this->db);
        $this->model_field_verification_floor_details = new model_field_verification_floor_details($this->db);
        $this->model_saf_geotag_upload_dtl = new model_saf_geotag_upload_dtl($this->db);
        $this->model_saf_memo_dtl = new model_saf_memo_dtl($this->db);
		$this->model_view_saf_receive_list = new model_view_saf_receive_list($this->db);
        $this->BO_SAF_Controller = new BO_SAF($this->db);
        $this->model_prop_owner_detail = new model_prop_owner_detail($this->db);
		$this->model_datatable = new model_datatable($this->db);
        $this->model_occupancy_type_mstr = new model_occupancy_type_mstr($this->db);
        $this->model_usage_type_dtl = new model_usage_type_dtl($this->db);
        $this->model_arr_building_mstr = new model_arr_building_mstr($this->db);
        $this->model_arr_old_building_mstr = new model_arr_old_building_mstr($this->db);
        $this->model_arr_vacant_mstr = new model_arr_vacant_mstr($this->db);
    }


    public function safCalTax($safDtl = null)
    {
        if(is_null($safDtl)) {

        } else {
            //$safDtl = $this->model_field_verification_dtl->getDtlBySafDtlIdMD5($saf_dtl_id);
            

            if ($safDtl['prop_type_mstr_id']==4) {
                
                $tempSafDtl = $this->model_saf_dtl->getSafDtlById(['saf_dtl_id'=>$safDtl['saf_dtl_id']]);
                $safDtl['land_occupation_date'] = $tempSafDtl['land_occupation_date'];

                $currentFY = getFY();
                $currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$currentFY])['id'];

                // date of effect
                $yrOfEffect_16_17_FY = getFY("2016-04-01");
                $yrOfEffect_16_17_FyID = $this->model_fy_mstr->getFyByFy(['fy'=>$yrOfEffect_16_17_FY])['id'];

                // vacant land details
                $vacantlandArea = ($safDtl['area_of_plot']*40.5);
                $mobileTowerArea =  $hoardingBoardArea = 0;

                $isMobileTower = $isHoldingBoard = false;
                $vacand_land_qtr = $mobile_tower_qtr = $hoarding_board_qtr = 0;
                // date of effect

                $FromFixEffectFyID =  $yrOfEffect_16_17_FyID;

                // acquisition fy
                $acquisitionFY = getFY($safDtl['land_occupation_date']);

                // acquisition fy
                $acquisitionFY = getFY($safDtl['land_occupation_date']);
                $acquisitionFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$acquisitionFY])['id'];
                if ( $yrOfEffect_16_17_FyID > $acquisitionFyID ) {
                    $acquisitionFyID = $yrOfEffect_16_17_FyID;
                    $safDtl['land_occupation_date'] = "2016-04-01";
                }
                if ( $yrOfEffect_16_17_FyID < $acquisitionFyID ) {
                    $FromFixEffectFyID = $acquisitionFyID;
                }

                $MM = date("m", strtotime($safDtl['land_occupation_date']));
                if($MM>=1 && 3>=$MM){ // X1
                    $temp_qtr = 4;
                }else if($MM>=4 && 6>=$MM){ // X4
                    $temp_qtr = 1;
                }else if($MM>=7 && 9>=$MM){ // X3
                    $temp_qtr = 2;
                }else if($MM>=10 && 12>=$MM){ // X2
                    $temp_qtr = 3;
                }

                $vacand_land_qtr = $temp_qtr;

                if($safDtl['is_mobile_tower']=='t'){

                    $mobileTowerFY = getFY($safDtl['tower_installation_date']);
                    $mobileTowerFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$mobileTowerFY])['id'];
                    if($FromFixEffectFyID>$mobileTowerFyID){
                        $mobileTowerFyID = $FromFixEffectFyID;
                        $safDtl['tower_installation_date'] = "2016-04-01";
                    }
                    $MM = date("m", strtotime($safDtl['tower_installation_date']));
                    if($MM>=1 && 3>=$MM){ // X1
                        $temp_qtr = 4;
                    }else if($MM>=4 && 6>=$MM){ // X4
                        $temp_qtr = 1;
                    }else if($MM>=7 && 9>=$MM){ // X3
                        $temp_qtr = 2;
                    }else if($MM>=10 && 12>=$MM){ // X2
                        $temp_qtr = 3;
                    }else{

                    }
                    $mobileTowerArea = $safDtl['tower_area']*0.092903;
                    $isMobileTower = true;
                    $mobile_tower_qtr = $temp_qtr;
                }
                if($safDtl['is_hoarding_board']=='t'){
                    $hoardinBoardFY = getFY($safDtl['hoarding_installation_date']);
                    $hoardinBoardFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$hoardinBoardFY])['id'];
                    if($yrOfEffect_16_17_FyID>$hoardinBoardFyID){
                        $hoardinBoardFyID = $FromFixEffectFyID;
                        $safDtl['hoarding_installation_date'] = "2016-04-01";
                    }
                    $MM = date("m", strtotime($safDtl['hoarding_installation_date']));
                    if($MM>=1 && 3>=$MM){ // X1
                        $temp_qtr = 4;
                    }else if($MM>=4 && 6>=$MM){ // X4
                        $temp_qtr = 1;
                    }else if($MM>=7 && 9>=$MM){ // X3
                        $temp_qtr = 2;
                    }else if($MM>=10 && 12>=$MM){ // X2
                        $temp_qtr = 3;
                    }else{

                    }
                    $hoardingBoardArea = $safDtl['hoarding_area']*0.092903;
                    $isHoldingBoard = true;
                    $hoarding_board_qtr = $temp_qtr;
                }

                $getFyList = $this->model_fy_mstr->getFyByIdRangeAsc(['fromId'=>$FromFixEffectFyID, 'toId'=>$currentFyID]);
                $safTaxDtl = [];
                $safTaxIncreament = 0;

                $mobileTowerOneTimeImpliment = false;
                $hoadingBoardOneTimeImpliment = false;
                foreach ($getFyList as $fyVal) {
                    $dateOfEffect = (explode("-", $fyVal['fy'])[1])."-04-01";

                    $vacand_land_qtr_temp = $mobile_tower_qtr_temp = $hoarding_board_qtr_temp = 0;
                    $isVacantLandTemp = $isMobileTowerTemp = $isHoldingBoardTemp = false;
                    $isMobileTowerIncreaseTemp = $isHoldingBoardIncreaseTemp = false;

                    $isExist = false;
                    $totalTax = 0;

                    $lastArvDtl = [];
                    $lastIncreament = -1;

                    // vacand land
                    if($fyVal['id']==$acquisitionFyID){
                        $isVacantLandTemp = true;
                        $vacand_land_qtr_temp = $vacand_land_qtr;
                    }
                    // mobile tower
                    if($isMobileTower==true){
                        if($fyVal['id']==$mobileTowerFyID){
                            $isMobileTowerTemp = true;
                            $mobile_tower_qtr_temp = $mobile_tower_qtr;
                        }
                        if($fyVal['id']>=$mobileTowerFyID){
                            $isMobileTowerIncreaseTemp = true;
                        }
                    }
                    // Hording Board
                    if($isHoldingBoard==true){
                        if($fyVal['id']==$hoardinBoardFyID){
                            $isHoldingBoardTemp = true;
                            $hoarding_board_qtr_temp = $hoarding_board_qtr;
                        }
                        if($fyVal['id']>$hoardinBoardFyID){
                            $isHoldingBoardIncreaseTemp = true;
                        }
                    }

                    if($isVacantLandTemp || $isMobileTowerTemp || $isHoldingBoardTemp){
                        $sendInput = ['road_type_mstr_id'=>$safDtl['road_type_mstr_id'], 'date_of_effect'=>$dateOfEffect];
                        $mrr = $this->model_arr_vacant_mstr->getMRRCalRate($sendInput)['rate'];
                        if(!$mrr){ $mrr = 0; }

                        $arrShort = array('vacand'=>$vacand_land_qtr_temp, 'mobile'=>$mobile_tower_qtr_temp, 'hording'=>$hoarding_board_qtr_temp);
                        
                        asort($arrShort);
                        
                        foreach($arrShort as $keyy=>$x_Qtr)
                        {
                            if($keyy=="vacand" && $x_Qtr!=0 && $x_Qtr!=null)
                            {
                                $isExist = true;
                                $calVacandLandArea = $vacantlandArea;
                                $vacandLandTax = 0;
                                if($isMobileTowerTemp==true && $x_Qtr==$mobile_tower_qtr_temp){
                                    $calVacandLandArea = $calVacandLandArea-$mobileTowerArea;
                                }
                                if($isHoldingBoardTemp==true && $x_Qtr==$hoarding_board_qtr_temp){
                                    $calVacandLandArea = $calVacandLandArea-$hoardingBoardArea;
                                }
                                $vacantLandTax = $calVacandLandArea*$mrr;
                                $totalTax += $vacantLandTax;

                                $vacantLandDtl = [
                                    'vacant_land_area_sqm'=>$vacantlandArea,
                                    'applied_rate'=>$mrr,
                                    'yearly_holding_tax'=>round(($vacantlandArea*$mrr), 2),
                                    'qtr_holding_tax'=>round((($vacantlandArea*$mrr)/4), 2),
                                    'vacant_land_area_sqft'=>($vacantlandArea*0.092903),
                                    'fy'=> $fyVal['fy'],
                                    'qtr'=> $x_Qtr,
                                ];

                                $lastIncreament++;
                                $lastArvDtl[$lastIncreament] = [
                                    'fyID'=> $fyVal['id'],
                                    'fy'=> $fyVal['fy'],
                                    'qtr'=> $x_Qtr,
                                    'arv'=>0,
                                    'holding_tax_yearly'=>$totalTax
                                ];                                
                            }

                            if($keyy=="mobile" && $x_Qtr!=0 && $x_Qtr!=null)
                            {
                                if (!$mobileTowerOneTimeImpliment){
                                    $mobileTowerOneTimeImpliment = true;
                                    $hordingBoardTax = 0;
                                    if ($isVacantLandTemp==false || $x_Qtr!=$vacand_land_qtr_temp) {
                                        $calVacandLandArea = $vacantlandArea;
                                        if($isHoldingBoardIncreaseTemp==true && $x_Qtr!=$mobile_tower_qtr_temp && $hoadingBoardOneTimeImpliment==true){
                                            $calVacandLandArea = $calVacandLandArea-$hoardingBoardArea;
                                            $hordingBoardTax = $hoardingBoardArea*$mrr*1.5;
                                        }
                                        $calVacandLandArea = $calVacandLandArea-$mobileTowerArea;
                                        $vacantLandTax = $calVacandLandArea*$mrr*1;
                                        $totalTax = $vacantLandTax;
                                    }
                                    $mobileTowerTax = $mobileTowerArea*$mrr*1.5;
                                    $totalTax += $mobileTowerTax+$hordingBoardTax;

                                    
                                    $isExist = true;
                                    foreach($lastArvDtl as $key => $mobileVal){
                                        if($mobileVal['fyID']==$fyVal['id']
                                            && $mobileVal['qtr']==$x_Qtr){

                                            $isExist = false;
                                            $lastArvDtl[$lastIncreament] = [
                                                'fyID'=> $fyVal['id'],
                                                'fy'=> $fyVal['fy'],
                                                'qtr'=> $x_Qtr,
                                                'arv'=>0,
                                                'holding_tax_yearly'=>$totalTax
                                            ];
                                        }
                                    }
                                    
                                    if($isExist){
                                        $lastIncreament++;
                                        $lastArvDtl[$lastIncreament] = [
                                            'fyID'=> $fyVal['id'],
                                            'fy'=> $fyVal['fy'],
                                            'qtr'=> $x_Qtr,
                                            'arv'=>0,
                                            'holding_tax_yearly'=>$totalTax
                                        ];
                                    }
                                }
                                
                            }

                            if($keyy=="hording" && $x_Qtr!=0 && $x_Qtr!=null)
                            {
                                if (!$hoadingBoardOneTimeImpliment){
                                    $hoadingBoardOneTimeImpliment = true;
                                    $mobileTowerTax = 0;
                                    if ($isVacantLandTemp==false || $x_Qtr!=$vacand_land_qtr_temp) {
                                        $calVacandLandArea = $vacantlandArea;
                                        if($isMobileTowerIncreaseTemp==true && $x_Qtr!=$mobile_tower_qtr_temp && $mobileTowerOneTimeImpliment==true){
                                            $calVacandLandArea = $calVacandLandArea-$mobileTowerArea;
                                            $mobileTowerTax = $mobileTowerArea*$mrr*1.5;
                                        }
                                        $calVacandLandArea = $calVacandLandArea-$hoardingBoardArea;
                                        $vacantLandTax = $calVacandLandArea*$mrr*1;
                                        $totalTax = $vacantLandTax;
                                    }
                                    $hordingBoardTax = $hoardingBoardArea*$mrr*1.5;
                                    $totalTax += $hordingBoardTax+$mobileTowerTax;

                                    $isExist = true;
                                    foreach($lastArvDtl as $key => $mobileVal){
                                        if($mobileVal['fyID']==$fyVal['id']
                                            && $mobileVal['qtr']==$x_Qtr){

                                            $isExist = false;
                                            $lastArvDtl[$lastIncreament] = [
                                                'fyID'=> $fyVal['id'],
                                                'fy'=> $fyVal['fy'],
                                                'qtr'=> $x_Qtr,
                                                'arv'=>0,
                                                'holding_tax_yearly'=>$totalTax
                                            ];

                                        }
                                    }
                                    if($isExist){
                                        $lastIncreament++;
                                        $lastArvDtl[$lastIncreament] = [
                                            'fyID'=> $fyVal['id'],
                                            'fy'=> $fyVal['fy'],
                                            'qtr'=> $x_Qtr,
                                            'arv'=>0,
                                            'holding_tax_yearly'=>$totalTax
                                        ];
                                    }
                                }
                            }
                        }
                        
                    }
                    //print_var($lastArvDtl);
                    foreach ($lastArvDtl as $key => $value) {
                        $safTaxDtl[$safTaxIncreament] = [
                            'fyID'=> $fyVal['id'],
                            'fy'=> $fyVal['fy'],
                            'qtr'=> $value['qtr'],
                            'arv'=>0,
                            'holding_tax_yearly'=>round($value['holding_tax_yearly'], 2),
                            'holding_tax'=>round(($value['holding_tax_yearly']/4), 2),
                            'water_tax'=>0,
                            'education_cess'=>0,
                            'health_cess'=>0,
                            'latrine_tax'=>0,
                            'additional_tax'=>0
                        ];
                        $safTaxIncreament++;
                    }
                } // end financial year foreach
                return $safTaxDtl;
            } else {
                $isWaterHarvesting = false;
                $area_of_plot = ($safDtl['area_of_plot']*40.5);
                if($area_of_plot > 300){
                    $isWaterHarvesting = true;
                    if($safDtl['is_water_harvesting']=='t'){
                        $isWaterHarvesting = false;
                    }
                }
                $floorDtlArr = $this->safCalInput($safDtl);
                $currentFY = getFY();
                $currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$currentFY])['id'];
                
                $yrOfEffect_16_17_FY = getFY("2016-04-01");
                $yrOfEffect_16_17_FyID = $this->model_fy_mstr->getFyByFy(['fy'=>$yrOfEffect_16_17_FY])['id'];

                $FromEffectFYID = 0;
                $prop_type_mstr_arr = array(1,5);
                if(in_array($safDtl["prop_type_mstr_id"], $prop_type_mstr_arr)){
                        $FromEffectFYID = $yrOfEffect_16_17_FyID;
                }else{
                    $FromEffectFYID = $currentFyID-12;
                }
                $getFyList = $this->model_fy_mstr->getFyByIdRangeAsc(['fromId'=>$FromEffectFYID, 'toId'=>$currentFyID]);
                $safTaxDtl = [];
                $safTaxIncreament = 0;
                $isCurrentFinancialYearEffected = false;
                $is_16_17_1st_qtr_tax_implement = false;

                foreach ($getFyList as $fyVal) {
                    $totalArv = 0;
                    $lastArvDtl = [];
                    $lastIncreament = -1;
                    $lastQtr = 0;

                    foreach ($floorDtlArr as $key => $floorDtl) { 
                        if ($fyVal['id']>=$floorDtl['fy_mstr_id']){
                            if ($yrOfEffect_16_17_FyID > $fyVal['id']) { // Old Rule Start
                                if ($floorDtl['type']=="floor") { // Only Floor Start
                                    
                                    $arv = $floorDtl['old_arv'];
                                    $usage_type_mstr_id = 2;
                                    if ($floorDtl['occupancy_type_mstr_id']==1
                                        && $floorDtl['usage_type_mstr_id']==1) {
                                        $usage_type_mstr_id = 1;
                                    }
                                    $arvRebate = 0;
                                    if ($usage_type_mstr_id==1) {
                                        $arvRebate += ($arv*30)/100;
                                    } else if ($usage_type_mstr_id==2) {
                                        $arvRebate += ($arv*15)/100;
                                    }
                                    if ($safDtl["prop_type_mstr_id"]==2
                                        && $floorDtl['occupancy_type_mstr_id']==1
                                        && $floorDtl['usage_type_mstr_id']==1) {
                                        $rebate_date = $floorDtl['date_from']."-01";
                                        if ("1942-04-01">$rebate_date) {
                                            if ( $arv!=0 ) {
                                                $arvRebate += (($arv*10)/100);
                                            }
                                        }
                                    }
                                    $arv -= $arvRebate;
                                    if ( $floorDtl['operator']=="+" ) {
                                        $totalArv += $arv;
                                    } else if ( $floorDtl['operator']=="-" ) {
                                        $totalArv -= $arv;
                                    }

                                    //echo $carperArea."x".$mrr." = , ARV => ".$floorDtl['operator'].$arv.", Total ARV => ".$totalArv.", date_from => ".$floorDtl['date_from']."FY => ".$fyVal['id']."<br />";
                                    if ($fyVal['id']==$floorDtl['fy_mstr_id']) {
                                        $temp_qtr = $floorDtl['qtr'];
                                        if ($lastQtr!=$temp_qtr) {
                                            $lastQtr=$temp_qtr;
                                            $lastIncreament++;
                                            $lastArvDtl[$lastIncreament] = [
                                                'fyID'=> $fyVal['id'],
                                                'arv'=>round($totalArv, 2),
                                                'qtr'=>$temp_qtr
                                            ];
                                        }else{
                                            $lastArvDtl[$lastIncreament] = [
                                                'fyID'=> $fyVal['id'],
                                                'arv'=>round($totalArv, 2),
                                                'qtr'=>$temp_qtr
                                            ];
                                        }
                                    }
                                } // Only Floor End

                            } // Old Rule End
                            if ($yrOfEffect_16_17_FyID <= $fyVal['id']) { // New Rule Start
                                if ($fyVal['id']==$yrOfEffect_16_17_FyID) {
                                    if ( !$is_16_17_1st_qtr_tax_implement ) {
                                        $oldARVTotal = 0;
                                        foreach ($floorDtlArr as $key => $floorDtlTemp) {
                                            if ($floorDtlTemp['type']=="floor") {
                                                if ($yrOfEffect_16_17_FyID > $floorDtlTemp['fy_mstr_id']) {
                                                    $isCurrentFinancialYearEffected = true;
                                                    $floorDateUptoFyID = $currentFyID;
                                                    if ($floorDtlTemp['date_upto']!="") {
                                                        $floorDateUptoFY = getFY($floorDtlTemp['date_upto']);
                                                        $floorDateUptoFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$floorDateUptoFY])['id'];
                                                    }

                                                    if ($yrOfEffect_16_17_FyID <= $floorDateUptoFyID) {
                                                        $arv = $floorDtl['new_arv'];
                                                        if ($floorDtl['type']=="floor") {
                                                            if($safDtl["prop_type_mstr_id"]==2
                                                                && $floorDtlTemp['occupancy_type_mstr_id']==1
                                                                && $floorDtlTemp['usage_type_mstr_id']==1){
                                                                $rebate_date = $floorDtlTemp['date_from']."-01";
                                                                if("1942-04-01">$rebate_date){
                                                                    if($arv!=0){
                                                                        $arvRebate = (($arv*10)/100);
                                                                        $arv = $arv - $arvRebate;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        if ( $arv!=0 ) {
                                                            if ( $floorDtlTemp['operator']=="+" ) {
                                                                $isSafNewRuleArv = true;
                                                            }
                                                            if ( $floorDtl['operator']=="+" ) {
                                                                $oldARVTotal += $arv;
                                                            } else if ( $floorDtl['operator']=="-" ) {
                                                                $oldARVTotal -= $arv;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        if ($oldARVTotal > 0) {
                                            //echo $oldARVTotal;
                                            $safTax = $oldARVTotal;
                                            $holding_tax = $safTax*0.02;
                                            $additional_tax = 0;
                                            if ($isWaterHarvesting==0) {
                                                $waterHarvestingTax = $holding_tax*1.5;
                                                $additional_tax = $waterHarvestingTax - $holding_tax;
                                                if($additional_tax!=0){
                                                    $additional_tax = round(($additional_tax/4), 2);
                                                }
                                            }
                                            if($holding_tax!=0){
                                                $holding_tax = round(($holding_tax/4), 2);
                                            }

                                            $isSafNewRuleArv = true;
                                            $lastIncreament++;
                                            $lastArvDtl[$lastIncreament] = [
                                                'fyID'=> $fyVal['id'],
                                                'arv'=>$oldARVTotal,
                                                'qtr'=>1
                                            ];
                                            $is_16_17_1st_qtr_tax_implement = true;
                                        }
                                    } // if new rule is implimented or not
                                } // end if old rule is not implimented in new rule

                                $arv = $floorDtl['new_arv'];
                                //echo $afr."x".$mf."x".$carperArea."x".$mrr." = ".$arv." => ".$floorDtl['date_from']."<br />";
                                if ($floorDtl['type']=="floor") {
                                    if($safDtl["prop_type_mstr_id"]==2
                                        && $floorDtl['occupancy_type_mstr_id']==1
                                        && $floorDtl['usage_type_mstr_id']==1){
                                        $rebate_date = $floorDtl['date_from']."-01";
                                        if("1942-04-01">$rebate_date){
                                            if($arv!=0){
                                                $arvRebate = (($arv*10)/100);
                                                $arv = $arv - $arvRebate;
                                            }
                                        }
                                    }
                                }
                                //$totalArv += $arv;
                                if ( $floorDtl['operator']=="+" ) {
                                    $totalArv += $arv;
                                } else if ( $floorDtl['operator']=="-" ) {
                                    $totalArv -= $arv;
                                }
                                if($fyVal['id']==$floorDtl['fy_mstr_id']){
                                    $isCurrentFinancialYearEffected = true;
                                    /* echo "TYPE : ".$floorDtl['type'].", floor : ".$floorDtl['floor_mstr_id'].", date_from : ".$floorDtl['date_from'].", date_upto : ".$floorDtl['date_upto'];
                                    //echo "<br />"; */
                                    $temp_qtr = $floorDtl['qtr'];

                                    $isExist = true;
                                    foreach($lastArvDtl as $key => $tempLastArvDtl){
                                        if($tempLastArvDtl['fyID']==$fyVal['id']
                                            && $tempLastArvDtl['qtr']==$temp_qtr){

                                            $isExist = false;
                                            $lastArvDtl[$key] = [
                                                'fyID'=> $fyVal['id'],
                                                'arv'=>$totalArv,
                                                'qtr'=>$temp_qtr
                                            ];
                                        }
                                    }
                                    if($isExist){
                                        $lastIncreament++;
                                        $lastArvDtl[$lastIncreament] = [
                                            'fyID'=> $fyVal['id'],
                                            'arv'=>$totalArv,
                                            'qtr'=>$temp_qtr
                                        ];
                                    }

                                }
                            } // New Rule End
                        }
                    } //end floorDtlArr foreach loop
                    foreach($lastArvDtl as $key => $value){
                        //if ($value['arv']>0) {
                            $holding_tax = 0;
                            $water_tax = 0;
                            $education_cess = 0;
                            $health_cess = 0;
                            $latrine_tax = 0;
                            $additional_tax = 0;
                            $safTaxQtr = $value['arv'];
                            if ($yrOfEffect_16_17_FyID > $fyVal['id']) {
                                $holding_tax = $safTaxQtr*0.125;
                                if ($holding_tax!=0) {
                                    $holding_tax = round(($holding_tax/4), 2);
                                }
                                $water_tax = $safTaxQtr*0.075;
                                if ($water_tax!=0) {
                                    $water_tax = round(($water_tax/4), 2);
                                }
                                $education_cess = $safTaxQtr*0.05;
                                if ($education_cess!=0) {
                                    $education_cess = round(($education_cess/4), 2);
                                }
                                $health_cess = $safTaxQtr*0.0625;
                                if ($health_cess!=0) {
                                    $health_cess = round(($health_cess/4), 2);
                                }
                                $latrine_tax = $safTaxQtr*0.075;
                                if ($latrine_tax!=0) {
                                    $latrine_tax = round(($latrine_tax/4), 2);
                                }
                            } else {
                                $holding_tax = $safTaxQtr*0.02;
                                //if ($safDtl['']) {
                                if($isWaterHarvesting){
                                    $waterHarvestingTax = $holding_tax*1.5;
                                    $additional_tax = $waterHarvestingTax - $holding_tax;
                                    if($additional_tax!=0){
                                        $additional_tax = round(($additional_tax/4), 2);
                                    }
                                }
                                if( $holding_tax!=0) {
                                    $holding_tax = round(($holding_tax/4), 2);
                                }
                            }
                            $safTaxDtl[$safTaxIncreament] = [
                                'fyID'=>$fyVal['id'],
                                'fy'=> $fyVal['fy'],
                                'arv'=>round($value['arv'], 2),
                                'qtr'=>$value['qtr'],
                                'holding_tax'=>$holding_tax,
                                'water_tax'=>$water_tax,
                                'education_cess'=>$education_cess,
                                'health_cess'=>$health_cess,
                                'latrine_tax'=>$latrine_tax,
                                'additional_tax'=>$additional_tax
                            ];
                            $safTaxIncreament++;
                        //}
                    }
                } // Financial Year Loop End
                return $safTaxDtl;
            }
        }
	}
    public function safCalInput($safDtl) {
        $j = 0;
        $taxEffectedFrom = "2008-04-01";//date('Y-04-01', strtotime('-12 year'));
        $taxEffectedFromFY = getFY($taxEffectedFrom);
        $taxEffectedFromFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$taxEffectedFromFY])['id']; // 2009-04-01, ID = 40
        $yrOfEffect_16_17_FY = getFY("2016-04-01");
		$yrOfEffect_16_17_FyID = $this->model_fy_mstr->getFyByFy(['fy'=>$yrOfEffect_16_17_FY])['id'];

        $floorDtlArr = [];

        if ($safDtl['is_mobile_tower']=='t') {
            $mobileTowerFY = getFY($safDtl['tower_installation_date']);
            $mobileTowerFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$mobileTowerFY])['id'];
            $MM = date("m", strtotime($safDtl['tower_installation_date']));
            if ($MM>=1 && 3>=$MM) { // X1
                $temp_qtr = 4;
            } else if($MM>=4 && 6>=$MM) { // X4
                $temp_qtr = 1;
            } else if($MM>=7 && 9>=$MM) { // X3
                $temp_qtr = 2;
            } else if($MM>=10 && 12>=$MM) { // X2
                $temp_qtr = 3;
            }
            $mobileTowerQtr = $temp_qtr;
            $date_from = "2016-04";
            if (date("Y-m-01", strtotime($safDtl['tower_installation_date'])) > "2016-04-01") {
                $date_from = date("Y-m", strtotime($safDtl['tower_installation_date']));
            } else {
                $mobileTowerFyID = $yrOfEffect_16_17_FyID;
                $mobileTowerQtr = 1;
            }

            $sendInput = ['road_type_mstr_id'=>$safDtl['road_type_mstr_id'], 'const_type_mstr_id'=>1, 'date_of_effect'=>'2016-04-01'];
            $mrrDtl = $this->model_arr_building_mstr->getMRRCalRate($sendInput);
            $mrr = 0;
            if ($mrrDtl) {
                $mrr = $mrrDtl['cal_rate'];
            }
            $carperArea = $safDtl['tower_area'];
            $new_arv = 1.5*1.5*$carperArea*$mrr;

            $floorDtlArr[$j] = [
                'type'=>'mobile',
                'floor_mstr_id'=>0,
                'usage_type_mstr_id'=>0,
                'occupancy_type_mstr_id'=>0,
                'const_type_mstr_id'=>1,
                'builtup_area'=>$safDtl['tower_area'],
                'date_from'=>$date_from,
                'date_upto'=>"",
                'fy_mstr_id'=>$mobileTowerFyID,
                'qtr'=>$mobileTowerQtr,
                'upto_fy_mstr_id'=>0,
                'upto_qtr'=>0,
                'old_arv'=>0,
                'new_arv'=>$new_arv,
                'operator'=>'+'
            ];
            $j++;
        }

        if ($safDtl['is_hoarding_board']=='t') {
            $hoardinBoardFY = getFY($safDtl['hoarding_installation_date']);
            $hoardinBoardFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$hoardinBoardFY])['id'];
            $MM = date("m", strtotime($safDtl['hoarding_installation_date']));
            if ($MM>=1 && 3>=$MM) { // X1
                $temp_qtr = 4;
            } else if($MM>=4 && 6>=$MM) { // X4
                $temp_qtr = 1;
            } else if($MM>=7 && 9>=$MM) { // X3
                $temp_qtr = 2;
            } else if($MM>=10 && 12>=$MM) { // X2
                $temp_qtr = 3;
            }
            $hoardinBoardQtr = $temp_qtr;

            $date_from = "2016-04";
            if (date("Y-m-01", strtotime($safDtl['hoarding_installation_date'])) > "2016-04-01") {
                $date_from = date("Y-m", strtotime($safDtl['hoarding_installation_date']));
            } else {
                $hoardinBoardFyID = $yrOfEffect_16_17_FyID;
                $hoardinBoardQtr = 1;
            }

            $sendInput = ['road_type_mstr_id'=>$safDtl['road_type_mstr_id'], 'const_type_mstr_id'=>1, 'date_of_effect'=>'2016-04-01'];
            $mrrDtl = $this->model_arr_building_mstr->getMRRCalRate($sendInput);
            $mrr = 0;
            if ($mrrDtl) {
                $mrr = $mrrDtl['cal_rate'];
            }
            $carperArea = $safDtl['hoarding_area'];
            $new_arv = 1.5*1.5*$carperArea*$mrr;

            $floorDtlArr[$j] = [
                'type'=>'hoarding',
                'floor_mstr_id'=>0,
                'usage_type_mstr_id'=>0,
                'occupancy_type_mstr_id'=>0,
                'const_type_mstr_id'=>1,
                'builtup_area'=>$safDtl['hoarding_area'],
                'date_from'=>$date_from,
                'date_upto'=>"",
                'fy_mstr_id'=>$hoardinBoardFyID,
                'qtr'=>$hoardinBoardQtr,
                'upto_fy_mstr_id'=>0,
                'upto_qtr'=>0,
                'old_arv'=>0,
                'new_arv'=>$new_arv,
                'operator'=>'+'
            ];
            $j++;
        }

        if ($safDtl['is_petrol_pump']=='t' && $safDtl['prop_type_mstr_id']!=4) {
            $petrolPumpFY = getFY($safDtl['petrol_pump_completion_date']);
            $petrolPumpFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$petrolPumpFY])['id'];
            $MM = date("m", strtotime($safDtl['petrol_pump_completion_date']));
            if ($MM>=1 && 3>=$MM) { // X1
                $temp_qtr = 4;
            } else if($MM>=4 && 6>=$MM) { // X4
                $temp_qtr = 1;
            } else if($MM>=7 && 9>=$MM) { // X3
                $temp_qtr = 2;
            } else if($MM>=10 && 12>=$MM) { // X2
                $temp_qtr = 3;
            }
            $petrolPumpQtr = $temp_qtr;

            $date_from = "2016-04";
            if (date("Y-m-01", strtotime($safDtl['petrol_pump_completion_date'])) > "2016-04-01") {
                $date_from = date("Y-m", strtotime($safDtl['petrol_pump_completion_date']));
            } else {
                $petrolPumpFyID = $yrOfEffect_16_17_FyID;
                $petrolPumpQtr = 1;
            }

            $sendInput = ['road_type_mstr_id'=>$safDtl['road_type_mstr_id'], 'const_type_mstr_id'=>1, 'date_of_effect'=>'2016-04-01'];
            $mrrDtl = $this->model_arr_building_mstr->getMRRCalRate($sendInput);
            $mrr = 0;
            if ($mrrDtl) {
                $mrr = $mrrDtl['cal_rate'];
            }
            $carperArea = $safDtl['hoarding_area'];
            $new_arv = 1.5*1.5*$carperArea*$mrr;

            $floorDtlArr[$j] = [
                'type'=>'petrol',
                'floor_mstr_id'=>0,
                'usage_type_mstr_id'=>0,
                'occupancy_type_mstr_id'=>0,
                'const_type_mstr_id'=>1,
                'builtup_area'=>$safDtl['under_ground_area'],
                'date_from'=>$date_from,
                'date_upto'=>"",
                'fy_mstr_id'=>$petrolPumpFyID,
                'qtr'=>$petrolPumpQtr,
                'upto_fy_mstr_id'=>0,
                'upto_qtr'=>0,
                'old_arv'=>0,
                'new_arv'=>$new_arv,
                'operator'=>'+'
            ];
            $j++;
        }

        if ($safDtl['prop_type_mstr_id']!=4) {
            $floorDtl = $this->model_field_verification_floor_details->getDtlBySafDtlId($safDtl['saf_dtl_id'], $safDtl['id']);
            foreach($floorDtl AS $key => $floorList) {
                $floorDateFromFY = getFY($floorList['date_from']);
                $floorDateFromFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$floorDateFromFY])['id'];
                $MM = date("m", strtotime($floorList['date_from']));
                if ($MM>=1 && 3>=$MM) { // X1
                    $temp_qtr = 4;
                } else if($MM>=4 && 6>=$MM) { // X4
                    $temp_qtr = 1;
                } else if($MM>=7 && 9>=$MM) { // X3
                    $temp_qtr = 2;
                } else if($MM>=10 && 12>=$MM) { // X2
                    $temp_qtr = 3;
                }

                $floorDateUptoFyID = 0;
                $floorDateUptoQtr = 0;
                $floorDateUptoQtrTemp = 0;
                if ($floorList['date_upto']<>"") {
                    $floorDateUptoFY = getFY($floorList['date_upto']);
                    $floorDateUptoFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$floorDateUptoFY])['id'];
                    $MM = date("m", strtotime($floorList['date_upto']));
                    if($MM>=1 && 3>=$MM){ // X1
                        $floorDateUptoQtr = 4;
                    }else if($MM>=4 && 6>=$MM){ // X4
                        $floorDateUptoQtr = 1;
                    }else if($MM>=7 && 9>=$MM){ // X3
                        $floorDateUptoQtr = 2;
                    }else if($MM>=10 && 12>=$MM){ // X2
                        $floorDateUptoQtr = 3;
                    }
                    $floorDateUptoQtrTemp = $floorDateUptoQtr;
                }

                if ($floorList['date_from']."-01" < $taxEffectedFrom) {
                    $floorDateFromFyID = $taxEffectedFromFyID;
                    $temp_qtr = 1;
                }

                //======================== OLD RULE START ARV CALC ===============================
                $carperArea = $floorList['builtup_area'];
                $usage_type_mstr_id = 2;
                if ($floorList['occupancy_type_mstr_id']==1
                    && $floorList['usage_type_mstr_id']==1) {
                    $usage_type_mstr_id = 1;
                }
                
                $sendInput = [
                    'usage_type_mstr_id'=>(int)$usage_type_mstr_id,
                    'const_type_mstr_id'=>(int)$floorList['const_type_mstr_id'],
                    'zone_mstr_id'=>(int)$safDtl['zone_mstr_id']
                ];
                
                $mrr = $this->model_arr_old_building_mstr->getMRRCalRate($sendInput)['rate'];
                if (!$mrr){ $mrr = 0; }
                $old_arv = $carperArea*$mrr;
                //======================== OLD RULE END ARV CALC ===============================
                //======================== NEW RULE START ARV CALC ===============================
                $afr = $this->model_occupancy_type_mstr->getOccupancyMultFactById($floorList['occupancy_type_mstr_id'])['mult_factor'];
                if (!$afr) { $afr = 0;}
                if ($floorList['usage_type_mstr_id']==1) {
                    $carperArea = (($floorList['builtup_area']*70)/100);
                } else {
                    $carperArea = (($floorList['builtup_area']*80)/100);
                }
                
                $sendInput = ['usage_type_mstr_id'=>$floorList['usage_type_mstr_id']];
                $mfDtl = $this->model_usage_type_dtl->getUsageTypeMultFact($sendInput);
                $mf = 0;
                if ($mfDtl) {
                    $mf = $mfDtl['mult_factor'];
                }

                $sendInput = ['road_type_mstr_id'=>$safDtl['road_type_mstr_id'], 'const_type_mstr_id'=>$floorList['const_type_mstr_id'], 'date_of_effect'=>'2016-04-01'];
                $mrrDtl = $this->model_arr_building_mstr->getMRRCalRate($sendInput);
                $mrr = 0;
                if ($mrrDtl) {
                    $mrr = $mrrDtl['cal_rate'];
                }
                
                $new_arv = $afr*$mf*$carperArea*$mrr;
                //echo $afr."x".$mf."x".$carperArea."x".$mrr." = ".$new_arv."<br />";
                //echo "<br />";
                //======================== NEW RULE END ARV CALC ===============================

                $floorDtlArr[$j] = [
                    'type'=>'floor',
                    'floor_mstr_id'=>$floorList['floor_mstr_id'],
                    'usage_type_mstr_id'=>$floorList['usage_type_mstr_id'],
                    'occupancy_type_mstr_id'=>$floorList['occupancy_type_mstr_id'],
                    'const_type_mstr_id'=>$floorList['const_type_mstr_id'],
                    'builtup_area'=>$floorList['builtup_area'],
                    'date_from'=>$floorList['date_from'],
                    'date_upto'=>$floorList['date_upto'],
                    'fy_mstr_id'=>$floorDateFromFyID,
                    'qtr'=>$temp_qtr,
                    'upto_fy_mstr_id'=>$floorDateUptoFyID,
                    'upto_qtr'=>$floorDateUptoQtr,
                    'old_arv' => $old_arv,
                    'new_arv' => $new_arv,
                    'operator'=>'+'
                ];
                $j++;

                if ( $floorDateUptoFyID<>0 && $floorDateUptoQtr<>0 ) {
                    if ( $floorDateUptoQtr==4 ) {
                        $floorDateUptoQtr = 1;
                        $floorDateUptoFyID = $floorDateUptoFyID+1;
                    }else {
                        $floorDateUptoQtr = $floorDateUptoQtr+1;
                    }
                    $date_upto = $floorList['date_upto'];
                    if ( $floorDateUptoQtrTemp==1 ) {
                        $date_upto = date("Y", strtotime($floorList['date_upto']))."-09";
                    } else if ( $floorDateUptoQtrTemp==2 ) {
                        $date_upto = date("Y", strtotime($floorList['date_upto']))."-12";
                    } else if ( $floorDateUptoQtrTemp==3 ) {
                        $YYYY = date("Y", strtotime($floorList['date_upto']));
                        $YYYY = $YYYY+1;
                        $date_upto = $YYYY."-03";
                    } else if ( $floorDateUptoQtrTemp==4 ) {
                        $date_upto = date("Y", strtotime($floorList['date_upto']))."-06";
                    } 
                    $floorDtlArr[$j] = [
                        'type'=>'floor',
                        'floor_mstr_id'=>$floorList['floor_mstr_id'],
                        'usage_type_mstr_id'=>$floorList['usage_type_mstr_id'],
                        'occupancy_type_mstr_id'=>$floorList['occupancy_type_mstr_id'],
                        'const_type_mstr_id'=>$floorList['const_type_mstr_id'],
                        'builtup_area'=>$floorList['builtup_area'],
                        'date_from'=>$date_upto,
                        'date_upto'=>$date_upto,
                        'fy_mstr_id'=>$floorDateUptoFyID,
                        'qtr'=>$floorDateUptoQtr,
                        'upto_fy_mstr_id'=>$floorDateUptoFyID,
                        'upto_qtr'=>$floorDateUptoQtr,
                        'old_arv' => $old_arv,
                        'new_arv' => $new_arv,
                        'operator'=>'-'
                    ];
                    $j++;
                }
            }
        }

        usort($floorDtlArr, 'floor_date_compare');
        //var_dump($floorDtlArr);
        return $floorDtlArr;
    }

    public function calcDiffPanelty($safTaxDtl, $newSafTaxDtl){
        $i = 1;
        $diffTax = [];
        foreach($newSafTaxDtl AS $key => $newList) {
            $_fy_id = $newList['fyID'];
            $_fy = $newList['fy'];
            $_qtr = $newList['qtr'];
            $_arv = $newList['arv'];
            $_holding_tax = $newList['holding_tax'];
            $_water_tax = $newList['water_tax'];
            $_education_cess = $newList['education_cess'];
            $_health_cess = $newList['health_cess'];
            $_latrine_tax = $newList['latrine_tax'];
            $_additional_tax = $newList['additional_tax'];

            foreach($safTaxDtl AS $key => $oldList) {
                if ($oldList['fy_mstr_id']==$_fy_id && $oldList['qtr']==$_qtr) {
                    $_arv -= $oldList['arv'];
                    $_holding_tax -= $oldList['holding_tax'];
                    $_water_tax -= $oldList['water_tax'];
                    $_education_cess -= $oldList['education_cess'];
                    $_health_cess -= $oldList['health_cess'];
                    $_latrine_tax -= $oldList['latrine_tax'];
                    $_additional_tax -= $oldList['additional_tax'];
                }
            }
            if ($_arv>0) {
                $diffTax[$i]['fy_id'] = $_fy_id;
                $diffTax[$i]['fy'] = $_fy;
                $diffTax[$i]['qtr'] = $_qtr;
                $diffTax[$i]['arv'] = $_arv;
                $diffTax[$i]['holding_tax'] = $_holding_tax;
                $diffTax[$i]['water_tax'] = $_water_tax;
                $diffTax[$i]['education_cess'] = $_education_cess;
                $diffTax[$i]['health_cess'] = $_health_cess;
                $diffTax[$i]['latrine_tax'] = $_latrine_tax;
                $diffTax[$i]['additional_tax'] = $_additional_tax;
                $i++;
            }
        }
        return $diffTax;
    }
}