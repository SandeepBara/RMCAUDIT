<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_ward_permission extends Model 
{
    protected $db;
    protected $table = 'tbl_ward_permission';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id', 'ward_mstr_id','emp_details_id','created_by_emp_details_id','created_on','status'];
    
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function insertData($input){
        try
        {
            $builder = $this->db->table($this->table)
                ->insert([
                    "ward_mstr_id" => $input['ward_mstr_id'],
                    "created_by_emp_details_id" => $input['created_by_emp_details_id'],
                    "created_on" => $input['created_on'],
                    "emp_details_id" => $input['emp_details_id']   
                ]);
                //echo $this->db->getLastQuery();
            return $insert_id = $this->db->insertId();
        }catch(Exception $e){
            echo $e->getMessage();
        }
       
    }
    public function gateWardDataByEmpdetailsId($id)
    {
        try
        {
            $builder = $this->db->table($this->table)
                    ->select('*')
                    ->where('status',1)
                    ->where('emp_details_id',$id)
                    ->get();
					//echo $this->db->getLastQuery();
            return $builder->getResultArray();
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function getWardDataByEmpdetailsId($id)
    {
        try
        {
            $builder = $this->db->table($this->table)
                    ->select("string_agg(ward_mstr_id::varchar,',') as ward_mstr_id")
                    ->where('status',1)
                    ->where('emp_details_id',$id)
                    ->groupBy('emp_details_id')
                    ->get();
                   // echo $this->db->getLastQuery();
            return $result = $builder->getFirstRow('array');
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }
    public function setWardPermissionStatusZero($id)
    {
        try
        {
            return $builder = $this->db->table($this->table)
                            ->where('emp_details_id',$id)
                            ->update([
                                 'status'=>0
                                 ]);
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }
    public function checkIsExists($ward_mstr_id,$id)
    {
        try
        {
            $builder = $this->db->table($this->table)
                    ->select('id')
                    ->where('ward_mstr_id',$ward_mstr_id)
                    ->where('emp_details_id',$id)
                    ->get();
            return $result = $builder->getFirstRow('array');
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }
    public function updateWardByEmpDetailsId($id,$ward_mstr_id)
    {
        try
        {
            return $builder = $this->db->table($this->table)
                            ->where('emp_details_id',$id)
                            ->where('ward_mstr_id',$ward_mstr_id)
                            ->update([
                                 'status'=>1
                                 ]);
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function getEmpListByWardPermissionAndUlb($ward_mstr_id, $ulb_mstr_id) {
        try {
            $sql = "SELECT 
                        tbl_emp_details.id,
                        CONCAT(tbl_emp_details.emp_name, ' ', tbl_emp_details.middle_name, ' ', tbl_emp_details.last_name, ' (', tbl_user_type_mstr.user_type, ')') AS emp_name,
                        tbl_emp_details.status
                    FROM tbl_ward_permission 
                    INNER JOIN tbl_emp_details ON tbl_emp_details.id=tbl_ward_permission.emp_details_id
                    INNER JOIN tbl_user_type_mstr ON tbl_user_type_mstr.id=tbl_emp_details.user_type_mstr_id
                    INNER JOIN tbl_ulb_permission ON tbl_ulb_permission.emp_details_id=tbl_ward_permission.emp_details_id
                    WHERE tbl_ward_permission.ward_mstr_id=".$ward_mstr_id." AND tbl_ulb_permission.ulb_mstr_id=".$ulb_mstr_id. " AND tbl_user_type_mstr.id IN (4,5,8)";
            $builder = $this->db->query($sql);
            return $builder->getResultArray();
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }
	
	public function getWardList($id)
    {
        try
        {
            $Session = Session();
            $ulb_mstr = $Session->get("ulb_dtl");
            $ulb_mstr_id = $ulb_mstr["ulb_mstr_id"];   
            
           $sql = "SELECT tbl_ward_mstr.id, tbl_ward_mstr.ward_no
                    FROM tbl_ward_permission 
                    INNER JOIN tbl_ward_mstr ON tbl_ward_mstr.id=tbl_ward_permission.ward_mstr_id
                    WHERE tbl_ward_mstr.ulb_mstr_id=$ulb_mstr_id and tbl_ward_permission.emp_details_id=".$id." AND tbl_ward_permission.status=1 
                    order by (substring(tbl_ward_mstr.ward_no, '^[0-9]+'))::int";
            $builder = $this->db->query($sql);
            return $builder->getResultArray();
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }
	
    
}