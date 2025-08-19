<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_harvesting_declaration_dtl extends Model 
{
    protected $db;
    protected $table = 'tbl_harvesting_declaration_dtl';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id', 'prop_dtl_id','declaration_date','doc_upload_path','remarks','ward_mstr_id', 'created_on','created_by_emp_details_id', 'status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function insrtdeclarationdtl($input){
		//print_r($input);
        $builder = $this->db->table($this->table)
                ->insert([
                  "prop_dtl_id"=>$input["prop_dtl_id"],
                  "declaration_date"=>$input["declaration_date"],
                  "remarks"=>$input["remarks"],
                  "ward_mstr_id"=>$input["ward_mstr_id"],
                  "created_on"=>$input["created_on"],
                  "created_by_emp_details_id"=>$input["created_by_emp_details_id"],
                  "status"=>'1'
				  ]);
			$this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }
    public function updatedocpathById($harvesting_declaration_last_id,$declaration_doc_path){
        return $builder = $this->db->table($this->table)
                            ->where('id',$harvesting_declaration_last_id)
                            ->update([
                                    'declaration_doc_path'=>$declaration_doc_path
                                    ]);
    }
    public function declaration_dtl_by_propdtlid($prop_dtl_id)
    {
        try{
             return $this->db->table($this->table)
                        ->select('*')
                        ->where('prop_dtl_id', $prop_dtl_id)
                        ->where('status ',1)
                        ->get()
                        ->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

}