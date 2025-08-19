<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PostModel;
use App\Models\model_ward_mstr;
use App\Models\model_ulb_mstr;
use App\Models\WaterMobileModel;
use App\Models\WaterConnectionDetailsViewModel;
use App\Models\WaterPropertyModel;
use App\Models\WaterConnectionThroughModel;
use App\Models\WaterConnectionTypeModel;
use App\Models\WaterPipelineModel;
use App\Models\PropertyModel;
use App\Models\WaterApplyNewConnectionModel;
use App\Models\WaterSiteInspectionModel;
use App\Models\WaterViewConnectionFeeModel;
use App\Models\WaterConnectionChargeModel;
use App\Models\WaterFerruleTypeModel;


class WaterSiteInspection extends MobiController
{
	protected $db;
	protected $dbSystem;
	protected $db_property;
	
	//protected $db_name;
	protected $model_ward_mstr;
	protected $model_ulb_mstr;
	protected $user_type_mstr_id;
	protected $emp_details_id;
	protected $site_ins_model;

	public function __construct()
    {
    	
    	$Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $this->emp_details_id = $emp_mstr["id"];
        $this->user_type_mstr_id = $emp_mstr["user_type_mstr_id"];
        

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
		
        $this->site_ins_model=new WaterSiteInspectionModel($this->db);
    }

	
	
	public function index($apply_connection_id,$si_id='')
	{

		$data=array();
		$data['user_type']=$this->user_type_mstr_id;
		if($si_id=='')
			$data['site_inspection_details']=$this->site_ins_model->getSiteInspectionDetailsbyJENew($apply_connection_id);
		else
		{
			$where=array('md5(id::text)'=>$si_id);
			$data['site_inspection_details']=$this->site_ins_model->getSiteInspectionDetailsbyJENew($apply_connection_id,$where);
		}
		//print_r($data['site_inspection_details']);
		
		return view('water/water_connection/site_inspection_details',$data);
		
	}
	public function aeInspection($apply_connection_id)
	{

		$data=array();
		$data['user_type']=$this->user_type_mstr_id;
		$data['site_inspection_details']=$this->site_ins_model->getSiteInspectionDetailsbyAE($apply_connection_id);
		//print_r($data['site_inspection_details']);
		
		return view('water/water_connection/technical_inspection_view',$data);
		
	}



	

}
