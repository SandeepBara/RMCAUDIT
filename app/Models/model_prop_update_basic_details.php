<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_prop_update_basic_details extends Model
{
	protected $db;
    protected $table = 'tbl_prop_update_basic_details';
    protected $allowedFields = [];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

	public function prop_detail_insold($input){
		
        $result = $this->db->table($this->table)->
            insert([
				  "prop_dtl_id"=>$input["prop_dtl_id"],
				  "ward_no"=>$input["old_ward_mstr_id"],
				  "new_ward_no"=>$input["old_new_ward_mstr_id"],
				  "plot_no"=>$input["old_plot_no"],
				  "khata"=>$input["old_khata_no"],
				  "mauja_name"=>$input["old_mauja_name"],
				  "address"=>$input["old_prop_address"],
				  "reason"=>"OLD",
				  "emp_detail_id"=>$input["emp_dtl_id"]
				  ]);
							
				//echo $this->getLastQuery();		
			$result = $this->db->insertID();
			      
		return $result;
	}
	
	public function prop_detail_insnew($input){
		
        $result = $this->db->table($this->table)->
            insert([
				  "prop_dtl_id"=>$input["prop_dtl_id"],
				  "ward_no"=>$input["ward_mstr_id"],
				  "new_ward_no"=>$input["new_ward_mstr_id"],
				  "plot_no"=>$input["plot_no"],
				  "khata"=>$input["khata_no"],
				  "mauja_name"=>$input["mauja_name"],
				  "address"=>$input["prop_address"],
				  "reason"=>$input["loc_remark"],
				  "emp_detail_id"=>$input["emp_dtl_id"]
				  ]);
							
				//echo $this->getLastQuery();		
			$result = $this->db->insertID();
			      
		return $result;
	}
	
	
}
?>
