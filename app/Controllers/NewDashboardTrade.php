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
use App\Models\DashboardModelTrade;
use App\Models\TradeApplicationTypeMstrModel;


class NewDashboardTrade extends AlphaController
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
	protected $DashboardModelTrade;
    protected $tradeapplicationtypemstrmodel;
	
    public function __construct()
	{
		ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        parent::__construct();
    	helper(['db_helper']);
		

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
		$this->DashboardModelTrade = new DashboardModelTrade($this->dbtrade);
        $this->tradeapplicationtypemstrmodel =  new tradeapplicationtypemstrmodel($this->dbtrade);
    }

	public function index(){
		$data=[];
		$Session = Session();
		$data["ulb_mstr"] = $Session->get("ulb_dtl");
		//print_var($data);
		return view('trade/dashboard/index', $data);
	}

	public function getSummaryData(){

		$out=["status"=> false];
		if($this->request->getMethod()=='get' || $this->request->getMethod()=='post'){

			$inputs = filterSanitizeStringtoUpper($this->request->getVar());
			
			$fyr =explode('-',$inputs['fy']);
			$f1=$fyr[0];
			$f2 = $fyr[1];
			$total_application = $this->DashboardModelTrade->getTotalApply($f1,$f2);
			$total_surrender = $this->DashboardModelTrade->getTotalsurrender($f1,$f2);
			$total_amendment = $this->DashboardModelTrade->getTotalAmendment($f1,$f2);
			$total_renewal = $this->DashboardModelTrade->getTotalRenewal($f1,$f2);
			$getTillNowCollection = $this->DashboardModelTrade->getTillNowCollection($f1,$f2);
			$total_collection = $this->DashboardModelTrade->getTotalCollection($f1,$f2);
			$TodaysCollection = $this->DashboardModelTrade->getTodaysCollection($f1,$f2);

			// $tot_col = array();
			// foreach($total_collection as $key=> $val){
			// 	if($total_collection[$key]['month'] >=4){
			// 		$tot_col[] = $val;
			// 	}

			// }
			// foreach($total_collection as $key=> $val){
			// 	if($total_collection[$key]['month'] <4){
			// 		$tot_col[] = $val;
			// 	}
				
			// }
			// print_var($total_collection);
			// die('sdsd');
			// foreach($total_collection as $val){
				
			// }
			
			if(is_array($total_application) && sizeof($total_application)>0) {
				$data = [
							"total_application" =>$total_application[0]["total"]??0,
							"total_surrender" =>$total_surrender[0]["total"]??0,
							"total_amendment" =>$total_amendment[0]["total"]??0,
							"total_renewal" =>$total_renewal[0]["total"]??0,
							"total_collection" =>$total_collection[0]["total"]??0,
							"tillnow_collection"=>$getTillNowCollection[0]['sum']??0,
							"todays_collection"=>$TodaysCollection[0]['todays_collection']??0,
				];
				$out=["status"=> true, "message"=> "success", "data"=> $data, "total_application"=> $total_collection];

			}
			else {
				
				$out=["status"=> false, "message"=> "No data found in our record"];
			}

		}
		else {
			$out=["status"=> false, "message"=> "Only POST method allowed"];
		}
		echo json_encode($out);
	}

	public function trade_mini_dashboard(){

		$data= array();
		$data["pending_report"]=$this->tradeapplicationtypemstrmodel->getTradeLevelPendingReport();
		$count =$this->tradeapplicationtypemstrmodel->getTradeApplyCount();

		$data["total_application"] = $count;

		// print_var($data);

		return view('trade/dashboard/tradeMiniDashboard', $data);
	}

	// public function tradeLevelPendingReport(){
	// 	$data=array();
	// 	$data["pending_report"]=$this->tradeapplicationtypemstrmodel->getTradeLevelPendingReport();
	// 		// print_r($data["pending_report"]);die();
	// 	return view('trade/Reports/trade_level_pending_report',$data); 
	// }

	public function tradeLicenseStatusDashboard(){
		$data=[];
		$Session = Session();
		$data["ulb_mstr"] = $Session->get("ulb_dtl");
		return view('trade/dashboard/tradeLicenseStatusDashboard',$data);
	}

	public function tradeLicenseStatusAjax(){
		$inputs = $this->request->getVar();
		$fromDte = $uptoDate = null;
		if($this->request->getVar("fromDate") && $this->request->getVar("uptoDate")){
			$fromDte = $this->request->getVar("fromDate");
			$uptoDate = $this->request->getVar("uptoDate");
		}
		$sql = "
			select *
			from (
				select count(id) as total_apply,
					count(case when pending_status !=5 AND current_date -apply_date >21 then id end)as deemed_license,
					count(case when application_type_id = 1 then id else null end ) as new_license,
					count(case when application_type_id = 2 then id else null end ) as renewal_license,
					count(case when application_type_id = 3 then id else null end ) as amendment_license,
					count(case when application_type_id = 4 then id else null end ) as surender_license,
					count(case when pending_status =1 then id else null end ) as pending_at_level,
					count(case when pending_status =2 then id else null end ) as back_to_citizen,
					count(case when payment_status =0 then id else null end ) as pending_at_jsk,
					count(case when payment_status !=0 then id else null end ) as provisanl_license
				from tbl_apply_licence
				where status = 1
				".($fromDte && $uptoDate ? " AND apply_date between '$fromDte' and '$uptoDate' " :"")."
			)a,( select count(id) as total_approved,
					count(case when license_date - apply_date > 21 then id end) deemed_approved,
					count(case when license_date - apply_date <= 21 then id end) normal_approved,
					count(case when application_type_id = 1 then id else null end ) as new_approved,
					count(case when application_type_id = 2 then id else null end ) as renewal_approved,
					count(case when application_type_id = 3 then id else null end ) as amendment_approved,
					count(case when application_type_id = 4 then id else null end ) as surender_approved	
				from tbl_apply_licence
				where status = 1 and pending_status =5
				".($fromDte && $uptoDate ? " AND license_date between '$fromDte' and '$uptoDate' " :"")."
			)b,(
				select count(id) as total_rejected
				from tbl_apply_licence
				join (
					select apply_licence_id, forward_date,
						ROW_NUMBER() OVER(
							PARTITION BY apply_licence_id
							ORDER BY id DESC
						)as row_num
					from tbl_level_pending		
				)tbl_level_pending on tbl_level_pending.apply_licence_id = tbl_apply_licence.id
				where tbl_apply_licence.status = 1 and tbl_apply_licence.pending_status =4 and tbl_level_pending.row_num=1
				".($fromDte && $uptoDate ? " AND tbl_level_pending.forward_date between '$fromDte' and '$uptoDate' " :"")."
			)c
		";
		$result = $this->dbtrade->query($sql)->getFirstRow("array");
		return json_encode(["status"=>true,"result"=>$result]);
	}
	
	public function tradeLicenseStatusDtl(){
		$data = $inputs = $this->request->getVar();
		$fromDte = $uptoDate = null;
		if($this->request->getVar("fromDate") && $this->request->getVar("uptoDate")){
			$fromDte = $this->request->getVar("fromDate");
			$uptoDate = $this->request->getVar("uptoDate");
		}
		$where ="";
		$sql ="";
		if(in_array($inputs["app_type"]??"",["new_license","renewal_license","amendment_license","surender_license","pending_at_level","back_to_citizen","pending_at_jsk","deemed_license","provisanl_license"])){
			if(($inputs["app_type"]??"")=="new_license"){
				$where = " AND (case when tbl_apply_licence.application_type_id = 1 then true else false end)::bool = true ";
			}
			if(($inputs["app_type"]??"")=="renewal_license"){
				$where = " AND (case when tbl_apply_licence.application_type_id = 2 then true else false end)::bool = true ";
			}
			if(($inputs["app_type"]??"")=="amendment_license"){
				$where = " AND (case when tbl_apply_licence.application_type_id = 3 then true else false end )::bool = true ";
			}
			if(($inputs["app_type"]??"")=="surender_license"){
				$where = " AND (case when tbl_apply_licence.application_type_id = 4 then true else false end )::bool = true ";
			}
			if(($inputs["app_type"]??"")=="pending_at_level"){
				$where = " AND (case when tbl_apply_licence.pending_status =1 then true else false end )::bool = true ";
			}
			if(($inputs["app_type"]??"")=="back_to_citizen"){
				$where = " AND (case when tbl_apply_licence.pending_status =2 then true else false end )::bool = true ";
			}
			if(($inputs["app_type"]??"")=="pending_at_jsk"){
				$where = " AND (case when tbl_apply_licence.payment_status =0 then true else false end )::bool = true ";
			}
			if(($inputs["app_type"]??"")=="deemed_license"){
				$where = " AND (case when pending_status !=5 AND current_date -apply_date >21 then true else false end )::bool = true ";
			}
			if(($inputs["app_type"]??"")=="provisanl_license"){
				$where = " AND (case when tbl_apply_licence.payment_status !=0 then true else false end )::bool = true ";
			}
	
			$sql = "select tbl_apply_licence.*,view_ward_mstr.ward_no
				from tbl_apply_licence
				left join view_ward_mstr on view_ward_mstr.id = tbl_apply_licence.ward_mstr_id
				where tbl_apply_licence.status = 1
				".($fromDte && $uptoDate ? " AND tbl_apply_licence.apply_date between '$fromDte' and '$uptoDate' " :"")."
				$where
			";
		}
		if(in_array(($inputs["app_type"]??""),["total_approved","new_approved","renewal_approved","amendment_approved","surender_approved","normal_approved","deemed_approved"])){
			if(($inputs["app_type"]??"")=="total_approved"){
				$where ="";
			}
			if(($inputs["app_type"]??"")=="normal_approved"){
				$where =" AND (case when license_date - apply_date <= 21 then true else false end )::bool = true";
			}
			if(($inputs["app_type"]??"")=="deemed_approved"){
				$where =" AND (case when license_date - apply_date > 21 then true else false end )::bool = true";
			}
			if(($inputs["app_type"]??"")=="new_approved"){
				$where =" AND (case when tbl_apply_licence.application_type_id = 1 then true else false end )::bool = true";
			}
			if(($inputs["app_type"]??"")=="renewal_approved"){
				$where =" AND (case when tbl_apply_licence.application_type_id = 2 then true else false end )::bool = true";
			}
			if(($inputs["app_type"]??"")=="amendment_approved"){
				$where =" AND (case when tbl_apply_licence.application_type_id = 3 then true else false end )::bool = true";
			}
			if(($inputs["app_type"]??"")=="surender_approved"){
				$where =" AND (case when tbl_apply_licence.application_type_id = 4 then true else false end )::bool = true";
			}
	
			$sql = "select tbl_apply_licence.*,view_ward_mstr.ward_no
				from tbl_apply_licence
				left join view_ward_mstr on view_ward_mstr.id = tbl_apply_licence.ward_mstr_id
				where tbl_apply_licence.status = 1 and tbl_apply_licence.pending_status =5
				".($fromDte && $uptoDate ? " AND tbl_apply_licence.license_date between '$fromDte' and '$uptoDate' " :"")."
				$where
			";
	
		}
		if(in_array(($inputs["app_type"]??""),["total_rejected"])){
			$where="";
			$sql ="select tbl_apply_licence.*,view_ward_mstr.ward_no
				from tbl_apply_licence
				left join view_ward_mstr on view_ward_mstr.id = tbl_apply_licence.ward_mstr_id
				join (
					select apply_licence_id, forward_date,
						ROW_NUMBER() OVER(
							PARTITION BY apply_licence_id
							ORDER BY id DESC
						)as row_num
					from tbl_level_pending		
				)tbl_level_pending on tbl_level_pending.apply_licence_id = tbl_apply_licence.id
				where tbl_apply_licence.status = 1 and tbl_apply_licence.pending_status =4 and tbl_level_pending.row_num=1
				".($fromDte && $uptoDate ? " AND tbl_level_pending.forward_date between '$fromDte' and '$uptoDate' " :"")."
				$where
			";
		}
		if(!$sql){
			return redirect()->back()->with('error', "Demand Not Gererated Now Please Wait");
		}
		$data["result"] = $this->dbtrade->query($sql)->getResultArray();
		return view("trade/dashboard/applicationView",$data);
	}
	
	public function tradeLicenseCollectionAjax(){
		$inputs = $this->request->getVar();
		$fromDte = $uptoDate = null;
		if($this->request->getVar("fromDate") && $this->request->getVar("uptoDate")){
			$fromDte = $this->request->getVar("fromDate");
			$uptoDate = $this->request->getVar("uptoDate");
		}
		$sql ="
			select 
					COALESCE(sum(case when transaction_type in ('NEW LICENSE','NEW LICENCE') then paid_amount else 0 end ),0) as new_application_collection,
					count(distinct(case when transaction_type in ('NEW LICENSE','NEW LICENCE') then related_id end )) as total_new_application,
					COALESCE(sum(case when transaction_type in ('RENEWAL') then paid_amount else 0 end ),0) as renewal_application_collection,
					count(distinct(case when transaction_type in ('RENEWAL') then related_id end )) as total_renewal_application,
					COALESCE(sum(case when transaction_type in ('AMENDMENT') then paid_amount else 0 end ),0) as amendment_application_collection,
					count(distinct(case when transaction_type in ('AMENDMENT') then related_id end )) as total_amendment_application,
					COALESCE(sum(case when transaction_type in ('SURRENDER') then paid_amount else 0 end ),0) as surrender_application_collection,
					count(distinct(case when transaction_type in ('SURRENDER') then related_id end )) as total_surrender_application
			from tbl_transaction
			where status in(1,2)
				".($fromDte && $uptoDate ? " AND transaction_date between '$fromDte' and '$uptoDate' " :"")."
		";		
		$result = $this->dbtrade->query($sql)->getFirstRow("array");
		return json_encode(["status"=>true,"result"=>$result]);
	}



}