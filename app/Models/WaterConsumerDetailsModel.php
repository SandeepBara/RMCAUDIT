<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class WaterConsumerDetailsModel extends Model
{

    protected $table = 'tbl_consumer_details';

  	public function __construct(ConnectionInterface $db)
  	{
  	    $this->db = $db;
  	}
    
    public function insertWaterConsumerOwner($apply_connection_id,$consumer_id,$login_emp_details_id)
    {

        $sql="insert into tbl_consumer_details(consumer_id,applicant_name,father_name,mobile_no,emp_details_id,created_on) select $consumer_id,applicant_name,father_name,mobile_no,$login_emp_details_id,'".date('Y-m-d H:i:s')."' from tbl_applicant_details where apply_connection_id=$apply_connection_id and status=1";
        $run=$this->db->query($sql);//echo $this->db->getLastQuery();echo"hearrrrrrrrrrrrrrrrrrrrrrrrrrrr"; die();
        
        
    }
    
    public function insertData(array $data)
    {

        $result= $this->db->table($this->table)
                 ->insert($data);       
        //echo $this->db->getLastQuery(); die();
        $insert_id=$this->db->insertID();
        return $insert_id;

    }

}