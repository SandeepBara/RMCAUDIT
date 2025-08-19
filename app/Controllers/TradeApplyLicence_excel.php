<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\TradeFirmTypeMstrModel;
use App\Models\TradeApplicationTypeMstrModel;
use App\Models\TradeOwnershipTypeMstrModel;
use App\Models\TradeItemsMstrModel;
use App\Models\TradeLicenceRateModel;
use App\Models\TradeViewLicenceRateModel;
use App\Models\StateModel;
use App\Models\DistrictModel;
use App\Models\model_ward_mstr;
use App\Models\PropertyModel;
use App\Models\TradeApplyLicenceModel;
use App\Models\TradeFirmOwnerModel;
use App\Models\TradeTradeItemsModel;
use App\Models\TradeTransactionModel;
use App\Models\model_ulb_mstr;
use App\Models\model_saf_dtl;
use App\Models\model_saf_owner_detail;
use App\Models\model_prop_dtl;
use App\Models\model_prop_owner_detail;
use App\Models\TradeChequeDtlModel;
use App\Models\model_trade_level_pending_dtl;
use App\Models\TradeViewLicenceOwnerrModel;
use App\Models\model_trade_licence;
use App\Models\TradeViewApplyLicenceOwnerModel;
use App\Models\model_trade_licence_owner_name;
use App\Models\model_trade_view_licence_trade_items;
use App\Models\model_trade_licence_validity;
use App\Models\model_trade_document;
use App\Models\model_trade_provisional_licence;
use App\Models\model_trade_transaction_fine_rebet_details;
use App\Models\model_bank_recancilation;

class TradeApplyLicence_excel extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $statemodel;
    protected $tradefirmtypemstrmodel;
    protected $tradeapplicationtypemstrmodel;
    protected $tradeownershiptypemstrmodel;
    protected $tradeitemsmstrmodel;
    protected $tradelicenceratemodel;
    protected $tradeviewlicenceratemodel;
    protected $districtmodel;
    protected $model_ward_mstr;
    protected $TradeApplyLicenceModel;
    protected $TradeFirmOwnerModel;
    protected $TradeTradeItemsModel;
    protected $TradeTransactionModel;
    protected $model_ulb_mstr;
    protected $model_saf_dtl;
    protected $model_saf_owner_detail;
    protected $model_prop_dtl;
    protected $model_prop_owner_detail;
    protected $TradeChequeDtlModel;
    protected $model_trade_level_pending_dtl;
    protected $TradeViewLicenceOwnerrModel;
    protected $model_trade_licence;
    protected $TradeViewApplyLicenceOwnerModel;
    protected $model_trade_licence_owner_name;
    protected $model_trade_view_licence_trade_items;
    protected $model_trade_licence_validity;
    protected $model_trade_document;
    protected $model_trade_provisional_licence;
    protected $model_trade_transaction_fine_rebet_details;
    protected $model_bank_recancilation;


    public function __construct(){
        parent::__construct();
    	helper(['db_helper', 'qr_code_generator_helper']);
        if($db_name = dbConfig("trade")){
            $this->db = db_connect($db_name); 
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        }
        if($db_name = dbConfig("property"))
        {
            $this->property_db = db_connect($db_name);
        }
        $this->statemodel = new statemodel($this->dbSystem);
        $this->districtmodel = new districtmodel($this->dbSystem);
        $this->model_ward_mstr=new model_ward_mstr($this->dbSystem);
        $this->property_model=new PropertyModel($this->property_db);
        $this->tradefirmtypemstrmodel = new tradefirmtypemstrmodel($this->db);
        $this->tradeapplicationtypemstrmodel =  new tradeapplicationtypemstrmodel($this->db);
        $this->tradeownershiptypemstrmodel =  new tradeownershiptypemstrmodel($this->db);
        $this->tradeitemsmstrmodel =  new tradeitemsmstrmodel($this->db);
        $this->tradelicenceratemodel =  new tradelicenceratemodel($this->db);
        $this->tradeviewlicenceratemodel =  new tradeviewlicenceratemodel($this->db);
        $this->TradeApplyLicenceModel = new TradeApplyLicenceModel($this->db);
        $this->TradeFirmOwnerModel = new TradeFirmOwnerModel($this->db);
        $this->TradeTradeItemsModel = new TradeTradeItemsModel($this->db);
        $this->TradeTransactionModel = new TradeTransactionModel($this->db);
        $this->modelUlb = new model_ulb_mstr($this->dbSystem);
        $this->model_saf_dtl = new model_saf_dtl($this->property_db);
        $this->model_saf_owner_detail = new model_saf_owner_detail($this->property_db);
        $this->model_prop_dtl = new model_prop_dtl($this->property_db);
        $this->model_prop_owner_detail = new model_prop_owner_detail($this->property_db);
        $this->TradeChequeDtlModel = new TradeChequeDtlModel($this->db);
        $this->model_trade_level_pending_dtl = new model_trade_level_pending_dtl($this->db);
        $this->TradeViewLicenceOwnerrModel = new tradeviewlicenceownerrmodel($this->db);
        $this->model_trade_licence = new model_trade_licence($this->db);
        $this->TradeViewApplyLicenceOwnerModel= new TradeViewApplyLicenceOwnerModel($this->db);
        $this->model_trade_licence_owner_name = new model_trade_licence_owner_name($this->db);
        $this->model_trade_view_licence_trade_items = new model_trade_view_licence_trade_items($this->db);
        $this->model_trade_licence_validity = new model_trade_licence_validity($this->db);
        $this->model_trade_document = new model_trade_document($this->db);
        $this->model_trade_provisional_licence = new model_trade_provisional_licence($this->db);
        $this->model_trade_transaction_fine_rebet_details = new model_trade_transaction_fine_rebet_details($this->db);
        $this->model_bank_recancilation = new model_bank_recancilation($this->db);
        

    }
    public function index()
    {
         $data=array();
         $notinsert=array();
         if(isset($_POST["submit"]))
        {
            $file = $_FILES['profile_image']['tmp_name'];
            $handle = fopen($file, "r");
            $c = 0;//
            $notc=0;
            while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
            {
                $data["application_no"] = $filesop[0];
                $data["curdate"] = $filesop[1];
                $data["firm_type"] = strtoupper($filesop[2]);
                if($data["firm_type"]=='PROPRIETARY'){$firm_type='PROPRIETORSHIP';}elseif($data["firm_type"]=='PARTNERSHIP') {$firm_type='PARTNERSHIP';}elseif ($data["firm_type"]=='PUBLIC LTD.') {$firm_type='PUBLIC LTD.';}elseif ($data["firm_type"]=='PVT LTD') {$firm_type='PVT. LTD.';}elseif ($data["firm_type"]=='OTHERS') {$firm_type='OTHER';}
                $data["firm_type_id"] = $this->tradefirmtypemstrmodel->getIdByfirmtype($firm_type)["id"]; 
                $data["application_type"] = strtoupper($filesop[3]);
                                  
                  
                 
                $data["application_type_id"] = $this->tradeapplicationtypemstrmodel->getIdByapplicationtype($data["application_type"])["id"]; 
                $data["ownership_type"] = strtoupper($filesop[4]);
                $data["ownership_type_id"] = $this->tradeownershiptypemstrmodel->getIdByownershipType($data["ownership_type"])["id"]; 
                $data["ward_no"] = $filesop[5];
                $data['ward_mstr_id']=$this->model_ward_mstr->getIdBywardno($data["ward_no"])["id"]; 
                $data["holding_no"] = $filesop[6];
                $data["firm_name"] = $filesop[7];
                $data["area_sqft"]= $filesop[8];
                $data["k_no"] = $filesop[9];
                $data["bind_book_no"] = $filesop[10];
                $data["account_no"] = $filesop[11];
                $data["firm_date"] = $filesop[12];
                $data["address"] = $filesop[13];
                $data["pin_code"] = $filesop[14];
                $data["licence_for_years"] = $filesop[15];
                 $data["licence_no"] = $filesop[16];
                  $data['emp_details_id']=1;                                    
                    $data['created_on']=date('Y-m-d H:i:s');
                     $data['data_from']='Sparrow';
                //print_r($data);
                $data["apply_licence_id"] = $this->TradeApplyLicenceModel->insertapplyexcel($data);
                
                
                if($data["apply_licence_id"]){

               $c = $c + 1;
                }else{
                    $notinsert[$notc]=$data;
                    $notc++;
                }


                if(!empty($data["licence_no"])){
                    $licence = $this->model_trade_licence->insertdata($data);
                }
                
            }
           echo $notc." Records sucessfully not inserted data !";
           echo "<br>";
            echo $c." Records sucessfully inserted data !";
            echo "<br>";
           echo "<pre>";
           print_r($notinsert);
           echo "</pre>";
                
        }
       return view('trade/Connection/tradeapply_excel',$data); 
    }

   public function ownerexcel()
    {
         $data=array();
         $notinsert=array();
         if(isset($_POST["submit"]))
        {
            $file = $_FILES['profile_image']['tmp_name'];
            $handle = fopen($file, "r");
            $c = 0;//
            $notc=0;
            while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
            {
                $data["application_no"] = $filesop[0];
                $data["apply_licence_id"] = $this->TradeApplyLicenceModel->getIdByapplicationno($data["application_no"])["id"] ;
                $data['owner_name']=$filesop[1];
                $data['guardian_name']=$filesop[2];
                $data['mobile']=$filesop[3];                               
                $data['address']=$filesop[4];                
                $data['pincode']=$filesop[5];
                $data['emp_details_id']=1;                                    
                $data['created_on']=date('Y-m-d H:i:s');
                    
               // print_r($data);
                $data["owner_id"]=$this->TradeFirmOwnerModel->insertdataexcel($data);
               
                
                if($data["owner_id"]){

               $c = $c + 1;
                }else{
                    $notinsert[$notc]=$data;
                    $notc++;
                }

                $c = $c + 1;
                
            }
           echo $notc." Records sucessfully not inserted data !";
           echo "<br>";
            echo $c." Records sucessfully inserted data !";
            echo "<br>";
           /*echo "<pre>";
           print_r($notinsert);
           echo "</pre>";*/
                
        }
       return view('trade/Connection/tradeapplyowner_excel',$data); 
    }

    public function firmexcel()
    {
         $data=array();
         $notinsert=array();
         if(isset($_POST["submit"]))
        {
            $file = $_FILES['profile_image']['tmp_name'];
            $handle = fopen($file, "r");
            $c = 0;//
            $notc=0;
            while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
            {
                   $data["application_no"] = $filesop[0];
                $data["apply_licence_id"] = $this->TradeApplyLicenceModel->getIdByapplicationno($data["application_no"])["id"];
                $data['nature']=$filesop[1];
                $data['trade_items_id']= $this->tradeitemsmstrmodel->getIdByitem($data['nature'])["id"];      
                $data['emp_details_id']=1;                                    
                $data['created_on']=date('Y-m-d H:i:s');
                    
               // print_r($data);
                $data["firm_id"]=$this->TradeTradeItemsModel->insertdataexcel($data);
               
               //die();
                
                if($data["firm_id"]){

               $c = $c + 1;
                }else{
                    $notinsert[$notc]=$data;
                    $notc++;
                }

               // $c = $c + 1;
                
            }
           echo $notc." Records sucessfully not inserted data !";
           echo "<br>";
            echo $c." Records sucessfully inserted data !";
            echo "<br>";
           echo "<pre>";
           print_r($notinsert);
           echo "</pre>";
                
        }
       return view('trade/Connection/tradeapplynature_excel',$data); 
    }


public function transactionexcel()
    {
         $data=array();
         $notinsert=array();
         if(isset($_POST["submit"]))
        {
            $file = $_FILES['profile_image']['tmp_name'];
            $handle = fopen($file, "r");
            $c = 0;//
            $notc=0;
            while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
            {
             
                    
                $data["application_no"] = $filesop[0];
                $data["apply_licence"] = $this->TradeApplyLicenceModel->getIdtyByapplicationno($data["application_no"]);
                $data["apply_licence_id"] = $data["apply_licence"]["id"];
                $data["application_type_id"] = $data["apply_licence"]["application_type_id"];
                 $data["application_type"] = $this->tradeapplicationtypemstrmodel->getapplicationtypeById($data["application_type_id"])["application_type"]; 
                $data["ward_no"] = $filesop[1];
                $data['ward_mstr_id']=$this->model_ward_mstr->getIdBywardno($data["ward_no"])["id"];
                $data['transaction_no']=$filesop[2];
                $data['transaction_date']=$filesop[3];
                $data['payment_mode']=$filesop[4];                            
                $data['paid_amount']=$filesop[5];
                $data['penalty']=$filesop[6];                    
                $data['emp_details_id']=1;                                    
                $data['created_on']=date('Y-m-d H:i:s');
                $data['cheque_no']=$filesop[7]; 
                $data['cheque_date']=$filesop[8]; 
                $data['bank_name']=$filesop[9]; 
                $data['branch_name']=$filesop[10]; 
                $data['bounce_status']=$filesop[11]; 
                $data['bounce_amount']=$filesop[12]; 
                print_r($data);
                if($data['bounce_status']==null){$data['status']=1;}else{$data['status']=$data['bounce_status'];}
                 $transaction_id = $this->TradeTransactionModel->insertdataexcel($data);
                
                
               //die();
                 $payment_status=1;
                 if($transaction_id){
                    if($data['payment_mode']<>'CASH'){
                      $chq_arr=array();
                                $chq_arr['transaction_id']=$transaction_id;
                                $chq_arr['cheque_no']=$data['cheque_no'];
                                $chq_arr['cheque_date']=$data['cheque_date'];
                                $chq_arr['bank_name']=$data['bank_name'];
                                $chq_arr['branch_name']=$data['branch_name'];                  
                                $chq_arr['emp_details_id']=1; 
                                $chq_arr['created_on']=date('Y-m-d H:i:s');
                                $chq_arr['status']=$data['bounce_status'];
                                
                                if($data['bounce_status']==2){
                                    $payment_status=2;
                                    }
                                   $chq_arr['cheque_dtl_id']=$this->TradeChequeDtlModel->insertdata($chq_arr);
                                    $chq_arr["apply_licence_id"]=$data["apply_licence_id"]; 
                                $chq_arr['bounce_amount']=$data['bounce_amount'];

                                
                               
                                $this->TradeChequeDtlModel->insertDatbankaexcel($chq_arr);
                            
                    }
                }

                $this->TradeApplyLicenceModel->update_application_no_excel($data["apply_licence_id"],$payment_status);

                
                if($transaction_id){

               $c = $c + 1;
                }else{
                    $notinsert[$notc]=$data;
                    $notc++;
                }

                //$c = $c + 1;
                
            }
           echo $notc." Records sucessfully not inserted data !";
           echo "<br>";
            echo $c." Records sucessfully inserted data !";
            echo "<br>";
           echo "<pre>";
           print_r($notinsert);
           echo "</pre>";
                
        }
       return view('trade/Connection/tradeapplytransaction_excel',$data); 
    }


}
?>
