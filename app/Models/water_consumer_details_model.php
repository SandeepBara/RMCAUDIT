<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class water_consumer_details_model extends Model
{

    protected $table = 'tbl_consumer_details';
    protected $allowedFields = ['id','consumer_id','applicant_name','father_name','city','district','state','mobile_no','email_id','emp_details_id','created_on','status'];

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}
    public function getOwnerName($related_id){
        try{
            $builder = $this->db->table($this->table)
                    ->select('applicant_name')
                    ->where('consumer_id', $related_id)
                    ->where('status',1)
                    ->get();
            //echo $this->getLastQuery();
            return $builder->getResultArray()[0];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    public function getConsumerDataByPropId($prop_dtl_id)
    {
        
        $builder = $this->db->table('view_consumer')
                    ->select('*')
                    ->where('prop_dtl_id', $prop_dtl_id)
                    ->limit(1)
                    ->get();
        //echo $this->getLastQuery();
        return $builder->getResultArray();
    }

    public function consumerDetailsData($id){
        try{
            $builder = $this->db->table($this->table)
                     ->select('*')
                     ->where('consumer_id',$id)
                     ->where('status', 1)
                     ->get();
            // echo $this->db->getLastQuery();
            return $builder->getResultArray()[0];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function consumerDetails($id){
        try{
            $builder = $this->db->table($this->table)
                     ->select('*')
                     ->where('consumer_id',$id)
                     ->where('status',1)
                     ->get();
                   // echo $this->db->getLastQuery();
            return $builder->getResultArray();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

      public function consumerDetailsbyMd5($id){
        try{
            $builder = $this->db->table($this->table)
                     ->select('*')
                     ->where('md5(consumer_id::text)', $id)
                     ->where('status', 1)
                     ->get();
            //echo $this->db->getLastQuery();
            return $builder->getResultArray();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    

    public function getConsumerName($consumer_id)
    {
        try{
            $sql = "select string_agg(applicant_name, ',') as applicant_name from tbl_consumer_details where consumer_id=".$consumer_id." group by consumer_id";
            $sql = $this->query($sql); 
                        //echo $this->getLastQuery();
          $builder = $sql->getFirstRow("array");
           return  $builder["applicant_name"];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function getMobileNo($id){
        try{
            $builder = $this->db->table($this->table)
                      ->select('mobile_no')
                      ->where('id',$id)
                      ->where('status',1)
                      ->get();
            return $builder->getResultArray()[0];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getConsumerDetails($consumer_id)
    {
        try
        {
            $builder = $this->db->table($this->table)
                    ->select("string_agg(mobile_no::varchar,',') as mobile_no,string_agg(father_name,',') as father_name,string_agg(applicant_name,',') as applicant_name")
                    ->where('consumer_id',$consumer_id)
                    ->groupBy('consumer_id')
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

    public function getConsumerDetailsbyMd5($consumer_id)
    {
        try
        {
            $builder = $this->db->table($this->table)
                    ->select("string_agg(mobile_no::varchar,',') as mobile_no,string_agg(father_name,',') as father_name,string_agg(applicant_name,',') as applicant_name")
                    ->where('md5(consumer_id::text)',$consumer_id)
                    ->groupBy('consumer_id')
                    ->where('status',1)
                    ->get();
                    //echo $this->db->getLastQuery();
            return $result = $builder->getResultArray();
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }
  

}