<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_view_saf_receive_list extends Model
{
    protected $db;
    protected $table = 'view_saf_receive_list';
    protected $allowedFields = ['id','saf_dl_id','saf_no','holding_no','ward_mstr_id','receiver_emp_details_id','sender_user_type_id','verification_status','status','created_on'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function safreceiveList($receiver_user_type_id,$from_date,$to_date,$ward_permission)
    {
        try{
             //$names = ['10', '11'];
              return $this->db->table('view_saf_receive_list')
                        ->select('*')
                        ->where('receiver_user_type_id',$receiver_user_type_id)
                        ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->whereIn('ward_mstr_id', $ward_permission)
                        ->where('sender_user_type_id',11)
                        ->where('verification_status ',0)
                        ->where('status',1)
                        ->where('doc_upload_status',1)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray();
						//echo $this->db->getLastQuery();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function safreceivewardList($receiver_user_type_id,$from_date,$to_date,$ward_mstr_id)
    {
        try{
             //$names = ['10', '11'];
             return $this->db->table('view_saf_receive_list')
                        ->select('*')
                        ->where('receiver_user_type_id',$receiver_user_type_id)
                        ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->where('ward_mstr_id', $ward_mstr_id)
                        ->where('sender_user_type_id',11)
                        ->where('verification_status ',0)
                        ->where('status',1)
                        ->where('doc_upload_status',1)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    /*public function safreceiveList($receiver_user_type_id,$ward_permission)
    {
        try{
             //$names = ['10', '11'];
             return $this->db->table('view_saf_receive_list')
                        ->select('*')
                        ->where('receiver_user_type_id',$receiver_user_type_id)
                        ->where('sender_user_type_id',0)
                        ->where('verification_status ',0)
                        ->whereIn('ward_mstr_id', $ward_permission)
                        ->where('status',1)
                        ->where('doc_upload_status',1)
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }*/
    public function sisafList($receiver_user_type_id,$from_date,$to_date,$ward_permission)
    {
        try{
             //$names = ['10', '11'];
            //->whereIn('ward_mstr_id', $ward_permission)
             return $this->db->table('view_saf_receive_list')
                        ->select('*')
                        ->where('receiver_user_type_id',$receiver_user_type_id)
                        ->whereIn('ward_mstr_id', $ward_permission)
                        ->where('verification_status ',0)
                        ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->where('status',1)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function wardwisesisafList($receiver_user_type_id,$from_date,$to_date,$ward_mstr_id)
    {
        try{
             //$names = ['10', '11'];
            //->whereIn('ward_mstr_id', $ward_permission)
             return $this->db->table('view_saf_receive_list')
                        ->select('*')
                        ->where('receiver_user_type_id',$receiver_user_type_id)
                        //->where('sender_user_type_id',7)
                        ->where('verification_status ',0)
                        ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->where('ward_mstr_id', $ward_mstr_id)
                        ->where('status',1)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function ulbtcsafList($receiver_user_type_id,$from_date,$to_date,$ward_permission)
    {
        try{
             //$names = ['10', '11'];
            //->whereIn('ward_mstr_id', $ward_permission)
             return $this->db->table('view_saf_receive_list')
                        ->select('*')
                        ->where('receiver_user_type_id',$receiver_user_type_id)
                        ->where('verification_status ',0)
                        ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->whereIn('ward_mstr_id', $ward_permission)
                        ->where('status',1)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function wardwiseulbtcsafList($receiver_user_type_id,$from_date,$to_date,$ward_mstr_id)
    {
        try{
             //$names = ['10', '11'];
            //->whereIn('ward_mstr_id', $ward_permission)
             return $this->db->table('view_saf_receive_list')
                        ->select('*')
                        ->where('receiver_user_type_id',$receiver_user_type_id)
                        ->where('verification_status ',0)
                         ->where('ward_mstr_id', $ward_mstr_id)
                        ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->where('status',1)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function eosafList($receiver_user_type_id,$from_date,$to_date)
    {
        try{
             //$names = ['10', '11'];
             return $this->db->table('view_saf_receive_list')
                        ->select('*')
                        ->where('receiver_user_type_id',$receiver_user_type_id)
                        ->where('sender_user_type_id',9)
                        ->where('verification_status ',0)
                        ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->where('status',1)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function wardwiseeosafList($receiver_user_type_id,$from_date,$to_date,$ward_mstr_id)
    {
        try{
             //$names = ['10', '11'];
             return $this->db->table('view_saf_receive_list')
                        ->select('*')
                        ->where('receiver_user_type_id',$receiver_user_type_id)
                        ->where('sender_user_type_id',9)
                        ->where('verification_status ',0)
                         ->where('ward_mstr_id', $ward_mstr_id)
                        ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->where('status',1)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    /*public function eosafList($receiver_user_type_id,$ward_permission)
    {
        try{
             //$names = ['10', '11'];
             return $this->db->table('view_saf_receive_list')
                        ->select('*')
                        ->where('receiver_user_type_id',$receiver_user_type_id)
                        ->where('sender_user_type_id',9)
                        ->where('verification_status ',0)
                        ->whereIn('ward_mstr_id', $ward_permission)
                        ->where('status',1)
                        ->where('doc_upload_status',1)
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }*/
    public function safreceivedetailbyid($id) {
        try{
            $sql = "SELECT
                        l.id,
                        l.saf_dtl_id,
                        s.saf_no,
                        s.assessment_type,
                        s.holding_no,
                        s.ward_mstr_id,
                        s.no_electric_connection,
                        l.sender_user_type_id,
                        l.receiver_user_type_id,
                        l.status,
                        l.verification_status,
                        l.remarks,
                        s.doc_upload_status,
                        l.created_on,
                        s.previous_holding_id,
                        s.saf_pending_status
                    FROM tbl_level_pending_dtl l
                    INNER JOIN tbl_saf_dtl s ON l.saf_dtl_id = s.id
                    WHERE MD5(l.id::TEXT)='".$id."'";
            $result= $this->db->query($sql);
            return $result->getFirstRow('array');
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
	public function safrecptid($id) {
        try{
            $builder = $this->db->table('view_saf_receive_list')
                        ->select('id')
                        ->where('md5(saf_dtl_id::text)', $id)
                        ->where('status',1)
                        ->get();
                        //echo $this->db->getLastQuery();
           $builder =  $builder->getResultArray();
            return $builder[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
    public function backtocitizenList($sender_user_type_id,$from_date,$to_date) {
        try{
             //$names = ['10', '11'];
             return $this->db->table('view_saf_receive_list')
                        ->select('*')
                        ->where('receiver_user_type_id',6)
                        ->where('sender_user_type_id',0)
                        ->where('verification_status ',2)
                        ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->where('status',1)
                        ->where('doc_upload_status',1)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function wardwisebacktocitizenList($sender_user_type_id,$from_date,$to_date,$ward_mstr_id)
    {
        try{
             //$names = ['10', '11'];
             return $this->db->table('view_saf_receive_list')
                        ->select('*')
                        ->where('receiver_user_type_id',6)
                        ->where('sender_user_type_id',0)
                        ->where('verification_status ',2)
                        ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                         ->where('ward_mstr_id', $ward_mstr_id)
                        ->where('status',1)
                        ->where('doc_upload_status',1)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function bocsafList($from_date,$to_date)
    {
        try{
             //$names = ['10', '11'];
             return $this->db->table('view_saf_receive_list')
                        ->select('*')
                        ->where('sender_user_type_id',0)
                        ->where('verification_status ',2)
                        ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->where('saf_pending_status',2)
                        ->where('status',1)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function wardwisebocsafList($from_date,$to_date,$ward_mstr_id)
    {
        try{
             //$names = ['10', '11'];
             return $this->db->table('view_saf_receive_list')
                        ->select('*')
                        ->where('sender_user_type_id',0)
                        ->where('verification_status ',2)
                        ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->where('ward_mstr_id', $ward_mstr_id)
                        ->where('saf_pending_status',2)
                        ->where('status',1)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function genmemoList($from_date,$to_date, $ward_permission)
    {
        //print_var($ward_permission);
        $sql = "SELECT
                    tbl_saf_dtl.ward_mstr_id,
                    tbl_saf_dtl.saf_no,
                    tbl_saf_dtl.assessment_type,
                    tbl_saf_memo_dtl.*,
                    tbl_saf_owner_detail.*, ward_no
                FROM tbl_saf_memo_dtl
                JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_saf_memo_dtl.saf_dtl_id
                JOIN (
                        SELECT saf_dtl_id, STRING_AGG(owner_name, ',') AS owner_name, STRING_AGG(mobile_no::TEXT, ',') AS mobile_no 
                        FROM tbl_saf_owner_detail 
                        WHERE status=1 
                        GROUP BY saf_dtl_id
                    ) AS tbl_saf_owner_detail ON tbl_saf_owner_detail.saf_dtl_id=tbl_saf_memo_dtl.saf_dtl_id
                join view_ward_mstr on view_ward_mstr.id=tbl_saf_dtl.ward_mstr_id
                WHERE tbl_saf_memo_dtl.memo_type='SAM' AND tbl_saf_memo_dtl.status=1 and 
                cast(tbl_saf_memo_dtl.created_on as date) between '$from_date' and '$to_date' and tbl_saf_dtl.ward_mstr_id in ($ward_permission)";
            
        $builder = $this->db->query($sql);
        
        // $builder= $this->db->table('view_saf_receive_list')
        //             ->select('*')
        //             ->where('receiver_user_type_id',$sender_user_type_id)
        //             ->where('sender_user_type_id', 0)
        //             ->where('verification_status ',1)
        //             ->where('date(created_on) >=', $from_date)
        //             ->where('date(created_on) <=', $to_date)
        //             ->whereIn('ward_mstr_id', $ward_permission)
        //             ->where('status',1)
        //             ->where('doc_upload_status',1)
        //             ->orderBy('id','DESC')
        //             ->get();
        
        //echo $this->db->getLastQuery();exit;
        return $builder->getResultArray();
        
    }
    public function wardwisegenmemoList($sender_user_type_id,$from_date,$to_date,$ward_mstr_id)
    {
        try{
             //$names = ['10', '11'];
             return $this->db->table('view_saf_receive_list')
                        ->select('*')
                        ->where('receiver_user_type_id',$sender_user_type_id)
                        ->where('sender_user_type_id',0)
                        ->where('verification_status ',1)
                         ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->where('status',1)
                        ->where('doc_upload_status',1)
                        ->where('ward_mstr_id', $ward_mstr_id)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function forwardList($sender_user_type_id,$from_date,$to_date,$ward_permission)
    {
        try{
             //$names = ['10', '11'];
             return $this->db->table('view_saf_receive_list')
                        ->select('*')
                        ->where('sender_user_type_id',$sender_user_type_id)
                        ->where('verification_status ',0)
                         ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->whereIn('ward_mstr_id', $ward_permission)
                        ->where('status',1)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function wardwiseforwardList($sender_user_type_id,$from_date,$to_date,$ward_mstr_id)
    {
        try{
             //$names = ['10', '11'];
             return $this->db->table('view_saf_receive_list')
                        ->select('*')
                        ->where('sender_user_type_id',$sender_user_type_id)
                        ->where('verification_status ',0)
                         ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->where('status',1)
                        ->where('ward_mstr_id', $ward_mstr_id)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function finalmemoList($sender_user_type_id,$from_date,$to_date,$ward_permission)
    {
        try{
             //$names = ['10', '11'];
             return $this->db->table('view_saf_receive_list')
                        ->select('*')
                        ->where('receiver_user_type_id',$sender_user_type_id)
                        ->where('verification_status ',1)
                         ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->whereIn('ward_mstr_id', $ward_permission)
                        ->where('status',1)
                        ->where('doc_upload_status',1)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function wardwisefinalmemoList($sender_user_type_id,$from_date,$to_date,$ward_mstr_id)
    {
        try{
             //$names = ['10', '11'];
             return $this->db->table('view_saf_receive_list')
                        ->select('*')
                        ->where('receiver_user_type_id',$sender_user_type_id)
                        ->where('verification_status ',1)
                         ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->where('status',1)
                        ->where('doc_upload_status',1)
                        ->where('ward_mstr_id', $ward_mstr_id)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
}
?> 