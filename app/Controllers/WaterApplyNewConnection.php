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
use App\Models\model_water_sms_log;
use App\Models\WaterSiteInspectionModel;
use App\Models\WaterViewConnectionFeeModel;
use App\Models\Water_Transaction_Model;
use App\Models\WaterRoadAppartmentFeeModel;
use App\Models\WaterPenaltyInstallmentModel;
use App\Models\WaterViewPropertyDetailModel;
use App\Models\WaterViewSAFDetailModel;
use App\Models\water_level_pending_model;
use App\Models\WaterConnectionChargeModel;
use Exception;


class WaterApplyNewConnection extends AlphaController
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
    protected $water_saf_detail_model;
    protected $water_level_pending_model;
    protected $model_water_sms_log;
    protected $conn_charge_model;
    
    
    public function __construct()
    {
        $session=session();
        $this->get_ulb_detail=$session->get('ulb_dtl');
        //print_var($get_ulb_detail);

      	$this->ulb_id=$this->get_ulb_detail['ulb_mstr_id'];
        

        $get_emp_details=$session->get('emp_details');
        $this->emp_id=$get_emp_details['id'];
        $this->user_type=$get_emp_details['user_type_mstr_id'];
        
        
        parent::__construct();
        helper(['db_helper', 'form','form_helper','sms_helper']);
        helper(['db_helper', 'qr_code_generator_helper']);
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
        $this->water_level_pending_model = new water_level_pending_model($this->db);

        $this->water_sms_log = new model_water_sms_log($this->db);
        $this->conn_charge_model=new WaterConnectionChargeModel($this->db);

    }

    function __destruct()
	{
		if (!empty($this->db)) $this->db->close();
		if (!empty($this->property_db)) $this->property_db->close();
		if (!empty($this->dbSystem)) $this->dbSystem->close();
	}
    
    public function index()
    {
        $data=array();
        $data['user_type']=$this->user_type;//print_var($data['user_type']);
        $data['property_type_list']=$this->water_property_model->property_list();
        $data['conn_type_list']=$this->conn_type_model->conn_type_list();
        $data['conn_through_list']=$this->conn_through_model->conn_through_list();
        $data['pipeline_type_list']=$this->pipeline_model->pipeline_list();
        $data['ulb_mstr_id']=$this->ulb_id;
        $data['ward_list']=$this->ward_model->getWardList($data);
        $ulb_name = (session()->get('ulb_dtl')['ulb_name']??null);
        //print_var(session()->get('ulb_dtl'));
        // echo"<pre>";print_var($data);echo"</pre>";
        
        if($this->request->getMethod()=='post')
        {
            if($this->request->getVar('id')=="")
            {
                $inputs = filterSanitizeStringtoUpper($this->request->getVar());
                //print_var($inputs);die();
                
                $data['connection_type_id']=$inputs['connection_type_id'];
                $data['conn_through_id']=$inputs['conn_through_id'];
                $data['property_type_id']=$inputs['property_type_id'];
                $data['pipeline_type_id']=$inputs['pipeline_type_id'];
                $data['category']=$inputs['category'];
                $data['ward_id']=$inputs['ward_id'];
                $data['holding_no']=$inputs['holding_no'];
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
                $data['landmark']=$inputs['landmark'];
                $data['pin']=$inputs['pin'];
              	
                // $data['holding_exists']=$inputs['holding_exists'];
                $data['saf_id']=$inputs['saf_id'];
                $data['prop_id']=$inputs['prop_id'];
                $data['count']=$inputs['count'] ?? 1;
                $data['owner_type']=$inputs['owner_type'];
                $data['saf_no']=$inputs['saf_no'];
                $data['owner_name']=$inputs['owner_name'];
                $data['mobile_no']=$inputs['mobile_no'];
               
                $data['guardian_name']=$inputs['guardian_name'];
                $data['email_id']=$inputs['email_id'];
                
              /*  if($data['conn_through_id']==1)
                {
                    $exists_prop=$this->water_prop_detail_model->chkholding_exist($data['holding_no']);
                }
                else if($data['conn_through_id']==5)
                {
                    $exists_saf=$this->water_saf_detail_model->chksaf_exist($data['saf_no']);
                }
                */


                // checking if exists water connection for the holding as owner because for owner only one water connection is applied but for tenant multiples
                if($data['saf_id']>0)    
                {
                    $count_saf=$this->apply_wtrconn_model->check_saf_exists($data['saf_id']);
                    if($count_saf>0)
                    {
                        flashToast("message", "Water Connection Already applied with this SAF No.");
                        return view("water/water_connection/applywaterconnection", $data);
                    }
                }
            


                if($data['prop_id']>0)
                {
                    $count_owner_prop=$this->apply_wtrconn_model->check_owner_holding_water_conn($data['prop_id']);
                    //print_r($count_owner_prop);
                    

                    $count_prop=$count_owner_prop['count_prop'];
                    // if($count_prop>0 and $data['owner_type']=='OWNER') 
                    // in case multiple connection apply from same holding owner can apply only once but for now only one connection can be given on one holding so 
                    
                    if($count_prop>0)
                    {
                        flashToast("message", "Owner Already has Water Connection");
                        return view("water/water_connection/applywaterconnection", $data);
                    }
                }
            
        		

                /*if($inputs['owner_name'])
                {
                    for($i=0;$i<sizeof($inputs['owner_name']);$i++)
                    {
                        $data['owner_name']=$inputs['owner_name'][$i];
                        $data['guardian_name']=$inputs['guardian_name'][$i];
                        $data['mobile_no']=$inputs['mobile_no'][$i];
                        $data['email_id']=$inputs['email_id'][$i];
                      
                        
                    }

                }*/

                
             /* $count_prop=$this->apply_wtrconn_model->check_saf_exists($data['prop_id']);

              $count_prop=$count_prop['count_prop'];

                if($count_prop==0)
                {

                      $_SESSION['msg']="Holding Already Exists";
                      return view("water/water_connection/applywaterconnection",$data);


                }
              $count_saf=$this->apply_wtrconn_model->check_saf_exists($data['saf_id']);
                          
              $count_saf=$count_saf['count_saf'];


                if($count_saf==0)
                {

                    $_SESSION['msg']="SAF Already Exists";
                    return view("water/water_connection/applywaterconnection",$data);

                }
                */


                $rules=[
                    'connection_type_id'=>'required|numeric',
                    'conn_through_id' =>'required|numeric',
                    'property_type_id' =>'required|numeric',
                    'ward_id' =>'required|numeric',
                    'area_in_sqft' =>'required|numeric',
                    'address' =>'required',
                    'landmark' =>'required|alpha_space',
                    'pin' =>'required|min_length[6]|max_length[6]|numeric',
                ];
                $alphaNumericSpacesDotDash = '/^[a-z0-9 .\-]+$/i';
                $alphaNumericSpacesDotDashForMultipleOwner = '/^[a-z0-9, .\-]+$/i';
                $rules=[                   
                    
                    'connection_type_id'=>'required|integer',
                    'conn_through_id' =>'required|integer',
                    'property_type_id' =>'required|integer',                    
                    'ward_id' =>'required|integer',                    
                    'area_in_sqft' =>'required|numeric',
                    // 'address' =>"required|regex_match[/^[a-z0-9\, .\-]+$/i]",
                    'landmark' =>'required',
                    'pin' =>'required|min_length[6]|max_length[6]|numeric', 
                    "owner_type"=>"required|in_list[OWNER,TENANT]",
                    "ward_id"=>"required|integer",
                    "area_in_sqft"=>"required|numeric",
                    "landmark"=>"required|regex_match[/^[a-z0-9\, .\-]+$/i]",
                    "owner_name.*"=>"required|regex_match[$alphaNumericSpacesDotDashForMultipleOwner]",                    
                    "mobile_no.*"=>"required|regex_match[/^[0-9]+$/i]|exact_length[10]",  
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
                if(!in_array($data['conn_through_id'],[1,2]) && $inputs["elect_acc_no"]!="")
                {
                    $rules["elect_acc_no"]="regex_match[$alphaNumericSpacesDotDash]";
                }
                if(!in_array($data['conn_through_id'],[1,2]) && $inputs["elect_cons_category"]!="")
                {
                    $rules["elect_cons_category"]="in_list[Residential - DS I/II,Commercial - NDS II/III,Agriculture - IS I/II,Low Tension - LTS,High Tension - HTS]";
                }
                if(!in_array($data['conn_through_id'],[1,2]) && $inputs["elect_bind_book_no"]!="")
                {
                    $rules["elect_bind_book_no"]="regex_match[$alphaNumericSpacesDotDash]";
                }
                if(!in_array($data['conn_through_id'],[1,2]) && $inputs["elec_k_no"]!="")
                {
                    $rules["elec_k_no"]="regex_match[/^[0-9]+$/i]";
                }
                if(!in_array($data['conn_through_id'],[1,2]))
                {
                    $rules["address"]="required|regex_match[/^[a-z0-9\, .\-]+$/i]";
                }
                //'holding_exists' =>'trim|required|alpha',
               /*if($inputs['email_id'])
                {
                    
                	for($i=0;$i<sizeof($inputs['email_id']);$i++)
                    {
                        
                      //  echo "---".$inputs['email_id'][$i];
                        
                    	if($inputs['email_id'][$i]!="")
                    	{
                            
                            //echo $inputs['email_id'][$i];

                    		$s1=["email_id[]"=>'valid_email'];

                           //print_r($s1);

	                		$rules=array_merge($rules,$s1);
                           // print_r($rules); 
	                		break;

                    	}
	                	

                	}
                }*/
                
                
                
                if(!$this->validate($rules))
                {
                    $data['validation']=$this->validator;
                    return view('water/water_connection/applywaterconnection', $data);
                }
                else
                {
                    $apply_water_conn=array();
                    if($inputs['flat_count']!="")
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
                    $apply_water_conn['apply_from']='JSK';
                    
                    
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
                    //$apply_water_conn['elec_category']=$data['elec_category'];
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

                    
                    // $apply_water_conn['elec_k_no']=$data['elec_k_no'];
                    // $apply_water_conn['elec_bind_book_no']=$data['elec_bind_book_no'];
                    // $apply_water_conn['elec_account_no']=$data['elec_account_no'];
                    // $apply_water_conn['elec_category']=$data['elec_category'];
                    
                    
                    

                        if($data['category']=="BPL")
                        {
                            $conn_fee=0;
                        }
                        else
                        {
                            $where = NULL;
                            if(in_array($data['property_type_id'],[1,7]))
                            {
                                $where=" and  (".$data['area_in_sqft'].">=area_from_sqft and ".$data['area_in_sqft']."<=area_upto_sqft)";
                            }

                            $get_rate_dtls=$this->apply_wtrconn_model->getNewRateId($data['property_type_id'], $where);
                            $rate_id=$get_rate_dtls['id'];
                            // print_r($get_rate_dtls);
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

                        //echo"<pre>";print_r($apply_water_conn);echo"</pre>";die();
                        $insert_id=$this->apply_wtrconn_model->insertData($apply_water_conn);



                        $conn_fee_charge=array();
                        $conn_fee_charge['apply_connection_id']=$insert_id;
                        $conn_fee_charge['charge_for']='New Connection';
                        $conn_fee_charge['conn_fee']=$conn_fee;
                        $conn_fee_charge['created_on']=date('Y-m-d H:i:s');
                        
                        // penalty 4000 for residential 10000 for commercial in regularization effective from 
                        // 01-01-2021 and half the amount is applied for connection who applied under 6 months from 01-01-2021 

                        $effective_date=date('2021-01-01');
                        $six_months_after=date('Y-m-d', strtotime($effective_date." + 6 months"));

                        
                        //echo $data['connection_type_id'];
                        $penalty=0;
                        if($data['connection_type_id']==2) // Regularization
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
                            if($data['connection_type_id']==2) // Regularization
                            {
                                $payment_status=0;
                            }
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

                            for($i=0; $i < sizeof($inputs['owner_name']); $i++)
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
                        }
                        //-------------------------------- sms send -----------------------------------------//
                        $appliction = $this->apply_wtrconn_model->getData(md5($insert_id));
                        $owners = $this->apply_wtrconn_model->water_owner_details(md5($insert_id));
                        $sms = Water(['application_no'=>$appliction['application_no'],'ulb_name'=>$ulb_name],'Apply Application');
                        if($sms['status']==true)
                        {
                            foreach ($owners as $val )
                            {
                                $mobile = '';
                                $mobile=$val['mobile_no'];
                                $message=$sms['sms']; 
                                $templateid=$sms['temp_id'];
                                $sms_log_data = ['emp_id'=>$this->emp_id,
                                                'ref_id'=>$insert_id,
                                                'ref_type'=>'tbl_apply_water_connection',
                                                'mobile_no'=>$mobile,
                                                'purpose'=>'Apply Application',
                                                'template_id'=>$templateid,
                                                'message'=>$message
                                ];
                                $sms_id =  $this->water_sms_log->insert_sms_log( $sms_log_data);
                                $s = send_sms($mobile,$message, $templateid);
                                
                                if($s)
                                {
                                    $update_sms_log = ['response'=>$s['response'],'smgid'=>$s['msg']];
                                    $up = $this->water_sms_log->update_sms_log(['id'=>$sms_id],$update_sms_log); 
                                
                                } 

                            } 
                        }  
                             
                                               
                        
                        // $sms="Your Application No. for Water Connection request is ".$app_no.". ".$this->get_ulb_detail['ulb_name'];
                        // SMSJHGOVT($owner_arr['mobile_no'],$sms);
                        return $this->response->redirect(base_url('WaterApplyNewConnection/water_connection_view/'.md5($insert_id)));
                    }
                    else
                    {
                        return $this->response->redirect(base_url('WaterApplyNewConnection'));
                    }
                }
            }
        }
        return view('water/water_connection/applywaterconnection', $data);
        
    }

    public function water_connection_view($insert_id)
    {
        $data['application_status']=$this->apply_wtrconn_model->application_status($insert_id);
        $data['consumer_details']=$this->apply_wtrconn_model->water_conn_details($insert_id);
        $data['owner_details']=$this->apply_wtrconn_model->water_owner_details($insert_id);
        $data['site_inspection_details']=$this->site_ins_model->getAllRecords($data['consumer_details']["id"]);
        $data['transaction']=$this->trans_model->get_all_transactions($insert_id);
        
        $data['dues']= $this->conn_charge_model->due_exists($insert_id);

        $data['level']=$this->water_level_pending_model->getAllRecords($data["consumer_details"]["id"]);
        //print_var($data['consumer_details']);
        $data['user_type']=(int)$this->user_type;
        $data['pay']=false;
        if(($data['consumer_details']['connection_type_id']==2 && $data['consumer_details']['payment_status']==1) || !empty($data['dues']) )
        { 
            $penalty=$this->penalty_installment_model->getUnpaidInstallmentSum($insert_id);
            if($penalty>0 || !empty($data['dues']))
            {
                $data['pay']=true;
            }
        }
        return view('water/water_connection/water_connection_details_view', $data);
    }

    public function water_connectionView($insert_id)
    {
        $data['user_type']=$this->user_type;
        $data['consumer_details']=$this->apply_wtrconn_model->water_conn_details($insert_id);
        $data['site_inspection_details']=$this->site_ins_model->getSiteInspectionDetailsbyJE($insert_id);
        $data['owner_details']=$this->apply_wtrconn_model->water_owner_details($insert_id);
        
        $data['water_conn_id']=$insert_id;
        //  print_r($data['owner_details']);
        $data['dues']=$this->conn_fee_model->conn_fee_charge($insert_id);
        $data['transaction_count']=$this->trans_model->getTransCountbyApplicationId($insert_id);
        $data['application_status']=$this->application_status($insert_id);
        return view('mobile/water/waterConnectionView', $data);

    }

     
    public function application_status($insert_id)
    {
        // $get_application_status=$this->apply_wtrconn_model->water_application_status($insert_id);
        // $get_level_pending_dtls=$this->apply_wtrconn_model->level_pending_details($insert_id);
        // //print_r($get_level_pending_dtls);
        // $count_site_inspec_diff_pay=$this->site_ins_model->application_site_inspection_payment_detls($insert_id);

        // //print_r($site_inspec_pay);

        // $app_status=$get_application_status['status'];
        // $doc_status=$get_application_status['doc_status'];
        // $payment_status=$get_application_status['payment_status'];
        // $status='';

        // /*

        // if($doc_status==0 and $payment_status==0)
        // {
        //     $status="Document Not Uploaded and Payment Not Done";
        // }
        // else if($doc_status==0 and $payment_status==2)
        // {
        //     $status="Document Not Uploaded and  Payment Done but not cleared";
        // }
        // else if($doc_status!=0 and $payment_status==0)
        // {
        //     $status="Document Uploaded but Payment Not Done";
        // }
        // else if(($doc_status==1 or $doc_status==0) and $payment_status==2)
        // {
        //     $status="Document Uploaded and Payment Done but Not Cleared";
        // }

        // */
        // if($app_status==0)
        // {
        //     $status="Application Is Deactivated";
        // }

        // if($doc_status==0 && $app_status!=0)
        // {
        //     if($payment_status==0)
        //     {
        //         $status="Document Not Uploaded and Payment Not Done";
        //     }
        //     else if($payment_status==1)
        //     {
        //         $status="Document Not Uploaded but Payment Done";
        //     }
        //     else if($payment_status==2)
        //     {
        //         $status="Document Not Uploaded but Payment Done but Cheque not cleared";
        //     }
        // }
        // else if($doc_status==1 and $payment_status!=1 && $app_status!=0)
        // {
        //     if($payment_status==0)
        //     {
        //         $status="Document Uploaded but Payment Not Done";
        //     }
        //     else if($payment_status==2)
        //     {
        //         $status="Document Uploaded and Payment Done but Cheque not cleared";
        //     }
        // }
        // if($doc_status==1 and $payment_status==1 && $app_status!=0)
        // {	 	
        //     if($get_level_pending_dtls)
        //     {  
        //         $receiver_id=$get_level_pending_dtls['receiver_user_type_id'];
        //         $verification_status=$get_level_pending_dtls['verification_status'];
        //        /* $receiver_id=$get_level_pending_dtls['user_type'];
        //         if($get_level_pending_dtls['verification_status']==0)
        //         {
        //             $status="Pending at ".$receiver_id;
        //         }
        //         else if($get_level_pending_dtls['verification_status']==2 and $get_level_pending_dtls['receiver_user_type_id']==12)
        //         {
        //             $status="Sent Back to Citizen by ".$receiver_id;
        //         }*/
        //         if($verification_status==0)
        //         {	
        //             if($receiver_id==12)
        //             {
        //                 $status="Pending at Dealing Officer";
        //             }
        //             else if($receiver_id==13)
        //             {
        //                 if($count_site_inspec_diff_pay>0)
        //                 {
        //                     $status="Payment Pending of Diff Amount at Site Inspection";
        //                 }
        //                 else
        //                 {
        //                     $status="Pending at Junior Engineer";
        //                 }
        //             }
        //             else if($receiver_id==14)
        //             {
        //                 $status="Pending at Section Head";
        //             }
        //             else if($receiver_id==15)
        //             {
        //                 $status="Pending at Assistant Engineer";
        //             }
        //             else if($receiver_id==16)
        //             {
        //                 $status="Pending at Executive Officer";
        //             }
        //         }
        //         else if($verification_status==2 and $receiver_id==12)
        //         {
        //             $status="Sent Back to Citizen by Dealing Officer";
        //         }
        //         else if($verification_status==2 and $receiver_id==13)
        //         {
        //             $status="Sent Back to Citizen by Junior Engineer";
        //         }
        //         else if($verification_status==2 and $receiver_id==14)
        //         {
        //             $status="Sent Back to Citizen by Section Head";
        //         }
        //         else if($verification_status==2 and $receiver_id==15)
        //         {
        //             $status="Sent Back to Citizen by Assistant Engineer";
        //         }
        //         else if($verification_status==2 and $receiver_id==16)
        //         {
        //             $status="Sent Back to Citizen by Executive Officer";
        //         }

        //         else if($verification_status==4 and $receiver_id==12)
        //         {
        //             $status="Application Rejected by Dealing Officer";
        //         }
        //         else if($verification_status==4 and $receiver_id==13)
        //         {
        //             $status="Application Rejected by Junior Engineer";
        //         }
        //         else if($verification_status==4 and $receiver_id==14)
        //         {
        //             $status="Application Rejected by Section Head";
        //         }
        //         else if($verification_status==4 and $receiver_id==15)
        //         {
        //             $status="Application Rejected by Assistant Engineer";
        //         }
        //         else if($verification_status==4 and $receiver_id==16)
        //         {
        //             $status="Application Rejected by Executive Officer";
        //         }

        //         else if($verification_status==1 and $receiver_id==16)
        //         {
        //             $status="Approved by Executive Officer";
        //         }
        //     }
        //     else
        //     {
        //         $status='Payment Is Don And Document Uploaded';
        //     }
        // }
        // return $status;
        return $this->apply_wtrconn_model->application_status($insert_id);
    }
 
    public function validate_holding_no()
    {
        //print_var($this->request->getMethod());
        if($this->request->getMethod()=="post")
        {
            $data=array();
            $inputs = arrFilterSanitizeString($this->request->getVar());  
            $data['ward_id']=$inputs['ward_id'];
            $data['holding_no']=$inputs['holding_no'];
            
            $count = $this->property_model->chkholding_exist($data);
            
            //print_var($count);
            if ($count = $this->property_model->chkholding_exist($data))
            {



                $response = ['response'=>true, 'dd'=>$count];

            } else {
                $response = ['response'=>false];
            }
        } else {
            $response = ['response'=>false];
        }
        return json_encode($response);
    }

    public function checkHoldingExists()
    {
        //print_var($this->request->getMethod());
        if($this->request->getMethod()=="post")
        {
            $data=array();
            $inputs = arrFilterSanitizeString($this->request->getVar());  
            $data['ward_id']=$inputs['ward_id'];
            $data['holding_no']=$inputs['holding_no'];
            
            //$count = $this->water_prop_detail_model->checkNeHoldingExists($data['ward_id'],$data['holding_no']);
            
            //print_var($count);
            if ($count = $this->water_prop_detail_model->checkNeHoldingExists($data['ward_id'],$data['holding_no']))
            {



                $response = ['response'=>true, 'dd'=>$count];

            } else {
                $response = ['response'=>false,'dd'=>['message'=>'Holding Not Found']];
            }
        } 
        else 
        {
            $response = ['response'=>false,'message'=>'Only Post allowed'];
        }
        return json_encode($response);
    }

    public function validate_saf_no()
    {

     
        if($this->request->getMethod()=="post")
        {

           $data=array();
           $inputs = arrFilterSanitizeString($this->request->getVar());  
           $data['ward_id']=$inputs['ward_id'];
           $data['saf_no']=$inputs['saf_no'];

           $count = $this->property_model->chksaf_exist($data);
          // print_r($count);

          // return $count;

            if ($count = $this->property_model->chksaf_exist($data)){


              //  $response = ['response'=>true, 'data'=>$count['id'], 'owner_name'=>$count['owner_name'], 'mobile_no' => $count['mobile_no'],'guardian_name'=>$count['guardian_name'],'email'=>$count['email'],'saf_id'=> $count['id'],'payment_status'=> $count['payment_status'],'prop_dtl_id'=>$count['prop_dtl_id'] ];


                 $response = ['response'=>true, 'dd'=>$count];

            } else {
                $response = ['response'=>false];
            }
        } else {
            $response = ['response'=>false];
        }
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

    public function view_memo($apply_conn_id_md5)
    {
        $data = array();
        $data['ulb']=session()->get('ulb_dtl');
        $sql =" with level_pending as(
                    select * from tbl_level_pending 
                    where receiver_user_type_id = 16 and verification_status=1 
                    and md5(apply_connection_id::text) = '$apply_conn_id_md5'
                ),
                owner as (
                    select distinct(apply_connection_id) as apply_connection_id,
                    string_agg( applicant_name,' , ')as ownere_name,
                        string_agg( father_name,' , ')as father_name,
                        string_agg(cast( mobile_no as varchar(10)),' , ')as mobile_no
                    from tbl_applicant_details 
                    where md5(apply_connection_id::text) = '$apply_conn_id_md5' 
                        --and status !=0
                    group by apply_connection_id
                ),
                transaction as (
                    select  distinct(related_id) as related_id,
                        string_agg( transaction_no::text,' , ')as transaction_no,
                        string_agg( transaction_date::text,' , ')as transaction_date,
                        sum(case when transaction_type='New Connection' then total_amount else 0 end) as conn_fee,
                        sum(case when transaction_type='Site Inspection' then total_amount else 0 end) as extra_charge,
                        sum(total_amount) as total
                    from tbl_transaction 
                    where md5(related_id::text) = '$apply_conn_id_md5' 
                    and transaction_type in ('New Connection','Site Inspection')
                    group by related_id
                ),
				charge as (
					select distinct(ap.id) as connection_id,
						sum(ch.conn_fee) as total_charge,
						sum(ch.penalty) as penalty,
						sum(case when charge_for ='New Connection' then conn_fee else 0 end )as conn_fee,
						sum(case when charge_for ='Site Inspection' then conn_fee else 0 end) as Site_Inspection                        
					from tbl_apply_water_connection ap
					join tbl_connection_charge ch on ch.apply_connection_id=ap.id 
						and ch.status=1
					where md5(ap.id::text)='$apply_conn_id_md5'  
					group by ap.id
				),
                je as(
                    select *
                    from view_site_inspection_details 
                    where id = (select max(id) 
                                 from view_site_inspection_details 
                              where  verified_by='JuniorEngineer' and status=1 
                                and md5(apply_connection_id::text) = '$apply_conn_id_md5'
                              )
                ),
                ae as(
                    select *
                    from view_site_inspection_details 
                    where id = (select max(id) 
                                 from view_site_inspection_details 
                              where  verified_by='AssistantEngineer' and status=1 
                                and md5(apply_connection_id::text) = '$apply_conn_id_md5'
                              )
                )
                
                select ap.id,ap.application_no,ap.apply_date,date_part('year',ap.apply_date) as year,ap.apply_date,
                    ap.address,ap.holding_no,ap.apply_from,ap.category,
                    tbl_consumer.consumer_no,
                    o.ownere_name,o.father_name,o.mobile_no,
                    w.ward_no,l.send_date as recieved_date,l.forward_date as verify_date,
                    t.transaction_no,t.transaction_date,
                    t.conn_fee,t.extra_charge,t.total as total_diposit,
                    ch.conn_fee,ch.penalty,ch.total_charge,ch.Site_Inspection,
                    p.pipeline_type,
                    case when ae.area_sqmt notnull then ae.area_sqmt 
                        When je.area_sqmt notnull then je.area_sqmt
                        else ap.area_sqmt end as area_sqmt,
                    case when ae.pipeline_size_type notnull then ae.pipeline_size_type 
                        When je.pipeline_size_type notnull then je.pipeline_size_type
                        else 'N/A' end as pipeline_size_type,
                    case when ae.pipe_type notnull then ae.pipe_type 
                        When je.pipe_type notnull then je.pipe_type
                        else 'N/A' end as pipe_type,
                    case when ae.pipeline_size notnull then ae.pipeline_size 
                        When je.pipeline_size notnull then je.pipeline_size
                        else 'N/A' end as pipeline_size,
                    case when ae.ferrule_type notnull then ae.ferrule_type 
                        When je.ferrule_type notnull then je.ferrule_type
                        else 'N/A' end as ferrule_type,    
                    case when ae.pipe_size notnull then ae.pipe_size 
                        When je.pipe_size notnull then je.pipe_size
                        else 'N/A' end as pipe_size,
                    case when ae.road_type notnull then ae.road_type 
                        When je.road_type notnull then je.road_type
                        else 'N/A' end as road_type,
                    je.emp_details_id as emp_details_id,
                    je.ts_map as je_ts_map,
                    'Junior Engineer' as user_type,
                    case when l.emp_details_id notnull then l.emp_details_id 
                        When l.receiver_user_id notnull then l.receiver_user_id
                        else null end as eo_id
                from tbl_apply_water_connection ap
                join tbl_consumer on tbl_consumer.apply_connection_id = ap.id
                join owner o on o.apply_connection_id = ap.id
                join level_pending l on l.apply_connection_id = ap.id
                join view_ward_mstr w on w.id = ap.ward_id
                left join transaction t on t.related_id = ap.id
                left join charge ch on ch.connection_id=ap.id 
                join tbl_pipeline_type_mstr p on p.id = ap.pipeline_type_id
                left join ae on ae.apply_connection_id = ap.id     
                left join je on je.apply_connection_id = ap.id 
                where md5(ap.id::text)='$apply_conn_id_md5'        
        "; 
        $data['data']= $this->apply_wtrconn_model->getDataRowQuery($sql);
        // $data['eo_signatur']=base_url('public/assets/img/water/'.$data['ulb']['city'].'/signetur/1411.png');
        $data['eo_signatur']=base_url('public/assets/img/water/'.$data['ulb']['city'].'/signetur/1558.png');
        $data['je_signatur']=base_url('public/assets/img/water/'.$data['ulb']['city'].'/signetur/1399.png');
        $data['je_ts_map']=base_url('public/assets/img/water/'.$data['ulb']['city'].'/Map-4.png');
        if(sizeof($data['data'])>0)
        {
            $data['data']=$data['data'][0];  

            if(!empty($data['data']['eo_id']))
            {                
                $eo_signatur = APPPATH.'../public/assets/img/water/'.$data['ulb']['city'].'//signetur/'.$data['data']['eo_id'].'.png';
                if(file_exists($eo_signatur))
                    $data['eo_signatur'] = base_url('public/assets/img/water/'.$data['ulb']['city'].'//signetur/'.$data['data']['eo_id'].'.png');

                $empDtl = $this->dbSystem->query("select * from tbl_emp_details where id=".$data['data']['eo_id'])->getFirstRow("array");  
                $data["eo_signatur"]= $empDtl && $empDtl["signature_path"]? base_url("/getImageLink.php?path=/emp_signature/".$empDtl["signature_path"]):$data["eo_signatur"] ;
            } 
            if(!empty($data['data']['emp_details_id']))
            {                
                $je_signatur = APPPATH.'../public/assets/img/water/'.$data['ulb']['city'].'//signetur/'.$data['data']['emp_details_id'].'.png';
                if(file_exists($je_signatur))                
                    $data['je_signatur']= base_url('public/assets/img/water/'.$data['ulb']['city'].'//signetur/'.$data['data']['emp_details_id'].'.png');

                $empDtl = $this->dbSystem->query("select * from tbl_emp_details where id=".$data['data']['emp_details_id'])->getFirstRow("array");  
                $data["je_signatur"]= $empDtl && $empDtl["signature_path"]? base_url("/getImageLink.php?path=/emp_signature/".$empDtl["signature_path"]):$data["je_signatur"] ;
            }
            if(!empty($data['data']['je_ts_map']))
            {                
                $je_ts_map = APPPATH.'../public/assets/img/water/'.$data['ulb']['city'].'/'.$data['data']['je_ts_map'];
                if(file_exists($je_ts_map))                
                    $data['je_ts_map']=base_url('public/assets/img/water/'.$data['ulb']['city'].'/'.$data['data']['je_ts_map']);
            }           
        }
        // if($this->user_type==1)
        // {
        //     // print_var($sql);
        // }
        // print_var($data['ulb']);die;
        $path=base_url('citizenPaymentReceipt/view_memo/'.$apply_conn_id_md5."/".$data['ulb']['ulb_mstr_id']);
        $data['ss']=qrCodeGeneratorFun($path);
        $data['user_type']=$this->user_type;
        return view('water/water_connection/memo',$data);
    }

    public function updat_application($apply_conn_id_md5)
    {
        $data['conn_type_list']=$this->conn_type_model->conn_type_list();
        $data['user_type']=$this->user_type;//print_var($data['user_type']);
        $data['property_type_list']=$this->water_property_model->property_list();
        $data['conn_type_list']=$this->conn_type_model->conn_type_list();
        $data['conn_through_list']=$this->conn_through_model->conn_through_list();
        $data['pipeline_type_list']=$this->pipeline_model->pipeline_list();
        $data['ulb_mstr_id']=$this->ulb_id;
        $data['ward_list']=$this->ward_model->getWardList($data);
        $ulb_name = (session()->get('ulb_dtl')['ulb_name']??null);

        $data['user_type']=$this->user_type;
        $data['consumer_details']=$this->apply_wtrconn_model->water_conn_details($apply_conn_id_md5);
        //print_var($data['user_type']);
        if(!empty($data['consumer_details']))
        {
            $apply_conn_id = $data['consumer_details']['id'];
            $data['connection_type_id']=$data['consumer_details']['connection_type_id'];
            $data['conn_through_id']=$data['consumer_details']['connection_through_id'];
            $data['property_type_id']=$data['consumer_details']['property_type_id'];
            $data['flat_count']=$data['consumer_details']['flat_count'];
            $data['category']=$data['consumer_details']['category'];

            $data['pipeline_type_id']=$data['consumer_details']['pipeline_type_id'];
            $data['holding_no']=$data['consumer_details']['holding_no'];
            $data['prop_id']=$data['consumer_details']['prop_dtl_id'];
            $data['saf_no']=$data['consumer_details']['saf_no'];
            $data['saf_id']=$data['consumer_details']['saf_dtl_id'];

            
            $data['ward_id']=$data['consumer_details']['ward_id'];
            $data['area_in_sqft']=$data['consumer_details']['area_sqft'];
            $data['area_in_sqmt']=$data['consumer_details']['area_sqmt'];
            $data['address']=$data['consumer_details']['address'];
            $data['landmark']=$data['consumer_details']['landmark'];
            $data['pin']=$data['consumer_details']['pin'];

            $data['k_no']=$data['consumer_details']['elec_k_no'];
			$data['bind_book_no']=$data['consumer_details']['elec_bind_book_no'];
			$data['account_no']=$data['consumer_details']['elec_account_no'];
			$data['electric_category_type']=$data['consumer_details']['elec_category'];            
			$data['apply_from']=$data['consumer_details']['apply_from'];           

            //$data['site_inspection_details']=$this->site_ins_model->getSiteInspectionDetailsbyJE($apply_conn_id_md5);
            $data['owner_name']=$this->apply_wtrconn_model->water_owner_details($apply_conn_id_md5);   
            //print_var($data['owner_name']);     
            $data['water_conn_id']=$apply_conn_id_md5;          

            $data['dues']=$this->conn_fee_model->conn_fee_charge($apply_conn_id_md5);
            $data['transaction_count']=$this->trans_model->getTransCountbyApplicationId($apply_conn_id_md5);
            $data['application_status']=$this->application_status($apply_conn_id_md5);
            if($this->request->getMethod()=='post')
			{
				$this->db->transBegin();
				try
				{

					$inputs=arrFilterSanitizeString($this->request->getVar());
					//print_var($inputs);
                    if(isset($inputs['appliction_meta_box']) && $inputs['appliction_meta_box']=='on')
                    {
                        $data['appliction_meta_box']='on';
                        $application_details['connection_type_id']=$inputs['connection_type_id'];
						$application_details['connection_through_id']=$inputs['conn_through_id'];
						$application_details['property_type_id']=$inputs['property_type_id'];
						if($application_details['property_type_id']==7)
							$consumer_details['flat_count']=$inputs['flat_count'];
						else
						{
							$application_details['category']=(isset($inputs['category']) && !empty($inputs['category']))?$inputs['category']:$data['category'];
							$application_details['pipeline_type_id']= (isset($inputs['pipeline_type_id']) && !empty($inputs['pipeline_type_id']))?$inputs['pipeline_type_id']:$data['pipeline_type_id'];
						}
						if(isset($inputs['holding_no']) && $inputs['holding_no']!='')
						{
							$application_details['holding_no']=$inputs['holding_no'];	
							$application_details['prop_dtl_id']=$inputs['prop_id'];	
						}
						if(isset($inputs['saf_no']) && $inputs['saf_no']!='')
						{
							$application_details['saf_no']=$inputs['saf_no'];	
							$application_details['saf_dtl_id']=$inputs['saf_id'];
						}	

                        if($inputs['saf_id']>0)    
                        {
                            $count_saf=$this->apply_wtrconn_model->check_saf_exists_another($apply_conn_id,$inputs['saf_no']);
                            if($count_saf>0)
                            {
                                flashToast("message", "Water Connection Already applied with this SAF No.");
                                return view("Water/water_connection/updat_application", $data);
                            }
                        }


                        if($inputs['prop_id']>0)
                        {
                            $count_owner_prop=$this->apply_wtrconn_model->check_holding_exists_another($apply_conn_id,$inputs['holding_no']);
                            //print_r($count_owner_prop);
                            $count_prop=$count_owner_prop['count_prop'];
                            // in case multiple connection apply from same holding owner can apply only once but for now only one connection can be given on one holding so                     
                            if($count_prop>0) {
                                flashToast("message", "Owner Already has Water Connection");
                                return view("Water/water_connection/updat_application", $data);
                            }
                        }

                                            
                        $chek = $this->apply_wtrconn_model->update_application($apply_conn_id,$application_details);		
                        			
						if(!$chek)
						{
							throw new Exception("Some Error Occurst Due to Application updatetion 1 ");
						}
						
                    }
					if(isset($inputs['consumer_check_box']) && $inputs['consumer_check_box']=='on')
					{ 
						$data['consumer_check_box']='on';
						
						$consumer_details['ward_id']=$inputs['ward_id'];
                        if(in_array($this->user_type,[1,2]))
                        {
                            $consumer_details['area_sqft']=$inputs['area_in_sqft'];	
                            $consumer_details['area_sqmt']=$inputs['area_in_sqmt'];
                        }	
						$consumer_details['address']=$inputs['address'];
                        $consumer_details['landmark']=$inputs['landmark'];	
						$consumer_details['pin']=$inputs['pin'];
						//$consumer_details['area_sqmt']=$inputs['area_in_sqmt'];	
                        
						$chek = $this->apply_wtrconn_model->update_application($apply_conn_id,$consumer_details);						
						if(!$chek)
						{
							throw new Exception("Some Error Occurst Due to Application Address updatetion ");
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
								$owner_detals['consumer_id']=$apply_conn_id;

								$chek = $this->apply_wtrconn_model->insert_owner($owner_detals);
								if(!$chek)
								{
									throw new Exception("Some Error Occurst Due to Application Owner Insetion ");
								}
							}
							else
							{
								
								$chek = $this->apply_wtrconn_model->update_owner($owner_id,$owner_detals);
								if(!$chek)
								{
									throw new Exception("Some Error Occurst Due to Application Owner Update ");
								}
								
							}
							
						}
					}
					if(isset($inputs['elect_check_box']) && $inputs['elect_check_box']=='on')
					{
						$data['elect_check_box']='on';
						$electric_dtl['elec_k_no']=$inputs['elec_k_no'];
						$electric_dtl['elec_bind_book_no']=$inputs['elec_bind_book_no'];
						$electric_dtl['elec_account_no']=$inputs['elec_account_no'];
						$electric_dtl['elec_category']=$inputs['elec_category'];
                        // print_var($electric_dtl);
                        // die;
						$chek = $this->apply_wtrconn_model->update_application($apply_conn_id,$electric_dtl);
						if(!$chek)
						{
							throw new Exception("Some Error Occurst Due to Application Electricity Details updatetion ");
						}
						
					}
					
					if($this->db->transStatus() === FALSE)
					{
						//die("rollback");
						$this->db->transRollback();                      
						flashToast("message", "Something errordue to Update!!!");  
						return $this->response->redirect(base_url('WaterApplyNewConnection/updat_application/'.$apply_conn_id_md5));
				
					}
					else
					{ //die("commit");
						$this->db->transCommit();
						flashToast("message", "Update Consumer Successfully!!!");
						return $this->response->redirect(base_url('WaterApplyNewConnection/updat_application/'.$apply_conn_id_md5));
					}
				}
				catch(Exception $e)
				{
					$this->db->transRollback();
					flashToast("message", $e->getMessage()); 
					// print_var($e->getMessage());
					// die; 
					return $this->response->redirect(base_url('WaterApplyNewConnection/updat_application/'.$apply_conn_id_md5));
				}
			}

            return view('Water/water_connection/updat_application', $data);
        } 
        else
        {
            flashToast("massege", "Application Not Found");
			return $this->response->redirect(base_url('Dashboard/welcome'));
        } 
        
    }
  
    public function updat_application_demand($apply_conn_id_md5)
    {
        $transaction_count=$this->trans_model->getTransCountbyApplicationId($apply_conn_id_md5);        
        if($transaction_count!=0)
        {
            return redirect()->to(base_url()."/WaterUpdateApplicationNew/index/".$apply_conn_id_md5);
        }
        $data['conn_type_list']=$this->conn_type_model->conn_type_list();
        $data['user_type']=$this->user_type;//print_var($data['user_type']);
        $data['property_type_list']=$this->water_property_model->property_list();
        $data['conn_type_list']=$this->conn_type_model->conn_type_list();
        $data['conn_through_list']=$this->conn_through_model->conn_through_list();
        $data['pipeline_type_list']=$this->pipeline_model->pipeline_list();
        $data['ulb_mstr_id']=$this->ulb_id;
        $data['ward_list']=$this->ward_model->getWardList($data);
        $ulb_name = (session()->get('ulb_dtl')['ulb_name']??null);

        $data['user_type']=$this->user_type;
        $data['consumer_details']=$this->apply_wtrconn_model->water_conn_details($apply_conn_id_md5);
        // print_var($data['user_type']);
        if(!empty($data['consumer_details']))
        {
            $apply_conn_id = $data['consumer_details']['id'];
            $data['connection_type_id']=$data['consumer_details']['connection_type_id'];
            $data['conn_through_id']=$data['consumer_details']['connection_through_id'];
            $data['property_type_id']=$data['consumer_details']['property_type_id'];
            $data['flat_count']=$data['consumer_details']['flat_count'];
            $data['category']=$data['consumer_details']['category'];
            $data['owner_type']=$data['consumer_details']['owner_type'];
            $data['pipeline_type_id']=$data['consumer_details']['pipeline_type_id'];
            $data['holding_no']=$data['consumer_details']['holding_no'];
            $data['prop_id']=$data['consumer_details']['prop_dtl_id'];
            $data['saf_no']=$data['consumer_details']['saf_no'];
            $data['saf_id']=$data['consumer_details']['saf_dtl_id'];

            
            $data['ward_id']=$data['consumer_details']['ward_id'];
            $data['area_in_sqft']=$data['consumer_details']['area_sqft'];
            $data['area_in_sqmt']=$data['consumer_details']['area_sqmt'];
            $data['address']=$data['consumer_details']['address'];
            $data['landmark']=$data['consumer_details']['landmark'];
            $data['pin']=$data['consumer_details']['pin'];

            $data['k_no']=$data['consumer_details']['elec_k_no'];
			$data['bind_book_no']=$data['consumer_details']['elec_bind_book_no'];
			$data['account_no']=$data['consumer_details']['elec_account_no'];
			$data['electric_category_type']=$data['consumer_details']['elec_category'];            
			$data['apply_from']=$data['consumer_details']['apply_from'];           

            //$data['site_inspection_details']=$this->site_ins_model->getSiteInspectionDetailsbyJE($apply_conn_id_md5);
            $data['owner_name']=$this->apply_wtrconn_model->water_owner_details($apply_conn_id_md5);   
            //print_var($data['owner_name']);     
            $data['water_conn_id']=$apply_conn_id_md5;          

            $data['dues']=$this->conn_fee_model->conn_fee_charge($apply_conn_id_md5);
            $data['transaction_count']=$this->trans_model->getTransCountbyApplicationId($apply_conn_id_md5);
            $data['application_status']=$this->application_status($apply_conn_id_md5);
            if($this->request->getMethod()=='post')
			{
				$this->db->transBegin();
				try
				{

					$inputs=arrFilterSanitizeString($this->request->getVar());

                    $application_details['connection_type_id']=$inputs['connection_type_id'];
                    $application_details['connection_through_id']=$inputs['conn_through_id'];
                    $application_details['property_type_id']=$inputs['property_type_id'];
                    $application_details['owner_type']=$inputs['owner_type'];

                    $application_details['address']=$inputs['address'];
                    $application_details['landmark']=$inputs['landmark'];	
                    $application_details['pin']=$inputs['pin'];

                    $application_details['elec_k_no']=$inputs['elec_k_no'];
                    $application_details['elec_bind_book_no']=$inputs['elec_bind_book_no'];
                    $application_details['elec_account_no']=$inputs['elec_account_no'];
                    $application_details['elec_category']=$inputs['elec_category'];

                    $application_details['ward_id']=$inputs['ward_id'];                    
                    $application_details['area_sqft']=$inputs['area_in_sqft'];
                    if($inputs['area_in_sqft']!="" and is_numeric($inputs['area_in_sqft']))
                    {
                        $inputs['area_in_sqmt']=$inputs['area_in_sqft']*0.092903;
                        $application_details['area_sqmt']=$inputs['area_in_sqmt'];                    
                    }	

                    if($application_details['property_type_id']==7)
                        $application_details['flat_count']=$inputs['flat_count'];
                    else
                    {
                        $application_details['category']=(isset($inputs['category']) && !empty($inputs['category']))?$inputs['category']:$data['category'];
                        $application_details['pipeline_type_id']= (isset($inputs['pipeline_type_id']) && !empty($inputs['pipeline_type_id']))?$inputs['pipeline_type_id']:$data['pipeline_type_id'];
                    }
                    if(isset($inputs['holding_no']) && $inputs['holding_no']!='')
                    {
                        $application_details['holding_no']=$inputs['holding_no'];	
                        $application_details['prop_dtl_id']=$inputs['prop_id'];	
                    }
                    if(isset($inputs['saf_no']) && $inputs['saf_no']!='')
                    {
                        $application_details['saf_no']=$inputs['saf_no'];	
                        $application_details['saf_dtl_id']=$inputs['saf_id'];
                    }	

                    if($inputs['saf_id']>0)    
                    {
                        $count_saf=$this->apply_wtrconn_model->check_saf_exists_another($apply_conn_id,$inputs['saf_no']);
                        if($count_saf>0)
                        {
                            flashToast("message", "Water Connection Already applied with this SAF No.");
                            return view("Water/water_connection/updat_application", $data);
                        }
                    }


                    if($inputs['prop_id']>0)
                    {
                        $count_owner_prop=$this->apply_wtrconn_model->check_holding_exists_another($apply_conn_id,$inputs['holding_no']);
                        
                        $count_prop=$count_owner_prop;                        
                        if($count_prop>0) 
                        {
                            flashToast("message", "Owner Already has Water Connection");
                            return view("Water/water_connection/updat_application", $data);
                        }
                    }
                   
                    #**************************************************************

                    {
                    
                        if(isset($application_details['category']) && $application_details['category']=="BPL")
                        {
                            $conn_fee=0;
                        }
                        else
                        {
                            $where = NULL;
                            if($application_details['property_type_id']==1)
                            {
                                $where=" and  (".$application_details['area_sqft'].">=area_from_sqft and ".$application_details['area_sqft']."<=area_upto_sqft)";
                            }

                            $get_rate_dtls=$this->apply_wtrconn_model->getNewRateId($application_details['property_type_id'], $where);
                            $rate_id=$get_rate_dtls['id'];
                            //print_var($get_rate_dtls);
                            $apply_water_conn['water_fee_mstr_id']=$rate_id;
                            $application_details['water_fee_mstr_id']=$rate_id;


                            if($get_rate_dtls['calculation_type']=='Fixed')
                            {
                                $conn_fee=$get_rate_dtls['conn_fee'];
                            }
                            else
                            {
                                $conn_fee=$get_rate_dtls['conn_fee']*$data['area_in_sqft'];
                            }
                        }

                        $conn_fee_charge=array();
                        $conn_fee_charge['apply_connection_id']=$apply_conn_id;
                        $conn_fee_charge['charge_for']='New Connection';
                        $conn_fee_charge['conn_fee']=$conn_fee;
                        $conn_fee_charge['created_on']=date('Y-m-d H:i:s');
                        
                        // penalty 4000 for residential 10000 for commercial in regularization effective from 
                        // 01-01-2021 and half the amount is applied for connection who applied under 6 months from 01-01-2021 

                        $effective_date=date('2021-01-01');
                        $six_months_after=date('Y-m-d', strtotime($effective_date." + 6 months"));

                        
                        //echo $data['connection_type_id'];
                        $penalty=0;
                        if($data['connection_type_id']==2) // Regularization
                        {
                            if(date('Y-m-d')<$six_months_after and $application_details['property_type_id']==1)
                            {
                                $penalty=2000;
                            }
                            else if($application_details['property_type_id']==1 and date('Y-m-d')>=$six_months_after)
                            {
                                $penalty=4000;
                            }
                            else if($application_details['property_type_id']!=1 and date('Y-m-d')<$six_months_after)
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
                            $this->penalty_installment_model->deleteUnpaidInstallment($apply_conn_id);
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
                                $penalty_installment['apply_connection_id']=$apply_conn_id;
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
                            $this->apply_wtrconn_model->delet_conn_fee($apply_conn_id);
                            $this->apply_wtrconn_model->insert_conn_fee($conn_fee_charge);
                        }                        
                        
                        if(isset($rate_id) && $rate_id!="")
                        {
                            $payment_status=0;
                            
                        }
                        else
                        {
                            $payment_status=1;
                            if($data['connection_type_id']==2) // Regularization
                            {
                                $payment_status=0;
                            }
                        }
                        $application_details['payment_status']=$payment_status;
                    }


                    #************************************************************
                    // print_var($conn_fee_charge);	
                    //print_var($inputs);die;                   
                    $chek = $this->apply_wtrconn_model->update_application($apply_conn_id,$application_details);		
                                
                    if(!$chek)
                    {
                        throw new Exception("Some Error Occurst Due to Application updatetion 1 ");
                    }

                    $icount = count($inputs['owner_name']);

                    $this->apply_wtrconn_model->delet_owner($apply_conn_id);
                    for($i=0;$i<$icount;$i++)
                    { 
                        $owner_detals=[];
                        $owner_detals['applicant_name']=$inputs['owner_name'][$i];
                        $owner_detals['father_name']=$inputs['guardian_name'][$i];
                        $owner_detals['mobile_no']=$inputs['mobile_no'][$i];
                        $owner_detals['email_id']=$inputs['email_id'][$i];
                        $owner_detals['city']=$inputs['city'][$i];
                        $owner_detals['district']=$inputs['district'][$i];
                        $owner_detals['state']=$inputs['state'][$i];
                        $owner_id=isset($inputs['woner_id'.$i]) && !empty($inputs['woner_id'.$i])?$inputs['woner_id'.$i]:'';
                        
                        if($owner_id=="")
                        {
                            
                            $owner_detals['emp_details_id']=$this->emp_id;
                            $owner_detals['created_on']=date('Y-m-d h:i:s');
                            $owner_detals['apply_connection_id']=$apply_conn_id;

                            $chek = $this->apply_wtrconn_model->insert_owner($owner_detals);
                            if(!$chek)
                            {
                                throw new Exception("Some Error Occurst Due to Application Owner Insetion ");
                            }
                        }
                        else
                        {
                            
                            $chek = $this->apply_wtrconn_model->update_owner($owner_id,$owner_detals);
                            if(!$chek)
                            {
                                throw new Exception("Some Error Occurst Due to Application Owner Update ");
                            }
                            
                        }
                        
                    }

					if($this->db->transStatus() === FALSE)
					{
						//die("rollback");
						$this->db->transRollback();                      
						flashToast("message", "Something errordue to Update!!!");  
						return $this->response->redirect(base_url('WaterApplyNewConnection/updat_application_demand/'.$apply_conn_id_md5));
				
					}
					else
					{ //die("commit");
						$this->db->transCommit();
						flashToast("message", "Update Consumer Successfully!!!");
						return $this->response->redirect(base_url('WaterApplyNewConnection/updat_application_demand/'.$apply_conn_id_md5));
					}
				}
				catch(Exception $e)
				{
					$this->db->transRollback();
					flashToast("message", $e->getMessage());
                    print_var( $e->getMessage());
                    echo $e->getFile();                    
					echo $e->getLine();
					die; 
					return $this->response->redirect(base_url('WaterApplyNewConnection/updat_application_demand/'.$apply_conn_id_md5));
				}
			}

            return view('Water/water_connection/updat_application_demand', $data);            
        } 
        else
        {
            flashToast("massege", "Application Not Found");
			return $this->response->redirect(base_url('Dashboard/welcome'));
        } 
        
    }
  
    
}
