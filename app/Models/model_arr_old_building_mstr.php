<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_arr_old_building_mstr extends Model 
{
    protected $table = 'tbl_arr_old_building_mstr';
    protected $allowedFields = ['id', 'zone_mstr_id', 'usage_type_mstr_id', 'const_type_mstr_id', 'rate', 'date_of_effect'];
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function getMRRCalRate($input){
        try{
            return $this->db->table($this->table)
                        ->select('id, rate')
                        ->where('zone_mstr_id', $input['zone_mstr_id'])
                        ->where('usage_type_mstr_id', $input['usage_type_mstr_id'])
                        ->where('const_type_mstr_id', $input['const_type_mstr_id'])
                        ->where('status', 1)
                        ->get()
                        ->getFirstRow('array');
            //echo $this->db->getLastQuery();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function getJoinOldRuleRateByRoadConsType($input){
        try{
            return $this->db->table("tbl_arr_old_building_mstr")
                        ->select('tbl_arr_old_building_mstr.zone_mstr_id, 
                                tbl_arr_old_building_mstr.rate AS rate,
                                tbl_const_type_mstr.construction_type AS construction_type'  
                        )
                        ->join('tbl_const_type_mstr', 'tbl_const_type_mstr.id=tbl_arr_old_building_mstr.const_type_mstr_id')
                        ->where('tbl_arr_old_building_mstr.usage_type_mstr_id', $input['usage_type_mstr_id'])
                        ->where('tbl_arr_old_building_mstr.status', 1)
                        ->where('tbl_const_type_mstr.status', 1)
                        ->orderBy('tbl_arr_old_building_mstr.zone_mstr_id, tbl_const_type_mstr.id', 'ASC')
                        ->get()
                        ->getResultArray();
           //echo  $this->db->getLastQuery();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
}
