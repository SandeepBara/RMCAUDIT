<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\model_ulb_mstr;
use App\Models\model_transaction;
use App\Models\model_prop_dtl;
use App\Models\model_prop_owner_detail;
use App\Models\model_saf_dtl;
use App\Models\model_saf_owner_detail;
use App\Models\model_tran_mode_mstr;
use App\Models\PropertyTransactionDeactivateModel;
use App\Models\model_prop_demand;
use App\Models\model_collection;
use App\Models\model_saf_demand;
use App\Models\model_saf_collection;
use App\Models\model_cheque_details;

class PropertyTransactionDeactivate extends AlphaController
{
    protected $property;
    //protected $dbSystem;
    protected $model_ward_mstr;
    protected $model_transaction;
    protected $model_prop_dtl;
    protected $model_prop_owner_detail;
    protected $model_saf_dtl;
    protected $model_saf_owner_detail;
    protected $model_tran_mode_mstr;
    protected $PropertyTransactionDeactivateModel;
    protected $model_prop_demand;
    protected $model_collection;
    protected $model_saf_demand;
    protected $model_saf_collection;
    protected $model_cheque_details;
    
    public function __construct(){
        parent::__construct();
        helper(['db_helper','form']);
        if($db_name = dbConfig("property")){
            $this->property = db_connect($db_name); 
        }
        /*if($db_name = dbSystem()){
            $this->dbSystem = db_connect($db_name); 
        }*/
        //$this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        //$this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
        $this->model_transaction = new model_transaction($this->property);
        $this->model_prop_dtl = new model_prop_dtl($this->property);
        $this->model_prop_owner_detail = new model_prop_owner_detail($this->property);
        $this->model_saf_dtl = new model_saf_dtl($this->property);
        $this->model_saf_owner_detail = new model_saf_owner_detail($this->property);
        $this->model_tran_mode_mstr = new model_tran_mode_mstr($this->property);
        $this->PropertyTransactionDeactivateModel = new PropertyTransactionDeactivateModel($this->property);
        $this->model_prop_demand = new model_prop_demand($this->property);
        $this->model_collection = new model_collection($this->property);
        $this->model_saf_demand = new model_saf_demand($this->property);
        $this->model_saf_collection = new model_saf_collection($this->property);
        $this->model_cheque_details = new model_cheque_details($this->property);
    }

    function __destruct() {
		$this->property->close();
	}
	
	public function detail()
    {
        $Session = Session();
        $emp_details = $Session->get("emp_details");
        $emp_details_id = $emp_details["id"];
        $ulb_dtl = $Session->get("ulb_dtl");

        $emp_details_id=$emp_details['user_type_mstr_id'];
        if($emp_details_id!="2" && $emp_details_id!="1")
        {
            return redirect()->to('/home');
        }

        if($this->request->getMethod()=='post') {
            $this->property->transBegin();
                $inputs = arrFilterSanitizeString($this->request->getVar());
                $sql = "SELECT id, prop_dtl_id, tran_type FROM tbl_transaction WHERE status IN (1,2) AND id=".$inputs["tran_id"];

                if ($tran_dtl = $this->property->query($sql)->getFirstRow("array")) {
                    $input = [
                        'transaction_id' => $inputs["tran_id"],
                        'deactivated_by' => $emp_details_id,
                        'remark' => $inputs["remarks"],
                        'deactive_date' => date('Y-m-d'),
                        'created_on' => date('Y-m-d H:i:s')
                    ];
                    $transaction_deactivate_dtl_id = $this->PropertyTransactionDeactivateModel->insertData($input);
                    
                    $document_dtl = $this->request->getFile("required_doc");
                    $extension = $document_dtl->getExtension();
                    $document_dtl->move(WRITEPATH."/uploads//".$ulb_dtl['city']."/property_transaction_deactivation//", md5($transaction_deactivate_dtl_id).".".$extension);
                    $uploadFileName = "/property_transaction_deactivation//".md5($transaction_deactivate_dtl_id).".".$extension;
                    if ($this->PropertyTransactionDeactivateModel->uploadDocument($uploadFileName, $transaction_deactivate_dtl_id)) {
                        if ($tran_dtl["tran_type"]=="Property") {
                            $sql = "UPDATE tbl_prop_demand 
                                    SET balance=amount, paid_status=0
                                    WHERE paid_status=1 AND status=1 AND id IN (SELECT prop_demand_id FROM tbl_collection WHERE transaction_id=".$inputs["tran_id"].")";
                            $this->property->query($sql);
                            $sql = "UPDATE tbl_collection SET deactive_status=1, status=0 WHERE deactive_status=0 AND status=1 AND transaction_id=".$inputs["tran_id"]."";
                            $this->property->query($sql);
                            $sql = "UPDATE tbl_transaction SET status=0, deactive_status=1 WHERE tran_type='Property' AND status IN (1,2) AND id=".$inputs["tran_id"];
                            $this->property->query($sql);
                        } else if ($tran_dtl["tran_type"]=="Saf") {
                            $sql = "UPDATE tbl_saf_demand 
                                    SET balance=amount, paid_status=0
                                    WHERE paid_status=1 AND status=1 AND id IN (SELECT saf_demand_id FROM tbl_saf_collection WHERE transaction_id=".$inputs["tran_id"].")";
                            $this->property->query($sql);
                            $sql = "UPDATE tbl_saf_collection SET status=0 WHERE status=1 AND transaction_id=".$inputs["tran_id"]."";
                            $this->property->query($sql);
                            $sql = "UPDATE tbl_transaction SET status=0, deactive_status=1 WHERE tran_type='Saf' AND status IN (1,2) AND id=".$inputs["tran_id"];
                            $this->property->query($sql);
                            $sql = "UPDATE tbl_saf_dtl SET payment_status=0 WHERE payment_status=1 AND id=".$tran_dtl["prop_dtl_id"];
                            $this->property->query($sql);
                            
                        }
                    }
                } else {
                    $this->property->transRollback();
                    flashToast('message', 'Transaction Already Deactivated !!');
                    return $this->response->redirect(base_url('PropertyTransactionDeactivate/detail'));
                }
            if($this->property->transStatus() === FALSE) {
                $this->property->transRollback();
                flashToast('message', 'Something Is Wrong!!');
                return $this->response->redirect(base_url('PropertyTransactionDeactivate/detail'));
            } else {
                $this->property->transCommit();
                flashToast('message', 'Transaction Deactivated Successfully!!');
                return $this->response->redirect(base_url('PropertyTransactionDeactivate/detail'));
            }
        }

        $data =(array)null;
        $data = $inputs = arrFilterSanitizeString($this->request->getVar());
        if ($inputs['tran_no']!="") {
            if ($tranDetails = $this->model_transaction->getTranDtlByTranNo($inputs['tran_no'])) {
                foreach ($tranDetails AS $key=>$val) {
                    $sqlData = "SELECT prop_dtl_id, MAX(id) AS tran_max_id FROM tbl_transaction WHERE prop_dtl_id='".$val["prop_dtl_id"]."' AND tran_type='".$val["tran_type"]."' AND status IN (1,2) GROUP BY prop_dtl_id";
                    $maxDtl = $this->property->query($sqlData)->getFirstRow("array");
                    $tranDetails[$key]["max_dtl_id"] = $maxDtl["tran_max_id"];
                }
                $data["propertyTransactionList"] = $tranDetails;                    
            } else {
                $data['validation'] = "Record Does Not Exists";
            }
        } else {
            //$data['validation'] = "Record Does Not Exists";
        }
        return view('property/transaction_deactivate',$data);
    }
}
?>
