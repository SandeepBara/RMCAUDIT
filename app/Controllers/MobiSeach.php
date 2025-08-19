<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PostModel;
use App\Models\model_ward_mstr;
use App\Models\model_ulb_mstr;
use App\Models\model_prop_dtl;
use App\Models\model_prop_owner_detail;
use App\Models\model_prop_tax;
use App\Models\model_prop_demand;
use App\Models\model_prop_floor_details;
use App\Models\model_transaction;
use App\Models\model_tran_mode_mstr;
use App\Models\model_payment_adjustment;
use App\Models\model_fy_mstr;
use App\Models\model_tc_call;
use App\Models\model_cheque_details;
use App\Models\model_collection;
use App\Models\model_emp_details;
use App\Models\model_saf_dtl;
use App\Models\model_transaction_fine_rebet_details;
use App\Models\model_bank_recancilation;
use App\Models\model_penalty_dtl;
use App\Models\model_view_ward_permission;
use App\Models\model_datatable;


class Mobi extends MobiController
{
	protected $db;
	protected $dbSystem;
	//protected $db_name;
	protected $model_ward_mstr;
	protected $model_ulb_mstr;
	protected $model_prop_dtl;
	protected $model_prop_owner_detail;
	protected $model_prop_tax;
	protected $model_prop_demand;
	protected $model_prop_floor_details;
	protected $model_transaction;
	protected $model_tran_mode_mstr;
	protected $model_payment_adjustment;
	protected $model_fy_mstr;
	protected $model_tc_call;
	protected $model_cheque_details;
	protected $model_collection;
	protected $model_emp_details;
	protected $model_saf_dtl;
	protected $model_transaction_fine_rebet_details;

	protected $model;
	protected $modelUlb;
	protected $modelemp;
	protected $modelprop;
	protected $modelowner;
	protected $modeltax;
	protected $modeldemand;
	protected $modelfloor;
	protected $modelpay;
	protected $modeltran;
	protected $modeladjustment;
	protected $modelchqDD;
	protected $modelpropcoll;
	protected $modelassess;
	protected $modelpenalty;
	protected $model_bank_recancilation;
	protected $model_penalty_dtl;
	protected $model_view_ward_permission;
	protected $model_datatable;
	protected $model_dttable;

	public function __construct()
    {
        parent::__construct();
    	helper(['db_helper','form', 'utility_helper']);

        if($db_name = dbConfig("property")){

			$this->db = db_connect($db_name);
        }
		$this->model_datatable = new model_datatable($this->db);
    }

	function __destruct() {
		$this->db->close();
	}

	public function list_of_Property()
	{
		$data = $inputs = arrFilterSanitizeString($this->request->getVar());
		$session = Session();
		$emp_mstr = $session->get("emp_details");
        $emp_details_id = $emp_mstr["id"];
		
		if ((isset($data['forward']) && $data['forward'] != "") && (isset($data['cmd'])  && $data['cmd'] == "clr")) {
			$session->set('forward', $this->request->getVar('forward'));
			return $this->response->redirect(base_url('jsk/jsk_Search_Property'));
		}

		$wardWhere = "";
		$ownerWhere = "";
		$holdingWhere = "";
		if (isset($data['by_holding_owner_dtl']) && !in_array(strtoupper($data['by_holding_owner_dtl']), ["BY_15_HOLDING", "BY_HOLDING", "BY_OWNER", "BY_ADDRESS"])) {
			return $this->response->redirect(base_url('err/err'));
		}
		if (isset($inputs['by_holding_owner_dtl']) && $inputs['by_holding_owner_dtl']!='') {
			if ($data['ward_mstr_id'] != null && $data['ward_mstr_id']!='') {
				$wardWhere = " AND ward_mstr_id='".$data['ward_mstr_id']."'";
			}
			if (strtoupper($data['by_holding_owner_dtl'])=="BY_HOLDING") {
				$holdingWhere = " WHERE UPPER(holding_no)='".strtoupper($data['keyword'])."'";
			} else if (strtoupper($data['by_holding_owner_dtl'])=="BY_15_HOLDING") {
				$holdingWhere = " WHERE new_holding_no='".strtoupper($data['keyword'])."'";
			} else if (strtoupper($data['by_holding_owner_dtl'])=="BY_ADDRESS") {
				$holdingWhere = " WHERE prop_address ~* '" . $data['keyword'] . "'";
			} else {
				$ownerWhere = " AND (mobile_no::TEXT ~* '" . $data['keyword']."' OR owner_name ~* '".$data['keyword']."')";
			}
		}
		if ($wardWhere !='' || $holdingWhere != '' || $ownerWhere != '') {
			//$sql = "select * from view_prop_dtl_owner_ward_prop_type_ownership_type where 1=1 " . $where;
			$sql = "SELECT 
						tbl_prop_dtl.id AS prop_dtl_id,
						tbl_prop_dtl.prop_address,
						tbl_prop_dtl.holding_no,
						tbl_prop_dtl.new_holding_no,
						tbl_prop_dtl.khata_no,
						tbl_prop_dtl.plot_no,
						prop_owner.owner_name,
						prop_owner.mobile_no,
						view_ward_mstr.ward_no,
						tbl_prop_dtl.status
					FROM tbl_prop_dtl
					INNER JOIN view_ward_mstr ON tbl_prop_dtl.ward_mstr_id = view_ward_mstr.id ".$wardWhere."
					INNER JOIN (
						SELECT tbl_prop_owner_detail.prop_dtl_id,
							string_agg((tbl_prop_owner_detail.owner_name)::text, ', '::text) AS owner_name,
							string_agg((tbl_prop_owner_detail.mobile_no)::text, ', '::text) AS mobile_no
						FROM tbl_prop_owner_detail WHERE status=1 ".$ownerWhere."
						GROUP BY tbl_prop_owner_detail.prop_dtl_id
					) AS prop_owner ON prop_owner.prop_dtl_id = tbl_prop_dtl.id
					".$holdingWhere;
			$result = $this->model_datatable->getDatatable($sql);
			$data['posts'] = $result['result'];
			$data['offset'] = $result['offset'];
			$data['pager'] = $result['count'];
		}
		//print_var($data['result']);
		$sql = "SELECT 
					ward_permission.ward_mstr_id,
					ward_mstr.ward_no
				FROM tbl_ward_permission ward_permission
				JOIN tbl_ward_mstr ward_mstr ON ward_mstr.id = ward_permission.ward_mstr_id
				WHERE ward_permission.status = 1 AND ward_mstr.status = 1 AND emp_details_id = '".$emp_details_id."';";
		$this->model_view_ward_permission->getPermittedWard($emp_details_id);
		$this->model_view_ward_permission->getPermittedWard($emp_details_id);
		//$data['ward'] = $this->model_view_ward_permission->getPermittedWard($emp_details_id);
		return view('mobile/Property/Property/Property_List', $data);
	}
}
