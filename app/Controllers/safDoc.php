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
use Exception;

class SafDoc extends AlphaController
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

	public function __construct()
	{
		
		parent::__construct();
		helper(['db_helper', 'upload_helper']);
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
	}

	function __destruct() {
		if(isset($this->db)) $this->db->close();
		if(isset($this->dbSystem)) $this->dbSystem->close();
	}

	public function index($ID)
	{
		$errMsg = [];
		if ($saf = $this->model_view_saf_dtl->getSafDtlByMD5SafDtlId($ID)) {
			//print_r($saf);
			$session = session();
			$ulb_mstr = $session->get("ulb_dtl");
			$emp_details = $session->get("emp_details");

			$temp = true;

			if ($this->request->getMethod() == 'post') {
				$ulb_dtl = $this->model_ulb_mstr->getulb_list($ulb_mstr["ulb_mstr_id"]);
				$inputs = arrFilterSanitizeString($this->request->getVar());
				$created_on = date("Y-m-d h:i:s");

				if (isset($inputs['btn_owner_doc_upload'])) {
					$rules = [
						'applicant_image_file' => 'uploaded[applicant_image_file]|max_size[applicant_image_file,5120]|ext_in[applicant_image_file,png,jpg,jpeg]',
						'applicant_doc_file' => 'uploaded[applicant_doc_file]|max_size[applicant_doc_file,5120]|ext_in[applicant_doc_file,png,jpg,jpeg,pdf]',

					];

					if ($this->validate($rules)) {
						$applicant_image_file = $this->request->getFile('applicant_image_file');
						$applicant_doc_file = $this->request->getFile('applicant_doc_file');
						if (
							$applicant_image_file->IsValid()
							&& !$applicant_image_file->hasMoved()
							&& $applicant_doc_file->IsValid()
							&& !$applicant_doc_file->hasMoved()
						) {
							try {
								$this->db->transBegin();
								$input = [
									'saf_dtl_id' => $saf['saf_dtl_id'],
									'saf_owner_dtl_id' => $inputs['saf_owner_dtl_id'],
									'owner_doc_mstr_id' => $inputs['owner_doc_mstr_id'],
									'emp_details_id' => $emp_details['id'],
									'created_on' => $created_on,
									'status' => 1
								];

								if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->checkOwnerImgDataIsExistBySafOwnerDtlId($input)) {

									$delete_path = WRITEPATH . 'uploads/' . $saf_doc_dtl_id['doc_path'];
									// unlink($delete_path);
									deleteFile($delete_path);

									$newFileName = md5($saf_doc_dtl_id['id']);
									$file_ext = $applicant_image_file->getExtension();

									$path = $ulb_dtl['city'] . "/" . "applicant_image";
									$owner_img_path = $path . "/" . $newFileName . '.' . $file_ext;
									if ($applicant_image_file->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext)) {
										$this->model_saf_doc_dtl->updatetransImgpathById($saf_doc_dtl_id['id'], $owner_img_path);
									}
								} else if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->insertOwnerImgData($input)) {
									$newFileName = md5($saf_doc_dtl_id);
									$file_ext = $applicant_image_file->getExtension();

									$path = $ulb_dtl['city'] . "/" . "applicant_image";
									$owner_img_path = $path . "/" . $newFileName . '.' . $file_ext;
									if ($applicant_image_file->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext)) {
										$this->model_saf_doc_dtl->updatetransImgpathById($saf_doc_dtl_id, $owner_img_path);
									}
								}

								if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->checkOwnerDocDataIsExistBySafOwnerDtlId($input)) {

									$delete_path = WRITEPATH . 'uploads/' . $saf_doc_dtl_id['doc_path'];
									// unlink($delete_path);
									deleteFile($delete_path);

									$newFileName = md5($saf_doc_dtl_id['id']);
									$file_ext = $applicant_doc_file->getExtension();

									$path = $ulb_dtl['city'] . "/" . "saf_doc_dtl";
									$owner_doc_path = $path . "/" . $newFileName . '.' . $file_ext;
									if ($applicant_doc_file->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext)) {
										$this->model_saf_doc_dtl->updatetransdocpathById($saf_doc_dtl_id['id'], $owner_doc_path, $input['owner_doc_mstr_id']);
									}
								} else if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->insertOwnerData($input)) {

									$newFileName = md5($saf_doc_dtl_id);
									$file_ext = $applicant_doc_file->getExtension();

									$path = $ulb_dtl['city'] . "/" . "saf_doc_dtl";
									$owner_doc_path = $path . "/" . $newFileName . '.' . $file_ext;
									if ($applicant_doc_file->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext)) {
										$this->model_saf_doc_dtl->updatetransdocpathById($saf_doc_dtl_id, $owner_doc_path, $input['owner_doc_mstr_id']);
									}
								}

								if ($this->db->transStatus() === FALSE) {
									$this->db->transRollback();
								} else {
									$this->db->transCommit();
								}
							} catch (Exception $e) {
							}
						} else {
							$errMsg = "<ul><li>something errors in owner details.</li></ul>";
						}
					} else {
						$errMsg = $this->validator->listErrors();
					}
				}

				if (isset($inputs['btn_upload'])) {
					$rules = [
						'upld_doc_path' => 'uploaded[upld_doc_path]|max_size[upld_doc_path,5120]|ext_in[upld_doc_path,pdf]',

					];
					if ($this->validate($rules)) {
						$upld_doc_path = $this->request->getFile('upld_doc_path');
						if (
							$upld_doc_path->IsValid()
							&& !$upld_doc_path->hasMoved()
						) {
							try {
								$this->db->transBegin();
								$input = [
									'saf_dtl_id' => $saf['saf_dtl_id'],
									'upld_doc_mstr_id' => $inputs['upld_doc_mstr_id'],
									'emp_details_id' => $emp_details['id'],
									'created_on' => $created_on,
									'status' => 1,

								];

								if ($input['upld_doc_mstr_id'] == 0) {
									$input['doc_mstr_idcheck'] = [0];
									$input['other_doccheck'] = 'saf_form';
								} elseif (($input['upld_doc_mstr_id'] >= 2 && $input['upld_doc_mstr_id'] <= 5) || ($input['upld_doc_mstr_id'] >= 18 && $input['upld_doc_mstr_id'] <= 20) || $input['upld_doc_mstr_id'] == 7) {
									$input['doc_mstr_idcheck'] = [2, 3, 4, 5, 7, 18, 19, 20];
									$input['other_doccheck'] = '';
								} elseif (($input['upld_doc_mstr_id'] >= 15 && $input['upld_doc_mstr_id'] <= 17) || $input['upld_doc_mstr_id'] == 6 || $input['upld_doc_mstr_id'] == 10 || $input['upld_doc_mstr_id'] == 13 || $input['upld_doc_mstr_id'] == 22) {
									$input['doc_mstr_idcheck'] = [6, 10, 13, 15, 16, 17, 22];
									$input['other_doccheck'] = '';
								} elseif ($input['upld_doc_mstr_id'] == 23) {
									$input['doc_mstr_idcheck'] = [23];
									$input['other_doccheck'] = '';
								} elseif ($input['upld_doc_mstr_id'] == 24) {
									$input['doc_mstr_idcheck'] = [24];
									$input['other_doccheck'] = '';
								} elseif ($input['upld_doc_mstr_id'] == 9) {
									$input['doc_mstr_idcheck'] = [9];
									$input['other_doccheck'] = '';
								}

								if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->checkDocDataIsExist($input)) {

									$delete_path = WRITEPATH . 'uploads/' . $saf_doc_dtl_id['doc_path'];
									// unlink($delete_path);
									deleteFile($delete_path);

									$newFileName = md5($saf_doc_dtl_id['id']);
									$file_ext = $upld_doc_path->getExtension();

									$path = $ulb_dtl['city'] . "/" . "saf_doc_dtl";
									$upld_doc_path->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext);
									$upld_doc_path_save = $path . "/" . $newFileName . '.' . $file_ext;
									$this->model_saf_doc_dtl->updatetransdocpathById($saf_doc_dtl_id['id'], $upld_doc_path_save, $input['upld_doc_mstr_id']);
								} else if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->insertFrData($input)) {

									$newFileName = md5($saf_doc_dtl_id);
									$file_ext = $upld_doc_path->getExtension();
									$path = $ulb_dtl['city'] . "/" . "saf_doc_dtl";

									$upld_doc_path->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext);
									$upld_doc_path_save = $path . "/" . $newFileName . '.' . $file_ext;
									$this->model_saf_doc_dtl->updatetransdocpathById($saf_doc_dtl_id, $upld_doc_path_save, $input['upld_doc_mstr_id']);
								}
								if ($this->db->transStatus() === FALSE) {
									$this->db->transRollback();
								} else {
									$this->db->transCommit();
								}
							} catch (Exception $e) {
							}
						} else {
							$errMsg = "<ul><li>something errors in SAF form details.</li></ul>";
						}
					} else {
						$errMsg = $this->validator->listErrors();
					}
				}
			}

			$data = $saf;
			$temp = true;
			$data['errMsg'] = $errMsg;
			$input = ['saf_dtl_id' => $data['saf_dtl_id']];
			$data['owner_detail'] = $this->model_saf_owner_detail->getOwnerdtlOrberByIdAscBySAFId($input);
			foreach ($data['owner_detail'] as $key => $value) {
				$input = ['saf_dtl_id' => $data['saf_dtl_id'], 'saf_owner_dtl_id' => $value['id']];
				$applicant_img_dtl = $this->model_saf_doc_dtl->getApplicantImgBySafDtlAndSafOwnerDtlIdFinal($input);

				if (!$applicant_img_dtl || $applicant_img_dtl['status'] != 2) {
					$temp = false;
				}
				$data['owner_detail'][$key]['applicant_img_dtl'] = $applicant_img_dtl;

				$applicant_doc_dtl = $this->model_saf_doc_dtl->getApplicantDocBySafDtlAndSafOwnerDtlIdFinal($input);
				if (!$applicant_doc_dtl || $applicant_doc_dtl['status'] != 2) {
					$temp = false;
				}
				$data['owner_detail'][$key]['applicant_doc_dtl'] = $applicant_doc_dtl;
			}

			$input = ['saf_dtl_id' => $data['saf_dtl_id']];
			// SAF Form
			$data['saf_form'] = $this->model_saf_doc_dtl->getSafFormBySafDtlIdFinal($input);
			$data['owner_doc_list'] = $this->model_doc_mstr->getDataByDocType('other');
			//print_r($data['prop_type_mstr_id']);
			if ($data['prop_type_mstr_id'] == 1) {
				$data['super_structure_doc_list'] = $this->model_doc_mstr->getDataByDocType('super_structure_doc');
				//print_r($data['super_structure_doc_list']);
			} else if ($data['prop_type_mstr_id'] == 3) {
				$data['flat_doc_list'] = $this->model_doc_mstr->getFlatDocListData();
			} else {
				$data['transfer_mode_doc_list'] = $this->model_doc_mstr->getDataByDocType('transfer_mode');
				$data['property_type_doc_list'] = $this->model_doc_mstr->getDataByDocType('property_type');
			}

			if ($data['prop_type_mstr_id'] == 1) {
				// super structure (Electricity Bill)
				if ($super_structure_doc_dtl = $this->model_saf_doc_dtl->getSuperStructureDocDtlBySafDtlId($input)) {
					if (isset($super_structure_doc_dtl['status']) && $super_structure_doc_dtl['status'] != 2) {
						$temp = false;
					}
					$data['super_structure_doc_dtl'] = $super_structure_doc_dtl;
				} else {
					$temp = false;
				}
			} else if ($data['prop_type_mstr_id'] == 3) {
				// flat dtl (Possession Certificate)
				if ($flat_doc_dtl = $this->model_saf_doc_dtl->getFlatDtlBySafDtlId($input)) {
					if ($flat_doc_dtl['status'] != 2) {
						$temp = false;
					}
					$data['flat_doc_dtl'] = $flat_doc_dtl;
				} else {
					$temp = false;
				}
			} else {
				// other document like (transfer mode, property type)
				if ($transfer_mode_doc_dtl = $this->model_saf_doc_dtl->getTransferModeDocDtlBySafDtlId($input)) {
					if ($transfer_mode_doc_dtl['status'] != 2) {
						$temp = false;
					}
					$data['transfer_mode_doc_dtl'] = $transfer_mode_doc_dtl;
				} else {
					$temp = false;
				}
				if ($property_type_doc_dtl = $this->model_saf_doc_dtl->getPropertyTypeDocDtlBySafDtlId($input)) {
					if ($property_type_doc_dtl['status'] != 2) {
						$temp = false;
					}
					$data['property_type_doc_dtl'] = $property_type_doc_dtl;
				} else {
					$temp = false;
				}
			}
			// no electric connection (form-I)
			if ($data['no_electric_connection'] == 't') {
				$data['no_electric_connection_doc_list'] = $this->model_doc_mstr->getDataByDocType('no_elect_connection');
				if ($no_electric_connection_doc_dtl = $this->model_saf_doc_dtl->getNoElectConnectionDtlBySafDtlId($input)) {
					if ($no_electric_connection_doc_dtl['status'] != 2) {
						$temp = false;
					}
					$data['no_electric_connection_doc_dtl'] = $no_electric_connection_doc_dtl;
				} else {
					$temp = false;
				}
			}


			if ($tran = $this->model_transaction->checkPaymentfordoc($input)) {
				if ($tran['status'] != 1) {
					if ($tran['tran_mode_mstr_id'] == 2 || $tran['tran_mode_mstr_id'] == 3) {
						if ($tran['status'] == 2) {
							$temp = false;
							$msg = " Cheque Or DD Not Cleared ";
						} elseif ($tran['status'] == 3) {
							$temp = false;
							$msg = " Cheque Bounce, Citizen Need To Repayment";
						}
					}
				}
			} else {
				$msg = 'Payment is not done.';
				$temp = false;
			}

			if ($data['khata_no'] == "") {
				$msg = 'Please Update Application First. i.e. khata no, plot no, owner details etc';
				$temp = false;
				flashToast('message', 'Please update application first.');
			}

			$data['message'] = $msg ?? NULL;
			$data['show_rmc_btn'] = $temp;

			return view('property/Saf/saf_doc_upload', $data);
		}
	}

	public function SAFdocumentUpload($saf_dtl_id)
	{
		$data = (array)null;
		$session = session();
		$ulb_mstr = $session->get("ulb_dtl");
		$emp_details = $session->get("emp_details");
		if (is_numeric($saf_dtl_id)) {
			$data = $this->model_view_saf_dtl->getSafDtlBySafDtlId($saf_dtl_id);
		} else {
			$data = $this->model_view_saf_dtl->getSafDtlByMD5SafDtlId($saf_dtl_id);
		}

		//If Application not updated then update first
		if ($data["khata_no"] == null || $data["khata_no"] == "") {
			flashToast("message", "Application is not updated, So, Please update application first");
			return $this->response->redirect(base_url('SAF/backOfficeSAFUpdate/' . md5($data['saf_dtl_id'])));
		}
		if ($this->request->getMethod() == 'post') {
			$is_specially_data = $this->request->getvar('is_specially_data');
			$is_armed_data = $this->request->getvar('is_armed_data');
			$gender_data = $this->request->getvar('gender_data');
			$dob_data = $this->request->getvar('dob_data');

			$ulb_dtl = $this->model_ulb_mstr->getulb_list($ulb_mstr["ulb_mstr_id"]);
			$inputs = arrFilterSanitizeString($this->request->getVar());
			if (isset($inputs['btn_owner_doc_upload'])) {
				$rules = [
					'applicant_image_file' => 'uploaded[applicant_image_file]|max_size[applicant_image_file,5120]|ext_in[applicant_image_file,png,jpg,jpeg]',
					'applicant_doc_file' => 'uploaded[applicant_doc_file]|max_size[applicant_doc_file,5120]|ext_in[applicant_doc_file,png,jpg,jpeg,pdf]'
				];
				if ($is_specially_data == 1) {

					$rules['handicaped_document'] = 'uploaded[handicaped_document]|max_size[handicaped_document,5120]|ext_in[handicaped_document,png,jpg,jpeg,pdf]';
				}
				if ($is_armed_data == 1) {
					$rules['armed_force_document'] = 'uploaded[armed_force_document]|max_size[armed_force_document,5120]|ext_in[armed_force_document,png,jpg,jpeg,pdf]';
				}
				if ($gender_data == 1) {
					$rules['gender_document'] = 'uploaded[gender_document]|max_size[gender_document,5120]|ext_in[gender_document,png,jpg,jpeg,pdf]';
				}
				if ($dob_data == 1) {
					$rules['dob_document'] = 'uploaded[dob_document]|max_size[dob_document,5120]|ext_in[dob_document,png,jpg,jpeg,pdf]';
				}
				// print_var($rules);
				// return;

				if ($this->validate($rules)) {
					$applicant_image_file = $this->request->getFile('applicant_image_file');
					$applicant_doc_file = $this->request->getFile('applicant_doc_file');

					if ($is_specially_data == 1) {
						$handicaped_document = $this->request->getFile('handicaped_document');
						// $handicaped_doc_mstr_id = $this->model_saf_doc_dtl->get_doc_mstr_id('handicaped_document');
						$handicaped_doc_mstr_id = 25;
						// return;
					}
					if ($is_armed_data == 1) {
						$armed_force_document = $this->request->getFile('armed_force_document');
						// $armed_doc_mstr_id = $this->model_saf_doc_dtl->get_doc_mstr_id('armed_force_document');
						$armed_doc_mstr_id = 26;
					}
					if ($gender_data == 1) {
						$gender_document = $this->request->getFile('gender_document');
						// $armed_doc_mstr_id = $this->model_saf_doc_dtl->get_doc_mstr_id('armed_force_document');
						$gender_doc_mstr_id = 27;
					}
					if ($dob_data == 1) {
						$dob_document = $this->request->getFile('dob_document');
						// $armed_doc_mstr_id = $this->model_saf_doc_dtl->get_doc_mstr_id('armed_force_document');
						$dob_doc_mstr_id = 28;
					}



					if ($applicant_image_file->IsValid() && !$applicant_image_file->hasMoved() && $applicant_doc_file->IsValid() && !$applicant_doc_file->hasMoved()) {
						try {
							$this->db->transBegin();
							$input = [
								'saf_dtl_id' => $data['saf_dtl_id'],
								'saf_owner_dtl_id' => $inputs['saf_owner_dtl_id'],
								'owner_doc_mstr_id' => $inputs['owner_doc_mstr_id'],
								'emp_details_id' => $emp_details['id'],
								'created_on' => "NOW()",
								'status' => 1
							];

							if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->checkOwnerImgDataIsExistBySafOwnerDtlId($input)) {
								$delete_path = WRITEPATH . 'uploads/' . $saf_doc_dtl_id['doc_path'];
								// @unlink($delete_path);

								$newFileName = md5($saf_doc_dtl_id['id']);
								$file_ext = $applicant_image_file->getExtension();

								$path = $ulb_dtl['city'] . "/" . "applicant_image";
								$owner_img_path = $path . "/" . $newFileName . '.' . $file_ext;
								if ($applicant_image_file->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext)) {
									$this->model_saf_doc_dtl->updatetransImgpathById($saf_doc_dtl_id['id'], $owner_img_path);
								}
							} else if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->insertOwnerImgData($input)) {
								$newFileName = md5($saf_doc_dtl_id);
								$file_ext = $applicant_image_file->getExtension();

								$path = $ulb_dtl['city'] . "/" . "applicant_image";
								$owner_img_path = $path . "/" . $newFileName . '.' . $file_ext;
								if ($applicant_image_file->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext)) {
									$this->model_saf_doc_dtl->updatetransImgpathById($saf_doc_dtl_id, $owner_img_path);
								}
							}

							if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->checkOwnerDocDataIsExistBySafOwnerDtlId($input)) {

								$delete_path = WRITEPATH . 'uploads/' . $saf_doc_dtl_id['doc_path'];
								// @unlink($delete_path);
								deleteFile($delete_path);

								$newFileName = md5($saf_doc_dtl_id['id']);
								$file_ext = $applicant_doc_file->getExtension();

								$path = $ulb_dtl['city'] . "/" . "saf_doc_dtl";
								$owner_doc_path = $path . "/" . $newFileName . '.' . $file_ext;
								if ($applicant_doc_file->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext)) {
									$this->model_saf_doc_dtl->updatetransdocpathById($saf_doc_dtl_id['id'], $owner_doc_path, $input['owner_doc_mstr_id']);
								}
							} else if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->insertOwnerData($input)) {

								$newFileName = md5($saf_doc_dtl_id);
								$file_ext = $applicant_doc_file->getExtension();

								$path = $ulb_dtl['city'] . "/" . "saf_doc_dtl";
								$owner_doc_path = $path . "/" . $newFileName . '.' . $file_ext;
								if ($applicant_doc_file->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext)) {
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

							//Gender image upload
							if ($gender_data == 1) {
								if ($gender_document->IsValid() && !$gender_document->hasMoved()) {
									if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->checkGenderDocDataIsExistBySafOwnerDtlId($input)) {

										$delete_path = WRITEPATH . 'uploads/' . $saf_doc_dtl_id['doc_path'];
										// @unlink($delete_path);
										deleteFile($delete_path);

										$newFileName = md5($saf_doc_dtl_id['id']);
										$file_ext = $gender_document->getExtension();

										$path = $ulb_dtl['city'] . "/" . "saf_doc_dtl";
										$owner_doc_path = $path . "/" . $newFileName . '.' . $file_ext;
										if ($gender_document->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext)) {
											$this->model_saf_doc_dtl->updatetransdocpathById($saf_doc_dtl_id['id'], $owner_doc_path, $gender_doc_mstr_id);
										}
									} else if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->insertGenderData($input)) {

										$newFileName = md5($saf_doc_dtl_id);
										$file_ext = $gender_document->getExtension();

										$path = $ulb_dtl['city'] . "/" . "saf_doc_dtl";
										$owner_doc_path = $path . "/" . $newFileName . '.' . $file_ext;
										if ($gender_document->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext)) {
											$this->model_saf_doc_dtl->updatetransdocpathById($saf_doc_dtl_id, $owner_doc_path, $gender_doc_mstr_id);
										}
									}
								}
							}
							//Dob image upload
							if ($dob_data == 1) {
								if ($dob_document->IsValid() && !$dob_document->hasMoved()) {
									if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->checkDobDocDataIsExistBySafOwnerDtlId($input)) {

										$delete_path = WRITEPATH . 'uploads/' . $saf_doc_dtl_id['doc_path'];
										// @unlink($delete_path);
										deleteFile($delete_path);

										$newFileName = md5($saf_doc_dtl_id['id']);
										$file_ext = $dob_document->getExtension();

										$path = $ulb_dtl['city'] . "/" . "saf_doc_dtl";
										$owner_doc_path = $path . "/" . $newFileName . '.' . $file_ext;
										if ($dob_document->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext)) {
											$this->model_saf_doc_dtl->updatetransdocpathById($saf_doc_dtl_id['id'], $owner_doc_path, $dob_doc_mstr_id);
										}
									} else if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->insertDobData($input)) {

										$newFileName = md5($saf_doc_dtl_id);
										$file_ext = $dob_document->getExtension();

										$path = $ulb_dtl['city'] . "/" . "saf_doc_dtl";
										$owner_doc_path = $path . "/" . $newFileName . '.' . $file_ext;
										if ($dob_document->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext)) {
											$this->model_saf_doc_dtl->updatetransdocpathById($saf_doc_dtl_id, $owner_doc_path, $dob_doc_mstr_id);
										}
									}
								}
							}



							if ($this->db->transStatus() === FALSE) {
								$this->db->transRollback();
								flashToast("message", "Oops, Owner document not uploaded.");
							} else {
								$this->db->transCommit();
								flashToast("message", "Owner Document uploaded successfully.");
							}
						} catch (Exception $e) {

							flashToast("message", $e->getMessage());
						}
					} else {
						flashToast("message", "something errors in owner details.");
					}
				} else {

					$errMsg = $this->validator->listErrors();
					flashToast("message", $errMsg);
				}
			}

			if (isset($inputs['btn_upload'])) {
				$rules = [
					'upld_doc_path' => 'uploaded[upld_doc_path]|max_size[upld_doc_path,5120]|ext_in[upld_doc_path,pdf]',
				];

				if ($this->validate($rules)) {
					$upld_doc_path = $this->request->getFile('upld_doc_path');
					if ($upld_doc_path->IsValid()  && !$upld_doc_path->hasMoved()) {
						try {
							$this->db->transBegin();
							$input = [
								'saf_dtl_id' => $data['saf_dtl_id'],
								'upld_doc_mstr_id' => $inputs['doc_mstr_id'],
								'doc_mstr_idcheck' => $inputs['doc_mstr_id'],
								'other_doc' => $inputs['other_doc'],
								'other_doccheck' => $inputs['other_doc'],
								'emp_details_id' => $emp_details['id'],
								'created_on' => "NOW()",
								'status' => 1,
							];

							if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->checkDocDataIsExist($input)) {
								// print_var($saf_doc_dtl_id);return;
								$delete_path = WRITEPATH . 'uploads/' . $saf_doc_dtl_id['doc_path'];
								// unlink($delete_path);
								deleteFile($delete_path);

								$newFileName = md5($saf_doc_dtl_id['id']);
								$file_ext = $upld_doc_path->getExtension();

								$path = $ulb_dtl['city'] . "/" . "saf_doc_dtl";
								$upld_doc_path->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext);
								$upld_doc_path_save = $path . "/" . $newFileName . '.' . $file_ext;
								$this->model_saf_doc_dtl->updatetransdocpathById($saf_doc_dtl_id['id'], $upld_doc_path_save, $input['upld_doc_mstr_id']);
							} else if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->insertFrData($input)) {
								$newFileName = md5($saf_doc_dtl_id);
								$file_ext = $upld_doc_path->getExtension();
								$path = $ulb_dtl['city'] . "/" . "saf_doc_dtl";

								$upld_doc_path->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext);
								$upld_doc_path_save = $path . "/" . $newFileName . '.' . $file_ext;
								$this->model_saf_doc_dtl->updatetransdocpathById($saf_doc_dtl_id, $upld_doc_path_save, $input['upld_doc_mstr_id']);
							}

							if ($this->db->transStatus() === FALSE) {
								$this->db->transRollback();
								flashToast("message", "Oops, Document not uploaded.");
							} else {
								$this->db->transCommit();
								flashToast("message", "Document uploaded successfully.");
							}
						} catch (Exception $e) {
							flashToast("message", $e->getMessage());
						}
					}
				} else {
					$errMsg = $this->validator->listErrors();
					flashToast("message", $errMsg);
				}
			}
			if (isset($inputs['btn_upload_additional'])) {
				// print_var($inputs);
				// die;
				$rules = [
					'additional_doc_file' => 'uploaded[additional_doc_file]|max_size[additional_doc_file,5120]|ext_in[additional_doc_file,pdf]',
				];

				if ($this->validate($rules)) {
					$upld_doc_path = $this->request->getFile('additional_doc_file');
					if ($upld_doc_path->IsValid()  && !$upld_doc_path->hasMoved()) {
						try {
							$this->db->transBegin();
							$input = [
								'saf_dtl_id' => $data['saf_dtl_id'],
								'upld_doc_mstr_id' => $inputs['doc_mstr_id'],
								'doc_mstr_idcheck' => $inputs['doc_mstr_id'],
								'other_doc' => $inputs['doc_mstr_id']==34 ?($inputs["otherDocName"]??'additional_doc'):"additional_doc", //find other doc
		    					'other_doccheck' => $inputs['doc_mstr_id']==34 ?($inputs["otherDocName"]??'additional_doc'):"additional_doc",
								'emp_details_id' => $emp_details['id'],
								'created_on' => "NOW()",
								'status' => 1,
							];

							if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->checkAdditionalDocDataIsExist($input)) {
								// echo "inside duplicate data";
								// die;
								// print_var($saf_doc_dtl_id);return;
								$delete_path = WRITEPATH . 'uploads/' . $saf_doc_dtl_id['doc_path'];
								// unlink($delete_path);
								deleteFile($delete_path);

								$newFileName = md5($saf_doc_dtl_id['id']);
								$file_ext = $upld_doc_path->getExtension();

								$path = $ulb_dtl['city'] . "/" . "saf_doc_dtl";
								$upld_doc_path->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext);
								$upld_doc_path_save = $path . "/" . $newFileName . '.' . $file_ext;

								$this->model_saf_doc_dtl->updatetransdocpathById($saf_doc_dtl_id['id'], $upld_doc_path_save, $input['upld_doc_mstr_id']);
							} else if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->insertFrData($input)) {
								// echo "inside fresh";
								// die;
								$newFileName = md5($saf_doc_dtl_id);
								$file_ext = $upld_doc_path->getExtension();
								$path = $ulb_dtl['city'] . "/" . "saf_doc_dtl";

								$upld_doc_path->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext);
								$upld_doc_path_save = $path . "/" . $newFileName . '.' . $file_ext;

								$this->model_saf_doc_dtl->updatetransdocpathById($saf_doc_dtl_id, $upld_doc_path_save, $input['upld_doc_mstr_id']);
								// echo "inside additional updocccccdate";
								// die;
							}

							if ($this->db->transStatus() === FALSE) {
								$this->db->transRollback();
								flashToast("message", "Oops, Document not uploaded.");
							} else {
								$this->db->transCommit();
								flashToast("message", "Document uploaded successfully.");
							}
						} catch (Exception $e) {
							flashToast("message", $e->getMessage());
						}
					}
				} else {
					$errMsg = $this->validator->listErrors();
					flashToast("message", $errMsg);
				}
			}

		}

		$data['saf_owner_detail'] = $this->model_saf_owner_detail->getOwnerdtlBySAFId(["saf_dtl_id" => $data['saf_dtl_id']]);
		// applicant img & document
		foreach ($data['saf_owner_detail'] as $key => $owner_detail) {
			$input = [
				'saf_dtl_id' => $data['saf_dtl_id'],
				'saf_owner_dtl_id' => $owner_detail['id'],
			];
			$data['saf_owner_detail'][$key]['applicant_img_dtl'] = $this->model_saf_doc_dtl->getApplicantImgBySafDtlAndSafOwnerDtlId($input);
			$data['saf_owner_detail'][$key]['applicant_doc_dtl'] = $this->model_saf_doc_dtl->getApplicantDocBySafDtlAndSafOwnerDtlId($input);
			$data['saf_owner_detail'][$key]['Handicaped_doc_dtl'] = $this->model_saf_doc_dtl->getHandicapedDocBySafDtlAndSafOwnerDtlId($input);
			$data['saf_owner_detail'][$key]['Armed_doc_dtl'] = $this->model_saf_doc_dtl->getArmedDocBySafDtlAndSafOwnerDtlId($input);
			$data['saf_owner_detail'][$key]['gender_doc_dtl'] = $this->model_saf_doc_dtl->getGenderDocBySafDtlAndSafOwnerDtlId($input);
			$data['saf_owner_detail'][$key]['dob_doc_dtl'] = $this->model_saf_doc_dtl->getDobDocBySafDtlAndSafOwnerDtlId($input);
		}

		$data['is_trust'] = $this->model_saf_floor_details->isTrust($data['saf_dtl_id']);
		$data['owner_doc_list'] = $this->model_doc_mstr->getDataByDocType('other');
		$data['additional_doc_list'] = $this->model_doc_mstr->getDataByDocType('additional_doc');
		$data['saf_doc_list'] = $this->model_doc_mstr->HaveToUploadDoc($data);
		$data["uploaded_doc_list"] = $this->model_saf_doc_dtl->getAllActiveDocuments($data['saf_dtl_id']);
		$data["additional_document"] = $this->model_saf_doc_dtl->getAdditionalDocument($data['saf_dtl_id']);

		$data["ulb_mstr_id"] = $ulb_mstr["ulb_mstr_id"];
		
		return view('property/Saf/SAFdocumentUpload', $data);
	}

	public function view($saf_dtl_id = null)
	{
		$data = (array)null;
		$session = Session();
		$ulb = $session->get('ulb_dtl');
		$emp_mstr = $session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];

		if (is_numeric($saf_dtl_id)) {
			$data = $this->model_view_saf_dtl->getSafDtlBySafDtlId($saf_dtl_id);
		} else  {
			$data = $this->model_view_saf_dtl->getSafDtlByMD5SafDtlId($saf_dtl_id);
		}
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

		// $data["doc_list"] = $this->model_saf_doc_dtl->getAllActiveDocuments($data['saf_dtl_id']);
		// $data["doc_list"] = $this->model_saf_doc_dtl->getAllDocumentsWithOwner($data['saf_dtl_id']);
		$data["doc_list"] = $this->model_saf_doc_dtl->getAllDocumentsWithOwner2($data['saf_dtl_id']);
		//print_var($data["doc_list"]);
		$data["ulb_mstr_id"] = $ulb["ulb_mstr_id"];
		// print_var($data);
		// die;
		if($this->request->getMethod()=='post')
        {
            $inputs = arrFilterSanitizeString($this->request->getVar());

            if (isset($inputs["btn_verify"])) {
                $input = [
                    "id" => $inputs["saf_doc_dtl_id"],
                    "verify_status" => 1, //Verified
                    "remarks" => "Verified",
                    "verified_by_emp_id" => $login_emp_details_id,
                ];

                $this->model_saf_doc_dtl->verifyDocument($input);
                return $this->response->redirect(base_url("safDoc/view/" . $saf_dtl_id) . "#documen");
            }

            if (isset($inputs["btn_reject"])) {
                $input = [
                    "id" => $inputs["saf_doc_dtl_id"],
                    "verify_status" => 2, //Rejected
                    "remarks" => 'Rejected',
                    "verified_by_emp_id" => $login_emp_details_id,
                ];

                $this->model_saf_doc_dtl->verifyDocument($input);
                return $this->response->redirect(base_url("safDoc/view/" . $saf_dtl_id) . "#documen");
            }
		}
		return view('property/saf/bo_doc_upload_saf_view', $data);
	}

	public function backtocitizenView($saf_dtl_id_MD5,$levelPassId) //new change
	{
		$data = (array)null;
		$session = session();
		$ulb_mstr = $session->get("ulb_dtl");
		$emp_details = $session->get("emp_details");
		$data = $this->model_view_saf_dtl->getSafDtlByMD5SafDtlId($saf_dtl_id_MD5);
		$data['levelPassId']=$levelPassId; //new change

		if ($this->request->getMethod() == 'post') {
			$ulb_dtl = $this->model_ulb_mstr->getulb_list($ulb_mstr["ulb_mstr_id"]);
			$inputs = arrFilterSanitizeString($this->request->getVar());

			$applicant_doc_data = $this->request->getvar('applicant_doc_data');
			$is_specially_data = $this->request->getvar('is_specially_data');
			$is_armed_data = $this->request->getvar('is_armed_data');
			$gender_data = $this->request->getvar('gender_data');
			$dob_data = $this->request->getvar('dob_data');

			if (isset($inputs['btn_owner_doc_upload'])) {
				$applicant_image_rule = [
					'applicant_image_file' => 'uploaded[applicant_image_file]|max_size[applicant_image_file,5120]|ext_in[applicant_image_file,png,jpg,jpeg]'
				];

				if ($applicant_doc_data == 1) {

					$rules = [
						// 'applicant_image_file' => 'uploaded[applicant_image_file]|max_size[applicant_image_file,5120]|ext_in[applicant_image_file,png,jpg,jpeg]',
						'applicant_doc_file' => 'uploaded[applicant_doc_file]|max_size[applicant_doc_file,5120]|ext_in[applicant_doc_file,png,jpg,jpeg,pdf]',
					];
				}
				if ($is_specially_data == 1) {

					$rules['handicaped_document'] = 'uploaded[handicaped_document]|max_size[handicaped_document,5120]|ext_in[handicaped_document,png,jpg,jpeg,pdf]';
				}
				if ($is_armed_data == 1) {
					$rules['armed_force_document'] = 'uploaded[armed_force_document]|max_size[armed_force_document,5120]|ext_in[armed_force_document,png,jpg,jpeg,pdf]';
				}
				if ($gender_data == 1) {
					$rules['gender_document'] = 'uploaded[gender_document]|max_size[gender_document,5120]|ext_in[gender_document,png,jpg,jpeg,pdf]';
				}
				if ($dob_data == 1) {
					$rules['dob_document'] = 'uploaded[dob_document]|max_size[dob_document,5120]|ext_in[dob_document,png,jpg,jpeg,pdf]';
				}

				if ($this->validate($applicant_image_rule)) {
					// echo "inside validation rules";
					// die;
					$input = [
						'saf_dtl_id' => $data['saf_dtl_id'],
						'saf_owner_dtl_id' => $inputs['saf_owner_dtl_id'],
						'emp_details_id' => $emp_details['id'],
						'created_on' => "NOW()",
						'status' => 1
					];

					$applicant_image_file = $this->request->getFile('applicant_image_file');

					if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->checkOwnerImgDataIsExistBySafOwnerDtlId2($input)) {
						$Deactivate_old_doc = $this->model_saf_doc_dtl->deactivateOldDoc($saf_doc_dtl_id['id']);
						if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->insertOwnerImgData($input)) {
							$newFileName = md5($saf_doc_dtl_id);
							$file_ext = $applicant_image_file->getExtension();
	
							$path = $ulb_dtl['city'] . "/" . "applicant_image";
							$owner_img_path = $path . "/" . $newFileName . '.' . $file_ext;
							if ($applicant_image_file->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext)) {
								$this->model_saf_doc_dtl->updatetransImgpathById($saf_doc_dtl_id, $owner_img_path);
							}
						}
					} else if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->insertOwnerImgData($input)) {
						$newFileName = md5($saf_doc_dtl_id);
						$file_ext = $applicant_image_file->getExtension();

						$path = $ulb_dtl['city'] . "/" . "applicant_image";
						$owner_img_path = $path . "/" . $newFileName . '.' . $file_ext;
						if ($applicant_image_file->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext)) {
							$this->model_saf_doc_dtl->updatetransImgpathById($saf_doc_dtl_id, $owner_img_path);
						}
					}


				}

				if ($this->validate($rules)) {
					$applicant_image_file = $this->request->getFile('applicant_image_file');
					$applicant_doc_file = $this->request->getFile('applicant_doc_file');

					if ($is_specially_data == 1) {
						$handicaped_document = $this->request->getFile('handicaped_document');
						// $handicaped_doc_mstr_id = $this->model_saf_doc_dtl->get_doc_mstr_id('handicaped_document');
						$handicaped_doc_mstr_id = 25;
						// return;
					}
					if ($is_armed_data == 1) {
						$armed_force_document = $this->request->getFile('armed_force_document');
						// $armed_doc_mstr_id = $this->model_saf_doc_dtl->get_doc_mstr_id('armed_force_document');
						$armed_doc_mstr_id = 26;
					}
					if ($gender_data == 1) {
						$gender_document = $this->request->getFile('gender_document');
						// $armed_doc_mstr_id = $this->model_saf_doc_dtl->get_doc_mstr_id('armed_force_document');
						$gender_doc_mstr_id = 27;
					}
					if ($dob_data == 1) {
						$dob_document = $this->request->getFile('dob_document');
						// $armed_doc_mstr_id = $this->model_saf_doc_dtl->get_doc_mstr_id('armed_force_document');
						$dob_doc_mstr_id = 28;
					}


					// echo "before doc";
					// return;
					// if ($applicant_image_file->IsValid() && !$applicant_image_file->hasMoved() && $applicant_doc_file->IsValid() && !$applicant_doc_file->hasMoved()) {
					try {
						$this->db->transBegin();
						$input = [
							'saf_dtl_id' => $data['saf_dtl_id'],
							'saf_owner_dtl_id' => $inputs['saf_owner_dtl_id'],
							'emp_details_id' => $emp_details['id'],
							'created_on' => "NOW()",
							'status' => 1
						];
						// echo "after doc";
						// return;

						/*	if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->checkOwnerImgDataIsExistBySafOwnerDtlId2($input)) {
								$Deactivate_old_doc = $this->model_saf_doc_dtl->deactivateOldDoc($saf_doc_dtl_id['id']);
								// $delete_path = WRITEPATH . 'uploads/' . $saf_doc_dtl_id['doc_path'];
								// //unlink($delete_path);

								// $newFileName = md5($saf_doc_dtl_id['id']);
								// $file_ext = $applicant_image_file->getExtension();

								// $path = $ulb_dtl['city'] . "/" . "applicant_image";
								// $owner_img_path = $path . "/" . $newFileName . '.' . $file_ext;
								// if ($applicant_image_file->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext)) {
								// 	$this->model_saf_doc_dtl->updatetransImgpathById($saf_doc_dtl_id['id'], $owner_img_path);
								// }
							}
							// else if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->insertOwnerImgData($input)) {
							// 	$newFileName = md5($saf_doc_dtl_id);
							// 	$file_ext = $applicant_image_file->getExtension();

							// 	$path = $ulb_dtl['city'] . "/" . "applicant_image";
							// 	$owner_img_path = $path . "/" . $newFileName . '.' . $file_ext;
							// 	if ($applicant_image_file->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext)) {
							// 		$this->model_saf_doc_dtl->updatetransImgpathById($saf_doc_dtl_id, $owner_img_path);
							// 	}
							// }
							 if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->insertOwnerImgData($input)) {
								$newFileName = md5($saf_doc_dtl_id);
								$file_ext = $applicant_image_file->getExtension();

								$path = $ulb_dtl['city'] . "/" . "applicant_image";
								$owner_img_path = $path . "/" . $newFileName . '.' . $file_ext;
								if ($applicant_image_file->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext)) {
									$this->model_saf_doc_dtl->updatetransImgpathById($saf_doc_dtl_id, $owner_img_path);
								}
							} */
						if ($applicant_doc_data == 1) {
							$input=array(null);
							$input = [
								'saf_dtl_id' => $data['saf_dtl_id'],
								'saf_owner_dtl_id' => $inputs['saf_owner_dtl_id'],
								'owner_doc_mstr_id' => $inputs['owner_doc_mstr_id'],
								'emp_details_id' => $emp_details['id'],
								'created_on' => "NOW()",
								'status' => 1
							];
							if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->checkOwnerDocDataIsExistBySafOwnerDtlId2($input)) {
								$Deactivate_old_doc = $this->model_saf_doc_dtl->deactivateOldDoc($saf_doc_dtl_id['id']);

								// $delete_path = WRITEPATH . 'uploads/' . $saf_doc_dtl_id['doc_path'];
								// //unlink($delete_path);

								// $newFileName = md5($saf_doc_dtl_id['id']);
								// $file_ext = $applicant_doc_file->getExtension();

								// $path = $ulb_dtl['city'] . "/" . "saf_doc_dtl";
								// $owner_doc_path = $path . "/" . $newFileName . '.' . $file_ext;
								// if ($applicant_doc_file->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext)) {
								// 	$this->model_saf_doc_dtl->updatetransdocpathById($saf_doc_dtl_id['id'], $owner_doc_path, $input['owner_doc_mstr_id']);
								// }
							}
							// else if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->insertOwnerData($input)) {

							// 	$newFileName = md5($saf_doc_dtl_id);
							// 	$file_ext = $applicant_doc_file->getExtension();

							// 	$path = $ulb_dtl['city'] . "/" . "saf_doc_dtl";
							// 	$owner_doc_path = $path . "/" . $newFileName . '.' . $file_ext;
							// 	if ($applicant_doc_file->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext)) {
							// 		$this->model_saf_doc_dtl->updatetransdocpathById($saf_doc_dtl_id, $owner_doc_path, $input['owner_doc_mstr_id']);
							// 	}
							// }
							if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->insertOwnerData($input)) {

								$newFileName = md5($saf_doc_dtl_id);
								$file_ext = $applicant_doc_file->getExtension();

								$path = $ulb_dtl['city'] . "/" . "saf_doc_dtl";
								$owner_doc_path = $path . "/" . $newFileName . '.' . $file_ext;
								if ($applicant_doc_file->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext)) {
									$this->model_saf_doc_dtl->updatetransdocpathById($saf_doc_dtl_id, $owner_doc_path, $input['owner_doc_mstr_id']);
								}
							}
						}

						//is sepcially abled image upload
						if ($is_specially_data == 1) {
							if ($handicaped_document->IsValid() && !$handicaped_document->hasMoved()) {
								if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->checkHandicapedDocDataIsExistBySafOwnerDtlId2($input)) {
									$Deactivate_old_doc = $this->model_saf_doc_dtl->deactivateOldDoc($saf_doc_dtl_id['id']);
								}
								if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->insertHandicapedData($input)) {

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
								if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->checkArmedDocDataIsExistBySafOwnerDtlId2($input)) {

									$Deactivate_old_doc = $this->model_saf_doc_dtl->deactivateOldDoc($saf_doc_dtl_id['id']);
								}
								if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->insertArmedData($input)) {

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

						//Gender image upload
						if ($gender_data == 1) {

							if ($gender_document->IsValid() && !$gender_document->hasMoved()) {
								if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->checkGenderDocDataIsExistBySafOwnerDtlId2($input)) {
									$Deactivate_old_doc = $this->model_saf_doc_dtl->deactivateOldDoc($saf_doc_dtl_id['id']);
								}
								if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->insertGenderData($input)) {

									$newFileName = md5($saf_doc_dtl_id);
									$file_ext = $gender_document->getExtension();

									$path = $ulb_dtl['city'] . "/" . "saf_doc_dtl";
									$owner_doc_path = $path . "/" . $newFileName . '.' . $file_ext;
									if ($gender_document->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext)) {
										$this->model_saf_doc_dtl->updatetransdocpathById($saf_doc_dtl_id, $owner_doc_path, $gender_doc_mstr_id);
									}
								}
							}
						}
						//Dob image upload
						if ($dob_data == 1) {

							if ($dob_document->IsValid() && !$dob_document->hasMoved()) {
								if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->checkDobDocDataIsExistBySafOwnerDtlId($input)) {
									$Deactivate_old_doc = $this->model_saf_doc_dtl->deactivateOldDoc($saf_doc_dtl_id['id']);
								}
								if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->insertDobData($input)) {

									$newFileName = md5($saf_doc_dtl_id);
									$file_ext = $dob_document->getExtension();

									$path = $ulb_dtl['city'] . "/" . "saf_doc_dtl";
									$owner_doc_path = $path . "/" . $newFileName . '.' . $file_ext;
									if ($dob_document->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext)) {
										$this->model_saf_doc_dtl->updatetransdocpathById($saf_doc_dtl_id, $owner_doc_path, $dob_doc_mstr_id);
									}
								}
							}
						}



						if ($this->db->transStatus() === FALSE) {
							$this->db->transRollback();
							flashToast("message", "Oops, Owner document not uploaded.");
						} else {
							$this->db->transCommit();
							flashToast("message", "Owner Document uploaded successfully.");
						}
					} catch (Exception $e) {

						flashToast("message", $e->getMessage());
					}
					// } else {
					// 	flashToast("message", "something errors in owner details.");
					// }
				} else {

					$errMsg = $this->validator->listErrors();
					flashToast("message", $errMsg);
				}
			}

			if (isset($inputs['btn_upload'])) {
				$rules = [
					'upld_doc_path' => 'uploaded[upld_doc_path]|max_size[upld_doc_path,5120]|ext_in[upld_doc_path,pdf]',
				];
				if ($this->validate($rules)) {
					$upld_doc_path = $this->request->getFile('upld_doc_path');
					if ($upld_doc_path->IsValid()  && !$upld_doc_path->hasMoved()) {
						try {
							$this->db->transBegin();
							$input = [
								'saf_dtl_id' => $data['saf_dtl_id'],
								'upld_doc_mstr_id' => $inputs['doc_mstr_id'],
								'doc_mstr_idcheck' => $inputs['doc_mstr_id'],
								'other_doc' => $inputs['other_doc'],
								'other_doccheck' => $inputs['other_doc'],
								'emp_details_id' => $emp_details['id'],
								'created_on' => "NOW()",
								'status' => 1,
							];

							if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->checkDocDataIsExist2($input)) {
								$Deactivate_old_doc = $this->model_saf_doc_dtl->deactivateOldDoc($saf_doc_dtl_id['id']);
							}

							if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->insertFrData($input)) {
								$newFileName = md5($saf_doc_dtl_id);
								$file_ext = $upld_doc_path->getExtension();
								$path = $ulb_dtl['city'] . "/" . "saf_doc_dtl";

								$upld_doc_path->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext);
								$upld_doc_path_save = $path . "/" . $newFileName . '.' . $file_ext;
								$this->model_saf_doc_dtl->updatetransdocpathById($saf_doc_dtl_id, $upld_doc_path_save, $input['upld_doc_mstr_id']);
							}

							if ($this->db->transStatus() === FALSE) {
								$this->db->transRollback();
								flashToast("message", "Oops, Document not uploaded.");
							} else {
								$this->db->transCommit();
								flashToast("message", "Document uploaded successfully.");
							}
						} catch (Exception $e) {

							flashToast("message", $e->getMessage());
						}
					}
				} else {

					$errMsg = $this->validator->listErrors();
					flashToast("message", $errMsg);
				}
			}
			if (isset($inputs['btn_upload_additional'])) {
				// print_var($inputs);
				// die;
				$rules = [
					'additional_doc_file' => 'uploaded[additional_doc_file]|max_size[additional_doc_file,5120]|ext_in[additional_doc_file,pdf]',
				];

				if ($this->validate($rules)) {
					$upld_doc_path = $this->request->getFile('additional_doc_file');
					if ($upld_doc_path->IsValid()  && !$upld_doc_path->hasMoved()) {
						try {
							$this->db->transBegin();
							$input = [
								'saf_dtl_id' => $data['saf_dtl_id'],
								'upld_doc_mstr_id' => $inputs['doc_mstr_id'],
								'doc_mstr_idcheck' => $inputs['doc_mstr_id'],
								'other_doc' => $inputs['doc_mstr_id']==34 ?($inputs["otherDocName"]??'additional_doc'):"additional_doc", //find other doc
								'other_doccheck' => $inputs['doc_mstr_id']==34 ?($inputs["otherDocName"]??'additional_doc'):"additional_doc",
								'emp_details_id' => $emp_details['id'],
								'created_on' => "NOW()",
								'status' => 1,
							];

							if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->checkAdditionalDocDataIsExist2($input)) {
								$Deactivate_old_doc = $this->model_saf_doc_dtl->deactivateOldDoc($saf_doc_dtl_id['id']);
								// echo "inside duplicate data";
								// die;
								// print_var($saf_doc_dtl_id);return;
								// $delete_path = WRITEPATH . 'uploads/' . $saf_doc_dtl_id['doc_path'];
								// //unlink($delete_path);

								// $newFileName = md5($saf_doc_dtl_id['id']);
								// $file_ext = $upld_doc_path->getExtension();

								// $path = $ulb_dtl['city'] . "/" . "saf_doc_dtl";
								// $upld_doc_path->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext);
								// $upld_doc_path_save = $path . "/" . $newFileName . '.' . $file_ext;

								// $this->model_saf_doc_dtl->updatetransdocpathById($saf_doc_dtl_id['id'], $upld_doc_path_save, $input['upld_doc_mstr_id']);
							}
							//  else if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->insertFrData($input)) {
							// 	// echo "inside fresh";
							// 	// die;
							// 	$newFileName = md5($saf_doc_dtl_id);
							// 	$file_ext = $upld_doc_path->getExtension();
							// 	$path = $ulb_dtl['city'] . "/" . "saf_doc_dtl";

							// 	$upld_doc_path->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext);
							// 	$upld_doc_path_save = $path . "/" . $newFileName . '.' . $file_ext;

							// 	$this->model_saf_doc_dtl->updatetransdocpathById($saf_doc_dtl_id, $upld_doc_path_save, $input['upld_doc_mstr_id']);
							// 	// echo "inside additional updocccccdate";
							// 	// die;
							// }
							if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->insertFrData($input)) {
								// echo "inside fresh";
								// die;
								$newFileName = md5($saf_doc_dtl_id);
								$file_ext = $upld_doc_path->getExtension();
								$path = $ulb_dtl['city'] . "/" . "saf_doc_dtl";

								$upld_doc_path->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext);
								$upld_doc_path_save = $path . "/" . $newFileName . '.' . $file_ext;

								$this->model_saf_doc_dtl->updatetransdocpathById($saf_doc_dtl_id, $upld_doc_path_save, $input['upld_doc_mstr_id']);
								// echo "inside additional updocccccdate";
								// die;
							}

							if ($this->db->transStatus() === FALSE) {
								$this->db->transRollback();
								flashToast("message", "Oops, Document not uploaded.");
							} else {
								$this->db->transCommit();
								flashToast("message", "Document uploaded successfully.");
							}
						} catch (Exception $e) {
							flashToast("message", $e->getMessage());
						}
					}
				} else {
					$errMsg = $this->validator->listErrors();
					flashToast("message", $errMsg);
				}
			}

			if (isset($inputs['btn_upload_extra'])) {
				// print_var($inputs);
				// die;
				$rules = [
					'extra_doc_file' => 'uploaded[extra_doc_file]|max_size[extra_doc_file,5120]|ext_in[extra_doc_file,pdf]',
				];

				if ($this->validate($rules)) {
					$upld_doc_path = $this->request->getFile('extra_doc_file');
					if ($upld_doc_path->IsValid()  && !$upld_doc_path->hasMoved()) {
						try {
							$this->db->transBegin();
							$input = [
								'saf_dtl_id' => $data['saf_dtl_id'],
								'upld_doc_mstr_id' => $inputs['doc_mstr_id'],
								'doc_mstr_idcheck' => $inputs['doc_mstr_id'],
								'other_doc' => $inputs['doc_mstr_id']==34 ?($inputs["otherDocName"]??'extra_doc'):'extra_doc', //find other doc
								'other_doccheck' => $inputs['doc_mstr_id']==34 ?($inputs["otherDocName"]??'extra_doc'):'extra_doc',
								'emp_details_id' => $emp_details['id'],
								'created_on' => "NOW()",
								'status' => 1,
							];
							if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->checkAdditionalDocDataIsExist2($input)) {
								$Deactivate_old_doc = $this->model_saf_doc_dtl->deactivateOldDoc($saf_doc_dtl_id['id']);
							}
							if ($saf_doc_dtl_id = $this->model_saf_doc_dtl->insertFrData($input)) {
								$newFileName = md5($saf_doc_dtl_id);
								$file_ext = $upld_doc_path->getExtension();
								$path = $ulb_dtl['city'] . "/" . "saf_doc_dtl";

								$upld_doc_path->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext);
								$upld_doc_path_save = $path . "/" . $newFileName . '.' . $file_ext;

								$this->model_saf_doc_dtl->updatetransdocpathById($saf_doc_dtl_id, $upld_doc_path_save, $input['upld_doc_mstr_id']);
							}

							if ($this->db->transStatus() === FALSE) {
								$this->db->transRollback();
								flashToast("message", "Oops, Document not uploaded.");
							} else {
								$this->db->transCommit();
								flashToast("message", "Document uploaded successfully.");
							}
						} catch (Exception $e) {
							flashToast("message", $e->getMessage());
						}
					}
				} else {
					$errMsg = $this->validator->listErrors();
					flashToast("message", $errMsg);
				}
			}
			
			//remove document having status 2 and verify status 0
			$this->model_saf_doc_dtl->deleteReplacedImage($data['saf_dtl_id']);

		}

		$data['saf_owner_detail'] = $this->model_saf_owner_detail->getOwnerdtlBySAFId(["saf_dtl_id" => $data['saf_dtl_id']]);
		// applicant img & document
		foreach ($data['saf_owner_detail'] as $key => $owner_detail) {
			$input = [
				'saf_dtl_id' => $data['saf_dtl_id'],
				'saf_owner_dtl_id' => $owner_detail['id'],
			];
			// $data['saf_owner_detail'][$key]['applicant_img_dtl'] = $this->model_saf_doc_dtl->getApplicantImgBySafDtlAndSafOwnerDtlId($input);
			// $data['saf_owner_detail'][$key]['applicant_doc_dtl'] = $this->model_saf_doc_dtl->getApplicantDocBySafDtlAndSafOwnerDtlId($input);

			$data['saf_owner_detail'][$key]['applicant_img_dtl'] = $this->model_saf_doc_dtl->getApplicantImgBySafDtlAndSafOwnerDtlId($input);
			$data['saf_owner_detail'][$key]['applicant_doc_dtl'] = $this->model_saf_doc_dtl->getApplicantDocBySafDtlAndSafOwnerDtlId($input);
			$data['saf_owner_detail'][$key]['Handicaped_doc_dtl'] = $this->model_saf_doc_dtl->getHandicapedDocBySafDtlAndSafOwnerDtlId($input);
			$data['saf_owner_detail'][$key]['Armed_doc_dtl'] = $this->model_saf_doc_dtl->getArmedDocBySafDtlAndSafOwnerDtlId($input);
			$data['saf_owner_detail'][$key]['gender_doc_dtl'] = $this->model_saf_doc_dtl->getGenderDocBySafDtlAndSafOwnerDtlId($input);
			$data['saf_owner_detail'][$key]['dob_doc_dtl'] = $this->model_saf_doc_dtl->getDobDocBySafDtlAndSafOwnerDtlId($input);
		}

		$data['owner_doc_list'] = $this->model_doc_mstr->getDataByDocType('other');
		$data['additional_doc_list'] = $this->model_doc_mstr->getDataByDocType('additional_doc');
		$data['saf_doc_list'] = $this->model_doc_mstr->HaveToUploadDoc($data);
		$data["uploaded_doc_list"] = $this->model_saf_doc_dtl->getAllActiveDocuments($data['saf_dtl_id']);
		$data["additional_document"] = $this->model_saf_doc_dtl->getAdditionalDocument($data['saf_dtl_id']);
		$data["extra_document"] = $this->model_saf_doc_dtl->getExtraDocument($data['saf_dtl_id']);

		$data["ulb_mstr_id"] = $ulb_mstr["ulb_mstr_id"];
		// print_var($data);
		// die;
		return view('property/Saf/backtocitizenView', $data);
	}



	public function send_rmc($id)
	{
		$sql = "SELECT * FROM tbl_level_pending_dtl WHERE saf_dtl_id=".$id;
		$test = $this->db->query($sql)->getFirstRow();
		if ($test && $test->sender_user_type_id!=5 && $test->receiver_user_type_id!=11) {
			return $this->response->redirect(base_url('safdtl/full/' . md5($id)));
		} else {
			$Session = Session();
			$emp_mstr = $Session->get("emp_details");
			$sender_emp_details_id = $emp_mstr["id"];

			$data = (array)null;
			$data['saf_id'] = $id;
			$leveldata = [
				'saf_dtl_id' => $id,
				'sender_user_type_id' => 11,
				'receiver_user_type_id' => 6,
				'forward_date' => date('Y-m-d'),
				'forward_time' => date('H:i:s'),
				'created_on' => date('Y-m-d H:i:s'),
				'remarks' => 'Payment Done And Document Uploaded',
				'verification_status' => 0,
				'sender_emp_details_id' => $sender_emp_details_id
			];
			$this->db->transBegin();
			if($test){
				$leveldata2 = [
					'level_pending_dtl_id' => $test->id,
					'forward_date' => date('Y-m-d'),
					'forward_time' => date('H:i:s'),
					'verification_status' => 1,
					'status'=> 0,
					'sender_emp_details_id' => $sender_emp_details_id,
					'receiver_emp_details_id' => $sender_emp_details_id,

				];
				$this->model_level_pending_dtl->updatelevelpendingById($leveldata2);
			}

			$this->model_level_pending_dtl->insrtlevelpendingdtl($leveldata);
			$this->model_level_pending_dtl->bugfix_level_pending($leveldata); //Shashi

			//$this->model_saf_doc_dtl->updatestatusDocUpload($data);

			$this->model_saf_dtl->updateSafstatusDocUpload($data);

			if ($this->db->transStatus() === FALSE) {
				$this->db->transRollback();
				flashToast("message", "Oops, Application couldn't send to ULB.");
				return $this->response->redirect(base_url('safDoc/SAFdocumentUpload/' . md5($data['saf_id'])));
			} else {
				$this->db->transCommit();
				flashToast("message", "Application sent to ULB.");
				return $this->response->redirect(base_url('safdtl/full/' . md5($data['saf_id'])));
			}
		}
	}

	public function re_send_rmc($id,$levelPassId)
	{
		$data = (array)null;
		$data['saf_id'] = $id;

		$Session = Session();
		$emp_mstr = $Session->get("emp_details");
		$sender_emp_details_id = $emp_mstr["id"];

		// $lastRecord = $this->model_level_pending_dtl->getLastBkctznRecord(["id" => md5($id)]);
		//new change
		$lastRecord = $this->model_level_pending_dtl->getLastBkctznRecordwithLevelId(["id" => $levelPassId]);
		if($lastRecord["sender_user_type_id"] == 11 || $lastRecord["sender_user_type_id"] == 13){
			$lastRecord["sender_user_type_id"] = 6;
		}
		
		$bub_fix = [
			'saf_dtl_id' => $id,
			'sender_user_type_id' => 11,
			'receiver_user_type_id' => $lastRecord["sender_user_type_id"],
			'forward_date' => "NOW()",
			'forward_time' => "NOW()",
			'created_on' => "NOW()",
			'status' => 1,
			'remarks' => 'Re-sent from back office to ULB',
			'sender_emp_details_id' => $sender_emp_details_id

		];
		$this->model_level_pending_dtl->bugfix_level_pending($bub_fix); //Shashi
		
		$leveldata = [
			'id' => $lastRecord["id"],
			'saf_dtl_id' => $id,
			'sender_user_type_id' => 11, //back office
			'receiver_user_type_id' => $lastRecord["sender_user_type_id"],
			'remarks' => 'Re-sent from back office to ULB',
			'sender_emp_details_id' => $sender_emp_details_id
		];
		$this->db->transBegin();

		$this->model_level_pending_dtl->BackToCitizenToULB($leveldata);

		//$this->model_saf_doc_dtl->updatestatusDocUpload($data);

		$this->model_saf_dtl->updateSafstatusDocUpload($data);

		if ($this->db->transStatus() === FALSE) {
			$this->db->transRollback();
			flashToast("message", "Oops, Application couldn't send to ULB.");
			return $this->response->redirect(base_url('safDoc/SAFdocumentUpload/' . md5($data['saf_id'])));
		} else {
			$this->db->transCommit();
			flashToast("message", "Application sent to ULB.");
			return $this->response->redirect(base_url('safdtl/full/' . md5($data['saf_id'])));
		}
	}
	public function re_send_rmc2($id,$levelPassId)
	{
		$data = (array)null;
		$data['saf_id'] = $id;

		$Session = Session();
		$emp_mstr = $Session->get("emp_details");
		$sender_emp_details_id = $emp_mstr["id"];

		// $lastRecord = $this->model_level_pending_dtl->getLastBkctznRecord(["id" => md5($id)]);
		//new change
		$lastRecord = $this->model_level_pending_dtl->getLastBkctznRecordwithLevelId(["id" => $levelPassId]);
		if($lastRecord["sender_user_type_id"] == 11 || $lastRecord["sender_user_type_id"] == 13){
			$lastRecord["sender_user_type_id"] = 6;
		}
		
		$bub_fix = [
			'saf_dtl_id' => $id,
			'sender_user_type_id' => 11,
			'receiver_user_type_id' => $lastRecord["sender_user_type_id"],
			'forward_date' => "NOW()",
			'forward_time' => "NOW()",
			'created_on' => "NOW()",
			'status' => 1,
			'remarks' => 'Re-sent from back office to ULB',
			'sender_emp_details_id' => $sender_emp_details_id

		];
		$this->model_level_pending_dtl->bugfix_level_pending($bub_fix); //Shashi
		
		$leveldata = [
			'id' => $lastRecord["id"],
			'saf_dtl_id' => $id,
			'sender_user_type_id' => 11, //back office
			'receiver_user_type_id' => $lastRecord["sender_user_type_id"],
			'remarks' => 'Re-sent from back office to ULB',
			'sender_emp_details_id' => $sender_emp_details_id
		];
		$this->db->transBegin();

		$this->model_level_pending_dtl->BackToCitizenToULB($leveldata);

		//$this->model_saf_doc_dtl->updatestatusDocUpload($data);

		$this->model_saf_dtl->updateSafstatusDocUpload($data);

		if ($this->db->transStatus() === FALSE) {
			$this->db->transRollback();
			flashToast("message", "Oops, Application couldn't send to ULB.");
			return $this->response->redirect(base_url('safDoc/SAFdocumentUpload/' . md5($data['saf_id'])));
		} else {
			$this->db->transCommit();
		//	flashToast("message", "Application sent to ULB.");
		//	return $this->response->redirect(base_url('safdtl/full/' . md5($data['saf_id'])));
		}
	}
}
