<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_menu_mstr extends Model
{
    protected $db;
    protected $table = 'tbl_menu_mstr';
    protected $allowedFields = ['id','menu_name','parent_menu_mstr_id','url_path','query_string','created_on','order_no','status'];
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function menu_list()
    {
         $sql = "SELECT * FROM tbl_menu_mstr WHERE status = ? AND menu_type IN (0,1,2) ";
        // $result = $this->query($sql, [0,1]);
        $ql= $this->db->query($sql, [1]);
        return $result =$ql->getResultArray();
    }

    public function getParentMenuNameById ($parent_menu_mstr_id) {
        $sql = "SELECT menu_name FROM tbl_menu_mstr WHERE id = $parent_menu_mstr_id";
        $ql= $this->db->query($sql);
        //echo $this->db->getLastQuery();
        $result = $ql->getFirstRow('array')['menu_name'];
        //print_r($result);
        return $result;
    }
    public function getParentMenuDtlNameById ($parent_menu_mstr_id) {
        $sql = "SELECT * FROM tbl_menu_mstr WHERE id = $parent_menu_mstr_id";
        $ql= $this->db->query($sql);
        return $ql->getFirstRow('array');
    }
	public function insert_menu(array $data)
    {
        $this->db->table($this->table)
                 ->insert($data);
                //echo $this->db->getLastQuery();
        return $this->db->insertID();
    }
    public function update_menu($data, $id)
    {
        return $this->db->table($this->table)
                         ->where('id', $id)
                         ->update($data);
                         //echo $this->db->getLastQuery();
    }
    public function getMenuDtlById($id) {
        $builder = $this->db->table($this->table);
        $builder = $builder->select('*');
        $builder = $builder->where('id', $id);
        $builder = $builder->get();
        $builder = $builder->getFirstRow('array');
        //echo $this->db->getlastQuery();
        return $builder;
     }
    public function get_menu_by_id($id)
    {
       return $this->db->table($this->table)
                ->select('*')
                ->where('id', $id)
                ->get()
                ->getFirstRow('array');
                
    }
    public function gate_under_menu_list()
    {
        $sql = "SELECT * FROM tbl_menu_mstr WHERE parent_menu_mstr_id = ? AND status = ? AND menu_type = ?";
        $ql= $this->db->query($sql, [0, 1, 0]);
        $result =$ql->getResultArray();
        return $result;
    }
    public function MenuDeactivate($menu_mstr_id)
    {
        $sql = "UPDATE tbl_menu_mstr
                SET status=0
                where id=?";
       return $sql= $this->db->query($sql, [$menu_mstr_id]);
    }
    public function getMenuMstrListByUserTypeMstrId($user_type_mstr_id){
        $sql = "SELECT id, menu_name, parent_menu_mstr_id, menu_icon, url_path, menu_type, created_on FROM tbl_menu_mstr WHERE id IN (SELECT menu_mstr_id FROM tbl_menu_permission WHERE user_type_mstr_id='$user_type_mstr_id' AND status=1) AND parent_menu_mstr_id IN (0,-1) AND status=1 AND menu_type=0 ORDER BY order_no ASC";
        $q = $this->db->query($sql);        
        $result = $q->getResultArray();
        # echo $this->db->getLastQuery();
        return $result;
    }
    public function getMenuSubListByUserTypeMstrId($user_type_mstr_id, $parent_menu_mstr_id){
        $sql = "SELECT id, menu_name, parent_menu_mstr_id, menu_icon, url_path, menu_type, created_on FROM tbl_menu_mstr WHERE id IN (SELECT menu_mstr_id FROM tbl_menu_permission WHERE user_type_mstr_id='$user_type_mstr_id' AND status=1) AND parent_menu_mstr_id!=0 AND parent_menu_mstr_id='$parent_menu_mstr_id' AND status=1 AND menu_type=1 ORDER BY order_no ASC";
        $q = $this->db->query($sql);        
        $result = $q->getResultArray();
        //echo $this->db->getLastQuery();die;
        return $result;

    }
    public function getMenuLinkListByUserTypeMstrId($user_type_mstr_id, $parent_menu_mstr_id){
        $sql = "SELECT id, menu_name, url_path, menu_type, created_on FROM tbl_menu_mstr WHERE id IN (SELECT menu_mstr_id FROM tbl_menu_permission WHERE user_type_mstr_id='$user_type_mstr_id' AND status=1) AND parent_menu_mstr_id!=0 AND parent_menu_mstr_id='$parent_menu_mstr_id' AND status=1 AND menu_type=2 ORDER BY order_no ASC";
        $q = $this->db->query($sql);        
        $result = $q->getResultArray();
        //echo $this->db->getLastQuery();
        return $result;

    }

    public function getSubMenuByMenuMstrId ($input) {
        $sql = "SELECT id, menu_name FROM tbl_menu_mstr WHERE parent_menu_mstr_id = ? AND menu_type = ? AND status = ?";
        $queryResult = $this->db->query($sql, [$input['parent_menu_mstr_id'], 1, 1]);        
        $result = $queryResult->getResultArray();
        //echo $this->db->getLastQuery();
        return $result;
    }

}