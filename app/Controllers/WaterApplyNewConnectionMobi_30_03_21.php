<?php namespace App\Controllers;

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



class WaterApplyNewConnectionMobi extends MobiController
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
    
    
    
    //protected $db_name;
    
    
    public function __construct()
    {   

        $session=session();
        $get_ulb_detail=$session->get('ulb_dtl');
        //print_r($get_ulb_detail);

      	$this->ulb_id=$get_ulb_detail['ulb_mstr_id'];
        $this->state=$get_ulb_detail['state'];
        $this->district=$get_ulb_detail['district'];
        $this->city=$get_ulb_detail['city'];
        
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
        $data['state_list']=$this->state_model->getstateList();


        //print_r($data['property_type_list']);
        if($this->request->getMethod()=='post')
        {
            if($this->request->getVar('id')=="")
            {

                $inputs = filterSanitizeStringtoUpper($this->request->getVar());
                //print_r($inputs);
                
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
                $data['count']=$inputs['count'];
                $data['owner_type']=$inputs['owner_type'];
                $data['saf_no']=$inputs['saf_no'];
                $data['owner_name']=$inputs['owner_name'];
                $data['mobile_no']=$inputs['mobile_no'];
                $data['state']=$inputs['state'];
                $data['district']=$inputs['district'];
                $data['city']=$inputs['city'];
                $data['guardian_name']=$inputs['guardian_name'];
                $data['email_id']=$inputs['email_id'];
                
                // checking if exists water connection for the holding as owner because for owner only one water connection is applied but for tenant multiples

             $count_owner_prop=$this->apply_wtrconn_model->check_owner_holding_water_conn($data['prop_id']);
             //print_r($count_owner_prop);
            

              $count_prop=$count_owner_prop['count_prop'];

                if($count_prop>0 and $data['owner_type']=='OWNER')
                {

                      $_SESSION['msg']="Owner Already has Water Connection";
                      return view("water/water_connection/applywaterconnection",$data);


                }


                /*if($inputs['owner_name'])
                {
                    for($i=0;$i<sizeof($inputs['owner_name']);$i++)
                    {
                        $data['owner_name']=$inputs['owner_name'][$i];
                        $data['guardian_name']=$inputs['guardian_name'][$i];
                        $data['mobile_no']=$inputs['mobile_no'][$i];
                        $data['email_id']=$inputs['email_id'][$i];
                        $data['state']=$inputs['state'][$i];
                        $data['district']=$inputs['district'][$i];
                        $data['city']=$inputs['city'][$i];
                        
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
                //'holding_exists' =>'trim|required|alpha',
               if($inputs['email_id'])
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
                }
                
                

                if(!$this->validate($rules)){

                    $data['validation']=$this->validator;
                    

                    return view('water/water_connection/applywaterconnection',$data);
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


                $apply_water_conn['elec_category']=$data['elec_category'];
                if($data['saf_id']!="")
                {
                    $apply_water_conn['saf_dtl_id']=$data['saf_id'];
                }
                if($data['prop_id']!="")
                {
                    $apply_water_conn['prop_dtl_id']=$data['prop_id'];
                }  

                
                $get_rate_id=$this->apply_wtrconn_model->get_rate_id($data['pipeline_type_id'],$data['property_type_id'],$data['conn_through_id'],$data['connection_type_id'],$data['category']);

               //print_r($get_rate_id);

                $rate_id="";

                if($get_rate_id)
                {
                    $apply_water_conn['water_fee_mstr_id']=$get_rate_id['id'];
                }
                
                $apply_water_conn['owner_type']=$data['owner_type'];
                $apply_water_conn['apply_date']=date('Y-m-d');
                $apply_water_conn['user_id']=$this->emp_id;

               
                $apply_water_conn['elec_k_no']=$data['elec_k_no'];
                $apply_water_conn['elec_bind_book_no']=$data['elec_bind_book_no'];
                $apply_water_conn['elec_account_no']=$data['elec_account_no'];
                $apply_water_conn['elec_category']=$data['elec_category'];
                
                
                $insert_id=$this->apply_wtrconn_model->insertData($apply_water_conn);

                 if($get_rate_id)
                 {
                	//echo "dsd";
                    $rate_id=$get_rate_id['id'];
                    $reg_fee=$get_rate_id['reg_fee'];
                    $proc_fee=$get_rate_id['proc_fee'];
                    $app_fee=$get_rate_id['app_fee'];
                    $sec_fee=$get_rate_id['sec_fee'];
                    $conn_fee=$get_rate_id['conn_fee'];

                    $water_conn_fee_charge=$reg_fee+$proc_fee+$app_fee+$sec_fee+$conn_fee;
                    if($data['property_type_id']==7)
                    {
                        $road_app_fee=$this->road_app_fee_model->getLastRow();
                        //print_r($road_app_fee);
                        $road_app_fee_id=$road_app_fee['id'];
                        $flat_count=$inputs['flat_count'];
                        $app_fee=$road_app_fee['appartment_fee'];
                        $total=$flat_count*$app_fee;
                        $water_conn_fee_charge=$water_conn_fee_charge+$total;
                        
                        
                    }
                    else
                    {
                        $road_app_fee_id=0;
                    }

                    $conn_fee_charge=array();
                    $conn_fee_charge['apply_connection_id']=$insert_id;
                    $conn_fee_charge['charge_for']='New Connection';
                    $conn_fee_charge['amount']=$water_conn_fee_charge;
                    $conn_fee_charge['created_on']=date('Y-m-d H:i:s');
                   

                    $this->apply_wtrconn_model->insert_conn_fee($conn_fee_charge);


                 }
               // print_r($insert_id);
                if($rate_id!="")
                {
                    $payment_status=0;
                }
                else
                {
                    $payment_status=1;
                }

                $app_no="APP".$insert_id.date('dmyhis');

                $this->apply_wtrconn_model->update_application_no($app_no,$payment_status,$road_app_fee_id,$insert_id);

                      
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
                              //  echo $inputs['owner_name'][$i];
                             
                                $owner_arr['apply_connection_id']=$insert_id;

                                $owner_arr['applicant_name']=$inputs['owner_name'][$i];

                                $owner_arr['father_name']=$inputs['guardian_name'][$i];

                                $owner_arr['city']=$inputs['city'][$i];

                                $owner_arr['district']=$inputs['district'][$i];

                                $owner_arr['state']=$inputs['state'][$i];

                                $owner_arr['mobile_no']=$inputs['mobile_no'][$i];

                                $owner_arr['email_id']=$inputs['email_id'][$i];

                                $owner_arr['emp_details_id']=$this->emp_id;
                                
                                $owner_arr['created_on']=date('Y-m-d H:i:s');
                                
                         
                                  $this->apply_wtrconn_model->insert_owner($owner_arr);



                          }
                         
                        //  print_r($owner_arr);

                    }

                 


                 return $this->response->redirect(base_url('WaterApplyNewConnectionMobi/water_connection_view/'.md5($insert_id)));


                }
                else
                {

                    return $this->response->redirect(base_url('WaterApplyNewConnectionMobi'));
                }
                   
                }

            }
         
        }
        
        else
        {   

             return view('water/water_connection/applywaterconnection',$data);
        }


    }

    public function water_connection_view($insert_id)
    {


            $data['user_type']=$this->user_type;

            $data['consumer_details']=$this->apply_wtrconn_model->water_conn_details($insert_id);
            //print_r($data);

            $data['owner_details']=$this->apply_wtrconn_model->water_owner_details($insert_id);
            
            $data['water_conn_id']=$insert_id;
            //  print_r($data['owner_details']);
            $data['dues']=$this->conn_fee_model->conn_fee_charge($insert_id);
            $data['transaction_count']=$this->trans_model->getTransCountbyApplicationId($insert_id);
            


            $data['application_status']=$this->application_status($insert_id);

            return view('water/water_connection/water_connection_details_view',$data);

    }

    public function application_status($insert_id)
    {


         $get_application_status=$this->apply_wtrconn_model->water_application_status($insert_id);
         $get_level_pending_dtls=$this->apply_wtrconn_model->level_pending_details($insert_id);
         $count_site_inspec_diff_pay=$this->site_ins_model->application_site_inspection_payment_detls($insert_id);
         
         //print_r($site_inspec_pay);


         $doc_status=$get_application_status['doc_status'];
         $payment_status=$get_application_status['payment_status'];

         if($doc_status==0 and $payment_status==0)
         {
            $status="Document Not Uploaded and Payment Not Done";
         }
         else if($doc_status==0 and $payment_status==2)
         {
            $status="Document Not Uploaded and  Payment Done but not cleared";
         }
         else if($doc_status!=0 and $payment_status==0)
         {
            $status="Document Uploaded but Payment Not Done";
         }
         else if(($doc_status==1 or $doc_status==0) and $payment_status==2)
         {
         	$status="Document Uploaded and Payment Done but Not Cleared";
         }
         else if($get_level_pending_dtls)
         {

            $receiver_id=$get_level_pending_dtls['user_type'];
            if($get_level_pending_dtls['verification_status']==0)
            {
                $status="Pending at ".$receiver_id;
            }
            else if($get_level_pending_dtls['verification_status']==2 and $get_level_pending_dtls['receiver_user_type_id']==12)
            {
                $status="Sent Back to Citizen by ".$receiver_id;
            }
            

         }
         else if($count_site_inspec_diff_pay)
         {
            $status="Payment not done for difference amount generated in Site Inspection";
         }
         else
         {
         	  $status="Final Approved";
         }
         return $status;

         

    }
 
    public function validate_holding_no()
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



                $response = ['response'=>true, 'dd'=>$count,'state'=>$this->state,'district'=>$this->district,'city'=>$this->city];

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


                 $response = ['response'=>true, 'dd'=>$count,'state'=>$this->state,'district'=>$this->district,'city'=>$this->city];

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
  
    
}
