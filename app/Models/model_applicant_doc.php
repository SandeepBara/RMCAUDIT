<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_applicant_doc extends Model 
{
    protected $db;
    protected $table = 'tbl_applicant_doc';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id','apply_connection_id','doc_for','document_id','document_path','emp_details_id','remarks','verify_status','verified_by_emp_id','verified_on','created_on','status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }


     public function insertData($input)
     {
        $builder = $this->db->table($this->table)
                            ->insert([
                  "apply_connection_id"=>$input["apply_connection_id"],
                  "doc_for"=>$input["doc_for"],
                  "document_id"=>$input["document_id"],
                  "document_path"=>'',
                  "remarks"=>'',
                  "emp_details_id"=>$input["emp_details_id"],
                  "created_on"=>$input["created_on"],
                  "status"=>'1'
				  ]);
							//echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }

    public function updatedocpathById($applicant_doc_id, $document_path, $document_id=null)
    {
        $arr['document_path'] = $document_path;
        $arr['verify_status'] = 0;
        
        if($document_id!=null)
        $arr['document_id'] = $document_id;

        
        return $builder = $this->db->table($this->table)
                            ->where('id', $applicant_doc_id)
                            ->update($arr);

    }

    public function check_doc_exist($apply_connection_id, $doc_for,$mywoner=array())
    {
        try
        {   if(count($mywoner)==0)
            {
             $builder = $this->db->table($this->table)
                        ->select('*, document_path as doc_path')
                        ->where('apply_connection_id', $apply_connection_id)
                        ->where('doc_for', $doc_for)
                        ->where('status', 1)
                        ->get()
                        ->getFirstRow('array');
            }
            else
            {
                $builder = $this->db->table($this->table)
                        ->select('*, document_path as doc_path')
                        ->where('apply_connection_id', $apply_connection_id)
                        ->where('doc_for', $doc_for)
                        ->where('status', 1)
                        ->where($mywoner)
                        ->get()
                        ->getFirstRow('array');
            }
            //echo $this->db->getLastQuery();
            return $builder;
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }


    public function getdocdet_by_conid($apply_connection_id,$doc_for)
    {
        try
        {
             return $this->db->table($this->table)
                        ->select('*')
                        ->where('apply_connection_id', $apply_connection_id)
                        ->where('doc_for', $doc_for)
                        ->where('status', 1)
                        ->get()
                        ->getResultArray();
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }
    public function getverifieddocdet_by_conid($apply_connection_id,$doc_for)
    {
        try
        {
             return $this->db->table($this->table)
                        ->select('*')
                        ->where('apply_connection_id',$apply_connection_id)
                        ->where('doc_for', $doc_for)
                        ->where('verify_status',1)
                        ->where('status',1)
                        ->get()
                        ->getResultArray();
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function getdocnamedet_by_conid($apply_connection_id,$doc_for)
    {
        $sql = "SELECT a.id, a.apply_connection_id, a.doc_for, a.document_id, d.document_name, a.document_path, a.remarks, a.verify_status,
        a.status FROM tbl_applicant_doc a join tbl_document_mstr d on(d.id=a.document_id) WHERE a.apply_connection_id='$apply_connection_id' AND a.doc_for='$doc_for'";
        $q = $this->db->query($sql);        
        $result = $q->getResultArray();
        //echo $this->db->getLastQuery();
        return $result;
    }

    public function getdocumentdet_by_conid($apply_connection_id,$doc_for,$verify_stts)
    {
        try{
             return $this->db->table($this->table)
                        ->select('*')
                        ->where('apply_connection_id',$apply_connection_id)
                        ->where('doc_for', $doc_for)
                        ->where('verify_status', $verify_stts)
                        ->where('status',1)
                        ->get()
                        ->getFirstRow('array');
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function getdocumentnamedet_by_conid($apply_connection_id,$doc_for,$verify_status){
        $sql = "SELECT a.id, a.apply_connection_id, a.doc_for, a.document_id, d.document_name, a.document_path, a.remarks, a.verify_status,
        a.status FROM tbl_applicant_doc a join tbl_document_mstr d on(d.id=a.document_id) WHERE a.apply_connection_id='$apply_connection_id'
        AND a.doc_for='$doc_for' and a.verify_status='$verify_status'";
        $q = $this->db->query($sql);        
        $result = $q->getFirstRow('array');
        return $result;
    }

    public function count_uploaded_document($apply_connection_id,$doc_for)
    {
        try{
             return $this->db->table($this->table)
                        ->select('count(id) as doc_cnt')
                        ->where('apply_connection_id',$apply_connection_id)
                        ->where('verify_status',0)
                        ->where('doc_for',$doc_for)
                        ->where('status',1)
                        ->get()
                        ->getFirstRow('array');
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
    
    
    public function insertOwnerImgData($input){
        $builder = $this->db->table($this->table)
                            ->insert([
                                        "apply_connection_id"=> $input["apply_connection_id"],
                                        "applicant_detail_id"=> $input["applicant_detail_id"],
                                        "doc_for"=> 'CONSUMER_PHOTO',
                                        "document_id"=> 21,
                                        "document_path"=> '',
                                        "remarks"=> '',
                                        "emp_details_id"=> $input["emp_details_id"],
                                        "created_on"=> $input["created_on"],
                                        "status"=> 1
                                    ]);
		//echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }
    public function insertOwnerData($input){
        $builder = $this->db->table($this->table)
                            ->insert([
                                        "apply_connection_id"=>$input["apply_connection_id"],
                                        "applicant_detail_id"=>$input["applicant_detail_id"],
                                        "doc_for"=>'ID Proof',
                                        "document_id"=>$input["owner_doc_mstr_id"],
                                        "document_path"=>'',
                                        "remarks"=>'',
                                        "emp_details_id"=>$input["emp_details_id"],
                                        "created_on"=>$input["created_on"],
                                        "status"=>'1'
                                    ]);
		//echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }
    public function check_owner_details($apply_connection_id,$applicant_detail_id)
    {
        try{
             //$names = ['10', '11'];
             return $this->db->table($this->table)
                        ->select('id,applicant_detail_id,document_path')
                        ->where('apply_connection_id',$apply_connection_id)
                        ->where('applicant_detail_id',$applicant_detail_id)
                        ->where('status',1)
                        ->get()
                        ->getFirstRow('array');
						//echo $this->db->getLastQuery();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
	public function check_owner_img($apply_connection_id,$applicant_detail_id)
    {
        try{
             $doc_nm = "CONSUMER_PHOTO";
                $data= $this->db->table($this->table)
                        ->select('id,applicant_detail_id,doc_for, doc_for as doc_name,document_path, document_path as doc_path, verify_status')
                        ->where('apply_connection_id',$apply_connection_id)
                        ->where('applicant_detail_id',$applicant_detail_id)
						->where('doc_for',$doc_nm)
                        ->where('status',1)
                        ->get()
                        ->getFirstRow('array');
						//echo $this->db->getLastQuery();
                return $data;
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
	public function check_owner_doc($apply_connection_id,$applicant_detail_id)
    {
        try{
            $doc_nm = "ID Proof";
            $builder = $this->db->table($this->table)
                        ->select('tbl_applicant_doc.id, applicant_detail_id,tbl_applicant_doc.doc_for, tbl_applicant_doc.doc_for as doc_name,document_path, document_path as doc_path, tbl_document_mstr.document_name,tbl_applicant_doc.verify_status')
                        ->join('tbl_document_mstr', 'tbl_document_mstr.id = tbl_applicant_doc.document_id', 'left')
                        ->where('apply_connection_id', $apply_connection_id)
                        ->where('applicant_detail_id', $applicant_detail_id)
						->where('tbl_applicant_doc.doc_for', $doc_nm)
                        ->where('tbl_applicant_doc.status', 1)
                        ->get()
                        ->getFirstRow('array');
            //echo $this->db->getLastQuery();
            return $builder;
			
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
	public function check_other_doc($apply_connection_id,$doc_nm)
    {
        try
        {
             $data= $this->db->table($this->table)
                        ->select('id,applicant_detail_id,document_path')
                        ->where('apply_connection_id',$apply_connection_id)
						->where('doc_for',$doc_nm)
                        ->where('status',1)
                        ->orderBy('id','desc')
                        ->get()
                        ->getFirstRow('array');
						//echo $this->db->getLastQuery();
                        //echo $this->db->getLastQuery();
            return $data;
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }
    
    public function get_ownerimgdetails_by_safid($apply_connection_id,$applicant_detail_id,$doc_for)
    {
        try{

             return $this->db->table($this->table)
                        ->select('*')
                        ->where('apply_connection_id',$apply_connection_id)
                        ->where('applicant_detail_id',$applicant_detail_id)
                        ->where('doc_for',$doc_for)
                        ->where('document_id', 21)
                        ->where('status',1)
                        ->get()
                        ->getResultArray();

                     //   echo $this->getLastQuery();

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function get_verifiedownerimgdetails_by_safid($apply_connection_id,$applicant_detail_id,$doc_for)
    {
        try{

             return $this->db->table($this->table)
                        ->select('*')
                        ->where('apply_connection_id',$apply_connection_id)
                        ->where('applicant_detail_id',$applicant_detail_id)
                        ->where('doc_for',$doc_for)
                        ->where('document_id', 21)
                        ->where('verify_status', 1)
                        ->where('status',1)
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function conownerdocdetbyid($apply_connection_id,$applicant_detail_id,$doc_for){
        $sql = "SELECT a.id, a.apply_connection_id, a.doc_for, a.document_id, d.document_name, a.document_path, a.remarks, a.verify_status,
        a.status FROM tbl_applicant_doc a join tbl_document_mstr d on(d.id=a.document_id) WHERE a.apply_connection_id='$apply_connection_id'
        and a.applicant_detail_id='$applicant_detail_id' AND a.doc_for='$doc_for'";
        $q = $this->db->query($sql);        
        $result = $q->getResultArray();
        //echo $this->db->getLastQuery();

        return $result;
    }
    public function get_details_by_connid($apply_connection_id,$applicant_detail_id,$app_other_doc,$verify_status)
    {
        try
        {
            $builder= $this->db->table($this->table)
                        ->select('*')
                        ->where('apply_connection_id',$apply_connection_id)
                        ->where('applicant_detail_id',$applicant_detail_id)
                        ->where('doc_for',$app_other_doc)
                        ->where('verify_status', $verify_status)
                       // ->where('document_id', 0)
                        ->where('status',1)
                        ->get()
                        ->getFirstRow('array');
                        
            //echo $this->getLastQuery();
            return $builder;
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }
    public function conownerdocnamebydoctype($apply_connection_id,$applicant_detail_id,$doc_for,$verify_status){
        $sql = "SELECT a.id, a.apply_connection_id, a.doc_for, a.document_id, d.document_name, a.document_path, a.remarks, a.verify_status,
        a.status, a.applicant_detail_id FROM tbl_applicant_doc a join tbl_document_mstr d on(d.id=a.document_id) WHERE a.apply_connection_id='$apply_connection_id'
        and a.applicant_detail_id='$applicant_detail_id' AND a.doc_for='$doc_for' and a.verify_status='$verify_status'";
        $q = $this->db->query($sql);        
        $result = $q->getFirstRow('array');
        //echo $this->db->getLastQuery();

        return $result;
    }
    public function  updateappimgdocById($input){
        $builder = $this->db->table($this->table)
                            ->where('id', $input['applicant_img_id'])
                            ->where('verify_status', 0)
                            ->update([
                                    'remarks'=>$input['app_img_remarks'],
                                    'verify_status'=>$input['app_img_verify'],
                                    'verified_by_emp_id'=>$input['emp_details_id'],
                                    'verified_on'=>$input['created_on']
                                    ]);
        //echo $this->db->getLastQuery();
        return $builder;

    }
    
    public function updateappdocById($input){
        return $builder = $this->db->table($this->table)
                            ->where('id', $input['applicant_doc_id'])
                            ->where('verify_status', 0)
                            ->update([
                                    'remarks'=>$input['app_doc_remarks'],
                                    'verify_status'=>$input['app_doc_verify'],
                                    'verified_by_emp_id'=>$input['emp_details_id'],
                                    'verified_on'=>$input['created_on']
                                    ]);

    }
    public function updateprdocById($input){
        return $builder = $this->db->table($this->table)
                            ->where('id', $input['pr_document_id'])
                            ->where('verify_status', 0)
                            ->update([
                                    'remarks'=>$input['pr_remarks'],
                                    'verify_status'=>$input['pr_verify'],
                                    'verified_by_emp_id'=>$input['emp_details_id'],
                                    'verified_on'=>$input['created_on']
                                    ]);
    }
    public function updateapdocById($input){
        return $builder = $this->db->table($this->table)
                            ->where('id', $input['ap_document_id'])
                            ->where('verify_status', 0)
                            ->update([
                                    'remarks'=>$input['ap_remarks'],
                                    'verify_status'=>$input['ap_verify'],
                                    'verified_by_emp_id'=>$input['emp_details_id'],
                                    'verified_on'=>$input['created_on']
                                    ]);
    }
    public function updatecfdocById($input){
        return $builder = $this->db->table($this->table)
                            ->where('id', $input['cf_document_id'])
                            ->where('verify_status', 0)
                            ->update([
                                    'remarks'=>$input['cf_remarks'],
                                    'verify_status'=>$input['cf_verify'],
                                    'verified_by_emp_id'=>$input['emp_details_id'],
                                    'verified_on'=>$input['created_on']
                                    ]);
    }
    public function updateeddocById($input){
        return $builder = $this->db->table($this->table)
                            ->where('id', $input['ed_document_id'])
                            ->where('verify_status', 0)
                            ->update([
                                    'remarks'=>$input['ed_remarks'],
                                    'verify_status'=>$input['ed_verify'],
                                    'verified_by_emp_id'=>$input['emp_details_id'],
                                    'verified_on'=>$input['created_on']
                                    ]);
    }
    public function updatembdocById($input){
        return $builder = $this->db->table($this->table)
                            ->where('id', $input['mb_document_id'])
                            ->where('verify_status', 0)
                            ->update([
                                    'remarks'=>$input['mb_remarks'],
                                    'verify_status'=>$input['mb_verify'],
                                    'verified_by_emp_id'=>$input['emp_details_id'],
                                    'verified_on'=>$input['created_on']
                                    ]);
    }
    public function updatebpldocById($input){
        return $builder = $this->db->table($this->table)
                            ->where('id', $input['bpl_document_id'])
                            ->where('verify_status', 0)
                            ->update([
                                    'remarks'=>$input['bpl_remarks'],
                                    'verify_status'=>$input['bpl_verify'],
                                    'verified_by_emp_id'=>$input['emp_details_id'],
                                    'verified_on'=>$input['created_on']
                                    ]);
    }
    public function verified_rej_owner_img_details($apply_connection_id,$applicant_detail_id)
    {
        try{
            $doc_for="consumer_photo";
            $stts = ['1', '2'];
            return $this->db->table($this->table)
                        ->select('id,applicant_detail_id,document_path,remarks,verify_status')
                        ->where('apply_connection_id',$apply_connection_id)
                        ->whereIn('verify_status',$stts)
                        ->where('doc_for',$doc_for)
                        ->where('applicant_detail_id',$applicant_detail_id)
                        ->where('document_id', 0)
                        ->where('status',1)
                        ->orderBy('id','DESC')
                        ->get()
                        //echo $this->db->getLastQuery();
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }


    public function check_ownerdtl_img_details($apply_connection_id,$applicant_detail_id)
    {
        try{
            $doc_for="consumer_photo";
            return $this->db->table($this->table)
                        ->select('id,applicant_detail_id,document_path')
                        ->where('apply_connection_id',$apply_connection_id)
                        ->where('verify_status',1)
                        ->where('doc_for',$doc_for)
                        ->where('applicant_detail_id',$applicant_detail_id)
                        ->where('document_id', 0)
                        ->where('status',1)
                        ->get()
                        ->getFirstRow('array');
            //echo $this->db->getLastQuery();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function check_newownerdtl_img_details($apply_connection_id,$applicant_detail_id)
    {
        try{
            $doc_for="consumer_photo";
             return $this->db->table($this->table)
                        ->select('id,applicant_detail_id,document_path')
                        ->where('apply_connection_id',$apply_connection_id)
                        ->where('verify_status',0)
                        ->where('doc_for',$doc_for)
                        ->where('applicant_detail_id',$applicant_detail_id)
                        ->where('document_id', 0)
                        ->where('status',1)
                        ->get()
                        ->getFirstRow('array');
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function check_verified_doc_exist($apply_connection_id,$doc_for)
    {
        try{

             return $this->db->table($this->table)
                        ->select('id,applicant_detail_id,document_path')
                        ->where('apply_connection_id',$apply_connection_id)
                        ->where('verify_status',1)
                        ->where('doc_for',$doc_for)
                        ->where('status',1)
                        ->get()
                        ->getFirstRow('array');
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function check_new_doc_exist($apply_connection_id,$doc_for)
    {
        try{

             return $this->db->table($this->table)
                        ->select('id,applicant_detail_id,document_path')
                        ->where('apply_connection_id',$apply_connection_id)
                        ->where('verify_status',0)
                        ->where('doc_for',$doc_for)
                        ->where('status',1)
                        ->get()
                        ->getFirstRow('array');
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function verified_rej_document_details($apply_connection_id,$other_doc)
    {
        try{
            $stts = ['1', '2'];
              return $this->db->table($this->table)
                        ->select('id,applicant_detail_id,document_path,remarks,verify_status,document_id')
                        ->where('apply_connection_id',$apply_connection_id)
                        ->whereIn('verify_status',$stts)
                        ->where('doc_for',$other_doc)
                        ->where('status',1)
                        ->orderBy('id','DESC')
                        ->get()
            //echo $this->db->getLastQuery();
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
	
	public function applicantDocDetails($apply_connection_id)
    {
        try{
            return $this->db->table($this->table)
                        ->select('doc_for,document_path,remarks,verify_status,document_id')
                        ->where('apply_connection_id',$apply_connection_id)
                        ->where('status',1)
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    
    public function getAllDocumentList($apply_connection_id)
    {
        $builder = $this->db->table($this->table)
                        ->join("tbl_document_mstr", "tbl_document_mstr.id=tbl_applicant_doc.document_id")
                        ->select('tbl_applicant_doc.*, document_name')
                        ->where('apply_connection_id', $apply_connection_id)
                        ->where('tbl_document_mstr.status', 1)
                        ->where('tbl_applicant_doc.status', 1)
                        ->orderBy('applicant_detail_id, tbl_applicant_doc.id','ASC')
                        ->get();
        //echo $this->getLastQuery();
        return $builder->getResultArray();
    }

    public function getAllActiveDocuments($apply_connection_id)
    {
        $builder = $this->db->table($this->table)
                            ->select('tbl_applicant_doc.*, coalesce(tbl_applicant_doc.doc_for, tbl_document_mstr.doc_for) as doc_name, tbl_document_mstr.document_name,tbl_document_mstr.doc_for as doc_group')
                            ->join('tbl_document_mstr', 'tbl_document_mstr.id = tbl_applicant_doc.document_id', 'left')
                            ->where('apply_connection_id', $apply_connection_id)
                            ->where('tbl_applicant_doc.status', 1)
                            ->get();
        //echo $this->db->getLastQuery();
        return $builder->getResultArray();
    }

}                