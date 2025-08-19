<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_demand_adjustment extends Model
{
	protected $db;
    protected $table = 'tbl_demand_adjustment';
    protected $allowedFields = ['id','prop_dtl_id','from_qtr','from_fy','to_qtr','to_fy','doc_path','remark','total_amount','created_on','status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }


    public function adjust_demand($data){
        $this->db->table('tbl_demand_adjustment')->
			insert([
				"prop_dtl_id"=>$data['prop_dtl_id'],
				"from_qtr"=>$data['from_fy_mstr_id'],
				"from_fy"=>$data['from_qtr'],
				"to_qtr"=>$data['upto_qtr'],
				"to_fy"=>$data['upto_fy_mstr_id'],
				"doc_path"=>NULL,
				"remarks"=>$data['remark'],
				"total_amount"=>$data['total_amount'],
				"created_on"=>$data['created_on'],
				"status"=>1,
				"deactivated_by_emp_dtl_id" => $data['deactivated_by_emp_dtl_id']
			]);
			return $this->db->insertID();	  
	}
	
	public function update_doc_path($adjust_demandLast_id,$doc_path){
		//print_r($data);
        $result = $this->db->table('tbl_demand_adjustment')->
				where("id", $adjust_demandLast_id)->
				update([
						"doc_path"=>$doc_path
					]);
				//echo $this->db->getLastQuery();
				return $result;
	}
	
	public function pay_status($data)
	{
		//print_r($data);
		$sql1 = "SELECT prop_dtl_id as dmnd_adjst_prop_dtl_id
				FROM tbl_demand_adjustment
				where prop_dtl_id=?";
				$ql= $this->query($sql1, [$data['prop_dtl_id']]);
				$demand_amnt =$ql->getFirstRow('array');
				//echo $this->db->getLastQuery();
		return $demand_amnt;
	}

	
}
?>