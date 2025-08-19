<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_govt_saf_transaction_fine_rebet_details extends Model
{
	protected $db;
    protected $table = 'tbl_govt_saf_transaction_fine_rebet_details';
    protected $allowedFields = [];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
	
	public function fine_rebet_details($data){
		$resultPnlt = $this->db->table('tbl_govt_saf_transaction_fine_rebet_details')->
			insert([
				  "govt_saf_dtl_id"=>$data['custm_id'],
				  "govt_saf_transaction"=>$data['insertPayment'],
				  "head_name"=>$data['head_name'],
				  "amount"=>$data['fine_rebet_amount'],
				  "value_add_minus"=>$data['add_minus'],
				  "created_on"=>$data["date"],
				  "status"=>1
				  ]);
	}
	
	public function penalty_dtl($govt_saf_transaction_id, $govt_saf_id){
		//print_r($data);
		$sql = "SELECT head_name, amount, value_add_minus
			    FROM tbl_govt_saf_transaction_fine_rebet_details
			    where status=1 AND govt_saf_transaction=? and govt_saf_dtl_id=?
				";
				$ql= $this->db->query($sql, [$govt_saf_transaction_id, $govt_saf_id]);
				//echo $this->db->getLastQuery();
				$result =$ql->getResultArray();
				return $result;
       
    }

	public function already_paid($govt_saf_transaction_id, $govt_saf_id){
		//print_r($data);
		$sql = "SELECT
					COALESCE(SUM(tbl_govt_saf_collection_dtl.amount), 0) AS already_paid
				FROM tbl_govt_saf_collection_dtl
				WHERE
					tbl_govt_saf_collection_dtl.govt_saf_transaction_id<?
					AND govt_saf_demand_dtl_id IN (
						SELECT
							tbl_govt_saf_collection_dtl.govt_saf_demand_dtl_id
						FROM tbl_govt_saf_collection_dtl
						JOIN tbl_govt_saf_demand_dtl ON tbl_govt_saf_demand_dtl.id=tbl_govt_saf_collection_dtl.govt_saf_demand_dtl_id
						WHERE
							tbl_govt_saf_demand_dtl.govt_saf_dtl_id=?
							AND tbl_govt_saf_collection_dtl.govt_saf_transaction_id=?
						AND tbl_govt_saf_collection_dtl.amount!=tbl_govt_saf_demand_dtl.amount
					)";
				$ql= $this->db->query($sql, [$govt_saf_transaction_id, $govt_saf_id, $govt_saf_transaction_id]);
				//echo $this->db->getLastQuery();
				return $ql->getFirstRow("array");
       
    }
    

}
?>