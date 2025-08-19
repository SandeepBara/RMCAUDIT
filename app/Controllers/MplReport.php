<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_transfer_mode_mstr;
use App\Models\model_prop_type_mstr;
use App\Models\model_doc_mstr;
use App\Models\model_saf_owner_detail;
use App\Models\model_saf_doc_dtl;
use App\Models\model_saf_dtl;
use App\Models\model_view_saf_dtl;
use App\Models\model_level_pending_dtl;
use App\Models\model_view_saf_doc_dtl;
use App\Models\model_ulb_mstr;
use App\Models\model_transaction;
use App\Models\model_saf_floor_details;
use App\Models\model_fy_mstr;
use App\Models\model_datatable;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

use Exception;

class MplReport extends AlphaController
{
	protected $db;
	protected $dbSystem;
	protected $model_transfer_mode_mstr;
	protected $model_prop_type_mstr;
	protected $model_doc_mstr;
	protected $model_saf_owner_detail;
	protected $model_saf_doc_dtl;
	protected $model_saf_dtl;
	protected $model_view_saf_dtl;
	protected $model_level_pending_dtl;
	protected $model_view_saf_doc_dtl;
	protected $model_ulb_mstr;
	protected $model_transaction;
	protected $model_saf_floor_details;
    protected $model_fy_mstr;
    protected $model_datatable;

	public function __construct()
	{
		
		parent::__construct();
		helper(['db_helper', 'upload_helper', 'form', 'utility_helper']);
		if ($db_name = dbConfig("property")) {
			$this->db = db_connect($db_name);
		}
		if ($db_system = dbSystem()) {
			$this->dbSystem = db_connect($db_system);
		}
		$this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
		$this->model_transfer_mode_mstr = new model_transfer_mode_mstr($this->db);
		$this->model_prop_type_mstr = new model_prop_type_mstr($this->db);
		$this->model_doc_mstr = new model_doc_mstr($this->db);
		$this->model_saf_owner_detail = new model_saf_owner_detail($this->db);
		$this->model_saf_doc_dtl = new model_saf_doc_dtl($this->db);
		$this->model_saf_dtl = new model_saf_dtl($this->db);
		$this->model_view_saf_dtl = new model_view_saf_dtl($this->db);
		$this->model_level_pending_dtl = new model_level_pending_dtl($this->db);
		$this->model_view_saf_doc_dtl = new model_view_saf_doc_dtl($this->db);
		$this->model_transaction = new model_transaction($this->db);
		$this->model_saf_floor_details = new model_saf_floor_details($this->db);
        $this->model_fy_mstr = new model_fy_mstr($this->db);
        $this->model_datatable = new model_datatable($this->db);
	}

	function __destruct() {
		if(isset($this->db)) $this->db->close();
		if(isset($this->dbSystem)) $this->dbSystem->close();
	}

    public function index()
    {

    }

    public function makeFirstDateByFyearQtr($fyear, $qtr) {
		list($fyear1, $fyear2) = explode("-", $fyear);
		if ($qtr==1) {
			return $fyear1."-04-01";
		} else if ($qtr==2) {
			return $fyear1."-07-01";
		} else if ($qtr==3) {
			return $fyear1."-10-01";
		} else if ($qtr==4) {
			return $fyear2."-01-01";
		}
	}

	public function makeDueDateByFyearQtr($fyear, $qtr) {
		list($fyear1, $fyear2) = explode("-", $fyear);
		if ($qtr==1) {
			return $fyear1."-06-30";
		} else if ($qtr==2) {
			return $fyear1."-09-30";
		} else if ($qtr==3) {
			return $fyear1."-12-31";
		} else if ($qtr==4) {
			return $fyear2."-03-31";
		}
	}

    public function assessed_hh()
    {
        $currentFY = getFY();
        $currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$currentFY])['id'];
        $fyear = explode('-',$currentFY);
                    
        $fromYear = $fyear[0].'-04-01';
        $uptoYear = $fyear[1].'-03-31';

        try{
            $data = array();
            $sql_assd = "SELECT COUNT(DISTINCT(saf_doc_dtl.saf_dtl_id)) AS assessed_hh FROM tbl_saf_dtl
                        INNER JOIN (SELECT saf_dtl_id FROM tbl_saf_doc_dtl WHERE status=1 AND verify_status!=0
                        AND saf_owner_dtl_id IS NULL GROUP BY saf_dtl_id) AS saf_doc_dtl ON saf_doc_dtl.saf_dtl_id=tbl_saf_dtl.id 
                        WHERE tbl_saf_dtl.status=1 and tbl_saf_dtl.created_on<'".$fromYear."'";
            $result = $this->db->query($sql_assd)->getFirstRow("array");

            $sql = "SELECT count(tbl_saf_dtl.*) as saf_done FROM tbl_saf_dtl 
                    JOIN tbl_transaction on tbl_transaction.prop_dtl_id=tbl_saf_dtl.id AND tbl_transaction.tran_type='Saf' AND tbl_transaction.status=1
                    WHERE tbl_saf_dtl.status=1 AND tbl_saf_dtl.apply_date between '".$fromYear."' and '".$uptoYear."'" ;
            
            $result1 = $this->db->query($sql)->getFirstRow("array");


            $response['ulb_name'] = 'RANCHI MUNICIPAL CORPORATION';
            $response['assessed_hh'] = $result['assessed_hh'];
            $response['suda_target'] = '35000';
            $response['suda_target_date'] = '06-06-2023';
            $response['saf_done'] = $result1['saf_done'];
            $response['balance'] = 35000-$result1['saf_done'];
            $response['assessed_hh_upto'] = date('d.m.Y', strtotime('31-03-'.$fyear[0]));
            $response['saf_done_upto'] = date('d.m.Y', strtotime($fromYear)).'-'.date('d.m.Y');
            
            $res['data'] = $response;
            
            return view('property/reports/assessed_hh_report', $res);
            
        }
        catch (Exception $e) {
            flashToast("message", $e->getMessage());
        }

    }

    public function collection21_22Paid()
    {
        $currentFY = getFY();
        $currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$currentFY])['id'];
        $fyear = explode('-',$currentFY);
                    
        $fromYear = $fyear[0].'-04-01';
        $uptoYear = $fyear[1].'-03-31';

        try{
                $sql = "with 
                    property as(
                        SELECT tbl_prop_dtl.id as prop_dtl_id ,demand_amount from tbl_prop_dtl
                        join (
                            select tbl_prop_demand.prop_dtl_id,sum(COALESCE(tbl_prop_demand.amount, 0)-COALESCE(tbl_prop_demand.adjust_amt,0)) as demand_amount from tbl_prop_demand 
                            join tbl_collection on tbl_collection.prop_demand_id=tbl_prop_demand.id 
                            where tbl_prop_demand.status=1 and tbl_prop_demand.paid_status=1 and tbl_prop_demand.fyear='2021-2022' and tbl_prop_demand.created_on::date<='2022-03-31' and (tbl_collection.created_on::date between '2021-04-01' and '2022-03-31') group by tbl_prop_demand.prop_dtl_id
                            ) demand_21_22 on demand_21_22.prop_dtl_id=tbl_prop_dtl.id
                        left join (select tbl_prop_demand.prop_dtl_id from tbl_prop_demand 
                                join tbl_collection on tbl_collection.prop_demand_id=tbl_prop_demand.id and tbl_collection.status=1
                                where tbl_prop_demand.status=1 and paid_status=1 and tbl_prop_demand.fyear='2022-2023' and tbl_collection.created_on::date<='2023-03-31' group by tbl_prop_demand.prop_dtl_id) demand_22_23 
                        on demand_22_23.prop_dtl_id=tbl_prop_dtl.id
                        where demand_22_23.prop_dtl_id is null and tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 and tbl_prop_dtl.govt_saf_dtl_id is null
                    ),
                    exempted as (
                        SELECT below_350.prop_dtl_id FROM (
                            SELECT distinct tbl_prop_dtl.id as prop_dtl_id
                            from tbl_prop_dtl 
                            LEFT JOIN (SELECT prop_dtl_id FROM tbl_prop_floor_details where builtup_area >350 and status=1 group by prop_dtl_id) floor ON floor.prop_dtl_id=tbl_prop_dtl.id
                            where tbl_prop_dtl.status=1  and floor.prop_dtl_id is null and char_length(tbl_prop_dtl.new_holding_no)>0 and tbl_prop_dtl.prop_type_mstr_id not in(4,3)
                        ) as below_350
                        left join (SELECT prop_dtl_id FROM tbl_prop_floor_details where (const_type_mstr_id=1 OR usage_type_mstr_id!=1 OR occupancy_type_mstr_id!=1) and status=1 group by prop_dtl_id ) tbl_prop_floor_details on tbl_prop_floor_details.prop_dtl_id=below_350.prop_dtl_id
                        WHERE tbl_prop_floor_details.prop_dtl_id is null
                    ),
                    hh_paid_in_previous_fyear as (
                        select sum(tbl_prop_demand.amount-tbl_prop_demand.adjust_amt) as paid_amount,
                        tbl_prop_demand.prop_dtl_id from tbl_prop_demand 
                        INNER JOIN tbl_collection on tbl_collection.prop_demand_id=tbl_prop_demand.id 
                        where tbl_prop_demand.status=1 and tbl_prop_demand.paid_status=1 and tbl_prop_demand.fyear='2022-2023' and (tbl_collection.created_on::date between '2022-04-01' and '2023-03-31') group by tbl_prop_demand.prop_dtl_id
                    ),
                    hh_paid_in_current_fyear as (
                        select sum(tbl_prop_demand.amount-tbl_prop_demand.adjust_amt) as paid_amount,
                        tbl_prop_demand.prop_dtl_id from tbl_prop_demand 
                        INNER JOIN tbl_collection on tbl_collection.prop_demand_id=tbl_prop_demand.id 
                        where tbl_prop_demand.status=1 and tbl_prop_demand.paid_status=1 and tbl_prop_demand.fyear='2022-2023' and (tbl_collection.created_on::date between '2023-04-01' and '2024-03-31') group by tbl_prop_demand.prop_dtl_id
                    )

                SELECT 
                count(property.prop_dtl_id) as no_of_hh,
                sum(property.demand_amount) as demand_amount,
                count(exempted.prop_dtl_id) as exempted,
                sum(exempted_demand.demand_amount) as exempt_demand,
                count(distinct hh_paid_in_previous_fyear.prop_dtl_id) as collected_hh, 
                sum(hh_paid_in_previous_fyear.paid_amount) as collection,
                count(distinct hh_paid_in_current_fyear.prop_dtl_id) as collected_hh1,
                sum(hh_paid_in_current_fyear.paid_amount) as collection1 
                from property
                left join exempted on property.prop_dtl_id=exempted.prop_dtl_id
                left join (
                    select sum(amount-adjust_amt) as demand_amount,tbl_prop_demand.prop_dtl_id from tbl_prop_demand
                       join exempted on tbl_prop_demand.prop_dtl_id=exempted.prop_dtl_id
                       where tbl_prop_demand.status=1 group by tbl_prop_demand.prop_dtl_id
                ) exempted_demand on exempted_demand.prop_dtl_id=property.prop_dtl_id
                left join hh_paid_in_previous_fyear on hh_paid_in_previous_fyear.prop_dtl_id=property.prop_dtl_id
                left join hh_paid_in_current_fyear on hh_paid_in_current_fyear.prop_dtl_id=property.prop_dtl_id";
            

            $result = $this->db->query($sql)->getRowArray();

            $response['ulb_name'] = 'RANCHI MUNICIPAL CORPORATION';
            $response['no_of_hh'] = $result['no_of_hh'];
            $response['demand_outstaning'] = round($result['demand_amount']/100000, 2);
            $response['no_of_hh_exempt'] = $result['exempted'];
            $response['demand'] = round($result['exempt_demand']/100000, 2);
            $response['balance'] = $result['no_of_hh']-$result['exempted'];
            $response['balance_demand'] = round(($result['demand_amount']-$result['exempt_demand'])/100000, 2);
            $response['hh_paid_upto_2023'] = $result['collected_hh']+$result['collected_hh1'];
            $response['amount_paid_upto_2023'] = round(($result['collection']+$result['collection1'])/100000, 2);
            $response['remaining_hhs'] = ($result['no_of_hh']-$result['exempted'])-($result['collected_hh']+$result['collected_hh1']);
            $response['due'] = round(($result['demand_amount']-$result['exempt_demand']-($result['collection']+$result['collected_hh1']))/100000, 2);

            $res['data'] = $response;
            //print_var($data);die();
            return view('property/reports/collection_mpl_report', $res);
            
        }
        catch (Exception $e) {
            flashToast("message", $e->getMessage());
        }
    }


    public function oneTimePaymentSaf()
    {
        $currentFY = getFY();
        $currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$currentFY])['id'];
        $fyear = explode('-',$currentFY);
                    
        $fromYear = $fyear[0].'-04-01';
        $uptoYear = $fyear[1].'-03-31';

        try{
            $sql = "SELECT count(property.prop_dtl_id) as no_of_hh,sum(tbl_prop_demand.demand_amount) as demand_amount
                FROM (
                    SELECT tbl_prop_dtl.id as prop_dtl_id from tbl_prop_dtl
                    left join (select prop_dtl_id from tbl_prop_demand where status=1 and paid_status=1 group by prop_dtl_id) tbl_prop_demand on tbl_prop_demand.prop_dtl_id=tbl_prop_dtl.id
                    where tbl_prop_demand.prop_dtl_id is null and char_length(tbl_prop_dtl.new_holding_no)>0 and tbl_prop_dtl.status=1 and tbl_prop_dtl.govt_saf_dtl_id is null
                ) as property
                left join(select sum(amount-adjust_amt) as demand_amount,prop_dtl_id from tbl_prop_demand where status=1 and paid_status=0 and fyear<='".$currentFY."' group by prop_dtl_id) tbl_prop_demand on tbl_prop_demand.prop_dtl_id=property.prop_dtl_id
                ";
            $result = $this->db->query($sql)->getRowArray();

            $sql1 = "SELECT count(property.prop_dtl_id) as no_of_hh_untrace,sum(tbl_prop_demand.demand_amount) as demand_amount_untrace
                FROM (
                    SELECT tbl_prop_dtl.id as prop_dtl_id from tbl_prop_dtl
                    left join (select prop_dtl_id from tbl_prop_demand where status=1 and paid_status=1 group by prop_dtl_id) tbl_prop_demand on tbl_prop_demand.prop_dtl_id=tbl_prop_dtl.id
                    where tbl_prop_demand.prop_dtl_id is null and char_length(tbl_prop_dtl.new_holding_no)>0 and tbl_prop_dtl.status=1 and tbl_prop_dtl.govt_saf_dtl_id is null and tbl_prop_dtl.prop_type_mstr_id=4
                ) as property
                left join(select sum(amount-adjust_amt) as demand_amount,prop_dtl_id from tbl_prop_demand where status=1 and paid_status=0 and fyear<='".$currentFY."' group by prop_dtl_id) tbl_prop_demand on tbl_prop_demand.prop_dtl_id=property.prop_dtl_id
                ";
            $result1 = $this->db->query($sql1)->getRowArray();

            $response['ulb_name'] = 'RANCHI MUNICIPAL CORPORATION';
            $response['no_of_hh'] = $result['no_of_hh'];
            $response['demand'] = round($result['demand_amount']/100000);
            $response['no_of_hh_untraced'] = $result1['no_of_hh_untrace'];
            $response['demand_untraced'] = round($result1['demand_amount_untrace']/100000);
            $response['no_of_hh_traced'] = $result['no_of_hh']-$result1['no_of_hh_untrace'];
            $response['demand_traced'] = round(($result['demand_amount']-$result1['demand_amount_untrace'])/100000);
            
            $res['data'] = $response;
            //print_var($data);die();
            return view('property/reports/oneTime_payment_in_saf', $res);
            
        }
        catch (Exception $e) {
            flashToast("message", $e->getMessage());
        }
    }

    public function noticeArrearCovered()
    {
        $currentFY = getFY();
        $currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$currentFY])['id'];
        $fyear = explode('-',$currentFY);
                    
        $fromYear = $fyear[0].'-04-01';
        $uptoYear = $fyear[1].'-03-31';

        try{
            $data = array();
            $residential_sql = "with property as(
                                SELECT 
                                    count(distinct tbl_prop_dtl.id) as no_of_hh,
                                    SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) AS arrear_demand
                                FROM tbl_prop_demand
                                INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                                WHERE 
                                    tbl_prop_dtl.status=1  
                                    AND tbl_prop_demand.status=1 
                                    AND tbl_prop_demand.paid_status=0 and char_length(tbl_prop_dtl.new_holding_no)>0 and tbl_prop_demand.due_date is not null and tbl_prop_demand.fy_mstr_id<=52
                                    AND tbl_prop_dtl.holding_type='PURE_RESIDENTIAL' and tbl_prop_dtl.govt_saf_dtl_id is null
                            ),
                            notice_served as (
                                SELECT count(distinct tbl_prop_notices.prop_dtl_id) as no_of_hh, sum(demand_amount) as demand_amount from tbl_prop_notices
                                JOIN tbl_prop_dtl on tbl_prop_notices.prop_dtl_id=tbl_prop_dtl.id
                                WHERE tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 AND tbl_prop_dtl.holding_type='PURE_RESIDENTIAL'
                                AND tbl_prop_notices.notice_type='Demand' and tbl_prop_notices.status=1 and tbl_prop_dtl.govt_saf_dtl_id is null
                            ),
                            notice_recovered as (
                                SELECT count(distinct tbl_prop_demand.prop_dtl_id) as no_of_hh, sum(tbl_prop_demand.amount-tbl_prop_demand.adjust_amt) as demand_amount from tbl_prop_notices
                                JOIN tbl_prop_dtl on tbl_prop_notices.prop_dtl_id=tbl_prop_dtl.id
                                JOIN tbl_prop_demand on tbl_prop_demand.prop_dtl_id=tbl_prop_notices.prop_dtl_id and (tbl_prop_notices.from_fyear=tbl_prop_demand.fyear or tbl_prop_notices.upto_fyear=tbl_prop_demand.fyear)
                                WHERE tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 AND tbl_prop_dtl.holding_type='PURE_RESIDENTIAL'
                                AND tbl_prop_notices.notice_type='Demand' and tbl_prop_notices.status=1 and tbl_prop_demand.status=1 and tbl_prop_demand.paid_status=1 and tbl_prop_dtl.govt_saf_dtl_id is null
                            )
                            SELECT property.no_of_hh as holding_hh,property.arrear_demand as arrear_demand,
                            notice_served.no_of_hh as notice_hh,notice_served.demand_amount as notice_demand,
                            notice_recovered.no_of_hh as recovered_hh, notice_recovered.demand_amount as recovered_demand
                            FROM property,notice_served,notice_recovered";
            $residential_result = $this->db->query($residential_sql)->getResultArray();


            $non_residential_sql = "with property as(
                                    SELECT 
                                        count(distinct tbl_prop_dtl.id) as no_of_hh,
                                        SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) AS arrear_demand
                                    FROM tbl_prop_demand
                                    INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                                    WHERE 
                                        tbl_prop_dtl.status=1  
                                        AND tbl_prop_demand.status=1 
                                        AND tbl_prop_demand.paid_status=0 and char_length(tbl_prop_dtl.new_holding_no)>0 and tbl_prop_demand.due_date is not null and tbl_prop_demand.fy_mstr_id<=52
                                        AND tbl_prop_dtl.holding_type!='PURE_RESIDENTIAL' and tbl_prop_dtl.prop_type_mstr_id!=4 and tbl_prop_dtl.govt_saf_dtl_id is null
                                ),
                                notice_served as (
                                    SELECT count(distinct tbl_prop_notices.prop_dtl_id) as no_of_hh, sum(demand_amount) as demand_amount from tbl_prop_notices
                                    JOIN tbl_prop_dtl on tbl_prop_notices.prop_dtl_id=tbl_prop_dtl.id
                                    WHERE tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 AND tbl_prop_dtl.holding_type!='PURE_RESIDENTIAL' and tbl_prop_dtl.prop_type_mstr_id!=4
                                    AND tbl_prop_notices.notice_type='Demand' and tbl_prop_notices.status=1 and tbl_prop_dtl.govt_saf_dtl_id is null
                                ),
                                notice_recovered as (
                                    SELECT count(distinct tbl_prop_demand.prop_dtl_id) as no_of_hh, sum(tbl_prop_demand.amount-tbl_prop_demand.adjust_amt) as demand_amount from tbl_prop_notices
                                    JOIN tbl_prop_dtl on tbl_prop_notices.prop_dtl_id=tbl_prop_dtl.id
                                    JOIN tbl_prop_demand on tbl_prop_demand.prop_dtl_id=tbl_prop_notices.prop_dtl_id and (tbl_prop_notices.from_fyear=tbl_prop_demand.fyear or tbl_prop_notices.upto_fyear=tbl_prop_demand.fyear)
                                    WHERE tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 AND tbl_prop_dtl.holding_type!='PURE_RESIDENTIAL' and tbl_prop_dtl.prop_type_mstr_id!=4
                                    AND tbl_prop_notices.notice_type='Demand' and tbl_prop_notices.status=1 and tbl_prop_demand.status=1 and tbl_prop_demand.paid_status=1 and tbl_prop_dtl.govt_saf_dtl_id is null
                                )
                                SELECT property.no_of_hh as holding_hh,property.arrear_demand as arrear_demand,
                                notice_served.no_of_hh as notice_hh,notice_served.demand_amount as notice_demand,
                                notice_recovered.no_of_hh as recovered_hh, notice_recovered.demand_amount as recovered_demand
                                FROM property,notice_served,notice_recovered";
            $non_residential_result = $this->db->query($non_residential_sql)->getResultArray();

            $vaccant_sql = "with property as(
                            SELECT 
                                count(distinct tbl_prop_dtl.id) as no_of_hh,
                                SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) AS arrear_demand
                            FROM tbl_prop_demand
                            INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                            WHERE 
                                tbl_prop_dtl.status=1  
                                AND tbl_prop_demand.status=1 
                                AND tbl_prop_demand.paid_status=0 and char_length(tbl_prop_dtl.new_holding_no)>0 and tbl_prop_demand.due_date is not null and tbl_prop_demand.fy_mstr_id<=52
                                AND tbl_prop_dtl.prop_type_mstr_id=4 and tbl_prop_dtl.govt_saf_dtl_id is null
                        ),
                        notice_served as (
                            SELECT count(distinct tbl_prop_notices.prop_dtl_id) as no_of_hh, sum(demand_amount) as demand_amount from tbl_prop_notices
                            JOIN tbl_prop_dtl on tbl_prop_notices.prop_dtl_id=tbl_prop_dtl.id
                            WHERE tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 AND tbl_prop_dtl.prop_type_mstr_id=4
                            AND tbl_prop_notices.notice_type='Demand' and tbl_prop_notices.status=1 and tbl_prop_dtl.govt_saf_dtl_id is null
                        ),
                        notice_recovered as (
                            SELECT count(distinct tbl_prop_demand.prop_dtl_id) as no_of_hh, sum(tbl_prop_demand.amount-tbl_prop_demand.adjust_amt) as demand_amount from tbl_prop_notices
                            JOIN tbl_prop_dtl on tbl_prop_notices.prop_dtl_id=tbl_prop_dtl.id
                            JOIN tbl_prop_demand on tbl_prop_demand.prop_dtl_id=tbl_prop_notices.prop_dtl_id and (tbl_prop_notices.from_fyear=tbl_prop_demand.fyear or tbl_prop_notices.upto_fyear=tbl_prop_demand.fyear)
                            WHERE tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 AND tbl_prop_dtl.prop_type_mstr_id=4
                            AND tbl_prop_notices.notice_type='Demand' and tbl_prop_notices.status=1 and tbl_prop_demand.status=1 and tbl_prop_demand.paid_status=1 and tbl_prop_dtl.govt_saf_dtl_id is null
                        )
                        SELECT property.no_of_hh as holding_hh,property.arrear_demand as arrear_demand,
                        notice_served.no_of_hh as notice_hh,notice_served.demand_amount as notice_demand,
                        notice_recovered.no_of_hh as recovered_hh, notice_recovered.demand_amount as recovered_demand
                        FROM property,notice_served,notice_recovered";
            $vaccant_result = $this->db->query($vaccant_sql)->getResultArray();
            
            $response['residential'] = $residential_result;
            $response['non_residential'] = $non_residential_result;
            $response['vacant_land'] = $vaccant_result;
            
            $res['data'] = $response;
            //print_var($data);die();
            return view('property/reports/arrear_notice_covered', $res);
            
        }
        catch (Exception $e) {
            flashToast("message", $e->getMessage());
        }
    }

    public function govtsaf_legacy()
    {
        $currentFY = getFY();
        $currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$currentFY])['id'];
        $fyear = explode('-',$currentFY);
                    
        $fromYear = $fyear[0].'-04-01';
        $uptoYear = $fyear[1].'-03-31';

        try{
            $data = array();
            $sql_assd = "SELECT count(tbl_govt_lagecy_property.id) as govt_lagecy,sum(demand) as demand_amount from tbl_govt_lagecy_property
            join tbl_prop_dtl on tbl_govt_lagecy_property.prop_dtl_id=tbl_prop_dtl.id
            join (select sum(amount-adjust_amt) as demand,prop_dtl_id from tbl_prop_demand where status=1 and paid_status=0 group by prop_dtl_id) tbl_prop_demand  on tbl_govt_lagecy_property.prop_dtl_id = tbl_prop_demand.prop_dtl_id
            where tbl_govt_lagecy_property.status=1 and tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)=0";
            $result = $this->db->query($sql_assd)->getFirstRow("array");
            
            $response['ulb_name'] = 'RANCHI MUNICIPAL CORPORATION';
            $response['gb_saf_hh'] = $result['govt_lagecy']??0;
            $response['gb_demand'] = round($result['demand_amount']/100000)??0;
            
            
            $res['data'] = $response;
            
            return view('property/reports/govt_hh_report', $res);
            
        }
        catch (Exception $e) {
            flashToast("message", $e->getMessage());
        }

    }

}