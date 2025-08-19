<?php 
namespace App\Models\Property;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class PropPhysicalVerification extends Model{
    protected $db;
    protected $table = 'tbl_prop_physical_verifications';
    
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function store(array $data)
    {
        $this->db->table($this->table)
                ->insert($data);
                
                // echo $this->db->getLastQuery();
        $insert_id=$this->db->insertID();
        return $insert_id;
    }

    public function updateData(int $id,array $data){
        return $this->db->table($this->table)->where("id",$id)->update($data);
    }
}
