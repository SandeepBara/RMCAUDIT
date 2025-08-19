<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\WaterConsumerDemandModel;
use App\Models\WaterSearchConsumerMobileModel;

class WaterViewUserChargeMobile extends AlphaController
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
       
        $this->demand_model = new WaterConsumerDemandModel($this->db);
        $this->search_consumer_mobile_model=new WaterSearchConsumerMobileModel($this->db);
    }
    
    public function view_usercharge($consumer_id)
    {
        $data=array();
        $data['consumer_dtls']=$this->search_consumer_mobile_model->getConsumerDetailsbyId($consumer_id);

        $data['due_details']=$this->demand_model->due_demand($consumer_id);
        return view("mobile/water/view_consumer_due_details",$data);
    }


}
?>
