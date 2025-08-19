<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_razor_pay_request extends Model
{
	protected $db;
    protected $table = 'tbl_razor_pay_request';
    protected $allowedFields = [];

    public function __construct(ConnectionInterface $db)
	{
        $this->db = $db;
    }

	
	public function pay_request($data)
	{
		$this->db->table('tbl_razor_pay_request')->
			Insert([
					"prop_dtl_id"=> $data["prop_dtl_id"],
					"module"=> $data["module"],
					"merchant_id"=> $data["merchant_id"],
					"from_fy_mstr_id"=> $data["from_fy_mstr_id"],
					"from_fy"=> $data["from_fy"],
					"from_qtr"=> $data["from_qtr"],
					"upto_fy_mstr_id"=> $data["upto_fy_mstr_id"],
					"upto_fy"=> $data["upto_fy"],
					"upto_qtr"=> $data["upto_qtr"],
					"demand_amt"=> $data["demand_amt"],
					"penalty_amt"=> $data["penalty_amt"],
					"discount"=> $data["discount"],
					"payable_amt"=> $data["payable_amt"],
					"order_id"=> $data["order_id"] ?? null
				  ]);
		//echo $this->getLastQuery();
		return $this->db->insertID();
	}

    public function getRecord($pgmas_id)
	{
		$builder=$this->db->table('tbl_razor_pay_request')
					->where("id", $pgmas_id)
					->get();
		//echo $this->getLastQuery();
		return $builder->getFirstRow('array');
	}

	public function updateRecord($data, $pg_mas_id)
	{
		return $this->db->table('tbl_razor_pay_request')
					->where("id", $pg_mas_id)
					->Update($data);
			
		//echo $this->getLastQuery();
		//return $this->db->insertID();
	}


}
?>