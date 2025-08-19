<?php
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_govt_saf_transaction_details extends Model
{
	protected $db;
    protected $table = 'tbl_govt_saf_transaction_details';
    protected $allowedFields = [];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    
	public function chqDDdetails($input){
		$chqDDdetails = $this->db->table('tbl_govt_saf_transaction_details')->
			insert([
				  "govt_saf_dtl_id"=>$input["custm_id"],
				  "govt_saf_transaction_id"=>$input['insertPayment'],
				  "cheque_no"=>$input["chq_no"],
				  "cheque_date"=>$input["chq_date"],
				  "bank_name"=>$input["bank_name"],
				  "branch_name"=>$input["branch_name"],
				  "bounce_status"=> 0,
				  "status"=> 2
				  ]);
      //echo $this->getLastQuery();
	}
	
	
	public function mode_dtl($data){
        try{        
            $builder = $this->db->table("tbl_govt_saf_transaction_details")
                        ->select('*')
                        ->where('md5(govt_saf_transaction_id::text)', $data)
                        ->get();
           return $builder->getFirstRow('array');
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
    
}
?>
