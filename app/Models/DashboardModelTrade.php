<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class DashboardModelTrade extends Model
{

	public function __construct(ConnectionInterface $db) {
	    $this->db = $db;
	}

    public function getTotalApply($fyearFirst, $fyearSecond){
        $fr = $fyearFirst."-04-01";
        $fs = $fyearSecond."-03-31";
        $sql = "select 
                    count(id) as total 
                    from tbl_apply_licence 
                    where apply_date between '$fr' and '$fs'
                    and application_type_id =1";
        $run = $this->db->query($sql);
        return $result = $run->getResultArray('array');
    }
      public function getTotalsurrender($fyearFirst, $fyearSecond){
        $fr = $fyearFirst."-04-01";
        $fs = $fyearSecond."-03-31";
        $sql = "select 
                    count(id) as total 
                    from tbl_apply_licence 
                    where apply_date between '$fr' and '$fs'
                    and application_type_id =4";
        $run = $this->db->query($sql);
        return $result = $run->getResultArray('array');
    }

  public function getTotalAmendment($fyearFirst, $fyearSecond){
        $fr = $fyearFirst."-04-01";
        $fs = $fyearSecond."-03-31";
        $sql = "select 
                    count(id) as total 
                    from tbl_apply_licence 
                    where apply_date between '$fr' and '$fs'
                    and application_type_id =3";
        $run = $this->db->query($sql);
        return $result = $run->getResultArray('array');
    }

  public function getTotalRenewal($fyearFirst, $fyearSecond){
        $fr = $fyearFirst."-04-01";
        $fs = $fyearSecond."-03-31";
        $sql = "select 
                    count(id) as total 
                    from tbl_apply_licence 
                    where apply_date between '$fr' and '$fs'
                    and application_type_id =2";
        $run = $this->db->query($sql);
        return $result = $run->getResultArray('array');
    }
      public function getTotalCollection($fyearFirst, $fyearSecond){
        $fr = $fyearFirst."-04-01";
        $fs = $fyearSecond."-03-31";
        $sql = "SELECT 
                    COUNT(DISTINCT(id)) AS no_of_tran,
                    SUM(paid_amount) AS tran_amt, 
                    CONCAT(TO_CHAR(transaction_date::DATE, 'yyyy-mm'), '-01') AS first_month,
                    (date_trunc('MONTH', CONCAT(TO_CHAR(transaction_date::DATE, 'yyyy-mm'), '-01')::DATE) + INTERVAL '1 MONTH - 1 day')::date AS last_month
                    FROM tbl_transaction 
                    WHERE transaction_date BETWEEN '$fr' AND '$fs' AND status IN (1,2)
                    GROUP BY TO_CHAR(transaction_date::DATE, 'yyyy-mm')
                    ORDER BY TO_CHAR(transaction_date::DATE, 'yyyy-mm')
                    ";
        $run = $this->db->query($sql);
        return $result = $run->getResultArray('array');
    }

     public function getTillNowCollection($fyearFirst, $fyearSecond){
        $fr = $fyearFirst."-04-01";
        $fs = $fyearSecond."-03-31";
        $sql = "select 
                    sum(paid_amount)
                    from tbl_transaction where 
                    transaction_date between '$fr' and  '$fs' and status in(1,2)
                    ";
                    
        $run = $this->db->query($sql);
        return $result = $run->getResultArray('array');
    }

     public function getTodaysCollection(){
        $today = date('Y-m-d');
        $sql = "select 
                    sum(paid_amount) as todays_collection
                    from tbl_transaction where 
                    transaction_date='$today' and status in(1,2)
                    ";
                    
        $run = $this->db->query($sql);
        return $result = $run->getResultArray('array');
    }

}