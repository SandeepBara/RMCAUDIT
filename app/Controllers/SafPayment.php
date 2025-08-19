<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\model_ownership_type_mstr;
use App\Models\model_prop_type_mstr;

use App\Models\model_saf_dtl;
use App\Models\model_saf_owner_detail;
use App\Models\model_saf_demand;

use App\Models\model_transaction;

class SafPayment extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $model_ward_mstr;
    protected $model_ownership_type_mstr;
    protected $model_prop_type_mstr;   
    protected $model_saf_dtl;
    protected $model_saf_owner_detail;
    protected $model_saf_demand;
 


    public function __construct(){
        parent::__construct();
    	helper(['db_helper']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name);            
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        }       


         $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);        
        $this->model_ownership_type_mstr = new model_ownership_type_mstr($this->db);
        $this->model_prop_type_mstr = new model_prop_type_mstr($this->db);

        $this->model_saf_dtl = new model_saf_dtl($this->db);
        $this->model_saf_owner_detail = new model_saf_owner_detail($this->db);
        $this->model_saf_demand = new model_saf_demand($this->db);
        
    }

    function __destruct() {
		if(isset($this->db)) $this->db->close();
		if(isset($this->dbSystem)) $this->dbSystem->close();
	}

    public function index(){

        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $ulb_mstr_id = $ulb_mstr["ulb_mstr_id"];   
        $data =(array)null;
        $data['saf_id']=25;        

        //$this->getGet(1);
        //
        $data["safDtl"] = $this->model_saf_dtl->Saf_details($data);
        $data["wardNo"] = $this->model_ward_mstr->ward_list($ulb_mstr_id,$data["safDtl"]);
        $data["ownship_type"] = $this->model_ownership_type_mstr->ownership_type($data["safDtl"]);
        $data["property_type"] = $this->model_prop_type_mstr->property_type($data["safDtl"]);
        $data["owner_details"] = $this->model_saf_owner_detail->owner_details($data);
        $data["demand_details"] = $this->model_saf_demand->demand_details($data);

      /*  print_r($data["owner_details"]);
        die();*/
        

        return view('property/Saf/saf_payment', $data);
    }
    

    public function test(){
        echo "asdasd";
        $query = $this->db->query('select * from tbl_doc_mstr');
        $result = $query->getResult();
        echo '<pre>';
        print_r($result);
    }
}