<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\model_saf_dtl;
use App\Models\model_saf_owner_detail;
use App\Models\PropertyTransactionDeactivateModel;
use App\Models\model_prop_dtl;
use App\Models\model_prop_owner_detail;
use App\Models\model_transaction;
use App\Models\model_emp_details;

class PropertyTransactionDeactivationReport extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $model_ward_mstr;
    protected $model_saf_dtl;
    protected $model_saf_owner_detail;
    protected $PropertyTransactionDeactivateModel;
    protected $model_prop_dtl;
    protected $model_prop_owner_detail;
    protected $model_transaction;
    protected $model_emp_details;
    
    public function __construct(){
        parent::__construct();
    	helper(['db_helper','form']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name); 
        }
        if($db_name = dbSystem()){
            $this->dbSystem = db_connect($db_name); 
        }
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_emp_details = new model_emp_details($this->dbSystem);
        $this->model_saf_owner_detail = new model_saf_owner_detail($this->db);
        $this->model_saf_dtl = new model_saf_dtl($this->db);
        $this->PropertyTransactionDeactivateModel = new PropertyTransactionDeactivateModel($this->db);
        $this->model_prop_dtl = new model_prop_dtl($this->db);
        $this->model_prop_owner_detail = new model_prop_owner_detail($this->db);
        $this->model_transaction = new model_transaction($this->db);
        
    }

    function __destruct() {
		$this->db->close();
		$this->dbSystem->close();
	}
    public function detail()
    {
        $data =(array)null;
        $session = session();
        $propertyTransactionDeactivateList = [];
        $ulb_dtl = $session->get('ulb_dtl');
        $ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];
        if($this->request->getMethod()=='post')
        {
            $data['from_date'] = $this->request->getVar('from_date');
            $data['to_date'] = $this->request->getVar('to_date');
            if($deactivateList = $this->PropertyTransactionDeactivateModel->getAllDeactivatedTransaction($data)){
                foreach ($deactivateList as $key => $value) {
                    $propertyTransactionDeactivateList[$key]['deactive_date'] = $value['deactive_date'];
                    $propertyTransactionDeactivateList[$key]['reason'] = $value['reason'];
                    $propertyTransactionDeactivateList[$key]['file_path'] = $value['file_path'];
                    $propertyTransactionDeactivateList[$key]['empDetails'] = $this->model_emp_details->employeeDetails($value['deactivated_by']);
                    //get Transaction Details
                    $transactionDeatails = $this->model_transaction->getDeactivatedTransactionDetails($value['transaction_id']);
                    $propertyTransactionDeactivateList[$key]['tran_date'] = $transactionDeatails['tran_date'];
                    $propertyTransactionDeactivateList[$key]['tran_no'] = $transactionDeatails['tran_no'];
                    $propertyTransactionDeactivateList[$key]['payable_amt'] = $transactionDeatails['payable_amt'];
                    $propertyTransactionDeactivateList[$key]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($transactionDeatails['ward_mstr_id']);
                    if($transactionDeatails['tran_type']=='Property'){
                        $propertyTransactionDeactivateList[$key]['holding_no'] = $this->model_prop_dtl->getPropdetails($transactionDeatails['prop_dtl_id']);
                        $propertyTransactionDeactivateList[$key]['ownerDetails'] = $this->model_prop_owner_detail->getOwnerDataByPropDetailsId($transactionDeatails['prop_dtl_id']);
                    }else{
                        $propertyTransactionDeactivateList[$key]['saf_no'] = $this->model_saf_dtl->getSafdetails($transactionDeatails['prop_dtl_id']);
                        $propertyTransactionDeactivateList[$key]['ownerDetails'] = $this->model_saf_owner_detail->getOwnerDataBySAFDetailsId($transactionDeatails['prop_dtl_id']);
                    }
                }
            }
            $data['propertyTransactionDeactivateList'] = $propertyTransactionDeactivateList;
            return view('report/property_transaction_deactivation_report',$data);
        }
        else
        {
            $data['from_date'] = date('Y-m-d');
            $data['to_date'] = date('Y-m-d');
            if($deactivateList = $this->PropertyTransactionDeactivateModel->getAllDeactivatedTransaction($data)){
                foreach ($deactivateList as $key => $value) {
                    $propertyTransactionDeactivateList[$key]['deactive_date'] = $value['deactive_date'];
                    $propertyTransactionDeactivateList[$key]['reason'] = $value['reason'];
                    $propertyTransactionDeactivateList[$key]['file_path'] = $value['file_path'];
                    $propertyTransactionDeactivateList[$key]['empDetails'] = $this->model_emp_details->employeeDetails($value['deactivated_by']);
                    //get Transaction Details
                    $transactionDeatails = $this->model_transaction->getDeactivatedTransactionDetails($value['transaction_id']);
                    $propertyTransactionDeactivateList[$key]['tran_date'] = $transactionDeatails['tran_date'];
                    $propertyTransactionDeactivateList[$key]['tran_no'] = $transactionDeatails['tran_no'];
                    $propertyTransactionDeactivateList[$key]['payable_amt'] = $transactionDeatails['payable_amt'];
                    $propertyTransactionDeactivateList[$key]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($transactionDeatails['ward_mstr_id']);
                    if($transactionDeatails['tran_type']=='Property'){
                        $propertyTransactionDeactivateList[$key]['holding_no'] = $this->model_prop_dtl->getPropdetails($transactionDeatails['prop_dtl_id']);
                        $propertyTransactionDeactivateList[$key]['ownerDetails'] = $this->model_prop_owner_detail->getOwnerDataByPropDetailsId($transactionDeatails['prop_dtl_id']);
                    }else{
                        $propertyTransactionDeactivateList[$key]['saf_no'] = $this->model_saf_dtl->getSafdetails($transactionDeatails['prop_dtl_id']);
                        $propertyTransactionDeactivateList[$key]['ownerDetails'] = $this->model_saf_owner_detail->getOwnerDataBySAFDetailsId($transactionDeatails['prop_dtl_id']);
                    }
                }
            }
            $data['propertyTransactionDeactivateList'] = $propertyTransactionDeactivateList;
            return view('report/property_transaction_deactivation_report',$data);
        } 
    }
}
?>
