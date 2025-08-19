<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_water_consumer_initial_meter;


class WaterInitialMeterReading extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $model_view_water_consumer;
    protected $model_ward_mstr;
    protected $emp_id;


    public function __construct()
    {

        $session=session();
        $emp_details_id=$session->get('emp_details');
        $this->emp_id=$emp_details_id['id'];

        parent::__construct();
        helper(['db_helper','form']);
        if($db_name = dbConfig("water")){
            $this->db = db_connect($db_name);   

        }
        if($db_name = dbSystem()){
            $this->dbSystem = db_connect($db_name); 
        }
       
        $this->initial_meter_reading_model=new model_water_consumer_initial_meter($this->db);
        

    }
    
    public function insert()
    {

        if($this->request->getMethod()=='post')
        {
           

           
                 $inputs=arrFilterSanitizeString($this->request->getVar());
                 //print_r($inputs);

                 $consumer_initial_reading=array();
                 $consumer_initial_reading['consumer_id']=$inputs['consumer_id'];
                 $consumer_initial_reading['initial_reading']=$inputs['initial_reading'];
                 $consumer_initial_reading['emp_details_id']=$this->emp_id;
                 $consumer_initial_reading['created_on']=date('Y-m-d H:i:s');
                
                 $this->initial_meter_reading_model->insertInitialReading($consumer_initial_reading);

               
               return $this->response->redirect(base_url('WaterViewConsumerMobile/view/'.md5($inputs['consumer_id'])));

           
        }
        
    }

  


}
?>
