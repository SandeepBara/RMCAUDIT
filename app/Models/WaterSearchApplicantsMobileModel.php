<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class WaterSearchApplicantsMobileModel extends Model
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
   public function search_applicants($where)
   {

        $sql="select id,ward_no,application_no,applicant_name,mobile_no from view_water_application_details  where $where";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
       // echo $this->getLastQuery();
        return $result;

   }
   public function getConsumerDetailsbyId($consumer_id)
   {

        $sql="select * from view_consumer_owner_details  where md5(id::text)='".$consumer_id."'";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        // echo $this->getLastQuery();
        return $result;

   }
   public function getMobileNo($apply_connection_id)
   {

        $sql="select mobile_no from tbl_applicant_details  where md5(apply_connection_id::text)='".$apply_connection_id."'";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        // echo $this->getLastQuery();
        return $result['mobile_no'];

   }

}
