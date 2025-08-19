<?php
namespace App\Controllers;
use App\Models\model_ward_mstr;
use App\Models\model_datatable;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Exception;
use RuntimeException;

class WaterHarvesting extends AlphaController
{
	protected $db;
	protected $model_datatable;
    public function __construct()
	{
        parent::__construct();
    	helper(["db_helper", "utility_helper", "form"]);
        if($db_name = dbConfig("property")) {
            $this->db = db_connect($db_name);
        }
		$this->model_datatable = new model_datatable($this->db);
    }

	function __destruct() {
		$this->db->close();
	}

	public function declaration() {
		$data = [];
		$ulb_dtl = getUlbDtl();
		//print_var($ulb_dtl);
		if ($this->request->getMethod()=="post") {
			try {
				$this->db->transBegin();
				$session = session();
				$empDtl = $session->get("emp_details");
				$emp_details_id = $empDtl["id"];
				$user_type_mstr_id = $empDtl["user_type_mstr_id"];
				$data = $this->request->getVar();
				$sql = "SELECT COUNT(*) AS total_count FROM tbl_water_hrvesting_declaration_dtl;";
				$total_count = $this->db->query($sql)->getFirstRow("array")["total_count"];
				$total_count = str_pad(++$total_count, 5, "0", STR_PAD_LEFT);

				$water_hrvting_application_no = "WHD/".$total_count;
				if (!$this->db->table("tbl_water_hrvesting_declaration_dtl")
						->insert([
							"done_before_17_wh"=>$data["ck_water_harvesting"],
							"prop_dtl_id"=>$data["prop_dtl_id"],
							"saf_dtl_id"=>$data["saf_dtl_id"],
							"holding_saf_sam_no"=>$data["holding_saf_sam_no"],
							"water_hrvting_application_no"=>$water_hrvting_application_no,
							"owner_name"=>$data["owner_name"],
							"guardian_name"=>$data["guardian_name"],
							"mobile_no"=>$data["mobile_no"],
							"ward_mstr_id"=>$data["ward_mstr_id"],
							"ward_no"=>$data["ward_no"],
							"prop_address"=>$data["building_name_address"],
							"rmc_recommended_application_date"=>($data["ck_water_harvesting"]=="YES")?$data["application_date"]:NULL,
							"water_harvesting_completion_date"=>$data["date_of_water_harvesting"],
							"created_on"=>date("Y-m-d H:i:s"),
							"created_by_emp_details_id"=>$emp_details_id,
							"allow_update"=>1,
							"status"=>1
						])) {
							throw new RuntimeException("Opps something is wrong");
						}
				$water_hrvesting_declaration_dtl_id = $this->db->insertID();
				$upload_path = WRITEPATH."uploads/".$ulb_dtl["city"]."/water_harvesting";
				$upload_save_path = WRITEPATH."uploads/".$ulb_dtl["city"]."/water_harvesting";

				$water_harvesing_img = $this->request->getFile('water_harvesing_img');
				$file_ext = $water_harvesing_img->getExtension();
				$newName = $water_hrvesting_declaration_dtl_id."_water_harvesting_img.".$file_ext;
				$upload_save_path = $ulb_dtl["city"]."/water_harvesting"."/".$newName;
				if ($water_harvesing_img->move($upload_path, $newName)) {
					$this->db->table("tbl_water_hrvesting_declaration_dtl")
							->where("id", $water_hrvesting_declaration_dtl_id)
							->update(["water_harvesting_img"=>$upload_save_path]);
				} else {
					throw new RuntimeException("Water Harvesting Image is not uploaded");
				}
				$water_harvesing_form = $this->request->getFile('water_harvesing_form');
				$file_ext = $water_harvesing_form->getExtension();
				$newName = $water_hrvesting_declaration_dtl_id."_water_harvesting_form.".$file_ext;
				$upload_save_path = $ulb_dtl["city"]."/water_harvesting"."/".$newName;
				if ($water_harvesing_form->move($upload_path, $newName)) {
					$this->db->table("tbl_water_hrvesting_declaration_dtl")
							->where("id", $water_hrvesting_declaration_dtl_id)
							->update(["water_harvesting_form"=>$upload_save_path]);
				} else {
					throw new RuntimeException("Water Harvesting Form is not uploaded");
				}
				if ($data["ck_water_harvesting"]=="YES") {
					$water_harvesing_rmc_file = $this->request->getFile('water_harvesing_rmc_file');
					$file_ext = $water_harvesing_rmc_file->getExtension();
					$newName = $water_hrvesting_declaration_dtl_id."_rmc_recommended_file.".$file_ext;
					$upload_save_path = $ulb_dtl["city"]."/water_harvesting"."/".$newName;
					if ($water_harvesing_rmc_file->move($upload_path, $newName)) {
						$this->db->table("tbl_water_hrvesting_declaration_dtl")
							->where("id", $water_hrvesting_declaration_dtl_id)
							->update(["rmc_recommended_file"=>$upload_save_path]);
					} else {
						throw new RuntimeException("RMC Recommended File is not uploaded");
					}
				}
				$subject = "Reference No: ".$water_hrvting_application_no.", Name: ".$data["owner_name"];
				if ($this->db->table("tbl_water_harvesting_declaration_mail_inbox")
						->insert([
							"water_hrvesting_declaration_dtl_id"=>$water_hrvesting_declaration_dtl_id,
							"subject"=>$subject,
							"receiver_user_type_mstr_id"=>5,
							"created_on"=>date("Y-m-d"),
							"sender_user_type_mstr_id"=>$user_type_mstr_id,
							"sender_emp_details_id"=>$emp_details_id,
							"sender_ip_address"=>getClientIpAddress(),
							"viewed"=>0,
							"msg_body"=>"",
							"status"=>0
						])) {
							$this->db->transCommit();
							flashToast("message", "Your request is successfully submitted");
							return $this->response->redirect(base_url('WaterHarvesting/declaration/'));
						} else {
							throw new RuntimeException("Opps something is wrong");
						}
				
			} catch (RuntimeException $e) {
				$errMsg = $e->getMessage();
				echo $errMsg;
				flashToast("message", $errMsg);
				$this->db->transRollback();
			} catch (Exception $e) {
				$errMsg = $e->getMessage();
				echo $errMsg;
				flashToast("message", $errMsg);
				$this->db->transRollback();
			}
			
		}
		return view('property/water_harvesting_declaration', $data);
	}

	public function ajaxGetPropSafDtl() {

		$data = $this->request->getVar();
		if (isset($data["holding_saf_sam_no"])) {
			$holding_saf_sam_no = $data["holding_saf_sam_no"];
			$sql = "SELECT
						tbl_prop_dtl.id AS prop_dtl_id,
						tbl_saf_dtl.id AS saf_dtl_id,
						tbl_prop_dtl.ward_mstr_id,
						view_ward_mstr.ward_no,
						owner_dtl.owner_name,
						owner_dtl.guardian_name,
						owner_dtl.mobile_no,
						tbl_prop_dtl.prop_address
					FROM tbl_prop_dtl
					INNER JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_prop_dtl.saf_dtl_id
					INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
					INNER JOIN (
						SELECT
							prop_dtl_id,
							STRING_AGG(owner_name, ', ') AS owner_name,
							STRING_AGG(guardian_name, ', ') AS guardian_name,
							STRING_AGG(mobile_no::TEXT, ', ') AS mobile_no
						FROM tbl_prop_owner_detail
						WHERE status=1
						GROUP BY prop_dtl_id
					) AS owner_dtl ON owner_dtl.prop_dtl_id=tbl_prop_dtl.id
					WHERE 
						tbl_prop_dtl.new_holding_no ILIKE '".$holding_saf_sam_no."'
						OR tbl_saf_dtl.saf_no ILIKE '".$holding_saf_sam_no."'";
						//echo $sql;
						
						//return json_encode($sql);
			if ($result = $this->db->query($sql)->getFirstRow("array")) {
				return json_encode(['response'=>1, 'data'=>$result]);
			} else {
				return json_encode(['response'=>2, 'msg'=>$holding_saf_sam_no." -> Holding / SAF no not found."]);
			}
			print_var($this->db);
		} else {
			return json_encode(['response'=>0, 'msg'=>"Something is wrong"]);
		}
	}

	public function declarationList() {
		$from_date = $this->request->getVar('from_date');
		$upto_date = $this->request->getVar('upto_date');
		$search_param = $this->request->getVar('search_param');
		$where_param ="";
		if($this->request->getMethod()=='post'){
			if(!empty($search_param))
			{
				$where_param = " where (holding_saf_sam_no='".$search_param."' or water_hrvting_application_no='".$search_param."' or UPPER(owner_name) like UPPER('%".$search_param."%') or mobile_no='".$search_param."')";
			}else{
				$where_param = " where (tbl_water_hrvesting_declaration_dtl.created_on::date between '".$from_date."' and '".$upto_date."')";
			}
		}
		$data = [];
		$sql = "SELECT
					tbl_water_hrvesting_declaration_dtl.id,
					tbl_water_hrvesting_declaration_dtl.ward_no,
					tbl_water_hrvesting_declaration_dtl.holding_saf_sam_no,
					tbl_water_hrvesting_declaration_dtl.water_hrvting_application_no,
					tbl_water_hrvesting_declaration_dtl.owner_name,
					tbl_water_hrvesting_declaration_dtl.mobile_no,
					tbl_water_hrvesting_declaration_dtl.prop_address,
					tbl_water_hrvesting_declaration_dtl.water_harvesting_completion_date,
					tbl_water_hrvesting_declaration_dtl.created_on,
					CASE 
						WHEN tbl_water_hrvesting_declaration_dtl.approval_status=1 THEN 'Approved'
						WHEN tbl_water_hrvesting_declaration_dtl.status=2 THEN 'Rejected'
						WHEN wh_mail_dtl.receiver_user_type_mstr_id=5 THEN 'STC'
						WHEN wh_mail_dtl.receiver_user_type_mstr_id=7 THEN 'NTC'
						ELSE 'Pending'
					END AS current_status
				FROM tbl_water_hrvesting_declaration_dtl
				LEFT JOIN (
					SELECT
						tbl_water_harvesting_declaration_mail_inbox.id,
						tbl_water_harvesting_declaration_mail_inbox.water_hrvesting_declaration_dtl_id,
						tbl_water_harvesting_declaration_mail_inbox.subject,
						tbl_water_harvesting_declaration_mail_inbox.sender_user_type_mstr_id,
						tbl_water_harvesting_declaration_mail_inbox.sender_emp_details_id,
						tbl_water_harvesting_declaration_mail_inbox.receiver_user_type_mstr_id,
						tbl_water_harvesting_declaration_mail_inbox.receiver_emp_details_id,
						tbl_water_harvesting_declaration_mail_inbox.created_on,
						tbl_water_harvesting_declaration_mail_inbox.msg_body,
						tbl_water_harvesting_declaration_mail_inbox.status
					FROM tbl_water_harvesting_declaration_mail_inbox
					INNER JOIN (
						SELECT
							water_hrvesting_declaration_dtl_id,
							MAX(id) AS max_temp_id
						FROM tbl_water_harvesting_declaration_mail_inbox
						GROUP BY water_hrvesting_declaration_dtl_id
					) AS temp_dtl ON temp_dtl.max_temp_id=tbl_water_harvesting_declaration_mail_inbox.id
				) AS wh_mail_dtl ON wh_mail_dtl.water_hrvesting_declaration_dtl_id=tbl_water_hrvesting_declaration_dtl.id 
				". $where_param;
		if ($result = $this->model_datatable->getDatatable($sql)) {
			$data['result'] = $result['result'];
			$data['offset'] = $result['offset'];
			$data['pager'] = $result['count'];
			$data['from_date'] = $from_date;
			$data['upto_date'] = $upto_date;
			$data['search_param'] = $search_param;

		}
		return view('property/water_harvesting_declaration_list', $data);
	}

	public function declarationReportExcel($from_date=null, $upto_date=null, $search_param=null) {
		
		try
		{
			$where_param ="";
			if(isset($search_param) && $search_param !='ALL')
			{
				$where_param = " where (holding_saf_sam_no='".$search_param."' or water_hrvting_application_no='".$search_param."' or UPPER(owner_name) like UPPER('%".$search_param."%') or mobile_no='".$search_param."')";
			}
			if(isset($from_date) && isset($upto_date))
			{
				$where_param = " where (tbl_water_hrvesting_declaration_dtl.created_on::date between '".$from_date."' and '".$upto_date."')";
			}
			$data = [];
			$sql = "SELECT
						tbl_water_hrvesting_declaration_dtl.id,
						tbl_water_hrvesting_declaration_dtl.ward_no,
						tbl_water_hrvesting_declaration_dtl.holding_saf_sam_no,
						tbl_water_hrvesting_declaration_dtl.water_hrvting_application_no,
						tbl_water_hrvesting_declaration_dtl.owner_name,
						tbl_water_hrvesting_declaration_dtl.mobile_no,
						tbl_water_hrvesting_declaration_dtl.prop_address,
						tbl_water_hrvesting_declaration_dtl.water_harvesting_completion_date,
						tbl_water_hrvesting_declaration_dtl.created_on,
						CASE 
							WHEN tbl_water_hrvesting_declaration_dtl.approval_status=1 THEN 'Approved'
							WHEN tbl_water_hrvesting_declaration_dtl.status=2 THEN 'Rejected'
							WHEN wh_mail_dtl.receiver_user_type_mstr_id=5 THEN 'STC'
							WHEN wh_mail_dtl.receiver_user_type_mstr_id=7 THEN 'NTC'
							ELSE 'Pending'
						END AS current_status
					FROM tbl_water_hrvesting_declaration_dtl
					LEFT JOIN (
						SELECT
							tbl_water_harvesting_declaration_mail_inbox.id,
							tbl_water_harvesting_declaration_mail_inbox.water_hrvesting_declaration_dtl_id,
							tbl_water_harvesting_declaration_mail_inbox.subject,
							tbl_water_harvesting_declaration_mail_inbox.sender_user_type_mstr_id,
							tbl_water_harvesting_declaration_mail_inbox.sender_emp_details_id,
							tbl_water_harvesting_declaration_mail_inbox.receiver_user_type_mstr_id,
							tbl_water_harvesting_declaration_mail_inbox.receiver_emp_details_id,
							tbl_water_harvesting_declaration_mail_inbox.created_on,
							tbl_water_harvesting_declaration_mail_inbox.msg_body,
							tbl_water_harvesting_declaration_mail_inbox.status
						FROM tbl_water_harvesting_declaration_mail_inbox
						INNER JOIN (
							SELECT
								water_hrvesting_declaration_dtl_id,
								MAX(id) AS max_temp_id
							FROM tbl_water_harvesting_declaration_mail_inbox
							GROUP BY water_hrvesting_declaration_dtl_id
						) AS temp_dtl ON temp_dtl.max_temp_id=tbl_water_harvesting_declaration_mail_inbox.id
					) AS wh_mail_dtl ON wh_mail_dtl.water_hrvesting_declaration_dtl_id=tbl_water_hrvesting_declaration_dtl.id 
					". $where_param;
			
			$records = $this->model_datatable->getRecords($sql);
			$spreadsheet = new Spreadsheet();
            
            $activeSheet = $spreadsheet->getActiveSheet();
                            $activeSheet->setCellValue('A1', 'Ward No');
                            $activeSheet->setCellValue('B1', '15 Digits Holding No./SAF No.');
                            $activeSheet->setCellValue('C1', 'Reference No.');
                            $activeSheet->setCellValue('D1', 'Owner Name');
                            $activeSheet->setCellValue('E1', 'Mobile No.');
                            $activeSheet->setCellValue('F1', 'Address');
                            $activeSheet->setCellValue('G1', 'Water Harvesting Completion Date');
                            $activeSheet->setCellValue('H1', 'Apply Date');
                            $activeSheet->setCellValue('I1', 'Current Status');
                            $activeSheet->fromArray($records, NULL, 'A2');
            $spreadsheet->getActiveSheet()->getStyle('B')->getNumberFormat()
                        ->setFormatCode(NumberFormat::FORMAT_TEXT);
            $filename = "Water_Harvesting_Declaration_List.xlsx";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $writer = new Xlsx($spreadsheet);
            ob_end_clean();
            $writer->save('php://output');
		}catch(Exception $e){
            print_r($e);
        }
	}

	public function declaration_view($whid) {
		$data = [];
		$sql = "SELECT
					*
				FROM tbl_water_hrvesting_declaration_dtl 
				WHERE 
					id=".$whid;
		if ($data = $this->db->query($sql)->getFirstRow("array")) {
			$sql = "SELECT
						tbl_water_harvesting_declaration_mail_inbox.id,
						tbl_water_harvesting_declaration_mail_inbox.subject,
						sender_user_type_mstr_id,
						sender_emp_details_id,
						receiver_user_type_mstr_id,
						receiver_emp_details_id,
						view_user_type_mstr.user_type,
						view_emp_details.emp_name,
						tbl_water_harvesting_declaration_mail_inbox.msg_body,
						tbl_water_harvesting_declaration_mail_inbox.created_on
					FROM tbl_water_harvesting_declaration_mail_inbox
					INNER JOIN view_user_type_mstr ON view_user_type_mstr.id=tbl_water_harvesting_declaration_mail_inbox.receiver_user_type_mstr_id
    				LEFT JOIN view_emp_details ON view_emp_details.id=tbl_water_harvesting_declaration_mail_inbox.receiver_emp_details_id
					WHERE
						water_hrvesting_declaration_dtl_id=".$whid."
						AND receiver_user_type_mstr_id!=sender_user_type_mstr_id
					ORDER BY tbl_water_harvesting_declaration_mail_inbox.id ASC";
			if ($level_result = $this->db->query($sql)->getResultArray()) {
				$data["level_remarks_result"] = $level_result;
			}
			//print_var($data);
			//die();
			return view('property/water_harvesting_declaration_view', $data);
		}
		
	}


}
?>
