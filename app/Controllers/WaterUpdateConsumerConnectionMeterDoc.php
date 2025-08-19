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
use App\Models\water_consumer_details_model;
//---------21-01-22---------------
use App\Models\model_view_water_consumer;
use CodeIgniter\HTTP\Response;
use App\Models\WaterPropertyModel;
use App\Models\WaterConnectionTypeModel;
use App\Models\WaterConnectionThroughModel;
use App\Models\WaterPipelineModel;
use App\Models\Water_name_transfer_log_model;
use App\Models\model_document_mstr;
use Exception;

use App\Models\WaterConsumerTaxModel;
use App\Models\WaterFixedMeterRateModel;
use App\Models\model_water_consumer;
use App\Models\WaterRateChartModel;
use App\Models\WaterDemandPenaltyMaster;
use App\Models\WaterRevisedMeterRateModel;
use App\Models\WaterMeterRateCalculationModel;
use App\Models\model_water_sms_log;
use App\Models\model_water_reading_doc;

class WaterUpdateConsumerConnectionMeterDoc extends MobiController
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
	protected $initial_meter_reading;
	protected $consumer_details_model;
	protected $water_property_model;
	protected $conn_type_model;
	protected $model;
	protected $model_view_water_consumer;
	protected $Water_name_transfer_log_model;
	protected $model_document_mstr;
	protected $consumer_demand_model;
	protected $generate_demand_controller;
	


	public function __construct()
    {
    	parent::__construct();
    	helper(['db_helper']);

    	$data =(array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl")??getUlbDtl();
        $this->ulb_mstr_id = $ulb_mstr["ulb_mstr_id"];

        $emp_mstr = $Session->get("emp_details");
        $this->emp_details_id = $emp_mstr["id"];
		$this->emp_id =$this->emp_details_id;
		$this->ulb_type_id =  $this->ulb_mstr_id;
		$this->ulb_details = $ulb_mstr;
        $this->user_type_mstr_id = $emp_mstr["user_type_mstr_id"];
       
        
        if($db_name = dbConfig("water"))
		{
			//echo $db_name;
            $this->db = db_connect($db_name);            
        }
        // if($db_name = dbConfig("property")){
		// 	//echo $db_name;
        //     $this->db_property = db_connect($db_name);            
        // }
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
		$this->consumer_details_model=new water_consumer_details_model($this->db);
		//---------21-01-22---------------
		$this->model_view_water_consumer = new model_view_water_consumer($this->db);
		$this->consumer_demand_model = new WaterConsumerDemandModel($this->db);
		$this->water_property_model=new WaterPropertyModel($this->db);
		$this->conn_type_model=new WaterConnectionTypeModel($this->db);
		$this->conn_through_model=new WaterConnectionThroughModel($this->db);
		$this->pipeline_model=new WaterPipelineModel($this->db);
		$this->Water_name_transfer_log_model = new Water_name_transfer_log_model($this->db);
		$this->model_document_mstr = new model_document_mstr($this->db);
		
    }

	function __destruct() 
	{
		if($this->db)
		$this->db->close();
		if($this->dbSystem)
		$this->dbSystem->close();
		
	}
	
	public function index($consumer_id,$meter_status_id)
	{
		

		$data=array();
		$data['user_type']=$this->user_type_mstr_id;
		$data['consumer_id']=$consumer_id; //echo($consumer_id);
		$data['consumer_details']=$this->search_consumer_mobile_model->getConsumerDetailsbyId($consumer_id);
		if(empty($data['consumer_details']) || count($data['consumer_details'])==0)
			$data['consumer_details']=$this->model_view_water_consumer->waterConsumerDetailsById($consumer_id);
		$data['applicant_details']=$this->payment_model->fetch_all_application_data(md5($data['consumer_details']['apply_connection_id']));
		$data['consumer_owner_details']=$this->consumer_details_model->consumerDetailsbyMd5($consumer_id);
		$data['connection_dtls']=$this->meter_status_model->getConnectionDetails($consumer_id);
		
		$data['meter_status']=$this->meter_status_model->getMeterDocUploadedofFirstConnection($consumer_id);
		//print_r($data['meter_status']);
		
		if($this->request->getMethod()=='post')
		{

				
			 	$inputs=arrFilterSanitizeString($this->request->getVar());
			 	//print_r($inputs);
				
				$meter_no=$inputs['meter_no'];

				$meter_connection_date = $inputs['meter_connection_date'];
				
				
				
		        // $update=$this->meter_status_model->updateMeterDetails($meter_status_id,$meter_no);
                
				$update = $this->meter_status_model->updateMeterDetailsWithDate($meter_status_id, $meter_no, $meter_connection_date);

		     
		        if($update)
		        { 
		          
		            $rules = ['meter_doc' => 'uploaded[meter_doc]|max_size[meter_doc,1024]|ext_in[meter_doc,pdf,jpg]'];

		            if($this->validate($rules))
		            {   
		              	
		                $file = $this->request->getFile('meter_doc');
		                $extension = $file->getExtension();
		                
		                $city = $this->model_ulb_mstr->getCity($this->ulb_mstr_id);
		                
		                if($file->isValid() && !$file->hasMoved())
		                {

		                    $newName = $meter_status_id.".".$extension;

		                  

		                    if($file->move(WRITEPATH.'uploads/'.$city['city'].'/meter_image',$newName))
		                    {
		 						
		                        $this->meter_status_model->updateMeterDocumentNamebyMd5($meter_status_id,$newName);
		                         flashToast('success', 'Updated Successfully!!');
		                    }
		                
		                }
		            }


		        }


		

			
		}
		//echo"<pre>";print_r($data);echo"</pre>";
		return view('water/water_connection/update_meter_doc',$data);
		
	}
	public function search_consumer($consumer_id)
	{
		$data['consumer_details']=$this->search_consumer_mobile_model->getConsumerDetailsbyId($consumer_id);
		$data['meter_status']=$this->meter_status_model->getLastConnectionDetails($data['consumer_details']['id']);
		//print_var($data['meter_status']);die;
		$meter_status_id = md5($data['meter_status']['id']);
		return $this->response->redirect(base_url('WaterUpdateConsumerConnectionMeterDoc/update_meter/'.$consumer_id.'/'.$meter_status_id));

	}
	public function update_meter($consumer_id,$meter_status_id)
	{
		$data=array();
		$data['consumer_id']=$consumer_id;//echo($consumer_id);
		$data['consumer_dtls']=$this->search_consumer_mobile_model->getConsumerDetailsbyId($consumer_id);
		$data['connection_dtls']=$this->meter_status_model->getConnectionDetails($consumer_id);
		$get_demanddetails=$this->consumer_demand_model->getLastDemand(md5($consumer_id));
		$last_demand_upto = $get_demanddetails['demand_upto'];
		// print_var($get_demanddetails);
		
		$regex = '/^\s*[+\-]?(?:\d+(?:\.\d*)?|\.\d+)\s*$/';

		if($this->request->getMethod()=='post')
		{

			$inputs=filterSanitizeStringtoUpper($this->request->getVar());
			
			$get_prev_conn_on_same_date=$this->meter_status_model->check_exists_connection_on_same_date($consumer_id,$inputs['connection_date']);
			
			$get_prev_conn_date=$this->meter_status_model->getLastConnectionDetails($data['consumer_dtls']['id']);
			
			$prev_conn_date=$get_prev_conn_date['connection_date'];
			
			$last_reading=$this->consumer_initial_meter->getLastMeterReading($consumer_id);
			
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
									'rules'=>'uploaded[meter_doc]|max_size[meter_doc,1024]|ext_in[meter_doc,pdf]',
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
									'rules'=>'uploaded[meter_doc]|max_size[meter_doc,1024]|ext_in[meter_doc,pdf]',
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
			else if($connection_type_earlier==$inputs['connection_type'])
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
				$this->generate_demand_controller->tax_generation($data['consumer_dtls']['id'],$upto_date,$final_meter_reading);
				
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


		        }


				
			}

			
		} 
		return view('water/water_connection/update_meter',$data);
		
	}

	public function consumer_basice_dtl($consumer_id)
	{
		echo "page No found";
		
	}
	public function consumer_owner_dtl($consumer_id)
	{   
        
        $data=array();
        $data['user_type']=$this->user_type_mstr_id;
        $data['consumer_id']=$consumer_id; 
        $data['property_type_list']=$this->water_property_model->property_list();
        $data['conn_type_list']=$this->conn_type_model->conn_type_list();
        $data['conn_through_list']=$this->conn_through_model->conn_through_list();
        $data['pipeline_type_list']=$this->pipeline_model->pipeline_list();
        $data['ulb_mstr_id']=$this->ulb_mstr_id;
        $data['ward_list']=$this->model->getWardList($data);
        $ulb_name = (session()->get('ulb_dtl')['ulb_name']??null);
		//print_var($data['ward_list']);

        $data['consumer_details']=$this->model_view_water_consumer->waterConsumerDetailsById($consumer_id);
		// print_var($data['consumer_details']);
        if(!empty($data['consumer_details']))
        {
            $data['applicant_details']=$this->payment_model->application_data($data['consumer_details']['apply_connection_id']);
			
            $data['owner_name']=$this->consumer_details_model->consumerDetails($data['consumer_details']['id']);
			
            $data['connection_dtls']=$this->meter_status_model->getLastConnectionDetails($data['consumer_details']['id']);
            
            $get_last_reading=$this->initial_meter_reading->initial_meter_reading($data['consumer_details']['id']);
            $data['last_reading']=$get_last_reading['initial_reading'];
			if($this->request->getMethod()=='post')
			{
				$this->db->transBegin();
				try
				{	
					$inputs=arrFilterSanitizeString($this->request->getVar());
					$rules = [						
						'remarks'=>'required',
						'document'=>'uploaded[document]|max_size[document,3072]|ext_in[document,png,pdf]',
					];
					if(!$this->validate($rules))
					{
						$data['validation']=$this->validator;
                    	return view('water/water_connection/consumer_owner_dtl', $data);						
					}
					
					if(isset($inputs['address_check_box']) && $inputs['address_check_box']=='on')
					{ 
						$data['address_check_box']='on';						
						$consumer_details['address']=$inputs['address'];	
						if(isset($inputs['ward_mstr_id']))
						{
							$consumer_details['ward_mstr_id']=$inputs['ward_mstr_id'];				
						}					
						$chek = $this->model_view_water_consumer->update_consume($data['consumer_details']['id'],$consumer_details);						
						
						if(!$chek)
						{
							throw new Exception("Some Error Occurst Due to Consumer Address updatetion ");
						}
						else
						{
							$data['consumer_details']=$this->model_view_water_consumer->getConsumerByMd5Id($consumer_id);
							$consumer_log_array=[];
							$consumer_log_array['consumer_id']=$data['consumer_details']['id'];							
							$consumer_log_array['connection_type_id']=$data['consumer_details']['connection_type_id'];
							$consumer_log_array['connection_through_id']=$data['consumer_details']['connection_through_id'];
							$consumer_log_array['property_type_id']=$data['consumer_details']['property_type_id'];
							$consumer_log_array['category']=$data['consumer_details']['category'];
							$consumer_log_array['ward_mstr_id']=$data['consumer_details']['ward_mstr_id'];
							$consumer_log_array['prop_dtl_id']=$data['consumer_details']['prop_dtl_id'];

							$consumer_log_array['area_sqmt']=$data['consumer_details']['area_sqmt'];							
							$consumer_log_array['area_sqft']=$data['consumer_details']['area_sqft'];
							$consumer_log_array['pipeline_type_id']=$data['consumer_details']['pipeline_type_id'];
							$consumer_log_array['flat_count']=$data['consumer_details']['flat_count'];
							$consumer_log_array['k_no']=$data['consumer_details']['k_no'];
							$consumer_log_array['bind_book_no']=$data['consumer_details']['bind_book_no'];
							$consumer_log_array['account_no']=$data['consumer_details']['account_no'];

							$consumer_log_array['electric_category_type']=$data['consumer_details']['electric_category_type'];							
							$consumer_log_array['created_on']=$data['consumer_details']['created_on'];
							$consumer_log_array['entry_type']=$data['consumer_details']['entry_type'];
							$consumer_log_array['is_meter_working']=$data['consumer_details']['is_meter_working'];
							$consumer_log_array['old_consumer_no']=$data['consumer_details']['old_consumer_no'];
							$consumer_log_array['holding_no']=$data['consumer_details']['holding_no'];
							$consumer_log_array['saf_dtl_id']=$data['consumer_details']['saf_dtl_id'];

							$consumer_log_array['saf_no']=$data['consumer_details']['saf_no'];							
							$consumer_log_array['address']=$data['consumer_details']['address'];
							$consumer_log_array['apply_from']=$data['consumer_details']['apply_from'];

							$consumer_log_array['remarks']=$inputs['remarks'];
							$consumer_log_array['ip_address']=$_SERVER['REMOTE_ADDR'];
							$consumer_log_array['emp_details_id']=$this->emp_details_id;
							$consumer_log_array['type']='Basic Update';
							$consumer_log_inserted_id = $this->Water_name_transfer_log_model->insertData_tbl_consumer_log($consumer_log_array);
							if(!$consumer_log_inserted_id)
							{
								throw new Exception("Some Error Occurst Due To Consumer Update Please Contact To Admin 1");
							}
							else
							{
								$file = $this->request->getFile('document');
								$extension = $file->getExtension();							
								$city = $this->model_ulb_mstr->getCity($this->ulb_mstr_id);
								if($file->isValid() && !$file->hasMoved())
								{
									$newName = md5($consumer_log_inserted_id).".".$extension;
									if($file->move(WRITEPATH.'uploads/'.$city['city'].'/consumer_update',$newName))
									{
										$update_data['document']=$city['city'].'/consumer_update'.'/'.$newName	;							
										$chek = $this->Water_name_transfer_log_model->updateData_tbl_consumer_log($update_data,$consumer_log_inserted_id);	
										if(!$chek)
										{
											throw new Exception("Some Error Occurst Please Contact To Admin ");
										}								
									
									}
								}
							}

						}					
					}
					if(isset($inputs['owner_check_box']) && $inputs['owner_check_box']=='on')
					{
						$data['owner_check_box']='on';
						$icount = count($inputs['owner_name']);
						for($i=0;$i<$icount;$i++)
						{ 
							$owner_detals=[];
							$owner_detals['father_name']=$inputs['guardian_name'][$i];
							$owner_detals['mobile_no']=$inputs['mobile_no'][$i];
							$owner_detals['city']=$inputs['city'][$i];
							$owner_detals['district']=$inputs['district'][$i];
							$owner_detals['state']=$inputs['state'][$i];
							$owner_id=isset($inputs['woner_id'.$i]) && !empty($inputs['woner_id'.$i])?$inputs['woner_id'.$i]:'';							
							if($owner_id!="")
							{
								$chek = $this->model_view_water_consumer->update_owner($owner_id,$owner_detals);

								if(!$chek)
								{
									throw new Exception("Some Error Occurst Due to Consumer Owner Update ");
								}
								else
								{
									$log_array=[];
									$log_array['consumer_id']=$data['consumer_details']['id'];
									$log_array['consumer_details_id']=$owner_id;
									$log_array['applicant_name']=$data['owner_name'][$i]['applicant_name'];
									$log_array['father_name']=$data['owner_name'][$i]['father_name'];
									$log_array['city']=$data['owner_name'][$i]['city'];
									$log_array['district']=$data['owner_name'][$i]['district'];
									$log_array['state']=$data['owner_name'][$i]['state'];
									$log_array['mobile_no']=$data['owner_name'][$i]['mobile_no'];
									$log_array['remarks']=$inputs['remarks'];
									$log_array['ip_address']=$_SERVER['REMOTE_ADDR'];
									$log_array['emp_details_id']=$this->emp_details_id;
									$consumer_log_array['type']='Basic Update';
									$inserted_id = $this->Water_name_transfer_log_model->insertData_tbl_consumer_details_log($log_array);
									if(!$inserted_id)
									{
										throw new Exception("Some Error Occurst Due To Consumer Owner Update Please Contact To Admin 1");
									}
									else
									{
										$file = $this->request->getFile('document');
										$extension = $file->getExtension();							
										$city = $this->model_ulb_mstr->getCity($this->ulb_mstr_id);
										if($file->isValid() && !$file->hasMoved())
										{
											$newName = md5($inserted_id).".".$extension;
											if($file->move(WRITEPATH.'uploads/'.$city['city'].'/basic_dtl_update',$newName))
											{
												$update_data['document']=$city['city'].'/basic_dtl_update'.'/'.$newName	;							
												$chek = $this->Water_name_transfer_log_model->updateData_tbl_consumer_details_log($update_data,$inserted_id);	
												if(!$chek)
												{
													throw new Exception("Some Error Occurst Please Contact To Admin ");
												}								
											
											}
										}
									}
									
					
								}
							}							
							
						}
					}					
					
					if($this->db->transStatus() === FALSE)
					{
						//die("rollback");
						$this->db->transRollback();
						flashToast("message", "Something errordue to Update!!!");  
						return $this->response->redirect(base_url('WaterUpdateConsumerConnectionMeterDoc/consumer_owner_dtl/'.$consumer_id));
				
					}
					else
					{ 
						//die("commit");
						$this->db->transCommit();
						flashToast("message", "Update Consumer Successfully!!!");
						return $this->response->redirect(base_url('WaterUpdateConsumerConnectionMeterDoc/consumer_owner_dtl/'.$consumer_id));
					}
				}
				catch(Exception $e)
				{
					$this->db->transRollback();
					flashToast("message", $e->getMessage()); 
					print_var($e->getMessage());
					die; 
					return $this->response->redirect(base_url('WaterUpdateConsumerConnectionMeterDoc/update/'.$consumer_id));
				}
			}
            return view('water/water_connection/consumer_owner_dtl',$data);
        }
		else
		{
			flashToast("massege", "Consumer Not Found");
			return $this->response->redirect(base_url('WaterConsumerList/index/consumer_owner_dtl'));
		}
        
    }
	public function last_meter_dtl($consumer_id)
	{
		
		echo "page No found";
	}

	public function name_trasfer($consumer_id)
	{
		$data['user_type']=$this->user_type_mstr_id;
        $data['consumer_id']=$consumer_id; 
        $data['property_type_list']=$this->water_property_model->property_list();
        $data['conn_type_list']=$this->conn_type_model->conn_type_list();
        $data['conn_through_list']=$this->conn_through_model->conn_through_list();
        $data['pipeline_type_list']=$this->pipeline_model->pipeline_list();
        $data['ulb_mstr_id']=$this->ulb_mstr_id;
		$ulb_name = (session()->get('ulb_dtl')['ulb_name']??null);
		$data['ward_list']=$this->model->getWardList($data);		
		$data['consumer_details']=$this->model_view_water_consumer->waterConsumerDetailsById($consumer_id);
		if(!empty($data['consumer_details']))
        {			
			//print_var($data['consumer_details']);
			$data['applicant_details']=$this->payment_model->application_data($data['consumer_details']['apply_connection_id']);
			$data['owner_name']=$this->consumer_details_model->consumerDetails($data['consumer_details']['id']);
			
			if($this->request->getMethod()=='post')
			{				
				$inputs=arrFilterSanitizeString($this->request->getVar());				
				
				try
				{
					$this->db->transBegin();
					$new_owner_ids='';
					$old_owner_ids='';
					$rules = [
						'owner_name'=>'required',
						'mobile_no'=>'required',
						'remarks'=>'required',
						'document'=>'uploaded[document]|max_size[document,3072]|ext_in[document,png,pdf]',
					];
					if(!$this->validate($rules))
					{
						$data['validation']=$this->validator;
                    	return view('water/water_connection/name_trasfer', $data);
					}
					// print_var($data['owner_name']);
					// die;
					if(isset($inputs['owner_name']))
					{
						$owner_arr=array();	
						for($i=0; $i < sizeof($inputs['owner_name']); $i++)
						{	
							$icount = count($inputs['owner_name']);
							for($i=0;$i<$icount;$i++)
							{ 
								$owner_detals=[];
								$owner_detals['applicant_name']=$inputs['owner_name'][$i];
								$owner_detals['father_name']=$inputs['guardian_name'][$i];
								$owner_detals['mobile_no']=$inputs['mobile_no'][$i];
								$owner_detals['city']=$inputs['city'][$i];
								$owner_detals['district']=$inputs['district'][$i];
								$owner_detals['state']=$inputs['state'][$i];
								$owner_detals['emp_details_id']=$this->emp_details_id;
								$owner_detals['created_on']=date('Y-m-d h:i:s');
								$owner_detals['consumer_id']=$data['consumer_details']['id'];
								$chek = $this->model_view_water_consumer->insert_owner($owner_detals);
								$new_owner_ids = $chek.',';
								//print_var($new_owner_ids);
								if(!$chek)
								{
									throw new Exception("Some Error Occurst Due to Consumer Owner Insetion ");
								}
															
							}
						}
						foreach($data['owner_name'] as $val)
						{
							$owner_detals['status']=0;
							$chek = $this->model_view_water_consumer->update_owner($val['id'],$owner_detals);
							$old_owner_ids = $val['id'].',';
							//print_var($old_owner_ids);
							if(!$chek)
							{
								throw new Exception("Some Error Occurst Due to Consumer Owner Update ");
							}
						}
						$owner_name_transfer_log_arr=[];
						$owner_name_transfer_log_arr['consumer_id'] = $data['consumer_details']['id'];
						$owner_name_transfer_log_arr['old_owner_ids'] = rtrim($old_owner_ids,',');
						$owner_name_transfer_log_arr['new_owner_ids'] = rtrim($new_owner_ids,',');
						$owner_name_transfer_log_arr['remarks']       = $inputs['remarks'];						
						$owner_name_transfer_log_arr['ip_address']    = $_SERVER['REMOTE_ADDR'];
						$owner_name_transfer_log_arr['emp_detail_id']= $this->emp_details_id;
						//print_var($owner_name_transfer_log_arr);
						$inserted_id = $this->Water_name_transfer_log_model->insertData($owner_name_transfer_log_arr);
						if(!$inserted_id)
						{
							throw new Exception("Some Error Occurst Please Contact To Admin 1");
						}
						else
						{
							$file = $this->request->getFile('document');
							$extension = $file->getExtension();							
							$city = $this->model_ulb_mstr->getCity($this->ulb_mstr_id);
							if($file->isValid() && !$file->hasMoved())
							{

								$newName = md5($inserted_id).".".$extension;

								if($file->move(WRITEPATH.'uploads/'.$city['city'].'/name_transfer',$newName))
								{
									$update_data['document']=$city['city'].'/name_transfer'.'/'.$newName	;							
									$chek = $this->Water_name_transfer_log_model->updateData($update_data,$inserted_id);	
									if(!$chek)
									{
										throw new Exception("Some Error Occurst Please Contact To Admin ");
									}								
								
								}
							}
						}

					}
					if($this->db->transStatus() === FALSE)
					{
						//die("rollback");
						$this->db->transRollback();
						flashToast("message", "Something errordue to Update!!!");  
						return $this->response->redirect(base_url('WaterUpdateConsumerConnectionMeterDoc/name_trasfer/'.$consumer_id));
				
					}
					else
					{ //die("commit");
						$this->db->transCommit();
						flashToast("message", "Update Consumer Successfully!!!");
						return $this->response->redirect(base_url('WaterUpdateConsumerConnectionMeterDoc/name_trasfer/'.$consumer_id));
					}
				}
				catch(Exception $e)
				{
					$this->db->transRollback();
					flashToast("message", $e->getMessage()); 
					// print_var($e->getMessage());
					// die; 
					return $this->response->redirect(base_url('WaterUpdateConsumerConnectionMeterDoc/name_trasfer/'.$consumer_id));
				}
			}
			return view('water/water_connection/name_trasfer',$data);

		}
		else
		{
			flashToast("massege", "Consumer Not Found");
			return $this->response->redirect(base_url('WaterConsumerList/index/name_trasfer'));
		}
		
		
	}
	#only for super Admin
	public function update($consumer_id)
	{   
        
        $data=array();
        $data['user_type']=$this->user_type_mstr_id;
        $data['consumer_id']=$consumer_id; 
        $data['property_type_list']=$this->water_property_model->property_list();
        $data['conn_type_list']=$this->conn_type_model->conn_type_list();
        $data['conn_through_list']=$this->conn_through_model->conn_through_list();
        $data['pipeline_type_list']=$this->pipeline_model->pipeline_list();
        $data['ulb_mstr_id']=$this->ulb_mstr_id;
        $data['ward_list']=$this->model->getWardList($data);
        $ulb_name = (session()->get('ulb_dtl')['ulb_name']??null);
		//print_var($data['ward_list']);

        $data['consumer_details']=$this->model_view_water_consumer->getConsumerByMd5Id($consumer_id);
		// print_var($data['consumer_details']);
        if(!empty($data['consumer_details']))
        {
            //$data['applicant_details']=$this->payment_model->fetch_all_application_data(md5($data['consumer_details']['apply_connection_id']));
            $data['applicant_details']=$this->payment_model->application_data($data['consumer_details']['apply_connection_id']);

			$data['connection_type_id']=$data['consumer_details']['connection_type_id'];
			$data['conn_through_id']=$data['consumer_details']['connection_through_id'];
			$data['property_type_id']=$data['consumer_details']['property_type_id'];
			$data['flat_count']=$data['consumer_details']['flat_count'];
			$data['category']=trim($data['consumer_details']['category']);
			$data['pipeline_type_id']=$data['consumer_details']['pipeline_type_id'];
			$data['holding_no']=$data['consumer_details']['holding_no'];

			$data['prop_id']=$data['consumer_details']['prop_dtl_id'];
			$data['saf_no']=$data['consumer_details']['saf_no'];
			$data['saf_id']=$data['consumer_details']['saf_dtl_id'];
			$data['ward_id']=$data['consumer_details']['ward_mstr_id'];			
			$data['area_in_sqft']=$data['consumer_details']['area_sqft'];
			$data['area_in_sqmt']=$data['consumer_details']['area_sqmt'];			
			$data['address']=$data['consumer_details']['address'];

			
			$data['k_no']=$data['consumer_details']['k_no'];
			$data['bind_book_no']=$data['consumer_details']['bind_book_no'];
			$data['account_no']=$data['consumer_details']['account_no'];
			$data['electric_category_type']=$data['consumer_details']['electric_category_type'];
			$data['apply_from']=$data['consumer_details']['apply_from'];			
			
            $data['owner_name']=$this->consumer_details_model->consumerDetails($data['consumer_details']['id']);
			//print_var($data['consumer_details']);
            $data['connection_dtls']=$this->meter_status_model->getLastConnectionDetails($data['consumer_details']['id']);
            
            $get_last_reading=$this->initial_meter_reading->initial_meter_reading($data['consumer_details']['id']);
            $data['last_reading']=$get_last_reading['initial_reading'];
			if($this->request->getMethod()=='post')
			{
				$this->db->transBegin();
				try
				{

					$inputs=arrFilterSanitizeString($this->request->getVar());
					
					if(isset($inputs['consumer_check_box']) && $inputs['consumer_check_box']=='on')
					{ 
						$data['consumer_check_box']='on';
						$consumer_details['connection_type_id']=$inputs['connection_type_id'];
						$consumer_details['connection_through_id']=$inputs['conn_through_id'];
						$consumer_details['property_type_id']=$inputs['property_type_id'];
						if($consumer_details['property_type_id']==7)
							$consumer_details['flat_count']=$inputs['flat_count'];
						else
						{
							$consumer_details['category']=(isset($inputs['category']) && !empty($inputs['category']))?$inputs['category']:$data['category'];
							$consumer_details['pipeline_type_id']= (isset($inputs['pipeline_type_id']) && !empty($inputs['pipeline_type_id']))?$inputs['pipeline_type_id']:$data['pipeline_type_id'];
						}
						if(isset($inputs['holding_no']) && $inputs['holding_no']!='')
						{
							$consumer_details['holding_no']=$inputs['holding_no'];	
							$consumer_details['prop_dtl_id']=$inputs['prop_id'];	
						}
						if(isset($inputs['saf_no']) && $inputs['saf_no']!='')
						{
							$consumer_details['saf_no']=$inputs['saf_no'];	
							$consumer_details['saf_dtl_id']=$inputs['saf_id'];
						}	
						$consumer_details['ward_mstr_id']=$inputs['ward_id'];	
						$consumer_details['area_sqft']=$inputs['area_in_sqft'];	
						$consumer_details['address']=$inputs['address'];
						$consumer_details['area_sqmt']=$inputs['area_in_sqmt'];	
						
						$chek = $this->model_view_water_consumer->update_consume($data['consumer_details']['id'],$consumer_details);						
						if(!$chek)
						{
							throw new Exception("Some Error Occurst Due to Consumer updatetion 1 ");
						}					
					}
					if(isset($inputs['owner_check_box']) && $inputs['owner_check_box']=='on')
					{
						$data['owner_check_box']='on';
						$icount = count($inputs['owner_name']);
						for($i=0;$i<$icount;$i++)
						{ 
							$owner_detals=[];
							$owner_detals['applicant_name']=$inputs['owner_name'][$i];
							$owner_detals['father_name']=$inputs['guardian_name'][$i];
							$owner_detals['mobile_no']=$inputs['mobile_no'][$i];
							$owner_detals['city']=$inputs['city'][$i];
							$owner_detals['district']=$inputs['district'][$i];
							$owner_detals['state']=$inputs['state'][$i];
							$owner_id=isset($inputs['woner_id'.$i]) && !empty($inputs['woner_id'.$i])?$inputs['woner_id'.$i]:'';
							
							if($owner_id=="")
							{
								
								$owner_detals['emp_details_id']=$this->emp_details_id;
								$owner_detals['created_on']=date('Y-m-d h:i:s');
								$owner_detals['consumer_id']=$data['consumer_details']['id'];

								$chek = $this->model_view_water_consumer->insert_owner($owner_detals);
								if(!$chek)
								{
									throw new Exception("Some Error Occurst Due to Consumer Owner Insetion ");
								}
							}
							else
							{
								
								$chek = $this->model_view_water_consumer->update_owner($owner_id,$owner_detals);
								if(!$chek)
								{
									throw new Exception("Some Error Occurst Due to Consumer Owner Update ");
								}
								
							}
							
						}
					}
					if(isset($inputs['elect_check_box']) && $inputs['elect_check_box']=='on')
					{
						$data['elect_check_box']='on';
						$electric_dtl['k_no']=$inputs['elec_k_no'];
						$electric_dtl['bind_book_no']=$inputs['elec_bind_book_no'];
						$electric_dtl['account_no']=$inputs['elec_account_no'];
						$electric_dtl['electric_category_type']=$inputs['elec_category'];
						$chek = $this->model_view_water_consumer->update_consume($data['consumer_details']['id'],$electric_dtl);
						if(!$chek)
						{
							throw new Exception("Some Error Occurst Due to Consumer Electricity Details updatetion ");
						}
						
					}
					
					if($this->db->transStatus() === FALSE)
					{
						//die("rollback");
						$this->db->transRollback();
						flashToast("message", "Something errordue to Update!!!");  
						return $this->response->redirect(base_url('WaterUpdateConsumerConnectionMeterDoc/update/'.$consumer_id));
				
					}
					else
					{ //die("commit");
						$this->db->transCommit();
						flashToast("message", "Update Consumer Successfully!!!");
						return $this->response->redirect(base_url('WaterUpdateConsumerConnectionMeterDoc/update/'.$consumer_id));
					}
				}
				catch(Exception $e)
				{
					$this->db->transRollback();
					flashToast("message", $e->getMessage()); 
					/* print_var($e->getMessage());
					die; */
					return $this->response->redirect(base_url('WaterUpdateConsumerConnectionMeterDoc/update/'.$consumer_id));
				}
			}
            //print_var($data['property_check_box']);
            return view('water/water_connection/update',$data);
        }
		else
		{
			flashToast("massege", "Consumer Not Found");
			return $this->response->redirect(base_url('WaterConsumerList/index/update'));
		}
        
    }
	public function update2($consumer_id)
	{
		echo "page Not found";
	}

	public function update_connection_date($consumer_id)
	{   
        
        $data=array();
        $data['user_type']=$this->user_type_mstr_id;
        $data['consumer_id']=$consumer_id; 
        // $data['property_type_list']=$this->water_property_model->property_list();
        // $data['conn_type_list']=$this->conn_type_model->conn_type_list();
        // $data['conn_through_list']=$this->conn_through_model->conn_through_list();
        //$data['pipeline_type_list']=$this->pipeline_model->pipeline_list();
        $data['ulb_mstr_id']=$this->ulb_mstr_id;
        //$data['ward_list']=$this->model->getWardList($data);
        $ulb_name = (session()->get('ulb_dtl')['ulb_name']??null);
		//print_var($data['ward_list']);

        //$data['consumer_details']=$this->model_view_water_consumer->getConsumerByMd5Id($consumer_id);
		$data['consumer_details']=$this->model_view_water_consumer->waterConsumerDetailsById($consumer_id);
		//print_var($data['consumer_details']['id']);
        if(!empty($data['consumer_details']))
        {
            //$data['applicant_details']=$this->payment_model->fetch_all_application_data(md5($data['consumer_details']['apply_connection_id']));
            $data['applicant_details']=$this->payment_model->application_data($data['consumer_details']['apply_connection_id']);

            $data['owner_name']=$this->consumer_details_model->consumerDetails($data['consumer_details']['id']);
			//print_var($data['consumer_details']);
            $data['connection_dtls']=$this->meter_status_model->getLastConnectionDetails($data['consumer_details']['id']);
            //print_var($data['connection_dtls']);
            $get_last_reading=$this->initial_meter_reading->initial_meter_reading($data['consumer_details']['id']);
            $data['last_reading']=$get_last_reading['initial_reading'];
			$get_first_reading = $this->initial_meter_reading->initial_meter_first_reading($data['consumer_details']['id']);
			
			$data['first_reading'] = $get_first_reading['initial_reading'];
			$first_reading_date = $get_first_reading['initial_date']??date('Y-m-d');
			//die;
			$data['last_demand_paid_from'] = $this->consumer_demand_model->getDueFrom2($data['consumer_details']['id']);
			
			$fist_paid_demand = $this->consumer_demand_model->get_fist_paid_demand($data['consumer_details']['id']);
			$fist_paid_demand =$fist_paid_demand['demand_from']??date('Y-m-d');
			// print_var($data['consumer_details']['id']);
			if($this->request->getMethod()=='post')
			{
				$this->db->transBegin();
				try
				{
					$curr_date = date('Y-md');
					$data['error']='';
					$regex = '/^\s*[+\-]?(?:\d+(?:\.\d*)?|\.\d+)\s*$/';
					$inputs=arrFilterSanitizeString($this->request->getVar());
					//print_var($inputs);die;
					$rules = [
						'new_cunsumer_connection_date'=>[
							'rules'=>"required",
							'errors'=>[
								'required'=>'New Consumer Connection Date Required',								
							]
							],
						'document'=>[
							'rules'=>'uploaded[document]|max_size[document,3072]|ext_in[document,jpg,pdf]',
							'errors'=>[
								'uploaded[document]'=>'Document Is Required',	
								'max_size[document,3072]'=>'size is less than 3mb',
								'ext_in[document,jpg,pdf]'=>'Extention in jpg or pdf'						
							]
							],
						'remarks'=>[
							'rules'=>'required|min_length[10]',
							'errors'=>[
								'required'=>'remarks Is Required',	
								'min_length[10]'=>'remarks have at least 10 Charecters',					
							]
							],
						];
					if(!$this->validate($rules))
					{
						$data['validation']=$this->validator;							
						//print_var($this->validator->listErrors());
						return view('water/water_connection/update_connection_date', $data);
						//throw new Exception('Errors');

					}					
					else if($inputs['new_cunsumer_connection_date']>$curr_date )
					{
						$data['error']="Connection Date can not be greater than Current Date";						
					}
					else if(isset($inputs['final_meter_reading']) && $inputs['final_meter_reading']!="" and preg_match($regex,$inputs['final_meter_reading'])==0)
					{
						$data['error']="Enter Valid Number";
					}
					// else if(($prev_conn_date==$inputs['connection_date'] || $prev_conn_date>$inputs['connection_date']))
					// {
					// 	$data['error']="Can not Update Connection Type on Same Date or less than previous date";
					// }
					else if($first_reading_date<$inputs['new_cunsumer_connection_date'])
					{
						$data['error']="Can not  Connection Date grater than first reading date $first_reading_date ";
					}
					else if($fist_paid_demand<$inputs['new_cunsumer_connection_date'])
					{
						$data['error']="Can not  Connection Date grater than first payment date $fist_paid_demand ";
					}
					else if(isset($inputs['final_reading']) && ($inputs['final_reading']<$data['first_reading']  || $inputs['final_reading']<=0))
					{
						$data['error']="Meter Reading should be greater than last reading";
					}

					if($data['error']!='')
					{
						throw new Exception($data['error']);
					}
					else
					{
						$data['consumer_details']=$this->model_view_water_consumer->getConsumerByMd5Id($consumer_id);
						// print_var($data);
						// print_var($data['connection_dtls']);die;
						$upto_date=date('Y-m-d');
						$final_meter_reading = 0;
						if($data['connection_dtls']['connection_type']!=3)
						{
							$final_meter_reading=$inputs['final_reading'];
						}
						//$data['consumer_details']=$this->model_view_water_consumer->waterConsumerDetailsById($consumer_id);
						$consumer_details['created_on']=$inputs['new_cunsumer_connection_date'];						
						$chek = $this->model_view_water_consumer->update_consume($data['consumer_details']['id'],$consumer_details);						
						
						if(!$chek)
						{
							throw new Exception("Some Error Occurst Due to Consumer Connection Date updatetion ");
						}
						else
						{

							$consumer_log_array=[];
							$consumer_log_array['consumer_id']=$data['consumer_details']['id'];							
							$consumer_log_array['connection_type_id']=$data['consumer_details']['connection_type_id'];
							$consumer_log_array['connection_through_id']=$data['consumer_details']['connection_through_id'];
							$consumer_log_array['property_type_id']=$data['consumer_details']['property_type_id'];
							$consumer_log_array['category']=$data['consumer_details']['category'];
							$consumer_log_array['ward_mstr_id']=$data['consumer_details']['ward_mstr_id'];
							$consumer_log_array['prop_dtl_id']=$data['consumer_details']['prop_dtl_id'];
	
							$consumer_log_array['area_sqmt']=$data['consumer_details']['area_sqmt'];							
							$consumer_log_array['area_sqft']=$data['consumer_details']['area_sqft'];
							$consumer_log_array['pipeline_type_id']=$data['consumer_details']['pipeline_type_id'];
							$consumer_log_array['flat_count']=$data['consumer_details']['flat_count'];
							$consumer_log_array['k_no']=$data['consumer_details']['k_no'];
							$consumer_log_array['bind_book_no']=$data['consumer_details']['bind_book_no'];
							$consumer_log_array['account_no']=$data['consumer_details']['account_no'];
	
							$consumer_log_array['electric_category_type']=$data['consumer_details']['electric_category_type'];							
							$consumer_log_array['created_on']=$data['consumer_details']['created_on'];
							$consumer_log_array['entry_type']=$data['consumer_details']['entry_type'];
							$consumer_log_array['is_meter_working']=$data['consumer_details']['is_meter_working'];
							$consumer_log_array['old_consumer_no']=$data['consumer_details']['old_consumer_no'];
							$consumer_log_array['holding_no']=$data['consumer_details']['holding_no'];
							$consumer_log_array['saf_dtl_id']=$data['consumer_details']['saf_dtl_id'];
	
							$consumer_log_array['saf_no']=$data['consumer_details']['saf_no'];							
							$consumer_log_array['address']=$data['consumer_details']['address'];
							$consumer_log_array['apply_from']=$data['consumer_details']['apply_from'];
	
							$consumer_log_array['remarks']=$inputs['remarks'];
							$consumer_log_array['ip_address']=$_SERVER['REMOTE_ADDR'];
							$consumer_log_array['emp_details_id']=$this->emp_details_id;
							$consumer_log_array['type']='Consumer Connection Date Update';
	
							$consumer_log_inserted_id = $this->Water_name_transfer_log_model->insertData_tbl_consumer_log($consumer_log_array);
							if(!$consumer_log_inserted_id)
							{
								throw new Exception("Some Error Occurst Due To Consumer Update Please Contact To Admin 1");
							}
							else
							{
								$file = $this->request->getFile('document');
								$extension = $file->getExtension();							
								$city = $this->model_ulb_mstr->getCity($this->ulb_mstr_id);
								if($file->isValid() && !$file->hasMoved())
								{
									$newName = md5($consumer_log_inserted_id).".".$extension;
									if($file->move(WRITEPATH.'uploads/'.$city['city'].'/consumer_update',$newName))
									{
										$update_data['document']=$city['city'].'/consumer_update'.'/'.$newName	;							
										$chek = $this->Water_name_transfer_log_model->updateData_tbl_consumer_log($update_data,$consumer_log_inserted_id);	
										if(!$chek)
										{
											throw new Exception("Some Error Occurst Please Contact To Admin ");
										}								
									
									}
								}
								$demand_dtl = $this->consumer_demand_model->get_unpaid_demand_ids($data['consumer_details']['id']);
								
								$demands_log =[];
								$demands_log['consumer_id']=$data['consumer_details']['id'];
								$demands_log['consumer_log_id']=$consumer_log_inserted_id;
								$demands_log['demand_id']=$demand_dtl['ids']??'';
								$demands_log['type']='Consumer Connection Date Update';
								$demands_log['emp_details_id']=$this->emp_details_id;
								$demands_log['ip_address']=$_SERVER['REMOTE_ADDR'];
								$demands_log['remarks']=$inputs['remarks'];
								$chek = $this->Water_name_transfer_log_model->insertData_tbl_consumer_demand_audit($demands_log);
								if(!$chek)
								{
									throw new Exception("Some Error Occurst Due To Consumer Demand Update Please Contact To Admin 1");
								} 
								else
								{
									$demand_update  = $this->consumer_demand_model->update_demand_status_connection_date($data['consumer_details']['id']);									
								}
								$tax_id = $this->generate_demand_controller->tax_generation($data['consumer_details']['id'],$upto_date,$final_meter_reading);
								// echo('tax_ids');
								// print_var($tax_id);
								if(!$tax_id)
								{
									throw new Exception("Some Error Occurst Due To Consumer Demand Genration Please Contact To Admin 1");
								}
							}
						}
					}					
					if($this->db->transStatus() === FALSE)
					{
						//die("rollback");
						$this->db->transRollback();
						flashToast("message", "Something errordue to Update!!!");  
						return $this->response->redirect(base_url('WaterUpdateConsumerConnectionMeterDoc/update_connection_date/'.$consumer_id));
				
					}
					else
					{ 
						//die("commit");
						$this->db->transCommit();
						flashToast("message", "Update Consumer Successfully!!!");
						return $this->response->redirect(base_url('WaterUpdateConsumerConnectionMeterDoc/update_connection_date/'.$consumer_id));
					}
				}
				catch(Exception $e)
				{
					$this->db->transRollback();
					flashToast("message", $e->getMessage()); 
					// print_var($e->getMessage());
					// die; 
					return $this->response->redirect(base_url('WaterUpdateConsumerConnectionMeterDoc/update_connection_date/'.$consumer_id));
				}
			}
            //print_var($data['property_check_box']);
            return view('water/water_connection/update_connection_date',$data);
        }
		else
		{
			flashToast("massege", "Consumer Not Found");
			return $this->response->redirect(base_url('WaterConsumerList/index/update_connection_date'));
		}
        
    }

	public function update_meter_connection_date($consumer_id)
	{   
        
        $data=array();
        $data['user_type']=$this->user_type_mstr_id;
        $data['consumer_id']=$consumer_id; 
        $data['ulb_mstr_id']=$this->ulb_mstr_id;
        $ulb_name = (session()->get('ulb_dtl')['ulb_name']??null);
		$data['consumer_details']=$this->model_view_water_consumer->waterConsumerDetailsById($consumer_id);
		
        if(!empty($data['consumer_details']))
        {
			 $data['applicant_details']=$this->payment_model->application_data($data['consumer_details']['apply_connection_id']);

            $data['owner_name']=$this->consumer_details_model->consumerDetails($data['consumer_details']['id']);
			
            $data['connection_dtls']=$this->meter_status_model->getLastConnectionDetails($data['consumer_details']['id']);
            
            $get_last_reading=$this->initial_meter_reading->initial_meter_reading($data['consumer_details']['id']);
            $data['last_reading']=$get_last_reading['initial_reading'];
			$get_first_reading = $this->initial_meter_reading->initial_meter_first_reading($data['consumer_details']['id']);
			
			$data['first_reading'] = $get_first_reading['initial_reading'];
			$first_reading_date = $get_first_reading['initial_date']??date('Y-m-d');

			$data['last_demand_paid_from'] = $this->consumer_demand_model->getDueFrom2($data['consumer_details']['id']);
			$last_paid_demands = $this->consumer_demand_model->get_last_paid_demand($data['consumer_details']['id']);
			$data['last_paid_demands']=$last_paid_demands;
			$fist_paid_demand = $this->consumer_demand_model->get_fist_paid_demand($data['consumer_details']['id']);
			$fist_paid_demand =$fist_paid_demand['demand_from']??date('Y-m-d');

			if($this->request->getMethod()=='post')
			{
				$this->db->transBegin();
				try
				{	
					$curr_date = date('Y-md');
					$data['error']='';
					$regex = '/^\s*[+\-]?(?:\d+(?:\.\d*)?|\.\d+)\s*$/';
					$inputs=arrFilterSanitizeString($this->request->getVar());
					$rules = [
						'new_cunsumer_connection_date'=>[
							'rules'=>"required",
							'errors'=>[
								'required'=>'New Consumer Connection Date Required',								
							]
							],
						'document'=>[
							'rules'=>'uploaded[document]|max_size[document,3072]|ext_in[document,jpg,jpeg,pdf]',
							'errors'=>[
								'uploaded[document]'=>'Document Is Required',	
								'max_size[document,3072]'=>'size is less than 3mb',
								'ext_in[document,jpg,pdf]'=>'Extention in jpg or pdf'						
							]
							],
						'remarks'=>[
							'rules'=>'required|min_length[10]',
							'errors'=>[
								'required'=>'remarks Is Required',	
								'min_length[10]'=>'remarks have at least 10 Charecters',					
							]
							],
						];
					if(!$this->validate($rules))
					{
						$data['validation']=$this->validator;	
						return view('water/water_connection/update_connection_date', $data);						

					}					
					else if($inputs['new_cunsumer_connection_date']>$curr_date )
					{
						$data['error']="Connection Date can not be greater than Current Date";						
					}
					else if(isset($inputs['final_meter_reading']) && $inputs['final_meter_reading']!="" and preg_match($regex,$inputs['final_meter_reading'])==0)
					{
						$data['error']="Enter Valid Number";
					}
					else if(!empty($last_paid_demands) && $last_paid_demands['demand_upto']>$inputs['new_cunsumer_connection_date'])
					{
						$data['error']="Can not  Connection Date Less than Last payment date ".$last_paid_demands['demand_upto'];
					}
					else if(isset($inputs['final_reading']) && $inputs['final_reading']!="" && ($inputs['final_reading']<=$data['first_reading']  || $inputs['final_reading']<=0))
					{
						$data['error']="Meter Reading should be greater than last reading";
					}

					if($data['error']!='')
					{
						throw new Exception($data['error']);
					}
					else
					{				
						$upto_date=date('Y-m-d');
						$final_meter_reading = 0;
						if($inputs['connection_type']!=3)
						{
							$final_meter_reading=$inputs['final_reading']!=''?$inputs['final_reading']:0;
							$consumer_initial_meter['consumer_id']=$data['consumer_details']['id'];
							$consumer_initial_meter['initial_reading']=0;
							$consumer_initial_meter['emp_details_id']=$this->emp_details_id;;
							$consumer_initial_meter['created_on']=$inputs['new_cunsumer_connection_date'];
							$this->initial_meter_reading->insertData($consumer_initial_meter);
						}
						$meter_status['consumer_id']=$data['consumer_details']['id'];
						$meter_status['connection_date']=$inputs['new_cunsumer_connection_date'];
						$meter_status['meter_status']=1;
						$meter_status['emp_details_id']=$this->emp_details_id;
						$meter_status['connection_type']=$inputs['connection_type'];
						$meter_status['meter_no']=$inputs['meter_no'];
						$meter_status['final_meter_reading']=$final_meter_reading;
						$meter_status['meter_intallation_date']=$inputs['new_cunsumer_connection_date'];
						$meter_status['initial_reading']=$inputs['connection_type']!=3?0.5:null;
						$meter_status['created_on']=date('Y-m-d');
												
						$chek = $this->meter_status_model->insertData($meter_status);						
						
						if(!$chek)
						{
							throw new Exception("Some Error Occurst Due to Consumer Connection Date updatetion ");
						}
						else
						{
							$file = $this->request->getFile('document');
							$extension = $file->getExtension();							
							$city = $this->model_ulb_mstr->getCity($this->ulb_mstr_id);							
							if($file->isValid() && !$file->hasMoved())
							{
								$newName = $chek.".".$extension;
								if($file->move(WRITEPATH.'uploads/'.$city['city'].'/meter_image',$newName))
								{		
									$newName= "/".$city['city'].'/meter_image'.$newName;								
									$this->meter_status_model->updateMeterDocumentName($chek,$newName);																		
								}
							}

							{
								$demand_dtl = $this->consumer_demand_model->get_unpaid_demand_ids($data['consumer_details']['id'],
																								[
																									'demand_from >='=>$inputs['new_cunsumer_connection_date'],
																									'demand_upto <='=>$upto_date,
																								]);
								
								$demands_log =[];
								$demands_log['consumer_id']=$data['consumer_details']['id'];
								$demands_log['demand_id']=$demand_dtl['ids']??'';
								$demands_log['type']='Meter Connection Date Update';
								$demands_log['emp_details_id']=$this->emp_details_id;
								$demands_log['ip_address']=$_SERVER['REMOTE_ADDR'];
								$demands_log['remarks']=$inputs['remarks'];
								$chek = $this->Water_name_transfer_log_model->insertData_tbl_consumer_demand_audit($demands_log);
								if(!$chek)
								{
									throw new Exception("Some Error Occurst Due To Consumer Demand Update Please Contact To Admin 1");
								} 
								else
								{
									$demand_update  = $this->consumer_demand_model->update_demand_status_connection_date($data['consumer_details']['id'],
																															[
																																'demand_from >='=>$inputs['new_cunsumer_connection_date'],
																																'demand_upto <='=>$upto_date,
																															]);										
								}
								if($inputs['connection_type']==3 || $final_meter_reading>0)
								{
									$tax_id = $this->generate_demand_controller->tax_generation($data['consumer_details']['id'],$upto_date,$final_meter_reading);
									
									if(!$tax_id)
									{
										throw new Exception("Some Error Occurst Due To Consumer Demand Genration Please Contact To Admin 1");
									}
								}
							}
						}
					}					
					if($this->db->transStatus() === FALSE)
					{
						$this->db->transRollback();
						flashToast("message", "Something errordue to Update!!!");  
						return $this->response->redirect(base_url('WaterUpdateConsumerConnectionMeterDoc/update_meter_connection_date/'.$consumer_id));
				
					}
					else
					{ 
						//die("commit");
						$this->db->transCommit();
						flashToast("message", "Update Consumer Successfully!!!");
						return $this->response->redirect(base_url('WaterUpdateConsumerConnectionMeterDoc/update_meter_connection_date/'.$consumer_id));
					}
				}
				catch(Exception $e)
				{
					$this->db->transRollback();
					flashToast("message", $e->getMessage()); 
					// print_var($e->getFile());
					// print_var($e->getLine());
					// die; 
					return $this->response->redirect(base_url('WaterUpdateConsumerConnectionMeterDoc/update_meter_connection_date/'.$consumer_id));
				}
			}
            return view('water/water_connection/update_meter_connection_date',$data);
        }
		else
		{
			flashToast("massege", "Consumer Not Found");
			return $this->response->redirect(base_url('WaterConsumerList/index/update_connection_date'));
		}
        
    }

	public function uplodeExisting($consumer_id)
	{
		$data['user_type']=$this->user_type_mstr_id;
        $data['consumer_id']=$consumer_id; 
        $data['property_type_list']=$this->water_property_model->property_list();
        $data['conn_type_list']=$this->conn_type_model->conn_type_list();
        $data['conn_through_list']=$this->conn_through_model->conn_through_list();
        $data['pipeline_type_list']=$this->pipeline_model->pipeline_list();
        $data['ulb_mstr_id']=$this->ulb_mstr_id;
		$ulb_name = (session()->get('ulb_dtl')['ulb_name']??null);
		$data['ward_list']=$this->model->getWardList($data);		
		$data['consumer_details']=$this->model_view_water_consumer->GetExisting_consumer($consumer_id);
		if(!empty($data['consumer_details']))
        {	
			
			$data['documernt_list']= $this->model_view_water_consumer->get_document_type();
			//print_var($data['documernt_list']);			
			$data['uplodate_doc'] = $this->Water_name_transfer_log_model->Existing_document_get($data['consumer_details']['id']);
			// print_var($data['uplodate_doc']);die;
			$data['applicant_details']=$this->payment_model->application_data($data['consumer_details']['apply_connection_id']);
			$data['owner_name']=$this->consumer_details_model->consumerDetails($data['consumer_details']['id']);
			if($this->request->getMethod()=='post')
			{				
				$inputs=arrFilterSanitizeString($this->request->getVar());				
				
				try
				{
					$this->db->transBegin();					
					$rules = [
						'doc_for'=>'required',
						'document_name'=>'required',
						'remarks'=>'required',
						'document'=>'uploaded[document]|max_size[document,3072]|ext_in[document,png,pdf]',
					];
					if(!$this->validate($rules))
					{
						$data['validation']=$this->validator;
                    	return view('water/water_connection/uplodeExisting', $data);
					}
					// print_var($inputs);
					// die;
					//if(isset($inputs['owner_name']))
					{
						$consumer_document_details=[];
						$consumer_document_details['consumer_id'] = $data['consumer_details']['id'];
						$consumer_document_details['doc_detail_id'] = $inputs['document_name'];
						$consumer_document_details['doc_for'] = $inputs['doc_for'];
						$consumer_document_details['uplode_type'] = 'Existing';
						$consumer_document_details['remarks']       = $inputs['remarks'];						
						$consumer_document_details['ip_address']    = $_SERVER['REMOTE_ADDR'];
						$consumer_document_details['emp_details_id']= $this->emp_details_id;
						// print_var($consumer_document_details);
						// die;
						$ch_data=[];
						$ch_data['consumer_id'] = $data['consumer_details']['id'];
						$ch_data['doc_detail_id'] = $inputs['document_name'];
						$ch_data['doc_for'] = $inputs['doc_for'];
						$ch_data['uplode_type'] = 'Existing';
						$ckeck = $this->Water_name_transfer_log_model->checkdate_tbl_consumer_document_details($ch_data);
						//print_var($ckeck);die;
						if($ckeck==0)
						{
							$inserted_id = $this->Water_name_transfer_log_model->insertData_tbl_consumer_document_details($consumer_document_details);
	
							if(!$inserted_id)
							{
								throw new Exception("Some Error Occurst Please Contact To Admin 1");
							}
							else
							{
								$file = $this->request->getFile('document');
								$extension = $file->getExtension();							
								$city = $this->model_ulb_mstr->getCity($this->ulb_mstr_id);
								if($file->isValid() && !$file->hasMoved())
								{
	
									$newName = 'Ext_'.md5($inserted_id).".".$extension;
	
									if($file->move(WRITEPATH.'uploads/'.$city['city'].'/consumer_doc',$newName))
									{
										$update_data['file_name']=$city['city'].'/consumer_doc'.'/'.$newName	;							
										$chek = $this->Water_name_transfer_log_model->updateData_tbl_consumer_document_details($update_data,$inserted_id);	
										if(!$chek)
										{
											throw new Exception("Some Error Occurst Please Contact To Admin ");
										}								
									
									}
								}
							}

						}
						else
						{
							$temp  = $this->Water_name_transfer_log_model->get_specific_data($ch_data)[0];
							$file = $this->request->getFile('document');
							$extension = $file->getExtension();	
							$city = $this->model_ulb_mstr->getCity($this->ulb_mstr_id);
							$newName = 'Ext_'.md5($temp['id']).".".$extension;							
							if(file_exists((WRITEPATH.'uploads/'.$city['city'].'/consumer_doc'.'/'.$newName)))
							{
								// unlink(WRITEPATH.'uploads/'.$city['city'].'/consumer_doc'.'/'.$newName);
								deleteFile(WRITEPATH.'uploads/'.$city['city'].'/consumer_doc'.'/'.$newName);
							}
							if($file->move(WRITEPATH.'uploads/'.$city['city'].'/consumer_doc',$newName))
							{
								$update_data['file_name']=$city['city'].'/consumer_doc'.'/'.$newName	;							
								$chek = $this->Water_name_transfer_log_model->updateData_tbl_consumer_document_details($update_data,$temp['id']);	
								if(!$chek)
								{
									throw new Exception("Some Error Occurst Please Contact To Admin ");
								}								
							
							}

						}

					}
					if($this->db->transStatus() === FALSE)
					{
						//die("rollback");
						$this->db->transRollback();
						flashToast("message", "Something errordue to Uploade!!!");  
						return $this->response->redirect(base_url('WaterUpdateConsumerConnectionMeterDoc/uplodeExisting/'.$consumer_id));
				
					}
					else
					{ 
						//die("commit");
						$this->db->transCommit();
						flashToast("message", "Uploade Do Successfully!!!");
						return $this->response->redirect(base_url('WaterUpdateConsumerConnectionMeterDoc/uplodeExisting/'.$consumer_id));
					}
				}
				catch(Exception $e)
				{
					$this->db->transRollback();
					flashToast("message", $e->getMessage()); 
					// print_var($e->getMessage());
					// die; 
					return $this->response->redirect(base_url('WaterUpdateConsumerConnectionMeterDoc/uplodeExisting/'.$consumer_id));
				}
			}
			return view('water/water_connection/uplodeExisting',$data);

		}
		else
		{
			flashToast("massege", "Consumer Not Found");
			return $this->response->redirect(base_url('WaterConsumerList/index/name_trasfer'));
		}
	}
	

	public function getdoc_list()
    {
    	 if($this->request->getMethod()=='post')
       	 {	
       	 	$inputs=arrFilterSanitizeString($this->request->getVar());
       	 	//print_r($inputs);die;
       		$doc_for=$inputs['doc_for'];			      		
       		if($result=$this->model_view_water_consumer->get_document_name($doc_for))
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

	public function AlterGovFixedChage($consumer_id)
    {
        $data = array();//echo($consumer_id);
        $data['consumer_id']=$consumer_id;
        if($consumer_id!=null)
        {
            $data['connection_dtls']=$this->meter_status_model->getLastConnectionDetailsbyMd5($consumer_id);
            $sql="select c.*,w.ward_no,p.property_type,
                    case when m.connection_type = 1 then 'METER'
                        when m.connection_type = 2 then 'GALLON'
                        when m.connection_type = 3 then 'FIXED' 
                        else 'Not Defiend' end as meter,
                    m.id as meter_id,
                    m.meter_status,
                    m.rate_per_month,
                    m.connection_type,
                    m.connection_date as meter_connection_date
                  from tbl_consumer c 
                  join view_ward_mstr w on w.id = c.ward_mstr_id 
                  join tbl_property_type_mstr p on p.id = c.property_type_id
                  left join ( select * from 
                        tbl_meter_status 
                        where md5(consumer_id::text)='$consumer_id' 
                        order by id desc limit 1
                        ) as m on m.consumer_id = c.id and m.status=1
                  where md5(c.id::text)='$consumer_id' ";
            $data['consumer_dtl']=$this->water_mobile_model->getDataRowQuery2($sql)['result'];			
            if(sizeof($data['consumer_dtl'])>0)
                $data['consumer_dtl']=$data['consumer_dtl'][0];            
           $cons_id=$data['consumer_dtl']['id'];
           $sql="select id,ward_no from view_ward_mstr where ulb_mstr_id =".$this->ulb_mstr_id." and status=1 ";
           $data['ward_list']=$this->water_mobile_model->getDataRowQuery2($sql)['result'];
           $sql="select * from tbl_consumer_details where md5(consumer_id::text) ='$consumer_id' and status=1 ";
           $data['owner_list']=$this->water_mobile_model->getDataRowQuery2($sql)['result'];//echo $sql;
           if(strtoupper($this->request->getMethod())=='POST')
           {
                $this->db->transBegin();				
                $inputs=arrFilterSanitizeString($this->request->getVar()); 
                $rules = [
					'rate_per_month'=>'required|numeric|greater_than_equal_to[1]',
					'meter_doc'=>'uploaded[meter_doc]|max_size[meter_doc,3072]|ext_in[meter_doc,png,pdf]',
				];
				if(!$this->validate($rules))
				{ 
					// $data['validation']=$this->validator;
                    $data['validation']=$this->validator->getErrors();
                    flashToast("error", $data['validation']);					                  
                    return $this->response->redirect(base_url('WaterUpdateConsumerConnectionMeterDoc/AlterGovFixedChage/'.$consumer_id));
                    
                }
                $file = $this->request->getFile('meter_doc');                
                $extension = $file->getExtension();                
                $city = $this->model_ulb_mstr->getCity($this->ulb_mstr_id);              
                
                if($data['consumer_dtl']['meter_id'])    
                {
                    $meter_dtl = "SELECT * 
                                  FROM tbl_meter_status 
                                  WHERE id = ".$data['consumer_dtl']['meter_id'];
                    $meter_dtl = $this->db->query($meter_dtl)->getFirstRow('array');
                    $consumer_connection_dtls=array();
                    $consumer_connection_dtls['consumer_id']=$meter_dtl['consumer_id'];
                    $consumer_connection_dtls['connection_date']=$meter_dtl['connection_date'];
                    $consumer_connection_dtls['connection_type']= $meter_dtl['connection_type'];
                    $consumer_connection_dtls['meter_no']=$meter_dtl['meter_no'];
                    $consumer_connection_dtls['final_meter_reading']=$meter_dtl['final_meter_reading']??null;                   
                    $consumer_connection_dtls['initial_reading']=$meter_dtl['initial_reading']??null;
                    $consumer_connection_dtls['meter_status']=$meter_dtl['meter_status'];
                    $consumer_connection_dtls['rate_per_month'] = $inputs['rate_per_month'];
                    $consumer_connection_dtls['emp_details_id']=$this->emp_details_id;

                    $insert_id=$this->meter_status_model->insertData($consumer_connection_dtls);
                } 
                else
                {
                    $consumer_connection_dtls=array();
                    $consumer_connection_dtls['consumer_id']=$data['consumer_dtl']['id'];
                    $consumer_connection_dtls['connection_date']= date('Y-m-d',strtotime($data['consumer_dtl']['created_on']));
                    $consumer_connection_dtls['connection_type']= 3;
                    $consumer_connection_dtls['meter_no']=null;
                    $consumer_connection_dtls['final_meter_reading']=null;                   
                    $consumer_connection_dtls['initial_reading']=null;
                    $consumer_connection_dtls['meter_status']=1;
                    $consumer_connection_dtls['rate_per_month'] = $inputs['rate_per_month'];
                    $consumer_connection_dtls['emp_details_id']=$this->emp_details_id;

                    $insert_id=$this->meter_status_model->insertData($consumer_connection_dtls);
                }
                if($insert_id)
                {
                    if($file->isValid() && !$file->hasMoved())
                    {
                        $newName = md5($insert_id).".".$extension;
                        if($file->move(WRITEPATH.'uploads/'.$city['city'].'/meter_image',$newName))
                        {                            
                            $this->meter_status_model->updateMeterDocumentName($insert_id,$newName);                           
                            
                        }
                    }
                    $this->db->transCommit();
                    flashToast("message", "Update Rate Successfully !!!");                       
                    return redirect()->to(base_url().'/WaterUpdateConsumerConnectionMeterDoc/AlterGovFixedChage/'.$consumer_id);
                }
                else
                {
                    $this->db->transRollback();
                    flashToast("message", "Opps Some Error Occurs !!!");
                }
           }
           return view('water/water_connection/AlterGovFixedChage',$data);
        }
        return redirect()->to(base_url().'/WaterConsumerList/index/AlterGovFixedChage');
    }
	
	public function odershitArvBilling($consumer_id)
	{
		$data=array();
        $data['user_type']=$this->user_type_mstr_id;
        $data['consumer_id']=$consumer_id; 
        $data['ulb_mstr_id']=$this->ulb_mstr_id;
		$data['consumer_details']=$this->model_view_water_consumer->waterConsumerDetailsById($consumer_id);		
        if(!empty($data['consumer_details']))
        {
            $data['owner_name']=$this->consumer_details_model->consumerDetails($data['consumer_details']['id']);
			$connection_dtl_sql = "select * from tbl_meter_status where status=1 AND consumer_id = ".$data['consumer_details']['id']." ORDER BY connection_date ASC ";
            $data['connection_dtls']= $this->db->query($connection_dtl_sql)->getFirstRow("array");
			// $data['connection_dtls']=$this->meter_status_model->getLastConnectionDetails($data['consumer_details']['id']);
            
            $get_last_reading = $this->initial_meter_reading->initial_meter_reading($data['consumer_details']['id']);
            $data['last_reading'] = $get_last_reading['initial_reading'];
			$get_first_reading = $this->initial_meter_reading->initial_meter_first_reading($data['consumer_details']['id']);
			
			$data['first_reading'] = $get_first_reading['initial_reading'];

			$data['last_demand_paid_from'] = $this->consumer_demand_model->getDueFrom2($data['consumer_details']['id']);
			$last_paid_demands = $this->consumer_demand_model->get_last_paid_demand($data['consumer_details']['id']);
			$data['last_paid_demands']=$last_paid_demands;
			$fist_paid_demand = $this->consumer_demand_model->get_fist_paid_demand($data['consumer_details']['id']);
			$fist_paid_demand =$fist_paid_demand['demand_from']??date('Y-m-d');
			if($this->request->getMethod()=='post')
			{
				$this->db->transBegin();
				try
				{	
					$curr_date = date('Y-md');
					$data['error']='';
					$regex = '/^\s*[+\-]?(?:\d+(?:\.\d*)?|\.\d+)\s*$/';
					$inputs=arrFilterSanitizeString($this->request->getVar());
					$rules = [
						'reading_from_date'=>[
							'rules'=>"required",
							'errors'=>[
								'required'=>'Reading From Date Required',								
							]
							],
						'reading_upto_date'=>[
							'rules'=>"required",
							'errors'=>[
								'required'=>'Reading Upto Date Required',								
							]
							],
						'from_date'=>[
							'rules'=>"required",
							'errors'=>[
								'required'=>'From Date Required',								
							]
							],
						'upto_date'=>[
							'rules'=>"required",
							'errors'=>[
								'required'=>'Upto Date Required',								
							]
							],
						'final_reading'=>[
							'rules'=>"required",
							'errors'=>[
								'required'=>'Final Reading Required',								
							]
							],
						'document'=>[
							'rules'=>'uploaded[document]|max_size[document,3072]|ext_in[document,jpg,jpeg,pdf]',
							'errors'=>[
								'uploaded[document]'=>'Document Is Required',	
								'max_size[document,3072]'=>'size is less than 3mb',
								'ext_in[document,jpg,pdf]'=>'Extention in jpg or pdf'						
							]
							],
						'remarks'=>[
							'rules'=>'required|min_length[10]',
							'errors'=>[
								'required'=>'remarks Is Required',	
								'min_length[10]'=>'remarks have at least 10 Charecters',					
							]
							],
						];
					if(!$this->validate($rules))
					{
						$data['validation']=$this->validator;	
						return view('water/water_connection/update_connection_date', $data);						

					}					
					elseif($inputs['from_date']>$curr_date ||  $inputs['upto_date']>$curr_date)
					{
						$data['error']="Range Of Demand Generation can not be greater than Current Date";						
					}
					elseif($inputs['reading_from_date']>=$inputs['reading_upto_date'])
					{
						$data['error']="Range Of Reading Date can not Same Date";						
					}

					if($data['error']!='')
					{
						throw new Exception($data['error']);
					}
					else
					{	
						$prive_upto_date = $inputs["reading_upto_date"]??date('Y-m-d');
						$prive_from_date = $inputs["reading_from_date"]??date('Y-m-d');

						$upto_date = $inputs["upto_date"]??date('Y-m-d');
						$from_date = $inputs["from_date"]??date('Y-m-d');
						
						$final_meter_reading = 0;

						$paid_demands_sql = "SELECT * 
											 FROM tbl_consumer_demand 
											 WHERE status = 1 AND paid_status = 1 
											 	AND consumer_id = ".$data['consumer_details']['id']
												." AND demand_from between '$from_date' AND '$upto_date' ";
						$paid_demands = $this->db->query($paid_demands_sql)->getResultArray();
						

						$all_demands_sql = "SELECT * 
											FROM tbl_consumer_demand 
											WHERE status = 1  
												AND consumer_id = ".$data['consumer_details']['id']
												." AND demand_from between '$from_date' AND '$upto_date' ";
						$all_demands = $this->db->query($all_demands_sql)->getResultArray();
						
						// $data['getpreviousMeterReding']= $this->initial_meter_reading->getpreviousMeterReding($data['consumer_details']['id'],$get_last_reading["id"]??0)['initial_reading']??0;
						
						$date1= date_create($prive_upto_date);
						$date2=date_create($prive_from_date);
						$date3 = date_create($upto_date);
						$date4 = date_create($from_date);
						$diff=date_diff($date2,$date1);
						$no_diff = $diff->format("%a");            
						$current_diff = date_diff($date3,$date4)->format("%a");
						$reading = ($inputs["final_reading"]??0) - (0);
						$arvg = $no_diff!=0 ? ($reading / $no_diff) : 1  ;
						$current_reading = ($current_diff * $arvg);
						$data["arg"]=[
							"priv_demand_from"=> $prive_from_date,
							"priv_demand_upto"=> $prive_upto_date,
							"demand_from"=> $date4->format("Y-m-d"),
							"demand_upto" => $date3->format("Y-m-d"),
							"priv_day_diff"=> $no_diff,
							"current_day_diff"=> $current_diff ,
							"last_reading" => $reading,
							"current_reading"=>$current_reading,
							"arvg" =>$arvg ,
						];
						$final_meter_reading = $arvg*$no_diff;
							
						$ids="";
						$paid_amount = 0;
						foreach($all_demands as $val)
						{
							$ids.=($val["id"].",");
							if($val["paid_status"]==1)
							{
								$paid_amount=$paid_amount+$val["amount"];
							}
						}
						$ids = rtrim($ids,",");
						
						if($data['connection_dtls']['connection_type']!=3)
						{
							$consumer_initial_meter['consumer_id']=$data['consumer_details']['id'];
							$consumer_initial_meter['initial_reading']=0;
							$consumer_initial_meter['emp_details_id']=$this->emp_details_id;;
							
							$this->initial_meter_reading->insertData($consumer_initial_meter);
						}						

						{
							$demands_log =[];
							$demands_log['consumer_id']=$data['consumer_details']['id'];
							$demands_log['demand_id']=$ids??'';
							$demands_log['type']='Waiver Average Billing';
							$demands_log['emp_details_id']=$this->emp_details_id;
							$demands_log['ip_address']=$_SERVER['REMOTE_ADDR'];
							$demands_log['remarks']=$inputs['remarks'];
							$chek = $this->Water_name_transfer_log_model->insertData_tbl_consumer_demand_audit($demands_log);
							if(!$chek)
							{
								throw new Exception("Some Error Occurst Due To Consumer Demand Update Please Contact To Admin 1");
							} 
							else
							{
								$file = $this->request->getFile('document'); 
								
								if($file)
								{
									$extension = $file->getExtension();                
									$city = $this->model_ulb_mstr->getCity($this->ulb_mstr_id)["city"]??"RANCHI";									
									if($file->isValid() && !$file->hasMoved())
									{
										$newName =$city.'/AverageBilling'.'/'.$chek.".".$extension;										
										if($file->move(WRITEPATH.'uploads/',$newName))
										{
											$update_log_sql = "update tbl_consumer_demand_audit set documents ='$newName' where id = $chek ";
											$this->db->query($update_log_sql)->getResultArray();
										}
									}                
								}
								
								
								if($ids)
								{

									$update_demands_sql = "update tbl_consumer_demand set status = 0 
															where id in ($ids)";
									$this->db->query($update_demands_sql)->getResultArray();
								}
								if($paid_amount>0 && $ids)
								{
									$collection_sql = "SELECT STRING_AGG(DISTINCT (transaction_id::text),',') transaction_id
														   FROM tbl_consumer_collection 
														   WHERE demand_id IN ($ids) AND status = 1 ";

									$tran_ids = $this->db->query($collection_sql)->getFirstRow("array")["transaction_id"]??null;
									
									if($tran_ids)
									{
										$paid_amount_sql = "SELECT SUM(paid_amount) as paid_amount
														FROM tbl_transaction 
														WHERE status = 1 AND transaction_type ='Demand Collection' 
																AND  related_id = ".$data['consumer_details']['id']
																."AND id IN($tran_ids) ";
										$paid_amount = $this->db->query($paid_amount_sql)->getFirstRow("array")["paid_amount"]??$paid_amount;

										$update_transection_sql = "UPDATE tbl_transaction SET status = 0 
																	WHERE status = 1 AND transaction_type ='Demand Collection' 
																		AND  related_id = ".$data['consumer_details']['id']
																		."AND id IN($tran_ids)";
										$this->db->query($update_transection_sql)->getResultArray();
	
										$update_collection_sql  = "UPDATE tbl_consumer_collection SET status = 0 
																	WHERE status = 1  
																		AND  consumer_id = ".$data['consumer_details']['id']
																		."AND demand_id IN($ids)";
										$this->db->query($update_collection_sql)->getResultArray();
									}
									
									$advance_insert_sql = "insert into tbl_advance_mstr(related_id,module,amount,reason,remarks,user_id)
																values(".$data['consumer_details']['id'].",'consumer', $paid_amount,'adjust amount','".$inputs['remarks']."',".$this->emp_details_id." ) ";	
									$this->db->query($advance_insert_sql)->getResultArray();								
									
								}
							}
							if($data['connection_dtls']['connection_type']==3 || $final_meter_reading>0)
							{
								$tax_id = $this->averageBulling($data['consumer_details']['id'],$data["arg"]["demand_from"],$data["arg"]["demand_upto"],$data["arg"]["current_reading"],null,$data["arg"]);
								
								$new_demands_sql = "select * from  tbl_consumer_demand  
														WHERE status = 1  AND consumer_id = ".$data['consumer_details']['id'];
								
								if(!$tax_id)
								{
									throw new Exception("Some Error Occurst Due To Consumer Demand Genration Please Contact To Admin 1");
								}
							}
							else
							{
								throw new Exception("Some Error Occurst Due To Consumer Demand Genration Please Contact To Admin 2");
							}
						}
					}					
					if($this->db->transStatus() === FALSE)
					{
						$this->db->transRollback();
						// die("false");
						flashToast("message", "Something errordue to Update!!!");  
						return $this->response->redirect(base_url('WaterUpdateConsumerConnectionMeterDoc/odershitArvBilling/'.$consumer_id));
				
					}
					else
					{ 
						// die("commit");
						$this->db->transCommit();
						flashToast("message", "Update Consumer Successfully!!!");
						return $this->response->redirect(base_url('WaterUpdateConsumerConnectionMeterDoc/odershitArvBilling/'.$consumer_id));
					}
				}
				catch(Exception $e)
				{
					$this->db->transRollback();
					flashToast("message", $e->getMessage()); 
					// print_var($e->getLine());print_var($e->getFile());print_var($e->getMessage());die("ex");
					return $this->response->redirect(base_url('WaterUpdateConsumerConnectionMeterDoc/odershitArvBilling/'.$consumer_id));
				}
			}
            return view('water/water_connection/odershitArvBilling',$data);
        }
		else
		{
			flashToast("massege", "Consumer Not Found");
			return $this->response->redirect(base_url('WaterConsumerList/index/odershitArvBilling'));
		}
	}
	public function averageBulling($consumer_id,$demand_from, $upto_date, $final_reading = 0,$file=null,$args) // rule date 07/11/2022
    {
		
		$this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->initial_meter_reading = new WaterConsumerInitialMeterReadingModel($this->db);
        $this->meter_status_model = new WaterMeterStatusModel($this->db);
        $this->consumer_tax_model = new WaterConsumerTaxModel($this->db);
        $this->fixed_meter_rate_model = new WaterFixedMeterRateModel($this->db);
        $this->consumer_demand_model = new WaterConsumerDemandModel($this->db);
        $this->model_water_consumer = new model_water_consumer($this->db);
        $this->rate_chart_model = new WaterRateChartModel($this->db);
        $this->demand_penalty_master = new WaterDemandPenaltyMaster($this->db);
        $this->revised_meter_rate_model = new WaterRevisedMeterRateModel($this->db);
        $this->meter_rate_calc_model = new WaterMeterRateCalculationModel($this->db);
        $this->water_sms_log = new model_water_sms_log($this->db);
        $this->consumer_details_model=new water_consumer_details_model($this->db);        
        $this->water_reading_doc = new model_water_reading_doc($this->db);

        $demand_id = false; 
        $consumer_tax_id = null;
        $get_demanddetails = $this->consumer_demand_model->getLastDemand2($consumer_id);
        $last_demand_upto = $get_demanddetails['demand_upto'];
		
        $consumer_details = $this->model_water_consumer->consumerDetailsbyid($consumer_id);
        $area_sqmt = $consumer_details['area_sqmt'];
        $ward_mstr_id = $consumer_details['ward_mstr_id'];
        $property_type_id = $consumer_details['property_type_id'];
        $category = !empty(trim($consumer_details['category']))?trim($consumer_details['category']):"APL";
        $ulb_details = session()->get('ulb_dtl')??getUlbDtl();
        $ulb_short_name = substr($ulb_details['city'], 0, 3);
        $ward_no = $this->model_ward_mstr->getWardNoById(['ward_mstr_id' => $ward_mstr_id]);
        $ward_no = isset($ward_no['ward_no']) ? $ward_no['ward_no'] : 0;
        $demand_no = $ulb_short_name . str_pad($ward_no, 3, '0', STR_PAD_LEFT) . str_pad($this->emp_id, 5, '0', STR_PAD_LEFT) . '/';         
        $generation_date = date('Y-m-d');
        if ((!empty($get_demanddetails) && $last_demand_upto == "") || $property_type_id <= 0 || $consumer_details['area_sqmt'] <= 0) 
        {
            flashToast("error", "Update your area or property type!!!");
        }
       
        $prev_connection_details = $this->meter_status_model->getLastConnectionDetails($consumer_details['id']);
        if($prev_connection_details['connection_type'] == 3)
        {
            flashToast("error", "Can not Generate average Billig Of this Consumer!!!");
            return ;
        }
        if(in_array($prev_connection_details['connection_type'],[1]) && $property_type_id ==3 && ($prev_connection_details['rate_per_month']==0 || empty($prev_connection_details['rate_per_month'])))
        {
            flashToast("error", "Average bulling Rate Not Available Of this Consumer!!!");
            return ;
        }
        if(empty($prev_connection_details) || $prev_connection_details['connection_date']=='')
        {
            flashToast("error", "Connection Date Not Found!!!");
            return;
        }
        
        $prev_conn_date = $prev_connection_details['connection_date'];
		$first_rading = 0;
		if(!$demand_from || $demand_from<'2021-01-01')
		{
			$old_to_date =$upto_date;
			$old_from_date = $demand_from;
			if($upto_date>='2020-12-31')
			{
				$old_to_date = '2020-12-31';
			}
			$old_demand = $this->averageBullingOldRule($consumer_id,$old_from_date, $old_to_date, $final_reading = 0,$file=null,$args);
			$demand_from = '2021-01-01';
			$consumer_tax_id = $old_demand["consumer_tax_id"]??0;
			$first_rading=$old_demand["final_reading"]??0;			
		}
		// die;
        #------this is for new Meter Rate 2021-01-01
		if ($upto_date>=$demand_from && ($property_type_id !=3 && $prev_connection_details['connection_type'] == 1 || $prev_connection_details['connection_type'] == 2 && $prev_connection_details['meter_status']==0 )) 
        {
            if ($upto_date == "") 
            {
                $to_date = date('Y-m-d');
            } 
            else 
            {
                $to_date = $upto_date;
            }
            $get_initial_reading = $this->initial_meter_reading->initial_meter_reading($consumer_id);
            $initial_reading = $get_initial_reading['initial_reading']??0;            
            $diff_reading = $args["current_reading"]-$first_rading;			
            if(!$args)
            {
                flashToast("error", "Demand Not Generated!!!");
                return;
            }
            
            if ($property_type_id == 1) 
            {
                $where = " and category='$category' and ceil($diff_reading)>=from_unit and ceil($diff_reading)<=upto_unit ";
            } 
            else 
            {
                $where = " and ceil($diff_reading)>=from_unit and ceil($diff_reading)<=upto_unit ";
            }            
            $get_meter_calc = $this->meter_rate_calc_model->getMeterCalculationRate($this->ulb_type_id);
            
            $temp_pro = $property_type_id;
            if(in_array($property_type_id,[7]))
            {
                $temp_pro = 1;
            }
            elseif(in_array($property_type_id,[8]))
            {
                $temp_pro = 4;
            }
            elseif(!in_array($property_type_id,[1,2,3,4,5,6,7]))
            {
                $temp_pro = 8;  

            }
            $get_meter_rate_new = $this->revised_meter_rate_model->getMeterRate_new($temp_pro, $where);
            //end her
            $temp_diff = $diff_reading;
            $incriment = 0;
            $amount= 0;
            $ret_ids=''; 
            $meter_rate_id=0;
            $meter_calc_rate=0;
            foreach($get_meter_rate_new as $key=>$val)
            {       
                $meter_calc_rate = $val['amount'];
                $meter_calc_factor = $get_meter_calc['meter_rate']; 
                $meter_rate_id = $val['id']; 
                if($key==0)
                    $ret_ids .=  $val['id'];
                else
                    $ret_ids .=  ",".$val['id'];

                $reading = $incriment + $val['reading'];                 
                 if($reading<=$diff_reading && !empty($val['reading']))
                 {
                    $amount += $meter_calc_rate * $meter_calc_factor * $val['reading']; 
                    $reading = $val['reading'];                                     
                 } 
                 elseif(empty($val['reading']))
                 {
                    $reading = $temp_diff - $reading;
                    $amount += $meter_calc_rate * $meter_calc_factor * $reading;                     
                    break;  
                 }
                 else
                 {
                    $reading = $temp_diff - $incriment;
                    $amount += $meter_calc_rate * $meter_calc_factor * $reading;                     
                    break;                   
                 } 
                
                $incriment +=$val['reading'];

            }              
            $ret_ids = ltrim($ret_ids,',');
            $meter_calc_factor = $get_meter_calc['meter_rate'];  
            $meter_rate = $meter_calc_factor *  $meter_calc_rate ;       
            $meter_rate_id = $meter_rate_id;
            $total_amount = $amount;
            if ($total_amount >= 0) 
            {
                $consumer_tax = array();
                $consumer_tax['consumer_id'] = $consumer_id;
                $consumer_tax['ward_mstr_id'] = $ward_mstr_id;
                $consumer_tax['charge_type'] = 'Average';
                $consumer_tax['rate_id'] = $meter_rate_id;
                $consumer_tax['initial_reading'] = $first_rading;
                $consumer_tax['final_reading'] = $args["current_reading"];
                $consumer_tax['amount'] = $total_amount;
                $consumer_tax['effective_from'] = date('Y-m-d');
                $consumer_tax['emp_details_id'] = $this->emp_id;
                $consumer_tax['created_on'] = date('Y-m-d H:i:s');
				// print_var($consumer_tax);
                $consumer_tax_id = $this->consumer_tax_model->insertData($consumer_tax);
                $demand_no = $demand_no . str_pad($consumer_tax_id, 6, 'A', STR_PAD_LEFT);

                $consumer_demand = array();
                $consumer_demand['consumer_id'] = $consumer_id;
                $consumer_demand['ward_mstr_id'] = $ward_mstr_id;
                $consumer_demand['consumer_tax_id'] = $consumer_tax_id;
                $consumer_demand['generation_date'] = date('Y-m-d');
                $consumer_demand['amount'] = $total_amount;
                $consumer_demand['current_meter_reading '] = $args["current_reading"];
                $consumer_demand['unit_amount'] = $args['arvg'];
                $consumer_demand['demand_from'] = $demand_from;
                $consumer_demand['demand_upto'] = $to_date;
                $consumer_demand['emp_details_id'] = $this->emp_id;
                $consumer_demand['created_on'] = date('Y-m-d H:i:s');
                $consumer_demand['connection_type'] = 'Meter';
                $consumer_demand['demand_no'] = $demand_no;

                $demand_id = $this->consumer_demand_model->insertData($consumer_demand);
            }
            if($file && $demand_id)
            {
                $extension = $file->getExtension();                
                $city = $this->ulb_details['city'];

                if($file->isValid() && !$file->hasMoved())
                {
                    $newName =$city.'/meter_reading'.'/'.$demand_id.".".$extension;
                    if($file->move(WRITEPATH.'uploads/',$newName))
                    {
                        $tbl_meter_reading_doc=["demand_id"=>$demand_id,
                                        "file_name"=>$newName,
                                        "meter_no"=>$prev_connection_details['meter_no']??0,
                        ];
                        $this->water_reading_doc->insert_meter_reading_doc($tbl_meter_reading_doc);
                    }
                }                
            }
        }
		
        if ( $upto_date>=$last_demand_upto && $final_reading > 0) 
        {
            $consumer_last_reading_insert = array();
            $consumer_last_reading_insert['consumer_id'] = $consumer_id;
            $consumer_last_reading_insert['initial_reading'] = $final_reading;
            $consumer_last_reading_insert['emp_details_id'] = $this->emp_id;
            $consumer_last_reading_insert['created_on'] = $upto_date??date('Y-m-d H:i:s');
            $consumer_last_reading_insert['status'] = 1;

            $this->initial_meter_reading->insertData($consumer_last_reading_insert);
        }
        if($consumer_tax_id)
        {
            #-----------------------------------sms send code---------------------------------
            //----------------------sms data -----------------------
            $appliction = $consumer_details;
            $owner = $this->consumer_details_model->consumerDetails($appliction['id']);
            //---------------------- end sms data------------------
            $demands = $this->consumer_demand_model->getTotalAmountByCidTid($appliction['id']);
            $amount = $demands['amount']+$demands['penalty'];                    
            $sms = null;//Water(['amount'=>$amount,'consumer_no'=>$appliction['consumer_no'],"toll_free_no1"=>'1800 8904115','ulb_name'=>$this->ulb_details['ulb_name']],'Consumer Demand');
            if($sms['status'])
            {
                $message = $sms['sms'];
                $templateid = $sms['temp_id'];
                foreach ($owner as $val )
                {
                    $mobile=$val['mobile_no'];
                    $sms_log_data = ['emp_id'=>$this->emp_id,
                                    'ref_id'=>$appliction['id'],
                                    'ref_type'=>'tbl_consumer',
                                    'mobile_no'=>$mobile,
                                    'purpose'=>"Consumer Demand",
                                    'template_id'=>$templateid,
                                    'message'=>$message
                    ];
                    $sms_id =  $this->water_sms_log->insert_sms_log($sms_log_data);
                    $s = send_sms($mobile,$message, $templateid);
                    
                    if($s)
                    {
                        $update_sms_log = ['response'=>$s['response'],'smgid'=>$s['msg']];
                        $up = $this->water_sms_log->update_sms_log(['id'=>$sms_id],$update_sms_log); 
                        
                    } 

                }
            }
            #----------------------------------- end sms send code----------------------------
        }    
        return $consumer_tax_id??null;
    }

	public function averageBullingOldRule($consumer_id,$demand_from, $upto_date, $final_reading = 0,$file=null,$args) // rule date 07/11/2022
    {
		$this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->initial_meter_reading = new WaterConsumerInitialMeterReadingModel($this->db);
        $this->meter_status_model = new WaterMeterStatusModel($this->db);
        $this->consumer_tax_model = new WaterConsumerTaxModel($this->db);
        $this->fixed_meter_rate_model = new WaterFixedMeterRateModel($this->db);
        $this->consumer_demand_model = new WaterConsumerDemandModel($this->db);
        $this->model_water_consumer = new model_water_consumer($this->db);
        $this->rate_chart_model = new WaterRateChartModel($this->db);
        $this->demand_penalty_master = new WaterDemandPenaltyMaster($this->db);
        $this->revised_meter_rate_model = new WaterRevisedMeterRateModel($this->db);
        $this->meter_rate_calc_model = new WaterMeterRateCalculationModel($this->db);
        $this->water_sms_log = new model_water_sms_log($this->db);
        $this->consumer_details_model=new water_consumer_details_model($this->db);        
        $this->water_reading_doc = new model_water_reading_doc($this->db);

        $demand_id = false; 
		$diffrence = 0;
        $consumer_tax_id = null;
        $get_demanddetails = $this->consumer_demand_model->getLastDemand2($consumer_id);
        $last_demand_upto = $get_demanddetails['demand_upto'];
        $consumer_details = $this->model_water_consumer->consumerDetailsbyid($consumer_id);
        $area_sqmt = $consumer_details['area_sqmt'];
        $ward_mstr_id = $consumer_details['ward_mstr_id'];
        $property_type_id = $consumer_details['property_type_id'];
        $category = !empty(trim($consumer_details['category']))?trim($consumer_details['category']):"APL";
        $ulb_details = session()->get('ulb_dtl')??getUlbDtl();
        $ulb_short_name = substr($ulb_details['city'], 0, 3);
        $ward_no = $this->model_ward_mstr->getWardNoById(['ward_mstr_id' => $ward_mstr_id]);
        $ward_no = isset($ward_no['ward_no']) ? $ward_no['ward_no'] : 0;
        $demand_no = $ulb_short_name . str_pad($ward_no, 3, '0', STR_PAD_LEFT) . str_pad($this->emp_id, 5, '0', STR_PAD_LEFT) . '/';         
        
        if ((!empty($get_demanddetails) && $last_demand_upto == "") || $property_type_id <= 0 || $consumer_details['area_sqmt'] <= 0) 
        {
            flashToast("error", "Update your area or property type!!!");
        }
       
        $prev_connection_details = $this->meter_status_model->getLastConnectionDetails($consumer_details['id']);
        if($prev_connection_details['connection_type'] == 3)
        {
            flashToast("error", "Can not Generate average Billig Of this Consumer!!!");
            return ;
        }
        if(in_array($prev_connection_details['connection_type'],[1]) && $property_type_id ==3 && ($prev_connection_details['rate_per_month']==0 || empty($prev_connection_details['rate_per_month'])))
        {
            flashToast("error", "Average bulling Rate Not Available Of this Consumer!!!");
            return ;
        }
        if(empty($prev_connection_details) || $prev_connection_details['connection_date']=='')
        {
            flashToast("error", "Connection Date Not Found!!!");
            return;
        }
        
        #------this is for new Meter Rate 2021-01-01
		if ($property_type_id !=3 && $prev_connection_details['connection_type'] == 1 || $prev_connection_details['connection_type'] == 2 && $prev_connection_details['meter_status']==0 ) 
        {
            if ($upto_date == "") 
            {
                $to_date = '2020-12-31';
            } 
            else 
            {
                $to_date = $upto_date;
            }
            if(!$args)
            {
                flashToast("error", "Demand Not Generated!!!");
                return;
            }
            
			$fixed_rate_details = $this->fixed_meter_rate_model->getMeterRateCharge($property_type_id, $area_sqmt, $demand_from);
            $rate_effect_details = $this->fixed_meter_rate_model->getMeterEffectBetweenDemandGeneration($property_type_id, $area_sqmt, $demand_from);  
			if(!$demand_from)
			{
				$demand_from = $rate_effect_details[0]['effective_date'];
			}
			
            $get_meter_calc = $fixed_rate_details["amount"]??1;
			$date1= date_create($upto_date);
			$date2=date_create($demand_from);
			$diff=date_diff($date1,$date2);
			$no_diff = $diff->format("%a");
			$diffrence = round(($args["arvg"] * $no_diff),2);
			$secend_rading = round(($args["current_reading"]-$diffrence),2);
			$amount = $diffrence * $get_meter_calc;	
            $amount= $amount > 0 ? $amount : 0; 
			$meter_rate_id =$fixed_rate_details["id"]??0;         
            $total_amount = $amount;
            if ($total_amount >= 0) 
            {
                $consumer_tax = array();
                $consumer_tax['consumer_id'] = $consumer_id;
                $consumer_tax['ward_mstr_id'] = $ward_mstr_id;
                $consumer_tax['charge_type'] = 'Average';
                $consumer_tax['rate_id'] = $meter_rate_id;
                $consumer_tax['initial_reading'] = 0;
                $consumer_tax['final_reading'] = $diffrence;
                $consumer_tax['amount'] = $total_amount;
                $consumer_tax['effective_from'] = date('Y-m-d');
                $consumer_tax['emp_details_id'] = $this->emp_id;
                $consumer_tax['created_on'] = date('Y-m-d H:i:s');

                $consumer_tax_id = $this->consumer_tax_model->insertData($consumer_tax);
                $demand_no = $demand_no . str_pad($consumer_tax_id, 6, 'A', STR_PAD_LEFT);

                $consumer_demand = array();
                $consumer_demand['consumer_id'] = $consumer_id;
                $consumer_demand['ward_mstr_id'] = $ward_mstr_id;
                $consumer_demand['consumer_tax_id'] = $consumer_tax_id;
                $consumer_demand['generation_date'] = date('Y-m-d');
                $consumer_demand['amount'] = $total_amount;
                $consumer_demand['current_meter_reading '] = $diffrence;
                $consumer_demand['unit_amount'] = $args['arvg'];
                $consumer_demand['demand_from'] = $demand_from;
                $consumer_demand['demand_upto'] = $to_date;
                $consumer_demand['emp_details_id'] = $this->emp_id;
                $consumer_demand['created_on'] = date('Y-m-d H:i:s');
                $consumer_demand['connection_type'] = 'Meter';
                $consumer_demand['demand_no'] = $demand_no;

                $demand_id = $this->consumer_demand_model->insertData($consumer_demand);
            }
            if($file && $demand_id)
            {
                $extension = $file->getExtension();                
                $city = $this->ulb_details['city'];

                if($file->isValid() && !$file->hasMoved())
                {
                    $newName =$city.'/meter_reading'.'/'.$demand_id.".".$extension;
                    if($file->move(WRITEPATH.'uploads/',$newName))
                    {
                        $tbl_meter_reading_doc=["demand_id"=>$demand_id,
                                        "file_name"=>$newName,
                                        "meter_no"=>$prev_connection_details['meter_no']??0,
                        ];
                        $this->water_reading_doc->insert_meter_reading_doc($tbl_meter_reading_doc);
                    }
                }                
            }
        }
        if ($final_reading > 0) 
        {
            $consumer_last_reading_insert = array();
            $consumer_last_reading_insert['consumer_id'] = $consumer_id;
            $consumer_last_reading_insert['initial_reading'] = $final_reading;
            $consumer_last_reading_insert['emp_details_id'] = $this->emp_id;
            $consumer_last_reading_insert['created_on'] = $upto_date??date('Y-m-d H:i:s');
            $consumer_last_reading_insert['status'] = 1;

            $this->initial_meter_reading->insertData($consumer_last_reading_insert);
        }

        return ["consumer_tax_id"=>$consumer_tax_id,"final_reading"=>$diffrence]??null;
    }

	public function deactivateUnpaidDeamands($consumer_id)
	{
		$data=array();
        $data['user_type']=$this->user_type_mstr_id;
		if(!(in_array($this->user_type_mstr_id,[1,2]) || in_array($this->emp_details_id,[1395])))
		{
			flashToast("error", "Demand Not Gererated Now Please Wait"); 
            return redirect()->back()->with('error', "You Are Not Authorized For This");
		}
        $data['consumer_id']=$consumer_id; 
        $data['ulb_mstr_id']=$this->ulb_mstr_id;
		$data['consumer_details']=$this->model_view_water_consumer->waterConsumerDetailsById($consumer_id);		
        if(!empty($data['consumer_details']))
        {
            $data['owner_name']=$this->consumer_details_model->consumerDetails($data['consumer_details']['id']);
			$connection_dtl_sql = "select * from tbl_meter_status where status=1 AND consumer_id = ".$data['consumer_details']['id']." ORDER BY connection_date ASC ";
            $data['connection_dtls']= $this->db->query($connection_dtl_sql)->getFirstRow("array");			
            
            $get_last_reading = $this->initial_meter_reading->initial_meter_reading($data['consumer_details']['id']);
            $data['last_reading'] = $get_last_reading['initial_reading'];
			$get_first_reading = $this->initial_meter_reading->initial_meter_first_reading($data['consumer_details']['id']);
			
			$data['first_reading'] = $get_first_reading['initial_reading'];

			$data['last_demand_paid_from'] = $this->consumer_demand_model->getDueFrom2($data['consumer_details']['id']);
			$last_paid_demands = $this->consumer_demand_model->get_last_paid_demand($data['consumer_details']['id']);
			$data['last_paid_demands']=$last_paid_demands;
			$fist_paid_demand = $this->consumer_demand_model->get_fist_paid_demand($data['consumer_details']['id']);
			$fist_paid_demand =$fist_paid_demand['demand_from']??date('Y-m-d');
			$unpaid_demands_sql = "SELECT * FROM tbl_consumer_demand WHERE status = 1 AND paid_status = 0 AND consumer_id = ".$data['consumer_details']['id']." ORDER BY demand_from desc";
			$demands = $this->db->query($unpaid_demands_sql)->getResultArray();
			$data["demand_list"] = $demands;
			
			if($this->request->getMethod()=='post')
			{
				
				$this->db->transBegin();
				try
				{	
					$curr_date = date('Y-md');
					$data['error']='';
					$regex = '/^\s*[+\-]?(?:\d+(?:\.\d*)?|\.\d+)\s*$/';
					$inputs=arrFilterSanitizeString($this->request->getVar());
					
					$rules = [
						"check"=>[
							'rules'=>'required',
							'errors'=>[
								'required'=>'Please Select atlist One Item',					
							]
							],
						'document'=>[
							'rules'=>'uploaded[document]|max_size[document,3072]|ext_in[document,jpg,jpeg,pdf]',
							'errors'=>[
								'uploaded[document]'=>'Document Is Required',	
								'max_size[document,3072]'=>'size is less than 3mb',
								'ext_in[document,jpg,pdf]'=>'Extention in jpg or pdf'						
							]
							],
						'remarks'=>[
							'rules'=>'required|min_length[10]',
							'errors'=>[
								'required'=>'remarks Is Required',	
								'min_length[10]'=>'remarks have at least 10 Charecters',					
							]
							],
						];
					if(!$this->validate($rules))
					{
						$data['validation']=$this->validator;	
						return view('water/water_connection/deactivateUnpaidDeamands', $data);						

					}				
					
					else
					{
						$Post_dimandsIds = implode(",",$inputs["check"]);
						{
							
							$demands_log =[];
							$demands_log['consumer_id']=$data['consumer_details']['id'];
							$demands_log['demand_id']=$Post_dimandsIds??'';
							$demands_log['type']='Deactivate Deamdnds';
							$demands_log['emp_details_id']=$this->emp_details_id;
							$demands_log['ip_address']=$_SERVER['REMOTE_ADDR'];
							$demands_log['remarks']=$inputs['remarks'];
							$demands_log['remarks']=$inputs['remarks'];
							$chek = $this->Water_name_transfer_log_model->insertData_tbl_consumer_demand_audit($demands_log);
							if(!$chek)
							{
								throw new Exception("Some Error Occurst Due To Consumer Demand Update Please Contact To Admin 1");
							} 
							if($Post_dimandsIds)
							{
								$file = $this->request->getFile('document');
								$extension = $file->getExtension();							
								$city = $this->model_ulb_mstr->getCity($this->ulb_mstr_id);
								if($file->isValid() && !$file->hasMoved())
								{
									$newName = $chek.".".$extension;
									if($file->move(WRITEPATH.'uploads/'.$city['city'].'/Demand_deactivation',$newName))
									{
										$file_name=$city['city'].'/Demand_deactivation'.'/'.$newName	;
										$sql_doc_update = "UPDATE tbl_consumer_demand_audit SET documents ='".$file_name."' 
														   WHERE id =".$chek ;							
										$this->db->query($sql_doc_update)->getResultArray();							
									
									}
								}

								$update_demands_sql = "update tbl_consumer_demand set status = 0 
														where id in ($Post_dimandsIds)";
								$this->db->query($update_demands_sql)->getResultArray();
							}
							
						}
					}					
					if($this->db->transStatus() === FALSE)
					{
						$this->db->transRollback();
						flashToast("message", "Something errordue to Update!!!");  
						return $this->response->redirect(base_url('WaterUpdateConsumerConnectionMeterDoc/deactivateUnpaidDeamands/'.$consumer_id));
				
					}
					else
					{ 
						$this->db->transCommit();
						flashToast("message", "Demand Removed Successfully!!!");
						return $this->response->redirect(base_url('WaterUpdateConsumerConnectionMeterDoc/deactivateUnpaidDeamands/'.$consumer_id));
					}
				}
				catch(Exception $e)
				{
					$this->db->transRollback();
					flashToast("message", $e->getMessage());
					return $this->response->redirect(base_url('WaterUpdateConsumerConnectionMeterDoc/deactivateUnpaidDeamands/'.$consumer_id));
				}
			}
            return view('water/water_connection/deactivateUnpaidDeamands',$data);
        }
		else
		{
			flashToast("massege", "Consumer Not Found");
			return $this->response->redirect(base_url('WaterConsumerList/index/deactivateUnpaidDeamands'));
		}
	}
	
}
