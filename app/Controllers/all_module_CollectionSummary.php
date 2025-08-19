<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\TradeTransactionModel;
use App\Models\model_ward_mstr;
use App\Models\model_emp_details;
use App\Models\Water_Transaction_Model;
use App\Models\model_transaction;

class all_module_CollectionSummary extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $TradeTransactionModel;
    protected $model_ward_mstr;
    protected $model_emp_details;
	protected $Water_Transaction_Model;
	protected $model_transaction;

    public function __construct(){
        parent::__construct();
    	helper(['db_helper','form']);
        if($db_name = dbConfig("trade")){
            $this->db = db_connect($db_name); 
        }
		if($db_name = dbConfig("water")){
            //echo $db_name;
            $this->water_db = db_connect($db_name);            
        }
        if($db_name = dbConfig("property"))
        {
            $this->property_db = db_connect($db_name);
        }
        if($db_name = dbSystem()){
            $this->dbSystem = db_connect($db_name); 
        }
        $this->TradeTransactionModel = new TradeTransactionModel($this->db);
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_emp_details = new model_emp_details($this->dbSystem);
		$this->Water_Transaction_Model = new Water_Transaction_Model($this->water_db);
		$this->model_transaction = new model_transaction($this->property_db);
    }

    function __destruct() {
		$this->db->close();
		$this->water_db->close();
        $this->property_db->close();
        $this->dbSystem->close();
	}

    public function all_module_collection_details(){
		
        $data =(array)null;
        $allModuleCollection=[];
        $total =0;
        $groundTotal =0;
        $totalProperty =0;
        $totalTrade =0;
        $totalWater =0;
        if($this->request->getMethod()=='post')
        {
            $inputs = arrFilterSanitizeString($this->request->getVar());
            $data['to_date'] = $inputs['to_date'];
            $data['from_date'] = $inputs['from_date'];
			$data['tax_collector'] = $inputs['tax_collector'];

			if($data['tax_collector']!=""){
				$emp_name = $this->model_emp_details->getempnamebyempid($data['tax_collector']);
				foreach($emp_name as $key => $emp_namess)
				{
					$emp_name[$key]['newwater_coll']=$this->Water_Transaction_Model->getnewTotalPaidAmountwithCountTransbyempid($data['from_date'],$data['to_date'],$emp_namess['id']);
					$emp_name[$key]['demandwater_coll']=$this->Water_Transaction_Model->getdemandTotalPaidAmountwithCountTransbyempid($data['from_date'],$data['to_date'],$emp_namess['id']);
					$emp_name[$key]['prop_coll']=$this->model_transaction->propgetTotalPaidAmountwithCountTransbyempid($data['from_date'],$data['to_date'],$emp_namess['id']);
					$emp_name[$key]['saf_coll']=$this->model_transaction->propgetTotalPaidAmountwithCountTransbyempid($data['from_date'],$data['to_date'],$emp_namess['id']);
					$emp_name[$key]['trade_coll']=$this->TradeTransactionModel->tradegetTotalPaidAmountwithCountTransbyempid($data['from_date'],$data['to_date'],$emp_namess['id']);
				}
			} else {
				$emp_name = $this->model_emp_details->getempnamelist();
				$i=0;
				foreach($emp_name as $key => $emp_namess)
				{
					$emp_name[$key]['newwater_coll']=$this->Water_Transaction_Model->getnewTotalPaidAmountwithCountTransbyempid($data['from_date'],$data['to_date'],$emp_namess['id']);
					$emp_name[$key]['demandwater_coll']=$this->Water_Transaction_Model->getdemandTotalPaidAmountwithCountTransbyempid($data['from_date'],$data['to_date'],$emp_namess['id']);
					$emp_name[$key]['prop_coll']=$this->model_transaction->propgetTotalPaidAmountwithCountTransbyempid($data['from_date'],$data['to_date'],$emp_namess['id']);
					$emp_name[$key]['saf_coll']=$this->model_transaction->propgetTotalPaidAmountwithCountTransbyempid($data['from_date'],$data['to_date'],$emp_namess['id']);
					$emp_name[$key]['trade_coll']=$this->TradeTransactionModel->tradegetTotalPaidAmountwithCountTransbyempid($data['from_date'],$data['to_date'],$emp_namess['id']);
				}
				
			}
           
        }

       
		
		$data['coll_list'] = $emp_name;
		//print_r($data['coll_list'] );
		$data['team_leader'] = $this->model_emp_details->get_team_leader();
        return view('report/all_module_collection_details',$data);
    } 
    
    public function get_tax_collector_ajax()
    {
          if($this->request->getMethod()=='post'){
 
            try{
                // data filter
                 $team_leader_id = sanitizeString($this->request->getVar('team_leader_id'));          
                 $data['tax_collector'] = $this->model_emp_details->get_tax_collector($team_leader_id);
                 $output = "";
                 $output.=  '<option value="">Select</option>';
                 foreach($data['tax_collector'] as $value)
                 {
                    $output.=  '<option value="'.$value['id'].'">'.$value['emp_name'].'</option>';
                 }
                 
                 return json_encode($output);
            }catch(Exception $e){

            }
        }
    }
	
	/*
	public function get_collection_details()
	{
		$data =(array)null;
		
		return view('report/all_module_collection_details', $data);
	}*/
	
	
}
?>
