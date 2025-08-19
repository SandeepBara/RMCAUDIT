<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\TradeFirmTypeMstrModel;
use App\Models\TradeApplicationTypeMstrModel;
use App\Models\TradeOwnershipTypeMstrModel;
use App\Models\TradeItemsMstrModel;
use App\Models\TradeLicenceRateModel;
use App\Models\TradeViewLicenceRateModel;
use App\Models\StateModel;
use App\Models\DistrictModel;
use App\Models\model_ward_mstr;
use App\Models\PropertyModel;
use App\Models\TradeApplyLicenceModel;
use App\Models\TradeFirmOwnerModel;
use App\Models\TradeTradeItemsModel;
use App\Models\TradeTransactionModel;
use App\Models\model_ulb_mstr;
use App\Models\model_saf_dtl;
use App\Models\model_saf_owner_detail;
use App\Models\model_prop_dtl;
use App\Models\model_prop_owner_detail;
use App\Models\TradeChequeDtlModel;
use App\Models\model_trade_level_pending_dtl;
//use App\Models\TradeViewLicenceOwnerrModel;
use App\Models\model_trade_licence;
use App\Models\model_view_trade_licence;
use App\Models\model_trade_licence_owner_name;
use App\Models\model_trade_view_licence_trade_items;
use App\Models\model_trade_licence_validity;
use App\Models\model_trade_document;
use App\Models\model_trade_transaction_fine_rebet_details;
use App\Models\model_datatable;
use App\Models\model_view_emp_details;
use App\Models\model_application_type_mstr;
use App\Models\model_apply_licence;
use App\Models\TradeCategoryTypeModel;
use App\Models\TradeViewApplyLicenceOwnerModel;
use App\Models\TradeApplyDenialModel;
use App\Models\model_trade_sms_log;
use App\Models\model_view_ward_permission;
use Exception;

class tradeapplylicence extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $statemodel;
    protected $tradefirmtypemstrmodel;
    protected $tradeapplicationtypemstrmodel;
    protected $tradeownershiptypemstrmodel;
    protected $tradeitemsmstrmodel;
    protected $tradelicenceratemodel;
    protected $tradeviewlicenceratemodel;
    protected $districtmodel;
    protected $model_ward_mstr;
    protected $TradeApplyLicenceModel;
    protected $TradeFirmOwnerModel;
    protected $TradeTradeItemsModel;
    protected $TradeTransactionModel;
    protected $model_ulb_mstr;
    protected $model_saf_dtl;
    protected $model_saf_owner_detail;
    protected $model_prop_dtl;
    protected $model_prop_owner_detail;
    protected $TradeChequeDtlModel;
    protected $model_trade_level_pending_dtl;
    //protected $TradeViewLicenceOwnerrModel;
    protected $model_trade_licence;
    protected $model_view_trade_licence;
    protected $model_trade_licence_owner_name;
    protected $model_trade_view_licence_trade_items;
    protected $model_trade_licence_validity;
    protected $model_trade_document;
    protected $model_trade_transaction_fine_rebet_details;
    protected $model_datatable;
    protected $model_view_emp_details;
    protected $model_application_type_mstr;
    protected $TradeCategoryTypeModel;
    protected $TradeViewApplyLicenceOwnerModel;
    protected $TradeApplyDenialModel;
    protected $model_trade_sms_log;
    protected $model_view_ward_permission;

    public function __construct()
    {

        parent::__construct();
        helper(['db_helper', 'qr_code_generator_helper', 'form_helper', 'sms_helper', 'utility']);
        if ($db_name = dbConfig("trade")) {
            $this->db = db_connect($db_name);
        }
        if ($db_system = dbSystem()) {
            $this->dbSystem = db_connect($db_system);
        }
        if ($db_name = dbConfig("property")) {
            $this->property_db = db_connect($db_name);
        }

        /*$this->db = db_connect("db_rmc_trade"); 
        $this->dbSystem = db_connect("db_system"); */
        $this->statemodel = new statemodel($this->dbSystem);
        $this->districtmodel = new districtmodel($this->dbSystem);
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_view_ward_permission = new model_view_ward_permission($this->dbSystem);
        // $this->property_model=new PropertyModel($this->property_db);
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
        $this->modelUlb = new model_ulb_mstr($this->dbSystem);
        $this->model_saf_dtl = new model_saf_dtl($this->property_db);
        $this->model_saf_owner_detail = new model_saf_owner_detail($this->property_db);
        $this->model_prop_dtl = new model_prop_dtl($this->property_db);
        $this->model_prop_owner_detail = new model_prop_owner_detail($this->property_db);
        $this->TradeChequeDtlModel = new TradeChequeDtlModel($this->db);
        $this->model_trade_level_pending_dtl = new model_trade_level_pending_dtl($this->db);
        //$this->TradeViewLicenceOwnerrModel = new tradeviewlicenceownerrmodel($this->db);
        //$this->model_trade_licence = new model_trade_licence($this->db);
        $this->model_view_trade_licence = new model_view_trade_licence($this->db);
        $this->model_trade_licence_owner_name = new model_trade_licence_owner_name($this->db);
        $this->model_trade_view_licence_trade_items = new model_trade_view_licence_trade_items($this->db);
        $this->model_trade_licence_validity = new model_trade_licence_validity($this->db);
        $this->model_trade_document = new model_trade_document($this->db);
        $this->model_trade_transaction_fine_rebet_details = new model_trade_transaction_fine_rebet_details($this->db);
        $this->model_datatable = new model_datatable($this->db);
        $this->model_view_emp_details = new model_view_emp_details($this->dbSystem);
        $this->model_application_type_mstr = new model_application_type_mstr($this->db);
        $this->model_apply_licence = new model_apply_licence($this->db);
        $this->TradeCategoryTypeModel = new TradeCategoryTypeModel($this->db);
        $this->TradeViewApplyLicenceOwnerModel = new TradeViewApplyLicenceOwnerModel($this->db);
        $this->TradeApplyDenialModel = new TradeApplyDenialModel($this->db);

        $this->model_trade_sms_log = new model_trade_sms_log($this->db);
    }

    function __destruct()
    {
        $this->db->close();
        $this->dbSystem->close();
        $this->property_db->close();
    }


    public function index()
    {
        $data = array();
        $data["applicationType"] = $this->tradeapplicationtypemstrmodel->getapplicationTypeList();
        // print_r($data["applicationType"]);
        return view('trade/Connection/tradeapply', $data);
    }

    public function applynewlicences($apptypeid = null)
    {
        $data = (array)null;
        $session = session();
        $get_emp_details = $session->get('emp_details');
        $emp_id = $get_emp_details['id'];
        $from_date = date('Y-m-d');
        $to_date = date('Y-m-d');
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        if ($apptypeid <> null) {
            $data["application_type"] = $this->tradeapplicationtypemstrmodel->getdatabymd5id($apptypeid);
            $data["user_type"] = $this->model_view_emp_details->getEmpListUserType($emp_id);
            return view('trade/Connection/ApplyNewLicence', $data);
        }
    }


    public function newapplicationlistAjax()
    {

        if ($this->request->getMethod() == 'post') {
            try {
                ## Read value
                $start = sanitizeString($this->request->getVar('start'));

                $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page

                $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
                $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
                if ($columnName == "s_no")
                    $columnName = 'apply.id';
                if ($columnName == "application_no")
                    $columnName = 'apply.application_no';
                if ($columnName == "ward_no")
                    $columnName = 'view_ward_mstr.ward_no';
                if ($columnName == "license_no")
                    $columnName = 'apply.license_no';
                if ($columnName == "firm_name")
                    $columnName = 'apply.firm_name';
                if ($columnName == "application_type")
                    $columnName = 'tbl_application_type.application_type';
                if ($columnName == "apply_date")
                    $columnName = 'apply.apply_date';
                if ($columnName == "apply_by")
                    $columnName = 'view_emp_details.name';
                if ($columnName == "view")
                    $columnName = 'view';

                //$columnName = "tbl_transaction.tran_date";
                $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
                $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value

                // Date filter
                $application_type_id = sanitizeString($this->request->getVar('application_type_id'));
                $form_date = sanitizeString($this->request->getVar('search_from_date'));
                $upto_date = sanitizeString($this->request->getVar('search_upto_date'));
                $searchQuery = "";

                $orderBY = " ORDER BY " . $columnName . " " . $columnSortOrder;
                $limit = " LIMIT " . $rowperpage . " OFFSET " . $start;

                $whereQueryWithSearch = "";
                if ($searchValue != '') {
                    if ($application_type_id > 1) {
                        $whereQueryWithSearch = " AND (apply.application_no ILIKE '%" . $searchValue . "%'
                        OR view_ward_mstr.ward_no ILIKE '%" . $searchValue . "%'
                        OR apply.firm_name ILIKE '%" . $searchValue . "%'
                        OR view_emp_details.emp_name ILIKE '%" . $searchValue . "%'
                        OR apply.license_no ILIKE '%" . $searchValue . "%')";
                    } else {
                        $whereQueryWithSearch = " AND (apply.application_no ILIKE '%" . $searchValue . "%'
                        OR view_ward_mstr.ward_no ILIKE '%" . $searchValue . "%'
                        OR apply.firm_name ILIKE '%" . $searchValue . "%'
                        OR view_emp_details.emp_name ILIKE '%" . $searchValue . "%')";
                    }
                }


                if ($application_type_id > 1) {

                    /* $selectStatement = "SELECT 
                                    ROW_NUMBER () OVER (ORDER BY ".$columnName." DESC) AS s_no,
                                    view_ward_mstr.ward_no,
                                    apply.application_no,
                                    apply.license_no,
                                    apply.firm_name,
                                    tbl_application_type.application_type,
                                    apply.apply_date,
                                    view_emp_details.emp_name,
                                    case when apply.payment_status=0 then 
                                    concat('<a class=', chr(39),'btn btn-primary', chr(39), 'href=".base_url()."/tradeapplylicence/licencepayment/',md5(apply.id::text),' role=button>payment</a>')   
                                    else
                                    concat('<a class=', chr(39),'btn btn-primary', chr(39), 'href=".base_url()."/tradeapplylicence/trade_licence_view/',md5(apply.id::text),' role=button>View</a>')
                                    end  as view  
                                    ";*/
                    $selectStatement = "SELECT 
                                    ROW_NUMBER () OVER (ORDER BY " . $columnName . " DESC) AS s_no,
                                        view_ward_mstr.ward_no,
                                        apply.application_no,
                                        apply.license_no,
                                        apply.firm_name,
                                        tbl_application_type.application_type,
                                        apply.apply_date,
                                        case when apply.emp_details_id isnull or apply.emp_details_id =0 then 'Online' else view_emp_details.emp_name end as emp_name,                                    
                                        concat('<a class=', chr(39),'btn btn-primary', chr(39), 'href=" . base_url() . "/tradeapplylicence/trade_licence_view/',md5(apply.id::text),' role=button>View</a>')
                                        as view  
                                        ";
                } else {
                    $selectStatement = "SELECT 
                                        ROW_NUMBER () OVER (ORDER BY " . $columnName . " DESC) AS s_no,
                                            view_ward_mstr.ward_no,
                                            apply.application_no,                                    
                                            apply.firm_name,
                                            tbl_application_type.application_type,
                                            apply.apply_date,
                                            case when apply.emp_details_id isnull or apply.emp_details_id =0 then 'Online' else view_emp_details.emp_name end as emp_name,

                                            concat('<a class=', chr(39),'btn btn-primary', chr(39), 'href=" . base_url() . "/tradeapplylicence/trade_licence_view/',md5(apply.id::text),' role=button>View</a>') 
                                            as view
                                            ";
                }

                $sql =  " FROM tbl_apply_licence apply  
                                        INNER JOIN tbl_application_type_mstr tbl_application_type ON tbl_application_type.id=apply.application_type_id                      
                                        left JOIN view_emp_details ON view_emp_details.id=apply.emp_details_id
                                        INNER JOIN view_ward_mstr ON view_ward_mstr.id=apply.ward_mstr_id                        
                                        WHERE apply.status>0 and apply.update_status=0 and apply.application_type_id=" . $application_type_id
                                        ." AND apply.apply_date BETWEEN '$form_date' AND '$upto_date' ";
                $totalRecords = $this->model_datatable->getTotalRecords($sql);

                // return json_encode([$totalRecords]);
                if ($totalRecords > 0) {
                    ## Total number of records with filtering
                    $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($sql . $whereQueryWithSearch);
                    ## Fetch records
                    $fetchSql = $selectStatement . $sql . $whereQueryWithSearch . $orderBY . $limit;

                    $records = $this->model_datatable->getRecords($fetchSql);
                    //return json_encode($records);
                } else {
                    $totalRecordwithFilter = 0;
                    $records = [];
                }
                //echo $this->db->getLastQuery();
                $response = array(
                    "iTotalRecords" => $totalRecords,
                    "iTotalDisplayRecords" => $totalRecordwithFilter,
                    "aaData" => $records
                );
                return json_encode($response);
            } catch (Exception $e) {
            }
        }
    }

    public function newapplicationlistrequestAjax()
    {

        if ($this->request->getMethod() == 'post') {

            try {

                ## Read value
                $start = sanitizeString($this->request->getVar('start'));

                $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page

                $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
                $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
                if ($columnName == "s_no")
                    $columnName = 'apply.id';
                if ($columnName == "application_no")
                    $columnName = 'apply.application_no';
                if ($columnName == "ward_no")
                    $columnName = 'view_ward_mstr.ward_no';
                if ($columnName == "license_no")
                    $columnName = 'apply.license_no';
                if ($columnName == "firm_name")
                    $columnName = 'apply.firm_name';
                if ($columnName == "application_type")
                    $columnName = 'tbl_application_type.application_type';
                if ($columnName == "apply_date")
                    $columnName = 'apply.apply_date';
                if ($columnName == "apply_by")
                    $columnName = 'view_emp_details.name';
                if ($columnName == "view")
                    $columnName = 'view';

                //$columnName = "tbl_transaction.tran_date";
                $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
                $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value

                $searchQuery = "";
                $whereQuery = "";

                // Date filter
                $from_date = sanitizeString($this->request->getVar('from_date'));
                $to_date = sanitizeString($this->request->getVar('to_date'));
                $ward_id = sanitizeString($this->request->getVar('ward_id'));
                $application_type_id = sanitizeString($this->request->getVar('application_type_id'));
                $status = 1;
                //for jsk 
                if ($application_type_id == "doc_0") {
                    $document_upload_status  = "0";
                    if ($ward_id == "all") {
                        $whereQuery .= "    apply.apply_date >=  '" . $from_date . "'";
                        $whereQuery .= "  AND  apply.apply_date <=  '" . $to_date . "'";
                        $whereQuery .= "  AND   apply.document_upload_status =  '" . $document_upload_status . "'";
                        $whereQuery .= "  AND   apply.status =  '" . $status . "'";
                    } else {
                        $whereQuery .= "    apply.apply_date >=  '" . $from_date . "'";
                        $whereQuery .= "  AND  apply.apply_date <=  '" . $to_date . "'";
                        $whereQuery .= "  AND   apply.ward_mstr_id =  '" . $ward_id . "'";
                        $whereQuery .= "  AND   apply.document_upload_status =  '" . $document_upload_status . "'";
                        $whereQuery .= "  AND   apply.status =  '" . $status . "'";
                    }
                } elseif ($application_type_id == "all") {
                    $payment_status  = "apply.payment_status in (0,1)";
                    $application_type_id  = "apply.application_type_id in (1,2,3,4)";
                    if ($ward_id == "all") {
                        $whereQuery .= "    apply.apply_date >=  '" . $from_date . "'";
                        $whereQuery .= "  AND  apply.apply_date <=  '" . $to_date . "'";
                        $whereQuery .= "  AND   apply.status =  '" . $status . "'";
                        $whereQuery .= "  AND   $payment_status";
                        $whereQuery .= "  AND   $application_type_id";
                    } else {
                        $whereQuery .= "    apply.apply_date >=  '" . $from_date . "'";
                        $whereQuery .= "  AND  apply.apply_date <=  '" . $to_date . "'";
                        $whereQuery .= "  AND   apply.ward_mstr_id =  '" . $ward_id . "'";
                        $whereQuery .= "  AND   apply.status =  '" . $status . "'";
                        $whereQuery .= "  AND   $payment_status";
                        $whereQuery .= "  AND   $application_type_id";
                    }
                } elseif ($application_type_id == "4") {
                    $payment_status  = "0";
                    if ($ward_id == "all") {
                        $whereQuery .= "    apply.apply_date >=  '" . $from_date . "'";
                        $whereQuery .= "  AND  apply.apply_date <=  '" . $to_date . "'";
                        $whereQuery .= "  AND   apply.payment_status =  '" . $payment_status . "'";
                        $whereQuery .= "  AND   apply.application_type_id =  '" . $application_type_id . "'";
                        $whereQuery .= "  AND   apply.status =  '" . $status . "'";
                    } else {
                        $whereQuery .= "    apply.apply_date >=  '" . $from_date . "'";
                        $whereQuery .= "  AND  apply.apply_date <=  '" . $to_date . "'";
                        $whereQuery .= "  AND   apply.ward_mstr_id =  '" . $ward_id . "'";
                        $whereQuery .= "  AND   apply.payment_status =  '" . $payment_status . "'";
                        $whereQuery .= "  AND   apply.application_type_id =  '" . $application_type_id . "'";
                        $whereQuery .= "  AND   apply.status =  '" . $status . "'";
                    }
                } else {
                    $payment_status  = "1";
                    if ($ward_id == "all") {
                        $whereQuery .= "    apply.apply_date >=  '" . $from_date . "'";
                        $whereQuery .= "  AND  apply.apply_date <=  '" . $to_date . "'";
                        $whereQuery .= "  AND   apply.application_type_id =  '" . $application_type_id . "'";
                        $whereQuery .= "  AND   apply.payment_status =  '" . $payment_status . "'";
                        $whereQuery .= "  AND   apply.status =  '" . $status . "'";
                    } else {
                        $whereQuery .= "    apply.apply_date >=  '" . $from_date . "'";
                        $whereQuery .= "  AND  apply.apply_date <=  '" . $to_date . "'";
                        $whereQuery .= "  AND   apply.ward_mstr_id =  '" . $ward_id . "'";
                        $whereQuery .= "  AND   apply.application_type_id =  '" . $application_type_id . "'";
                        $whereQuery .= "  AND   apply.payment_status =  '" . $payment_status . "'";
                        $whereQuery .= "  AND   apply.status =  '" . $status . "'";
                    }
                }

                $orderBY = " ORDER BY " . $columnName . " " . $columnSortOrder;
                $limit = " LIMIT " . $rowperpage . " OFFSET " . $start;

                $whereQueryWithSearch = "";
                if ($searchValue != '') {

                    $whereQueryWithSearch = " AND (apply.application_no ILIKE '%" . $searchValue . "%'
                    OR view_ward_mstr.ward_no ILIKE '%" . $searchValue . "%'
                    OR apply.firm_name ILIKE '%" . $searchValue . "%'
                    OR view_emp_details.emp_name ILIKE '%" . $searchValue . "%')";
                }
                $selectStatement = "SELECT 
                ROW_NUMBER () OVER (ORDER BY " . $columnName . " DESC) AS s_no,
                    view_ward_mstr.ward_no,
                    apply.application_no,                                    
                    apply.firm_name,
                    apply.license_no,
                    tbl_application_type.application_type,
                    apply.apply_date,
                    apply.apply_from,
                    concat('<a class=', chr(39),'btn btn-primary', chr(39), 'href=" . base_url() . "/tradeapplylicence/trade_licence_view/',md5(apply.id::text),' role=button>View</a>') 
                    as view
                    ";


                $sql =  " FROM tbl_apply_licence apply  
                    INNER JOIN tbl_application_type_mstr tbl_application_type ON tbl_application_type.id=apply.application_type_id                      
                    INNER JOIN view_ward_mstr ON view_ward_mstr.id=apply.ward_mstr_id                        
                    WHERE " . $whereQuery;
                //'<a class='btn btn-primary' href='". echo base_url('tradeapplylicence/trade_licence_view/'.md5("apply.id"))."' role='button'>View</a>' as view
                //  return json_encode($selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit);
                ## Total number of records without filtering
                //return json_encode($sql);   
                $totalRecords = $this->model_datatable->getTotalRecords($sql);
                // return json_encode([$totalRecords]);
                if ($totalRecords > 0) {
                    ## Total number of records with filtering
                    $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($sql . $whereQueryWithSearch);
                    ## Fetch records
                    $fetchSql = $selectStatement . $sql . $whereQueryWithSearch . $orderBY . $limit;

                    $records = $this->model_datatable->getRecords($fetchSql);
                    //return json_encode($records);
                } else {
                    $totalRecordwithFilter = 0;
                    $records = [];
                }
                //echo $this->db->getLastQuery();
                $response = array(
                    "iTotalRecords" => $totalRecords,
                    "iTotalDisplayRecords" => $totalRecordwithFilter,
                    "aaData" => $records
                );
                return json_encode($response);
            } catch (Exception $e) {
            }
        }
    }


    public function viewTransactionReceipt_copy($transaction_id = null)
    {

        $data = array();
        $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
        $data['transaction_id'] = $this->TradeTransactionModel->getTransaction_ID($transaction_id);
        $applyid = md5($data['transaction_id']['related_id']);
        $transaction_id = md5($data['transaction_id']['id']);
        $path = base_url('citizenPaymentReceipt/view_trade_transaction_receipt/' . $ulb_mstr_id . '/' . $applyid . '/' . $transaction_id);
        $data['ss'] = qrCodeGeneratorFun($path);
        $data['transaction_id'] = $transaction_id;
        $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
        $data['ulb_mstr_name'] = $this->modelUlb->getulb_list($ulb_mstr_id);
        $data['applicant_details'] = $this->TradeApplyLicenceModel->fetch_all_application_data($applyid);
        $data['transaction_details'] = $this->TradeTransactionModel->transaction_details($transaction_id);
        $warddet = $this->model_ward_mstr->getWardNoBywardId($data['transaction_details']['ward_mstr_id']);
        $data['ward_no'] = $warddet["ward_no"];
        $data['status'] = [1, 2];
        $data['rebet'] = $this->TradeTransactionModel->getRebetDetails($transaction_id);
        $data['delayApplyLicence'] = $data['rebet']['0']['amount'];
        $data['denialApply'] = $data['rebet']['1']['amount'];
        if ($data['transaction_details']['collected_by'] !== "online") {
            $data['cheque_details'] = $this->TradeChequeDtlModel->alltransactioncheque_details($transaction_id);
        }
        $data["level_id"] = $this->model_trade_level_pending_dtl->get_level_id($applyid);
        //print_var($data['delayApplyLicence']);
        return view('trade/Connection/transactionReceipt', $data);
    }

    public function viewTransactionReceipt($transaction_id = null)
    {
        return redirect()->to(base_url('TradeCitizen/viewTransactionReceipt/' . $transaction_id));
    }

    public function provisionalCertificate($id = null)
    {
        $data = (array)null;
        $Session = Session();
        date_default_timezone_set('Asia/Kolkata');
        $ulb_mstr = $Session->get("ulb_dtl");
        $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
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
        $data["valid_upto"] = date('Y-m-d', strtotime(date("$vUpto", mktime(time())) . " + 20 day"));
        $apply_licence_id = $data['basic_details']['id'];
        //$data['item_details'] = $this->TradeTradeItemsModel->getdatabyid_md5($id);
        $data['tranProvDtl'] = $this->TradeTransactionModel->tranProvDtl($id);
        return view('trade/Connection/provitionalLicence', $data);
    }

    //licence status 
    public function licence_statusAjax()
    {
        if ($this->request->getMethod() == 'post') {
            try {
                ## Read value
                $start = sanitizeString($this->request->getVar('start'));
                $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page
                $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
                $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
                if ($columnName == "s_no")
                    $columnName = 'apply.id';
                if ($columnName == "application_no")
                    $columnName = 'apply.application_no';
                if ($columnName == "ward_no")
                    $columnName = 'view_ward_mstr.ward_no';
                if ($columnName == "license_no")
                    $columnName = 'apply.license_no';
                if ($columnName == "firm_name")
                    $columnName = 'apply.firm_name';
                if ($columnName == "application_type")
                    $columnName = 'tbl_application_type.application_type';
                if ($columnName == "apply_date")
                    $columnName = 'apply.apply_date';
                if ($columnName == "apply_by")
                    $columnName = 'apply.apply_from';
                if ($columnName == "view")
                    $columnName = 'view';

                //$columnName = "tbl_transaction.tran_date";
                $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
                $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value

                // Date filter
                $from_date = sanitizeString($this->request->getVar('from_date'));
                $to_date = sanitizeString($this->request->getVar('to_date'));
                $ward_id = sanitizeString($this->request->getVar('ward_id'));
                $pending_status = sanitizeString($this->request->getVar('status'));;
                $searchQuery = "";
                $whereQuery = "";

                if ($pending_status == 0) {
                    if ($ward_id == "all") {
                        $whereQuery .= "    apply.apply_date >=  '" . $from_date . "'";
                        $whereQuery .= "  AND  apply.apply_date <=  '" . $to_date . "'";
                        $whereQuery .= "  AND  apply.pending_status not in (2,4,5)";
                        $whereQuery .= "  AND  apply.document_upload_status = 1";
                    } else {
                        $whereQuery .= "    apply.apply_date >=  '" . $from_date . "'";
                        $whereQuery .= "  AND  apply.apply_date <=  '" . $to_date . "'";
                        $whereQuery .= "  AND   apply.ward_mstr_id =  '" . $ward_id . "'";
                        $whereQuery .= "  AND  apply.pending_status not in (2,4,5)";
                        $whereQuery .= "  AND  apply.document_upload_status = 1";
                    }
                } else {
                    if ($ward_id == "all") {
                        $whereQuery .= "    apply.apply_date >=  '" . $from_date . "'";
                        $whereQuery .= "  AND  apply.apply_date <=  '" . $to_date . "'";
                        $whereQuery .= "  AND  apply.pending_status =  '" . $pending_status . "'";
                    } else {
                        $whereQuery .= "    apply.apply_date >=  '" . $from_date . "'";
                        $whereQuery .= "  AND  apply.apply_date <=  '" . $to_date . "'";
                        $whereQuery .= "  AND   apply.ward_mstr_id =  '" . $ward_id . "'";
                        $whereQuery .= "  AND  apply.pending_status =  '" . $pending_status . "'";
                    }
                }


                $orderBY = " ORDER BY " . $columnName . " " . $columnSortOrder;
                $limit = " LIMIT " . $rowperpage . " OFFSET " . $start;
                $whereQueryWithSearch = "";
                if ($searchValue != '') {

                    $whereQueryWithSearch = " AND (apply.application_no ILIKE '%" . $searchValue . "%'
                        OR view_ward_mstr.ward_no ILIKE '%" . $searchValue . "%'
                        OR apply.firm_name ILIKE '%" . $searchValue . "%'
                        OR apply.apply_from ILIKE '%" . $searchValue . "%')";
                }

                $selectStatement = "SELECT 
                    ROW_NUMBER () OVER (ORDER BY " . $columnName . " DESC) AS s_no,
                        view_ward_mstr.ward_no,
                        apply.application_no,                                    
                        apply.firm_name,
                        apply.license_no,
                        tbl_application_type.application_type,
                        apply.apply_date,
                        apply.apply_from,
                        concat('<a class=', chr(39),'btn btn-primary', chr(39), 'href=" . base_url() . "/tradeapplylicence/trade_licence_view/',md5(apply.id::text),' role=button>View</a>') 
                        as view
                        ";

                $sql =  "FROM tbl_apply_licence apply  
                        INNER JOIN tbl_application_type_mstr tbl_application_type ON tbl_application_type.id=apply.application_type_id                      
                        INNER JOIN view_ward_mstr ON view_ward_mstr.id=apply.ward_mstr_id 
                        WHERE" . $whereQuery;
                //'<a class='btn btn-primary' href='". echo base_url('tradeapplylicence/trade_licence_view/'.md5("apply.id"))."' role='button'>View</a>' as view
                //  return json_encode($selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit);
                ## Total number of records without filtering
                //return json_encode($sql);   
                $totalRecords = $this->model_datatable->getTotalRecords($sql);
                // return json_encode([$totalRecords]);
                if ($totalRecords > 0) {
                    ## Total number of records with filtering
                    $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($sql . $whereQueryWithSearch);
                    ## Fetch records
                    $fetchSql = $selectStatement . $sql . $whereQueryWithSearch . $orderBY . $limit;

                    $records = $this->model_datatable->getRecords($fetchSql);
                    //return json_encode($records);
                } else {
                    $totalRecordwithFilter = 0;
                    $records = [];
                }
                //echo $this->db->getLastQuery();
                $response = array(
                    "iTotalRecords" => $totalRecords,
                    "iTotalDisplayRecords" => $totalRecordwithFilter,
                    "aaData" => $records
                );
                return json_encode($response);
            } catch (Exception $e) {
            }
        }
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
                    return view('trade/Connection/SearchLicense', $data);
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
                                            return $this->response->redirect(base_url('tradeapplylicence/applynewlicence/' . $apptypeid . '/' . $id));
                                        } else {
                                            //print_var($licensedata);
                                            $data["msg"] = "Application No. $licensedata[application_no] is applied for surrender against License No. $licensedata[license_no] which is not approved yet.";
                                        }
                                    } else {
                                        return $this->response->redirect(base_url('tradeapplylicence/applynewlicence/' . $apptypeid . '/' . $id));
                                    }
                                }
                            } else {
                                if (is_null($licensedata["valid_upto"]) && $licensedata["update_status"] && $licensedata["pending_status"] != 5) {

                                    $data["msg"] = "Already Applied! Please Track Status of Application No." . $licensedata["application_no"];
                                } else if ($licensedata['valid_upto'] <= date('Y-m-d')) {
                                    $data["msg"] = "License No. $licensedata[license_no] is valid till $licensedata[valid_upto], which has expired. Therefore, please apply for renewal before surrender.";
                                } else {
                                    return $this->response->redirect(base_url('tradeapplylicence/applynewlicence/' . $apptypeid . '/' . $id));
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

            return view('trade/Connection/SearchLicense', $data);
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
                    return view('trade/Connection/SearchLicense', $data);
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
                    return $this->response->redirect(base_url('tradeapplylicence/applynewlicence/' . $apptypeid . '/' . $id));
                }
                
            }

            return view('trade/Connection/SearchLicense', $data);
        }
    }

    public function applynewlicence($apptypeid = null, $id = null)
    {
        $arr_application_type = [
            "c4ca4238a0b923820dcc509a6f75849b" => 1,
            "c81e728d9d4c2f636f067f89cc14862c" => 2,
            "eccbc87e4b5ce2fe28308fd9f2a7baf3" => 3,
            "a87ff679a2f3e71d9181a67b7542122c" => 4
        ];
        $apptype_id = $arr_application_type[$apptypeid];
        $data = array();
        $session = session();
        $get_ulb_id = $session->get('ulb_dtl');
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
        $data['ward_list'] = $this->model_ward_mstr->getWardList($get_ulb_id);
        $data["application_type"] = $this->tradeapplicationtypemstrmodel->getdatabyid($apptype_id);
        $data["firmtypelist"] = $this->tradefirmtypemstrmodel->getFirmTypeList();
        $data["ownershiptypelist"] = $this->tradeownershiptypemstrmodel->getownershipTypeList();
        // $data["tradeitemlist"] = $this->tradeitemsmstrmodel->gettradeitemsList();

        if ($apptypeid <> null and $id <> null) {
            $data["application_type"] = $this->tradeapplicationtypemstrmodel->getdatabymd5id($apptypeid);
            $data["application_type_id"] = $data["application_type"]["id"];
            $data["licencedet"] = $this->TradeApplyLicenceModel->apply_licence_md5($id);
            // print_var($data['licencedet']);die();

            $data["tobacco_status"] = $data["licencedet"]["tobacco_status"];
            $data['ward_no'] = $this->model_ward_mstr->getWardNoById($data["licencedet"]);
            $data['ward_id'] = $data["licencedet"]['ward_mstr_id'];
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
            $data["firm_name"] = $data["licencedet"]["firm_name"];
            $data["area_in_sqft"] = (float)$data["licencedet"]["area_in_sqft"];
            $data["firm_date"] = $data["licencedet"]["establishment_date"];
            $data["address"] = $data["licencedet"]["address"];
            $data["landmark"] = $data["licencedet"]["landmark"];
            $data["pin_code"] = $data["licencedet"]["pin_code"];
            $data['apply_licence_id'] = $data["licencedet"]["id"];
            $data["tradeitemdet"] = explode(',', $data["licencedet"]["nature_of_bussiness"]);
            $data['nature_of_bussiness'] = $data["licencedet"]["nature_of_bussiness"];
            if ($data['application_type_id'] == 2)
                $data['validUpto'] = $data["licencedet"]["valid_upto"];
        } else {
            $data["application_type"] = $this->tradeapplicationtypemstrmodel->getdatabymd5id($apptypeid);
        }

        if ($this->request->getMethod() == "post") {


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
                    // 'pin_code' => 'required|min_length[6]|max_length[6]',
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

                $data['nature_of_bussiness'] = implode(',', $inputs['tade_item']) ?? null;
                // var_dump($data['nature_of_bussiness']);

                $data["application_type_id"] = $data["application_type"]["id"];
                $data["firm_type_id"] = $inputs['firmtype_id'];
                $data["otherfirmtype"] = $inputs['firmtype_other'];
                // $data['nature_of_bussiness']=NULL;                
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



                $data["rate_id"] = $rate_data["rate_id"];
                $data['emp_details_id'] = $emp_id;
                $data['created_on'] = date('Y-m-d H:i:s');
                $vali_upto = date('Y-m-d', strtotime(date('Y-m-d') . "+" . $data["licence_for_years"] . " years"));
                $data['valid_upto'] = $vali_upto;
                $applyid = $this->TradeApplyLicenceModel->insertapply($data);


                $owner_arr = array();
                $owner_arr['apply_licence_id'] = $applyid;
                //$owner_arr['licence_id']=$data["licencedet"]["id"];
                $owner_arr['emp_details_id'] = $emp_id;
                $owner_arr['created_on'] = date('Y-m-d H:i:s');

                $owner_for_sms = [];
                if (isset($inputs['owner_name'])) {
                    for ($i = 0; $i < sizeof($inputs['owner_name']); $i++) {
                        $owner_arr['owner_name'] = $inputs['owner_name'][$i];
                        $owner_arr['guardian_name'] = $inputs['guardian_name'][$i];
                        $owner_arr['emailid'] = $inputs['emailid'][$i];
                        $owner_arr['mobile'] = $inputs['mobile_no'][$i];
                        $this->TradeFirmOwnerModel->insertdata($owner_arr);
                        array_push($owner_for_sms, $owner_arr);
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
                    //$denialAmount=100+(($totalDays)*10);
                    $denialAmount = getDenialAmountTrade(date('Y-m-d', $notice_date), date('Y-m-d', $now));
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

                $transact_arr['penalty'] = $rate_data['penalty'] + $denialAmount + $rate_data['arear_amount'];
                if ($inputs['payment_mode'] != 'CASH') {
                    $transact_arr['status'] = 2;
                }
                $transact_arr['emp_details_id'] = $emp_id;
                $transact_arr['created_on'] = date('Y-m-d H:i:s');
                $transact_arr['ip_address'] = '';
                $transaction_id = $this->TradeTransactionModel->insertPayment($transact_arr);

                $trafinerebate = array();  // penalty insert
                $trafinerebate['transaction_id'] = $transaction_id;
                $trafinerebate['head_name'] = 'Delay Apply License';
                $trafinerebate['amount'] = $rate_data['penalty'];
                $trafinerebate['value_add_minus'] = 'Add';
                $trafinerebate['created_on'] = date('Y-m-d H:i:s');
                $this->model_trade_transaction_fine_rebet_details->fine_rebet_details($trafinerebate);
                $denialAmount = $denialAmount + $rate_data['arear_amount'];
                if ($denialAmount > 0) {
                    $denial = array();  // denial insert
                    $denial['transaction_id'] = $transaction_id;
                    $denial['head_name'] = 'Denial Apply';
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

                /**********sms send testing code *************/
                $transaction_no = $this->TradeTransactionModel->transaction_details(md5($transaction_id))['transaction_no'];
                $sms = Trade(array('ammount' => $transact_arr['paid_amount'], 'application_no' => $app_no, 'ref_no' => $transaction_no), 'Payment done');
                if ($sms['status'] == true) {
                    foreach ($owner_for_sms as $val) {
                        $message = $sms['sms'];
                        $templateid = $sms['temp_id'];
                        $sms_data = [
                            'emp_id' => $emp_id,
                            'ref_id' => $applyid,
                            'ref_type' => 'tbl_apply_licence',
                            'mobile_no' => $val['mobile'],
                            'purpose' => strtoupper('NEW LICENSE PAYMENT'),
                            'template_id' => $templateid,
                            'message' => $message
                        ];
                        $sms_id = $this->model_trade_sms_log->insert_sms_log($sms_data);
                        if ($sms_id) {
                            //$res=SMSJHGOVT("7050180186", $message, $templateid);
                            $res = send_sms($val['mobile'], $message, $templateid); //print_var($res);
                            if ($res) {
                                $update = [
                                    'response' => $res['response'],
                                    'smgid' => $res['msg'],
                                ];
                                $where = ['id' => $sms_id];
                                $update = $this->model_trade_sms_log->update_sms_log($where, $update);
                            }
                        }
                    }
                }
                // $this->db->transRollback();                    
                // print_var($sms);die; 
                /***********end sms send*********************/

                if ($this->db->transStatus() === FALSE) {
                    $this->db->transRollback();
                    flashToast("applylicence", "Something errordue to payment!!!");
                    if ($data["application_type_id"] == 1) {
                        return $this->response->redirect(base_url('tradeapplylicence/applynewlicence/' . $apptypeid));
                    } else {
                        return $this->response->redirect(base_url('tradeapplylicence/applynewlicence/' . $apptypeid . '/' . $id));
                    }
                } else {
                    $this->db->transCommit();
                    if ($data["application_type"]["id"] <> 4) {
                        return $this->response->redirect(base_url('tradeapplylicence/view_transaction_receipt/' . md5($applyid) . '/' . md5($transaction_id)));
                    } else {
                        return $this->response->redirect(base_url('tradeapplylicence/trade_licence_view/' . md5($applyid)));
                    }
                }
            }

            # Renewal
            elseif ($data["application_type"]["id"] == 2) {
                $rules = [
                    'licence_for' => 'required',
                    'payment_mode' => 'required',
                    'holding_no' => 'required',
                ];
                if (!$this->validate($rules)) {
                    $data['validation'] = $this->validator;
                    return view('trade/Connection/applylicence', $data);
                }

                $inputs = filterSanitizeStringtoUpper($this->request->getVar());

                //print_var($inputs);die();
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
                $data["brife_desp_firm"] = isset($inputs['brife_desp_firm']) && trim($inputs['brife_desp_firm']) ? trim($inputs['brife_desp_firm']) : $data["licencedet"]['brife_desp_firm'];
                $data["category_type_id"] = $data["licencedet"]["category_type_id"];
                $data["license_no"] = $data["licencedet"]["license_no"];
                $data["provisional_license_no"] = $data["licencedet"]["provisional_license_no"];
                $data["prop_dtl_id"] = isset($inputs['prop_id']) && !empty($inputs['prop_id']) ? $inputs['prop_id'] : $data["licencedet"]["prop_dtl_id"];
                $data["holding_no"] = isset($inputs['holding_no']) && !empty($inputs['holding_no']) ? $inputs['holding_no'] : $data["licencedet"]["holding_no"];
                $priv_m_d = date('m-d', strtotime($data["valid_from"]));
                $date = date('Y') . '-' . $priv_m_d;
                $vali_upto = date('Y-m-d', strtotime($date . "+" . $data["licence_for_years"] . " years"));
                $data['valid_upto'] = $vali_upto;
                $denialAmount = 0;
                //$test_deta =re_day_diff($inputs['firm_date'],$inputs['licence_for']);
                //print_var($test_deta); die;
                // if($test_deta['diff_day']<0)
                // {
                //     $day = $test_deta['diff_day']*-1;                       
                //     flashToast("applylicence", "Please select more $day Days!!!");
                //     return $this->response->redirect(base_url('tradeapplylicence/applynewlicence/'.$apptypeid.'/'.$id));
                //     //return $this->response->redirect(base_url('TradeCitizen/applynewlicence/'.$apptypeid.'/'.$id));
                // }
                # Calculating Rate
                {
                    $args['areasqft'] = $data["licencedet"]["area_in_sqft"];
                    $args['applytypeid'] = $data["application_type"]["id"];
                    $args['estdate'] = $inputs["firm_date"];
                    $args['tobacco_status'] = $data["licencedet"]["tobacco_status"];
                    $args['licensefor'] = $inputs["licence_for"];
                    $args['nature_of_business'] = $data["nature_of_bussiness"];
                    $rate_data = $this->getcharge($args);
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

                $transact_arr['penalty'] = $rate_data['penalty'] + $rate_data['arear_amount'];
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

                $denialAmount = $denialAmount + $rate_data['arear_amount'];
                if ($denialAmount > 0) {
                    $denial = array();  // denial insert
                    $denial['transaction_id'] = $transaction_id;
                    $denial['head_name'] = 'Denial Apply';
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

                # Update Application No
                // $app_no = "APPLIC" . $applyid . date('dmyhis');
                
                $warddet = $this->model_ward_mstr->getWardNoBywardId($data["ward_mstr_id"]);
                $ward_no = $warddet["ward_no"];
                $prov_no = $short_ulb_name . $ward_no . date('mdy') . $applyid;
                $this->TradeApplyLicenceModel->update_prov_no($applyid, $prov_no);
                $app_no = "APN".str_pad(substr($ward_no,0,2),2,"0",STR_PAD_LEFT).STR_PAD($applyid,7,"0",STR_PAD_LEFT);
                $this->TradeApplyLicenceModel->update_application_no($app_no, $applyid, $payment_status);

                /**********sms send testing code *************/
                $owner_for_sms = $this->TradeFirmOwnerModel->getdatabyid_md5(md5($applyid));
                $transaction_no = $this->TradeTransactionModel->transaction_details(md5($transaction_id))['transaction_no'];
                $sms = Trade(array('ammount' => $transact_arr['paid_amount'], 'application_no' => $app_no, 'ref_no' => $transaction_no), 'Payment done');
                if ($sms['status'] == true) {
                    foreach ($owner_for_sms as $val) {
                        $message = $sms['sms'];
                        $templateid = $sms['temp_id'];
                        $sms_data = [
                            'emp_id' => $emp_id,
                            'ref_id' => $applyid,
                            'ref_type' => 'tbl_apply_licence',
                            'mobile_no' => $val['mobile'],
                            'purpose' => 'RENEWAL PAYMENT',
                            'template_id' => $templateid,
                            'message' => $message
                        ];
                        $sms_id = $this->model_trade_sms_log->insert_sms_log($sms_data);
                        if ($sms_id) {
                            //$res=SMSJHGOVT("7050180186", $message, $templateid);
                            $res = send_sms($val['mobile'], $message, $templateid); //print_var($res);
                            if ($res) {
                                $update = [
                                    'response' => $res['response'],
                                    'smgid' => $res['msg'],
                                ];
                                $where = ['id' => $sms_id];
                                $update = $this->model_trade_sms_log->update_sms_log($where, $update);
                            }
                        }
                    }
                }
                // $this->db->transRollback();                    
                // print_var($sms);die; 
                /***********end sms send*********************/

                if ($this->db->transStatus() === FALSE) {
                    $this->db->transRollback();
                    flashToast("applylicence", "Something errordue to payment!!!");
                    if ($data["application_type_id"] == 1) {
                        return $this->response->redirect(base_url('tradeapplylicence/applynewlicence/' . $apptypeid));
                    } else {
                        return $this->response->redirect(base_url('tradeapplylicence/applynewlicence/' . $apptypeid . '/' . $id));
                    }
                } else {
                    $this->db->transCommit();
                    if ($data["application_type"]["id"] <> 4) {
                        return $this->response->redirect(base_url('tradeapplylicence/view_transaction_receipt/' . md5($applyid) . '/' . md5($transaction_id)));
                    } else {
                        return $this->response->redirect(base_url('tradeapplylicence/trade_licence_view/' . md5($applyid)));
                    }
                }
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
                //print_var($inputs);die;
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

                $vali_upto = date('Y-m-d', strtotime(date('Y-m-d') . "+" . $data["licence_for_years"] . " years"));
                $data['valid_upto'] = $vali_upto;

                # Calculating Rate
                {
                    $args['areasqft'] = $inputs["area_in_sqft"];
                    $args['applytypeid'] = 3;
                    $args['estdate'] = date('Y-m-d');
                    $args['tobacco_status'] = $data["licencedet"]["tobacco_status"];
                    $args['licensefor'] = $inputs["licence_for"];
                    $args['nature_of_business'] = $data["nature_of_bussiness"];
                    $rate_data = $this->getcharge($args);
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
                $denialAmount = 0;
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

                $transact_arr['penalty'] = $rate_data['penalty'] + $rate_data['arear_amount'];
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

                $denialAmount = $denialAmount + $rate_data['arear_amount'];
                if ($denialAmount > 0) {
                    $denial = array();  // denial insert
                    $denial['transaction_id'] = $transaction_id;
                    $denial['head_name'] = 'Denial Apply';
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

                # Update Application No
                // $app_no = "APPLIC" . $applyid . date('dmyhis');

                 
                $app_no = "APN".str_pad(substr($ward_no,0,2),2,"0",STR_PAD_LEFT).STR_PAD($applyid,7,"0",STR_PAD_LEFT);

                $this->TradeApplyLicenceModel->update_application_no($app_no, $applyid, $payment_status);

                /**********sms send testing code *************/
                $owner_for_sms = $this->TradeFirmOwnerModel->getdatabyid_md5(md5($applyid));
                $transaction_no = $this->TradeTransactionModel->transaction_details(md5($transaction_id))['transaction_no'];
                $sms = Trade(array('ammount' => $transact_arr['paid_amount'], 'application_no' => $app_no, 'ref_no' => $transaction_no), 'Payment done');
                if ($sms['status'] == true) {
                    foreach ($owner_for_sms as $val) {
                        $message = $sms['sms'];
                        $templateid = $sms['temp_id'];
                        $sms_data = [
                            'emp_id' => $emp_id,
                            'ref_id' => $applyid,
                            'ref_type' => 'tbl_apply_licence',
                            'mobile_no' => $val['mobile'],
                            'purpose' => 'AMENDMENT PAYMENT',
                            'template_id' => $templateid,
                            'message' => $message
                        ];
                        $sms_id = $this->model_trade_sms_log->insert_sms_log($sms_data);
                        if ($sms_id) {
                            //$res=SMSJHGOVT("7050180186", $message, $templateid);
                            $res = send_sms($val['mobile'], $message, $templateid); //print_var($res);
                            if ($res) {
                                $update = [
                                    'response' => $res['response'],
                                    'smgid' => $res['msg'],
                                ];
                                $where = ['id' => $sms_id];
                                $update = $this->model_trade_sms_log->update_sms_log($where, $update);
                            }
                        }
                    }
                }
                // $this->db->transRollback();                    
                // print_var($sms);die; 
                /***********end sms send*********************/

                if ($this->db->transStatus() === FALSE) {
                    $this->db->transRollback();
                    flashToast("applylicence", "Something errordue to payment!!!");
                    if ($data["application_type_id"] == 1) {
                        return $this->response->redirect(base_url('tradeapplylicence/applynewlicence/' . $apptypeid));
                    } else {
                        return $this->response->redirect(base_url('tradeapplylicence/applynewlicence/' . $apptypeid . '/' . $id));
                    }
                } else {
                    $this->db->transCommit();
                    if ($data["application_type"]["id"] <> 4) {
                        return $this->response->redirect(base_url('tradeapplylicence/view_transaction_receipt/' . md5($applyid) . '/' . md5($transaction_id)));
                    } else {
                        return $this->response->redirect(base_url('tradeapplylicence/trade_licence_view/' . md5($applyid)));
                    }
                }
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
                $data["brife_desp_firm"] = $data["licencedet"]['brife_desp_firm'];
                $data["valid_from"] = date('Y-m-d');
                $data["valid_upto"] = $data["licencedet"]["valid_upto"];
                $data["license_no"] = $data["licencedet"]["license_no"];
                $data["payment_status"] = 1; // No Payment in case of surrender
                $data["rate_id"] = 16; // 0 Rs Payment in surrender as tbl_licence_rate
                $data['emp_details_id'] = $emp_id;
                $data['created_on'] = date('Y-m-d H:i:s');

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

                // $app_no = "APPLIC" . $applyid . date('dmyhis');
                
                $warddet = $this->model_ward_mstr->getWardNoBywardId($data["ward_mstr_id"]);
                $ward_no = $warddet["ward_no"];
                
                $app_no = "APN".str_pad(substr($ward_no,0,2),2,"0",STR_PAD_LEFT).STR_PAD($applyid,7,"0",STR_PAD_LEFT);
                $this->TradeApplyLicenceModel->update_application_no($app_no, $applyid, $data["payment_status"]);

                if ($this->db->transStatus() === FALSE) {
                    $this->db->transRollback();
                    flashToast("applylicence", "Something error due to payment!!!");
                    if ($data["application_type_id"] == 1) {
                        return $this->response->redirect(base_url('tradeapplylicence/applynewlicence/' . $apptypeid));
                    } else {
                        return $this->response->redirect(base_url('tradeapplylicence/applynewlicence/' . $apptypeid . '/' . $id));
                    }
                } else {
                    $this->db->transCommit();
                    if ($data["application_type"]["id"] <> 4) {
                        return $this->response->redirect(base_url('tradeapplylicence/view_transaction_receipt/' . md5($applyid) . '/' . md5($transaction_id)));
                    } else {
                        return $this->response->redirect(base_url('tradeapplylicence/trade_licence_view/' . md5($applyid)));
                    }
                }
            }
        }

        $data["tradeitemlist"] = $this->tradeitemsmstrmodel->gettradeitemsList();

        // print_var($data);return;

        return view('trade/Connection/ApplyLicence', $data);
    }


    public function licencepayment($id = null)
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
        /* $data["apptypeid"] = $apptypeid;
                $data["id"] = $id;
                $data["statelist"] = $this->statemodel->getstateList();
                $data["idprooflist"] = $this->model_trade_document->getIdPoorfDocumentList();
                $data['ward_list']=$this->model_ward_mstr->getWardList($get_ulb_id);
                $data["application_type"] = $this->tradeapplicationtypemstrmodel->getdatabymd5id($apptypeid);    
                $data["firmtypelist"] = $this->tradefirmtypemstrmodel->getFirmTypeList(); 
                $data["ownershiptypelist"] = $this->tradeownershiptypemstrmodel->getownershipTypeList(); */
        // $data["tradeitemlist"] = $this->tradeitemsmstrmodel->gettradeitemsList();

        if ($id <> null) {
            //Check Licence Number Is Debarred Or Not
            if ($debarred = $this->model_trade_licence->checkDebarredLicence($id)) {
                $data['debarredDetails'] = "This Licence Is Debarred";
            }
            /*die();*/
            /*  $data["application_type"] = $this->tradeapplicationtypemstrmodel->getdatabymd5id($apptypeid);
            $data["application_type_id"]=$data["application_type"]["id"]; */
            $data["licencedet"] = $this->TradeApplyLicenceModel->apply_licence_md5($id);
            // print_r($data["licencedet"]);

            //print_r($data["validitydet"]);
            $data['ward_no'] = $this->model_ward_mstr->getWardNoById($data["licencedet"]);
            $data['new_ward_no'] = $this->model_ward_mstr->getWardNoBywardId($data["licencedet"]["new_ward_mstr_id"]);
            $data["ownerdet"] = $this->TradeFirmOwnerModel->getdatabyid_md5($id);
            //$data["tradeitemdet"] = $this->model_trade_view_licence_trade_items->get_details($id);            
            $data["application_type"] = $this->tradeapplicationtypemstrmodel->getdatabyid($data["licencedet"]["application_type_id"]);
            if ($data["licencedet"]["application_type_id"] > 1) {
                $data["licencelastdet"] = $this->TradeApplyLicenceModel->apply_licence_last($data["licencedet"]['id']);
            }
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
            $data["tobacco_status"] = $data["licencedet"]["tobacco_status"];;
        }


        // $safdet = $this->model_saf_dtl->getSafDtlBySafno($data);

        if ($this->request->getMethod() == "post") {
            $this->db->transBegin();
            /* echo $_SERVER['REMOTE_ADDR'];
                die();*/

            $rules = [
                'licence_for' => 'required',
                'payment_mode' => 'required',
            ];




            if (!$this->validate($rules)) {

                $data['validation'] = $this->validator;

                return view('trade/Connection/applylicence', $data);
            } else {
                $inputs = filterSanitizeStringtoUpper($this->request->getVar());

                $data["application_type_id"] = $data["application_type"]["id"];
                $data["licence_for_years"] = $inputs['licence_for'];
                $data["otherfirmtype"] = $inputs['firmtype_other'];
                $penalty = 0;
                $applyid = $data["licencedet"]["id"];



                $date1 = $inputs['firm_date'];
                $date2 = date('Y-m-d');

                $vDiff = abs(strtotime($date2) - strtotime($date1)); // here abs in case theres a mix in the dates
                $vMonths = ceil($vDiff / (30 * 60 * 60 * 24)); // number of seconds in a month of 30 days
                if ($vMonths > 0) {
                    $penalty = 100 + (($vMonths) * 20);
                }





                $data["property_type"] = $data["licencedet"]["property_type"];


                $data["licence_id"] = $data["licencedet"]["id"];
                $ratedet = $this->tradelicenceratemodel->getrate($data);
                $total_charge = $ratedet["rate"] * $data["licence_for_years"];

                $total_charge = $total_charge + $penalty;



                $data["rate_id"] = $ratedet["id"];
                $data['emp_details_id'] = $emp_id;
                $data['created_on'] = date('Y-m-d H:i:s');


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
                $transact_arr['ip_address'] = '';

                $transaction_id = $this->TradeTransactionModel->insertPayment($transact_arr);
                //echo $transaction_id;
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

                $warddet = $this->model_ward_mstr->getWardNoBywardId($data["ward_mstr_id"]);
                $ward_no = $warddet["ward_no"];

                $data['ward_count'] = $this->TradeApplyLicenceModel->count_ward_by_wardid($data["ward_mstr_id"]);
                $sl_no = $data['ward_count']['ward_cnt'];
                $sl_noo = $sl_no + 1;
                $serial_no = str_pad($sl_noo, 4, "0", STR_PAD_LEFT);
                $prov_no = $short_ulb_name . 'P' . $ward_no . $serial_no;
                $this->TradeApplyLicenceModel->update_prov_no($applyid, $prov_no);






                if ($this->db->transStatus() === FALSE) {
                    $this->db->transRollback();
                    flashToast("applylicence", "Something errordue to payment!!!");

                    return $this->response->redirect(base_url('tradeapplylicence/licencepayment/' . md5($data["application_type"]["id"])));
                } else {
                    $this->db->transCommit();

                    return $this->response->redirect(base_url('tradeapplylicence/view_transaction_receipt/' . md5($applyid) . '/' . md5($transaction_id)));
                }
            }
        }
        return view('trade/Connection/licencepayment', $data);
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
        $data['ward_list'] = $this->model_ward_mstr->getWardList($get_ulb_id);
        if($get_emp_details["user_type_mstr_id"]==5)        
        {
            $ward_list = $this->model_view_ward_permission->getPermittedWard($emp_id);
            $data['ward_list'] =  array_map(function($val){
                return["id"=>$val["ward_mstr_id"],"ward_no"=>$val['ward_no']];
            },$ward_list);
          
        }
        $data["application_type"] = $this->tradeapplicationtypemstrmodel->getdatabymd5id($apptypeid);
        $data["firmtypelist"] = $this->tradefirmtypemstrmodel->getFirmTypeList();
        $data["ownershiptypelist"] = $this->tradeownershiptypemstrmodel->getownershipTypeList();
        // $data["tradeitemlist"] = $this->tradeitemsmstrmodel->gettradeitemsList();

        if ($apptypeid <> null and $id <> null) {
            //Check Licence Number Is Debarred Or Not
            if ($debarred = $this->model_trade_licence->checkDebarredLicence($id)) {
                $data['debarredDetails'] = "This Licence Is Debarred";
            }
            /*die();*/
            $data["application_type"] = $this->tradeapplicationtypemstrmodel->getdatabymd5id($apptypeid);
            $data["application_type_id"] = $data["application_type"]["id"];
            $data["licencedet"] = $this->TradeApplyLicenceModel->apply_licence_md5($id);
            // print_r($data["licencedet"]);
            // print_var($data["licencedet"]);

            //print_r($data["validitydet"]);
            $data['ward_no'] = $this->model_ward_mstr->getWardNoById($data["licencedet"]);
            $data['new_ward_no'] = $this->model_ward_mstr->getWardNoBywardId($data["licencedet"]["new_ward_mstr_id"]);
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
            $warddet = $this->model_ward_mstr->getWardNoBywardId($data['licencedet']['ward_mstr_id']);
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
            /* echo $_SERVER['REMOTE_ADDR'];
                die();*/
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
                    'brife_desp_firm' => 'required',
                ];
            } elseif ($data["application_type"]["id"] == 3) {
                $rules = [
                    'area_in_sqft' => 'required',
                    'payment_mode' => 'required',
                ];
            } elseif ($data["application_type"]["id"] == 2) {
                $rules = [
                    'licence_for' => 'required',
                    'payment_mode' => 'required',
                ];
            } else {
                $rules = [
                    'area_in_sqft' => 'required',

                ];
            }



            if (!$this->validate($rules)) {

                $data['validation'] = $this->validator;
                //print_var($data);die;
                return view('trade/Connection/tobaccoapplylicence', $data);
            } else {
                $inputs = filterSanitizeStringtoUpper($this->request->getVar());
                // echo '<pre>';print_r($inputs);return;
                $data["application_type_id"] = $data["application_type"]["id"];
                $data["licence_for_years"] = $inputs['licence_for'];
                $data["otherfirmtype"] = $inputs['firmtype_other'];
                $data["tobacco_status"] = 1;
                $penalty = 0;
                $data["prop_dtl_id"] = 0;
                if ($data["application_type"]["id"] == 1) {

                    $data["firm_type_id"] = $inputs['firmtype_id'];
                    $data["ownership_type_id"] = $inputs['ownership_type_id'];
                    $data["ward_mstr_id"] = $inputs['old_ward_id'];
                    $data["new_ward_mstr_id"] = $inputs['new_ward_id'];
                    $data["nature_of_bussiness"] = '185';
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
                    $data["brife_desp_firm"] = $data["licencedet"]['brife_desp_firm'];
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
                }


                $data["rate_id"] = $ratedet["id"];
                $data['emp_details_id'] = $emp_id;
                $data['created_on'] = date('Y-m-d H:i:s');

                if ($data["application_type"]["id"] == 1) {
                    $applyid = $this->TradeApplyLicenceModel->insertapply($data);
                    $data["licencedet"] = $this->TradeApplyLicenceModel->apply_licence_md5(md5($applyid));
                    $data["licence_id"] = $data["licencedet"]["id"];
                } else {
                    $applyid = $this->TradeApplyLicenceModel->insertrenewdata($data);
                    $this->TradeApplyLicenceModel->update_status_id($applyid, $data["licence_id"]);
                }

                if ($applyid) {
                    $owner_arr = array();
                    $owner_arr['apply_licence_id'] = $applyid;
                    $owner_arr['licence_id'] = $data["licencedet"]["id"];
                    $owner_arr['emp_details_id'] = $emp_id;
                    $owner_arr['created_on'] = date('Y-m-d H:i:s');
                    //print_var($inputs['tade_item']);die;
                    $tradeitem_arr = array();
                    $tradeitem_arr['apply_licence_id'] = $applyid;
                    $tradeitem_arr['licence_id'] = $data["licence_id"];
                    //$tradeitem_arr['trade_items_id']=$inputs['tade_item'][$i]; 
                    $tradeitem_arr['emp_details_id'] = $emp_id;
                    $tradeitem_arr['created_on'] = date('Y-m-d H:i:s');
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
                    if ($data["application_type"]["id"] == 1) {
                        //denialAmount calculation
                        $denialAmount = 0;
                        $denialId = $this->request->getVar('dnialID');
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
                    }
                    if ($data["application_type"]["id"] <> 4) {

                        $transact_arr = array();
                        if ($data["application_type"]["id"] == 1) {
                            $totalCharge = $total_charge + $denialAmount;
                            $transact_arr['penalty'] = $penalty + $denialAmount;
                            $transact_arr['paid_amount'] = $totalCharge;
                        } else {
                            $transact_arr['penalty'] = $penalty;
                            $transact_arr['paid_amount'] = $total_charge;
                        }
                        $transact_arr['related_id'] = $applyid;
                        $transact_arr['ward_mstr_id'] = $data["ward_mstr_id"];
                        $transact_arr['transaction_type'] = $data["application_type"]["application_type"];
                        $transact_arr['transaction_date'] = date('Y-m-d');
                        $transact_arr['penalty'] = $penalty;
                        $transact_arr['payment_mode'] = $inputs['payment_mode'];

                        if ($inputs['payment_mode'] != 'CASH') {
                            $transact_arr['status'] = 2;
                        }
                        $transact_arr['emp_details_id'] = $emp_id;
                        $transact_arr['created_on'] = date('Y-m-d H:i:s');
                        $transact_arr['ip_address'] = $ip_addres ?? null;
                        $transaction_id = $this->TradeTransactionModel->insertPayment($transact_arr);
                        //echo $transaction_id;
                        $trafinerebate = array();
                        $trafinerebate['transaction_id'] = $transaction_id;
                        $trafinerebate['head_name'] = 'Delay Apply License';
                        $trafinerebate['amount'] = $penalty;
                        $trafinerebate['value_add_minus'] = 'Add';
                        $trafinerebate['created_on'] = date('Y-m-d H:i:s');
                        $this->model_trade_transaction_fine_rebet_details->fine_rebet_details($trafinerebate);
                        if ($data["application_type"]["id"] == 1) {
                            if ($denialAmount > 0) {
                                $denial = array();  // denial insert
                                $denial['transaction_id'] = $transaction_id;
                                $denial['head_name'] = 'Denial Apply  ';
                                $denial['amount'] = $denialAmount;
                                $denial['value_add_minus'] = 'Add';
                                $denial['created_on'] = date('Y-m-d H:i:s');
                                $this->model_trade_transaction_fine_rebet_details->fine_rebet_details($denial);
                            }
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
                        $data['ward_count'] = $this->TradeApplyLicenceModel->count_ward_by_wardid($data["ward_mstr_id"]);
                        $sl_no = $data['ward_count']['ward_cnt'];
                        $sl_noo = $sl_no + 1;
                        $serial_no = str_pad($sl_noo, 4, "0", STR_PAD_LEFT);
                        $ward_nmm = str_pad($ward_no, 2, "0", STR_PAD_LEFT);
                        $prov_no = $short_ulb_name . 'P' . $ward_no . $serial_no;
                        $this->TradeApplyLicenceModel->update_prov_no($applyid, $prov_no);
                    } else {
                        $payment_status = 0;
                    }

                    $app_no = "APPLIC" . $applyid . date('dmyhis');

                    $this->TradeApplyLicenceModel->update_application_no($app_no, $applyid, $payment_status);

                    /**********sms send testing code *************/
                    if ($data["application_type"]["id"] <> 4) {
                        $owner_for_sms = $this->TradeFirmOwnerModel->getdatabyid_md5(md5($applyid));
                        $transaction_no = $this->TradeTransactionModel->transaction_details(md5($transaction_id))['transaction_no'];
                        $sms = Trade(array('ammount' => $transact_arr['paid_amount'], 'application_no' => $app_no, 'ref_no' => $transaction_no), 'Payment done');
                        if ($sms['status'] == true) {
                            foreach ($owner_for_sms as $val) {
                                $message = $sms['sms'];
                                $templateid = $sms['temp_id'];
                                $sms_data = [
                                    'emp_id' => $emp_id,
                                    'ref_id' => $applyid,
                                    'ref_type' => 'tbl_apply_licence',
                                    'mobile_no' => $val['mobile'],
                                    'purpose' => strtoupper($data["application_type"]["application_type"] . " PAYMENT"),
                                    'template_id' => $templateid,
                                    'message' => $message
                                ];
                                $sms_id = $this->model_trade_sms_log->insert_sms_log($sms_data);
                                if ($sms_id) {
                                    //$res=SMSJHGOVT("7050180186", $message, $templateid);
                                    $res = send_sms($val['mobile'], $message, $templateid); //print_var($res);
                                    if ($res) {
                                        $update = [
                                            'response' => $res['response'],
                                            'smgid' => $res['msg'],
                                        ];
                                        $where = ['id' => $sms_id];
                                        $update = $this->model_trade_sms_log->update_sms_log($where, $update);
                                    }
                                }
                            }
                        }
                        // $this->db->transRollback();                    
                        // print_var($sms);die; 
                    }
                    /***********end sms send*********************/


                    if ($this->db->transStatus() === FALSE) {
                        $this->db->transRollback();
                        flashToast("applylicence", "Something errordue to payment!!!");
                        if ($data["application_type_id"] == 1) {
                            return $this->response->redirect(base_url('tradeapplylicence/tobaccoapplynewlicence/' . $apptypeid));
                        } else {
                            return $this->response->redirect(base_url('tradeapplylicence/tobaccoapplynewlicence/' . $apptypeid . '/' . $id));
                        }
                    } else {
                        $this->db->transCommit();
                        if ($data["application_type"]["id"] <> 4) {
                            if ($session->get('emp_details.user_type_mstr_id') == 5) {
                                return $this->response->redirect(base_url('tradeapplylicenceMobile/view_transaction_receipt/' . md5($applyid) . '/' . md5($transaction_id)));
                            } else {
                                return $this->response->redirect(base_url('tradeapplylicence/view_transaction_receipt/' . md5($applyid) . '/' . md5($transaction_id)));
                            }
                        } else {
                            return $this->response->redirect(base_url('tradeapplylicence/trade_licence_view/' . md5($applyid)));
                        }
                    }
                }
            }
        }
        // echo '<pre>';print_r($data);return;
        return view('trade/Connection/tobaccoapplylicense', $data);
    }

    public function view_transaction_receipt($applyid = null, $transaction_id = null)
    {
        $data = array();
        $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
        $path = base_url('citizenPaymentReceipt/view_trade_transaction_receipt/' . $ulb_mstr_id . '/' . $applyid . '/' . $transaction_id);
        $data['ss'] = qrCodeGeneratorFun($path);
        $data['transaction_id'] = $transaction_id;
        $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];

        $data['ulb_mstr_name'] = $this->modelUlb->getulb_list($ulb_mstr_id);
        $data['applicant_details'] = $this->TradeApplyLicenceModel->fetch_all_application_data($applyid);
        $data['transaction_details'] = $this->TradeTransactionModel->transaction_details($transaction_id);
        $data['rebet'] = $this->TradeTransactionModel->getRebetDetails($transaction_id);
        // echo '<pre>';print_r($data['rebet']);return;
        $data['delayApplyLicence'] = $data['rebet']['0']['amount'];
        $data['denialApply'] = $data['rebet']['1']['amount'] ?? null;
        $warddet = $this->model_ward_mstr->getWardNoBywardId($data['transaction_details']['ward_mstr_id']);
        $data['ward_no'] = $warddet["ward_no"];
        $data['status'] = [1, 2];
        $data['cheque_details'] = $this->TradeChequeDtlModel->alltransactioncheque_details($transaction_id);
        //print_var($data['ulb_mstr_name']);
        return view('trade/Connection/payment_receipt', $data);
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

    public function validate_holding_no()
    {
        if ($this->request->getMethod() == "post") {
            $data = array();
            //print_var($_POST);return;
            $inputs = arrFilterSanitizeString($this->request->getVar());
            $propdet = $this->model_prop_dtl->propertyDetailsfortradebyHoldingNo($inputs);
            $response = ['response' => true, 'pp' => $propdet];
        } else {
            $response = ['response' => false];
        }
        return json_encode($response);
    }

    public function validate_saf_no()
    {

        if ($this->request->getMethod() == "post") {

            $data = array();
            $inputs = arrFilterSanitizeString($this->request->getVar());
            // $data['ward_id']=$inputs['ward_id'];
            $data['saf_no'] = $inputs['saf_no'];

            $safdet = $this->model_saf_dtl->getSafDtlBySafno($data);

            $data["saf_dtl_id"] = $safdet["id"];
            // print_r($count);

            // return $count;

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
            $data['curdate'] = date("Y-m-d");
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
                $diff_year = date_diff(date_create($temp2), date_create(date('Y-m-d')))->format('%R%y');
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
            $total_denial_amount = $denial_amount_month + $rate + $pre_app_amount;

            # Check If Any cheque bounce charges
            if (isset($inputs['apply_licence_id'], $inputs['apply_licence_id'])) {
                $penalty = $this->TradeTransactionModel->getChequeBouncePenalty($inputs['apply_licence_id']);
                $denial_amount_month += $penalty;
                $total_denial_amount += $penalty;
            }

            if ($count) {
                $response = ['response' => true, 'rate' => $rate, 'penalty' => $denial_amount_month, 'total_charge' => $total_denial_amount, 'rate_id' => $count['id'], 'arear_amount' => $pre_app_amount];
            } else {
                $response = ['response' => false];
            }
            //echo $count;
            return json_encode($response);
        }
    }

    public function search_change_nature_of_business_()
    {

        $data = array();
        if ($this->request->getMethod() == "post") {

            $nature_of_business_list = $this->TradeApplyLicenceModel->nature_of_business_list();
            $data['nature_of_business_list'] = $nature_of_business_list;

            $license_no = $this->request->getVar('licenceno');
            $license_data = $this->TradeApplyLicenceModel->getlicencedata2($license_no);
            $data['license_data'] = $license_data;


            if (isset($_POST['update_nob'])) {
                $nob = $this->request->getVar('nob');
                $apply_id = $this->request->getVar('apply_id');
                $license_no = $this->request->getVar('license_no');
                // print_var($_POST);
                // die;
                $updateResponse = $this->TradeApplyLicenceModel->update_nature_of_business($nob, $apply_id);
                flashToast("nob_success", "Nature of business of license no ".$license_no." changed to ".$nob." updated successfully!!!");
                // print_var($nature_of_business_list);
                // die();
                return redirect()->to(base_url("tradeapplylicence/search_change_nature_of_business"));
            }
        }
        // print_var($data['license_data']);

        return view('trade/Connection/search_change_nature_of_business', $data);
    }
    public function search_change_nature_of_business()
    {
        $data = array();
        if ($this->request->getMethod() == "post") {

            $nature_of_business_list = $this->TradeApplyLicenceModel->nature_of_business_list();

            $data['nature_of_business_list'] = $nature_of_business_list;

            $license_no = $this->request->getVar('licenceno');


            $license_data = $this->TradeApplyLicenceModel->getLicenseData($license_no);
            $data['license_data'] = $license_data;


            if (isset($_POST['update_nob'])) {
                $nob = $this->request->getVar('nob');

                $apply_id = $this->request->getVar('apply_id');
                $license_no = $this->request->getVar('license_no');


                $updateResponse = $this->TradeApplyLicenceModel->update_nature_of_business2($nob, $apply_id, $license_no);
                flashToast("nob_success", "Nature of business of license no ".$license_no." changed to ".$nob." updated successfully!!!");

                return redirect()->to(base_url("tradeapplylicence/search_change_nature_of_business"));
            }

        }

        return view('trade/Connection/search_change_nature_of_business', $data);
    }



    public function getcharge_copy($args = array())
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
            $nob = array();
            if (!empty($args))
                $inputs = $args;
            else
                $inputs = arrFilterSanitizeString($this->request->getVar());
            // print_var($inputs);
            $data['area_in_sqft'] = (float)$inputs['areasqft'];
            $data['application_type_id'] = $inputs['applytypeid'];
            $data['firm_date'] = $inputs['estdate'];

            $nob = explode(',', $inputs['nature_of_business']);
            if (sizeof($nob) <= 1) {
                $data['nature_of_business'] = $nob[0];
            } else {
                $data['nature_of_business'] = "multiple_values";
            }

            // echo $data['nature_of_business'];

            # Date of firm establishment/expiry date

            $data['tobacco_status'] = $inputs['tobacco_status'];
            $data['timeforlicense'] = $inputs['licensefor'];
            $data['curdate'] = date("Y-m-d");
            $denial_amount_month = 0;
            $count = $this->tradelicenceratemodel->getrate($data);

            // echo $count['rate'];
            $rate = $count['rate'] * $data['timeforlicense'];

            $pre_app_amount = 0;
            $vDiff = 0;

            if (isset($inputs['apply_licence_id']) && in_array($inputs['applytypeid'], [2])) {
                // echo "valid upto ";
                $validUpto = $this->TradeApplyLicenceModel->validUpto($inputs['apply_licence_id']);
                // echo $validUpto['valid_upto'];
                $data['firm_date'] = (strtotime($validUpto['valid_upto']) > strtotime('2020-01-01')) ? $validUpto['valid_upto'] : '2020-01-01';
                $vDiff = abs(strtotime($data['firm_date']) - strtotime($data['curdate']));
                $diff_year = date_diff(date_create($validUpto['valid_upto']), date_create(date('Y-m-d')))->format('%R%y');
                $pre_app_amount = ($diff_year > 0 ? $diff_year : 0) * $count['rate'];
            }

            # if application type id is 1 (start)
            if ($inputs['applytypeid'] == 1) {
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

                if ($data['nature_of_business'] == 198) {
                    // echo "199999998";
                    $data['firm_date'] = (strtotime($inputs['estdate']) > strtotime('2021-11-01')) ? $inputs['estdate'] : '2021-11-01';
                    $vDiff = abs(strtotime($data['curdate']) - strtotime($data['firm_date']));
                    $data['firm_date'] . " ";
                    $diff_year = date_diff(date_create($data['firm_date']), date_create(date('Y-m-d')))->format('%R%y');
                    $pre_app_amount = ($diff_year > 0 ? $diff_year : 0) * $count['rate'];
                } {
                    /**  if($data['nature_of_business']=='multiple_values'){
                        $data['firm_date'] = (strtotime($inputs['estdate'])>strtotime('2020-01-01'))?$inputs['estdate']:'2020-01-01';
                        $vDiff = abs(strtotime($data['curdate']) - strtotime($data['firm_date'])); // here abs in case theres a mix in the dates
                        $diff_year = date_diff(date_create($data['firm_date']),date_create(date('Y-m-d')))->format('%R%y');
                        $pre_app_amount = ($diff_year>0?$diff_year:0)*$count['rate'];
                     **/
                }
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

    // this function also written in TradeCitizen controller
    public function applicationStatus_md5($apply_licence_id)
    {
        $data['licencee'] = $this->TradeApplyLicenceModel->apply_licence_md5($apply_licence_id);
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

    public function trade_licence_view_copy($id)
    {
        $data = array();
        $data['user_type'] = $this->user_type;
        $data['linkId'] = $id;
        $data['licencee'] = $this->TradeApplyLicenceModel->apply_licence_md5($id);
        $data['firm_type'] = $this->tradefirmtypemstrmodel->getdatabyid($data["licencee"]["firm_type_id"]);
        $data["ownershiptype"] = $this->tradeownershiptypemstrmodel->getdatabyid($data["licencee"]["ownership_type_id"]);
        $data["holding_no"] = $data['licencee']["holding_no"];
        $warddet = $this->model_ward_mstr->getWardNoBywardId($data['licencee']['ward_mstr_id']);
        $data['new_ward_no'] = $this->model_ward_mstr->getWardNoBywardId($data['licencee']['new_ward_mstr_id']);
        $data['ward_no'] = $warddet["ward_no"];
        $data["ownershiptype"] = $this->tradeownershiptypemstrmodel->getownership($data['licencee']['ownership_type_id']);
        $data['categoryTypeDetails'] = $this->TradeCategoryTypeModel->getdatabyid($data['licencee']['category_type_id']);
        $data['licencee'] = $this->TradeApplyLicenceModel->apply_licence_md5($id);
        $data["application_type"] = $this->tradeapplicationtypemstrmodel->getdatabyid($data['licencee']["application_type_id"]);

        //application status
        if ($data['licencee']["pending_status"] == 5) {
            if ($data["application_type"]["application_type"] == 'SURRENDER') {
                $data["application_status"] = '<span style="color:#167b3b">Licence Surrender Successfully!</span>';
            } else {
                $data["application_status"] = '<span style="color:#167b3b">Licence Created Successfully!</span>';
            }
        } elseif ($data['licencee']["pending_status"] == 4) {
            $data["application_status"] = '<span style="color:red">Licence Rejected!</span>';
        } elseif ($data['licencee']["pending_status"] == 2) {
            $data["level"] = $this->model_trade_level_pending_dtl->get_receiver_user_type_id_orderbydesc($id);
            if ($data["level"]["receiver_user_type_id"] == 17) {
                $level_name = "Dealing Assistant";
            } elseif ($data["level"]["receiver_user_type_id"] == 18) {
                $level_name = "Section Head";
            } elseif ($data["level"]["receiver_user_type_id"] == 19) {
                $level_name = "Executive Officer";
            } elseif ($data["level"]["receiver_user_type_id"] == 20) {
                $level_name = "Tax Daroga";
            }

            $data["application_status"] = '<span style="color:red">' . $level_name . ' Reverse The Application TO Citizen!</span>';
        } elseif ($data['licencee']["pending_status"] == 0 && $data['licencee']["document_upload_status"] == 0 || $data['licencee']["document_upload_status"] == 2) {
            $data["application_status"] = "Pending At Back Office";
        } elseif (($data['licencee']["payment_status"] == 0) and ($data['licencee']["document_upload_status"] == 1)) {
            $data["level"] = $this->model_trade_level_pending_dtl->get_receiver_user_type_id_orderbydesc($id);

            if ($data["level"]["receiver_user_type_id"] == 17) {
                $data["application_status"] = "Pending At Dealing Assistant";
            } elseif ($data["level"]["receiver_user_type_id"] == 18) {
                $data["application_status"] = "Pending At Section Head";
            } elseif ($data["level"]["receiver_user_type_id"] == 19) {
                $data["application_status"] = "Pending At Executive Officer";
            } elseif ($data["level"]["receiver_user_type_id"] == 20) {
                $data["application_status"] = "Pending At Tax Daroga";
            }
        } else {
            if (($data['licencee']["payment_status"] > 0) and ($data['licencee']["document_upload_status"] == 1)) {
                $data["level"] = $this->model_trade_level_pending_dtl->get_receiver_user_type_id_orderbydesc($id);

                if ($data["level"]["receiver_user_type_id"] == 17) {
                    $data["application_status"] = "Pending At Dealing Assistant";
                } elseif ($data["level"]["receiver_user_type_id"] == 18) {
                    $data["application_status"] = "Pending At Section Head";
                } elseif ($data["level"]["receiver_user_type_id"] == 19) {
                    $data["application_status"] = "Pending At Executive Officer";
                } elseif ($data["level"]["receiver_user_type_id"] == 20) {
                    $data["application_status"] = "Pending At Tax Daroga";
                }
            } elseif (($data['licencee']["payment_status"] > 0) and ($data['licencee']["document_upload_status"] == 0)) {
                $data["application_status"] = "Payment Done But Document Upload Pending";
            }
        }
        //end

        $data['firm_owner'] = $this->TradeFirmOwnerModel->getdatabyid_md5($id);
        $data['trans_detail'] = $this->TradeTransactionModel->alltransaction_details($id);
        $data["trade_items"] = $this->tradeitemsmstrmodel->gettradeitems($data['licencee']['nature_of_bussiness']);
        return view('trade/connection/trade_licence_details_view', $data);
    }

    public function trade_licence_view($id)
    {
        return redirect()->to(base_url("trade_da/view_application_details/$id"));
    }

    public function getlicencedetails()
    {
        if ($this->request->getMethod() == "post") {
            $data = array();
            $inputs = arrFilterSanitizeString($this->request->getVar());
            // $data['ward_id']=$inputs['ward_id'];
            $data['licence_no'] = $inputs['licence_no'];
            $data['application_type'] = $inputs['application_type'];


            if ($licencedet = $this->TradeViewLicenceOwnerrModel->getdatabylicenceno($data)) {
                $response = ['response' => true, 'dd' => $licencedet, 'at' => md5($data['application_type'])];
            } else {
                $response = ['response' => false];
            }
        } else {
            $response = ['response' => false];
        }
        return json_encode($response);
    }


    public function provisional($id = null)
    {
        $data = (array)null;
        $Session = Session();
        $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
        $path = base_url('citizenPaymentReceipt/view_trade_provisinal_receipt/' . $ulb_mstr_id . '/' . $id);
        $data['ss'] = qrCodeGeneratorFun($path);

        $data['ulb'] = $this->modelUlb->getulb_list($ulb_mstr_id);
        $data['basic_details'] = $this->model_view_trade_licence->getDatabyid($id);

        $vUpto = $data['basic_details']['apply_date'];
        $data["valid_upto"] = date('Y-m-d', strtotime(date($vUpto, time()) . " + 22 day"));
        $data['tranProvDtl'] = $this->TradeTransactionModel->tranProvDtl($id);
        return view('trade/Connection/provisional_licence', $data);
    }

    public function denialDetails()
    {
        return view('');
    }

    public function updateApplicationDetails()
    {
        $data = (array)null;
        $session = session();
        $get_ulb_id = $session->get('ulb_dtl');
        $get_emp_details = $session->get('emp_details');
        $data['ulb_mstr_name'] = $this->modelUlb->getulb_list($get_ulb_id["ulb_mstr_id"]);
        $ulb_city_nm = $data['ulb_mstr_name']['city'];
        $short_ulb_name = $data["ulb_mstr_name"]["short_ulb_name"];
        $emp_id = $get_emp_details['id'];
        $ip_address = $get_emp_details['ip_address'];
        $data['ward_list'] = $this->model_ward_mstr->getWardList($get_ulb_id);
        if ($this->request->getMethod() == "post") {
            if (isset($_POST['btn_searchApplin'])) {
                $inputs = arrFilterSanitizeString($this->request->getVar());
                $data['applicationNo'] = $inputs['applicationNo'];
                if ($data['applicationNo'] == "") {
                    $data['error'] = '<h3 style="color:red;">Please Enter Application Number</h3>';
                    return view('trade/Connection/updateApplicationDetails', $data);
                }
                $data["applicationDetails"] = $this->TradeApplyLicenceModel->getLicenceDetails($data['applicationNo']);
                if (!$data["applicationDetails"]) {
                    $data['error'] = '<h3 style="color:red;">Please Enter Correct Application Number</h3>';
                    return view('trade/Connection/updateApplicationDetails', $data);
                }
                // print_r( $data["applicationDetails"]);exit;
                $data["tradeitemlist"] = $this->tradeitemsmstrmodel->gettradeitemsList();
                $data["firmtypelist"] = $this->tradefirmtypemstrmodel->getFirmTypeList();
                $data['categoryTypeDetails'] = $this->TradeCategoryTypeModel->getCategoryType();
                $data["ownershiptypelist"] = $this->tradeownershiptypemstrmodel->getownershipTypeList();
                $data["application_type"] = $this->tradeapplicationtypemstrmodel->getapplicationtypeById($data["applicationDetails"]['application_type_id']);
                $data['ownerDetails'] = $this->TradeFirmOwnerModel->getdatabyid_md5(md5($data["applicationDetails"]['id']));
                $data['transactionDetails'] = $this->TradeTransactionModel->getTransaction_details($data["applicationDetails"]['id']);
                $data['denial'] = 'Denial Apply';
                $data['datadelay'] = 'Delay Apply License';
                $data['Denial_Apply'] = $this->model_trade_transaction_fine_rebet_details->getfine_rebet_details($transactionDetails['id'], $data['denial']);
                $data['Delay_Apply'] = $this->model_trade_transaction_fine_rebet_details->getfine_rebet_details($transactionDetails['id'], $data['datadelay']);
            }
            if (isset($_POST['btn_submit'])) {
                $inputs = arrFilterSanitizeString($this->request->getVar());
                $data['id'] = $inputs['id'];
                $data['firmtype_id'] = $inputs['firmtype_id'];
                $data['ownership_type_id'] = $inputs['ownership_type_id'];
                $data['holding_no'] = $inputs['holding_no'];
                $data['ward_mstr_id'] = $inputs['ward_mstr_id'];
                $data['new_ward_mstr_id'] = $inputs['new_ward_mstr_id'];
                $data['firm_name'] = $inputs['firm_name'];
                $data['area_in_sqft'] = $inputs['area_in_sqft'];
                $data['firm_date'] = $inputs['firm_date'];
                $data['firmaddress'] = $inputs['firmaddress'];
                $data['landmark'] = $inputs['landmark'];
                $data['pin_code'] = $inputs['pin_code'];
                $data['owner_name'] = $inputs['owner_name'];
                $data['owner_name_id'] = $inputs['owner_name_id'];
                $data['mobile_no'] = $inputs['mobile_no'];
                $data['emailid'] = $inputs['emailid'];
                $data['address'] = $inputs['address'];
                $data['idproof'] = $inputs['idproof'] ?? NULL;
                $data['id_no'] = $inputs['id_no'] ?? NULL;
                $data['guardian_name'] = $inputs['guardian_name'];
                $data['updated_by'] = $get_emp_details['id'];
                $data['reason'] = $inputs['remark'] ?? NULL;
                $data['updated_date'] = date('Y-m-d');
                $data['created_on'] = date('Y-m-d H:i:s');
                $data['firmtype_other'] = $inputs['firmtype_other'];
                $data['category_type_id'] = $inputs['category_type_id'] ?? NULL;
                $data['premises_owner_name'] = $inputs['owner_business_premises'];
                $data['owner_name1'] = $inputs['owner_name1'];
                $data['guardian_name1'] = $inputs['guardian_name1'];
                $data['mobile_no1'] = $inputs['mobile_no1'];
                $data['emailid1'] = $inputs['emailid1'];
                $data['payment_mode'] = $inputs['payment_mode'];
                $data['transactionId'] = $inputs['transactionId'];
                $data['emp_details_id'] = $emp_id;
                $data['curdate'] = date("Y-m-d");
                $data['natureOfBusiness'] = $inputs['natureOfBusiness'];
                $data['tade_item'] = $inputs['tade_item'];
                $data['natureofbussiness'] = implode(",", $data['tade_item']);
                $c = $data['natureOfBusiness'] . "," . $data['natureofbussiness'];
                $data['nature_of_bussiness'] = implode(',', array_unique(explode(',', $c)));
                $updateData = $this->TradeApplyLicenceModel->updateApplicnDetails($data);
                $len = sizeof($data['owner_name']);
                for ($i = 0; $i < $len; $i++) {
                    $input['emp_details_id'] = $emp_id;
                    $input['created_on'] = date("Y-m-d");
                    $input['owner_id'] =  $data['owner_name_id'][$i];
                    $input['owner_name'] = $data['owner_name'][$i];
                    $input['guardian_name'] = $data['guardian_name'][$i];
                    $input['mobile'] = $data['mobile_no'][$i];
                    $input['emailid'] = $data['emailid'][$i];

                    $this->TradeFirmOwnerModel->updatedetailsowner($input);
                }

                if (isset($inputs['owner_name1'])) {
                    for ($i = 0; $i < sizeof($inputs['owner_name1']); $i++) {
                        $owner_arr['emp_details_id'] = $emp_id;
                        $owner_arr['created_on'] = date("Y-m-d");
                        $owner_arr['apply_licence_id'] = $inputs['id'];
                        $owner_arr['owner_name'] = $inputs['owner_name1'][$i];
                        $owner_arr['guardian_name'] = $inputs['guardian_name1'][$i];
                        $owner_arr['emailid'] = $inputs['emailid1'][$i];
                        $owner_arr['mobile'] = $inputs['mobile_no1'][$i];
                        $this->TradeFirmOwnerModel->insertdata($owner_arr);
                    }
                }

                $doc_path_image = $this->request->getFile('file');
                try {
                    $newFileName = md5($denial_id);
                    $file_ext = $doc_path_image->getExtension();
                    $path_images = $ulb_city_nm . "/" . "tbl_apply_changes_doc";
                    $doc_path_image->move(WRITEPATH . 'uploads/' . $path_images . '/', $newFileName . '.' . $file_ext);
                    $doc_path_save = $path_images . "/" . $newFileName . '.' . $file_ext;
                    $this->TradeApplyLicenceModel->updatedocpathById($denial_id, $doc_path_save);
                } catch (Exception $e) {
                }
                $data['success'] = '<h3 style="color:green;">Data Updated Successfully</h3>';
            }
        }

        return view('trade/Connection/updateApplicationDetails', $data);
    }
}
