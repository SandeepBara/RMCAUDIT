<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_prop_tax_print extends Model
{
    protected $db;
    protected $table = 'tbl_prop_tax_print';
    protected $allowedFields = [''];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function insertData(array $data){
        $this->db->table($this->table)
                            ->insert($data);
        //echo  $this->db->getLastQuery();                    
        return $this->db->insertID();
    }    
}
?>