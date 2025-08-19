<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class WaterPaymentModel extends Model
{

    protected $table = 'tbl_apply_water_connection';

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}

    public function update_due_amount($transaction_id,$due_amount) // call from userchargepayment controller
    {
        $sql="update tbl_transaction set due_amount=$due_amount where id=$transaction_id";
        $this->db->query($sql);
        //echo $this->getLastQuery();
    }

    public function get_penalty_details($water_conn_id, $type='APPLICANT')
    {
        
       return  $result=$this->db->table("tbl_penalty_dtl")
                            ->select("coalesce(sum(penalty_amt),0) as penalty")
                            ->where("md5(related_id::text)", $water_conn_id)
                            ->where("upper(type)", $type)
                            ->where('status',1)
                            ->where('transaction_id', NULL)
                            ->get()
                            ->getFirstRow("array");

        // echo  $this->getLastQuery();      
    }

    public function countPenaltyExists($apply_connection_id)
    {
        $sql="select count(id) as count from tbl_penalty_dtl where md5(related_id::text)='".$apply_connection_id."' and upper(type)='APPLICANT' ";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        return $result['count'];
        
    }    
    public function updateDifferencePenalty($apply_connection_id,$transaction_id)
    {
        $sql="update tbl_penalty_dtl set transaction_id=$transaction_id,status=0 where md5(related_id::text)='".$apply_connection_id."' and upper(type)='APPLICANT' ";
        $run=$this->db->query($sql);


    }
    public function deleteUnpaidDifferencePenalty($apply_connection_id)
    {
       $sql="delete from tbl_penalty_dtl where md5(related_id::text)='".$apply_connection_id."' and upper(type)='APPLICANT' and status=1 and transaction_id is NULL ";
        $run=$this->db->query($sql);

        
    }

    public function getPenaltyConsumer($consumer_id)
    {
       
       return  $result=$this->db->table("tbl_penalty_dtl")
                            ->select("coalesce(sum(penalty_amt),0) as penalty")
                            ->where("md5(related_id::text)",$consumer_id)
                            ->where("upper(type)","consumer")
                            ->where('status',1)
                            ->get()
                            ->getFirstRow("array");

                       //  echo  $this->getLastQuery();

       return $result;
      
    }
    public function getPenaltyConsumer2($consumer_id)
    {
       
       return  $result=$this->db->table("tbl_penalty_dtl")
                            ->select("coalesce(sum(penalty_amt),0) as penalty")
                            ->where("related_id",$consumer_id)
                            ->where("upper(type)","consumer")
                            ->where('status',1)
                            ->get()
                            ->getFirstRow("array");

                       //  echo  $this->getLastQuery();

       return $result;
      
    }
    public function check_transaction_exist($related_id,$total_paid_amount)
    {
         $result=$this->db->table("tbl_transaction")
                                ->select("count(id) as count")
                                ->whereIn("status",[1,2])
                                ->where("paid_amount",$total_paid_amount)
                                ->where("related_id",$related_id)
                                ->where('transaction_date',date('Y-m-d'))
                                ->whereIn("upper(transaction_type)",['NEW CONNECTION','SITE INSPECTION'])
                                ->get()
                                ->getFirstRow("array");

                                //echo  $this->db->getLastQuery();die;
         return $result['count'];
    }
    public function check_transaction_exist_consumer($related_id,$total_paid_amount)
    {
         $result=$this->db->table("tbl_transaction")
                                ->select("count(id) as count")
                                ->whereIn("status",[1,2])
                                ->where("paid_amount",$total_paid_amount)
                                ->where("related_id",$related_id)
                                ->where('transaction_date',date('Y-m-d'))
                                ->whereIn("upper(transaction_type)",['DEMAND COLLECTION'])
                                ->get()
                                ->getFirstRow("array");

                             //   echo  $this->db->getLastQuery();
         return $result['count'];
    }
    public function get_rebate_details()
    {

        return $result=$this->db->table("tbl_rebate_details")
                            ->select("coalesce(sum(amount),0) as rebate")
                            ->where('status',1)
                            ->get()
                            ->getFirstRow("array");

                           // echo $this->getLastQuery();
                            
      
    }
    public function insert_transaction(array $data)
    {

        $result= $this->db->table("tbl_transaction")
                 ->insert($data);       
        //echo $this->getLastQuery();
        $insert_id=$this->db->insertID();
        return $insert_id;

    }
    public function update_trans_no($trans_no,$transaction_id)
    {

        return $builder = $this->db->table("tbl_transaction")
                            ->where('id', $transaction_id)
                            ->update([
                                    'transaction_no'=>$trans_no
                                    ]);
    }
    public function update_conn_charge_paid_status($water_conn_id,$transaction_id,$payment_for)
    {
         return $builder = $this->db->table("tbl_connection_charge")
                            ->where('apply_connection_id', $water_conn_id)
                            ->where('charge_for',$payment_for)
                            ->update([
                                    'paid_status'=>$transaction_id
                                    ]);
                      //     echo $this->getLastQuery();

    }

    public function insert_cheque_details(array $data)
    {

          $result= $this->db->table("tbl_cheque_details")
                 ->insert($data);       
                 echo $this->getLastQuery();
            $insert_id=$this->db->insertID();
            return $insert_id;
    }

    public function insert_fine_rebate(array $data)
    {

         $result= $this->db->table("tbl_transaction_fine_rebet_details")
                 ->insert($data);       
               //  echo $this->getLastQuery();
            $insert_id=$this->db->insertID();
            return $insert_id;
    }

    public function update_payment_status($water_conn_id,$payment_status)
    {
          return $builder = $this->db->table("tbl_apply_water_connection")
                            ->where('id', $water_conn_id)   
                            ->update([
                                    'payment_status'=>$payment_status
                                    ]);
                      //      echo $this->getLastQuery();
    }

    public function fetch_all_application_data($water_conn_id)
    {
        $sql="select * from view_water_application_details left join (select apply_connection_id,string_agg(applicant_name,',') as applicant_name,string_agg(father_name,',') as father_name,string_agg(mobile_no::text,',') as mobile_no from tbl_applicant_details group by apply_connection_id) as owner on owner.apply_connection_id=view_water_application_details.id where md5(view_water_application_details.id::text)='".$water_conn_id."'";

        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
       // echo $this->db->getLastQuery();
        return $result;

    }
    public function application_data($water_conn_id)
    {
        $sql="select * from view_water_application_details where id = $water_conn_id";

        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
       // echo $this->db->getLastQuery();
        return $result;

    }
    public function transaction_details($transaction_id)
    {
        if(is_numeric($transaction_id))
            $transaction_id = md5($transaction_id);
        $sql="select tbl_transaction.*,cheque_no,cheque_date,bank_name,branch_name from tbl_transaction left join tbl_cheque_details on tbl_cheque_details.transaction_id=tbl_transaction.id where md5(tbl_transaction.id::text)='".$transaction_id."'";

        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        // echo $this->db->getLastQuery();
        return $result;

    }
    public function insert_level_pending(array $data)
    {
           $result= $this->db->table("tbl_level_pending")
                 ->insert($data);
                 echo $this->getLastQuery();
           $insert_id=$this->db->insertID();
           return $insert_id;
    }
    /*public function check_count_level($apply_connection_id)
    {
           $result= $this->db->table("tbl_level_pending")
                             ->select("count(id) as id")
                             ->where("status",1)

           $insert_id=$this->db->insertID();
           return $insert_id;
    }

    */

    public function updateDemandFromandUpto($transaction_id,$demand_from,$demand_upto)
    {
        $sql="update tbl_transaction set from_month='".$demand_from."',upto_month='".$demand_upto."' where id=$transaction_id";
        $this->db->query($sql);
        // echo $this->getLastQuery();
        
    }

    public function transaction_details_int_id($transaction_id)
    {
        $sql="select tbl_transaction.*,cheque_no,cheque_date,bank_name,branch_name from tbl_transaction left join tbl_cheque_details on tbl_cheque_details.transaction_id=tbl_transaction.id where tbl_transaction.id='".$transaction_id."'";

        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        //echo $this->db->getLastQuery();
        return $result;

    }
    
    public function meter_reding_for_recipt($transaction_id)
    {
        $sql = "select sum(case when d.connection_type in('Meter','Metered') 
                        then COALESCE (c.amount , 0)+ COALESCE (c.penalty , 0) else null::numeric end ) as meter_payment,
                    sum(case when d.connection_type ='Fixed' 
                        then COALESCE (c.amount , 0)+ COALESCE (c.penalty , 0) else null::numeric end 
                    ) as fixed_payment,
                    max(case when d.connection_type in('Meter','Metered') 
                        then t.final_reading else null::numeric end ) as final_reading,
                    min(case when d.connection_type in('Meter','Metered') 
                        then t.initial_reading else null::numeric end ) as initial_reading,
                    max(case when d.connection_type ='Fixed' 
                        then d.demand_upto else null::date end ) as demand_upto,
                    min(case when d.connection_type ='Fixed'  
                        then d.demand_from else null::date end ) as demand_from    
                from tbl_consumer_collection c 
                join tbl_consumer_demand d on d.id = c.demand_id
                left join tbl_consumer_tax t on t.id = d.consumer_tax_id 
                Where c.transaction_id = $transaction_id AND d.status=1 AND c.status=1";
        $data = $this->db->query($sql)->getFirstRow('array');
        //echo $this->db->getLastQuery();
        return $data;

    }
}