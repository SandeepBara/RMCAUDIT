<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class districtmodel extends Model 
{
    protected $db;
    protected $table = 'districts';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id', 'state_id', 'name','status'];
    
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }    
    public function getdistrictbystateid($state_id){        
         try
        {
            $builder = $this->db->table($this->table)
                    ->select('id,name')
                    ->where('state_id',$state_id)
                    ->where('status',1)
                    ->orderBy('id', 'asc')                   
                    ->get();
                    //echo $this->db->getLastQuery();
            return $result = $builder->getResultArray();
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    
}