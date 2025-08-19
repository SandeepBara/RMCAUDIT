<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\model_pagination;
use App\Models\WaterReportModel;

use App\Models\WaterMobileModel;

use App\Models\WaterApplyNewConnectionModel;
use App\Models\WaterViewConnectionFeeModel;
use App\Models\WaterConnectionChargeModel;
use App\Models\WaterPenaltyInstallmentModel;
use App\Models\WaterPenaltyModel;
use App\Models\WaterPaymentModel;
use App\Models\Water_Transaction_Model;
use App\Models\WaterSiteInspectionModel;
use App\Models\WaterConnectionDetailsViewModel;
use App\Controllers\WaterPayment;
use App\Controllers\WaterApplyNewConnection;
use App\Models\model_ulb_mstr;
use App\Models\model_emp_details;
use App\Models\water_applicant_details_model;
use App\Models\WaterSearchApplicantsMobileModel;
use CodeIgniter\HTTP\Response;
use Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\AutoFilter\Column\Rule;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

use App\Models\model_datatable;

class water_report extends AlphaController
{   
    protected $db;
    protected $dbSystem;
    protected $ulb_id;
    protected $emp_id;
    
    //protected $db_name;
    protected $water_report_model;
    protected $model_datatable;
    protected $WaterMobileModel;
    protected $user_type;

    public function __construct()
    {   
        ini_set('memory_limit', '-1');
        helper(['php_office_helper']);
        helper(['db_helper','form']);
        helper(['db_helper', 'utility_helper','url']);
        $session=session();
        $ulb_details=$session->get('ulb_dtl');
        //print_r($ulb_details);
        $this->ulb_id=$ulb_details['ulb_mstr_id'];

        $emp_details=$session->get('emp_details');
        $this->emp_id=$emp_details['id'];
        $this->user_type = $emp_details["user_type_mstr_id"];

        parent::__construct();
        helper(['db_helper']);
        helper('form','url');
        if($db_name = dbConfig("water")){
            //echo $db_name;
            $this->db = db_connect($db_name);   
              
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        } 
        
        helper(['form']);
        //$db_name = db_connect("db_rmc_property"); 
        
        $this->ward_model=new model_ward_mstr($this->dbSystem);
        $this->water_report_model=new WaterReportModel($this->db);
        $this->WaterMobileModel=new WaterMobileModel($this->db);

        // $this->apply_waterconn_model=new WaterApplyNewConnectionModel($this->db); 
        // $this->conn_fee=new WaterViewConnectionFeeModel($this->db);  
        // $this->conn_charge_model=new WaterConnectionChargeModel($this->db); 
        // $this->penalty_installment_model=new WaterPenaltyInstallmentModel($this->db); 
        // $this->WaterPenaltyModel=new WaterPenaltyModel($this->db); 
        // $this->payment_model=new WaterPaymentModel($this->db); 
        // $this->transaction_model=new Water_Transaction_Model($this->db);
        // $this->site_ins_model=new WaterSiteInspectionModel($this->db);
        // $this->water_conn_dtls=new WaterConnectionDetailsViewModel($this->db);

        $this->conn_fee=new WaterViewConnectionFeeModel($this->db);
        $this->water_conn_dtls=new WaterConnectionDetailsViewModel($this->db);
        $this->apply_waterconn_model=new WaterApplyNewConnectionModel($this->db);
        $this->apply_conn=new WaterApplyNewConnection();
        $this->payment_model=new WaterPaymentModel($this->db);
        $this->transaction_model=new Water_Transaction_Model($this->db);
		$this->modelUlb = new model_ulb_mstr($this->dbSystem);
        $this->site_ins_model=new WaterSiteInspectionModel($this->db);
        $this->modelemp = new model_emp_details($this->dbSystem);
        $this->applicant_details=new water_applicant_details_model($this->db);
        $this->conn_charge_model=new WaterConnectionChargeModel($this->db);
        $this->penalty_installment_model=new WaterPenaltyInstallmentModel($this->db);
        $this->search_applicant_mobile_model=new WaterSearchApplicantsMobileModel($this->db);
		$this->WaterPenaltyModel=new WaterPenaltyModel($this->db);
        $this->model_datatable = new model_datatable($this->db);
    }

    public function __destruct()
    {
        if($this->db)
            $this->db->close();
        if($this->dbSystem)
            $this->dbSystem ->close();
    }
    
    public function index()
    {
        $data =(array)null;
        $session = session();
        $ulb_dtl = $session->get('ulb_dtl');
        $ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];

        $data =(array)null;
        $Session = Session();
        $status=array();

        $data['ulb_mstr_id']=$this->ulb_id;
        $whereClause="where 1=1 ";
        $join=NULL;
        $data['team_leader']='';
        $tc=[
            'where'=>['user_type_id'=>[8,5,4]],
            'tbl'=>'view_emp_details',
            'column'=>['id','emp_name','user_type','lock_status','employee_code']
        ];
        $data['tc']=$this->WaterMobileModel->getDataNew($tc['where'],$tc['column'],$tc['tbl'],array(),array('emp_name'=>'ASC'));
        
        if($this->request->getMethod()=='post')
        {
            //print_var($this->request->getPost());
            
            //print_var($data['collection']);
        }
        //------------------------ hear ---------------------------------//
        
        
        $uri_string = uri_string();
        if (isset($_GET['page']) && $_GET['page']== 'clr')
        {
            $Session->remove('tempData'); //echo('remove');
        }

        //$data['wardList'] = $this->model_ward_mstr->getWardListForReport($ulb_mstr_id);        
        if($this->request->getMethod()=='post')
        {
            $inputs = arrFilterSanitizeString($this->request->getVar());
            $Session->set('tempData', $inputs);            
        }
        $tempData=$Session->get('tempData');       
        if(!empty($tempData))
        {
            
            $data['from_date'] = $tempData['from_date'];
            $data['to_date'] = $tempData['to_date'];
            $data['tc_id']= $tempData['tc_id'];           
            
            $status=array();
            
            // //$inputs = $this->request->getPost();
            // $data['from_date']=$inputs['from_date'];
            // $data['to_date']=$inputs['to_date'];
            // $data['team_leader']=$inputs['team_leader'];
            $data['from']=strtotime($tempData['from_date']);
            $data['to']=strtotime($tempData['to_date']);

            $with="with trancastion as (
                    select *,cast(created_on as date) as date_c from tbl_transaction
                ) ";
            $select= "select count(related_id) as c_count,count(t.id) as t_count, 
                    sum(paid_amount) as amount ,emp_details_id,ve.emp_name,ve.employee_code 
                    ";
            $from = " from trancastion t join view_emp_details ve on ve.id=t.emp_details_id
                where t.date_c>='".$data['from_date']."' and t.date_c<='".$data['to_date']."' and t.status not in(0,3) ";
            $sql =  $with.$select.$from;
            $sum_sql = $with."select sum(paid_amount) as total_paid_amount,
                                count(t.id) as total_taransection ,
                                count(distinct(emp_details_id)) as total_emp ".$from;
            if($data['tc_id']!='') 
            {
                $sql.=" and t.emp_details_id=".$data['tc_id']." ";
                $sum_sql.=" and t.emp_details_id=".$data['tc_id']." ";
            }      
                
            $sql.=" group by  t.emp_details_id,ve.emp_name,ve.employee_code ";
            $sql.="order by ve.emp_name asc ";
           
            $data['collection']=$this->WaterMobileModel->getDataRowQuery($sql);
            $total = $this->WaterMobileModel->row_sql($sum_sql);    
            //print_var($total);        
            $data['total_taransection']=$total[0]['total_taransection']??0;
            $data['total_paid_amount']=$total[0]['total_paid_amount']??0;
            $data['total_emp']=$total[0]['total_emp']??0;

            

        }        
        return view('water/report/team_summary', $data);

    }

    public function water_collection_dtl($emp_id='',$from='',$to='')
    {
        $data = array();
        $from_date = date('Y-m-d',$from);   
        $to_date = date('Y-m-d',$to); 
        
        $sql="  select t.id,c.id,apw.id,t.paid_amount,t.transaction_type,apw.application_no,
                    c.consumer_no,cast(t.created_on as date) as created_on,payment_mode
                from tbl_transaction t 
                left join tbl_consumer c on c.id=t.related_id
                left join tbl_apply_water_connection apw on apw.id = t.related_id
                where md5(t.emp_details_id::text)='$emp_id' 
                    and cast(t.created_on as date) >='$from_date'
                    and cast(t.created_on as date) <='$to_date' ";
            //print_var($sql);
        $data['transaction']=$this->WaterMobileModel->getDataRowQuery2($sql);
        //print_var($data['transaction']);
        return view('water/report/water_collection_dtl', $data);
    }

     
    public function government_body_demand()
    {
        $data =(array)null;
        $session = session();
        $ulb_dtl = $session->get('ulb_dtl');
        $ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];

        $data =(array)null;
        $Session = Session();
        $status=array();
        $curent_date = date('Y-m');
        $finyear='';
        if(date('m')>3)
        {
            $finyear=date('Y');
        }
        else
            $finyear=(date('Y')-1);
        $i=1;
        $data['fin_year_list']=array();
        while(true):
            array_push($data['fin_year_list'],$finyear."-".($finyear+1));
            if($finyear=='2016')
                break;
            $finyear-=1;
        endwhile;

        //print_var($data['fin_year_list']);
        $data['ulb_mstr_id']=$this->ulb_id;
        $whereClause=" where property_type = 'Goverment & PSU' ";
        $whereClauseNew="  ";
        $join=NULL;
        //$data['consumer_wise_dcb']=[];
        $data['team_leader']='';
        $ward_whare=[
            'status <>'=>[0],
            'ulb_mstr_id'=>[$ulb_mstr_id]
        ];
        $data['ward']=$this->WaterMobileModel->getDataNew($ward_whare,array('id','ward_no'),'view_ward_mstr',array(),array('ward_no'=>'ASC'));
       
        $uri_string = uri_string();
        if (isset($_GET['page']) && $_GET['page']== 'clr')
        {
            $Session->remove('tempData'); //echo('remove');
        }

               
        if($this->request->getMethod()=='post')
        {
            $inputs = arrFilterSanitizeString($this->request->getVar());
            $Session->set('tempData', $inputs);
            
            //echo('remove');
        }
        $tempData=$Session->get('tempData');
        
        if(!empty($tempData))
        {
            $data['ward_id'] = $tempData['ward_id'];
            //$data['fin_year'] = $tempData['fin_year'];
            $data['category'] = $tempData['category'];
            $data['connection_type'] = $tempData['connection_type'];
            $status=array();           
             
            $ward_id = $data['ward_id'];           
            if($data["ward_id"]!="")
            {
                $whereClause .= " and tbl_consumer.ward_mstr_id=".$data["ward_id"];
                $whereClauseNew .= " and tbl_consumer.ward_mstr_id=".$data["ward_id"];
            }            
            if($data["category"]!="")
            {
                $whereClause .= " and category='".$data["category"]."'";
                $whereClauseNew .= " and category='".$data["category"]."'";
            }
            if($data["connection_type"]!="")
            {
                if($data["connection_type"] == "Meter")
                {
                    $conn="1";
                }
                if($data["connection_type"] == "Non-Meter")
                {
                    $conn="2,3";
                }
                $whereClause .= " and connection_type in ($conn)";
                $whereClauseNew .= " and connection_type in ($conn)";
            }
            

              { 
                  // $sql="  SELECT tbl_consumer.id,
                    //             tbl_consumer.consumer_no, w.ward_no,
                    //             tbl_property_type_mstr.property_type,
                    //             tbl_consumer.ward_mstr_id,
                    //             tbl_consumer.category,
                    //             meter_type.connection_type,
                    //             owner.applicant_name,
                    //             COALESCE((COALESCE(demand.arrear_demand, (0)::numeric) - COALESCE(prev_coll_amount.prev_coll, (0)::numeric)), (0)::numeric) AS outstanding_at_begin,
                    //             COALESCE(prev_coll_amount.prev_coll, (0)::numeric) AS prev_coll,
                    //             COALESCE(demand.curr_demand, (0)::numeric) AS current_demand,
                    //             COALESCE(coll.arrear_coll, (0)::numeric) AS arrear_coll,
                    //             COALESCE(coll.curr_coll, (0)::numeric) AS curr_coll,
                    //             (COALESCE((COALESCE(demand.arrear_demand, (0)::numeric) - COALESCE(prev_coll_amount.prev_coll, (0)::numeric)), (0)::numeric) - COALESCE(coll.arrear_coll, (0)::numeric)) AS old_due,
                    //             (COALESCE(demand.curr_demand, (0)::numeric) - COALESCE(coll.curr_coll, (0)::numeric)) AS curr_due,
                    //             (COALESCE((COALESCE(demand.curr_demand, (0)::numeric) + (COALESCE(demand.arrear_demand, (0)::numeric) - COALESCE(prev_coll_amount.prev_coll, (0)::numeric))), (0)::numeric) - COALESCE((COALESCE(coll.curr_coll, (0)::numeric) + COALESCE(coll.arrear_coll, (0)::numeric)), (0)::numeric)) AS outstanding,


                    //             ((COALESCE(demand.arrear_demand, (0)::numeric)) + (COALESCE(demand.curr_demand, (0)::numeric)) ) as total_demand,
                    //             ((COALESCE((COALESCE(demand.arrear_demand, (0)::numeric) - COALESCE(prev_coll_amount.prev_coll, (0)::numeric)), (0)::numeric)) + (COALESCE(demand.curr_demand, (0)::numeric)))  as c_total_demand,
                    //             (COALESCE(coll.arrear_coll, (0)::numeric) + COALESCE(coll.curr_coll, (0)::numeric)) as total_cullection
                                
                    //         FROM (
                    //                 (
                    //                     (
                    //                         (
                    //                             (
                    //                                 (
                    //                                     tbl_consumer
                    //                                     LEFT JOIN 
                    //                                             ( 
                    //                                                 SELECT tbl_consumer_details.consumer_id,
                    //                                                         string_agg(
                    //                                                                     (tbl_consumer_details.applicant_name)::text, ','::text
                    //                                                                 ) AS applicant_name
                    //                                                 FROM tbl_consumer_details
                    //                                                 GROUP BY tbl_consumer_details.consumer_id
                    //                                             ) owner ON ((owner.consumer_id = tbl_consumer.id))
                                                        
                    //                                 )
                    //                                 LEFT JOIN tbl_property_type_mstr ON ((tbl_property_type_mstr.id = tbl_consumer.property_type_id))
                    //                             )
                    //                             LEFT JOIN ( 
                    //                                         SELECT tbl_consumer_demand.consumer_id,
                    //                                                 sum(
                    //                                                     CASE
                    //                                                         WHEN (tbl_consumer_demand.demand_upto <= '$formday-03-31'::date) 
                    //                                                         THEN tbl_consumer_demand.amount
                    //                                                         ELSE NULL::numeric
                    //                                                         END
                    //                                                     ) AS arrear_demand,
                    //                                                 sum(
                    //                                                     CASE
                    //                                                         WHEN (
                    //                                                                 (tbl_consumer_demand.demand_upto > '$formday-03-31'::date) 
                    //                                                                 AND (tbl_consumer_demand.demand_upto <= '$today-03-31'::date)
                    //                                                             ) 
                    //                                                         THEN tbl_consumer_demand.amount
                    //                                                         ELSE NULL::numeric
                    //                                                         END
                    //                                                     ) AS curr_demand
                    //                                             FROM tbl_consumer_demand
                    //                                             WHERE (tbl_consumer_demand.status = 1)
                    //                                             GROUP BY tbl_consumer_demand.consumer_id
                    //                                     ) demand ON ((demand.consumer_id = tbl_consumer.id))
                    //                         )
                    //                         LEFT JOIN ( 
                    //                                     SELECT tbl_consumer_collection.consumer_id,
                    //                                         sum(
                    //                                             CASE
                    //                                                 WHEN (tbl_consumer_demand.demand_upto <= '$formday-03-31'::date) 
                    //                                                 THEN tbl_consumer_collection.amount
                    //                                                 ELSE NULL::numeric
                    //                                                 END
                    //                                             ) AS arrear_coll,
                    //                                         sum(
                    //                                             CASE
                    //                                                 WHEN (
                    //                                                         (tbl_consumer_demand.demand_upto > '$formday-03-31'::date) 
                    //                                                         AND (tbl_consumer_demand.demand_upto <= '$today-03-31'::date)
                    //                                                     ) 
                    //                                                 THEN tbl_consumer_collection.amount
                    //                                                 ELSE NULL::numeric
                    //                                                 END
                    //                                             ) AS curr_coll
                    //                                         FROM (
                    //                                             (
                    //                                                 tbl_consumer_collection
                    //                                                 JOIN tbl_consumer_demand ON ((tbl_consumer_demand.id = tbl_consumer_collection.demand_id))
                    //                                             )
                    //                                             JOIN tbl_transaction ON ((tbl_transaction.id = tbl_consumer_collection.transaction_id))
                    //                                         )
                    //                                     WHERE (
                    //                                             (tbl_transaction.transaction_date >= '$formday-04-01'::date) 
                    //                                             AND (tbl_transaction.transaction_date <= '$today-03-31'::date)
                    //                                         )
                    //                                     GROUP BY tbl_consumer_collection.consumer_id
                    //                                 ) coll ON ((coll.consumer_id = tbl_consumer.id))
                    //                     )
                    //                     LEFT JOIN (
                    //                                 SELECT tbl_transaction.related_id,
                    //                                         sum(tbl_consumer_collection.amount) AS prev_coll
                    //                                 FROM (
                    //                                         (
                    //                                             tbl_consumer_collection
                    //                                             JOIN tbl_consumer_demand ON ((tbl_consumer_demand.id = tbl_consumer_collection.demand_id))
                    //                                         )
                    //                                         JOIN tbl_transaction ON ((tbl_transaction.id = tbl_consumer_collection.transaction_id))
                    //                                     )
                    //                                 WHERE (
                    //                                         (tbl_transaction.transaction_date <= '$formday-03-31'::date) 
                    //                                         AND ((tbl_transaction.transaction_type)::text = 'Demand Collection'::text)
                    //                                     )
                    //                                 GROUP BY tbl_transaction.related_id
                    //                             ) prev_coll_amount ON ((prev_coll_amount.related_id = tbl_consumer.id))
                    //                 )
                    //                 LEFT JOIN ( 
                    //                             SELECT tbl_meter_status.id,
                    //                                 tbl_meter_status.consumer_id,
                    //                                 tbl_meter_status.connection_type
                    //                             FROM tbl_meter_status
                    //                             WHERE (
                    //                                     tbl_meter_status.id IN ( 
                    //                                                             SELECT max(tbl_meter_status_1.id) AS id
                    //                                                             FROM tbl_meter_status tbl_meter_status_1
                    //                                                             GROUP BY tbl_meter_status_1.consumer_id
                    //                                                             ORDER BY (max(tbl_meter_status_1.id))
                    //                                                         )
                    //                                 )
                    //                             ORDER BY tbl_meter_status.consumer_id
                    //                         ) meter_type ON ((meter_type.consumer_id = tbl_consumer.id))
                    //             )
                    //         join view_ward_mstr w on w.id = tbl_consumer.ward_mstr_id
                    //         where tbl_consumer.property_type_id = 3   $where 
                    //         order by w.ward_no asc  ";
              }            
            

            $fy = explode('-',getFY());        
            $from =$fy[0].'-03-31'; 
            $to = $fy[0].'-04-01';

            $sql = "with owner as (
                        SELECT tbl_consumer_details.consumer_id,
                            string_agg(tbl_consumer_details.applicant_name::text, ','::text) AS applicant_name
                        FROM tbl_consumer_details
                        join tbl_consumer on  tbl_consumer.id = tbl_consumer_details.consumer_id
                            AND tbl_consumer.property_type_id = 3 AND tbl_consumer.status = 1
                        GROUP BY tbl_consumer_details.consumer_id
                    ),
                    demand as ( 
                        SELECT tbl_consumer_demand.consumer_id,
                            sum(
                                CASE WHEN tbl_consumer_demand.demand_upto <= '$from'::date 
                                    THEN tbl_consumer_demand.amount
                                ELSE NULL::numeric
                                END
                            ) AS arrear_demand,
                            sum(
                                CASE WHEN tbl_consumer_demand.demand_upto > '$from'::date 
                                    AND tbl_consumer_demand.demand_upto <= '$from'::date 
                                    THEN tbl_consumer_demand.amount
                                    ELSE NULL::numeric
                                END
                            ) AS curr_demand
                        FROM tbl_consumer_demand
                        WHERE tbl_consumer_demand.status = 1
                        GROUP BY tbl_consumer_demand.consumer_id
                    ),
                    coll as ( 
                        SELECT tbl_consumer_collection.consumer_id,
                            sum(
                                CASE WHEN tbl_consumer_demand.demand_upto <= '$from'::date 
                                    THEN tbl_consumer_collection.amount
                                    ELSE NULL::numeric
                                END
                            ) AS arrear_coll,
                            sum(
                                CASE WHEN tbl_consumer_demand.demand_upto > '$from'::date 
                                    AND tbl_consumer_demand.demand_upto <= '$from'::date 
                                    THEN tbl_consumer_collection.amount
                                    ELSE NULL::numeric
                                END
                            ) AS curr_coll
                        FROM tbl_consumer_collection
                        JOIN tbl_consumer_demand ON tbl_consumer_demand.id = tbl_consumer_collection.demand_id
                        JOIN tbl_transaction ON tbl_transaction.id = tbl_consumer_collection.transaction_id
                        WHERE tbl_transaction.transaction_date >= '$to'::date 
                            AND tbl_transaction.transaction_date <= '$from'::date
                        GROUP BY tbl_consumer_collection.consumer_id
                    ),
                    prev_coll_amount as ( 
                            SELECT tbl_transaction.related_id,
                                sum(tbl_consumer_collection.amount) AS prev_coll
                            FROM tbl_consumer_collection
                            JOIN tbl_consumer_demand ON tbl_consumer_demand.id = tbl_consumer_collection.demand_id
                            JOIN tbl_transaction ON tbl_transaction.id = tbl_consumer_collection.transaction_id
                            WHERE tbl_transaction.transaction_date <= '$from'::date 
                                AND tbl_transaction.transaction_type::text = 'Demand Collection'::text
                            GROUP BY tbl_transaction.related_id
                    ),
                    meter_type as ( 
                        SELECT tbl_meter_status.id,
                        tbl_meter_status.consumer_id,
                        tbl_meter_status.connection_type
                        FROM tbl_meter_status
                        WHERE (
                                tbl_meter_status.id IN ( 
                                                    SELECT max(tbl_meter_status_1.id) AS id
                                                    FROM tbl_meter_status tbl_meter_status_1
                                                    GROUP BY tbl_meter_status_1.consumer_id
                                                    ORDER BY (max(tbl_meter_status_1.id))
                                                    )
                            )
                        --ORDER BY tbl_meter_status.consumer_id
                    ) 
                        
                    SELECT tbl_consumer.id,
                        tbl_consumer.consumer_no,
                        tbl_property_type_mstr.property_type,
                        tbl_consumer.ward_mstr_id,
                        tbl_consumer.category,
                        meter_type.connection_type,
                        owner.applicant_name,
                        
                        COALESCE(
                            COALESCE(demand.arrear_demand, 0::numeric) 
                            - COALESCE(prev_coll_amount.prev_coll, 0::numeric), 0::numeric
                        ) AS outstanding_at_begin,
                        
                        COALESCE(prev_coll_amount.prev_coll, 0::numeric) AS prev_coll,
                        COALESCE(demand.curr_demand, 0::numeric) AS current_demand,
                        COALESCE(coll.arrear_coll, 0::numeric) AS arrear_coll,
                        COALESCE(coll.curr_coll, 0::numeric) AS curr_coll,
                        
                        (COALESCE(
                                COALESCE(demand.arrear_demand, 0::numeric) 
                                - COALESCE(prev_coll_amount.prev_coll, 0::numeric), 0::numeric
                            ) 
                            - COALESCE(coll.arrear_coll, 0::numeric) )AS old_due,
                            
                        (COALESCE(demand.curr_demand, 0::numeric) - COALESCE(coll.curr_coll, 0::numeric)) AS curr_due,
                        
                        (
                            COALESCE(
                                COALESCE(demand.curr_demand, 0::numeric) 
                                + (
                                    COALESCE(demand.arrear_demand, 0::numeric) 
                                    - COALESCE(prev_coll_amount.prev_coll, 0::numeric)
                                ), 0::numeric
                            ) 
                            - COALESCE(
                                COALESCE(coll.curr_coll, 0::numeric) 
                                + COALESCE(coll.arrear_coll, 0::numeric), 0::numeric
                            )
                        ) AS outstanding,
                        w.ward_no
                    FROM tbl_consumer
                    LEFT JOIN owner ON owner.consumer_id = tbl_consumer.id
                    LEFT JOIN tbl_property_type_mstr ON tbl_property_type_mstr.id = tbl_consumer.property_type_id
                    LEFT JOIN demand ON demand.consumer_id = tbl_consumer.id
                    LEFT JOIN coll ON coll.consumer_id = tbl_consumer.id
                    LEFT JOIN prev_coll_amount ON prev_coll_amount.related_id = tbl_consumer.id
                    LEFT JOIN  meter_type ON meter_type.consumer_id = tbl_consumer.id 
                    left join view_ward_mstr w on w.id =  tbl_consumer.ward_mstr_id 
                    where  tbl_consumer.property_type_id = 3 AND tbl_consumer.status = 1 
                    $whereClauseNew      

            ";
            $data['consumer']=$this->WaterMobileModel->getDataRowQuery($sql);
            //print_var($data['consumer']);
            

        }
        return view('water/report/government_body_demand', $data);
    }


    
    public function ward_team_summary_report()
    {
        $data =(array)null;
        $session = session();
        $ulb_dtl = $session->get('ulb_dtl');
        $ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];
        $user_type_id = $session->get('emp_details')['user_type_mstr_id'];

        $data =(array)null;
        $Session = Session();
        $status=array();

        $data['ulb_mstr_id']=$this->ulb_id;
        $whereClause="where 1=1 ";
        $join=NULL;
        
        $data['team_leader']='';
        $tc=[
            'where'=>['user_type_id'=>[8,5,4]],
            'tbl'=>'view_emp_details',
            'column'=>['id','emp_name','user_type','lock_status','employee_code']
        ];
        // if($user_type_id==1)
        // {
        //     $data['tc']=$this->WaterMobileModel->getDataNew(array(),$tc['column'],$tc['tbl'],array(),array('emp_name'=>'ASC')); 
        // }
        // else
            $data['tc']=$this->WaterMobileModel->getDataNew($tc['where'],$tc['column'],$tc['tbl'],array(),array('emp_name'=>'ASC'));
        $ward_whare=[
            'where'=>['status '=>[1],'ulb_mstr_id'=>[$ulb_mstr_id]],
            'tbl'=>'view_ward_mstr',
            'column'=>['id','ward_no','ulb_mstr_id','status']
        ];
        $data['ward']=$this->WaterMobileModel->getDataNew($ward_whare['where'],$ward_whare['column'],$ward_whare['tbl'],array(),array('ward_no'=>'ASC'));
        
        $uri_string = uri_string();
        if (isset($_GET['page']) && $_GET['page']== 'clr')
        {
            $Session->remove('tempData'); //echo('remove');
        }

               
        if($this->request->getMethod()=='post')
        {
            $inputs = arrFilterSanitizeString($this->request->getVar());
            $Session->set('tempData', $inputs);
            
        }
        $tempData=$Session->get('tempData');
        
        if(!empty($tempData))
        {
            $data['ward_id'] = $tempData['ward_id'];
            $data['from_date'] = $tempData['from_date'];
            $data['to_date'] = $tempData['to_date'];
            $data['tc_id']= $tempData['tc_id'];           
            
            $status=array();
            
            // //$inputs = $this->request->getPost();
            // $data['from_date']=$inputs['from_date'];
            // $data['to_date']=$inputs['to_date'];
            // $data['team_leader']=$inputs['team_leader'];
            //print_var($data['ward_id']);
            $data['from']=strtotime($tempData['from_date']);
            $data['to']=strtotime($tempData['to_date']);
            $from = $data['from_date'];
            $to = $data['to_date'];

            $sql=" with emp as (
                        select ve.emp_name,ve.id as emp_id,ward_no,ve.user_type_id,ve.user_type,
                            ve.employee_code 
                        from view_emp_details ve
                        left join view_ward_permission vw on vw.emp_details_id=ve.id and vw.ulb_mstr_id = $ulb_mstr_id
                        where ve.id in (
                            select distinct(emp_details_id) from tbl_transaction
                                where cast(created_on as date) between '$from' and '$to'
                        
                            )
                        group by ve.emp_name,ve.id,vw.ward_no,ve.user_type_id,ve.user_type,ve.employee_code 
                    ),
                    
                    trancastion as (
                        select *,cast(created_on as date) as date_c from tbl_transaction
                        where cast(created_on as date) between '$from' and '$to' AND status in (1,2)
                    ),
                    ward as (
                        select * from view_ward_mstr
                        where ulb_mstr_id = $ulb_mstr_id
                    )
                    select count(related_id) as c_count,count(t.id) as t_count, 
                        COALESCE(sum(paid_amount),0) as amount ,ve.emp_id,ve.user_type,ve.emp_name ,
                        w.ward_no,w.id as ward_id,ve.employee_code 
                    from trancastion t
                    join ward w on  t.ward_mstr_id = w.id
                    join emp ve  on ve.emp_id=t.emp_details_id and w.ward_no = ve.ward_no 
            
                    ";
            //print_var($_SESSION['emp_details']);
            if($user_type_id==1)
            {
                $sql.=' where 1=1 ';
            }
            else
                $sql.=' where ve.user_type_id in(8,5,4)';
            
            if($data['tc_id']!='')       
                $sql.=" and ve.emp_id=".$data['tc_id']." ";
                
            if($data['ward_id']!='')
            {   //echo($data['ward_id']);
                $sql.=" and w.id=".$data['ward_id']." ";
            }
            // if(trim($inputs['order_by'])=='No. of cunsumer')
                $sql.=" group by  t.emp_details_id,ve.emp_name,w.ward_no,w.id,ve.emp_id,ve.user_type,ve.employee_code     ";
                $sql.=" order by ve.emp_name,ve.emp_id asc ";
            //print_var($sql);die;
            $data['collection']=$this->WaterMobileModel->getDataRowQuery($sql);
            //print_var($data['collection']['result']);

        }
        return view('water/report/ward_team_summary_report', $data);
    }

    public function ward_team_summary_report_dtl($emp_id='',$ward_id='',$from='',$to='')
    {
        $data = array();
        $from_date = date('Y-m-d',$from);   
        $to_date = date('Y-m-d',$to); 
        
        $sql="  select t.id,c.id,apw.id,t.paid_amount,t.transaction_type,apw.application_no,
                    c.consumer_no,cast(t.created_on as date) as created_on,payment_mode
                from tbl_transaction t 
                left join tbl_consumer c on c.id=t.related_id
                left join tbl_apply_water_connection apw on apw.id = t.related_id
                where md5(t.emp_details_id::text)='$emp_id' 
                    and cast(t.created_on as date) >='$from_date'
                    and cast(t.created_on as date) <='$to_date' 
                    and md5(t.ward_mstr_id::text) ='$ward_id' 
                    AND t.status in (1,2) ";
        $data['transaction']=$this->WaterMobileModel->getDataRowQuery2($sql);
        //print_var($sql);
        return view('water/report/water_collection_dtl', $data); 
    }

    public function counter_report()
    {
        $data =(array)null;
        $Session = session();
        $ulb_dtl = $Session->get('ulb_dtl');
        $ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];

        $data['ulb_mstr_id']=$this->ulb_id;
        $data['team_leader']='';
        $tc=[
            'where'=>['user_type_id'=>[8,5,4]],
            'tbl'=>'view_emp_details',
            'column'=>['id','emp_name','user_type','lock_status','employee_code']
        ];
        $data['tc']=$this->WaterMobileModel->getDataNew($tc['where'],$tc['column'],$tc['tbl'],array(),array('emp_name'=>'ASC'));
        $ward_whare=[
            'where'=>['status '=>[1],'ulb_mstr_id'=>[$ulb_mstr_id]],
            'tbl'=>'view_ward_mstr',
            'column'=>['id','ward_no']
        ];
        $data['ward']=$this->WaterMobileModel->getDataNew($ward_whare['where'],$ward_whare['column'],$ward_whare['tbl'],array(),array('ward_no'=>'ASC'));
        
        $uri_string = uri_string();
        if (isset($_GET['page']) && $_GET['page']== 'clr')
        {
            $Session->remove('tempData'); //echo('remove');
        }
       
        if($this->request->getMethod()=='post')
        {
            $inputs = arrFilterSanitizeString($this->request->getVar());
            $Session->set('tempData', $inputs);
        }
        $tempData=$Session->get('tempData');
        if(!empty($tempData))
        {
            $data['ward_id'] = $tempData['ward_id'];
            $data['from_date'] = $tempData['from_date'];
            $data['to_date'] = $tempData['to_date'];
            $data['tc_id']= $tempData['tc_id'];           
            
            $status=array();
            $data['from']=strtotime($tempData['from_date']);
            $data['to']=strtotime($tempData['to_date']);
            $from = $data['from_date'];
            $to = $data['to_date'] ;
            $ward = $data['ward_id'];

            $sql=" with app as(
	
                        select gar.*,a.* from view_water_application_details a
                        left join (select apply_connection_id,
                                        string_agg((tbl_applicant_details.father_name)::text||' ', ','::text  ) AS father_name
                                    FROM tbl_applicant_details
                                    GROUP BY tbl_applicant_details.apply_connection_id
                                ) as gar on gar.apply_connection_id=a.id
                    ),
                    consumer as(
                        select vc.*,c.is_meter_working,
                            case when c.is_meter_working notnull and c.is_meter_working=1 then 'Metered'
                                when c.is_meter_working isnull or c.is_meter_working=0 then 'Non_Metered'
                                end as meter,woner.worner_name,woner.father_name,woner.mobile_no
                            
                        from (
                                SELECT tbl_pipeline_type_mstr.pipeline_type,
                                    tbl_property_type_mstr.property_type,
                                    tbl_connection_type_mstr.connection_type,
                                    tbl_connection_through_mstr.connection_through,
                                    tbl_apply_water_connection.holding_no,
                                    tbl_apply_water_connection.application_no,
                                    view_ward_mstr.ward_no,
                                    tbl_consumer.consumer_no,
                                    tbl_consumer.id,
                                    tbl_consumer.apply_connection_id,
                                    tbl_consumer.connection_type_id,
                                    tbl_consumer.connection_through_id,
                                    tbl_consumer.property_type_id,
                                    tbl_consumer.category,
                                    tbl_consumer.ward_mstr_id,
                                    tbl_consumer.prop_dtl_id,
                                    tbl_consumer.area_sqmt,
                                    tbl_consumer.area_sqft,
                                    tbl_consumer.pipeline_type_id,
                                    tbl_consumer.flat_count,
                                    tbl_consumer.k_no,
                                    tbl_consumer.bind_book_no,
                                    tbl_consumer.account_no,
                                    tbl_consumer.electric_category_type,
                                    tbl_consumer.emp_details_id,
                                    tbl_consumer.created_on
                                FROM ((((((tbl_consumer
                                LEFT JOIN tbl_pipeline_type_mstr ON ((tbl_pipeline_type_mstr.id = tbl_consumer.pipeline_type_id)))
                                LEFT JOIN tbl_property_type_mstr ON ((tbl_property_type_mstr.id = tbl_consumer.property_type_id)))
                                LEFT JOIN tbl_connection_type_mstr ON ((tbl_connection_type_mstr.id = tbl_consumer.connection_type_id)))
                                LEFT JOIN tbl_connection_through_mstr ON ((tbl_connection_through_mstr.id = tbl_consumer.connection_through_id)))
                                LEFT JOIN tbl_apply_water_connection ON ((tbl_apply_water_connection.id = tbl_consumer.apply_connection_id)))
                                LEFT JOIN view_ward_mstr ON ((view_ward_mstr.id = tbl_consumer.ward_mstr_id)))
                          
                         ) vc
                        join tbl_consumer c on c.id =vc.id
                        join (
                                select consumer_id , 
                                    string_agg((applicant_name)::text||' ', ','::text  ) AS worner_name,
                                    string_agg((father_name)::text||' ', ','::text  ) AS father_name,
                                    string_agg((mobile_no)::text||' ', ','::text  ) AS mobile_no
                                FROM tbl_consumer_details cd
                                join tbl_consumer cc on cc.id = cd.consumer_id
                                GROUP BY consumer_id 
                            ) woner on woner.consumer_id = c.id 
                        
                        
                        
                    )
                    
                    select t.related_id,t.transaction_type,t.transaction_no,t.payment_mode,cast(t.transaction_date as date) as date,
                        t.paid_amount,ch.cheque_no,ch.bank_name,ch.branch_name,t.status,emp.emp_name,emp.user_type,emp.employee_code, 
                        
                        app.application_no as app_application_no,app.applicant_name as app_applicant_name,
                        app.mobile_no as app_mobile_no,app.father_name as app_father_name,app.ward_no as app_ward_no,
                        
                        consumer.consumer_no as c_consumer_no,consumer.meter,consumer.application_no as c_application_no
                        ,consumer.worner_name as c_worner_name  ,consumer.mobile_no as c_mobile_no ,
                        consumer.father_name as c_father_name, consumer.ward_no as c_ward_no,consumer.property_type as c_property_type,
                        consumer.holding_no as c_holding_no
                    from tbl_transaction t 
                    left join app on app.id = t.related_id and t.transaction_type IN ('New Connection', 'Site Inspection')
                    left join consumer on consumer.id = t.related_id and t.transaction_type = 'Demand Collection'
                    left join tbl_cheque_details ch on ch.transaction_id = t.id
                    join view_emp_details emp on emp.id = t.emp_details_id
                    where cast(t.transaction_date as date) >='$from ' 
                        and cast(t.transaction_date as date) <='$to' AND t.status in (1,2)
                     ";
            if($data['tc_id']!='')       
                $sql.=" and t.emp_details_id=".$data['tc_id']." ";
            if($ward!='')
                $sql.=" and t.ward_mstr_id='".$ward."' ";           
            //$sql.=" group by  t.emp_details_id,ve.emp_name ";
            $sql.=" order by t.transaction_type ";
            //print_var($sql);
            $data['collection']=$this->WaterMobileModel->getDataRowQuery2($sql);
            //print_var($data['collection']['count']);

        }
        return view('water/report/counter_report_copy', $data);
    }

    public function tc_pay($water_conn_id)
    {
        $data=array();  //echo($water_conn_id);
        $data['curr_date']=date('Y-m-d');
        $data['user_type']=session()->get('emp_details')['user_type_mstr_id'];//print_var(session()->get('emp_details'));
        $data['user_id']=session()->get('emp_details')['id'];
        
        $data['consumer_details']=$this->apply_waterconn_model->water_conn_details($water_conn_id);

        $data['owner_details']=$this->apply_waterconn_model->water_owner_details($water_conn_id);

        //print_r($data['owner_details']);
        $data['water_conn_id']=$water_conn_id;
        
        $water_conn_details= $this->conn_fee->fetch_water_con_details($water_conn_id);
        //print_r($get_rate_id);
        

        $data['dues']= $this->conn_charge_model->due_exists($water_conn_id);


        $rate_id=$water_conn_details['water_fee_mstr_id'];

        $data['application_no']=$water_conn_details['application_no'];

        $data['conn_fee_charge']=$this->conn_fee->conn_fee_charge($water_conn_id);

        
        $data['penalty_installment']=$this->penalty_installment_model->getUnpaidInstallment($water_conn_id);

        $data['penalty']=$this->penalty_installment_model->getUnpaidInstallmentSum($water_conn_id);

        # cheque bounce penalty
        $data['other_penalty']=$this->WaterPenaltyModel->getUnpaidPenaltySum($water_conn_id);

        $rebate_details=$this->payment_model->get_rebate_details($water_conn_id);

        // echo $data['rebate_details']['rebate'];
        $data['rebate']=$rebate_details['rebate'];

        $data['total_amount']=$data['conn_fee_charge']['conn_fee']+$data['penalty']+$data['other_penalty']-$data['rebate'];


        // print_r($data['conn_fee_charge_details']);
        // echo $water_conn_id;

        $data['transaction_details']=$this->transaction_model->get_all_transactions($water_conn_id);
        
        //echo"<pre>";print_r($data);echo"</pre>";

        return view('water/water_connection/water_tc_pay',$data);
    }

    public function proceed_payment()
    {
        $data=array();
        $session=session();
        $get_emp_details=$session->get('emp_details');
        $emp_id=$get_emp_details['id'];
        $user_type_mstr_id=$get_emp_details['user_type_mstr_id'];
        $this->get_ulb_detail=$session->get('ulb_dtl');

        if($this->request->getMethod()=='post')
        {    
            $water_conn_id_md5 = $this->request->getPost('water_conn_id');
            $sql_my="select * 
                     from tbl_site_inspection 
                     where scheduled_status = 1 
                        and md5(apply_connection_id::text) = '$water_conn_id_md5' 
                        and verified_by = 'JuniorEngineer' and payment_status = 0 order by id desc ";
            $data['si_verify_dtls']=$this->WaterMobileModel->getDataRowQuery2($sql_my);
            
            $si_verify_id='';
            if(!empty($data['si_verify_dtls']['result']))
                $si_verify_id = $data['si_verify_dtls']['result'][0]['id'];
            
            
            if(!empty($si_verify_id))
            {
                $this->db->transBegin();
                $inputs=arrFilterSanitizeString($this->request->getVar());
                $payment_mode=$inputs['payment_mode'];
                $total_paid_amount=$inputs['total_amount'];
                $total_amount=$inputs['conn_fee'];

                $rebate=$inputs['rebate'];
                $water_conn_id=$inputs['water_conn_id'];
                $payment_for=$inputs['payment_for'];
                $penalty_installment_upto_id=$inputs['penalty_installment_upto_id'];
                
                $connection_type_id=$_POST['connection_type_id'];
                $application_no=$inputs['application_no'];
                
                $get_water_conn_id=$this->water_conn_dtls->fetch_water_con_details($water_conn_id);
                
                $penalty=$this->penalty_installment_model->getUnpaidInstallmentSum($water_conn_id);
                $other_penalty=$this->WaterPenaltyModel->getUnpaidPenaltySum($water_conn_id);
                $water_conn_id=$get_water_conn_id['id'];
                $ward_id=$get_water_conn_id['ward_id'];
                $doc_status=$get_water_conn_id['doc_status'];
                $conn_payment_status = $get_water_conn_id['payment_status'];
                $installment_rebate=0;
                
                # Regularization
                if($connection_type_id==2)
                {
                    $pay_full=$_POST['pay_full'];
                    $penalty_installment_amount=$this->penalty_installment_model->getPenaltyforRebate(md5($water_conn_id));

                    if($pay_full==1)
                    {
                        $penalty+=$this->penalty_installment_model->getUnpaidInstallmentSum(md5($water_conn_id));
                        $installment_rebate=in_array($conn_payment_status,[0,null])?((10*$penalty_installment_amount)/100):0;
                    }
                    else
                    {
                        $get_installment_details=$this->penalty_installment_model->getInstallmentId(md5($water_conn_id),$penalty_installment_upto_id);
                        //print_r($get_installment_details);
                        $penalty_installment_id=$get_installment_details['intallment_id'];
                        $total_count_left=$get_installment_details['count'];
                        
                        $penalty+=$get_installment_details['installment_amount'];
                        
                        $count=$this->penalty_installment_model->countExistsUnpaidInstallmentafterId(md5($water_conn_id),$penalty_installment_upto_id);

                        if($count==0 and $total_count_left>0)
                        {
                            $installment_rebate=in_array($conn_payment_status,[0,null])?((10*$penalty_installment_amount)/100):0;
                        }
                        else
                        {
                            $installment_rebate=0;
                        }
                        
                    }
                    
                }
                //echo "sss".$inputs['conn_fee'].'-'.$penalty.'-'.$installment_rebate;
                //echo $penalty;
            
                $total_paid_amount=round($inputs['conn_fee']+$penalty-$installment_rebate);
                //$get_diff_penalty=$this->payment_model->get_penalty_details(md5($water_conn_id));
                //$diff_penalty=$get_diff_penalty['penalty'];
                
               
                $status=1;
                if($payment_mode!='CASH')
                {
                    $status=2;
                    $cheque_no=$inputs['cheque_no'];
                    $cheque_dt=$inputs['cheque_date'];
                    $bank_name=$inputs['bank_name'];
                    $branch_name=$inputs['branch_name'];
                }

                $trans_arr=array();
                $trans_arr['ward_mstr_id']=$ward_id;
                $trans_arr['transaction_type']=$payment_for;
                $trans_arr['transaction_date']=date('Y-m-d');
                $trans_arr['related_id']=$water_conn_id;
                $trans_arr['payment_mode']=$payment_mode;
                $trans_arr['penalty']=$penalty;
                $trans_arr['rebate']=$rebate+$installment_rebate;
                $trans_arr['paid_amount']=$total_paid_amount;
                $trans_arr['total_amount']=$total_amount;
                $trans_arr['emp_details_id']=$emp_id;
                $trans_arr['created_on']=date('Y-m-d H:i:s');
                $trans_arr['status']=$status;
                $trans_arr['payment_from']="JSK";
                $trans_arr['ip_address']=$get_emp_details['ip_address'];
                
                
            
                $check_trans_exist=$this->payment_model->check_transaction_exist($water_conn_id,$total_paid_amount);
                
                
                //print_var($inputs);print_var($check_trans_exist);die;
                if($check_trans_exist==0)
                {
                    $transaction_id=$this->payment_model->insert_transaction($trans_arr);
                    if($transaction_id)
                    {
                        $trans_no="WTRAN".$transaction_id.date('YmdHis');
                        $this->payment_model->update_trans_no($trans_no,$transaction_id);
                        $this->payment_model->update_conn_charge_paid_status($water_conn_id,$transaction_id,$payment_for);

                    
                        

                        if($payment_mode!='CASH')
                        {
                            $chq_arr=array();
                            $chq_arr['transaction_id']=$transaction_id;
                            $chq_arr['cheque_no']=$cheque_no;
                            $chq_arr['cheque_date']=$cheque_dt;
                            $chq_arr['bank_name']=$bank_name;
                            $chq_arr['branch_name']=$branch_name;
                            $chq_arr['emp_details_id']=$emp_id;
                            $chq_arr['created_on']=date('Y-m-d H:i:s');
                            $chq_arr['status']=2;

                            $this->payment_model->insert_cheque_details($chq_arr);
                        }
                        
                        if($connection_type_id==2)
                        {
                            if($pay_full==1)
                            {
                                $this->penalty_installment_model->updateFullInstallment($water_conn_id,$transaction_id);
                                $unpaid_installment_loop=$this->penalty_installment_model->getInstallmentDetailsbyApplyConnectionId(md5($water_conn_id),$transaction_id);
                                foreach($unpaid_installment_loop as $val1)
                                {   
                                    $trans_rebate=array();
                                    $trans_rebate['apply_connection_id']=$water_conn_id;
                                    $trans_rebate['transaction_id']=$transaction_id;
                                    $trans_rebate['head_name']=$val1['penalty_head'];
                                    $trans_rebate['amount']=$val1['installment_amount'];
                                    $trans_rebate['value_add_minus']="+";
                                    $trans_rebate['created_on']=date('Y-m-d H:i:s');
                                    $trans_rebate['status']=1;
                                    
                                    $this->payment_model->insert_fine_rebate($trans_rebate);

                                }
                            }
                            else
                            {
                                $this->penalty_installment_model->updateInstallment($penalty_installment_id,$transaction_id);
                                if($penalty_installment_upto_id>0)
                                {
                                    $penalty_installment=$this->penalty_installment_model->getInstallmentDetails(md5($water_conn_id),$penalty_installment_upto_id,$transaction_id);
                                    foreach($penalty_installment as $val)
                                    {
                                        $trans_rebate=array();
                                        $trans_rebate['apply_connection_id']=$water_conn_id;
                                        $trans_rebate['transaction_id']=$transaction_id;
                                        $trans_rebate['head_name']=$val['penalty_head'];
                                        $trans_rebate['amount']=$val['installment_amount'];
                                        $trans_rebate['value_add_minus']="+";
                                        $trans_rebate['created_on']=date('Y-m-d H:i:s');
                                        $trans_rebate['status']=1;
                                        
                                        $this->payment_model->insert_fine_rebate($trans_rebate);

                                    }

                                }
                            }
                        }

                        if($rebate>0)
                        {
                            $trans_rebate=array();
                            $trans_rebate['apply_connection_id']=$water_conn_id;
                            $trans_rebate['transaction_id']=$transaction_id;
                            $trans_rebate['head_name']="Rebate";
                            $trans_rebate['amount']=$rebate;
                            $trans_rebate['value_add_minus']="-";
                            $trans_rebate['created_on']=date('Y-m-d H:i:s');
                            $trans_rebate['status']=1;

                            $this->payment_model->insert_fine_rebate($trans_rebate);
                        }
                    
                        if($installment_rebate>0)
                        {
                            $trans_rebate=array();
                            $trans_rebate['apply_connection_id']=$water_conn_id;
                            $trans_rebate['transaction_id']=$transaction_id;
                            $trans_rebate['head_name']="Installment Rebate";
                            $trans_rebate['amount']=$installment_rebate;
                            $trans_rebate['value_add_minus']="-";
                            $trans_rebate['created_on']=date('Y-m-d H:i:s');
                            $trans_rebate['status']=1;
                            
                            $this->payment_model->insert_fine_rebate($trans_rebate);
                        }
                        
                        if($other_penalty>0)
                        {
                            $trans_rebate=array();
                            $trans_rebate['apply_connection_id']=$water_conn_id;
                            $trans_rebate['transaction_id']=$transaction_id;
                            $trans_rebate['head_name']="Cheque Bounce Charge";
                            $trans_rebate['amount']=$other_penalty;
                            $trans_rebate['value_add_minus']="+";
                            $trans_rebate['created_on']=date('Y-m-d H:i:s');
                            $trans_rebate['status']=1;
                            
                            $this->payment_model->insert_fine_rebate($trans_rebate);

                            # update status of cheque bounce charge
                            $this->WaterPenaltyModel->updateUnpaidPenalty($water_conn_id, 'Applicant');
                            
                        }
                        
                        if($payment_for=='New Connection')
                        {
                            $this->payment_model->update_payment_status($water_conn_id,$status);
                        }



                        if($doc_status==1 and $status==1 and $payment_for=='New Connection')
                        {
                            $level_pending=array();
                            $level_pending['apply_connection_id']=$water_conn_id;
                            $level_pending['sender_user_type_id']=0;
                            $level_pending['receiver_user_type_id']=12;
                            $level_pending['created_on']=date('Y-m-d H:i:s');
                            $level_pending['emp_details_id']=$emp_id;
                            $this->payment_model->insert_level_pending($level_pending);
                        }


                        if($payment_for=='Site Inspection' and $status==1)
                        {
                            //$this->site_ins_model->update_site_ins_pay_status($water_conn_id);
                            $sql_my="update tbl_site_inspection set payment_status = 1
                                     where scheduled_status = 1 
                                        and md5(apply_connection_id::text) = '$water_conn_id_md5' 
                                        and verified_by = 'JuniorEngineer' 
                                        and payment_status = 0 
                                        and id = $si_verify_id ";
                                
                            $this->WaterMobileModel->getDataRowQuery2($sql_my);
                            if($status==1)
                            {
                                $level_pending_arr=array();
                                $level_pending_arr['apply_connection_id']=$water_conn_id;
                                $level_pending_arr['sender_user_type_id']=13;
                                $level_pending_arr['receiver_user_type_id']=14;
                                $level_pending_arr['created_on']=date('Y-m-d H:i:s');
                                $level_pending_arr['emp_details_id']=$emp_id;
                                $this->payment_model->insert_level_pending($level_pending_arr);
                                $sql = "UPDATE tbl_level_pending SET forward_date='".date('Y-m-d')."',
                                                forward_time='".date('H:i:s')."',
                                                remarks='Aouto Forword',
                                                verification_status=1,
                                                receiver_user_id = 1
                                                FROM
                                                (
                                                    SELECT id
                                                    FROM tbl_level_pending
                                                    WHERE verification_status=0 
                                                        AND status !=0
                                                        AND apply_connection_id = $water_conn_id
                                                        AND receiver_user_type_id = 13  
                                                    ORDER BY id DESC
                                                    LIMIT 1                                                  
                                                ) subquery
                                        WHERE tbl_level_pending.verification_status=0 
                                            AND tbl_level_pending.apply_connection_id = $water_conn_id
                                            AND tbl_level_pending.receiver_user_type_id = 13
                                            AND tbl_level_pending.id = subquery.id
                                                ";
                                // print_var($sql);die;
                                $this->db->query($sql);
                                
                            }

                        }
                    }

                

                    if($this->db->transStatus() === FALSE)
                    {
                        $this->db->transRollback();
                        flashToast("payment", "Something went wrong in payment!!!");
                        return $this->response->redirect(base_url('WaterPayment/payment/'.md5($water_conn_id)));
                    }
                    else
                    {
                        
                        $this->db->transCommit();

                        //echo $water_conn_id.'-'.$transaction_id;

                        $mobile_no=$this->search_applicant_mobile_model->getMobileNo(md5($water_conn_id));
                        $sms="Your Water Connection Payment of Rs. ".$total_paid_amount." for Application No.  ".$application_no." is successfully done. ".$this->get_ulb_detail['ulb_name'];
                        SMSJHGOVT($mobile_no,$sms);
                        return $this->response->redirect(base_url('WaterPayment/view_transaction_receipt/'.md5($water_conn_id).'/'.md5($transaction_id)));
                    }

                }
                else
                {
                    flashToast("payment", "Transaction Already Done!!!");
                        return $this->response->redirect(base_url('WaterPayment/payment/'.md5($water_conn_id)));
                }

                
            }
        }  

    }

    public function bulk_demand()
    {
        $data =(array)null;
        $Session = session();
        $ulb_dtl = $Session->get('ulb_dtl');
        $data['ulb_dtl'] = $ulb_dtl;
        $ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];
        $data['ulb_id']= $ulb_mstr_id;

        $data['ulb_mstr_id']=$this->ulb_id;
        $ward_whare=[
            'where'=>['status '=>[1],'ulb_mstr_id'=>[$ulb_mstr_id]],
            'tbl'=>'view_ward_mstr',
            'column'=>['id','ward_no','ulb_mstr_id','status']
        ];
        $data['ward']=$this->WaterMobileModel->getDataNew($ward_whare['where'],$ward_whare['column'],$ward_whare['tbl'],array(),array('ward_no'=>'ASC'));
        
        $uri_string = uri_string();
        if (isset($_GET['page']) && $_GET['page']== 'clr')
        {
            $Session->remove('tempData'); //echo('remove');
        }
       
        if($this->request->getMethod()=='post')
        {
            $rules = ['ward_id'=>[
                'rules'=>'required',
                'errors'=>[
                    'required'=>'Please select Ward No.'
                ]
            ]];
            if(!$this->validate($rules))
            {
                $data['validation']=$this->validator; 
                return redirect()->back()->with('fail','Somthing is Wrong!');
            }
            
            $inputs = arrFilterSanitizeString($this->request->getVar());
            $Session->set('tempData', $inputs);
        }
        $tempData=$Session->get('tempData');
        if(!empty($tempData))
        {
            $data['ward_id'] = $tempData['ward_id'];
            $data["consumer_no"] = trim($tempData['consumer_no']??null);
            $ward = $data['ward_id'];
            $where = ' ';$where2=' ';
            if($ward != '')
            {
                $where = " where w.id = $ward ";
                $where2 = " and c. ward_mstr_id = $ward ";
                if($data["consumer_no"])
                {
                    $where2 .= " and c. consumer_no ILIKE'%".$data["consumer_no"]."%'";
                }
            }

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
                    where c.status = 1 and d.status = 1 and d.paid_status = 0 --and d.consumer_id in(11233,1220)
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
                $where
            ";
        
            
            $sql.=" order by w.ward_no  ";
            //print_var($sql);
            $data['bulk_demand']=$this->WaterMobileModel->getDataRowQuery2($sql); 
            $export= !empty($this->request->getPost('export')) ? true: false;
            if($export)
            {
                
                exporttoexcel($data['bulk_demand'],'hello');
            } 
            //print_var($data['bulk_demand']['result']);          

        }
        return view('water/report/bulk_demand', $data);

    }

    public function mothe_meter_non_meter()
    {
        $data['fy_list']=fy_year_list();
        $cur_fy = $data['fy_list'][0];
        
        if(!empty($_POST))
        {
            $cur_fy= $_POST['fy_year'];
        }
        $fy = explode('-',$cur_fy);
        $priv_from=($fy[0]-1).'-04-01';
        $data['priv_fy']=$fy[0]-1;
        //echo $priv_from;
        $from=$fy[0].'-04-01';
        $to = $fy[1].'-04-01';
        // $from='2020-04-01';
        // $to = '2021-04-01';
        
        $sql  = "with meter_status as (
                    select consumer_id,
                        case when connection_type = 1 then 'Meter'			
                            else 'Non_Meter' end as meter_stustus,
                        row_number() over(partition by consumer_id order by id desc) as row_num
                    from tbl_meter_status
                    --order by consumer_id
                )
                    select count(c.id) as total,
                        substring(cast(c.created_on as date)::text,1,7) as yyyy_mm,
                        substring(cast(c.created_on as date)::text,6,2) as mm,
                        m.meter_stustus
                    from  tbl_consumer c
                    left join meter_status m on m.consumer_id = c.id and m.row_num = 1
                    where status = 1 and cast(c.created_on as date)>='$from' and cast(c.created_on as date)<'$to'
                    group by  substring(cast(c.created_on as date)::text,6,2),substring(cast(c.created_on as date)::text,1,7),
                        m.meter_stustus";
            $reports=$this->WaterMobileModel->getDataRowQuery2($sql);
            $data['reports']=$reports['result']?true:false;
            //print_var($sql);
            $id = "Meter";
            $Meter = array_filter( $reports['result'], function($obj) use ($id) {
               
                    if ($obj['meter_stustus'] == $id) 
                    {
                        //print_var($obj);
                        return true;
                    }
               
                return false;
                
            });  
            $Meter = array_values($Meter);          
            //print_var($Meter);

            //$id = "Non_Meter";
            $Non_Meter = array_filter( $reports['result'], function($obj) use ($id) {
               
                    if ($obj['meter_stustus'] != $id) 
                    {
                        //print_var($obj);
                        return true;
                    }
               
                return false;
                
            });  
            $Non_Meter = array_values($Non_Meter);          
            //print_var($Non_Meter);
            //print_var($data['reports']);
            $data['Meter']=$Meter;
            $data['Non_Meter']=$Non_Meter;
            function array_arraeng($temp_data)
            {
                $temp = [];
                $temp_data = array_values($temp_data); 
                // if($temp_data && sizeof($temp_data)>0)
                {                   
                    foreach($temp_data as $key=>$val)
                    {
                        //print_var($val);
                        if($val['meter_stustus']=='Meter' && $key==0)
                        {
                            $temp[0]=$temp_data[$key];                             
                        }
                        else
                        {
                            $temp[1]=$temp_data[$key];
                        }
                    }                    
                } 
                if(sizeof($temp)==1)
                {
                    if(!isset($temp[0]))
                        $temp[0]=array();
                    else
                        $temp[1]=array();
                } 
                return $temp;
            }

            $data['jan'] = array_filter( $reports['result'], function($obj) use ($id) {               
                                if ($obj['mm']==1) 
                                {
                                    //print_var($obj);
                                    return true;
                                }  
                                return false;                            
            });           
                        
            $data['jan']=array_arraeng($data['jan']);                    
                  
            $data['feb'] = array_filter( $reports['result'], function($obj) use ($id) {
    
                            if ($obj['mm']==2) 
                            {                               
                                return true;
                            }                            
                    
                            return false;
                        
                    }); 
            $data['feb']=array_arraeng($data['feb']);
             
            $data['mar'] = array_filter( $reports['result'], function($obj) use ($id) 
                    {                    
                        if ($obj['mm']==3) 
                        {
                            return true;
                        }                                              
                        return false;                                
                    }
                );
            $data['mar']=array_arraeng($data['mar']); 
            //print_var($data['mar']);die;
            $data['apr'] = array_filter( $reports['result'], function($obj) use ($id) 
                {                    
                    if ( $obj['mm']==4) 
                    {
                        //print_var($obj);
                        return true;
                    }
                                          
                    return false;                                
                }
            );
            $data['apr']=array_arraeng($data['apr']);  
            $data['may'] = array_filter( $reports['result'], function($obj) use ($id) 
                                {                    
                                    if ( $obj['mm']==5) 
                                    {
                                        //print_var($obj);
                                        return true;
                                    }                                                           
                                    return false;                                
                                }
                            ); 
            $data['may']=array_arraeng($data['may']);  
            $data['jun'] = array_filter( $reports['result'], function($obj) use ($id) 
                            {                    
                                if ($obj['mm']==6) 
                                {
                                    //print_var($obj);
                                    return true;
                                }
                                                       
                                return false;                                
                            }
                        );
            $data['jun']=array_arraeng($data['jun']);          
            $data['jul'] = array_filter( $reports['result'], function($obj) use ($id) 
                            {                    
                                if ( $obj['mm']==7) 
                                {
                                    //print_var($obj);
                                    return true;
                                }
                                                        
                                return false;                                
                            }
                        ); 
            $data['jul']=array_arraeng($data['jul']);  
            $data['aug'] = array_filter( $reports['result'], function($obj) use ($id) 
                        {                    
                            if ( $obj['mm']==8) 
                            {
                                //print_var($obj);
                                return true;
                            }
                                                   
                            return false;                                
                        }
                    );
            $data['aug']=array_arraeng($data['aug']);  
            $data['sep'] = array_filter( $reports['result'], function($obj) use ($id) 
                        {                    
                            if ($obj['mm']==9) 
                            {
                                //print_var($obj);
                                return true;
                            }
                                                  
                            return false;                                
                        }
                    ); 
            $data['sep']=array_arraeng($data['sep']); 
            $data['oct'] = array_filter( $reports['result'], function($obj) use ($id) 
                            {                    
                                if ($obj['mm']==10) 
                                {
                                    //print_var($obj);
                                    return true;
                                }
                                                       
                                return false;                                
                            }
                        );  
            $data['oct']=array_arraeng($data['oct']); 
            $data['nov'] = array_filter( $reports['result'], function($obj) use ($id) 
                        {                    
                            if ($obj['mm']==11) 
                            {
                                //print_var($obj);
                                return true;
                            }
                                                  
                            return false;                                
                        }
                    ); 
            $data['nov']=array_arraeng($data['nov']);
            $data['dec'] = array_filter( $reports['result'], function($obj) use ($id) 
                    {                    
                        if ( $obj['mm']==12) 
                        {
                            //print_var($obj);
                            return true;
                        }
                                                
                        return false;                                
                    }
                ); 
            $data['dec']=array_arraeng($data['dec']);
            //print_var($data);
            
            return view('water/report/mothe_meter_non_meter',$data);
    }

    public function colletion_report()
    {
        $data =(array)null;
        $data['ulb_mstr_id']=$this->ulb_id;        
        $data['team_leader']='';
        $tc=[
            'where'=>['user_type_id'=>[8,5,4]],
            'tbl'=>'view_emp_details',
            'column'=>['id','emp_name','user_type','lock_status','employee_code']
        ];
        $data['tc']=$this->WaterMobileModel->getDataNew($tc['where'],$tc['column'],$tc['tbl'],array(),array('emp_name'=>'ASC'));
        if($this->request->getMethod()=='post')
        {
 
            try{

                ## Read value
                $start = sanitizeString($this->request->getVar('start'));
               
                $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page

                $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
                $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
                 if ($columnName=="s_no")
                     $columnName = 'tbl_transaction.id';                    
                if ($columnName=="ward_no")
                     $columnName = 'view_ward_mstr.ward_no';
                if ($columnName=="transaction_no")                   
                    $columnName = 'tbl_transaction.transaction_no';
                if ($columnName=="transaction_date")
                    $columnName = 'tbl_transaction.transaction_date';
                if ($columnName=="application_no")
                    $columnName = 'tbl_apply_water_connection.application_no';
                if ($columnName=="consumer_no")
                    $columnName = 'tbl_consumer.consumer_no';
                if ($columnName=="payment_mode")
                    $columnName = 'tbl_transaction.payment_mode';
                if ($columnName=="transaction_type")
                    $columnName = 'tbl_transaction.transaction_type';
                if ($columnName=="paid_amount")
                    $columnName = 'tbl_transaction.paid_amount';
                if ($columnName=="emp_name")
                    $columnName = 'view_emp_details.emp_name';
                else
                    $columnName = 'tbl_transaction.id';
 
                //$columnName = "tbl_transaction.tran_date";
                $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
                $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value
                
                $searchQuery = "";                
                $total = 0;
                                
                // Date filter
                $from_date = sanitizeString($this->request->getVar('from_date'));
                $to_date = sanitizeString($this->request->getVar('to_date'));          
                $tc_id = sanitizeString($this->request->getVar('tc_id'));
                //$app_type = sanitizeString($this->request->getVar('appl_type'));
                $pay_mode = sanitizeString($this->request->getVar('paymnt_mode')); 
                $data['to_date'] = $to_date;
                $data['from_date'] = $from_date;
                $data['tc_id']=$tc_id;
                //$data['app_type'] = $app_type;
                $data['pay_mode']=$pay_mode;
                $whereQuery = " tbl_transaction.transaction_date between '".$from_date."' and '".$to_date."'";
                 
                if($pay_mode !="all")
                { 
                    if(strtoupper($pay_mode)==strtoupper('Online'))
                    {
                        $whereQuery .= "  AND  upper(tbl_transaction.payment_mode) in(upper('Online'),upper('Onl'))"; 
                    }
                    else
                        $whereQuery .= "  AND  upper(tbl_transaction.payment_mode) =upper('".$pay_mode."')"; 

                }
                if($tc_id !="0")
                {
                    $whereQuery .= "  AND  tbl_transaction.emp_details_id =  '".$tc_id."'";  
                }

                $orderBY = " ORDER BY ".$columnName." ".$columnSortOrder;
                $limit = " LIMIT ".$rowperpage." OFFSET ".$start;
                
                $whereQueryWithSearch = "";
                if ($searchValue!='') 
                {
                      
                $whereQueryWithSearch = " AND (tbl_apply_water_connection.application_no ILIKE '%".$searchValue."%'
                                        OR tbl_consumer.consumer_no ILIKE '%".$searchValue."%'
                                        OR view_ward_mstr.ward_no ILIKE '%".$searchValue."%'
                                        OR tbl_transaction.transaction_no ILIKE '%".$searchValue."%'
                                        OR tbl_transaction.payment_mode ILIKE '%".$searchValue."%'
                                        OR tbl_transaction.transaction_type ILIKE '%".$searchValue."%'
                                        OR view_emp_details.emp_name ILIKE '%".$searchValue."%')";
                                    
                } 
                $base_url = base_url();
                $selectStatement = "SELECT 
                ROW_NUMBER () OVER (ORDER BY ".$columnName." DESC) AS s_no,
                tbl_transaction.id, tbl_transaction.transaction_no,tbl_transaction.transaction_type,
                tbl_transaction.transaction_date,tbl_transaction.payment_mode ,tbl_transaction.paid_amount,
                 tbl_consumer.id as consumer_id, tbl_consumer.consumer_no,
                tbl_apply_water_connection.id as application_id , tbl_apply_water_connection.application_no,
                view_ward_mstr.ward_no,
                CONCAT(view_emp_details.emp_name,'(',view_emp_details.employee_code,')') as emp_name,                
                CASE WHEN  transaction_type in('New Connection','Penlaty Instalment','Site Inspection') 
                        then CONCAT('<a href=', chr(39), '".base_url()."/WaterPayment/view_transaction_receipt/', MD5(tbl_transaction.related_id::TEXT), '/', MD5(tbl_transaction.id::TEXT), chr(39), ' target=', chr(39), '_blank', chr(39), ' class=', chr(39), 'btn btn-info', chr(39), '>View</a>')
                    when transaction_type='Demand Collection' 
                        then CONCAT('<a href=', chr(39), '".base_url()."/WaterUserChargePayment/payment_tc_receipt/', MD5(tbl_transaction.related_id::TEXT), '/', MD5(tbl_transaction.id::TEXT), chr(39), ' target=', chr(39), '_blank', chr(39), ' class=', chr(39), 'btn btn-info', chr(39), '>View</a>')
                    END AS link
                 ";      

                $sql =" from tbl_transaction
                        join view_ward_mstr on view_ward_mstr.id = tbl_transaction.ward_mstr_id
                        left join tbl_consumer on tbl_consumer.id = tbl_transaction.related_id and transaction_type='Demand Collection'
                        left join tbl_apply_water_connection on tbl_apply_water_connection.id = tbl_transaction.related_id 
                            and transaction_type in('New Connection','Penlaty Instalment','Site Inspection')
                        left join view_emp_details on view_emp_details.id = tbl_transaction.emp_details_id
                        where tbl_transaction.status in(1,2) 
                       and ".$whereQuery;

                $totalRecords = $this->model_datatable->getTotalRecords($sql);  
                  
                // return json_encode([$totalRecords]);
                if ($totalRecords>0) 
                { 
                    ## Total number of records with filtering
                        $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($sql.$whereQueryWithSearch);
                    ## Fetch records
                        $fetchSql = $selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit;
                        //print_var($fetchSql);die;
                        $records = $this->model_datatable->getRecords($fetchSql);
                        $totalsql = " select SUM(paid_amount) as total ".$sql.$whereQueryWithSearch;
                        $total = $this->model_datatable->getRecords($totalsql)[0]['total']??0;
                   // return json_encode($records);
                } 
                else 
                {
                    $totalRecordwithFilter = 0;
                    $records = [];
                }
                //echo $this->db->getLastQuery();
                $response = array(
                    "iTotalRecords" => $totalRecords,
                    "iTotalDisplayRecords" => $totalRecordwithFilter,
                    "aaData" => $records,
                    "total"  => $total,
                );
                return json_encode($response);
            }
            catch(Exception $e)
            {

            }
        }
        return view('water/report/colletion_report', $data);

    }

    public function demad_reports()
    {
        $data = (array)null;
        $Session = Session();
        if ($this->request->getMethod() == 'post') 
        {
            try {

                ## Read value
                $start = sanitizeString($this->request->getVar('start'));

                $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page

                $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
                $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
                if ($columnName == "s_no" )
                    $columnName = 'tbl_consumer.id';
                elseif ($columnName == "Meter_status")
                    $columnName = 'meter_status.connection_type';
                elseif ($columnName == "demand_remark")
                    $columnName = 'demand_dtl.consumer_id';
                elseif ($columnName == "owner_name")
                    $columnName = 'owner.owner_name';
                elseif ($columnName == "father_name")
                    $columnName = 'owner.father_name';
                elseif ($columnName == "mobile_no")
                    $columnName = 'owner.mobile_no';
                elseif ($columnName == "ward_no")
                    $columnName = 'view_ward_mstr.ward_no';
                else
                    $columnName = 'tbl_consumer.id';
                

                $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
                $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value

                
                $genrated = sanitizeString($this->request->getVar('genrated')); 
                $consumer_type = sanitizeString($this->request->getVar('consumer_type'));
                $connection_type = sanitizeString($this->request->getVar('connection_type'));
                $ward_id = sanitizeString($this->request->getVar('ward_id'));
                $consumer_type_wher = " AND 1=1 ";
           
                if($consumer_type=='Dry Consumer')
                {
                    $consumer_type_wher =" AND (tbl_consumer.apply_from ='Existing' AND tbl_consumer.created_on::date>='2021-10-26')"; 
                }
                if($consumer_type=='Main Consumer')
                {
                    $consumer_type_wher =" AND supper_Dray.consumer_id ISNULL AND ((tbl_consumer.apply_from ='Existing' AND tbl_consumer.created_on::date>='2021-10-26'))=FALSE"; 
                }
                if($consumer_type=='Supper Dry Consumer')
                {
                    $consumer_type_wher =" AND supper_Dray.consumer_id IS NOT NULL AND ((tbl_consumer.apply_from ='Existing' AND tbl_consumer.created_on::date>='2021-10-26'))=FALSE"; 
                }
                if($consumer_type=='Dry Consumer & Main Consumer')
                {
                    $consumer_type_wher =" AND ((tbl_consumer.apply_from ='Existing' AND tbl_consumer.created_on::date>='2021-10-26') OR (supper_Dray.consumer_id ISNULL))"; 
                }
                if($consumer_type=='Dry Consumer & Supper Dry Consumer')
                {
                    $consumer_type_wher =" AND ((tbl_consumer.apply_from ='Existing' AND tbl_consumer.created_on::date>='2021-10-26') OR (supper_Dray.consumer_id IS NOT NULL))"; 
                }
                if($consumer_type=='Main Consumer & Supper Dry Consumer')
                {
                    $consumer_type_wher =" AND (((tbl_consumer.apply_from ='Existing' AND tbl_consumer.created_on::date>='2021-10-26'))=FALSE)"; 
                }
                
                $keyword = sanitizeString($this->request->getVar('keyword'));
                $upto_date =sanitizeString($this->request->getVar('upto_date'))??date('Y-m-d');
                $searchQuery = "";
                $whereQuery = "";

                $orderBY = " ORDER BY " . $columnName . " " . $columnSortOrder;
                $limit = " LIMIT " . $rowperpage . " OFFSET " . $start;

                if ($genrated == "genrated") 
                {
                    $whereQuery .= " AND  demand_dtl.consumer_id IS NOT NULL ";                    
                } 
                elseif($genrated == "not_genrated") 
                {
                    $whereQuery .= " AND  demand_dtl.consumer_id IS NULL  ";
                   
                }

                if ($keyword != "") 
                {
                    $whereQuery .= " AND  (tbl_consumer.consumer_no ~* '$keyword' 
                                            OR owner.owner_name ~* '$keyword' 
                                            OR owner.father_name ~* '$keyword' 
                                            OR owner.mobile_no ~* '$keyword' )";
                    
                }
                if(($ward_id != 'All') && $ward_id)
                {
                    $whereQuery .= " AND tbl_consumer.ward_mstr_id = $ward_id ";
                } 

                if($connection_type == 1)
                {
                    $whereQuery .= " AND meter_status.connection_type in (1,2)";
                }
                if($connection_type == 3)
                {
                    $whereQuery .= " AND meter_status.connection_type not in (1,2)";
                }

                $whereQueryWithSearch = "";
                if ($searchValue != '') 
                {
                    $whereQueryWithSearch = " AND (tbl_consumer.consumer_no ~* '$searchValue' 
                                                    OR owner.owner_name ~* '$searchValue' 
                                                    OR owner.father_name ~* '$searchValue' 
                                                    OR owner.mobile_no ~* '$searchValue')";
                }

                
                $selectStatement = "SELECT 
                                        ROW_NUMBER () OVER (ORDER BY " . $columnName . ") AS s_no,				  
                                        tbl_consumer.id,
                                        view_ward_mstr.ward_no,
                                        CASE WHEN demand_dtl.consumer_id IS NULL THEN 'NO' ELSE 'YES' END AS demand_remark,        
                                        case when meter_status.connection_type=1 then 'Meter' 
                                            when meter_status.connection_type=2 then'Galen'
                                            when meter_status.connection_type=3 then'Fixed'
                                            else 'xxxx' end as meter_status,
                                        meter_status.connection_date as connection_date,
                                        owner_name,father_name,mobile_no,
                                        tbl_consumer.consumer_no,tbl_consumer.category,
                                        demand_dtl.max_demand_upto,                                        
                                        CASE WHEN tbl_consumer.apply_from ='Existing' AND tbl_consumer.created_on::date>='2021-10-26' THEN 'Dry Consumer'
                                            WHEN supper_Dray.consumer_id ISNULL THEN 'Main Consumer'
                                            ELSE  'Supper Dry Consumer'
                                        END AS consumer_Type ,
                                        last_demands.last_demands,last_demands.demand_type,
                                        last_demands.last_meter_reading,
                                        concat('<a class=', chr(39),'btn btn-primary', chr(39), 'href=" . base_url() . "/WaterViewConsumerDetails/index/',md5(tbl_consumer.id::text),' role=button>View</a>') as view
                ";
                $sql =" FROM tbl_consumer
                        join (
                            select distinct(consumer_id) as consumer_id,
                                string_agg( applicant_name , ', ') as owner_name,
                                string_agg( father_name, ', ') as father_name,
                                string_agg( mobile_no::text, ', ') as mobile_no
                            from tbl_consumer_details
                            where status =1 
                            group by consumer_id
                        ) owner on owner.consumer_id = tbl_consumer.id 
                        left join view_ward_mstr on view_ward_mstr.id = tbl_consumer.ward_mstr_id
                        LEFT JOIN(
                            SELECT distinct(tbl_consumer.id) as consumer_id,
                                tbl_transaction.id as transaction_id
                            FROM tbl_consumer
                            LEFT JOIN tbl_transaction on tbl_transaction.related_id = tbl_consumer.id 
                                and tbl_transaction.status in (1,2)
                                and tbl_transaction.transaction_type = 'Demand Collection'
                            WHERE tbl_consumer.status=1        
                                AND tbl_transaction.id isnull
                        
                        ) supper_Dray on supper_Dray.consumer_id = tbl_consumer.id
                        left join (
                            select connection_type,consumer_id,connection_date 
                            from tbl_meter_status 
                            inner join (
                                select max(id) as max_id
                                from tbl_meter_status 
                                where status = 1
                                group by consumer_id
                            ) AS abc ON abc.max_id=tbl_meter_status.id
                            
                        ) meter_status on meter_status.consumer_id=tbl_consumer.id
                        LEFT JOIN (
                            SELECT
                                consumer_id,max(demand_upto) as max_demand_upto
                            FROM tbl_consumer_demand
                            WHERE 
                                status=1 
                                AND demand_upto >='$upto_date'
                            GROUP BY consumer_id
                        ) AS demand_dtl ON demand_dtl.consumer_id=tbl_consumer.id
                        LEFT JOIN (
                            select 
                                 distinct tbl_consumer_demand.consumer_id,tbl_consumer_demand.demand_upto as last_demands ,
                                tbl_consumer_demand.connection_type as demand_type,
                                max( case when tbl_consumer_demand.current_meter_reading is null then last_meter_data.initial_reading
                                        else tbl_consumer_demand.current_meter_reading 
                                    end ) as last_meter_reading
                            from tbl_consumer_demand
                            join(
                                select max(tbl_consumer_demand.id) as last_id
                                    from tbl_consumer_demand
                                    join(
                                        SELECT consumer_id,max(demand_upto) as demand_upto
                                        FROM tbl_consumer_demand
                                        WHERE 
                                            status=1 
                                        GROUP BY consumer_id
                                    ) latest on latest.consumer_id = tbl_consumer_demand.consumer_id and latest.demand_upto =tbl_consumer_demand.demand_upto
                                 WHERE status=1 
                                 GROUP BY tbl_consumer_demand.consumer_id
                            )maxs on maxs.last_id = tbl_consumer_demand.id 
                            left join(
                                select consumer_id,initial_reading
                                from tbl_consumer_initial_meter    
                                where status = 1
                                    and id in(
                                        select max(id) from tbl_consumer_initial_meter where status =1 group by consumer_id
                                    )
                            ) last_meter_data on last_meter_data.consumer_id = tbl_consumer_demand.consumer_id
                            WHERE status=1 
                            group by tbl_consumer_demand.consumer_id,tbl_consumer_demand.demand_upto,tbl_consumer_demand.connection_type
                        )AS last_demands on last_demands.consumer_id = tbl_consumer.id
                        where tbl_consumer.status =1 
                " . $whereQuery." ". $consumer_type_wher; 
                
                $totalRecords = $this->model_datatable->getTotalRecords($sql);
                if ($totalRecords > 0) 
                {
                    ## Total number of records with filtering
                    $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($sql . $whereQueryWithSearch);
                    ## Fetch records
                    $fetchSql = $selectStatement . $sql . $whereQueryWithSearch . $orderBY . $limit;
                    // print_var($fetchSql);die;
                    $records = $this->model_datatable->getRecords($fetchSql, false);
                    
                } 
                else 
                {
                    $totalRecordwithFilter = 0;
                    $records = [];
                }
                
                $response = array(
                    "iTotalRecords" => $totalRecords,
                    "iTotalDisplayRecords" => $totalRecordwithFilter,
                    "aaData" => $records,
                    "total" => '<b style="padding-right:20px">Total :-' . $totalRecords . '</b>',
                );
                return json_encode($response);
            } 
            catch (Exception $e) 
            {
                print_var($e->getMessage());die;
            }
        }
        else
        {
            $ward_whare=[
                'where'=>['status '=>[1],'ulb_mstr_id'=>[$this->ulb_id]],
                'tbl'=>'view_ward_mstr',
                'column'=>['id','ward_no','ulb_mstr_id','status']
            ];
            $data['ward']=$this->WaterMobileModel->getDataNew($ward_whare['where'],$ward_whare['column'],$ward_whare['tbl'],array(),array('ward_no'=>'ASC'));
            return view('water/report/demand_reports',$data);
        }
    }

    public function demad_reportsExcel($genrated = null,$upto_date = null,$ward_id = 'All',$keyword = null,$connection_type=null,$consumer_type=null)
    {
        try{
                if($upto_date==null)
                {
                    $upto_date=date('Y-m-d');
                }
                
                $whereQueryWithSearch = "";
                $whereQuery=" ";
                if ($genrated == "genrated") 
                {
                    $whereQuery .= " AND  demand_dtl.consumer_id IS NOT NULL ";                    
                } 
                elseif($genrated == "not_genrated") 
                {
                    $whereQuery .= " AND  demand_dtl.consumer_id IS NULL  ";
                   
                }

                if ($keyword != "xxx") 
                {
                    $whereQuery .= " AND  (tbl_consumer.consumer_no ~* '$keyword' 
                                            OR owner.owner_name ~* '$keyword' 
                                            OR owner.father_name ~* '$keyword' 
                                            OR owner.mobile_no ~* '$keyword' )";
                    
                } 
                if($ward_id && $ward_id!='All')
                {
                    $whereQuery .= " AND tbl_consumer.ward_mstr_id = $ward_id ";
                }
                if($connection_type == 1)
                {
                    $whereQuery .= " AND meter_status.connection_type in (1,2)";
                }
                if($connection_type == 3)
                {
                    $whereQuery .= " AND meter_status.connection_type not in (1,2)";
                }
                
                $consumer_type_wher = " AND 1=1 ";
           
                if($consumer_type=='Dry Consumer')
                {
                    $consumer_type_wher =" AND (tbl_consumer.apply_from ='Existing' AND tbl_consumer.created_on::date>='2021-10-26')"; 
                }
                if($consumer_type=='Main Consumer')
                {
                    $consumer_type_wher =" AND supper_Dray.consumer_id ISNULL AND ((tbl_consumer.apply_from ='Existing' AND tbl_consumer.created_on::date>='2021-10-26'))=FALSE"; 
                }
                if($consumer_type=='Supper Dry Consumer')
                {
                    $consumer_type_wher =" AND supper_Dray.consumer_id IS NOT NULL AND ((tbl_consumer.apply_from ='Existing' AND tbl_consumer.created_on::date>='2021-10-26'))=FALSE"; 
                }
                if($consumer_type=='Dry Consumer & Main Consumer')
                {
                    $consumer_type_wher =" AND ((tbl_consumer.apply_from ='Existing' AND tbl_consumer.created_on::date>='2021-10-26') OR (supper_Dray.consumer_id ISNULL))"; 
                }
                if($consumer_type=='Dry Consumer & Supper Dry Consumer')
                {
                    $consumer_type_wher =" AND ((tbl_consumer.apply_from ='Existing' AND tbl_consumer.created_on::date>='2021-10-26') OR (supper_Dray.consumer_id IS NOT NULL))"; 
                }
                if($consumer_type=='Main Consumer & Supper Dry Consumer')
                {
                    $consumer_type_wher =" AND (((tbl_consumer.apply_from ='Existing' AND tbl_consumer.created_on::date>='2021-10-26'))=FALSE)"; 
                }

                $selectStatement = "SELECT
                                    tbl_consumer.id,
                                    view_ward_mstr.ward_no,
                                    CASE WHEN demand_dtl.consumer_id IS NULL THEN 'NO' ELSE 'YES' END AS demand_remark,        
                                    case when meter_status.connection_type=1 then 'Meter' 
                                        when meter_status.connection_type=2 then'Galen'
                                        when meter_status.connection_type=3 then'Fixed'
                                        else 'xxxx' end as meter_status,
                                    meter_status.connection_date as connection_date,
                                    owner_name,father_name,mobile_no,
                                    tbl_consumer.consumer_no,tbl_consumer.category, 
                                    tbl_consumer.address,                                       
                                    CASE WHEN tbl_consumer.apply_from ='Existing' AND tbl_consumer.created_on::date>='2021-10-26' THEN 'Dry Consumer'
                                        WHEN supper_Dray.consumer_id ISNULL THEN 'Main Consumer'
                                        ELSE  'Supper Dry Consumer'
                                    END AS consumer_Type ,
                                    demand_dtl.max_demand_upto,
                                    last_demands.last_demands,last_demands.demand_type,
                                    last_demands.last_meter_reading 
                ";

                $sql =" FROM tbl_consumer
                        join (
                            select distinct(consumer_id) as consumer_id,
                                string_agg( applicant_name , ', ') as owner_name,
                                string_agg( father_name, ', ') as father_name,
                                string_agg( mobile_no::text, ', ') as mobile_no
                            from tbl_consumer_details
                            where status =1 
                            group by consumer_id
                        ) owner on owner.consumer_id = tbl_consumer.id 
                        LEFT JOIN view_ward_mstr on view_ward_mstr.id = tbl_consumer.ward_mstr_id
                        LEFT JOIN(
                            SELECT distinct(tbl_consumer.id) as consumer_id,
                                tbl_transaction.id as transaction_id
                            FROM tbl_consumer
                            LEFT JOIN tbl_transaction on tbl_transaction.related_id = tbl_consumer.id 
                                and tbl_transaction.status in (1,2)
                                and tbl_transaction.transaction_type = 'Demand Collection'
                            WHERE tbl_consumer.status=1        
                                AND tbl_transaction.id isnull
                        
                        ) supper_Dray on supper_Dray.consumer_id = tbl_consumer.id
                        left join (
                            select connection_type,consumer_id,connection_date 
                            from tbl_meter_status 
                            inner join (
                                select max(id) as max_id
                                from tbl_meter_status 
                                where status = 1
                                group by consumer_id
                            ) AS abc ON abc.max_id=tbl_meter_status.id
                            
                        ) meter_status on meter_status.consumer_id=tbl_consumer.id
                        LEFT JOIN (
                            SELECT
                                consumer_id,max(demand_upto) as max_demand_upto
                            FROM tbl_consumer_demand
                            WHERE 
                                status=1 
                                AND demand_upto >='$upto_date'
                            GROUP BY consumer_id
                        ) AS demand_dtl ON demand_dtl.consumer_id=tbl_consumer.id
                        LEFT JOIN (
                            select 
                                 distinct tbl_consumer_demand.consumer_id,tbl_consumer_demand.demand_upto as last_demands ,
                                tbl_consumer_demand.connection_type as demand_type,
                                max( case when tbl_consumer_demand.current_meter_reading is null then last_meter_data.initial_reading
                                        else tbl_consumer_demand.current_meter_reading 
                                    end ) as last_meter_reading
                            from tbl_consumer_demand
                            join(
                                select max(tbl_consumer_demand.id) as last_id
                                    from tbl_consumer_demand
                                    join(
                                        SELECT consumer_id,max(demand_upto) as demand_upto
                                        FROM tbl_consumer_demand
                                        WHERE 
                                            status=1 
                                        GROUP BY consumer_id
                                    ) latest on latest.consumer_id = tbl_consumer_demand.consumer_id and latest.demand_upto =tbl_consumer_demand.demand_upto
                                 WHERE status=1 
                                 GROUP BY tbl_consumer_demand.consumer_id
                            )maxs on maxs.last_id = tbl_consumer_demand.id 
                            left join(
                                select consumer_id,initial_reading
                                from tbl_consumer_initial_meter    
                                where status = 1
                                    and id in(
                                        select max(id) from tbl_consumer_initial_meter where status =1 group by consumer_id
                                    )
                            ) last_meter_data on last_meter_data.consumer_id = tbl_consumer_demand.consumer_id
                            WHERE status=1 
                            group by tbl_consumer_demand.consumer_id,tbl_consumer_demand.demand_upto,tbl_consumer_demand.connection_type
                        )AS last_demands on last_demands.consumer_id = tbl_consumer.id
                        where tbl_consumer.status =1                  
                        ".$whereQuery." ". $consumer_type_wher;

            $fetchSql = $selectStatement.$sql;
            // print_var($fetchSql);die;
            $result = $this->model_datatable->getRecords($fetchSql,false);
            $records = [];
            if ($result) 
            {
                
                foreach ($result AS $key=>$tran_dtl) 
                {
                    

                    $records[] = [
                        'ward_no'=>$tran_dtl['ward_no'],                    
                        'consumer_no'=>$tran_dtl['consumer_no'],
                        // 'ward_no'=>$tran_dtl['ward_no'], 
                        'owner_name'=>$tran_dtl['owner_name'],
                        'father_name'=>$tran_dtl['father_name'],
                        'mobile_no'=>$tran_dtl['mobile_no'],
                        'address'=>$tran_dtl['address'],
                        'connection_date'=>$tran_dtl['connection_date'],
                        'meter_status'=>$tran_dtl['meter_status'],
                        'category'=>$tran_dtl['category'],
                        'consumer_type'=>$tran_dtl['consumer_type'],
                        'max_demand_upto'=>$tran_dtl['max_demand_upto'], 
                        'last_demands'=>$tran_dtl['last_demands'], 
                        'demand_type'=>$tran_dtl['demand_type'], 
                        'last_meter_reading'=>$tran_dtl['last_meter_reading'],                          
                        
                    ];

                }
            }
            phpOfficeLoad();
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $activeSheet = $spreadsheet->getActiveSheet();
                            $activeSheet->setCellValue('A1', 'Ward No.');
                            $activeSheet->setCellValue('B1', 'Consumer No.');
                            $activeSheet->setCellValue('C1', 'Application Name.');
                            $activeSheet->setCellValue('D1', 'Guardian Name');
                            $activeSheet->setCellValue('E1', 'Mobile No.');
                            $activeSheet->setCellValue('F1', 'Address');      
                            $activeSheet->setCellValue('G1', 'Apply Date');
                            $activeSheet->setCellValue('H1', 'Connection Type');
                            $activeSheet->setCellValue('I1', 'Category');
                            $activeSheet->setCellValue('J1', 'Consumer Type');
                            $activeSheet->setCellValue('K1', 'Current Demand Date');
                            $activeSheet->setCellValue('L1', 'Last Demand Date');
                            $activeSheet->setCellValue('M1', 'Demand Type');
                            $activeSheet->setCellValue('N1', 'Last Meter Reading');
                            // $activeSheet->setCellValue('O1', 'Apply From');                                 
                           

                            $activeSheet->fromArray($records, NULL, 'A3');
            $filename = "Demand_Genareted_report_".date('Ymd-hisa').".xlsx";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            //$writer->save(APPPATH.'/hello world.xlsx');
            $writer->save('php://output');
        }
        catch(Exception $e)
        {
            print_r($e);
        }
    }


    public function entery_reports()
    {
        $data=[];
        $data['ward']=array();
        $Session = Session();
        $data['report']=array();
        $from_date = date('Y-m-d');
        $to_date = date('Y-m-d');
        $ward_id='';
        $entry_type='';
        $oprator_id='';
        $ward_sql = " select * from view_ward_mstr where status = 1 "; 
        $data['ward']=$this->WaterMobileModel->row_sql($ward_sql);
        $operator_sql=" select * from view_emp_details where user_type_id in (8,5,7) order by user_type";
        $data['oprator']=$this->WaterMobileModel->row_sql($operator_sql);
        $and_where = " and tbl_consumer.apply_from !='Existing'";
        if (isset($_GET['page']) && $_GET['page']== 'clr')
        {
            $Session->remove('tempData');
        }
        if(strtoupper($this->request->getMethod())==strtoupper("post"))
        {
            $inputs = filterSanitizeStringtoUpper($this->request->getVar());            
            $Session->set('tempData', $inputs);           
           
        }
        $tempData=$Session->get('tempData');
        if(!empty($tempData))
        {   
            $from_date=$tempData['from_date'];
            $to_date = $tempData['to_date'];
            $ward_id = $tempData['ward_id'];
            if($tempData['ward_id'] !='')
                $and_where.=" and view_ward_mstr.id = $tempData[ward_id]";
            $entry_type=$tempData['entry_type'];
            //$oprator_id=$tempData['oprator_id'];
            if($entry_type!='' && $entry_type=1)
                $and_where.=" and tbl_consumer.apply_from !='Existing' ";
            elseif($entry_type!='' && $entry_type=2)
                $and_where.=" and tbl_consumer.apply_from ='Existing' ";
            
        }
        
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        $data['ward_id']=$ward_id ;
        $data['entry_type']=$entry_type ;
        $data['oprator_id']=$oprator_id ;
        $data['ulb_dtl']=$Session->get('ulb_dtl');
        return view("Water/report/entry_detail_report", $data);
    }
    public function entry_detail_reportAjax()
    {
        //if($this->request->getMethod()=='POST')
        //echo"hear";die;
        if($_POST)
        {
			try
            {
                ## Read value
                $start = sanitizeString($this->request->getVar('start'));
                
                
                $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page

                $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
                $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
                if ($columnName=="emp_name")
                    $columnName = 'view_emp_details.emp_name';
                else if ($columnName=="apply_date")
                    $columnName = 'cast(tbl_consumer.created_on as date)';
                else if ($columnName=="ward_no")
                    $columnName = 'view_ward_mstr.ward_no';
                else if ($columnName=="property_type")
                    $columnName = 'tbl_property_type_mstr.property_type';
                else
                    $columnName = 'cast(tbl_consumer.created_on as date)';
                //$columnName = "tbl_transaction.tran_date";
                $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
                $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value
                
                
                
                // Date filter
                $search_from_date = sanitizeString($this->request->getVar('search_from_date'));
                $search_upto_date = sanitizeString($this->request->getVar('search_upto_date'));
                $search_ward_mstr_id = sanitizeString($this->request->getVar('search_ward_mstr_id'));
                $search_collector_id = sanitizeString($this->request->getVar('search_collector_id'));
                $search_entry_type = sanitizeString($this->request->getVar('search_entry_type'));
                $searchQuery = "";
                $whereQuery = "";
                
                $orderBY = " ORDER BY ".$columnName." ".$columnSortOrder;
                $limit = " LIMIT ".$rowperpage." OFFSET ".$start;
                
                $whereQuery .= "  tbl_consumer.status=1 AND tbl_consumer.created_on::date  BETWEEN '".$search_from_date."' AND '".$search_upto_date."'";
                if ($search_ward_mstr_id != '') 
                {
                    $whereQuery .= " AND  view_ward_mstr.id='".$search_ward_mstr_id."'";
                }
                if ($search_collector_id != '') 
                {
                    $whereQuery .= " AND  view_emp_details.id='".$search_collector_id."'";
                }
                if($search_entry_type!='')
                {
                    $whereQuery .= " AND  tbl_consumer.apply_from ".($search_entry_type=='1' ? " !='Existing' " :" ='Existing'");
                }
                $whereQueryWithSearch = "";
                if ($searchValue!='') 
                {
                    $whereQueryWithSearch =" AND (view_emp_details.emp_name ~* '".$searchValue."'
                                                    OR view_emp_details.user_type ~* '".$searchValue."'
                                                    OR  tbl_consumer.consumer_no ~* '".$searchValue."'
                                                    OR tbl_consumer.category ~* '".$searchValue."' 
                                                    OR tbl_consumer.address ~* '".$searchValue."'
                                                    OR tbl_consumer.saf_no ~* '".$searchValue."'
                                                    OR tbl_consumer.holding_no ~* '".$searchValue."'
                                                    )";
                }

                $base_url = base_url();
                $selectStatement = "SELECT
                                    ROW_NUMBER () OVER (ORDER BY ".$columnName.") AS s_no,
                                    view_emp_details.emp_name , 
                                    view_emp_details.id as emp_id ,
                                    view_emp_details.user_type,
                                    tbl_consumer.consumer_no,
                                    tbl_consumer.category,
                                    tbl_consumer.address,
                                    tbl_consumer.saf_no,
                                    tbl_consumer.holding_no,
                                    tbl_consumer.apply_from,
                                    view_ward_mstr.ward_no,
                                    tbl_consumer.created_on::date,
                                    owner.owner_name,
                                    owner.father_name,
                                    owner.mobile_no,
                                    tbl_property_type_mstr.property_type
                ";                

                $sql =" from tbl_consumer
                        join tbl_property_type_mstr on  tbl_property_type_mstr.id = tbl_consumer.property_type_id
                        join view_ward_mstr on view_ward_mstr.id = tbl_consumer.ward_mstr_id
                        left join tbl_connection_type_mstr on tbl_connection_type_mstr.id = tbl_consumer.connection_type_id 
                        left join (
                            select distinct(consumer_id) as consumer_id,
                                string_agg(applicant_name,', ') as owner_name,
                                string_agg(father_name,', ') as father_name,
                                string_agg(mobile_no::text,', ') as mobile_no
                            from tbl_consumer_details
                            where status=1 
                            group by consumer_id
                        ) owner on owner.consumer_id = tbl_consumer.id
                        left join(
                            select id,emp_name,user_type_id,user_type
                            from view_emp_details 
                        
                        ) view_emp_details on view_emp_details.id = tbl_consumer.emp_details_id    
                        where                 
                        ".$whereQuery;
                $totalRecords = $this->model_datatable->getTotalRecords($sql,false);
                //print_var($totalRecords);
                //print_var($whereQueryWithSearch);
                $total_collection = 0;
                if ($totalRecords>0) 
                {
                    
                    ## Total number of records with filtering
                    $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($sql.$whereQueryWithSearch,false);
                    ## Fetch records
                    $fetchSql = $selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit;
                    //$fetchSumSql = $selectSumStatement.$sql;
                    $result = $this->model_datatable->getRecords($fetchSql,false);
                    //$total_collection = $this->model_datatable->getSumRecords($fetchSumSql)['total_payable_amt'];
                    
                    $records = [];
                    if ($result) 
                    {
                        foreach ($result AS $key=>$tran_dtl) 
                        {
                            $records[] = [
                                's_no'=>$tran_dtl['s_no'],
                                'emp_name'=>$tran_dtl['emp_name'],
                                'apply_date'=>$tran_dtl['created_on'],
                                'consumer_no'=>$tran_dtl['consumer_no'],
								'category'=>$tran_dtl['category'],
                                'holding_no'=>$tran_dtl['holding_no'],
                                'saf_no'=>$tran_dtl['saf_no'],
                                'owner_name'=>$tran_dtl['owner_name'],
                                'ward_no'=>$tran_dtl['ward_no'],
                                'father_name'=>$tran_dtl['father_name'],
								'mobile_no'=>$tran_dtl['mobile_no'],
                                'property_type'=>$tran_dtl['property_type'],
                                'address'=>$tran_dtl['address'],
                                'apply_from' =>$tran_dtl['apply_from'], 
                                
                                
                            ];
                        }
                    }
                } 
                else 
                {
                    $totalRecordwithFilter = 0;
                    $records = [];
                }

                $response = array(
                    "draw" => 0,
                    "total_collection" => $total_collection,
                    "recordsTotal" => $totalRecords,                 
                    "recordsFiltered" => $totalRecordwithFilter,
                    "data" => $records
                );
                return json_encode($response);
                
                
                
            }
            catch(Exception $e)
            {
                echo"catch";
                print_r($e);
            }
        } 
        else 
        {
            echo "asdasd";
            print_var($_POST);
            //print_var($_GET);
        }
    }

    public function entry_detail_reportExcel($search_from_date = null, $search_upto_date = null, $search_ward_mstr_id = null, $search_collector_id = null,$entry_type=null)
    {
        try{
                $whereQuery = "  tbl_consumer.status=1 AND tbl_consumer.created_on::date BETWEEN '".$search_from_date."' AND '".$search_upto_date."'";
                if ($search_ward_mstr_id != 'ALL') 
                {
                    $whereQuery .= " AND  view_ward_mstr.id='".$search_ward_mstr_id."'";
                }
                if ($search_collector_id != 'ALL') 
                {
                    $whereQuery .= " AND  view_emp_details.id='".$search_collector_id."'";
                }
                if($entry_type!='')
                {
                    $whereQuery .= " AND  tbl_consumer.apply_from ".($entry_type=='1' ? " !='Existing' " :" ='Existing'");
                }
                $whereQueryWithSearch = "";
                

                $selectStatement = "SELECT
                                    view_emp_details.emp_name , 
                                    view_emp_details.id as emp_id ,
                                    view_emp_details.user_type,
                                    tbl_consumer.consumer_no,
                                    tbl_consumer.category,
                                    tbl_consumer.address,
                                    tbl_consumer.saf_no,
                                    tbl_consumer.holding_no,
                                    tbl_consumer.apply_from,
                                    view_ward_mstr.ward_no,
                                    tbl_consumer.created_on::date,
                                    owner.owner_name,
                                    owner.father_name,
                                    owner.mobile_no,
                                    tbl_property_type_mstr.property_type 
                ";

                $sql =" from tbl_consumer
                        join tbl_property_type_mstr on  tbl_property_type_mstr.id = tbl_consumer.property_type_id
                        join view_ward_mstr on view_ward_mstr.id = tbl_consumer.ward_mstr_id
                        left join tbl_connection_type_mstr on tbl_connection_type_mstr.id = tbl_consumer.connection_type_id 
                        left join (
                            select distinct(consumer_id) as consumer_id,
                                string_agg(applicant_name,', ') as owner_name,
                                string_agg(father_name,', ') as father_name,
                                string_agg(mobile_no::text,', ') as mobile_no
                            from tbl_consumer_details
                            where status=1 
                            group by consumer_id
                        ) owner on owner.consumer_id = tbl_consumer.id
                        left join(
                            select id,emp_name,user_type_id,user_type
                            from view_emp_details 
                        
                        ) view_emp_details on view_emp_details.id = tbl_consumer.emp_details_id    
                        where                 
                        ".$whereQuery;

            $fetchSql = $selectStatement.$sql;
            $result = $this->model_datatable->getRecords($fetchSql,false);
            $records = [];
            if ($result) 
            {
                foreach ($result AS $key=>$tran_dtl) 
                {
                    

                    $records[] = [
                        //'s_no'=>$tran_dtl['s_no'],                        
                        'consumer_no'=>$tran_dtl['consumer_no'],
                        'ward_no'=>$tran_dtl['ward_no'], 
                        'category'=>$tran_dtl['category'],
                        'holding_no'=>$tran_dtl['holding_no'],
                        'saf_no'=>$tran_dtl['saf_no'],
                        'owner_name'=>$tran_dtl['owner_name'],
                        'father_name'=>$tran_dtl['father_name'],
                        'mobile_no'=>$tran_dtl['mobile_no'],
                        'property_type'=>$tran_dtl['property_type'],
                        'address'=>$tran_dtl['address'], 
                        'emp_name'=>$tran_dtl['emp_name'],
                        'apply_date'=>$tran_dtl['created_on'],                       
                        'apply_from' =>$tran_dtl['apply_from'],                       
                    ];

                }
            }
            phpOfficeLoad();
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $activeSheet = $spreadsheet->getActiveSheet();
                            $activeSheet->setCellValue('A1', 'Consumer No.');
                            $activeSheet->setCellValue('B1', 'Ward No.');
                            $activeSheet->setCellValue('C1', 'Category');
                            $activeSheet->setCellValue('D1', 'Holding No');
                            $activeSheet->setCellValue('E1', 'Saf No');
                            $activeSheet->setCellValue('F1', 'Owner Name.');
                            $activeSheet->setCellValue('G1', 'Father Name');
                            $activeSheet->setCellValue('H1', 'Mobile No');
                            $activeSheet->setCellValue('I1', 'Property Type');
                            $activeSheet->setCellValue('J1', 'Address');
                            $activeSheet->setCellValue('K1', 'Operator Name');
                            $activeSheet->setCellValue('L1', 'Apply Date');
                            $activeSheet->setCellValue('M1', 'Apply From');                            

                            $activeSheet->fromArray($records, NULL, 'A3');
            $filename = "counter_Entery_report_".date('Ymd-hisa').".xlsx";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            //$writer->save(APPPATH.'/hello world.xlsx');
            $writer->save('php://output');
        }
        catch(Exception $e)
        {
            print_r($e);
        }
    }

    public function demand_summary()
    {
        $data =(array)null;
        $session = session();
        $ulb_dtl = $session->get('ulb_dtl');
        $ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];

        $data =(array)null;
        $Session = Session();
        $status=array();

        $data['ulb_mstr_id']=$this->ulb_id;
        $whereClause="where 1=1 ";
        $join=NULL;
        $data['team_leader']='';
        $tc=[
            'where'=>['user_type_id'=>[8,5,4]],
            'tbl'=>'view_emp_details',
            'column'=>['id','emp_name','user_type','lock_status','employee_code']
        ];
        $data['tc']=$this->WaterMobileModel->getDataNew($tc['where'],$tc['column'],$tc['tbl'],array(),array('emp_name'=>'ASC'));        
        $uri_string = uri_string();
        if (isset($_GET['page']) && $_GET['page']== 'clr')
        {
            $Session->remove('tempData'); //echo('remove');
        }

        //$data['wardList'] = $this->model_ward_mstr->getWardListForReport($ulb_mstr_id);        
        if($this->request->getMethod()=='post')
        {
            $inputs = arrFilterSanitizeString($this->request->getVar());
            $Session->set('tempData', $inputs);            
        }
        $tempData=$Session->get('tempData');       
        if(!empty($tempData))
        {
            
            $data['from_date'] = $tempData['from_date'];
            $data['to_date'] = $tempData['to_date'];
            $data['tc_id']= $tempData['tc_id'];           
            
            $status=array();
            
            $data['from']=strtotime($tempData['from_date']);
            $data['to']=strtotime($tempData['to_date']);
            $where = "";
            if($data['tc_id']!='')
            {
                $where = " AND emp_details_id = ".$data['tc_id'];
            }

            $with=" with demand as (
                        select 
                            distinct(consumer_id) as consumer_id, 
                            count(distinct(consumer_id)) as num_demand,
                            count(tbl_consumer_demand.id),
                            case when connection_type='Fixed' then 'Fixed'
                                when connection_type in ('Metered','Meter') then 'Metered' 
                                end as connection_type,
                            emp_details_id,generation_date
                        from tbl_consumer_demand 
                        where generation_date between '".$data['from_date']."' and '".$data['to_date']."' and status = 1
                            $where
                        group by consumer_id,connection_type,emp_details_id,generation_date
                    ),
                    temp as (
                        select count(distinct(consumer_id)) as total_demand,
                            connection_type,emp_name,view_emp_details.id,view_emp_details.employee_code,generation_date
                        from demand
                        join view_emp_details on view_emp_details.id = demand.emp_details_id
                        group by connection_type,emp_name,id,view_emp_details.employee_code,generation_date
                    ),
                    final as (
                        select sum(total_demand) as total_demand, 
                            sum(case when connection_type='Metered' then total_demand else 0 end) as meter_demand, 
                            sum(case when connection_type='Fixed' then total_demand else 0 end) as fixed_demand,
                            emp_name,id as emp_details_id ,employee_code
                        from temp 
                        group by emp_name,id ,employee_code
                        order by emp_name
                    ) ";
            $select= "select  total_demand,
                         meter_demand,
                        fixed_demand,
                        emp_name,emp_details_id,employee_code
                    ";
            $from =" from final ";                    
            $sum_sql = $with."select  sum(total_demand) as total_demand ,
                                    count(emp_details_id) as total_emp,
                                    sum(meter_demand) as total_meter_demand,
                                    sum(fixed_demand) as fixed_demand                                        
                            ".$from;           
           
            $data['demands']=$this->WaterMobileModel->get_data_10($from,$select,false,$with);
            $total = $this->WaterMobileModel->row_sql($sum_sql);    
            // print_var( $data['demands']);        
            $data['total_demand']=$total[0]['total_demand']??0;
            $data['total_emp']=$total[0]['total_emp']??0;
            $data['total_meter_demand']=$total[0]['total_meter_demand']??0;
            $data['fixed_demand']=$total[0]['fixed_demand']??0;
            

        }
        return view('water/report/demand_summary', $data);

    }

    public function demand_summaryExcel($from_date=null,$to_date=null,$tc_id=null)
    {        
        if($from_date==null)
        {
            $from_date=date('Y-m-d');            
        }
        if($to_date==null)
        {
            $to_date=date('Y-m-d');            
        }
        if($tc_id==null || $tc_id=='ALL')
        {
            $tc_id='';            
        }
        try
        {
            $whereQuery = " ";
            if ($tc_id != '') 
            {
                $whereQuery .= " AND  emp_details_id = ".$tc_id;
            }
            $whereQueryWithSearch = "";
            $with=" with demand as (
                        select 
                            distinct(consumer_id) as consumer_id, 
                            count(distinct(consumer_id)) as num_demand,
                            count(tbl_consumer_demand.id),
                            case when connection_type='Fixed' then 'Fixed'
                                when connection_type in ('Metered','Meter') then 'Metered' 
                                end as connection_type,
                            emp_details_id,generation_date
                        from tbl_consumer_demand 
                        where generation_date between '".$from_date."' and '".$to_date."' and status = 1
                            $whereQuery
                        group by consumer_id,connection_type,emp_details_id,generation_date
                    ),
                    temp as (
                        select count(distinct(consumer_id)) as total_demand,
                            connection_type,emp_name,view_emp_details.id,view_emp_details.employee_code,generation_date
                        from demand
                        join view_emp_details on view_emp_details.id = demand.emp_details_id
                        group by connection_type,emp_name,id,view_emp_details.employee_code,generation_date
                    ),
                    final as (
                        select sum(total_demand) as total_demand, 
                            sum(case when connection_type='Metered' then total_demand else 0 end) as meter_demand, 
                            sum(case when connection_type='Fixed' then total_demand else 0 end) as fixed_demand,
                            emp_name,id as emp_details_id,employee_code 
                        from temp 
                        group by emp_name,id ,employee_code
                        order by emp_name
                    ) 
            ";
    
            $selectStatement = "SELECT
                                total_demand,
                                meter_demand,
                                fixed_demand,
                                emp_name,emp_details_id,employee_code
            ";

            $sql =" from final  ";

            $fetchSql = $with.$selectStatement.$sql;
            $result = $this->model_datatable->getRecords($fetchSql,false);
            $records = [];
            if ($result) 
            {
                foreach ($result AS $key=>$tran_dtl) 
                {
                    

                    $records[] = [                        
                        'emp_name'=>$tran_dtl['emp_name']." (".$tran_dtl['employee_code'].")",
                        'total_demand'=>$tran_dtl['total_demand'], 
                        'meter_demand'=>$tran_dtl['meter_demand'],
                        'fixed_demand'=>$tran_dtl['fixed_demand'],                                             
                    ];

                }
            }
            phpOfficeLoad();
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $activeSheet = $spreadsheet->getActiveSheet();
                            $activeSheet->setCellValue('A1', 'Tax Collector');
                            $activeSheet->setCellValue('B1', 'Total Consumers');
                            $activeSheet->setCellValue('C1', 'Metered');
                            $activeSheet->setCellValue('D1', 'Non Metered');                                                        

                            $activeSheet->fromArray($records, NULL, 'A3');
            $filename = "demand_summary_report_".date('Ymd-hisa').".xlsx";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            //$writer->save(APPPATH.'/hello world.xlsx');
            $writer->save('php://output');
        }
        catch(Exception $e)
        {
            print_r($e);
        }
    }

    public function demandSummaryDtl($emp_id='',$from='',$to='')
    {
        $data = array();
        $from_date = date('Y-m-d',$from);   
        $to_date = date('Y-m-d',$to);

        $sql = "select tbl_consumer.id as consumer_id ,
                    consumer_no,
                    tbl_meter_reading_doc.file_name,
                    SUM(COALESCE(tbl_consumer_tax.initial_reading,0)) as initial_reading,
                    SUM(COALESCE(tbl_consumer_tax.final_reading,0)) as final_reading,
                    sum(tbl_consumer_demand.amount) as amount, 
                    min(tbl_consumer_demand.demand_from) as demand_from ,
                    max(tbl_consumer_demand.demand_upto) as demand_upto,
                    tbl_consumer_demand.connection_type,
                    tbl_consumer_demand.generation_date
                from tbl_consumer_demand 
                join tbl_consumer on tbl_consumer.id = tbl_consumer_demand.consumer_id
                left join tbl_meter_reading_doc on tbl_meter_reading_doc.demand_id = tbl_consumer_demand.id
                left join tbl_consumer_tax on tbl_consumer_tax.id = tbl_consumer_demand.consumer_tax_id
                where tbl_consumer_demand.emp_details_id = $emp_id
                    and tbl_consumer_demand.status = 1 
                    and tbl_consumer_demand.generation_date between '$from_date' and '$to_date' 
                group by tbl_consumer.id,connection_type,tbl_consumer_demand.generation_date,tbl_meter_reading_doc.file_name
                order by tbl_consumer.id,tbl_consumer_demand.generation_date
                ";
        //print_var($sql);
        $data['transaction']=$this->WaterMobileModel->getDataRowQuery2($sql)['result'];
        //print_var($data['transaction']);
        return view('water/report/demandSummaryDtl', $data);
    }

    public function water_connection_reports()
    {
        $data=array();
        
        if(strtoupper($this->request->getMethod())==strtoupper("post"))
        {   
			try
            {
                ## Read value
                $start = sanitizeString($this->request->getVar('start'));                
                
                $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page

                $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
                $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
                if ($columnName=="emp_name")
                    $columnName = 'view_emp_details.emp_name';
                else if ($columnName=="apply_date")
                    $columnName = 'cast(tbl_apply_water_connection.apply_date as date)';
                else if ($columnName=="ward_no")
                    $columnName = 'view_ward_mstr.ward_no';
                else if ($columnName=="property_type")
                    $columnName = 'tbl_property_type_mstr.property_type';
                else
                    $columnName = 'cast(tbl_apply_water_connection.apply_date as date)';
                //$columnName = "tbl_transaction.tran_date";
                $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
                $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value
                $keyword = 
                
                
                // Date filter
                $search_from_date = sanitizeString($this->request->getVar('from_date'));
                $search_upto_date = sanitizeString($this->request->getVar('upto_date'));
                //$search_ward_mstr_id = sanitizeString($this->request->getVar('search_ward_mstr_id'));
                //$search_collector_id = sanitizeString($this->request->getVar('search_collector_id'));
                $category = sanitizeString($this->request->getVar('category'));
                $keyword = sanitizeString($this->request->getVar('keyword'));
                $searchQuery = "";
                $whereQuery = "";
                
                $orderBY = " ORDER BY ".$columnName." ".$columnSortOrder;
                $limit = " LIMIT ".$rowperpage." OFFSET ".$start;
                //print_var($limit);die;
                $whereQuery .= "  AND tbl_apply_water_connection.apply_date::date  BETWEEN '".$search_from_date."' AND '".$search_upto_date."'";
                // if ($search_ward_mstr_id != '') 
                // {
                //     $whereQuery .= " AND  view_ward_mstr.id='".$search_ward_mstr_id."'";
                // }
                // if ($search_collector_id != '') 
                // {
                //     $whereQuery .= " AND  view_emp_details.id='".$search_collector_id."'";
                // }
                if($category!='')
                {
                    $whereQuery .= " AND tbl_apply_water_connection.category ".($category=='APL' ? " ='APL' " :" ='BPL'");
                }
                $whereQueryWithSearch = "";
                if ($searchValue!='') 
                {
                    $whereQueryWithSearch =" AND (tbl_apply_water_connection.address ~* '".$searchValue."'
                                                    OR tbl_apply_water_connection.application_no ~* '".$searchValue."'
                                                    OR  tbl_apply_water_connection.apply_from ~* '".$searchValue."'
                                                    OR tbl_applicant_details.applicant_name ~* '".$searchValue."' 
                                                    OR tbl_applicant_details.father_name ~* '".$searchValue."'
                                                    OR tbl_applicant_details.mobile_no ~* '".$searchValue."'
                                                    OR view_emp_details.emp_name ~* '".$searchValue."'
                                                    OR  tbl_property_type_mstr.property_type ~* '".$searchValue."'
                                                    OR view_ward_mstr.ward_no ~* '".$searchValue."'
                                                    )";
                }
                if ($keyword!='') 
                {
                    $whereQuery =" AND (tbl_apply_water_connection.address ~* '".$keyword."'
                                                    OR tbl_apply_water_connection.application_no ~* '".$keyword."'
                                                    OR  tbl_apply_water_connection.apply_from ~* '".$keyword."'
                                                    OR tbl_applicant_details.applicant_name ~* '".$keyword."' 
                                                    OR tbl_applicant_details.father_name ~* '".$keyword."'
                                                    OR tbl_applicant_details.mobile_no ~* '".$keyword."'
                                                    OR view_emp_details.emp_name ~* '".$keyword."'
                                                    OR  tbl_property_type_mstr.property_type ~* '".$keyword."'
                                                    OR view_ward_mstr.ward_no ~* '".$keyword."'
                                                    )";
                }

                $base_url = base_url();
                $selectStatement = "SELECT
                                    ROW_NUMBER () OVER (ORDER BY ".$columnName.") AS s_no,
                                    tbl_apply_water_connection.category,
                                    tbl_apply_water_connection.address,
                                    tbl_apply_water_connection.apply_date,
                                    tbl_apply_water_connection.application_no,
                                    tbl_apply_water_connection.apply_from,
                                    tbl_applicant_details.applicant_name,
                                    tbl_applicant_details.father_name,
                                    tbl_applicant_details.mobile_no,
                                    view_ward_mstr.ward_no,
                                    tbl_property_type_mstr.property_type,
                                    view_emp_details.emp_name
                ";                

                $sql =" from tbl_apply_water_connection
                        join (
                            select distinct(apply_connection_id) as apply_connection_id,
                                string_agg(applicant_name,', ') as applicant_name,
                                string_agg(father_name,', ') as father_name,
                                string_agg(mobile_no::text,', ') as mobile_no
                            from tbl_applicant_details     
                            group by apply_connection_id
                        ) tbl_applicant_details on tbl_applicant_details.apply_connection_id=tbl_apply_water_connection.id
                        join view_ward_mstr on view_ward_mstr.id = tbl_apply_water_connection.ward_id
                        join tbl_property_type_mstr on  tbl_property_type_mstr.id = tbl_apply_water_connection.property_type_id
                        left join view_emp_details on view_emp_details.id = tbl_apply_water_connection.user_id
                        where apply_from !='Existing' and connection_type_id=1 and tbl_apply_water_connection.status = 5 
                        ".$whereQuery;
                        // print_var($sql);
                        // die;
                $totalRecords = $this->model_datatable->getTotalRecords($sql,false);
                //print_var($totalRecords);
                //print_var($whereQueryWithSearch);
                $total_collection = 0;
                if ($totalRecords>0) 
                {
                    
                    ## Total number of records with filtering
                    $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($sql.$whereQueryWithSearch,false);
                    ## Fetch records
                    $fetchSql = $selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit;
                    //$fetchSumSql = $selectSumStatement.$sql;
                    $result = $this->model_datatable->getRecords($fetchSql,false);
                    //$total_collection = $this->model_datatable->getSumRecords($fetchSumSql)['total_payable_amt'];
                    
                    $records = [];
                    if ($result) 
                    {
                        foreach ($result AS $key=>$tran_dtl) 
                        {
                            $records[] = [
                                's_no'=>$tran_dtl['s_no'],
                                'application_no'=>$tran_dtl['application_no'],
                                'ward_no'=>$tran_dtl['ward_no'],
                                'applicant_name'=>$tran_dtl['applicant_name'],
								'father_name'=>$tran_dtl['father_name'],
                                'mobile_no'=>$tran_dtl['mobile_no'],
                                'apply_date'=>$tran_dtl['apply_date'],
                                'category'=>$tran_dtl['category'],
                                'apply_from'=>$tran_dtl['apply_from'],
                                'address'=>$tran_dtl['address'],
								'property_type'=>$tran_dtl['property_type'],
                                'emp_name'=>$tran_dtl['emp_name'],
                                // 'address'=>$tran_dtl['address'],
                                // 'apply_from' =>$tran_dtl['apply_from'], 
                                
                                
                            ];
                        }
                    }
                } 
                else 
                {
                    $totalRecordwithFilter = 0;
                    $records = [];
                }

                $response = array(
                    "draw" => 0,
                    "total_collection" => $total_collection,
                    "recordsTotal" => $totalRecords,                 
                    "recordsFiltered" => $totalRecordwithFilter,
                    "data" => $records
                );
                return json_encode($response);
                
                
                
            }
            catch(Exception $e)
            {
                echo"catch";
                print_r($e);
            }
        } 
        else 
        {
            // echo "asdasd";
            // print_var($_POST);
            //print_var($_GET);
            return view("Water/report/water_connection_reports", $data);
        }
        return view("Water/report/water_connection_reports", $data);
    }
    public function water_connection_reportsExcel($category=null,$from_date=null,$upto_date=null,$keyword=null)
    {        
        if($from_date==null)
        {
            $from_date=date('Y-m-d');            
        }
        if($upto_date==null)
        {
            $upto_date=date('Y-m-d');            
        }
        // if($tc_id==null || $tc_id=='ALL')
        // {
        //     $tc_id='';            
        // }
        try
        {
            $whereQuery = " where apply_from !='Existing' and connection_type_id=1 
                            AND tbl_apply_water_connection.status = 5 
                            --AND tbl_apply_water_connection.apply_date::date BETWEEN '$from_date' AND '$upto_date'";
            if ($category != '') 
            {
                $whereQuery .= " AND  tbl_apply_water_connection.category ='$category' ";
            }
            if ($keyword!='xxx') 
            {
                $whereQuery =" AND (tbl_apply_water_connection.address ~* '".$keyword."'
                                                OR tbl_apply_water_connection.application_no ~* '".$keyword."'
                                                OR  tbl_apply_water_connection.apply_from ~* '".$keyword."'
                                                OR tbl_applicant_details.applicant_name ~* '".$keyword."' 
                                                OR tbl_applicant_details.father_name ~* '".$keyword."'
                                                OR tbl_applicant_details.mobile_no ~* '".$keyword."'
                                                OR view_emp_details.emp_name ~* '".$keyword."'
                                                OR  tbl_property_type_mstr.property_type ~* '".$keyword."'
                                                OR view_ward_mstr.ward_no ~* '".$keyword."'
                                                )";
            }
            $whereQueryWithSearch = "";            
    
            $selectStatement = "SELECT
                                tbl_apply_water_connection.category,
                                tbl_apply_water_connection.address,
                                tbl_apply_water_connection.apply_date,
                                tbl_apply_water_connection.application_no,
                                tbl_apply_water_connection.apply_from,
                                tbl_applicant_details.applicant_name,
                                tbl_applicant_details.father_name,
                                tbl_applicant_details.mobile_no,
                                view_ward_mstr.ward_no,
                                tbl_property_type_mstr.property_type,
                                view_emp_details.emp_name
            ";

            $sql =" from  tbl_apply_water_connection
                    join (
                        select distinct(apply_connection_id) as apply_connection_id,
                            string_agg(applicant_name,', ') as applicant_name,
                            string_agg(father_name,', ') as father_name,
                            string_agg(mobile_no::text,', ') as mobile_no
                        from tbl_applicant_details     
                        group by apply_connection_id
                    ) tbl_applicant_details on tbl_applicant_details.apply_connection_id=tbl_apply_water_connection.id
                    join view_ward_mstr on view_ward_mstr.id = tbl_apply_water_connection.ward_id
                    join tbl_property_type_mstr on  tbl_property_type_mstr.id = tbl_apply_water_connection.property_type_id
                    left join view_emp_details on view_emp_details.id = tbl_apply_water_connection.user_id  ";

            $fetchSql = $selectStatement.$sql.$whereQuery;
            $result = $this->model_datatable->getRecords($fetchSql,true);die;
            $records = [];
            if ($result) 
            {
                foreach ($result AS $key=>$tran_dtl) 
                {
                    

                    $records[] = [                        
                        //'s_no'=>$tran_dtl['s_no'],
                        'application_no'=>$tran_dtl['application_no'],
                        'ward_no'=>$tran_dtl['ward_no'],
                        'applicant_name'=>$tran_dtl['applicant_name'],
                        'father_name'=>$tran_dtl['father_name'],
                        'mobile_no'=>$tran_dtl['mobile_no'],
                        'apply_date'=>$tran_dtl['apply_date'],
                        'category'=>$tran_dtl['category'],
                        'apply_from'=>$tran_dtl['apply_from'],
                        'address'=>$tran_dtl['address'],
                        'property_type'=>$tran_dtl['property_type'],
                        'emp_name'=>$tran_dtl['emp_name'],                                             
                    ];

                }
            }
            phpOfficeLoad();
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $activeSheet = $spreadsheet->getActiveSheet();
                            $activeSheet->setCellValue('A1', 'App No.');
                            $activeSheet->setCellValue('B1', 'Ward No.');
                            $activeSheet->setCellValue('C1', 'Application Name.');
                            $activeSheet->setCellValue('D1', 'Guardian Name'); 

                            $activeSheet->setCellValue('E1', 'Mobile No.');
                            $activeSheet->setCellValue('F1', 'Apply Date');
                            $activeSheet->setCellValue('G1', 'Category');
                            $activeSheet->setCellValue('H1', 'Apply From'); 

                            $activeSheet->setCellValue('I1', 'Address');
                            $activeSheet->setCellValue('J1', 'Property Type');
                            $activeSheet->setCellValue('K1', 'User Name');
                                                                               

                            $activeSheet->fromArray($records, NULL, 'A3');
            $filename = "Water_Connection_report_".date('Ymd-hisa').".xlsx";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            //$writer->save(APPPATH.'/hello world.xlsx');
            $writer->save('php://output');
        }
        catch(Exception $e)
        {
            print_r($e);
        }
    }

    public function bulk_payment_ricipt()
    {
        $data =(array)null;
        $Session = session();
        $ulb_dtl = $Session->get('ulb_dtl');
        $data['ulb_dtl'] = $ulb_dtl;
        $ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];
        $data['ulb_id']= $ulb_mstr_id;
        $data['ulb_mstr_name'] = $this->modelUlb->getulb_list($ulb_mstr_id);
        $data['ulb_mstr_id']=$this->ulb_id;
        $ward_whare=[
            'where'=>['status '=>[1],'ulb_mstr_id'=>[$ulb_mstr_id]],
            'tbl'=>'view_ward_mstr',
            'column'=>['id','ward_no','ulb_mstr_id','status']
        ];
        $data['ward']=$this->WaterMobileModel->getDataNew($ward_whare['where'],$ward_whare['column'],$ward_whare['tbl'],array(),array('ward_no'=>'ASC'));
        $operator_sql=" select * 
                        from view_emp_details 
                        where user_type_id in (8,5,7,4) and lock_status = 0 
                        order by user_type asc , emp_name asc";
        $data['oprator']=$this->WaterMobileModel->row_sql($operator_sql);
        $uri_string = uri_string();
        if (isset($_GET['page']) && $_GET['page']== 'clr')
        {
            $Session->remove('tempData'); //echo('remove');
        }
       
        if($this->request->getMethod()=='post')
        {
            $rules = ['tc_id'=>[
                        'rules'=>'required',
                        'errors'=>[
                            'required'=>'Please select Operator.'
                            ]
                        ],
                        'from_date'=>[
                            'rules'=>'required',
                            'errors'=>[
                                'required'=>'Please Enter From Date.'
                            ]
                        ],
                        'upto_date'=>[
                            'rules'=>'required',
                            'errors'=>[
                                'required'=>'Please Enter Upto Date.'
                            ]
                        ]
                    ];
            if(!$this->validate($rules))
            {
                $data['validation']=$this->validator; 
                return redirect()->back()->with('fail','Somthing is Wrong!');
            }
            
            $inputs = arrFilterSanitizeString($this->request->getVar());
            $Session->set('tempData', $inputs);
        }
        $tempData=$Session->get('tempData');
        if(!empty($tempData))
        {            
            $data['tc_id'] = $tempData['tc_id'];
            $tc_id = $data['tc_id'];
            $from_date = $data['from_date'] = $tempData['from_date'];
            $upto_date = $data['upto_date'] = $tempData['upto_date'];
            $where = " WHERE tbl_transaction.status in (1,2) 
                        AND tbl_transaction.transaction_type = 'Demand Collection' 
                        AND tbl_transaction.transaction_date between '$from_date' AND '$upto_date' ";            
            if($tc_id != '')
            {
                $where .= " AND tbl_transaction.emp_details_id = $tc_id ";                
            }

            $sql=" 
            select *, tbl_transaction.id as transaction_id
            from tbl_transaction 
            join tbl_consumer on tbl_consumer.id = tbl_transaction.related_id
            join view_ward_mstr on view_ward_mstr.id =  tbl_consumer.ward_mstr_id
            left join (
                select distinct(tbl_consumer_details.consumer_id) as consumer_id,
                    string_agg( tbl_consumer_details.applicant_name,', ') as applicant,
                    string_agg( tbl_consumer_details.father_name,', ') as father_name, 
                    string_agg( cast(tbl_consumer_details.mobile_no as varchar(10)),', ') as mobile_no
                from tbl_consumer_details 
                join tbl_transaction  on tbl_transaction.related_id = tbl_consumer_details.consumer_id
                    and tbl_consumer_details.status = 1	
                $where
                group by tbl_consumer_details.consumer_id
            ) owner on owner.consumer_id = tbl_transaction.related_id            
            $where
            ";
        
            
            $sql.=" order by view_ward_mstr.ward_no  ";
            //print_var($sql);die;
            $data['bulk_payment']=$this->WaterMobileModel->getDataRowQuery2($sql);  
            foreach ($data['bulk_payment']['result'] as $key=> $val)
            {               
                $data['bulk_payment']['result'][$key]['meter_reading']=$this->payment_model->meter_reding_for_recipt($val['transaction_id']);
            }           
             
            //print_var($data['bulk_payment']);  die;        

        }
        return view('water/report/bulk_payment_ricipt', $data);

    }

    public function meter_non_meter_consumer_list()
    {
        $data=[];
        $data['view']="WaterViewConsumerDetails/index/";
        $ward_list = " select * from view_ward_mstr where status =1 ";
        $data['ward_list'] = $this->WaterMobileModel->row_sql($ward_list);
        $data['ward_id']=$this->request->getVar('ward_id')??'';
        $data['keyword']=$this->request->getVar('keyword')??''; 
        $data['connection_type']=$this->request->getVar('connection_type')??'1';       
        $where = "";        
        if($data['ward_id'])
        {
            $data['ward_id'] =  $this->request->getVar('ward_id');
            $where .= " AND tbl_consumer.ward_mstr_id =".$data['ward_id'];
        }
        if($data['connection_type'])
        { 
            if($data['connection_type']==1)
             $where .= " AND tbl_meter_status.connection_type in(1,2)";
            else
             $where .= " AND tbl_meter_status.connection_type NOT IN(1,2)";
        }
        if($data['keyword'])
        {
            $data['keyword'] =  $this->request->getVar('keyword');
            $where .= " AND (tbl_consumer.consumer_no ILIKE('%".$data['keyword']."%') 
                        OR tbl_consumer.holding_no ILIKE('%".$data['keyword']."%')
                        OR owner.applicant_name ILIKE('%".$data['keyword']."%')
                        OR owner.father_name ILIKE('%".$data['keyword']."%')
                        OR owner.mobile_no ILIKE('%".$data['keyword']."%')
                        ) ";
        }
        
        $select=" select tbl_consumer.id, tbl_consumer.consumer_no, tbl_consumer.category,tbl_consumer.address,
                    view_ward_mstr.ward_no,
                    owner.applicant_name,owner.father_name,owner.mobile_no,
                    case when tbl_meter_status.connection_type in (1,2) then 'Meter'
                        else 'Fixed' end as connection_type,
                    tbl_meter_status.meter_connection_date 
                ";  
        $from=" from tbl_consumer
                join view_ward_mstr on view_ward_mstr.id = tbl_consumer.ward_mstr_id
                left join (
                        select distinct(consumer_id),
                            string_agg(applicant_name,' ,') as applicant_name,
                            string_agg(father_name,' ,') as father_name,
                            string_agg(mobile_no::text,' ,') as mobile_no
                        from tbl_consumer_details 
                            where status = 1
                            group by consumer_id            
                    ) owner on owner.consumer_id = tbl_consumer.id
                left join (
                    select consumer_id,connection_date as meter_connection_date,
                            connection_type 
                    from  tbl_meter_status
                    where id in (
                                    select max(id)
                                    from tbl_meter_status 
                                    where status = 1 and connection_date notnull 
                                    group by consumer_id
                                ) 
                    ) tbl_meter_status on tbl_meter_status.consumer_id = tbl_consumer.id
                    
                where tbl_consumer.status=1 $where       
              
       ";       
       $data['consumer_details']=$this->WaterMobileModel->get_data_10($from,$select,false);

       return view("Water/report/meter_non_meter_consumer_list",$data);
    }
    public function meter_non_meter_consumer_listExcel($ward_id='All',$keyword='@@@',$connection_type='')
    {     
        $where = "";  
        try{      
            if($ward_id!='All')
            {           
                $where .= " AND tbl_consumer.ward_mstr_id =".$ward_id;
            }
            if($connection_type)
            { 
                if($connection_type==1)
                $where .= " AND tbl_meter_status.connection_type in(1,2)";
                else
                $where .= " AND tbl_meter_status.connection_type NOT IN(1,2)";
            }
            if($keyword!="@@@")
            {
                $data['keyword'] =  $keyword;
                $where .= " AND (tbl_consumer.consumer_no ILIKE('%".$data['keyword']."%') 
                            OR tbl_consumer.holding_no ILIKE('%".$data['keyword']."%')
                            OR owner.applicant_name ILIKE('%".$data['keyword']."%')
                            OR owner.father_name ILIKE('%".$data['keyword']."%')
                            OR owner.mobile_no ILIKE('%".$data['keyword']."%')
                            ) ";
            }
            
            $select=" select tbl_consumer.id, tbl_consumer.consumer_no, tbl_consumer.category,tbl_consumer.address,
                        view_ward_mstr.ward_no,
                        owner.applicant_name,owner.father_name,owner.mobile_no,
                        case when tbl_meter_status.connection_type in (1,2) then 'Meter'
                            else 'Fixed' end as connection_type,
                        tbl_meter_status.meter_connection_date 
                    ";  
            $from=" from tbl_consumer
                    join view_ward_mstr on view_ward_mstr.id = tbl_consumer.ward_mstr_id
                    left join (
                            select distinct(consumer_id),
                                string_agg(applicant_name,' ,') as applicant_name,
                                string_agg(father_name,' ,') as father_name,
                                string_agg(mobile_no::text,' ,') as mobile_no
                            from tbl_consumer_details 
                                where status = 1
                                group by consumer_id            
                        ) owner on owner.consumer_id = tbl_consumer.id
                    left join (
                        select consumer_id,connection_date as meter_connection_date,
                                connection_type 
                        from  tbl_meter_status
                        where id in (
                                        select max(id)
                                        from tbl_meter_status 
                                        where status = 1 and connection_date notnull 
                                        group by consumer_id
                                    ) 
                        ) tbl_meter_status on tbl_meter_status.consumer_id = tbl_consumer.id
                        
                    where tbl_consumer.status=1 $where       
                
            ";
            $fetchSql = $select.$from;
            $result = $this->model_datatable->getRecords($fetchSql,false);
            
            $records = [];
            if ($result) 
            {
                foreach ($result AS $key=>$tran_dtl) 
                {
                    $records[] = [                        
                        'ward_no'=>$tran_dtl['ward_no'],
                        'consumer_no'=>$tran_dtl['consumer_no'], 
                        'category'=>$tran_dtl['category'],
                        'applicant_name'=>$tran_dtl['applicant_name'],   
                        'mobile_no'=>$tran_dtl['mobile_no'],
                        'address'=>$tran_dtl['address'], 
                        'connection_type'=>$tran_dtl['connection_type'],
                        'meter_connection_date'=>$tran_dtl['meter_connection_date'],                                          
                    ];

                }
            }            
            phpOfficeLoad();
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $activeSheet = $spreadsheet->getActiveSheet();
                            $activeSheet->setCellValue('A1', 'Ward No.');
                            $activeSheet->setCellValue('B1', 'Consumer No.');
                            $activeSheet->setCellValue('C1', 'Category');
                            $activeSheet->setCellValue('D1', 'Applicant Name');   
                            
                            $activeSheet->setCellValue('E1', 'Mobile No.');
                            $activeSheet->setCellValue('F1', 'Address');
                            $activeSheet->setCellValue('G1', 'Connection Type');
                            $activeSheet->setCellValue('H1', 'Meter/Fixed Connection Date');   

                            $activeSheet->fromArray($records, NULL, 'A2');
            $filename = "MeterFixedConsumerReport_".date('Ymd-hisa').".xlsx";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            //$writer->save(APPPATH.'/hello world.xlsx');
            $writer->save('php://output');
        }
        catch(Exception $e)
        {
            print_r($e);
        }
    }

    public function  ward_wise_demand()
    {
        $data =(array)null;
        $session = session();
        $ulb_dtl = $session->get('ulb_dtl');
        $ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];
        $data =(array)null;
        $from_date = date('Y-m-d');
        $to_date = date('Y-m-d');

        $ward_whare=[
            'where'=>['status '=>[1],'ulb_mstr_id'=>[$ulb_mstr_id]],
            'tbl'=>'view_ward_mstr',
            'column'=>['id','ward_no','ulb_mstr_id','status']
        ];
        $data['ward_list'] =$this->WaterMobileModel->getDataNew($ward_whare['where'],$ward_whare['column'],$ward_whare['tbl'],array(),array('ward_no'=>'ASC'));
        $tc=[
            'where'=>['user_type_id'=>[8,5,4],'lock_status <>'=> [1]],
            'tbl'=>'view_emp_details',
            'column'=>['id','emp_name','user_type','lock_status','employee_code']
        ];
        $data['tc_list']=$this->WaterMobileModel->getDataNew($tc['where'],$tc['column'],$tc['tbl'],array(),array('emp_name'=>'ASC'));
        
        $where = " ";
        $with_where="  AND tbl_consumer_demand.status = 1 ";
        $inputs = $this->request->getVar();
        if(isset($inputs['ward_mstr_id']) && $inputs['ward_mstr_id']!='')
        {
            $where .=" AND view_ward_mstr.id = ".$inputs['ward_mstr_id'] ;
            $data['ward_mstr_id'] = $inputs['ward_mstr_id'] ;
        }
        if(isset($inputs['from_date']) && isset($inputs['to_date']))
        {
            if($inputs['from_date']!='')
            {
                $from_date = $inputs['from_date'];
            }
            if($inputs['to_date']!='')
            {
                $to_date = $inputs['to_date'];
            }            
        }
        $data['from_date']=$from_date;
        $data['to_date'] =$to_date;
        if(isset($inputs['emp_details_id']) && $inputs['emp_details_id']!='')
        {
            $with_where .=" AND tbl_consumer_demand.emp_details_id = ".$inputs['emp_details_id'];
            $data['emp_details_id'] = $inputs['emp_details_id'];
        }
        $with_where .=" AND tbl_consumer_demand.generation_date BETWEEN '".$from_date."' AND '".$to_date."'";
        $sql ="WITH demand AS ( 
                    SELECT DISTINCT(consumer_id) AS consumer_id, COUNT(DISTINCT(consumer_id)) AS num_demand, 
                        COUNT(tbl_consumer_demand.id),
                        ward_mstr_id
                    FROM tbl_consumer_demand 
                    WHERE 1=1 $with_where
                    GROUP BY consumer_id,ward_mstr_id 
                ),
                consumer AS(
                    SELECT DISTINCT(ward_mstr_id)AS ward_mstr_id ,COUNT(tbl_consumer.id)AS count_id
                    FROM tbl_consumer
                    JOIN view_ward_mstr ON view_ward_mstr.id = tbl_consumer.ward_mstr_id
                    WHERE tbl_consumer.status =1 
                    GROUP BY ward_mstr_id
                ),
                demand_genereted AS (
                    SELECT DISTINCT(tbl_consumer.ward_mstr_id) AS ward_mstr_id,COUNT(*) AS count_id
                    FROM tbl_consumer
                    JOIN demand ON demand.consumer_id = tbl_consumer.id
                    WHERE tbl_consumer.status =1 
                    GROUP BY tbl_consumer.ward_mstr_id
                )
                SELECT DISTINCT(view_ward_mstr.id) AS ward_mstr_id,view_ward_mstr.ward_no,
                    (substring(view_ward_mstr.ward_no, '^[0-9]+'))::int,
                    SUM(coalesce(consumer.count_id,0)) - SUM(coalesce(demand_genereted.count_id,0)) AS not_generate,
                    SUM(coalesce(demand_genereted.count_id,0)) AS generate,
                    SUM(coalesce(consumer.count_id,0)) AS total_consumer
                FROM view_ward_mstr
                JOIN consumer on consumer.ward_mstr_id = view_ward_mstr.id
                LEFT JOIN demand_genereted on demand_genereted.ward_mstr_id = view_ward_mstr.id
                WHERE 1=1 $where
                GROUP BY view_ward_mstr.id,view_ward_mstr.ward_no 
                ORDER BY (substring(view_ward_mstr.ward_no, '^[0-9]+'))::int";
        $data['summary'] = $this->WaterMobileModel->row_sql($sql);
        
        // print_var($sql);

        return view('water/report/ward_wise_demand', $data);
        
    }
    
    public function ward_wise_demand_dtl($action,$ward_id,$from_date,$to_date,$emp_id='ALL')
    {
        try{

            $from_date = date('Y-m-d',strtotime($from_date));
            $to_date = date('Y-m-d',strtotime($to_date));
            $join = "";
            $join_where = "";
            $where = " ";
            $with_where="  AND tbl_consumer_demand.status = 1 ";
            if($action!="generate"){
                $join = " LEFT ";
                $join_where="  AND demand.consumer_id isnull ";
            }   
            if($emp_id !='ALL')     
            {
                $with_where .=" AND tbl_consumer_demand.emp_details_id = ".$emp_id; 
            } 
            if($ward_id)     
            {
                $where .=" AND view_ward_mstr.id = ".$ward_id; 
            }   
            $sql = "WITH demand AS ( 
                        SELECT DISTINCT(consumer_id) AS consumer_id, COUNT(DISTINCT(consumer_id)) AS num_demand, 
                            COUNT(tbl_consumer_demand.id),
                            ward_mstr_id
                        FROM tbl_consumer_demand 
                        WHERE 1=1 AND tbl_consumer_demand.generation_date BETWEEN '$from_date' AND '$to_date'
                            $with_where
                        GROUP BY consumer_id,ward_mstr_id 
                    )
                    SELECT tbl_consumer.id,tbl_consumer.consumer_no,view_ward_mstr.ward_no,
                        owners.applicant_name,owners.father_name,owners.mobile_no,
                        case when meter_status.connection_type in(1,2) then 'Meter' else 'Fixed' end as connection_type
                    FROM tbl_consumer
                    JOIN view_ward_mstr on view_ward_mstr.id = tbl_consumer.ward_mstr_id
                    $join JOIN demand on demand.consumer_id = tbl_consumer.id
                    LEFT JOIN (
                        SELECT DISTINCT(tbl_consumer_details.consumer_id) as consumer_id,
                            STRING_AGG(applicant_name,',') as applicant_name,
                            STRING_AGG(father_name,',') as father_name,
                            STRING_AGG(mobile_no::text,',') as mobile_no
                        from tbl_consumer_details
                        LEFT JOIN demand on demand.consumer_id = tbl_consumer_details.consumer_id
                        where tbl_consumer_details.status =1 $join_where
                        group by tbl_consumer_details.consumer_id
                    ) owners on owners.consumer_id = tbl_consumer.id
                    LEFT JOIN (
                        SELECT consumer_id,connection_type
                        FROM tbl_meter_status
                        where id in(
                            select max(tbl_meter_status.id) 
                            from tbl_meter_status
                            join tbl_consumer on tbl_consumer.id = tbl_meter_status.consumer_id
                            where tbl_meter_status.status =1 
                                and tbl_consumer.ward_mstr_id = $ward_id
                            group by tbl_meter_status.consumer_id
                        )
                    )meter_status on meter_status.consumer_id = tbl_consumer.id
                    where tbl_consumer.status=1 $where $join_where
            ";
            $data['summary'] = $this->WaterMobileModel->row_sql($sql);
            if($this->request->getMethod() == 'post' && $this->request->getVar('Export')=='Export')
            {   
                $records=[];
                foreach ($data['summary'] AS $key=>$tran_dtl) 
                {
                    $records[] = [
                        'ward_no'=>$tran_dtl['ward_no'],                        
                        'consumer_no'=>$tran_dtl['consumer_no'],                        
                        'applicant_name'=>$tran_dtl['applicant_name'],
                        'father_name'=>$tran_dtl['father_name'],
                        'mobile_no'=>$tran_dtl['mobile_no'],
                        'connection_type'=>$tran_dtl['connection_type'],
                    ];
                }             
                phpOfficeLoad();
                $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                $activeSheet = $spreadsheet->getActiveSheet();
                                $activeSheet->setCellValue('A1', 'Ward No.');
                                $activeSheet->setCellValue('B1', 'Consumer No.');
                                $activeSheet->setCellValue('C1', 'Owner Name');
                                $activeSheet->setCellValue('D1', 'Father Name');
                                $activeSheet->setCellValue('E1', 'Mobile No');
                                $activeSheet->setCellValue('F1', 'Connection Type');  

                                $activeSheet->fromArray($records, NULL, 'A3');
                $filename = $data['summary'][0]["ward_no"].($join?"_Demand_Not_Genrated_": "_Demand_Genrated_").date('Ymd-hisa').".xlsx";
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="'.$filename.'"');
                header('Cache-Control: max-age=0');
                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                //$writer->save(APPPATH.'/hello world.xlsx');
                $writer->save('php://output');
            }
            return view("water/report/ward_wise_demand_dtl",$data);
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function get_permited_ward()
    {
        if(strtoupper($this->request->getMethod())=="POST")
        {
            $inputs = $this->request->getVar();
            $where ="";
            if(isset($inputs['emp_details_id']) && $inputs['emp_details_id']!='')
            {
                $where = " AND emp_details_id =".$inputs['emp_details_id'];
            }
            $sql = " SELECT tbl_ward_mstr.id,
                        tbl_ward_mstr.ward_no
                    FROM dblink('host=".env("db.pgsql.hname")." user=".env("db.pgsql.uname")." password=".env("db.pgsql.pass")." port=".env("db.pgsql.port")." dbname=db_system'::text,
                        'SELECT tbl_ward_mstr.id, ward_no, ulb_mstr_id,tbl_ward_permission.emp_details_id 
                        FROM tbl_ward_mstr 
                        join tbl_ward_permission 
                        on tbl_ward_permission.ward_mstr_id=tbl_ward_mstr.id
                        where tbl_ward_mstr.status=1 and tbl_ward_permission.status=1 
                            and ulb_mstr_id = 1 $where '::text
                            ) 
                    tbl_ward_mstr(id integer, ward_no text, ulb_mstr_id bigint, emp_details_id bigint);";

            $data = $this->WaterMobileModel->row_sql($sql);
            // print_var($data);die;
            $html = "<option value=''>All</option>";
            foreach ($data as $val)
            {
                $html .= "<option value=".$val['id'].">".$val['ward_no']."</option>";
            }

           
            $data = [
                "response"=>true,
                "data"=>$html
            ]; 
            return json_encode($data);
        } else {
            $data = [
                "response"=>false
            ]; 
            return json_encode($data);
        }
    }

    public function MainDraySupper()
    {
        try
        {
            if(strtoupper($this->request->getMethod())=="POST")
            {
                $fy = explode('-',getFY()); 
                if($this->request->getVar('fyear'))
                {
                    $fy = explode('-',$this->request->getVar('fyear'));
                }        
                $from =$fy[0].'-03-31'; 
                $to = $fy[1].'-03-31';
                //PRINT_var("from = ".$from);PRINT_var("to = ".$to);die;
                $whereClause="where 1=1 AND tbl_consumer.status = 1 ";
    
                $inputs=$this->request->getVar();
                $start = sanitizeString($this->request->getVar('start'));
                    
                $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page
    
                $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
                $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
                if ($columnName=="consumer_no" )
                    $columnName='tbl_consumer.consumer_no';
                else if ($columnName=="applicant_name")
                    $columnName = 'owner.applicant_name';
                else if ($columnName=="property_type")
                    $columnName = 'tbl_consumer.property_type_id';            
                else
                    $columnName = 'tbl_consumer.id ';
    
                //$columnName = "tbl_transaction.tran_date";
                $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
                $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value
                
                // Date filterarchValue = sanitizeString($this->request->getVar('search')['value']);
                $orderBY = " ORDER BY ".$columnName." ".$columnSortOrder;
                $limit = " LIMIT ".($rowperpage==-1?"ALL":$rowperpage)." OFFSET ".$start;            
                // echo"$limit";die;
                if($inputs["ward_id"]!="")
                {
                    $whereClause .= " AND tbl_consumer.ward_mstr_id=".$inputs["ward_id"];
                }
                if($inputs["property_type"]!="")
                {                
                    $whereClause.=" AND tbl_consumer.property_type_id =".$inputs["property_type"]." " ;   
                }
                if($inputs["category"]!="")//"APL or BPL";
                {
                    $whereClause .= " and tbl_consumer.category='".$inputs["category"]."'";
                }
                if($inputs["connection_type"]!="")
                {
                    if($inputs["connection_type"] == "Meter")
                    {
                        $conn="1,2";
                    }
                    if($inputs["connection_type"] == "Non-Meter")
                    {
                        $conn="3";
                    }
                    $whereClause .= " and meter_type.connection_type in ($conn)";
                }
    
                $whereQueryWithSearch = "";
                if ($searchValue!='') 
                {
                    $whereQueryWithSearch = " AND (tbl_consumer.consumer_no ILIKE '%".$searchValue."%'
                                    OR owner.applicant_name ILIKE '%".$searchValue."%'
                                    OR tbl_property_type_mstr.property_type ILIKE '%".$searchValue."%'
                                     )";
                }
                $base_url = base_url();
                
                $with = "with owner as (
                                SELECT tbl_consumer_details.consumer_id,
                                    string_agg(tbl_consumer_details.applicant_name::text, ','::text) AS applicant_name,
                                    string_agg(tbl_consumer_details.mobile_no::text, ','::text) AS mobile_no
                                FROM tbl_consumer_details
                                join tbl_consumer on  tbl_consumer.id = tbl_consumer_details.consumer_id
                                where tbl_consumer.status =1 and tbl_consumer_details.status=1
                                GROUP BY tbl_consumer_details.consumer_id
                        ),
                        demand as ( 
                            SELECT tbl_consumer_demand.consumer_id,
                                sum(
                                    CASE WHEN tbl_consumer_demand.demand_upto <= '$from'::date 
                                        THEN tbl_consumer_demand.amount       
                                    ELSE NULL::numeric
                                    END
                                ) AS arrear_demand,
                                sum(
                                    CASE WHEN tbl_consumer_demand.demand_upto > '$from'::date 
                                        AND tbl_consumer_demand.demand_upto <= '$to'::date 
                                        THEN tbl_consumer_demand.amount
                                        ELSE NULL::numeric
                                    END
                                ) AS curr_demand
                            FROM tbl_consumer_demand
                            WHERE tbl_consumer_demand.status = 1
                            GROUP BY tbl_consumer_demand.consumer_id
                        ),
                        coll as ( 
                            SELECT tbl_consumer_collection.consumer_id,
                                sum(
                                    CASE WHEN tbl_consumer_demand.demand_upto <= '$from'::date 
                                        THEN tbl_consumer_collection.amount
                                        ELSE NULL::numeric
                                    END
                                ) AS arrear_coll,
                                sum(
                                    CASE WHEN tbl_consumer_demand.demand_upto > '$from'::date 
                                        AND tbl_consumer_demand.demand_upto <= '$to'::date 
                                        THEN tbl_consumer_collection.amount
                                        ELSE NULL::numeric
                                    END
                                ) AS curr_coll
                            FROM tbl_consumer_collection
                            JOIN tbl_consumer_demand ON tbl_consumer_demand.id = tbl_consumer_collection.demand_id
                            JOIN tbl_transaction ON tbl_transaction.id = tbl_consumer_collection.transaction_id
                            WHERE tbl_transaction.transaction_date > '$from'::date 
                                AND tbl_transaction.transaction_date <= '$to'::date
                                AND tbl_transaction.status in(1,2)
                            GROUP BY tbl_consumer_collection.consumer_id
                        ),
                        prev_coll_amount as ( 
                                SELECT tbl_transaction.related_id,
                                    sum(tbl_consumer_collection.amount) AS prev_coll
                                FROM tbl_consumer_collection
                                JOIN tbl_consumer_demand ON tbl_consumer_demand.id = tbl_consumer_collection.demand_id
                                JOIN tbl_transaction ON tbl_transaction.id = tbl_consumer_collection.transaction_id
                                WHERE tbl_transaction.transaction_date <= '$from'::date 
                                    AND tbl_transaction.transaction_type::text = 'Demand Collection'::text
                                    AND tbl_transaction.status in(1,2)
                                GROUP BY tbl_transaction.related_id
                        ),
                        meter_type as ( 
                            SELECT tbl_meter_status.id,
                            tbl_meter_status.consumer_id,
                            tbl_meter_status.connection_type
                            FROM tbl_meter_status
                            WHERE (
                                    tbl_meter_status.id IN ( 
                                                        SELECT max(tbl_meter_status_1.id) AS id
                                                        FROM tbl_meter_status tbl_meter_status_1
                                                        where status = 1
                                                        GROUP BY tbl_meter_status_1.consumer_id
                                                        ORDER BY (max(tbl_meter_status_1.id))
                                                        )
                                )                        
                        ),
                        supper_Dray as (
                            select distinct(tbl_consumer.id) as consumer_id,
                                tbl_transaction.id as transaction_id
                            from tbl_consumer
                            left join tbl_transaction on tbl_transaction.related_id = tbl_consumer.id 
                                and tbl_transaction.status in (1,2)
                                and tbl_transaction.transaction_type = 'Demand Collection'
                            where tbl_consumer.status=1        
                                and tbl_transaction.id isnull
                        )
                ";                        
                $select = " SELECT ROW_NUMBER () OVER (ORDER BY ".$columnName.") AS s_no,
                            tbl_consumer.id,
                            tbl_consumer.consumer_no,
                            tbl_property_type_mstr.property_type,
                            tbl_consumer.ward_mstr_id,
                            view_ward_mstr.ward_no,
                            tbl_consumer.category,
                            tbl_consumer.address,
                            CASE WHEN meter_type.connection_type IN (1,2) THEN 'Meter'
                                ELSE 'Fixed' END AS connetion_type ,
                            owner.applicant_name,
                            owner.mobile_no,
                            CASE WHEN tbl_consumer.apply_from ='Existing' AND tbl_consumer.created_on::date>='2021-10-26' THEN 'Dry Consumer'
                                WHEN supper_Dray.consumer_id ISNULL THEN 'Main Consumer'
                                ELSE  'Supper Dry Consumer'
                            END AS consumer_Type,

                            COALESCE(
                                COALESCE(demand.arrear_demand, 0::numeric) 
                                - COALESCE(prev_coll_amount.prev_coll, 0::numeric), 0::numeric
                            ) AS outstanding_at_begin,
                            
                            COALESCE(prev_coll_amount.prev_coll, 0::numeric) AS prev_coll,
                            COALESCE(demand.curr_demand, 0::numeric) AS current_demand,
                            COALESCE(coll.arrear_coll, 0::numeric) AS arrear_coll,
                            COALESCE(coll.curr_coll, 0::numeric) AS curr_coll,
                            
                            (COALESCE(
                                    COALESCE(demand.arrear_demand, 0::numeric) 
                                    - COALESCE(prev_coll_amount.prev_coll, 0::numeric), 0::numeric
                                ) 
                                - COALESCE(coll.arrear_coll, 0::numeric) )AS old_due,
                                
                            (COALESCE(demand.curr_demand, 0::numeric) - COALESCE(coll.curr_coll, 0::numeric)) AS curr_due,
                            
                            (
                                COALESCE(
                                    COALESCE(demand.curr_demand, 0::numeric) 
                                    + (
                                        COALESCE(demand.arrear_demand, 0::numeric) 
                                        - COALESCE(prev_coll_amount.prev_coll, 0::numeric)
                                    ), 0::numeric
                                ) 
                                - COALESCE(
                                    COALESCE(coll.curr_coll, 0::numeric) 
                                    + COALESCE(coll.arrear_coll, 0::numeric), 0::numeric
                                )
                            ) AS outstanding 
                ";
    
                $from_tbl = " FROM tbl_consumer
                        LEFT JOIN owner ON owner.consumer_id = tbl_consumer.id
                        LEFT JOIN view_ward_mstr ON view_ward_mstr.id = tbl_consumer.ward_mstr_id
                        LEFT JOIN tbl_property_type_mstr ON tbl_property_type_mstr.id = tbl_consumer.property_type_id
                        LEFT JOIN demand ON demand.consumer_id = tbl_consumer.id
                        LEFT JOIN coll ON coll.consumer_id = tbl_consumer.id
                        LEFT JOIN prev_coll_amount ON prev_coll_amount.related_id = tbl_consumer.id
                        LEFT JOIN  meter_type ON meter_type.consumer_id = tbl_consumer.id   
                        LEFT JOIN supper_Dray ON supper_Dray.consumer_id = tbl_consumer.id                  
                        $whereClause
                ";
               
                $totalRecords = $this->model_datatable->getTotalRecords($from_tbl,false,$with);
                if ($totalRecords>0) 
                {
                    
                    ## Total number of records with filtering
                    $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($from_tbl.$whereQueryWithSearch,false,$with);
                    
                    ## Fetch records                
                   $fetchSql = $with.$select.$from_tbl.$whereQueryWithSearch.$orderBY.$limit;
                    
                    $result = $this->model_datatable->getRecords($fetchSql,false);                
                    
                    $records = [];
                    if ($result) 
                    {
                        foreach ($result AS $key=>$tran_dtl) 
                        {
                            $records[] = [
                                's_no'=>$tran_dtl['s_no'],
                                'consumer_no'=>$tran_dtl['consumer_no'],
                                'ward_no'=>$tran_dtl['ward_no'],
                                'applicant_name'=>$tran_dtl['applicant_name'],
                                'mobile_no'=>$tran_dtl['mobile_no'],
                                'address'=>$tran_dtl['address'],
                                'property_type'=>$tran_dtl['property_type'],
                                'consumer_type'=>$tran_dtl['consumer_type'],
                                'connetion_type'=>$tran_dtl['connetion_type'],
                                'outstanding_at_begin'=>$tran_dtl['outstanding_at_begin'],
                                'current_demand'=>$tran_dtl['current_demand'],
                                'total'=>$tran_dtl['outstanding_at_begin']+$tran_dtl['current_demand'],
                                'arrear_coll'=>$tran_dtl['arrear_coll'],
                                'curr_coll'=>$tran_dtl['curr_coll'],
                                'old_due'=>$tran_dtl['old_due'],
                                'curr_due'=>$tran_dtl['curr_due'],
                                'outstanding'=>$tran_dtl['outstanding'],
                                // 'link'=>$tran_dtl['link'],
                                
                            ];
                        }
                    }
                } 
                else 
                {
                    $totalRecordwithFilter = 0;
                    $records = [];
                }
    
                $response = array(
                    "draw" => 0,                
                    "recordsTotal" => $totalRecords,                 
                    "recordsFiltered" => $totalRecordwithFilter,
                    "data" => $records,                
    
                );
                return json_encode($response); 
                
            }
            else
            {
                $data['ulb_mstr_id']=$this->ulb_id;
                $data['ward_list']=$this->ward_model->getWardList($data);
                $sql = " select * from tbl_property_type_mstr where status = 1 ";
                $data['property_list'] = $this->water_report_model->row_sql($sql);                
                return view('water/report/MainDraySupper', $data);
            }
            
        }
        catch(Exception $e)
        {
            print_r($e);
        }
    }


    public function WaterConsumerWiseDCBReportExcel2($search_ward_mstr_id=null,$property_type=null,$category=null,$connection_type=null,$fyear = null)
    {   
        try
        {    
                $fy = explode('-',getFY());
                if($fyear)
                {
                    $fy = explode('-',$fyear);
                }         
                $from =$fy[0].'-03-31'; 
                $to = $fy[1].'-03-31';
                $whereQuery = " where 1=1 AND tbl_consumer.status = 1 ";
            
                if ($search_ward_mstr_id != 'ALL' && $search_ward_mstr_id !=null) 
                {
                    $whereQuery .= " AND  tbl_consumer.ward_mstr_id='".$search_ward_mstr_id."'";
                }
                if($property_type!=""  && $property_type != 'ALL')
                {                    
                    $whereQuery.=" AND tbl_consumer.property_type_id =".$property_type." "; 
                }
                if($category!="" && $category !='ALL')//"APL or BPL";
                {
                    $whereQuery .= " and tbl_consumer.category='".$category."'";
                }
                if($connection_type!="" && $connection_type != 'ALL')
                {
                    if($connection_type== "Meter")
                    {
                        $conn="1,2";
                    }
                    if($connection_type== "Non-Meter")
                    {
                        $conn="3";
                    }
                    $whereQuery .= " and meter_type.connection_type in ($conn)";
                }
                

                $selectStatement = "SELECT 
                                        tbl_consumer.id,
                                        view_ward_mstr.ward_no,
                                        tbl_consumer.consumer_no,
                                        tbl_property_type_mstr.property_type,
                                        tbl_consumer.ward_mstr_id,
                                        tbl_consumer.category,                                        
                                        tbl_consumer.address,
                                        CASE WHEN meter_type.connection_type IN (1,2) THEN 'Meter'
                                            ELSE 'Fixed' END AS connetion_type,
                                        owner.applicant_name,
                                        owner.mobile_no,
                                        CASE WHEN tbl_consumer.apply_from ='Existing' AND tbl_consumer.created_on::date>='2021-10-26' THEN 'Dry Consumer'
                                            WHEN supper_Dray.consumer_id ISNULL THEN 'Main Consumer'
                                            ELSE  'Supper Dry Consumer'
                                            END AS consumer_Type,
                                        
                                        COALESCE(
                                            COALESCE(demand.arrear_demand, 0::numeric) 
                                            - COALESCE(prev_coll_amount.prev_coll, 0::numeric), 0::numeric
                                        ) AS outstanding_at_begin,
                                        
                                        COALESCE(prev_coll_amount.prev_coll, 0::numeric) AS prev_coll,
                                        COALESCE(demand.curr_demand, 0::numeric) AS current_demand,
                                        COALESCE(coll.arrear_coll, 0::numeric) AS arrear_coll,
                                        COALESCE(coll.curr_coll, 0::numeric) AS curr_coll,
                                        
                                        (COALESCE(
                                                COALESCE(demand.arrear_demand, 0::numeric) 
                                                - COALESCE(prev_coll_amount.prev_coll, 0::numeric), 0::numeric
                                            ) 
                                            - COALESCE(coll.arrear_coll, 0::numeric) )AS old_due,
                                            
                                        (COALESCE(demand.curr_demand, 0::numeric) - COALESCE(coll.curr_coll, 0::numeric)) AS curr_due,
                                        
                                        (
                                            COALESCE(
                                                COALESCE(demand.curr_demand, 0::numeric) 
                                                + (
                                                    COALESCE(demand.arrear_demand, 0::numeric) 
                                                    - COALESCE(prev_coll_amount.prev_coll, 0::numeric)
                                                ), 0::numeric
                                            ) 
                                            - COALESCE(
                                                COALESCE(coll.curr_coll, 0::numeric) 
                                                + COALESCE(coll.arrear_coll, 0::numeric), 0::numeric
                                            )
                                        ) AS outstanding 
                            ";

                $with_query ="with owner as (
                                    SELECT tbl_consumer_details.consumer_id,
                                        string_agg(tbl_consumer_details.applicant_name::text, ','::text) AS applicant_name,
                                        string_agg(tbl_consumer_details.mobile_no::text, ','::text) AS mobile_no
                                    FROM tbl_consumer_details
                                    join tbl_consumer on  tbl_consumer.id = tbl_consumer_details.consumer_id
                                    where tbl_consumer.status =1 and tbl_consumer_details.status=1
                                    GROUP BY tbl_consumer_details.consumer_id
                            ),
                            demand as ( 
                                SELECT tbl_consumer_demand.consumer_id,
                                    sum(
                                        CASE WHEN tbl_consumer_demand.demand_upto <= '$from'::date 
                                            THEN tbl_consumer_demand.amount       
                                        ELSE NULL::numeric
                                        END
                                    ) AS arrear_demand,
                                    sum(
                                        CASE WHEN tbl_consumer_demand.demand_upto > '$from'::date 
                                            AND tbl_consumer_demand.demand_upto <= '$to'::date 
                                            THEN tbl_consumer_demand.amount
                                            ELSE NULL::numeric
                                        END
                                    ) AS curr_demand
                                FROM tbl_consumer_demand
                                WHERE tbl_consumer_demand.status = 1
                                GROUP BY tbl_consumer_demand.consumer_id
                            ),
                            coll as ( 
                                SELECT tbl_consumer_collection.consumer_id,
                                    sum(
                                        CASE WHEN tbl_consumer_demand.demand_upto <= '$from'::date 
                                            THEN tbl_consumer_collection.amount
                                            ELSE NULL::numeric
                                        END
                                    ) AS arrear_coll,
                                    sum(
                                        CASE WHEN tbl_consumer_demand.demand_upto > '$from'::date 
                                            AND tbl_consumer_demand.demand_upto <= '$to'::date 
                                            THEN tbl_consumer_collection.amount
                                            ELSE NULL::numeric
                                        END
                                    ) AS curr_coll
                                FROM tbl_consumer_collection
                                JOIN tbl_consumer_demand ON tbl_consumer_demand.id = tbl_consumer_collection.demand_id
                                JOIN tbl_transaction ON tbl_transaction.id = tbl_consumer_collection.transaction_id
                                WHERE tbl_transaction.transaction_date > '$from'::date 
                                    AND tbl_transaction.transaction_date <= '$to'::date
                                    AND tbl_transaction.status in(1,2)
                                GROUP BY tbl_consumer_collection.consumer_id
                            ),
                            prev_coll_amount as ( 
                                    SELECT tbl_transaction.related_id,
                                        sum(tbl_consumer_collection.amount) AS prev_coll
                                    FROM tbl_consumer_collection
                                    JOIN tbl_consumer_demand ON tbl_consumer_demand.id = tbl_consumer_collection.demand_id
                                    JOIN tbl_transaction ON tbl_transaction.id = tbl_consumer_collection.transaction_id
                                    WHERE tbl_transaction.transaction_date <= '$from'::date 
                                        AND tbl_transaction.transaction_type::text = 'Demand Collection'::text
                                        AND tbl_transaction.status in(1,2)
                                    GROUP BY tbl_transaction.related_id
                            ),
                            meter_type as ( 
                                SELECT tbl_meter_status.id,
                                tbl_meter_status.consumer_id,
                                tbl_meter_status.connection_type
                                FROM tbl_meter_status
                                WHERE (
                                        tbl_meter_status.id IN ( 
                                                            SELECT max(tbl_meter_status_1.id) AS id
                                                            FROM tbl_meter_status tbl_meter_status_1
                                                            where status = 1
                                                            GROUP BY tbl_meter_status_1.consumer_id
                                                            ORDER BY (max(tbl_meter_status_1.id))
                                                            )
                                    )                        
                            ),
                            supper_Dray as (
                                select distinct(tbl_consumer.id) as consumer_id,
                                    tbl_transaction.id as transaction_id
                                from tbl_consumer
                                left join tbl_transaction on tbl_transaction.related_id = tbl_consumer.id 
                                    and tbl_transaction.status in (1,2)
                                    and tbl_transaction.transaction_type = 'Demand Collection'
                                where tbl_consumer.status=1        
                                    and tbl_transaction.id isnull
                            )
                ";
                $sql ="FROM tbl_consumer
                        LEFT JOIN owner ON owner.consumer_id = tbl_consumer.id
                        LEFT JOIN view_ward_mstr ON view_ward_mstr.id = tbl_consumer.ward_mstr_id
                        LEFT JOIN tbl_property_type_mstr ON tbl_property_type_mstr.id = tbl_consumer.property_type_id
                        LEFT JOIN demand ON demand.consumer_id = tbl_consumer.id
                        LEFT JOIN coll ON coll.consumer_id = tbl_consumer.id
                        LEFT JOIN prev_coll_amount ON prev_coll_amount.related_id = tbl_consumer.id
                        LEFT JOIN  meter_type ON meter_type.consumer_id = tbl_consumer.id 
                        LEFT JOIN supper_Dray ON supper_Dray.consumer_id = tbl_consumer.id                    
                        $whereQuery
                ";

            $fetchSql = $with_query.$selectStatement.$sql;
            
            $result = $this->model_datatable->getRecords($fetchSql,false);
            // PRINT_VAR(count($result));die;
            $records = [];
            if ($result) 
            {
                foreach ($result AS $key=>$tran_dtl) 
                {
                    

                    $records[] = [
                        'ward_no'=>$tran_dtl['ward_no'],
                        'consumer_no'=>$tran_dtl['consumer_no'],
                        'applicant_name'=>$tran_dtl['applicant_name'],
                        'mobile_no'=>$tran_dtl['mobile_no'],
                        'address'=>$tran_dtl['address'],
                        'property_type'=>$tran_dtl['property_type'],
                        'consumer_type'=>$tran_dtl['consumer_type'],
                        'connetion_type'=>$tran_dtl['connetion_type'],
                        'outstanding_at_begin'=>$tran_dtl['outstanding_at_begin'],
                        'current_demand'=>$tran_dtl['current_demand'],
                        'total'=>$tran_dtl['outstanding_at_begin']+$tran_dtl['current_demand'],
                        'arrear_coll'=>$tran_dtl['arrear_coll'],
                        'curr_coll'=>$tran_dtl['curr_coll'],
                        'old_due'=>$tran_dtl['old_due'],
                        'curr_due'=>$tran_dtl['curr_due'],
                        'outstanding'=>$tran_dtl['outstanding'],

                        
                    ];

                }
            }
            phpOfficeLoad();
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $activeSheet = $spreadsheet->getActiveSheet();
                            $activeSheet->setCellValue('A1', 'Ward No.');
                            $activeSheet->setCellValue('B1', 'Consumer No.');
                            $activeSheet->setCellValue('C1', 'Consumer Name');
                            $activeSheet->setCellValue('D1', 'Mobile No');
                            $activeSheet->setCellValue('E1', 'Address');
                            $activeSheet->setCellValue('F1', 'Property Type');
                            $activeSheet->setCellValue('G1', 'Consumer Type');
                            $activeSheet->setCellValue('H1', 'Connection Type');
                            $activeSheet->setCellValue('I1', 'Outstanding at the begining');
                            $activeSheet->setCellValue('J1', 'Current Demand');
                            $activeSheet->setCellValue('K1', 'Total Demand');
                            $activeSheet->setCellValue('L1', 'Old Due Collection');
                            $activeSheet->setCellValue('M1', 'Current Collection');
                            $activeSheet->setCellValue('N1', 'Old Due');
                            $activeSheet->setCellValue('O1', 'Current Due');
                            $activeSheet->setCellValue('P1', 'Outstanding Due');


                            $activeSheet->fromArray($records, NULL, 'A3');

            $filename = "ConsumerWiseDCB_".date('Ymd-hisa').".xlsx";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            //$writer->save(APPPATH.'/hello world.xlsx');
            $writer->save('php://output');
        }
        catch(Exception $e)
        {   
            print_var($e);
        }
    }
    public function BTCExportExcel($from_date=null,$to_date=null,$ward_id=null)
    {    
        $where="1=1 ";    
        if($from_date==null)
        {
            $from_date=date('Y-m-d');            
        }
        if($to_date==null)
        {
            $to_date=date('Y-m-d');            
        }
        if($ward_id==null || $ward_id=='ALL')
        {
            $ward_id='';            
        }
        try
        {
            $where.=" AND level_date between '".$from_date."' and '".$to_date."'";
            if ($ward_id != '') 
            {
                $where .= " AND  ward_id = ".$ward_id;
            }
            $result = $this->water_report_model->backtocitizenListReport($where);
            $records = [];
            if ($result) 
            {
                foreach ($result AS $key=>$tran_dtl) 
                {
                    

                    $records[] = [                        
                        'ward_no'=>$tran_dtl['ward_no'],
                        'application_no'=>$tran_dtl['application_no'], 
                        'applicant_name'=>$tran_dtl['applicant_name'],
                        'mobile_no'=>$tran_dtl['mobile_no'],   
                        'category'=>$tran_dtl['category'],
                        'connection_type'=>$tran_dtl['connection_type'], 
                        'apply_date'=>$tran_dtl['apply_date'],
                        'remarks'=>$tran_dtl['remarks'],  
                        'user_type'=>$tran_dtl['user_type'],                                        
                    ];

                }
            }
            phpOfficeLoad();
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $activeSheet = $spreadsheet->getActiveSheet();
                            $activeSheet->setCellValue('A1', 'Ward No.');
                            $activeSheet->setCellValue('B1', 'Application No.');
                            $activeSheet->setCellValue('C1', 'Applicant Name');
                            $activeSheet->setCellValue('D1', 'Mobile No.');    
                            
                            $activeSheet->setCellValue('E1', 'Category');
                            $activeSheet->setCellValue('F1', 'Connection Type');
                            $activeSheet->setCellValue('G1', 'Apply Date');
                            $activeSheet->setCellValue('H1', 'Remarks'); 
                            $activeSheet->setCellValue('I1', 'User Type'); 
                            
                            $activeSheet->fromArray($records, NULL, 'A3');
            $filename = "Water_BTC_report_".date('Ymd-hisa').".xlsx";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            //$writer->save(APPPATH.'/hello world.xlsx');
            $writer->save('php://output');
        }
        catch(Exception $e)
        {
            print_r($e);
        }
    }

    public function WaterSurvey()
    {
        try{
            $data =(array)null;
            $Session = session();
            $ulb_dtl = $Session->get('ulb_dtl');
            $ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];
            $uri_string = uri_string();
            if ($this->request->getMethod() == 'post') 
            {
                
                ## Read value
                $start = sanitizeString($this->request->getVar('start'));

                $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page

                $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
                $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
                
                if ($columnName == "s_no" )
                    $columnName = 'tbl_consumer.id';
                elseif ($columnName == "Meter_status")
                    $columnName = 'meter_status.connection_type';
                elseif ($columnName == "demand_remark")
                    $columnName = 'demand_dtl.consumer_id';
                elseif ($columnName == "owner_name")
                    $columnName = 'owner.owner_name';
                elseif ($columnName == "father_name")
                    $columnName = 'owner.father_name';
                elseif ($columnName == "mobile_no")
                    $columnName = 'owner.mobile_no';
                elseif ($columnName == "ward_no")
                    $columnName = 'view_ward_mstr.ward_no';
                else
                    $columnName = 'tbl_consumer.id';
                
                

                $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
                $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value

                $survey = sanitizeString($this->request->getVar('survey')); 
                $consumer_type = sanitizeString($this->request->getVar('consumer_type'));
                $connection_type = sanitizeString($this->request->getVar('connection_type'));
                $ward_id = sanitizeString($this->request->getVar('ward_id'));

                $consumer_type_wher = " AND 1=1 ";           
                if($consumer_type=='Dry Consumer')
                {
                    $consumer_type_wher =" AND (tbl_consumer.apply_from ='Existing' AND tbl_consumer.created_on::date>='2021-10-26')"; 
                }
                if($consumer_type=='Main Consumer')
                {
                    $consumer_type_wher =" AND supper_Dray.consumer_id ISNULL AND ((tbl_consumer.apply_from ='Existing' AND tbl_consumer.created_on::date>='2021-10-26'))=FALSE"; 
                }
                if($consumer_type=='Supper Dry Consumer')
                {
                    $consumer_type_wher =" AND supper_Dray.consumer_id IS NOT NULL AND ((tbl_consumer.apply_from ='Existing' AND tbl_consumer.created_on::date>='2021-10-26'))=FALSE"; 
                }
                if($consumer_type=='Dry Consumer & Main Consumer')
                {
                    $consumer_type_wher =" AND ((tbl_consumer.apply_from ='Existing' AND tbl_consumer.created_on::date>='2021-10-26') OR (supper_Dray.consumer_id ISNULL))"; 
                }
                if($consumer_type=='Dry Consumer & Supper Dry Consumer')
                {
                    $consumer_type_wher =" AND ((tbl_consumer.apply_from ='Existing' AND tbl_consumer.created_on::date>='2021-10-26') OR (supper_Dray.consumer_id IS NOT NULL))"; 
                }
                if($consumer_type=='Main Consumer & Supper Dry Consumer')
                {
                    $consumer_type_wher =" AND (((tbl_consumer.apply_from ='Existing' AND tbl_consumer.created_on::date>='2021-10-26'))=FALSE)"; 
                }
                
                $keyword = sanitizeString($this->request->getVar('keyword'));
                $from_date =sanitizeString($this->request->getVar('from_date'));
                $upto_date =sanitizeString($this->request->getVar('upto_date'));
                $searchQuery = "";
                $whereQuery = "";

                $orderBY = " ORDER BY " . $columnName . " " . $columnSortOrder;
                $limit = " LIMIT " . $rowperpage . " OFFSET " . $start;

                if($from_date && $upto_date && $survey == "survey_done")
                {
                    $whereQuery .= " AND  tbl_water_survey.created_on::DATE BETWEEN '".$from_date."' AND '".$upto_date."' "; 
                }
                if ($survey == "survey_done") 
                {
                    $whereQuery .= " AND  tbl_water_survey.consumer_id IS NOT NULL ";                    
                } 
                elseif($survey == "survey_no_done") 
                {
                    $whereQuery .= " AND  tbl_water_survey.consumer_id IS NULL  ";
                   
                }

                if ($keyword != "") 
                {
                    $whereQuery .= " AND  (tbl_consumer.consumer_no ~* '$keyword' 
                                            OR owner.owner_name ~* '$keyword' 
                                            OR owner.father_name ~* '$keyword' 
                                            OR owner.mobile_no ~* '$keyword' )";
                    
                } 
                if(($ward_id != 'All') && $ward_id)
                {
                    $whereQuery .= " AND tbl_consumer.ward_mstr_id = $ward_id ";
                }

                if($connection_type == 1)
                {
                    $whereQuery .= " AND meter_status.connection_type in (1,2)";
                }
                if($connection_type == 3)
                {
                    $whereQuery .= " AND meter_status.connection_type not in (1,2)";
                }
                // print_var($conection_type);print_var($whereQuery);die;
                $whereQueryWithSearch = "";
                if ($searchValue != '') 
                {
                    $whereQueryWithSearch = " AND (tbl_consumer.consumer_no ~* '$searchValue' 
                                                    OR owner.owner_name ~* '$searchValue' 
                                                    OR owner.father_name ~* '$searchValue' 
                                                    OR owner.mobile_no ~* '$searchValue')";
                }

                
                $selectStatement = "SELECT 
                                        ROW_NUMBER () OVER (ORDER BY " . $columnName . ") AS s_no,				  
                                        tbl_consumer.id,        
                                        view_ward_mstr.ward_no,
                                        tbl_consumer.address,
                                        tbl_consumer.consumer_no, 
                                        CASE WHEN meter_status.connection_type=1 then 'Meter' 
                                            WHEN meter_status.connection_type=2 then'Galen'
                                            WHEN meter_status.connection_type=3 then'Fixed'
                                            ELSE 'xxxx' 
                                            END AS meter_status,
                                        CASE WHEN tbl_consumer.apply_from ='Existing' AND tbl_consumer.created_on::date>='2021-10-26' THEN 'Dry Consumer'
                                                WHEN supper_Dray.consumer_id ISNULL THEN 'Main Consumer'
                                                ELSE  'Supper Dry Consumer'
                                            END AS consumer_type ,
                                            
                                        tbl_consumer.holding_no,
                                        tbl_consumer.saf_no,                                            
                                        owner.owner_name,
                                        owner.mobile_no,
                                        
                                        
                                        CASE WHEN tbl_water_survey.is_holding_map=1 THEN 'YES' ELSE 'NO' END AS holding_map,
                                        (
                                            CASE WHEN tbl_water_survey.holding_no IS NOT NULL THEN tbl_water_survey.holding_no
                                                WHEN tbl_water_survey.prop_id IS NOT NULL THEN (
                                                                                            SELECT new_holding_no 
                                                                                            FROM view_prop_detail
                                                                                            WHERE view_prop_detail.id = tbl_water_survey.prop_id 
                                                                                        )
                                            END
                                        ) AS survey_holding_no,
                                        
                                        (
                                            CASE WHEN tbl_water_survey.saf_no IS NOT NULL THEN tbl_water_survey.saf_no
                                                WHEN tbl_water_survey.saf_id IS NOT NULL THEN (
                                                                                                SELECT saf_no
                                                                                                FROM view_saf_detail
                                                                                                WHERE view_saf_detail.id = tbl_water_survey.saf_id
                                                                                            )
                                            END
                                        )AS survey_saf_no,
                                        
                                        tbl_water_survey.reason_not_map_prop AS survey_reason_not_map,
                                        CASE WHEN tbl_water_survey.meter_connection_type_id = 1 THEN 'Meter' ELSE 'Fixed' END AS survey_meter_connection_type,
                                        tbl_water_survey.meter_no AS survey_meter_no,
                                        tbl_water_survey.is_meter_working AS survey_meter_working,
                                        tbl_water_survey.supply_duration AS survey_supply_duration,
                                        CASE WHEN tbl_water_survey.is_apply_disconneciton =1 THEN 'YES' ELSE 'NO' END AS survey_apply_disconneciton,
                                        
                                        CASE WHEN tbl_water_survey.desconn_document IS NOT NULL
                                            THEN concat('<a class=', chr(39),'btn btn-primary', chr(39), 'target = ',chr(39),'_blank',chr(39), ' href=" . base_url() . "/getImageLink.php?path=',tbl_water_survey.desconn_document,' role=button>View</a>') 
                                            END AS survey_desconn_document,

                                        CASE WHEN tbl_water_survey.bill_served_status = 1 THEN 'YES' ELSE 'NO' END AS survey_served_status,
                                        tbl_water_survey.last_bill_serve_date AS survey_last_bill_serve_date,
                                        tbl_water_survey.bill_not_serve_reason AS survey_bill_not_serve_reason,
                                        tbl_water_survey.latitude AS latitude,
                                        tbl_water_survey.longitude AS longitude,
                                        CASE WHEN tbl_water_survey.geo_doc IS NOT NULL
                                            THEN concat('<a class=', chr(39),'btn btn-primary', chr(39), 'target = ',chr(39),'_blank',chr(39), ' href=" . base_url() . "/getImageLink.php?path=',tbl_water_survey.geo_doc,' role=button>View</a>') 
                                            END AS geo_doc,
                                        tbl_water_survey.created_on::DATE AS survey_date
                ";
                // print_var($selectStatement);die;
                $sql =" FROM tbl_consumer
                        JOIN (
                            select distinct(consumer_id) as consumer_id,
                                string_agg( applicant_name , ', ') as owner_name,
                                string_agg( father_name, ', ') as father_name,
                                string_agg( mobile_no::text, ', ') as mobile_no
                            from tbl_consumer_details
                            where status =1 
                            group by consumer_id
                        ) owner on owner.consumer_id = tbl_consumer.id
                        JOIN view_ward_mstr ON view_ward_mstr.id = tbl_consumer.ward_mstr_id
                        LEFT JOIN(
                            SELECT DISTINCT(tbl_consumer.id) AS consumer_id,
                                tbl_transaction.id AS transaction_id
                            FROM tbl_consumer
                            LEFT JOIN tbl_transaction ON tbl_transaction.related_id = tbl_consumer.id 
                                AND tbl_transaction.status IN (1,2)
                                AND tbl_transaction.transaction_type = 'Demand Collection'
                            WHERE tbl_consumer.status=1        
                                AND tbl_transaction.id isnull
                        
                        ) supper_Dray ON supper_Dray.consumer_id = tbl_consumer.id
                        LEFT JOIN (
                            select connection_type,consumer_id,connection_date 
                            from tbl_meter_status 
                            inner join (
                                select max(id) as max_id
                                from tbl_meter_status 
                                where status = 1
                                group by consumer_id
                            ) AS abc ON abc.max_id=tbl_meter_status.id
                            
                        ) meter_status on meter_status.consumer_id=tbl_consumer.id
                        LEFT JOIN tbl_water_survey ON tbl_water_survey.consumer_id = tbl_consumer.id AND tbl_water_survey.status =1
                        LEFT JOIN view_emp_details ON view_emp_details.id = tbl_water_survey.emp_dtl_id
                        WHERE tbl_consumer.status = 1 
                " . $whereQuery ." ". $consumer_type_wher; 
                
                $totalRecords = $this->model_datatable->getTotalRecords($sql);
                
                if ($totalRecords > 0) 
                {
                    ## Total number of records with filtering
                    $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($sql . $whereQueryWithSearch);
                    ## Fetch records
                    $fetchSql = $selectStatement . $sql . $whereQueryWithSearch . $orderBY . $limit;
                   
                    $records = $this->model_datatable->getRecords($fetchSql, false);
                   
                } 
                else 
                {
                    $totalRecordwithFilter = 0;
                    $records = [];
                }
                $response = array(
                    "iTotalRecords" => $totalRecords,
                    "iTotalDisplayRecords" => $totalRecordwithFilter,
                    "aaData" => $records,
                    "total" => '<b style="padding-right:20px">Total :-' . $totalRecords . '</b>',
                );
                return json_encode($response);
            }
            $data['ulb_mstr_id']=$this->ulb_id;
            $data['team_leader']='';
            $tc=[
                'where'=>['user_type_id'=>[8,5,4]],
                'tbl'=>'view_emp_details',
                'column'=>['id','emp_name','user_type','lock_status','employee_code']
            ];
            $data['tc']=$this->WaterMobileModel->getDataNew($tc['where'],$tc['column'],$tc['tbl'],array(),array('emp_name'=>'ASC'));
            
            $data['ward']=$this->ward_model->getWardList($data);
            // print_var($data['ward_list']);die;
            return view("water/report/WaterSurveyReport",$data);
        }
        catch(Exception $e)
        {
            print_r($e->getMessage());
        }
    }
    
    public function WaterSurveyExport()
    {
        // if ($this->request->getMethod() == 'post') 
        {
            
            ## Read value
            

            $survey = sanitizeString($this->request->getVar('survey')); 
            $consumer_type = sanitizeString($this->request->getVar('consumer_type'));
            $connection_type = sanitizeString($this->request->getVar('connection_type'));
            $ward_id = sanitizeString($this->request->getVar('ward_id'));

            $consumer_type_wher = " AND 1=1 ";           
            if($consumer_type=='Dry Consumer')
            {
                $consumer_type_wher =" AND (tbl_consumer.apply_from ='Existing' AND tbl_consumer.created_on::date>='2021-10-26')"; 
            }
            if($consumer_type=='Main Consumer')
            {
                $consumer_type_wher =" AND supper_Dray.consumer_id ISNULL AND ((tbl_consumer.apply_from ='Existing' AND tbl_consumer.created_on::date>='2021-10-26'))=FALSE"; 
            }
            if($consumer_type=='Supper Dry Consumer')
            {
                $consumer_type_wher =" AND supper_Dray.consumer_id IS NOT NULL AND ((tbl_consumer.apply_from ='Existing' AND tbl_consumer.created_on::date>='2021-10-26'))=FALSE"; 
            }
            if($consumer_type=='Dry Consumer & Main Consumer')
            {
                $consumer_type_wher =" AND ((tbl_consumer.apply_from ='Existing' AND tbl_consumer.created_on::date>='2021-10-26') OR (supper_Dray.consumer_id ISNULL))"; 
            }
            if($consumer_type=='Dry Consumer & Supper Dry Consumer')
            {
                $consumer_type_wher =" AND ((tbl_consumer.apply_from ='Existing' AND tbl_consumer.created_on::date>='2021-10-26') OR (supper_Dray.consumer_id IS NOT NULL))"; 
            }
            if($consumer_type=='Main Consumer & Supper Dry Consumer')
            {
                $consumer_type_wher =" AND (((tbl_consumer.apply_from ='Existing' AND tbl_consumer.created_on::date>='2021-10-26'))=FALSE)"; 
            }
            
            $keyword = sanitizeString($this->request->getVar('keyword'));
            $from_date =sanitizeString($this->request->getVar('from_date'));
            $upto_date =sanitizeString($this->request->getVar('upto_date'));
            $searchQuery = "";
            $whereQuery = "";


            if($from_date && $upto_date && $survey == "survey_done")
            {
                $whereQuery .= " AND  tbl_water_survey.created_on::DATE BETWEEN '".$from_date."' AND '".$upto_date."' "; 
            }
            if ($survey == "survey_done") 
            {
                $whereQuery .= " AND  tbl_water_survey.consumer_id IS NOT NULL ";                    
            } 
            elseif($survey == "survey_no_done") 
            {
                $whereQuery .= " AND  tbl_water_survey.consumer_id IS NULL  ";
                
            }

            if ($keyword != "") 
            {
                $whereQuery .= " AND  (tbl_consumer.consumer_no ~* '$keyword' 
                                        OR owner.owner_name ~* '$keyword' 
                                        OR owner.father_name ~* '$keyword' 
                                        OR owner.mobile_no ~* '$keyword' )";
                
            } 
            if(($ward_id != 'All') && $ward_id)
            {
                $whereQuery .= " AND tbl_consumer.ward_mstr_id = $ward_id ";
            }

            if($connection_type == 1)
            {
                $whereQuery .= " AND meter_status.connection_type in (1,2)";
            }
            if($connection_type == 3)
            {
                $whereQuery .= " AND meter_status.connection_type not in (1,2)";
            }
            // print_var($conection_type);print_var($whereQuery);die;
            $whereQueryWithSearch = "";
            

            
            $selectStatement = "SELECT 				  
                                    tbl_consumer.id,        
                                    view_ward_mstr.ward_no,
                                    tbl_consumer.address,
                                    tbl_consumer.consumer_no, 
                                    CASE WHEN meter_status.connection_type=1 then 'Meter' 
                                        WHEN meter_status.connection_type=2 then'Galen'
                                        WHEN meter_status.connection_type=3 then'Fixed'
                                        ELSE 'xxxx' 
                                        END AS meter_status,
                                    CASE WHEN tbl_consumer.apply_from ='Existing' AND tbl_consumer.created_on::date>='2021-10-26' THEN 'Dry Consumer'
                                            WHEN supper_Dray.consumer_id ISNULL THEN 'Main Consumer'
                                            ELSE  'Supper Dry Consumer'
                                        END AS consumer_type ,
                                        
                                    tbl_consumer.holding_no,
                                    tbl_consumer.saf_no,                                            
                                    owner.owner_name,
                                    owner.mobile_no,
                                    
                                    
                                    CASE WHEN tbl_water_survey.is_holding_map=1 THEN 'YES' ELSE 'NO' END AS holding_map,
                                    (
                                        CASE WHEN tbl_water_survey.holding_no IS NOT NULL THEN tbl_water_survey.holding_no
                                            WHEN tbl_water_survey.prop_id IS NOT NULL THEN (
                                                                                        SELECT new_holding_no 
                                                                                        FROM view_prop_detail
                                                                                        WHERE view_prop_detail.id = tbl_water_survey.prop_id 
                                                                                    )
                                        END
                                    ) AS survey_holding_no,
                                    
                                    (
                                        CASE WHEN tbl_water_survey.saf_no IS NOT NULL THEN tbl_water_survey.saf_no
                                            WHEN tbl_water_survey.saf_id IS NOT NULL THEN (
                                                                                            SELECT saf_no
                                                                                            FROM view_saf_detail
                                                                                            WHERE view_saf_detail.id = tbl_water_survey.saf_id
                                                                                        )
                                        END
                                    )AS survey_saf_no,
                                    
                                    tbl_water_survey.reason_not_map_prop AS survey_reason_not_map,
                                    CASE WHEN tbl_water_survey.meter_connection_type_id = 1 THEN 'Meter' ELSE 'Fixed' END AS survey_meter_connection_type,
                                    tbl_water_survey.meter_no AS survey_meter_no,
                                    tbl_water_survey.is_meter_working AS survey_meter_working,
                                    tbl_water_survey.supply_duration AS survey_supply_duration,
                                    CASE WHEN tbl_water_survey.is_apply_disconneciton =1 THEN 'YES' ELSE 'NO' END AS survey_apply_disconneciton,
                                    
                                    CASE WHEN tbl_water_survey.desconn_document IS NOT NULL
                                        THEN concat('" . base_url() . "/getImageLink.php?path=',tbl_water_survey.desconn_document) 
                                        END AS survey_desconn_document,

                                    CASE WHEN tbl_water_survey.bill_served_status = 1 THEN 'YES' ELSE 'NO' END AS survey_served_status,
                                    tbl_water_survey.last_bill_serve_date AS survey_last_bill_serve_date,
                                    tbl_water_survey.bill_not_serve_reason AS survey_bill_not_serve_reason,
                                    tbl_water_survey.latitude AS latitude,
                                    tbl_water_survey.longitude AS longitude,
                                    CASE WHEN tbl_water_survey.geo_doc IS NOT NULL
                                        THEN concat('" . base_url() . "/getImageLink.php?path=',tbl_water_survey.geo_doc) 
                                        END AS geo_doc,
                                    tbl_water_survey.created_on::DATE AS survey_date
            ";
            // print_var($selectStatement);die;
            $sql =" FROM tbl_consumer
                    JOIN (
                        select distinct(consumer_id) as consumer_id,
                            string_agg( applicant_name , ', ') as owner_name,
                            string_agg( father_name, ', ') as father_name,
                            string_agg( mobile_no::text, ', ') as mobile_no
                        from tbl_consumer_details
                        where status =1 
                        group by consumer_id
                    ) owner on owner.consumer_id = tbl_consumer.id
                    JOIN view_ward_mstr ON view_ward_mstr.id = tbl_consumer.ward_mstr_id
                    LEFT JOIN(
                        SELECT DISTINCT(tbl_consumer.id) AS consumer_id,
                            tbl_transaction.id AS transaction_id
                        FROM tbl_consumer
                        LEFT JOIN tbl_transaction ON tbl_transaction.related_id = tbl_consumer.id 
                            AND tbl_transaction.status IN (1,2)
                            AND tbl_transaction.transaction_type = 'Demand Collection'
                        WHERE tbl_consumer.status=1        
                            AND tbl_transaction.id isnull
                    
                    ) supper_Dray ON supper_Dray.consumer_id = tbl_consumer.id
                    LEFT JOIN (
                        select connection_type,consumer_id,connection_date 
                        from tbl_meter_status 
                        inner join (
                            select max(id) as max_id
                            from tbl_meter_status 
                            where status = 1
                            group by consumer_id
                        ) AS abc ON abc.max_id=tbl_meter_status.id
                        
                    ) meter_status on meter_status.consumer_id=tbl_consumer.id
                    LEFT JOIN tbl_water_survey ON tbl_water_survey.consumer_id = tbl_consumer.id AND tbl_water_survey.status =1
                    LEFT JOIN view_emp_details ON view_emp_details.id = tbl_water_survey.emp_dtl_id
                    WHERE tbl_consumer.status = 1 
            " . $whereQuery ." ". $consumer_type_wher; 
            // print_var($sql);die;
            $fetchSql = $selectStatement.$sql; 
            $result = $this->model_datatable->getRecords($fetchSql,false);
            $records = [];
            if ($result) 
            { 
               
                foreach ($result AS $key=>$tran_dtl) 
                {
                    
                    if($survey=='survey_no_done')
                    {
                        $records[] = [
                            'ward_no'=>$tran_dtl['ward_no'],                        
                            'consumer_no'=>$tran_dtl['consumer_no'],                        
                            'meter_status'=>$tran_dtl['meter_status'],
                            'consumer_type'=>$tran_dtl['consumer_type'],
                            'holding_no'=>$tran_dtl['holding_no'],
                            'saf_no'=>$tran_dtl['saf_no'],
                            'owner_name'=>$tran_dtl['owner_name'],
                            'mobile_no'=>$tran_dtl['mobile_no'],
                            'address'=>$tran_dtl['address'],
                        ];
                    }
                    else
                    {

                        $records[] = [
                            'ward_no'=>$tran_dtl['ward_no'],                        
                            'consumer_no'=>$tran_dtl['consumer_no'],                        
                            'meter_status'=>$tran_dtl['meter_status'],
                            'consumer_type'=>$tran_dtl['consumer_type'],
                            'holding_no'=>$tran_dtl['holding_no'],
                            'saf_no'=>$tran_dtl['saf_no'],
                            'owner_name'=>$tran_dtl['owner_name'],
                            'mobile_no'=>$tran_dtl['mobile_no'],
                            'address'=>$tran_dtl['address'],
    
                            'holding_map'=>$tran_dtl['holding_map'],                          
                            'survey_holding_no'=>$tran_dtl['survey_holding_no'], 
                            'survey_saf_no'=>$tran_dtl['survey_saf_no'], 
                            'survey_reason_not_map'=>$tran_dtl['survey_reason_not_map'], 
                            'survey_meter_connection_type'=>$tran_dtl['survey_meter_connection_type'],
                            'survey_meter_no'=>$tran_dtl['survey_meter_no'],                        
                            'survey_meter_working'=>$tran_dtl['survey_meter_working'],                        
                            'survey_supply_duration'=>$tran_dtl['survey_supply_duration'],
                            'survey_apply_disconneciton'=>$tran_dtl['survey_apply_disconneciton'],
                            'survey_desconn_document'=>$tran_dtl['survey_desconn_document'],
                            'survey_served_status'=>$tran_dtl['survey_served_status'],
                            'survey_last_bill_serve_date'=>$tran_dtl['survey_last_bill_serve_date'],
                            'survey_bill_not_serve_reason'=>$tran_dtl['survey_bill_not_serve_reason'],
                            'latitude'=>$tran_dtl['latitude'],
                            'longitude'=>$tran_dtl['longitude'],                          
                            'geo_doc'=>$tran_dtl['geo_doc'], 
                            'survey_date'=>$tran_dtl['survey_date'], 
                        ];
                    }


                }
            }
            phpOfficeLoad();
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $activeSheet = $spreadsheet->getActiveSheet();
                            $activeSheet->setCellValue('A1', 'Ward No.');
                            $activeSheet->setCellValue('B1', 'Consumer No.');
                            $activeSheet->setCellValue('C1', 'Connection Type');
                            $activeSheet->setCellValue('D1', 'Consumer Type.');
                            $activeSheet->setCellValue('E1', 'Holding No.');
                            $activeSheet->setCellValue('F1', 'Saf No.');      
                            $activeSheet->setCellValue('G1', 'Owner Name.');
                            $activeSheet->setCellValue('H1', 'Mobile No.');
                            $activeSheet->setCellValue('I1', 'Address.');
                            if($survey=='survey_done')
                            {
                                $activeSheet->setCellValue('J1', 'Holding Map');
                                $activeSheet->setCellValue('K1', 'Survey Holding No.');
                                $activeSheet->setCellValue('L1', 'Survey Saf No.');
                                $activeSheet->setCellValue('M1', 'Holding Not Map Remarks');
                                $activeSheet->setCellValue('N1', 'Survey Connection Type');
                                $activeSheet->setCellValue('O1', 'Survey Meter No.');    
                                
                                $activeSheet->setCellValue('P1', 'Survey Meter Funcationl'); 
                                $activeSheet->setCellValue('Q1', 'Water Supply Duration'); 
                                $activeSheet->setCellValue('R1', 'Is Disconnection Apply'); 
                                $activeSheet->setCellValue('S1', 'Disconnection Doc'); 
                                $activeSheet->setCellValue('T1', 'Anny Bill Serve By Tc'); 
                                $activeSheet->setCellValue('U1', 'Last Bill ServeDate'); 

                                $activeSheet->setCellValue('V1', 'Remarks Of Bill Not Surved'); 
                                $activeSheet->setCellValue('W1', 'Latitude'); 
                                $activeSheet->setCellValue('X1', 'Longitude'); 
                                $activeSheet->setCellValue('Y1', 'Survey Img'); 
                                $activeSheet->setCellValue('Z1', 'Survey Date'); 
                                // $activeSheet->setCellValue('AA1', 'Apply From');
                            }

                            $activeSheet->fromArray($records, NULL, 'A3');
            $filename = "WaterSurveyReport".$survey.date('Ymd-hisa').".xlsx";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            //$writer->save(APPPATH.'/hello world.xlsx');
            $writer->save('php://output');
        }
    }

    public function waterConsumerHhAplBplMReport()
    {        
        try{
            $data =(array)null;
            $Session = session();
            $ulb_dtl = $Session->get('ulb_dtl');
            $ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];
            $data["fyList"] = fy_year_list();

            $curentFyear = getFY();        
            list($fromYear,$uptoYear) = explode("-",$curentFyear);            
            $data["fyear"] = $curentFyear;
            $where = "WHERE 1=1 ";
            if ($this->request->getMethod() == 'post') 
            {
                $inputs = sanitizeString($this->request->getVar());
                if($inputs["fyear"] != "")
                {
                    $data["fyear"] = $inputs["fyear"];
                    list($fromYear,$uptoYear) = explode("-",$inputs["fyear"]);
                }
                if($inputs["wardId"] != "")
                {
                    $data["wardId"] = $inputs["wardId"];
                    $where .= " AND view_ward_mstr.id = ".$inputs["wardId"]; 
                }
            }
            $fromDate = $fromYear."-04-01";
            $uptoDate = $uptoYear."-03-31";

            $sql="
            WITH supper_Dray AS ( 
                SELECT DISTINCT(tbl_consumer.id) AS consumer_id, 
                    tbl_transaction.id AS transaction_id 
                FROM tbl_consumer 
                LEFT JOIN tbl_transaction ON tbl_transaction.related_id = tbl_consumer.id 
                    AND tbl_transaction.status IN (1,2) AND tbl_transaction.transaction_type = 'Demand Collection' 
                    AND tbl_transaction.transaction_date <='$uptoDate'
                WHERE tbl_consumer.status=1 AND tbl_transaction.id isnull 
            ),
            transection AS (
                select DISTINCT(tbl_transaction.related_id) AS consumer_id,max(id) as id
                FROM tbl_transaction
                WHERE tbl_transaction.status IN (1,2) AND tbl_transaction.transaction_type = 'Demand Collection' 
                    AND tbl_transaction.transaction_date <='$uptoDate'
                group by tbl_transaction.related_id
            ),
            prop_detail as  (
                SELECT  count(view_prop_detail.id) as total_prop, count(apartment_details_id) as total_appartment,ward_mstr_id
                FROM view_prop_detail
                where prop_type_mstr_id !=4
                    and char_length(new_holding_no)>0
                group by ward_mstr_id
            )
            select ward_no2,
                sum(total_prop) as total_prop,
                sum(total_appartment) as total_appartment,
                sum(total_consumer)as total_consumer, 
                sum(total_holding_consumer) as total_holding_consumer,
                sum(total_apl_consumer)as total_apl_consumer,
                sum(total_bpl_consumer)as total_bpl_consumer,
                sum(total_dry_consumer)as total_dry_consumer,

                sum(total_apl_dry_consumer)as total_apl_dry_consumer,

                sum(total_apl_meter_dry_consumer)as total_apl_meter_dry_consumer,
                sum(total_apl_meter_paid_dry_consumer)as total_apl_meter_paid_dry_consumer,
                sum(total_apl_meter_un_paid_dry_consumer)as total_apl_meter_un_paid_dry_consumer,

                sum(total_apl_non_meter_dry_consumer )as total_apl_non_meter_dry_consumer,
                sum(total_apl_non_meter_paid_dry_consumer)as total_apl_non_meter_paid_dry_consumer,
                sum(total_apl_non_meter_un_paid_dry_consumer)as total_apl_non_meter_un_paid_dry_consumer,

                sum(total_bpl_dry_consumer)as total_bpl_dry_consumer,

                sum(total_bpl_meter_dry_consumer )as total_bpl_meter_dry_consumer,
                sum(total_bpl_meter_paid_dry_consumer )as total_bpl_meter_paid_dry_consumer,
                sum(total_bpl_meter_un_paid_dry_consumer )as total_bpl_meter_un_paid_dry_consumer,

                sum(total_bpl_non_meter_dry_consumer )as total_bpl_non_meter_dry_consumer,
                sum(total_bpl_non_meter_paid_dry_consumer)as total_bpl_non_meter_paid_dry_consumer,
                sum(total_bpl_non_meter_un_paid_dry_consumer)as total_bpl_non_meter_un_paid_dry_consumer,
                sum(balance) As balance,
                sum(balence_apl) as balence_apl,

                sum(balence_apl_meter) as balence_apl_meter,
                sum(balence_apl_paid_meter) as balence_apl_paid_meter,

                sum(balence_apl_unpaid_meter) as balence_apl_unpaid_meter,

                sum(balence_apl_non_meter) as balence_apl_non_meter,
                sum(balence_apl_paid_non_meter) as balence_apl_paid_non_meter,

                sum(balence_apl_unpaid_non_meter) as balence_apl_unpaid_non_meter,

                sum(balence_bpl) as balence_bpl,

                sum(balence_bpl_meter) as balence_bpl_meter,
                sum(balence_bpl_paid_meter) as balence_bpl_paid_meter,

                sum(balence_bpl_unpaid_meter) as balence_bpl_unpaid_meter,

                sum(balence_bpl_non_meter) as balence_bpl_non_meter,
                sum(balence_bpl_paid_non_meter) as balence_bpl_paid_non_meter,

                sum(balence_bpl_unpaid_non_meter) as balence_bpl_unpaid_non_meter
            from (
                select water.*,
                    regexp_replace(water.ward_no ,'/.*', '') as ward_no2,
                    coalesce(prop_detail.total_prop,0) as total_prop,
                    coalesce(prop_detail.total_appartment,0) as total_appartment
                from (
                
                    select 
                        view_ward_mstr.id,view_ward_mstr.ward_no,
                        count(case when consumer_Type !='Supper Dry Consumer' then consumer_id ELSE null END)as total_consumer, 
                        count(holding_consumer) as total_holding_consumer,
                        count(case when consumer_Type !='Supper Dry Consumer'  AND category ='APL' then consumer_id else null end)as total_apl_consumer,
                        count(case when consumer_Type !='Supper Dry Consumer'  AND category ='BPL' then consumer_id else null end)as total_bpl_consumer,
                        count(case when consumer_Type='Dry Consumer' then consumer_id ELSE null END )as total_dry_consumer,

                        count(case when consumer_Type='Dry Consumer' AND category='APL' then consumer_id ELSE null END )as total_apl_dry_consumer,

                        count(case when consumer_Type='Dry Consumer' AND category='APL' AND connection_type =1 then consumer_id ELSE null END )as total_apl_meter_dry_consumer,
                        count(case when consumer_Type='Dry Consumer' AND category='APL' AND connection_type =1 AND transection_id is not null then consumer_id ELSE null END )as total_apl_meter_paid_dry_consumer,
                        count(case when consumer_Type='Dry Consumer' AND category='APL' AND connection_type =1 AND transection_id is null then consumer_id ELSE null END )as total_apl_meter_un_paid_dry_consumer,

                        count(case when consumer_Type='Dry Consumer' AND category='APL' AND connection_type =3 then consumer_id ELSE null END )as total_apl_non_meter_dry_consumer,
                        count(case when consumer_Type='Dry Consumer' AND category='APL' AND connection_type =3 AND transection_id is not null then consumer_id ELSE null END )as total_apl_non_meter_paid_dry_consumer,
                        count(case when consumer_Type='Dry Consumer' AND category='APL' AND connection_type =3 AND transection_id is null then consumer_id ELSE null END )as total_apl_non_meter_un_paid_dry_consumer,

                        count(case when consumer_Type='Dry Consumer' AND category='BPL' then consumer_id ELSE null END )as total_bpl_dry_consumer,

                        count(case when consumer_Type='Dry Consumer' AND category='BPL' AND connection_type =1 then consumer_id ELSE null END )as total_bpl_meter_dry_consumer,
                        count(case when consumer_Type='Dry Consumer' AND category='BPL' AND connection_type =1 AND transection_id is not null then consumer_id ELSE null END )as total_bpl_meter_paid_dry_consumer,
                        count(case when consumer_Type='Dry Consumer' AND category='BPL' AND connection_type =1 AND transection_id is null then consumer_id ELSE null END )as total_bpl_meter_un_paid_dry_consumer,

                        count(case when consumer_Type='Dry Consumer' AND category='BPL' AND connection_type =3 then consumer_id ELSE null END )as total_bpl_non_meter_dry_consumer,
                        count(case when consumer_Type='Dry Consumer' AND category='BPL' AND connection_type =3 AND transection_id is not null then consumer_id ELSE null END )as total_bpl_non_meter_paid_dry_consumer,
                        count(case when consumer_Type='Dry Consumer' AND category='BPL' AND connection_type =3 AND transection_id is null then consumer_id ELSE null END )as total_bpl_non_meter_un_paid_dry_consumer,

                        (count(case when consumer_Type !='Supper Dry Consumer' then consumer_id ELSE null END) - count(case when consumer_Type='Dry Consumer' then consumer_id ELSE null END )) As balance,
                        (
                            count(case when consumer_Type !='Supper Dry Consumer'  AND category ='APL' then consumer_id else null end) 
                            - count(case when consumer_Type='Dry Consumer' AND category='APL' then consumer_id ELSE null END )
                        ) as balence_apl,

                        (
                            count(case when consumer_Type !='Supper Dry Consumer'  AND category ='APL' AND connection_type =1 then consumer_id else null end)          
                            - count(case when consumer_Type='Dry Consumer' AND category='APL' AND connection_type =1 then consumer_id ELSE null END )

                        ) as balence_apl_meter,
                        (
                            count(case when consumer_Type !='Supper Dry Consumer'  AND category ='APL' AND connection_type =1 AND transection_id is not null then consumer_id else null end)          
                            - count(case when consumer_Type='Dry Consumer' AND category='APL' AND connection_type =1 AND transection_id is not null then consumer_id ELSE null END )

                        ) as balence_apl_paid_meter,

                        (
                            count(case when consumer_Type !='Supper Dry Consumer'  AND category ='APL' AND connection_type =1 AND transection_id is null then consumer_id else null end)          
                            - count(case when consumer_Type='Dry Consumer' AND category='APL' AND connection_type =1 AND transection_id is null then consumer_id ELSE null END )

                        ) as balence_apl_unpaid_meter,

                        (
                            count(case when consumer_Type !='Supper Dry Consumer'  AND category ='APL' AND connection_type =3 then consumer_id else null end) 
                            - count(case when consumer_Type='Dry Consumer' AND category='APL' AND connection_type =3 then consumer_id ELSE null END )

                        ) as balence_apl_non_meter,
                        (
                            count(case when consumer_Type !='Supper Dry Consumer'  AND category ='APL' AND connection_type =3 AND transection_id is not null then consumer_id else null end)          
                            - count(case when consumer_Type='Dry Consumer' AND category='APL' AND connection_type =3 AND transection_id is not null then consumer_id ELSE null END )

                        ) as balence_apl_paid_non_meter,

                        (
                            count(case when consumer_Type !='Supper Dry Consumer'  AND category ='APL' AND connection_type =3 AND transection_id is null then consumer_id else null end)          
                            - count(case when consumer_Type='Dry Consumer' AND category='APL' AND connection_type =3 AND transection_id is null then consumer_id ELSE null END )

                        ) as balence_apl_unpaid_non_meter,

                        (
                            count(case when consumer_Type !='Supper Dry Consumer'  AND category ='BPL' then consumer_id else null end) 
                            - count(case when consumer_Type='Dry Consumer' AND category='BPL' then consumer_id ELSE null END )
                        ) as balence_bpl,

                        (
                            count(case when consumer_Type !='Supper Dry Consumer'  AND category ='BPL' AND connection_type =1 then consumer_id else null end)          
                            - count(case when consumer_Type='Dry Consumer' AND category='BPL' AND connection_type =1 then consumer_id ELSE null END )

                        ) as balence_bpl_meter,
                        (
                            count(case when consumer_Type !='Supper Dry Consumer'  AND category ='BPL' AND connection_type =1 AND transection_id is not null then consumer_id else null end)          
                            - count(case when consumer_Type='Dry Consumer' AND category='BPL' AND connection_type =1 AND transection_id is not null then consumer_id ELSE null END )

                        ) as balence_bpl_paid_meter,

                        (
                            count(case when consumer_Type !='Supper Dry Consumer'  AND category ='BPL' AND connection_type =1 AND transection_id is null then consumer_id else null end)          
                            - count(case when consumer_Type='Dry Consumer' AND category='BPL' AND connection_type =1 AND transection_id is null then consumer_id ELSE null END )

                        ) as balence_bpl_unpaid_meter,

                        (
                            count(case when consumer_Type !='Supper Dry Consumer'  AND category ='BPL' AND connection_type =3 then consumer_id else null end) 
                            - count(case when consumer_Type='Dry Consumer' AND category='BPL' AND connection_type =3 then consumer_id ELSE null END )

                        ) as balence_bpl_non_meter,
                        (
                            count(case when consumer_Type !='Supper Dry Consumer'  AND category ='BPL' AND connection_type =3 AND transection_id is not null then consumer_id else null end)          
                            - count(case when consumer_Type='Dry Consumer' AND category='BPL' AND connection_type =3 AND transection_id is not null then consumer_id ELSE null END )

                        ) as balence_bpl_paid_non_meter,

                        (
                            count(case when consumer_Type !='Supper Dry Consumer'  AND category ='BPL' AND connection_type =3 AND transection_id is null then consumer_id else null end)          
                            - count(case when consumer_Type='Dry Consumer' AND category='BPL' AND connection_type =3 AND transection_id is null then consumer_id ELSE null END )

                        ) as balence_bpl_unpaid_non_meter
                    from 
                    view_ward_mstr
                    left join(
                        select tbl_consumer.id as consumer_id, ward_mstr_id,property_type_id,
                            CASE
                                WHEN ((trim(tbl_consumer.category)::text = 'APL'::text) OR (trim(tbl_consumer.category) ='')) THEN 'APL'
                                ELSE trim(tbl_consumer.category)::text
                            END AS category,
                            CASE
                                WHEN (((tbl_consumer.holding_no)::text = ''::text) OR (tbl_consumer.holding_no IS NULL)) THEN tbl_consumer.id
                                ELSE NULL::bigint
                            END AS non_holding_consumer,
                    
                            CASE
                                WHEN (((tbl_consumer.holding_no)::text <> ''::text) AND (tbl_consumer.holding_no IS NOT NULL)) THEN tbl_consumer.id
                                ELSE NULL::bigint
                            END AS holding_consumer ,
                        
                            CASE WHEN meter_status.connection_type in(1,2) then 1 else 3 end as connection_type,
                        
                            CASE WHEN transection.consumer_id is Not null  then transection.id else Null end as transection_id,
                        
                            CASE WHEN tbl_consumer.apply_from ='Existing' AND tbl_consumer.created_on::date>='2021-10-26' THEN 'Dry Consumer'
                                        WHEN supper_Dray.consumer_id ISNULL THEN 'Main Consumer'
                                        ELSE  'Supper Dry Consumer'
                                    END AS consumer_Type
                        from tbl_consumer
                        join tbl_property_type_mstr on tbl_property_type_mstr.id = tbl_consumer.property_type_id
                        left join(
                            select distinct(consumer_id) as consumer_id,connection_type
                            from tbl_meter_status
                            where id in (SELECT MAX(id) 
                                        FROM tbl_meter_status 
                                        WHERE status =1 and connection_date <='$uptoDate'
                                        GROUP BY consumer_id 
                                        )
                        )meter_status  on meter_status.consumer_id = tbl_consumer.id
                        LEFT JOIN supper_Dray ON supper_Dray.consumer_id = tbl_consumer.id
                        LEFT JOIN transection ON transection.consumer_id = tbl_consumer.id
                        where tbl_consumer.status = 1 and tbl_consumer.created_on::date <='$uptoDate'
                        
                    )consumer on consumer.ward_mstr_id = view_ward_mstr.id
                    $where
                    group by view_ward_mstr.id,view_ward_mstr.ward_no
                )water
                left join prop_detail on prop_detail.ward_mstr_id = water.id
                order by (substring(ward_no, '^[0-9]+'))::int,ward_no
            )finals
            group by ward_no2
            order by (substring(ward_no2, '^[0-9]+'))::int,ward_no2";
            // PRINT_VAR($sql);die;
            $data["result"] = $this->db->query($sql)->getResultArray();            
            
            return view("water/report/waterConsumerHhAplBplMReport",$data);
        }
        catch(Exception $e)
        {
            print_r($e->getMessage());
        }
    }

    public function MPLReport()
    {
        try{
            $data =(array)null;
            $data["fyList"] = fy_year_list();
            $curentFyear = getFY();        
            list($fromYear,$uptoYear) = explode("-",$curentFyear);            
            $data["fyear"] = $curentFyear;            
            if ($this->request->getMethod() == 'post') 
            {
                $inputs = arrFilterSanitizeString($this->request->getVar());
                // print_var($inputs);
                if($inputs["fyear"] != "")
                {
                    $data["fyear"] = $inputs["fyear"];
                    list($fromYear,$uptoYear) = explode("-",$inputs["fyear"]);
                }
            }
            $data["demands"]=array();
            $fromDate = $fromYear."-04-01";
            $uptoDate = $uptoYear."-03-31";
            // print_var($fromDate);

            while($fromDate<=$uptoDate)
            { 
                $lastDate = date("Y-m-t", strtotime($fromDate ));
                $fixedlastDate    = date("Y-m-t", strtotime($fromDate . ' -1 month'));;
                $fixedfromDate    = date("Y-m", strtotime($fixedlastDate ))."-01";
                
                $sql = "
                with  supper_Dray as (
                    SELECT DISTINCT(tbl_consumer.id) AS consumer_id,
                        tbl_transaction.id AS transaction_id
                    FROM tbl_consumer
                    LEFT JOIN tbl_transaction ON tbl_transaction.related_id = tbl_consumer.id 
                        AND tbl_transaction.status IN (1,2)
                        AND tbl_transaction.transaction_type = 'Demand Collection'
                    WHERE tbl_consumer.status=1        
                        AND tbl_transaction.id isnull
                
                ) ,
                consumers as (
                    select tbl_consumer.* ,
                    CASE WHEN tbl_consumer.apply_from ='Existing' AND tbl_consumer.created_on::date>='2021-10-26' THEN 'Dry Consumer'
                        WHEN supper_Dray.consumer_id ISNULL THEN 'Main Consumer'
                        ELSE  'Supper Dry Consumer'
                    END AS consumer_Type
                    from tbl_consumer
                    LEFT JOIN supper_Dray ON supper_Dray.consumer_id = tbl_consumer.id
                    where tbl_consumer.status =1 
                    and tbl_consumer.created_on<='$lastDate'
                ),
                demand as ( 
                    SELECT tbl_consumer_demand.consumer_id,
                    count(case when connection_type in('Meter','Meterd') 
                                AND tbl_consumer_demand.demand_upto BETWEEN '$fromDate'::date 
                                AND '$lastDate'::date 
                                then tbl_consumer_demand.id 
                            else null end) as total_meter_demand , 
                    count(case when connection_type='Fixed' 
                            AND tbl_consumer_demand.demand_upto BETWEEN '$fixedfromDate'::date 
                            AND '$fixedlastDate'::date  
                        then tbl_consumer_demand.id else null end) as total_fixed_demand ,
                        sum(
                            CASE WHEN tbl_consumer_demand.demand_upto < '$fromDate'::date 
                                THEN tbl_consumer_demand.amount       
                            ELSE NULL::numeric
                            END
                        ) AS arrear_demand,
                        sum(
                            CASE WHEN tbl_consumer_demand.demand_upto >= '$fromDate'::date 
                                AND tbl_consumer_demand.demand_upto <= '$lastDate'::date 
                                THEN tbl_consumer_demand.amount
                                ELSE NULL::numeric
                            END
                        ) AS curr_demand
                    FROM tbl_consumer_demand
                    join consumers on consumers.id = tbl_consumer_demand.consumer_id
                    WHERE tbl_consumer_demand.status = 1
                        AND tbl_consumer_demand.generation_date <= '$lastDate'
                    GROUP BY tbl_consumer_demand.consumer_id
                ),
                coll as ( 
                    SELECT tbl_consumer_collection.consumer_id,
                        sum(
                            CASE WHEN tbl_consumer_demand.demand_upto < '$fromDate'::date 
                                THEN tbl_consumer_collection.amount
                                ELSE NULL::numeric
                            END
                        ) AS arrear_coll,
                        sum(
                            CASE WHEN tbl_consumer_demand.demand_upto >= '$fromDate'::date 
                                AND tbl_consumer_demand.demand_upto <= '$lastDate'::date 
                                THEN tbl_consumer_collection.amount
                                ELSE NULL::numeric
                            END
                        ) AS curr_coll
                    FROM tbl_consumer_collection
                    JOIN tbl_consumer_demand ON tbl_consumer_demand.id = tbl_consumer_collection.demand_id
                    join consumers on consumers.id = tbl_consumer_demand.consumer_id
                    JOIN tbl_transaction ON tbl_transaction.id = tbl_consumer_collection.transaction_id
                    WHERE tbl_transaction.transaction_date >= '$fromDate'::date 
                        AND tbl_transaction.transaction_date <= '$lastDate'::date
                        AND tbl_transaction.status in(1,2)
                        AND tbl_consumer_demand.status = 1
                    GROUP BY tbl_consumer_collection.consumer_id
                ),
                prev_coll_amount as ( 
                        SELECT tbl_transaction.related_id,
                            sum(tbl_consumer_collection.amount) AS prev_coll
                        FROM tbl_consumer_collection
                        JOIN tbl_consumer_demand ON tbl_consumer_demand.id = tbl_consumer_collection.demand_id
                        join consumers on consumers.id = tbl_consumer_demand.consumer_id
                        JOIN tbl_transaction ON tbl_transaction.id = tbl_consumer_collection.transaction_id
                        WHERE tbl_transaction.transaction_date < '$fromDate'::date 
                            AND tbl_transaction.transaction_type::text = 'Demand Collection'::text
                            AND tbl_transaction.status in(1,2)
                            AND tbl_consumer_demand.status = 1
                        GROUP BY tbl_transaction.related_id
                ),
                meter_status as (
                    select max(tbl_meter_status.id) as max_id
                    from tbl_meter_status
                    join consumers on consumers.id = tbl_meter_status.consumer_id
                    where tbl_meter_status.status =1
                        and tbl_meter_status.connection_date<='$lastDate'
                    group by consumer_id
                ),
                connection_type as (
                    select tbl_meter_status.connection_date,
                        consumers.id as consumer_id,tbl_meter_status.id,
                        case when connection_type in(1,2) then 1 else 3 end as connection_type
                    from consumers
                    left join 
                    (   tbl_meter_status 
                        join meter_status on meter_status.max_id = tbl_meter_status.id
                     )tbl_meter_status on tbl_meter_status.consumer_id = consumers.id
                )
                select 
                    count(case when connection_type=1 then consumers.id else null end) as total_meter_consumer , 
                    count(case when connection_type=3 then consumers.id else null end) as total_fixed_consumer ,
                    count(consumers.id) as total_consumer, 
                    /*sum(COALESCE(
                        COALESCE(demand.arrear_demand, 0::numeric) 
                        - COALESCE(prev_coll_amount.prev_coll, 0::numeric), 0::numeric
                    )) AS outstanding_at_begin,*/
                
                    --sum(COALESCE(prev_coll_amount.prev_coll, 0::numeric)) AS prev_coll,
                    sum(COALESCE(demand.curr_demand, 0::numeric)) AS current_demand,
                    sum(COALESCE(demand.total_meter_demand, 0::numeric)) AS total_meter_demand,
                    sum(COALESCE(demand.total_fixed_demand, 0::numeric)) AS total_fixed_demand,
                    sum(COALESCE(demand.total_fixed_demand, 0::numeric) + COALESCE(demand.total_meter_demand, 0::numeric)) AS total_demand_served,
                    sum(COALESCE(coll.arrear_coll, 0::numeric)) AS arrear_coll,
                    sum(COALESCE(coll.curr_coll, 0::numeric)) AS curr_coll,
                    sum(COALESCE(coll.curr_coll, 0::numeric) + COALESCE(coll.arrear_coll, 0::numeric)) as total_collection,
                
                    /*sum(COALESCE(
                            COALESCE(demand.arrear_demand, 0::numeric) 
                            - COALESCE(prev_coll_amount.prev_coll, 0::numeric), 0::numeric
                        ) 
                        - COALESCE(coll.arrear_coll, 0::numeric) )AS old_due,*/
                
                    --sum(COALESCE(demand.curr_demand, 0::numeric) - COALESCE(coll.curr_coll, 0::numeric)) AS curr_due,
                
                    /*sum(
                        COALESCE(
                            COALESCE(demand.curr_demand, 0::numeric) 
                            + (
                                COALESCE(demand.arrear_demand, 0::numeric) 
                                - COALESCE(prev_coll_amount.prev_coll, 0::numeric)
                            ), 0::numeric
                        ) 
                        - COALESCE(
                            COALESCE(coll.curr_coll, 0::numeric) 
                            + COALESCE(coll.arrear_coll, 0::numeric), 0::numeric
                        )
                    ) AS outstanding ,*/                   
                    '$fromDate/$lastDate' as Date
                from consumers
                join connection_type on connection_type.consumer_id = consumers.id
                LEFT JOIN demand ON demand.consumer_id = consumers.id
                LEFT JOIN coll ON coll.consumer_id = consumers.id
                --LEFT JOIN prev_coll_amount ON prev_coll_amount.related_id = consumers.id
                WHERE consumers.consumer_Type IN('Main Consumer')
                ";
                $data["demands"][] = $this->db->query($sql)->getFirstRow("array");
                $fromDate = date("Y-m-d", strtotime(date("Y-m-t", strtotime($fromDate )) . ' +1 day'));
                if($fromDate > date("Y-m-d"))
                {
                    break;
                }
            }
            // print_var($data["demands"]);
            return view("water/report/MPLReport",$data);
        }
        catch(Exception $e)
        {
            print_r($e->getMessage());
            print_var($e->getFile());
            print_var($e->getLine());
        }
    }

    public function waterPhysicalStatus(){
        $data = $this->request->getVar();
        $data['ulb_mstr_id']=$this->ulb_id;
        $data['wardList']=$this->ward_model->getWardList($data);
        $fromDate = $uptoDate = $ward_id = null;
        if($this->request->getVar("fromDate") && $this->request->getVar("uptoDate")){
            $fromDate = $this->request->getVar("fromDate");
            $uptoDate = $this->request->getVar("uptoDate");
        }
        if($this->request->getVar("ward_id")){
            $ward_id = $this->request->getVar("ward_id");
        }
        if($this->request->getMethod()=="post"){
            $sql="
                with supper_Dray AS (
                    SELECT distinct(tbl_consumer.id) as consumer_id,
                        tbl_transaction.id as transaction_id
                    FROM tbl_consumer
                    LEFT JOIN tbl_transaction on tbl_transaction.related_id = tbl_consumer.id 
                        and tbl_transaction.status in (1,2)
                        and tbl_transaction.transaction_type = 'Demand Collection'
                    WHERE tbl_consumer.status=1        
                        AND tbl_transaction.id isnull
    
                ),
                consumer_type AS(
                    SELECT  tbl_consumer.*,
                        CASE WHEN tbl_consumer.apply_from ='Existing' AND tbl_consumer.created_on::date>='2021-10-26' THEN 'Dry Consumer'
                                WHEN supper_Dray.consumer_id ISNULL THEN 'Main Consumer'
                                ELSE  'Supper Dry Consumer'
                            END AS consumer_type
                    FROM tbl_consumer
                    LEFT JOIN supper_Dray ON supper_Dray.consumer_id = tbl_consumer.id   	
                ),
                consumer as(	
                    select ward_mstr_id,
                        count(id) as total_consumer,
                        count(case when consumer_type='Supper Dry Consumer' then id else null end)as legecy_consumer,
                        count(case when consumer_type!='Supper Dry Consumer' then id else null end)as total_hh
                    from consumer_type
                    where 1=1 
                        AND juidco_consumer!='Juidco'
                        ".($fromDate && $uptoDate ? " AND created_on::date between '$fromDate' and '$uptoDate' " : "")." 
                    group by ward_mstr_id
                ),
                juidco_consumer as (
                    select ward_mstr_id,
                        count(id) as judco_consumer
                    FROM tbl_consumer
                    where juidco_consumer='Juidco'
                        ".($fromDate && $uptoDate ? " AND created_on::date between '$fromDate' and '$uptoDate' " : "")." 
                    group by ward_mstr_id	
                ),
                deactivated_consumer as (
                    select ward_mstr_id, count(distinct(consumer_id)) as deactivated_consumer
                    from tbl_consumer_deactivation
                    where 1=1 
                        ".($fromDate && $uptoDate ? " AND deactivation_date::date between '$fromDate' and '$uptoDate' " : "")." 
                    group by ward_mstr_id
                ),
                last_meter as (
                        select consumer_id,connection_type,
                            ROW_NUMBER() OVER(PARTITION BY consumer_id ORDER BY id DESC) as row_num
                        from tbl_meter_status
                        where status = 1 
                            ".($fromDate && $uptoDate ? " AND connection_date::date <='$uptoDate' " : "")." 
                ), 
                meter_no_meter as(
                    select tbl_consumer.ward_mstr_id, count(distinct(case when last_meter.connection_type in (1,2) then tbl_consumer.id end))as meter_connection,
                        count(distinct(case when last_meter.connection_type not in (1,2) then tbl_consumer.id end))as non_meter_connection
                    from tbl_consumer
                    left join last_meter on last_meter.consumer_id=tbl_consumer.id and row_num=1
                    where tbl_consumer.status =1
                        ".($fromDate && $uptoDate ? " AND tbl_consumer.created_on::date <= '$uptoDate' " : "")."
                    group by tbl_consumer.ward_mstr_id
                ),
                demand_served as (
                    select tbl_consumer.ward_mstr_id, count(distinct(case when last_meter.connection_type in (1,2) then tbl_consumer.id end))as meter_connection_demand,
                        count(distinct(case when last_meter.connection_type not in (1,2) then tbl_consumer.id end))as non_meter_connection_demand 
                    from tbl_consumer_demand
                    join tbl_consumer on tbl_consumer.id = tbl_consumer_demand.consumer_id
                    left join last_meter on last_meter.consumer_id=tbl_consumer.id and row_num=1
                    where 1=1 
                        ".($fromDate && $uptoDate ? " AND tbl_consumer_demand.generation_date::date between '$fromDate' and '$uptoDate' " : "")." 
                    group by tbl_consumer.ward_mstr_id
                ),
                last_remarks as (
                    select apply_connection_id,forward_date,
                        ROW_NUMBER() OVER(PARTITION BY apply_connection_id ORDER BY id DESC) as row_num
                    from tbl_level_pending
                    where status = 1
                    
                ),
                approved_application as (
                    select tbl_apply_water_connection.ward_id,count(distinct(tbl_apply_water_connection.id)) as total_approved 
                    from tbl_apply_water_connection
                    join last_remarks on last_remarks.apply_connection_id = tbl_apply_water_connection.id
                    where last_remarks.row_num=1 and tbl_apply_water_connection.status=5 
                        ".($fromDate && $uptoDate ? " AND forward_date::date between '$fromDate' and '$uptoDate' " : "")." 
                    group by tbl_apply_water_connection.ward_id
                    
                )
                select view_ward_mstr.id,view_ward_mstr.ward_no,
                    COALESCE(approved_application.total_approved,0)as total_approved,
                    COALESCE(consumer.total_consumer,0) as total_consumer, COALESCE(consumer.legecy_consumer,0)legacy_consumer, COALESCE(consumer.total_hh,0)total_hh,
                    COALESCE(juidco_consumer.judco_consumer,0)judco_consumer, 
                    COALESCE(deactivated_consumer.deactivated_consumer,0)deactivated_consumer, 
                    COALESCE(meter_no_meter.meter_connection,0)meter_connection, COALESCE(meter_no_meter.non_meter_connection,0)non_meter_connection,
                    COALESCE(demand_served.meter_connection_demand,0)meter_connection_demand, COALESCE(demand_served.non_meter_connection_demand,0)non_meter_connection_demand
                    
                from view_ward_mstr
                left join approved_application on approved_application.ward_id =view_ward_mstr.id
                left join consumer on consumer.ward_mstr_id =view_ward_mstr.id
                left join juidco_consumer on juidco_consumer.ward_mstr_id =view_ward_mstr.id
                left join deactivated_consumer on deactivated_consumer.ward_mstr_id =view_ward_mstr.id
                left join meter_no_meter on meter_no_meter.ward_mstr_id =view_ward_mstr.id
                left join demand_served on demand_served.ward_mstr_id =view_ward_mstr.id
                where 1=1 
                    ".($ward_id ? " AND view_ward_mstr.id = $ward_id " :"")."
    
            ";
            $data["result"] = $this->db->query($sql)->getResultArray();
            foreach($data["result"][0] as $key=> $val){               
                $data["total"][0][$key] = $key=="ward_no" ? sizeof($data["result"]) : array_sum(array_column($data["result"], $key));
    
            }
        }
        return view("water/report/waterPhysicalStatus",$data);
    }

    public function sumaryDCB(){
        $data = $this->request->getVar();
        $data['ulb_mstr_id']=$this->ulb_id;
        $data['ward_list']=$this->ward_model->getWardList($data);
        if($this->request->getMethod()=='post'){
            $fy = explode('-',getFY()); 
            if($this->request->getVar('fin_year'))
            {
                $fy = explode('-',$this->request->getVar('fin_year'));
            }        
            $from =$fy[0].'-03-31'; 
            $to = $fy[1].'-03-31';
            $whereClause="where 1=1 AND tbl_consumer.status = 1 ";
            if($this->request->getVar("ward_id")!="")
            {
                $whereClause .= " AND tbl_consumer.ward_mstr_id=".$this->request->getVar("ward_id");
            }
            $sql = "
                with supper_Dray AS (
                    SELECT distinct(tbl_consumer.id) as consumer_id,
                        tbl_transaction.id as transaction_id
                    FROM tbl_consumer
                    LEFT JOIN tbl_transaction on tbl_transaction.related_id = tbl_consumer.id 
                        and tbl_transaction.status in (1,2)
                        and tbl_transaction.transaction_type = 'Demand Collection'
                    WHERE tbl_consumer.status=1        
                        AND tbl_transaction.id isnull

                ), 
                owner as (
                        SELECT tbl_consumer_details.consumer_id,
                            string_agg(tbl_consumer_details.applicant_name::text, ','::text) AS applicant_name
                        FROM tbl_consumer_details
                        join tbl_consumer on  tbl_consumer.id = tbl_consumer_details.consumer_id
                        where tbl_consumer.status =1 and tbl_consumer_details.status=1
                        GROUP BY tbl_consumer_details.consumer_id
                ),
                demand as ( 
                    SELECT tbl_consumer_demand.consumer_id,
                        sum(
                            CASE WHEN tbl_consumer_demand.demand_upto <= '$from'::date 
                                THEN tbl_consumer_demand.amount       
                            ELSE NULL::numeric
                            END
                        ) AS arrear_demand,
                        sum(
                            CASE WHEN tbl_consumer_demand.demand_upto > '$from'::date 
                                AND tbl_consumer_demand.demand_upto <= '$to'::date 
                                THEN tbl_consumer_demand.amount
                                ELSE NULL::numeric
                            END
                        ) AS curr_demand
                    FROM tbl_consumer_demand
                    WHERE tbl_consumer_demand.status = 1
                    GROUP BY tbl_consumer_demand.consumer_id
                ),
                coll as ( 
                    SELECT tbl_consumer_collection.consumer_id,
                        sum(
                            CASE WHEN tbl_consumer_demand.demand_upto <= '$from'::date 
                                THEN tbl_consumer_collection.amount
                                ELSE NULL::numeric
                            END
                        ) AS arrear_coll,
                        sum(
                            CASE WHEN tbl_consumer_demand.demand_upto > '$from'::date 
                                AND tbl_consumer_demand.demand_upto <= '$to'::date 
                                THEN tbl_consumer_collection.amount
                                ELSE NULL::numeric
                            END
                        ) AS curr_coll
                    FROM tbl_consumer_collection
                    JOIN tbl_consumer_demand ON tbl_consumer_demand.id = tbl_consumer_collection.demand_id
                    JOIN tbl_transaction ON tbl_transaction.id = tbl_consumer_collection.transaction_id
                    WHERE tbl_transaction.transaction_date > '$from'::date 
                        AND tbl_transaction.transaction_date <= '$to'::date
                        AND tbl_transaction.status in(1,2)
                    GROUP BY tbl_consumer_collection.consumer_id
                ),
                prev_coll_amount as ( 
                        SELECT tbl_transaction.related_id,
                            sum(tbl_consumer_collection.amount) AS prev_coll
                        FROM tbl_consumer_collection
                        JOIN tbl_consumer_demand ON tbl_consumer_demand.id = tbl_consumer_collection.demand_id
                        JOIN tbl_transaction ON tbl_transaction.id = tbl_consumer_collection.transaction_id
                        WHERE tbl_transaction.transaction_date <= '$from'::date 
                            AND tbl_transaction.transaction_type::text = 'Demand Collection'::text
                            AND tbl_transaction.status in(1,2)
                        GROUP BY tbl_transaction.related_id
                ),
                meter_type as ( 
                    SELECT tbl_meter_status.id,
                    tbl_meter_status.consumer_id,
                    tbl_meter_status.connection_type
                    FROM tbl_meter_status
                    WHERE (
                            tbl_meter_status.id IN ( 
                                                SELECT max(tbl_meter_status_1.id) AS id
                                                FROM tbl_meter_status tbl_meter_status_1
                                                where status = 1
                                                GROUP BY tbl_meter_status_1.consumer_id
                                                ORDER BY (max(tbl_meter_status_1.id))
                                                )
                        )                        
                ),
                all_advance as(
                    select tbl_consumer.id, sum(tbl_advance_mstr.amount) as amount 
                        from tbl_advance_mstr
                        JOIN tbl_transaction ON tbl_transaction.id = tbl_advance_mstr.transaction_id
                        join tbl_consumer on tbl_consumer.id=tbl_advance_mstr.related_id
                        where tbl_advance_mstr.status=1 AND tbl_transaction.transaction_type='Demand Collection' 
                            AND tbl_transaction.status in (1, 2) 
                            AND (tbl_transaction.transaction_date <= '$to'::date) 
                            AND tbl_advance_mstr.module='consumer' 
                        group by tbl_consumer.id
                ),
                all_adjustment as (
                    select tbl_consumer.id, sum(tbl_adjustment_mstr.amount) as amount 
                        from tbl_adjustment_mstr
                        JOIN tbl_transaction ON tbl_transaction.id = tbl_adjustment_mstr.transaction_id
                        join tbl_consumer on tbl_consumer.id=tbl_adjustment_mstr.related_id
                        where tbl_adjustment_mstr.status=1 AND tbl_transaction.transaction_type='Demand Collection' 
                            AND tbl_transaction.status in (1, 2) 
                            AND (tbl_transaction.transaction_date <= '$to'::date) 
                            AND tbl_adjustment_mstr.module='consumer' 
                        group by tbl_consumer.id
                ),
                consumer_dcb as(
                    SELECT ROW_NUMBER () OVER (ORDER BY tbl_consumer.id ) AS s_no,
                        tbl_consumer.id,
                        tbl_consumer.consumer_no,
                        tbl_consumer.property_type_id,
                        tbl_property_type_mstr.property_type,
                        tbl_consumer.ward_mstr_id,
                        tbl_consumer.category,
                        case when meter_type.connection_type in(1,2) then 'Meter' else 'Fixed' end as connection_type,
                        owner.applicant_name,
                        
                        COALESCE(
                            COALESCE(demand.arrear_demand, 0::numeric) 
                            - COALESCE(prev_coll_amount.prev_coll, 0::numeric), 0::numeric
                        ) AS outstanding_at_begin,
                        
                        COALESCE(prev_coll_amount.prev_coll, 0::numeric) AS prev_coll,
                        COALESCE(demand.curr_demand, 0::numeric) AS current_demand,
                        COALESCE(coll.arrear_coll, 0::numeric) AS arrear_coll,
                        COALESCE(coll.curr_coll, 0::numeric) AS curr_coll,
                        
                        (COALESCE(
                                COALESCE(demand.arrear_demand, 0::numeric) 
                                - COALESCE(prev_coll_amount.prev_coll, 0::numeric), 0::numeric
                            ) 
                            - COALESCE(coll.arrear_coll, 0::numeric) )AS old_due,
                            
                        (COALESCE(demand.curr_demand, 0::numeric) - COALESCE(coll.curr_coll, 0::numeric)) AS curr_due,
                        
                        (
                            COALESCE(
                                COALESCE(demand.curr_demand, 0::numeric) 
                                + (
                                    COALESCE(demand.arrear_demand, 0::numeric) 
                                    - COALESCE(prev_coll_amount.prev_coll, 0::numeric)
                                ), 0::numeric
                            ) 
                            - COALESCE(
                                COALESCE(coll.curr_coll, 0::numeric) 
                                + COALESCE(coll.arrear_coll, 0::numeric), 0::numeric
                            )
                        ) AS outstanding ,
                        ((coalesce(all_advance.amount,0))) as advance_amount,
                        CASE WHEN tbl_consumer.apply_from ='Existing' AND tbl_consumer.created_on::date>='2021-10-26' THEN 'Dry Consumer'
                            WHEN supper_Dray.consumer_id ISNULL THEN 'Main Consumer'
                            ELSE  'Supper Dry Consumer'
                        END AS consumer_Type
                    FROM tbl_consumer
                    left join supper_Dray ON supper_Dray.consumer_id = tbl_consumer.id 
                    LEFT JOIN owner ON owner.consumer_id = tbl_consumer.id
                    LEFT JOIN tbl_property_type_mstr ON tbl_property_type_mstr.id = tbl_consumer.property_type_id
                    LEFT JOIN demand ON demand.consumer_id = tbl_consumer.id
                    LEFT JOIN coll ON coll.consumer_id = tbl_consumer.id
                    LEFT JOIN prev_coll_amount ON prev_coll_amount.related_id = tbl_consumer.id
                    LEFT JOIN  meter_type ON meter_type.consumer_id = tbl_consumer.id  
                    LEFT JOIN  all_advance on all_advance.id = tbl_consumer.id
                    LEFT JOIN  all_adjustment on all_adjustment.id = tbl_consumer.id 
                                    
                    $whereClause
                )
                select count(case when property_type_id in(1,9) then id end) as resident_consumer,		
                    count(case when property_type_id in(1,9) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then id end) as meter_collectable_resident_consumer,
                    count(case when property_type_id in(1,9) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then id end) as meter_not_collectable_resident_consumer,	
                    count(case when property_type_id in(1,9) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then id end) as fixed_collectable_resident_consumer,
                    count(case when property_type_id in(1,9) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then id end) as fixed_not_collectable_resident_consumer,
                    
                        sum(case when property_type_id in(1,9) then outstanding_at_begin end) as resident_outstanding_at_begin,			
                        sum(case when property_type_id in(1,9) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then outstanding_at_begin end) as meter_collectable_resident_outstanding_at_begin,
                        sum(case when property_type_id in(1,9) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then outstanding_at_begin end) as meter_not_collectable_resident_outstanding_at_begin,	
                        sum(case when property_type_id in(1,9) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then outstanding_at_begin end) as fixed_collectable_resident_outstanding_at_begin,
                        sum(case when property_type_id in(1,9) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then outstanding_at_begin end) as fixed_not_collectable_resident_outstanding_at_begin,
                    
                        sum(case when property_type_id in(1,9) then prev_coll end) AS resident_prev_coll,					
                        sum(case when property_type_id in(1,9) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then prev_coll end) as meter_collectable_resident_prev_coll,
                        sum(case when property_type_id in(1,9) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then prev_coll end) as meter_not_collectable_resident_prev_coll,	
                        sum(case when property_type_id in(1,9) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then prev_coll end) as fixed_collectable_resident_prev_coll,
                        sum(case when property_type_id in(1,9) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then prev_coll end) as fixed_not_collectable_resident_prev_coll,
                    
                        sum(case when property_type_id in(1,9) then current_demand end) AS resident_current_demand,		
                        sum(case when property_type_id in(1,9) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then current_demand end) as meter_collectable_resident_current_demand,
                        sum(case when property_type_id in(1,9) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then current_demand end) as meter_not_collectable_resident_current_demand,	
                        sum(case when property_type_id in(1,9) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then current_demand end) as fixed_collectable_resident_current_demand,
                        sum(case when property_type_id in(1,9) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then current_demand end) as fixed_not_collectable_resident_current_demand,
                    
                        sum(case when property_type_id in(1,9) then arrear_coll end) AS resident_arrear_coll,
                        sum(case when property_type_id in(1,9) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then arrear_coll end) as meter_collectable_resident_arrear_coll,
                        sum(case when property_type_id in(1,9) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then arrear_coll end) as meter_not_collectable_resident_arrear_coll,	
                        sum(case when property_type_id in(1,9) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then arrear_coll end) as fixed_collectable_resident_arrear_coll,
                        sum(case when property_type_id in(1,9) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then arrear_coll end) as fixed_not_collectable_resident_arrear_coll,
                    
                        sum(case when property_type_id in(1,9) then curr_coll end) AS resident_curr_coll,
                        sum(case when property_type_id in(1,9) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then curr_coll end) as meter_collectable_resident_curr_coll,
                        sum(case when property_type_id in(1,9) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then curr_coll end) as meter_not_collectable_resident_curr_coll,	
                        sum(case when property_type_id in(1,9) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then curr_coll end) as fixed_collectable_resident_curr_coll,
                        sum(case when property_type_id in(1,9) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then curr_coll end) as fixed_not_collectable_resident_curr_coll,	
                    
                        sum(case when property_type_id in(1,9) then old_due end)AS resident_old_due,
                        sum(case when property_type_id in(1,9) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then old_due end) as meter_collectable_resident_old_due,
                        sum(case when property_type_id in(1,9) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then old_due end) as meter_not_collectable_resident_old_due,	
                        sum(case when property_type_id in(1,9) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then old_due end) as fixed_collectable_resident_old_due,
                        sum(case when property_type_id in(1,9) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then old_due end) as fixed_not_collectable_resident_old_due,
                    
                        sum(case when property_type_id in(1,9) then curr_due end) AS resident_curr_due,
                        sum(case when property_type_id in(1,9) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then curr_due end) as meter_collectable_resident_curr_due,
                        sum(case when property_type_id in(1,9) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then curr_due end) as meter_not_collectable_resident_curr_due,	
                        sum(case when property_type_id in(1,9) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then curr_due end) as fixed_collectable_resident_curr_due,
                        sum(case when property_type_id in(1,9) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then curr_due end) as fixed_not_collectable_resident_curr_due,
                    
                        sum(case when property_type_id in(1,9) then outstanding end) AS resident_outstanding ,
                        sum(case when property_type_id in(1,9) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then outstanding end) as meter_collectable_resident_outstanding,
                        sum(case when property_type_id in(1,9) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then outstanding end) as meter_not_collectable_resident_outstanding,	
                        sum(case when property_type_id in(1,9) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then outstanding end) as fixed_collectable_resident_outstanding,
                        sum(case when property_type_id in(1,9) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then outstanding end) as fixed_not_collectable_resident_outstanding,
                    
                        sum(case when property_type_id in(1,9) then advance_amount end) as resident_advance_amount,	
                        sum(case when property_type_id in(1,9) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then advance_amount end) as meter_collectable_resident_advance_amount,
                        sum(case when property_type_id in(1,9) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then advance_amount end) as meter_not_collectable_resident_advance_amount,	
                        sum(case when property_type_id in(1,9) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then advance_amount end) as fixed_collectable_resident_advance_amount,
                        sum(case when property_type_id in(1,9) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then advance_amount end) as fixed_not_collectable_resident_advance_amount,
                    
                    count(case when property_type_id in(2,10) then id end) as comercial_consumer,
                    count(case when property_type_id in(2,10) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then id end) as meter_collectable_comercial_consumer,
                    count(case when property_type_id in(2,10) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then id end) as meter_not_collectable_comercial_consumer,	
                    count(case when property_type_id in(2,10) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then id end) as fixed_collectable_comercial_consumer,
                    count(case when property_type_id in(2,10) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then id end) as fixed_not_collectable_comercial_consumer,
                    
                        sum(case when property_type_id in(2,10) then outstanding_at_begin end) as comercial_outstanding_at_begin,
                        sum(case when property_type_id in(2,10) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then outstanding_at_begin end) as meter_collectable_comercial_outstanding_at_begin,
                        sum(case when property_type_id in(2,10) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then outstanding_at_begin end) as meter_not_collectable_comercial_outstanding_at_begin,	
                        sum(case when property_type_id in(2,10) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then outstanding_at_begin end) as fixed_collectable_comercial_outstanding_at_begin,
                        sum(case when property_type_id in(2,10) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then outstanding_at_begin end) as fixed_not_collectable_comercial_outstanding_at_begin,
                    
                        sum(case when property_type_id in(2,10) then prev_coll end) AS comercial_prev_coll,
                        sum(case when property_type_id in(2,10) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then prev_coll end) as meter_collectable_comercial_prev_coll,
                        sum(case when property_type_id in(2,10) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then prev_coll end) as meter_not_collectable_comercial_prev_coll,	
                        sum(case when property_type_id in(2,10) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then prev_coll end) as fixed_collectable_comercial_prev_coll,
                        sum(case when property_type_id in(2,10) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then prev_coll end) as fixed_not_collectable_comercial_prev_coll,
                    
                        sum(case when property_type_id in(2,10) then current_demand end) AS comercial_current_demand,
                        sum(case when property_type_id in(2,10) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then current_demand end) as meter_collectable_comercial_current_demand,
                        sum(case when property_type_id in(2,10) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then current_demand end) as meter_not_collectable_comercial_current_demand,	
                        sum(case when property_type_id in(2,10) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then current_demand end) as fixed_collectable_comercial_current_demand,
                        sum(case when property_type_id in(2,10) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then current_demand end) as fixed_not_collectable_comercial_current_demand,

                        sum(case when property_type_id in(2,10) then arrear_coll end) AS comercial_arrear_coll,
                        sum(case when property_type_id in(2,10) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then arrear_coll end) as meter_collectable_comercial_arrear_coll,
                        sum(case when property_type_id in(2,10) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then arrear_coll end) as meter_not_collectable_comercial_arrear_coll,	
                        sum(case when property_type_id in(2,10) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then arrear_coll end) as fixed_collectable_comercial_arrear_coll,
                        sum(case when property_type_id in(2,10) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then arrear_coll end) as fixed_not_collectable_comercial_arrear_coll,

                        sum(case when property_type_id in(2,10) then curr_coll end) AS comercial_curr_coll,	
                        sum(case when property_type_id in(2,10) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then curr_coll end) as meter_collectable_comercial_curr_coll,
                        sum(case when property_type_id in(2,10) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then curr_coll end) as meter_not_collectable_comercial_curr_coll,	
                        sum(case when property_type_id in(2,10) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then curr_coll end) as fixed_collectable_comercial_curr_coll,
                        sum(case when property_type_id in(2,10) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then curr_coll end) as fixed_not_collectable_comercial_curr_coll,

                        sum(case when property_type_id in(2,10) then old_due end)AS comercial_old_due,	
                        sum(case when property_type_id in(2,10) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then old_due end) as meter_collectable_comercial_old_due,
                        sum(case when property_type_id in(2,10) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then old_due end) as meter_not_collectable_comercial_old_due,	
                        sum(case when property_type_id in(2,10) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then old_due end) as fixed_collectable_comercial_old_due,
                        sum(case when property_type_id in(2,10) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then old_due end) as fixed_not_collectable_comercial_old_due,

                        sum(case when property_type_id in(2,10) then curr_due end) AS comercial_curr_due,		
                        sum(case when property_type_id in(2,10) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then curr_due end) as meter_collectable_comercial_curr_due,
                        sum(case when property_type_id in(2,10) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then curr_due end) as meter_not_collectable_comercial_curr_due,	
                        sum(case when property_type_id in(2,10) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then curr_due end) as fixed_collectable_comercial_curr_due,
                        sum(case when property_type_id in(2,10) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then curr_due end) as fixed_not_collectable_comercial_curr_due,

                        sum(case when property_type_id in(2,10) then outstanding end) AS comercial_outstanding ,	
                        sum(case when property_type_id in(2,10) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then outstanding end) as meter_collectable_comercial_outstanding,
                        sum(case when property_type_id in(2,10) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then outstanding end) as meter_not_collectable_comercial_outstanding,	
                        sum(case when property_type_id in(2,10) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then outstanding end) as fixed_collectable_comercial_outstanding,
                        sum(case when property_type_id in(2,10) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then outstanding end) as fixed_not_collectable_comercial_outstanding,

                        sum(case when property_type_id in(2,10) then advance_amount end) as comercial_advance_amount,
                        sum(case when property_type_id in(2,10) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then advance_amount end) as meter_collectable_comercial_advance_amount,
                        sum(case when property_type_id in(2,10) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then advance_amount end) as meter_not_collectable_comercial_advance_amount,	
                        sum(case when property_type_id in(2,10) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then advance_amount end) as fixed_collectable_comercial_advance_amount,
                        sum(case when property_type_id in(2,10) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then advance_amount end) as fixed_not_collectable_comercial_advance_amount,
                    
                    count(case when property_type_id in(3,11) then id end) as gove_consumer,
                    count(case when property_type_id in(3,11) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then id end) as meter_collectable_gove_consumer,
                    count(case when property_type_id in(3,11) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then id end) as meter_not_collectable_gove_consumer,	
                    count(case when property_type_id in(3,11) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then id end) as fixed_collectable_gove_consumer,
                    count(case when property_type_id in(3,11) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then id end) as fixed_not_collectable_gove_consumer,

                        sum(case when property_type_id in(3,11) then outstanding_at_begin end) as gove_outstanding_at_begin,
                        sum(case when property_type_id in(3,11) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then outstanding_at_begin end) as meter_collectable_gove_outstanding_at_begin,
                        sum(case when property_type_id in(3,11) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then outstanding_at_begin end) as meter_not_collectable_gove_outstanding_at_begin,	
                        sum(case when property_type_id in(3,11) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then outstanding_at_begin end) as fixed_collectable_gove_outstanding_at_begin,
                        sum(case when property_type_id in(3,11) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then outstanding_at_begin end) as fixed_not_collectable_gove_outstanding_at_begin,

                        sum(case when property_type_id in(3,11) then prev_coll end) AS gove_prev_coll,
                        sum(case when property_type_id in(3,11) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then prev_coll end) as meter_collectable_gove_prev_coll,
                        sum(case when property_type_id in(3,11) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then prev_coll end) as meter_not_collectable_gove_prev_coll,	
                        sum(case when property_type_id in(3,11) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then prev_coll end) as fixed_collectable_gove_prev_coll,
                        sum(case when property_type_id in(3,11) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then prev_coll end) as fixed_not_collectable_gove_prev_coll,

                        sum(case when property_type_id in(3,11) then current_demand end) AS gove_current_demand,
                        sum(case when property_type_id in(3,11) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then current_demand end) as meter_collectable_gove_current_demand,
                        sum(case when property_type_id in(3,11) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then current_demand end) as meter_not_collectable_gove_current_demand,	
                        sum(case when property_type_id in(3,11) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then current_demand end) as fixed_collectable_gove_current_demand,
                        sum(case when property_type_id in(3,11) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then current_demand end) as fixed_not_collectable_gove_current_demand,

                        sum(case when property_type_id in(3,11) then arrear_coll end) AS gove_arrear_coll,
                        sum(case when property_type_id in(3,11) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then arrear_coll end) as meter_collectable_gove_arrear_coll,
                        sum(case when property_type_id in(3,11) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then arrear_coll end) as meter_not_collectable_gove_arrear_coll,	
                        sum(case when property_type_id in(3,11) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then arrear_coll end) as fixed_collectable_gove_arrear_coll,
                        sum(case when property_type_id in(3,11) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then arrear_coll end) as fixed_not_collectable_gove_arrear_coll,

                        sum(case when property_type_id in(3,11) then curr_coll end) AS gove_curr_coll,	
                        sum(case when property_type_id in(3,11) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then curr_coll end) as meter_collectable_gove_curr_coll,
                        sum(case when property_type_id in(3,11) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then curr_coll end) as meter_not_collectable_gove_curr_coll,	
                        sum(case when property_type_id in(3,11) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then curr_coll end) as fixed_collectable_gove_curr_coll,
                        sum(case when property_type_id in(3,11) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then curr_coll end) as fixed_not_collectable_gove_curr_coll,

                        sum(case when property_type_id in(3,11) then old_due end)AS gove_old_due,
                        sum(case when property_type_id in(3,11) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then old_due end) as meter_collectable_gove_old_due,
                        sum(case when property_type_id in(3,11) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then old_due end) as meter_not_collectable_gove_old_due,	
                        sum(case when property_type_id in(3,11) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then old_due end) as fixed_collectable_gove_old_due,
                        sum(case when property_type_id in(3,11) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then old_due end) as fixed_not_collectable_gove_old_due,

                        sum(case when property_type_id in(3,11) then curr_due end) AS gove_curr_due,
                        sum(case when property_type_id in(3,11) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then curr_due end) as meter_collectable_gove_curr_due,
                        sum(case when property_type_id in(3,11) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then curr_due end) as meter_not_collectable_gove_curr_due,	
                        sum(case when property_type_id in(3,11) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then curr_due end) as fixed_collectable_gove_curr_due,
                        sum(case when property_type_id in(3,11) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then curr_due end) as fixed_not_collectable_gove_curr_due,

                        sum(case when property_type_id in(3,11) then outstanding end) AS gove_outstanding ,
                        sum(case when property_type_id in(3,11) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then outstanding end) as meter_collectable_gove_outstanding,
                        sum(case when property_type_id in(3,11) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then outstanding end) as meter_not_collectable_gove_outstanding,	
                        sum(case when property_type_id in(3,11) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then outstanding end) as fixed_collectable_gove_outstanding,
                        sum(case when property_type_id in(3,11) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then outstanding end) as fixed_not_collectable_gove_outstanding,

                        sum(case when property_type_id in(3,11) then advance_amount end) as gove_advance_amount,
                        sum(case when property_type_id in(3,11) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then advance_amount end) as meter_collectable_gove_advance_amount,
                        sum(case when property_type_id in(3,11) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then advance_amount end) as meter_not_collectable_gove_advance_amount,	
                        sum(case when property_type_id in(3,11) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then advance_amount end) as fixed_collectable_gove_advance_amount,
                        sum(case when property_type_id in(3,11) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then advance_amount end) as fixed_not_collectable_gove_advance_amount,
                    
                    count(case when property_type_id in(4,13) then id end) as instit_consumer,
                    count(case when property_type_id in(4,13) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then id end) as meter_collectable_instit_consumer,
                    count(case when property_type_id in(4,13) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then id end) as meter_not_collectable_instit_consumer,	
                    count(case when property_type_id in(4,13) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then id end) as fixed_collectable_instit_consumer,
                    count(case when property_type_id in(4,13) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then id end) as fixed_not_collectable_instit_consumer,

                        sum(case when property_type_id in(4,13) then outstanding_at_begin end) as instit_outstanding_at_begin,	
                        sum(case when property_type_id in(4,13) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then outstanding_at_begin end) as meter_collectable_instit_outstanding_at_begin,
                        sum(case when property_type_id in(4,13) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then outstanding_at_begin end) as meter_not_collectable_instit_outstanding_at_begin,	
                        sum(case when property_type_id in(4,13) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then outstanding_at_begin end) as fixed_collectable_instit_outstanding_at_begin,
                        sum(case when property_type_id in(4,13) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then outstanding_at_begin end) as fixed_not_collectable_instit_outstanding_at_begin,	

                        sum(case when property_type_id in(4,13) then prev_coll end) AS instit_prev_coll,
                        sum(case when property_type_id in(4,13) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then prev_coll end) as meter_collectable_instit_prev_coll,
                        sum(case when property_type_id in(4,13) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then prev_coll end) as meter_not_collectable_instit_prev_coll,	
                        sum(case when property_type_id in(4,13) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then prev_coll end) as fixed_collectable_instit_prev_coll,
                        sum(case when property_type_id in(4,13) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then prev_coll end) as fixed_not_collectable_instit_prev_coll,	

                        sum(case when property_type_id in(4,13) then current_demand end) AS instit_current_demand,
                        sum(case when property_type_id in(4,13) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then current_demand end) as meter_collectable_instit_current_demand,
                        sum(case when property_type_id in(4,13) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then current_demand end) as meter_not_collectable_instit_current_demand,	
                        sum(case when property_type_id in(4,13) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then current_demand end) as fixed_collectable_instit_current_demand,
                        sum(case when property_type_id in(4,13) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then current_demand end) as fixed_not_collectable_instit_current_demand,

                        sum(case when property_type_id in(4,13) then arrear_coll end) AS instit_arrear_coll,
                        sum(case when property_type_id in(4,13) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then arrear_coll end) as meter_collectable_instit_arrear_coll,
                        sum(case when property_type_id in(4,13) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then arrear_coll end) as meter_not_collectable_instit_arrear_coll,	
                        sum(case when property_type_id in(4,13) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then arrear_coll end) as fixed_collectable_instit_arrear_coll,
                        sum(case when property_type_id in(4,13) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then arrear_coll end) as fixed_not_collectable_instit_arrear_coll,

                        sum(case when property_type_id in(4,13) then curr_coll end) AS instit_curr_coll,
                        sum(case when property_type_id in(4,13) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then curr_coll end) as meter_collectable_instit_curr_coll,
                        sum(case when property_type_id in(4,13) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then curr_coll end) as meter_not_collectable_instit_curr_coll,	
                        sum(case when property_type_id in(4,13) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then curr_coll end) as fixed_collectable_instit_curr_coll,
                        sum(case when property_type_id in(4,13) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then curr_coll end) as fixed_not_collectable_instit_curr_coll,

                        sum(case when property_type_id in(4,13) then old_due end)AS instit_old_due,
                        sum(case when property_type_id in(4,13) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then old_due end) as meter_collectable_instit_old_due,
                        sum(case when property_type_id in(4,13) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then old_due end) as meter_not_collectable_instit_old_due,	
                        sum(case when property_type_id in(4,13) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then old_due end) as fixed_collectable_instit_old_due,
                        sum(case when property_type_id in(4,13) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then old_due end) as fixed_not_collectable_instit_old_due,

                        sum(case when property_type_id in(4,13) then curr_due end) AS instit_curr_due,
                        sum(case when property_type_id in(4,13) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then curr_due end) as meter_collectable_instit_curr_due,
                        sum(case when property_type_id in(4,13) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then curr_due end) as meter_not_collectable_instit_curr_due,	
                        sum(case when property_type_id in(4,13) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then curr_due end) as fixed_collectable_instit_curr_due,
                        sum(case when property_type_id in(4,13) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then curr_due end) as fixed_not_collectable_instit_curr_due,	

                        sum(case when property_type_id in(4,13) then outstanding end) AS instit_outstanding ,
                        sum(case when property_type_id in(4,13) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then outstanding end) as meter_collectable_instit_outstanding,
                        sum(case when property_type_id in(4,13) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then outstanding end) as meter_not_collectable_instit_outstanding,	
                        sum(case when property_type_id in(4,13) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then outstanding end) as fixed_collectable_instit_outstanding,
                        sum(case when property_type_id in(4,13) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then outstanding end) as fixed_not_collectable_instit_outstanding,	

                        sum(case when property_type_id in(4,13) then advance_amount end) as instit_advance_amount,
                        sum(case when property_type_id in(4,13) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then advance_amount end) as meter_collectable_instit_advance_amount,
                        sum(case when property_type_id in(4,13) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then advance_amount end) as meter_not_collectable_instit_advance_amount,	
                        sum(case when property_type_id in(4,13) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then advance_amount end) as fixed_collectable_instit_advance_amount,
                        sum(case when property_type_id in(4,13) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then advance_amount end) as fixed_not_collectable_instit_advance_amount,	
                    
                    count(case when property_type_id in(5) then id end) as si_unit_consumer,
                    count(case when property_type_id  in(5) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then id end) as meter_collectable_si_unit_consumer,
                    count(case when property_type_id  in(5) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then id end) as meter_not_collectable_si_unit_consumer,	
                    count(case when property_type_id  in(5) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then id end) as fixed_collectable_si_unit_consumer,
                    count(case when property_type_id  in(5) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then id end) as fixed_not_collectable_si_unit_consumer,	

                        sum(case when property_type_id in(5) then outstanding_at_begin end) as si_unit_outstanding_at_begin,
                        sum(case when property_type_id  in(5) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then outstanding_at_begin end) as meter_collectable_si_unit_outstanding_at_begin,
                        sum(case when property_type_id  in(5) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then outstanding_at_begin end) as meter_not_collectable_si_unit_outstanding_at_begin,	
                        sum(case when property_type_id  in(5) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then outstanding_at_begin end) as fixed_collectable_si_unit_outstanding_at_begin,
                        sum(case when property_type_id  in(5) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then outstanding_at_begin end) as fixed_not_collectable_si_unit_outstanding_at_begin,
                                
                        sum(case when property_type_id in(5) then prev_coll end) AS si_unit_prev_coll,
                        sum(case when property_type_id  in(5) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then prev_coll end) as meter_collectable_si_unit_prev_coll,
                        sum(case when property_type_id  in(5) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then prev_coll end) as meter_not_collectable_si_unit_prev_coll,	
                        sum(case when property_type_id  in(5) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then prev_coll end) as fixed_collectable_si_unit_prev_coll,
                        sum(case when property_type_id  in(5) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then prev_coll end) as fixed_not_collectable_si_unit_prev_coll,

                        sum(case when property_type_id in(5) then current_demand end) AS si_unit_current_demand,
                        sum(case when property_type_id  in(5) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then current_demand end) as meter_collectable_si_unit_current_demand,
                        sum(case when property_type_id  in(5) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then current_demand end) as meter_not_collectable_si_unit_current_demand,	
                        sum(case when property_type_id  in(5) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then current_demand end) as fixed_collectable_si_unit_current_demand,
                        sum(case when property_type_id  in(5) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then current_demand end) as fixed_not_collectable_si_unit_current_demand,

                        sum(case when property_type_id in(5) then arrear_coll end) AS si_unit_arrear_coll,
                        sum(case when property_type_id  in(5) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then arrear_coll end) as meter_collectable_si_unit_arrear_coll,
                        sum(case when property_type_id  in(5) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then arrear_coll end) as meter_not_collectable_si_unit_arrear_coll,	
                        sum(case when property_type_id  in(5) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then arrear_coll end) as fixed_collectable_si_unit_arrear_coll,
                        sum(case when property_type_id  in(5) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then arrear_coll end) as fixed_not_collectable_si_unit_arrear_coll,

                        sum(case when property_type_id in(5) then curr_coll end) AS si_unit_curr_coll,	
                        sum(case when property_type_id  in(5) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then curr_coll end) as meter_collectable_si_unit_curr_coll,
                        sum(case when property_type_id  in(5) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then curr_coll end) as meter_not_collectable_si_unit_curr_coll,	
                        sum(case when property_type_id  in(5) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then curr_coll end) as fixed_collectable_si_unit_curr_coll,
                        sum(case when property_type_id  in(5) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then curr_coll end) as fixed_not_collectable_si_unit_curr_coll,

                        sum(case when property_type_id in(5) then old_due end)AS si_unit_old_due,	
                        sum(case when property_type_id  in(5) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then old_due end) as meter_collectable_si_unit_old_due,
                        sum(case when property_type_id  in(5) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then old_due end) as meter_not_collectable_si_unit_old_due,	
                        sum(case when property_type_id  in(5) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then old_due end) as fixed_collectable_si_unit_old_due,
                        sum(case when property_type_id  in(5) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then old_due end) as fixed_not_collectable_si_unit_old_due,

                        sum(case when property_type_id in(5) then curr_due end) AS si_unit_curr_due,
                        sum(case when property_type_id  in(5) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then curr_due end) as meter_collectable_si_unit_curr_due,
                        sum(case when property_type_id  in(5) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then curr_due end) as meter_not_collectable_si_unit_curr_due,	
                        sum(case when property_type_id  in(5) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then curr_due end) as fixed_collectable_si_unit_curr_due,
                        sum(case when property_type_id  in(5) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then curr_due end) as fixed_not_collectable_si_unit_curr_due,

                        sum(case when property_type_id in(5) then outstanding end) AS si_unit_outstanding ,
                        sum(case when property_type_id  in(5) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then outstanding end) as meter_collectable_si_unit_outstanding,
                        sum(case when property_type_id  in(5) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then outstanding end) as meter_not_collectable_si_unit_outstanding,	
                        sum(case when property_type_id  in(5) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then outstanding end) as fixed_collectable_si_unit_outstanding,
                        sum(case when property_type_id  in(5) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then outstanding end) as fixed_not_collectable_si_unit_outstanding,

                        sum(case when property_type_id in(5) then advance_amount end) as si_unit_advance_amount,
                        sum(case when property_type_id  in(5) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then advance_amount end) as meter_collectable_si_unit_advance_amount,
                        sum(case when property_type_id  in(5) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then advance_amount end) as meter_not_collectable_si_unit_advance_amount,	
                        sum(case when property_type_id  in(5) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then advance_amount end) as fixed_collectable_si_unit_advance_amount,
                        sum(case when property_type_id  in(5) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then advance_amount end) as fixed_not_collectable_si_unit_advance_amount,
                            
                    count(case when property_type_id in(6,14) then id end) as indust_consumer,
                    count(case when property_type_id  in(6,14) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then id end) as meter_collectable_indust_consumer,
                    count(case when property_type_id  in(6,14) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then id end) as meter_not_collectable_indust_consumer,	
                    count(case when property_type_id  in(6,14) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then id end) as fixed_collectable_indust_consumer,
                    count(case when property_type_id  in(6,14) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then id end) as fixed_not_collectable_indust_consumer,

                        sum(case when property_type_id in(6,14) then outstanding_at_begin end) as indust_outstanding_at_begin,	
                        sum(case when property_type_id  in(6,14) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then outstanding_at_begin end) as meter_collectable_indust_outstanding_at_begin,
                        sum(case when property_type_id  in(6,14) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then outstanding_at_begin end) as meter_not_collectable_indust_outstanding_at_begin,	
                        sum(case when property_type_id  in(6,14) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then outstanding_at_begin end) as fixed_collectable_indust_outstanding_at_begin,
                        sum(case when property_type_id  in(6,14) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then outstanding_at_begin end) as fixed_not_collectable_indust_outstanding_at_begin,

                        sum(case when property_type_id in(6,14) then prev_coll end) AS indust_prev_coll,
                        sum(case when property_type_id  in(6,14) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then prev_coll end) as meter_collectable_indust_prev_coll,
                        sum(case when property_type_id  in(6,14) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then prev_coll end) as meter_not_collectable_indust_prev_coll,	
                        sum(case when property_type_id  in(6,14) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then prev_coll end) as fixed_collectable_indust_prev_coll,
                        sum(case when property_type_id  in(6,14) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then prev_coll end) as fixed_not_collectable_indust_prev_coll,

                        sum(case when property_type_id in(6,14) then current_demand end) AS indust_current_demand,
                        sum(case when property_type_id  in(6,14) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then current_demand end) as meter_collectable_indust_current_demand,
                        sum(case when property_type_id  in(6,14) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then current_demand end) as meter_not_collectable_indust_current_demand,	
                        sum(case when property_type_id  in(6,14) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then current_demand end) as fixed_collectable_indust_current_demand,
                        sum(case when property_type_id  in(6,14) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then current_demand end) as fixed_not_collectable_indust_current_demand,

                        sum(case when property_type_id in(6,14) then arrear_coll end) AS indust_arrear_coll,
                        sum(case when property_type_id  in(6,14) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then arrear_coll end) as meter_collectable_indust_arrear_coll,
                        sum(case when property_type_id  in(6,14) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then arrear_coll end) as meter_not_collectable_indust_arrear_coll,	
                        sum(case when property_type_id  in(6,14) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then arrear_coll end) as fixed_collectable_indust_arrear_coll,
                        sum(case when property_type_id  in(6,14) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then arrear_coll end) as fixed_not_collectable_indust_arrear_coll,
                        
                        sum(case when property_type_id in(6,14) then curr_coll end) AS indust_curr_coll,		
                        sum(case when property_type_id  in(6,14) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then curr_coll end) as meter_collectable_indust_curr_coll,
                        sum(case when property_type_id  in(6,14) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then curr_coll end) as meter_not_collectable_indust_curr_coll,	
                        sum(case when property_type_id  in(6,14) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then curr_coll end) as fixed_collectable_indust_curr_coll,
                        sum(case when property_type_id  in(6,14) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then curr_coll end) as fixed_not_collectable_indust_curr_coll,

                        sum(case when property_type_id in(6,14) then old_due end)AS indust_old_due,			
                        sum(case when property_type_id  in(6,14) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then old_due end) as meter_collectable_indust_old_due,
                        sum(case when property_type_id  in(6,14) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then old_due end) as meter_not_collectable_indust_old_due,	
                        sum(case when property_type_id  in(6,14) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then old_due end) as fixed_collectable_indust_old_due,
                        sum(case when property_type_id  in(6,14) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then old_due end) as fixed_not_collectable_indust_old_due,

                        sum(case when property_type_id in(6,14) then curr_due end) AS indust_curr_due,		
                        sum(case when property_type_id  in(6,14) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then curr_due end) as meter_collectable_indust_curr_due,
                        sum(case when property_type_id  in(6,14) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then curr_due end) as meter_not_collectable_indust_curr_due,	
                        sum(case when property_type_id  in(6,14) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then curr_due end) as fixed_collectable_indust_curr_due,
                        sum(case when property_type_id  in(6,14) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then curr_due end) as fixed_not_collectable_indust_curr_due,

                        sum(case when property_type_id in(6,14) then outstanding end) AS indust_outstanding ,
                        sum(case when property_type_id  in(6,14) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then outstanding end) as meter_collectable_indust_outstanding,
                        sum(case when property_type_id  in(6,14) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then outstanding end) as meter_not_collectable_indust_outstanding,	
                        sum(case when property_type_id  in(6,14) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then outstanding end) as fixed_collectable_indust_outstanding,
                        sum(case when property_type_id  in(6,14) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then outstanding end) as fixed_not_collectable_indust_outstanding,

                        sum(case when property_type_id in(6,14) then advance_amount end) as indust_advance_amount,
                        sum(case when property_type_id  in(6,14) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then advance_amount end) as meter_collectable_indust_advance_amount,
                        sum(case when property_type_id  in(6,14) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then advance_amount end) as meter_not_collectable_indust_advance_amount,	
                        sum(case when property_type_id  in(6,14) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then advance_amount end) as fixed_collectable_indust_advance_amount,
                        sum(case when property_type_id  in(6,14) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then advance_amount end) as fixed_not_collectable_indust_advance_amount,
                    
                    count(case when property_type_id in(7,15) then id end) as appartment_consumer,	
                    count(case when property_type_id  in(7,15) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then id end) as meter_collectable_appartment_consumer,
                    count(case when property_type_id  in(7,15) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then id end) as meter_not_collectable_appartment_consumer,	
                    count(case when property_type_id  in(7,15) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then id end) as fixed_collectable_appartment_consumer,
                    count(case when property_type_id  in(7,15) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then id end) as fixed_not_collectable_appartment_consumer,
                    
                        sum(case when property_type_id in(7,15) then outstanding_at_begin end) as appartment_outstanding_at_begin,
                        sum(case when property_type_id  in(7,15) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then outstanding_at_begin end) as meter_collectable_appartment_outstanding_at_begin,
                        sum(case when property_type_id  in(7,15) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then outstanding_at_begin end) as meter_not_collectable_appartment_outstanding_at_begin,	
                        sum(case when property_type_id  in(7,15) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then outstanding_at_begin end) as fixed_collectable_appartment_outstanding_at_begin,
                        sum(case when property_type_id  in(7,15) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then outstanding_at_begin end) as fixed_not_collectable_appartment_outstanding_at_begin,
                                
                        sum(case when property_type_id in(7,15) then prev_coll end) AS appartment_prev_coll,
                        sum(case when property_type_id  in(7,15) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then prev_coll end) as meter_collectable_appartment_prev_coll,
                        sum(case when property_type_id  in(7,15) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then prev_coll end) as meter_not_collectable_appartment_prev_coll,	
                        sum(case when property_type_id  in(7,15) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then prev_coll end) as fixed_collectable_appartment_prev_coll,
                        sum(case when property_type_id  in(7,15) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then prev_coll end) as fixed_not_collectable_appartment_prev_coll,

                        sum(case when property_type_id in(7,15) then current_demand end) AS appartment_current_demand,
                        sum(case when property_type_id  in(7,15) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then current_demand end) as meter_collectable_appartment_current_demand,
                        sum(case when property_type_id  in(7,15) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then current_demand end) as meter_not_collectable_appartment_current_demand,	
                        sum(case when property_type_id  in(7,15) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then current_demand end) as fixed_collectable_appartment_current_demand,
                        sum(case when property_type_id  in(7,15) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then current_demand end) as fixed_not_collectable_appartment_current_demand,

                        sum(case when property_type_id in(7,15) then arrear_coll end) AS appartment_arrear_coll,
                        sum(case when property_type_id  in(7,15) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then arrear_coll end) as meter_collectable_appartment_arrear_coll,
                        sum(case when property_type_id  in(7,15) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then arrear_coll end) as meter_not_collectable_appartment_arrear_coll,	
                        sum(case when property_type_id  in(7,15) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then arrear_coll end) as fixed_collectable_appartment_arrear_coll,
                        sum(case when property_type_id  in(7,15) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then arrear_coll end) as fixed_not_collectable_appartment_arrear_coll,

                        sum(case when property_type_id in(7,15) then curr_coll end) AS appartment_curr_coll,		
                        sum(case when property_type_id  in(7,15) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then curr_coll end) as meter_collectable_appartment_curr_coll,
                        sum(case when property_type_id  in(7,15) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then curr_coll end) as meter_not_collectable_appartment_curr_coll,	
                        sum(case when property_type_id  in(7,15) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then curr_coll end) as fixed_collectable_appartment_curr_coll,
                        sum(case when property_type_id  in(7,15) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then curr_coll end) as fixed_not_collectable_appartment_curr_coll,

                        sum(case when property_type_id in(7,15) then old_due end)AS appartment_old_due,			
                        sum(case when property_type_id  in(7,15) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then old_due end) as meter_collectable_appartment_old_due,
                        sum(case when property_type_id  in(7,15) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then old_due end) as meter_not_collectable_appartment_old_due,	
                        sum(case when property_type_id  in(7,15) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then old_due end) as fixed_collectable_appartment_old_due,
                        sum(case when property_type_id  in(7,15) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then old_due end) as fixed_not_collectable_appartment_old_due,

                        sum(case when property_type_id in(7,15) then curr_due end) AS appartment_curr_due,			
                        sum(case when property_type_id  in(7,15) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then curr_due end) as meter_collectable_appartment_curr_due,
                        sum(case when property_type_id  in(7,15) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then curr_due end) as meter_not_collectable_appartment_curr_due,	
                        sum(case when property_type_id  in(7,15) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then curr_due end) as fixed_collectable_appartment_curr_due,
                        sum(case when property_type_id  in(7,15) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then curr_due end) as fixed_not_collectable_appartment_curr_due,

                        sum(case when property_type_id in(7,15) then outstanding end) AS appartment_outstanding ,
                        sum(case when property_type_id  in(7,15) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then outstanding end) as meter_collectable_appartment_outstanding,
                        sum(case when property_type_id  in(7,15) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then outstanding end) as meter_not_collectable_appartment_outstanding,	
                        sum(case when property_type_id  in(7,15) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then outstanding end) as fixed_collectable_appartment_outstanding,
                        sum(case when property_type_id  in(7,15) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then outstanding end) as fixed_not_collectable_appartment_outstanding,

                        sum(case when property_type_id in(7,15) then advance_amount end) as appartment_advance_amount,
                        sum(case when property_type_id  in(7,15) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then advance_amount end) as meter_collectable_appartment_advance_amount,
                        sum(case when property_type_id  in(7,15) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then advance_amount end) as meter_not_collectable_appartment_advance_amount,	
                        sum(case when property_type_id  in(7,15) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then advance_amount end) as fixed_collectable_appartment_advance_amount,
                        sum(case when property_type_id  in(7,15) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then advance_amount end) as fixed_not_collectable_appartment_advance_amount,
                    
                    count(case when property_type_id in(8) then id end) as trust_consumer,
                    count(case when property_type_id  in(8) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then id end) as meter_collectable_trust_consumer,
                    count(case when property_type_id  in(8) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then id end) as meter_not_collectable_trust_consumer,	
                    count(case when property_type_id  in(8) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then id end) as fixed_collectable_trust_consumer,
                    count(case when property_type_id  in(8) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then id end) as fixed_not_collectable_trust_consumer,

                        sum(case when property_type_id in(8) then outstanding_at_begin end) as trust_outstanding_at_begin,	
                        sum(case when property_type_id  in(8) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then outstanding_at_begin end) as meter_collectable_trust_outstanding_at_begin,
                        sum(case when property_type_id  in(8) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then outstanding_at_begin end) as meter_not_collectable_trust_outstanding_at_begin,	
                        sum(case when property_type_id  in(8) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then outstanding_at_begin end) as fixed_collectable_trust_outstanding_at_begin,
                        sum(case when property_type_id  in(8) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then outstanding_at_begin end) as fixed_not_collectable_trust_outstanding_at_begin,
                            
                        sum(case when property_type_id in(8) then prev_coll end) AS trust_prev_coll,
                        sum(case when property_type_id  in(8) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then prev_coll end) as meter_collectable_trust_prev_coll,
                        sum(case when property_type_id  in(8) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then prev_coll end) as meter_not_collectable_trust_prev_coll,	
                        sum(case when property_type_id  in(8) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then prev_coll end) as fixed_collectable_trust_prev_coll,
                        sum(case when property_type_id  in(8) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then prev_coll end) as fixed_not_collectable_trust_prev_coll,

                        sum(case when property_type_id in(8) then current_demand end) AS trust_current_demand,
                        sum(case when property_type_id  in(8) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then current_demand end) as meter_collectable_trust_current_demand,
                        sum(case when property_type_id  in(8) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then current_demand end) as meter_not_collectable_trust_current_demand,	
                        sum(case when property_type_id  in(8) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then current_demand end) as fixed_collectable_trust_current_demand,
                        sum(case when property_type_id  in(8) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then current_demand end) as fixed_not_collectable_trust_current_demand,

                        sum(case when property_type_id in(8) then arrear_coll end) AS trust_arrear_coll,
                        sum(case when property_type_id  in(8) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then arrear_coll end) as meter_collectable_trust_arrear_coll,
                        sum(case when property_type_id  in(8) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then arrear_coll end) as meter_not_collectable_trust_arrear_coll,	
                        sum(case when property_type_id  in(8) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then arrear_coll end) as fixed_collectable_trust_arrear_coll,
                        sum(case when property_type_id  in(8) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then arrear_coll end) as fixed_not_collectable_trust_arrear_coll,

                        sum(case when property_type_id in(8) then curr_coll end) AS trust_curr_coll,	
                        sum(case when property_type_id  in(8) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then curr_coll end) as meter_collectable_trust_curr_coll,
                        sum(case when property_type_id  in(8) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then curr_coll end) as meter_not_collectable_trust_curr_coll,	
                        sum(case when property_type_id  in(8) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then curr_coll end) as fixed_collectable_trust_curr_coll,
                        sum(case when property_type_id  in(8) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then curr_coll end) as fixed_not_collectable_trust_curr_coll,

                        sum(case when property_type_id in(8) then old_due end)AS trust_old_due,		
                        sum(case when property_type_id  in(8) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then old_due end) as meter_collectable_trust_old_due,
                        sum(case when property_type_id  in(8) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then old_due end) as meter_not_collectable_trust_old_due,	
                        sum(case when property_type_id  in(8) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then old_due end) as fixed_collectable_trust_old_due,
                        sum(case when property_type_id  in(8) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then old_due end) as fixed_not_collectable_trust_old_due,

                        sum(case when property_type_id in(8) then curr_due end) AS trust_curr_due,
                        sum(case when property_type_id  in(8) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then curr_due end) as meter_collectable_trust_curr_due,
                        sum(case when property_type_id  in(8) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then curr_due end) as meter_not_collectable_trust_curr_due,	
                        sum(case when property_type_id  in(8) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then curr_due end) as fixed_collectable_trust_curr_due,
                        sum(case when property_type_id  in(8) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then curr_due end) as fixed_not_collectable_trust_curr_due,

                        sum(case when property_type_id in(8) then outstanding end) AS trust_outstanding ,
                        sum(case when property_type_id  in(8) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then outstanding end) as meter_collectable_trust_outstanding,
                        sum(case when property_type_id  in(8) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then outstanding end) as meter_not_collectable_trust_outstanding,	
                        sum(case when property_type_id  in(8) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then outstanding end) as fixed_collectable_trust_outstanding,
                        sum(case when property_type_id  in(8) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then outstanding end) as fixed_not_collectable_trust_outstanding,

                        sum(case when property_type_id in(8) then advance_amount end) as trust_advance_amount,
                        sum(case when property_type_id  in(8) AND connection_type='Meter' and consumer_Type !='Supper Dry Consumer' then advance_amount end) as meter_collectable_trust_advance_amount,
                        sum(case when property_type_id  in(8) AND connection_type='Meter' and consumer_Type ='Supper Dry Consumer' then advance_amount end) as meter_not_collectable_trust_advance_amount,	
                        sum(case when property_type_id  in(8) AND connection_type !='Meter' and consumer_Type !='Supper Dry Consumer' then advance_amount end) as fixed_collectable_trust_advance_amount,
                        sum(case when property_type_id  in(8) AND connection_type !='Meter' and consumer_Type ='Supper Dry Consumer' then advance_amount end) as fixed_not_collectable_trust_advance_amount

                from consumer_dcb
            ";
            $data["result"] = $this->db->query($sql)->getFirstRow("array");
        }
        
        return view("water/report/sumaryDCB",$data);
    }

    public function disruptedEntry()
    {
        $data = (array) null;
        $data['user_type'] = $this->user_type;

        $data['ulb_mstr_id'] = $this->ulb_id;
        $whereClause = "where 1=1 ";
        $join = NULL;
        $data['team_leader'] = '';
        $tc = [
            'where' => ['user_type_id' => [8, 5, 4]],
            'tbl' => 'view_emp_details',
            'column' => ['id', 'emp_name', "full_emp_name", 'user_type', 'lock_status', 'employee_code']
        ];
        $data['tc'] = $this->WaterMobileModel->getDataNew($tc['where'], $tc['column'], $tc['tbl'], array(), array('emp_name' => 'ASC'));
        $Session = Session();
        $emp_mstr = $Session->get("emp_details");
        $data['emp_details_id'] = $emp_mstr["id"];
        $user_type_mstr_id = $emp_mstr["user_type_mstr_id"];
        $data["user_type_mstr_id"] = $user_type_mstr_id;

        if ($this->request->getMethod() == 'post') {
            $inputs = arrFilterSanitizeString($this->request->getVar());
            $consumer_no = $inputs['consumer_no'];
            $tc_name = $inputs['created_by'];
            $remarks = $inputs['remarks'];

            $sql = "SELECT *FROM tbl_consumer where consumer_no='$consumer_no'";
            $consumer = $this->db->query($sql)->getRow();

            if (empty($consumer)) {
                flashToast("message", "Given Consumer Not valid");
                return redirect()->back()->with("", "");
            } elseif ($consumer->status == 0) {
                flashToast("message", "Given Consumer is not active");
                return redirect()->back()->with("", "");
            }

            $insertData = [
                'consumer_id' => $consumer->id,
                'remarks' => $remarks,
                'tc_id' => $tc_name,
                'created_by' => $this->emp_id
            ];

            $result = $this->db->table('tbl_disrupted_consumer_data')->insert($insertData);

            if ($result) {
                flashToast("message", "Successfully entry in disrupted report");
                return redirect()->back()->with("", "");
            }
        }

        return view("water/report/disrupted/entry", $data);
    }

    public function serviceDistrupedBulkEntry()
    {
        $file = $this->request->getFile('bulk_file');

        if (!$file->isValid()) {
            $_SESSION['msg'] = "Invalid file upload";
            return redirect()->back();
        }

        $ext = $file->getClientExtension();
        if (!in_array($ext, ['csv', 'xls', 'xlsx'])) {
            $_SESSION['msg'] = "Only CSV or Excel files are allowed.";
            return redirect()->back();
        }

        $expectedHeaders = ['sl_no', 'name', 'consumer_no', 'ward_no', 'address', 'remarks', 'tc_id'];

        try {
            $spreadsheet = IOFactory::load($file->getTempName());
            $worksheet = $spreadsheet->getActiveSheet();
            $sheetData = $worksheet->toArray();

            if (empty($sheetData)) {
                $_SESSION['msg'] = "File is empty or corrupted.";
                // log_message('error', 'Uploaded file is empty or corrupted.');
                return redirect()->back();
            }

            $rawHeaders = array_map('trim', $sheetData[0]);
            $dataRows = array_slice($sheetData, 1);

            $missingHeaders = array_diff($expectedHeaders, $rawHeaders);
            if (!empty($missingHeaders)) {
                $_SESSION['msg'] = "Missing required headers: " . implode(', ', $missingHeaders);
                // log_message('error', 'Missing headers: ' . implode(', ', $missingHeaders));
                return redirect()->back();
            }

            $this->db->transBegin();
            $processedCount = 0;
            $errors = [];

            foreach ($dataRows as $index => $row) {
                if (count(array_filter($row)) === 0) {
                    continue; // skip empty row
                }

                if (count($row) < count($rawHeaders)) {
                    $row = array_pad($row, count($rawHeaders), null);
                }

                $rowAssoc = array_combine($rawHeaders, $row);

                $consumerNo = trim($rowAssoc['consumer_no'] ?? '');
                $remarks = trim($rowAssoc['remarks'] ?? '');
                $tcId = !empty(trim($rowAssoc['tc_id'] ?? '')) ? trim($rowAssoc['tc_id']) : null;

                if (empty($consumerNo)) {
                    $errors[] = "Missing consumer_no at row " . ($index + 2);
                    continue; // Continue processing other rows, collect all errors
                }

                $consumer = $this->db->table('tbl_consumer')
                    ->where('consumer_no', $consumerNo)
                    ->get()
                    ->getRow();

                if (!$consumer) {
                    $errors[] = "Consumer not found for consumer_no '$consumerNo' at row " . ($index + 2);
                    continue; // Continue processing other rows
                }

                $existingEntry = $this->db->table('tbl_disrupted_consumer_data')
                    ->where('consumer_id', $consumer->id)
                    ->get()
                    ->getRow();

                if ($existingEntry) {
                    continue; // skip duplicate
                }

                $insertData = [
                    'consumer_id' => $consumer->id,
                    'remarks' => $remarks,
                    'tc_id' => $tcId,
                    'created_by' => $this->emp_id
                ];

                $result = $this->db->table('tbl_disrupted_consumer_data')->insert($insertData);
                if ($result) {
                    $processedCount++;
                } else {
                    $errors[] = "Failed to insert data for consumer_no '$consumerNo' at row " . ($index + 2);
                }
            }

            // Check if there were any errors during processing
            if (!empty($errors)) {
                $this->db->transRollback();
                $_SESSION['msg'] = "Import failed with errors: " . implode('; ', $errors);
                // log_message('error', "Import errors: " . implode('; ', $errors));
                return redirect()->back();
            }

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                $_SESSION['msg'] = "Database error occurred during import.";
                // log_message('error', "Transaction failed while importing disrupted consumer data.");
            } else {
                $this->db->transCommit();
                // Set success message - you might want to use a different session variable for success
                $_SESSION['success_msg'] = "File processed successfully. $processedCount records imported.";
                // log_message('info', "$processedCount disrupted consumer entries imported successfully.");
            }

        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            $this->db->transRollback();
            $_SESSION['msg'] = "Spreadsheet error: " . $e->getMessage();
            // log_message('error', "Spreadsheet read error: " . $e->getMessage());
        } catch (Exception $e) {
            $this->db->transRollback();
            $_SESSION['msg'] = "Import failed: " . $e->getMessage();
            // log_message('error', "General error during disrupted data import: " . $e->getMessage());
        }

        return redirect()->back();
    }

    public function serviceDisruptedData($ajax="")
    {
        if($this->request->isAJAX() || $ajax){
            $where =" tbl_disrupted_consumer_data.status = 1 "	;
			
            $start = sanitizeString($this->request->getVar('start'));                
            $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page

            $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']??""); // Column index
			$columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']??""); // Column name
            if ($columnName=="consumer_name" )
                $columnName='consumer_details.consumer_name';
            else if ($columnName=="consumer_no")
                $columnName = 'tbl_consumer.consumer_no';
            else if ($columnName=="ward_no")
                $columnName = 'view_ward_mstr.ward_no';
            else if ($columnName=="full_emp_name")
                $columnName = 'view_emp_details.full_emp_name';
            else 
                $columnName = 'tbl_consumer.id'; 

            $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']??"");
			$orderBY = " ORDER BY ".$columnName." ".$columnSortOrder;
            $limit = " LIMIT ".($rowperpage==-1?"ALL":$rowperpage)." OFFSET ".$start; 
			
			
			$searchValue = sanitizeString($this->request->getVar('search')['value']??"");
			$whereQueryWithSearch = "";
            if ($searchValue!='') 
            {
                $whereQueryWithSearch = " AND (tbl_consumer.consumer_no ILIKE '%".$searchValue."%'
                                OR consumer_details.consumer_name ILIKE '%".$searchValue."%'
                                OR view_emp_details.full_emp_name ILIKE '%".$searchValue."%'
                                 )";
            }

            $select = "SELECT 
                        ROW_NUMBER () OVER (ORDER BY ".$columnName.") AS s_no,
                        tbl_consumer.id as consumer_id,
                        consumer_details.consumer_name,
                        tbl_consumer.address,
                        tbl_consumer.consumer_no, 
                        view_ward_mstr.ward_no, 
                        tbl_disrupted_consumer_data.remarks,
                        view_emp_details.emp_name,
                        view_emp_details.full_emp_name
			";
			$from = " FROM tbl_consumer 
                JOIN tbl_disrupted_consumer_data 
                ON tbl_disrupted_consumer_data.consumer_id = tbl_consumer.id
                LEFT JOIN (
                    SELECT consumer_id, STRING_AGG(applicant_name, '') AS consumer_name
                    FROM tbl_consumer_details
                    WHERE status = 1
                    GROUP BY consumer_id
                ) AS consumer_details 
                ON consumer_details.consumer_id = tbl_consumer.id
                LEFT JOIN view_ward_mstr 
                ON view_ward_mstr.id = tbl_consumer.ward_mstr_id
                LEFT JOIN view_emp_details
                ON view_emp_details.id = tbl_disrupted_consumer_data.created_by
                WHERE $where			
			";
			
			$totalRecords = $this->model_datatable->getTotalRecords($from,false);
            if ($totalRecords>0) 
            {
                
                ## Total number of records with filtering
                $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($from.$whereQueryWithSearch,false);
                
                ## Fetch records                
               $fetchSql = $select.$from.$whereQueryWithSearch.$orderBY;
			   if(!$ajax){
				    $fetchSql .= $limit;
			   }
                
                $result = $this->model_datatable->getRecords($fetchSql,false);                
                
                $records = [];
                if ($result) 
                {
                    foreach ($result AS $key=>$tran_dtl) 
                    {
                        $records[] = [
                            's_no'=>$tran_dtl['s_no'],
                            'consumer_name'=>$tran_dtl['consumer_name'],
                            'consumer_no'=>$tran_dtl['consumer_no'],
                            'ward_no'=>$tran_dtl['ward_no'],
							"address"=>$tran_dtl["address"],
                            'remarks'=>$tran_dtl["remarks"],
                            'full_emp_name'=>$tran_dtl['full_emp_name'],
                            "link"=>$ajax ?"" :'<a href="' . base_url('WaterViewConsumerDetails/index/' . md5($tran_dtl['consumer_id'])) . '" target="blank" class="btn btn-sm btn-primary">View</a>',
                            
                        ];
                    }
                }
                else 
                {
                    $totalRecordwithFilter = 0;
                    $records = [];
                }
                if($ajax){
                    phpOfficeLoad();
                    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                    $activeSheet =  $spreadsheet->getActiveSheet();
                                    $activeSheet->setCellValue('A1', 'Sl No.');
                                    $activeSheet->setCellValue('B1', 'Name');
                                    $activeSheet->setCellValue('C1', 'Consumer No.');
                                    $activeSheet->setCellValue('D1', 'Ward No');
                                    $activeSheet->setCellValue('E1', 'Address');
                                    $activeSheet->setCellValue('F1', 'Remarks');
                                    $activeSheet->setCellValue('G1', 'Tc Name');


                                    $activeSheet->fromArray($records, NULL, 'A3');

                    $filename = "report".date('Ymd-hisa').".xlsx";
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="'.$filename.'"');
                    header('Cache-Control: max-age=0');
                    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                    $writer->save('php://output');exit;
                    
                }
                else{
                    $response = array(
                        "draw" => 0,                
                        "recordsTotal" => $totalRecords,                 
                        "recordsFiltered" => $totalRecordwithFilter,
                        "data" => $records,                
        
                    );
                    return json_encode($response);
                }
            }
            $sql = "
                ";
            $data['result'] = $this->db->query($sql)->getResultArray() ?? [];
        }

        $data = array();
        return view("water/report/disrupted/list", $data);
    }
    
}

?>