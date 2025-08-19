<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;
class filePermission extends Model 
{
    protected $db;
    protected $table = 'tbl_menu_permission_dtl';

    protected $primaryKey = 'id';
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function insert_file(array $data)
    {
        $this->db->table($this->table)
                 ->insert($data);
                //  echo($this->db->getLastQuery());
        return $this->db->insertID();
    }

    public function getPermitedFile($userTypeId,$empDtlId=null)
    {
        $builder = $this->db->table($this->table)
                    ->select('menu_mstr_id,menu_permission_id,user_type_mstr_id,class_name,function_name');
        if( $userTypeId!=1)
        {
            $builder->where('user_type_mstr_id', $userTypeId);

        }
         $builder->where('status', 1);
        $builder->where('is_access', TRUE);
        $builder->groupBy('menu_mstr_id,menu_permission_id,user_type_mstr_id,class_name,function_name');
        $builder->orderBy('class_name');
        $builder = $builder->get();
        $builder = $builder->getResultArray();
        // echo($this->db->getLastQuery());
        return $builder??null;
    }
    public function checkPermitFile($data)
    {
        $temp =  $this->db->table($this->table)
        ->select("*")
        ->where($data)
        ->get()
        ->getFirstRow('array');
        // echo($this->db->getLastQuery());
        return$temp;

    }
    public function update_data($data, $id)
    {
       $temp = $this->db->table($this->table)
                         ->where('id', $id)
                         ->update($data);
                        //  echo($this->db->getLastQuery());
                         return$temp;
    }
    public function deactivateAllfile($where)
    {
        try{

            $temp = $this->db->table($this->table)
                             ->where('user_type_mstr_id!=', 1)
                             ->where($where)
                             ->update([
                                'status'=>0,
                                "is_access"=>false
                                ]);
                            //  echo($this->db->getLastQuery());
                             return$temp;
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }
    public function getData($where)
    {
        return $this->db->table($this->table)
                    ->select("*")
                    ->where($where)
                    ->get()
                    ->getResultArray();
    }

}