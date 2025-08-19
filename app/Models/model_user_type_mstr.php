<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_user_type_mstr extends Model
{
    protected $db;
    protected $table = 'tbl_user_type_mstr';

    protected $primaryKey = 'id';
    protected $allowedFields = ['id', 'user_type','user_for','status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function user_type_list(){
        $builder = $this->db->table($this->table);
        $builder->select('id, user_type,user_for,status');
        $builder->where('status', 1);
        $builder = $builder->get();
        //echo $this->db->getLastQuery();
        $builder = $builder->getResultArray();
        return $builder;
    }
	public function insertData($input){
        $builder = $this->db->table($this->table)
                            ->insert([
                  "user_type"=>$input["user_type"],
                  "user_for"=>$input["user_for"]
				  ]);
							//echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }
	public function checkdata($input){
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('user_type', $input['user_type']);
        $builder->where('status', 1);
        $builder = $builder->get();
        $builder = $builder->getResultArray();
        return $builder[0];

    }
	public function getdatabyid($id){ $builder = $this->db->table($this->table);
        $builder->select('id,user_type,user_for,status');
        $builder->where('md5(id::text)', $id);
        $builder->where('status', 1);
        $builder = $builder->get();
        $builder = $builder->getResultArray();
        return $builder[0];


    }
	public function checkupdatedata($input){
        $builder = $this->db->table($this->table);
        $builder->select('id');
        $builder->where('user_type', $input['user_type']);
		$builder->where('md5(id::text) !=', $input['id']);
        $builder->where('status', 1);
        $builder = $builder->get();
		//echo $this->db->getLastQuery();
        $builder = $builder->getResultArray();
        return $builder[0];

    }
	public function updatedataById($input){
        return $builder = $this->db->table($this->table)
                            ->where('md5(id::text)', $input['id'])
                            ->update([
                                    'user_type'=>$input['user_type'],
                                    'user_for'=>$input['user_for']
                                    ]);
    }
    public function deletedataById($id){
        return $builder = $this->db->table($this->table)
                            ->where('id', $id)
                            ->update([
                                    'status'=>'0'
                                    ]);
    }
    public function userTypeList(){
         try{
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('id!=', 1)
                        ->where('id!=', 2)
                        ->where('user_for', "AGENCY")
                        ->where('status', 1)
                        ->get();
                        //echo $this->db->getLastQuery();
          return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
   public function ajax_data($user_type_mstr_id){
        try{
            $builder = $this->db->table($this->table)
                            ->select('*')
                            ->where('id!=', 1)
                            ->where('id!=', 2)
                            ->where('user_for', "AGENCY")
                            ->where('id!=', $user_type_mstr_id)
                            ->where('status', 1)
                            ->get();
                            //echo $this->db->getLastQuery();
              return $builder->getResultArray();
            }catch(Exception $e){
                return $e->getMessage();   
        }
   }
   public function under_userHierarchyList($under_user_type_mstr_id){
    try{
         $builder = $this->db->table($this->table)
                        ->select('user_type')
                        ->where('status', 1)
                        ->where('id',$under_user_type_mstr_id)
                        ->get();
           return $builder->getResultArray();
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
   }
   public function userList(){
    try{
        $builder = $this->db->table($this->table)
                    ->select('*')
                    ->where('status',1)
                    ->where('id!=',1)
                    ->where('user_for','AGENCY')
                    ->get();
         return $builder->getResultArray();
    }catch(Excption $e){
        echo $e->getMessage();
    }
   }
   public function ulbUserList(){
    try{
        $builder = $this->db->table($this->table)
                    ->select('*')
                    ->where('status',1)
                    ->where('id!=',1)
                    ->where('id!=',2)
                    ->where('user_for','ULB')
                    ->get();
         return $builder->getResultArray();
    }catch(Excption $e){
        echo $e->getMessage();
    }
   }
   public function userDetailsList(){
    try{
         $builder = $this->db->table($this->table)
                 ->select('id')
                 ->where('status',1)
                 ->where('user_for','ULB')
                 ->where('id!=',11)
                 ->where('status',1)
                 ->get();
        return $builder->getResultArray();
    }catch(Exception $e){
        echo $e->getMessage();
    }
   }
}
?>