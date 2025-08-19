<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_trade_licence_owner_name extends Model 
{
    protected $db;
    protected $table = 'tbl_licence_owner_name';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id', 'licence_id','owner_name','address','mobile','city','district', 'guardian_name','state', 'created_on','emp_details_id', 'status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

   public function insertdata($input){

         $sql_prop = "insert into tbl_licence_owner_name(licence_id,owner_name,address,mobile,city,district,
         guardian_name, state,emp_details_id,created_on,status) select '".$input['licence_id']."', owner_name,address,mobile,city,district,guardian_name, state,
         '".$input['emp_details_id']."','".$input['created_on']."', '1' from tbl_firm_owner_name where apply_licence_id='".$input['apply_licence_id']."'";
        $this->db->query($sql_prop);       
       // echo $this->db->getLastQuery();
         $con_dtl_id = $this->db->insertID();
         return $con_dtl_id;
     }
    public function get_licence_md5dtl($id)
    {
        try{
             return $this->db->table($this->table)
                        ->select('*')
                        ->where('status',1)
                        ->where('md5(licence_id::text)',$id)
                        ->get()
                        ->getResultArray();
                        /*echo $this->db->getLastQuery();*/
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
   public function getOwnerName($licence_id){
    try{
        $sql = "select string_agg(owner_name, ',') as owner_name from tbl_licence_owner_name where licence_id=".$licence_id." group by licence_id";
            $sql = $this->query($sql); 
                        //echo $this->getLastQuery();
          $builder = $sql->getFirstRow("array");
           return  $builder["owner_name"];
        }catch(Exception $e){
            return $e->getMessage();   
        }
   }
  public function getOwnerMobileNo($licence_id){
    try{
        $builder = $this->db->table($this->table)
                  ->select('mobile')
                  ->where('licence_id',$licence_id)
                  ->where('status',1)
                  ->get();
        return $builder->getResultArray()[0];
    }catch(Exception $e){
        echo $e->getMessage();
    }
  }

  public function getOwnerDetails($licence_id)
  {
      try
      {
          $builder = $this->db->table($this->table)
                  ->select("string_agg(mobile::varchar,',') as mobile,string_agg(owner_name,',') as owner_name,string_agg(guardian_name,',') as guardian_name")
                  ->where('licence_id',$licence_id)
                  ->groupBy('licence_id')
                  ->where('status',1)
                  ->get();
                  //echo $this->db->getLastQuery();
          return $result = $builder->getResultArray()[0];
      }
      catch(Exception $e)
      {
          echo $e->getMessage();
      }
  }
  
  public function getLicenceDetails($id){
    try{
        $builder = $this->db->table($this->table)
                  ->select('*')
                  ->where('licence_id',$id)
                  ->get();
        return $builder->getResultArray();
    }catch(Exception $e){
      echo $e->getMessage();
    }
  }
  public function insertLicenceOwnerData($data){
    try{
          $builder = $this->db->table($this->table)
                    ->insert([
                              "licence_id" => $data['licence_id'],
                              "owner_name" => $data['owner_name'],
                              "address" => $data['address'],
                              "mobile" => $data['mobile'],
                              "city" => $data['city'],
                              "district" => $data['district'],
                              "emp_details_id" => $data['emp_details_id'],
                              "guardian_name" => $data['guardian_name'],
                              "state" => $data['state'],
                              "created_on" => $data['created_on']
                            ]);
                   // echo $this->db->getLastQuery();
    }catch(Exception $e){
      echo $e->getMessage();
    }
  }
}
