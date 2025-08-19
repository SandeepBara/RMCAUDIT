<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_trade_transaction_fine_rebet_details extends Model
{
	protected $db;
    protected $table = 'tbl_transaction_fine_rebet_details';
    protected $allowedFields = [];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

	public function penalty_dtl($data){
		//print_r($data);
		$sql = "SELECT head_name, amount
			    FROM tbl_transaction_fine_rebet_details
			    where transaction_id=?
				";
				$ql= $this->db->query($sql, [$data]);
				//echo $this->db->getLastQuery();
				$result =$ql->getResultArray();
				return $result;
       
    }
	
	public function fine_rebet_details($data){
		$resultPnlt = $this->db->table('tbl_transaction_fine_rebet_details')->
			insert([
				  "transaction_id"=>$data['transaction_id'],
				  "head_name"=>$data['head_name'],
				  "amount"=>$data['amount'],
				  "value_add_minus"=>$data['value_add_minus'],
				  "created_on"=>$data["created_on"],
				  "status"=>1
				  ]);
			//echo $this->db->getLastQuery();
	}
    public function getRebate($data){
    	try{
            $builder = $this->db->table($this->table)
                      ->select('COALESCE(SUM(amount),0) as rebate')
                      ->where('date(created_on) >=',$data['from_date'])
                      ->where('date(created_on) <=',$data['to_date'])
                      ->where('upper(value_add_minus)',strtoupper('minus'))
                      ->where('status',1)
                      ->get();
                    //echo $this->db->getLastQuery();
            return $builder->getFirstRow("array");
           /*return  $builder['totalamount'];*/
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getRebateByTransactionId($transaction_id){
    	try{
    			$builder =$this->db->table($this->table)
    					  ->select('COALESCE(SUM(amount),0) as rebate')	
    					  ->where('transaction_id',$transaction_id)
    					  ->where('upper(value_add_minus)',strtoupper('minus'))
    					  ->where('status',1)
    					  ->get();
    					 //echo $this->db->getLastQuery();
    			$builder=$builder->getFirstRow("array");
    			return $builder['rebate'];
    	}catch(Exception $e){
    		echo $e->getMessage();
    	}
    }

	public function getfine_rebet_details($transaction_id,$data){
 		return $result=$this->db->table($this->table)
							->select("*")
							->where('transaction_id',$transaction_id)
							->where('head_name',$data)
							->where('status',1)
							->get()
							->getResultArray()['0'];
						  // echo $this->getLastQuery();exit;
	}
}
?>