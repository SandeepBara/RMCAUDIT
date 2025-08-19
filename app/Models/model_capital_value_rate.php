<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_capital_value_rate extends Model 
{
    protected $table = 'tbl_capital_value_rate';
    protected $allowedFields = ['id','property_type','ward_no','road_type_mstr_id', 'usage_type', 'rate', 'effect_from', 'status'];
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function showCapitalRate() { // Capital Value Rate
        try {
            $sql = 'select ward_no, rate from tbl_capital_value_rate order by ward_no::int,id asc';
            $query = $this->db->query($sql);
            $result_list = $query->getResultArray();
            $result = [];
            foreach ($result_list as $element) {
                $result[$element['ward_no']][] = $element;
            }
           
            return $result;
            
        } catch(Exception $e) {
            return $e->getMessage();   
        }
    }
    public function showCapitalRateByWardNo($ward_no) { // Capital Value Rate
        try {
            $sql = 'select ward_no, rate from tbl_capital_value_rate where ward_no::int='.$ward_no.' order by id';
            $query = $this->db->query($sql);
            $result = $query->getResultArray();
        
           
            return $result;
            
        } catch(Exception $e) {
            return $e->getMessage();   
        }
    }
    public function showCapitalRateByWardNo24($ward_no) { // Capital Value Rate
        try {
            $sql = 'select ward_no, max_rate as rate from tbl_capital_value_rate_2024 where ward_no::int='.$ward_no.' order by id';
            $query = $this->db->query($sql);
            $result = $query->getResultArray();
            return $result;
        } catch(Exception $e) {
            return $e->getMessage();
        }
    }

    public function getCVR2022($property_type, $ward_no, $road_type_mstr_id, $usage_type) { // Capital Value Rate
        try {
            $builder = $this->db->table($this->table)
                        ->select('id, rate, max_rate')
                        ->where('property_type', $property_type)
                        ->where('ward_no', $ward_no)
                        ->where('road_type_mstr_id', $road_type_mstr_id)
                        ->where('usage_type', $usage_type)
                        ->where('effect_from', "2022-04-01")
                        ->where('status', 1)
                        ->get();
            //echo $this->db->getLastQuery();
            return $builder->getFirstRow("array");
        } catch(Exception $e) {
            return $e->getMessage();   
        }
    }
    public function getCVR2024($property_type, $ward_no, $road_type_mstr_id, $usage_type) { // Capital Value Rate 2024
        try {
            $builder = $this->db->table('tbl_capital_value_rate_2024')
                        ->select('id, rate, max_rate')
                        ->where('property_type', $property_type)
                        ->where('ward_no', $ward_no)
                        ->where('road_type_mstr_id', $road_type_mstr_id)
                        ->where('usage_type', $usage_type)
                        ->where('effect_from', "2024-04-01")
                        ->where('status', 1)
                        ->get();
            //echo $this->db->getLastQuery();
            return $builder->getFirstRow("array");
        } catch(Exception $e) {
            return $e->getMessage();
        }
    }

}