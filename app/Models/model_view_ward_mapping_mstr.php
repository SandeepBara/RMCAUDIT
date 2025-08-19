<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_view_ward_mapping_mstr extends Model 
{
    protected $db;
    protected $table = 'view_ward_mapping_mstr';
    
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function getNewWardLIstByOldWardMstrId($old_ward_mstr_id)
    {
        try{
            $builder = $this->db->table($this->table)
                    ->select('new_ward_mstr_id AS id, new_ward_no AS ward_no')
                    ->where('old_ward_mstr_id',$old_ward_mstr_id)
                    ->orderBy('new_ward_no','ASC')
                    ->get();
            //print_var($this->db->getLastQuery());
            return $result = $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
}