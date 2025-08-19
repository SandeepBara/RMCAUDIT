<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_trade_licence_trade_items extends Model 
{
    protected $db;
    protected $table = 'tbl_licence_trade_items';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id', 'licence_id','trade_items_id', 'created_on','emp_details_id', 'status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

   public function insertdata($input){

         $sql_prop = "insert into tbl_licence_trade_items(licence_id,trade_items_id,emp_details_id,created_on,status) select '".$input['licence_id']."', trade_items_id,'".$input['emp_details_id']."','".$input['created_on']."', '1' from tbl_application_trade_items where apply_licence_id='".$input['apply_licence_id']."'";
        $this->db->query($sql_prop);
        $con_dtl_id = $this->db->insertID();
        //echo $this->db->getLastQuery();
         return $con_dtl_id;
     }
     public function updateinsertdata($input){
        $this->db->table($this->table)
                            ->where('licence_id', $input['licence_id'])
                            ->where('status',1)
                            ->update([
                                    'status'=>0
                                    ]);
            
         $sql_prop = "insert into tbl_licence_trade_items(licence_id,trade_items_id,emp_details_id,created_on,status) select '".$input['licence_id']."', trade_items_id,'".$input['emp_details_id']."','".$input['created_on']."', '1' from tbl_application_trade_items where apply_licence_id='".$input['apply_licence_id']."'";
        $this->db->query($sql_prop);
        $con_dtl_id = $this->db->insertID();
        //echo $this->db->getLastQuery();
         return $con_dtl_id;
     }
    public function get_licence_md5dtl($id)
    {
        try{
             return $this->db->table($this->table)
                        ->select('*')
                        ->where('status',1)
                        ->where('licence_id',$id)
                        ->get()
                        ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
   public function deleteRecord($licence_id){
    try{
         return $builder = $this->db->table($this->table)
                          ->where('licence_id',$licence_id)
                          ->delete();
    }catch(Exception $e){
        echo $e->getMessage();
    }
   }
   public function insertRecord($data){
    try{
        return $builder = $this->db->table($this->table)
                    ->insert([
                                "licence_id"=>$data['licence_id'],
                                "emp_details_id"=>$data['emp_details_id'],
                                "created_on"=>$data['created_on']
                            ]);
    }catch(Exception $e){
        echo $e->getMessage();
    }
   }
}