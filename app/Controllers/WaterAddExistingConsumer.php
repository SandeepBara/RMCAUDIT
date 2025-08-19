<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Controllers\WaterGenerateDemand;
use App\Controllers\WaterApplyNewConnectionCitizen;
use App\Models\WaterPropertyModel;
use App\Models\WaterConnectionTypeModel;
use App\Models\WaterViewPropertyDetailModel;
use App\Models\WaterFixedMeterRateModel;
use App\Models\model_water_consumer;
use App\Models\WaterConsumerDetailsModel;
use App\Models\WaterMeterStatusModel;
use App\Models\WaterConsumerDemandModel;

use App\Models\WaterMobileModel;
use App\Models\WaterConsumerInitialMeterReadingModel;
use App\Models\WaterConnectionThroughModel;
use App\Models\WaterPipelineModel;


class WaterAddExistingConsumer extends AlphaController
{
    protected $water;
    // protected $db;
    protected $dbSystem;
    protected $model_view_water_consumer;
    protected $model_ward_mstr;
    protected $ulb_id;
    protected $emp_id;
	protected $fixed_meter_rate_model;
	protected $water_consumer_details_model;
	protected $meter_status_model;
	protected $last_reading;
	protected $WaterApplyNewConnectionCitizen;
	protected $model_water_consumer;
	protected $conn_through_model;
	protected $pipeline_model;

	protected $consumer_demand_model;

    public function __construct()
	{
    	
        $session=session();
        $ulb_details=$session->get('ulb_dtl');
        //print_r($ulb_details);
        $this->ulb_id=$ulb_details['ulb_mstr_id'];
        
        $emp_details=$session->get('emp_details');
        $this->emp_id=$emp_details['id'];
        
        
        parent::__construct();
        helper(['db_helper','form']);
        if($db_name = dbConfig("water")){
            $this->water = db_connect($db_name);            
        }
        if($db_name = dbConfig("property"))
        {
        	$this->property = db_connect($db_name);
        }
        if($db_name = dbSystem()){
            $this->dbSystem = db_connect($db_name); 
        }
         /*if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name); 
        }*/
        
        $this->ward_model = new model_ward_mstr($this->dbSystem);
        $this->water_property_model=new WaterPropertyModel($this->water);
        $this->conn_type_model=new WaterConnectionTypeModel($this->water);
        $this->water_property_details_model=new WaterViewPropertyDetailModel($this->water);
        $this->fixed_meter_rate_model=new WaterFixedMeterRateModel($this->water);
        $this->model_water_consumer = new model_water_consumer($this->water);
        $this->water_consumer_details_model=new WaterConsumerDetailsModel($this->water);
        $this->meter_status_model=new WaterMeterStatusModel($this->water);
        $this->generate_demand_model=new WaterGenerateDemand($this->water);
		$this->generate_demand_model=new WaterGenerateDemand($this->water);
        $this->WaterApplyNewConnectionCitizen=new WaterApplyNewConnectionCitizen($this->water);

		$this->WaterMobileModel=new WaterMobileModel($this->water);		
		$this->meter_status_model=new WaterMeterStatusModel($this->water);
        $this->last_reading=new WaterConsumerInitialMeterReadingModel($this->water);
		$this->conn_through_model=new WaterConnectionThroughModel($this->water);
		$this->pipeline_model=new WaterPipelineModel($this->water);
		$this->consumer_demand_model = new WaterConsumerDemandModel($this->water);
		// WaterConsumerDemandModel
    }
    
    public function index()
    {  
        $data=array();
		$ulb_details = session()->get('ulb_dtl');
		$ulb_details['short_ulb_name']=substr(strtoupper($ulb_details['city']),0,3);
        $data['property_type_list']=$this->water_property_model->property_list();
        $data['conn_type_list']=$this->conn_type_model->conn_type_list();
        $data['ulb_mstr_id']=$this->ulb_id;
        $data['ward_list']=$this->ward_model->getWardList($data);
		$data['conn_through_list']=$this->conn_through_model->conn_through_list();
		$data['pipeline_type_list']=$this->pipeline_model->pipeline_list();

        if($this->request->getMethod()=='post')
        {	
			$consumer_id=null;
			$connection_id=null;
			$this->water->transBegin();
        	$inputs=arrFilterSanitizeString($this->request->getVar());
			
        	$data['old_consumer_no']=$inputs['old_consumer_no'];
			$data['conn_through_id']=$inputs['conn_through_id'];
        	$data['ward_id']=$inputs['ward_id'];
        	$data['holding_no']=$inputs['holding_no'];
			$data['prop_id']=$inputs['prop_id'];
			$data['saf_no']=$inputs['saf_no'];
			$data['saf_id']=$inputs['saf_id'];
        	$data['property_type_id']=$inputs['property_type_id'];
        	$data['address']=$inputs['address'];
        	$data['area_in_sqft']=$inputs['area_in_sqft'];
        	$data['area_in_sqmt']=$inputs['area_in_sqmt'];
        	$data['connection_type_id']=$inputs['connection_type_id'];
        	$data['connection_date']=$inputs['connection_date'];
        	$data['demand_upto']=$inputs['demand_upto'];
        	$data['arrear_amount']=$inputs['arrear_amount'];
        	$data['unit_rate']=$inputs['unit_rate'];
        	$data['meter_no'] = isset($inputs['meter_no'])? $inputs['meter_no'] :'';
			$data['initial_reading']=isset($inputs['initial_reading'])? $inputs['initial_reading'] :0;
			$data['juidco_consumer']=isset($inputs['juidco_consumer'])? $inputs['juidco_consumer'] :0;
			$data['meter_type'] = isset($inputs['meter_type'])?$inputs['meter_type']:0;
			$data['pipeline_type_id']=$inputs['pipeline_type_id']??'';
        	if($data['property_type_id']==1)
        	{
        		$data['applicant_category']=$inputs['applicant_category'];
				$data['pipeline_type_id']=$inputs['pipeline_type_id'];
        	}
        	else
        	{
        		$data['applicant_category']="APL";
        	}


			if($data['saf_id']>0 && $data['conn_through_id']==5)    
			{
				$count_saf=$this->model_water_consumer->cout_saf($data['saf_no']);
				if($count_saf>0)
				{
					flashToast("message", "Water Connection Already applied with this SAF No.");
					return view('water/water_connection/add_existing_consumer',$data);
				}
			}
		


			if($data['prop_id']>0 && $data['conn_through_id']==1)
			{
				$count_prop=$this->model_water_consumer->cout_holding($data['holding_no']);
				// in case multiple connection apply from same holding owner can apply only once but for now only one connection can be given on one holding so 
				
				if($count_prop>0)
				{
					flashToast("message", "Water Connection Already applied with this Holding No.");
					return view('water/water_connection/add_existing_consumer',$data);
				}
			}
			
        	$ulb_short_nm=$ulb_details['short_ulb_name'];			
        	$ulb_nm = substr($ulb_short_nm, 0, 3);
			$ward_id = $data['ward_id'];
			$ward_nm = array_filter($data['ward_list'],function($ward) use($ward_id)
			{
				if($ward['id']==$ward_id)
				{
					return true;
				}
				else
				{
					return false;
				}
			});
			$ward_nm = array_values($ward_nm)[0]??[];
			$ward_nm=$ward_nm['ward_no'];
			// print_var($inputs);die;
        	$data['ward_count']=$this->model_water_consumer->count_ward_by_wardid($ward_id);
			$sl_no = $data['ward_count']['ward_cnt'];
			$sl_noo = $sl_no+1;
			$serial_no = str_pad($sl_noo, 4, "0", STR_PAD_LEFT);
			$ward_nmm = str_pad($ward_nm, 2, "0", STR_PAD_LEFT);
			$consumer_no=$ulb_nm.'WC'.$ward_nmm.$serial_no;

        	if($data['unit_rate']=="")
        	{	 
        		 $_SESSION['message']="Rate Not Defined";
				
        		 return view('water/water_connection/add_existing_consumer',$data);
        	}
        	else
        	{	 
				// print_var($inputs);die;
        		$consumer_array=array();
        		$consumer_array['old_consumer_no']=$old_consumer_no??$data['old_consumer_no'];
        		$consumer_array['ward_mstr_id']=$data['ward_id'];
        		$consumer_array['consumer_no']=$consumer_no;
				$consumer_array['connection_through_id']=$data['conn_through_id'];				
				if(!empty($data['holding_no']) && $data['conn_through_id']==1)
				{
					$consumer_array['holding_no']=$data['holding_no'];
					$consumer_array['prop_dtl_id']=$data['prop_id'];
				}
				elseif(!empty($data['saf_no']) && $data['conn_through_id']==5)
				{
					$consumer_array['saf_no']=$data['saf_no'];
					$consumer_array['saf_dtl_id']=$data['saf_id'];
				}
				if($data['pipeline_type_id']=='')
				{
					$data['pipeline_type_id']=2;
				}
				if($data['property_type_id']==1)
				{
					$consumer_array['pipeline_type_id']=$data['pipeline_type_id'];
					$consumer_array['category']=$data['applicant_category'];
				}
				else
				{
					$consumer_array['pipeline_type_id']=$data['pipeline_type_id']=2; // other than residential property pipeline type will be old pipeline
					$consumer_array['category']=$data['applicant_category']='APL'; // other than residential property category type will be APL
				}
        		$consumer_array['property_type_id']=$data['property_type_id'];
        		$consumer_array['category']=$data['applicant_category'];
        		$consumer_array['address']=$data['address'];
        		$consumer_array['area_sqft']=$data['area_in_sqft'];
        		$consumer_array['area_sqmt']=$data['area_in_sqmt'];
        		$consumer_array['apply_from']='Existing';
        		$consumer_array['is_meter_working']=1;
				$consumer_array['created_on']=date('Y-m-d h:i:s');
        		$consumer_array['emp_details_id']=$this->emp_id;
				if($data['juidco_consumer']=='1')
					$consumer_array['juidco_consumer']='Juidco';
        		// print_var($consumer_array);
				// print_var($inputs);
				// die;
				
        		$consumer_id=$this->model_water_consumer->insertData($consumer_array);
				$connection_id=$consumer_id;
				//print_var($consumer_id);die;
				$data['ward_count']=$this->model_water_consumer->count_ward_by_wardid($data['ward_id']);
				$sl_no = $data['ward_count']['ward_cnt'];
				$sl_noo = $sl_no+1;
				$serial_no = str_pad($sl_noo, 4, "0", STR_PAD_LEFT);
				$ulb_short_nm=session()->get('ulb_dtl')['city'];
				$ulb_nm = substr($ulb_short_nm, 0, 3);
				$ward_nm=$this->WaterMobileModel->getDataNew(array('id'=>[$data['ward_id']]),array('ward_no'),'view_ward_mstr');
				$ward_nm=$ward_nm['ward_no'];
				$ward_nmm = str_pad($ward_nm, 2, "0", STR_PAD_LEFT);

				$consumer_no=$ulb_nm.'WC'.$ward_nmm.$serial_no.str_pad($consumer_id, 5, "0", STR_PAD_LEFT);
				$this->WaterMobileModel->updateNew(array('id'=>$consumer_id),array('consumer_no'=>$consumer_no),'tbl_consumer');
        		//print_r($inputs['owner_name']);
				
        		 for($i=0;$i<sizeof($inputs['owner_name']);$i++)
                 {
                 		
	                    $owner_arr['consumer_id']=$consumer_id;
	                    $owner_arr['applicant_name']=$inputs['owner_name'][$i];
	                    $owner_arr['father_name']=$inputs['guardian_name'][$i];
						$owner_arr['mobile_no']=$inputs['mobile_no'][$i];
	                    if($owner_arr['mobile_no']=="")
	                    {
	                        $owner_arr['mobile_no']=0;
	                    }
	                    //$owner_arr['email_id']=$inputs['email_id'][$i];
	                    $owner_arr['emp_details_id']=$this->emp_id;
	                    $owner_arr['created_on']=date('Y-m-d H:i:s');	                    
	             		
	                     $this->water_consumer_details_model->insertData($owner_arr);
	                    
                 }
				 
                $connection_dtls_array=array();
                $connection_dtls_array['consumer_id']=$consumer_id;
                $connection_dtls_array['connection_date']=$data['connection_date'];
				$connection_dtls_array['meter_status']=0;
				if(in_array($data['connection_type_id'],['1','2']))
				{
					$connection_dtls_array['meter_status']= $data['meter_type']??1;
				}
                $connection_dtls_array['status']=1;
                $connection_dtls_array['connection_type']=$data['connection_type_id'];
                $connection_dtls_array['meter_no']=$data['meter_no'];
                $connection_dtls_array['meter_reading_in']=0;
                $connection_dtls_array['final_meter_reading']=isset($data['connection_type_id']) && in_array($data['connection_type_id'],['1','2']) ? ($data['initial_reading']??0) :null;
				$connection_dtls_array['initial_reading']=isset($data['connection_type_id']) && in_array($data['connection_type_id'],['1','2']) ? 0.5 :null;
				$connection_dtls_array['emp_details_id']= $this->emp_id;
				$connection_dtls_array['created_on']=date('Y-m-d h:i:s');
				if($data['connection_type_id']==1 && $data['meter_type']==0 && $data['property_type_id']==3)
				{
					$connection_dtls_array['meter_status']=$data['meter_type'];
					$connection_dtls_array['rate_per_month']=$data['initial_reading']??0;
				}
                
                $this->meter_status_model->insertData($connection_dtls_array);
				
                if($data['arrear_amount']>0)
                {	
                	$consumer_demand=array();
                    $consumer_demand['consumer_id']=$consumer_id;
                    $consumer_demand['ward_mstr_id']=$data['ward_id'];
                    $consumer_demand['generation_date']=date('Y-m-d');
                    $consumer_demand['amount']=$data['arrear_amount'];
                    $consumer_demand['unit_amount']=$data['unit_rate'];
                    $consumer_demand['demand_from']=$data['connection_date'];
                    $consumer_demand['demand_upto']=$data['demand_upto']??date('Y-m-d');
                    $consumer_demand['penalty']=0;
                    $consumer_demand['emp_details_id']=$this->emp_id;
                    $consumer_demand['created_on']=date('Y-m-d H:i:s');
                    $consumer_demand['connection_type']='Fixed';
                    
                    // print_r($consumer_demand);
                    //  echo "<br>";
                    $demand_id = $this->consumer_demand_model->insertData($consumer_demand);
					$demand_no = $ulb_nm.'EX'.str_pad($ward_nm, 3, "0", STR_PAD_LEFT).str_pad($this->emp_id, 5, "0", STR_PAD_LEFT).'/'.str_pad($demand_id, 6, "0", STR_PAD_LEFT);
					$d_update=$this->WaterMobileModel->updateNew(array('id'=>$demand_id),array('demand_no'=>$demand_no),'tbl_consumer_demand');


                }
				
				//connection_type_id = 3 -- Fixed
                if($data['connection_type_id']==3)
                {	
                	$this->generate_demand_model->generate_demand($consumer_id,date('Y-m-t'));
                }
				 
				if(in_array($data['connection_type_id'],[1,2]))
				{
					$meter_status=[
						'consumer_id'=>$consumer_id,
						'connection_date'=>$data['connection_date'],
						'meter_status'=>1,
						'emp_details_id'=>$this->emp_id,
						'connection_type'=>$data['connection_type_id'],
						'meter_no'=>$data['meter_no'],
						'meter_doc'=>'',
						'final_meter_reading'=>$data['initial_reading'],
						// 'meter_intallation_date'=>$data['connection_date'],
						'initial_reading'=>0.5,
						'created_on'=>date('Y-m-d h:i:s'),						
					];
					$consumer_initial_meter=[
						'consumer_id'=>$consumer_id,
						'initial_reading'=>$data['initial_reading'],
						'emp_details_id'=>$this->emp_id,
						'created_on'=>date('Y-m-d h:i:s'),	
					];
					
					// $this->meter_status_model->insertData($meter_status);
					$this->last_reading->insertData($consumer_initial_meter);
					// $this->generate_demand_model->generate_demand($consumer_id,date('Y-m-t'),$data['initial_reading']);
					$this->generate_demand_model->averageBulling($consumer_id,date('Y-m-t'),$data['initial_reading']);
					
				}
				
                
        	}			
			
			if($this->water->transStatus() === FALSE)
			{
				$this->water->transRollback();
				
				flashToast('message', "Transaction failed");
				//return $this->response->redirect(base_url('BankReconciliationAllModuleList/detail/'.$data['module'].'/'.$data['from_date'].'/'.$data['to_date']));
				return view('water/water_connection/add_existing_consumer',$data);
			}
			else
			{    
				if($connection_id)
				{
					$this->water->transCommit();
					
					return redirect()->to('WaterViewConsumerDetails/index/'.md5($connection_id))->with('message','Add Cunsumer Successfully');
				}
			}
        }
		
        return view('water/water_connection/add_existing_consumer',$data);
     	
    }
	
  	public function getHoldingDetails()
    {   
       
       if($this->request->getMethod()=='post')
       {
       		$ward_id=$_POST['ward_id']??null;
       		$holding_no=$_POST['holding_no'];			   		
       		$response = $this->WaterApplyNewConnectionCitizen->validate_holding_no($holding_no);			
			if($response['response']==true)
			{
				$chek = $this->model_water_consumer->cout_holding($holding_no);
				if($chek!=0)
				{
					$response = ['response'=>false, 'dd'=> ['message'=>'Consumer Already Exist With This Holding No.', ]];
				}
			}
			
       }
       else
       {
       		$response=['response'=>false, 'dd'=> ['message'=>'Only Post Allowed.', ]];
       }
	   return json_encode($response);   
	 
    }

	public function validate_saf_no()
	{
		if($this->request->getMethod()=='post')
       {
       		$saf_no=$_POST['saf_no'];       		
       		$response = $this->WaterApplyNewConnectionCitizen->validate_saf_no(['saf_no'=>$saf_no]);
			
			if($response['response']==true)
			{
				$chek = $this->model_water_consumer->cout_saf($saf_no);
				if($chek!=0)
				{
					$response = ['response'=>false, 'dd'=> ['message'=>'Consumer Already Exist With This Saf No.', ]];
				}
			}
			else
			{
				$response = ['response'=>false, 'dd'=> ['message'=>'Saf No. Not Found', ]];
			}


       }
       else
       {
       		$response=['response'=>false,'dd'=> ['message'=>'Only POst Allowed', ]];
       }
	   return json_encode($response);
	}

    public function getUnitRateDetails()
    {
    	 if($this->request->getMethod()=='post')
       	 {	
       	 	$inputs=arrFilterSanitizeString($this->request->getVar());
       	 	//print_r($inputs);

       		$property_type_id=$inputs['property_type_id'];
       		$connection_type_id=$inputs['connection_type_id'];
       		$where ='';
       		if($connection_type_id==0)
       		{
       			$where=" where property_type_id=$property_type_id and type='Fixed'";
       		}
       		else if($connection_type_id==1)
       		{
       			$where=" where property_type_id=$property_type_id and type='Meter'";
       		}
       		else if($connection_type_id==2)
       		{
       			$where=" where property_type_id=$property_type_id and type='Gallon'";
       		}
       		//echo $where;

       		if($result=$this->fixed_meter_rate_model->getRateDetails($where))
       		{
       			$response=['response'=>true,'result'=>$result];
       		}
       		else
       		{
       			$response=['response'=>false];
       		}

        }
        else
        {
       		$response=['response'=>false];
        }
        
		  return json_encode($response);
    }
  	
}
?>
