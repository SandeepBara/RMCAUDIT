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
use App\Models\WaterConnectionChargeModel;
use App\Models\WaterPaymentModel;
use App\Models\WaterPenaltyModel;


class WaterApplyNewConnectionCitizen extends HomeController
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
    protected $conn_charge_model;
    protected $WaterPenaltyModel;
    protected $payment_model;
    
    public function __construct()
    {
        
        parent::__construct();
        helper(['db_helper', 'form']);
        $session=session();
        $get_ulb_detail=$session->get('ulb_dtl')??getUlbDtl();
        //print_r($get_ulb_detail);

      	$this->ulb_id=$get_ulb_detail['ulb_mstr_id'];
        
        
        $get_emp_details=$session->get('emp_details');
        $this->emp_id=$get_emp_details['id'];
        $this->user_type=$get_emp_details['user_type_mstr_id'];        
        
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

        $this->conn_charge_model=new WaterConnectionChargeModel($this->db);
        $this->WaterPenaltyModel=new WaterPenaltyModel($this->db);
        $this->payment_model=new WaterPaymentModel($this->db);
    }

    public function __destruct()
    {
        if($this->db)
        {
            $this->db->close();            
        }
        if($this->property_db)
        {
            $this->property_db->close();
        }
        if( $this->dbSystem )
        {
            $this->dbSystem->close();
        }
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
        
        
        //print_var($_SESSION);
        if($this->request->getMethod()=='post')
        {
            if($this->request->getVar('id')=="")
            {
                $inputs = filterSanitizeStringtoUpper($this->request->getVar());
                //print_var($inputs);die;
                
                $data['connection_type_id']=$inputs['connection_type_id'];
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
                        // $_SESSION['msg']="Water Connection Already applied with this SAF No.";
                        cSetCookie('msg',"Water Connection Already applied with this SAF No.");
                        return view("water/water_connection/applywaterconnection", $data);
                    }
                }
                if($data['prop_id']>0)
                {
                    $count_owner_prop=$this->apply_wtrconn_model->check_owner_holding_water_conn($data['prop_id']);
                    //print_r($count_owner_prop);
                    $count_prop=$count_owner_prop['count_prop'];
                    if($count_prop>0 and $data['owner_type']=='OWNER')
                    {
                        // $_SESSION['msg']="Owner Already has Water Connection";
                        cSetCookie('msg',"Owner Already has Water Connection");
                        return view("water/water_connection/applywaterconnection", $data);
                    }
                }
                $alphaNumericSpacesDotDash = '/^[a-z0-9 .\-]+$/i';
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
                    return view('water/water_connection/applywaterconnection', $data);
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
                    $apply_water_conn['apply_from']='ONL';                    
                    
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
                                            
                        $conn_fee_charge['penalty']=$penalty;
                        $conn_fee_charge['amount']=$penalty+$conn_fee;
                        if($conn_fee_charge['amount']>0)
                        {
                            $this->apply_wtrconn_model->insert_conn_fee($conn_fee_charge);
                        }
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
                            $this->apply_wtrconn_model->BPL_transection($insert_id,'Online',$this->emp_id);
                        }
                    

                    if($insert_id)
                    {

                        if(isset($inputs['owner_name']))
                        {

                            $owner_arr=array();
                            for($i=0;$i<sizeof($inputs['owner_name']);$i++)
                            {
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
                            
                            //  print_r($owner_arr);

                        }
                        return $this->response->redirect(base_url('WaterApplyNewConnectionCitizen/water_connection_view/'.md5($insert_id)));


                    }
                    else
                    {
                        return $this->response->redirect(base_url('WaterApplyNewConnectionCitizen'));
                    }
                   
                }

            }
         
        }
        
        else
        {   
            return view('water/water_connection/applywaterconnection', $data);
            //return view('citizen/water/applywaterconnection', $data);
        }
    }

    
    public function water_connection_view($insert_id)
    {
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
        $data['ulb_id'] = $_SESSION['ulb_dtl']['ulb_mstr_id']??getUlbDtl()['ulb_mstr_id'];
		return view('citizen/water/water_connection_details_view', $data);
    }

    public function application_status($insert_id)
    {
        return $this->apply_wtrconn_model->application_status($insert_id);

         $get_application_status=$this->apply_wtrconn_model->water_application_status($insert_id);
         $get_level_pending_dtls=$this->apply_wtrconn_model->level_pending_details($insert_id);
         //print_r($get_level_pending_dtls);
         $count_site_inspec_diff_pay=$this->site_ins_model->application_site_inspection_payment_detls($insert_id);
         
         $doc_status=$get_application_status['doc_status'];
         $payment_status=$get_application_status['payment_status'];

         
         if($doc_status==0)
         {
                if($payment_status==0)
                {
                    $status="Document Not Uploaded and Payment Not Done";
                }
                else if($payment_status==1)
                {
                    $status="Document Not Uploaded but Payment Done";
                }
                else if($payment_status==2)
                {
                    $status="Document Not Uploaded but Payment Done but Cheque not cleared";
                }
         }
         else if($doc_status==1 and $payment_status!=1)
         {
                if($payment_status==0)
                {
                    $status="Document Uploaded but Payment Not Done";
                }
                
                else if($payment_status==2)
                {
                    $status="Document Uploaded and Payment Done but Cheque not cleared";
                }
         }
         if($doc_status==1 and $payment_status==1)
         {	 	

            if($get_level_pending_dtls)
            {  
                
                $receiver_id=$get_level_pending_dtls['receiver_user_type_id'];
                $verification_status=$get_level_pending_dtls['verification_status'];              
                if($verification_status==0)
                {	
                    if($receiver_id==12)
                    {
                        $status="Pending at Dealing Officer";
                    }
                    else if($receiver_id==13)
                    {
                        if($count_site_inspec_diff_pay>0)
                        {
                            $status="Payment Pending of Diff Amount at Site Inspection";
                        }
                        else
                        {
                            $status="Pending at Junior Engineer";
                        }
                        
                    }
                    else if($receiver_id==14)
                    {
                        $status="Pending at Section Head";
                    }
                    else if($receiver_id==15)
                    {
                        $status="Pending at Assistant Engineer";
                    }
                    else if($receiver_id==16)
                    {
                        $status="Pending at Executive Officer";
                    }
                }
                else if($verification_status==2 and $receiver_id==12)
                {
                    $status="Sent Back to Citizen by Dealing Officer";
                }
                else if($verification_status==2 and $receiver_id==13)
                {
                    $status="Sent Back to Citizen by Junior Engineer";
                }
                else if($verification_status==2 and $receiver_id==14)
                {
                    $status="Sent Back to Citizen by Section Head";
                }
                else if($verification_status==2 and $receiver_id==15)
                {
                    $status="Sent Back to Citizen by Assistant Engineer";
                }
                else if($verification_status==2 and $receiver_id==16)
                {
                    $status="Sent Back to Citizen by Executive Officer";
                }

                else if($verification_status==4 and $receiver_id==12)
                {
                    $status="Application Rejected by Dealing Officer";
                }
                else if($verification_status==4 and $receiver_id==13)
                {
                    $status="Application Rejected by Junior Engineer";
                }
                else if($verification_status==4 and $receiver_id==14)
                {
                    $status="Application Rejected by Section Head";
                }
                else if($verification_status==4 and $receiver_id==15)
                {
                    $status="Application Rejected by Assistant Engineer";
                }
                else if($verification_status==4 and $receiver_id==16)
                {
                    $status="Application Rejected by Executive Officer";
                }

                else if($verification_status==1 and $receiver_id==16)
                {
                    $status="Approved by Executive Officer";
                }
            }          

         }
         return $status;

         

    }
 
  /*  public function validate_holding_no()
    {

        
        if($this->request->getMethod()=="post")
        {

           $data=array();
           $inputs = arrFilterSanitizeString($this->request->getVar());  
           $data['ward_id']=$inputs['ward_id'];
           $data['holding_no']=$inputs['holding_no'];

           $count = $this->property_model->chkholding_exist($data);
           //print_r($count);

           
            if ($count = $this->property_model->chkholding_exist($data)){



                $response = ['response'=>true, 'dd'=>$count];

            } else {
                $response = ['response'=>false];
            }
        } else {
            $response = ['response'=>false];
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
  */


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

    //search to searchList
    public function searchList($type='')
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
    public function getconnetCharge($water_conn_id)
    { 
        $data=array();
        $data['curr_date']=date('Y-m-d');        
        $consumer_details=$this->apply_wtrconn_model->water_conn_details($water_conn_id);        
        $water_conn_details= $this->conn_fee_model->fetch_water_con_details($water_conn_id);
        //print_r($get_rate_id);

        $data['dues']= $this->conn_charge_model->due_exists($water_conn_id); 
        $data['rate_id']=$water_conn_details['water_fee_mstr_id'];
        $data['conn_fee_charge']=$this->conn_fee_model->conn_fee_charge($water_conn_id);
        $data['penalty']=$this->penalty_installment_model->getUnpaidInstallmentSum($water_conn_id);

        # cheque bounce penalty
        $data['other_penalty']=$this->WaterPenaltyModel->getUnpaidPenaltySum($water_conn_id);         
        $rebate_details=$this->payment_model->get_rebate_details($water_conn_id);
        $data['rebate']=$rebate_details['rebate'];

        # Regularizaton
        if($consumer_details['connection_type_id'] == 2)
        $data['rebate'] += (($data['penalty']/100) * 10); // 10 % Off in whole payment of penalty

        $data['total_amount']=$data['conn_fee_charge']['conn_fee']+$data['penalty']+$data['other_penalty']-$data['rebate'];
        return $data ;
        
    }
    
  
    
}
