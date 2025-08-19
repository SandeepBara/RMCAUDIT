<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\model_ulb_mstr;
use App\Models\model_view_ward_permission;
use App\Models\model_view_trade_level_pending;
use App\Models\model_firm_owner_name;
use App\Models\model_application_doc;
use App\Models\model_trade_level_pending_dtl;
use App\Models\model_apply_licence;
use App\Models\model_view_trade_licence;
use App\Models\model_view_application_doc;

use App\Models\model_trade_licence_validity;
use App\Models\model_trade_item_mstr;
use App\Models\model_prop_dtl;
use App\Models\model_prop_owner_detail;
use App\Models\model_trade_licence_owner_name;
use App\Models\model_trade_licence_trade_items;
use App\Models\TradeTaxdarogaVerificationModel;
use App\Models\TradeTaxdarogaDocumentVerificationModel;
use App\Models\TradeSiteInspectionMessageModel;
use App\Models\model_application_type_mstr;
use App\Models\model_trade_items_mstr;
use App\Models\TradeTransactionModel;
use App\Models\TradeChequeDtlModel;
use App\Models\trade_view_application_doc_model;
use Exception;

class Trade_SI extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $property_db;
    protected $model_ward_mstr;
    protected $model_ulb_mstr;
    protected $model_view_ward_permission;
    protected $model_view_trade_level_pending;
    protected $model_firm_owner_name;
    protected $model_application_doc;
    protected $model_trade_level_pending_dtl;
    protected $model_apply_licence;
    protected $model_view_trade_licence;
    protected $model_view_application_doc;
    
    protected $model_trade_licence_validity;
    protected $model_trade_item_mstr;
    protected $model_prop_dtl;
    protected $model_prop_owner_detail;
    protected $model_trade_licence_owner_name;
    protected $model_trade_licence_trade_items;
    protected $TradeTaxdarogaVerificationModel;
    protected $TradeTaxdarogaDocumentVerificationModel;
    protected $TradeSiteInspectionMessageModel;
    protected $model_application_type_mstr;
    protected $model_trade_items_mstr;
    protected $TradeTransactionModel;
    protected $TradeChequeDtlModel;
    protected $trade_view_application_doc_model;

    public function __construct()
    {


        parent::__construct();
        helper(['db_helper', 'qr_code_generator_helper','form']);
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
        $this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_view_ward_permission = new model_view_ward_permission($this->dbSystem);
        $this->model_view_trade_level_pending = new model_view_trade_level_pending($this->db);
        $this->model_firm_owner_name = new model_firm_owner_name($this->db);
        $this->model_application_doc = new model_application_doc($this->db);
        $this->model_trade_level_pending_dtl = new model_trade_level_pending_dtl($this->db);
        $this->model_apply_licence = new model_apply_licence($this->db);
        $this->model_view_trade_licence = new model_view_trade_licence($this->db);
        $this->model_view_application_doc = new model_view_application_doc($this->db);
        
        $this->model_trade_licence_validity = new model_trade_licence_validity($this->db);
        $this->model_trade_item_mstr = new model_trade_item_mstr($this->db);
        $this->model_prop_dtl = new model_prop_dtl($this->property_db);
        $this->model_prop_owner_detail = new model_prop_owner_detail($this->property_db);
        $this->model_trade_licence_owner_name = new model_trade_licence_owner_name($this->db);
        $this->model_trade_licence_trade_items = new model_trade_licence_trade_items($this->db);
        $this->TradeTaxdarogaVerificationModel = new TradeTaxdarogaVerificationModel($this->db);
        $this->TradeTaxdarogaDocumentVerificationModel = new TradeTaxdarogaDocumentVerificationModel($this->db);
        $this->TradeSiteInspectionMessageModel = new TradeSiteInspectionMessageModel($this->db);
        $this->model_application_type_mstr = new model_application_type_mstr($this->db);
        $this->model_trade_items_mstr = new model_trade_items_mstr($this->db);
        $this->TradeTransactionModel = new TradeTransactionModel($this->db);
        $this->TradeChequeDtlModel = new TradeChequeDtlModel($this->db);
        $this->trade_view_application_doc_model = new trade_view_application_doc_model($this->db);
    }

    public function index()
    {
        $data =(array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];

        //Transaction Mode List
        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['wardList'] = $wardList;

        $receiver_user_type_id = $emp_mstr["user_type_mstr_id"];
        $ward="";

        $i=0;
        foreach($wardList as $key => $value)
        {
            if($i==0)
            {
                $ward=array($value['ward_mstr_id']);
            }
            else
            {
                array_push($ward, $value['ward_mstr_id']);
            }
            $i++;
        }
        if($this->request->getMethod()=='post')
        {
            $data['ward_mstr_id'] = $this->request->getVar('ward_mstr_id');
            $data['from_date'] = $this->request->getVar('from_date');
            $data['to_date'] = $this->request->getVar('to_date');

            if($data['ward_mstr_id']!="")
            {
                $data['posts'] = $this->model_view_trade_level_pending->waterjereceivebywardidList($receiver_user_type_id, $data['from_date'],$data['to_date'],$data['ward_mstr_id']);
            }
            else
            {
                $data['posts'] = $this->model_view_trade_level_pending->waterjereceiveList($receiver_user_type_id, $data['from_date'],$data['to_date'],$ward);
            }
            //print_var($ward);
            $j=0;
            foreach($data['posts'] as $key => $value)
            {
                $wardd = $this->model_ward_mstr->getdatabyid($value['ward_mstr_id']);
                $licence_details = $this->model_apply_licence->getDatabyid($value['apply_licence_id']);
                $app_type = $this->model_application_type_mstr->trade_application_type_list($licence_details['application_type_id']);

                $data['posts'][$key]['ward_no'] = $wardd['ward_no'];
                $data['posts'][$key]['licence_details'] = $licence_details;
                $data['posts'][$key]['app_type'] = $app_type;
            }
            return view('trade/Connection/trade_si_list', $data);
        }
        else
        {
            $data['from_date'] = date('Y-m-d');
            $data['to_date'] = date('Y-m-d');
            $data['posts'] = $this->model_view_trade_level_pending->waterjereceiveList($receiver_user_type_id,$data['from_date'],$data['to_date'],$ward);
            // print_var($data['posts']);
            $j=0;
            foreach($data['posts'] as $key => $value)
            {
                $wardd = $this->model_ward_mstr->getdatabyid($value['ward_mstr_id']);
                $licence_details = $this->model_apply_licence->getDatabyid($value['apply_licence_id']);
                $app_type = $this->model_application_type_mstr->trade_application_type_list($licence_details['application_type_id']);

                $data['posts'][$key]['ward_no'] = $wardd['ward_no'];
                $data['posts'][$key]['licence_details'] = $licence_details;
                $data['posts'][$key]['app_type'] = $app_type;
            }
            return view('trade/Connection/trade_si_list', $data);
        }
    }
    public function mobiIndex()
    {
        $data =(array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];

        //Transaction Mode List
        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['wardList'] = $wardList;

        $receiver_user_type_id = $emp_mstr["user_type_mstr_id"];
        $ward="";

        $i=0;
        foreach($wardList as $key => $value)
        {
            if($i==0)
            {
                $ward=array($value['ward_mstr_id']);
            }
            else
            {
                array_push($ward, $value['ward_mstr_id']);
            }
            $i++;
        }
        if($this->request->getMethod()=='post')
        {
            $data['ward_mstr_id'] = $this->request->getVar('ward_mstr_id');
            $data['from_date'] = $this->request->getVar('from_date');
            $data['to_date'] = $this->request->getVar('to_date');

            if($data['ward_mstr_id']!="")
            {
                $data['posts'] = $this->model_view_trade_level_pending->waterjereceivebywardidList($receiver_user_type_id, $data['from_date'],$data['to_date'],$data['ward_mstr_id']);
            }
            else
            {
                $data['posts'] = $this->model_view_trade_level_pending->waterjereceiveList($receiver_user_type_id, $data['from_date'],$data['to_date'],$ward);
            }
            //print_var($ward);
            $j=0;
            foreach($data['posts'] as $key => $value)
            {
                $wardd = $this->model_ward_mstr->getdatabyid($value['ward_mstr_id']);
                $licence_details = $this->model_apply_licence->getDatabyid($value['apply_licence_id']);
                $app_type = $this->model_application_type_mstr->trade_application_type_list($licence_details['application_type_id']);

                $data['posts'][$key]['ward_no'] = $wardd['ward_no'];
                $data['posts'][$key]['licence_details'] = $licence_details;
                $data['posts'][$key]['app_type'] = $app_type;
            }
            return view('trade/Connection/trade_si_mobi_list', $data);
        }
        else
        {
            $data['from_date'] = date('Y-m-d');
            $data['to_date'] = date('Y-m-d');
            $data['posts'] = $this->model_view_trade_level_pending->waterjereceiveList($receiver_user_type_id,$data['from_date'],$data['to_date'],$ward);
            // print_var($data['posts']);
            $j=0;
            foreach($data['posts'] as $key => $value)
            {
                $wardd = $this->model_ward_mstr->getdatabyid($value['ward_mstr_id']);
                $licence_details = $this->model_apply_licence->getDatabyid($value['apply_licence_id']);
                $app_type = $this->model_application_type_mstr->trade_application_type_list($licence_details['application_type_id']);

                $data['posts'][$key]['ward_no'] = $wardd['ward_no'];
                $data['posts'][$key]['licence_details'] = $licence_details;
                $data['posts'][$key]['app_type'] = $app_type;
            }
            return view('trade/Connection/trade_si_mobi_list', $data);
        }
    }
    public function licenceCancel($ID=null)
    {   
        try
        {
            $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id']; 
            $data =(array)null;
            $data['name']=$name;
            $data['id']=$ID;
            if($data['id']==""){
                if($this->request->getMethod()=='post')
                {
                   $inputs = filterSanitizeStringtoUpper($this->request->getVar());

                   if($inputs['keyword']<>"")
                   {
                      $where="tbl_licence.status IN(1,2,3) and (firm_name ilike '%".$inputs['keyword']."%' or licence_no ilike '%".$inputs['keyword']."%')
                      ";
                      $data['licence_list'] = $this->model_trade_licence->licence_listkeyword($where);
                  }
                  else
                  {
                      $data['from_date']=$inputs['from_date'];
                      $data['to_date']=$inputs['to_date'];
                      $data['licence_list'] = $this->model_trade_licence->licence_listdate($data);
                  }
              }
              else
              {
               $data['from_date']=date("d-m-Y");
               $data['to_date']=date("d-m-Y");
               $data['licence_list'] = $this->model_trade_licence->licence_listdate($data);

              }
              return view('trade/Connection/CancelLicence', $data);
            }
           else
           {
                $data['cancellicencebyId'] = $this->model_trade_licence->cancellicencebyId($data);
                echo "<script>alert('Licence Successfully Canceled');</script>";
                return view('trade/Connection/CancelLicence', $data);
            }

        } catch (Exception $e) {

        }
    }


    public function viewDetails($id)
    {
        $data =(array)null;
        $level_data_sql = " select * from tbl_level_pending where md5(id::text) = '$id' "; 
        $level_data = $this->model_trade_level_pending_dtl->rowQuery($level_data_sql)[0]??[];
        if(!$level_data)
        {
            flashToast("licence","Application Not Found");
            return $this->response->redirect(base_url('Trade_SH/index'));
        }
        elseif($level_data['status']==2)
        {
            flashToast("licence","Application Already BTC");
            return $this->response->redirect(base_url('Trade_SH/index'));
        }
        elseif($level_data['status']==3)
        {
            flashToast("licence","Application Already Forword");
            return $this->response->redirect(base_url('Trade_SH/index'));
        }
        elseif($level_data['status']!=1)
        {
            flashToast("licence","Already Taken Acction On This Application");
            return $this->response->redirect(base_url('Trade_SH/index'));
        } 
        $Session = Session();
        $data['apply_id'] = $id;
        $data['view_trade_level_pending_id'] = $id;
        date_default_timezone_set('Asia/Kolkata');
        $ulb_mstr = $Session->get("ulb_dtl");

        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $data['ulb_dtl'] = $this->model_ulb_mstr->getulb_list($data['ulb_mstr_id']);
        $ulb_short_nm=$data['ulb_dtl']['short_ulb_name'];
        $ulb_nm = substr($ulb_short_nm, 0, 3);

        $login_emp_details_id = $emp_mstr["id"];
        //ward List
        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['wardList'] = $wardList;
        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        /* $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['wardList'] = $wardList; */
        $data['form'] = $this->model_view_trade_level_pending->tradelevelpendingdetailbyid($id);
        if($data['form'] && $data['form']['pending_status']==5)
        {
            flashToast("licence","License Already Created Of ".$data['form']['application_no']." Please Contact To Admin !!!!");
            return $this->response->redirect(base_url('Trade_SH/index'));
        }
        
        $data['basic_details'] = $this->model_view_trade_licence->getDatabyid(md5($data['form']['apply_licence_id']));
        $data['ward_mstr_id'] = $data['basic_details']['ward_mstr_id'];
        $data['ward']  = $this->model_ward_mstr->getdatabyid($data['form']['ward_mstr_id']);
        $data['holding'] = $this->model_apply_licence->getholding($data['basic_details']['application_no']);
        $data['nature_business']=null;
        $data['dd']=array();
        if($data['holding']['nature_of_bussiness'])
            $data['nature_business'] =$this->model_trade_items_mstr->nature_business($data['holding']['nature_of_bussiness']);
        // print_var($data['nature_business']);return;
        foreach($data['nature_business']??[] as $val)
        {
            $data['dd']=$val;
        }
        
        $data['nature_business']['trade_item'] = is_array($data['dd'])? implode('<b>,</b><br>',$data['dd']):$data['dd'];
        // print_var($data['dd']);die();
        $data['payment_dtls'] = $this->TradeTransactionModel->payment_details($data['basic_details']['id']);
        //$data['cheque_dtls'] = $this->TradeChequeDtlModel->cheque_details($data['payment_dtls']['id']);
        $data['form']['ward_no'] = $data['ward']['ward_no'];
        $ward_nm=$data['ward']['ward_no'];
        $ward_id=$data['form']['ward_mstr_id'];
        $application_type_id = $data['form']['application_type_id'];
        $licence_id = $data['form']['apply_licence_id'];
        $apply_licence_id=$data['form']['apply_licence_id'];
        $data['apply_licence_id']=$data['form']['apply_licence_id'];
        $data['owner_details'] = $this->model_firm_owner_name->applicantdetails($data['form']['apply_licence_id']);
        
        
        $data['doc_exists']=$this->trade_view_application_doc_model->getdoc_by_appid($apply_licence_id);
        
        // get date and time fr site inspection
        $data['inspection_details'] = $this->TradeSiteInspectionMessageModel->get_date_time($login_emp_details_id,$apply_licence_id);
        $data['forward_date'] = date('d-m-Y', strtotime($data['inspection_details']['forward_date']));
        $data['forward_time'] = date('h:i:s a', strtotime($data['inspection_details']['forward_time']));
        //end

        
        foreach($data['doc_exists'] as $key => $value)
        {
            $data['doc_exists'][$key]['verify_status'] = $this->TradeTaxdarogaDocumentVerificationModel->getAllRemarks($value['id']);
        }

        //print_r($data['doc_exists']);
        $data['dealingLevel'] = $this->model_trade_level_pending_dtl->getDealingLevelData($apply_licence_id);
        //$data['delingReceiveDate'] = $this->model_trade_level_pending_dtl->getDealingReceiveDate($apply_licence_id);

        $data['taxDarogaLevel'] = $this->model_trade_level_pending_dtl->getTaxDarogaLevelData($apply_licence_id);
        //$data['taxDarogaReceiveDate'] = $this->model_trade_level_pending_dtl->getTaxDarogaReceiveDate($apply_licence_id);

        $data['sectionHeadLevel'] = $this->model_trade_level_pending_dtl->getSectionHeadLevelData($apply_licence_id);
        // $data['sectionHeadReceiveDate'] = $this->model_trade_level_pending_dtl->getSectionHeadReceiveDate($apply_licence_id);

        $data['executiveLevel'] = $this->model_trade_level_pending_dtl->getExecutiveLevelData($apply_licence_id);
        //$data['executiveReceiveDate'] = $this->model_trade_level_pending_dtl->getExecutiveReceiveDate($apply_licence_id);
        //End 
        $data['levelPendinDtlId'] = $this->model_trade_level_pending_dtl->getLevelPendingDtlId($apply_licence_id);
        //Start 
        //End 
        
        $data['site_inspection_id'] = $this->TradeSiteInspectionMessageModel->GetSiteInspectionId($apply_licence_id);
        //End


        if($this->request->getMethod()=='post')
        {
            # Send Message for inspection
            if(isset($_POST['btn_send_message']))
            {
                $inputData =(array)null;
                $inputs = arrFilterSanitizeString($this->request->getVar()); 
                
                
                $forward_date = date('Y-m-d', strtotime($inputs['site_date']));
                $forward_time = date('H:i', strtotime($inputs['site_date']));
                $currenthour = date("H:i");
                
                if (strtotime('now') > strtotime($inputs['site_date']))
                {
                    $data['error']  = "Please select time greater or equal to given time!";
                    flashToast('licence', $data['error']);
                    return view('trade/Connection/trade_si_view', $data);
                }
                
                
                /*$applyLicenceDetails = $this->model_apply_licence->getData($inputs['apply_licence_id']);*/
                $inputData= [                     
                    'apply_licence_id' => $apply_licence_id,
                    'emp_details_id' => $login_emp_details_id,
                    'created_on' => date('Y-m-d H:i:s'),
                    'forward_date' => $forward_date,
                    'forward_time' => $forward_time
                ];
                //Prevent Double Posting
                if($checkData = $this->TradeSiteInspectionMessageModel->getDoublePostingData($inputData['apply_licence_id']))
                {
                    return view('trade/Connection/trade_si_view', $data);
                }
                else
                {
                    if($insertdata = $this->TradeSiteInspectionMessageModel->insertMessageData($inputData))
                    {
                        $data['site_inspection_id'] = $this->TradeSiteInspectionMessageModel->GetSiteInspectionId($apply_licence_id);
                        flashToast('licence','Site Inspection Message Sent To The Citizen Successfully!!!');
                        // get date and time fr site inspection
                        $data['inspection_details'] = $this->TradeSiteInspectionMessageModel->get_date_time($login_emp_details_id,$apply_licence_id);
                        $data['forward_date'] = date('d-m-Y', strtotime($data['inspection_details']['forward_date']));
                        $data['forward_time'] = date('h:i:s a', strtotime($data['inspection_details']['forward_time']));
                        //print_r($data['forward_date']);exit;
                        //end
                        return view('trade/Connection/trade_si_view', $data);
                    }
                    else
                    {
                        flashToast('licence','Fail To Send Message To The Citizen!!!');
                        return view('trade/Connection/trade_si_view', $data);
                    }
                }
            }

            # Cancel Message for inspection
            if(isset($_POST['btn_cancel_message']))
            {
                $inputData =(array)null;
                $inputs = arrFilterSanitizeString($this->request->getVar()); 
                $inputData = [                     
                    'apply_licence_id' =>$inputs['apply_licence_id'],
                    'cancel_by' => $login_emp_details_id,
                    'cancel_date' => date('Y-m-d H:i:s')
                ];
                //prevent Double Posting
                if($checkCancelData = $this->TradeSiteInspectionMessageModel->getCancelMessageData($inputData['apply_licence_id']))
                {
                    if($updateData = $this->TradeSiteInspectionMessageModel->cancelSiteInspectionMessage($inputData))
                    {
                        $data['site_inspection_id'] = $this->TradeSiteInspectionMessageModel->GetSiteInspectionId($apply_licence_id);
                        flashToast('licence','Message Cancel Successfully !!!');
                        return view('trade/Connection/trade_si_view', $data);
                    }
                    else
                    {
                        flashToast('licence','Fail To Cancel Message !!!');
                        return view('trade/Connection/trade_si_view', $data);
                    }
                }
                else
                {
                    return view('trade/Connection/trade_si_view', $data);
                }
            }

            # Update Ward
            if(isset($_POST['btn_update_ward']))
            {
                $inputs = arrFilterSanitizeString($this->request->getVar());
                $licenceOld = $this->model_apply_licence->where("md5(id::text)",$inputs['apply_licence_id'])->get()->getFirstRow("array"); 
                //update Ward No
                
                $this->db->transBegin();
                if( ($this->model_apply_licence->updateWard($inputs)) && $this->model_apply_licence->updateArea($inputs))
                { 
                    $applicationTran = $this->TradeTransactionModel
                        ->select("*")
                        ->where("related_id",$licenceOld["id"])
                        ->where("transaction_type",$data["basic_details"]['application_type'])
                        ->whereIn("status",[1,2])
                        ->get()
                        ->getFirstRow('array'); 
                    $args = array(
                        "apply_licence_id"=>$licenceOld["id"],
                        'areasqft' => $inputs['area_in_sqrt'],
                        'applytypeid' => $licenceOld['application_type_id'],
                        'estdate' => $licenceOld['application_type_id'] == 1 ? $licenceOld['establishment_date'] : $licenceOld['valid_from']??date("Y-m-d"),
                        'apply_licence_id' => $licenceOld['id'],
                        'tobacco_status' => $licenceOld['tobacco_status'],
                        'licensefor' => $licenceOld['licence_for_years'],
                        'nature_of_business' => $licenceOld['nature_of_bussiness'],
                        "curdate"=>$applicationTran["transaction_date"]??null,
                        "testPayentAmount"=>true,
                    );
                    $newCharge = json_decode((new \App\Controllers\TradeCitizen())->getcharge($args),true);
                    $activeTran= $this->TradeTransactionModel
                        ->select("sum(paid_amount) as paid_amount")
                        ->where("related_id",$licenceOld["id"])
                        ->whereIn("status",[1,2])
                        ->get()
                        ->getFirstRow('array'); 

                    $paidAmount = $activeTran['paid_amount'] ?? 0;
                    $totalCharge = $newCharge['total_charge'] ?? 0;

                    if($licenceOld["application_type_id"]!=4 && $newCharge["response"] && ($totalCharge - $paidAmount)>0){
                        $this->model_apply_licence->edit($licenceOld['id'],["is_fild_verification_charge"=>true,"exrta_charge"=>($totalCharge - $paidAmount)]);
                    }else{
                        $this->model_apply_licence->edit($licenceOld['id'],["is_fild_verification_charge"=>false,"exrta_charge"=>0]);
                    }
                    $licence = $this->model_apply_licence->where("id",$licenceOld['id'])->get()->getFirstRow("array");
                    //insert Data Into
                    $data['basic'] = $this->model_view_trade_licence->getDatabyid(md5($data['form']['apply_licence_id']));
                    $data['ward_mstr_id'] = $data['basic']['ward_mstr_id'];
                    $testLog = $this->db->table("tbl_trade_update_log")->where("app_id",$licenceOld['id'])->get()->getFirstRow("array");
                    $appLog = [
                        "new_calculation"=>$newCharge,
                        "paid_amount"=>$paidAmount,
                        "old_data"=>$licenceOld,
                        "request_data"=>$this->request->getVar(),
                        "new_data"=>$licence,
                        "sl"=>1,
                        "emp_id"=>$login_emp_details_id,
                        "date_time"=>date("Y-m-d H:s:i"),
                    ];
                    if($testLog){
                        $appLog["sl"]=$testLog["update_sl"]+1;
                        $updateData=[
                            "update_sl"=>$testLog["update_sl"]+1,
                            "app_log"=>json_encode(array_merge(json_decode($testLog["app_log"],true)??[],[$appLog])),
                            "updated_at"=>$appLog["date_time"],
                        ];
                        $this->db->table("tbl_trade_update_log")->where("id",$testLog["id"])->update($updateData);
                    }else{
                        $updateData=[
                            "app_id"=>$licence["id"],
                            "update_sl"=>1,
                            "app_log"=>json_encode([$appLog]),
                            "updated_at"=>$appLog["date_time"],
                        ];
                        $this->db->table("tbl_trade_update_log")->insert($updateData);
                    }
                    if($this->db->transStatus()){
                        $this->db->transCommit();
                        flashToast('licence', 'Ward Updated Successfully!!!');
                        return redirect()->back();
                        // return view('trade/Connection/trade_si_view', $data);
                    }
                    flashToast('licence', 'Ward Updated Error!!!');
                    $this->db->transRollback();
                    return redirect()->back();
                }
                else
                {
                    $this->db->transRollback();
                    flashToast('licence', 'Fail To Update Ward No!!!');
                    return redirect()->back();
                    return $this->response->redirect(base_url('Trade_SI/index/'));
                }
            }

            # Verify Document
            if(isset($_POST['btn_verify']))
            {
                $cnt=$_POST['btn_verify'];
                $inputData = [                    
                    'apply_licence_id' => $apply_licence_id,
                    'emp_details_id' => $login_emp_details_id,
                    'created_on' => date('Y-m-d H:i:s'),
                    'upload_doc_id' => $_POST['app_doc_id'.$cnt],
                    'doc_remark' => $_POST['rejectedremarks'.$cnt],
                    'verify_status' => 1                
                ];
                $this->TradeTaxdarogaDocumentVerificationModel->insertDocumentData($inputData);
                return $this->response->redirect(base_url('trade_si/viewDetails/'.$id));
            }

            # Reject Document
            if(isset($_POST['btn_reject']))
            {
                $cnt=$_POST['btn_reject'];
                $rules=[
                    'rejectedremarks'.$cnt => 'required', 
                ];
                
                if(!$this->validate($rules))
                {
                    $data['validation']=$this->validator;                        
                    return view('trade/Connection/trade_si_view', $data);
                }
                else
                {
                    $inputData = [                    
                        'apply_licence_id' => $apply_licence_id,
                        'emp_details_id' => $login_emp_details_id,
                        'created_on' => date('Y-m-d H:i:s'),
                        'upload_doc_id' => $_POST['app_doc_id'.$cnt],
                        'doc_remark' => $_POST['rejectedremarks'.$cnt],
                        'verify_status' => 2
                    ];
                    # tbl_taxdaroga_document_verification
                    $this->TradeTaxdarogaDocumentVerificationModel->insertDocumentData($inputData);

                    $app_doc_id=$_POST['app_doc_id'.$cnt];
                    $inputs=array(
                        'verify_status'=> 2,
                        'remarks'=>  $_POST['rejectedremarks'.$cnt], 
                        'verified_by_emp_id'=> $login_emp_details_id,
                        'lvl_pending_id'=> $data['form']['id'],
                    );
                    # Reject Dealing Officer Verified documet -- by Hayat
                    $this->trade_view_application_doc_model->VerifyDocument($app_doc_id, $inputs);
                    return $this->response->redirect(base_url('trade_si/viewDetails/'.$id));
                }
            }

            # Forward Application
            if(isset($_POST['btn_forward_submit']))
            {
                $inputs = arrFilterSanitizeString($this->request->getVar());
                $data = [
                    'remarks' => $this->request->getVar('remarks'),                        
                    'level_pending_dtl_id' => $id,
                    'apply_licence_id' => $apply_licence_id,
                    'forward_date' => date('Y-m-d'),
                    'forward_time' => date('H:i:s'),
                    'emp_details_id' => $login_emp_details_id,
                    'created_on' => date('Y-m-d H:i:s'),                         
                    'sender_user_type_id' => $sender_user_type_id,
                    'receiver_user_type_id'=> 18,                         
                    'area_in_sqft' => $inputs['area_in_sqft'],
                ];
                    //Insert Data into tbl_taxdaroga_document_verification
                $this->TradeTaxdarogaVerificationModel->insertData($data);
                if($updateverify = $this->model_trade_level_pending_dtl->updatelevelpendingById($data))
                {
                    if($insertbackward = $this->model_trade_level_pending_dtl->insrtlevelpendingdtl($data))
                    {
                        flashToast('licence','Application Verified & Forwarded to Section Head Successfully!!!');
                        return $this->response->redirect(base_url('Trade_SI/index/'));
                    }
                }
            }

            //Backward
            if(isset($_POST['btn_backward']))
            {
                $inputData =(array)null;
                $inputs = arrFilterSanitizeString($this->request->getVar()); 
                // $level_pending_dtl_id = $this->model_trade_level_pending_dtl->getLevelPendingDetailsIdForTaxDaroga($inputs['apply_licence_id']);
                $level_pending_id = $data['form']['id'];
                
                $data['lastRecord'] = 
                $this->model_trade_level_pending_dtl->getLastRecord($level_pending_id);
                $apply_licence_id=$data['lastRecord']["apply_licence_id"];
                $level_last_deta = $this->model_trade_level_pending_dtl->getDataNew(['id'=>$data['lastRecord']['id']],'*','tbl_level_pending');


                $inputData = [
                    'remarks' => $inputs['remarks'],
                    'level_pending_dtl_id' =>$id,
                    'apply_licence_id' => $apply_licence_id,
                    'emp_details_id' => $login_emp_details_id,
                    'created_on' =>date('Y-m-d H:i:s'),
                    'forward_date' =>date('Y-m-d'),
                    'forward_time' =>date('H:i:s'),
                    'sender_user_type_id' => $sender_user_type_id,
                    'receiver_user_type_id'=>17,
                    'verification_status'=>3,
                    'area_in_sqft' => $inputs['area_in_sqft']
                ];
                $btcdata = [
                'remarks' => $this->request->getVar('remarks'),
                'level_id' => $level_last_deta["id"],
                'apply_licence_id' => $apply_licence_id,
                'sender_user_type_id' => $level_last_deta["sender_user_type_id"],
                'receiver_user_type_id'=> $level_last_deta["receiver_user_type_id"],
                'forward_date' =>$level_last_deta["forward_date"],
                'forward_time' => $level_last_deta["forward_time"],
                'created_on'=> $level_last_deta["created_on"],
                'verification_status'=> 2,
                'emp_details_id'=>$level_last_deta["emp_details_id"],
                'status'=>$level_last_deta["status"],
                'send_date' => $level_last_deta["send_date"]??null, 
                'receiver_user_id' => $login_emp_details_id,
                'ip_address'=> $_SERVER['REMOTE_ADDR'],
                ];


                $this->TradeTaxdarogaVerificationModel->insertData($inputData); 
                if($updatebackward = $this->model_trade_level_pending_dtl->updatelevelpendingById($inputData))
                {

                    $this->model_trade_level_pending_dtl->tbl_level_sent_back_dtl($btcdata);
                    if($insertbackward = $this->model_trade_level_pending_dtl->insrtlevelpendingdtl($inputData))
                    {
                        flashToast('licence','Application Backwarded To The Dealing Officer !!!');
                        return $this->response->redirect(base_url('Trade_SI/index/'));
                    }
                }
            }
        }
        else
        {   
                //print_var($data['payment_dtls']);
            return view('trade/Connection/trade_si_view', $data);
        }
    }
    public function viewDetailsMobi($id)
    {
        $data =(array)null;
        $level_data_sql = " select * from tbl_level_pending where md5(id::text) = '$id' "; 
        $level_data = $this->model_trade_level_pending_dtl->rowQuery($level_data_sql)[0]??[];
        if(!$level_data)
        {
            flashToast("licence","Application Not Found");
            return $this->response->redirect(base_url('Trade_SI/mobiIndex'));
        }
        elseif($level_data['status']==2)
        {
            flashToast("licence","Application Already BTC");
            return $this->response->redirect(base_url('Trade_SI/mobiIndex'));
        }
        elseif($level_data['status']==3)
        {
            flashToast("licence","Application Already Forword");
            return $this->response->redirect(base_url('Trade_SI/mobiIndex'));
        }
        elseif($level_data['status']!=1)
        {
            flashToast("licence","Already Taken Acction On This Application");
            return $this->response->redirect(base_url('Trade_SI/mobiIndex'));
        }
        $Session = Session();
        $data['apply_id'] = $id;
        $data['view_trade_level_pending_id'] = $id;
        date_default_timezone_set('Asia/Kolkata');
        $ulb_mstr = $Session->get("ulb_dtl");

        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $data['ulb_dtl'] = $this->model_ulb_mstr->getulb_list($data['ulb_mstr_id']);
        $ulb_short_nm=$data['ulb_dtl']['short_ulb_name'];
        $ulb_nm = substr($ulb_short_nm, 0, 3);

        $login_emp_details_id = $emp_mstr["id"];
        //ward List
        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['wardList'] = $wardList;
        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        /* $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['wardList'] = $wardList; */
        $data['form'] = $this->model_view_trade_level_pending->tradelevelpendingdetailbyid($id);
        if($data['form'] && $data['form']['pending_status']==5)
        {
            flashToast("licence","License Already Created Of ".$data['form']['application_no']." Please Contact To Admin !!!!");
            return $this->response->redirect(base_url('Trade_SI/mobiIndex'));
        }
        
        $data['basic_details'] = $this->model_view_trade_licence->getDatabyid(md5($data['form']['apply_licence_id']));
        $data['ward_mstr_id'] = $data['basic_details']['ward_mstr_id'];
        $data['ward']  = $this->model_ward_mstr->getdatabyid($data['form']['ward_mstr_id']);
        $data['holding'] = $this->model_apply_licence->getholding($data['basic_details']['application_no']);
        $data['nature_business']=null;
        $data['dd']=array();
        if($data['holding']['nature_of_bussiness'])
            $data['nature_business'] =$this->model_trade_items_mstr->nature_business($data['holding']['nature_of_bussiness']);
        // print_var($data['nature_business']);return;
        foreach($data['nature_business'] as $val)
        {
            $data['dd']=$val;
        }
        
        $data['nature_business']['trade_item'] = is_array($data['dd'])? implode('<b>,</b><br>',$data['dd']):$data['dd'];
        // print_var($data['dd']);die();
        $data['payment_dtls'] = $this->TradeTransactionModel->payment_details($data['basic_details']['id']);
        //$data['cheque_dtls'] = $this->TradeChequeDtlModel->cheque_details($data['payment_dtls']['id']);
        $data['form']['ward_no'] = $data['ward']['ward_no'];
        $ward_nm=$data['ward']['ward_no'];
        $ward_id=$data['form']['ward_mstr_id'];
        $application_type_id = $data['form']['application_type_id'];
        $licence_id = $data['form']['apply_licence_id'];
        $apply_licence_id=$data['form']['apply_licence_id'];
        $data['apply_licence_id']=$data['form']['apply_licence_id'];
        $data['owner_details'] = $this->model_firm_owner_name->applicantdetails($data['form']['apply_licence_id']);
        
        
        $data['doc_exists']=$this->trade_view_application_doc_model->getdoc_by_appid($apply_licence_id);
        
        // get date and time fr site inspection
        $data['inspection_details'] = $this->TradeSiteInspectionMessageModel->get_date_time($login_emp_details_id,$apply_licence_id);
        $data['forward_date'] = date('d-m-Y', strtotime($data['inspection_details']['forward_date']));
        $data['forward_time'] = date('h:i:s a', strtotime($data['inspection_details']['forward_time']));
        //end

        
        foreach($data['doc_exists'] as $key => $value)
        {
            $data['doc_exists'][$key]['verify_status'] = $this->TradeTaxdarogaDocumentVerificationModel->getAllRemarks($value['id']);
        }

        //print_r($data['doc_exists']);
        $data['dealingLevel'] = $this->model_trade_level_pending_dtl->getDealingLevelData($apply_licence_id);
        //$data['delingReceiveDate'] = $this->model_trade_level_pending_dtl->getDealingReceiveDate($apply_licence_id);

        $data['taxDarogaLevel'] = $this->model_trade_level_pending_dtl->getTaxDarogaLevelData($apply_licence_id);
        //$data['taxDarogaReceiveDate'] = $this->model_trade_level_pending_dtl->getTaxDarogaReceiveDate($apply_licence_id);

        $data['sectionHeadLevel'] = $this->model_trade_level_pending_dtl->getSectionHeadLevelData($apply_licence_id);
        // $data['sectionHeadReceiveDate'] = $this->model_trade_level_pending_dtl->getSectionHeadReceiveDate($apply_licence_id);

        $data['executiveLevel'] = $this->model_trade_level_pending_dtl->getExecutiveLevelData($apply_licence_id);
        //$data['executiveReceiveDate'] = $this->model_trade_level_pending_dtl->getExecutiveReceiveDate($apply_licence_id);
        //End 
        $data['levelPendinDtlId'] = $this->model_trade_level_pending_dtl->getLevelPendingDtlId($apply_licence_id);
        //Start 
        //End 
        
        $data['site_inspection_id'] = $this->TradeSiteInspectionMessageModel->GetSiteInspectionId($apply_licence_id);
        //End


        if($this->request->getMethod()=='post')
        {
            # Send Message for inspection
            if(isset($_POST['btn_send_message']))
            {
                $inputData =(array)null;
                $inputs = arrFilterSanitizeString($this->request->getVar()); 
                
                
                $forward_date = date('Y-m-d', strtotime($inputs['site_date']));
                $forward_time = date('H:i', strtotime($inputs['site_date']));
                $currenthour = date("H:i");
                
                if (strtotime('now') > strtotime($inputs['site_date']))
                {
                    $data['error']  = "Please select time greater or equal to given time!";
                    flashToast('licence', $data['error']);
                    return view('trade/Connection/trade_si_view_mobi', $data);
                }
                
                
                /*$applyLicenceDetails = $this->model_apply_licence->getData($inputs['apply_licence_id']);*/
                $inputData= [                     
                    'apply_licence_id' => $apply_licence_id,
                    'emp_details_id' => $login_emp_details_id,
                    'created_on' => date('Y-m-d H:i:s'),
                    'forward_date' => $forward_date,
                    'forward_time' => $forward_time
                ];
                //Prevent Double Posting
                if($checkData = $this->TradeSiteInspectionMessageModel->getDoublePostingData($inputData['apply_licence_id']))
                {
                    return view('trade/Connection/trade_si_view_mobi', $data);
                }
                else
                {
                    if($insertdata = $this->TradeSiteInspectionMessageModel->insertMessageData($inputData))
                    {
                        $data['site_inspection_id'] = $this->TradeSiteInspectionMessageModel->GetSiteInspectionId($apply_licence_id);
                        flashToast('licence','Site Inspection Message Sent To The Citizen Successfully!!!');
                        // get date and time fr site inspection
                        $data['inspection_details'] = $this->TradeSiteInspectionMessageModel->get_date_time($login_emp_details_id,$apply_licence_id);
                        $data['forward_date'] = date('d-m-Y', strtotime($data['inspection_details']['forward_date']));
                        $data['forward_time'] = date('h:i:s a', strtotime($data['inspection_details']['forward_time']));
                        //print_r($data['forward_date']);exit;
                        //end
                        return view('trade/Connection/trade_si_view_mobi', $data);
                    }
                    else
                    {
                        flashToast('licence','Fail To Send Message To The Citizen!!!');
                        return view('trade/Connection/trade_si_view_mobi', $data);
                    }
                }
            }

            # Cancel Message for inspection
            if(isset($_POST['btn_cancel_message']))
            {
                $inputData =(array)null;
                $inputs = arrFilterSanitizeString($this->request->getVar()); 
                $inputData = [                     
                    'apply_licence_id' =>$inputs['apply_licence_id'],
                    'cancel_by' => $login_emp_details_id,
                    'cancel_date' => date('Y-m-d H:i:s')
                ];
                //prevent Double Posting
                if($checkCancelData = $this->TradeSiteInspectionMessageModel->getCancelMessageData($inputData['apply_licence_id']))
                {
                    if($updateData = $this->TradeSiteInspectionMessageModel->cancelSiteInspectionMessage($inputData))
                    {
                        $data['site_inspection_id'] = $this->TradeSiteInspectionMessageModel->GetSiteInspectionId($apply_licence_id);
                        flashToast('licence','Message Cancel Successfully !!!');
                        return view('trade/Connection/trade_si_view_mobi', $data);
                    }
                    else
                    {
                        flashToast('licence','Fail To Cancel Message !!!');
                        return view('trade/Connection/trade_si_view_mobi', $data);
                    }
                }
                else
                {
                    return view('trade/Connection/trade_si_view_mobi', $data);
                }
            }

            # Update Ward
            if(isset($_POST['btn_update_ward']))
            {
                $inputs = arrFilterSanitizeString($this->request->getVar()); 
                //update Ward No
                if( $updateData= $this->model_apply_licence->updateWard($inputs))
                {
                    //insert Data Into
                    $data['basic'] = $this->model_view_trade_licence->getDatabyid(md5($data['form']['apply_licence_id']));
                    $data['ward_mstr_id'] = $data['basic']['ward_mstr_id'];
                    flashToast('licence', 'Ward Updated Successfully!!!');
                    return view('trade/Connection/trade_si_view_mobi', $data);
                }
                else
                {
                    flashToast('licence', 'Fail To Update Ward No!!!');
                    return $this->response->redirect(base_url('Trade_SI/mobiIndex/'));
                }
            }

            # Verify Document
            if(isset($_POST['btn_verify']))
            {
                $cnt=$_POST['btn_verify'];
                $inputData = [                    
                    'apply_licence_id' => $apply_licence_id,
                    'emp_details_id' => $login_emp_details_id,
                    'created_on' => date('Y-m-d H:i:s'),
                    'upload_doc_id' => $_POST['app_doc_id'.$cnt],
                    'doc_remark' => $_POST['rejectedremarks'.$cnt],
                    'verify_status' => 1                
                ];
                $this->TradeTaxdarogaDocumentVerificationModel->insertDocumentData($inputData);
                return $this->response->redirect(base_url('trade_si/viewDetailsMobi/'.$id));
            }

            # Reject Document
            if(isset($_POST['btn_reject']))
            {
                $cnt=$_POST['btn_reject'];
                $rules=[
                    'rejectedremarks'.$cnt => 'required', 
                ];
                
                if(!$this->validate($rules))
                {
                    $data['validation']=$this->validator;                        
                    return view('trade/Connection/trade_si_view_mobi', $data);
                }
                else
                {
                    $inputData = [                    
                        'apply_licence_id' => $apply_licence_id,
                        'emp_details_id' => $login_emp_details_id,
                        'created_on' => date('Y-m-d H:i:s'),
                        'upload_doc_id' => $_POST['app_doc_id'.$cnt],
                        'doc_remark' => $_POST['rejectedremarks'.$cnt],
                        'verify_status' => 2
                    ];
                    # tbl_taxdaroga_document_verification
                    $this->TradeTaxdarogaDocumentVerificationModel->insertDocumentData($inputData);

                    $app_doc_id=$_POST['app_doc_id'.$cnt];
                    $inputs=array(
                        'verify_status'=> 2,
                        'remarks'=>  $_POST['rejectedremarks'.$cnt], 
                        'verified_by_emp_id'=> $login_emp_details_id,
                        'lvl_pending_id'=> $data['form']['id'],
                    );
                    # Reject Dealing Officer Verified documet -- by Hayat
                    $this->trade_view_application_doc_model->VerifyDocument($app_doc_id, $inputs);
                    return $this->response->redirect(base_url('trade_si/viewDetailsMobi/'.$id));
                }
            }

            # Forward Application
            if(isset($_POST['btn_forward_submit']))
            {
                $inputs = arrFilterSanitizeString($this->request->getVar());
                $data = [
                    'remarks' => $this->request->getVar('remarks'),                        
                    'level_pending_dtl_id' => $id,
                    'apply_licence_id' => $apply_licence_id,
                    'forward_date' => date('Y-m-d'),
                    'forward_time' => date('H:i:s'),
                    'emp_details_id' => $login_emp_details_id,
                    'created_on' => date('Y-m-d H:i:s'),                         
                    'sender_user_type_id' => $sender_user_type_id,
                    'receiver_user_type_id'=> 18,                         
                    'area_in_sqft' => $inputs['area_in_sqft'],
                ];
                //Insert Data into tbl_taxdaroga_document_verification
                $this->TradeTaxdarogaVerificationModel->insertData($data);
                if($updateverify = $this->model_trade_level_pending_dtl->updatelevelpendingById($data))
                {
                    if($insertbackward = $this->model_trade_level_pending_dtl->insrtlevelpendingdtl($data))
                    {
                        flashToast('licence','Application Verified & Forwarded to Section Head Successfully!!!');
                        return $this->response->redirect(base_url('Trade_SI/mobiIndex/'));
                    }
                }
            }

            //Backward
            if(isset($_POST['btn_backward']))
            {
                $inputData =(array)null;
                $inputs = arrFilterSanitizeString($this->request->getVar()); 
                // $level_pending_dtl_id = $this->model_trade_level_pending_dtl->getLevelPendingDetailsIdForTaxDaroga($inputs['apply_licence_id']);
                $level_pending_id = $data['form']['id'];
                
                $data['lastRecord'] = 
                $this->model_trade_level_pending_dtl->getLastRecord($level_pending_id);
                $apply_licence_id=$data['lastRecord']["apply_licence_id"];
                $level_last_deta = $this->model_trade_level_pending_dtl->getDataNew(['id'=>$data['lastRecord']['id']],'*','tbl_level_pending');


                $inputData = [
                    'remarks' => $inputs['remarks'],
                    'level_pending_dtl_id' =>$id,
                    'apply_licence_id' => $apply_licence_id,
                    'emp_details_id' => $login_emp_details_id,
                    'created_on' =>date('Y-m-d H:i:s'),
                    'forward_date' =>date('Y-m-d'),
                    'forward_time' =>date('H:i:s'),
                    'sender_user_type_id' => $sender_user_type_id,
                    'receiver_user_type_id'=>17,
                    'verification_status'=>3,
                    'area_in_sqft' => $inputs['area_in_sqft']
                ];
                $btcdata = [
                    'remarks' => $this->request->getVar('remarks'),
                    'level_id' => $level_last_deta["id"],
                    'apply_licence_id' => $apply_licence_id,
                    'sender_user_type_id' => $level_last_deta["sender_user_type_id"],
                    'receiver_user_type_id'=> $level_last_deta["receiver_user_type_id"],
                    'forward_date' =>$level_last_deta["forward_date"],
                    'forward_time' => $level_last_deta["forward_time"],
                    'created_on'=> $level_last_deta["created_on"],
                    'verification_status'=> 2,
                    'emp_details_id'=>$level_last_deta["emp_details_id"],
                    'status'=>$level_last_deta["status"],
                    'send_date' => $level_last_deta["send_date"]??null, 
                    'receiver_user_id' => $login_emp_details_id,
                    'ip_address'=> $_SERVER['REMOTE_ADDR'],
                ];


                $this->TradeTaxdarogaVerificationModel->insertData($inputData); 
                if($updatebackward = $this->model_trade_level_pending_dtl->updatelevelpendingById($inputData))
                {

                    $this->model_trade_level_pending_dtl->tbl_level_sent_back_dtl($btcdata);
                    if($insertbackward = $this->model_trade_level_pending_dtl->insrtlevelpendingdtl($inputData))
                    {
                        flashToast('licence','Application Backwarded To The Dealing Officer !!!');
                        return $this->response->redirect(base_url('Trade_SI/mobiIndex/'));
                    }
                }
            }
        }
        else
        {   
                //print_var($data['payment_dtls']);
            return view('trade/Connection/trade_si_view_mobi', $data);
        }
    }

    public function eo_approved_view($id)
    {
        $data =(array)null;
        $Session = Session();
        date_default_timezone_set('Asia/Kolkata');
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");

        $login_emp_details_id = $emp_mstr["id"];
        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        $data['basic_details'] = $this->model_view_trade_licence->getDatabyid($id);
        $apply_licence_id=$data['basic_details']['id'];
            //$data['consumer_details'] = $this->model_water_consumer->consumerDetails($apply_connection_id);
            //$data['consumer_initial_details'] = $this->model_water_consumer_initial_meter->consumerinitialDetails($data['consumer_details']['id']);
        $data['ward']  = $this->model_ward_mstr->getdatabyid($data['basic_details']['ward_mstr_id']);
        $data['basic_details']['ward_no']=$ward['ward_no'];
        $data['owner_details'] = $this->model_firm_owner_name->applicantdetails($apply_licence_id);


        return view('trade/Connection/trade_eo_approved_view', $data);


    }
    public function municipal_licence($id=null)
    {
        $data =(array)null;
        $Session = Session();
        date_default_timezone_set('Asia/Kolkata');
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $ulb_mstr_id = $data['ulb_mstr_id']; 
        $emp_mstr = $Session->get("emp_details");
        $path=base_url('citizenPaymentReceipt/municipal_licence/'.$ulb_mstr_id.'/'.$id);
        $data['ss']=qrCodeGeneratorFun($path);

        $login_emp_details_id = $emp_mstr["id"];
        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        $data['ulb'] = $this->model_ulb_mstr->getulb_list($data['ulb_mstr_id']);
        $data['basic_details'] = $this->model_view_trade_licence->getDatabyid($id);
        $apply_licence_id=$data['basic_details']['id'];
        $data['licence_dtl'] = $this->model_trade_licence->get_licence_dtl($apply_licence_id);
        $data['licence_validity_dtl'] = $this->model_trade_licence_validity->get_licence_validity_dtl($data['licence_dtl']["id"]);
            //print_r($data['licence_validity_dtl']);
        $data['item_details'] = $this->model_trade_item_mstr->getitemname($apply_licence_id);
        $data['prop_dtl'] = $this->model_prop_dtl->getPropdetailsbyid($data['licence_dtl']['prop_dtl_id']);
        $data['prop_owner_dtl'] = $this->model_prop_owner_detail->propownerdetails($data['licence_dtl']['prop_dtl_id']);
        $data['ward']  = $this->model_ward_mstr->getdatabyid($data['basic_details']['ward_mstr_id']);
        $data['owner_details'] = $this->model_firm_owner_name->applicantdetails($apply_licence_id);
            //print_r($data['prop_dtl']);
        return view('trade/Connection/municipal_licence', $data);

    }
    public function trade_licence_list()
    {
        $data =(array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];

            // echo $login_emp_details_id;
            // die();

            //Transaction Mode List
        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['wardList'] = $wardList;

        $receiver_user_type_id = $emp_mstr["user_type_mstr_id"];
        $ward="";

        $i=0;
        foreach($wardList as $key => $value){
            if($i==0){
                $ward=array($value['ward_mstr_id']);
            }else{
                array_push($ward, $value['ward_mstr_id']);
            }
            $i++;
        }
        if($this->request->getMethod()=='post')
        {
            $data['ward_mstr_id'] = $this->request->getVar('ward_mstr_id');
            $data['from_date'] = $this->request->getVar('from_date');
            $data['to_date'] = $this->request->getVar('to_date');

            if($data['ward_mstr_id']!="")
            {
                $data['posts'] = $this->model_trade_licence->get_wardwiselicence_list($data['from_date'],$data['to_date'],$data['ward_mstr_id']);
            }
            else
            {
            $data['posts'] = $this->model_trade_licence->get_licence_list($data['from_date'],$data['to_date'],$ward);
            }

            $j=0;
            foreach($data['posts'] as $key => $value)
            {
                $wardd = $this->model_ward_mstr->getdatabyid($value['ward_mstr_id']);
                $owner = $this->model_firm_owner_name->applicantdetails($value['apply_licence_id']);
                $j=0;
                foreach($owner as $keyy => $val){
                    if($j==0){
                        $data['posts'][$key]['owner_name']=array($val["owner_name"]);
                        $data['posts'][$key]['mobile_no']=array($val["mobile"]);
                    }else{
                        array_push($data['posts'][$key]['owner_name'], $val["owner_name"]);
                        array_push($data['posts'][$key]['mobile_no'], $val["mobile"]);
                    }
                    $j++;
                }
                $data['posts'][$key]['ward_no'] = $wardd['ward_no'];
            }
            return view('trade/Connection/trade_licence_list', $data);
        }
        else
        {
            $data['from_date'] = date('Y-m-d');
            $data['to_date'] = date('Y-m-d');
            $data['posts'] = $this->model_trade_licence->get_licence_list($data['from_date'],$data['to_date'],$ward);

            $j=0;
            foreach($data['posts'] as $key => $value){
                $wardd = $this->model_ward_mstr->getdatabyid($value['ward_mstr_id']);
                $owner = $this->model_firm_owner_name->applicantdetails($value['apply_licence_id']);
                $j=0;
                foreach($owner as $keyy => $val){
                    if($j==0){
                        $data['posts'][$key]['owner_name']=array($val["owner_name"]);
                        $data['posts'][$key]['mobile_no']=array($val["mobile"]);
                    }else{
                        array_push($data['posts'][$key]['owner_name'], $val["owner_name"]);
                        array_push($data['posts'][$key]['mobile_no'], $val["mobile"]);
                    }
                    $j++;
                }
                $data['posts'][$key]['ward_no'] = $wardd['ward_no'];
            }
            return view('trade/Connection/trade_licence_list', $data);
        }
    }
}