<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PostModel;
use App\Models\model_ward_mstr;
use App\Models\model_ulb_mstr;
use App\Models\model_prop_dtl;

class searchProcess extends AlphaController
{
	protected $db;
	protected $dbSystem;
	//protected $db_name;
	protected $model_ward_mstr;
	protected $model_ulb_mstr;
	protected $model_prop_dtl;
	

	public function __construct()
    {
        parent::__construct();
    	helper(['db_helper', 'qr_code_generator_helper']);
    	//	$this->load->library('phpqrcode/qrlib');
        if($db_name = dbConfig("property")){
			//echo $db_name;
            $this->dbproperty = db_connect($db_name);            
        }
		if($db_name = dbConfig("trade")){
			//echo $db_name;
            $this->dbtrade = db_connect($db_name);            
        }
		if($db_name = dbConfig("water")){
			//echo $db_name;
            $this->dbwater = db_connect($db_name);            
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        } 
		
        helper(['form']);
		//$db_name = db_connect("db_rmc_property");		
        $this->model = new model_ward_mstr($this->dbSystem);
		$this->modelUlb = new model_ulb_mstr($this->dbSystem);
		$this->modelprop = new model_prop_dtl($this->dbproperty);
		
    }
	
	
	
	public function searchProcessbyholding()
	{
		$ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];	
		$data =(array)null;
        helper(['form']);
        if($this->request->getMethod()=='post'){
			$inputs = filterSanitizeStringtoUpper($this->request->getVar());
			$data['keyword']=$inputs['keyword'];
			if($data['keyword']!=""){
				$where="(holding_no ilike '%".$data['keyword']."%' or new_holding_no ilike '%".$data['keyword']."%')";
			}
			//$data['emp_details'] = $this->modelprop->consumer_details($where);
			return view('searchProcess', $data);
		
		} else{
			return view('searchProcess');
		}
	}
	
	
	
	
	

}
