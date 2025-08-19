<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class ModelPropNoticeSerial extends Model 
{
    protected $db;
    protected $table = 'tbl_notice_serial';
    // protected $allowedFields = ['id', 'subject', 'sender_id', 'receiver_id', 'related_id', 'created_on', 'remarks', 'status','ulb_id','ink','status'];
    
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function insertNoticeSerialData($input){
        try{
            $builder = $this->db->table($this->table)
                            ->insert($input);
              return $insert_id=$this->db->insertID();
        return $insert_id = $this->db->insertID();
        }catch(Exception $e){
            echo $e->getMessage(); 
        }
    }

}