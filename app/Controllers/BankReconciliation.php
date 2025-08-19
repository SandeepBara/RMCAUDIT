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
use App\Models\model_level_pending_dtl;

class BankReconciliation extends AlphaController
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
    protected $model_level_pending_dtl;
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
        $this->model_level_pending_dtl = new model_level_pending_dtl($this->db);
    }

    function __destruct() {
		$this->db->close();
	}

    public function detail($id=null)
    {
        $data =(array)null;
        $chequeDetailsList = [];
        $ward_mstr_id ="";
       if ($chequeDetails = $this->model_cheque_details->chequeDetailsById($id) ) {
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
            $chequeDetailsList['cheque_no'] = $cheque;
            $chequeDetailsList['ward_mstr_id'] = $ward_mstr_id;
            $data['chequeDetailsList'] = $chequeDetailsList;
        } else {
            $data['validation'] = "Record Does Not Exists";
        }
        return view('property/cheque_cancelation', $data);  
    }
    public function cheque(){
        $session = session();
        $emp_details = $session->get('emp_details');
        $emp_details_id = $emp_details['id'];
        if($this->request->getMethod()=='post')
        {
            $input = [
                        'reason' => $this->request->getVar('reason'),
                        'ward_mstr_id' => $this->request->getVar('ward_mstr_id'),
                        'cancel_date' =>date('Y-m-d'),
                        'created_on' =>date('Y-m-d H:i:s')
                    ];
            $cheque_no = $this->request->getVar('cheque_no');
            $status = $this->request->getVar('status');
            if($status==1){ 
                $input['amount']=0;
            }else{
                $input['amount'] = $this->request->getVar('amount');
            }
            $chequeDetails = $this->model_cheque_details->chequeDetails($cheque_no);
            $transaction_id = $chequeDetails['transaction_id'];
            $verify_date = date('Y-m-d');
            $transactionDetails = $this->model_transaction->getTransactionDetails($transaction_id);
            $input['transaction_id'] =0;
            $input['prop_type'] = $transactionDetails['tran_type'];
            $input['prop_dtl_id'] = $transactionDetails['prop_dtl_id'];
            $input['emp_details_id'] = $emp_details_id;
            $input['cheque_dtl_id'] = $chequeDetails['id'];
            $saf_dtl_id = $transactionDetails['prop_dtl_id'];

            $insert_id = $this->model_bank_recancilation->insertData($input);
            if($insert_id){
                if($status==1){ //Cheque Clear
                    $this->model_transaction->updateStatusClear($transaction_id);
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
                    $this->model_cheque_details->updateBounceStatusClear($transaction_id);
                    flashToast('bank_cancel','Cheque Clear Successfully!!');
                   return redirect()->to(base_url('BankReconciliationList/detail'));
                }
                else
                {
                    $this->model_transaction->updateStatusNotClear($transaction_id);
                    if($transactionDetails['tran_type']=="Property")
                    {
                      //Get Collection Details
                       $collectionDetails = $this->model_collection->collectionDetails($transaction_id);
                       //Update deactive status
                       foreach ($collectionDetails as $value) {
                            $this->model_prop_demand->updatePaidStatusNotClear($value['prop_demand_id']); 
                       }
                       $this->model_collection->updateStatusNotClear($transaction_id);
                    }
                    else
                    {
                        //Saf Collection Details
                        $safDetails = $this->model_saf_collection->safDetails($transaction_id);
                        //Update Deactive status
                        foreach ($safDetails as $value) {
                           $getAmountNotClear = $this->model_saf_demand->getAmountNotClear($value['saf_demand_id']);
                            $this->model_saf_demand->updatePaidStatusNotClear($value['saf_demand_id'],$getAmountNotClear['amount']);
                        }
                        //Update Payment Status Zero
                        $this->model_saf_dtl->updatePaymentStatus($transactionDetails['prop_dtl_id']);
                        $this->model_saf_collection->updateStatusNotClear($transaction_id);
                    } 
                    //Update cheque_details bounce status
                    $this->model_cheque_details->updateBounceStatusNotClear($transaction_id);
                   flashToast('bank_cancel', 'Cheque Canceled Successfully!!');
                  //return view('property/cheque_cancelation');
                    return redirect()->to(base_url('BankReconciliationList/detail'));
                }
            } 
        }  
    }
}
?>
