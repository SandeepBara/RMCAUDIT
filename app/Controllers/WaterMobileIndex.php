<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PostModel;
use App\Models\model_ward_mstr;
use App\Models\model_ulb_mstr;
use App\Models\model_view_water_consumer;
use App\Models\ModelWaterSurvey;
use App\Models\WaterApplyNewConnectionModel;
use App\Models\WaterMeterStatusModel;
use App\Models\WaterMobileModel;
use Exception;

class WaterMobileIndex extends MobiController
{
	protected $db;
	protected $dbSystem;
	protected $db_property;
	
	//protected $db_name;
	protected $model_ward_mstr;
	protected $model_ulb_mstr;
	protected $water_mobile_model;


	public function __construct()
    {
        parent::__construct();
    	helper(['db_helper']);
        if($db_name = dbConfig("water")){
			//echo $db_name;
            $this->db = db_connect($db_name);
        }
        if($db_name = dbConfig("property")){
			//echo $db_name;
            $this->db_property = db_connect($db_name);            
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        } 

        helper(['form']);
		//$db_name = db_connect("db_rmc_property");		
        $this->model = new model_ward_mstr($this->dbSystem);
		$this->modelUlb = new model_ulb_mstr($this->dbSystem);
		$this->water_mobile_model=new WaterMobileModel($this->db);

		
		$this->model_view_water_consumer = new model_view_water_consumer($this->db);
		$this->WaterApplyNewConnectionModel = new WaterApplyNewConnectionModel($this->db);
		$this->meter_status_model=new WaterMeterStatusModel($this->db);
		$this->ModelWaterSurvey = new ModelWaterSurvey($this->db);

    }

	
	/*public function home()
	{ 
		$data =(array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $emp_details_id = $emp_mstr["id"];
        $user_type_mstr_id = $emp_mstr["user_type_mstr_id"];
        $data["user_type_mstr_id"]=$user_type_mstr_id;
		return view('mobile/index',$data);
	}*/
	public function index()
	{	

		$data =(array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $emp_details_id = $emp_mstr["id"];
        $user_type_mstr_id = $emp_mstr["user_type_mstr_id"];
        $data["user_type_mstr_id"]=$user_type_mstr_id;
		return view('mobile/water',$data);

	}
	
	public function search_consumer()
	{
		return $this->response->redirect(base_url('WaterfieldSiteInspection/search_consumer_for_siteInspection/mobile'));
		
		$data=array();
		$Session=session();
		$emp_mstr = $Session->get("emp_details");
        $emp_details_id = $emp_mstr["id"];
        $user_type_mstr_id = $emp_mstr["user_type_mstr_id"];

		if(isset($_POST['search']))
		{
			
			$_SESSION['from_date']=$data['from_date']=$_POST['from_date'];
			$_SESSION['upto_date']=$data['upto_date']=$_POST['upto_date'];
			
		}
		else
		{
			$_SESSION['from_date']=$data['from_date']=date("Y-m-d");	
			$_SESSION['upto_date']=$data['upto_date']=date("Y-m-d");	
			
		}
		$data['user_type_mstr_id']=$user_type_mstr_id;

		$data['consumer_dtls']=$this->water_mobile_model->search_consumer($data);

		//print_r($data);


		return view('mobile/water/search_consumer',$data);

	}

	public function water_reports_menu()
	{
		$Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
		$data['emp_details_id'] = $emp_mstr["id"];
		return view('mobile/water/reports_menu',$data);
	}

	public function WaterSurvey($cosumer_id=null)
	{
		
		$data=array();
		$data["message"]="";
		$Session=session();
		$emp_mstr = $Session->get("emp_details");
        $emp_details_id = $emp_mstr["id"];
        $user_type_mstr_id = $emp_mstr["user_type_mstr_id"];
		$data["ulb_dtl"] = $Session->get("ulb_dtl");
		if($this->request->getMethod() == 'post')
		{
			$inputs = arrFilterSanitizeString($this->request->getVar());
			
			$alphaNumericSpacesDotDash = '/^[a-z0-9 .\-\/\\\n]+$/i';
			$rules=[                   
				
				'consumer_no'=>"required|regex_match[$alphaNumericSpacesDotDash]",
				'consumer_id' =>'required|numeric',
				'holding_map' =>'required|integer',                    
				'connection_type' =>'required|integer',                    
				'bill_status' =>'required|integer',
				// 'address' =>"required|regex_match[$alphaNumericSpacesDotDash]",
				'image_path_container_latitude_text' =>'required|numeric',
				'image_path_container_longitude_text' =>'required|numeric',
				
			];
			if($inputs["disconnection"])
			{
				$rules["application_form"]=[
                    'uploaded[file]',
                    'mime_in[file,image/jpeg,image/jpg]',
                    'max_size[file,5242880]',
                    'ext_in[file,jpg,jpeg]',
                ];
			}
			if($inputs["holding_no"])
			{
				$rules["holding_no"]="required|regex_match[$alphaNumericSpacesDotDash]";
			}
			if($inputs["holding_no"])
			{
				$rules["holding_no"]="regex_match[$alphaNumericSpacesDotDash]";
			}
			if(isset($inputs["bill_not_serve_reason"]) && $inputs["bill_not_serve_reason"])
			{
				$rules["bill_not_serve_reason"]="regex_match[$alphaNumericSpacesDotDash]";
			}
			if(isset($inputs["reason_not_map_prop"]) && $inputs["reason_not_map_prop"])
			{
				$rules["reason_not_map_prop"]="regex_match[$alphaNumericSpacesDotDash]";
			}
			if(!$this->validate($rules))
			{
				
				$data['validation']=$this->validator;
			}
			else{

				$application_form = $this->request->getFile('application_form');
	
				$inputData=[
					"consumer_no"=>$inputs["consumer_no"],
					"consumer_id"=>$inputs["consumer_id"],
					"is_holding_map"=>$inputs["holding_map"],
	
					"reason_not_map_prop"=>$inputs["reason_not_map"],
					"meter_connection_type_id"=>$inputs["connection_type"],
					"meter_no"=>$inputs["meter_no"],
					"supply_duration"=>$inputs["supply_duration"]??null,
					"is_meter_working"=>$inputs["meter_status"],
					"is_apply_disconneciton"=>$inputs["disconnection"]?$inputs["disconnection"]:0,
	
					"bill_served_status"=>$inputs["bill_status"]??0,
	
					"bill_not_serve_reason"=>(($inputs["bill_status"]??0)==0? $inputs["bill_not_serve_reason"] : null),
					"latitude"=>$inputs["image_path_container_latitude_text"]??null,
					"longitude"=>$inputs["image_path_container_longitude_text"]??null,
					"geo_doc" => $inputs["file_path"]??null,
					"emp_dtl_id"=>$emp_details_id,
					
				];
				if($inputs["last_bill_serve_date"]??0)
				{
					$inputData["last_bill_serve_date"]=$inputs["last_bill_serve_date"];
				}
				if($inputs["prop_id"])
				{
									
					$inputData["prop_id"]=$inputs["prop_id"];
					$inputData["holding_no"]=$inputs["holding_no"];
				}
				elseif($inputs["saf_id"])
				{
					$inputData["saf_id"]=$inputs["saf_id"];
					$inputData["saf_no"]=$inputs["holding_no"];
				}
				if(!$this->ModelWaterSurvey->getSurveyDtl($inputs["consumer_id"]))
				{
					$this->db->transBegin();
					$id = $this->ModelWaterSurvey->insertData($inputData);
					
					if($id && $this->db->transStatus() ===TRUE)
					{
						if($inputData["is_apply_disconneciton"])
						{
							$file_ext = $application_form->getExtension();
							$left_dt = date('dmYHis');
							$ltrand = mt_rand();
							$tmp_file_name = md5($left_dt . $ltrand);
							$temp_path = "WaterServey/disconnectionForm";
							$ulb_folder_name = $data["ulb_dtl"]["district"];
							if ($application_form->move(WRITEPATH . 'uploads/' . $ulb_folder_name . '/' . $temp_path . '/', $tmp_file_name . '.' . $file_ext)) 
							{
								$file_name = $ulb_folder_name . '/' . $temp_path . "/" . $tmp_file_name . '.' . $file_ext;                  
								$updeat = ["desconn_document"=>$file_name] ;
								$this->ModelWaterSurvey->updateData($updeat,$id);
							}
						}
						$this->db->transCommit();
						flashToast("message", "Survey Done Successfully");
						$data["message"] = "Survey Done Successfully";
						// return $this->response->redirect(base_url('WaterMobileIndex/WaterSurvey'));
						return $this->response->redirect(base_url('WaterSearchConsumerMobile/search_consumer_survey/survey'));
					}
					$this->db->transRollback();
					flashToast("message", "Some Error Occurs");
					$data["message"] = "Some Error Occurs";
	
				}
				else
				{
					flashToast("message", "Survey this consumer alrady Done");
					$data["message"] = "Survey this consumer alrady Done";
				}
			}
		}
		if($cosumer_id)
		{
			$where = " md5(id::text) = '".$cosumer_id."'";
			$waterConsumer = $this->model_view_water_consumer->waterConsumerLists($where);
			if(!$waterConsumer)
			{
				return $this->response->redirect(base_url('WaterSearchConsumerMobile/search_consumer_survey/survey'));
			}
			$waterConsumer = $waterConsumer[0];
			$data["consumer_no"]=$waterConsumer["consumer_no"];
			$data["consumer_id"]=$waterConsumer["id"];
			$data["readonly"]="readonly";
		}
		return view('mobile/water/WaterSurvey',$data);
	}


	public function uploadGeoTagImg_Ajax()
    {
        $out = ["status" => false, "message" => null];
        if ($this->request->getMethod() == 'post') 
		{
            $Session = Session();
            $data["emp_details"] = $Session->get("emp_details");
            $data["ulb_dtl"] = $Session->get("ulb_dtl");

            $inputs = arrFilterSanitizeString($this->request->getVar());
            $direction_type = $inputs["direction_type"];
            $saf_dtl_id = $inputs["consumer_id"];
            $login_emp_details_id = $data["emp_details"]["id"];
            $latlong = [
                "latitude" => $inputs["latitude"],
                "longitude" => $inputs["longitude"],
            ];

            $ulb_folder_name = $data["ulb_dtl"]["district"];
            $upload_type = 'Field Verification';
            $left_dt = date('dmYHis');
            $ltrand = mt_rand();
            $tmp_file_name = md5($left_dt . $ltrand);
            $temp_path = "WaterServey/GeoTaggin";
            $destination_path = "field_verification";

            $leftfile = $this->request->getFile('file'); 
            $validated = $this->validate([
                'file' => [
                    'uploaded[file]',
                    'mime_in[file,image/jpeg,image/jpg]',
                    'max_size[file,5242880]',
                    'ext_in[file,jpg,jpeg]',
                ],
            ]);
            if ($validated && !$leftfile->hasMoved()) 
			{
                $file_ext = $leftfile->getExtension();
                if ($leftfile->move(WRITEPATH . 'uploads/' . $ulb_folder_name . '/' . $temp_path . '/', $tmp_file_name . '.' . $file_ext)) 
				{
                    $file_path = WRITEPATH . 'uploads/' . $ulb_folder_name . '/' . $temp_path . "/" . $tmp_file_name . '.' . $file_ext;
                    $in = 1;
					$file_name = $ulb_folder_name . '/' . $temp_path . "/" . $tmp_file_name . '.' . $file_ext;                  
                    $out = ["status" => true, "message" => "Image uploaded successfully", 'path' => $file_path, 'insert_id' => $in,"file_path"=>$file_name];
                }
            } 
			elseif (!$validated) 
			{
                $out = ["status" => false, "message" => $leftfile->getSize()];
            }
        }
        echo json_encode($out);
    }
	public function search_consumerAjax()
	{
		try{
			$data = (array)null;
			$inputs = arrFilterSanitizeString($this->request->getVar());
			$where = " consumer_no = '".$inputs["consumer_no"]."'";
			$waterConsumer = $this->model_view_water_consumer->waterConsumerLists($where);
			if(!$waterConsumer)
			{
				throw new Exception("Data Not Found");
			}
			$waterConsumer = $waterConsumer[0];
			$consumer_owners = "SELECT STRING_AGG(applicant_name,',') AS owner_name,
									STRING_AGG(mobile_no::TEXT,',') AS mobile_no 
								FROM tbl_consumer_details
								WHERE consumer_id = ".$waterConsumer["id"]."
									AND status=1
								";
			$consumer_owners = $this->db->query($consumer_owners)->getFirstRow('array');					
			$data["consumer_id"] = $waterConsumer["id"];
			$data["consumer_address"] = $waterConsumer["address"];
			$data["consumer_ward_no"] = $waterConsumer["ward_no"];
			$data["consumer_owners_name"] = $consumer_owners["owner_name"]??"";
			$data["consumer_owners_mobile_no"] = $consumer_owners["mobile_no"]??"";

			$data["holding_no"] = null;
			$data["prop_id"] = null;
			$data["saf_id"] =null;
			$data["saf_no"] = null;
			$data["connection_type"] = null;
			$data["meter_status"] = null;
			$data["meter_no"] = null;

			$data["prop_address"] = "";
			$data["prop_owners_name"] ="";
			$data["prop_owners_mobile_no"] = "";

			$meter_status =  "";
			$connection_type = "";
			$meter_no = "";
			$prop_ward_no="";
			$prop_id="";
			$saf_id = "";
			$holding_no = "";
			$saf_no = "";
			$prop_address="";
			$prop_owneres=(array)null;
			if($waterConsumer['holding_no'])
			{
				$holding_no = $waterConsumer['holding_no'];
				$prop_id = $this->WaterApplyNewConnectionModel->getPropetyIdByNewHolding($holding_no);
				if(!$prop_id)
				{
					$prop_id = $this->WaterApplyNewConnectionModel->getPropertyIdByOldHolding($holding_no);
					if($prop_id)
					{
						$holding_no = $prop_id["new_holding_no"];
					}
				}
				$prop_address = $prop_id["prop_address"]??"";
				$prop_ward_no = $prop_id["ward_no"]??"";
				$prop_id = $prop_id["prop_id"]??null;
				if($prop_id)
				{
					$prop_owneres = "SELECT STRING_AGG(owner_name,',') AS owner_name,
										STRING_AGG(mobile_no::TEXT,',') AS mobile_no 
									FROM view_prop_detail
									WHERE id = ".$prop_id."";
					$prop_owneres = $this->db->query($prop_owneres)->getFirstRow('array');

				}
			}
			elseif($waterConsumer['saf_no'])
			{
				$saf_id = $this->WaterApplyNewConnectionModel->getSafIdBySafNo($waterConsumer['saf_no']);
				$prop_address = $saf_id["prop_address"]??null;
				$prop_ward_no = $saf_id["ward_no"]??"";
				$saf_id = $saf_id["id"]??"";
				$saf_no = $waterConsumer['saf_no']??"";

				if($saf_id)
				{
					$prop_owneres = "SELECT STRING_AGG(owner_name,',') AS owner_name,
											STRING_AGG(mobile_no::TEXT,',') AS mobile_no 
										FROM view_saf_detail
										WHERE id = ".$saf_id."";
					$prop_owneres = $this->db->query($prop_owneres)->getFirstRow('array');
				}
			}

			if($connection_dtls = $this->meter_status_model->getLastConnectionDetails($waterConsumer['id']))
			{
				$meter_status = $connection_dtls["meter_status"]??"";
				$connection_type = $connection_dtls["connection_type"]??"";
				$meter_no = $connection_dtls["meter_no"]??"";
			}
			$data["holding_no"] = $holding_no;
			$data["prop_id"] = $prop_id;
			$data["prop_address"] = $prop_address;
			$data["prop_ward_no"] = $prop_ward_no;
			$data["prop_owners_name"] =$prop_owneres["owner_name"]??"";
			$data["prop_owners_mobile_no"] = $prop_owneres["mobile_no"]??"";
			$data["saf_id"] = $saf_id;
			$data["saf_no"] = $saf_no;
			$data["connection_type"] = $connection_type;
			$data["meter_status"] = $meter_status;
			$data["meter_no"] = $meter_no;
			return json_encode(["status"=>true,"data"=>$data]);

		}
		catch(Exception $e)
		{
			return json_encode(["status"=>false,"message"=>$e->getMessage()]);
		}
	}
	
	
	
	
	

}
