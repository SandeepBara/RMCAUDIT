<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class denial_details_model extends Model
{
    protected $db;
    protected $table = 'tbl_denial_dtl';
    protected $allowedFields = [];
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    
    public function denial_details($input)
    {
		$result = $this->db->table($this->table)->
        insert([
			  "firm_name"=>($input["firm_Name"]!="")?$input["firm_Name"]:null,
			  "owner_name"=>($input["owner_name"]!="")?$input["owner_name"]:null,
			  "holding_no"=>($input["holding_no"]!="")?$input["holding_no"]:null,
			  "ward_mstr_id"=>($input["ward_no"]!="")?$input["ward_no"]:null,
			  "address"=>($input["address"]!="")?$input["address"]:null,
			  "city"=>($input["city"]!="")?$input["city"]:null,
			  "landmark"=>($input["landmark"]!="")?$input["landmark"]:null,
			  "pin_code"=>($input["pin_code"]!="")?$input["pin_code"]:null,
			  "licence_no"=>($input["licence_no"]!="")?$input["licence_no"]:null,
			  "mobile_no"=>($input["mobile_no"]!="")?$input["mobile_no"]:null,
			  "remarks"=>($input["remarks"]!="")?$input["remarks"]:null,
			  "doc_path"=>null,
			  "emp_details_id"=>$input["emp_id"],
			  "created_on"=>$input["created_on"],
			  "status"=>1
			  ]);
		//echo $this->getLastQuery();
		$result = $this->db->insertID();
		return $result;
    }
	
	
	public function update_denial_doc($id,$doc_path)
    {
		$this->db->table($this->table)
				->where('id', $id)
				->set(['doc_path' => $doc_path])
				->update();
	}
	
	public function getdenial_details($id)
    {
        try{
            return $this->db->table($this->table)
					->select('*')
					->where('status',1)
					->where('md5(id::text)',$id)
					->get()
					->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();
        }
	}
	

}