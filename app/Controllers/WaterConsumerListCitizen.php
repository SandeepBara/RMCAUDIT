<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_view_water_consumer;
use App\Models\model_ward_mstr;

class WaterConsumerListCitizen extends HomeController
{
    protected $water;
    // protected $db;
    protected $dbSystem;
    protected $model_view_water_consumer;
    protected $model_ward_mstr;
    protected $ulb_id;
    protected $emp_id;
    

    public function __construct(){

        $session=session();
        $ulb_details=$session->get('ulb_dtl');
        //print_r($ulb_details);
        $this->ulb_id=$ulb_details['ulb_mstr_id'];

        $emp_details=$session->get('emp_details');
        $this->emp_id=$emp_details['id'];


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
        $this->ward_model = new model_ward_mstr($this->dbSystem);
    }
    
    public function index()
    {

        $data=array();
        $data['ulb_mstr_id']=$this->ulb_id;
        $data['ward_list']=$this->ward_model->getWardList($data);
        
        $data['view']="WaterViewConsumerDetailsCitizen/index/";
        $where=1;
        if($this->request->getMethod()=='post')
        {

            $inputs = filterSanitizeStringtoUpper($this->request->getVar());
            $data['ward_id']=$inputs['ward_id'];
            $data['keyword']=$inputs['keyword'];
            if($data['ward_id']!="" && $data['keyword']=="")
            {
                $where=" ward_mstr_id=".$data['ward_id'];
            }
            if($data['keyword']!="" && $data['ward_id']=="")
            {
                $where=" owner_name like '%".$data['keyword']."%' or mobile_no like '%".$data['keyword']."%' or consumer_no like '%".$data['keyword']."%'";
            }
            if($data['ward_id']!="" and $data['keyword']!="")
            {

                $where="ward_mstr_id=".$data['ward_id']." and (owner_name like '%".$data['keyword']."%' or mobile_no like '%".$data['keyword']."%' or consumer_no like '%".$data['keyword']."%')";

            }
			$data['consumer_details']=$this->model_view_water_consumer->waterConsumerLists($where);
			return view('citizen/water/search_consumer_lists',$data);
        }
       
		return view('citizen/water/search_consumer_lists',$data);
    }

}
?>
