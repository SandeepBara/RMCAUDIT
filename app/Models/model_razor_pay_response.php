<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_razor_pay_response extends Model
{
	protected $db;
    protected $table = 'tbl_razor_pay_response';
    protected $allowedFields = [];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

	
	
	public function pay_response($data)
	{
		$this->db->table('tbl_razor_pay_response')->
			Insert([
					"razorpay_payment_id"=> $data["razorpay_payment_id"],
					"prop_dtl_id"=> $data["prop_dtl_id"],
					"module"=> $data["module"],
					"payable_amt"=> $data["payable_amt"],
					"ip_address"=> $data["ip_address"],
					"merchant_id"=> $data["merchant_id"],
					"razorpay_order_id"=> $data["razorpay_order_id"],
					"razorpay_signature"=> $data["razorpay_signature"],
					"code"=> $data["code"],
					"description"=> $data["description"],
					"source"=> $data["source"],
					"reason"=> $data["reason"],
					"order_id"=> $data["order_id"],
					"payment_id"=> $data["payment_id"],
				]);
		//echo $this->db->getLastQuery();
		return $this->db->InsertID();
	}

	#-------------------Trade---------------------
	public function pay_responseTrade($data)
	{
		$this->db->table('tbl_razor_pay_response')->
			Insert([
					"razor_pay_request_id"=> $data["request_id"],
					"apply_licence_id"=> $data["apply_licence_id"],
					"merchant_id"=> $data["merchant_id"],
					"order_id"=> $data["order_id"],
					"payment_id"=> $data["razorpay_payment_id"],
					"error_code"=> $data["error_code"]??null,
					"error_desc"=> $data["error_desc"]??null,
					"error_source"=> $data["error_source"]??null,
					"error_step"=> $data["error_step"]??null,
					"error_reason"=> $data["error_reason"]??null,
					"amount"=> $data["amount"],
					"created_on"=> date('Y-m-d H:i:s'),
					"ip_address"=> $data["ip_address"]
				]);
		//echo $this->db->getLastQuery();
		return $this->db->InsertID();
	}
    

}
?>