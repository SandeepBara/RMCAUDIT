<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\model_ulb_mstr;
use App\Models\TradeTransactionModel;
use App\Models\TradeApplyLicenceModel;
use App\Models\TradeFirmOwnerModel;
use App\Models\TradeTransactionDeactivateModel;
use App\Models\model_prop_dtl;
use App\Models\TradeChequeDtlModel;


class TradeTransactionDeactivate extends AlphaController
{
    protected $trade;
    protected $dbSystem;
    protected $property;
    protected $model_ward_mstr;
    protected $model_ulb_mstr;
    protected $TradeTransactionModel;
    protected $TradeApplyLicenceModel;
    protected $TradeFirmOwnerModel;
    protected $TradeTransactionDeactivateModel;
    protected $model_prop_dtl;
    protected $TradeChequeDtlModel;
    
    public function __construct(){
        parent::__construct();
        helper(['db_helper','form']);
        if($db_name = dbConfig("trade")){
            $this->trade = db_connect($db_name); 
        }
        if($db_name = dbSystem()){
            $this->dbSystem = db_connect($db_name); 
        }
        if($db_name = dbConfig("property")){
            $this->property = db_connect($db_name); 
        }
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
        $this->TradeTransactionModel = new TradeTransactionModel($this->trade);
        $this->TradeApplyLicenceModel = new TradeApplyLicenceModel($this->trade);
        $this->TradeFirmOwnerModel = new TradeFirmOwnerModel($this->trade);
        $this->TradeTransactionDeactivateModel = new TradeTransactionDeactivateModel($this->trade);
        $this->model_prop_dtl = new model_prop_dtl($this->property);
        $this->TradeChequeDtlModel = new TradeChequeDtlModel($this->trade);
    }

    function __destruct() {
		$this->trade->close();
		$this->dbSystem->close();
        $this->property->close();
	}

    public function detail($transaction_no=null)
    {
        $session=session();
        $emp_details=$session->get('emp_details');
        $emp_details_id=$emp_details['user_type_mstr_id'];
        if($emp_details_id!="2" && $emp_details_id!="1")
        {
            return redirect()->to('/home');
        }

        $data =(array)null;
        $tradeTransactionList = [];
        if($this->request->getMethod()=='post')
        {
            //Water Transaction Details
            $inputs = arrFilterSanitizeString($this->request->getVar());
            $data['statusData']=[1,2];
            $data['transaction_no'] = strtoupper($inputs['transaction_no']);
            $data['cheque_no'] = strtoupper($inputs['cheque_no']??null);
            if (!empty($data['transaction_no']) && $tranDetails = $this->TradeTransactionModel->getTransactionByTransactionNo($data)) 
            {
                if(!empty($tranDetails))
                {
                    if($tranDetails['verify_status']=="")
                    {
                        $temp1['transaction_id'] =md5($tranDetails['id']); 
                        $cke = $this->TradeTransactionModel->getCheckDtlBytrid($temp1);
                        $tradeTransactionList[0]['id'] = $tranDetails['id'];
                        $tradeTransactionList[0]['transaction_date'] = $tranDetails['transaction_date'];
                        $tradeTransactionList[0]['transaction_no'] = $tranDetails['transaction_no'];
                        $waterTransactionList[0]['cheque_no'] = $cke['cheque_no']??null;
                        $waterTransactionList[0]['cheque_date'] = $cke['cheque_date']??null;
                        $waterTransactionList[0]['bank_name'] = $cke['bank_name']??null;
                        $waterTransactionList[0]['branch_name'] = $cke['branch_name']??null;
                        $tradeTransactionList[0]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($tranDetails['ward_mstr_id']);
                    }
                    else
                    {
                        $data['validation'] = "Cash Verification Is Done, We Can Not Deactivate Transaction!!!";
                    }
                }
                else 
                {
                    $data['validation'] = "Record Does Not Exists";
                }
                
            } 
            elseif(!empty($data['cheque_no']) && $chequedtl = $this->TradeTransactionModel->getCheckDtlByno($data))
            {
                foreach($chequedtl as $key => $val)
                { 
                    $temp['id']=md5($val['transaction_id']);
                    $temp['statusData']=[1,2];
                    $trans = $this->TradeTransactionModel->getTransactionByTransactionId($temp);                   
                    if(!empty($trans) && $trans['verify_status']=="")
                    {
                        $tradeTransactionList[$key]['id'] = $trans['id'];
                        $tradeTransactionList[$key]['transaction_date'] = $trans['transaction_date'];
                        $tradeTransactionList[$key]['cheque_no'] = $val['cheque_no'];
                        $tradeTransactionList[$key]['cheque_date'] = $val['cheque_date'];
                        $tradeTransactionList[$key]['bank_name'] = $val['bank_name']??null;
                        $tradeTransactionList[$key]['branch_name'] = $val['branch_name']??null;
                        $tradeTransactionList[$key]['transaction_no'] = $trans['transaction_no'];
                        $tradeTransactionList[$key]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($trans['ward_mstr_id']);
                        
                    }                    

                }
                if(sizeof($chequedtl)==1 && !empty($trans) && $trans['verify_status']!="")
                {
                    $data['validation'] = "Cash Verification Is Done, We Can Not Deactivate Transaction!!!";
                }
                if(sizeof($chequedtl)<1)
                {
                    $data['validation'] = "Record Does Not Exists";
                }
            }
            else 
            {
                $data['validation'] = "Record Does Not Exists";
            }
            $data['tradeTransactionList'] = $tradeTransactionList;
            return view('trade/Connection/transaction_deactivate',$data);
        }
        else if(isset($transaction_no))
        {
            $data['transaction_no']=$transaction_no;
            $data['statusData']=[1,2];
            if ($tranDetails = $this->TradeTransactionModel->getTransactionByTransactionNoUsingMd($data)) 
            {
                $tradeTransactionList[0]['id'] = $tranDetails['id'];
                $tradeTransactionList[0]['transaction_date'] = $tranDetails['transaction_date'];
                $tradeTransactionList[0]['transaction_no'] = $tranDetails['transaction_no'];
                $data['transaction_no'] = $tranDetails['transaction_no'];
                $tradeTransactionList[0]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($tranDetails['ward_mstr_id']);
            } 
            else 
            {
                $data['validation'] = "Record Does Not Exists";
            }
            $data['tradeTransactionList'] = $tradeTransactionList;
            return view('trade/Connection/transaction_deactivate',$data);
        }
        else
        {
           return view('trade/Connection/transaction_deactivate',$data);
        } 
    }
    public function create(){
        $session = session();
        $emp_details = $session->get('emp_details');
        $ulb_dtl = $session->get('ulb_dtl');
        $emp_details_id = $emp_details['id'];
        $city = $this->model_ulb_mstr->getCity($ulb_dtl['ulb_mstr_id']);
        if($this->request->getMethod()=='post'){

            // print_var($_POST);die();
            $input = [
                        'remark' => $this->request->getVar('remark'),
                        'deactive_date' =>date('Y-m-d'),
                        'created_on' =>date('Y-m-d H:i:s'),
                        'transaction_id' => $this->request->getVar('transaction_id'),
                        'deactivated_by' =>$emp_details_id
                    ];
            $insert_id = $this->TradeTransactionDeactivateModel->insertData($input);
            if($insert_id){
                $rules = ['doc_path' => 'uploaded[doc_path]|max_size[doc_path,5124]|ext_in[doc_path,pdf]'];
                if($this->validate($rules))
                {
                    $file = $this->request->getFile('doc_path');
                    $extension = $file->getExtension();
                    
                    if($file->isValid() && !$file->hasMoved()){
                        $newName = md5($insert_id).".".$extension;
                       
                        if($file->move(WRITEPATH.'uploads/'.$city['city'].'/trade_transaction_deactivation',$newName))
                        {
                            $this->TradeTransactionDeactivateModel->uploadDocument($newName,$insert_id);
                        }
                    }
                }
                //check transaction id In checkDetails
                if($id = $this->TradeChequeDtlModel->checkTradeTransactionIdExists($input['transaction_id'])){
                    $this->TradeChequeDtlModel->tradeChequeDeactivate($id);
                }
                $this->TradeTransactionModel->updateTradeTransactionStatus($input['transaction_id']);
                $this->TradeTransactionModel->updatePaymentStatus($this->request->getVar('licence_id'));
                 flashToast('deactivate', 'Transaction Deactivated Successfully!!');
               return $this->response->redirect(base_url('TradeTransactionDeactivate/detail'));
            }else{
               flashToast('deactivate', 'Something Is Wrong!!');
                return $this->response->redirect(base_url('TradeTransactionDeactivate/detail'));
            }
        }
    }
    public function view($id=null){
        $data =(array)null;
        $data['id'] = $id;
        $data['statusData']=[1,2];
       // print_r($data);
        $data['basic_details'] = $this->TradeTransactionModel->getTransactionByTransactionId($data);
        $data['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($data['basic_details']['ward_mstr_id']);
        $data['licenceDetails'] = $this->TradeApplyLicenceModel->applyLicenceDetails($data['basic_details']['related_id']);
        $data['ownerDetails'] = $this->TradeFirmOwnerModel->getOwnerDetails($data['basic_details']['related_id']);
        $data['holding_no'] = $this->model_prop_dtl->getPropdetails($data['licenceDetails']['prop_dtl_id']);

        // print_var($data);
        return view('trade/Connection/transaction_deactivate_view',$data);
    }
}
?>
