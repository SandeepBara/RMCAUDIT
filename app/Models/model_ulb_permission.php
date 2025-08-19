<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_ulb_permission extends Model 
{
    protected $db;
    protected $table = 'tbl_ulb_permission';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id', 'ulb_mstr_id', 'emp_details_id', 'created_by_emp_details_id', 'created_on', 'status'];
    
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function getPermittedUlbByEmpDetailsId($input){
        try{
            $builder = $this->db->table($this->table);
            $builder->select('id, ulb_mstr_id');
            $builder->where('emp_details_id', $input['emp_details_id']);
            $builder->where('status', 1);
            $builder = $builder->get();
            return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function insertData($ulb_mstr_id,$created_by_emp_details_id,$created_on,$emp_details_id){
        try
        {
            $builder = $this->db->table($this->table)
                ->insert([
                    "ulb_mstr_id" => $ulb_mstr_id,
                    "created_by_emp_details_id" => $created_by_emp_details_id,
                    "created_on" => $created_on,
                    "emp_details_id" =>$emp_details_id   
                ]);
            return $insert_id = $this->db->insertId();
        }catch(Exception $e){
            echo $e->getMessage();
        }
       
    }
    public function gateUlbDataByEmpdetailsId($id)
    {
        try
        {
            $builder = $this->db->table($this->table)
                    ->select('*')
                    ->where('status',1)
                    ->where('emp_details_id',$id)
                    ->get();
            return $result = $builder->getResultArray();
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }
    public function setStatusZeroForUpdateRecord($id)
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
    public function updateUlbByEmpDetailsId($id,$ulb_mstr_id)
    {
        try
        {
            return $builder = $this->db->table($this->table)
                            ->where('emp_details_id',$id)
                            ->where('ulb_mstr_id',$ulb_mstr_id)
                            ->update([
                                 'status'=>1
                                 ]);
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }
    public function checkIsExists($ulb_mstr_id,$id)
    {
        try
        {
            $builder = $this->db->table($this->table)
                    ->select('id')
                    ->where('ulb_mstr_id',$ulb_mstr_id)
                    ->where('emp_details_id',$id)
                    ->get();
            return $result = $builder->getResultArray()[0];
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

}