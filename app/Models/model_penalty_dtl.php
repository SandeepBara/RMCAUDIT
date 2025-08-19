<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_penalty_dtl extends Model
{
    protected $table = 'tbl_penalty_dtl';
    protected $allowedFields = ['id', 'prop_dtl_id', 'penalty_amt', 'penalty_type','status','transaction_id'];
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function insertData(array $data)
    {
        return $this->db->table($this->table)
                        ->insert($data);
    }
	
	
	public function difference_Penalty($data)
    { 
        $sql = "SELECT penalty_amt
		FROM tbl_penalty_dtl
		where prop_dtl_id=? AND transaction_id=0";
        $ql= $this->db->query($sql, [$data]);
        $result =$ql->getFirstRow('array');
        return $result;
    }
	
	public function Updatedifference_Penalty($data)
    { 
		$this->db->table($this->table)
			 ->where('prop_dtl_id', $data['custm_id'])
			 ->where('transaction_id', 0)
			 ->set(['transaction_id' => $data['insertPayment']])
			 ->update();
	}

    public function updateSAFPenaltyStatus($data)
    {
        $this->db->table($this->table)
			 ->where('prop_dtl_id', $data['prop_dtl_id'])
			 ->where('transaction_id', $data['transaction_id'])
             ->where('module', 'Saf')
			 ->set(['status' => $data['status'], 'transaction_id'=> 0])
			 ->update();
    }
	
}
