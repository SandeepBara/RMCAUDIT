<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_prop_owner_detail extends Model
{
    protected $table = 'tbl_prop_owner_detail';
    protected $allowedFields = [''];
	
	public function insertData($input){
        try{
            $this->db->table($this->table)
                            ->insert($input);
            //echo $this->db->getLastQuery();
            return $this->db->insertID();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function UpdateOwner($data, $prop_owner_dtl_id){
		
        return $this->db->table($this->table)
                            ->where('id', $prop_owner_dtl_id)
                            ->update($data);
        //echo $this->db->getLastQuery();
    }

    public function owner_details($data)
    {
      
		try{        
            $builder = $this->db->table("tbl_prop_owner_detail")
                        ->select('*')
                        ->where('md5(prop_dtl_id::text)', $data['id'])
                        ->where('status', 1)
                        ->get();

           return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
	public function getOwnerDtlById($id) {
		try{        
            $builder = $this->db->table("tbl_prop_owner_detail")
                        ->select('*')
                        ->where('id', $id)
                        ->get();
           return $builder->getFirstRow("array");
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
    public function insertpropownerdetbysafid($input, $prop_dtl_id){
        $sql_owner = "INSERT INTO tbl_prop_owner_detail
                        (prop_dtl_id, owner_name, guardian_name,
                        relation_type, mobile_no, email, 
                        pan_no, aadhar_no, emp_details_id, 
                        created_on, status)
                    SELECT 
                        '".$prop_dtl_id."', owner_name, guardian_name,
                        relation_type, mobile_no, email,
                        pan_no, aadhar_no, '".$input['emp_details_id']."',
                        '".$input['created_on']."', status 
                    FROM tbl_saf_owner_detail WHERE saf_dtl_id='".$input['saf_dtl_id']."'";
            $this->db->query($sql_owner);
            return true; 
         //return $prop_owner_detail_id;
     }

    public function getPropOwnerDtlByPropDtlId($input){
		try{        
            return $this->db->table($this->table)
                        ->select('*')
                        ->where('prop_dtl_id', $input['prop_dtl_id'])
                        ->where('status', 1)
                        ->get()
                        ->getResultArray();
            //echo $this->getLastQuery();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function getAggPropOwnerDtlByPropDtlId($input){
		try{        
            return $this->db->table($this->table)
                        ->select("string_agg(id::text, ',') AS id, string_agg(owner_name::text, ',') AS owner_name, string_agg(guardian_name::text, ',') AS guardian_name, string_agg(mobile_no::text, ',') AS mobile_no")
                        ->where('prop_dtl_id', $input['prop_dtl_id'])
                        ->where('status', 1)
                        ->get()
                        ->getFirstRow('array');
                        // echo $this->getLastQuery();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function getPropertyOwnerDetails($prop_dtl_id)
    {
        try{
            $sql = "select string_agg(owner_name, ',') as owner_name from tbl_prop_owner_detail where prop_dtl_id=".$prop_dtl_id." group by prop_dtl_id";
            $sql = $this->query($sql); 
                        //echo $this->getLastQuery();
          $builder = $sql->getFirstRow("array");
           return  $builder["owner_name"];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function ownerDetailsData($id)
    {
        try{        
            return $this->db->table($this->table)
                        ->select('relation_type,mobile_no,owner_name')
                        ->where('prop_dtl_id',$id)
                        ->where('status', 1)
                        ->get()
                        ->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function getPropertyGuardianDetails($prop_dtl_id)
    {
        try{
            $sql = "select string_agg(guardian_name , ',') as guardian from tbl_prop_owner_detail where prop_dtl_id=".$prop_dtl_id." group by prop_dtl_id";
            $sql = $this->query($sql); 
                        //echo $this->getLastQuery();
          $builder = $sql->getFirstRow("array");
           return  $builder["guardian"];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function property($prop_dtl_id){
        try{
            $builder = $this->db->table($this->table)
                     ->select('mobile_no,email,pan_no,aadhar_no,relation_type')
                     ->where('prop_dtl_id',$prop_dtl_id)
                     ->where('status',1)
                     ->get();
            return $builder->getResultArray()[0];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function propownerdetails($prop_dtl_id)
    {      
      try{
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('status', 1)
                        ->where('prop_dtl_id', $prop_dtl_id)
                        ->get(); 
           return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function propertyidbyownername($owner_name){
        try{
            $builder = $this->db->table($this->table)
                     ->select('prop_dtl_id')
                     ->like('owner_name', $owner_name)
                     ->where('status',1)
                     ->get();
            return $builder->getResultArray()[0];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function propertyidbyownermobno($mobile_no){
        try{
            $builder = $this->db->table($this->table)
                     ->select('prop_dtl_id')
                     ->where('mobile_no', $mobile_no)
                     ->where('status',1)
                     ->get();
            return $builder->getResultArray()[0];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
	
	
	public function citizenName($data)
    {
		try{        
            $builder = $this->db->table("tbl_prop_owner_detail")
                        ->select('owner_name')
                        ->where('md5(prop_dtl_id::text)', $data)
                        ->get();

           return $builder->getFirstRow('array');
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	public function getOwnerDataByPropDetailsId($prop_dtl_id)
    {
        try
        {
            $builder = $this->db->table($this->table)
                    ->select("string_agg(mobile_no::varchar,',') as mobile_no,string_agg(relation_type,',') as relation_type,string_agg(email,',') as email,string_agg(pan_no::varchar,',') as pan_no,string_agg(guardian_name::varchar,',') as aadhar_no,string_agg(guardian_name,',') as guardian_name,string_agg(owner_name,',') as owner_name")
                    ->where('prop_dtl_id',$prop_dtl_id)
                    ->groupBy('prop_dtl_id')
                    ->where('status',1)
                    ->get();
                   // echo $this->db->getLastQuery();
            return $result = $builder->getResultArray()[0];
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function getPropOwnerDtlByProp_dtlId($input)
    {      
      try
      {
             $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('status', 1)
                        ->where('prop_dtl_id', $input['prop_dtl_id'])
						->OrderBy('id')
                        ->get(); 
            //echo $this->db->getLastQuery();
            return $builder->getResultArray();
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }
	
	
	public function owner_det($data)
    { 
        $builder = "select * from tbl_prop_owner_detail
		where md5(prop_dtl_id::TEXT)='".$data."'";
        $builder = $this->query($builder);
        //echo $this->db->getLastQuery();
        return $builder = $builder->getResultArray();
    }
	
	
	
	public function pro_ownerdetail_update($data){
		
        return $this->db->table($this->table)
                            ->where('prop_dtl_id', $data['prop_dtl_id'])
                            ->update([
                                'owner_name'=>$data['owner_name'],
								'relation_type'=>$data['relation_type'],
								'mobile_no'=>$data['mobile_no'],
								'guardian_name'=>$data['guardian_name'],
                            ]);
        //echo $this->db->getLastQuery();
    }
	
	public function gatedetailsowner($data)
    {
		try{        
            $builder = "select * from view_prop_dtl_owner_ward_prop_type_ownership_type
			where holding_no='".$data."' or new_holding_no='".$data."'";
			$builder = $this->query($builder);
           return $builder->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
	}
    public function UpdateOwnerSpecialData($owner_id, $sql_cond){
		
         $sql = "update tbl_prop_owner_detail set ".$sql_cond." where id=".$owner_id." and status=1";
        $this->db->query($sql);
        return $this->db->insertID();
        
    }

    public function UpdateOwnerSpecialDataFull($owner_id, $data_input){
		
         $sql = "update tbl_prop_owner_detail set gender='".$data_input['gender']."',dob='".$data_input['dob']."',is_specially_abled='".$data_input['is_specially_abled']."',is_armed_force='".$data_input['is_armed_force']."' where id=".$owner_id." and status=1";
       $this->db->query($sql);
      
    //    return $this->db->insertID();
       return true;
       
   }
	
}
?>