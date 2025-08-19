<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\model_ward_permission;
use App\Models\model_view_emp_details;
use App\Models\model_fy_mstr;
use App\Models\model_tran_mode_mstr;
use App\Models\model_datatable;
use Exception;

//include APPPATH . './Libraries/phpoffice/autoload.php';

class safsamandgeotagging_report extends AlphaController
{
	protected $db;
	protected $dbSystem;
    protected $model_ward_mstr;
	protected $model_ward_permission;
    protected $model_view_emp_details;
    protected $model_fy_mstr;
    protected $model_tran_mode_mstr;
    protected $model_datatable;

    public function __construct(){
        ini_set('memory_limit', '-1');
        parent::__construct();
    	helper(['url', 'db_helper', 'validation_helper', 'validate_saf_helper', 'sms_helper', 'php_office_helper']);
        
        if($db_name = dbConfig("property")){  $this->db = db_connect($db_name); }
        if ($db_name = dbSystem()) { $this->dbSystem = db_connect($db_name); }
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
		$this->model_ward_permission = new model_ward_permission($this->dbSystem);
        $this->model_view_emp_details = new model_view_emp_details($this->dbSystem);
        $this->model_fy_mstr = new model_fy_mstr($this->dbSystem);
        $this->model_tran_mode_mstr = new model_tran_mode_mstr($this->db);
        $this->model_datatable = new model_datatable($this->db);
    }

    function __destruct() {
		$this->db->close();
		$this->dbSystem->close();
	}
	
	
  
    /*public function safSamAndGeotagging()
    {
        $session = session();
        $ulb_mstr_id = $session->get('ulb_dtl')['ulb_mstr_id'];
        $data = ['from_date'=>date('Y-m-d'), 'upto_date'=>date('Y-m-d')];
        $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        $propWhere = "";
        $wardWhere = "";
        $where = "";
        if($this->request->getMethod()=='post')
        {
            $data = arrFilterSanitizeString($this->request->getVar());
            if ($data['ward_mstr_id']!='') {
                $propWhere = " AND tbl_prop_dtl.ward_mstr_id=".$data['ward_mstr_id'];
                $wardWhere = " AND view_ward_mstr.id=".$data['ward_mstr_id'];
                $where = " AND tbl_saf_dtl.ward_mstr_id=".$data['ward_mstr_id'];
            }

            $sql = "WITH total_prop AS (
                SELECT ward_mstr_id, COUNT(*) AS no_of_prop FROM tbl_prop_dtl WHERE status=1 ".$propWhere." GROUP BY ward_mstr_id
            ),
            total_saf AS (
                SELECT ward_mstr_id, COUNT(*) AS no_of_saf FROM tbl_saf_dtl WHERE status=1 ".$where." GROUP BY ward_mstr_id
            ),
            total_sam AS (
                SELECT tbl_saf_dtl.ward_mstr_id, COUNT(*) AS no_of_sam FROM tbl_saf_memo_dtl
                INNER JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_saf_memo_dtl.saf_dtl_id
                WHERE tbl_saf_dtl.status=1 ".$where." AND tbl_saf_memo_dtl.status=1 AND tbl_saf_memo_dtl.memo_type='SAM' GROUP BY tbl_saf_dtl.ward_mstr_id
            ),
            total_fam AS (
                SELECT tbl_saf_dtl.ward_mstr_id, COUNT(*) AS no_of_fam FROM tbl_saf_memo_dtl
                INNER JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_saf_memo_dtl.saf_dtl_id
                WHERE tbl_saf_dtl.status=1 ".$where." AND tbl_saf_memo_dtl.status=1 AND tbl_saf_memo_dtl.memo_type='FAM' GROUP BY tbl_saf_dtl.ward_mstr_id
            ),
            total_geotagging AS (
                SELECT tbl_saf_dtl.ward_mstr_id, COUNT(*) AS no_of_geotagging FROM tbl_saf_dtl 
                INNER JOIN (SELECT geotag_dtl_id FROM tbl_saf_geotag_upload_dtl WHERE status=1 GROUP BY geotag_dtl_id) AS geotag_dtl ON geotag_dtl.geotag_dtl_id=tbl_saf_dtl.id 
                WHERE tbl_saf_dtl.status=1 ".$where." GROUP BY tbl_saf_dtl.ward_mstr_id
            ),
            total_back_to_citizen AS (
                SELECT tbl_saf_dtl.ward_mstr_id, COUNT(*) AS back_to_citizen FROM tbl_saf_dtl
                INNER JOIN (
                    SELECT 
                        tbl_level_pending_dtl.saf_dtl_id 
                    FROM tbl_level_pending_dtl 
                    INNER JOIN (SELECT MAX(id) AS id, saf_dtl_id FROM tbl_level_pending_dtl GROUP BY saf_dtl_id) AS level_pending_dtl ON level_pending_dtl.id=tbl_level_pending_dtl.id
                    WHERE 
                        tbl_level_pending_dtl.status=1
                        AND tbl_level_pending_dtl.verification_status=2
                        AND tbl_level_pending_dtl.sender_user_type_id=11
                        AND tbl_level_pending_dtl.receiver_user_type_id=6
                    GROUP BY tbl_level_pending_dtl.saf_dtl_id) AS back_to_citizen_dtl ON back_to_citizen_dtl.saf_dtl_id=tbl_saf_dtl.id 
                WHERE tbl_saf_dtl.status=1 ".$where." GROUP BY tbl_saf_dtl.ward_mstr_id
            ),
            total_sam_pending AS (
                SELECT 
                    tbl_saf_dtl.ward_mstr_id, 
                    COUNT(*) AS sam_pending 
                FROM tbl_saf_dtl 
                INNER JOIN tbl_transaction ON tbl_transaction.prop_dtl_id=tbl_saf_dtl.id AND tbl_transaction.tran_type='Saf'
                WHERE NOT EXISTS (
                    SELECT saf_dtl_id FROM tbl_saf_memo_dtl WHERE tbl_saf_memo_dtl.memo_type='SAM' AND tbl_saf_memo_dtl.saf_dtl_id=tbl_saf_dtl.id)
                AND tbl_saf_dtl.status=1 ".$where." GROUP BY tbl_saf_dtl.ward_mstr_id
            ),
            total_fam_pending AS (
                SELECT 
                    tbl_saf_dtl.ward_mstr_id, COUNT(*) AS fam_pending 
                FROM tbl_saf_dtl
                INNER JOIN tbl_transaction ON tbl_transaction.prop_dtl_id=tbl_saf_dtl.id AND tbl_transaction.tran_type='Saf'
                WHERE NOT EXISTS (
                    SELECT saf_dtl_id FROM tbl_saf_memo_dtl WHERE tbl_saf_memo_dtl.memo_type='FAM' AND tbl_saf_memo_dtl.saf_dtl_id=tbl_saf_dtl.id)
                AND tbl_saf_dtl.status=1 ".$where." GROUP BY tbl_saf_dtl.ward_mstr_id
            )
            SELECT
                view_ward_mstr.ward_no,
                COALESCE(total_saf.no_of_saf, 0) AS no_of_saf,
                COALESCE(total_sam.no_of_sam, 0) AS no_of_sam,
                COALESCE(total_geotagging.no_of_geotagging, 0) AS no_of_geotagging,
                COALESCE(total_back_to_citizen.back_to_citizen, 0) AS no_of_back_to_citizen,
                COALESCE(total_sam_pending.sam_pending, 0) AS no_of_sam_pending,
                COALESCE(total_fam_pending.fam_pending, 0) AS no_of_fam_pending
            FROM view_ward_mstr
            LEFT JOIN total_saf ON total_saf.ward_mstr_id=view_ward_mstr.id
            LEFT JOIN total_sam ON total_sam.ward_mstr_id=view_ward_mstr.id
            LEFT JOIN total_geotagging ON total_geotagging.ward_mstr_id=view_ward_mstr.id
            LEFT JOIN total_back_to_citizen ON total_back_to_citizen.ward_mstr_id=view_ward_mstr.id
            LEFT JOIN total_sam_pending ON total_sam_pending.ward_mstr_id=view_ward_mstr.id
            LEFT JOIN total_fam_pending ON total_fam_pending.ward_mstr_id=view_ward_mstr.id
            WHERE view_ward_mstr.status=1 ".$wardWhere;

            if($report_list = $this->model_datatable->getRecords($sql))
            {
                //echo $this->db->getLastQuery();
                $data['pending_dtl'] = $report_list;
            }
        }
        
        $data['wardList'] = $wardList;
        return view('property/reports/safSamAndGeotagging', $data); 
    }*/
	
	public function safSamAndGeotagging()
    {
        $session = session();
        $ulb_mstr_id = $session->get('ulb_dtl')['ulb_mstr_id'];
        $data = ['from_date'=>date('Y-m-d'), 'upto_date'=>date('Y-m-d')];
        $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        $propWhere = "";
        $wardWhere = "";
        $where = "";
        if($this->request->getMethod()=='post')
        {
            $data = arrFilterSanitizeString($this->request->getVar());

            $where = " AND tbl_saf_dtl.created_on::date between '".$data['from_date']."' and '".$data['to_date']."'";

            if ($data['ward_mstr_id']!='') {
                $propWhere = " AND tbl_prop_dtl.ward_mstr_id=".$data['ward_mstr_id'];
                $wardWhere = " AND view_ward_mstr.id=".$data['ward_mstr_id'];
                $where = " AND tbl_saf_dtl.ward_mstr_id=".$data['ward_mstr_id'];
            }

            $sql = "WITH total_prop AS (
                SELECT ward_mstr_id, COUNT(*) AS no_of_prop FROM tbl_prop_dtl WHERE status=1 ".$propWhere." GROUP BY ward_mstr_id
            ),
            total_saf AS (
				SELECT tbl_saf_dtl.ward_mstr_id, COUNT(*) AS no_of_saf FROM tbl_saf_dtl 
                INNER JOIN (select prop_dtl_id from tbl_transaction where tbl_transaction.tran_type='Saf' group by prop_dtl_id) tbl_transaction ON  tbl_transaction.prop_dtl_id=tbl_saf_dtl.id 
                WHERE tbl_saf_dtl.status=1 ".$where." GROUP BY tbl_saf_dtl.ward_mstr_id
            ),
            total_sam AS (
                SELECT tbl_saf_dtl.ward_mstr_id, COUNT(DISTINCT tbl_saf_memo_dtl.saf_dtl_id) AS no_of_sam FROM tbl_saf_memo_dtl
                INNER JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_saf_memo_dtl.saf_dtl_id
                WHERE tbl_saf_dtl.status=1 AND (tbl_saf_memo_dtl.created_on::date between '".$data['from_date']."' and '".$data['to_date']."') ".$where." AND tbl_saf_memo_dtl.status=1 AND tbl_saf_memo_dtl.memo_type='SAM' GROUP BY tbl_saf_dtl.ward_mstr_id
            ),
            total_fam AS (
                SELECT tbl_saf_dtl.ward_mstr_id, COUNT(DISTINCT tbl_saf_memo_dtl.saf_dtl_id) AS no_of_fam FROM tbl_saf_memo_dtl
                INNER JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_saf_memo_dtl.saf_dtl_id
                WHERE tbl_saf_dtl.status=1 AND (tbl_saf_memo_dtl.created_on::date between '".$data['from_date']."' and '".$data['to_date']."') ".$where." AND tbl_saf_memo_dtl.status=1 AND tbl_saf_memo_dtl.memo_type='FAM' GROUP BY tbl_saf_dtl.ward_mstr_id
            ),
            total_geotagging AS (
                SELECT tbl_saf_dtl.ward_mstr_id, COUNT(DISTINCT geotag_dtl.geotag_dtl_id) AS no_of_geotagging FROM tbl_saf_dtl 
                INNER JOIN (SELECT geotag_dtl_id FROM tbl_saf_geotag_upload_dtl WHERE status=1 AND (tbl_saf_geotag_upload_dtl.created_on::date between '".$data['from_date']."' and '".$data['to_date']."') GROUP BY geotag_dtl_id) AS geotag_dtl ON geotag_dtl.geotag_dtl_id=tbl_saf_dtl.id 
                WHERE tbl_saf_dtl.status=1 ".$where." GROUP BY tbl_saf_dtl.ward_mstr_id
            ),
            total_back_to_citizen AS (
                SELECT tbl_saf_dtl.ward_mstr_id, COUNT(back_to_citizen_dtl.saf_dtl_id) AS back_to_citizen FROM tbl_saf_dtl
                INNER JOIN (
                    SELECT 
                        tbl_level_pending_dtl.saf_dtl_id 
                    FROM tbl_level_pending_dtl 
                    INNER JOIN (SELECT MAX(id) AS id, saf_dtl_id FROM tbl_level_pending_dtl GROUP BY saf_dtl_id) AS level_pending_dtl ON level_pending_dtl.id=tbl_level_pending_dtl.id
                    WHERE 
                        tbl_level_pending_dtl.status=1
                        AND tbl_level_pending_dtl.verification_status=2
                        AND tbl_level_pending_dtl.receiver_user_type_id=11
                        and (tbl_level_pending_dtl.forward_date::date between '".$data['from_date']."' and '".$data['to_date']."')
                    GROUP BY tbl_level_pending_dtl.saf_dtl_id) AS back_to_citizen_dtl ON back_to_citizen_dtl.saf_dtl_id=tbl_saf_dtl.id 
                WHERE tbl_saf_dtl.status=1 ".$where." GROUP BY tbl_saf_dtl.ward_mstr_id
            ),
            total_sam_pending AS (
                SELECT 
                    tbl_saf_dtl.ward_mstr_id, 
                    COUNT(*) AS sam_pending 
                FROM tbl_saf_dtl 
                INNER JOIN (select prop_dtl_id from tbl_transaction where tbl_transaction.tran_type='Saf' group by prop_dtl_id) tbl_transaction ON  tbl_transaction.prop_dtl_id=tbl_saf_dtl.id 
                WHERE NOT EXISTS (
                    SELECT saf_dtl_id FROM tbl_saf_memo_dtl WHERE (tbl_saf_memo_dtl.memo_type='SAM' or tbl_saf_memo_dtl.memo_type='FAM') AND tbl_saf_memo_dtl.saf_dtl_id=tbl_saf_dtl.id)
                AND tbl_saf_dtl.status=1 ".$where." GROUP BY tbl_saf_dtl.ward_mstr_id
            ),
            total_fam_pending AS (
                SELECT 
                    tbl_saf_dtl.ward_mstr_id, COUNT(*) AS fam_pending 
                FROM tbl_saf_dtl
                INNER JOIN (select prop_dtl_id from tbl_transaction where tbl_transaction.tran_type='Saf' group by prop_dtl_id) tbl_transaction ON  tbl_transaction.prop_dtl_id=tbl_saf_dtl.id 
                WHERE NOT EXISTS (
                    SELECT saf_dtl_id FROM tbl_saf_memo_dtl WHERE tbl_saf_memo_dtl.memo_type='FAM' AND tbl_saf_memo_dtl.saf_dtl_id=tbl_saf_dtl.id)
                AND tbl_saf_dtl.status=1  ".$where." GROUP BY tbl_saf_dtl.ward_mstr_id
            )
            SELECT
                view_ward_mstr.ward_no,
                COALESCE(total_saf.no_of_saf, 0) AS no_of_saf,
                COALESCE(total_sam.no_of_sam, 0) AS no_of_sam,
                COALESCE(total_fam.no_of_fam, 0) AS no_of_fam,
                COALESCE(total_geotagging.no_of_geotagging, 0) AS no_of_geotagging,
                COALESCE(total_back_to_citizen.back_to_citizen, 0) AS no_of_back_to_citizen,
                COALESCE(total_sam_pending.sam_pending, 0) AS no_of_sam_pending,
                COALESCE(total_fam_pending.fam_pending, 0) AS no_of_fam_pending
            FROM view_ward_mstr
            LEFT JOIN total_saf ON total_saf.ward_mstr_id=view_ward_mstr.id
            LEFT JOIN total_sam ON total_sam.ward_mstr_id=view_ward_mstr.id
            LEFT JOIN total_fam ON total_fam.ward_mstr_id=view_ward_mstr.id
            LEFT JOIN total_geotagging ON total_geotagging.ward_mstr_id=view_ward_mstr.id
            LEFT JOIN total_back_to_citizen ON total_back_to_citizen.ward_mstr_id=view_ward_mstr.id
            LEFT JOIN total_sam_pending ON total_sam_pending.ward_mstr_id=view_ward_mstr.id
            LEFT JOIN total_fam_pending ON total_fam_pending.ward_mstr_id=view_ward_mstr.id
            WHERE view_ward_mstr.status=1 ".$wardWhere;

            /* echo "<pre>";
            print_r($sql);
            echo "</pre>"; */
            if($report_list = $this->model_datatable->getRecords($sql))
            {
                //echo $this->db->getLastQuery();
                $data['pending_dtl'] = $report_list;
            }
        }
        
        $data['wardList'] = $wardList;
        return view('property/reports/safSamAndGeotagging', $data); 
    }

    public function safSamAndGeotaggingDateRange()
    {
        $session = session();
        $ulb_mstr_id = $session->get('ulb_dtl')['ulb_mstr_id'];
        $data = ['from_date'=>date('Y-m-d'), 'upto_date'=>date('Y-m-d')];
        $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        $propWhere = "";
        $wardWhere = "";
        $safWhere = "";
        if($this->request->getMethod()=='post')
        {
            $data = arrFilterSanitizeString($this->request->getVar());
            if ($data['ward_mstr_id']!='') {
                $propWhere = " AND tbl_prop_dtl.ward_mstr_id=".$data['ward_mstr_id'];
                $wardWhere = " AND view_ward_mstr.id=".$data['ward_mstr_id'];
                $where = " AND tbl_saf_dtl.ward_mstr_id=".$data['ward_mstr_id'];
            }
            if ($data['saf_apply_from_date']!='' && $data['saf_apply_upto_date']!='') {
                $safWhere = " AND tbl_saf_dtl.apply_date BETWEEN '".$data['saf_apply_from_date']."' AND '".$data['saf_apply_upto_date']."'";
            }

            $sql = "WITH total_prop AS (
                SELECT ward_mstr_id, COUNT(*) AS no_of_prop FROM tbl_prop_dtl WHERE status=1 ".$propWhere." GROUP BY ward_mstr_id
            ),
            total_saf AS (
                SELECT ward_mstr_id, COUNT(*) AS no_of_saf FROM tbl_saf_dtl WHERE status=1 ".$where." GROUP BY ward_mstr_id
            ),
            total_sam AS (
                SELECT tbl_saf_dtl.ward_mstr_id, COUNT(*) AS no_of_sam FROM tbl_saf_memo_dtl
                INNER JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_saf_memo_dtl.saf_dtl_id
                WHERE tbl_saf_dtl.status=1 ".$where.$safWhere." AND tbl_saf_memo_dtl.status=1 AND tbl_saf_memo_dtl.memo_type='SAM' GROUP BY tbl_saf_dtl.ward_mstr_id
            ),
            total_fam AS (
                SELECT tbl_saf_dtl.ward_mstr_id, COUNT(*) AS no_of_fam FROM tbl_saf_memo_dtl
                INNER JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_saf_memo_dtl.saf_dtl_id
                WHERE tbl_saf_dtl.status=1 ".$where.$safWhere." AND tbl_saf_memo_dtl.status=1 AND tbl_saf_memo_dtl.memo_type='FAM' GROUP BY tbl_saf_dtl.ward_mstr_id
            ),
            total_geotagging AS (
                SELECT tbl_saf_dtl.ward_mstr_id, COUNT(*) AS no_of_geotagging FROM tbl_saf_dtl 
                INNER JOIN (SELECT geotag_dtl_id FROM tbl_saf_geotag_upload_dtl WHERE status=1 GROUP BY geotag_dtl_id) AS geotag_dtl ON geotag_dtl.geotag_dtl_id=tbl_saf_dtl.id 
                WHERE tbl_saf_dtl.status=1 ".$where.$safWhere." GROUP BY tbl_saf_dtl.ward_mstr_id
            ),
            total_back_to_citizen AS (
                SELECT tbl_saf_dtl.ward_mstr_id, COUNT(*) AS back_to_citizen FROM tbl_saf_dtl
                INNER JOIN (
                    SELECT 
                        tbl_level_pending_dtl.saf_dtl_id 
                    FROM tbl_level_pending_dtl 
                    INNER JOIN (SELECT MAX(id) AS id, saf_dtl_id FROM tbl_level_pending_dtl GROUP BY saf_dtl_id) AS level_pending_dtl ON level_pending_dtl.id=tbl_level_pending_dtl.id
                    WHERE 
                        tbl_level_pending_dtl.status=1
                        AND tbl_level_pending_dtl.verification_status=2
                        AND tbl_level_pending_dtl.sender_user_type_id=11
                        AND tbl_level_pending_dtl.receiver_user_type_id=6
                    GROUP BY tbl_level_pending_dtl.saf_dtl_id) AS back_to_citizen_dtl ON back_to_citizen_dtl.saf_dtl_id=tbl_saf_dtl.id 
                WHERE tbl_saf_dtl.status=1 ".$where.$safWhere." GROUP BY tbl_saf_dtl.ward_mstr_id
            ),
            total_sam_pending AS (
                SELECT 
                    tbl_saf_dtl.ward_mstr_id, 
                    COUNT(*) AS sam_pending 
                FROM tbl_saf_dtl 
                INNER JOIN tbl_transaction ON tbl_transaction.prop_dtl_id=tbl_saf_dtl.id AND tbl_transaction.tran_type='Saf'
                WHERE NOT EXISTS (
                    SELECT saf_dtl_id FROM tbl_saf_memo_dtl WHERE tbl_saf_memo_dtl.memo_type='SAM' AND tbl_saf_memo_dtl.saf_dtl_id=tbl_saf_dtl.id)
                AND tbl_saf_dtl.status=1 ".$where.$safWhere." GROUP BY tbl_saf_dtl.ward_mstr_id
            ),
            total_fam_pending AS (
                SELECT 
                    tbl_saf_dtl.ward_mstr_id, COUNT(*) AS fam_pending 
                FROM tbl_saf_dtl
                INNER JOIN tbl_transaction ON tbl_transaction.prop_dtl_id=tbl_saf_dtl.id AND tbl_transaction.tran_type='Saf'
                WHERE NOT EXISTS (
                    SELECT saf_dtl_id FROM tbl_saf_memo_dtl WHERE tbl_saf_memo_dtl.memo_type='FAM' AND tbl_saf_memo_dtl.saf_dtl_id=tbl_saf_dtl.id)
                AND tbl_saf_dtl.status=1 ".$where.$safWhere." GROUP BY tbl_saf_dtl.ward_mstr_id
            )
            SELECT
                view_ward_mstr.ward_no,
                COALESCE(total_saf.no_of_saf, 0) AS no_of_saf,
                COALESCE(total_sam.no_of_sam, 0) AS no_of_sam,
                COALESCE(total_geotagging.no_of_geotagging, 0) AS no_of_geotagging,
                COALESCE(total_back_to_citizen.back_to_citizen, 0) AS no_of_back_to_citizen,
                COALESCE(total_sam_pending.sam_pending, 0) AS no_of_sam_pending,
                COALESCE(total_fam_pending.fam_pending, 0) AS no_of_fam_pending
            FROM view_ward_mstr
            LEFT JOIN total_saf ON total_saf.ward_mstr_id=view_ward_mstr.id
            LEFT JOIN total_sam ON total_sam.ward_mstr_id=view_ward_mstr.id
            LEFT JOIN total_geotagging ON total_geotagging.ward_mstr_id=view_ward_mstr.id
            LEFT JOIN total_back_to_citizen ON total_back_to_citizen.ward_mstr_id=view_ward_mstr.id
            LEFT JOIN total_sam_pending ON total_sam_pending.ward_mstr_id=view_ward_mstr.id
            LEFT JOIN total_fam_pending ON total_fam_pending.ward_mstr_id=view_ward_mstr.id
            WHERE view_ward_mstr.status=1 ".$wardWhere;

            //print_var($sql);
            if($report_list = $this->model_datatable->getRecords($sql))
            {
                //echo $this->db->getLastQuery();
                $data['pending_dtl'] = $report_list;
            }
        }
        
        $data['wardList'] = $wardList;
        return view('property/reports/safSamAndGeotaggingDateRange', $data); 
    }
	
	
	
	public function holding_collection()
    {
        $session = session();
        $ulb_mstr_id = $session->get('ulb_dtl')['ulb_mstr_id'];
        $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        $data['wardList'] = $wardList;
        return view('property/reports/holding_collection', $data);
    }

    public function holding_collectionAjax() {
        if($this->request->getMethod()=='post'){
			try{
                ## Read value
                $start = sanitizeString($this->request->getVar('start'));
                $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page

                $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
                $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
                if ($columnName=="s_no" || $columnName=="ward_no" || $columnName=="total_demand")
                    $columnName = 'view_ward_mstr.ward_no';
                if ($columnName=="holding_no")
                    $columnName = 'tbl_prop_dtl.holding_no';
                if ($columnName=="owner_name")
                    $columnName = 'prop_owner_detail.owner_name';
                if ($columnName=="mobile_no")
                    $columnName = 'prop_owner_detail.mobile_no';
                if ($columnName=="address")
                    $columnName = 'tbl_prop_dtl.prop_address';

                
                //$columnName = "tbl_transaction.tran_date";
                $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
                $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value
                
                // Date filter
                $search_ward_mstr_id = sanitizeString($this->request->getVar('search_ward_mstr_id'));
                $searchQuery = "";
                $whereQuery = "";
                
                
                $orderBY = " ORDER BY ".$columnName." ".$columnSortOrder;
                $limit = " LIMIT ".$rowperpage." OFFSET ".$start;

                $currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>'2016-2017'])['id'];

                if ($search_ward_mstr_id != '') {
                    $whereQuery .= " AND tbl_prop_dtl.ward_mstr_id='".$search_ward_mstr_id."'";
                }
                
                $whereQueryWithSearch = "";
                if ($searchValue!='') {
                    $whereQueryWithSearch .= " AND (prop_owner_detail.owner_name ILIKE '%".$searchValue."%'
                                    OR prop_owner_detail.mobile_no ILIKE '%".$searchValue."%'
                                    OR tbl_prop_dtl.holding_no ILIKE '%".$searchValue."%'
                                    OR view_ward_mstr.ward_no ILIKE '%".$searchValue."%'
                                    OR tbl_prop_dtl.prop_address ILIKE '%".$searchValue."%'
                                    OR tbl_prop_dtl.prop_city ILIKE '%".$searchValue."%'
                                    OR tbl_prop_dtl.prop_pin_code ILIKE '%".$searchValue."%')";
                }
                
                $selectStatement = "SELECT 
                                    ROW_NUMBER () OVER (ORDER BY ".$columnName.") AS s_no,
                                    view_ward_mstr.ward_no,
                                    (CASE WHEN tbl_prop_dtl.holding_no!='' THEN tbl_prop_dtl.holding_no ELSE tbl_prop_dtl.new_holding_no END) AS holding_no,
                                    prop_owner_detail.owner_name,
                                    prop_owner_detail.mobile_no,
                                    CONCAT(tbl_prop_dtl.prop_address, ', City - ', tbl_prop_dtl.prop_city, ', Pin Code - ', tbl_prop_dtl.prop_pin_code) AS address,
                                    prop_demand.total_demand,
                                    prop_collection.total_collection,
                                    (prop_demand.total_demand-prop_collection.total_collection) AS total_remaining";
                $sql =  " FROM tbl_prop_dtl
                        INNER JOIN (SELECT prop_dtl_id, SUM(amount) AS total_demand FROM tbl_prop_demand WHERE status=1 GROUP BY prop_dtl_id) AS prop_demand ON prop_demand.prop_dtl_id=tbl_prop_dtl.id
                        INNER JOIN (SELECT prop_dtl_id, SUM(amount) AS total_collection FROM tbl_collection WHERE status=1 GROUP BY prop_dtl_id) AS prop_collection ON prop_collection.prop_dtl_id=tbl_prop_dtl.id
                        INNER JOIN (SELECT STRING_AGG(owner_name, ', ') AS owner_name, STRING_AGG(mobile_no::TEXT, ', ') AS mobile_no, prop_dtl_id FROM tbl_prop_owner_detail GROUP BY prop_dtl_id) prop_owner_detail ON prop_owner_detail.prop_dtl_id=tbl_prop_dtl.id
                        INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
                        WHERE tbl_prop_dtl.status=1 AND view_ward_mstr.status=1".$whereQuery;
                
                ## Total number of records without filtering
                $totalRecords = $this->model_datatable->getTotalRecords($sql);
                //return json_encode($totalRecords);
                if ($totalRecords>0) {
                   
                    ## Total number of records with filtering
                    $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($sql.$whereQueryWithSearch);
                    
                    ## Fetch records
                    $fetchSql = $selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit;
                    $records = $this->model_datatable->getRecords($fetchSql);
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
            }catch(Exception $e){

            }
        }
    }
	
	
	public function holding_collectionExcel($search_ward_mstr_id = null) {
        try{
            $whereQuery = "";
                if ($search_ward_mstr_id != 'ALL') {
                    $whereQuery .= " AND  tbl_prop_dtl.ward_mstr_id='".$search_ward_mstr_id."'";
                }

                $currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>'2016-2017'])['id'];

                $selectStatement = "SELECT 
                                    view_ward_mstr.ward_no,
                                    (CASE WHEN tbl_prop_dtl.holding_no!='' THEN tbl_prop_dtl.holding_no ELSE tbl_prop_dtl.new_holding_no END) AS holding_no,
                                    prop_owner_detail.owner_name,
                                    prop_owner_detail.mobile_no,
                                    CONCAT(tbl_prop_dtl.prop_address, ', City - ', tbl_prop_dtl.prop_city, ', Pin Code - ', tbl_prop_dtl.prop_pin_code) AS address,
                                    prop_demand.total_demand,
                                    prop_collection.total_collection,
                                    (prop_demand.total_demand-prop_collection.total_collection) AS total_remaining";

                $sql = " FROM tbl_prop_dtl
                        INNER JOIN (SELECT prop_dtl_id, SUM(amount) AS total_demand FROM tbl_prop_demand WHERE status=1 GROUP BY prop_dtl_id) AS prop_demand ON prop_demand.prop_dtl_id=tbl_prop_dtl.id
                        INNER JOIN (SELECT prop_dtl_id, SUM(amount) AS total_collection FROM tbl_collection WHERE status=1 GROUP BY prop_dtl_id) AS prop_collection ON prop_collection.prop_dtl_id=tbl_prop_dtl.id
                        INNER JOIN (SELECT STRING_AGG(owner_name, ', ') AS owner_name, STRING_AGG(mobile_no::TEXT, ', ') AS mobile_no, prop_dtl_id FROM tbl_prop_owner_detail GROUP BY prop_dtl_id) prop_owner_detail ON prop_owner_detail.prop_dtl_id=tbl_prop_dtl.id
                        INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
                        WHERE tbl_prop_dtl.status=1 AND view_ward_mstr.status=1".$whereQuery;

            $fetchSql = $selectStatement.$sql;
            $records = $this->model_datatable->getRecords($fetchSql);
            phpOfficeLoad();
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $activeSheet = $spreadsheet->getActiveSheet();
                            $activeSheet->setCellValue('A1', 'Ward No.');
                            $activeSheet->setCellValue('B1', 'Holding No.');
                            $activeSheet->setCellValue('C1', 'Applicant Name');
                            $activeSheet->setCellValue('D1', 'Mobile No.');
                            $activeSheet->setCellValue('E1', 'Address');
                            $activeSheet->fromArray($records, NULL, 'A2');
            $filename = "prop_individual_demand_and_collecton_".date('Ymd-hisa').".xlsx";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            //$writer->save(APPPATH.'/hello world.xlsx');
            $writer->save('php://output');
        }catch(Exception $e){
            print_r($e);
        }
    }
}