<?php 
namespace App\Controllers;
use App\Controllers;
use App\Models\model_prop_dtl;

	class Memo extends AlphaController{
		protected $model_prop_dtl;

		public function __construct()
    {
        parent::__construct();
    	helper(['url', 'db_helper', 'validation_helper', 'validate_saf_helper']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name);
        }
        if ($db_name = dbSystem()) {
            $this->dbSystem = db_connect($db_name); 
        }
        
        $this->model_prop_dtl = new model_prop_dtl($this->db);
       
    }
		public function index($prop_dtl_id_MD5=null){
			// echo $prop_dtl_id;
			// return;
			$prop = $this->model_prop_dtl->get_prop_full_details($prop_dtl_id_MD5);
			$prop = $prop['get_prop_full_details'];
        	$data=json_decode($prop, true);
			// print_var($data);
			// return;
			$session=session();
			$data['ulb_details'] = $session->get('ulb_dtl');
        	// echo '<pre>';print_r($session->get('ulb_dtl'));
			return view('trade/memo',$data);
		}

		public function index2(){
			$session=session();
			$data['ulb_details'] = $session->get('ulb_dtl');
        	// echo '<pre>';print_r($get_emp_details);
			return view('trade/memo2',$data);
		}

	}




 ?>