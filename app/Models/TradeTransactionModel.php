<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class TradeTransactionModel extends Model
{
    protected $db;
    protected $table = 'tbl_transaction';
    protected $allowedFields = ['id', 'transaction_no', 'apply_licence_id', 'ward_mstr_id', 'transaction_type', 'transaction_date', 'related_id', 'payment_mode', 'paid_amount', 'penalty', 'verify_status', 'verified_by', 'verified_on', 'emp_details_id', 'created_on', 'status', 'notification_id'];

    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }

    // Using In Cash Verification
    public function TradePaymentList($emp_details_id, $transaction_date)
    {

        $sql = "select tbl_transaction.id as transaction_id, transaction_no, payment_mode, transaction_type, paid_amount, 
        tbl_transaction.related_id as apply_licence_id, ward_no, applicant_name, application_no, 
        firm_name, verify_status, emp_name as verified_by, verified_on::date as   verified_on
        from tbl_transaction
        join view_apply_licence_owner on view_apply_licence_owner.id=tbl_transaction.related_id
        left join view_emp_details on view_emp_details.id=tbl_transaction.verified_by
        where tbl_transaction.transaction_date='$transaction_date' and tbl_transaction.emp_details_id=$emp_details_id  
        and tbl_transaction.status in (1, 2)";
        //print_r($sql);
        $sql = $this->db->query($sql);
        //echo $this->db->getLastQuery();
        return $result = $sql->getResultArray();
    }


    // Using In Cash Verification
    function CashVerify($trade_trxn_ids, $verified_by)
    {
        $builder = $this->db->table($this->table)
            ->wherein('id', $trade_trxn_ids)
            ->update([
                'verify_status' => 1,
                'verified_by' => $verified_by,
                'verified_on' => 'NOW()',
            ]);
        //echo $this->db->getLastQuery().'trade';
        return $builder;
    }

    public function getTransactionById($id)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('*')
                ->where('md5(id::text)', $id)
                ->whereIn('status', [1, 2])
                ->get();
            return $builder->getResultArray()[0];
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getEmpDetailsId($data)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('emp_details_id')
                ->where('transaction_date >=', $data['from_date'])
                ->where('transaction_date <=', $data['to_date'])
                ->whereIn('status', [1, 2])
                ->groupby('emp_details_id')
                ->get();
            // echo $this->db->getLastQuery();
            return $builder->getResultArray();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function insertdataexcel($input)
    {
        $builder = $this->db->table($this->table)
            ->insert([
                'transaction_no' => $input["transaction_no"],
                'ward_mstr_id' => $input["ward_mstr_id"],
                'transaction_type' => $input["application_type"],
                'transaction_date' => $input["transaction_date"],
                'related_id' => $input["apply_licence_id"],
                'payment_mode' => $input["payment_mode"],
                'paid_amount' => $input["paid_amount"],
                'penalty' => ($input["penalty"] <> null) ? $input["penalty"] : 0,
                'status' => $input["status"],
                'emp_details_id' => $input["emp_details_id"],
                'created_on' => $input["created_on"]
            ]);
        echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }

    public function insertPayment($input)
    {
        $result = $this->db->table($this->table)->insert($input);

        //echo $this->getLastQuery();		
        $result = $this->db->insertID();

        $transaction_no = "TRANML" . date('d') . $result . date('Y') . date('m') . date('s');

        $this->db->table($this->table)
            ->where('id', $result)
            ->set(['transaction_no' => $transaction_no])
            ->update();
        //echo $this->db->getLastQuery();


        //print_r($result);
        //die();


        return $result;
    }

    public function transaction_details($transaction_id)
    {

        return $result = $this->db->table($this->table)
            ->select("*")
            ->where('md5(id::text)', $transaction_id)
            ->whereIn('status', [1, 2])
            ->get()
            ->getFirstRow("array");
    }

    public function alltransaction_details($transaction_id)
    {

        $result = $this->db->table($this->table)
            ->select("*")
            ->where('md5(related_id::text)', $transaction_id)
            ->whereIn('status', [1, 2])
            ->get();
        //echo $this->db->getLastQuery();                      
        return $result->getResultArray();
    }

    // get transaction details by id 
    public function get_trans_details($transaction_id)
    {

        $result = $this->db->table($this->table)
            ->select("*")
            ->where('md5(related_id::text)', $transaction_id)
            //->whereIn('status', [1,2])
            ->orderBy('id', 'desc')
            ->get();
        //echo $this->db->getLastQuery();                      
        return $result->getResultArray()[0] ?? [];
    }

    public function getTransactionDetails($id)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('*')
                ->where('id', $id)
                ->where('status!=', 0)
                ->get();
            return $builder->getResultArray()[0];
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function updateTradeTransactionClearStatus($transaction_id)
    {
        try {
            $builder = $this->db->table($this->table)
                ->where('id', $transaction_id)
                ->update([
                    'status' => 1
                ]);
            // echo $this->db->getLastQuery();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function updateTradeTransactionNotClearStatus($transaction_id)
    {
        try {
            $builder = $this->db->table($this->table)
                ->where('id', $transaction_id)
                ->update([
                    'status' => 3
                ]);
            // echo $this->db->getLastQuery();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getTradeChequeDetailsById($id)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('*')
                ->where('id', $id)
                ->where('status', 3)
                ->get();
            // echo $this->getLastQuery();
            return $builder->getResultArray()[0];
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getDailyCollectionByTaxCollector($data)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('*')
                ->where('transaction_date>=', $data['from_date'])
                ->where('transaction_date<=', $data['to_date'])
                ->whereIn('ward_mstr_id', $data['wardPermission'])
                ->where('emp_details_id', $data['id'])
                ->whereIn('status', [1, 2])
                ->orderBy('ward_mstr_id,transaction_date', 'ASC')
                ->get();
            echo $this->db->getLastQuery();
            return $builder->getResultArray();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getTradeModeWiseCollectionByTcByOneMode($data)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('*')
                ->where('transaction_date>=', $data['from_date'])
                ->where('transaction_date<=', $data['to_date'])
                ->whereIn('ward_mstr_id', $data['wardPermission'])
                ->where('payment_mode', $data['payment_mode'])
                ->where('emp_details_id', $data['id'])
                ->whereIn('status', [1, 2])
                ->orderBy('ward_mstr_id,transaction_date', 'ASC')
                ->get();
            //echo $this->db->getLastQuery();
            return $builder->getResultArray();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getTotalAmount($employee_id, $tran_date)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('COALESCE(SUM(paid_amount),0) as paid_amount')
                ->where('md5(emp_details_id::text)', $employee_id)
                ->where('transaction_date', $tran_date)
                ->where('verify_status', NULL)

                ->whereIn('status', [1, 2])
                ->get();
            //echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
            return  $builder['paid_amount'];
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getTotalAmountCash($employee_id, $tran_date)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('COALESCE(SUM(paid_amount),0) as paid_amount')
                ->where('md5(emp_details_id::text)', $employee_id)
                ->where('transaction_date', $tran_date)
                ->where('verify_status', NULL)

                ->where('payment_mode', 'CASH')
                ->whereIn('status', [1, 2])
                ->get();
            //echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
            return  $builder['paid_amount'];
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getChequeDetailsByEmpId($id, $tran_date)
    {
        try {
            $sql = "select tbl_transaction.id as transaction_id,transaction_no,transaction_date,paid_amount as payable_amt,cheque_no,cheque_date,bank_name,branch_name from tbl_transaction left join tbl_cheque_dtl on tbl_cheque_dtl.transaction_id=tbl_transaction.id where transaction_date='$tran_date' 
          and md5(tbl_transaction.emp_details_id::text)='$id' and verify_status is NULL and payment_mode in('CHEQUE','DD')";

            $run = $this->query($sql);
            echo $this->getLastQuery();
            return $run->getResultArray();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function updateVerificationStatus($id, $transaction_id, $verified_on)
    {
        try {
            return $builder = $this->db->table($this->table)
                ->where('id', $transaction_id)
                ->update([
                    'verify_status' => 1,
                    'verified_on' => $verified_on,
                    'verified_by' => $id
                ]);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getDdDetailsByEmpId($id, $tran_date)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('*')
                ->where('md5(emp_details_id::text)', $id)
                ->where('status', 2)
                ->where('transaction_date', $tran_date)
                ->where('verify_status', NULL)

                ->where('payment_mode', 'DD')
                ->get();
            //echo $this->db->getLastQuery();
            return $builder->getResultArray();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function updateVerificationStatuCashCollection($employee_id, $verified_date, $id, $tran_date)
    {
        try {
            return $builder = $this->db->table($this->table)
                ->where('md5(emp_details_id::text)', $employee_id)
                ->where('payment_mode', 'CASH')
                ->where('transaction_date', $tran_date)
                ->update([
                    'verify_status' => 1,
                    'verified_on' => $verified_date,
                    'verified_by' => $id
                ]);
            //echo $this->db->getLastQuery();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function UpdateVerifiedStatus($inserted_id, $transaction_id)
    {
        try {
            return $builder = $this->db->table($this->table)
                ->where('id', $transaction_id)
                ->update([
                    'notification_id' => $inserted_id
                ]);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getAllNotVarifiedDataByEmpId($id, $tran_date)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('*')
                ->where('md5(emp_details_id::text)', $id)
                ->whereIn('status', [1, 2])
                ->where('transaction_date', $tran_date)
                ->where('verify_status', NULL)
                ->where('notification_id', NULL)

                ->get();
            // echo $this->db->getLastQuery();
            return $builder->getResultArray();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getTransactionByTransactionNo($data)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('*')
                ->where('upper(transaction_no)', $data['transaction_no'])
                ->whereIn('status', $data['statusData'])
                ->get();
            //echo $this->db->getLastQuery();
            return $builder->getResultArray()[0] ?? array();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getCheckDtlByno($data)
    {
        try {
            $builder = $this->db->table('tbl_cheque_dtl')
                ->select('*')
                ->where('upper(cheque_no)', $data['cheque_no'])
                ->whereIn('status', [1, 2])
                ->get();
            // echo $this->db->getLastQuery();
            return $builder->getResultArray();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getCheckDtlBytrid($data)
    {
        try {
            $builder = $this->db->table('tbl_cheque_dtl')
                ->select('*')
                ->where('md5(transaction_id::text)', $data['transaction_id'])
                ->whereIn('status', [1, 2])
                ->orderBy('id', 'desc')
                ->get();
            // echo $this->db->getLastQuery();
            return $builder->getFirstRow('array');
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getTransactionByTransactionId($data)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('*')
                ->where('md5(id::text)', $data['id'])
                ->whereIn('status', $data['statusData'])
                ->get();
            return $builder->getResultArray()[0];
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getTransactionByTransactionNoUsingMd($data)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('*')
                ->where('md5(transaction_no::text)', $data['transaction_no'])
                ->whereIn('status', $data['statusData'])
                ->get();
            //echo $this->db->getLastQuery();
            return $builder->getResultArray()[0];
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function updateTradeTransactionStatus($id)
    {
        try {
            return $builder = $this->db->table($this->table)
                ->where('id', $id)
                ->update([
                    "status" => 0
                ]);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function updatePaymentStatus($id)
    {
        try {
            return $builder = $this->db->table('tbl_apply_licence')
                ->where('id', $id)
                ->update([
                    "payment_status" => 0
                ]);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }




    public function gateCollection()
    {
        $sql = "SELECT sum(paid_amount),transaction_type
		FROM tbl_transaction
		group by transaction_type
		";
        $ql = $this->query($sql, [$data['id']]);
        $result = $ql->getResultArray();
        return $result;
    }

    public function getTotalAmountByVerifyStatus($verify_status)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('COALESCE(SUM(paid_amount),0) as totalamount')
                ->where('verify_status', $verify_status)
                ->whereIn('status', [1, 2])
                ->get();
            //echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
            return  $builder['totalamount'];
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getEmployeeId($verify_status)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('emp_details_id')
                ->where('md5(verify_status::text)', $verify_status)
                ->whereIn('status', [1, 2])
                ->get();
            //echo $this->db->getLastQuery();
            return $builder->getResultArray()[0];
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getNotVerifiedAmounTrade($id)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('COALESCE(SUM(paid_amount),0) as totalamount')
                ->where('md5(verify_status::text)', $id)
                ->whereIn('status', [1, 2])
                ->get();
            //echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
            return  $builder['totalamount'];
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }



    public function gatenewLicencecll($frm_date, $to_date)
    {
        $sql = "SELECT coalesce(sum(paid_amount),0) AS newAmount
		FROM tbl_transaction
		where status In(1,2) and transaction_type='NEW LICENSE' and transaction_date BETWEEN'" . $frm_date . "' and '" . $to_date . "'";
        $ql = $this->db->query($sql);
        //echo $this->db->getLastQuery();
        $result = $ql->getResultArray()[0];
        if (!empty($result)) {
            return $result;
        } else {
            return $result = 0;
        }
    }

    public function gaterenewalLicencecll($frm_date, $to_date)
    {
        $sql = "SELECT coalesce(sum(paid_amount),0) AS renewalAmount
		FROM tbl_transaction
		where status In(1,2) and transaction_type='RENEWAL' and transaction_date BETWEEN'" . $frm_date . "' and '" . $to_date . "'";
        $ql = $this->db->query($sql);
        $result = $ql->getResultArray()[0];
        if (!empty($result)) {
            return $result;
        } else {
            return $result = 0;
        }
    }

    public function gateamendmentLicencecll($frm_date, $to_date)
    {
        $sql = "SELECT coalesce(sum(paid_amount),0) AS amendmentAmount
		FROM tbl_transaction
		where status In(1,2) and transaction_type='AMENDMENT' and transaction_date BETWEEN'" . $frm_date . "' and '" . $to_date . "'";
        //COALESCE
        $ql = $this->db->query($sql);
        $result = $ql->getResultArray()[0];
        if (!empty($result)) {
            return $result;
        } else {
            return $result = 0;
        }
    }

    public function current_fy_collection($data)
    {
        //print_r($data);
        $fromdate = $data['fromdate'];
        $toDate = $data['toDate'];
        $sql = "SELECT sum(paid_amount) AS collectionAmount
			    FROM tbl_transaction
				where status In(1,2) AND transaction_date BETWEEN '$fromdate' AND '$toDate'";
        $ql = $this->db->query($sql);
        //echo $this->db->getLastQuery();
        $result = $ql->getResultArray()[0];
        return $result;
    }

    public function current_mnth_collection($data)
    {
        //print_r($data);
        $fromdate = $data['fromdate'];
        $sql = "SELECT sum(paid_amount) AS collectionAmount
			    FROM tbl_transaction
				where status In(1,2) AND transaction_date='$fromdate'";
        $ql = $this->db->query($sql);
        //echo $this->db->getLastQuery();
        $result = $ql->getResultArray()[0];
        return $result;
    }

    /*
	public function insertdy_collection($data){
		//print_r($data);
		$fromdate=$data['fromdate'];
		$sql = "INSERT INTO public.trade_dashboard_daily_collection(fy,date, amount)
				SELECT 
				'2020-2021','".$fromdate."',sum(paid_amount)
			    FROM tbl_transaction
				where status In(1,2) AND transaction_date='".$fromdate."'";
				$ql= $this->db->query($sql);
				//echo $this->db->getLastQuery();
				$result =$ql->getResultArray();
				return $result;
       
    }
	
	*/
    public function getTotalBydate($from_date, $transaction_mode)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('COALESCE(SUM(paid_amount),0) as totalamount')
                ->where('transaction_date', $from_date)
                ->where('payment_mode', $transaction_mode)
                ->whereIn('status', [1, 2])
                ->get();
            // echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
            return  $builder['totalamount'];
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getAllTotalBydate($payment_mode, $from_date, $to_date)
    {

        try {
            $sql = "select * from all_module_collection('" . $payment_mode . "') where m_date BETWEEN'" . $from_date . "' and '" . $to_date . "'";
            $ql = $this->db->query($sql);
            return $result = $ql->getResultArray();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function checkStatus($data)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('id')
                ->where('related_id', $data['apply_id'])
                ->where('ward_mstr_id', $data['ward_mstr_id'])
                ->where('status', 3)
                ->get();
            // echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
            return  $builder["id"];
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function insertRe_AllpyData($data)
    {
        $builder = $this->db->table($this->table)
            ->insert([
                'ward_mstr_id' => $data["ward_mstr_id"],
                'transaction_type' => $data["transaction_type"],
                'transaction_date' => $data["transaction_date"],
                'related_id' => $data["apply_licence_id"],
                'payment_mode' => $data["payment_mode"],
                'paid_amount' => $data["paid_amount"],
                'penalty' => $data["penalty"],
                'emp_details_id' => $data["emp_details_id"],
                'created_on' => $data["created_on"]
            ]);
        //echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }
    public function updateTransactionNo($transaction_no, $inserted_id)
    {
        try {
            return $builder = $this->db->table($this->table)
                ->where('id', $inserted_id)
                ->update([
                    "transaction_no" => $transaction_no
                ]);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getTransactionId($related_id)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('id')
                ->where('related_id', $related_id)
                ->get();
            // echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
            return  $builder["id"];
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getBulkPrintData($data)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('*')
                ->where('transaction_date>=', $data['from_date'])
                ->where('transaction_date<=', $data['to_date'])
                ->whereIn('status', [1, 2])
                ->get();
            //echo $this->db->getLastQuery();
            return $builder->getResultArray();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function tranProvDtl($data)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('*')
                ->where('md5(related_id::text)', $data)
                ->whereIn('status', [1, 2])
                ->get();
            //echo $this->db->getLastQuery();exit;
            return $builder->getResultArray()[0] ?? [];
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getTotalCashCollection($data)
    {
        try {
            $builder = $this->db->table($this->table);
            $builder = $builder->select('COALESCE(SUM(paid_amount),0) as cash,COALESCE(COUNT(DISTINCT related_id),0) as consumer,COALESCE(COUNT(id),0) as id');
            $builder = $builder->where('transaction_date >=', $data['from_date']);
            $builder = $builder->where('transaction_date <=', $data['to_date']);
            $builder = $builder->where('upper(payment_mode)', 'CASH');
            if ($data['ward_mstr_id'] != "") {
                $builder = $builder->where('ward_mstr_id', $data['ward_mstr_id']);
            }
            $builder = $builder->whereIn('status', [1, 2]);
            $builder = $builder->get();
            // echo $this->db->getLastQuery();
            return $builder->getFirstRow("array");
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    //get collection details 
    public function get_collection_details_with_id($tax_collector_id, $from_date, $to_date)
    {
        $sql = "select * from all_module_user_collection('$tax_collector_id','$from_date','$to_date')";
        //COALESCE
        $ql = $this->db->query($sql);
        //echo $this->db->getLastQuery();
        $result = $ql->getResultArray();
        if (!empty($result)) {
            return $result;
        } else {
            return $result = 0;
        }
    }

    public function get_collection_details($from_date, $to_date)
    {
        $sql = "SELECT 
                    COALESCE(SUM(paid_amount),0) as paid_amount,
                    CASE 
                        WHEN view_emp_details.emp_name ISNULL THEN 'ONLINE' ELSE view_emp_details.emp_name  
                    END as tax_collector,
                    CASE 
                        WHEN view_emp_details.last_name ISNULL THEN '' ELSE view_emp_details.last_name  
                    END as last_name,
                    COALESCE(COUNT(tbl_transaction.id),0) as id
                FROM tbl_transaction LEFT JOIN view_emp_details 
                    ON tbl_transaction.emp_details_id = view_emp_details.id 
                WHERE tbl_transaction.transaction_date >= '$from_date' 
                    AND tbl_transaction.transaction_date <= '$to_date'
                GROUP BY view_emp_details.emp_name,tbl_transaction.emp_details_id,view_emp_details.last_name";
        
        //COALESCE
        $ql = $this->db->query($sql);
        $result = $ql->getResultArray();
        if (!empty($result)) {
            return $result;
        } else {
            return $result = 0;
        }
    }

    public function getTotalChequeCollection($data)
    {
        try {
            $builder = $this->db->table($this->table);
            $builder = $builder->select('COALESCE(SUM(paid_amount),0) as cheque,COALESCE(COUNT(DISTINCT related_id),0) as consumer,COALESCE(COUNT(id),0) as id');
            $builder = $builder->where('transaction_date >=', $data['from_date']);
            $builder = $builder->where('transaction_date <=', $data['to_date']);
            $builder = $builder->where('upper(payment_mode)', 'CHEQUE');
            if ($data['ward_mstr_id'] != "") {
                $builder = $builder->where('ward_mstr_id', $data['ward_mstr_id']);
            }
            $builder = $builder->whereIn('status', [1, 2]);
            $builder = $builder->get();
            /*echo $this->db->getLastQuery();*/
            return $builder->getFirstRow("array");
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getTotalDDCollection($data)
    {
        try {
            $builder = $this->db->table($this->table);
            $builder = $builder->select('COALESCE(SUM(paid_amount),0) as dd,COALESCE(COUNT(DISTINCT related_id),0) as consumer,COALESCE(COUNT(id),0) as id');
            $builder = $builder->where('transaction_date >=', $data['from_date']);
            $builder = $builder->where('transaction_date <=', $data['to_date']);
            $builder = $builder->where('upper(payment_mode)', 'DD');
            if ($data['ward_mstr_id'] != "") {
                $builder = $builder->where('ward_mstr_id', $data['ward_mstr_id']);
            }
            $builder = $builder->whereIn('status', [1, 2]);
            $builder = $builder->get();
            /*echo $this->db->getLastQuery();*/
            return $builder->getFirstRow("array");
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getTotalOnlineCollection($data)
    {
        try {
            $builder = $this->db->table($this->table);
            $builder = $builder->select('COALESCE(SUM(paid_amount),0) as online,COALESCE(COUNT(DISTINCT related_id),0) as consumer,COALESCE(COUNT(id),0) as id');
            $builder = $builder->where('transaction_date >=', $data['from_date']);
            $builder = $builder->where('transaction_date <=', $data['to_date']);
            $builder = $builder->where('upper(payment_mode)', 'ONLINE');
            if ($data['ward_mstr_id'] != "") {
                $builder = $builder->where('ward_mstr_id', $data['ward_mstr_id']);
            }
            $builder = $builder->whereIn('status', [1, 2]);
            $builder = $builder->get();
            /*echo $this->db->getLastQuery();*/
            return $builder->getFirstRow("array");
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getTotalCardCollection($data)
    {
        try {
            $builder = $this->db->table($this->table);
            $builder = $builder->select('COALESCE(SUM(paid_amount),0) as card,COALESCE(COUNT(DISTINCT related_id),0) as consumer,COALESCE(COUNT(id),0) as id');
            $builder = $builder->where('transaction_date >=', $data['from_date']);
            $builder = $builder->where('transaction_date <=', $data['to_date']);
            $builder = $builder->where('upper(payment_mode)', 'CARD');
            if ($data['ward_mstr_id'] != "") {
                $builder = $builder->where('ward_mstr_id', $data['ward_mstr_id']);
            }
            $builder = $builder->whereIn('status', [1, 2]);
            $builder = $builder->get();
            /*echo $this->db->getLastQuery();*/
            return $builder->getFirstRow("array");
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getTotalChequeCollectionCancel($data)
    {
        try {
            $builder = $this->db->table($this->table);
            $builder = $builder->select('COALESCE(SUM(paid_amount),0) as cheque,COALESCE(COUNT(DISTINCT related_id),0) as consumer,COALESCE(COUNT(id),0) as id');
            $builder = $builder->where('transaction_date >=', $data['from_date']);
            $builder = $builder->where('transaction_date <=', $data['to_date']);
            $builder = $builder->where('upper(payment_mode)', 'CHEQUE');
            if ($data['ward_mstr_id'] != "") {
                $builder = $builder->where('ward_mstr_id', $data['ward_mstr_id']);
            }
            $builder = $builder->where('status', 3);
            $builder = $builder->get();
            /*echo $this->db->getLastQuery();*/
            return $builder->getFirstRow("array");
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getTotalDDCollectionCancel($data)
    {
        try {
            $builder = $this->db->table($this->table);
            $builder = $builder->select('COALESCE(SUM(paid_amount),0) as dd,COALESCE(COUNT(DISTINCT related_id),0) as consumer,COALESCE(COUNT(id),0) as id');
            $builder = $builder->where('transaction_date >=', $data['from_date']);
            $builder = $builder->where('transaction_date <=', $data['to_date']);
            $builder = $builder->where('upper(payment_mode)', 'DD');
            if ($data['ward_mstr_id'] != "") {
                $builder = $builder->where('ward_mstr_id', $data['ward_mstr_id']);
            }
            $builder = $builder->where('status', 3);
            $builder = $builder->get();
            /*echo $this->db->getLastQuery();*/
            return $builder->getFirstRow("array");
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getTotalOnlineCollectionCancel($data)
    {
        try {
            $builder = $this->db->table($this->table);
            $builder = $builder->select('COALESCE(SUM(paid_amount),0) as online,COALESCE(COUNT(DISTINCT related_id),0) as consumer,COALESCE(COUNT(id),0) as id');
            $builder = $builder->where('transaction_date >=', $data['from_date']);
            $builder = $builder->where('transaction_date <=', $data['to_date']);
            $builder = $builder->where('upper(payment_mode)', 'ONLINE');
            if ($data['ward_mstr_id'] != "") {
                $builder = $builder->where('ward_mstr_id', $data['ward_mstr_id']);
            }
            $builder = $builder->where('status', 3);
            $builder = $builder->get();
            /*echo $this->db->getLastQuery();*/
            return $builder->getFirstRow("array");
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getTotalCardCollectionCancel($data)
    {
        try {
            $builder = $this->db->table($this->table);
            $builder = $builder->select('COALESCE(SUM(paid_amount),0) as card,COALESCE(COUNT(DISTINCT related_id),0) as consumer,COALESCE(COUNT(id),0) as id');
            $builder = $builder->where('transaction_date >=', $data['from_date']);
            $builder = $builder->where('transaction_date <=', $data['to_date']);
            $builder = $builder->where('upper(payment_mode)', 'CARD');
            if ($data['ward_mstr_id'] != "") {
                $builder = $builder->where('ward_mstr_id', $data['ward_mstr_id']);
            }
            $builder = $builder->where('status', 3);
            $builder = $builder->get();
            /*echo $this->db->getLastQuery();*/
            return $builder->getFirstRow("array");
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getTotalNewLicenceCollection($data)
    {
        try {
            $builder = $this->db->table($this->table);
            $builder = $builder->select('COALESCE(SUM(paid_amount),0) as new,COALESCE(COUNT(DISTINCT related_id),0) as consumer,COALESCE(COUNT(id),0) as id');
            $builder = $builder->where('transaction_date >=', $data['from_date']);
            $builder = $builder->where('transaction_date <=', $data['to_date']);
            $builder = $builder->where('upper(transaction_type)', 'NEW LICENSE');
            if ($data['ward_mstr_id'] != "") {
                $builder = $builder->where('ward_mstr_id', $data['ward_mstr_id']);
            }
            $builder = $builder->whereIn('status', [1, 2]);
            $builder = $builder->get();
            //echo $this->db->getLastQuery();exit;
            return $builder->getFirstRow("array");
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getTotalRenewalLicenceCollection($data)
    {
        try {
            $builder = $this->db->table($this->table);
            $builder = $builder->select('COALESCE(SUM(paid_amount),0) as renewal,COALESCE(COUNT(DISTINCT related_id),0) as consumer,COALESCE(COUNT(id),0) as id');
            $builder = $builder->where('transaction_date >=', $data['from_date']);
            $builder = $builder->where('transaction_date <=', $data['to_date']);
            $builder = $builder->where('upper(transaction_type)', 'RENEWAL');
            if ($data['ward_mstr_id'] != "") {
                $builder = $builder->where('ward_mstr_id', $data['ward_mstr_id']);
            }
            $builder = $builder->whereIn('status', [1, 2]);
            $builder = $builder->get();
            //echo $this->db->getLastQuery();
            return $builder->getFirstRow("array");
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getTotalAmendmentLicenceCollection($data)
    {
        try {
            $builder = $this->db->table($this->table);
            $builder = $builder->select('COALESCE(SUM(paid_amount),0) as amendment,COALESCE(COUNT(DISTINCT related_id),0) as consumer,COALESCE(COUNT(id),0) as id');
            $builder = $builder->where('transaction_date >=', $data['from_date']);
            $builder = $builder->where('transaction_date <=', $data['to_date']);
            $builder = $builder->where('upper(transaction_type)', 'AMENDMENT');
            if ($data['ward_mstr_id'] != "") {
                $builder = $builder->where('ward_mstr_id', $data['ward_mstr_id']);
            }
            $builder = $builder->whereIn('status', [1, 2]);
            $builder = $builder->get();
            //echo $this->db->getLastQuery();
            return $builder->getFirstRow("array");
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getTotalSurenderLicenceCollection($data)
    {
        try {
            $builder = $this->db->table($this->table);
            $builder = $builder->select('COALESCE(SUM(paid_amount),0) as surender,COALESCE(COUNT(DISTINCT related_id),0) as consumer,COALESCE(COUNT(id),0) as id');
            $builder = $builder->where('transaction_date >=', $data['from_date']);
            $builder = $builder->where('transaction_date <=', $data['to_date']);
            $builder = $builder->where('upper(transaction_type)', 'SURRENDER');
            if ($data['ward_mstr_id'] != "") {
                $builder = $builder->where('ward_mstr_id', $data['ward_mstr_id']);
            }
            $builder = $builder->whereIn('status', [1, 2]);
            $builder = $builder->get();
            //echo $this->db->getLastQuery();exit;
            return $builder->getFirstRow("array");
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }


    public function getTotalPaidAmountwithCountTrans($from_date, $to_date)
    {

        $sql = "select count(id) as count,coalesce(sum(paid_amount),0) as paid_amount from tbl_transaction where transaction_date between '$from_date' and '$to_date'  and status in(1,2)";
        $run = $this->db->query($sql);
        $result = $run->getFirstRow("array");
        // echo $this->getLastQuery();
        return $result;
    }

    public function payment_details($data)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('*')
                ->where('related_id', $data)
                ->whereIn('status', [1, 2])
                ->get();
            //echo $this->db->getLastQuery();
            //return $builder->getResultArray()[0];
            return $builder->getResultArray();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getTranById($transaction_id)
    {
        try {
            $data = $this->db->table($this->table)
                ->select('*')
                ->where('id', $transaction_id)
                ->where('status <>', 0)
                ->get();                      
            $data = $data->getFirstRow('array');
            // echo $this->db->getLastQuery();
            //print_var($data);
            return $data;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getTransactionWithChequeDetails($where)
    {
        $sql = "select tbl_transaction.*,'Trade' as tran_type,
                tbl_cheque_dtl.id as cheque_dtl_id,cheque_no,cheque_date,bank_name,branch_name,
                tbl_cheque_dtl.clear_bounce_date,tbl_cheque_dtl.remarks as clear_bounce_remarks,
                view_emp_details.emp_name  
            from tbl_transaction 
            join tbl_cheque_dtl on tbl_cheque_dtl.transaction_id=tbl_transaction.id 
            left join view_emp_details on view_emp_details.id = tbl_transaction.emp_details_id
            $where";
        $run = $this->db->query($sql);
        $result = $run->getResultArray();
        // echo $this->getLastQuery();
        return $result;
    }
    public function updateStatusClear($transaction_id)
    {
        $sql = "update tbl_transaction set status=1 where id=$transaction_id";
        $run = $this->db->query($sql);
    }
    public function updateBounceStatus($transaction_id)
    {
        $sql = "update tbl_transaction set status=3 where id=$transaction_id";
        $run = $this->db->query($sql);
        // echo $this->db->getLastQuery();
    }

    public function updateChequeBounceStatus($transaction_id)
    {
        $sql = "update tbl_cheque_dtl set status=3 where transaction_id=$transaction_id";
        $run = $this->db->query($sql);
    }

    public function updateChequeClearStatus($transaction_id)
    {
        $sql = "update tbl_cheque_dtl set status=1 where transaction_id=$transaction_id";
        $run = $this->db->query($sql);
    }

    public function getChequeBouncePenalty($apply_licence_id)
    {
        $sql = "select coalesce(sum(amount), 0) as penalty from tbl_bank_recancilation where related_id=$apply_licence_id and status=3";
        $run = $this->db->query($sql);
        $result = $run->getFirstRow("array");
        //echo $this->getLastQuery();
        return $result['penalty'];
    }


    public function tradegetTotalPaidAmountwithCountTransbyempid($from_date, $to_date, $tax_collector_id)
    {

        $sql = "select count(id) as count,coalesce(sum(paid_amount),0) as paid_amount,emp_details_id
		from tbl_transaction 
		where transaction_date between '$from_date' and '$to_date'  and status in(1,2)
		and emp_details_id='$tax_collector_id'
		group by emp_details_id";
        $run = $this->db->query($sql);
        $result = $run->getFirstRow("array");
        // echo $this->getLastQuery();
        return $result;
    }

    public function tradegetTotalPaidAmountwithCountTransallid($from_date, $to_date)
    {

        $sql = "select count(id) as count,coalesce(sum(paid_amount),0) as paid_amount,emp_details_id
		from tbl_transaction 
		where transaction_date between '$from_date' and '$to_date'  and status in(1,2)
		group by emp_details_id";
        $run = $this->db->query($sql);
        $result = $run->getFirstRow("array");
        // echo $this->getLastQuery();
        return $result;
    }


    public function collhome()
    {

        $sql = "select sum(paid_amount) as amnt  from tbl_transaction where status=1";
        $run = $this->db->query($sql);
        $result = $run->getFirstRow("array");
        // echo $this->getLastQuery();
        return $result;
    }

    public function get_all_licence_by_ward($ward_id, $where)
    {
        $sql = "select count(related_id) as no_of_application,ward_mstr_id,
        sum(paid_amount) as amount
        from tbl_transaction
        where $where
        and status in (1,2) and ward_mstr_id = " . $ward_id . "  group by ward_mstr_id";
        $run = $this->db->query($sql);
        $result = $run->getResultArray()[0];
        // echo $this->getLastQuery();exit;
        return $result;
    }

    public function get_licence_by_single_ward($ward_id, $where)
    {
        $sql = "select count(id) as no_of_application,ward_mstr_id 
		from tbl_apply_licence where $where and status=1  and ward_mstr_id = " . $ward_id . "
         group by ward_mstr_id";
        $run = $this->db->query($sql);
        $result = $run->getResultArray()[0];
        //echo $this->getLastQuery();exit;
        return $result;
    }


    public function get_Licence_by_single_ward_transaction($ward_id, $from_date, $to_date)
    {
        $sql = "select l.id,l.ward_no,l.application_no,l.application_type,l.firm_name,l.apply_date from tbl_transaction t inner join 
        view_trade_licence l on t.related_id=l.id where (t.transaction_date between '" . $from_date . "' and '" . $to_date . "')  and t.status in (1,2) 
         and md5(t.ward_mstr_id::text) = '" . $ward_id . "'  order by l.id desc";
        $run = $this->db->query($sql);
        $result = $run->getResultArray();
        // echo $this->getLastQuery();exit;
        return $result;
    }

    public function get_Licence_by_ward_and_application_type($ward_id, $application_type_id, $from_date, $to_date, $where = null)
    {
        if ($application_type_id == 'rej') {
            $sql = "select t.id,vward.ward_no,t.application_no,apptype.application_type,t.firm_name,t.apply_date 
            from tbl_apply_licence t 
            inner join (select distinct apply_licence_id from tbl_level_pending 
            where forward_date between '$from_date' and '$to_date' and status = 4
           ) newl 
            on newl.apply_licence_id=t.id 
            inner join (select * from view_ward_mstr) vward
            on vward.id = t.ward_mstr_id
            inner join (select * from tbl_application_type_mstr) as apptype
            on apptype.id = t.application_type_id where t.ward_mstr_id = '$ward_id'
            order by t.id desc";
        } elseif ($application_type_id == 'levl') {
            $sql = "select t.id,vward.ward_no,t.application_no,apptype.application_type,t.firm_name,t.apply_date 
            from (select * from tbl_level_pending where id in (select max(id) from tbl_level_pending group by apply_licence_id) and forward_date between '$from_date' and '$to_date'and status not in (2,4,5)
            ) newl left join tbl_apply_licence t              
            on newl.apply_licence_id=t.id 
            inner join view_ward_mstr vward
            on vward.id = t.ward_mstr_id
            inner join tbl_application_type_mstr as apptype
            on apptype.id = t.application_type_id where t.ward_mstr_id = '$ward_id'
            order by t.id desc";
        } elseif ($application_type_id == 'bo') {
            $sql = "select t.id,vward.ward_no,t.application_no,apptype.application_type,t.firm_name,t.apply_date 
            from (select * from tbl_level_pending where id in (select max(id) from tbl_level_pending group by apply_licence_id) and forward_date between '$from_date' and '$to_date'and status=2
            ) newl left join tbl_apply_licence t              
            on newl.apply_licence_id=t.id 
            inner join view_ward_mstr vward
            on vward.id = t.ward_mstr_id
            inner join tbl_application_type_mstr as apptype
            on apptype.id = t.application_type_id where t.ward_mstr_id = '$ward_id'
            order by t.id desc";
        } elseif ($application_type_id == '5') {
            $sql = "select t.id,vward.ward_no,t.application_no,apptype.application_type,t.firm_name,t.apply_date 
            from (select * from tbl_level_pending where id in (select max(id) from tbl_level_pending group by apply_licence_id) and forward_date between '$from_date' and '$to_date'and status=5
            ) newl left join tbl_apply_licence t              
            on newl.apply_licence_id=t.id 
            inner join view_ward_mstr vward
            on vward.id = t.ward_mstr_id
            inner join tbl_application_type_mstr as apptype
            on apptype.id = t.application_type_id where t.ward_mstr_id = '$ward_id'
            order by t.id desc";
        } elseif ($application_type_id == 'jsk') {
            $sql = "select tbl_apply_licence.*,view_ward_mstr.ward_no,view_ward_mstr.ulb_mstr_id,
                tbl_application_type_mstr.application_type
            from tbl_apply_licence 
            left join view_ward_mstr  on view_ward_mstr.id = tbl_apply_licence.ward_mstr_id 
            left join tbl_application_type_mstr on tbl_application_type_mstr.id = tbl_apply_licence.application_type_id
            where  apply_date::date between '$from_date' and '$to_date' AND payment_status=0 and 
            tbl_apply_licence.status=1 and ward_mstr_id = '$ward_id'
            ";
        }elseif ($application_type_id == 'bco') {
            $sql = "select tbl_apply_licence.*,view_ward_mstr.ward_no,view_ward_mstr.ulb_mstr_id,
                tbl_application_type_mstr.application_type
            from tbl_apply_licence 
            left join view_ward_mstr  on view_ward_mstr.id = tbl_apply_licence.ward_mstr_id 
            left join tbl_application_type_mstr on tbl_application_type_mstr.id = tbl_apply_licence.application_type_id
            where  apply_date::date between '$from_date' and '$to_date' AND payment_status=1 AND pending_status = 0  AND document_upload_status = 0 and 
            tbl_apply_licence.status=1 and ward_mstr_id = '$ward_id'
            ";
        } elseif ($application_type_id == 'prov') {
            $sql = "select tbl_apply_licence.*,view_ward_mstr.ward_no,view_ward_mstr.ulb_mstr_id,
                tbl_application_type_mstr.application_type
             from tbl_apply_licence left join view_ward_mstr on view_ward_mstr.id = tbl_apply_licence.ward_mstr_id 
            left join tbl_application_type_mstr on tbl_application_type_mstr.id = tbl_apply_licence.application_type_id 
            where apply_date::date between '$from_date' and '$to_date'and provisional_license_no is not null and tbl_apply_licence.status = 1 and payment_status = 1
            and ward_mstr_id = '$ward_id'";
        } elseif ($application_type_id == 'da' || $application_type_id == 'td' || $application_type_id == 'sh' || $application_type_id == 'eo') {
            $sql = "select tbl_apply_licence.*,apptype.application_type,vward.ward_no  from tbl_level_pending 
            left join tbl_apply_licence 
            on tbl_level_pending.apply_licence_id = tbl_apply_licence.id 
            left join tbl_application_type_mstr as apptype
            on apptype.id = tbl_apply_licence.application_type_id
            left join view_ward_mstr vward
            on vward.id = tbl_apply_licence.ward_mstr_id
            where $where and tbl_level_pending.id in (select max(id) from tbl_level_pending 
            group by apply_licence_id)";
        } else {
            $sql = "select t.id,vward.ward_no,t.application_no,apptype.application_type,t.firm_name,t.apply_date 
            from tbl_apply_licence t 
            inner join (select distinct related_id from tbl_transaction 
            where transaction_date between '$from_date' and '$to_date' and status in (1,2) 
            and ward_mstr_id = '$ward_id' and transaction_type='$application_type_id') newl 
            on newl.related_id=t.id 
            inner join (select * from view_ward_mstr) vward
            on vward.id = t.ward_mstr_id
            inner join (select * from tbl_application_type_mstr) as apptype
            on apptype.id = t.application_type_id
            order by t.id";
        }

        $run = $this->db->query($sql);
        $result = $run->getResultArray();
        // echo $this->getLastQuery();exit;
        return $result;
    }

    public function get_collection_by_ward_and_application_type($ward_id, $application_type_id, $from_date, $to_date)
    {
        $sql = "select t.id,vward.ward_no,t.application_no,apptype.application_type,t.firm_name,t.apply_date,newl.sum 
            from tbl_apply_licence t inner join (select related_id,sum(paid_amount) as sum from tbl_transaction 
            where transaction_date between '$from_date' and '$to_date' and status in (1,2) 
            and ward_mstr_id = '$ward_id' and transaction_type='$application_type_id' group by related_id) 
            newl on newl.related_id=t.id 
            inner join (select * from view_ward_mstr) vward on vward.id = t.ward_mstr_id 
            inner join (select * from tbl_application_type_mstr) as apptype on apptype.id = t.application_type_id order by t.id";


        $run = $this->db->query($sql);
        $result = $run->getResultArray();
        // echo $this->getLastQuery();exit;
        return $result;
    }
    public function getTransaction_ID($applyID)
    {

        return $result = $this->db->table($this->table)
            ->select("id,related_id")
            //->where('md5(related_id::text)',$applyID)
            ->where('md5(id::text)', $applyID)
            ->whereIn('status', [1, 2])
            ->get()
            ->getFirstRow("array");
    }

    public function getRebetDetails($transaction_id)
    {
        $result = $this->db->table('tbl_transaction_fine_rebet_details')
            ->select("*")
            ->where('md5(transaction_id::text)', $transaction_id)
            ->where('status', 1)
            ->get()
            ->getResultArray();
        //echo $this->db->getLastQuery();
        return $result;
    }



    public function getTransaction_details($applyID)
    {
        return $result = $this->db->table($this->table)
            ->select("*")
            ->where('related_id', $applyID)
            ->whereIn('status', [1, 2])
            ->get()
            ->getResultArray()[0];
        // echo $this->getLastQuery();exit;
    }

    public function updatePaymentMode($data)
    {
        try {
            $builder = $this->db->table($this->table)
                ->where('id', $data['transactionId'])
                ->update([
                    'payment_mode' => $data['payment_mode'],
                ]);
            //echo $this->db->getLastQuery();exit;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getPenlatyDtlByTrId($transection_id)
    {
        try{
            $data = $this->db->table('tbl_bank_recancilation')
                             ->select('*')
                             ->where('transaction_id',$transection_id) 
                             ->orderBy('id','desc')
                             ->get()
                             ->getFirstRow('array');   
            return $data;                         
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }
    public function insertChBouncPenlaty(array $data)
    {

    	$result= $this->db->table('tbl_bank_recancilation')
                 ->insert($data);       
       // echo $this->getLastQuery();
        $insert_id=$this->db->insertID();
        return $insert_id;

    }
    public function updateChBouncPenlatyAmtByTrId($data=array())
    {
        try{
            $data = $this->db->table('tbl_bank_recancilation')
                             ->where('transaction_id',$data['transaction_id']) 
                             ->update([
                                 'amount'=>$data['amount'],
                             ]);
                             
                            
            return $data;                         
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function updateChBouncPenlatyStatusByTrId($data=array())
    {
        try{
            $data = $this->db->table('tbl_bank_recancilation')
                             ->where('transaction_id',$data['transaction_id']) 
                             ->update([
                                 'status'=>1,
                             ]);
                             
                            
            return $data;                         
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }


    public function row_query($sql)
    {
        $run = $this->db->query($sql)->getResultArray();
        //echo($this->db->getLastQuery());die;
        return $run;
    }

    public function updateChequeData($id,$data){
        return $this->db->table("tbl_cheque_dtl")
                ->where("id",$id)
                ->update(
                    [
                        "cheque_no"=>$data["cheque_no"],
                        "cheque_date"=>$data["cheque_date"],
                        "bank_name"=>$data["bank_name"],
                        "branch_name"=>$data["branch_name"]
                    ]
                );
    }

    public function update_trade_cheque($cheque_tbl_id,$cheuqe_no_to_update)
    {
         $sql="update tbl_cheque_dtl set cheque_no='".$cheuqe_no_to_update."' where id=".$cheque_tbl_id."";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();
        return $result;
        
    }
}
