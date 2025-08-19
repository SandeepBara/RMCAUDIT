<?php 
namespace App\Controllers;
use CodeIgniter\Controller;

use App\Models\TradeTransactionModel;
use App\Models\TradeApplyLicenceModel;
use App\Models\model_trade_dashboard_data;
use App\Models\model_trade_dashboard_daily_collection;
//use App\Models\model_licence;

class Dashboard_trade extends AlphaController
{
    protected $db;
	protected $TradeTransactionModel;
	protected $TradeApplyLicenceModel;
	protected $model_trade_dashboard_data;
	protected $model_trade_dashboard_daily_collection;
	//protected $model_licence;
	
    public function __construct(){
        parent::__construct();
    	helper(['db_helper']);
        if($db_name = dbConfig("trade")){
            $this->db = db_connect($db_name); 
        }
		 
		if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        } 
		
		
		//$this->model_licence = new model_licence($this->db);
		$this->TradeTransactionModel = new TradeTransactionModel($this->db);
		$this->TradeApplyLicenceModel = new TradeApplyLicenceModel($this->db);
		$this->model_trade_dashboard_data = new model_trade_dashboard_data($this->db);
		$this->model_trade_dashboard_daily_collection = new model_trade_dashboard_daily_collection($this->db);
    }
    
	
	
	public function ajax_gatetrade()
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
			
			$gatedatabyfy = $this->model_trade_dashboard_data->gatedatabyfy($fy_year);
			
			$response = ['response'=>true, 'active'=>$gatedatabyfy['active_licence'], 
			'deactive'=>$gatedatabyfy['deactive_licence'], 'newapp'=>$gatedatabyfy['new_licence'],  'renewal'=>$gatedatabyfy['renewal_licence'], 
			'amendment'=>$gatedatabyfy['amendment_licence'], 'surrenderapp'=>$gatedatabyfy['surrender_licence'],
			'newLicence'=>$gatedatabyfy['new_licence_amount'], 'renewalLicence'=>$gatedatabyfy['renewal_licence_amount'],
			'amendmentLicence'=>$gatedatabyfy['amendment_licence_demand']];
			
		}else{
			$response = ['response'=>false];
		}
		echo json_encode($response);
        
    }
	
	
	public function fy_colltrade()
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
					$total_current_fy_collection_amount = $this->model_trade_dashboard_daily_collection->current_fy_collection($datas);
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
	
	public function cmprtrade()
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
				$crntcmpr_collection_amount = $this->model_trade_dashboard_daily_collection->cmpr_fy_collection($datas);
				
				$data['crntcmpr'][$i]=$crntcmpr_collection_amount['collectionamount']+0;
				$yr = date('Y-m-d', strtotime('-1 month', strtotime($datas['fromdate'])));
			}
			for($i=0;$i<=2;$i++){
				
				$datas['fromdate'] = $prvyr;
				$datas['toDate'] = date("Y-m-t", strtotime($datas['fromdate'])) ;
				$prvcmpr_collection_amount = $this->model_trade_dashboard_daily_collection->cmpr_fy_collection($datas);
				
				$data['prvcmpr'][$i]=$prvcmpr_collection_amount['collectionamount']+0;
				$prvyr = date('Y-m-d', strtotime('-1 month', strtotime($datas['fromdate'])));
			}
			$data['cmpr'] = [$data['prvcmpr'],$data['crntcmpr']];
			//$response = ['response'=>true, 'amendmentLicence'=>$amendmentLicences];
			
			$response = ['response'=>true, 'compare'=>$data['cmpr']];
			
		}else{
			$response = ['response'=>false];
		}
		echo json_encode($response);
        
    }
	
	public function dy_colltrade()
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
				$total_current_mnth_collection_amount = $this->model_trade_dashboard_daily_collection->current_mnth_collection($datas);
				
				$data['total_dy_collectionamnt'][$i]=$total_current_mnth_collection_amount['collectionamount']+0;
				$dy = date('Y-m-d', strtotime('+1 day', strtotime($datas['fromdate'])));
			}
			
			
			$response = ['response'=>true,
			'dayCollection'=>$data['total_dy_collectionamnt']];
			
		}else{
			$response = ['response'=>false];
		}
		echo json_encode($response);
        
    }
	
	/*
	public function insertdailycollection(){
		
		$dm = 30;$m = 04;
		$dm = 31;$m = 05;
		$dm = 30;$m = 06;
		$dm = 31;$m = 07;
		$dm = 31;$m = 8;
		$dm = 30;$m = 9;
		$dm = 31;$m = 10;
		$dm = 30;$m = 11;
		$dm = 31;$m = 12;
		$dm = 31;$m = 01;
		$dm = 28;$m = 02;
		$dm = 31;$m = 03;
		
		$dm = 31;$m = 8;
		$dy= date('2021-').$m.('-01');
		$dyend = date('2021-').$m.'-'.$dm;
		//print_r($dyend);die();
		for($i=0;$i<$dm;$i++){
			$datas['fromdate'] = $dy;
			$total_dy_collection_amount = $this->TradeTransactionModel->insertdy_collection($datas);
			
			$dy = date('Y-m-d', strtotime('+1 day', strtotime($datas['fromdate'])));
		}
	}
    
    */
}