<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_transaction;
use App\Models\model_view_tc_transaction_details;
use App\Models\model_cash_verification_mstr;
use App\Models\model_cash_verification_details;
use App\Models\MasterModel;
use App\Models\model_emp_details;
use App\Models\Water_Transaction_Model;
use App\Models\TradeTransactionModel;
use App\Models\model_cheque_details;
use App\Models\Water_Cheque_Details_Model;
use App\Models\TradeChequeDtlModel;
use App\Models\model_user_hierarchy;
use App\Models\model_notification;


class UnverifiedCheque extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $water;
    protected $trade;
    protected $model_ward_mstr;
    protected $model_view_bank_recancilation;
    protected $model_emp_details;
    protected $model_view_tc_transaction_details;
    protected $model_transaction;
    protected $Water_Transaction_Model;
    protected $TradeTransactionModel;
    protected $model_cheque_details;
    protected $Water_Cheque_Details_Model;
    protected $TradeChequeDtlModel;
    protected $model_user_hierarchy;
    protected $model_notification;
    //protected $db_name;
    public function __construct()
    {
        parent::__construct();
        helper(['db_helper','form']);
        if($db_name = dbConfig("property")){
            //echo $db_name;
            $this->db = db_connect($db_name);            
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        }
        if($db_name = dbConfig("water")){
            //echo $db_name;
            $this->water = db_connect($db_name);            
        }
        if($db_name = dbConfig("trade")){
            //echo $db_name;
            $this->trade = db_connect($db_name);            
        }
        //$db_name = db_connect("db_rmc_property"); 
        $this->model_transaction = new model_transaction($this->db);
        $this->model_cheque_details = new model_cheque_details($this->db);
        $this->model_view_tc_transaction_details = new model_view_tc_transaction_details($this->db);
        $this->model_cash_verification_mstr = new model_cash_verification_mstr($this->db);
        $this->model_cash_verification_details = new model_cash_verification_details($this->db);
        $this->master = new MasterModel($this->dbSystem);
        $this->model_emp_details = new model_emp_details($this->dbSystem);
        $this->model_user_hierarchy = new model_user_hierarchy($this->dbSystem);
        $this->model_notification = new model_notification($this->dbSystem);
        $this->Water_Transaction_Model = new Water_Transaction_Model($this->water);
        $this->Water_Cheque_Details_Model = new Water_Cheque_Details_Model($this->water);
        $this->TradeTransactionModel= new TradeTransactionModel($this->trade);
        $this->TradeChequeDtlModel= new TradeChequeDtlModel($this->trade);
    }
    public function detail($id=null)
    {
        $session=session();
        $empDetails = $session->get('emp_details');
        $data['employee_id'] = $empDetails['id'];
        //Get TC Details List
        $notVerrifiedDetails=[];
        $chequeDetails=[];
        $key=0;
        if($this->request->getMethod()=='post'){
        $inputs = arrFilterSanitizeString($this->request->getVar());
        $data['from_date'] = $inputs['from_date'];
        $data['to_date'] = $inputs['to_date'];
        //get All Notification Detais Data
        $notificationData = $this->model_notification->getAllNotVerifiedData($data);
       // print_r($notificationData);
        foreach ($notificationData as $value) {
            //calculate sum for all transaction
            $propertyCheque = $this->model_transaction->getTotalAmountByVerifyStatus($value['id']);
            $tradeCheque = $this->TradeTransactionModel->getTotalAmountByVerifyStatus($value['id']);
            $waterCheque = $this->Water_Transaction_Model->getTotalAmountByVerifyStatus($value['id']);
            $notVerrifiedDetails[$key]['total'] = $propertyCheque+$tradeCheque+$waterCheque;
            $notVerrifiedDetails[$key]['created_on'] = $value['created_on'];
            $notVerrifiedDetails[$key]['remarks'] = $value['remarks'];
            $notVerrifiedDetails[$key]['verify_status'] = $value['id'];
            //get Employee Name
            if($propertyCheque>0){
                $employee_id = $this->model_transaction->getEmployeeId(md5($value['id']));
                $notVerrifiedDetails[$key]['empDetails'] = $this->model_emp_details->employeeDetails($employee_id['tran_by_emp_details_id']);
                //print_r($notVerrifiedDetails[$key]['empDetails']);
            }else if($tradeCheque>0){
                $employee_id = $this->TradeTransactionModel->getEmployeeId(md5($value['id']));
                $notVerrifiedDetails[$key]['empDetails'] = $this->model_emp_details->employeeDetails($employee_id['emp_details_id']);
            }else if($waterCheque>0){
                $employee_id = $this->Water_Transaction_Model->getEmployeeId(md5($value['id']));
                $notVerrifiedDetails[$key]['empDetails'] = $this->model_emp_details->employeeDetails($employee_id['emp_details_id']);
            }
            $key++;
        }
        $data['notVerrifiedDetails'] = $notVerrifiedDetails;
        return view('property/unverified_cheque',$data);
      }else if(isset($id)){
       // echo $id;
            $key = 0;
            $propertyCheque = $this->model_transaction->getNotVerifiedAmountProperty($id);
            $tradeCheque = $this->TradeTransactionModel->getNotVerifiedAmounTrade($id);
            $waterCheque = $this->Water_Transaction_Model->getNotVerifiedAmounTrade($id);
            $data['total'] = $propertyCheque+$tradeCheque+$waterCheque;
            if($propertyCheque>0){
                $propertyId = $this->model_transaction->getEmployeeId($id);
                $data['empDetails'] = $this->model_emp_details->employeeDetails($propertyId['tran_by_emp_details_id']);
            }else if($tradeCheque>0){
                $tradeId = $this->TradeTransactionModel->getEmployeeId($id);
                $data['empDetails'] = $this->model_emp_details->employeeDetails($tradeId['emp_details_id']);
            }else if($waterCheque>0){
                $waterId = $this->Water_Transaction_Model->getEmployeeId($id);
                $data['empDetails'] = $this->model_emp_details->employeeDetails($waterId['emp_details_id']);
            }
            //get All cheque Details
            if($propertyChequeDetails = $this->model_transaction->getAllNotVerifiedChequeDetails($id)){
                foreach ($propertyChequeDetails as $value) {
                    //get Property cheque Details
                    //$chewqData = $this->model_cheque_details->
                }
            }

        return view('property/unverified_cheque_view', $data);
      }else{
            $data['from_date'] = date('Y-m-d');
            $data['to_date'] = date('Y-m-d');
            $notificationData = $this->model_notification->getAllNotVerifiedData($data);
           // print_r($notificationData);
            foreach ($notificationData as $value) {
                //calculate sum for all transaction
                $propertyCheque = $this->model_transaction->getTotalAmountByVerifyStatus($value['id']);
                $tradeCheque = $this->TradeTransactionModel->getTotalAmountByVerifyStatus($value['id']);
                $waterCheque = $this->Water_Transaction_Model->getTotalAmountByVerifyStatus($value['id']);
                $notVerrifiedDetails[$key]['total'] = $propertyCheque+$tradeCheque+$waterCheque;
                $notVerrifiedDetails[$key]['created_on'] = $value['created_on'];
                $notVerrifiedDetails[$key]['remarks'] = $value['remarks'];
                $notVerrifiedDetails[$key]['verify_status'] = $value['id'];
                //get Employee Name
                if($propertyCheque>0){
                    $employee_id = $this->model_transaction->getEmployeeId($value['id']);
                    $notVerrifiedDetails[$key]['empDetails'] = $this->model_emp_details->employeeDetails($employee_id['tran_by_emp_details_id']);
                    //print_r($notVerrifiedDetails[$key]['empDetails']);
                }else if($tradeCheque>0){
                    $employee_id = $this->TradeTransactionModel->getEmployeeId($value['id']);
                    $notVerrifiedDetails[$key]['empDetails'] = $this->model_emp_details->employeeDetails($employee_id['emp_details_id']);
                }else if($waterCheque>0){
                    $employee_id = $this->Water_Transaction_Model->getEmployeeId($value['id']);
                    $notVerrifiedDetails[$key]['empDetails'] = $this->model_emp_details->employeeDetails($employee_id['emp_details_id']);
                }
                $key++;
            }
            $data['notVerrifiedDetails'] = $notVerrifiedDetails;
            return view('property/unverified_cheque', $data);
        }
    }
    public function verificationDetails(){
        if($this->request->getMethod()=='post'){
            flashToast('cashVerification', 'Cash verified Successfully!!');
            return $this->response->redirect(base_url('CashVerification/details'));
        } else{
            flashToast('cashVerification', 'Something Is Wrong!!');
           return $this->response->redirect(base_url('CashVerification/details'));
        }
    }
}
?>
