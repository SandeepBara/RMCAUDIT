<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_trade_online_pay_response extends Model
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

	public function getRecordBYPayId($online_payment_id) 
    {
		
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

	public function getRefStatus($txnReferenceId) 
    {
		$sql = "SELECT
					tbl_online_pay_request.*,
					tbl_online_pay_response.id AS online_pay_response_id,
                    tbl_apply_licence.application_no,
					tbl_transaction.transaction_no
				FROM tbl_online_pay_request
				INNER JOIN tbl_apply_licence ON tbl_apply_licence.id=tbl_online_pay_request.apply_licence_id 
                INNER JOIN tbl_application_type_mstr ON tbl_application_type_mstr.application_type = tbl_online_pay_request.payment_from 
                    AND tbl_application_type_mstr.status = 1
				LEFT JOIN tbl_online_pay_response ON tbl_online_pay_response.online_payment_id=tbl_online_pay_request.id
				LEFT JOIN tbl_transaction ON tbl_transaction.id=tbl_online_pay_response.transaction_id
				WHERE
					tbl_online_pay_response.txn_reference_id='".$txnReferenceId."'
                    AND tbl_online_pay_request.status = 1 ";
        // print_var($sql);die;
		return $this->db->query($sql)->getFirstRow();
	}
}
?>