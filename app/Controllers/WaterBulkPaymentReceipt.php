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
use App\Models\model_emp_details;
use App\Models\water_applicant_details_model;


class WaterBulkPaymentReceipt extends AlphaController
{
    protected $db;
    protected $property_db;
    protected $dbSystem;
	protected $model_ulb_mstr;
    protected $user_type;
    //protected $db_name;
    
    
    public function __construct()
    {   
        parent::__construct();
        helper(['db_helper', 'form', 'qr_code_generator_helper']);
        if($db_name = dbConfig("water")){
            //echo $db_name;
            $this->db = db_connect($db_name);            
        }
        if($db_name = dbConfig("property"))
        {
            $this->property_db = db_connect($db_name);
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        }
        
        //$db_name = db_connect("db_rmc_property"); 
        
        $this->conn_fee=new WaterViewConnectionFeeModel($this->db);
        $this->water_conn_dtls=new WaterConnectionDetailsViewModel($this->db);
        $this->apply_waterconn_model=new WaterApplyNewConnectionModel($this->db);
        $this->apply_conn=new WaterApplyNewConnection();
        $this->payment_model=new WaterPaymentModel($this->db);
        $this->transaction_model=new Water_Transaction_Model($this->db);
		$this->modelUlb = new model_ulb_mstr($this->dbSystem);
        $this->site_ins_model=new WaterSiteInspectionModel($this->db);
        $this->modelemp = new model_emp_details($this->dbSystem);
        $this->applicant_details=new water_applicant_details_model($this->db);
       // print_r($this->apply_conn);

    }
    public function bulkPrint(){
        $data=array();
        $printAllData=[];
        $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id']; 
        if($this->request->getMethod()=="post"){
            $inputs = arrFilterSanitizeString($this->request->getVar());  
            $data['from_date'] = $inputs['from_date'];
            $data['to_date'] = $inputs['to_date'];
            if($printData = $this->transaction_model->getWaterBulkPrintData($data)){
                $data['len'] = sizeof($printData);
                foreach ($printData as $key => $value) {
                    $path = base_url('citizenPaymentReceipt/view_transaction_receipt/'.$ulb_mstr_id.'/'.$value['related_id'].'/'.$value['id']);
                    $printAllData[$key]['ss']=qrCodeGeneratorFun($path);
                    $printAllData[$key]['ulb_mstr_name'] = $this->modelUlb->getulb_list($ulb_mstr_id);
                    $printAllData[$key]['applicant_details']=$this->payment_model->fetch_all_application_data(md5($value['related_id']));
                    $printAllData[$key]['transaction_details']=$this->payment_model->transaction_details(md5($value['id']));
                   // print_r($data['transaction_details']);
                    $printAllData[$key]['emp_dtls'] = $this->modelemp->emp_dtls($printAllData[$key]['transaction_details']['emp_details_id']);
                    //print_r($data['emp_dtls']);
                    $printAllData[$key]['applicant_basic_details']=$this->applicant_details->getApplicantsName(md5($value['related_id']));
                }
                $data['printAllData'] = $printAllData;
                return view('water/water_connection/waterBulkPayment',$data);  
            }else{
                return view('water/water_connection/waterBulkPaymentReceipt',$data);  
            }
       }else{
        return view('water/water_connection/waterBulkPaymentReceipt');  
       }
    }
}
