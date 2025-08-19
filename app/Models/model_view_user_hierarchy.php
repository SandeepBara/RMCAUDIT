<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_view_user_hierarchy extends Model
{
    protected $db;
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function userHierarchyList()
    {
        try{
            $builder = $this->db->table('view_userhierarchy_usertypemstr')
                        ->select('user_type_mstr_id,user_type')
                        ->where('status', 1)
                        ->GroupBy('user_type_mstr_id,user_type')
                        ->get();
           return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function under_userHierarchyList($user_type_mstr_id)
    {
        try{
            $builder = $this->db->table('view_userhierarchy_usertypemstr')
                        ->select('under_user_type_mstr_id,under_user_type')
                        ->where('status', 1)
                        ->where('user_type_mstr_id',$user_type_mstr_id)
                        ->get();
           return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function ajax_reporting_data($user_type_mstr_id){
        try{
            $builder = $this->db->table('view_userhierarchy_usertypemstr')
                    ->select('user_type_mstr_id,user_type')
                    ->where('status',1)
                    ->where('under_user_type_mstr_id',$user_type_mstr_id)
                    ->get();
        return $builder->getResultArray();
        }catch(Exception $e){
            echo $e->getMessage();
        }
   }
}
?>
