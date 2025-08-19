<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_view_water_consumer;
use App\Models\model_ward_mstr;
use App\Models\water_consumer_details_model;
use App\Models\water_consumer_demand_model;

use App\Models\WaterPaymentModel;
use App\Models\WaterMeterStatusModel;
use App\Models\WaterConsumerInitialMeterReadingModel;

class WaterViewConsumerDetailsCitizen extends HomeController
{
    protected $water;
    // protected $db;
    protected $dbSystem;
    protected $model_view_water_consumer;
    protected $model_ward_mstr;
    protected $ulb_id;
    protected $emp_id;
    protected $meter_status_model;
    protected $last_reading;

    public function __construct()
    {

        parent::__construct();
        helper(['db_helper','form']);
        $session=session();
        $ulb_details=$session->get('ulb_dtl')??getUlbDtl();;
        //print_r($ulb_details);
        $this->ulb_id=$ulb_details['ulb_mstr_id'];

        $emp_details=$session->get('emp_details');
        $this->emp_id=$emp_details['id'];

        
        if($db_name = dbConfig("water")){
            $this->water = db_connect($db_name);            
        }
        if($db_name = dbSystem()){
            $this->dbSystem = db_connect($db_name); 
        }
         /*if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name); 
        }*/
        $this->model_view_water_consumer = new model_view_water_consumer($this->water);
        $this->ward_model = new model_ward_mstr($this->dbSystem);
        $this->consumer_details_model=new water_consumer_details_model($this->water);

        $this->consumer_demand_model=new water_consumer_demand_model($this->water);

        $this->payment_model=new WaterPaymentModel($this->water);
        $this->meter_status_model=new WaterMeterStatusModel($this->water);
        $this->last_reading=new WaterConsumerInitialMeterReadingModel($this->water);

    }

    public function __destruct()
    {
        if($this->water) $this->water->close();
        if($this->dbSystem) $this->dbSystem->close();
    }
    
    public function index($consumer_id=null)
    {
        $data=array();
       $data['user_type']='';
        $data['consumer_id']=$consumer_id;
        $data['consumer_details']=$this->model_view_water_consumer->waterConsumerDetailsById($consumer_id);
        $data['applicant_details']=$this->payment_model->fetch_all_application_data(md5($data['consumer_details']['apply_connection_id']));
        
        //print_var($data['consumer_details']);die;
        $data['consumer_owner_details']=$this->consumer_details_model->consumerDetailsbyMd5($consumer_id);
        $data['connection_dtls']=$this->meter_status_model->getLastConnectionDetails($data['consumer_details']['id']);
        // because at eo verification default connection meter is set but its doc not uploaded
        $get_last_reading=$this->last_reading->initial_meter_reading($data['consumer_details']['id']);
        $data['last_reading']=$get_last_reading['initial_reading'];
        
        $data['dues']=$this->consumer_demand_model->countPaidStatus($consumer_id);
        return view('citizen/water/water_consumer_details_view', $data);
    }

}
?>
