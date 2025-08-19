<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_advance_mstr extends Model
{
	protected $db;
    protected $table = 'tbl_advance_mstr';
    protected $allowedFields = [''];

    public function __construct(ConnectionInterface $db)
	{
        $this->db = $db;
    }
    
	
	public function transaction_adjust($input)
	{
        $result = $this->db->table($this->table)->
            insert([
                  "prop_dtl_id"=>$input["prop_dtl_id"],
				  "amount"=>$input["amount"],
				  "transaction_id"=>$input["transaction_id"],
				  "created_on"=>$input["created_on"],
				  "reason"=>$input["reason"],
				  "module"=>"Property",
				  "adjust_by_emp_details_id"=>$input['emp_details_id']
			]);
		return $result;
	}
	
	public function adjust_amount($data)
    {
		try{
			$sql="select (select sum(amount) as total_amnt from tbl_advance_mstr where md5(prop_dtl_id::text)='".$data."' group by prop_dtl_id),
			(select coalesce(amount,0) as rest_amnt from tbl_adjustment_mstr where md5(prop_dtl_id::text)='".$data."')
			from tbl_advance_mstr
			where md5(prop_dtl_id::text)='".$data."' and module='Property'";
			$run=$this->db->query($sql);
			$result=$run->getFirstRow("array");
			//echo $this->getLastQuery();
			return $result;
		}catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
	public function adjust_amount_insert($input)
	{
        $result = $this->db->table($this->table)->
            insert([
                  "prop_dtl_id"=>$input["prop_dtl_id"],
				  "amount"=>$input["amount"],
				  "remarks"=>$input["remarks"],
				  "created_on"=>$input["created_on"],
				  "reason"=>$input["reason"],
				  "module"=>"Property",
				  "adjust_by_emp_details_id"=>$input['emp_details_id']
				]);
		return $result = $this->db->insertID();
	}
	
	public function adjust_amount_update($adjust_id,$doc_path){
		//print_r($data);
        $result = $this->db->table('tbl_advance_mstr')->
				where("id", $adjust_id)->
				update([
						"doc"=> $doc_path
					]);
				//echo $this->db->getLastQuery();
				return $result;
	}

	public function advance_amnt($data)
    {
		$sql="select sum(tbl_advance_mstr.amount) as amount, tbl_adjustment_mstr.amount as rst_amnt
		from tbl_advance_mstr 
		left join tbl_adjustment_mstr on tbl_adjustment_mstr.prop_dtl_id = tbl_advance_mstr.prop_dtl_id
		where prop_dtl_id='".$data."'
		group by prop_dtl_id";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
		//echo $this->getLastQuery();
        return $result;
    }
	
	public function advance_amnt_update($id,$rest_advnce){
		//print_r($data);
        $result = $this->db->table('tbl_advance_mstr')->
				where("id", $id)->
				update([
						"amount"=> $rest_advnce
					]);
				//echo $this->db->getLastQuery();
				return $result;
	}
	
	public function deactivateAdvance($trxn_id)
	{
		$result = $this->db->table($this->table)
				->where("transaction_id", $trxn_id)
				->update([
						"status"=> 0, //deactivate advance amount
					]);
		//echo $this->db->getLastQuery();
		return $result;
	}

	public function advance_amnt2023($transaction_id)
    {
		$sql="select amount from tbl_advance_mstr 
			where tbl_advance_mstr.transaction_id=".$transaction_id." and tbl_advance_mstr.status=1 and created_on::date>='2023-03-01'
			and module='Property' and remarks='Advance Payment due to avg. calculation of 2022-2023'";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
		//echo $this->getLastQuery();
        return $result['amount'];
    }
}
?>
