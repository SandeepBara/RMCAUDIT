<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\model_view_prop_dtl_demand;
use App\Models\model_view_prop_dtl_collection;

class DemandVsCollection extends AlphaController
{
    protected $db;
    protected $model_ward_mstr;
    protected $model_view_prop_dtl_demand;
    protected $model_view_prop_dtl_collection;
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
        $this->model_view_prop_dtl_demand = new model_view_prop_dtl_demand($this->db);
        $this->model_view_prop_dtl_collection = new model_view_prop_dtl_collection($this->db);
    }
    public function report()
    {
        $data =(array)null;
        helper(['form']);
        $session = session();
        $ulb_dtl = $session->get('ulb_dtl');
        $ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];
        //Transaction Mode List
        $wardList = $this->model_ward_mstr->getWardListForReport($ulb_mstr_id);
        $data['wardList'] = $wardList;
        $data['wardResultList'] = $wardList;
        $demandDtl = [];
        if($this->request->getMethod()=='post')
        {
            $ward_mstr_id = $this->request->getVar('ward_mstr_id');
            $data['ward_mstr_id'] = $ward_mstr_id;
            if($ward_mstr_id!="")
            {
                //get Ward By Id 
                $demandDtl[0]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($ward_mstr_id);
                $demandDtl[0]['amount'] = $this->model_view_prop_dtl_demand->getDemandDetails($ward_mstr_id);
                $demandDtl[0]['collection_amount'] = $this->model_view_prop_dtl_collection->getCollectionDetails($ward_mstr_id);
                $data['demandList'] = $demandDtl;
                return view('report/demand_vs_collection',$data);
            }
            else
            {
                foreach ($data['wardList'] as $key => $value)
                {
                    $demandDtl[$key]['ward_no'] = $value['ward_no'];
                    $demandDtl[$key]['amount'] = $this->model_view_prop_dtl_demand->getDemandDetails($value['id']);
                    $demandDtl[$key]['collection_amount'] = $this->model_view_prop_dtl_collection->getCollectionDetails($value['id']);
                }
                $data['demandList'] = $demandDtl;
                return view('report/demand_vs_collection',$data);  
            }
        }
        else
        {
            $from_date = date('Y-m-d');
            foreach ($data['wardList'] as $key => $value)
            {
                $demandDtl[$key]['ward_no'] = $value['ward_no'];
                $demandDtl[$key]['amount'] = $this->model_view_prop_dtl_demand->getDemandDetails($value['id']);
                $demandDtl[$key]['collection_amount'] = $this->model_view_prop_dtl_collection->getCollectionDetails($value['id']);
            }
            $data['demandList'] = $demandDtl;
            return view('report/demand_vs_collection',$data);
        } 
    }
}
?>
