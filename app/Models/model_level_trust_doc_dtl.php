<?php
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_level_trust_doc_dtl extends Model
{
    protected $db;
    protected $table = 'tbl_level_trust_doc_dtl';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id', 'saf_dtl_id','sender_user_type_id','receiver_user_type_id','forward_date','forward_time', 'created_on','remarks','verification_status', 'status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function insertData($input) {
        try{
            $builder = $this->db->table($this->table)
                        ->insert([
                        "saf_dtl_id"=>$input["saf_dtl_id"],
                        "sender_user_type_id"=>$input["sender_user_type_id"],
                        "receiver_user_type_id"=>$input["receiver_user_type_id"],
                        "forward_date"=> $input["forward_date"],
                        "forward_time"=> $input["forward_time"],
                        "remarks"=> $input["remarks"],
                        "created_on"=> $input["created_on"],
                        "sender_emp_details_id"=> $input["sender_emp_details_id"]
                        ]);
            return $insert_id = $this->db->insertID();
            
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function getLastRecord($input)
    {
        $sql="SELECT * FROM tbl_level_trust_doc_dtl where saf_dtl_id=$input[saf_dtl_id] and status=1 and verification_status=0 and receiver_user_type_id=$input[receiver_user_type_id]";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        //echo $this->getLastQuery();
        return $result;
    }

    public function updatelevelpendingById($input)
    {
        $update_data=array("verification_status"=> $input['verification_status']);
        $update_data["receiver_emp_details_id"]=$input["receiver_emp_details_id"];

        $builder = $this->db->table($this->table)
                    ->where('id', $input['level_id']);
        $builder= $builder->update($update_data);
        return $builder;

    }
}