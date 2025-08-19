<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\model_ward_permission;
use App\Models\model_view_emp_details;
use App\Models\model_fy_mstr;
use App\Models\model_tran_mode_mstr;
use App\Models\model_datatable;
use Exception;

//include APPPATH . './Libraries/phpoffice/autoload.php';

class safpropreport extends Controller
{
	protected $db;
	protected $dbSystem;
    protected $model_ward_mstr;
	protected $model_ward_permission;
    protected $model_view_emp_details;
    protected $model_fy_mstr;
    protected $model_tran_mode_mstr;
    protected $model_datatable;

    public function __construct(){
        ini_set('memory_limit', '-1');
        //parent::__construct();
    	helper(['url', 'db_helper', 'validation_helper', 'validate_saf_helper', 'sms_helper', 'php_office_helper']);
        /* if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name);
        }
        if ($db_name = dbSystem()) {
            $this->dbSystem = db_connect($db_name); 
        } */
        $this->db = db_connect('db_rmc_property');
        $this->dbSystem = db_connect('db_system'); 
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
		$this->model_ward_permission = new model_ward_permission($this->dbSystem);
        $this->model_view_emp_details = new model_view_emp_details($this->dbSystem);
        $this->model_fy_mstr = new model_fy_mstr($this->dbSystem);
        $this->model_tran_mode_mstr = new model_tran_mode_mstr($this->db);
        $this->model_datatable = new model_datatable($this->db);
    }
	
	
	
    public function saf_prop_Report() {
        $session = session();
        $ulb_mstr_id = $session->get('ulb_dtl')['ulb_mstr_id'];
        
        $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        $fyList = $this->model_fy_mstr->getfyList();
        $data['wardList'] = $wardList;
        $data['fyList'] = $fyList;
        return view('property/reports/SAFProperty_Individual_demand_collection_Report', $data);
    }
	
	
	public function decision_Making_report() {
        $session = session();
        $ulb_mstr_id = $session->get('ulb_dtl')['ulb_mstr_id'];
        
        $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        $fyList = $this->model_fy_mstr->getfyList();
        $data['wardList'] = $wardList;
        $data['fyList'] = $fyList;
        return view('property/reports/decision_Making_report', $data);
    }
	

}