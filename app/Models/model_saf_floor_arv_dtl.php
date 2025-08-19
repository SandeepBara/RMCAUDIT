<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_saf_floor_arv_dtl extends Model
{
	protected $db;
    protected $table = 'tbl_saf_floor_arv_dtl';
    protected $allowedFields = [];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

	public function insertData($input){
        try{        
            return $this->db->table($this->table)
                            ->insert($input);
            //echo $this->db->getLastQuery();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    
}
?>