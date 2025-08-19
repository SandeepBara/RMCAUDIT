<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_view_trade_level_pending extends Model
{
    protected $db;
    protected $table = 'view_trade_level_pending';
    protected $allowedFields = ['id','apply_licence_id','application_no','ward_mstr_id','receiver_user_type_id','sender_user_type_id','verification_status','status','created_on'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function tradedareceiveList($receiver_user_type_id,$from_date,$to_date,$ward_permission)
    { 
        // print_var($ward_permission);return;
        try{
            $result = $this->db->table('view_trade_level_pending')
                        ->select('*')
                        ->where('receiver_user_type_id',$receiver_user_type_id)
                        ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date);
                        if($ward_permission)
                            $result = $result->whereIn('ward_mstr_id', $ward_permission);
                        $result = $result->whereIn('sender_user_type_id', [0, 20])
                        ->where('status', 1)
                        //->where('doc_upload_status',1)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray();
                        // echo $this->db->getLastQuery();
                        return $result;
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function tradeReceiveListWithWhateverSecond($sql_query){
    try{

        $builder = $this->query($sql_query);
        return $result = $builder->getResultArray();
    }catch(Exception $e){
        return $e->getMessage();
    }
}
    public function tradedareceivebywardidList($receiver_user_type_id,$from_date,$to_date,$ward_mstr_id)
    {
        try{
             return $this->db->table('view_trade_level_pending')
                        ->select('*')
                        ->where('receiver_user_type_id',$receiver_user_type_id)
                        ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->where('ward_mstr_id', $ward_mstr_id)
                        ->where('sender_user_type_id',0)
                        // ->where('verification_status ',0)
                        ->where('status',1)
                        //->where('doc_upload_status',1)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function tradelevelpendingdetailbyid($id)
    {
        try{
            $builder = $this->db->table('view_trade_level_pending')
                        ->select('*')
                        ->where('md5(id::text)', $id)
                        ->where('status', 1)
                        ->get();

                        // echo $this->db->getLastQuery();
                    
           $builder =  $builder->getResultArray();
            return $builder[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
	public function tradeapplicationdetailsbyid($id)
    {
        try{
            $builder = $this->db->table('view_trade_level_pending')
                        ->select('*')
                        ->where('md5(apply_licence_id::text)', $id)
                        ->where('status',1)
                        ->get();
                        /*echo $this->db->getLastQuery();*/
           $builder =  $builder->getResultArray();
            return $builder[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function daapprovedList($sender_user_type_id,$from_date,$to_date,$ward_permission)
    {
        // print_var($ward_permission);return;
        try{
             //$names = ['10', '11'];
            return $this->db->table('view_trade_level_pending')
                        ->select('*')
                        ->where('receiver_user_type_id',$sender_user_type_id)
                        ->where('sender_user_type_id',0)
                         ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->whereIn('ward_mstr_id', $ward_permission)
                        ->where('status',1)
                        //->where('doc_status',1)
                        ->orderBy('id','DESC')
                        ->get()           
                        ->getResultArray();
                     /*echo $this->db->getLastQuery();*/

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function wardwise_daapprovedList($sender_user_type_id,$from_date,$to_date,$ward_mstr_id)
    {
        try{
             //$names = ['10', '11'];
             return $this->db->table('view_trade_level_pending')
                        ->select('*')
                        ->where('receiver_user_type_id',$sender_user_type_id)
                        ->where('sender_user_type_id',0)
                          ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->where('status',1)
                        //->where('doc_status',1)
                        ->where('ward_mstr_id', $ward_mstr_id)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
	
	 public function get_application_list($application_no)
    { 
        try{
             return $this->db->table('view_trade_level_pending')
                        ->select('*')
                        ->where('application_no',$application_no)
                         ->where('status',1)
                         ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray();
                        //echo $this->db->getLastQuery();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
    public function waterjereceiveList($receiver_user_type_id,$from_date,$to_date,$ward_permission)
    {
        // print_var($ward_permission);return;
        
        try
        {
            $builder = $this->db->table('view_trade_level_pending')
                        ->select('*')
                        ->where('receiver_user_type_id', $receiver_user_type_id)
                        ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->whereIn('ward_mstr_id', $ward_permission)
                        //->where('sender_user_type_id',0)
                         ->where('status', 1)
                        //->where('doc_upload_status',1)
                        ->orderBy('id', 'DESC')
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


    public function waterjereceivebywardidList($receiver_user_type_id,$from_date,$to_date,$ward_mstr_id)
    {
        try{
            $data= $this->db->table('view_trade_level_pending')
                        ->select('*')
                        ->where('receiver_user_type_id',$receiver_user_type_id)
                        ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->where('ward_mstr_id', $ward_mstr_id)
                        //->where('sender_user_type_id',0)
                        //->where('verification_status ',0)
                        ->where('status',1)
                        //->where('doc_upload_status',1)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray();
                        //echo $this->db->getLastQuery();
                        return $data;
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function forwardList($sender_user_type_id,$from_date,$to_date,$ward_permission)
    {
        try{
             //$names = ['10', '11'];
            return $this->db->table('view_trade_level_pending')
                        ->select('*')
                        ->where('sender_user_type_id',$sender_user_type_id)
                        ->where('verification_status ',0)
                         ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->whereIn('ward_mstr_id', $ward_permission)
                        ->where('status',1)
                        //->where('doc_status',1)
                        ->orderBy('id','DESC')
                        ->get()           
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function wardwise_forwardList($sender_user_type_id,$from_date,$to_date,$ward_mstr_id)
    {
        try{
             //$names = ['10', '11'];
             return $this->db->table('view_trade_level_pending')
                        ->select('*')
                        ->where('sender_user_type_id',$sender_user_type_id)
                        ->where('verification_status ',0)
                         ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->where('status',1)
                        //->where('doc_status',1)
                        ->where('ward_mstr_id', $ward_mstr_id)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function getDenialList($receiver_user_type_id,$from_date,$to_date,$ward_permission,$mailStatus)
    {
        try
        {
            $builder = $this->db->table('view_denial_mail_dtl')
                        ->select('*')
                        ->where('receiver_user_type_id', $receiver_user_type_id)
                        ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->whereIn('ward_id', $ward_permission)
                        ->where('status',$mailStatus)
                        ->orderBy('id', 'DESC')
                        ->get()
                        ->getResultArray();
        //   echo $this->db->getLastQuery(); exit;
            return $builder;
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }

    public function getDenialListByWard($receiver_user_type_id,$from_date,$to_date,$ward_mstr_id,$mailStatus)
    {
        try{
            return  $this->db->table('view_denial_mail_dtl')
                        ->select('*')
                        ->where('receiver_user_type_id',$receiver_user_type_id)
                        ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->where('ward_id', $ward_mstr_id)
                        ->where('status',$mailStatus)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray();
                 //echo $this->db->getLastQuery(); exit;
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }


    //update status in mail

    


}
?> 