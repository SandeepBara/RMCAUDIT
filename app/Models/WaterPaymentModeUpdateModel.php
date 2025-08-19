<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class WaterPaymentModeUpdateModel extends Model
{

    protected $table = 'tbl_payment_mode_update_log';

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}


    public function insertData(array $data)
    {

    	$result= $this->db->table($this->table)
                 ->insert($data);       
               //  echo $this->getLastQuery();
        $insert_id=$this->db->insertID();
        return $insert_id;

    }
  
    public function updateData($file,$insert_id)
    {

        $sql="update tbl_payment_mode_update_log set file='$file' where md5(id::text)='$insert_id'";

        $this->query($sql);
        //echo $this->getLastQuery();
    }
}