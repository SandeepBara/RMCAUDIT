<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class WaterConnectionDetailsViewModel extends Model
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

       
        $sql="select * from tbl_apply_water_connection where md5(id::text)='".$water_conn_id."';";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        // echo $this->getLastQuery();
        return $result;

    }
    public function conn_fee_charge($water_conn_id)
    {

        $sql="select * from tbl_connection_charge where md5(apply_connection_id::text)='".$water_conn_id."'";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        return $result;

    }
    public function conn_fee_charge_details($rate_id)
    {
        
        $sql="select * from tbl_water_connection_fee_mstr where md5(id::text)='".$water_conn_id."'";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        return $result;
        
    }




    

}