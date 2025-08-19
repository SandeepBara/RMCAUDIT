<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_apartment_details extends Model
{
	protected $db;
    protected $table = 'tbl_apartment_details';
    protected $allowedFields = [''];

	public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function getApartmentDtl() {
        try{
            $builder = $this->db->table($this->table)
                     ->select('*')
                     ->where('status',1)
                     ->get();
            return $builder->getResultArray();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    public function getApartmentDtlById($id) {
        try{
            $builder = $this->db->table($this->table)
                     ->select('*')
                     ->where('status', 1)
                     ->where('id', $id)
                     ->get();
            return $builder->getFirstRow("array");
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    
}

?>
