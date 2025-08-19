<?php 
namespace App\Models\Water;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class TblConsumerRequest  extends Model
{
    protected $db;
    protected $table = 'tbl_consumer_requests';
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function store(array $data)
    {
        $this->db->table($this->table)
                ->insert($data);
        $insert_id=$this->db->insertID();
        $prefix = $data["request_type_id"]==1?"NT":($data["request_type_id"]==2?"PS":($data["request_type_id"]==3?"CD":"OT"));
        $request_no = $prefix.date("Y").(str_pad($insert_id,6,"0",STR_PAD_LEFT));
        $this->updateData($insert_id,["request_no"=>$request_no]);
        return $insert_id;
    }

    public function updateData(int $id,array $data){
        return $this->db->table($this->table)->where("id",$id)->update($data);
    }

    public function getAllData(){
        return self::select("*")->where("status",1)->get()->getResultArray();
    }

    public function getStatus($id){
        $status ="";
        $requestDtl = self::where("id",$id)->get()->getFirstRow("array");
        $curentRole = $this->db->query("select * from view_user_type_mstr where id = ".$requestDtl["pending_at_role_id"])->getFirstRow("array");
        if($requestDtl["pending_status"]==5){
            $status="Application Approved on ".$requestDtl["approval_rejected_date"];
        }
        elseif($requestDtl["pending_status"]==3){
            $status="Application Rejected on ".$requestDtl["approval_rejected_date"];
        }
        elseif($requestDtl["payment_status"]==0){
            $status="Payment Pending";
        }
        elseif($requestDtl["payment_status"]==2){
            $status="Payment is not clear";
        }
        elseif($requestDtl["doc_upload_status"]==0){
            $status="Document upload pending";
        }
        elseif($requestDtl["is_parked"]=="t"){
            $status = "Application back to citizen from ".$curentRole["user_type"];
        }
        elseif($requestDtl["pending_status"]!==0 && $requestDtl["pending_status"]!=5){
            $status = "Application pending at ".$curentRole["user_type"];
        }
        return $status;
    }
}