<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_ulb_mstr;
use App\Models\model_view_ward_permission;
use App\Models\model_view_trade_level_pending;
use App\Models\model_apply_licence;
use App\Models\model_ward_mstr;
use App\Models\model_firm_owner_name;
use App\Models\model_application_doc;
use App\Models\model_trade_level_pending_dtl;
use App\Models\model_view_trade_licence;
use App\Models\model_view_application_doc;
use App\Models\model_user_type_mstr;
use App\Models\TradeTaxdarogaDocumentVerificationModel;
use App\Models\model_application_type_mstr;
use App\Models\model_category_type;
use App\Models\model_trade_items_mstr;
use App\Models\TradeTransactionModel;
use App\Models\TradeChequeDtlModel;
use App\Models\trade_view_application_doc_model;
use App\Models\TradeApplyLicenceModel;
use App\Models\TradeViewApplyLicenceOwnerModel;
use App\Models\TradeTradeItemsModel;
use App\Models\model_datatable;
use App\Models\model_prop_dtl;
use App\Models\model_prop_owner_detail;
use App\Models\model_trade_sms_log;

use Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Trade_DA extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $property_db;
    protected $model_ulb_mstr;
    protected $model_view_ward_permission;
    protected $model_view_trade_level_pending;
    protected $model_apply_licence;
    protected $model_ward_mstr;
    protected $model_firm_owner_name;
    protected $model_application_doc;
    protected $model_trade_level_pending_dtl;
    protected $model_view_trade_licence;
    protected $model_view_applicant_doc;
    protected $model_view_application_doc;
    protected $model_user_type_mstr;
    protected $TradeTaxdarogaDocumentVerificationModel;
    protected $model_application_type_mstr;
    protected $model_category_type;
    protected $model_trade_items_mstr;
    protected $TradeTransactionModel;
    protected $TradeChequeDtlModel;
    protected $trade_view_application_doc_model;
    protected $TradeApplyLicenceModel;
    protected $TradeViewApplyLicenceOwnerModel;
    protected $TradeTradeItemsModel;
    protected $model_datatable;
    protected $model_prop_dtl;
    protected $model_prop_owner_detail;
    protected $model_trade_sms_log;



    public function __construct()
    {

        parent::__construct();
        helper(['db_helper', 'form', 'qr_code_generator_helper', 'form_helper', 'sms_helper', 'utility_helper']);
        ini_set('memory_limit', '-1');
        helper(['php_office_helper']);
        helper(['db_helper', 'form']);
        helper(['db_helper', 'utility_helper']);
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
        $this->model_view_trade_level_pending = new model_view_trade_level_pending($this->db);
        $this->model_apply_licence = new model_apply_licence($this->db);
        $this->model_firm_owner_name = new model_firm_owner_name($this->db);
        $this->model_application_doc = new model_application_doc($this->db);
        $this->model_trade_level_pending_dtl = new model_trade_level_pending_dtl($this->db);
        $this->model_view_trade_licence = new model_view_trade_licence($this->db);
        $this->model_view_application_doc = new model_view_application_doc($this->db);
        $this->TradeTaxdarogaDocumentVerificationModel = new TradeTaxdarogaDocumentVerificationModel($this->db);
        $this->model_user_type_mstr = new model_user_type_mstr($this->dbSystem);
        $this->model_application_type_mstr = new model_application_type_mstr($this->db);
        $this->model_category_type = new model_category_type($this->db);
        $this->model_trade_items_mstr = new model_trade_items_mstr($this->db);
        $this->TradeTransactionModel = new TradeTransactionModel($this->db);
        $this->TradeChequeDtlModel = new TradeChequeDtlModel($this->db);
        $this->trade_view_application_doc_model = new trade_view_application_doc_model($this->db);
        $this->TradeApplyLicenceModel = new TradeApplyLicenceModel($this->db);
        $this->TradeViewApplyLicenceOwnerModel = new TradeViewApplyLicenceOwnerModel($this->db);
        $this->modelUlb = new model_ulb_mstr($this->dbSystem);
        $this->TradeTradeItemsModel = new TradeTradeItemsModel($this->db);
        $this->model_datatable = new model_datatable($this->db);
        $this->model_prop_dtl = new model_prop_dtl($this->property_db);
        $this->model_prop_owner_detail = new model_prop_owner_detail($this->property_db);
        $this->model_trade_sms_log = new model_trade_sms_log($this->db);
    }

    public function index()
    {
        $data = (array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];

        // print_var($emp_mstr);die();
        //Transaction Mode List
        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['wardList'] = $wardList;
        $receiver_user_type_id = $emp_mstr["user_type_mstr_id"];
        // print_r($receiver_user_type_id);exit;
        $ward = [];

        $i = 0;
        foreach ($wardList as $key => $value) {
            if ($i == 0) {
                $ward = array($value['ward_mstr_id']);
            } else {
                array_push($ward, $value['ward_mstr_id']);
            }
            $i++;
        }

        $wm_ids = implode(',', $ward);
        if ($this->request->getMethod() == 'post') {

            // print_var($_POST);die();

            $data['ward_mstr_id'] = $this->request->getVar('ward_mstr_id');
            $data['to_date'] = $this->request->getVar('to_date'); //this is not a date field now. It receives input from the search box
            // $data['to_date'] = $this->request->getVar('to_date');
            $whatever = $data['to_date'];
            $wmid = $data['ward_mstr_id'];
            if (isset($_POST['filter']) && $wmid != "") {
                // echo "fff";
                $whereQry = " WHERE vl.receiver_user_type_id = $receiver_user_type_id AND vl.sender_user_type_id IN (0,20) AND vl.status = 1 AND fown.ward_mstr_id=$wmid "; {
                    // $sql_qry = "SELECT 
                    //                 fown.ward_no,
                    //                 fown.mobile_no,
                    //                 fown.application_type,
                    //                 fown.firm_name,
                    //                 fown.apply_date,
                    //                 vl.* 
                    //             FROM view_trade_level_pending as vl 
                    //             JOIN view_apply_licence_owner as fown ON fown.id = vl.apply_licence_id
                    //             WHERE vl.receiver_user_type_id = 17 AND vl.sender_user_type_id IN (0,20) AND vl.status = 1 AND fown.ward_mstr_id=$wmid
                    //             ORDER BY vl.id DESC LIMIT 11";
                    // $data['posts']['result'] = $this->model_view_trade_level_pending->tradeReceiveListWithWhateverSecond($sql_qry);

                    // print_var($data);

                    // return view('trade/Connection/trade_da_list', $data);
                }
            } elseif ($data['ward_mstr_id'] != "" && isset($_POST['btn_search'])) {
                // echo "ddd";  
                // $data['posts'] = $this->model_view_trade_level_pending->tradedareceivebywardidList($receiver_user_type_id, $data['from_date'], $data['to_date'], $data['ward_mstr_id']);
                $whereQry = " WHERE (fown.mobile_no ILIKE '%$whatever%' OR vl.application_no ILIKE '%$whatever%') AND fown.ward_mstr_id=$wmid
                            AND vl.receiver_user_type_id = 17 AND vl.sender_user_type_id IN (0,20) AND vl.status = 1 "; {
                    // $sql_qry = "SELECT 
                    //                 fown.ward_no,
                    //                 fown.mobile_no,
                    //                 fown.application_type,
                    //                 fown.firm_name,
                    //                 fown.apply_date,
                    //                 vl.* 
                    //             FROM view_trade_level_pending as vl 
                    //             JOIN view_apply_licence_owner as fown ON fown.id = vl.apply_licence_id 
                    //             WHERE (fown.mobile_no ILIKE '%224736100522031545%' OR vl.application_no ILIKE '%224736100522031545%') AND fown.ward_mstr_id=$wmid
                    //             AND vl.receiver_user_type_id = 17 AND vl.sender_user_type_id IN (0,20) AND vl.status = 1
                    //             ORDER BY vl.id DESC LIMIT 11";
                    // $data['posts']['result'] = $this->model_view_trade_level_pending->tradeReceiveListWithWhateverSecond($sql_qry);

                    // print_var($data['posts']);die();
                }
            } elseif ($whatever != "" && isset($_POST['btn_search'])) {
                // echo "whatttt!";
                $whereQry = " WHERE (fown.mobile_no ILIKE '%$whatever%' OR vl.application_no ILIKE '%$whatever%') AND vl.receiver_user_type_id = 17 AND vl.sender_user_type_id IN (0,20) AND vl.status = 1 "; {
                    // $sql_qry = "SELECT 

                    //                 fown.ward_no,
                    //                 fown.mobile_no,
                    //                 fown.application_type,
                    //                 fown.firm_name,
                    //                 fown.apply_date,
                    //                 vl.* 
                    //             FROM view_trade_level_pending as vl 
                    //             JOIN view_apply_licence_owner as fown ON fown.id = vl.apply_licence_id 
                    //             WHERE (fown.mobile_no ILIKE '%224736100522031545%' OR vl.application_no ILIKE '%224736100522031545%') 
                    //             AND vl.receiver_user_type_id = 17 AND vl.sender_user_type_id IN (0,20) AND vl.status = 1
                    //             ORDER BY vl.id DESC";
                    // $sql = "select * from tbl_transaction limit 10";
                    // $data['posts']['result'] = $this->model_view_trade_level_pending->tradeReceiveListWithWhatever($receiver_user_type_id, $data['to_date'], null, $ward);
                    // $data['posts']['result'] = $this->model_view_trade_level_pending->tradeReceiveListWithWhateverSecond($sql_qry);

                    // $data['posts'] = $this->model_datatable->getDatatable($sql_qry);
                }
            } else {
                $whereQry = " WHERE vl.receiver_user_type_id = 17 AND vl.sender_user_type_id IN (0,20) AND vl.status = 1 AND fown.ward_mstr_id IN ($wm_ids)";
            }

            $sql_qry = "SELECT 
                            fown.ward_no,
                            al.application_no,
                            fown.mobile_no,
                            fown.application_type,
                            al.firm_name,
                            fown.apply_date,
                            vl.* 
                        FROM tbl_level_pending as vl 
                        JOIN view_apply_licence_owner as fown ON fown.id = vl.apply_licence_id 
                         JOIN tbl_apply_licence as al on al.id=vl.apply_licence_id " . $whereQry . " ORDER BY vl.id DESC";
            // $data['posts']['result'] = $this->model_view_trade_level_pending->tradeReceiveListWithWhateverSecond($sql_qry); 
            $data['posts'] = $this->model_datatable->getDatatable($sql_qry);
            // print_var($data['posts']);die;
            $j = 0;
            foreach ($ward as $value) {
                $ward_list[] = $this->model_ward_mstr->getdatabyid($value);
            }

            $data['wardList'] = $ward_list;
            $data['posts']['count'] = $data['posts']['count'];

            // print_var($data['posts']['count']);

            return view('trade/Connection/trade_da_list', $data);
        } else {
            $data['from_date'] = date('Y-m-d', time() - 3600 * 24 * 7); //date('Y-m-d');
            $data['to_date'] = date('Y-m-d');

            $ward_per = implode(',', $ward);
            if (isset($_GET['page']) && $_GET['page'] == 1) {
                $data['posts']['offset'] = 0;
                $offset = $data['posts']['offset'];
            } elseif ($_GET['page'] > 1) {
                $data['posts']['offset'] = (($_GET['page'] - 1) * 10);
                $offset = $data['posts']['offset'];
            } else {
                $offset = 0;
            }

            $limit = 10;
            $sql_query = "SELECT 
                            fo.ward_no,
                            al.application_no,
                            al.firm_name, 
                            fo.application_type,
                            fo.mobile_no, 
                            fo.application_type_id,
                            fo.apply_date,
                            lp.* 
                        FROM tbl_level_pending  lp 
                        JOIN view_apply_licence_owner fo ON fo.id=lp.apply_licence_id
                        JOIN tbl_apply_licence as al on al.id=lp.apply_licence_id
                        WHERE lp.receiver_user_type_id = '$receiver_user_type_id' AND fo.ward_mstr_id 
                            IN ($ward_per) AND lp.sender_user_type_id IN (0,20) 
                            AND lp.status = 1 ORDER BY lp.id ASC";
            $sql_local = $sql_query . " OFFSET $offset LIMIT 10";

            $data['posts']['count'] = $this->model_datatable->getCount($sql_query);
            // $data['posts'] = $this->model_datatable->getDatatable($sql_query); 
            $data['posts']['result'] = $this->model_view_trade_level_pending->tradeReceiveListWithWhateverSecond($sql_local);
            // print_var($data['posts']);
            foreach ($ward as $value) {
                $ward_list[] = $this->model_ward_mstr->getdatabyid($value);
            }
            // print_var($ward_list);die();
            $data['wardList'] = $ward_list;
            // $data['posts']['count'] = sizeof($data['posts']['result']); 

            {
                // $data['postss'] = $this->model_view_trade_level_pending->tradedareceiveList2($receiver_user_type_id, $data['from_date'], $data['to_date'], $ward);
                // $data['count'] = $this->model_view_trade_level_pending->getTradeDaReceiveListCount(implode(',',$ward));
                // $data['count'];
                // print_var($data['postss']);die();
                $j = 0;
                // foreach ($data['posts']['result'] as $key => $value){
                //     // print_r($value);
                //     $wardd = $this->model_ward_mstr->getdatabyid($value['ward_mstr_id']);
                //     $licence_details = $this->model_apply_licence->getDatabyid($value['apply_licence_id']);
                //     $app_type = $this->model_application_type_mstr->trade_application_type_list($licence_details['application_type_id']);
                //     $apply_date = date_create($licence_details['apply_date']);
                //     $current_date = date_create(date('Y-m-d'));
                //     $diff = date_diff($apply_date, $current_date);
                //     $data['posts'][$key]['pending_since'] =  $diff->format("%a");
                //     $data['posts'][$key]['ward_no'] = $wardd['ward_no'];
                //     $data['posts'][$key]['licence_details'] = $licence_details;
                //     $data['posts'][$key]['app_type'] = $app_type;
                // }

                // print_var($data);
                // die();
            }
            return view('trade/Connection/trade_da_list', $data);
        }
    }
    public function view($id)
    {
        $data = (array)null;
        $level_data_sql = " select * from tbl_level_pending where md5(id::text) = '$id' ";
        $level_data = $this->model_trade_level_pending_dtl->rowQuery($level_data_sql)[0] ?? [];
        if (!$level_data) {
            flashToast("licence", "Application Not Found");
            return $this->response->redirect(base_url('Trade_SH/index'));
        } elseif ($level_data['status'] == 2) {
            flashToast("licence", "Application Already BTC");
            return $this->response->redirect(base_url('Trade_SH/index'));
        } elseif ($level_data['status'] == 3) {
            flashToast("licence", "Application Already Forword");
            return $this->response->redirect(base_url('Trade_SH/index'));
        } elseif ($level_data['status'] != 1) {
            flashToast("licence", "Already Taken Acction On This Application");
            return $this->response->redirect(base_url('Trade_SH/index'));
        }
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['wardList'] = $wardList;
        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];

        $data['form'] = $this->model_view_trade_level_pending->tradelevelpendingdetailbyid($id);

        if ($data['form'] && $data['form']['pending_status'] == 5) {
            flashToast("licence", "License Already Created Of " . $data['form']['application_no'] . " Please Contact To Admin !!!!");
            return $this->response->redirect(base_url('Trade_SH/index'));
        }

        $data['basic_details'] = $this->model_view_trade_licence->getDatabyid(md5($data['form']['apply_licence_id']));
        if (isset($data['basic_details']['holding_no']) && !empty(trim($data['basic_details']['holding_no']))) {
            $prop_id = $this->model_view_trade_licence->getPropetyIdByNewHolding($data['basic_details']['holding_no']);
            if (isset($prop_id['id']))
                $data['PropSafLink'] = base_url() . "/propDtl/full/" . $prop_id['id'];
        }
        $data['ward_mstr_id'] = $data['basic_details']['ward_mstr_id'];
        $ward = $this->model_ward_mstr->getdatabyid($data['form']['ward_mstr_id']);
        $data['form']['ward_no'] = $ward['ward_no'];
        $data['ward']['ward_no'] = $ward['ward_no'];
        $data['owner_list'] = $this->model_firm_owner_name->applicantdetails($data['form']['apply_licence_id']);

        $data['holding'] = $this->model_apply_licence->getholding($data['basic_details']['application_no']);
        $data['category_type'] = $this->model_category_type->category_type($data['holding']['category_type_id']);
        $data['nature_business'] = $this->model_trade_items_mstr->nature_business($data['holding']['nature_of_bussiness']);
        $data['payment_dtls'] = $this->TradeTransactionModel->payment_details($data['basic_details']['id']);

        $data['cheque_dtls'] = array();
        //$data['cheque_dtls'] = $this->TradeChequeDtlModel->cheque_details($data['payment_dtls']['id']);
        $apply_licence_id = $data['form']['apply_licence_id'];
        $verified_status = '1';
        $verify_status = '0';
        $data['documents'] = $this->trade_view_application_doc_model->getAllActiveDocuments($apply_licence_id);

        $data['dealingLevel'] = $this->model_trade_level_pending_dtl->getDealingLevelData($apply_licence_id);
        //$data['delingReceiveDate'] = $this->model_trade_level_pending_dtl->getDealingReceiveDate($apply_licence_id);

        $data['taxDarogaLevel'] = $this->model_trade_level_pending_dtl->getTaxDarogaLevelData($apply_licence_id);
        //$data['taxDarogaReceiveDate'] = $this->model_trade_level_pending_dtl->getTaxDarogaReceiveDate($apply_licence_id);

        $data['sectionHeadLevel'] = $this->model_trade_level_pending_dtl->getSectionHeadLevelData($apply_licence_id);
        // $data['sectionHeadReceiveDate'] = $this->model_trade_level_pending_dtl->getSectionHeadReceiveDate($apply_licence_id);

        $data['executiveLevel'] = $this->model_trade_level_pending_dtl->getExecutiveLevelData($apply_licence_id);

        $appliction = $data['form'];
        $owners = $data['owner_list'];
        if ($this->request->getMethod() == 'post') {
            # Reject/Verify Document
            if (isset($_POST['btn_reject']) || isset($_POST['btn_verify'])) {
                if (isset($_POST['btn_reject']))
                    $verify_status = 2; //2 rejected

                if (isset($_POST['btn_verify']))
                    $verify_status = 1; // 1 Verified

                $rules = ['rejectedremarks' => 'required',];

                if (!$this->validate($rules)) {
                    $data['validation'] = $this->validator;
                    return view('trade/Connection/trade_da_view', $data);
                } else {
                    $app_doc_id = $_POST['app_doc_id'];
                    $inputs = array(
                        'verify_status' => $verify_status,
                        'remarks' => $_POST['rejectedremarks'],
                        'verified_by_emp_id' => $login_emp_details_id,
                        'lvl_pending_id' => $_POST['level_pending_id'],
                    );
                    $this->trade_view_application_doc_model->VerifyDocument($app_doc_id, $inputs);
                    if (isset($_POST['btn_reject']))
                        flashToast('message', 'Application Rejected!!!');
                    if (isset($_POST['btn_verify']))
                        flashToast('message', 'Application Document Verified!!!');
                    return $this->response->redirect(base_url('trade_da/view/' . $id));
                }
            }

            # Back to Citizen
            if (isset($_POST['btn_app_submit'])) {

                $level_pending_id = $_POST['level_pending_id'];
                // die();
                $data['lastRecord'] = $this->model_trade_level_pending_dtl->getLastRecord($level_pending_id);
                $apply_licence_id = $data['lastRecord']["apply_licence_id"];
                $level_last_deta = $this->model_trade_level_pending_dtl->getDataNew(['id' => $data['lastRecord']['id']], '*', 'tbl_level_pending');



                $data = [
                    'remarks' => $this->request->getVar('remarks'),
                    'level_pending_dtl_id' => $id,
                    'apply_licence_id' => $apply_licence_id,
                    'emp_details_id' => $login_emp_details_id,
                    'receiver_user_type_id' => 0,
                    'created_on' => date('Y-m-d H:i:s'),
                    'forward_date' => date('Y-m-d'),
                    'forward_time' => date('H:i:s'),
                    'sender_user_type_id' => $sender_user_type_id,
                    'verification_status' => 2,
                    'level_pending_status' => 2 // back to citizen
                ];

                $btcdata = [
                    'remarks' => $this->request->getVar('remarks'),
                    'level_id' => $level_last_deta["id"],
                    'apply_licence_id' => $apply_licence_id,
                    'sender_user_type_id' => $level_last_deta["sender_user_type_id"],
                    'receiver_user_type_id' => $level_last_deta["receiver_user_type_id"],
                    'forward_date' => $level_last_deta["forward_date"],
                    'forward_time' => $level_last_deta["forward_time"],
                    'created_on' => $level_last_deta["created_on"],
                    'verification_status' => 2,
                    'emp_details_id' => $level_last_deta["emp_details_id"],
                    'status' => $level_last_deta["status"],
                    'send_date' => $level_last_deta["send_date"] ?? null,
                    'receiver_user_id' => $login_emp_details_id,
                    'ip_address' => $_SERVER['REMOTE_ADDR'],
                ];
                //print_var($btcdata);die();
                // update tbl_level_pending
                if ($updatebacktocitizen = $this->model_trade_level_pending_dtl->updatebacktocitizenById($data)) {
                    // tbl_apply_licence set pending_status=2
                    $this->model_trade_level_pending_dtl->tbl_level_sent_back_dtl($btcdata);
                    if ($updatesafpendingstts = $this->model_apply_licence->update_level_pending_status($data)) {

                        //----------------------sms send -----------------------

                        $sms = Trade(['application_no' => $appliction['application_no']], 'sent back'); //print_var($owners);die;
                        if ($sms['status'] == true) {
                            foreach ($owners as $val) {
                                $mobile = '';
                                $mobile = $val['mobile'];
                                $message = $sms['sms'];
                                $templateid = $sms['temp_id'];
                                $sms_log_data = [
                                    'emp_id' => $login_emp_details_id,
                                    'ref_id' => $appliction['apply_licence_id'],
                                    'ref_type' => 'tbl_apply_licence',
                                    'mobile_no' => $mobile,
                                    'purpose' => "sent back",
                                    'template_id' => $templateid,
                                    'message' => $message
                                ];
                                $sms_id =  $this->model_trade_sms_log->insert_sms_log($sms_log_data);
                                $s = send_sms($mobile, $message, $templateid);

                                if ($s) {
                                    $update_sms_log = ['response' => $s['response'], 'smgid' => $s['msg']];
                                    $up = $this->model_trade_sms_log->update_sms_log(['id' => $sms_id], $update_sms_log);
                                }
                            }
                        }

                        flashToast("message", "Application sent back to citizen");
                        return $this->response->redirect(base_url('trade_SH/index'));
                    }
                }
            }

            # Approve & Forward
            if (isset($_POST['btn_approve_submit'])) {

                $documentstd = $data['documents'];

                $data = [
                    'remarks' => $this->request->getVar('remarks'),
                    'level_pending_dtl_id' => $id,
                    'apply_licence_id' => $apply_licence_id,
                    'forward_date' => date('Y-m-d'),
                    'forward_time' => date('H:i:s'),
                    'emp_details_id' => $login_emp_details_id,
                    'sender_user_type_id' => $sender_user_type_id,
                    'receiver_user_type_id' => 20,
                    'created_on' => date('Y-m-d H:i:s'),
                    'level_pending_status' => 0
                ];

                if ($updatelevelpending = $this->model_trade_level_pending_dtl->updatelevelpendingById($data)) {
                    // update tbl_apply_license pending_status=0
                    if ($updatesafpendingstts = $this->model_apply_licence->update_level_pending_status($data)) {
                        $insrtlevelpending = $this->model_trade_level_pending_dtl->insrtlevelpendingdtl($data);
                        $inputs = array(
                            'verify_status' => 1,
                            'remarks' => 'Verified together',
                            'verified_by_emp_id' => $login_emp_details_id,
                            'lvl_pending_id' => $_POST['level_pending_id'],
                        );
                        $this->trade_view_application_doc_model->VerifyAllDocument($apply_licence_id, $inputs);

                        $verify_inputs = array(
                            'apply_licence_id' => $apply_licence_id,
                            'doc_verify_status' => 1,
                            'doc_verify_date' => date('Y-m-d'),
                            'doc_verify_emp_details_id' => $login_emp_details_id,
                        );
                        $this->model_apply_licence->verifyDocument($verify_inputs);

                        #Verify Trade Daroga Level Documents(start)

                        foreach ($documentstd as $val) {
                            // $cnt=$_POST['btn_verify'];
                            $inputData = [
                                'apply_licence_id' => $apply_licence_id,
                                'emp_details_id' => $login_emp_details_id,
                                'created_on' => date('Y-m-d H:i:s'),
                                'upload_doc_id' => $val['id'],
                                'doc_remark' => 'documents verified',
                                'verify_status' => 1
                            ];
                            $this->TradeTaxdarogaDocumentVerificationModel->insertDocumentData($inputData);
                        }

                        #Verify Trade Daroga Level Documents(End)

                        flashToast('message', 'Application Approved & Forwaded to Tax Daroga!!!');
                        return $this->response->redirect(base_url('trade_SH/index/'));
                    }
                }
            }
        } else {   //print_var($data);
            return view('trade/Connection/trade_da_view', $data);
        }
    }


    /*track application number*/
    public function track_application_no($view='')
    {
        $data = (array)null;
        $Session = Session();
        $data['view'] = $view;

        switch($view){
            case 'fullUpdate': $data["page"] = "Trade_Apply_Licence/updateApplication";
                break;
            default: $data["page"]="trade_da/view_application_details";
        }
        if ($this->request->getMethod() == 'post') {
            // die();
            $data['application_no'] = trim($this->request->getVar('applcn_no'));
            if (isset($_POST['firm_name'])) {
                $data['firm_name'] = $this->request->getVar('firm_name');
                $myQuery = $this->model_apply_licence->get_licence_firm_name($data['firm_name']);
                $data['id'] = $this->model_datatable->getDatatable($myQuery);
                $data['id'] = $data['id']['result'];
                // print_var($data['id']);die();

                return view('trade/Connection/track_application_no', $data);
            }



            if ($data['application_no'] == "") {
                $data['validation'] = "Application No. feild is required !";
                return view('trade/Connection/track_application_no', $data);
            } else {
                $data['id'] = $this->model_apply_licence->get_licence_id($data['application_no']);
                // print_var($data);die();
                $id = $data['id'][0]['id'] ?? NULL;

                // return $this->response->redirect(base_url('trade_da/view_application_details/'.md5($id)));
                return view('trade/Connection/track_application_no', $data);
            }
        } else {
            return view('trade/Connection/track_application_no', $data);
        }
    }

    public function track_and_update_application()
    {
        $data = (array)null;
        $Session = Session();
        if ($this->request->getMethod() == 'post') {
            $data['application_no'] = trim($this->request->getVar('applcn_no'));

            if ($data['application_no'] == "") {
                $data['validation'] = "Application No. feild is required !";
                return view('trade/Connection/track_and_update_application', $data);
            } else {
                $data['id'] = $this->model_apply_licence->get_licence_id_for_application_update($data['application_no']);
                // print_var($data);die();
                $id = $data['id'][0]['id'] ?? NULL;
                // print_var($data['id']);die();

                if ($id) {
                    // return $this->response->redirect(base_url('trade_da/view_application_details/'.md5($id)));
                    return view('trade/Connection/track_and_update_application', $data);
                } else {
                    $data['error'] = "<span style='color:red;'>Incorrect Application Number !</span>";
                    return view('trade/Connection/track_and_update_application', $data);
                }
            }
        } else {
            return view('trade/Connection/track_and_update_application', $data);
        }
    }

    //trade application details
    public function view_application_details($id)
    {
        $data = (array)null;
        $emp_id = session()->get('emp_details')['user_mstr_id'] ?? null;
        $Session = Session();
        date_default_timezone_set('Asia/Kolkata');
        // echo $id;
        $data['basic_details'] = $this->model_view_trade_licence->getDatabyid($id);

        if (empty($data['basic_details'])) {

            $data['basic_details'] = $this->model_view_trade_licence->getDatabyid2($id);

            if ($data['basic_details']['firm_type_id'] != null) {
                $firm_type = $this->model_view_trade_licence->getFirmTypebyid($data['basic_details']['firm_type_id']);
                $data['basic_details']['firm_type'] = $firm_type;
            }
            if ($data['basic_details']['application_type_id'] != null) {
                $application_type = $this->model_view_trade_licence->getApplicationTypebyid($data['basic_details']['application_type_id']);
                $data['basic_details']['application_type'] = $application_type;
            }
            if ($data['basic_details']['nature_of_bussiness'] != null) {
                $nature_of_bussiness = $this->model_view_trade_licence->getNatureOfBussinessbyid($data['basic_details']['nature_of_bussiness']);
                $data['basic_details']['nature_of_bussiness'] = $nature_of_bussiness;
            }
        }

        // if(empty($data['basic_details']))
        //     return redirect()->to(base_url('TradeApplyLicence/applynewlicences/$'));
        // array_merge($data['dtl'], array("firm_type"=>$firm_type));
        // print_var($data['basic_details']);die();
        $data['ward_mstr_id'] = $data['basic_details']['ward_mstr_id'];
        $data['ward'] = $this->model_ward_mstr->getdatabyid($data['ward_mstr_id']);
        $data['owner_list'] = $this->model_firm_owner_name->applicantdetails($data['basic_details']['id']);
        $data['holding'] = $this->model_apply_licence->getholding($data['basic_details']['application_no']);
        // print_var($data['holding']);
        // die();
        $data['category_type'] = $this->model_category_type->category_type($data['holding']['category_type_id']);
        //$data['nature_business'] = !empty($data['holding']['category_type_id']) ? $this->model_trade_items_mstr->get_nature_business($data['holding']['nature_of_bussiness']) : [];
        $nature_business = $this->model_trade_items_mstr->get_nature_businessItemCode($data['holding']['nature_of_bussiness'] ?? null);
        $natur = array_map(function ($val) {
            return $val['trade_item'];
        }, $nature_business);
        $natur = implode($natur);
        if ($natur) {
            $data['nature_business'][0]['trade_item'] = $natur;
        }
        $data['payment_dtls'] = $this->TradeTransactionModel->payment_details($data['basic_details']['id']);

        $apply_licence_id = $data['basic_details']['id'];
        $data['doc_exists'] = $this->trade_view_application_doc_model->getdocdet_by_appid($apply_licence_id);
        $data['linkId'] = $id;

        // $where_dealing = '';
        // $where_dealing .= " sender_user_type_id = 17 ";
        // $where_dealing .= " AND receiver_user_type_id = 20 ";

        // $where_tax_daroga = '';
        // $where_tax_daroga .= " sender_user_type_id = 20 ";
        // $where_tax_daroga .= " AND receiver_user_type_id = 18 ";

        // $where_sh = '';
        // $where_sh .= " sender_user_type_id = 18 ";
        // $where_sh .= " AND receiver_user_type_id = 19 ";

        // $where_eo = '';
        // $where_eo .= "  receiver_user_type_id = 19 ";
        // $where_eo .= " AND status = 5 ";
        // //end
        // $data['dealingLevel'] = $this->model_trade_level_pending_dtl->getDealingLevelData($apply_licence_id, $where_dealing);
        // $data['taxDarogaLevel'] = $this->model_trade_level_pending_dtl->getTaxDarogaLevelData($apply_licence_id, $where_tax_daroga);
        // $data['sectionHeadLevel'] = $this->model_trade_level_pending_dtl->getSectionHeadLevelData($apply_licence_id, $where_sh);
        // $data['executiveLevel'] = $this->model_trade_level_pending_dtl->getExecutiveLevelData($apply_licence_id, $where_eo);
        // if( $emp_id==1)
        {
            $where_dealing = '';
            $where_dealing .= " AND tbl_level_pending.receiver_user_type_id = 17 ";
            // $where_dealing .= " AND receiver_user_type_id = 20 ";

            $where_tax_daroga = '';
            $where_tax_daroga .= " AND tbl_level_pending.receiver_user_type_id = 20 ";
            // $where_tax_daroga .= " AND receiver_user_type_id = 18 ";

            $where_sh = '';
            $where_sh .= " AND tbl_level_pending.receiver_user_type_id = 18 ";
            // $where_sh .= " AND receiver_user_type_id = 19 ";

            $where_eo = '';
            $where_eo .= "  AND tbl_level_pending.receiver_user_type_id = 19 ";
            // $where_eo .= " AND status = 5 ";

            $data['dealingLevel'] = $this->model_trade_level_pending_dtl->getLevelRemaks($apply_licence_id, $where_dealing);
            $data['taxDarogaLevel'] = $this->model_trade_level_pending_dtl->getLevelRemaks($apply_licence_id, $where_tax_daroga);
            $data['sectionHeadLevel'] = $this->model_trade_level_pending_dtl->getLevelRemaks($apply_licence_id, $where_sh);
            $data['executiveLevel'] = $this->model_trade_level_pending_dtl->getLevelRemaks($apply_licence_id, $where_eo);

        }

        $data['levelPendinDtlId'] = $this->model_trade_level_pending_dtl->getLevelPendingDtlId($apply_licence_id);
        $data['licence_owner_id'] = $this->TradeViewApplyLicenceOwnerModel->getDatabyapplno($data['basic_details']['application_no']);

        $data['licencee'] = $this->TradeApplyLicenceModel->apply_licence_md5($id);
        $data['new_ward_no'] = $this->model_ward_mstr->getWardNoBywardId($data['licencee']['new_ward_mstr_id']);

        // payment receipt code
        //$applyid = md5($data['payment_dtls']['related_id']);
        $applyid = $id;
        //$transaction_id = md5($data['payment_dtls']['id']);
        $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
        //$path=base_url('citizenPaymentReceipt/view_trade_transaction_receipt/'.$ulb_mstr_id.'/'.$applyid.'/'.$transaction_id);
        //$data['ss']=qrCodeGeneratorFun($path);
        //$data['transaction_id']=$transaction_id;
        $data['ulb_mstr_name'] = $this->modelUlb->getulb_list($ulb_mstr_id);
        //$data['applicant_details']=$this->TradeApplyLicenceModel->fetch_all_application_data($applyid);        
        //$data['transaction_details']=$this->TradeTransactionModel->transaction_details($transaction_id);
        $warddet = !empty($data['transaction_details']) ? $this->model_ward_mstr->getWardNoBywardId($data['transaction_details']['ward_mstr_id']) : null;
        $data['ward_no'] = $warddet["ward_no"] ?? null;
        $data['status'] = [1, 2];
        //$data['cheque_details']=$this->TradeChequeDtlModel->alltransactioncheque_details($transaction_id);
        // end

        //provitional licence code 
        $id_provitional = md5($data['licence_owner_id']['id']);
        $ulb_mstr = $Session->get("ulb_dtl");
        $path = base_url('citizenPaymentReceipt/view_trade_provisinal_receipt/' . $ulb_mstr_id . '/' . $id_provitional);
        $data['ssprov'] = qrCodeGeneratorFun($path);
        $emp_mstr = $Session->get("emp_details");
        // print_var($emp_mstr);die();
        $login_emp_details_id = $emp_mstr["id"];
        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        $data['ulb'] = $this->modelUlb->getulb_list($ulb_mstr_id);
        $data['basic_details_prov'] = $this->TradeViewApplyLicenceOwnerModel->get_Data($data['licence_owner_id']['id']);
        $vUpto = $data['basic_details_prov']['apply_date'];
        //$data["valid_upto"] = date('Y-m-d',strtotime(date("$vUpto", mktime()) . " + 20 day"));
        $apply_licence_id = $data['basic_details_prov']['id'];
        $data['firm_owner_name'] =
            //$data['item_details'] = $this->TradeTradeItemsModel->getdatabyid_md5($id_provitional);
            $data['tranProvDtl'] = $this->TradeTransactionModel->tranProvDtl($id_provitional);
        $data['ward']  = $this->model_ward_mstr->getdatabyid($data['basic_details_prov']['ward_mstr_id']);
        $data['newWard']  = $this->model_ward_mstr->getdatabyid($data['basic_details_prov']['new_ward_mstr_id']);
        //end 

        //trade licence
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $ulb_mstr_id = $data['ulb_mstr_id'];
        $path = base_url('citizenPaymentReceipt/municipal_licence/' . $ulb_mstr_id . '/' . $id);
        $data['ss'] = qrCodeGeneratorFun($path);
        $data['ulb'] = $this->model_ulb_mstr->getulb_list($data['ulb_mstr_id']);
        // $data['basic_details'] = $this->model_view_trade_licence->getDatabyid($id);

        // print_var($data['basic_details']);die();
        $apply_licence_id = $data['basic_details']['id'];
        $data['licence_dtl'] = $this->model_apply_licence->getDatabyid($apply_licence_id);
        $data['item_details'] = !empty($data['licence_dtl']['nature_of_bussiness']) ? $this->model_trade_items_mstr->get_nature_business($data['licence_dtl']['nature_of_bussiness']) : null;
        $data['prop_dtl'] = $this->model_prop_dtl->getPropdetailsbyid($data['licence_dtl']['prop_dtl_id']);
        $data['prop_owner_dtl'] = $this->model_prop_owner_detail->propownerdetails($data['licence_dtl']['prop_dtl_id']);
        $data['ward']  = $this->model_ward_mstr->getdatabyid($data['basic_details']['ward_mstr_id']);
        $data['owner_details'] = $this->model_firm_owner_name->applicantdetails($apply_licence_id);
        //end



        //end
        $data['application_type']['id'] = $data['licence_dtl']['application_type_id'];
        $data["application_status"] = $this->application_status($id);

        $sql_notice = "select * from tbl_denial_notice where md5(apply_id::text) = '$id' and status = 2";
        $noticeDetails = $this->model_view_trade_licence->row_query($sql_notice, array())[0] ?? [];
        $data['notice_date'] = !empty($noticeDetails) ? $noticeDetails['created_on'] : null;
        if (isset($data['basic_details']['holding_no']) && !empty(trim($data['basic_details']['holding_no']))) {
            $prop_id = $this->model_view_trade_licence->getPropetyIdByNewHolding($data['basic_details']['holding_no']);
            if (isset($prop_id['id']))
                $data['PropSafLink'] = base_url() . "/propDtl/full/" . $prop_id['id'];
        }
        // print_var($data);
        return view('trade/Connection/trade_application_details', $data);
    }

    //trade application details
    public function viewApplicationDetails($id)
    {
        return $this->view_application_details($id);
        $data = (array)null;
        $Session = Session();
        date_default_timezone_set('Asia/Kolkata');
        $data['basic_details'] = $this->model_view_trade_licence->getDatabyid($id);
        // echo '<pre>';print_r($data);return;
        $data['ward_mstr_id'] = $data['basic_details']['ward_mstr_id'];
        $data['ward'] = $this->model_ward_mstr->getdatabyid($data['ward_mstr_id']);
        $data['owner_list'] = $this->model_firm_owner_name->applicantdetails($data['basic_details']['id']);


        $data['holding'] = $this->model_apply_licence->getholding($data['basic_details']['application_no']);
        $data['category_type'] = $this->model_category_type->category_type($data['holding']['category_type_id']);
        $data['nature_business'] = $this->model_trade_items_mstr->get_nature_business($data['holding']['nature_of_bussiness']);
        $data['payment_dtls'] = $this->TradeTransactionModel->payment_details($data['basic_details']['id']);

        $data['cheque_dtls'] = $this->TradeChequeDtlModel->cheque_details($data['payment_dtls'][0]['id']);
        $apply_licence_id = $data['basic_details']['id'];
        $data['doc_exists'] = $this->trade_view_application_doc_model->getdocdet_by_appid($apply_licence_id);
        $data['linkId'] = $id;
        //where clause 
        $where_dealing = '';
        $where_dealing .= " sender_user_type_id = 17 ";
        $where_dealing .= " AND receiver_user_type_id = 20 ";

        $where_tax_daroga = '';
        $where_tax_daroga .= " sender_user_type_id = 20 ";
        $where_tax_daroga .= " AND receiver_user_type_id = 18 ";

        $where_sh = '';
        $where_sh .= " sender_user_type_id = 18 ";
        $where_sh .= " AND receiver_user_type_id = 19 ";

        $where_eo = '';
        $where_eo .= "  receiver_user_type_id = 19 ";
        $where_eo .= " AND status = 5 ";
        //end
        $data['dealingLevel'] = $this->model_trade_level_pending_dtl->getDealingLevelData($apply_licence_id, $where_dealing);
        $data['taxDarogaLevel'] = $this->model_trade_level_pending_dtl->getTaxDarogaLevelData($apply_licence_id, $where_tax_daroga);
        $data['sectionHeadLevel'] = $this->model_trade_level_pending_dtl->getSectionHeadLevelData($apply_licence_id, $where_sh);
        $data['executiveLevel'] = $this->model_trade_level_pending_dtl->getExecutiveLevelData($apply_licence_id, $where_eo);
        $data['levelPendinDtlId'] = $this->model_trade_level_pending_dtl->getLevelPendingDtlId($apply_licence_id);
        $data['licence_owner_id'] = $this->TradeViewApplyLicenceOwnerModel->getDatabyapplno($data['basic_details']['application_no']);
        $data['licencee'] = $this->TradeApplyLicenceModel->apply_licence_md5($id);
        $data['new_ward_no'] = $this->model_ward_mstr->getWardNoBywardId($data['licencee']['new_ward_mstr_id']);


        //application status
        if ($data['licencee']["document_upload_status"] == 0) {
            $data["application_status"] = '<span style="color:#167b3b">Pending AT JSK Created Successfully!</span>';
        } elseif ($data['licencee']["pending_status"] == 5) {
            if ($data['licencee']["application_type_id"] == 4) {
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
            } elseif (($data['licencee']["payment_status"] > 0) and ($data['licencee']["document_upload_status"] == 0 || $data['licencee']["document_upload_status"] == 2)) {
                $data["application_status"] = "Pending at JSK ";
            } elseif (($data['licencee']["payment_status"] == 0) and ($data['licencee']["document_upload_status"] == 0 || $data['licencee']["document_upload_status"] == 2)) {
                $data["application_status"] = "Pending at JSK ";
            }
        }
        //end
        // print_var($data);return;
        return view('trade/Connection/trade_application_details', $data);
    }



    public function municipalLicence_copy($id = null)
    {
        $data = (array)null;
        $Session = Session();
        date_default_timezone_set('Asia/Kolkata');
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $ulb_mstr_id = $data['ulb_mstr_id'];
        $emp_mstr = $Session->get("emp_details");
        $path = base_url('citizenPaymentReceipt/municipal_licence/' . $ulb_mstr_id . '/' . $id);
        $data['ss'] = qrCodeGeneratorFun($path);
        $login_emp_details_id = $emp_mstr["id"];
        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        $data['ulb'] = $this->model_ulb_mstr->getulb_list($data['ulb_mstr_id']);
        $data['basic_details'] = $this->model_view_trade_licence->getDatabyid($id);
        $apply_licence_id = $data['basic_details']['id'];
        $data['licence_dtl'] = $this->model_apply_licence->getDatabyid($apply_licence_id);
        $data['item_details'] = $this->model_trade_items_mstr->get_nature_business($data['licence_dtl']['nature_of_bussiness']);
        $data['prop_dtl'] = $this->model_prop_dtl->getPropdetailsbyid($data['licence_dtl']['prop_dtl_id']);
        $data['prop_owner_dtl'] = $this->model_prop_owner_detail->propownerdetails($data['licence_dtl']['prop_dtl_id']);
        $data['ward']  = $this->model_ward_mstr->getdatabyid($data['basic_details']['ward_mstr_id']);
        $data['owner_details'] = $this->model_firm_owner_name->applicantdetails($apply_licence_id);
        return view('trade/Connection/licenceView', $data);
    }

    public function municipalLicence($id = null)
    {
        return $this->response->redirect(base_url("Trade_EO/municipal_licence/$id"));
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
        $data["valid_upto"] = date('Y-m-d', strtotime(date("$vUpto", mktime()) . " + 20 day"));
        $apply_licence_id = $data['basic_details']['id'];
        $data['item_details'] = $this->TradeTradeItemsModel->getdatabyid_md5($id);
        $data['tranProvDtl'] = $this->TradeTransactionModel->tranProvDtl($id);
        return view('trade/Connection/provitionalLicence', $data);
    }

    public function licence_status()
    {
        $data = (array)null;
        $Session = Session();
        if ($this->request->getMethod() == 'post') {
            try {

                ## Read value
                $start = sanitizeString($this->request->getVar('start'));

                $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page

                $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
                $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
                if ($columnName == "s_no" || $columnName == "application_no")
                    $columnName = 'apply.application_no';
                if ($columnName == "license_no")
                    $columnName = 'apply.license_no';
                if ($columnName == "firm_name")
                    $columnName = 'apply.firm_name';
                if ($columnName == "application_type")
                    $columnName = 'tbl_application_type.application_type';
                if ($columnName == "apply_date")
                    $columnName = 'apply.apply_date';
                if ($columnName == "view")
                    $columnName = 'view';

                $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
                $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value

                // Date filter
                $valid = sanitizeString($this->request->getVar('valid'));
                $licence = sanitizeString($this->request->getVar('licence'));
                $ward_id = sanitizeString($this->request->getVar('ward_id'));
                $fromDate = sanitizeString($this->request->getVar('from_date'));
                $uptoDate = sanitizeString($this->request->getVar('upto_date'));
                $todaysdate = date('Y-m-d');
                $searchQuery = "";
                $whereQuery = "";

                $orderBY = " ORDER BY (substring(view_ward_mstr.ward_no, '^[0-9]+'))::int,ward_no ," . $columnName . " " . $columnSortOrder;
                $limit = " LIMIT " . $rowperpage . " OFFSET " . $start;

                if ($licence == "") {
                    if ($valid == "valid") {
                        $whereQuery .= " AND  apply.valid_upto IS NOT NULL ";
                        $whereQuery .= " AND  apply.license_no IS NOT NULL ";
                        if($fromDate && $uptoDate){
                            $whereQuery .= " AND  apply.valid_upto BETWEEN '$fromDate' AND '$uptoDate' ";
                        }else{
                            $whereQuery .= " AND  apply.valid_upto >= '" . $todaysdate . "' ";
                        }
                    } elseif ($valid == "tobeExpir") {
                        $whereQuery .= " AND  apply.valid_upto IS NOT NULL And apply.update_status=0 ";
                        $whereQuery .= " AND  apply.license_no IS NOT NULL ";
                        if($fromDate && $uptoDate){
                            $whereQuery .= " AND  apply.valid_upto BETWEEN '$fromDate' AND '$uptoDate' ";
                        }else{
                        $whereQuery .= " AND  apply.valid_upto BETWEEN '" . $todaysdate . "' AND '" . date('Y-m-d', strtotime($todaysdate . ' + 30 days')) . "' ";
                        }
                    } else {
                        $whereQuery .= " AND  apply.valid_upto IS NOT NULL And apply.update_status=0";
                        $whereQuery .= " AND  apply.license_no IS NOT NULL ";
                        if($fromDate && $uptoDate){
                            $whereQuery .= " AND  apply.valid_upto BETWEEN '$fromDate' AND '$uptoDate' ";
                        }else{
                            $whereQuery .= " AND   apply.valid_upto < '" . $todaysdate . "' ";
                        }
                    }
                } else {
                    $whereQuery .= " AND  apply.valid_upto IS NOT NULL ";
                    $whereQuery .= " AND  apply.license_no IS NOT NULL ";
                    $whereQuery .= " AND  (apply.license_no = '" . $licence . "' OR apply.firm_name =  '" . $licence . "')";
                }

                if (strtoupper($ward_id) != "ALL") {
                    $whereQuery .= "AND  apply.ward_mstr_id = $ward_id ";
                }

                $whereQueryWithSearch = "";
                if ($searchValue != '') {
                    $whereQueryWithSearch = " AND (apply.application_no ILIKE '%" . $searchValue . "%'
                    OR apply.firm_name ILIKE '%" . $searchValue . "%'
                    OR apply.license_no ILIKE '%" . $searchValue . "%'
                    OR tbl_application_type.application_type ILIKE '%" . $searchValue . "%'
                    OR apply.application_no ILIKE '%" . $searchValue . "%')";
                }


                $selectStatement = "SELECT 
                ROW_NUMBER () OVER (ORDER BY " . $columnName . ") AS s_no,
                    view_ward_mstr.ward_no,				  
                    apply.application_no,
                    apply.firm_name,
                    apply.license_no,
                    apply.valid_upto,
                    tbl_application_type.application_type,
                    apply.apply_date,
                    concat('<a class=', chr(39),'btn btn-primary', chr(39), 'href=" . base_url() . "/trade_da/view_application_details/',md5(apply.id::text),' role=button>View</a>') as view";
                $sql =  " FROM tbl_apply_licence apply  
                    INNER JOIN tbl_application_type_mstr tbl_application_type ON tbl_application_type.id=apply.application_type_id                      
                    LEFT JOIN view_ward_mstr on view_ward_mstr.id = apply.ward_mstr_id
                    WHERE apply.pending_status = 5 
                        and apply.application_type_id in(1,2,3) " . $whereQuery;
                $totalRecords = $this->model_datatable->getTotalRecords($sql);
                if ($totalRecords > 0) {
                    ## Total number of records with filtering
                    $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($sql . $whereQueryWithSearch);
                    ## Fetch records
                    $fetchSql = $selectStatement . $sql . $whereQueryWithSearch . $orderBY . $limit;

                    $records = $this->model_datatable->getRecords($fetchSql);
                } else {
                    $totalRecordwithFilter = 0;
                    $records = [];
                }
                //echo $this->db->getLastQuery();
                $response = array(
                    "iTotalRecords" => $totalRecords,
                    "iTotalDisplayRecords" => $totalRecordwithFilter,
                    "aaData" => $records,
                    "total" => '<b style="padding-right:20px">Total :-' . $totalRecords . '</b>',
                );
                return json_encode($response);
            } catch (Exception $e) {
            }
        } else {
            $data['ward_list'] = $this->model_ward_mstr->getWardList($Session->get("ulb_dtl"));
            return view('trade/Connection/licence_status', $data);
        }
    }

    public function licence_statusExcel($valid = null, $ward_id = "All",$fromDate=null,$uptoDate= null, $licence = null)
    {
        try {

            $todaysdate = date('Y-m-d');
            $searchQuery = "";
            $whereQuery = "";
            $limit = " LIMIT 1";
            $fromDate = $fromDate!="from_date"?$fromDate : null;
            $uptoDate = $uptoDate!="upto_date"?$uptoDate : null;

            if ($licence == "") {
                if ($valid == "valid") {
                    $whereQuery .= " AND  apply.valid_upto IS NOT NULL And apply.update_status=0";
                    $whereQuery .= " AND  apply.license_no IS NOT NULL ";
                    if($fromDate && $uptoDate){
                        $whereQuery .= " AND  apply.valid_upto BETWEEN '$fromDate' AND '$uptoDate' ";
                    }else{
                        $whereQuery .= " AND  apply.valid_upto >= '" . $todaysdate . "' ";
                    }
                } elseif ($valid == "tobeExpir") {
                    $whereQuery .= " AND  apply.valid_upto IS NOT NULL And apply.update_status=0";
                    $whereQuery .= " AND  apply.license_no IS NOT NULL ";
                    if($fromDate && $uptoDate){
                        $whereQuery .= " AND  apply.valid_upto BETWEEN '$fromDate' AND '$uptoDate' ";
                    }else{
                        $whereQuery .= " AND  apply.valid_upto BETWEEN '" . $todaysdate . "' AND '" . date('Y-m-d', strtotime($todaysdate . ' + 30 days')) . "' ";
                    }
                } else {
                    $whereQuery .= " AND  apply.valid_upto IS NOT NULL And apply.update_status=0";
                    $whereQuery .= " AND  apply.license_no IS NOT NULL ";
                    if($fromDate && $uptoDate){
                        $whereQuery .= " AND  apply.valid_upto BETWEEN '$fromDate' AND '$uptoDate' ";
                    }else{
                        $whereQuery .= " AND   apply.valid_upto < '" . $todaysdate . "' ";
                    }
                }
            } else {
                $whereQuery .= " AND  apply.valid_upto IS NOT NULL ";
                $whereQuery .= " AND  apply.license_no IS NOT NULL ";
                $whereQuery .= " AND  (apply.license_no = '" . $licence . "' OR apply.firm_name =  '" . $licence . "')";
            }

            if (strtoupper($ward_id) != "ALL") {
                $whereQuery .= "AND  apply.ward_mstr_id = $ward_id ";
            }

            $with = "with items as(
                        select apply.id as apply_licence_id,
                            string_agg(tbl_trade_items_mstr.id::text,',')as ids,
                            string_agg(trade_item,',') as trade_item
                            from tbl_trade_items_mstr
                        join tbl_apply_licence apply on tbl_trade_items_mstr.id::text in (apply.nature_of_bussiness)
                        WHERE apply.pending_status = 5 
                            and apply.application_type_id in(1,2,3)  $whereQuery
                        group by apply.id
                    ) ";
            $selectStatement = "SELECT             			  
                apply.application_no,
                apply.firm_name,
                apply.license_no,
                apply.valid_upto,
                apply.pan_no,
                apply.holding_no,
                apply.address,
                apply.license_date,
                apply.area_in_sqft,
                tbl_application_type.application_type,
                apply.apply_date,
                owner.owner_name,
                owner.mobile,
                view_ward_mstr.ward_no,
                items.trade_item
                ";
            $sql =  " FROM tbl_apply_licence apply  
                INNER JOIN tbl_application_type_mstr tbl_application_type ON tbl_application_type.id=apply.application_type_id    
                Left Join (
                            select distinct(apply_licence_id) as apply_licence_id,
                                string_agg(owner_name::text, ',' ) as owner_name,
                                string_agg(mobile::text, ',' ) as mobile 
                            from tbl_firm_owner_name
                            where status = 1
                            group by apply_licence_id
                        ) owner on owner.apply_licence_id = apply.id    
                Join view_ward_mstr on view_ward_mstr.id = apply.ward_mstr_id  
                left Join items on items.apply_licence_id = apply.id             
                WHERE apply.pending_status = 5 
                    and apply.application_type_id in(1,2,3) " . $whereQuery . " 
                ORDER BY (substring(view_ward_mstr.ward_no, '^[0-9]+'))::int,ward_no";
            ## Total number of records without filtering
            $fetchSql = $with . $selectStatement . $sql;
            $result = $this->model_datatable->getRecords($fetchSql, false);
            // print_var($fetchSql);
            // die();
            $records = [];
            if ($result) {
                foreach ($result as $key => $tran_dtl) {


                    $records[] = [
                        'ward_no' => $tran_dtl['ward_no'],
                        'application_no' => $tran_dtl['application_no'],
                        'license_no' => $tran_dtl['license_no'],
                        'address' => $tran_dtl['address'],
                        'license_date' => $tran_dtl['license_date'],
                        'valid_upto' => $tran_dtl['valid_upto'],

                        'firm_name' => $tran_dtl['firm_name'],
                        'area_in_sqft' => $tran_dtl['area_in_sqft'],
                        'pan_no' => $tran_dtl['pan_no'],
                        'holding_no' => $tran_dtl['holding_no'],
                        'trade_item' => $tran_dtl['trade_item'],
                        'application_type' => $tran_dtl['application_type'],
                        'owner_name' => $tran_dtl['owner_name'],
                        'mobile' => $tran_dtl['mobile'],
                    ];
                }
            }
            //phpOfficeLoad();
            $spreadsheet = new Spreadsheet();
            $activeSheet = $spreadsheet->getActiveSheet();
            $activeSheet->setCellValue('A1', 'Ward No.');
            $activeSheet->setCellValue('B1', 'Application No.');
            $activeSheet->setCellValue('C1', 'License No.');
            $activeSheet->setCellValue('D1', 'Address');
            $activeSheet->setCellValue('E1', 'Issued Date');
            $activeSheet->setCellValue('F1', 'Valid Upto.');
            $activeSheet->setCellValue('G1', 'Firm Name');
            $activeSheet->setCellValue('H1', 'Area');
            $activeSheet->setCellValue('I1', 'Pan No');
            $activeSheet->setCellValue('J1', 'Holding No.');
            $activeSheet->setCellValue('K1', 'Business Nature');
            $activeSheet->setCellValue('L1', 'Application Type');
            $activeSheet->setCellValue('M1', 'Owner Name');
            $activeSheet->setCellValue('N1', 'Mobile No');

            $activeSheet->fromArray($records, NULL, 'A2');
            $filename = "gb_saf_collection_report_" . date('Ymd-hisa') . ".xlsx";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            $writer = new Xlsx($spreadsheet);
            //$writer->save(APPPATH.'/hello world.xlsx');
            ob_end_clean();
            $writer->save('php://output');
        } catch (Exception $e) {
            print_r($e);
        }
    }

    public function da_back_to_citizen_list()
    {
        $data = (array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        //print_r($emp_mstr);
        $login_emp_details_id = $emp_mstr["id"];
        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['wardList'] = $wardList;

        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        $ward = "";

        $i = 0;
        foreach ($wardList as $key => $value) {
            if ($i == 0) {
                $ward = array($value['ward_mstr_id']);
            } else {
                array_push($ward, $value['ward_mstr_id']);
            }
            $i++;
        }
        if ($this->request->getMethod() == 'post') {
            $data['ward_mstr_id'] = $this->request->getVar('ward_mstr_id');
            $data['from_date'] = $this->request->getVar('from_date');
            $data['to_date'] = $this->request->getVar('to_date');
            if ($data['ward_mstr_id'] != "") {
                $data['posts'] = $this->model_apply_licence->wardwiseboc_saf_list($data['from_date'], $data['to_date'], $data['ward_mstr_id']);
            } else {
                $data['posts'] = $this->model_apply_licence->boc_saf_list($data['from_date'], $data['to_date'], $ward);
            }

            //print_r($data['posts']);
            $j = 0;
            foreach ($data['posts'] as $key => $value) {
                $wardd = $this->model_ward_mstr->getdatabyid($value['ward_mstr_id']);
                $owner = $this->model_firm_owner_name->applicantdetails($value['id']);
                $j = 0;
                foreach ($owner as $keyy => $val) {
                    //$ow[$key][$keyy]['owner']= $val["owner_name"];
                    if ($j == 0) {
                        $data['posts'][$key]['owner_name'] = array($val["owner_name"]);
                        $data['posts'][$key]['mobile_no'] = array($val["mobile"]);
                    } else {
                        array_push($data['posts'][$key]['owner_name'], $val["owner_name"]);
                        array_push($data['posts'][$key]['mobile_no'], $val["mobile"]);
                    }
                    $j++;
                }
                $data['posts'][$key]['ward_no'] = $wardd['ward_no'];
            }
            return view('trade/Connection/da_back_to_citizen_list', $data);
        } else {
            $data['from_date'] = date('Y-m-d');
            $data['to_date'] = date('Y-m-d');
            $data['posts'] = $this->model_apply_licence->boc_saf_list($data['from_date'], $data['to_date'], $ward);
            //print_r($data['posts']);
            $j = 0;
            foreach ($data['posts'] as $key => $value) {
                $wardd = $this->model_ward_mstr->getdatabyid($value['ward_mstr_id']);

                $owner = $this->model_firm_owner_name->applicantdetails($value['id']);
                $j = 0;
                foreach ($owner as $keyy => $val) {
                    //$ow[$key][$keyy]['owner']= $val["owner_name"];
                    if ($j == 0) {
                        $data['posts'][$key]['owner_name'] = array($val["owner_name"]);
                        $data['posts'][$key]['mobile_no'] = array($val["mobile"]);
                    } else {
                        array_push($data['posts'][$key]['owner_name'], $val["owner_name"]);
                        array_push($data['posts'][$key]['mobile_no'], $val["mobile"]);
                    }
                    $j++;
                }
                $data['posts'][$key]['ward_no'] = $wardd['ward_no'];
            }
            //print_r($data['posts']);
            return view('trade/Connection/da_back_to_citizen_list', $data);
        }
    }

    public function boc_document_verification_view($id)
    {
        $data = (array)null;
        $Session = Session();
        date_default_timezone_set('Asia/Kolkata');
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");

        $login_emp_details_id = $emp_mstr["id"];
        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        $data['basic_details'] = $this->model_view_trade_licence->getDatabyid($id);
        $data['ward'] = $this->model_ward_mstr->getdatabyid($data['basic_details']['ward_mstr_id']);
        $data['owner_details'] = $this->model_firm_owner_name->applicantdetails($data['basic_details']['id']);
        $data['holding'] = $this->model_apply_licence->getholding($data['basic_details']['application_no']);
        $verify_status = '0';
        foreach ($data['owner_details'] as $key => $value) {
            $app__doc = $value['owner_name'] . '(Consumer Photo)';
            $data['owner_details'][$key]['saf_owner_img_list'] = $this->model_application_doc->get_ownerimgdetails_by_safid($data['basic_details']['id'], $value['id'], $app__doc);

            $app_doc_type = "Identity Proof";
            $data['owner_details'][$key]['saf_owner_doc_list'] = $this->model_application_doc->conownerdocdetbyid($data['basic_details']['id'], $value['id'], $app_doc_type);
        }
        //print_var($data['owner_details']);
        $apply_licence_id = $data['basic_details']['id'];

        $business_doc = "Business Premises";
        $data['business_doc_exists'] = $this->model_application_doc->getdocdet_by_conid($apply_licence_id, $business_doc);

        $noc_doc = "NOC And NOC Affidavit Document";
        $data['noc_doc_exists'] = $this->model_application_doc->getdocdet_by_conid($apply_licence_id, $noc_doc);

        $partnership_doc = "Partnership Document";
        $data['partnership_doc_exists'] = $this->model_application_doc->getdocdet_by_conid($apply_licence_id, $partnership_doc);

        $sapat_patra_doc = "Sapat Patra";
        $data['sapat_patra_doc_exists'] = $this->model_application_doc->getdocdet_by_conid($apply_licence_id, $sapat_patra_doc);

        $solid_waste_doc = "Solid Waste User Charge Document";
        $data['solid_waste_doc_exists'] = $this->model_application_doc->getdocdet_by_conid($apply_licence_id, $solid_waste_doc);

        $electricity_doc = "Electricity Bill";
        $data['electricity_doc_exists'] = $this->model_application_doc->getdocdet_by_conid($apply_licence_id, $electricity_doc);

        $application_doc = "Application Form";
        $data['application_doc_exists'] = $this->model_application_doc->getdocdet_by_conid($apply_licence_id, $application_doc);

        $data['remark'] = $this->model_trade_level_pending_dtl->backtocitizen_dl_remarks_by_con_id($apply_licence_id);
        // print_var($data['remark']);die();
        $data['basic_details']['remarks'] = $data['remark']['remarks'];

        return view('trade/Connection/da_back_to_citizen_view', $data);
    }
    public function da_approved_list()
    {
        $data = (array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];

        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['wardList'] = $wardList;
        //print_r($emp_mstr);

        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        $ward = "";

        $i = 0;
        foreach ($wardList as $key => $value) {
            if ($i == 0) {
                $ward = array($value['ward_mstr_id']);
            } else {
                array_push($ward, $value['ward_mstr_id']);
            }
            $i++;
        }
        if ($this->request->getMethod() == 'post') {
            $data['ward_mstr_id'] = $this->request->getVar('ward_mstr_id');
            $data['from_date'] = $this->request->getVar('from_date');
            $data['to_date'] = $this->request->getVar('to_date');
            if ($data['ward_mstr_id'] != "") {
                $data['posts'] = $this->model_view_trade_level_pending->wardwise_daapprovedList($sender_user_type_id, $data['from_date'], $data['to_date'], $data['ward_mstr_id']);
            } else {
                $data['posts'] = $this->model_view_trade_level_pending->daapprovedList($sender_user_type_id, $data['from_date'], $data['to_date'], $ward);
            }
            //print_r($data['posts']);
            $j = 0;
            foreach ($data['posts'] as $key => $value) {
                $wardd = $this->model_ward_mstr->getdatabyid($value['ward_mstr_id']);
                $owner = $this->model_firm_owner_name->applicantdetails($value['apply_licence_id']);
                $j = 0;
                foreach ($owner as $keyy => $val) {
                    //$ow[$key][$keyy]['owner']= $val["owner_name"];
                    if ($j == 0) {
                        $data['posts'][$key]['owner_name'] = array($val["owner_name"]);
                        $data['posts'][$key]['mobile_no'] = array($val["mobile"]);
                    } else {
                        array_push($data['posts'][$key]['owner_name'], $val["owner_name"]);
                        array_push($data['posts'][$key]['mobile_no'], $val["mobile"]);
                    }
                    $j++;
                }
                $data['posts'][$key]['ward_no'] = $wardd['ward_no'];
            }
            return view('trade/Connection/da_approved_list', $data);
        } else {
            $data['from_date'] = date('Y-m-d');
            $data['to_date'] = date('Y-m-d');
            $data['posts'] = $this->model_view_trade_level_pending->daapprovedList($sender_user_type_id, $data['from_date'], $data['to_date'], $ward);
            // print_var($data['posts']);return;
            $j = 0;
            foreach ($data['posts'] as $key => $value) {
                $wardd = $this->model_ward_mstr->getdatabyid($value['ward_mstr_id']);
                $owner = $this->model_firm_owner_name->applicantdetails($value['apply_licence_id']);
                $j = 0;
                foreach ($owner as $keyy => $val) {
                    //$ow[$key][$keyy]['owner']= $val["owner_name"];
                    if ($j == 0) {
                        $data['posts'][$key]['owner_name'] = array($val["owner_name"]);
                        $data['posts'][$key]['mobile_no'] = array($val["mobile"]);
                    } else {
                        array_push($data['posts'][$key]['owner_name'], $val["owner_name"]);
                        array_push($data['posts'][$key]['mobile_no'], $val["mobile"]);
                    }
                    $j++;
                }
                $data['posts'][$key]['ward_no'] = $wardd['ward_no'];
            }
            return view('trade/Connection/da_approved_list', $data);
        }
        // print_r($data['posts']);
    }
    public function da_approved_view($id)
    {
        $data = (array)null;
        $Session = Session();
        date_default_timezone_set('Asia/Kolkata');
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");

        $login_emp_details_id = $emp_mstr["id"];
        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        $data['form'] = $this->model_view_trade_level_pending->tradelevelpendingdetailbyid($id);

        // print_var($data['form']);
        $data['basic_details'] = $this->model_view_trade_licence->getDatabyid(md5($data['form']['apply_licence_id']));
        $data['ward']  = $this->model_ward_mstr->getdatabyid($data['form']['ward_mstr_id']);
        // print_var($data['ward']);return;
        $data['form']['ward_no'] = $data['ward']['ward_no'];
        $data['owner_details'] = $this->model_firm_owner_name->applicantdetails($data['form']['apply_licence_id']);
        $data['holding'] = $this->model_apply_licence->getholding($data['basic_details']['application_no']);
        $verify_status = '1';

        // return;
        foreach ($data['owner_details'] as $key => $value) {
            // $app__doc='Consumer Photo';
            // $data['owner_details'][$key]['saf_owner_img_list'] = $this->model_application_doc->get_verifiedownerimgdetails_by_safid($data['basic_details']['id'],$value['id'],$app__doc);

            // $app_doc_type="Identity Proof";
            // $data['owner_details'][$key]['saf_owner_doc_list'] = $this->model_view_application_doc->get_verifiedownerdocdetails_by_conid($data['basic_details']['id'],$value['id'],$app_doc_type);

            $app__doc = 'Consumer Photo';
            $data['owner_details'][$key]['saf_owner_img_list'] = $this->model_application_doc->get_ownerimgdetails_by_safid($data['basic_details']['id'], $value['id'], $app__doc);

            $app_doc_type = "Identity Proof";
            $data['owner_details'][$key]['saf_owner_doc_list'] = $this->model_application_doc->conownerdocdetbyid($data['basic_details']['id'], $value['id'], $app_doc_type);

            //print_var($value);
        }
        //print_var($data['owner_details']);
        $apply_licence_id = $data['basic_details']['id'];

        $business_doc = "Business Premises";
        $data['business_doc_exists'] = $this->model_application_doc->getdocdet_by_conid($apply_licence_id, $business_doc);

        $tanent_doc = "Tanented";
        $data['tanent_doc_exists'] = $this->model_application_doc->getdocdet_by_conid($apply_licence_id, $tanent_doc);

        $Pvt_doc = "Pvt. Ltd. OR Ltd. Company";
        $data['Pvt_doc_exists'] = $this->model_application_doc->getdocdet_by_conid($apply_licence_id, $Pvt_doc);

        //print_r($data['business_doc_exists']);
        $noc_doc = "NOC And NOC Affidavit Document";
        $data['noc_doc_exists'] = $this->model_application_doc->getdocdet_by_conid($apply_licence_id, $noc_doc);

        $partnership_doc = "Partnership Document";
        $data['partnership_doc_exists'] = $this->model_application_doc->getdocdet_by_conid($apply_licence_id, $partnership_doc);

        $sapat_patra_doc = "Sapat Patra";
        $data['sapat_patra_doc_exists'] = $this->model_application_doc->getdocdet_by_conid($apply_licence_id, $sapat_patra_doc);

        $solid_waste_doc = "Solid Waste User Charge Document";
        $data['solid_waste_doc_exists'] = $this->model_application_doc->getdocdet_by_conid($apply_licence_id, $solid_waste_doc);

        $electricity_doc = "Electricity Bill";
        $data['electricity_doc_exists'] = $this->model_application_doc->getdocdet_by_conid($apply_licence_id, $electricity_doc);

        $application_doc = "Application Form";
        $data['application_doc_exists'] = $this->model_application_doc->getdocdet_by_conid($apply_licence_id, $application_doc);

        $data['remark'] = $this->model_trade_level_pending_dtl->approved_dl_remarks_by_con_id($apply_licence_id);
        // print_var($data['remark']);

        // return;

        $data['basic_details']['remarks'] = $data['remark']['remarks'] ?? null;

        return view('trade/Connection/da_approved_view', $data);
    }
    public function forward_list()
    {
        $data = (array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];

        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['wardList'] = $wardList;
        //print_r($emp_mstr);

        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        $ward = "";

        $i = 0;
        foreach ($wardList as $key => $value) {
            if ($i == 0) {
                $ward = array($value['ward_mstr_id']);
            } else {
                array_push($ward, $value['ward_mstr_id']);
            }
            $i++;
        }
        if ($this->request->getMethod() == 'post') {
            $data['ward_mstr_id'] = $this->request->getVar('ward_mstr_id');
            $data['from_date'] = $this->request->getVar('from_date');
            $data['to_date'] = $this->request->getVar('to_date');
            if ($data['ward_mstr_id'] != "") {
                $data['posts'] = $this->model_view_trade_level_pending->wardwise_forwardList($sender_user_type_id, $data['from_date'], $data['to_date'], $data['ward_mstr_id']);
            } else {
                $data['posts'] = $this->model_view_trade_level_pending->forwardList($sender_user_type_id, $data['from_date'], $data['to_date'], $ward);
            }

            //print_r($data['posts']);
            $j = 0;
            foreach ($data['posts'] as $key => $value) {
                $wardd = $this->model_ward_mstr->getdatabyid($value['ward_mstr_id']);
                $user_type_nm = $this->model_user_type_mstr->getdatabyid(md5($value['receiver_user_type_id']));
                $owner = $this->model_firm_owner_name->applicantdetails($value['apply_licence_id']);
                $j = 0;
                foreach ($owner as $keyy => $val) {
                    //$ow[$key][$keyy]['owner']= $val["owner_name"];
                    if ($j == 0) {
                        $data['posts'][$key]['owner_name'] = array($val["owner_name"]);
                        $data['posts'][$key]['mobile_no'] = array($val["mobile"]);
                    } else {
                        array_push($data['posts'][$key]['owner_name'], $val["owner_name"]);
                        array_push($data['posts'][$key]['mobile_no'], $val["mobile"]);
                    }
                    $j++;
                }
                $data['posts'][$key]['ward_no'] = $wardd['ward_no'];
                $data['posts'][$key]['user_type'] = $user_type_nm['user_type'];
            }
            return view('trade/Connection/trade_forward_list', $data);
        } else {
            $data['from_date'] = date('Y-m-d');
            $data['to_date'] = date('Y-m-d');
            $data['posts'] = $this->model_view_trade_level_pending->forwardList($sender_user_type_id, $data['from_date'], $data['to_date'], $ward);
            //print_r($data['posts']);
            $j = 0;
            foreach ($data['posts'] as $key => $value) {
                $wardd = $this->model_ward_mstr->getdatabyid($value['ward_mstr_id']);
                $user_type_nm = $this->model_user_type_mstr->getdatabyid(md5($value['receiver_user_type_id']));
                $owner = $this->model_firm_owner_name->applicantdetails($value['apply_licence_id']);
                $j = 0;
                foreach ($owner as $keyy => $val) {
                    //$ow[$key][$keyy]['owner']= $val["owner_name"];
                    if ($j == 0) {
                        $data['posts'][$key]['owner_name'] = array($val["owner_name"]);
                        $data['posts'][$key]['mobile_no'] = array($val["mobile"]);
                    } else {
                        array_push($data['posts'][$key]['owner_name'], $val["owner_name"]);
                        array_push($data['posts'][$key]['mobile_no'], $val["mobile"]);
                    }
                    $j++;
                }
                $data['posts'][$key]['ward_no'] = $wardd['ward_no'];
                $data['posts'][$key]['user_type'] = $user_type_nm['user_type'];
            }
            return view('trade/Connection/trade_forward_list', $data);
        }
        // print_r($data['posts']);
    }
    public function forward_view($id)
    {
        $data = (array)null;
        $Session = Session();
        date_default_timezone_set('Asia/Kolkata');
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");

        $login_emp_details_id = $emp_mstr["id"];
        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        $data['form'] = $this->model_view_trade_level_pending->tradelevelpendingdetailbyid($id);
        $data['basic_details'] = $this->model_view_trade_licence->getDatabyid(md5($data['form']['apply_licence_id']));
        $data['ward']  = $this->model_ward_mstr->getdatabyid($data['form']['ward_mstr_id']);
        $data['form']['ward_no'] = $ward['ward_no'];
        $data['owner_details'] = $this->model_firm_owner_name->applicantdetails($data['form']['apply_licence_id']);
        $data['holding'] = $this->model_apply_licence->getholding($data['basic_details']['application_no']);
        $verify_status = '1';
        foreach ($data['owner_details'] as $key => $value) {
            $app__doc = 'Consumer Photo';
            $data['owner_details'][$key]['saf_owner_img_list'] = $this->model_application_doc->get_verifiedownerimgdetails_by_safid($data['basic_details']['id'], $value['id'], $app__doc);

            $app_doc_type = "Identity Proof";
            $data['owner_details'][$key]['saf_owner_doc_list'] = $this->model_view_application_doc->get_verifiedownerdocdetails_by_conid($data['basic_details']['id'], $value['id'], $app_doc_type);
        }
        //print_r($data['owner_details']);
        $apply_licence_id = $data['basic_details']['id'];

        $business_doc = "Business Premises";
        $data['business_doc_exists'] = $this->model_application_doc->getdocdet_by_conid($apply_licence_id, $business_doc);


        $tanent_doc = "Tanented";
        $data['tanent_doc_exists'] = $this->model_application_doc->getdocdet_by_conid($apply_licence_id, $tanent_doc);

        $Pvt_doc = "Pvt. Ltd. OR Ltd. Company";
        $data['Pvt_doc_exists'] = $this->model_application_doc->getdocdet_by_conid($apply_licence_id, $Pvt_doc);

        $noc_doc = "NOC And NOC Affidavit Document";
        $data['noc_doc_exists'] = $this->model_application_doc->getdocdet_by_conid($apply_licence_id, $noc_doc);

        $partnership_doc = "Partnership Document";
        $data['partnership_doc_exists'] = $this->model_application_doc->getdocdet_by_conid($apply_licence_id, $partnership_doc);

        $sapat_patra_doc = "Sapat Patra";
        $data['sapat_patra_doc_exists'] = $this->model_application_doc->getdocdet_by_conid($apply_licence_id, $sapat_patra_doc);

        $solid_waste_doc = "Solid Waste User Charge Document";
        $data['solid_waste_doc_exists'] = $this->model_application_doc->getdocdet_by_conid($apply_licence_id, $solid_waste_doc);

        $electricity_doc = "Electricity Bill";
        $data['electricity_doc_exists'] = $this->model_application_doc->getdocdet_by_conid($apply_licence_id, $electricity_doc);

        $application_doc = "Application Form";
        $data['application_doc_exists'] = $this->model_application_doc->getdocdet_by_conid($apply_licence_id, $application_doc);

        $data['remark'] = $this->model_trade_level_pending_dtl->forward_remarks_by_con_id($apply_licence_id, $sender_user_type_id);
        $data['basic_details']['remarks'] = $data['remark']['remarks'];

        return view('trade/Connection/trade_forward_view', $data);
    }



    public function municipal_licence($id = null)
    {
        $data = (array)null;
        $Session = Session();
        date_default_timezone_set('Asia/Kolkata');
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $ulb_mstr_id = $data['ulb_mstr_id'];
        $emp_mstr = $Session->get("emp_details");
        $path = base_url('citizenPaymentReceipt/municipal_licence/' . $ulb_mstr_id . '/' . $id);
        $data['ss'] = qrCodeGeneratorFun($path);
        $login_emp_details_id = $emp_mstr["id"];
        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        $data['ulb'] = $this->model_ulb_mstr->getulb_list($data['ulb_mstr_id']);
        $data['basic_details'] = $this->model_view_trade_licence->getDatabyid($id);
        $apply_licence_id = $data['basic_details']['id'];
        $data['licence_dtl'] = $this->model_apply_licence->getDatabyid($apply_licence_id);
        $data['item_details'] = $this->model_trade_items_mstr->get_nature_business($data['licence_dtl']['nature_of_bussiness']);
        $data['prop_dtl'] = $this->model_prop_dtl->getPropdetailsbyid($data['licence_dtl']['prop_dtl_id']);
        $data['prop_owner_dtl'] = $this->model_prop_owner_detail->propownerdetails($data['licence_dtl']['prop_dtl_id']);
        $data['ward']  = $this->model_ward_mstr->getdatabyid($data['basic_details']['ward_mstr_id']);
        $data['owner_details'] = $this->model_firm_owner_name->applicantdetails($apply_licence_id);
        return view('trade/Connection/licence_trade', $data);
    }

    public function application_status($md5id)
    {
        $data['licencee'] = $this->TradeApplyLicenceModel->apply_licence_md5($md5id); //print_var($data['licencee']);
        $data["application_status"] = '';
        if ($data['licencee']["pending_status"] == 5) {
            if ($data['licencee']["application_type_id"] == 4) {
                $data["application_status"] = '<span style="color:#167b3b">Licence Surrender Successfully!</span>';
            } else {
                $data["application_status"] = '<span style="color:#167b3b">Licence Created Successfully!</span>';
                if (!empty($data['licencee']["valid_upto"])) {
                    $valid_date = date('Y-m-d', strtotime($data['licencee']["valid_upto"]));
                    $c_date = date('Y-m-d');
                    if ($valid_date == $c_date)
                        $data["application_status"] = '<span style="color:red">Licence Valide Only Today!</span>';
                    elseif ($valid_date < $c_date)
                        $data["application_status"] = '<span style="color:red">Licence Expired!</span>';
                }
            }
        } elseif ($data['licencee']["pending_status"] == 4) {
            $data["application_status"] = '<span style="color:red">Licence Rejected!</span>';
        } elseif ($data['licencee']["pending_status"] == 2) {
            $data["level"] = $this->model_trade_level_pending_dtl->get_receiver_user_type_id_orderbydesc($md5id);
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
        } elseif (($data['licencee']["payment_status"] == 0) and ($data['licencee']["document_upload_status"] == 1)) {
            $data["level"] = $this->model_trade_level_pending_dtl->get_receiver_user_type_id_orderbydesc($md5id);

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
                $data["level"] = $this->model_trade_level_pending_dtl->get_receiver_user_type_id_orderbydesc($md5id);

                if ($data["level"]["receiver_user_type_id"] == 17) {
                    $data["application_status"] = "Pending At Dealing Assistant";
                } elseif ($data["level"]["receiver_user_type_id"] == 18) {
                    $data["application_status"] = "Pending At Section Head";
                } elseif ($data["level"]["receiver_user_type_id"] == 19) {
                    $data["application_status"] = "Pending At Executive Officer";
                } elseif ($data["level"]["receiver_user_type_id"] == 20) {
                    $data["application_status"] = "Pending At Tax Daroga";
                }
            } elseif (($data['licencee']["payment_status"] > 0) and ($data['licencee']["document_upload_status"] == 0 || $data['licencee']["document_upload_status"] == 2)) {
                $data["application_status"] = "Pending at Back Office";
            } elseif (($data['licencee']["payment_status"] == 0) and ($data['licencee']["document_upload_status"] == 0 || $data['licencee']["document_upload_status"] == 2)) {
                $data["application_status"] = "Pending at Back Office";
            }
        }
        //print_var($data["application_status"]);
        return $data["application_status"];
    }
}
