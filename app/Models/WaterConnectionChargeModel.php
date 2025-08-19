<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class WaterConnectionChargeModel extends Model
{

    protected $table = 'tbl_connection_charge';

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}

    public function due_exists($apply_connection_id)
    {
        $result=$this->db->table($this->table)
                         ->select("count(id) as count")
                         ->where('md5(apply_connection_id::text)',$apply_connection_id)
                         ->where('status',1)
                         ->where('paid_status',0)
                         ->get()
                         ->getFirstRow("array");

        //echo $this->getLastQuery();
        return $result['count'];

    }

    public function checkConnectionChargePaid($apply_connection_id)
    {
    	$result=$this->db->table($this->table)
    				->selectCount('id')
    				->where('apply_connection_id',$apply_connection_id)
                    ->where('paid_status!=',0)
                    ->where('charge_for','New Connection')
    				->get()
    				->getFirstRow("array");

    				//echo ($result['id']);
    		//	echo $this->getLastQuery();
    	return $result['id'];
    }

    public function checkExists($apply_connection_id)
    {
        $result=$this->db->table($this->table)
                    ->select('count(id) as count')
                    ->where('apply_connection_id',$apply_connection_id)
                    // ->where('payment_status',0)
                    ->where('paid_status',0)
                    ->where('charge_for','Site Inspection')
                    ->get()
                    ->getFirstRow("array");

                    //echo ($result['id']);
            //print_var($result); 
            // echo $this->getLastQuery();
        return $result['count'];
    }
    
    public function get_connection_charge_paid($apply_connection_id)
    {

        return $result=$this->db->table("tbl_connection_charge")
                        ->select("sum(amount) as amount")
                        ->where("charge_for","New Connection")
                        ->where('paid_status!=',0)
                        ->where('apply_connection_id',$apply_connection_id)
                        ->get()
                        ->getFirstRow("array");

    }

    public function get_connection_charge_paid_details($apply_connection_id)
    {

         $result=$this->db->table("tbl_connection_charge")
                        ->select("*")
                        ->where("charge_for","New Connection")
                        ->where('paid_status!=',0)
                        ->where('apply_connection_id',$apply_connection_id)
                        ->get()
                        ->getFirstRow("array");
        //echo $this->db->getLastQuery();
        return $result;

    }

    public function get_All_connection_charge_paid_details($apply_connection_id)
    {

         $result=$this->db->table("tbl_connection_charge")
                        ->select("sum(conn_fee) as conn_fee, sum(penalty) as penalty")
                        // ->where("charge_for","New Connection")
                        ->where('paid_status!=',0)
                        ->where('apply_connection_id',$apply_connection_id)
                        ->get()
                        ->getFirstRow("array");
        //echo $this->db->getLastQuery();
        return $result;

    }

    

    public function insertData(array $data)
    {

    	$result= $this->db->table($this->table)
                 ->insert($data);       
               // echo $this->getLastQuery();
        $insert_id=$this->db->insertID();
        return $insert_id;

    }
   
    public function insert_site_inspec_conn_charge(array $data)
    {


        $result= $this->db->table($this->table)
                 ->insert($data);       
         //echo $this->db->getLastQuery();
        $insert_id=$this->db->insertID();
        return $insert_id;
    }
    public function update_connection_charge(array $connection_charge)
    {
         return $builder=$this->db->table($this->table)
                                ->where('apply_connection_id',$connection_charge['apply_connection_id'])
                                ->where('paid_status',0)
                                ->where('charge_for',$connection_charge['charge_for'])
                                ->update([
                                    "amount"=>$connection_charge['amount'],
                                    "conn_fee"=>$connection_charge['conn_fee'],
                                    "penalty"=>$connection_charge['penalty']
                                    
                                ]);
    }
    public function deleteConnectionCharge($apply_connection_id)
    {
        $sql="delete from tbl_connection_charge where md5(apply_connection_id::text)='$apply_connection_id' and paid_status=0 and charge_for='Site Inspection' and status=1";
        $run=$this->db->query($sql);
        // echo $this->getLastQuery();


    }
    public function updateStatus($id)
    {
        try{
            $builder = $this->db->table($this->table)
                     ->where('id',$id)
                     ->update([
                             'paid_status'=>0
                             ]);
                    // echo $this->db->getLastQuery();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getIdUsingTransactionId($transaction_id){
        try{
            $builder = $this->db->table($this->table)
                 ->select('id')
                 ->where('paid_status',$transaction_id)
                 ->where('status',1)
                 ->get();
            return $builder->getResultArray()[0];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function updatePaidStatus($transaction_id){
        try{
            return $builder = $this->db->table($this->table)
                             ->where('paid_status',$transaction_id)
                             ->update([
                                        'paid_status'=>0
                                    ]);
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getAllConnectionCarge($apply_connection_id)
    {
        try{

            $data = $this->db->table($this->table)
                        ->select('sum(case when paid_status!=0 then amount else 0 end )as paid_connection_charge,
                                    sum(case when paid_status =0 then amount else 0 end )as unpaid_connection_charge,
                                    sum( amount )as total_connection_charge')
                        ->where('status',1);
            if(is_numeric($apply_connection_id))
                $data  = $data->where('apply_connection_id',$apply_connection_id);
            else
                $data  = $data->where('md5(apply_connection_id::text)',$apply_connection_id);
            $data = $data->get()->getFirstRow('array');
            return $data;
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }

    }
}