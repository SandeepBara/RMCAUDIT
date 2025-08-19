<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_fixed_arr_building_mstr extends Model
{
    protected $db;
    protected $table = 'tbl_fixed_arr_building_mstr';
    protected $allowedFields = ['id','road_type_mstr_id','const_type_mstr_id','rate'];

    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }

    

    public function gateAllData(){
        try{
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->get();
            return $builder->getResultArray();


        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

}
?>