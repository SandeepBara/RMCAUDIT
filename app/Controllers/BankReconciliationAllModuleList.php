<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_datatable;

use App\Models\Water_Cheque_Details_Model;
use App\Models\Water_Transaction_Model;
use App\Models\water_bank_reconcilation_model;
use App\Models\model_transaction;
use App\Models\model_prop_dtl;
use App\Models\model_saf_dtl;
use App\Models\model_view_transaction;
use App\Models\model_cheque_details;
use App\Models\model_bank_recancilation;
use App\Models\model_saf_collection;
use App\Models\model_collection;
use App\Models\model_saf_demand;
use App\Models\model_prop_demand;
use App\Models\model_level_pending_dtl;
use App\Models\water_level_pending_model;
use App\Models\TradeTransactionModel;
use App\Models\TradeChequeDtlModel;
use App\Models\TradeBankRecancilationModel;
use App\Models\model_trade_level_pending_dtl;
use App\Models\WaterApplyNewConnectionModel;
use App\Models\TradeApplyLicenceModel;
use App\Models\WaterConnectionChargeModel;
use App\Models\WaterConsumerCollectionModel;
use App\Models\WaterConsumerDemandModel;
use App\Models\WaterPenaltyInstallmentModel;
use App\Models\model_apply_licence;
use App\Models\model_govt_bank_recancilation;
use App\Models\model_govt_saf_transaction;
use App\Models\model_govt_saf_collection_dtl;
use App\Models\model_govt_saf_demand_dtl;
use App\Models\model_govt_saf_level_pending_dtl;
use App\Models\model_water_level_pending_dtl;
use App\Models\model_g_saf;
use App\Models\WaterPenaltyModel;
use App\Models\model_penalty_dtl;
use App\Models\model_advance_mstr;
use App\Models\model_adjustment_mstr;
use App\Models\WaterViewConnectionFeeModel;
use Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class BankReconciliationAllModuleList extends AlphaController
{
    protected $db;
    protected $water;
    protected $trade;
    protected $Water_Cheque_Details_Model;
    protected $Water_Transaction_Model;
    protected $water_applicant_details_model;
    protected $water_bank_reconcilation_model;
    protected $water_consumer_details_model;
    protected $model_transaction;
    protected $model_prop_dtl;
    protected $model_saf_dtl;
    protected $model_saf_owner_detail;
    protected $model_view_transaction;
    protected $model_prop_owner_detail;
    protected $model_cheque_details;
    protected $model_bank_recancilation;
    protected $model_saf_collection;
    protected $model_collection;
    protected $model_saf_demand;
    protected $model_prop_demand;
    protected $model_level_pending_dtl;
    protected $water_level_pending_model;
    protected $TradeTransactionModel;
    protected $TradeChequeDtlModel;
    protected $TradeBankRecancilationModel;
    protected $model_trade_level_pending_dtl;
    protected $WaterApplyNewConnectionModel;
    protected $penalty_installment_model;
    protected $TradeApplyLicenceModel;
    protected $WaterConnectionChargeModel;
    protected $model_penalty_dtl;
    protected $model_advance_mstr;
    protected $model_adjustment_mstr;
    protected $WaterPenaltyModel;
    protected $conn_fee;
    protected $WaterConsumerCollectionModel;
    protected $WaterConsumerDemandModel;
    protected $model_govt_saf_transaction;
    protected $model_datatable;


    public function __construct()
    {
        parent::__construct();
        helper(['db_helper', 'form', 'utility_helper', 'php_office_helper']);

        if ($db_name = dbConfig("property")) {
            $this->db = db_connect($db_name);
        }
        if ($db_name = dbConfig("water")) {
            $this->water = db_connect($db_name);
        }
        if ($db_name = dbConfig("trade")) {
            $this->trade = db_connect($db_name);
        }
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $this->ulb_mstr_id = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $this->login_emp_details_id = $emp_mstr["id"];

        $this->Water_Cheque_Details_Model = new Water_Cheque_Details_Model($this->water);
        $this->Water_Transaction_Model = new Water_Transaction_Model($this->water);
        $this->water_bank_reconcilation_model = new water_bank_reconcilation_model($this->water);
        $this->water_level_pending_model = new water_level_pending_model($this->water);
        $this->WaterApplyNewConnectionModel = new WaterApplyNewConnectionModel($this->water);
        $this->WaterConnectionChargeModel = new WaterConnectionChargeModel($this->water);
        $this->WaterPenaltyInstallmentModel = new WaterPenaltyInstallmentModel($this->water);
        $this->conn_fee = new WaterViewConnectionFeeModel($this->water);
        $this->model_transaction = new model_transaction($this->db);
        $this->model_prop_dtl = new model_prop_dtl($this->db);
        $this->model_saf_dtl = new model_saf_dtl($this->db);
        $this->model_view_transaction = new model_view_transaction($this->db);
        $this->model_cheque_details = new model_cheque_details($this->db);
        $this->model_bank_recancilation = new model_bank_recancilation($this->db);
        $this->model_saf_collection = new model_saf_collection($this->db);
        $this->model_collection = new model_collection($this->db);
        $this->model_saf_demand = new model_saf_demand($this->db);
        $this->model_prop_demand = new model_prop_demand($this->db);
        $this->model_level_pending_dtl = new model_level_pending_dtl($this->db);
        $this->TradeChequeDtlModel = new TradeChequeDtlModel($this->trade);
        $this->TradeTransactionModel = new TradeTransactionModel($this->trade);
        $this->TradeBankRecancilationModel = new TradeBankRecancilationModel($this->trade);
        $this->model_trade_level_pending_dtl = new model_trade_level_pending_dtl($this->trade);
        $this->TradeApplyLicenceModel = new TradeApplyLicenceModel($this->trade);
        $this->WaterConsumerCollectionModel = new WaterConsumerCollectionModel($this->water);
        $this->WaterConsumerDemandModel = new WaterConsumerDemandModel($this->water);
        $this->penalty_installment_model = new WaterPenaltyInstallmentModel($this->water);
        $this->model_apply_licence = new model_apply_licence($this->trade);
        $this->model_govt_bank_recancilation = new model_govt_bank_recancilation($this->db);
        $this->model_govt_saf_transaction = new model_govt_saf_transaction($this->db);
        $this->model_govt_saf_collection_dtl = new model_govt_saf_collection_dtl($this->db);
        $this->model_govt_saf_demand_dtl = new model_govt_saf_demand_dtl($this->db);
        $this->model_govt_saf_level_pending_dtl = new model_govt_saf_level_pending_dtl($this->db);
        $this->model_water_level_pending_dtl = new model_water_level_pending_dtl($this->water);
        $this->model_g_saf = new model_g_saf($this->db);
        $this->WaterPenaltyModel = new WaterPenaltyModel($this->water);
        $this->model_penalty_dtl = new model_penalty_dtl($this->db);
        $this->model_advance_mstr = new model_advance_mstr($this->db);
        $this->model_adjustment_mstr = new model_adjustment_mstr($this->db);
        // $this->model_datatable = new model_datatable($this->db);
    }

    public function detail($module = null, $from_date = "", $to_date = "", $cheque_no_input = "", $payment_mode = "")
    {
        $data = (array)null;
        if ($this->request->getMethod() == 'get' || $from_date != "") {

            if ($from_date !== "") {
                $data['from_date'] = $from_date;
                $data['to_date'] = $to_date;
                $data['module'] = $module;
                $data['cheque_no_input'] = $cheque_no_input;
                $data['payment_mode'] = $payment_mode;
            } else {
                $data['cheque_no_input'] = $this->request->getVar('cheque_no_input');
                $data['payment_mode'] = $this->request->getVar('payment_mode');
                $data['from_date'] = $this->request->getVar('from_date');
                $data['to_date'] = $this->request->getVar('to_date');
                $data['module'] = $this->request->getVar('module');
                $data['filter_date'] = $this->request->getVar('filter_date');
                // print_var($data['filter_date']);
                // die;
                $data['status'] = $this->request->getVar('status');
            }
            //Cheque Details



            if ($data['module'] == "WATER") {
                //$data['chequeDetails'] = $this->Water_Cheque_Details_Model->getChequeDetailsByDate($data);
                $where = " where transaction_date between '" . $data['from_date'] . "' and '" . $data['to_date'] . "'";
                if (isset($data['filter_date']) && $data['filter_date'] == 'by_ch_clear_date') {
                    $where = " where  clear_bounce_date between '" . $data['from_date'] . "' and '" . $data['to_date'] . "'";
                }
                if (isset($data['status']) && $data['status'] == 'pending') {
                    $where .= " and tbl_transaction.status=2 ";
                } elseif (isset($data['status']) && $data['status'] == 'clear') {
                    $where .= " and tbl_transaction.status=1 ";
                } elseif (isset($data['status']) && $data['status'] == 'bounced') {
                    $where .= " and tbl_transaction.status=3 ";
                } else
                    $where .= " and tbl_transaction.status!=0 ";

                if ($data['payment_mode'] != '') {
                    $where .= " and tbl_transaction.tran_mode='" . $data['payment_mode'] . "'";
                }
                // SERACH BY CHEQUE NO.
                if ($data['cheque_no_input'] != '') {
                    $where .= " AND tbl_cheque_details.cheque_no='" . $data['cheque_no_input'] . "' and tbl_transaction.status!=0";
                }

                $sql = "select tbl_transaction.*,'Water' as tran_type,
                                tbl_cheque_details.id as cheque_dtl_id,cheque_no,cheque_date,bank_name,branch_name,
                                tbl_cheque_details.clear_bounce_date,tbl_cheque_details.remarks as clear_bounce_remarks,
                                view_emp_details.emp_name,view_ward_mstr.ward_no
                        from tbl_transaction 
                        join tbl_cheque_details on tbl_cheque_details.transaction_id=tbl_transaction.id
                        left join view_emp_details on view_emp_details.id = tbl_transaction.emp_details_id LEFT JOIN view_ward_mstr on tbl_transaction.ward_mstr_id=view_ward_mstr.id
                        $where ORDER BY transaction_date";
                // die;

                $this->model_datatable = new model_datatable($this->water);
                $result = $this->model_datatable->getDatatable($sql);
                $data['chequeDetails'] = isset($result['result']) ? $result['result'] : null;
                $data['pager'] = $result['count'];

                // $data['chequeDetails'] = $this->Water_Transaction_Model->getTransactionWithChequeDetails($where);

            } else if ($data['module'] == "PROPERTY") {
                $where = " where  tran_date between '" . $data['from_date'] . "' and '" . $data['to_date'] . "'";
                if (isset($data['filter_date']) && $data['filter_date'] == 'by_ch_clear_date') {
                    $where = " where  clear_bounce_date between '" . $data['from_date'] . "' and '" . $data['to_date'] . "'";
                }
                if (isset($data['status']) && $data['status'] == 'pending') {
                    $where .= " and tbl_transaction.status=2 ";
                } elseif (isset($data['status']) && $data['status'] == 'clear') {
                    $where .= " and tbl_transaction.status=1 ";
                } elseif (isset($data['status']) && $data['status'] == 'bounced') {
                    $where .= " and tbl_transaction.status=3 ";
                } else
                    $where .= " AND tbl_transaction.status!=0 ";

                $where .= " and tbl_transaction.tran_type='Property'";

                if ($data['payment_mode'] != '') {
                    $where .= " and tbl_transaction.tran_mode='" . $data['payment_mode'] . "'";
                }
                // SERACH BY CHEQUE NO.
                if ($data['cheque_no_input'] != '') {
                    $where .= " AND tbl_cheque_details.cheque_no='" . $data['cheque_no_input'] . "' and tbl_transaction.tran_type='Property' AND tbl_transaction.status!=0";
                }
                //echo $where;
                $sql = "select tbl_transaction.*,transaction_mode as payment_mode,
                            tbl_cheque_details.id as cheque_dtl_id,
                            tbl_cheque_details.clear_bounce_date as clear_bounce_date,
                            tbl_cheque_details.remarks as clear_bounce_remarks,tbl_cheque_details.cheque_no,
                            tbl_cheque_details.cheque_date,tbl_cheque_details.bank_name,tbl_cheque_details.branch_name,view_ward_mstr.ward_no,view_emp_details.emp_name
                        from tbl_transaction 
                        join tbl_cheque_details on tbl_cheque_details.transaction_id=tbl_transaction.id
                        left join view_emp_details on  tbl_transaction.tran_by_emp_details_id=view_emp_details.id
                        join tbl_tran_mode_mstr on tbl_tran_mode_mstr.id=tbl_transaction.tran_mode_mstr_id LEFT JOIN view_ward_mstr on tbl_transaction.ward_mstr_id=view_ward_mstr.id $where ORDER BY tran_date";

                $this->model_datatable = new model_datatable($this->db);
                $result = $this->model_datatable->getDatatable($sql);
                $data['chequeDetails'] = isset($result['result']) ? $result['result'] : null;
                $data['pager'] = $result['count'];
                // $data['chequeDetails'] = $this->model_transaction->getPropTransactionWithChequeDetails($where);

            } else if ($data['module'] == "SAF") {
                $where = " where  tran_date between '" . $data['from_date'] . "' and '" . $data['to_date'] . "'";
                if (isset($data['filter_date']) && $data['filter_date'] == 'by_ch_clear_date') {
                    $where = " where  clear_bounce_date between '" . $data['from_date'] . "' and '" . $data['to_date'] . "'";
                }
                if (isset($data['status']) && $data['status'] == 'pending') {
                    $where .= " and tbl_transaction.status=2 ";
                } elseif (isset($data['status']) && $data['status'] == 'clear') {
                    $where .= " and tbl_transaction.status=1 ";
                } elseif (isset($data['status']) && $data['status'] == 'bounced') {
                    $where .= " and tbl_transaction.status=3 ";
                } else
                    $where .= " AND tbl_transaction.status!=0 ";

                $where .= " and tbl_transaction.tran_type='Saf'";

                if ($data['payment_mode'] != '') {
                    $where .= " and tbl_transaction.tran_mode='" . $data['payment_mode'] . "'";
                }
                // SERACH BY CHEQUE NO.
                if ($data['cheque_no_input'] != '') {
                    $where .= " AND tbl_cheque_details.cheque_no='" . $data['cheque_no_input'] . "' and tbl_transaction.tran_type='Saf' AND tbl_transaction.status!=0 ";
                }

                $sql = "select tbl_transaction.*,transaction_mode as payment_mode,
                            tbl_cheque_details.id as cheque_dtl_id,
                            tbl_cheque_details.clear_bounce_date as clear_bounce_date,
                            tbl_cheque_details.remarks as clear_bounce_remarks,tbl_cheque_details.cheque_no,
                            tbl_cheque_details.cheque_date,tbl_cheque_details.bank_name,tbl_cheque_details.branch_name,view_ward_mstr.ward_no,view_emp_details.emp_name
                        from tbl_transaction 
                        join tbl_cheque_details on tbl_cheque_details.transaction_id=tbl_transaction.id
                        left join view_emp_details on  tbl_transaction.tran_by_emp_details_id=view_emp_details.id 
                        join tbl_tran_mode_mstr on tbl_tran_mode_mstr.id=tbl_transaction.tran_mode_mstr_id LEFT JOIN view_ward_mstr on tbl_transaction.ward_mstr_id=view_ward_mstr.id $where";

                $this->model_datatable = new model_datatable($this->db);
                $result = $this->model_datatable->getDatatable($sql);
                $data['chequeDetails'] = isset($result['result']) ? $result['result'] : null;
                $data['pager'] = $result['count'];
                // $data['chequeDetails'] = $this->model_transaction->getPropTransactionWithChequeDetails($where);


            } else if ($data['module'] == "TRADE") {
                $where = " where transaction_date between '" . $data['from_date'] . "' and '" . $data['to_date'] . "'";
                if (isset($data['filter_date']) && $data['filter_date'] == 'by_ch_clear_date') {
                    $where = " where  clear_bounce_date between '" . $data['from_date'] . "' and '" . $data['to_date'] . "'";
                }
                if (isset($data['status']) && $data['status'] == 'pending') {
                    $where .= " and tbl_transaction.status=2 ";
                } elseif (isset($data['status']) && $data['status'] == 'clear') {
                    $where .= " and tbl_transaction.status=1 ";
                } elseif (isset($data['status']) && $data['status'] == 'bounced') {
                    $where .= " and tbl_transaction.status=3 ";
                } else
                    $where .= " and  tbl_transaction.status!=0 ";
                // SERACH BY CHEQUE NO.
                if ($data['cheque_no_input'] != '') {
                    $where .= " AND tbl_cheque_dtl.cheque_no='" . $data['cheque_no_input'] . "' and  tbl_transaction.status!=0";
                }

                if ($data['payment_mode'] != '') {
                    $where .= " and tbl_transaction.tran_mode='" . $data['payment_mode'] . "'";
                }
                $sql = "select tbl_transaction.*,'Trade' as tran_type,
                            tbl_cheque_dtl.id as cheque_dtl_id,cheque_no,cheque_date,bank_name,branch_name,
                            tbl_cheque_dtl.clear_bounce_date,tbl_cheque_dtl.remarks as clear_bounce_remarks,
                            view_emp_details.emp_name,view_ward_mstr.ward_no
                        from tbl_transaction 
                        join tbl_cheque_dtl on tbl_cheque_dtl.transaction_id=tbl_transaction.id 
                        left join view_emp_details on view_emp_details.id = tbl_transaction.emp_details_id LEFT JOIN view_ward_mstr on tbl_transaction.ward_mstr_id=view_ward_mstr.id
                        $where";                
                $this->model_datatable = new model_datatable($this->trade);
                $result = $this->model_datatable->getDatatable($sql);
                $data['chequeDetails'] = isset($result['result']) ? $result['result'] : null;
                $data['pager'] = $result['count'];
                // $data['chequeDetails'] = $this->TradeTransactionModel->getTransactionWithChequeDetails($where);
            } else if ($data['module'] == 'GBSAF') {
                $where = " where  tran_date between '" . $data['from_date'] . "' and '" . $data['to_date'] . "'";
                if (isset($data['filter_date']) && $data['filter_date'] == 'by_ch_clear_date') {
                    $where = " where  clear_bounce_date between '" . $data['from_date'] . "' and '" . $data['to_date'] . "'";
                }
                if (isset($data['status']) && $data['status'] == 'pending') {
                    $where .= " and tbl_govt_saf_transaction.status=2 ";
                } elseif (isset($data['status']) && $data['status'] == 'clear') {
                    $where .= " and tbl_govt_saf_transaction.status=1 ";
                } elseif (isset($data['status']) && $data['status'] == 'bounced') {
                    $where .= " and tbl_govt_saf_transaction.status=3 ";
                } else
                    $where .= " and  tbl_govt_saf_transaction.status!=0 ";

                if ($data['payment_mode'] != '') {
                    $where .= " and tbl_transaction.tran_mode='" . $data['payment_mode'] . "'";
                }
                // SERACH BY CHEQUE NO.
                if ($data['cheque_no_input'] != '') {
                    $where .= " AND tbl_govt_saf_transaction_details.cheque_no='" . $data['cheque_no_input'] . "' and tbl_govt_saf_transaction.status!=0";
                }

                 $sql = "select tbl_govt_saf_transaction.*,'Government SAF' as tran_type,
                                tbl_govt_saf_transaction.tran_verification_status as verify_status,
                            transaction_mode as payment_mode,tbl_govt_saf_transaction_details.id as cheque_dtl_id,
                            tbl_govt_saf_transaction_details.cheque_no,
                            tbl_govt_saf_transaction_details.clear_bounce_date,tbl_govt_saf_transaction_details.remarks as clear_bounce_remarks,
                            tbl_govt_saf_transaction_details.cheque_date,tbl_govt_saf_transaction_details.bank_name,
                            tbl_govt_saf_transaction_details.branch_name,view_ward_mstr.ward_no,view_emp_details.emp_name 
                            from tbl_govt_saf_transaction 
                        join tbl_govt_saf_transaction_details on tbl_govt_saf_transaction_details.govt_saf_transaction_id=tbl_govt_saf_transaction.id 
                        left join view_emp_details on  tbl_govt_saf_transaction.tran_by_emp_details_id=view_emp_details.id 
                        join tbl_tran_mode_mstr on tbl_tran_mode_mstr.id=tbl_govt_saf_transaction.tran_mode_mstr_id LEFT JOIN view_ward_mstr on tbl_govt_saf_transaction.ward_mstr_id=view_ward_mstr.id $where";

                $this->model_datatable = new model_datatable($this->db);
                $result = $this->model_datatable->getDatatable($sql);
                $data['chequeDetails'] = isset($result['result']) ? $result['result'] : null;
                $data['pager'] = $result['count'];
                // $data['chequeDetails'] = $this->model_govt_saf_transaction->getTransactionWithChequeDetails($where);

            }
        }

        // print_var($data);
        // die;
        return view('water/water_connection/bank_reconciliation_water_list', $data);
    }





    public function cheque()
    {
        $session = session();
        $emp_details = $session->get('emp_details');
        $emp_details_id = $emp_details['id'];
        if ($this->request->getMethod() == 'post') {
            //print_var($this->request->getVar());exit;
            $data['from_date'] = $this->request->getVar('from_date');
            $data['to_date'] = $this->request->getVar('to_date');
            $data['module'] = $this->request->getVar('mod');
            $data['transaction_id'] = $this->request->getVar('transaction_id');
            $data['id'] = $this->request->getVar('id'); // cheque_detail_id
            $data['status'] = $this->request->getVar('status');
            $data['reason'] = $this->request->getVar('reason');
            $data['amount'] = $this->request->getVar('amount');

            if ($data['amount'] == "") {
                $data['amount'] = 0;
            }

            if ($data['module'] == 'PROPERTY') {
                $transaction_dtls = $this->model_transaction->getTransactionById(md5($data['transaction_id']));
                $bank_recon_array = array();
                $bank_recon_array['cancel_date'] = date('Y-m-d');
                $bank_recon_array['cheque_dtl_id'] = $data['id'];
                $bank_recon_array['transaction_id'] = $data['transaction_id'];
                $bank_recon_array['reason'] = $data['reason'];
                $bank_recon_array['amount'] = $data['amount'];
                $bank_recon_array['emp_details_id'] = $emp_details_id;
                $bank_recon_array['created_on'] = date('Y-m-d H:i:s');
                $bank_recon_array['status'] = $data['status'];
                $bank_recon_array['ward_mstr_id'] = $transaction_dtls['ward_mstr_id'];
                $bank_recon_array['related_id'] = $transaction_dtls['prop_dtl_id'];
                $bank_recon_array['prop_type'] = $transaction_dtls['tran_type'];

                $this->model_bank_recancilation->insertData($bank_recon_array);
                if ($data['status'] == 1) {
                    $this->model_transaction->updateStatusClear($data['transaction_id']);
                    if ($transaction_dtls['tran_type'] == 'Saf') {
                        $this->model_saf_dtl->clearPaymentStatus($transaction_dtls['prop_dtl_id']);
                        $saf_dtl = $this->model_saf_dtl->getSafDtlByMd5ID(md5($transaction_dtls['prop_dtl_id']));
                        if ($saf_dtl['payment_status'] == 1 and $saf_dtl['doc_upload_status'] == 1) {
                            $level_pending_arr = array();
                            $level_pending_arr['saf_dtl_id'] = $transaction_dtls['prop_dtl_id'];
                            $level_pending_arr['sender_user_type_id'] = 11;
                            $level_pending_arr['receiver_user_type_id'] = 6;
                            $level_pending_arr['created_on'] = date('Y-m-d H:i:s');

                            $this->model_level_pending_dtl->insertData($level_pending_arr);
                        }
                    }
                } else {
                    $penalty = (array)null;
                    $penalty = [
                        "prop_dtl_id" => $transaction_dtls["prop_dtl_id"],
                        "penalty_amt" => $data['amount'],
                        "penalty_type" => "Cheque Bounce",
                        "transaction_id" => $data["transaction_id"],
                        "module" => $transaction_dtls['tran_type'],
                    ];
                    $this->model_penalty_dtl->insertData($penalty);
                    $this->model_penalty_dtl->updateSAFPenaltyStatus(["prop_dtl_id" => $transaction_dtls["prop_dtl_id"], "transaction_id" => $data["transaction_id"], "status" => 1]);

                    $this->model_transaction->updateStatusNotClear($data['transaction_id']);
                    $this->model_advance_mstr->deactivateAdvance($data['transaction_id']);
                    $this->model_adjustment_mstr->deactivateAdjustment($data['transaction_id']);

                    if ($transaction_dtls['tran_type'] == 'Property') {
                        $prop_id = $transaction_dtls['prop_dtl_id'];
                        $demand_id = $this->model_collection->getAllDemandIdthroughTransactionId($transaction_dtls['id']);
                        $this->model_collection->updateStatus($transaction_dtls['id']);
                        $this->model_prop_demand->updateDemandNotPaid($demand_id);
                        $this->model_prop_demand->updateDemandAmount($demand_id);
                    } else {
                        $saf_id = $transaction_dtls['prop_dtl_id'];
                        $this->model_saf_dtl->updatePaymentStatus($transaction_dtls['prop_dtl_id']);
                        $demand_id = $this->model_saf_collection->getAllDemandIdthroughTransactionId($transaction_dtls['id']);
                        $this->model_saf_collection->updateStatus($transaction_dtls['id']);
                        $this->model_saf_demand->updateDemandNotPaid($demand_id);
                        $this->model_saf_demand->updateDemandAmount($demand_id);
                    }
                }
            } else if ($data['module'] == 'WATER') {

                $transaction_dtls = $this->Water_Transaction_Model->getTransactionById(md5($data['transaction_id']));
                if (strtoupper($transaction_dtls['transaction_type']) == 'NEW CONNECTION' or strtoupper($transaction_dtls['transaction_type'] == 'SITE INSPECTION')) {
                    $consumer_type = 'Applicant';
                } else {
                    $consumer_type = 'Consumer';
                }

                $bank_recon_array = array();
                $bank_recon_array['clearance_date'] = date('Y-m-d');
                $bank_recon_array['cheque_dtl_id'] = $data['id'];
                $bank_recon_array['transaction_id'] = $data['transaction_id'];
                $bank_recon_array['reason'] = $data['reason'];
                $bank_recon_array['amount'] = $data['amount'];
                $bank_recon_array['emp_details_id'] = $emp_details_id;
                $bank_recon_array['created_on'] = date('Y-m-d H:i:s');
                $bank_recon_array['status'] = $data['status'];
                $bank_recon_array['related_id'] = $transaction_dtls['related_id'];
                $bank_recon_array['consumer_type'] = $consumer_type;

                # tbl_bank_reconcilation
                $this->water_bank_reconcilation_model->insertData($bank_recon_array);
                if ($data['status'] == 1) {
                    # tbl_transaction set status=1
                    $this->Water_Transaction_Model->updateStatusClear($data['transaction_id']);

                    # tbl_apply_water_connection set payment_status=1
                    $this->WaterApplyNewConnectionModel->updateApplyNewConnectionPaymentStatusClear($transaction_dtls['related_id']);


                    $water_conn_dtl = $this->WaterApplyNewConnectionModel->getData(md5($transaction_dtls['related_id']));
                    if ($water_conn_dtl['doc_status'] == 1 and $water_conn_dtl['payment_status'] == 1) {
                        $level_pending_arr = array();
                        $level_pending_arr['apply_connection_id'] = $transaction_dtls['related_id'];
                        $level_pending_arr['sender_user_type_id'] = 11;
                        $level_pending_arr['emp_details_id'] = $emp_details_id;
                        $level_pending_arr['receiver_user_type_id'] = 12;
                        $level_pending_arr['created_on'] = date('Y-m-d H:i:s');

                        $this->model_water_level_pending_dtl->insertData($level_pending_arr);
                    }
                } else {
                    # insert cheque bounce charge
                    if ($data['amount'] > 0) {
                        $PenaltyData = array();
                        $PenaltyData['related_id'] = $transaction_dtls['related_id'];
                        $PenaltyData['penalty_amt'] = $data['amount'];
                        $PenaltyData['penalty_type'] = 1;  // 1-cheque bounce
                        $PenaltyData['created_on'] = date('Y-m-d H:i:s');
                        $PenaltyData['status'] = 1;
                        $PenaltyData['type'] = $consumer_type;
                        $PenaltyData['transaction_id'] = $data['transaction_id'];
                        $this->WaterPenaltyModel->insertData($PenaltyData);
                    }

                    # tbl_transaction set status=3
                    $this->Water_Transaction_Model->updateBounceStatus($data['transaction_id']);
                    if (strtoupper($transaction_dtls['transaction_type']) == 'NEW CONNECTION' or strtoupper($transaction_dtls['transaction_type']) == 'SITE INSPECTION') {
                        $apply_connection_id = $transaction_dtls['related_id'];
                        # tbl_apply_water_connection set payment_status=0
                        $this->WaterApplyNewConnectionModel->updateApplyNewConnectionPaymentStatus($transaction_dtls['related_id']);

                        # tbl_connection_charge set paid_status=0
                        $demand_id = $this->WaterConnectionChargeModel->updatePaidStatus($transaction_dtls['id']);

                        # tbl_penalty_installment set paid_status'=> 0, transaction_id'=> NULL, 'payment_from'=> NULL
                        $this->WaterPenaltyInstallmentModel->updatePenaltyInstallmentStatus($transaction_dtls['related_id']);
                    } else {
                        $consumer_id = $transaction_dtls['related_id'];
                        # string_agg(tbl_consumer_collection.id, ',')
                        $demand_id = $this->WaterConsumerCollectionModel->getAllDemandIdthroughTransactionId($transaction_dtls['id']);

                        # tbl_consumer_collection set status=0
                        $this->WaterConsumerCollectionModel->updateStatus($transaction_dtls['id']);

                        # tbl_consumer_demand set paid_status=0
                        $this->WaterConsumerDemandModel->updateDemandNotPaid($demand_id);
                    }
                }
            } else if ($data['module'] == 'TRADE') {

                $transaction_dtls = $this->TradeTransactionModel->getTransactionById(md5($data['transaction_id']));


                $bank_recon_array = array();
                $bank_recon_array['related_id'] = $transaction_dtls['related_id'];
                $bank_recon_array['cheque_dtl_id'] = $data['id'];
                $bank_recon_array['transaction_id'] = $data['transaction_id'];
                $bank_recon_array['reason'] = $data['reason'];
                $bank_recon_array['amount'] = $data['amount'];
                $bank_recon_array['emp_details_id'] = $emp_details_id;
                $bank_recon_array['created_on'] = date('Y-m-d H:i:s');
                $bank_recon_array['status'] = $data['status'];
                $bank_recon_array['type'] = $transaction_dtls['transaction_type'];
                $bank_recon_array['clearance_date'] = date('Y-m-d');

                # insert tbl_bank_recancilation
                $this->TradeBankRecancilationModel->insertData($bank_recon_array);

                if ($data['status'] == 1) {
                    # tbl_transaction set status=1
                    $this->TradeTransactionModel->updateStatusClear($data['transaction_id']);

                    # tbl_cheque_dtl set status=1
                    $this->TradeTransactionModel->updateChequeClearStatus($data['transaction_id']);

                    # tbl_apply_licence set payment_status=1
                    $this->model_apply_licence->updatePaymentDone($transaction_dtls['related_id']);


                    $license_dtl = $this->model_apply_licence->applyLicenseDetails(md5($transaction_dtls['related_id']));
                    if ($license_dtl['payment_status'] == 1 and $license_dtl['document_upload_status'] == 1) {
                        $level_pending_arr = array();
                        $level_pending_arr['apply_licence_id'] = $transaction_dtls['related_id'];
                        $level_pending_arr['sender_user_type_id'] = 11;
                        $level_pending_arr['sender_emp_details_id'] = $emp_details_id;
                        $level_pending_arr['receiver_user_type_id'] = 17;
                        $level_pending_arr['created_on'] = date('Y-m-d H:i:s');

                        $this->model_trade_level_pending_dtl->insrtlevelpendingdtl($level_pending_arr);
                    }
                } else {
                    # tbl_transaction set status=3
                    $this->TradeTransactionModel->updateBounceStatus($data['transaction_id']);

                    # tbl_cheque_dtl set status=3
                    $this->TradeTransactionModel->updateChequeBounceStatus($data['transaction_id']);

                    # tbl_apply_licence set payment_status=0
                    $this->model_apply_licence->updatePaidStatus($transaction_dtls['related_id']);
                }
            } else if ($data['module'] == 'GBSAF') {

                $transaction_dtls = $this->model_govt_saf_transaction->getTransactionById(md5($data['transaction_id']));


                $bank_recon_array = array();
                $bank_recon_array['govt_saf_id'] = $transaction_dtls['govt_saf_dtl_id'];
                $bank_recon_array['cheque_dtl_id'] = $data['id'];
                $bank_recon_array['transaction_id'] = $data['transaction_id'];
                $bank_recon_array['reason'] = $data['reason'];
                $bank_recon_array['amount'] = $data['amount'];
                $bank_recon_array['emp_details_id'] = $emp_details_id;
                $bank_recon_array['created_on'] = date('Y-m-d H:i:s');
                $bank_recon_array['status'] = $data['status'];
                $bank_recon_array['clearance_date'] = date('Y-m-d');


                $this->model_govt_bank_recancilation->insertData($bank_recon_array);

                if ($data['status'] == 1) {
                    $this->model_govt_saf_transaction->updateStatusClear($data['transaction_id']);
                    $this->model_g_saf->updateTransactionStatus($transaction_dtls['govt_saf_dtl_id'], 1);
                    $gbsaf_dtl = $this->model_g_saf->getApplicationDetailbyId(md5($transaction_dtls['govt_saf_dtl_id']));
                    if ($gbsaf_dtl['app_status'] == 2 and $gbsaf_dtl['is_transaction_done'] == 1) {
                        $gov_level_pending_arr = array();
                        $gov_level_pending_arr['govt_saf_dtl_id'] = $transaction_dtls['govt_saf_dtl_id'];
                        $gov_level_pending_arr['sender_user_type_id'] = 11;
                        $gov_level_pending_arr['sender_emp_details_id'] = $emp_details_id;
                        $gov_level_pending_arr['receiver_user_type_id'] = 9;
                        $gov_level_pending_arr['created_on'] = date('Y-m-d H:i:s');

                        $this->model_govt_saf_level_pending_dtl->insertData($gov_level_pending_arr);
                    }
                } else {
                    $this->model_govt_saf_transaction->updateBounceStatus($data['transaction_id']);
                    $demand_id = $this->model_govt_saf_collection_dtl->getAllDemandIdthroughTransactionId($transaction_dtls['id']);
                    $this->model_govt_saf_collection_dtl->updateStatus($transaction_dtls['id']);
                    $this->model_govt_saf_demand_dtl->updateDemandNotPaid($demand_id);
                    $this->model_g_saf->updateTransactionStatus($transaction_dtls['govt_saf_dtl_id'], 0);
                }
            }

            return $this->response->redirect(base_url('BankReconciliationAllModuleList/detail/' . ($data['module']) . '/' . $data['from_date'] . '/' . $data['to_date']));
        }
    }

    public function cheque_verification()
    {
        $session = session();
        $emp_details = $session->get('emp_details');
        $emp_details_id = $emp_details['id'];
        if ($this->request->getMethod() == 'post') {
            $data['from_date'] = $this->request->getVar('from_date');
            $data['cheque_no_input'] = $this->request->getVar('cheque_no');
            $data['payment_mode'] = $this->request->getVar('payment_mode_input');
            $data['to_date'] = $this->request->getVar('to_date');
            $data['module'] = $this->request->getVar('mod');
            $data['transaction_id'] = $this->request->getVar('transaction_id');
            $data['id'] = $this->request->getVar('id'); // cheque_detail_id

            $data['reason'] = $this->request->getVar('reason'); //rr
            if ($data['reason'] == 'other') {
                $data['reason'] = $this->request->getVar('remarks_input_value');
            }
            $data['amount'] = $this->request->getVar('amount'); //am

            $data['clear_bounce_date'] = $this->request->getVar('clear_bounce_date');
            $data['bn_status'] = $this->request->getVar('bn_status'); //

            if ($data['bn_status'] == '3') {
                $data['bounce_status'] = 1;
            } else {
                $data['amount'] = 0;
                $data['bounce_status'] = 0;
            }

            $input = [
                "id" => $data['id'],
                "clear_bounce_date" =>  $data['clear_bounce_date'], //cl
                "remarks" =>  $data['reason'],
                "bounce_amount" =>  $data['amount'], //cl
                "status" =>  $data['bn_status'], //cl
                "bounce_status" =>   $data['bounce_status'] //cl
            ];

            if ($data['module'] == "WATER") {
                $this->water->transBegin();
                $input['transaction_id'] = $data['transaction_id'];
                $this->Water_Cheque_Details_Model->verifyChequeDtl($input);
                $transaction_dtls =  $this->Water_Transaction_Model->getTranById($data['transaction_id']);

                $wmodule = (!empty($transaction_dtls) && trim(strtoupper($transaction_dtls['transaction_type'])) == strtoupper('Demand Collection'))?'consumer':'connection';
                $where=['status'=>1,
                        'transaction_id'=>$input['transaction_id'],
                        'related_id'=>$transaction_dtls['related_id'],
                        'module'=>$wmodule
                    ];
                $update = ['status'=>$data['bn_status'],
                            'remarks'=> $input['remarks']
                        ];
                //update adjustment  and update advance               
                $this->WaterPenaltyModel->update_tbl_adjustment_mstr($where,$update);
                $this->WaterPenaltyModel->update_tbl_advance_mstr($where,$update);
                
                if ($data['bn_status'] == 1) {
                    $this->Water_Transaction_Model->updateStatusClear($data['transaction_id']);
                } elseif ($data['bn_status'] == 3) {
                    $this->Water_Transaction_Model->updateBounceStatus($data['transaction_id']);
                }
                if ($data['bn_status'] == '3') {
                    // $transaction_dtls =  $this->Water_Transaction_Model->getTranById($data['transaction_id']);

                    if (!empty($transaction_dtls)) {
                        $penalty_input = [
                            "related_id" => $transaction_dtls['related_id'], //cl
                            "penalty_amt" => $data['amount'], //cl
                            "penalty_type" => '1', //cl
                            "status" => 1, //cl
                            "transaction_id" => $data['transaction_id'],
                            "created_on" => date('Y-m-d'),
                            "type" => trim(strtoupper($transaction_dtls['transaction_type'])) == strtoupper('Demand Collection') ? 'Consumer' : 'Applicant', //cl
                        ];
                        $chek = $this->WaterPenaltyModel->getPenlatyDtlByTrId($data['transaction_id']);
                        //print_var($transaction_dtls);die;
                        if (empty($chek))
                            $penalty_id = $this->WaterPenaltyModel->insertData($penalty_input);
                        else
                            $this->WaterPenaltyModel->updatePenlatyAmtByTrId($penalty_input);

                        if (trim(strtoupper($transaction_dtls['transaction_type'])) == strtoupper('Demand Collection')) {

                            $demands_ids_array = $this->WaterConsumerCollectionModel->getConsumerCollectionByTransactionId($transaction_dtls['id']);
                            $demands_ids_string = array_map(function ($val) {
                                return $val['demand_id'];
                            }, $demands_ids_array);
                            $demands_ids_string = implode(',', $demands_ids_string);
                            if (!empty($demands_ids_array))
                                $this->WaterConsumerDemandModel->updateDemandNotPaid($demands_ids_string);
                        }

                        if (in_array(trim(strtoupper($transaction_dtls['transaction_type'])), [strtoupper('New Connection'), strtoupper('Site Inspection'), strtoupper('Penlaty Instalment')])) {
                            if (date('Y-m-d', strtotime($transaction_dtls['transaction_date'] . '+3 months')) == date('Y-m-d')) {
                                $this->penalty_installment_model->updateInstallmentDtlbyAppConnIdAdTrId($transaction_dtls['related_id'], $transaction_dtls['id']);
                            } else {
                                $this->penalty_installment_model->updateChBounceTrIdAndAppId($transaction_dtls['related_id'], $transaction_dtls['id']);
                            }

                            $this->conn_fee->updatePadeToUnpadet($transaction_dtls['related_id'], $transaction_dtls['transaction_type']);
                            $this->conn_fee->updateoUnpadetstatusApp($transaction_dtls['related_id']);
                            //print_var($transaction_dtls);die;
                        }
                    }

                    //    echo "cheque verified successfully";
                    flashToast('message', 'Cheque Bounced');
                } elseif ($data['bn_status'] == 1) {
                    $transaction_dtls =  $this->Water_Transaction_Model->getTranById($data['transaction_id']);
                    if (!empty($transaction_dtls)) {
                        $penalty_input = [
                            "related_id" => $transaction_dtls['related_id'], //cl
                            "penalty_amt" => $data['amount'], //cl
                            "penalty_type" => '1', //cl
                            "status" => 0, //cl
                            "transaction_id" => $data['transaction_id'],
                            "created_on" => date('Y-m-d'),
                            "type" => trim(strtoupper($transaction_dtls['transaction_type'])) == strtoupper('Demand Collection') ? 'Consumer' : 'Applicant', //cl
                        ];

                        $this->WaterPenaltyModel->updatePenlatyAmtByTrId($penalty_input);

                        if (trim(strtoupper($transaction_dtls['transaction_type'])) == strtoupper('Demand Collection')) {

                            $demands_ids_array = $this->WaterConsumerCollectionModel->getConsumerCollectionByTransactionId($transaction_dtls['id']);
                            $demands_ids_string = array_map(function ($val) {
                                return $val['demand_id'];
                            }, $demands_ids_array);
                            $demands_ids_string = implode(',', $demands_ids_string);
                            if (!empty($demands_ids_array))
                                $this->WaterConsumerDemandModel->updateDemandPaid($demands_ids_string);
                        }

                        if (in_array(trim(strtoupper($transaction_dtls['transaction_type'])), [strtoupper('New Connection'), strtoupper('Site Inspection'), strtoupper('Penlaty Instalment')])) {
                            $this->penalty_installment_model->updateChClearTrIdAndAppId($transaction_dtls['related_id'], $transaction_dtls['id']);

                            $this->conn_fee->updateUnpadeToPadet($transaction_dtls['related_id'], $transaction_dtls['transaction_type']);
                            $this->conn_fee->updateoPadetstatusApp($transaction_dtls['related_id']);
                        }
                    }

                    flashToast('message', 'Cheque Verified');
                }

                if ($this->water->transStatus() === FALSE) {
                    $this->water->transRollback();
                    flashToast('message', "Transaction failed");
                    // return $this->response->redirect(base_url('BankReconciliationAllModuleList/detail/' . $data['module'] . '/' . $data['from_date'] . '/' . $data['to_date'] . '/' . $data['cheque_no_input'] . '/' . $data['payment_mode']));
                } else {
                    $this->water->transCommit();
                }
            }
            if ($data['module'] == "TRADE") {
                $this->trade->transBegin();
                $input['transaction_id'] = $data['transaction_id'];
                $this->TradeChequeDtlModel->verifyChequeDtl($input);
                if ($data['bn_status'] == 1) {
                    $this->TradeTransactionModel->updateStatusClear($data['transaction_id']);
                } elseif ($data['bn_status'] == 3) {
                    $this->TradeTransactionModel->updateBounceStatus($data['transaction_id']);
                }
                if ($data['bn_status'] == '3') {
                    $transaction_dtls =  $this->TradeTransactionModel->getTranById($data['transaction_id']);

                    if (!empty($transaction_dtls)) {
                        $tbl_bank_recancilation = [
                            "transaction_id" => $transaction_dtls['id'], //cl
                            "related_id" => $transaction_dtls['related_id'], //cl
                            "cheque_dtl_id" => $data['id'], //cl
                            "reason" =>  $data['reason'], //cl
                            "amount_receive_date" => $transaction_dtls['transaction_date'], //cl
                            "emp_details_id" => $this->login_emp_details_id, //cl
                            "type" => 1, //cl
                            "created_on" => date('Y-m-d h:i:s'), //cl
                            "amount" => $data['amount'], //cl
                            "status" => 3, //cl

                        ];
                        $chek = $this->TradeTransactionModel->getPenlatyDtlByTrId($data['transaction_id']);

                        if (empty($chek))
                            $penalty_id = $this->TradeTransactionModel->insertChBouncPenlaty($tbl_bank_recancilation);
                        else
                            $this->TradeTransactionModel->updateChBouncPenlatyAmtByTrId($tbl_bank_recancilation);

                        $this->TradeApplyLicenceModel->updateApplyLicencePaymentStatusNotClear($transaction_dtls['related_id']);
                    }

                    //    echo "cheque verified successfully";
                    flashToast('message', 'Cheque Bounced');
                } elseif ($data['bn_status'] == 1) {
                    $transaction_dtls =  $this->TradeTransactionModel->getTranById($data['transaction_id']);

                    if (!empty($transaction_dtls)) {
                        $tbl_bank_recancilation = [
                            "transaction_id" => $transaction_dtls['id'], //cl
                            "related_id" => $transaction_dtls['related_id'], //cl
                            "cheque_dtl_id" => $data['id'], //cl
                            "reason" =>  $data['reason'], //cl
                            "amount_receive_date" => $transaction_dtls['transaction_date'], //cl
                            "emp_details_id" => $this->login_emp_details_id, //cl
                            "type" => 1, //cl
                            "created_on" => date('Y-m-d h:i:s'), //cl
                            "amount" => $data['amount'], //cl
                            "status" => 3, //cl

                        ];
                        $chek = $this->TradeTransactionModel->getPenlatyDtlByTrId($data['transaction_id']);

                        if (empty($chek))
                            $this->TradeTransactionModel->updateChBouncPenlatyStatusByTrId($tbl_bank_recancilation);

                        $this->TradeApplyLicenceModel->updateApplyLicencePaymentStatusClear($transaction_dtls['related_id']);
                    }

                    //    echo "cheque verified successfully";
                    flashToast('message', 'Cheque Verified');
                }

                if ($this->trade->transStatus() === FALSE) {
                    $this->trade->transRollback();
                    flashToast('message', "Transaction failed");
                    // return $this->response->redirect(base_url('BankReconciliationAllModuleList/detail/' . $data['module'] . '/' . $data['from_date'] . '/' . $data['to_date']));
                } else {
                    $this->trade->transCommit();
                }
            }
            // echo "module ";
            // print_var($data['module']);
            // die;
            if ($data['module'] == "PROPERTY" || $data['module'] == "SAF") {
              
                $this->model_cheque_details->verifyChequeDtl($input);
                $this->model_cheque_details->updateTransactionStatus($data['transaction_id'], $data['bn_status']);

                // echo "before trans";
                $transaction_dtls = $this->model_cheque_details->getPropDtlByTransaction($data['transaction_id']);
                if ($transaction_dtls['tran_type'] == "Saf") {
                    $this->model_saf_dtl->updateSafPaymentStatus($transaction_dtls['prop_dtl_id']);
                }

                // print_r($transaction_dtls);
                // die;
                if ($data['bn_status'] == '3') {
                    $penalty_input = [
                        "prop_dtl_id" => $transaction_dtls['prop_dtl_id'], //cl
                        "penalty_amt" => $data['amount'], //cl
                        "penalty_type" => 'Cheque Bounced', //cl
                        "status" => 1, //cl
                        "transaction_id" => $data['transaction_id'],
                        "module" => $data['module'] //cl
                    ];

                    if ($data['module'] == "PROPERTY") {
                        $sql = "UPDATE tbl_prop_demand 
                                SET balance=amount, paid_status=0
                                WHERE paid_status=1 AND status=1 AND id IN (SELECT prop_demand_id FROM tbl_collection WHERE transaction_id=".$data['transaction_id'].")";
                        $this->db->query($sql);
                        $sql = "UPDATE tbl_collection SET deactive_status=1, status=0 WHERE deactive_status=0 AND status=1 AND transaction_id=".$data['transaction_id']."";
                        $this->db->query($sql);
                        $sql = "UPDATE tbl_adjustment_mstr SET status=0 WHERE transaction_id=".$data['transaction_id']." AND status=1 AND module='Property'";
                        $this->db->query($sql);
                    } else if ($data['module'] == "SAF") {
                        $sql = "UPDATE tbl_saf_demand 
                                SET balance=COALESCE(amount, 0)-COALESCE(adjust_amt, 0), paid_status=0
                                WHERE paid_status=1 AND status=1 AND id IN (SELECT saf_demand_id FROM tbl_saf_collection WHERE transaction_id=".$data['transaction_id'].")";
                        $this->db->query($sql);
                        $sql = "UPDATE tbl_saf_collection SET status=0 WHERE status=1 AND transaction_id=".$data['transaction_id']."";
                        $this->db->query($sql);
                        $sql = "UPDATE tbl_adjustment_mstr SET status=0 WHERE transaction_id=".$data['transaction_id']." AND status=1 AND module='Saf'";
                        $this->db->query($sql);
                        $sql = "UPDATE tbl_saf_dtl SET payment_status=0 WHERE payment_status=1 AND id=".$transaction_dtls['prop_dtl_id'];
                        $this->db->query($sql);
                    }
                    
                    $data = $this->model_cheque_details->insertPenalty($penalty_input);
                    //    echo "cheque verified successfully";
                    flashToast('message', 'Cheque Bounced');
                }
                // echo "cheque verified with clear";
                flashToast('message', 'Cheque Verified');
            }

            if ($data['module'] == "GBSAF") {
                // echo "inside gbsaf";
                // die;
                $this->model_cheque_details->verifyGBChequeDtl($input);
                $this->model_cheque_details->updateGBTransactionStatus($data['transaction_id'], $data['bn_status']);
                // echo "before trans";
                $transaction_dtls = $this->model_govt_saf_transaction->getTransactionById(md5($data['transaction_id']));
                // print_r($transaction_dtls);
                // die;
                if ($data['bn_status'] == '3') {
                    $penalty_input = [
                        "govt_saf_dtl_id" => $transaction_dtls['govt_saf_dtl_id'], //cl
                        "govt_saf_transaction" => $data['transaction_id'], //cl
                        "head_name" => 'Cheque Bounced', //cl
                        "amount" => $data['amount'], //cl
                        "value_add_minus" => 'Add',
                        "status" => 1
                    ];

                    $data = $this->model_cheque_details->insertGBPenalty($penalty_input);
                    //    echo "cheque verified successfully";
                    flashToast('message', 'Cheque Bounced');
                }
                // echo "cheque verified with clear";
                flashToast('message', 'Cheque Verified');
            }

            return $this->response->redirect(base_url('BankReconciliationAllModuleList/detail/' . ($data['module']) . '/' . $data['from_date'] . '/' . $data['to_date'] .'/'.$data['cheque_no_input'].'/'.$data['payment_mode']));
        }
    }

    public function ajaxBankReconcilliationDataDownload()
    {

        if ($this->request->getMethod() == "get") {
            $inputs = $this->request->getVar();
            $data['cheque_no_input'] = $inputs['cheque_no_input'];
            $data['payment_mode'] = $inputs['payment_mode'];
            $data['from_date'] = $inputs['from_date'];
            $data['to_date'] = $inputs['to_date'];
            $data['module'] = $inputs['module'];
            $data['filter_date'] = $inputs['filter_date'];
            $data['status'] = $inputs['status'];
        }
        try {
            if ($data['module'] == "WATER") {
                $where = " where transaction_date between '" . $data['from_date'] . "' and '" . $data['to_date'] . "'";
                if (isset($data['filter_date']) && $data['filter_date'] == 'by_ch_clear_date') {
                    $where = " where  clear_bounce_date between '" . $data['from_date'] . "' and '" . $data['to_date'] . "'";
                }
                if (isset($data['status']) && $data['status'] == 'pending') {
                    $where .= " and tbl_transaction.status=2 ";
                } elseif (isset($data['status']) && $data['status'] == 'clear') {
                    $where .= " and tbl_transaction.status=1 ";
                } elseif (isset($data['status']) && $data['status'] == 'bounced') {
                    $where .= " and tbl_transaction.status=3 ";
                } else
                    $where .= " and tbl_transaction.status!=0 ";

                if ($data['payment_mode'] != '') {
                    $where .= " and tbl_transaction.tran_mode='" . $data['payment_mode'] . "'";
                }
                // SERACH BY CHEQUE NO.
                if ($data['cheque_no_input'] != '') {
                    $where = "where tbl_cheque_details.cheque_no='" . $data['cheque_no_input'] . "'";
                }

               $sql = "select tbl_transaction.*,'Water' as tran_type,
                                tbl_cheque_details.id as cheque_dtl_id,cheque_no,cheque_date,bank_name,branch_name,
                                tbl_cheque_details.clear_bounce_date,tbl_cheque_details.remarks as clear_bounce_remarks,
                                view_emp_details.emp_name,view_ward_mstr.ward_no
                        from tbl_transaction 
                        join tbl_cheque_details on tbl_cheque_details.transaction_id=tbl_transaction.id
                        left join view_emp_details on view_emp_details.id = tbl_transaction.emp_details_id LEFT JOIN view_ward_mstr on tbl_transaction.ward_mstr_id=view_ward_mstr.id
                        $where";
                //$this->model_datatable = new model_datatable($this->water);
                $run = $this->water->query($sql);
                if ($result = $run->getResultArray()) {
                    $records = [];
                    foreach ($result as $key => $value) {
                        $records[] = [
                            'tran_no' => ($value['tran_no'] ?? $value['transaction_no']),
                            'transaction_date' => ($value['transaction_date'] ?? $value['transaction_date']),
                            'payment_mode' => ($value['payment_mode'] != "") ? $value['payment_mode'] : "",
                            'tran_type' => $value['tran_type'] ?? $value['tran_type'],
                            'cheque_date' => $value['cheque_date'] != "" ? date('d-m-Y', strtotime($value['cheque_date'])) : "",
                            'cheque_no' => ($value['cheque_no'] != "") ? $value['cheque_no'] : "",
                            'bank_name' => $value['bank_name'] != "" ? $value['bank_name'] : "",
                            'branch_name' => $value['branch_name'] != "" ? $value['branch_name'] : "",
                            'amount' => isset($data['module']) && in_array($data['module'], ["WATER", "TRADE"]) ? $value['paid_amount']: $value['payable_amt'],
                            'clear_bounce_date' => isset($value['clear_bounce_date']) ? $value['clear_bounce_date'] : "",
                            'remarks' => isset($value['clear_bounce_remarks']) ? $value['clear_bounce_remarks'] : "",
                            'tc_name' => isset($value['emp_name']) ? $value['emp_name'] : "",
                        ];
                    }
                    $spreadsheet = new Spreadsheet();
                    $activeSheet = $spreadsheet->getActiveSheet();
                    $activeSheet->setCellValue('A1', 'Transaction No.');
                    $activeSheet->setCellValue('B1', 'Transaction Date');
                    $activeSheet->setCellValue('C1', 'Payment Mode');
                    $activeSheet->setCellValue('D1', 'Transaction Type');
                    $activeSheet->setCellValue('E1', 'Cheque Date');
                    $activeSheet->setCellValue('F1', 'Cheque No.');
                    $activeSheet->setCellValue('G1', 'Bank Name');
                    $activeSheet->setCellValue('H1', 'Branch Name');
                    $activeSheet->setCellValue('I1', 'Amount');
                    $activeSheet->setCellValue('J1', 'Clear/Bounce Date');
                    $activeSheet->setCellValue('K1', 'Remarks');
                    $activeSheet->setCellValue('L1', 'Tc Name');                             
                    $activeSheet->fromArray($records, NUll, 'A2');
                    $filename = "Bank_Reconcilliation_Report_" . date('Ymd-hisa') . ".xlsx";
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="' . $filename . '"');
                    header('Cache-Control: max-age=0');
                    $writer = new Xlsx($spreadsheet);
                    //$writer->save(APPPATH.'/hello world.xlsx');
                    $writer->save('php://output');
                }
            } else if ($data['module'] == "PROPERTY") {
                $where = " where  tran_date between '" . $data['from_date'] . "' and '" . $data['to_date'] . "'";
                if (isset($data['filter_date']) && $data['filter_date'] == 'by_ch_clear_date') {
                    $where = " where  clear_bounce_date between '" . $data['from_date'] . "' and '" . $data['to_date'] . "'";
                }
                if (isset($data['status']) && $data['status'] == 'pending') {
                    $where .= " and tbl_transaction.status=2 ";
                } elseif (isset($data['status']) && $data['status'] == 'clear') {
                    $where .= " and tbl_transaction.status=1 ";
                } elseif (isset($data['status']) && $data['status'] == 'bounced') {
                    $where .= " and tbl_transaction.status=3 ";
                } else
                    $where .= " AND tbl_transaction.status!=0 ";

                //$where .= " and tbl_transaction.tran_type='Property'";

                if ($data['payment_mode'] != '') {
                    $where .= " and tbl_transaction.tran_mode='" . $data['payment_mode'] . "'";
                }
                // SERACH BY CHEQUE NO.
                if ($data['cheque_no_input'] != '') {
                    $where = "where tbl_cheque_details.cheque_no='" . $data['cheque_no_input'] . "' and tbl_transaction.tran_type='Property'";
                }
                $sql = "select 
                            view_ward_mstr.ward_no,
                            tbl_prop_dtl.new_holding_no,
                            tbl_transaction.tran_no,
                            tbl_transaction.tran_date,
                            tbl_transaction.tran_mode,
                            tbl_transaction.tran_type,
                            tbl_cheque_details.cheque_date,
                            tbl_cheque_details.cheque_no,
                            tbl_cheque_details.bank_name,
                            tbl_cheque_details.branch_name,
                            tbl_transaction.payable_amt,
                            tbl_cheque_details.clear_bounce_date,
                            tbl_cheque_details.remarks,
                            view_emp_details.emp_name
                        from tbl_transaction 
                        INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_transaction.prop_dtl_id AND tbl_transaction.tran_type='Property'
                        INNER JOIN tbl_cheque_details on tbl_cheque_details.transaction_id=tbl_transaction.id
                        left join view_emp_details on  tbl_transaction.tran_by_emp_details_id=view_emp_details.id
                        LEFT JOIN view_ward_mstr on tbl_prop_dtl.ward_mstr_id=view_ward_mstr.id  
                        $where";
                /*print_var($sql);
                die();*/
                $run = $this->db->query($sql);
                if ($result = $run->getResultArray()) {
                    $records = [];
                    foreach ($result as $key => $value) {
                        $records[] = [
                            'ward_no' => ($value['ward_no']!="")?$value['ward_no']:"",
                            'new_holding_no' => ($value['new_holding_no']!="")?$value['new_holding_no']:"",
                            'tran_no' => ($value['tran_no']!="")?$value['tran_no']:"",
                            'tran_date' => ($value['tran_date']!="")?$value['tran_date']:"",
                            'tran_mode' => ($value['tran_mode']!="")?$value['tran_mode']:"",
                            'tran_type' => ($value['tran_type']!="")?$value['tran_type']:"",
                            'cheque_date' => ($value['cheque_date']!="")?$value['cheque_date']:"",
                            'cheque_no' => ($value['cheque_no']!="")?$value['cheque_no']:"",
                            'bank_name' => ($value['bank_name']!="")?$value['bank_name']:"",
                            'branch_name' => ($value['branch_name']!="")?$value['branch_name']:"",
                            'payable_amt' => ($value['payable_amt']!="")?$value['payable_amt']:"",
                            'clear_bounce_date' => ($value['clear_bounce_date']!="")?$value['clear_bounce_date']:"",
                            'remarks' => ($value['remarks']!="")?$value['remarks']:"",
                            'emp_name' => ($value['emp_name']!="")?$value['emp_name']:"",
                        ];
                    }
                    $spreadsheet = new Spreadsheet();
                    //phpOfficeLoad();
                    //$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                    $activeSheet = $spreadsheet->getActiveSheet();
                    $activeSheet->setCellValue('A1', 'Ward No.');
                    $activeSheet->setCellValue('B1', 'Holding No');
                    $activeSheet->setCellValue('C1', 'Tran No');
                    $activeSheet->setCellValue('D1', 'Tran Date');
                    $activeSheet->setCellValue('E1', 'Tran Mode');
                    $activeSheet->setCellValue('F1', 'Tran Type');
                    $activeSheet->setCellValue('G1', 'Cheque Date');
                    $activeSheet->setCellValue('H1', 'Cheque No.');
                    $activeSheet->setCellValue('I1', 'Bank Name');
                    $activeSheet->setCellValue('J1', 'Branch Name');
                    $activeSheet->setCellValue('K1', 'Tran Amount');
                    $activeSheet->setCellValue('L1', 'Clear/Bounce Date');
                    $activeSheet->setCellValue('M1', 'Remarks');
                    $activeSheet->setCellValue('N1', 'Tc Name');                          
                    $activeSheet->fromArray($records, NUll, 'A2');
                    $filename = "Property_Bank_Reconcilliation_Report_" . date('Ymd-hisa') . ".xlsx";
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="' . $filename . '"');
                    header('Cache-Control: max-age=0');
                    $writer = new Xlsx($spreadsheet);
                    //$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                    $writer->save('php://output');
                }
            } else if ($data['module'] == "SAF") {
                $where = " where  tran_date between '" . $data['from_date'] . "' and '" . $data['to_date'] . "'";
                if (isset($data['filter_date']) && $data['filter_date'] == 'by_ch_clear_date') {
                    $where = " where  clear_bounce_date between '" . $data['from_date'] . "' and '" . $data['to_date'] . "'";
                }
                if (isset($data['status']) && $data['status'] == 'pending') {
                    $where .= " and tbl_transaction.status=2 ";
                } elseif (isset($data['status']) && $data['status'] == 'clear') {
                    $where .= " and tbl_transaction.status=1 ";
                } elseif (isset($data['status']) && $data['status'] == 'bounced') {
                    $where .= " and tbl_transaction.status=3 ";
                } else
                    $where .= " AND tbl_transaction.status!=0 ";

                //$where .= " and tbl_transaction.tran_type='Property'";

                if ($data['payment_mode'] != '') {
                    $where .= " and tbl_transaction.tran_mode='" . $data['payment_mode'] . "'";
                }
                // SERACH BY CHEQUE NO.
                if ($data['cheque_no_input'] != '') {
                    $where = "where tbl_cheque_details.cheque_no='" . $data['cheque_no_input'] . "' and tbl_transaction.tran_type='Property'";
                }

                $sql = "select 
                            view_ward_mstr.ward_no,
                            tbl_saf_dtl.saf_no,
                            tbl_transaction.tran_no,
                            tbl_transaction.tran_date,
                            tbl_transaction.tran_mode,
                            tbl_transaction.tran_type,
                            tbl_cheque_details.cheque_date,
                            tbl_cheque_details.cheque_no,
                            tbl_cheque_details.bank_name,
                            tbl_cheque_details.branch_name,
                            tbl_transaction.payable_amt,
                            tbl_cheque_details.clear_bounce_date,
                            tbl_cheque_details.remarks,
                            view_emp_details.emp_name
                        from tbl_transaction 
                        INNER JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_transaction.prop_dtl_id AND tbl_transaction.tran_type='Saf'
                        INNER JOIN tbl_cheque_details on tbl_cheque_details.transaction_id=tbl_transaction.id
                        left join view_emp_details on  tbl_transaction.tran_by_emp_details_id=view_emp_details.id
                        LEFT JOIN view_ward_mstr on tbl_saf_dtl.ward_mstr_id=view_ward_mstr.id  
                        $where";
                //print_var($sql);
                $run = $this->db->query($sql);
                if ($result = $run->getResultArray()) {
                    $records = [];
                    foreach ($result as $key => $value) {
                        $records[] = [
                            'ward_no' => ($value['ward_no']!="")?$value['ward_no']:"",
                            'saf_no' => ($value['saf_no']!="")?$value['saf_no']:"",
                            'tran_no' => ($value['tran_no']!="")?$value['tran_no']:"",
                            'tran_date' => ($value['tran_date']!="")?$value['tran_date']:"",
                            'tran_mode' => ($value['tran_mode']!="")?$value['tran_mode']:"",
                            'tran_type' => ($value['tran_type']!="")?$value['tran_type']:"",
                            'cheque_date' => ($value['cheque_date']!="")?$value['cheque_date']:"",
                            'cheque_no' => ($value['cheque_no']!="")?$value['cheque_no']:"",
                            'bank_name' => ($value['bank_name']!="")?$value['bank_name']:"",
                            'branch_name' => ($value['branch_name']!="")?$value['branch_name']:"",
                            'payable_amt' => ($value['payable_amt']!="")?$value['payable_amt']:"",
                            'clear_bounce_date' => ($value['clear_bounce_date']!="")?$value['clear_bounce_date']:"",
                            'remarks' => ($value['remarks']!="")?$value['remarks']:"",
                            'emp_name' => ($value['emp_name']!="")?$value['emp_name']:"",
                        ];
                    }
                    $spreadsheet = new Spreadsheet();
                    //phpOfficeLoad();
                    //$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                    $activeSheet = $spreadsheet->getActiveSheet();
                    $activeSheet->setCellValue('A1', 'Ward No.');
                    $activeSheet->setCellValue('B1', 'Saf No');
                    $activeSheet->setCellValue('C1', 'Tran No');
                    $activeSheet->setCellValue('D1', 'Tran Date');
                    $activeSheet->setCellValue('E1', 'Tran Mode');
                    $activeSheet->setCellValue('F1', 'Tran Type');
                    $activeSheet->setCellValue('G1', 'Cheque Date');
                    $activeSheet->setCellValue('H1', 'Cheque No.');
                    $activeSheet->setCellValue('I1', 'Bank Name');
                    $activeSheet->setCellValue('J1', 'Branch Name');
                    $activeSheet->setCellValue('K1', 'Tran Amount');
                    $activeSheet->setCellValue('L1', 'Clear/Bounce Date');
                    $activeSheet->setCellValue('M1', 'Remarks');
                    $activeSheet->setCellValue('N1', 'Tc Name');                          
                    $activeSheet->fromArray($records, NUll, 'A2');
                    $filename = "SAF_Bank_Reconcilliation_Report_" . date('Ymd-hisa') . ".xlsx";
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="' . $filename . '"');
                    header('Cache-Control: max-age=0');
                    $writer = new Xlsx($spreadsheet);
                    //$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                    $writer->save('php://output');
                }
            } else if ($data['module'] == 'GBSAF') {
                $where = " where  tran_date between '" . $data['from_date'] . "' and '" . $data['to_date'] . "'";
                if (isset($data['filter_date']) && $data['filter_date'] == 'by_ch_clear_date') {
                    $where = " where  clear_bounce_date between '" . $data['from_date'] . "' and '" . $data['to_date'] . "'";
                }
                if (isset($data['status']) && $data['status'] == 'pending') {
                    $where .= " and tbl_govt_saf_transaction.status=2 ";
                } elseif (isset($data['status']) && $data['status'] == 'clear') {
                    $where .= " and tbl_govt_saf_transaction.status=1 ";
                } elseif (isset($data['status']) && $data['status'] == 'bounced') {
                    $where .= " and tbl_govt_saf_transaction.status=3 ";
                } else
                    $where .= " and  tbl_govt_saf_transaction.status!=0 ";

                if ($data['payment_mode'] != '') {
                    $where .= " and tbl_govt_saf_transaction.tran_mode='" . $data['payment_mode'] . "'";
                }
                // SERACH BY CHEQUE NO.
                if ($data['cheque_no_input'] != '') {
                    $where = "where tbl_govt_saf_transaction_details.cheque_no='" . $data['cheque_no_input'] . "'";
                }
                $sql = "SELECT 
                            view_ward_mstr.ward_no,
                            tbl_govt_saf_dtl.application_no,
                            tbl_govt_saf_transaction.tran_no,
                            tbl_govt_saf_transaction.tran_date,
                            tbl_govt_saf_transaction.tran_mode,
                            tbl_govt_saf_transaction_details.cheque_date,
                            tbl_govt_saf_transaction_details.cheque_no,
                            tbl_govt_saf_transaction_details.bank_name,
                            tbl_govt_saf_transaction_details.branch_name,
                            tbl_govt_saf_transaction.payable_amt,
                            tbl_govt_saf_transaction_details.clear_bounce_date,
                            tbl_govt_saf_transaction_details.remarks,
                            view_emp_details.emp_name
                        FROM tbl_govt_saf_transaction 
                        INNER JOIN tbl_govt_saf_dtl ON tbl_govt_saf_dtl.id=tbl_govt_saf_transaction.govt_saf_dtl_id
                        INNER JOIN tbl_govt_saf_transaction_details on tbl_govt_saf_transaction_details.govt_saf_transaction_id=tbl_govt_saf_transaction.id
                        left join view_emp_details on  tbl_govt_saf_transaction.tran_by_emp_details_id=view_emp_details.id
                        LEFT JOIN view_ward_mstr on tbl_govt_saf_transaction.ward_mstr_id=view_ward_mstr.id  
                        $where";
                $run = $this->db->query($sql);
                if ($result = $run->getResultArray()) {
                    $records = [];
                    foreach ($result as $key => $value) {
                        $records[] = [
                            'ward_no' => ($value['ward_no']!="")?$value['ward_no']:"",
                            'application_no' => ($value['application_no']!="")?$value['application_no']:"",
                            'tran_no' => ($value['tran_no']!="")?$value['tran_no']:"",
                            'tran_date' => ($value['tran_date']!="")?$value['tran_date']:"",
                            'tran_mode' => ($value['tran_mode']!="")?$value['tran_mode']:"",
                            'cheque_date' => ($value['cheque_date']!="")?$value['cheque_date']:"",
                            'cheque_no' => ($value['cheque_no']!="")?$value['cheque_no']:"",
                            'bank_name' => ($value['bank_name']!="")?$value['bank_name']:"",
                            'branch_name' => ($value['branch_name']!="")?$value['branch_name']:"",
                            'payable_amt' => ($value['payable_amt']!="")?$value['payable_amt']:"",
                            'clear_bounce_date' => ($value['clear_bounce_date']!="")?$value['clear_bounce_date']:"",
                            'remarks' => ($value['remarks']!="")?$value['remarks']:"",
                            'emp_name' => ($value['emp_name']!="")?$value['emp_name']:"",
                        ];
                    }
                    $spreadsheet = new Spreadsheet();
                    //phpOfficeLoad();
                    //$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                    $activeSheet = $spreadsheet->getActiveSheet();
                    $activeSheet->setCellValue('A1', 'Ward No.');
                    $activeSheet->setCellValue('B1', 'Application No');
                    $activeSheet->setCellValue('C1', 'Tran No');
                    $activeSheet->setCellValue('D1', 'Tran Date');
                    $activeSheet->setCellValue('E1', 'Tran Mode');
                    $activeSheet->setCellValue('F1', 'Cheque Date');
                    $activeSheet->setCellValue('G1', 'Cheque No.');
                    $activeSheet->setCellValue('H1', 'Bank Name');
                    $activeSheet->setCellValue('I1', 'Branch Name');
                    $activeSheet->setCellValue('J1', 'Tran Amount');
                    $activeSheet->setCellValue('K1', 'Clear/Bounce Date');
                    $activeSheet->setCellValue('L1', 'Remarks');
                    $activeSheet->setCellValue('M1', 'Tc Name');                          
                    $activeSheet->fromArray($records, NUll, 'A2');
                    $filename = "GBSAF_Bank_Reconcilliation_Report_" . date('Ymd-hisa') . ".xlsx";
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="' . $filename . '"');
                    header('Cache-Control: max-age=0');
                    $writer = new Xlsx($spreadsheet);
                    //$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                    $writer->save('php://output');
                }
            } else if ($data['module'] == "TRADE") {
                $where = " where transaction_date between '" . $data['from_date'] . "' and '" . $data['to_date'] . "'";
                if (isset($data['filter_date']) && $data['filter_date'] == 'by_ch_clear_date') {
                    $where = " where  clear_bounce_date between '" . $data['from_date'] . "' and '" . $data['to_date'] . "'";
                }
                if (isset($data['status']) && $data['status'] == 'pending') {
                    $where .= " and tbl_transaction.status=2 ";
                } elseif (isset($data['status']) && $data['status'] == 'clear') {
                    $where .= " and tbl_transaction.status=1 ";
                } elseif (isset($data['status']) && $data['status'] == 'bounced') {
                    $where .= " and tbl_transaction.status=3 ";
                } else
                    $where .= " and  tbl_transaction.status!=0 ";
                // SERACH BY CHEQUE NO.
                if ($data['cheque_no_input'] != '') {
                    $where = "where tbl_cheque_dtl.cheque_no='" . $data['cheque_no_input'] . "'";
                }

                if ($data['payment_mode'] != '') {
                    $where .= " and tbl_transaction.tran_mode='" . $data['payment_mode'] . "'";
                }
                $sql = "select 
                            view_ward_mstr.ward_no,
                            tbl_apply_licence.application_no,
                            tbl_transaction.transaction_no,
                            tbl_transaction.transaction_date,
                            tbl_transaction.payment_mode,
                            'Trade' as tran_type,
                            tbl_cheque_dtl.cheque_date,
                            tbl_cheque_dtl.cheque_no,
                            tbl_cheque_dtl.bank_name,
                            tbl_cheque_dtl.branch_name,
                            tbl_transaction.paid_amount,
                            tbl_cheque_dtl.clear_bounce_date,
                            tbl_cheque_dtl.remarks as clear_bounce_remarks,
                            view_emp_details.emp_name
                        from tbl_transaction 
                        INNER JOIN tbl_apply_licence ON tbl_apply_licence.id=tbl_transaction.related_id
                        join tbl_cheque_dtl on tbl_cheque_dtl.transaction_id=tbl_transaction.id 
                        left join view_emp_details on view_emp_details.id = tbl_transaction.emp_details_id 
                        LEFT JOIN view_ward_mstr on tbl_apply_licence.ward_mstr_id=view_ward_mstr.id
                        $where";
                $run = $this->trade->query($sql);
                if ($result = $run->getResultArray()) {
                    $records = [];
                    foreach ($result as $key => $value) {
                        $records[] = [
                            'ward_no' => ($value['ward_no']!="")?$value['ward_no']:"",
                            'application_no' => ($value['application_no']!="")?$value['application_no']:"",
                            'tran_no' => ($value['transaction_no']!="")?$value['transaction_no']:"",
                            'tran_date' => ($value['transaction_date']!="")?$value['transaction_date']:"",
                            'tran_mode' => ($value['payment_mode']!="")?$value['payment_mode']:"",
                            'tran_type' => ($value['tran_type']!="")?$value['tran_type']:"",
                            'cheque_date' => ($value['cheque_date']!="")?$value['cheque_date']:"",
                            'cheque_no' => ($value['cheque_no']!="")?$value['cheque_no']:"",
                            'bank_name' => ($value['bank_name']!="")?$value['bank_name']:"",
                            'branch_name' => ($value['branch_name']!="")?$value['branch_name']:"",
                            'payable_amt' => ($value['paid_amount']!="")?$value['paid_amount']:"",
                            'clear_bounce_date' => ($value['clear_bounce_date']!="")?$value['clear_bounce_date']:"",
                            'remarks' => ($value['clear_bounce_remarks']!="")?$value['clear_bounce_remarks']:"",
                            'emp_name' => ($value['emp_name']!="")?$value['emp_name']:"",
                        ];
                    }
                    $spreadsheet = new Spreadsheet();
                    $activeSheet = $spreadsheet->getActiveSheet();  
                    $activeSheet->setCellValue('A1', 'Ward No.');
                    $activeSheet->setCellValue('B1', 'Application No');
                    $activeSheet->setCellValue('C1', 'Tran No');
                    $activeSheet->setCellValue('D1', 'Tran Date');
                    $activeSheet->setCellValue('E1', 'Tran Mode');
                    $activeSheet->setCellValue('F1', 'Tran Type');
                    $activeSheet->setCellValue('G1', 'Cheque Date');
                    $activeSheet->setCellValue('H1', 'Cheque No.');
                    $activeSheet->setCellValue('I1', 'Bank Name');
                    $activeSheet->setCellValue('J1', 'Branch Name');
                    $activeSheet->setCellValue('K1', 'Tran Amount');
                    $activeSheet->setCellValue('L1', 'Clear/Bounce Date');
                    $activeSheet->setCellValue('M1', 'Remarks');
                    $activeSheet->setCellValue('N1', 'Tc Name');                          
                    $activeSheet->fromArray($records, NUll, 'A2');
                    $filename = "Trade_Bank_Reconcilliation_Report_" . date('Ymd-hisa') . ".xlsx";
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="' . $filename . '"');
                    header('Cache-Control: max-age=0');
                    $writer = new Xlsx($spreadsheet);
                    //$writer->save(APPPATH.'/hello world.xlsx');
                    $writer->save('php://output');
                }
            }
            return;
        } catch (Exception $e) {
            echo "Exception";
            print_r($e);
        }
    }
}
