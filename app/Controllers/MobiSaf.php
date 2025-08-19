<?php
namespace App\Controllers;
use App\Controllers\SAF\SAFHelper;
use CodeIgniter\Controller;
use App\Models\model_ulb_mstr;
use App\Models\model_fy_mstr;
use App\Models\model_ward_mstr;
use App\Models\model_ward_permission;
use App\Models\model_view_ward_mapping_mstr;
use App\Models\model_apartment_details;
use App\Models\model_transfer_mode_mstr;
use App\Models\model_ownership_type_mstr;
use App\Models\model_prop_type_mstr;
use App\Models\model_road_type_mstr;
use App\Models\model_floor_mstr;
use App\Models\model_usage_type_mstr;
use App\Models\model_usage_type_dtl;
use App\Models\model_occupancy_type_mstr;
use App\Models\model_const_type_mstr;
use App\Models\model_saf_dtl;
use App\Models\model_saf_owner_detail;
use App\Models\model_saf_floor_details;
use App\Models\model_saf_floor_arv_dtl;
use App\Models\model_saf_tax;
use App\Models\model_saf_demand;
use App\Models\model_arr_old_building_mstr;
use App\Models\model_arr_building_mstr;
use App\Models\model_arr_vacant_mstr;
use App\Models\model_prop_dtl;
use App\Models\model_prop_owner_detail;
use App\Models\model_prop_demand;
use App\Models\model_payment_adjust;
use App\Models\model_saf_distributed_dtl;
use App\Models\model_transaction;
use App\Models\model_prop_floor_details;
use App\Models\model_visiting_dtl;
use Exception;

class MobiSaf extends MobiController
{
	protected $db;
	protected $dbSystem;
	protected $model_ulb_mstr;
	protected $model_fy_mstr;
	protected $model_ward_mstr;
	protected $model_ward_permission;
	protected $model_view_ward_mapping_mstr;
	protected $model_apartment_details;
	protected $model_transfer_mode_mstr;
	protected $model_ownership_type_mstr;
	protected $model_prop_type_mstr;
	protected $model_road_type_mstr;
	protected $model_floor_mstr;
	protected $model_usage_type_mstr;
	protected $model_usage_type_dtl;
	protected $model_occupancy_type_mstr;
	protected $model_const_type_mstr;
	protected $model_saf_dtl;
	protected $model_saf_owner_detail;
	protected $model_saf_floor_details;
	protected $model_saf_floor_arv_dtl;
	protected $model_saf_tax;
	protected $model_saf_demand;
	protected $model_arr_old_building_mstr;
	protected $model_arr_building_mstr;
	protected $model_arr_vacant_mstr;
	protected $model_prop_dtl;
	protected $model_prop_owner_detail;
	protected $model_prop_demand;
	protected $model_payment_adjust;
	protected $model_saf_distributed_dtl;
	protected $model_transaction;
	protected $model_prop_floor_details;
	protected $model_visiting_dtl;

	public function __construct()
    {
        parent::__construct();
    	helper(['form', 'url', 'db_helper', 'validation_helper', 'validate_saf_helper','form_helper']);
        if($db_name = dbConfig("property")){
			//echo $db_name;
            $this->db = db_connect($db_name);
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system);
        }
        $this->model_fy_mstr = new model_fy_mstr($this->dbSystem);
		$this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
		$this->model_ward_permission = new model_ward_permission($this->dbSystem);
		$this->model_view_ward_mapping_mstr = new model_view_ward_mapping_mstr($this->dbSystem);
        $this->model_apartment_details = new model_apartment_details($this->db);
        $this->model_transfer_mode_mstr = new model_transfer_mode_mstr($this->db);
        $this->model_ownership_type_mstr = new model_ownership_type_mstr($this->db);
        $this->model_prop_type_mstr = new model_prop_type_mstr($this->db);
        $this->model_road_type_mstr = new model_road_type_mstr($this->db);
        $this->model_floor_mstr = new model_floor_mstr($this->db);
        $this->model_usage_type_mstr = new model_usage_type_mstr($this->db);
        $this->model_usage_type_dtl = new model_usage_type_dtl($this->db);
        $this->model_occupancy_type_mstr = new model_occupancy_type_mstr($this->db);
        $this->model_const_type_mstr = new model_const_type_mstr($this->db);
        $this->model_saf_dtl = new model_saf_dtl($this->db);
        $this->model_saf_owner_detail = new model_saf_owner_detail($this->db);
		$this->model_saf_floor_details = new model_saf_floor_details($this->db);
		$this->model_saf_floor_arv_dtl = new model_saf_floor_arv_dtl($this->db);
        $this->model_saf_tax = new model_saf_tax($this->db);
        $this->model_saf_demand = new model_saf_demand($this->db);
        $this->model_arr_old_building_mstr = new model_arr_old_building_mstr($this->db);
        $this->model_arr_building_mstr = new model_arr_building_mstr($this->db);
		$this->model_arr_vacant_mstr = new model_arr_vacant_mstr($this->db);
		$this->model_prop_dtl = new model_prop_dtl($this->db);
		$this->model_prop_owner_detail = new model_prop_owner_detail($this->db);
		$this->model_prop_demand = new model_prop_demand($this->db);
		$this->model_payment_adjust = new model_payment_adjust($this->db);
		$this->model_saf_distributed_dtl = new model_saf_distributed_dtl($this->db);
		$this->model_transaction = new model_transaction($this->db);
		$this->model_prop_floor_details = new model_prop_floor_details($this->db);
		$this->model_visiting_dtl = new model_visiting_dtl($this->dbSystem);
    }

	function __destruct() {
		if(isset($this->db)) $this->db->close();
		if(isset($this->dbSystem)) $this->dbSystem->close();
	}

	public function searchDistributedDtl($ID = NULL, $SAF_FORM_ID = NULL)
	{
		if($ID =='New-Assessment') {
			if ($SAF_FORM_ID != null) {
				$parameter = md5(0)."::".hashEncrypt($ID)."/".$SAF_FORM_ID;
				$_POST = array();
				return redirect()->to(base_url('MobiSaf/AddUpdate2/'.$parameter));
			} else {
				$parameter = md5(0)."::".hashEncrypt($ID);
				$_POST = array();
				return redirect()->to(base_url('MobiSaf/AddUpdate2/'.$parameter));
			}
		}
		$Session = session();
		$inputs = arrFilterSanitizeString($this->request->getVar());
		$data = $inputs;
		$ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
		$wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
		$data['wardList'] = $wardList;
		if (!is_null($ID)) {
			$input = ['saf_distributed_dtl_id'=> $ID];
			if ($results = $this->model_saf_distributed_dtl->getDetailsBySafDistributedDtlId($input)) {
				flashToast('saf_no_encrypted', $results['saf_no']);
				$Session->set('form_no', $results['form_no']);
				$Session->set('saf_distributed_dtl_id', $results['id']);
				return redirect()->to(base_url('MobiSaf/addupdate2'));
			} else {
				$Session->remove('form_no');
				$Session->remove('saf_distributed_dtl_id');
				flashToast('message', "SAF does't exist !!!");
				return view('mobile/saf/saf_search_distributed_dtl');
			}
		} else {
			$Session->remove('form_no');
			$Session->remove('saf_distributed_dtl_id');
			if($this->request->getMethod()=='post') {
				if ($this->request->getVar("search")=="search") {
					try {
						$input = ['form_no'=> $inputs['form_no']];
						if($results = $this->model_saf_distributed_dtl->getDetailsBySAFFormNo($input)) {
							$data['saf_distributed_dtl_list'] = $results;
						} else {
							flashToast('message', $input['form_no']." = does`t exist !!!");
						}
						return view('mobile/saf/saf_search_distributed_dtl', $data);
					} catch(Exception $e) {
						echo $e->getMessage();
						flashToast('message', $e->getMessage());
					}
				} else {
					try {
						$inputs = arrFilterSanitizeString($this->request->getVar());
						$input = ['holding_id'=>$inputs['holding_id'], 'ward_mstr_id'=>$inputs['ward_mstr_id'], 'holding_no'=>$inputs['holding_no']];

						if ($prop_dtl = $this->model_prop_dtl->getPropIdByWardNoHodingNo($input)) {
							$input = [
								'prop_dtl_id'=>$prop_dtl[0]['id']
							];
							$isPaymentCleared = "NO";
							$isPaymentCleared = "YES";
							if (!$this->model_prop_demand->getIsDemandClearedByPropDtlId($input)) {
								$isPaymentCleared = "YES";
							} else if ($prop_dtl[0]["new_holding_no"]=="") {
								$isPaymentCleared = "YES";
							}

							$prop_dtl['isPaymentCleared'] = $isPaymentCleared;
							$process = false;

							if(isset($inputs['YES'])) {
								$process = true;
								$assessmentType = "Re-Assessment";
							} else if(isset($inputs['NO'])) {
								if($isPaymentCleared=="YES")
								{
									$process = true;
									$assessmentType = "Mutation";
								}
							}
							if(($assessmentType=="Mutation" || isset($inputs['NO'])) && in_array($prop_dtl[0]["prop_type_mstr_id"],[1,5])){
								flashToast('safmanual', "SUPER STRUCTURE AND OCCUPIED PROPERTY Can Not Apply Mutation");
								flashToast('message', "SUPER STRUCTURE AND OCCUPIED PROPERTY Can Not Apply Mutation");
								return redirect()->to(base_url('MobiSaf/searchDistributedDtl'));
							}

							if ($process) {
								if($inputs['form_id']!=''){
									$parameter = md5($inputs['holding_id'])."::".hashEncrypt($assessmentType).'/'.$inputs['form_id'];
									return redirect()->to(base_url('MobiSaf/AddUpdate2/'.$parameter));
								}
								else{
									$parameter = md5($inputs['holding_id'])."::".hashEncrypt($assessmentType);
									return redirect()->to(base_url('MobiSaf/AddUpdate2/'.$parameter));
								}
							} else {
								flashToast('safmanual', "Please clear your dues before proceeding...");
								return redirect()->to(base_url('MobiSaf/searchDistributedDtl'));
							}
						}
					}catch(Exception $e){
						echo $e->getMessage();
						flashToast('message', $e->getMessage());
					}
				}
			} else {
				return view('mobile/saf/saf_search_distributed_dtl', $data);
			}
		}
	}

	public function addUpdate2($param = null, $SAF_FORM_ID=NULL) {
		$safHelper = new SAFHelper($this->db);
        $data = arrFilterSanitizeString($this->request->getVar());
        $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
		$emp_details_id = $_SESSION['emp_details']['id'];
		$assessmentType = "";
        if ($this->request->getMethod()!='post') {
            $assessmentType = "New-Assessment";
            if (!is_null($param)) {
                $paramExplode = explode("::", $param);
                $ID = $paramExplode[0];
                $assessmentType = hashDecrypt($paramExplode[1]); //New-Assessment, Re-Assessment, Mutation
                $prop_dtl_list = $this->model_prop_dtl->getPropIdHodingNoWardByMD5ID(['id'=>$ID]);
				if (!empty($prop_dtl_list)) {
					$pendingSaf = $this->db->query("select * from tbl_saf_dtl where previous_holding_id='".$prop_dtl_list['id']."' and saf_pending_status!=1 and status=1 order by id desc")->getFirstRow("array");
					$pendingNotice = $this->db->query("select * from tbl_prop_notices where prop_dtl_id='".$prop_dtl_list['id']."' and notice_type='Demand' and status=1 order by id desc")->getFirstRow("array"); 
					// if($pendingSaf && $assessmentType!="Mutation"){
					// 	flashToast('message',"Saf Is Already apply. Please Wait For Approval");
					// 	return redirect()->back();
					// }
					// if($pendingNotice && $assessmentType!="Mutation"){
					// 	flashToast('message',"Notice Had Generated. Please Clear First");
					// 	return redirect()->back();
					// }

					if($assessmentType=="Mutation" && in_array($prop_dtl_list["prop_type_mstr_id"],[1,5])){
						flashToast('message',"SUPER STRUCTURE AND OCCUPIED PROPERTY Can Not Apply Mutation");
						return redirect()->back();
					}
				}
                $prop_owner_dtl_list = $this->model_prop_owner_detail->getPropOwnerDtlByPropDtlId(['prop_dtl_id'=>$prop_dtl_list['id']]);
            }
            $data["has_previous_holding_no"] = 0;
            $data["is_owner_changed"] = 0;
            if ($assessmentType=="Re-Assessment") {
                $data["has_previous_holding_no"] = 1;
                $data["is_owner_changed"] = 0;
            } else if ($assessmentType=="Mutation") {
                $data["has_previous_holding_no"] = 1;
                $data["is_owner_changed"] = 1;
				list($fromYear,$uptoYear) = explode("-",getFY());
				$fyear = ($fromYear-1)."-".$fromYear;
				if(!trim($prop_dtl_list["new_holding_no"])){
					$fyear = '2015-2016';
				}
				$dueDemand = $this->db->query("select * from prop_getdemand(".($prop_dtl_list["id"]??0).",'$fyear',4)")->getFirstRow("array");
				
				if($dueDemand["prop_getdemand"]){
					$data["dueAmount"] = $dueDemand["prop_getdemand"];
				}
            }
            if ($assessmentType!="New-Assessment") {
                $data['prev_prop_dtl_id'] = $prop_dtl_list['id'];
                $data['holding_no'] = $prop_dtl_list['holding_no'];
                $data['ward_mstr_id'] = $prop_dtl_list['ward_mstr_id'];
                $data['prop_owner_dtl_list'] = $prop_owner_dtl_list;

                $data["newWardList"] = $safHelper->getNewWardByOldWardID($prop_dtl_list["ward_mstr_id"]);

                foreach ($prop_dtl_list as $key=>$value) {
                    if($key=="is_mobile_tower")
                        $data[$key]=($value=="t")?"1":"0";
                    else if($key=="is_hoarding_board")
                        $data[$key]=($value=="t")?"1":"0";
                    else if($key=="is_petrol_pump")
                        $data[$key]=($value=="t")?"1":"0";
                    else if($key=="is_water_harvesting")
                        $data[$key]=($value=="t")?"1":"0";
                    else
                        $data[$key]=$value;
                }

                $prop_floor=$this->model_prop_floor_details->getFloorByPropId(["prop_dtl_id"=> $prop_dtl_list['id']]);
                foreach ($prop_floor as $key=>$row) {
                	$data["prop_floor_details_id"][$key]=$row['id'];
                    $data["floor_mstr_id"][$key]=$row['floor_mstr_id'];
                    $data["usage_type_mstr_id"][$key]=$row['usage_type_mstr_id'];
                    $data["occupancy_type_mstr_id"][$key]=$row['occupancy_type_mstr_id'];
                    $data["const_type_mstr_id"][$key]=$row['const_type_mstr_id'];
                    $data["builtup_area"][$key]=$row['builtup_area'];
                    $data["date_from"][$key]=date("Y-m", strtotime($row['date_from']));
                    $data["date_upto"][$key]=($row['date_upto']=="")?"":date("Y-m", strtotime($row['date_upto']));
                }

                if ($assessmentType=="Re-Assessment") {
                    foreach($prop_owner_dtl_list as $key=>$row) {
                        $data["owner_name"][$key]=$row["owner_name"];
                        $data["guardian_name"][$key]=$row["guardian_name"];
                        $data["relation_type"][$key]=$row["relation_type"];
                        $data["mobile_no"][$key]=$row["mobile_no"];
                        $data["aadhar_no"][$key]=$row["aadhar_no"];
                        $data["pan_no"][$key]=$row["pan_no"];
                        $data["email"][$key]=$row["email"];
						$data["gender"][$key]=$row["gender"];
						$data["dob"][$key]=$row["dob"];
						$data["is_armed_force"][$key]=$row["is_armed_force"];
						$data["is_specially_abled"][$key]=$row["is_specially_abled"];
                    }
                }
            }
			$data['assessmentType'] = $assessmentType;
        }
		//$this->model_apartment_details
        $data['param'] = $param;
		$data['assessment_type'] = $assessmentType;
        $data['ulb_address'] = $this->model_ulb_mstr->getAddressById(['ulb_mstr_id'=>$ulb_mstr_id]);
		$empPemittedWardList = $this->model_ward_permission->getWardDataByEmpdetailsId($emp_details_id);
		$data['wardList'] = $this->model_ward_mstr->getWardListOnlyDigitEmpPermitted(['ulb_mstr_id'=>$ulb_mstr_id, 'emp_pemitted_ward_list'=>$empPemittedWardList["ward_mstr_id"]]);
        $safMstrDtl = $safHelper->getSafMstrDtl();
        $data['transferModeList'] = json_decode($safMstrDtl['transfer_mode_mstr'], true);
        $data['ownershipTypeList'] = json_decode($safMstrDtl['ownership_type_mstr'], true);
        $data['propTypeList'] = json_decode($safMstrDtl['prop_type_mstr'], true);
        $data['roadTypeList'] = json_decode($safMstrDtl['road_type_mstr'], true);
        $data['floorList'] = json_decode($safMstrDtl['floor_mstr'], true);
        $data['usageTypeList'] = json_decode($safMstrDtl['usage_type_mstr'], true);
        $data['occupancyTypeList'] = json_decode($safMstrDtl['occupancy_type_mstr'], true);
        $data['constTypeList'] = json_decode($safMstrDtl['const_type_mstr'], true);
		$data['apartmentDetailsList'] = json_decode($safMstrDtl['apartment_dtl'], true);

        if ($this->request->getMethod()=='post') {
			try {
				$inputs = arrFilterSanitizeString($this->request->getVar());
				if (!is_null($param)) {
					$paramExplode = explode("::", $param);
					$inputs["assessmentType"] = hashDecrypt($paramExplode[1]);
					$data["assessmentType"] = $inputs["assessmentType"];
					if($inputs["assessmentType"] == 'New-Assessment'){
						$inputs["land_occupation_date"] = '2016-04-01';
					}else{
						$inputs["land_occupation_date"] = $inputs["land_occupation_date"];
					}
				}
				
				if ($inputs["road_type_width"]==0) {
					$inputs["road_type_mstr_id"] = 4;
				} else if ($inputs["road_type_width"]>=40) {
					$inputs['road_type_mstr_id'] = 1;
				} else if ($inputs["road_type_width"]>=20 && $inputs["road_type_width"]<=39) {
					$inputs['road_type_mstr_id'] = 2;
				} else if ($inputs["road_type_width"]<20) {
					$inputs['road_type_mstr_id'] = 3;
				}
				$data["road_type_mstr_id"] = $inputs["road_type_mstr_id"];

                $data["newWardList"] = $safHelper->getNewWardByOldWardID($inputs["ward_mstr_id"]);
				if ($inputs["zone_mstr_id"]=="") {
					$inputs["zone_mstr_id"]=1;
				}
				//print_var($data["newWardList"]);
				if ($inputs["prop_type_mstr_id"]==3) {
					if ($apartmentDtl =$this->model_apartment_details->getApartmentDtlById($inputs["apartment_details_id"])) {
						$data["road_type_mstr_id"] = $inputs["road_type_mstr_id"] = $apartmentDtl["road_type_mstr_id"];
						if ($apartmentDtl["water_harvesting_status"]=='t' || $apartmentDtl["water_harvesting_status"]==true || $apartmentDtl["water_harvesting_status"]==1) {
							$data["is_water_harvesting"] = $inputs["is_water_harvesting"] = true;
						} else {
								$data["is_water_harvesting"] = $inputs["is_water_harvesting"] = false;
						}
					}
				}
                if (isset($_POST['btn_back'])) {
                    return view('mobile/saf/saf_add_update', $data);
                } else if(isset($_POST['btn_review'])) {
                    if ($inputs['prop_type_mstr_id']==4) { // vacant land cal
                        if ($inputs['road_type_mstr_id']!=4) {
							$vacantDtlArr = $safHelper->makeVacantFloorDtlArr($inputs);
						} else {
							$vacantDtlArr = $safHelper->makeVacantWithNoRoadFloorDtlArr($inputs);
						}

                        list($safTaxDtl, $new_rule_sub, $cv_rule_sub, $cv_vacant_land_dtl) = $safHelper->calVacantTaxDtl($vacantDtlArr);

                        $data['new_rule_sub'] = $new_rule_sub;
                        $data['cv_rule_sub'] = $cv_rule_sub;
                        $data['safTaxDtl'] = $safTaxDtl;
                        $data['cv_vacant_land_dtl'][0] = $cv_vacant_land_dtl;
						//print_var($data['cv_vacant_land_dtl']);
                    } else { // building cale
                        $floorDtlArr = $safHelper->makeBuildingFloorDtlArr($inputs);
                        $data['floorDtlArr'] = $floorDtlArr;

                        $isAdditionaTaxImplemented = $safHelper->checkIsAdditionaTaxImplemented($inputs["is_water_harvesting"], $inputs["prop_type_mstr_id"], $inputs["area_of_plot"]);

                        list($safTaxDtl, $old_rule_arv_sub, $new_rule_arv_sub, $cv_rule_arv_sub) = $safHelper->calBuildingTaxDtl($floorDtlArr, $inputs['prop_type_mstr_id'], $isAdditionaTaxImplemented);
                        $data['safTaxDtl'] = $safTaxDtl;
                        $data["old_rule_arv_sub"] = $old_rule_arv_sub;
                        $data["new_rule_arv_sub"] = $new_rule_arv_sub;
                        $data["cv_rule_arv_sub"] = $cv_rule_arv_sub;
                    }
                    return view('mobile/Saf/saf_add_update_review', $data);
                } else if(isset($_POST['btn_submit'])) {
                    $inputs["emp_details_id"] = $emp_details_id;
					if ($inputs["zone_mstr_id"]=="") {
						$inputs["zone_mstr_id"]==1;
					}
                    if ($inputs['prop_type_mstr_id']==4) { // vacant land cal
                        $this->db->transBegin();
						$this->dbSystem->transBegin();
							if ($inputs['road_type_mstr_id']!=4) {
								$vacantDtlArr = $safHelper->makeVacantFloorDtlArr($inputs);
							} else {
								$vacantDtlArr = $safHelper->makeVacantWithNoRoadFloorDtlArr($inputs);
							}
                            list($safTaxDtl, $new_rule_sub, $cv_rule_sub, $cv_vacant_land_dtl) = $safHelper->calVacantTaxDtl($vacantDtlArr);
                            $saf_dtl_id = $safHelper->saveSafData($inputs);
                            $safHelper->calSafDemand($safTaxDtl, $saf_dtl_id, $inputs["prev_prop_dtl_id"]);

							$application = $this->model_saf_dtl->SafDetailsById($saf_dtl_id);							
							$vistingRepostInput = safAplyVisit($application,$this->request->getVar());           
							$visiting_dtl_id = $this->model_visiting_dtl->insertdetail($vistingRepostInput);
                        if ($this->db->transStatus() === false) {
                            $this->db->transRollback();
							$this->dbSystem->transRollback();
                        } else {
                            $this->db->transCommit();
							$this->dbSystem->transCommit();
							$LINK = base_url('mobisafDemandPayment/saf_property_details/'.md5($saf_dtl_id));
						    return redirect()->to($LINK);
                        }
                    } else { // building cal
                        $this->db->transBegin();
						$this->dbSystem->transBegin();
                            $floorDtlArr = $safHelper->makeBuildingFloorDtlArr($inputs);
                            $isAdditionaTaxImplemented = $safHelper->checkIsAdditionaTaxImplemented($inputs["is_water_harvesting"], $inputs["prop_type_mstr_id"], $inputs["area_of_plot"]);
                            list($safTaxDtl, $old_rule_arv_sub, $new_rule_arv_sub, $cv_rule_arv_sub) = $safHelper->calBuildingTaxDtl($floorDtlArr, $inputs['prop_type_mstr_id'], $isAdditionaTaxImplemented);

                            $saf_dtl_id = $safHelper->saveSafData($inputs);
                            $safHelper->calSafDemand($safTaxDtl, $saf_dtl_id, $inputs["prev_prop_dtl_id"]);

							$application = $this->model_saf_dtl->SafDetailsById($saf_dtl_id);							
							$vistingRepostInput = safAplyVisit($application,$this->request->getVar());           
							$visiting_dtl_id = $this->model_visiting_dtl->insertdetail($vistingRepostInput);
                        if ($this->db->transStatus() === false) {
                            $this->db->transRollback();
							$this->dbSystem->transRollback();
                        } else {
                            $this->db->transCommit();
							$this->dbSystem->transCommit();
                            $LINK = base_url('mobisafDemandPayment/saf_property_details/'.md5($saf_dtl_id));
						    return redirect()->to($LINK);
                        }
                    }
                }
            } catch(Exception $e) {
				print_var($e->getTraceAsString());
                print_var($e->getTrace()[0]["args"]);
                echo $e->getMessage()."<br />";
                echo $e->getLine()."<br />";
			}
        } else {
			return view('mobile/saf/saf_add_update', $data);
        }
	}
}
