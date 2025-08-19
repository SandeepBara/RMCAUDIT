<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_user_ward extends Model
{
    protected $db;
    protected $table = 'tbl_user_ward';
    protected $allowedFields = ['id', 'user_type','status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

   /* public function userType(){
        try{
            $builder = $this->db->table($this->table)
                        ->select('user_type')
                        ->where('user_type')
                        ->where('status', 1)
                        ->get();
           return $builder->getResultArray();

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }*/
}
?>