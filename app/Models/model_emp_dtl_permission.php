<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_emp_dtl_permission extends Model 
{
    protected $db;
    protected $table = 'tbl_emp_dtl_permission';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id', 'ulb_mstr_id', 'ward_mstr_id', 'emp_details_id', 'created_on', 'updated_on'];
    
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function getwardbyempid($login_emp_details_id){
        try{
            $builder = $this->db->table($this->table);
            $builder->select('ward_mstr_id');
            $builder->where('emp_details_id', $login_emp_details_id);
            $builder->where('status', 1);
            $builder = $builder->get();

            $builder = $builder->getResultArray();
            return $builder;
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function insertData($input){
        try{
            $builder = $this->db->table($this->table)
                            ->insert($input);
            return $insert_id = $this->db->insertID();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
}