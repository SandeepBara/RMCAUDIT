<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_view_bank_recancilation extends Model 
{
    protected $db;
    protected $table = 'view_bank_recancilation';
    
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function getAllBankRecancilation($ward_mstr_id,$from_date,$to_date)
    {
        try{        
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('ward_mstr_id',$ward_mstr_id)
                        ->where('cancel_date >=',$from_date)
                        ->where('cancel_date <=',$to_date)
                        ->get();
                       // echo $this->getLastQuery();
           return $builder->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    
   
}