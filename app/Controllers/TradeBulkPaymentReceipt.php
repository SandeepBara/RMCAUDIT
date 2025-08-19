<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\TradeApplyLicenceModel;
use App\Models\TradeTransactionModel;
use App\Models\model_ulb_mstr;
use App\Models\TradeChequeDtlModel;
class TradeBulkPaymentReceipt extends AlphaController
{
    protected $db;
    protected $dbSystem;    
    protected $model_ward_mstr;
    protected $TradeApplyLicenceModel;
    protected $TradeTransactionModel;
    protected $model_ulb_mstr;
    protected $TradeChequeDtlModel;
    public function __construct(){
        parent::__construct();
    	helper(['db_helper', 'qr_code_generator_helper','form']);
        if($db_name = dbConfig("trade")){
            $this->db = db_connect($db_name); 
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        }
        $this->model_ward_mstr=new model_ward_mstr($this->dbSystem);
        $this->TradeApplyLicenceModel = new TradeApplyLicenceModel($this->db);
        $this->TradeTransactionModel = new TradeTransactionModel($this->db);
        $this->modelUlb = new model_ulb_mstr($this->dbSystem);
        $this->TradeChequeDtlModel = new TradeChequeDtlModel($this->db);
    }   
    public function bulkPrint(){
        $data=array();
        $printAllData=[];
        if($this->request->getMethod()=="post"){
            $inputs = arrFilterSanitizeString($this->request->getVar());  
            $data['from_date'] = $inputs['from_date'];
            $data['to_date'] = $inputs['to_date'];
            if($printData = $this->TradeTransactionModel->getBulkPrintData($inputs)){
                $data['len'] = sizeof($printData);
                $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id']; 
                foreach ($printData as $key => $value) {
                    $path=base_url('citizenPaymentReceipt/view_trade_transaction_receipt/'.$ulb_mstr_id.'/'.$value['related_id'].'/'.$value['id']);
                    $printAllData[$key]['ss']=qrCodeGeneratorFun($path);
                    $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id']; 
                    $data['ulb_mstr_name'] = $this->modelUlb->getulb_list($ulb_mstr_id);
                    $printAllData[$key]['applicant_details'] = $this->TradeApplyLicenceModel->fetch_all_application_data(md5($value['related_id']));        
                    $printAllData[$key]['transaction_details'] = $this->TradeTransactionModel->transaction_details(md5($value['id']));
                    $printAllData[$key]['warddet']=$this->model_ward_mstr->getWardNoBywardId($printAllData[$key]['transaction_details']['ward_mstr_id']);
                    $data['status']=[1,2];
                    $printAllData[$key]['cheque_details'] = $this->TradeChequeDtlModel->alltransactioncheque_details(md5($value['id']));
                }
                $data['printAllData'] = $printAllData;
                return view('trade/Connection/bulkPrint',$data);
            }else{
                return view('trade/Connection/tradeApplyBulkPaymentReceipt',$data);
            }
            
        }else{
            return view('trade/Connection/tradeApplyBulkPaymentReceipt');
        }
    }
}
?>
