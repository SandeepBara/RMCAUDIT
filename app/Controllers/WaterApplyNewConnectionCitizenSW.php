<?php 
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\WaterApplyNewConnectionModel;
use App\Models\WaterPropertyModel;
use App\Models\WaterConnectionThroughModel;
use App\Models\WaterConnectionTypeModel;
use App\Models\WaterPipelineModel;
use App\Models\model_ward_mstr;
use App\Models\PropertyModel;
use App\Models\StateModel;
use App\Models\DistrictModel;
use App\Models\WaterSiteInspectionModel;
use App\Models\WaterViewConnectionFeeModel;
use App\Models\Water_Transaction_Model;
use App\Models\WaterRoadAppartmentFeeModel;
use App\Models\WaterPenaltyInstallmentModel;
use App\Models\WaterViewPropertyDetailModel;
use App\Models\WaterViewSAFDetailModel;

use App\Models\model_view_water_consumer;
use App\Models\Citizensw_water_model;
use App\Models\Siginsw_water_model;



class WaterApplyNewConnectionCitizenSW extends HomeController
{
    protected $db;
    protected $property_db;
    protected $dbSystem;
    protected $user_type;
    protected $emp_id;
    protected $ulb_id;
    protected $state;
    protected $district;
    protected $city;
    protected $apply_wtrconn_model;
    protected $water_property_model;
    protected $conn_through_model;
    protected $conn_type_model;
    protected $pipeline_model;
    protected $ward_model;
    protected $property_model;
    protected $state_model;
    protected $district_model;
    protected $site_ins_model;
    protected $conn_fee_model;
    protected $trans_model;
    protected $road_app_fee_model;
    protected $penalty_installment_model;
    protected $water_prop_detail_model;
    protected $water_saf_detail_mode;
    protected $water_saf_detail_model;
    //protected $db_name;
    protected $Citizensw_water_model;
    protected $LogingCounter;
    protected $Siginsw_water_model;
    
    
    public function __construct()
    {
        
        $session=session();
        $get_ulb_detail=$session->get('ulb_dtl');
        //print_r($get_ulb_detail);

      	$this->ulb_id=$get_ulb_detail['ulb_mstr_id'];
        
        
        $get_emp_details=$session->get('emp_details');
        $this->emp_id=$get_emp_details['id'];
        $this->user_type=$get_emp_details['user_type_mstr_id'];
        
        
        parent::__construct();
        helper(['db_helper', 'form']);
        if($db_name = dbConfig("water")){
            //echo $db_name;
            $this->db = db_connect($db_name);            
        }
        if($db_name = dbConfig("property"))
        {
            $this->property_db = db_connect($db_name);
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        }
        
        
        
        $this->apply_wtrconn_model=new WaterApplyNewConnectionModel($this->db);
        $this->water_property_model=new WaterPropertyModel($this->db);
        $this->conn_through_model=new WaterConnectionThroughModel($this->db);
        $this->conn_type_model=new WaterConnectionTypeModel($this->db);
        $this->pipeline_model=new WaterPipelineModel($this->db);
        $this->ward_model=new model_ward_mstr($this->dbSystem);
        $this->property_model=new PropertyModel($this->property_db);
        $this->state_model=new statemodel($this->dbSystem);
        $this->district_model=new districtmodel($this->dbSystem);
        $this->site_ins_model=new WaterSiteInspectionModel($this->db);
        $this->conn_fee_model=new WaterViewConnectionFeeModel($this->db);
        $this->trans_model=new Water_Transaction_Model($this->db);
        $this->road_app_fee_model=new WaterRoadAppartmentFeeModel($this->db);
        $this->penalty_installment_model=new WaterPenaltyInstallmentModel($this->db);
        $this->water_prop_detail_model=new WaterViewPropertyDetailModel($this->db);
        $this->water_saf_detail_model=new WaterViewSAFDetailModel($this->db);

        $this->model_view_water_consumer = new model_view_water_consumer($this->db);
        $this->Citizensw_water_model = new Citizensw_water_model($this->db);
        $this->Siginsw_water_model = new Siginsw_water_model($this->db);
    }
    
    public function __destruct()
    {
        if($this->db)
            $this->db->close();
        if($this->dbSystem)
            $this->dbSystem->close();
        if($this->property_db)
            $this->property_db->close();
    }

    public function index()
    {
        $data=array();
        helper(['form']);
        
        $data['user_type']=$this->user_type;
        $data['property_type_list']=$this->water_property_model->property_list();
        $data['conn_type_list']=$this->conn_type_model->conn_type_list();
        $data['conn_through_list']=$this->conn_through_model->conn_through_list();
        $data['pipeline_type_list']=$this->pipeline_model->pipeline_list();
        $data['ulb_mstr_id']=$this->ulb_id;
        $data['ward_list']=$this->ward_model->getWardList($data); 
        $sw_inpcripted_data = session()->get('post_incrept');
        $sw_decript_data = session()->get('post_decript');

        if(session()->get("apply_from")=="sws")
        {
            $data['firstName']=$sw_decript_data['firstName'];
            $data['lastName']=$sw_decript_data['lastName'];
            $data['email']=$sw_decript_data['email'];
            $data['mobile']=$sw_decript_data['mobile']; 
            $data['connection_type_id'] = $_SESSION['serviceId']== 211 ? 1 : ($_SESSION['serviceId']==582 ? 2 : '' );

        }
        elseif(session()->get("apply_from")=="swsc")
        {
            $data['firstName']=$sw_decript_data['name'];
            $data['lastName']=$sw_decript_data['lastName']??'';
            $data['guardian_name'] = $sw_decript_data['father_name'];
            $data['email']=$sw_decript_data['email_id'];
            $data['mobile']=$sw_decript_data['mobile_no'];
            $data['pin']=$sw_decript_data['permanent_pin_no'];
            $data['category'] = $sw_decript_data['is_bpl']==1?"BPL":"APL";
            $data['address'] =$sw_decript_data['permanent_address_line1']." ".$sw_decript_data['permanent_address_line2'];
            if($sw_decript_data['permanent_village'])
            {
                $data['address']=trim($data['address'])." Vill-".$sw_decript_data['permanent_village']; 
            }
            if($sw_decript_data['permanent_district'])
            {
                $data['address']=trim($data['address'])." Dist-".$sw_decript_data['permanent_district'];
            }
            if($sw_decript_data['permanent_block'])
            {
                $data['address']=trim($data['address'])." Block-".$sw_decript_data['permanent_block'];
            }
            $data['connection_type_id'] = $_SESSION['serviceId']== "JH_UD_WT_NEW" ? 1 : 2;
           
        }
        else
        {
            return $this->response->redirect(base_url("citizensw/index"));
        }
            

        if($this->request->getMethod()=='post')
        {
            if($this->request->getVar('id')=="")
            {
                $inputs = filterSanitizeStringtoUpper($this->request->getVar());               
                $this->db->transBegin();

                $data['connection_type_id']=$data['connection_type_id'];
                $data['conn_through_id']=$inputs['conn_through_id'];
                $data['property_type_id']=$inputs['property_type_id'];
                $data['pipeline_type_id']=$inputs['pipeline_type_id'];
                $data['category']=$inputs['category']??null;
                $data['ward_id']=$inputs['ward_id'];
                $data['holding_no']=$inputs['holding_no']??null;
                $data['area_in_sqft']=$inputs['area_in_sqft'];
                if($data['area_in_sqft']!="" and is_numeric($data['area_in_sqft']))
                {
                    $data['area_in_sqmt']=$data['area_in_sqft']*0.092903;
                }
                if($inputs['category']=="")
                {
                    $data['category']="APL";
                }
                
                $data['address']=$inputs['address'];
                $data['landmark']=$inputs['landmark']??null;
                $data['pin']=$inputs['pin'];
              	
                // $data['holding_exists']=$inputs['holding_exists'];
                $data['saf_id']=$inputs['saf_id'];
                $data['prop_id']=$inputs['prop_id'];
                $data['count']=$inputs['count']??null;
                $data['owner_type']=$inputs['owner_type'];
                $data['saf_no']=$inputs['saf_no'];
                $data['owner_name']=$inputs['owner_name'];
                $data['mobile_no']=$inputs['mobile_no'];
               
                $data['guardian_name']=$inputs['guardian_name'];
                $data['email_id']=$inputs['email_id']??null;

                $data['elec_category']=$inputs['elec_category']??null;
                $data['elec_k_no']=$inputs['elec_k_no']??null;
                $data['elec_bind_book_no']=$inputs['elec_bind_book_no']??null;
                $data['elec_account_no']=$inputs['elec_account_no']??null;

                // checking if exists water connection for the holding as owner because for owner only one water connection is applied but for tenant multiples
                if($data['saf_id']>0)    
                {
                    $count_saf=$this->apply_wtrconn_model->check_saf_exists($data['saf_id']);
                    if($count_saf>0)
                    {
                        $_SESSION['msg']="Water Connection Already applied with this SAF No.";
                        return view("citizen/water/applywaterconnectionSW", $data);
                    }
                }
                


                if($data['prop_id']>0)
                {
                    $count_owner_prop=$this->apply_wtrconn_model->check_owner_holding_water_conn($data['prop_id']);
                    //print_r($count_owner_prop);
                    $count_prop=$count_owner_prop['count_prop'];
                    if($count_prop>0 and $data['owner_type']=='OWNER')
                    {
                        $_SESSION['msg']="Owner Already has Water Connection";
                        return view("citizen/water/applywaterconnectionSW",$data);
                    }
                }

                $alphaNumericSpacesDotDash = '/^[a-z0-9.\- ]+$/i';
                $rules=[                   
                    
                    'connection_type_id'=>'required|integer',
                    'conn_through_id' =>'required|integer',
                    'property_type_id' =>'required|integer',                    
                    'ward_id' =>'required|integer',                    
                    'area_in_sqft' =>'required|numeric',
                    'address' =>"required|regex_match[$alphaNumericSpacesDotDash]",
                    'landmark' =>'required',
                    'pin' =>'required|min_length[6]|max_length[6]|numeric', 
                    "owner_type"=>"required|in_list[OWNER,TENANT]", 
                    "ward_id"=>"required|integer",
                    "area_in_sqft"=>"required|numeric",
                    "landmark"=>"required|regex_match[$alphaNumericSpacesDotDash]",                    
                    "owner_name.*"=>"required|regex_match[$alphaNumericSpacesDotDash]",
                    "guardian_name.*"=>"required|regex_match[$alphaNumericSpacesDotDash]",
                    "mobile_no.*"=>"required|regex_match[/^[0-9]+$/i]|exact_length[10]",
                    "email_id.*"=>"required|valid_emails",
                    "elec_bind_book_no"=>"required|regex_match[$alphaNumericSpacesDotDash]",
                    "elec_category"=>"required|in_list[Residential - DS I/II,Commercial - NDS II/III,Agriculture - IS I/II,Low Tension - LTS,High Tension - HTS]",
                    "elec_account_no"=>"required|regex_match[$alphaNumericSpacesDotDash]",
                    "elec_k_no"=>"required|regex_match[/^[0-9]+$/i]",
                    
                ];
                if($inputs["category"]!="")
                {
                    $rules["category"]="in_list[APL,BPL]";
                }
                if($inputs["pipeline_type_id"]!="")
                {
                    $rules["pipeline_type_id"]="integer";
                }
                if($inputs["flat_count"]!="")
                {
                    $rules["flat_count"]="integer";
                }
                if(!$this->validate($rules))
                {
                    $data['validation']=$this->validator;
                    return view('citizen/water/applywaterconnectionSW', $data);
                }
                else
                {

                    $apply_water_conn=array();
                    if(isset($inputs['flat_count']) && $inputs['flat_count']!="")
                    {
                        $flat_count=$inputs['flat_count'];
                    }
                    else
                    {
                        $flat_count=0;
                    }
                    $apply_water_conn['connection_type_id']=$data['connection_type_id'];
                    $apply_water_conn['connection_through_id']=$data['conn_through_id'];
                    $apply_water_conn['property_type_id']=$data['property_type_id'];
                
                    $apply_water_conn['ward_id']=$data['ward_id'];
                    $apply_water_conn['holding_no']=$data['holding_no'];
                    $apply_water_conn['area_sqft']=$data['area_in_sqft'];
                    $apply_water_conn['area_sqmt']=$data['area_in_sqmt'];
                    $apply_water_conn['address']=$data['address'];
                    $apply_water_conn['landmark']=$data['landmark'];
                    $apply_water_conn['pin']=$data['pin'];
                    $apply_water_conn['saf_no']=$data['saf_no'];
                    $apply_water_conn['created_on']=date('Y-m-d H:i:s');
                    $apply_water_conn['road_type']='PWD';
                    $apply_water_conn['flat_count']=$flat_count;
                    $apply_water_conn['apply_from']= $_SESSION['apply_from']??'ONL';
                    
                    
                    if($data['property_type_id']==1)
                    {
                        $apply_water_conn['pipeline_type_id']=$data['pipeline_type_id'];
                        $apply_water_conn['category']=$data['category'];
                    }
                    else
                    {
                        $apply_water_conn['pipeline_type_id']=$data['pipeline_type_id']=2; // other than residential property pipeline type will be old pipeline
                        $apply_water_conn['category']=$data['category']='APL'; // other than residential property category type will be APL
                    }
                    $apply_water_conn['elec_category']=$data['elec_category']??null;
                    if($data['saf_id']!="")
                    {
                        $apply_water_conn['saf_dtl_id']=$data['saf_id'];
                    }
                    if($data['prop_id']!="")
                    {
                        $apply_water_conn['prop_dtl_id']=$data['prop_id'];
                    }  
                    
                    $apply_water_conn['owner_type']=$data['owner_type'];
                    $apply_water_conn['apply_date']=date('Y-m-d');
                    $apply_water_conn['user_id']=$this->emp_id;
                    
                    $apply_water_conn['elec_k_no']=$data['elec_k_no']??null;
                    $apply_water_conn['elec_bind_book_no']=$data['elec_bind_book_no']??null;
                    $apply_water_conn['elec_account_no']=$data['elec_account_no']??null;
                    $apply_water_conn['elec_category']=$data['elec_category']??null;

                    if($data['category']=="BPL")
                    {
                        $conn_fee=0;
                    }
                    else
                    {
                        $where='';
                        if($data['property_type_id']==1)
                        {
                            $where=" and  (".$data['area_in_sqft'].">=area_from_sqft and ".$data['area_in_sqft']."<=area_upto_sqft)";
                        }

                        $get_rate_dtls=$this->apply_wtrconn_model->getNewRateId($data['property_type_id'],$where);
                        $rate_id=$get_rate_dtls['id'];
                        //print_var($get_rate_dtls);die;
                        $apply_water_conn['water_fee_mstr_id']=$rate_id;


                        if($get_rate_dtls['calculation_type']=='Fixed')
                        {
                            $conn_fee=$get_rate_dtls['conn_fee'];
                        }
                        else
                        {
                            $conn_fee=$get_rate_dtls['conn_fee']*$data['area_in_sqft'];
                        }
                        
                    }
                    
                    $insert_id=$this->apply_wtrconn_model->insertData($apply_water_conn);

                    $conn_fee_charge=array();
                    $conn_fee_charge['apply_connection_id']=$insert_id;
                    $conn_fee_charge['charge_for']='New Connection';
                    $conn_fee_charge['conn_fee']=$conn_fee;
                    $conn_fee_charge['created_on']=date('Y-m-d H:i:s');
                    
                    // penalty 4000 for residential 10000 for commercial in regularization effective from 
                    // 01-01-2021 and half the amount is applied for connection who applied under 6 months from 01-01-2021 

                    $effective_date=date('2021-01-01');
                    $six_months_after=date('Y-m-d',strtotime($effective_date." + 6 months"));

                    
                    //echo $data['connection_type_id'];
                    $penalty=0;
                    if($data['connection_type_id']==2)
                    {

                        if(date('Y-m-d')<$six_months_after and $data['property_type_id']==1)
                        {
                            $penalty=2000;
                        }
                        else if($data['property_type_id']==1 and date('Y-m-d')>=$six_months_after)
                        {
                            $penalty=4000;
                        }
                        else if($data['property_type_id']!=1 and date('Y-m-d')<$six_months_after)
                        {
                            $penalty=5000;
                        }
                        else
                        {
                            $penalty=10000;
                        }
                        
                    }

                    if($penalty>0)
                    {
                    	$installment_amount1=($penalty*40)/100;
	                    $installment_amount2=($penalty*30)/100;
	                    
	                    for($j=1;$j<=3;$j++)
	                    {	
	                    	
	                    	if($j==1)
	                    	{
	                    		$installment_amount=$installment_amount1;
	                    	}
	                    	else
	                    	{
	                    		$installment_amount=$installment_amount2;
	                    	}
	                    	
	                    	
	                    	$penalty_installment=array();
		                    $penalty_installment['apply_connection_id']=$insert_id;
                            $penalty_installment['penalty_head']="$j"." Installment";
		                    $penalty_installment['installment_amount']=$installment_amount;
		                    $penalty_installment['balance_amount']=$installment_amount;
		                    $penalty_installment['paid_status']=0;
		                    
	                    	$this->penalty_installment_model->insertData($penalty_installment);
	                    }
                    	
                    }
                    
                    //exit();
					                   	
                    $conn_fee_charge['penalty']=$penalty;
                    $conn_fee_charge['amount']=$penalty+$conn_fee;
                    
                    //print_r($conn_fee_charge);
                    if($conn_fee_charge['amount']>0)
                    {
                        $this->apply_wtrconn_model->insert_conn_fee($conn_fee_charge);
                    }
                    
               		 
                    // print_r($insert_id);
                    if(isset($rate_id) && $rate_id!="")
                    {
                        $payment_status=0;
                    }
                    else
                    {
                        $payment_status=1;
                    }

                    $app_no="APP".$insert_id.date('dmyhis');

                    $this->apply_wtrconn_model->update_application_no($app_no,$payment_status,$insert_id);
                    
                    if($payment_status==1 &&  $data['category']=="BPL")
                    {
                        $this->apply_wtrconn_model->BPL_transection($insert_id,'CASH',$this->emp_id);
                    }
                

                    if($insert_id)
                    {
                        if(isset($inputs['owner_name']))
                        {

                            $owner_arr=array();
                            // print_r($inputs['owner_name']);
                            //  echo sizeof($inputs['owner_name']);

                            for($i=0;$i<sizeof($inputs['owner_name']);$i++)
                            {

                                    //echo  'owner_name'.$i.'';
                                    //echo $inputs['owner_name'][$i];
                                    
                                    $owner_arr['apply_connection_id']=$insert_id;

                                    $owner_arr['applicant_name']=$inputs['owner_name'][$i];

                                    $owner_arr['father_name']=$inputs['guardian_name'][$i];
                                    
                                    $owner_arr['mobile_no']=$inputs['mobile_no'][$i];
                                    if($owner_arr['mobile_no']=="")
                                    {
                                        $owner_arr['mobile_no']=0;
                                    }
                                    $owner_arr['email_id']=$inputs['email_id'][$i];

                                    $owner_arr['emp_details_id']=$this->emp_id;
                                    
                                    $owner_arr['created_on']=date('Y-m-d H:i:s');
                            
                                    $this->apply_wtrconn_model->insert_owner($owner_arr);

                            }
                            
                            //print_r($owner_arr);
                        }
                        if(isset($_SESSION['apply_from']))
                        {
                            $tbl_single_window_apply=[];
                            $tbl_single_window_apply['apply_connection_id']=$insert_id;
                            $tbl_single_window_apply['cust_id']=$_SESSION["custId"];
                            $tbl_single_window_apply['caf_no']=$_SESSION["caf_unique_no"];
                            $tbl_single_window_apply['service_id']=$_SESSION['serviceId'];
                            $tbl_single_window_apply['department_id']=$_SESSION['departmentId'];
                            $tbl_single_window_apply['application_status']=0;
                            $tbl_single_window_apply['sw_stage']=1;
                            $tbl_single_window_apply['entry_date']=date('Y-m-d');
                            $tbl_single_window_apply['total_amount']=0;
                            $tbl_single_window_apply['apply_from']=$_SESSION['apply_from'];

                            $sw_id = $this->Citizensw_water_model->insertData($tbl_single_window_apply);  
                            
                            if($_SESSION['apply_from']=="sws")
                            {

                                $push_sw=array();
                                $path = '';//base_url('citizenPaymentReceipt/view_trade_transaction_receipt/' . $this->ulb_id . '/' . md5($data["license"]['id']) . '/' . md5($transaction_id));
                                $push_sw['application_stage']=1;
                                $push_sw['status']='Application submitted ';
                                $push_sw['acknowledgment_no']=$app_no;
                                $push_sw['service_type_id']=$tbl_single_window_apply['service_id'];
                                $push_sw['caf_unique_no']=$tbl_single_window_apply['caf_no'];
                                $push_sw['department_id']=$tbl_single_window_apply['department_id'];
                                $push_sw['Swsregid']=$tbl_single_window_apply['cust_id'];
                                $push_sw['payable_amount ']=0;
                                $push_sw['payment_validity']='';
                                $push_sw['payment_other_details']='';
                                $push_sw['certificate_url']=$path;
                                $push_sw['approval_date']='';
                                $push_sw['expire_date']='';
                                $push_sw['licence_no']='';
                                $push_sw['certificate_no']='';
                                $push_sw['customer_id']=$tbl_single_window_apply['cust_id'];                            
                                $post_url =getenv('single_indow_push_url');
                                $http = getenv('single_indow_push_http');
                                $resp = httpPostJson($post_url,$push_sw,$http);
                                // print_var($resp);
                                // die;
                                $respons_data=[];
                                $respons_data['apply_connection_id']=$insert_id;
                                $respons_data['response_msg']= json_encode(['url'=>$http.'/'.$post_url,
                                                                                'data'=>$push_sw]);
                                $respons_data['tbl_single_window_id']=$sw_id;
                                $respons_data['emp_id']=null;
                                $respons_data['response_status']=json_encode($resp);
                                $this->Citizensw_water_model->insertResponse($respons_data);
                            }
                            elseif($_SESSION['apply_from']=="swsc")
                            {
                                $emp_id = $this->emp_id;
                                $ip = $this->request->getIPAddress();
                                $login = $this->Siginsw_water_model->loginSinglewindowCitizen($emp_id,$ip);
                                if(isset($login['status']) && $login['status']!="Success")
                                {
                                    $_SESSION['msg']="Single Window Not Login Please Contact To Admin";
                                    return $this->response->redirect(base_url("WaterApplyNewConnectionCitizenSW/index"));
                                }

                                $update_window_singin = [
                                    "apply_connection_id" =>$insert_id,
                                    "tbl_single_window_id" => $sw_id,                                    

                                ];
                                $where_sigin = [
                                    "id"=>$login['single_window_singin_id']
                                ];
                                $this->Siginsw_water_model->updateData($update_window_singin,$where_sigin);

                                $push_sw=array();
                                $path = '';//base_url('citizenPaymentReceipt/view_trade_transaction_receipt/' . $this->ulb_id . '/' . md5($data["license"]['id']) . '/' . md5($transaction_id));
                                $push_sw['application_stage']=1;
                                $push_sw['current_status']='Application submitted ';

                                $push_sw['caf_no']=$tbl_single_window_apply['caf_no'];
                                $push_sw['sws_reference_no']=$tbl_single_window_apply['department_id'];
                                $push_sw['dept_reference_no']=$app_no;
                                $push_sw['service_id']=$tbl_single_window_apply['service_id'];
                                $push_sw['submission_date']=date('Y-m-d');
                                $push_sw['approval_no']='';
                                $push_sw['approval_date']='';
                                $push_sw['certificate_type']='URL';
                                $push_sw['certificate_url ']=$path;
                                $push_sw['valid_upto'] = '';

                                $post_url =getenv('citizen_single_indow_push_url');
                                $http = getenv('citizen_single_indow_push_http');

                                $resp = httpPostHeaderJson($post_url,$push_sw,$login['token'],$http);  
                                
                                // print_var($push_sw);
                                // print_var($resp);
                                // print_var($login['token']);
                                // die;
                                $respons_data=[];
                                $respons_data['apply_connection_id']=$insert_id;
                                $respons_data['response_msg']= json_encode(['url'=>$http.'/'.$post_url,
                                                                                'data'=>$push_sw]);
                                $respons_data['tbl_single_window_id']=$sw_id;
                                $respons_data['emp_id']=null;
                                $respons_data['response_status']=json_encode($resp);
                                $respons_data['token']          = $login['token'];
                                $respons_data['tbl_single_window_singin'] = $login['single_window_singin_id'];
                                $this->Citizensw_water_model->insertResponse($respons_data);
                                   

                            }

                        }


                    }
                    if($this->db->transStatus()=== FALSE)
                    {
                        flashToast('message','Error Occures Please Contact to Admin');
                        return view('citizen/water/applywaterconnectionSW', $data);
                        return $this->response->redirect(base_url('WaterApplyNewConnectionCitizenSW/index'));
                    }
                    else
                    {
                        $this->db->transCommit();
                        return $this->response->redirect(base_url('WaterApplyNewConnectionCitizen/water_connection_view/'.md5($insert_id)));
                    }
                   
                }

            }
         
        }
        
        else
        {   
            return view('citizen/water/applywaterconnectionSW', $data);
        }
    }

    
    public function water_connection_view($insert_id)
    {
        return $this->response->redirect(base_url("WaterApplyNewConnectionCitizen/water_connection_view/$insert_id"));  
		
        $data['user_type']=$this->user_type;

		$data['consumer_details']=$this->apply_wtrconn_model->water_conn_details($insert_id);
		
		$data['site_inspection_details']=$this->site_ins_model->getSiteInspectionDetailsbyJE($insert_id);
		$data['owner_details']=$this->apply_wtrconn_model->water_owner_details($insert_id);
		
		$data['water_conn_id']=$insert_id;
		//print_var($data['consumer_details']);
		$data['dues']=$this->conn_fee_model->conn_fee_charge($insert_id);
		$data['transaction_count']=$this->trans_model->getTransCountbyApplicationId($insert_id);
		
		$data['application_status']=$this->application_status($insert_id);
        //print_var($_SESSION['ulb_dtl']['ulb_mstr_id']);
        $data['ulb_id'] = $_SESSION['ulb_dtl']['ulb_mstr_id'];
		return view('citizen/water/water_connection_details_view', $data);
    }

    public function application_status($insert_id)
    {
        return $this->apply_wtrconn_model->application_status($insert_id);
    }

    public function validate_holding_no($holding_no_arg=null)
    {
        if($holding_no_arg!=null || $this->request->getMethod()=="post")
        {            
            
            if($holding_no_arg!=null)
                $holding_no=$holding_no_arg;
            else
            {
                $inputs = arrFilterSanitizeString($this->request->getVar()); 
                $holding_no=$inputs['holding_no']??null;
            }
                
            
            if ($count = $this->water_prop_detail_model->chkholding_exist($holding_no))
            {
                //print_r($count[0]['prop_type_mstr_id']);
                if($count[0]['prop_type_mstr_id']==4)
                {
                    $response = ['response'=>false, 'dd'=>['message'=>'This is a vacant land']];
                }
                else if($count[0]['is_old'][0]==1)
                {
                    $response = ['response'=>false, 'dd'=> ['message'=>'This is a old property']];
                }
                else
                {

                    $response = ['response'=>true, 'dd'=>$count];
                }
                
            }
            else
            {
                $response = ['response'=>false, 'dd'=> ['message'=>'Holding No not found']];
            }
        }
        else
        {
            $response = ['response'=>false, 'dd'=> ['message'=>'Argument is not proper', ]];
        }
        if($holding_no_arg!=null)
            return $response;
        return json_encode($response);
    }
        

    public function validate_saf_no($saf_arg=null)
    {   
        if($saf_arg!=null || $this->request->getMethod()=="post")
        {
            if($saf_arg!=null)
                $inputs=$saf_arg;
            else
                $inputs = arrFilterSanitizeString($this->request->getVar());             
            $data['ward_id']=$inputs['ward_id']??null;
            $saf_no=$inputs['saf_no'];
            //print_var($saf_no);die; 
            if($count = $this->water_saf_detail_model->chksaf_exist($saf_no))
            {
                $response = ['response'=>true, 'dd'=>$count];
            }
            else
            {
                $response = ['response'=>false];
            }
        }
        else
        {
            $response = ['response'=>false];
        } 
        if($saf_arg!=null)
            return $response;      
        
        return json_encode($response);
    }

    public function show_district_list()
    {
    	if($this->request->getMethod()=="post")
        {
           $data=array();
           $inputs = arrFilterSanitizeString($this->request->getVar());  
           $state_id=$inputs['state_id'];
           $state_list=$this->district_model->getdistrictbystateid($state_id);
           //echo ($state_list);
           // print_r($state_list);
           
           return $state_list;
        }
    }

    public function search($type='')
    {
        $data=array();
        $data['ulb_mstr_id']=$this->ulb_id;
        $data['ward_list']=$this->ward_model->getWardList($data);
        if($type==md5(2))
            $data['type']=2;
        elseif($type==md5(1))
            $data['type']=1;

        $data['ward_id']='';
        $data['keyword']='';
        $data['consumer_details']=null;
        $where=1;
        if($this->request->getMethod()=='post')
        {
            if($type==md5(2))
            {
                $data['type']=2;
                $inputs = filterSanitizeStringtoUpper($this->request->getVar());
                $data['ward_id']=$inputs['ward_id'];
                $data['keyword']=$inputs['keyword'];
                $data['view']="WaterViewConsumerDetailsCitizen/index/";
                if($data['ward_id']!="" && $data['keyword']=="")
                {
                    $where=" ward_mstr_id=".$data['ward_id'];
                }
                if($data['keyword']!="" && $data['ward_id']=="")
                {
                    $where=" owner_name like '%".$data['keyword']."%' or mobile_no like '%".$data['keyword']."%' or consumer_no like '%".$data['keyword']."%'";
                }
                if($data['ward_id']!="" and $data['keyword']!="")
                {

                    $where="ward_mstr_id=".$data['ward_id']." and (owner_name like '%".$data['keyword']."%' or mobile_no like '%".$data['keyword']."%' or consumer_no like '%".$data['keyword']."%')";

                }
                $data['consumer_details']=$this->model_view_water_consumer->waterConsumerLists($where);
                //return view('citizen/water/search_consumer_lists',$data);
            }
            elseif($type==md5(1))
            {
                $data['type']=1;
                $inputs = filterSanitizeStringtoUpper($this->request->getVar());
                $data['ward_id']=$inputs['ward_id'];
                $data['keyword']=$inputs['keyword'];
                $data['view']="WaterApplyNewConnectionCitizen/water_connection_view/";
                if($data['ward_id']!="" && $data['keyword']=="")
                {
                    $where=" and wc.ward_id=".$data['ward_id'];
                }
                if($data['keyword']!="" && $data['ward_id']=="")
                {
                    $where=" and o.owner_name like '%".$data['keyword']."%' or o.mobile_no like '%".$data['keyword']."%' or wc.application_no like '%".$data['keyword']."%'";
                }
                if($data['ward_id']!="" and $data['keyword']!="")
                {

                    $where=" and wc.ward_id=".$data['ward_id']." and (o.owner_name like '%".$data['keyword']."%' or o.mobile_no like '%".$data['keyword']."%' or wc.application_no like '%".$data['keyword']."%')";

                }
                $sql =" with owner as (
                            select distinct(apply_connection_id) as connection_id,
                            STRING_AGG ( ad.applicant_name,' ,') as owner_name,
                            string_agg(mobile_no::text,',') as mobile_no,
                            string_agg(father_name::text,',') as father_name
                            from tbl_applicant_details ad 
                            where status=1
                            group by apply_connection_id
                        )
                        select wc.id as id,wc.application_no as consumer_no,wc.category,
                            w.ward_no,
                            o.owner_name as owner_name,o.mobile_no as mobile_no,o.father_name as father_name
                        from tbl_apply_water_connection wc 
                        left join owner o on o.connection_id = wc.id
                        join view_ward_mstr w on w.id = wc.ward_id
                        where 1=1 $where ";
                $data['consumer_details']=$this->apply_wtrconn_model->getDataRowQuery($sql);
                //return view('citizen/water/search_consumer_lists',$data);
                //print_var($sql);
                //print_var($data['consumer_details']);
            }

        }
        return view("citizen/water/search",$data);
    }



    
  
    
}
