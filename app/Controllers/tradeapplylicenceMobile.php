<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\model_ulb_mstr;
use App\Models\model_view_ward_permission;
use App\Models\TradeFirmTypeMstrModel;
use App\Models\TradeApplicationTypeMstrModel;
use App\Models\TradeOwnershipTypeMstrModel;
use App\Models\TradeItemsMstrModel;
use App\Models\TradeLicenceRateModel;
use App\Models\TradeViewLicenceRateModel;
use App\Models\StateModel;
use App\Models\DistrictModel;

use App\Models\PropertyModel;
use App\Models\TradeApplyLicenceModel;
use App\Models\model_view_trade_licence;
use App\Models\TradeFirmOwnerModel;
use App\Models\TradeTradeItemsModel;
use App\Models\TradeTransactionModel;

use App\Models\model_saf_dtl;
use App\Models\model_saf_owner_detail;
use App\Models\model_prop_dtl;
use App\Models\model_prop_owner_detail;
use App\Models\TradeChequeDtlModel;
use App\Models\model_trade_level_pending_dtl;
use App\Models\TradeViewApplyLicenceOwnerModel;
use App\Models\model_trade_licence_owner_name;
use App\Models\model_trade_view_licence_trade_items;
use App\Models\model_trade_licence_validity;
use App\Models\model_trade_document;
use App\Models\model_trade_provisional_licence;
use App\Models\model_emp_details;
use App\Models\model_trade_transaction_fine_rebet_details;
use App\Controllers\TradeApplyLicence;
use App\Models\model_visiting_dtl;
use App\Models\TradeApplyDenialModel;

class tradeapplylicenceMobile extends MobiController
{
    protected $db;
    protected $dbSystem;
    protected $property_db;
    protected $model_ward_mstr;
    protected $model_ulb_mstr;
    protected $model_view_ward_permission;
    protected $statemodel;
    protected $tradefirmtypemstrmodel;
    protected $tradeapplicationtypemstrmodel;
    protected $tradeownershiptypemstrmodel;
    protected $tradeitemsmstrmodel;
    protected $tradelicenceratemodel;
    protected $tradeviewlicenceratemodel;
    protected $districtmodel;

    protected $TradeApplyLicenceModel;
    protected $TradeFirmOwnerModel;
    protected $TradeTradeItemsModel;
    protected $TradeTransactionModel;
    protected $model_view_trade_licence;

    protected $model_saf_dtl;
    protected $model_saf_owner_detail;
    protected $model_prop_dtl;
    protected $model_prop_owner_detail;
    protected $TradeChequeDtlModel;
    protected $model_trade_level_pending_dtl;

    protected $TradeViewApplyLicenceOwnerModel;
    protected $model_trade_licence_owner_name;
    protected $model_trade_view_licence_trade_items;
    protected $model_trade_licence_validity;
    protected $model_trade_document;
    protected $model_trade_provisional_licence;
    protected $model_emp_details;
    protected $model_trade_transaction_fine_rebet_details;
    protected $TradeApplyDenialModel;
    protected $model_visiting_dtl;

    public function __construct()
    {


        parent::__construct();
        helper(['db_helper', 'qr_code_generator_helper', 'form_helper']);
        if ($db_name = dbConfig("trade")) {
            //echo $db_name;
            $this->db = db_connect($db_name);
        }
        if ($db_name = dbConfig("property")) {
            $this->property_db = db_connect($db_name);
        }
        if ($db_system = dbSystem()) {
            $this->dbSystem = db_connect($db_system);
        }
        $this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_view_ward_permission = new model_view_ward_permission($this->dbSystem);
        $this->statemodel = new statemodel($this->dbSystem);
        $this->districtmodel = new districtmodel($this->dbSystem);
        $this->model_emp_details = new model_emp_details($this->dbSystem);
        $this->property_model = new PropertyModel($this->property_db);
        $this->tradefirmtypemstrmodel = new tradefirmtypemstrmodel($this->db);
        $this->tradeapplicationtypemstrmodel =  new tradeapplicationtypemstrmodel($this->db);
        $this->tradeownershiptypemstrmodel =  new tradeownershiptypemstrmodel($this->db);
        $this->tradeitemsmstrmodel =  new tradeitemsmstrmodel($this->db);
        $this->tradelicenceratemodel =  new tradelicenceratemodel($this->db);
        $this->tradeviewlicenceratemodel =  new tradeviewlicenceratemodel($this->db);
        $this->TradeApplyLicenceModel = new TradeApplyLicenceModel($this->db);
        $this->TradeFirmOwnerModel = new TradeFirmOwnerModel($this->db);
        $this->TradeTradeItemsModel = new TradeTradeItemsModel($this->db);
        $this->TradeTransactionModel = new TradeTransactionModel($this->db);

        $this->model_saf_dtl = new model_saf_dtl($this->property_db);
        $this->model_saf_owner_detail = new model_saf_owner_detail($this->property_db);
        $this->model_prop_dtl = new model_prop_dtl($this->property_db);
        $this->model_prop_owner_detail = new model_prop_owner_detail($this->property_db);
        $this->TradeChequeDtlModel = new TradeChequeDtlModel($this->db);
        $this->model_trade_level_pending_dtl = new model_trade_level_pending_dtl($this->db);
        //$this->TradeViewLicenceOwnerrModel = new tradeviewlicenceownerrmodel($this->db);
        //$this->model_trade_licence = new model_trade_licence($this->db);
        $this->model_view_trade_licence = new model_view_trade_licence($this->db);
        $this->TradeViewApplyLicenceOwnerModel = new TradeViewApplyLicenceOwnerModel($this->db);
        $this->model_trade_licence_owner_name = new model_trade_licence_owner_name($this->db);
        $this->model_trade_view_licence_trade_items = new model_trade_view_licence_trade_items($this->db);
        $this->model_trade_licence_validity = new model_trade_licence_validity($this->db);
        $this->model_trade_document = new model_trade_document($this->db);
        //$this->model_trade_provisional_licence = new model_trade_provisional_licence($this->db);
        $this->model_trade_transaction_fine_rebet_details = new model_trade_transaction_fine_rebet_details($this->db);
        $this->TradeApplyLicenceController = new TradeApplyLicence($this->db);
        $this->TradeApplyDenialModel = new TradeApplyDenialModel($this->db);
        $this->model_visiting_dtl = new model_visiting_dtl($this->dbSystem);
    }

    public function applynewlicences()
    {
        try {
            $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
            $data = (array)null;
            if ($this->request->getMethod() == 'post') {
                $inputs = filterSanitizeStringtoUpper($this->request->getVar());
                if ($inputs['keyword'] <> "") {
                    $data['keyword'] = $inputs['keyword'];
                    $data['licencedet'] = $this->model_view_trade_licence->getlicencedatabykeyword($data);
                } else {
                    $data['from_date'] = $inputs['from_date'];
                    $data['to_date'] = $inputs['to_date'];
                    $data['licencedet'] = $this->model_view_trade_licence->getlicencedatabydate($data);
                }
            }
            return view('mobile/trade/applyNewLicence', $data);
        } catch (Exception $e) {
        }
    }


    public function ApplyNewLicenseMobi($apptypeid = null, $id = null)
    {
        $data = array();
        $session = session();
        $get_ulb_id = $session->get('ulb_dtl');

        $get_emp_details = $session->get('emp_details');
        $data['ulb_mstr_name'] = $this->model_ulb_mstr->getulb_list($get_ulb_id["ulb_mstr_id"]);
        $short_ulb_name = $data["ulb_mstr_name"]["short_ulb_name"];
        $emp_id = $get_emp_details['id'];
        $ip_address = $get_emp_details['ip_address'];
        $data = (array)null;
        $data['curdate'] = date("Y-m-d");
        $data["apptypeid"] = $apptypeid;
        $data["id"] = $id;
        $data["statelist"] = $this->statemodel->getstateList();
        $data["idprooflist"] = $this->model_trade_document->getIdPoorfDocumentList();
        $data['ward_list'] = $this->model_ward_mstr->getWardList($get_ulb_id);
        if($get_emp_details["user_type_mstr_id"]==5)        
        {
            $ward_list = $this->model_view_ward_permission->getPermittedWard($emp_id);
            $data['ward_list'] =  array_map(function($val){
                return["id"=>$val["ward_mstr_id"],"ward_no"=>$val['ward_no']];
            },$ward_list);
          
        }
        $data["application_type"] = $this->tradeapplicationtypemstrmodel->getdatabymd5id($apptypeid);
        //print_var($data["application_type"]);die(); 
        $data["firmtypelist"] = $this->tradefirmtypemstrmodel->getFirmTypeList();
        $data["ownershiptypelist"] = $this->tradeownershiptypemstrmodel->getownershipTypeList();
        // $data["tradeitemlist"] = $this->tradeitemsmstrmodel->gettradeitemsList();

        if ($apptypeid <> null and $id <> null) 
        {
            $data["application_type"] = $this->tradeapplicationtypemstrmodel->getdatabymd5id($apptypeid);
            $data["application_type_id"] = $data["application_type"]["id"];
            $data["licencedet"] = $this->TradeApplyLicenceModel->apply_licence_md5($id);
            $data["tobacco_status"] = $data["licencedet"]["tobacco_status"];
            $data['ward_no'] = $this->model_ward_mstr->getWardNoById($data["licencedet"]);
            $data['new_ward_no'] = $this->model_ward_mstr->getWardNoBywardId($data["licencedet"]["new_ward_mstr_id"]);
            $data["ownerdet"] = $this->TradeFirmOwnerModel->getdatabyid_md5($id);
            //$data["tradeitemdet"] = $this->model_trade_view_licence_trade_items->get_details($id);            

            $data["firmtype"] = $this->tradefirmtypemstrmodel->getdatabyid($data["licencedet"]["firm_type_id"]);

            $data["ownershiptype"] = $this->tradeownershiptypemstrmodel->getdatabyid($data["licencedet"]["ownership_type_id"]);
            $data["firm_type_id"] = $data["firmtype"]["id"];
            $data["firm_type"] = $data["firmtype"]["firm_type"];
            $data["ownership_type_id"] = $data["ownershiptype"]["id"];
            $data["ownership_type"] = $data["ownershiptype"]["ownership_type"];
            $data["holding_no"] = $data['licencedet']["holding_no"];
            $warddet = $this->model_ward_mstr->getWardNoBywardId($data['licencedet']['ward_mstr_id']);
            $data['ward_no'] = $warddet["ward_no"];
            $data['ward_id']=$data["licencedet"]['ward_mstr_id'];
            $data["firm_name"] = $data["licencedet"]["firm_name"];
            $data["area_in_sqft"] = (float)$data["licencedet"]["area_in_sqft"];
            $data["firm_date"] = $data["licencedet"]["establishment_date"];
            $data["address"] = $data["licencedet"]["address"];
            $data["landmark"] = $data["licencedet"]["landmark"];
            $data["pin_code"] = $data["licencedet"]["pin_code"];
            $data['apply_licence_id'] = $data["licencedet"]["id"];
            $data["tradeitemdet"] = explode(',', $data["licencedet"]["nature_of_bussiness"]);
            $data['nature_of_bussiness'] = $data["licencedet"]["nature_of_bussiness"];
        } 
        else 
        {
            $data["application_type"] = $this->tradeapplicationtypemstrmodel->getdatabymd5id($apptypeid);
        }

        if ($this->request->getMethod() == "post") 
        {
            $this->db->transBegin();
            # New License
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
                    'payment_mode' => 'required',
                    'apply_from' => 'required',
                    'brife_desp_firm' => 'required',
                ];
                if (!$this->validate($rules)) {
                    $data['validation'] = $this->validator;
                    return view('trade/Connection/applylicence', $data);
                }
                $inputs = filterSanitizeStringtoUpper($this->request->getVar());
                $data["application_type_id"] = $data["application_type"]["id"];
                $data["firm_type_id"] = $inputs['firmtype_id'];
                $data["otherfirmtype"] = $inputs['firmtype_other'];
                $data['nature_of_bussiness'] = NULL;
                $data['tobacco_status'] = null;
                $data["ownership_type_id"] = $inputs['ownership_type_id'];
                $data["ward_mstr_id"] = $inputs['old_ward_id'];
                $data["new_ward_mstr_id"] = $inputs['new_ward_id'];
                if ($inputs['prop_id'] <> '') {
                    $data["prop_dtl_id"] = $inputs['prop_id'];
                }
                if ($data['tobacco_status'] == 1) {
                    $data['nature_of_bussiness'] = $data['nature_of_bussiness'];
                } else {
                    $data['tade_item'] = $this->request->getPost('tade_item');
                    $data['nature_of_bussiness'] = is_array($data['tade_item']) ? implode(",", $data['tade_item']) : Null;
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
                $data["apply_from"] = 'TC';
                $data["brife_desp_firm"] = $inputs['brife_desp_firm'];
                # Calculating rate
                {
                    $args['areasqft'] = (float)$inputs['area_in_sqft'];
                    $args['applytypeid'] = $data["application_type"]["id"];
                    $args['estdate'] = $inputs["firm_date"];
                    $args['tobacco_status'] = $inputs['tobacco_status'];
                    $args['licensefor'] = $inputs["licence_for"];
                    $args['nature_of_business'] = $data['nature_of_bussiness'];
                    $rate_data = $this->TradeApplyLicenceController->getcharge($args);
                    $rate_data = json_decode(json_encode(json_decode($rate_data)), true);
                    // print_var($rate_data);die();
                }
                $data["rate_id"] = $rate_data["rate_id"];
                $data['emp_details_id'] = $emp_id;
                $data['created_on'] = date('Y-m-d H:i:s');
                $data['valid_from'] = date('Y-m-d');
                $vali_upto = date('Y-m-d', strtotime(date('Y-m-d') . "+" . $data["licence_for_years"] . " years"));
                $data['valid_upto'] = $vali_upto;
                $applyid = $this->TradeApplyLicenceModel->insertapply($data);

                // print_var($applyid);die();

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
                $noticeDetails = $this->TradeApplyDenialModel->getDenialDate($denialId);
                if ($noticeDetails) {
                    $now = strtotime(date('Y-m-d H:i:s')); // todays date
                    $notice_date = strtotime($noticeDetails['created_on']); //notice date
                    $datediff = $now - $notice_date; //days difference in second
                    $totalDays =   ceil($datediff / (60 * 60 * 24)); // total no. of days
                    // $denialAmount=100+(($totalDays)*10);
                    $denialAmount = getDenialAmountTrade($notice_date, $now);
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
                $transact_arr['payment_mode'] = $inputs['payment_mode'];
                $transact_arr['paid_amount'] = $totalCharge;

                $transact_arr['penalty'] = $rate_data['penalty'] + $denialAmount;
                if ($inputs['payment_mode'] != 'CASH') {
                    $transact_arr['status'] = 2;
                }
                $transact_arr['emp_details_id'] = $emp_id;
                $transact_arr['created_on'] = date('Y-m-d H:i:s');
                $transact_arr['ip_address'] = '';

                $transaction_id = $this->TradeTransactionModel->insertPayment($transact_arr);
                //echo $transaction_id;
                $trafinerebate = array();
                $trafinerebate['transaction_id'] = $transaction_id;
                $trafinerebate['head_name'] = 'Delay Apply License';
                $trafinerebate['amount'] = $rate_data['penalty'];
                $trafinerebate['value_add_minus'] = 'Add';
                $trafinerebate['created_on'] = date('Y-m-d H:i:s');
                $this->model_trade_transaction_fine_rebet_details->fine_rebet_details($trafinerebate);

                if ($denialAmount > 0) {
                    $denial = array();  // denial insert
                    $denial['transaction_id'] = $transaction_id;
                    $denial['head_name'] = 'Denial Apply  ';
                    $denial['amount'] = $denialAmount;
                    $denial['value_add_minus'] = 'Add';
                    $denial['created_on'] = date('Y-m-d H:i:s');
                    $this->model_trade_transaction_fine_rebet_details->fine_rebet_details($denial);
                }

                $payment_status = 1;
                if ($inputs['payment_mode'] != 'CASH') {
                    $chq_arr = array();
                    $chq_arr['transaction_id'] = $transaction_id;
                    $chq_arr['cheque_no'] = $inputs['chq_no'];
                    $chq_arr['cheque_date'] = $inputs['chq_date'];
                    $chq_arr['bank_name'] = $inputs['bank_name'];
                    $chq_arr['branch_name'] = $inputs['branch_name'];
                    $chq_arr['emp_details_id'] = $emp_id;
                    $chq_arr['created_on'] = date('Y-m-d H:i:s');
                    $payment_status = 2;
                    $this->TradeChequeDtlModel->insertdata($chq_arr);
                }

                $warddet = $this->model_ward_mstr->getWardNoBywardId($data["ward_mstr_id"]);
                $ward_no = $warddet["ward_no"];

                # Update Provisional No
                $prov_no = $short_ulb_name . $ward_no . date('mdy') . $applyid;
                $this->TradeApplyLicenceModel->update_prov_no($applyid, $prov_no);

                # Update Application No
                // $app_no = "APPLIC" . $applyid . date('dmyhis');
                $app_no = "APN".str_pad(substr($ward_no,0,2),2,"0",STR_PAD_LEFT).STR_PAD($applyid,7,"0",STR_PAD_LEFT);
                $this->TradeApplyLicenceModel->update_application_no($app_no, $applyid, $payment_status);
            }

            # Renewal
            elseif ($data["application_type"]["id"] == 2) {
                $rules = [
                    'licence_for' => 'required',
                    'payment_mode' => 'required',
                    'holding_no'=>'required',
                ];
                if (!$this->validate($rules)) {
                    $data['validation'] = $this->validator;
                    return view('trade/Connection/applylicence', $data);
                }

                $inputs = filterSanitizeStringtoUpper($this->request->getVar());


                $data["otherfirmtype"] = $inputs['firmtype_other'];
                $data["licence_for_years"] = $inputs["licence_for"];
                $data["valid_from"] = $inputs["firm_date"];


                $data["application_type_id"] = $data["application_type"]["id"];               
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

                $data["prop_dtl_id"] = isset($inputs['prop_id'])&&!empty($inputs['prop_id'])?$inputs['prop_id']:$data["licencedet"]["prop_dtl_id"];
                $data["holding_no"] = isset($inputs['holding_no'])&&!empty($inputs['holding_no'])?$inputs['holding_no']:$data["licencedet"]["holding_no"];
                $data["provisional_license_no"] = $data["licencedet"]["provisional_license_no"];
                $data["license_no"] = $data["licencedet"]["license_no"];
                $priv_m_d = date('m-d', strtotime($data["valid_from"]));
                $date = date('Y') . '-' . $priv_m_d;
                $vali_upto = date('Y-m-d', strtotime($date . "+" . $data["licence_for_years"] . " years"));
                $data['valid_upto'] = $vali_upto;
                $data["apply_from"] = 'TC';

                # Calculating Rate
                {
                    $args['areasqft'] = $data["licencedet"]["area_in_sqft"];
                    $args['applytypeid'] = $data["application_type"]["id"];
                    $args['estdate'] = $inputs["firm_date"];
                    $args['tobacco_status'] = $data["licencedet"]["tobacco_status"];
                    $args['licensefor'] = $inputs["licence_for"];
                    $args['nature_of_business'] = $data['nature_of_bussiness'];
                    $rate_data = $this->TradeApplyLicenceController->getcharge($args);
                    $rate_data = json_decode(json_encode(json_decode($rate_data)), true);
                }

                $data["rate_id"] = $rate_data["rate_id"];
                $data['emp_details_id'] = $emp_id;
                $data['created_on'] = date('Y-m-d H:i:s');

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

                $transact_arr = array();
                $transact_arr['related_id'] = $applyid;
                $transact_arr['ward_mstr_id'] = $data["ward_mstr_id"];
                $transact_arr['transaction_type'] = $data["application_type"]["application_type"];
                $transact_arr['transaction_date'] = date('Y-m-d');
                $transact_arr['payment_mode'] = $inputs['payment_mode'];
                $transact_arr['paid_amount'] = $rate_data['total_charge'];

                $transact_arr['penalty'] = $rate_data['penalty'];
                if ($inputs['payment_mode'] != 'CASH') {
                    $transact_arr['status'] = 2;
                }
                $transact_arr['emp_details_id'] = $emp_id;
                $transact_arr['created_on'] = date('Y-m-d H:i:s');
                $transact_arr['ip_address'] = '';

                $transaction_id = $this->TradeTransactionModel->insertPayment($transact_arr);
                //echo $transaction_id;
                $trafinerebate = array();
                $trafinerebate['transaction_id'] = $transaction_id;
                $trafinerebate['head_name'] = 'Delay Apply License';
                $trafinerebate['amount'] = $rate_data['penalty'];
                $trafinerebate['value_add_minus'] = 'Add';
                $trafinerebate['created_on'] = date('Y-m-d H:i:s');
                $this->model_trade_transaction_fine_rebet_details->fine_rebet_details($trafinerebate);
                $payment_status = 1;
                if ($inputs['payment_mode'] != 'CASH') {
                    $chq_arr = array();
                    $chq_arr['transaction_id'] = $transaction_id;
                    $chq_arr['cheque_no'] = $inputs['chq_no'];
                    $chq_arr['cheque_date'] = $inputs['chq_date'];
                    $chq_arr['bank_name'] = $inputs['bank_name'];
                    $chq_arr['branch_name'] = $inputs['branch_name'];
                    $chq_arr['emp_details_id'] = $emp_id;
                    $chq_arr['created_on'] = date('Y-m-d H:i:s');
                    $payment_status = 2;
                    $this->TradeChequeDtlModel->insertdata($chq_arr);
                }

                # Update Application No
               
                $warddet = $this->model_ward_mstr->getWardNoBywardId($data["ward_mstr_id"]);
                $ward_no = $warddet["ward_no"];

                # Update Application No
                // $app_no = "APPLIC" . $applyid . date('dmyhis');
                $app_no = "APN".str_pad(substr($ward_no,0,2),2,"0",STR_PAD_LEFT).STR_PAD($applyid,7,"0",STR_PAD_LEFT);

                $this->TradeApplyLicenceModel->update_application_no($app_no, $applyid, $payment_status);
            }

            # 3 Amendment
            elseif ($data["application_type"]["id"] == 3) {
                $rules = [
                    'area_in_sqft' => 'required',
                    'payment_mode' => 'required',
                ];
                if (!$this->validate($rules)) {
                    $data['validation'] = $this->validator;
                    return view('trade/Connection/applylicence', $data);
                }

                $inputs = filterSanitizeStringtoUpper($this->request->getVar());

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
                $data["provisional_license_no"] = $data["licencedet"]["provisional_license_no"];
                $vali_upto = date('Y-m-d', strtotime(date('Y-m-d') . "+" . $data["licence_for_years"] . " years"));
                $data['valid_upto'] = $vali_upto;
                $data["apply_from"] = 'TC';

                # Calculating Rate
                {
                    $args['areasqft'] = $inputs["area_in_sqft"];
                    $args['applytypeid'] = 3;
                    $args['estdate'] = date('Y-m-d');
                    $args['tobacco_status'] = $data["licencedet"]["tobacco_status"];
                    $args['licensefor'] = $inputs["licence_for"];
                    $args['nature_of_business'] = $data['nature_of_bussiness'];
                    $rate_data = $this->TradeApplyLicenceController->getcharge($args);
                    $rate_data = json_decode(json_encode(json_decode($rate_data)), true);
                }

                $data["rate_id"] = $rate_data["rate_id"];
                $data['emp_details_id'] = $emp_id;
                $data['created_on'] = date('Y-m-d H:i:s');

                $applyid = $this->TradeApplyLicenceModel->insertapply($data);
                $this->TradeApplyLicenceModel->update_status_id($applyid, $data["licencedet"]["id"]);

                $warddet = $this->model_ward_mstr->getWardNoBywardId($data["ward_mstr_id"]);
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
                if ($inputs['payment_mode'] != 'CASH') {
                    $transact_arr['status'] = 2;
                }
                $transact_arr['emp_details_id'] = $emp_id;
                $transact_arr['created_on'] = date('Y-m-d H:i:s');
                $transact_arr['ip_address'] = '';

                $transaction_id = $this->TradeTransactionModel->insertPayment($transact_arr);
                //echo $transaction_id;
                $trafinerebate = array();
                $trafinerebate['transaction_id'] = $transaction_id;
                $trafinerebate['head_name'] = 'Delay Apply License';
                $trafinerebate['amount'] = $rate_data['penalty'];
                $trafinerebate['value_add_minus'] = 'Add';
                $trafinerebate['created_on'] = date('Y-m-d H:i:s');
                $this->model_trade_transaction_fine_rebet_details->fine_rebet_details($trafinerebate);
                $payment_status = 1;
                if ($inputs['payment_mode'] != 'CASH') {
                    $chq_arr = array();
                    $chq_arr['transaction_id'] = $transaction_id;
                    $chq_arr['cheque_no'] = $inputs['chq_no'];
                    $chq_arr['cheque_date'] = $inputs['chq_date'];
                    $chq_arr['bank_name'] = $inputs['bank_name'];
                    $chq_arr['branch_name'] = $inputs['branch_name'];
                    $chq_arr['emp_details_id'] = $emp_id;
                    $chq_arr['created_on'] = date('Y-m-d H:i:s');
                    $payment_status = 2;
                    $this->TradeChequeDtlModel->insertdata($chq_arr);
                }

                # Update Application No
                // $app_no = "APPLIC" . $applyid . date('dmyhis');
                $app_no = "APN".str_pad(substr($ward_no,0,2),2,"0",STR_PAD_LEFT).STR_PAD($applyid,7,"0",STR_PAD_LEFT);
                $this->TradeApplyLicenceModel->update_application_no($app_no, $applyid, $payment_status);
            }

            # Surrender
            elseif ($data["application_type"]["id"] == 4) {
                $rules = [
                    'area_in_sqft' => 'required',
                ];
                if (!$this->validate($rules)) {
                    $data['validation'] = $this->validator;
                    return view('trade/Connection/applylicence', $data);
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
                $data["provisional_license_no"] = $data["licencedet"]["provisional_license_no"];
                $data["valid_from"] = date('Y-m-d');
                $data["valid_upto"] = $data["licencedet"]["valid_upto"];
                $data["license_no"] = $data["licencedet"]["license_no"];
                $data["payment_status"] = 1; // No Payment in case of surrender
                $data["rate_id"] = 16; // 0 Rs Payment in surrender as tbl_licence_rate
                $data['emp_details_id'] = $emp_id;
                $data['created_on'] = date('Y-m-d H:i:s');
                $data["apply_from"] = 'TC';

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
  
                $warddet = $this->model_ward_mstr->getWardNoBywardId($data["ward_mstr_id"]);
                $ward_no = $warddet["ward_no"];

                // $app_no = "APPLIC" . $applyid . date('dmyhis');
                $app_no = "APN".str_pad(substr($ward_no,0,2),2,"0",STR_PAD_LEFT).STR_PAD($applyid,7,"0",STR_PAD_LEFT);
                
                $this->TradeApplyLicenceModel->update_application_no($app_no, $applyid, $data["payment_status"]);
            }
            $this->dbSystem->transBegin();
            $application["id"]=$applyid;
            $application["application_no"]=$app_no;
            $application["application_type_id"] = $data["application_type"]["id"];
            $vistingRepostInput = tradeConnApplyVisit($application,$this->request->getVar());           
            $visiting_dtl_id = $this->model_visiting_dtl->insertdetail($vistingRepostInput);
            if(isset($transaction_id) && $transaction_id)
            {
                $application["id"]=$applyid;
                $application["application_no"]=$app_no;
                $vistingRepostInput = tradeTranVisit($application,$transaction_id,$this->request->getVar());           
                $visiting_dtl_id = $this->model_visiting_dtl->insertdetail($vistingRepostInput);
            }
            if ($this->db->transStatus() === FALSE) {
                $this->db->transRollback();
                $this->dbSystem->transRollback();
                flashToast("applylicence", "Something errordue to payment!!!");
                if ($data["application_type_id"] == 1) {
                    return $this->response->redirect(base_url('tradeapplylicenceMobile/ApplyNewLicenseMobi/' . $apptypeid));
                } else {
                    return $this->response->redirect(base_url('tradeapplylicenceMobile/ApplyNewLicenseMobi/' . $apptypeid . '/' . $id));
                }
            } else {
                $this->db->transCommit();
                $this->dbSystem->transCommit();
                if ($data["application_type"]["id"] <> 4) {
                    return $this->response->redirect(base_url('mobitradeapplylicence/view_transaction_receipt/' . md5($applyid) . '/' . md5($transaction_id) . '/true'));
                } else {
                    return $this->response->redirect(base_url('mobitradeapplylicence/trade_licence_view/' . md5($applyid)));
                }
            }
        }
        $data["tradeitemlist"] = $this->tradeitemsmstrmodel->gettradeitemsList();
        return view('mobile/trade/ApplyLicenseMobi', $data);
    }







    public function applylicence_old($apptypeid = null, $id = null)
    {
        $data = array();
        helper(['form']);
        $session = session();
        $get_ulb_id = $session->get('ulb_dtl');
        $get_emp_details = $session->get('emp_details');
        $emp_id = $get_emp_details['id'];
        $data['ulb_mstr_name'] = $this->model_ulb_mstr->getulb_list($get_ulb_id["ulb_mstr_id"]);
        $short_ulb_name = $data["ulb_mstr_name"]["short_ulb_name"];



        $data = (array)null;
        $data['curdate'] = date("Y-m-d");
        $data["apptypeid"] = $apptypeid;
        $data["id"] = $id;
        $data["statelist"] = $this->statemodel->getstateList();
        $data["idprooflist"] = $this->model_trade_document->getIdPoorfDocumentList();
        $data['ward_list'] = $this->model_ward_mstr->getWardList($get_ulb_id);
        $data["application_type"] = $this->tradeapplicationtypemstrmodel->getdatabymd5id($apptypeid);
        $data["firmtypelist"] = $this->tradefirmtypemstrmodel->getFirmTypeList();
        $data["ownershiptypelist"] = $this->tradeownershiptypemstrmodel->getownershipTypeList();
        $data["tradeitemlist"] = $this->tradeitemsmstrmodel->gettradeitemsList();

        if ($apptypeid <> null and $id <> null) {

            $data["application_type"] = $this->tradeapplicationtypemstrmodel->getdatabymd5id($apptypeid);
            $data["application_type_id"] = $data["application_type"]["id"];
            $data["licencedet"] = $this->model_trade_licence->get_licence_md5dtl($id);
            $data["validitydet"] = $this->model_trade_licence_validity->get_licence_validity_dtl($data["licencedet"]["id"]);
            $data["applylicencedet"] = $this->TradeViewApplyLicenceOwnerModel->getdetails($data["licencedet"]["apply_licence_id"]);
            $data["ownerdet"] = $this->model_trade_licence_owner_name->get_licence_md5dtl($id);
            $data["tradeitemdet"] = $this->model_trade_view_licence_trade_items->get_details($id);

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
            if ($data["licencedet"]["property_type"] == 'PROPERTY') {
                $data["holding_exists"] = 'YES';
                $propdet = $this->model_prop_dtl->getholdingnobypropid($data["licencedet"]["prop_dtl_id"]);
                $data["holding_no"] = $propdet["holding_no"];
            } else {
                $data["holding_exists"] = 'NO';
                $propdet = $this->model_saf_dtl->getSafdetails($data["licencedet"]["prop_dtl_id"]);
                $data["saf_no"] = $propdet["saf_no"];
            }
            $warddet = $this->model_ward_mstr->getWardNoBywardId($data['licencedet']['ward_mstr_id']);
            $data['ward_no'] = $warddet["ward_no"];
            $data["firm_name"] = $data["licencedet"]["firm_name"];
            $data["area_in_sqft"] = (float)$data["licencedet"]["area_in_sqft"];
            $data["firm_date"] = $data["licencedet"]["establishment_date"];
            $data["address"] = $data["licencedet"]["firm_address"];
            $data["landmark"] = $data["licencedet"]["landmark"];
            $data["pin_code"] = $data["licencedet"]["pin_code"];
            $ratedet = $this->tradelicenceratemodel->getrate($data);
            $data["rate"] = $ratedet["rate"] * $data["Chargeforyear"];
        } else {
            $data["application_type"] = $this->tradeapplicationtypemstrmodel->getdatabymd5id($apptypeid);
        }

        // $safdet = $this->model_saf_dtl->getSafDtlBySafno($data);

        if ($this->request->getMethod() == "post") {
            $this->db->transBegin();
            /* echo $_SERVER['REMOTE_ADDR'];
               die();*/
            if ($data["application_type"]["id"] == 1) {

                $rules = [
                    'firmtype_id' => 'required',
                    'ownership_type_id' => 'required',
                    'ward_id' => 'required',
                    'holding_no' => 'required',
                    'area_in_sqft' => 'required',
                    'firm_date' => 'required',
                    'pin_code' => 'required|min_length[6]|max_length[6]',
                    'firm_name' => 'required',
                    'area_in_sqft' => 'required',
                    'firmaddress' => 'required',
                    'licence_for' => 'required',
                    'payment_mode' => 'required',
                ];
            } else {
                if ($data["application_type"]["id"] == 3) {
                    $rules = [
                        'area_in_sqft' => 'required',
                        'payment_mode' => 'required',
                    ];
                } else {
                    $rules = [
                        'licence_for' => 'required',
                        'payment_mode' => 'required',
                    ];
                }
            }

            if (!$this->validate($rules)) {

                $data['validation'] = $this->validator;


                return view('mobile/trade/applylicence', $data);
            } else {
                $inputs = filterSanitizeStringtoUpper($this->request->getVar());

                $data["application_type_id"] = $data["application_type"]["id"];
                $data["licence_for_years"] = $inputs['licence_for'];
                $data["otherfirmtype"] = $inputs['firmtype_other'];
                $penalty = 0;
                if ($data["application_type"]["id"] == 1) {

                    $data["firm_type_id"] = $inputs['firmtype_id'];
                    $data["ownership_type_id"] = $inputs['ownership_type_id'];
                    $data["ward_mstr_id"] = $inputs['ward_id'];
                    $data["holding_exists"] = $inputs['holding_exists'];
                    $data["prop_dtl_id"] = $inputs['prop_id'];
                    $data["holding_no"] = $inputs['holding_no'];
                    $data["property_type"] = 'PROPERTY';
                    $data["firm_name"] = $inputs['firm_name'];
                    $data["area_in_sqft"] = (float)$inputs['area_in_sqft'];
                    $data["firm_date"] = $inputs['firm_date'];
                    $data["address"] = $inputs['firmaddress'];
                    $data["landmark"] = $inputs['landmark'];
                    $data["pin_code"] = $inputs['pin_code'];

                    $ratedet = $this->tradelicenceratemodel->getrate($data);
                    $charge = $ratedet["rate"] * $data["licence_for_years"];

                    $date1 = $data["firm_date"];
                    $date2 = date('Y-m-d');

                    $ts1 = strtotime($date1);
                    $ts2 = strtotime($date2);

                    $year1 = date('Y', $ts1);
                    $year2 = date('Y', $ts2);

                    $month1 = date('m', $ts1);
                    $month2 = date('m', $ts2);

                    $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
                    if ($diff > 0) {
                        $penalty = ($diff * 20) + 100;
                    }
                    $total_charge = $charge + $penalty;
                } else {

                    if ($data["application_type"]["id"] == 2) {
                        $date1 = $inputs['firm_date'];
                        $date2 = date('Y-m-d');

                        $ts1 = strtotime($date1);
                        $ts2 = strtotime($date2);

                        $year1 = date('Y', $ts1);
                        $year2 = date('Y', $ts2);

                        $month1 = date('m', $ts1);
                        $month2 = date('m', $ts2);

                        $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
                        if ($diff > 0) {
                            $penalty = ($diff * 20) + 100;
                        }
                    }

                    $data["firm_type_id"] = $data["licencedet"]["firm_type_id"];
                    $data["ownership_type_id"] = $data["licencedet"]["ownership_type_id"];
                    $data["ward_mstr_id"] = $data["licencedet"]["ward_mstr_id"];
                    $data["prop_dtl_id"] = $data["licencedet"]["prop_dtl_id"];
                    $data["property_type"] = $data["licencedet"]["property_type"];
                    if ($data["application_type"]["id"] == 3) {
                        $data["area_in_sqft"] = (float)$inputs['area_in_sqft'];
                        $data["licence_for_years"] = $data['Chargeforyear'];
                    } else {
                        $data["area_in_sqft"] = (float)$data["licencedet"]["area_in_sqft"];
                    }
                    $data["licence_id"] = $data["licencedet"]["id"];
                    $ratedet = $this->tradelicenceratemodel->getrate($data);
                    $total_charge = $ratedet["rate"] * $data["licence_for_years"];
                    $total_charge = $total_charge + $penalty;
                }

                $data["rate_id"] = $ratedet["id"];
                $data['emp_details_id'] = $emp_id;
                $data['created_on'] = date('Y-m-d H:i:s');

                $applyid = $this->TradeApplyLicenceModel->insertapply($data);




                if ($applyid) {
                    $owner_arr = array();
                    $owner_arr['apply_licence_id'] = $applyid;
                    $owner_arr['licence_id'] = $data["licencedet"]["id"];
                    $owner_arr['emp_details_id'] = $emp_id;
                    $owner_arr['created_on'] = date('Y-m-d H:i:s');

                    $tradeitem_arr = array();
                    $tradeitem_arr['apply_licence_id'] = $applyid;
                    $tradeitem_arr['licence_id'] = $data["licence_id"];
                    $tradeitem_arr['trade_items_id'] = $inputs['tade_item'][$i];
                    $tradeitem_arr['emp_details_id'] = $emp_id;
                    $tradeitem_arr['created_on'] = date('Y-m-d H:i:s');
                    if ($data["application_type"]["id"] == 1) {

                        if (isset($inputs['owner_name'])) {
                            for ($i = 0; $i < sizeof($inputs['owner_name']); $i++) {
                                $owner_arr['owner_name'] = $inputs['owner_name'][$i];
                                $owner_arr['guardian_name'] = $inputs['guardian_name'][$i];
                                $owner_arr['emailid'] = $inputs['emailid'][$i];
                                $owner_arr['document_id'] = $inputs['idproof'][$i];
                                $owner_arr['id_no'] = $inputs['id_no'][$i];
                                $owner_arr['mobile'] = $inputs['mobile_no'][$i];


                                $this->TradeFirmOwnerModel->insertdata($owner_arr);
                            }
                        }

                        if (isset($inputs['tade_item'])) {

                            for ($i = 0; $i < sizeof($inputs['tade_item']); $i++) {
                                $tradeitem_arr['trade_items_id'] = $inputs['tade_item'][$i];

                                $this->TradeTradeItemsModel->insertdata($tradeitem_arr);
                            }

                            //  print_r($owner_arr);

                        }
                    } else {

                        if (isset($data["ownerdet"])) {

                            if (!empty($data["ownerdet"])) {

                                $this->TradeFirmOwnerModel->insertrenewdata($owner_arr);
                            }
                        }

                        if ($data["application_type"]["id"] == 3) {
                            if (isset($inputs['tade_item'])) {
                                for ($i = 0; $i < sizeof($inputs['tade_item']); $i++) {
                                    $tradeitem_arr['trade_items_id'] = $inputs['tade_item'][$i];
                                    $this->TradeTradeItemsModel->insertdata($tradeitem_arr);
                                }
                            }
                        } else {
                            if (isset($data["tradeitemdet"])) {

                                if (!empty($data["tradeitemdet"])) {

                                    $this->TradeTradeItemsModel->insertrenewdata($owner_arr);
                                }
                            }
                        }
                    }

                    $transact_arr = array();
                    $transact_arr['related_id'] = $applyid;
                    $transact_arr['ward_mstr_id'] = $data["ward_mstr_id"];
                    $transact_arr['transaction_type'] = $data["application_type"]["application_type"];
                    $transact_arr['transaction_date'] = date('Y-m-d');
                    $transact_arr['payment_mode'] = $inputs['payment_mode'];
                    $transact_arr['paid_amount'] = $total_charge;
                    $transact_arr['penalty'] = $penalty;
                    if ($inputs['payment_mode'] != 'CASH') {
                        $transact_arr['status'] = 2;
                    }
                    $transact_arr['emp_details_id'] = $emp_id;
                    $transact_arr['created_on'] = date('Y-m-d H:i:s');

                    $transaction_id = $this->TradeTransactionModel->insertPayment($transact_arr);
                    $trafinerebate = array();
                    $trafinerebate['transaction_id'] = $transaction_id;
                    $trafinerebate['head_name'] = 'Delay Apply License';
                    $trafinerebate['amount'] = $penalty;
                    $trafinerebate['value_add_minus'] = 'Add';
                    $trafinerebate['created_on'] = date('Y-m-d H:i:s');
                    $this->model_trade_transaction_fine_rebet_details->fine_rebet_details($trafinerebate);
                    $payment_status = 1;
                    if ($inputs['payment_mode'] != 'CASH') {
                        $chq_arr = array();
                        $chq_arr['transaction_id'] = $transaction_id;
                        $chq_arr['cheque_no'] = $inputs['chq_no'];
                        $chq_arr['cheque_date'] = $inputs['chq_date'];
                        $chq_arr['bank_name'] = $inputs['bank_name'];
                        $chq_arr['branch_name'] = $inputs['branch_name'];
                        $chq_arr['emp_details_id'] = $emp_id;
                        $chq_arr['created_on'] = date('Y-m-d H:i:s');

                        $payment_status = 2;

                        $this->TradeChequeDtlModel->insertdata($chq_arr);
                    }

                    $app_no = "APPLIC" . $applyid . date('dmyhis');

                    $this->TradeApplyLicenceModel->update_application_no($app_no, $applyid, $payment_status);

                    $warddet = $this->model_ward_mstr->getWardNoBywardId($data["ward_mstr_id"]);
                    $ward_no = $warddet["ward_no"];

                    $data['ward_count'] = $this->TradeApplyLicenceModel->count_ward_by_wardid($data["ward_mstr_id"]);
                    $sl_no = $data['ward_count']['ward_cnt'];
                    $sl_noo = $sl_no + 1;
                    $serial_no = str_pad($sl_noo, 4, "0", STR_PAD_LEFT);
                    $ward_nmm = str_pad($ward_nm, 2, "0", STR_PAD_LEFT);
                    $prov_no = $short_ulb_name . 'P' . $ward_no . $serial_no;
                    $this->TradeApplyLicenceModel->update_prov_no($applyid, $prov_no);


                    if ($this->db->transStatus() === FALSE) {
                        $this->db->transRollback();
                        flashToast("applylicence", "Something errordue to payment!!!");
                        if ($data["application_type_id"] == 1) {
                            return $this->response->redirect(base_url('tradeapplylicenceMobile/applylicence/' . $apptypeid));
                        } else {
                            return $this->response->redirect(base_url('tradeapplylicenceMobile/applylicence/' . $apptypeid . '/' . $id));
                        }
                    } else {
                        $this->db->transCommit();
                        return $this->response->redirect(base_url('tradeapplylicenceMobile/view_transaction_receipt/' . md5($applyid) . '/' . md5($transaction_id)));
                    }
                    return $this->response->redirect(base_url('tradeapplylicenceMobile/view_transaction_receipt/' . md5($applyid) . '/' . md5($transaction_id)));
                }
            }
        }


        return view('mobile/trade/applylicence', $data);
    }


    public function view_transaction_receipt($applyid = null, $transaction_id = null)
    {

        $data = array();

        $data['transaction_id'] = $transaction_id;
        $data['status'] = [1, 2];
        $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
        $data['ulb_mstr_name'] = $this->model_ulb_mstr->getulb_list($ulb_mstr_id);
        $data['transaction_details'] = $this->TradeTransactionModel->transaction_details($transaction_id);
        $data['applicant_details'] = $this->TradeViewApplyLicenceOwnerModel->getFirmData($data['transaction_details']['related_id']);
        $data['emp_details'] = $this->model_emp_details->emp_dtls($data['transaction_details']['emp_details_id']);
        $warddet = $this->model_ward_mstr->getWardNoBywardId($data['transaction_details']['ward_mstr_id']);
        $data['ward_no'] = $warddet["ward_no"];
        // print_var($data);die();
        $data['cheque_details'] = $this->TradeChequeDtlModel->alltransactioncheque_details($data['transaction_id']);
        return view('mobile/trade/payment_tax_receipt', $data);
    }
}
