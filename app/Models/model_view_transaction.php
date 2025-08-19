<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_view_transaction extends Model 
{
    protected $db;
    protected $table = 'view_transaction';
    
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function getAllTransactionByDate($from_date,$to_date)
    {
        try{        
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('tran_date >=',$from_date)
                        ->where('tran_date <=',$to_date)
                        ->whereIn('status',[1,2])
                        ->orderBY('id','DESC')
                        ->get();
                        //echo $this->db->getLastQuery();
           return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function getAllTransactionByEmpId($emp_id,$from_date,$to_date)
    {
        try{        
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('tran_by_emp_details_id',$emp_id)
                        ->where('tran_date >=',$from_date)
                        ->where('tran_date <=',$to_date)
                        ->whereIn('status',[1,2])
                        ->get();
                        //echo $this->getLastQuery();
           return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function getAllTransactionByDateAndTransactionMode($from_date,$to_date,$tran_mode_mstr_id)
    {
        try{        
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('tran_date >=',$from_date)
                        ->where('tran_date <=',$to_date)
                        ->where('tran_mode_mstr_id',$tran_mode_mstr_id)
                        ->whereIn('status',[1,2])
                        ->get();
                        //echo $this->getLastQuery();
           return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function getAllTransactionByDateForSafAndProperty($from_date,$to_date,$tran_type)
    {
        try{        
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('tran_date >=',$from_date)
                        ->where('tran_date <=',$to_date)
                        ->where('tran_type',$tran_type)
                        ->whereIn('status',[1,2])
                        ->orderBy('id'.'DESC')
                        ->get();
                        //echo $this->getLastQuery();
           return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function getAllPropTypeTransactionByDateForTC($from_date,$to_date,$tran_type,$tran_by_emp_details_id)
    {
        try{        
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('tran_date >=',$from_date)
                        ->where('tran_date <=',$to_date)
                        ->where('tran_type',$tran_type)
                        ->whereIn('status',[1,2])
                        ->where('tran_by_emp_details_id',$tran_by_emp_details_id)
                        ->get();
                        //echo $this->getLastQuery();
           return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function getAllWardwiseTransactionByDateForTC($from_date,$to_date,$ward_id,$tran_by_emp_details_id)
    {
        try{        
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('tran_date >=',$from_date)
                        ->where('tran_date <=',$to_date)
                        ->where('ward_id',$ward_id)
                        ->whereIn('status',[1,2])
                        ->where('tran_by_emp_details_id',$tran_by_emp_details_id)
                        ->get();
                        //echo $this->getLastQuery();
           return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function getDailyCollectionByTaxCollector($data){
        try{
            $builder = $this->db->table($this->table)
                      ->select('*')
                      ->where('tran_date>=',$data['from_date'])
                      ->where('tran_date<=',$data['to_date'])
                      ->whereIn('ward_id',$data['wardPermission'])
                      ->where('tran_by_emp_details_id',$data['id'])
                      ->whereIn('status',[1,2])
                      ->orderBy('ward_id,tran_date','ASC')
                      ->get();
                      //echo $this->db->getLastQuery();
            return $builder->getResultArray();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
}