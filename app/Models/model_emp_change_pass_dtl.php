<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_emp_change_pass_dtl extends Model
{
    protected $db;
    protected $table = 'tbl_emp_change_pass_dtl';
    protected $allowedFields = ['id', 'emp_details_id','old_user_pass', 'password_changed_by_emp_details_id','created_on','status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function insertData($input){
        $builder = $this->db->table($this->table)
                            ->insert([
                                "emp_details_id" =>$input['id'],
                                "old_user_pass"=>$input['old_user_pass'],
                                "password_changed_by_emp_details_id" =>$input['id'],
                                "created_on" =>$input['created_on'],
                                "status"=>1
                            ]);
        return $insert_id = $this->db->insertID();
    }
}
?>