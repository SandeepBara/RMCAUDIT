<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class Water_Cheque_Details_Model extends Model
{

    protected $table = 'tbl_cheque_details';
    protected $allowedFields = ['id','transaction_id','cheque_no','cheque_date','bank_name','branch_name','emp_details_id','created_on','status'];

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}
    public function allChequeDetails(){
        try{
            $builder = $this->db->table($this->table)
                     ->select('*')
                     ->where('status',2)
                     ->get();
            return $builder->getResultArray();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function chequeDetailsById($id){
        try{
            $builder = $this->db->table($this->table)
                     ->select('*')
                     ->where('status',2)
                     ->where('md5(id::text)',$id)
                     ->get();
                     //echo $this->db->getLastQuery();
            return $builder->getResultArray()[0];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getChequeDetailsByDate($data){
        try{
            return $this->db->table($this->table)
                         ->select('*')
                         ->where('date(created_on)>=',$data['from_date'])
                         ->where('date(created_on)<=',$data['to_date'])
                         ->where('status!=',0)
                         ->orderBy('date(created_on),cheque_no','DESC')
                         ->get()
                         ->getResultArray();
                //echo $this->db->getLastQuery();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function updateStatus($id){
        try{
            $builder = $this->db->table($this->table)
                     ->where('id',$id)
                     ->update([
                             'status'=>3
                             ]);
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function updateChequeClearStatus($id){
        try{
            $builder = $this->db->table($this->table)
                     ->where('id',$id)
                     ->update([
                             'status'=>1
                             ]);
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getChequeDetailsById($id){
        try{
            $builder = $this->db->table($this->table)
                     ->select('*')
                     ->where('id',$id)
                     ->get();
                    // echo $this->db->getLastQuery();
            return $builder->getResultArray()[0];
        }catch(Exception $e){
            echo $e->getMessage();
        }
       /* $newCnncColl = "SELECT tb1.id,tb1.transaction_id,tb1.cheque_no,tb1.cheque_date,tb1.bank_name,tb1.branch_name,tb1.emp_details_id,tb1.created_on,tb1.status FROM tbl_cheque_details tb1
        INNER JOIN tbl_transaction tb2 ON tb1.related_id = tb2.related_id
        WHERE  tb2.payment_mode='CHEQUE' OR tb2.payment_mode='DD' AND tb1.status=2 
        ";
        $ql= $this->query($newCnncColl);
        $resultnewCnncColl =$ql->getResultArray()[0];
        return $resultnewCnncColl;*/
    }
    public function getAllChequeDetailsByDate($data){
        try{
            $builder = $this->db->table($this->table)
                     ->select('*')
                     ->where('date(created_on)>=',$data['from_date'])
                     ->where('date(created_on)<=',$data['to_date'])
                     ->where('status',3)
                     ->get();
                    // echo $this->db->getLastQuery();
            return $builder->getResultArray();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getChequeDetailsByTransactionId($transaction_id,$from_date){
    try{
      $builder = $this->db->table($this->table)
                ->select('*')
                ->where('transaction_id',$transaction_id)
               // ->where('date(created_on)',$from_date)
                ->whereIn('status',[1,2,3])
                ->get();
      return $builder->getResultArray()[0];
    }catch(Exception $e){
      echo $e->getMessage();
    }
  }
  public function checkTransactionIdExists($transaction_id){
    try{
        $builder = $this->db->table($this->table)
                 ->select('id')
                 ->where('transaction_id',$transaction_id)
                 ->whereIn('status',[1,2,3])
                 ->get();
        $builder = $builder->getFirstRow("array");
        return $builder['id'];
    }catch(Exception $e){
        echo $e->getMessage();
    }
  }
  public function deactivateChequeDetails($id){
    try{
        return $builder = $this->db->table($this->table)
                         ->where('id',$id)
                         ->update([
                                    'status'=>0
                                 ]);
    }catch(Exception $e){
        echo $e->getMessage();
    }
  }

  public function checkExistsChequebyTransactionId($transaction_id)
  {
        $sql="select count(id) as count from tbl_cheque_details where md5(transaction_id::text)='$transaction_id'";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        //echo $this->getLastQuery();
        return $result['count'];

  }
  public function verifyChequeDtl($input)
  {
    // print_r($input);
    // die;
    try
    {
        $sql = "UPDATE tbl_cheque_details set 
        clear_bounce_date='".$input['clear_bounce_date']."',
        remarks='".$input['remarks']."',
        status=".$input['status'].",   
        verification_date=now()
        WHERE id=".$input['id']."";
        if(isset($input['transaction_id']) && !empty($input['transaction_id']))
        {
            $sql.=" and transaction_id = ".$input['transaction_id'];  
        }
        $query_run = $this->db->query($sql);
        //echo $this->db->getLastQuery();die;
    }
    catch(Exception $e)
    {
        echo $e->getMessage();
    }
  }

}