<?php 
namespace App\Models\DbSystem;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class TblWfRoleMapMstr  extends Model
{
    protected $db;
    protected $table = 'tbl_wf_role_map_mstr';
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function store(array $data)
    {
        $this->db->table($this->table)
                ->insert($data);
        $insert_id=$this->db->insertID();
        return $insert_id;
    }

    public function updateData(int $id,array $data){
        return $this->db->table($this->table)->where("id",$id)->update($data);
    }

    public function getAllData(){
        return self::select("*")->orderBy("tbl_wf_mstr_id","ASC")->orderBy("sl_no","ASC")->get()->getResultArray();
    }

    public function getWfMaps($tbl_wf_mstr_id){
        
        return self::select("*")->where("tbl_wf_mstr_id",$tbl_wf_mstr_id)
            ->where("status",1)
            ->orderBy("sl_no","ASC")
            ->get()
            ->getResultArray();
    }

    public function getWfMapsOrm(){
        return self::select("*")
        ->where("status",1)
        ->orderBy("sl_no","ASC");
    }

    public function getWfMapPermission($tbl_wf_mstr_id,$user_type_id){
        return $this->getWfMapsOrm()->where("tbl_wf_mstr_id",$tbl_wf_mstr_id)
            ->where("role_id",$user_type_id)
            ->get()
            ->getFirstRow("array");
    }

    public function getInitiatorRole($tbl_wf_mstr_id){
        return $this->getWfMapsOrm()->where("tbl_wf_mstr_id",$tbl_wf_mstr_id)
        ->where("is_initiator",true)
        ->get()
        ->getFirstRow("array");
    }
    public function getFinisherRole($tbl_wf_mstr_id){
        return $this->getWfMapsOrm()->where("tbl_wf_mstr_id",$tbl_wf_mstr_id)
        ->where("is_finiser",true)
        ->get()
        ->getFirstRow("array");
    }

    
}