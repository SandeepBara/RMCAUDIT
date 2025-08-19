<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\model_emp_details;
use App\Models\TCVisitingModel;
use App\Models\model_user_hierarchy;


class TCVisitingDetailReport extends AlphaController
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
        if($db_name = dbSystem()){
            $this->dbSystem = db_connect($db_name); 
        }
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_emp_details=new model_emp_details($this->dbSystem);
        $this->tc_visiting_model=new TCVisitingModel($this->dbSystem);
        $this->model_user_hierarchy=new model_user_hierarchy($this->dbSystem);
        
    }
    public function index()
    {
        $data =(array)null;
        helper(['form']);
        $session = session();
        $ulb_dtl = $session->get('ulb_dtl');
        $ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];
        $data['curr_date']=date('Y-m-d');
        //Transaction Mode List
      
        $data['userlist']=$this->model_emp_details->getTcDetails();
        
        if($this->request->getMethod()=='post')
        {
            
            $inputs = arrFilterSanitizeString($this->request->getVar());
            $data['emp_dtl_id']=$inputs['emp_dtl_id'];
            $data['date_upto']=$inputs['date_upto'];
            $data['date_from']=$inputs['date_from'];
            
        	$where=" visiting_date between '".$data['date_from']."' and '".$data['date_upto']."' and user_id=".$data['emp_dtl_id'];
            
              $arr['a']=array();

           // print_r($data['userlists']);
            if($data['emp_dtl_id']!="")
            {
                 $data['userlists']=$this->model_user_hierarchy->getUserDtls($data['emp_dtl_id']);
            }
            foreach($data['userlists'] as $key=>$val)
            {
                $data['emp_dtl_id']=$val['id'];
                //echo $val['id'];
                
                $arr['a'][$val['id']]['tc_dtl_prop']=$this->tc_visiting_model->getTCvisitingdetailspropertyreport($data['emp_dtl_id']);
                $arr['a'][$val['id']]['tc_dtl_saf']=$this->tc_visiting_model->getTCvisitingdetailssafreport($data['emp_dtl_id']);
                $arr['a'][$val['id']]['tc_dtl_water']=$this->tc_visiting_model->getTCvisitingdetailswaterreport($data['emp_dtl_id']);
                $arr['a'][$val['id']]['tc_dtl_trade']=$this->tc_visiting_model->getTCvisitingdetailstradereport($data['emp_dtl_id']);
                $arr['a'][$val['id']]['tc_dtl_water_cons']=$this->tc_visiting_model->getTCvisitingdetailswaterconsumerreport($data['emp_dtl_id']);
                
                //print_r($arr['a']);
                //  exit();

            }
           // print_r($arr['a']);
            $data['a']=$arr['a'];


        }
        else
        {

             $data['date_upto']=date('Y-m-d');
             $data['date_from']=date('Y-m-d');

             $where=" visiting_date between '".$data['date_from']."' and  '".$data['date_upto']."'";

             $arr['a']=array();
             $data['userlists']=$data['userlist'];
             
            foreach($data['userlists'] as $key=>$val)
            {
                $data['emp_dtl_id']=$val['id'];
                //echo $val['id'];

                $arr['a'][$val['id']]['tc_dtl_prop']=$this->tc_visiting_model->getTCvisitingdetailspropertyreport($data['emp_dtl_id']);
                $arr['a'][$val['id']]['tc_dtl_saf']=$this->tc_visiting_model->getTCvisitingdetailssafreport($data['emp_dtl_id']);
                $arr['a'][$val['id']]['tc_dtl_water']=$this->tc_visiting_model->getTCvisitingdetailswaterreport($data['emp_dtl_id']);
                $arr['a'][$val['id']]['tc_dtl_trade']=$this->tc_visiting_model->getTCvisitingdetailstradereport($data['emp_dtl_id']);
                $arr['a'][$val['id']]['tc_dtl_water_cons']=$this->tc_visiting_model->getTCvisitingdetailswaterconsumerreport($data['emp_dtl_id']);
                

            }
           // print_r($arr['a']);
            $data['a']=$arr['a'];


        }
       		
       	  $data['visiting_detail']=$this->tc_visiting_model->getTCVisitingDetails($where);
       	  //print_r($data['visiting_detail']);

          return view('report/tcvistingdetailreport',$data);
            
            
       
      
    }
    public function demo()
    {
    	echo "hi";

    }
}
?>
