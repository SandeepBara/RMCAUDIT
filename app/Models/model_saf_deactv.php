<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_saf_deactv extends Model
{
	protected $db;
    protected $table = 'tbl_saf_deactv';
    protected $allowedFields = [];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

	public function saf_deactive($input){
		//print_r($input);
		$date = date('Y-m-d');
        $result = $this->db->table($this->table)->
            insert([
				  "saf_dtl_id"=>$input["saf_dtl_id"],
				  "ward_no"=>$input["ward_no"],
				  "saf_no"=>$input["saf_no"],
				  "reason"=>$input["remarks"],
				  "action_type"=>"Deactivate",
				  "action_date"=>$date,
				  "emp_detail_id"=>$input["emp_dtl_id"]
				  ]);
							
				//echo $this->getLastQuery();		
			$result = $this->db->insertID();
			        
		return $result;
	}
	
	
	
}
?>
