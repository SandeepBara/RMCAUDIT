<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class water_consumer_demand_model extends Model
{

    protected $table = 'tbl_consumer_demand';
    protected $allowedFields = ['id','consumer_id','ward_mstr_id','consumer_tax_id','generation_date','amount','paid_status','emp_details_id','created_on','status'];
	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}
    public function countPaidStatus($id){
        try{        
             $builder = $this->db->table($this->table)
                        ->select('COUNT(paid_status) as count')
                        ->where('md5(consumer_id::text)',$id)
                        ->where('paid_status',0)
                        ->where('status',1)
                        ->groupBy('consumer_id')
                        ->get();
                        //echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
           return  $builder['count'];
        }catch(Exception $e){
            echo $e->getMessage();   
        }
    }
    public function countPaidStatus2($id){
        try{        
             $builder = $this->db->table($this->table)
                        ->select('COUNT(paid_status) as count')
                        ->where('consumer_id',$id)
                        ->where('paid_status',0)
                        ->groupBy('consumer_id')
                        ->get();
                        //echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
           return  $builder['count'];
        }catch(Exception $e){
            echo $e->getMessage();   
        }
    }
    public function updateStatus($demand_id){
        try{
            return $builder = $this->db->table($this->table)
                             ->where('id',$demand_id)
                             ->where('status',1)
                             ->update([
                                        'paid_status'=>0
                                    ]);
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
}