<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class water_applicant_details_model extends Model
{

    protected $table = 'tbl_applicant_details';
    protected $allowedFields = ['id','apply_connection_id','applicant_name','father_name','city','district','state','mobile_no','email_id','emp_details_id','created_on','status'];

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}

    public function applicantDetails($apply_connection_id)
    {
        try
        {
            $builder = $this->db->table($this->table)
                    ->select('*')
                    ->where('apply_connection_id', $apply_connection_id)
                    ->where('status', 1)
                    ->get();
            //echo $this->getLastQuery();
            return $builder->getResultArray();
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
   }
    public function applicationDetailsData($apply_connection_id){
        try{
            $builder = $this->db->table($this->table)
                     ->select('applicant_name,mobile_no')
                     ->where('apply_connection_id',$apply_connection_id)
                     ->where('status',1)
                     ->get();
            return $builder->getResultArray()[0];
        }catch(Exception $e){
            echo $e->getMessage();
       }
    }
    public function getApplicantNameDetails($apply_connection_id)
    {
        try{
            $sql = "select string_agg(applicant_name, ',') as applicant_name from tbl_applicant_details where apply_connection_id=".$apply_connection_id." group by apply_connection_id";
            $sql = $this->query($sql); 
                        //echo $this->getLastQuery();
          $builder = $sql->getFirstRow("array");
           return  $builder["applicant_name"];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function applicantData($apply_connection_id){
        try{
            $builder = $this->db->table($this->table)
                     ->select('*')
                     ->where('md5(apply_connection_id::text)',$apply_connection_id)
                     //->where('status',1)
                     ->get();
                     //echo $this->db->getLastQuery();
            return $builder->getResultArray();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getOwnerName($related_id){
        try{
            $builder = $this->db->table($this->table)
                    ->select('applicant_name')
                    ->where('apply_connection_id',$related_id)
                    ->where('status',1)
                    ->get();
                    //echo $this->getLastQuery();
            return $builder->getResultArray()[0];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    public function getApplicantsName($apply_connection_id)
    {
        $sql="select string_agg(applicant_name,',') as applicant_name from tbl_applicant_details where md5(apply_connection_id::text)='".$apply_connection_id."'";

        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        return $result;
        
    }
    public function getApplicantMObileNo($apply_connection_id){
        try{
            $builder = $this->db->table($this->table)
                     ->select('mobile_no')
                     ->where('apply_connection_id',$apply_connection_id)
                     ->where('status',1)
                     ->get();
            return $builder->getResultArray()[0];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getApplicantDetails($apply_connection_id)
    {
      try
      {
          $builder = $this->db->table($this->table)
                  ->select("string_agg(mobile_no::varchar,',') as mobile_no,string_agg(applicant_name,',') as applicant_name,string_agg(father_name,',') as father_name")
                  ->where('apply_connection_id',$apply_connection_id)
                  ->groupBy('apply_connection_id')
                  //->where('status',1)
                  ->get();
                  //echo $this->db->getLastQuery();
          //return $result = $builder->getResultArray()[0];
          return $result = $builder->getFirstRow('array');
      }
      catch(Exception $e)
      {
          echo $e->getMessage();
      }
    }

}