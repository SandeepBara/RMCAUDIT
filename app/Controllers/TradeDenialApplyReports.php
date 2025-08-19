<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\TradeApplyDenialModel;
 
class TradeDenialApplyReports extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $trade;
    protected $ward_model;
    protected $TradeApplyDenialModel;

    public function __construct(){

        $session=session();
        $get_ulb_detail=$session->get('ulb_dtl');
 
        $this->ulb_id=$get_ulb_detail['ulb_mstr_id'];

        parent::__construct();
    	helper(['db_helper','form']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name); 
        }
        if($db_name = dbConfig("trade")){
            $this->trade = db_connect($db_name); 
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        }
       
        $this->model_ward_mstr=new model_ward_mstr($this->dbSystem);
        $this->TradeApplyDenialModel=new TradeApplyDenialModel($this->trade);
 
    }


    //reports 
    public function report()
    {
        $data =(array)null;
        $data['ulb_mstr_id']=$this->ulb_id;
        $data['ward_list']=$this->model_ward_mstr->getWardList($data);

        if($this->request->getMethod()=='post')
        {
            $from_date = $this->request->getVar('from_date');
            $to_date = $this->request->getVar('to_date');
            $ward_id = $this->request->getVar('ward_id');
            
            $data['to_date'] = $to_date;
            $data['from_date'] = $from_date;
            $data['ward_id'] = $ward_id;
             
            if($data['ward_id']!="all")
            {
                //$where1="created_on::date between '".$data['from_date']."' and '".$data['to_date']."'";
                $where="created_on::date between '".$data['from_date']."' and '".$data['to_date']."' and ward_id=".$data['ward_id'];
                $whereverrej="tbl_denial_mail_dtl.forward_date::date between '".$data['from_date']."' and '".$data['to_date']."' and tbl_denial_consumer_dtl.ward_id=".$data['ward_id'];
                $data['denialApply']=$this->TradeApplyDenialModel->totalDenialApply($where);
                $data['rejectedDenial']=$this->TradeApplyDenialModel->rejectedDenial($whereverrej);
                $data['approvedDenial']=$this->TradeApplyDenialModel->approvedDenial($whereverrej);
                $data['pendingAtEo']=$this->TradeApplyDenialModel->pendingAtEo($where);
            }
            else 
            {   
                    $where="created_on::date between '".$data['from_date']."' and '".$data['to_date']."'";
                    $whereverrej="tbl_denial_mail_dtl.forward_date::date between '".$data['from_date']."' and '".$data['to_date']."'";
                    $data['denialApply']=$this->TradeApplyDenialModel->totalDenialApply($where);
                    $data['rejectedDenial']=$this->TradeApplyDenialModel->rejectedDenial($whereverrej);
                    $data['approvedDenial']=$this->TradeApplyDenialModel->approvedDenial($whereverrej);
                    $data['pendingAtEo']=$this->TradeApplyDenialModel->pendingAtEo($where);
            }
        }
        else
        {
            $data['to_date'] = date('Y-m-d');
            $data['from_date'] = date('Y-m-d');
            $data['ward_id'] = "all";
            $where="created_on::date between '".$data['from_date']."' and '".$data['to_date']."'";
            $whereverrej="tbl_denial_mail_dtl.forward_date::date between '".$data['from_date']."' and '".$data['to_date']."'";
            $data['denialApply']=$this->TradeApplyDenialModel->totalDenialApply($where);
			$data['rejectedDenial']=$this->TradeApplyDenialModel->rejectedDenial($whereverrej);
			$data['approvedDenial']=$this->TradeApplyDenialModel->approvedDenial($whereverrej);
            $data['pendingAtEo']=$this->TradeApplyDenialModel->pendingAtEo($where);
        }
        return view('report/trade_Denialapply_report',$data);
    }

    public function denial_details($from_date,$to_date,$ward_id,$status)
    {
        $data =(array)null;
        $from_date = base64_decode($from_date);
        $to_date = base64_decode($to_date);
        $ward_id = base64_decode($ward_id);
        $status = base64_decode($status);
        if($status =="all") // getting all denail details 
        {
            $where="tbl_denial_consumer_dtl.created_on::date between '".$from_date."' and '".$to_date."'";
            if($ward_id!="all")
            {
                $where.=  "and tbl_denial_consumer_dtl.ward_id='".$ward_id."'";
            }
            $data['denialDetails']=$this->TradeApplyDenialModel->denialDetails($where);
            $data['status'] = "All Denial Details";
        }
        elseif($status =="5" || $status =="4") // getting verified denail details  or getting rejected denail details
        {
            $where="tbl_denial_mail_dtl.forward_date::date between '".$from_date."' and '".$to_date."' and tbl_denial_mail_dtl.status = '".$status."'";
            if($ward_id!="all")
            {
                $where.=  "and tbl_denial_consumer_dtl.ward_id='".$ward_id."'";
            }
            $data['denialDetails']=$this->TradeApplyDenialModel->rejectApproved($where);
            if($status =="5")
            {
                $data['status'] = "Approved Denial Details";

            }
            else
            {
                $data['status'] = "Rejected Denial Details";

            }
 
        }
        
        elseif($status =="1") // getting pending at executive officer  denail details
        {
            $where="tbl_denial_mail_dtl.created_on::date between '".$from_date."' and '".$to_date."' and tbl_denial_mail_dtl.status='".$status."'";
            if($ward_id!="all")
            {
                $where.=  "and tbl_denial_consumer_dtl.ward_id='".$ward_id."'";
            }
            $data['denialDetails']=$this->TradeApplyDenialModel->pendingEo($where);
            $data['status'] = "Pending At Executive Officer";

        }

        $empDtlList = $this->trade->query("select * from view_emp_details")->getResultArray();
        foreach ($data['denialDetails'] as $key => $value) {
            $empDtl = array_values(array_filter($empDtlList,function($emp)use($value){
                return $value["emp_details_id"]==$emp["id"];
            }))[0]??[];
            $data['denialDetails'][$key]['full_emp_name'] = $empDtl['full_emp_name']??"";
            $ward = $this->model_ward_mstr->getdatabyid($value['ward_id']);
            $data['denialDetails'][$key]['ward'] = $ward['ward_no'];
        }

        return view('report/trade_Denialapply_list',$data);  
    }

    public function viewDetails($id)
    {
            $data =(array)null;
            $data['denial_details']=$this->TradeApplyDenialModel->denialDetailsByID($id);
            $data['noticeDetails']  = $this->TradeApplyDenialModel->getNoticeDetails($data['denial_details']['id']);
             $data['approvedDocDetails']  = $this->TradeApplyDenialModel->getapprovedDocDetails($data['denial_details']['id']);
            $data['ward']  = $this->model_ward_mstr->getdatabyid($data['denial_details']['ward_id']);

         
        return view('report/trade_Denialapply_view',$data);  
    }



     

   

   

    

  








 

        




}
?>
