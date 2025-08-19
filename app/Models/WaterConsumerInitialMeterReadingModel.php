<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class WaterConsumerInitialMeterReadingModel extends Model
{

    protected $table = 'tbl_consumer_initial_meter';

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}



    public function insertData(array $data)
    {

    	$result= $this->db->table($this->table)
                 ->insert($data);       
        //echo $this->getLastQuery();
        $insert_id=$this->db->insertID();
        return $insert_id;

    }

    public function initial_meter_reading($consumer_id) // get last reading
    {
        $result=$this->db->table($this->table)
                            ->select("initial_reading,date(created_on) as initial_date,id ")
                            ->where("consumer_id",$consumer_id)
                            ->where("status",1)
                            ->orderby("id desc")
                            ->get()
                            ->getFirstRow("array");
        // echo($this->db->getLastQuery());die; 
        return $result;

    }
    public function getpreviousMeterReding($consumer_id,$last_id)
    {
        $sql="select * from tbl_consumer_initial_meter where (consumer_id)='".$consumer_id."' and id < $last_id and status=1 order by id desc limit 1";
        $run=$this->db->query($sql);
       //echo $this->db->getLastQuery();
        $result=$run->getFirstRow("array");
        return $result;
    }

    public function initial_meter_first_reading($consumer_id) // get last reading
    {
        $result=$this->db->table($this->table)
                            ->select("initial_reading,date(created_on) as initial_date,id ")
                            ->where("consumer_id",$consumer_id)
                            ->where("status",1)
                            ->orderby("created_on asc")
                            ->get()
                            ->getFirstRow("array");
        //echo($this->db->getLastQuery());die; 
        return $result;

    }
    
    

}