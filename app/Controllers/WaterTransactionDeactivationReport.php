<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\model_emp_details;
use App\Models\Water_Transaction_Model;
use App\Models\WaterApplyNewConnectionModel;
use App\Models\WaterConsumerModel;
use App\Models\WaterTransactionDeactivateModel;

class WaterTransactionDeactivationReport extends AlphaController
{
    protected $dbSystem;
    protected $water;
    protected $model_ward_mstr;
    protected $model_emp_details;
    protected $Water_Transaction_Model;
    protected $WaterApplyNewConnectionModel;
    protected $WaterConsumerModel;
    protected $WaterTransactionDeactivateModel;
    
    public function __construct(){
        parent::__construct();
    	helper(['db_helper','form']);
        if($db_name = dbSystem()){
            $this->dbSystem = db_connect($db_name); 
        }
        if($db_name = dbConfig("water")){
            $this->water = db_connect($db_name); 
        }
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_emp_details = new model_emp_details($this->dbSystem);
        $this->Water_Transaction_Model = new Water_Transaction_Model($this->water);
        $this->WaterApplyNewConnectionModel = new WaterApplyNewConnectionModel($this->water);
        $this->WaterConsumerModel = new WaterConsumerModel($this->water);
        $this->WaterTransactionDeactivateModel = new WaterTransactionDeactivateModel($this->water);
    }

    function __destruct() {
		$this->water->close();
		$this->dbSystem->close();
	}
    
    public function detail()
    {
        $data =(array)null;
        $session = session();
        $waterTransactionDeactivateList = [];
        $ulb_dtl = $session->get('ulb_dtl');
        $ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];
        $data['folder']=$ulb_dtl['city'];
        if($this->request->getMethod()=='post')
        {
            $data['from_date'] = $this->request->getVar('from_date');
            $data['to_date'] = $this->request->getVar('to_date');
            if($deactivateList = $this->WaterTransactionDeactivateModel->getAllDeactivatedTransaction($data)){
                foreach ($deactivateList as $key => $value) {
                    $waterTransactionDeactivateList[$key]['deactive_date'] = $value['deactive_date'];
                    $waterTransactionDeactivateList[$key]['reason'] = $value['reason'];
                    $waterTransactionDeactivateList[$key]['file_path'] = $value['file_path'];
                    $waterTransactionDeactivateList[$key]['empDetails'] = $this->model_emp_details->employeeDetails($value['deactivated_by']);
                    //get Transaction Details
                    $transactionDeatails = $this->Water_Transaction_Model->getDeactivatedTransactionDetails($value['transaction_id']);
                    $waterTransactionDeactivateList[$key]['transaction_no'] = $transactionDeatails['transaction_no'];
                    $waterTransactionDeactivateList[$key]['transaction_date'] = $transactionDeatails['transaction_date'];
                    $waterTransactionDeactivateList[$key]['paid_amount'] = $transactionDeatails['paid_amount'];
                    $waterTransactionDeactivateList[$key]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($transactionDeatails['ward_mstr_id']);
                    if($transactionDeatails['transaction_type']=='Demand Collection'){
                        $waterTransactionDeactivateList[$key]['consumer_no'] = $this->WaterConsumerModel->getConsumerNo($transactionDeatails['related_id']);
                    }else{
                        $waterTransactionDeactivateList[$key]['application_no'] = $this->WaterApplyNewConnectionModel->getApplicationNo($transactionDeatails['related_id']);
                    }
                }
            }
            $data['waterTransactionDeactivateList'] = $waterTransactionDeactivateList;
            return view('water/report/water_transaction_deactivation_report',$data);
        }
        else
        {
            $data['from_date'] = date('Y-m-d');
            $data['to_date'] = date('Y-m-d');
            if($deactivateList = $this->WaterTransactionDeactivateModel->getAllDeactivatedTransaction($data)){
                foreach ($deactivateList as $key => $value) {
                    $waterTransactionDeactivateList[$key]['deactive_date'] = $value['deactive_date'];
                    $waterTransactionDeactivateList[$key]['reason'] = $value['reason'];
                    $waterTransactionDeactivateList[$key]['file_path'] = $value['file_path'];
                    $waterTransactionDeactivateList[$key]['empDetails'] = $this->model_emp_details->employeeDetails($value['deactivated_by']);
                    //get Transaction Details
                    $transactionDeatails = $this->Water_Transaction_Model->getDeactivatedTransactionDetails($value['transaction_id']);
                    $waterTransactionDeactivateList[$key]['transaction_no'] = $transactionDeatails['transaction_no'];
                    $waterTransactionDeactivateList[$key]['transaction_date'] = $transactionDeatails['transaction_date'];
                    $waterTransactionDeactivateList[$key]['paid_amount'] = $transactionDeatails['paid_amount'];
                    $waterTransactionDeactivateList[$key]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($transactionDeatails['ward_mstr_id']);
                    if($transactionDeatails['transaction_type']=='Demand Collection'){
                        $waterTransactionDeactivateList[$key]['consumer_no'] = $this->WaterConsumerModel->getConsumerNo($transactionDeatails['related_id']);
                    }else{
                        $waterTransactionDeactivateList[$key]['application_no'] = $this->WaterApplyNewConnectionModel->getApplicationNo($transactionDeatails['related_id']);
                    }
                }
            }
            $data['waterTransactionDeactivateList'] = $waterTransactionDeactivateList;
            return view('water/report/water_transaction_deactivation_report',$data);
        } 
    }
}
?>
