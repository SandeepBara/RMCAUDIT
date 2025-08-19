<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_tc_activity extends Model 
{


    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
   
   public function form_distribute(array $data)
   {
        $sql="select created_on::date as date,to_char( to_timestamp ( created_on::time::text, 'HH24:MI:SS' ) , 'HH12:MI:SS PM' )  as time from tbl_saf_distributed_dtl where (survey_by_emp_details_id)=".$data['emp_dtl_id']." and created_on::date between '".$data['date_from']."' and '".$data['date_upto']."'";
        $run=$this->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();
        return $result;

   }
   public function saf_payment(array $data)
   {
        $sql="select created_on::date as date,to_char( to_timestamp ( created_on::time::text, 'HH24:MI:SS' ) , 'HH12:MI:SS PM' ) as time from tbl_transaction where (tran_by_emp_details_id)=".$data['emp_dtl_id']." and tran_date between '".$data['date_from']."' and '".$data['date_upto']."' and upper(tran_type)='SAF'";
        $run=$this->query($sql);
        $result=$run->getResultArray();
       // echo $this->getLastQuery();
        return $result;

   }
   public function property_payment(array $data)
   {
        $sql="select created_on::date as date,to_char( to_timestamp ( created_on::time::text, 'HH24:MI:SS' ) , 'HH12:MI:SS PM' ) as time from tbl_transaction where (tran_by_emp_details_id)=".$data['emp_dtl_id']." and tran_date between '".$data['date_from']."' and '".$data['date_upto']."' and upper(tran_type)='PROPERTY'";
        $run=$this->query($sql);
        $result=$run->getResultArray();
        // echo $this->getLastQuery();

        return $result;

   }
    public function field_verification(array $data)
   {
        $sql="select created_on::date as date,to_char( to_timestamp ( created_on::time::text, 'HH24:MI:SS' ) , 'HH12:MI:SS PM' ) as time from tbl_field_verification_dtl where (verified_by_emp_details_id)=".$data['emp_dtl_id']." and created_on::date between '".$data['date_from']."' and '".$data['date_upto']."'" ;
        $run=$this->query($sql);
        $result=$run->getResultArray();
         //echo $this->getLastQuery();
        return $result;

   }
   public function saf_done(array $data)
   {
        $sql="select created_on::date as date,to_char( to_timestamp ( created_on::time::text, 'HH24:MI:SS' ) , 'HH12:MI:SS PM' ) as time from tbl_saf_dtl where (emp_details_id)=".$data['emp_dtl_id']." and created_on::date between '".$data['date_from']."' and '".$data['date_upto']."'";
        $run=$this->query($sql);
        $result=$run->getResultArray();
        return $result;

   }
   public function geotagged(array $data)
   {
         $sql="select created_on::date as date,max(to_char( to_timestamp ( created_on::time::text, 'HH24:MI:SS' ) , 'HH12:MI:SS PM' )) as time from tbl_saf_geotag_upload_dtl where (created_by_emp_details_id)=".$data['emp_dtl_id']." and created_on::date between '".$data['date_from']."' and '".$data['date_upto']."' group by geotag_dtl_id";
        $run=$this->query($sql);
        /*
         $sql="select max(to_char(to_timestamp(created_on::time,'HH24:MI:SS'),'HH12:MI:SS PM') as time from tbl_saf_geotag_upload_dtl where (created_by_emp_details_id)=".$data['emp_dtl_id']." and created_on::date between '".$data['date_from']."' and '".$data['date_upto']."' group by geotag_dtl_id";*/
         
        $result=$run->getResultArray();
       // echo $this->getLastQuery();
        return $result;

   }


}
