<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class WaterDemandPenaltyMaster extends Model
{
    
    protected $table = 'tbl_demand_penalty_master';

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}

    public function getPenaltyDetails($connection_type,$demand_from)
    {
        $sql="select * from tbl_demand_penalty_master where effective_date<='".$demand_from."' and status=1 and connection_type=$connection_type order by effective_date desc";
        $run=$this->db->query($sql);
        // echo $this->getLastQuery();
        $result=$run->getFirstRow("array");
        return $result;

    }
    public function getPenaltyMasterList($connection_type)
    {
        $sql="select * from tbl_demand_penalty_master where status=1 and connection_type=$connection_type";
        $run=$this->db->query($sql);
        // echo $this->getLastQuery();
        $result=$run->getResultArray("array");
        return $result;

    }
    
}