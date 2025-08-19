<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_menu_permission extends Model
{
    protected $db;
	protected $table = 'tbl_menu_permission';
    protected $allowedFields = ['id','query_string','created_on','menu_mstr_id','user_type_mstr_id','status'];
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function menu_permission(array $data)
    {
        $this->db->table($this->table)
                 ->insert($data);
                 /*echo $this->db->getLastQuery();*/
    }
    public function menu_permission_list($menu_mstr_id)
    {
        $sql = "SELECT * FROM tbl_menu_permission where menu_mstr_id=? 
               AND status=?";
        $ql= $this->db->query($sql, [$menu_mstr_id,1]);
        $result =$ql->getResultArray();
        return $result;
    }
    public function updateMenuPermissionStatusZero($menu_mstr_id)
    {
        return $builder = $this->db->table($this->table)
                            ->where('menu_mstr_id', $menu_mstr_id)
                            ->where('user_type_mstr_id!=', 1)
                            ->update([
                                    'status'=>0
                                    ]);
    }
    public function checkMenuPermissionIsExist($menu_mstr_id,$user_type_mstr_id)
    {
        $sql = "SELECT id FROM tbl_menu_permission
				where user_type_mstr_id=? AND menu_mstr_id=?";
        $ql= $this->db->query($sql, [$user_type_mstr_id,$menu_mstr_id]);
        $result =$ql->getFirstRow('array');
        return $result;
    }
    public function updateMenuPermission($menu_mstr_id,$user_type_mstr_id)
    {
        $sql = "UPDATE tbl_menu_permission
                SET status=1
                where menu_mstr_id=? AND user_type_mstr_id=?";
       $sql= $this->db->query($sql, [$menu_mstr_id,$user_type_mstr_id]);
       /*echo $this->db->getLastQuery();*/
    }
    public function user_list()
    {
        $sql = "SELECT * FROM tbl_user_type_mstr WHERE status = ? AND id != ?";
        $ql= $this->db->query($sql, [1, 1]);
        $result =$ql->getResultArray();
        return $result;
    }
}
?>
