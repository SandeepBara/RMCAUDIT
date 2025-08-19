<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_view_saf_doc_dtl extends Model
{
    protected $db;
    protected $table = 'view_saf_doc_dtl ';
    protected $allowedFields = ['id','saf_dl_id','saf_no','holding_no','ward_mstr_id','owner_name','mobile_no','receiver_emp_details_id','status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function safdocListbysafid($id)
    {
        try{
            $builder = $this->db->table('view_saf_doc_dtl ')
                        ->select('*')
                        ->where('saf_dtl_id',$id)
                        ->where('status',1)
                        ->get();

           return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function getOwnerDocDtlBySafIdAndOwnerDtlId($saf_dtl_id, $saf_owner_dtl_id)
    {
        try{

             return $this->db->table($this->table)
                        ->select('*')
                        ->where('saf_dtl_id', $saf_dtl_id)
                        ->where('saf_owner_dtl_id', $saf_owner_dtl_id)
                        ->whereIn('status', [1, 2])
						//->where('verify_status', 0)
                        ->get()
                        ->getFirstRow("array");
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    
    public function safownerdocnamebydoctype($id,$saf_owner_dtl_id,$doc_type,$verify_status)
    {
        try{
            $builder = $this->db->table('view_saf_doc_dtl ')
                        ->select('*')
                        ->where('saf_dtl_id',$id)
                        ->where('saf_owner_dtl_id',$saf_owner_dtl_id)
                         ->where('doc_type',$doc_type)
                        ->where('verify_status', $verify_status)
                        //->where('status',1)
                        ->get();
           return $builder->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function safownerdocdetbyid($id,$saf_owner_dtl_id,$doc_type)
    {
        try{
            $builder = $this->db->table('view_saf_doc_dtl ')
                        ->select('*')
                        ->where('saf_dtl_id',$id)
                        ->where('saf_owner_dtl_id',$saf_owner_dtl_id)
                         ->where('doc_type',$doc_type)
                        ->where('status',1)
                        ->get();

           return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function getDocDtlByDocTypeAndSafDtlId($id)
    {
        try{
            $builder = $this->db->table('view_saf_doc_dtl')
                        ->select('*')
                        ->where('saf_dtl_id',$id)
                        ->where('saf_owner_dtl_id IS NULL')
                        ->where('status',1)
						->where('verify_status', 0)
                        ->get();
            //echo $this->db->getLastQuery();
            return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function safdocnamebydoctype($id,$doc_type,$verify_status)
    {
        try{
            $builder = $this->db->table('view_saf_doc_dtl ')
                        ->select('*')
                        ->where('saf_dtl_id',$id)
                        ->where('doc_type',$doc_type)
                        ->where('verify_status', $verify_status)
                        ->where('status',1)
                        ->get();
            //echo $this->db->getLastQuery();
           return $builder->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
	public function safdocflatbydoctype($id,$doc_type,$verify_status)
    {
        try{
            $builder = $this->db->table('view_saf_doc_dtl ')
                        ->select('*')
                        ->where('saf_dtl_id',$id)
                        ->whereIn('doc_type',$doc_type)
                        ->where('verify_status', $verify_status)
                        ->where('status',1)
                        ->get();
            //echo $this->db->getLastQuery();
           return $builder->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
	
    public function count_applicant_doc_new_upload($id)
    {
        $doc_type='other';
        try{
            $builder = $this->db->table('view_saf_doc_dtl ')
                        ->select('count(*) as app_doc_cnt')
                        ->where('saf_dtl_id',$id)
                         ->where('doc_type',$doc_type)
                        ->where('verify_status',0)
                         ->where('status',1)
                        ->get();

           return $builder->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function count_tr_new_upload($id)
    {
        $doc_type='transfer_mode';
        try{
            $builder = $this->db->table('view_saf_doc_dtl ')
                        ->select('count(*) as tr_doc_cnt')
                        ->where('saf_dtl_id',$id)
                         ->where('doc_type',$doc_type)
                        ->where('verify_status',0)
                         ->where('status',1)
                        ->get();

           return $builder->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function count_pr_new_upload($id)
    {
        $doc_type='property_type';
        try{
            $builder = $this->db->table('view_saf_doc_dtl ')
                        ->select('count(*) as pr_doc_cnt')
                        ->where('saf_dtl_id',$id)
                         ->where('doc_type',$doc_type)
                        ->where('verify_status',0)
                         ->where('status',1)
                        ->get();

           return $builder->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function count_no_electric_connection_new_upload($id)
    {
        $doc_type='no_elect_connection';
        try{
            $builder = $this->db->table('view_saf_doc_dtl ')
                        ->select('count(*) as no_electric_connection_doc_cnt')
                        ->where('saf_dtl_id',$id)
                         ->where('doc_type',$doc_type)
                        ->where('verify_status',0)
                         ->where('status',1)
                        ->get();

           return $builder->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
	
	public function count_super_new_upload($id)
    {
        $doc_type='super_structure_doc';
        try{
            $builder = $this->db->table('view_saf_doc_dtl ')
                        ->select('count(*) as super_doc_cnt')
                        ->where('saf_dtl_id',$id)
                         ->where('doc_type',$doc_type)
                        ->where('verify_status',0)
                         ->where('status',1)
                        ->get();

           return $builder->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
    public function count_flat_new_upload($id)
    {
        $doc_type='flat_doc';
        try{
            $builder = $this->db->table('view_saf_doc_dtl ')
                        ->select('count(*) as flat_doc_cnt')
                        ->where('saf_dtl_id',$id)
                         ->where('doc_type',$doc_type)
                        ->where('verify_status',0)
                         ->where('status',1)
                        ->get();

           return $builder->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }


    public function verified_doc_list_by_safid($id, $doc_type)
    {
        try
        {
            $builder = $this->db->table('view_saf_doc_dtl ')
                                ->select('*')
                                ->where('saf_dtl_id', $id)
                                ->where('doc_type', $doc_type)
                                //->where('verify_status', 1)
                                //->where('status', 1)
                                ->get();
            //echo $this->db->getLastQuery();
            return $builder->getResultArray()[0];
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }

    
    public function notverified_doc_list_by_safid($id,$doc_type)
    {
        try{
            $builder = $this->db->table('view_saf_doc_dtl ')
                        ->select('*')
                        ->where('saf_dtl_id',$id)
                         ->where('doc_type',$doc_type)
                        ->where('verify_status',2)
                         ->where('status',1)
                        ->get();

           return $builder->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function uploaded_doc_list_by_safid($id,$doc_type)
    {
        try{
            $builder = $this->db->table('view_saf_doc_dtl ')
                        ->select('*')
                        ->where('saf_dtl_id',$id)
                         ->where('doc_type',$doc_type)
                         ->where('status',1)
                        ->get();

           return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function verified_reject_owner_doc_dtl($saf_id,$saf_owner_dtl_id)
    {
        try{
            $other_doc="other";
            $stts = ['1', '2', '0'];
             return $this->db->table($this->table)
                        ->select('id,saf_owner_dtl_id,doc_path,remarks,verify_status,doc_mstr_id,doc_name')
                        ->where('saf_dtl_id',$saf_id)
                        ->whereIn('verify_status',$stts)
                        ->where('doc_type',$other_doc)
                        ->where('saf_owner_dtl_id',$saf_owner_dtl_id)
                        ->where('status',1)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray();
						//echo $this->db->getLastQuery();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
	public function verified_rej_super_doc_details($saf_id,$sup_doc)
    {
		//print_r($sup_doc);
        try{
            $stts = ['1', '2', '0'];
            return $this->db->table($this->table)
				->select('id,saf_owner_dtl_id,doc_path,remarks,verify_status,doc_name,doc_mstr_id')
				->where('saf_dtl_id',$saf_id)
				->whereIn('verify_status',$stts)
				->where('doc_mstr_id', $sup_doc)
				->where('status',1)
				->orderBy('id','DESC')
				->get()
				->getResultArray();
				//echo $this->db->getLastQuery();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
	public function verified_rej_flat_doc_details($saf_id)
    {
		//print_r($sup_doc);
        try{
            $stts = ['1', '2', '0'];
			$flat_doc = ['7', '18'];
            return $this->db->table($this->table)
				->select('id,saf_owner_dtl_id,doc_path,remarks,verify_status,doc_name,doc_mstr_id')
				->where('saf_dtl_id',$saf_id)
				->whereIn('verify_status',$stts)
				->whereIn('doc_mstr_id', $flat_doc)
				->where('status',1)
				->orderBy('id','DESC')
				->get()
				->getResultArray();
				//echo $this->db->getLastQuery();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
    public function verified_rej_tr_doc_details($saf_id,$tr_doc)
    {
        try{
            $stts = ['1', '2', '0'];
             return $this->db->table($this->table)
                        ->select('id,saf_owner_dtl_id,doc_path,remarks,verify_status,doc_name,doc_mstr_id')
                        ->where('saf_dtl_id',$saf_id)
                        ->whereIn('verify_status',$stts)
                        ->whereIn('doc_mstr_id', $tr_doc)
                        ->where('status',1)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function verified_rej_pr_doc_details($saf_id,$pr_doc)
    {
        try{
            $stts = ['1', '2', '0'];
             return $this->db->table($this->table)
                        ->select('id,saf_owner_dtl_id,doc_path,remarks,verify_status,doc_name,doc_mstr_id')
                        ->where('saf_dtl_id',$saf_id)
                        ->whereIn('verify_status',$stts)
                        ->whereIn('doc_mstr_id', $pr_doc)
                        ->where('status',1)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function check_ownerdtl_doc_details($saf_id,$saf_owner_dtl_id)
    {
        try{
            $other_doc="other";
            return $this->db->table($this->table)
                        ->select('id,saf_owner_dtl_id,doc_path')
                        ->where('saf_dtl_id',$saf_id)
                        ->where('doc_type',$other_doc)
                        ->where('saf_owner_dtl_id',$saf_owner_dtl_id)
                        ->where('status',1)
                        ->get()
                        ->getResultArray()[0];
						//echo $this->db->getLastQuery();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function check_ownerdtll_doc_details($saf_id,$saf_owner_dtl_id)
    {
        try{
            $other_doc="other";
             return $this->db->table($this->table)
                        ->select('id,saf_owner_dtl_id,doc_path')
                        ->where('saf_dtl_id',$saf_id)
                        ->where('verify_status',0)
                        ->where('doc_type',$other_doc)
                        ->where('saf_owner_dtl_id',$saf_owner_dtl_id)
                        ->where('doc_mstr_id', 11)
                        ->where('status',1)
                        ->get()
                        ->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

}
?> 