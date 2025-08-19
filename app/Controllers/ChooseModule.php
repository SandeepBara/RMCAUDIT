<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\model_prop_dtl;
use App\Models\model_prop_owner_detail;
use App\Models\model_prop_tax;
use App\Models\model_prop_demand;
use App\Models\model_prop_floor_details;
use App\Models\model_transaction;
use App\Models\model_tran_mode_mstr;
use App\Models\model_ulb_mstr;

class Home extends BaseController
{
	protected $dbSystem;
	protected $model_ulb_mstr;
	
	public function __construct()
    {

    	$session=session();
        helper(['form', 'db_helper']);
        if($dbname = dbSystem()){
            $this->dbSystem = db_connect($dbname); 
        }
		$this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
    }
	
	public function index() {
		$data = [];
		if($ulb_list = $this->model_ulb_mstr->getUlbList()) {
			$data['ulb_list'] = $ulb_list;
		}
		return view('index', $data);
	}

}
