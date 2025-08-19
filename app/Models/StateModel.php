<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class statemodel extends Model 
{
    protected $db;
    protected $table = 'states';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id', 'name','status'];
    
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }    
    public function getstateList(){        
         try
        {
            $builder = $this->db->table($this->table)
                    ->select('*')
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

    public function getstateid($statename){        
         try
        {
            $builder = $this->db->table($this->table)
                    ->select('id')
                    ->where('name',$statename)
                    ->where('status',1)
                    ->orderBy('id', 'asc')                   
                    ->get();
                   // echo $this->db->getLastQuery();
            return $result = $builder->getFirstRow("array");
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    
}