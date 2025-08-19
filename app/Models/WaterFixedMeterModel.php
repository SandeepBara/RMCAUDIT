<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class WaterFixedMeterRateModel extends Model
{

    protected $table = 'tbl_meter_status';

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

    public function initial_meter_reading($consumer_id)
    {
        return $result=$this->db->table($this->table)
                            ->select("initial_reading")
                            ->where("consumer_id",$consumer_id)
                            ->where("status",1)
                            ->orderby("id desc")
                            ->get()
                            ->getFirstRow("array");

    }
    

}