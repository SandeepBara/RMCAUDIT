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


class WaterUpdateConsumerConnectionJsk extends AlphaController
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
	protected $meter_status_model;
	protected $generate_demand_controller;
	protected $consumer_initial_meter;
	


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
		helper('form','url');
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
    
	public function __destruct()
    {
        $this->db->close();
		$this->db_property->close();
        $this->dbSystem->close();
    }
	
	public function index($consumer_id)
	{
		
		$data=array();
		$data['consumer_id']=$consumer_id;//echo($consumer_id);
		$data['consumer_dtls']=$this->search_consumer_mobile_model->getConsumerDetailsbyId($consumer_id);
		$data['connection_dtls']=$this->meter_status_model->getConnectionDetails($consumer_id);
		$last_reading=$this->consumer_initial_meter->getLastMeterReading($consumer_id);
		$data['AllConnectionDetails'] = $this->meter_status_model->getAllConnectionDetails($consumer_id);
		$data['last_reading'] = $last_reading;
		$regex = '/^\s*[+\-]?(?:\d+(?:\.\d*)?|\.\d+)\s*$/';

		if($this->request->getMethod()=='post')
		{			
			$inputs=filterSanitizeStringtoUpper($this->request->getVar());
			// print_var($inputs);die;
			$get_prev_conn_on_same_date=$this->meter_status_model->check_exists_connection_on_same_date($consumer_id,$inputs['connection_date']);
			
			$get_prev_conn_date=$this->meter_status_model->getLastConnectionDetails($data['consumer_dtls']['id']);
			
			$prev_conn_date=$get_prev_conn_date['connection_date'];
			
			// $last_reading=$this->consumer_initial_meter->getLastMeterReading($consumer_id);
			
			$get_demanddetails=$this->demand_model->getLastDemand($consumer_id);
			
			$demand_upto=$get_demanddetails['demand_upto'];
			
			$get_conn_type_before=$this->meter_status_model->check_exists_same_connection_type_before($consumer_id);
			$connection_type_earlier=$get_conn_type_before['connection_type'];
			
			$curr_date=date('Y-m-d');

			$rules=['connection_type'=>
						[
							'rules'=>'required',
							'errors'=>[
								'required'=>'Connection Type is requird',
								
							]
						],
						'connection_date'=>
						[
							'rules'=>'required',
							'errors'=>[
								'required'=>'Connection Date is requird',
							]
						],						
						
			];
			if($inputs['connection_type']==1)
			{

				$meter_rul = ['connection_type'=>
								[
									'rules'=>'required',
									'errors'=>[
										'required'=>'Connection Type is requird',
										
									]
									],
								'connection_date'=>
								[
									'rules'=>'required',
									'errors'=>[
										'required'=>'Connection Date is requird',
									]
									],
								'meter_doc' => 
								[
									'rules'=>'uploaded[meter_doc]|max_size[meter_doc,3072]|ext_in[meter_doc,pdf,jpg,png,jpeg]',
									'errors'=>[
										'uploaded'=>'Document is requird',
										
										'max_size'=>'Document size must be less than 3 mb',
										'ext_in'=>'Document type must be pdf'
									]
									],
							'meter_no'=>
							[
								'rules'=>'required',
								'errors'=>[
									'required'=>'Meter No is requird',
								]
								],	
							
						 ];
				$rules=$meter_rul;
			}
			if(($data["connection_dtls"]['connection_type']==1 or $data["connection_dtls"]['connection_type']==2) and $inputs['connection_type']==1)
			{
				$old_meter_rul=['connection_type'=>
								[
									'rules'=>'required',
									'errors'=>[
										'required'=>'Connection Type is requird',
										
									]
									],
								'connection_date'=>
								[
									'rules'=>'required',
									'errors'=>[
										'required'=>'Connection Date is requird',
									]
									],
								'meter_doc' => 
								[
									'rules'=>'uploaded[meter_doc]|max_size[meter_doc,1024]|ext_in[meter_doc,pdf,jpg,png,jpeg]',
									'errors'=>[
										'uploaded'=>'Document is requird',
										
										'max_size'=>'Document size must be less than 1024 kb',
										'ext_in'=>'Document type must be pdf'
									]
									],
							'meter_no'=>
							[
								'rules'=>'required',
								'errors'=>[
									'required'=>'Meter No is requird',
								]
								],	
							'final_meter_reading'=>
							[
								'rules'=>'required',
								'errors'=>[
									'required'=>'Final Mmeter Reading is requird',
									
								]
								],
				];
				$rules=$old_meter_rul;
			}
			elseif(($data["connection_dtls"]['connection_type']==1 or $data["connection_dtls"]['connection_type']==2) and $inputs['connection_type']!=1)
			{
				$old_meter_rul=['connection_type'=>
								[
									'rules'=>'required',
									'errors'=>[
										'required'=>'Connection Type is requird',
										
									]
									],
								'connection_date'=>
								[
									'rules'=>'required',
									'errors'=>[
										'required'=>'Connection Date is requird',
									]
									],									
							'final_meter_reading'=>
							[
								'rules'=>'required',
								'errors'=>[
									'required'=>'Final Mmeter Reading is requird',
									
								]
								],
				];
				$rules=$old_meter_rul;
			}
			
			if(!$this->validate($rules))
			{ 
				$data['error']= display_error($this->validator,'meter_doc');
				
			}			
			else if($connection_type_earlier==$inputs['connection_type'] && $connection_type_earlier !=1)
			{
				$data['error']="You can not update same connection type as before";
			}
			else if($inputs['connection_date']>$curr_date )
			{
				$data['error']="Connection Date can not be greater than Current Date";
			}
			else if(isset($inputs['final_meter_reading']) && $inputs['final_meter_reading']!="" and preg_match($regex,$inputs['final_meter_reading'])==0)
			{
				$data['error']="Enter Valid Number";
			}
			//else if(($prev_conn_date==$inputs['connection_date'] || $prev_conn_date>$inputs['connection_date']))
			else if(( $prev_conn_date>$inputs['connection_date']))
			{
				$data['error']="Can not Update Connection Type on Same Date or less than previous date";
			}
			else if($demand_upto>$inputs['connection_date'])
			{
				$data['error']="Can not update Connection Date, Demand already generated upto that month";
			}
			else if(isset($inputs['final_meter_reading']) && $inputs['final_meter_reading']!="" and is_numeric($inputs['final_meter_reading']) and $inputs['final_meter_reading']!="" and ($inputs['final_meter_reading']<$last_reading  || $inputs['final_meter_reading']<=0))
			{
				$data['error']="Meter Reading should be greater than last reading";
			}
			elseif($inputs['connection_type']==4 && strtoupper(trim($inputs['meter_no'])) != strtoupper($get_prev_conn_date['meter_no']))
			{
				$data['error']="You Can Meter/Fixed The Connection On Priviuse Meter";
			}
			else
			{
				
				$this->db->transBegin();
				
				$consumer_connection_dtls=array();
				$consumer_connection_dtls['consumer_id']=$data['consumer_dtls']['id'];
				$consumer_connection_dtls['connection_date']=$inputs['connection_date'];
				$consumer_connection_dtls['connection_type']= ($inputs['connection_type']!=4 ? $inputs['connection_type'] : $connection_type_earlier);
				$consumer_connection_dtls['meter_no']=$inputs['meter_no'];				
				
				$consumer_connection_dtls['final_meter_reading']=$inputs['final_meter_reading']??null;
				if(isset($inputs['initial_meter_reading'])&& !empty($inputs['initial_meter_reading']))
					$consumer_connection_dtls['initial_reading']=$inputs['initial_meter_reading']??null;

				$consumer_connection_dtls['meter_status']=($inputs['connection_type']!=4 ? 1 : 0);
				$consumer_connection_dtls['emp_details_id']=$this->emp_details_id;
				
				$upto_date=$consumer_connection_dtls['connection_date'];
				
				$final_meter_reading=$inputs['final_meter_reading']??null;
				$this->generate_demand_controller->tax_generation($data['consumer_dtls']['id'],$upto_date,$final_meter_reading);
				// print_var($consumer_connection_dtls);die;
		        $insert_id=$this->meter_status_model->insertData($consumer_connection_dtls);

		        if($insert_id)
		        { 
		            
		            // $rules = ['meter_doc' => 'uploaded[meter_doc]|max_size[meter_doc,1024]|ext_in[meter_doc,pdf]'];

		            // if($this->validate($rules))
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

		                        if(($consumer_connection_dtls['connection_type']==1 || $consumer_connection_dtls['connection_type']==2))
		                        {
		                              // insert initial reading of meter or gallon if connection type updated is gallon or meter
									$initial_reding =  0.5;
									if(in_array($data['connection_dtls']['connection_type'],[3,1]))
									{
										$initial_reding = $inputs['initial_meter_reading']??0.5;
									}
		                             $consumer_initial_meter=array();
		                             $consumer_initial_meter['consumer_id']=$consumer_connection_dtls['consumer_id'];
		                             $consumer_initial_meter['initial_reading']=$initial_reding ;
		                             $consumer_initial_meter['emp_details_id']=$consumer_connection_dtls['emp_details_id'];
		                             $consumer_initial_meter['created_on']=date('Y-m-d H:i:s');
		                             
		                             if($inputs['connection_type']!=4)
									 {
										 $this->initial_meter_reading->insertData($consumer_initial_meter);

									 }
		                             
		                        }
		                      
		                    }
		                }
		            }
					if($this->db->transStatus()===FALSE)
					{
						$this->db->transRollback();	
						flashToast("message", "Something errordue to Update!!!");  
						return $this->response->redirect(base_url('WaterUpdateConsumerConnectionJsk/index/'.$consumer_id));	
					}
					else
					{						
						$this->db->transCommit();	
						flashToast("message", "Update Connection successfully!!!"); 
						return redirect()->to(base_url()."/WaterUpdateConsumerConnectionJsk/index/".$consumer_id);	
					}
		        }
				
				$this->db->transRollback();	
				flashToast("message", "Something errordue to Update!!!5");  
				return $this->response->redirect(base_url('WaterUpdateConsumerConnectionJsk/index/'.$consumer_id));	
				
			}

			
		} 
		// print_var($data);
		return view('water/water_connection/update_connection_details',$data);
		
	}


	
}
