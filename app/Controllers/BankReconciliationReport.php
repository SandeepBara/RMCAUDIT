<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\model_bank_recancilation;
use App\Models\model_cheque_details;
use App\Models\model_transaction;
use App\Models\model_prop_dtl;
use App\Models\model_prop_owner_detail;
use App\Models\model_saf_dtl;
use App\Models\model_saf_owner_detail;
use App\Models\Water_Cheque_Details_Model;
use App\Models\Water_Transaction_Model;
use App\Models\water_bank_reconcilation_model;
use App\Models\WaterApplyNewConnectionModel;
use App\Models\water_applicant_details_model;
use App\Models\water_consumer_details_model;
use App\Models\WaterConsumerModel;
use App\Models\TradeChequeDtlModel;
use App\Models\TradeTransactionModel;
use App\Models\TradeApplyLicenceModel;
use App\Models\TradeBankRecancilationModel;
use App\Models\model_trade_licence_owner_name;

class BankReconciliationReport extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $water;
    protected $trade;
    protected $model_ward_mstr;
    protected $model_bank_recancilation;
    protected $model_cheque_details;
    protected $model_transaction;
    protected $model_prop_dtl;
    protected $model_prop_owner_detail;
    protected $model_saf_dtl;
    protected $model_saf_owner_detail;
    protected $Water_Cheque_Details_Model;
    protected $Water_Transaction_Model;
    protected $water_bank_reconcilation_model;
    protected $WaterApplyNewConnectionModel;
    protected $water_applicant_details_model;
    protected $water_consumer_details_model;
    protected $WaterConsumerModel;
    protected $TradeChequeDtlModel;
    protected $TradeTransactionModel;
    protected $TradeApplyLicenceModel;
    protected $TradeBankRecancilationModel;
    protected $model_trade_licence_owner_name;
    public function __construct(){
        parent::__construct();
        helper(['db_helper','form']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name); 
        }
        if($db_name = dbSystem()){
            $this->dbSystem = db_connect($db_name); 
        }
        if($db_name = dbConfig("water")){
            $this->water = db_connect($db_name); 
        }
        if($db_name = dbConfig("trade")){
            $this->trade = db_connect($db_name); 
        }
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_bank_recancilation = new model_bank_recancilation($this->db);
        $this->model_cheque_details = new model_cheque_details($this->db);
        $this->model_transaction = new model_transaction($this->db);
        $this->model_prop_dtl = new model_prop_dtl($this->db);
        $this->model_saf_dtl = new model_saf_dtl($this->db);
        $this->model_prop_owner_detail = new model_prop_owner_detail($this->db);
        $this->model_saf_owner_detail = new model_saf_owner_detail($this->db);
        $this->Water_Cheque_Details_Model = new Water_Cheque_Details_Model($this->water);
        $this->Water_Transaction_Model = new Water_Transaction_Model($this->water);
        $this->water_bank_reconcilation_model = new water_bank_reconcilation_model($this->water);
        $this->WaterApplyNewConnectionModel = new WaterApplyNewConnectionModel($this->water);
        $this->water_applicant_details_model = new water_applicant_details_model($this->water);
        $this->water_consumer_details_model = new water_consumer_details_model($this->water);
        $this->WaterConsumerModel = new WaterConsumerModel($this->water);
        $this->TradeChequeDtlModel = new TradeChequeDtlModel($this->trade);
        $this->TradeTransactionModel = new TradeTransactionModel($this->trade);
        $this->TradeApplyLicenceModel = new TradeApplyLicenceModel($this->trade);
        $this->TradeBankRecancilationModel = new TradeBankRecancilationModel($this->trade);
        $this->model_trade_licence_owner_name = new model_trade_licence_owner_name($this->trade);
    }

    function __destruct() {
		$this->db->close();
		$this->dbSystem->close();
		$this->water->close();
		$this->trade->close();
	}
    
    public function generation()
    {
        $data =(array)null;
        $session = session();
        $ulb_dtl = $session->get('ulb_dtl');
        $ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];
        $bankCancelData=[];
        if($this->request->getMethod()=='post')
        {
            $data['from_date'] = $this->request->getVar('from_date');
            $data['to_date'] = $this->request->getVar('to_date');
            $data['module'] = $this->request->getVar('module');
            if($data['module']=="PROPERTY")
            {
               $chequeDetails = $this->model_cheque_details->getAllChequeDetails($data);
               foreach ($chequeDetails as $key => $value) {
                   $bankCancelData[$key]['branch_name'] = $value['branch_name'];
                   $bankCancelData[$key]['bank_name'] = $value['bank_name'];
                   $bankCancelData[$key]['cheque_no'] = $value['cheque_no'];
                   //Transaction details
                   $transactionDetails = $this->model_transaction->getTransactionDetailsForChequeBounce($value['transaction_id']);
                   $bankCancelData[$key]['tran_no'] = $transactionDetails['tran_no'];
                   $bankCancelData[$key]['tran_date'] = $transactionDetails['tran_date'];
                   //Bank Recancilation
                   $bankRecancilation = $this->model_bank_recancilation->getDetails($value['id']);
                   $bankCancelData[$key]['amount'] = $bankRecancilation['amount'];
                   $bankCancelData[$key]['reason'] = $bankRecancilation['reason'];
                   $bankCancelData[$key]['cancel_date'] = $bankRecancilation['cancel_date'];
                   //get Ward No
                   $bankCancelData[$key]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($bankRecancilation['ward_mstr_id']);
                   //Owner Details
                   if($bankRecancilation['prop_type']=="Property"){
                        $propDetails = $this->model_prop_dtl->getPropDetailsByPropId($bankRecancilation['related_id']);
                        $bankCancelData[$key]['holding_no'] = $propDetails['holding_no'];
                        $bankCancelData[$key]['owner'] = $this->model_prop_owner_detail->getPropertyOwnerDetails($propDetails['id']);
                        $ownerMobile = $this->model_prop_owner_detail->property($propDetails['id']);
                        $bankCancelData[$key]['mobile_no'] = $ownerMobile ['mobile_no'];
                   }else{
                        $safDetails = $this->model_saf_dtl->getSafDetailsBySafId($bankRecancilation['related_id']);
                        $bankCancelData[$key]['saf_no'] = $safDetails['saf_no'];
                        $bankCancelData[$key]['owner'] = $this->model_saf_owner_detail->getSafOwnerDetails($safDetails['id']);
                        $ownerMobile = $this->model_saf_owner_detail->saf($safDetails['id']);
                        $bankCancelData[$key]['mobile_no'] = $ownerMobile['mobile_no'];
                   }
               }
               $data['bankCancelData']= $bankCancelData;
               return view('report/bank_reconciliation_report',$data);  
            }else if($data['module']=="WATER"){
                $chequeDetails = $this->Water_Cheque_Details_Model->getAllChequeDetailsByDate($data);
                foreach ($chequeDetails as $key => $value) {
                   $bankCancelData[$key]['branch_name'] = $value['branch_name'];
                   $bankCancelData[$key]['bank_name'] = $value['bank_name'];
                   $bankCancelData[$key]['cheque_no'] = $value['cheque_no'];
                   $transactionDetails = $this->Water_Transaction_Model->getAllTransactionById($value['transaction_id']);
                   $bankCancelData[$key]['tran_date'] = $transactionDetails['transaction_date'];
                   $bankCancelData[$key]['tran_no'] = $transactionDetails['transaction_no'];
                   //get Ward No
                   $bankCancelData[$key]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($transactionDetails['ward_mstr_id']);
                   //Bank Recancilation
                   $bankRecancilation = $this->water_bank_reconcilation_model->geBankRecancilationtDetails($value['id']);
                   $bankCancelData[$key]['amount'] = $bankRecancilation['amount'];
                   $bankCancelData[$key]['reason'] = $bankRecancilation['reason'];
                   $bankCancelData[$key]['cancel_date'] = $bankRecancilation['amount_receive_date'];
                   if($bankRecancilation['consumer_type']=="Applicant"){
                    $applyConnectionDetails = $this->WaterApplyNewConnectionModel->getApplyConnectionDetails($bankRecancilation ['related_id']);
                    if($applyConnectionDetails['holding_no']!=""){
                        $bankCancelData[$key]['holding_no'] = $applyConnectionDetails['holding_no'];
                    }else{
                        $bankCancelData[$key]['saf_no'] = $applyConnectionDetails['saf_no'];
                    }
                    //Get Applicant Details
                    $bankCancelData[$key]['owner'] =$this->water_applicant_details_model->getApplicantNameDetails($applyConnectionDetails['id']);
                    $applicantMobile =$this->water_applicant_details_model->getApplicantMObileNo($applyConnectionDetails['id']);
                    $bankCancelData[$key]['mobile_no'] = $applicantMobile['mobile_no'];
                   }else{ //Consumer 
                        $consume = $this->WaterConsumerModel->getConsumer($bankRecancilation ['related_id']);
                        $applyConnectionDetails = $this->WaterApplyNewConnectionModel->getApplyConnectionDetails($consume['apply_connection_id']);
                        if($applyConnectionDetails['holding_no']!=""){
                            $bankCancelData[$key]['holding_no'] = $applyConnectionDetails['holding_no'];
                        }else{
                            $bankCancelData[$key]['saf_no'] = $applyConnectionDetails['saf_no'];
                        }
                        //consmer Details 
                        $bankCancelData[$key]['owner'] = $this->water_consumer_details_model->getConsumerName($consume['id']);
                        $consumerMobile = $this->water_consumer_details_model->getMobileNo($consume['id']);
                        $bankCancelData[$key]['mobile_no'] = $consumerMobile['mobile_no'];
                    }

                }
               $data['bankCancelData']= $bankCancelData;
               return view('report/bank_reconciliation_report',$data); 
            }else if($data['module']=="TRADE"){
                $chequeDetails = $this->TradeChequeDtlModel->getTradeChequeDetails($data);
                foreach ($chequeDetails as $key => $value) {
                    $bankCancelData[$key]['branch_name'] = $value['branch_name'];
                    $bankCancelData[$key]['bank_name'] = $value['bank_name'];
                    $bankCancelData[$key]['cheque_no'] = $value['cheque_no'];
                    $transactionDetails = $this->TradeTransactionModel->getTradeChequeDetailsById($value['transaction_id']);
                    $bankCancelData[$key]['tran_date'] = $transactionDetails['transaction_date'];
                    $bankCancelData[$key]['tran_no'] = $transactionDetails['transaction_no'];
                    //get Ward No
                   $bankCancelData[$key]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($transactionDetails['ward_mstr_id']);
                   //Bank Recancilation
                   $bankRecancilation = $this->TradeBankRecancilationModel->getBankRecancilationtDetails($value['id']);
                   $bankCancelData[$key]['amount'] = $bankRecancilation['amount'];
                   $bankCancelData[$key]['reason'] = $bankRecancilation['reason'];
                   $bankCancelData[$key]['cancel_date'] = $bankRecancilation['amount_receive_date'];
                   $applyLicenceDetails = $this->TradeApplyLicenceModel->applyLicenceDetails($bankRecancilation['related_id']);
                   if($applyLicenceDetails['property_type']=="PROPERTY"){
                        $propDetails = $this->model_prop_dtl->getPropDetailsByPropId($applyLicenceDetails['prop_dtl_id']);
                        $bankCancelData[$key]['holding_no'] = $propDetails['holding_no'];
                        
                   }else{
                        $safDetails = $this->model_saf_dtl->getSafDetailsBySafId($applyLicenceDetails['prop_dtl_id']);
                        $bankCancelData[$key]['saf_no'] = $safDetails['saf_no'];
                   }
                   $bankCancelData[$key]['owner'] = $this->model_trade_licence_owner_name->getOwnerName($applyLicenceDetails['id']);
                   $ownerMobile = $this->model_trade_licence_owner_name->getOwnerMobileNo($applyLicenceDetails['id']);
                   $bankCancelData[$key]['mobile_no'] = $ownerMobile['mobile'];
                }
                $data['bankCancelData']= $bankCancelData;
               return view('report/bank_reconciliation_report',$data); 
            }
        }
        else
        {
            return view('report/bank_reconciliation_report',$data);
        } 
    }
}
?>
