<?php 
namespace App\Models\Water;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class TblWfTrack  extends Model
{
    protected $db;
    protected $table = 'tbl_wf_tracks';
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

    public function getAppRemarks($app_id,$ref_type){
        return self::select($this->table.".*,sender_user_type.user_type,receiver_user_type.user_type as receiver_user_type,view_emp_details.emp_name")
                ->join("view_user_type_mstr AS sender_user_type","sender_user_type.id = $this->table.sender_role_id","INNER")
                ->Join("view_user_type_mstr AS receiver_user_type","receiver_user_type.id = $this->table.reciver_role_id","LEFT")
                ->join("view_emp_details","view_emp_details.id = $this->table.sender_user_id","LEFT")
                ->where($this->table.".status",1)
                ->where($this->table.".ref_type",$ref_type)
                ->where($this->table.".ref_value",$app_id)
                ->orderBy($this->table.".created_at","ASC")
                ->get()
                ->getResultArray();
    }
    public function getLasRemarks($app_id,$ref_type){
        return self::select("*")
            ->where("status",1)
            ->where("ref_type",$ref_type)
            ->where("ref_value",$app_id)
            ->orderBy("id","DESC")
            ->get()
            ->getFirstRow("array");
    }

    
}