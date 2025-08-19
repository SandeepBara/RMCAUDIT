<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_trade_dashboard_daily_collection extends Model
{
    protected $table = 'trade_dashboard_daily_collection';
    protected $allowedFields = [''];

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	
	}
	
	
	public function current_fy_collection($data){
		//print_r($data);
		$fromdate=$data['fromdate'];
		$toDate=$data['toDate'];
		$sql = "SELECT sum(amount) AS collectionAmount
			    FROM trade_dashboard_daily_collection
				where date BETWEEN '$fromdate' AND '$toDate'";
				$ql= $this->db->query($sql);
				//echo $this->db->getLastQuery();
				$result =$ql->getResultArray()[0];
				return $result;

    }
	
	public function current_mnth_collection($data){
		//print_r($data);
		$fromdate=$data['fromdate'];
		$sql = "SELECT sum(amount) as collectionamount
			    FROM trade_dashboard_daily_collection
				where date='".$fromdate."'";
				$ql= $this->db->query($sql);
				//echo $this->db->getLastQuery();
				$result =$ql->getResultArray()[0];
				return $result;
       
    }
	
	
	public function cmpr_fy_collection($data){
		//print_r($data);
		$fromdate=$data['fromdate'];
		$toDate=$data['toDate'];
		$sql = "SELECT sum(amount) as collectionamount
			    FROM trade_dashboard_daily_collection
				where date BETWEEN '$fromdate' AND '$toDate'";
				$ql= $this->db->query($sql);
				//echo $this->db->getLastQuery();
				$result =$ql->getResultArray()[0];
				return $result;
       
    }

}
