<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class CashVerificationPropertyModel extends Model
{

    protected $table = 'tbl_tc_visiting';

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}

    public function getData()
    {
        $result=$this->db->table($this->table)
                        ->select('*')
                        ->where('status',1)
                        ->get()
                        ->getResultArray();

        return $result;
    }
    public function getVerifiedAmountProperty($user_id)
    {
        $sql="select coalesce(sum(payable_amt),0) as payable_amount,view_emp_details.emp_name,verify.emp_name as verified_by from tbl_transaction left join view_emp_details on view_emp_details.id=tbl_transaction.tran_by_emp_details_id 
            left join (select id,emp_name from view_emp_details ) as verify on verify.id=tbl_transaction.verified_by

            where verify_status=1 and status in(1,2) and tran_by_emp_details_id=".$user_id." group by view_emp_details.emp_name,verify.emp_name";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
         // echo $this->getLastQuery();

        return $result;

    }

    public function getCollectedAmountProperty($user_id)
    {
        $sql="select coalesce(sum(payable_amt),0) as payable_amount from tbl_transaction where  status in(1,2) and tran_by_emp_details_id=".$user_id;
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        //  echo $this->getLastQuery();

        return $result['payable_amount'];
        
    }
    
    
    public function getAllVerifiedProperty($user_id)
    {
        $sql="select * from tbl_transaction where (verify_status=1 or (verify_status is NULL and notification_id is not NULL)) and status in(1,2) and tran_by_emp_details_id=".$user_id;
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        // echo $this->getLastQuery();

        return $result;
        
    }


    
}