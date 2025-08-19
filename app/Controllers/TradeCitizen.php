<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\TradeViewApplyLicenceOwnerModel;
use App\Models\TradeApplyLicenceModel;
use App\Models\model_firm_owner_name;
use App\Models\model_application_doc;
use App\Models\model_trade_level_pending_dtl;
use App\Models\TradeFirmOwnerModel;
use App\Models\TradeTradeItemsModel;
use App\Models\TradeTransactionModel;
use App\Models\model_ulb_mstr;
use App\Models\TradeChequeDtlModel;
use App\Models\model_apply_licence;
use App\Models\statemodel;
use App\Models\tradefirmtypemstrmodel;
use App\Models\tradeapplicationtypemstrmodel;
use App\Models\tradeownershiptypemstrmodel;
use App\Models\tradeitemsmstrmodel;
use App\Models\tradelicenceratemodel;
use App\Models\model_saf_dtl;
use App\Models\model_prop_dtl;
use App\Models\model_trade_licence;
use App\Models\model_trade_licence_owner_name;
use App\Models\model_trade_view_licence_trade_items;
use App\Models\model_trade_licence_validity;
use App\Models\model_trade_document;
use App\Models\model_trade_transaction_fine_rebet_details;
use App\Models\districtmodel;
use App\Models\TradeCategoryTypeModel;
use App\Models\trade_view_application_doc_model;
use App\Models\model_view_trade_licence;
use App\Models\TradeApplyDenialModel;
use App\Models\model_trade_items_mstr;
use App\Models\model_prop_owner_detail;
use Exception;
use App\Models\Citizensw_trade_model;

class TradeCitizen extends HomeController
{
    protected $db;
    protected $dbSystem;
    protected $ulb_id;
    protected $emp_id;
    protected $TradeApplyLicenceModel;
    protected $model_application_doc;
    protected $model_firm_owner_name;
    protected $model_trade_level_pending_dtl;
    protected $TradeFirmOwnerModel;
    protected $TradeTradeItemsModel;
    protected $TradeTransactionModel;
    protected $model_ward_mstr;
    protected $model_ulb_mstr;
    protected $TradeChequeDtlModel;
    protected $statemodel;
    protected $tradefirmtypemstrmodel;
    protected $tradeapplicationtypemstrmodel;
    protected $tradeownershiptypemstrmodel;
    protected $tradeitemsmstrmodel;
    protected $tradelicenceratemodel;
    protected $model_saf_dtl;
    protected $model_prop_dtl;
    protected $model_trade_licence;
    protected $model_trade_licence_owner_name;
    protected $model_trade_view_licence_trade_items;
    protected $model_trade_licence_validity;
    protected $model_trade_document;
    protected $model_trade_transaction_fine_rebet_details;
    protected $districtmodel;
    protected $TradeCategoryTypeModel;
    protected $model_apply_licence;
    protected $trade_view_application_doc_model;
    protected $model_view_trade_licence;
    protected $TradeApplyDenialModel;
    protected $model_trade_items_mstr;
    protected $model_prop_owner_detail;
    protected $Citizensw_trade_model;


    public function __construct()
    {
        parent::__construct();
        helper(['db_helper', 'qr_code_generator_helper', 'form_helper', 'sms_helper']);
        $session = session();
        $ulb_details = $session->get('ulb_dtl');
        $ulb_details = getUlbDtl();
        $this->ulb_id = $ulb_details['ulb_mstr_id'];

        $emp_details = $session->get('emp_details');
        $this->emp_id = $emp_details['id'];


        if ($db_name = dbConfig("trade")) {
            //echo $db_name;
            $this->db = db_connect($db_name);
        }
        if ($db_system = dbSystem()) {
            $this->dbSystem = db_connect($db_system);
        }
        if ($db_name = dbConfig("property")) {
            $this->property_db = db_connect($db_name);
        }
        helper(['form']);
        //$db_name = db_connect("db_rmc_property"); 

        $this->ward_model = new model_ward_mstr($this->dbSystem);
        $this->TradeViewApplyLicenceOwnerModel = new TradeViewApplyLicenceOwnerModel($this->db);
        $this->TradeApplyLicenceModel = new TradeApplyLicenceModel($this->db);
        $this->model_trade_level_pending_dtl = new model_trade_level_pending_dtl($this->db);
        $this->TradeFirmOwnerModel = new TradeFirmOwnerModel($this->db);
        $this->TradeTradeItemsModel = new TradeTradeItemsModel($this->db);
        $this->TradeTransactionModel = new TradeTransactionModel($this->db);
        $this->model_apply_licence = new model_apply_licence($this->db);
        $this->modelUlb = new model_ulb_mstr($this->dbSystem);
        $this->TradeChequeDtlModel = new TradeChequeDtlModel($this->db);
        $this->model_firm_owner_name = new model_firm_owner_name($this->db);
        $this->statemodel = new statemodel($this->dbSystem);
        $this->tradefirmtypemstrmodel = new tradefirmtypemstrmodel($this->db);
        $this->tradeapplicationtypemstrmodel =  new tradeapplicationtypemstrmodel($this->db);
        $this->tradeownershiptypemstrmodel =  new tradeownershiptypemstrmodel($this->db);
        $this->tradeitemsmstrmodel =  new tradeitemsmstrmodel($this->db);
        $this->tradelicenceratemodel =  new tradelicenceratemodel($this->db);
        $this->model_saf_dtl = new model_saf_dtl($this->property_db);
        $this->model_prop_dtl = new model_prop_dtl($this->property_db);
        //$this->model_trade_licence = new model_trade_licence($this->db);
        $this->model_trade_licence_owner_name = new model_trade_licence_owner_name($this->db);
        $this->model_trade_view_licence_trade_items = new model_trade_view_licence_trade_items($this->db);
        $this->model_trade_licence_validity = new model_trade_licence_validity($this->db);
        $this->model_trade_document = new model_trade_document($this->db);
        $this->model_trade_transaction_fine_rebet_details = new model_trade_transaction_fine_rebet_details($this->db);
        $this->districtmodel = new districtmodel($this->db);
        $this->TradeCategoryTypeModel = new TradeCategoryTypeModel($this->db);
        $this->model_application_doc = new model_application_doc($this->db);
        $this->trade_view_application_doc_model = new trade_view_application_doc_model($this->db);
        $this->model_view_trade_licence = new model_view_trade_licence($this->db);
        $this->TradeApplyDenialModel = new TradeApplyDenialModel($this->db);
        $this->model_trade_items_mstr = new model_trade_items_mstr($this->db);
        $this->model_prop_owner_detail = new model_prop_owner_detail($this->db);
        $this->Citizensw_trade_model = new Citizensw_trade_model($this->db);
    }


    public function index()
    {
        try {
            $data = (array)null;
            $data['ulb_mstr_id'] = $this->ulb_id;
            $data['ward_list'] = $this->ward_model->getWardList($data);

            if ($this->request->getMethod() == 'post') {
                $inputs = filterSanitizeStringtoUpper($this->request->getVar());
                $data['ward_mstr_id'] = $inputs['ward_mstr_id'];
                $data["keyword"] = $inputs['keyword'];
                if ($data['ward_mstr_id'] == "" && $data["keyword"] == "") {
                    $data['error'] = '<span style="color:red;">Please Select Ward No. or Enter Keyword</span>';
                    return view('citizen/trade/SearchApplicant', $data);
                }
                if ($data['ward_mstr_id'] != "" && $data['keyword'] != "") {
                    $where = "ward_mstr_id = '" . $data['ward_mstr_id'] . "' and update_status=0 and (license_no = '" . $data['keyword'] . "' or firm_name = '" . $data['keyword'] . "' or application_no = '" . $data['keyword'] . "') AND status =1";
                } elseif ($data["ward_mstr_id"] != "") {
                    $where = "ward_mstr_id = '" . $data['ward_mstr_id'] . "' and update_status=0 AND status =1";
                } else {
                    $where = "update_status=0 and (license_no = '" . $data['keyword'] . "' or firm_name = '" . $data['keyword'] . "' or application_no = '" . $data['keyword'] . "') AND status =1";
                }

                $data['application_details'] = $this->TradeViewApplyLicenceOwnerModel->fetch_aaplication_citizendetail($where);
                $data['ward_list'] = $this->ward_model->getWardList($data);
                // print_var($data);exit;
                return view('citizen/trade/SearchApplicant', $data);
            } else {
                return view('citizen/trade/SearchApplicant', $data);
            }
        } catch (Exception $e) {
            //
        }
    }

    public function applicationStatus_md5($apply_licence_id)
    {
        $data['licencee'] = $this->TradeApplyLicenceModel->apply_licence_md5($apply_licence_id);
        //print_var($data["licencee"]);
        if ($data['licencee']["pending_status"] == 5) {
            $data["application_status"] = "Application Approved";
        } else if ($data['licencee']["pending_status"] == 2) {
            $data["level"] = $this->model_trade_level_pending_dtl->check_btcz_to_which_level($apply_licence_id);
            if ($data["level"]["receiver_user_type_id"] == 17) {
                $data["application_status"] = "Application sent to citizen by Dealing Assistant";
            } elseif ($data["level"]["receiver_user_type_id"] == 18) {
                $data["application_status"] = "Application sent by citizen by Section Head";
            } elseif ($data["level"]["receiver_user_type_id"] == 19) {
                $data["application_status"] = "Application sent by citizen by Executive Officer";
            } elseif ($data["level"]["receiver_user_type_id"] == 20) {
                $data["application_status"] = "Application sent by citizen by Tax Daroga";
            }
        } else {
            if (($data['licencee']["payment_status"] == 1) and ($data['licencee']["document_upload_status"] == 1)) {
                $data["level"] = $this->model_trade_level_pending_dtl->getusertypebyid_md5($apply_licence_id);
                if (isset($data["level"]["receiver_user_type_id"], $data["level"]["receiver_user_type_id"])) {

                    if ($data["level"]["receiver_user_type_id"] == 17) {
                        $data["application_status"] = "Application Pending At Dealing Assistant";
                    } elseif ($data["level"]["receiver_user_type_id"] == 18) {
                        $data["application_status"] = "Application Pending At Section Head";
                    } elseif ($data["level"]["receiver_user_type_id"] == 19) {
                        $data["application_status"] = "Application Pending At Executive Officer";
                    } elseif ($data["level"]["receiver_user_type_id"] == 20) {
                        $data["application_status"] = "Application Pending At Tax Daroga";
                    }
                }
            } elseif (($data['licencee']["payment_status"] == 2) and ($data['licencee']["document_upload_status"] == 0)) {
                $data["application_status"] = "Payment Done but not clear also document not uploaded";
            } elseif (($data['licencee']["payment_status"] == 1) and ($data['licencee']["document_upload_status"] == 0)) {
                $data["application_status"] = "Payment Done But Document not uploaded";
            } else {
                $data["application_status"] = "Payment Not Done also document not uploaded";
            }
        }
        return $data["application_status"] ?? NULL;
    }

    public function trade_licence_view($id)
    {
        $data = array();
        //$data['user_type']=$this->user_type;
        $data['licencee'] = $this->TradeApplyLicenceModel->apply_licence_md5($id);
        $data["ward_mstr_no"] = $data['licencee']['ward_mstr_id'];
        $data["holding_no"] = $data['licencee']["holding_no"];
        $warddet = $this->ward_model->getWardNoBywardId($data["ward_mstr_no"]);
        $data['ward_no'] = $warddet["ward_no"];
        $data["application_status"] = $this->applicationStatus_md5($id);

        $data["tradedetail"] = $this->tradeitemsmstrmodel->tradedetail($data["licencee"]['nature_of_bussiness']);
        $data['categoryDetails'] = $this->TradeCategoryTypeModel->categoryDetails($data["licencee"]['category_type_id']);
        $data["firmtype"] = $this->tradefirmtypemstrmodel->getdatabyid($data["licencee"]["firm_type_id"]);
        $data["ownershiptype"] = $this->tradeownershiptypemstrmodel->getdatabyid($data["licencee"]["ownership_type_id"]);
        $data['firm_owner'] = $this->TradeFirmOwnerModel->getdatabyid_md5($id);
        // $data['trade_items']=$this->TradeTradeItemsModel->getdatabyid_md5($id);
        $data['trans_detail'] = $this->TradeTransactionModel->alltransaction_details($id);

        $sql_notice = "select * from tbl_denial_notice where md5(apply_id::text) = '$id' and status = 2";
        $noticeDetails = $this->model_view_trade_licence->row_query($sql_notice, array())[0] ?? [];
        $data['notice_date'] = !empty($noticeDetails) ? $noticeDetails['created_on'] : null;
        $data['application_type']['id'] = $data['licencee']['application_type_id'];
        // print_var( $data['licencee']);die;
        return view('citizen/trade/trade_licence_details_view', $data);
    }


    public function provisional($id = null)
    {
        $data = (array)null;
        $Session = Session();
        date_default_timezone_set('Asia/Kolkata');
        $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id']??$this->ulb_id;
        $path = base_url('citizenPropertySAF/citizen_saf_payment_receipt/' . $ulb_mstr_id . '/' . $id);
        $data['ss'] = qrCodeGeneratorFun($path);
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $ulb_mstr_id = $data['ulb_mstr_id'];
        $emp_mstr = $Session->get("emp_details");

        $login_emp_details_id = $emp_mstr["id"];
        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];

        $data['ulb'] = $this->modelUlb->getulb_list($data['ulb_mstr_id']);
        $data['basic_details'] = $this->TradeViewApplyLicenceOwnerModel->getDatabyid($id);
        $lyear = 365 * $data['basic_details']['licence_for_years'];
        $data["valid_upto"] = date('Y-m-d', strtotime(date("Y-m-d", mktime()) . " + $lyear day"));

        $apply_licence_id = $data['basic_details']['id'];

        $data['item_details'] = $this->TradeTradeItemsModel->getdatabyid_md5($id);

        $data['ward']  = $this->ward_model->getdatabyid($data['basic_details']['ward_mstr_id']);

        return view('citizen/trade/provisional_licence', $data);
    }

    public function provisionalCertificate($id = null)
    {
        $data = (array)null;
        $Session = Session();
        date_default_timezone_set('Asia/Kolkata');
        $ulb_mstr = $Session->get("ulb_dtl");
        $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id']??$this->ulb_id;
        $path = base_url('citizenPaymentReceipt/view_trade_provisinal_receipt/' . $ulb_mstr_id . '/' . $id);
        $data['ss'] = qrCodeGeneratorFun($path);

        $emp_mstr = $Session->get("emp_details");
        $data['applicant_details'] = $this->TradeApplyLicenceModel->fetch_all_application_data($id);
        $login_emp_details_id = $emp_mstr["id"];
        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        $data['ulb'] = $this->modelUlb->getulb_list($ulb_mstr_id);
        $data['apply_id'] = $this->TradeApplyLicenceModel->getlicenceID($id);
        $data['basic_details'] = $this->TradeViewApplyLicenceOwnerModel->get_licenceDetails($data['apply_id']['id']);
        //print_r( $data['basic_details']);exit;
        $vUpto = $data['basic_details']['apply_date'];
        $data["valid_upto"] = date('Y-m-d', strtotime(date("$vUpto", time()) . " + 20 day"));
        $apply_licence_id = $data['basic_details']['id'];
        // $data['item_details'] = $this->TradeTradeItemsModel->getdatabyid_md5($id);
        $data['tranProvDtl'] = $this->TradeTransactionModel->tranProvDtl($id);
        return view('trade/Connection/provitionalLicence', $data);
    }
    public function municipalLicence($id = null)
    {

        $data = (array)null;
        $Session = Session();
        date_default_timezone_set('Asia/Kolkata');
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $ulb_mstr_id = $data['ulb_mstr_id']??$this->ulb_id;
        $emp_mstr = $Session->get("emp_details");
        
        $path = base_url('citizenPaymentReceipt/municipal_licence/' . $ulb_mstr_id . '/' . $id);
        $data['ss'] = qrCodeGeneratorFun($path);
        $login_emp_details_id = $emp_mstr["id"];
        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        $data['ulb'] = $this->modelUlb->getulb_list($data['ulb_mstr_id']);
        $data['basic_details'] = $this->model_view_trade_licence->getDatabyid($id);
        $apply_licence_id = $data['basic_details']['id'];
        
        $data['licence_dtl'] = $this->model_apply_licence->getDatabyid($apply_licence_id);
        $data['item_details'] = $this->model_trade_items_mstr->get_nature_business($data['licence_dtl']['nature_of_bussiness']);
        $data['prop_dtl'] = $this->model_prop_dtl->getPropdetailsbyid($data['licence_dtl']['prop_dtl_id']);
        $data['prop_dtl'] = json_decode(json_encode($data['prop_dtl']), true);
        // $data['prop_owner_dtl'] = $this->model_prop_owner_detail->propownerdetails($data['licence_dtl']['prop_dtl_id']);
        $data['ward']  = $this->ward_model->getdatabyid($data['basic_details']['ward_mstr_id']);
        $data['owner_details'] = $this->model_firm_owner_name->applicantdetails($apply_licence_id);
        // print_var($data['prop_dtl']);
        return view('trade/Connection/licenceView', $data);
    }

    //public function viewTransactionReceipt($applyid=null,$header=null)
    public function viewTransactionReceipt_COPY($applyid = null, $header = null)
    {
        $data = array();
        $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
        $data['transaction_id'] = $this->TradeTransactionModel->getTransaction_ID($applyid);
        $transaction_id = md5($data['transaction_id']['id']);
        $path = base_url('citizenPaymentReceipt/view_trade_transaction_receipt/' . $ulb_mstr_id . '/' . $applyid . '/' . $transaction_id);
        $data['ss'] = qrCodeGeneratorFun($path);
        $data['transaction_id'] = $transaction_id;
        $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
        $data['ulb_mstr_name'] = $this->modelUlb->getulb_list($ulb_mstr_id);
        $data['applicant_details'] = $this->TradeApplyLicenceModel->fetch_all_application_data($applyid);
        //print_r($data['applicant_details']);exit;      
        $data['transaction_details'] = $this->TradeTransactionModel->transaction_details($transaction_id);

        $data['rebet'] = $this->TradeTransactionModel->getRebetDetails($transaction_id);
        $data['delayApplyLicence'] = isset($data['rebet']['0']['amount']) ? $data['rebet']['0']['amount'] : 0;
        $data['denialApply'] = isset($data['rebet']['1']['amount']) ? $data['rebet']['1']['amount'] : 0;

        $warddet = $this->ward_model->getWardNoBywardId($data['transaction_details']['ward_mstr_id']);
        $data['ward_no'] = $warddet["ward_no"];
        $data['status'] = [1, 2];
        if (!in_array(strtoupper($data['transaction_details']['payment_mode']), ["ONLINE", "CASH"])) {
            $data['cheque_details'] = $this->TradeChequeDtlModel->alltransactioncheque_details($transaction_id);
        }
        $data["level_id"] = $this->model_trade_level_pending_dtl->get_level_id($applyid);
        $data['header'] = $header ? "layout_home/header" : null;
        //print_var( $data['rebet']);
        return view('trade/Connection/transactionReceipt', $data);
    }

    public function viewTransactionReceipt($transaction_id_md5 = null, $header = null)
    {
        $data = array();
        $_SESSION['ulb_dtl']=getUlbDtl();
        $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id']??$this->ulb_id;
        $data['transaction_id'] = $this->TradeTransactionModel->getTransaction_ID($transaction_id_md5);
        $transaction_id = md5($data['transaction_id']['id']);
        $applyid = md5($data['transaction_id']['related_id']);
        $path = base_url('citizenPaymentReceipt/view_trade_transaction_receipt/' . $ulb_mstr_id . '/' . $applyid . '/' . $transaction_id);
        $data['ss'] = qrCodeGeneratorFun($path);
        $data['transaction_id'] = $transaction_id;
        $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
        $data['ulb_mstr_name'] = $this->modelUlb->getulb_list($ulb_mstr_id);
        $data['applicant_details'] = $this->TradeApplyLicenceModel->fetch_all_application_data($applyid);
        //print_r($data['applicant_details']);exit;      
        $data['transaction_details'] = $this->TradeTransactionModel->transaction_details($transaction_id);

        $data['rebet'] = $this->TradeTransactionModel->getRebetDetails($transaction_id);
        // print_var($data['rebet']);
        $data['delayApplyLicence'] = isset($data['rebet']['0']['amount']) ? $data['rebet']['0']['amount'] : 0;
        $data['denialApply'] = isset($data['rebet']['1']['amount']) ? $data['rebet']['1']['amount'] : 0;

        // $warddet=$this->ward_model->getWardNoBywardId($data['transaction_details']['ward_mstr_id']);
        $warddet = $this->ward_model->getWardNoBywardId($data['applicant_details']['ward_mstr_id']);
        $data['ward_no'] = $warddet["ward_no"];
        $data['status'] = [1, 2];
        if (!in_array(strtoupper($data['transaction_details']['payment_mode']), ["ONLINE", "CASH"])) {
            $data['cheque_details'] = $this->TradeChequeDtlModel->alltransactioncheque_details($transaction_id);
        }
        $data["level_id"] = $this->model_trade_level_pending_dtl->get_level_id($applyid);
        $data['header'] = $header ? "layout_home/header" : null;
        // print_var( $data);
        return view('trade/Connection/transactionReceipt', $data);
    }

    public function applynewlicence($apptypeid = null, $id = null)
    {
        $data = array();
        $session = session();
        // print_var($session->get());
        $get_ulb_id = $session->get('ulb_dtl');

        $get_emp_details = $session->get('emp_details');
        $data['ulb_mstr_name'] = $this->modelUlb->getulb_list($get_ulb_id["ulb_mstr_id"]);


        $short_ulb_name = $data["ulb_mstr_name"]["short_ulb_name"];
        $emp_id = $get_emp_details['id'];
        // $emp_id=isset($get_emp_details['id']) and !empty($get_emp_details['id'])?$get_emp_details['id']:null;
        $ip_address = $get_emp_details['ip_address'];
        $data = (array)null;
        $data['curdate'] = date("Y-m-d");
        $data["apptypeid"] = $apptypeid;
        $data["id"] = $id;
        $data["statelist"] = $this->statemodel->getstateList();
        $data["idprooflist"] = $this->model_trade_document->getIdPoorfDocumentList();
        $data['ward_list'] = $this->ward_model->getWardList($get_ulb_id);
        $data["application_type"] = $this->tradeapplicationtypemstrmodel->getdatabymd5id($apptypeid);
        $data["firmtypelist"] = $this->tradefirmtypemstrmodel->getFirmTypeList();
        $data["ownershiptypelist"] = $this->tradeownershiptypemstrmodel->getownershipTypeList();


        // $data["tradeitemlist"] = $this->tradeitemsmstrmodel->gettradeitemsList();

        if ($apptypeid <> null and $id <> null) 
        {
            $data["application_type"] = $this->tradeapplicationtypemstrmodel->getdatabymd5id($apptypeid);
            $data["application_type_id"] = $data["application_type"]["id"];
            $data["licencedet"] = $this->TradeApplyLicenceModel->apply_licence_md5($id);
            $data["tobacco_status"] = $data["licencedet"]["tobacco_status"];
            if($data["licencedet"]['update_status']>0)
            {
                return $this->response->redirect(base_url("TradeCitizen/trade_licence_view/".md5($data["licencedet"]['update_status'])));
            }
            $data['ward_no'] = $this->ward_model->getWardNoById($data["licencedet"]);
            $data['new_ward_no'] = $this->ward_model->getWardNoBywardId($data["licencedet"]["new_ward_mstr_id"]);
            $data["ownerdet"] = $this->TradeFirmOwnerModel->getdatabyid_md5($id);
            //$data["tradeitemdet"] = $this->model_trade_view_licence_trade_items->get_details($id);

            $data["tradedetail"] = $this->tradeitemsmstrmodel->tradedetail($data["licencedet"]['nature_of_bussiness']);
            $data["tradeitemdet"] = explode(',', $data["licencedet"]["nature_of_bussiness"]);
            $data['categoryDetails'] = $this->TradeCategoryTypeModel->categoryDetails($data["licencedet"]['category_type_id']);

            $data["firmtype"] = $this->tradefirmtypemstrmodel->getdatabyid($data["licencedet"]["firm_type_id"]);

            $data["ownershiptype"] = $this->tradeownershiptypemstrmodel->getdatabyid($data["licencedet"]["ownership_type_id"]);
            $data["firm_type_id"] = $data["firmtype"]["id"];
            $data["firm_type"] = $data["firmtype"]["firm_type"];
            $data["ownership_type_id"] = $data["ownershiptype"]["id"];
            $data["ownership_type"] = $data["ownershiptype"]["ownership_type"];
            $data["holding_no"] = $data['licencedet']["holding_no"];
            $warddet = $this->ward_model->getWardNoBywardId($data['licencedet']['ward_mstr_id']);
            $data['ward_no'] = $warddet["ward_no"];
            $data['ward_id']=$data["licencedet"]['ward_mstr_id'];
            $data["firm_name"] = $data["licencedet"]["firm_name"];
            $data["area_in_sqft"] = (float)$data["licencedet"]["area_in_sqft"];
            $data["firm_date"] = $data["licencedet"]["establishment_date"];
            $data["address"] = $data["licencedet"]["address"];
            $data["landmark"] = $data["licencedet"]["landmark"];
            $data["pin_code"] = $data["licencedet"]["pin_code"];
            $data['apply_licence_id'] = $data["licencedet"]["id"];
        } 
        else 
        {
            $data["application_type"] = $this->tradeapplicationtypemstrmodel->getdatabymd5id($apptypeid);
        }

        if ($this->request->getMethod() == "post") 
        {

            $this->db->transBegin();
            $data['apply_from'] = 'ONL';
            $data['nature_of_bussiness'] = NULL;
            $data['tobacco_status'] = $this->request->getPost('tobacco_status') ?? null;


            if ($data["application_type"]["id"] == 1) 
            {
                $rules = [
                    'firmtype_id' => 'required',
                    'ownership_type_id' => 'required',
                    'old_ward_id' => 'required',
                    'new_ward_id' => 'required',
                    'area_in_sqft' => 'required',
                    'firm_date' => 'required',
                    'pin_code' => 'required|min_length[6]|max_length[6]',
                    'firm_name' => 'required',
                    'firmaddress' => 'required',
                    'licence_for' => 'required',
                    'brife_desp_firm' => 'required',

                ];
            } 
            elseif ($data["application_type"]["id"] == 3)
            {
                $rules = [
                    'area_in_sqft' => 'required',

                ];
            } 
            elseif ($data["application_type"]["id"] == 2) 
            {
                $rules = [
                    'licence_for' => 'required',
                    'holding_no'=>'required',

                ];
            } 
            else 
            {
                $rules = [
                    'area_in_sqft' => 'required',
                ];
            }
            /*
                if(!$this->validate($rules))
                {
                    
                    $data['validation']=$this->validator;  
                    return view('citizen/trade/applylicence', $data);
                }
                else
                {  
                    $inputs = filterSanitizeStringtoUpper($this->request->getVar());                    
                    $data["tobacco_status"]=$inputs['tobacco_status'];  
                    $data["application_type_id"]=$data["application_type"]["id"];
                    $data["licence_for_years"]=$inputs['licence_for'];
                    $data["otherfirmtype"]=$inputs['firmtype_other'];
                    $data["category_type_id"]=$inputs['category_type_id'];
                    $penalty=0;
                    $data["prop_dtl_id"]=0;
                    $denialAmount=0;
                    //denialAmount calculation 
                    if($data["application_type"]["id"]!=4)
                    {
                        $denialId = $this->request->getVar('dnialID');  
                        if($denialId && $data["application_type"]["id"]==1)
                        {
                            $noticeDetails=$this->TradeApplyDenialModel->getDenialDate($denialId);                    
                            $now = strtotime(date('Y-m-d H:i:s')); // todays date
                            $notice_date = strtotime($noticeDetails['created_on']); //notice date                            
                            $datediff = $now - $notice_date; //days difference in second
                            $totalDays =   ceil($datediff / (60 * 60 * 24)); // total no. of days                            
                            $denialAmount=100+(($totalDays)*10);
                        }
                    }
                    //end
                    


                    if($data["application_type"]["id"]==1)
                    {
                        $data["firm_type_id"]=$inputs['firmtype_id'];                        
                        $data["ownership_type_id"]=$inputs['ownership_type_id'];
                        $data["ward_mstr_id"]=$inputs['old_ward_id'];
                        $data["new_ward_mstr_id"]=$inputs['new_ward_id'];
                        if($inputs['prop_id']<>'')
                        {           
                            $data["prop_dtl_id"]=$inputs['prop_id'];
                        }
                        $data["holding_no"]=$inputs['holding_no'];
                        $data["property_type"]='PROPERTY';                                              
                        $data["firm_name"]=$inputs['firm_name'];
                        $data["area_in_sqft"]=(float)$inputs['area_in_sqft'];                           
                        $data["firm_date"]=$inputs['firm_date'];
                        $data["address"]=$inputs['firmaddress'];
                        $data["landmark"]=$inputs['landmark'];
                        $data["pin_code"]=$inputs['pin_code'];
                        $data["owner_business_premises"]=$inputs['owner_business_premises'];

                        $ratedet = $this->tradelicenceratemodel->getrate($data);
                        
                        $charge = $ratedet["rate"]*$data["licence_for_years"];
                        
                        $date1 = $data["firm_date"];
                        $date2 = date('Y-m-d');

                        $vDiff = abs(strtotime($date2) - strtotime($date1)); // here abs in case theres a mix in the dates
                        $vMonths = ceil($vDiff / (30*60*60*24)); // number of seconds in a month of 30 days
                        if($vMonths>0)
                        {
                            $penalty=100+(($vMonths)*20);
                        }
                
                        $total_charge=$penalty+$charge;
                        
                        //if denial found
                        if($denialAmount!=0)
                        {
                            $total_charge = $total_charge +  $denialAmount;
                        }
                        //print_var($inputs);print_var($data['nature_of_bussiness']);die;
                        if($data['tobacco_status']==1)
                        {
                            $data['nature_of_bussiness'] = $data['nature_of_bussiness'];
                        }
                        else
                        {
                            $data['tade_item'] = $inputs['tade_item'];
                            $data['nature_of_bussiness'] = implode(",", $data['tade_item']);
                        }
                            
                    }
                    else
                    {
                        if($data["application_type"]["id"]==2)
                        {
                            $date1 = $inputs['firm_date'];
                            $date2 = date('Y-m-d');
                            $vDiff = abs(strtotime($date2) - strtotime($date1)); // here abs in case theres a mix in the dates
                            $vMonths = ceil($vDiff / (30*60*60*24)); // number of seconds in a month of 30 days
                            if($vMonths>0)
                            {
                                $penalty=100+(($vMonths)*20);   
                            }
                        }


                        $data["firm_type_id"]=$data["licencedet"]["firm_type_id"];                        
                        $data["ownership_type_id"]=$data["licencedet"]["ownership_type_id"];
                        $data["ward_mstr_id"]=$data["licencedet"]["ward_mstr_id"];
                        $data["new_ward_mstr_id"]=$data["licencedet"]["new_ward_mstr_id"];  
                        $data["owner_business_premises"]=$data["licencedet"]["premises_owner_name"];
                        if($data["licencedet"]["prop_dtl_id"]<>'')
                        {
                            $data["prop_dtl_id"]=$data["licencedet"]["prop_dtl_id"];
                        }
                        $data["property_type"]=$data["licencedet"]["property_type"];  
                        if($data["application_type"]["id"]==3)
                        {
                            $data["area_in_sqft"]=(float)$inputs['area_in_sqft']; 
                                $data["licence_for_years"]=1;
                        }
                        elseif($data["application_type"]["id"]==4)
                        {
                            $data["licence_for_years"]=0;
                            $data["area_in_sqft"]=(float)$data["licencedet"]["area_in_sqft"];
                        }
                        else
                        {
                            $data["area_in_sqft"]=(float)$data["licencedet"]["area_in_sqft"];
                        }
                        if($data["tobacco_status"]=="")
                        {
                            $data["tobacco_status"]=0;
                        }
                        $data["licence_id"]=$data["licencedet"]["id"];
                        $ratedet = $this->tradelicenceratemodel->getrate($data);
                        
                        $total_charge = $ratedet["rate"]*$data["licence_for_years"];

                        $total_charge=$total_charge+$penalty;
                        
                        if($data['tobacco_status']==1)
                        {
                            $data['nature_of_bussiness'] = $data['nature_of_bussiness'];
                        }
                        elseif($data["application_type"]["id"]==2 || $data["application_type"]["id"]==3 || $data["application_type"]["id"]==4)
                        {
                            $data['tade_item'] = $inputs['tade_item'];
                            $data['nature_of_bussiness'] = $data['tade_item'];
                        }
                        else
                        {
                            $data['tade_item'] = $inputs['tade_item'];
                            $data['nature_of_bussiness'] = implode(",", $data['tade_item']);
                        }   
                    }

                    $data["rate_id"] = $ratedet["id"];
                    $data['emp_details_id']='0';                                    
                    $data['created_on']=date('Y-m-d H:i:s');
                    $data['apply_from']='Online';
                    if($data["application_type"]["id"]==1)
                    {
                        $applyid = $this->TradeApplyLicenceModel->insertapply($data);
                    }
                    else
                    {
                        # Incase of surrender there are not payment
                        if($data["application_type_id"]==4)
                        $data['payment_status']=1;
                        
                        $applyid = $this->TradeApplyLicenceModel->insertrenewdata($data);
                        //print_r($applyid);
                        $this->TradeApplyLicenceModel->update_status_id($applyid,$data["licence_id"]);
                        //die();
                    }
                    
                    if($applyid)
                    {
                        $owner_arr=array();
                        $owner_arr['apply_licence_id']=$applyid;
                        $owner_arr['licence_id']=$data["licencedet"]["id"] ?? NULL;
                        $owner_arr['emp_details_id']='0';                                        
                        $owner_arr['created_on']=date('Y-m-d H:i:s');
                        
                        if($data["application_type"]["id"]==1)
                        {
                            if(isset($inputs['owner_name']))
                            {
                                for($i=0;$i<sizeof($inputs['owner_name']);$i++)
                                {
                                    $owner_arr['owner_name']=$inputs['owner_name'][$i];
                                    $owner_arr['guardian_name']=$inputs['guardian_name'][$i];
                                    $owner_arr['emailid']=$inputs['emailid'][$i];                                           
                                    $owner_arr['mobile']=$inputs['mobile_no'][$i];
                                    $this->TradeFirmOwnerModel->insertdata($owner_arr);
                                }
                            }
                        }
                        else
                        {
                            if(isset($data["ownerdet"]))
                            {
                                if(!empty($data["ownerdet"]))
                                {
                                    $this->TradeFirmOwnerModel->insertrenewdata($owner_arr);
                                }
                            }

                        }
                        if($data["application_type"]["id"]<>4)
                        {
                            $warddet=$this->ward_model->getWardNoBywardId($data["ward_mstr_id"]);
                            $ward_no=$warddet["ward_no"];

                            $data['ward_count']=$this->TradeApplyLicenceModel->count_ward_by_wardid($data["ward_mstr_id"]);
                            //print_r($data['ward_count']['ward_cnt']);
                            $sl_no = $data['ward_count']['ward_cnt'];
                            $sl_noo = $sl_no+1;
                            $serial_no = str_pad($sl_noo, 4, "0", STR_PAD_LEFT);
                            
                            $prov_no=$short_ulb_name.'P'.$ward_no.$serial_no;
                            $this->TradeApplyLicenceModel->update_prov_no($applyid, $prov_no);
                            $payment_status=0;
                        }
                        else
                        {
                            //  there is no payment incase of surrender
                            $payment_status=1;
                        }

                        
                        $app_no="APPLIC".$applyid.date('dmyhis');
                        $this->TradeApplyLicenceModel->update_application_no($app_no, $applyid, $payment_status);
                    
                        if($this->db->transStatus() === FALSE)
                        {
                            $this->db->transRollback();
                            flashToast("applylicence", "Something errordue to payment!!!");
                            if($data["application_type_id"]==1)
                            {
                            
                                return $this->response->redirect(base_url('TradeCitizen/applynewlicence/'.$apptypeid));
                            }
                            else
                            {
                                
                                return $this->response->redirect(base_url('TradeCitizen/applynewlicence/'.$apptypeid.'/'.$id));
                            }
                        }
                        else
                        {
                            $this->db->transCommit();
                            if($data["application_type"]["id"]<>4)
                            {
                                return $this->response->redirect(base_url('TradePaymentCitizen/handleRazorPayRequest/'.md5($applyid)));
                                
                            }
                            elseif($data["application_type"]["id"]==4)
                            {
                                return $this->response->redirect(base_url('TradeCitizen/doc_upload/'.md5($applyid).'/'.md5($transaction_id)));
                            }
                            else
                            {
                                return $this->response->redirect(base_url('TradeCitizen/trade_licence_view/'.md5($applyid)));
                            }
                        }
                    } 
                }
            */

            if ($data["application_type"]["id"] == 1) 
            { //print_var($_POST);die;
                /*
                $rules=[
                            'firmtype_id'=>'required',
                            'ownership_type_id' =>'required',                    
                            'old_ward_id' =>'required',  
                            'new_ward_id' =>'required',                               
                            'area_in_sqft' =>'required',
                            'firm_date' => 'required',                                                 
                            'pin_code' =>'required|min_length[6]|max_length[6]',                     
                            'firm_name' =>'required',                                
                            'firmaddress' => 'required',
                            'licence_for' =>'required', 
                            'payment_mode' => 'required',
                            'apply_from' => 'required',
                        ];
                */
                if (!$this->validate($rules)) 
                {
                    $data['validation'] = $this->validator;
                    return view('citizen/trade/applylicence', $data);
                }
                $inputs = filterSanitizeStringtoUpper($this->request->getVar());



                $data['nature_of_bussiness'] = implode(',', $inputs['tade_item']) ?? null;

                $inputs['payment_mode'] = 'Online';
                $data["application_type_id"] = $data["application_type"]["id"];
                $data["firm_type_id"] = $inputs['firmtype_id'];
                $data["otherfirmtype"] = $inputs['firmtype_other'];
                $data['category_type_id'] = $inputs['category_type_id'];
                $data["ownership_type_id"] = $inputs['ownership_type_id'];
                $data["ward_mstr_id"] = $inputs['old_ward_id'];
                $data["new_ward_mstr_id"] = $inputs['new_ward_id'];
                $data["brife_desp_firm"] = $inputs['brife_desp_firm'];

                if ($inputs['prop_id'] <> '') {
                    $data["prop_dtl_id"] = $inputs['prop_id'];
                }
                $data["holding_no"] = $inputs['holding_no'];
                $data["property_type"] = 'PROPERTY';
                $data["firm_name"] = $inputs['firm_name'];
                $data["area_in_sqft"] = (float)$inputs['area_in_sqft'];
                $data["firm_date"] = $inputs['firm_date'];
                $data["address"] = $inputs['firmaddress'];
                $data["landmark"] = $inputs['landmark'];
                $data["pin_code"] = $inputs['pin_code'];
                $data["owner_business_premises"] = $inputs['owner_business_premises'];
                $data["licence_for_years"] = $inputs['licence_for'];
                $data["tobacco_status"] = $inputs['tobacco_status'];

                # Calculating rate
                {
                    $args['areasqft'] = (float)$inputs['area_in_sqft'];
                    $args['applytypeid'] = $data["application_type"]["id"];
                    $args['estdate'] = $inputs["firm_date"];
                    $args['tobacco_status'] = $inputs['tobacco_status'];
                    $args['licensefor'] = $inputs["licence_for"];
                    $args['nature_of_business'] = $data['nature_of_bussiness'];

                    $rate_data = $this->getcharge($args);
                    $rate_data = json_decode(json_encode(json_decode($rate_data)), true);
                }

                // print_var($args);die();

                $data["rate_id"] = $rate_data["rate_id"];
                $data['emp_details_id'] = $emp_id;
                $data['created_on'] = date('Y-m-d H:i:s');
                $applyid = $this->TradeApplyLicenceModel->insertapply($data);

                $owner_arr = array();
                $owner_arr['apply_licence_id'] = $applyid;
                //$owner_arr['licence_id']=$data["licencedet"]["id"];
                $owner_arr['emp_details_id'] = $emp_id;
                $owner_arr['created_on'] = date('Y-m-d H:i:s');


                if (isset($inputs['owner_name'])) {
                    for ($i = 0; $i < sizeof($inputs['owner_name']); $i++) {
                        $owner_arr['owner_name'] = $inputs['owner_name'][$i];
                        $owner_arr['guardian_name'] = $inputs['guardian_name'][$i];
                        $owner_arr['emailid'] = $inputs['emailid'][$i];
                        $owner_arr['mobile'] = $inputs['mobile_no'][$i];
                        $this->TradeFirmOwnerModel->insertdata($owner_arr);
                    }
                }


                //denialAmount calculation
                $denialAmount = 0;
                $denialId = $this->request->getVar('dnialID');
                $noticeDetails = array();
                if ($denialId)
                    $noticeDetails = $this->TradeApplyDenialModel->getDenialDate($denialId);
                if ($noticeDetails) {
                    $now = strtotime(date('Y-m-d H:i:s')); // todays date
                    $notice_date = strtotime($noticeDetails['created_on']); //notice date
                    $datediff = $now - $notice_date; //days difference in second
                    $totalDays =   ceil($datediff / (60 * 60 * 24)); // total no. of days
                    $denialAmount = 100 + (($totalDays) * 10);
                    $this->TradeApplyDenialModel->updateStatusFine($denialId, $denialAmount, $applyid); //update status and fineAmount
                    // in tbl_denial_notice by $denialId

                }

                //end
                $totalCharge = $rate_data['total_charge'] + $denialAmount;

                $transact_arr = array();
                $transact_arr['related_id'] = $applyid;
                $transact_arr['ward_mstr_id'] = $data["ward_mstr_id"];
                $transact_arr['transaction_type'] = $data["application_type"]["application_type"];
                $transact_arr['transaction_date'] = date('Y-m-d');
                $transact_arr['payment_mode'] = 'Online';
                $transact_arr['paid_amount'] = $totalCharge;

                $transact_arr['penalty'] = $rate_data['penalty'] + $denialAmount;
                $transact_arr['status'] = 1;
                if ($inputs['payment_mode'] != 'CASH') {
                    $transact_arr['status'] = 2;
                }
                $transact_arr['emp_details_id'] = $emp_id;
                $transact_arr['created_on'] = date('Y-m-d H:i:s');
                $transact_arr['ip_address'] = '';
                $transaction_id = 0; // $this->TradeTransactionModel->insertPayment($transact_arr); 

                $trafinerebate = array();  // penalty insert
                $trafinerebate['transaction_id'] = $transaction_id;
                $trafinerebate['head_name'] = 'Delay Apply License';
                $trafinerebate['amount'] = $rate_data['penalty'];
                $trafinerebate['value_add_minus'] = 'Add';
                $trafinerebate['created_on'] = date('Y-m-d H:i:s');
                //$this->model_trade_transaction_fine_rebet_details->fine_rebet_details($trafinerebate);

                if ($denialAmount > 0) {
                    $denial = array();  // denial insert
                    $denial['transaction_id'] = $transaction_id;
                    $denial['head_name'] = 'Denial Apply  ';
                    $denial['amount'] = $denialAmount;
                    $denial['value_add_minus'] = 'Add';
                    $denial['created_on'] = date('Y-m-d H:i:s');
                    //$this->model_trade_transaction_fine_rebet_details->fine_rebet_details($denial);
                }


                $payment_status = 0;
                if ($inputs['payment_mode'] != 'CASH' && $inputs['payment_mode'] != 'Online') {
                    $chq_arr = array();
                    $chq_arr['transaction_id'] = $transaction_id;
                    $chq_arr['cheque_no'] = $inputs['chq_no'];
                    $chq_arr['cheque_date'] = $inputs['chq_date'];
                    $chq_arr['bank_name'] = $inputs['bank_name'];
                    $chq_arr['branch_name'] = $inputs['branch_name'];
                    $chq_arr['emp_details_id'] = $emp_id;
                    $chq_arr['created_on'] = date('Y-m-d H:i:s');
                    $payment_status = 2;
                    //$this->TradeChequeDtlModel->insertdata($chq_arr);
                }
                //print_var($data["ward_mstr_id"]);die;
                $warddet = $this->ward_model->getWardNoBywardId($data["ward_mstr_id"]);
                $ward_no = $warddet["ward_no"];

                # Update Provisional No
                $prov_no = $short_ulb_name . $ward_no . date('mdy') . $applyid;
                $this->TradeApplyLicenceModel->update_prov_no($applyid, $prov_no);

                # Update Application No
                // $app_no = "APPLIC" . $applyid . date('dmyhis');
                $app_no = "APN".str_pad(substr($ward_no,0,2),2,"0",STR_PAD_LEFT).STR_PAD($applyid,7,"0",STR_PAD_LEFT);
                $this->TradeApplyLicenceModel->update_application_no($app_no, $applyid, $payment_status);

                /*if($this->db->transStatus() === FALSE)
                        {
                            $this->db->transRollback();
                            flashToast("applylicence", "Something errordue to payment!!!");
                            if($data["application_type_id"]==1)
                            {
                                return $this->response->redirect(base_url('tradeapplylicence/applynewlicence/'.$apptypeid));
                            }
                            else
                            {
                                return $this->response->redirect(base_url('tradeapplylicence/applynewlicence/'.$apptypeid.'/'.$id));
                            }
                        }
                        else
                        {
                            $this->db->transCommit();
                            if($data["application_type"]["id"]<>4)
                            {
                                return $this->response->redirect(base_url('tradeapplylicence/view_transaction_receipt/'.md5($applyid).'/'.md5($transaction_id)));
                            }
                            else
                            {
                                return $this->response->redirect(base_url('tradeapplylicence/trade_licence_view/'.md5($applyid)));
                            }
                        }*/
            }

            # Renewal
            elseif ($data["application_type"]["id"] == 2) 
            {
                /*$rules=[
                            'licence_for' =>'required',                         
                            'payment_mode' => 'required',
                    ];
                */
                if (!$this->validate($rules)) {
                    $data['validation'] = $this->validator;
                    return view('citizen/trade/applylicence', $data);
                }

                $inputs = filterSanitizeStringtoUpper($this->request->getVar());

                $inputs['payment_mode'] = 'Online';
                $data["otherfirmtype"] = $inputs['firmtype_other'];
                $data["licence_for_years"] = $inputs["licence_for"];
                // $data["valid_from"] = $inputs["firm_date"];
                $data["valid_from"] = $data["licencedet"]['valid_upto'];
                $data["application_type_id"] = $data["application_type"]["id"];
                $data["prop_dtl_id"] = $data["licencedet"]["prop_dtl_id"];
                $data["firm_type_id"] = $data["licencedet"]["firm_type_id"];
                $data["ownership_type_id"] = $data["licencedet"]["ownership_type_id"];
                $data["ward_mstr_id"] = $data["licencedet"]["ward_mstr_id"];
                $data["new_ward_mstr_id"] = $data["licencedet"]["new_ward_mstr_id"];
                $data["area_in_sqft"] = $data["licencedet"]["area_in_sqft"];
                $data["owner_business_premises"] = $data["licencedet"]["premises_owner_name"];
                $data["tobacco_status"] = $data['licencedet']['tobacco_status'];
                $data["prop_dtl_id"] = $data["licencedet"]["prop_dtl_id"];
                $data["property_type"] = $data["licencedet"]["property_type"];
                $data["licence_id"] = $data["licencedet"]["id"];
                $data["nature_of_bussiness"] = $data["licencedet"]["nature_of_bussiness"];
                $data["brife_desp_firm"] = $data["licencedet"]['brife_desp_firm'];
                $data["firm_date"] = $data["licencedet"]['establishment_date'];
                // print_var($inputs);
                $test_deta = $this->re_day_diff($inputs['firm_date'], $inputs['licence_for']);
                // die;
                // if($test_deta['diff_day']<0)
                // {
                //     $day = $test_deta['diff_day']*-1;                       
                //     flashToast("applylicence", "Please select more $day Days!!!");
                //     return $this->response->redirect(base_url('TradeCitizen/applynewlicence/'.$apptypeid.'/'.$id));
                // }
                # Calculating Rate
                {
                    $args['areasqft'] = $data["licencedet"]["area_in_sqft"];
                    $args['applytypeid'] = $data["application_type"]["id"];
                    $args['estdate'] = $inputs["firm_date"];
                    $args['tobacco_status'] = $data["licencedet"]["tobacco_status"];
                    $args['licensefor'] = $inputs["licence_for"];
                    $args['nature_of_business'] = $data['nature_of_bussiness'];
                    $rate_data = $this->getcharge($args);
                    $rate_data = json_decode(json_encode(json_decode($rate_data)), true);
                }

                $data["rate_id"] = $rate_data["rate_id"];
                $data['emp_details_id'] = $emp_id;
                $data['created_on'] = date('Y-m-d H:i:s');

                $data["prop_dtl_id"] = isset($inputs['prop_id'])&&!empty($inputs['prop_id'])?$inputs['prop_id']:$data["licencedet"]["prop_dtl_id"];
                $data["holding_no"] = isset($inputs['holding_no'])&&!empty($inputs['holding_no'])?$inputs['holding_no']:$data["licencedet"]["holding_no"];

                $data["provisional_license_no"] = $data["licencedet"]["provisional_license_no"];
                $data["license_no"] = $data["licencedet"]["license_no"];
                $priv_m_d = date('m-d', strtotime($data["valid_from"]));
                $date = date('Y') . '-' . $priv_m_d;
                $vali_upto = date('Y-m-d', strtotime($date . "+" . $data["licence_for_years"] . " years"));
                // $data['valid_upto'] = $vali_upto;

                $applyid = $this->TradeApplyLicenceModel->insertapply($data);
                $this->TradeApplyLicenceModel->update_status_id($applyid, $data["licencedet"]["id"]);

                $owner_arr = array();
                $owner_arr['apply_licence_id'] = $applyid;
                $owner_arr['licence_id'] = $data["licencedet"]["id"];
                $owner_arr['emp_details_id'] = $emp_id;
                $owner_arr['created_on'] = date('Y-m-d H:i:s');
                if (isset($data["ownerdet"], $data["ownerdet"])) {
                    $this->TradeFirmOwnerModel->insertrenewdata($owner_arr);
                }

                /*----transaction------*/
                $transact_arr = array();
                $transact_arr['related_id'] = $applyid;
                $transact_arr['ward_mstr_id'] = $data["ward_mstr_id"];
                $transact_arr['transaction_type'] = $data["application_type"]["application_type"];
                $transact_arr['transaction_date'] = date('Y-m-d');
                $transact_arr['payment_mode'] = $inputs['payment_mode'];
                $transact_arr['paid_amount'] = $rate_data['total_charge'];

                $transact_arr['penalty'] = $rate_data['penalty'];
                $transact_arr['status'] = 1;
                if ($inputs['payment_mode'] != 'CASH' && $inputs['payment_mode'] != 'Online') {
                    $transact_arr['status'] = 2;
                }
                $transact_arr['emp_details_id'] = $emp_id;
                $transact_arr['created_on'] = date('Y-m-d H:i:s');
                $transact_arr['ip_address'] = '';

                $transaction_id = 0; //$this->TradeTransactionModel->insertPayment($transact_arr); 
                //echo $transaction_id;
                $trafinerebate = array();
                $trafinerebate['transaction_id'] = $transaction_id;
                $trafinerebate['head_name'] = 'Delay Apply License';
                $trafinerebate['amount'] = $rate_data['penalty'];
                $trafinerebate['value_add_minus'] = 'Add';
                $trafinerebate['created_on'] = date('Y-m-d H:i:s');
                //$this->model_trade_transaction_fine_rebet_details->fine_rebet_details($trafinerebate);


                $payment_status = 0;
                if ($inputs['payment_mode'] != 'CASH' && $inputs['payment_mode'] != 'Online') {
                    $chq_arr = array();
                    $chq_arr['transaction_id'] = $transaction_id;
                    $chq_arr['cheque_no'] = $inputs['chq_no'];
                    $chq_arr['cheque_date'] = $inputs['chq_date'];
                    $chq_arr['bank_name'] = $inputs['bank_name'];
                    $chq_arr['branch_name'] = $inputs['branch_name'];
                    $chq_arr['emp_details_id'] = $emp_id;
                    $chq_arr['created_on'] = date('Y-m-d H:i:s');
                    $payment_status = 2;
                    //$this->TradeChequeDtlModel->insertdata($chq_arr);
                }

                # Update Application No
                // $app_no = "APPLIC" . $applyid . date('dmyhis');
                $warddet = $this->ward_model->getWardNoBywardId($data["ward_mstr_id"]);
                $ward_no = $warddet["ward_no"];

                # Update Application No
                // $app_no = "APPLIC" . $applyid . date('dmyhis');
                $app_no = "APN".str_pad(substr($ward_no,0,2),2,"0",STR_PAD_LEFT).STR_PAD($applyid,7,"0",STR_PAD_LEFT);
                $this->TradeApplyLicenceModel->update_application_no($app_no, $applyid, $payment_status);


                /*if($this->db->transStatus() === FALSE)
                    {
                        $this->db->transRollback();
                        flashToast("applylicence", "Something errordue to payment!!!");
                        if($data["application_type_id"]==1)
                        {
                            return $this->response->redirect(base_url('tradeapplylicence/applynewlicence/'.$apptypeid));
                        }
                        else
                        {
                            return $this->response->redirect(base_url('tradeapplylicence/applynewlicence/'.$apptypeid.'/'.$id));
                        }
                    }
                    else
                    {
                        $this->db->transCommit();
                        if($data["application_type"]["id"]<>4)
                        {
                            return $this->response->redirect(base_url('tradeapplylicence/view_transaction_receipt/'.md5($applyid).'/'.md5($transaction_id)));
                        }
                        else
                        {
                            return $this->response->redirect(base_url('tradeapplylicence/trade_licence_view/'.md5($applyid)));
                        }
                    }*/
            }

            # 3 Amendment
            elseif ($data["application_type"]["id"] == 3) 
            {
                /*$rules=[
                            'area_in_sqft' =>'required', 
                            'payment_mode' => 'required',
                        ];*/
                if (!$this->validate($rules)) {
                    $data['validation'] = $this->validator;
                    return view('citizen/trade/applylicence', $data);
                }

                $inputs = filterSanitizeStringtoUpper($this->request->getVar());
                $inputs['payment_mode'] = 'Online';
                $data["area_in_sqft"] = $inputs["area_in_sqft"];
                $data["ownership_type_id"] = $inputs["ownership_type_id"];
                $data["otherfirmtype"] = $inputs['firmtype_other'];
                $data["licence_for_years"] = $inputs["licence_for"];

                $data["valid_from"] = date('Y-m-d');
                $data["application_type_id"] = $data["application_type"]["id"];
                $data["prop_dtl_id"] = $data["licencedet"]["prop_dtl_id"];
                $data["firm_type_id"] = $data["licencedet"]["firm_type_id"];
                $data["ownership_type_id"] = $data["licencedet"]["ownership_type_id"];
                $data["ward_mstr_id"] = $data["licencedet"]["ward_mstr_id"];
                $data["new_ward_mstr_id"] = $data["licencedet"]["new_ward_mstr_id"];

                $data["owner_business_premises"] = $data["licencedet"]["premises_owner_name"];
                $data["tobacco_status"] = $data['licencedet']['tobacco_status'];
                $data["prop_dtl_id"] = $data["licencedet"]["prop_dtl_id"];
                $data["property_type"] = $data["licencedet"]["property_type"];
                $data["licence_id"] = $data["licencedet"]["id"];
                $data["nature_of_bussiness"] = $data["licencedet"]["nature_of_bussiness"];
                $data["license_no"] = $data["licencedet"]["license_no"];
                $data["brife_desp_firm"] = $data["licencedet"]['brife_desp_firm'];
                $data["firm_date"] = $data["licencedet"]['establishment_date'];
                # Calculating Rate
                {
                    $args['areasqft'] = $inputs["area_in_sqft"];
                    $args['applytypeid'] = 3;
                    $args['estdate'] = date('Y-m-d');
                    $args['tobacco_status'] = $data["licencedet"]["tobacco_status"];
                    $args['licensefor'] = $inputs["licence_for"];
                    $args['nature_of_business'] = $data['nature_of_bussiness'];
                    $rate_data = $this->getcharge($args);
                    $rate_data = json_decode(json_encode(json_decode($rate_data)), true);
                }

                $data["rate_id"] = $rate_data["rate_id"];
                $data['emp_details_id'] = $emp_id;
                $data['created_on'] = date('Y-m-d H:i:s');

                $applyid = $this->TradeApplyLicenceModel->insertapply($data);
                $this->TradeApplyLicenceModel->update_status_id($applyid, $data["licencedet"]["id"]);

                $warddet = $this->ward_model->getWardNoBywardId($data["ward_mstr_id"]);
                $ward_no = $warddet["ward_no"];

                # Update Provisional No
                $prov_no = $short_ulb_name . $ward_no . date('mdy') . $applyid;
                $this->TradeApplyLicenceModel->update_prov_no($applyid, $prov_no);

                $owner_arr = array();
                $owner_arr['apply_licence_id'] = $applyid;
                $owner_arr['licence_id'] = $data["licencedet"]["id"];
                $owner_arr['emp_details_id'] = $emp_id;
                $owner_arr['created_on'] = date('Y-m-d H:i:s');

                if (isset($inputs['owner_name'])) {
                    for ($i = 0; $i < sizeof($inputs['owner_name']); $i++) {
                        $owner_arr['owner_name'] = $inputs['owner_name'][$i];
                        $owner_arr['guardian_name'] = $inputs['guardian_name'][$i];
                        $owner_arr['emailid'] = $inputs['emailid'][$i];
                        $owner_arr['mobile'] = $inputs['mobile_no'][$i];
                        $this->TradeFirmOwnerModel->insertdata($owner_arr);
                    }
                }


                $transact_arr = array();
                $transact_arr['related_id'] = $applyid;
                $transact_arr['ward_mstr_id'] = $data["ward_mstr_id"];
                $transact_arr['transaction_type'] = $data["application_type"]["application_type"];
                $transact_arr['transaction_date'] = date('Y-m-d');
                $transact_arr['payment_mode'] = $inputs['payment_mode'];
                $transact_arr['paid_amount'] = $rate_data['total_charge'];

                $transact_arr['penalty'] = $rate_data['penalty'];
                $transact_arr['status'] = 1;
                if ($inputs['payment_mode'] != 'CASH' && $inputs['payment_mode'] != 'Online') {
                    $transact_arr['status'] = 2;
                }
                $transact_arr['emp_details_id'] = $emp_id;
                $transact_arr['created_on'] = date('Y-m-d H:i:s');
                $transact_arr['ip_address'] = '';

                $transaction_id = 0; //$this->TradeTransactionModel->insertPayment($transact_arr); 
                //echo $transaction_id;
                $trafinerebate = array();
                $trafinerebate['transaction_id'] = $transaction_id;
                $trafinerebate['head_name'] = 'Delay Apply License';
                $trafinerebate['amount'] = $rate_data['penalty'];
                $trafinerebate['value_add_minus'] = 'Add';
                $trafinerebate['created_on'] = date('Y-m-d H:i:s');
                //$this->model_trade_transaction_fine_rebet_details->fine_rebet_details($trafinerebate);



                $payment_status = 0;
                if ($inputs['payment_mode'] != 'CASH'  && $inputs['payment_mode'] != 'Online') {
                    $chq_arr = array();
                    $chq_arr['transaction_id'] = $transaction_id;
                    $chq_arr['cheque_no'] = $inputs['chq_no'];
                    $chq_arr['cheque_date'] = $inputs['chq_date'];
                    $chq_arr['bank_name'] = $inputs['bank_name'];
                    $chq_arr['branch_name'] = $inputs['branch_name'];
                    $chq_arr['emp_details_id'] = $emp_id;
                    $chq_arr['created_on'] = date('Y-m-d H:i:s');
                    $payment_status = 2;
                    //$this->TradeChequeDtlModel->insertdata($chq_arr);
                }

                # Update Application No
                // $app_no = "APPLIC" . $applyid . date('dmyhis');
                $app_no = "APN".str_pad(substr($ward_no,0,2),2,"0",STR_PAD_LEFT).STR_PAD($applyid,7,"0",STR_PAD_LEFT);
                $this->TradeApplyLicenceModel->update_application_no($app_no, $applyid, $payment_status);

                /*if($this->db->transStatus() === FALSE)
                    {
                        $this->db->transRollback();
                        flashToast("applylicence", "Something errordue to payment!!!");
                        if($data["application_type_id"]==1)
                        {
                            return $this->response->redirect(base_url('tradeapplylicence/applynewlicence/'.$apptypeid));
                        }
                        else
                        {
                            return $this->response->redirect(base_url('tradeapplylicence/applynewlicence/'.$apptypeid.'/'.$id));
                        }
                    }
                    else
                    {
                        $this->db->transCommit();
                        if($data["application_type"]["id"]<>4)
                        {
                            return $this->response->redirect(base_url('tradeapplylicence/view_transaction_receipt/'.md5($applyid).'/'.md5($transaction_id)));
                        }
                        else
                        {
                            return $this->response->redirect(base_url('tradeapplylicence/trade_licence_view/'.md5($applyid)));
                        }
                    }*/
            }

            # Surrender
            elseif ($data["application_type"]["id"] == 4) 
            {
                /*$rules=[
                        'area_in_sqft' =>'required',
                    ];*/
                if (!$this->validate($rules)) {
                    $data['validation'] = $this->validator;
                    return view('citizen/trade/applylicence', $data);
                }
                $inputs = filterSanitizeStringtoUpper($this->request->getVar());
                //print_var($inputs);

                $data["otherfirmtype"] = $inputs["firmtype_other"];
                $data["firm_type_id"] = $data["licencedet"]["firm_type_id"];
                $data["ownership_type_id"] = $data["licencedet"]["ownership_type_id"];
                $data["ward_mstr_id"] = $data["licencedet"]["ward_mstr_id"];
                $data["new_ward_mstr_id"] = $data["licencedet"]["new_ward_mstr_id"];
                $data["owner_business_premises"] = $data["licencedet"]["premises_owner_name"];
                $data["prop_dtl_id"] = $data["licencedet"]["prop_dtl_id"];
                $data["property_type"] = $data["licencedet"]["property_type"];
                $data["licence_for_years"] = $data["licencedet"]["licence_for_years"];
                $data["area_in_sqft"] = (float)$data["licencedet"]["area_in_sqft"];
                $data["nature_of_bussiness"] = $data["licencedet"]["nature_of_bussiness"];
                $data["brife_desp_firm"] = $data["licencedet"]['brife_desp_firm'];
                $data["valid_from"] = date('Y-m-d');
                $data["valid_upto"] = $data["licencedet"]["valid_upto"];
                $data["license_no"] = $data["licencedet"]["license_no"];
                $data["payment_status"] = 1; // No Payment in case of surrender
                $data["rate_id"] = 16; // 0 Rs Payment in surrender as tbl_licence_rate
                $data['emp_details_id'] = $emp_id;
                $data['created_on'] = date('Y-m-d H:i:s');
                $data["firm_date"] = $data["licencedet"]['establishment_date'];
                $applyid = $this->TradeApplyLicenceModel->insertapply($data);
                $this->TradeApplyLicenceModel->update_status_id($applyid, $data["licencedet"]["id"]);

                $owner_arr = array();
                $owner_arr['apply_licence_id'] = $applyid;
                $owner_arr['licence_id'] = $data["licencedet"]["id"];
                $owner_arr['emp_details_id'] = $emp_id;
                $owner_arr['created_on'] = date('Y-m-d H:i:s');
                if (isset($data["ownerdet"])) {
                    if (!empty($data["ownerdet"])) {
                        $this->TradeFirmOwnerModel->insertrenewdata($owner_arr);
                    }
                }

                $warddet = $this->ward_model->getWardNoBywardId($data["ward_mstr_id"]);
                $ward_no = $warddet["ward_no"];
                // $app_no = "APPLIC" . $applyid . date('dmyhis');
                $app_no = "APN".str_pad(substr($ward_no,0,2),2,"0",STR_PAD_LEFT).STR_PAD($applyid,7,"0",STR_PAD_LEFT);
                $this->TradeApplyLicenceModel->update_application_no($app_no, $applyid, $data["payment_status"]);

                /*if($this->db->transStatus() === FALSE)
                    {
                        $this->db->transRollback();
                        flashToast("applylicence", "Something errordue to payment!!!");
                        if($data["application_type_id"]==1)
                        {
                            return $this->response->redirect(base_url('tradeapplylicence/applynewlicence/'.$apptypeid));
                        }
                        else
                        {
                            return $this->response->redirect(base_url('tradeapplylicence/applynewlicence/'.$apptypeid.'/'.$id));
                        }
                    }
                    else
                    { 
                        $this->db->transCommit();
                        if($data["application_type"]["id"]<>4)
                        {
                            return $this->response->redirect(base_url('tradeapplylicence/view_transaction_receipt/'.md5($applyid).'/'.md5($transaction_id)));
                        }
                        else
                        {
                            return $this->response->redirect(base_url('tradeapplylicence/trade_licence_view/'.md5($applyid)));
                        }
                    }*/
            }
            // print_var($data["application_type"]["id"]);return;
            if ($this->db->transStatus() === FALSE) 
            {
                $this->db->transRollback();
                flashToast("applylicence", "Something errordue to payment!!!");
                if ($data["application_type_id"] == 1) {

                    return $this->response->redirect(base_url('TradeCitizen/applynewlicence/' . $apptypeid));
                } else {

                    return $this->response->redirect(base_url('TradeCitizen/applynewlicence/' . $apptypeid . '/' . $id));
                }
            } 
            else 
            {
                $this->db->transCommit();
                if ($data["application_type"]["id"] <> 4) {
                    return $this->response->redirect(base_url('TradePaymentCitizen/handleRazorPayRequest/' . md5($applyid)));
                } elseif ($data["application_type"]["id"] == 4) {
                    return $this->response->redirect(base_url('TradeCitizen/doc_upload/' . md5($applyid)));
                } else {
                    return $this->response->redirect(base_url('TradeCitizen/trade_licence_view/' . md5($applyid)));
                }
            }
        }
        $data["tradeitemlist"] = $this->tradeitemsmstrmodel->gettradeitemsList();
        $data['categoryTypeDetails'] = $this->TradeCategoryTypeModel->getCategoryType();

        // print_var($data);
        return view('citizen/trade/applylicence', $data);
    }


    public function doc_upload($id = null, $transaction_id = null)
    {
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $ulb_mstr_id = $ulb_mstr["ulb_mstr_id"];
        //$ulb_shrt_nm = $ulb_mstr["short_ulb_name"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
        $data = (array)null;
        $data["aid"] = $id;
        $data["transaction_id"] = $transaction_id;
        $data['ulb_dtl'] = $this->modelUlb->getulb_list($ulb_mstr_id);
        $ulb_city_nm = $data['ulb_dtl']['city'];
        $photo_id_proof_doc_for = 'Identity Proof';
        $data['photo_id_proof_document_list'] = $this->model_trade_document->getDocumentList($photo_id_proof_doc_for);
        $data['trade_conn_dtl'] = $this->model_apply_licence->getData($id);
        $apply_licence_id = $data['trade_conn_dtl']['id'];

        $data['owner_list'] = $this->model_firm_owner_name->applicantdetails_md5($id);
        $data['trans_details'] = $this->TradeTransactionModel->get_trans_details(md5($data['trade_conn_dtl']["id"])); //print_var($data['trans_details']);
        $data['payment_mode']  =  $data['trans_details']['payment_mode'] ?? NULL;
        if (isset($data['payment_mode']) &&  !in_array(strtolower($data['payment_mode']), ['cash', 'online'])) {
            $data['cheque_details'] = $this->TradeChequeDtlModel->get_check_details($data['trans_details']['id']);
        }

        $firm_owner_id = "";
        $l = 0;
        foreach ($data['owner_list'] as $key => $value) {
            $data['doc_details_owner'] = $this->model_application_doc->check_doc_exist_owner($apply_licence_id, $value['id']);
            $data['owner_list'][$key]['doc_upload_id'] = $data['doc_details_owner']['id'] ?? NULL;
            $data['owner_list'][$key]['document_path'] = $data['doc_details_owner']['document_path'] ?? NULL;
            $data['owner_list'][$key]['doc_document_id'] = $data['doc_details_owner']['document_id'] ?? NULL;

            if ($l == 0) {
                $firm_owner_id = array($value['id']);
            } else {
                array_push($firm_owner_id, $value['id']);
            }
            $l++;
        }

        if ($firm_owner_id) {
            $string_owner_id = implode(', ', $firm_owner_id);
        }

        // get owner doc list   
        $data['owner_doc_list'] = $this->model_firm_owner_name->count_doc_list_owner($id);
        //uploaded doc details 
        $data['doc_details_owner_count'] = $this->model_application_doc->count_doc_exist_owner($apply_licence_id, $string_owner_id);
        $data["licencedet"] = $this->TradeApplyLicenceModel->apply_licence_md5($id);

        // print_var($data['licencedet']);return;
        $data["application_type"] = $this->tradeapplicationtypemstrmodel->getdatabyid($data["licencedet"]["application_type_id"]);
        $data["firmtype"] = $this->tradefirmtypemstrmodel->getdatabyid($data["licencedet"]["firm_type_id"]);
        $data["ownershiptype"] = $this->tradeownershiptypemstrmodel->getdatabyid($data["licencedet"]["ownership_type_id"]);
        $data['ward_no'] = $this->ward_model->getWardNoById($data["licencedet"]);
        $data['new_ward_no'] = $this->ward_model->getWardNoBywardId($data["licencedet"]["new_ward_mstr_id"]);
        $data["idprooflist"] = $this->model_trade_document->getIdPoorfDocumentList();
        $show = '1';
        if ($data['trade_conn_dtl']['application_type_id'] == 1) {
            if ($data['trade_conn_dtl']['ownership_type_id'] == 1) {
                $show .= ',' . '2';
            } else {
                $show .= ',' . '3';
            }
            if ($data['trade_conn_dtl']['firm_type_id'] == 2) {
                $show .= ',' . '4';
            } elseif ($data['trade_conn_dtl']['firm_type_id'] == 3 or $data['trade_conn_dtl']['firm_type_id'] == 4) {
                $show .= ',' . '5';
            }
            if ($data['trade_conn_dtl']['category_type_id'] == 2) {
                $show .= ',' . '6';
            }
        }


        $data['doc_details'] = $this->model_trade_document->getDocumentDetailsCitizen($data['trade_conn_dtl']['application_type_id'], $show);
        $data['doc_count'] = $this->model_trade_document->getDocumentcountCitizen($data['trade_conn_dtl']['application_type_id'], $show);
        $data['doc_count_mandatory'] = $this->model_trade_document->getDocumentcountMandatoryCitizen($data['trade_conn_dtl']['application_type_id'], $show);
        $data['doc_upload_count'] = $this->model_application_doc->check_upload_doc_count($apply_licence_id);
        $docmnt_id = [];
        $i = 0;
        foreach ($data['doc_upload_count'] as $key => $value) {
            if ($i == 0) {
                $docmnt_id = array($value['document_id']);
            } else {
                array_push($docmnt_id, $value['document_id']);
            }
            $i++;
        }


        if ($docmnt_id) {
            $string_doc_id = implode(', ', $docmnt_id);
            $data['doc_cnt_mndtry'] = $this->model_trade_document->Documentmandatory_count($data["application_type"]['id'], $string_doc_id);
            $data['doc_cnt'] = $this->model_trade_document->Document_count($data["application_type"]['id'], $string_doc_id);
        } else {
            $data['doc_cnt_mndtry']['count'] = 0;
            $data['doc_cnt']['count'] = 0;
        }

        //count owner document upload details
        foreach ($data['doc_details'] as $key1 => $value1) {
            $data['doc_details'][$key1]['docfor'] = $this->model_trade_document->getDocumentappList($data['trade_conn_dtl']['application_type_id'], $value1['doc_for']);
            $data['doc_details'][$key1]['docexists'] = $this->model_application_doc->check_doc_exist($apply_licence_id, $value1['doc_for']);
        }



        $firm_id = $data['owner_list']['id'] ?? NULL;
        $firm_type_id = $data['trade_conn_dtl']['firm_type_id'];
        $ownership_type_id = $data['trade_conn_dtl']['ownership_type_id'];
        $doc_status = $data['trade_conn_dtl']['document_upload_status'];
        $payment_status = $data['trade_conn_dtl']['payment_status'];

        $data["tradedetail"] = $this->tradeitemsmstrmodel->tradedetail($data["licencedet"]['nature_of_bussiness']);
        // print_var($data["licencedet"]['category_type_id']);die;
        $data['categoryDetails'] = $this->TradeCategoryTypeModel->categoryDetails($data["licencedet"]['category_type_id']);
        // print_var($data['categoryDetails']);return;
        $data['hide_rmc_btn'] = $this->model_trade_level_pending_dtl->hide_rmc_btn($apply_licence_id);
        $data['apply_licence_id'] = $apply_licence_id;
        $data['temp'] = $temp ?? NULL;
        if ($this->request->getMethod() == 'post') {
            # Upload Owner Document Id Proof
            if (isset($_POST['btn_doc_path'])) {
                $cnt = $_POST['btn_doc_path'];
                $rules = [
                    'doc_path' => 'uploaded[doc_path' . $cnt . ']|max_size[doc_path' . $cnt . ',30720]|ext_in[doc_path' . $cnt . ',pdf, jpg, jpeg]',
                    'doc_mstr_id' . $cnt . '' => 'required',
                ];
                // print_r($rules); 
                if ($this->validate($rules)) {
                    $doc_path = $this->request->getFile('doc_path' . $cnt);
                    if ($doc_path->IsValid() && !$doc_path->hasMoved()) {
                        try {
                            $this->db->transBegin();
                            $input = [
                                'apply_licence_id' => $apply_licence_id,
                                'doc_for' => $this->request->getVar('doc_for' . $cnt),
                                'document_id' => $this->request->getVar('doc_mstr_id' . $cnt),
                                'emp_details_id' => $login_emp_details_id,
                                'created_on' => date('Y-m-d H:i:s'),
                                'firm_owner_dtl_id' => $this->request->getVar('ownrid'),
                            ];

                            if ($app_doc_dtl_id = $this->model_application_doc->check_upload_doc_exist($input)) {
                                $delete_path = WRITEPATH . 'uploads/' . $app_doc_dtl_id['document_path'];

                                // unlink($delete_path);
                                deleteFile($delete_path);
                                $newFileName = md5($app_doc_dtl_id['id']);
                                $file_ext = $doc_path->getExtension();
                                $path = $ulb_city_nm . "/" . "trade_doc_dtl";
                                $doc_path->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext);
                                $doc_path_save = $path . "/" . $newFileName . '.' . $file_ext;
                                $this->model_application_doc->updatedocpathById($app_doc_dtl_id['id'], $doc_path_save, $input['document_id']);
                            } else if ($app_doc_dtl_id = $this->model_application_doc->insertData($input)) {
                                $newFileName = md5($app_doc_dtl_id);
                                $file_ext = $doc_path->getExtension();
                                $path = $ulb_city_nm . "/" . "trade_doc_dtl";
                                $doc_path->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext);
                                $doc_path_save = $path . "/" . $newFileName . '.' . $file_ext;
                                $this->model_application_doc->updatedocpathById($app_doc_dtl_id, $doc_path_save, $input['document_id']);
                            }
                            if ($this->db->transStatus() === FALSE) {
                                $this->db->transRollback();
                            } else {
                                $this->db->transCommit();
                                return $this->response->redirect(base_url('TradeCitizen/doc_upload/' . $id . '/' . $transaction_id));
                            }
                        } catch (Exception $e) {
                        }
                    } else {
                        $errMsg = "<ul><li>something errors in SAF form details.</li></ul>";
                        $data['errors'] =   $errMsg;
                        return view('citizen/trade/trade_document_upload', $data);
                    }
                } else {

                    $errMsg = $this->validator->listErrors();
                    $data['errors'] =   $errMsg;
                    return view('citizen/trade/trade_document_upload', $data);
                }
            }







            # Upload Document
            if (isset($_POST['btn_doc_path_owner'])) {
                $cnt_owner = $_POST['btn_doc_path_owner'];

                $rules = [
                    'doc_path_owner' => 'uploaded[doc_path_owner' . $cnt_owner . ']|max_size[doc_path_owner' . $cnt_owner . ',30720]|ext_in[doc_path_owner' . $cnt_owner . ',pdf]',
                    'idproof' . $cnt_owner . '' => 'required',
                ];

                if ($this->validate($rules)) {

                    $doc_path = $this->request->getFile('doc_path_owner' . $cnt_owner);
                    if ($doc_path->IsValid() && !$doc_path->hasMoved()) {
                        try {
                            $this->db->transBegin();
                            $input = [
                                'firm_owner_dtl_id' => $this->request->getVar('ownrid'),
                                'apply_licence_id' => $apply_licence_id,
                                'doc_for' => $this->request->getVar('doc_for' . $cnt_owner),
                                'document_id' => $this->request->getVar('idproof' . $cnt_owner),
                                'emp_details_id' => $login_emp_details_id,
                                'created_on' => date('Y-m-d H:i:s'),
                            ];


                            if ($app_doc_dtl_id = $this->model_application_doc->check_upload_doc_exist_owner($input)) {
                                //print_var($app_doc_dtl_id);die;
                                $delete_path = WRITEPATH . 'uploads/' . $app_doc_dtl_id['document_path'];
                                // unlink($delete_path);
                                deleteFile($delete_path);
                                $newFileName = md5($app_doc_dtl_id['id']);
                                $file_ext = $doc_path->getExtension();
                                $path = $ulb_city_nm . "/" . "trade_doc_dtl";
                                $doc_path->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext);
                                $doc_path_save = $path . "/" . $newFileName . '.' . $file_ext;
                                $this->model_application_doc->updatedocpathById($app_doc_dtl_id['id'], $doc_path_save, $input['document_id']);
                            } else if ($app_doc_dtl_id = $this->model_application_doc->insertData($input)) {
                                $newFileName = md5($app_doc_dtl_id);
                                $file_ext = $doc_path->getExtension();
                                $path = $ulb_city_nm . "/" . "trade_doc_dtl";
                                $doc_path->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext);
                                $doc_path_save = $path . "/" . $newFileName . '.' . $file_ext;
                                $this->model_application_doc->updatedocpathById($app_doc_dtl_id, $doc_path_save, $input['document_id']);
                            }
                            if ($this->db->transStatus() === FALSE) {

                                $this->db->transRollback();
                            } else {

                                $this->db->transCommit();
                                return $this->response->redirect(base_url('TradeCitizen/doc_upload/' . $id . '/' . $transaction_id));
                            }
                        } catch (Exception $e) {
                        }
                    } else {
                        $errMsg = "<ul><li>something errors in SAF form details.</li></ul>";
                        $data['errors'] =   $errMsg;
                        return view('citizen/trade/trade_document_upload', $data);
                    }
                } else {
                    $errMsg = $this->validator->listErrors();
                    $data['errors'] =   $errMsg;
                    return view('citizen/trade/trade_document_upload', $data);
                }
            }
            //owner document upload code end
        } else {
            $data['hide_rmc_btn'] = $this->model_trade_level_pending_dtl->hide_rmc_btn($apply_licence_id);
            $data['apply_licence_id'] = $apply_licence_id;
            $data['temp'] = $temp ?? NULL; 
            //print_var($data['transaction_id']);
            return view('citizen/trade/trade_document_upload', $data);
        }
    }



    public function send_rmc($id = null, $appid = null, $transaction_id = null)
    {
        //echo"id=$id, appid=$appid, transaction_id=$transaction_id";die;
        $sql = "select count(*) as count from tbl_level_pending where apply_licence_id = ?";
        $count = $this->model_trade_level_pending_dtl->rowQuery($sql,[$id])[0]['count']??0;
        if($count!=0)
        {
            return $this->response->redirect(base_url('TradeCitizen/docview/' . $appid));
        } 
        $Session = Session();
        date_default_timezone_set('Asia/Kolkata');
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");

        $login_emp_details_id = $emp_mstr["id"];
        $data = (array)null;
        $data['apply_licence_id'] = $id;
        $leveldata = [
            'apply_licence_id' => $data['apply_licence_id'],
            'sender_user_type_id' => 0,
            'receiver_user_type_id' => 17,
            'created_on' => date('Y-m-d H:i:s'),
            'remarks' => '',
            'emp_details_id' => $login_emp_details_id,
            'verification_status' => 0
        ];
        //print_r($leveldata);
        $data["license"] = $this->TradeApplyLicenceModel->apply_licence_md5(md5($data['apply_licence_id']));
        $sw = [];
        $sw['application_statge']= 3 ;        
        $where_sw = ['apply_license_id'=>$data['apply_licence_id']];
        $get_ws = $this->Citizensw_trade_model->getData($where_sw);
        //print_var($get_ws);die;
        if($data["license"]['apply_from']=='sws' && !empty($get_ws))
        {
            // print_var($get_ws);die;
            $where_sw = ['apply_license_id'=>$data['apply_licence_id'],'id'=> $get_ws['id']];
            $this->Citizensw_trade_model->updateData($sw,$where_sw);
            $push_sw=array();
            $path = base_url('TradeCitizen/provisionalCertificate/'.md5($data["license"]['id']));
            $push_sw['application_stage']=12;
            $push_sw['status']='Application sent to Dealing Assistant';
            $push_sw['acknowledgment_no']=$data["license"]['application_no'];
            $push_sw['service_type_id']=$get_ws['service_id'];
            $push_sw['caf_unique_no']=$get_ws['caf_no'];
            $push_sw['department_id']=$get_ws['department_id'];
            $push_sw['Swsregid']=$get_ws['cust_id'];
            $push_sw['payable_amount ']='';
            $push_sw['payment_validity']='';
            $push_sw['payment_other_details']='';
            $push_sw['certificate_url']=$path;
            $push_sw['approval_date']=$data["license"]['valid_from'];
            $push_sw['expire_date']=$data["license"]['valid_upto'];
            $push_sw['licence_no']=$data["license"]['license_no'];
            $push_sw['certificate_no']=$data["license"]['provisional_license_no'];
            $push_sw['customer_id']=$get_ws['cust_id'];
            $post_url = getenv('single_indow_push_url');
            $http = getenv('single_indow_push_http');
            $resp = httpPostJson($post_url,$push_sw,$http);

            $respons_data=[];
            $respons_data['apply_license_id']=$data['apply_licence_id'];
            $respons_data['response_msg']= json_encode(['url'=>$http.'/'.$post_url,
                                                        'data'=>$push_sw]);
            $respons_data['tbl_single_window_id']=$get_ws['id'];
            $respons_data['emp_id']=$login_emp_details_id;
            $respons_data['response_status']=json_encode($resp);
            $this->Citizensw_trade_model->insertResponse($respons_data);
            // print_var($http.'/'.$post_url);
            // print_var($resp);die;

        }
        $data['doc_upload_stts'] = $this->model_apply_licence->update_doc_status($leveldata);
        $level_pending_insrt = $this->model_trade_level_pending_dtl->insrtlevelpendingdtl($leveldata);
        $data['trade_conn_dtl'] = $this->model_apply_licence->getData($appid);
        if ($data['trade_conn_dtl']['application_type_id'] == 4) {
            return $this->response->redirect(base_url('TradeCitizen/docview/' . $appid));
        } else {
            return $this->response->redirect(base_url('TradeCitizen/view_transaction_receipt/' . $appid . '/' . $transaction_id));
        }
    }


    public function docview($id = null)
    {
        $data = (array)null;
        $data['id'] = $id;

        $data['basic_details'] = $this->model_view_trade_licence->getDatabyid($id);
        $data['ward'] = $this->ward_model->getdatabyid($data['basic_details']['ward_mstr_id']);
        $data['owner_details'] = $this->model_firm_owner_name->applicantdetails($data['basic_details']['id']);
        $data['holding'] = $this->model_apply_licence->getholding($data['basic_details']['application_no']);
        $data['application_status'] = $this->applicationStatus_md5($id);
        $verify_status = '0';
        foreach ($data['owner_details'] as $key => $value) {
            $app__doc = 'Consumer Photo';
            $data['owner_details'][$key]['saf_owner_img_list'] = $this->model_application_doc->get_ownerimgdetails_by_safid($data['basic_details']['id'], $value['id'], $app__doc);
            $app_doc_type = "Identity Proof";
            $data['owner_details'][$key]['saf_owner_doc_list'] = $this->model_application_doc->conownerdocdetbyid($data['basic_details']['id'], $value['id'], $app_doc_type);
        }
        $apply_licence_id = $data['basic_details']['id'];
        $data['doc_exists'] = $this->trade_view_application_doc_model->getdocdet_by_appid($apply_licence_id);
        return view('citizen/trade/trade_doc_view', $data);
    }

    public function view_transaction_receipt_copy($applyid = null, $transaction_id = null)
    {
        $data = array();
        $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
        $path = base_url('citizenPaymentReceipt/view_trade_transaction_receipt/' . $ulb_mstr_id . '/' . $applyid . '/' . $transaction_id);
        $data['ss'] = qrCodeGeneratorFun($path);

        $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
        $data['ulb_mstr_id'] = $ulb_mstr_id;
        $data['ulb_mstr_name'] = $this->modelUlb->getulb_list($ulb_mstr_id);
        $data['applicant_details'] = $this->TradeApplyLicenceModel->fetch_all_application_data($applyid);
        $data['transaction_details'] = $this->TradeTransactionModel->transaction_details($transaction_id);
        $warddet = $this->ward_model->getWardNoBywardId($data['transaction_details']['ward_mstr_id']);
        $data['ward_no'] = $warddet["ward_no"];
        $data['status'] = [1, 2];
        $data['cheque_details'] = $this->TradeChequeDtlModel->alltransactioncheque_details($transaction_id);
        //print_r($data['cheque_details']);
        return view('citizen/trade/payment_receipt_citizen', $data);
    }

    public function view_transaction_receipt($applyid = null, $transaction_id = null)
    {

        return $this->response->redirect(base_url("TradeCitizen/viewTransactionReceipt/$transaction_id/$applyid"));
    }



    public function getcharge1($args = array())
    {
        if (!empty($args) || $this->request->getMethod() == "post") {
            if (!empty($args))
                $inputs = $args;
            else
                $inputs = arrFilterSanitizeString($this->request->getVar());

            $data = array();
            $data['area_in_sqft'] = (float)$inputs['areasqft'];
            $data['application_type_id'] = $inputs['applytypeid'];
            $data['firm_date'] = $inputs['estdate'];
            $data['tobacco_status'] = $inputs['tobacco_status'];
            $data['timeforlicense'] = $inputs['licensefor'];
            $data['curdate'] = date("Y-m-d");
            $denial_amount_month = 0;
            $count = $this->tradelicenceratemodel->getrate($data);
            $rate = $count['rate'] * $data['timeforlicense'];
            $vDiff = abs(strtotime($data['curdate']) - strtotime($data['firm_date'])); // here abs in case theres a mix in the dates
            $vMonths = ceil($vDiff / (30 * 60 * 60 * 24)); // number of seconds in a month of 30 days
            if ($vMonths > 0) {
                $denial_amount_month = 100 + (($vMonths) * 20);
            }
            $total_denial_amount = $denial_amount_month + $rate;

            # Check If Any cheque bounce charges
            if (isset($inputs['apply_licence_id'], $inputs['apply_licence_id'])) {
                $penalty = $this->TradeTransactionModel->getChequeBouncePenalty($inputs['apply_licence_id']);
                $denial_amount_month += $penalty;
                $total_denial_amount += $penalty;
            }

            if ($count) {
                $response = ['response' => true, 'rate' => $rate, 'penalty' => $denial_amount_month, 'total_charge' => $total_denial_amount];
            } else {
                $esponse = ['response' => false];
            }
            //echo $count;
            return json_encode($response);
        }
    }


    public function getdistrictname()
    {

        if ($this->request->getMethod() == "post") {

            $data = array();
            $inputs = arrFilterSanitizeString($this->request->getVar());
            // $state_id=$inputs['state_id'];  
            $state_name = $inputs['state_name'];
            $statedet = $this->statemodel->getstateid($state_name);

            $state_id = $statedet['id'];

            $count = $this->districtmodel->getdistrictbystateid($state_id);
        }
        return json_encode($count);
    }

    public function validate_saf_no()
    {
        if ($this->request->getMethod() == "post") {
            $data = array();
            $inputs = arrFilterSanitizeString($this->request->getVar());

            $data['saf_no'] = $inputs['saf_no'];

            $safdet = $this->model_saf_dtl->getSafDtlBySafno($data);

            $data["saf_dtl_id"] = $safdet["id"];
            if ($count = $this->model_saf_owner_detail->getOwnerdtlBySAFId($data)) {

                $response = ['response' => true, 'dd' => $count, 'sf' => $safdet];
            } else {
                $response = ['response' => false];
            }
        } else {
            $response = ['response' => false];
        }
        return json_encode($response);
    }

    public function validate_holding_no()
    {
        if ($this->request->getMethod() == "post") {
            $data = array();
            $inputs = arrFilterSanitizeString($this->request->getVar());

            $propdet = $this->model_prop_dtl->propertyDetailsfortradebyHoldingNo($inputs);
            $response = ['response' => true, 'pp' => $propdet];
        } else {
            $response = ['response' => false];
        }
        return json_encode($response);
    }


    public function searchLicense2($apptypeid = null)
    {
        $data = (array)null;
        if ($apptypeid <> null) {
            $data["application_type"] = $this->tradeapplicationtypemstrmodel->getdatabymd5id($apptypeid);
            $data["msg"] = '';
            if ($this->request->getMethod() == "post") {
                $rules = [
                    'Searchlicense' => 'required',
                ];
                if (!$this->validate($rules)) {
                    $data['validation'] = $this->validator;
                    return view('citizen/trade/SearchLicense', $data);
                } else {
                    $inputs = filterSanitizeStringtoUpper($this->request->getVar());
                    $data["Searchlicense"] = $inputs['Searchlicense'];
                    if ($data["application_type"]["id"] == 3) {
                        // if applying for amendment, then search surrendered license
                        $application_type_id = 4; //surrender
                        $licensedata = $this->TradeApplyLicenceModel->getlicencedataSurrendered($data["Searchlicense"], $application_type_id);
                    } else {
                        $licensedata = $this->TradeApplyLicenceModel->getlicencedata($data["Searchlicense"]);
                    }

                    if (isset($licensedata, $licensedata)) {
                        if (is_null($licensedata["valid_upto"]) and $licensedata["update_status"] == 0 && $licensedata["pending_status"] != 5) {
                            $data["msg"] = "Already Applied! Please Track Status of Application No." . $licensedata["application_no"];
                        } else {
                            $id = md5($licensedata["id"]);
                            if ($data["application_type"]["id"] <> 4) {
                                if (date('Y-m-d', strtotime($licensedata["valid_upto"] . ' - 30 days')) > date('Y-m-d') && $data["application_type"]["id"] != 3) {
                                    $data["msg"] = "License Not Expired! This Licence Is Valid Upto " . $licensedata["valid_upto"];
                                } else {
                                    if ($data["application_type"]["id"] == 3) {
                                        if ($licensedata["application_type_id"] == 4 && $licensedata["pending_status"] == 5) {
                                            return $this->response->redirect(base_url('TradeCitizen/applynewlicence/' . $apptypeid . '/' . $id));
                                        } else {
                                            //print_var($licensedata);
                                            $data["msg"] = "Application No. $licensedata[application_no] is applied for surrender against License No. $licensedata[license_no] which is not approved yet.";
                                        }
                                    } else {
                                        return $this->response->redirect(base_url('TradeCitizen/applynewlicence/' . $apptypeid . '/' . $id));
                                    }
                                }
                            } else {
                                if (is_null($licensedata["valid_upto"]) && $licensedata["update_status"] && $licensedata["pending_status"] != 5) {

                                    $data["msg"] = "Already Applied! Please Track Status of Application No." . $licensedata["application_no"];
                                } else if ($licensedata['valid_upto'] <= date('Y-m-d')) {
                                    $data["msg"] = "License No. $licensedata[license_no] is valid till $licensedata[valid_upto], which has expired. Therefore, please apply for renewal before surrender.";
                                } else {
                                    return $this->response->redirect(base_url('TradeCitizen/applynewlicence/' . $apptypeid . '/' . $id));
                                }
                            }
                        }
                    } else {
                        $licensedata = $this->TradeApplyLicenceModel->getlicencedata($data["Searchlicense"]);
                        if ($licensedata && $data["application_type"]["id"] == 3) {
                            $data["msg"] = "Please apply for surrender before amendment.";
                        } else
                            $data["msg"] = "License Not found.";
                    }
                }
            }

            return view('citizen/trade/SearchLicense', $data);
        }
    }
    public function searchLicense($apptypeid = null)
    {
        $data = (array)null;
        if ($apptypeid <> null) 
        {
            $data["application_type"] = $this->tradeapplicationtypemstrmodel->getdatabymd5id($apptypeid);
            $data["msg"] = '';
            if ($this->request->getMethod() == "post") 
            {
                $rules = [
                    'Searchlicense' => 'required',
                ];
                if (!$this->validate($rules)) 
                {
                    $data['validation'] = $this->validator;
                    return view('citizen/trade/SearchLicense', $data);
                }
                $inputs = filterSanitizeStringtoUpper($this->request->getVar());
                $data["Searchlicense"] = $inputs['Searchlicense'];
                $nextMonth = date('Y-m-d', strtotime(date('Y-m-d') . ' + 1 Month'));
                
                $licensedata = $this->TradeApplyLicenceModel->getlicencedata($data["Searchlicense"]);
                
                if(!$licensedata)
                {
                    $data["msg"] = "License Not found.";
                }
                elseif($licensedata['pending_status'] != 5)
                {
                    $data["msg"] = "Application Already Apply. Please Track App No. ".$licensedata['application_no'];
                }
                elseif($licensedata["application_type_id"]!= 4 && $data["application_type"]["id"] == 3)
                {
                    $data["msg"] = "Please apply for surrender before amendment.";
                }
                elseif($licensedata["valid_upto"] > $nextMonth && !in_array($data["application_type"]["id"],[4,3]))
                {
                    $data["msg"] = "License Not Expired! This Licence Is Valid Upto " . $licensedata["valid_upto"];
                }
                elseif($licensedata["valid_upto"] < date('Y-m-d') && in_array($data["application_type"]["id"],[4,3]))
                {
                    $data["msg"] = "License No. $licensedata[license_no] is valid till $licensedata[valid_upto], which has expired. Therefore, please apply for renewal before surrender.";
                }
                else
                {
                    $id = md5($licensedata["id"]);
                    return $this->response->redirect(base_url('TradeCitizen/applynewlicence/' . $apptypeid . '/' . $id));
                }
                
            }

            return view('citizen/trade/SearchLicense', $data);
        }
    }



    public function tobaccoapplynewlicence($apptypeid = null, $id = null)
    {
        $data = array();
        $session = session();
        $get_ulb_id = $session->get('ulb_dtl');
        //print_r($get_ulb_id["ulb_mstr_id"]);
        $get_emp_details = $session->get('emp_details');
        $data['ulb_mstr_name'] = $this->modelUlb->getulb_list($get_ulb_id["ulb_mstr_id"]);
        $short_ulb_name = $data["ulb_mstr_name"]["short_ulb_name"];
        $emp_id = $get_emp_details['id'];
        $ip_address = $get_emp_details['ip_address'];
        $data = (array)null;
        $data['curdate'] = date("Y-m-d");
        $data["apptypeid"] = $apptypeid;
        $data["id"] = $id;
        $data["statelist"] = $this->statemodel->getstateList();
        $data["idprooflist"] = $this->model_trade_document->getIdPoorfDocumentList();
        $data['ward_list'] = $this->ward_model->getWardList($get_ulb_id);
        $data["application_type"] = $this->tradeapplicationtypemstrmodel->getdatabymd5id($apptypeid);
        $data["firmtypelist"] = $this->tradefirmtypemstrmodel->getFirmTypeList();
        $data["ownershiptypelist"] = $this->tradeownershiptypemstrmodel->getownershipTypeList();
        // $data["tradeitemlist"] = $this->tradeitemsmstrmodel->gettradeitemsList();

        if ($apptypeid <> null and $id <> null) {
            //Check Licence Number Is Debarred Or Not

            $data["application_type"] = $this->tradeapplicationtypemstrmodel->getdatabymd5id($apptypeid);
            $data["application_type_id"] = $data["application_type"]["id"];
            $data["licencedet"] = $this->TradeApplyLicenceModel->apply_licence_md5($id);

            $data["tradedetail"] = $this->tradeitemsmstrmodel->tradedetail($data["licencedet"]['nature_of_bussiness']);
            $data['categoryDetails'] = $this->TradeCategoryTypeModel->categoryDetails($data["licencedet"]['category_type_id']);

            $data['ward_no'] = $this->ward_model->getWardNoById($data["licencedet"]);
            $data['new_ward_no'] = $this->ward_model->getWardNoBywardId($data["licencedet"]["new_ward_mstr_id"]);
            $data["ownerdet"] = $this->TradeFirmOwnerModel->getdatabyid_md5($id);
            //$data["tradeitemdet"] = $this->model_trade_view_licence_trade_items->get_details($id);            

            $data["firmtype"] = $this->tradefirmtypemstrmodel->getdatabyid($data["licencedet"]["firm_type_id"]);
            //calculate left Year for amendment charge//
            if ($data["application_type"]["id"] == 3) {
                $date1 = $data['curdate'];
                $date2 = $data['validitydet']["validity"];

                $ts1 = strtotime($date1);
                $ts2 = strtotime($date2);

                $year1 = date('Y', $ts1);
                $year2 = date('Y', $ts2);

                $month1 = date('m', $ts1);
                $month2 = date('m', $ts2);

                $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
                $data["Chargeforyear"] = ceil($diff / 12);
            }
            //------------------end calculate left Year for amendment charge//
            //print_r($data["validitydet"]);
            $data["ownershiptype"] = $this->tradeownershiptypemstrmodel->getdatabyid($data["licencedet"]["ownership_type_id"]);
            $data["firm_type_id"] = $data["firmtype"]["id"];
            $data["firm_type"] = $data["firmtype"]["firm_type"];
            $data["ownership_type_id"] = $data["ownershiptype"]["id"];
            $data["ownership_type"] = $data["ownershiptype"]["ownership_type"];
            $data["holding_no"] = $data["holding_no"];
            $warddet = $this->ward_model->getWardNoBywardId($data['licencedet']['ward_mstr_id']);
            $data['ward_no'] = $warddet["ward_no"];
            $data["firm_name"] = $data["licencedet"]["firm_name"];
            $data["area_in_sqft"] = (float)$data["licencedet"]["area_in_sqft"];
            $data["firm_date"] = $data["licencedet"]["establishment_date"];
            $data["address"] = $data["licencedet"]["firm_address"];
            $data["landmark"] = $data["licencedet"]["landmark"];
            $data["pin_code"] = $data["licencedet"]["pin_code"];
            $data["tobacco_status"] = 1;
            $ratedet = $this->tradelicenceratemodel->getrate($data);
            $data["rate"] = $ratedet["rate"] * $data["Chargeforyear"];
        } else {
            $data["application_type"] = $this->tradeapplicationtypemstrmodel->getdatabymd5id($apptypeid);
        }

        // $safdet = $this->model_saf_dtl->getSafDtlBySafno($data);

        if ($this->request->getMethod() == "post") {
            $this->db->transBegin();

            if ($data["application_type"]["id"] == 1) {

                $rules = [
                    'firmtype_id' => 'required',
                    'ownership_type_id' => 'required',
                    'old_ward_id' => 'required',
                    'new_ward_id' => 'required',
                    'area_in_sqft' => 'required',
                    'firm_date' => 'required',
                    'pin_code' => 'required|min_length[6]|max_length[6]',
                    'firm_name' => 'required',
                    'firmaddress' => 'required',
                    'licence_for' => 'required',

                ];
            } elseif ($data["application_type"]["id"] == 3) {
                $rules = [
                    'area_in_sqft' => 'required',

                ];
            } elseif ($data["application_type"]["id"] == 2) {
                $rules = [
                    'licence_for' => 'required',

                ];
            } else {
                $rules = [
                    'area_in_sqft' => 'required',

                ];
            }



            if (!$this->validate($rules)) {

                $data['validation'] = $this->validator;

                return view('citizen/trade/tobaccoapplylicence', $data);
            } else {
                $inputs = filterSanitizeStringtoUpper($this->request->getVar());
                $data["application_type_id"] = $data["application_type"]["id"];
                $data["licence_for_years"] = $inputs['licence_for'];
                $data["otherfirmtype"] = $inputs['firmtype_other'];
                $data["tobacco_status"] = 1;
                $data["category_type_id"] = $inputs['category_type_id'];
                $data["nature_of_bussiness"] = '185';
                $penalty = 0;
                $data["prop_dtl_id"] = 0;
                if ($data["application_type"]["id"] == 1) {

                    $data["firm_type_id"] = $inputs['firmtype_id'];
                    $data["ownership_type_id"] = $inputs['ownership_type_id'];
                    $data["ward_mstr_id"] = $inputs['old_ward_id'];
                    $data["new_ward_mstr_id"] = $inputs['new_ward_id'];

                    if ($inputs['prop_id'] <> '') {
                        $data["prop_dtl_id"] = $inputs['prop_id'];
                    }
                    $data["holding_no"] = $inputs['holding_no'];
                    $data["property_type"] = 'PROPERTY';
                    $data["firm_name"] = $inputs['firm_name'];
                    $data["area_in_sqft"] = (float)$inputs['area_in_sqft'];
                    $data["firm_date"] = $inputs['firm_date'];
                    $data["address"] = $inputs['firmaddress'];
                    $data["landmark"] = $inputs['landmark'];
                    $data["pin_code"] = $inputs['pin_code'];
                    $data["owner_business_premises"] = $inputs['owner_business_premises'];


                    $ratedet = $this->tradelicenceratemodel->getrate($data);
                    $charge = $ratedet["rate"] * $data["licence_for_years"];

                    $date1 = $data["firm_date"];
                    $date2 = date('Y-m-d');

                    $vDiff = abs(strtotime($date2) - strtotime($date1)); // here abs in case theres a mix in the dates
                    $vMonths = ceil($vDiff / (30 * 60 * 60 * 24)); // number of seconds in a month of 30 days
                    if ($vMonths > 0) {
                        $penalty = 100 + (($vMonths) * 20);
                    }

                    $total_charge = $penalty + $charge;

                    if ($data['tobacco_status'] == 1) {
                        $data['nature_of_bussiness'] = $data['nature_of_bussiness'];
                    }
                } else {
                    if ($data["application_type"]["id"] == 2) {
                        $date1 = $inputs['firm_date'];
                        $date2 = date('Y-m-d');
                        $vDiff = abs(strtotime($date2) - strtotime($date1)); // here abs in case theres a mix in the dates
                        $vMonths = ceil($vDiff / (30 * 60 * 60 * 24)); // number of seconds in a month of 30 days
                        if ($vMonths > 0) {
                            $penalty = 100 + (($vMonths) * 20);
                        }
                    }


                    $data["firm_type_id"] = $data["licencedet"]["firm_type_id"];
                    $data["ownership_type_id"] = $data["licencedet"]["ownership_type_id"];
                    $data["ward_mstr_id"] = $data["licencedet"]["ward_mstr_id"];
                    $data["new_ward_mstr_id"] = $data["licencedet"]["new_ward_mstr_id"];
                    $data["owner_business_premises"] = $data["licencedet"]["premises_owner_name"];
                    if ($data["licencedet"]["prop_dtl_id"] <> '') {
                        $data["prop_dtl_id"] = $data["licencedet"]["prop_dtl_id"];
                    }
                    $data["property_type"] = $data["licencedet"]["property_type"];
                    if ($data["application_type"]["id"] == 3) {
                        $data["area_in_sqft"] = (float)$inputs['area_in_sqft'];
                        $data["licence_for_years"] = $data['Chargeforyear'];
                    } elseif ($data["application_type"]["id"] == 4) {
                        $data["licence_for_years"] = 0;
                        $data["area_in_sqft"] = (float)$data["licencedet"]["area_in_sqft"];
                    } else {
                        $data["area_in_sqft"] = (float)$data["licencedet"]["area_in_sqft"];
                    }

                    $data["licence_id"] = $data["licencedet"]["id"];
                    $ratedet = $this->tradelicenceratemodel->getrate($data);
                    $total_charge = $ratedet["rate"] * $data["licence_for_years"];

                    $total_charge = $total_charge + $penalty;

                    if ($data['tobacco_status'] == 1) {
                        $data['nature_of_bussiness'] = $data['nature_of_bussiness'];
                    }
                }


                $data["rate_id"] = $ratedet["id"];
                $data['emp_details_id'] = $emp_id;
                $data['created_on'] = date('Y-m-d H:i:s');
                $data["apply_from"] = 'Online';

                if ($data["application_type"]["id"] == 1) {
                    $applyid = $this->TradeApplyLicenceModel->insertapply($data);
                } else {
                    $applyid = $this->TradeApplyLicenceModel->insertrenewdata($data);
                    $this->TradeApplyLicenceModel->update_status_id($applyid, $data["licence_id"]);
                }

                if ($applyid) {
                    $owner_arr = array();
                    $owner_arr['apply_licence_id'] = $applyid;
                    $owner_arr['licence_id'] = $data["licencedet"]["id"] ?? NULL;
                    $owner_arr['emp_details_id'] = $emp_id;
                    $owner_arr['created_on'] = date('Y-m-d H:i:s');

                    // $tradeitem_arr=array();
                    // $tradeitem_arr['apply_licence_id']=$applyid;
                    // $tradeitem_arr['licence_id']=$data["licence_id"];
                    // $tradeitem_arr['trade_items_id']=$inputs['tade_item'][$i]; 
                    // $tradeitem_arr['emp_details_id']=$emp_id;                                        
                    // $tradeitem_arr['created_on']=date('Y-m-d H:i:s');
                    if ($data["application_type"]["id"] == 1) {
                        if (isset($inputs['owner_name'])) {
                            for ($i = 0; $i < sizeof($inputs['owner_name']); $i++) {
                                $owner_arr['owner_name'] = $inputs['owner_name'][$i];
                                $owner_arr['guardian_name'] = $inputs['guardian_name'][$i];
                                $owner_arr['emailid'] = $inputs['emailid'][$i];
                                $owner_arr['mobile'] = $inputs['mobile_no'][$i];
                                $this->TradeFirmOwnerModel->insertdata($owner_arr);
                            }
                        }
                    } else {
                        if (isset($data["ownerdet"])) {
                            if (!empty($data["ownerdet"])) {
                                $this->TradeFirmOwnerModel->insertrenewdata($owner_arr);
                            }
                        }
                    }
                    if ($data["application_type"]["id"] <> 4) {
                        $warddet = $this->ward_model->getWardNoBywardId($data["ward_mstr_id"]);
                        $ward_no = $warddet["ward_no"];

                        $data['ward_count'] = $this->TradeApplyLicenceModel->count_ward_by_wardid($data["ward_mstr_id"]);
                        $sl_no = $data['ward_count']['ward_cnt'];


                        $sl_noo = $sl_no + 1;
                        $serial_no = str_pad($sl_noo, 4, "0", STR_PAD_LEFT);

                        $prov_no = $short_ulb_name . 'P' . $ward_no . $serial_no;
                        $this->TradeApplyLicenceModel->update_prov_no($applyid, $prov_no);
                    }

                    $app_no = "APPLIC" . $applyid . date('dmyhis');

                    $this->TradeApplyLicenceModel->update_application_no($app_no, $applyid, $payment_status = 0);



                    if ($this->db->transStatus() === FALSE) {
                        $this->db->transRollback();
                        flashToast("applylicence", "Something errordue to payment!!!");
                        if ($data["application_type_id"] == 1) {
                            return $this->response->redirect(base_url('TradeCitizen/tobaccoapplynewlicence/' . $apptypeid));
                        } else {
                            return $this->response->redirect(base_url('TradeCitizen/tobaccoapplynewlicence/' . $apptypeid . '/' . $id));
                        }
                    } else {
                        $this->db->transCommit();
                        if ($data["application_type"]["id"] <> 4) {
                            return $this->response->redirect(base_url('TradePaymentCitizen/handleRazorPayRequest/' . md5($applyid)));
                            return $this->response->redirect(base_url('TradeCitizen/doc_upload/' . md5($applyid) . '/' . md5($transaction_id)));
                        } else {
                            return $this->response->redirect(base_url('TradeCitizen/trade_licence_view/' . md5($applyid)));
                        }
                    }
                }
            }
        }
        $data["tradeitemdet"] = $this->tradeitemsmstrmodel->get_tobocoItem();
        $data['categoryTypeDetails'] = $this->TradeCategoryTypeModel->getCategoryType();

        //print_r($data);
        return view('citizen/trade/tobaccoapplylicense', $data);
    }



    public function tobaccogetcharge()
    {
        if ($this->request->getMethod() == "post") {
            $data = array();
            $inputs = arrFilterSanitizeString($this->request->getVar());
            $data['area_in_sqft'] = (float)$inputs['areasqft'];
            $data['application_type_id'] = $inputs['applytypeid'];
            $data['firm_date'] = $inputs['estdate'];
            $data['tobacco_status'] = 1;
            $data['timeforlicense'] = $inputs['licensefor'];
            $data['curdate'] = date("Y-m-d");
            $denial_amount_month = 0;
            $count = $this->tradelicenceratemodel->getrate($data);
            $rate = $count['rate'] * $data['timeforlicense'];
            $vDiff = abs(strtotime($data['curdate']) - strtotime($data['firm_date'])); // here abs in case theres a mix in the dates
            $vMonths = ceil($vDiff / (30 * 60 * 60 * 24)); // number of seconds in a month of 30 days
            if ($vMonths > 0) {
                $denial_amount_month = 100 + (($vMonths) * 20);
            }

            $total_denial_amount = $denial_amount_month + $rate;



            if ($count) {
                $response = ['response' => true, 'rate' => $rate, 'penalty' => $denial_amount_month, 'total_charge' => $total_denial_amount];
            } else {
                $response = ['response' => false];
            }
            //echo $count;
            return json_encode($response);
        }
    }

    public function fetchTradeData()
    {
        $out = ['status' => false];
        if ($this->request->getMethod() == "post") {
            $inputs = filterSanitizeStringtoUpper($this->request->getVar());
            $prop_dtl_id = $inputs['prop_dtl_id'];

            $license = $this->TradeApplyLicenceModel->getApplyLicenseByPropId($prop_dtl_id);

            for ($i = 0; $i < sizeof($license); $i++) {
                if ($license[$i]['pending_status'] != 5)
                    $license[$i]['application_status'] = $this->applicationStatus_md5(md5($license[$i]['id']));
                $yetToBeExpire = round((strtotime($license[$i]['valid_upto']) - time()) / (60 * 60 * 24));

                $license[$i]['yetToBeExpire'] = $yetToBeExpire;
                $license[$i]['application_type'] = $this->tradeapplicationtypemstrmodel->getdatabyid($license[$i]['application_type_id'])['application_type'];
                $license[$i]['apply_license_id_MD5'] = md5($license[$i]['id']);
            }

            if ($license)
                $out['status'] = true;
            $out['data'] = $license;
        }
        echo json_encode($out);
    }

    public function getDenialDetails()
    {
        $data = (array)null;
        if ($this->request->getMethod() == 'post') {
            try {
                $noticeNo = $this->request->getVar('noticeNo');
                $firm_date = $this->request->getVar('firm_date'); //firm establishment date

                $denialDetails = $this->TradeApplyDenialModel->getDenialFirmDetails(strtoupper(trim($noticeNo)), $firm_date);
                if ($denialDetails) {

                    $now = strtotime(date('Y-m-d H:i:s')); // todays date
                    $notice_date = strtotime($denialDetails['noticedate']); //notice date

                    $datediff = $now - $notice_date; //days difference in second
                    $totalDays =   ceil($datediff / (60 * 60 * 24)); // total no. of days

                    //$denialAmount=100+(($totalDays)*10);
                    $denialAmount = getDenialAmountTrade(date('Y-m-d', $notice_date), date('Y-m-d', $now));
                    $data['denialDetails'] = $denialDetails;
                    $data['denialAmount'] = $denialAmount;

                    return json_encode($data);
                } else {
                    $response = "noData";
                    return json_encode($response);
                }
            } catch (Exception $e) {
            }
        }
    }

    public function viewDenialDetails($id)
    {
        $data = (array)null;
        $data['denial_details'] = $this->TradeApplyDenialModel->denialDetailsByID($id);
        $data['noticeDetails']  = $this->TradeApplyDenialModel->getNoticeDetails($data['denial_details']['id']);
        $data['approvedDocDetails']  = $this->TradeApplyDenialModel->getapprovedDocDetails($data['denial_details']['id']);
        $data['ward']  = $this->ward_model->getdatabyid($data['denial_details']['ward_id']);
        return view('report/tradeDenialView.php', $data);
    }

    public function getDenialAmountById()
    {
        $data = (array)null;
        if ($this->request->getMethod() == 'post') {
            try {

                $denialId = $this->request->getVar('denialId');
                $noticeDetails = $this->TradeApplyDenialModel->getDenialDate($denialId);

                $now = strtotime(date('Y-m-d H:i:s')); // todays date
                $notice_date = strtotime($noticeDetails['created_on']); //notice date

                $datediff = $now - $notice_date; //days difference in second
                $totalDays =   ceil($datediff / (60 * 60 * 24)); // total no. of days

                $denialAmount = 100 + (($totalDays) * 10);
                if ($denialAmount) {
                    $response = array(
                        "denialAmount" => "Denial Amount :- " . $denialAmount,
                    );
                } else {
                    $response = array(
                        "nodata" => "nodata",
                    );
                }

                return json_encode($response);
            } catch (Exception $e) {
            }
        }
    }
    public function getcharge($args = array())
    {
        /*
            $data=array();
            $inputs = arrFilterSanitizeString($this->request->getVar());  
            $data['area_in_sqft']=$areasqft;
            $data['application_type_id']=$applytypeid;
            $data['curdate']=date("Y-m-d");
            $count = $this->tradelicenceratemodel->getrate($data);
            die();
            */
        if (!empty($args) || $this->request->getMethod() == "post") {
            $data = array();
            if (!empty($args))
                $inputs = $args;
            else
                $inputs = arrFilterSanitizeString($this->request->getVar());
            //print_var($inputs);
            $data['area_in_sqft'] = (float)$inputs['areasqft'];
            $data['application_type_id'] = $inputs['applytypeid'];
            $data['firm_date'] = $inputs['estdate'];
            $data['firm_date'] = date('Y-m-d', strtotime($data['firm_date']));

            # Date of firm establishment/expiry date


            $data['tobacco_status'] = $inputs['tobacco_status'];
            $data['timeforlicense'] = $inputs['licensefor'];
            $data['curdate'] = $inputs['curdate']??date("Y-m-d");
            $denial_amount_month = 0;
            $count = $this->tradelicenceratemodel->getrate($data);
            $rate = $count['rate'] * $data['timeforlicense'];

            $pre_app_amount = 0;
            if (isset($data['application_type_id']) && in_array($data['application_type_id'], [1, 2])) {
                $nob = array();
                $data['nature_of_business'] = null;
                if (isset($inputs['nature_of_business']))
                    $nob = explode(',', $inputs['nature_of_business']);
                if (sizeof($nob) == 1) {
                    $data['nature_of_business'] = $nob[0];
                }

                $temp = $data['firm_date'];
                $temp2 = $data['firm_date'];
                if ($data['nature_of_business'] == 198 && strtotime($temp) <= strtotime('2021-10-30')) {
                    $temp = '2021-10-30';
                    $temp2 = $temp;
                } elseif ($data['nature_of_business'] != 198 && strtotime($temp) <= strtotime('2020-01-01')) {
                    $temp = '2020-01-01';
                }
                $data['firm_date'] = $temp;
                $diff_year = date_diff(date_create($temp2), date_create($data['curdate']))->format('%R%y');
                $pre_app_amount = ($diff_year > 0 ? $diff_year : 0) * $count['rate'];
                //print_var($temp);die;
            }

            $vDiff = abs(strtotime($data['curdate']) - strtotime($data['firm_date'])); // here abs in case theres a mix in the dates
            $vMonths = ceil($vDiff / (30 * 60 * 60 * 24)); // number of seconds in a month of 30 days
            if (strtotime($data['firm_date']) >= strtotime($data['curdate'])) {
                $vMonths = 0;
                // print_var($vMonths);die;
            }
            if ($vMonths > 0) {
                $denial_amount_month = 100 + (($vMonths) * 20);
                # In case of ammendment no denial amount
                if ($data['application_type_id'] == 3)
                    $denial_amount_month = 0;
            }
            $isVerificationChage = false;
            $newCharge=0;
            $alreadyPaidAmount = 0;
            $total_denial_amount = $denial_amount_month + $rate + $pre_app_amount;
            # Check If Any cheque bounce charges
            if (isset($inputs['apply_licence_id'], $inputs['apply_licence_id'])) {
                $penalty = $this->TradeTransactionModel->getChequeBouncePenalty($inputs['apply_licence_id']);
                $denial_amount_month += $penalty;
                $total_denial_amount += $penalty;
            }


            if(isset($inputs["apply_licence_id"]) && $inputs["apply_licence_id"]){
                $md5LicenseId = $inputs["apply_licence_id"];
                if(is_numeric($inputs["apply_licence_id"])){
                    $md5LicenseId = md5($inputs["apply_licence_id"]);
                }
                $application = $this->TradeApplyLicenceModel
                    ->where("md5(id::text)",$md5LicenseId)
                    ->get()
                    ->getFirstRow('array');
                if($application && $application["is_fild_verification_charge"]=="t" && !($input["testPayentAmount"]??false)){
                    $isVerificationChage = true;
                    $activeTran= $this->TradeTransactionModel
                        ->select("sum(paid_amount) as paid_amount")
                        ->where("md5(related_id::text)",$md5LicenseId)
                        ->whereIn("status",[1,2])
                        ->get()
                        ->getFirstRow('array'); 
                    $newCharge = $total_denial_amount - $denial_amount_month;
                    $alreadyPaidAmount = $activeTran["paid_amount"];
                    $total_denial_amount = $newCharge - $alreadyPaidAmount;                 
                    $denial_amount_month =0;
                }
            }

            if ($count && $total_denial_amount>0) {
                $response = ['response' => true, 'rate' => $rate, 'penalty' => $denial_amount_month, 'total_charge' => $total_denial_amount, 'rate_id' => $count['id'], 'arear_amount' => $pre_app_amount];
                if($isVerificationChage){
                    $response["newCharge"] = $newCharge;
                    $response["alreadyPaidAmount"] = $alreadyPaidAmount;
                    $response["isVerificationChage"]=$isVerificationChage;
                }
            } else {
                $response = ['response' => false];
            }
            //echo $count;
            return json_encode($response);
        }
    }



    public function extraCharge($md5Id){
        $logData = $this->db->table("tbl_trade_update_log")->where("md5(app_id::text)",$md5Id)->get()->getFirstRow("array");
        $application = $this->TradeApplyLicenceModel
                    ->where("md5(id::text)",$md5Id)
                    ->get()
                    ->getFirstRow('array');
        $lastChage = array_reverse(json_decode($logData["app_log"],true))[0]??[];
        
        $response=[
                "status"=>false,
                "html"=>"
                    <div class='text-center'><h4>Error In Fething Amount</h4><div>
                "
            ];
        if($application["is_fild_verification_charge"]=="t"){
            $response=[
                "status"=>true,
                "html"=>"
                    <div class='text-bold'>
                        <h4>Amount : ".$application["exrta_charge"]." </h4>
                        <table class='table table-responsive table-border'>
                            <tr>
                                <td colspan='2'>Changeed Area : </td>
                                <td colspan='2'>".$lastChage["old_data"]["area_in_sqft"]." To ".$lastChage["new_data"]["area_in_sqft"]."</td>
                                
                            </tr>
                            <tr>
                                <td>New Charge : </td>
                                <td>".$lastChage["new_calculation"]["total_charge"]."</td>
                                <td>Already Paid : </td>
                                <td>".$lastChage["paid_amount"]."</td>
                            </tr>
                            <tr>
                                <td colspan='2'>Balance : </td>
                                <td colspan='2'>".($lastChage["new_calculation"]["total_charge"] - $lastChage["paid_amount"]) ."</td>                                
                            </tr>
                        <table>
                    <div>
                "
            ];
        }
        return json_encode($response);
    }


    public function getcharge_copy($args = array())
    {
        // print_var($args);die();   
        /*
        $data=array();
        $inputs = arrFilterSanitizeString($this->request->getVar());  
        $data['area_in_sqft']=$areasqft;
        $data['application_type_id']=$applytypeid;
        $data['curdate']=date("Y-m-d");
        $count = $this->tradelicenceratemodel->getrate($data);
        die();
        */
        if (!empty($args) || $this->request->getMethod() == "post") {
            $data = array();
            if (!empty($args))
                $inputs = $args;
            else
                $inputs = arrFilterSanitizeString($this->request->getVar());

            $data['area_in_sqft'] = (float)$inputs['areasqft'];
            $data['application_type_id'] = $inputs['applytypeid'];
            $data['firm_date'] = $inputs['estdate'];

            $nob = explode(',', $inputs['nature_of_business']);
            if (sizeof($nob) <= 1) {
                $data['nature_of_business'] = $nob[0];
            } else {
                $data['nature_of_business'] = "multiple_values";
            }

            # Date of firm establishment/expiry date

            $data['tobacco_status'] = $inputs['tobacco_status'];
            $data['timeforlicense'] = $inputs['licensefor'];
            $data['curdate'] = date("Y-m-d");
            $denial_amount_month = 0;
            $count = $this->tradelicenceratemodel->getrate($data);
            $rate = $count['rate'] * $data['timeforlicense'];

            $pre_app_amount = 0;
            $vDiff = 0;

            if (isset($inputs['apply_licence_id']) && in_array($inputs['applytypeid'], [2])) {
                $validUpto = $this->TradeApplyLicenceModel->validUpto($inputs['apply_licence_id']);
                $data['firm_date'] = (strtotime($validUpto['valid_upto']) > strtotime('2020-01-01')) ? $validUpto['valid_upto'] : '2020-01-01';
                $diff_year = date_diff(date_create($validUpto['valid_upto']), date_create(date('Y-m-d')))->format('%R%y');
                $pre_app_amount = ($diff_year > 0 ? $diff_year : 0) * $count['rate'];
            }
            // if application type id is 1 (start)
            if ($inputs['applytypeid'] == 1) {
                // echo "dddd";


                if (strtotime($data['firm_date']) < strtotime('2020-01-01')) {

                    $data['firm_date'] = (strtotime($inputs['estdate']) > strtotime('2020-01-01')) ? $inputs['estdate'] : '2020-01-01';

                    $vDiff = abs(strtotime($data['curdate']) - strtotime($data['firm_date']));
                    $diff_year = date_diff(date_create($inputs['estdate']), date_create(date('Y-m-d')))->format('%R%y');
                    $pre_app_amount = ($diff_year > 0 ? $diff_year : 0) * $count['rate'];
                }
                if (strtotime($data['firm_date']) > strtotime('2020-01-01')) {
                    $vDiff = abs(strtotime($data['curdate']) - strtotime($data['firm_date']));
                    $diff_year = date_diff(date_create($inputs['estdate']), date_create(date('Y-m-d')))->format('%R%y');
                    $pre_app_amount = ($diff_year > 0 ? $diff_year : 0) * $count['rate'];
                }
                // $validUpto=$this->TradeApplyLicenceModel->validUpto($inputs['apply_licence_id']);
                // $data['firm_date'] = (strtotime($validUpto['valid_upto'])>strtotime('2020-01-01'))?$validUpto['valid_upto']:'2020-01-01';
                // $diff_year = date_diff(date_create($validUpto['valid_upto']),date_create(date('Y-m-d')))->format('%R%y');
                // $pre_app_amount = ($diff_year>0?$diff_year:0)*$count['rate'];
                if ($data['nature_of_business'] == 198) {
                    // echo "199999998";
                    $data['firm_date'] = (strtotime($inputs['estdate']) > strtotime('2021-11-01')) ? $inputs['estdate'] : '2021-11-01';
                    $vDiff = abs(strtotime($data['curdate']) - strtotime($data['firm_date']));
                    $data['firm_date'] . " ";
                    $diff_year = date_diff(date_create($data['firm_date']), date_create(date('Y-m-d')))->format('%R%y');
                    $pre_app_amount = ($diff_year > 0 ? $diff_year : 0) * $count['rate'];
                }
                // if($data['nature_of_business']=='multiple_values'){
                //     $data['firm_date'] = (strtotime($inputs['estdate'])>strtotime('2020-01-01'))?$inputs['estdate']:'2020-01-01';
                //     $vDiff = abs(strtotime($data['curdate']) - strtotime($data['firm_date'])); // here abs in case theres a mix in the dates
                //     $diff_year = date_diff(date_create($data['firm_date']),date_create(date('Y-m-d')))->format('%R%y');
                //     $pre_app_amount = ($diff_year>0?$diff_year:0)*$count['rate'];

                // }
            }

            // if application type id is 1 (end)

            $vMonths = ceil($vDiff / (30 * 60 * 60 * 24)); // number of seconds in a month of 30 days
            if ($vMonths > 0) {
                $denial_amount_month = 100 + (($vMonths) * 20);
                # In case of ammendment no denial amount
                if ($data['application_type_id'] == 3)
                    $denial_amount_month = 0;
            }
            $total_denial_amount = $denial_amount_month + $rate + $pre_app_amount;

            # Check If Any cheque bounce charges
            if (isset($inputs['apply_licence_id'], $inputs['apply_licence_id'])) {
                $penalty = $this->TradeTransactionModel->getChequeBouncePenalty($inputs['apply_licence_id']);
                $denial_amount_month += $penalty;
                $total_denial_amount += $penalty;
            }

            if ($count) {
                $response = ['response' => true, 'rate' => $rate, 'penalty' => $denial_amount_month, 'total_charge' => $total_denial_amount, 'rate_id' => $count['id']];
            } else {
                $response = ['response' => false];
            }
            //echo $count;
            return json_encode($response);
        }
    }

    public function re_day_diff($from_date, $licence_for_year, $post_from = null)
    {
        return re_day_diff($from_date, $licence_for_year, $post_from);
        // $valid_from = $from_date;
        // $valid_upto =date("Y-m-d", strtotime("+$licence_for_year years", strtotime($valid_from)));
        // $diff_day = (int)date_diff(date_create(date('Y-m-d')),date_create($valid_upto))->format("%R%a");

        // //$valid_upto = date("Y-m-d",($diff_day)); 
        // $temp = [
        //     'diff_day'=>$diff_day,
        //     'valid_upto'=>$valid_upto,
        //     'valid_from'=>$valid_from
        // ];

        // if(($this->request->getMethod()=='post') and $post_from)
        // {
        //     return json_encode($temp);
        // }
        // return $temp;       
    }

    public function dinial_charge($notice_date = null, $current_date = null, $call_from = null)
    {
        //print_var($this->request->getMethod());
        if ($this->request->getMethod() == "post" && $call_from == null) {
            $inputs = arrFilterSanitizeString($this->request->getVar());
            $notice_date =  isset($inputs['notice_date']) ? date('Y-m-d', strtotime($inputs['notice_date'])) : date('Y-m-d');
            $current_date = isset($inputs['current_date']) ? date('Y-m-d', strtotime($inputs['current_date'])) : date('Y-m-d');
            $amount = getDenialAmountTrade($notice_date, $current_date);
            return json_encode(['amount' => $amount, 'response' => true]);
        }
        return $amount = getDenialAmountTrade($notice_date, $current_date);
    }

    public function another_controler_dinial_charge($notice_date = null, $current_date = null)
    {

        return $amount = getDenialAmountTrade($notice_date, $current_date);
    }
    public function check_bounce_payment($md5_licence_id = null)
    {
        $licence = $this->TradeApplyLicenceModel->apply_licence_md5($md5_licence_id);
        //$data['license']=$licence;
        if ($this->request->getmethod() == 'post') {
            $inputs = arrFilterSanitizeString($this->request->getVar());

            if (!empty($licence) && in_array($licence['application_type_id'], [1, 2, 3]) && $licence['payment_status'] == 0) {
                $ss = [
                    'ch_bounc' => [
                        'licence_id' => $licence['id'],
                        'for_year' => $this->request->getVar('licence_for'),
                    ]
                ];

                //session()->set($ss);
                $sql_update = " update tbl_apply_licence set licence_for_years=$inputs[licence_for] where id = $licence[id]";
                $this->TradeTransactionModel->row_query($sql_update);
                return $this->response->redirect(base_url('TradePaymentCitizen/handleRazorPayRequest/' . md5($licence['id'])));
            } else {
                return $this->response->redirect(base_url('TradeCitizen/trade_licence_view/' . $md5_licence_id));
                //return $this->response->redirect(base_url('TradeCitizen/trade_licence_view/'.md5(($licence['id'])));
            }
        }
    }
}
