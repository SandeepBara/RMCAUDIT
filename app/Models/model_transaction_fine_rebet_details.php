<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_transaction_fine_rebet_details extends Model
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
				  "transaction_id"=>$data['insertPayment'],
				  "head_name"=>$data['head_name'],
				  "amount"=>$data['fine_rebet_amount'],
				  "value_add_minus"=>$data['add_minus'],
				  "created_on"=>$data["date"],
				  "status"=>1
				  ]);
	}
    

}
?>