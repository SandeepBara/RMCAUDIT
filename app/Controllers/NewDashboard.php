<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\ConstructionTypeModel;
use App\Models\model_prop_demand;
use App\Models\model_dashboard_data;
use App\Models\model_dashboard_daily_collection;
use App\Models\model_transaction;
use App\Models\model_fy_mstr;
use App\Models\model_login_details;
use App\Models\model_emp_details;
use App\Models\model_prop_dtl;
use App\Models\model_collection;
use App\Models\model_saf_demand;
use App\Models\model_saf_dtl;
use App\Models\model_saf_geotag_upload_dtl;
use App\Models\model_saf_collection;
use App\Models\model_level_pending_dtl;
use App\Models\model_saf_memo_dtl;
use App\Models\TradeTransactionModel;
use App\Models\TradeApplyLicenceModel;
use App\Models\model_ulb_mstr;
use App\Models\model_apply_water_connection;
use App\Models\Water_Transaction_Model;
use App\Models\model_consumer;
use App\Models\model_water_dashboard_data;
use App\Models\DashboardModel;


// water models
use App\Models\WaterReportModel;

class NewDashboard extends AlphaController
{
    protected $db;
	protected $model_prop_demand;
	protected $model_dashboard_data;
	protected $model_dashboard_daily_collection;
	protected $model_transaction;
	protected $model_ulb_mstr;
	protected $model_login_details;
	protected $model_emp_details;
	protected $model_fy_mstr;
	protected $model_prop_dtl;
	protected $model_collection;
	protected $model_saf_demand;
	protected $model_saf_dtl;
	protected $model_saf_geotag_upload_dtl;
	protected $model_level_pending_dtl;
	protected $model_saf_collection;
	protected $model_saf_memo_dtl;
	protected $TradeTransactionModel;
	protected $TradeApplyLicenceModel;
	
	protected $model_apply_water_connection;
	protected $Water_Transaction_Model;
	protected $model_consumer;
	protected $model_water_dashboard_data;
	protected $modelUlb;
	protected $DashboardModel;
	
    public function __construct()
	{
        parent::__construct();
    	helper(['db_helper','form_helper']);
		

        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name); 
        }
		if($db_name = dbConfig("trade")){
            $this->dbtrade = db_connect($db_name); 
        }
		if($db_name = dbConfig("water")){
            $this->dbwater = db_connect($db_name); 
        }
		 
		if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        } 
		
		$this->model_prop_demand = new model_prop_demand($this->db);
		$this->model_dashboard_data = new model_dashboard_data($this->db);
		$this->model_dashboard_daily_collection = new model_dashboard_daily_collection($this->db);
		$this->model_transaction = new model_transaction($this->db);
		$this->modelUlb = new model_ulb_mstr($this->dbSystem);
		$this->model_login_details = new model_login_details($this->dbSystem);
		$this->model_emp_details = new model_emp_details($this->dbSystem);
		$this->modelfy = new model_fy_mstr($this->dbSystem);
		$this->model_prop_dtl = new model_prop_dtl($this->db);
		$this->model_collection = new model_collection($this->db);
		$this->model_saf_memo_dtl = new model_saf_memo_dtl($this->db);
		$this->model_saf_demand = new model_saf_demand($this->db);
		$this->model_saf_dtl = new model_saf_dtl($this->db);
		$this->model_saf_geotag_upload_dtl = new model_saf_geotag_upload_dtl($this->db);
		$this->model_level_pending_dtl = new model_level_pending_dtl($this->db);
		$this->model_saf_collection = new model_saf_collection($this->db);
		$this->TradeTransactionModel = new TradeTransactionModel($this->dbtrade);
		$this->TradeApplyLicenceModel = new TradeApplyLicenceModel($this->dbtrade);
		
		$this->model_apply_water_connection = new model_apply_water_connection($this->dbwater);
		$this->Water_Transaction_Model = new Water_Transaction_Model($this->dbwater);
		$this->model_consumer = new model_consumer($this->dbwater);
		$this->model_water_dashboard_data = new model_water_dashboard_data($this->dbwater);
		$this->DashboardModel = new DashboardModel($this->db);

		$this->water_report_model=new WaterReportModel($this->dbwater);
		
    }

	public function index(){
		$data=[];
		$Session = Session();
		$data["ulb_mstr"] = $Session->get("ulb_dtl");
		// print_var($data);		
		return view('property/dashboard/index', $data);
	}

	public function getSummaryData()
	{
		
		$out=["status"=> false];
		if($this->request->getMethod()=='get' || $this->request->getMethod()=='post' )	
		{
			$inputs = filterSanitizeStringtoUpper($this->request->getVar());
			
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
		echo json_encode($out);
	}

	public function Water_Dashbord_index()
	{
		$data=[];
		$Session = Session();
		$data["ulb_mstr"] = $Session->get("ulb_dtl");
		$data['fy_year_list']=fy_year_list();
		return view('property/dashboard/water_dashbord', $data);
	}
	public function Water_Dashbord()
	{
		$data=[];
		$Session = Session();
		$data["ulb_mstr"] = $Session->get("ulb_dtl");
		//print_var($data);die;
		//return view('property/dashboard/index', $data);
		
		$out=["status"=> false];
		if($this->request->getMethod()=='get' || $this->request->getMethod()=='post' )	
		{
			
			$inputs = filterSanitizeStringtoUpper($this->request->getVar());
			$fy_yer = explode('-',$inputs['fy']);
			$from_date = $fy_yer[0].'-04-01';
			$to_date = $fy_yer[1].'-03-31';
			$where1=" and apply_date between '$from_date' and '$to_date'";
			$where2=" and m.created_on::date between '".$from_date."' and '".$to_date."'";			
			$data = $this->water_report_model->getApplicationFormDetail($where1);
			$level_pending_detail=$this->water_report_model->levelPendingFormDetail($where2);			
			$consumer_sql = " select case when status =1 then count(id)  else 0 end as new_consumer, 
									case when status =2 then count(id)  else 0 end as deativate_consumer
								from tbl_consumer where status in (1,0) and created_on between '$from_date' and '$to_date' group by status";			
												
			$consuer = $this->water_report_model->getApplicationFormDetail('',$consumer_sql);			
			//$data=$this->DashboardModel->getSummaryData($inputs["fy"]);
			$collection_sql = " select COALESCE(sum (paid_amount),0) as paid_amount 
								from tbl_transaction 
								where transaction_date  between '$from_date' and '$to_date' 
								and status	in(1,2)";
			$fy_total_transection_sum = $this->water_report_model->getApplicationFormDetail('',$collection_sql);		
			
			$sql_today_collection = " select  COALESCE(sum (paid_amount),0) as today_transection 
									 from tbl_transaction
									 where transaction_date = cast(now() as date) and status in (1,2)";
			//print_var($level_pending_detail);die;
			$today_collection = $this->water_report_model->getApplicationFormDetail('',$sql_today_collection);
			// $monthly_collection=$this->DashboardModel->getMonthlyCollection($inputs["fy"]);
			$todate = date_create(date('Y-m-d'));
			$t_date=date('Y-m-d');			
			$priv7day = date_add($todate, date_interval_create_from_date_string('-7 days'));
			$pri7days = date_format($priv7day,'Y-m-d');
			$sql7 = " select  COALESCE(sum (paid_amount),0) as last7day_collection 
									 from tbl_transaction
									 where transaction_date between '$pri7days' and  '$t_date' and status in (1,2)";
			$last7day_collection = $this->water_report_model->getApplicationFormDetail('',$sql7);
			//print_var($today_collection);die;
			$strat = date('Y-m').'-01';
			$days = cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y'));
			$end = date('Y-m')."-$days";
			//print_var($end);
			
			$sql_thismonth_collection = " select  COALESCE(sum (paid_amount),0) as this_month 
											from tbl_transaction
											where transaction_date between '$strat' and  '$end' and status in (1,2) ";
			$thismonth_collection = $this->water_report_model->getApplicationFormDetail('',$sql_thismonth_collection);
			$dcb = $this->water_dcb_Ajax('true');
			$monthly = $this->water_monthly_collection_ajax('true');
			
			if(is_array($data) && sizeof($data)>0) 
			{
				$data['new_consumer']=$consuer['new_consumer']??0;
				$data['total_apply']=($data['new_connection']??0)+($data['regularization']??0);
				$data['deativate_consumer']=$consuer['deativate_consumer']??0;
				$data["today_collection"] = $today_collection["today_transection"] ?? 0;
				$data["last7day_collection"] = $last7day_collection["last7day_collection"] ?? 0; 
				$data["thismonth_collection"] = $thismonth_collection["this_month"] ?? 0; 
				$data["no_of_verification"] = $level_pending_detail["approved"] ?? 0; 
				$data["rejected"] = $level_pending_detail["rejected"] ?? 0; 
				$data["level_pending"] = $level_pending_detail["level_pending"] ?? 0;
				// $data ['current_demand'] =$dcb['current_demand']??0;
				// $data ['current_demand'] =$dcb['current_demand']??0;
				// $data ['current_demand'] =$dcb['current_demand']??0;

				$out=["status"=> true, "message"=> "success", "data"=> $data,'dcb'=>$dcb, "monthly_collection"=> $monthly];

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
		//print_var($out);
		echo json_encode($out);
	}

	public function water_dcb_Ajax($from=null)
	{
		$data=[];
		// $Session = Session();
		// $data["ulb_mstr"] = $Session->get("ulb_dtl");
		//print_var($data);
		//return view('property/dashboard/index', $data);
		
		$out=["status"=> false];
		if($this->request->getMethod()=='get' || $this->request->getMethod()=='post' )	
		{
			$inputs = filterSanitizeStringtoUpper($this->request->getVar());
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
	}

	public function water_monthly_collection_ajax($from=null)
	{
		$out=["status"=> false];
		if($this->request->getMethod()=='get' || $this->request->getMethod()=='post' )	
		{
			$inputs = filterSanitizeStringtoUpper($this->request->getVar());
			$fy_yer = explode('-',$inputs['fy']);
			$from_date = $fy_yer[0].'-04-01';
			$to_date = $fy_yer[1].'-03-31';
			$sql_monthly_collection = "with temp as(
										select  count(id) as no_of_trxn,
											COALESCE(sum (paid_amount),0) as total_amount ,
											--substring(cast(transaction_date as varchar),6,2) as month,
											case when substring(cast(transaction_date as varchar),6,2)='01' then 'Jan '||'$fy_yer[1]' 
												when substring(cast(transaction_date as varchar),6,2)='02' then 'Feb '||'$fy_yer[1]' 
												when substring(cast(transaction_date as varchar),6,2)='03' then'Mar '||'$fy_yer[1]' 
												when substring(cast(transaction_date as varchar),6,2)='04' then 'Apr '||'$fy_yer[0]' 
												when substring(cast(transaction_date as varchar),6,2)='05' then 'May '||'$fy_yer[0]' 
												when substring(cast(transaction_date as varchar),6,2)='06' then 'Jun '||'$fy_yer[0]' 
												when substring(cast(transaction_date as varchar),6,2)='07' then 'Jul '||'$fy_yer[0]' 
												when substring(cast(transaction_date as varchar),6,2)='08' then 'Aug '||'$fy_yer[0]' 
												when substring(cast(transaction_date as varchar),6,2)='09' then 'Sep '||'$fy_yer[0]' 
												when substring(cast(transaction_date as varchar),6,2)='10' then 'Oct '||'$fy_yer[0]'
												when substring(cast(transaction_date as varchar),6,2)='11' then 'Nov '||'$fy_yer[0]' 
												when substring(cast(transaction_date as varchar),6,2)='12' then 'Dec '||'$fy_yer[0]'

												end as month,
											case when (substring(cast(transaction_date as varchar),6,2)::int -3)>0 then substring(cast(transaction_date as varchar),6,2)::int -3
												when (substring(cast(transaction_date as varchar),6,2)::int -3)<=0 then (substring(cast(transaction_date as varchar),6,2)::int -3)+12
												end as row
										from tbl_transaction
										where transaction_date between '$from_date' and '$to_date' and status in (1,2)
										group by substring(cast(transaction_date as varchar),6,2) 
									)
									select * from temp order by row";
			$monthly_collection=$this->model_water_dashboard_data->rowquery($sql_monthly_collection);
			if(is_array($monthly_collection) && sizeof($monthly_collection)>0) 
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
					return $monthly_collection;
				}

				$out=["status"=> true, "message"=> "success", "monthly_collection"=> $monthly_collection];

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
		
	}

	public function tcVisitingDashboard($module=1){
		$sql = "SELECT id,
					REGEXP_REPLACE(concat(emp_name,' ',middle_name,' ', last_name),'\s+', ' ', 'g') as name
				FROM view_emp_details 
				WHERE user_type_mstr_id = 5 and status =1 and lock_status=0
				ORDER BY concat(emp_name,' ',middle_name,' ', last_name) ";
		$sql2 = "SELECT id,
					REGEXP_REPLACE(concat(emp_name,' ',middle_name,' ', last_name),'\s+', ' ', 'g') as name
				FROM view_emp_details 
				WHERE user_type_mstr_id = 5 and status =1 and lock_status=1
				ORDER BY concat(emp_name,' ',middle_name,' ', last_name) ";
		$data["fyear_month"]=[];
		$stardData = (explode("-",getFY())[0])."-04-01";
		while($stardData <= date("Y-m-d")){
			array_push($data["fyear_month"],(date("Y-m", strtotime($stardData))));
			$stardData = date("Y-m-d",strtotime($stardData."+1 Month"));
		}
		$data["tcList"] = $this->dbSystem->query($sql)->getResultArray();
		$data["tcList2"] = $this->dbSystem->query($sql2)->getResultArray();
		#water
		if($module==3){
			return view("property/dashboard/TcVisitingWaterDashboard",$data);
		}
		#trade
		if($module==4){
			return view("property/dashboard/TcVisitingTradeDashboard",$data);
		}
		return view("property/dashboard/TcVisitingDashboard",$data);
	}
	
	public function ajaxTcVisitingDashboard(){
		$data = $inputs = $this->request->getVar();
	
		$respons = [
			"status"=>false,
			"data"=>$data,
			"error"=>"",
			"method"=>$this->request->getMethod(),
		];
		if($this->request->getMethod()=="post"){
			$sql = "WITH visiting AS (
						SELECT  tbl_visiting_dtl.emp_id,
							concat(view_emp_details.emp_name,' ',view_emp_details.middle_name,' ',view_emp_details.last_name),
							tbl_visiting_dtl.ref_no,tbl_visiting_dtl.ref_type_id,tbl_visiting_dtl.module_id,
							tbl_visiting_dtl.remarks_id,
							tbl_visiting_dtl.id
						FROM tbl_visiting_dtl
						JOIN view_emp_details ON view_emp_details.id = tbl_visiting_dtl.emp_id
						WHERE tbl_visiting_dtl.module_id in(1,2)
							".(isset($inputs["fromDate"]) && isset($inputs["uptoDate"]) ? (" AND tbl_visiting_dtl.created_on::DATE BETWEEN '".$inputs["fromDate"]."' AND '".$inputs["uptoDate"]."'"):"")."
							".(isset($inputs["emp_id"]) ? (" AND view_emp_details.id in( ".implode(",",$inputs["emp_id"]).")"):"")."
					)
					SELECT tbl_visiting_remarks.id,tbl_visiting_remarks.remarks,
							COUNT(visiting.id)
					FROM tbl_visiting_remarks
					LEFT JOIN visiting ON visiting.remarks_id = tbl_visiting_remarks.id
					WHERE tbl_visiting_remarks.module_id IN (1,2)
					GROUP BY tbl_visiting_remarks.id
					ORDER BY count(visiting.id) DESC
				";
			$respons["result"] = $this->dbSystem->query($sql)->getResultArray();
			$respons["status"]=true;
			
		}
		else{
			$respons["status"]=false;
			$respons["error"]="Only Post Allow";
		}
		return json_encode($respons);
	}
	
	public function ajaxTcVisitingDashboardHH(){
		$data = $inputs = $this->request->getVar();
		$respons = [
			"status"=>false,
			"data"=>$data,
			"error"=>"",
			"method"=>$this->request->getMethod(),
		];
		try{
			if($this->request->getMethod()=="post"){
				$sql = "with wards as (
							select distinct (ward_mstr_id) ward_mstr_id,ward_no
							from view_ward_permission
							join view_ward_mstr on view_ward_mstr.id = view_ward_permission.ward_mstr_id
							where view_ward_permission.status =1 and view_ward_mstr.ulb_mstr_id = 1
							 ".((isset($inputs["emp_id"]) ? (" AND view_ward_permission.emp_details_id IN(".implode(",",$inputs["emp_id"]).")"):""))."	
						),
						due_demands as(
							select prop_dtl_id,sum(balance)as balance
							from tbl_prop_demand
							join tbl_prop_dtl on tbl_prop_dtl.id = tbl_prop_demand.prop_dtl_id
							join wards ON wards.ward_mstr_id = tbl_prop_dtl.ward_mstr_id
							where tbl_prop_demand.status =1 and tbl_prop_demand.paid_status =0
							group by prop_dtl_id
						),
						tbl_visiting_dtl as(
							SELECT id, ref_no, ref_type_id, remarks_id, other_remarks, module_id, ip_address,
								address, latitude,longitude, emp_id, created_on, status, transaction_id
							FROM tbl_visiting_dtl
							where module_id in (2) 
							".(isset($inputs["fromDate"]) && isset($inputs["uptoDate"]) ? (" AND tbl_visiting_dtl.created_on::DATE BETWEEN '".$inputs["fromDate"]."' AND '".$inputs["uptoDate"]."'"):"")."							
						),
						trans as (
							select case 
										when tbl_transaction.from_fyear = tbl_transaction.upto_fyear and tbl_transaction.from_fyear = get_fy('".$inputs["fromDate"]."')then 1 --current only
										when tbl_transaction.upto_fyear < get_fy('".$inputs["fromDate"]."')then 2 --arrear only
										when due_demands.balance<=0 or due_demands.balance is null then 3 -- full payment
										else 4 -- arrear + crrent
									end as payment_type,
								payable_amt,demand_amt,tbl_visiting_dtl.id
							from tbl_transaction
							join tbl_visiting_dtl ON tbl_visiting_dtl.transaction_id = tbl_transaction.id
							left join due_demands on due_demands.prop_dtl_id = tbl_transaction.prop_dtl_id
							
						)
						select 
							count(distinct(wards.ward_mstr_id))as ward_alloted,
							string_agg(distinct(wards.ward_no),', ') as ward_no_alloted,
							count(distinct(tbl_prop_dtl.id)) as total_hh,
							count((CASE WHEN due_demands.prop_dtl_id is null then 1 else null end)) as total_full_payid_hh,
							count(distinct(tbl_visiting_dtl.ref_type_id)) as visit_hh,
							count(distinct(CASE WHEN tbl_visiting_dtl.transaction_id is NOT NULL THEN tbl_visiting_dtl.ref_type_id END)) as payment_visit_hh,
							count(case when trans.payment_type=1 then 1 end)as current_payment,
							count(case when trans.payment_type=2 then 1 end)as arrear_payment,
							count(case when trans.payment_type=3 then 1 end)as full_payment,
							count(case when trans.payment_type=4 then 1 end)as current_arrear_payment,
							sum(trans.payable_amt) as amount_collected						
						from wards
						left join tbl_prop_dtl ON wards.ward_mstr_id = tbl_prop_dtl.ward_mstr_id and tbl_prop_dtl.status = 1
						left join due_demands ON due_demands.prop_dtl_id = tbl_prop_dtl.id
						left join tbl_visiting_dtl ON tbl_visiting_dtl.ref_type_id = tbl_prop_dtl.id
						left join trans on trans.id = tbl_visiting_dtl.id			
				";
				// print_var($sql);die;
				$respons["result"] = $this->db->query($sql)->getResultArray();
				$respons["status"]=true;
				
			}
			else{
				$respons["status"]=false;
				$respons["error"]="Only Post Allow";
			}
	
		}
		catch(Exception $e){
			$respons["status"]=false;
			$respons["error"]=$e->getMessage();
		}
		return json_encode($respons);
	}
	
	public function ajaxTcVisitingWaterDashboard(){
	
		$data = $inputs = $this->request->getVar();
	
		$respons = [
			"status"=>false,
			"data"=>$data,
			"error"=>"",
			"method"=>$this->request->getMethod(),
		];
		if($this->request->getMethod()=="post"){
			$sql = "WITH visiting AS (
						SELECT  tbl_visiting_dtl.emp_id,
							concat(view_emp_details.emp_name,' ',view_emp_details.middle_name,' ',view_emp_details.last_name),
							tbl_visiting_dtl.ref_no,tbl_visiting_dtl.ref_type_id,tbl_visiting_dtl.module_id,
							tbl_visiting_dtl.remarks_id,
							tbl_visiting_dtl.id
						FROM tbl_visiting_dtl
						JOIN view_emp_details ON view_emp_details.id = tbl_visiting_dtl.emp_id
						WHERE tbl_visiting_dtl.module_id in(3)
							".(isset($inputs["fromDate"]) && isset($inputs["uptoDate"]) ? (" AND tbl_visiting_dtl.created_on::DATE BETWEEN '".$inputs["fromDate"]."' AND '".$inputs["uptoDate"]."'"):"")."
							".(isset($inputs["emp_id"]) ? (" AND view_emp_details.id in( ".implode(",",$inputs["emp_id"]).")"):"")."
					)
					SELECT tbl_visiting_remarks.id,tbl_visiting_remarks.remarks,
							COUNT(visiting.id)
					FROM tbl_visiting_remarks
					LEFT JOIN visiting ON visiting.remarks_id = tbl_visiting_remarks.id
					WHERE tbl_visiting_remarks.module_id IN (3)
					GROUP BY tbl_visiting_remarks.id
					ORDER BY count(visiting.id) DESC
				";
			$respons["result"] = $this->dbSystem->query($sql)->getResultArray();
			$respons["status"]=true;
			
		}
		else{
			$respons["status"]=false;
			$respons["error"]="Only Post Allow";
		}
		return json_encode($respons);
	}
	
	public function ajaxTcVisitingWaterDashboardHH(){
		$data = $inputs = $this->request->getVar();
		$respons = [
			"status"=>false,
			"data"=>$data,
			"error"=>"",
			"method"=>$this->request->getMethod(),
		];
		try{
			if($this->request->getMethod()=="post"){				
				$sql = "with wards as (
							select distinct (view_ward_permission.id) ward_mstr_id,view_ward_mstr.ward_no
							from view_ward_permission
							join view_ward_mstr on view_ward_mstr.id = view_ward_permission.id
							where 1=1 and view_ward_mstr.ulb_mstr_id = 1
							".((isset($inputs["emp_id"]) ? (" AND view_ward_permission.emp_details_id IN(".implode(",",$inputs["emp_id"]).")"):""))."	
											
						),
						due_demands as(
							select consumer_id,sum(balance_amount)as balance
							from tbl_consumer_demand
							join tbl_consumer on tbl_consumer.id = tbl_consumer_demand.consumer_id
							join wards ON wards.ward_mstr_id = tbl_consumer.ward_mstr_id
							where tbl_consumer_demand.status =1 and tbl_consumer_demand.paid_status =0
							group by consumer_id
						),
						tbl_visiting_dtl as(
							SELECT id, ref_no, ref_type_id, remarks_id, other_remarks, module_id, ip_address,
								address, latitude,longitude, emp_id, created_on, status, transaction_id
							FROM tbl_visiting_dtl
							where module_id in (3) 							
							".(isset($inputs["fromDate"]) && isset($inputs["uptoDate"]) ? (" AND tbl_visiting_dtl.created_on::DATE BETWEEN '".$inputs["fromDate"]."' AND '".$inputs["uptoDate"]."'"):"")."
							-- AND tbl_visiting_dtl.created_on::DATE BETWEEN '".$inputs["fromDate"]."' AND (date_trunc('month', '".$inputs["fromDate"]."'::DATE) + interval '1 month' - interval '1 day')::DATE
						),
						trans as (
							select 
									case 
										when current_fy(tbl_transaction.from_month) = current_fy(tbl_transaction.upto_month) and current_fy(tbl_transaction.from_month) = current_fy('".$inputs["fromDate"]."')then 1 --current only
										when current_fy(tbl_transaction.upto_month) < current_fy('".$inputs["fromDate"]."')then 2 --arrear only
										when due_demands.balance<=0 or due_demands.balance is null then 3 -- full payment
										else 4 -- arrear + crrent
									end as payment_type,
								paid_amount as payable_amt,(paid_amount + due_amount) as demand_amt,tbl_visiting_dtl.id
							from tbl_transaction
							join tbl_visiting_dtl ON tbl_visiting_dtl.transaction_id = tbl_transaction.id
							left join due_demands on due_demands.consumer_id = tbl_transaction.related_id
							where tbl_transaction.transaction_type = 'Demand Collection'
							
						),
						meter_consumer as (
							select consumer_id,connection_type,connection_date
							from tbl_meter_status
							join(
								select max(id) as id 
								from tbl_meter_status
								where status =1 
									and connection_date<= (date_trunc('month', '".$inputs["fromDate"]."'::DATE) + interval '1 month' - interval '1 day')::DATE
								group by consumer_id
							)last_meter on last_meter.id = tbl_meter_status.id
						)
	
						select 
							count(distinct(wards.ward_mstr_id))as ward_alloted,							
							string_agg(distinct(wards.ward_no),', ') as ward_no_alloted,
							count(distinct(tbl_consumer.id)) as total_consumer,
							
							count(distinct( case when meter_consumer.connection_type in (1,2) then tbl_consumer.id end)) as total_meter_consumer,
							count(distinct( case when meter_consumer.connection_type not in (1,2) then tbl_consumer.id end)) as total_fixed_consumer,
							
							count(distinct( CASE WHEN tbl_visiting_dtl.remarks_id = 14 AND meter_consumer.connection_type in (1,2) THEN tbl_visiting_dtl.ref_type_id END)) as total_meter_bill_served,
							count(distinct( CASE WHEN tbl_visiting_dtl.remarks_id = 14 AND meter_consumer.connection_type NOT in (1,2) THEN tbl_visiting_dtl.ref_type_id END)) as total_fixed_bill_served,
							
							count(distinct(CASE WHEN tbl_visiting_dtl.transaction_id is NOT NULL AND meter_consumer.connection_type in (1,2) THEN tbl_visiting_dtl.ref_type_id END)) as total_meter_payment,
							count(distinct(CASE WHEN tbl_visiting_dtl.transaction_id is NOT NULL AND meter_consumer.connection_type NOT in (1,2) THEN tbl_visiting_dtl.ref_type_id END)) as total_fixed_payment,
							
							COALESCE(sum((CASE WHEN tbl_visiting_dtl.transaction_id is NOT NULL AND meter_consumer.connection_type in (1,2) THEN trans.payable_amt END)),0) as total_meter_payment_amount,
							COALESCE(sum((CASE WHEN tbl_visiting_dtl.transaction_id is NOT NULL AND meter_consumer.connection_type NOT in (1,2) THEN trans.payable_amt END)),0) as total_fixed_payment_amount,
							
							count(case when trans.payment_type=1 then 1 end)as current_payment,
							count(case when trans.payment_type=2 then 1 end)as arrear_payment,
							count(case when trans.payment_type=3 then 1 end)as full_payment,
							count(case when trans.payment_type=4 then 1 end)as current_arrear_payment,
	
							COALESCE(sum(trans.payable_amt),0) as amount_collected						
						from wards
						left join tbl_consumer ON wards.ward_mstr_id = tbl_consumer.ward_mstr_id and tbl_consumer.status = 1
						left join meter_consumer on meter_consumer.consumer_id = tbl_consumer.id
						left join tbl_visiting_dtl ON tbl_visiting_dtl.ref_type_id = tbl_consumer.id
						left join trans on trans.id = tbl_visiting_dtl.id			
							
				
				";
				// print_var($sql);die;
				$respons["result"] = $this->dbwater->query($sql)->getResultArray();
				$respons["status"]=true;
				
			}
			else{
				$respons["status"]=false;
				$respons["error"]="Only Post Allow";
			}
	
		}
		catch(Exception $e){
			$respons["status"]=false;
			$respons["error"]=$e->getMessage();
		}
		return json_encode($respons);
	}
	
	
	public function ajaxTcVisitingTradeDashboard(){
	
		$data = $inputs = $this->request->getVar();
	
		$respons = [
			"status"=>false,
			"data"=>$data,
			"error"=>"",
			"method"=>$this->request->getMethod(),
		];
		if($this->request->getMethod()=="post"){
			$sql = "WITH visiting AS (
						SELECT  tbl_visiting_dtl.emp_id,
							concat(view_emp_details.emp_name,' ',view_emp_details.middle_name,' ',view_emp_details.last_name),
							tbl_visiting_dtl.ref_no,tbl_visiting_dtl.ref_type_id,tbl_visiting_dtl.module_id,
							tbl_visiting_dtl.remarks_id,
							tbl_visiting_dtl.id
						FROM tbl_visiting_dtl
						JOIN view_emp_details ON view_emp_details.id = tbl_visiting_dtl.emp_id
						WHERE tbl_visiting_dtl.module_id in(4)
							".(isset($inputs["fromDate"]) ? (" AND tbl_visiting_dtl.created_on::DATE BETWEEN '".$inputs["fromDate"]."' AND (date_trunc('month', '".$inputs["fromDate"]."'::DATE) + interval '1 month' - interval '1 day')::DATE"):"")."
							".(isset($inputs["emp_id"]) ? (" AND view_emp_details.id in( ".implode(",",$inputs["emp_id"]).")"):"")."
					)
					SELECT tbl_visiting_remarks.id,tbl_visiting_remarks.remarks,
							COUNT(visiting.id)
					FROM tbl_visiting_remarks
					LEFT JOIN visiting ON visiting.remarks_id = tbl_visiting_remarks.id
					WHERE tbl_visiting_remarks.module_id IN (4)
					GROUP BY tbl_visiting_remarks.id
					ORDER BY count(visiting.id) DESC
				";
			$respons["result"] = $this->dbSystem->query($sql)->getResultArray();
			$respons["status"]=true;
			
		}
		else{
			$respons["status"]=false;
			$respons["error"]="Only Post Allow";
		}
		return json_encode($respons);
	}
	
	public function ajaxTcVisitingTradeDashboardHH(){
		$data = $inputs = $this->request->getVar();
		$respons = [
			"status"=>false,
			"data"=>$data,
			"error"=>"",
			"method"=>$this->request->getMethod(),
		];
		try{
			if($this->request->getMethod()=="post"){				
				$sql = "
					with wards as (
						select distinct (ward_mstr_id) ward_mstr_id
						from view_ward_permission
						where 1 =1 
						".((isset($inputs["emp_id"]) ? (" AND view_ward_permission.emp_details_id IN(".implode(",",$inputs["emp_id"]).")"):""))."										
					),
					approved as (
						select tbl_apply_licence.*,
							case when tbl_apply_licence.application_type_id =1 OR tbl_apply_licence.valid_from >= '2024-06-01' then 1 --renewal_license,
								else 2 -- expired
							end as licence_type
						from tbl_apply_licence
						join (
							select max(id)as id
							from tbl_apply_licence
							where apply_date <=(date_trunc('month', '".$inputs["fromDate"]."'::DATE) + interval '1 month' - interval '1 day')::DATE
							and pending_status =5 and license_no !=''
							group by license_no
						) valid_license on valid_license.id = tbl_apply_licence.id 
					),
					pending_licence as (
						select tbl_apply_licence.*,
							case when tbl_apply_licence.application_type_id =1 OR tbl_apply_licence.valid_from >= '2024-06-01' then 1 --renewal_license,
								else 2 -- expired
							end as licence_type
						from tbl_apply_licence
						left join approved on approved.license_no = tbl_apply_licence.license_no
						where tbl_apply_licence.apply_date <=(date_trunc('month', '".$inputs["fromDate"]."'::DATE) + interval '1 month' - interval '1 day')::DATE
						and tbl_apply_licence.pending_status !=5 and tbl_apply_licence.application_type_id =1
						and approved.id is null
					),
					valide_license as (
						select *
						from approved
						/*union(
							select *
							from pending_licence
						)*/
					),
					valide_license_count as(
						select count(id) counts,
							count(case when licence_type=1 then id end) renewed_counts,
							count(case when licence_type=2 then id end) expired_counts,
							ward_mstr_id
						from valide_license
						group by ward_mstr_id
					),
					tbl_visiting_dtl as(
						SELECT tbl_visiting_dtl.id, tbl_visiting_dtl.ref_no, tbl_visiting_dtl.ref_type_id, 
							tbl_visiting_dtl.remarks_id, tbl_visiting_remarks.remarks, tbl_visiting_dtl.other_remarks, tbl_visiting_dtl.module_id, tbl_visiting_dtl.ip_address,
							tbl_visiting_dtl.address, tbl_visiting_dtl.latitude, tbl_visiting_dtl.longitude, tbl_visiting_dtl.emp_id, 
							tbl_visiting_dtl.created_on, tbl_visiting_dtl.status, tbl_visiting_dtl.transaction_id,
								tbl_apply_licence.ward_mstr_id
						FROM tbl_visiting_dtl
						LEFT join tbl_visiting_remarks on tbl_visiting_remarks.id = tbl_visiting_dtl.remarks_id
						LEFT join tbl_apply_licence on tbl_apply_licence.id =  tbl_visiting_dtl.ref_type_id
						where tbl_visiting_dtl.module_id in (4) 
							".(isset($inputs["fromDate"]) ? (" AND tbl_visiting_dtl.created_on::DATE BETWEEN '".$inputs["fromDate"]."' AND (date_trunc('month', '".$inputs["fromDate"]."'::DATE) + interval '1 month' - interval '1 day')::DATE"):"")."	
					),
					trans as (
						select 
								case 
									when current_fy(tbl_apply_licence.apply_date) =current_fy('".$inputs["fromDate"]."')then 1 --current only
									when current_fy(tbl_apply_licence.apply_date) < current_fy('".$inputs["fromDate"]."')then 2 --arrear only
								end as payment_type,
								case when tbl_apply_licence.application_type_id =1 then 'new'
									when tbl_apply_licence.application_type_id !=1 AND  tbl_apply_licence.valid_from >= '".$inputs["fromDate"]."' then 'renewed'
									else 'expired'
								end as payment_recived,
							paid_amount as payable_amt,(paid_amount) as demand_amt,tbl_visiting_dtl.id
						from tbl_transaction
						join tbl_visiting_dtl ON tbl_visiting_dtl.transaction_id = tbl_transaction.id
						left join tbl_apply_licence on tbl_apply_licence.id = tbl_transaction.related_id
						
					), 
					tc_visited as (
						select tbl_visiting_dtl.*,
								tbl_apply_licence.ward_mstr_id,
							case when tbl_apply_licence.application_type_id =1 OR tbl_apply_licence.valid_from >= '".$inputs["fromDate"]."' then 1
								else 2
							end as licence_visited
						from tbl_visiting_dtl
						join tbl_apply_licence on tbl_apply_licence.id =  tbl_visiting_dtl.ref_type_id
						order by tbl_visiting_dtl.ref_type_id
					)					
					select 
						sum(ward_alloted)as ward_alloted,
						sum(total_renewed)as total_renewed,
						sum(total_expired)as total_expired,
						sum(total_new_payment)as total_new_payment,
						sum(total_renewed_payment)as total_renewed_payment,
						sum(total_expired_payment)as total_expired_payment,
						sum(total_new_payment_amount)as total_new_payment_amount,
						sum(total_renewed_payment_amount)as total_renewed_payment_amount,
						sum(total_expired_payment_amount)as total_expired_payment_amount,	
						sum(amount_collected)as amount_collected,
						sum(current_payment)as current_payment,
						sum(arrear_payment)as arrear_payment,
						sum(valide_license_count.counts) as total_valide_license,
						sum(valide_license_count.renewed_counts) as total_renewed_license,
						sum(valide_license_count.expired_counts) as total_expired_license
					from (
						select wards.ward_mstr_id,
							count(distinct(wards.ward_mstr_id))as ward_alloted,
						
							count(distinct( case when tc_visited.licence_visited=1 then tbl_visiting_dtl.ref_type_id end)) as total_renewed,
							count(distinct( case when tc_visited.licence_visited=2 then tbl_visiting_dtl.ref_type_id end)) as total_expired,
							
							count(distinct(CASE WHEN tbl_visiting_dtl.transaction_id is NOT NULL AND trans.payment_recived='new' THEN tbl_visiting_dtl.ref_type_id END)) as total_new_payment,
							count(distinct(CASE WHEN tbl_visiting_dtl.transaction_id is NOT NULL AND trans.payment_recived='renewed' THEN tbl_visiting_dtl.ref_type_id END)) as total_renewed_payment,		
							count(distinct(CASE WHEN tbl_visiting_dtl.transaction_id is NOT NULL AND trans.payment_recived='expired' THEN tbl_visiting_dtl.ref_type_id END)) as total_expired_payment,
							
							COALESCE(sum((CASE WHEN tbl_visiting_dtl.transaction_id is NOT NULL AND trans.payment_recived='new' THEN trans.payable_amt END)),0) as total_new_payment_amount,
							COALESCE(sum((CASE WHEN tbl_visiting_dtl.transaction_id is NOT NULL AND trans.payment_recived='renewed' THEN trans.payable_amt END)),0) as total_renewed_payment_amount,
							COALESCE(sum((CASE WHEN tbl_visiting_dtl.transaction_id is NOT NULL AND trans.payment_recived='expired' THEN trans.payable_amt END)),0) as total_expired_payment_amount,
							
							count(case when trans.payment_type=1 then 1 end)as current_payment,
							count(case when trans.payment_type=2 then 1 end)as arrear_payment,
						
							COALESCE(sum(trans.payable_amt),0) as amount_collected	
						from wards
						left join tbl_apply_licence on tbl_apply_licence.ward_mstr_id = wards.ward_mstr_id
						left join tbl_visiting_dtl ON tbl_visiting_dtl.ref_type_id = tbl_apply_licence.id
						left join tc_visited ON tc_visited.id =tbl_visiting_dtl.id
						left join trans on trans.id = tbl_visiting_dtl.id
						group by wards.ward_mstr_id
					)temps 
					left join valide_license_count on valide_license_count.ward_mstr_id = temps.ward_mstr_id
				";
				// print_var($sql);die;
				$respons["result"] = $this->dbtrade->query($sql)->getResultArray();
				$respons["status"]=true;
				
			}
			else{
				$respons["status"]=false;
				$respons["error"]="Only Post Allow";
			}
	
		}
		catch(Exception $e){
			$respons["status"]=false;
			$respons["error"]=$e->getMessage();
		}
		return json_encode($respons);
	}

}