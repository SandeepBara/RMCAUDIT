<?php namespace App\Controllers;


use CodeIgniter\Controller;
use App\Models\PostModel;
use App\Models\model_ward_mstr;
use App\Models\model_ulb_mstr;
use App\Models\model_visiting_dtl;
use App\Models\model_view_emp_details;


class visiting_report_list extends AlphaController
{
	protected $db;
	protected $dbSystem;
	//protected $db_name;
	protected $model_ward_mstr;
	protected $model_ulb_mstr;
	protected $model_visiting_dtl;
	protected $model_view_emp_details;
	

	public function __construct()
    {
        parent::__construct();
    	helper(['db_helper', 'qr_code_generator_helper', 'utility_helper']);
    	//	$this->load->library('phpqrcode/qrlib');
        if($db_name = dbConfig("property")){
			//echo $db_name;
            $this->db = db_connect($db_name);            
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        } 
		
        helper(['form']);
		//$db_name = db_connect("db_rmc_property");		
        $this->model = new model_ward_mstr($this->dbSystem);
		$this->modelUlb = new model_ulb_mstr($this->dbSystem);
		$this->model_visiting_dtl = new model_visiting_dtl($this->dbSystem);
		$this->model_view_emp_details = new model_view_emp_details($this->dbSystem);
    }
			//print_r($session->get('where'));	
	public function getvisitinglist()
    {
        $data =(array)null;
        helper(['form']);
        $Session = Session();
		$emp_mstr = $Session->get("emp_details");
        $emp_details_id = $emp_mstr["id"]; 
		$data['tax_collector'] = $this->model_view_emp_details->get_tc_name();
        if($this->request->getMethod()=='post'){
			$inputs = filterSanitizeStringtoUpper($this->request->getVar());
			$data['from_date']=$inputs['from_date'];
			$data['to_date']=$inputs['to_date'];
			$data['emp_details_id'] = $inputs['tc_name'];
			if($data['emp_details_id']!=""){
				if($visiting_list = $this->model_visiting_dtl->visiting_list($data['emp_details_id'],$data['from_date'],$data['to_date']))
				{
					$data['visiting_list'] = $visiting_list;
				}
			}else{
				if($visiting_list = $this->model_visiting_dtl->visiting_listwithoutid($data['from_date'],$data['to_date']))
				{
					$data['visiting_list'] = $visiting_list;
				}
			}
			return view('report/visiting_report_dtl', $data);
		
		} else{
			$data['from_date'] = date('Y-m-d');
			$data['to_date'] = date('Y-m-d');
			if($visiting_list = $this->model_visiting_dtl->visiting_listwithoutid($data['from_date'],$data['to_date']))
			{
				$data['visiting_list'] = $visiting_list;
			}
			
			return view('report/visiting_report_dtl', $data);
		}
    }
	

}
