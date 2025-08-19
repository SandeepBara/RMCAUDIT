<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_view_usage_type_dtl extends Model
{
    protected $db;
    protected $table = 'tbl_usage_type_dtl';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id','usage_type_mstr_id','mult_factor','date_of_effect','status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function usageTypeDetailList()
    {
        try{
            $builder = $this->db->table('model_view_usage_type_dtl')
                        ->select('*')
                        ->where('status',1)
                        ->get();
           return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
}
?> 