<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PostModel;
use App\Models\model_ward_mstr;
use App\Models\model_ulb_mstr;
use App\Models\WaterMobileModel;
use App\Models\WaterSearchConsumerMobileModel;
use App\Models\WaterConsumerDemandModel;
use App\Models\WaterPaymentModel;
use App\Models\model_water_consumer_initial_meter;
use App\Controllers\WaterGenerateDemand;
use App\Models\WaterMeterStatusModel;
use App\Models\WaterConsumerInitialMeterReadingModel;
use App\Models\model_view_water_consumer;
use App\Models\WaterConsumerTaxModel;
use App\Models\model_emp_details;
use App\Models\model_visiting_dtl;
use App\Models\Water_Transaction_Model;
use CodeIgniter\HTTP\Response;

class WaterViewConsumerMobile extends MobiController
{
	protected $db;
	protected $dbSystem;
	protected $db_property;
	protected $model;
	//protected $db_name;
	protected $model_ward_mstr;
	protected $model_ulb_mstr;
	protected $ulb_mstr_id;
	protected $emp_details_id;
	protected $user_type_mstr_id;
	protected $generate_demand_controller;
	protected $search_consumer_mobile_model;
	protected $consumer_tax_model;
	protected $demand_model;
	protected $meter_status_model;
	protected $model_view_water_consumer;
	protected $modelemp;
	protected $initial_reading_model;
	protected $Water_Transaction_Model;
	protected $model_visiting_dtl;

	public function __construct()
	{


		$data = (array)null;
		$Session = Session();
		$ulb_mstr = $Session->get("ulb_dtl");
		$this->ulb_mstr_id = $ulb_mstr["ulb_mstr_id"];

		$emp_mstr = $Session->get("emp_details");
		$this->emp_details_id = $emp_mstr["id"];
		$this->user_type_mstr_id = $emp_mstr["user_type_mstr_id"];

		parent::__construct();
		helper(['db_helper','form_helper']);
		if ($db_name = dbConfig("water")) {
			//echo $db_name;
			$this->db = db_connect($db_name);
		}
		// if ($db_name = dbConfig("property")) {
		// 	//echo $db_name;
		// 	$this->db_property = db_connect($db_name);
		// }
		if ($db_system = dbSystem()) {
			$this->dbSystem = db_connect($db_system);
		}

		helper(['form']);
		//$db_name = db_connect("db_rmc_property");		
		$this->model = new model_ward_mstr($this->dbSystem);
		$this->modelUlb = new model_ulb_mstr($this->dbSystem);
		$this->water_mobile_model = new WaterMobileModel($this->db);
		$this->search_consumer_mobile_model = new WaterSearchConsumerMobileModel($this->db);
		$this->demand_model = new WaterConsumerDemandModel($this->db);
		$this->payment_model = new WaterPaymentModel($this->db);
		$this->consumer_initial_meter = new model_water_consumer_initial_meter($this->db);
		$this->generate_demand_controller = new WaterGenerateDemand();
		$this->meter_status_model = new WaterMeterStatusModel($this->db);
		$this->initial_reading_model = new WaterConsumerInitialMeterReadingModel($this->db);
		$this->model_view_water_consumer = new model_view_water_consumer($this->db);
		$this->consumer_tax_model = new WaterConsumerTaxModel($this->db);
		$this->modelemp = new model_emp_details($this->dbSystem);

		$this->Water_Transaction_Model=new Water_Transaction_Model($this->db);
		$this->model_visiting_dtl = new model_visiting_dtl($this->dbSystem);
	}

	public function home()
	{
		$data = array();
		return view('mobile/index', $data);
	}

	public function view($consumer_id)
	{

		$data = array();

		$curr_month = date('m');
		if ($curr_month == 01 or $curr_month == 02 or $curr_month == 03) {
			$curr_year = date('Y') - 1;
		} else {
			$curr_year = date('Y');
		}


		$next_year = $curr_year + 1;
		$start_year = $curr_year . '-04-01';
		$end_year = $next_year . '-03-31';

		if ($this->request->getMethod() == 'post') 
		{
			$inputs = arrFilterSanitizeString($this->request->getVar());			

			if ($inputs['generate_demand']) 
			{			
				
				$due_upto = $this->demand_model->getMaxDemandGeneratedDate($consumer_id);
				//print_var($inputs);die;
				$prev_month = date('Y-m-d', strtotime(date('Y-m-d') . "-1 months"));
				if ($inputs['connection_type'] == 3) 
				{
					if ($due_upto >= $prev_month) {
						flashToast("error", "Demand Already Generated Upto Previous Month in Fixed!!!");
						return $this->response->redirect(base_url('WaterViewConsumerMobile/view/' . $consumer_id));
					}
				}

				//if ($inputs['generatedemand']) 
				{
					$upto_date = date('Y-m-d');
					
					$ar_squ_f = $inputs['area_sqft'] ?? 0;
					if ($ar_squ_f == null || $ar_squ_f == 0) 
					{
						flashToast("error", "Update your area or property type!!!");
						return $this->response->redirect(base_url('WaterViewConsumerMobile/view/' . $consumer_id));
					}
					
					$last_meter_reading=['last_reading'];
					$final_meter_reading = isset($inputs['final_meter_reading']) ? $inputs['final_meter_reading'] : 0;
					if ($last_meter_reading >= $final_meter_reading and $final_meter_reading != "" && $inputs['connection_type'] != 3) 
					{
						flashToast("error", "Final Reading should be greater than Previous!!!");
						return $this->response->redirect(base_url('WaterViewConsumerMobile/view/' . $consumer_id));
					}
					$tax_id = $this->generate_demand_controller->tax_generation($consumer_id, $upto_date, $final_meter_reading);
					
					if($tax_id)
					{	flashToast("success_demand", "Demand Generated Successfully!!!");				
						return $this->response->redirect(base_url('WaterViewConsumerMobile/consumer_demand_receipt/'.md5($consumer_id).'/'.md5($tax_id)));
					}
					return $this->response->redirect(base_url('WaterViewConsumerMobile/view/' . $consumer_id));
				}
			}
			if (isset($inputs['proceed_to_pay'])) 
			{

				return $this->response->redirect(base_url('WaterUserChargePaymentMobile/payment_details/' . $consumer_id));
			}
		}

		$sql = "select 
					meter_ststus.connection_type AS meter_connection_type,
					meter_ststus.meter_no,
					last_reading_dtl.initial_reading,
					last_reading_dtl.initial_date,
					min_consumer_demand.arr_due_amt,
					min_consumer_demand.curr_due_amt,
					min_consumer_demand.due_from,
					min_consumer_demand.due_upto,
					view_consumer_dtl.id,
					ward_no,
					consumer_no,
					application_no,
					applicant_name,
					mobile_no, 
					pipeline_type,
					property_type,
					view_consumer_dtl.connection_type,
					connection_through,
					category, 
					area_sqmt,
					area_sqft, 
					applicant_name as owner_name ,
					mobile_no 
				from view_consumer_dtl 
				left join (select 
							consumer_id,
							string_agg(applicant_name,',') as applicant_name, 
							string_agg(mobile_no::text,',') as mobile_no 
						from tbl_consumer_details
						where consumer_id='".$consumer_id."'
						group by consumer_id 
				) as owner on owner.consumer_id=view_consumer_dtl.id 
				LEFT JOIN (SELECT 
								consumer_id,
								connection_type,
								meter_no
							FROM tbl_meter_status
							where consumer_id='".$consumer_id."'
							ORDER BY id DESC
							LIMIT 1
				) AS meter_ststus ON meter_ststus.consumer_id=view_consumer_dtl.id
				LEFT JOIN (
					SELECT
						initial_reading,
						date(created_on) as initial_date,
						consumer_id
					FROM tbl_consumer_initial_meter
					where consumer_id='".$consumer_id."' and status = 1
					ORDER BY id DESC
					LIMIT 1
				) AS last_reading_dtl ON last_reading_dtl.consumer_id=view_consumer_dtl.id
				LEFT JOIN (
					select 
						SUM(CASE WHEN tbl_consumer_demand.demand_upto<'".date('Y-m-d')."' THEN amount ELSE 0 END) AS arr_due_amt,
						SUM(CASE WHEN tbl_consumer_demand.demand_upto>='".date('Y-m-d')."' THEN amount ELSE 0 END) AS curr_due_amt,
						MAX(demand_upto) AS due_upto,
						MIN(demand_from) AS due_from,
						consumer_id
					from tbl_consumer_demand 
					where consumer_id='".$consumer_id."'and status=1 and paid_status=0
					GROUP BY consumer_id
				) AS min_consumer_demand ON min_consumer_demand.consumer_id=view_consumer_dtl.id
				where view_consumer_dtl.id='".$consumer_id."'";
		$result = $this->db->query($sql)->getFirstRow("array");
		// print_var($result);
		// die();
		$data['consumer_dtls'] = $result;	
		$cons_id = $data['consumer_dtls']['id'];
		$data['consumer_id']=$cons_id;
		$data['connection_dtls'] = $this->meter_status_model->getLastConnectionDetails($cons_id);
		//print_var($data['connection_dtls']);
		//$get_last_reading = $this->initial_reading_model->initial_meter_reading($data['consumer_dtls']['id']);
		$data['last_reading'] = $result['initial_reading'];		
		$data['arr_due_amt'] = $result['arr_due_amt'];
		$data['curr_due_amt'] = $result['curr_due_amt'];
		$data['due_from'] = $result['due_from'];
		$data['due_upto'] =$result['due_upto'];
		$data['last_transection_id']=$this->Water_Transaction_Model->getLastTransectionId($cons_id);
		
		// print_var($data);
		return view('mobile/water/view_consumer_details', $data);
	}

	public function demand_generate($consumer_id)
	{
		$data = array();
		$curr_month = date('m');
		if ($curr_month == 01 or $curr_month == 02 or $curr_month == 03) 
		{
			$curr_year = date('Y') - 1;
		} 
		else 
		{
			$curr_year = date('Y');
		}

		$next_year = $curr_year + 1;
		$start_year = $curr_year . '-04-01';
		$end_year = $next_year . '-03-31';

		$sql = "select 
					meter_ststus.connection_type AS meter_connection_type,
					meter_ststus.meter_no,
					last_reading_dtl.initial_reading,
					last_reading_dtl.initial_date,
					min_consumer_demand.arr_due_amt,
					min_consumer_demand.curr_due_amt,
					min_consumer_demand.due_from,
					min_consumer_demand.due_upto,
					view_consumer_dtl.id,
					ward_no,
					consumer_no,
					application_no,
					applicant_name,
					mobile_no, 
					pipeline_type,
					property_type,
					view_consumer_dtl.property_type_id,
					view_consumer_dtl.connection_type,					
					connection_through,
					category, 
					area_sqmt,
					area_sqft, 
					applicant_name as owner_name ,
					mobile_no 
				from view_consumer_dtl 
				left join (select 
							consumer_id,
							string_agg(applicant_name,',') as applicant_name, 
							string_agg(mobile_no::text,',') as mobile_no 
						from tbl_consumer_details
						where consumer_id='".$consumer_id."'
						group by consumer_id 
				) as owner on owner.consumer_id=view_consumer_dtl.id 
				LEFT JOIN (SELECT 
								consumer_id,
								connection_type,
								meter_no
							FROM tbl_meter_status
							where consumer_id='".$consumer_id."'
							ORDER BY id DESC
							LIMIT 1
				) AS meter_ststus ON meter_ststus.consumer_id=view_consumer_dtl.id
				LEFT JOIN (
					SELECT
						initial_reading,
						date(created_on) as initial_date,
						consumer_id
					FROM tbl_consumer_initial_meter
					where consumer_id='".$consumer_id."'
					ORDER BY id DESC
					LIMIT 1
				) AS last_reading_dtl ON last_reading_dtl.consumer_id=view_consumer_dtl.id
				LEFT JOIN (
					select 
						SUM(CASE WHEN tbl_consumer_demand.demand_upto<'".$start_year."' THEN amount ELSE 0 END) AS arr_due_amt,
						SUM(CASE WHEN tbl_consumer_demand.demand_upto>='".$end_year."' THEN amount ELSE 0 END) AS curr_due_amt,
						MAX(demand_upto) AS due_upto,
						MAX(demand_from) AS due_from,
						consumer_id
					from tbl_consumer_demand 
					where consumer_id='".$consumer_id."'and status=1 and paid_status=0
					GROUP BY consumer_id
				) AS min_consumer_demand ON min_consumer_demand.consumer_id=view_consumer_dtl.id
				where view_consumer_dtl.id='".$consumer_id."'";
		$result = $this->db->query($sql)->getFirstRow("array");		
		$data['consumer_dtls'] = $result;	
		$cons_id = $data['consumer_dtls']['id'];
		$data['consumer_id']=$cons_id;
		$data['connection_dtls'] = $this->meter_status_model->getLastConnectionDetails($cons_id);		
		$data['last_reading'] = $result['initial_reading'];		
		$data['arr_due_amt'] = $result['arr_due_amt'];
		$data['curr_due_amt'] = $result['curr_due_amt'];
		$data['due_from'] = $result['due_from'];
		$data['due_upto'] =$result['due_upto'];
		
		$get_last_reading = $this->initial_reading_model->initial_meter_reading($data['consumer_dtls']['id']);				
		$last_meter_reading = $get_last_reading['initial_reading'];
		$data['last_reading']=$last_meter_reading;
		$data['curent_year_month'] = date('Y-m');
		$data['last_reading_date']	=(!empty($data['last_reading_date'])?date('Y-m',strtotime($data['last_reading_date'])):null);	
		$cons_id = $data['consumer_dtls']['id'];

		$data['getpreviousMeterReding']= $this->initial_reading_model->getpreviousMeterReding($cons_id,$get_last_reading["id"]??0)['initial_reading']??0;
        
		$data['last_demand_dtl'] = $this->demand_model->getLastDemand2($cons_id);
        $date1= date_create($data['last_demand_dtl']['demand_upto']);
        $date2=date_create($data['last_demand_dtl']['demand_from']);
        $date3 = date_create(date("Y-m-d"));
        $diff=date_diff($date2,$date1);
        $no_diff = $diff->format("%a");            
        $current_diff = date_diff($date3,$date1)->format("%a");
        $reading = ($data['last_reading']??0) - ($data['getpreviousMeterReding']);
        $arvg = $no_diff!=0 ? round(($reading / $no_diff),2) : 1;
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
        
        
        $data['twoAvgBill'] = $this->consumer_tax_model->getAverageTwoBill($cons_id);
        
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
		if ($this->request->getMethod() == 'post') 
		{
			$inputs = arrFilterSanitizeString($this->request->getVar());
			// if($this->emp_details_id==1375)
			// {
			// 	print_var($inputs);die;
			// }
			$file = null;
			$due_upto = $this->demand_model->getMaxDemandGeneratedDate($cons_id);

			$prev_month = date('Y-m-d', strtotime(date('Y-m-d') . "-1 months"));
			if ($data['connection_dtls']['connection_type'] == 3) 
			{
				if ($due_upto >= $prev_month) {
					flashToast("error", "Demand Already Generated Upto Previous Month in Fixed!!!");
					return $this->response->redirect(base_url('WaterViewConsumerMobile/demand_generate/' . $consumer_id));
				}
			}
			elseif(in_array($data['connection_dtls']['connection_type'],[1,2]))
			{
				$due_upto = !empty($due_upto)?date('Y-m',strtotime($due_upto)):null;
				if((!is_null($data['last_reading_date']) && $data['last_reading_date']>=$data['curent_year_month']) || (!is_null($due_upto) && $due_upto>=$data['curent_year_month']))
				{
					flashToast("error", "Demand Already Generated Of This Month in Meter!!!");
					return $this->response->redirect(base_url('WaterViewConsumerMobile/demand_generate/' . $consumer_id));	
				}
				$file = $this->request->getFile('document');
                $rules = [
                    'document'=>'uploaded[document]|max_size[document,3072]|ext_in[document,jpg,jpeg]',
                ];
                if(!$this->validate($rules))
                {
                    $data['validation']=$this->validator->getError();
                    flashToast("error", $data['validation']);
                    return $this->response->redirect(base_url('WaterViewConsumerMobile/demand_generate/' . $consumer_id));	
                }
			}

			if(empty($data['connection_dtls']) ||  $data['connection_dtls']['connection_date']=='')
			{
				flashToast("error", "Connection Date Not Found!!!"); 
				return $this->response->redirect(base_url('WaterViewConsumerMobile/demand_generate/' . $consumer_id));
			}
			
			if ($inputs['generatedemand']) {
				$upto_date = date('Y-m-d');
				
				$ar_squ_f = $data['consumer_dtls']['area_sqft'] ?? 0;
				if ($ar_squ_f == null || $ar_squ_f == 0) {
					flashToast("error", "Update your area or property type!!!");
					return $this->response->redirect(base_url('WaterViewConsumerMobile/demand_generate/' . $consumer_id));
				}
				

				$final_meter_reading = isset($inputs['final_meter_reading']) ? $inputs['final_meter_reading'] : 0;
				if ($last_meter_reading > $final_meter_reading and $final_meter_reading != "") {
					flashToast("error", "Final Reading should be greater than Previous!!!");
					return $this->response->redirect(base_url('WaterViewConsumerMobile/demand_generate/' . $consumer_id));
				}
				$this->db->transBegin();
				$tax_id = $this->generate_demand_controller->tax_generation($cons_id, $upto_date, $final_meter_reading,$file);
				
				if($tax_id && $this->db->transStatus())
				{
					$this->db->transCommit();
					$vistingRepostInput = waterDemandGenrateVisit($data['consumer_dtls'],$this->request->getVar());
					$visiting_dtl_id = $this->model_visiting_dtl->insertdetail($vistingRepostInput);	
					flashToast("success_demand", "Demand Generated Successfully!!!");				
					return $this->response->redirect(base_url('WaterViewConsumerMobile/consumer_demand_receipt/'.md5($consumer_id).'/'.md5($tax_id)));
				}
				if(is_null($tax_id) && $data['consumer_dtls']['category']=='BPL')
				{
					flashToast("success_demand", "Demand Not Generated Of BPL Category Due To Maintenance!!!");
				}
				$this->db->transRollback();
				return $this->response->redirect(base_url('WaterViewConsumerMobile/view/' . $consumer_id));
			}
		}
		if(empty($data['connection_dtls']) ||  $data['connection_dtls']['connection_date']=='')
        {
            flashToast("error", "Connection Date Not Found!!!"); 
        }
		return view('mobile/water/consumer_demand_generate', $data);
	}

	public function update_consumer($md5consumer_id)
	{
		$data['consumer_dtls'] = $this->search_consumer_mobile_model->getConsumerDetailsbyId($md5consumer_id);
		if (!empty($data['consumer_dtls'])) {
			return $this->response->redirect(base_url('WaterViewConsumerDetails/update_consumer/' . $md5consumer_id . '/' . '1'));
		} else {
		}
	}

	public function consumer_demand_receipt($consumer_id,$tax_id=null)
	{
		$data['ulb_mstr_name']=session()->get('ulb_dtl');
		$data['emp_dtls'] = $data['emp_dtls'] = $this->modelemp->emp_dtls($this->emp_details_id);		
		$data['consumer_dtl']= $this->search_consumer_mobile_model->getConsumerDetailsbyId($consumer_id);
		if (empty($data['consumer_dtls']) || count($data['consumer_dtls']) == 0)
			$data['consumer_dtls'] = $this->model_view_water_consumer->waterConsumerDetailsById($consumer_id);
		if($data['consumer_dtls'])
		{
			$cons_id = $data['consumer_dtls']['id'];
			$this->demand_model->impose_penalty($cons_id);
			if($tax_id<>null)
			{
				$data['consume_tax'] = $this->consumer_tax_model->getData($tax_id);
				$data['demand_dtl'] =  $this->demand_model->getTotalAmountByCidTid($cons_id,$data['consume_tax']['id']);
				
			}
			else
			{
				$data['demand_dtl'] =  $this->demand_model->getTotalAmountByCid($cons_id);
			}
			$data['current_meter_status'] = $this->meter_status_model->getLastConnectionDetails($cons_id);
			$data['meter_last_reading'] = $this->initial_reading_model->initial_meter_reading($data['consumer_dtls']['id']);
			if($data['meter_last_reading'])
			{
				$data['priv_meter_reading'] = $this->initial_reading_model->getpreviousMeterReding($data['consumer_dtls']['id'],$data['meter_last_reading']['id']);
			}			
			if($data['current_meter_status'])
			{
				$data['priv_meter_status'] =  $this->meter_status_model->getPreviousConnectionDetails($cons_id,$data['current_meter_status']['id']);				
			}
							
			//print_var($data['current_meter_status']);
			return view('mobile/water/consumer_demand_receipt',$data);
		}
		else
		{
			flashToast("error", "Consumer Not Found");
			return $this->response->redirect(base_url('WaterSearchConsumerMobile/search_consumer_tc/'));
		}
	}
}
