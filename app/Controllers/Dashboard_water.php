<?php 
namespace App\Controllers;
use CodeIgniter\Controller;

use App\Models\model_apply_water_connection;
use App\Models\Water_Transaction_Model;
use App\Models\model_consumer;
use App\Models\WaterConsumerDemandModel;
use App\Models\WaterConsumerCollectionModel;
use App\Models\model_water_dashboard_data;
use App\Models\model_water_dashboard_daily_collection;

class Dashboard_water extends AlphaController
{
    protected $db;
	
	protected $model_apply_water_connection;
	protected $Water_Transaction_Model;
	protected $model_consumer;
	protected $WaterConsumerDemandModel;
	protected $WaterConsumerCollectionModel;
	protected $model_water_dashboard_data;
	protected $model_water_dashboard_daily_collection;
	
    public function __construct(){
        parent::__construct();
    	helper(['db_helper']);
        if($db_name = dbConfig("water")){
            $this->db = db_connect($db_name); 
        }
		 
		if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        } 
		
		
		$this->model_apply_water_connection = new model_apply_water_connection($this->db);
		$this->Water_Transaction_Model = new Water_Transaction_Model($this->db);
		$this->model_consumer = new model_consumer($this->db);
		$this->WaterConsumerDemandModel = new WaterConsumerDemandModel($this->db);
		$this->WaterConsumerCollectionModel = new WaterConsumerCollectionModel($this->db);
		$this->model_water_dashboard_data = new model_water_dashboard_data($this->db);
		$this->model_water_dashboard_daily_collection = new model_water_dashboard_daily_collection($this->db);
    }
    
	
	
	public function ajax_gatewater()
    { 
		if($this->request->getMethod()=='post'){
			
			$data = [
					'fyYr' => $this->request->getVar('fy_mstr_id')
					];
			$fy_year = $data['fyYr'];		
			if($fy_year==""){
				$month= date("m");
				$year= date("Y");
				if($month==01 || $month==02 || $month==03)
				{
					$fy = ($year-0001);
					$fy_year = $fy.'-'.$year;
				}else
				{
					$fy = ($year+0001);
					$fy_year = $year.'-'.$fy;
				}
				
			}
			
			$fydata = $this->model_water_dashboard_data->gatedatabyfy($fy_year);
			
			$response = ['response'=>true, 'newcon'=>$fydata['new_connection'], 'regul'=>$fydata['regularization'], 
			'consumer'=>$fydata['total_consumer'], 'current_demand'=>$fydata['current_demand'], 'current_Collec'=>$fydata['current_coll'],
			'arrear_demand'=>$fydata['arrear_demand'], 'arrear_Collec'=>$fydata['arrear_coll'], 'conncount'=>$fydata['connection_count'],
			'connamount'=>$fydata['connection_amount'], 'conscount'=>$fydata['consumer_count'], 'consamount'=>$fydata['consumer_amount']];
		
		}else{
			$response = ['response'=>false];
		}
		echo json_encode($response);
        
    }
	
	
	public function fy_collwater()
    { 
		if($this->request->getMethod()=='post'){
			
			$data = [
					'fyYr' => $this->request->getVar('fy_mstr_id')
					];
			$fy_year = $data['fyYr'];		
			if($fy_year==""){
				$month= date("m");
				$year= date("Y");
				if($month==01 || $month==02 || $month==03)
				{
					$fy = ($year-0001);
					$fy_year = $fy.'-'.$year;
				}else
				{
					$fy = ($year+0001);
					$fy_year = $year.'-'.$fy;
				}
				
				$data['yearrr'] = explode('-',$fy_year);
				$ys = $data['yearrr'][0];
				$y = $data['yearrr'][1];
				
			}else {
				$data['yearrr'] = explode('-',$fy_year);
				$ys = $data['yearrr'][0];
				$y = $data['yearrr'][1];
				
			}
			
			$yr= $ys.date('-04-01');
			$yer = $y.date('-03-31');
			for($i=0;$i<=11;$i++){
				if($yr<=$yer){
					$datas['fromdate'] = $yr;
					$datas['toDate'] = date("Y-m-t", strtotime($datas['fromdate'])) ;
					$total_current_fy_collection_amount = $this->model_water_dashboard_daily_collection->current_fy_collection($datas);
					//$total_current_fy_saf_collection_amount = $this->model_saf_collection->current_fy_saf_collection($datas);
					
				}
				$data['fy_collection'][$i]=$total_current_fy_collection_amount['collectionamount']+0;
				$yr = date('Y-m-d', strtotime('+1 month', strtotime($datas['fromdate'])));
				
			}
			
			$response = ['response'=>true, 'mnthCollection'=>$data['fy_collection']];
		
		}else{
			$response = ['response'=>false];
		}
		echo json_encode($response);
        
    }
	
	
	public function dcbwater()
    { 
		if($this->request->getMethod()=='post'){
			$data = [
					'fyYr' => $this->request->getVar('fy_mstr_id')
					];
			$fy_year = $data['fyYr'];		
			if($fy_year==""){
				$month= date("m");
				$year= date("Y");
				if($month==01 || $month==02 || $month==03)
				{
					$fy = ($year-0001);
					$fy_year = $fy.'-'.$year;
				}else
				{
					$fy = ($year+0001);
					$fy_year = $year.'-'.$fy;
				}
				
			}
			
			$fydata = $this->model_water_dashboard_data->gatedmndcollbyfy($fy_year);
			
			$consublnc = round(($fydata['total_demand'] - $fydata['total_collection']),2);
			
			$data['waterdcb'] = [$fydata['total_collection'],$consublnc];
			
			$response = ['response'=>true, 'wtrdcb'=>$data['waterdcb']];
		
		}else{
			$response = ['response'=>false];
		}
		echo json_encode($response);
        
    }
	
	
	public function cmprwater()
    { 
		if($this->request->getMethod()=='post'){
			
			$data = [
					'fyYr' => $this->request->getVar('fy_mstr_id')
					];
			$fy_year = $data['fyYr'];		
			if($fy_year==""){
				$month= date("m");
				$year= date("Y");
				if($month==01 || $month==02 || $month==03)
				{
					$fy = ($year-0001);
					$fy_year = $fy.'-'.$year;
				}else
				{
					$fy = ($year+0001);
					$fy_year = $year.'-'.$fy;
				}
				
				$data['yearrr'] = explode('-',$fy_year);
				
				$crnt = date("m");
				if($crnt==01 || $crnt==02 || $crnt==03){
					$xt = $data['yearrr'][1];
				}else{
					$xt = $data['yearrr'][0];
				}
			}else {
				$data['yearrr'] = explode('-',$fy_year);
				
				$crnt = date("m");
				if($crnt==01 || $crnt==02 || $crnt==03){
					$xt = $data['yearrr'][1];
				}else{
					$xt = $data['yearrr'][0];
				}
			}
			
			$yrr= $xt-01;
			$prvyr= $yrr.date('-m-01');
			$yr= $xt.date('-m-01');
			for($i=0;$i<=2;$i++){
				
				$datas['fromdate'] = $yr;
				$datas['toDate'] = date("Y-m-t", strtotime($datas['fromdate'])) ;
				$crntcmpr_collection_amount = $this->model_water_dashboard_daily_collection->cmpr_fy_collection($datas);
				
				$data['crntcmpr'][$i]=$crntcmpr_collection_amount['collectionamount']+0;
				$yr = date('Y-m-d', strtotime('-1 month', strtotime($datas['fromdate'])));
			}
			for($i=0;$i<=2;$i++){
				
				$datas['fromdate'] = $prvyr;
				$datas['toDate'] = date("Y-m-t", strtotime($datas['fromdate'])) ;
				$prvcmpr_collection_amount = $this->model_water_dashboard_daily_collection->cmpr_fy_collection($datas);
				
				$data['prvcmpr'][$i]=$prvcmpr_collection_amount['collectionamount']+0;
				$prvyr = date('Y-m-d', strtotime('-1 month', strtotime($datas['fromdate'])));
			}
			$data['cmpr'] = [$data['prvcmpr'],$data['crntcmpr']];
			
			$response = ['response'=>true,'compare'=>$data['cmpr']];
			
			
		}else{
			$response = ['response'=>false];
		}
		echo json_encode($response);
        
    }
	
	
	public function dy_collwater()
    { 
		if($this->request->getMethod()=='post'){
			
			$data = [
					'fyYr' => $this->request->getVar('fy_mstr_id')
					];
			$fy_year = $data['fyYr'];		
			if($fy_year==""){
				$month= date("m");
				$year= date("Y");
				if($month==01 || $month==02 || $month==03)
				{
					$fy = ($year-0001);
					$fy_year = $fy.'-'.$year;
				}else
				{
					$fy = ($year+0001);
					$fy_year = $year.'-'.$fy;
				}
				
				$data['yearrr'] = explode('-',$fy_year);
				
				$crnt = date("m");
				if($crnt==01 || $crnt==02 || $crnt==03){
					$xt = $data['yearrr'][1];
				}else{
					$xt = $data['yearrr'][0];
				}
			}else {
				$data['yearrr'] = explode('-',$fy_year);
				
				$crnt = date("m");
				if($crnt==01 || $crnt==02 || $crnt==03){
					$xt = $data['yearrr'][1];
				}else{
					$xt = $data['yearrr'][0];
				}
			}
			
			$dm= date('t');
			$dy= $xt.date('-m-01');
			$dyend = $xt.date('-m-t');
			for($i=0;$i<$dm;$i++){
				$datas['fromdate'] = $dy;
				$total_current_mnth_collection_amount = $this->model_water_dashboard_daily_collection->current_mnth_collection($datas);
				
				$data['total_dy_collectionamnt'][$i]=$total_current_mnth_collection_amount['collectionamount']+0;
				$dy = date('Y-m-d', strtotime('+1 day', strtotime($datas['fromdate'])));
			}
			
			$response = ['response'=>true, 'dayCollection'=>$data['total_dy_collectionamnt']];
			
			
		}else{
			$response = ['response'=>false];
		}
		echo json_encode($response);
        
    }
	
	
}