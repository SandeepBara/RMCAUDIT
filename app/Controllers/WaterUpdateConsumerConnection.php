<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PostModel;
use App\Models\model_ward_mstr;
use App\Models\model_ulb_mstr;
use App\Models\WaterMobileModel;
use App\Models\WaterSearchConsumerMobileModel;
use App\Models\WaterConsumerDemandModel;
use App\Models\WaterPaymentModel;
use App\Models\model_water_consumer_initial_meter;
use App\Models\WaterMeterStatusModel;
use App\Controllers\WaterGenerateDemand;
use App\Models\WaterConsumerInitialMeterReadingModel;


class WaterUpdateConsumerConnection extends MobiController
{
	protected $db;
	protected $dbSystem;
	protected $db_property;
	
	//protected $db_name;
	protected $model_ward_mstr;
	protected $model_ulb_mstr;
	protected $ulb_mstr_id;
	protected $emp_details_id;
	protected $user_type_mstr_id;
	


	public function __construct()
    {
    	

    	$data =(array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $this->ulb_mstr_id = $ulb_mstr["ulb_mstr_id"];

        $emp_mstr = $Session->get("emp_details");
        $this->emp_details_id = $emp_mstr["id"];
        $this->user_type_mstr_id = $emp_mstr["user_type_mstr_id"];
       
        parent::__construct();
    	helper(['db_helper']);
        if($db_name = dbConfig("water")){
			//echo $db_name;
            $this->db = db_connect($db_name);            
        }
        if($db_name = dbConfig("property")){
			//echo $db_name;
            $this->db_property = db_connect($db_name);            
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        } 

        helper(['form']);
		//$db_name = db_connect("db_rmc_property");		
        $this->model = new model_ward_mstr($this->dbSystem);
		$this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
		$this->water_mobile_model=new WaterMobileModel($this->db);
		$this->search_consumer_mobile_model=new WaterSearchConsumerMobileModel($this->db);
		$this->demand_model=new WaterConsumerDemandModel($this->db);
		$this->payment_model=new WaterPaymentModel($this->db);
		$this->consumer_initial_meter=new model_water_consumer_initial_meter($this->db);
		$this->meter_status_model=new WaterMeterStatusModel($this->db);
		$this->generate_demand_controller=new WaterGenerateDemand();
		$this->initial_meter_reading=new WaterConsumerInitialMeterReadingModel($this->db);

    }

	
	
	public function index($consumer_id,$from=null)
	{
		
		$data=array();
		$data['consumer_id']=$consumer_id;
		$data['consumer_dtls']=$this->search_consumer_mobile_model->getConsumerDetailsbyId($consumer_id);
		$data['connection_dtls']=$this->meter_status_model->getConnectionDetails($consumer_id);
		//print_var($data['consumer_dtls']);

		//print_r($data['connection_dtls']);
		// regular expression to check a decimal number
		$regex = '/^\s*[+\-]?(?:\d+(?:\.\d*)?|\.\d+)\s*$/';

		//echo preg_match($regex, '0.0'); 
		if($this->request->getMethod()=='post')
		{

			$inputs=filterSanitizeStringtoUpper($this->request->getVar());
			// print_var($inputs);
			
			$get_prev_conn_on_same_date=$this->meter_status_model->check_exists_connection_on_same_date($consumer_id,$inputs['connection_date']);
			
			$get_prev_conn_date=$this->meter_status_model->getLastConnectionDetails($data['consumer_dtls']['id']);
			//print_r($prev_conn_date);
			
			$prev_conn_date=$get_prev_conn_date['connection_date'];
			
			$last_reading=$this->consumer_initial_meter->getLastMeterReading($consumer_id);
			//exit();
			
			$get_demanddetails=$this->demand_model->getLastDemand($consumer_id);
			//print_r($get_demanddetails);
			$demand_upto=$get_demanddetails['demand_upto'];
			
			$get_conn_type_before=$this->meter_status_model->check_exists_same_connection_type_before($consumer_id);
			$connection_type_earlier=$get_conn_type_before['connection_type'];
			
			$curr_date=date('Y-m-d');
			
			
			if($connection_type_earlier==$inputs['connection_type'])
			{
				$data['error']="You can not update same connection type as before";
			}
			elseif($data['consumer_dtls']['area_sqft']==0)
			{
				$data['error']="Update Area first";
			}
			else if($inputs['connection_date']>$curr_date )
			{
				$data['error']="Connection Date can not be greater than Current Date";
			}
			else if(isset($inputs['final_meter_reading']) && $inputs['final_meter_reading']!="" and preg_match($regex, $inputs['final_meter_reading'])==0)
			{
				$data['error']="Enter Valid Number";
			}
			else if(($prev_conn_date==$inputs['connection_date'] || $prev_conn_date>$inputs['connection_date']))
			{
				$data['error']="Can not Update Connection Type on Same Date or less than previous date";
			}
			else if($demand_upto>$inputs['connection_date'])
			{
				$data['error']="Can not update Connection Date, Demand already generated upto that month";
			}
			else if(isset($inputs['final_meter_reading']) && $inputs['final_meter_reading']!="" and is_numeric($inputs['final_meter_reading']) and $inputs['final_meter_reading']!="" and $inputs['final_meter_reading']<$last_reading  and $inputs['final_meter_reading']<=0)
			{
				$data['error']="Meter Reading should be greater than last reading";
			}
			else
			{
				//print_r($inputs);
				$consumer_connection_dtls=array();
				$consumer_connection_dtls['consumer_id']=$data['consumer_dtls']['id'];
				$consumer_connection_dtls['connection_date']=$inputs['connection_date'];
				$consumer_connection_dtls['connection_type']=$inputs['connection_type'];
				$consumer_connection_dtls['meter_no']=$inputs['meter_no'];
				
				$consumer_connection_dtls['final_meter_reading']=$inputs['final_meter_reading']??null;
				$consumer_connection_dtls['meter_status']=1;
				$consumer_connection_dtls['emp_details_id']=$this->emp_details_id;
				
				$upto_date=$consumer_connection_dtls['connection_date'];
				
				$final_meter_reading=$inputs['final_meter_reading']??null;
				$tax_id = $this->generate_demand_controller->tax_generation($data['consumer_dtls']['id'],$upto_date,$final_meter_reading);
				
				
				
				//print_r($consumer_connection_dtls);
		        $insert_id=$this->meter_status_model->insertData($consumer_connection_dtls);

		        if($insert_id)
		        { 
		            
		            $rules = ['meter_doc' => 'uploaded[meter_doc]|max_size[meter_doc,1024]|ext_in[meter_doc,pdf,jpg]'];

		            if($this->validate($rules))
		            {   
		              	
		                $file = $this->request->getFile('meter_doc');
		                $extension = $file->getExtension();
		                
		                $city = $this->model_ulb_mstr->getCity($this->ulb_mstr_id);
		                
		                if($file->isValid() && !$file->hasMoved())
		                {

		                    $newName = md5($insert_id).".".$extension;

		                    if($file->move(WRITEPATH.'uploads/'.$city['city'].'/meter_image',$newName))
		                    {
		 						
		                        $this->meter_status_model->updateMeterDocumentName($insert_id,$newName);

		                        if($consumer_connection_dtls['connection_type']==1 or $consumer_connection_dtls['connection_type']==2)
		                        {
		                              // insert initial reading of meter or gallon if connection type updated is gallon or meter

		                             $consumer_initial_meter=array();
		                             $consumer_initial_meter['consumer_id']=$consumer_connection_dtls['consumer_id'];
		                             $consumer_initial_meter['initial_reading']=0.5;
		                             $consumer_initial_meter['emp_details_id']=$consumer_connection_dtls['emp_details_id'];
		                             $consumer_initial_meter['created_on']=date('Y-m-d H:i:s');
		                             
		                             //print_r($consumer_initial_meter);
		                             $this->initial_meter_reading->insertData($consumer_initial_meter);
		                             
		                        }
		                      
		                    }
		                }
		            }

					if($from && $tax_id)
					{
						return $this->response->redirect(base_url('WaterViewConsumerMobile/consumer_demand_receipt/'.$consumer_id.'/'.md5($tax_id)));
					}
					return $this->response->redirect(base_url('WaterUpdateConsumerConnection/index/' . $consumer_id));
		        }


				
			}

			
		}
		return view('mobile/water/update_connection_details',$data);
		
	}


	
	
	
	
}
