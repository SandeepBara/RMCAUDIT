<?php
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_saf_doc_dtl extends Model
{
    protected $db;
    protected $table = 'tbl_saf_doc_dtl';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id','saf_dtl_id','doc_mstr_id','other_doc','doc_path','emp_details_id','remarks','verify_status','verified_by_emp_id','verified_on','created_on','status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }


     public function insertData($input){
        $builder = $this->db->table($this->table)
                            ->insert([
                  "saf_dtl_id"=>$input["saf_dtl_id"],
                  "doc_mstr_id"=>$input["trans_doc_mstr_id"],
                  "other_doc"=>'',
                  "doc_path"=>'',
                   "remarks"=>'',
                  "emp_details_id"=>$input["emp_details_id"],
                  "created_on"=>$input["created_on"],
                  "status"=>'1'
				  ]);
							//echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }
    public function insertNoElectricConnectionData($input){
        $builder = $this->db->table($this->table)
                     ->insert([
                        "saf_dtl_id"=>$input["saf_dtl_id"],
                        "doc_mstr_id"=>$input["doc_mstr_id"],
                        "other_doc"=>'',
                        "doc_path"=>'',
                        "remarks"=>'',
                        "emp_details_id"=>$input["emp_details_id"],
                        "created_on"=>$input["created_on"],
                        "status"=>'1'
				  ]);
		//echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }
    public function insertPrData($input){
        $builder = $this->db->table($this->table)
                            ->insert([
                  "saf_dtl_id"=>$input["saf_dtl_id"],
                  "doc_mstr_id"=>$input["prop_doc_mstr_id"],
                  "other_doc"=>'',
                  "doc_path"=>'',
                   "remarks"=>'',
                  "emp_details_id"=>$input["emp_details_id"],
                  "created_on"=>$input["created_on"],
                  "status"=>'1'
				  ]);
							//echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }
    public function insertFrData($input)
    {

        $builder = $this->db->table($this->table)
				->insert([
                  "saf_dtl_id"=>$input["saf_dtl_id"],
                  "doc_mstr_id"=>$input["upld_doc_mstr_id"],
                  "other_doc"=>$input["other_doccheck"],
                  "doc_path"=> NULL,
                  "remarks"=> NULL,
                  "emp_details_id"=>$input["emp_details_id"],
                  "created_on"=>$input["created_on"],
                  "status"=>$input["status"]
				  ]);

        return $insert_id = $this->db->insertID();
    }

	public function inserttransferData($input)
    {
        $builder = $this->db->table($this->table)
                            ->insert([
                  "saf_dtl_id"=>$input["saf_dtl_id"],
                  "doc_mstr_id"=>$input["transfer_mode_doc_mstr_id"],
                  "other_doc"=>'',
                  "doc_path"=>'',
                   "remarks"=>'',
                  "emp_details_id"=>$input["emp_details_id"],
                  "created_on"=>$input["created_on"],
                  "status"=>$input["status"]
				  ]);
							//echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }

	public function insertsuperData($input){
        $builder = $this->db->table($this->table)
                ->insert([
                  "saf_dtl_id"=>$input["saf_dtl_id"],
                  "doc_mstr_id"=>$input["upld_doc_mstr_id"],
                  "other_doc"=>'',
                  "doc_path"=>'',
                  "remarks"=>'',
                  "emp_details_id"=>$input["emp_details_id"],
                  "created_on"=>$input["created_on"],
                  "status"=>$input["status"]
				  ]);
							//echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }

	public function insertflatData($input){
        $builder = $this->db->table($this->table)
                   ->insert([
                  "saf_dtl_id"=>$input["saf_dtl_id"],
                  "doc_mstr_id"=>$input["flat_doc_mstr_id"],
                  "other_doc"=>'',
                  "doc_path"=>'',
                   "remarks"=>'',
                  "emp_details_id"=>$input["emp_details_id"],
                  "created_on"=>$input["created_on"],
                  "status"=>$input["status"]
				  ]);
							//echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }

	public function insertpropertyData($input){
        $builder = $this->db->table($this->table)
                            ->insert([
                  "saf_dtl_id"=>$input["saf_dtl_id"],
                  "doc_mstr_id"=>$input["property_type_doc_mstr_id"],
                  "other_doc"=>'',
                  "doc_path"=>'',
                   "remarks"=>'',
                  "emp_details_id"=>$input["emp_details_id"],
                  "created_on"=>$input["created_on"],
                  "status"=>$input["status"]
				  ]);
							//echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }

	public function insertelectricData($input){
        $builder = $this->db->table($this->table)
                            ->insert([
                  "saf_dtl_id"=>$input["saf_dtl_id"],
                  "doc_mstr_id"=>$input["no_electric_connection_doc_mstr_id"],
                  "other_doc"=>'',
                  "doc_path"=>'',
                   "remarks"=>'',
                  "emp_details_id"=>$input["emp_details_id"],
                  "created_on"=>$input["created_on"],
                  "status"=>$input["status"]
				  ]);
							//echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }


    public function insertOwnerImageData($input){
        $builder = $this->db->table($this->table)
                            ->insert([
                  "saf_dtl_id"=>$input["saf_dtl_id"],
                  "saf_owner_dtl_id"=>$input["saf_owner_dtl_id"],
                  "doc_mstr_id"=>'0',
                  "other_doc"=>'applicant_image',
                  "doc_path"=>$input["photo_path"],
                   "remarks"=>'',
                  "emp_details_id"=>$input["emp_details_id"],
                  "created_on"=>$input["created_on"],
                  "status"=>'1'
				  ]);
							//echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }
    public function checkOwnerImgDataIsExistBySafOwnerDtlId($input){
        try{
             return $this->db->table($this->table)
                        ->select('id, doc_path')
                        ->where('saf_dtl_id', $input["saf_dtl_id"])
                        ->where('saf_owner_dtl_id', $input["saf_owner_dtl_id"])
                        ->where('doc_mstr_id',  0)
                        ->where('other_doc',  'applicant_image')
                        ->whereIn('status', [1, 2])
                        ->get()
                        ->getFirstRow("array");

        }catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function checkOwnerImgDataIsExistBySafOwnerDtlId2($input){
        try{
             return $this->db->table($this->table)
                        ->select('id, doc_path')
                        ->where('saf_dtl_id', $input["saf_dtl_id"])
                        ->where('saf_owner_dtl_id', $input["saf_owner_dtl_id"])
                        ->where('doc_mstr_id',  0)
                        ->where('other_doc',  'applicant_image')
                        ->whereIn('status', [1])
                        ->get()
                        ->getFirstRow("array");

        }catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function checkOwnerDocDataIsExistBySafOwnerDtlId($input){
        try{
             return $this->db->table($this->table)
                        ->select('id, doc_path')
                        ->where('saf_dtl_id', $input["saf_dtl_id"])
                        ->where('saf_owner_dtl_id', $input["saf_owner_dtl_id"])
                        ->where('doc_mstr_id !=',  0)
                        ->where('other_doc',  '')
                        ->whereIn('status', [1, 2])
                        ->get()
                        ->getFirstRow("array");


        }catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function checkOwnerDocDataIsExistBySafOwnerDtlId2($input){
        try{
             return $this->db->table($this->table)
                        ->select('id, doc_path')
                        ->where('saf_dtl_id', $input["saf_dtl_id"])
                        ->where('saf_owner_dtl_id', $input["saf_owner_dtl_id"])
                        ->where('doc_mstr_id !=',  0)
                        ->where('other_doc',  '')
                        ->whereIn('status', [1])
                        ->get()
                        ->getFirstRow("array");


        }catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function checkHandicapedDocDataIsExistBySafOwnerDtlId($input){
        try{
             return $this->db->table($this->table)
                        ->select('id, doc_path')
                        ->where('saf_dtl_id', $input["saf_dtl_id"])
                        ->where('saf_owner_dtl_id', $input["saf_owner_dtl_id"])
                        ->where('doc_mstr_id',  25)
                        ->where('other_doc',  'handicaped_document')
                        ->whereIn('status', [1, 2])
                        ->get()
                        ->getFirstRow("array");

        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function checkArmedDocDataIsExistBySafOwnerDtlId($input){
        try{
             return $this->db->table($this->table)
                        ->select('id, doc_path')
                        ->where('saf_dtl_id', $input["saf_dtl_id"])
                        ->where('saf_owner_dtl_id', $input["saf_owner_dtl_id"])
                        ->where('doc_mstr_id',  26)
                        ->where('other_doc',  'armed_force_document')
                        ->whereIn('status', [1, 2])
                        ->get()
                        ->getFirstRow("array");

        }catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function checkGenderDocDataIsExistBySafOwnerDtlId($input){
        try{
             return $this->db->table($this->table)
                        ->select('id, doc_path')
                        ->where('saf_dtl_id', $input["saf_dtl_id"])
                        ->where('saf_owner_dtl_id', $input["saf_owner_dtl_id"])
                        ->where('doc_mstr_id',  27)
                        ->where('other_doc',  'gender_document')
                        ->whereIn('status', [1, 2])
                        ->get()
                        ->getFirstRow("array");

        }catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function checkDobDocDataIsExistBySafOwnerDtlId($input){
        try{
             return $this->db->table($this->table)
                        ->select('id, doc_path')
                        ->where('saf_dtl_id', $input["saf_dtl_id"])
                        ->where('saf_owner_dtl_id', $input["saf_owner_dtl_id"])
                        ->where('doc_mstr_id',  28)
                        ->where('other_doc',  'dob_document')
                        ->whereIn('status', [1, 2])
                        ->get()
                        ->getFirstRow("array");

        }catch(Exception $e){
            return $e->getMessage();
        }
    }


	public function checkDocDataIsExist($input) {


        try {
           $builder = $this->db->table($this->table)
                        ->select('id, doc_path')
                        ->where('saf_dtl_id', $input['saf_dtl_id'])
                        // ->whereIn('doc_mstr_id', $data)
                        ->where('other_doc', $input['other_doccheck'])
                        ->whereIn('status', [1, 2])
                        ->get();

            return $builder->getFirstRow('array');
            // return $builder->getResultArray[0];
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function checkDocDataIsExist1($input) {
        try {
           $builder = $this->db->table($this->table)
                        ->select('id, doc_path')
                        ->where('saf_dtl_id', $input['saf_dtl_id'])
                        ->where('doc_mstr_id', $input['doc_mstr_idcheck'])
                        ->where('other_doc', $input['other_doccheck'])
                        ->whereIn('status', [1, 2])
                        ->get();

            return $builder->getFirstRow('array');
            // return $builder->getResultArray[0];
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
	public function checkDocDataIsExist2($input) {


        try {
           $builder = $this->db->table($this->table)
                        ->select('id, doc_path')
                        ->where('saf_dtl_id', $input['saf_dtl_id'])
                        // ->whereIn('doc_mstr_id', $data)
                        ->where('other_doc', $input['other_doccheck'])
                        ->whereIn('status', [1])
                        ->get();

            return $builder->getFirstRow('array');
            // return $builder->getResultArray[0];
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
	public function checkAdditionalDocDataIsExist($input) {


        try {
           $builder = $this->db->table($this->table)
                        ->select('id, doc_path')
                        ->where('saf_dtl_id', $input['saf_dtl_id'])
                        ->where('other_doc', $input['other_doccheck'])
                        ->whereIn('status', [1, 2])
                        ->get();

            return $builder->getFirstRow('array');
            // return $builder->getResultArray[0];
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
	public function checkAdditionalDocDataIsExist2($input) {


        try {
           $builder = $this->db->table($this->table)
                        ->select('id, doc_path')
                        ->where('saf_dtl_id', $input['saf_dtl_id'])
                        ->where('other_doc', $input['other_doccheck'])
                        ->whereIn('status', [1])
                        ->get();

            return $builder->getFirstRow('array');
            // return $builder->getResultArray[0];
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
	public function checkHandicapedDocDataIsExistBySafOwnerDtlId2($input) {


        try {
           $builder = $this->db->table($this->table)
                        ->select('id, doc_path')
                        ->where('saf_dtl_id', $input['saf_dtl_id'])
                        ->where('other_doc', 'handicaped_document')
                        ->whereIn('status', [1])
                        ->get();

            return $builder->getFirstRow('array');
            // return $builder->getResultArray[0];
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
	public function checkArmedDocDataIsExistBySafOwnerDtlId2($input) {


        try {
           $builder = $this->db->table($this->table)
                        ->select('id, doc_path')
                        ->where('saf_dtl_id', $input['saf_dtl_id'])
                        ->where('other_doc', 'armed_force_document')
                        ->whereIn('status', [1])
                        ->get();

            return $builder->getFirstRow('array');
            // return $builder->getResultArray[0];
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
	public function checkGenderDocDataIsExistBySafOwnerDtlId2($input) {


        try {
           $builder = $this->db->table($this->table)
                        ->select('id, doc_path')
                        ->where('saf_dtl_id', $input['saf_dtl_id'])
                        ->where('saf_owner_dtl_id', $input["saf_owner_dtl_id"])
                        ->where('other_doc', 'gender_document')
                        ->whereIn('status', [1])
                        ->get();

            return $builder->getFirstRow('array');
            // return $builder->getResultArray[0];
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
	public function checkDobDocDataIsExistBySafOwnerDtlId2($input) {


        try {
           $builder = $this->db->table($this->table)
                        ->select('id, doc_path')
                        ->where('saf_dtl_id', $input['saf_dtl_id'])
                        ->where('other_doc', 'dob_document')
                        ->whereIn('status', [1])
                        ->get();

            return $builder->getFirstRow('array');
            // return $builder->getResultArray[0];
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

	public function checktransferDocDataIsExistBySafOwnerDtlId($input) {
        try {
            return $this->db->table($this->table)
                        ->select('id, doc_path')
                        ->where('saf_dtl_id', $input['saf_dtl_id'])
                        ->where('doc_mstr_id !=', 0)
						->where('doc_mstr_id <=', 7)
                        ->where('other_doc', '')
                        ->whereIn('status', [1, 2])
                        ->get()
						->getResultArray()[0];
                //echo $this->db->getLastQuery();

        }catch(Exception $e){
            return $e->getMessage();
        }
    }

	public function checksuperDocDataIsExistById($input) {
        try {
            return $this->db->table($this->table)
                        ->select('id, doc_path')
                        ->where('saf_dtl_id', $input['saf_dtl_id'])
                        ->where('doc_mstr_id =', 19)
                        ->where('other_doc', '')
                        ->whereIn('status', [1, 2])
                        ->get()
						->getResultArray()[0];
                //echo $this->db->getLastQuery();

        }catch(Exception $e){
            return $e->getMessage();
        }
    }

	public function checkflatDocDataIsExistById($input) {
        try {
            return $this->db->table($this->table)
                        ->select('id, doc_path')
                        ->where('saf_dtl_id', $input['saf_dtl_id'])
                        ->whereIn('doc_mstr_id', [7,18])
                        ->where('other_doc', '')
                        ->whereIn('status', [1, 2])
                        ->get()
						->getResultArray()[0];
                //echo $this->db->getLastQuery();

        }catch(Exception $e){
            return $e->getMessage();
        }
    }

	public function checkpropertyDocDataIsExistBySafOwnerDtlId($input) {
        try {
            return $this->db->table($this->table)
                        ->select('id, doc_path')
                        ->where('saf_dtl_id', $input['saf_dtl_id'])
                        ->where('doc_mstr_id >=', 8)
						->where('doc_mstr_id <=', 10)
                        ->where('other_doc', '')
                        ->whereIn('status', [1, 2])
                        ->get()
						->getResultArray()[0];
                //echo $this->db->getLastQuery();

        }catch(Exception $e){
            return $e->getMessage();
        }
    }

	public function checkprDocDataIsExist($input) {
        try {
            return $this->db->table($this->table)
                        ->select('id, doc_path')
                        ->where('saf_dtl_id', $input['saf_dtl_id'])
                        ->where('doc_mstr_id >=', 8)
						->where('doc_mstr_id <=', 10)
                        ->where('verify_status', 0)
                        ->whereIn('status', [1, 2])
                        ->get()
						->getResultArray()[0];
                //echo $this->db->getLastQuery();

        }catch(Exception $e){
            return $e->getMessage();
        }
    }

	public function checkOwnerImgExist($input){
        try{
             return $this->db->table($this->table)
                        ->select('id, doc_path')
                        ->where('saf_dtl_id', $input["saf_dtl_id"])
                        ->where('saf_owner_dtl_id', $input["saf_owner_dtl_id"])
                        ->where('doc_mstr_id',  0)
						->where('verify_status',  0)
                        ->where('other_doc',  'applicant_image')
                        ->whereIn('status', [1, 2])
                        ->get()
                        ->getFirstRow("array");

        }catch(Exception $e){
            return $e->getMessage();
        }
    }

	public function checkOwnerdocExist($input){

        try{
             return $this->db->table($this->table)
                        ->select('id, doc_path')
                        ->where('saf_dtl_id', $input["saf_dtl_id"])
                        ->where('saf_owner_dtl_id', $input["saf_owner_dtl_id"])
						->where('other_doc',  '')
						->where('verify_status',  0)
                        ->whereIn('status', [1, 2])
                        ->get()
                        ->getFirstRow("array");
						//echo $this->db->getLastQuery();
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

	public function checksupdocExist($input){
        try{
             return $this->db->table($this->table)
                        ->select('id, doc_path')
                        ->where('saf_dtl_id', $input["saf_dtl_id"])
                        ->whereIn('doc_mstr_id', $input["doc_mstr_idcheck"])
						->where('other_doc',  '')
						->where('verify_status',  0)
                        ->whereIn('status', [1, 2])
                        ->get()
                        ->getFirstRow("array");
						//echo $this->db->getLastQuery();
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

	public function checktrdocExist($input){
        try{
             return $this->db->table($this->table)
                        ->select('id, doc_path')
                        ->where('saf_dtl_id', $input["saf_dtl_id"])
                        ->whereIn('doc_mstr_id',  [1,2,3,4,5,6,7])
						->where('other_doc',  '')
						->where('verify_status',  0)
                        ->whereIn('status', [1, 2])
                        ->get()
                        ->getFirstRow("array");
						//echo $this->db->getLastQuery();
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

	public function checkprdocExist($input){
        try{
             return $this->db->table($this->table)
                        ->select('id, doc_path')
                        ->where('saf_dtl_id', $input["saf_dtl_id"])
                        ->whereIn('doc_mstr_id',  [8,9,10])
						->where('other_doc',  '')
						->where('verify_status',  0)
                        ->whereIn('status', [1, 2])
                        ->get()
                        ->getFirstRow("array");
						//echo $this->db->getLastQuery();
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

	public function checkflatdocExist($input){
        try{
             return $this->db->table($this->table)
                        ->select('id, doc_path')
                        ->where('saf_dtl_id', $input["saf_dtl_id"])
                        ->whereIn('doc_mstr_id',  [7,18])
						->where('other_doc',  '')
						->where('verify_status',  0)
                        ->whereIn('status', [1, 2])
                        ->get()
                        ->getFirstRow("array");
						//echo $this->db->getLastQuery();
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

	public function checkelectricDocDataIsExistById($input) {
        try {
            return $this->db->table($this->table)
                        ->select('id, doc_path')
                        ->where('saf_dtl_id', $input['saf_dtl_id'])
                        ->where('doc_mstr_id =', 13)
                        ->where('other_doc', '')
                        ->whereIn('status', [1, 2])
                        ->get()
						->getResultArray()[0];
                //echo $this->db->getLastQuery();

        }catch(Exception $e){
            return $e->getMessage();
        }
    }

	public function checkpropSuperDocDataIsExistBySafOwnerDtlId($input) {
        try {
            $builder = $this->db->table($this->table)
                        ->select('id, doc_path')
                        ->where('saf_dtl_id', $input['saf_dtl_id'])
						->where('saf_owner_dtl_id', $input["saf_owner_dtl_id"])
						->where('doc_mstr_id', 0)
						->where('other_doc', '')
                        ->whereIn('status', [1, 2])
                        ->get();
                //echo $this->db->getLastQuery();
            return $builder->getFirstRow('array');
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function insertOwnerImgData($input){
		//print_r($input);
        $this->db->table($this->table)
                ->insert([
                    "saf_dtl_id"=>$input["saf_dtl_id"],
                    "saf_owner_dtl_id"=>$input["saf_owner_dtl_id"],
                    "doc_mstr_id"=>'0',
                    "other_doc"=>'applicant_image',
                    "doc_path"=>'',
                    "remarks"=>'',
                    "emp_details_id"=>$input["emp_details_id"],
                    "created_on"=>$input["created_on"],
                    "status"=>$input["status"]
                ]);
                //echo $this->db->getLastQuery();
        return $this->db->insertID();
    }
    public function insertHandicapedData($input){
		//print_r($input);
        $this->db->table($this->table)
                ->insert([
                    "saf_dtl_id"=>$input["saf_dtl_id"],
                    "saf_owner_dtl_id"=>$input["saf_owner_dtl_id"],
                    "doc_mstr_id"=>25,
                    "other_doc"=>'handicaped_document',
                    "doc_path"=>'',
                    "remarks"=>'',
                    "emp_details_id"=>$input["emp_details_id"],
                    "created_on"=>$input["created_on"],
                    "status"=>$input["status"]
                ]);
                //echo $this->db->getLastQuery();
        return $this->db->insertID();
    }
    public function insertArmedData($input){
		//print_r($input);
        $this->db->table($this->table)
                ->insert([
                    "saf_dtl_id"=>$input["saf_dtl_id"],
                    "saf_owner_dtl_id"=>$input["saf_owner_dtl_id"],
                    "doc_mstr_id"=>26,
                    "other_doc"=>'armed_force_document',
                    "doc_path"=>'',
                    "remarks"=>'',
                    "emp_details_id"=>$input["emp_details_id"],
                    "created_on"=>$input["created_on"],
                    "status"=>$input["status"]
                ]);
                //echo $this->db->getLastQuery();
        return $this->db->insertID();
    }
    public function insertGenderData($input){
		//print_r($input);
        $this->db->table($this->table)
                ->insert([
                    "saf_dtl_id"=>$input["saf_dtl_id"],
                    "saf_owner_dtl_id"=>$input["saf_owner_dtl_id"],
                    "doc_mstr_id"=>27,
                    "other_doc"=>'gender_document',
                    "doc_path"=>'',
                    "remarks"=>'',
                    "emp_details_id"=>$input["emp_details_id"],
                    "created_on"=>$input["created_on"],
                    "status"=>$input["status"]
                ]);
                //echo $this->db->getLastQuery();
        return $this->db->insertID();
    }
    public function insertDobData($input){
		//print_r($input);
        $this->db->table($this->table)
                ->insert([
                    "saf_dtl_id"=>$input["saf_dtl_id"],
                    "saf_owner_dtl_id"=>$input["saf_owner_dtl_id"],
                    "doc_mstr_id"=>28,
                    "other_doc"=>'dob_document',
                    "doc_path"=>'',
                    "remarks"=>'',
                    "emp_details_id"=>$input["emp_details_id"],
                    "created_on"=>$input["created_on"],
                    "status"=>$input["status"]
                ]);
                //echo $this->db->getLastQuery();
        return $this->db->insertID();
    }

    public function insertOwnerData($input){

        $builder = $this->db->table($this->table)
                  ->insert([
                  "saf_dtl_id"=>$input["saf_dtl_id"],
                  "saf_owner_dtl_id"=>$input["saf_owner_dtl_id"],
                  "doc_mstr_id"=>$input["owner_doc_mstr_id"],
                  "other_doc"=>'',
                  "doc_path"=>'',
                  "remarks"=>'',
                  "emp_details_id"=>$input["emp_details_id"],
                  "created_on"=>$input["created_on"],
                  "status"=>$input["status"]
				  ]);
				//echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }
    public function insertOwnerDocData($input){

        $builder = $this->db->table($this->table)
                  ->insert([
                  "saf_dtl_id"=>$input["saf_dtl_id"],
                  "saf_owner_dtl_id"=>$input["saf_owner_dtl_id"],
                  "doc_mstr_id"=>$input["owner_doc_mstr_id"],
                  "other_doc"=>'',
                  "doc_path"=>'',
                  "remarks"=>'',
                  "emp_details_id"=>$input["emp_details_id"],
                  "created_on"=>$input["created_on"],
                  "status"=>$input["status"]
				  ]);
				//echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }

	public function insertSupData($input){
        $builder = $this->db->table($this->table)
                ->insert([
                  "saf_dtl_id"=>$input["saf_dtl_id"],
                  "saf_owner_dtl_id"=>0,
                  "doc_mstr_id"=>$input["super_structure_doc_mstr_id"],
                  "other_doc"=>'',
                  "doc_path"=>'',
                  "remarks"=>'',
                  "emp_details_id"=>$input["emp_details_id"],
                  "created_on"=>$input["created_on"],
                  "status"=>$input["status"]
				  ]);
							//echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }

	public function updatetransImgpathById($saf_doc_id,$doc_path, $owner_doc_mstr_id = 0)
    {

        return $builder = $this->db->table($this->table)
                            ->where('id', $saf_doc_id)
                            ->update([
                                'doc_path'=> $doc_path,
                                'doc_mstr_id'=> $owner_doc_mstr_id,
                                'verify_status'=> 0,
                            ]);
				//echo $this->db->getLastQuery();
    }
    public function updatetransdocpathById($saf_doc_id, $doc_path, $owner_doc_mstr_id)
    {

        return $builder = $this->db->table($this->table)
							->where('id', $saf_doc_id)
							->update([
								'doc_path'=> $doc_path,
								'doc_mstr_id'=> $owner_doc_mstr_id,
                                'verify_status'=> 0,
							]);


			// echo $this->db->getLastQuery();
            // echo "after update";
            // die;
    }

	public function updatesuperdocpathById($saf_doc_id,$doc_path, $super_doc_mstr_id){

        return $builder = $this->db->table($this->table)
							->where('id', $saf_doc_id)
							->update([
								'doc_path'=>$doc_path,
								'doc_mstr_id'=>$super_doc_mstr_id
							]);

    }



    public function check_owner_details($saf_id,$saf_owner_dtl_id)
    {
        try{
             return $this->db->table($this->table)
                        ->select('id, saf_owner_dtl_id, doc_path')
                        ->where('saf_dtl_id', $saf_id)
                        ->where('saf_owner_dtl_id', $saf_owner_dtl_id)
                        ->where('doc_mstr_id',  0)
                        ->where('status', 1)
                        ->get()
                        ->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function verified_rej_owner_img_details($saf_id,$saf_owner_dtl_id)
    {
        try{
            $other_doc="applicant_image";
            $stts = ['1', '2', '0'];
             return $this->db->table($this->table)
                        ->select('id,saf_owner_dtl_id,doc_path,remarks,verify_status')
                        ->where('saf_dtl_id',$saf_id)
                        ->whereIn('verify_status',$stts)
                        ->where('other_doc',$other_doc)
                        ->where('saf_owner_dtl_id',$saf_owner_dtl_id)
                        ->where('doc_mstr_id', 0)
                        ->where('status',1)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray();
						//echo $this->db->getLastQuery();
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function check_ownerdtl_img_details($saf_id,$saf_owner_dtl_id)
    {
        try{
            $other_doc="applicant_image";
             return $this->db->table($this->table)
                        ->select('id,saf_owner_dtl_id,doc_path')
                        ->where('saf_dtl_id',$saf_id)
                        ->where('verify_status',1)
                        ->where('other_doc',$other_doc)
                        ->where('saf_owner_dtl_id',$saf_owner_dtl_id)
                        ->where('doc_mstr_id', 0)
                        ->where('status',1)
                        ->get()
                        ->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function check_newownerdtl_img_details($saf_id,$saf_owner_dtl_id)
    {
        try{
            $other_doc="applicant_image";
             return $this->db->table($this->table)
                        ->select('id,saf_owner_dtl_id,doc_path')
                        ->where('saf_dtl_id',$saf_id)
                        ->where('verify_status',0)
                        ->where('other_doc',$other_doc)
                        ->where('saf_owner_dtl_id',$saf_owner_dtl_id)
                        ->where('doc_mstr_id', 0)
                        ->where('status',1)
                        ->get()
                        ->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function count_applicant_img_new_upload($saf_id)
    {
        try{
            $other_doc="applicant_image";
             return $this->db->table($this->table)
                        ->select('count(id) as app_img_cnt')
                        ->where('saf_dtl_id',$saf_id)
                        ->where('verify_status',0)
                        ->where('other_doc',$other_doc)
                        ->where('doc_mstr_id', 0)
                        ->where('status', 1)
                        ->get()
                        ->getFirstRow("array");
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function check_fr_doc($saf_id,$fr_doc)
    {
        try{
             //$names = ['10', '11'];
             return $this->db->table($this->table)
                        ->select('id')
                        ->where('saf_dtl_id',$saf_id)
                        ->where('doc_mstr_id', 0)
                        ->where('other_doc', $fr_doc)
                        ->where('status',1)
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function check_tr_doc($saf_id,$tr_doc)
    {
        try{
             //$names = ['10', '11'];
             return $this->db->table($this->table)
                        ->select('id')
                        ->where('saf_dtl_id',$saf_id)
                        ->whereIn('doc_mstr_id', $tr_doc)
                        ->where('status',1)
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function check_no_elect_connection_exists($saf_id, $no_elect_connection_doc){
        try {
             return $this->db->table($this->table)
                        ->select('id')
                        ->where('saf_dtl_id',$saf_id)
                        ->whereIn('doc_mstr_id', $no_elect_connection_doc)
                        ->where('status', 1)
                        ->get()
                        ->getResultArray();
        } catch(Exception $e) {
            return $e->getMessage();
        }
    }

    public function check_flat_exists($saf_id, $flat_doc){
        try {
             return $this->db->table($this->table)
                        ->select('id')
                        ->where('saf_dtl_id',$saf_id)
                        ->whereIn('doc_mstr_id', $flat_doc)
                        ->where('status', 1)
                        ->get()
                        ->getResultArray();
        } catch(Exception $e) {
            return $e->getMessage();
        }
    }

    public function check_trr_doc($saf_id,$tr_doc)
    {
        try{

             return $this->db->table($this->table)
                        ->select('id,saf_owner_dtl_id,doc_path')
                        ->where('saf_dtl_id',$saf_id)
                        ->where('verify_status',1)
                        ->whereIn('doc_mstr_id', $tr_doc)
                        ->where('status',1)
                        ->get()
                        ->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function check_trrr_doc($saf_id,$tr_doc)
    {
        try{

             return $this->db->table($this->table)
                        ->select('id,saf_owner_dtl_id,doc_path')
                        ->where('saf_dtl_id',$saf_id)
                        ->where('verify_status',0)
                        ->whereIn('doc_mstr_id', $tr_doc)
                        ->where('status',1)
                        ->get()
                        ->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function check_prr_doc($saf_id,$pr_doc)
    {
        try{

             return $this->db->table($this->table)
                        ->select('id,saf_owner_dtl_id,doc_path')
                        ->where('saf_dtl_id',$saf_id)
                        ->where('verify_status',1)
                        ->whereIn('doc_mstr_id', $pr_doc)
                        ->where('status',1)
                        ->get()
                        ->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function check_prrr_doc($saf_id,$pr_doc)
    {
        try{

             return $this->db->table($this->table)
                        ->select('id,saf_owner_dtl_id,doc_path')
                        ->where('saf_dtl_id',$saf_id)
                        ->where('verify_status',0)
                        ->whereIn('doc_mstr_id', $pr_doc)
                        ->where('status',1)
                        ->get()
                        ->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function check_pr_doc($saf_id,$pr_doc)
    {
        try{
             //$names = ['10', '11'];
             return $this->db->table($this->table)
                        ->select('id')
                        ->where('saf_dtl_id',$saf_id)
                        ->whereIn('doc_mstr_id', $pr_doc)
                        ->where('status',1)
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function getOwnerDocDtlBySafIdAndOwnerDtlId($saf_dtl_id, $saf_owner_dtl_id, $other_doc)
    {
        try
        {
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('saf_dtl_id', $saf_dtl_id)
                        ->where('saf_owner_dtl_id', $saf_owner_dtl_id)
                        ->where('other_doc', $other_doc)
                        ->where('doc_mstr_id', 0)
                        ->whereIn('status', [1, 2])
						//->where('verify_status', 0)
                        ->get();
            //echo $this->db->getLastQuery();
            return $builder->getFirstRow("array");
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }
    public function getSafFormDocBySafDtlId($saf_dtl_id)
    {
        try
        {
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('saf_dtl_id',$saf_dtl_id)
                         ->where('other_doc', 'saf_form')
                        ->whereIn('status', [1, 2])
                        ->get();
            //echo $this->db->getLastQuery();
            return $builder->getResultArray()[0];
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function get_details_by_safid($saf_id,$saf_owner_dtl_id,$app_other_doc,$verify_status)
    {
        try{

             return $this->db->table($this->table)
                        ->select('*')
                        ->where('saf_dtl_id',$saf_id)
                        ->where('saf_owner_dtl_id',$saf_owner_dtl_id)
                        ->where('other_doc',$app_other_doc)
                        ->where('verify_status', $verify_status)
                        ->where('doc_mstr_id', 0)
                        //->where('status',1)
                        ->get()
                        ->getResultArray()[0];
						//echo $this->db->getLastQuery();
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function get_ownerimgdetails_by_safid($saf_id,$saf_owner_dtl_id,$app_other_doc)
    {
        try{

             return $this->db->table($this->table)
                        ->select('*')
                        ->where('saf_dtl_id',$saf_id)
                        ->where('saf_owner_dtl_id',$saf_owner_dtl_id)
                        ->where('other_doc',$app_other_doc)
                        ->where('doc_mstr_id', 0)
                        ->where('status',1)
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function get_ownersafform_by_safid($saf_id,$app_other_doc)
    {
        try{

             return $this->db->table($this->table)
                        ->select('*')
                        ->where('saf_dtl_id',$saf_id)
                        ->where('other_doc',$app_other_doc)
                        ->where('doc_mstr_id', 0)
                        ->where('status',1)
                        ->get()
                        ->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function updateDocVerifiedStatusById($input){
        return $this->db->table($this->table)
                        ->where('id', $input['level_pending_dtl_id'])
                        ->where('verify_status', 0)
                        ->update([
                            'remarks'=> $input['remarks'],
                            'verify_status'=> $input['verify_status'],
                            'verified_by_emp_id'=> $input['verified_by_emp_id'],
                            'verified_on'=> $input['verified_on']
                        ]);
    }

    public function updateappimgdocById($input){
        return $builder = $this->db->table($this->table)
                            ->where('id', $input['applicant_img_id'])
                            ->where('verify_status', 0)
                            ->update([
                                    'remarks'=>$input['app_img_remarks'],
                                    'verify_status'=>$input['app_img_verify'],
                                    'verified_by_emp_id'=>$input['emp_details_id'],
                                    'verified_on'=>$input['created_on']
                                    ]);

    }
    public function updateappdocById($input){
        return $builder = $this->db->table($this->table)
                            ->where('id', $input['applicant_doc_id'])
                            ->where('verify_status', 0)
                            ->update([
                                    'remarks'=>$input['app_doc_remarks'],
                                    'verify_status'=>$input['app_doc_verify'],
                                    'verified_by_emp_id'=>$input['emp_details_id'],
                                    'verified_on'=>$input['created_on']
                                    ]);

    }
    public function updatetrdocById($input){
        return $builder = $this->db->table($this->table)
                            ->where('id', $input['tr_document_id'])
                            ->where('verify_status', 0)
                            ->update([
                                    'remarks'=>$input['tr_remarks'],
                                    'verify_status'=>$input['tr_verify'],
                                    'verified_by_emp_id'=>$input['emp_details_id'],
                                    'verified_on'=>$input['created_on']
                                    ]);
    }

	public function updatesuperdocById($input){
        return $builder = $this->db->table($this->table)
                            ->where('id', $input['super_document_id'])
                            ->where('verify_status', 0)
                            ->update([
                                    'remarks'=>$input['super_remarks'],
                                    'verify_status'=>$input['super_verify'],
                                    'verified_by_emp_id'=>$input['emp_details_id'],
                                    'verified_on'=>$input['created_on']
                                    ]);
    }

	public function updateflatdocById($input){
        return $builder = $this->db->table($this->table)
                            ->where('id', $input['flat_document_id'])
                            ->where('verify_status', 0)
                            ->update([
                                    'remarks'=>$input['flat_remarks'],
                                    'verify_status'=>$input['flat_verify'],
                                    'verified_by_emp_id'=>$input['emp_details_id'],
                                    'verified_on'=>$input['created_on']
                                    ]);
    }


    public function updateprdocById($input){
        return $builder = $this->db->table($this->table)
                            ->where('id', $input['pr_document_id'])
                            ->where('verify_status', 0)
                            ->update([
                                    'remarks'=>$input['pr_remarks'],
                                    'verify_status'=>$input['pr_verify'],
                                    'verified_by_emp_id'=>$input['emp_details_id'],
                                    'verified_on'=>$input['created_on']
                                    ]);
    }
    public function safFormDocNameByOtherDocAndSafDtlId($saf_dtl_id, $status)
    {
        try{
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('saf_dtl_id',$saf_dtl_id)
                         ->where('other_doc', 'saf_form')
                        ->where('verify_status', $status)
                        ->where('status',1)
                        ->get();
            //echo $this->db->getLastQuery();
           return $builder->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function getApplicantImgBySafDtlAndSafOwnerDtlId($input)
    {
        try
        {
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('saf_dtl_id', $input['saf_dtl_id'])
                        ->where('saf_owner_dtl_id', $input['saf_owner_dtl_id'])
                        ->where('other_doc', 'applicant_image')
                        ->where('doc_mstr_id', 0)
                        ->whereIn('status', [1])
						->whereIn('verify_status', ['0', '1', '2'])
						->orderBy('id','DESC')
                        ->get();
                //echo $this->db->getLastQuery();
            return $builder->getFirstRow('array');
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }



    public function getApplicantDocBySafDtlAndSafOwnerDtlId($input)
    {
        try
        {
            $builder = $this->db->table($this->table)
                        ->select('
                            tbl_saf_doc_dtl.id as saf_doc_dtl_id,
                            tbl_saf_doc_dtl.saf_dtl_id as saf_dtl_id,
                            tbl_saf_doc_dtl.doc_mstr_id as doc_mstr_id,
                            tbl_doc_mstr.doc_name as doc_name,
                            tbl_saf_doc_dtl.other_doc as other_doc,
                            tbl_saf_doc_dtl.doc_path as doc_path,
                            tbl_saf_doc_dtl.emp_details_id as emp_details_id,
                            tbl_saf_doc_dtl.created_on as created_on,
                            tbl_saf_doc_dtl.status as saf_doc_dtl_status,
                            tbl_saf_doc_dtl.remarks as remarks,
                            tbl_saf_doc_dtl.verify_status as verify_status,
                            tbl_saf_doc_dtl.verified_by_emp_id as verified_by_emp_id,
                            tbl_saf_doc_dtl.verified_on as verified_on,
                            tbl_saf_doc_dtl.saf_owner_dtl_id as saf_owner_dtl_id,
                            tbl_doc_mstr.status as doc_mstr_status,
                            tbl_doc_mstr.doc_type as doc_type,
                            tbl_doc_mstr.doc_id as doc_id'
                        )
                        ->join('tbl_doc_mstr', 'tbl_doc_mstr.id = tbl_saf_doc_dtl.doc_mstr_id')
                        ->where('tbl_saf_doc_dtl.saf_dtl_id', $input['saf_dtl_id'])
                        ->where('tbl_saf_doc_dtl.saf_owner_dtl_id', $input['saf_owner_dtl_id'])
                        ->where('tbl_doc_mstr.doc_type', 'other')
                        ->whereIn('tbl_saf_doc_dtl.status', [1])
                        ->get();
                //echo $this->db->getLastQuery();
            return $builder->getFirstRow('array');
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function getHandicapedDocBySafDtlAndSafOwnerDtlId($input)
    {
        try
        {
            $builder = $this->db->table($this->table)
                        ->select('
                            tbl_saf_doc_dtl.id as saf_doc_dtl_id,
                            tbl_saf_doc_dtl.saf_dtl_id as saf_dtl_id,
                            tbl_saf_doc_dtl.doc_mstr_id as doc_mstr_id,
                            tbl_doc_mstr.doc_name as doc_name,
                            tbl_saf_doc_dtl.other_doc as other_doc,
                            tbl_saf_doc_dtl.doc_path as doc_path,
                            tbl_saf_doc_dtl.emp_details_id as emp_details_id,
                            tbl_saf_doc_dtl.created_on as created_on,
                            tbl_saf_doc_dtl.status as saf_doc_dtl_status,
                            tbl_saf_doc_dtl.remarks as remarks,
                            tbl_saf_doc_dtl.verify_status as verify_status,
                            tbl_saf_doc_dtl.verified_by_emp_id as verified_by_emp_id,
                            tbl_saf_doc_dtl.verified_on as verified_on,
                            tbl_saf_doc_dtl.saf_owner_dtl_id as saf_owner_dtl_id,
                            tbl_doc_mstr.status as doc_mstr_status,
                            tbl_doc_mstr.doc_type as doc_type,
                            tbl_doc_mstr.doc_id as doc_id'
                        )
                        ->join('tbl_doc_mstr', 'tbl_doc_mstr.id = tbl_saf_doc_dtl.doc_mstr_id')
                        ->where('tbl_saf_doc_dtl.saf_dtl_id', $input['saf_dtl_id'])
                        ->where('tbl_saf_doc_dtl.saf_owner_dtl_id', $input['saf_owner_dtl_id'])
                        ->where('tbl_doc_mstr.doc_type', 'handicaped_document')
                        ->whereIn('tbl_saf_doc_dtl.status', [1])
                        ->get();
                //echo $this->db->getLastQuery();
            return $builder->getFirstRow('array');
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }
    public function getArmedDocBySafDtlAndSafOwnerDtlId($input)
    {
        try
        {
            $builder = $this->db->table($this->table)
                        ->select('
                            tbl_saf_doc_dtl.id as saf_doc_dtl_id,
                            tbl_saf_doc_dtl.saf_dtl_id as saf_dtl_id,
                            tbl_saf_doc_dtl.doc_mstr_id as doc_mstr_id,
                            tbl_doc_mstr.doc_name as doc_name,
                            tbl_saf_doc_dtl.other_doc as other_doc,
                            tbl_saf_doc_dtl.doc_path as doc_path,
                            tbl_saf_doc_dtl.emp_details_id as emp_details_id,
                            tbl_saf_doc_dtl.created_on as created_on,
                            tbl_saf_doc_dtl.status as saf_doc_dtl_status,
                            tbl_saf_doc_dtl.remarks as remarks,
                            tbl_saf_doc_dtl.verify_status as verify_status,
                            tbl_saf_doc_dtl.verified_by_emp_id as verified_by_emp_id,
                            tbl_saf_doc_dtl.verified_on as verified_on,
                            tbl_saf_doc_dtl.saf_owner_dtl_id as saf_owner_dtl_id,
                            tbl_doc_mstr.status as doc_mstr_status,
                            tbl_doc_mstr.doc_type as doc_type,
                            tbl_doc_mstr.doc_id as doc_id'
                        )
                        ->join('tbl_doc_mstr', 'tbl_doc_mstr.id = tbl_saf_doc_dtl.doc_mstr_id')
                        ->where('tbl_saf_doc_dtl.saf_dtl_id', $input['saf_dtl_id'])
                        ->where('tbl_saf_doc_dtl.saf_owner_dtl_id', $input['saf_owner_dtl_id'])
                        ->where('tbl_doc_mstr.doc_type', 'armed_force_document')
                        ->whereIn('tbl_saf_doc_dtl.status', [1])
                        ->get();
                //echo $this->db->getLastQuery();
            return $builder->getFirstRow('array');
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function getGenderDocBySafDtlAndSafOwnerDtlId($input)
    {
        try
        {
            $builder = $this->db->table($this->table)
                        ->select('
                            tbl_saf_doc_dtl.id as saf_doc_dtl_id,
                            tbl_saf_doc_dtl.saf_dtl_id as saf_dtl_id,
                            tbl_saf_doc_dtl.doc_mstr_id as doc_mstr_id,
                            tbl_doc_mstr.doc_name as doc_name,
                            tbl_saf_doc_dtl.other_doc as other_doc,
                            tbl_saf_doc_dtl.doc_path as doc_path,
                            tbl_saf_doc_dtl.emp_details_id as emp_details_id,
                            tbl_saf_doc_dtl.created_on as created_on,
                            tbl_saf_doc_dtl.status as saf_doc_dtl_status,
                            tbl_saf_doc_dtl.remarks as remarks,
                            tbl_saf_doc_dtl.verify_status as verify_status,
                            tbl_saf_doc_dtl.verified_by_emp_id as verified_by_emp_id,
                            tbl_saf_doc_dtl.verified_on as verified_on,
                            tbl_saf_doc_dtl.saf_owner_dtl_id as saf_owner_dtl_id,
                            tbl_doc_mstr.status as doc_mstr_status,
                            tbl_doc_mstr.doc_type as doc_type,
                            tbl_doc_mstr.doc_id as doc_id'
                        )
                        ->join('tbl_doc_mstr', 'tbl_doc_mstr.id = tbl_saf_doc_dtl.doc_mstr_id')
                        ->where('tbl_saf_doc_dtl.saf_dtl_id', $input['saf_dtl_id'])
                        ->where('tbl_saf_doc_dtl.saf_owner_dtl_id', $input['saf_owner_dtl_id'])
                        ->where('tbl_doc_mstr.doc_type', 'gender_document')
                        ->whereIn('tbl_saf_doc_dtl.status', [1])
                        ->get();
                //echo $this->db->getLastQuery();
            return $builder->getFirstRow('array');
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }
    public function getDobDocBySafDtlAndSafOwnerDtlId($input)
    {
        try
        {
            $builder = $this->db->table($this->table)
                        ->select('
                            tbl_saf_doc_dtl.id as saf_doc_dtl_id,
                            tbl_saf_doc_dtl.saf_dtl_id as saf_dtl_id,
                            tbl_saf_doc_dtl.doc_mstr_id as doc_mstr_id,
                            tbl_doc_mstr.doc_name as doc_name,
                            tbl_saf_doc_dtl.other_doc as other_doc,
                            tbl_saf_doc_dtl.doc_path as doc_path,
                            tbl_saf_doc_dtl.emp_details_id as emp_details_id,
                            tbl_saf_doc_dtl.created_on as created_on,
                            tbl_saf_doc_dtl.status as saf_doc_dtl_status,
                            tbl_saf_doc_dtl.remarks as remarks,
                            tbl_saf_doc_dtl.verify_status as verify_status,
                            tbl_saf_doc_dtl.verified_by_emp_id as verified_by_emp_id,
                            tbl_saf_doc_dtl.verified_on as verified_on,
                            tbl_saf_doc_dtl.saf_owner_dtl_id as saf_owner_dtl_id,
                            tbl_doc_mstr.status as doc_mstr_status,
                            tbl_doc_mstr.doc_type as doc_type,
                            tbl_doc_mstr.doc_id as doc_id'
                        )
                        ->join('tbl_doc_mstr', 'tbl_doc_mstr.id = tbl_saf_doc_dtl.doc_mstr_id')
                        ->where('tbl_saf_doc_dtl.saf_dtl_id', $input['saf_dtl_id'])
                        ->where('tbl_saf_doc_dtl.saf_owner_dtl_id', $input['saf_owner_dtl_id'])
                        ->where('tbl_doc_mstr.doc_type', 'dob_document')
                        ->whereIn('tbl_saf_doc_dtl.status', [1])
                        ->get();
                //echo $this->db->getLastQuery();
            return $builder->getFirstRow('array');
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function getUploadedFormDocBySafDtlId($input) {
        try {
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('saf_dtl_id', $input['saf_dtl_id'])
                        ->where('other_doc', 'saf_form')
                        ->where('status', 1)
                        ->get();
                //echo $this->db->getLastQuery();
            return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function getUploadedDocBySafDtlId($input) {
        try {
            $builder = $this->db->table($this->table)
                        ->select('
                            tbl_saf_doc_dtl.id as saf_doc_dtl_id,
                            tbl_saf_doc_dtl.saf_dtl_id as saf_dtl_id,
                            tbl_saf_doc_dtl.doc_mstr_id as doc_mstr_id,
                            tbl_doc_mstr.doc_name as doc_name,
                            tbl_saf_doc_dtl.other_doc as other_doc,
                            tbl_saf_doc_dtl.doc_path as doc_path,
                            tbl_saf_doc_dtl.emp_details_id as emp_details_id,
                            tbl_saf_doc_dtl.created_on as created_on,
                            tbl_saf_doc_dtl.status as saf_doc_dtl_status,
                            tbl_saf_doc_dtl.remarks as remarks,
                            tbl_saf_doc_dtl.verify_status as verify_status,
                            tbl_saf_doc_dtl.verified_by_emp_id as verified_by_emp_id,
                            tbl_saf_doc_dtl.verified_on as verified_on,
                            tbl_saf_doc_dtl.saf_owner_dtl_id as saf_owner_dtl_id,
                            tbl_doc_mstr.status as doc_mstr_status,
                            tbl_doc_mstr.doc_type as doc_type,
                            tbl_doc_mstr.doc_id as doc_id'
                        )
                        ->join('tbl_doc_mstr', 'tbl_doc_mstr.id = tbl_saf_doc_dtl.doc_mstr_id')
                        ->where('tbl_saf_doc_dtl.saf_dtl_id', $input['saf_dtl_id'])
                        ->where('tbl_doc_mstr.doc_type !=', 'other')
                        ->where('tbl_saf_doc_dtl.status', 1)
                        ->get();
                //echo $this->db->getLastQuery();
            return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

// new
    public function getApplicantDocBySafDtlAndSafOwnerDtlIdFinal($input) {
        try {
            $builder = $this->db->table($this->table)
                        ->select('
                            tbl_saf_doc_dtl.id as saf_doc_dtl_id,
                            tbl_saf_doc_dtl.saf_dtl_id as saf_dtl_id,
                            tbl_saf_doc_dtl.doc_mstr_id as doc_mstr_id,
                            tbl_doc_mstr.doc_name as doc_name,
                            tbl_saf_doc_dtl.other_doc as other_doc,
                            tbl_saf_doc_dtl.doc_path as doc_path,
                            tbl_saf_doc_dtl.emp_details_id as emp_details_id,
                            tbl_saf_doc_dtl.created_on as created_on,
                            tbl_saf_doc_dtl.status as status,
                            tbl_saf_doc_dtl.remarks as remarks,
                            tbl_saf_doc_dtl.verify_status as verify_status,
                            tbl_saf_doc_dtl.verified_by_emp_id as verified_by_emp_id,
                            tbl_saf_doc_dtl.verified_on as verified_on,
                            tbl_saf_doc_dtl.saf_owner_dtl_id as saf_owner_dtl_id,
                            tbl_doc_mstr.status as doc_mstr_status,
                            tbl_doc_mstr.doc_type as doc_type,
                            tbl_doc_mstr.doc_id as doc_id'
                        )
                        ->join('tbl_doc_mstr', 'tbl_doc_mstr.id = tbl_saf_doc_dtl.doc_mstr_id')
                        ->where('tbl_saf_doc_dtl.saf_dtl_id', $input['saf_dtl_id'])
                        ->where('tbl_saf_doc_dtl.saf_owner_dtl_id', $input['saf_owner_dtl_id'])
                        ->where('tbl_doc_mstr.doc_type', 'other')
                        ->whereIn('tbl_saf_doc_dtl.status', [1, 2])
                        ->get();
                //echo $this->db->getLastQuery();
            return $builder->getFirstRow('array');
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function getApplicantImgBySafDtlAndSafOwnerDtlIdFinal($input) {
        try {
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('saf_dtl_id', $input['saf_dtl_id'])
                        ->where('saf_owner_dtl_id', $input['saf_owner_dtl_id'])
                        ->where('doc_mstr_id', 0)
                        ->where('other_doc', 'applicant_image')
                        ->whereIn('status', [1, 2])
                        ->get();
                //echo $this->db->getLastQuery();
            return $builder->getFirstRow('array');
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function getSafFormBySafDtlIdFinal($input) {
        try {
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('saf_dtl_id', $input['saf_dtl_id'])
                        ->where('doc_mstr_id', 0)
                        ->where('other_doc', 'saf_form')
                        ->whereIn('status', [1, 2])
                        ->get();
                //echo $this->db->getLastQuery();
            return $builder->getFirstRow('array');
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function getNoElectConnectionDtlBySafDtlId($input) {
        try {
             return $this->db->table($this->table)
                        ->select('*')
                        ->where('saf_dtl_id', $input['saf_dtl_id'])
                        ->where("doc_mstr_id IN (SELECT id FROM tbl_doc_mstr WHERE doc_type='no_elect_connection' AND status=1)")
                        ->whereIn('status', [1, 2])
                        ->get()
                        ->getResultArray()[0];
        } catch(Exception $e) {
            return $e->getMessage();
        }
    }

    public function getSuperStructureDocDtlBySafDtlId($input) {
        try {
             return $this->db->table($this->table)
                        ->select('*')
                        ->where('saf_dtl_id', $input['saf_dtl_id'])
                        ->where("doc_mstr_id IN (SELECT id FROM tbl_doc_mstr WHERE doc_type='super_structure_doc' AND status=1)")
                        ->whereIn('status', [1, 2])
                        ->get()
                        ->getResultArray()[0];
						//echo $this->db->getLastQuery();
        } catch(Exception $e) {
            return $e->getMessage();
        }
    }

    public function getFlatDtlBySafDtlId($input) {
        try {
             return $this->db->table($this->table)
                        ->select('*')
                        ->where('saf_dtl_id', $input['saf_dtl_id'])
                        ->whereIn('doc_mstr_id', [7, 18])
                        ->whereIn('status', [1, 2])
                        ->get()
                        ->getResultArray()[0];
        } catch(Exception $e) {
            return $e->getMessage();
        }
    }

    public function getTransferModeDocDtlBySafDtlId($input) {
        try {
             return $this->db->table($this->table)
                        ->select('*')
                        ->where('saf_dtl_id', $input['saf_dtl_id'])
                        ->where("doc_mstr_id IN (SELECT id FROM tbl_doc_mstr WHERE doc_type='transfer_mode' AND status=1)")
                        ->whereIn('status', [1, 2])
                        ->get()
                        ->getResultArray()[0];
        } catch(Exception $e) {
            return $e->getMessage();
        }
    }

    public function getPropertyTypeDocDtlBySafDtlId ($input) {
        try {
             return $this->db->table($this->table)
                        ->select('*')
                        ->where('saf_dtl_id', $input['saf_dtl_id'])
                        ->where("doc_mstr_id IN (SELECT id FROM tbl_doc_mstr WHERE doc_type='property_type' AND status=1)")
                        ->whereIn('status', [1, 2])
                        ->get()
                        ->getResultArray()[0];
        } catch(Exception $e) {
            return $e->getMessage();
        }
    }


	public function updatestatusDocUpload($input){
		//print_r($input);
        return $builder = $this->db->table($this->table)
						->where('saf_dtl_id', $input['saf_dtl_id'])
						->update([
								'status'=> 1
							]);
    }



    public function check_ownerdtl_img($saf_id,$saf_owner_dtl_id)
    {
        try
        {
            $other_doc="applicant_image";
            $stts = ['1', '2', '0'];
            return $this->db->table($this->table)
                        ->select('id,saf_owner_dtl_id,doc_path,remarks,verify_status')
                        ->where('saf_dtl_id',$saf_id)
                        ->whereIn('verify_status',$stts)
                        ->where('other_doc',$other_doc)
                        ->where('saf_owner_dtl_id',$saf_owner_dtl_id)
                        ->where('doc_mstr_id', 0)
                        ->where('status',1)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray()[0];

        }catch(Exception $e){
            return $e->getMessage();
        }
    }


	public function check_ownerdtl_doc($saf_id,$saf_owner_dtl_id)
    {
        try{
            $stts = ['1', '2', '0'];
            $builder = $this->db->table($this->table)
                        ->select('id,saf_owner_dtl_id,doc_path,remarks,verify_status,doc_mstr_id')
                        ->where('saf_dtl_id',$saf_id)
                        ->whereIn('verify_status',$stts)
                        ->where('other_doc','')
                        ->where('saf_owner_dtl_id',$saf_owner_dtl_id)
                        ->where('status',1)
                        ->orderBy('id','DESC')
                        ->get();
            //echo $this->db->getLastQuery();
            return $builder->getFirstRow('array');
        }catch(Exception $e){
            return $e->getMessage();
        }
    }


	public function check_supdoc($saf_id,$sup_doc)
    {
		//print_r($sup_doc);
        try
        {
            $stts = ['1', '2', '0'];
            $builder= $this->db->table($this->table)
				->select('id,saf_owner_dtl_id,doc_path,remarks,verify_status')
				->where('saf_dtl_id',$saf_id)
				->whereIn('verify_status',$stts)
				->where('doc_mstr_id', $sup_doc)
				->where('status',1)
				->orderBy('id','DESC')
				->get();
		    //echo $this->db->getLastQuery();
            $builder->getLastRow('array');
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }


	public function check_flatdoc($saf_id)
    {
		//print_r($sup_doc);
        try{
            $stts = ['1', '2', '0'];
			$flat_doc = ['7', '18'];
            return $this->db->table($this->table)
				->select('id,saf_owner_dtl_id,verify_status')
				->where('saf_dtl_id',$saf_id)
				->whereIn('verify_status',$stts)
				->whereIn('doc_mstr_id', $flat_doc)
				->where('status',1)
				->orderBy('id','DESC')
				->get()
				->getResultArray()[0];
				//echo $this->db->getLastQuery();
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

	public function getAllVerifiedDocuments($input)
    {
        $builder = $this->db->table($this->table)
                            ->select('tbl_saf_doc_dtl.*, tbl_doc_mstr.doc_name')
                            ->join('tbl_doc_mstr', 'tbl_doc_mstr.id = tbl_saf_doc_dtl.doc_mstr_id')
                            ->where('saf_dtl_id', $input['saf_dtl_id'])
                            ->where('tbl_saf_doc_dtl.status', 1)
                            ->where('verify_status', 1)
                            ->whereNotIn('doc_mstr_id', [0]) // 0 mean applicant_image
                            ->where('saf_owner_dtl_id', NULL)
                            ->get();
        //echo $this->db->getLastQuery();
        return $builder->getResultArray();
    }

    public function getLatestUploadedDocuments($input)
    {
        $builder = $this->db->table($this->table)
                            ->select('tbl_saf_doc_dtl.*, tbl_doc_mstr.doc_name')
                            ->join('tbl_doc_mstr', 'tbl_doc_mstr.id = tbl_saf_doc_dtl.doc_mstr_id')
                            ->where('saf_dtl_id', $input['saf_dtl_id'])
                            ->where('tbl_saf_doc_dtl.status', 1)
                            ->whereIn('verify_status', [0,2])
                            ->whereNotIn('doc_mstr_id', [0]) // 0 mean applicant_image
                            ->where('saf_owner_dtl_id', NULL)
                            ->get();
        //echo $this->db->getLastQuery();
        return $builder->getResultArray();
    }

	public function getAllActiveDocuments($saf_dtl_id)
    {
        $builder = $this->db->table($this->table)
                            ->select('tbl_saf_doc_dtl.*, coalesce(tbl_doc_mstr.doc_name, other_doc) as doc_name')
                            ->join('tbl_doc_mstr', 'tbl_doc_mstr.id = tbl_saf_doc_dtl.doc_mstr_id', 'left')
                            ->where('saf_dtl_id', $saf_dtl_id)
                            ->where('tbl_saf_doc_dtl.status', 1)
                            ->whereNotIn('tbl_saf_doc_dtl.other_doc', ['applicant_image']) // 0 mean applicant_image
							->orderBy('id', 'ASC')
                            ->get();
        //echo $this->db->getLastQuery();
        return $builder->getResultArray();
    }
	public function getAllDocuments($saf_dtl_id)
    {
        $builder = $this->db->table($this->table)
                            ->select('tbl_saf_doc_dtl.*, coalesce(tbl_doc_mstr.doc_name, other_doc) as doc_name')
                            ->join('tbl_doc_mstr', 'tbl_doc_mstr.id = tbl_saf_doc_dtl.doc_mstr_id', 'left')
                            ->where('saf_dtl_id', $saf_dtl_id)
                            // ->whereNotIn('tbl_saf_doc_dtl.other_doc', ['applicant_image']) // 0 mean applicant_image
                            ->get();
        //echo $this->db->getLastQuery();
        return $builder->getResultArray();
    }
	public function getAllDocumentsWithOwner($saf_dtl_id)
    {
        $builder = $this->db->table($this->table)
                            ->select('tbl_saf_doc_dtl.*, coalesce(tbl_doc_mstr.doc_name, other_doc) as doc_name,tbl_saf_owner_detail.owner_name as owner_name')
                            ->join('tbl_doc_mstr', 'tbl_doc_mstr.id = tbl_saf_doc_dtl.doc_mstr_id', 'left')
                            ->join('tbl_saf_owner_detail', 'tbl_saf_owner_detail.id = tbl_saf_doc_dtl.saf_owner_dtl_id', 'left')
                            ->where('tbl_saf_doc_dtl.saf_dtl_id', $saf_dtl_id)
                            // ->whereNotIn('tbl_saf_doc_dtl.other_doc', ['applicant_image']) // 0 mean applicant_image
                            ->get();
        // echo $this->db->getLastQuery();
        // die;
        return $builder->getResultArray();
    }
	public function getAllDocumentsWithOwner2($saf_dtl_id)
    {
        // $where_con = " not (tbl_saf_doc_dtl.status = 2 and tbl_saf_doc_dtl.verify_status = 0)";
        $builder = $this->db->table($this->table)
                            ->select('tbl_saf_doc_dtl.*, coalesce(tbl_doc_mstr.doc_name, other_doc) as doc_name,tbl_saf_owner_detail.owner_name as owner_name')
                            ->join('tbl_doc_mstr', 'tbl_doc_mstr.id = tbl_saf_doc_dtl.doc_mstr_id', 'left')
                            ->join('tbl_saf_owner_detail', 'tbl_saf_owner_detail.id = tbl_saf_doc_dtl.saf_owner_dtl_id', 'left')
                            ->where('tbl_saf_doc_dtl.saf_dtl_id', $saf_dtl_id)
                            ->where("tbl_saf_doc_dtl.doc_path ILIKE 'RANCHI%'")
							->where("tbl_saf_doc_dtl.status!=", 0)
							->orderBy('tbl_saf_doc_dtl.id', 'asc')
                            //->where('(tbl_saf_doc_dtl.status!=2 and tbl_saf_doc_dtl.verify_status!=0)') // 0 mean applicant_image
                            // ->whereNotIn($where_con) // 0 mean applicant_image
                            ->get();
        // echo $this->db->getLastQuery();
        // die;
        return $builder->getResultArray();
    }
	public function getAdditionalDocument($saf_dtl_id)
    {
        $builder = $this->db->table($this->table)
                            ->select('tbl_saf_doc_dtl.*, coalesce(tbl_doc_mstr.doc_name, other_doc) as doc_name')
                            ->join('tbl_doc_mstr', 'tbl_doc_mstr.id = tbl_saf_doc_dtl.doc_mstr_id', 'left')
                            ->where('saf_dtl_id', $saf_dtl_id)
                            ->where('tbl_saf_doc_dtl.status', 1)
                            ->where('tbl_saf_doc_dtl.other_doc', ['additional_doc']) // 0 mean applicant_image
                            ->get();
        //echo $this->db->getLastQuery();
        return $builder->getResultArray();
    }
	public function getWaterDocument($saf_dtl_id)
    {
        $builder = $this->db->table($this->table)
                            ->select('tbl_saf_doc_dtl.*, coalesce(tbl_doc_mstr.doc_name, other_doc) as doc_name')
                            ->join('tbl_doc_mstr', 'tbl_doc_mstr.id = tbl_saf_doc_dtl.doc_mstr_id', 'left')
                            ->where('saf_dtl_id', $saf_dtl_id)
                            ->where('tbl_saf_doc_dtl.status', 1)
                            ->where('tbl_saf_doc_dtl.other_doc', ['water_harvesting']) // 0 mean applicant_image
                            ->get();
        //echo $this->db->getLastQuery();
        return $builder->getResultArray();
    }
	public function deleteReplacedImage($saf_dtl_id)
    {
        $sql = "delete from tbl_saf_doc_dtl where saf_dtl_id=".$saf_dtl_id." and
        (tbl_saf_doc_dtl.status=2 and tbl_saf_doc_dtl.verify_status=0)";

        $this->db->query($sql);
        //echo $this->db->getLastQuery();
        // return $builder->getResultArray();
    }

    public function verifyDocument($input)
    {
        $builder = $this->db->table($this->table)
                            ->where('id', $input["id"])
                            ->Update([
                                "verify_status"=> $input["verify_status"],
                                "remarks"=> $input["remarks"],
                                "verified_by_emp_id"=> $input["verified_by_emp_id"],
                                "verified_on"=> "NOW()",
                            ]);
        //echo $this->db->getLastQuery();
        return $builder;
    }
    public function get_doc_mstr_id($doc_type)
    {
        try
        {
             $sql = "select id from tbl_doc_mstr where doc_type='$doc_type'";
            $builder= $this->query($sql);
            // print_var($builder->getFirstRow('array'));
            // return;
            return $builder->getFirstRow('array');
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }
    public function deactivateOldDoc($saf_doc_id)
    {
        try
        {
             $sql = "UPDATE tbl_saf_doc_dtl set status=2 where id=".$saf_doc_id."";
            $builder= $this->db->query($sql);

            // print_var($builder->getFirstRow('array'));
            // return;
            // return $builder->getResultArray();
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function getAllTrustDocuments($saf_dtl_id)
    {
        $builder = $this->db->table($this->table)
                            ->select('tbl_saf_doc_dtl.*, coalesce(tbl_doc_mstr.doc_name, other_doc) as doc_name')
                            ->join('tbl_doc_mstr', 'tbl_doc_mstr.id = tbl_saf_doc_dtl.doc_mstr_id', 'left')
                            ->where('saf_dtl_id', $saf_dtl_id)
                            ->where('tbl_saf_doc_dtl.status', 1)
                            ->whereIn('tbl_saf_doc_dtl.other_doc', ['trust_document', 'income_tax']) 
                            ->get();
        //echo $this->db->getLastQuery();
        return $builder->getResultArray();
    }

    public function getExtraDocument($saf_dtl_id)
    {
        $builder = $this->db->table($this->table)
                            ->select('tbl_saf_doc_dtl.*, coalesce(tbl_doc_mstr.doc_name, other_doc) as doc_name')
                            ->join('tbl_doc_mstr', 'tbl_doc_mstr.id = tbl_saf_doc_dtl.doc_mstr_id', 'left')
                            ->where('saf_dtl_id', $saf_dtl_id)
                            ->where('tbl_saf_doc_dtl.status', 1)
                            ->where('tbl_saf_doc_dtl.other_doc', ['extra_doc']) // 0 mean applicant_image
                            ->get();
        //echo $this->db->getLastQuery();
        return $builder->getResultArray();
    }
}
