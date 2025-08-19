<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\TCVisitingModel;
use App\Models\FeedbackMessageModel;
use App\Models\PropertyModel;
use App\Models\model_saf_dtl;
use App\Models\model_water_consumer;
use App\Models\WaterApplyNewConnectionModel;
use App\Models\model_trade_licence;


class TCVisiting extends MobiController
{
    protected $db;
    protected $property_db;
    protected $dbSystem;
    protected $emp_id;
    protected $ulb_id;
    protected $state;
   
    //protected $db_name;
    
    
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
        if($db_name = dbConfig("trade"))
        {
            $this->trade = db_connect($db_name);
        }
        if($db_system = dbSystem())
        {
            $this->dbSystem = db_connect($db_system); 
        }
        
        $this->tc_visiting_model=new TCVisitingModel($this->dbSystem);
        $this->ward_model=new model_ward_mstr($this->dbSystem);
        $this->feedback_model=new FeedbackMessageModel($this->dbSystem);
        $this->property_model=new PropertyModel($this->property_db);
        $this->model_saf_dtl=new model_saf_dtl($this->property_db);
        $this->water_conn_model=new WaterApplyNewConnectionModel($this->db);
        $this->model_water_consumer=new model_water_consumer($this->db);
        $this->model_trade_licence=new model_trade_licence($this->trade);

    }
    
    public function index()
    {

        $data=array();
        $data['ulb_mstr_id']=$this->ulb_id;
        $data['ward_list']=$this->ward_model->getWardList($data);
        $data['feedback_list']=$this->feedback_model->getData();

        //print_r($data['ward_list']);
        $inputs=arrFilterSanitizeString($this->request->getVar());
        if($this->request->getMethod()=='post')
        {
            
            if($this->request->getVar('submit_property'))
            {
                
                
                $tc_visiting=array();

                $tc_visiting['related_id']=$inputs['prop_id'];
                $tc_visiting['message_id']=$inputs['message_id'];
                $tc_visiting['module']=$inputs['module_property'];
                $tc_visiting['visiting_date']=date('Y-m-d');
                $tc_visiting['created_on']=date('Y-m-d H:i:s');
                $tc_visiting['user_id']=$this->emp_id;
                
                if($tc_visiting['message_id']==0)
                {
                    $tc_visiting['other_reason']=$inputs['other_reason_prop'];
                }
                //print_r($tc_visiting);

                $insert_id=$this->tc_visiting_model->insertData($tc_visiting);
                if($insert_id)
                {
                    flashToast("insert", "Inserted Successfully!!!");
                }

            }
            else if($this->request->getVar('submit_saf'))
            {
                $tc_visiting=array();

                $tc_visiting['related_id']=$inputs['saf_id'];
                $tc_visiting['message_id']=$inputs['message_id'];
                $tc_visiting['module']=$inputs['module_saf'];
                $tc_visiting['visiting_date']=date('Y-m-d');
                $tc_visiting['created_on']=date('Y-m-d H:i:s');
                $tc_visiting['user_id']=$this->emp_id;

                if($tc_visiting['message_id']==0)
                {
                    $tc_visiting['other_reason']=$inputs['other_reason_saf'];
                }
                


                $insert_id=$this->tc_visiting_model->insertData($tc_visiting);
                if($insert_id)
                {
                    flashToast("insert", "Inserted Successfully!!!");
                }

            }
            else if($this->request->getVar('submit_water'))
            {
                $tc_visiting=array();

                $tc_visiting['related_id']=$inputs['water_conn_id'];
                $tc_visiting['message_id']=$inputs['message_id'];
                $tc_visiting['module']=$inputs['module_water'];
                $tc_visiting['visiting_date']=date('Y-m-d');
                $tc_visiting['created_on']=date('Y-m-d H:i:s');
                $tc_visiting['user_id']=$this->emp_id;

                if($tc_visiting['message_id']==0)
                {
                    $tc_visiting['other_reason']=$inputs['other_reason_water'];
                }


                $insert_id=$this->tc_visiting_model->insertData($tc_visiting);
                if($insert_id)
                {
                    flashToast("insert", "Inserted Successfully!!!");
                }
            }
            else
            {
                $tc_visiting=array();

                $tc_visiting['related_id']=$inputs['license_id'];
                $tc_visiting['message_id']=$inputs['message_id'];
                $tc_visiting['module']=$inputs['module_trade'];
                $tc_visiting['visiting_date']=date('Y-m-d');
                $tc_visiting['created_on']=date('Y-m-d H:i:s');
                $tc_visiting['user_id']=$this->emp_id;
                
                if($tc_visiting['message_id']==0)
                {
                    $tc_visiting['other_reason']=$inputs['other_reason_trade'];
                }


                $insert_id=$this->tc_visiting_model->insertData($tc_visiting);
                if($insert_id)
                {
                    flashToast("insert", "Inserted Successfully!!!");
                }
            }
            
        }

        return view('mobile/water/tc_visiting',$data);

    }

    public function validate_holding()
    {
        if($this->request->getMethod()=='post')
        {

            $ward_id=$this->request->getVar('ward_id');
            $holding_no=$this->request->getVar('holding_no');
            
            $prop_id=$this->property_model->validate_holding($ward_id,strtoupper($holding_no));
            if($prop_id)
            {
                $arr=["response"=>true,"prop_id"=>$prop_id];
            }
            else
            {
                $arr=["response"=>false];
            }

            echo json_encode($arr);
            

        }
    }

    public function validate_saf()
    {

        if($this->request->getMethod()=='post')
        {

            $ward_id=$this->request->getVar('ward_id');
            $saf_no=$this->request->getVar('saf_no');
            
            $saf_id=$this->model_saf_dtl->validate_saf($ward_id,strtoupper($saf_no));
            if($saf_id)
            {
                $arr=["response"=>true,"saf_id"=>$saf_id];
            }
            else
            {
                $arr=["response"=>false];
            }

            echo json_encode($arr);
            

        }
    }
    public function validate_consumer()
    {

        if($this->request->getMethod()=='post')
        {

            $ward_id=$this->request->getVar('ward_id');
            $consumer_no=$this->request->getVar('consumer_no');
            
            $consumer_id=$this->model_water_consumer->validate_consumer($ward_id,strtoupper($consumer_no));
            if($consumer_id)
            {
                $arr=["response"=>true,"consumer_id"=>$consumer_id];
            }
            else
            {
                $arr=["response"=>false];
            }
            
            
            echo json_encode($arr);
            

        }
    }
    public function validate_application()
    {

        if($this->request->getMethod()=='post')
        {

            $ward_id=$this->request->getVar('ward_id');
            $application_no=$this->request->getVar('application_no');
            $water_conn_id=$this->water_conn_model->validate_application($ward_id,strtoupper($application_no));
            if($water_conn_id)
            {
                $arr=["response"=>true,"water_conn_id"=>$water_conn_id];
            }
            else
            {
                $arr=["response"=>false];
            }
           

            echo json_encode($arr);
            

        }
    }
    
    public function validate_license()
    {

        if($this->request->getMethod()=='post')
        {

            $ward_id=$this->request->getVar('ward_id');
            $license_no=$this->request->getVar('license_no');
            $license_id=$this->model_trade_licence->validate_license($ward_id,strtoupper($license_no));
            if($license_id)
            {
                $arr=["response"=>true,"license_id"=>$license_id];
            }
            else
            {
                $arr=["response"=>false];
            }
           

            echo json_encode($arr);
            

        }
    }
  
    
}
