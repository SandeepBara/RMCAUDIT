<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_transaction;
use App\Models\ModelTransactionDeactivate;


class TradeapplicationReport extends AlphaController
{
	protected $db;
	protected $dbSystem;
	protected $model_transaction;
	protected $model_emp_details;
	//protected $db_name;
	public function __construct()
    {
        parent::__construct();
    	helper(['db_helper']);
        if($db_name = dbConfig("property")){
			//echo $db_name;
            $this->db = db_connect($db_name);            
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        } 
        helper(['form']);
		//$db_name = db_connect("db_rmc_property");	
		$this->trans_dtls=new ModelTransactionDeactivate($this->db);
    }
	public function application_report()
	{	
		$data=array();
		$curr_date=date('Y-m-d');
		$session=session();
		$emp_details=$session->get('emp_details');
		$session_user_id=$emp_details['id'];
		if($this->request->getMethod()=='post'){ 
			return view('report/trade_application_details',$data);
		}
		return view('report/trade_application_status_report',$data);
	}
	
}
