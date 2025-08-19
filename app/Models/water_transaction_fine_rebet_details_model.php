<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class water_transaction_fine_rebet_details_model extends Model
{

    protected $table = 'tbl_transaction_fine_rebet_details';
    protected $allowedFields = ['id','transaction_id','head_name','value_add_minus','amount','created_on','status'];
	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
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

    public function getInstallmentRebatePaid($apply_connection_id)
    {
        $sql="select coalesce(sum(amount),0) as installment_rebate from tbl_transaction_fine_rebet_details where apply_connection_id=$apply_connection_id and status=1 and head_name='Installment Rebate'";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        // echo $this->getLastQuery();
        return $result['installment_rebate'];
        
        
    }


}