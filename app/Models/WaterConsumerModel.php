<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class WaterConsumerModel extends Model
{

    protected $table = 'tbl_consumer';

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}
    public function getConsumer($consumer_id){
        try{
            $builder = $this->db->table($this->table)
                     ->select('*')
                     ->where('id',$consumer_id)
                     ->where('status',1)
                     ->get();
                    // echo $this->db->getLastQuery();
            return $builder->getResultArray()[0];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getConsumerNo($id){
        try{        
             $builder = $this->db->table($this->table)
                        ->select('consumer_no')
                        ->where('id',$id)
                        ->where('status', 1)
                        ->get();
                       // echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
           return  $builder["consumer_no"];
        }catch(Exception $e){
            echo $e->getMessage();   
        }
    }
}