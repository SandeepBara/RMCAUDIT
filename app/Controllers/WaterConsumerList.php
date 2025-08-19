<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_view_water_consumer;
use App\Models\model_ward_mstr;

class WaterConsumerList extends AlphaController
{
    protected $water;
    // protected $db;
    protected $dbSystem;
    protected $model_view_water_consumer;
    protected $model_ward_mstr;
    protected $ulb_id;
    protected $emp_id;
    

    public function __construct(){
        parent::__construct();
        helper(['db_helper','form']);
        $session=session();
        $ulb_details = getUlbDtl();
        $this->ulb_id=$ulb_details['ulb_mstr_id'];

        $emp_details=$session->get('emp_details');
        $this->emp_id=$emp_details['id'];


        
        
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
    public function __destruct()
    {
        if($this->water)
        {
            $this->water->close();
        }
        if($this->dbSystem)
        {
            $this->dbSystem->close();            
        }
    }
    public function index($param)
    {
        $data=array();
        $data['ulb_mstr_id']=$this->ulb_id;
        $data['ward_list']=$this->ward_model->getWardList($data);
        $data['consumer_details']=[];
        //print_var($data);
        
        if($param=='search')
        {
            $data['view']="WaterViewConsumerDetails/index/";

        }
        else if($param=='update_conn_type')
        {
            $data['view']="WaterUpdateConsumerConnectionJsk/index/";
        }
        elseif($param=='update_meter')
        {
            $data['view']="WaterUpdateConsumerConnectionMeterDoc/search_consumer/";
        }
        
        elseif($param=='consumer_owner_dtl')
        {
            $data['view']="WaterUpdateConsumerConnectionMeterDoc/consumer_owner_dtl/";
        }
        elseif($param=='consumer_basice_dtl')
        {
            $data['view']="WaterUpdateConsumerConnectionMeterDoc/consumer_basice_dtl/";
        }
        elseif($param=='last_meter_dtl')
        {
            $data['view']="WaterUpdateConsumerConnectionMeterDoc/last_meter_dtl/";
        }
        elseif($param=='name_trasfer')
        {
            $data['view']="WaterUpdateConsumerConnectionMeterDoc/name_trasfer/";
        }
        elseif($param=='update')
        {
            $data['view']="WaterUpdateConsumerConnectionMeterDoc/update/";
            if($this->emp_id!=1)
                $data['view']="WaterUpdateConsumerConnectionMeterDoc/update2/";
        }
        elseif($param=='update_connection_date')
        {
            $data['view']="WaterUpdateConsumerConnectionMeterDoc/update_connection_date/";
        }
        elseif($param=='update_meter_connection_date')
        {
            $data['view']="WaterUpdateConsumerConnectionMeterDoc/update_meter_connection_date/";
        }
        elseif($param=='uplodeExisting')
        {
            $data['view']="WaterUpdateConsumerConnectionMeterDoc/uplodeExisting/";  
        }
        elseif($param=='AlterGovFixedChage')
        {
            $data['view']="WaterUpdateConsumerConnectionMeterDoc/AlterGovFixedChage/";  
        }
        elseif($param=='deactivateUnpaidDeamands')
        {
            $data['view']="WaterUpdateConsumerConnectionMeterDoc/deactivateUnpaidDeamands/";  
        }
        elseif($param=="AverageBilling")
        {
            $data['view']="WaterUpdateConsumerConnectionMeterDoc/odershitArvBilling/";  
        }
        elseif($param=="consumerRequest")
        {
            $data['view']="waterConsumerRequest/applyRequest/";  
        }
        
        if($this->request->getMethod()=='post')
        {

            $inputs = filterSanitizeStringtoUpper($this->request->getVar());
            $data['ward_id']=$inputs['ward_id'];
            $data['keyword']=$inputs['keyword'];
            if($data['ward_id']!="")
            {
                $where=" ward_mstr_id=".$data['ward_id'];
            }
            if($data['keyword']!="")
            {
                $where=" owner_name like '%".$data['keyword']."%' or mobile_no like '%".$data['keyword']."%' or consumer_no like '%".$data['keyword']."%'  or holding_no like '%".$data['keyword']."%' ";
            }
            if($data['ward_id']!="" and $data['keyword']!="")
            {

                $where=" ward_mstr_id=".$data['ward_id']." and (owner_name like '%".$data['keyword']."%' or mobile_no like '%".$data['keyword']."%' or consumer_no like '%".$data['keyword']."%'  or holding_no like '%".$data['keyword']."%' )";

            }
            if($param=='uplodeExisting')
            {
                $where .= " AND apply_from ='Existing' ";
                $data['consumer_details']=$this->model_view_water_consumer->waterConsumerLists($where);
            }
            else
                $data['consumer_details']=$this->model_view_water_consumer->waterConsumerLists($where);
        }
        //print_r($data['consumer_details']);
        return view('water/water_connection/search_consumer_lists', $data);
    }

}
?>
