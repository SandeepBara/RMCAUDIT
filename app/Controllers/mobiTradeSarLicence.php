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
use App\Models\tradeapplicationtypemstrmodel;
use App\Models\model_view_ward_permission;


class mobiTradeSarLicence extends MobiController
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
    protected $tradeapplicationtypemstrmodel;
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
        $this->tradeapplicationtypemstrmodel =  new tradeapplicationtypemstrmodel($this->db);        
        $this->model_view_ward_permission = new model_view_ward_permission($this->dbSystem);
        
    }

    public function index2($id=null)
    {

        $data=array();
        $data['id'] = $id;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $data['ward_list']=$this->model_ward_mstr->getWardList($data);

        // if($this->request->getMethod()=='post')
        // {
        //     $inputs = filterSanitizeStringtoUpper($this->request->getVar());
        //     $data['keyword']=$inputs['keyword'];
        //     // if(md5($id)==md5(4)){}
        //     // elseif(md5($id)==md5(3)){}
        //     // elseif(md5($id)==md5(2)){}
        //     // else{}
        //     // $data["fromdate"]=$inputs['from_date'];
        //     // $data["todate"]=$inputs['to_date'];

        //     // print_var($inputs);die();
        // } 
        // else
        // {
        //     $data["fromdate"]=date('Y-m-d');
        //     $data["todate"]=date('Y-m-d');          
        // }  
        if(isset($_GET['btn_search'])){ 
            $data['keyword']  = $_GET['keyword'];
        }
        if(isset($data['keyword']) && $data['keyword']!=null)
        {
            $data['application_details']=$this->TradeViewApplyLicenceOwnerModel->fetch_license_details_by_keyword2($data);
            if(in_array($id,[md5(4),md5(3),md5(2)]))
                $data['application_details']=$this->TradeViewApplyLicenceOwnerModel->getNewLicenceByLicenceNo($data['keyword']);
            // print_var($data['application_details']);return;
            if(!empty($data['application_details']))
            {
                if($id == md5(4))
                { //surrender

                    if(strtotime($data['application_details']['valid_upto'])>strtotime(date('Y-m-d')))
                    {
                        $data['application_details']['view'] = '<a class="btn btn-primary" href="'.base_url('tradeapplylicenceMobile/ApplyNewLicenseMobi/'.$id.'/'.md5($data['application_details']['id'])).'" role="button">View</a>';

                    }
                    else
                    {

                        $data['application_details']['view'] = "<span class='text-info'>License can't be surrendered. It expired on ".$data['application_details']['valid_upto']."</span>";
                    }
                }
                elseif($id == md5(3)) //amendment
                {

                    if($data['application_details']['application_type_id']==4)
                    {
                        $data['application_details']['view'] = '<a class="btn btn-primary" href="'.base_url('tradeapplylicenceMobile/ApplyNewLicenseMobi/'.$id.'/'.md5($data['application_details']['id'])).'" role="button">View</a>';

                    }
                    else
                    {
                        $data['application_details']['view'] = "<span class='text-info'>surrender the licensee first. licence valid upto ".$data['application_details']['valid_upto']."</span>";
                    }

                }
                elseif($id == md5(2)) //renewal
                {
                    $prev_month=date('Y-m-d',strtotime(date('Y-m-d')."+1 months"));                                
                    if(strtotime($data['application_details']['valid_upto'])<strtotime($prev_month))
                    {
                        $data['application_details']['view'] = '<a class="btn btn-primary" href="'.base_url('tradeapplylicenceMobile/ApplyNewLicenseMobi/'.$id.'/'.md5($data['application_details']['id'])).'" role="button">View</a>';

                    }
                    else
                    {

                        $data['application_details']['view'] = "<span class='text-info'>License can't be Renewed. It's valid upto  ".$data['application_details']['valid_upto']."</span>";

                    }

                }
                elseif($id == md5(1)) //new license
                {

                    if(strtotime($data['application_details']['valid_upto'])>strtotime(date('Y-m-d')))
                    {
                        $data['application_details']['view'] = '<a class="btn btn-primary" href="'.base_url('tradeapplylicenceMobile/ApplyNewLicenseMobi/'.md5($data['application_details']['application_type_id']).'/'.md5($data['application_details']['id'])).' role="button">View</a>';


                    }
                    else
                    {

                        $data['application_details']['view'] = "<span class='text-info'>This is a new license. It's valid upto  ".$data['application_details']['valid_upto']."</span>";

                    }
                }
            }    

        
        }
        return view('mobile/trade/SearchLicenseMobi', $data);
    }
    public function index($apptypeid = null)
    {
        $data = (array)null;
        $data=array();
        $data['id'] = $apptypeid;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['ward_list'] = $wardList;
        $ward=array();
        foreach($wardList as $key => $value)
        {
           $ward[]=$value['ward_mstr_id'];
        }
        if ($apptypeid <> null && in_array($apptypeid,[md5(4),md5(3),md5(2)])) 
        {
            $data["application_type"] = $this->tradeapplicationtypemstrmodel->getdatabymd5id($apptypeid);
            $data["msg"] = '';
            if(isset($_GET['btn_search']))
            { 
                $data['keyword']  = $_GET['keyword'];
            }
            if(isset($data['keyword']) && $data['keyword']!=null)
            {
                $data["Searchlicense"] = $data['keyword'];
                $nextMonth = date('Y-m-d', strtotime(date('Y-m-d') . ' + 1 Month'));
                
                $licensedata = $this->TradeApplyLicenceModel->getlicencedataMobi($data["Searchlicense"],$ward);
                $data['application_details']=$licensedata;
                if(!$licensedata)
                {
                    $data["msg"] = "License Not found.";
                }
                elseif($licensedata['pending_status'] != 5)
                {
                    $data['application_details']['view'] = "<span class='text-info'>Application Already Apply. Please Track App No. ".$licensedata['application_no']."</span>";
                }
                elseif($licensedata["application_type_id"]!= 4 && $data["application_type"]["id"] == 3)
                {
                    $data['application_details']['view'] =  "<span class='text-info'>Please apply for surrender before amendment."."</span>";
                }
                elseif($licensedata["valid_upto"] > $nextMonth && !in_array($data["application_type"]["id"],[4,3]))
                {
                    $data['application_details']['view'] =  "<span class='text-info'>License Not Expired! This Licence Is Valid Upto " . $licensedata["valid_upto"]."</span>";
                }
                elseif($licensedata["valid_upto"] < date('Y-m-d') && in_array($data["application_type"]["id"],[4,3]))
                {
                    $data['application_details']['view'] =  "<span class='text-info'>License No. $licensedata[license_no] is valid till $licensedata[valid_upto], which has expired. Therefore, please apply for renewal before surrender."."</span>";
                }
                else
                {
                    $id = md5($licensedata["id"]);
                    // return $this->response->redirect(base_url('TradeCitizen/applynewlicence/' . $apptypeid . '/' . $id));
                    $data['application_details']['view'] = '<a class="btn btn-primary" href="'.base_url('tradeapplylicenceMobile/ApplyNewLicenseMobi/'.$apptypeid.'/'.$id).'" role="button">View</a>';
                }
                
            }

            return view('mobile/trade/SearchLicenseMobi', $data);
        }
        return view('mobile/trade/SearchLicenseMobi', $data);
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

            // print_var($data['emp_details']);return;
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
