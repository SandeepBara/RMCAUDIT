<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_view_property_collection_report extends Model 
{
    protected $db;
    protected $table = 'view_property_collection_report';
    
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function getAllTransactionWard($from_date,$to_date,$ward_mstr_id)
    {
        try{        
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('tran_date >=',$from_date)
                        ->where('tran_date <=',$to_date)
                        ->where('ward_mstr_id',$ward_mstr_id)
                        ->where('tran_type','Property')
                        ->orderBy('id','DESC')
                        ->get();
                        /*echo $this->getLastQuery();*/
           return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
   public function getAllTransaction($from_date,$to_date)
    {
        try{        
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('tran_date >=',$from_date)
                        ->where('tran_date <=',$to_date)
                        ->where('tran_type','Property')
                        ->orderBy('id','DESC')
                        ->get();
                        //echo $this->db->getLastQuery();
           return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
}