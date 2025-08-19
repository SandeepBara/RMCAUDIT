<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class TCVisitingModel extends Model
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
    public function insertData($data)
    {
        $result= $this->db->table($this->table)
                 ->insert($data);       
        //echo $this->getLastQuery();
        $insert_id=$this->db->insertID();
        return $insert_id;
    }
    public function getTCVisitingDetails($where)
    {
        $sql="select distinct(user_id) as user_id,emp_name,visiting_date from tbl_tc_visiting join tbl_emp_details on tbl_emp_details.id=tbl_tc_visiting.user_id where $where";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        //print_r($result);
        //echo $this->getLastQuery();
        return $result;

    }
    public function getTCvisitingdetailspropertyreport($emp_id)
    {
        $sql="select case when message_id=0 then tbl_tc_visiting.other_reason else 
tbl_feedback_message.message end as message, * from tbl_tc_visiting join 
            ( select id,ward_no,holding_no from 
            dblink(host=".getenv('db.pgsql.hname')." user=".getenv('db.pgsql.uname')." password=".getenv('db.pgsql.pass')." dbname=db_rmc_property'::text, 
            'SELECT  tbl_prop_dtl.id as id, ward_no, holding_no FROM tbl_prop_dtl left join 
            view_ward_mstr on view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id '::text) 
             tbl_prop_dtl(id bigint, ward_no text, holding_no text)) as prop on prop.id=
            tbl_tc_visiting.related_id 
            full join tbl_feedback_message on tbl_feedback_message.id=tbl_tc_visiting.message_id

            where upper(module)='PROPERTY' and user_id=$emp_id";

        $run=$this->query($sql);
        $result=$run->getResultArray();
        // echo $this->getLastQuery();

        return $result;



    }
    public function getTCvisitingdetailssafreport($emp_id)
    {
       
        $sql="select *,saf_no from tbl_tc_visiting join 
            ( select id,ward_no,saf_no from 
            dblink(host=".getenv('db.pgsql.hname')." user=".getenv('db.pgsql.uname')." password=".getenv('db.pgsql.pass')." dbname=db_rmc_property'::text, 
            'SELECT  tbl_saf_dtl.id as id, ward_no, saf_no FROM tbl_saf_dtl left join 
            view_ward_mstr on view_ward_mstr.id=tbl_saf_dtl.ward_mstr_id '::text) 
             tbl_saf_dtl(id bigint, ward_no text, saf_no text)) as saf on saf.id=
            tbl_tc_visiting.related_id 
            full join tbl_feedback_message on tbl_feedback_message.id=tbl_tc_visiting.message_id

            where upper(module)='SAF' and user_id=$emp_id";

        $run=$this->query($sql);
        $result=$run->getResultArray();
         echo $this->getLastQuery();
        return $result;



    }
    public function getTCvisitingdetailswaterreport($emp_id)
    {
        $sql="select * from tbl_tc_visiting join 
            ( select id,ward_no,application_no from 
            dblink(host=".getenv('db.pgsql.hname')." user=".getenv('db.pgsql.uname')." password=".getenv('db.pgsql.pass')." dbname=db_rmc_water'::text, 
            'SELECT  tbl_apply_water_connection.id as id, ward_no, application_no FROM tbl_apply_water_connection left join 
            view_ward_mstr on view_ward_mstr.id=tbl_apply_water_connection.ward_id '::text) 
             tbl_apply_water_connection(id bigint, ward_no text, application_no text)) as application on application.id=
            tbl_tc_visiting.related_id where upper(module)='WATER APPLICATION'  and user_id=$emp_id";

        $run=$this->query($sql);
        $result=$run->getResultArray();
        return $result;



    }
     public function getTCvisitingdetailswaterconsumerreport($emp_id)
    {
        $sql="select * from tbl_tc_visiting join 
            ( select id,ward_no,consumer_no from 
            dblink(host=".getenv('db.pgsql.hname')." user=".getenv('db.pgsql.uname')." password=".getenv('db.pgsql.pass')." dbname=db_rmc_water'::text, 
            'SELECT  tbl_consumer.id as id, ward_no, consumer_no FROM tbl_consumer left join 
            view_ward_mstr on view_ward_mstr.id=tbl_consumer.ward_id '::text) 
             tbl_consumer(id bigint, ward_no text, consumer_no text)) as consumer on consumer.id=
            tbl_tc_visiting.related_id where upper(module)='WATER CONSUMER' and user_id=$emp_id";

        $run=$this->query($sql);
        $result=$run->getResultArray();
        return $result;



    }
     public function getTCvisitingdetailstradereport($emp_id)
    {
        $sql="select * from tbl_tc_visiting join 
            ( select id,ward_no,holding_no from 
            dblink(host=".getenv('db.pgsql.hname')." user=".getenv('db.pgsql.uname')." password=".getenv('db.pgsql.pass')." dbname=db_rmc_trade'::text, 
            'SELECT  tbl_prop_dtl.id as id, ward_no, holding_no FROM tbl_prop_dtl left join 
            view_ward_mstr on view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id '::text) 
             tbl_prop_dtl(id bigint, ward_no text, holding_no text)) as prop on prop.id=
            tbl_tc_visiting.related_id where upper(module)='TRADE'";

        $run=$this->query($sql);
        $result=$run->getResultArray();
        return $result;



    }
    
}