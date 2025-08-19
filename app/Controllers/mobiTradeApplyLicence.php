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
use App\Models\model_view_ward_permission;

class mobitradeapplylicence extends MobiController
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
    protected $model_view_ward_permission;

    public function __construct(){
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
        $this->model_view_ward_permission = new model_view_ward_permission($this->dbSystem);
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
        
    }

    function __destruct() {
		$this->db->close();
        $this->dbSystem->close();
        $this->property_db->close();
	}

    public function index()
    {
        $data=array();
        // $Session = Session();
        // $ulb_mstr = $Session->get("ulb_dtl");
        // $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        // $data['ward_list']=$this->model_ward_mstr->getWardList($data);
        $session = session();
        $get_emp_details = $session->get('emp_details');
        $data = filterSanitizeStringtoUpper($this->request->getVar());
        if($get_emp_details["user_type_mstr_id"]==5)        
        {
            $emp_id = $get_emp_details['id'];
            $ward_list = $this->model_view_ward_permission->getPermittedWard($emp_id);            

            $wardIds = array_map(function($val){
                return $val["ward_mstr_id"];
            },$ward_list);
            $wardIds = implode(",",$wardIds);
            $data["ward_id"] = $wardIds;
          
        }
        if(isset($data["keyword"]) && isset($data["from_date"]) && isset($data["to_date"]))
        {
            $data["fromdate"]=$data['from_date'];
            $data["todate"]=$data['to_date'];
            if(isset($data['keyword']) && $data['keyword'])
            { 
                $data['keyword']=$data['keyword'];
                $data['application_details']=$this->TradeViewApplyLicenceOwnerModel->fetch_license_details_by_keyword($data);  
            }else {
                $data['application_details']=$this->TradeViewApplyLicenceOwnerModel->fetch_aaplication_details($data);
            } 
            // print_var($inputs);die();
        }    


        return view('mobile/trade/SearchLicense', $data);
    }


    public function trade_licence_view($id)
    {
        $data=array();
        $data['licencee']=$this->ModelTradeLicense->apply_licence_md5($id);
        $data["holding_no"]=$data['licencee']["holding_no"];
        $warddet=$this->model_ward_mstr->getWardNoBywardId($data['licencee']['ward_mstr_id']);
        $data['ward_no']=$warddet["ward_no"];
        $data["application_status"]=$this->TradeApplyLicenceController->applicationStatus_md5($id);
        $data['firm_owner']=$this->TradeFirmOwnerModel->getdatabyid_md5($id);
        //Get nature Of Bussiness
        $data['tradeItemId'] = $this->TradeApplyLicenceModel->getNatureOfBusinessId($id);
        $data['trade_items'] = !empty($data['tradeItemId'])?$this->TradeItemsMstrModel->getTradeItemDetails($data['tradeItemId']):[];
        $data['trans_detail']=$this->TradeTransactionModel->alltransaction_details($id);
        //print_var($data['licencee']);
        $sql_notice = "select * from tbl_denial_notice where md5(apply_id::text) = '$id' and status = 2";
        $noticeDetails = $this->TradeTransactionModel->row_query($sql_notice,array())[0]??[]; 
        $data['notice_date']=!empty($noticeDetails)?$noticeDetails['created_on']:null; 
        $data['application_type']['id']= $data['licencee']['application_type_id'];
        return view('mobile/trade/trade_licence_details_view', $data);
    }
    
    public function view_transaction_receipt($applyid=null,$transaction_id=null)
    {
        $data=array();
        $data['transaction_id']=$transaction_id;
        $data['status'] = [1,2];
        $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id']; 
        $data['ulb_mstr_name'] = $this->model_ulb_mstr->getulb_list($ulb_mstr_id);
        $data['transaction_details']=$this->TradeTransactionModel->transaction_details($transaction_id);
        $data['applicant_details']=$this->TradeViewApplyLicenceOwnerModel->getFirmData($data['transaction_details']['related_id']); 
        $data['emp_details']=$this->model_emp_details->emp_dtls($data['transaction_details']['emp_details_id']); 
        $warddet=$this->model_ward_mstr->getWardNoBywardId($data['transaction_details']['ward_mstr_id']);
        $data['ward_no']=$warddet["ward_no"];
        $data['cheque_details']=$this->TradeChequeDtlModel->alltransactioncheque_details($transaction_id);

        //print_var($data);
        return view('mobile/trade/payment_receipt', $data);  
    }

    public function view_provisional($id=null)
    {   
        $data =(array)null;
       $Session = Session();
       date_default_timezone_set('Asia/Kolkata');
       $ulb_mstr = $Session->get("ulb_dtl");
       $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id']; 
       $path=base_url('citizenPaymentReceipt/view_trade_provisinal_receipt/'.$ulb_mstr_id.'/'.$id);
       $data['ss']=qrCodeGeneratorFun($path);
      
       $emp_mstr = $Session->get("emp_details");
       $data['applicant_details']=$this->TradeApplyLicenceModel->fetch_all_application_data($id);
       $login_emp_details_id = $emp_mstr["id"];
       $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
       $data['ulb'] = $this->model_ulb_mstr->getulb_list($ulb_mstr_id);
       $data['apply_id'] = $this->TradeApplyLicenceModel->getlicenceID($id); 
       $data['basic_details'] = $this->TradeViewApplyLicenceOwnerModel->get_licenceDetails($data['apply_id']['id']); 
       //print_r( $data['basic_details']);exit;
       $vUpto = $data['basic_details']['apply_date'];
        $data["valid_upto"] = date('Y-m-d',strtotime(date("$vUpto", time()) . " + 20 day"));
       $apply_licence_id=$data['basic_details']['id'];
        // $data['item_details'] = $this->TradeTradeItemsModel->getdatabyid_md5($id);
        // print_var($data['item_details']);return;
       $data['tranProvDtl'] = $this->TradeTransactionModel->tranProvDtl($id);
        return view('mobile/trade/provisional', $data);  
    }

    
    
    


}
?>
