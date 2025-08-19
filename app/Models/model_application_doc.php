<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_application_doc extends Model 
{
    protected $db;
    protected $table = 'tbl_application_doc';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id','apply_licence_id','doc_for','document_id','document_path','emp_details_id','remarks','verify_status','verified_by_emp_id','verified_on','created_on','status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function doc_count_ver_sts_1($apply_licence_id)
    {
        try{
              $builder = $this->db->table($this->table)
                        ->select('verify_status')
                        ->where('apply_licence_id',$apply_licence_id)                        
                        ->where('status',1)
                       // ->where('verify_status',1)
                        ->where('lvl_pending_id =
                        (SELECT MAX(lvl_pending_id) FROM  tbl_application_doc)')
                        ->get()
                        ->getResultArray();
                       //echo $this->db->getLastQuery();
                        return $builder;
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }


    public function insertData($input)
    {
		//print_var($input);print_var($this->db);exit;
        $builder = $this->db->table($this->table)
                ->insert([
                  "apply_licence_id"=>$input["apply_licence_id"],
                  "doc_for"=>$input["doc_for"],
                  "document_id"=>0,
                  "document_path"=>'',
                  "remarks"=>'',
				  "firm_owner_dtl_id"=>$input["firm_owner_dtl_id"] ?? NULL,
                  "emp_details_id"=>$input["emp_details_id"],
                  "created_on"=>$input["created_on"],
                  "status"=>'1'
				]);
		//echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }

	public function updateownerdocpathById($applicant_doc_id,$firm_owner_dtl_id,$document_path,$doc_id){
        return $builder = $this->db->table($this->table)
                            ->where('id', $applicant_doc_id)
							->where('firm_owner_dtl_id', $firm_owner_dtl_id)
                            ->update([
                                    'document_path'=>$document_path,
									'document_id'=>$doc_id
                                    ]);

    }
	
	public function updatedocpathById($applicant_doc_id ,$document_path, $doc_id){
         $builder = $this->db->table($this->table)
                            ->where('id', $applicant_doc_id)
                            ->update([
                                    'document_path'=> $document_path,
									'document_id'=> $doc_id
                                    ]);
        //echo $this->db->getLastQuery();die;
        return $builder;

    }

    public function check_upload_doc_count($apply_licence_id)
    {
        try
        {
            $builder = $this->db->table($this->table)
                        ->select('document_id')
                        ->where('apply_licence_id', $apply_licence_id) 
                        ->where('firm_owner_dtl_id', NULL)                     
                        ->where('status', 1)
                        ->where('verify_status', 0)
                        ->get()
                        ->getResultArray();
            //echo $this->db->getLastQuery();
            return $builder;
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }

    

    //check reupload doc count
    public function check_upload_doc_count_all($apply_licence_id)
    {
         
        try
        {
              $builder = $this->db->table($this->table)
                        ->selectCount('id')
                        ->where('apply_licence_id', $apply_licence_id) 
                         ->where('status', 1)
                        ->where('verify_status', 0)
                        ->get()
                        ->getResultArray()[0];
                        //echo $this->db->getLastQuery();exit;
                        return $builder;
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }

    

    public function check_upload_bo_doc_count($apply_licence_id)
    {
        
        try{
              $builder = $this->db->table($this->table)
                        ->selectCount('id')
                        ->where('apply_licence_id',$apply_licence_id)                        
                        ->where('status',1)
                        ->where('verify_status',2)
                        ->where('lvl_pending_id =
                        (SELECT MAX(lvl_pending_id) FROM  tbl_application_doc)')
                        ->get()
                        ->getResultArray()[0];
                       //echo $this->db->getLastQuery();exit;
                        return $builder;
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function updateverifiedById($input){      
        return $builder = $this->db->table($this->table)
                            ->where('apply_licence_id', $input['apply_licence_id'])
                            ->where('verify_status', 0)
                            ->update([ 
                                    'remarks'=>$input['app_doc_remarks'],                                   
                                    'verify_status'=> 1,
                                    'verified_by_emp_id'=> $input['emp_details_id'],
                                    'verified_on'=> "NOW()"
                                    ]);
                           /* echo $this->db->getLastQuery();
                            return $builder;*/

    }

    public function check_doc_exist($apply_licence_id,$doc_for)
    {
        try
        {
            $doc = $this->db->table($this->table)
                        ->select('id,verify_status,document_path')
                        ->where('apply_licence_id',$apply_licence_id)
                        ->where('doc_for', $doc_for)
                        ->where('status',1)
						->orderBy('id','DESC')
                        ->get();
            
			//echo $this->db->getLastQuery();echo '<br>';
			return $doc->getFirstRow('array');
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }

     public function check_doc_exist_owner($apply_licence_id,$owner_id,$document_id=null)
    {
        //echo "<br>".$document_id;
        try{
             $doc = $this->db->table($this->table)
                        ->select('id,verify_status,document_path,document_id')
                        ->where('apply_licence_id',$apply_licence_id)
                        ->where('firm_owner_dtl_id', $owner_id)
                        ->where('status',1);
            if($document_id!==null)
            {
                $document_id = (int)$document_id;
                $doc = $doc->where('document_id',$document_id);
            }
            else
                $doc = $doc->where('document_id <>',0);

            $doc = $doc->orderBy('id','DESC')
                    ->get()
                    ->getResultArray()[0];
            
            //echo $this->db->getLastQuery();
            if($doc)
            {
                return $doc;
            } 
            else 
            { 
                return $doc=0;
            }
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }

    public function count_doc_exist_owner($apply_licence_id,$owner_id)
    {
        $wherein = "firm_owner_dtl_id in ($owner_id)";
        try{
              $doc = $this->db->table($this->table)
                        ->select('count(id)')
                        ->where('apply_licence_id',$apply_licence_id)
                        ->where($wherein)
                        ->where('status',1)
                        //->groupby('id','DESC')
                        ->get()
                        ->getResultArray()[0];
                        //echo $this->db->getLastQuery();
                       // print_r($doc);exit;
                        
                if($doc){return $doc;} else { return $doc=0;}
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function count_rejected_document($apply_licence_id,$doc_for)
    {
        try{
             return $this->db->table($this->table)
                        ->select('count(id) as doc_cnt')
                        ->where('apply_licence_id',$apply_licence_id)
                        ->where('verify_status',2)
                        ->where('doc_for',$doc_for)
                        ->where('status',1)
                        ->get()
                        ->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function count_upload_document($apply_licence_id,$doc_for)
    {
        try{
             return $this->db->table($this->table)
                        ->select('count(id) as doc_cnt')
                        ->where('apply_licence_id',$apply_licence_id)                        
                        ->where('doc_for',$doc_for)
                        ->where('status',1)
                        ->get()
                        ->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
	
	public function check_upload_owner_doc_exist($data)
    {
		
        try{
             return $this->db->table($this->table)
                        ->select('id,document_path')
                        ->where('apply_licence_id',$data['apply_licence_id'])
						->where('firm_owner_dtl_id',$data['firm_owner_dtl_id'])
                        ->where('doc_for', $data['doc_for'])
                        ->where('status',1)
						->where('verify_status',0)
                        ->get()
                        ->getResultArray()[0];
						//echo $this->db->getLastQuery();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
	public function check_upload_doc_exist($data)
    {
        try
        {
            $builder= $this->db->table($this->table)
                        ->select('id, document_path')
                        ->where('apply_licence_id',$data['apply_licence_id'])
                        ->where('doc_for', $data['doc_for'])
                        ->where('status',1)
						//->whereIN('verify_status', [0,2])
                        ->get();
            //echo $this->db->getLastQuery();
            return $builder->getFirstRow('array');
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }

    //check uploaded doc list owner
    public function check_upload_doc_exist_owner($data,$doc_for=null)
    {
        try
        {
            $sql ="select id, document_path from tbl_application_doc where apply_licence_id=$data[apply_licence_id] and firm_owner_dtl_id=$data[firm_owner_dtl_id] and status=1 and verify_status=0 ";
            if($doc_for)
            {
                $sql.=" and doc_for='$doc_for' ";
            }
            else
            {
                $sql.='and document_id != 0 ';
            }

            $sql.=" order by id desc limit 1";
            $builder=$this->db->query($sql);        
            $builder = $builder->getFirstRow('array');
            //echo $this->db->getLastQuery();exit;
            return $builder;

            /*
            $builder= $this->db->table($this->table)
                        ->select('id, document_path')
                        ->where('apply_licence_id', $data['apply_licence_id'])
                        ->where('firm_owner_dtl_id', $data['firm_owner_dtl_id'])
                        ->where('status', 1)
                        ->where('verify_status', 0)
                        ->get()
                        ->getResultArray()[0];
            echo $this->db->getLastQuery();
            */
        }
        catch(Exception $e)
        {
            print_var($e->getMessage());
            return $e->getMessage();   
        }
    }

    //reupload check data exist 
    public function check_upload_doc_exist_reupload($data)
    {   //print_var($data);
        if(isset($data['firm_owner_dtl_id']) && $data['firm_owner_dtl_id'])
        {
            try{
                    $data=$this->db->table($this->table)
                           ->select('id,document_path')
                           ->where('apply_licence_id',$data['apply_licence_id'])
                           ->where('firm_owner_dtl_id', $data['firm_owner_dtl_id'])
                           ->where('doc_for', $data['doc_for'])
                           ->where('status',1)
                           ->where('verify_status',0)
                           ->orderBy('id','desc')
                           ->get()                           
                           ->getFirstRow('array');
                           //echo $this->db->getLastQuery();
                    return $data;
           }
           catch(Exception $e)
           {
               return $e->getMessage();   
           }
        }
        else
        {
            try{
                return $this->db->table($this->table)
                           ->select('id,document_path')
                           ->where('apply_licence_id',$data['apply_licence_id'])
                           ->where('doc_for', $data['doc_for'])
                           ->where('status',1)
                           ->where('verify_status',0)
                           ->orderBy('id','desc')
                           ->get()
                           ->getFirstRow('array');
                           //echo $this->db->getLastQuery();
           }
           catch(Exception $e)
           {
               return $e->getMessage();   
           }
        }
       
         
    }
	
	
    public function check_agreement_doc_exist($apply_licence_id)
    {
        try{
            $doc_for = ['rent_agreement', 'lease_agreement', 'electricity_bill'];
             return $this->db->table($this->table)
                        ->select('id')
                        ->where('apply_licence_id',$apply_licence_id)
                        ->whereIn('doc_for', $doc_for)
                        ->where('status',1)
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    

    public function getdocdet_by_conid($apply_licence_id,$doc_for)
    {
        try
        {
             return $this->db->table($this->table)
                        ->select('*')
                        ->where('apply_licence_id',$apply_licence_id)
                        ->where('doc_for', $doc_for)
                        ->where('status',1)
                        ->get()
                        ->getResultArray();
						//echo $this->db->getLastQuery();
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }
    public function getaggdocdet_by_conid($apply_licence_id)
    {
        try
        {
            $doc_for = ['rent_agreement', 'lease_agreement', 'electricity_bill'];
            return $this->db->table($this->table)
                        ->select('*')
                        ->where('apply_licence_id',$apply_licence_id)
                        ->whereIn('doc_for', $doc_for)
                        ->where('status',1)
                        ->get()
                        ->getResultArray();
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function getverifieddocdet_by_conid($apply_licence_id,$doc_for)
    {
        try{
             return $this->db->table($this->table)
                        ->select('*')
                        ->where('apply_licence_id',$apply_licence_id)
                        ->where('doc_for', $doc_for)
                        ->where('verify_status',1)
                        ->where('status',1)
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
    
    public function getaggverifieddocdet_by_conid($apply_licence_id)
    {
        try{
            $doc_for = ['rent_agreement', 'lease_agreement', 'electricity_bill'];
             return $this->db->table($this->table)
                        ->select('*')
                        ->where('apply_licence_id',$apply_licence_id)
                        ->whereIn('doc_for', $doc_for)
                        ->where('verify_status',1)
                        ->where('status',1)
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function getdocnamedet_by_conid($apply_connection_id,$doc_for){
        $sql = "SELECT a.id, a.apply_connection_id, a.doc_for, a.document_id, d.document_name, a.document_path, a.remarks, a.verify_status,
        a.status FROM tbl_applicant_doc a join tbl_document_mstr d on(d.id=a.document_id) WHERE a.apply_connection_id='$apply_connection_id' AND a.doc_for='$doc_for'";
        $q = $this->db->query($sql);        
        $result = $q->getResultArray();
        //echo $this->db->getLastQuery();

        return $result;
    }

    public function getdocumentdet_by_conid($apply_licence_id,$doc_for,$verify_stts)
    {
        try{
             return $this->db->table($this->table)
                        ->select('*')
                        ->where('apply_licence_id',$apply_licence_id)
                        ->where('doc_for', $doc_for)
                        ->where('verify_status', $verify_stts)
                        ->where('status',1)
                        ->get()
                        ->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function getaggrdocumentdet_by_conid($apply_licence_id,$verify_stts)
    {
        try{
            $doc_for = ['rent_agreement', 'lease_agreement', 'electricity_bill'];
             return $this->db->table($this->table)
                        ->select('*')
                        ->where('apply_licence_id',$apply_licence_id)
                        ->whereIn('doc_for', $doc_for)
                        ->where('verify_status', $verify_stts)
                        ->where('status',1)
                        ->get()
                        ->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function getdocumentnamedet_by_conid($apply_connection_id,$doc_for,$verify_status){
        $sql = "SELECT a.id, a.apply_connection_id, a.doc_for, a.document_id, d.document_name, a.document_path, a.remarks, a.verify_status,
        a.status FROM tbl_applicant_doc a join tbl_document_mstr d on(d.id=a.document_id) WHERE a.apply_connection_id='$apply_connection_id'
        AND a.doc_for='$doc_for' and a.verify_status='$verify_status'";
        $q = $this->db->query($sql);        
        $result = $q->getResultArray()[0];        
        return $result;
    }

    public function count_uploaded_document($apply_licence_id,$doc_for)
    {
        try{
             return $this->db->table($this->table)
                        ->select('count(id) as doc_cnt')
                        ->where('apply_licence_id',$apply_licence_id)
                        ->where('verify_status',0)
                        ->where('doc_for',$doc_for)
                        ->where('status',1)
                        ->get()
                        ->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function count_agguploaded_document($apply_licence_id)
    {
        try{
            $doc_for = ['rent_agreement', 'lease_agreement', 'electricity_bill'];
             return $this->db->table($this->table)
                        ->select('count(id) as doc_cnt')
                        ->where('apply_licence_id',$apply_licence_id)
                        ->where('verify_status',0)
                        ->whereIn('doc_for',$doc_for)
                        ->where('status',1)
                        ->get()
                        ->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    
    public function insertOwnerImgData($input){
        $builder = $this->db->table($this->table)
                            ->insert([
                  "apply_licence_id"=>$input["apply_licence_id"],
                  "firm_owner_dtl_id"=>$input["firm_owner_dtl_id"],
                  "doc_for"=>'Consumer Photo',
                  "document_id"=>'0',
                  "document_path"=>'',
                  "remarks"=>'',
                  "emp_details_id"=>$input["emp_details_id"],
                  "created_on"=>$input["created_on"],
                  "status"=>'1'
				  ]);
							//echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }
    public function insertOwnerData($input){
        $builder = $this->db->table($this->table)
                            ->insert([
                  "apply_licence_id"=>$input["apply_licence_id"],
                  "firm_owner_dtl_id"=>$input["firm_owner_dtl_id"],
                  "doc_for"=>'Identity Proof',
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
	
    public function check_owner_details($apply_licence_id,$firm_owner_dtl_id)
    {
        try{
             $doc_nm = "Consumer Photo";
             return $this->db->table($this->table)
                        ->select('id,firm_owner_dtl_id,document_path')
                        ->where('apply_licence_id',$apply_licence_id)
						->where('firm_owner_dtl_id',$firm_owner_dtl_id)
                        ->where('doc_for',$doc_nm)
                        ->where('status',1)
                        ->get()
                        ->getResultArray();
						
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
	public function check_owner_doc($apply_licence_id,$firm_owner_dtl_id)
    {
        try{
             $doc_nm = "Identity Proof";
             return $this->db->table($this->table)
                        ->select('id,firm_owner_dtl_id,document_path')
                        ->where('apply_licence_id',$apply_licence_id)
						->where('firm_owner_dtl_id',$firm_owner_dtl_id)
                        ->where('doc_for',$doc_nm)
                        ->where('status',1)
                        ->get()
                        ->getResultArray();
						
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    
    public function get_ownerimgdetails_by_safid($apply_licence_id,$firm_owner_dtl_id,$doc_for)
    {
        try{

            $data=$this->db->table($this->table)
                        ->select('*')
                        ->where('apply_licence_id',$apply_licence_id)
                        ->where('doc_for',$doc_for)
                        ->where('document_id', 0)
                        ->where('firm_owner_dtl_id', $firm_owner_dtl_id)
                        ->where('status',1)
                        ->get()
                        ->getResultArray();
						//echo $this->db->getLastQuery();
            return $data;
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function get_verifiedownerimgdetails_by_safid($apply_licence_id,$firm_owner_dtl_id,$doc_for)
    {
        try
        {
            $data= $this->db->table($this->table)
					->select('*')
					->where('apply_licence_id', $apply_licence_id)
					//->where('firm_owner_dtl_id',$firm_owner_dtl_id)
					->where('doc_for', $doc_for)
					->where('document_id', 0)
				   // ->where('verify_status', 1)
					->where('status', 1)
                    ->where('firm_owner_dtl_id',$firm_owner_dtl_id )
					->get()
					->getResultArray();
					//echo $this->db->getLastQuery();
            return $data;
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }

    public function conownerdocdetbyid($apply_licence_id,$firm_owner_dtl_id,$doc_for)
    {
        $sql = "SELECT a.id, a.apply_licence_id, a.doc_for, a.document_id, d.doc_name, a.document_path, a.remarks, a.verify_status,
        a.status FROM tbl_application_doc a join tbl_document d on(d.id=a.document_id) WHERE a.apply_licence_id='$apply_licence_id'
        AND a.doc_for='$doc_for'";

        $sql = "SELECT a.id, a.apply_licence_id, a.doc_for, a.document_id, d.doc_name, a.document_path, a.remarks, a.verify_status,
        a.status FROM tbl_application_doc a join tbl_document d on(d.id=a.document_id) WHERE a.apply_licence_id='$apply_licence_id'
        AND upper(d.doc_for)=upper('$doc_for')";

        $q = $this->db->query($sql);        
        $result = $q->getResultArray();
        //echo $this->db->getLastQuery();

        return $result;
    }
    public function get_details_by_connid($apply_licence_id,$firm_owner_dtl_id,$app_other_doc,$verify_status)
    {
        try{

             return $this->db->table($this->table)
                        ->select('*')
                        ->where('apply_licence_id',$apply_licence_id)
                        ->where('firm_owner_dtl_id',$firm_owner_dtl_id)
                        ->where('doc_for',$app_other_doc)
                        ->where('verify_status', $verify_status)
                        ->where('document_id', 0)
                        ->where('status',1)
                        ->get()
                        ->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function conownerdocnamebydoctype($apply_licence_id,$firm_owner_dtl_id,$doc_for,$verify_status){
        $sql = "SELECT a.id, a.apply_licence_id, a.doc_for, a.document_id, d.doc_name, a.document_path, a.remarks, a.verify_status,
        a.status, a.firm_owner_dtl_id FROM tbl_application_doc a join tbl_document d on(d.id=a.document_id) WHERE a.apply_licence_id='$apply_licence_id'
        and a.firm_owner_dtl_id='$firm_owner_dtl_id' AND a.doc_for='$doc_for' and a.verify_status='$verify_status'";
        $q = $this->db->query($sql);        
        $result = $q->getResultArray()[0];
        //echo $this->db->getLastQuery();

        return $result;
    }
    public function updateappimgdocById($input){
        return $builder = $this->db->table($this->table)
                            ->where('id', $input['applicant_img_id'])
                            ->where('verify_status', 0)
                            ->update([
                                    'remarks'=>$input['app_img_remarks'],
                                    'verify_status'=>$input['app_img_verify'],
                                    'verified_by_emp_id'=>$input['emp_details_id'],
                                    'verified_on'=>$input['created_on']
                                    ]);

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
	//document  bu noc par sap sol ele app
	public function updatebudocById($input){
        return $builder = $this->db->table($this->table)
                            ->where('id', $input['bu_document_id'])
                            ->where('verify_status', 0)
                            ->update([
                                    'remarks'=>$input['bu_remarks'],
                                    'verify_status'=>$input['bu_verify'],
                                    'verified_by_emp_id'=>$input['emp_details_id'],
                                    'verified_on'=>$input['created_on']
                                    ]);
    }
	public function updatenocdocById($input){
        return $builder = $this->db->table($this->table)
                            ->where('id', $input['noc_document_id'])
                            ->where('verify_status', 0)
                            ->update([
                                    'remarks'=>$input['noc_remarks'],
                                    'verify_status'=>$input['noc_verify'],
                                    'verified_by_emp_id'=>$input['emp_details_id'],
                                    'verified_on'=>$input['created_on']
                                    ]);
    }
	public function updatepardocById($input){
        return $builder = $this->db->table($this->table)
                            ->where('id', $input['Par_document_id'])
                            ->where('verify_status', 0)
                            ->update([
                                    'remarks'=>$input['Par_remarks'],
                                    'verify_status'=>$input['Par_verify'],
                                    'verified_by_emp_id'=>$input['emp_details_id'],
                                    'verified_on'=>$input['created_on']
                                    ]);
    }
	public function updatesapdocById($input){
        return $builder = $this->db->table($this->table)
                            ->where('id', $input['sap_document_id'])
                            ->where('verify_status', 0)
                            ->update([
                                    'remarks'=>$input['sap_remarks'],
                                    'verify_status'=>$input['sap_verify'],
                                    'verified_by_emp_id'=>$input['emp_details_id'],
                                    'verified_on'=>$input['created_on']
                                    ]);
    }
	public function updatesoldocById($input){
        return $builder = $this->db->table($this->table)
                            ->where('id', $input['sol_document_id'])
                            ->where('verify_status', 0)
                            ->update([
                                    'remarks'=>$input['sol_remarks'],
                                    'verify_status'=>$input['sol_verify'],
                                    'verified_by_emp_id'=>$input['emp_details_id'],
                                    'verified_on'=>$input['created_on']
                                    ]);
    }
	public function updateeledocById($input){
        return $builder = $this->db->table($this->table)
                            ->where('id', $input['ele_document_id'])
                            ->where('verify_status', 0)
                            ->update([
                                    'remarks'=>$input['ele_remarks'],
                                    'verify_status'=>$input['ele_verify'],
                                    'verified_by_emp_id'=>$input['emp_details_id'],
                                    'verified_on'=>$input['created_on']
                                    ]);
    }
	public function updateapdocById($input){
        return $builder = $this->db->table($this->table)
                            ->where('id', $input['app_document_id'])
                            ->where('verify_status', 0)
                            ->update([
                                    'remarks'=>$input['app_remarks'],
                                    'verify_status'=>$input['app_verify'],
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
    public function updatercdocById($input){
        return $builder = $this->db->table($this->table)
                            ->where('id', $input['rc_document_id'])
                            ->where('verify_status', 0)
                            ->update([
                                    'remarks'=>$input['rc_remarks'],
                                    'verify_status'=>$input['rc_verify'],
                                    'verified_by_emp_id'=>$input['emp_details_id'],
                                    'verified_on'=>$input['created_on']
                                    ]);
    }
    public function updateaddocById($input){
        return $builder = $this->db->table($this->table)
                            ->where('id', $input['ad_document_id'])
                            ->where('verify_status', 0)
                            ->update([
                                    'remarks'=>$input['ad_remarks'],
                                    'verify_status'=>$input['ad_verify'],
                                    'verified_by_emp_id'=>$input['emp_details_id'],
                                    'verified_on'=>$input['created_on']
                                    ]);
    }
    public function updatefadocById($input){
        return $builder = $this->db->table($this->table)
                            ->where('id', $input['fa_document_id'])
                            ->where('verify_status', 0)
                            ->update([
                                    'remarks'=>$input['fa_remarks'],
                                    'verify_status'=>$input['fa_verify'],
                                    'verified_by_emp_id'=>$input['emp_details_id'],
                                    'verified_on'=>$input['created_on']
                                    ]);
    }

    public function verified_rej_owner_img_details($apply_licence_id,$firm_owner_dtl_id)
    {
        try{
            $doc_for="Consumer Photo";
            $stts = ['1', '2', '0'];
            return $this->db->table($this->table)
                        ->select('id,firm_owner_dtl_id,document_path,remarks,verify_status')
                        ->where('apply_licence_id',$apply_licence_id)
                        ->whereIn('verify_status',$stts)
                        ->where('doc_for',$doc_for)
                        ->where('firm_owner_dtl_id',$firm_owner_dtl_id)
                        ->where('document_id', 0)
                        ->where('status',1)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray();
						//echo $this->db->getLastQuery();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }


    public function check_ownerdtl_img_details($apply_licence_id,$firm_owner_dtl_id)
    {
        try{
            $doc_for="consumer_photo";
            return $this->db->table($this->table)
                        ->select('id,firm_owner_dtl_id,document_path')
                        ->where('apply_licence_id',$apply_licence_id)
                        ->where('verify_status',1)
                        ->where('doc_for',$doc_for)
                        ->where('firm_owner_dtl_id',$firm_owner_dtl_id)
                        ->where('document_id', 0)
                        ->where('status',1)
                        ->get()
                        ->getResultArray()[0];
            //echo $this->db->getLastQuery();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function check_newownerdtl_img_details($apply_licence_id,$firm_owner_dtl_id)
    {
        try{
            $doc_for="consumer_photo";
             return $this->db->table($this->table)
                        ->select('id,firm_owner_dtl_id,document_path')
                        ->where('apply_licence_id',$apply_licence_id)
                        ->where('verify_status',0)
                        ->where('doc_for',$doc_for)
                        ->where('firm_owner_dtl_id',$firm_owner_dtl_id)
                        ->where('document_id', 0)
                        ->where('status',1)
                        ->get()
                        ->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function check_verified_doc_exist($apply_licence_id,$doc_for)
    {
        try{

             return $this->db->table($this->table)
                        ->select('id,firm_owner_dtl_id,document_path,verify_status')
                       ->where('apply_licence_id',$apply_licence_id)
                        //->where('verify_status',1)
                        ->where('doc_for',$doc_for)
                        ->where('status',1)
						->orderBy('id','DESC')
                        ->get()
                        ->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function check_new_doc_exist($apply_licence_id,$doc_for)
    {
        try{

             return $this->db->table($this->table)
                        ->select('id,firm_owner_dtl_id,document_path')
                        ->where('apply_licence_id',$apply_licence_id)
                        ->where('verify_status',0)
                        ->where('doc_for',$doc_for)
                        ->where('status',1)
                        ->get()
                        ->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function verified_rej_document_details($apply_licence_id,$other_doc)
    {
        try{
            $stts = ['1', '2', '0'];
              return $this->db->table($this->table)
                        ->select('id,firm_owner_dtl_id,document_path,remarks,verify_status,document_id')
                        ->where('apply_licence_id',$apply_licence_id)
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
    
    public function agg_verified_rej_document_details($apply_licence_id)
    {
        try{
            $stts = ['1', '2'];
             $doc_for = ['rent_agreement', 'lease_agreement', 'electricity_bill'];
              return $this->db->table($this->table)
                        ->select('id,firm_owner_dtl_id,document_path,remarks,verify_status,document_id,doc_for')
                        ->where('apply_licence_id',$apply_licence_id)
                        ->whereIn('verify_status',$stts)
                        ->whereIn('doc_for',$doc_for)
                        ->where('status',1)
                        ->orderBy('id','DESC')
                        ->get()
            //echo $this->db->getLastQuery();
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function aggcheck_verified_doc_exist($apply_licence_id)
    {
        try{
            $doc_for = ['rent_agreement', 'lease_agreement', 'electricity_bill'];
             return $this->db->table($this->table)
                        ->select('id,firm_owner_dtl_id,document_path,doc_for')
                       ->where('apply_licence_id',$apply_licence_id)
                        ->where('verify_status',1)
                        ->whereIn('doc_for',$doc_for)
                        ->where('status',1)
                        ->get()
                        ->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function aggcheck_new_doc_exist($apply_licence_id)
    {
        try{
            $doc_for = ['rent_agreement', 'lease_agreement', 'electricity_bill'];
             return $this->db->table($this->table)
                        ->select('id,firm_owner_dtl_id,document_path,doc_for')
                        ->where('apply_licence_id',$apply_licence_id)
                        ->where('verify_status',0)
                        ->whereIn('doc_for',$doc_for)
                        ->where('status',1)
                        ->get()
                        ->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function getDocumentIdForPaymentBill($apply_licence_id){
        try{
             $builder = $this->db->table($this->table)
                       ->select('document_id')
                       ->where('apply_licence_id',$apply_licence_id)
                       ->where('upper(doc_for)',strtoupper('payment_receipt'))
                       ->where('status',1)
                       ->get();
                    echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow('array');
            return $builder['document_id'];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getDocumentIdForElectricBill($apply_licence_id){
        try{
             $builder = $this->db->table($this->table)
                       ->select('document_id')
                       ->where('apply_licence_id',$apply_licence_id)
                       ->where('upper(doc_for)',strtoupper('electricity_bill'))
                       ->where('status',1)
                       ->get();
            $builder = $builder->getFirstRow('array');
            return $builder['document_id'];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getDocumentIdForRegistration($apply_licence_id){
        try{
             $builder = $this->db->table($this->table)
                       ->select('document_id')
                       ->where('apply_licence_id',$apply_licence_id)
                       ->where('upper(doc_for)',strtoupper('registration_certificate'))
                       ->where('status',1)
                       ->get();
            $builder = $builder->getFirstRow('array');
            return $builder['document_id'];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
}                
