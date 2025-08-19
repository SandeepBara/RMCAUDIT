<?php

namespace App\Models;

use App\Models\Water\TblRequestType;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class Water_Transaction_Model extends Model
{

    protected $table = 'tbl_transaction';
    protected $allowedFields = ['id', 'ward_mstr_id', 'transaction_no', 'transaction_type', 'transaction_date', 'related_id', 'payment_mode', 'penalty', 'rebate', 'paid_amount', 'verify_status', 'verified_on', 'emp_details_id', 'created_on', 'status', 'verified_by', 'notification_id'];

    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }

    // Using In Cash Verification
    public function WaterPaymentList($emp_details_id, $transaction_date)
    {

        $sql = "select tbl_transaction.id as transaction_id, transaction_no, applicant_name, 
            payment_mode, from_month, upto_month, paid_amount, tbl_transaction.related_id as consumer_id, transaction_type, ward_no, applicant_name, consumer_no, address, verify_status, emp_name as verified_by, verified_on
            from tbl_transaction 
            join view_ward_mstr on view_ward_mstr.id=tbl_transaction.ward_mstr_id
            join tbl_consumer on tbl_consumer.id=tbl_transaction.related_id
            LEFT JOIN (
                SELECT tbl_consumer_details.consumer_id,
                        string_agg((tbl_consumer_details.applicant_name)::text, ','::text) AS applicant_name,
                        string_agg((tbl_consumer_details.mobile_no)::text, ','::text) AS mobile_no
                    FROM tbl_consumer_details
                    WHERE tbl_consumer_details.status = 1
                group by consumer_id
            )  as ownerr on ownerr.consumer_id=tbl_consumer.id
            left join view_emp_details on tbl_transaction.verified_by = view_emp_details.id
            where tbl_transaction.transaction_date='$transaction_date' and tbl_transaction.emp_details_id=$emp_details_id and 
            tbl_transaction.transaction_type='Demand Collection' and tbl_transaction.status in (1, 2)";
        $sql = $this->db->query($sql);
        //echo $this->db->getLastQuery();
        return $result = $sql->getResultArray();
    }


    public function WaterConnectionPaymentList($emp_details_id, $transaction_date)
    {

        $sql = "select tbl_transaction.id as transaction_id, transaction_no, applicant_name, 
        payment_mode, from_month, upto_month, paid_amount, tbl_transaction.related_id as consumer_id, transaction_type, ward_no, applicant_name, application_no, address, verify_status, emp_name as verified_by, verified_on
        from tbl_transaction 
        join view_ward_mstr on view_ward_mstr.id=tbl_transaction.ward_mstr_id
        join tbl_apply_water_connection on tbl_apply_water_connection.id=tbl_transaction.related_id
        LEFT JOIN (
            SELECT tbl_applicant_details.apply_connection_id,
                    string_agg((tbl_applicant_details.applicant_name)::text, ','::text) AS applicant_name,
                    string_agg((tbl_applicant_details.mobile_no)::text, ','::text) AS mobile_no
                FROM tbl_applicant_details
                GROUP BY tbl_applicant_details.apply_connection_id
            
        )as ownerr on ownerr.apply_connection_id=tbl_apply_water_connection.id
        left join view_emp_details on tbl_transaction.verified_by = view_emp_details.id
        where tbl_transaction.transaction_date='$transaction_date' and tbl_transaction.emp_details_id=$emp_details_id and 
        tbl_transaction.transaction_type IN ('New Connection', 'Site Inspection') and tbl_transaction.status in (1, 2)";
        $sql = $this->db->query($sql);
        //echo $this->db->getLastQuery();
        return $result = $sql->getResultArray();
    }


    // Using In Cash Verification
    function CashVerify($water_trxn_ids, $verified_by)
    {
        $builder = $this->db->table($this->table)
            ->wherein('id', $water_trxn_ids)
            ->update([
                'verify_status' => 1,
                'verified_by' => $verified_by,
                'verified_on' => 'NOW()',
            ]);
        //echo $this->db->getLastQuery().'water';
        return $builder;
    }

    public function water_pay_now($trxn, $other, $cheque=array())
    {
        $result= $this->db->table($this->table)->insert($trxn);       
        //echo $this->getLastQuery();
        $transaction_id=$this->db->insertID();

        //update trxn_no
        $trans_no="WTRAN".$transaction_id.date('dmyhis');
        $this->db->table($this->table)
            ->where('id', $transaction_id)
            ->update(['transaction_no'=> $trans_no]);

        
        if(in_array($trxn["payment_mode"], ["CHEQUE", "DD","NEFT",'RTGS'])){
            
            $chq_arr=array();
            $chq_arr['transaction_id']=$transaction_id;
            $chq_arr['cheque_no']= $cheque["cheque_no"];
            $chq_arr['cheque_date']= $cheque["cheque_date"];
            $chq_arr['bank_name']= $cheque["bank_name"];
            $chq_arr['branch_name']= $cheque["branch_name"];
            $chq_arr['emp_details_id']= $trxn["emp_details_id"];
            $chq_arr['created_on']= date('Y-m-d H:i:s');
            $chq_arr['status'] = 2;

            $result= $this->db->table("tbl_cheque_details")->insert($chq_arr);       
            //echo $this->getLastQuery();
            $check_id=$this->db->insertID();
        }


        if($other["other_penalty"]>0)
        {
            $trans_fine=array();
            $trans_fine['transaction_id']=$transaction_id;
            $trans_fine['head_name']="Cheque Bounce Charge";
            $trans_fine['amount']= $other["other_penalty"];
            $trans_fine['value_add_minus']="+";
            $trans_fine['created_on']=date('Y-m-d H:i:s');
            $trans_fine['status']=1;
            $this->db->table("tbl_transaction_fine_rebet_details")->insert($trans_fine);
        }
        if($trxn["penalty"]>0)
        {
            $trans_fine=array();
            $trans_fine['transaction_id']=$transaction_id;
            $trans_fine['head_name']="1.5% Penalty";
            $trans_fine['amount']=$trxn["penalty"];
            $trans_fine['value_add_minus']="+";
            $trans_fine['created_on']=date('Y-m-d H:i:s');
            $trans_fine['status']=1;
            $this->db->table("tbl_transaction_fine_rebet_details")->insert($trans_fine);
        }
        if($trxn["rebate"]>0)
        {
            $trans_rebate=array();
            $trans_rebate['transaction_id']=$transaction_id;
            $trans_rebate['head_name']="Penalty";
            $trans_rebate['amount']=$trxn["rebate"];
            $trans_rebate['value_add_minus']="+";
            $trans_rebate['created_on']=date('Y-m-d H:i:s');
            $trans_rebate['status']=1;
            $this->payment_model->insert_fine_rebate($trans_rebate);
        }

        // Insert Collection
        $sql="
            insert into tbl_consumer_collection
            (consumer_id,ward_mstr_id,transaction_id,amount,emp_details_id,created_on,status,demand_id, demand_from,demand_upto,penalty,connection_type)

            select consumer_id,ward_mstr_id, $transaction_id as trans_id,amount, $trxn[emp_details_id] as emp_id,'".date('Y-m-d H:i:s')."' as created_on,1,id,demand_from,demand_upto,penalty,connection_type from tbl_consumer_demand where paid_status=0 and status=1 and consumer_id=$trxn[related_id] and id in($other[demand_id])";
        $run=$this->db->query($sql);

        // Update Demand
        $sql = "update tbl_consumer_demand set paid_status=1 where consumer_id=$trxn[related_id] and paid_status=0 and status=1 and id in($other[demand_id])";
        $run = $this->db->query($sql);

        // Update Dues Amount
        $sql = "select round(coalesce(sum(amount),0)) as due_amount from tbl_consumer_demand where consumer_id=$trxn[related_id] and paid_status=0 and status = 1";
        $run = $this->db->query($sql);
        $due_amount = $run->getFirstRow("array");
        $sql="update tbl_transaction set due_amount=$due_amount[due_amount] where id=$transaction_id";
        $this->db->query($sql);

        return $transaction_id;
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

    public function getTransactionDetails($transaction_id)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('id,ward_mstr_id,transaction_no,upper(transaction_type) as transaction_type,transaction_date,
                    related_id,payment_mode,penalty,rebate,paid_amount,verify_status,verified_by,
                    verified_on,emp_details_id,created_on,status,total_amount,from_month ,upto_month ,ip_address,notification_id
                    ')
                ->where('id', $transaction_id)
                ->where('status', 2)
                ->get();
            // echo $this->getLastQuery();
            return $builder->getResultArray()[0];
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function BankReconciliation($from_date, $upto_date)
    {

        $sql = "select tbl_transaction.*, tbl_transaction.transaction_no as tran_no, tbl_transaction.transaction_date as tran_date, transaction_type as tran_type, paid_amount as payable_amt,  tbl_cheque_details.id as cheque_dtl_id,cheque_no,cheque_date,bank_name,branch_name 
        from tbl_transaction 
        join tbl_cheque_details on tbl_cheque_details.transaction_id=tbl_transaction.id
        where upper(payment_mode) in ('CHEQUE', 'DD') and transaction_date between '$from_date' and '$upto_date'";
        $run = $this->db->query($sql);
        $result = $run->getResultArray();
        //echo $this->getLastQuery();
        return $result;
    }

    public function getChequeTransactionDetailsbyId($transaction_id)
    {

        $sql = "select tbl_transaction.*,tbl_cheque_details.id as cheque_dtl_id,cheque_no,cheque_date,bank_name,branch_name from tbl_transaction join tbl_cheque_details on tbl_cheque_details.transaction_id=tbl_transaction.id
        where md5(tbl_transaction.id::text)='$transaction_id' and tbl_transaction.status in(1,2)";
        $run = $this->db->query($sql);
        $result = $run->getFirstRow("array");
        //echo $this->getLastQuery();
        return $result;
    }


    public function updateStatus($transaction_id)
    {
        try {
            $builder = $this->db->table($this->table)
                ->where('id', $transaction_id)
                ->update([
                    'status' => 3
                ]);
            //echo $this->db->getLastQuery();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function updateChequeClearStatus($transaction_id)
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
    /*public function getAllTransactionDataWithQuerey(){
        try{
            $sql ="with applicant as(
                select tbl_transaction.paid_amount,tbl_transaction.transaction_no,tbl_transaction.transaction_date,tbl_apply_water_connection.application_no,tbl_applicant_details.applicant_name from tbl_transaction join tbl_applicant_details on
                tbl_applicant_details.id=tbl_transaction.related_id on
                tbl_applicant_details.apply_connection_id=tbl_apply_water_connection.id 
                where tbl_transaction.transaction_type ='New Connection'
            ), consumer as (
             select tbl_transaction.paid_amount,tbl_transaction.transaction_no,tbl_transaction.transaction_date,tbl_consumer.consumer_no,tbl_consumer_details.applicant_name from tbl_transaction join tbl_consumer_details on
                tbl_consumer_details.id=tbl_transaction.related_id on
                tbl_consumer_details.consumer_id=tbl_consumer.id 
                where tbl_transaction.transaction_type ='Demand'
            )
            select transaction_no,transaction_date,applicant_name,consumer_no,paid_amount from applicant full join applicant on applicant.id=consumer.apply_connection_id ";
        }catch(Exception $e){

        }*/
    // }
    public function getTransactionByDate($data)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('*')
                ->where('transaction_date >=', $data['from_date'])
                ->where('transaction_date <=', $data['to_date'])
                ->where('payment_mode', $data['payment_mode'])
                ->whereIn('status', [1, 2])
                ->get();
            return $builder->getResultArray();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getTransaction($data)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('*')
                ->where('transaction_date >=', $data['from_date'])
                ->where('transaction_date <=', $data['to_date'])
                ->whereIn('status', [1, 2])
                ->get();
            //echo $this->getLastQuery();
            return $builder->getResultArray();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getDemandTransactions($where)
    {
        $sql = "select * from view_demand_transactions $where";
        $run = $this->db->query($sql);
        $result = $run->getResultArray();
        //echo $this->db->getLastQuery();
        return $result;
    }

    public function getNewConnectionTransactions($where)
    {
        $sql = "select * from view_new_connection_transactions $where";
        $run = $this->db->query($sql);
        $result = $run->getResultArray();
        //echo $this->getLastQuery();
        return $result;
    }
    public function getTransactionDetailsForModeUpdate($where)
    {
        $sql = "select * from tbl_transaction where verify_status is NULL and status in (1,2) $where";
        $run = $this->db->query($sql);
        $result = $run->getFirstRow("array");
        //echo $this->getLastQuery();
        return $result;
    }


    public function getSumByMode($data)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('SUM(paid_amount) as total')
                ->where('transaction_date >=', $data['from_date'])
                ->where('transaction_date <=', $data['to_date'])
                ->where('payment_mode', $data['payment_mode'])
                ->whereIn('status', [1, 2])
                ->get();
            //echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
            return  $builder['total'];
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getSum($data)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('SUM(paid_amount) as total')
                ->where('transaction_date >=', $data['from_date'])
                ->where('transaction_date <=', $data['to_date'])
                ->whereIn('status', [1, 2])
                ->get();
            //echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
            return  $builder['total'];
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function gateCnncColl($frm_date, $to_date)
    {
        $newCnncColl = "SELECT SUM(Totalconnamount), count (Cnnctioncount) 
		FROM (SELECT sum(tb1.paid_amount) as Totalconnamount, count(tb2.id) 
		as Cnnctioncount FROM tbl_transaction tb1 
		INNER JOIN tbl_apply_water_connection tb2 ON tb1.related_id = tb2.id 
		WHERE tb1.transaction_date between '" . $frm_date . "' AND '" . $to_date . "' AND
		tb1.transaction_type='New Connection' OR tb1.transaction_type='Site Inspection' group by tb2.id) as tbl";
        $ql = $this->query($newCnncColl);
        //echo $this->db->getLastQuery();
        $resultnewCnncColl = $ql->getResultArray()[0];
        return $resultnewCnncColl;
    }

    public function gateconsumerColl($frm_date, $to_date)
    {
        $newCnncColl = "SELECT SUM(Totalconsumeramount), count (consumerCount) 
		FROM (SELECT sum(tb1.paid_amount) as Totalconsumeramount, count(tb2.id) 
		as consumerCount FROM tbl_transaction tb1 INNER JOIN tbl_consumer tb2 ON
		tb1.related_id = tb2.id 
		WHERE tb1.transaction_date between '" . $frm_date . "' AND '" . $to_date . "' AND
		tb1.transaction_type='Demand Collection' group by tb2.id ) AS tbl
		";
        $ql = $this->query($newCnncColl);
        //echo $this->db->getLastQuery();
        $resultnewCnncColl = $ql->getResultArray()[0];
        return $resultnewCnncColl;
    }

    public function get_all_transactions($water_conn_id)
    {
        $sql = "select * from tbl_transaction where md5(related_id::text)='" . $water_conn_id . "' and transaction_type in ('New Connection','Site Inspection','Penlaty Instalment') and status in (1, 2)";
        $run = $this->db->query($sql);
        //echo $this->getLastQuery();
        $result = $run->getResultArray();
        return $result;
    }


    public function getConsumerTransactions($consumer_id)
    {
        $sql = "select * from tbl_transaction where md5(related_id::text)='" . $consumer_id . "' and transaction_type ='Demand Collection' and status in (1, 2)";
        $run = $this->db->query($sql);
        // echo $this->getLastQuery();
        $result = $run->getResultArray();
        return $result;
    }

    public function getTransactionLevelPending($id)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('*')
                ->where('id', $id)
                ->where('status', 1)
                ->get();
            return $builder->getResultArray()[0];
        } catch (Exception $e) {
            echo $this->getMessage();
        }
    }

    public function getTransCountbyApplicationId($apply_connection_id)
    {

        $sql = "select count(id) as count from tbl_transaction where md5(related_id::text)='" . $apply_connection_id . "' and status in(1, 2) and transaction_type in ('New Connection','Site Inspection')";
        $run = $this->db->query($sql);
        $result = $run->getFirstRow("array");
        return $result['count'];
    }
    public function getTransCountbyConsumer($consumer_id)
    {

        $sql = "select count(id) as count from tbl_transaction where md5(related_id::text)='" . $consumer_id . "' and status in(1,2) and transaction_type='Demand Collection' ";
        $run = $this->db->query($sql);
        $result = $run->getFirstRow("array");
        return $result['count'];
    }
    public function getAllTransactionById($transaction_id)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('*')
                ->where('id', $transaction_id)
                ->where('status', 3)
                ->get();
            return $builder->getFirstRow('array');
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
            // print_var($data);die;
            return $data;
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
            //echo $this->db->getLastQuery();
            return $builder->getResultArray();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getDailyCollectionByTaxCollectorByOneMode($data)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('*')
                ->where('transaction_date>=', $data['from_date'])
                ->where('transaction_date<=', $data['to_date'])
                ->whereIn('ward_mstr_id', $data['wardPermission'])
                ->where('emp_details_id', $data['id'])
                ->where('payment_mode', $data['payment_mode'])
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
            // echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
            return  $builder['paid_amount'];
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    /*  public function getChequeDetailsByEmpId($id,$tran_date){
        try{
            $builder =$this->db->table($this->table)
                    ->select('*')
                    ->where('md5(emp_details_id::text)',$id)
                    ->where('status',2)
                    ->where('transaction_date',$tran_date)
                    ->where('verify_status',NULL)
                    ->whereIn('payment_mode',['CHEQUE','DD'])
                    ->get();
                  echo $this->db->getLastQuery();
            return $builder->getResultArray();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }*/

    public function getChequeDetailsByEmpId($id, $tran_date)
    {

        $sql = "select tbl_transaction.id as transaction_id,transaction_no,transaction_date,paid_amount as payable_amt,cheque_no,cheque_date,bank_name,branch_name from tbl_transaction left join tbl_cheque_details on tbl_cheque_details.transaction_id=tbl_transaction.id where transaction_date='$tran_date' 
          and md5(tbl_transaction.emp_details_id::text)='$id' and verify_status is NULL and payment_mode in('CHEQUE','DD')";

        $run = $this->query($sql);
        $result = $run->getResultArray();
        // echo $this->getLastQuery();
        return $result;
    }
    public function updateVerificationStatus($id, $transaction_id, $verified_on)
    {
        try {
            $builder = $this->db->table($this->table)
                ->where('id', $transaction_id)
                ->update([
                    'verify_status' => 1,
                    'verified_on' => $verified_on,
                    'verified_by' => $id
                ]);
            echo $this->db->getLastQuery();
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

            $builder = $this->db->table($this->table)
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
            //echo $this->db->getLastQuery();
            return $builder->getResultArray();
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
            //echo $this->db->getLastQuery();
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
                ->whereIn('status', [1, 2])
                ->get();
            // echo $this->db->getLastQuery();
            return $builder->getResultArray()[0] ?? array();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getCheckDtlByno($data)
    {
        try {
            $builder = $this->db->table('tbl_cheque_details')
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
            $builder = $this->db->table('tbl_cheque_details')
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
                ->whereIn('status', [1, 2])
                ->get();
            // echo $this->db->getLastQuery();
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
    public function updateWaterTransactionStatus($id)
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
            // echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
            return  $builder['totalamount'];
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
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
    public function getAllTotalBydate($from_date)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('COALESCE(SUM(paid_amount),0) as totalamount')
                ->where('transaction_date', $from_date)
                ->whereIn('status', [1, 2])
                ->get();
            //echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
            return  $builder['totalamount'];
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getDeactivatedTransactionDetails($transaction_id)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('*')
                ->where('id', $transaction_id)
                ->whereIn('status', [0, 3])
                ->get();
            //echo $this->db->getLastQuery();
            return $builder->getResultArray()[0];
        } catch (Exception $e) {
            echo $e->getMessage();
            print_var($transaction_id);
            die;
        }
    }
    public function getWaterBulkPrintData($data)
    {
        try {
            $builder = $this->db->table($this->table)
                ->select('*')
                ->where('transaction_date>=', $data['from_date'])
                ->where('transaction_date<=', $data['to_date'])
                ->whereIn('status', [1, 2])
                ->get();
            return $builder->getResultArray();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getTotalPaidAmountwithCountTrans($from_date, $to_date)
    {

        $sql = "select transaction_type,count(id) as count,coalesce(sum(paid_amount),0) as paid_amount 
		from tbl_transaction where transaction_date between '$from_date' and '$to_date'  and status in(1,2) group by transaction_type";
        $run = $this->db->query($sql);
        $result = $run->getResultArray();
        //  echo $this->db->getLastQuery();die;
        return $result;
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
            /*echo $this->db->getLastQuery();*/
            return $builder->getFirstRow("array");
        } catch (Exception $e) {
            echo $e->getMessage();
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
    public function getTotalNewCollection($data)
    {
        try {
            $builder = $this->db->table($this->table);
            $builder = $builder->select('COALESCE(SUM(paid_amount),0) as new,COALESCE(COUNT(DISTINCT related_id),0) as consumer,COALESCE(COUNT(id),0) as id');
            $builder = $builder->where('transaction_date >=', $data['from_date']);
            $builder = $builder->where('transaction_date <=', $data['to_date']);
            $builder = $builder->where('upper(transaction_type)', 'NEW CONNECTION');
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
    public function getTotalDemandCollection($data)
    {
        try {
            $builder = $this->db->table($this->table);
            $builder = $builder->select('COALESCE(SUM(paid_amount),0) as renewal,COALESCE(COUNT(DISTINCT related_id),0) as consumer,COALESCE(COUNT(id),0) as id');
            $builder = $builder->where('transaction_date >=', $data['from_date']);
            $builder = $builder->where('transaction_date <=', $data['to_date']);
            $builder = $builder->whereIn('upper(transaction_type)', ['DEMAND COLLECTION', 'SITE INSPECTION']);
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

    public function updateChequeDetails($data)
    {
        $sql = "update tbl_cheque_details set cheque_no='" . $data['cheque_no'] . "',cheque_date='" . $data['cheque_date'] . "',bank_name='" . $data['bank_name'] . "',branch_name='" . $data['branch_name'] . "' where id=" . $data['cheque_dtl_id'] . "";
        $run = $this->db->query($sql);
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
    public function updatePaymentMode($transaction_id, $payment_mode)
    {
        $sql = " update tbl_transaction set payment_mode='$payment_mode' where id=$transaction_id";
        $this->db->query($sql);
        //echo $this->getLastQuery();
    }


    public function getTransactionWithChequeDetails($where)
    {
        $sql = "select tbl_transaction.*,'Water' as tran_type,
                    tbl_cheque_details.id as cheque_dtl_id,cheque_no,cheque_date,bank_name,branch_name,
                    tbl_cheque_details.clear_bounce_date,tbl_cheque_details.remarks as clear_bounce_remarks,
                    view_emp_details.emp_name 
            from tbl_transaction 
            join tbl_cheque_details on tbl_cheque_details.transaction_id=tbl_transaction.id 
            left join view_emp_details on view_emp_details.id = tbl_transaction.emp_details_id
            $where";
        $run = $this->db->query($sql);
        $result = $run->getResultArray();
        //echo $this->getLastQuery();
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
        // echo $this->db->getLastQuery();die;
    }





    //get Water Apply collection details 
    public function water_collection_details_with_id($from_date, $to_date, $tax_collector_id)
    {
        $sql = "select COALESCE(SUM(paid_amount),0) as water_paid_amount,view_emp_details.emp_name as tax_collector,COALESCE(COUNT(tbl_transaction.id),0) as water_count
        from tbl_transaction 
		left join view_emp_details ON tbl_transaction.emp_details_id = view_emp_details.id 
		where tbl_transaction.transaction_type = 'New Connection' or tbl_transaction.transaction_type = 'Site Inspection' and 
		tbl_transaction.emp_details_id = $tax_collector_id
        and tbl_transaction.transaction_date >= '$from_date' and tbl_transaction.transaction_date <= '$to_date' 
        group by view_emp_details.emp_name,view_emp_details.id";
        //COALESCE
        $ql = $this->db->query($sql);
        $result = $ql->getResultArray();
        if (!empty($result)) {
            return $result;
        } else {
            return $result = 0;
        }
    }

    public function water_collection_details($from_date, $to_date)
    {
        $sql = "select COALESCE(SUM(paid_amount),0) as water_paid_amount,view_emp_details.emp_name as tax_collector,
                 COALESCE(COUNT(tbl_transaction.id),0) as water_count
                 from tbl_transaction 
				 left join view_emp_details ON tbl_transaction.emp_details_id = view_emp_details.id 
				 where tbl_transaction.transaction_type = 'New Connection' or tbl_transaction.transaction_type = 'Site Inspection' and 
				 tbl_transaction.transaction_date >= '$from_date' and 
                 tbl_transaction.transaction_date <= '$to_date'
                  group by view_emp_details.emp_name,view_emp_details.id";
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


    public function getnewTotalPaidAmountwithCountTransbyempid($from_date, $to_date, $tax_collector_id)
    {

        $sql = "select count(id) as newcount,coalesce(sum(paid_amount),0) as newpaid_amount ,emp_details_id
		from tbl_transaction 
		where transaction_date between '$from_date' and '$to_date'  
		and emp_details_id= '$tax_collector_id' and transaction_type='New Connection' or transaction_type='Site Inspection'
		and status in(1,2) group by emp_details_id";
        $run = $this->db->query($sql);
        $result = $run->getResultArray()[0];
        //echo $this->getLastQuery();
        return $result;
    }

    public function getdemandTotalPaidAmountwithCountTransbyempid($from_date, $to_date, $tax_collector_id)
    {

        $sql = "select count(id) as demandcount,coalesce(sum(paid_amount),0) as demandpaid_amount ,emp_details_id
		from tbl_transaction 
		where transaction_date between '$from_date' and '$to_date'  
		and emp_details_id= '$tax_collector_id' and transaction_type='Demand Collection'
		and status in(1,2) group by emp_details_id";
        $run = $this->db->query($sql);
        $result = $run->getResultArray()[0];
        //echo $this->getLastQuery();
        return $result;
    }



    public function insertdy_collection($data)
    {
        //print_r($data);
        $fromdate = $data['fromdate'];
        $sql = "INSERT INTO public.water_dashboard_daily_collection(fy,date, amount)
				SELECT 
				'2021-2022','" . $fromdate . "',sum(paid_amount)
			    FROM tbl_transaction
				where status=1 AND transaction_date='" . $fromdate . "'";
        $ql = $this->db->query($sql);
        //echo $this->db->getLastQuery();
        $result = $ql->getResultArray();
        return $result;
    }

    public function row_sql($sql)
    {
        $run = $this->db->query($sql);
        $result = $run->getResultArray();
        //echo $this->db->getLastQuery();
        return $result;
    }

    public function getLastTransectionId($related_id,$transaction_type='Demand Collection')
    {
        try{
            
            $id = $this->db->table($this->table)
                    ->select('id')
                    ->where('related_id',$related_id)                    
                    ->whereNotIn('status ',[0,3]) ;
            if($transaction_type!='Demand Collection')
            {
                $id= $id->where('transaction_type <>','Demand Collection');
            }
            else
            {
                $id= $id->where('transaction_type','Demand Collection');
            }
            $id = $id->orderBy('transaction_date','desc')
                    ->orderBy('id','desc')
                    ->get()
                    ->getFirstRow('array');
            //echo $this->db->getLastQuery();
            return $id['id']??null;  
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
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

    public function update_water_cheque($cheque_tbl_id,$cheuqe_no_to_update)
    {
         $sql="update tbl_cheque_details set cheque_no='".$cheuqe_no_to_update."' where id=".$cheque_tbl_id."";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();
        return $result;
        
    }

    public function getRequestTransactionList($app_id){
        $request_type = array_map(function($val){
            return$val["request_type"];
        },(new TblRequestType($this->db))->select("request_type")->get()->getResultArray());
        return self::select("*")
            ->where("related_id",$app_id)
            ->whereIn("status",[1,2])
            ->whereIN("transaction_type",$request_type)
            ->get()
            ->getResultArray();
    }

    
}
