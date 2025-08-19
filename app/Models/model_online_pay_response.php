<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_online_pay_response extends Model
{
	protected $db;
    protected $table = 'tbl_online_pay_response';
    protected $allowedFields = [];

    public function __construct(ConnectionInterface $db) {
        $this->db = $db;
    }
	
	public function pay_response($data) {
		$this->db->table($this->table)
					->insert($data);
		$this->db->getLastQuery();
		return $this->db->insertID();
	}

	public function getRecordBYPayId($online_payment_id) {
		//return false;
		/* $builder=$this->db->table($this->table)
					->select("*")
					->where("online_payment_id", $online_payment_id)
					//->where("amount", $input["amount"])
					->get(); */
		//echo $this->getLastQuery();
		$sql = "SELECT
					tbl_transaction.id AS tran_id,
					tbl_transaction.tran_date,
					tbl_transaction.tran_no,
					tbl_online_pay_response.biller_txn_reference_id,
					tbl_online_pay_response.bill_number
				FROM tbl_online_pay_response
				INNER JOIN tbl_transaction ON tbl_transaction.id=tbl_online_pay_response.transaction_id
				WHERE
				tbl_online_pay_response.online_payment_id=".$online_payment_id;
		return $this->db->query($sql)->getFirstRow();
	}

	public function getTranNo($tran_id) {
		$sql = "SELECT tran_no FROM tbl_transaction WHERE id=".$tran_id;
		return $this->db->query($sql)->getFirstRow();
	}

	public function getRefStatus($txnReferenceId) {
		$sql = "SELECT
					tbl_online_pay_request.*,
					tbl_online_pay_response.id AS online_pay_response_id,
					tbl_prop_dtl.new_holding_no,
					tbl_transaction.tran_no
				FROM tbl_online_pay_request
				INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_online_pay_request.prop_dtl_id
				LEFT JOIN tbl_online_pay_response ON tbl_online_pay_response.online_payment_id=tbl_online_pay_request.id
				LEFT JOIN tbl_transaction ON tbl_transaction.id=tbl_online_pay_response.transaction_id
				WHERE
					tbl_online_pay_response.txn_reference_id='".$txnReferenceId."'";
		return $this->db->query($sql)->getFirstRow();
	}
}
?>