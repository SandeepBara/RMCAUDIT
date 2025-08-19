<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

use function PHPSTORM_META\type;

class WaterReportModel extends Model
{
    
    
    
	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}
    
    
    public function getApplicationDetails($where)
    {
        $sql="select id,ward_no,application_no,applicant_name,mobile_no,connection_type,category,apply_date from view_water_application_details $where ";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        // echo $this->getLastQuery();
        return $result;
    }
    public function approvedListReport($where)
    {
        $sql="select apply_connection_id,ward_no,application_no,applicant_name,mobile_no,connection_type,category,apply_date from view_approved_application_details where $where";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        return $result;

    }

    public function rejectedListReport($where)
    {
        $sql="select apply_connection_id,ward_no,application_no,applicant_name,mobile_no,connection_type,category,apply_date from view_rejected_application_details where $where";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        return $result;

    }

    public function backtocitizenListReport($where)
    {
        $sql="select apply_connection_id,ward_no,application_no,applicant_name,mobile_no,connection_type,category,apply_date,
                    remarks,forward_date,view_user_type_mstr.user_type  
                from view_backtocitizen_application_details 
                join view_user_type_mstr on view_user_type_mstr.id = view_backtocitizen_application_details.sender_user_type_id
                where $where";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        return $result;

    }

    public function levelPendingUserWiseCount($where,$receiver_user_type_id,$permitted_ward)
    {
        $sql="select count(view_water_application_details.id) as count from view_water_application_details join (select apply_connection_id,created_on from tbl_level_pending where verification_status=0 and receiver_user_type_id=$receiver_user_type_id group by apply_connection_id,created_on) as level_pending on 
            level_pending.apply_connection_id=view_water_application_details.id
            where $where and ward_id in($permitted_ward)";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        //echo $this->getLastQuery();
        return $result['count'];
        
    }
    
    public function levelPendingUserWiseList($where,$receiver_user_type_id,$permitted_ward)
    {
        $sql="select id,ward_no,application_no,apply_date,applicant_name,mobile_no,category,connection_type from view_water_application_details join (select apply_connection_id,created_on from tbl_level_pending where verification_status=0 and receiver_user_type_id=$receiver_user_type_id group by apply_connection_id,created_on) as level_pending on 
            level_pending.apply_connection_id=view_water_application_details.id
            where $where and ward_id in($permitted_ward)";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();
        return $result;
        
    }

    public function wardWiseCollection($where, $joinType=null)
    {
        
        $sql="select view_ward_mstr.ward_no,count(tbl_transaction.id) as count_consumer,coalesce(sum(paid_amount),0) as total_collection
             from tbl_transaction 
             $joinType join view_ward_mstr on view_ward_mstr.id=tbl_transaction.ward_mstr_id  and transaction_type='Demand Collection' 
             where $where
             group by ward_no order by (substring(ward_no, '^[0-9]+'))::int,ward_no";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        return $result;
        
    }
    

    public function getApplicationFormDetail($where,$sql=null)
    {
        // it is use in dashbord as well as another plase
        if($sql==null)
        {
            $sql="with application_form_detail as (
                
                        select case when connection_type_id=1 then count(id) end as new_connection,
                                        case when connection_type_id=2 then count(id) end as regularization,
                                        case when doc_status=0 or payment_status=0 then count(id) end as jsk_pending
                                        from tbl_apply_water_connection where status in(1,2) $where
                    group by connection_type_id,doc_status,payment_status
        
                    )
                    
                    select coalesce(sum(new_connection),0) as new_connection,coalesce(sum(regularization),0) as regularization,coalesce(sum(jsk_pending),0) as jsk_pending from application_form_detail";
        }
        else
            $sql = $sql.' '.$where;
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        
        return $result;             
    }


    public function levelPendingFormDetail($where)
    {
        $sql="
                WITH level_pending_form AS 
                (
                    SELECT CASE WHEN tbl_level_pending.verification_status=0 THEN 
                    count(DISTINCT tbl_level_pending.apply_connection_id) END AS level_pending,
                    CASE WHEN tbl_level_pending.verification_status = 4 THEN 
                    count(DISTINCT tbl_level_pending.apply_connection_id) END AS rejected,
                    CASE WHEN tbl_level_pending.verification_status = 2 THEN 
                    count(DISTINCT tbl_level_pending.apply_connection_id) END AS back_to_citizen,
                    CASE WHEN tbl_level_pending.receiver_user_type_id = 16 AND tbl_level_pending.verification_status = 1 
                    THEN count(DISTINCT tbl_level_pending.apply_connection_id) END AS approved

                    FROM (SELECT topnum.apply_connection_id,topnum.verification_status,topnum.receiver_user_type_id
                    FROM ( SELECT m.id,m.apply_connection_id,m.receiver_user_type_id,m.verification_status,
                    row_number() OVER (PARTITION BY m.apply_connection_id ORDER BY m.id DESC) AS rownum
                    FROM tbl_level_pending m 
                    join tbl_apply_water_connection on tbl_apply_water_connection.id=m.apply_connection_id
                    where m.status=1 $where) topnum WHERE topnum.rownum = 1) tbl_level_pending
                    GROUP BY tbl_level_pending.receiver_user_type_id, tbl_level_pending.verification_status
                 )
                 SELECT
                    coalesce(sum(level_pending_form.level_pending),0) AS level_pending,
                    coalesce(sum(level_pending_form.rejected),0) AS rejected,
                    coalesce(sum(level_pending_form.back_to_citizen),0) AS back_to_citizen,
                    coalesce(sum(level_pending_form.approved),0) AS approved
                   FROM level_pending_form

                    ";

         $run=$this->db->query($sql);
         $result=$run->getFirstRow("array");
         //echo $this->getLastQuery();

         return $result;             

    }


    public function LevelPendingListGrpByUserType()
    {
        $sql="select distinct(count(tbl_level_pending.id)) as total, receiver_user_type_id, user_type
                from tbl_level_pending 
                right join (
                        
                        select id, user_type
                        from dblink('host=".getenv('db.pgsql.hname')." user=".getenv('db.pgsql.uname')." password=".getenv('db.pgsql.pass')." dbname=db_system port=".getenv('db.pgsql.port')."'::text, 
                        'select id, user_type from tbl_user_type_mstr where user_for=''ULB'''::text) 
                        consumer_collection(id bigint, user_type text)
                    ) tbl_user_type_mstr on tbl_user_type_mstr.id=tbl_level_pending.receiver_user_type_id
                where verification_status=0 and status=1
                group by receiver_user_type_id, user_type
                order by receiver_user_type_id";

        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();
        return $result;

    }

    // how many demand generated on a particular date
    public function countDemandBilled($where)
    {
        $sql="select coalesce(count(distinct(apply_connection_id)),0) as billed from tbl_consumer_demand join tbl_consumer on tbl_consumer.id=tbl_consumer_demand.consumer_id where $where ";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        return $result['billed'];

    }

    public function countApproved($where)
    {
        $sql="select coalesce(count(distinct(apply_connection_id)),0) as approved from tbl_level_pending join tbl_apply_water_connection on tbl_apply_water_connection.id=tbl_level_pending.apply_connection_id where verification_status=1 and receiver_user_type_id=16 $where ";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        return $result['approved'];
        
    }

    public function countPayment($where)
    {
        $sql="select coalesce(count(distinct(related_id)),0) as payment from tbl_transaction join tbl_consumer on tbl_consumer.id=tbl_transaction.related_id where transaction_type='Demand Collection' $where";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        return $result['payment'];
        
    }

    public function approvedList($where)
    {
        $sql="select view_consumer_owner_details.id,view_consumer_owner_details.ward_no,view_consumer_owner_details.consumer_no,
        view_consumer_owner_details.applicant_name,view_consumer_owner_details.mobile_no from view_water_application_details join (select apply_connection_id from tbl_level_pending where receiver_user_type_id=16 and verification_status=1 $where group by apply_connection_id) as level_pending on level_pending.apply_connection_id=view_water_application_details.id join view_consumer_owner_details on view_consumer_owner_details.apply_connection_id=view_water_application_details.id";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        return $result;
        
    }

    public function billedList($where)
    {
        $sql="select view_consumer_owner_details.id,ward_no,consumer_no from view_consumer_owner_details join (select consumer_id from tbl_consumer_demand where $where group by consumer_id ) as demand on demand.consumer_id=view_consumer_owner_details.id";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        return $result;
        
    }

    public function paymentList($where)
    {
        $sql="select view_consumer_owner_details.id,view_consumer_owner_details.ward_no,view_consumer_owner_details.consumer_no,
        view_consumer_owner_details.applicant_name,view_consumer_owner_details.mobile_no from  view_consumer_owner_details join (select related_id from tbl_transaction where transaction_type='Demand Collection' $where group by related_id) as trans on view_consumer_owner_details.id=trans.related_id ";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        return $result;
        
    }

    public function collectionPaymentModeWise($where)
    {

        $sql="select upper(payment_mode) as payment_mode,count(id) as count_trans,count(distinct(related_id)) as count_consumer,sum(paid_amount) as paid_amount from tbl_transaction where status in (1,2) and transaction_type='Demand Collection' $where group by payment_mode ";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        // echo $this->getLastQuery();
        return $result;
    }
    



    public function collectionConnectionTypeWise($where)
    {

        $sql="select upper(tbl_connection_type_mstr.connection_type) as connection_type, count(tbl_transaction.id) as count_trans, count(distinct(related_id)) as count_consumer,
        sum(paid_amount) as paid_amount 
        from tbl_transaction 
        join tbl_apply_water_connection on tbl_apply_water_connection.id=tbl_transaction.related_id
        join tbl_connection_type_mstr on tbl_connection_type_mstr.id=tbl_apply_water_connection.connection_type_id
        where tbl_transaction.status in (1,2) and transaction_type='New Connection' $where 
        group by tbl_connection_type_mstr.connection_type";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        // echo $this->getLastQuery();
        return $result;
    }

    public function bouncedCollectionPaymentModeWise($where)
    {

        $sql="select upper(payment_mode) as payment_mode,count(id) as count_trans,count(distinct(related_id)) as count_consumer,sum(paid_amount) as paid_amount from tbl_transaction where status=3 and transaction_type='Demand Collection' $where group by payment_mode ";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        return $result;
    }

    public function connectionTypeWisePayment($where) // Metered or Non-Metered Payment
    {

        $sql="select upper(connection_type) as connection_type, sum(amount) as paid_amount, count(distinct(tbl_consumer_collection.consumer_id)) as count from tbl_consumer_collection where status=1 $where group by connection_type ";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        // echo $this->getLastQuery();
        return $result;
    }

    public function consumerTotalAdvance($where) // Metered or Non-Metered Payment
    {

        $sql="select sum(advance_amount) as advance_amt,count(tbl_consumer_advance_dtls.id) as count from tbl_consumer_advance_dtls join tbl_consumer on tbl_consumer.id=tbl_consumer_advance_dtls.consumer_id where active_status=1 $where";
        //print_var($sql);die;
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        //echo $this->getLastQuery();
        return $result;
    }

    public function propertyTypeWiseCollection($where) // Metered or Non-Metered Payment
    {
        
       $sql="select property_type_id,sum(paid_amount) as paid_amount,coalesce((count(tbl_transaction.id)),0) as count from tbl_transaction join tbl_consumer on tbl_consumer.id=tbl_transaction.related_id where transaction_type='Demand Collection'  $where group by tbl_consumer.property_type_id
            ";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();
        return $result;
        
    }
    public function underSiteInspection($where)
    {

        $sql="select * from view_under_site_inspection $where";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();
        return $result;

    }

    public function applicationLevelPendingStatusDetail($where)
    {

        $sql="select * from view_application_level_pending_details $where";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();
        return $result;

    }

    public function consumerSummary($where)
    {
        $sql="select * from view_consumer_summary $where";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        //echo $this->getLastQuery();
        return $result;
    }

    public function consumerWiseDCB($whereClause=NULL, $join=NULL)
    {
        $sql="select * from view_consumer_wise_dcb $join $whereClause ";
        //$sql="select * from consumer_wise_dcb $join $whereClause ";
        $run=$this->db->query($sql);
        $result=$run->getResultArray(); 
        //print_var($sql);
        //echo $this->db->getLastQuery();
        return $result;
    }
    public function consumerWiseDCB2($sql,$show_data=null)
    {
        if(is_null($show_data))
        {
                $show_data = limitInPagination();
        }
        $uri_string = uri_string();
        if(isset($_GET['page']))
        {
            $page = intval($_GET['page'])-1;
            if($page<0) $page = 0;
        } 
        else 
        {
            $page = 0;
        }
        $start_page = $page*$show_data;

        try
        {
            //->limit( $show_data,$start_page)
            $sql2=$sql." offset $start_page limit $show_data ";            
            $run= $this->db->query($sql2);
            //print_var($this->db->getlastQuery());
            $data['result']=$run->getResultArray();
            $count=$this->db->query($sql)->getResultArray();
            //echo($this->db->getlastQuery());
            $data['count']=!empty($count)?count($count):0; 
            $data['offset']=$start_page;
            return $data;
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
        
    }

    public function getTotalRecords($totalRecordsSql,$getQuery=false)
    {
        try
        {
            $builder = $this->db->query("SELECT count(*) as allcount from (".$totalRecordsSql.") count ");
            $totalRecords = $builder->getResultArray()[0]['allcount'];
            if ($getQuery===true)
            {
                echo $this->db->getLastQuery();
            }
            return $totalRecords;
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }

    public function getTotalRecordwithFilter($totalRecordwithFilterSql, $getQuery = false, $totalRecordsSql0="")
    {
        try
        {
            $builder = $this->db->query($totalRecordsSql0."SELECT count(*) as allcount ".$totalRecordwithFilterSql);

            $totalRecordwithFilter = $builder->getResultArray()[0]['allcount'];
            if ($getQuery===true)
            {
                echo $this->db->getLastQuery();
            }
            return $totalRecordwithFilter;
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }

    public function getRecords($fetchSql, $getQuery = false)
    {
        try
        {
            $builder = $this->db->query($fetchSql);
            $records = $builder->getResultArray();
            if ($getQuery===true)
            {
                echo $this->db->getLastQuery();
            }
            return $records;
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }

    public function wardWiseDCB($last_date,$curr_last_date,$join)
    {
        $session=session();
        $ulb_details=$session->get('ulb_dtl');
        //print_r($ulb_details);
        $this->ulb_id=$ulb_details['ulb_mstr_id'];
       /* $sql="SELECT view_ward_mstr.id,view_ward_mstr.ward_no,demand.arrear_demand,demand.current_demand,
                coll.arrear_collection,coll.curr_collection
              FROM view_ward_mstr
              LEFT JOIN ( SELECT tbl_consumer_demand.ward_mstr_id,
                    sum( 
                        CASE
                            WHEN tbl_consumer_demand.demand_upto <= '$last_date'::date THEN tbl_consumer_demand.amount
                            ELSE NULL::numeric
                        END) AS arrear_demand,
                    sum(
                        CASE
                            WHEN tbl_consumer_demand.demand_upto > '$last_date'::date AND tbl_consumer_demand.demand_upto <= '$curr_last_date'::date THEN tbl_consumer_demand.amount
                            ELSE NULL::numeric
                        END) AS current_demand
                   FROM tbl_consumer_demand
             GROUP BY tbl_consumer_demand.ward_mstr_id) demand ON demand.ward_mstr_id = view_ward_mstr.id
             
             LEFT JOIN (SELECT tbl_consumer_collection.ward_mstr_id,
            sum(
                CASE
                    WHEN tbl_consumer_demand.demand_upto <= '$last_date'::date THEN tbl_consumer_collection.amount
                    ELSE NULL::numeric
                END) AS arrear_collection,
            sum(
                CASE
                    WHEN tbl_consumer_demand.demand_upto > '$last_date'::date AND tbl_consumer_demand.demand_upto <= '$curr_last_date'::date THEN tbl_consumer_collection.amount
                    ELSE NULL::numeric
                END) AS curr_collection
           FROM tbl_consumer_collection
             JOIN tbl_consumer_demand ON tbl_consumer_demand.id = tbl_consumer_collection.demand_id
          GROUP BY tbl_consumer_collection.ward_mstr_id) coll ON coll.ward_mstr_id = view_ward_mstr.id;
        ";
        */
        
        
        $sql=" SELECT view_ward_mstr.id,view_ward_mstr.ward_no,
                coalesce(demand.total_consumer,0) as total_consumer,
                coalesce(demand.arrear_demand,0) as arrear_demand,
                coalesce(demand.current_demand,0) as current_demand,
                coalesce(prev_coll.prev_coll_amt,0) as prev_coll_amt,
                coalesce(coll.arrear_collection,0) as arrear_collection,
                coalesce(coll.curr_collection,0) as curr_collection,
                coalesce(advance_amount,0) as advance_amount
                 FROM view_ward_mstr
                LEFT JOIN ( SELECT tbl_consumer_demand.ward_mstr_id, 
                count(distinct(consumer_id)) as total_consumer,
                sum(
                    CASE
                        WHEN tbl_consumer_demand.demand_upto <= '$last_date'::date THEN tbl_consumer_demand.amount
                        ELSE NULL::numeric
                    END) AS arrear_demand,
                sum(
                    CASE
                        WHEN tbl_consumer_demand.demand_upto > '$last_date'::date AND tbl_consumer_demand.demand_upto <= '$curr_last_date'::date THEN tbl_consumer_demand.amount
                        ELSE NULL::numeric
                    END) AS current_demand
                FROM tbl_consumer_demand
                join tbl_consumer on tbl_consumer.id=tbl_consumer_demand.consumer_id
                $join
                GROUP BY tbl_consumer_demand.ward_mstr_id

                ) demand ON demand.ward_mstr_id = view_ward_mstr.id
            

                LEFT JOIN ( SELECT tbl_consumer_collection.ward_mstr_id,
                sum(
                    CASE
                        WHEN tbl_consumer_demand.demand_upto <= '$last_date'::date THEN tbl_consumer_collection.amount
                        ELSE NULL::numeric
                    END) AS arrear_collection,
                sum(
                    CASE
                        WHEN tbl_consumer_demand.demand_upto > '$last_date'::date AND tbl_consumer_demand.demand_upto <= '$curr_last_date'::date THEN tbl_consumer_collection.amount
                        ELSE NULL::numeric
                    END) AS curr_collection
                FROM tbl_consumer_collection
            
                JOIN tbl_consumer_demand ON tbl_consumer_demand.id = tbl_consumer_collection.demand_id 
                join tbl_transaction on tbl_transaction.id=tbl_consumer_collection.transaction_id 
                join tbl_consumer on tbl_consumer.id=tbl_consumer_collection.consumer_id
                $join
                where transaction_date>'$last_date' and transaction_date<='$curr_last_date'
                GROUP BY tbl_consumer_collection.ward_mstr_id) coll ON coll.ward_mstr_id = view_ward_mstr.id
                
                left join (select tbl_consumer_collection.ward_mstr_id,sum(tbl_consumer_collection.amount) as prev_coll_amt
               from tbl_consumer_collection join tbl_transaction on tbl_transaction.id=
               tbl_consumer_collection.transaction_id join tbl_consumer_demand on tbl_consumer_demand.id=
               tbl_consumer_collection.demand_id
               join tbl_consumer on tbl_consumer.id=tbl_consumer_collection.consumer_id
                $join
                where transaction_date<='$last_date' and 
               transaction_type='Demand Collection' 
               group by tbl_consumer_collection.ward_mstr_id
              ) as prev_coll on prev_coll.ward_mstr_id=view_ward_mstr.id

              left join (

               select tbl_consumer.ward_mstr_id,sum(advance_amount) as advance_amount from tbl_consumer_advance_dtls
               join tbl_transaction on tbl_transaction.id=tbl_consumer_advance_dtls.transaction_id
               join tbl_consumer on tbl_consumer.id=tbl_transaction.related_id
               join view_ward_mstr on view_ward_mstr.id=tbl_consumer.ward_mstr_id
               $join
               where tbl_transaction.status in(1,2) and advance_from!='R' and active_status=1 
               and advance_from!='N' and transaction_date>'$last_date' and 
               transaction_date<='$curr_last_date' and transaction_type='Demand Collection' and 
               tbl_consumer.status!=0

               group by tbl_consumer.ward_mstr_id

              ) as advance on advance.ward_mstr_id=view_ward_mstr.id
              where view_ward_mstr.ulb_mstr_id=".$this->ulb_id."
            order by (substring(ward_no, '^[0-9]+'))::int,ward_no";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        // echo $this->db->getLastQuery();
        return $result;
    }

    public function wardWiseDCB2($last_date,$curr_last_date,$where)
    {
        $session=session();
        $ulb_details=$session->get('ulb_dtl')??getUlbDtl();        
        $this->ulb_id=$ulb_details['ulb_mstr_id'];
        
        $sql=" SELECT view_ward_mstr.id,view_ward_mstr.ward_no,
                coalesce(demand.total_consumer,0) as total_consumer,
                coalesce(demand.arrear_demand,0) as arrear_demand,
                coalesce(demand.current_demand,0) as current_demand,
                coalesce(prev_coll.prev_coll_amt,0) as prev_coll_amt,
                coalesce(coll.arrear_collection,0) as arrear_collection,
                coalesce(coll.curr_collection,0) as curr_collection,
                coalesce(advance_amount,0) as advance_amount,
                (coalesce(all_advance.amount,0)) as advance_amount,
                coalesce(meter_status.meter_consumer,0) as meter_consumer, 
	            coalesce(meter_status.non_meter_consumer,0) as non_meter_consumer
                FROM view_ward_mstr
                LEFT JOIN ( 
                    SELECT tbl_consumer.ward_mstr_id, count(distinct(tbl_consumer.id)) as total_consumer,
                        sum(
                            CASE
                                WHEN tbl_consumer_demand.demand_upto <= '$last_date'::date THEN tbl_consumer_demand.amount
                                ELSE NULL::numeric
                            END) AS arrear_demand,
                        sum(
                            CASE
                                WHEN tbl_consumer_demand.demand_upto > '$last_date'::date AND tbl_consumer_demand.demand_upto <= '$curr_last_date'::date THEN tbl_consumer_demand.amount
                                ELSE NULL::numeric
                            END) AS current_demand
                    FROM tbl_consumer 
                    LEFT JOIN tbl_consumer_demand on tbl_consumer.id=tbl_consumer_demand.consumer_id 
                        and tbl_consumer_demand.status=1
                    WHERE tbl_consumer.status = 1
                        $where
                    GROUP BY tbl_consumer.ward_mstr_id

                ) demand ON demand.ward_mstr_id = view_ward_mstr.id
            

                LEFT JOIN ( 
                    SELECT tbl_consumer.ward_mstr_id,
                        sum(
                            CASE
                                WHEN tbl_consumer_demand.demand_upto <= '$last_date'::date THEN tbl_consumer_collection.amount
                                ELSE NULL::numeric
                            END) AS arrear_collection,
                        sum(
                            CASE
                                WHEN tbl_consumer_demand.demand_upto > '$last_date'::date AND tbl_consumer_demand.demand_upto <= '$curr_last_date'::date THEN tbl_consumer_collection.amount
                                ELSE NULL::numeric
                            END) AS curr_collection
                    FROM tbl_consumer_collection
                
                    JOIN tbl_consumer_demand ON tbl_consumer_demand.id = tbl_consumer_collection.demand_id 
                    join tbl_transaction on tbl_transaction.id=tbl_consumer_collection.transaction_id 
                    join tbl_consumer on tbl_consumer.id=tbl_consumer_collection.consumer_id                    
                    where transaction_date>'$last_date' and transaction_date<='$curr_last_date'
                        AND tbl_transaction.transaction_type::text = 'Demand Collection'::text
                        AND tbl_transaction.status in(1,2) and tbl_consumer.status=1
                        $where
                    GROUP BY tbl_consumer.ward_mstr_id
                ) coll ON coll.ward_mstr_id = view_ward_mstr.id
                                
                left join (
                    select tbl_consumer.ward_mstr_id,
                        sum(tbl_consumer_collection.amount) as prev_coll_amt
                    FROM tbl_consumer_collection 
                    JOIN tbl_consumer_demand ON tbl_consumer_demand.id = tbl_consumer_collection.demand_id 
                    join tbl_transaction on tbl_transaction.id=tbl_consumer_collection.transaction_id 
                    join tbl_consumer on tbl_consumer.id=tbl_consumer_collection.consumer_id                    
                    where transaction_date<='$last_date' 
                        AND tbl_transaction.transaction_type::text = 'Demand Collection'::text 
                        AND tbl_transaction.status in(1,2) and tbl_consumer.status=1
                        $where
                    group by tbl_consumer.ward_mstr_id
                ) as prev_coll on prev_coll.ward_mstr_id=view_ward_mstr.id

                left join (
                    select tbl_consumer.ward_mstr_id,sum(advance_amount) as advance_amount 
                    from tbl_consumer_advance_dtls
                    join tbl_transaction on tbl_transaction.id=tbl_consumer_advance_dtls.transaction_id
                    join tbl_consumer on tbl_consumer.id=tbl_transaction.related_id
                    join view_ward_mstr on view_ward_mstr.id=tbl_consumer.ward_mstr_id                    
                    where tbl_transaction.status in(1,2) and advance_from!='R' and active_status=1 
                        and advance_from!='N' and transaction_date>'$last_date' and 
                        transaction_date<='$curr_last_date' and transaction_type='Demand Collection'
                        and tbl_consumer.status=1 
                        $where
                    group by tbl_consumer.ward_mstr_id

                ) as advance on advance.ward_mstr_id=view_ward_mstr.id
                left join(
                    select tbl_consumer.ward_mstr_id, sum(tbl_advance_mstr.amount) as amount 
                        from tbl_advance_mstr
                        JOIN tbl_transaction ON tbl_transaction.id = tbl_advance_mstr.transaction_id
                        join tbl_consumer on tbl_consumer.id=tbl_advance_mstr.related_id
                        where tbl_advance_mstr.status=1 AND tbl_transaction.transaction_type='Demand Collection' 
                            AND tbl_transaction.status in (1, 2) 
                            AND (tbl_transaction.transaction_date <= '$curr_last_date'::date) 
                            AND tbl_advance_mstr.module='consumer' 
                            AND tbl_consumer.status=1 
                            $where
                        group by tbl_consumer.ward_mstr_id
                ) as all_advance on all_advance.ward_mstr_id = view_ward_mstr.id
                left join (
                    select tbl_consumer.ward_mstr_id, sum(tbl_adjustment_mstr.amount) as amount 
                        from tbl_adjustment_mstr
                        JOIN tbl_transaction ON tbl_transaction.id = tbl_adjustment_mstr.transaction_id
                        join tbl_consumer on tbl_consumer.id=tbl_adjustment_mstr.related_id
                        where tbl_adjustment_mstr.status=1 AND tbl_transaction.transaction_type='Demand Collection' 
                            AND tbl_transaction.status in (1, 2) 
                            AND (tbl_transaction.transaction_date <= '$curr_last_date'::date) 
                            AND tbl_adjustment_mstr.module='consumer' 
                            AND tbl_consumer.status=1 
                            $where
                        group by tbl_consumer.ward_mstr_id
                ) as all_adjustment on all_adjustment.ward_mstr_id = view_ward_mstr.id
                left join (
                    select tbl_consumer.ward_mstr_id,
                        count( case when meter_status.meter_stustus='Meter' then tbl_consumer.id else null end) as meter_consumer,
                        count( case when meter_status.meter_stustus!='Meter' then tbl_consumer.id else null end) as non_meter_consumer
                    from tbl_consumer
                    left join(
                        select consumer_id,
                            case when connection_type = 1 then 'Meter'			
                                else 'Non_Meter' end as meter_stustus,
                            row_number() over(partition by consumer_id order by id desc) as row_num
                        from tbl_meter_status
                        where connection_date<='$curr_last_date'::date
                    )meter_status on meter_status.consumer_id = tbl_consumer.id and row_num=1
                    where tbl_consumer.status = 1
                            $where
                    group by tbl_consumer.ward_mstr_id
                )as meter_status on meter_status.ward_mstr_id = view_ward_mstr.id
                where view_ward_mstr.ulb_mstr_id=".$this->ulb_id."
                order by (substring(ward_no, '^[0-9]+'))::int,ward_no";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //echo $this->db->getLastQuery();
        return $result;
    }

    public function wardWiseDCB2PMU($last_date,$curr_last_date,$where,$wherdry,$wheremain)
    {
        $session=session();
        $ulb_details=$session->get('ulb_dtl')??getUlbDtl();        
        $this->ulb_id=$ulb_details['ulb_mstr_id'];
        
        $sql="WITH supper_Dray AS (
                SELECT distinct(tbl_consumer.id) as consumer_id,
                    tbl_transaction.id as transaction_id
                FROM tbl_consumer
                LEFT JOIN tbl_transaction on tbl_transaction.related_id = tbl_consumer.id 
                    and tbl_transaction.status in (1,2)
                    and tbl_transaction.transaction_type = 'Demand Collection'
                WHERE tbl_consumer.status=1        
                    AND tbl_transaction.id isnull
            
            ) 
            select id,ward_no,
            sum(coalesce(total_consumer,0)) as total_consumer,
            sum(coalesce(arrear_demand,0)) as arrear_demand,
            sum(coalesce(current_demand,0)) as current_demand,
            sum(coalesce(prev_coll_amt,0)) as prev_coll_amt,
            sum(coalesce(arrear_collection,0)) as arrear_collection,
            sum(coalesce(curr_collection,0)) as curr_collection,
            -- sum(coalesce(advance_amount,0)) as advance_amount,
            sum((coalesce(advance_amount,0))) as advance_amount,
            sum(coalesce(meter_consumer,0)) as meter_consumer,
            sum(coalesce(non_meter_consumer,0)) as non_meter_consumer
            FROM(   
                SELECT *
                FROM( 
                        SELECT view_ward_mstr.id,view_ward_mstr.ward_no,
                            coalesce(demand.total_consumer,0) as total_consumer,
                            coalesce(demand.arrear_demand,0) as arrear_demand,
                            coalesce(demand.current_demand,0) as current_demand,
                            coalesce(prev_coll.prev_coll_amt,0) as prev_coll_amt,
                            coalesce(coll.arrear_collection,0) as arrear_collection,
                            coalesce(coll.curr_collection,0) as curr_collection,
                            -- coalesce(advance_amount,0) as advance_amount,
                            ((coalesce(all_advance.amount,0))) as advance_amount,
							coalesce(meter_consumer,0) as meter_consumer,
							coalesce(non_meter_consumer,0) as non_meter_consumer
                        FROM view_ward_mstr
                        LEFT JOIN ( 
                            SELECT tbl_consumer.ward_mstr_id, count(distinct(tbl_consumer.id)) as total_consumer,
                                sum(
                                    CASE
                                        WHEN tbl_consumer_demand.demand_upto <= '$last_date'::date THEN tbl_consumer_demand.amount
                                        ELSE NULL::numeric
                                    END) AS arrear_demand,
                                sum(
                                    CASE
                                        WHEN tbl_consumer_demand.demand_upto > '$last_date'::date AND tbl_consumer_demand.demand_upto <= '$curr_last_date'::date THEN tbl_consumer_demand.amount
                                        ELSE NULL::numeric
                                    END) AS current_demand
                            FROM tbl_consumer 
                            LEFT JOIN tbl_consumer_demand on tbl_consumer.id=tbl_consumer_demand.consumer_id 
                                and tbl_consumer_demand.status=1
                            LEFT JOIN supper_Dray ON  supper_Dray.consumer_id = tbl_consumer.id
                            WHERE tbl_consumer.status = 1
                                $where $wherdry
                            GROUP BY tbl_consumer.ward_mstr_id

                        ) demand ON demand.ward_mstr_id = view_ward_mstr.id
                    

                        LEFT JOIN ( 
                            SELECT tbl_consumer.ward_mstr_id,
                                sum(
                                    CASE
                                        WHEN tbl_consumer_demand.demand_upto <= '$last_date'::date THEN tbl_consumer_collection.amount
                                        ELSE NULL::numeric
                                    END) AS arrear_collection,
                                sum(
                                    CASE
                                        WHEN tbl_consumer_demand.demand_upto > '$last_date'::date AND tbl_consumer_demand.demand_upto <= '$curr_last_date'::date THEN tbl_consumer_collection.amount
                                        ELSE NULL::numeric
                                    END) AS curr_collection
                            FROM tbl_consumer_collection
                        
                            JOIN tbl_consumer_demand ON tbl_consumer_demand.id = tbl_consumer_collection.demand_id 
                            join tbl_transaction on tbl_transaction.id=tbl_consumer_collection.transaction_id 
                            join tbl_consumer on tbl_consumer.id=tbl_consumer_collection.consumer_id
                            LEFT JOIN supper_Dray ON  supper_Dray.consumer_id = tbl_consumer.id                    
                            where transaction_date>'$last_date' and transaction_date<='$curr_last_date'
                                AND tbl_transaction.transaction_type::text = 'Demand Collection'::text
                                AND tbl_transaction.status in(1,2) and tbl_consumer.status=1
                                $where $wherdry
                            GROUP BY tbl_consumer.ward_mstr_id
                        ) coll ON coll.ward_mstr_id = view_ward_mstr.id
                                        
                        left join (
                            select tbl_consumer.ward_mstr_id,
                                sum(tbl_consumer_collection.amount) as prev_coll_amt
                            FROM tbl_consumer_collection 
                            JOIN tbl_consumer_demand ON tbl_consumer_demand.id = tbl_consumer_collection.demand_id 
                            join tbl_transaction on tbl_transaction.id=tbl_consumer_collection.transaction_id 
                            join tbl_consumer on tbl_consumer.id=tbl_consumer_collection.consumer_id   
                            LEFT JOIN supper_Dray ON  supper_Dray.consumer_id = tbl_consumer.id                 
                            where transaction_date<='$last_date' 
                                AND tbl_transaction.transaction_type::text = 'Demand Collection'::text 
                                AND tbl_transaction.status in(1,2) and tbl_consumer.status=1
                                $where $wherdry
                            group by tbl_consumer.ward_mstr_id
                        ) as prev_coll on prev_coll.ward_mstr_id=view_ward_mstr.id

                        left join (
                            select tbl_consumer.ward_mstr_id,sum(advance_amount) as advance_amount 
                            from tbl_consumer_advance_dtls
                            join tbl_transaction on tbl_transaction.id=tbl_consumer_advance_dtls.transaction_id
                            join tbl_consumer on tbl_consumer.id=tbl_transaction.related_id
                            LEFT JOIN supper_Dray ON  supper_Dray.consumer_id = tbl_consumer.id
                            join view_ward_mstr on view_ward_mstr.id=tbl_consumer.ward_mstr_id                    
                            where tbl_transaction.status in(1,2) and advance_from!='R' and active_status=1 
                                and advance_from!='N' and transaction_date>'$last_date' and 
                                transaction_date<='$curr_last_date' and transaction_type='Demand Collection'
                                and tbl_consumer.status=1 
                                $where $wherdry
                            group by tbl_consumer.ward_mstr_id

                        ) as advance on advance.ward_mstr_id=view_ward_mstr.id
                        left join(
			                select tbl_consumer.ward_mstr_id, sum(tbl_advance_mstr.amount) as amount 
			                    from tbl_advance_mstr
			                    JOIN tbl_transaction ON tbl_transaction.id = tbl_advance_mstr.transaction_id
			                    join tbl_consumer on tbl_consumer.id=tbl_advance_mstr.related_id
								LEFT JOIN supper_Dray ON  supper_Dray.consumer_id = tbl_consumer.id
			                    where tbl_advance_mstr.status=1 AND tbl_transaction.transaction_type='Demand Collection' 
			                        AND tbl_transaction.status in (1, 2) 
			                        AND (tbl_transaction.transaction_date <= '2025-03-31'::date) 
			                        AND tbl_advance_mstr.module='consumer'
									AND tbl_consumer.status = 1
									$where $wherdry
			                    group by tbl_consumer.ward_mstr_id
			            ) as all_advance on all_advance.ward_mstr_id = view_ward_mstr.id
			            left join (
			                select tbl_consumer.ward_mstr_id, sum(tbl_adjustment_mstr.amount) as amount 
			                    from tbl_adjustment_mstr
			                    JOIN tbl_transaction ON tbl_transaction.id = tbl_adjustment_mstr.transaction_id
			                    join tbl_consumer on tbl_consumer.id=tbl_adjustment_mstr.related_id
								LEFT JOIN supper_Dray ON  supper_Dray.consumer_id = tbl_consumer.id
			                    where tbl_adjustment_mstr.status=1 AND tbl_transaction.transaction_type='Demand Collection' 
			                        AND tbl_transaction.status in (1, 2) 
			                        AND (tbl_transaction.transaction_date <= '2025-03-31'::date) 
			                        AND tbl_adjustment_mstr.module='consumer'
									AND tbl_consumer.status = 1
									$where $wherdry
			                    group by tbl_consumer.ward_mstr_id
			            ) as all_adjustment on all_adjustment.ward_mstr_id = view_ward_mstr.id
						left join (
							select tbl_consumer.ward_mstr_id,
								count( case when meter_status.meter_stustus='Meter' then tbl_consumer.id else null end) as meter_consumer,
								count( case when meter_status.meter_stustus!='Meter' then tbl_consumer.id else null end) as non_meter_consumer
							from tbl_consumer
							left join(
								select consumer_id,
									case when connection_type = 1 then 'Meter'			
										else 'Non_Meter' end as meter_stustus,
									row_number() over(partition by consumer_id order by id desc) as row_num
								from tbl_meter_status
								where connection_date<='2025-03-31'::date
							)meter_status on meter_status.consumer_id = tbl_consumer.id and row_num=1
							LEFT JOIN supper_Dray ON  supper_Dray.consumer_id = tbl_consumer.id
			                where tbl_consumer.status = 1
								$where $wherdry
			                group by tbl_consumer.ward_mstr_id
			            )as meter_status on meter_status.ward_mstr_id = view_ward_mstr.id
                        where view_ward_mstr.ulb_mstr_id=".$this->ulb_id."
                        order by (substring(ward_no, '^[0-9]+'))::int,ward_no
                )dry
                UNION
                (
                    SELECT view_ward_mstr.id,view_ward_mstr.ward_no,
                            coalesce(demand.total_consumer,0) as total_consumer,
                            coalesce(demand.arrear_demand,0) as arrear_demand,
                            coalesce(demand.current_demand,0) as current_demand,
                            coalesce(prev_coll.prev_coll_amt,0) as prev_coll_amt,
                            coalesce(coll.arrear_collection,0) as arrear_collection,
                            coalesce(coll.curr_collection,0) as curr_collection,
                           -- coalesce(advance_amount,0) as advance_amount,
                           ((coalesce(all_advance.amount,0))) as advance_amount,
							coalesce(meter_consumer,0) as meter_consumer,
							coalesce(non_meter_consumer,0) as non_meter_consumer
                        FROM view_ward_mstr
                        LEFT JOIN ( 
                            SELECT tbl_consumer.ward_mstr_id, count(distinct(tbl_consumer.id)) as total_consumer,
                                sum(
                                    CASE
                                        WHEN tbl_consumer_demand.demand_upto <= '$last_date'::date THEN tbl_consumer_demand.amount
                                        ELSE NULL::numeric
                                    END) AS arrear_demand,
                                sum(
                                    CASE
                                        WHEN tbl_consumer_demand.demand_upto > '$last_date'::date AND tbl_consumer_demand.demand_upto <= '$curr_last_date'::date THEN tbl_consumer_demand.amount
                                        ELSE NULL::numeric
                                    END) AS current_demand
                            FROM tbl_consumer 
                            LEFT JOIN tbl_consumer_demand on tbl_consumer.id=tbl_consumer_demand.consumer_id 
                                and tbl_consumer_demand.status=1
                            LEFT JOIN supper_Dray ON  supper_Dray.consumer_id = tbl_consumer.id
                            WHERE tbl_consumer.status = 1
                                $where $wheremain
                            GROUP BY tbl_consumer.ward_mstr_id

                        ) demand ON demand.ward_mstr_id = view_ward_mstr.id
                    

                        LEFT JOIN ( 
                            SELECT tbl_consumer.ward_mstr_id,
                                sum(
                                    CASE
                                        WHEN tbl_consumer_demand.demand_upto <= '$last_date'::date THEN tbl_consumer_collection.amount
                                        ELSE NULL::numeric
                                    END) AS arrear_collection,
                                sum(
                                    CASE
                                        WHEN tbl_consumer_demand.demand_upto > '$last_date'::date AND tbl_consumer_demand.demand_upto <= '$curr_last_date'::date THEN tbl_consumer_collection.amount
                                        ELSE NULL::numeric
                                    END) AS curr_collection
                            FROM tbl_consumer_collection
                        
                            JOIN tbl_consumer_demand ON tbl_consumer_demand.id = tbl_consumer_collection.demand_id 
                            join tbl_transaction on tbl_transaction.id=tbl_consumer_collection.transaction_id 
                            join tbl_consumer on tbl_consumer.id=tbl_consumer_collection.consumer_id
                            LEFT JOIN supper_Dray ON  supper_Dray.consumer_id = tbl_consumer.id                    
                            where transaction_date>'$last_date' and transaction_date<='$curr_last_date'
                                AND tbl_transaction.transaction_type::text = 'Demand Collection'::text
                                AND tbl_transaction.status in(1,2) and tbl_consumer.status=1
                                $where $wheremain
                            GROUP BY tbl_consumer.ward_mstr_id
                        ) coll ON coll.ward_mstr_id = view_ward_mstr.id
                                        
                        left join (
                            select tbl_consumer.ward_mstr_id,
                                sum(tbl_consumer_collection.amount) as prev_coll_amt
                            FROM tbl_consumer_collection 
                            JOIN tbl_consumer_demand ON tbl_consumer_demand.id = tbl_consumer_collection.demand_id 
                            join tbl_transaction on tbl_transaction.id=tbl_consumer_collection.transaction_id 
                            join tbl_consumer on tbl_consumer.id=tbl_consumer_collection.consumer_id   
                            LEFT JOIN supper_Dray ON  supper_Dray.consumer_id = tbl_consumer.id                 
                            where transaction_date<='$last_date' 
                                AND tbl_transaction.transaction_type::text = 'Demand Collection'::text 
                                AND tbl_transaction.status in(1,2) and tbl_consumer.status=1
                                $where $wheremain
                            group by tbl_consumer.ward_mstr_id
                        ) as prev_coll on prev_coll.ward_mstr_id=view_ward_mstr.id

                        left join (
                            select tbl_consumer.ward_mstr_id,sum(advance_amount) as advance_amount 
                            from tbl_consumer_advance_dtls
                            join tbl_transaction on tbl_transaction.id=tbl_consumer_advance_dtls.transaction_id
                            join tbl_consumer on tbl_consumer.id=tbl_transaction.related_id
                            LEFT JOIN supper_Dray ON  supper_Dray.consumer_id = tbl_consumer.id
                            join view_ward_mstr on view_ward_mstr.id=tbl_consumer.ward_mstr_id                    
                            where tbl_transaction.status in(1,2) and advance_from!='R' and active_status=1 
                                and advance_from!='N' and transaction_date>'$last_date' and 
                                transaction_date<='$curr_last_date' and transaction_type='Demand Collection'
                                and tbl_consumer.status=1 
                                $where $wheremain
                            group by tbl_consumer.ward_mstr_id

                        ) as advance on advance.ward_mstr_id=view_ward_mstr.id
                        left join(
			                select tbl_consumer.ward_mstr_id, sum(tbl_advance_mstr.amount) as amount 
			                    from tbl_advance_mstr
			                    JOIN tbl_transaction ON tbl_transaction.id = tbl_advance_mstr.transaction_id
			                    join tbl_consumer on tbl_consumer.id=tbl_advance_mstr.related_id
								LEFT JOIN supper_Dray ON  supper_Dray.consumer_id = tbl_consumer.id
			                    where tbl_advance_mstr.status=1 AND tbl_transaction.transaction_type='Demand Collection' 
			                        AND tbl_transaction.status in (1, 2) 
			                        AND (tbl_transaction.transaction_date <= '2025-03-31'::date) 
			                        AND tbl_advance_mstr.module='consumer'
									AND tbl_consumer.status = 1
									$where $wheremain
			                    group by tbl_consumer.ward_mstr_id
			            ) as all_advance on all_advance.ward_mstr_id = view_ward_mstr.id
			            left join (
			                select tbl_consumer.ward_mstr_id, sum(tbl_adjustment_mstr.amount) as amount 
			                    from tbl_adjustment_mstr
			                    JOIN tbl_transaction ON tbl_transaction.id = tbl_adjustment_mstr.transaction_id
			                    join tbl_consumer on tbl_consumer.id=tbl_adjustment_mstr.related_id
								LEFT JOIN supper_Dray ON  supper_Dray.consumer_id = tbl_consumer.id
			                    where tbl_adjustment_mstr.status=1 AND tbl_transaction.transaction_type='Demand Collection' 
			                        AND tbl_transaction.status in (1, 2) 
			                        AND (tbl_transaction.transaction_date <= '2025-03-31'::date) 
			                        AND tbl_adjustment_mstr.module='consumer'
									AND tbl_consumer.status = 1
									$where $wheremain
			                    group by tbl_consumer.ward_mstr_id
			            ) as all_adjustment on all_adjustment.ward_mstr_id = view_ward_mstr.id
						left join (
							select tbl_consumer.ward_mstr_id,
								count( case when meter_status.meter_stustus='Meter' then tbl_consumer.id else null end) as meter_consumer,
								count( case when meter_status.meter_stustus!='Meter' then tbl_consumer.id else null end) as non_meter_consumer
							from tbl_consumer
							left join(
								select consumer_id,
									case when connection_type = 1 then 'Meter'			
										else 'Non_Meter' end as meter_stustus,
									row_number() over(partition by consumer_id order by id desc) as row_num
								from tbl_meter_status
								where connection_date<='2025-03-31'::date
							)meter_status on meter_status.consumer_id = tbl_consumer.id and row_num=1
							LEFT JOIN supper_Dray ON  supper_Dray.consumer_id = tbl_consumer.id
			                where tbl_consumer.status = 1
								$where $wheremain
			                group by tbl_consumer.ward_mstr_id
			            )as meter_status on meter_status.ward_mstr_id = view_ward_mstr.id
                        where view_ward_mstr.ulb_mstr_id=".$this->ulb_id."
                        order by (substring(ward_no, '^[0-9]+'))::int,ward_no
                )
            ) final 
            group by id,ward_no
            order by (substring(ward_no, '^[0-9]+'))::int,ward_no ";
        $run=$this->db->query($sql);print_var($sql);
        $result=$run->getResultArray();
        //echo $this->db->getLastQuery();
        // print_var($sql);die;
        return $result;
    }
    
    public function viewDcb($last_date,$curr_last_date,$fy_mstr_id,$join,$prop_where)
    {
        $sql="SELECT view_ward_mstr.id,view_ward_mstr.ward_no,COALESCE(((demand.arrear_demand-prev_coll.prev_coll_amt)-coll.arrear_collection), 0::numeric) AS arrear_demand,COALESCE((demand.current_demand-coll.curr_collection), 0::numeric) AS current_demand,
            prop_arrear_demand,prop_current_demand
            
            FROM view_ward_mstr
             LEFT JOIN 
             ( SELECT tbl_consumer_demand.ward_mstr_id,
                sum(
                    CASE
                        WHEN tbl_consumer_demand.demand_upto <= '$last_date'::date  THEN tbl_consumer_demand.amount
                        ELSE NULL::numeric
                    END) AS arrear_demand,
                sum(
                    CASE
                        WHEN tbl_consumer_demand.demand_upto > '$last_date'::date AND tbl_consumer_demand.demand_upto <= '$curr_last_date'::date THEN tbl_consumer_demand.amount
                        ELSE NULL::numeric
                    END) AS current_demand
                FROM tbl_consumer_demand
                JOIN tbl_consumer ON tbl_consumer.id = tbl_consumer_demand.consumer_id
                $join
                GROUP BY tbl_consumer_demand.ward_mstr_id
            ) demand ON demand.ward_mstr_id = view_ward_mstr.id

             LEFT JOIN ( SELECT tbl_consumer_collection.ward_mstr_id,
                sum(
                    CASE
                        WHEN tbl_consumer_demand.demand_upto <= '$last_date'::date THEN tbl_consumer_collection.amount
                        ELSE NULL::numeric
                    END) AS arrear_collection,
                sum(
                    CASE
                        WHEN tbl_consumer_demand.demand_upto > '$last_date'::date AND tbl_consumer_demand.demand_upto <= '$curr_last_date'::date THEN tbl_consumer_collection.amount
                        ELSE NULL::numeric
                    END) AS curr_collection
                FROM tbl_consumer_collection
                
                JOIN tbl_consumer_demand ON tbl_consumer_demand.id = tbl_consumer_collection.demand_id 
                join tbl_transaction on tbl_transaction.id=tbl_consumer_collection.transaction_id 
                join tbl_consumer on tbl_consumer.id=tbl_consumer_collection.consumer_id
                $join
                where transaction_date>'$last_date' and transaction_date<='$curr_last_date'
                GROUP BY tbl_consumer_collection.ward_mstr_id) coll ON coll.ward_mstr_id = view_ward_mstr.id
                
                left join (select tbl_consumer_collection.ward_mstr_id,sum(tbl_consumer_collection.amount) as prev_coll_amt
               from tbl_consumer_collection join tbl_transaction on tbl_transaction.id=
               tbl_consumer_collection.transaction_id join tbl_consumer_demand on tbl_consumer_demand.id=
               tbl_consumer_collection.demand_id
               join tbl_consumer on tbl_consumer.id=tbl_consumer_collection.consumer_id
                $join
                where transaction_date<='$last_date' and 
               transaction_type='Demand Collection' 
               group by tbl_consumer_collection.ward_mstr_id
              ) as prev_coll on prev_coll.ward_mstr_id=view_ward_mstr.id


            left join 
            (

                select ward_mstr_id,prop_arrear_demand,prop_current_demand from 
                dblink(
                'host=".getenv('db.pgsql.hname')." user=".getenv('db.pgsql.uname')." password=".getenv('db.pgsql.pass')." port=".getenv('db.pgsql.port')." dbname=db_rmc_property'::text,
                
                'select tbl_prop_demand.ward_mstr_id,
                sum(
                    CASE
                        WHEN fy_mstr_id < $fy_mstr_id and paid_status=0 and tbl_prop_demand.status=1 THEN amount
                        ELSE NULL::numeric
                    END) AS prop_arrear_demand,
                sum(
                    CASE
                        WHEN fy_mstr_id = $fy_mstr_id  and paid_status=0  and tbl_prop_demand.status=1 THEN amount
                        ELSE NULL::numeric
                    END) AS prop_current_demand
                FROM tbl_prop_demand
                JOIN tbl_prop_dtl ON tbl_prop_dtl.id = tbl_prop_demand.prop_dtl_id
                 $prop_where
                GROUP BY tbl_prop_demand.ward_mstr_id'::text

                ) tbl_prop_demand (ward_mstr_id bigint,prop_arrear_demand numeric,prop_current_demand numeric)
                
            ) as prop_demand on prop_demand.ward_mstr_id=demand.ward_mstr_id 

            order by (substring(ward_no, '^[0-9]+')::int,ward_no)
            ";

        $run=$this->db->query($sql);
        $result=$run->getResultArray();
       //   echo $this->getLastQuery();

        return $result;
            
    }
    
    // public function viewDcbNew($last_date,$curr_last_date,$fy_mstr_id,$water_where,$prop_where)
    // {
    //     // print_var($this->db->getDatabase());
    //     // die;

       
    //     $demand_water_where=null;
    //     $coll_water_where =null;
        
    //     if($water_where!="")
    //     {
    //         $demand_water_where=" where $water_where";
    //         $coll_water_where=" and $water_where";
    //     }
    //      $sql="SELECT view_ward_mstr.id,view_ward_mstr.ward_no,COALESCE(((demand.arrear_demand-prev_coll.prev_coll_amt)-COALESCE(coll.arrear_collection,0)), 0::numeric) AS arrear_demand,COALESCE((demand.current_demand-COALESCE(coll.curr_collection,0)), 0::numeric) AS current_demand,
    //         prop_arrear_demand,prop_current_demand
            
    //         FROM view_ward_mstr
    //          LEFT JOIN 
    //          ( SELECT tbl_consumer_demand.ward_mstr_id,
    //             sum(
    //                 CASE
    //                     WHEN tbl_consumer_demand.demand_upto <= '$last_date'::date  THEN tbl_consumer_demand.amount
    //                     ELSE NULL::numeric
    //                 END) AS arrear_demand,
    //             sum(
    //                 CASE
    //                     WHEN tbl_consumer_demand.demand_upto > '$last_date'::date AND tbl_consumer_demand.demand_upto <= '$curr_last_date'::date THEN tbl_consumer_demand.amount
    //                     ELSE NULL::numeric
    //                 END) AS current_demand
    //             FROM tbl_consumer_demand
    //             JOIN tbl_consumer ON tbl_consumer.id = tbl_consumer_demand.consumer_id
    //             $demand_water_where
    //             GROUP BY tbl_consumer_demand.ward_mstr_id
    //         ) demand ON demand.ward_mstr_id = view_ward_mstr.id

    //          LEFT JOIN ( SELECT tbl_consumer_collection.ward_mstr_id,
    //             sum(
    //                 CASE
    //                     WHEN tbl_consumer_demand.demand_upto <= '$last_date'::date THEN tbl_consumer_collection.amount
    //                     ELSE NULL::numeric
    //                 END) AS arrear_collection,
    //             sum(
    //                 CASE
    //                     WHEN tbl_consumer_demand.demand_upto > '$last_date'::date AND tbl_consumer_demand.demand_upto <= '$curr_last_date'::date THEN tbl_consumer_collection.amount
    //                     ELSE NULL::numeric
    //                 END) AS curr_collection
    //             FROM tbl_consumer_collection
                
    //             JOIN tbl_consumer_demand ON tbl_consumer_demand.id = tbl_consumer_collection.demand_id 
    //             join tbl_transaction on tbl_transaction.id=tbl_consumer_collection.transaction_id 
    //             join tbl_consumer on tbl_consumer.id=tbl_consumer_collection.consumer_id
                
    //             where transaction_date>'$last_date' and transaction_date<='$curr_last_date'  $coll_water_where
    //             GROUP BY tbl_consumer_collection.ward_mstr_id) coll ON coll.ward_mstr_id = view_ward_mstr.id
                
    //             left join (select tbl_consumer_collection.ward_mstr_id,sum(tbl_consumer_collection.amount) as prev_coll_amt
    //            from tbl_consumer_collection join tbl_transaction on tbl_transaction.id=
    //            tbl_consumer_collection.transaction_id join tbl_consumer_demand on tbl_consumer_demand.id=
    //            tbl_consumer_collection.demand_id
    //            join tbl_consumer on tbl_consumer.id=tbl_consumer_collection.consumer_id
               
    //             where transaction_date<='$last_date' and 
    //            transaction_type='Demand Collection' $coll_water_where
    //            group by tbl_consumer_collection.ward_mstr_id
    //           ) as prev_coll on prev_coll.ward_mstr_id=view_ward_mstr.id


    //         left join 
    //         (
                
    //             select ward_mstr_id,prop_arrear_demand,prop_current_demand from 
    //             dblink(
    //             'host=".getenv('db.pgsql.hname')." user=".getenv('db.pgsql.uname')." password=".getenv('db.pgsql.pass')." port=".getenv('db.pgsql.port')." dbname=db_rmc_property'::text,

    //             'select tbl_prop_demand.ward_mstr_id,
    //             sum(
    //                 CASE
    //                     WHEN fy_mstr_id < $fy_mstr_id and paid_status=0 and tbl_prop_demand.status=1 THEN amount
    //                     ELSE NULL::numeric
    //                 END) AS prop_arrear_demand,
    //             sum(
    //                 CASE
    //                     WHEN fy_mstr_id = $fy_mstr_id  and paid_status=0  and tbl_prop_demand.status=1 THEN amount
    //                     ELSE NULL::numeric
    //                 END) AS prop_current_demand
    //             FROM tbl_prop_demand
    //             JOIN tbl_prop_dtl ON tbl_prop_dtl.id = tbl_prop_demand.prop_dtl_id
    //              $prop_where
    //             GROUP BY tbl_prop_demand.ward_mstr_id'::text

    //             ) tbl_prop_demand (ward_mstr_id bigint,prop_arrear_demand numeric,prop_current_demand numeric)
                
    //         ) as prop_demand on prop_demand.ward_mstr_id=view_ward_mstr.id 
    //         order by (substring(ward_no, '^[0-9]+'))::int,ward_no
    //         ";
    //     //print_var($sql);
    //     $run=$this->db->query($sql);
    //     $result=$run->getResultArray();

    //     return $result;
            
    // }
    // public function viewDcbNew($last_date,$curr_last_date,$fy_mstr_id,$water_where,$prop_where)
    public function viewDcbNew($fy_start_date,$fy_end_date,$fy_mstr_id,$water_where,$prop_where)
    {
        // echo $fy_start_date."  ".$fy_end_date."<Br/";
        // echo gettype($fy_start_date);
        // die;
        // print_var($this->db->getDatabase());
        // die;

       
        $demand_water_where=null;
        $coll_water_where =null;
        
        if($water_where!="")
        {
            $demand_water_where=" where $water_where";
            $coll_water_where=" and $water_where";
        }
         $sql="SELECT view_ward_mstr.id,view_ward_mstr.ward_no,COALESCE(((demand.arrear_demand-prev_coll.prev_coll_amt)-COALESCE(coll.arrear_collection,0)), 0::numeric) AS arrear_demand,COALESCE((demand.current_demand-COALESCE(coll.curr_collection,0)), 0::numeric) AS current_demand,
            prop_arrear_demand,prop_current_demand
            
            FROM view_ward_mstr
             LEFT JOIN 
             ( SELECT tbl_consumer_demand.ward_mstr_id,
                sum(
                    CASE
                        WHEN tbl_consumer_demand.demand_upto < '$fy_start_date'::date  THEN tbl_consumer_demand.amount
                        ELSE NULL::numeric
                    END) AS arrear_demand,
                sum(
                    CASE
                        WHEN tbl_consumer_demand.demand_upto between '$fy_start_date' and '$fy_end_date' THEN tbl_consumer_demand.amount
                        ELSE NULL::numeric
                    END) AS current_demand
                FROM tbl_consumer_demand
                JOIN tbl_consumer ON tbl_consumer.id = tbl_consumer_demand.consumer_id
                $demand_water_where
                GROUP BY tbl_consumer_demand.ward_mstr_id
            ) demand ON demand.ward_mstr_id = view_ward_mstr.id

             LEFT JOIN ( SELECT tbl_consumer_collection.ward_mstr_id,
                sum(
                    CASE
                        WHEN tbl_consumer_demand.demand_upto < '$fy_start_date'::date THEN tbl_consumer_collection.amount
                        ELSE NULL::numeric
                    END) AS arrear_collection,
                sum(
                    CASE
                        WHEN tbl_consumer_demand.demand_upto between '$fy_start_date' and '$fy_end_date' AND tbl_consumer_demand.demand_upto <= '$fy_end_date'::date THEN tbl_consumer_collection.amount
                        ELSE NULL::numeric
                    END) AS curr_collection
                FROM tbl_consumer_collection
                
                JOIN tbl_consumer_demand ON tbl_consumer_demand.id = tbl_consumer_collection.demand_id 
                join tbl_transaction on tbl_transaction.id=tbl_consumer_collection.transaction_id 
                join tbl_consumer on tbl_consumer.id=tbl_consumer_collection.consumer_id
                
                where transaction_date between '$fy_start_date' and '$fy_end_date' $coll_water_where
                GROUP BY tbl_consumer_collection.ward_mstr_id) coll ON coll.ward_mstr_id = view_ward_mstr.id
                
                left join (select tbl_consumer_collection.ward_mstr_id,sum(tbl_consumer_collection.amount) as prev_coll_amt
               from tbl_consumer_collection join tbl_transaction on tbl_transaction.id=
               tbl_consumer_collection.transaction_id join tbl_consumer_demand on tbl_consumer_demand.id=
               tbl_consumer_collection.demand_id
               join tbl_consumer on tbl_consumer.id=tbl_consumer_collection.consumer_id
               
                where transaction_date<'$fy_start_date'::date and 
               transaction_type='Demand Collection' $coll_water_where
               group by tbl_consumer_collection.ward_mstr_id
              ) as prev_coll on prev_coll.ward_mstr_id=view_ward_mstr.id


            left join 
            (
                
                select ward_mstr_id,prop_arrear_demand,prop_current_demand from 
                dblink(
                'host=".getenv('db.pgsql.hname')." user=".getenv('db.pgsql.uname')." password=".getenv('db.pgsql.pass')." port=".getenv('db.pgsql.port')." dbname=db_rmc_property'::text,

                'select tbl_prop_demand.ward_mstr_id,
                sum(
                    CASE
                        WHEN due_date<''$fy_start_date'' and paid_status=0 and tbl_prop_demand.status=1 THEN amount
                        ELSE NULL::numeric
                    END) AS prop_arrear_demand,
                sum(
                    CASE
                        WHEN due_date between ''$fy_start_date'' and ''$fy_end_date'' and paid_status=0  and tbl_prop_demand.status=1 THEN amount
                        ELSE NULL::numeric
                    END) AS prop_current_demand
                FROM tbl_prop_demand
                JOIN tbl_prop_dtl ON tbl_prop_dtl.id = tbl_prop_demand.prop_dtl_id
                 $prop_where
                GROUP BY tbl_prop_demand.ward_mstr_id'::text

                ) tbl_prop_demand (ward_mstr_id bigint,prop_arrear_demand numeric,prop_current_demand numeric)
                
            ) as prop_demand on prop_demand.ward_mstr_id=view_ward_mstr.id 
            order by (substring(ward_no, '^[0-9]+'))::int,ward_no
            ";
        // print_var($sql);
        $run=$this->db->query($sql);
        $result=$run->getResultArray();

        return $result;
            
    }
  
    

    public function viewAllModuleDcb($last_date,$curr_last_date,$fy_mstr_id,$join,$prop_where)
    {
        
     $sql="SELECT view_ward_mstr.id,view_ward_mstr.ward_no,
        COALESCE(((demand.arrear_demand-prev_coll.prev_coll_amt)+demand.current_demand), 0::numeric) AS water_demand,COALESCE((coll.arrear_collection+coll.curr_collection), 0::numeric) AS water_coll,
            COALESCE((prop_arrear_demand+prop_current_demand),0) as prop_demand,COALESCE((prop_arrear_coll+prop_current_coll),0) as prop_coll
            
            FROM view_ward_mstr
             LEFT JOIN 
             ( SELECT tbl_consumer_demand.ward_mstr_id,
                sum(
                    CASE
                        WHEN tbl_consumer_demand.demand_upto <= '$last_date'::date  THEN tbl_consumer_demand.amount
                        ELSE NULL::numeric
                    END) AS arrear_demand,
                sum(
                    CASE
                        WHEN tbl_consumer_demand.demand_upto > '$last_date'::date AND tbl_consumer_demand.demand_upto <= '$curr_last_date'::date THEN tbl_consumer_demand.amount
                        ELSE NULL::numeric
                    END) AS current_demand
                FROM tbl_consumer_demand
                JOIN tbl_consumer ON tbl_consumer.id = tbl_consumer_demand.consumer_id
                $join
                GROUP BY tbl_consumer_demand.ward_mstr_id
            ) demand ON demand.ward_mstr_id = view_ward_mstr.id

             LEFT JOIN ( SELECT tbl_consumer_collection.ward_mstr_id,
                sum(
                    CASE
                        WHEN tbl_consumer_demand.demand_upto <= '$last_date'::date THEN tbl_consumer_collection.amount
                        ELSE NULL::numeric
                    END) AS arrear_collection,
                sum(
                    CASE
                        WHEN tbl_consumer_demand.demand_upto > '$last_date'::date AND tbl_consumer_demand.demand_upto <= '$curr_last_date'::date THEN tbl_consumer_collection.amount
                        ELSE NULL::numeric
                    END) AS curr_collection
                FROM tbl_consumer_collection
            
                JOIN tbl_consumer_demand ON tbl_consumer_demand.id = tbl_consumer_collection.demand_id 
                join tbl_transaction on tbl_transaction.id=tbl_consumer_collection.transaction_id 
                join tbl_consumer on tbl_consumer.id=tbl_consumer_collection.consumer_id
                $join
                where transaction_date>'$last_date' and transaction_date<='$curr_last_date'
                GROUP BY tbl_consumer_collection.ward_mstr_id) coll ON coll.ward_mstr_id = view_ward_mstr.id
                
                left join (select tbl_consumer_collection.ward_mstr_id,sum(tbl_consumer_collection.amount) as prev_coll_amt
               from tbl_consumer_collection join tbl_transaction on tbl_transaction.id=
               tbl_consumer_collection.transaction_id join tbl_consumer_demand on tbl_consumer_demand.id=
               tbl_consumer_collection.demand_id
               join tbl_consumer on tbl_consumer.id=tbl_consumer_collection.consumer_id
                $join
                where transaction_date<='$last_date' and 
               transaction_type='Demand Collection' 
               group by tbl_consumer_collection.ward_mstr_id
              ) as prev_coll on prev_coll.ward_mstr_id=view_ward_mstr.id


            left join 
            (

                select ward_mstr_id,prop_arrear_demand,prop_current_demand,prop_arrear_coll,prop_current_coll,
                prop_prev_coll_amt from 
                dblink(
                'host=".getenv('db.pgsql.hname')." user=".getenv('db.pgsql.uname')." password=".getenv('db.pgsql.pass')." port=".getenv('db.pgsql.port')." dbname=db_rmc_property'::text,

                'select demand1.ward_mstr_id,(prop_arrear_demand-prop_prev_coll_amt) as prop_arr_demand,prop_current_demand,prop_arrear_coll,prop_current_coll,prop_prev_coll_amt

                 from (select tbl_prop_demand.ward_mstr_id,
                 sum(
                    CASE
                        WHEN fy_mstr_id < $fy_mstr_id and tbl_prop_demand.status=1 THEN amount
                        ELSE NULL::numeric
                    END) AS prop_arrear_demand,
                 sum(
                    CASE
                        WHEN fy_mstr_id = $fy_mstr_id and tbl_prop_demand.status=1 THEN amount
                        ELSE NULL::numeric
                    END) AS prop_current_demand

                FROM tbl_prop_demand
                JOIN tbl_prop_dtl ON tbl_prop_dtl.id = tbl_prop_demand.prop_dtl_id
                 $prop_where
                GROUP BY tbl_prop_demand.ward_mstr_id) as demand1

                left join 
                (
                    select tbl_collection.ward_mstr_id,
                    sum(
                        CASE
                            WHEN fy_mstr_id < $fy_mstr_id and tbl_collection.status=1 THEN amount
                            ELSE NULL::numeric
                        END) AS prop_arrear_coll,
                    sum(
                        CASE
                            WHEN fy_mstr_id = $fy_mstr_id  and tbl_collection.status=1 THEN amount
                            ELSE NULL::numeric
                        END) AS prop_current_coll
                    FROM tbl_collection
                    JOIN tbl_prop_dtl ON tbl_prop_dtl.id = tbl_collection.prop_dtl_id
                    join tbl_transaction on tbl_transaction.id=tbl_collection.transaction_id 
                     $prop_where and tran_date>''$last_date'' and tran_date<=''$curr_last_date'' and tran_type=''Property''

                    GROUP BY tbl_collection.ward_mstr_id
                ) as coll on coll.ward_mstr_id=demand1.ward_mstr_id

                left join 

                (
                    select tbl_collection.ward_mstr_id,
                   
                    sum(amount) AS prop_prev_coll_amt
                    FROM tbl_collection
                    JOIN tbl_prop_dtl ON tbl_prop_dtl.id = tbl_collection.prop_dtl_id
                    join tbl_transaction on tbl_transaction.id=tbl_collection.transaction_id 
                    $prop_where and tran_date<=''$last_date'' and tran_type=''Property''

                    GROUP BY tbl_collection.ward_mstr_id
                ) as prev_coll on prev_coll.ward_mstr_id=demand1.ward_mstr_id

                '::text

                ) tbl_prop_demand (ward_mstr_id bigint,prop_arrear_demand numeric,prop_current_demand numeric,prop_arrear_coll numeric,prop_current_coll numeric,prop_prev_coll_amt numeric)
                

            ) as prop_demand on prop_demand.ward_mstr_id=view_ward_mstr.id  


            order by (substring(ward_no, '^[0-9]+'))::int,ward_no
            ";


        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();

        return $result;
            
    }
    
    /* public function viewAllModuleDcbNew($last_date,$curr_last_date,$fy_mstr_id,$water_where,$prop_where)
    {
        $demand_water_where=null;
        $coll_water_where=null;
        if($water_where!="")
        {
            $demand_water_where=" where $water_where";
            $coll_water_where=" and $water_where";
        }
         $sql="SELECT view_ward_mstr.id,view_ward_mstr.ward_no,
        COALESCE(((demand.arrear_demand-prev_coll.prev_coll_amt)+demand.current_demand), 0::numeric) AS water_demand,COALESCE((coll.arrear_collection+coll.curr_collection), 0::numeric) AS water_coll,
            COALESCE((prop_arrear_demand+prop_current_demand),0) as prop_demand,COALESCE((prop_arrear_coll+prop_current_coll),0) as prop_coll
            
            FROM view_ward_mstr
             LEFT JOIN 
             ( SELECT tbl_consumer_demand.ward_mstr_id,
                sum(
                    CASE
                        WHEN tbl_consumer_demand.demand_upto <= '$last_date'::date  THEN tbl_consumer_demand.amount
                        ELSE NULL::numeric
                    END) AS arrear_demand,
                sum(
                    CASE
                        WHEN tbl_consumer_demand.demand_upto > '$last_date'::date AND tbl_consumer_demand.demand_upto <= '$curr_last_date'::date THEN tbl_consumer_demand.amount
                        ELSE NULL::numeric
                    END) AS current_demand
                FROM tbl_consumer_demand
                JOIN tbl_consumer ON tbl_consumer.id = tbl_consumer_demand.consumer_id
                $demand_water_where
                GROUP BY tbl_consumer_demand.ward_mstr_id
            ) demand ON demand.ward_mstr_id = view_ward_mstr.id

             LEFT JOIN ( SELECT tbl_consumer_collection.ward_mstr_id,
                sum(
                    CASE
                        WHEN tbl_consumer_demand.demand_upto <= '$last_date'::date THEN tbl_consumer_collection.amount
                        ELSE NULL::numeric
                    END) AS arrear_collection,
                sum(
                    CASE
                        WHEN tbl_consumer_demand.demand_upto > '$last_date'::date AND tbl_consumer_demand.demand_upto <= '$curr_last_date'::date THEN tbl_consumer_collection.amount
                        ELSE NULL::numeric
                    END) AS curr_collection
                FROM tbl_consumer_collection
            
                JOIN tbl_consumer_demand ON tbl_consumer_demand.id = tbl_consumer_collection.demand_id 
                join tbl_transaction on tbl_transaction.id=tbl_consumer_collection.transaction_id 
                join tbl_consumer on tbl_consumer.id=tbl_consumer_collection.consumer_id
              
                where transaction_date>'$last_date' and transaction_date<='$curr_last_date' $coll_water_where
                GROUP BY tbl_consumer_collection.ward_mstr_id) coll ON coll.ward_mstr_id = view_ward_mstr.id
                
                left join (select tbl_consumer_collection.ward_mstr_id,sum(tbl_consumer_collection.amount) as prev_coll_amt
               from tbl_consumer_collection join tbl_transaction on tbl_transaction.id=
               tbl_consumer_collection.transaction_id join tbl_consumer_demand on tbl_consumer_demand.id=
               tbl_consumer_collection.demand_id
               join tbl_consumer on tbl_consumer.id=tbl_consumer_collection.consumer_id
              
                where transaction_date<='$last_date' and 
               transaction_type='Demand Collection' $coll_water_where
               group by tbl_consumer_collection.ward_mstr_id
              ) as prev_coll on prev_coll.ward_mstr_id=view_ward_mstr.id


            left join 
            (

                select ward_mstr_id,prop_arrear_demand,prop_current_demand,prop_arrear_coll,prop_current_coll,
                prop_prev_coll_amt from 
                dblink(
                'host=".getenv('db.pgsql.hname')." user=".getenv('db.pgsql.uname')." password=".getenv('db.pgsql.pass')." port=".getenv('db.pgsql.port')." dbname=db_rmc_property'::text,

                'select demand1.ward_mstr_id,(prop_arrear_demand-prop_prev_coll_amt) as prop_arr_demand,prop_current_demand,prop_arrear_coll,prop_current_coll,prop_prev_coll_amt

                 from (select tbl_prop_demand.ward_mstr_id,
                 sum(
                    CASE
                        WHEN fy_mstr_id < $fy_mstr_id and tbl_prop_demand.status=1 THEN amount
                        ELSE NULL::numeric
                    END) AS prop_arrear_demand,
                 sum(
                    CASE
                        WHEN fy_mstr_id = $fy_mstr_id and tbl_prop_demand.status=1 THEN amount
                        ELSE NULL::numeric
                    END) AS prop_current_demand

                FROM tbl_prop_demand
                JOIN tbl_prop_dtl ON tbl_prop_dtl.id = tbl_prop_demand.prop_dtl_id
                 $prop_where
                GROUP BY tbl_prop_demand.ward_mstr_id) as demand1

                left join 
                (
                    select tbl_collection.ward_mstr_id,
                    sum(
                        CASE
                            WHEN fy_mstr_id < $fy_mstr_id and tbl_collection.status=1 THEN amount
                            ELSE NULL::numeric
                        END) AS prop_arrear_coll,
                    sum(
                        CASE
                            WHEN fy_mstr_id = $fy_mstr_id  and tbl_collection.status=1 THEN amount
                            ELSE NULL::numeric
                        END) AS prop_current_coll
                    FROM tbl_collection
                    JOIN tbl_prop_dtl ON tbl_prop_dtl.id = tbl_collection.prop_dtl_id
                    join tbl_transaction on tbl_transaction.id=tbl_collection.transaction_id 
                     $prop_where and tran_date>''$last_date'' and tran_date<=''$curr_last_date'' and tran_type=''Property''

                    GROUP BY tbl_collection.ward_mstr_id
                ) as coll on coll.ward_mstr_id=demand1.ward_mstr_id

                left join 

                (
                    select tbl_collection.ward_mstr_id,
                   
                    sum(amount) AS prop_prev_coll_amt
                    FROM tbl_collection
                    JOIN tbl_prop_dtl ON tbl_prop_dtl.id = tbl_collection.prop_dtl_id
                    join tbl_transaction on tbl_transaction.id=tbl_collection.transaction_id 
                    $prop_where and tran_date<=''$last_date'' and tran_type=''Property''

                    GROUP BY tbl_collection.ward_mstr_id
                ) as prev_coll on prev_coll.ward_mstr_id=demand1.ward_mstr_id

                '::text

                ) tbl_prop_demand (ward_mstr_id bigint,prop_arrear_demand numeric,prop_current_demand numeric,prop_arrear_coll numeric,prop_current_coll numeric,prop_prev_coll_amt numeric)
                

            ) as prop_demand on prop_demand.ward_mstr_id=view_ward_mstr.id


            order by (substring(ward_no, '^[0-9]+'))::int,ward_no
            ";

        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();

        return $result;
            
    } 
    */
    public function viewAllModuleDcbNew($fy_start_date,$fy_end_date,$fy_mstr_id,$water_where,$prop_where)
    {
        $demand_water_where=null;
        $coll_water_where=null;
        if($water_where!="")
        {
            $demand_water_where=" where $water_where";
            $coll_water_where=" and $water_where";
        }
         $sql="SELECT view_ward_mstr.id,view_ward_mstr.ward_no,
        COALESCE(((demand.arrear_demand-prev_coll.prev_coll_amt)+demand.current_demand), 0::numeric) AS water_demand,COALESCE((coll.arrear_collection+coll.curr_collection), 0::numeric) AS water_coll,
            COALESCE((prop_arrear_demand+prop_current_demand),0) as prop_demand,COALESCE((prop_arrear_coll+prop_current_coll),0) as prop_coll
            
            FROM view_ward_mstr
             LEFT JOIN 
             ( SELECT tbl_consumer_demand.ward_mstr_id,
                sum(
                    CASE
                        WHEN tbl_consumer_demand.demand_upto < '$fy_start_date'::date  THEN tbl_consumer_demand.amount
                        ELSE NULL::numeric
                    END) AS arrear_demand,
                sum(
                    CASE
                        WHEN tbl_consumer_demand.demand_upto between '$fy_start_date' and '$fy_end_date' THEN tbl_consumer_demand.amount
                        ELSE NULL::numeric
                    END) AS current_demand
                FROM tbl_consumer_demand
                JOIN tbl_consumer ON tbl_consumer.id = tbl_consumer_demand.consumer_id
                $demand_water_where
                GROUP BY tbl_consumer_demand.ward_mstr_id
            ) demand ON demand.ward_mstr_id = view_ward_mstr.id

             LEFT JOIN ( SELECT tbl_consumer_collection.ward_mstr_id,
                sum(
                    CASE
                        WHEN tbl_consumer_demand.demand_upto <'$fy_start_date' THEN tbl_consumer_collection.amount
                        ELSE NULL::numeric
                    END) AS arrear_collection,
                sum(
                    CASE
                        WHEN tbl_consumer_demand.demand_upto between '$fy_start_date' and '$fy_end_date' THEN tbl_consumer_collection.amount
                        ELSE NULL::numeric
                    END) AS curr_collection
                FROM tbl_consumer_collection
            
                JOIN tbl_consumer_demand ON tbl_consumer_demand.id = tbl_consumer_collection.demand_id 
                join tbl_transaction on tbl_transaction.id=tbl_consumer_collection.transaction_id 
                join tbl_consumer on tbl_consumer.id=tbl_consumer_collection.consumer_id
              
                where transaction_date between '$fy_start_date' and '$fy_end_date' $coll_water_where
                GROUP BY tbl_consumer_collection.ward_mstr_id) coll ON coll.ward_mstr_id = view_ward_mstr.id
                
                left join (select tbl_consumer_collection.ward_mstr_id,sum(tbl_consumer_collection.amount) as prev_coll_amt
               from tbl_consumer_collection join tbl_transaction on tbl_transaction.id=
               tbl_consumer_collection.transaction_id join tbl_consumer_demand on tbl_consumer_demand.id=
               tbl_consumer_collection.demand_id
               join tbl_consumer on tbl_consumer.id=tbl_consumer_collection.consumer_id
              
                where transaction_date<'$fy_start_date' and 
               transaction_type='Demand Collection' $coll_water_where
               group by tbl_consumer_collection.ward_mstr_id
              ) as prev_coll on prev_coll.ward_mstr_id=view_ward_mstr.id


            left join 
            (

                select ward_mstr_id,prop_arrear_demand,prop_current_demand,prop_arrear_coll,prop_current_coll,
                prop_prev_coll_amt from 
                dblink(
                'host=".getenv('db.pgsql.hname')." user=".getenv('db.pgsql.uname')." password=".getenv('db.pgsql.pass')." port=".getenv('db.pgsql.port')." dbname=db_rmc_property'::text,

                'select demand1.ward_mstr_id,(prop_arrear_demand-prop_prev_coll_amt) as prop_arr_demand,prop_current_demand,prop_arrear_coll,prop_current_coll,prop_prev_coll_amt

                 from (select tbl_prop_demand.ward_mstr_id,
                 sum(
                    CASE
                        WHEN due_date <''$fy_start_date'' and tbl_prop_demand.status=1 THEN amount
                        ELSE NULL::numeric
                    END) AS prop_arrear_demand,
                 sum(
                    CASE
                        WHEN due_date between ''$fy_start_date'' and ''$fy_end_date'' and tbl_prop_demand.status=1 THEN amount
                        ELSE NULL::numeric
                    END) AS prop_current_demand

                FROM tbl_prop_demand
                JOIN tbl_prop_dtl ON tbl_prop_dtl.id = tbl_prop_demand.prop_dtl_id
                 $prop_where
                GROUP BY tbl_prop_demand.ward_mstr_id) as demand1

                left join 
                (
                    select tbl_collection.ward_mstr_id,
                    sum(
                        CASE
                            WHEN due_date <''$fy_start_date'' and tbl_collection.status=1 THEN amount
                            ELSE NULL::numeric
                        END) AS prop_arrear_coll,
                    sum(
                        CASE
                            WHEN due_date between ''$fy_start_date'' and ''$fy_end_date''  and tbl_collection.status=1 THEN amount
                            ELSE NULL::numeric
                        END) AS prop_current_coll
                    FROM tbl_collection
                    JOIN tbl_prop_dtl ON tbl_prop_dtl.id = tbl_collection.prop_dtl_id
                    join tbl_transaction on tbl_transaction.id=tbl_collection.transaction_id 
                     $prop_where and tran_date between ''$fy_start_date'' and ''$fy_end_date'' and tran_type=''Property''

                    GROUP BY tbl_collection.ward_mstr_id
                ) as coll on coll.ward_mstr_id=demand1.ward_mstr_id

                left join 

                (
                    select tbl_collection.ward_mstr_id,
                   
                    sum(amount) AS prop_prev_coll_amt
                    FROM tbl_collection
                    JOIN tbl_prop_dtl ON tbl_prop_dtl.id = tbl_collection.prop_dtl_id
                    join tbl_transaction on tbl_transaction.id=tbl_collection.transaction_id 
                    $prop_where and tran_date<''$fy_start_date'' and tran_type=''Property''

                    GROUP BY tbl_collection.ward_mstr_id
                ) as prev_coll on prev_coll.ward_mstr_id=demand1.ward_mstr_id

                '::text

                ) tbl_prop_demand (ward_mstr_id bigint,prop_arrear_demand numeric,prop_current_demand numeric,prop_arrear_coll numeric,prop_current_coll numeric,prop_prev_coll_amt numeric)
                

            ) as prop_demand on prop_demand.ward_mstr_id=view_ward_mstr.id


            order by (substring(ward_no, '^[0-9]+'))::int,ward_no
            ";

        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //echo $this->getLastQuery();

        return $result;
            
    }

    public function row_sql($sql)
    {
        $run=$this->db->query($sql);
        $result=$run->getResultArray();

        return $result;
    }
}


