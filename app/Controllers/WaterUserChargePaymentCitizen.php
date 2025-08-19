<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\WaterViewConnectionFeeModel;
use App\Models\WaterConnectionDetailsViewModel;
use App\Models\WaterApplyNewConnectionModel;
use App\Controllers\WaterApplyNewConnection;
use App\Models\WaterPaymentModel;
use App\Models\Water_Transaction_Model;
use App\Models\model_ulb_mstr;
use App\Models\WaterSiteInspectionModel;
use App\Models\model_water_consumer;
use App\Models\water_consumer_details_model;
use App\Models\model_emp_details;
use App\Models\WaterConsumerCollectionModel;
use App\Models\WaterConsumerDemandModel;
use App\Models\WaterViewConsumerModel;
use App\Models\WaterPenaltyModel;



class WaterUserChargePaymentCitizen extends HomeController
{
    protected $db;
    protected $property_db;
    protected $dbSystem;
	protected $model_ulb_mstr;
    protected $conn_fee;
    protected $water_conn_dtls;
    protected $apply_waterconn_model;
    protected $apply_conn;
    protected $payment_model;
    protected $transaction_model;
    protected $modelUlb;
    protected $site_ins_model;
    protected $consumer_model;
    protected $modelemp;
    protected $collection_model;
    protected $demand_model;
    protected $consumer_details_model;
    protected $consumer_owner_details;
    protected $WaterPenaltyModel;

    //protected $db_name;
    
    
    public function __construct()
    {
        parent::__construct();
        helper(['db_helper', 'form', 'qr_code_generator_helper']);
        $this->session=session();
        if($db_name = dbConfig("water")){
            //echo $db_name;
            $this->db = db_connect($db_name);            
        }
        // if($db_name = dbConfig("property"))
        // {
        //     $this->property_db = db_connect($db_name);
        // }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        }
        
        //$db_name = db_connect("db_rmc_property"); 
        
        $this->conn_fee=new WaterViewConnectionFeeModel($this->db);
        $this->water_conn_dtls=new WaterConnectionDetailsViewModel($this->db);
        $this->apply_waterconn_model=new WaterApplyNewConnectionModel($this->db);
        $this->apply_conn=new WaterApplyNewConnectionCitizen();
        $this->payment_model=new WaterPaymentModel($this->db);
        $this->transaction_model=new Water_Transaction_Model($this->db);
		$this->modelUlb = new model_ulb_mstr($this->dbSystem);
        $this->site_ins_model=new WaterSiteInspectionModel($this->db);
        $this->consumer_model=new model_water_consumer($this->db);
        $this->modelemp = new model_emp_details($this->dbSystem);
        $this->collection_model=new WaterConsumerCollectionModel($this->db);
        $this->demand_model=new WaterConsumerDemandModel($this->db);
        $this->consumer_details_model=new WaterViewConsumerModel($this->db);
        $this->consumer_owner_details=new water_consumer_details_model($this->db);
        $this->WaterPenaltyModel=new WaterPenaltyModel($this->db);

       // print_r($this->apply_conn);

    }

    public function __destruct()
    {
        if($this->db) $this->db->close();
        if($this->dbSystem) $this->dbSystem->close();
    }
    
    

    public function payment_tc_receipt($consumer_id=NULL,$transaction_id=null, $downloadReceipt=false)
    {
        $data=array();
        $session=$this->session;
        $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id']??getUlbDtl()['ulb_mstr_id'];        
        $get_emp_details=$session->get('emp_details');
        $emp_id=$get_emp_details['id'];
        $user_type_mstr_id=$get_emp_details['user_type_mstr_id'];
        $data['user_type']=$user_type_mstr_id;
        //print_var($ulb_mstr_id);
		//$path=base_url('citizenPaymentReceipt/payment_jsk_receipt/'.$ulb_mstr_id.'/'.$transaction_id);
        $path=base_url('citizenPaymentReceipt/view_transaction_receipt/'.$ulb_mstr_id.'/'.$consumer_id.'/'.$transaction_id);
		$data['ss']=qrCodeGeneratorFun($path);
        $data['transaction_id']=$transaction_id;
        $data['consumer_id']=$consumer_id;
        $data['ulb_mstr_name'] = $this->modelUlb->getulb_list($ulb_mstr_id);
        $data['consumer_details']=$this->consumer_details_model->consumerDetails($consumer_id);
        
        $data['consumer_owner_details']=$this->consumer_owner_details->getConsumerDetailsbyMd5($consumer_id);
        $data['transaction_details']=$this->payment_model->transaction_details($transaction_id);
        $data['emp_dtls'] = $this->modelemp->emp_dtls($data['transaction_details']['emp_details_id']);
        $data['downloadReceipt'] = $downloadReceipt;
        $data['meter_reading']=$this->payment_model->meter_reding_for_recipt($data['transaction_details']['id']);
        $data['adjustment_amount'] =  $this->WaterPenaltyModel->get_tbl_adjustment_mstr($data['consumer_details']['id'],$data['transaction_details']['id']);
        $data['advance_amount'] =  $this->WaterPenaltyModel->get_tbl_advance_mstr($data['consumer_details']['id'],$data['transaction_details']['id']);
        $data['from'] ='citizen';
        //print_var($transaction_id);die;
        //return view('citizen/water/user_charge_payment_receipt', $data);
        return view('water/water_connection/user_charge_payment_receipt', $data);
       
    }



}
