<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class TradeFirmOwnerModel extends Model
{
    protected $db;
    protected $table = 'tbl_firm_owner_name';
    protected $allowedFields = ['id','apply_licence_id','owner_name','guardian_name','address','mobile','city','district','state','emp_details_id','created_on','status','document_id','id_no'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

  public function insertdataexcel($input)
  {
         $builder = $this->db->table($this->table)
                            ->insert([
                               'apply_licence_id'=>$input["apply_licence_id"],
                               'owner_name'=>$input["owner_name"],
                               'guardian_name'=>$input["guardian_name"],
                               'emailid'=>$input["emailid"],
                               'mobile'=>$input["mobile"],
                               'address'=>$input["address"],                              
                               'emp_details_id'=>$input["emp_details_id"],
                               'created_on'=>$input["created_on"],
                               'document_id'=>$input["document_id"],
                               'id_no'=>$input["id_no"]
                            ]);
                            //echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
   }
   public function insertdata($input){
         $builder = $this->db->table($this->table)
                            ->insert([
                               'apply_licence_id'=>$input["apply_licence_id"],
                               'owner_name'=>$input["owner_name"],
                               'guardian_name'=>$input["guardian_name"],
                               'emailid'=>$input["emailid"],
                               'mobile'=>$input["mobile"],                                                             
                               'emp_details_id'=>$input["emp_details_id"],
                               'created_on'=>$input["created_on"]
                            ]);
                           // echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();

   }

   public function insertrenewdata($input){
        if($input['emp_details_id'])
            $emp_id = "'$input[emp_details_id]'";
          else
            $emp_id = null;

          $sql_prop = "insert into tbl_firm_owner_name(apply_licence_id,owner_name,guardian_name,address,mobile,city,district,state".($emp_id?",emp_details_id":'').",created_on,status,document_id,id_no) 
         select '".$input['apply_licence_id']."',owner_name,guardian_name,address,mobile,city, district,state
         ".($emp_id?",'$input[emp_details_id]'":'').",'".$input['created_on']."', '1',document_id,id_no from tbl_firm_owner_name 
         where apply_licence_id='".$input['licence_id']."'";

        //  $sql_prop = "insert into tbl_firm_owner_name(apply_licence_id,owner_name,guardian_name,address,mobile,city,district,state,emp_details_id,created_on,status,document_id,id_no) 
        //  select '".$input['apply_licence_id']."',owner_name,guardian_name,address,mobile,city, district,state,
        //  '".$input['emp_details_id']."','".$input['created_on']."', '1',document_id,id_no from tbl_firm_owner_name 
        //  where apply_licence_id='".$input['licence_id']."'";
        $this->db->query($sql_prop);
        //echo $this->db->getLastQuery();die;
        $con_dtl_id = $this->db->insertID();
       
         return $con_dtl_id;
     }

   public function getdatabyid_md5($applyid)
    {
        try{        
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('status', 1)
                        ->where('md5(apply_licence_id::text)', $applyid)
                        ->get();
                   // echo $this->db->getLastQuery();exit;
           return $builder->getResultArray();

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function getOwnerById($id){
        $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('status', 1);
        if(is_numeric($id)){
            $builder  = $builder->where("id",$id);
        }else{
            $builder = $builder->where("md5(id::text)",$id);
        }
        $builder = $builder->get();
        // echo $this->db->getLastQuery();exit;
        return $builder->getFirstRow("array");
    }


    public function getOwnerDetails($apply_licence_id){
      try{
          $builder = $this->db->table($this->table)
                    ->select('*')
                    ->where('apply_licence_id',$apply_licence_id)
                    ->where('status',1)
                    ->get();
                    //echo $this->db->getLastQuery();
          return $builder->getResultArray();
      }catch(Exception $e){
        echo $e->getMessage();
      }
    }
   public function setStatusZero($apply_licence_id){
    try{
        return $builder = $this->db->table($this->table)
                          ->where('md5(apply_licence_id::text)',$apply_licence_id)
                          ->delete();
/*                          ->update([
                                    "status"=>0
                                ]);*/
    }catch(Exception $e){
      echo $e->getMessage();
    }
   }

public function updatedetails($input)
{
    try
    {
        $builder = $this->db->table($this->table)
                ->where('id',$input["owner_id"])                                                   
                ->update([
                          "owner_name"=>$input["owner_name"],
                          "guardian_name"=>$input["guardian_name"],
                          "mobile"=>$input["mobile"],
                          "emailid"=>$input["emailid"],
                          "address"=>$input["address"],
                          "document_id"=>$input["document_id"] ?? NULL,
                          "id_no"=>$input["id_no"] ?? NULL,

                      ]);
        // echo $this->db->getLastQuery();
        return $builder;
    }
    catch(Exception $e)
    {
        echo $e->getMessage();
    }
}
public function insertDetails($input)
{
    try
    {
        $builder = $this->db->table($this->table)
                ->where('id',$input["owner_id"])                                                   
                ->insert([
                          "apply_licence_id"=>$input['apply_licence_id'],
                          "owner_name"=>$input["owner_name"],
                          "guardian_name"=>$input["guardian_name"],
                          "mobile"=>$input["mobile"],
                          "emailid"=>$input["emailid"],
                          "address"=>$input["address"],
                          "document_id"=>$input["document_id"] ?? NULL,
                          "id_no"=>$input["id_no"] ?? NULL,

                      ]);
        // echo $this->db->getLastQuery();
        return $builder;
    }
    catch(Exception $e)
    {
        echo $e->getMessage();
    }
}

public function updateRenewalDetails($input)
{
    try
    {
        $builder = $this->db->table($this->table)
                ->where('apply_licence_id',$input["apply_licence_id"]) 
                ->update([
                          "mobile"=>$input["mobile"],
                          "emailid"=>$input["emailid"],
                          
                      ]);
        // echo $this->db->getLastQuery();
        return $builder;
    }
    catch(Exception $e)
    {
        echo $e->getMessage();
    }
}

public function updatedetailsowner($input){
    try
    {
          $builder = $this->db->table($this->table)
                  ->where('id',$input["owner_id"])                                                   
                  ->update([
                            "owner_name"=>$input["owner_name"],
                            "guardian_name"=>$input["guardian_name"],
                            "mobile"=>$input["mobile"],
                            "emailid"=>$input["emailid"],
                            "address"=>$input["address"],
                            "document_id"=>$input["document_id"] ?? NULL,
                            "id_no"=>$input["id_no"] ?? NULL,
                            'emp_details_id'=>$input["emp_details_id"],
                            'created_on'=>$input["created_on"]
                            
                        ]);
        //echo $this->db->getLastQuery();
        return $builder;
      }
      catch(Exception $e)
      {
          echo $e->getMessage();
      }
    }
    
}
?>