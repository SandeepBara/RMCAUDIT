<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_tc_activity_water extends Model 
{

  
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
   
   public function application_applied(array $data)
   {
        $sql="select created_on::date as date,to_char( to_timestamp ( created_on::time::text, 'HH24:MI:SS' ) , 'HH12:MI:SS PM' )  as time from tbl_apply_water_connection where (user_id)=".$data['emp_dtl_id']." and created_on::date between '".$data['date_from']."' and '".$data['date_upto']."'";
        $run=$this->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();
        return $result;

   }
   public function application_payment(array $data)
   {
    
        $sql="select created_on::date as date,to_char( to_timestamp ( created_on::time::text, 'HH24:MI:SS' ) , 'HH12:MI:SS PM' ) as time from tbl_transaction where (emp_details_id)=".$data['emp_dtl_id']." and transaction_date between '".$data['date_from']."' and '".$data['date_upto']."' and upper(transaction_type) in('NEW CONNECTION','SITE INSPECTION')";
        $run=$this->query($sql);
        $result=$run->getResultArray();
        //  echo $this->getLastQuery();

        return $result;

   }
   public function consumer_payment(array $data)
   {
        $sql="select created_on::date as date,to_char( to_timestamp ( created_on::time::text, 'HH24:MI:SS' ) , 'HH12:MI:SS PM' ) as time from tbl_transaction where (emp_details_id)=".$data['emp_dtl_id']." and transaction_date between '".$data['date_from']."' and '".$data['date_upto']."' and upper(transaction_type) ='DEMAND COLLECTION'";
        $run=$this->query($sql);
        $result=$run->getResultArray();
        // echo $this->getLastQuery();
        return $result;

   }
    


}
