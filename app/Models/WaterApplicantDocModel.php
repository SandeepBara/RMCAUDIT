<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class WaterApplicantDocModel extends Model 
{
    protected $db;
    protected $table = 'tbl_applicant_doc';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id','apply_connection_id','doc_for','document_id','document_path','emp_details_id','remarks','verify_status','verified_by_emp_id','verified_on','created_on','status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function insertData(array $data)
    {
        
        $result= $this->db->table($this->table)
                 ->insert($data);       
       // echo $this->getLastQuery();
        $insert_id=$this->db->insertID();
        return $insert_id;

    }


    public function updateDocumentPath($id,$doc_name)
    {
        $sql="update tbl_applicant_doc set document_path='".$doc_name."' where id=$id";
        $run=$this->query($sql);
        
        
    }
    

}                