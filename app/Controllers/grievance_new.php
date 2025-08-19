<?php

namespace App\Controllers;

use App\Models\DbSystem\TblCitizen;
use App\Models\DbSystem\TblOtp;
use App\Models\DbSystem\TblWfMstr;
use App\Models\DbSystem\TblWfRoleMapMstr;
use App\Models\DbSystem\TblWfTrack;
use App\Models\model_ulb_mstr;
use App\Models\model_emp_details;
use App\Models\grievance_details_model;
use App\Models\GrievanceModel;
use App\Models\model_datatable;
use App\Models\PropertyModel;
use App\Models\model_saf_dtl;
use App\Models\model_user_type_mstr;
use App\Models\WaterApplyNewConnectionModel;
use App\Models\model_water_consumer;
use App\Models\model_ward_mstr;
use App\Models\WaterViewPropertyDetailModel;
use DateTime;
use Exception;

class grievance_new extends HomeController
{

	protected $trade_db;
	protected $property_db;
	protected $water_db;
	protected $dbSystem;
	protected $session;

	# db_system
	protected $modelUlb;
	protected $emp_id;
	protected $citizen_id;
	protected $user_type;
	protected $modelemp;
	protected $grievance_details_model;
	protected $grievance_model;
	protected $water_prop_detail_model;
	protected $model_ward_mstr;
	protected $ward_model;
	protected $modelTblWfMstr;
	protected $modelTblWfRoleMapMstr;
    protected $modelTblWfTrack;
    protected $modelUserTypeMaster;
	protected $model_dataTable;
	protected $model_Citizen;
	protected $model_Otp;

	# property
	protected $saf_model;
	protected $property_model;

	# water
	protected $water_conn_model;
	protected $consumer_model;

	public function __construct()
	{

		helper(['db_helper',"utility_helper","php_office_helper", 'form', 'qr_code_generator_helper', 'sms_helper',"cookie"]);
		$this->session = session();
		$get_emp_details = $this->session->get('emp_details') ?? '';
		$this->emp_id = $get_emp_details['id'] ?? '';
		$this->user_type = $get_emp_details['user_type_mstr_id'] ?? '';		
		$citizen = $this->session->get('citizen') ?? [];
		$this->citizen_id = $citizen["id"]??"";
		$baseName = basename(str_replace('\\', '/', get_class($this)));
		$methodName = trim(explode($baseName,current_url())[1]??"","\\\/");
		if(!$get_emp_details && !$citizen && !in_array($methodName,["login","resistorCitizen"])){
			echo view('index');	die;
		}

		parent::__construct();

		if ($db_name = dbConfig("water")) {
			//echo $db_name;
			$this->water_db = db_connect($db_name);
		}
		
		if($db_name = dbConfig("trade")){
            //echo $db_name;
            $this->trade_db = db_connect($db_name);            
        }
        if($db_name = dbConfig("property"))
        {
            $this->property_db = db_connect($db_name);
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        }
        

		//$db_name = db_connect("db_rmc_property"); 

		$this->modelUlb = new model_ulb_mstr($this->dbSystem);
		$this->modelemp = new model_emp_details($this->dbSystem);
		$this->grievance_details_model = new grievance_details_model($this->dbSystem);
		$this->grievance_model = new GrievanceModel($this->dbSystem);
		$this->ward_model = new model_ward_mstr($this->dbSystem);
		$this->modelTblWfMstr  = new TblWfMstr($this->dbSystem);
		$this->modelTblWfRoleMapMstr = new TblWfRoleMapMstr($this->dbSystem);
        $this->modelTblWfTrack = new TblWfTrack($this->dbSystem);
		$this->modelUserTypeMaster = new model_user_type_mstr($this->dbSystem);
		$this->model_dataTable = new model_datatable($this->dbSystem); 
		$this->model_Citizen = new TblCitizen($this->dbSystem);
		$this->model_Otp = new TblOtp($this->dbSystem);

		$this->property_model = new PropertyModel($this->property_db);
		$this->saf_model = new model_saf_dtl($this->property_db);
		$this->water_prop_detail_model=new WaterViewPropertyDetailModel($this->property_db);

		$this->water_conn_model = new WaterApplyNewConnectionModel($this->water_db);
		$this->consumer_model = new model_water_consumer($this->water_db);
	}


	public function __destruct()
	{
		if (!empty($this->db)) $this->db->close();
		if (!empty($this->property_db)) $this->property_db->close();
		if (!empty($this->dbSystem)) $this->dbSystem->close();
	}



	public function resistorCitizen(){
		$data =[];
		try{
			$purpose = "citizen registration";
			if($this->request->getMethod()=="post"){
				$data = $inputs = arrFilterSanitizeString($this->request->getVar());
				if($this->request->getVar("register")){
					$rules = [
						"mobile_no"=>"required|min_length[10]|max_length[10]",
						"full_name"=>"required",
						"otp"=>"required",
					];
					if($this->validate($rules)){
						$testOtp = json_decode($this->verifyOtp($inputs["otp"],$inputs["mobile_no"],$purpose),true);
						if($testOtp["status"]){
							$test = json_decode($this->testMobileNo($inputs["mobile_no"]),true);
							if($test["status"]){
								$input = [
									"phone_no"=>$inputs["mobile_no"],
									"name"=>$inputs["full_name"]
								];
								$id = $this->model_Citizen->store($input);
								$citizen = $this->model_Citizen->where("id",$id)->get()->getFirstRow("array");
								$this->session->set("citizen",$citizen);
								if($this->request->getVar("ajax")){
									$response=[
										"url"=>base_url('grievance_new/welcome'),
										"message"=>"Registration Successfully",
										"response"=>true,
									];
									return json_encode($response);
								}
								return redirect()->to(base_url('grievance_new/welcome'));
							}
							$data["error"]="This Mobile No Already Registered";
						}
						else{
							$data["error"]=$testOtp["error"];
						}
						
					}else{
						$data["validation"]=$this->validator;
						if($this->request->getVar("ajax")){
							$data["validation"]=$this->validator->getErrors();
							return json_encode($data);
						}
					}

					if($this->request->getVar("ajax")){
						$data["status"]=false;
						return json_encode($data);
					}
					return view("grievance_new/citizenRegister",$data);
				}elseif($this->request->getVar("register_otp")){
					$rules = [
						"mobile_no"=>"required|min_length[10]|max_length[10]",
					];
					if($this->validate($rules)){
						$otp =$this->model_Otp->generateOtp();
						$currentDateTime = new DateTime();
						$expireAt = (clone $currentDateTime)->modify('+10 minutes');
						$input=[
							"mobile_no"=>$inputs["mobile_no"],
							"otp"=>$otp,
							"purpose"=>$purpose,
							"valid_upto"=>$expireAt->format("Y-m-d H:i:s"),
						];
						$response = $this->sentOtp($otp,$input["mobile_no"]);
						if ($response["response"] == false) {
							$response["error"]= "Sorry, OTP could not send ".($input["mobile_no"]).". Please try again later";
						}else{
							$this->dbSystem->table($this->model_Otp->table)
							->where("mobile_no",$input["mobile_no"])
							->where("purpose",$input["purpose"])
							->where("consume_on",null)
							->update(["status"=>0]);
							$id = $this->model_Otp->store($input);
							$response["message"]=  "OTP sent on ".$input["mobile_no"]." successfully";				
						}
						return json_encode($response);
					}else{
						$data["validation"]=$this->validator;
					}
				}
			}
			// return view("grievance_new/citizenRegister",$data);
		}catch(Exception $e){
			return redirect()->back()->with('error', 'Server Error');
		}
	}

	public function testMobileNo($mobileNo=""){
		$citizen = $this->model_Citizen->where("phone_no",$mobileNo)->get()->getFirstRow("array");
		
		$response=[
			"status"=>true,
			"error"=>""
		];
		if($citizen){
			$response["status"]=false;
			$response["error"] = "This Mobile No. Is Already Exists";
		}
		return json_encode($response);
	}

	public function login(){
		$data=[];
		try{
			$purpose="Grievance Login";
			if($this->request->getMethod()=="post"){
				$data = $inputs = arrFilterSanitizeString($this->request->getVar());
				if($this->request->getVar("login")){
					$rules = [
					   "mobile_no"=>"required",
					   "otp"=>"required"
				   ];
				   if($this->validate($rules)){
						$testOtp = json_decode($this->verifyOtp($inputs["otp"],$inputs["mobile_no"],$purpose),true);
						if($testOtp["status"]){
							$citizen = $this->model_Citizen->where("phone_no",$inputs["mobile_no"])->get()->getFirstRow("array");							
							if($citizen){							
								$this->session->set('citizen', $citizen);
								if($this->request->getVar("ajax")){
									$response=[
										"url"=>base_url('grievance_new/welcome'),
										"message"=>"login Successfully",
										"response"=>true,
									];
									return json_encode($response);
								}
								return redirect()->to(base_url('grievance_new/welcome'));								
							}else{
								$data['errMsg'] = "invalid Mobile No";
							}					   
						}else{
							$data["errMsg"]=$testOtp["error"];
						}
				   }else{
					   $data["validation"]=$this->validator;
					   if($this->request->getVar("ajax")){
						   $data["validation"]=$this->validator->getErrors();
						   return json_encode($data);
					   }
				   }
				   if($this->request->getVar("ajax")){
						$data["status"]=false;
						return json_encode($data);
					}				   
					return view('grievance/login', $data);
				}elseif($this->request->getVar("send_otp")){
					$rules = [
						"mobile_no"=>"required|min_length[10]|max_length[10]",
					];
					if($this->validate($rules)){
						$otp =$this->model_Otp->generateOtp();
						$currentDateTime = new DateTime();
						$expireAt = (clone $currentDateTime)->modify('+10 minutes');
						$input=[
							"mobile_no"=>$inputs["mobile_no"],
							"otp"=>$otp,
							"purpose"=>$purpose,
							"valid_upto"=>$expireAt->format("Y-m-d H:i:s"),
						];
						$response = $this->sentOtp($otp,$input["mobile_no"]);
						if ($response["response"] == false) {
							$response["error"]= "Sorry, OTP could not send ".($input["mobile_no"]).". Please try again later";
						}else{
							$this->dbSystem->table($this->model_Otp->table)
							->where("mobile_no",$input["mobile_no"])
							->where("purpose",$input["purpose"])
							->where("consume_on",null)
							->update(["status"=>0]);
							$id = $this->model_Otp->store($input);
							$response["message"]=  "OTP sent on ".$input["mobile_no"]." successfully";				
						}
						return json_encode($response);
					}
				}
			}
			return view("grievance/login",$data);
		}catch(Exception $e){
			return redirect()->back()->with('error', 'Server Error');
		}
	}

	public function logOut(){
		if ($this->session->has("citizen")) {
			
			$this->session->remove('citizen');
			$this->session->destroy();
		}
		return redirect()->to(base_url('grievance_new/login'));
	}

	public function verifyOtp($otp=null,$mobileNo=null,$purpose=null,$refType=null,$refId=null){
		$currentDateTime = new DateTime();
		$response = [
			"status"=>true,
			"error"=>""
		];
		$otp = $this->model_Otp->where("otp",$otp)
				->where("mobile_no",$mobileNo)
				->where("purpose",$purpose)
				->where("ref_table",$refType)
				->where("ref_id",$refId)
				->where("status",1)
				->orderBy("id","DESC")
				->get()
				->getFirstRow("array");
		if(!$otp){
			$response["status"]=false;
			$response["error"]="invalid OTP";
		}
		if($otp && $otp["valid_upto"]< $currentDateTime->format("Y-m-d H:i:s")){
			$response["status"]=false;
			$response["error"]="OTP Is Expired";
		}
		if($otp){
			// $this->model_Otp->updateData($otp["id"],["status"=>0,"consume_on"=>$currentDateTime->format("Y-m-d H:i:s")]);
		}
		return json_encode($response);

	}

	private function sentOtp($otp,$mobileNo){
		$message = "Dear Citizen, your OTP for online payment of Holding Tax for $otp is INR 0. This OPT is valid for 10 minutes only.";		
		$templateid = "1307161908198113240";
		return send_sms($mobileNo,$message,$templateid);
	}

	public function welcome(){
		$citizen = $this->session->get("citizen");
		
		$data["citizen"] = $citizen;
		return view("grievance/dashboard",$data);
	}

	// public function index(){
	// 	$data = [];			
	// 	return view('grievance/index', $data);
	// }
	
	public function grievance_insert(){
		$data = [];
		if($this->request->getMethod() == 'post'){ 
			$ulb_city_nm = "RANCHI";
			$inputs = arrFilterSanitizeString($this->request->getVar());
			
			#=====complain Save ==============
			if(isset($_POST['saveGrievance'])){
				$rules = [
					"module_id"=>"required",
					"name"=>"required",
					"phone"=>"required",
					"queries"=>"required",
					'upload_file' => 'uploaded[upload_file]|max_size[upload_file,5120]|ext_in[upload_file,png,jpg,jpeg,pdf]'
				];
				if(in_array($this->request->getVar("module_id"),[1,2,3])){
					$rules["app_no"] = "required";
					$rules["app_id"] = "required";
					$rules["module_id"] = "required";
					$rules["ward_id"] = "required";
					$rules["address"] = "required";
				}
			
				if($this->validate($rules)){
					$doc_path_file = $this->request->getFile('upload_file');
					if($doc_path_file->isValid() && !$doc_path_file->hasMoved()) {						
						try{
							$wf = $this->modelTblWfMstr->getWf($inputs["grievance_type_id"]);                    
							$wf_role_map = $this->modelTblWfRoleMapMstr->getWfMaps($wf["id"]??0);
							$initiator = [];
							$finisher = [];
							foreach($wf_role_map as $val){
								if($val["is_initiator"]=="t"){
									
									array_push($initiator,$val);
								}
								if($val["is_finiser"]=="t"){
									array_push($finisher,$val);
								}
							}
							if(sizeof($finisher)>1){
								usort($finisher, function($a, $b) {
									return $a['sl_no'] <=> $b['sl_no'];
								});
								$finisherT = array_reverse($finisher);
								$f[] = $finisherT[0];
								$finisher =$f; 
								
							}
							if(!$wf){
								throw new Exception("Wf Not Mapped");
							}
							if(sizeof($initiator)==0){
								throw new Exception("Initiator Not Defined");
							}
							if(sizeof($finisher)==0){
								throw new Exception("Finisher Not Defined");
							}

							$input = [
								'grievance_type_id' => $inputs['grievance_type_id'],
								'module_id' => $inputs['module_id']??null,
								'complain_type_id' => $inputs['complain_type_id']??null,
								"name"=>$inputs["name"]??null,
								"mobile_no"=>$inputs["phone"]??null,
								'app_no' => $inputs['app_no']??null,
								'app_id' => $inputs['app_id']??null,
								'app_type' => $inputs['app_type']??null,
								'ward_mstr_id' => $inputs['ward_id']??null,
								"owner_name"=>$inputs["owner_name"]??null,
								"guardian_name"=>$inputs["guardian_name"]??null,
								"address"=> $inputs["address"]??null,
								"queries" => $inputs["queries"]??null,
								"workflow_id"=> $wf["id"], 
								"current_role_id"=> $initiator[0]["role_id"], 
								"initiator_role_id"=> $initiator[0]["role_id"],  
								"finisher_role_id"=> $finisher[0]["role_id"], 
							];	
							if($this->citizen_id){
								$input["citizen_id"]=$this->citizen_id;
							}
							elseif($this->emp_id){
								$input["emp_dtl_id"]=$this->emp_id;
							}

							$this->dbSystem->transBegin();
							
							$grievance_id = $this->grievance_details_model->store($input);
							if(!$grievance_id){
								throw new Exception("Data Not Stored");
							}

							$newFileName = $grievance_id;
							$file_ext = $doc_path_file->getExtension();
							$path = $ulb_city_nm."/"."grievance";

							$doc_path_file->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext);
							$doc_path_file_save = $path."/".$newFileName.'.'.$file_ext;
							$updaterow = $this->grievance_details_model->update_grievance_doc($grievance_id, $doc_path_file_save);
							
							$this->dbSystem->transcommit();
							if($this->request->getVar("ajax")){
								return json_encode([
									"status"=>true,
									"url"=>base_url('grievance_new/viewGrievance/'.$grievance_id),
								]);
								
							}
							return $this->response->redirect(base_url('grievance_new/viewGrievance/'.$grievance_id));

						}catch (Exception $e){
							$this->dbSystem->transRollback();
							log_message('error', $e->getMessage());
							if($this->request->getVar("ajax")){
								return json_encode([
									"status"=>false,
									"error"=>$e->getMessage(),
								]);
								
							}
							return redirect()->back()->with('error', 'An error occurred while processing your request.');

						}

					}else{
						return redirect()->back()->with('error', 'Invalid file upload. Please check your file.');
					}
				}else{
					$data['validation']=$this->validator;
					if($this->request->getVar("ajax")??false){
						$data["status"]=false;
						$data['validation']=$this->validator->getErrors();
						return json_encode($data);
					}					
				}
			}
		}
		return view("grievance/index", $data);
	}

	public function grievance_token_no($id = null)
	{
		$data = array();
		$test = $this->grievance_details_model->select("*")->where("id",$id)->get()->getFirstRow("array");
		if($test){
			$token_no =$test["token_no"];
			$data['token_no'] = $token_no;
			return view('citizen/grievance_menu', $data);
		}else{
			return redirect()->back()->with('error', 'Invalid Id');
		}
		
	}

	public function getAppStatus($id){
		$status = "";
		$app = $this->grievance_details_model->select("*")->where("id",$id)->get()->getFirstRow("array");
		if($app){
			if($app["status"]==5){
				$roleSql = "select tbl_user_type_mstr.*,tbl_emp_details.emp_name
						from tbl_emp_details 
						JOIN tbl_user_type_mstr on tbl_user_type_mstr.id = tbl_emp_details.user_type_mstr_id
						WHERE tbl_emp_details.id = ".$app["closing_by"];
				$role = $this->dbSystem->query($roleSql)->getFirstRow('array');
				$status="Token Is Close On ".$app["closing_on"] ." By ".($role["user_type"]??"");
			}
			elseif($app["status"]==4){
				$roleSql = "select *
						from tbl_user_type_mstr
						WHERE tbl_user_type_mstr.id = ".$app["current_role_id"];
				$status="Token Is Rejected By ".($role["user_type"]??"");
			}else{
				$roleSql = "select *
						from tbl_user_type_mstr
						WHERE tbl_user_type_mstr.id = ".$app["current_role_id"];
				$role = $this->dbSystem->query($roleSql)->getFirstRow('array');
				$status="Token Is Pending At ".($role["user_type"]??"");
			}
		}
		return $status;
	}

	public function getGrievanceDtl($token_no_parm=null){
		if($token_no_parm){
			$token_no = $token_no_parm;
		}else{
			$token_no = $this->request->getVar("token_no");
		}
		$data = $this->grievance_details_model->select("*")->where("token_no",$token_no)->get()->getResultArray();
		if($token_no_parm){
			return $data;
		}
		else{
			$data = array_map(function($item){
				$item["app_status"]=$this->getAppStatus($item["id"]);
				if($path = $item["doc_path"]){
					$url = 	base_url("/getImageLink.php?path=$path");				
					$link = '<a target="_blank" href="'.$url.'">
							<img src="'.$url.'" class="img-lg" />
							</a>
					';
					$item["doc_path"] = $link;
				}
				return $item;
			},$data);
			$heading=[
				"id"=>"View",
				"token_no"=>"token no",
				"grievance_type"=>"grievance type",
				"grievance_type"=>"grievance type",
				"app_no" => "reference no",
				"app_type" => "reference type",
				"ward_no"=>"ward no",
				"mobile_no"=>"mobile no",
				"doc_path"=>"Attachment",
				"app_status"=>"Status",
			];
			$response=[
				"response"=>$data?true:false,
				"data"=>$data,
				"heading"=>$heading,
				"message"=>$data?"data Fetched":"data not find"
			];
			return json_encode($response);
		}

	}

	public function applyGrievance(){
		$data = [];
		$data["citizen"]=$this->session->get("citizen");
		$data["wardList"] = $this->ward_model->getWardList(['ulb_mstr_id' => 1]);
		if(!$data["citizen"]){
			return view('grievance/grievanceApplyCounter', $data);
		}
		return view('grievance/grievance_apply', $data);
	}

    public function citizenGrievanceList(){
		$data["from"]="search";
		$citizen = $this->session->get("citizen");
		$citizenId = $citizen["id"]??0;
		$inputs=arrFilterSanitizeString($this->request->getVar());
		$where ='';
		if(isset($inputs['by_holding_owner_dtl']) && $inputs['by_holding_owner_dtl']=='by_application_no' && trim($inputs['keyword'])!='')
		{
			$data['by_holding_owner_dtl'] = $inputs['by_holding_owner_dtl']; 
			$data['keyword'] = $inputs['keyword']; 
			$where = " AND (
							tbl_grievance_details.token_no ilike('%".$data['keyword']."%')
							OR tbl_grievance_details.app_no ilike('%".$data['keyword']."%')
							)";
		}
		elseif(isset($inputs['by_holding_owner_dtl']) && $inputs['by_holding_owner_dtl']=='by_owner' && trim($inputs['keyword'])!='')
		{
			$data['by_holding_owner_dtl'] = $inputs['by_holding_owner_dtl']; 
			$data['keyword'] = $inputs['keyword'];
			$where = " AND (tbl_grievance_details.mobile_no ilike('%".$data['keyword']."%')
							OR tbl_grievance_details.owner_name ilike('%".$data['keyword']."%')                                
						)"; 
		}
		$sql = "select tbl_grievance_details.*,tbl_ward_mstr.ward_no,
				CASE WHEN tbl_grievance_details.module_id=1 THEN 'PROPERTY'
					WHEN tbl_grievance_details.module_id=2 THEN 'WATER'
					WHEN tbl_grievance_details.module_id=3 THEN 'TRADE' 
					ELSE 'OTHER'
				END AS grievance_type
				from tbl_grievance_details
				left join tbl_ward_mstr on tbl_ward_mstr.id = tbl_grievance_details.ward_mstr_id
				where tbl_grievance_details.citizen_id = $citizenId $where
				ORDER BY tbl_grievance_details.id DESC
				";
		$data['posts'] = $this->model_dataTable->getDatatable($sql);
		return view("grievance/citizenGrievanceList",$data);
	}

	public function viewGrievance($id,$action="view"){
		$isCitizen = $this->session->get("citizen")?true:false;
		$data["from"] = $action;
		$app = $this->grievance_details_model->select("*")->where("id",$id)->get()->getFirstRow("array");
		if($app){
			$ward_no= $this->ward_model->select("ward_no")->where("id",$app["ward_mstr_id"])->get()->getFirstRow("array");
			$app["ward_no"] = $ward_no["ward_no"]??"";
			switch($app["module_id"]){
				case 1 : $app["grievance_for"]="PROPERTY";
					break;
				case 2 : $app["grievance_for"]="WATER";
					break;
				case 3 : $app["grievance_for"]="TRADE";
					break;
				default : $app["grievance_for"]="OTHER";
			}
			// dd($app);
			$app["app_status"]=$this->getAppStatus($id);
			$data["app"] = $app;
			$permission = $this->modelTblWfRoleMapMstr->getWfMapPermission($app["workflow_id"],$this->user_type);
			if(in_array($app["status"],[5,4]) && $permission){
                $permission["can_forward"] = "f";
                $permission["can_backward"] = "f";
                $permission["can_verify_doc"] = "f";
                $permission["can_upload_doc"] = "f";
                $permission["can_btc"] = "f";                
                $permission["can_take_payment"] = "f";                                
                $permission["can_edit"] = "f";                                                
                $permission["is_finiser"] = "f";                                                
                $permission["is_initiator"] = "f";                                                
                $permission["backword_role_id"] = null;                                                
                $permission["forward_role_id"] = null;
            } 			
            $data["permission"] = $permission;
            $data["level"] = $this->modelTblWfTrack->getAppRemarks($app["id"],$this->grievance_details_model->table); 
            $data["fullDocUpload"] = true;            
            $data["fullDocVerify"] = true;
			if(!$isCitizen){
				return view("grievance/grievanceDtlCounter",$data);
			}
            return view("grievance/grievanceDtl",$data);
		}
		else{
			return redirect()->back()->with('error', 'Invalid Id');
		}
	}

	public function search(){
        $data =(array)null;
        try{
            $data["from"]="search";
            $data['user_type']=$this->user_type;
            $user_type_nm = $this->modelUserTypeMaster->getdatabyid(MD5($this->user_type));
            $data['user_type_nm'] = $user_type_nm['user_type'];
            
            $inputs=arrFilterSanitizeString($this->request->getVar());
            $where ='';
            if(isset($inputs['by_holding_owner_dtl']) && $inputs['by_holding_owner_dtl']=='by_application_no' && trim($inputs['keyword'])!='')
            {
                $data['by_holding_owner_dtl'] = $inputs['by_holding_owner_dtl']; 
                $data['keyword'] = $inputs['keyword']; 
                $where = " AND (
                                tbl_grievance_details.token_no ilike('%".$data['keyword']."%')
                                OR tbl_grievance_details.app_no ilike('%".$data['keyword']."%')
                                )";
            }
            elseif(isset($inputs['by_holding_owner_dtl']) && $inputs['by_holding_owner_dtl']=='by_owner' && trim($inputs['keyword'])!='')
            {
                $data['by_holding_owner_dtl'] = $inputs['by_holding_owner_dtl']; 
                $data['keyword'] = $inputs['keyword'];
                $where = " AND (tbl_grievance_details.mobile_no ilike('%".$data['keyword']."%')
                                OR tbl_grievance_details.owner_name ilike('%".$data['keyword']."%')                                
                            )"; 
            }
            $sql="SELECT tbl_grievance_details.*,complain_type_master.complain_type,tbl_ward_mstr.ward_no,
					CASE WHEN tbl_grievance_details.module_id=1 THEN 'PROPERTY'
						WHEN tbl_grievance_details.module_id=2 THEN 'WATER'
						WHEN tbl_grievance_details.module_id=3 THEN 'TRADE' 
						ELSE 'OTHER'
					END AS grievance_type
                FROM tbl_grievance_details
				left join tbl_ward_mstr on tbl_ward_mstr.id = tbl_grievance_details.ward_mstr_id
				LEFT JOIN complain_type_master ON complain_type_master.id = tbl_grievance_details.complain_type_id
                WHERE 1=1 AND tbl_grievance_details.status NOT IN(0) 
                    $where
                ";
            $data['posts'] = $this->model_dataTable->getDatatable($sql);

            return view("grievance/inbox",$data);
        }catch(Exception $e){
            flashToast("message", $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function inbox(){
        $data =(array)null;
        try{
            $data["from"]="inbox";
            $data['user_type']=$this->user_type;
            $user_type_nm = $this->modelUserTypeMaster->getdatabyid(MD5($this->user_type));
            $data['user_type_nm'] = $user_type_nm['user_type'];
            
            $inputs=arrFilterSanitizeString($this->request->getVar());                     
            
            $where ='';
            if(isset($inputs['by_holding_owner_dtl']) && $inputs['by_holding_owner_dtl']=='by_application_no' && trim($inputs['keyword'])!='')
            {
                $data['by_holding_owner_dtl'] = $inputs['by_holding_owner_dtl']; 
                $data['keyword'] = $inputs['keyword']; 
                $where = " AND (
                                tbl_grievance_details.token_no ilike('%".$data['keyword']."%')
                                OR tbl_grievance_details.app_no ilike('%".$data['keyword']."%')
                                )";
            }
            elseif(isset($inputs['by_holding_owner_dtl']) && $inputs['by_holding_owner_dtl']=='by_owner' && trim($inputs['keyword'])!='')
            {
                $data['by_holding_owner_dtl'] = $inputs['by_holding_owner_dtl']; 
                $data['keyword'] = $inputs['keyword'];
                $where = " AND (tbl_grievance_details.mobile_no ilike('%".$data['keyword']."%')
                                OR tbl_grievance_details.owner_name ilike('%".$data['keyword']."%')                                
                            )"; 
            }
            $sql="SELECT tbl_grievance_details.*,complain_type_master.complain_type,tbl_ward_mstr.ward_no,
					CASE WHEN tbl_grievance_details.module_id=1 THEN 'PROPERTY'
						WHEN tbl_grievance_details.module_id=2 THEN 'WATER'
						WHEN tbl_grievance_details.module_id=3 THEN 'TRADE' 
						ELSE 'OTHER'
					END AS grievance_type
                FROM tbl_grievance_details
				left join tbl_ward_mstr on tbl_ward_mstr.id = tbl_grievance_details.ward_mstr_id
				LEFT JOIN complain_type_master ON complain_type_master.id = tbl_grievance_details.complain_type_id
                WHERE 1=1 ".($this->user_type !=1 ? "AND tbl_grievance_details.current_role_id = ".$this->user_type : "")." 
                    AND tbl_grievance_details.status NOT IN(4,5,0) 
                    $where
                ";
			$data['posts'] = $this->model_dataTable->getDatatable($sql);

			return view("grievance/inbox",$data);
        }catch(Exception $e){
            flashToast("message", $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function outBox(){
        $data =(array)null;
        try{
			$data['user_type']=$this->user_type;
            $user_type_nm = $this->modelUserTypeMaster->getdatabyid(MD5($this->user_type));
            $data['user_type_nm'] = $user_type_nm['user_type'];
            
            $inputs=arrFilterSanitizeString($this->request->getVar());                     
            
            $where ='';
            if(isset($inputs['by_holding_owner_dtl']) && $inputs['by_holding_owner_dtl']=='by_application_no' && trim($inputs['keyword'])!='')
            {
                $data['by_holding_owner_dtl'] = $inputs['by_holding_owner_dtl']; 
                $data['keyword'] = $inputs['keyword']; 
                $where = " AND (
                                tbl_grievance_details.token_no ilike('%".$data['keyword']."%')
                                OR tbl_grievance_details.app_no ilike('%".$data['keyword']."%')
                                )";
            }
            elseif(isset($inputs['by_holding_owner_dtl']) && $inputs['by_holding_owner_dtl']=='by_owner' && trim($inputs['keyword'])!='')
            {
                $data['by_holding_owner_dtl'] = $inputs['by_holding_owner_dtl']; 
                $data['keyword'] = $inputs['keyword'];
                $where = " AND (tbl_grievance_details.mobile_no ilike('%".$data['keyword']."%')
                                OR tbl_grievance_details.owner_name ilike('%".$data['keyword']."%')                                
                            )"; 
            }

            $sql="SELECT tbl_grievance_details.*,complain_type_master.complain_type,tbl_ward_mstr.ward_no,
					CASE WHEN tbl_grievance_details.module_id=1 THEN 'PROPERTY'
						WHEN tbl_grievance_details.module_id=2 THEN 'WATER'
						WHEN tbl_grievance_details.module_id=3 THEN 'TRADE' 
						ELSE 'OTHER'
					END AS grievance_type
                FROM tbl_grievance_details
				left join tbl_ward_mstr on tbl_ward_mstr.id = tbl_grievance_details.ward_mstr_id
				LEFT JOIN complain_type_master ON complain_type_master.id = tbl_grievance_details.complain_type_id
                WHERE 1=1 ".($this->user_type !=1 ? "AND tbl_grievance_details.current_role_id != ".$this->user_type : "")." 
                    AND tbl_grievance_details.status NOT IN(4,5,0) 
                    $where
                ";
			$data['posts'] = $this->model_dataTable->getDatatable($sql);

			return view("grievance/inbox",$data);
        }catch(Exception $e){
            flashToast("message", $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


	public function postNextLevel($id){
        try{
            $request_dtl = $this->grievance_details_model->where("md5(id::text)",$id)->get()->getFirstRow("array");
			
            if(!$request_dtl){
                throw new Exception("Data Not Find");
            }
            $currentUserType = $this->user_type;
            if((int)$this->user_type==1){
                $currentUserType = $request_dtl["current_role_id"];
            }
            $forwardBackwordRole = $this->modelTblWfRoleMapMstr->getWfMapPermission($request_dtl["workflow_id"],$currentUserType);
            $getInitiatorRole = $this->modelTblWfRoleMapMstr->getInitiatorRole($request_dtl["workflow_id"]);  
            $permission = $this->modelTblWfRoleMapMstr->getWfMapPermission($request_dtl["workflow_id"],$this->user_type);          
            $inputs=arrFilterSanitizeString($this->request->getVar());
            if($this->request->getMethod()=="post"){               
                if($request_dtl["current_role_id"]!=$currentUserType){
                    throw new Exception("This Application is not pending at you");
                }elseif($request_dtl["current_role_id"]!=$currentUserType){
                    throw new Exception("This Application is not pending at you");
                }
                if($request_dtl["pending_status"]==5){
                    throw new Exception("This Application is already approved");
                }
                if(!$forwardBackwordRole){
                    throw new Exception($inputs["action_btn"]." Role Not Found");
                }
                $is_parked =false;
                $reciver_role_id = $forwardRole = $forwardBackwordRole["forward_role_id"];
                $backwardRole = $forwardBackwordRole["backword_role_id"];
                if(($getInitiatorRole["role_id"]??0)==$backwardRole && $inputs["action_btn"]=="Backward"){
                    $inputs["action_btn"] ="Back To Citizen";
                }
                if(($request_dtl["finiser_role_id"]??0)==$this->user_type && $inputs["action_btn"]=="Forward"){
                    $inputs["action_btn"] ="Approved";
                }
                if(in_array($inputs["action_btn"],["Backward"])){
                    $reciver_role_id =$backwardRole;
                }
                if(in_array($inputs["action_btn"],["Back To Citizen"])){
                    $reciver_role_id =$request_dtl["initiater_role_id"];
                    $is_parked =true;
                }
                if((!in_array($inputs["action_btn"],["Back To Citizen","Approved"])) && !$reciver_role_id){
                    throw new Exception("Forward Role Not Found");
                }
                if($permission["is_finiser"]=='f' && $inputs["action_btn"]=='Approved'){
                    throw new Exception("You cannot approved the application");
                }
                if($permission["can_reject"]=='f' && $inputs["action_btn"]=='Reject'){
                    throw new Exception("You cannot reject the application");
                }

                $lastRemarks = $this->modelTblWfTrack->getLasRemarks($request_dtl["id"],$this->grievance_details_model->table);
                $verification_status = $inputs["action_btn"]=="Forward"? 1:( $inputs["action_btn"]=="Backward"?1:( $inputs["action_btn"]=="Back To Citizen"?2:( $inputs["action_btn"]=="Reject"?4:( $inputs["action_btn"]=="Approved"?5:1))));                
                $updatePrivRemark = [
                    "reciver_user_id"     => $this->emp_id,
                    "verification_status" => $verification_status,
                ];
                $insertRemarks =[
                    "ref_type"       => $this->grievance_details_model->table,
                    "ref_value"      => $request_dtl["id"],
                    "sender_role_id" => $currentUserType,
                    "sender_user_id" => $this->emp_id,
                    "reciver_role_id" => $reciver_role_id,
                    "remarks" => $inputs["level_remarks"],
                ];
                $app_update =[
                    "status"    => $verification_status,                    
                ];
                if(!in_array($inputs["action_btn"],['Reject',"Approved"])){
                    $app_update["current_role_id"]= $reciver_role_id;
                }
                if($request_dtl["is_parked"]=='t'){
                    $app_update["current_role_id"] = $request_dtl["current_role_id"]!=$request_dtl["initiater_role_id"]?$request_dtl["current_role_id"]:$reciver_role_id;
                    $insertRemarks["reciver_role_id"] = $app_update["current_role_id"];
                }
                if($is_parked){
                    $app_update["current_role_id"] = $request_dtl["current_role_id"];
                }
                
                $this->dbSystem->transBegin();

                if(in_array($inputs["action_btn"],['Reject',"Approved"])){
                    $this->approvedReject($id);
                }
                $this->grievance_details_model->updateData($request_dtl["id"],$app_update);
                $this->modelTblWfTrack->updateData($lastRemarks["id"]??0,$updatePrivRemark);
                $this->modelTblWfTrack->store($insertRemarks);
                
                if($this->dbSystem->transStatus() === FALSE)
                {
                    throw new Exception("Something went wrong");
                }
                $this->dbSystem->transCommit();
                return $this->response->redirect(base_url("grievance_new/".$inputs["views"]??"outBox"));     

            }
            
        }catch(Exception $e){
            $this->dbSystem->transRollback();
            flashToast("message", $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

	private function approvedReject($id){
        $request_dtl = $this->grievance_details_model->where("MD5(id::TEXT)",$id)->get()->getFirstRow("array");
        $consumer_id = $request_dtl["consumer_id"];
        $inputs=arrFilterSanitizeString($this->request->getVar());
        
        $app_update["closing_on"]=date("Y-m-d H:i:s");
        $app_update["closing_by"]=$this->emp_id;  
        if(in_array($inputs["action_btn"],['Forward',"Approved"])){
            $app_update["status"] = 5; 
        }
        if(in_array($inputs["action_btn"],['Reject'])){
            $app_update["status"] = 4; 
        }        
        $this->grievance_details_model->updateData($request_dtl["id"],$app_update);
    }

	public function getComplainType($args=null){
		if($args!=null || $this->request->getMethod()=="post")
		{
			if($args!=null)
				$inputs=$args;
			else
				$inputs = arrFilterSanitizeString($this->request->getVar());
			$module=$inputs['module_id'];
			$sql = "SELECT *
					FROM complain_type_master
					WHERE status = 1 AND module_id = $module
					ORDER BY complain_type ASC
			";
			$data = $this->dbSystem->query($sql)
					->getResultArray();
			if($data)
			{
				$option = "";
				foreach($data as $val){
					$option .= "<option value='".$val['id']."'>".$val["complain_type"]."</option>";
				}
				$select= "<select name='complain_type_id' id ='complain_type_id' class='form-control'>
							<option value=''>Select</option>
							".$option."
						</select>
					";
				$response = ['response'=>true,'dd'=> $args ? $data : $select];
			}
			else
			{
				$response = ['response'=>false,'dd'=> ['message'=>'Complain List Not Found']];
			}
		}
		else
		{
			$response = ['response'=>false,'dd'=> ['message'=>'Argument is not proper']];
		} 
		if($args!=null)
			return $response;  
		
		return json_encode($response);
	}

	public function validateAppNO($args=null){
		$response = ['status'=>false, 'error'=>''];
		if($args!=null || $this->request->getMethod()=="post"){
			if($args!=null){
				$moduleId=$args["module_id"]??null;
				$appNo=$args["app_no"]??null;
	
			}
			else
			{
				$inputs = arrFilterSanitizeString($this->request->getVar()); 
				$moduleId=$inputs["module_id"]??null;
				$appNo=$inputs["app_no"]??null;
			}
		}
		if($moduleId==1){
			$sql = "SELECT tbl_prop_dtl.*,view_ward_mstr.ward_no, prop_address as address,
						tbl_prop_owner_detail.owner_name,
						tbl_prop_owner_detail.guardian_name AS guardian_name ,
						tbl_prop_owner_detail.mobile_no,
						'Holding' AS app_type
					FROM tbl_prop_dtl
					JOIN view_ward_mstr ON view_ward_mstr.id = tbl_prop_dtl.ward_mstr_id
					LEFT JOIN tbl_prop_owner_detail ON tbl_prop_owner_detail.prop_dtl_id = tbl_prop_dtl.id AND tbl_prop_owner_detail.status =1
					WHERE tbl_prop_dtl.new_holding_no='".$appNo."' AND tbl_prop_dtl.status = 1
					ORDER BY tbl_prop_owner_detail.id ASC			
			";
			$data = $this->property_db->query($sql)
					->getResultArray();
			if(!$data){
				$sql = "SELECT tbl_prop_dtl.*,prop_address as address,
							view_ward_mstr.ward_no,
							tbl_prop_owner_detail.owner_name,
							tbl_prop_owner_detail.guardian_name AS guardian_name ,
							tbl_prop_owner_detail.mobile_no,
							'Holding' AS app_type
						FROM tbl_prop_dtl
						JOIN view_ward_mstr ON view_ward_mstr.id = tbl_prop_dtl.ward_mstr_id
						LEFT JOIN tbl_prop_owner_detail ON tbl_prop_owner_detail.prop_dtl_id = tbl_prop_dtl.id AND tbl_prop_owner_detail.status =1
						WHERE tbl_prop_dtl.holding_no='".$appNo."' AND tbl_prop_dtl.status = 1
						ORDER BY tbl_prop_owner_detail.id ASC			
				";
				$data = $this->property_db->query($sql)
						->getResultArray();
			}
			if(!$data){
				$sql = "SELECT tbl_saf_dtl.*,prop_address as address,
							view_ward_mstr.ward_no,
							tbl_saf_owner_detail.owner_name,
							tbl_saf_owner_detail.guardian_name AS guardian_name ,
							tbl_saf_owner_detail.mobile_no,
							'SAF' AS app_type
						FROM tbl_saf_dtl
						JOIN view_ward_mstr ON view_ward_mstr.id = tbl_saf_dtl.ward_mstr_id
						LEFT JOIN tbl_saf_owner_detail ON tbl_saf_owner_detail.saf_dtl_id = tbl_saf_dtl.id AND tbl_saf_owner_detail.status =1
						WHERE tbl_saf_dtl.saf_no='".$appNo."' AND tbl_saf_dtl.status = 1
						ORDER BY tbl_saf_owner_detail.id ASC
				";
				$data = $this->property_db->query($sql)
						->getResultArray();
			}
			if($data)
			{
				$response = ['status'=>true, 'data'=>$data];
			}			
			else
			{
				$response = ['status'=>false, 'error'=>'Holding / SAF No not found'];
			}
		}elseif($moduleId==2){
			$sql = "SELECT tbl_consumer.*, 
						view_ward_mstr.ward_no,
						tbl_consumer_details.applicant_name AS owner_name,
						tbl_consumer_details.father_name AS guardian_name ,
						tbl_consumer_details.mobile_no,
						'Consumer' AS app_type
					FROM tbl_consumer
					JOIN view_ward_mstr ON view_ward_mstr.id = tbl_consumer.ward_mstr_id
					LEFT JOIN tbl_consumer_details ON tbl_consumer_details.consumer_id = tbl_consumer.id 
						AND tbl_consumer_details.status =1
					WHERE tbl_consumer.consumer_no='".$appNo."' AND tbl_consumer.status = 1
					ORDER BY tbl_consumer_details.id ASC			
			";
			$data = $this->water_db->query($sql)
					->getResultArray();
			if(!$data){
				$sql = "SELECT tbl_apply_water_connection.*, tbl_apply_water_connection.ward_id AS ward_mstr_id, 
							view_ward_mstr.ward_no,
							tbl_applicant_details.applicant_name  AS owner_name,
							tbl_applicant_details.father_name AS guardian_name ,
							tbl_applicant_details.mobile_no,
							'Application' AS app_type
						FROM tbl_apply_water_connection
						JOIN view_ward_mstr ON view_ward_mstr.id = tbl_apply_water_connection.ward_id
						LEFT JOIN tbl_applicant_details ON tbl_applicant_details.apply_connection_id = tbl_apply_water_connection.id AND tbl_applicant_details.status =1
						WHERE tbl_apply_water_connection.application_no='".$appNo."' AND tbl_apply_water_connection.status = 1
						ORDER BY tbl_applicant_details.id ASC
				";
				$data = $this->water_db->query($sql)
						->getResultArray();
			}
			if($data)
			{
				$response = ['status'=>true, 'data'=>$data];
			}			
			else
			{
				$response = ['status'=>false, 'error'=>'Consumer / Application No not found'];
			}
		}elseif($moduleId==3){
			$sql = "SELECT tbl_apply_licence.*,
							view_ward_mstr.ward_no,
							tbl_firm_owner_name.owner_name,
							tbl_firm_owner_name.guardian_name AS guardian_name ,
							tbl_firm_owner_name.mobile AS mobile_no,
							'Application' AS app_type
						FROM tbl_apply_licence
						JOIN view_ward_mstr ON view_ward_mstr.id = tbl_apply_licence.ward_mstr_id
						LEFT JOIN tbl_firm_owner_name ON tbl_firm_owner_name.apply_licence_id = tbl_apply_licence.id AND tbl_firm_owner_name.status =1
						WHERE tbl_apply_licence.application_no='".$appNo."' AND tbl_apply_licence.status = 1 AND tbl_apply_licence.update_status =0
						ORDER BY tbl_firm_owner_name.id ASC
				";
			$data = $this->trade_db->query($sql)
					->getResultArray();
			if(!$data){
				$sql = "SELECT tbl_apply_licence.*,
							view_ward_mstr.ward_no,
							tbl_firm_owner_name.owner_name,
							tbl_firm_owner_name.guardian_name AS guardian_name ,
							tbl_firm_owner_name.mobile AS mobile_no,
							'License' AS app_type
						FROM tbl_apply_licence
						JOIN view_ward_mstr ON view_ward_mstr.id = tbl_apply_licence.ward_mstr_id
						LEFT JOIN tbl_firm_owner_name ON tbl_firm_owner_name.apply_licence_id = tbl_apply_licence.id AND tbl_firm_owner_name.status =1
						WHERE tbl_apply_licence.license_no='".$appNo."' AND tbl_apply_licence.status = 1 AND tbl_apply_licence.update_status =0
						ORDER BY tbl_firm_owner_name.id ASC
				";
				$data = $this->trade_db->query($sql)
						->getResultArray();
			}
			if($data)
			{
				$response = ['status'=>true, 'data'=>$data];
			}			
			else
			{
				$response = ['status'=>false, 'error'=>'Application No not found'];
			}
			
		}
		$response["sql"]=$sql;
		return json_encode($response);
	}
	

	#=========================reports=============================

	public function levelWisePendingClose(){
		$data = $this->request->getVar();
		$data["wardList"] = $this->ward_model->getWardList(['ulb_mstr_id' => 1]);
		$fromDate = $uptoDate = null;
		if($this->request->getVar("fromDate") && $this->request->getVar("uptoDate")){
			$fromDate = $this->request->getVar("fromDate");
			$uptoDate = $this->request->getVar("uptoDate");
		}
		$where =" "	;
		if($this->request->getVar("status")){
			$where.=" AND tbl_grievance_details.status =".$this->request->getVar("status");
		}
		if($this->request->getVar("wardId")){
			$where.=" AND tbl_grievance_details.ward_mstr_id =".$this->request->getVar("wardId");
		}
		if($this->request->getVar("moduleId")){
			$where.=" AND tbl_grievance_details.module_id =".$this->request->getVar("moduleId");
		}
		if($this->request->getVar("applyFrom")){
			if($this->request->getVar("applyFrom")=="1"){
				$where.=" AND tbl_grievance_details.citizen_id IS NOT NULL";
			}else{
				$where.=" AND tbl_grievance_details.emp_dtl_id IS NOT NULL";
			}
		}		
		$sql = "
			with roles as (
				select role_id,user_type,tbl_wf_role_map_mstr.sl_no
				from tbl_wf_role_map_mstr
				join tbl_user_type_mstr on tbl_user_type_mstr.id = tbl_wf_role_map_mstr.role_id
				where tbl_wf_role_map_mstr.status =1 and(forward_role_id is not null or backword_role_id is not null)
					and tbl_wf_role_map_mstr.tbl_wf_mstr_id in (
						select distinct workflow_id
						from tbl_grievance_details
					)
			),
			grievance as (
				select tbl_grievance_details.id,tbl_grievance_details.current_role_id, 
					tbl_grievance_details.closing_on::date,
					case when tbl_wf_tracks.id is null then created_on::date
						else tbl_wf_tracks.forward_date 
					end as receiving_date,
					ROW_NUMBER() OVER (PARTITION BY tbl_grievance_details.id order by tbl_wf_tracks.id DESC )  as row_num
				from tbl_grievance_details
				left join tbl_wf_tracks on tbl_grievance_details.id = tbl_wf_tracks.ref_value
					and tbl_wf_tracks.status =1 and tbl_wf_tracks.reciver_role_id = tbl_grievance_details.current_role_id 
					and tbl_wf_tracks.ref_type='tbl_grievance_details'
				where 1=1 $where
			)
			select  roles.role_id,roles.user_type,sl_no,count( distinct(grievance.id))
			from roles
			left join grievance on grievance.current_role_id = roles.role_id and grievance.row_num=1
				".($fromDate && $uptoDate ? " AND receiving_date BETWEEN '$fromDate' AND '$uptoDate'" :"")."
			group by roles.role_id,roles.user_type,sl_no
		";

		if($this->request->getMethod()=="post"){
			$data["result"] = $this->dbSystem->query($sql)->getResultArray();
			$headers =[];
			usort($data["result"], function($a, $b) {
				return $a['sl_no'] <=> $b['sl_no'];
			});
			foreach($data["result"] as $val){
				array_push($headers,$val["user_type"]);
			}
			$data["header"] = $headers;
		}
		return view("grievance/grievance_level_wise_reports",$data);

	}

	public function appliedGrievanceReport($ajax=null){
		$data = $this->request->getVar();
		$data["wardList"] = $this->ward_model->getWardList(['ulb_mstr_id' => 1]);
		$fromDate = $uptoDate = null;
				
		
		if($this->request->getMethod()=="post" || ($ajax && $this->request->getMethod()=="get") ){
			$where =" "	;
			if($this->request->getVar("fromDate") && $this->request->getVar("uptoDate")){
				$fromDate = $this->request->getVar("fromDate");
				$uptoDate = $this->request->getVar("uptoDate");
				$where.=" AND tbl_grievance_details.created_on::DATE BETWEEN '$fromDate' AND '$uptoDate'" ;
			}
			else{
				$where.=" AND tbl_grievance_details.created_on::DATE = CURRENT_DATE" ;
			}
			if($this->request->getVar("status")){
				$where.=" AND tbl_grievance_details.status =".$this->request->getVar("status");
			}
			if($this->request->getVar("wardId")){
				$where.=" AND tbl_grievance_details.ward_mstr_id =".$this->request->getVar("wardId");
			}
			if($this->request->getVar("moduleId")){
				$where.=" AND tbl_grievance_details.module_id =".$this->request->getVar("moduleId");
			}
			if($this->request->getVar("applyFrom")){
				if($this->request->getVar("applyFrom")=="1"){
					$where.=" AND tbl_grievance_details.citizen_id IS NOT NULL";
				}else{
					$where.=" AND tbl_grievance_details.emp_dtl_id IS NOT NULL";
				}
			}

			$start = sanitizeString($this->request->getVar('start'));                
            $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page

            $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
			$columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
            if ($columnName=="token_no" )
                $columnName='token_no';
            else if ($columnName=="app_no")
                $columnName = 'app_no';
            else 
                $columnName = 'app_type'; 

            $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']);
			$orderBY = " ORDER BY ".$columnName." ".$columnSortOrder;
            $limit = " LIMIT ".($rowperpage==-1?"ALL":$rowperpage)." OFFSET ".$start; 
			
			
			$searchValue = sanitizeString($this->request->getVar('search')['value']);
			$whereQueryWithSearch = "";
            if ($searchValue!='') 
            {
                $whereQueryWithSearch = " AND (token_no ILIKE '%".$searchValue."%'
                                OR app_no ILIKE '%".$searchValue."%'
                                OR app_type ILIKE '%".$searchValue."%'
                                 )";
            }

			$select = "select *,
						case when status in (4,5) then 'close' else 'open' end as token_status,
						case when module_id =1 then 'PROPERTY'
							when module_id =2 then 'WATER'
							when module_id =3 then 'TRADE'
							ELSE 'OTHER'
						END AS module,
					case when citizen_id is not null then 'Citizen' else 'Counter' end as apply_from,
					((case when closing_on is null then current_date else closing_on end)::DATE - created_on::DATE) AS day_difference,
					ROW_NUMBER () OVER (ORDER BY ".$columnName.") AS s_no
			";
			$from = " from tbl_grievance_details
					where status !=0 $where			
			";
			
			$totalRecords = $this->model_dataTable->getTotalRecords($from,false);
            if ($totalRecords>0) 
            {
                
                ## Total number of records with filtering
                $totalRecordwithFilter = $this->model_dataTable->getTotalRecordwithFilter($from.$whereQueryWithSearch,false);
                
                ## Fetch records                
               $fetchSql = $select.$from.$whereQueryWithSearch.$orderBY;
			   if(!$ajax){
				$fetchSql .= $limit;
			   }
                
                $result = $this->model_dataTable->getRecords($fetchSql,false);                
                
                $records = [];
                if ($result) 
                {
                    foreach ($result AS $key=>$tran_dtl) 
                    {
                        $records[] = [
                            's_no'=>$tran_dtl['s_no'],
                            'token_no'=>$tran_dtl['token_no'],
                            'app_no'=>$tran_dtl['app_no'],
                            'app_type'=>$tran_dtl['app_type'],
							"module"=>$tran_dtl["module"],
                            'token_status'=>$ajax ? $tran_dtl['token_status']: ("<span class = 'text-".($tran_dtl['token_status']=='close' ? 'success' :'info')."'>".$tran_dtl['token_status']."</span>"),
                            'apply_from'=>$tran_dtl['apply_from'],
							'created_on'=>$tran_dtl['created_on'],
							'closing_on'=>$tran_dtl['closing_on'],
                            'day_difference'=>$tran_dtl['day_difference'],
                            'queries'=>$tran_dtl['queries']
                            
                        ];
                    }
                }
            } 
            else 
            {
                $totalRecordwithFilter = 0;
                $records = [];
            }
			if($ajax){
				phpOfficeLoad();
				$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
				$activeSheet = $spreadsheet->getActiveSheet();
								$activeSheet->setCellValue('A1', 'Sl No.');
								$activeSheet->setCellValue('B1', 'Token No');
								$activeSheet->setCellValue('C1', 'Ref No');
								$activeSheet->setCellValue('D1', 'Ref Type');
								$activeSheet->setCellValue('E1', 'Module');
								$activeSheet->setCellValue('F1', 'Token Status');
								$activeSheet->setCellValue('G1', 'Apply From');
								$activeSheet->setCellValue('H1', 'Apply Date');
								$activeSheet->setCellValue('I1', 'Closing Date');
								$activeSheet->setCellValue('J1', 'Pending Day');
								$activeSheet->setCellValue('K1', 'Query');


								$activeSheet->fromArray($records, NULL, 'A3');

				$filename = "Grievance".date('Ymd-hisa').".xlsx";
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="'.$filename.'"');
				header('Cache-Control: max-age=0');
				$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
				$writer->save('php://output');
				
			}
			else{
				$response = array(
					"draw" => 0,                
					"recordsTotal" => $totalRecords,                 
					"recordsFiltered" => $totalRecordwithFilter,
					"data" => $records,                
	
				);
				return json_encode($response);
			}

		}
		return view("grievance/grievance_applied_reports",$data);

	}
}
