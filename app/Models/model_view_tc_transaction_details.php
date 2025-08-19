<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_view_tc_transaction_details extends Model
{
	protected $db;
    protected $table = 'view_tc_transaction_details';
    protected $allowedFields = ['transaction_id','prop_dtl_id','tran_date','tran_no','tran_mode_mstr_id','payable_amt','tran_by_emp_details_id',  'transaction_mode','cheque_no','cheque_date','bank_name','branch_name','tran_by_emp_details_name'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

	public function tc_collection($data){
        
        $sql="select tran_by_emp_details_id,tran_by_emp_details_name,sum(payable_amt) as collection_amount from view_tc_transaction_details where 
         tran_date='".$data['date_from']."' and verify_status is NULL and  md5(tran_by_emp_details_id::text)='".$data['id']."' group by tran_by_emp_details_name,tran_by_emp_details_id";

        $run=$this->query($sql);
        $result=$run->getResultArray();
    // echo $this->getLastQuery();
        return $result;
    }

    public function tc_collection_by_cash($data){


        try{        
            $builder = $this->db->table($this->table)
                        ->select('SUM(payable_amt) as cash_amount')
                        ->where('transaction_mode', 'CASH')
                        ->where('tran_date=', $data['date_from'])
                        ->where('md5(tran_by_emp_details_id::text)', $data['id'])
                        ->where('verify_status',NULL)
                        ->groupBy('tran_by_emp_details_id')
                        ->groupBy('tran_by_emp_details_name')
                        ->get();
                       //echo $this->db->getLastQuery();
                        
       $rw = $builder->getFirstRow('array');
        return $rw["cash_amount"];

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
     public function tc_collection_by_cheque($data){

            try{        
           
                    $sql="select * from view_tc_transaction_details where transaction_mode='CHEQUE' and tran_date = '".$data['date_from']."' and md5(tran_by_emp_details_id::text)='".$data['id']."' and verify_status is NULL";
                   $rw= $this->query($sql);
                  //  echo $this->getLastQuery();
                    $result =$rw->getResultArray();      

                return $result;
            }catch(Exception $e){
                return $e->getMessage();   
            }
        }

  
	 public function tc_collection_by_dd($data){

            try{        
           
                     $sql="select * from view_tc_transaction_details where transaction_mode='DD' and tran_date = '".$data['date_from']."' and md5(tran_by_emp_details_id::text)='".$data['id']."' and verify_status is NULL ";
                   $rw= $this->query($sql);
                  //  echo $this->getLastQuery();
                    $result =$rw->getResultArray();      

                return $result;
            }catch(Exception $e){
                return $e->getMessage();   
            }

        }
}
?>