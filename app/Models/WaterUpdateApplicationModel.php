<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class WaterUpdateApplicationModel extends Model
{

    protected $table = 'tbl_apply_water_connection';

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}



    public function insertData(array $data)
    {

    	$result= $this->db->table($this->table)
                 ->insert($data);       
       // echo $this->getLastQuery();
        $insert_id=$this->db->insertID();
        return $insert_id;

    }
    
    public function fetch_water_con_details($water_conn_id)
    {

       
        $sql="select * from tbl_apply_water_connection where md5(id::text)='".$water_conn_id."' and status in(1,2)";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        // echo $this->getLastQuery();
        return $result;

    }
    public function update_application_details(array $data,$water_conn_id)
    {

         return $builder = $this->db->table($this->table)
                            ->where('id', $water_conn_id)
                            ->update($data);
                //  echo $this->getLastQuery();          
    }

    public function delete_prev_owner($water_conn_id)
    {
         $sql="delete from tbl_applicant_details where apply_connection_id='".$water_conn_id."'";

        $this->db->query($sql);
           //echo $this->getLastQuery();
    }
    public function update_owner(array $data,$water_conn_id)
    {
     
        $result= $this->db->table("tbl_applicant_details")
                 ->insert($data);       
         // echo $this->getLastQuery();
        $insert_id=$this->db->insertID();
        return $insert_id;

    }

    public function update_owner_new(array $data,$owner_id)
    {
     
        $sql="update tbl_applicant_details set applicant_name='".$data['applicant_name']."', father_name='".$data['father_name']."', mobile_no=".$data['mobile_no'].", email_id='".$data['email_id']."', state='".$data['state']."', district='".$data['district']."', city='".$data['city']."' where id=".$owner_id;

        $run=$this->db->query($sql);
      //  echo $this->getLastQuery();

        return $run;

    }

    public function insert_conn_fee(array $data)
    {

        $sql="delete from tbl_connection_charge where apply_connection_id=".$data['apply_connection_id'];

        $this->db->query($sql);
        // echo $this->getLastQuery();
        $result= $this->db->table("tbl_connection_charge")
                 ->insert($data);       
         // echo $this->getLastQuery();
        $insert_id=$this->db->insertID();
        return $insert_id;
    
    }
    public function delete_old_conn_fee($apply_connection_id)
    {
        $sql="delete from tbl_connection_charge where apply_connection_id=".$apply_connection_id;

        $this->db->query($sql);
    }
    public function del_owner($owner_id)
    {
        $sql="delete from tbl_applicant_details where id=".$owner_id;
        $run=$this->db->query($sql);
        return $run;

    }
    public function count_owner($water_conn_id)
    {
        $sql="select count(id) as count_owner from tbl_applicant_details where apply_connection_id=".$water_conn_id;
        $run=$this->db->query($sql);
         //echo $this->getLastQuery();
        $result=$run->getFirstRow("array");
       
        return $result['count_owner'];

    }

    

}