<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_view_consumer_deactivation;
use App\Models\model_ward_mstr;
class ConsumerDeactivationReport extends AlphaController
{
    protected $water;
    protected $dbSystem;
    protected $model_view_consumer_deactivation;
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
        $this->model_view_consumer_deactivation = new model_view_consumer_deactivation($this->water);
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
    }
    public function detail()
    {
        $data =(array)null;
        $session = session();
        $ulb_dtl = $session->get('ulb_dtl');
        $ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];
        //Transaction Mode List
        $wardList = $this->model_ward_mstr->getWardListForReport($ulb_mstr_id);
        $data['wardList'] = $wardList;
        if($this->request->getMethod()=='post'){
            $data['from_date'] = $this->request->getVar('from_date');
            $data['to_date'] = $this->request->getVar('to_date');
            $data['ward_mstr_id'] = $this->request->getVar('ward_mstr_id');

            if($data['ward_mstr_id']!=""){
             $data['consumerDeactivationList'] = $this->model_view_consumer_deactivation->getConsumerDeactivationList($data);
            }else{
                $data['consumerDeactivationList'] = $this->model_view_consumer_deactivation->getAllConsumerDeactivationList($data);
            } 
            return view('water/report/consumer_deactivation_report',$data);
        }
        else{
            $data['from_date'] = date('Y-m-d');
            $data['to_date'] = date('Y-m-d');
            $data['consumerDeactivationList'] = $this->model_view_consumer_deactivation->getAllConsumerDeactivationList($data);
            return view('water/report/consumer_deactivation_report',$data);
        } 
    }
}
?>
