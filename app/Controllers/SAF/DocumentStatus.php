<?php
namespace App\Controllers\SAF;
use CodeIgniter\Controller;
use App\Models\model_doc_mstr;
use App\Models\model_saf_doc_dtl;
use App\Models\model_view_saf_dtl;
use App\Models\model_saf_owner_detail;

class DocumentStatus extends Controller
{
	protected $db;
    protected $model_doc_mstr;
    protected $model_saf_doc_dtl;
    protected $model_view_saf_dtl;
    protected $model_saf_owner_detail;

    public function __construct($db) {
    	helper(['url', 'db_helper']);
        $this->db = $db;
        $this->model_doc_mstr = new model_doc_mstr($this->db);
        $this->model_saf_doc_dtl = new model_saf_doc_dtl($this->db);
        $this->model_view_saf_dtl = new model_view_saf_dtl($this->db);
        $this->model_saf_owner_detail = new model_saf_owner_detail($this->db);
    }

    public function isFullDocUploaded($safDtlId) {
        $everyDocUploaded = true;
        $data = $this->model_view_saf_dtl->getSafDtlBySafDtlId($safDtlId);
        $saf_owner_detail = $this->model_saf_owner_detail->getOwnerdtlBySAFId(["saf_dtl_id" => $safDtlId]);
        foreach ($saf_owner_detail as $key => $owner_detail) {
			$input = [
				'saf_dtl_id' => $data['saf_dtl_id'],
				'saf_owner_dtl_id' => $owner_detail['id'],
			];
			$saf_owner_detail[$key]['applicant_img_dtl'] = $this->model_saf_doc_dtl->getApplicantImgBySafDtlAndSafOwnerDtlId($input);
			$saf_owner_detail[$key]['applicant_doc_dtl'] = $this->model_saf_doc_dtl->getApplicantDocBySafDtlAndSafOwnerDtlId($input);
			$saf_owner_detail[$key]['Handicaped_doc_dtl'] = $this->model_saf_doc_dtl->getHandicapedDocBySafDtlAndSafOwnerDtlId($input);
			$saf_owner_detail[$key]['Armed_doc_dtl'] = $this->model_saf_doc_dtl->getArmedDocBySafDtlAndSafOwnerDtlId($input);
			$saf_owner_detail[$key]['gender_doc_dtl'] = $this->model_saf_doc_dtl->getGenderDocBySafDtlAndSafOwnerDtlId($input);
			$saf_owner_detail[$key]['dob_doc_dtl'] = $this->model_saf_doc_dtl->getDobDocBySafDtlAndSafOwnerDtlId($input);
            //print_var($saf_owner_detail);
            //break;
            //print_var($saf_owner_detail[$key]['applicant_img_dtl']);
		}
        if (isset($saf_owner_detail)) {
            foreach ($saf_owner_detail as $owner_detail) {
                if (!$owner_detail['applicant_doc_dtl']) {
                    $everyDocUploaded = false;
                }
                if (isset($owner_detail["is_specially_abled"])) {
                    if ($owner_detail["is_specially_abled"] != 'f') {
                        if (!$owner_detail['Handicaped_doc_dtl']) {
                            $everyDocUploaded = false;
                        }
                    }
                }
                if (isset($owner_detail["is_armed_force"])) {
                    if ($owner_detail["is_armed_force"] != 'f') {
                        if (!$owner_detail['Armed_doc_dtl']) {
                            $everyDocUploaded = false;
                        }
                    }
                }
                if (isset($owner_detail["gender"])) {
                    if ($owner_detail["gender"] == 'Female' || $owner_detail['gender'] == 'Other') {
                        if (!$owner_detail['gender_doc_dtl']) {
                            $everyDocUploaded = false;
                        }
                    }
                }
                if (isset($owner_detail["dob"])) {
                    $dob_year = date('Y', strtotime($owner_detail['dob']));
                    $current_year = date('Y');
                    $c_age = $current_year - $dob_year;
                    if ($c_age > 60) {
                        if (!$owner_detail['dob_doc_dtl']) {
                            $everyDocUploaded = false;
                        }
                    }
                }
            }
        }

        $saf_doc_list = $this->model_doc_mstr->HaveToUploadDoc($data);
        //print_var($saf_doc_list);
        $uploaded_doc_list = $this->model_saf_doc_dtl->getAllActiveDocuments($safDtlId);
        foreach ($saf_doc_list as $row) {
            $docs_name = implode(', ', array_map(function ($entry) {
                return $entry['doc_name'];
            }, $row));
            $document_uploaded = [];
            foreach ($uploaded_doc_list as $rec) {
                foreach ($row as $rec1)
                    if ($rec["doc_mstr_id"] == $rec1["id"]) {
                        $document_uploaded = $rec;
                        break;
                    }
            }
            if (!$document_uploaded) {
                $everyDocUploaded = false;
            }
        }
        return $everyDocUploaded;
    }
}