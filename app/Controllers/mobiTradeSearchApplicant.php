<?php 
namespace App\Controllers;

use App\Models\model_view_ward_permission;
use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\TradeViewApplyLicenceOwnerModel;


class mobiTradeSearchApplicant extends MobiController
{
    protected $db;
    protected $dbSystem;
    protected $ulb_id;
    protected $emp_id;
    protected $ward_model;
    protected $TradeViewApplyLicenceOwnerModel;
    protected $model_view_ward_permission;
    
    public function __construct()
    {


        $session=session();
        $ulb_details=$session->get('ulb_dtl');
        $this->ulb_id=$ulb_details['ulb_mstr_id'];

        $emp_details=$session->get('emp_details');
        $this->emp_id=$emp_details['id'];


        parent::__construct();
        helper(['db_helper']);
        if($db_name = dbConfig("trade")){
            $this->db = db_connect($db_name);            
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        } 
        
        helper(['form']);
        $this->ward_model=new model_ward_mstr($this->dbSystem);
        $this->TradeViewApplyLicenceOwnerModel= new TradeViewApplyLicenceOwnerModel($this->db);
        $this->model_view_ward_permission = new model_view_ward_permission($this->dbSystem);
    }

    function __destruct() {
		$this->db->close();
        $this->dbSystem->close();
	}
    
    public function index()
    {
        $session = session();
        $get_emp_details = $session->get('emp_details');
        $data = filterSanitizeStringtoUpper($this->request->getVar());
        if($get_emp_details["user_type_mstr_id"]==5)        
        {
            $emp_id = $get_emp_details['id'];
            $ward_list = $this->model_view_ward_permission->getPermittedWard($emp_id);
            $data['ward_list'] =  array_map(function($val){
                return["id"=>$val["ward_mstr_id"],"ward_no"=>$val['ward_no']];
            },$ward_list);

            $wardIds = array_map(function($val){
                return $val["id"];
            },$data["ward_list"]);
            $wardIds = implode(",",$wardIds);
            $data["ward_id"] = $wardIds;
          
        }
        if (isset($data["keyword"]) && isset($data["from_date"]) && isset($data["to_date"])) {
            $data["fromdate"] = $data["from_date"];
            $data["todate"] = $data["to_date"];
            if(isset($data['keyword']) && $data['keyword'])
            { 
                $data['application_details']=$this->TradeViewApplyLicenceOwnerModel->fetch_aaplication_details_by_keyword($data);   
            } else {
                $data['application_details']=$this->TradeViewApplyLicenceOwnerModel->fetch_aaplication_details($data);
            } 
        } else {
            $data["fromdate"]=date('Y-m-d');
            $data["todate"]=date('Y-m-d');  
        }
        $data['ulb_mstr_id']=$this->ulb_id;
        $data['ward_list']=$this->ward_model->getWardList($data); 

        return view('mobile/trade/SearchApplicant', $data);
    }
    
    
    
    
}
