<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_transaction;
use App\Models\model_prop_dtl;
use App\Models\model_prop_owner_detail;
use App\Models\model_view_property_collection_report;
use App\Models\model_ward_mstr;

class PropertyCollectionWardWise extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $model_transaction;
    protected $model_prop_dtl;
    protected $model_prop_owner_detail;
    protected $model_view_property_collection_report;
    protected $model_ward_mstr;
    public function __construct(){
        parent::__construct();
    	helper(['db_helper', 'form']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name); 
        }
        if($db_name = dbSystem()){
            $this->dbSystem = db_connect($db_name); 
        }
        $this->model_transaction = new model_transaction($this->db);
        $this->model_prop_dtl = new model_prop_dtl($this->db);
        $this->model_prop_owner_detail = new model_prop_owner_detail($this->db);
        $this->model_view_property_collection_report = new model_view_property_collection_report($this->db);
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
    }
    public function report()
    {
        $data =(array)null;
        $session = session();
        $ulb_dtl = $session->get('ulb_dtl');
        $ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];
        //Transaction Mode List
        $wardList = $this->model_ward_mstr->getWardListForReport($ulb_mstr_id);
        $data['wardList'] = $wardList;
        if($this->request->getMethod()=='post')
        {   
            $total =0;
            $from_date = $this->request->getVar('from_date');
            $to_date = $this->request->getVar('to_date');
            $ward_mstr_id = $this->request->getVar('ward_mstr_id');
            $data['to_date'] = $to_date;
            $data['from_date'] = $from_date;
            $data['ward_mstr_id'] = $ward_mstr_id;
            if($ward_mstr_id!=""){
                $data['transactionList'] = $this->model_view_property_collection_report->getAllTransactionWard($from_date,$to_date,$ward_mstr_id);
            }
            else{ 
                $data['transactionList'] = $this->model_view_property_collection_report->getAllTransaction($from_date,$to_date);
            }
            foreach ($data['transactionList'] as $key => $value)
            {
                $total =$total+$value['payable_amt'];
                $prop_dtl_id = $value['prop_dtl_id'];
                $data['transactionList'][$key]['holding'] = $this->model_prop_dtl->getPropdetails($prop_dtl_id);
                $data['transactionList'][$key]['owner'] = $this->model_prop_owner_detail->getPropertyOwnerDetails($prop_dtl_id);
            }
            $data['total'] = $total;
            return view('report/property_collection_ward_wise_report',$data);
        }
        else
        {
            $from_date = date('Y-m-d');
            $data['transactionList'] = $this->model_view_property_collection_report->getAllTransaction($from_date,$from_date);
            foreach ($data['transactionList'] as $key => $value)
            {
                $total =$total+$value['payable_amt'];
                $prop_dtl_id = $value['prop_dtl_id'];
                $data['transactionList'][$key]['holding'] = $this->model_prop_dtl->getPropdetails($prop_dtl_id);
                $data['transactionList'][$key]['owner'] = $this->model_prop_owner_detail->getPropertyOwnerDetails($prop_dtl_id);
            }
            return view('report/property_collection_ward_wise_report',$data);
        } 
    }
}
?>
