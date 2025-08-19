<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;
use Razorpay\Api\Order;

class WaterPenaltyModel extends Model
{
    protected $db;
    protected $table = 'tbl_penalty_dtl';
    
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

    public function checkExists($apply_connection_id)
    {
        $sql="select count(id) as count from tbl_penalty_dtl where related_id=$apply_connection_id and penalty_type='Difference Penalty paid as Installment Rebate' and type='Applicant'";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        return $result;
        
    }
	
	public function getUnpaidPenaltySum($apply_connection_id, $type='Applicant')
    {
        $sql="select coalesce(sum(penalty_amt), 0) as penalty_amt from tbl_penalty_dtl where md5(related_id::text)='$apply_connection_id' and type='$type' and status=1";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        return $result["penalty_amt"];
    }

    function updatePaidPenalty($related_id, $type='Applicant')
    {
        //0 for paid,  1 for active
        $this->db->table($this->table)
                        ->where('related_id', $related_id)
                        ->where('type', $type)
                        ->update([
                                'status'=> 1
                                ]);
        
    }
    

    public function updateUnpaidPenalty($related_id, $type='Applicant')
    {
        //0 for paid,  1 for active
        $run=$this->db->table($this->table)
                        ->where('related_id', $related_id)
                        ->where('type', $type)
                        ->update([
                                'status'=> 0
                                ]);
        echo $this->getLastQuery();
    }

    public function getPenlatyDtlByTrId($transection_id)
    {
        try{
            $data = $this->db->table($this->table)
                             ->select('*')
                             ->where('transaction_id',$transection_id) 
                             ->orderBy('id','desc')
                             ->get()
                             ->getFirstRow('array');   
            return $data;                         
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }
   
    public function updatePenlatyAmtByTrId($data=array())
    {
        try{
            $data = $this->db->table($this->table)
                             ->where('transaction_id',$data['transaction_id']) 
                             ->update([
                                 'penalty_amt'=>$data['penalty_amt'],
                             ]);
                             
            //echo $this->db->getLastQuery();die;                
            return $data;                         
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function updatePenlatyStatusByTrId($data=array())
    {
        try{
            $data = $this->db->table($this->table)
                             ->where('transaction_id',$data['transaction_id']) 
                             ->update([
                                 'status'=>$data['penalty_amt'],
                             ]);
                             
                            
            return $data;                         
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }

    #-------------------advance and adjustment 22-07-2022 -----------------------
    public function getAdvance($consumer_id,$modul="consumer")
    {
        try{             
            $sql ="WITH advance AS (
                        SELECT
                            COALESCE(SUM(amount), 0) AS advance_amt
                        FROM 
                        tbl_advance_mstr 
                        WHERE related_id= $consumer_id 
                            AND tbl_advance_mstr.module='$modul' AND status=1
                    ),
                    adjustment AS (
                        SELECT
                            COALESCE(SUM(amount), 0) AS adjusatment_amt
                        FROM tbl_adjustment_mstr 
                        WHERE related_id= $consumer_id
                        AND tbl_adjustment_mstr.module='$modul' AND status=1
                    )
                    SELECT
                        advance_amt,
                        adjusatment_amt,
                        advance_amt-adjusatment_amt AS balance
                    FROM advance,adjustment 
                    ";
            $data= $this->db->query($sql)->getFirstRow('array');
            // echo $this->db->getLastQuery();die;            
            return $data; 
                                 
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }
    public function insert_tbl_advance_mstr(array $inputs)
    {
        try{             
            $this->db->table('tbl_advance_mstr')
                    ->insert($inputs);
            // echo $this->db->getLastQuery();die;            
            return $this->db->insertID(); 
                                 
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }
    public function update_tbl_advance_mstr(array $where,array $inputs)
    {
        try{
            $data = $this->db->table('tbl_advance_mstr')                    
                    ->where($where) 
                    ->update($inputs);                      
            // echo $this->db->getLastQuery();die; 
            return $data;                      
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function get_tbl_advance_mstr($consumer_id,$transection_id,$modul="consumer")
    {
        try{
            $data = $this->db->table('tbl_advance_mstr')
                            ->select('amount')
                            ->where('status',1)
                            ->where('related_id',$consumer_id)
                            ->where('transaction_id',$transection_id)
                            ->where('module',$modul)
                            ->get()
                            ->getFirstRow('array');
            return $data;
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }
    public function insert_tbl_adjustment_mstr(array $inputs)
    {
        try{             
            $this->db->table('tbl_adjustment_mstr')
                    ->insert($inputs);
            // echo $this->db->getLastQuery();die;            
            return $this->db->insertID(); 
                                 
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }
    public function update_tbl_adjustment_mstr(array $where,array $inputs)
    {
        try{
            $data = $this->db->table('tbl_adjustment_mstr')                    
                    ->where($where) 
                    ->update($inputs);                      
            // echo $this->db->getLastQuery();die; 
            return $data;                      
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }
    public function get_tbl_adjustment_mstr($consumer_id,$transection_id,$modul="consumer")
    {
        try{
            $data = $this->db->table('tbl_adjustment_mstr')
                            ->select('amount')
                            ->where('status',1)
                            ->where('related_id',$consumer_id)
                            ->where('transaction_id',$transection_id)
                            ->where('module',$modul)
                            ->get()
                            ->getFirstRow('array');
            return $data;
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }
    #-------------------end advance and adjustment-------------------------
}