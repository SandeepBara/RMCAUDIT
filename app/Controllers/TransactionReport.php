<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_transaction;
use App\Models\model_prop_dtl;
use App\Models\model_saf_dtl;
use App\Models\model_saf_owner_detail;
use App\Models\model_view_transaction;
use App\Models\model_prop_owner_detail;

class TransactionReport extends AlphaController
{
    protected $db;
    protected $model_transaction;
    protected $model_prop_dtl;
    protected $model_saf_dtl;
    protected $model_saf_owner_detail;
    protected $model_view_transaction;
    protected $model_prop_owner_detail;
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
    }
    public function report()
    {
        $data =(array)null;
        if($this->request->getMethod()=='post'){
            $from_date = $this->request->getVar('from_date');
            $to_date = $this->request->getVar('to_date');
            $tran_type = $this->request->getVar('tran_type');
            $data['to_date'] = $to_date;
            $data['from_date'] = $from_date;
            $data['tran_type'] = $tran_type;
            if($tran_type=="Saf"){
                $total= $this->model_transaction->calculateSumForSafAndProperty($from_date,$to_date,$tran_type);
                $data['total'] = $total;
                $data['transactionList'] = $this->model_view_transaction->getAllTransactionByDateForSafAndProperty($from_date,$to_date,$tran_type);
               // print_r($data['transactionList']);
                foreach ($data['transactionList'] as $key => $value){
                    $prop_dtl_id = $value['prop_dtl_id'];
                    $data['transactionList'][$key]['holding'] = $this->model_saf_dtl->getSafdetails($prop_dtl_id);

                    $data['transactionList'][$key]['owner'] = $this->model_saf_owner_detail->getSafOwnerDetails($prop_dtl_id);
                }
                return view('report/property_transaction_report',$data);
            }
            else if($tran_type=="Property"){
                $total= $this->model_transaction->calculateSumForSafAndProperty($from_date,$to_date,$tran_type);
                $data['total'] = $total;
                $data['transactionList'] = $this->model_view_transaction->getAllTransactionByDateForSafAndProperty($from_date,$to_date,$tran_type);
               // print_r($data['transactionList']);
                foreach ($data['transactionList'] as $key => $value){
                    $prop_dtl_id = $value['prop_dtl_id'];
                    $data['transactionList'][$key]['holding'] = $this->model_prop_dtl->getPropdetails($prop_dtl_id);
                    $data['transactionList'][$key]['owner'] = $this->model_prop_owner_detail->getPropertyOwnerDetails($prop_dtl_id);
                }
                return view('report/property_transaction_report',$data);
            }
            else
            {
                $total= $this->model_transaction->calculateSum($from_date,$to_date);
                $data['total'] = $total;
                $data['transactionList'] = $this->model_view_transaction->getAllTransactionByDate($from_date,$to_date);
               // print_r($data['transactionList']);
                foreach ($data['transactionList'] as $key => $value){
                    $tran_type = $value['tran_type'];
                    $prop_dtl_id = $value['prop_dtl_id'];
                    if($tran_type=="Property")
                    {
                        $data['transactionList'][$key]['holding'] = $this->model_prop_dtl->getPropdetails($prop_dtl_id);
                        $data['transactionList'][$key]['owner'] = $this->model_prop_owner_detail->getPropertyOwnerDetails($prop_dtl_id);
                    }
                    else
                    {
                        $data['transactionList'][$key]['holding'] = $this->model_saf_dtl->getSafdetails($prop_dtl_id);

                        $data['transactionList'][$key]['owner'] = $this->model_saf_owner_detail->getSafOwnerDetails($prop_dtl_id);
                    }
                }
                return view('report/property_transaction_report',$data);
            }
            
        } 
        else
        {
            $from_date = date('Y-m-d');
            $to_date = date('Y-m-d');
            $total= $this->model_transaction->calculateSum($from_date,$to_date);
            $data['total'] = $total;
            $data['transactionList'] = $this->model_view_transaction->getAllTransactionByDate($from_date,$to_date);
           // Calculate Sum
            foreach ($data['transactionList'] as $key => $value){
                $tran_type = $value['tran_type'];
                $prop_dtl_id = $value['prop_dtl_id'];
                if($tran_type=="Property")
                {
                   $data['transactionList'][$key]['holding'] = $this->model_prop_dtl->getPropdetails($prop_dtl_id);
                   $data['transactionList'][$key]['owner'] = $this->model_prop_owner_detail->getPropertyOwnerDetails($prop_dtl_id);
                }
                else
                {
                    $data['transactionList'][$key]['holding'] = $this->model_saf_dtl->getSafdetails($prop_dtl_id);

                    $data['transactionList'][$key]['owner'] = $this->model_saf_owner_detail->getSafOwnerDetails($prop_dtl_id);
                }
            }
            return view('report/property_transaction_report',$data);
        } 
    }

    public function collection_api(){

        $sql = "select sum(tn.paid_amount) as collection, count(tn.*), count(al.*) from tbl_transaction as tn 
        join tbl_apply_licence as al 
        on al.id=tn.related_id
        where tn.verify_status=1 and tn.status=1 and al.status=1";
    }
}
?>
