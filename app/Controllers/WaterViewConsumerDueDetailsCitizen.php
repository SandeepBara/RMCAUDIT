<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_view_water_consumer;
use App\Models\model_ward_mstr;
use App\Models\water_consumer_details_model;
use App\Models\WaterConsumerDemandModel;
use App\Models\Water_Transaction_Model;


class WaterViewConsumerDueDetailsCitizen extends HomeController
{
    protected $water;
    // protected $db;
    protected $dbSystem;
    protected $model_view_water_consumer;
    protected $model_ward_mstr;
    protected $ulb_id;
    protected $emp_id;
    

    public function __construct(){

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

        $this->consumer_demand_model=new WaterConsumerDemandModel($this->water);
        $this->trans_model=new Water_Transaction_Model($this->water);


    }

    public function __destruct()
    {
        if($this->water) $this->water->close();
        if($this->dbSystem) $this->dbSystem->close();
    }
    
    public function index($consumer_id=null)
    {

        $data=array();

        $data['consumer_id']=$consumer_id;

        $data['consumer_details']=$this->model_view_water_consumer->waterConsumerDetailsById($consumer_id);

        $data['consumer_owner_details']=$this->consumer_details_model->consumerDetailsbyMd5($consumer_id);

        if(!empty($data['consumer_details']) && !isset($data['consumer_details']['id']))
        {
            !empty($data['consumer_details']['id'])?$this->consumer_demand_model->impose_penalty($data['consumer_details']['id']):'';

        }
        $data['dues']=$this->consumer_demand_model->due_demand($consumer_id);

        return view('citizen/water/water_consumer_due_details_view',$data);


    }

    public function transactionDetails($consumer_id)
    {

        $data=array();

        $data['consumer_id']=$consumer_id;

        $data['consumer_details']=$this->model_view_water_consumer->waterConsumerDetailsById($consumer_id);

        $data['consumer_owner_details']=$this->consumer_details_model->consumerDetailsbyMd5($consumer_id);

        $data['dues']=$this->consumer_demand_model->due_demand($consumer_id);

        $data['transaction_details']=$this->trans_model->getConsumerTransactions($consumer_id);
        //print_var($data['consumer_details']);die;


        return view('citizen/water/water_consumer_transaction_details_view',$data);

    }

}
?>
