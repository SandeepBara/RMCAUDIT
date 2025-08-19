<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\WaterRateChartModel;
use App\Models\WaterPropertyModel;



class WaterRateChartMaster extends AlphaController
{
    protected $db;
    protected $dbSystem;
    
    //protected $db_name;
    
    
    public function __construct()
    {
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
        session();
        //$db_name = db_connect("db_rmc_property"); 
        
        $this->rate_chart_model=new WaterRateChartModel($this->db);
        $this->property_model=new WaterPropertyModel($this->db);
        
    }
    
    public function index()
    {
        $data['rate_chart_list']=$this->rate_chart_model->rate_chart_list();
        $data['user_type_id']=session()->get('emp_details')['user_type_mstr_id'];    
        return view('water/master/RateChart_list',$data);
    }
    
    public function create($id=null)
    {


        $data =(array)null;
        helper(['form']);
        $session=session();
        $emp_details=$session->get('emp_details');
        $session_user_id=$emp_details['id'];
        $data['property_type']=$this->property_model->property_list();
        $data['curr_date']=date('Y-m-d');
        
        if($this->request->getMethod()=='post')
        {
            if($this->request->getVar('id')=="")
            {


                $rules=[

                    'type'=>'required',
                    'property_type_id'=>'required',
                    'range_from'=>'required',
                    'range_upto'=>'required',
                    'effect_date'=>'required',
                    'amount'=>'required',
                   
                    
                ];
                if(!$this->validate($rules)){

                    $data['validation']=$this->validator;
                    return view('water/master/rate_chart_master',$data);
                }
                else
                {

                   
                    //store the data
                
                    $data['type']= $this->request->getVar('type');
                    $data['property_type_id']= $this->request->getVar('property_type_id');
                    $data['range_from']= $this->request->getVar('range_from');
                    $data['range_upto']= $this->request->getVar('range_upto');
                    $data['amount']= $this->request->getVar('amount');
                    $data['effect_date']= $this->request->getVar('effect_date');


                     $data_exist=$this->rate_chart_model->checkdata($data);
                        
                     if($data_exist)
                     {
                        
                        echo "<script>alert('Data Already Exists');</script>";
                       return view('water/master/rate_chart_master',$data);
                     }
                    else{

                       
                       
                        $rate_mstr=array();
                        $rate_mstr['type']=$data['type'];
                        $rate_mstr['property_type_id']=$data['property_type_id'];
                        $rate_mstr['range_from']=$data['range_from'];
                        $rate_mstr['range_upto']=$data['range_upto'];
                        $rate_mstr['amount']=$data['amount'];
                        $rate_mstr['effective_date']=$data['effect_date'];
                        $rate_mstr['emp_details_id']=$session_user_id;
                        $rate_mstr['created_on']=date('Y-m-d H:i:s');
                        
                     
                        if($insert_last_id = $this->rate_chart_model->insertData($rate_mstr)){
                           return $this->response->redirect(base_url('WaterRateChartMaster'));
                        }
                        else{
                       return $this->response->redirect(base_url('WaterRateChartMaster'));
                        }

                    }
                }

            }
            else
            {


                 $rules=[

                    'type'=>'required',
                    'property_type_id'=>'required',
                    'range_from'=>'required',
                    'range_upto'=>'required',
                    'effect_date'=>'required',
                    'amount'=>'required',
                   
                    
                ];
                if(!$this->validate($rules)){

                    $data['validation']=$this->validator;
                    return view('water/master/rate_chart_master',$data);
                }
                else
                {


                    //store the data
                    $data['type']= $this->request->getVar('type');
                    $data['property_type_id']= $this->request->getVar('property_type_id');
                    $data['range_from']= $this->request->getVar('range_from');
                    $data['range_upto']= $this->request->getVar('range_upto');
                    $data['amount']= $this->request->getVar('amount');
                    $data['effect_date']= $this->request->getVar('effect_date');
                    $data['id']= $this->request->getVar('id');
                    
                    
                   
                    $id=$this->request->getVar('id');

                    $data_exist=$this->rate_chart_model->checkdata($data);
                  
                    if($data_exist)
                    {
                        echo "<script>alert('Data Already Exists');</script>";
                        return view('water/master/pipeline_master',$data);
                     }
                    else{

                        $rate_mstr=array();
                        $rate_mstr['type']=$data['type'];
                        $rate_mstr['property_type_id']=$data['property_type_id'];
                        $rate_mstr['range_from']=$data['range_from'];
                        $rate_mstr['range_upto']=$data['range_upto'];
                        $rate_mstr['amount']=$data['amount'];
                        $rate_mstr['effective_date']=$data['effect_date'];
                        $rate_mstr['emp_details_id']=$session_user_id;
                        $rate_mstr['created_on']=date('Y-m-d H:i:s');
                        $rate_mstr['id']=$data['id'];

                        if($insert_last_id = $this->rate_chart_model->updateData($rate_mstr)){
                          
                            return $this->response->redirect(base_url('WaterRateChartMaster'));
                        }
                        else{

                         return $this->response->redirect(base_url('WaterRateChartMaster'));
                        }

                    }
                }

            }
        }
        else if(isset($id))
        {
           
            $getData=$this->rate_chart_model->getData($id);
            //print_r($getData);
            $data['type']=$getData['type'];
            $data['property_type_id']=$getData['property_type_id'];
            $data['range_from']=$getData['range_from'];
            $data['range_upto']=$getData['range_upto'];
            $data['effect_date']=$getData['effective_date'];
            $data['amount']=$getData['amount'];
           
            
            $data['id']=$getData['id'];
            return view('water/master/rate_chart_master',$data);
        }  
        else
        {   

           
            //print_r($data['property_type']);
            return view('water/master/rate_chart_master',$data);
        }
    }

    public function delete($id)
    {
        $this->rate_chart_model->deleteData($id);
        return $this->response->redirect(base_url('WaterRateChartMaster'));
    }
    
    
}
