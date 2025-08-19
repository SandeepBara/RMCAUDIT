<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_dashboard_data extends Model
{
    protected $table = 'dashboard_data';
    protected $allowedFields = [''];

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	
	}
	
	public function total_current_arrear_amount($data)
    {
		$sql = "SELECT *
			    FROM dashboard_data
				where FY='".$data."'";
				$ql= $this->db->query($sql);
				//echo $this->db->getLastQuery();
				$result =$ql->getResultArray()[0];
				return $result;
		
    }
	
	
	public function total_prop_dmnd_coll($data)
    {
		$sql = "SELECT total_demand,total_coll
			    FROM dashboard_data
				where FY='".$data."'";
				$ql= $this->db->query($sql);
				//echo $this->db->getLastQuery();
				$result =$ql->getResultArray()[0];
				return $result;
		
    }

}
