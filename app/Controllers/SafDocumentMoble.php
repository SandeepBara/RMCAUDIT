<?php

namespace App\Controllers;
use App\Models\model_view_saf_dtl;
use App\Models\model_saf_owner_detail;
use App\Models\model_saf_doc_dtl;

class SafDocumentMoble extends MobiController
{
    protected $db;
    protected $model_view_saf_dtl;
    protected $model_saf_owner_detail;
    protected $model_saf_doc_dtl;

    public function __construct()
    {
        parent::__construct();
        helper(['db_helper', 'geotagging_helper', 'utility_helper']);
        if ($db_name = dbConfig("property")) {
            $this->db = db_connect($db_name);
        }
        $this->model_view_saf_dtl = new model_view_saf_dtl($this->db);
        $this->model_saf_owner_detail = new model_saf_owner_detail($this->db);
        $this->model_saf_doc_dtl = new model_saf_doc_dtl($this->db);
    }

    function __destruct() {
		$this->db->close();
	}

    public function viewDoc($saf_dtl_id) {
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

		$data["doc_list"] = $this->model_saf_doc_dtl->getAllDocumentsWithOwner2($data['saf_dtl_id']);
        return view('mobile/Property/FieldVerification/doc_upload_saf_view', $data);
    }
}
