<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class TradeChequeDtlModel extends Model
{
  protected $db;
  protected $table = 'tbl_cheque_dtl';
  protected $allowedFields = ['id', 'transaction_id', 'cheque_no', 'cheque_date', 'bank_name', 'branch_name', 'emp_details_id', 'created_on', 'status'];

  public function __construct(ConnectionInterface $db)
  {
    $this->db = $db;
  }

  public function insertdata($input)
  {

    $result = $this->db->table($this->table)
      ->insert($input);
    //echo $this->getLastQuery();		
    $result = $this->db->insertID();


    return $result;
  }

  public function insertDatbankaexcel($input)
  {
    try {
      $builder = $this->db->table('tbl_bank_recancilation')
        ->insert([
          "created_on" => $input['created_on'],
          "emp_details_id" => $input['emp_details_id'],
          "cheque_dtl_id" => $input['cheque_dtl_id'],
          "transaction_id" => $input['transaction_id'],
          "amount" => $input['bounce_amount'],
          "related_id" => $input['apply_licence_id']

        ]);
      echo $this->db->getLastQuery();
      return $insert_id = $this->db->insertId();
    } catch (Exception $e) {
      echo $e->getMessage();
    }
  }

  public function alltransactioncheque_details($data)
  {
    // print_var($data);die;
    $result = $this->db->table($this->table)
      ->select("*")
      ->where('md5(transaction_id::text)', $data)
      ->get();

    // echo $this->getLastQuery();	                          
    return $result->getResultArray()[0] ?? [];
  }


  // Using in Cash Verification
  public function BankReconciliation($from_date, $upto_date)
  {

    $sql = "select tbl_transaction.*, transaction_no as tran_no, transaction_date as tran_date, transaction_type as tran_type, paid_amount as payable_amt, tbl_cheque_dtl.id as cheque_dtl_id,cheque_no,cheque_date,bank_name,branch_name 
        from tbl_transaction 
        join tbl_cheque_dtl on tbl_cheque_dtl.transaction_id=tbl_transaction.id
        where upper(payment_mode) in ('CHEQUE', 'DD') and transaction_date between '$from_date' and '$upto_date'";
    //print_var($sql);
    $run = $this->db->query($sql);
    $result = $run->getResultArray();
    //echo $this->getLastQuery();
    return $result;
  }

  public function tradeChequeDetailsByDate($data)
  {
    try {
      $builder = $this->db->table($this->table)
        ->select('*')
        ->where('date(created_on)>=', $data['from_date'])
        ->where('date(created_on)<=', $data['to_date'])
        ->whereIn('status', [1, 2])
        ->orderBy('date(created_on), cheque_no', 'DESC')
        ->get();
      //echo $this->db->getLastQuery();
      return $builder->getResultArray();
    } catch (Exception $e) {
      echo $e->getMessage();
    }
  }
  public function getTradeChequeDetailsById($id)
  {
    try {
      $builder = $this->db->table($this->table)
        ->select('*')
        ->where('id', $id)
        ->where('status!=', 0)
        ->get();
      return $builder->getResultArray()[0];
    } catch (Exception $e) {
      echo $e->getMessage();
    }
  }
  public function updateTradeChequeClearStatus($id)
  {
    try {
      $builder = $this->db->table($this->table)
        ->where('id', $id)
        ->update([
          'status' => 1,
        ]);
      // echo $this->db->getLastQuery();
    } catch (Exception $e) {
      echo $e->getMessage();
    }
  }
  public function updateTradeNotClearStatus($id)
  {
    try {
      $builder = $this->db->table($this->table)
        ->where('id', $id)
        ->update([
          'status' => 3,
        ]);
      // echo $this->db->getLastQuery();
    } catch (Exception $e) {
      echo $e->getMessage();
    }
  }
  public function getTradeChequeDetails($data)
  {
    try {
      $builder = $this->db->table($this->table)
        ->select('*')
        ->where('date(created_on)>=', $data['from_date'])
        ->where('date(created_on)<=', $data['to_date'])
        ->where('status', 3)
        ->get();
      //echo $this->db->getLastQuery();
      return $builder->getResultArray();
    } catch (Exception $e) {
      echo $e->getMessage();
    }
  }
  public function getChequeDetailsByTransactionId($transaction_id, $from_date)
  {
    try {
      $builder = $this->db->table($this->table)
        ->select('*')
        ->where('transaction_id', $transaction_id)
        //->where('date(created_on)',$from_date)
        ->where('status>', 0)
        ->get();
      return $builder->getResultArray()[0];
    } catch (Exception $e) {
      echo $e->getMessage();
    }
  }
  public function getBankDetails($transaction_id)
  {
    try {
      $builder = $this->db->table($this->table)
        ->select('*')
        ->where('transaction_id', $transaction_id)
        ->where('status', 3)
        ->get();
      return $builder->getResultArray()[0];
    } catch (Exception $e) {
      echo $e->getMessage();
    }
  }
  public function checkTradeTransactionIdExists($transaction_id)
  {
    try {
      $builder = $this->db->table($this->table)
        ->select('id')
        ->where('transaction_id', $transaction_id)
        ->whereIn('status', [1, 2, 3])
        ->get();
      $builder = $builder->getFirstRow("array");
      return $builder['id'];
    } catch (Exception $e) {
      echo $e->getMessage();
    }
  }
  public function tradeChequeDeactivate($id)
  {
    try {
      return $builder = $this->db->table($this->table)
        ->where('id', $id)
        ->update([
          'status' => 0
        ]);
    } catch (Exception $e) {
      echo $e->getMessage();
    }
  }


  public function cheque_details($data)
  {
    try {

      $builder = $this->db->table($this->table)
        ->select('*')
        ->where('transaction_id', $data)
        ->get();
      return $builder->getResultArray()[0] ?? [];
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }

  // get cheque details by id 
  public function get_check_details($transaction_id)
  {

    $result = $this->db->table($this->table)
      ->select("*")
      ->where('transaction_id', $transaction_id)
      ->get();
    //echo $this->getLastQuery();                           
    return $result->getResultArray()[0];
  }

  public function verifyChequeDtl($input)
  {
    // print_r($input);
    // die;
    try
    {
        $sql = "UPDATE tbl_cheque_dtl set 
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
        //echo $this->db->getLastQuery();
    }
    catch(Exception $e)
    {
        echo $e->getMessage();
    }
  }
  public function row_sql($sql)
  {
      $run=$this->db->query($sql);
      $result=$run->getResultArray();

      return $result;
  }
}
