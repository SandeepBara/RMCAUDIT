<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PostModel;
use App\Controllers\SAF\SAFHelper;
use App\Controllers\SAF\NEW_SAFHelper;
use App\Models\model_ward_mstr;
use App\Models\model_ulb_mstr;
use App\Models\model_prop_dtl;
use App\Models\model_prop_owner_detail;
use App\Models\model_prop_floor_details;
use App\Models\model_prop_demand;
use App\Models\model_fy_mstr;
use App\Models\model_emp_details;
use App\Models\model_saf_dtl;
use App\Models\model_view_ward_permission;
use App\Models\model_datatable;
use App\Models\model_view_saf_dtl;
use App\Models\model_saf_owner_detail;
use App\Models\model_saf_doc_dtl;
use App\Models\model_doc_mstr;
use App\Models\model_level_trust_doc_dtl;
use App\Models\model_field_verification_dtl;
use App\Models\model_field_verification_floor_details;
use App\Models\model_apartment_details;

class TrustList extends MobiController
{
	protected $db;
	protected $dbSystem;
	//protected $db_name;
	protected $model_ward_mstr;
	protected $model_ulb_mstr;
	protected $model_prop_dtl;
	protected $model_prop_owner_detail;
	protected $model_prop_demand;
	protected $model_prop_floor_details;
	protected $model_fy_mstr;
	protected $model_emp_details;
	protected $model_saf_dtl;
	protected $model_transaction_fine_rebet_details;

	protected $model;
	protected $modelUlb;
	protected $modelemp;
	protected $modelprop;
	protected $modelowner;
	protected $modelfloor;
	protected $modelassess;
	protected $model_view_ward_permission;
	protected $model_datatable;
	protected $model_dttable;
	protected $model_view_saf_dtl;
	protected $model_saf_owner_detail;
	protected $model_saf_doc_dtl;
	protected $model_doc_mstr;
	protected $model_level_trust_doc_dtl;
	protected $model_field_verification_dtl;
    protected $model_field_verification_floor_details;
	protected $model_apartment_details;

	public function __construct()
    {
		/*ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);*/
        parent::__construct();
    	helper(['db_helper','form', 'utility_helper']);

        if($db_name = dbConfig("property")){

			$this->db = db_connect($db_name);
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system);
        }

		$this->model = new model_ward_mstr($this->dbSystem);
		$this->modelUlb = new model_ulb_mstr($this->dbSystem);
		$this->modelemp = new model_emp_details($this->dbSystem);
		$this->model_fy_mstr = new model_fy_mstr($this->dbSystem);
		$this->model_prop_dtl = new model_prop_dtl($this->db);
		$this->model_prop_owner_detail = new model_prop_owner_detail($this->db);
		$this->model_prop_floor_details = new model_prop_floor_details($this->db);
		$this->model_prop_demand = new model_prop_demand($this->db);
		$this->modelassess = new model_saf_dtl($this->db);
		$this->model_view_ward_permission = new model_view_ward_permission($this->dbSystem);
		$this->model_datatable = new model_datatable($this->db);
		$this->model_dttable = new model_datatable($this->db);
		$this->model_view_saf_dtl = new model_view_saf_dtl($this->db);
		$this->model_saf_owner_detail = new model_saf_owner_detail($this->db);
		$this->model_saf_doc_dtl = new model_saf_doc_dtl($this->db);
		$this->model_doc_mstr = new model_doc_mstr($this->db);
		$this->model_level_trust_doc_dtl = new model_level_trust_doc_dtl($this->db);
		$this->model_field_verification_dtl = new model_field_verification_dtl($this->db);
        $this->model_field_verification_floor_details = new model_field_verification_floor_details($this->db);
		$this->model_apartment_details = new model_apartment_details($this->db);
    }

	public function index()
	{
		$data = (array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $emp_details_id = $emp_mstr["id"];
        $user_type_mstr_id = $emp_mstr["user_type_mstr_id"];


        $data = arrFilterSanitizeString($this->request->getVar());
        //$empward = $this->model_ward_permission->getWardDataByEmpdetailsId($emp_details_id);
        $show_data = limitInPagination();
        if(isset($_GET['page'])) {
            $page = intval($_GET['page'])-1;
            if($page<0) $page = 0;
        } else {
            $page = 0;
        }
        $start_page = $page*$show_data;

        $limitSql = " LIMIT $show_data OFFSET $start_page";

        $data['wardList'] = $this->model_view_ward_permission->getPermittedWard($emp_details_id);
        $permittedWard = array_column($data['wardList'], 'ward_mstr_id');
        $whereDateRange = "";
        $whereWard = " AND tbl_saf_dtl.ward_mstr_id IN (".implode(',', $permittedWard).")";

        $whereSearchPrm = "";
        if (isset($data["from_date"]) && isset($data["upto_date"]) && isset($data["ward_mstr_id"]) && isset($data["search_param"])) {
            if ($data["from_date"]!="" && $data["upto_date"]!="") {
                $whereDateRange = " AND tbl_saf_dtl.apply_date BETWEEN '".$data["from_date"]."' AND '".$data["upto_date"]."'";
            }
            if ($data["ward_mstr_id"]!="") {
                $whereWard = " AND tbl_saf_dtl.ward_mstr_id IN (".$data["ward_mstr_id"].")";
            }
            if ($data["search_param"]!="") {
                $whereSearchPrm = " AND (trust_dtl.saf_no ILIKE '".$data["search_param"]."'
                                        OR trust_dtl.owner_name ILIKE '%".$data["search_param"]."%'
                                        OR trust_dtl.mobile_no ILIKE '%".$data["search_param"]."%'
										OR tbl_prop_dtl.new_holding_no ILIKE '%".$data["search_param"]."%')";
            }
        }

        $sql = "WITH trust_dtl AS (
                    SELECT 
						tbl_saf_dtl.id as saf_dtl_id, 
						tbl_saf_dtl.ward_mstr_id, 
						tbl_saf_dtl.saf_no, 
						tbl_saf_dtl.prop_type_mstr_id, 
						owner_dtl.owner_name, 
						owner_dtl.mobile_no, 
						tbl_saf_dtl.assessment_type, 
						tbl_saf_dtl.prop_address, 
						tbl_saf_dtl.apply_date, 
						count(*) OVER() AS full_count 
					FROM tbl_saf_dtl 
					INNER JOIN ( 
						SELECT tbl_saf_owner_detail.saf_dtl_id, 
							string_agg(tbl_saf_owner_detail.owner_name::text, ', '::text) AS owner_name, 
							string_agg(tbl_saf_owner_detail.mobile_no::text, ', '::text) AS mobile_no 
						FROM tbl_saf_owner_detail 
						GROUP BY tbl_saf_owner_detail.saf_dtl_id 
					) owner_dtl ON owner_dtl.saf_dtl_id = tbl_saf_dtl.id 
					INNER JOIN ( 
						SELECT saf_dtl_id FROM tbl_saf_floor_details where usage_type_mstr_id in(43,12) and status=1 group by saf_dtl_id 
					) tbl_saf_floor_details on tbl_saf_floor_details.saf_dtl_id=tbl_saf_dtl.id 
					LEFT JOIN (
						SELECT saf_dtl_id FROM tbl_saf_doc_dtl where other_doc='trust_document' and verify_status=1 group by saf_dtl_id
					) tbl_saf_doc_dtl on tbl_saf_doc_dtl.saf_dtl_id=tbl_saf_dtl.id 
                    WHERE
                        tbl_saf_dtl.payment_status=1 ".$whereDateRange."
						AND tbl_saf_dtl.status=1 ".$whereWard."
						AND tbl_saf_doc_dtl.saf_dtl_id is null
						AND tbl_saf_dtl.trust_type is null
                    ORDER BY tbl_saf_dtl.id DESC 
                )
			SELECT trust_dtl.*, tbl_prop_type_mstr.property_type, 
			tbl_prop_dtl.new_holding_no, view_ward_mstr.ward_no 
			FROM trust_dtl 
			INNER JOIN view_ward_mstr ON view_ward_mstr.id = trust_dtl.ward_mstr_id 
			LEFT JOIN tbl_prop_dtl ON tbl_prop_dtl.saf_dtl_id=trust_dtl.saf_dtl_id 
			INNER JOIN tbl_prop_type_mstr ON tbl_prop_type_mstr.id=trust_dtl.prop_type_mstr_id".$whereSearchPrm.$limitSql."";
		$resultBuilder = $this->db->query($sql);
        $total_count = $resultBuilder->getFirstRow("array")['full_count']??0;
        $result = [
            'result' => $resultBuilder->getResultArray(),
            'count' => $total_count,
            'offset' => $start_page,
        ];
		
		$data['posts'] = $result['result'];
		$data['leveldetails'] = $data['posts'];
		$data['pager'] = $result['count'];
		
		return view('mobile/school_trust_list', $data);
	}

	public function viewDetails($saf_dtl_id) {
		$Session = Session();
        $ulb_dtl = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_dtl["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $emp_details_id = $emp_mstr["id"];

        $data = $this->model_view_saf_dtl->getSafDtlBySafDtlId($saf_dtl_id);
		$data['saf_owner_detail'] = $this->model_saf_owner_detail->getOwnerdtlBySAFId(["saf_dtl_id" => $data['saf_dtl_id']]);
		// applicant img & document
		foreach ($data['saf_owner_detail'] as $key => $owner_detail) {
			$input = [
				'saf_dtl_id' => $data['saf_dtl_id'],
				'saf_owner_dtl_id' => $owner_detail['id'],
			];
			$data['saf_owner_detail'][$key]['applicant_img_dtl'] = $this->model_saf_doc_dtl->getApplicantImgBySafDtlAndSafOwnerDtlId($input);
			$data['saf_owner_detail'][$key]['applicant_doc_dtl'] = $this->model_saf_doc_dtl->getApplicantDocBySafDtlAndSafOwnerDtlId($input);
		}
		
		//$data["doc_list"] = $this->model_saf_doc_dtl->getAllDocumentsWithOwner2($data['saf_dtl_id']);
		if($this->request->getMethod()=='post')
		{ 
			$inputs = arrFilterSanitizeString($this->request->getVar());
			if (isset($inputs['btn_upload']))
			{
				$rules = [
					'upld_doc_path'=>'uploaded[upld_doc_path]|max_size[upld_doc_path,5120]|ext_in[upld_doc_path,pdf]',
				];
				if($this->validate($rules))
				{
					$upld_doc_path = $this->request->getFile('upld_doc_path');
					if ($upld_doc_path->IsValid()  && !$upld_doc_path->hasMoved())
					{
						try
						{
							$this->db->transBegin();
							$input = [
									'saf_dtl_id'=> $data['saf_dtl_id'],
									'upld_doc_mstr_id'=> $inputs['doc_mstr_id'],
									'doc_mstr_idcheck'=> $inputs['doc_mstr_id'],
									'other_doc'=> $inputs['other_doc'],
									'other_doccheck'=> $inputs['other_doc'],
									'emp_details_id'=> $emp_details_id,
									'created_on'=> "NOW()",
									'status'=> 1,
								];
							if($saf_doc_dtl_id = $this->model_saf_doc_dtl->checkDocDataIsExist1($input))
							{
								$delete_path = WRITEPATH.'uploads/'.$saf_doc_dtl_id['doc_path'];
								// unlink($delete_path);
								deleteFile($delete_path);

								$newFileName = md5($saf_doc_dtl_id['id']);
								$file_ext = $upld_doc_path->getExtension();

								$path = $ulb_dtl['city']."/"."saf_doc_dtl";
								$upld_doc_path->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext);
								$upld_doc_path_save = $path."/".$newFileName.'.'.$file_ext;
								$this->model_saf_doc_dtl->updatetransdocpathById($saf_doc_dtl_id['id'], $upld_doc_path_save,$input['upld_doc_mstr_id']);

							}
							else if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->insertFrData($input))
							{
								$newFileName = md5($saf_doc_dtl_id);
								$file_ext = $upld_doc_path->getExtension();
								$path = $ulb_dtl['city']."/"."saf_doc_dtl";

								$upld_doc_path->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext);
								$upld_doc_path_save = $path."/".$newFileName.'.'.$file_ext;
								$this->model_saf_doc_dtl->updatetransdocpathById($saf_doc_dtl_id, $upld_doc_path_save, $input['upld_doc_mstr_id']);
							}

							if ($this->db->transStatus() === FALSE)
							{
								$this->db->transRollback();
								flashToast("message", "Oops, Document not uploaded.");
							}
							else
							{
								$this->db->transCommit();
								flashToast("message", "Document uploaded successfully.");
							}
						}
						catch (Exception $e)
						{
							flashToast("message", $e->getMessage());
						}
					}
				}
				else
				{

					$errMsg = $this->validator->listErrors();
					flashToast("message", $errMsg);
				}
			}
			if(isset($inputs['send_to_ulb']))
			{
				$this->db->transBegin();
				$saf_up_date = $this->db->table('tbl_saf_dtl')->set('trust_type', $inputs['is_trust_school'])
								->where('id', $saf_dtl_id)
								->update();
				if($saf_up_date)
				{

				
					$leveldata = [
						'saf_dtl_id' => $saf_dtl_id,
						'sender_user_type_id' => $emp_mstr['user_type_mstr_id'],
						'receiver_user_type_id' => 9,
						'forward_date' => date('Y-m-d'),
						'forward_time' => date('H:i:s'),
						'created_on' =>date('Y-m-d H:i:s'),
						'remarks' => '',
						'verification_status' => 0,
						'sender_emp_details_id'=> $emp_details_id
					];
					

					$this->model_level_trust_doc_dtl->insertData($leveldata);
					$this->trustDemandGenerate($saf_dtl_id, $inputs['is_trust_school']);
					if ($this->db->transStatus() === FALSE)
					{
						$this->db->transRollback();
						flashToast("message", "Oops, Application couldn't send to ULB.");
					}
					else
					{
						$this->db->transCommit();
						flashToast("message", "Application sent to ULB.");
						return $this->response->redirect(base_url('TrustList/index'));
					}
				}
			}
		}
		$data["doc_list"] = $this->model_doc_mstr->getDataByDocTypeTrust(['trust_document', 'income_tax']);
		$data["uploaded_doc_list"]=$this->model_saf_doc_dtl->getAllTrustDocuments($data['saf_dtl_id']);
		return view('mobile/trust_certificate_upload', $data);
    }

	// public function send_rmc()
	// {
	// 	$Session = Session();
    //     $ulb_dtl = $Session->get("ulb_dtl");
    //     $data['ulb_mstr_id'] = $ulb_dtl["ulb_mstr_id"];
    //     $emp_mstr = $Session->get("emp_details");
    //     $emp_details_id = $emp_mstr["id"];
	// 	$inputs = arrFilterSanitizeString($this->request->getVar());
	// 	$saf_dtl_id = $inputs['saf_dtl_id'];

	// 	$this->db->transBegin();
	// 	$saf_up_date = $this->db->table('tbl_saf_dtl')->set('trust_type', $inputs['is_trust_school'])
	// 					->where('id', $saf_dtl_id)
    //                     ->update();
	// 	if($saf_up_date)
	// 	{

		
	// 		$leveldata = [
	// 			'saf_dtl_id' => $saf_dtl_id,
	// 			'sender_user_type_id' => $emp_mstr['user_type_mstr_id'],
	// 			'receiver_user_type_id' => 9,
	// 			'forward_date' => date('Y-m-d'),
	// 			'forward_time' => date('H:i:s'),
	// 			'created_on' =>date('Y-m-d H:i:s'),
	// 			'remarks' => '',
	// 			'verification_status' => 0,
	// 			'sender_emp_details_id'=> $emp_details_id
	// 		];
			

	// 		$this->model_level_trust_doc_dtl->insertData($leveldata);
	// 		$this->trustDemandGenerate($saf_dtl_id, $inputs['is_trust_school']);
	// 		if ($this->db->transStatus() === FALSE)
	// 		{
	// 			$this->db->transRollback();
	// 			flashToast("message", "Oops, Application couldn't send to ULB.");
	// 			return $this->response->redirect(base_url('TrustList/viewDetails/'.$saf_dtl_id));
	// 		}
	// 		else
	// 		{
	// 			$this->db->transCommit();
	// 			flashToast("message", "Application sent to ULB.");
	// 			return $this->response->redirect(base_url('TrustList/index'));
	// 		}
	// 	}
	// }


	public function jskIndex()
	{
		$data = (array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $emp_details_id = $emp_mstr["id"];
        $user_type_mstr_id = $emp_mstr["user_type_mstr_id"];


        $data = arrFilterSanitizeString($this->request->getVar());
        //$empward = $this->model_ward_permission->getWardDataByEmpdetailsId($emp_details_id);
        $show_data = limitInPagination();
        if(isset($_GET['page'])) {
            $page = intval($_GET['page'])-1;
            if($page<0) $page = 0;
        } else {
            $page = 0;
        }
        $start_page = $page*$show_data;

        $limitSql = " LIMIT $show_data OFFSET $start_page";

        $data['wardList'] = $this->model_view_ward_permission->getPermittedWard($emp_details_id);
        $permittedWard = array_column($data['wardList'], 'ward_mstr_id');
        $whereDateRange = "";
        $whereWard = " AND tbl_saf_dtl.ward_mstr_id IN (".implode(',', $permittedWard).")";

        $whereSearchPrm = "";
        if (isset($data["from_date"]) && isset($data["upto_date"]) && isset($data["ward_mstr_id"]) && isset($data["search_param"])) {
            if ($data["from_date"]!="" && $data["upto_date"]!="") {
                $whereDateRange = " AND tbl_saf_dtl.apply_date BETWEEN '".$data["from_date"]."' AND '".$data["upto_date"]."'";
            }
            if ($data["ward_mstr_id"]!="") {
                $whereWard = " AND tbl_saf_dtl.ward_mstr_id IN (".$data["ward_mstr_id"].")";
            }
            if ($data["search_param"]!="") {
                $whereSearchPrm = " AND (trust_dtl.saf_no ILIKE '".$data["search_param"]."'
                                        OR trust_dtl.owner_name ILIKE '%".$data["search_param"]."%'
                                        OR trust_dtl.mobile_no ILIKE '%".$data["search_param"]."%'
										OR tbl_prop_dtl.new_holding_no ILIKE '%".$data["search_param"]."%')";
            }
        }

        $sql = "WITH trust_dtl AS (
                    SELECT 
						tbl_saf_dtl.id as saf_dtl_id, 
						tbl_saf_dtl.ward_mstr_id, 
						tbl_saf_dtl.saf_no, 
						tbl_saf_dtl.prop_type_mstr_id, 
						owner_dtl.owner_name, 
						owner_dtl.mobile_no, 
						tbl_saf_dtl.assessment_type, 
						tbl_saf_dtl.prop_address, 
						tbl_saf_dtl.apply_date, 
						count(*) OVER() AS full_count 
					FROM tbl_saf_dtl 
					INNER JOIN ( 
						SELECT tbl_saf_owner_detail.saf_dtl_id, 
							string_agg(tbl_saf_owner_detail.owner_name::text, ', '::text) AS owner_name, 
							string_agg(tbl_saf_owner_detail.mobile_no::text, ', '::text) AS mobile_no 
						FROM tbl_saf_owner_detail 
						GROUP BY tbl_saf_owner_detail.saf_dtl_id 
					) owner_dtl ON owner_dtl.saf_dtl_id = tbl_saf_dtl.id 
					INNER JOIN ( 
						SELECT saf_dtl_id FROM tbl_saf_floor_details where usage_type_mstr_id in(43,12) and status=1 group by saf_dtl_id 
					) tbl_saf_floor_details on tbl_saf_floor_details.saf_dtl_id=tbl_saf_dtl.id 
					LEFT JOIN (
						SELECT saf_dtl_id FROM tbl_saf_doc_dtl where other_doc='trust_document' and verify_status=1 group by saf_dtl_id
					) tbl_saf_doc_dtl on tbl_saf_doc_dtl.saf_dtl_id=tbl_saf_dtl.id 
                    WHERE
                        tbl_saf_dtl.payment_status=1 ".$whereDateRange."
						AND tbl_saf_dtl.status=1 ".$whereWard."
						AND tbl_saf_doc_dtl.saf_dtl_id is null
						AND tbl_saf_dtl.trust_type is null
                    ORDER BY tbl_saf_dtl.id DESC 
                )
			SELECT trust_dtl.*, tbl_prop_type_mstr.property_type, 
			tbl_prop_dtl.new_holding_no, view_ward_mstr.ward_no 
			FROM trust_dtl 
			INNER JOIN view_ward_mstr ON view_ward_mstr.id = trust_dtl.ward_mstr_id 
			LEFT JOIN tbl_prop_dtl ON tbl_prop_dtl.saf_dtl_id=trust_dtl.saf_dtl_id 
			INNER JOIN tbl_prop_type_mstr ON tbl_prop_type_mstr.id=trust_dtl.prop_type_mstr_id".$whereSearchPrm.$limitSql."";
		$resultBuilder = $this->db->query($sql);
        $total_count = $resultBuilder->getFirstRow("array")['full_count']??0;
        $result = [
            'result' => $resultBuilder->getResultArray(),
            'count' => $total_count,
            'offset' => $start_page,
        ];
		
		$data['posts'] = $result['result'];
		$data['leveldetails'] = $data['posts'];
		$data['pager'] = $result['count'];
		
		return view('property/jsk_school_trust', $data);
	}

	public function jskViewDetails($saf_dtl_id) {
		$Session = Session();
        $ulb_dtl = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_dtl["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $emp_details_id = $emp_mstr["id"];

        $data = $this->model_view_saf_dtl->getSafDtlBySafDtlId($saf_dtl_id);
		$data['saf_owner_detail'] = $this->model_saf_owner_detail->getOwnerdtlBySAFId(["saf_dtl_id" => $data['saf_dtl_id']]);
		// applicant img & document
		foreach ($data['saf_owner_detail'] as $key => $owner_detail) {
			$input = [
				'saf_dtl_id' => $data['saf_dtl_id'],
				'saf_owner_dtl_id' => $owner_detail['id'],
			];
			$data['saf_owner_detail'][$key]['applicant_img_dtl'] = $this->model_saf_doc_dtl->getApplicantImgBySafDtlAndSafOwnerDtlId($input);
			$data['saf_owner_detail'][$key]['applicant_doc_dtl'] = $this->model_saf_doc_dtl->getApplicantDocBySafDtlAndSafOwnerDtlId($input);
		}
		
		//$data["doc_list"] = $this->model_saf_doc_dtl->getAllDocumentsWithOwner2($data['saf_dtl_id']);
		if($this->request->getMethod()=='post')
		{ 
			$inputs = arrFilterSanitizeString($this->request->getVar());
			if (isset($inputs['btn_upload']))
			{
				$rules = [
					'upld_doc_path'=>'uploaded[upld_doc_path]|max_size[upld_doc_path,5120]|ext_in[upld_doc_path,pdf]',
				];
				if($this->validate($rules))
				{
					$upld_doc_path = $this->request->getFile('upld_doc_path');
					if ($upld_doc_path->IsValid()  && !$upld_doc_path->hasMoved())
					{
						try
						{
							$this->db->transBegin();
							$input = [
									'saf_dtl_id'=> $data['saf_dtl_id'],
									'upld_doc_mstr_id'=> $inputs['doc_mstr_id'],
									'doc_mstr_idcheck'=> $inputs['doc_mstr_id'],
									'other_doc'=> $inputs['other_doc'],
									'other_doccheck'=> $inputs['other_doc'],
									'emp_details_id'=> $emp_details_id,
									'created_on'=> "NOW()",
									'status'=> 1,
								];
							if($saf_doc_dtl_id = $this->model_saf_doc_dtl->checkDocDataIsExist1($input))
							{
								$delete_path = WRITEPATH.'uploads/'.$saf_doc_dtl_id['doc_path'];
								// unlink($delete_path);
								deleteFile($delete_path);

								$newFileName = md5($saf_doc_dtl_id['id']);
								$file_ext = $upld_doc_path->getExtension();

								$path = $ulb_dtl['city']."/"."saf_doc_dtl";
								$upld_doc_path->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext);
								$upld_doc_path_save = $path."/".$newFileName.'.'.$file_ext;
								$this->model_saf_doc_dtl->updatetransdocpathById($saf_doc_dtl_id['id'], $upld_doc_path_save,$input['upld_doc_mstr_id']);

							}
							else if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->insertFrData($input))
							{
								$newFileName = md5($saf_doc_dtl_id);
								$file_ext = $upld_doc_path->getExtension();
								$path = $ulb_dtl['city']."/"."saf_doc_dtl";

								$upld_doc_path->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext);
								$upld_doc_path_save = $path."/".$newFileName.'.'.$file_ext;
								$this->model_saf_doc_dtl->updatetransdocpathById($saf_doc_dtl_id, $upld_doc_path_save, $input['upld_doc_mstr_id']);
							}

							if ($this->db->transStatus() === FALSE)
							{
								$this->db->transRollback();
								flashToast("message", "Oops, Document not uploaded.");
							}
							else
							{
								$this->db->transCommit();
								flashToast("message", "Document uploaded successfully.");
							}
						}
						catch (Exception $e)
						{
							flashToast("message", $e->getMessage());
						}
					}
				}
				else
				{

					$errMsg = $this->validator->listErrors();
					flashToast("message", $errMsg);
				}
			}
			if(isset($inputs['send_to_ulb']))
			{
				$this->db->transBegin();
				$saf_up_date = $this->db->table('tbl_saf_dtl')->set('trust_type', $inputs['is_trust_school'])
								->where('id', $saf_dtl_id)
								->update();
				if($saf_up_date)
				{

				
					$leveldata = [
						'saf_dtl_id' => $saf_dtl_id,
						'sender_user_type_id' => $emp_mstr['user_type_mstr_id'],
						'receiver_user_type_id' => 9,
						'forward_date' => date('Y-m-d'),
						'forward_time' => date('H:i:s'),
						'created_on' =>date('Y-m-d H:i:s'),
						'remarks' => '',
						'verification_status' => 0,
						'sender_emp_details_id'=> $emp_details_id
					];
					

					$this->model_level_trust_doc_dtl->insertData($leveldata);
					$this->trustDemandGenerate($saf_dtl_id, $inputs['is_trust_school']);
					if ($this->db->transStatus() === FALSE)
					{
						$this->db->transRollback();
						flashToast("message", "Oops, Application couldn't send to ULB.");
					}
					else
					{
						$this->db->transCommit();
						flashToast("message", "Application sent to ULB.");
						return $this->response->redirect(base_url('TrustList/jskIndex'));
					}
				}
			}
		}
		$data["doc_list"] = $this->model_doc_mstr->getDataByDocTypeTrust(['trust_document', 'income_tax']);
		$data["uploaded_doc_list"]=$this->model_saf_doc_dtl->getAllTrustDocuments($data['saf_dtl_id']);
		return view('property/jsk_trust_view', $data);
    }

	public function makeDueDateByFyearQtr($fyear, $qtr)
	{
		list($fyear1, $fyear2) = explode("-", $fyear);
		if ($qtr == 1) {
			return $fyear1 . "-06-30";
		} else if ($qtr == 2) {
			return $fyear1 . "-09-30";
		} else if ($qtr == 3) {
			return $fyear1 . "-12-31";
		} else if ($qtr == 4) {
			return $fyear2 . "-03-31";
		}
	}

	public function getFyID($FY)
	{
		return $this->model_fy_mstr->getFyByFy(['fy' => $FY])['id'];
	}

	public function trustDemandGenerate($saf_dtl_id, $is_trust_school)
	{
		$currentFY = "2023-2024";
		$safHelper = new SAFHelper($this->db);
		$newsafHelper = new NEW_SAFHelper($this->db);
		$sql = "SELECT tbl_prop_dtl.*,tbl_saf_dtl.saf_pending_status,tbl_prop_tax.prop_dtl_id FROM tbl_prop_dtl
                JOIN (select max(id) as proptaxid,prop_dtl_id from tbl_prop_tax where fYear='2022-2023' or tbl_prop_tax.fy_mstr_id=53 and created_on::date<now()::date and status=1 group by prop_dtl_id) tbl_prop_tax on tbl_prop_tax.prop_dtl_id=tbl_prop_dtl.id
                LEFT JOIN tbl_saf_dtl on tbl_prop_dtl.saf_dtl_id=tbl_saf_dtl.id
                WHERE tbl_prop_dtl.status=1 and tbl_prop_dtl.saf_dtl_id=".$saf_dtl_id." and tbl_prop_dtl.prop_type_mstr_id!=4 
                ";

        $resultArr = $this->db->query($sql)->getRowArray();
		if($resultArr)
		{
			$prop_dtl_id = $resultArr['prop_dtl_id'];
			$saf_dtl_id = $resultArr['saf_dtl_id'];
			$ward_mstr_id = $resultArr['ward_mstr_id'];
			

			if($resultArr['saf_pending_status'] == 1)
			{
				$record = $this->model_field_verification_dtl->getdatabysafid($saf_dtl_id);
				$record["occupation_date"] = $resultArr["occupation_date"];
				$record['verification_id'] = $record['id'];
			}else{
				$record = $resultArr;
				$record["percentage_of_property_transfer"] = null;
			}
			if(isset($resultArr['apartment_dtl_id']))
			{
				$apt = $this->model_apartment_details->getApartmentDtlById($resultArr['apartment_dtl_id']);
				$record["is_water_harvesting"] = ($apt['water_harvesting_status'] == 1)?'t':'f';
			}

			$inputs = array();
			$inputs['is_trust_school'] = $is_trust_school;
			$inputs['ward_mstr_id'] = $record['ward_mstr_id'];
			$inputs['zone_mstr_id'] = ($resultArr['zone_mstr_id'] > 0)?$resultArr['zone_mstr_id']:2;
			$inputs["prop_type_mstr_id"] = $record['prop_type_mstr_id'];
			$inputs['road_type_mstr_id'] = $record['road_type_mstr_id'];
			$inputs["area_of_plot"] = $record['area_of_plot'];
			$inputs["tower_installation_date"] = $record['tower_installation_date'];
			$inputs["tower_area"] = $record['tower_area'];
			$inputs["hoarding_installation_date"] = $record['hoarding_installation_date'];
			$inputs["hoarding_area"] = $record['hoarding_area'];
			$inputs["petrol_pump_completion_date"] = $record['petrol_pump_completion_date'];
			$inputs["under_ground_area"] = $record['under_ground_area'];
			$inputs["percentage_of_property_transfer"] = $record['percentage_of_property_transfer'];
			if ($record["is_mobile_tower"]=="t") $inputs["is_mobile_tower"] = 1; else $inputs["is_mobile_tower"] = 0;
			if ($record["is_hoarding_board"]=="t") $inputs["is_hoarding_board"] = 1; else $inputs["is_hoarding_board"] = 0;
			if ($record["is_petrol_pump"]=="t") $inputs["is_petrol_pump"] = 1; else $inputs["is_petrol_pump"] = 0;
			if ($record["is_water_harvesting"]=="t") $inputs["is_water_harvesting"] = 1; else $inputs["is_water_harvesting"] = 0;
			$floorDtlArr = array();

				
			if($resultArr['saf_pending_status'] == 1){
				$sqlveri = "select tbl_field_verification_dtl.id from tbl_field_verification_dtl 
							left join (select field_verification_dtl_id from tbl_field_verification_floor_details where date_upto is not null group by field_verification_dtl_id) tbl_field_verification_floor_details on tbl_field_verification_floor_details.field_verification_dtl_id=tbl_field_verification_dtl.id
							where tbl_field_verification_floor_details.field_verification_dtl_id is null and id=".$record['verification_id']."
							";
				$checkfield_verification =  $this->db->query($sqlveri)->getRowArray();
				if($checkfield_verification && $checkfield_verification['id'])
				{ 
					$field_verifcation_floor_dtl = $this->model_field_verification_floor_details->getFloorDataBymstrId($record['verification_id']);
					if(count($field_verifcation_floor_dtl)==0)
					{
						$field_verifcation_floor_dtl = $this->model_prop_floor_details->getViewPropFloorDtlByPropDtlId(['prop_dtl_id'=>$prop_dtl_id]);
					}
				}else{
					$field_verifcation_floor_dtl = $this->model_prop_floor_details->getViewPropFloorDtlByPropDtlId(['prop_dtl_id'=>$prop_dtl_id]);
				}
			}else{
				$field_verifcation_floor_dtl = $this->model_prop_floor_details->getViewPropFloorDtlByPropDtlId(['prop_dtl_id'=>$prop_dtl_id]);
			}
			

			$floorKey = 0;
			foreach ($field_verifcation_floor_dtl as $key => $value) 
			{
				$date_fromarra = explode('-', $value["date_from"]);

				if($date_fromarra[0] <= 1970){
					$date_from = '1970-04-01';
				}else{
					$date_from = $value["date_from"];
				}
				$inputs["floor_mstr_id"][$floorKey] = !empty($value["floor_mstr_id"])?$value["floor_mstr_id"]:3;
				$inputs["usage_type_mstr_id"][$floorKey] = $value["usage_type_mstr_id"];
				$inputs["const_type_mstr_id"][$floorKey] = $value["const_type_mstr_id"];
				$inputs["occupancy_type_mstr_id"][$floorKey] = $value["occupancy_type_mstr_id"];
				$inputs["builtup_area"][$floorKey] = $value["builtup_area"];
				$inputs["date_from"][$floorKey] = date("Y-m", strtotime($date_from));
				$inputs["date_upto"][$floorKey] = "";
				if ($value["date_upto"]!="" && $value["date_upto"]!="null") {
					$inputs["date_upto"][$floorKey] = date("Y-m", strtotime($value["date_upto"]));
				}
			
				$floorKey++;

			}
			$inputs['prop_dtl_id']=$prop_dtl_id;
			
			$floorDtlArr = $safHelper->makeBuildingFloorDtlArr($inputs);
			$isAdditionaTaxImplemented = $safHelper->checkIsAdditionaTaxImplemented($inputs["is_water_harvesting"], $inputs["prop_type_mstr_id"], $inputs["area_of_plot"]);
			
			list($newSafTaxDtl, $old_rule_arv_sub, $new_rule_arv_sub, $cv_rule_arv_sub) = $newsafHelper->calBuildingTaxDtl_2023($floorDtlArr, (int)$record['prop_type_mstr_id'], $isAdditionaTaxImplemented);
			
			if($newSafTaxDtl)
			{
				$this->db->table('tbl_prop_tax')->set('status', 0)->where('prop_dtl_id', $prop_dtl_id)
                        ->where('fy_mstr_id', 53)
                        ->where('status', 1)
                        ->update();
				$this->db->table('tbl_prop_demand')->where('prop_dtl_id', $prop_dtl_id)
					->whereIn('fy_mstr_id', [53,54])
					->where('status', 1)
					->where('paid_status', 0)
					->update(['status'=>0, 'balance'=>'0.00']);
				foreach($newSafTaxDtl as $key => $taxDtl)
				{
					
					$pymt_frm_qtr = (int)$taxDtl['qtr'];
					$pymt_frm_year = (string)$taxDtl['fyear'];

					$pymt_upto_qtr = (int)4;
					$pymt_upto_year = (string)$currentFY;
					if ($key < sizeof($newSafTaxDtl) - 1) {
						$pymt_upto_qtr = (int)$newSafTaxDtl[$key + 1]['qtr'] - 1;
						$pymt_upto_year = (string)$newSafTaxDtl[$key + 1]['fyear'];
					}
					list($from_y1_new, $from_y2_new) = explode("-", $pymt_frm_year);
					list($upto_y1_new, $upto_y2_new) = explode("-", $pymt_upto_year);


					$fy_mstr_id = $this->getFyID($taxDtl['fyear']);
					$holding_tax = isset($taxDtl['holding_tax'])?$taxDtl['holding_tax']:0;
					$water_tax = isset($taxDtl['water_tax'])?$taxDtl['water_tax']:0;
					$education_cess = isset($taxDtl['education_cess'])?$taxDtl['education_cess']:0;
					$health_cess = isset($taxDtl['health_cess'])?$taxDtl['health_cess']:0;
					$latrine_tax = isset($taxDtl['latrine_tax'])?$taxDtl['latrine_tax']:0;
					$additional_tax = isset($taxDtl['additional_tax'])?$taxDtl['additional_tax']:0;
					$quarterly_tax = $holding_tax+$water_tax+$education_cess+$health_cess+$latrine_tax+$additional_tax;
					
					if($taxDtl['arv'] > 0)
					{
						$sql = "INSERT INTO tbl_prop_tax (prop_dtl_id, fy_mstr_id, fyear, qtr, arv, holding_tax, water_tax, education_cess, health_cess, latrine_tax, additional_tax, created_on, status, quarterly_tax)
						VALUES ('$prop_dtl_id', '".$fy_mstr_id."' ,'" . $taxDtl['fyear'] . "', '" . $taxDtl['qtr'] . "', '".$taxDtl['arv']."', '$holding_tax', '$water_tax', '$education_cess', '$health_cess', '$latrine_tax', '$additional_tax', '" . date("Y-m-d H:i:s") . "', 1, '".$quarterly_tax."') returning id";
						$query = $this->db->query($sql);
						$return = $query->getFirstRow("array");
						$prop_tax_id = $return["id"];
						
						while ($from_y1_new <= $upto_y1_new && $from_y2_new <= $upto_y2_new) 
						{
							
							$newFY = $from_y1_new . "-" . $from_y2_new;
							$till_qtr = $newFY == $pymt_upto_year ? $pymt_upto_qtr : 4;
							for ($q = $pymt_frm_qtr; $q <= $till_qtr; $q++) {
								
								$newFY = $from_y1_new . "-" . $from_y2_new;
								$adjust_amt = 0;
								$demandAmt = $taxDtl['quarterly_tax'] - $taxDtl['additional_tax'];
								$amount = $taxDtl['quarterly_tax'];
								$additional_tax = $taxDtl['additional_tax'];
								$due_date = $this->makeDueDateByFyearQtr($newFY, $q);

								$sql = "SELECT COALESCE(SUM(amount), 0) AS total_amount FROM (SELECT fy_mstr_id, qtr, amount, balance, fyear, due_date FROM tbl_prop_demand WHERE prop_dtl_id=" . $prop_dtl_id . " AND status=1 AND paid_status=1 AND due_date='" . $due_date . "'
                                            UNION
                                            SELECT fy_mstr_id, qtr, amount, balance, fyear, due_date FROM tbl_saf_demand WHERE saf_dtl_id=" . $saf_dtl_id . " AND status=1 AND paid_status=1 AND due_date='" . $due_date . "') AS tbl_demand
                                            GROUP BY due_date
                                            ORDER BY due_date";
								$total_result = $this->db->query($sql);
								if ($total_prev_demand = $total_result->getFirstRow("array")) {
									$quarterly_tax = $amount - $total_prev_demand["total_amount"];
									$demandAmt = $demandAmt - $total_prev_demand["total_amount"];
									$adjust_amt = $total_prev_demand["total_amount"];
								}

								$additional = 0;
								if($newFY != '2016-2017')
								{
									$additional = $additional_tax;
								}

								if ($quarterly_tax > 0 && round($demandAmt+$additional) > 0) 
								{
									$index = [
										'prop_dtl_id' => $prop_dtl_id,
										'prop_tax_id' => $prop_tax_id,
										'fy_mstr_id' => $this->getFyID($newFY),
										'ward_mstr_id' => $ward_mstr_id,
										'fyear' => $newFY,
										'qtr' => $q,
										'due_date' => $due_date,
										'amount' => round($amount, 2),
										'balance' => round($demandAmt+$additional, 2),
										'fine_tax' => 0,
										'created_on' => date("Y-m-d H:i:s"),
										'status' => 1,
										'paid_status' => 0,
										'demand_amount' => round($amount-$additional_tax, 2),
										'additional_amount' => $additional,
										'adjust_amt' => $adjust_amt
									];
									//print_var($index);
									$prop_tax[] = $index;
									$this->model_prop_demand->insertData($index);
								}
							}
							$pymt_frm_qtr = 1;
							$from_y1_new++;
							$from_y2_new++;       
						
							
						}

					}
					
				}
			}
		
		}
	}
}
