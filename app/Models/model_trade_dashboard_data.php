<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_trade_dashboard_data extends Model
{
    protected $table = 'trade_dashboard_data';
    protected $allowedFields = [''];

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	
	}
	
	public function gatedatabyfy($data)
    {
		$sql = "SELECT *
			    FROM trade_dashboard_data
				where fy='".$data."'";
				$ql= $this->db->query($sql);
				//echo $this->db->getLastQuery();
				$result =$ql->getResultArray()[0];
				return $result;
		
    }
	
	
	public function gatedmndcollbyfy($data)
    {
		$sql = "SELECT total_demand,total_collection
			    FROM trade_dashboard_data
				where FY='".$data."'";
				$ql= $this->db->query($sql);
				//echo $this->db->getLastQuery();
				$result =$ql->getResultArray()[0];
				return $result;
		
    }

}
