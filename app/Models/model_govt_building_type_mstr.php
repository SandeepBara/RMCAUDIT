<?php
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_govt_building_type_mstr extends Model
{
	protected $db;
    protected $table = 'tbl_govt_building_type_master';
    protected $allowedFields = ['id','building_type'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function getGovtBuilTypeList(){
        try{
            $builder = $this->db->table($this->table)
                        ->select('id, building_type')
                        ->get();
            return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
}
?>
