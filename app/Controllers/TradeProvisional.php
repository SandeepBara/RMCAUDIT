<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\model_ulb_mstr;
use App\Models\model_firm_owner_name;
use App\Models\model_apply_licence;
use App\Models\model_provisional_licence;
use App\Models\TradeViewApplyLicenceOwnerModel;

class TradeProvisional extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $property_db;
    protected $db_rmc_trade;
    protected $model_provisional_licence;
    protected $model_ward_mstr;
    protected $model_ulb_mstr;
    protected $model_firm_owner_name;
    


    public function __construct(){
        parent::__construct();
        helper(['db_helper']);
        if($db_name = dbConfig("trade")){
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
       
        


        $this->TradeViewApplyLicenceOwnerModel = new TradeViewApplyLicenceOwnerModel($this->db);
        $this->model_provisional_licence   = new model_provisional_licence($this->db);
        $this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_firm_owner_name = new model_firm_owner_name($this->db);
        
    }

    public function index()
    {
        $data =(array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");

        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        
        $login_emp_details_id = $emp_mstr["id"];

        // $data['application_no'] = $provisional_licence['application_no'];


        //Transaction Mode List
        $wardList = $this->model_ward_mstr->getWardList($data);
        $data['wardList'] = $wardList;


        $application_no = $this->model_provisional_licence->getApplicationList($data);
        $data['application_no'] = $application_no;

        

        // $owner_details = $this->model_firm_owner_name->getOwnerDetails($data);
        // $data['owner_details'] = $owner_details;
        // if error found then comment below
        $where=1;
        if($this->request->getMethod()=='post')
        {

            $inputs = filterSanitizeStringtoUpper($this->request->getVar());
            $data['ward_mstr_id']=$inputs['ward_mstr_id'];
            $data["application no"]=$inputs['application no'];
            
            //print_r($inputs);
            /*$data['keyword']=$inputs['keyword'];  
            $data['likestmt']=[
                'application_no' => strtoupper($inputs['keyword']),
                'holding_no' => strtoupper($inputs['keyword']),
                'applicant_name' => strtoupper($inputs['keyword']),
                'mobile_no' => strtoupper($inputs['keyword'])
            ];         
*/
             
        } 
        else{             
                
            }  

             if($data['ward_mstr_id']==''){

                $data['ward_list']= $this->model_provisional_licence->getWardList($data);

                $data['application_details']=$this->model_provisional_licence->getApplicationList($data);
                 
                $data['ward_details'] = $this->TradeViewApplyLicenceOwnerModel->getWardDetail($data);

                $data['all_details'] =array_merge($data['application_details'], $data['ward_details']);

             }else{
                // $data['application_details']=$this->model_provisional_licence->getWardList($data);
                
             }    

       
       // print_r($data['all_details']);

        return view ('trade/Connection/trade_provisional', $data);
        
       // end error comment



        // echo $owner_details;
        // print_r($owner_details);
        // die();
        
        // $receiver_user_type_id = $emp_mstr["user_type_mstr_id"];
        // $ward="";


        }
    }

