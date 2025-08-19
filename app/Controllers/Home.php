<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\model_prop_dtl;
use App\Models\model_prop_owner_detail;
use App\Models\model_prop_tax;
use App\Models\model_prop_demand;
use App\Models\model_prop_floor_details;
use App\Models\model_transaction;
use App\Models\model_tran_mode_mstr;
use App\Models\model_ulb_mstr;
use App\Models\model_all_module_dcb;

use App\Models\model_collection;
use App\Models\WaterConsumerDemandModel;
use App\Models\WaterConsumerCollectionModel;
use App\Models\TradeTransactionModel;
use App\Models\DashboardModel;
use App\Models\WaterReportModel;
use App\Models\model_water_dashboard_data;

class Home extends BaseController
{
    protected $db_name;
    protected $model_ward_mstr;
    protected $model_prop_dtl;
    protected $model_prop_owner_detail;
    protected $model_prop_tax;
    protected $model_prop_demand;
    protected $model_prop_floor_details;
    protected $model_transaction;
    protected $model_tran_mode_mstr;
    protected $model_ulb_mstr;
    protected $model_all_module_dcb;

    protected $model_collection;
    protected $WaterConsumerDemandModel;
    protected $WaterConsumerCollectionModel;
    protected $TradeTransactionModel;

    public function __construct()
    {
        helper(['form', 'db_helper']);
        $this->ulb_id = 1;

        /*$session=session();
        $ulb_details=$session->get('ulb_dtl');
		$ulb_dtl = $session->get('ulb_dtl');
		$db_property = $ulb_dtl['property'];
		$db_water = $ulb_dtl['water'];
		$db_trade = $ulb_dtl['trade'];
		$db_name = db_connect($db_property);
		$db_watername = db_connect($db_water);
		$db_tradename = db_connect($db_trade);		
        if($dbname = dbSystem())
		{
            $this->dbSystem = db_connect($dbname); 
        }
        $this->model = new model_ward_mstr($this->dbSystem);
		$this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
		$this->modelprop = new model_prop_dtl($db_name);
		$this->modelowner = new model_prop_owner_detail($db_name);
		$this->modeltax = new model_prop_tax($db_name);
		$this->modeldemand = new model_prop_demand($db_name);
		$this->modelfloor = new model_prop_floor_details($db_name);
		$this->modelpay = new model_transaction($db_name);
		$this->modeltran = new model_tran_mode_mstr($db_name);
		$this->model_all_module_dcb = new model_all_module_dcb($this->dbSystem);
		
		$this->model_collection = new model_collection($db_name);
		$this->WaterConsumerDemandModel = new WaterConsumerDemandModel($db_watername);
		$this->WaterConsumerCollectionModel = new WaterConsumerCollectionModel($db_watername);
		$this->TradeTransactionModel = new TradeTransactionModel($db_tradename);

		$this->DashboardModel = new DashboardModel($db_name);

		$this->water_report_model=new WaterReportModel($db_watername);
		$this->model_water_dashboard_data = new model_water_dashboard_data($db_watername);*/
    }

    function __destruct()
    {
        //if(isset($this->dbSystem)) $this->dbSystem->close();
    }

    public function index()
    {
        $data = (array)null;
        cSetCookie("heading_title", "HOME");
        return view('index', $data);
    }

    public function test()
    {
        return view('home');
    }

    public function PropertyMenu()
    {
        return view('citizen/property_menu');
    }

    public function WaterMenu()
    {
        return view('citizen/water_menu');
    }

    // TradeMenu to tradeList
    public function tradeList()
    {
        return view('citizen/trade_menu');
    }

    /*public function GrievanceMenu()
    {
        $data=[];
        $data['ulb_mstr_id']=$this->ulb_id;
        $data['ward_list']=$this->model->getWardList($data);
        //print_r($data['ward_list']);
        return view('citizen/grievance_menu',$data);
    }*/

    public function login()
    {
        return view('login');
    }

    public function dashboard()
    {
        echo view('layout_vertical/header');
        echo view('dashboard');
        echo view('layout_vertical/footer');
    }

    /*public function pay_Property_Tax()
    {

        $data =(array)null;
        helper(['form']);
        if($this->request->getMethod()=='post'){

            $data = [
                        'previous_ward_mstr_id' => $this->request->getVar('previous_ward_mstr_id'),
                        'ward_mstr_id' => $this->request->getVar('ward_mstr_id'),
                        'holding_no' => $this->request->getVar('holding_no'),
                        'house_no' => $this->request->getVar('house_no')
                    ];

                if($data['previous_ward_mstr_id']!=""){
                    if($data['holding_no']!=""){
                        $data['emp_details'] = $this->modelprop->acc_prv_hld_emp_details($data);
                    }elseif($data['house_no']!=""){
                        $data['emp_details'] = $this->modelprop->acc_prv_hus_emp_details($data);
                    }else{
                        echo "hjbfvjh";
                    }
                }elseif($data['ward_mstr_id']!=""){
                    if($data['holding_no']!=""){
                        $data['emp_details'] = $this->modelprop->acc_wrd_hld_emp_details($data);
                    }elseif($data['house_no']!=""){
                        $data['emp_details'] = $this->modelprop->acc_wrd_hus_emp_details($data);
                    }else{
                        echo "gnghjvhmvh";
                    }
                }
            //print_r($data);
            //$data['emp_details'] = $this->modelprop->emp_details($data);
            //print_r($data);
            $data['ward'] = $this->model->ward_list();
            return view('property/citizen/search_Property_List', $data);
        } else{
        //$data['ward'] = $this->model->ward_list();
        return view('property/citizen/pay_Property_Tax');
        }
    }*/

    /*public function citizen_due_details($id=null)
    {
        $data =(array)null;
        $data['id']=$id;
        $data['basic_details'] = $this->modelprop->basic_details($data);
        $data['owner_details'] = $this->modelowner->owner_details($data);
        $data['tax_list'] = $this->modeltax->tax_list($data);
        $data['demand_detail'] = $this->modeldemand->demand_detail($data);
        //print_r($data);
        return view('property/citizen/citizen_due_details',$data);
    }*/

    /*public function citizen_property_details($id=null)
    {
        $data =(array)null;
        $data['id']=$id;
        $data['basic_details'] = $this->modelprop->basic_details($data);
        $data['owner_details'] = $this->modelowner->owner_details($data);
        $data['tax_list'] = $this->modeltax->tax_list($data);
        $data['demand_detail'] = $this->modeldemand->demand_detail($data);
        $data['occupancy_detail'] = $this->modelfloor->occupancy_detail($data);
        $data['payment_detail'] = $this->modelpay->payment_detail($data);
        return view('property/citizen/citizen_property_details', $data);
    }

    public function citizen_payment_details($id=null)
    {
        $data =(array)null;
        $data['id']=$id;
        $data['basic_details'] = $this->modelprop->basic_details($data);
        $data['owner_details'] = $this->modelowner->owner_details($data);
        $data['tax_list'] = $this->modeltax->tax_list($data);
        $data['payment_detail'] = $this->modelpay->payment_detail($data);
        return view('property/citizen/citizen_payment_details', $data);
    }

    public function citizen_confirm_payment($id=null)
    {
        $data =(array)null;
        $data['id']=$id;
        if($data['id']==""){
        helper(['form']);
        if($this->request->getMethod()=='post'){

            $data = [
                        'custm_id' => $this->request->getVar('custm_id'),
                        'due_upto_year' => $this->request->getVar('due_upto_year'),
                        'date_upto_qtr' => $this->request->getVar('date_upto_qtr'),
                        'total_rebate' => $this->request->getVar('total_rebate'),
                        'total_payable' => $this->request->getVar('total_payable'),
                        'payment_mode' => $this->request->getVar('payment_mode'),
                        'from_fy_year' => $this->request->getVar('from_fy_year'),
                        'from_fy_qtr' => $this->request->getVar('from_fy_qtr'),
                        'total_qrt_pnlty' => $this->request->getVar('total_qrt_pnlty'),
                        'total_qrt' => $this->request->getVar('total_qrt'),
                        'totl_dmnd' => $this->request->getVar('totl_dmnd'),
                        'tl_qtr' => $this->request->getVar('tl_qtr')

                    ];
            $data['date'] = date('Y-m-d');
            $data['insertPayment'] = $this->modelpay->insertPayment($data);
            return $this->response->redirect(base_url('Home/pay_Property_Tax'));
            //return view('property/citizen/pay_Property_Tax');
        }
        }else{
        $data['basic_details'] = $this->modelprop->basic_details($data);
        $data['owner_details'] = $this->modelowner->owner_details($data);
        $data['tax_list'] = $this->modeltax->tax_list($data);
        $data['demand_detail'] = $this->modeldemand->demand_detail($data);
        $data['tran_mode'] = $this->modeltran->getTranModeList();
        return view('property/citizen/citizen_confirm_payment', $data);
        }
    }

    public function dcb()
    {
        $dcb = $this->model_all_module_dcb->total_dcbprop();
        $total_collection = $dcb['p_collection'];
        $total_balance = $dcb['p_balance'];
        $consucoll = $dcb['w_collection'];
        $consublnc = $dcb['w_balance'];
        $trdconsucoll = $dcb['t_collection'];
        $data['propdcb'] = [$total_collection,$total_balance];
        $data['waterdcb'] = [$consucoll,$consublnc];
        $data['tradedcb'] = [$trdconsucoll];

        $response = ['response'=>true, 'propdcb'=>$data['propdcb'],'wtrdcb'=>$data['waterdcb'],'tradedcb'=>$data['tradedcb']];

        echo json_encode($response);

    }

    public function dcbbyulbid()
    {

        $data=[];
        $data['ulb_mstr_id']=$this->ulb_id;
        $dcb = $this->model_all_module_dcb->total_dcbpropbyulbid($data['ulb_mstr_id']);
        $total_collection = $dcb['p_collection'];
        $total_balance = $dcb['p_balance'];
        $consucoll = $dcb['w_collection'];
        $consublnc = $dcb['w_balance'];
        $trdconsucoll = $dcb['t_collection'];
        $data['propdcb'] = [$total_collection,$total_balance];
        $data['waterdcb'] = [$consucoll,$consublnc];
        $data['tradedcb'] = [$trdconsucoll];

        $response = ['response'=>true, 'propdcb'=>$data['propdcb'],'wtrdcb'=>$data['waterdcb'],'tradedcb'=>$data['tradedcb']];

        if($this->request->getMethod()=='get' || $this->request->getMethod()=='post')
        {
            $inputs = filterSanitizeStringtoUpper($this->request->getVar());
            $temp['fy'] = $inputs['fy_mstr_id']??getFY();
            $prop_dcb = $this->getSummaryData($temp);
            $water_dcb= $this-> water_dcb_Ajax($temp,'true');
            $data['propdcb'] = [$prop_dcb['data']['current_collection'],$prop_dcb['data']['current_demand']];
            $data['waterdcb'] = [$water_dcb['current_coll'],$water_dcb['current_demand']];
            $response = ['response'=>true, 'propdcb'=>$data['propdcb'],'wtrdcb'=>$data['waterdcb'],'tradedcb'=>$data['tradedcb']];
        }

        echo json_encode($response);

    }
    public function getSummaryData($args=array())
    {
        if(!empty($args))
        {
            $inputs=$args;
        }
        elseif($this->request->getMethod()=='get' || $this->request->getMethod()=='post')
        {
            $inputs = filterSanitizeStringtoUpper($this->request->getVar());
        }
        $out=["status"=> false];
        if(!empty($args) || $this->request->getMethod()=='get' || $this->request->getMethod()=='post' )
        {
            //$inputs = filterSanitizeStringtoUpper($this->request->getVar());

            $data=$this->DashboardModel->getSummaryData($inputs["fy"]);
            $monthly_collection=$this->DashboardModel->getMonthlyCollection($inputs["fy"]);

            if(is_array($data) && sizeof($data)>0)
            {

                $data["today_collection"] = $monthly_collection[0]["today_collection"] ?? 0;
                $data["last7day_collection"] = $monthly_collection[0]["last7day_collection"] ?? 0;
                $data["thismonth_collection"] = $monthly_collection[0]["thismonth_collection"] ?? 0;
                $out=["status"=> true, "message"=> "success", "data"=> $data, "monthly_collection"=> $monthly_collection];

            }
            else
            {

                $out=["status"=> false, "message"=> "No data found in our record"];
            }

        }
        else
        {
            $out=["status"=> false, "message"=> "Only POST method allowed"];
        }

        return $out;
    }

    public function water_dcb_Ajax($args=array(),$from=null)
    {
        $data=[];
        $out=["status"=> false];
        if(!empty($args))
        {
            $inputs=$args;
        }
        elseif($this->request->getMethod()=='get' || $this->request->getMethod()=='post')
        {
            $inputs = filterSanitizeStringtoUpper($this->request->getVar());
        }
        if(!empty($args) || $this->request->getMethod()=='get' || $this->request->getMethod()=='post' )
        {
            //$inputs = filterSanitizeStringtoUpper($this->request->getVar());
            $fy_yer = explode('-',$inputs['fy']);
            $from_date = $fy_yer[0].'-04-01';
            $to_date = $fy_yer[1].'-03-31';

            $sql_dcb ="with demand as(
                        SELECT
                                sum(
                                    CASE
                                        WHEN (cd.demand_upto < '$from_date')
                                            THEN cd.amount
                                            ELSE 0
                                        END
                                    ) AS arrear_demand,
                                sum(
                                    CASE
                                        WHEN (cd.demand_upto < '$from_date') and cd.paid_status=0
                                            THEN cd.amount
                                            ELSE 0
                                        END
                                    ) AS arrear_due,
                                sum(
                                    CASE
                                        WHEN ((cd.demand_upto >= '$from_date')
                                            AND (cd.demand_upto < '$to_date'))
                                            THEN cd.amount
                                            ELSE 0
                                        END
                                    ) AS curr_demand,
                                sum(
                                    CASE
                                        WHEN ((cd.demand_upto >= '$from_date')
                                            AND (cd.demand_upto < '$to_date')) and cd.paid_status=0
                                            THEN cd.amount
                                            ELSE 0
                                        END
                                    ) AS curr_due,
                                sum(
                                    CASE
                                        WHEN  cd.demand_upto <= '$to_date'
                                            THEN cd.amount
                                            ELSE 0
                                        END
                                    ) AS total_demand
                        FROM tbl_consumer_demand cd
                        WHERE cd.status = 1
                    ),

                    collection as (
                        select
                        sum ( case when t.transaction_date >= '$from_date' and t.transaction_date < '$to_date'
                                then cl.amount else 0 end
                            )as current_coll,
                        sum ( case when t.transaction_date < '$from_date'
                                then cl.amount else 0 end
                            )as arrear_coll,
                        sum(case when  t.transaction_date < '$to_date'
                                then cl.amount else 0 end
                        )as total_coll,
                        sum(case when t.transaction_date >= '$from_date' and t.transaction_date < '$to_date'
                                and cd.demand_upto<='2021-03-31'
                                then cd.amount else 0 end
                        )as c_a_coll,
                        sum(case when t.transaction_date >= '$from_date' and t.transaction_date < '$to_date'
                                and cd.demand_upto >= '$from_date' and cd.demand_upto < '$to_date'
                                then cd.amount else 0 end
                        )as c_c_coll
                        from tbl_consumer_collection cl
                        join tbl_consumer_demand cd on cd.id = cl.demand_id and cd.status=1
                        join tbl_transaction t on t.id = cl.transaction_id  and t.status=1
                        where cl.status = 1

                    )


                    select

                        COALESCE(
                            (COALESCE
                                (d.arrear_demand, (0)::numeric) - COALESCE(cl.arrear_coll, (0)::numeric)
                            ), (0)::numeric
                        ) AS outstanding_at_begin,
                        COALESCE(d.curr_demand, (0)::numeric) AS current_demand,
                        COALESCE(d.arrear_demand, (0)::numeric) AS arrear_demand,
                        COALESCE(cl.current_coll, (0)::numeric) AS current_coll,
                        COALESCE(cl.arrear_coll, (0)::numeric) AS prev_coll,

                        (
                            (COALESCE(
                                (
                                    COALESCE(d.arrear_demand, (0)::numeric)
                                        -
                                    COALESCE(cl.arrear_coll, (0)::numeric)
                                ), (0)::numeric)
                            )
                            +
                            (COALESCE(d.curr_demand, (0)::numeric) - COALESCE(cl.current_coll, (0)::numeric))
                        )as outstanding,

                        (COALESCE((COALESCE(d.arrear_demand, (0)::numeric) - COALESCE(cl.arrear_coll, (0)::numeric)), (0)::numeric)
                        +
                        COALESCE(d.curr_demand, (0)::numeric)) as c_p_demand,
                        COALESCE(cl.c_a_coll, (0)::numeric)as c_a_coll,COALESCE(cl.c_c_coll, (0)::numeric)as c_c_coll,
                        (COALESCE(
                                    (COALESCE
                                        (d.arrear_demand, (0)::numeric) - COALESCE(cl.arrear_coll, (0)::numeric)
                                    ), (0)::numeric
                                )
                            -
                            COALESCE(cl.c_a_coll, (0)::numeric)
                        ) as old_due,
                        ((COALESCE(d.curr_demand, (0)::numeric))-COALESCE(cl.c_c_coll, (0)::numeric)) as curr_due


                    from  demand d
                    left join collection cl on 1=1 ";//print_var($sql_dcb);
            $dcb = $this->water_report_model->getApplicationFormDetail('',$sql_dcb);

            if(is_array($dcb) && sizeof($dcb)>0)
            {

                $data ['current_demand'] =$dcb['current_demand']??0;
                $data ['outstanding_at_begin'] =$dcb['outstanding_at_begin']??0;
                $data ['arrear_demand'] =$dcb['arrear_demand']??0;

                $data ['current_coll'] =$dcb['current_coll']??0;
                $data ['prev_coll'] =$dcb['prev_coll']??0;
                $data ['outstanding'] =$dcb['outstanding']??0;

                $data ['c_p_demand'] =$dcb['c_p_demand']??0;
                $data ['c_c_coll'] =$dcb['c_c_coll']??0;
                $data ['old_due'] =$dcb['old_due']??0;
                $data ['curr_due'] =$dcb['curr_due']??0;
                if($from!=null)
                {
                    // print_var($data);
                    return $data;
                }

                $out=["status"=> true, "message"=> "success", "data"=> $data];

                // $out=["status"=> true, "message"=> "success", "data"=> $data, "monthly_collection"=> $monthly_collection];

            }
            else
            {

                $out=["status"=> false, "message"=> "No data found in our record"];
            }

        }
        else
        {
            $out=["status"=> false, "message"=> "Only POST method allowed"];
        }

        return json_encode($out);
    }*/

    //--------------------------------------------------------------------

    public function screenreader()
    {
        return view('home/screenreader');
    }

    public function holiday()
    {
        return view('home/holiday');
    }

    public function website_policy()
    {
        return view('home/website_policy');
    }
    public function sitemap()
    {
        return view('home/sitemap');
    }
    public function faq()
    {
        return view('home/faq');
    }
    public function gallery()
    {
        return view('home/gallery');
    }
}