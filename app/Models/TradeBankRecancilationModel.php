<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class TradeBankRecancilationModel extends Model
{
	protected $db;
    protected $table = 'tbl_bank_recancilation';
    protected $allowedFields = ['id','trnsaction_id','related_id','cheque_dtl_id','reason','amount','amount_receive_date','emp_details_id','created_on','status','type'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function insertTradeDataCheque($input){
        try
        {
          $builder = $this->db->table($this->table)
                ->insert([
                    "transaction_id" =>0,
                    "type" => $input['type'],
                    "related_id" =>$input['related_id'],
                    "cheque_dtl_id" => $input['cheque_dtl_id'],
                    "reason" => $input['reason'],
                    "amount" => $input['amount'],
                    "amount_receive_date" => $input['amount_receive_date'],
                    "emp_details_id" => $input['emp_details_id'],
                    "created_on" => $input['created_on'],
                    "type" => $input['type']
                ]);
               //echo $this->db->getLastQuery();
            return $insert_id = $this->db->insertId();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function insertData(array $data)
    {

      $result= $this->db->table($this->table)
                 ->insert($data);       
       // echo $this->getLastQuery();
        $insert_id=$this->db->insertID();
        return $insert_id;

    }
    public function getBankRecancilationtDetails($cheque_dtl_id){
        try{
            $builder = $this->db->table($this->table)
                      ->select('*')
                      ->where('cheque_dtl_id',$cheque_dtl_id)
                      ->where('status',1)
                      ->get();
            return $builder->getResultArray()[0];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getChequBounceCharge($related_id){
        try{
            $builder = $this->db->table($this->table)
                      ->select('COALESCE(SUM(amount),0) as amount')
                      ->where('md5(related_id::text)',$related_id)
                      ->where('status',1)
                      ->get();
                     //echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
           return  $builder['amount'];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function updateBankRecancilation($related_id,$transaction_id){
      try{
            $builder = $this->db->table($this->table)
                          ->where('md5(related_id::text)',$related_id)
                          ->update([
                                  "transaction_id"=>$transaction_id
                                  ]);
                          //echo $this->db->getLastQuery();
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
public function getChequeDetails($related_id){
    try{
      $builder = $this->db->table($this->table)
                ->select('*')
                ->where('md5(related_id::text)',$related_id)
                ->where('status',1)
                ->get();
      return $builder->getResultArray()[0];
    }catch(Exception $e){
      echo $e->getMessage();
    }
  }
}
?>