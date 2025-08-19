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

use App\Models\Citizensw_trade_model;
use App\Controllers\TradeCitizen;
use Exception;

class TradeCitizenSW extends HomeController
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
    protected $TradeCitizenController;


    public function __construct()
    {
        $session = session();
        $ulb_details = $session->get('ulb_dtl');
        //print_r($ulb_details);
        $this->ulb_id = $ulb_details['ulb_mstr_id'];

        $emp_details = $session->get('emp_details');
        $this->emp_id = $emp_details['id'];


        parent::__construct();
        helper(['db_helper', 'qr_code_generator_helper', 'form_helper', 'sms_helper']);
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
        $this->TradeCitizenController= new TradeCitizen($this->db);
    }

    public function __destruct()
    {
        if($this->db)
            $this->db->close();
        if($this->property_db)
            $this->property_db->close();
        if($this->dbSystem)
            $this->dbSystem->close();
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
                    $where = "ward_mstr_id = '" . $data['ward_mstr_id'] . "' and update_status=0 and (license_no = '" . $data['keyword'] . "' or firm_name = '" . $data['keyword'] . "' or application_no = '" . $data['keyword'] . "')";
                } elseif ($data["ward_mstr_id"] != "") {
                    $where = "ward_mstr_id = '" . $data['ward_mstr_id'] . "' and update_status=0";
                } else {
                    $where = "update_status=0 and (license_no = '" . $data['keyword'] . "' or firm_name = '" . $data['keyword'] . "' or application_no = '" . $data['keyword'] . "')";
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

    public function applynewlicence($apptypeid = null, $id = null)
    {
        $data = array();
        $session = session();       
        $get_ulb_id = $session->get('ulb_dtl');
        //print_var($get_ulb_id);die;
        if(empty($get_ulb_id) || $session->get('apply_from')!=='sws')
        {
            flashToast("applylicence", "Connection !!!");
            // echo '<script> window.location = "https://advantage.jharkhand.gov.in/SingleWindow/DepartmentForms/status"; </script>'; 
            return $this->response->redirect(base_url('Home/'));
        }

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

        if ($apptypeid <> null and $id <> null) 
        {
            $data["application_type"] = $this->tradeapplicationtypemstrmodel->getdatabymd5id($apptypeid);
            $data["application_type_id"] = $data["application_type"]["id"];
            $data["licencedet"] = $this->TradeApplyLicenceModel->apply_licence_md5($id);
            $data["tobacco_status"] = $data["licencedet"]["tobacco_status"];

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
        //print_var(session()->get());
        if($data["application_type"]['id']==1)
        {
            $data['firm_name'] = $_SESSION['post_incrept']['industry_undertaking'];
            $data['area_in_sqft']=$_SESSION['post_incrept']['industry_area'];
            $data['pin_code']=$_SESSION['post_incrept']['industry_pin_code'];

            $data['woner_name'] = $_SESSION['post_decript']['firstName'].' ' .$_SESSION['post_decript']['lastName'];
            $data['email']=$_SESSION['post_decript']['email'];
            $data['mobile_no']=$_SESSION['post_decript']['mobile'];
        }
        if ($this->request->getMethod() == "post") 
        {

            $this->db->transBegin();
            $sw_id = 0;
            //print_var($_SESSION);die;
            $data['apply_from'] = 'sws';
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
            
            if ($data["application_type"]["id"] == 1) 
            { 
               
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

                if ($inputs['prop_id'] <> '') 
                {
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

                    $rate_data = $this->TradeCitizenController->getcharge($args);
                    $rate_data = json_decode(json_encode(json_decode($rate_data)), true);
                }

                // print_var($args);die();

                $data["rate_id"] = $rate_data["rate_id"];
                $data['emp_details_id'] = $emp_id;
                $data['created_on'] = date('Y-m-d H:i:s');
                $applyid = $this->TradeApplyLicenceModel->insertapply($data);                
                
                $sw = [];
                $sw['apply_license_id']=$applyid ;
                $sw['cust_id']=$_SESSION['custId'] ;
                $sw['department_id']=$_SESSION['departmentId'] ;
                $sw['session_id']=$_SESSION['post_incrept']['sessionId'] ;
                $sw['caf_no']=$_SESSION['caf_unique_no'] ;
                $sw['service_id']=$_SESSION['serviceId'] ;
                $sw['application_statge']= 0 ;
                $sw['amount'] = 0;
                $sw['sw_status']=0 ;
                $sw['arrear_amount']=0 ;
                $sw['denial_amount']=0 ;
                $sw['rejection_fine']=0 ;
                $sw['total_amount']=0 ;
                $sw['ceation_on']=date('Y-m-d') ;
                $sw_id = $this->Citizensw_trade_model->InsertData($sw);
                

                $owner_arr = array();
                $owner_arr['apply_licence_id'] = $applyid;
                //$owner_arr['licence_id']=$data["licencedet"]["id"];
                $owner_arr['emp_details_id'] = $emp_id;
                $owner_arr['created_on'] = date('Y-m-d H:i:s');


                if (isset($inputs['owner_name'])) 
                {
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
                if ($noticeDetails) 
                {
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

                if ($denialAmount > 0) 
                {
                    $denial = array();  // denial insert
                    $denial['transaction_id'] = $transaction_id;
                    $denial['head_name'] = 'Denial Apply  ';
                    $denial['amount'] = $denialAmount;
                    $denial['value_add_minus'] = 'Add';
                    $denial['created_on'] = date('Y-m-d H:i:s');
                    //$this->model_trade_transaction_fine_rebet_details->fine_rebet_details($denial);
                }


                $payment_status = 0;
                if ($inputs['payment_mode'] != 'CASH' && $inputs['payment_mode'] != 'Online') 
                {
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
                $app_no = "APPLIC" . $applyid . date('dmyhis');
                $this->TradeApplyLicenceModel->update_application_no($app_no, $applyid, $payment_status);
                
                
            }

            # Renewal
            elseif ($data["application_type"]["id"] == 2) 
            {
                if (!$this->validate($rules)) {
                    $data['validation'] = $this->validator;
                    return view('citizen/trade/applylicence', $data);
                }

                $inputs = filterSanitizeStringtoUpper($this->request->getVar());

                $inputs['payment_mode'] = 'Online';
                $data["otherfirmtype"] = $inputs['firmtype_other'];
                $data["licence_for_years"] = $inputs["licence_for"];
                $data["valid_from"] = $inputs["firm_date"];
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
                
                # Calculating Rate
                {
                    $args['areasqft'] = $data["licencedet"]["area_in_sqft"];
                    $args['applytypeid'] = $data["application_type"]["id"];
                    $args['estdate'] = $inputs["firm_date"];
                    $args['tobacco_status'] = $data["licencedet"]["tobacco_status"];
                    $args['licensefor'] = $inputs["licence_for"];
                    $args['nature_of_business'] = $data['nature_of_bussiness'];
                    $rate_data =$this->TradeCitizenController->getcharge($args);
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
                $data['valid_upto'] = $vali_upto;

                $applyid = $this->TradeApplyLicenceModel->insertapply($data);
                $this->TradeApplyLicenceModel->update_status_id($applyid, $data["licencedet"]["id"]);

                $sw = [];
                $sw['apply_license_id']=$applyid ;
                $sw['cust_id']=$_SESSION['custId'] ;
                $sw['department_id']=$_SESSION['departmentId'] ;
                $sw['session_id']=$_SESSION['post_incrept']['sessionId'] ;
                $sw['caf_no']=$_SESSION['caf_unique_no'] ;
                $sw['service_id']=$_SESSION['serviceId'] ;
                $sw['application_statge']= 0 ;
                $sw['amount'] = 0;
                $sw['sw_status']=0 ;
                $sw['arrear_amount']=0 ;
                $sw['denial_amount']=0 ;
                $sw['rejection_fine']=0 ;
                $sw['total_amount']=0 ;
                $sw['ceation_on']=date('Y-m-d') ;
                $sw_id = $this->Citizensw_trade_model->InsertData($sw);

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
                if ($inputs['payment_mode'] != 'CASH' && $inputs['payment_mode'] != 'Online') 
                {
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
                $app_no = "APPLIC" . $applyid . date('dmyhis');
                $this->TradeApplyLicenceModel->update_application_no($app_no, $applyid, $payment_status);

                
            }

            # 3 Amendment
            elseif ($data["application_type"]["id"] == 3) 
            {
                
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
                    $rate_data=$this->TradeCitizenController->getcharge($args);
                    $rate_data = json_decode(json_encode(json_decode($rate_data)), true);
                }

                $data["rate_id"] = $rate_data["rate_id"];
                $data['emp_details_id'] = $emp_id;
                $data['created_on'] = date('Y-m-d H:i:s');

                $applyid = $this->TradeApplyLicenceModel->insertapply($data);
                $this->TradeApplyLicenceModel->update_status_id($applyid, $data["licencedet"]["id"]);

                $sw = [];
                $sw['apply_license_id']=$applyid ;
                $sw['cust_id']=$_SESSION['custId'] ;
                $sw['department_id']=$_SESSION['departmentId'] ;
                $sw['session_id']=$_SESSION['post_incrept']['sessionId'] ;
                $sw['caf_no']=$_SESSION['caf_unique_no'] ;
                $sw['service_id']=$_SESSION['serviceId'] ;
                $sw['application_statge']= 0 ;
                $sw['amount'] = 0;
                $sw['sw_status']=0 ;
                $sw['arrear_amount']=0 ;
                $sw['denial_amount']=0 ;
                $sw['rejection_fine']=0 ;
                $sw['total_amount']=0 ;
                $sw['ceation_on']=date('Y-m-d') ;
                $sw_id = $this->Citizensw_trade_model->InsertData($sw);

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
                $app_no = "APPLIC" . $applyid . date('dmyhis');
                $this->TradeApplyLicenceModel->update_application_no($app_no, $applyid, $payment_status);

                
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

                $sw = [];
                $sw['apply_license_id']=$applyid ;
                $sw['cust_id']=$_SESSION['custId'] ;
                $sw['department_id']=$_SESSION['departmentId'] ;
                $sw['session_id']=$_SESSION['post_incrept']['sessionId'] ;
                $sw['caf_no']=$_SESSION['caf_unique_no'] ;
                $sw['service_id']=$_SESSION['serviceId'] ;
                $sw['application_statge']= 1 ;
                $sw['amount'] = 0;
                $sw['sw_status']=1 ;
                $sw['arrear_amount']=0 ;
                $sw['denial_amount']=0 ;
                $sw['rejection_fine']=0 ;
                $sw['total_amount']=0 ;
                $sw['ceation_on']=date('Y-m-d') ;
                $sw_id = $this->Citizensw_trade_model->InsertData($sw);

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

                $app_no = "APPLIC" . $applyid . date('dmyhis');
                $this->TradeApplyLicenceModel->update_application_no($app_no, $applyid, $data["payment_status"]);
                
            }
            // print_var($data["application_type"]["id"]);return;
            $push_sw=array();
            $path = '';//base_url('citizenPaymentReceipt/view_trade_transaction_receipt/' . $this->ulb_id . '/' . md5($data["license"]['id']) . '/' . md5($transaction_id));
            $push_sw['application_stage']=$data["application_type"]["id"] != 4 ? 1 : 11 ;
            $push_sw['status']='Application submitted ';
            $push_sw['acknowledgment_no']=$app_no;
            $push_sw['service_type_id']=$sw['service_id'];
            $push_sw['caf_unique_no']=$sw['caf_no'];
            $push_sw['department_id']=$sw['department_id'];
            $push_sw['Swsregid']=$sw['cust_id'];
            $push_sw['payable_amount ']=0;
            $push_sw['payment_validity']='';
            $push_sw['payment_other_details']='';
            $push_sw['certificate_url']=$path;
            $push_sw['approval_date']='';
            $push_sw['expire_date']='';
            $push_sw['licence_no']='';
            $push_sw['certificate_no']='';
            $push_sw['customer_id']=$sw['cust_id'];
            $post_url = getenv('single_indow_push_url');
            $http = getenv('single_indow_push_http');
            $resp = httpPostJson($post_url,$push_sw,$http);            

            $respons_data=[];
            $respons_data['apply_license_id']=$applyid;
            $respons_data['response_msg']= json_encode(['url'=>$http.'/'.$post_url,
                                                        'data'=>$push_sw]);
            $respons_data['tbl_single_window_id']=$sw_id??null;
            $respons_data['emp_id']=$emp_id;
            $respons_data['response_status']=json_encode($resp);
            $this->Citizensw_trade_model->insertResponse($respons_data);
           
            //print_var($resp);die;
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
                if ($data["application_type"]["id"] <> 4) 
                {
                    return $this->response->redirect(base_url('TradePaymentCitizen/handleRazorPayRequest/' . md5($applyid)));
                } elseif ($data["application_type"]["id"] == 4) 
                {
                    return $this->response->redirect(base_url('TradeCitizen/doc_upload/' . md5($applyid)));
                } 
                else 
                {
                    return $this->response->redirect(base_url('TradeCitizen/trade_licence_view/' . md5($applyid)));
                }
            }
        }
        $data["tradeitemlist"] = $this->tradeitemsmstrmodel->gettradeitemsList();
        $data['categoryTypeDetails'] = $this->TradeCategoryTypeModel->getCategoryType();

        // print_var($data);die;
        return view('citizen/trade/applylicenceSW', $data);
    }
    
}
