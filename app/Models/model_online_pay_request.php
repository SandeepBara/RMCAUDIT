<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_online_pay_request extends Model
{
	protected $db;
    protected $table = 'tbl_online_pay_request';
    protected $allowedFields = [];

    public function __construct(ConnectionInterface $db) {
        $this->db = $db;
    }
	
	public function pay_request($data) {
		$this->db->table($this->table)
					->insert($data);
		//echo $this->getLastQuery();
		return $this->db->insertID();
	}

    public function getRecordBYRefNo($input) {
		/* $builder=$this->db->table($this->table)
					->select("*")
					->where("biller_txn_reference_id", $input["billertxnReferenceId"])
					->where("bill_number", $input["txnReferenceId"])
					//->where("amount", $input["amount"])
					->get(); */
		//echo $this->getLastQuery();
		//return $builder->getFirstRow();
		$sql = "SELECT
					tbl_online_pay_request.*,
					tbl_prop_dtl.new_holding_no
				FROM tbl_online_pay_request
				INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_online_pay_request.prop_dtl_id
				WHERE
					tbl_online_pay_request.biller_txn_reference_id='".$input["billertxnReferenceId"]."'
					AND tbl_online_pay_request.payable_amt='".$input["amount"]."'";

		return $this->db->query($sql)->getFirstRow();
	}

	public function updateRecord($data, $pg_mas_id)
	{
		return $this->db->table($this->table)
					->where("id", $pg_mas_id)
					->Update($data);
			
		//echo $this->getLastQuery();
		//return $this->db->insertID();
	}
}
?>