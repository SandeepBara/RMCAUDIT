<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_view_saf_dtl_collection extends Model
{
    protected $db;
    protected $table = 'view_saf_dtl_collection';
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function getCollectionDetails($ward_mstr_id)
    {
        try{        
            $builder = $this->db->table($this->table)
                        ->select('SUM(collection_amount) as total_collection')
                        ->where('ward_mstr_id',$ward_mstr_id)
                        ->get();
                       // echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
           return  $builder['total_collection'];
        }catch(Exception $e){
            echo $e->getMessage();   
        }
    }
}
?> 