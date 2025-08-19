<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Controllers\Reports\PropReports;
use App\Models\model_ward_mstr;
use App\Models\model_ward_permission;
use App\Models\model_view_emp_details;
use App\Models\model_fy_mstr;
use App\Models\model_tran_mode_mstr;
use App\Models\model_datatable;
use App\Models\model_prop_type_mstr;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Exception;

//include APPPATH . './Libraries/phpoffice/autoload.php';

class EODCB extends AlphaController
{
	protected $db;
	protected $dbSystem;
    protected $PropReports;
    protected $model_ward_mstr;
	protected $model_ward_permission;
    protected $model_view_emp_details;
    protected $model_fy_mstr;
    protected $model_tran_mode_mstr;
    protected $model_datatable;
	protected $model_prop_type_mstr;

    public function __construct()
    {
        ini_set('memory_limit', '-1');
        error_reporting(-1);
        parent::__construct();
    	helper(['url', 'db_helper', 'validation_helper', 'validate_saf_helper', 'sms_helper', 'php_office_helper', 'utility_helper']);
        if($db_name = dbConfig("property")){  $this->db = db_connect($db_name); }
        if ($db_name = dbSystem()) { $this->dbSystem = db_connect($db_name); }
        /* $this->db = db_connect('db_rmc_property');
        $this->dbSystem = db_connect('db_system');  */
        $this->PropReports = new PropReports();
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
		$this->model_ward_permission = new model_ward_permission($this->dbSystem);
        $this->model_view_emp_details = new model_view_emp_details($this->dbSystem);
        $this->model_fy_mstr = new model_fy_mstr($this->dbSystem);
        $this->model_tran_mode_mstr = new model_tran_mode_mstr($this->db);
        $this->model_datatable = new model_datatable($this->db);
		$this->model_prop_type_mstr = new model_prop_type_mstr($this->db);
        helper('form_helper');
    }

    function __destruct() {
		$this->db->close();
		$this->dbSystem->close();
	}


    /* public function eoDCB_old()
    {
        $currentFY = getFY();
        $currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$currentFY])['id'];
        $fyList = $this->model_fy_mstr->getFYListDescBefore10Year($currentFyID);

        $previous_year = $currentFyID-1;

        //try {
            $data['report_list'] = array();
            for($i=$previous_year-4; $i<=$previous_year; $i++)
            {
                $addholding = ($i==49)?'+1520':'';
                $WhereFYCurrent = " AND fy_mstr_id=".$i;
                $WhereFYArrear = " AND tbl_prop_demand.fy_mstr_id<".$i;
                $fyear = explode('-',$this->model_fy_mstr->getFyearByFyid($i)['fy']);
                
                $fromYear = $fyear[0].'-04-01';
                $uptoYear = $fyear[1].'-03-31';

                $sql = "WITH ARREAR_DEMAND AS (
                    SELECT 
                        SUM(tbl_prop_demand.amount - tbl_prop_demand.adjust_amt) AS arrear_demand
                    FROM tbl_prop_demand
                    INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    WHERE 
                        tbl_prop_dtl.status=1
                        AND tbl_prop_demand.status=1 
                        AND tbl_prop_demand.paid_status=0 and char_length(tbl_prop_dtl.new_holding_no)>0 and tbl_prop_demand.due_date is not null ".$WhereFYArrear." 
                ),
                CURRENT_DEMAND AS (
                    SELECT 
                        count(tbl_prop_dtl.id) AS current_holding,
                        SUM(tbl_prop_demand.amount - tbl_prop_demand.adjust_amt) AS current_demand
                    FROM tbl_prop_dtl
                    INNER JOIN (
                        select prop_dtl_id, sum(amount) as amount, sum(adjust_amt) as adjust_amt from tbl_prop_demand where tbl_prop_demand.status=1 
                        AND tbl_prop_demand.paid_status IN (0,1) and tbl_prop_demand.due_date is not null  ".$WhereFYCurrent." 
                        group by prop_dtl_id
                    ) tbl_prop_demand ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    WHERE tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0  


                ),
                ARREAR_COLLECTION AS (
                    SELECT 
                        SUM(tbl_prop_demand.amount - tbl_prop_demand.adjust_amt) AS arrear_collection_amount
                    FROM tbl_prop_demand
                    INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    INNER JOIN tbl_collection on tbl_collection.prop_demand_id=tbl_prop_demand.id AND (tbl_collection.created_on::date between '".$fromYear."' and '".$uptoYear."') 
                    WHERE tbl_prop_dtl.status=1  and tbl_prop_demand.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 ".$WhereFYArrear." AND tbl_prop_demand.paid_status=1
                ),
                CURRENT_COLLECTION AS (
                    SELECT 
                        count(tbl_prop_dtl.id) AS collection_from_no_of_HH,
                        SUM(tbl_prop_demand.amount - tbl_prop_demand.adjust_amt) AS current_collection_amount
                    FROM tbl_prop_dtl
                    INNER JOIN (
                        select prop_dtl_id, sum(amount) as amount, sum(adjust_amt) as adjust_amt from tbl_prop_demand where tbl_prop_demand.status=1 
                        AND tbl_prop_demand.paid_status=1 AND tbl_prop_demand.status=1 ".$WhereFYCurrent." 
                        group by prop_dtl_id
                    ) tbl_prop_demand ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    WHERE tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 
                ),
                PROPERTY_MAPPED AS (
                    SELECT count(tbl_prop_dtl.id)".$addholding." as prop_mapped FROM tbl_prop_dtl 
                    JOIN (select geotag_dtl_id from tbl_saf_geotag_upload_dtl where status=1 and created_on::date between '".$fromYear."' and '".$uptoYear."' group by geotag_dtl_id) tbl_saf_doc_dtl on tbl_prop_dtl.saf_dtl_id=tbl_saf_doc_dtl.geotag_dtl_id
                    where tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0
                ),
                EXAMPTED_PROPERTY AS (
                    select count(tbl_prop_dtl.id) as exampted_prop FROM tbl_prop_dtl
                    JOIN (SELECT sum(builtup_area) as builtup_area,prop_dtl_id FROM tbl_prop_floor_details where  status=1 group by prop_dtl_id having sum(builtup_area) <=350) floor ON floor.prop_dtl_id=tbl_prop_dtl.id
                    JOIN (SELECT prop_dtl_id FROM tbl_prop_demand where amount>0 and status=1 ".$WhereFYCurrent." group by prop_dtl_id ) tbl_prop_demand on tbl_prop_demand.prop_dtl_id=tbl_prop_dtl.id
                    where floor.builtup_area>0 and tbl_prop_dtl.status=1  and char_length(tbl_prop_dtl.new_holding_no)>0
                ),
                PROPERTY_TAX_DEMANDED AS (
                    SELECT count(tbl_prop_dtl.id) as prop_tax_demanded from tbl_prop_dtl
                    JOIN (SELECT prop_dtl_id FROM tbl_prop_demand where status=1 ".$WhereFYCurrent." group by prop_dtl_id) tbl_prop_demand on tbl_prop_demand.prop_dtl_id=tbl_prop_dtl.id
                    WHERE tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0
                ),
                PROPERTY_TAX_Collected AS (
                    SELECT count(tbl_prop_dtl.id) as prop_tax_colleted from tbl_prop_dtl
                    JOIN (SELECT prop_dtl_id FROM tbl_prop_demand where status=1 and paid_status=1 ".$WhereFYCurrent." group by prop_dtl_id) tbl_prop_demand on tbl_prop_demand.prop_dtl_id=tbl_prop_dtl.id
                    WHERE tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0
                ),
                RESIDENTIAL_PROPERTY AS (
                    SELECT count(tbl_prop_dtl.id) demand_holding,sum(demand_amount) as demand_tax,count(distinct tbl_prop_demand1.prop_dtl_id) as collection_holding,sum(collected_amount) as collected_amount from tbl_prop_dtl 
                    JOIN (select sum(amount-adjust_amt) as demand_amount,prop_dtl_id from tbl_prop_demand where status=1 ".$WhereFYCurrent." group by prop_dtl_id) tbl_prop_demand on tbl_prop_demand.prop_dtl_id=tbl_prop_dtl.id
                    LEFT JOIN (select sum(amount-adjust_amt) as collected_amount,prop_dtl_id from tbl_prop_demand where status=1 and paid_status=1 ".$WhereFYCurrent." group by prop_dtl_id) tbl_prop_demand1 on tbl_prop_demand1.prop_dtl_id=tbl_prop_dtl.id
                    WHERE tbl_prop_dtl.holding_type='PURE_RESIDENTIAL' and tbl_prop_dtl.status=1 and new_holding_no is not null
                ),
                COMMERCIAL_PROPERTY AS (
                    SELECT count(tbl_prop_dtl.id) demand_holding,sum(demand_amount) as demand_tax,count(distinct tbl_prop_demand1.prop_dtl_id) as collection_holding,sum(collected_amount) as collected_amount from tbl_prop_dtl 
                    JOIN (select sum(amount-adjust_amt) as demand_amount,prop_dtl_id from tbl_prop_demand where status=1 ".$WhereFYCurrent." group by prop_dtl_id) tbl_prop_demand on tbl_prop_demand.prop_dtl_id=tbl_prop_dtl.id
                    LEFT JOIN (select sum(amount-adjust_amt) as collected_amount,prop_dtl_id from tbl_prop_demand where status=1 and paid_status=1 ".$WhereFYCurrent." group by prop_dtl_id) tbl_prop_demand1 on tbl_prop_demand1.prop_dtl_id=tbl_prop_dtl.id
                    WHERE (tbl_prop_dtl.holding_type='PURE_COMMERCIAL' or tbl_prop_dtl.holding_type='MIX_COMMERCIAL') and tbl_prop_dtl.status=1 and new_holding_no is not null
                ),
                GOVERNMENT_PROPERTY AS (
                    SELECT count(tbl_govt_saf_dtl.id) demand_holding,sum(demand_amount) as demand_tax,count(distinct tbl_govt_saf_demand_dtl1.govt_saf_dtl_id) as collection_holding,sum(collected_amount) as collected_amount from tbl_govt_saf_dtl 
                    JOIN (select sum(amount-adjust_amount) as demand_amount,govt_saf_dtl_id from tbl_govt_saf_demand_dtl  where status=1 ".$WhereFYCurrent." group by govt_saf_dtl_id) tbl_govt_saf_demand_dtl on tbl_govt_saf_demand_dtl.govt_saf_dtl_id=tbl_govt_saf_dtl.id
                    LEFT JOIN (select sum(amount-adjust_amount) as collected_amount,govt_saf_dtl_id from tbl_govt_saf_demand_dtl where status=1 and paid_status=1 ".$WhereFYCurrent." group by govt_saf_dtl_id) tbl_govt_saf_demand_dtl1 on tbl_govt_saf_demand_dtl1.govt_saf_dtl_id=tbl_govt_saf_dtl.id
                    WHERE tbl_govt_saf_dtl.status=1 
                ),
                INSTITUTIONAL_PROPERTY AS (
                    SELECT count(tbl_prop_dtl.id) demand_holding,sum(demand_amount) as demand_tax,count(distinct tbl_prop_demand1.prop_dtl_id) as collection_holding,sum(collected_amount) as collected_amount from tbl_prop_dtl 
                    JOIN (select prop_dtl_id from tbl_prop_floor_details WHERE usage_type_mstr_id in(8,10,12,42,43) and status=1 group by prop_dtl_id) floor1 ON floor1.prop_dtl_id=tbl_prop_dtl.id 
                    JOIN (select sum(amount-adjust_amt) as demand_amount,prop_dtl_id from tbl_prop_demand WHERE status=1 ".$WhereFYCurrent." group by prop_dtl_id) tbl_prop_demand on tbl_prop_demand.prop_dtl_id=tbl_prop_dtl.id
                    JOIN tbl_saf_dtl on tbl_prop_dtl.saf_dtl_id=tbl_saf_dtl.id and tbl_saf_dtl.status=1 and tbl_saf_dtl.trust_type is not null
                    LEFT JOIN (select sum(amount-adjust_amt) as collected_amount,prop_dtl_id from tbl_prop_demand where status=1 and paid_status=1 ".$WhereFYCurrent." group by prop_dtl_id) tbl_prop_demand1 on tbl_prop_demand1.prop_dtl_id=tbl_prop_dtl.id
                    WHERE tbl_prop_dtl.status=1 and new_holding_no is not null
                ),
                TRUST_PROPERTY AS (
                    SELECT count(tbl_prop_dtl.id) demand_holding,sum(demand_amount) as demand_tax,count(distinct tbl_prop_demand1.prop_dtl_id) as collection_holding,sum(collected_amount) as collected_amount from tbl_prop_dtl 
                    JOIN (select prop_dtl_id from tbl_prop_floor_details WHERE usage_type_mstr_id in(12,43) and status=1 group by prop_dtl_id) floor1 ON floor1.prop_dtl_id=tbl_prop_dtl.id 
                    JOIN (select sum(amount-adjust_amt) as demand_amount,prop_dtl_id from tbl_prop_demand WHERE status=1 ".$WhereFYCurrent." group by prop_dtl_id) tbl_prop_demand on tbl_prop_demand.prop_dtl_id=tbl_prop_dtl.id
                    JOIN tbl_saf_dtl on tbl_prop_dtl.saf_dtl_id=tbl_saf_dtl.id and tbl_saf_dtl.status=1 and tbl_saf_dtl.trust_type is not null
                    LEFT JOIN (select sum(amount-adjust_amt) as collected_amount,prop_dtl_id from tbl_prop_demand where status=1 and paid_status=1 ".$WhereFYCurrent." group by prop_dtl_id) tbl_prop_demand1 on tbl_prop_demand1.prop_dtl_id=tbl_prop_dtl.id
                    WHERE tbl_prop_dtl.status=1 and new_holding_no is not null
                ),
                ONLINE AS (
                    SELECT count(tbl_prop_dtl.id) as prop_count,sum(collection_amount) as collection_amount from tbl_prop_dtl
                    JOIN tbl_transaction on tbl_transaction.prop_dtl_id=tbl_prop_dtl.id and tbl_transaction.tran_type='Property' and tbl_transaction.tran_mode='ONLINE' and tbl_transaction.status=1
                    JOIN (select transaction_id,sum(amount) as collection_amount from tbl_collection where status=1 ".$WhereFYCurrent." group by transaction_id) tbl_collection 
                    on tbl_collection.transaction_id=tbl_transaction.id and char_length(tbl_prop_dtl.new_holding_no)>0
                )
                
                SELECT 
                    ROUND(COALESCE(ARREAR_DEMAND.arrear_demand, 0)) AS arrear_demand,
                    ROUND(COALESCE(CURRENT_DEMAND.current_demand, 0)) AS current_demand,
                    ROUND(COALESCE(ARREAR_DEMAND.arrear_demand, 0)+COALESCE(CURRENT_DEMAND.current_demand, 0)) AS total_demand,
                    ROUND(COALESCE(ARREAR_COLLECTION.arrear_collection_amount, 0)) AS arrear_collection_amount,
                    ROUND(COALESCE(CURRENT_COLLECTION.current_collection_amount, 0)) AS current_collection_amount,
                    ROUND(COALESCE(ARREAR_COLLECTION.arrear_collection_amount, 0)+COALESCE(CURRENT_COLLECTION.current_collection_amount, 0)) AS total_collection_amount,
                    PROPERTY_MAPPED.prop_mapped,
                    EXAMPTED_PROPERTY.exampted_prop,
                    PROPERTY_TAX_DEMANDED.prop_tax_demanded,
                    PROPERTY_TAX_Collected.prop_tax_colleted,
                    ROUND(RESIDENTIAL_PROPERTY.demand_holding) as residential_property_demand,
                    ROUND(RESIDENTIAL_PROPERTY.demand_tax) as residential_demand,
                    ROUND(RESIDENTIAL_PROPERTY.collection_holding) as residential_property_collection,
                    ROUND(RESIDENTIAL_PROPERTY.collected_amount) as residential_collection,
                    ROUND(COMMERCIAL_PROPERTY.demand_holding) as commercial_property_demand,
                    ROUND(COMMERCIAL_PROPERTY.demand_tax) as commercial_demand,
                    ROUND(COMMERCIAL_PROPERTY.collection_holding) as commercial_property_collection,
                    ROUND(COMMERCIAL_PROPERTY.collected_amount) as commercial_collection,
                    ROUND(GOVERNMENT_PROPERTY.demand_holding) as gov_property_demand,
                    ROUND(GOVERNMENT_PROPERTY.demand_tax) as gov_demand,
                    ROUND(GOVERNMENT_PROPERTY.collection_holding) as gov_property_collection,
                    ROUND(GOVERNMENT_PROPERTY.collected_amount) as gov_collection,
                    ROUND(INSTITUTIONAL_PROPERTY.demand_holding) as institutional_property_demand,
                    ROUND(INSTITUTIONAL_PROPERTY.demand_tax) as institutional_demand,
                    ROUND(INSTITUTIONAL_PROPERTY.collection_holding) as institutional_property_collection,
                    ROUND(INSTITUTIONAL_PROPERTY.collected_amount) as institutional_collection,
                    ROUND(TRUST_PROPERTY.demand_holding) as trust_property_demand,
                    ROUND(TRUST_PROPERTY.demand_tax) as trust_demand,
                    ROUND(TRUST_PROPERTY.collection_holding) as trust_property_collection,
                    ROUND(TRUST_PROPERTY.collected_amount) as trust_collection,
                    ROUND(ONLINE.prop_count) as online_prop_count,
                    ROUND(ONLINE.collection_amount) as online_collection
                FROM ARREAR_DEMAND,CURRENT_DEMAND,ARREAR_COLLECTION,CURRENT_COLLECTION,PROPERTY_MAPPED,EXAMPTED_PROPERTY,PROPERTY_TAX_DEMANDED,PROPERTY_TAX_Collected,RESIDENTIAL_PROPERTY,COMMERCIAL_PROPERTY,GOVERNMENT_PROPERTY,INSTITUTIONAL_PROPERTY,TRUST_PROPERTY,ONLINE
                ";
                //print_var($sql);
                $builder = $this->db->query($sql);
                if ($report_list = $builder->getResultArray()) {
                    //$data['report_list'] = $report_list;
                    $data['report_list'][$this->model_fy_mstr->getFiyrByid($i)['fy']] = $report_list[0];
                }
            }
            //echo $this->db->getLastQuery();
        // }catch(Exception $e){
        //     print_r($e);
        // }

        return view('property/reports/eo_dcb_report', $data);
    } */

	public function eoDCB()
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $currentFY = getFY();
        $currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$currentFY])['id'];
        $fyList = $this->model_fy_mstr->getFYListDescBefore10Year($currentFyID);

        $previous_year = $currentFyID-1;

        //try {
            $data['report_list'] = array();
            for($i=$previous_year-4; $i<=$previous_year; $i++)
            {
                $addholding = ($i==49)?'+1520':'';
                $Where22_23 ="";
                $propDemand = "";
                if($i==53) #  for current demand
                {

                    $Where22_23 =" AND tbl_prop_demand.fy_mstr_id=54";
                    $propDemand = "prop_demand as(
                                        select SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) as arrear_demand,tbl_prop_demand.prop_dtl_id from tbl_prop_demand
                                        INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                                        where tbl_prop_demand.status=1 and tbl_prop_demand.fy_mstr_id=53 and tbl_prop_dtl.status=1 and tbl_prop_demand.status=1 
                                        and char_length(tbl_prop_dtl.new_holding_no)>0 
                                        group by tbl_prop_demand.prop_dtl_id
                                    ),";
                }
                elseif($i==54) #for  arrear Demand
                {
                    $Where22_23 ="";
                    $propDemand = "prop_demand as(
                        tbl_prop_demand.prop_dtl_id,
                        select SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) as arrear_demand
                        from tbl_prop_demand
                        INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                        where tbl_prop_demand.status=1 and tbl_prop_demand.fy_mstr_id=54 and tbl_prop_dtl.status=1 and tbl_prop_demand.status=1 
                        and char_length(tbl_prop_dtl.new_holding_no)>0 
                        group by tbl_prop_demand.prop_dtl_id
                    ),";
                }
                $WhereFYCurrent = " AND fy_mstr_id=".$i;
                $WhereFYArrear = " AND tbl_prop_demand.fy_mstr_id<".$i;
                $fyear = explode('-',$this->model_fy_mstr->getFyearByFyid($i)['fy']);
                
                $fromYear = $fyear[0].'-04-01';
                $uptoYear = $fyear[1].'-03-31';

                $AREA_DEMAND = "ARREAR_DEMAND AS (
                    SELECT 
                        SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) AS arrear_demand
                    FROM tbl_prop_demand
                    INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    WHERE 
                        tbl_prop_dtl.status=1 
                        AND tbl_prop_demand.status=1 
                        AND tbl_prop_demand.paid_status IN(0,1) and char_length(tbl_prop_dtl.new_holding_no)>0 and tbl_prop_demand.due_date is not null ".$WhereFYArrear." 
                ),";
                if($i==54)
                {
                    $WhereFYArrear2 = " AND tbl_prop_demand.fy_mstr_id<".($i-1);
                  
                    $AREA_DEMAND = "ARREAR_DEMAND AS (
                        SELECT 
                            SUM(curent.arrear_demand-(curent.arrear_demand*2.98/100)) arrear_demand 
                        FROM (
                            SELECT 
                                SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) AS arrear_demand
                            FROM tbl_prop_demand
                            INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                            WHERE 
                                tbl_prop_dtl.status=1 
                                AND tbl_prop_demand.status=1 
                                AND tbl_prop_demand.paid_status IN(0,1) and char_length(tbl_prop_dtl.new_holding_no)>0 and tbl_prop_demand.due_date is not null ".$WhereFYArrear2." 
                            )curent
                    ),";
                }
                $fromYear2 = $fromYear;
                if($i==54){$fromYear2 = ($fyear[0]-1).'-04-01';}
                $sql = "WITH PREV_COLLECTION AS (
                    SELECT 
                        SUM(COALESCE(tbl_collection.amount, 0)) AS arrear_collection_amount
                    FROM tbl_prop_dtl
                    INNER JOIN tbl_collection on tbl_collection.prop_dtl_id=tbl_prop_dtl.id AND tbl_collection.created_on::date < '".$fromYear2."'
                    WHERE tbl_prop_dtl.status=1 and tbl_collection.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 
                ),".($propDemand?
                $propDemand:"")."".$AREA_DEMAND."
                CURRENT_DEMAND AS (
                    SELECT 
                        count(tbl_prop_dtl.id) AS current_holding,
                        SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) AS current_demand
                    FROM tbl_prop_dtl
                    INNER JOIN (
                        select prop_dtl_id, sum(amount) as amount, sum(adjust_amt) as adjust_amt from tbl_prop_demand where tbl_prop_demand.status=1 
                        AND tbl_prop_demand.paid_status IN (0,1) and tbl_prop_demand.due_date is not null  ".(isset($i) && $i!=53?$WhereFYCurrent:$Where22_23)." 
                        group by prop_dtl_id
                    ) tbl_prop_demand ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    ".(isset($i) && $i==53 ? " JOIN prop_demand on prop_demand.prop_dtl_id=tbl_prop_demand.prop_dtl_id":"")."
                    WHERE tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0   


                ),
                ARREAR_COLLECTION AS (
                    SELECT 
                        SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) AS arrear_collection_amount
                    FROM tbl_prop_demand
                    INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    INNER JOIN tbl_collection on tbl_collection.prop_demand_id=tbl_prop_demand.id AND (tbl_collection.created_on::date between '".$fromYear."' and '".$uptoYear."') 
                    WHERE tbl_prop_dtl.status=1 and tbl_prop_demand.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 ".$WhereFYArrear." AND tbl_prop_demand.paid_status=1
                ),
                CURRENT_COLLECTION AS (
                    SELECT 
                        count(tbl_prop_dtl.id) AS collection_from_no_of_HH,
                        SUM(tbl_prop_demand.amount - tbl_prop_demand.adjust_amt) AS current_collection_amount
                    FROM tbl_prop_dtl
                    INNER JOIN (
                        select tbl_prop_demand.prop_dtl_id, sum(tbl_prop_demand.amount) as amount, sum(tbl_prop_demand.adjust_amt) as adjust_amt from tbl_prop_demand 
                        INNER JOIN tbl_collection on tbl_collection.prop_demand_id=tbl_prop_demand.id AND (tbl_collection.created_on::date between '".$fromYear."' and '".$uptoYear."') 
                        where tbl_prop_demand.status=1 
                        AND tbl_prop_demand.paid_status=1 AND tbl_prop_demand.status=1 and tbl_prop_demand.fy_mstr_id=".$i." 
                        group by tbl_prop_demand.prop_dtl_id
                    ) tbl_prop_demand ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    
                    WHERE tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 
                ),
                PROPERTY_MAPPED AS (
                    SELECT count(tbl_prop_dtl.id)".$addholding." as prop_mapped FROM tbl_prop_dtl 
                    where tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 and tbl_prop_dtl.created_on::date<='".$uptoYear."'
                ),
                EXAMPTED_PROPERTY AS (
                    select count(tbl_prop_dtl.id) as exampted_prop FROM tbl_prop_dtl
                    LEFT JOIN (SELECT prop_dtl_id FROM tbl_prop_floor_details where  status=1 and usage_type_mstr_id not in(11) group by prop_dtl_id) floor ON floor.prop_dtl_id=tbl_prop_dtl.id
                    where tbl_prop_dtl.status=1  and char_length(tbl_prop_dtl.new_holding_no)>0 
                    and tbl_prop_dtl.created_on::date<='".$uptoYear."' and floor.prop_dtl_id is null and tbl_prop_dtl.prop_type_mstr_id !=4
                ),
                PROPERTY_TAX_DEMANDED AS (
                    SELECT count(tbl_prop_dtl.id) as prop_tax_demanded from tbl_prop_dtl
                    JOIN (SELECT prop_dtl_id FROM tbl_prop_demand where status=1 ".$WhereFYCurrent." group by prop_dtl_id) tbl_prop_demand on tbl_prop_demand.prop_dtl_id=tbl_prop_dtl.id
                    WHERE tbl_prop_dtl.status=1  and char_length(tbl_prop_dtl.new_holding_no)>0
                ),
                PROPERTY_TAX_Collected AS (
                    SELECT count(tbl_prop_dtl.id) as prop_tax_colleted from tbl_prop_dtl
                    JOIN (SELECT tbl_prop_demand.prop_dtl_id FROM tbl_prop_demand 
                    INNER JOIN tbl_collection on tbl_collection.prop_demand_id=tbl_prop_demand.id AND (tbl_collection.created_on::date between '".$fromYear."' and '".$uptoYear."') 
                    where tbl_prop_demand.status=1 and tbl_prop_demand.paid_status=1 and tbl_prop_demand.fy_mstr_id=".$i." group by tbl_prop_demand.prop_dtl_id) tbl_prop_demand on tbl_prop_demand.prop_dtl_id=tbl_prop_dtl.id
                    WHERE tbl_prop_dtl.status=1  and char_length(tbl_prop_dtl.new_holding_no)>0
                ),
                RESIDENTIAL_PROPERTY AS (
                    SELECT count(tbl_prop_dtl.id) demand_holding,sum(demand_amount) as demand_tax,count(distinct tbl_prop_demand1.prop_dtl_id) as collection_holding,sum(collected_amount) as collected_amount from tbl_prop_dtl 
                    JOIN (select sum(amount-adjust_amt) as demand_amount,prop_dtl_id from tbl_prop_demand where status=1 ".$WhereFYCurrent." group by prop_dtl_id) tbl_prop_demand on tbl_prop_demand.prop_dtl_id=tbl_prop_dtl.id
                    LEFT JOIN (select sum(amount-adjust_amt) as collected_amount,prop_dtl_id from tbl_prop_demand where status=1 and paid_status=1 ".$WhereFYCurrent." group by prop_dtl_id) tbl_prop_demand1 on tbl_prop_demand1.prop_dtl_id=tbl_prop_dtl.id
                    WHERE tbl_prop_dtl.holding_type='PURE_RESIDENTIAL' and tbl_prop_dtl.status=1 and new_holding_no is not null
                ),
                COMMERCIAL_PROPERTY AS (
                    SELECT count(tbl_prop_dtl.id) demand_holding,sum(demand_amount) as demand_tax,count(distinct tbl_prop_demand1.prop_dtl_id) as collection_holding,sum(collected_amount) as collected_amount from tbl_prop_dtl 
                    JOIN (select sum(amount-adjust_amt) as demand_amount,prop_dtl_id from tbl_prop_demand where status=1 ".$WhereFYCurrent." group by prop_dtl_id) tbl_prop_demand on tbl_prop_demand.prop_dtl_id=tbl_prop_dtl.id
                    LEFT JOIN (select sum(amount-adjust_amt) as collected_amount,prop_dtl_id from tbl_prop_demand where status=1 and paid_status=1 ".$WhereFYCurrent." group by prop_dtl_id) tbl_prop_demand1 on tbl_prop_demand1.prop_dtl_id=tbl_prop_dtl.id
                    WHERE (tbl_prop_dtl.holding_type='PURE_COMMERCIAL' or tbl_prop_dtl.holding_type='MIX_COMMERCIAL') and tbl_prop_dtl.status=1 and new_holding_no is not null
                ),
                GOVERNMENT_PROPERTY AS (
                    SELECT count(tbl_govt_saf_dtl.id) demand_holding,sum(demand_amount) as demand_tax,count(distinct tbl_govt_saf_demand_dtl1.govt_saf_dtl_id) as collection_holding,sum(collected_amount) as collected_amount from tbl_govt_saf_dtl 
                    JOIN (select sum(amount-adjust_amount) as demand_amount,govt_saf_dtl_id from tbl_govt_saf_demand_dtl  where status=1 ".$WhereFYCurrent." group by govt_saf_dtl_id) tbl_govt_saf_demand_dtl on tbl_govt_saf_demand_dtl.govt_saf_dtl_id=tbl_govt_saf_dtl.id
                    LEFT JOIN (select sum(amount-adjust_amount) as collected_amount,govt_saf_dtl_id from tbl_govt_saf_demand_dtl where status=1 and paid_status=1 ".$WhereFYCurrent." group by govt_saf_dtl_id) tbl_govt_saf_demand_dtl1 on tbl_govt_saf_demand_dtl1.govt_saf_dtl_id=tbl_govt_saf_dtl.id
                    WHERE tbl_govt_saf_dtl.status=1 
                ),
                INSTITUTIONAL_PROPERTY AS (
                    SELECT count(tbl_prop_dtl.id) demand_holding,sum(demand_amount) as demand_tax,count(distinct tbl_prop_demand1.prop_dtl_id) as collection_holding,sum(collected_amount) as collected_amount from tbl_prop_dtl 
                    JOIN (select prop_dtl_id from tbl_prop_floor_details WHERE usage_type_mstr_id in(8,10,12,42,43) and status=1 group by prop_dtl_id) floor1 ON floor1.prop_dtl_id=tbl_prop_dtl.id 
                    JOIN (select sum(amount-adjust_amt) as demand_amount,prop_dtl_id from tbl_prop_demand WHERE status=1 ".$WhereFYCurrent." group by prop_dtl_id) tbl_prop_demand on tbl_prop_demand.prop_dtl_id=tbl_prop_dtl.id
                    JOIN tbl_saf_dtl on tbl_prop_dtl.saf_dtl_id=tbl_saf_dtl.id and tbl_saf_dtl.status=1 and tbl_saf_dtl.trust_type is not null
                    LEFT JOIN (select sum(amount-adjust_amt) as collected_amount,prop_dtl_id from tbl_prop_demand where status=1 and paid_status=1 ".$WhereFYCurrent." group by prop_dtl_id) tbl_prop_demand1 on tbl_prop_demand1.prop_dtl_id=tbl_prop_dtl.id
                    WHERE tbl_prop_dtl.status=1 and new_holding_no is not null
                ),
                TRUST_PROPERTY AS (
                    SELECT count(tbl_prop_dtl.id) demand_holding,sum(demand_amount) as demand_tax,count(distinct tbl_prop_demand1.prop_dtl_id) as collection_holding,sum(collected_amount) as collected_amount from tbl_prop_dtl 
                    JOIN (select prop_dtl_id from tbl_prop_floor_details WHERE usage_type_mstr_id in(12,43) and status=1 group by prop_dtl_id) floor1 ON floor1.prop_dtl_id=tbl_prop_dtl.id 
                    JOIN (select sum(amount-adjust_amt) as demand_amount,prop_dtl_id from tbl_prop_demand WHERE status=1 ".$WhereFYCurrent." group by prop_dtl_id) tbl_prop_demand on tbl_prop_demand.prop_dtl_id=tbl_prop_dtl.id
                    JOIN tbl_saf_dtl on tbl_prop_dtl.saf_dtl_id=tbl_saf_dtl.id and tbl_saf_dtl.status=1 and tbl_saf_dtl.trust_type is not null
                    LEFT JOIN (select sum(amount-adjust_amt) as collected_amount,prop_dtl_id from tbl_prop_demand where status=1 and paid_status=1 ".$WhereFYCurrent." group by prop_dtl_id) tbl_prop_demand1 on tbl_prop_demand1.prop_dtl_id=tbl_prop_dtl.id
                    WHERE tbl_prop_dtl.status=1 and new_holding_no is not null
                ),
                ONLINE AS (
                    SELECT count(tbl_prop_dtl.id) as prop_count,sum(collection_amount) as collection_amount from tbl_prop_dtl
                    JOIN tbl_transaction on tbl_transaction.prop_dtl_id=tbl_prop_dtl.id and tbl_transaction.tran_type='Property' and tbl_transaction.tran_mode='ONLINE' and tbl_transaction.status=1
                    JOIN (select transaction_id,sum(amount) as collection_amount from tbl_collection where status=1 ".$WhereFYCurrent." group by transaction_id) tbl_collection 
                    on tbl_collection.transaction_id=tbl_transaction.id
                )
                
                SELECT 
                    ROUND(CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0)>0 then COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0) else 0 end) AS arrear_demand,
                    ROUND(COALESCE(CURRENT_DEMAND.current_demand, 0)) AS current_demand,
                    ROUND((CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0)>0 then COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0) else 0 end)+COALESCE(CURRENT_DEMAND.current_demand, 0)) AS total_demand,
                    ROUND(COALESCE(ARREAR_COLLECTION.arrear_collection_amount, 0)) AS arrear_collection_amount,
                    ROUND(COALESCE(CURRENT_COLLECTION.current_collection_amount, 0)) AS current_collection_amount,
                    ROUND(COALESCE(ARREAR_COLLECTION.arrear_collection_amount, 0)+COALESCE(CURRENT_COLLECTION.current_collection_amount, 0)) AS total_collection_amount,
                    PROPERTY_MAPPED.prop_mapped,
                    EXAMPTED_PROPERTY.exampted_prop,
                    PROPERTY_TAX_DEMANDED.prop_tax_demanded,
                    PROPERTY_TAX_Collected.prop_tax_colleted,
                    ROUND(RESIDENTIAL_PROPERTY.demand_holding) as residential_property_demand,
                    ROUND(RESIDENTIAL_PROPERTY.demand_tax) as residential_demand,
                    ROUND(RESIDENTIAL_PROPERTY.collection_holding) as residential_property_collection,
                    ROUND(RESIDENTIAL_PROPERTY.collected_amount) as residential_collection,
                    ROUND(COMMERCIAL_PROPERTY.demand_holding) as commercial_property_demand,
                    ROUND(COMMERCIAL_PROPERTY.demand_tax) as commercial_demand,
                    ROUND(COMMERCIAL_PROPERTY.collection_holding) as commercial_property_collection,
                    ROUND(COMMERCIAL_PROPERTY.collected_amount) as commercial_collection,
                    ROUND(GOVERNMENT_PROPERTY.demand_holding) as gov_property_demand,
                    ROUND(GOVERNMENT_PROPERTY.demand_tax) as gov_demand,
                    ROUND(GOVERNMENT_PROPERTY.collection_holding) as gov_property_collection,
                    ROUND(GOVERNMENT_PROPERTY.collected_amount) as gov_collection,
                    ROUND(INSTITUTIONAL_PROPERTY.demand_holding) as institutional_property_demand,
                    ROUND(INSTITUTIONAL_PROPERTY.demand_tax) as institutional_demand,
                    ROUND(INSTITUTIONAL_PROPERTY.collection_holding) as institutional_property_collection,
                    ROUND(INSTITUTIONAL_PROPERTY.collected_amount) as institutional_collection,
                    ROUND(TRUST_PROPERTY.demand_holding) as trust_property_demand,
                    ROUND(TRUST_PROPERTY.demand_tax) as trust_demand,
                    ROUND(TRUST_PROPERTY.collection_holding) as trust_property_collection,
                    ROUND(TRUST_PROPERTY.collected_amount) as trust_collection,
                    ROUND(ONLINE.prop_count) as online_prop_count,
                    ROUND(ONLINE.collection_amount) as online_collection
                FROM ARREAR_DEMAND,CURRENT_DEMAND,ARREAR_COLLECTION,CURRENT_COLLECTION,PROPERTY_MAPPED,EXAMPTED_PROPERTY,PROPERTY_TAX_DEMANDED,PROPERTY_TAX_Collected,RESIDENTIAL_PROPERTY,COMMERCIAL_PROPERTY,GOVERNMENT_PROPERTY,INSTITUTIONAL_PROPERTY,TRUST_PROPERTY,ONLINE,PREV_COLLECTION
                ";
                //print_var($sql); die();
                $builder = $this->db->query($sql);
                if ($report_list = $builder->getResultArray()) {
                    //$data['report_list'] = $report_list;
                    $data['report_list'][$this->model_fy_mstr->getFiyrByid($i)['fy']] = $report_list[0];
                }
            }
            //echo $this->db->getLastQuery();
        // }catch(Exception $e){
        //     print_r($e);
        // }

        return view('property/reports/eo_dcb_report', $data);
    }
   
}

