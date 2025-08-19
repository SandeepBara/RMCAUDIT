<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_saf_doc_collected_dtl extends Model 
{
    protected $db;
    protected $table = 'tbl_saf_doc_collected_dtl';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id','saf_distributed_dtl_id','doc_mstr_id','created_on','status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }


     public function trinsertData($input){
        $builder = $this->db->table($this->table)
                            ->insert([
                  "saf_distributed_dtl_id"=>$input["saf_distributed_dtl_id"],
                  "doc_mstr_id"=>$input["trans_doc_mstr_id"],
                  "created_on"=>$input["created_on"],
                  "status"=>'1'
				  ]);
							//echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }
    public function prinsertData($input){
        $builder = $this->db->table($this->table)
                            ->insert([
                  "saf_distributed_dtl_id"=>$input["saf_distributed_dtl_id"],
                  "doc_mstr_id"=>$input["prop_doc_mstr_id"],
                  "created_on"=>$input["created_on"],
                  "status"=>'1'
				  ]);
							//echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }

    public function get_tr_mode_data($id)
    { 
       // $this->db1 = $this->load->database('db', TRUE);

        $sql="SELECT d.doc_name,t.transfer_mode FROM tbl_saf_doc_collected_dtl s join tbl_doc_mstr d on(s.doc_mstr_id=d.id) join tbl_transfer_mode_mstr t on(d.doc_id=t.id) where d.doc_type='transfer_mode'  and md5(s.saf_distributed_dtl_id::text)='$id'";
        $q = $this->db->query($sql);        
        $result2 = $q->getResultArray();
        return $result2[0];
    }
     public function get_pr_mode_data($id)
    { 
       // $this->db1 = $this->load->database('db', TRUE);

        $sql="SELECT d.doc_name,t.property_type FROM tbl_saf_doc_collected_dtl s join tbl_doc_mstr d on(s.doc_mstr_id=d.id) join tbl_prop_type_mstr t on(d.doc_id=t.id) where d.doc_type='property_type'  and md5(s.saf_distributed_dtl_id::text)='$id'";
        $q = $this->db->query($sql);        
        $result2 = $q->getResultArray();
        return $result2[0];
    }
     public function get_other_data($id)
    { 
       // $this->db1 = $this->load->database('db', TRUE);

        $sql="SELECT d.doc_name FROM tbl_saf_doc_collected_dtl s join tbl_doc_mstr d on(s.doc_mstr_id=d.id)  where d.doc_type='other' and s.saf_distributed_dtl_id='$id'";
        $q = $this->db->query($sql);
        $result2 = $q->getResultArray();
        return $result2;
    }

}