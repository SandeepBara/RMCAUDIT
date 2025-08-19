<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_view_application_doc extends Model
{
    protected $db;
    protected $table = 'view_application_doc ';
    protected $allowedFields = ['id','apply_licence_id','verify_status','status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function verified_reject_owner_doc_dtl($apply_licence_id,$firm_owner_dtl_id)
    {
        try{
            $other_doc="Identity Proof";
            $stts = ['1', '2','0'];
             return $this->db->table($this->table)
                        ->select('id,firm_owner_dtl_id,document_path,remarks,verify_status,document_id,doc_name')
                        ->where('apply_licence_id',$apply_licence_id)
                        ->whereIn('verify_status',$stts)
                        ->where('doc_for',$other_doc)
                        ->where('firm_owner_dtl_id',$firm_owner_dtl_id)
                        ->where('status',1)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function check_ownerdtl_doc_details($apply_licence_id,$firm_owner_dtl_id)
    {
        try{
            $other_doc="Identity Proof";
             return $this->db->table($this->table)
                        ->select('id,firm_owner_dtl_id,document_path')
                        ->where('apply_licence_id',$apply_licence_id)
                        ->where('verify_status',1)
                        ->where('doc_for',$other_doc)
                        ->where('firm_owner_dtl_id',$firm_owner_dtl_id)
                        ->where('status',1)
                        ->get()
                        ->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function check_ownerdtll_doc_details($apply_licence_id,$firm_owner_dtl_id)
    {
        try{
            $other_doc="Identity Proof";
             return $this->db->table($this->table)
                        ->select('id,firm_owner_dtl_id,document_path')
                        ->where('apply_licence_id',$apply_licence_id)
                        ->where('verify_status',0)
                        ->where('doc_for',$other_doc)
                        ->where('firm_owner_dtl_id',$firm_owner_dtl_id)
                        ->where('status',1)
                        ->get()
                        ->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function verified_rej_tr_doc_details($apply_connection_id,$other_doc)
    {
        try{
            $stts = ['1', '2'];
             return $this->db->table($this->table)
                        ->select('id,applicant_detail_id,document_path,remarks,verify_status,document_name,document_id')
                        ->where('apply_connection_id',$apply_connection_id)
                        ->whereIn('verify_status',$stts)
                        ->where('doc_for',$other_doc)
                        ->where('status',1)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function safownerdocdetbyid($id,$applicant_detail_id,$doc_type)
    {
        try{
            $builder = $this->db->table('view_saf_doc_dtl ')
                        ->select('*')
                        ->where('apply_connection_id',$id)
                        ->where('applicant_detail_id',$applicant_detail_id)
                         ->where('doc_for',$doc_type)
                        ->where('status',1)
                        ->get();

           return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function get_verifiedownerdocdetails_by_conid($apply_licence_id,$firm_owner_dtl_id,$doc_for)
    {
        try{

             $data = $this->db->table($this->table)
                        ->select('*')
                        ->where('apply_licence_id',$apply_licence_id)
                        ->where('firm_owner_dtl_id',$firm_owner_dtl_id)
                        ->where('doc_for',$doc_for)
                        //->where('verify_status', 1)
                        ->where('status',1)
                        ->get()
                        ->getResultArray();
                //echo $this->db->getLastQuery();
            return $data;
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function get_verifiedaddressdocdetails_by_conid($apply_connection_id,$doc_for)
    {
        try{

             return $this->db->table($this->table)
                        ->select('*')
                        ->where('apply_connection_id',$apply_connection_id)
                        ->where('doc_for',$doc_for)
                        ->where('verify_status', 1)
                        ->where('status',1)
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

}
?> 