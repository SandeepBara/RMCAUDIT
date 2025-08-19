<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_view_saf_dtl_demand extends Model
{
    protected $db;
    protected $table = 'view_saf_dtl_demand';
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function getDemandDetails($ward_mstr_id)
    {
       try{        
            $builder = $this->db->table($this->table)
                    ->select('SUM(amount) as total_demand')
                    ->where('ward_mstr_id',$ward_mstr_id)
                    ->get();
                       //echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
           return  $builder['total_demand'];
        }catch(Exception $e){
            echo $e->getMessage();   
        }
    }

    public function getSumDemandBySAFDtlIdFyIdQtr($input) {
        try{
            $builder = $this->db->table($this->table)
                                ->select('COALESCE(SUM(amount), 0) AS amount')
                                ->where('saf_dtl_id', $input['saf_dtl_id'])
                                ->where('fy_mstr_id', $input['fy_mstr_id'])
                                ->where('qtr', $input['qtr'])
                                ->where('paid_status', 1)
                                ->where('status', 1)
                                ->get();
            //echo $this->db->getLastQuery();
            return $builder->getFirstRow("array");
        }catch(Exception $e){
            return false;   
        }
    }

    public function checkDemandIsExistBySafDtlIdFyIdQtr($input) {
        try{
            $builder = $this->db->table($this->table)
                                ->select('saf_dtl_id')
                                ->where('saf_dtl_id', $input['saf_dtl_id'])
                                ->where('fy_mstr_id', $input['fy_mstr_id'])
                                ->where('qtr', $input['qtr'])
                                ->whereIn('paid_status', [0, 1])
                                ->where('status', 1)
                                ->get();
            //echo $this->db->getLastQuery()."<br />";
            return $builder->getFirstRow("array");
            
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
     public function getAllDemand($from_date,$to_date)
    {
       try{        
            $builder = $this->db->table($this->table)
                    ->select('SUM(amount) as total_demand')
                    ->where('created_on >=',$from_date)
                    ->where('created_on <=',$to_date)
                    ->get();
                       //echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
           return  $builder['total_demand'];
        }catch(Exception $e){
            echo $e->getMessage();   
        }
    }
}
?> 
