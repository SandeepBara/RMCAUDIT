<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_application_type_mstr extends Model 
{
    protected $db;
    protected $table = 'tbl_application_type_mstr';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id', 'application_type','status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function trade_application_type_list($data)
    {   
      try{

            $builder = $this->db->table($this->table)
					->select('application_type')
					->where('id', $data)
					->get(); 
           return $builder->getFirstRow('array');
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    

}