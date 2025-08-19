<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_user_hierarchy extends Model
{
    protected $db;
	protected $table = 'tbl_user_heirarchy';
    protected $allowedFields = ['id','user_type_mstr_id','under_user_type_mstr_id','created_on','status'];
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function insertUserTypedata(array $input)
    {
        $builder = $this->db->table($this->table)
                            ->insert([
                                  "user_type_mstr_id"=>$input["user_type_mstr_id"],
                                  "under_user_type_mstr_id"=>$input["under_user_type_mstr_id"],
                                   "created_on"=>$input["created_on"]
                                  ]);
                            //echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }
    public function updateUserTypeData($user_type_mstr_id,$under_user_type_mstr_id)
    {
        return $builder = $this->db->table($this->table)
                            ->where('user_type_mstr_id', $user_type_mstr_id)
                            ->where('under_user_type_mstr_id', $under_user_type_mstr_id)
                            ->update([
                                    'status'=>1
                                    ]);
    } 
    public function userHierarchyList($user_type_mstr_id)
    {
        try{
            $builder = $this->db->table('view_userhierarchy_usertypemstr')
                        ->select('under_user_type_mstr_id')
                        ->where('status', 1)
                        ->where('user_type_mstr_id',$user_type_mstr_id)
                        ->get();
           return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function chackdata($user_type_mstr_id){
        try{
            $builder = $this->db->table($this->table)
                        ->select('id')
                        ->where('user_type_mstr_id', $user_type_mstr_id)
                        ->where('status',1)
                        ->get();
           return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function gateDataById($user_type_mstr_id){
        try{
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('user_type_mstr_id',$user_type_mstr_id)
                        ->get();
            return $builder->getResultArray()[0];
        }
        catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function updateStatusZero($user_type_mstr_id)
    {
        return $builder = $this->db->table($this->table)
                            ->where('user_type_mstr_id', $user_type_mstr_id)
                            ->update([
                                    'status'=>0
                                    ]);
    }
    public function DeleteUserType($user_type_mstr_id)
    {
        return $builder = $this->db->table($this->table)
                            ->where('user_type_mstr_id', $user_type_mstr_id)
                            ->update([
                                    'status'=>0
                                    ]);
    } 
    public function checkIsExists($user_type_mstr_id,$under_user_type_mstr_id)
    {
        try{
            $builder = $this->db->table($this->table)
                        ->select('id')
                        ->where('user_type_mstr_id',$user_type_mstr_id)
                        ->where('under_user_type_mstr_id',$under_user_type_mstr_id)
                        ->get();
            return $builder->getResultArray()[0];
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }
    public function ajax_reporting_data($user_type_mstr_id)
    {
        try{
            $builder = $this->db->table($this->table)
                    ->select('user_type_mstr_id')
                    ->where('under_user_type_mstr_id',$user_type_mstr_id)
                    ->where('status',1)
                    ->get();
        return $builder->getResultArray();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    public function get_project_manager_by_user($user_id)
    {
        
        $designation_id="";
        $hierarchy_id="";
        $return=array();

        while($designation_id!=3) 
        {
            $report_to_sql="select report_to from tbl_emp_details where md5(id::text)='$user_id'";
            $result=$this->query($report_to_sql);
            //echo $this->getLastQuery();
            $arr_result=$result->getResultArray();
            //print_r($arr_result);
            $report_to=$arr_result[0]['report_to'];
           

            if($report_to!="")
            {
                 $hierarchy_id.=$report_to.',';
                $next="select user_type_mstr_id,report_to from tbl_emp_details where id=$report_to";
                $result2=$this->query($next);
                $arr_result2=$result2->getResultArray();
               // echo $this->getLastQuery();
                $user_id=$arr_result2[0]['report_to'];
                if($user_id!="")
                {
                    $hierarchy_id.=$user_id.',';
                }
                
                $designation_id=$arr_result2[0]['user_type_mstr_id'];
                if($designation_id==3)
                {
                    
                    $return['hierarchy_id']=$hierarchy_id;
                    return $hierarchy_id;
                }
            }
            else
            {

               
                $return['hierarchy_id']=$hierarchy_id;
                return $hierarchy_id;
            }
            if($user_id=="")
            {
              
                $return['hierarchy_id']=$hierarchy_id;
                return $hierarchy_id;
            }

        }

      
    }

    public function get_user_details($id)
    {
        $sql="select * from tbl_emp_details where md5(id::text)='".$id."'";
        $run=$this->query($sql);
        $result=$run->getResultArray();
        return $result[0]['emp_name'];
    }

    public function getUserList()
    {
        $sql="select * from tbl_emp_details where status=1";
        $run=$this->query($sql);
        $result=$run->getResultArray();
       // echo $this->getLastQuery();
        return $result;

    }
    public function getTCList()
    {
       try{
        $builder = $this->db->table($this->table)
                  ->select('*')
                  ->where('user_type_mstr_id',5)
                  ->where('status',1)
                  ->get();
        return $builder->getResultArray();
       }catch(Exception $e){
        echo $e->getMessage();
       }

    }
    public function getUserListExceptAdmins()
    {
        $sql="select * from tbl_emp_details where status=1 and user_type_mstr_id not in(1,2) order by emp_name asc";
        $run=$this->query($sql);
        $result=$run->getResultArray();
       // echo $this->getLastQuery();
        return $result;

    }
    public function getUserDtls($id)
    {
        $sql="select * from tbl_emp_details where status=1 and  id=$id";
        $run=$this->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();
        return $result;

    }

    public function user_logins(array $data)
    {
        $sql="select created_on::date as date, to_char( to_timestamp ( created_on::time::text, 'HH24:MI:SS' ) , 'HH12:MI:SS PM' ) as time from tbl_login_details where (emp_details_id)= '".$data['emp_dtl_id']."' and created_on::date between '".$data['date_from']."' and '".$data['date_upto']."'";
        $run=$this->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();
        return $result;
        
    }

     public function insert_notification(array $data)
    {

      $result= $this->db->table("tbl_notification")
                 ->insert($data);
        $insert_id=$this->db->insertID();
         //echo $this->getLastQuery();
        return $insert_id;

            
    }
    
    public function get_notification($session_user_id)
    {
        
          $result=$this->db->table("tbl_notification")
          ->select('*')
          ->where('status',0)
          ->where('receiver_id',$session_user_id)
          ->orderby('id desc')
          ->get()
          ->getResultArray();


        //  echo $this->getLastQuery();
          
          return $result;

    }
  

    public function cash_verf_pending_list(array $data)
    {

        $sql="

select tbl_notification.id,tbl_notification.created_on::date as date,verify_status,emp_name,paid,remarks,tbl_emp_details.id as emp_id from tbl_notification join 
(select id,tran_by_emp_details_id,verify_status,paid from 
dblink('host=".getenv('db.pgsql.hname')." user=".getenv('db.pgsql.uname')." password=".getenv('db.pgsql.pass')."  dbname=db_rmc_property'::text,
'select id,tran_by_emp_details_id,verify_status,sum(payable_amt) as paid from tbl_transaction 
group by verify_status,id,tran_by_emp_details_id'::text) trans(id bigint,tran_by_emp_details_id bigint,verify_status bigint,paid numeric)) 
as trans on trans.verify_status=tbl_notification.id 
join tbl_emp_details on tbl_emp_details.id=trans.tran_by_emp_details_id where tbl_notification.created_on::date='".$data['date_from']."'
and tbl_notification.status=1 and receiver_id=".$data['session_user_id']."
";
        $run=$this->query($sql);
        $array=$run->getResultArray();
        // echo $this->getLastQuery();
        return $array;
    }
    
    public function cash_vef_chq_list(array $data)
    {


      $id=$data['id'];
      $sql="select * from tbl_notification join (select tran_date,holding_no,owner_name,mobile_no,transaction_id,tran_no,prop_dtl_id,tran_type,cheque_no,bank_name,branch_name,tran_by_emp_details_id,verify_status,paid from 
dblink('host=".getenv('db.pgsql.hname')." user=".getenv('db.pgsql.uname')." password=".getenv('db.pgsql.pass')."  dbname=db_rmc_property'::text,
'select tran_date,holding_no,owner_name,mobile_no,transaction_id,tran_no,view_tc_transaction_details.prop_dtl_id,tran_type,cheque_no,bank_name,branch_name,tran_by_emp_details_id,verify_status,sum(payable_amt) as paid from view_tc_transaction_details join tbl_prop_dtl on tbl_prop_dtl.id=view_tc_transaction_details.prop_dtl_id 
join (select prop_dtl_id,max(owner_name) as owner_name,max(mobile_no) as mobile_no from tbl_prop_owner_detail group by prop_dtl_id) as owner on owner.prop_dtl_id=tbl_prop_dtl.id
group by tran_date,holding_no,owner_name,mobile_no,verify_status,view_tc_transaction_details.prop_dtl_id,tran_type,cheque_no,bank_name,branch_name,transaction_id,tran_no,tran_by_emp_details_id'::text) trans(tran_date date,holding_no text,owner_name text,mobile_no bigint,transaction_id bigint,tran_no text, prop_dtl_id integer, tran_type text, cheque_no text,bank_name text,branch_name text,tran_by_emp_details_id bigint,verify_status bigint,paid numeric)) as trans on trans.verify_status=tbl_notification.id where 
      md5(tbl_notification.id::text)='".$data['id']."' and status=1 and tran_type='Property'
      ";

      $run=$this->query($sql);
      $result=$run->getResultArray();

    //echo $this->getLastQuery();
      return $result;

    }


    public function cash_vef_chq_list_saf(array $data)
    {


      $id=$data['id'];
      $sql="select * from tbl_notification join (select tran_date,holding_no,owner_name,mobile_no,transaction_id,tran_no,prop_dtl_id,tran_type,cheque_no,bank_name,branch_name,tran_by_emp_details_id,verify_status,paid from 
dblink('host=".getenv('db.pgsql.hname')." user=".getenv('db.pgsql.uname')." password=".getenv('db.pgsql.pass')."  dbname=db_rmc_property'::text,
'select tran_date,holding_no,owner_name,mobile_no,transaction_id,tran_no,view_tc_transaction_details.prop_dtl_id,tran_type,cheque_no,bank_name,branch_name,tran_by_emp_details_id,verify_status,sum(payable_amt) as paid from view_tc_transaction_details join tbl_saf_dtl on tbl_saf_dtl.id=view_tc_transaction_details.prop_dtl_id 
join (select saf_dtl_id,max(owner_name) as owner_name,max(mobile_no) as mobile_no from tbl_saf_owner_detail group by saf_dtl_id) as owner on owner.saf_dtl_id=tbl_saf_dtl.id
group by tran_date,holding_no,owner_name,mobile_no,verify_status,view_tc_transaction_details.prop_dtl_id,tran_type,cheque_no,bank_name,branch_name,transaction_id,tran_no,tran_by_emp_details_id'::text) trans(tran_date date,holding_no text,owner_name text,mobile_no bigint,transaction_id bigint,tran_no text, prop_dtl_id integer, tran_type text, cheque_no text,bank_name text,branch_name text,tran_by_emp_details_id bigint,verify_status bigint,paid numeric)) as trans on trans.verify_status=tbl_notification.id where 
      md5(tbl_notification.id::text)='".$data['id']."' and status=1 and tran_type='Saf'
      ";

      $run=$this->query($sql);
      $result=$run->getResultArray();

   // echo $this->getLastQuery();
      return $result;

    }

    public function update_notification_status(array $data)
    {

        $sql="update tbl_notification set status=0 where md5(id::text)='".$data['notification_id']."'";
          $run=$this->query($sql);
    }

    public function gettotal_pending_amount($notification_id)
    {
        $sql="
select payable_amt from tbl_notification join 
(select verify_status,payable_amt from 
 dblink('host=".getenv('db.pgsql.hname')." user=".getenv('db.pgsql.uname')." password=".getenv('db.pgsql.pass')."  dbname=db_rmc_property'::text,
'select verify_status,sum(payable_amt) as payable_amt from tbl_transaction group by verify_status'
::text) trans(verify_status integer,payable_amt float)) as trans on trans.verify_status=
tbl_notification.id
where md5(tbl_notification.id::text)='".$notification_id."'";


       $run=$this->query($sql);
       $result=$run->getResultArray();
       $payable_amt=$run->getFirstRow("array");
       //print_r($payable_amt);
      //  echo $this->getLastQuery();
       return $payable_amt;
    }
}
?>
