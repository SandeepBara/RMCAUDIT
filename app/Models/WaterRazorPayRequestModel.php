<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class WaterRazorPayRequestModel extends Model
{

    protected $table = 'tbl_razor_pay_request';

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}

    public function insertData($data)
    {
        $result= $this->db->table($this->table)
                 ->insert($data);       
        //echo $this->getLastQuery();
        $insert_id=$this->db->insertID();
        return $insert_id;
    }

}