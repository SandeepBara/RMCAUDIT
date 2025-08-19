<?php
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_govt_saf_transaction extends Model
{
	protected $db;
    protected $table = 'tbl_govt_saf_transaction';
    protected $allowedFields = [];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

	// Using In Cash Verification
	public function GBSafPaymentList($tran_by_emp_details_id, $tran_date){

		$sql="select tbl_govt_saf_transaction.id as transaction_id, tran_no, tran_mode, from_qtr, from_fyear, upto_qtr, upto_fyear, payable_amt, tbl_govt_saf_transaction.govt_saf_dtl_id,    
		application_no, building_colony_name, office_name, building_colony_address , ward_no, tran_verification_status, view_emp_details.emp_name as verified_by, tran_verify_datetime
		from tbl_govt_saf_transaction
		join tbl_govt_saf_dtl on tbl_govt_saf_dtl.id=tbl_govt_saf_transaction.govt_saf_dtl_id
		left join view_ward_mstr on view_ward_mstr.id=tbl_govt_saf_dtl.ward_mstr_id
		left join view_emp_details on view_emp_details.id=tbl_govt_saf_transaction.tran_verify_by_emp_details_id
		where tran_by_emp_details_id=$tran_by_emp_details_id and tran_date='$tran_date' and tbl_govt_saf_transaction.status in (1, 2)";
		//print_var($sql);
		$sql= $this->db->query($sql);
        //echo $this->db->getLastQuery();
        return $result = $sql->getResultArray();
	}

	// Using In Cash Verification
	function CashVerify($gbsaf_trxn_ids, $verified_by)
    {
        $builder = $this->db->table($this->table)
    				 ->wherein('id', $gbsaf_trxn_ids)
    				 ->update([
    				 			'tran_verification_status' => 1,
                                'tran_verify_by_emp_details_id' => $verified_by,
                                'tran_verify_datetime' => 'NOW()',
    				 		  ]);
        //echo $this->db->getLastQuery().'gbsaf';
        return $builder;
    }

	
    public function getTransactionById($id){
        try{
            $builder = $this->db->table($this->table)
                      ->select('*')
                      ->where('md5(id::text)',$id)
                      ->whereIn('status',[1,2])
                      ->get();
            return $builder->getFirstRow('array');
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    
	public function payment_detail($input)
    { 
		$sql = "SELECT * FROM tbl_govt_saf_transaction where status in (1,2) AND govt_saf_dtl_id=?";
        $ql= $this->query($sql, [$input]);
		//echo $this->db->getLastQuery();
		if($ql){
			return $ql->getResultArray();
		}else{
			return false;
		}
    }
	
	public function checkPayment($data)
	{
		$sql = "SELECT govt_saf_dtl_id
				FROM tbl_govt_saf_transaction
				where govt_saf_dtl_id=? AND status In(1,2)";
				$ql= $this->query($sql, [$data["custm_id"]]);
				// $result =$ql->getFirstRow('array');
				$result =$ql->getResultArray();
				return $result;
	}
	
	
	public function govsafinsertPayment($input){
			//print_r($input);
		$input["pnlty"] =$input['latefine'] + $input['tol_pently'];
        $result = $this->db->table($this->table)->
            insert([
				  "tran_no"=>NULL,
				  "penalty_amt"=>isset($input["pnlty"])?$input["pnlty"]:0,
				  "from_fy_mstr_id"=>$input["from_fy_year"],
				  "from_qtr"=>$input["from_fy_qtr"],
				  "remarks"=>'Payment Done By Citizen',
                  "govt_saf_dtl_id"=>$input["custm_id"],
				  "tran_by_emp_details_id"=>$input['emp_details']['id'],
				  "upto_fy_mstr_id"=>$input["due_upto_year"],
				  "upto_qtr"=>$input["date_upto_qtr"],
				  "discount_amt"=>$input["rebate"],
				  "payable_amt"=>$input["total_payabl"],
				  "tran_mode_mstr_id"=>$input["payment_mode"],
				  "tran_date"=>$input["date"],
				  "round_off"=>$input["round_off"],
				  "status"=>($input["payment_mode"]==1)?"1":"2",
				  "ward_mstr_id"=>($input["ward_mstr_id"]!="")?$input["ward_mstr_id"]:null
				  ]);
							//echo $this->db->getLastQuery();
							
			$result = $this->db->insertID();
			
			$input["tran_no"]= "GBTRAN".date('d').$result.date('Y').date('m').date('s');

			$this->db->table($this->table)
				     ->where('id', $result)
				     ->set(['tran_no' => $input["tran_no"]])
				     ->update();
			
			return $result;
			
	}
	
	
	public function getTrandtlList($trxn_id_md5)
    {
		try{        
            $builder = $this->db->table("tbl_govt_saf_transaction")
                        ->select('*')
                        ->where('md5(id::text)', $trxn_id_md5)
                        ->get();
			//echo $this->db->getLastQuery();
           return $builder->getFirstRow('array');
        }catch(Exception $e){
            return $e->getMessage();   
        }
        
    }
  
  public function updateVerificationStatuCashCollection($employee_id,$verified_date,$id,$tran_date){
        try{
            return $builder = $this->db->table($this->table)
                     ->where('md5(tran_by_emp_details_id::text)',$employee_id)
                     ->where('tran_mode_mstr_id',1)
                     ->where('tran_date',$tran_date)
                     ->update([
                                'tran_verification_status' =>1,
                                'tran_verify_by_emp_details_id' =>$id,
                                'tran_verify_datetime' =>$verified_date
                              ]);
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
  public function updateVerificationStatus($login_emp_id,$transaction_id,$verified_date)
  {
	    try{
	        return $builder = $this->db->table($this->table)
	                 ->where('id',$transaction_id)
	                 ->update([
	                            'tran_verification_status' =>1,
	                            'tran_verify_datetime' =>$verified_date,
	                            'tran_verify_by_emp_details_id' =>$login_emp_id
	                          ]);
	               //  echo $this->db->getLastQuery();
	    }catch(Exception $e){
	        echo $e->getMessage();
	    }
   }
  public function getTransactionWithChequeDetails($where)
  {
	//   print_r($this->db->getDatabase());
	//   die;
       $sql="select tbl_govt_saf_transaction.*,'GBSaf' as tran_type,
	   			tbl_govt_saf_transaction.tran_verification_status as verify_status,
				transaction_mode as payment_mode,tbl_govt_saf_transaction_details.id as cheque_dtl_id,
				tbl_govt_saf_transaction_details.cheque_no,
				tbl_govt_saf_transaction_details.clear_bounce_date,tbl_govt_saf_transaction_details.remarks as clear_bounce_remarks,
				tbl_govt_saf_transaction_details.cheque_date,tbl_govt_saf_transaction_details.bank_name,
				tbl_govt_saf_transaction_details.branch_name 
	   		from tbl_govt_saf_transaction 
			join tbl_govt_saf_transaction_details on tbl_govt_saf_transaction_details.govt_saf_transaction_id=tbl_govt_saf_transaction.id 
			join tbl_tran_mode_mstr on tbl_tran_mode_mstr.id=tbl_govt_saf_transaction.tran_mode_mstr_id $where";
      $run=$this->db->query($sql);
      $result=$run->getResultArray();
    //   echo $this->getLastQuery();
      return $result;

  }
  public function updateStatusClear($transaction_id)
  {
  		$sql="update tbl_govt_saf_transaction set status=1 where id=$transaction_id";
  		$run=$this->db->query($sql);
  }
  public function updateBounceStatus($transaction_id)
  {
  		$sql="update tbl_govt_saf_transaction set status=3 where id=$transaction_id";
  		$run=$this->db->query($sql);
  }
    
}
?>
