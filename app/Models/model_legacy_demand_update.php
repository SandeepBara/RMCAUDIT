<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_legacy_demand_update extends Model
{
    protected $db;
    protected $table = 'tbl_legacy_demand_update_dtl';
    protected $allowedFields = [''];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function insertData($input){
        $builder = $this->db->table($this->table)
                            ->insert($input);
        return $insert_id = $this->db->insertID();
    }
    public function getid_by_propdtlid($prop_dtl_id)
    {
        try
        {
            return $this->db->table($this->table)
                        ->select('count(id) as count_demand')
                        ->where('prop_dtl_id', $prop_dtl_id)
                        ->where('status', 1)
                        ->get()
                        ->getResultArray()[0];
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }
}
?>