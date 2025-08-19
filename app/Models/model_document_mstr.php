<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_document_mstr extends Model
{
	protected $db;
    protected $table = 'tbl_document_mstr';
    protected $allowedFields = ['id','document_name','doc_for','status'];

    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }

   

    public function getDocumentList($address_proof_doc_for)
    {
        try
        {
            $builder = $this->db->table($this->table)
                        ->select('id, document_name,document_name as doc_name,doc_for,status,is_mandatory')
                        ->where('doc_for', $address_proof_doc_for)
                        ->where('status', 1)
                        ->orderBy('id','ASC')
                        ->get();
                       // echo $this->getLastQuery();
            return $builder->getResultArray();
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }
	
	
    public function get_docname_data_bydoc_id($id,$doc_type)
    { 
       // $this->db1 = $this->load->database('db', TRUE);

        $sql="SELECT d.doc_name,d.id,t.transfer_mode FROM tbl_saf_doc_collected_dtl s join tbl_doc_mstr d on(s.doc_mstr_id=d.id) join tbl_transfer_mode_mstr t on(d.doc_id=t.id) where d.doc_type='$doc_type'  and s.saf_distributed_dtl_id='$id'";
        $q = $this->db->query($sql);        
        $result2 = $q->getResultArray();
        return $result2;
    }
}
?>