<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\model_trade_transaction;
use App\Models\model_emp_details;
use App\Models\TradeTransactionDeactivateModel;

class TradeTransactionDeactivationReport extends AlphaController
{
    protected $trade;
    protected $dbSystem;
    protected $model_ward_mstr;
    protected $model_trade_transaction;
    protected $model_emp_details;
    protected $TradeTransactionDeactivateModel;
    
    public function __construct(){
        parent::__construct();
    	helper(['db_helper','form']);
        if($db_name = dbConfig("trade")){
            $this->trade = db_connect($db_name); 
        }
        if($db_name = dbSystem()){
            $this->dbSystem = db_connect($db_name); 
        }
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_emp_details = new model_emp_details($this->dbSystem);
        $this->model_trade_transaction = new model_trade_transaction($this->trade);
        $this->TradeTransactionDeactivateModel = new TradeTransactionDeactivateModel($this->trade);
        
    }

    function __destruct() {
		$this->trade->close();
		$this->dbSystem->close();
	}

    public function detail()
    {
        $data =(array)null;
        $session = session();
        $tradeTransactionDeactivateList = [];
        $ulb_dtl = $session->get('ulb_dtl');
        $ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];
        if($this->request->getMethod()=='post')
        {
            $data['from_date'] = $this->request->getVar('from_date');
            $data['to_date'] = $this->request->getVar('to_date');
            if($deactivateList = $this->TradeTransactionDeactivateModel->getAllDeactivatedTransaction($data)){

                foreach ($deactivateList as $key => $value) {
                    $tradeTransactionDeactivateList[$key]['deactive_date'] = $value['deactive_date'];
                    $tradeTransactionDeactivateList[$key]['reason'] = $value['reason'];
                    $tradeTransactionDeactivateList[$key]['file_path'] = $value['file_path'];
                    $tradeTransactionDeactivateList[$key]['empDetails'] = $this->model_emp_details->employeeDetails($value['deactivated_by']);
                    //get Transaction Details
                   $tradeTransactionDeactivateList[$key]['transactionDeatails'] = $this->model_trade_transaction->getDeactivatedTransactionDetails($value['transaction_id']);    
                    $tradeTransactionDeactivateList[$key]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($tradeTransactionDeactivateList[$key]['transactionDeatails']['ward_mstr_id']);
                }
            }
            $data['tradeTransactionDeactivateList'] = $tradeTransactionDeactivateList;
            return view('trade/Connection/trade_transaction_deactivation_report',$data);
        }
        else
        {
            $data['from_date'] = date('Y-m-d');
            $data['to_date'] = date('Y-m-d');
            if($deactivateList = $this->TradeTransactionDeactivateModel->getAllDeactivatedTransaction($data)){

                foreach ($deactivateList as $key => $value) {
                    $tradeTransactionDeactivateList[$key]['deactive_date'] = $value['deactive_date'];
                    $tradeTransactionDeactivateList[$key]['reason'] = $value['reason'];
                    $tradeTransactionDeactivateList[$key]['file_path'] = $value['file_path'];
                    $tradeTransactionDeactivateList[$key]['empDetails'] = $this->model_emp_details->employeeDetails($value['deactivated_by']);
                    //get Transaction Details
                   $tradeTransactionDeactivateList[$key]['transactionDeatails'] = $this->model_trade_transaction->getDeactivatedTransactionDetails($value['transaction_id']);    
                    $tradeTransactionDeactivateList[$key]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($tradeTransactionDeactivateList[$key]['transactionDeatails']['ward_mstr_id']);
                }
            }
            $data['tradeTransactionDeactivateList'] = $tradeTransactionDeactivateList;
            return view('trade/Connection/trade_transaction_deactivation_report',$data);
        } 
    }
}
?>
