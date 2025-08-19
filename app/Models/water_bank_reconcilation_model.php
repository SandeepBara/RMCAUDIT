<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class water_bank_reconcilation_model extends Model
{

    protected $table = 'tbl_bank_reconcilation';
    protected $allowedFields = ['id','consumer_type','transaction_id','related_id','cheque_dtl_id','reason','amount','amount_receive_date','emp_details_id','created_on','status'];

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}
    public function insertData(array $data)
    {

        $result= $this->db->table($this->table)
                 ->insert($data);       
       // echo $this->getLastQuery();
        $insert_id=$this->db->insertID();
        return $insert_id;

    }
    public function insertDataCheque($input){
        try
        {
          $builder = $this->db->table($this->table)
                ->insert([
                    "transaction_id" => $input['transaction_id'],
                    "consumer_type" => $input['consumer_type'],
                    "related_id" =>$input['related_id'],
                    "cheque_dtl_id" => $input['cheque_dtl_id'],
                    "reason" => $input['reason'],
                    "amount" => $input['amount'],
                    "amount_receive_date" => $input['amount_receive_date'],
                    "emp_details_id" => $input['emp_details_id'],
                    "created_on" => $input['created_on']
                ]);
               //echo $this->db->getLastQuery();
            return $insert_id = $this->db->insertId();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
   public function geBankRecancilationtDetails($cheque_dtl_id){
    try{
        $builder = $this->db->table($this->table)
                ->select('*')
                ->where('cheque_dtl_id',$cheque_dtl_id)
                ->where('status',1)
                ->get();
                //echo $this->db->getLastQuery();
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
