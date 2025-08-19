<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_view_saf_floor_details extends Model 
{
    protected $db;
    protected $table = 'view_saf_floor_details';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id', 'saf_dtl_id', 'floor_mstr_id', 'floor_name', 'usage_type_mstr_id', 'usage_type', 'const_type_mstr_id', 'construction_type', 'occupancy_type_mstr_id', 'occupancy_name', 'builtup_area', 'date_from', 'date_upto', 'emp_details_id', 'created_on', 'status'];
    
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    

    public function getDataBySafDtlId($input){
        try{
            return $this->db->table($this->table)
                            ->select('id,floor_mstr_id, floor_name, usage_type_mstr_id, usage_type, const_type_mstr_id, construction_type, occupancy_type_mstr_id, occupancy_name, builtup_area, date_from, date_upto,carpet_area')
                            ->where('saf_dtl_id', $input['saf_dtl_id'])
                            ->where('status', 1)
                            ->orderBy('date_from', 'ASC')
                            ->get()
                            ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function getDataBySafDtlId_md5($saf_dtl_id){
        try{
            $data = $this->db->table($this->table)
                    ->select('id,floor_mstr_id, floor_name, usage_type_mstr_id, usage_type, const_type_mstr_id, construction_type, occupancy_type_mstr_id, occupancy_name, builtup_area, date_from, date_upto,carpet_area');
            if(is_numeric($saf_dtl_id))
            {
                $data= $data->where('saf_dtl_id', $saf_dtl_id);
            }
            else
            {
                $data=$data->where('md5(saf_dtl_id::text)', $saf_dtl_id);
            }
            $data=    $data->where('status', 1)
                ->orderBy('date_from', 'ASC')
                ->get()
                ->getResultArray();
            //    echo $this->db->getLastQuery();
            return $data;
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
   
}