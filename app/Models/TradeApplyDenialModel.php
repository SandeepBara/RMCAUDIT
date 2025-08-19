<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class TradeApplyDenialModel extends Model
{
	protected $db;
    protected $table = 'tbl_denial_consumer_dtl';
    protected $allowedFields = ['id','application_no','firm_type_id','application_type_id','ownership_type_id','ward_mstr_id','prop_dtl_id','firm_name','area_in_sqft','k_no','bind_book_no','account_no','payment_status','document_upload_status','pending_status','doc_verify_status','doc_verify_date','doc_verify_emp_details_id','emp_details_id','created_on','status','establishment_date','rate_id','address','landmark','pin_code','property_type','licence_for_years','apply_date','holding_no,otherfirmtype','provisional_licence_no','category_type_id','nature_of_bussiness','update_status'];

    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }

    public function insertdenialapply($input){
        $builder = $this->db->table($this->table)
			->insert([
				"ward_id"=>$input["new_ward_id"],
				"license_no"=>$input["licence_no"],
				"holding_no"=>$input["holding_no"],
 				"applicant_name"=>$input["owner_name"],                 
				"firm_name"=>$input["firm_Name"],
				"pincode"=>$input["pin_code"],
   				"mob_no"=>$input["mobileno"] ,
				"city"=>$input["city"],
				"landmark"=>$input["landmark"],
				"address"=>$input["address"],
                "ip_address"=>$input["ipaddress"]??null,
                "latitude"=>$input["latitude"]??null,
                "longitude"=>$input["longitude"]??null,
                "remarks"=>$input["remarks"],
 				"emp_details_id"=>$input["emp_details_id"],
				"created_on"=>$input["created_on"]
			]);

        //echo $this->db->getLastQuery(); exit;

        return $this->db->insertId();
    }

    public function updatedocpathById($denial_id ,$document_path){
        return $builder = $this->db->table($this->table)
                            ->where('id', $denial_id)
                            ->update([
                                    'file_name'=> $document_path,
                                    ]);

            // echo $this->db->getLastQuery(); exit;
    }

    public function getDenialDetailsByID($denial_id)
    {
        try{ 
            $status = [1,4,5];
              return $this->db->table($this->table)
                        ->select('*')
                        ->where('md5(id::text)', $denial_id)
                        ->whereIn('status',$status)
                        ->get()
                        // ->getResultArray()[0];
                        ->getFirstRow("array");
                //echo $this->db->getLastQuery(); exit;
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function updateStatus($input){
        return $builder = $this->db->table($this->table)
                        ->where('md5(id::text)', $input["denial_id"])
                        ->update([
                                'status'=>$input["status"],
                                ]);
            // echo $this->db->getLastQuery(); exit;
    }

    public function insertNoticeData($input){
        $builder = $this->db->table('tbl_denial_notice')
            ->insert([
                "denial_id"=>$input["denial_ID"],
                 "remarks"=>$input["remarks"],
                "emp_details_id"=>$input["emp_details_id"],
                "created_on"=>$input["created_on"],
                "approved_by"=>$input["approved_by"]??null,
            ]);

       // echo $this->db->getLastQuery(); exit;

        return $this->db->insertId();
    }

    public function updateNoticeNo($id,$noticeNO){
        return  $builder = $this->db->table('tbl_denial_notice')
                            ->where('id',$id)
                            ->update([
                                    'notice_no'=>$noticeNO,
                                    ]);
                // echo $this->db->getLastQuery(); exit;
    }

    public function fetchNoticeDetails($denial_id)
    {
        $sql = "select c.firm_name,c.applicant_name,c.mob_no,c.address,c.pincode,n.notice_no,n.approved_by from tbl_denial_consumer_dtl 
            c inner join tbl_denial_notice n on c.id = n.denial_id where md5(c.id::text) = '$denial_id'";
            $run=$this->db->query($sql);
            // $result=$run->getResultArray()[0];
            $result=$run->getFirstRow("array");
            //echo $this->getLastQuery();exit;
            return $result; 
    }     
    	


    public function getNoticeDetails($denial_id)
    {
        $sql = "select * from tbl_denial_notice where denial_id= '$denial_id'";
        $run=$this->db->query($sql);
        // $result=$run->getResultArray()[0];
        $result=$run->getFirstRow("array");
       // echo $this->getLastQuery();exit;
        return $result; 
    }   


    public function insertDocDetails($input){
        $builder = $this->db->table('tbl_denial_approved_document_upload')
			->insert([
				"denial_id"=>$input["denial_ID"],
				"notice_id"=>$input["notice_id"],
                "remarks"=>$input["remarks"],
 				"emp_details_id"=>$input["emp_details_id"],
				"created_on"=>$input["created_on"]
			]);

      //  echo $this->db->getLastQuery(); exit;

        return $this->db->insertId();
    }

    public function updatedocpathByIdapprove($denial_id ,$document_path){
        return $builder = $this->db->table('tbl_denial_approved_document_upload')
                            ->where('id', $denial_id)
                            ->update([
                                    'file'=> $document_path,
                                    ]);
            // echo $this->db->getLastQuery(); exit;
    }

    public function getapprovedDocDetails($denial_id)
    {
        $sql = "select * from tbl_denial_approved_document_upload where denial_id= '$denial_id'";
        $run=$this->db->query($sql);
        // $result=$run->getResultArray()[0];
        $result=$run->getFirstRow("array");
       // echo $this->getLastQuery();exit;
        return $result; 
    }


    public function totalDenialApply($where)
    {
        $sql = "select count(*) from tbl_denial_consumer_dtl where $where  ";
        $run=$this->db->query($sql);
        // $result=$run->getResultArray()[0];
        $result=$run->getFirstRow("array");
        //echo $this->getLastQuery();exit;
        return $result; 
    }

    public function rejectedDenial($whereverrej)
    {
        $sql = "select count(*) from tbl_denial_mail_dtl 
        inner join tbl_denial_consumer_dtl 
        on tbl_denial_mail_dtl.denial_id = tbl_denial_consumer_dtl.id
        where $whereverrej and tbl_denial_mail_dtl.status = 4";
        $run=$this->db->query($sql);
        // $result=$run->getResultArray()[0];
        $result=$run->getFirstRow("array");
    // echo $this->getLastQuery();exit;
        return $result; 
    }

    public function approvedDenial($whereverrej)
    {
        $sql = "select count(*) from tbl_denial_mail_dtl 
        inner join tbl_denial_consumer_dtl 
        on tbl_denial_mail_dtl.denial_id = tbl_denial_consumer_dtl.id
        where $whereverrej and tbl_denial_mail_dtl.status = 5";
        $run=$this->db->query($sql);
        // $result=$run->getResultArray()[0];
        $result=$run->getFirstRow("array");
        return $result; 
    }

    public function applyWithNotice($where)
    {
        $sql = "select count(tbl_denial_notice.id) from tbl_denial_consumer_dtl 
        left join tbl_denial_notice 
        on tbl_denial_consumer_dtl.id = tbl_denial_notice.denial_id 
        where $where and tbl_denial_notice.status = 2 ;";
        $run=$this->db->query($sql);
        // $result=$run->getResultArray()[0];
        $result=$run->getFirstRow("array");
        //echo $this->getLastQuery();exit;

        return $result; 
    }

    public function pendingAtEo($where)
    {
        //$sql = "select count(*) from tbl_denial_mail_dtl where $where and status = 1";
        $sql = "select count(*) from view_denial_mail_dtl where $where and status = 1";
        //print_var($sql);die;
        $run=$this->db->query($sql);
        // $result=$run->getResultArray()[0];
        $result=$run->getFirstRow("array");
        //echo $this->getLastQuery();exit;
        return $result; 
    }

    
    public function denialDetails($where)
    {  
        $sql = "select * from tbl_denial_consumer_dtl where $where";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();exit;
        return $result; 
    }

    public function rejectApproved($whereverrej)
    {
        $sql = "select tbl_denial_consumer_dtl.*,view_emp_details.emp_name, view_emp_details.last_name from tbl_denial_mail_dtl 
        inner join tbl_denial_consumer_dtl 
        on tbl_denial_mail_dtl.denial_id = tbl_denial_consumer_dtl.id join view_emp_details on view_emp_details.id =  tbl_denial_consumer_dtl.emp_details_id 
        where $whereverrej";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();exit;
        return $result; 
    }


    public function pendingEo($where)
    {
        $sql = "select tbl_denial_consumer_dtl.* from tbl_denial_mail_dtl 
        inner join tbl_denial_consumer_dtl 
        on tbl_denial_mail_dtl.denial_id = tbl_denial_consumer_dtl.id
        where $where";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();exit;
        return $result; 
    }

    public function denialDetailsByID($id)
    {
        $sql = "select * from tbl_denial_consumer_dtl where md5(id::text) = '$id'";
        $run=$this->db->query($sql);
        // $result=$run->getResultArray()[0];
        $result=$run->getFirstRow("array");
        //echo $this->getLastQuery();exit;
        return $result;   
    }

    public function getDenialFirmDetails($notice_no,$firm_date)
    {
        $sql = "select dnl.*,n.notice_no,n.created_on as noticedate,n.id as dnialid from tbl_denial_consumer_dtl dnl 
        inner join tbl_denial_notice  n 
        on dnl.id = n.denial_id 
        where  n.notice_no = '$notice_no' and n.created_on < '$firm_date' and dnl.status = 5 and n.status = 1";
        $run=$this->db->query($sql);
        // $result=$run->getResultArray()[0];
        $result=$run->getFirstRow("array");
        //echo $this->db->getLastQuery();
        return $result;   
    }

    public function getDenialDate($denialId)
    {
        $sql = "select * from tbl_denial_notice where denial_id = '$denialId' and status = 1 ";
        $run=$this->db->query($sql);
        //$result=$run->getResultArray()[0];
        $result=$run->getFirstRow("array");
        //echo $this->db->getLastQuery();exit;
        return $result; 
    }

    

    public function updateStatusFine($denial_id,$denialAmount,$applyid){
        return $builder = $this->db->table('tbl_denial_notice')
                            ->where('denial_id', $denial_id)
                            ->update([
                                    'apply_id'=> $applyid,
                                    'fine_amount'=> $denialAmount,
                                    'status'=>2, //denial amount paid status
                                    ]);
            // echo $this->db->getLastQuery(); exit;
    }

    public function wardWiseDenialDetails($where,$status,$ward_id, $wardPermission)
    {  
        if($status=='ttl')
        {
            if($ward_id=="All"){
                $sql = "select ward_no, count(cons.id),view_ward_mstr.id from view_ward_mstr
                left join 
                (select ward_id,id from tbl_denial_consumer_dtl 
                where $where) as cons 
                on view_ward_mstr.id = cons.ward_id where view_ward_mstr.id in ($wardPermission) group by ward_no,view_ward_mstr.id order by view_ward_mstr.id";
            }
            else{
                $sql = "select ward_no, count(cons.id),view_ward_mstr.id from view_ward_mstr
                left join 
                (select ward_id,id from tbl_denial_consumer_dtl 
                where $where) as cons 
                on view_ward_mstr.id = cons.ward_id where view_ward_mstr.id = $ward_id group by ward_no,view_ward_mstr.id order by view_ward_mstr.id";
            } 
        }
        elseif($status==5 || $status==4)
        {
            if($ward_id=="All")
            {
                $sql = "select view_ward_mstr.ward_no,count(apprv.id),view_ward_mstr.id from view_ward_mstr
                left join (select tbl_denial_consumer_dtl.id,ward_id from tbl_denial_mail_dtl left join 
                tbl_denial_consumer_dtl on tbl_denial_mail_dtl.denial_id = tbl_denial_consumer_dtl.id where $where ) as apprv
                on 	view_ward_mstr.id = apprv.ward_id where view_ward_mstr.id in ($wardPermission) group by view_ward_mstr.ward_no,view_ward_mstr.id  order by view_ward_mstr.id";
            }
            else
            {
                $sql = "select view_ward_mstr.ward_no,count(apprv.id),view_ward_mstr.id from view_ward_mstr
                left join (select tbl_denial_consumer_dtl.id,ward_id from tbl_denial_mail_dtl left join 
                tbl_denial_consumer_dtl on tbl_denial_mail_dtl.denial_id = tbl_denial_consumer_dtl.id where $where ) as apprv
                on 	view_ward_mstr.id = apprv.ward_id where view_ward_mstr.id = $ward_id group by view_ward_mstr.ward_no,view_ward_mstr.id  order by view_ward_mstr.id";
            } 
        }
        else
        {
            if($ward_id=="All")
            {
                $sql = "select view_ward_mstr.ward_no,count(apply.id),view_ward_mstr.id from view_ward_mstr
                left join (select tbl_denial_consumer_dtl.id,ward_id from tbl_denial_notice left join 
                tbl_denial_consumer_dtl on tbl_denial_notice.denial_id = tbl_denial_consumer_dtl.id 
                left join tbl_apply_licence on tbl_denial_notice.apply_id = tbl_apply_licence.id 
                where $where) as apply
                on 	view_ward_mstr.id = apply.ward_id where view_ward_mstr.id in ($wardPermission) group by view_ward_mstr.ward_no,view_ward_mstr.id  order by view_ward_mstr.id " ;
            }
            else
            {
                $sql = "select view_ward_mstr.ward_no,count(apply.id),view_ward_mstr.id from view_ward_mstr
                left join (select tbl_denial_consumer_dtl.id,ward_id from tbl_denial_notice left join 
                tbl_denial_consumer_dtl on tbl_denial_notice.denial_id = tbl_denial_consumer_dtl.id 
                left join tbl_apply_licence on tbl_denial_notice.apply_id = tbl_apply_licence.id 
                where $where) as apply
                on 	view_ward_mstr.id = apply.ward_id where view_ward_mstr.id = $ward_id group by view_ward_mstr.ward_no,view_ward_mstr.id  order by view_ward_mstr.id " ;
            }
            
        }
            
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();exit;
        return $result; 
    }

    public function DenialDetailsByWardId($where,$status)
    {  
        if($status=='ttl')
        {
            $sql ="select view_ward_mstr.ward_no, cons.* from view_ward_mstr 
            inner join (select * from tbl_denial_consumer_dtl 
            where $where) as cons on view_ward_mstr.id = cons.ward_id 
            order by cons.id desc";
        }
        
        elseif($status==5 || $status==4)
        {
                
            $sql = "select view_ward_mstr.ward_no,apprv.*  from view_ward_mstr 
                    inner join (select tbl_denial_consumer_dtl.* from tbl_denial_mail_dtl 
                    left join tbl_denial_consumer_dtl on tbl_denial_mail_dtl.denial_id = tbl_denial_consumer_dtl.id 
                    where $where ) as apprv 
                    on view_ward_mstr.id = apprv.ward_id  order by apprv.id desc";
            
        }
        else
        {
            $sql = "select view_ward_mstr.ward_no,apply.* from view_ward_mstr inner join 
            (select tbl_denial_consumer_dtl.* from tbl_denial_notice left join tbl_denial_consumer_dtl 
            on tbl_denial_notice.denial_id = tbl_denial_consumer_dtl.id left join tbl_apply_licence 
            on tbl_denial_notice.apply_id = tbl_apply_licence.id where $where) 
            as apply on view_ward_mstr.id = apply.ward_id  order by apply.id desc" ;    
        }
        
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        return $result; 
    }

    public function denialDetailsByWard($where)
    {
        $sql = "select *,tbl_denial_notice.status
        as ntc ,tbl_denial_notice.notice_no, view_ward_mstr.ward_no from tbl_denial_consumer_dtl 
        left join 
        tbl_denial_notice on tbl_denial_consumer_dtl.id = tbl_denial_notice.denial_id 
        left join view_ward_mstr on tbl_denial_consumer_dtl.ward_id = view_ward_mstr.id
        where $where";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();exit;
        return $result;   
    }

}
?>