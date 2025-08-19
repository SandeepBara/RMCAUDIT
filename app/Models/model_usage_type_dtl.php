<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_usage_type_dtl extends Model 
{
    protected $db;
    protected $table = 'tbl_usage_type_dtl';
    protected $allowedFields = ['id','usage_type_mstr_id','mult_factor','date_of_effect','status'];
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function insertData(array $data){
        $builder = $this->db->table($this->table)
                            ->insert($data);
        return $insert_id = $this->db->insertID();
    }
    public function checkdata($usage_type_mstr_id)
    {
        try{
            return $this->db->table($this->table)
                        ->select('id, usage_type_mstr_id')
                        ->where('status', 1)
                        ->where('usage_type_mstr_id',$usage_type_mstr_id)
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function getdatabyid($id)
    {
        try{
        
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('status',1)
                        ->where('id',$id)
                        ->get();
           return $builder->getResultArray()[0];

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function checkupdatedata($id,$usage_type_mstr_id)
    {
        try{
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('usage_type_mstr_id', $usage_type_mstr_id);
        $builder->where('id!=', $id);
        $builder->where('status', 1);
        $builder = $builder->get();
        //echo $this->db->getLastQuery();
        $builder = $builder->getResultArray();
        return $builder[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function updatedataById($input)
    {
        return $builder = $this->db->table($this->table)
                            ->where('id', $input['id'])
                            ->update([
                                    'usage_type_mstr_id'=>$input['usage_type_mstr_id'],
                                    'mult_factor' =>$input['mult_factor']
                                    ]);
    }
     public function deleteUsageTypeDetail($id){
        return $builder = $this->db->table($this->table)
                            ->where('id',$id)
                            ->update([
                                    'status'=>0
                                    ]);

    }
    public function getUsageTypeMultFact($input){
        return $this->db->table($this->table)
                    ->select('id, mult_factor, cv_mult_factor')
                    ->where('usage_type_mstr_id', $input['usage_type_mstr_id'])
                    ->where('status', 1)
                    ->get()
                    ->getResultArray()[0];
        //->where('date_of_effect', $input['date_of_effect'])
        //echo $this->db->getLastQuery();
    }   
    
}
