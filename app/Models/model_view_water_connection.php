<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_view_water_connection extends Model 
{
    protected $db;
    protected $table = 'view_water_connection';
    
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function getDatabyid($id)
    {
    	$result=$this->db->table($this->table)
    				->select('*')
    				//->where('status',1)
                    ->where('md5(id::text)',$id)
    				->get()
    				->getFirstRow("array");

                    //echo $this->getLastQuery();
    	return $result;

    }
}