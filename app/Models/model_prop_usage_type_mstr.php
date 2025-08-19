<?php
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_prop_usage_type_mstr extends Model
{
	  protected $db;
    protected $table = 'tbl_prop_usage_type_mstr';
    protected $allowedFields = ['id','prop_usage_type'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function getPropUsageTypeList(){
        try{
            $builder = $this->db->table($this->table)
                        ->select('id, prop_usage_type')
                        ->get();
            return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
}
?>
