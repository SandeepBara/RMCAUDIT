<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PostModel;
use App\Models\model_ward_mstr;
use App\Models\model_ulb_mstr;
use App\Models\WaterMobileModel;
use App\Models\WaterSearchConsumerMobileModel;
use App\Models\WaterSearchApplicantsMobileModel;
use App\Models\model_view_ward_permission;



class WaterSearchApplicantsMobile extends MobiController
{
	protected $db;
	protected $dbSystem;
	protected $db_property;
	
	//protected $db_name;
	protected $model_ward_mstr;
	protected $model_ulb_mstr;
	protected $ulb_mstr_id;
	protected $emp_details_id;
	protected $user_type_mstr_id;
	protected $search_applicant_mobile_mode;
	protected $model_view_ward_permission;

	public function __construct()
    {
		
        parent::__construct();
    	helper(['db_helper','utility_helper']);

    	$data =(array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl")??getUlbDtl();
        $this->ulb_mstr_id = $ulb_mstr["ulb_mstr_id"];

        $emp_mstr = $Session->get("emp_details");
        $this->emp_details_id = $emp_mstr["id"];
        $this->user_type_mstr_id = $emp_mstr["user_type_mstr_id"];
       
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
		$this->search_consumer_mobile_model=new WaterSearchConsumerMobileModel($this->db);
		$this->search_applicant_mobile_model=new WaterSearchApplicantsMobileModel($this->db);		
		$this->model_view_ward_permission = new model_view_ward_permission($this->dbSystem);
    }

	function __destruct()
	{
		if($this->db) $this->db->close();
		if($this->db_property) $this->db_property->close();
		if($this->dbSystem) $this->dbSystem->close();
	}

	
	public function home()
	{ 
		$data=array();		
		return view('mobile/index',$data);
	}

	public function search_applicants_tc()
	{

		$data=array();
		$Session=session();
		$emp_mstr = $Session->get("emp_details");
        $emp_details_id = $this->emp_details_id;
        $user_type_mstr_id = $this->user_type_mstr_id;
		$data['ulb_mstr_id']=$this->ulb_mstr_id;
		$data['ward_id']='';
		$data['keyword']='';
		$data['applicants_dtl']=null;
		$data['ward_list']=$this->model_view_ward_permission->getPermittedWard($emp_details_id);
        //$data['ward_list']=$this->search_applicant_mobile_model->wardlist_by_emp_dtls_id($emp_details_id);
        
		$ward_ids = array_map(function($val){
			return $val['ward_mstr_id'];
		},$data['ward_list']);
		$ward_ids = !empty($ward_ids) ? implode(",",$ward_ids) : null ;
		$start = 0;
		$limit=10;
		$offset=$start;
		if(isset($_GET['page'])) 
		{	$page = $_GET['page'];
			$temp = $Session->get('temp');	
			
			$start = 10*(int)$page;
			$limit=$offset+10;
			$offset=$start;
		} 
		else 
		{
			!empty($Session->get('temp'))?$Session->remove('temp'):'';
		}		
		if($this->request->getMethod()=='get')
		{
			$inputs=filterSanitizeStringtoUpper($this->request->getVar());
			//print_r($inputs);
			$data['ward_id']=$inputs['ward_id']??null;
			$data['keyword']=$inputs['keyword']??null;
			$where = ' AND payment_status = 0 ';
			if($data['ward_id']!="")
			{
				$where.=" AND ward_id=".$data['ward_id'];
			}
			else 
			{
				$where.=" AND ward_id in (".$ward_ids.")" ;
			}
			if($data['keyword']!="") 
			{
				$where .="  AND (upper(applicant_name) Ilike '%".$data['keyword']."%' or upper(application_no) Ilike '%".$data['keyword']."%' or mobile_no::text Ilike '%".$data['keyword']."%') ";
			}

			$select=" select id,ward_no,application_no,applicant_name,mobile_no ";
			$from=" from view_water_application_details  where 1=1 $where "; 
			$count = "select count(*) ".$from;
			$sql = $select.$from ." offset $start limit $limit ";			
			$data['applicants_dtl']=$this->search_consumer_mobile_model->search_consumer2($sql);
			$data['count']=$this->search_consumer_mobile_model->search_consumer2($count)[0]['count']??0;
			$data['offset']=$offset;


			//$data['applicants_dtl']=$this->search_applicant_mobile_model->search_applicants($where);
			
		}
		return view('mobile/water/application_lists', $data);
	}

}
