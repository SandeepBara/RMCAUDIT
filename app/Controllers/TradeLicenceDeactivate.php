<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\model_ulb_mstr;
use App\Models\TradeApplyLicenceModel;
use App\Models\TradeFirmOwnerModel;
use App\Models\TradeTransactionDeactivateModel;
use App\Models\model_prop_dtl;
use App\Models\model_trade_licence;
use App\Models\model_trade_licence_owner_name;
use App\Models\TradeLicenceDeactivateModel;

class TradeLicenceDeactivate extends AlphaController
{
    protected $trade;
    protected $dbSystem;
    protected $property;
    protected $model_ward_mstr;
    protected $model_ulb_mstr;
    protected $TradeApplyLicenceModel;
    protected $TradeFirmOwnerModel;
    protected $TradeTransactionDeactivateModel;
    protected $model_prop_dtl;
    protected $model_trade_licence;
    protected $model_trade_licence_owner_name;
    protected $TradeLicenceDeactivateModel;
    
    public function __construct(){
        parent::__construct();
        helper(['db_helper','form']);
        if($db_name = dbConfig("trade")){
            $this->trade = db_connect($db_name); 
        }
        if($db_name = dbSystem()){
            $this->dbSystem = db_connect($db_name); 
        }
        if($db_name = dbConfig("property")){
            $this->property = db_connect($db_name); 
        }
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
        $this->TradeApplyLicenceModel = new TradeApplyLicenceModel($this->trade);
        $this->TradeFirmOwnerModel = new TradeFirmOwnerModel($this->trade);
        $this->TradeTransactionDeactivateModel = new TradeTransactionDeactivateModel($this->trade);
        $this->model_trade_licence = new model_trade_licence($this->trade);
        $this->model_trade_licence_owner_name = new model_trade_licence_owner_name($this->trade);
        $this->TradeLicenceDeactivateModel = new TradeLicenceDeactivateModel($this->trade);
        $this->model_prop_dtl = new model_prop_dtl($this->property);

    }
    public function detail($licence_no=null)
    {
        $data =(array)null;
        $tradeLicenceList = [];
        if($this->request->getMethod()=='post')
        {
            //Water Transaction Details
            $inputs = arrFilterSanitizeString($this->request->getVar());
            $data['licence_no'] = $inputs['licence_no'];
            $licenceNoData = md5($data['licence_no']);
           if ($licenceDetails = $this->model_trade_licence->getLicenceByLicenceNo($licenceNoData)) {
                $tradeLicenceList[0]['id'] = $licenceDetails['id'];
                $tradeLicenceList[0]['licence_no'] = $licenceDetails['licence_no'];
                $tradeLicenceList[0]['application_no'] = $licenceDetails['application_no'];
                $tradeLicenceList[0]['firm_name'] = $licenceDetails['firm_name'];
                $tradeLicenceList[0]['establishment_date'] = $licenceDetails['establishment_date'];
                $tradeLicenceList[0]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($licenceDetails['ward_mstr_id']);
            } else {
                $data['validation'] = "Record Does Not Exists";
            }
            $data['tradeLicenceList'] = $tradeLicenceList;
            return view('trade/Connection/licence_deactivate',$data);
        }
        else if(isset($licence_no)){
           if ($licenceDetails = $this->model_trade_licence->getLicenceByLicenceNo($licence_no)) {
                $tradeLicenceList[0]['id'] = $licenceDetails['id'];
                $tradeLicenceList[0]['licence_no'] = $licenceDetails['licence_no'];
                $data['licence_no'] = $licenceDetails['licence_no'];
                $tradeLicenceList[0]['application_no'] = $licenceDetails['application_no'];
                $tradeLicenceList[0]['firm_name'] = $licenceDetails['firm_name'];
                $tradeLicenceList[0]['establishment_date'] = $licenceDetails['establishment_date'];
                $tradeLicenceList[0]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($licenceDetails['ward_mstr_id']);
            } else {
                $data['validation'] = "Record Does Not Exists";
            }
            $data['tradeLicenceList'] = $tradeLicenceList;
            return view('trade/Connection/licence_deactivate',$data);
        }
        else
        {
           return view('trade/Connection/licence_deactivate',$data);
        } 
    }
    public function create(){
        $session = session();
        $emp_details = $session->get('emp_details');
        $ulb_dtl = $session->get('ulb_dtl');
        $emp_details_id = $emp_details['id'];
        $city = $this->model_ulb_mstr->getCity($ulb_dtl['ulb_mstr_id']);
        if($this->request->getMethod()=='post'){
            $input = [
                        'reason' => $this->request->getVar('remark'),
                        'deactivate_date' =>date('Y-m-d'),
                        'created_on' =>date('Y-m-d H:i:s'),
                        'licence_id' => $this->request->getVar('licence_id'),
                        'deactivated_by' =>$emp_details_id
                    ];
            $insert_id = $this->TradeLicenceDeactivateModel->insertDeactivateData($input);
            if($insert_id){
                $rules = ['doc_path' => 'uploaded[doc_path]|max_size[doc_path,5124]|ext_in[doc_path,pdf]'];
                if($this->validate($rules))
                {
                    $file = $this->request->getFile('doc_path');
                    $extension = $file->getExtension();
                    if($file->isValid() && !$file->hasMoved()){
                        $newName = md5($insert_id).".".$extension;
                       
                        if($file->move(WRITEPATH.'uploads/'.$city['city'].'/trade_licence_deactivate',$newName))
                        {
                            $this->TradeLicenceDeactivateModel->uploadDocument($newName,$insert_id);
                        }
                    }
                }
                $this->model_trade_licence->updateDeativateStatus($input['licence_id']);
                 flashToast('deactivate', 'Licence Deactivated Successfully!!');
               return $this->response->redirect(base_url('TradeLicenceDeactivate/detail'));
            }else{
               flashToast('deactivate', 'Something Is Wrong!!');
                return $this->response->redirect(base_url('TradeLicenceDeactivate/detail'));
            }
        }
    }
    public function view($id=null){
        $data =(array)null;
        $data['id'] = $id;
        $data['basic_details'] = $this->model_trade_licence->getLicenceById($data);
        $data['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($data['basic_details']['ward_mstr_id']);
        $data['holding_no'] = $this->model_prop_dtl->getPropdetails($data['basic_details']['prop_dtl_id']);
        $data['ownerDetails'] = $this->model_trade_licence_owner_name->getLicenceDetails($data['basic_details']['id']);
        return view('trade/Connection/licence_deactivate_view',$data);
    }
}
?>
