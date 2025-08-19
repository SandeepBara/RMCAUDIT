<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_cash_verification_mstr extends Model
{
	protected $db;
    protected $table = 'tbl_cash_verification';
    // protected $allowedFields = ['id','depositor_emp_details_id','depositor_collected_amount','verified_amount','verification_date','emp_details_id','created_on','status','verify_status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function getCount($emp_id,$curr_date,$verified_by)
    {
        $sql="select count(id) as count from tbl_cash_verification where emp_details_id=".$emp_id." and verified_date='".$curr_date."' and emp_id=$verified_by";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
       // echo $this->getLastQuery();

        return $result['count'];


        
    }
    public function getCashVerifyIdbyVerifydateEmpId($emp_id,$curr_date,$verify_by)
    {
        $sql="select id from tbl_cash_verification where emp_details_id=".$emp_id." and verified_date='".$curr_date."' and emp_id=".$verify_by;
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        return $result['id'];


        
    }

    public function insertData(array $data)
    {


        $result= $this->db->table($this->table)
                 ->insert($data);       
        // echo $this->getLastQuery();
        $insert_id=$this->db->insertID();
        return $insert_id;

    }
    
    public function getData($id)
    {


        $result= $this->db->table($this->table)
                 ->select("*")
                 ->where("status",1)
                 ->where("md5(id::text)",$id)
                 ->get()
                 ->getFirstRow("array");

        return $result;

    }

    public function cashVerificationReport($date)
    {

        $sql="select tbl_cash_verification.id,tbl_cash_verification.verified_date,collection_amount,verify_amount,tbl_emp_details.emp_name,verify.emp_name as verify_by from tbl_cash_verification join tbl_emp_details on tbl_emp_details.id=tbl_cash_verification.emp_details_id join (select id,emp_name from tbl_emp_details ) as verify on verify.id=tbl_cash_verification.emp_id where verified_date='".$date."' and tbl_cash_verification.status=1 ";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        return $result;

    }

    public function updateverifyAmount($total_verified,$id)
    {
        $sql1="select coalesce(verify_amount,0) as verify_amount from tbl_cash_verification where id=".$id;
        $run1=$this->db->query($sql1);
        $result=$run1->getFirstRow("array");
        /*print_r($result);*/

        $verify_amount=$result['verify_amount'];

        $verf=$verify_amount+$total_verified;
        $sql="update tbl_cash_verification set verify_amount=$verf where id=".$id;
        $run=$this->db->query($sql);
        /*echo $this->getLastQuery();*/


    }
	public function tc_collection($data){
        try{        
            $builder = $this->db->table($this->table)
                        ->select('SUM(payable_amt) as collection_amount,tran_by_emp_details_id,tran_by_emp_details_name')          
                        ->where('tran_date', $data['date'])
                        ->where('md5(tran_by_emp_details_id::text)', $data['id'])
                        ->groupBy('tran_by_emp_details_id')
                        ->groupBy('tran_by_emp_details_name')
                        ->get();
                        //echo $this->db->getLastQuery();
                        
       return $builder->getFirstRow('array');
        //return $rw["cash_amount"];

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    

    public function insert_data(array $data,array $data2)
    {


            //print_r($data);
             $result= $this->db->table($this->table)
                 ->insert($data);
                //echo $this->db->getLastQuery();

            $insert_id=$this->db->insertID();
            $emp_id=$data2['depositor_id'];
            $stampdate=$data2['stampdate'];
            $curr_date=$data2['date'];


              $sql="insert into tbl_cash_verification_details(cash_verification_mstr_id,cash_mode_id,cheque_detail_id,amount,emp_details_id,verified_date,created_on,verified_status)

              select $insert_id,0,chq_dtl_id,payable_amt,$emp_id,'$curr_date','$stampdate',0 from view_tc_transaction_details where  md5(tran_by_emp_details_id::text)='".$data2['id']."' and tran_date = '".$data2['date_from']."'  and transaction_mode in('CHEQUE','DD')";

            $rw= $this->query($sql);
           // echo "hhhhhhhhhhh";
         //  echo $this->db->getLastQuery();

        return $insert_id;
    }

    
	
    public function get_total_coll_by_tc($cash_verf_mstr_id)
    {
        $sql="select collected_amount from tbl_cash_verification_mstr where md5(id::text)='".$cash_verf_mstr_id."'";

        $run=$this->query($sql);
        $result=$run->getResultArray();
        return $result[0]['collected_amount'];

    }
    
}
?>