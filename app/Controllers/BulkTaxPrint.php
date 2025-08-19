<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\model_tran_mode_mstr;
use App\Models\model_transaction;
use App\Models\model_ulb_mstr;
use App\Models\model_fy_mstr;
use App\Models\model_prop_dtl;
use App\Models\model_collection;
use App\Models\model_saf_collection;
use App\Models\model_saf_dtl;
use App\Models\model_cheque_details;
use App\Models\model_transaction_fine_rebet_details;
use App\Models\model_system_name;
use App\Models\model_emp_details;


class BulkTaxPrint extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $model_ulb_mstr;
    protected $model_ward_mstr;
    protected $model_fy_mstr;
    protected $model_saf_dtl;
    protected $model_prop_dtl;
    protected $model_transaction;
    protected $model_collection;
    protected $model_cheque_details;
    protected $model_transaction_fine_rebet_details;
    protected $modelprop;
    protected $model_saf_collection;
    protected $model_system_name;
    protected $modeladjust;
    protected $modelpay;
    protected $modelpropcoll;
    protected $modelassess;
    protected $modelchqDD;
    protected $model_emp_details;

    public function __construct() {
        Parent::__construct();
        helper(['db_helper', 'qr_code_generator_helper', 'form', 'utility_helper']);
        if ($db_name = dbConfig("property")) {
            $this->db = db_connect($db_name);
        }
        $this->model_saf_dtl = new model_saf_dtl($this->db);
        $this->model_prop_dtl = new model_prop_dtl($this->db);
        $this->model_transaction = new model_transaction($this->db);
        $this->model_collection = new model_collection($this->db);
        $this->modelprop = new model_prop_dtl($this->db);
        $this->modelpay = new model_transaction($this->db);
        $this->modelassess = new model_saf_dtl($this->db);
        $this->modeltran = new model_tran_mode_mstr($this->db);
        $this->modelchqDD = new model_cheque_details($this->db);
        $this->modelpropcoll = new model_collection($this->db);
        $this->modelpenalty = new model_transaction_fine_rebet_details($this->db);
        $this->modelsafcoll = new model_saf_collection($this->db);
        $this->model_emp_details = new model_emp_details($this->db);
    }

    function __destruct() {
		if (isset($this->db)) $this->db->close();
	}


    public function bulkTaxPrint() {
        $data = (array)null;
        $ulb_mstr = [
            "ulb_mstr_id" => "1",
            "logo_path" => "/muncipalicon/RMC_LOGO.jpg",
            "watermark_path" => "/img/logo/1.png",
            "property" => "db_rmc_property",
            "water" => "db_rmc_water",
            "trade" => "db_rmc_trade",
            "advertisement" => "db_rmc_advertisement",
            "state" => "JHARKHAND",
            "district" => "RANCHI",
            "city" => "RANCHI",
            "ulb_type_id" => "1",
            "ulb_name" => "Ranchi Municipal Corporation",
            "ulb_name_hindi" => "रांची नगर निगम",
        ];
        $data = $this->request->getVar();
        if (!empty($data)) {
            if ($data["tran_type"]=="Property") {
                $sql = "SELECT 
                            transaction_fine_rebet_details.fine_rebet_dtl,
                            tbl_transaction.id,
                            tbl_transaction.tran_type,
                            tbl_transaction.tran_mode,
                            tbl_transaction.payable_amt,
                            tbl_transaction.round_off,
                            tbl_transaction.tran_no,
                            tbl_transaction.tran_date,
                            tbl_transaction.from_fyear,
                            tbl_transaction.from_qtr,
                            tbl_transaction.upto_fyear,
                            tbl_transaction.upto_qtr,
                            tbl_transaction.status AS tran_staus,
                            collection_dtl.holding_tax,
                            collection_dtl.water_tax,
                            collection_dtl.education_cess,
                            collection_dtl.health_cess,
                            collection_dtl.latrine_tax,
                            collection_dtl.additional_tax,	
                            tbl_cheque_details.cheque_no,
                            tbl_cheque_details.cheque_date,
                            tbl_cheque_details.bank_name,
                            tbl_cheque_details.branch_name,
                            tbl_prop_dtl.id AS prop_id,
                            tbl_prop_dtl.holding_no,
                            tbl_prop_dtl.new_holding_no,
                            tbl_prop_dtl.prop_address,
                            view_ward_mstr.ward_no,
                            new_ward_mstr.ward_no AS new_ward_no,
                            prop_owner_dtl.owner_name,
                            prop_owner_dtl.mobile_no
                        FROM tbl_transaction
                        INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_transaction.prop_dtl_id AND tbl_transaction.tran_type='Property'
                        INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
                        LEFT JOIN view_ward_mstr AS new_ward_mstr ON new_ward_mstr.id=tbl_prop_dtl.new_ward_mstr_id
                        INNER JOIN (
                            SELECT
                                prop_dtl_id,
                                STRING_AGG(CONCAT(owner_name, ' ', relation_type, ' ', guardian_name), ',')	AS owner_name,
                                STRING_AGG(mobile_no::TEXT, ',') AS mobile_no
                            FROM tbl_prop_owner_detail WHERE status=1 GROUP BY prop_dtl_id
                        ) AS prop_owner_dtl ON prop_owner_dtl.prop_dtl_id=tbl_prop_dtl.id
                        INNER JOIN (
                            SELECT 
                                transaction_id,
                                SUM(holding_tax) AS holding_tax, 
                                SUM(water_tax) AS water_tax,
                                SUM(education_cess) AS education_cess,
                                SUM(health_cess) AS health_cess,
                                SUM(latrine_tax) AS latrine_tax,
                                SUM(additional_tax) AS additional_tax
                            FROM tbl_collection
                            Group by transaction_id
                        ) AS collection_dtl ON collection_dtl.transaction_id=tbl_transaction.id AND tbl_transaction.tran_type='Property'
                        LEFT JOIN tbl_cheque_details ON tbl_cheque_details.transaction_id=tbl_transaction.id
                        LEFT JOIN (
                            SELECT 
                                transaction_id,
                                json_agg(json_build_object('head_name', head_name, 'amount', amount)) AS fine_rebet_dtl
                            FROM tbl_transaction_fine_rebet_details
                            GROUP BY transaction_id
                        ) AS transaction_fine_rebet_details ON transaction_fine_rebet_details.transaction_id=tbl_transaction.id
                        WHERE 
                            tbl_transaction.status IN (1,2)
                            AND tbl_transaction.tran_date BETWEEN '".$data['start_tran_date']."' AND '".$data['end_tran_date']."'
                            AND tbl_transaction.tran_by_emp_details_id='".$data['tc_id']."'";
            } else if ($data["tran_type"]=="Saf") {
                $sql = "SELECT 
                            transaction_fine_rebet_details.fine_rebet_dtl,
                            tbl_transaction.id,
                            tbl_transaction.tran_type,
                            tbl_transaction.tran_mode,
                            tbl_transaction.payable_amt,
                            tbl_transaction.round_off,
                            tbl_transaction.tran_no,
                            tbl_transaction.tran_date,
                            tbl_transaction.from_fyear,
                            tbl_transaction.from_qtr,
                            tbl_transaction.upto_fyear,
                            tbl_transaction.upto_qtr,
                            tbl_transaction.status AS tran_staus,
                            collection_dtl.holding_tax,
                            collection_dtl.water_tax,
                            collection_dtl.education_cess,
                            collection_dtl.health_cess,
                            collection_dtl.latrine_tax,
                            collection_dtl.additional_tax,	
                            tbl_cheque_details.cheque_no,
                            tbl_cheque_details.cheque_date,
                            tbl_cheque_details.bank_name,
                            tbl_cheque_details.branch_name,
                            tbl_saf_dtl.id AS saf_id,
                            tbl_saf_dtl.saf_no AS holding_no,
                            '' AS new_holding_no,
                            tbl_saf_dtl.prop_address,
                            view_ward_mstr.ward_no,
                            new_ward_mstr.ward_no AS new_ward_no,
                            saf_owner_dtl.owner_name,
                            saf_owner_dtl.mobile_no
                        FROM tbl_transaction
                        INNER JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_transaction.prop_dtl_id AND tbl_transaction.tran_type='Saf'
                        LEFT JOIN view_ward_mstr ON view_ward_mstr.id=tbl_saf_dtl.ward_mstr_id
                        INNER JOIN view_ward_mstr AS new_ward_mstr ON new_ward_mstr.id=tbl_saf_dtl.new_ward_mstr_id
                        INNER JOIN (
                            SELECT
                                saf_dtl_id,
                                STRING_AGG(CONCAT(owner_name, ' ', guardian_name, ' ', relation_type), ',')	AS owner_name,
                                STRING_AGG(mobile_no::TEXT, ',') AS mobile_no
                            FROM tbl_saf_owner_detail WHERE status=1 
                            GROUP BY saf_dtl_id
                        ) AS saf_owner_dtl ON saf_owner_dtl.saf_dtl_id=tbl_saf_dtl.id
                        INNER JOIN (
                            SELECT 
                                transaction_id,
                                SUM(holding_tax) AS holding_tax, 
                                SUM(water_tax) AS water_tax,
                                SUM(education_cess) AS education_cess,
                                SUM(health_cess) AS health_cess,
                                SUM(latrine_tax) AS latrine_tax,
                                SUM(additional_tax) AS additional_tax
                            FROM tbl_saf_collection
                            Group by transaction_id
                        ) AS collection_dtl ON collection_dtl.transaction_id=tbl_transaction.id AND tbl_transaction.tran_type='Saf'
                        LEFT JOIN tbl_cheque_details ON tbl_cheque_details.transaction_id=tbl_transaction.id
                        LEFT JOIN (
                            SELECT 
                                transaction_id,
                                json_agg(json_build_object('head_name', head_name, 'amount', amount)) AS fine_rebet_dtl
                            FROM tbl_transaction_fine_rebet_details
                            GROUP BY transaction_id
                        ) AS transaction_fine_rebet_details ON transaction_fine_rebet_details.transaction_id=tbl_transaction.id
                        WHERE 
                            tbl_transaction.status IN (1,2)
                            AND tbl_transaction.tran_date BETWEEN '".$data['start_tran_date']."' AND '".$data['end_tran_date']."'
                            AND tbl_transaction.tran_by_emp_details_id='".$data['tc_id']."'";
            }
            if ($tran_list = $this->db->query($sql)->getResultArray()) {
                foreach ($tran_list as $key => $tran_row) {
                    $receipt_data_array[$key]['path'] = $path = base_url('citizenPaymentReceipt/payment_jsk_receipt/' . $ulb_mstr["ulb_mstr_id"] . '/' . md5($tran_row['id']));
                    $tran_list[$key]['ss'] = $data['ss'] = qrCodeGeneratorFun($path);
                    if ($tran_row["fine_rebet_dtl"]!="") {
                        $tran_list[$key]['penalty_dtl'] = json_decode($tran_row["fine_rebet_dtl"], true);
                    }
                }
                //print_var($tran_list);
                $data["tran_list"] = $tran_list;
            }
        }
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $data['logo_path'] = $ulb_mstr["logo_path"];
        $data['ulb_mstr_name'] = $ulb_mstr["ulb_name"];
        $tc_list = $this->model_emp_details->getActivatedAllTcList();
        $data["tc_list"] = $tc_list;
        return view('property/jsk/bulkTaxPrintpayment_receipt', $data);
    }
    
}
