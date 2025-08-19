<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_view_prop_dtl_demand extends Model
{
    protected $db;
    protected $table = 'view_prop_dtl_demand';
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function getDemandDetails($ward_mstr_id)
    {
       try{        
            $builder = $this->db->table($this->table)
                    ->select('SUM(amount) as total_demand')
                    ->where('ward_mstr_id',$ward_mstr_id)
                    ->where('paid_status',0)
                    ->get();
                  //echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
           return  $builder['total_demand'];
        }catch(Exception $e){
            echo $e->getMessage();   
        }
    }
}
?> 