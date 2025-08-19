<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PostModel;
use App\Models\model_ward_mstr;
use App\Models\model_ulb_mstr;
use App\Models\WaterMobileModel;
use App\Models\WaterSearchConsumerMobileModel;
use App\Models\model_datatable;
use App\Models\model_view_ward_permission;



class WaterSearchConsumerMobile extends MobiController
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
	protected $search_consumer_mobile_model;
	protected $model_datatable;
	protected $model_view_ward_permission;


	public function __construct()
    {
		parent::__construct();
		helper(['db_helper','utility_helper']);
        $Session = Session();
        $ulb_mstr = getUlbDtl();
        $this->ulb_mstr_id = $ulb_mstr["ulb_mstr_id"];

        $emp_mstr = $Session->get("emp_details");
        $this->emp_details_id = $emp_mstr["id"];
        $this->user_type_mstr_id = $emp_mstr["user_type_mstr_id"];
       
        if($db_name = dbConfig("water")){
            $this->db = db_connect($db_name);            
        }
		if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system);
        }
		$this->water_mobile_model=new WaterMobileModel($this->db);
		$this->search_consumer_mobile_model=new WaterSearchConsumerMobileModel($this->db);
		$this->model_datatable = new model_datatable($this->db);
		$this->model_view_ward_permission = new model_view_ward_permission($this->dbSystem);
    }

	function __destruct()
	{
		if($this->db)
			$this->db->close();
		if($this->dbSystem)
			$this->dbSystem->close();
	}

	
	public function home() { 
		$data=array();
		return view('mobile/index',$data);
	}

	public function search_con() 
	{
		$Session=session();
		$data = arrFilterSanitizeString($this->request->getVar());
		$wardWhere = "";
		$keywordWhere = "";
		if (isset($data["ward_id"]) && isset($data["keyword"])) 
		{
			if ($data["ward_id"]!="") 
			{
				$wardWhere = " AND tbl_consumer.ward_mstr_id='".$data["ward_id"]."'";
			}
			if ($data["keyword"]!="") 
			{
				$keywordWhere = " applicant_name ~* '".$data['keyword']."' or consumer_no ~* '".$data['keyword']."' or mobile_no::text ~* '".$data['keyword']."'"." or application_no::text ~* '".$data['keyword']."'";
				$keywordWhere = " applicant_name ILIKE '%".$data['keyword']."%' or consumer_no ILIKE '%".$data['keyword']."%' or mobile_no::text ILIKE '%".$data['keyword']."%'"." or application_no::text ILIKE '%".$data['keyword']."%'";
			}
			 $sql = "SELECT 
						tbl_consumer.id,
						ward_no,
						consumer_no,
						application_no,
						applicant_name,
						mobile_no,
						address  
					FROM tbl_consumer
					INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_consumer.ward_mstr_id ".$wardWhere."
					LEFT JOIN tbl_apply_water_connection ON tbl_apply_water_connection.id=tbl_consumer.apply_connection_id
					LEFT JOIN (
						SELECT consumer_id,string_agg(applicant_name,',') as applicant_name,
							string_agg(mobile_no::text,',') AS mobile_no 
						FROM tbl_consumer_details 
						where status = 1
						group by consumer_id 
					) AS owner on owner.consumer_id=tbl_consumer.id 
					WHERE ".$keywordWhere;
					$result = $this->model_datatable->getDatatable($sql);
					//print_var($result["result"]);
					$data['consumer_dtls'] = $result['result'];
					$data['offset'] = $result['offset'];
					$data['count'] = $result['count'];
		}		
		$data['ward_list'] = $Session->get("ward_list");
		return view('mobile/water/consumer_lists',$data);

	}

	public function search_consumer_tc($param=NULL) 
	{
		$data=array();
		$Session = session();
        $emp_details_id = $this->emp_details_id;
		$data['param']=$param;

        $data['ward_list']=$this->model_view_ward_permission->getPermittedWard($emp_details_id);
		//$data['ward_list']=$Session->get("ward_list");
		$ward_ids = array_map(function($val){
			return $val['ward_mstr_id'];
	   },$data['ward_list']);
	   $ward_ids = !empty($ward_ids) ? implode(",",$ward_ids) : null ;

		$data['ward_id']='';
		$data['keyword']='';
		$data['consumer_dtls']=null;	
		$start = 0;
		$limit=10;
		$offset=$start;
		if(isset($_GET['page'])) 
		{	$page = $_GET['page'];
			$start = 10*(int)$page;
			$limit=$offset+10;
			$offset=$start;
		} 
		else 
		{
			!empty($Session->get('temp'))?$Session->remove('temp'):'';
		}
		$inputs=[];
		if($this->request->getMethod()=='get') 
		{	
			$inputs=filterSanitizeStringtoUpper($this->request->getVar());
			// if($this->emp_details_id==1375)
			// {
			// 	print_var($inputs);
			// }
			$data['ward_id']=$inputs['ward_id']??null;
			$data['keyword']=$inputs['keyword']??null;
			$where="";

			if($data['ward_id']!="" ) 
			{
				$where.=" AND ward_mstr_id=".$data['ward_id'];

			} 
			else
			{
				if($ward_ids)
				$where.=" AND ward_mstr_id IN(".$ward_ids.")";

			
			}			
			if($data['keyword']!="") 
			{
				$where.="  and (applicant_name Ilike '%".$data['keyword']."%' or consumer_no Ilike '%".$data['keyword']."%' or mobile_no::text Ilike '%".$data['keyword']."%'"." or application_no::text Ilike '%".$data['keyword']."%' ) ";
			}
			//$data['consumer_dtls']=$this->search_consumer_mobile_model->search_consumer($where);
			$inputs['temp_where']=$where;			
		}	
		
		$temp = $inputs ; 
		if(!empty($temp))
		{ 
			$inputs= $temp;
			$where = $inputs['temp_where'];
			$data['ward_id']=$inputs['ward_id']??null;
			$data['keyword']=$inputs['keyword']??null;

			$select="select id,ward_no,consumer_no,application_no,applicant_name,mobile_no,address ";
			$from=" from view_consumer_dtl 
					left join (
						select consumer_id,string_agg(applicant_name,',') as applicant_name,
							string_agg(mobile_no::text,',') as mobile_no 
						from tbl_consumer_details
						where status = 1 
						group by consumer_id 
						) as owner on owner.consumer_id=view_consumer_dtl.id 
					where 1=1 $where"; 
			$count = "select count(*) ".$from;
			$sql = $select.$from ." offset $start limit $limit ";			
			$data['consumer_dtls']=$this->search_consumer_mobile_model->search_consumer2($sql);
			$data['count']=$this->search_consumer_mobile_model->search_consumer2($count)[0]['count']??0;
			$data['offset']=$offset;
			
			
		}
		// print_r($data);


		return view('mobile/water/consumer_lists',$data);

	}

	public function search_consumer_survey($param=NULL)
	{

		$data=array();
		$Session = session();
        $emp_details_id = $this->emp_details_id;
		$data['param']=$param;

        $data['ward_list']=$this->model_view_ward_permission->getPermittedWard($emp_details_id);
		$ward_ids = array_map(function($val){
			return $val['ward_mstr_id'];
	   },$data['ward_list']);
	   $ward_ids = !empty($ward_ids) ? implode(",",$ward_ids) : null ;

		$data['ward_id']='';
		$data['keyword']='';
		$data['consumer_dtls']=null;	
		$start = 0;
		$limit=10;
		$offset=$start;
		if(isset($_GET['page'])) 
		{	$page = $_GET['page'];
			$start = 10*(int)$page;
			$limit=$offset+10;
			$offset=$start;
		} 
		else 
		{
			!empty($Session->get('temp'))?$Session->remove('temp'):'';
		}
		$inputs=[];
		if($this->request->getMethod()=='get') 
		{	
			$inputs=filterSanitizeStringtoUpper($this->request->getVar());

			$data['ward_id']=$inputs['ward_id']??null;
			$data['keyword']=$inputs['keyword']??null;
			$where="";

			if($data['ward_id']!="" ) 
			{
				$where.=" AND ward_mstr_id=".$data['ward_id'];

			} 
			else
			{
				if($ward_ids)
				$where.=" AND ward_mstr_id IN(".$ward_ids.")";

			
			}			
			if($data['keyword']!="") 
			{
				$where.="  and (applicant_name Ilike '%".$data['keyword']."%' or consumer_no Ilike '%".$data['keyword']."%' or mobile_no::text Ilike '%".$data['keyword']."%'"." or application_no::text Ilike '%".$data['keyword']."%' ) ";
			}
			//$data['consumer_dtls']=$this->search_consumer_mobile_model->search_consumer($where);
			$inputs['temp_where']=$where;			
		}	
		
		$temp = $inputs ; 
		if(!empty($temp))
		{ 
			$inputs= $temp;
			$where = $inputs['temp_where'];
			$data['ward_id']=$inputs['ward_id']??null;
			$data['keyword']=$inputs['keyword']??null;

			$select="select id,ward_no,consumer_no,application_no,applicant_name,mobile_no,address ";
			$from=" from view_consumer_dtl 
					left join (
						select consumer_id,string_agg(applicant_name,',') as applicant_name,
							string_agg(mobile_no::text,',') as mobile_no 
						from tbl_consumer_details
						where status = 1 
						group by consumer_id 
						) as owner on owner.consumer_id=view_consumer_dtl.id 
					LEFT JOIN(
						SELECT consumer_id 
						FROM tbl_water_survey 
						WHERE tbl_water_survey.status =1
					)water_survey ON water_survey.consumer_id = view_consumer_dtl.id
					where 1=1 
						AND water_survey.consumer_id IS NULL
						$where"; 
			$count = "select count(*) ".$from;
			$sql = $select.$from ." offset $start limit $limit ";			
			$data['consumer_dtls']=$this->search_consumer_mobile_model->search_consumer2($sql);
			$data['count']=$this->search_consumer_mobile_model->search_consumer2($count)[0]['count']??0;
			$data['offset']=$offset;
			
			
		}
		// print_r($data);


		return view('mobile/water/consumer_lists',$data);
	}
}
