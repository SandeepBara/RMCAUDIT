<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_prop_update_owner_details extends Model
{
	protected $db;
    protected $table = 'tbl_prop_update_owner_details';
    protected $allowedFields = [];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

	public function prop_ownerdetail_insold($input){
		$result = $this->db->table($this->table)->
            insert([
				  "prop_dtl_id"=>$input["prop_dtl_id"],
				  "owner_name"=>$input["old_owner_name"],
				  "relation_type"=>$input["old_relation_type"],
				  "mobile_no"=>$input["old_mobile_no"],
				  "guardiab_name"=>$input["old_guardian_name"],
				  "reason"=>"OLD",
				  "emp_detail_id"=>$input["emp_dtl_id"]
				  ]);
							
				//echo $this->getLastQuery();		
			$result = $this->db->insertID();
			 
		return $result;
	}
	
	public function prop_ownerdetail_insnew($input){
		
        $result = $this->db->table($this->table)->
            insert([
				  "prop_dtl_id"=>$input["prop_dtl_id"],
				  "owner_name"=>$input["owner_name"],
				  "relation_type"=>$input["relation_type"],
				  "mobile_no"=>$input["mobile_no"],
				  "guardiab_name"=>$input["guardian_name"],
				  "reason"=>$input["owner_remark"],
				  "emp_detail_id"=>$input["emp_dtl_id"]
				  ]);
							
				//echo $this->getLastQuery();		
			$result = $this->db->insertID();
			      
		return $result;
	}
	
	
}
?>
