<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Water_Cheque_Details_Model;
use App\Models\Water_Transaction_Model;
use App\Models\water_applicant_details_model;
use App\Models\water_bank_reconcilation_model;
use App\Models\water_consumer_details_model;
use App\Models\model_transaction;
use App\Models\model_prop_dtl;
use App\Models\model_saf_dtl;
use App\Models\model_saf_owner_detail;
use App\Models\model_view_transaction;
use App\Models\model_prop_owner_detail;
use App\Models\model_cheque_details;
use App\Models\model_bank_recancilation;
use App\Models\model_saf_collection;
use App\Models\model_collection;
use App\Models\model_saf_demand;
use App\Models\model_prop_demand;
use App\Models\model_level_pending_dtl;
use App\Models\water_level_pending_model;
use App\Models\TradeTransactionModel;
use App\Models\TradeChequeDtlModel;
use App\Models\TradeBankRecancilationModel;
use App\Models\model_trade_level_pending_dtl;
use App\Models\WaterApplyNewConnectionModel;
use App\Models\TradeApplyLicenceModel;
use App\Models\WaterConnectionChargeModel;
use App\Models\WaterPenaltyModel;
use App\Models\WaterSearchConsumerModel;


class BankReconciliationWaterList extends AlphaController
{   
    protected $db;
    protected $water;
    protected $trade;
    protected $Water_Cheque_Details_Model;
    protected $Water_Transaction_Model;
    protected $water_applicant_details_model;
    protected $water_bank_reconcilation_model;
    protected $water_consumer_details_model;
    protected $model_transaction;
    protected $model_prop_dtl;
    protected $model_saf_dtl;
    protected $model_saf_owner_detail;
    protected $model_view_transaction;
    protected $model_prop_owner_detail;
    protected $model_cheque_details;
    protected $model_bank_recancilation;
    protected $model_saf_collection;
    protected $model_collection;
    protected $model_saf_demand;
    protected $model_prop_demand;
    protected $model_level_pending_dtl;
    protected $water_level_pending_model;
    protected $TradeTransactionModel;
    protected $TradeChequeDtlModel;
    protected $TradeBankRecancilationModel;
    protected $model_trade_level_pending_dtl;
    protected $WaterApplyNewConnectionModel;
    protected $TradeApplyLicenceModel;
    protected $WaterConnectionChargeModel;

    public function __construct(){
        parent::__construct();
    	helper(['db_helper','form']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name); 
        }
        if($db_name = dbConfig("water")){
            $this->water = db_connect($db_name);            
        }
        if($db_name = dbConfig("trade")){
            $this->trade = db_connect($db_name);            
        }
        $this->Water_Cheque_Details_Model = new Water_Cheque_Details_Model($this->water);
        $this->Water_Transaction_Model = new Water_Transaction_Model($this->water);
        $this->water_applicant_details_model = new water_applicant_details_model($this->water);
        $this->water_bank_reconcilation_model = new water_bank_reconcilation_model($this->water);
        $this->water_consumer_details_model = new water_consumer_details_model($this->water);
        $this->water_level_pending_model = new water_level_pending_model($this->water);
        $this->WaterApplyNewConnectionModel = new WaterApplyNewConnectionModel($this->water);
        $this->WaterConnectionChargeModel = new WaterConnectionChargeModel($this->water);
        $this->model_transaction = new model_transaction($this->db);
        $this->model_prop_dtl = new model_prop_dtl($this->db);
        $this->model_saf_dtl = new model_saf_dtl($this->db);
        $this->model_saf_owner_detail = new model_saf_owner_detail($this->db);
        $this->model_view_transaction = new model_view_transaction($this->db);
        $this->model_prop_owner_detail = new model_prop_owner_detail($this->db);
        $this->model_cheque_details = new model_cheque_details($this->db);
        $this->model_bank_recancilation = new model_bank_recancilation($this->db);
        $this->model_saf_collection = new model_saf_collection($this->db);
        $this->model_collection = new model_collection($this->db);
        $this->model_saf_demand = new model_saf_demand($this->db);
        $this->model_prop_demand = new model_prop_demand($this->db);
        $this->model_level_pending_dtl = new model_level_pending_dtl($this->db);
        $this->TradeChequeDtlModel = new TradeChequeDtlModel($this->trade);
        $this->TradeTransactionModel = new TradeTransactionModel($this->trade);
        $this->TradeBankRecancilationModel = new TradeBankRecancilationModel($this->trade);
        $this->model_trade_level_pending_dtl = new model_trade_level_pending_dtl($this->trade);
        $this->TradeApplyLicenceModel = new TradeApplyLicenceModel($this->trade);
        $this->water_penalty_model=new WaterPenaltyModel($this->water);
        $this->search_applicant_model=new WaterSearchConsumerModel($this->water);

    }

    function __destruct() {
		$this->db->close();
		$this->water->close();
		$this->trade->close();
	}

    public function detail($module=null)
    {
        $data =(array)null;
        $chequeDetailsList = [];
        $ward_mstr_id ="";
        if($this->request->getMethod()=='post')
        {
            //Cheque Details
            $data['from_date'] = $this->request->getVar('from_date');
            $data['to_date'] = $this->request->getVar('to_date');
            $data['module'] = $this->request->getVar('module');
            if($data['module']=="WATER"){
                //$data['chequeDetails'] = $this->Water_Cheque_Details_Model->getChequeDetailsByDate($data);
                $data['chequeDetails'] = $this->Water_Transaction_Model->getChequeTransactionDetails($data['from_date'],$data['to_date']);

                return view('water/water_connection/bank_reconciliation_water_list', $data);
            }
            else if($data['module']=="PROPERTY"){
                 $data['chequeDetails'] = $this->model_cheque_details->chequeDetails($data);
               return view('water/water_connection/bank_reconciliation_water_list', $data);
            }
            else if($data['module']=="TRADE"){
               $data['chequeDetails'] = $this->TradeChequeDtlModel->tradeChequeDetailsByDate($data);
               return view('water/water_connection/bank_reconciliation_water_list', $data);
            }
        }
        else
        { 
            return view('water/water_connection/bank_reconciliation_water_list', $data);
        } 
    }
    public function cheque(){
        $session = session();
        $emp_details = $session->get('emp_details');
        $emp_details_id = $emp_details['id'];
        if($this->request->getMethod()=='post'){
            $data['from_date'] = $this->request->getVar('from_date');
            $data['to_date'] = $this->request->getVar('to_date');
            $data['module'] = $this->request->getVar('mod');
           
            if($data['module']=="WATER"){
                $input = [
                    'reason' => $this->request->getVar('reason'),
                    'amount_receive_date' =>date('Y-m-d'),
                    'created_on' =>date('Y-m-d H:i:s'),
                    'cheque_no' => $this->request->getVar('cheque_no'),
                    'status' => $this->request->getVar('status'),
                    'amount' => $this->request->getVar('amount'),
                    'cheque_dtl_id' => $this->request->getVar('id')
                ];
                if($input['status']==1){
                    $input['amount']=0;
                }
                $input['emp_details_id'] = $emp_details_id;
                $cheque_details = $this->Water_Cheque_Details_Model->getChequeDetailsById($input['cheque_dtl_id']);
                $input['transaction_id'] = $cheque_details['transaction_id'];
                //start
                $transaction = $this->Water_Transaction_Model->getTransactionDetails($input['transaction_id']);
                $input['related_id'] = $transaction['related_id'];
                if($transaction['transaction_type']=='NEW CONNECTION' or $transaction['transaction_type']=='SITE INSPECTION'){
                    
                    $input['consumer_type'] ="Applicant";
                }
                else{
                    $input['consumer_type'] ="Consumer";
                }
                //End
                //Start
                //Prevent double Posting
                if($prevent = $this->water_bank_reconcilation_model->preventDouble($input['cheque_dtl_id'])){
                    echo '<script>alert("Cheque Verification Already Done!!!")</script>'; 
                   // $data['chequeDetails'] = $this->Water_Cheque_Details_Model->getChequeDetailsByDate($data);
                      $data['chequeDetails'] = $this->Water_Transaction_Model->getChequeTransactionDetails($data['from_date'],$data['to_date']);

                    return view('water/water_connection/bank_reconciliation_water_list', $data);
                }else{
                    $insert_id = $this->water_bank_reconcilation_model->insertDataCheque($input);
                    $penalty_cheque_bounce=array();
                    $penalty_cheque_bounce['related_id']=$input['related_id'];
                    $penalty_cheque_bounce['type']=$input['consumer_type'];
                    $penalty_cheque_bounce['penalty_amt']=$input['amount'];
                    $penalty_cheque_bounce['created_on']=date('Y-m-d H:i:s');
                    $penalty_cheque_bounce['penalty_type']=1;
                    
                    $this->water_penalty_model->insertData($penalty_cheque_bounce);
                    

                    if($insert_id){
                        if($input['status']==2){
                            //Update transaction status
                            $this->Water_Transaction_Model->updateStatus($cheque_details['transaction_id']);
                            //update cheque details status 
                            $this->Water_Cheque_Details_Model->updateStatus($cheque_details['id']);
                            //Check transaction id is Exists Or Not In tbl Connection Charge 
                            if($existsId = $this->WaterConnectionChargeModel->getIdUsingTransactionId($cheque_details['transaction_id'])){
                                $this->WaterConnectionChargeModel->updateStatus($existsId['id']);
                            }
                            //Update Apply payment status in new connection Apply table
                            if($transaction['transaction_type']=='NEW CONNECTION' or $transaction['transaction_type']=='SITE INSPECTION'){
                                $this->WaterApplyNewConnectionModel->updateApplyNewConnectionPaymentStatus($input['related_id']);
                            }
                          //$data['chequeDetails'] = $this->Water_Cheque_Details_Model->getChequeDetailsByDate($data);
                          $data['chequeDetails'] = $this->Water_Transaction_Model->getChequeTransactionDetails($data['from_date'],$data['to_date']);
                          flashToast('bank_cancel', 'Cheque Cancelled Successfully!!');
                          return view('water/water_connection/bank_reconciliation_water_list', $data);
                        }
                        else{
                            //Update transaction status
                            $this->Water_Transaction_Model->updateChequeClearStatus($cheque_details['transaction_id']);
                            //update cheque details status 
                            $this->Water_Cheque_Details_Model->updateChequeClearStatus($cheque_details['id']);
                            //insert data into level pendind details table
                            $transactionData = $this->Water_Transaction_Model->getTransactionLevelPending($cheque_details['transaction_id']);
                            if($transactionData['transaction_type']=="NEW CONNECTION" or $transaction['transaction_type']=='SITE INSPECTION'){
                                     $leveldata = [
                                         'apply_connection_id' => $transactionData['related_id'],
                                         'sender_user_type_id' => 0,
                                         'receiver_user_type_id' =>12,
                                         'forward_date' => date('Y-m-d'),
                                         'forward_time' => date('H:i:s'),
                                         'created_on' =>date('Y-m-d H:i:s'),
                                         'emp_details_id' => $emp_details_id
                                        ];
                                $this->water_level_pending_model->insertLevelPendingData($leveldata);
                                //Update Apply payment status in new connection Apply table
                                $this->WaterApplyNewConnectionModel->updateApplyNewConnectionPaymentStatusClear($input['related_id']);
                            }else if($transactionData['transaction_type']=="Site Inspection"){
                                $leveldata = [
                                         'apply_connection_id' => $transactionData['related_id'],
                                         'sender_user_type_id' =>13,
                                         'receiver_user_type_id' =>14,
                                         'forward_date' => date('Y-m-d'),
                                         'forward_time' => date('H:i:s'),
                                         'created_on' =>date('Y-m-d H:i:s'),
                                         'emp_details_id' => $emp_details_id
                                        ];
                                $this->water_level_pending_model->insertLevelPendingData($leveldata);
                            }
                            //$data['chequeDetails'] = $this->Water_Cheque_Details_Model->getChequeDetailsByDate($data);
                            $data['chequeDetails'] = $this->Water_Transaction_Model->getChequeTransactionDetails($data['from_date'],$data['to_date']);

                            flashToast('bank_cancel', 'Cheque Clear Successfully!!');
                            return view('water/water_connection/bank_reconciliation_water_list', $data);
                        }
                    } 
                }
                //End
            }
            else if($data['module']=="PROPERTY"){
                    $input = [
                        'reason' => $this->request->getVar('reason'),
                        'cancel_date' =>date('Y-m-d'),
                        'created_on' =>date('Y-m-d H:i:s'),
                        'status' => $this->request->getVar('status'),
                        'amount' => $this->request->getVar('amount'),
                        'cheque_dtl_id' => $this->request->getVar('id')
                    ];
                if($input['status']==1){ 
                    $input['amount']=0;
                }
                $chequeDetails = $this->model_cheque_details->getChequeDetailsById($input['cheque_dtl_id']);
                $verify_date = date('Y-m-d');
                $transactionDetails = $this->model_transaction->getTransactionDetails($chequeDetails['transaction_id']);
                $input['transaction_id'] =0;
                $input['prop_type'] = $transactionDetails['tran_type'];
                $input['prop_dtl_id'] = $transactionDetails['prop_dtl_id'];
                $input['ward_mstr_id'] = $transactionDetails['ward_mstr_id'];
                $input['emp_details_id'] = $emp_details_id;
                $saf_dtl_id = $transactionDetails['prop_dtl_id'];
                //Prevent double Posting
                if($prevent = $this->model_bank_recancilation->preventDouble($input['cheque_dtl_id'])){
                    echo '<script>alert("Cheque Verification Already Done!!!")</script>'; 
                    $data['chequeDetails'] = $this->model_cheque_details->chequeDetails($data);
                    return view('water/water_connection/bank_reconciliation_water_list', $data);
                }else{
                        $insert_id = $this->model_bank_recancilation->insertData($input);
                        if($insert_id){
                            if($input['status']==1){ //Cheque Clear
                                $this->model_transaction->updateStatusClear($chequeDetails['transaction_id']);
                                if($transactionDetails['tran_type']=="Saf")
                                {
                                    //Get Saf Details for Payment status and Document upload status
                                    $safPayment_Document_Status = $this->model_saf_dtl->safPayment_Document_Status($input['prop_dtl_id']);
                                    if($safPayment_Document_Status['payment_status']==1 && $safPayment_Document_Status['doc_upload_status']==1){
                                          $leveldata = [
                                         'saf_dtl_id' => $input['prop_dtl_id'],
                                         'sender_user_type_id' => 0,
                                         'receiver_user_type_id' => 6,
                                         'forward_date' => date('Y-m-d'),
                                         'forward_time' => date('H:i:s'),
                                         'created_on' =>date('Y-m-d H:i:s')
                                        ];
                                    $level_pending_insrt=$this->model_level_pending_dtl->insrtlevelpendingdtl($leveldata);
                                    }
                                } 
                                //Update cheque_details bounce status
                                $this->model_cheque_details->updateBounceStatusClear($input['cheque_dtl_id']);
                                flashToast('bank_cancel','Cheque Clear Successfully!!');
                                $data['chequeDetails'] = $this->model_cheque_details->chequeDetails($data);
                                return view('water/water_connection/bank_reconciliation_water_list', $data);
                            }
                            else
                            {
                                $this->model_transaction->updateStatusNotClear($chequeDetails['transaction_id']);
                                if($transactionDetails['tran_type']=="Property")
                                {
                                  //Get Collection Details
                                   $collectionDetails = $this->model_collection->collectionDetails($chequeDetails['transaction_id']);
                                   //Update deactive status
                                   foreach ($collectionDetails as $value) {
                                        $this->model_prop_demand->updatePaidStatusNotClear($value['prop_demand_id']); 
                                   }
                                   $this->model_collection->updateStatusNotClear($chequeDetails['transaction_id']);
                                }
                                else
                                {
                                    //Saf Collection Details
                                    $safDetails = $this->model_saf_collection->safDetails($chequeDetails['transaction_id']);
                                    //Update Deactive status
                                    foreach ($safDetails as $value) {
                                       $getAmountNotClear = $this->model_saf_demand->getAmountNotClear($value['saf_demand_id']);
                                        $this->model_saf_demand->updatePaidStatusNotClear($value['saf_demand_id'],$getAmountNotClear['amount']);
                                    }
                                    //Update Payment Status Zero
                                    $this->model_saf_dtl->updatePaymentStatus($transactionDetails['prop_dtl_id']);
                                    $this->model_saf_collection->updateStatusNotClear($chequeDetails['transaction_id']);
                                } 
                                //Update cheque_details bounce status
                                $this->model_cheque_details->updateBounceStatusNotClear($chequeDetails['transaction_id']);
                               flashToast('bank_cancel', 'Cheque Canceled Successfully!!');
                               $data['chequeDetails'] = $this->model_cheque_details->chequeDetails($data);
                               return view('water/water_connection/bank_reconciliation_water_list', $data);
                            }
                        } 
                }
                
            }
            else if($data['module']=="TRADE"){
                $input = [
                    'reason' => $this->request->getVar('reason'),
                    'amount_receive_date' =>date('Y-m-d'),
                    'created_on' =>date('Y-m-d H:i:s'),
                    'cheque_no' => $this->request->getVar('cheque_no'),
                    'status' => $this->request->getVar('status'),
                    'amount' => $this->request->getVar('amount'),
                    'cheque_dtl_id' => $this->request->getVar('id'),
                    'type'=> "Apply"
                ];
                if($input['status']==1){
                    $input['amount']=0;
                }
                $input['emp_details_id'] = $emp_details_id;
                $cheque_details = $this->TradeChequeDtlModel->getTradeChequeDetailsById($input['cheque_dtl_id']);
                $input['transaction_id'] = $cheque_details['transaction_id'];
                //start
                $transaction = $this->TradeTransactionModel->getTransactionDetails($input['transaction_id']);
                $input['related_id'] = $transaction['related_id'];
                //start, insert data
                //Prevent double Posting
                if($prevent = $this->TradeBankRecancilationModel->preventDouble($input['cheque_dtl_id'])){
                    echo '<script>alert("Cheque Verification Already Done!!!")</script>'; 
                    $data['chequeDetails'] = $this->TradeChequeDtlModel->tradeChequeDetailsByDate($data);
                    return view('water/water_connection/bank_reconciliation_water_list', $data);
                }else{
                    $insert_id = $this->TradeBankRecancilationModel->insertTradeDataCheque($input);
                    if($insert_id){
                        if($input['status']==1){
                            //Update transaction status
                            $this->TradeTransactionModel->updateTradeTransactionClearStatus($cheque_details['transaction_id']);
                            //update payment status 
                            $this->TradeApplyLicenceModel->updateApplyLicencePaymentStatusClear($input['related_id']);
                            //update cheque details status 
                            $this->TradeChequeDtlModel->updateTradeChequeClearStatus($cheque_details['id']);
                            //insert data into level pendind details table
                             $leveldata = [
                                         'apply_licence_id' => $transaction['related_id'],
                                         'sender_user_type_id' => 0,
                                         'receiver_user_type_id' =>17,
                                         'forward_date' => date('Y-m-d'),
                                         'forward_time' => date('H:i:s'),
                                         'created_on' =>date('Y-m-d H:i:s'),
                                         'emp_details_id' => $emp_details_id
                                        ];
                            $this->model_trade_level_pending_dtl->insrtTradeLevelPendingDtl($leveldata);
                            flashToast('bank_cancel', 'Cheque Clear Successfully!!');
                            $data['chequeDetails'] = $this->TradeChequeDtlModel->tradeChequeDetailsByDate($data);
                            return view('water/water_connection/bank_reconciliation_water_list', $data);
                        }else{
                             //Update transaction status
                            $this->TradeTransactionModel->updateTradeTransactionNotClearStatus($cheque_details['transaction_id']);
                            //update payment status 
                            $this->TradeApplyLicenceModel->updateApplyLicencePaymentStatusNotClear($input['related_id']);
                            //update cheque details status 
                            $this->TradeChequeDtlModel->updateTradeNotClearStatus($cheque_details['id']);
                            flashToast('bank_cancel', 'Cheque Canceled Successfully!!');
                            $data['chequeDetails'] = $this->TradeChequeDtlModel->tradeChequeDetailsByDate($data);
                            return view('water/water_connection/bank_reconciliation_water_list', $data);
                        }
                    }else{
                        flashToast('bank_cancel', 'Something Is Wrong!!');
                        $data['chequeDetails'] = $this->TradeChequeDtlModel->tradeChequeDetailsByDate($data);
                        return view('water/water_connection/bank_reconciliation_water_list', $data);
                    }
                }
                //End
            }
        }
    }

 
  

}
?>
