<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_view_trade_transaction extends Model 
{
    protected $db;
    protected $table = 'view_transaction';
    
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }


    public function tradeTransactionDetails($data)
    {
        $sql="select transaction_date,payment_mode,paid_amount as payable_amount from tbl_transaction where transaction_date between '".$data['from_date']."' and '".$data['to_date']."' and status in(1,2)" ;
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        return $result;

    }

    public function tradeTransactionDetailsbyEmpId($data)
    {
        $sql="select transaction_date,payment_mode,paid_amount as payable_amount from tbl_transaction where transaction_date between '".$data['from_date']."' and '".$data['to_date']."' and status in(1,2) and emp_details_id=".$data['tran_by_emp_details_id'] ;
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        return $result;
        
    }

    public function tradeTransactionDetailsbyEmpIdTransDate($cash_verify_id)
    {
        $sql="select tbl_transaction.*,cheque_no,cheque_date,bank_name,branch_name from tbl_transaction left  join tbl_cheque_details on tbl_cheque_details.transaction_id=tbl_transaction.id where cash_verify_id = ".$cash_verify_id;
        
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();

        return $result;

    }

     public function tradeNotVerified($cash_verify_id)
    {
        $sql="select * from tbl_transaction join 

            (
             
                SELECT tbl_notification.id
               FROM dblink('host=".getenv('db.pgsql.hname')." user=".getenv('db.pgsql.uname')." password=".getenv('db.pgsql.pass')." dbname=db_system'::text, 
                'SELECT tbl_notification.id FROM  tbl_notification 
            where tbl_notification.status=1'::text) tbl_notification
                (id integer)
                ) as notif on notif.id=tbl_transaction.notification_id
       where cash_verify_id= ".$cash_verify_id;
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        // echo $this->getLastQuery();

        return $result;
        
    }
    public function tradeTotalAmount($data)
    {
        $sql="select coalesce(sum(paid_amount),0) as payable_amount from tbl_transaction where transaction_date between '".$data['from_date']."' and '".$data['to_date']."' and status in(1,2)" ;
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        return $result['payable_amount'];

    }
    public function tradeTotalAmountbyEmpId($data)
    {
        $sql="select coalesce(sum(paid_amount),0) as payable_amount from tbl_transaction where transaction_date between '".$data['from_date']."' and '".$data['to_date']."' and status in(1,2) and emp_details_id=".$data['tran_by_emp_details_id'] ;
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        //echo $this->getLastQuery();
        return $result['payable_amount'];

    }

    public function getAllTransactionByDate($from_date,$to_date)
    {
        try{        
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('tran_date >=',$from_date)
                        ->where('tran_date <=',$to_date)
                        ->whereIn('status',[1,2])
                        ->get();
                        //echo $this->db->getLastQuery();
           return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function calculateSumByTransactionEmpDetailsId($emp_id,$from_date,$to_date)
    {
        try{        
             $builder = $this->db->table($this->table)
                        ->select('SUM(paid_amount) as total')
                        ->where('transaction_date >=',$from_date)
                        ->where('transaction_date <=',$to_date)
                        ->where('emp_details_id',$emp_id)
                        ->whereIn('status',[1,2])
                        ->get();
                       // echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
           return  $builder['total'];
        }catch(Exception $e){
            echo $e->getMessage();   
        }
    }
    
    public function getAllTransactionByEmpId($emp_id,$from_date,$to_date)
    {
        try{        
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('emp_details_id',$emp_id)
                        ->where('transaction_date >=',$from_date)
                        ->where('transaction_date <=',$to_date)
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