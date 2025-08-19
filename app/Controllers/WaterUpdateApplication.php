<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\WaterApplyNewConnectionModel;
use App\Models\WaterPropertyModel;
use App\Models\WaterConnectionThroughModel;
use App\Models\WaterConnectionTypeModel;
use App\Models\WaterPipelineModel;
use App\Models\model_ward_mstr;
use App\Models\PropertyModel;
use App\Models\WaterUpdateApplicationModel;
use App\Models\StateModel;
use App\Models\DistrictModel;

class WaterUpdateApplication extends AlphaController
{
    protected $db;
    protected $property_db;
    protected $dbSystem;
    
    //protected $db_name;
    
    
    public function __construct()
    {
        
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
        
        //$db_name = db_connect("db_rmc_property"); 
        
        $this->apply_wtrconn_model=new WaterApplyNewConnectionModel($this->db);
        $this->water_property_model=new WaterPropertyModel($this->db);
        $this->conn_through_model=new WaterConnectionThroughModel($this->db);
        $this->conn_type_model=new WaterConnectionTypeModel($this->db);
        $this->pipeline_model=new WaterPipelineModel($this->db);
        $this->ward_model=new model_ward_mstr($this->dbSystem);
        $this->property_model=new PropertyModel($this->property_db);
        $this->update_water_app_model=new WaterUpdateApplicationModel($this->db);
		$this->state_model=new StateModel($this->dbSystem);
		$this->district_model=new DistrictModel($this->dbSystem);
		
    }
    
    public function index()
    {


    }

   /* public function update_application()
    {

        $data=array();
        helper(['form']);
        $session=session();
        $get_ulb_id=$session->get('ulb_dtl');
        $get_emp_details=$session->get('emp_details');
        $emp_id=$get_emp_details['id'];

        if($this->request->getMethod()=='post')
        {

          
                $inputs = filterSanitizeStringtoUpper($this->request->getVar());
                // print_r($inputs['owner_name']);
                print_r($inputs);

                $data['connection_type_id']=$inputs['connection_type_id'];
                $data['conn_through_id']=$inputs['conn_through_id'];
                $data['property_type_id']=$inputs['property_type_id'];
                $data['pipeline_type_id']=$inputs['pipeline_type_id'];
                $data['category']=$inputs['category'];
                $data['ward_id']=$inputs['ward_id'];
                $data['holding_no']=$inputs['holding_no'];
                $data['area_in_sqft']=$inputs['area_in_sqft'];
                $data['area_in_sqmt']=$inputs['area_in_sqmt'];
                $data['address']=$inputs['address'];
                $data['landmark']=$inputs['landmark'];
                $data['pin']=$inputs['pin'];
              
                $data['holding_exists']=$inputs['holding_exists'];
                $data['saf_id']=$inputs['saf_id'];
                $data['prop_id']=$inputs['prop_id'];
                $data['count']=$inputs['count'];
                $data['owner_type']=$inputs['owner_type'];
                $data['water_conn_id']=$inputs['water_conn_id'];
                $data['saf_no']=$inputs['saf_no'];
                $data['bank_name']=$inputs['bank_name'];
                $data['branch_name']=$inputs['branch_name'];
                $data['account_no']=$inputs['account_no'];
                $data['ifsc_code']=$inputs['ifsc_code'];
                $data['k_no']=$inputs['k_no'];
                $data['bind_book_no']=$inputs['bind_book_no'];
                $data['elec_account_no']=$inputs['elec_account_no'];
                $data['elec_category']=$inputs['elec_category'];
                

            $rules=[

                    'connection_type_id'=>'required',
                    'conn_through_id' =>'required',
                    'property_type_id' =>'required',
                    'pipeline_type_id' =>'required',
                    'category' =>'required',
                    'ward_id' =>'required',
                    'holding_exists' =>'required',
                    'area_in_sqft' =>'required',
                    'area_in_sqmt' =>'required',
                    'address' =>'required',
                    'landmark' =>'required',
                    'pin' =>'required|min_length[6]|max_length[6]',
                    
                    
                  
                    
                ];


            if(!$this->validate($rules))
            {

                $data['validation']=$this->validator;
                return view('water/water_connection/update_application',$data);

            }
            else
            {


                $apply_water_conn=array();

                $apply_water_conn['connection_type_id']=$data['connection_type_id'];
                $apply_water_conn['connection_through_id']=$data['conn_through_id'];
                $apply_water_conn['property_type_id']=$data['property_type_id'];
                $apply_water_conn['pipeline_type_id']=$data['pipeline_type_id'];
                $apply_water_conn['category']=$data['category'];
                $apply_water_conn['ward_id']=$data['ward_id'];
                $apply_water_conn['holding_no']=$data['holding_no'];
                $apply_water_conn['saf_no']=$data['saf_no'];
                $apply_water_conn['area_sqft']=$data['area_in_sqft'];
                $apply_water_conn['area_sqmt']=$data['area_in_sqmt'];
                $apply_water_conn['address']=$data['address'];
                $apply_water_conn['landmark']=$data['landmark'];
                $apply_water_conn['pin']=$data['pin'];
                $apply_water_conn['elec_k_no']=$data['k_no'];
                $apply_water_conn['elec_bind_book_no']=$data['bind_book_no'];
                $apply_water_conn['elec_account_no']=$data['elec_account_no'];
                $apply_water_conn['elec_category']=$data['elec_category'];
                
                
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

                  

                    $rate_id=$get_rate_id['id'];
                    $reg_fee=$get_rate_id['reg_fee'];
                    $proc_fee=$get_rate_id['proc_fee'];
                    $app_fee=$get_rate_id['app_fee'];
                    $sec_fee=$get_rate_id['sec_fee'];
                    $conn_fee=$get_rate_id['conn_fee'];

                    $water_conn_fee_charge=$reg_fee+$proc_fee+$app_fee+$sec_fee+$conn_fee;

                    $conn_fee_charge=array();
                    $conn_fee_charge['apply_connection_id']=$data['water_conn_id'];
                    $conn_fee_charge['charge_for']='New Connection';
                    $conn_fee_charge['amount']=$water_conn_fee_charge;
                    $conn_fee_charge['created_on']=date('Y-m-d H:i:s');
                   
                            
                    $this->update_water_app_model->insert_conn_fee($conn_fee_charge);


                }
                
                        
                if($rate_id!="")
                {
                    $apply_water_conn['water_fee_mstr_id']=$rate_id;
                }
                
                $apply_water_conn['owner_type']=$data['owner_type'];
                $apply_water_conn['apply_date']=date('Y-m-d');
                $apply_water_conn['user_id']=$emp_id;
                $water_conn_id=$data['water_conn_id'];

               
               
                $update=$this->update_water_app_model->update_application_details($apply_water_conn,$water_conn_id);

                       

                        if($update)
                        {

                         
                            if(isset($inputs['owner_name']))
                            {

                                  $owner_arr=array();
                                   print_r($inputs['owner_name']);


                                   $this->update_water_app_model->delete_prev_owner($water_conn_id);
                                   
                                  for($i=0;$i<sizeof($inputs['owner_name']);$i++)
                                  {

                                    echo "string";
                                    //echo  'owner_name'.$i.'';
                                      //  echo $inputs['owner_name'][$i];
                                     
                                        $owner_arr['apply_connection_id']=$data['water_conn_id'];

                                        $owner_arr['applicant_name']=$inputs['owner_name'][$i];

                                        $owner_arr['father_name']=$inputs['guardian_name'][$i];

                                        $owner_arr['city']=$inputs['city'][$i];

                                        $owner_arr['district']=$inputs['district'][$i];

                                        $owner_arr['state']=$inputs['state'][$i];

                                        $owner_arr['mobile_no']=$inputs['mobile_no'][$i];

                                        $owner_arr['email_id']=$inputs['email_id'][$i];

                                        $owner_arr['emp_details_id']=$emp_id;
                                        
                                        $owner_arr['created_on']=date('Y-m-d H:i:s');
                                        
                                 
                                        $this->update_water_app_model->update_owner($owner_arr,$water_conn_id);



                                  }
                                 
                                //  print_r($owner_arr);

                            }


                         

                         //  return $this->response->redirect(base_url('WaterApplyNewConnection/water_connection_view/'.md5($water_conn_id)));



                        }
                    

                        else
                        {

                          
                        }
                   
                
            }
        }

    }*/



    public function update_application()
    {

        $data=array();
        helper(['form']);
        $session=session();
        $get_ulb_id=$session->get('ulb_dtl');
        $get_emp_details=$session->get('emp_details');
        $emp_id=$get_emp_details['id'];
        //$data['state_list']=$this->state_model->getstateList();

        //print_r($data);

       
        if($this->request->getMethod()=='post')
        {
                $inputs = filterSanitizeStringtoUpper($this->request->getVar());
                if(isset($inputs['btn_review']) && $inputs['btn_review'])
                {
                    $water_conn_id = $inputs['water_conn_id'];
                   /*
                    data['connection_type_id']=$inputs['connection_type_id'];
                    $data['conn_through_id']=$inputs['conn_through_id'];
                    $data['property_type_id']=$inputs['property_type_id'];
                    $data['pipeline_type_id']=$inputs['pipeline_type_id'];
                    $data['category']=$inputs['category'];
                    $data['water_conn_id']=$inputs['water_conn_id2'];
                    */
                    $data['elec_k_no']=$inputs['elec_k_no'];
                    $data['elec_bind_book_no']=$inputs['elec_bind_book_no'];
                    $data['elec_account_no']=$inputs['elec_account_no'];
                    $data['elec_category']=$inputs['elec_category'];
                    

                    $rules=[

                            'connection_type_id'=>'required',
                            'conn_through_id' =>'required',
                            'property_type_id' =>'required',    
                        ];


                    if(!$this->validate($rules))
                    {
                        //$data['validation']=$this->validator;
                        $update=$this->update_water_app_model->update_application_details($data, $water_conn_id);
                        return $this->response->redirect(base_url('WaterUpdateApplicationNew/index/'.md5($water_conn_id)));
                    }
                    else
                    {
                        $apply_water_conn=array();

                        $apply_water_conn['connection_type_id']=$data['connection_type_id'];
                        $apply_water_conn['connection_through_id']=$data['conn_through_id'];
                        $apply_water_conn['property_type_id']=$data['property_type_id'];
                      
                    
                        $apply_water_conn['elec_k_no']=$data['k_no'];
                        $apply_water_conn['elec_bind_book_no']=$data['bind_book_no'];
                        $apply_water_conn['elec_account_no']=$data['elec_account_no'];
                        $apply_water_conn['elec_category']=$data['elec_category'];
                        
                      
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

                        $get_rate_id=$this->apply_wtrconn_model->get_rate_id($data['pipeline_type_id'],$data['property_type_id'],$data['conn_through_id'],$data['connection_type_id'],$data['category']);

                        //print_r($get_rate_id);
                        $rate_id="";
                        if($get_rate_id)
                        {
                            $rate_id=$get_rate_id['id'];
                            $reg_fee=$get_rate_id['reg_fee'];
                            $proc_fee=$get_rate_id['proc_fee'];
                            $app_fee=$get_rate_id['app_fee'];
                            $sec_fee=$get_rate_id['sec_fee'];
                            $conn_fee=$get_rate_id['conn_fee'];

                            $water_conn_fee_charge=$reg_fee+$proc_fee+$app_fee+$sec_fee+$conn_fee;
                            
                            $conn_fee_charge=array();
                            $conn_fee_charge['apply_connection_id']=$data['water_conn_id'];
                            $conn_fee_charge['charge_for']='New Connection';
                            $conn_fee_charge['amount']=$water_conn_fee_charge;
                            $conn_fee_charge['created_on']=date('Y-m-d H:i:s');
                            $this->update_water_app_model->insert_conn_fee($conn_fee_charge);
                        }
                        else
                        {
                            $this->update_water_app_model->delete_old_conn_fee($data['water_conn_id']);
                        }
                        
                                
                        if($rate_id!="")
                        {
                            $apply_water_conn['water_fee_mstr_id']=$rate_id;
                        }
                        
                        $water_conn_id=$data['water_conn_id'];
                        $update=$this->update_water_app_model->update_application_details($apply_water_conn,$water_conn_id);
                        return $this->response->redirect(base_url('WaterUpdateApplicationNew/index/'.md5($data['water_conn_id'])));
                    }
                }
                else
                {
                    if($this->request->getMethod()=='post')
                    {
                        $inputs=filterSanitizeStringtoUpper($this->request->getVar());
                        
                        if($inputs['add'])
                        {
                            $data['applicant_name']=$inputs['applicant_name'];
                            $data['guardian_name']=$inputs['guardian_name'];
                            $data['mobile_no']=$inputs['mobile_no'];
                            $data['email_id']=$inputs['email_id'];
                            $data['state']=$inputs['state'];
                            $data['city']=$inputs['city'];
                            $data['owner_id']=$inputs['owner_id'];
                            $data['water_conn_id']=$inputs['water_conn_id'];
                            $data['district']=$inputs['district'];

                            $rules=[

                            'applicant_name'=>'required',
                            'mobile_no' =>'required|numeric',
                          
                            ];

                            if($inputs['email_id']!="")
                            {
                                
                                //$arr=array();
                                $arr=['email_id'=>'valid_email'];
                                array_merge($rules,$arr);

                            }

                            //print_r($rules);

                            if(!$this->validate($rules))
                            {

                                $data['validation']=$this->validator;
                                $session=session();
                                $_SESSION['validation']=$data['validation'];

                                //return view('water/water_connection/update_application',$data);
                                return $this->response->redirect(base_url('WaterUpdateApplicationNew/index/'.md5($data['water_conn_id'])));


                            }
                            else
                            {
                                $application = $this->apply_wtrconn_model->getData(md5($data['water_conn_id']));
                                if($inputs['add']=='ADD' && $application['doc_verify_status']!=1)
                                {
                                    $insert_owner=array();
                                    $insert_owner['applicant_name']=$data['applicant_name'];
                                    $insert_owner['father_name']=$data['guardian_name'];
                                    $insert_owner['mobile_no']=$data['mobile_no'];
                                    $insert_owner['email_id']=$data['email_id'];
                                    $insert_owner['state']=$data['state'];
                                    $insert_owner['district']=$data['district'];
                                    $insert_owner['city']=$data['city'];
                                    $insert_owner['apply_connection_id']=$data['water_conn_id'];
                                    
                                    
                                    $this->apply_wtrconn_model->insert_owner($insert_owner);
                                    //return $this->response->redirect(base_url('WaterApplyNewConnection/water_connection_view/'.md5($data['water_conn_id'])));

                                    return $this->response->redirect(base_url('WaterUpdateApplicationNew/index/'.md5($data['water_conn_id'])));
                                    



                                }
                                else if($inputs['add']='Update' && $application['doc_verify_status']!=1)
                                {
                                        //echo "string";
                                    $update_owner['applicant_name']=$data['applicant_name'];
                                    $update_owner['father_name']=$data['guardian_name'];
                                    $update_owner['mobile_no']=$data['mobile_no'];
                                    $update_owner['email_id']=$data['email_id'];
                                    $update_owner['state']=$data['state'];
                                    $update_owner['district']=$data['district'];
                                    $update_owner['city']=$data['city'];
                                    $update_owner['owner_id']=$data['owner_id'];
                                    
                                    $this->update_water_app_model->update_owner_new($update_owner,$data['owner_id']);

                                    //return $this->response->redirect(base_url('WaterApplyNewConnection/water_connection_view/'.md5($data['water_conn_id'])));

                                    return $this->response->redirect(base_url('WaterUpdateApplicationNew/index/'.md5($data['water_conn_id'])));
                                    

                                }
                                else 
                                {
                                    if($inputs['add']=='ADD')
                                        flashToast("message", "Owner Can't Add Because Document Already Verified");
                                    elseif($inputs['add']='Update')
                                        flashToast("message", "Owner Can't Update Because Document Already Verified");
                                    return $this->response->redirect(base_url('WaterUpdateApplicationNew/index/'.md5($data['water_conn_id'])));
                                }

                            }


                        }


                    }

                }
        }

    }
    
    public function delete_owner()
    {
        if($this->request->getMethod()=='post')
        {
           $owner_id=$this->request->getVar('owner_id');
           $water_conn_id=$this->request->getVar('water_conn_id');
           
           if($owner_id!="")
           {
               
                 $count_owner=$this->update_water_app_model->count_owner($water_conn_id);

                if($count_owner==1)
                {
                    $run=$this->update_water_app_model->del_owner($owner_id);
                    if($run)
                    {
                        $response=['response'=>true];

                    }
                }
                else
                {
                    $response=['response'=>false];
                }
                

               return json_encode($response);

           }
        }
    }
    
}
