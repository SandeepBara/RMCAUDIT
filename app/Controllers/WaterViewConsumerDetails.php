<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_view_water_consumer;
use App\Models\model_ward_mstr;
use App\Models\water_consumer_details_model;
use App\Models\water_consumer_demand_model;
use App\Models\WaterSearchConsumerMobileModel;
use App\Controllers\WaterGenerateDemand;
use App\Models\WaterMeterStatusModel;
use App\Models\WaterConsumerDemandModel;
use App\Models\WaterConsumerInitialMeterReadingModel;

use App\Models\WaterPaymentModel;
use App\Models\WaterMobileModel;
use App\Models\WaterConsumerTaxModel;
use App\Models\Model_water_notice;


class WaterViewConsumerDetails extends AlphaController
{
    protected $water;
    // protected $db;
    protected $dbSystem;
    protected $model_view_water_consumer;
    protected $model_ward_mstr;
    protected $ulb_id;
    protected $emp_id;
    protected $user_type_id;
    protected $meter_status_model;
    protected $last_reading;
    protected $payment_model;
    protected $consumer_details_model;
    protected $demand_model;
    protected $consumer_tax_model;
    protected $water_notice;

    public function __construct()
    {
        $session=session();
        $ulb_details=$session->get('ulb_dtl');
        //print_r($ulb_details);
        $this->ulb_id=$ulb_details['ulb_mstr_id'];

        $emp_details=$session->get('emp_details');
        $this->emp_id=$emp_details['id'];
        $this->user_type_id = $emp_details['user_type_mstr_id'];

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

        $this->consumer_demand_model=new water_consumer_demand_model($this->water);
        $this->demand_model=new WaterConsumerDemandModel($this->water);

        $this->search_consumer_mobile_model=new WaterSearchConsumerMobileModel($this->water);
        $this->generate_demand_controller=new WaterGenerateDemand();
        $this->meter_status_model=new WaterMeterStatusModel($this->water);
        $this->last_reading=new WaterConsumerInitialMeterReadingModel($this->water);

        $this->payment_model=new WaterPaymentModel($this->water);
        $this->WaterMobileModel=new WaterMobileModel($this->water);
        $this->consumer_tax_model = new WaterConsumerTaxModel($this->water);

        $this->water_notice = new Model_water_notice($this->water);
        
    }
    
    public function index($consumer_id=null)
    {   
        
        $data=array();
        $data['user_type']=$this->user_type_id;
        $data['consumer_id']=$consumer_id; //echo($consumer_id);

        $data['consumer_details']=$this->model_view_water_consumer->waterConsumerDetailsById($consumer_id);
        if(!empty($data['consumer_details']))
        {
            //$data['applicant_details']=$this->payment_model->fetch_all_application_data(md5($data['consumer_details']['apply_connection_id']));
            if($data['consumer_details']['apply_connection_id'])
                $data['applicant_details']=$this->payment_model->application_data($data['consumer_details']['apply_connection_id']);
            $data['consumer_owner_details']=$this->consumer_details_model->consumerDetails($data['consumer_details']['id']);
            if($data['consumer_details']['apply_from']=='Existing')
            {
                $data['consumer_details']['holding_no']=$this->model_view_water_consumer->getholding_no($data['consumer_details']['id'])['holding_no']??null;
            }
            
            $data['connection_dtls']=$this->meter_status_model->getLastConnectionDetails($data['consumer_details']['id']);
            // because at eo verification default connection meter is set but its doc not uploaded
            $get_last_reading=$this->last_reading->initial_meter_reading($data['consumer_details']['id']);
            $data['last_reading']=$get_last_reading['initial_reading'];


            $data['meter_status']=$this->meter_status_model->getMeterDocUploadedofFirstConnection2($data['consumer_details']['id']);
            //print_var($data['meter_status']);
            $data['dues']=$this->consumer_demand_model->countPaidStatus2($data['consumer_details']['id']);
            $sql = "SELECT tbl_meter_reading_doc.* 
                    FROM tbl_meter_reading_doc 
                    JOIN(
                            SELECT id 
                            FROM tbl_consumer_demand
                            WHERE status = 1 AND consumer_id = ".$data['consumer_details']['id']."
                            ORDER BY id desc limit 1
                        ) demand on demand.id = tbl_meter_reading_doc.demand_id
                    WHERE status = 1
                    order by id desc
                    limit 1
                    ";
            $data["ReadingImg"] = $this->water->query($sql)->getFirstRow('array');            
            return view('water/water_connection/water_consumer_details_view',$data);
        }
        
    }


    public function demand_generate($consumer_id,$tc=false)
    {
        $data=array();
        $data['tc']=$tc;
        
        $data['consumer_dtls']=$this->search_consumer_mobile_model->getConsumerDetailsbyId($consumer_id);
        if(empty($data['consumer_dtls']) || count($data['consumer_dtls'])==0 )        
            $data['consumer_dtls']=$this->model_view_water_consumer->waterConsumerDetailsById($consumer_id);
        $decr_consumer_id = $data['consumer_dtls']['id'];
        $data['connection_dtls']=$this->meter_status_model->getLastConnectionDetails($decr_consumer_id);
        
        $data['due_details']=$this->demand_model->consumerDueDetailsAll($decr_consumer_id);        
        $data['bid']=$consumer_id;        
        $cons_id=$data['consumer_dtls']['id'];
        $get_last_reading = $this->last_reading->initial_meter_reading($data['consumer_dtls']['id']);
        $data['last_reading'] = $get_last_reading['initial_reading'];
        $data['last_demand_dtl'] = $this->demand_model->getLastDemand2($decr_consumer_id);
        
        $data['getpreviousMeterReding']= $this->last_reading->getpreviousMeterReding($decr_consumer_id,$get_last_reading["id"]??0)['initial_reading']??0;
        // print_var($data['connection_dtls']);
        
        $date1= date_create($data['last_demand_dtl']['demand_upto']);        
        $date2=date_create($data['last_demand_dtl']['demand_from']);
        $date3 = date_create(date("Y-m-d"));
        $diff=date_diff($date2,$date1);        
        $no_diff = $diff->format("%a");            
        $current_diff = date_diff($date3,$date1)->format("%a");
        
        $reading = ($data['last_reading']??0) - ($data['getpreviousMeterReding']); 
        $arvg = $no_diff!=0 ? round(($reading / $no_diff),2) : 1 ;
        
        $current_reading = ( $current_diff * $arvg);
        
        $data["arg"]=[
                        "priv_demand_from"=> $data['last_demand_dtl']['demand_from'],
                        "priv_demand_upto"=> $data['last_demand_dtl']['demand_upto'],
                        "demand_from"=> $data['last_demand_dtl']['demand_upto'],
                        "demand_upto" => date("Y-m-d"),
                        "priv_day_diff"=> $no_diff,
                        "current_day_diff"=> $current_diff ,
                        "last_reading" => $reading,
                        "current_reading"=>$current_reading,
                        "arvg" =>$arvg ,
                    ];
                    
        // print_var($data['arg']);
        
        $data['twoAvgBill'] = $this->consumer_tax_model->getAverageTwoBill($decr_consumer_id);
        // print_var($data["arg"]);
        $towAvgBill = false;
        $data['oneAvgBill'] = false;
        $filter = array_filter($data['twoAvgBill'],function($val){
            return $val['charge_type']=='Average'?true:false;
        });
        if(sizeof($filter)>0)
        {
            $data['oneAvgBill']=true;
        }
        if(sizeof($filter)>1)
        {
            $towAvgBill=true;
        }
        $data['twoAvgBill']=$towAvgBill;
        
        if($tc)
        {
            $curr_month = date('m');
            if ($curr_month == 01 or $curr_month == 02 or $curr_month == 03) {
                $curr_year = date('Y') - 1;
            } else {
                $curr_year = date('Y');
            }


            $next_year = $curr_year + 1;
            $start_year = $curr_year . '-04-01';
            $end_year = $next_year . '-03-31';
            		
            $data['arr_due_amt'] = $this->demand_model->getArrearDues2($cons_id, $start_year);
            $data['curr_due_amt'] = $this->demand_model->getCurrentYearDues2($cons_id, $start_year, $end_year);
            $data['due_from'] = $this->demand_model->getDueFrom2($cons_id);
            $data['due_upto'] = $this->demand_model->getDueUpto2($cons_id);
        }
        if($this->request->getMethod()=='post')
        {
            $inputs=arrFilterSanitizeString($this->request->getVar());
            $file = null;
            //  print_r($inputs);
            //$due_upto=$this->demand_model->getMaxDemandGenerated($consumer_id);
            $due_upto=$this->demand_model->getMaxDemandGeneratedDate($cons_id);
            //print_r($data['due_details']);die;
            $prev_month=date('Y-m-d',strtotime(date('Y-m-d')."-1 months"));
            if($data['connection_dtls']['connection_type']==3)
            {   
                if($due_upto>=$prev_month)
                {
                    flashToast("error", "Demand Already Generated Upto Previous Month in Fixed!!!");
                    if($tc)
                    {
                        return $this->response->redirect(base_url('WaterViewConsumerMobile/view/'.$cons_id));
                    }
                    return $this->response->redirect(base_url('WaterViewConsumerDetails/demand_generate/'.$consumer_id));
                }

            }
            elseif(in_array($data['connection_dtls']['connection_type'],[1,2]))
            {   
                $file = $this->request->getFile('document');
                $rules = [
                    'document'=>'uploaded[document]|max_size[document,3072]|ext_in[document,png,pdf,jpg,jpeg]',
                ];
                if(!$this->validate($rules))
                {
                    $data['validation']=$this->validator->getError();
                    flashToast("error", $data['validation']);
                    if($tc)
                    {
                        return $this->response->redirect(base_url('WaterViewConsumerMobile/view/'.$cons_id));
                    }
                    return $this->response->redirect(base_url('WaterViewConsumerDetails/demand_generate/'.$consumer_id));
                }

            }
            if($inputs['generatedemand'])
            {
                $upto_date=!empty($inputs['upto_date'])?date('Y-m-d',strtotime($inputs['upto_date']))??date('Y-m-d'):date('Y-m-d');
                if($upto_date<=$due_upto)
                {
                    flashToast("error", "Demand Already Generated Upto $due_upto !!!");
                    if($tc)
                    {
                        return $this->response->redirect(base_url('WaterViewConsumerMobile/view/'.$cons_id));
                    }
                    return $this->response->redirect(base_url('WaterViewConsumerDetails/demand_generate/'.$consumer_id));
                }

                $final_meter_reading=isset($inputs['final_meter_reading'])?$inputs['final_meter_reading']:0;
                
                $last_meter_reading=$get_last_reading['initial_reading'];
                $last_demand_generated=$get_last_reading['initial_date'];
                $final_meter_reading=isset($inputs['final_meter_reading'])?$inputs['final_meter_reading']:0;
                if($last_meter_reading>$final_meter_reading && $final_meter_reading!="")
                {
                    flashToast("error", "Final Reading should be greater than Previous!!!");
                    if($tc)
                    {
                        return $this->response->redirect(base_url('WaterViewConsumerMobile/view/'.$cons_id));
                    }
                    return $this->response->redirect(base_url('WaterViewConsumerDetails/demand_generate/'.$consumer_id));
                }
                $ar_squ_f = $data['consumer_dtls']['area_sqft']??0;
				if($ar_squ_f==null || $ar_squ_f==0)
				{
					flashToast("error", "Update your area or property type!!!");
                    if($tc)
                    {
                        return $this->response->redirect(base_url('WaterViewConsumerMobile/view/'.$cons_id));
                    }
                    return $this->response->redirect(base_url('WaterViewConsumerDetails/demand_generate/'.$consumer_id));
				}
                $last_demand_generated_month = date("m-Y", strtotime($last_demand_generated));
                $current_month = date("m-Y");
                if($last_demand_generated_month == $current_month && in_array($data['connection_dtls']['connection_type'],[1,2]))
                {
                    flashToast("error", "Can not generated demand multiple times in same month!!!");
                    if($tc)
                    {
                        return $this->response->redirect(base_url('WaterViewConsumerMobile/view/'.$cons_id));
                    }
                    return $this->response->redirect(base_url('WaterViewConsumerDetails/demand_generate/'.$consumer_id));
                }
                
                $this->water->transBegin();

                // $tax_id = $this->generate_demand_controller->averageBulling($cons_id,$upto_date,$final_meter_reading,$file);                
                $tax_id = $this->generate_demand_controller->tax_generation($cons_id,$upto_date,$final_meter_reading,$file);
                if($this->water->transStatus() === FALSE || !$tax_id)
                {
                    $this->water->transRollback();                    
                    flashToast('message', "Demand Not Generated");
                    return redirect()->back()->withInput();
                } 
                else
                {
                    $this->water->transCommit();
                    if($tax_id && $tc)
                    { 
                        flashToast("success_demand", "Demand Generated Successfully!!!");                       
                        return $this->response->redirect(base_url('WaterViewConsumerMobile/consumer_demand_receipt/'.$consumer_id.'/'.md5($tax_id)));
                    }
                    // if(is_null($tax_id) && $data['consumer_dtls']['category']=='BPL')
                    // { 
                    // 	// flashToast("message", "Demand Not Generated Of BPL Category Due To Maintenance!!!");
                    //     return $this->response->redirect(base_url('WaterViewConsumerDetails/demand_generate/'.$consumer_id));
                    // }
                    return $this->response->redirect(base_url('WaterViewConsumerDueDetails/index/'.$consumer_id));

                }              
            }

        }
        if(empty($data['consumer_dtls']['area_sqmt']) )
        {
            
            flashToast("message", "Update your area or property type!!!");
        }
        if(empty($data['connection_dtls']) ||  $data['connection_dtls']['connection_date']=='')
        {
            flashToast("error", "Connection Date Not Found!!!"); 
        } 
             
        return view('water/water_connection/consumer_demand_generate',$data);

    }

    /*********************15-02-2022 by sandeep **********************/ 
    
    public function update_consumer($consumer_id=null,$mobile=false)
    {
        $data = array();//echo($consumer_id);
        $data['mobile']=$mobile;
        $data['consumer_id']=$consumer_id;
        if($consumer_id!=null)
        {
            $data['connection_dtls']=$this->meter_status_model->getLastConnectionDetailsbyMd5($consumer_id);
            $sql="select c.*,w.ward_no,p.property_type,
                    case when m.connection_type = 1 then 'METER'
                        when m.connection_type = 2 then 'GALLON'
                        when m.connection_type = 3 then 'FIXED' 
                        else 'Not Defiend' end as meter
                  from tbl_consumer c 
                  join view_ward_mstr w on w.id = c.ward_mstr_id 
                  join tbl_property_type_mstr p on p.id = c.property_type_id
                  left join ( select * from 
                        tbl_meter_status 
                        where md5(consumer_id::text)='$consumer_id' 
                        order by id desc limit 1
                        ) as m on m.consumer_id = c.id and m.status=1
                  where md5(c.id::text)='$consumer_id' ";
            $data['consumer_dtl']=$this->WaterMobileModel->getDataRowQuery2($sql)['result'];
            if(sizeof($data['consumer_dtl'])>0)
                $data['consumer_dtl']=$data['consumer_dtl'][0];            
           $cons_id=$data['consumer_dtl']['id'];
           //print_var($cons_id);
           $sql="select id,ward_no from view_ward_mstr where ulb_mstr_id =$this->ulb_id and status=1 ";
           $data['ward_list']=$this->WaterMobileModel->getDataRowQuery2($sql)['result'];
           //$data['connection']=$this->meter_status_model->getLastConnectionDetailsbyMd5($consumer_id); 
           $sql="select * from tbl_consumer_details where md5(consumer_id::text) ='$consumer_id' and status=1 ";
           $data['owner_list']=$this->WaterMobileModel->getDataRowQuery2($sql)['result'];//echo $sql;
           if(strtoupper($this->request->getMethod())=='POST')
           {
                $inputs=arrFilterSanitizeString($this->request->getVar());
                $up_data = [
                    'area_sqft'=>$inputs['area_in_sqft'],
                    'area_sqmt'=>$inputs['area_in_sqmt'],
                ];
                $update =$this->WaterMobileModel->updateNew(array('md5(id::text)'=>$consumer_id),$up_data,'tbl_consumer');
                if($update)
                {
                    echo base_url();
                    flashToast("message", "Update Consumer Successfully !!!");
                    if($mobile)
                        return redirect()->to(base_url().'/WaterViewConsumerMobile/view/'.$cons_id);   
                    return redirect()->to(base_url().'/WaterViewConsumerDetails/index/'.$consumer_id);
                }
                else
                {
                    flashToast("message", "Update Consumer Successfully !!!");
                }
           }
        }
        //print_var($data['consumer_dtl']);die;
        return view('water/water_connection/update_consumer',$data);
    }
    public function bulkFixedDemandgenerate()
    {
        $data = array();
        $lastdate = date("Y-m-t");
        $lastForDate = date('Y-m-d',strtotime($lastdate."- 4 days"));
        if(date("Y-m-d")<$lastForDate)
        {
            flashToast("error", "Demand Not Gererated Now Please Wait"); 
            return redirect()->back()->with('error', "Demand Not Gererated Now Please Wait");
        }
        $sql = "select tbl_consumer.id, tbl_consumer.consumer_no,address,
                    view_ward_mstr.ward_no,
                    owner_name,father_name,mobile_no,
                    case when connection_type isnull or connection_type=3 then 'Fixed'
                        when connection_type=2 then 'Gallon'
                        when connection_type=1 then 'Meter'
                        end as connection_type,
                    meter_fixed.connection_date,
                    demand.demand_upto        
                from tbl_consumer
                join view_ward_mstr on view_ward_mstr.id = tbl_consumer.ward_mstr_id
                left join(
                    select distinct(consumer_id)as consumer_id,
                        string_agg(applicant_name,', ') as owner_name,
                        string_agg(father_name,', ') as father_name,
                        string_agg(mobile_no::text,', ') as mobile_no
                    from tbl_consumer_details 
                    where status=1
                    group by consumer_id
                )owneres on owneres.consumer_id = tbl_consumer.id
                join (
                    select consumer_id,max(demand_upto) as demand_upto
                    from tbl_consumer_demand
                    where status=1
                    group by consumer_id
                    order by consumer_id
                )demand on demand.consumer_id=tbl_consumer.id
                    and demand_upto < (SELECT (date_trunc('month', now()::date) - interval '1 month')::date
                                        AS end_of_month)
                join(
                select consumer_id,connection_type,connection_date, 
                row_number() OVER (PARTITION BY consumer_id ORDER BY id DESC) AS rownum
                from tbl_meter_status
                where status =1
                order by consumer_id
                )meter_fixed on meter_fixed.consumer_id=tbl_consumer.id 
                    and meter_fixed.rownum=1 and meter_fixed.connection_type=3
                JOIN( 
                        SELECT DISTINCT( tbl_transaction.related_id) as related_id
                        FROM tbl_transaction 
                        WHERE tbl_transaction.status in (1,2)
                            and tbl_transaction.transaction_type = 'Demand Collection'
                )tran ON tran.related_id = tbl_consumer.id    
                WHERE tbl_consumer.status =1 and tbl_consumer.property_type_id !=3    
                    AND(tbl_consumer.apply_from !='Existing' OR NOT tbl_consumer.created_on::date >='2021-10-26')
                order by demand.demand_upto desc,meter_fixed.connection_type ,view_ward_mstr.id
                
                ";
            $result = $this->WaterMobileModel->getDataRowQuery($sql,50);
            $data = $result;
            if(strtoupper($this->request->getMethod())=="POST")
            {
                $upto_date  =   date('Y-m-d');
                $file       =   null;
                $final_meter_reading=0;
                $inputs = $this->request->getVar();
                foreach($inputs['check'] as $val)
                {
                    $this->water->transBegin();
                    $tax_id = $this->generate_demand_controller->tax_generation($val,$upto_date,$final_meter_reading,$file);                    
                    if($this->water->transStatus() === FALSE || !$tax_id)
                    {
                        $this->water->transRollback();                    
                        flashToast('message', "Demand Not Generated");
                    } 
                    else
                    {
                        $this->water->transCommit();
                        flashToast("success_demand", "Demand Generated Successfully!!!"); 
                    }
                }
                return $this->response->redirect(base_url('WaterViewConsumerDetails/bulkFixedDemandgenerate'));
            }
            return view("water/water_connection/bulkFixedDemandgenerate",$data);
    }

    public function bulkMeterDemandGenerate()
    {
        $data = array();
        $lastdate = date("Y-m-t");
        $lastForDate = date('Y-m-d',strtotime($lastdate."- 4 days"));
        if(date("Y-m-d")<$lastForDate && $this->user_type_id !=1)
        {
            flashToast("error", "Demand Not Gererated Now Please Wait"); 
            return redirect()->back()->with('error', "Demand Not Gererated Now Please Wait");
        }
        $sql = "select tbl_consumer.id, tbl_consumer.consumer_no,address,
                        view_ward_mstr.ward_no,
                        owner_name,father_name,mobile_no,
                        case when connection_type isnull or connection_type=3 then 'Fixed'
                            when connection_type=2 then 'Gallon'
                            when connection_type=1 then 'Meter'
                            end as connection_type,
                        meter_fixed.connection_date,
                        demand.demand_upto        
                from tbl_consumer
                join view_ward_mstr on view_ward_mstr.id = tbl_consumer.ward_mstr_id
                left join(
                    select distinct(consumer_id)as consumer_id,
                        string_agg(applicant_name,', ') as owner_name,
                        string_agg(father_name,', ') as father_name,
                        string_agg(mobile_no::text,', ') as mobile_no
                    from tbl_consumer_details 
                    where status=1
                    group by consumer_id
                )owneres on owneres.consumer_id = tbl_consumer.id
                join (
                    select consumer_id,max(demand_upto) as demand_upto
                    from tbl_consumer_demand
                    where status=1
                    group by consumer_id
                    order by consumer_id
                )demand on demand.consumer_id=tbl_consumer.id
                    and demand_upto < (SELECT (date_trunc('month', now()::date) - interval '2 month')::date
                                        AS end_of_month)
                join(
                    select consumer_id,connection_type,connection_date, 
                    row_number() OVER (PARTITION BY consumer_id ORDER BY id DESC) AS rownum
                    from tbl_meter_status
                    where status =1
                    order by consumer_id
                )meter_fixed on meter_fixed.consumer_id=tbl_consumer.id 
                    and meter_fixed.rownum=1 and meter_fixed.connection_type in(1,2)
                JOIN( 
                        SELECT DISTINCT( tbl_transaction.related_id) as related_id
                        FROM tbl_transaction 
                        WHERE tbl_transaction.status in (1,2)
                            and tbl_transaction.transaction_type = 'Demand Collection'
                )tran ON tran.related_id = tbl_consumer.id 
                WHERE tbl_consumer.status =1 and tbl_consumer.property_type_id !=3 
                    AND(tbl_consumer.apply_from !='Existing' OR NOT tbl_consumer.created_on::date >='2021-10-26')
                order by demand.demand_upto desc,meter_fixed.connection_type ,view_ward_mstr.id
                
                ";
        $result = $this->WaterMobileModel->getDataRowQuery($sql,50);
        $data = $result;
        if(strtoupper($this->request->getMethod())=="POST")
        {
            $upto_date  =   date('Y-m-d');
            $file       =   null;
            $final_meter_reading=0;
            $inputs = $this->request->getVar();
            foreach($inputs['check'] as $val)
            {
                $this->water->transBegin();
                // $tax_id = $this->generate_demand_controller->tax_generation($val,$upto_date,$final_meter_reading,$file); 
                $tax_id = $this->generate_demand_controller->meterAverageBilling($val,$upto_date,$final_meter_reading,$file); 
                //  print_var("=============$val===================");
                // continue;                
                if($this->water->transStatus() === FALSE || !$tax_id)
                {
                    $this->water->transRollback();                    
                    flashToast('message', "Demand Not Generated");
                } 
                else
                {
                    $this->water->transCommit();
                    flashToast("success_demand", "Demand Generated Successfully!!!"); 
                }
            }
            return $this->response->redirect(base_url('WaterViewConsumerDetails/bulkMeterDemandGenerate'));
        }
        return view("water/water_connection/bulkMeterDemandgenerate",$data);
    }

    /*********************15-02-2022 by sandeep end **********************/ 
    #*********************11-03-2023 by sandeep for Worng Demand genration for Meter****************#

    public function getMeterDiffConcumerList()
    {
        $sql = "with demands as (
            select distinct(tbl_consumer_demand.consumer_id) as consumer_id
            from tbl_consumer_demand 
            left join tbl_diff_demand_genrated on tbl_diff_demand_genrated.demand_id = tbl_consumer_demand.id 
            where tbl_consumer_demand.consumer_tax_id notnull 
                and tbl_consumer_demand.status = 1
                and tbl_consumer_demand.connection_type in ('Meter','Meterd') 
                and tbl_diff_demand_genrated.demand_id is null
            group by tbl_consumer_demand.consumer_id
        )
        select tbl_consumer.id,view_ward_mstr.ward_no,tbl_consumer.consumer_no, 
            tbl_consumer.address, owners.owner_name,owners.father_name,owners.mobile_no
        from tbl_consumer
        join demands on demands.consumer_id = tbl_consumer.id
        left join (
            select distinct(tbl_consumer_details.consumer_id) as consumer_id,
                string_agg(applicant_name,', ') as owner_name,
                string_agg(father_name,', ') as father_name,
                string_agg(mobile_no::text,', ') as mobile_no
            from tbl_consumer_details
            join demands on demands.consumer_id = tbl_consumer_details.consumer_id
            where tbl_consumer_details.status =1 
            group by tbl_consumer_details.consumer_id
        ) owners on owners.consumer_id = tbl_consumer.id
        left join view_ward_mstr on view_ward_mstr.id = tbl_consumer.ward_mstr_id
        where tbl_consumer.status = 1
        ";
        $result = $this->WaterMobileModel->getDataRowQuery($sql,500);
        $data = $result;
        // print_var($result['count']);
        // die;
        return view("water/water_connection/getMeterDiffConcumerList",$data);
    }

    public function MeterDiffConcumerGenrate()
    {
        $data=(array)null;
        if(strtoupper($this->request->getMethod())=="POST")
        {
            $upto_date  =   date('Y-m-d');
            $file       =   null;
            $final_meter_reading=0;
            $inputs = $this->request->getVar();
            foreach($inputs['check'] as $val)
            {              
                $this->water->transBegin();
                $sql = "WITH demands AS(
                            SELECT tbl_consumer_demand.id,tbl_consumer_demand.consumer_id,tbl_consumer_demand.consumer_tax_id,tbl_consumer_demand.generation_date ,
                                tbl_consumer_demand.amount,tbl_consumer_demand.paid_status,tbl_consumer_demand.demand_from,
                                tbl_consumer_demand.demand_upto,tbl_consumer_demand.connection_type,
                                tbl_consumer_tax.charge_type,tbl_consumer_tax.rate_id , initial_reading,final_reading,
                                tbl_consumer_tax.amount,tbl_consumer_tax.effective_from
                            FROM tbl_consumer_demand                         
                            LEFT JOIN tbl_diff_demand_genrated ON tbl_diff_demand_genrated.demand_id = tbl_consumer_demand.id
                            JOIN tbl_consumer_tax ON tbl_consumer_tax.id = tbl_consumer_demand.consumer_tax_id
                            WHERE tbl_consumer_demand.status =1  
                                AND tbl_consumer_demand.connection_type IN ('Meter','Meterd')
                                AND tbl_consumer_demand.consumer_tax_id IS NOT NULL
                                AND tbl_diff_demand_genrated.demand_id IS NULL
                                AND tbl_consumer_demand.consumer_id = $val
                            ORDER BY tbl_consumer_demand.consumer_id,tbl_consumer_tax.created_on
                        )
                        SELECT demands.* ,
                            view_ward_mstr.ward_no,tbl_consumer.consumer_no, tbl_consumer.address, 
                            owners.owner_name,owners.mobile_no
                        FROM demands
                        LEFT JOIN tbl_consumer ON tbl_consumer.id = demands.consumer_id
                        LEFT JOIN (
                            SELECT distinct(tbl_consumer_details.consumer_id) AS consumer_id,
                                string_agg(applicant_name,', ') AS owner_name,
                                string_agg(father_name,', ') AS father_name,
                                string_agg(mobile_no::text,', ') AS mobile_no
                            FROM tbl_consumer_details
                            WHERE tbl_consumer_details.status =1 
                                AND tbl_consumer_details.consumer_id = $val
                            GROUP BY tbl_consumer_details.consumer_id
                        ) owners ON owners.consumer_id = tbl_consumer.id
                        LEFT JOIN view_ward_mstr ON view_ward_mstr.id = tbl_consumer.ward_mstr_id
                ";
                $demands = $this->water->query($sql)->getResultArray(); 
                $tax_id = true;               
                foreach($demands as $key =>$demand)
                {
                    $final_reading = $demand['final_reading']??0;
                    $initial_reading = $demand['initial_reading']??0;
                    $test = $this->generate_demand_controller->generatDiffDemand($val,$demand['id'],$final_reading,$initial_reading,$demand['connection_type']);
                    
                    if(!$test["status"])
                    {
                        $tax_id =  $test["status"];
                    }
                    $demands[$key]["diffrent_ammount"] = $test["diffrent_ammount"];
                    $demands[$key]["text_coler"] = !$test["status"]?"danger":"success";
                    $data["demands"][] =$demands[$key];                                      
                    
                }  
                    
                if($this->water->transStatus() === FALSE || !$tax_id)
                {
                    $this->water->transRollback();                    
                    flashToast('message', "Demand Not Generated");
                } 
                else
                {
                    // $this->water->transRollback();    
                    $this->water->transCommit();
                    flashToast("success_demand", "Demand Generated Successfully!!!"); 
                }
            }
            return view('water/water_connection/getMeterDiffConcumerListDtails',$data);
        }
        else
        {
            return $this->response->redirect(base_url('WaterViewConsumerDetails/getMeterDiffConcumerList'));
        }
    }
    
    
    #********************11-03-2023 by sandeep for Worng Demand genration for Meter End ************#




    public function Notice($consumer_id ){

        $result = [];
         try {
             $data = arrFilterSanitizeString($this->request->getVar());

              $result['consumer_dtl'] = $this->water_notice->getBasicDetails($consumer_id);

              $result['connection_dtls']=$this->meter_status_model->getLastConnectionDetails($result['consumer_dtl']['id']);
              $result['due_details']=$this->demand_model->consumerDueDetails(md5($consumer_id));
              $result['due_summary'] = $this->demand_model->demand_summary( $result['consumer_dtl']['id']);


              $result['dues']=$this->demand_model->due_demand(md5($consumer_id));
             $emp_details_id = $_SESSION['emp_details']['id'];

              if (isset($_POST['gen_notice'])) {

                 $notice_generate_time = date('YdHis');
                 $notice_no = 'NOTICE/WTR/' .$consumer_id .$notice_generate_time;

                 $sql = "SELECT * FROM tbl_water_notices
                         WHERE notice_no='".trim($notice_no)."'";
                 $checkdata = $this->water_notice->query($sql)->getResultArray("array");

                 $firstActiveEoSql = " select * from view_emp_details where lock_status=0 and user_type_mstr_id=16 order by id ASC limit 1 "; 
                 $firstEo = $this->dbSystem->query($firstActiveEoSql)->getFirstRow("array");
                 $firstEo=["id"=>1661];
                 
                //  $firstActiveEoSql = " select * from view_emp_details where lock_status=0 and user_type_id=16 order by id ASC limit 1 "; 
                //  $firstEo = $this->water->query($firstActiveEoSql)->getFirstRow("array");

                 if(count($checkdata)==0)
                 {

                     if($data["notice_type"] == 'Meter')
                     {

                         if((float)[$result['due_summary']['balance_amount']] > 0){

                             $input = [
                                 "wtr_dtl_id"=>$consumer_id,
                                 "notice_no"=>$notice_no,
                                 "notice_date"=>$data["notice_date"],
                                 "notice_type"=>$data["notice_type"],
                                 "from_fyear"=>"",
                                 "upto_fyear"=>"",
                                 "total_amount"=>$result['due_summary']['balance_amount'],
                                 "penalty_total" =>$result['due_summary']['penalty'],
                                 "demand_amount" =>$result['due_summary']['amount'],
                                 "generated_by_emp_details_id"=>$emp_details_id,
                                 "print_status"=>1,
                                 "approved_by"=>$firstEo["id"]??null,
                             ];
                         }else{
                             $input = [];
                         }

                     }
                     if($data["notice_type"] == 'Non_Meter' ){
                         $input = [
                             "wtr_dtl_id"=>$consumer_id,
                             "notice_no"=>$notice_no,
                             "notice_date"=>$data["notice_date"],
                             "notice_type"=>$data["notice_type"],
                             "from_fyear"=>"",
                             "upto_fyear"=>"",
                             "total_amount"=>$result['due_summary']['balance_amount'],
                             "penalty_total" =>$result['due_summary']['penalty'],
                             "demand_amount" =>$result['due_summary']['amount'],
                             "generated_by_emp_details_id"=>$emp_details_id,
                             "print_status"=>1,
                             "approved_by"=>$firstEo["id"]??null,
                         ];
                     }
                     if($input)
                     {
                         $lastInsertId = $this->water_notice->insertWaterNoticeData($input);
                         $serial_no = $lastInsertId."/".$this->water_notice->getWaterCount($consumer_id)['serial'];
                         $this->water_notice->updateWater_Record(
                             ['serial_no'=>$serial_no], $lastInsertId);
                     }

                 }
             }
              $result['notice_dtl'] = $this->water_notice->getWaterNotice($consumer_id);
             return view('water/generate_notice', $result);
         } catch(Exception $e) {
             dd($e);
             print_var($e);
         }
        return view('water/generate_notice', $result);

    }


    public function GeneratedNotice($notice_id) {
        try {

            $emp_details_id = $_SESSION['emp_details']['id'];
            $notice=$this->water_notice->getWaterNoticeById($notice_id);

            $noticeDtl['water_dtl'] = $notice;
            $noticeDtl['emp_details_id']= $emp_details_id;

            $sql = "SELECT
                        tbl_consumer.id,
                        tbl_consumer.consumer_no,
                        view_ward_mstr.ward_no,
                        tbl_consumer.holding_no,
                        tbl_consumer.address,
                        tbl_consumer_details.applicant_name AS owner_name,
                        tbl_consumer_details.father_name,
                        MIN(tbl_consumer_demand.demand_from) AS demand_from,
                        MAX(tbl_consumer_demand.demand_upto) AS demand_upto,
                        tbl_water_notices.wtr_dtl_id
                    FROM
                        tbl_consumer
                    INNER JOIN
                        tbl_consumer_details ON tbl_consumer.id = tbl_consumer_details.consumer_id
                        INNER JOIN
                            tbl_consumer_demand ON tbl_consumer.id = tbl_consumer_demand.consumer_id
                        LEFT JOIN
                            tbl_water_notices ON tbl_consumer.id = tbl_water_notices.wtr_dtl_id
                        LEFT JOIN view_ward_mstr ON tbl_consumer.ward_mstr_id = view_ward_mstr.id
                        WHERE
                            tbl_water_notices.wtr_dtl_id = '".$notice["wtr_dtl_id"]."'
                        GROUP BY
                            tbl_consumer.id,
                            tbl_consumer.consumer_no,
                            view_ward_mstr.ward_no,
                            tbl_consumer.holding_no,
                            tbl_consumer_details.applicant_name,
                            tbl_consumer.address,
                            tbl_consumer_details.father_name,
                            tbl_water_notices.wtr_dtl_id
                ";
            $result = $this->water_notice->query($sql)->getFirstRow("array");
            $notice['consumer'] = $result;

            $notice["signature_path"] = base_url("/public/assets/img/watetsign.png");
            if($notice["approved_by"]){
                $empDtl = $this->dbSystem->query("select * from tbl_emp_details where id=".$notice["approved_by"])->getFirstRow("array");
                $notice["signature_path"]= $empDtl && $empDtl["signature_path"]? base_url("/getImageLink.php?path=/emp_signature/".$empDtl["signature_path"]):$notice["signature_path"] ;
            }


            if($notice['notice_type'] == "Meter"){

                $get_last_reading = $this->last_reading->initial_meter_reading($notice['consumer']['id']);
                $notice['last_reading'] = $get_last_reading['initial_reading'];
                $notice['getpreviousMeterReding']= $this->last_reading->getpreviousMeterReding($notice['id'],$get_last_reading["id"]??0)['initial_reading']??0;
                $notice['reading'] = ($notice['last_reading']??0) - ($notice['getpreviousMeterReding']);
                // print_var($notice);
                return view('water/water_notice_print', $notice);
            }
            if($notice['notice_type'] == "Non_Meter"){

                return view('water/water_notice_non_meter_print', $notice);
            }

        } catch(Exception $e) {
            print_var($e);
        }
    }

}
