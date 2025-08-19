<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_notice extends Model 
{
    protected $db;
    protected $table = 'tbl_prop_notices';
    protected $allowedFields = ['id', 'subject', 'sender_id', 'receiver_id', 'related_id', 'created_on', 'remarks', 'status','ulb_id','ink','status'];
    
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function insertNoticeData($input){
        try{
            $builder = $this->db->table($this->table)
                            ->insert($input);
                            // echo $this->db->getLastQuery();
              return $insert_id=$this->db->insertID();
        return $insert_id = $this->db->insertID();
        }catch(Exception $e){
            echo $e->getMessage(); 
        }
    }

    public function getNotice($prop_id)
    {
        try{
            $builder = $this->db->table($this->table)
                      ->select('*')
                      ->where('prop_dtl_id',$prop_id)
                      ->where('status',1)
                      ->orderBy('created_on','DESC')
                      ->get();
            return $builder->getResultArray();
          }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    public function getNoticeById_old($notice_id)
    {
        try{
            $builder = $this->db->table($this->table)
                      ->select('*')
                      ->where('MD5(id::text)',$notice_id)
                      ->where('status',1)
                      ->orderBy('created_on','DESC')
                      ->get();
            return $builder->getRowArray();
          }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    public function getNoticeById($notice_id)
    {
        try{
            if(is_numeric($notice_id)){
                $notice_id = md5($notice_id);
            }
            $builder = $this->db->table($this->table)
                      ->select('*')
                      ->where('MD5(id::text)',$notice_id)
                      ->whereIN('status',[1,5])
                      ->orderBy('created_on','DESC')
                      ->get();
            return $builder->getRowArray();
          }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    public function getCount($prop_id)
    {
        try{
            $builder = $this->db->table($this->table)
                      ->select('count(*) as serial')
                      ->where('prop_dtl_id',$prop_id)
                      ->where('status',1)
                      ->get();
            return $builder->getRowArray();
          }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    public function updateRecord($data, $notice_id)
	{
		return $this->db->table($this->table)
					->where("id", $notice_id)
					->Update($data);
	}
	
	public function getNoticeList()
    {
        try{
            $builder = $this->db->table($this->table)
                        ->join("view_prop_dtl_owner_ward_prop_type_ownership_type", "view_prop_dtl_owner_ward_prop_type_ownership_type.prop_dtl_id=tbl_prop_notices.prop_dtl_id")
                        ->where("tbl_prop_notices.status", 1)
                        ->OrderBy("tbl_prop_notices.id desc")
                        ->get();
            return $builder->getResultArray();
          }catch(Exception $e){
            echo $e->getMessage();
        }
    }
}