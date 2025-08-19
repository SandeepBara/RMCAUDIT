<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class UserTypeModel extends Model 
{
    /*protected $table = 'tbl_user_type_mstr';

    protected $primaryKey = 'id';

    protected $allowedFields = ['user_type'];*/
    protected $db;



    public function __constructor(ConnectionInterface &$db)
    {
       
        $this->db = & $db; 

    }

    public function getAll()
    { 
       // $this->db1 = $this->load->database('db', TRUE);

        $sql="SELECT * FROM tbl_user_type_mstr";
        $q = $this->db->query($sql);        
        $result2 = $q->getResultArray();
        return $result2;
    }

    public function createNew(array $data)
    {
        $this->db->insert('tbl_user_type_mstr',$data);
    }
    public function updatedata($id, $data=array())
    {
        $this->db->update($id,$data);
    }
    public function getbyid($id)
    {
        $this->db->where('id',$id)->first();
    }

    public function deletebyid($id)
    {
        $this->db->where('id',$id)->delete();
    }

}