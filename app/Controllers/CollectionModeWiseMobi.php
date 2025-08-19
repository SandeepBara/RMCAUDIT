<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_transaction;
use App\Models\model_prop_dtl;
use App\Models\model_saf_dtl;
use App\Models\model_saf_owner_detail;
use App\Models\model_emp_details;
use App\Models\model_prop_owner_detail;
use App\Models\model_ward_permission;
use App\Models\model_ward_mstr;
use App\Models\TradeTransactionModel;
use App\Models\TradeApplyLicenceModel;
use App\Models\model_trade_licence_owner_name;
use App\Models\Water_Transaction_Model;
use App\Models\WaterApplyNewConnectionModel;
use App\Models\water_applicant_details_model;
use App\Models\water_consumer_details_model;
use App\Models\WaterConsumerModel;
use App\Models\model_tran_mode_mstr;

class CollectionModeWiseMobi extends MobiController
{
    protected $db;
    protected $dbSystem;
    protected $trade;
    protected $water;
    protected $model_transaction;
    protected $model_prop_dtl;
    protected $model_saf_dtl;
    protected $model_saf_owner_detail;
    protected $model_emp_details;
    protected $model_prop_owner_detail;
    protected $model_ward_permission;
    protected $model_ward_mstr;
    protected $TradeTransactionModel;
    protected $TradeApplyLicenceModel;
    protected $model_trade_licence_owner_name;
    protected $Water_Transaction_Model;
    protected $WaterApplyNewConnectionModel;
    protected $water_applicant_details_model;
    protected $water_consumer_details_model;
    protected $WaterConsumerModel;
    protected $model_tran_mode_mstr;
    public function __construct(){
        parent::__construct();
        helper(['db_helper','form']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name); 
        }
        if($db_name = dbSystem()){
            $this->dbSystem = db_connect($db_name); 
        }
        if($db_name = dbConfig("trade")){
            $this->trade = db_connect($db_name); 
        }
        if($db_name = dbConfig("water")){
            $this->water = db_connect($db_name); 
        }
        $this->model_tran_mode_mstr = new model_tran_mode_mstr($this->db);
        $this->model_transaction = new model_transaction($this->db);
        $this->model_prop_dtl = new model_prop_dtl($this->db);
        $this->model_saf_dtl = new model_saf_dtl($this->db);
        $this->model_saf_owner_detail = new model_saf_owner_detail($this->db);
        $this->model_emp_details = new model_emp_details($this->dbSystem);
        $this->model_prop_owner_detail = new model_prop_owner_detail($this->db);
        $this->model_ward_permission = new model_ward_permission($this->dbSystem);
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->TradeTransactionModel = new TradeTransactionModel($this->trade);
        $this->TradeApplyLicenceModel = new TradeApplyLicenceModel($this->trade);
        $this->model_trade_licence_owner_name = new model_trade_licence_owner_name($this->trade);
        $this->Water_Transaction_Model = new Water_Transaction_Model($this->water);
        $this->WaterApplyNewConnectionModel = new WaterApplyNewConnectionModel($this->water);
        $this->water_applicant_details_model = new water_applicant_details_model($this->water);
        $this->water_consumer_details_model = new water_consumer_details_model($this->water);
        $this->WaterConsumerModel = new WaterConsumerModel($this->water);
    }
    public function report()
    {
        $data =(array)null;
        $session = session();
        //Employee Details
        $emp_details = $session->get('emp_details');
        $data['id'] = $emp_details['id'];
        $wardPermission = $this->model_ward_permission->getWardDataByEmpdetailsId($data['id']);
        $data['wardPermission'] =explode(",",$wardPermission['ward_mstr_id']);
        //Transaction Mode
        $data['transactionModeList'] = $this->model_tran_mode_mstr->getTranModeList();
        $tcDetails =[]; 
        $total =0;
        if($this->request->getMethod()=='post')
        {
            $data['from_date'] = $this->request->getVar('from_date');
            $data['to_date'] = $this->request->getVar('to_date');
            $data['module'] = $this->request->getVar('module');
            if($data['module']=="PROPERTY"){
                    $data['tran_mode_mstr_id'] = $this->request->getVar('payment_mode');
                    if($data['tran_mode_mstr_id']!=""){
                        $data['transactionList'] = $this->model_transaction->getModeWiseCollectionByTcByOneMode($data);
                    }else{
                        $data['transactionList'] = $this->model_transaction->getModeWiseCollectionByTcForAll($data);
                    }
                    //print_r($data['transactionList']);
                   foreach ($data['transactionList'] as $key => $value) {
                       $tcDetails[$key]['tran_date'] = $value['tran_date'];
                       $tcDetails[$key]['tran_no'] = $value['tran_no'];
                       $tcDetails[$key]['payable_amt'] = $value['payable_amt'];
                       $tcDetails[$key]['status'] = $value['status'];
                       $total = $total+$value['payable_amt'];
                       if($value['tran_type']=="Property"){
                        //get Holding No
                       $tcDetails[$key]['holding_no'] = $this->model_prop_dtl->getPropdetails($value['prop_dtl_id']);
                       //get property Owner Detais
                      $tcDetails[$key]['ownerDetails'] = $this->model_prop_owner_detail->getOwnerDataByPropDetailsId($value['prop_dtl_id']);
                       }else{
                        //get SAF No
                       $tcDetails[$key]['saf_no'] = $this->model_saf_dtl->getSafdetails($value['prop_dtl_id']);
                       //get Saf Ownere Details
                       $tcDetails[$key]['ownerDetails'] = $this->model_saf_owner_detail->getOwnerDataBySAFDetailsId($value['prop_dtl_id']);
                       }
                       //get Ward No
                       $tcDetails[$key]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($value['ward_mstr_id']);
                       //get Payment Mode
                       $tcDetails[$key]['payment_mode'] = $this->model_tran_mode_mstr->getTransactionMode($value['tran_mode_mstr_id']);
                   }
                   $data['total'] = $total;
                   $data['tcDetails'] = $tcDetails;
                   return view('report/collection_mode_wise_mobi',$data);
                }else if($data['module']=="TRADE"){
                    $data['payment_mode'] = $this->request->getVar('payment_mode');
                    if($data['payment_mode']!=""){
                        $data['transactionList'] = $this->TradeTransactionModel->getTradeModeWiseCollectionByTcByOneMode($data);
                    }else{
                        $data['transactionList'] = $this->TradeTransactionModel->getDailyCollectionByTaxCollector($data);
                    }
                    foreach ($data['transactionList'] as $key => $value) {
                       $tcDetails[$key]['transaction_date'] = $value['transaction_date'];
                       $tcDetails[$key]['transaction_no'] = $value['transaction_no'];
                       $tcDetails[$key]['paid_amount'] = $value['paid_amount'];
                       $tcDetails[$key]['payment_mode'] = $value['payment_mode'];
                       $tcDetails[$key]['status'] = $value['status'];
                       $total = $total+$value['paid_amount'];
                       //get Application No
                       $tcDetails[$key]['application_no'] = $this->TradeApplyLicenceModel->getApplicationNo($value['related_id']);
                       //get Owner Details
                        $tcDetails[$key]['ownerDetails'] = $this->model_trade_licence_owner_name->getOwnerDetails($value['related_id']);
                        //get Ward No
                       $tcDetails[$key]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($value['ward_mstr_id']);
                    }
                    $data['total'] = $total;
                    $data['tcDetails'] = $tcDetails;
                   return view('report/collection_mode_wise_trade_mobi',$data);
                }else if($data['module']=="WATER"){
                    $data['payment_mode'] = $this->request->getVar('payment_mode');
                    if($data['payment_mode']!=""){
                        $data['transactionList'] = $this->Water_Transaction_Model->getDailyCollectionByTaxCollectorByOneMode($data);
                    }else{
                        $data['transactionList'] = $this->Water_Transaction_Model->getDailyCollectionByTaxCollector($data);
                    }
                    foreach ($data['transactionList'] as $key => $value) {
                        $tcDetails[$key]['transaction_date'] = $value['transaction_date'];
                        $tcDetails[$key]['transaction_no'] = $value['transaction_no'];
                        $tcDetails[$key]['payment_mode'] = $value['payment_mode'];
                        $tcDetails[$key]['paid_amount'] = $value['paid_amount'];
                        $total = $total+$value['paid_amount'];
                        //get Ward No
                        $tcDetails[$key]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($value['ward_mstr_id']); 
                        if($value['transaction_type']=="Demand Collection"){
                            //get Consumer Details
                           $tcDetails[$key]['consumer_no'] = $this->WaterConsumerModel->getConsumerNo($value['related_id']);
                           $tcDetails[$key]['ownerDetails'] = $this->water_consumer_details_model->getConsumerDetails($value['related_id']);
                        }else{
                            //get Applicant Details
                            $tcDetails[$key]['application_no'] = $this->WaterApplyNewConnectionModel->getApplicationNo($value['related_id']);
                            $tcDetails[$key]['ownerDetails'] = $this->water_applicant_details_model->getApplicantDetails($value['related_id']);
                        }
                }
                $data['total'] = $total;
                $data['tcDetails'] = $tcDetails;
                return view('report/collection_mode_wise_water_mobi',$data);
            }
        }else{
            return view('report/collection_mode_wise_mobi',$data);
        }
    }
}
?>
