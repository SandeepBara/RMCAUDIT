<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_bank_recancilation extends Model
{
	protected $db;
  protected $table = 'tbl_bank_recancilation';
  protected $allowedFields = ['id','created_on','emp_details_id','cancel_date','cheque_dtl_id','transaction_id','reason','amount','status'];
  public function __construct(ConnectionInterface $db){
    $this->db = $db;
  }
 /* public function insertData($input){
    try
    {
      $builder = $this->db->table($this->table)
            ->insert([
                "created_on" => $input['created_on'],
                "emp_details_id" => $input['emp_details_id'],
                "cheque_dtl_id" => $input['cheque_dtl_id'],
                "transaction_id" => $input['transaction_id'],
                "reason" => $input['reason'],
                "amount" => $input['amount'],
                "cancel_date" => $input['cancel_date'],
                "ward_mstr_id" =>$input['ward_mstr_id'],
                "related_id" =>$input['prop_dtl_id'],
                "prop_type" =>$input['prop_type']
            ]);
            //echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertId();
    }catch(Exception $e){
        echo $e->getMessage();
    }
  }*/
   
   public function insertData(array $data)
   {

      $result= $this->db->table($this->table)
                 ->insert($data);       
       // echo $this->getLastQuery();
        $insert_id=$this->db->insertID();
        return $insert_id;

   }

      public function bank_reCancel($input)
      {
        $sql1 = "SELECT tb1.reason,tb1.amount,tb2.cheque_no,tb2.cheque_date,tb2.bank_name,tb2.branch_name,tb1.cheque_dtl_id
        FROM tbl_bank_recancilation tb1
        LEFT JOIN tbl_cheque_details tb2 ON tb1.cheque_dtl_id = tb2.id
        where tb1.related_id=? AND tb1.status=1";
        $ql= $this->query($sql1, [$input]);
        $chequeDetails =$ql->getFirstRow('array');
        return $chequeDetails;
      }
   
   public function chequePaymentDone($input){
	  $this->db->table($this->table)
		 ->where('cheque_dtl_id', $input['bank_reCancel']['cheque_dtl_id'])
		 ->set([
		 'status' => 0,
		 'transaction_id' => $input['insertPayment']
		 ])
		 ->update();
   }
   public function getDetails($id){
    try{
        $builder = $this->db->table($this->table)
                 ->select('*')
                 ->where('cheque_dtl_id',$id)
                 ->where('status',1)
                 ->get();
          return $builder->getResultArray()[0];
    }catch(Exception $e){
      echo $e->getMessage();
    }
   }
   public function preventDouble($cheque_dtl_id){
      try{
          $builder = $this->db->table($this->table)
                    ->select('id')
                    ->where('cheque_dtl_id',$cheque_dtl_id)
                    ->where('status',1)
                    ->get();
            $builder = $builder->getFirstRow("array");
            return $builder['id'];
      }catch(Exception $e){
        echo $e->getMessage();
      }
    }
}
?>