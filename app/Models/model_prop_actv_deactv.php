<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_prop_actv_deactv extends Model
{
	protected $db;
    protected $table = 'tbl_prop_actv_deactv';
    protected $allowedFields = [];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

	public function prop_deactive_active($input){
		
		$date = date('Y-m-d');
        $result = $this->db->table($this->table)->
            insert([
				  "prop_dtl_id"=>$input["prop_dtl_id"],
				  "ward_no"=>$input["ward_no"],
				  "holding_no"=>$input["holding_no"],
				  "reason"=>$input["remarks"],
				  "action_type"=>$input["action_type"],
				  "action_date"=>$date,
				  "emp_detail_id"=>$input["emp_dtl_id"]
				  ]);
							
				//echo $this->getLastQuery();		
			$result = $this->db->insertID();
			      
		return $result;
	}
	public function updatedocpathById($data) {
         return $this->db->table($this->table)
                ->where('id', $data['prop_deactive'])
                ->update([
                    'doc_path'=>$data['reason_doc_file_path']
                ]); 
    }
	
	
}
?>
