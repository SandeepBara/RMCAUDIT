<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class WaterRevisedMeterRateModel extends Model
{
    
    protected $table = 'tbl_meter_rate_chart';

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}




    public function getMeterRate($property_type_id,$where)
    {
    
        $sql="select id,amount from tbl_meter_rate_chart where property_type_id=$property_type_id $where and status=1 order by effective_date desc ";
        
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");

        //echo $this->getLastQuery();die;

        return $result;
      
    }
    public function getMeterRate_new($property_type_id,$where)
    {
    
        $sql="select id,amount,upto_unit,reading from tbl_meter_rate_chart where property_type_id=$property_type_id $where and status=1 order by effective_date desc ";
        
        $run=$this->db->query($sql);
        // $result=$run->getFirstRow("array");
        $result=$run->getResultArray();

        // echo $this->getLastQuery();
        // die;

        return $result;
      
    }
   
}