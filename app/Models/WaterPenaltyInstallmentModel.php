<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class WaterPenaltyInstallmentModel extends Model
{
    
    protected $table = 'tbl_penalty_installment';
    
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


    public function getUnpaidInstallment($apply_connection_id)
    {
        $sql="select id,  (balance_amount+rebate) as installment_amount from tbl_penalty_installment where paid_status=0 and status=1 and md5(apply_connection_id::text)='$apply_connection_id' order by id";
        $run=$this->db->query($sql);
        //echo $this->getLastQuery();
        $result=$run->getResultArray();
        return $result;
        
    }
    public function getUnpaidInstallment2($apply_connection_id)
    {
        $sql="select id,  (balance_amount+rebate) as installment_amount from tbl_penalty_installment where paid_status=0 and status=1 and apply_connection_id::text='$apply_connection_id' order by id";
        $run=$this->db->query($sql);
        //echo $this->getLastQuery();
        $result=$run->getResultArray();
        return $result;
        
    }
    public function getUnpaidInstallmentSum($apply_connection_id)
    {
        $sql="select coalesce(sum(balance_amount+rebate),0) as installment_amount from tbl_penalty_installment where paid_status=0 and status=1 and md5(apply_connection_id::text)='$apply_connection_id'";
        $run=$this->db->query($sql);
      // echo $this->getLastQuery();
        $result=$run->getFirstRow("array");

       /* $sql="select sum(penalty) as installment_amount from tbl_connection_charge where  md5(apply_connection_id::text)='$apply_connection_id' and status=1";
        $run=$this->db->query($sql);
        //echo $this->getLastQuery();
        $result=$run->getFirstRow("array");
        */
        return $result['installment_amount'];
        
    }

    public function getPenaltyforRebate($apply_connection_id)
    {
        $sql="select coalesce(sum(penalty),0) as installment_amount from tbl_connection_charge where  md5(apply_connection_id::text)='$apply_connection_id' and status=1";
        $run=$this->db->query($sql);
        //echo $this->getLastQuery();
        $result=$run->getFirstRow("array");
      
        return $result['installment_amount'];
        
    }

    public function getSum($apply_connection_id,$installment_upto_id)
    {
        $sql="select coalesce(sum(balance_amount+rebate),0) as installment_amount, count(id) as count from tbl_penalty_installment where paid_status=0 and status=1 and md5(apply_connection_id::text)='$apply_connection_id' and id<=$installment_upto_id";
        $run=$this->db->query($sql);
        //echo $this->getLastQuery();
        $result=$run->getFirstRow("array");
        return $result;
    }
    public function getInstallmentId($apply_connection_id,$installment_upto_id)
    {
         $sql="select string_agg(id::text,',') as intallment_id,coalesce(sum(balance_amount+rebate),0) as installment_amount,count(id) as count from tbl_penalty_installment where paid_status=0 and status=1 and md5(apply_connection_id::text)='$apply_connection_id' and id<=$installment_upto_id";
         $run=$this->db->query($sql);
         //echo $this->getLastQuery();
         $result=$run->getFirstRow("array");
         return $result;
    }
    public function updateInstallment($installment_id,$transaction_id)
    {
        $sql="update tbl_penalty_installment set paid_status=1,transaction_id=$transaction_id,payment_from='A' where id in($installment_id) and status=1 and paid_status=0  ";
        $run=$this->db->query($sql);
        //echo $this->getLastQuery();

    }
    public function updateFullInstallment($water_conn_id,$transaction_id)
    {
        $sql="update tbl_penalty_installment set paid_status=1,transaction_id=$transaction_id,payment_from='A' where apply_connection_id=$water_conn_id and status=1 and paid_status=0 ";
        $run=$this->db->query($sql);
        //echo $this->getLastQuery();

    }
    public function getInstallmentDetails($apply_connection_id,$installment_upto_id,$transaction_id)
    {
        $sql="select id,balance_amount+rebate as installment_amount,penalty_head from tbl_penalty_installment where md5(apply_connection_id::text)='".$apply_connection_id."' and id<=$installment_upto_id and transaction_id=$transaction_id and status=1 and paid_status=1";
        $run=$this->db->query($sql);
        echo $this->getLastQuery();
        
        $result=$run->getResultArray();
        return $result;
        
    }
    public function getInstallmentDetailsbyApplyConnectionId($apply_connection_id,$transaction_id)
    {
        $sql="select id,balance_amount+rebate as installment_amount,penalty_head from tbl_penalty_installment where md5(apply_connection_id::text)='".$apply_connection_id."'  and status=1 and paid_status=1 and transaction_id=$transaction_id";
        $run=$this->db->query($sql);
        // echo $this->getLastQuery();

        $result=$run->getResultArray();
        return $result;
        
    }
    public function updateInstallmentDtlbyAppConnIdAdTrId($apply_connection_id,$transaction_id)
    {
        try
		{
            return $builder = $this->db->table($this->table)
                             ->where('apply_connection_id', $apply_connection_id)
                             ->where('transaction_id',$transaction_id)
                             ->update([
                                        'paid_status'=> 0,
										'transaction_id'=> NULL,
										'payment_from'=> NULL,
                                    ]);
        }
		catch(Exception $e)
		{
            echo $e->getMessage();
        }
        
    }
    public function updateChBounceTrIdAndAppId($apply_connection_id,$transaction_id)
    {
        try
		{
             $builder = $this->db->table($this->table)
                             ->where('apply_connection_id', $apply_connection_id)
                             ->where('transaction_id',$transaction_id)
                             ->update([
                                        'paid_status'=> 0,										
                                    ]);
                                    // echo $this->db->getLastQuery();
                                    return 1;
        }
		catch(Exception $e)
		{
            echo $e->getMessage();
        }
        
    }

    public function updateChClearTrIdAndAppId($apply_connection_id,$transaction_id)
    {
        try
		{
             $builder = $this->db->table($this->table)
                             ->where('apply_connection_id', $apply_connection_id)
                             ->where('transaction_id',$transaction_id)
                             ->update([
                                        'paid_status'=> 1,										
                                    ]);
                                    //echo $this->db->getLastQuery();
                                    return 1;
        }
		catch(Exception $e)
		{
            echo $e->getMessage();
        }
        
    }
    
    public function countExistsUnpaidInstallmentafterId($apply_connection_id,$installment_upto_id)
    {

        $sql="select count(id) as count from tbl_penalty_installment where md5(apply_connection_id::text)='".$apply_connection_id."' and id>$installment_upto_id and status=1 and paid_status=0";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        return $result['count'];


    }

    public function paidInstallment($apply_connection_id)
    {   
        $sql="select coalesce(sum(balance_amount+rebate),0) as installment_amount from tbl_penalty_installment where paid_status=1 and status=1 and apply_connection_id=$apply_connection_id";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        //echo $this->getLastQuery();
        return $result['installment_amount'];
    }
    
    public function deactivateUnpaidInstallment($apply_connection_id)
    {
        $sql="update tbl_penalty_installment set status=2 where apply_connection_id=$apply_connection_id and paid_status=0 and status=1";
        $run=$this->db->query($sql);
       
        
    }
    public function deleteUnpaidInstallment($apply_connection_id)
    {
        $sql="delete from tbl_penalty_installment where apply_connection_id=$apply_connection_id and paid_status=0 and status=1";
        $run=$this->db->query($sql);
        
        
    }

    public function countUnpaidInstallment($apply_connection_id)
    {
        $sql="select count(id) as count from tbl_penalty_installment where md5(apply_connection_id::text)='".$apply_connection_id."' and paid_status=0 and status=1";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        return $result['count'];

    }
	
	public function updatePenaltyInstallmentStatus($apply_connection_id)
	{
        try
		{
            return $builder = $this->db->table($this->table)
                             ->where('apply_connection_id', $apply_connection_id)
                             ->update([
                                        'paid_status'=> 0,
										'transaction_id'=> NULL,
										'payment_from'=> NULL,
                                    ]);
        }
		catch(Exception $e)
		{
            echo $e->getMessage();
        }
    }
   
}
