<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class water_level_pending_model extends Model
{

    protected $table = 'tbl_level_pending';
    protected $allowedFields = ['id','apply_connection_id','sender_user_type_id','receiver_user_type_id','forward_date','forward_time','remarks','emp_details_id','created_on','status','verification_status'];

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}
     public function getDealingAssistantStatus($apply_connection_id){
        try{
            $builder = $this->db->table($this->table)
                    ->select('verification_status,forward_date')
                    ->where('apply_connection_id',$apply_connection_id)
                    ->where('receiver_user_type_id',12)
                    ->where('status',1)
                    ->orderBy('id','DESC')
                    ->limit(1)
                    ->get();
                    //echo $this->getLastQuery();
            // return $builder->getResultArray()[0];
            return $builder->getFirstRow('array');

        }
        catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getJuniorEngineerStatus($apply_connection_id){
        try{
            $builder = $this->db->table($this->table)
                    ->select('verification_status,forward_date')
                    ->where('apply_connection_id',$apply_connection_id)
                    ->where('receiver_user_type_id',13)
                    ->where('status',1)
                    ->orderBy('id','DESC')
                    ->limit(1)
                    ->get();
                   // echo $this->getLastQuery();
            // return $builder->getResultArray()[0];
            return $builder->getFirstRow('array');
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getSectionHeadStatus($apply_connection_id){
        try{
            $builder = $this->db->table($this->table)
                    ->select('verification_status,forward_date')
                    ->where('apply_connection_id',$apply_connection_id)
                    ->where('receiver_user_type_id',14)
                    ->where('status',1)
                    ->orderBy('id','DESC')
                    ->limit(1)
                    ->get();
                   // echo $this->getLastQuery();
            //return $builder->getResultArray()[0];
            return $builder->getFirstRow('array');
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getAssistantEngineerStatus($apply_connection_id){
        try{
            $builder = $this->db->table($this->table)
                    ->select('verification_status,forward_date')
                    ->where('apply_connection_id',$apply_connection_id)
                    ->where('receiver_user_type_id',15)
                    ->where('status',1)
                    ->orderBy('id','DESC')
                    ->limit(1)
                    ->get();
                   // echo $this->getLastQuery();
            // return $builder->getResultArray()[0];
            return $builder->getFirstRow('array');
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getExecutiveOfficerStatus($apply_connection_id){
        try{
            $builder = $this->db->table($this->table)
                    ->select('verification_status,forward_date')
                    ->where('apply_connection_id',$apply_connection_id)
                    ->where('receiver_user_type_id',16)
                    ->where('status',1)
                    ->orderBy('id','DESC')
                    ->limit(1)
                    ->get();
                   // echo $this->getLastQuery();
            // return $builder->getResultArray()[0];
            return $builder->getFirstRow('array');
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function insertLevelPendingData($input){
        try
        {
          $builder = $this->db->table($this->table)
                ->insert([
                    "apply_connection_id" => $input['apply_connection_id'],
                    "sender_user_type_id" => $input['sender_user_type_id'],
                    "receiver_user_type_id" =>$input['receiver_user_type_id'],
                    "forward_date" => $input['forward_date'],
                    "forward_time" => $input['forward_time'],
                    "created_on" => $input['created_on'],
                    "emp_details_id" => $input['emp_details_id']
                ]);
               // echo $this->db->getLastQuery();
            return $insert_id = $this->db->insertId();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
	
	
	
	public function getremarks($apply_connection_id)
    {
        try
        {
            $builder = $this->db->table($this->table)
                    ->select('receiver_user_type_id, forward_date, forward_time, remarks, created_on')
                    ->where('md5(apply_connection_id::text)', $apply_connection_id)
                    ->where('status<>', 0)
                    ->get();
            //echo $this->db->getLastQuery();die;
            return $builder->getResultArray();
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }




    public function getAllRecords($water_connection_id)
    {
        $builder = $this->db->table($this->table)
                        ->select('tbl_level_pending.*, user_type')
                        ->join('view_user_type_mstr', 'view_user_type_mstr.id = tbl_level_pending.receiver_user_type_id')
                        ->where("apply_connection_id", $water_connection_id)
                        ->where("verification_status !=",0)
                        ->orderBy("id")
                        ->get();
        // echo $this->db->getLastQuery();die;
        return $builder->getResultArray('array');
    }
}