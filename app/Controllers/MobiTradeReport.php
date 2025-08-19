<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\TradeApplyLicenceModel;
use App\Models\TradeFirmOwnerModel;
use App\Models\TradeTradeItemsModel;
use App\Models\TradeTransactionModel;
use App\Models\model_trade_level_pending_dtl;
use App\Models\model_ulb_mstr;
use App\Models\TradeChequeDtlModel;
use App\Models\TradeViewApplyLicenceOwnerModel;
use App\Models\model_emp_details;
use App\Models\ModelTradeLicense;
use App\Models\TradeItemsMstrModel;
use App\Controllers\TradeApplyLicence;
use App\Models\model_application_type_mstr;
use App\Models\TradeApplyDenialModel;
use App\Models\model_view_ward_permission;



class MobiTradeReport extends MobiController
{
    protected $db;
    protected $dbSystem;
    protected $model_ward_mstr;
    protected $TradeApplyLicenceModel;
    protected $TradeFirmOwnerModel;
    protected $TradeTradeItemsModel;
    protected $TradeTransactionModel;
    protected $model_trade_level_pending_dtl;
    protected $model_ulb_mstr;
    protected $TradeChequeDtlModel;
    protected $TradeViewApplyLicenceOwnerModel;
    protected $model_emp_details;
    protected $TradeApplyLicenceController;
    protected $ModelTradeLicense;
    protected $tradeitemsmstrmodel;
    protected $model_application_type_mstr;
    protected $TradeApplyDenialModel;
    protected $model_view_ward_permission;


    public function __construct()
    {

        $session=session();
        $get_ulb_detail=$session->get('ulb_dtl');
        $this->ulb_id=$get_ulb_detail['ulb_mstr_id'];
        parent::__construct();
          helper(['db_helper', 'qr_code_generator_helper']);
        if($db_name = dbConfig("trade"))
            {
            $this->db = db_connect($db_name); 
        }
        if($db_system = dbSystem())
            {
            $this->dbSystem = db_connect($db_system); 
        }
        if($db_name = dbConfig("property"))
        {
            $this->property_db = db_connect($db_name);
        }
        
        $this->model_ward_mstr=new model_ward_mstr($this->dbSystem);
        $this->TradeApplyLicenceModel = new TradeApplyLicenceModel($this->db);
        $this->TradeFirmOwnerModel = new TradeFirmOwnerModel($this->db);
        $this->TradeTradeItemsModel = new TradeTradeItemsModel($this->db);
        $this->TradeTransactionModel = new TradeTransactionModel($this->db);
        $this->model_trade_level_pending_dtl = new model_trade_level_pending_dtl($this->db);
            $this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
            $this->TradeChequeDtlModel = new TradeChequeDtlModel($this->db);
            $this->TradeViewApplyLicenceOwnerModel= new TradeViewApplyLicenceOwnerModel($this->db);
            $this->model_emp_details = new model_emp_details($this->dbSystem);
        $this->ModelTradeLicense = new ModelTradeLicense($this->db);
        $this->TradeItemsMstrModel =  new tradeitemsmstrmodel($this->db);
            $this->TradeApplyLicenceController = new tradeapplylicence($this->db);
        $this->model_application_type_mstr = new model_application_type_mstr($this->db);
        $this->TradeApplyDenialModel = new TradeApplyDenialModel($this->db);
        $this->model_view_ward_permission = new model_view_ward_permission($this->dbSystem);

    }

    function __destruct() {
        $this->db->close();
        $this->dbSystem->close();
        $this->property_db->close();
    }

    public function applyLicenceReport()
    {   
        $data=array();
        $Session = Session();
        $data['ulb_mstr_id']=$this->ulb_id;
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
         $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
         $data['ward_list'] = $wardList;

         $ward="";

        $i=0;
        foreach($wardList as $key => $value)
        {
            if($i==0)
            {
                $ward=array($value['ward_mstr_id']);
            }
            else
            {
                array_push($ward, $value['ward_mstr_id']);
            }
            $i++;
        }
        $wardPermission =  implode(",",$ward);

        if($this->request->getMethod()=='get'){              
          $data['to_date'] = !empty($this->request->getVar('to_date'))? $this->request->getVar('to_date'): date('Y-m-d');
          $data['from_date'] = !empty($this->request->getVar('from_date'))? $this->request->getVar('from_date'): date('Y-m-d');
          $data['ward_id'] = !empty($this->request->getVar('ward_id'))?$this->request->getVar('ward_id'):"All";
             
            if($data['ward_id']!="All"){
                $where=" apply_date::date between '".$data['from_date']."' and '".$data['to_date']."' and ward_mstr_id=".$data['ward_id'];
                $colwhere=" transaction_date::date between '".$data['from_date']."' and '".$data['to_date']."' and ward_mstr_id=".$data['ward_id'];
                $levelwhere=" forward_date::date between '".$data['from_date']."' and '".$data['to_date']."'";
                $where="created_on::date between '".$data['from_date']."' and '".$data['to_date']."' and ward_id=".$data['ward_id'];
                $whereverrej="tbl_denial_mail_dtl.forward_date::date between '".$data['from_date']."' and '".$data['to_date']."' and tbl_denial_consumer_dtl.ward_id=".$data['ward_id'];

              }
            else {   
                $where=" apply_date::date between '".$data['from_date']."' and '".$data['to_date']."' and ward_mstr_id in($wardPermission)";
                $colwhere=" transaction_date::date between '".$data['from_date']."' and '".$data['to_date']."' and ward_mstr_id in($wardPermission)";
                $levelwhere=" forward_date::date between '".$data['from_date']."' and '".$data['to_date']."'";
                $where="created_on::date between '".$data['from_date']."' and '".$data['to_date']."' and ward_id in($wardPermission)";
                $whereverrej="tbl_denial_mail_dtl.forward_date::date between '".$data['from_date']."' and '".$data['to_date']."' and tbl_denial_consumer_dtl.ward_id in ($wardPermission)";
            }
            $data['newapplyLicense']=$this->TradeApplyLicenceModel->newapplyLicensereportTc($colwhere);
                $data['renewapplyLicense']=$this->TradeApplyLicenceModel->renewapplyLicensereportTc($colwhere);
                $data['amendapplyLicense']=$this->TradeApplyLicenceModel->amendapplyLicensereportTc($colwhere);
                  $data['surrendapplyLicense']=$this->TradeApplyLicenceModel->surrendapplyLicensereportTc($colwhere);
  
             // amount collection 
             $data['newlicencecollection']=$this->TradeApplyLicenceModel->newlicence_collectionTc($colwhere);
             $data['renewlicencecollection']=$this->TradeApplyLicenceModel->renewlicence_collectionTc($colwhere);
             $data['amendmentcollection']=$this->TradeApplyLicenceModel->amendment_collectionTc($colwhere);
             $data['surrendercollection']=$this->TradeApplyLicenceModel->surrender_collectionTc($colwhere);

             //denial
             $data['denialApply']=$this->TradeApplyDenialModel->totalDenialApply($where);
             $data['rejectedDenial']=$this->TradeApplyDenialModel->rejectedDenial($whereverrej);
             $data['approvedDenial']=$this->TradeApplyDenialModel->approvedDenial($whereverrej);
             $data['pendingAtEo']=$this->TradeApplyDenialModel->pendingAtEo($where);
             $data['wardWiseDenial']=$this->TradeApplyDenialModel->totalDenialApply($where);
 
         }
        // print_var($data['ward_id']);return;
        return view('mobile/trade/applyLicenceReport', $data);  

    }

    public function viewLicenceDetails($from_date,$to_date,$ward_id,$apptype)
    { 
        $data=array();
        $Session = Session();
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['ward_list'] = $wardList;
        $ward="";
        $i=0;
        foreach($wardList as $key => $value)
        {
            if($i==0)
            {
                $ward=array($value['ward_mstr_id']);
            }
            else
            {
                array_push($ward, $value['ward_mstr_id']);
            }
            $i++;
        }
        $wardPermission =  implode(",",$ward);
        $data['from_date'] = base64_decode($from_date);            
        $data['to_date'] = base64_decode($to_date);
        $data['ward_id'] = base64_decode($ward_id);
        $apptype = base64_decode($apptype);
        if($data['ward_id']!="All")
        {
            $where=" apply_date::date between '".$data['from_date']."' and '".$data['to_date']."' and ward_mstr_id=".$data['ward_id'];
            $colwhere=" transaction_date::date between '".$data['from_date']."' and '".$data['to_date']."' and tbl_transaction.ward_mstr_id=".$data['ward_id'];
            $where_collection=" transaction_date::date between '".$data['from_date']."' and '".$data['to_date']."' and ward_mstr_id=".$data['ward_id'];
            $levelwhere=" forward_date::date between '".$data['from_date']."' and '".$data['to_date']."'";
        }
        else 
        {   
            $where=" apply_date::date between '".$data['from_date']."' and '".$data['to_date']."' and ward_mstr_id in ($wardPermission)";
            $colwhere=" transaction_date::date between '".$data['from_date']."' and '".$data['to_date']."' and tbl_transaction.ward_mstr_id in ($wardPermission)";
            $levelwhere=" forward_date::date between '".$data['from_date']."' and '".$data['to_date']."'";
            $data['ward_id_name'] = "All";
            $where_collection=" transaction_date::date between '".$data['from_date']."' and '".$data['to_date']."' and ward_mstr_id in ($wardPermission)";
        }
        if($apptype==1)  
        { 
          $colwhere.=  "and transaction_type='NEW LICENSE'";
          $data['view_licence']=$this->TradeApplyLicenceModel->view_licence($colwhere);
          $data['application_type'] = "New License";
        } 
        elseif($apptype==2)
        {
          $colwhere.=  "and transaction_type='RENEWAL'";
          $data['view_licence']=$this->TradeApplyLicenceModel->view_licence($colwhere);
          $data['application_type'] = "Renewal";
        }
        elseif($apptype==3)
        {
          $colwhere.=  "and transaction_type='AMENDMENT'";
          $data['view_licence']=$this->TradeApplyLicenceModel->view_licence($colwhere);
          $data['application_type'] = "Amendment";

        }
        elseif($apptype==4)
        {
          $colwhere.=  "and transaction_type='SURRENDER'";
          $data['view_licence']=$this->TradeApplyLicenceModel->view_licence($colwhere);
          $data['application_type'] = "Surrender";
        }
        elseif($apptype=="total")
        {
          $colwhere.=  "and transaction_type in ('NEW LICENSE','RENEWAL','AMENDMENT','SURRENDER')";
          $data['view_licence']=$this->TradeApplyLicenceModel->view_licence($colwhere);
          $data['application_type'] = "All Licence Details";
        }
        elseif($apptype=="jsk")
        {
          $data['view_licence']=$this->TradeApplyLicenceModel->pendingJskView($where);
          $data['application_type'] = "Pending At JSK";
        }
        elseif($apptype=="prov")
        {
          $data['view_licence'] = $this->model_trade_level_pending_dtl->totalprovisionalview($where);
          $data['application_type'] = "Provisional";
        }
        elseif($apptype=="lvl")
        {
          $levelwhere.= "and status not in(2,4,5)";
          $data['view_licence']=$this->model_trade_level_pending_dtl->view_licenceFromlevel($levelwhere);
          $data['application_type'] = "Pending At Level";
        }
        elseif($apptype=="btc")
        {
          $levelwhere.= "and status = 2";
          $data['view_licence']=$this->model_trade_level_pending_dtl->view_licenceFromlevel($levelwhere);
          $data['application_type'] = "Back To Citizen";
        }
        elseif($apptype=="rejected")
        {
          $levelwhere.= "and status = 4";
          $data['view_licence']=$this->model_trade_level_pending_dtl->view_licenceFromlevel($levelwhere);
          $data['application_type'] = "Rejected Licence";
        }
        elseif($apptype=="fnl")
        {
          $levelwhere.= "and status = 5";
          $data['view_licence']=$this->model_trade_level_pending_dtl->view_licenceFromlevel($levelwhere);
          $data['application_type'] = "Approved Licence";
        }
        elseif($apptype=="newCollectn")
        {
          $colwhere.=  "and transaction_type='NEW LICENSE'";
          $data['view_licence']=$this->TradeApplyLicenceModel->AmountCollection($colwhere);
          $data['application_type'] = "New License Collection";
          $data['paid'] = "paid";

        }
        elseif($apptype=="renewCollectn")
        {
          $colwhere.=  "and transaction_type='RENEWAL'";
          $data['view_licence']=$this->TradeApplyLicenceModel->AmountCollection($colwhere);
          $data['application_type'] = "Renewal Collection";
          $data['paid'] = "paid";

        }
        elseif($apptype=="amedCollectn")
        {
          $colwhere.=  "and transaction_type='AMENDMENT'";
          $data['view_licence']=$this->TradeApplyLicenceModel->AmountCollection($colwhere);
          $data['application_type'] = "Amendment Collection";
          $data['paid'] = "paid";

        }
        elseif($apptype=="surrenCollectn")
        {
          $colwhere.=  "and transaction_type='SURRENDER'";
          $data['view_licence']=$this->TradeApplyLicenceModel->AmountCollection($colwhere);
          $data['application_type'] = "Surrender Collection";
          $data['paid'] = "paid";

        }
      
        foreach($data['view_licence'] as $key => $value)
        {
          $ward = $this->model_ward_mstr->getdatabyid($value['ward_mstr_id']);
          $applicationType = $this->model_application_type_mstr->trade_application_type_list($value['application_type_id']);
          $data['view_licence'][$key]['ward'] = $ward['ward_no'];
          $data['view_licence'][$key]['applicationType'] = $applicationType['application_type'];
        }

        return view('mobile/trade/viewApplyLicenceReport', $data);  
    }

    public function denial_details($from_date,$to_date,$ward_id,$status)
    {
        $data =(array)null;
        $Session = Session();

        $from_date = base64_decode($from_date);
        $to_date = base64_decode($to_date);
        $ward_id = base64_decode($ward_id);
        $status = base64_decode($status);
        $data['from_date'] = $from_date;            
        $data['to_date'] = $to_date;

         $emp_mstr = $Session->get("emp_details");
         $login_emp_details_id = $emp_mstr["id"];
         $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
         $data['ward_list'] = $wardList;

         $ward="";

        $i=0;
        foreach($wardList as $key => $value)
        {
            if($i==0)
            {
                $ward=array($value['ward_mstr_id']);
            }
            else
            {
                array_push($ward, $value['ward_mstr_id']);
            }
            $i++;
        }

        $wardPermission =  implode(",",$ward);
        if($status =="all") // getting all denail details 
        {
            $where="tbl_denial_consumer_dtl.created_on::date between '".$from_date."' and '".$to_date."' and tbl_denial_consumer_dtl.ward_id in ($wardPermission)";
            if($ward_id!="All")
            {
                 $where="tbl_denial_consumer_dtl.created_on::date between '".$from_date."' and '".$to_date."' and tbl_denial_consumer_dtl.ward_id='".$ward_id."'";
            }
            $data['denialDetails']=$this->TradeApplyDenialModel->denialDetails($where);
            $data['status'] = "All Denial Details";
        }
        elseif($status =="5" || $status =="4") // getting verified denail details  or getting rejected denail details
        {
            $where="tbl_denial_mail_dtl.forward_date::date between '".$from_date."' and '".$to_date."' and tbl_denial_mail_dtl.status = '".$status."' and tbl_denial_consumer_dtl.ward_id in ($wardPermission)";
            if($ward_id!="All")
            {
              $where="tbl_denial_mail_dtl.forward_date::date between '".$from_date."' and '".$to_date."' and tbl_denial_mail_dtl.status = '".$status."' and tbl_denial_consumer_dtl.ward_id='".$ward_id."'";

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
            $where="tbl_denial_mail_dtl.created_on::date between '".$from_date."' and '".$to_date."' and tbl_denial_mail_dtl.status='".$status."' and tbl_denial_consumer_dtl.ward_id in ($wardPermission)";
            if($ward_id!="All")
            {
              $where="tbl_denial_mail_dtl.created_on::date between '".$from_date."' and '".$to_date."' and tbl_denial_mail_dtl.status='".$status."' and tbl_denial_consumer_dtl.ward_id='".$ward_id."'";
            }
            $data['denialDetails']=$this->TradeApplyDenialModel->pendingEo($where);
            $data['status'] = "Pending At Executive Officer";
        }

        
        foreach($data['denialDetails'] as $key => $value){
            $ward = $this->model_ward_mstr->getdatabyid($value['ward_id']);
            $data['denialDetails'][$key]['ward']= $ward['ward_no'];  
         }
         return view('mobile/trade/denialAppliedList', $data);  
    }


    public function viewDetails($id)
    { 
      $data =(array)null;
      $Session = Session();
      $emp_mstr = $Session->get("emp_details");
      $data['emp_name'] = $emp_mstr['emp_name'];
      $data['denial_details']=$this->TradeApplyDenialModel->denialDetailsByID($id);
      $data['noticeDetails']  = $this->TradeApplyDenialModel->getNoticeDetails($data['denial_details']['id']);
      $data['approvedDocDetails']  = $this->TradeApplyDenialModel->getapprovedDocDetails($data['denial_details']['id']);
      $data['ward']  = $this->model_ward_mstr->getdatabyid($data['denial_details']['ward_id']);
      return view('mobile/trade/denialDetailsView', $data);  
    }
    
          
    public function wardWiseDenialReport()
    {
        $data = array();
        $data['ulb_mstr_id']=$this->ulb_id;
        $Session = Session();
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['ward_list'] = $wardList;
  
          $ward="";

        $i=0;
        foreach($wardList as $key => $value)
        {
            if($i==0)
            {
                $ward=array($value['ward_mstr_id']);
            }
            else
            {
                array_push($ward, $value['ward_mstr_id']);
            }
            $i++;
        }
        $wardPermission =  implode(",",$ward);

        if($this->request->getMethod()=='get')
        {  
            $data['to_date'] = $this->request->getVar('to_date');
            $data['from_date'] = $this->request->getVar('from_date');
            $data['ward_id'] = $this->request->getVar('ward_id');      
            $where=" created_on::date between '".$data['from_date']."' and '".$data['to_date']."' and tbl_denial_consumer_dtl.ward_id in($wardPermission)";
            $whereverrej="tbl_denial_mail_dtl.forward_date::date between '".$data['from_date']."' and '".$data['to_date']."' and tbl_denial_consumer_dtl.ward_id in($wardPermission)";
            $noticewhere="tbl_denial_notice.created_on::date between '".$data['from_date']."' and '".$data['to_date']."' and tbl_denial_consumer_dtl.ward_id in($wardPermission)";

            if($data['ward_id']!="All")
            {
              $where=" created_on::date between '".$data['from_date']."' and '".$data['to_date']."' and tbl_denial_consumer_dtl.ward_id = '".$data['ward_id']."'";
              $whereverrej="tbl_denial_mail_dtl.forward_date::date between '".$data['from_date']."' and '".$data['to_date']."' and tbl_denial_consumer_dtl.ward_id = '".$data['ward_id']."'";
              $noticewhere="tbl_denial_notice.created_on::date between '".$data['from_date']."' and '".$data['to_date']."' and tbl_denial_consumer_dtl.ward_id = '".$data['ward_id']."'";

            }
            $data['denialApply']=$this->TradeApplyDenialModel->totalDenialApply($where);
            $data['rejectedDenial']=$this->TradeApplyDenialModel->rejectedDenial($whereverrej);
            $data['approvedDenial']=$this->TradeApplyDenialModel->approvedDenial($whereverrej);
            $data['applyByNotice']=$this->TradeApplyDenialModel->applyWithNotice($noticewhere);
        }

        return view('mobile/trade/denialAppliedmenuByWardWise',$data);
    }

    public function wardWiseDenialDetails($from_date,$to_date,$ward_id,$status)
    {
        $data =(array)null;
        $Session = Session();

        $from_date = base64_decode($from_date);
        $to_date = base64_decode($to_date);
        $ward_id = base64_decode($ward_id);
        $status = base64_decode($status);
        $data['from_date'] = $from_date;            
        $data['to_date'] = $to_date;
        $data['status'] = $status;

        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['ward_list'] = $wardList;
  
        $ward="";
        $i=0;
        foreach($wardList as $key => $value)
        {
            if($i==0)
            {
                $ward=array($value['ward_mstr_id']);
            }
            else
            {
                array_push($ward, $value['ward_mstr_id']);
            }
            $i++;
        }
        $wardPermission =  implode(",",$ward);

        if($status == 'ttl')//total denial by ward
        {
          $where="tbl_denial_consumer_dtl.created_on::date between '".$from_date."' and '".$to_date."'";
            if($ward_id!="All")
            {
                $where.=  "and tbl_denial_consumer_dtl.ward_id='".$ward_id."'";
            }
            $data['denial'] = 'All Denial Report'; 

        }
        elseif($status == 5) //approve denial by ward
        {
          $where="tbl_denial_mail_dtl.forward_date::date between '".$from_date."' and '".$to_date."' and tbl_denial_mail_dtl.status = 5";
          if($ward_id!="All")
          {
              $where.=  "and tbl_denial_consumer_dtl.ward_id='".$ward_id."'";
          }
          $data['denial'] = 'Approved Denial Report'; 

        }
        elseif($status == 4) // rejected denial by ward
        {
          $where="tbl_denial_mail_dtl.forward_date::date between '".$from_date."' and '".$to_date."' and tbl_denial_mail_dtl.status = 4";
          if($ward_id!="All")
          {
              $where.=  "and tbl_denial_consumer_dtl.ward_id='".$ward_id."'";
          }
          $data['denial'] = 'Rejected Denial Report'; 

        }
        elseif($status == 2) // apply by notice by ward
        {
          $where="tbl_apply_licence.created_on::date between '".$from_date."' and '".$to_date."' and tbl_denial_notice.status =2";
          if($ward_id!="All")
          {
              $where.=  "and tbl_denial_consumer_dtl.ward_id='".$ward_id."'";
          }
          $data['denial'] = 'Apply New Licence By Notice'; 
        }

        $data['denialDetails']=$this->TradeApplyDenialModel->wardWiseDenialDetails($where,$status,$ward_id, $wardPermission);
        return view('mobile/trade/denialAppliedListWardWise', $data);  
    }

    public function viewDetailsByWard($from_date,$to_date,$wardId,$status)
    { 
      $from_date = base64_decode($from_date);
      $to_date = base64_decode($to_date);
      $ward_id = base64_decode($wardId);
      $status = base64_decode($status);
      $data['from_date'] = $from_date;            
      $data['to_date'] = $to_date;
      $data['status'] = $status;
      if($status == 'ttl')//total denial by ward
      {
        $where="tbl_denial_consumer_dtl.created_on::date between '".$from_date."' and '".$to_date."' and tbl_denial_consumer_dtl.ward_id='".$ward_id."'";
        $data['denial'] = 'All Denial Report'; 
      }
      elseif($status == 5) //approve denial by ward
      {
        $where="tbl_denial_mail_dtl.forward_date::date between '".$from_date."' and '".$to_date."' and tbl_denial_mail_dtl.status = 5 
        and tbl_denial_consumer_dtl.ward_id='".$ward_id."'";
        
        $data['denial'] = 'Approved Denial Report'; 

      }
      elseif($status == 4) // rejected denial by ward
      {
        $where="tbl_denial_mail_dtl.forward_date::date between '".$from_date."' and '".$to_date."' and tbl_denial_mail_dtl.status = 4
        and tbl_denial_consumer_dtl.ward_id='".$ward_id."'";
        
        $data['denial'] = 'Rejected Denial Report'; 
      }
      elseif($status == 2) // apply by notice by ward
      {
        $where="tbl_apply_licence.created_on::date between '".$from_date."' and '".$to_date."' and tbl_denial_notice.status =2
        and tbl_denial_consumer_dtl.ward_id='".$ward_id."'";
        
        $data['denial'] = 'Apply New Licence By Notice'; 
      }

      $data['denial_details']=$this->TradeApplyDenialModel->DenialDetailsByWardId($where,$status);

      return view('mobile/trade/denialAppliedViewWardWise',$data);
    }

    public function reports_menu()
    {
      return view('mobile/trade/reports_menu');
    }
    public function tc_team_summary()
    {
        $data = array();
        $data['module']="Trade";
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
        
        if($this->request->getMethod()=='get')
        {
            $from_date = !empty($this->request->getVar('from_date'))? $this->request->getVar('from_date'): date('Y-m-d');
            $to_date = !empty($this->request->getVar('to_date'))? $this->request->getVar('to_date'): date('Y-m-d');
            $ward_id = !empty($this->request->getVar('ward_id'))? $this->request->getVar('ward_id'): '';
            $data['to_date'] = $to_date;
            $data['from_date'] = $from_date;
            $data['ward_id'] = $ward_id;
            $and = '';
            if($ward_id!='')
            {
                $and = "and t.ward_mstr_id = $ward_id ";
            }
            

        } 
        $where="where t.emp_details_id=$login_emp_details_id 
                    and cast(t.transaction_date as date) between '$from_date'
                    and '$to_date' $and";

        $tbl = "from tbl_transaction t                 
                $where";

        $sql_tr="  select t.id as transactin_id,t.transaction_no,t.paid_amount,t.transaction_type,
                    cast(t.transaction_date as date) as created_on,payment_mode
                    $tbl ";
        $data['from_date'] = $from_date;
        $data['to_date']= $to_date;
        $transaction = $this->TradeTransactionModel->row_query($sql_tr);
        $data['transaction']=$transaction ;
        $sql_total = "select sum(paid_amount) $tbl";
        $data["total"] = $this->TradeTransactionModel->row_query($sql_total)[0]['sum']??0;
        $data['emp_dtls'] = $this->model_emp_details->emp_dtls($login_emp_details_id);
        $data['ulb_mstr_name'] = $this->model_ulb_mstr->getulb_list($data['ulb_mstr_id']); 
        return view('mobile/water/reports/tc_team_summary',$data);
    }

}
?>
