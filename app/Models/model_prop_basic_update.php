<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_prop_basic_update extends Model 
{
    protected $table = 'tbl_prop_basic_update';
    protected $allowedFields = ['id','prop_dtl_id','prop_owner_detail_id','update_type', 'remarks', 'supportive_document', 'create_on', 'emp_detail_id', 'status'];
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function insertData($input) { // Capital Value Rate
        try {
            $this->db->table($this->table)
                            ->insert($input);
            //echo  $this->db->getLastQuery();                    
            return $this->db->insertId();
        } catch(Exception $e) {
            return $e->getMessage();   
        }
    }

    public function updateBYId($id, $input) { // Capital Value Rate
        try {
            return $this->db->table($this->table)
                    ->where('id', $id)
                    ->update($input);
        } catch(Exception $e) {
            return $e->getMessage();   
        }
    }
}