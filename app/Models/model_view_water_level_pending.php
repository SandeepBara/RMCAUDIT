<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_view_water_level_pending extends Model
{
    protected $db;
    protected $table = 'view_water_level_pending';
    protected $allowedFields = ['id','apply_connection_id','holding_no','application_no','ward_id','receiver_user_type_id','sender_user_type_id','verification_status','status','created_on'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function getLastRecord($water_connection_id_md5)
    {
        $builder = $this->db->table('view_water_level_pending')
                        ->select('*')
                        ->where('md5(apply_connection_id::text)', $water_connection_id_md5)
                        ->where('verification_status', 0)
                        ->where('status', 1)
                        ->orderBy('id','DESC')
                        ->get();
        //echo $this->db->getLastQuery();
        return $builder->getFirstRow('array');
    }
    public function waterdareceiveList($receiver_user_type_id,$from_date,$to_date,$ward_permission)
    {
        try{
             return $this->db->table('view_water_level_pending')
                        ->select('*')
                        ->where('receiver_user_type_id',$receiver_user_type_id)
                        ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->whereIn('ward_id', $ward_permission)
                        //->where('sender_user_type_id', 0)
                        ->where('verification_status', 0)
                        ->where('status',1)
                        //->where('doc_upload_status',1)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray();

                       // echo $this->getLastQuery();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function waterdareceivebywardidList($receiver_user_type_id,$from_date,$to_date,$ward_mstr_id)
    {
        try{
             return $this->db->table('view_water_level_pending')
                        ->select('*')
                        ->where('receiver_user_type_id', $receiver_user_type_id)
                        ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->where('ward_id', $ward_mstr_id)
                        //->where('sender_user_type_id',0)
                        ->where('verification_status ', 0)
                        ->where('status', 1)
                        //->where('doc_upload_status',1)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function waterlevelpendingdetailbyid($id)
    {        
        try{
            if(is_numeric($id))
            {
                $id = md5($id);
            }
            $builder = $this->db->table('view_water_level_pending')
                        ->select('*')
                        ->where('md5(id::text)', $id)
                        ->where('status',1)
                        ->get();
                        //echo $this->db->getLastQuery();
           $builder =  $builder->getResultArray();
            return $builder[0];
        }
        catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function daapprovedList($sender_user_type_id,$from_date,$to_date,$ward_permission)
    {
        try{
             //$names = ['10', '11'];
            return $this->db->table('view_water_level_pending')
                        ->select('*')
                        ->where('receiver_user_type_id',$sender_user_type_id)
                        ->where('sender_user_type_id',0)
                        ->where('verification_status ',1)
                         ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->whereIn('ward_id', $ward_permission)
                        ->where('status',1)
                        //->where('doc_status',1)
                        ->orderBy('id','DESC')
                        ->get()           
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function wardwise_daapprovedList($sender_user_type_id,$from_date,$to_date,$ward_mstr_id)
    {
        try{
             //$names = ['10', '11'];
             return $this->db->table('view_water_level_pending')
                        ->select('*')
                        ->where('receiver_user_type_id',$sender_user_type_id)
                        ->where('sender_user_type_id',0)
                        ->where('verification_status ',1)
                         ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->where('status',1)
                        //->where('doc_status',1)
                        ->where('ward_id', $ward_mstr_id)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function waterjereceiveList($receiver_user_type_id,$from_date,$to_date,$ward_permission)
    {
        try{
             $data= $this->db->table('view_water_level_pending')
                        ->select('*')
                        ->where('receiver_user_type_id',$receiver_user_type_id)
                        ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->whereIn('ward_id', $ward_permission)
                        //->where('sender_user_type_id',0)
                        ->where('verification_status ',0)
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
    public function waterjereceivebywardidList($receiver_user_type_id,$from_date,$to_date,$ward_mstr_id)
    {
        try{
             return $this->db->table('view_water_level_pending')
                        ->select('*')
                        ->where('receiver_user_type_id',$receiver_user_type_id)
                        ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->where('ward_id', $ward_mstr_id)
                        //->where('sender_user_type_id',0)
                        ->where('verification_status ',0)
                        ->where('status',1)
                        //->where('doc_upload_status',1)
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
                $data =  $this->db->table('view_water_level_pending')
                        ->select('*')
                        ->where('sender_user_type_id',$sender_user_type_id)
                        ->where('verification_status ',0)
                         ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->whereIn('ward_id', $ward_permission)
                        ->where('status',1)
                        //->where('doc_status',1)
                        ->orderBy('id','DESC')
                        ->get()           
                        ->getResultArray();
                //echo $this->db->getLastQuery();
                return $data;
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function wardwise_forwardList($sender_user_type_id,$from_date,$to_date,$ward_mstr_id)
    {
        try{
             //$names = ['10', '11'];
             return $this->db->table('view_water_level_pending')
                        ->select('*')
                        ->where('sender_user_type_id',$sender_user_type_id)
                        ->where('verification_status ',0)
                         ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->where('status',1)
                        //->where('doc_status',1)
                        ->where('ward_id', $ward_mstr_id)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function waterconsumerList($receiver_user_type_id,$from_date,$to_date,$ward_permission)
    {
        try{
             return $this->db->table('view_water_level_pending')
                        ->select('*')
                        ->where('receiver_user_type_id',$receiver_user_type_id)
                        ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->whereIn('ward_id', $ward_permission)
                        //->where('sender_user_type_id',0)
                        ->where('verification_status ',1)
                        ->where('status',1)
                        //->where('doc_upload_status',1)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function waterconsumerbywardidList($receiver_user_type_id,$from_date,$to_date,$ward_mstr_id)
    {
        try{
             return $this->db->table('view_water_level_pending')
                        ->select('*')
                        ->where('receiver_user_type_id',$receiver_user_type_id)
                        ->where('date(created_on) >=', $from_date)
                        ->where('date(created_on) <=', $to_date)
                        ->where('ward_id', $ward_mstr_id)
                        //->where('sender_user_type_id',0)
                        ->where('verification_status ',1)
                        ->where('status',1)
                        //->where('doc_upload_status',1)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function getDataNew($where = array(),$column=array('*'))
    {
        try
        {   $data=array();
            $builder =$this->db;  
            $builder = $builder->table('view_water_level_pending')
                        ->select($column);
            if(count($where)>0)
            {
                $builder=$builder->where($where);
            }
            $data = $builder->get();
            if(sizeof($data)==1)
                $data=$data->getFirstRow('array');
            else
                $data=$data->getResultArray();
            return $data;
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }
}
?> 