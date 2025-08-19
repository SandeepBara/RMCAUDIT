<?php
namespace App\Controllers;
use App\Models\model_view_ward_permission;
use App\Models\model_datatable;

class WaterHarvestingTC extends MobiController
{
	protected $db;
	protected $dbSystem;
	protected $model_datatable;
	protected $model_view_ward_permission;
    public function __construct()
	{
        parent::__construct();
    	helper(["db_helper", "utility_helper", "form"]);
        if($db_name = dbConfig("property")) {
            $this->db = db_connect($db_name);
        }
		if ($db_name = dbSystem()) {
            $this->dbSystem = db_connect($db_name);
        }
		$this->model_datatable = new model_datatable($this->db);
		$this->model_view_ward_permission = new model_view_ward_permission($this->dbSystem);
    }

	function __destruct() {
		$this->db->close();
	}

	public function ATCList() {
		$data = [];
		$data = arrFilterSanitizeString($this->request->getVar());
		$session = session();
		$empDtl = $session->get("emp_details");
		$emp_details_id = $empDtl["id"];
		$data['wardList'] = $this->model_view_ward_permission->getPermittedWard($emp_details_id);
		$user_type_mstr_id = $empDtl["user_type_mstr_id"];
		$wardIds = array_map(function($val){return $val['ward_mstr_id'];},$data['wardList']);
		$wardIds = implode(',',$wardIds);
		$whereWard=" AND tbl_water_hrvesting_declaration_dtl.ward_mstr_id IN(".$wardIds.")";
		$where="";  
		if (isset($data["from_date"]) && isset($data["upto_date"]) && isset($data["ward_mstr_id"]) && isset($data["search_param"])) 
		{
			if ($data["from_date"]!="" && $data["upto_date"]!="") { 
				$where .= " AND tbl_water_harvesting_declaration_mail_inbox.created_on::date BETWEEN '".$data["from_date"]."' AND '".$data["upto_date"]."'";
				                
            }
            if ($data["ward_mstr_id"]!="") {
                $where .= " AND tbl_water_hrvesting_declaration_dtl.ward_mstr_id =".$data["ward_mstr_id"];                
            }
			else
			{
				$where .= $whereWard;
			}
            if ($data["search_param"]!="") {
                $where .= " AND (tbl_water_hrvesting_declaration_dtl.water_hrvting_application_no ILIKE '".$data["search_param"]."'
                                        OR tbl_water_hrvesting_declaration_dtl.owner_name ILIKE '%".$data["search_param"]."%'
                                        OR tbl_water_hrvesting_declaration_dtl.mobile_no ILIKE '%".$data["search_param"]."%')";
                          
            }
		}
		elseif($wardIds)
			$where.=$whereWard; 
		$sql = "SELECT
					tbl_water_harvesting_declaration_mail_inbox.id,
					tbl_water_hrvesting_declaration_dtl.water_hrvting_application_no,
					tbl_water_hrvesting_declaration_dtl.owner_name,
					tbl_water_hrvesting_declaration_dtl.guardian_name,
					tbl_water_hrvesting_declaration_dtl.mobile_no,
					tbl_water_hrvesting_declaration_dtl.ward_no,
					tbl_water_hrvesting_declaration_dtl.prop_address,
					tbl_water_hrvesting_declaration_dtl.remarks,
					tbl_water_harvesting_declaration_mail_inbox.receiver_user_type_mstr_id,
					tbl_water_harvesting_declaration_mail_inbox.subject,
					tbl_water_harvesting_declaration_mail_inbox.viewed,
					tbl_water_harvesting_declaration_mail_inbox.msg_body,
					tbl_water_harvesting_declaration_mail_inbox.created_on
				FROM tbl_water_harvesting_declaration_mail_inbox
				INNER JOIN tbl_water_hrvesting_declaration_dtl ON tbl_water_hrvesting_declaration_dtl.id=tbl_water_harvesting_declaration_mail_inbox.water_hrvesting_declaration_dtl_id
				WHERE 
					tbl_water_harvesting_declaration_mail_inbox.status=0 $where
					AND tbl_water_harvesting_declaration_mail_inbox.receiver_user_type_mstr_id=".$user_type_mstr_id;
		if ($result = $this->model_datatable->getDatatable($sql)) {
			$data['result'] = $result['result'];
			$data['offset'] = $result['offset'];
			$data['pager'] = $result['count'];
		}
		return view('property/water_harvesting_tc_list', $data);		
	}

	public function atc_fieldverify($water_harvesting_declaration_mail_inbox_id) {
		$data = [];
		$ulbDtl = getUlbDtl();
		$session = session();
		$empDtl = $session->get("emp_details");
		$emp_details_id = $empDtl["id"];
		$user_type_mstr_id = $empDtl["user_type_mstr_id"];
		if ($this->request->getMethod()=="post") {
			$inputs = $this->request->getVar();
			if ($inputs["verify_status"]=="VERIFY") {
				$this->db->table("tbl_water_harvesting_declaration_mail_inbox")
						->where("id", $water_harvesting_declaration_mail_inbox_id)
						->update([
							"status"=>1,
							"receiver_emp_details_id"=>$emp_details_id,
							"msg_body"=>$inputs["msg_body"]
						]);
				if ($this->db->table("tbl_water_harvesting_declaration_mail_inbox")
					->insert([
						"water_hrvesting_declaration_dtl_id"=>$inputs["water_hrvesting_declaration_dtl_id"],
						"subject"=>$inputs["subject"],
						"receiver_user_type_mstr_id"=>7,
						"created_on"=>date('Y-m-d H:i:s'),
						"sender_user_type_mstr_id"=>$user_type_mstr_id,
						"sender_emp_details_id"=>$emp_details_id,
						"sender_ip_address"=>getClientIpAddress(),
						"viewed"=>0,
						"status"=>0
					])) {
					flashToast("message", "Updated");
					return $this->response->redirect(base_url('WaterHarvestingTC/ATCList'));;
				}
			} else {

			}
			
		}
		$sql = "SELECT
					tbl_water_hrvesting_declaration_dtl.*,
					tbl_water_harvesting_declaration_mail_inbox.id AS water_harvesting_declaration_mail_inbox_id,
					tbl_water_harvesting_declaration_mail_inbox.subject,
					tbl_water_harvesting_declaration_mail_inbox.created_on AS forward_datetime
				FROM tbl_water_harvesting_declaration_mail_inbox
				INNER JOIN tbl_water_hrvesting_declaration_dtl ON tbl_water_hrvesting_declaration_dtl.id=tbl_water_harvesting_declaration_mail_inbox.water_hrvesting_declaration_dtl_id
				WHERE 
					tbl_water_harvesting_declaration_mail_inbox.status=0
					AND tbl_water_harvesting_declaration_mail_inbox.id=".$water_harvesting_declaration_mail_inbox_id;
		if ($data = $this->db->query($sql)->getFirstRow("array")) {
			//print_var($data);
		}
		$data["ulb_dtl"] = $ulbDtl;
		return view('property/water_harvesting_atc_view', $data);
	}


	public function UTCList() {
		$data = [];
		$session = session();
		$empDtl = $session->get("emp_details");
		$emp_details_id = $empDtl["id"];
		$user_type_mstr_id = $empDtl["user_type_mstr_id"];
		
		$data = arrFilterSanitizeString($this->request->getVar()); 
		$data['wardList'] = $this->model_view_ward_permission->getPermittedWard($emp_details_id);
        $permittedWard = array_column($data['wardList'], 'ward_mstr_id');
        $whereWard = " AND tbl_water_hrvesting_declaration_dtl.ward_mstr_id IN (".implode(',', $permittedWard).")";		
		$where="";        
        if (isset($data["from_date"]) && isset($data["upto_date"]) && isset($data["ward_mstr_id"]) && isset($data["search_param"])) 
        {
            if ($data["from_date"]!="" && $data["upto_date"]!="") { 
				$where .= " AND tbl_water_harvesting_declaration_mail_inbox.created_on::date BETWEEN '".$data["from_date"]."' AND '".$data["upto_date"]."'";
				                
            }
            if ($data["ward_mstr_id"]!="") {
                $where .= " AND tbl_water_hrvesting_declaration_dtl.ward_mstr_id =".$data["ward_mstr_id"];                
            }
			else
			{
				$where .= $whereWard;
			}
            if ($data["search_param"]!="") {
                $where .= " AND (tbl_water_hrvesting_declaration_dtl.water_hrvting_application_no ILIKE '".$data["search_param"]."'
                                        OR tbl_water_hrvesting_declaration_dtl.owner_name ILIKE '%".$data["search_param"]."%'
                                        OR tbl_water_hrvesting_declaration_dtl.mobile_no ILIKE '%".$data["search_param"]."%')";
                          
            }
        }
		else
		{
			$where .= $whereWard;
		}

		$sql = "SELECT
					tbl_water_harvesting_declaration_mail_inbox.id,
					tbl_water_hrvesting_declaration_dtl.water_hrvting_application_no,
					tbl_water_hrvesting_declaration_dtl.owner_name,
					tbl_water_hrvesting_declaration_dtl.guardian_name,
					tbl_water_hrvesting_declaration_dtl.mobile_no,
					tbl_water_hrvesting_declaration_dtl.ward_no,
					tbl_water_hrvesting_declaration_dtl.prop_address,
					tbl_water_hrvesting_declaration_dtl.remarks,
					tbl_water_harvesting_declaration_mail_inbox.receiver_user_type_mstr_id,
					tbl_water_harvesting_declaration_mail_inbox.subject,
					tbl_water_harvesting_declaration_mail_inbox.viewed,
					tbl_water_harvesting_declaration_mail_inbox.msg_body,
					tbl_water_harvesting_declaration_mail_inbox.created_on
				FROM tbl_water_harvesting_declaration_mail_inbox
				INNER JOIN tbl_water_hrvesting_declaration_dtl ON tbl_water_hrvesting_declaration_dtl.id=tbl_water_harvesting_declaration_mail_inbox.water_hrvesting_declaration_dtl_id
				WHERE 
					tbl_water_harvesting_declaration_mail_inbox.status=0 $where
					AND tbl_water_harvesting_declaration_mail_inbox.receiver_user_type_mstr_id=".$user_type_mstr_id;
		if ($result = $this->model_datatable->getDatatable($sql)) {
			$data['result'] = $result['result'];
			$data['offset'] = $result['offset'];
			$data['pager'] = $result['count'];
		}
		return view('property/water_harvesting_tc_list', $data);		
	}

	public function utc_fieldverify($water_harvesting_declaration_mail_inbox_id) {
		$data = [];
		$ulbDtl = getUlbDtl();
		$session = session();
		$empDtl = $session->get("emp_details");
		$emp_details_id = $empDtl["id"];
		$user_type_mstr_id = $empDtl["user_type_mstr_id"];
		if ($this->request->getMethod()=="post") {
			$inputs = $this->request->getVar();
			if ($inputs["verify_status"]=="VERIFY") { //// VERIFY
				$this->db->table("tbl_water_hrvesting_declaration_dtl")
						->where("id", $inputs["water_hrvesting_declaration_dtl_id"])
						->update([
							"status"=>1,
							"approval_status"=>1,
							"approved_by_emp_details_id"=>$emp_details_id,
							"approved_datetime"=>date('Y-m-d H:i:s'),
							"remarks"=>$inputs["msg_body"],
							"allow_update"=>0
						]);
				$this->db->table("tbl_water_harvesting_declaration_mail_inbox")
						->where("id", $water_harvesting_declaration_mail_inbox_id)
						->update([
							"status"=>1,
							"receiver_emp_details_id"=>$emp_details_id,
							"msg_body"=>$inputs["msg_body"]
						]);
				if ($this->db->table("tbl_water_harvesting_declaration_mail_inbox")
					->insert([
						"water_hrvesting_declaration_dtl_id"=>$inputs["water_hrvesting_declaration_dtl_id"],
						"subject"=>$inputs["subject"],
						"created_on"=>date('Y-m-d H:i:s'),
						"sender_user_type_mstr_id"=>$user_type_mstr_id,
						"sender_emp_details_id"=>$emp_details_id,
						"receiver_user_type_mstr_id"=>$user_type_mstr_id,
						"receiver_emp_details_id"=>$emp_details_id,
						"sender_ip_address"=>getClientIpAddress(),
						"msg_body"=>$inputs["msg_body"],
						"viewed"=>1,
						"status"=>1
					])) {
					$sql = "SELECT * FROM tbl_water_hrvesting_declaration_dtl WHERE id=".$inputs["water_hrvesting_declaration_dtl_id"];
					if ($waterHrvResult = $this->db->query($sql)->getFirstRow("array")) {
						$sql = "UPDATE tbl_prop_dtl SET is_water_harvesting=true WHERE id=".$waterHrvResult["prop_dtl_id"];
						$this->db->query($sql);
						$sql = "INSERT INTO  tbl_prop_tax (prop_dtl_id, fy_mstr_id, fyear, qtr, arv, holding_tax, additional_tax, quarterly_tax, created_on, status)
								SELECT 
									prop_dtl_id, fy_mstr_id, fyear, qtr, arv, holding_tax, 0, holding_tax, '".date("Y-m-d H:i:s")."', 1
								FROM tbl_prop_tax WHERE prop_dtl_id=".$waterHrvResult["prop_dtl_id"]." AND fyear='2022-2023' ORDER BY qtr DESC LIMIT 1";
						if($new_prop_tax_id = $this->db->query($sql)->insertID()) {
							$selectSql = "SELECT id FROM tbl_prop_demand WHERE fyear='2022-2023' AND qtr=1 AND paid_status=0";
							if ($resultSTMT = $this->db->query($selectSql)->getFirstRow("array")) {
								$updateSql = "UPDATE tbl_prop_demand SET amount=demand_amount, balance=demand_amount, additional_amount=0 WHERE prop_dtl_id=".$waterHrvResult["prop_dtl_id"]." AND id=".$resultSTMT["id"];
								$resultSTMT = $this->db->query($updateSql);
							}
							$selectSql = "SELECT id FROM tbl_prop_demand WHERE fyear='2022-2023' AND qtr=2 AND paid_status=0";
							if ($resultSTMT = $this->db->query($selectSql)->getFirstRow("array")) {
								$updateSql = "UPDATE tbl_prop_demand SET amount=demand_amount, balance=demand_amount, additional_amount=0 WHERE prop_dtl_id=".$waterHrvResult["prop_dtl_id"]." AND id=".$resultSTMT["id"];
								$resultSTMT = $this->db->query($updateSql);
							}
							$selectSql = "SELECT id FROM tbl_prop_demand WHERE fyear='2022-2023' AND qtr=3 AND paid_status=0";
							if ($resultSTMT = $this->db->query($selectSql)->getFirstRow("array")) {
								$updateSql = "UPDATE tbl_prop_demand SET amount=demand_amount, balance=demand_amount, additional_amount=0 WHERE prop_dtl_id=".$waterHrvResult["prop_dtl_id"]." AND id=".$resultSTMT["id"];
								$resultSTMT = $this->db->query($updateSql);
							}
							$selectSql = "SELECT id FROM tbl_prop_demand WHERE fyear='2022-2023' AND qtr=4 AND paid_status=0";
							if ($resultSTMT = $this->db->query($selectSql)->getFirstRow("array")) {
								$updateSql = "UPDATE tbl_prop_demand SET amount=demand_amount, balance=demand_amount, additional_amount=0 WHERE prop_dtl_id=".$waterHrvResult["prop_dtl_id"]." AND id=".$resultSTMT["id"];
								$resultSTMT = $this->db->query($updateSql);
							}
						}
					}
					flashToast("message", "Veriried");
					return $this->response->redirect(base_url('WaterHarvestingTC/UTCList'));
				}
			} else {  //// REJECTED
				$this->db->table("tbl_water_hrvesting_declaration_dtl")
						->where("id", $inputs["water_hrvesting_declaration_dtl_id"])
						->update([
							"status"=>2,
							"approval_status"=>2,
							"approved_by_emp_details_id"=>$emp_details_id,
							"approved_datetime"=>date('Y-m-d H:i:s'),
							"remarks"=>$inputs["msg_body"],
							"allow_update"=>0
						]);
				$this->db->table("tbl_water_harvesting_declaration_mail_inbox")
						->where("id", $water_harvesting_declaration_mail_inbox_id)
						->update([
							"status"=>2,
							"receiver_emp_details_id"=>$emp_details_id,
							"msg_body"=>$inputs["msg_body"]
						]);
				if ($this->db->table("tbl_water_harvesting_declaration_mail_inbox")
					->insert([
						"water_hrvesting_declaration_dtl_id"=>$inputs["water_hrvesting_declaration_dtl_id"],
						"subject"=>$inputs["subject"],
						"created_on"=>date('Y-m-d H:i:s'),
						"sender_user_type_mstr_id"=>$user_type_mstr_id,
						"sender_emp_details_id"=>$emp_details_id,
						"receiver_user_type_mstr_id"=>$user_type_mstr_id,
						"receiver_emp_details_id"=>$emp_details_id,
						"sender_ip_address"=>getClientIpAddress(),
						"msg_body"=>$inputs["msg_body"],
						"viewed"=>1,
						"status"=>2
					])) {
					flashToast("message", "Rejected");
					return $this->response->redirect(base_url('WaterHarvestingTC/UTCList'));;
				}
			}
			
		}
		$sql = "SELECT
					tbl_water_hrvesting_declaration_dtl.*,
					tbl_water_harvesting_declaration_mail_inbox.id AS water_harvesting_declaration_mail_inbox_id,
					tbl_water_harvesting_declaration_mail_inbox.subject,
					tbl_water_harvesting_declaration_mail_inbox.created_on AS forward_datetime
				FROM tbl_water_harvesting_declaration_mail_inbox
				INNER JOIN tbl_water_hrvesting_declaration_dtl ON tbl_water_hrvesting_declaration_dtl.id=tbl_water_harvesting_declaration_mail_inbox.water_hrvesting_declaration_dtl_id
				WHERE 
					tbl_water_harvesting_declaration_mail_inbox.status=0
					AND tbl_water_harvesting_declaration_mail_inbox.id=".$water_harvesting_declaration_mail_inbox_id;
		if ($data = $this->db->query($sql)->getFirstRow("array")) {
			$sql = "SELECT
						tbl_water_harvesting_declaration_mail_inbox.id,
						tbl_water_harvesting_declaration_mail_inbox.created_on,
						view_user_type_mstr.user_type,
						view_emp_details.emp_name,
						tbl_water_harvesting_declaration_mail_inbox.msg_body
					FROM tbl_water_harvesting_declaration_mail_inbox
					INNER JOIN view_user_type_mstr ON view_user_type_mstr.id=tbl_water_harvesting_declaration_mail_inbox.receiver_user_type_mstr_id
    				INNER JOIN view_emp_details ON view_emp_details.id=tbl_water_harvesting_declaration_mail_inbox.receiver_emp_details_id
					WHERE 
						tbl_water_harvesting_declaration_mail_inbox.status=1
						AND water_hrvesting_declaration_dtl_id=".$data["id"];
			if ($level_dtl = $this->db->query($sql)->getResultArray()) {
				$data["level_dtl"] = $level_dtl;
			}
		}
		$data["ulb_dtl"] = $ulbDtl;
		return view('property/water_harvesting_utc_view', $data);
	}
}
?>
