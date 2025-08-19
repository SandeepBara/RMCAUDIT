<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class ModelTransactionDeactivate extends Model 
{
    protected $table = 'tbl_transaction';
   // protected $allowedFields = ['id', 'road_type_mstr_id','const_type_mstr_id', 'given_rate', 'date_of_effect','cal_rate','status'];
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
   
   public function transaction_details(array $data)
   {

   		/*$result=$this->db->table("view_tc_transaction_details")
   			->select('*')
   			->where('tran_no',$data['trans_no'])
        ->where('verify_status',NULL)
        ->where('status',1)
        ->where('status',2)
   			->get()
   			->getFirstRow("array");
      */


   		$sql="select * from view_tc_transaction_details where status in(1,2) and verify_status is NULL and tran_no='".$data['trans_no']."'";

      $run=$this->query($sql);
      $result=$run->getFirstRow("array");

   		//echo $this->getLastQuery();
   		return $result;


   }
   public function get_prop_details($prop_id,$table,$table2,$col)
   {

   		$sql="select $table.holding_no,ward_no,prop_address,owner_name,mobile_no from $table join view_ward_mstr on view_ward_mstr.id=$table.ward_mstr_id join (select $col,string_agg(owner_name,',') as owner_name,max(mobile_no) as mobile_no from $table2 group by $col) as owner on owner.$col=$table.id where $table.id=".$prop_id."";


   		
   		$result=$this->query($sql);
         $result=$result->getFirstRow("array");

   		
   		//echo $this->getLastQuery();
   		return $result;
   }
   public function cheque_details($trans_id)
   {

   		$result=$this->db->table("tbl_cheque_details")
   		->select('*')
   		->where('transaction_id',$trans_id)
   		->get()
   		->getFirstRow("array");

   		//echo $this->getLastQuery();
   		return $result;

   }
   public function getFyYearById($fy_id)
   {

   		$result=$this->db->table("view_fy_mstr")
   		->select('*')
   		->where('id',$fy_id)
   		->get()
   		->getFirstRow("array");

   		//echo $this->getLastQuery();
   		//print_r($result);
   		return $result['fy'];


   }

   
   public function insert_trans_deactivate(array $data)
   {

         $result= $this->db->table("tbl_transaction_deactivate_dtl")
                 ->insert($data);
                 //  echo $this->getLastQuery();
        $insert_id=$this->db->insertID();
       
        return $insert_id;
   }

   public function updateFileName(array $data)
   {
        
        $sql="update tbl_transaction_deactivate_dtl set file_path='".$data['newName']."' where id=".$data['insert_id']."";
        $this->query($sql);

        //echo $this->getLastQuery();
        $update_trans_status="update tbl_transaction set status=0 where id=".$data['transaction_id']."";
         $this->query($update_trans_status);


        $update_demand="select string_agg(prop_demand_id::text,',') as demand_id from tbl_collection where transaction_id=".$data['transaction_id']."";

        $get_demand_id=$this->query($update_demand);
       // echo $this->getLastQuery();

        $val_demand_id=$get_demand_id->getFirstRow("array");
        //print_r($val_demand_id);

        $update_demand="update tbl_prop_demand set status=0 where id in ".$val_demand_id['demand_id']."";

        $this->query($update_demand);

        $update_collection="update tbl_collection set status=0 where transaction_id=".$data['transaction_id']."";
        $this->query($update_collection);

        $update_fine="update tbl_transaction_fine_rebet_details set status=0 where transaction_id=".$data['transaction_id']."";

         $this->query($update_fine);

   }

   public function trans_deactive_report(array $data)
   {

      $sql="select distinct(tbl_transaction.tran_no),tran_date,tran_type,
            coalesce(tbl_prop_dtl.holding_no,tbl_saf_dtl.saf_no) as h_no,

            coalesce(owner_prop.owner_name,saf_owner.owner_name) as owner_name,
            coalesce(owner_prop.mobile_no,saf_owner.mobile_no),emp_name,deactive_date,payable_amt,from_fy_mstr_id,from_qtr,upto_fy_mstr_id,upto_qtr

            from tbl_transaction join tbl_transaction_deactivate_dtl on tbl_transaction_deactivate_dtl.transaction_id=tbl_transaction.id left
            join tbl_prop_dtl on tbl_prop_dtl.id=tbl_transaction.prop_dtl_id left
            join tbl_saf_dtl on tbl_saf_dtl.id=tbl_transaction.prop_dtl_id left
            join 
            (select prop_dtl_id,string_agg(owner_name,',') as owner_name,max(mobile_no) as mobile_no from tbl_prop_owner_detail group by prop_dtl_id) 
            as owner_prop on owner_prop.prop_dtl_id=tbl_prop_dtl.id left
            join 
            (select saf_dtl_id,string_agg(owner_name,',') as owner_name,max(mobile_no) as mobile_no from tbl_saf_owner_detail group by saf_dtl_id) as
            saf_owner on saf_owner.saf_dtl_id=tbl_saf_dtl.id 
            join (select id,emp_name from dblink(''host=".getenv('db.pgsql.hname')." user=".getenv('db.pgsql.uname')." password=".getenv('db.pgsql.pass')." dbname=db_system'::text,'select id,emp_name from tbl_emp_details'::text) as emp(id bigint,emp_name text)) as emp on emp.id=tbl_transaction_deactivate_dtl.deactivated_by where tbl_transaction_deactivate_dtl.created_on::date between '".$data['date_from']."' and '".$data['date_upto']."' 
            ";


            $run=$this->query($sql);
            $result=$run->getResultArray();
          //   echo $this->getLastQuery();
            return $result;


   }
}
