<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\WaterSearchConsumerModel;


class WaterSearchConsumerCitizen extends HomeController
{
    protected $db;
    protected $dbSystem;
    protected $ulb_id;
    protected $emp_id;
    
    //protected $db_name;
    
    
    public function __construct()
    {   

        $session=session();
        $ulb_details=$session->get('ulb_dtl');
        //print_r($ulb_details);
        $this->ulb_id=$ulb_details['ulb_mstr_id'];

        $emp_details=$session->get('emp_details');
        $this->emp_id=$emp_details['id'];


        parent::__construct();
        helper(['db_helper']);
        if($db_name = dbConfig("water")){
            //echo $db_name;
            $this->db = db_connect($db_name);            
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        } 
        
        helper(['form']);
        //$db_name = db_connect("db_rmc_property"); 
        
        $this->ward_model=new model_ward_mstr($this->dbSystem);
        $this->search_consumer_model= new WaterSearchConsumerModel($this->db);
    }
    
    public function index($param)
    {   

        if($param=='search')
        {
            $view="WaterApplyNewConnectionCitizen/water_connection_view/";
        }
        else if($param=='pay')
        {
            $view="WaterPayment/payment/";
        }
        else if($param=='dues')
        {
            $view="WaterViewConnectionCharge/fee_charge/";
        }
        $data=array();
        $data['ulb_mstr_id']=$this->ulb_id;
        $data['ward_list']=$this->ward_model->getWardList($data);
        //print_r($data);
        $data['view']=$view;

        $where=1;
        if($this->request->getMethod()=='post')
        {

            $inputs = filterSanitizeStringtoUpper($this->request->getVar());
            $data['ward_id']=$inputs['ward_id'];
            $data['keyword']=$inputs['keyword'];
            if($data['ward_id']!="")
            {
                $where=" ward_id=".$data['ward_id'];
            }
            if($data['keyword']!="")
            {
                $where=" owner_name like '%".$data['keyword']."%' or mobile_no like '%".$data['keyword']."%' or application_no like '%".$data['keyword']."%'";
            }
            if($data['ward_id']!="" and $data['keyword']!="")
            {

                $where="ward_id=".$data['ward_id']." and (applicant_name like '%".$data['keyword']."%' or mobile_no like '%".$data['keyword']."%' or application_no like '%".$data['keyword']."%')";

            }
            	


        }
       

        $data['application_details']=$this->search_consumer_model->fetch_consumer_details($where);
       //print_r($data['application_details']);

       return view('citizen/water/search_consumer',$data);
       
    }

   
    
    
}