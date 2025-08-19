<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_dashboard_daily_collection extends Model
{
    protected $table = 'dashboard_daily_collection';
    protected $allowedFields = [''];

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	
	}
	
	public function total_today_collection_amount($data)
    {
		try{        
            $builder = $this->db->table("dashboard_daily_collection")
                        ->select('amount')
                        ->where('date', $data)
                        ->get();
						//echo $this->db->getLastQuery()
            return $builder->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
	
	public function current_fy_collection($data){
		//print_r($data);
		$fromdate=$data['fromdate'];
		$toDate=$data['toDate'];
		$sql = "SELECT sum(amount) as fy_coll
			    FROM dashboard_daily_collection
				where date BETWEEN '$fromdate' AND '$toDate'";
				$ql= $this->db->query($sql);
				//echo $this->db->getLastQuery();
				$result =$ql->getResultArray()[0];
				return $result;
       
    }
	
	public function dy_collection($data){
		//print_r($data);
		$fromdate=$data['fromdate'];
		$sql = "SELECT sum(amount) as dy_coll
			    FROM dashboard_daily_collection
				where date='".$fromdate."'";
				$ql= $this->db->query($sql);
				//echo $this->db->getLastQuery();
				$result =$ql->getResultArray()[0];
				return $result;
       
    }
	
	
	public function compare_fy_collection($data){
		//print_r($data);
		$fromdate=$data['fromdate'];
		$toDate=$data['toDate'];
		$sql = "SELECT sum(amount) as fy_coll
			    FROM dashboard_daily_collection
				where date BETWEEN '$fromdate' AND '$toDate'";
				$ql= $this->db->query($sql);
				//echo $this->db->getLastQuery();
				$result =$ql->getResultArray()[0];
				return $result;
       
    }

}
