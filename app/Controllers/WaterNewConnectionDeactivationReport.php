<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_view_apply_water_connection_deactivation;
use App\Models\model_ward_mstr;
class WaterNewConnectionDeactivationReport extends AlphaController
{
    protected $water;
    protected $dbSystem;
    protected $model_view_apply_water_connection_deactivation;
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
        $this->model_view_apply_water_connection_deactivation = new model_view_apply_water_connection_deactivation($this->water);
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
    }
    public function detail()
    {
        $data =(array)null;
        $session = session();
        $ulb_dtl = $session->get('ulb_dtl');
        $ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];
        $folder='';
        if($ulb_mstr_id==2)        
            $folder = 'DHANBAD';        
        elseif($ulb_mstr_id==1)
            $folder='RANCHI';
        $data['folder']=$folder;
        //Transaction Mode List
        $wardList = $this->model_ward_mstr->getWardListForReport($ulb_mstr_id);
        $data['wardList'] = $wardList;
        if($this->request->getMethod()=='post')
        {
            $data['from_date'] = $this->request->getVar('from_date');
            $data['to_date'] = $this->request->getVar('to_date');
            $data['ward_mstr_id'] = $this->request->getVar('ward_mstr_id');

            if($data['ward_mstr_id']!=""){
             $data['newConnectionDeactivationList'] = $this->model_view_apply_water_connection_deactivation->getNewConnectionDeactivationList($data);
            }else{
                $data['newConnectionDeactivationList'] = $this->model_view_apply_water_connection_deactivation->getAllNewConnectionDeactivationList($data);
            } 
            return view('water/report/new_connection_deactivation',$data);
        }
        else
        {
            $data['from_date'] = date('Y-m-d');
            $data['to_date'] = date('Y-m-d');
            $data['newConnectionDeactivationList'] = $this->model_view_apply_water_connection_deactivation->getAllNewConnectionDeactivationList($data);
            return view('water/report/new_connection_deactivation',$data);
        } 
    }
}
?>
