<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_view_water_consumer;
use App\Models\model_ward_mstr;
use App\Models\water_consumer_details_model;
use App\Models\WaterConsumerDemandModel;
use App\Models\Water_Transaction_Model;
use App\Models\WaterPaymentModel;
use App\Models\WaterMobileModel;


class WaterViewConsumerDueDetails extends AlphaController
{
    protected $water;
    // protected $db;
    protected $dbSystem;
    protected $model_view_water_consumer;
    protected $model_ward_mstr;
    protected $ulb_id;
    protected $user_type_mstr_id;
    protected $emp_id;
    protected $ward_model;
    protected $consumer_details_model;
    protected $consumer_demand_model;
    protected $trans_model;
    protected $payment_model;
    protected $WaterMobileModel;

    
    public function __construct(){
    	
        $session=session();
        $ulb_details=$session->get('ulb_dtl');
        //print_r($ulb_details);
        $this->ulb_id=$ulb_details['ulb_mstr_id'];

        $emp_details=$session->get('emp_details');
        $this->emp_id=$emp_details['id'];
        $this->user_type_mstr_id =$emp_details['user_type_mstr_id'];

        parent::__construct();
        helper(['db_helper','form','utility_helper']);
        if($db_name = dbConfig("water")){
            $this->water = db_connect($db_name);            
        }
        if($db_name = dbSystem()){
            $this->dbSystem = db_connect($db_name); 
        }
         /*if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name); 
        }*/
        $this->model_view_water_consumer = new model_view_water_consumer($this->water);
        $this->ward_model = new model_ward_mstr($this->dbSystem);
        $this->consumer_details_model=new water_consumer_details_model($this->water);

        $this->consumer_demand_model=new WaterConsumerDemandModel($this->water);
        $this->trans_model=new Water_Transaction_Model($this->water);
        $this->payment_model=new WaterPaymentModel($this->water);
        $this->WaterMobileModel=new WaterMobileModel($this->water);


    }
    
    public function index($consumer_id=null)
    {

        $data=array();

        $data['consumer_id']=$consumer_id;

        $data['consumer_details']=$this->model_view_water_consumer->waterConsumerDetailsById($consumer_id);
        $this->consumer_demand_model->impose_penalty($data['consumer_details']['id']);
        $data['applicant_details']=$this->payment_model->fetch_all_application_data(md5($data['consumer_details']['apply_connection_id']));
        $data['consumer_owner_details']=$this->consumer_details_model->consumerDetailsbyMd5($consumer_id);

        $data['due_details']=$this->consumer_demand_model->consumerDueDetails($consumer_id);
        $data['due_summary'] = $this->consumer_demand_model->demand_summary( $data['consumer_details']['id']);
        
        $data['dues']=$this->consumer_demand_model->due_demand($consumer_id);
        $sql_demand_history = " SELECT tbl_consumer_demand.generation_date,paid_status,
                                       amount,penalty,balance_amount,demand_from,demand_upto,
                                       connection_type, current_meter_reading,
                                       tbl_meter_reading_doc.file_name,tbl_meter_reading_doc.meter_no
                                FROM tbl_consumer_demand
                                LEFT JOIN tbl_meter_reading_doc ON tbl_meter_reading_doc.demand_id = tbl_consumer_demand.id 
                                    AND tbl_meter_reading_doc.status = 1
                                WHERE tbl_consumer_demand.status = 1 
                                    AND tbl_consumer_demand.consumer_id=".$data['consumer_details']['id']."
                                ORDER BY demand_from DESC
                                ";
        $data["bemand_history"] = $this->water->query($sql_demand_history)->getResultArray();
        $data['user_type_id'] = $this->user_type_mstr_id;
        return view('water/water_connection/water_consumer_due_details_view',$data);


    }

    public function transactionDetails($consumer_id)
    {

        $data=array();

        $data['consumer_id']=$consumer_id;
        $data['user_type'] = session()->get('emp_details');
        $data['user_type'] = $data['user_type']['user_type_mstr_id']??[];

        $data['consumer_details']=$this->model_view_water_consumer->waterConsumerDetailsById($consumer_id);
        $data['applicant_details']=$this->payment_model->application_data($data['consumer_details']['apply_connection_id']);
        $data['consumer_owner_details']=$this->consumer_details_model->consumerDetails($data['consumer_details']['id']);

        //$data['dues']=$this->consumer_demand_model->due_demand($consumer_id);
        $with_sql = " with demands as ( 
                        select min(d.demand_from) as demand_from , max(d.demand_upto) as demand_upto,
                            c.transaction_id
                        from tbl_consumer_collection c 
                        join tbl_consumer_demand d on d.id = c.demand_id and d.status = 1
                        where c.consumer_id = 19744 and c.status=1
                        group by c.transaction_id 
                    ) ";
        $tr_select_sql = " select t.* ,d.demand_from,d.demand_upto ";
        $from = " from tbl_transaction t 
                  left join demands d on d.transaction_id = t.id                 
                  where t.related_id = " . $data['consumer_details']['id'] . " 
                   and t.transaction_type ='Demand Collection' and status in (1, 2) order by t.transaction_date desc";
        $transection = $this->WaterMobileModel->get_data_10($from,$tr_select_sql,false,$with_sql);
        $data['transaction_details']= $transection['result'];
        $data['count']= $transection['count'];
        $data['offset']= $transection['offset'];
        //$data['transaction_details']=$this->trans_model->getConsumerTransactions($consumer_id);
        //echo"<pre>";print_r($data);echo"</pre>";die;


        return view('water/water_connection/water_consumer_transaction_details_view',$data);

    }
    public function print_demnds($consumer_id)
    {
        $data =(array)null;
        $Session = session();
        $ulb_dtl = $Session->get('ulb_dtl');
        $data['ulb_dtl'] = $ulb_dtl;
        $ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];
        $data['ulb_id']= $ulb_mstr_id;

        $data['ulb_mstr_id']=$this->ulb_id;
        if(!empty($consumer_id))
        {
            $where2 = " AND md5(c.id::text) = '".$consumer_id."'";
            if(is_numeric($consumer_id))
                $where2 = " AND c.id = ".$consumer_id;
            $sql=" 
                with demand as(
                    select c.id,sum(d.amount) as demand,min(demand_from),max(demand_upto),
                    sum(COALESCE(advance_amount,0)) as advance,
                    sum(d.penalty) as penalty,
                    STRING_AGG(distinct(demand_no),', ') as demand_no
                    from tbl_consumer  c 
                    join view_ward_mstr w on w.id = c.ward_mstr_id 
                    join tbl_consumer_demand d on d.consumer_id = c.id 
                    left join tbl_consumer_advance_dtls ad on ad.consumer_id = c.id and ad.status = 1
                    where c.status = 1 and d.status = 1 and d.paid_status = 0 
                    $where2
                    group by c.id
                    ),
                lst_demand_no as (
                    select distinct(demand_no),c.id,d.id as demand_id,		
                        row_number() 
                            over(
                                    partition by d.consumer_id 
                                    order by d.id desc 
                                ) as row_num
                    from tbl_consumer  c
                    join tbl_consumer_demand d on d.consumer_id = c.id 
                    where c.status = 1 and d.status = 1 and d.paid_status = 0 
                        and d.consumer_id in(select id from demand)
                        $where2
                    group by c.id,demand_no,d.id	                    
                ),
                owner as (
                    select distinct(c.id) as consumer_id,string_agg( cd.applicant_name,', ') as applicant,
                    string_agg( cd.father_name,', ') as father_name, 
                    string_agg( cast(cd.mobile_no as varchar(10)),', ') as mobile_no
                    from tbl_consumer c
                    left join tbl_consumer_details cd on cd.consumer_id = c.id and cd.status = 1	
                    where c.status = 1 and c.id in(select id from demand)
                    group by c.id
                    ),
                meter_status as (
                    select consumer_id,
                        case when connection_type=1 then 'Meter'
                            when connection_type=2 then 'Gallon'
                            else 'Fixed'
                        end as metere_status
                    from tbl_meter_status m 
                    where m.consumer_id in(select id from demand)
                    )
                
                select c.id,c.consumer_no,wc.application_no,c.address,c.area_sqft,c.holding_no,c.consumer_no,
                    d.demand,d.min,d.max,d.advance,d.demand_no,d.penalty,
                    m.metere_status,
                    o.applicant,o.father_name,o.mobile_no,
                    w.ward_no,wc.holding_no,wc.address,wc.area_sqft,
                    ld.demand_no as last_demand_no
                from  tbl_consumer c
                join demand d on d.id = c.id
                join owner o on o.consumer_id = c.id
                join view_ward_mstr w on w.id = c.ward_mstr_id
                join lst_demand_no ld on ld.id = c.id and ld.row_num=1
                left join meter_status m on m.consumer_id = c.id
                left join tbl_apply_water_connection wc on wc.id = c.apply_connection_id
                $where2
            ";
            
            $sql.=" order by w.ward_no  ";
            //print_var($sql);die;
            $data['bulk_demand']=$this->WaterMobileModel->getDataRowQuery2($sql); 
        }
        return view('water/report/bulk_demand', $data);
    }

}
?>
