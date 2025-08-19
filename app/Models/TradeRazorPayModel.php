<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class TradeRazorPayModel extends Model
{

    protected $table = 'tbl_razor_pay_request';

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}

    public function insertData($data)
    {
        $result= $this->db->table($this->table)
                 ->insert($data);       
        //echo $this->getLastQuery();
        $insert_id=$this->db->insertID();
        return $insert_id;
    }

    public function getData($id)
    {
        $result=$this->db->table($this->table)
                    ->select('*')
                    //->where('status',1)
                    ->where('md5(id::text)', $id)
                    ->get()
                    ->getFirstRow("array");
                    //echo $this->getLastQuery();
        return $result;
    }

    public function UpdateRazorPayTable($data)
    {
        $this->db->table($this->table)
                        ->where('id', $data['pg_mas_id'])
                        ->update([
                                'razorpay_payment_id'=> $data['razorpay_payment_id'],
                                'razorpay_order_id'=> $data['razorpay_order_id'],
                                'razorpay_signature'=> $data['razorpay_signature'] ?? NULL,
                                'error_reason'=> $data['error_reason'] ?? NULL,
                                'status'=> $data['status']
                                ]);

        $pg_request = $this->getData(md5($data['pg_mas_id']));

        $param=[
                    "razor_pay_request_id"=> $data['pg_mas_id'],
                    "apply_licence_id"=> $pg_request["apply_licence_id"],
                    "merchant_id"=> $pg_request["merchant_id"],
                    "order_id"=> $data["razorpay_order_id"],
                    "payment_id"=> $data["razorpay_payment_id"],
                    "error_code"=> $data["error_code"] ?? NULL,
                    "error_desc"=> $data["error_desc"] ?? NULL,
                    "error_source"=> $data["error_source"] ?? NULL,
                    "error_step"=> $data["error_step"] ?? NULL,
                    "error_reason"=> $data["error_reason"] ?? NULL,
                    "amount"=> $pg_request["amount"],
                    "created_on"=> date('Y-m-d H:i:s'),
                    "ip_address"=> $_SERVER['REMOTE_ADDR'],
                ];
        //print_r($pg_request);
        //print_r($param);
        $this->db->table('tbl_razor_pay_response')->insert($param);  
        return $this->db->insertID();
    }
}