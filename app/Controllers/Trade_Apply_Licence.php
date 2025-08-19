<?php 
namespace App\Controllers;
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
//use App\Models\TradeViewLicenceOwnerrModel;
//use App\Models\model_trade_licence;
use App\Models\TradeViewApplyLicenceOwnerModel;
use App\Models\model_trade_licence_owner_name;
use App\Models\model_trade_view_licence_trade_items;
use App\Models\model_trade_licence_validity;
use App\Models\model_trade_document;
use App\Models\model_trade_provisional_licence;
use App\Models\model_trade_transaction_fine_rebet_details;
use App\Models\Trade_update_dtl_model;
use App\Models\TradeCategoryTypeModel;

class Trade_Apply_Licence extends AlphaController
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
    //protected $TradeViewLicenceOwnerrModel;
    //protected $model_trade_licence;
    protected $TradeViewApplyLicenceOwnerModel;
    protected $model_trade_licence_owner_name;
    protected $model_trade_view_licence_trade_items;
    protected $model_trade_licence_validity;
    protected $model_trade_document;
    protected $model_trade_provisional_licence;
    protected $model_trade_transaction_fine_rebet_details;
    protected $Trade_update_dtl_model;
    protected $TradeCategoryTypeModel;


    public function __construct(){
        
        parent::__construct();
        helper(['db_helper', 'qr_code_generator_helper','form']);
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
        //$this->TradeViewLicenceOwnerrModel = new tradeviewlicenceownerrmodel($this->db);
        //$this->model_trade_licence = new model_trade_licence($this->db);
        $this->TradeViewApplyLicenceOwnerModel= new TradeViewApplyLicenceOwnerModel($this->db);
        $this->model_trade_licence_owner_name = new model_trade_licence_owner_name($this->db);
        $this->model_trade_view_licence_trade_items = new model_trade_view_licence_trade_items($this->db);
        $this->model_trade_licence_validity = new model_trade_licence_validity($this->db);
        $this->model_trade_document = new model_trade_document($this->db);
        $this->model_trade_transaction_fine_rebet_details = new model_trade_transaction_fine_rebet_details($this->db);
        $this->Trade_update_dtl_model = new Trade_update_dtl_model($this->db);
        $this->TradeCategoryTypeModel = new TradeCategoryTypeModel($this->db);
        

    }
    public function index()
    {
       $data=array();
       $data["applicationType"]=$this->tradeapplicationtypemstrmodel->getapplicationTypeList();
        // print_r($data["applicationType"]);
       return view('trade/Connection/tradeapply',$data); 
   }

   public function tradeLevelPendingReport(){
        $data=array();
        // $data["pending_report"]=$this->tradeapplicationtypemstrmodel->getTradeLevelPendingReport();
        $data["pending_report"]=$this->tradeapplicationtypemstrmodel->getTradeLevelPendingReportFYear();
            // print_r($data["pending_report"]);die();
        return view('trade/Reports/trade_level_pending_report',$data); 
    }

    public function applynewlicences()
    {   
            //return view('trade/Connection/applyNewLicence');
        try 
        {
            $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id']; 
            $data =(array)null;
            $data['name']=$name;
            if($this->request->getMethod()=='post')
            {
                $inputs = filterSanitizeStringtoUpper($this->request->getVar());
                
                
                if($inputs['keyword']<>""){
                    $data['keyword']=$inputs['keyword'];
                    $data['licencedet'] = $this->TradeViewLicenceOwnerrModel->getlicencedatabykeyword($data);
                }
                else{
                    $data['from_date']=$inputs['from_date'];
                    $data['to_date']=$inputs['to_date'];
                    $data['licencedet'] = $this->TradeViewLicenceOwnerrModel->getlicencedatabydate($data);
                }
            }
            else
            {
                $data['from_date']=date("d-m-Y");
                $data['to_date']=date("d-m-Y");
                $data['licencedet'] = $this->TradeViewLicenceOwnerrModel->getlicencedatabydate($data);
            }
                //print_r($data['licencedet']);
            return view('trade/Connection/trade_apply_licence_list', $data);
        }
        catch (Exception $e)
        {
            
        }
    }


    public function applynewlicence($id=null)
    {
        $data=array();
        $input=array();
        $session=session();
        $get_ulb_id=$session->get('ulb_dtl');

        $get_emp_details = $session->get('emp_details');
        $data['ulb_mstr_name'] = $this->modelUlb->getulb_list($get_ulb_id["ulb_mstr_id"]);
        
        $short_ulb_name=$data["ulb_mstr_name"]["short_ulb_name"];
        $emp_id=$get_emp_details['id'];
        $ip_address = $get_emp_details['ip_address'];
        $city = $this->modelUlb->getCity($get_ulb_id["ulb_mstr_id"]);
        $data = $this->TradeApplyLicenceModel->getUpdateDataId($id);
        $data["tradeitemlist"] = $this->tradeitemsmstrmodel->gettradeitemsList();
        $data["firmtypelist"] = $this->tradefirmtypemstrmodel->getFirmTypeList(); 
        $data["ownershiptypelist"] = $this->tradeownershiptypemstrmodel->getownershipTypeList();
        $data['ward_list'] =$this->model_ward_mstr->getWardList($get_ulb_id);

        if($data["application_type_id"]==1)
        {
            $data['curdate']=date("Y-m-d");
            $data["id"] = $id;
            $data["statelist"] = $this->statemodel->getstateList();
            $data["idprooflist"] = $this->model_trade_document->getIdPoorfDocumentList();$data["application_type"] = $this->tradeapplicationtypemstrmodel->getapplicationtypeById($data["application_type_id"]); 
        }
        else
        {
            $data["application_type"] = $this->tradeapplicationtypemstrmodel->getapplicationtypeById($data["application_type_id"]); 
            $data["firm_type"] = $this->tradefirmtypemstrmodel->getdatabyid($data["firm_type_id"]); 
            $data["ownership_type"] = $this->tradeownershiptypemstrmodel->getdatabyid($data["ownership_type_id"]); 
            $data["category_type"] = $this->TradeCategoryTypeModel->getdatabyid($data["category_type_id"]); 
            $data["ward"] = $this->model_ward_mstr->getWardNoBywardId($data["ward_mstr_id"]); 
        }
        
        if($this->request->getMethod()=="post")
        {
            $inputs = arrFilterSanitizeString($this->request->getVar());

                // print_var($inputs);die();
            
            if($data["application_type_id"]==1)
            {
                $data2['firmtype_id'] = $inputs['firmtype_id'];
                $data2['ownership_type_id'] = $inputs['ownership_type_id'];
                $data2['id'] = $inputs['id'];
                if(isset($inputs['holding_no']) &&$inputs['holding_no'] != "")
                {                    
                    $data2['holding_no'] = $inputs['holding_no']??null;
                    $data2['prop_dtl_id'] = $inputs['prop_id'];
                }
                $data2['ward_mstr_id'] = $inputs['ward_mstr_id'];
                $data2['new_ward_mstr_id'] = $inputs['new_ward_mstr_id'];
                $data2['firm_name'] = $inputs['firm_name'];
                $data2['area_in_sqft'] = $inputs['area_in_sqft'];
                $data2['firm_date'] = $inputs['firm_date'];
                $data2['firmaddress'] = $inputs['firmaddress'];
                $data2['landmark'] = $inputs['landmark'];
                $data2['pin_code'] = $inputs['pin_code'];
                $data2['owner_name'] = $inputs['owner_name'];
                $data2['owner_name_id'] = $inputs['owner_name_id'];
                $data2['mobile_no'] = $inputs['mobile_no'];
                $data2['emailid'] = $inputs['emailid']??null;
                $data2['address'] = $inputs['address'];
                $data2['idproof'] = $inputs['idproof'] ?? NULL;
                $data2['id_no'] = $inputs['id_no'] ?? NULL;
                $data2['guardian_name'] = $inputs['guardian_name'];
                $data2['updated_by'] = $get_emp_details['id'];
                $data2['reason'] = $inputs['remark'] ?? NULL;
                $data2['updated_date'] = date('Y-m-d');
                $data2['created_on'] = date('Y-m-d H:i:s');
                $data2['firmtype_other'] = $inputs['firmtype_other'];
                $data2['category_type_id'] = $inputs['category_type_id'];
                $data2['premises_owner_name'] = $inputs['owner_business_premises'];
                
                $inputs['applid']  = $this->TradeApplyLicenceModel->getlicenceID($data['id']);
                    # In case of tobbaco nature of business cant modify
                if($data['tobacco_status']==1)
                {
                    $data2['nature_of_bussiness'] = $data['nature_of_bussiness'];
                }
                else
                {
                    $data2['tade_item'] = $inputs['tade_item'];
                    $data2['nature_of_bussiness'] = implode(",", $data2['tade_item']);
                }

                $updateData = $this->TradeApplyLicenceModel->updateNewApplyLicence($data2);

                $len = sizeof($data2['owner_name']);
                for ($i=0; $i <$len ; $i++)
                {
                    $input['owner_id'] =  $data2['owner_name_id'][$i];
                    $input['owner_name'] = $data2['owner_name'][$i];
                    $input['guardian_name'] = $data2['guardian_name'][$i];
                    $input['mobile'] = $data2['mobile_no'][$i];
                    $input['emailid'] = $data2['emailid'][$i];
                    $input['address'] = $data2['address'][$i];
                    if($input['owner_id'])
                        $this->TradeFirmOwnerModel->updatedetails($input);
                    else
                    {
                        $input['apply_licence_id'] =$inputs['applid']['id'];
                        $this->TradeFirmOwnerModel->insertDetails($input);
                    }
                }
            }
            else if($data["application_type_id"]==3)
            {
                $data['id'] = $inputs['id'];
                $data['ward_mstr_id'] = $inputs['ward_mstr_id'];
                $data['firmtype_id'] = $inputs['firmtype_id'];
                $data['ownership_type_id'] = $inputs['ownership_type_id'];
                $data['category_type_id'] = $inputs['category_type_id'];
                $data['firmtype_other'] = $inputs['firmtype_other'];

                $data['owner_name'] = $inputs['owner_name'];
                $data['owner_name_id'] = $inputs['owner_name_id'];
                $data['mobile_no'] = $inputs['mobile_no'];
                $data['emailid'] = $inputs['emailid'];
                $data['address'] = $inputs['address'];
                $data['guardian_name'] = $inputs['guardian_name'];

                    # In case of tobbaco nature of business cant modify
                if($data['tobacco_status']==1)
                {
                    $data['nature_of_bussiness'] = $data['nature_of_bussiness'];
                }
                else
                {
                    $data['tade_item'] = $inputs['tade_item'];
                    $data['nature_of_bussiness'] = implode(",", $data['tade_item']);
                }
                
                $updateData = $this->TradeApplyLicenceModel->updateAmendementLicence($data);


                $len = sizeof($data['owner_name']);
                for ($i=0; $i <$len ; $i++)
                {
                    $input['owner_id'] =  $data['owner_name_id'][$i];
                    $input['owner_name'] = $data['owner_name'][$i];
                    $input['guardian_name'] = $data['guardian_name'][$i];
                    $input['mobile'] = $data['mobile_no'][$i];
                    $input['emailid'] = $data['emailid'][$i];
                    $input['address'] = $data['address'][$i];
                    
                    $this->TradeFirmOwnerModel->updatedetails($input);
                }
            }
            elseif($data["application_type_id"]==2){
                        // echo "application type 2";
                        // print_var($inputs);die();
                $reivedData = array();
                $reivedData['mobile'] = $inputs['mobile_no'][0];
                $reivedData['emailid'] = $inputs['emailid'][0];
                $reivedData['apply_licence_id'] = $inputs['id'];
                $resp = $this->TradeFirmOwnerModel->updateRenewalDetails($reivedData);

                        // print_var($resp);die();
            }
            else
                $this->TradeApplyLicenceModel->update_doc_data($id);
            
            
            if(is_numeric($data['id']))
                $data['id']=md5($data['id']);
            flashToast('update','Application Updated Successfully!!!');
                // if(session()->get('emp_details')['id']==1016)
                // {
                //     // print_var($updateData);die;
                //     return $this->response->redirect(base_url('Trade_Apply_Licence/applynewlicence/'.$data['id']));
                // }
            return $this->response->redirect(base_url('TradeDocument/doc_upload/'.$data['id']));
        }
        else if(isset($id, $id))
        {
                //get Owner Details
            $data['ownerDetails'] = $this->TradeFirmOwnerModel->getdatabyid_md5($id);
                //Get nature Of Bussiness
            $data['tradeItemId'] = $this->TradeApplyLicenceModel->getNatureOfBusinessId($id);
            $data['tradeitemdet']=[];
            if($data['tradeItemId'])
                $data['tradeitemdet'] = $this->tradeitemsmstrmodel->getTradeItemDetails($data['tradeItemId']);

                # In case of tobbaco nature of business you cant add tobacco on update mode
            if($data['tobacco_status']==1)
            {
                $data['nature_of_bussiness'] = $data['nature_of_bussiness'];
            }
            
                //get Category_type_details
            $data['categoryTypeDetails'] = $this->TradeCategoryTypeModel->getCategoryType();
            
                // print_var($data);
            return view('trade/Connection/trade_apply_licence', $data);
        }
    }

    /*Update new licence  added on 08-05-2022*/
    public function updateNewLicence($id=null)
    {
        $data=array();
        $input=array();
        $session=session();
        $get_ulb_id=$session->get('ulb_dtl');

        $get_emp_details = $session->get('emp_details');
        $data['ulb_mstr_name'] = $this->modelUlb->getulb_list($get_ulb_id["ulb_mstr_id"]);
        
        $short_ulb_name=$data["ulb_mstr_name"]["short_ulb_name"];
        $emp_id=$get_emp_details['id'];
        $ip_address = $get_emp_details['ip_address'];
        $city = $this->modelUlb->getCity($get_ulb_id["ulb_mstr_id"]);
        $data = $this->TradeApplyLicenceModel->getUpdateDataId($id);
        $data["tradeitemlist"] = $this->tradeitemsmstrmodel->gettradeitemsList();
        $data["firmtypelist"] = $this->tradefirmtypemstrmodel->getFirmTypeList(); 
        $data["ownershiptypelist"] = $this->tradeownershiptypemstrmodel->getownershipTypeList();
        $data['ward_list'] =$this->model_ward_mstr->getWardList($get_ulb_id);

        if($data["application_type_id"]==1)
        {
            $data['curdate']=date("Y-m-d");
            $data["id"] = $id;
            $data["statelist"] = $this->statemodel->getstateList();
            $data["idprooflist"] = $this->model_trade_document->getIdPoorfDocumentList();$data["application_type"] = $this->tradeapplicationtypemstrmodel->getapplicationtypeById($data["application_type_id"]); 
        }
        else
        {
            $data["application_type"] = $this->tradeapplicationtypemstrmodel->getapplicationtypeById($data["application_type_id"]); 
            $data["firm_type"] = $this->tradefirmtypemstrmodel->getdatabyid($data["firm_type_id"]); 
            $data["ownership_type"] = $this->tradeownershiptypemstrmodel->getdatabyid($data["ownership_type_id"]); 
            $data["category_type"] = $this->TradeCategoryTypeModel->getdatabyid($data["category_type_id"]); 
            $data["ward"] = $this->model_ward_mstr->getWardNoBywardId($data["ward_mstr_id"]); 
        }
        
        if($this->request->getMethod()=="post")
        {
            $inputs = arrFilterSanitizeString($this->request->getVar());
            
            if($data["application_type_id"]==1)
            {
                $data['firmtype_id'] = $inputs['firmtype_id'];
                $data['ownership_type_id'] = $inputs['ownership_type_id'];
                $data['id'] = $inputs['id'];
                $data['holding_no'] = $inputs['holding_no'];
                $data['prop_dtl_id'] = $inputs['prop_id'];
                $data['ward_mstr_id'] = $inputs['ward_mstr_id'];
                $data['new_ward_mstr_id'] = $inputs['new_ward_mstr_id'];
                $data['firm_name'] = $inputs['firm_name'];
                $data['area_in_sqft'] = $inputs['area_in_sqft'];
                $data['firm_date'] = $inputs['firm_date'];
                $data['firmaddress'] = $inputs['firmaddress'];
                $data['landmark'] = $inputs['landmark'];
                $data['pin_code'] = $inputs['pin_code'];
                $data['owner_name'] = $inputs['owner_name'];
                $data['owner_name_id'] = $inputs['owner_name_id'];
                $data['mobile_no'] = $inputs['mobile_no'];
                $data['emailid'] = $inputs['emailid'];
                $data['address'] = $inputs['address'];
                $data['idproof'] = $inputs['idproof'] ?? NULL;
                $data['id_no'] = $inputs['id_no'] ?? NULL;
                $data['guardian_name'] = $inputs['guardian_name'];
                $data['updated_by'] = $get_emp_details['id'];
                $data['reason'] = $inputs['remark'] ?? NULL;
                $data['updated_date'] = date('Y-m-d');
                $data['created_on'] = date('Y-m-d H:i:s');
                $data['firmtype_other'] = $inputs['firmtype_other'];
                $data['category_type_id'] = $inputs['category_type_id'];
                $data['premises_owner_name'] = $inputs['owner_business_premises'];
                
                    # In case of tobbaco nature of business cant modify
                if($data['tobacco_status']==1)
                {
                    $data['nature_of_bussiness'] = $data['nature_of_bussiness'];
                }
                else
                {
                    $data['tade_item'] = $inputs['tade_item'];
                    $data['nature_of_bussiness'] = implode(",", $data['tade_item']);
                }

                $updateData = $this->TradeApplyLicenceModel->updateNewApplyLicence($data);
                $len = sizeof($data['owner_name']);
                for ($i=0; $i <$len ; $i++)
                {
                    $input['owner_id'] =  $data['owner_name_id'][$i];
                    $input['owner_name'] = $data['owner_name'][$i];
                    $input['guardian_name'] = $data['guardian_name'][$i];
                    $input['mobile'] = $data['mobile_no'][$i];
                    $input['emailid'] = $data['emailid'][$i];
                    $input['address'] = $data['address'][$i];
                    
                    $this->TradeFirmOwnerModel->updatedetails($input);
                }
            }
            else if($data["application_type_id"]==3)
            {
                $data['id'] = $inputs['id'];
                $data['ward_mstr_id'] = $inputs['ward_mstr_id'];
                $data['firmtype_id'] = $inputs['firmtype_id'];
                $data['ownership_type_id'] = $inputs['ownership_type_id'];
                $data['category_type_id'] = $inputs['category_type_id'];
                $data['firmtype_other'] = $inputs['firmtype_other'];

                $data['owner_name'] = $inputs['owner_name'];
                $data['owner_name_id'] = $inputs['owner_name_id'];
                $data['mobile_no'] = $inputs['mobile_no'];
                $data['emailid'] = $inputs['emailid'];
                $data['address'] = $inputs['address'];
                $data['guardian_name'] = $inputs['guardian_name'];

                    # In case of tobbaco nature of business cant modify
                if($data['tobacco_status']==1)
                {
                    $data['nature_of_bussiness'] = $data['nature_of_bussiness'];
                }
                else
                {
                    $data['tade_item'] = $inputs['tade_item'];
                    $data['nature_of_bussiness'] = implode(",", $data['tade_item']);
                }
                
                $updateData = $this->TradeApplyLicenceModel->updateAmendementLicence($data);


                $len = sizeof($data['owner_name']);
                for ($i=0; $i <$len ; $i++)
                {
                    $input['owner_id'] =  $data['owner_name_id'][$i];
                    $input['owner_name'] = $data['owner_name'][$i];
                    $input['guardian_name'] = $data['guardian_name'][$i];
                    $input['mobile'] = $data['mobile_no'][$i];
                    $input['emailid'] = $data['emailid'][$i];
                    $input['address'] = $data['address'][$i];
                    
                    $this->TradeFirmOwnerModel->updatedetails($input);
                }
            }
            else
                $this->TradeApplyLicenceModel->update_doc_data($id);
            
            
            if(is_numeric($data['id']))
                $data['id']=md5($data['id']);
            flashToast('update','Application Updated Successfully!!!');
            return $this->response->redirect(base_url('TradeDocument/doc_upload/'.$data['id']));
        }
        else if(isset($id, $id))
        {
                // echo "Dddd";
                //get Owner Details
            $data['ownerDetails'] = $this->TradeFirmOwnerModel->getdatabyid_md5($id);
                //Get nature Of Bussiness
            $data['tradeItemId'] = $this->TradeApplyLicenceModel->getNatureOfBusinessId($id);
            $data['tradeitemdet']=[];
            if($data['tradeItemId'])
                $data['tradeitemdet'] = $this->tradeitemsmstrmodel->getTradeItemDetails($data['tradeItemId']);

                # In case of tobbaco nature of business you cant add tobacco on update mode
            if($data['tobacco_status']==1)
            {
                $data['nature_of_bussiness'] = $data['nature_of_bussiness'];
            }
            
                //get Category_type_details
            $data['categoryTypeDetails'] = $this->TradeCategoryTypeModel->getCategoryType();
            
                // die();
            return view('trade/Connection/trade_apply_licence', $data);
        }
    }
    /* Update new license code ends here*/
    /* Update Licence at any stages */
    public function updateApplicationAtAnyStage($id=null)
    {
        $data=array();
        $input=array();
        $session=session();
        $get_ulb_id=$session->get('ulb_dtl');

        $get_emp_details = $session->get('emp_details');
        $data['ulb_mstr_name'] = $this->modelUlb->getulb_list($get_ulb_id["ulb_mstr_id"]);
        
        $short_ulb_name=$data["ulb_mstr_name"]["short_ulb_name"];
        $emp_id=$get_emp_details['id'];
        $ip_address = $get_emp_details['ip_address'];
        $city = $this->modelUlb->getCity($get_ulb_id["ulb_mstr_id"]);
        $data = $this->TradeApplyLicenceModel->getUpdateDataId($id);
        $data["tradeitemlist"] = $this->tradeitemsmstrmodel->gettradeitemsList();
        $data["firmtypelist"] = $this->tradefirmtypemstrmodel->getFirmTypeList(); 
        $data["ownershiptypelist"] = $this->tradeownershiptypemstrmodel->getownershipTypeList();
        $data['ward_list'] =$this->model_ward_mstr->getWardList($get_ulb_id);

            // print_var($data);die();
        if($data["application_type_id"]==1)
        {
            $data['curdate']=date("Y-m-d");
            $data["id"] = $id;
            $data["statelist"] = $this->statemodel->getstateList();
            $data["idprooflist"] = $this->model_trade_document->getIdPoorfDocumentList();$data["application_type"] = $this->tradeapplicationtypemstrmodel->getapplicationtypeById($data["application_type_id"]); 
        }
        else
        {
            $data["application_type"] = $this->tradeapplicationtypemstrmodel->getapplicationtypeById($data["application_type_id"]); 
            $data["firm_type"] = $this->tradefirmtypemstrmodel->getdatabyid($data["firm_type_id"]); 
            $data["ownership_type"] = $this->tradeownershiptypemstrmodel->getdatabyid($data["ownership_type_id"]); 
            $data["category_type"] = $this->TradeCategoryTypeModel->getdatabyid($data["category_type_id"]); 
            $data["ward"] = $this->model_ward_mstr->getWardNoBywardId($data["ward_mstr_id"]); 
        }
            // print_var($data);
        if($this->request->getMethod()=="post")
        {
            $inputs = arrFilterSanitizeString($this->request->getVar());

                // print_var($inputs);
                //    die();
            
            if($data["application_type_id"]==1)
            {
                    // echo "application type 1";
                
                $data2['firmtype_id'] = $inputs['firmtype_id'];
                $data2['ownership_type_id'] = $inputs['ownership_type_id'];
                $data2['id'] = $inputs['id'];
                $data2['ward_mstr_id'] = $inputs['ward_mstr_id'];
                $data2['new_ward_mstr_id'] = $inputs['new_ward_mstr_id'];
                $data2['firm_name'] = $inputs['firm_name'];
                $data2['area_in_sqft'] = $inputs['area_in_sqft'];
                $data2['firm_date'] = $inputs['firm_date'];
                if(isset($inputs['holding_no']) && $inputs['holding_no'] != "")
                {                    
                    $data2['holding_no'] = $inputs['holding_no'];
                    $data2['prop_dtl_id'] = $inputs['prop_id'];
                }
                $data2['firmaddress'] = $inputs['firmaddress'];
                $data2['landmark'] = $inputs['landmark'];
                $data2['pin_code'] = $inputs['pin_code'];
                $data2['owner_name'] = $inputs['owner_name'];
                $data2['owner_name_id'] = $inputs['owner_name_id'];
                $data2['mobile_no'] = $inputs['mobile_no'];
                $data2['emailid'] = $inputs['emailid'];
                $data2['address'] = $inputs['address'];
                $data2['idproof'] = $inputs['idproof'] ?? NULL;
                $data2['id_no'] = $inputs['id_no'] ?? NULL;
                $data2['guardian_name'] = $inputs['guardian_name'];
                $data2['updated_by'] = $get_emp_details['id'];
                $data2['reason'] = $inputs['remark'] ?? NULL;
                $data2['updated_date'] = date('Y-m-d');
                $data2['created_on'] = date('Y-m-d H:i:s');
                $data2['firmtype_other'] = $inputs['firmtype_other'];
                $data2['category_type_id'] = $inputs['category_type_id'];
                $data2['premises_owner_name'] = $inputs['owner_business_premises'];
                

                $inputs['applid']  = $this->TradeApplyLicenceModel->getlicenceID($data['id']);
                
                    # In case of tobbaco nature of business cant modify
                if($data['tobacco_status']==1)
                {
                    $data2['nature_of_bussiness'] = $data['nature_of_bussiness'];
                }
                else
                {
                    $data2['tade_item'] = $inputs['tade_item'];
                    $data2['nature_of_bussiness'] = implode(",", $data2['tade_item']);
                }

                $updateData = $this->TradeApplyLicenceModel->updateNewApplyLicence($data);
                    // print_r($updateData);die();
                $len = sizeof($data2['owner_name']);

                for ($i=0; $i <$len ; $i++)
                {
                    $input['owner_id'] =  $data2['owner_name_id'][$i];
                    $input['owner_name'] = $data2['owner_name'][$i];
                    $input['guardian_name'] = $data2['guardian_name'][$i];
                    $input['mobile'] = $data2['mobile_no'][$i];
                    $input['emailid'] = $data2['emailid'][$i];
                    $input['address'] = $data2['address'][$i];
                    if($input['owner_id'])
                        $this->TradeFirmOwnerModel->updatedetails($input);
                    else
                    {
                        $input['apply_licence_id'] =$inputs['applid']['id'];
                        $this->TradeFirmOwnerModel->insertDetails($input);
                    }
                }
                    // die();
            }
            else if($data["application_type_id"]==3)
            {
                    // echo "application type 3";
                $data['id'] = $inputs['id'];
                $data['ward_mstr_id'] = $inputs['ward_mstr_id'];
                $data['firmtype_id'] = $inputs['firmtype_id'];
                $data['ownership_type_id'] = $inputs['ownership_type_id'];
                $data['category_type_id'] = $inputs['category_type_id'];
                $data['firmtype_other'] = $inputs['firmtype_other'];

                $data['owner_name'] = $inputs['owner_name'];
                $data['owner_name_id'] = $inputs['owner_name_id'];
                $data['mobile_no'] = $inputs['mobile_no'];
                $data['emailid'] = $inputs['emailid'];
                $data['address'] = $inputs['address'];
                $data['guardian_name'] = $inputs['guardian_name'];

                    # In case of tobbaco nature of business cant modify
                if($data['tobacco_status']==1)
                {
                    $data['nature_of_bussiness'] = $data['nature_of_bussiness'];
                }
                else
                {
                    $data['tade_item'] = $inputs['tade_item'];
                    $data['nature_of_bussiness'] = implode(",", $data['tade_item']);
                }
                
                $updateData = $this->TradeApplyLicenceModel->updateAmendementLicence($data);


                $len = sizeof($data['owner_name']);
                for ($i=0; $i <$len ; $i++)
                {
                    $input['owner_id'] =  $data['owner_name_id'][$i];
                    $input['owner_name'] = $data['owner_name'][$i];
                    $input['guardian_name'] = $data['guardian_name'][$i];
                    $input['mobile'] = $data['mobile_no'][$i];
                    $input['emailid'] = $data['emailid'][$i];
                    $input['address'] = $data['address'][$i];
                    
                    $this->TradeFirmOwnerModel->updatedetails($input);
                }
            }
            elseif($data["application_type_id"]==2){
                        // echo "application type 2";
                        // print_var($inputs);die();
                $reivedData = array();
                $reivedData['mobile'] = $inputs['mobile_no'][0];
                $reivedData['emailid'] = $inputs['emailid'][0];
                $reivedData['apply_licence_id'] = $inputs['id'];
                $resp = $this->TradeFirmOwnerModel->updateRenewalDetails($reivedData);

                        // print_var($resp);die();
            }
            else{
                    // echo "no application type";
                    // print_var($inputs);die();
                $this->TradeApplyLicenceModel->update_doc_data($id);
            }
            
            if(is_numeric($data['id']))
                $data['id']=md5($data['id']);
            flashToast('update','Application Updated Successfully!!!');
            $_SESSION['msgColseWindow'] = "yes";
            return $this->response->redirect(base_url('Trade_Apply_Licence/updateApplicationAtAnyStage/'.$data['id']));
            

        }
        else if(isset($id, $id))
        {
                // echo "Dddd";
                //get Owner Details
            $data['ownerDetails'] = $this->TradeFirmOwnerModel->getdatabyid_md5($id);
                //Get nature Of Bussiness
            $data['tradeItemId'] = $this->TradeApplyLicenceModel->getNatureOfBusinessId($id);
            $data['tradeitemdet']=[];
            if($data['tradeItemId'])
                $data['tradeitemdet'] = $this->tradeitemsmstrmodel->getTradeItemDetails($data['tradeItemId']);

                # In case of tobbaco nature of business you cant add tobacco on update mode
            if($data['tobacco_status']==1)
            {
                $data['nature_of_bussiness'] = $data['nature_of_bussiness'];
            }
            
                //get Category_type_details
            $data['categoryTypeDetails'] = $this->TradeCategoryTypeModel->getCategoryType();
            
                // die(); 
                // print_var($data);
                // die();
            return view('trade/Connection/update_application_at_any_stage', $data);
        }

            // return view('trade/Connection/update_application_at_any_stage', $data);
    }
    /*update code ends here*/


    public function view_transaction_receipt($applyid=null,$transaction_id=null)
    {   

        $data=array();
        $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id']; 
        $path=base_url('citizenPaymentReceipt/view_trade_transaction_receipt/'.$ulb_mstr_id.'/'.$applyid.'/'.$transaction_id);
        $data['ss']=qrCodeGeneratorFun($path);
        $data['transaction_id']=$transaction_id;
        $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id']; 
        $data['ulb_mstr_name'] = $this->modelUlb->getulb_list($ulb_mstr_id);
        $data['applicant_details']=$this->TradeApplyLicenceModel->fetch_all_application_data($applyid);        
        $data['transaction_details']=$this->TradeTransactionModel->transaction_details($transaction_id);
        $warddet=$this->model_ward_mstr->getWardNoBywardId($data['transaction_details']['ward_mstr_id']);
        $data['ward_no']=$warddet["ward_no"];
        $data['status']=[1,2];
        $data['cheque_details']=$this->TradeChequeDtlModel->alltransactioncheque_details($data);

        return view('trade/Connection/payment_receipt',$data);  

    }
    public function getdistrictname()
    {

        if($this->request->getMethod()=="post")
        {

            $data=array();
            $inputs = arrFilterSanitizeString($this->request->getVar());  
                // $state_id=$inputs['state_id'];  
            $state_name=$inputs['state_name']; 
            $statedet = $this->statemodel->getstateid($state_name);

            $state_id=$statedet['id'];  

            $count = $this->districtmodel->getdistrictbystateid($state_id);
            

        }
        return json_encode($count);
    }



    public function trade_licence_view($id)
    {

        $data=array();
        $data['user_type']=$this->user_type;
        
        $data['licencee']=$this->TradeApplyLicenceModel->apply_licence_md5($id);
                //if_exist($data["level"]);
                //print_r($data['licencee']);

                /*if($data['licencee']["property_type"]=='PROPERTY'){
                    $propdet = $this->model_prop_dtl->getDataBySafDtlId($data['licencee']['prop_dtl_id']);
                    $data["holding_no"]=$propdet["holding_no"];
                }elseif($data['licencee']["property_type"]=='SAF'){
                    $safdet = $this->model_saf_dtl->getsafnoBySafDistDtlId($data['licencee']['prop_dtl_id']);
                    $data["holding_no"]=$safdet["saf_no"];
                }*/
                $data["holding_no"]=$data['licencee']["holding_no"];
                $warddet=$this->model_ward_mstr->getWardNoBywardId($data['licencee']['ward_mstr_id']);
                $data['ward_no']=$warddet["ward_no"];



                if($data['licencee']["pending_status"]==1){
                    $data["application_status"]="Licence Created Successfully";
                }else{
                    if(($data['licencee']["payment_status"]>0) and ($data['licencee']["document_upload_status"]==1)){

                    $data["level"]=$this->model_trade_level_pending_dtl->getusertypebyid_md5($id);
                    
                    if($data["level"]["receiver_user_type_id"]==17){
                        $data["application_status"]="Pending At Dealing Assistant";
                    }elseif($data["level"]["receiver_user_type_id"]==18){
                        $data["application_status"]="Pending At Section Head";
                    }elseif($data["level"]["receiver_user_type_id"]==19){
                        $data["application_status"]="Pending At Executive Officer";
                    }elseif($data["level"]["receiver_user_type_id"]==20){
                        $data["application_status"]="Pending At Tax Daroga";
                    }
                    
                }elseif(($data['licencee']["payment_status"]>0) and ($data['licencee']["document_upload_status"]==0)){
                    $data["application_status"]="Payment Done But Document Upload Pending";
                }
            }


            $data['firm_owner']=$this->TradeFirmOwnerModel->getdatabyid_md5($id);            
            // print_r($data['firm_owner']);

            $data['trade_items']=$this->TradeTradeItemsModel->getdatabyid_md5($id);
                //print_r($data['trade_items']);
            $data['trans_detail']=$this->TradeTransactionModel->alltransaction_details($id);

            // print_r($data['trans_detail']);

            return view('trade/connection/trade_licence_details_view',$data);

        }

        
        public function getlicencedetails(){

            if($this->request->getMethod()=="post")
            {

            $data=array();
            $inputs = arrFilterSanitizeString($this->request->getVar());  
            // $data['ward_id']=$inputs['ward_id'];
            $data['licence_no']=$inputs['licence_no'];      
            $data['application_type']=$inputs['application_type'];    

            
            if ($licencedet = $this->TradeViewLicenceOwnerrModel->getdatabylicenceno($data)){


                $response = ['response'=>true, 'dd'=>$licencedet, 'at'=>md5($data['application_type']) ];
            } else {
                $response = ['response'=>false];
            }
        } else {
            $response = ['response'=>false];
        }
        return json_encode($response);
    }


    public function provisional($id=null)
    {
        $data =(array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $ulb_mstr_id = $data['ulb_mstr_id']; 
        $emp_mstr = $Session->get("emp_details");
        /* $data['ulb_mstr_name'] = $this->modelUlb->getulb_list($ulb_mstr["ulb_mstr_id"]);
        $short_ulb_name=$data["ulb_mstr_name"]["short_ulb_name"];
        $prodet = $this->model_trade_provisional_licence->get_pro();
        //print_r($prodet);
        foreach ($prodet as  $value) {

        $paym_arr=array();
        $paym_arr['apply_licence_id']=$value["id"];
        $paym_arr['provisional_date']=date('Y-m-d');
        $paym_arr['created_on']=date('Y-m-d H:i:s');                                                      
        $provid=$this->model_trade_provisional_licence->insertdata($paym_arr);
        $warddet=$this->model_ward_mstr->getWardNoBywardId($data["ward_mstr_id"]);
        $ward_no=$warddet["ward_no"];

        $data['ward_count']=$this->TradeApplyLicenceModel->count_ward_by_wardid($data["ward_mstr_id"]);
        $sl_no = $data['ward_count']['ward_cnt'];
        $sl_noo = $sl_no+1;
        $serial_no = str_pad($sl_noo, 4, "0", STR_PAD_LEFT);
        $ward_nmm = str_pad($ward_nm, 2, "0", STR_PAD_LEFT);
        $prov_no=$short_ulb_name.'P'.$ward_no.$serial_no;
        $this->model_trade_provisional_licence->update_prov_no($provid,$prov_no);
        }*/

        $login_emp_details_id = $emp_mstr["id"];
        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];

        $data['ulb'] = $this->modelUlb->getulb_list($data['ulb_mstr_id']);
        $data['basic_details'] = $this->TradeViewApplyLicenceOwnerModel->getDatabyid($id);         
        $lyear = 365 * $data['basic_details']['licence_for_years'];
        $data["valid_upto"] = date('Y-m-d',strtotime(date("Y-m-d", mktime()) . " + $lyear day"));

        $apply_licence_id=$data['basic_details']['id'];
        $data['licence_dtl'] = $this->model_trade_provisional_licence->getprovisinalno($id);

        //print_r($data['licence_dtl']);
        $data['item_details'] = $this->TradeTradeItemsModel->getdatabyid_md5($id);

        $data['ward']  = $this->model_ward_mstr->getdatabyid($data['basic_details']['ward_mstr_id']);
        //$data['owner_details'] = $this->model_firm_owner_name->applicantdetails($apply_licence_id);
        //print_r($data['prop_dtl']);
        return view('trade/Connection/provisional_licence', $data);

    }
    public function getUpdateData($id=null){
        /*$data['id'] = $id;*/
        $session=session();
        $get_ulb_id=$session->get('ulb_dtl');
        $data = $this->TradeViewApplyLicenceOwnerModel->getUpdateDataId($id);
        $data["statelist"] = $this->statemodel->getstateList();
        $data["idprooflist"] = $this->model_trade_document->getIdPoorfDocumentList();
        $data['ward_list']= $this->model_ward_mstr->getWardList($get_ulb_id);
        $data["firmtypelist"] = $this->tradefirmtypemstrmodel->getFirmTypeList(); 
        $data["ownershiptypelist"] = $this->tradeownershiptypemstrmodel->getownershipTypeList();
        $data["tradeitemlist"] = $this->tradeitemsmstrmodel->gettradeitemsList();

        return view('trade/Connection/trade_apply_licence', $data);
    }
    public function getApplyLicenceDetails(){
        if($this->request->getMethod()=='post'){
            $data['from_date'] = $this->request->getVar('from_date');
            $data['to_date'] = $this->request->getVar('to_date');
            $data['applyLicenceData'] = $this->TradeViewApplyLicenceOwnerModel->getlicencedatabydate($data);
            //print_r($data['applyLicenceData']);
            return view('trade/Connection/trade_apply_licence_list', $data);
        }else{
            $data['from_date'] = date('Y-m-d');
            $data['to_date'] = date('Y-m-d');
            $data['applyLicenceData'] = $this->TradeViewApplyLicenceOwnerModel->getlicencedatabydate($data);
            return view('trade/Connection/trade_apply_licence_list', $data);
        }
    }

    public function updateApplication($id)
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        try{
            $session=session();
            $session=session();
            $get_ulb_id=$session->get('ulb_dtl');
            $get_emp_details = $session->get('emp_details');
            $emp_id=$get_emp_details['id'];
            $data = $this->TradeApplyLicenceModel->getUpdateDataId($id);            
            $lId = $data["id"];
            $data["application_type_list"] = $this->tradeapplicationtypemstrmodel->getapplicationTypeList();
            $data["application_type"] = $this->tradeapplicationtypemstrmodel->getapplicationtypeById($data["application_type_id"]); 
            $data["firm_type"] = $this->tradefirmtypemstrmodel->getdatabyid($data["firm_type_id"]); 
            $data["ownership_type"] = $this->tradeownershiptypemstrmodel->getdatabyid($data["ownership_type_id"]); 
            $data["category_type"] = $this->TradeCategoryTypeModel->getdatabyid($data["category_type_id"]); 
            $data["ward"] = $this->model_ward_mstr->getWardNoBywardId($data["ward_mstr_id"]); 
            $data["tradeitemlist"] = $this->tradeitemsmstrmodel->gettradeitemsList();
            $data["firmtypelist"] = $this->tradefirmtypemstrmodel->getFirmTypeList(); 
            $data["ownershiptypelist"] = $this->tradeownershiptypemstrmodel->getownershipTypeList();
            $data['ward_list'] =$this->model_ward_mstr->getWardList($get_ulb_id);
            $data['categoryTypeDetails'] = $this->TradeCategoryTypeModel->getCategoryType();
            $data['ownerDetails'] = $this->TradeFirmOwnerModel->getdatabyid_md5($id);
            //Get nature Of Bussiness
            $data['tradeItemId'] = $this->TradeApplyLicenceModel->getNatureOfBusinessId($id); 
            $applogsql = " SELECT * 
                           FROM tbl_apply_licence_log_history 
                           WHERE license_id =".$lId;
            $ownerlogsql = " SELECT * 
                            FROM tbl_firm_owner_name_log_history 
                            WHERE license_id =".$lId;
            $data["appLogs"] = $this->db->query($applogsql)->getFirstRow("array"); 
            
            $data["heading"] = $data["appLogs"]?array_filter(array_keys(json_decode($data["appLogs"]["logs"],true)[0]??[]),function($val) {
                return $val;
            }):[];
            
            $logid = $data["appLogs"];
            $data["ownereLogsList"] =  $this->db->query($ownerlogsql)->getRowArray(); 
            $data["wonerHeadings"] = ($data["ownereLogsList"])?array_filter(array_keys(json_decode($data["ownereLogsList"]["logs"],true)[0]??[]),function($val) {
                return $val;
            }):[];  
            if(strtoupper($this->request->getMethod())=='POST')
            {
                $inputs = filterSanitizeStringtoUpper($this->request->getVar());
                $this->db->transBegin();
                $doc_name="";
                if((isset($inputs['owner_dtl_check_box']) && strtolower($inputs["owner_dtl_check_box"])=="on") || (isset($inputs['firm_check_box']) && strtolower($inputs["firm_check_box"])=="on"))
                {
                    $file = $this->request->getFile('suport_doc');
                    $extension = $file->getExtension();
                    $folder = $get_ulb_id["city"]."/Trade/Update_log/";
                    $doc_name = strtotime(date("Y-m-d H:s:i")).".".$extension;
                    $file->move(WRITEPATH."/uploads/".$folder,$doc_name);
                    $doc_name =$folder.$doc_name;
                }
                if(isset($inputs['owner_dtl_check_box']) && strtolower($inputs["owner_dtl_check_box"])=="on")
                {
                    $logs = [];
                    foreach($inputs["owner_name"] as $key=>$val)
                    {
                        $logs["worners"]=[];
                        $wid = $inputs["owner_name_id"][$key]??"";                        
                        $input["owner_name"] = $inputs["owner_name"][$key]??"";
                        $input["guardian_name"] = $inputs["guardian_name"][$key]??"";
                        $input["mobile"] = $inputs["mobile_no"][$key]??"";
                        $input["emailid"] = $inputs["emailid"][$key]??"";
                        $input["address"] = $inputs["address"][$key]??"";                        
                        if($wid)
                        {
                            $logsql = "SELECT * 
                                       FROM tbl_firm_owner_name_log_history 
                                       WHERE owner_id = ".$wid;
                            $logid = $this->db->query($logsql)->getFirstRow("array");
                            
                            $input["owner_id"] = $wid;
                            $oldOwner = $this->TradeFirmOwnerModel->getOwnerById($wid);
                            $oldOwner["license_id"] = $lId;                            
                            $oldOwner["updated_at"] = date("Y-m-d H:s:i");
                            $oldOwner["updated_by"] = $emp_id;
                            $oldOwner["purpush"] = "Ownere Update";
                            $oldOwner["supproting_doc"] = ($doc_name??"");

                            $input["emp_details_id"] = $oldOwner["emp_details_id"];
                            $input["created_on"] = $oldOwner["created_on"];

                            array_push($logs["worners"],$oldOwner);
                            $sql ="";
                            if(!$logid)
                            {
                                $oldOwner["counter"] = 1;
                                $sql = "INSERT into tbl_firm_owner_name_log_history(license_id,owner_id,logs) 
                                        values(".$lId.",".$wid.",'".(json_encode($logs["worners"]))."')";
                            }
                            else
                            {
                                $oldOwner["counter"] = ($logid["logs"]?((array_reverse(json_decode($logid["logs"],true))[0]["counter"])??0):0)+1;                                
                                $sql = "UPDATE tbl_firm_owner_name_log_history SET logs = logs|| '".json_encode($logs["worners"])."'::jsonb
                                        WHERE id =".$logid["id"];
                            }                        
                        }
                        else
                        {
                            $input["apply_licence_id"] = $data["id"];
                            $input["emp_details_id"] = $emp_id;
                            $input["created_on"] = date("Y-m-d");
                            $newid = $this->TradeFirmOwnerModel->insertdata($input); 
                            $newOwnere = $this->TradeFirmOwnerModel->getOwnerById($newid);                            
                            $newOwnere["updated_at"] = date("Y-m-d H:s:i");
                            $newOwnere["license_id"] = $lId;  
                            $newOwnere["updated_by"] = $emp_id;
                            $newOwnere["puspush"] = "New Ownere Add";
                            $newOwnere["supproting_doc"] = ($doc_name??"");
                            $newOwnere["counter"] = 1;
                            $sql = "INSERT into tbl_firm_owner_name_log_history(owner_id,logs) 
                                    values(".$newid.",'".(json_encode($newOwnere))."')";
                        }
                        $sql ? $this->db->query($sql):"";                                                   
                        $id ? $this->TradeFirmOwnerModel->updatedetailsowner($input):""; 
                        // print_var($sql);die;
                    }
                }
                if(isset($inputs['firm_check_box']) && strtolower($inputs["firm_check_box"])=="on")
                {
                    $oldData = $this->TradeApplyLicenceModel->getUpdateDataId($id);
                    $updateData["firm_type_id"]         =   trim($inputs["firmtype_id"])?$inputs["firmtype_id"]:$oldData["firm_type_id"];
                    if($inputs["firmtype_other"])
                    {
                        $updateData["otherfirmtype"]         =   $inputs["firmtype_other"]?$inputs["firmtype_other"]:NUll;
                    }
                    $updateData["application_type_id"]  =   trim($inputs["application_type_id"])?$inputs["application_type_id"]:$oldData["application_type_id"];
                    $updateData["ownership_type_id"]    =   trim($inputs["ownership_type_id"])?$inputs["ownership_type_id"] :$oldData["ownership_type_id"];
                    $updateData["ward_mstr_id"]         =   trim($inputs["ward_mstr_id"])?$inputs["ward_mstr_id"]:$oldData["ward_mstr_id"];
                    $updateData["prop_dtl_id"]          =   trim($inputs["prop_id"])?$inputs["prop_id"]:$oldData["prop_dtl_id"];
                    $updateData["firm_name"]            =   trim($inputs["firm_name"])?$inputs["firm_name"]:$oldData["firm_name"];
                    $updateData["area_in_sqft"]         =   trim($inputs["area_in_sqft"])?$inputs["area_in_sqft"]:$oldData["area_in_sqft"];
                    $updateData["address"]              =   trim($inputs["firmaddress"])?$inputs["firmaddress"]:$oldData["address"];
                    $updateData["landmark"]             =   trim($inputs["landmark"])?$inputs["landmark"]:$oldData["landmark"];
                    $updateData["pin_code"]             =   trim($inputs["pin_code"])?$inputs["pin_code"]:$oldData["pin_code"];
                    $updateData["licence_for_years"]    =   trim($inputs["licence_for_years"])?$inputs["licence_for_years"]:$oldData["licence_for_years"];

                    $updateData["holding_no"]           =   trim($inputs["holding_no"])?$inputs["holding_no"]:$oldData["holding_no"];
                    $updateData["new_ward_mstr_id"]     =   trim($inputs["new_ward_mstr_id"])?$inputs["new_ward_mstr_id"]:$oldData["new_ward_mstr_id"];
                    $updateData["premises_owner_name"]  =   trim($inputs["owner_business_premises"])?$inputs["owner_business_premises"]:$oldData["premises_owner_name"];
                    $updateData["brife_desp_firm"]      =   trim($inputs["brife_desp_firm"])?$inputs["brife_desp_firm"]:$oldData["brife_desp_firm"];
                    $updateData["category_type_id"]     =   trim($inputs["category_type_id"])?$inputs["category_type_id"]:$oldData["category_type_id"];
                    $updateData["establishment_date"]   =   trim($inputs["firm_date"])?$inputs["firm_date"]:$oldData["establishment_date"];
                    $updateData["nature_of_bussiness"]  =   trim(implode(",",$inputs["tade_item"]))?implode(",",$inputs["tade_item"]):$oldData["nature_of_bussiness"];
                    // $updateData["licence_for_years"]    =   $inputs["licence_for_years"];
                    // $updateData["licence_for_years"]    =   $inputs["licence_for_years"];

                    // print_var($updateData);
                    // print_var($oldData);
                    // die;
                    $oldData["updated_at"] = date("Y-m-d H:s:i");
                    $oldData["updated_by"] = $emp_id;
                    $oldData["purpush"] = "License Update";
                    $oldData["supproting_doc"] = ($doc_name??"");
                    $sql ="";

                    if(!$logid)
                    {                        
                        $oldData["counter"] = 1;
                        $sql = "INSERT into tbl_apply_licence_log_history(license_id,logs) 
                                values(".$lId.",'[".(json_encode($oldData))."]')";
                    }
                    else
                    {
                        $oldData["counter"] = ($logid["logs"]?((array_reverse(json_decode($logid["logs"],true))[0]["counter"])??0):0)+1;                        
                        $sql = "UPDATE tbl_apply_licence_log_history SET logs = logs|| '".("[".json_encode($oldData)."]")."'::jsonb
                                WHERE id =".$logid["id"];
                    }
                    // print_var($sql);
                    $this->TradeApplyLicenceModel->updateData($lId,$updateData);
                    // echo $this->db->getLastQuery();
                    $sql ? $this->db->query($sql):"";
                    // print_var($sql);die;
                    
                }
                if($this->db->transStatus()==FALSE)
                {
                    $this->db->transRollback();
                    flashToast("message", "Something errordue to Update!!!");
                    return $this->response->redirect(base_url('Trade_Apply_Licence/updateApplication/'.$id));
                }
                $this->db->transCommit();
                flashToast("message", "Update License Successfully!!!");
                return $this->response->redirect(base_url('Trade_Apply_Licence/updateApplication/'.$id));
            }
            return view('trade/Connection/updateApplication', $data);
        }
        catch(Exception $e)
        {
            $this->db->transRollback();
            flashToast("error", "Some things Wrong"); 
            print_var($e->getMessage());die;
            return redirect()->back()->with('error', "Demand Not Gererated Now Please Wait");
        }
    }

}
?>
