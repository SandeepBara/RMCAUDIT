<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\model_user_hierarchy;
use App\Models\model_tc_activity;
use App\Models\model_tc_activity_water;
use App\Models\model_tc_activity_trade;



class UserActivityDetails extends AlphaController
{
    protected $db;
    protected $model_ward_mstr;
    protected $model_view_prop_dtl_demand;
    protected $model_view_prop_dtl_collection;
    public function __construct(){
        parent::__construct();
    	helper(['db_helper']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name); 
        }
        if($db_name = dbConfig("water")){
            $this->water = db_connect($db_name); 
        }
        if($db_name = dbConfig("trade")){
            $this->trade = db_connect($db_name); 
        }
        
        if($db_name = dbSystem()){
            $this->dbSystem = db_connect($db_name); 
        }
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_user_hierarchy=new model_user_hierarchy($this->dbSystem);
        $this->model_tc_activity=new model_tc_activity($this->db);
        $this->model_tc_activity_water=new model_tc_activity_water($this->water);
        $this->model_tc_activity_trade=new model_tc_activity_trade($this->trade);


    }
    public function report()
    {
        $data =(array)null;
        helper(['form']);
        $session = session();
        $ulb_dtl = $session->get('ulb_dtl');
        $ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];
        $data['curr_date']=date('Y-m-d');
        //Transaction Mode List
      
        $data['userlist']=$this->model_user_hierarchy->getUserListExceptAdmins();
        //print_r($data['userlist']);

        if($this->request->getMethod()=='post')
        {
            
            $inputs = arrFilterSanitizeString($this->request->getVar());
            $data['emp_dtl_id']=$inputs['emp_dtl_id'];
            $data['date_upto']=$inputs['date_upto'];
            $data['date_from']=$inputs['date_from'];
            
            $data['userlists']=$data['userlist'];

            if($data['emp_dtl_id']!="")
            {
                $data['userlists']=$this->model_user_hierarchy->getUserDtls($data['emp_dtl_id']);
                //$data['userlists']=["0"=>array("id"=>$data['emp_dtl_id'])];
            }
            
            

            
           /* $data['form_distribute']=$this->model_tc_activity->form_distribute($data);
            $data['saf_payment']=$this->model_tc_activity->saf_payment($data);
            $data['prop_payment']=$this->model_tc_activity->property_payment($data);
            $data['saf_done']=$this->model_tc_activity->saf_done($data);
            $data['field_verf']=$this->model_tc_activity->field_verification($data);
            $data['geotagged']=$this->model_tc_activity->geotagged($data);
            $data['water_application_applied']=$this->model_tc_activity_water->application_applied($data);
            $data['water_application_payment']=$this->model_tc_activity_water->application_payment($data);
            $data['consumer_payment']=$this->model_tc_activity_water->consumer_payment($data);
            $data['trade_application_applied']=$this->model_tc_activity_trade->application_applied($data);
            $data['trade_application_payment']=$this->model_tc_activity_trade->application_payment($data);
            */

            
            $arr['a']=array();

           // print_r($data['userlists']);

            foreach($data['userlists'] as $key=>$val)
            {
                $data['emp_dtl_id']=$val['id'];
                //echo $val['id'];
                
                $arr['a'][$val['id']]['form_distribute']=$this->model_tc_activity->form_distribute($data);
                $arr['a'][$val['id']]['userlogin']=$this->model_user_hierarchy->user_logins($data);

                $arr['a'][$val['id']]['saf_payment']=$this->model_tc_activity->saf_payment($data);
                $arr['a'][$val['id']]['prop_payment']=$this->model_tc_activity->property_payment($data);
                $arr['a'][$val['id']]['saf_done']=$this->model_tc_activity->saf_done($data);
                $arr['a'][$val['id']]['field_verf']=$this->model_tc_activity->field_verification($data);
                $arr['a'][$val['id']]['geotagged']=$this->model_tc_activity->geotagged($data);
                $arr['a'][$val['id']]['water_application_applied']=$this->model_tc_activity_water->application_applied($data);
                $arr['a'][$val['id']]['water_application_payment']=$this->model_tc_activity_water->application_payment($data);
                $arr['a'][$val['id']]['consumer_payment']=$this->model_tc_activity_water->consumer_payment($data);
                $arr['a'][$val['id']]['trade_application_applied']=$this->model_tc_activity_trade->application_applied($data);
                $arr['a'][$val['id']]['trade_application_payment']=$this->model_tc_activity_trade->application_payment($data);
                
                //print_r($arr['a']);
                //  exit();

            }
           // print_r($arr['a']);
            $data['a']=$arr['a'];

           // $data=array_merge($data,$arr['a']);
            //print_r($data['a']);
           // print_r($data['water_application_applied']);

        }
        else
        {

             $data['date_upto']=date('Y-m-d');
             $data['date_from']=date('Y-m-d');

             $arr['a']=array();
             $data['userlists']=$data['userlist'];

            foreach($data['userlists'] as $key=>$val)
            {
                $data['emp_dtl_id']=$val['id'];
                //echo $val['id'];

                $arr['a'][$val['id']]['form_distribute']=$this->model_tc_activity->form_distribute($data);
                $arr['a'][$val['id']]['userlogin']=$this->model_user_hierarchy->user_logins($data);

                $arr['a'][$val['id']]['saf_payment']=$this->model_tc_activity->saf_payment($data);
                $arr['a'][$val['id']]['prop_payment']=$this->model_tc_activity->property_payment($data);
                $arr['a'][$val['id']]['saf_done']=$this->model_tc_activity->saf_done($data);
                $arr['a'][$val['id']]['field_verf']=$this->model_tc_activity->field_verification($data);
                $arr['a'][$val['id']]['geotagged']=$this->model_tc_activity->geotagged($data);
                $arr['a'][$val['id']]['water_application_applied']=$this->model_tc_activity_water->application_applied($data);
                $arr['a'][$val['id']]['water_application_payment']=$this->model_tc_activity_water->application_payment($data);
                $arr['a'][$val['id']]['consumer_payment']=$this->model_tc_activity_water->consumer_payment($data);
                $arr['a'][$val['id']]['trade_application_applied']=$this->model_tc_activity_trade->application_applied($data);
                $arr['a'][$val['id']]['trade_application_payment']=$this->model_tc_activity_trade->application_payment($data);
                
                //print_r($arr['a']);
                //  exit();

            }
           // print_r($arr['a']);
            $data['a']=$arr['a'];


        }
       
          return view('report/tcvistingreport_new',$data);
            
            
       
      
    }
}
?>
