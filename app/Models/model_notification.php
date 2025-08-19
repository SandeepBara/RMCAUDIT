<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_notification extends Model 
{
    protected $db;
    protected $table = 'tbl_notification';
    protected $allowedFields = ['id', 'subject', 'sender_id', 'receiver_id', 'related_id', 'created_on', 'remarks', 'status','ulb_id','ink','status'];
    
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function getCount($emp_id,$curr_date)
    {
        $sql="select count(id) as count from tbl_notification where emp_details_id=".$emp_id." and date(created_on)='".$curr_date."'";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        echo $this->getLastQuery();

        return $result['count'];
       
    }

    public function getNotifIdbyVerifydateEmpId($emp_id,$curr_date)
    {
        $sql="select id from tbl_notification where emp_details_id=".$emp_id." and date(created_on)='".$curr_date."'";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        return $result['id'];


        
    }
    
    public function insertNotificationData($input){
        try{
            $builder = $this->db->table($this->table)
                            ->insert([
                                  "subject"=>$input["subject"],
                                  "sender_id"=>$input["sender_id"],
                                  "remarks"=>$input["remarks"],
                                   "created_on"=>$input["created_on"],
                                   "remarks"=>$input["remarks"],
                                   "receiver_id"=>$input["receiver_id"],
                                   "emp_details_id"=>$input["emp_details_id"],
                                   "cash_verify_id"=>$input["cash_verify_id"]
                                   
                                  ]);
                            // echo $this->db->getLastQuery();
              return $insert_id=$this->db->insertID();
        return $insert_id = $this->db->insertID();
        }catch(Exception $e){
            echo $e->getMessage(); 
        }
    }
    public function getAllNotVerifiedData($data){
      try{
        $builder = $this->db->table($this->table)
                  ->select('*')
                  ->where('receiver_id',$data['employee_id'])
                  ->where('date(created_on)>=',$data['from_date'])
                  ->where('date(created_on)<=',$data['to_date'])
                  ->where('status',1)
                  ->orderBy('created_on','ASC')
                  ->get();
        return $builder->getResultArray();
      }catch(Exception $e){
        echo $e->getMessage();
      }
    }
}