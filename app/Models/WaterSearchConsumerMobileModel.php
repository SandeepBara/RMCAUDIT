<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class WaterSearchConsumerMobileModel extends Model
{

    protected $table = 'tbl_consumer';

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}

  public function wardlist_by_emp_dtls_id($emp_details_id)
  {

      return $result=$this->db->table("view_ward_permission")
                          ->select("id,ward_no")
                          ->where('emp_details_id',$emp_details_id)
                          ->orderBy(" (substring(ward_no, '^[0-9]+'))::int,ward_no")
                          ->get()
                          ->getResultArray();

                       // echo $this->getLastQuery();
             
  }
   public function search_consumer($where)
   {

        $sql="select id,ward_no,consumer_no,application_no,applicant_name,mobile_no from view_consumer_dtl left join 
          (select consumer_id,string_agg(applicant_name,',') as applicant_name,string_agg(mobile_no::text,',') as mobile_no from tbl_consumer_details group by consumer_id ) as owner on owner.consumer_id=view_consumer_dtl.id where $where";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();
        return $result;

   }
   public function search_consumer2($sql)
   {
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();
        return $result;

   }
   public function getConsumerDetailsbyId($consumer_id)
   {

         $sql="select * from view_consumer_dtl 
                left join (
                        select consumer_id,string_agg(applicant_name,',') as owner_name, string_agg(mobile_no::text,',') as mobile_no 
                        from tbl_consumer_details group by consumer_id
                    )as owner on owner.consumer_id=view_consumer_dtl.id
               where md5(id::text)='".$consumer_id."'";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
     //     echo $this->db->getLastQuery();
        return $result;

   }
   public function getMobileNo($consumer_id)
   {

         $sql="select mobile_no from tbl_consumer_details  where md5(consumer_id::text)='".$consumer_id."'";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        // echo $this->getLastQuery();
        return $result['mobile_no'];

   }

}
