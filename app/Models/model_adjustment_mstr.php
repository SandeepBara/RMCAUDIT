<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_adjustment_mstr extends Model
{
    protected $table = 'tbl_adjustment_mstr';
    protected $allowedFields = [''];
	
	public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
	
	public function rest_advnce_insrt($data)
    {
		$resultAdv = $this->db->table('tbl_adjustment_mstr')->
			insert([
				  "prop_dtl_id"=>$data["custm_id"],
				  "amount"=>$data['advance'],
				  "transaction_id"=>$data['insertPayment'],
				  "created_on"=>$data["date"],
				  "status"=>1
				]);
	}
	
	public function rest_advnce_update($data){
		$sql = "SELECT amount
			FROM tbl_adjustment_mstr
			where prop_dtl_id=?";
			$ql= $this->query($sql, [$data["custm_id"]]);
			$advn =$ql->getFirstRow('array');
			
		$amntupdate = $advn['amount'] + $data['total_payabl'];
        $result = $this->db->table('tbl_adjustment_mstr')->
			where("prop_dtl_id", $data["custm_id"])->
			update([
					"amount"=>$amntupdate,
					"transaction_id"=>$data['insertPayment']
				]);
				
	}
	
	public function check_rest_advnce($data)
    {
		$sql = "SELECT prop_dtl_id
			FROM tbl_adjustment_mstr
			where prop_dtl_id=?";
			$ql= $this->query($sql, [$data]);
			$advn =$ql->getFirstRow('array');
			return $advn;
	}
	
	public function rst_advnc_amnt($data)
    {
		 $sql="select (select sum(amount) as total_amnt from tbl_advance_mstr where prop_dtl_id='".$data['prop_dtl_id']."' group by prop_dtl_id),
		(select coalesce(amount,0) as rest_amnt from tbl_adjustment_mstr where prop_dtl_id='".$data['prop_dtl_id']."')
		from tbl_adjustment_mstr
		where transaction_id='".$data['id']."'";

		// die;

        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
		//echo $this->getLastQuery();
        return $result;
		
	}
	
	public function deactivateAdjustment($trxn_id)
	{
		$result = $this->db->table($this->table)
				->where("transaction_id", $trxn_id)
				->update([
						"status"=> 0, //deactivate advance amount
					]);
		//echo $this->db->getLastQuery();
		return $result;
	}

}
?>