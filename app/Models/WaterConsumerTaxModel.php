<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class WaterConsumerTaxModel extends Model
{

    protected $table = 'tbl_consumer_tax';

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}



    public function insertData(array $data)
    {

    	$result= $this->db->table($this->table)
                 ->insert($data);       
        //echo $this->db->getLastQuery(); die;
        $insert_id=$this->db->insertID();
        return $insert_id;

    }
    public function getCount($consumer_id)
    {
        
        return  $result=$this->db->table($this->table)
                                ->select("count(id) as count")
                                ->where('date(created_on)=',date('Y-m-d'))
                                ->where("charge_type",'Meter')
                                ->where("consumer_id",$consumer_id)
                                ->where("status",1)
                                ->get()
                                ->getFirstRow("array");

           // echo $this->getLastQuery();
    }
    public function getData($id)
    {
        try{
            $result = $this->db->table($this->table)
                                -> select('*')
                                ->where('md5(id::text)',$id)
                                ->where('status',1)
                                ->orderBy('id','desc')
                                ->get()
                                ->getFirstRow('array');
            return $result;
        }
        catch(Exception $e)
        {
            echo $e;
        }
    }
    public function getAverageTwoBill($consumer_id)
    {
        try{
            $result = $this->db->table($this->table)
                                -> select('*')
                                ->where('consumer_id',$consumer_id)
                                ->where('status',1)
                                ->orderBy('id','desc')
                                ->limit(2)
                                ->get()
                                ->getResultArray(); 
                                // echo $this->db->getLastQuery();
            return $result;
        }
        catch(Exception $e)
        {
            echo $e; 
        }
    }
}