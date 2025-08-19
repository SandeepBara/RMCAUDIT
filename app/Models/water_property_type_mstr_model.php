<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class water_property_type_mstr_model extends Model
{

    protected $table = 'tbl_property_type_mstr';
    protected $allowedFields = ['id','property_type','status'];

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}
   public function propertyDetails($property_type_id){
    try{
        $builder = $this->db->table($this->table)
                ->select('*')
                ->where('id',$property_type_id)
                ->where('status',1)
                ->get();
                //echo $this->getLastQuery();
        // return $builder->getResultArray()[0];
        return $builder->getFirstRow('array');
    }catch(Exception $e){
        echo $e->getMessage();
    }
   }
   public function getPropertyType($property_type_id){
     try
        {        
            $builder = $this->db->table($this->table)
                        ->select('property_type')
                        ->where('id',$property_type_id)
                        ->where('status',1)
                        ->get();
                      // echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
           return  $builder['property_type'];
        }catch(Exception $e){
            echo $e->getMessage();   
        }
   }
}