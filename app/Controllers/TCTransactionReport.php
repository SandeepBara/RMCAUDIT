<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_transaction;
use App\Models\model_prop_dtl;
use App\Models\model_saf_dtl;
use App\Models\model_saf_owner_detail;
use App\Models\model_view_transaction;
use App\Models\model_prop_owner_detail;
use App\Models\model_ward_mstr;
use App\Models\model_view_ward_permission;
use App\Models\model_emp_details;
use App\Models\model_ulb_mstr;

class TCTransactionReport extends MobiController
{


    
    protected $db;
    protected $model_transaction;
    protected $model_prop_dtl;
    protected $model_saf_dtl;
    protected $model_saf_owner_detail;
    protected $model_view_transaction;
    protected $model_prop_owner_detail;
    protected $model_ward_mstr;
	protected $model_view_ward_permission;
    public function __construct(){
        parent::__construct();
    	helper(['db_helper']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name); 
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        }
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_transaction = new model_transaction($this->db);
        $this->model_prop_dtl = new model_prop_dtl($this->db);
        $this->model_saf_dtl = new model_saf_dtl($this->db);
        $this->model_saf_owner_detail = new model_saf_owner_detail($this->db);
        $this->model_view_transaction = new model_view_transaction($this->db);
        $this->model_prop_owner_detail = new model_prop_owner_detail($this->db);
		$this->model_view_ward_permission=new model_view_ward_permission($this->dbSystem);
        $this->modelemp = new model_emp_details($this->dbSystem);
        $this->modelUlb = new model_ulb_mstr($this->dbSystem);
    }

    function __destruct() {
		$this->db->close();
		$this->dbSystem->close();
	}

    public function datewise_ward_transaction_report()
    {
        $data =(array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];        
        $emp_mstr = $Session->get("emp_details");
        $tran_by_emp_details_id = $emp_mstr["id"];
        $wardList = $this->model_ward_mstr->getWardListForReport($data['ulb_mstr_id']);
        $data['wardList'] = $wardList;
        helper(['form']);
        if($this->request->getMethod()=='post')
        {
            $from_date = $this->request->getVar('from_date');
            $to_date = $this->request->getVar('to_date');
            $ward_id = $this->request->getVar('ward_id');
            $data['to_date'] = $to_date;
            $data['from_date'] = $from_date;
            $data['ward_id'] = $ward_id;
            if($ward_id!="")
            {
                $total= $this->model_transaction->calculatewardwiseSumForTCSafAndProperty($from_date,$to_date,$ward_id,$tran_by_emp_details_id);
                $data['total'] = $total;
                $data['transactionList'] = $this->model_view_transaction->getAllWardwiseTransactionByDateForTC($from_date,$to_date,$ward_id,$tran_by_emp_details_id);
                //print_r($data['transactionList']);exit;
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
                return view('mobile/report/datewise_ward_transaction_report',$data);
            }            
            else
            {
                $total= $this->model_transaction->calculateSumByTransactionEmpDetailsId($tran_by_emp_details_id,$from_date,$to_date);
                $data['total'] = $total;
                $data['transactionList'] = $this->model_view_transaction->getAllTransactionByEmpId($tran_by_emp_details_id,$from_date,$to_date);
                //print_var($data['transactionList']);exit;
                foreach ($data['transactionList'] as $key => $value)
                {
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
                return view('mobile/report/datewise_ward_transaction_report',$data);
            }

        } 
        else
        {
            $from_date = date('Y-m-d');
            $to_date = date('Y-m-d');
            $total= $this->model_transaction->calculateSumByTransactionEmpDetailsId($tran_by_emp_details_id,$from_date,$to_date);
            $data['total'] = $total;
            $data['transactionList'] = $this->model_view_transaction->getAllTransactionByEmpId($tran_by_emp_details_id,$from_date,$to_date);
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
            // echo "<pre>";
            // print_r($data);
            // return;
             
            return view('mobile/report/datewise_ward_transaction_report',$data);
        } 
    }

    public function datewise_proptype_transaction_report()
    {
        $data =(array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];        
        $emp_mstr = $Session->get("emp_details");
        $tran_by_emp_details_id = $emp_mstr["id"];
        helper(['form']);
        if($this->request->getMethod()=='post')
        {
            $from_date = $this->request->getVar('from_date');
            $to_date = $this->request->getVar('to_date');
            $tran_type = $this->request->getVar('tran_type');
            $data['to_date'] = $to_date;
            $data['from_date'] = $from_date;
            $data['tran_type'] = $tran_type;
			
            if($tran_type=="Saf")
            {
                $total= $this->model_transaction->calculateSumForTCSafAndProperty($from_date,$to_date,$tran_type,$tran_by_emp_details_id);
                $data['total'] = $total;
                $data['transactionList'] = $this->model_view_transaction->getAllPropTypeTransactionByDateForTC($from_date,$to_date,$tran_type,$tran_by_emp_details_id);
               // print_r($data['transactionList']);
                foreach ($data['transactionList'] as $key => $value){
                    $prop_dtl_id = $value['prop_dtl_id'];
                    $data['transactionList'][$key]['holding'] = $this->model_saf_dtl->getSafdetails($prop_dtl_id);

                    $data['transactionList'][$key]['owner'] = $this->model_saf_owner_detail->getSafOwnerDetails($prop_dtl_id);
                }
                return view('mobile/report/datewise_proptype_transaction_report',$data);
            }
            else if($tran_type=="Property")
            {
                $total= $this->model_transaction->calculateSumForTCSafAndProperty($from_date,$to_date,$tran_type,$tran_by_emp_details_id);
                $data['total'] = $total;
                $data['transactionList'] = $this->model_view_transaction->getAllPropTypeTransactionByDateForTC($from_date,$to_date,$tran_type,$tran_by_emp_details_id);
               // print_r($data['transactionList']);
                foreach ($data['transactionList'] as $key => $value){
                    $prop_dtl_id = $value['prop_dtl_id'];
                    $data['transactionList'][$key]['holding'] = $this->model_prop_dtl->getPropdetails($prop_dtl_id);
                    $data['transactionList'][$key]['owner'] = $this->model_prop_owner_detail->getPropertyOwnerDetails($prop_dtl_id);
                }
                return view('mobile/report/datewise_proptype_transaction_report',$data);
            }
            else
            {
                $total= $this->model_transaction->calculateSumByTransactionEmpDetailsId($tran_by_emp_details_id,$from_date,$to_date);
                $data['total'] = $total;
                $data['transactionList'] = $this->model_view_transaction->getAllTransactionByEmpId($tran_by_emp_details_id,$from_date,$to_date);
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
                return view('mobile/report/datewise_proptype_transaction_report',$data);
            }

        } 
        else
        {
            $from_date = date('Y-m-d');
            $to_date = date('Y-m-d');
            $total= $this->model_transaction->calculateSumByTransactionEmpDetailsId($tran_by_emp_details_id,$from_date,$to_date);
            $data['total'] = $total;
            $data['transactionList'] = $this->model_view_transaction->getAllTransactionByEmpId($tran_by_emp_details_id,$from_date,$to_date);
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
            return view('mobile/report/datewise_proptype_transaction_report',$data);
        } 
    }
	
	public function tc_team_summary()
    {
        $data = array();
        $data['module']="Property";
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];        
        $emp_mstr = $Session->get("emp_details");
        $tran_by_emp_details_id = $emp_mstr["id"];
        $login_emp_details_id = $emp_mstr["id"];
        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['wardList'] = $wardList;
        
        helper(['form']);
        $from_date = date('Y-m-d');
        $to_date = date('Y-m-d');    
        $and = '';    
        
        if($this->request->getMethod()=='post')
        {
            $from_date = $this->request->getVar('from_date');
            $to_date = $this->request->getVar('to_date');
            $ward_id = $this->request->getVar('ward_id');
            $data['to_date'] = $to_date;
            $data['from_date'] = $from_date;
            $data['ward_id'] = $ward_id;
            $and = '';
            if($ward_id!='')
            {
                $and = "and t.ward_mstr_id = $ward_id ";
            }
            

        } 
        $where="where t.tran_by_emp_details_id=$login_emp_details_id 
                    and cast(t.tran_date as date) between '$from_date'
                    and '$to_date' $and";

        $tbl = "from tbl_transaction t                 
                $where";

        $sql_tr="  select t.id as transactin_id,t.tran_no as transaction_no,
                    t.payable_amt as paid_amount,t.tran_type as transaction_type,
                    cast(t.tran_date as date) as created_on,t.tran_mode as payment_mode
                    $tbl ";
        $gov_tbl = "from tbl_govt_saf_transaction t 
                    $where";
        $gov_tr = " select t.id as transactin_id,t.tran_no as transaction_no,
                    t.payable_amt as paid_amount,'gov' as transaction_type,
                    cast(t.tran_date as date) as created_on,t.tran_mode as payment_mode
                    $gov_tbl ";
        $data['from_date'] = $from_date;
        $data['to_date']= $to_date;
        $transaction = $this->model_transaction->row_sql($sql_tr);
        $gov_tr = $this->model_transaction->row_sql($gov_tr);
        foreach($gov_tr as $val)
        {
            $transaction[]=$val;
        }
        //print_var($transaction);
        $data['transaction']=$transaction ;
        $sql_total = "select sum(payable_amt) $tbl";
        $sql_gov_total = "select sum(payable_amt) $gov_tbl";
        $data["total"] = $this->model_transaction->row_sql($sql_total)[0]['sum']??0;
        $data["total"]=$data["total"]+ $this->model_transaction->row_sql($sql_gov_total)[0]['sum']??0;
        $data['emp_dtls'] = $this->modelemp->emp_dtls($login_emp_details_id);
        $data['ulb_mstr_name'] = $this->modelUlb->getulb_list($data['ulb_mstr_id']); 
        return view('mobile/water/reports/tc_team_summary',$data);
    }

}
?>
