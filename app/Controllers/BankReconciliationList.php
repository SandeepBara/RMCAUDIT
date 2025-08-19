<?php namespace App\Controllers;
use CodeIgniter\Controller;
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

class BankReconciliationList extends AlphaController
{
    protected $db;
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
    public function __construct(){
        parent::__construct();
    	helper(['db_helper','form']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name); 
        }
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

    }

    function __destruct() {
		$this->db->close();
	}

    public function detail()
    {
        $data =(array)null;
        $chequeDetailsList = [];
        $ward_mstr_id ="";
        if($this->request->getMethod()=='post')
        {
            //Cheque Details
            $cheque_no = $this->request->getVar('cheque_no');

            $data['cheque_no'] = $cheque_no;
            if ($chequeDetails = $this->model_cheque_details->chequeDetails($cheque_no) ) {
                $chequeDetailsList[0]['id'] = $chequeDetails['id'];
                $chequeDetailsList[0]['cheque_date'] = $chequeDetails['cheque_date'];
                $cheque = $chequeDetails['cheque_no'];
                $chequeDetailsList[0]['bank_name'] = $chequeDetails['bank_name'];
                $chequeDetailsList[0]['branch_name'] = $chequeDetails['branch_name'];
                $transaction_id = $chequeDetails['transaction_id'];
                $transactionDetails = $this->model_transaction->getTransactionDetails($transaction_id);
                $chequeDetailsList[0]['tran_no'] = $transactionDetails['tran_no'];
                $chequeDetailsList[0]['tran_date'] = $transactionDetails['tran_date'];
                $chequeDetailsList[0]['tran_type'] = $transactionDetails['tran_type'];
                if($transactionDetails['tran_type']=='Property'){
                    //Get Property Owner details
                    $chequeDetailsList[0]['holding'] = $this->model_prop_dtl->getPropdetails($transactionDetails['prop_dtl_id']);
                    $ward_mstr_id = $this->model_prop_dtl->getWardMstrId($transactionDetails['prop_dtl_id']);
                    $chequeDetailsList[0]['owner'] = $this->model_prop_owner_detail->getPropertyOwnerDetails($transactionDetails['prop_dtl_id']);
                }
                else
                {
                    $chequeDetailsList[0]['holding'] = $this->model_saf_dtl->getSafdetails($transactionDetails['prop_dtl_id']);
                    $ward_mstr_id = $this->model_saf_dtl->getWardMstrId($transactionDetails['prop_dtl_id']);
                    $chequeDetailsList[0]['owner'] = $this->model_saf_owner_detail->getSafOwnerDetails($transactionDetails['prop_dtl_id']);
        
                }
                $chequeDetailsList[0]['cheque_no'] = $cheque;
                $chequeDetailsList[0]['ward_mstr_id'] = $ward_mstr_id;
                $data['chequeDetailsList'] = $chequeDetailsList;
            } else {
                $data['validation'] = "Record Does Not Exists";
            }
            return view('property/bank_reconciliation_list', $data);
        }
        else
        {
            if ($chequeDetails = $this->model_cheque_details->allChequeDetails()) {
                foreach ($chequeDetails as $key => $value) {
                    $cheque = $value['cheque_no'];
                    $chequeDetailsList[$key]['id'] = $value['id'];
                    $chequeDetailsList[$key]['bank_name'] = $value['bank_name'];
                    $chequeDetailsList[$key]['cheque_date'] = $value['cheque_date'];
                    $chequeDetailsList[$key]['branch_name'] = $value['branch_name'];
                    $transaction_id = $value['transaction_id'];
                    $transactionDetails = $this->model_transaction->getTransactionDetails($transaction_id);
                    $chequeDetailsList[$key]['tran_no'] = $transactionDetails['tran_no'];
                    $chequeDetailsList[$key]['tran_date'] = $transactionDetails['tran_date'];
                    $chequeDetailsList[$key]['tran_type'] = $transactionDetails['tran_type'];
                    if($transactionDetails['tran_type']=='Property'){
                        //Get Property Owner details
                        $chequeDetailsList[$key]['holding'] = $this->model_prop_dtl->getPropdetails($transactionDetails['prop_dtl_id']);
                        $ward_mstr_id = $this->model_prop_dtl->getWardMstrId($transactionDetails['prop_dtl_id']);
                        $chequeDetailsList[$key]['owner'] = $this->model_prop_owner_detail->getPropertyOwnerDetails($transactionDetails['prop_dtl_id']);
                    }
                    else
                    {
                        $chequeDetailsList[$key]['holding'] = $this->model_saf_dtl->getSafdetails($transactionDetails['prop_dtl_id']);
                        $ward_mstr_id = $this->model_saf_dtl->getWardMstrId($transactionDetails['prop_dtl_id']);
                        $chequeDetailsList[$key]['owner'] = $this->model_saf_owner_detail->getSafOwnerDetails($transactionDetails['prop_dtl_id']);
            
                    }
                    $chequeDetailsList[$key]['cheque_no'] = $cheque;
                    $chequeDetailsList[$key]['ward_mstr_id'] = $ward_mstr_id;
                    $data['chequeDetailsList'] = $chequeDetailsList;
                    //print_r($data);
                }
                
            } 
            return view('property/bank_reconciliation_list', $data);
        } 
    }
   /* public function cheque(){
        $session = session();
        $emp_details = $session->get('emp_details');
        $emp_details_id = $emp_details['id'];
        if($this->request->getMethod()=='post'){
            $input = [
                        'reason' => $this->request->getVar('reason'),
                        'amount' => $this->request->getVar('amount'),
                        'ward_mstr_id' => $this->request->getVar('ward_mstr_id'),
                        'cancel_date' =>date('Y-m-d'),
                        'created_on' =>date('Y-m-d H:i:s')
                    ];
            $cheque_no = $this->request->getVar('cheque_no');
            $status = $this->request->getVar('status');
            $chequeDetails = $this->model_cheque_details->chequeDetails($cheque_no);
            $transaction_id = $chequeDetails['transaction_id'];
            $verify_date = date('Y-m-d');
            $transactionDetails = $this->model_transaction->getTransactionDetails($transaction_id);
            $input['transaction_id'] = $transactionDetails['id'];
            $input['emp_details_id'] = $emp_details_id;
            $input['cheque_dtl_id'] = $chequeDetails['id'];
            $insert_id = $this->model_bank_recancilation->insertData($input);
            if($insert_id){
                $this->model_transaction->updateDeActiveStatus($transaction_id,$emp_details_id,$verify_date,$status);
                if($transactionDetails['tran_type']=="Property")
                {
                  //Get Collection Details
                   $collectionDetails = $this->model_collection->collectionDetails($transaction_id);
                   //Update deactive status
                   foreach ($collectionDetails as $value) {
                        $this->model_prop_demand->updatePaidStatus($value['prop_demand_id']); 
                   }
                   $this->model_collection->updateDeActiveStatus($transaction_id);
                }
                else
                {
                    //Sef Collection Details
                    $safDetails = $this->model_saf_collection->safDetails($transaction_id);
                    //Update Deactive status
                    foreach ($safDetails as $value) {
                        $this->model_saf_demand->updatePaidStatus($value['saf_demand_id']);
                    }
                    $this->model_saf_collection->updateDeActiveStatus($transaction_id);
                } 
                //Update cheque_details bounce status
                $this->model_cheque_details->updateBounceStatus($transaction_id);
              flashToast('bank_cancel','Cheque Canceled Successfully!!');
              return view('property/cheque_cancelation');
            }else{
                flashToast('bank_cancel','SomeThing Is Wrong!!!');
              return view('property/cheque_cancelation');
            }
        }
    }*/
    public function view($cheque_no=null){
        if ($chequeDetails = $this->model_cheque_details->chequeDetails($cheque_no) ) {
            $cheque = $chequeDetails['cheque_no'];
            $chequeDetailsList['bank_name'] = $chequeDetails['bank_name'];
            $chequeDetailsList['branch_name'] = $chequeDetails['branch_name'];
            $transaction_id = $chequeDetails['transaction_id'];
            $transactionDetails = $this->model_transaction->getTransactionDetails($transaction_id);
            $chequeDetailsList['tran_no'] = $transactionDetails['tran_no'];
            $chequeDetailsList['tran_date'] = $transactionDetails['tran_date'];
            $chequeDetailsList['tran_type'] = $transactionDetails['tran_type'];
            if($transactionDetails['tran_type']=='Property'){
                //Get Property Owner details
                $chequeDetailsList['holding'] = $this->model_prop_dtl->getPropdetails($transactionDetails['prop_dtl_id']);
                $ward_mstr_id = $this->model_prop_dtl->getWardMstrId($transactionDetails['prop_dtl_id']);
                $chequeDetailsList['owner'] = $this->model_prop_owner_detail->getPropertyOwnerDetails($transactionDetails['prop_dtl_id']);
            }
            else
            {
                $chequeDetailsList['holding'] = $this->model_saf_dtl->getSafdetails($transactionDetails['prop_dtl_id']);
                $ward_mstr_id = $this->model_saf_dtl->getWardMstrId($transactionDetails['prop_dtl_id']);
                $chequeDetailsList['owner'] = $this->model_saf_owner_detail->getSafOwnerDetails($transactionDetails['prop_dtl_id']);
    
            }
            $chequeDetailsList['cheque'] = $cheque;
            $chequeDetailsList['ward_mstr_id'] = $ward_mstr_id;
            $data['chequeDetailsList'] = $chequeDetailsList;
        } 
        return view('property/bank_reconciliation_list_view', $data);
    }
}
?>
