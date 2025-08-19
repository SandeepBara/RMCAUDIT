<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_saf_owner_detail extends Model 
{
    protected $db;
    protected $table = 'tbl_saf_owner_detail';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id', 'saf_dtl_id', 'owner_name', 'guardian_name', 'relation_type', 'mobile_no', 'email', 'pan_no', 'aadhar_no', 'emp_details_id', 'created_on', 'status'];
    
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function insertData($input){
        try{
            return $this->db->table($this->table)
                            ->insert($input);
            //echo $this->db->getLastQuery();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function UpdateOwner($data, $saf_owner_dtl_id){
		
        return $this->db->table($this->table)
                            ->where('id', $saf_owner_dtl_id)
                            ->update($data);
        //echo $this->db->getLastQuery();exit;
    }


    public function joinSafDtlByNameMobile($input) {
        try{

            //$input['mobile_no'] = $input['mobile_no']==""?"NULL":$input['mobile_no'];
            $WHERE = "";
            if ($input['owner_name']!="") {
                $WHERE .= " tbl_saf_owner_detail.owner_name ILIKE '%".$input['owner_name']."%'";
            }
            if ($input['mobile_no']!="") {
                if ($WHERE!=""){
                    $WHERE .= " OR";
                }
                $WHERE .= " tbl_saf_owner_detail.mobile_no ILIKE '%".$input['mobile_no']."%'";
            }

            $sql = "SELECT 
                    tbl_saf_dtl.id AS id,
                    tbl_saf_dtl.saf_no AS saf_no, 
                    tbl_saf_owner_detail.owner_name AS owner_name, 
                    tbl_saf_owner_detail.guardian_name AS guardian_name,
                    tbl_saf_owner_detail.mobile_no AS mobile_no,
                    tbl_saf_dtl.prop_address AS prop_address
                    FROM tbl_saf_dtl
                    INNER JOIN (SELECT saf_dtl_id, STRING_AGG(owner_name, ',') AS owner_name, STRING_AGG(guardian_name, ',') AS guardian_name, STRING_AGG(mobile_no::TEXT, ',') AS mobile_no FROM tbl_saf_owner_detail GROUP BY saf_dtl_id) AS tbl_saf_owner_detail ON tbl_saf_owner_detail.saf_dtl_id = tbl_saf_dtl.id
                    WHERE ".$WHERE;
            $queryResult = $this->db->query($sql);
            $result = $queryResult->getResultArray();
            //echo $this->db->getLastQuery();
            return $result;
        }catch(Exception $e){
            echo $e->getMessage();   
        }
    }

    public function ownerdetbyid($owner_det_id) {      
        try {
            $builder = $this->db->table($this->table)
                                ->select('*')
                                ->where('status', 1)
                                ->where('id', $owner_det_id)
                                ->get(); 
            return $builder->getResultArray();
        } catch(Exception $e) {
            return $e->getMessage();   
        }
    }

    public function getOwnerdtlBySAFId($input) {      
        try {
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('saf_dtl_id', $input['saf_dtl_id'])
                        ->where('status', 1)
						->orderBy("id")
                        ->get();
            //echo  $this->db->getLastQuery();          
            return $builder->getResultArray();
        } catch(Exception $e) {
            return $e->getMessage();   
        }
    }

    public function getOwnerdtlOrberByIdAscBySAFId($input) {      
        try {
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('saf_dtl_id', $input['saf_dtl_id'])
                        ->where('status', 1)
                        ->orderBy('id', 'ASC')
                        ->get();
            //echo  $this->db->getLastQuery();          
            return $builder->getResultArray();
        } catch(Exception $e) {
            return $e->getMessage();   
        }
    }

    public function updateSAFOwnerDtlByBackOffice($input) {
        return $this->db->table($this->table)
               ->where('md5(saf_dtl_id::text)', $input['saf_dtl_id'])
               ->where('id', $input['saf_owner_detail_id'])
               ->update([
                   'owner_name'=>$input['owner_name'],
                   'guardian_name'=>$input['guardian_name'],
                   'relation_type'=>$input['relation_type'],
                   'mobile_no'=>$input['mobile_no'],
                   'email'=>$input['email'],
                   'pan_no'=>$input['pan_no'],
                   'aadhar_no'=>$input['aadhar_no'],
               ]);
       //echo $this->db->getLastQuery();    
   }

    public function ownerdetails($saf_dtl_id) {      
        try {
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('saf_dtl_id', $saf_dtl_id)
                        ->where('status', 1)
						->OrderBy("id")
                        ->get(); 
            //echo $this->db->GetLastQuery();
           return $builder->getResultArray();
        } catch(Exception $e) {
            return $e->getMessage();   
        }
    }

    public function ownerdetails_md5($saf_dtl_id)
    {      
      try{

            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('status', 1)
                        ->where('md5(saf_dtl_id::text)', $saf_dtl_id)
                        ->get(); 

           return $builder->getResultArray();


        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function ownerdetailsBySafId($saf_dtl_id)
    {      
      try{

            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('status', 1)
                        ->where('saf_dtl_id', $saf_dtl_id)
                        ->get(); 

           return $builder->getResultArray();


        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function getSafOwnerDetails($saf_dtl_id)
    {
        try{
            $sql = "select string_agg(owner_name, ',') as owner_name from tbl_saf_owner_detail where saf_dtl_id=".$saf_dtl_id." group by saf_dtl_id";
            $sql = $this->query($sql); 
                        //echo $this->getLastQuery();
          $builder = $sql->getFirstRow("array");
           return  $builder["owner_name"];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function getSafGuardianDetails($saf_dtl_id)
    {
        try{
            $sql = "select string_agg(guardian_name , ',') as guardian from tbl_saf_owner_detail where saf_dtl_id=".$saf_dtl_id." group by saf_dtl_id";
            $sql = $this->query($sql); 
                        //echo $this->getLastQuery();
          $builder = $sql->getFirstRow("array");
           return  $builder["guardian"];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function ownerDetailsData($id)
    {
        try{        
            $builder = $this->db->table($this->table)
                        ->select('relation_type,mobile_no,owner_name')
                        ->where('saf_dtl_id',$id)
                        ->where('status', 1)
                        ->get();
                       // echo $this->db->getLastQuery();
            return  $builder->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	public function saf($saf_dtl_id){
        try{
            $builder = $this->db->table($this->table)
                     ->select('mobile_no,email,pan_no,aadhar_no,relation_type')
                     ->where('saf_dtl_id',$saf_dtl_id)
                     ->where('status',1)
                     ->get();
                     //echo $this->getLastQuery();
            return $builder->getResultArray()[0];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
	
	public function citizenName($data)
    {
		try{        
            $builder = $this->db->table("tbl_saf_owner_detail")
                        ->select('owner_name')
                        ->where('md5(saf_dtl_id::text)', $data)
                        ->get();

           return $builder->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function getOwnerDataBySAFDetailsId($saf_dtl_id)
    {
        try
        {
            $builder = $this->db->table($this->table)
                    ->select("string_agg(mobile_no::varchar,',') as mobile_no,string_agg(relation_type,',') as relation_type,string_agg(email,',') as email,string_agg(pan_no::varchar,',') as pan_no,string_agg(aadhar_no::varchar,',') as aadhar_no,string_agg(owner_name,',') as owner_name,string_agg(guardian_name,',') as guardian_name")
                    ->where('saf_dtl_id',$saf_dtl_id)
                    ->groupBy('saf_dtl_id')
                    ->where('status',1)
                    ->get();
                    //echo $this->db->getLastQuery();
            return $result = $builder->getResultArray()[0];
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }
	
	
	
	public function safowndtl_deactive($data) {
         return $this->db->table($this->table)
                ->where('saf_dtl_id', $data)
                ->update([
                    'status'=>0
                ]); 
    }

    public function joinSafOwnerDtlBySafDtlId($saf_dtl_id) {
        $sql  = "SELECT
                    STRING_AGG(owner_name, ',') AS owner_name, 
                    STRING_AGG(guardian_name, ',') AS guardian_name, 
                    STRING_AGG(mobile_no::TEXT, ',') AS mobile_no
                FROM tbl_saf_owner_detail
                WHERE
                    saf_dtl_id=".$saf_dtl_id." AND status=1";
        $queryResult = $this->db->query($sql);
        return $queryResult->getResultArray()[0];
    }
	
	
}