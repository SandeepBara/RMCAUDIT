<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_view_property_transaction extends Model 
{
    protected $db;
    protected $table = 'tbl_transaction';
    
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }


    public function propertyTransactionDetails($data)
    {
        $sql="select tran_date,payable_amt as payable_amount,tran_type,tbl_tran_mode_mstr.transaction_mode as payment_mode from tbl_transaction 
              join tbl_tran_mode_mstr on tbl_tran_mode_mstr.id=tbl_transaction.tran_mode_mstr_id
            where tran_date between '".$data['from_date']."' and '".$data['to_date']."' and tbl_transaction.status in(1,2)" ;
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();

        return $result;

    }
    public function propertyTransactionDetailsbyEmpId($data)
    {
        $sql="select tran_date,payable_amt as payable_amount,tran_type,tbl_tran_mode_mstr.transaction_mode as payment_mode from tbl_transaction 
              join tbl_tran_mode_mstr on tbl_tran_mode_mstr.id=tbl_transaction.tran_mode_mstr_id
            where tran_date between '".$data['from_date']."' and '".$data['to_date']."' and tbl_transaction.status in(1,2) and tran_by_emp_details_id=".$data['tran_by_emp_details_id'] ;
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();

        return $result;
        
    }

    public function propertyTransactionDetailsbyEmpIdTransDate($cash_verify_id)
    {
        $sql="select tbl_transaction.*, tbl_cheque_details.bank_name, tbl_cheque_details.cheque_no, cheque_date, branch_name from tbl_transaction 
            left join tbl_cheque_details on tbl_cheque_details.transaction_id=tbl_transaction.id join tbl_tran_mode_mstr on tbl_tran_mode_mstr.id=tbl_transaction.tran_mode_mstr_id
            where cash_verify_id=".$cash_verify_id ;
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        // echo $this->getLastQuery();

        return $result;
        
    }

    public function propertyNotVerified($cash_verify_id)
    {
        $sql="select * from tbl_transaction join 

            (
             
                SELECT tbl_notification.id
               FROM dblink('host=".getenv('db.pgsql.hname')." user=".getenv('db.pgsql.uname')." password=".getenv('db.pgsql.pass')." dbname=db_system'::text, 
                'SELECT tbl_notification.id FROM  tbl_notification 
            where tbl_notification.status=1'::text) tbl_notification
                (id integer)
                ) as notif on notif.id=tbl_transaction.notification_id
       where cash_verify_id= ".$cash_verify_id;
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        // echo $this->getLastQuery();

        return $result;
        
    }

    public function propertyTotalAmount($data)
    {
        $sql="select coalesce(sum(payable_amt),0) as payable_amount from tbl_transaction where transaction_date between '".$data['from_date']."' and '".$data['to_date']."' and status in(1,2)" ;
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        return $result['payable_amount'];

    }

    public function propertyTotalAmountbyEmpId($data)
    {
        $sql="select coalesce(sum(payable_amt),0) as payable_amount from tbl_transaction where tran_date between '".$data['from_date']."' and '".$data['to_date']."' and status in(1,2) and tran_by_emp_details_id=".$data['tran_by_emp_details_id'] ;
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        //echo $this->getLastQuery();

        return $result['payable_amount'];

    }



}