<?php 
namespace App\Controllers;

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

class BOC_SAF extends AlphaController
{
    protected $db;
    protected $dbSystem;
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
	protected $model_datatable;
    protected $PropReports;

    public function __construct(){
        parent::__construct();
    	helper(['db_helper', 'utility_helper']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name);            
        }
        if($db_system = dbSystem()){
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
		$this->model_datatable = new model_datatable($this->db);
    }

    function __destruct() {
		$this->db->close();
		$this->dbSystem->close();
	}

	
	public function index()
	{
        /*$data =(array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
        //print_r($emp_mstr);
        $data['wardList'] = $this->model_view_ward_permission->getPermittedWardWithSession($login_emp_details_id);
		$imploded_ward_mstr_id= implode(', ', array_map(function ($entry) {
			return $entry['ward_mstr_id'];
		  }, $data['wardList']));

		
        if($this->request->getMethod()=='post')
		{
            $data['ward_mstr_id'] = $this->request->getVar('ward_mstr_id');
            $data['from_date'] = $this->request->getVar('from_date');
            $data['to_date'] = $this->request->getVar('to_date');
            if($data['ward_mstr_id']!="")
            {
                $where = "ward_mstr_id=".$data['ward_mstr_id']." AND date(forward_date) between '".date("Y-m-d", strtotime($data['from_date']))."' AND '".date("Y-m-d", strtotime($data['to_date']))."'
				AND saf_pending_status='2' AND tbl_saf_dtl.status=1 and tbl_level_pending_dtl.status=1 ORDER BY id DESC";
            }
            else
			{
				$where = "ward_mstr_id in (".$imploded_ward_mstr_id.") AND date(forward_date) between '".date("Y-m-d", strtotime($data['from_date']))."' AND '".date("Y-m-d", strtotime($data['to_date']))."'
				AND saf_pending_status='2' AND tbl_saf_dtl.status=1 and tbl_level_pending_dtl.status=1 ORDER BY id DESC";
            }
		
        }
        else
        {
            $data['from_date'] = date('Y-m-d');
            $data['to_date'] = date('Y-m-d');
            $where = "ward_mstr_id in (".$imploded_ward_mstr_id.") AND date(forward_date) between '".date("Y-m-d", strtotime($data['from_date']))."' AND '".date("Y-m-d", strtotime($data['to_date']))."'
			 AND saf_pending_status='2' AND tbl_saf_dtl.status=1 and tbl_level_pending_dtl.status=1 ORDER BY id DESC";
        }
		
		$Session->set('where', $where);
		$Session->set('from_date', $data['from_date']);
		$Session->set('to_date', $data['to_date']);
		$Session->set('wardList', $data['wardList']);*/
		return $this->response->redirect(base_url('BOC_SAF/btc_list'));
	}

    public function inbox()
	{
		$session = session();
        $emp_mstr = $session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
        $data = arrFilterSanitizeString($this->request->getVar());
        $data['wardList'] = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $permittedWard = array_column($data['wardList'], 'ward_mstr_id');
        $whereWard = " AND tbl_saf_dtl.ward_mstr_id IN (".implode(',', $permittedWard).")";
        $wherePropType = "";
        $whereAssessmentType = "";
        $whereSearchPrm = "";
        if (isset($data["ward_mstr_id"]) && isset($data["search_param"]) && isset($data["prop_type_mstr_id"]) && isset($data["assessment_type"])) {
            if ($data["assessment_type"]!="") {
                $whereAssessmentType = " AND tbl_saf_dtl.assessment_type='".$data["assessment_type"]."'";
            }
            if ($data["prop_type_mstr_id"]!="") {
                $wherePropType = " AND tbl_saf_dtl.prop_type_mstr_id='".$data["prop_type_mstr_id"]."'";
            }
            if ($data["ward_mstr_id"]!="") {
                $whereWard = " AND tbl_saf_dtl.ward_mstr_id IN (".$data["ward_mstr_id"].")";
            }
            if ($data["search_param"]!="") {
                $whereSearchPrm = " AND (tbl_saf_dtl.saf_no ~* '".$data["search_param"]."' 
                                        OR owner_dtl.owner_name ~* '".$data["search_param"]."' 
                                        OR owner_dtl.mobile_no ~* '".$data["search_param"]."')";
            }
        }
        
        $sql = "SELECT 
                    tbl_level_pending_dtl.id,
                    tbl_level_pending_dtl.saf_dtl_id,
                    tbl_prop_type_mstr.property_type,
                    view_ward_mstr.ward_no,
                    tbl_saf_dtl.saf_no,
                    owner_dtl.owner_name,
                    owner_dtl.mobile_no,
                    tbl_saf_dtl.assessment_type,
                    tbl_level_pending_dtl.forward_date,
                    tbl_level_pending_dtl.forward_time,
                    tbl_level_pending_dtl.remarks
                FROM tbl_level_pending_dtl
                INNER JOIN tbl_saf_dtl ON tbl_level_pending_dtl.saf_dtl_id = tbl_saf_dtl.id ".$whereWard."
                INNER JOIN tbl_prop_type_mstr ON tbl_prop_type_mstr.id=tbl_saf_dtl.prop_type_mstr_id
                INNER JOIN ( SELECT tbl_saf_owner_detail.saf_dtl_id,
                        string_agg(tbl_saf_owner_detail.owner_name::text, ', '::text) AS owner_name,
                        string_agg(tbl_saf_owner_detail.mobile_no::text, ', '::text) AS mobile_no
                    FROM tbl_saf_owner_detail
                    GROUP BY tbl_saf_owner_detail.saf_dtl_id
                ) owner_dtl ON owner_dtl.saf_dtl_id = tbl_level_pending_dtl.saf_dtl_id
                INNER JOIN view_ward_mstr ON view_ward_mstr.id = tbl_saf_dtl.ward_mstr_id
                WHERE 
                    tbl_level_pending_dtl.verification_status='0'
                    AND tbl_level_pending_dtl.status='1'
                    AND tbl_level_pending_dtl.receiver_user_type_id='11'
                     ".$whereSearchPrm.$whereAssessmentType.$wherePropType."
                ORDER BY tbl_level_pending_dtl.id DESC";
        

        $result = $this->model_datatable->getDatatable($sql);
        $data['btcList'] = isset($result['result']) ? $result['result'] : null;
        $data['pager'] = $result['count'];
		return view('property/saf/bo_inbox', $data);			
        
	}
	
	
    public function btc_list()
	{
		$session = session();
        $ulb_mstr = $session->get("ulb_dtl");
        $emp_mstr = $session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];

        $user_mstr_id = $emp_mstr["user_type_mstr_id"];
        if($user_mstr_id=="4")
        {
            return redirect()->to('/home');
        }

        $data = arrFilterSanitizeString($this->request->getVar());
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];

        $data['wardList'] = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $permittedWard = array_column($data['wardList'], 'ward_mstr_id');
        $whereWard = " AND tbl_saf_dtl.ward_mstr_id IN (".implode(',', $permittedWard).")";
        $wherePropType = "";
        $whereAssessmentType = "";
        $whereSearchPrm = "";
        $whereDateRange = "";
        if(isset($data["search_param"]) && $data["search_param"]!="")
            {
                $whereSearchPrm = " AND (tbl_saf_dtl.saf_no ~* '".$data["search_param"]."' 
                                        OR owner_dtl.owner_name ~* '".$data["search_param"]."' 
                                        OR owner_dtl.mobile_no ~* '".$data["search_param"]."')";
            }else{
                if ($data["assessment_type"]!="") {
                    $whereAssessmentType = " AND tbl_saf_dtl.assessment_type='".$data["assessment_type"]."'";
                }
                if ($data["prop_type_mstr_id"]!="") {
                    $wherePropType = " AND tbl_saf_dtl.prop_type_mstr_id='".$data["prop_type_mstr_id"]."'";
                }
                if ($data["ward_mstr_id"]!="") {
                    $whereWard = " AND tbl_saf_dtl.ward_mstr_id IN (".$data["ward_mstr_id"].")";
                }
                $from_date = $this->request->getVar("from_date");
                $to_date = $this->request->getVar("to_date");
                if ($from_date != "" && $to_date != "" ) {
                    $whereDateRange = " AND date(tbl_level_pending_dtl.forward_date) between '$from_date' AND '$to_date'";
                }
            }
        $sql = "SELECT 
                    tbl_level_pending_dtl.id,
                    tbl_level_pending_dtl.saf_dtl_id,
                    tbl_prop_type_mstr.property_type,
                    view_ward_mstr.ward_no,
                    tbl_saf_dtl.saf_no,
					tbl_saf_dtl.apply_date,
                    owner_dtl.owner_name,
                    owner_dtl.mobile_no,
                    tbl_saf_dtl.assessment_type,
                    tbl_level_pending_dtl.forward_date,
                    tbl_level_pending_dtl.forward_time,
                    tbl_level_pending_dtl.remarks
                FROM tbl_level_pending_dtl
                INNER JOIN tbl_saf_dtl ON tbl_level_pending_dtl.saf_dtl_id = tbl_saf_dtl.id ".$whereWard."
                INNER JOIN tbl_prop_type_mstr ON tbl_prop_type_mstr.id=tbl_saf_dtl.prop_type_mstr_id
                INNER JOIN ( SELECT tbl_saf_owner_detail.saf_dtl_id,
                        string_agg(tbl_saf_owner_detail.owner_name::text, ', '::text) AS owner_name,
                        string_agg(tbl_saf_owner_detail.mobile_no::text, ', '::text) AS mobile_no
                    FROM tbl_saf_owner_detail
                    GROUP BY tbl_saf_owner_detail.saf_dtl_id
                ) owner_dtl ON owner_dtl.saf_dtl_id = tbl_level_pending_dtl.saf_dtl_id
                INNER JOIN view_ward_mstr ON view_ward_mstr.id = tbl_saf_dtl.ward_mstr_id
                WHERE 
                    tbl_saf_dtl.id NOT IN (select saf_dtl_id from tbl_btc_hide)
                    AND tbl_saf_dtl.status=1 AND tbl_saf_dtl.saf_pending_status!=1 
                    AND tbl_level_pending_dtl.verification_status='2'
                    AND tbl_level_pending_dtl.receiver_user_type_id=11
                    AND tbl_level_pending_dtl.status='1' ".$whereSearchPrm.$whereAssessmentType.$wherePropType.$whereDateRange."
                ORDER BY tbl_level_pending_dtl.id DESC";

        $result = $this->model_datatable->getDatatable($sql);
        $data['btcList'] = isset($result['result']) ? $result['result'] : null;
        $data['pager'] = $result['count'];
        $data['offset'] = $result['offset'];
		return view('property/saf/boc_saf_list', $data);			
        
	}

    public function btc_list_excel()
	{
        $data = arrFilterSanitizeString($this->request->getVar());
        if($this->request->getMethod()=='post'){
			try{
                $data = arrFilterSanitizeString($this->request->getVar());
               $sql = $this->PropReports->excelExportBTCList($data);
               
               // $sql = $this->excelExportBTCList($data);

          /*      $result = $this->db->query("SELECT generateCSVReports('".$sql."', 'excelExportBTC')");
               $filename = $result->getFirstRow("array");
               return json_encode($filename);

*/
                $result = $this->db->query($sql);
                $data_ = $result->getResult("array");
                $file="/genexcel/csvExportBTC_".date('Y').".csv";
                $filename=dirname(dirname(__DIR__))."/writable".$file;
                if(file_exists($filename)){
                    $fp=fopen($filename,'w');
                }else{
                    $fp=fopen($filename,'x+');
                }
                 //return json_encode($result);
                fputcsv($fp,array_keys($data_[0]));
                foreach($data_ as $fields)
                {
                    fputcsv($fp,$fields);
                }
                $filename=['generatecsvreports'=>$file];
                fclose($fp);
            return json_encode($filename);
               
            } catch(Exception $e) {
                echo $e;
            }
        }		
        
	}
    public function excelExportBTCList($data) {
        try{
            $wherePropType = "";
            $whereAssessmentType = "";
            $whereSearchPrm = "";
            if (isset($data["ward_mstr_id"]) && isset($data["search_param"]) && isset($data["prop_type_mstr_id"]) && isset($data["assessment_type"])) {
                if ($data["assessment_type"]!="") {
                    $whereAssessmentType = " AND tbl_saf_dtl.assessment_type='".$data["assessment_type"]."'";
                }
                if ($data["prop_type_mstr_id"]!="") {
                    $wherePropType = " AND tbl_saf_dtl.prop_type_mstr_id='".$data["prop_type_mstr_id"]."'";
                }
                if ($data["ward_mstr_id"]!="") {
                    $whereWard = " AND tbl_saf_dtl.ward_mstr_id='".$data["ward_mstr_id"]."'";
                }
                if ($data["search_param"]!="") {
                    $whereSearchPrm = " AND (tbl_saf_dtl.saf_no ~* '".$data["search_param"]."' 
                                            OR owner_dtl.owner_name ~* '".$data["search_param"]."' 
                                            OR owner_dtl.mobile_no ~* '".$data["search_param"]."')";
                }
            }
            $sql = "SELECT 
                        tbl_prop_type_mstr.property_type,
                        CONCAT('`', view_ward_mstr.ward_no) AS ward_no,
                        tbl_saf_dtl.saf_no,
                        owner_dtl.owner_name,
                        CONCAT('`', owner_dtl.mobile_no) AS mobile_no,
                        tbl_saf_dtl.assessment_type,
						tbl_saf_dtl.apply_date,
                        tbl_level_pending_dtl.remarks,
                        tbl_level_pending_dtl.forward_date
                    FROM tbl_level_pending_dtl
                    INNER JOIN tbl_saf_dtl ON tbl_level_pending_dtl.saf_dtl_id = tbl_saf_dtl.id ".$whereWard."
                    INNER JOIN tbl_prop_type_mstr ON tbl_prop_type_mstr.id=tbl_saf_dtl.prop_type_mstr_id
                    INNER JOIN ( SELECT tbl_saf_owner_detail.saf_dtl_id,
                            string_agg(tbl_saf_owner_detail.owner_name::text, ', '::text) AS owner_name,
                            string_agg(tbl_saf_owner_detail.mobile_no::text, ', '::text) AS mobile_no
                        FROM tbl_saf_owner_detail
                        GROUP BY tbl_saf_owner_detail.saf_dtl_id
                    ) owner_dtl ON owner_dtl.saf_dtl_id = tbl_level_pending_dtl.saf_dtl_id
                    INNER JOIN view_ward_mstr ON view_ward_mstr.id = tbl_saf_dtl.ward_mstr_id
                    WHERE 
                    tbl_saf_dtl.status=1 AND tbl_saf_dtl.saf_pending_status!=1 AND tbl_level_pending_dtl.verification_status='2' 
						AND tbl_level_pending_dtl.receiver_user_type_id='11'
                        AND tbl_level_pending_dtl.status='1' ".$whereSearchPrm.$whereAssessmentType.$wherePropType."
                    ORDER BY tbl_level_pending_dtl.id DESC";
                return $sql;
                return $this->addExtraQuote($sql);
        } catch(Exception $e) {

        }
    }
	
	public function send_to_rmc($id=null)
	{
		$data =(array)null;
		$data['saf_id']=$id;
		$leveldata = [
				'saf_dtl_id' => $data['saf_id'],
				'sender_user_type_id' => 11,
				'receiver_user_type_id' => 6,
				'forward_date' => date('Y-m-d'),
				'forward_time' => date('H:i:s'),
				'created_on' =>date('Y-m-d H:i:s'),
				'remarks' => 'Document Correction Uploaded',
				'verification_status' => 0
			];
		//print_r($leveldata);
		$this->model_level_pending_dtl->updateverfystatusDocUpload($data);
		
		$level_pending_insrt=$this->model_level_pending_dtl->insrtlevelpendingdtl($leveldata);
		
		$this->model_saf_dtl->updateSafstatusDocUpload($data);
		//echo "jhdbfjv";
		return $this->response->redirect(base_url('safdoc/view/'.md5($data['saf_id'])));
		//return $this->response->redirect(base_url('boc_saf/index/'.md5($data['saf_id'])));
	}


}