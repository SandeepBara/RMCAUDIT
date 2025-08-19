<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\model_saf_demand;

class WardWiseSAFDemand extends AlphaController
{
    protected $db;
    protected $dbSystem;    
    protected $model_ward_mstr;
    protected $model_saf_demand;
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
        $this->model_saf_demand = new model_saf_demand($this->db);
    }
    public function report()
    {
        $data =(array)null;
        helper(['form']);
        $session = session();
        $ulb_dtl = $session->get('ulb_dtl');
        $ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];
        $total =0;
        $demandDtl = [];
        //Transaction Mode List
        $data['wardList'] = $this->model_ward_mstr->getWardListForReport($ulb_mstr_id);
        
        if($this->request->getMethod()=='post')
        {
            $data['ward_mstr_id'] = $this->request->getVar('ward_mstr_id');
            if($data['ward_mstr_id']!=""){
              $demandDtl[0]['demand'] = $this->model_saf_demand->getDemandDetails($data['ward_mstr_id']);
              $demandDtl[0]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($data['ward_mstr_id']);
              $data['total'] = $demandDtl[0]['demand'];
              $data['demandDtl'] = $demandDtl;
            }else{
                foreach ($data['wardList'] as $key => $value) {
                    $demandDtl[$key]['demand'] = $this->model_saf_demand->getDemandDetails($value['id']);
                    $demandDtl[$key]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($value['id']);
                    $data['total'] = $data['total']+$demandDtl[$key]['demand'];

                }
                $data['demandDtl'] = $demandDtl;
            }
            return view('report/ward_wise_saf_demand',$data);
        }
        else
        {
            foreach ($data['wardList'] as $key => $value) {
                    $demandDtl[$key]['demand'] = $this->model_saf_demand->getDemandDetails($value['id']);
                    $demandDtl[$key]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($value['id']);
                    $data['total'] = $data['total']+$demandDtl[$key]['demand'];
            }
            $data['demandDtl'] = $demandDtl;
            return view('report/ward_wise_saf_demand',$data);
        } 
    }
}
?>
