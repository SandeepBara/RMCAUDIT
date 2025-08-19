<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_cheque_details extends Model
{
      protected $db;
      protected $table = 'tbl_cheque_details';
      protected $allowedFields = [];

      public function __construct(ConnectionInterface $db){
          $this->db = $db;
      }

      public function mode_dtl($transaction_id_md5)
      {
      
          try
          {    
            $builder = $this->db->table("tbl_cheque_details")
                        ->select('*')
                        ->where('md5(transaction_id::text)', $transaction_id_md5)
                        ->get();
            $result = $builder->getFirstRow('array');
            //echo $this->db->getLastQuery();
            return $result;

          }
          catch(Exception $e){
            return $e->getMessage();   
          }
      }

    public function chequeDetails($data){
        try{
            $builder = $this->db->table($this->table)
                      ->select('*')
                      ->where('date(created_on)>=',$data['from_date'])
                      ->where('date(created_on)<=',$data['to_date'])
                      ->where('status!=',0)
                       ->orderBy("date(created_on),cheque_no","DESC")
                      ->get();
                     //echo $this->getLastQuery();
            return $builder->getResultArray();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function updateBounceStatusClear($id){
        try{
            return $builder = $this->db->table($this->table)
                     ->where('id',$id)
                     ->update([
                                'status'=>1
                              ]);
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
	 public function updateBounceStatusNotClear($transaction_id){
        try{
            return $builder = $this->db->table($this->table)
                     ->where('transaction_id',$transaction_id)
                     ->update([
                                'status'=>3
                              ]);
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
	public function chqDDdetails($input){
		$chqDDdetails = $this->db->table('tbl_cheque_details')->
			insert([
				  "prop_dtl_id"=>$input["custm_id"],
				  "transaction_id"=>$input['insertPayment'],
				  "cheque_no"=>$input["chq_no"],
				  "cheque_date"=>$input["chq_date"],
				  "bank_name"=>$input["bank_name"],
				  "branch_name"=>$input["branch_name"],
				  "bounce_status"=>0,
				  "created_on"=>$input["date"],
				  "status"=>2
				  ]);
      //echo $this->getLastQuery();
	}
  public function allChequeDetails(){
        try{
            $builder = $this->db->table($this->table)
                      ->select('*')
                      ->where('bounce_status',0)
                      ->where('status',1)
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
                  ->where('status',1)
                  ->where('md5(id::text)',$id)
                  ->get();
                  //echo $this->getLastQuery();
          return $builder->getFirstRow('array');
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
      return $builder->getFirstRow('array');
    }catch(Exception $e){
      echo $e->getMessage();
    }
  }
  public function getAllChequeDetails($data){
    try{
        $builder =$this->db->table($this->table)
                 ->select('*')
                 ->where('date(created_on)>=',$data['from_date'])
                 ->where('date(created_on)<=',$data['to_date'])
                 ->where('status',3)
                 ->get();
                 //echo $this->db->getLastQuery();
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
                ->whereIn('status',[1,2])
                ->get();
      return $builder->getFirstRow('array');
    }catch(Exception $e){
      echo $e->getMessage();
    }
  }
  public function getNotverifiedCheque($transaction_id){
    try{
        $builder = $this->db->table($this->table)
                 ->select('*')
                 ->where('transaction_id',$transaction_id)
                 ->whereIn('status',[1,2])
                 ->get();
        return $builder->getFirstRow('array');
    }catch(Exception $e){
      echo $e->getMessage();
    }
  }
  public function chechPropertyChequeDetails($transaction_id){
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
  public function propertyChequeDeactivate($id){
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

  public function verifyChequeDtl($input)
  {
    // print_r($input);
    // die;
    try
    {
      $sql = "UPDATE tbl_cheque_details set 
      clear_bounce_date='".$input['clear_bounce_date']."',
      remarks='".$input['remarks']."',
      bounce_amount=".$input['bounce_amount'].",
      status=".$input['status'].",
      bounce_status=".$input['bounce_status'].",
      verification_date=now()
      WHERE id=".$input['id']."";
      // die;
      $query_run = $this->db->query($sql);
      // echo "insert id";
      // print_r($this->query_run->getResutlArray());
            //echo $this->db->getLastQuery();
        // return  $this->db->insertId();
    }catch(Exception $e){
        echo $e->getMessage();
    }
  }

public function verifyGBChequeDtl($input)
{
  try {
    $sql = "UPDATE tbl_govt_saf_transaction_details set 
          clear_bounce_date='" . $input['clear_bounce_date'] . "',
          remarks='" . $input['remarks'] . "',
          bounce_amount=" . $input['bounce_amount'] . ",
          status=" . $input['status'] . ",
          bounce_status=" . $input['bounce_status'] . ",
          verification_date=now()
          WHERE id=" . $input['id'] . "";
          return $this->db->query($sql);
  } catch (Exception $e) {
    echo $e->getMessage();
  }
}

public function updateGBTransactionStatus($t_id, $t_status) {
  try {
    $sql = "UPDATE tbl_govt_saf_transaction set status=" . $t_status . " where id=" . $t_id . "";
    return $this->db->query($sql);
  } catch (Exception $e) {
    echo $e->getMessage();
  }
}
public function insertPenalty($input)
  {
    try
    {
       $sql = "INSERT INTO tbl_penalty_dtl
      (prop_dtl_id,
      penalty_amt,
      penalty_type,
      created_on,
      status,
      transaction_id,
      module)
      VALUES
      (".$input['prop_dtl_id'].",
      ".$input['penalty_amt'].",
      '".$input['penalty_type']."',
      now(),
      ".$input['status'].",
      '".$input['transaction_id']."',
      '".$input['module']."')
      ";

      $query_run = $this->db->query($sql);
    
            //echo $this->db->getLastQuery();
        return  $this->db->insertId();
    }catch(Exception $e){
        echo $e->getMessage();
    }
  }

  public function getPropDtlByTransaction($id){
    // echo $id." /";
    try{
         $sql = "SELECT prop_dtl_id,tran_type from tbl_transaction where id=".$id."";
        $qry_run = $this->db->query($sql);
        // print_r($qry_run->getResultArray()[0]['prop_dtl_id']);
        // die;
        return $qry_run->getResultArray()[0];
    }catch(Exception $e){
        echo $e->getMessage();
    }
}
  public function updateTransactionStatus($t_id,$t_status){
    // echo $id." /";
    try{
         $sql = "UPDATE tbl_transaction set status=".$t_status." where id=".$t_id."";
        // die;
        $qry_run = $this->db->query($sql);
        // print_r($qry_run->getResultArray()[0]['prop_dtl_id']);
        // die;
        // return $qry_run->getResultArray()[0];
    }catch(Exception $e){
        echo $e->getMessage();
    }
}
}
?>