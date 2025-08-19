<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\TradeViewApplyLicenceOwnerModel;
use App\Models\TradeTransactionModel;
use App\Models\TradeApplyLicenceModel;
use App\Models\TradeFirmOwnerModel;
use App\Models\TradeTradeItemsModel;
use App\Models\tradelicenceratemodel;
use App\Models\TradeBankRecancilationModel;
use App\Models\TradeChequeDtlModel;

class TradeReApplyLicence extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $ward_model;
    protected $TradeTransactionModel;
    protected $TradeViewApplyLicenceOwnerModel;
    protected $TradeApplyLicenceModel;
    protected $model_ward_mstr;
    protected $TradeFirmOwnerModel;
    protected $TradeTradeItemsModel;
    protected $tradelicenceratemodel;
    protected $TradeBankRecancilationModel;
    protected $TradeChequeDtlModel;
    public function __construct()
    {  
        parent::__construct();
        helper(['db_helper','form']);
        if($db_name = dbConfig("trade")){
            //echo $db_name;
            $this->db = db_connect($db_name);            
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        } 
        
        //$db_name = db_connect("db_rmc_property"); 
        
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->TradeViewApplyLicenceOwnerModel = new TradeViewApplyLicenceOwnerModel($this->db);
        $this->TradeTransactionModel = new TradeTransactionModel($this->db);
        $this->TradeApplyLicenceModel = new TradeApplyLicenceModel($this->db);
        $this->TradeFirmOwnerModel = new TradeFirmOwnerModel($this->db);
        $this->TradeTradeItemsModel = new TradeTradeItemsModel($this->db);
        $this->tradelicenceratemodel = new tradelicenceratemodel($this->db);
        $this->TradeBankRecancilationModel = new TradeBankRecancilationModel($this->db);
        $this->TradeChequeDtlModel = new TradeChequeDtlModel($this->db);

    }
    
    public function detail()
    {    
        $data=array();
        $session=session();
        //get Employee Details
        $emp_details=$session->get('emp_details');
        $ulb_details=$session->get('ulb_dtl');
        //get Ward List
        $data['ulb_mstr_id']=$ulb_details['ulb_mstr_id'];
        $data['ward_list']=$this->model_ward_mstr->getWardList($data);

        if($this->request->getMethod()=='post')
        {
            $inputs = filterSanitizeStringtoUpper($this->request->getVar());
            $data['ward_mstr_id'] = $inputs['ward_mstr_id'];
            $data['application_no'] = strtoupper($inputs['application_no']);
            //get Apply Licence Id
            if($data['apply_id'] = $this->TradeViewApplyLicenceOwnerModel->getApplyLicenceId($data)){ // get related id from transaction table
                //check Transaction status cheque payment is bounce (bounce status = 3)
                if( $related_id = $this->TradeTransactionModel->checkStatus($data)){
                    $data['application_details'] = $this->TradeViewApplyLicenceOwnerModel->getApplyLicenceDetails($data);
                }else{
                    $data['validation'] = "Payment Is Clear,We Can Not Re-Apply Licence!!!";
                }
            }else{
                $data['validation'] = "Record Does Not Exists";
            }
            return view('trade/Connection/trade_re_apply_licence',$data);
        }else{
           return view('trade/Connection/trade_re_apply_licence',$data);
        }
    }
    public function view($id){
       $data['licencee'] = $this->TradeApplyLicenceModel->apply_licence_md5($id);
       //get transaction id
       $transaction_id = $this->TradeTransactionModel->getTransactionId($data['licencee']['id']);
       //get Cheque Details
       $data['bankDetails'] = $this->TradeChequeDtlModel->getBankDetails($transaction_id);
       $data['chequeDetails'] = $this->TradeBankRecancilationModel->getChequeDetails($id);
       $data["holding_no"]=$data['licencee']["holding_no"];
       $data['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($data['licencee']['ward_mstr_id']);
       //get Trade firm Owner Details
       $data['firm_owner']=$this->TradeFirmOwnerModel->getdatabyid_md5($id);  
       //get Trade Item 
       $data['trade_items']=$this->TradeTradeItemsModel->getdatabyid_md5($id);
       //get Penalty Charge
       $data['penalty'] = $this->TradeBankRecancilationModel->getChequBounceCharge($id);
       //get cheque Details

        return view('trade/Connection/trade_re_apply_licence_view',$data);
    }
    public function getcharge(){
        if($this->request->getMethod()=="post")
        {
            $data=array();
            $inputs = arrFilterSanitizeString($this->request->getVar());  
            $data['area_in_sqft']=(float)$inputs['areasqft'];
            $data['application_type_id']=$inputs['applytypeid'];
            $data['curdate']=date("Y-m-d");
            if ($count = $this->tradelicenceratemodel->getrate($data)){
            $response = ['response'=>true, 'rate'=>$count['rate']];            
            } else {
                $response = ['response'=>false];
            }
            //echo $count;
            return json_encode($response);
       }
    }
    public function re_apply(){
        $session=session();
        //get Employee Details
        $emp_details = $session->get('emp_details');
        $ulb_details=$session->get('ulb_dtl');
        $data['ulb_mstr_id']=$ulb_details['ulb_mstr_id'];
        $data['ward_list']=$this->model_ward_mstr->getWardList($data);
        if($this->request->getMethod()=="post"){
           $inputs = arrFilterSanitizeString($this->request->getVar()); 
            $data['emp_details_id'] = $emp_details['id'];
            $data['licence_for'] = $inputs['licence_for'];
            $data['ward_mstr_id'] = $inputs['ward_mstr_id'];
            $data['payment_mode'] = $inputs['payment_mode'];
            $data['paid_amount'] = $inputs['licence_charge']+$inputs['penalty'];
            $data['penalty'] = $inputs['penalty'];
            $data['created_on'] = date('Y-m-d H:i:s');
            $data['apply_licence_id'] = $inputs['apply_id'];
            $data['transaction_date'] = date('Y-m-d');
            $data['transaction_type'] = "NEW LICENSE";
            //update Transaction Amount
           $inserted_id = $this->TradeTransactionModel->insertRe_AllpyData($data);
           if($inserted_id){
            //Update Licence for Years
            $this->TradeApplyLicenceModel->updateLicenceYears($inputs['id'],$inputs['licence_for']);
            $transaction_no = "TRANML".date('d').$result.date('Y').date('m').date('s');
            $this->TradeTransactionModel->updateTransactionNo($transaction_no,$inserted_id);
            //update BankRecancilation transaction Id
            $this->TradeBankRecancilationModel->updateBankRecancilation($inputs['id'],$inserted_id);
            /*flashToast('holding','Re Payment Done!!');*/
            return $this->response->redirect(base_url('tradeapplylicence/view_transaction_receipt/'.md5($inputs['id']).'/'.md5($inserted_id)));
           }else{
            flashToast('holding','Something Is Wrong!!');
            return $this->response->redirect(base_url('TradeReApplyLicence/detail'));
           }
        }
    }
}