<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_ulb_mstr;
use App\Models\model_fy_mstr;
use App\Models\model_ward_mstr;
use App\Models\model_view_ward_mapping_mstr;
use App\Models\model_transfer_mode_mstr;
use App\Models\model_ownership_type_mstr;
use App\Models\model_prop_type_mstr;
use App\Models\model_road_type_mstr;
use App\Models\model_floor_mstr;
use App\Models\model_usage_type_mstr;
use App\Models\model_usage_type_dtl;
use App\Models\model_occupancy_type_mstr;
use App\Models\model_const_type_mstr;
use App\Models\model_saf_dtl;
use App\Models\model_saf_owner_detail;
use App\Models\model_saf_floor_details;
use App\Models\model_saf_floor_arv_dtl;
use App\Models\model_saf_tax;
use App\Models\model_saf_demand;
use App\Models\model_arr_old_building_mstr;
use App\Models\model_arr_building_mstr;
use App\Models\model_arr_vacant_mstr;
use App\Models\model_prop_dtl;
use App\Models\model_prop_owner_detail;
use App\Models\model_prop_demand;
use App\Models\model_payment_adjust;
use App\Models\model_saf_distributed_dtl;
use App\Models\model_doc_mstr;
use App\Models\model_saf_doc_dtl;
use App\Models\model_view_saf_doc_dtl;
use App\Models\model_level_pending_dtl;
use App\Models\model_view_saf_dtl;
//use App\Models\BO_SAF_Controller;
use App\Models\model_saf_memo_dtl;
use App\Models\model_view_saf_floor_details;
use App\Models\model_field_verification_dtl;
use App\Models\model_transaction;
use App\Models\model_field_verification_floor_details;
use App\Models\model_saf_geotag_upload_dtl;
use App\Models\model_razor_pay_response;
use App\Models\model_razor_pay_request;
use Exception;

class CitizenDtl extends CitizenLoginController
{
	protected $db;
	protected $dbSystem;
	protected $model_ulb_mstr;
	protected $model_fy_mstr;
	protected $model_ward_mstr;
	protected $model_view_ward_mapping_mstr;
	protected $model_transfer_mode_mstr;
	protected $model_ownership_type_mstr;
	protected $model_prop_type_mstr;
	protected $model_road_type_mstr;
	protected $model_floor_mstr;
	protected $model_usage_type_mstr;
	protected $model_usage_type_dtl;
	protected $model_occupancy_type_mstr;
	protected $model_const_type_mstr;
	protected $model_saf_dtl;
	protected $model_saf_owner_detail;
	protected $model_saf_floor_details;
	protected $model_saf_floor_arv_dtl;
	protected $model_saf_tax;
	protected $model_saf_demand;
	protected $model_arr_old_building_mstr;
	protected $model_arr_building_mstr;
	protected $model_arr_vacant_mstr;
	protected $model_prop_dtl;
	protected $model_prop_owner_detail;
	protected $model_prop_demand;
	protected $model_payment_adjust;
	protected $model_saf_distributed_dtl;
	protected $model_doc_mstr;
	protected $model_saf_doc_dtl;
	protected $model_view_saf_doc_dtl;
	protected $model_level_pending_dtl;
	protected $model_view_saf_dtl;
	protected $model_transaction;
	protected $model_saf_memo_dtl;
	//protected $BO_SAF_Controller;
	protected $model_field_verification_dtl;
	protected $model_view_saf_floor_details;
	protected $model_field_verification_floor_details;
	protected $model_saf_geotag_upload_dtl;

	protected $api;
	protected $api_key_id = "";
	protected $api_secret = "";

    public function __construct(){
        parent::__construct();
    	helper(['url', 'db_helper', 'validation_helper', 'validate_saf_helper', 'sms_helper', 'utility_helper']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name);
        }
        if ($db_name = dbSystem()) {
            $this->dbSystem = db_connect($db_name);
        }



		$this->model_fy_mstr = new model_fy_mstr($this->dbSystem);
		$this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
		$this->model_view_ward_mapping_mstr = new model_view_ward_mapping_mstr($this->dbSystem);
        $this->model_transfer_mode_mstr = new model_transfer_mode_mstr($this->db);
        $this->model_ownership_type_mstr = new model_ownership_type_mstr($this->db);
        $this->model_prop_type_mstr = new model_prop_type_mstr($this->db);
        $this->model_road_type_mstr = new model_road_type_mstr($this->db);
        $this->model_floor_mstr = new model_floor_mstr($this->db);
        $this->model_usage_type_mstr = new model_usage_type_mstr($this->db);
        $this->model_usage_type_dtl = new model_usage_type_dtl($this->db);
        $this->model_occupancy_type_mstr = new model_occupancy_type_mstr($this->db);
        $this->model_const_type_mstr = new model_const_type_mstr($this->db);
        $this->model_saf_dtl = new model_saf_dtl($this->db);
        $this->model_saf_owner_detail = new model_saf_owner_detail($this->db);
		$this->model_saf_floor_details = new model_saf_floor_details($this->db);
		$this->model_saf_floor_arv_dtl = new model_saf_floor_arv_dtl($this->db);
        $this->model_saf_tax = new model_saf_tax($this->db);
        $this->model_saf_demand = new model_saf_demand($this->db);
        $this->model_arr_old_building_mstr = new model_arr_old_building_mstr($this->db);
        $this->model_arr_building_mstr = new model_arr_building_mstr($this->db);
		$this->model_arr_vacant_mstr = new model_arr_vacant_mstr($this->db);
		$this->model_prop_dtl = new model_prop_dtl($this->db);
		$this->model_prop_owner_detail = new model_prop_owner_detail($this->db);
		$this->model_prop_demand = new model_prop_demand($this->db);
		$this->model_payment_adjust = new model_payment_adjust($this->db);
		$this->model_saf_distributed_dtl = new model_saf_distributed_dtl($this->db);
		$this->model_doc_mstr = new model_doc_mstr($this->db);
		$this->model_saf_doc_dtl = new model_saf_doc_dtl($this->db);
		$this->model_view_saf_doc_dtl = new model_view_saf_doc_dtl($this->db);
		$this->model_view_saf_dtl = new model_view_saf_dtl($this->db);
		$this->model_transaction = new model_transaction($this->db);
		$this->model_level_pending_dtl = new model_level_pending_dtl($this->db);
		//$this->BO_SAF_Controller = new BO_SAF($this->db);
		$this->model_view_saf_floor_details = new model_view_saf_floor_details($this->db);
		$this->model_field_verification_dtl = new model_field_verification_dtl($this->db);
		$this->model_saf_memo_dtl = new model_saf_memo_dtl($this->db);
		$this->model_field_verification_floor_details = new model_field_verification_floor_details($this->db);
		$this->model_saf_geotag_upload_dtl = new model_saf_geotag_upload_dtl($this->db);
		$this->model_razor_pay_response = new model_razor_pay_response($this->db);
		$this->model_razor_pay_request = new model_razor_pay_request($this->db);

		$this->api_key_id = getenv("razorpay.api_key_id");
		$this->api_secret = getenv("razorpay.api_secret_key");
    }

	function __destruct() {
		if (isset($this->db)) $this->db->close();
		if (isset($this->dbSystem)) $this->dbSystem->close();
	}

	public function my_application(){
		$session = session();
		$ulb = getUlbDtl();	
		$saf_dtl = cGetCookie('saf_dtl');
		$session->set('saf_dtl', $saf_dtl);
		
		$saf = $this->model_view_saf_dtl->get_saf_full_details(($saf_dtl["saf_dtl_id"]));
        $saf = $saf['get_saf_full_details'];
        $data=json_decode($saf, true);

        $data['application_status'] = $this->model_view_saf_dtl->get_saf_status(($saf_dtl["saf_dtl_id"]));
		$data["ulb_mstr_id"]=$ulb["ulb_mstr_id"];
		$data["ulb_name"]=$ulb["ulb_name"];
		return view('Citizen/SAF/my_application', $data);
	}


	public function document_app()
	{
		$data =(array)null;
		$session = session();
		$ulb_mstr = $session->get("ulb_dtl");
		$saf_dtl = $session->get('saf_dtl');

		$data = $this->model_view_saf_dtl->getSafDtlByMD5SafDtlId(md5($saf_dtl["saf_dtl_id"]));

		if($this->request->getMethod()=='post')
		{
			$is_specially_data = $this->request->getvar('is_specially_data');
			$is_armed_data = $this->request->getvar('is_armed_data');

			$ulb_dtl = $this->model_ulb_mstr->getulb_list($ulb_mstr["ulb_mstr_id"]);
			$inputs = arrFilterSanitizeString($this->request->getVar());
			if(isset($inputs['btn_owner_doc_upload']))
			{
				$rules=[
					'applicant_image_file'=>'uploaded[applicant_image_file]|max_size[applicant_image_file,5120]|ext_in[applicant_image_file,png,jpg,jpeg]',
					'applicant_doc_file'=>'uploaded[applicant_doc_file]|max_size[applicant_doc_file,5120]|ext_in[applicant_doc_file,png,jpg,jpeg,pdf]',
				];
				if ($is_specially_data == 1) {

					$rules['handicaped_document'] = 'uploaded[handicaped_document]|max_size[applicant_doc_file,5120]|ext_in[applicant_doc_file,png,jpg,jpeg,pdf]';
				}
				if ($is_armed_data == 1) {
					$rules['armed_force_document'] = 'uploaded[armed_force_document]|max_size[applicant_doc_file,5120]|ext_in[applicant_doc_file,png,jpg,jpeg,pdf]';
				}
				if ($this->validate($rules))
				{
					$applicant_image_file = $this->request->getFile('applicant_image_file');
					$applicant_doc_file = $this->request->getFile('applicant_doc_file');

					if ($is_specially_data == 1) {
						$handicaped_document = $this->request->getFile('handicaped_document');
						$handicaped_doc_mstr_id = $this->model_saf_doc_dtl->get_doc_mstr_id('handicaped_document');
						// return;
					}
					if ($is_armed_data == 1) {
						$armed_force_document = $this->request->getFile('armed_force_document');
						$armed_doc_mstr_id = $this->model_saf_doc_dtl->get_doc_mstr_id('armed_force_document');
					}

					if ($applicant_image_file->IsValid() && !$applicant_image_file->hasMoved() && $applicant_doc_file->IsValid() && !$applicant_doc_file->hasMoved())
					{
						try
						{
							$this->db->transBegin();
							$input = [
								'saf_dtl_id'=> $data['saf_dtl_id'],
								'saf_owner_dtl_id'=> $inputs['saf_owner_dtl_id'],
								'owner_doc_mstr_id'=> $inputs['owner_doc_mstr_id'],
								'emp_details_id'=> 0,
								'created_on'=> "NOW()",
								'status'=> 1
							];

							if($saf_doc_dtl_id = $this->model_saf_doc_dtl->checkOwnerImgDataIsExistBySafOwnerDtlId($input))
							{
								$delete_path = WRITEPATH.'uploads/'.$saf_doc_dtl_id['doc_path'];
								// @unlink($delete_path);
								deleteFile($delete_path);

								$newFileName = md5($saf_doc_dtl_id['id']);
								$file_ext = $applicant_image_file->getExtension();

								$path = $ulb_dtl['city']."/"."applicant_image";
								$owner_img_path = $path."/".$newFileName.'.'.$file_ext;
								if($applicant_image_file->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext))
								{
									$this->model_saf_doc_dtl->updatetransImgpathById($saf_doc_dtl_id['id'], $owner_img_path);
								}
							}
							else if($saf_doc_dtl_id = $this->model_saf_doc_dtl->insertOwnerImgData($input))
							{
								$newFileName = md5($saf_doc_dtl_id);
								$file_ext = $applicant_image_file->getExtension();

								$path = $ulb_dtl['city']."/"."applicant_image";
								$owner_img_path = $path."/".$newFileName.'.'.$file_ext;
								if($applicant_image_file->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext)){
									$this->model_saf_doc_dtl->updatetransImgpathById($saf_doc_dtl_id, $owner_img_path);
								}
							}

							if($saf_doc_dtl_id = $this->model_saf_doc_dtl->checkOwnerDocDataIsExistBySafOwnerDtlId($input))
							{
								$delete_path = WRITEPATH.'uploads/'.$saf_doc_dtl_id['doc_path'];
								// @unlink($delete_path);
								deleteFile($delete_path);

								$newFileName = md5($saf_doc_dtl_id['id']);
								$file_ext = $applicant_doc_file->getExtension();

								$path = $ulb_dtl['city']."/"."saf_doc_dtl";
								$owner_doc_path = $path."/".$newFileName.'.'.$file_ext;
								if($applicant_doc_file->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext)){
									$this->model_saf_doc_dtl->updatetransdocpathById($saf_doc_dtl_id['id'], $owner_doc_path, $input['owner_doc_mstr_id']);
								}
							}
							else if($saf_doc_dtl_id = $this->model_saf_doc_dtl->insertOwnerData($input))
							{

								$newFileName = md5($saf_doc_dtl_id);
								$file_ext = $applicant_doc_file->getExtension();

								$path = $ulb_dtl['city']."/"."saf_doc_dtl";
								$owner_doc_path = $path."/".$newFileName.'.'.$file_ext;
								if($applicant_doc_file->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext)){
									$this->model_saf_doc_dtl->updatetransdocpathById($saf_doc_dtl_id, $owner_doc_path, $input['owner_doc_mstr_id']);
								}
							}

							//is sepcially abled image upload
							if ($is_specially_data == 1) {
								if ($handicaped_document->IsValid() && !$handicaped_document->hasMoved()) {
									if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->checkHandicapedDocDataIsExistBySafOwnerDtlId($input)) {

										$delete_path = WRITEPATH . 'uploads/' . $saf_doc_dtl_id['doc_path'];
										// @unlink($delete_path);
										deleteFile($delete_path);

										$newFileName = md5($saf_doc_dtl_id['id']);
										$file_ext = $handicaped_document->getExtension();

										$path = $ulb_dtl['city'] . "/" . "saf_doc_dtl";
										$owner_doc_path = $path . "/" . $newFileName . '.' . $file_ext;
										if ($handicaped_document->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext)) {
											$this->model_saf_doc_dtl->updatetransdocpathById($saf_doc_dtl_id['id'], $owner_doc_path, $handicaped_doc_mstr_id);
										}
									} else if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->insertHandicapedData($input)) {

										$newFileName = md5($saf_doc_dtl_id);
										$file_ext = $handicaped_document->getExtension();

										$path = $ulb_dtl['city'] . "/" . "saf_doc_dtl";
										$owner_doc_path = $path . "/" . $newFileName . '.' . $file_ext;
										if ($handicaped_document->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext)) {
											$this->model_saf_doc_dtl->updatetransdocpathById($saf_doc_dtl_id, $owner_doc_path, $handicaped_doc_mstr_id);
										}
									}
								}
							}

							//Armed Force image upload
							if ($is_armed_data == 1) {
								if ($armed_force_document->IsValid() && !$armed_force_document->hasMoved()) {
									if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->checkArmedDocDataIsExistBySafOwnerDtlId($input)) {

										$delete_path = WRITEPATH . 'uploads/' . $saf_doc_dtl_id['doc_path'];
										// @unlink($delete_path);
										deleteFile($delete_path);

										$newFileName = md5($saf_doc_dtl_id['id']);
										$file_ext = $armed_force_document->getExtension();

										$path = $ulb_dtl['city'] . "/" . "saf_doc_dtl";
										$owner_doc_path = $path . "/" . $newFileName . '.' . $file_ext;
										if ($armed_force_document->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext)) {
											$this->model_saf_doc_dtl->updatetransdocpathById($saf_doc_dtl_id['id'], $owner_doc_path, $armed_doc_mstr_id);
										}
									} else if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->insertArmedData($input)) {

										$newFileName = md5($saf_doc_dtl_id);
										$file_ext = $armed_force_document->getExtension();

										$path = $ulb_dtl['city'] . "/" . "saf_doc_dtl";
										$owner_doc_path = $path . "/" . $newFileName . '.' . $file_ext;
										if ($armed_force_document->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext)) {
											$this->model_saf_doc_dtl->updatetransdocpathById($saf_doc_dtl_id, $owner_doc_path, $armed_doc_mstr_id);
										}
									}
								}
							}

							if ($this->db->transStatus() === FALSE)
							{
								$this->db->transRollback();
								flashToast("message", "Oops, Owner document not uploaded.");
							}
							else
							{
								$this->db->transCommit();
								flashToast("message", "Owner Document uploaded successfully.");
							}
						}
						catch (Exception $e)
						{

							flashToast("message", $e->getMessage());
						}
					}
					else
					{
						flashToast("message", "something errors in owner details.");
					}
				}
				else
				{
					$errMsg = $this->validator->listErrors();
					flashToast("message", $errMsg);
				}
			}

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
									'emp_details_id'=> 0,
									'created_on'=> "NOW()",
									'status'=> 1,
								];

							if($saf_doc_dtl_id = $this->model_saf_doc_dtl->checkDocDataIsExist($input))
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
		}

		$data['saf_owner_detail'] = $this->model_saf_owner_detail->getOwnerdtlBySAFId(["saf_dtl_id"=> $data['saf_dtl_id']]);
		// applicant img & document
		foreach($data['saf_owner_detail'] as $key => $owner_detail)
		{
			$input = [
				'saf_dtl_id'=> $data['saf_dtl_id'],
				'saf_owner_dtl_id'=> $owner_detail['id'],
			];
			$data['saf_owner_detail'][$key]['applicant_img_dtl'] = $this->model_saf_doc_dtl->getApplicantImgBySafDtlAndSafOwnerDtlId($input);
			$data['saf_owner_detail'][$key]['applicant_doc_dtl'] = $this->model_saf_doc_dtl->getApplicantDocBySafDtlAndSafOwnerDtlId($input);
			$data['saf_owner_detail'][$key]['Handicaped_doc_dtl'] = $this->model_saf_doc_dtl->getHandicapedDocBySafDtlAndSafOwnerDtlId($input);
			$data['saf_owner_detail'][$key]['Armed_doc_dtl'] = $this->model_saf_doc_dtl->getArmedDocBySafDtlAndSafOwnerDtlId($input);
		}

		$data['is_trust'] = $this->model_saf_floor_details->isTrust($data['saf_dtl_id']);
		$data['owner_doc_list'] = $this->model_doc_mstr->getDataByDocType('other');
		$data['saf_doc_list'] = $this->model_doc_mstr->HaveToUploadDoc($data);
		$data["uploaded_doc_list"]=$this->model_saf_doc_dtl->getAllActiveDocuments($data['saf_dtl_id']);



		$data["ulb_mstr_id"]=$ulb_mstr["ulb_mstr_id"];
		$data["ulb_name"]=$ulb_mstr["ulb_name"];

		// print_var($data);
		// return;
		
		if($data["doc_upload_status"]==1)
		return view('Citizen/SAF/view_application_document', $data);
		else
		return view('Citizen/SAF/application_document', $data);
	}

	public function citizen_saf_payment_details() {
		$ulb = getUlbDtl();
		$saf_dtl = cGetCookie('saf_dtl');
		$saf = $this->model_view_saf_dtl->get_saf_full_details(($saf_dtl["saf_dtl_id"]));
        $saf = $saf['get_saf_full_details'];
        $data=json_decode($saf, true);
		$data['demand_detail'] = $this->model_saf_demand->demandDetailsById($saf_dtl["saf_dtl_id"]);
		$data["ulb_name"]=$ulb["ulb_name"];
		$data["ulb_mstr_id"]=$ulb["ulb_mstr_id"];
        return view('Citizen/SAF/my_application_payment', $data);
	}

	public function citizen_saf_confirm_payment()
	{
		$data =(array)null;
		$ulb = getUlbDtl();	
		$saf_dtl = cGetCookie('saf_dtl');

		$data['demand_detail'] = $this->model_saf_demand->demand_detail(["id"=> md5($saf_dtl["saf_dtl_id"])]);
		$data["fy_demand"] = $this->model_saf_demand->fydemand($saf_dtl["saf_dtl_id"]);
		$data['saf_dtl_id'] = $saf_dtl["saf_dtl_id"];
		$data['ulb_mstr_id'] = $ulb["ulb_mstr_id"];
		$data["ulb_name"]=$ulb["ulb_name"];
		$geoTag = $this->db->query("select distinct (direction_type) as direction_type from tbl_saf_geotag_upload_dtl where geotag_dtl_id = ".$data['saf_dtl_id']." AND status =1 ")->getResultArray();
		$data["is_geo_tag_done"] = sizeof($geoTag)>=3 ? true : false;
		
		return view('Citizen/SAF/citizen_saf_confirm_payment', $data);
	}


	public function documents() {
		$data =(array)null;
		return view('Citizen/SAF/documents', $data);
	}


	public function safLogout() {
		if (cHasCookie('saf_dtl')) {
			cDeleteCookie('saf_dtl');
		}

		return redirect()->to(base_url('CitizenSaf/searchApplication'));
	}



	public function Ajax_getSAFPayableAmount()
	{
		$response = ['response'=> false];
		if($this->request->getMethod()=='post')
		{
			$input = [
					'fy' => $this->request->getVar('fy'),
					'qtr' => $this->request->getVar('qtr'),
					'saf_dtl_id' => $this->request->getVar('saf_dtl_id'),
					'user_id'=> 0,
					];
			$data=$this->model_saf_demand->getSAFDemandAmountDetails($input);
			$out='<tr>
						<td class="pull-right">Demand Amount</td>
						<td>'.$data['DemandAmount'].'</td>
						<td class="pull-right">Rebate</td>
						<td>'.$data['RebateAmount'].'</td>
					</tr>
					<tr>
						<td class="pull-right"> </td>
						<td></td>
						<td class="pull-right">	Special Rebate </td>
						<td>'.$data['SpecialRebateAmount'].'</td>
					</tr>
					<tr>
						<td class="pull-right">Late Assessment Penalty</td>
						<td>'.$data['LateAssessmentPenalty'].'</td>
						<td class="pull-right">	1 % Interest </td>
						<td>'.$data['OnePercentPnalty'].'</td>
					</tr>';
			if($data["OtherPenalty"])
			$out.='<tr>
						<td class="pull-right">Other Penalty</td>
						<td colspan="3">'.$data['OtherPenalty'].'</td>
					</tr>';

			$out.='<tr>
						<td class="pull-right">Advance</td>
						<td>'.$data['AdvanceAmount'].'</td>
						<td class="pull-right text-success">Total Paybale Amount</td>
						<td class="text-success" id="total_payable_amount_temp">'.$data['PayableAmount'].'</td>
					</tr>';
			$response = ['response'=> true, 'data'=> $data, 'html_data'=> $out];
		}
		echo json_encode($response);
	}

	public function Ajax_getQtr()
	{
		$response = ['response'=> false];
		if($this->request->getMethod()=='post')
		{
			$data = [
					'fy_mstr_id' => $this->request->getVar('fy_mstr_id'),
					'saf_dtl_id' => $this->request->getVar('saf_dtl_id'),
					];
			$result = $this->model_saf_demand->getDistinctQtr($data);
			$option=null;
			if(!empty($result))
			{
				foreach($result as $value)
				{
					$option .= '<option value="'.$value['qtr'].'">'.$value['qtr'].'</option>';
				}
			}
			$response = ['response'=> true, 'data'=> $option];
		}
		echo json_encode($response);
	}


	public function Ajax_getOnlineSafPayableAmount(){
		$data=(array)null;
		$out = ["status"=> false, "message"=> "Invalid method"];
		if($this->request->getMethod()=='post'){

			$Session = Session();
			$ulb = $Session->get('ulb_dtl');
			$data["saf_owner_detail"] = $Session->get('get_saf_full_details')["saf_owner_detail"];

			$input = [
				'fy' => $this->request->getVar('fy'),
				'qtr' => $this->request->getVar('qtr'),
				'saf_dtl_id' => $this->request->getVar('saf_dtl_id'),
				'user_id'=> 0,
				];


			//print_var($data);exit;
			$data["DuesYear"]=$this->model_saf_demand->geDuesYear($input["saf_dtl_id"]);
			try{
				if($data["DuesYear"])
				{
					$data["DuesDetails"]=$this->model_saf_demand->getSAFDemandAmountDetails($input);



					$input = [
							"prop_dtl_id"=> $input["saf_dtl_id"],
							"module"=> "Saf",
							"merchant_id"=> 0,
							"from_fy_mstr_id"=> $data["DuesYear"]["min_fy_id"],
							"from_fy"=> $data["DuesYear"]["min_year"],
							"from_qtr"=> $data["DuesYear"]["min_quarter"],
							"upto_fy_mstr_id"=> $data["DuesYear"]["max_fy_id"], // gadbad hai
							"upto_fy"=> $input["fy"],
							"upto_qtr"=> $input["qtr"],
							"demand_amt"=> $data["DuesDetails"]["DemandAmount"],
							"penalty_amt"=> ($data["DuesDetails"]["OnePercentPnalty"] + $data["DuesDetails"]["OtherPenalty"]),
							"discount"=> ($data["DuesDetails"]["RebateAmount"] + $data["DuesDetails"]["AdvanceAmount"]),
							"payable_amt"=> $data["DuesDetails"]["PayableAmount"],
							"order_id"=> null,
						];
					$data["pg_mas_id"]=$this->model_razor_pay_request->pay_request($input);

					helper('rozarpay_helper');
					includeRazorLibrary();
					$this->api = new \Razorpay\Api\Api($this->api_key_id, $this->api_secret);
					$order_id = $this->api->order->create(array(
							'receipt' => $data["pg_mas_id"],
							'amount' => $data["DuesDetails"]["PayableAmount"]*100,
							'currency' => 'INR'
						)
					);
					$this->model_razor_pay_request->updateRecord(["order_id"=> $order_id["id"]], $data["pg_mas_id"]);

					$razorpay_param=[
							"key"=> $this->api_key_id,
							"order_id"=> $order_id["id"],
							"amount"=> $data["DuesDetails"]["PayableAmount"]*100,
							"currency"=> "INR",
							"name"=> $ulb["ulb_name"],
							"description"=> "SAF Tax Payment",
							"owner_name"=> $data["saf_owner_detail"][0]["owner_name"],
							"owner_email"=> $data["saf_owner_detail"][0]["email"],
							"owner_contact"=> $data["saf_owner_detail"][0]["mobile_no"],
							"pg_mas_id"=> $data["pg_mas_id"],
					];

					$out = ["status"=> true, "data"=> $razorpay_param, "message"=> ""];
				}
			}
			catch(Exception $e){
				$out = ["status"=> false, "data"=> [], "message"=> "Exception: ".$e->getMessage()];
			}

		}
		echo json_encode($out);
	}

	public function paymentSuccess($saf_dtl_id, $pg_mas_id, $razorpay_payment_id, $razorpay_order_id, $razorpay_signature)
	{
		$data =(array)null;

		try
		{
			helper('rozarpay_helper');
			includeRazorLibrary();
			$this->api = new \Razorpay\Api\Api($this->api_key_id, $this->api_secret);

			//validate razor pay signature
			$attributes  = array('razorpay_signature'  => $razorpay_signature,  'razorpay_payment_id'  => $razorpay_payment_id ,  'razorpay_order_id' => $razorpay_order_id);
			$order  = $this->api->utility->verifyPaymentSignature($attributes);



			$request=$this->model_razor_pay_request->getRecord($pg_mas_id);
			if($request["order_id"]!=$razorpay_order_id){

				throw new Exception("Payment gateway order Id is not matching");
			}

			$input=[
					"razorpay_payment_id"=> $pg_mas_id,
					"prop_dtl_id"=> $saf_dtl_id,
					"module"=> $request["module"],
					"payable_amt"=> $request["payable_amt"],
					"ip_address"=> get_client_ip(),
					"merchant_id"=> 0,
					"razorpay_order_id"=> $razorpay_order_id,
					"razorpay_signature"=> $razorpay_signature,
					"code"=> null,
					"description"=> null,
					"source"=> null,
					"reason"=> null,
					"order_id"=> $razorpay_order_id,
					"payment_id"=> $razorpay_payment_id,
			];
			$data=[
				"saf_dtl_id"=> $saf_dtl_id,
				"fy"=> $request["upto_fy"],
				"qtr"=> $request["upto_qtr"],
				"user_id"=> 0,
				"payment_mode"=> "Online",
				"remarks"=> null,
				"total_payable_amount"=> 0,
			];

			$this->db->transBegin();
			$this->model_razor_pay_response->pay_response($input);
			$trxn_id=$this->model_transaction->saf_pay_now($data, []);

			if($this->db->transStatus() === FALSE)
			{
				$this->db->transRollback();
				flashToast("message", "Some error occured, Transaction process has bee rollback!!!");
				return $this->response->redirect(base_url('CitizenDtl/citizen_saf_payment_details'));

			}
			else
			{
				$this->db->transCommit();

				// Update saf full detail in session
				$Session = Session();
				$saf_dtl = $Session->get('saf_dtl');
				$saf = $this->model_view_saf_dtl->get_saf_full_details(md5($saf_dtl["saf_dtl_id"]));
				$saf = $saf['get_saf_full_details'];
				$data=json_decode($saf, true);
				$Session->set('get_saf_full_details', $data);
				$this->send_rmc(); //Added by shashi
				flashToast("message", "Payment successfully done and Application sent to ULB!!!");
				return $this->response->redirect(base_url("CitizenDtl/citizen_saf_payment_details"));
			}

		}
		catch (Exception $e)
		{
			flashToast("message", $e->getMessage());
			return $this->response->redirect(base_url('CitizenDtl/citizen_saf_confirm_payment'));
		}

	}

	public function send_rmc()
	{
		$data =(array)null;
		$Session = Session();
		$saf_dtl = $Session->get('saf_dtl');
		$data["saf_id"]=$saf_dtl["saf_dtl_id"];

		$leveldata = [
		 		'saf_dtl_id' => $saf_dtl["saf_dtl_id"],
		 		'sender_user_type_id' => 11,
		 		'receiver_user_type_id' => 6,
		 		'forward_date' => date('Y-m-d'),
		 		'forward_time' => date('H:i:s'),
				'created_on' =>date('Y-m-d H:i:s'),
		 		'remarks' => 'Payment Done And Document Uploaded by citizen',
		 		'verification_status' => 0,
				'sender_emp_details_id'=> NULL
		 	];
		//SENDING TO PROJECT MANAGER
		/* $leveldata = [
			'saf_dtl_id' => $saf_dtl["saf_dtl_id"],
			'sender_user_type_id' => 11,
			'receiver_user_type_id' => 3,
			'forward_date' => date('Y-m-d'),
			'forward_time' => date('H:i:s'),
			'created_on' =>date('Y-m-d H:i:s'),
			'remarks' => 'Payment Done And Document Uploaded by citizen',
			'verification_status' => 0,
			'sender_emp_details_id'=> 0
		]; */
		$this->db->transBegin();

		$this->model_level_pending_dtl->insrtlevelpendingdtl($leveldata);
		
		$this->model_level_pending_dtl->bugfix_level_pending($leveldata); //Shashi

		//$this->model_saf_doc_dtl->updatestatusDocUpload($data);

		$this->model_saf_dtl->updateSafstatusDocUpload($data);

		if ($this->db->transStatus() === FALSE)
		{
			$this->db->transRollback();
			flashToast("message", "Oops, Application couldn't send to ULB.");
			return $this->response->redirect(base_url('CitizenDtl/document_app'));
		}
		else
		{
			$this->db->transCommit();
			flashToast("message", "Application sent to ULB.");
			return $this->response->redirect(base_url('CitizenDtl/my_application'));
		}
	}

	public function view_submit_app()
	{
		$data =(array)null;
		$session = session();
		$saf_dtl = $session->get('saf_dtl');
		$saf_dtl_id=md5($saf_dtl['saf_dtl_id']);
		$data['verification_data'] = $this->model_field_verification_dtl->getFieldVerificationDtlBySafDtlIdAndVerifiedBy($saf_dtl_id);
        if($data['verification_data'])
        {

            $data['assessment_data'] = $this->model_view_saf_dtl->getSafDtlByMD5SafDtlId(md5($data['verification_data']['saf_dtl_id']));

            $data['saf_owner_detail'] = $this->model_saf_owner_detail->getOwnerdtlBySAFId($data['verification_data']);

            // Vacant land
            if($data['verification_data']['prop_type_mstr_id']!=4)
            {
                $data['saf_floor_data'] = $this->model_view_saf_floor_details->getDataBySafDtlId($data['verification_data']);
                $data["verification_floor_data"] = $this->model_field_verification_floor_details->getagencyDataBymstrId($data["verification_data"]["id"], $data['verification_data']["verified_by"]);
                //print_var($data["verification_floor_data"]);

                $data["ExtraFloorAddedByTC"] = $this->model_field_verification_floor_details->getExtraFloorAddedByTC($data["verification_data"]["id"], $data['verification_data']["verified_by"]);
            }
            $data["safGeoTaggingDtl"] = $this->model_saf_geotag_upload_dtl->getAllGeoTagImgDtlBySafDtlId($data['verification_data']);

			return view('Citizen/SAF/submit_application_form', $data);
		}
	}

}
?>
