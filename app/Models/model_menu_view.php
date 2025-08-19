<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_menu_view extends Model
{
    protected $db;
    protected $allowedFields = ['id','query_string','created_on','menu_mstr_id','user_type_mstr_id','status','user_type'];
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function menu_permission(array $data)
    {
        $this->db->table($this->table)
                 ->insert($data);
    }
    public function menu_permission_list($menu_mstr_id)
    {
        $sql = "SELECT * FROM view_permission_list where menu_mstr_id=? AND status=?";
        $ql= $this->db->query($sql, [$menu_mstr_id,1]);
        //echo $this->db->getLastQuery();
        return $result =$ql->getResultArray();
    }
}
?>