<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class WaterConsumerCollectionModel extends Model
{

    protected $table = 'tbl_consumer_collection';

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}



    public function insertData($trans_id,$demand_id,$emp_id,$consumer_id)
    {

    	/* $sql="
            insert into tbl_consumer_collection
            (consumer_id,ward_mstr_id,transaction_id,amount,emp_details_id,created_on,status,demand_id)
            select consumer_id,ward_mstr_id,$trans_id as trans_id,amount,$emp_id as emp_id,'".date('Y-m-d H:i:s')."' as created_on,status,id from tbl_consumer_demand where paid_status=0 and generation_date<='2020-07-01' 
            and status=1";*/
            
             $sql="
            insert into tbl_consumer_collection
            (consumer_id,ward_mstr_id,transaction_id,amount,emp_details_id,created_on,status,demand_id, demand_from,demand_upto,penalty,connection_type)

            select consumer_id,ward_mstr_id,$trans_id as trans_id,amount,$emp_id as emp_id,'".date('Y-m-d H:i:s')."' as created_on,1,id,demand_from,demand_upto,penalty,connection_type from tbl_consumer_demand where paid_status=0 and status=1 and consumer_id=$consumer_id and id in($demand_id)";
            

            $run=$this->db->query($sql);
            //echo $this->getLastQuery();exit;
            return $run;

    }

    public function insertCollectionData($trans_id,$upto_month,$emp_id,$consumer_id)
    {

    	/* $sql="
            insert into tbl_consumer_collection
            (consumer_id,ward_mstr_id,transaction_id,amount,emp_details_id,created_on,status,demand_id)
            select consumer_id,ward_mstr_id,$trans_id as trans_id,amount,$emp_id as emp_id,'".date('Y-m-d H:i:s')."' as created_on,status,id from tbl_consumer_demand where paid_status=0 and generation_date<='2020-07-01' 
            and status=1";*/
            
             $sql="
            insert into tbl_consumer_collection
            (consumer_id,ward_mstr_id,transaction_id,amount,emp_details_id,created_on,status,demand_id, demand_from,demand_upto,penalty,connection_type)

            select consumer_id,ward_mstr_id,$trans_id as trans_id,amount,$emp_id as emp_id,'".date('Y-m-d H:i:s')."' as created_on,1,id,demand_from,demand_upto,penalty,connection_type from tbl_consumer_demand where paid_status=0 and status=1 and consumer_id=$consumer_id and generation_date<='".$upto_month."'";
            

            $run=$this->db->query($sql);
            //echo $this->getLastQuery();
            return $run;

    }

  public function getConsumerCollectionByTransactionId($transaction_id){
    try{
        $builder = $this->db->table($this->table)
                  ->select('demand_id')
                  ->where('transaction_id',$transaction_id)
                  ->where('status',1)
                  ->get();
        return $builder->getResultArray();
    }catch(Exception $e){
        echo $e->getMessage();
    }
  }
  public function updateStatus($transaction_id){
    try{
        return $builder = $this->db->table($this->table)
                         ->where('transaction_id',$transaction_id)
                         ->update([
                                    'status'=>0
                                   ]);
    }catch(Exception $e){
        echo $e->getMessage();
    }
  }
  public function getMinDemandId($transaction_id)
  {
      $sql1="select min(demand_id) as min_demand_id from tbl_consumer_collection where transaction_id=$transaction_id and status=1";
      $run1=$this->db->query($sql1);
      $get_min_demand_id=$run1->getFirstRow("array");
      //echo $this->getLastQuery();

      return $get_min_demand_id['min_demand_id'];

  }
  public function getMaxDemandId($transaction_id)
  {
      $sql1="select max(demand_id) as max_demand_id from tbl_consumer_collection where transaction_id=$transaction_id and status=1";
      $run1=$this->db->query($sql1);
      $get_min_demand_id=$run1->getFirstRow("array");
      // echo $this->getLastQuery();
      return $get_min_demand_id['max_demand_id'];
      
  }
  
  
  public function gatecoll()
  {
      $sql1="select sum(amount) as conscoll from tbl_consumer_collection where status=1";
      $run1=$this->db->query($sql1);
      $collection=$run1->getFirstRow("array");
      //echo $this->getLastQuery();

      return $collection['conscoll'];

  }
  public function getAllDemandIdthroughTransactionId($transaction_id)
  {
      $sql="select string_agg(demand_id::text,',') as demand_id from tbl_consumer_collection where transaction_id=$transaction_id";
      $run=$this->db->query($sql);
      $result=$run->getFirstRow("array");
      //echo $this->getLastQuery();
      return $result['demand_id'];
  }
  

}