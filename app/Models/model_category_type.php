<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_category_type extends Model 
{
    protected $db;
    protected $table = 'tbl_category_type';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id', 'category_type', 'status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function category_type($data)
    {      
      try{

            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('id', $data)
                        ->get(); 
           return $builder->getFirstRow('array');
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    

}