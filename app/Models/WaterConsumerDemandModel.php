<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class WaterConsumerDemandModel extends Model
{

    protected $table = 'tbl_consumer_demand';

    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }


    public function onePointFivePercentPenalty($consumer_id, $where)
    {
        $sql = "with interest_penalty as
            (
            select coalesce(sum(amount),0) as amount,generation_date,((DATE_PART('year', '2021-03-01'::date) - 
            DATE_PART('year', generation_date::date)) * 12 + 
            (DATE_PART('month', '2021-03-01'::date) - DATE_PART('month', generation_date::date))) as month

            from tbl_consumer_demand where 
            ((DATE_PART('year', '2021-03-01'::date) - DATE_PART('year', generation_date::date)) * 12 + 
            (DATE_PART('month', '2021-03-01'::date) - DATE_PART('month', generation_date::date)))>2 
            and md5(consumer_id::text)='$consumer_id' and paid_status=0 and status=1 $where
            group by generation_date

            )
             select coalesce(sum((1.5*month*amount)/100)) as interestpenalty from interest_penalty
            ";

        $run = $this->db->query($sql);
        $result = $run->getFirstRow("array");
        $interestpenalty = round($result['interestpenalty'], 2);
        //echo $this->db->getLastQuery();
        return $interestpenalty;
    }

    public function onePointFivePercentPenalty2($consumer_id, $where)
    {
        $sql = "with interest_penalty as
            (
            select coalesce(sum(amount),0) as amount,generation_date,((DATE_PART('year', '2021-03-01'::date) - 
            DATE_PART('year', generation_date::date)) * 12 + 
            (DATE_PART('month', '2021-03-01'::date) - DATE_PART('month', generation_date::date))) as month

            from tbl_consumer_demand where 
            ((DATE_PART('year', '2021-03-01'::date) - DATE_PART('year', generation_date::date)) * 12 + 
            (DATE_PART('month', '2021-03-01'::date) - DATE_PART('month', generation_date::date)))>2 
            and consumer_id::text='$consumer_id' and paid_status=0 and status=1 $where
            group by generation_date

            )
             select coalesce(sum((1.5*month*amount)/100)) as interestpenalty from interest_penalty
            ";

        $run = $this->db->query($sql);
        $result = $run->getFirstRow("array");
        $interestpenalty = round($result['interestpenalty'], 2);
        // echo $this->db->getLastQuery();
        return $interestpenalty;
    }

    public function insertData(array $data)
    {

        $result = $this->db->table($this->table)
            ->insert($data);
        // echo $this->getLastQuery();
        $insert_id = $this->db->insertID();
        return $insert_id;
    }

    public function consumerDueDetails($consumer_id)
    {
        /*  $sql="select max(demand_upto) as demand_upto,concat(date_part('month', demand_upto),'/',date_part('year', demand_upto)) as month,date_part('month', demand_upto),date_part('year', demand_upto),sum(amount+penalty) 
            from tbl_consumer_demand where paid_status=0 and status=1 and md5(consumer_id::text)='".$consumer_id."' group by 
            date_part('month', demand_upto),date_part('year', demand_upto)";*/

        $sql = "select max(demand_upto) as demand_upto,concat(to_char(to_timestamp (date_part('month', demand_upto)::text, 'MM'), 'TMmon'),'/',date_part('year', demand_upto)) as demand_month,sum(amount) as current_amount,sum(penalty) as penalty
            from tbl_consumer_demand where paid_status=0 and status=1 and md5(consumer_id::text)='" . $consumer_id . "' group by
            date_part('month', demand_upto),date_part('year', demand_upto) 
            order by date_part('year', demand_upto) desc,date_part('month', demand_upto) desc";

        $run = $this->db->query($sql);
        $result = $run->getResultArray();
        // echo $this->getLastQuery();
        return $result;
    }
    public function consumerDueDetailsAll($consumer_id)
    {
        /*  $sql="select max(demand_upto) as demand_upto,concat(date_part('month', demand_upto),'/',date_part('year', demand_upto)) as month,date_part('month', demand_upto),date_part('year', demand_upto),sum(amount+penalty) 
            from tbl_consumer_demand where paid_status=0 and status=1 and md5(consumer_id::text)='".$consumer_id."' group by 
            date_part('month', demand_upto),date_part('year', demand_upto)";*/

        $sql = "select max(demand_upto) as demand_upto,concat(to_char(to_timestamp (date_part('month', demand_upto)::text, 'MM'), 'TMmon'),'/',date_part('year', demand_upto)) as demand_month,sum(amount) as current_amount,sum(penalty) as penalty
            from tbl_consumer_demand where paid_status=0 and status=1 and consumer_id=" . $consumer_id . " group by
            date_part('month', demand_upto),date_part('year', demand_upto) 
            order by date_part('year', demand_upto) desc,date_part('month', demand_upto) desc";

        $run = $this->db->query($sql);
        $result = $run->getResultArray();
        // echo $this->getLastQuery();
        return $result;
    }

    public function due_demand($consumer_id)
    {

        $sql = "select amount, penalty, balance_amount, demand_from, demand_upto, connection_type from tbl_consumer_demand where paid_status=0 and status=1 and md5(consumer_id::text)='" . $consumer_id . "' order by demand_from asc";
        $run = $this->db->query($sql);
        $result = $run->getResultArray();
        // echo $this->getLastQuery();
        return $result;
    }

    public function impose_penalty($consumer_id)
    {
        
        $sql = "select * from impose_penalty($consumer_id);";
        $run = $this->db->query($sql);
        $result = $run->getResultArray();
        $sql = "
                update tbl_consumer_demand set penalty=0.00, balance_amount = (balance_amount-new_penalty) 
                from 
                (
                    select  
                        news.consumer_id  ,news.demand_from ,news.demand_upto
                        ,news.id new_id, olds.id as old_id,
                        news.demand_id,
                        news.demand_no as new_no , olds.demand_no as old_no
                        ,news.amount as new_amount, olds.amount as old_amount,news.penalty as new_penalty, olds.penalty as old_penalty,
                        news.paid_status as new_paid_status ,olds.paid_status as old_paid_status
                    from (
                        select tbl_diff_demand_genrated.*,demand_no,demand_from,demand_upto,amount,penalty, paid_status
                        from tbl_diff_demand_genrated
                        join tbl_consumer_demand on tbl_consumer_demand.id = tbl_diff_demand_genrated.demand_id
                        where tbl_diff_demand_genrated.demand_type = 'New' and tbl_consumer_demand.paid_status =0  
                            and tbl_consumer_demand.status =1
                            and tbl_diff_demand_genrated.consumer_id = $consumer_id
                    ) news    
                    join (
                        select tbl_diff_demand_genrated.*,tbl_consumer_demand.demand_no,tbl_consumer_demand.demand_from ,
                            tbl_consumer_demand.demand_upto,tbl_consumer_demand.amount,tbl_consumer_demand.penalty,
                            tbl_consumer_demand.paid_status
                        from tbl_diff_demand_genrated
                        join tbl_consumer_demand on tbl_consumer_demand.id = tbl_diff_demand_genrated.demand_id
                        join tbl_consumer_collection on tbl_consumer_collection.demand_id = tbl_diff_demand_genrated.demand_id 
                            and tbl_consumer_collection.created_on::date<'2023-03-14'
                        where tbl_diff_demand_genrated.demand_type = 'Old'  and tbl_consumer_demand.status =1
                            and tbl_diff_demand_genrated.consumer_id = $consumer_id
                    )olds on olds.consumer_id = news.consumer_id 
                        and olds.demand_from = news.demand_from
                        and olds.demand_upto = news.demand_upto
                        and olds.demand_no = news.demand_no
                    where news.consumer_id = $consumer_id
                ) temps
                where tbl_consumer_demand.paid_status = 0 
                and tbl_consumer_demand.id = temps.demand_id and temps.old_penalty = 0 
                and temps.new_penalty>0
            ";
            $run = $this->db->query($sql)->getResultArray();
        // echo $this->getLastQuery();
        return $result;
    }

    public function getAmountPayable($consumer_id, $uptoDate,$demand_from=null)
    {
        $sql = "select string_agg(id::text,',') as demand_id, round(coalesce(sum(amount),0)) as amount, round(coalesce(sum(penalty),0)) as penalty, 
        round(coalesce(sum(balance_amount),0)) as balance_amount ,max(demand_upto) as demand_upto, min(demand_from) as demand_from
        from tbl_consumer_demand 
        where demand_from<='$uptoDate' and consumer_id=$consumer_id and status=1 and paid_status=0 ".($demand_from!=null?" and demand_from >='$demand_from'" :'');

        $run = $this->db->query($sql);
        //echo $this->db->getLastQuery();
        $result = $run->getFirstRow("array");
        return $result;
    }
    
    public function getDuebyMonth($consumer_id, $upto_month)
    {
        $sql = "select coalesce(sum(amount), 0) as amount from tbl_consumer_demand where paid_status=0 and status=1 and md5(consumer_id::text)='" . $consumer_id . "' and generation_date<='" . $upto_month . "'";
        $run = $this->db->query($sql);
        $result = $run->getFirstRow("array");
        //echo $this->getLastQuery();exit;
        return $result;
    }

    public function getTotalDues($consumer_id)
    {
        $sql = "select coalesce(sum(amount), 0) as amount from tbl_consumer_demand where paid_status=0 and status=1 and md5(consumer_id::text)='" . $consumer_id . "'";
        $run = $this->db->query($sql);
        $result = $run->getFirstRow("array");
        //echo $this->getLastQuery();
        return $result;
    }

    public function getLastDemand($consumer_id)
    {
        $sql = "select * from tbl_consumer_demand where status=1 and md5(consumer_id::text)='$consumer_id' order by demand_upto desc ";
        $run = $this->db->query($sql);
        $result = $run->getFirstRow("array");
        //echo $this->db->getLastQuery();
        return $result;
    }
    public function getLastDemand2($consumer_id)
    {
        $sql = "select * from tbl_consumer_demand where status=1 and consumer_id::text='$consumer_id' order by demand_upto desc ";
        $run = $this->db->query($sql);
        $result = $run->getFirstRow("array");
        //echo $this->db->getLastQuery();
        return $result;
    }

    public function getDueFrom($consumer_id)
    {
        $sql = "select min(demand_from) as demand_from from tbl_consumer_demand where status=1 and paid_status=0 and md5(consumer_id::text)='$consumer_id' ";
        $run = $this->db->query($sql);
        $result = $run->getFirstRow("array");
        // echo $this->getLastQuery();
        return $result['demand_from'];
    }
    public function getDueFrom2($consumer_id)
    {
        $sql = "select min(demand_from) as demand_from from tbl_consumer_demand where status=1 and paid_status=0 and consumer_id=$consumer_id ";
        $run = $this->db->query($sql);
        $result = $run->getFirstRow("array");
        // echo $this->getLastQuery();
        return $result['demand_from'];
    }

    public function getMaxFrom($consumer_id)
    {
        $sql = "select min(demand_from) as demand_from, max(generation_date) as demand_upto from tbl_consumer_demand where status=1 and paid_status=0 and md5(consumer_id::text)='$consumer_id' ";
        $run = $this->db->query($sql);
        $result = $run->getFirstRow("array");
        //echo $this->getLastQuery();
        return $result;
    }

    public function getDueFromCurrentYear($consumer_id, $start_year)
    {
        $sql = "select min(demand_from) as demand_from from tbl_consumer_demand where status=1 and paid_status=0 and md5(consumer_id::text)='$consumer_id' and demand_from>='$start_year' ";
        $run = $this->db->query($sql);
        $result = $run->getFirstRow("array");
        // echo $this->getLastQuery();
        return $result['demand_from'];
    }



    public function getDueUpto($consumer_id)
    {
        $sql = "select max(demand_upto) as demand_upto from tbl_consumer_demand where status=1 and paid_status=0 and md5(consumer_id::text)='$consumer_id' ";
        $run = $this->db->query($sql);
        $result = $run->getFirstRow("array");
        //echo $this->getLastQuery();
        return $result['demand_upto'];
    }
    public function getDueUpto2($consumer_id)
    {
        $sql = "select max(demand_upto) as demand_upto from tbl_consumer_demand where status=1 and paid_status=0 and consumer_id::text='$consumer_id' ";
        $run = $this->db->query($sql);
        $result = $run->getFirstRow("array");
        //echo $this->getLastQuery();
        return $result['demand_upto'];
    }
    public function getMaxDemandGenerated($consumer_id)
    {
        $sql = "select max(demand_upto) as demand_upto from tbl_consumer_demand where status=1 and md5(consumer_id::text)='$consumer_id' ";
        $run = $this->db->query($sql);
        $result = $run->getFirstRow("array");
        //echo $this->getLastQuery();
        return $result['demand_upto'];
    }
    public function getMaxDemandGeneratedDate($consumer_id)
    {
        $sql = "select max(demand_upto) as demand_upto from tbl_consumer_demand where status=1 and consumer_id::text='$consumer_id' ";
        $run = $this->db->query($sql);
        $result = $run->getFirstRow("array");
        //echo $this->getLastQuery();
        return $result['demand_upto'];
    }

    public function update_demand_status_old($consumer_id, $paid_upto)
    {

        $sql = "update tbl_consumer_demand set paid_status=1 where consumer_id=$consumer_id and paid_status=0 and status=1 and generation_date<='$paid_upto'";

        $run = $this->db->query($sql);

        return $run;
    }
    public function update_demand_status($consumer_id, $demand_id)
    {

        $sql = "update tbl_consumer_demand set paid_status=1 where consumer_id=$consumer_id and paid_status=0 and status=1 and id in($demand_id)";

        $run = $this->db->query($sql);
        //echo $this->getLastQuery();exit;
        return $run;
    }

    public function update_demand_statusCollection($consumer_id, $upto_month)
    {
        $sql = "update tbl_consumer_demand set paid_status=1 where consumer_id=$consumer_id and paid_status=0 and status=1 and generation_date<='" . $upto_month . "'";
        $run = $this->db->query($sql);
        //echo $this->getLastQuery();exit;
        return $run;
    }

    public function date_diff_water($from_date, $to_date)
    {

        $datequerysql = "select age('" . $to_date . "', '" . $from_date . "') as whole_diff,
                   date_part('year',age('" . $to_date . "', '" . $from_date . "')) as year_diff,
                   date_part('month',age('" . $to_date . "', '" . $from_date . "')) as month_diff,
                   date_part('day',age('" . $to_date . "', '" . $from_date . "')) as day_diff";

        $run = $this->db->query($datequerysql);
        // echo $this->db->getLastQuery();
        $result = $run->getFirstRow("array");
        return $result;
    }
    public function getDueCount($consumer_id)
    {
        $sql = "select count(*) as count from tbl_consumer_demand where paid_status=0 and md5(consumer_id::text)='" . $consumer_id . "'";
        $run = $this->db->query($sql);
        //echo $this->getLastQuery();
        $result = $run->getFirstRow("array");
        return $result['count'];
    }
    public function getDueCount2($consumer_id)
    {
        $sql = "select count(*) as count from tbl_consumer_demand where paid_status=0 and consumer_id::text='".$consumer_id."'";
        $run = $this->db->query($sql);
        //echo $this->getLastQuery();
        $result = $run->getFirstRow("array");
        return $result['count'];
    }

    public function getAmountPayablePreviousYear($consumer_id, $demand_upto, $start_year, $end_year)
    {

        /*   $sql="with due_amount as (select max(demand_upto) as demand_upto,concat(to_char(to_timestamp (date_part('month', demand_upto)::text, 'MM'), 'TMmon'),'/',date_part('year', demand_upto)) as demand_month,sum(amount) as current_amount,sum(penalty) as penalty
            from tbl_consumer_demand where paid_status=0 and status=1 and md5(consumer_id::text)='".$consumer_id."' group by
            date_part('month', demand_upto),date_part('year', demand_upto) 
            order by date_part('year', demand_upto) desc) 

            select sum(current_amount)+sum(penalty) as payable_amount from due_amount where demand_upto<='".$demand_upto."'
            ";
   


            $sql="with a as(select max(demand_upto) as demand_upto,string_agg(id::text,',') as demand_id,
            concat(to_char(to_timestamp (date_part('month', demand_upto)::text, 'MM'), 'TMmon'),'/',
            date_part('year', demand_upto)) as demand_month,sum(amount) as current_amount,sum(penalty) as penalty
            from tbl_consumer_demand where paid_status=0 and status=1 and 
            md5(consumer_id::text)='c74d97b01eae257e44aa9d5bade97baf' group by
            date_part('month', demand_upto),date_part('year', demand_upto) 
            order by date_part('year', demand_upto) desc)
            
            select string_agg(demand_id,','),sum(current_amount) as current_amount from a where
            demand_upto<='$demand_upto' or 
            (demand_upto>='$start_year' and demand_upto<='$end_year') "; */

        $sql = "select string_agg(id::text,',') as demand_id,coalesce(sum(amount),0) as payable_amount from tbl_consumer_demand where ((demand_upto>='$start_year' and demand_upto<='$end_year') or demand_upto<='$demand_upto') and md5(consumer_id::text)='" . $consumer_id . "' and status=1 and paid_status=0";

        $run = $this->db->query($sql);
        // echo $this->getLastQuery();
        $result = $run->getFirstRow("array");
        return $result;
    }
    public function getAmountPayableCurrentYear($consumer_id, $demand_upto, $start_year)
    {

        /*  $sql="with a as(select max(demand_upto) as demand_upto,string_agg(id::text,',') as demand_id,
            concat(to_char(to_timestamp (date_part('month', demand_upto)::text, 'MM'), 'TMmon'),'/',
            date_part('year', demand_upto)) as demand_month,sum(amount) as current_amount,sum(penalty) as penalty
            from tbl_consumer_demand where paid_status=0 and status=1 and 
            md5(consumer_id::text)='c74d97b01eae257e44aa9d5bade97baf' group by
            date_part('month', demand_upto),date_part('year', demand_upto) 
            order by date_part('year', demand_upto) desc)
            
            select string_agg(demand_id,','),sum(current_amount) as current_amount from a where
            (demand_upto>='$start_year' and demand_upto<='$demand_upto') ";*/

        $sql = "select string_agg(id::text,',') as demand_id,coalesce(sum(amount),0) as payable_amount from tbl_consumer_demand where (demand_upto>='$start_year' and demand_upto<='$demand_upto') and md5(consumer_id::text)='" . $consumer_id . "' and status=1 and paid_status=0";


        $run = $this->db->query($sql);
        // echo $this->getLastQuery();
        $result = $run->getFirstRow("array");
        return $result;
    }

    public function getArrearDues($consumer_id, $start_year)
    {
        $sql = "select coalesce(sum(amount),0) as arr_due_amt from tbl_consumer_demand where demand_upto<'$start_year' and md5(consumer_id::text)='" . $consumer_id . "' and status=1 and paid_status=0";


        $run = $this->db->query($sql);
        //echo $this->getLastQuery();
        $result = $run->getFirstRow("array");
        return $result['arr_due_amt'];
    }
    public function getArrearDues2($consumer_id, $start_year)
    {
        $sql = "select coalesce(sum(amount),0) as arr_due_amt from tbl_consumer_demand where demand_upto<'$start_year' and consumer_id::text='" . $consumer_id . "' and status=1 and paid_status=0";
        $run = $this->db->query($sql);
        //echo $this->getLastQuery();
        $result = $run->getFirstRow("array");
        return $result['arr_due_amt'];
    }
    public function getCurrentYearDues($consumer_id, $start_year, $end_year)
    {
        $sql = "select coalesce(sum(amount),0) as curr_due_amt from tbl_consumer_demand where (demand_upto>='$start_year' and demand_upto<='$end_year') and md5(consumer_id::text)='" . $consumer_id . "' and status=1 and paid_status=0";


        $run = $this->db->query($sql);
        //echo $this->getLastQuery();
        $result = $run->getFirstRow("array");
        return $result['curr_due_amt'];
    }
    public function getCurrentYearDues2($consumer_id, $start_year, $end_year)
    {
        $sql = "select coalesce(sum(amount),0) as curr_due_amt from tbl_consumer_demand where (demand_upto>='$start_year' and demand_upto<='$end_year') and consumer_id::text='".$consumer_id."' and status=1 and paid_status=0";


        $run = $this->db->query($sql);
        //echo $this->getLastQuery();
        $result = $run->getFirstRow("array");
        return $result['curr_due_amt'];
    }

    public function getDueAmount($consumer_id)
    {
        $sql = "select coalesce(sum(amount),0) as due_amount from tbl_consumer_demand where consumer_id='" . $consumer_id . "' and paid_status=0";
        $run = $this->db->query($sql);
        $result = $run->getFirstRow("array");
        return $result;
    }

    public function getDemandFrom($demand_id)
    {
        $sql = "select demand_from from tbl_consumer_demand where id='" . $demand_id . "'";
        $run = $this->db->query($sql);
        $result = $run->getFirstRow("array");
        return $result['demand_from'];
    }
    public function getDemandUpto($demand_id)
    {
        $sql = "select demand_upto from tbl_consumer_demand where id='" . $demand_id . "'";
        $run = $this->db->query($sql);
        $result = $run->getFirstRow("array");
        return $result['demand_upto'];
    }


    public function gatedemand()
    {
        $sql = "select sum(amount) as totaldemand from tbl_consumer_demand where status=1";
        $run = $this->db->query($sql);
        $result = $run->getFirstRow("array");
        return $result['totaldemand'];
    }
    public function updateDemandNotPaid($demand_id)
    {
        $sql = "update tbl_consumer_demand set paid_status=0 where id in($demand_id)";
        $run = $this->db->query($sql);
        //echo $this->getLastQuery();
    }
    public function updateDemandPaid($demand_id)
    {
        $sql = "update tbl_consumer_demand set paid_status=1 where id in($demand_id)";
        $run = $this->db->query($sql);
        //echo $this->getLastQuery();
    }
    public function privDemantfromDate($consumer_id, $uptoDate)
    {
        $sql = "select string_agg(id::text,',') as demand_id, coalesce(sum(amount), 0) as payable_amount from tbl_consumer_demand where  demand_upto<='$uptoDate' and md5(consumer_id::text)='" . $consumer_id . "' and status=1 and paid_status=0";

        $run = $this->db->query($sql);
        //echo $this->db->getLastQuery();
        $result = $run->getFirstRow("array");
        return $result;
    }
    public function getTotalAmountByCidTid($consumer_id,$tax_id=null)
    {
        try{
            $this->impose_penalty($consumer_id);
            $sql = " select coalesce(sum(amount),0) as amount, coalesce(sum(penalty),0) as penalty, min(demand_from) as demand_from,
                        max(demand_upto) as demand_upto	
                    from tbl_consumer_demand 
                    where consumer_id = $consumer_id".( $tax_id!=null ?"and consumer_tax_id =$tax_id":"")."  and paid_status =0 and status = 1 ";
            $result = $this->db->query($sql)
                                ->getFirstRow('array');
            return $result;
        }
        catch(Exception $e)
        {
            echo $e;
        }
    }
    public function getTotalAmountByCid($consumer_id)
    {
        try{
            $sql = " select coalesce(sum(amount),0) as amount, coalesce(sum(penalty),0) as penalty, min(demand_from) as demand_from,
                        max(demand_upto) as demand_upto	
                    from tbl_consumer_demand 
                    where consumer_id = $consumer_id  and paid_status =0 and status = 1 ";
            $result = $this->db->query($sql)
                                ->getFirstRow('array');
            return $result;
        }
        catch(Exception $e)
        {
            echo $e;
        }
    }

    public function demand_summary($consumer_id)
    {
        try{
            $sql = " select max(demand_upto) as demand_upto, min(demand_from) as demand_from ,
                        sum(amount) as amount, sum(penalty) as penalty, sum(balance_amount) as balance_amount
                    from tbl_consumer_demand 
                    where status = 1 and paid_status = 0 and consumer_id =  $consumer_id ";
            $result = $this->db->query($sql)
                                ->getFirstRow('array');
            return $result;
        }
        catch(Exception $e)
        {
            echo $e;
        }
    }

    public function get_fist_paid_demand($consumer_id)
    {
        try{
            $sql = " select min(demand_from) as demand_from 
                    from tbl_consumer_demand 
                    where status = 1 and paid_status = 1 and consumer_id =  $consumer_id ";
            $result = $this->db->query($sql)
                                ->getFirstRow('array');
            // echo $this->db->getLastQuery();
            return $result;
        }
        catch(Exception $e)
        {
            echo $e;
        }
    }
    public function update_demand_status_connection_date($consumer_id,$where=array())
    {
        try{
           
            $data = $this->db->table('tbl_consumer_demand')
                         ->where(['consumer_id'=>$consumer_id,'paid_status'=>0,'status'=>1]);
                         if(!empty($where))
                         {
                            $data=$data->where($where);
                         }
                         $data=$data->update(['status'=>0]);
                // echo $this->db->getLastQuery();
            return $data; 
        }
        catch (Exception $e)
        {
            echo $e;
        }
               
                         
    }
    public function get_unpaid_demand_ids($consumer_id,$where=array())
    {
        try{
           
            $data = $this->db->table('tbl_consumer_demand')
                        ->select("string_agg(id::text,', ') as ids ")
                         ->where('consumer_id',$consumer_id)
                         ->where('paid_status',0)
                         ->where('status',1);
                         if(!empty($where))
                         {
                            $data=$data->where($where);
                         }
                    $data=$data->get()
                         ->getFirstRow('array');
                // echo $this->db->getLastQuery();
            return $data; 
        }
        catch (Exception $e)
        {
            echo $e;
        }
    }

    public function get_last_paid_demand($consumer_id)
    {
        try{
           
            $data = $this->db->table('tbl_consumer_demand')
                        ->select("*")
                         ->where('consumer_id',$consumer_id)
                         ->where('paid_status',1)
                         ->where('status',1)
                         ->orderBy('id','desc')
                         ->get()
                         ->getFirstRow('array');
                // echo $this->db->getLastQuery();
        return $data; 
        }
        catch (Exception $e)
        {
            echo $e;
        }
    }

}
