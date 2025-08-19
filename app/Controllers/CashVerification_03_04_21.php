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


class CashVerification extends AlphaController
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
        $this->model_cash_verification_mstr = new model_cash_verification_mstr($this->dbSystem);
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
    public function details($id=null)
    {
        $session=session();
        //Get TC Details List
        $data['emplist']=$this->model_emp_details->getTCList();
        /*print_r($data['emplist']) ;*/
        $collectionDetails = [];
      if($this->request->getMethod()=='post'){
        //get Total Collection
        $total =0;
        $data['employee_id'] = $this->request->getVar('employee_id');
        $data['tran_date'] = $this->request->getVar('tran_date');
        $session->set('dateData', $data['tran_date']);
        if($data['employee_id']==""){ //All Employee
            foreach ($data['emplist'] as $key => $value) {
                 //Property
                $totalAmount = $this->model_transaction->getTotalAmount(md5($value['id']),$data['tran_date']);
                 //Water Collection Amount
                $tradeAmount = $this->TradeTransactionModel->getTotalAmount(md5($value['id']),$data['tran_date']);
                 //Trade Collection Amount
                $waterAmount = $this->Water_Transaction_Model->getTotalAmount(md5($value['id']),$data['tran_date']);
                //echo $waterAmount;
                $total = $totalAmount+$tradeAmount+$waterAmount;
                $collectionDetails[$key]['total'] = $total; 
                //get Employee Name
                $empName = $this->model_emp_details->getEmpDetailsById(md5($value['id']));
                $collectionDetails[$key]['id'] = $value['id'];
                $collectionDetails[$key]['tran_date'] = $data['tran_date'];
                $collectionDetails[$key]['emp_name'] = $empName['emp_name'];
                $collectionDetails[$key]['last_name'] = $empName['last_name'];
            }
            $data['collectionDetails'] = $collectionDetails;
        }else{ 
             //Property
            $totalAmount = $this->model_transaction->getTotalAmount(md5($data['employee_id']),$data['tran_date']);
             //Water Collection Amount
            $tradeAmount = $this->TradeTransactionModel->getTotalAmount(md5($data['employee_id']),$data['tran_date']);
             //Trade Collection Amount
            $waterAmount = $this->Water_Transaction_Model->getTotalAmount(md5($data['employee_id']),$data['tran_date']);
            //echo $waterAmount;
            $total = $totalAmount+$tradeAmount+$waterAmount;
            $collectionDetails[0]['total'] = $total; 
            //get Employee Name
            $empName = $this->model_emp_details->getEmpDetailsById(md5($data['employee_id']));
            $collectionDetails[0]['id'] = $empName['id'];
            $collectionDetails[0]['tran_date'] = $data['tran_date'];
            $collectionDetails[0]['emp_name'] = $empName['emp_name'];
            $collectionDetails[0]['last_name'] = $empName['last_name'];
            $data['collectionDetails'] = $collectionDetails;
            $session->set('dateData', $data['tran_date']);
        }
        return view('property/cash_Verification', $data);

      }else if(isset($id)){
        $key =0;
        $chequeDetails=[];
        $ddDetails=[];
        $session=session();
        $data['date_from'] = $session->get('dateData');
        $data['employee_id'] = $id;
        //Total Collection
            $totalAmount = $this->model_transaction->getTotalAmount($id,$data['date_from']);
            $tradeAmount = $this->TradeTransactionModel->getTotalAmount($id,$data['date_from']);
            $waterAmount = $this->Water_Transaction_Model->getTotalAmount($id,$data['date_from']);
            $data['collection_amount'] = $totalAmount+$tradeAmount+$waterAmount;
        //End Total Collection
        //Total Cash Collection
            $data['totalAmountCash'] = $this->model_transaction->getTotalAmountCash($id,$data['date_from']);
            $data['tradeAmountCash'] = $this->TradeTransactionModel->getTotalAmountCash($id,$data['date_from']);
            $data['waterAmountCash'] = $this->Water_Transaction_Model->getTotalAmountCash($id,$data['date_from']);
            $data['cash_amount'] = $data['totalAmountCash']+$data['tradeAmountCash']+$data['waterAmountCash'];
        //End Total Cash Collection
        //Employee Details
            $empName = $this->model_emp_details->getEmpDetailsById($id);
            $data['tc_name'] = $empName['emp_name']."  ".$empName['last_name'];
        //End Employee Details
        //Cheque Details
            if($propertyChequeDetails = $this->model_transaction->getChequeDetailsByEmpId($id,$data['date_from'])){ 

                foreach ($propertyChequeDetails as $value) {
                  
                    
                    $chequeDetails[$key]['transaction_id'] = $value['id'].'/'.'property'.'/'.$value['payable_amt'];
                    $chequeDetails[$key]['tran_date'] = $value['tran_date'];
                    $chequeDetails[$key]['tran_no'] = $value['tran_no'];
                    $chequeDetails[$key]['payable_amt'] = $value['payable_amt'];
                    //Property cheque Details
                    $propertyChequeData = $this->model_cheque_details->getChequeDetailsByTransactionId($value['id'],$data['date_from']);
                    $chequeDetails[$key]['cheque_date'] = $propertyChequeData['cheque_date'];
                    $chequeDetails[$key]['cheque_no'] = $propertyChequeData['cheque_no'];
                    $chequeDetails[$key]['branch_name'] = $propertyChequeData['branch_name'];
                    $chequeDetails[$key]['bank_name'] = $propertyChequeData['bank_name'];
                    $key++;
                }
            }
           if($waterChequeDetails = $this->Water_Transaction_Model->getChequeDetailsByEmpId($id,$data['date_from'])){
                $key++;
                foreach ($waterChequeDetails as $value) {
                    $chequeDetails[$key]['transaction_id'] = $value['id'].'/'.'water'.'/'.$value['paid_amount'];
                    $chequeDetails[$key]['tran_date'] = $value['transaction_date'];
                    $chequeDetails[$key]['tran_no'] = $value['transaction_no'];
                    $chequeDetails[$key]['payable_amt'] = $value['paid_amount'];
                    //Water cheque Details
                    $waterChequeData = $this->Water_Cheque_Details_Model->getChequeDetailsByTransactionId($value['id'],$data['date_from']);
                    $chequeDetails[$key]['cheque_date'] = $waterChequeData['cheque_date'];
                    $chequeDetails[$key]['cheque_no'] = $waterChequeData['cheque_no'];
                    $chequeDetails[$key]['branch_name'] = $waterChequeData['branch_name'];
                    $chequeDetails[$key]['bank_name'] = $waterChequeData['bank_name'];
                    $key++;
                }
            }
            if($tradeChequeDetails = $this->TradeTransactionModel->getChequeDetailsByEmpId($id,$data['date_from'])){
               // print_r($tradeChequeDetails);
                $key++;
                foreach ($tradeChequeDetails as $value) {
                    $chequeDetails[$key]['transaction_id'] = $value['transaction_id'].'/'.'trade'.'/'.$value['payable_amt'];
                    $chequeDetails[$key]['tran_date'] = $value['transaction_date'];
                    $chequeDetails[$key]['tran_no'] = $value['transaction_no'];
                    $chequeDetails[$key]['payable_amt'] = $value['payable_amt'];
                    //Trade cheque Details
                    $tradeChequeData = $this->TradeChequeDtlModel->getChequeDetailsByTransactionId($value['id'],$data['date_from']);
                    $chequeDetails[$key]['cheque_date'] = $tradeChequeData['cheque_date'];
                    $chequeDetails[$key]['cheque_no'] = $tradeChequeData['cheque_no'];
                    $chequeDetails[$key]['branch_name'] = $tradeChequeData['branch_name'];
                    $chequeDetails[$key]['bank_name'] = $tradeChequeData['bank_name'];
                    $key++;
                }
            }
            $data['chequeDetails'] = $chequeDetails;
        //End Cheque Details
        //Dd Details
        if($propertyDdDetails = $this->model_transaction->getDdDetailsByEmpId($id,$data['date_from'])){ 
            $key++;
            foreach ($propertyDdDetails as $value) {
                $ddDetails[$key]['transaction_id'] = $value['id'].'/'.'property'.'/'.$value['payable_amt'];
                $ddDetails[$key]['tran_date'] = $value['tran_date'];
                $ddDetails[$key]['tran_no'] = $value['tran_no'];
                $ddDetails[$key]['payable_amt'] = $value['payable_amt'];
                //Property cheque Details
                $propertyChequeData = $this->model_cheque_details->getChequeDetailsByTransactionId($value['id'],$data['date_from']);
                $ddDetails[$key]['cheque_date'] = $propertyChequeData['cheque_date'];
                $ddDetails[$key]['cheque_no'] = $propertyChequeData['cheque_no'];
                $ddDetails[$key]['branch_name'] = $propertyChequeData['branch_name'];
                $ddDetails[$key]['bank_name'] = $propertyChequeData['bank_name'];
                $key++;
            }
        }
        if($waterDdDetails = $this->Water_Transaction_Model->getDdDetailsByEmpId($id,$data['date_from'])){
            //print_r($waterDdDetails);
                $key++;
                foreach ($waterDdDetails as $value) {
                    $ddDetails[$key]['transaction_id'] = $value['id'].'/'.'water'.'/'.$value['paid_amount'];
                    $ddDetails[$key]['tran_date'] = $value['transaction_date'];
                    $ddDetails[$key]['tran_no'] = $value['transaction_no'];
                    $ddDetails[$key]['payable_amt'] = $value['paid_amount'];
                    //Water cheque Details
                    $waterChequeData = $this->Water_Cheque_Details_Model->getChequeDetailsByTransactionId($value['id'],$data['date_from']);
                    $ddDetails[$key]['cheque_date'] = $waterChequeData['cheque_date'];
                    $ddDetails[$key]['cheque_no'] = $waterChequeData['cheque_no'];
                    $ddDetails[$key]['branch_name'] = $waterChequeData['branch_name'];
                    $ddDetails[$key]['bank_name'] = $waterChequeData['bank_name'];
                    $key++;
                }
            }
            if($tradeDdDetails = $this->TradeTransactionModel->getDdDetailsByEmpId($id,$data['date_from'])){
                //print_r($tradeDdDetails);
                $key++;
                foreach ($tradeDdDetails as $value) {
                    $ddDetails[$key]['transaction_id'] = $value['id'].'/'.'trade'.'/'.$value['paid_amount'];
                    $ddDetails[$key]['tran_date'] = $value['transaction_date'];
                    $ddDetails[$key]['tran_no'] = $value['transaction_no'];
                    $ddDetails[$key]['payable_amt'] = $value['paid_amount'];
                    //Trade cheque Details
                    $tradeChequeData = $this->TradeChequeDtlModel->getChequeDetailsByTransactionId($value['id'],$data['date_from']);
                    $ddDetails[$key]['cheque_date'] = $tradeChequeData['cheque_date'];
                    $ddDetails[$key]['cheque_no'] = $tradeChequeData['cheque_no'];
                    $ddDetails[$key]['branch_name'] = $tradeChequeData['branch_name'];
                    $ddDetails[$key]['bank_name'] = $tradeChequeData['bank_name'];
                    $key++;
                }
            }
            $data['ddDetails'] = $ddDetails;
        //End DD Deails
        return view('property/collection_details', $data);
      }else{
            $data['tran_date'] = date('Y-m-d');
            $session->set('dateData', $data['tran_date']);
            foreach ($data['emplist'] as $key => $value) {
             //Property
            $totalAmount = $this->model_transaction->getTotalAmount(md5($value['id']),$data['tran_date']);
             //Water Collection Amount
            $tradeAmount = $this->TradeTransactionModel->getTotalAmount(md5($value['id']),$data['tran_date']);
             //Trade Collection Amount
            $waterAmount = $this->Water_Transaction_Model->getTotalAmount(md5($value['id']),$data['tran_date']);
            //echo $waterAmount;
            $total = $totalAmount+$tradeAmount+$waterAmount;
            $collectionDetails[$key]['total'] = $total; 
            //get Employee Name
            $empName = $this->model_emp_details->getEmpDetailsById(md5($value['id']));

            $collectionDetails[$key]['id'] = $value['id'];
            $collectionDetails[$key]['tran_date'] = $data['tran_date'];
            $collectionDetails[$key]['emp_name'] = $empName['emp_name'];
            $collectionDetails[$key]['last_name'] = $empName['last_name'];
            $data['collectionDetails'] = $collectionDetails;
           // print_r($data['collectionDetails']);
            }
            return view('property/cash_Verification', $data);
        }
    }
    public function verificationDetails(){
        if($this->request->getMethod()=='post'){


            

            $session=session();
            $empDetails = $session->get('emp_details');
            $id =$empDetails['id'];
            $verified_date = date('Y-m-d');
            $inputs = arrFilterSanitizeString($this->request->getVar());
            
            if($inputs['amountVerify'] || $inputs['cashVerify'])
            {

            $verifiedData  = $inputs['amountVerify'];
            $employee_id  = $inputs['employee_id'];
            $trans_date  = $inputs['trans_date'];
            $len = is_array($verifiedData)?sizeof($verifiedData):0;
            $totalChequeverified=0;
            $verifiedCash = 0;
            
            
            $session=session();
            $emp_dtls=$session->get('emp_details');
            $emp_dt=$this->model_emp_details->getEmpDetailsById($employee_id);
            $emp_id=$emp_dt['id'];
            $cashverification_mstr=array();
            $cashverification_mstr['emp_details_id']=$emp_id;
            $cashverification_mstr['transaction_date']=$trans_date;
            $cashverification_mstr['collection_amount']=$inputs['total_collection'];
            $cashverification_mstr['emp_id']=$emp_dtls['id'];
            $cashverification_mstr['verified_date']=date('Y-m-d');
            $cashverification_mstr['created_on']=date('Y-m-d H:i:s');
            
            //print_r($cashverification_mstr);
            $count=$this->model_cash_verification_mstr->getCount($emp_id,date('Y-m-d'),$emp_dtls['id']);
            //exit();

          /*  if($count==0)
            {
                $cash_verify_id=$this->model_cash_verification_mstr->insertData($cashverification_mstr);

            }
            else
            {
                $cash_verify_id=$this->model_cash_verification_mstr->getCashVerifyIdbyVerifydateEmpId($emp_id,date('Y-m-d'),$emp_dtls['id']);
            }*/
            //exit();
            if($len>0){

                for($i=0;$i<$len;$i++){
                    $iparr = explode("/", $verifiedData[$i]); 
                    $totalCheq  =$totalChequeverified+$iparr[2]; 
                    

                    if($iparr[1]=="property"){ 
                        //update property transaction
                        $this->model_transaction->updateVerificationStatus($id,$iparr[0],$verified_date);
                    }else if($iparr[1]=="water"){
                        //Water Transaction
                        $this->Water_Transaction_Model->updateVerificationStatus($id,$iparr[0],$verified_date);
                      

                    }else if($iparr[1]=="trade"){
                        //trade Transaction
                         $this->TradeTransactionModel->updateVerificationStatus($id,$iparr[0],$verified_date);
                    }
                }
            }
            //Cash verified
           if($inputs['cashVerify']=="cashVerify"){

            
            $verifiedCash = $inputs['cash_amount'];
                $this->model_transaction->updateVerificationStatuCashCollection($employee_id,$verified_date,$id,$trans_date);
                $this->Water_Transaction_Model->updateVerificationStatuCashCollection($employee_id,$verified_date,$id,$trans_date);
                $this->TradeTransactionModel->updateVerificationStatuCashCollection($employee_id,$verified_date,$id,$trans_date);

             
            }
            //get Reporting Project Manager
           $reportingMp = $this->model_user_hierarchy->get_project_manager_by_user($employee_id);
           //$reportingMp = rtrim($reportingMp,',');
           $trim=rtrim($reportingMp,',');
            $val=explode(',', $trim);
            //print_r($val);
            $lastindex=end($val);
          // echo "aa".$lastindex=key($val);
             $project_manager_id=$lastindex;
           //get not verified Transaction Data
            $input = [
                    'subject' =>'Cash Verification',
                    'sender_id' =>$id,
                    'receiver_id' =>$project_manager_id,
                    'created_on' =>date('Y-m-d H:i:s'),
                    'remarks' => $this->request->getVar('remarks'),
                    'emp_details_id'=>$emp_id,
                    'cash_verify_id'=>$cash_verify_id
                    ];
            $transactionDetails = $this->model_transaction->getAllNotVarifiedDataByEmpId($employee_id,$trans_date);
            /*print_r($transactionDetails);*/
            $WaterTransactionDetails = $this->Water_Transaction_Model->getAllNotVarifiedDataByEmpId($employee_id,$trans_date);
            //print_r($WaterTransactionDetails);
            $tradeTransactionDetails = $this->TradeTransactionModel->getAllNotVarifiedDataByEmpId($employee_id,$trans_date);
            //Insert Notification Data
            $inserted_id ="";
            if($transactionDetails!="" || $WaterTransactionDetails!="" || $tradeTransactionDetails!="")
            {

                $count=$this->model_notification->getCount($emp_id,date('Y-m-d'));

              /*  if($count==0)
                {
                    $inserted_id = $this->model_notification->insertNotificationData($input);
                }
                else
                {
                    $inserted_id = $this->model_notification->getNotifIdbyVerifydateEmpId($emp_id,$curr_date);
                    
                }*/
                
            }
            //echo $inserted_id;
           if($transactionDetails){
                foreach ($transactionDetails as $value) {
                //update not verified property transaction
                $this->model_transaction->UpdateVerifiedStatus($inserted_id,$value['id']);
                }

            }
           if($WaterTransactionDetails){
                foreach ($WaterTransactionDetails as $value) {
                //update not verified Water transaction
                $this->Water_Transaction_Model->UpdateVerifiedStatus($inserted_id,$value['id']);
                }
            }
            if($tradeTransactionDetails){
                foreach ($tradeTransactionDetails as $value) {
                //update not verified Water transaction
                $this->TradeTransactionModel->UpdateVerifiedStatus($inserted_id,$value['id']);
                }
            }

           // echo $totalChequeverified;
            // echo '-'.$verifiedCash;


          

            $total_verified=$verifiedCash+$totalChequeverified;
          //  $this->model_cash_verification_mstr->updateverifyAmount($total_verified,$cash_verify_id);

            
          //  exit();


            flashToast('cashVerification', 'Cash verified Successfully!!');
            return $this->response->redirect(base_url('CashVerification/details'));

        }
        else
        {
            flashToast('cashVerification', 'Atleast one checkbox need to be selected!!');
            return $this->response->redirect(base_url('CashVerification/details'));
        }
        
        
        } else{
            flashToast('cashVerification', 'Something Is Wrong!!');
           return $this->response->redirect(base_url('CashVerification/details'));
        }
    }
}
?>
