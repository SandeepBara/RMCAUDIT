<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_view_apply_water_connection_deactivation extends Model
{
    protected $db;
    protected $table = 'view_apply_water_connection_deactivation';
    protected $allowedFields = ['id','apply_water_connection_id','remark','deactivation_date','emp_details_id','doc_path','ward_mstr_id','application_no','category','area_sqft','applicant_name','father_name','mobile_no','ward_no'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function getNewConnectionDeactivationList($data)
    {
        try{
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('deactivation_date >=',$data['from_date'])
                        ->where('deactivation_date <=',$data['to_date'])
                        ->where('ward_mstr_id',$data['ward_mstr_id'])
                        ->get();
                       // echo $this->db->getLastQuery();
           return $builder->getResultArray();
        }catch(Exception $e){
            echo $e->getMessage();   
        }
    }
    public function getAllNewConnectionDeactivationList($data){
        try{
            $builder = $this->db->table($this->table)
                      ->select('*')
                      ->where('deactivation_date >=',$data['from_date'])
                      ->where('deactivation_date <=',$data['to_date'])
                      ->get();
                      //echo $this->db->getLastQuery();
            return $builder->getResultArray();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
}
?> 