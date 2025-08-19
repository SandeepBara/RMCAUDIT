<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_user_mstr extends Model 
{
    protected $db;
    protected $table = 'tbl_user_mstr';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id', 'user_name', 'user_pass', 'status', 'created_on', 'updated_on','lock_status','user_for'];
    protected $encrypter ;
    
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
        $this->encrypter =  \Config\Services::encrypter();
    }

    public function verifyUserNamePass($input){
        $builder = $this->db->table($this->table);
        $builder->select('id,user_for');
        $builder->where('user_name', $input['user_name']);
        $builder->where('user_pass', $input['user_pass']);
         $builder->where('lock_status', 0);
        $builder->where('status', 1);
        $builder = $builder->get();
        $builder = $builder->getResultArray();
   
        return $builder[0]??null;
    }
    public function verifyUserNamePassMD5($input){
        $builder = $this->db->table($this->table);
        $builder->select('id,user_for');
        $builder->where('user_name', $input['user_name']);
        $builder->where('md5(user_pass::text)', $input['user_pass']);
         $builder->where('lock_status', 0);
        $builder->where('status', 1);
        $builder = $builder->get();
        $builder = $builder->getResultArray();
        return $builder[0]??null;
    }
    public function checkOldPassword($id,$old_user_pass)
    {
        try{
             $builder = $this->db->table($this->table)
                        ->select('id')
                        ->where('user_pass',$old_user_pass)
                        ->where('status', 1)
                        ->where('id',$id)
                        ->get();
        return $builder->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function changePassword($id,$new_pwd,$created_on)
    {
        return $builder = $this->db->table($this->table)
                            ->where('id',$id)
                            ->update([
                                    'user_pass'=>$new_pwd,
                                    'updated_on'=>$created_on
                                    ]);
    }
    public function insertData_old($input){
        try{
            $this->db->table($this->table)
                        ->insert([
                            "user_name" => $input['emp_name'],
                            "user_pass" =>12345,
                            "user_for" =>'AGENCY',
                            "created_on" => $input['created_on'],
                            "updated_on" => $input['created_on']
                        ]);
            return $this->db->insertID();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function insertData($input){
        try{
            $this->db->table($this->table)
                        ->insert([
                            "user_name" => $input["user_name"]??$input['emp_name'],
                            "user_pass" =>base64_encode($this->encrypter->encrypt("12345")),
                            "user_for" =>'AGENCY',
                            "created_on" => $input['created_on'],
                            "updated_on" => $input['created_on']
                        ]);
            return $this->db->insertID();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function lockEmployee($user_mstr_id){
        try{
                return $builder = $this->db->table($this->table)
                    ->where('id',$user_mstr_id)
                    ->update([
                        'lock_status'=>1
                    ]);
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function unlockEmployee($user_mstr_id){
        try{
                return $builder = $this->db->table($this->table)
                    ->where('id',$user_mstr_id)
                    ->update([
                        'lock_status'=>0
                    ]);
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function updateUserMstrById($emp_name,$user_mstr_id,$updated_on)
    {
        try{
             return $builder = $this->db->table($this->table)
                    ->where('id',$user_mstr_id)
                    ->update([
                        'user_name'=>$emp_name,
                        'updated_on'=>$updated_on
                    ]);
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function updateUserName($user_name,$user_mstr_id)
    {
        try
        {
            return $builder = $this->db->table($this->table)
                    ->where('id',$user_mstr_id)
                    ->update([
                        'user_name'=>$user_name
                    ]);
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }
    public function insertUlbData_old($input){
        try{
            $builder = $this->db->table($this->table)
                            ->insert([
                                "user_name" => $input['emp_name'],
                                "user_pass" =>12345,
                                "user_for" =>'ULB',
                                "created_on" => $input['created_on'],
                                "updated_on" => $input['created_on']
                            ]);
            return $insert_id = $this->db->insertID();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function insertUlbData($input){
        try{
            $builder = $this->db->table($this->table)
                            ->insert([
                                "user_name" => $input["user_name"]??$input['emp_name'],
                                "user_pass" =>base64_encode($this->encrypter->encrypt("12345")),
                                "user_for" =>'ULB',
                                "created_on" => $input['created_on'],
                                "updated_on" => $input['created_on']
                            ]);
            return $insert_id = $this->db->insertID();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    
    public function getUserName($id){
        try{
            $builder = $this->db->table($this->table)
                    ->select('user_name')
                    ->where('id',$id)
                    ->where('status',1)
                    ->get();
                    echo $this->getLastQuery();
            return $builder->getResultArray()[0];
        }catch(Exception $e){
           return $e->getMessage(); 
        }
    }
}