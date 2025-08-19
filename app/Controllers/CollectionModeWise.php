<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_transaction;
use App\Models\model_prop_dtl;
use App\Models\model_saf_dtl;
use App\Models\model_saf_owner_detail;
use App\Models\model_view_transaction;
use App\Models\model_tran_mode_mstr;
use App\Models\model_prop_owner_detail;
class CollectionModeWise extends AlphaController
{
    protected $db;
    protected $model_transaction;
    protected $model_prop_dtl;
    protected $model_saf_dtl;
    protected $model_saf_owner_detail;
    protected $model_view_transaction;
    protected $model_tran_mode_mstr;
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
        $this->model_tran_mode_mstr = new model_tran_mode_mstr($this->db);
        $this->model_prop_owner_detail = new model_prop_owner_detail($this->db);
    }
    public function report()
    {
        $data =(array)null;
        //Transaction Mode List
        $transactionModeList = $this->model_tran_mode_mstr->getTranModeList();
        $data['transactionModeList'] = $transactionModeList;
        if($this->request->getMethod()=='post')
        {
            $from_date = $this->request->getVar('from_date');
            $to_date = $this->request->getVar('to_date');
            $tran_mode_mstr_id = $this->request->getVar('tran_mode_mstr_id');
            $data['to_date'] = $to_date;
            $data['from_date'] = $from_date;
            $data['tran_mode_mstr_id'] = $tran_mode_mstr_id;

            if($tran_mode_mstr_id!="")
            {
                $total= $this->model_transaction->calculateSumByTransactionMode($from_date,$to_date,$tran_mode_mstr_id);
                $data['total'] = $total;

                $data['transactionList'] = $this->model_view_transaction->getAllTransactionByDateAndTransactionMode($from_date,$to_date,$tran_mode_mstr_id);
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
                return view('report/collection_mode_wise_report',$data);
            }
            else
            {
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
                return view('report/collection_mode_wise_report',$data);
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
            return view('report/collection_mode_wise_report',$data);
        } 
    }
}
?>
