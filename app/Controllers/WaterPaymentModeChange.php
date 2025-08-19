<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_view_water_consumer;
use App\Models\model_ward_mstr;
use App\Models\Water_Transaction_Model;
use App\Models\WaterApplyNewConnectionModel;
use App\Models\water_consumer_details_model;
use App\Models\Water_Cheque_Details_Model;
use App\Models\WaterPaymentModeUpdateModel;
use App\Models\WaterPaymentModel;


class WaterPaymentModeChange extends AlphaController
{
    protected $water;
    // protected $db;
    protected $dbSystem;
    protected $model_view_water_consumer;
    protected $model_ward_mstr;
    protected $ulb_id;
    protected $emp_id;
    

    public function __construct(){
        
        $session=session();
        $ulb_details=$session->get('ulb_dtl');
        //print_r($ulb_details);
        $this->ulb_id=$ulb_details['ulb_mstr_id'];
        $this->ulb_city_nm=$ulb_details['city'];

        $emp_details=$session->get('emp_details');
        $this->emp_id=$emp_details['id'];
        $this->ip_address=$emp_details['ip_address'];
        //print_r($emp_details);

        parent::__construct();
        helper(['db_helper','form']);
        if($db_name = dbConfig("water")){
            $this->water = db_connect($db_name);            
        }
        if($db_name = dbSystem()){
            $this->dbSystem = db_connect($db_name); 
        }
         /*if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name); 
        }*/
        $this->model_view_water_consumer = new model_view_water_consumer($this->water);
        $this->water_transaction_model=new Water_Transaction_Model($this->water);
        $this->water_application_details=new WaterApplyNewConnectionModel($this->water);
        $this->water_consumer_details_model=new water_consumer_details_model($this->water);
        $this->cheque_details_model=new Water_Cheque_Details_Model($this->water);
        $this->payment_mode_update_model=new WaterPaymentModeUpdateModel($this->water);
        $this->payment_model=new WaterPaymentModel($this->water);

    }
    
    public function index()
    {   

        $data=array();
        $data['ulb_mstr_id']=$this->ulb_id;
        //print_r($data);
        
        if($this->request->getMethod()=='post')
        {   
            
            $inputs = filterSanitizeStringtoUpper($this->request->getVar());
            
            $data['transaction_no']=$inputs['transaction_no'];
            $where=" and transaction_no='".$data['transaction_no']."'";
            $data['transaction_details']=$this->water_transaction_model->getTransactionDetailsForModeUpdate($where);
            //  print_r($data['transaction_details']);
            if(!empty($data['transaction_details']))
            {


	            if($data['transaction_details']['payment_mode']=='CHEQUE')
	            {
	            	$data['cheque_details']=$this->cheque_details_model->getChequeDetailsByTransactionId($data['transaction_details']['id'],"");
	            	//print_r($data['cheque_details']);
	            }
	            if($data['transaction_details']['transaction_type']=='Demand Collection')
	            {   
	                $where=" id=".$data['transaction_details']['related_id'];
	                $consumer_details=$this->model_view_water_consumer->waterConsumerLists($where);
	                $data['consumer_details']=$consumer_details[0];
	                //print_r($consumer_details[0]);
	                $data['owner_details']=$this->consumer_details_model->consumerDetailsbyMd5(md5($data['transaction_details']['related_id']));


	            }
	            else
	            {   
	                $data['consumer_details']=$this->water_application_details->water_conn_details(md5($data['transaction_details']['related_id']));
	                $data['owner_details']=$this->water_application_details->water_owner_details(md5($data['transaction_details']['related_id']));
	                
	            }

	            if($inputs['update'])
	            {
	            	$inputs = filterSanitizeStringtoUpper($this->request->getVar());
	            	//print_r($inputs);
	            	$data['transaction_id']=$inputs['transaction_id'];
					$data['payment_mode']=$inputs['payment_mode'];
	            	$data['cheque_no']=$inputs['cheque_no'];
	            	$data['cheque_date']=$inputs['cheque_date'];
	            	$data['bank_name']=$inputs['bank_name'];
	            	$data['branch_name']=$inputs['branch_name'];
	            	
	            	$this->water_transaction_model->updatePaymentMode($data['transaction_id'],$data['payment_mode']);

	            	

	            	if($data['payment_mode']!='CASH')
	            	{	
	            	
	            		$count=$this->cheque_details_model->checkExistsChequebyTransactionId(md5($data['transaction_id']));
	            		

	            		if($count>0)
	            		{
	            			$data['cheque_dtl_id']=$inputs['cheque_dtl_id'];
		            		$data['cheque_no']=$inputs['cheque_no'];
			            	$data['cheque_date']=$inputs['cheque_date'];
			            	$data['bank_name']=$inputs['bank_name'];
			            	$data['branch_name']=$inputs['branch_name'];
							
							$this->water_transaction_model->updateChequeDetails($data);
	            		}
	            		else
	            		{
	            			$chq_arr=array();
		                    $chq_arr['transaction_id']=$data['transaction_id'];
		                    $chq_arr['cheque_no']=$data['cheque_no'];
		                    $chq_arr['cheque_date']=$data['cheque_date'];
		                    $chq_arr['bank_name']=$data['bank_name'];
		                    $chq_arr['branch_name']=$data['branch_name'];
		                    $chq_arr['emp_details_id']=$this->emp_id;
		                    $chq_arr['created_on']=date('Y-m-d H:i:s');
		                    $chq_arr['status']=2;

		                    $this->payment_model->insert_cheque_details($chq_arr);
	            		}
	            		
	            		

	            		

	            		$rules=[
	                        'file'=>'uploaded[file]|max_size[file,10240]|ext_in[file,png,jpg,jpeg,pdf]',
	                	];
	                $file=$this->request->getFile('file');
	            	
	                if($this->validate($rules)){ 
	                if(isset($file))
	                {	
	                	
	                	$payment_mode_update_model=array();
	                	$payment_mode_update_model['transaction_id']=$data['transaction_id'];
	                	$payment_mode_update_model['payment_mode']=$data['payment_mode'];
	                	$payment_mode_update_model['cheque_no']=$data['cheque_no'];
	                	$payment_mode_update_model['cheque_date']=$data['cheque_date'];
	                	$payment_mode_update_model['bank_name']=$data['bank_name'];
	                	$payment_mode_update_model['branch_name']=$data['branch_name'];
	                	$payment_mode_update_model['emp_details_id']=$this->emp_id;
	                	$payment_mode_update_model['ip_address']=$this->ip_address;
						                	

	                    if($insert_id = $this->payment_mode_update_model->insertData($payment_mode_update_model)){
	                    if($file->IsValid() && !$file->hasMoved()){
	                        $newFileName = md5($insert_id);
	                        $file_ext = $file->getExtension();
	                        $path = $this->ulb_city_nm."/"."water_payment_mode_update";
	                        
	                        if($file->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext))
	                        {
	                            
	                            $file_name = $path."/".$newFileName.'.'.$file_ext;
	                            $this->payment_mode_update_model->updateData($file_name,md5($insert_id));
	                            $_SESSION['message']="Updated Successfully";
	                        }
	                    }
	                  }
	                }
	                }


	            	}
	            	
	            }

        	}
        	else
        	{
        		$_SESSION['message']='Transaction No. not found';
        	}

        }
        

       return view('water/water_connection/update_payment_mode',$data);


    }

}
?>
