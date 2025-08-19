<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class WaterFixedMeterRateModel extends Model
{
    
    protected $table = 'tbl_fixed_meter_rate';

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}



    public function insertData(array $data)
    {

    	$result= $this->db->table($this->table)
                 ->insert($data);       
       // echo $this->getLastQuery();
        $insert_id=$this->db->insertID();
        return $insert_id;

    }
    public function getMeteredRateId(array $data)
    {
    
        $sql="select id,amount from tbl_fixed_meter_rate where property_type_id=".$data['property_type_id']." and type='Meter' and ceil(".$data['area_sqft'].") >= range_from and ceil(".$data['area_sqft'].") <= range_upto and status=1 order by effective_date desc ";
        
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");

        //echo $this->getLastQuery();

        return $result;
      
    }
    public function getFixedRateId(array $data)
    {
        
        $sql="select id,amount from tbl_fixed_meter_rate where property_type_id=".$data['property_type_id']." and type='Fixed' and ceil(".$data['area_sqft'].") >= range_from and ceil(".$data['area_sqft'].") <= range_upto and status=1 order by effective_date desc ";
        
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");

        //echo $this->getLastQuery();

        return $result;
      
    }

    public function getFixedRatebyEffectDate(array $data)
    {
    
        $sql="select id,amount from tbl_fixed_meter_rate where property_type_id=".$data['property_type_id']." and type='Fixed' and ceil(".$data['area_sqft'].") >= range_from and ceil(".$data['area_sqft'].") <= range_upto and status=1 and effect_date='".$data['date']."' order by effective_date desc ";
        
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");

        //echo $this->getLastQuery();

        return $result;
      
    }
    public function getFixedRateChangeCount(array $data)
    {
        $sql="select count(id) as count from tbl_fixed_meter_rate where property_type_id=".$data['property_type_id']." and type='Fixed' and ceil(".$data['area_sqft'].") >= range_from and ceil(".$data['area_sqft'].") <= range_upto";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        return $result;
        
    }
    public function fixedRateCharge($property_type_id,$area_sqmt)
    {
        $sql="select * from tbl_fixed_meter_rate where property_type_id=$property_type_id and ceil($area_sqmt) >= range_from and ceil($area_sqmt) <= range_upto";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        return $result;
    }
    public function getLatestFixedRateCharge($property_type_id,$area_sqmt,$demand_from)
    {
        $sql="select * from tbl_fixed_meter_rate where property_type_id=$property_type_id and ceil($area_sqmt) >= range_from and ceil($area_sqmt) <= range_upto and effective_date<'$demand_from' and type='Fixed' order by effective_date desc";
        $run=$this->db->query($sql);
       //echo $this->db->getLastQuery();
        $result=$run->getFirstRow("array");
        return $result;
    }
    public function getFixedRateEffectBetweenDemandGeneration($property_type_id,$area_sqmt,$demand_from)
    {
        $sql="select * from tbl_fixed_meter_rate where property_type_id=$property_type_id and ceil($area_sqmt) >= range_from and ceil($area_sqmt) <= range_upto and effective_date>='$demand_from' and type='Fixed' order by effective_date asc";
        $run=$this->db->query($sql);
        //echo $this->db->getLastQuery();
        $result=$run->getResultArray();
        return $result;
    }

    public function getMeterRateCharge($property_type_id,$area_sqmt,$demand_from=NULL)
    {
        $sql="SELECT * 
                FROM tbl_fixed_meter_rate 
                WHERE property_type_id=$property_type_id 
                AND ceil($area_sqmt) >= range_from AND ceil($area_sqmt) <= range_upto 
                AND type='Meter'
                ".($demand_from?" AND effective_date <='$demand_from' ":"")." 
                ORDER BY effective_date DESC
                ";
        $run=$this->db->query($sql);
        // echo $this->db->getLastQuery();
        $result=$run->getFirstRow("array");
        return $result;
    }
    public function getMeterEffectBetweenDemandGeneration($property_type_id,$area_sqmt,$demand_from)
    {
        $sql="select * from tbl_fixed_meter_rate where property_type_id=$property_type_id and ceil($area_sqmt) >= range_from and ceil($area_sqmt) <= range_upto and effective_date>='$demand_from' and type='Meter' order by effective_date asc";
        $run=$this->db->query($sql);
        // echo $this->db->getLastQuery();
        $result=$run->getResultArray();
        return $result;
    }
    public function getLastFixedRateCharge($property_type_id,$area_sqmt)
    {
        $sql="select * from tbl_fixed_meter_rate where property_type_id=$property_type_id and ceil($area_sqmt) >= range_from and ceil($area_sqmt) <= range_upto and type='Fixed' order by effective_date desc";
        $run=$this->db->query($sql);
       //echo $this->getLastQuery();
        $result=$run->getFirstRow("array");
        return $result;
    }
    public function getRateDetails($where)
    {
        $sql="select distinct(amount) as amount from tbl_fixed_meter_rate $where";
        $run=$this->db->query($sql);
       //echo $this->getLastQuery();
        $result=$run->getResultArray();
        return $result;
    }
    
}