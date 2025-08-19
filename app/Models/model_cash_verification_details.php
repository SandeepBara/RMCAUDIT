<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_cash_verification_details extends Model
{
	protected $db;
    protected $table = 'tbl_cash_verification_details';
    protected $allowedFields = ['id','cash_verification_mstr_id','cash_mode_id','cheque_detail_id','amount','verified_status','verified_by_emp_id','emp_details_id','verified_date','created_on','status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }


    public function update_data($chq_dtl_id,array $data)
    {

              $count=0;
              //echo "model".$chq_dtl_id;
              if($chq_dtl_id==""){$chq_dtl_id=0;}
              $chq_id=rtrim($chq_dtl_id,',');
              if($chq_dtl_id!=0)
              {
                $sql="update tbl_cash_verification_details set verified_status=1 where cheque_detail_id in($chq_id)";
                $rw= $this->query($sql);

                $sql2="update tbl_transaction set cash_verify_status=1,cash_verified_by=,cash_verify_date= where id in(select transaction_id from tbl_cheque_details join tbl_cash_verification_details on tbl_cash_verification_details.cheque_detail_id=tbl_cheque_details.id where verified_status=1)";

                $this->query($sql2);
                
                       //    echo $this->getLastQuery();
              }
                $sql2="select * from tbl_cash_verification_details where verified_status=0";
                $exc=$this->query($sql2);
            // echo $this->getLastQuery();
                $result=$exc->getResultArray();
               // print_r($result);
                 $count=sizeof($result);
                  return $count;
            
            
           //echo $this->db->getLastQuery();
           // $rw=$this->query($sql);
    }

    public function insert_notification(array $data)
    {

        $result= $this->db->table("tbl_notification")
                 ->insert($data);
    echo $this->getLastQuery();
    }
    
	
    public function insert_cash_data(array $data)
    {

        $result= $this->db->table($this->table)
                 ->insert($data);
              //   echo $this->getLastQuery();
    }
    
    public function cash_vef_list(array $data)
    {

      $id=$data['id'];
      $sql="select sum(amount::float) as cash_amt from tbl_cash_verification_details
      where verified_status=0 and cheque_detail_id=0 and  md5(tbl_cash_verification_details.cash_verification_mstr_id::text)='".$data['cash_verf_mstr_id']."' and md5(emp_details_id::text)='".$id."' and tbl_cash_verification_details.created_on::date='". $data['date_from']."'";

      $run=$this->query($sql);
      $result=$run->getResultArray();
   //  echo $this->getLastQuery();
      return $result;

    }




    public function verify_pending_cash($cash_mstr_id,$trans_id,$id,$curr_date,$tc_id)
    {

      $cash_mstr_id=rtrim($cash_mstr_id,',');

      $sql="update tbl_cash_verification_mstr set verify_status=1 where md5(id::text) ='".$cash_mstr_id."'";
      $sql2="update tbl_cash_verification_details set verified_status=1,verified_by_emp_id= where md5(cash_verification_mstr_id::text) ='".$cash_mstr_id."'";

      $get_trans_date="select trans_date from tbl_cash_verification_mstr where md5(id::text)='".$cash_mstr_id."'";
      $run=$this->query($get_trans_date);
      $result=$run->getResultArray();
      //echo $this->getLastQuery();
       $trans_date=$result[0]['trans_date'];

       $sql3="update tbl_transaction set cash_verify_status=1,cash_verified_by=$id,cash_verify_date='$curr_date' where tran_date='$trans_date' and md5(tran_by_emp_details_id::text)='".$tc_id."'";

       $this->query($sql);
       $this->query($sql2);
       $this->query($sql3);
       echo $this->getLastQuery();
       
       
    }

    public function total_cash_collected($emp_id,$cash_verf_mstr_id,$from_date)
    {
      $sql="
        select COALESCE(collected_amount::float,0)-COALESCE(chq,0) as collected_amount from tbl_cash_verification_mstr left join 
        (select cash_verification_mstr_id,COALESCE(sum(amount::float),0) as chq from tbl_cash_verification_details 
        where cash_mode_id=0 and md5(cash_verification_mstr_id::text)='".$cash_verf_mstr_id."'  group by cash_verification_mstr_id) as dtl
        on dtl.cash_verification_mstr_id=tbl_cash_verification_mstr.id where 
        md5(tbl_cash_verification_mstr.id::text)='".$cash_verf_mstr_id."' and 
        md5(tbl_cash_verification_mstr.tc_id::text) ='".$emp_id."' and tbl_cash_verification_mstr.created_on::date='".$from_date."'";

        $run=$this->query($sql);
        $result=$run->getResultArray();
        //print_r($result);
        // echo $this->getLastQuery();

        return $result[0]['collected_amount'];
      
    }

    public function update_cash_transaction($cash_mstr_id,$tc_id)
    {

         $get_trans_date="select trans_date from tbl_cash_verification_mstr where (id)='".$cash_mstr_id."'";
      $run=$this->query($get_trans_date);
      $result=$run->getResultArray();
      //echo $this->getLastQuery();
       $trans_date=$result[0]['trans_date'];

       $sql3="update tbl_transaction set cash_verify_status=1,cash_verified_by=$id,cash_verify_date='$curr_date' where tran_date='$trans_date' and md5(tran_by_emp_details_id::text)='".$tc_id."'";

        $this->query($sql3);
        // echo $this->getLastQuery();



    }

    public function update_cash_verification(array $data)
    {

        $sql="update tbl_transaction set verify_status=1,verified_by=".$data['user_id'].",verify_date='".date('Y-m-d')."' where tran_by_emp_details_id='".$data['tran_by_emp_details_id']."' and tran_date='".$data['trans_date']."' and tran_mode_mstr_id=1 ";

        $run=$this->query($sql);

      //  echo $this->getLastQuery();
    }

    public function update_cheque_verification(array $data)
    {

      //  $sql="update tbl_transaction set verify_status=2,verified_by=".$data['user_id'].",verify_date='".date('Y-m-d')."' where tran_by_emp_details_id='".$data['tran_by_emp_details_id']."' and tran_date='".$data['trans_date']."' and tran_mode_mstr_id in(2,3) ";


        $sql2="update tbl_transaction set verify_status=1,verified_by=".$data['user_id'].",verify_date='".date('Y-m-d')."' where id in(".$data['transaction_id'].")";
        
       // $run=$this->query($sql);
        $run2=$this->query($sql2);
       //echo $this->getLastQuery();

    }

    public function check_verification_pending(array $data)
    {
        $sql="select count(*) as cnt from tbl_transaction where tran_date='".$data['trans_date']."' and tran_by_emp_details_id=".$data['tran_by_emp_details_id']." and verify_status=2 ";

        $run=$this->query($sql);
        $result=$run->getResultArray();
        
       // echo $this->getLastQuery();
       // $count=sizeof($get_count);
        return $result;

    }

    public function update_cheque_notverified_status(array $data)
    {

        $sql="update tbl_transaction set verify_status=".$data['notification_id']." where verify_status is NULL and tran_date='".$data['trans_date']."' and tran_by_emp_details_id='".$data['tran_by_emp_details_id']."'";

        $run=$this->query($sql);
        //echo $this->getLastQuery();
      

    }

    public function verify_pending_cheques(array $data)
    {
        $sql="update tbl_transaction set verify_status=1,verified_by=".$data['curr_user_id'].",verify_date='".date('Y-m-d')."' where md5(verify_status::text)='".$data['notification_id']."'";
         $run=$this->query($sql);
         //echo $this->getLastQuery();
    }
    
    
}
?>