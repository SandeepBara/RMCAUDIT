<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class tradeviewlicenceratemodel extends Model 
{
    protected $db;
    protected $table = 'view_licence_rate';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id', 'apllication_type_id', 'apllication_type', 'range_from', 'range_to', 'rate', 'effective_date', 'emp_details_id','status'];
    
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }    
    public function getlicencerateList(){        
         try
        {
            $builder = $this->db->table($this->table)
                    ->select('*')
                    ->where('status',1)
                    ->orderBy('id', 'desc')                   
                    ->get();
                   // echo $this->db->getLastQuery();
            return $result = $builder->getResultArray();
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    
}