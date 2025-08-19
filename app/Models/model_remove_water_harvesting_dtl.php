<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_remove_water_harvesting_dtl extends Model 
{
    protected $db;
    protected $table = 'tbl_remove_water_harvesting_dtl';

    protected $primaryKey = 'id';

    protected $allowedFields = [];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }


     public function remove_additionaltax($input){
        $builder = $this->db->table($this->table)
                ->insert([
                  "prop_dtl_id"=>$input["custm_id"],
                  "remove_date"=>$input["crntdate"],
                  "remove_doc_path"=>null,
                  "remarks"=>$input["remarks"],
                  "fy_mstr_id"=>$input["due_upto_year"],
				  "qtr"=>$input["date_upto_qtr"],
                  "created_by_emp_details_id"=>$input["emp_details_id"],
                  "created_on"=>$input["created_on"],
                  "status"=>'1'
				  ]);
			//echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }

	public function update_remove_additionaltax($applicant_doc_id,$document_path){
		//print_r($document_path);
        return $builder = $this->db->table($this->table)
                            ->where('id', $applicant_doc_id)
                            ->update([
                                    'remove_doc_path'=>$document_path
                                    ]);
									//echo $this->db->getLastQuery();

    }
	
	
	public function waterharvest_reports($from,$to)
    { 

        $sql="SELECT tbl1.remove_date,tbl1.remove_doc_path,tbl1.remarks,tbl1.qtr,view_fy_mstr.fy,tbl2.owner_name,tbl2.mobile_no,tbl2.holding_no
		FROM tbl_remove_water_harvesting_dtl tbl1
		LEFT JOIN view_fy_mstr ON tbl1.fy_mstr_id = view_fy_mstr.id
		LEFT JOIN view_prop_dtl_owner_ward_prop_type_ownership_type tbl2 ON tbl1.prop_dtl_id = tbl2.prop_dtl_id
		where tbl1.status=1 and tbl1.remove_date between '".$from."' and '".$to."'";
        $q = $this->db->query($sql);        
        $result = $q->getResultArray();
        return $result;
        //echo $this->db->getLastQuery();
    }

    
    

}                