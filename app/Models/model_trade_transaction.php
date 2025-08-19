<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_trade_transaction extends Model
{
	protected $db;
    protected $table = 'tbl_transaction';
    protected $allowedFields = ['tran_no', 'penalty_amt', 'from_fy_mstr_id', 'from_qtr', 'remarks', 'tran_verify_by_emp_details_id', 'tran_verify_date_time', 'prop_dtl_id', 'tran_by_emp_details_id', 'upto_fy_mstr_id', 'upto_qtr', 'created_on', 'discount_amt', 'payable_amt', 'tran_mode_mstr_id', 'tran_date','tran_type','ward_mstr_id','verify_date','verified_by','verify_status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function getAllTransaction($data)
    {
        $sql="select tbl_transaction.*,emp_name,ward_no,application_no,owner_name,firm_name from tbl_transaction left join tbl_apply_licence on tbl_apply_licence.id=tbl_transaction.related_id left join (select apply_licence_id,string_agg(owner_name,',') as owner_name from tbl_firm_owner_name group by apply_licence_id) as  tbl_firm_owner_name on tbl_firm_owner_name.apply_licence_id=tbl_apply_licence.id 
            left join view_ward_mstr on view_ward_mstr.id=tbl_transaction.ward_mstr_id
            left join view_emp_details on view_emp_details.id=tbl_transaction.emp_details_id
            where tbl_transaction.status in(1,2) and transaction_date between '".$data['from_date']."' and '".$data['to_date']."'";
        $run=$this->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();

        return $result;

    }
    
    public function getAllTransactionsWardWise($data)
    {
        $sql="select tbl_transaction.*,emp_name,ward_no,application_no,owner_name,firm_name from tbl_transaction left join tbl_apply_licence on tbl_apply_licence.id=tbl_transaction.related_id left join (select apply_licence_id,string_agg(owner_name,',') as owner_name from tbl_firm_owner_name group by apply_licence_id) as  tbl_firm_owner_name on tbl_firm_owner_name.apply_licence_id=tbl_apply_licence.id 
            left join view_ward_mstr on view_ward_mstr.id=tbl_transaction.ward_mstr_id
            left join view_emp_details on view_emp_details.id=tbl_transaction.emp_details_id
            where tbl_transaction.status in(1,2) and transaction_date between '".$data['from_date']."' and '".$data['to_date']."' and tbl_transaction.ward_mstr_id=".$data['ward_id'];
        $run=$this->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();

        return $result;

    }

    public function getAllTransactionsPayModeWise($data)
    {
        $sql="select tbl_transaction.*,emp_name,ward_no,application_no,owner_name,firm_name from tbl_transaction left join tbl_apply_licence on tbl_apply_licence.id=tbl_transaction.related_id left join (select apply_licence_id,string_agg(owner_name,',') as owner_name from tbl_firm_owner_name group by apply_licence_id) as  tbl_firm_owner_name on tbl_firm_owner_name.apply_licence_id=tbl_apply_licence.id 
            left join view_ward_mstr on view_ward_mstr.id=tbl_transaction.ward_mstr_id
            left join view_emp_details on view_emp_details.id=tbl_transaction.emp_details_id
            where tbl_transaction.status in(1,2) and transaction_date between '".$data['from_date']."' and '".$data['to_date']."' and payment_mode='".$data['payment_mode']."'";

        $run=$this->query($sql);
        $result=$run->getResultArray();
        // echo $this->getLastQuery();

        return $result;

    }

    //get_total_paid_amount_trade_transaction
    public function get_total_paid_amount_trade_transaction($data)
    {
         $sql="select tbl_transaction.*,view_emp_details.emp_name,view_ward_mstr.ward_no,tbl_apply_licence.application_no,tbl_apply_licence.firm_name 
        from tbl_transaction 
        left join tbl_apply_licence on tbl_apply_licence.id=tbl_transaction.related_id 
        left join view_ward_mstr on view_ward_mstr.id=tbl_transaction.ward_mstr_id
        left join view_emp_details on view_emp_details.id=tbl_transaction.emp_details_id
        where tbl_transaction.status in(1,2) and transaction_date between '".$data['from_date']."' and '".$data['to_date']."' or tbl_transaction.emp_details_id=".$data['tc_id']." or tbl_transaction.transaction_type ='".$data['app_type']."' or tbl_transaction.payment_mode='".$data['pay_mode']."'";
        $run=$this->query($sql);
        $result=$run->getResultArray();
        $this->getLastQuery();
        return $result;

    }

    //get collection 
    public function amount_collection($data,$payment_mode,$collected_by)
    {   if($collected_by=="online")
        {
            $sql="select sum(paid_amount)
            from tbl_transaction 
             where status = 1 
            and transaction_date between '".$data['fromDate']."' and '".$data['todate']."' 
            and payment_mode = '".$payment_mode."' ";
         }
         else{
            $sql="select sum(paid_amount)
            from tbl_transaction 
             where status = 1 
            and transaction_date between '".$data['fromDate']."' and '".$data['todate']."' 
            and payment_mode = '".$payment_mode."' 
            and collected_by = '".$collected_by."' ";
         }
         
        $run=$this->query($sql);
        $result=$run->getResultArray()[0];
        $this->getLastQuery();
        if($result['sum']=="")
        {
          $result['sum'] = 0;
        }
        else{
        $result;
        }
        return $result;
    }

    public function getTotalAmount($data)
    {
        $sql="select sum(paid_amount) as paid_amount from tbl_transaction where status in(1,2) and transaction_date between '".$data['from_date']."' and '".$data['to_date']."'";
        $run=$this->query($sql);
        $result=$run->getFirstRow("array");
        //echo $this->getLastQuery();

        return $result['paid_amount'];

    }
	public function getTotalAmountWardWise($data)
    {
        $sql="select sum(paid_amount) as paid_amount from tbl_transaction where status in(1,2) and transaction_date between '".$data['from_date']."' and '".$data['to_date']."' and ward_mstr_id=".$data['ward_id'];
        $run=$this->query($sql);
        $result=$run->getFirstRow("array");
        //echo $this->getLastQuery();

        return $result['paid_amount'];

    }
    public function getTotalAmountPayModeWise($data)
    {
        $sql="select sum(paid_amount) as paid_amount from tbl_transaction where status in(1,2) and transaction_date between '".$data['from_date']."' and '".$data['to_date']."' and payment_mode='".$data['payment_mode']."'";
        $run=$this->query($sql);
        $result=$run->getFirstRow("array");
        //echo $this->getLastQuery();

        return $result['paid_amount'];

    }
    public function getDeactivatedTransactionDetails($transaction_id){
        try{
            $builder = $this->db->table($this->table)
                      ->select('*')
                      ->where('id',$transaction_id)
                      ->where('status',0)
                      ->get();
                      //echo $this->db->getLastQuery();
            return $builder->getResultArray()[0];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
	
	
	
	public function getAllTransactionsTCWise($data)
    {
        $sql="select tbl_transaction.*,view_emp_details.emp_name,view_ward_mstr.ward_no,tbl_apply_licence.application_no,tbl_apply_licence.firm_name 
		from tbl_transaction 
		left join tbl_apply_licence on tbl_apply_licence.id=tbl_transaction.related_id 
        left join view_ward_mstr on view_ward_mstr.id=tbl_transaction.ward_mstr_id
		left join view_emp_details on view_emp_details.id=tbl_transaction.emp_details_id
		where tbl_transaction.status in(1,2) and transaction_date between '".$data['from_date']."' and '".$data['to_date']."' and tbl_transaction.emp_details_id=".$data['tc_id']." or tbl_transaction.transaction_type ='".$data['app_type']."' or tbl_transaction.payment_mode='".$data['pay_mode']."'";
        $run=$this->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();

        return $result;

    }
	
	
	public function getAllTransactionsTCpayappWise($data)
    {
        $sql="select tbl_transaction.*,view_emp_details.emp_name,view_ward_mstr.ward_no,tbl_apply_licence.application_no,tbl_apply_licence.firm_name 
		from tbl_transaction 
		left join tbl_apply_licence on tbl_apply_licence.id=tbl_transaction.related_id 
        left join view_ward_mstr on view_ward_mstr.id=tbl_transaction.ward_mstr_id
		left join view_emp_details on view_emp_details.id=tbl_transaction.emp_details_id
		where tbl_transaction.status in(1,2) and transaction_date between '".$data['from_date']."' and '".$data['to_date']."' and tbl_transaction.emp_details_id=".$data['tc_id']." and tbl_transaction.transaction_type ='".$data['app_type']."' and tbl_transaction.payment_mode='".$data['pay_mode']."'";
        $run=$this->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();
        return $result;
    }
	
	
	public function getAllTransactionsTCappWise($data)
    {
        $sql="select tbl_transaction.*,view_emp_details.emp_name,view_ward_mstr.ward_no,tbl_apply_licence.application_no,tbl_apply_licence.firm_name 
		from tbl_transaction 
		left join tbl_apply_licence on tbl_apply_licence.id=tbl_transaction.related_id 
        left join view_ward_mstr on view_ward_mstr.id=tbl_transaction.ward_mstr_id
		left join view_emp_details on view_emp_details.id=tbl_transaction.emp_details_id
		where tbl_transaction.status in(1,2) and transaction_date between '".$data['from_date']."' and '".$data['to_date']."' and tbl_transaction.emp_details_id=".$data['tc_id']." and tbl_transaction.transaction_type ='".$data['app_type']."'";
        $run=$this->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();
        return $result;
    }
	
	
	public function getAllTransactionsTCpayWise($data)
    {
        $sql="select tbl_transaction.*,view_emp_details.emp_name,view_ward_mstr.ward_no,tbl_apply_licence.application_no,tbl_apply_licence.firm_name 
		from tbl_transaction 
		left join tbl_apply_licence on tbl_apply_licence.id=tbl_transaction.related_id 
        left join view_ward_mstr on view_ward_mstr.id=tbl_transaction.ward_mstr_id
		left join view_emp_details on view_emp_details.id=tbl_transaction.emp_details_id
		where tbl_transaction.status in(1,2) and transaction_date between '".$data['from_date']."' and '".$data['to_date']."' and tbl_transaction.emp_details_id=".$data['tc_id']." and tbl_transaction.payment_mode='".$data['pay_mode']."'";
        $run=$this->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();
        return $result;
    }
	
	
	public function getAllTransactionspayappWise($data)
    {
        $sql="select tbl_transaction.*,view_emp_details.emp_name,view_ward_mstr.ward_no,tbl_apply_licence.application_no,tbl_apply_licence.firm_name 
		from tbl_transaction 
		left join tbl_apply_licence on tbl_apply_licence.id=tbl_transaction.related_id 
        left join view_ward_mstr on view_ward_mstr.id=tbl_transaction.ward_mstr_id
		left join view_emp_details on view_emp_details.id=tbl_transaction.emp_details_id
		where tbl_transaction.status in(1,2) and transaction_date between '".$data['from_date']."' and '".$data['to_date']."' and tbl_transaction.transaction_type ='".$data['app_type']."' and tbl_transaction.payment_mode='".$data['pay_mode']."'";
        $run=$this->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();
        return $result;
    }
	
	public function getAllTransactionforTC($data)
    {
        $sql="select tbl_transaction.*,view_emp_details.emp_name,view_ward_mstr.ward_no,tbl_apply_licence.application_no,tbl_apply_licence.firm_name
		from tbl_transaction 
		left join tbl_apply_licence on tbl_apply_licence.id=tbl_transaction.related_id 
		left join view_ward_mstr on view_ward_mstr.id=tbl_transaction.ward_mstr_id
        left join view_emp_details on view_emp_details.id=tbl_transaction.emp_details_id
        where tbl_transaction.status in(1,2) and transaction_date between '".$data['from_date']."' and '".$data['to_date']."'";
        $run=$this->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();

        return $result;

    }
    
}
?>