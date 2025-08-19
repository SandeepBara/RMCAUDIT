<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_view_ulb_permission extends Model 
{
    protected $db;
    protected $table = 'view_ulb_permission';

    protected $primaryKey = 'id';

    //protected $allowedFields = ['ulb_permission_id', 'ulb_mstr_id', 'emp_details_id', 'created_by_emp_details_id', 'created_on', 'ulb_name', , 'short_ulb_name', 'db_property', 'db_water', 'logo_path', 'watermark_path'];
    
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function getPermittedUlbByEmpDetailsId($input)
    {
        try
        {
            $builder = $this->db->table($this->table);
            $builder->select('ulb_permission_id, ulb_mstr_id, ulb_name, short_ulb_name');
            $builder->where('emp_details_id', $input['emp_details_id']);
            $builder->orderBy('ulb_mstr_id','ASC');
            $builder = $builder->get();
            //echo $this->db->getLastQuery();
            return $builder->getResultArray();
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }

    public function getPermittedUlb($id)
    {
        try{
            $builder = $this->db->table($this->table)
                    ->select('*')
                    ->where('emp_details_id',$id)
                    ->get();
            return $result = $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
}