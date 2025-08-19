<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_water_online_pay_response extends Model
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
		
		$sql = "SELECT
					tbl_transaction.id AS tran_id,
                    tbl_transaction.related_id ,
                    tbl_transaction.transaction_type ,
					tbl_transaction.transaction_date,
					tbl_transaction.transaction_no,
					tbl_online_pay_response.biller_txn_reference_id,
					tbl_online_pay_response.bill_number
				FROM tbl_online_pay_response
				INNER JOIN tbl_transaction ON tbl_transaction.id=tbl_online_pay_response.transaction_id
				WHERE
				tbl_online_pay_response.online_payment_id=".$online_payment_id;
		return $this->db->query($sql)->getFirstRow();
	}

	public function getTranNo($tran_id) {
		$sql = "SELECT transaction_no FROM tbl_transaction WHERE id=".$tran_id;
		return $this->db->query($sql)->getFirstRow();
	}

	public function getRefStatus($txnReferenceId,$payment_from='Demand Collection') {
        $column = "consumer.consumer_no as application_no";
        $join = "INNER JOIN tbl_consumer consumer ON consumer.id=tbl_online_pay_request.related_id 
                    AND tbl_online_pay_request.payment_from='Demand Collection' 
                    AND tbl_online_pay_request.status = 1 ";
        if($payment_from=="Connection")
        {
            $column = "consumer.application_no as application_no";
            $join = "INNER JOIN tbl_apply_water_connection consumer ON consumer.id=tbl_online_pay_request.related_id 
                    AND tbl_online_pay_request.payment_from='Connection' 
                    AND tbl_online_pay_request.status = 1 ";
        }
		$sql = "SELECT
					tbl_online_pay_request.*,
					tbl_online_pay_response.id AS online_pay_response_id,
                    $column,
					tbl_transaction.transaction_no
				FROM tbl_online_pay_request
				$join
				LEFT JOIN tbl_online_pay_response ON tbl_online_pay_response.online_payment_id=tbl_online_pay_request.id
				LEFT JOIN tbl_transaction ON tbl_transaction.id=tbl_online_pay_response.transaction_id
				WHERE
					tbl_online_pay_response.txn_reference_id='".$txnReferenceId."'";
        // print_var($sql);die;
		return $this->db->query($sql)->getFirstRow();
	}
}
?>