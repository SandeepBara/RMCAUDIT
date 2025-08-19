<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_view_water_consumer;
use App\Models\model_ward_mstr;

class WaterConsumer extends AlphaController
{
    protected $water;
   // protected $db;
    protected $dbSystem;
    protected $model_view_water_consumer;
    protected $model_ward_mstr;
    public function __construct(){
        parent::__construct();
        helper(['db_helper','form']);
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
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
    }
    public function report()
    {
        $data =(array)null;
        $session = session();
        $ulb_dtl = $session->get('ulb_dtl');
        $ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];
        //Transaction Mode List
        $data['wardList'] = $this->model_ward_mstr->getWardListForReport($ulb_mstr_id);
        if($this->request->getMethod()=='post')
        {
            $data['from_date'] = $this->request->getVar('from_date');
            $data['to_date'] = $this->request->getVar('to_date');
            $data['ward_mstr_id'] = $this->request->getVar('ward_mstr_id');
            if($data['ward_mstr_id']!=""){
                $data['consumerList'] = $this->model_view_water_consumer->consumerList($data);
                //print_r($data['consumerList']);
            }else{
               $data['consumerList'] = $this->model_view_water_consumer->consumerListByDate($data);
            }
            return view('water/report/water_consumer',$data);
        }else{
                $data['from_date'] = date('Y-m-d');
                $data['to_date'] = date('Y-m-d');
                $data['consumerList'] = $this->model_view_water_consumer->consumerListByDate($data);
            return view('water/report/water_consumer',$data);
        }
    }
}
?>
