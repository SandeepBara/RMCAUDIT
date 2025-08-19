<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class WaterCashVerificationModel extends Model
{

  protected $table = 'tbl_apply_water_connection';

  public function __construct(ConnectionInterface $db)
  {
      $this->db = $db;
      $session = session();
      $this->db_link = $session->get('ulb_dtl');
  }

  public function TodaysCollection($trans_date, $employee_id=null)
  {
      $sql="with coll as 
      (
          select view_emp_details.id, view_emp_details.emp_name, coalesce(sum(pamount), 0.00) as prop_saf, coalesce(sum(gsaf_amount), 0.00) as gsaf, coalesce(sum(paid_amount_water), 0.00) as water,
          coalesce(sum(paid_amount_trade), 0.00) as trade, sum(coalesce(pamount,0)+coalesce(gsaf_amount,0)+coalesce(paid_amount_water,0)+coalesce(paid_amount_trade,0)) as total
          
          from view_emp_details 
          left join (
            
                select tran_by_emp_details_id, coalesce(sum(payable_amt), 0.00) as pamount 
                from tbl_transaction 
              where status in (1, 2) and tran_date='$trans_date' 
              group by tran_by_emp_details_id
            
            ) as p on p.tran_by_emp_details_id=view_emp_details.id
            left join (
              
              select tran_by_emp_details_id, coalesce(sum(payable_amt), 0.00) as gsaf_amount from 
                tbl_govt_saf_transaction where status in (1, 2) and tran_date='$trans_date' 
              group by tran_by_emp_details_id 
            
            ) gsaf  on gsaf.tran_by_emp_details_id=view_emp_details.id 
          left join (
            
              select emp_details_id,paid_amount_water 
              from dblink('host=".getenv('db.pgsql.hname')." user=".getenv('db.pgsql.uname')." password=".getenv('db.pgsql.pass')." port=".getenv('db.pgsql.port')." dbname=".$this->db_link["water"]."'::text,
                'select emp_details_id,coalesce(sum(paid_amount),0) as paid_amount_water 
              from tbl_transaction  where status in (1,2) and transaction_date=''$trans_date'' group by emp_details_id'::text
              ) water 
              (emp_details_id bigint,paid_amount_water numeric)
            
            )  w on w.emp_details_id=view_emp_details.id
          left join (
            
              select emp_details_id,paid_amount_trade 
              from dblink('host=".getenv('db.pgsql.hname')." user=".getenv('db.pgsql.uname')." password=".getenv('db.pgsql.pass')." port=".getenv('db.pgsql.port')." dbname=".$this->db_link["trade"]."'::text,
              'select emp_details_id,coalesce(sum(paid_amount),0) as paid_amount_trade 
              from tbl_transaction where status in(1,2) and transaction_date=''$trans_date'' group by emp_details_id '::text
              ) trade (emp_details_id bigint,paid_amount_trade numeric) 
            
            ) t on t.emp_details_id=view_emp_details.id
    
            group by view_emp_details.id,view_emp_details.emp_name
      )
       select id as tran_by_emp_details_id, emp_name, '$trans_date'::date as transaction_date, coalesce(prop_saf, 0.00) as prop_saf, coalesce(gsaf, 0.00) as gsaf, coalesce(water, 0.00) as water, coalesce(trade, 0.00) as trade, total                        
       from coll where total>0";

      if($employee_id!=null){

        $sql.=" and id=$employee_id";
      }
      //print_var($sql);exit;
      $run=$this->db->query($sql);
      $result=$run->getResultArray();
      //echo $this->getLastQuery();
      return $result;
  }


  public function TodaysVerifiedCollection($trans_date, $employee_id=null)
  {
      $sql="with coll as 
      (
          select view_emp_details.id, view_emp_details.emp_name, coalesce(sum(pamount), 0.00) as prop_saf, coalesce(sum(gsaf_amount), 0.00) as gsaf, coalesce(sum(paid_amount_water), 0.00) as water,
          coalesce(sum(paid_amount_trade), 0.00) as trade, sum(coalesce(pamount,0)+coalesce(gsaf_amount,0)+coalesce(paid_amount_water,0)+coalesce(paid_amount_trade,0)) as total
          
          from view_emp_details 
          left join (
            
                select tran_by_emp_details_id, coalesce(sum(payable_amt), 0.00) as pamount 
                from tbl_transaction 
              where status in (1, 2) and tran_date='$trans_date' and verify_status=1
              group by tran_by_emp_details_id
            
            ) as p on p.tran_by_emp_details_id=view_emp_details.id
            left join (
              
              select tran_by_emp_details_id, coalesce(sum(payable_amt), 0.00) as gsaf_amount from 
                tbl_govt_saf_transaction where status in (1, 2) and tran_date='$trans_date'  and tran_verification_status=1
              group by tran_by_emp_details_id 
            
            ) gsaf  on gsaf.tran_by_emp_details_id=view_emp_details.id 
          left join (
            
              select emp_details_id,paid_amount_water 
              from dblink('host=".getenv('db.pgsql.hname')." user=".getenv('db.pgsql.uname')." password=".getenv('db.pgsql.pass')." port=".getenv('db.pgsql.port')." dbname=".$this->db_link["water"]."'::text,
                'select emp_details_id,coalesce(sum(paid_amount),0) as paid_amount_water 
              from tbl_transaction  where status in (1,2) and transaction_date=''$trans_date''  and verify_status=1 group by emp_details_id'::text
              ) water 
              (emp_details_id bigint,paid_amount_water numeric)
            
            )  w on w.emp_details_id=view_emp_details.id
          left join (
            
              select emp_details_id,paid_amount_trade 
              from dblink('host=".getenv('db.pgsql.hname')." user=".getenv('db.pgsql.uname')." password=".getenv('db.pgsql.pass')." port=".getenv('db.pgsql.port')." dbname=".$this->db_link["trade"]."'::text,
              'select emp_details_id,coalesce(sum(paid_amount),0) as paid_amount_trade 
              from tbl_transaction where status in(1,2) and transaction_date=''$trans_date''  and verify_status=1 group by emp_details_id '::text
              ) trade (emp_details_id bigint,paid_amount_trade numeric) 
            
            ) t on t.emp_details_id=view_emp_details.id
    
            group by view_emp_details.id,view_emp_details.emp_name
      )
       select id as tran_by_emp_details_id, emp_name, '$trans_date'::date as transaction_date, coalesce(prop_saf, 0.00) as prop_saf, coalesce(gsaf, 0.00) as gsaf, coalesce(water, 0.00) as water, coalesce(trade, 0.00) as trade, total                        
       from coll where total>0";

      if($employee_id!=null){

        $sql.=" and id=$employee_id";
      }
      //print_var($sql);
      $run=$this->db->query($sql);
      $result=$run->getResultArray();
      //echo $this->getLastQuery();
      return $result;
  }

  
  public function totalCollectionToday($trans_date, $employee_id=null)
  {
      $sql="with coll as 
            (
                  select view_emp_details.id, view_emp_details.emp_name, coalesce(sum(pamount), 0.00) as prop_saf, coalesce(sum(gsaf_amount), 0.00) as gsaf, coalesce(sum(paid_amount_water), 0.00) as water,
                  coalesce(sum(paid_amount_trade), 0.00) as trade, sum(coalesce(pamount,0)+coalesce(gsaf_amount,0)+coalesce(paid_amount_water,0)+coalesce(paid_amount_trade,0)) as total
                from view_emp_details 
                left join (
                      select tran_by_emp_details_id,coalesce(sum(payable_amt),0) as pamount 
                      from tbl_transaction where status in(1,2) and verify_status is NULL and tran_date='$trans_date'  group by tran_by_emp_details_id
                    ) as p on p.tran_by_emp_details_id=view_emp_details.id
                    left join (
                        select tran_by_emp_details_id,coalesce(sum(payable_amt),0) as gsaf_amount from 
                      tbl_govt_saf_transaction where status in(1,2) and tran_verification_status is NULL and tran_date='$trans_date' group by tran_by_emp_details_id 
                    ) gsaf  on gsaf.tran_by_emp_details_id=view_emp_details.id 
                left join (
                      select emp_details_id,paid_amount_water 
                      from dblink('host=".getenv('db.pgsql.hname')." user=".getenv('db.pgsql.uname')." password=".getenv('db.pgsql.pass')." port=".getenv('db.pgsql.port')." dbname=".$this->db_link["water"]."'::text,
                            'select emp_details_id,coalesce(sum(paid_amount),0) as paid_amount_water 
                      from tbl_transaction  where status in(1,2) and verify_status is NULL and transaction_date=''$trans_date'' group by emp_details_id'::text
                      ) water 
                      (emp_details_id bigint,paid_amount_water numeric)
                    )  w on w.emp_details_id=view_emp_details.id
                left join (
                      select emp_details_id,paid_amount_trade 
                    from dblink('host=".getenv('db.pgsql.hname')." user=".getenv('db.pgsql.uname')." password=".getenv('db.pgsql.pass')." port=".getenv('db.pgsql.port')." dbname=".$this->db_link["trade"]."'::text,
                        'select emp_details_id,coalesce(sum(paid_amount),0) as paid_amount_trade 
                        from tbl_transaction where status in(1,2)  and verify_status is NULL and transaction_date=''$trans_date'' group by emp_details_id '::text
                        ) trade (emp_details_id bigint,paid_amount_trade numeric) 
                    ) t on t.emp_details_id=view_emp_details.id
               
                    group by view_emp_details.id,view_emp_details.emp_name
            )
           select id as tran_by_emp_details_id, emp_name,' $trans_date' as transaction_date, coalesce(prop_saf, 0.00) as prop_saf, coalesce(gsaf, 0.00) as gsaf, coalesce(water, 0.00) as water, coalesce(trade, 0.00) as trade, total                        
           from coll where total>0";

      if($employee_id!=null){

        $sql.=" and id=$employee_id";
      }
      //print_var($sql);
      $run=$this->db->query($sql);
      $result=$run->getResultArray();
      //echo $this->getLastQuery();
      return $result;
  }

 /* public function totalCashCollectedbyEmpId($emp_id,$trans_date)
  {
       $sql="with coll as 
            ( select p.tran_by_emp_details_id,sum(pamount) as prop_saf,sum(gsaf_amount) as gsaf,sum(paid_amount_water) as water,
            sum(paid_amount_trade) as trade,sum(coalesce(pamount,0)+coalesce(gsaf_amount,0)+coalesce(paid_amount_water,0)+coalesce(paid_amount_trade,0)) as total from 
            
           (select tran_by_emp_details_id,coalesce(sum(payable_amt-round_off),0) as pamount from tbl_transaction where
           status in(1,2) and verify_status is NULL and tran_date='$trans_date' and tran_mode_mstr_id=1 and md5(tran_by_emp_details_id::text)='$emp_id'  group by tran_by_emp_details_id) as p

           left join (select tran_by_emp_details_id,coalesce(sum(payable_amt-round_off),0) as gsaf_amount from tbl_govt_saf_transaction 
           where status in(1,2) and tran_verification_status is NULL and tran_date='$trans_date' and tran_mode_mstr_id=1 and md5(tran_by_emp_details_id::text)='$emp_id' group by tran_by_emp_details_id ) gsaf 
           on gsaf.tran_by_emp_details_id=p.tran_by_emp_details_id left join 

           (select emp_details_id,paid_amount_water from dblink('host=".getenv('db.pgsql.hname')." user=".getenv('db.pgsql.uname')." password=".getenv('db.pgsql.pass')." port=".getenv('db.pgsql.port')." dbname=db_rmc_water'::text,
           'select emp_details_id,coalesce(sum(paid_amount),0) as paid_amount_water from tbl_transaction  where status in(1,2) and verify_status is NULL and transaction_date=''$trans_date'' and md5(emp_details_id::text)=''$emp_id'' and upper(payment_mode)=''CASH''  group by emp_details_id
          '::text) water (emp_details_id bigint,paid_amount_water numeric) ) 
           w on w.emp_details_id=p.tran_by_emp_details_id

           left join ( select emp_details_id,paid_amount_trade from dblink('host=".getenv('db.pgsql.hname')." user=".getenv('db.pgsql.uname')." password=".getenv('db.pgsql.pass')." port=".getenv('db.pgsql.port')." dbname=db_rmc_trade'::text,
           'select emp_details_id,coalesce(sum(paid_amount),0) as paid_amount_trade from tbl_transaction where status in(1,2)  and verify_status is NULL and transaction_date=''$trans_date'' and md5(emp_details_id::text)=''$emp_id'' and upper(payment_mode)=''CASH'' group by emp_details_id '::text)
           trade (emp_details_id bigint,paid_amount_trade numeric) ) t 
           on t.emp_details_id=p.tran_by_emp_details_id

           group by p.tran_by_emp_details_id

           )
           select tran_by_emp_details_id,emp_name,'$trans_date' as transaction_date,prop_saf,gsaf,water,trade,total from coll 
           join view_emp_details on view_emp_details.id=coll.tran_by_emp_details_id
        ";
      $run=$this->db->query($sql);
      $result=$run->getFirstRow("array");
      //echo $this->getLastQuery();
      return $result;
  }
*/

  public function totalCashCollectedbyEmpId($emp_id,$trans_date)
  {
      // $db_water='db_rmc_water';
	    // $db_trade='db_rmc_trade'; $this->db_link["trade"]
        $db_water= $this->db_link["water"];
        $db_trade= $this->db_link["trade"];

       $sql="with coll as 
            (
              select view_emp_details.id,view_emp_details.emp_name, coalesce(sum(pamount), 0) as prop_saf, coalesce(sum(gsaf_amount), 0) as gsaf, coalesce(sum(paid_amount_water), 0) as water,
              sum(paid_amount_trade) as trade,sum(coalesce(pamount,0)+coalesce(gsaf_amount,0)+coalesce(paid_amount_water,0)+coalesce(paid_amount_trade,0)) as total 
              from  view_emp_details 
		          left join (
                          select tran_by_emp_details_id,coalesce(sum(payable_amt),0) as pamount 
		                      from tbl_transaction 
                          where status in(1,2) and verify_status is NULL and tran_date='$trans_date' and tran_mode_mstr_id=1 
		                      and md5(tran_by_emp_details_id::text)='$emp_id' group by tran_by_emp_details_id
                        ) as p on p.tran_by_emp_details_id=view_emp_details.id
              left join (
                          select tran_by_emp_details_id,coalesce(sum(payable_amt),0) as gsaf_amount 
                          from tbl_govt_saf_transaction 
                          where status in(1,2) and tran_verification_status is NULL and tran_date='$trans_date'  
                          and tran_mode_mstr_id=1 and md5(tran_by_emp_details_id::text)='$emp_id' group by tran_by_emp_details_id
                        ) as gsaf on gsaf.tran_by_emp_details_id=view_emp_details.id 
		          left join (
                            select emp_details_id,paid_amount_water 
                            from dblink('host=".getenv('db.pgsql.hname')." user=".getenv('db.pgsql.uname')." password=".getenv('db.pgsql.pass')." port=".getenv('db.pgsql.port')." dbname=$db_water'::text,
                            'select emp_details_id,coalesce(sum(paid_amount),0) as paid_amount_water from tbl_transaction  where status in(1,2) and verify_status is NULL and transaction_date=''$trans_date'' and md5(emp_details_id::text)=''$emp_id'' and upper(payment_mode)=''CASH'' group by emp_details_id
                            '::text) water (emp_details_id bigint,paid_amount_water numeric)
                        )  w on w.emp_details_id=view_emp_details.id
              left join (
                              select emp_details_id,paid_amount_trade from dblink('host=".getenv('db.pgsql.hname')." user=".getenv('db.pgsql.uname')." password=".getenv('db.pgsql.pass')." port=".getenv('db.pgsql.port')." dbname=$db_trade'::text,
                              'select emp_details_id,coalesce(sum(paid_amount),0) as paid_amount_trade from tbl_transaction where status in(1,2)  and verify_status is NULL and transaction_date=''$trans_date'' and md5(emp_details_id::text)=''$emp_id'' and upper(payment_mode)=''CASH'' group by emp_details_id '::text)
                              trade (emp_details_id bigint,paid_amount_trade numeric) 
                        ) t on t.emp_details_id=view_emp_details.id
              group by view_emp_details.id,view_emp_details.emp_name

           )
           select id as tran_by_emp_details_id,emp_name,'$trans_date' as transaction_date,prop_saf,gsaf,water,trade,total from coll 
           where total>0
        ";
      //print_var($sql);
      $run=$this->db->query($sql);
      $result=$run->getFirstRow("array");
      //echo $this->getLastQuery();
      return $result;
  }

/*  public function totalCashCollectedbyEmpIds($emp_id,$trans_date)
  {
       $sql="with coll as 
            ( select p.tran_by_emp_details_id,sum(pamount) as prop_saf,sum(gsaf_amount) as gsaf,sum(paid_amount_water) as water,
            sum(paid_amount_trade) as trade,sum(coalesce(pamount,0)+coalesce(gsaf_amount,0)+coalesce(paid_amount_water,0)+coalesce(paid_amount_trade,0)) as total from 
            
           (select tran_by_emp_details_id,coalesce(sum(payable_amt-round_off),0) as pamount from tbl_transaction where
           status in(1,2) and verify_status is NULL and tran_date='$trans_date' and md5(tran_by_emp_details_id::text)='$emp_id'  group by tran_by_emp_details_id) as p

           left join (select tran_by_emp_details_id,coalesce(sum(payable_amt-round_off),0) as gsaf_amount from tbl_govt_saf_transaction 
           where status in(1,2) and tran_verification_status is NULL and tran_date='$trans_date' and md5(tran_by_emp_details_id::text)='$emp_id' group by tran_by_emp_details_id ) gsaf 
           on gsaf.tran_by_emp_details_id=p.tran_by_emp_details_id left join 

           (select emp_details_id,paid_amount_water from dblink('host=".getenv('db.pgsql.hname')." user=".getenv('db.pgsql.uname')." password=".getenv('db.pgsql.pass')." port=".getenv('db.pgsql.port')." dbname=db_rmc_water'::text,
           'select emp_details_id,coalesce(sum(paid_amount),0) as paid_amount_water from tbl_transaction  where status in(1,2) and verify_status is NULL and transaction_date=''$trans_date'' and md5(emp_details_id::text)=''$emp_id''   group by emp_details_id
          '::text) water (emp_details_id bigint,paid_amount_water numeric) ) 
           w on w.emp_details_id=p.tran_by_emp_details_id

           left join ( select emp_details_id,paid_amount_trade from dblink('host=".getenv('db.pgsql.hname')." user=".getenv('db.pgsql.uname')." password=".getenv('db.pgsql.pass')." port=".getenv('db.pgsql.port')." dbname=db_rmc_trade'::text,
           'select emp_details_id,coalesce(sum(paid_amount),0) as paid_amount_trade from tbl_transaction where status in(1,2)  and verify_status is NULL and transaction_date=''$trans_date'' and md5(emp_details_id::text)=''$emp_id'' group by emp_details_id '::text)
           trade (emp_details_id bigint,paid_amount_trade numeric) ) t 
           on t.emp_details_id=p.tran_by_emp_details_id

           group by p.tran_by_emp_details_id

           )
           select tran_by_emp_details_id,emp_name,'$trans_date' as transaction_date,prop_saf,gsaf,water,trade,total from coll 
           join view_emp_details on view_emp_details.id=coll.tran_by_emp_details_id
        ";
      $run=$this->db->query($sql);
      $result=$run->getFirstRow("array");
      //echo $this->getLastQuery();
      return $result;
  }
  */

   public function totalCashCollectedbyEmpIds($emp_id,$trans_date)
  {
         $sql="with coll as 
            (
              select view_emp_details.id,view_emp_details.emp_name, coalesce(sum(pamount), 0.00) as prop_saf, coalesce(sum(gsaf_amount), 0.00) as gsaf, coalesce(sum(paid_amount_water), 0.00) as water,
              coalesce(sum(paid_amount_trade), 0.00) as trade, sum(coalesce(pamount,0)+coalesce(gsaf_amount,0)+coalesce(paid_amount_water,0)+coalesce(paid_amount_trade,0)) as total 
                from view_emp_details 
                left join (
                      select tran_by_emp_details_id,coalesce(sum(payable_amt),0) as pamount 
                      from tbl_transaction 
                      where status in(1,2) and verify_status is NULL and tran_date='$trans_date' and md5(tran_by_emp_details_id::text)='$emp_id' group by tran_by_emp_details_id
                    ) as p on p.tran_by_emp_details_id=view_emp_details.id
              left join (
                      select tran_by_emp_details_id,coalesce(sum(payable_amt),0) as gsaf_amount 
                      from tbl_govt_saf_transaction 
                      where status in(1,2) and tran_verification_status is NULL and tran_date='$trans_date' and md5(tran_by_emp_details_id::text)='$emp_id' group by tran_by_emp_details_id
                    ) gsaf  on gsaf.tran_by_emp_details_id=view_emp_details.id 
              left join (
                      select emp_details_id,paid_amount_water from dblink('host=".getenv('db.pgsql.hname')." user=".getenv('db.pgsql.uname')." password=".getenv('db.pgsql.pass')." port=".getenv('db.pgsql.port')." dbname=".$this->db_link["water"]."'::text,
                      'select emp_details_id,coalesce(sum(paid_amount),0) as paid_amount_water from tbl_transaction  where status in(1,2) and verify_status is NULL and transaction_date=''$trans_date'' and md5(emp_details_id::text)=''$emp_id''  group by emp_details_id
                      '::text) water (emp_details_id bigint,paid_amount_water numeric)
                    ) w on w.emp_details_id=view_emp_details.id
              left join (
                      select emp_details_id,paid_amount_trade from dblink('host=".getenv('db.pgsql.hname')." user=".getenv('db.pgsql.uname')." password=".getenv('db.pgsql.pass')." port=".getenv('db.pgsql.port')." dbname=".$this->db_link["trade"]."'::text,
                      'select emp_details_id,coalesce(sum(paid_amount),0) as paid_amount_trade from tbl_transaction where status in(1,2)  and verify_status is NULL and transaction_date=''$trans_date'' and md5(emp_details_id::text)=''$emp_id'' group by emp_details_id '::text)
                      trade (emp_details_id bigint,paid_amount_trade numeric)
                    ) t on t.emp_details_id=view_emp_details.id

              group by view_emp_details.id,view_emp_details.emp_name

           )
           select id as tran_by_emp_details_id,emp_name,'$trans_date' as transaction_date,prop_saf,gsaf,water,trade,total 
           from coll where total>0 ";
      //print_var($sql);
      $run=$this->db->query($sql);
      $result=$run->getFirstRow("array");
      //echo $this->getLastQuery();
      return $result;
  }

}
