<?php 
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\model_saf_distributed_dtl;
use App\Models\model_doc_mstr;
use App\Models\model_saf_doc_collected_dtl;
use App\Models\model_transfer_mode_mstr;
use App\Models\model_prop_type_mstr;
use App\Models\model_ward_permission;
use App\Models\model_ulb_mstr;
use App\Models\model_view_ward_permission;
use App\Models\model_datatable;
use App\Models\model_visiting_dtl;
use Exception;

/*
use App\Models\model_saf_owner_detail;
use App\Models\model_prop_owner_detail;
use App\Models\WaterApplyNewConnectionModel;
use App\Models\water_consumer_details_model;
use App\Models\TradeApplyLicenceModel;
use App\Models\ModelTradeLicense;*/

class visiting_dtl extends MobiController
{
    protected $db;
    protected $dbSystem;
    protected $model_view_ward_permission;
    protected $model_ward_mstr;
    protected $model_ward_permission;
    protected $model_ulb_mstr;
    protected $model_saf_distributed_dtl;
    protected $model_saf_doc_collected_dtl;
    protected $model_doc_mstr;
    protected $model_transfer_mode_mstr;
    protected $model_prop_type_mstr;
	protected $model_datatable;
	protected $model_visiting_dtl;
	/*
	protected $model_saf_owner_detail;
    protected $model_prop_owner_detail;
    protected $WaterApplyNewConnectionModel;
    protected $water_consumer_details_model;
    protected $TradeApplyLicenceModel;
	protected $ModelTradeLicense;*/
    
    public function __construct()
    {
        parent::__construct();
    	helper(['db_helper','geotagging_helper', 'utility_helper']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name);            
        }

        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        }

        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_ward_permission = new model_ward_permission($this->dbSystem);
        $this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
        $this->model_saf_distributed_dtl = new model_saf_distributed_dtl($this->db);
        $this->model_saf_doc_collected_dtl = new model_saf_doc_collected_dtl($this->db);
        $this->model_doc_mstr = new model_doc_mstr($this->db);
        $this->model_transfer_mode_mstr = new model_transfer_mode_mstr($this->db);
        $this->model_prop_type_mstr = new model_prop_type_mstr($this->db);
        $this->model_view_ward_permission = new model_view_ward_permission($this->dbSystem);
        $this->model_datatable = new model_datatable($this->db);
		$this->model_visiting_dtl = new model_visiting_dtl($this->dbSystem);
		/*
		$this->model_saf_owner_detail = new model_saf_owner_detail($this->db);
        $this->model_prop_owner_detail = new model_prop_owner_detail($this->db);
        $this->WaterApplyNewConnectionModel = new WaterApplyNewConnectionModel($this->db);
        $this->water_consumer_details_model = new water_consumer_details_model($this->db);
        $this->TradeApplyLicenceModel = new TradeApplyLicenceModel($this->dbSystem);
        $this->ModelTradeLicense = new ModelTradeLicense($this->db);*/
    }

    public function visit_details()
    {
        $data =(array)null;
        helper(['form']);
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $emp_mstr = $Session->get("emp_details");
        $emp_details_id = $emp_mstr["id"];  
        if($emp_id==1375)
		{
			ini_set('display_errors', 1);
			ini_set('display_startup_errors', 1);
			error_reporting(E_ALL);
		}
        if($this->request->getMethod()=='post')
        {
			if(isset($_POST['btndesign']))
			{
                $inputs = filterSanitizeStringtoUpper($this->request->getVar());
				try
				{
                    $alphaNumericSpacesDotDash = '/^[a-z0-9 \\/\\.\-\_\n]+$/i';
					$rules=[
                        "moduleId"=>"required|integer",
                        "ref_no"=>"required|regex_match[$alphaNumericSpacesDotDash]",
                        // "address"=>"required|regex_match[$alphaNumericSpacesDotDash]",
                        "ref_id"=>"required",
                        "remarks_id"=>"required",
                    ];
                    if($inputs["other_remark"])
                    {
                        $rules["other_remark"] = "required|regex_match[$alphaNumericSpacesDotDash]";
                    }
                    if(!$this->validate($rules))
                    {
                        $data['validation']=$this->validator;
                        return view('mobile/visiting_dtl', $data);
                    }
                    // print_var(filterSanitizeStringtoUpper($this->request->getVar()));die;
                    // $getloc = json_decode(file_get_contents("http://ipinfo.io/"));
                    
					// $coordinates = explode(",", $getloc->loc??"");
					// $latitude = $coordinates[0]??null; // latitude
					// $longitude = $coordinates[1]??null; // longitude
                    // $city = $getloc->city??null; $org = $getloc->org??null; $region = $getloc->region??null;
					// $country = $getloc->country??null; $postal = $getloc->postal??null;
					// $ip = $getloc->ip??null;
					// $address = trim($org.",".$city.",".$region.",".$country.",".$postal,",");

					$input = [
						'ref_no'=>$inputs["ref_no"],
						'ref_type_id'=>$inputs["ref_id"],
						'remarks_id' => $inputs["remarks_id"],
                        'other_remarks' => $inputs["other_remark"],
                        'module_id' => $inputs["moduleId"],
                        "ip_address"=> $ip??null,
                        "address" => $address??null,
                        "latitude" => $latitude??null,
                        "longitude" => $longitude??null,
                        "emp_id" =>$emp_details_id,
						'created_on' =>date('Y-m-d H:i:s'),
					];
                    $this->dbSystem->transBegin();
					$visiting_dtl_id = $this->model_visiting_dtl->insertdetail($input);
                    if($this->dbSystem->transStatus()===FALSE)
                    {
                        $this->dbSystem->transRollback();
                        throw new Exception("Data Not Inserted");
                    }
                    else
                    {
                        $this->dbSystem->transCommit();
                        return $this->response->redirect(base_url('visiting_dtl/getvisitinglist/'));
                    }
					
				}
                catch (Exception $e) 
                { 
                    print_var($e->getMessage());
                    flashToast("Somthig Whent Wrong");
                }
			}
		}
		
		return view('mobile/visiting_dtl');
    }
	
    public function validateRefNo()
    {
        $respons["status"]=false;
        try{
            $inputs = filterSanitizeStringtoUpper($this->request->getVar());
            if(strtolower($this->request->getMethod())!="post")
            {
                throw new Exception("Only Post Allow");
            }
            
            if($inputs["moduleId"]==1 || $inputs["moduleId"]==2)
            {
                $this->db = db_connect("db_rmc_property");
                $select = "SELECT id FROM tbl_prop_dtl where status = 1 AND new_holding_no = '".$inputs["refno"]."'";
                if($inputs["moduleId"]==1)
                {
                    $select = "SELECT id FROM tbl_saf_dtl where status = 1 AND saf_no = '".$inputs["refno"]."'";
                }
            }
            if($inputs["moduleId"]==3)
            {
                $this->db = db_connect("db_rmc_water");
                $select = "SELECT id FROM tbl_consumer where status = 1 AND consumer_no = '".$inputs["refno"]."'";
            }
            if($inputs["moduleId"]==4)
            {
                $this->db = db_connect("db_rmc_trade");
                $select = "SELECT id FROM tbl_apply_licence where status = 1 AND new_holding_no = '".$inputs["refno"]."'";

            }
            $data = $this->db->query($select)->getFirstRow("array");
            if(!$data)
            {
                throw new Exception("Data Not Found");
            }
            $respons["status"]=true;
            $respons["id"]  = $data["id"];
            return json_encode($respons);


        }catch(\Exception $e)
        {
            $respons["mesage"]=$e->getMessage();
            return json_encode($respons);
        }
    }

    public function getRemarks()
    {
        $respons["status"]=false;
        try{
            $inputs = filterSanitizeStringtoUpper($this->request->getVar());
            if(strtolower($this->request->getMethod())!="post")
            {
                throw new Exception("Only Post Allow");
            }
            $where =" WHERE 1=1 ";
            if($inputs["moduleId"])
            {                
                $where .= " AND module_id = ".$inputs["moduleId"];
            }
            $select = "SELECT id , remarks FROM tbl_visiting_remarks ".$where ;

            $data = $this->dbSystem->query($select)->getResultArray();
            $remarks = "<option value=''>Please Select</option>";
            foreach($data as $val)
            {
                $remarks.=" <option value='".$val["id"]."'>".$val["remarks"]."</option>";
            }

            $respons["status"]=true;
            $respons["remarks"]  = $remarks;
            $respons["SELECT"] = $select;
            return json_encode($respons);


        }catch(\Exception $e)
        {
            $respons["mesage"]=$e->getMessage();
            return json_encode($respons);
        } 
    }
	
	
	public function getvisitinglist()
    {
        $data =(array)null;
        helper(['form']);
        $Session = Session();
		$emp_mstr = $Session->get("emp_details");
        $emp_details_id = $emp_mstr["id"]; 
        $data["user_type_id"] = $emp_mstr["user_type_mstr_id"];
        if(!in_array($data["user_type_id"],[5,7]))
        {
            $emp_details_id=null;
                $sql = "SELECT id,
                            REGEXP_REPLACE(concat(emp_name,' ',middle_name,' ', last_name),'\s+', ' ', 'g') as name
                        FROM view_emp_details 
                        WHERE user_type_mstr_id = 5 
                        ORDER BY concat(emp_name,' ',middle_name,' ', last_name) ";
            $data["tcList"] = $this->dbSystem->query($sql)->getResultArray();
        }
		
        if($this->request->getMethod()=='post')
        {
			$inputs = filterSanitizeStringtoUpper($this->request->getVar());
			$data['from_date']=$inputs['from_date'];
			$data['to_date']=$inputs['to_date'];
            $data['emp_id'] = $inputs['emp_id']??null;
            $data['moduleId'] = $inputs['moduleId']??null;
            $data['remarks_id'] = $inputs['remarks_id']??null;
            $where ="";
            if(!in_array($data["user_type_id"],[5,7])){
                $emp_details_id = $data['emp_id'];
            }
            if($data['moduleId'])
            {
                $where .= " AND tbl1.module_id = ".$data['moduleId'];
            }
            if($data['remarks_id'])
            {
                $where .= " AND tbl1.remarks_id = ".$data['remarks_id'];
            }

			if($visiting_list = $this->model_visiting_dtl->visiting_list($data['from_date'],$data['to_date'],$emp_details_id,$where))
			{
				$data['visiting_list'] = $visiting_list;
			}
			
			return view('mobile/visiting_dtl_list', $data);
		
		} 
        else
        {
			$data['from_date'] = date('Y-m-d');
			$data['to_date'] = date('Y-m-d');
			if($visiting_list = $this->model_visiting_dtl->visiting_list($data['from_date'],$data['to_date'],$emp_details_id))
			{
				$data['visiting_list'] = $visiting_list;
			}
			
			return view('mobile/visiting_dtl_list', $data);
		}
    }
	
	

}
