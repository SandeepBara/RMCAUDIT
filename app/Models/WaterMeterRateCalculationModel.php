<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class WaterMeterRateCalculationModel extends Model
{
    
    protected $table = 'tbl_meter_rate_calculation_mstr';

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}




    public function getMeterCalculationRate($ulb_type_id)
    {
        
        $sql="select * from tbl_meter_rate_calculation_mstr where status=1 and ulb_type_id=$ulb_type_id order by effective_date desc ";

        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        //echo $this->getLastQuery();
        return $result;
        
    }
   
}