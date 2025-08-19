<?php
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_govt_saf_collection_dtl extends Model
{
	protected $db;
    protected $table = 'tbl_govt_saf_collection_dtl';
    protected $allowedFields = [];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    
	public function demandCollection($data)
	{ $date_cls= date("Y-m-d H:i:s");
        $sql1 = "INSERT INTO tbl_govt_saf_collection_dtl (govt_saf_dtl_id,govt_saf_transaction_id,govt_saf_demand_dtl_id,fy_mstr_id,qtr,amount,holding_tax,water_tax,education_cess,health_cess,latrine_tax,additional_tax,created_on,ward_mstr_id,fine_months,fine_amt)
			SELECT govt_saf_dtl_id,".$data['insertPayment'].",id,fy_id,qtr,total_tax,holding_tax,water_tax,education_cess,health_cess,latrine_tax,additional_tax,'$date_cls',ward_mstr_id,".$data['pntmnth'].",".$data['tol_pent']."
			FROM view_govsaf_demand
			WHERE id=".$data['resultid']['id'];
			$ql= $this->query($sql1);
			//echo $this->getLastQuery();
			//$result1 =$ql->getResultArray();
		
    }
	
	
	public function collection_dtl($data){
		$sql = "SELECT sum(holding_tax) as holding_tax, sum(water_tax) as water_tax,sum(education_cess) as education_cess,
				sum(health_cess) as health_cess,sum(latrine_tax) as latrine_tax,sum(additional_tax) as additional_tax
			    FROM tbl_govt_saf_collection_dtl
			    where govt_saf_transaction_id=?
				Group by govt_saf_transaction_id";
				$ql= $this->db->query($sql, [$data]);
				//echo $this->db->getLastQuery();
				$result =$ql->getResultArray()[0];
				return $result;
       
    }

	public function collection_demand_dtl($data){
		$sql = "SELECT 
					COALESCE(sum(tbl_govt_saf_demand_dtl.demand_amount), 0) as holding_tax,
					COALESCE(sum(tbl_govt_saf_demand_dtl.additional_holding_tax), 0) as additional_tax
				FROM tbl_govt_saf_collection_dtl
				INNER JOIN tbl_govt_saf_demand_dtl ON tbl_govt_saf_demand_dtl.id=tbl_govt_saf_collection_dtl.govt_saf_demand_dtl_id
			    where tbl_govt_saf_collection_dtl.status=1 AND govt_saf_transaction_id=? 
				and tbl_govt_saf_demand_dtl.status=1
				Group by govt_saf_transaction_id";
				$result = $this->db->query($sql, [$data]);
				return $result->getFirstRow("array");
    }

	public function sum_collection_dtl($data){
		$sql = "SELECT 
					COALESCE(sum(holding_tax), 0)+COALESCE(sum(water_tax), 0)+COALESCE(sum(education_cess), 0)+COALESCE(sum(health_cess), 0)+COALESCE(sum(latrine_tax), 0)+COALESCE(sum(additional_tax), 0) AS holding_tax
				FROM tbl_govt_saf_collection_dtl
			    where govt_saf_transaction_id=?
				Group by govt_saf_transaction_id";
				$result = $this->db->query($sql, [$data]);
				return $result->getFirstRow("array");
       
    }

    public function getAllDemandIdthroughTransactionId($transaction_id)
    {
    	$sql="select string_agg(govt_saf_demand_dtl_id::text,',') as demand_id from tbl_govt_saf_collection_dtl where govt_saf_transaction_id=$transaction_id";
    	$run=$this->db->query($sql);
    	$result=$run->getFirstRow("array");
    	//echo $this->getLastQuery();
    	return $result['demand_id'];

    }
    public function updateStatus($transaction_id)
    {
    	$sql="update tbl_govt_saf_collection_dtl set status=0 where govt_saf_transaction_id=$transaction_id";
    	$run=$this->db->query($sql);
    }
	
}
?>
