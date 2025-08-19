<?php
namespace App\Controllers;

use App\Models\model_water_consumer;
use CodeIgniter\Controller;
use App\Models\model_datatable;
use App\Models\model_view_saf_receive_list;
use App\Models\model_ward_mstr;
use App\Models\model_view_saf_doc_dtl;
use App\Models\model_level_pending_dtl;
use App\Models\model_emp_dtl_permission;
use App\Models\model_saf_owner_detail;
use App\Models\model_saf_doc_dtl;
use App\Models\model_saf_dtl;
use App\Models\model_saf_memo_dtl;
use App\Models\model_saf_floor_details;
use App\Models\model_prop_dtl;
use App\Models\model_prop_owner_detail;
use App\Models\model_prop_floor_details;
use App\Models\model_saf_tax;
use App\Models\model_prop_tax;
use App\Models\model_saf_demand;
use App\Models\model_prop_demand;
use App\Models\model_fy_mstr;
use App\Models\model_prop_type_mstr;
use App\Models\model_road_type_mstr;
use App\Models\model_view_saf_floor_details;
use App\Models\model_field_verification_dtl;
use App\Models\model_field_verification_floor_details;
use App\Models\model_doc_mstr;
use App\Models\model_ulb_mstr;
use App\Models\model_view_ward_permission;
use App\Models\model_view_saf_dtl;
use App\Controllers\Reports\PropReports;
use Exception;
//use False\True;

class glitchPro extends BaseController
{
    protected $db;
    protected $dbSystem;
    protected $trade;
    protected $model_view_saf_dtl;
    protected $model_ulb_mstr;
    protected $model_ward_mstr;
    protected $model_emp_dtl_permission;
    protected $model_view_ward_permission;
    protected $model_fy_mstr;
    protected $model_view_saf_receive_list;
    protected $model_view_saf_doc_dtl;
    protected $model_level_pending_dtl;
    protected $model_saf_owner_detail;
    protected $model_saf_doc_dtl;
    protected $model_saf_dtl;
    protected $model_saf_memo_dtl;
    protected $model_saf_floor_details;
    protected $model_prop_dtl;
    protected $model_prop_owner_detail;
    protected $model_prop_floor_details;
    protected $model_saf_tax;
    protected $model_prop_tax;
    protected $model_saf_demand;
    protected $model_prop_demand;
    protected $model_prop_type_mstr;
    protected $model_road_type_mstr;
    protected $model_view_saf_floor_details;
    protected $model_field_verification_dtl;
    protected $model_field_verification_floor_details;
    protected $model_doc_mstr;
    protected $model_water_consumer;
    protected $model_datatable;
    protected $PropReports;


    public function __construct()
    {
        parent::__construct();
        helper(['db_helper', 'utility_helper']);
        if ($db_name = dbConfig("property")) {
            $this->db = db_connect($db_name);
        }
        if ($db_name1 = dbConfig("water")) {
            $this->water = db_connect($db_name1);
        }
        if ($db_trade = dbConfig("trade")) {
            $this->trade = db_connect($db_trade);
        }
        if ($db_system = dbSystem()) {
            $this->dbSystem = db_connect($db_system);
        }
        $this->PropReports = new PropReports();
        $this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_emp_dtl_permission = new model_emp_dtl_permission($this->dbSystem);
        $this->model_view_ward_permission = new model_view_ward_permission($this->dbSystem);
        $this->model_fy_mstr = new model_fy_mstr($this->dbSystem);
        $this->model_view_saf_receive_list = new model_view_saf_receive_list($this->db);
        $this->model_view_saf_doc_dtl = new model_view_saf_doc_dtl($this->db);
        $this->model_level_pending_dtl = new model_level_pending_dtl($this->db);
        $this->model_saf_owner_detail = new model_saf_owner_detail($this->db);
        $this->model_saf_doc_dtl = new model_saf_doc_dtl($this->db);
        $this->model_saf_dtl = new model_saf_dtl($this->db);
        $this->model_saf_memo_dtl = new model_saf_memo_dtl($this->db);
        $this->model_saf_floor_details = new model_saf_floor_details($this->db);
        $this->model_prop_dtl = new model_prop_dtl($this->db);
        $this->model_prop_owner_detail = new model_prop_owner_detail($this->db);
        $this->model_prop_floor_details = new model_prop_floor_details($this->db);
        $this->model_saf_tax = new model_saf_tax($this->db);
        $this->model_prop_tax = new model_prop_tax($this->db);
        $this->model_saf_demand = new model_saf_demand($this->db);
        $this->model_prop_demand = new model_prop_demand($this->db);
        $this->model_prop_type_mstr = new model_prop_type_mstr($this->db);
        $this->model_road_type_mstr = new model_road_type_mstr($this->db);
        $this->model_view_saf_floor_details = new model_view_saf_floor_details($this->db);
        $this->model_field_verification_dtl = new model_field_verification_dtl($this->db);
        $this->model_field_verification_floor_details = new model_field_verification_floor_details($this->db);
        $this->model_doc_mstr = new model_doc_mstr($this->db);
        $this->model_view_saf_dtl = new model_view_saf_dtl($this->db);
        $this->model_water_consumer = new model_water_consumer($this->water);
        $this->model_datatable = new model_datatable($this->db);
    }

    function __destruct()
    {
        $this->db->close();
        $this->dbSystem->close();
    }

    public function check()
    {
        echo 'ok';
    }

    public function index()
    {

    }

    public function consumerDetails($consumer_no){
        $sql = "SELECT * FROM view_consumer_owner_details WHERE consumer_no = '$consumer_no'";
        $query = $this->water->query($sql);
        $result = $query->getFirstRow();
        return $result;
    }

    private function demandHistory($id, $demand_from){
        $sql_demand_history = " SELECT tbl_consumer_demand.id,tbl_consumer_demand.generation_date,paid_status,
                                       amount,penalty,balance_amount,demand_from,demand_upto,
                                       connection_type, current_meter_reading,
                                       tbl_meter_reading_doc.file_name,tbl_meter_reading_doc.meter_no
                                FROM tbl_consumer_demand
                                LEFT JOIN tbl_meter_reading_doc ON tbl_meter_reading_doc.demand_id = tbl_consumer_demand.id 
                                    AND tbl_meter_reading_doc.status = 1
                                WHERE tbl_consumer_demand.status = 1 
                                    AND tbl_consumer_demand.consumer_id='$id'
                                    AND tbl_consumer_demand.demand_from >= '$demand_from'
                                ORDER BY demand_from DESC
                                ";
        $data["demand_history"] = $this->water->query($sql_demand_history)->getResultArray();
        return $data['demand_history'];
    }

    public function wrongMeterUpdate(){

        $data = [];

        if (isset($_POST['btn_search'])){
//            dd($_POST);
            $inputData = arrFilterSanitizeString($this->request->getVar());
            $consumer_no = $inputData['consumer_no'];
            $demand_from = $inputData['from_date'];
            $data['owner_details'] = $this->consumerDetails($consumer_no);
            $id = $data['owner_details']->id;

            $data['demand_history'] = $this->demandHistory($id, $demand_from);
//            dd($data['demand_history']);
        }

        return view('glitch/wrongMeterReadingUpdate', $data);
    }






    public function isMapped($holding_no){
        $sql = "SELECT COUNT(*) FROM tbl_consumer WHERE holding_no = '$holding_no'";
        $query = $this->water->query($sql);
        $result = $query->getRow();
        return ($result->count > 0) ? 1 : 0;

    }


    public function isHoldingValid($holding_no){
        $sql = "SELECT holding_no FROM tbl_prop_dtl WHERE holding_no = '$holding_no' OR new_holding_no = '$holding_no'";
        $query = $this->db->query($sql);
        $result = $query->getRow();
        return ($result) ? $result->consumer_no : null;
    }

    public function isConsumerValid($consumer_no){
        $sql = "SELECT consumer_no FROM tbl_consumer WHERE consumer_no = '$consumer_no'";
        $query = $this->water->query($sql);
        return $query->getResultArray();
    }

    public function getHoldingData ($holding_no){
        $sql = "SELECT tbl_prop_dtl.id, tbl_prop_dtl.new_holding_no, tbl_prop_dtl.water_conn_no, tbl_prop_owner_detail.owner_name, tbl_prop_dtl.prop_address FROM tbl_prop_dtl
            INNER JOIN tbl_prop_owner_detail ON tbl_prop_dtl.id = tbl_prop_owner_detail.prop_dtl_id
            WHERE tbl_prop_dtl.holding_no = '$holding_no' OR tbl_prop_dtl.new_holding_no = '$holding_no'";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }

    public function getWaterData($consumer_no){
        $sql = "SELECT tbl_consumer.*, tbl_consumer_details.*, tbl_consumer.created_on
        FROM tbl_consumer
        INNER JOIN tbl_consumer_details ON tbl_consumer_details.consumer_id = tbl_consumer.id
        WHERE tbl_consumer.consumer_no = '$consumer_no'";

        $query = $this->water->query($sql);
        $result = $query->getResultArray();

        return $result;
    }


    public function updatePropDtl($consumer_no, $water_connection_date,$holding_no){

        $sql = "UPDATE tbl_prop_dtl
            SET water_conn_no = '$consumer_no', water_conn_date = '$water_connection_date'
            WHERE new_holding_no = '$holding_no'";
        $query = $this->db->query($sql);
        return $this->db->affectedRows();
    }

    public function HoldingMap()
    {
        $data = [];

        if (isset($_POST['btn_search_holding']) || isset($_POST['btn_search_consumer'])) {
            $inputData = arrFilterSanitizeString($this->request->getVar());
            $holding_no = $inputData['holding_no'];

            $consumerMapped = $this->isMapped($holding_no);

            if ($consumerMapped == '1') {
                flashToast('message', 'Water Consumer Already Mapped');
                $url = base_url('glitchPro/HoldingMap/');
                return $this->response->redirect($url);

            } else {
                $data['result'] = $this->getHoldingData($holding_no);
            }
        }




        if (isset($_POST['btn_search_consumer'])){

            $consumer_no = $_POST['consumer_no'];
            $validatedConsumerNo = $this->isConsumerValid($consumer_no);

            if ((string)$consumer_no === (string)$validatedConsumerNo[0]['consumer_no']){
                $data['consumer'] = $this->getWaterData($consumer_no);
            }else{
                flashToast('error', 'Invalid Consumer No');
                $url = base_url('glitchPro/HoldingMap/');
                return $this->response->redirect($url);
            }

        }

        if(isset($_POST['btn_verify'])){
            $inputData = arrFilterSanitizeString($this->request->getVar());
            $prop_dtl_id = $inputData['prop_dtl_id'];
            $holding_no = $inputData['holding_no'];
            $consumer_no = $inputData['water_consumer_no'];
            $water_connection_date = $inputData['water_connection_data'];

            $this->db->transStart();

            $waterUpdate = $this->model_water_consumer->updateNonMapHoldingNO($consumer_no, $holding_no, $prop_dtl_id);

            if ($this->db->transStatus() === false){
                $this->db->transRollback();
                flashToast('error', 'Transaction failed: Water Consumer Mapping Failed');
                $url=base_url('glitchPro/HoldingMap/');
                return $this->response->redirect($url);
            } else {
                $this->updatePropDtl($consumer_no, $water_connection_date, $holding_no);

                if ($this->db->transStatus() === false) {
                    $this->db->transRollback();
                    flashToast('error', 'Transaction failed: Error While Property Detail Update');
                    $url=base_url('glitchPro/HoldingMap/');
                    return $this->response->redirect($url);
                } else {
                    $this->db->transCommit();
                    flashToast('message', 'Water Consumer Mapped Successfully');
                    $url=base_url('glitchPro/HoldingMap/');
                    return $this->response->redirect($url);
                }
            }
        }


        return view('glitch/HoldingMap', $data);
    }


}