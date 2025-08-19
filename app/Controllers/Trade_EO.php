<?php 
namespace App\Controllers;

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
use App\Models\ModelTradeLicense;
use App\Models\model_trade_licence_validity;
use App\Models\model_trade_item_mstr;
use App\Models\model_prop_dtl;
use App\Models\model_prop_owner_detail;
use App\Models\model_trade_licence_owner_name;
use App\Models\model_trade_licence_trade_items;
use App\Models\TradeTaxdarogaDocumentVerificationModel;
use App\Models\TradeTaxdarogaVerificationModel;
use App\Models\TradeDebarredDtlModel;
use App\Models\TradeTradeItemsModel;
use App\Models\model_application_type_mstr;
use App\Models\model_trade_items_mstr;
use App\Models\TradeTransactionModel;
use App\Models\TradeChequeDtlModel;
use App\Models\trade_view_application_doc_model;
use App\Models\TradeApplyLicenceModel;
use App\Models\TradeApplyDenialModel;

use App\Models\model_trade_sms_log;
use App\Models\Citizensw_trade_model;

use Exception;

class Trade_EO extends AlphaController
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
    protected $ModelTradeLicense;
    protected $model_trade_licence_validity;
    protected $model_trade_item_mstr;
    protected $model_prop_dtl;
    protected $model_prop_owner_detail;
    protected $model_trade_licence_owner_name;
    protected $model_trade_licence_trade_items;
    protected $TradeTaxdarogaDocumentVerificationModel;
    protected $TradeTaxdarogaVerificationModel;
    protected $TradeDebarredDtlModel;
    protected $TradeTradeItemsModel;
	protected $model_application_type_mstr;
	protected $model_trade_items_mstr;
	protected $TradeTransactionModel;
	protected $TradeChequeDtlModel;
    protected $trade_view_application_doc_model;
    protected $TradeApplyLicenceModel;
	protected $TradeApplyDenialModel;
    protected $Citizensw_trade_model;
    protected $model_trade_sms_log;

    public function __construct()
    {
        parent::__construct();
    	helper(['db_helper', 'qr_code_generator_helper','form','form_helper','sms_helper']);
        if($db_name = dbConfig("trade"))
        {
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
        $this->ModelTradeLicense = new ModelTradeLicense($this->db);
        $this->TradeApplyLicenceModel = new TradeApplyLicenceModel($this->db);
        $this->model_trade_licence_validity = new model_trade_licence_validity($this->db);
        $this->model_trade_item_mstr = new model_trade_item_mstr($this->db);
        $this->model_prop_dtl = new model_prop_dtl($this->property_db);
        $this->model_prop_owner_detail = new model_prop_owner_detail($this->property_db);
        $this->model_trade_licence_owner_name = new model_trade_licence_owner_name($this->db);
        $this->model_trade_licence_trade_items = new model_trade_licence_trade_items($this->db);
        $this->TradeTaxdarogaDocumentVerificationModel = new TradeTaxdarogaDocumentVerificationModel($this->db);
        $this->TradeTaxdarogaVerificationModel = new TradeTaxdarogaVerificationModel($this->db);
        $this->TradeDebarredDtlModel = new TradeDebarredDtlModel($this->db);
        $this->TradeTradeItemsModel = new TradeTradeItemsModel($this->db);
		$this->model_application_type_mstr = new model_application_type_mstr($this->db);
		$this->model_trade_items_mstr = new model_trade_items_mstr($this->db);
		$this->TradeTransactionModel = new TradeTransactionModel($this->db);
		$this->TradeChequeDtlModel = new TradeChequeDtlModel($this->db);
        $this->trade_view_application_doc_model = new trade_view_application_doc_model($this->db);
		$this->TradeApplyDenialModel=new TradeApplyDenialModel($this->db);

        $this->model_trade_sms_log = new model_trade_sms_log($this->db);
        $this->Citizensw_trade_model = new Citizensw_trade_model($this->db);  

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
        foreach($wardList as $key => $value){
            if($i==0){
                $ward=array($value['ward_mstr_id']);
            }else{
                array_push($ward, $value['ward_mstr_id']);
            }
            $i++;
         }
        
        if($this->request->getMethod()=='post'){
            $data['ward_mstr_id'] = $this->request->getVar('ward_mstr_id');
             $data['from_date'] = $this->request->getVar('from_date');
             $data['to_date'] = $this->request->getVar('to_date');

            if($data['ward_mstr_id']!="")
            {
                $data['posts'] = $this->model_view_trade_level_pending->waterjereceivebywardidList($receiver_user_type_id,$data['from_date'],$data['to_date'],$data['ward_mstr_id']);
            }
            else{
                $data['posts'] = $this->model_view_trade_level_pending->waterjereceiveList($receiver_user_type_id,$data['from_date'],$data['to_date'],$ward);
            }

            $j=0;
            foreach($data['posts'] as $key => $value){
                $wardd = $this->model_ward_mstr->getdatabyid($value['ward_mstr_id']);
                $licence_details = $this->model_apply_licence->getDatabyid($value['apply_licence_id']);
				$app_type = $this->model_application_type_mstr->trade_application_type_list($licence_details['application_type_id']);
				
                $data['posts'][$key]['ward_no'] = $wardd['ward_no'];
				$data['posts'][$key]['licence_details'] = $licence_details;
				$data['posts'][$key]['app_type'] = $app_type;
            }
            return view('trade/Connection/trade_eo_list', $data);
        }
        else{
            $data['from_date'] = date('Y-m-d');
            $data['to_date'] = date('Y-m-d');
            $data['posts'] = $this->model_view_trade_level_pending->waterjereceiveList($receiver_user_type_id,$data['from_date'],$data['to_date'],$ward);
            $j=0;
            foreach($data['posts'] as $key => $value){
                $wardd = $this->model_ward_mstr->getdatabyid($value['ward_mstr_id']);
                $licence_details = $this->model_apply_licence->getDatabyid($value['apply_licence_id']);
				$app_type = $this->model_application_type_mstr->trade_application_type_list($licence_details['application_type_id']);
				
                $data['posts'][$key]['ward_no'] = $wardd['ward_no'];
				$data['posts'][$key]['licence_details'] = $licence_details;
				$data['posts'][$key]['app_type'] = $app_type;
            }
            return view('trade/Connection/trade_eo_list', $data);
        }
	}



	public function licenceCancel()
	{   
        $data =(array)null;
        if($this->request->getMethod()=='post')
        {
            $inputs = filterSanitizeStringtoUpper($this->request->getVar());
            if($inputs['keyword']<>"")
            {
                $data['keyword']=$inputs['keyword'];
                $data['licence_list'] = $this->ModelTradeLicense->rejectedLicenseListByKeyword($inputs['keyword']);
            }
            else
            {
                $data['from_date']=date("Y-m-d", strtotime($inputs['from_date']));
                $data['to_date']=date("Y-m-d", strtotime($inputs['to_date']));
                $data['licence_list'] = $this->ModelTradeLicense->rejectedLicenseList($data);
            }
        }
        else
        {
            $data['from_date']=date("Y-m-d");
            $data['to_date']=date("Y-m-d");
            $data['licence_list'] = $this->ModelTradeLicense->rejectedLicenseList($data);
        }
        
        return view('trade/Connection/CancelLicence', $data);
	}
	

	public function licenceCancel_old($id=null)
	{   
        $data =(array)null;
        $data['id']=$id;
        if($data['id']=="")
        {
            if($this->request->getMethod()=='post')
            {
                $inputs = filterSanitizeStringtoUpper($this->request->getVar());
                if($inputs['keyword']<>"")
                {
                    $where="tbl_licence.status IN(1,2,3) and (firm_name ilike '%".$inputs['keyword']."%' or licence_no ilike '%".$inputs['keyword']."%')
                    ";
                    $data['licence_list'] = $this->ModelTradeLicense->licence_listkeyword($where);
                }
                else
                {
                    $data['from_date']=$inputs['from_date'];
                    $data['to_date']=$inputs['to_date'];
                    $data['licence_list'] = $this->ModelTradeLicense->licence_listdate($data);
                }
            }
            else
            {
                $data['from_date']=date("d-m-Y");
                $data['to_date']=date("d-m-Y");
                $data['licence_list'] = $this->ModelTradeLicense->licence_listdate($data);
            }
            return view('trade/Connection/CancelLicence', $data);
        }
        else
        {
            $data['cancellicencebyId'] = $this->model_trade_licence->cancellicencebyId($data);
            echo "<script>alert('Licence Successfully Canceled');</script>";
            return view('trade/Connection/CancelLicence', $data);
        }
	}
	
	
	
    public function view($id)
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
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $data['ulb_dtl'] = $this->model_ulb_mstr->getulb_list($data['ulb_mstr_id']);

        
        $login_emp_details_id = $emp_mstr["id"];
        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        $data['form'] = $this->model_view_trade_level_pending->tradelevelpendingdetailbyid($id); 
        if($data['form']['pending_status']==5)
        {
            flashToast("licence","Already Application Approd");
            return $this->response->redirect(base_url('Trade_SH/index'));
        }
        $data['basic_details'] = $this->model_view_trade_licence->getDatabyid(md5($data['form']['apply_licence_id']));
         
        $licence = $data['basic_details'];
        $data['ward']  = $this->model_ward_mstr->getdatabyid($data['form']['ward_mstr_id']);
        $data['form']['ward_no']=$data['ward']['ward_no'];
        $ward_nm=$data['ward']['ward_no'];
        $ward_id=$data['form']['ward_mstr_id'];
        $application_type_id = $data['form']['application_type_id'];
        $licence_id = $data['form']['apply_licence_id'];        

        $data['owner_details'] = $this->model_firm_owner_name->applicantdetails($data['form']['apply_licence_id']);
        
        // data for sms
        $owner_for_sms = $data['owner_details'];
        $application_no = $data['basic_details']['application_no'];
        //end sms data
		$data['holding'] = $this->model_apply_licence->getholding($data['basic_details']['application_no']);
        if (isset($data['basic_details']['holding_no']) && !empty(trim($data['basic_details']['holding_no']))) {
            $prop_id = $this->model_view_trade_licence->getPropetyIdByNewHolding($data['basic_details']['holding_no']);
            if (isset($prop_id['id']))
                $data['PropSafLink'] = base_url() . "/propDtl/full/" . $prop_id['id'];
        }
        

		$data['dd']=array();
        if($data['holding']['nature_of_bussiness'])
        {

            $data['nature_business'] =$this->model_trade_items_mstr->nature_business($data['holding']['nature_of_bussiness']);
            foreach($data['nature_business'] as $val)
            {
                $data['dd']=$val;
            }
        }
        
        $data['nature_business']['trade_item'] = is_array($data['dd'])? implode('<b>,</b><br>',$data['dd']):$data['dd'];

        // if($login_emp_details_id==1436)
        // {
        //     print_var($data);die;

        // }
        
		$data['payment_dtls'] = $this->TradeTransactionModel->payment_details($data['basic_details']['id']);
        $data['payment_dtls']=array_reverse( $data['payment_dtls']);        
        //print_var($data['payment_dtls']);die;
        //$data['cheque_dtls']=[];
        if(!empty($data['payment_dtls']))
        {
            //$data['payment_dtls']=$data['payment_dtls'][0];
            //$data['cheque_dtls'] = $this->TradeChequeDtlModel->cheque_details($data['payment_dtls'][0]['id']);

        }
        
        $apply_licence_id=$data['basic_details']['id'];

        $data['documents']=$this->trade_view_application_doc_model->getAllActiveDocuments($apply_licence_id);

        $data['siteInspectionRemark'] = $this->TradeTaxdarogaVerificationModel->siteInspectionRemarks($apply_licence_id);
        $data['area_in_sqft']=$data['siteInspectionRemark']['area_in_sqft'];

        //Get All Level Remarks
        $data['dealingLevel'] = $this->model_trade_level_pending_dtl->getDealingLevelData($apply_licence_id);
        $data['delingReceiveDate'] = $this->model_trade_level_pending_dtl->getDealingReceiveDate($apply_licence_id);
        //Tax Daroga
        $data['taxDarogaLevel'] = $this->model_trade_level_pending_dtl->getTaxDarogaLevelData($apply_licence_id);
        $data['taxDarogaReceiveDate'] = $this->model_trade_level_pending_dtl->getTaxDarogaReceiveDate($apply_licence_id);
        //End Tax Daroga
        //start Section Head
        $data['sectionHeadLevel'] = $this->model_trade_level_pending_dtl->getSectionHeadLevelData($apply_licence_id);
        $data['sectionHeadReceiveDate'] = $this->model_trade_level_pending_dtl->getSectionHeadReceiveDate($apply_licence_id);
        //End Section Head
        //Start Executive 
        $data['executiveLevel'] = $this->model_trade_level_pending_dtl->getExecutiveLevelData($apply_licence_id);
        $data['executiveReceiveDate'] = $this->model_trade_level_pending_dtl->getExecutiveReceiveDate($apply_licence_id);
        //End Executive

        //get Trade Item List
        // $nature_of_business = $this->TradeTradeItemsModel->getTradeItemDetais($apply_licence_id);
        $apply_date=$data['basic_details']['apply_date'];
        $old_license_id= $data['basic_details']['update_status'];
        if($this->request->getMethod()=='post')
        {   
            
            
            # Approve Application
            if(isset($_POST['btn_approved_submit']))
            {
               
                $licence_for_years = $data['holding']["licence_for_years"];
                # Making a License No
                $ulbShortName = strtoupper(substr($data['ulb_dtl']['ulb_name'], 0, 3));
                $ward_no = $data['ward']['ward_no'];
                $license_no = $ulbShortName.$ward_no.date("mdY").$apply_licence_id;
                $data = [
                        'remarks' => $this->request->getVar('remarks'),
                        'radio_fapproval' => $this->request->getVar('radio_fapproval'),
                        'level_pending_dtl_id' => $id,
                        'apply_license_id' => $apply_licence_id,
                        'apply_licence_id' => $apply_licence_id,
                        'license_no' => $license_no,
                        'license_date' => date('Y-m-d'),
                        'ward_mstr_id' => $data['form']['ward_mstr_id'],
                        'emp_details_id' => $login_emp_details_id,
                        'created_on' => date('Y-m-d H:i:s'),
                        'forward_date' => date('Y-m-d'),
                        'forward_time' => date('H:i:s'),
                        'sender_user_type_id' => $sender_user_type_id,
                        'receiver_user_type_id'=> 20,
                        'level_pending_status'=> 5, //Approve
                        "approved_by"=>$login_emp_details_id,
                    ];                      

                    
                    if($updateverify = $this->model_trade_level_pending_dtl->updatelevelpendingById($data))
                    {
                       if($updatependingstts = $this->model_apply_licence->update_level_pending_status($data))
                       {
                            # 1	NEW LICENSE
                            if($application_type_id == 1)
                            {
                                // update new license_no
                                $this->TradeApplyLicenceModel->updateLicenseNo($data);

                                // update license validity
                                $data['valid_upto']=date("Y-m-d", strtotime("+$licence_for_years years", strtotime($apply_date)));
                                $this->TradeApplyLicenceModel->updateLicenseValidity($data);

                                // update tbl_apply_license set status=5
                                $this->TradeApplyLicenceModel->approveLicense($apply_licence_id);
                                /**********sms send *************/
                                $sms = Trade(array('application_no'=>$application_no,'licence_no'=>$license_no,'ulb_name'=>session()->get('ulb_dtl')['ulb_name']),'Application Approved');
                                if($sms['status']==true)
                                {
                                    foreach($owner_for_sms as $val)
                                    {
                                        $message= $sms['sms'];
                                        $templateid= $sms['temp_id'];
                                        $sms_data = [
                                            'emp_id'=>$login_emp_details_id,
                                            'ref_id'=>$apply_licence_id,
                                            'ref_type'=>'tbl_apply_licence',
                                            'mobile_no'=>$val['mobile'],
                                            'purpose'=>strtoupper('Application Approved'),
                                            'template_id'=>$templateid,
                                            'message'=>$message                                
                                            ];
                                        $sms_id = $this->model_trade_sms_log->insert_sms_log($sms_data);
                                        if($sms_id)
                                        {
                                            $res=send_sms($val['mobile'], $message, $templateid);                                            
                                            if($res)
                                            {
                                                $update=[
                                                    'response'=>$res['response'],
                                                    'smgid'=>$res['msg'],
                                                ];
                                                $where =['id'=>$sms_id];
                                                $update = $this->model_trade_sms_log->update_sms_log($where,$update);
                                            }
                                        }

                                        
                                    }

                                }
                                // $this->db->transRollback();                    
                                // print_var($sms);die; 
                                /***********end sms send*********************/
                                #------------sws push------------------
                                $sws_whare = ['apply_license_id'=>$apply_licence_id];
                                $sws = $this->Citizensw_trade_model->getData($sws_whare);
                                //print_var($sws);
                                //die;
                                if($licence['apply_from']=='sws' && !empty($sws))
                                {                    
                                    $sw = [];
                                    $sw['sw_status']= 20 ; 
                                    $sw['application_statge']= 5 ;
                                    $where_sw = ['apply_license_id'=>$apply_licence_id,'id'=> $sws['id']];                            
                                    $this->Citizensw_trade_model->updateData($sw,$where_sw);

                                    $push_sw=array();
                                    $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id']; 
                                    $path= base_url('citizenPaymentReceipt/municipal_licence/'.$ulb_mstr_id.'/'.md5($apply_licence_id));
                                    $push_sw['application_stage']=20;
                                    $push_sw['status']='Application Aprroved';
                                    $push_sw['acknowledgment_no']=$licence['application_no'];
                                    $push_sw['service_type_id']=$sws['service_id'];
                                    $push_sw['caf_unique_no']=$sws['caf_no'];
                                    $push_sw['department_id']=$sws['department_id'];
                                    $push_sw['Swsregid']=$sws['cust_id'];
                                    $push_sw['payable_amount ']='';
                                    $push_sw['payment_validity']='';
                                    $push_sw['payment_other_details']='';
                                    $push_sw['certificate_url']=$path;
                                    $push_sw['approval_date']=date('Y-m-d H:i:s');
                                    $push_sw['expire_date']=$data['valid_upto'];
                                    $push_sw['licence_no']=$license_no;
                                    $push_sw['certificate_no']=$licence['provisional_license_no'];
                                    $push_sw['customer_id']=$sws['cust_id'];
                                    $post_url = getenv('single_indow_push_url');
                                    $http = getenv('single_indow_push_http');
                                    $resp = httpPostJson($post_url,$push_sw,$http);
                                    // print_var($push_sw);
                                    // print_var($resp);die;
                                    $respons_data=[];
                                    $respons_data['apply_license_id']=$apply_licence_id;
                                    $respons_data['response_msg']= json_encode(['url'=>$http.'/'.$post_url,
                                                                                'data'=>$push_sw]);
                                    $respons_data['tbl_single_window_id']=$sws['id'];
                                    $respons_data['emp_id']=$login_emp_details_id;
                                    $respons_data['response_status']=json_encode($resp);
                                    $this->Citizensw_trade_model->insertResponse($respons_data);

                                }           
                                #--------------------------------------
                                flashToast('licence','Application Approved Successfully!!!');
                                return $this->response->redirect(base_url('Trade_EO/municipal_licence/'.md5($apply_licence_id)));
                            }

                            # 2 RENEWAL
                            if($application_type_id == 2)
                            {
                                $sql = " select * from tbl_apply_licence 
                                        where update_status=$apply_licence_id and status = 1 
                                        order by id desc limit 1 ";
                                $prive_licence = $this->TradeApplyLicenceModel->rowQury($sql);
                                
                                if(!empty($prive_licence))
                                {                                    
                                    $prive_licence_id = $prive_licence['id'];
                                    $licence_no = $prive_licence['license_no'];
                                    $valid_from = $prive_licence['valid_upto'];
                                    // $test_data = re_day_diff($valid_from,$licence_for_years);
                                    //if($test_data['diff_day']>0)
                                    {
                                        $datef = date('Y-m-d', strtotime($valid_from));
                                        $datefrom = date_create($datef);
                                        $datea = date('Y-m-d', strtotime($apply_date));
                                        $dateapply = date_create($datea);
                                        $year_diff = date_diff($datefrom, $dateapply);
                                        $year_diff =  $year_diff->format('%y');

                                        $priv_m_d = date('m-d', strtotime($valid_from));
                                        $date = date('Y',strtotime($valid_from)) . '-' . $priv_m_d;
                                        $licence_for_years2 = $licence_for_years + $year_diff; 
                                        $vali_upto = date('Y-m-d', strtotime($date . "+" . $licence_for_years2 . " years"));
                                        $data['valid_upto'] = $vali_upto;
                                        // $data['valid_upto']=$test_data['valid_upto'];
                                        // update license validity
                                        $this->TradeApplyLicenceModel->updateLicenseValidity($data);
                                        // update tbl_apply_license set status=5
                                        $this->TradeApplyLicenceModel->approveLicense($apply_licence_id);
                                        
                                        //$sql_update_priv_l = "update tbl_apply_licence set status = 0 where id = $prive_licence_id ";
                                        //$this->TradeApplyLicenceModel->rowQury($sql_update_priv_l);
                                        $sql_updae_l_no = "update tbl_apply_licence set license_no = '$licence_no' where id = $apply_licence_id ";
                                        $this->TradeApplyLicenceModel->rowQury($sql_updae_l_no);
                                        
                                        /***********sms send*********************/
                                        $sms = Trade(array('application_no'=>$application_no,'licence_no'=>$licence_no,'ulb_name'=>session()->get('ulb_dtl')['ulb_name']),'Application Approved');
                                        if($sms['status']==true)
                                        {
                                            foreach($owner_for_sms as $val)
                                            {
                                                $message= $sms['sms'];
                                                $templateid= $sms['temp_id'];
                                                $sms_data = [
                                                    'emp_id'=>$login_emp_details_id,
                                                    'ref_id'=>$apply_licence_id,
                                                    'ref_type'=>'tbl_apply_licence',
                                                    'mobile_no'=>$val['mobile'],
                                                    'purpose'=>strtoupper('RENEWAL'),
                                                    'template_id'=>$templateid,
                                                    'message'=>$message                                
                                                    ];
                                                $sms_id = $this->model_trade_sms_log->insert_sms_log($sms_data);
                                                if($sms_id)
                                                {
                                                    $res=send_sms($val['mobile'], $message, $templateid);
                                                    if($res)
                                                    {
                                                        $update=[
                                                            'response'=>$res['response'],
                                                            'smgid'=>$res['msg'],
                                                        ];
                                                        $where =['id'=>$sms_id];
                                                        $update = $this->model_trade_sms_log->update_sms_log($where,$update);
                                                    }
                                                }

                                                
                                            }

                                        }
                                        // $this->db->transRollback();                    
                                        // print_var($sms);die; 
                                        /***********end sms send*********************/
                                        #------------sws push------------------
                                        $sws_whare = ['apply_license_id'=>$apply_licence_id];
                                        $sws = $this->Citizensw_trade_model->getData($sws_whare);
                                        //print_var($sws);
                                        //die;
                                        if($licence['apply_from']=='sws' && !empty($sws))
                                        {                    
                                            $sw = [];
                                            $sw['sw_status']= 20 ; 
                                            $sw['application_statge']= 5 ;
                                            $where_sw = ['apply_license_id'=>$apply_licence_id,'id'=> $sws['id']];                            
                                            $this->Citizensw_trade_model->updateData($sw,$where_sw);

                                            $push_sw=array();
                                            $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id']; 
                                            $path= base_url('citizenPaymentReceipt/municipal_licence/'.$ulb_mstr_id.'/'.md5($apply_licence_id));
                                            $push_sw['application_stage']=20;
                                            $push_sw['status']='Application Aprroved';
                                            $push_sw['acknowledgment_no']=$licence['application_no'];
                                            $push_sw['service_type_id']=$sws['service_id'];
                                            $push_sw['caf_unique_no']=$sws['caf_no'];
                                            $push_sw['department_id']=$sws['department_id'];
                                            $push_sw['Swsregid']=$sws['cust_id'];
                                            $push_sw['payable_amount ']='';
                                            $push_sw['payment_validity']='';
                                            $push_sw['payment_other_details']='';
                                            $push_sw['certificate_url']=$path;
                                            $push_sw['approval_date']=date('Y-m-d H:i:s');
                                            $push_sw['expire_date']=$data['valid_upto'];
                                            $push_sw['licence_no']=$licence_no;
                                            $push_sw['certificate_no']=$licence['provisional_license_no'];
                                            $push_sw['customer_id']=$sws['cust_id'];
                                            $post_url = getenv('single_indow_push_url');
                                            $http = getenv('single_indow_push_http');
                                            $resp = httpPostJson($post_url,$push_sw,$http);
                                            // print_var($push_sw);
                                            // print_var($resp);die;
                                            $respons_data=[];
                                            $respons_data['apply_license_id']=$apply_licence_id;
                                            $respons_data['response_msg']= json_encode(['url'=>$http.'/'.$post_url,
                                                                                        'data'=>$push_sw]);
                                            $respons_data['tbl_single_window_id']=$sws['id'];
                                            $respons_data['emp_id']=$login_emp_details_id;
                                            $respons_data['response_status']=json_encode($resp);
                                            $this->Citizensw_trade_model->insertResponse($respons_data);

                                        }           
                                        #--------------------------------------

                                        flashToast('licence','Application Approved Successfully!!!');
                                        
                                    }
                                    // else
                                    // {
                                    //     flashToast('licence','Some Error Occurred On Licence Validity Please Contact to Admin!!!');
                                    // }
                                    
                                }
                                else
                                {
                                    $this->db->transRollback();
                                    flashToast('licence','Some Error Occurred Please Contact to Admin!!!');
                                    return $this->response->redirect(base_url('Trade_EO/view/'.$id));
                                }
                                
                                
                                return $this->response->redirect(base_url('Trade_EO/municipal_licence/'.md5($apply_licence_id)));
                            }

                            # 3	AMENDMENT
                            if($application_type_id == 3)
                            {
                                // update license validity
                                # Previous validity or one year, whichever geater
                                $old_license = $this->model_view_trade_licence->getDatabyid(md5($old_license_id));
                                $oneYear_validity=date("Y-m-d", strtotime("+1 years", strtotime('now')));
                                $previous_validity=$old_license['valid_upto'];
                                if($previous_validity>$oneYear_validity)
                                $data['valid_upto'] = $previous_validity;
                                else
                                $data['valid_upto'] = $oneYear_validity;
                                $data['valid_from'] = date('Y-m-d');
                                $this->TradeApplyLicenceModel->updateLicenseValidity($data);

                                // update tbl_apply_license set status=5
                                $this->TradeApplyLicenceModel->approveLicense($apply_licence_id);

                                /***********sms send*********************/
                                $licence_no = $old_license['license_no'];
                                $sms = Trade(array('application_no'=>$application_no,'licence_no'=>$licence_no,'ulb_name'=>session()->get('ulb_dtl')['ulb_name']),'Application Approved');
                                if($sms['status']==true)
                                {
                                    foreach($owner_for_sms as $val)
                                    {
                                        $message= $sms['sms'];
                                        $templateid= $sms['temp_id'];
                                        $sms_data = [
                                            'emp_id'=>$login_emp_details_id,
                                            'ref_id'=>$apply_licence_id,
                                            'ref_type'=>'tbl_apply_licence',
                                            'mobile_no'=>$val['mobile'],
                                            'purpose'=>strtoupper('AMENDMENT'),
                                            'template_id'=>$templateid,
                                            'message'=>$message                                
                                            ];
                                        $sms_id = $this->model_trade_sms_log->insert_sms_log($sms_data);
                                        if($sms_id)
                                        {
                                            $res=send_sms($val['mobile'], $message, $templateid);
                                            if($res)
                                            {
                                                $update=[
                                                    'response'=>$res['response'],
                                                    'smgid'=>$res['msg'],
                                                ];
                                                $where =['id'=>$sms_id];
                                                $update = $this->model_trade_sms_log->update_sms_log($where,$update);
                                            }
                                        }

                                        
                                    }

                                }
                                // $this->db->transRollback();                    
                                // print_var($sms);die; 
                                /***********end sms send*********************/
                                #------------sws push------------------
                                $sws_whare = ['apply_license_id'=>$apply_licence_id];
                                $sws = $this->Citizensw_trade_model->getData($sws_whare);
                                //print_var($sws);
                                //die;
                                if($licence['apply_from']=='sws' && !empty($sws))
                                {                    
                                    $sw = [];
                                    $sw['sw_status']= 20 ; 
                                    $sw['application_statge']= 5 ;
                                    $where_sw = ['apply_license_id'=>$apply_licence_id,'id'=> $sws['id']];                            
                                    $this->Citizensw_trade_model->updateData($sw,$where_sw);

                                    $push_sw=array();
                                    $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id']; 
                                    $path= base_url('citizenPaymentReceipt/municipal_licence/'.$ulb_mstr_id.'/'.md5($apply_licence_id));
                                    $push_sw['application_stage']=20;
                                    $push_sw['status']='Application Aprroved';
                                    $push_sw['acknowledgment_no']=$licence['application_no'];
                                    $push_sw['service_type_id']=$sws['service_id'];
                                    $push_sw['caf_unique_no']=$sws['caf_no'];
                                    $push_sw['department_id']=$sws['department_id'];
                                    $push_sw['Swsregid']=$sws['cust_id'];
                                    $push_sw['payable_amount ']='';
                                    $push_sw['payment_validity']='';
                                    $push_sw['payment_other_details']='';
                                    $push_sw['certificate_url']=$path;
                                    $push_sw['approval_date']=date('Y-m-d H:i:s');
                                    $push_sw['expire_date']=$data['valid_upto'];
                                    $push_sw['licence_no']=$licence_no;
                                    $push_sw['certificate_no']=$licence['provisional_license_no'];
                                    $push_sw['customer_id']=$sws['cust_id'];
                                    $post_url = getenv('single_indow_push_url');
                                    $http = getenv('single_indow_push_http');
                                    $resp = httpPostJson($post_url,$push_sw,$http);
                                    // print_var($push_sw);
                                    // print_var($resp);die;
                                    $respons_data=[];
                                    $respons_data['apply_license_id']=$apply_licence_id;
                                    $respons_data['response_msg']= json_encode(['url'=>$http.'/'.$post_url,
                                                                                'data'=>$push_sw]);
                                    $respons_data['tbl_single_window_id']=$sws['id'];
                                    $respons_data['emp_id']=$login_emp_details_id;
                                    $respons_data['response_status']=json_encode($resp);
                                    $this->Citizensw_trade_model->insertResponse($respons_data);

                                }           
                                #--------------------------------------

                                flashToast('licence','Application Approved Successfully!!!');
                                return $this->response->redirect(base_url('Trade_EO/municipal_licence/'.md5($apply_licence_id)));
                            }
                            
                            # 4 SURRENDER
                            if($application_type_id==4)
                            {
                                // Incase of surrender valid upto is previous license validity
                                $old_license = $this->model_view_trade_licence->getDatabyid(md5($old_license_id));
                                $data['valid_upto'] = $old_license['valid_upto'];

                                // update license validity
                                $this->TradeApplyLicenceModel->updateLicenseValidity($data);

                                // update tbl_apply_license set status=5
                                $this->TradeApplyLicenceModel->approveLicense($apply_licence_id);
                                
                                /***********sms send*********************/
                                $licence_no = $old_license['license_no'];
                                $sms = Trade(array('application_no'=>$application_no,'licence_no'=>$licence_no,'ulb_name'=>session()->get('ulb_dtl')['ulb_name']),'Application Approved');
                                if($sms['status']==true)
                                {
                                    foreach($owner_for_sms as $val)
                                    {
                                        $message= $sms['sms'];
                                        $templateid= $sms['temp_id'];
                                        $sms_data = [
                                            'emp_id'=>$login_emp_details_id,
                                            'ref_id'=>$apply_licence_id,
                                            'ref_type'=>'tbl_apply_licence',
                                            'mobile_no'=>$val['mobile'],
                                            'purpose'=>strtoupper('SURRENDER'),
                                            'template_id'=>$templateid,
                                            'message'=>$message                                
                                            ];
                                        $sms_id = $this->model_trade_sms_log->insert_sms_log($sms_data);
                                        if($sms_id)
                                        {
                                            $res=send_sms($val['mobile'], $message, $templateid);
                                            if($res)
                                            {
                                                $update=[
                                                    'response'=>$res['response'],
                                                    'smgid'=>$res['msg'],
                                                ];
                                                $where =['id'=>$sms_id];
                                                $update = $this->model_trade_sms_log->update_sms_log($where,$update);
                                            }
                                        }

                                        
                                    }

                                }
                                // $this->db->transRollback();                    
                                // print_var($sms);die; 
                                /***********end sms send*********************/
                                #------------sws push------------------
                                $sws_whare = ['apply_license_id'=>$apply_licence_id];
                                $sws = $this->Citizensw_trade_model->getData($sws_whare);
                                //print_var($sws);
                                //die;
                                if($licence['apply_from']=='sws' && !empty($sws))
                                {                    
                                    $sw = [];
                                    $sw['sw_status']= 20 ; 
                                    $sw['application_statge']= 5 ;
                                    $where_sw = ['apply_license_id'=>$apply_licence_id,'id'=> $sws['id']];                            
                                    $this->Citizensw_trade_model->updateData($sw,$where_sw);

                                    $push_sw=array();
                                    $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id']; 
                                    $path= base_url('TradeCitizen/trade_licence_view/'.md5($apply_licence_id));
                                    $push_sw['application_stage']=20;
                                    $push_sw['status']='Application Aprroved';
                                    $push_sw['acknowledgment_no']=$licence['application_no'];
                                    $push_sw['service_type_id']=$sws['service_id'];
                                    $push_sw['caf_unique_no']=$sws['caf_no'];
                                    $push_sw['department_id']=$sws['department_id'];
                                    $push_sw['Swsregid']=$sws['cust_id'];
                                    $push_sw['payable_amount ']='';
                                    $push_sw['payment_validity']='';
                                    $push_sw['payment_other_details']='';
                                    $push_sw['certificate_url']=$path;
                                    $push_sw['approval_date']=date('Y-m-d H:i:s');
                                    $push_sw['expire_date']=$data['valid_upto'];
                                    $push_sw['licence_no']=$licence_no;
                                    $push_sw['certificate_no']=$licence['provisional_license_no'];
                                    $push_sw['customer_id']=$sws['cust_id'];
                                    $post_url = getenv('single_indow_push_url');
                                    $http = getenv('single_indow_push_http');
                                    $resp = httpPostJson($post_url,$push_sw,$http);
                                    // print_var($push_sw);
                                    // print_var($resp);die;
                                    $respons_data=[];
                                    $respons_data['apply_license_id']=$apply_licence_id;
                                    $respons_data['response_msg']= json_encode(['url'=>$http.'/'.$post_url,
                                                                                'data'=>$push_sw]);
                                    $respons_data['tbl_single_window_id']=$sws['id'];
                                    $respons_data['emp_id']=$login_emp_details_id;
                                    $respons_data['response_status']=json_encode($resp);
                                    $this->Citizensw_trade_model->insertResponse($respons_data);

                                }           
                                #--------------------------------------

                                flashToast('licence','License Surrendered Successfully!!!');
                                return $this->response->redirect(base_url('Trade_EO/index/'));
                            }
                       }
                    }
                    
            }

            # Backward
            if(isset($_POST['btn_backward_submit']))
            {
                
                $data['lastRecord'] = 
                            $this->model_trade_level_pending_dtl->getLastRecord($data['form']['id']);
                $apply_licence_id2=$data['lastRecord']["apply_licence_id"];
                $level_last_deta = $this->model_trade_level_pending_dtl->getDataNew(['id'=>$data['lastRecord']['id']],'*','tbl_level_pending');

                $data = [
                        'remarks' => $this->request->getVar('remarks'),
                         'level_pending_dtl_id' => $id,
                         'apply_licence_id' => $apply_licence_id,
                         'emp_details_id' => $login_emp_details_id,
                         'created_on' =>date('Y-m-d H:i:s'),
                         'forward_date' =>date('Y-m-d'),
                         'forward_time' =>date('H:i:s'),
                         'sender_user_type_id' => $sender_user_type_id,
                         'receiver_user_type_id'=> 18,
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
                

                if($updatebackward = $this->model_trade_level_pending_dtl->updatelevelpendingById($data))
                {
                    $this->model_trade_level_pending_dtl->tbl_level_sent_back_dtl($btcdata);
                    if($insertbackward = $this->model_trade_level_pending_dtl->insrtlevelpendingdtl($data))
                    {
                        flashToast('licence', 'Application Backwarded To The Section Head !!!');
                        return $this->response->redirect(base_url('Trade_EO/index/'));
                    }
                }
            }

            # Back to Citizen
            if(isset($_POST['btn_backToCitizen']))
            {
                $apply_licence_id = $this->request->getVar('apply_licence_id');

                $level_pending_dtl_id = $this->model_trade_level_pending_dtl->getLevelPendingDetailsIdForExecutiveOfficer($apply_licence_id);
                $data['basic_details'] = $this->model_view_trade_licence->getDatabyid($apply_licence_id);
                $id=$data['basic_details']['id'];

                $data['lastRecord'] = 
                            $this->model_trade_level_pending_dtl->getLastRecord($level_pending_dtl_id);
                $apply_licence_id2=$data['lastRecord']["apply_licence_id"];
                $level_last_deta = $this->model_trade_level_pending_dtl->getDataNew(['id'=>$data['lastRecord']['id']],'*','tbl_level_pending');

                $data = [
                         'remarks' => $this->request->getVar('remarks'),
                         'level_pending_dtl_id' => md5($level_pending_dtl_id),
                         'apply_licence_id' => $id,
                         'emp_details_id' => $login_emp_details_id,
                         'created_on' =>date('Y-m-d H:i:s'),
                         'current_date' =>date('Y-m-d'),
                         'forward_date' =>date('Y-m-d'),
                         'forward_time' =>date('H:i:s'),
                         'sender_user_type_id' => $sender_user_type_id,
                         'receiver_user_type_id'=>11,
                         'level_pending_status'=>2
                        ];
                $btcdata = [
                    'remarks' => $this->request->getVar('remarks'),
                    'level_id' => $level_last_deta["id"],
                    'apply_licence_id' => $apply_licence_id2,
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

                if($updatebacktocitizen = $this->model_trade_level_pending_dtl->updatebacktocitizenById($data))
                {
                    $this->model_trade_level_pending_dtl->tbl_level_sent_back_dtl($btcdata);
                    if($updatesafpendingstts = $this->model_apply_licence->update_level_pending_status($data))
                    {
                        /**********sms send *************/
                        $sms = Trade(array('application_no'=>$application_no),'sent back');
                        if($sms['status']==true)
                        {
                            foreach($owner_for_sms as $val)
                            {
                                $message= $sms['sms'];
                                $templateid= $sms['temp_id'];
                                $sms_data = [
                                    'emp_id'=>$login_emp_details_id,
                                    'ref_id'=>$id,
                                    'ref_type'=>'tbl_apply_licence',
                                    'mobile_no'=>$val['mobile'],
                                    'purpose'=>'Back to Citizen',
                                    'template_id'=>$templateid,
                                    'message'=>$message                                
                                    ];
                                $sms_id = $this->model_trade_sms_log->insert_sms_log($sms_data);
                                if($sms_id)
                                {
                                    $res=send_sms($val['mobile'], $message, $templateid);
                                    if($res)
                                    {
                                        $update=[
                                            'response'=>$res['response'],
                                            'smgid'=>$res['msg'],
                                        ];
                                        $where =['id'=>$sms_id];
                                        $update = $this->model_trade_sms_log->update_sms_log($where,$update);
                                    }
                                }

                                
                            }

                        }
                        // $this->db->transRollback();                    
                        // print_var($sms);die; 
                        /***********end sms send*********************/
                        flashToast('licence','Application Sent Back to Citizen !!!');
                        return $this->response->redirect(base_url('Trade_EO/index/'));
                    }
                }
            }

            if(isset($_POST['btn_reject']))
            {
                $apply_licence_id = $this->request->getVar('apply_licence_id');
                $level_pending_dtl_id = $this->model_trade_level_pending_dtl->getLevelPendingDetailsIdForExecutiveOfficer($apply_licence_id);
                $remarks = $this->request->getVar('remarks');

                //update tbl_apply_license set pending_status=4
                if($rejectApplication=$this->model_apply_licence->updateRejectStatus($apply_licence_id,$login_emp_details_id))
                {
                    //Update Level Pending 
                    $this->model_trade_level_pending_dtl->updateRejectStatus($level_pending_dtl_id,$remarks,$login_emp_details_id);
                    //update firm Owner details status
                    $this->model_firm_owner_name->updateRejectStatus($apply_licence_id,$login_emp_details_id);
                    flashToast('licence','Application Rejected Successfully!!!');
                    return $this->response->redirect(base_url('Trade_EO/index/'));
                }
                else
                {
                    flashToast('licence','Fail To Reject Application!!!');
                    return $this->response->redirect(base_url('Trade_EO/index/'));
                }
            }
            
        }
        else
        {
                       
            return view('trade/Connection/trade_eo_view', $data);
        }
    }

    public function bulkApproveEO()
    {
        $data = (array)null;
        $Session = Session();        
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $data['ulb_dtl'] = $this->model_ulb_mstr->getulb_list($data['ulb_mstr_id']);        
        $login_emp_details_id = $emp_mstr["id"];
        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];

        if($this->request->getMethod()=='post')
        {
            $data['item'] = $_POST;

            foreach($data['item']['selecteditems'] as $my_level_ids)
            {
                $this->db->transBegin();
                $level_data_sql = " select * from tbl_level_pending where id  = $my_level_ids "; 
                $level_data = $this->model_trade_level_pending_dtl->rowQuery($level_data_sql)[0]??[];                
                if(!$level_data)
                {
                    $this->db->transCommit();
                    continue;
                }
                elseif(!in_array($level_data['status'],[1]))
                {    
                    $this->db->transCommit();                
                    continue;
                }
                $licence_id = $level_data['apply_licence_id'];
                $data['licence'] = $this->model_apply_licence->getDatabyid($licence_id);
                if($data['licence']['pending_status']==5)
                {
                    $this->db->transCommit();
                    continue;
                }
               
                $data['ward']  = $this->model_ward_mstr->getdatabyid($data['licence']['ward_mstr_id']);
                $data['licence']['ward_no'] = $data['ward']['ward_no'];
                $application_type_id = $data['licence']['application_type_id'];
                
                $data['owner_details'] = $this->model_firm_owner_name->applicantdetails($licence_id);
                // data for sms
                $owner_for_sms = $data['owner_details'];
                $application_no = $data['licence']['application_no'];
                //end sms data
                $data['holding'] = $this->model_apply_licence->getholding($data['licence']['application_no']);
                $data['dd'] = array();
                
                if ($data['holding']['nature_of_bussiness'])
                    $data['nature_business'] = $this->model_trade_items_mstr->nature_business($data['holding']['nature_of_bussiness']);
                foreach ($data['nature_business'] as $val) 
                {
                    $data['dd'] = $val;
                }
    
                $data['nature_business']['trade_item'] = is_array($data['dd']) ? implode('<b>,</b><br>', $data['dd']) : $data['dd'];
                $data['payment_dtls'] = $this->TradeTransactionModel->payment_details($data['licence']['id']);
                $data['payment_dtls'] = array_reverse($data['payment_dtls']);
                
                $apply_date = $data['licence']['apply_date'];
                $old_license_id = $data['licence']['update_status'];                
                # Approve Application
                if (isset($_POST['btn_approved_submit'])) 
                {
                    $licence_for_years = $data['licence']["licence_for_years"];
                    # Making a License No
                    $ulbShortName = strtoupper(substr($data['ulb_dtl']['ulb_name'], 0, 3));
                    $ward_no = $data['ward']['ward_no'];
                    $license_no = $ulbShortName . $ward_no . date("mdY") . $licence_id;
                    $level_update = [
                        'remarks' => 'license approved by EO',
                        'radio_fapproval' => '',
                        'level_pending_dtl_id' => md5($my_level_ids),
                        'apply_license_id' => $licence_id,
                        'apply_licence_id' => $licence_id,
                        'license_no' => $license_no,
                        'license_date' => date('Y-m-d'),
                        'ward_mstr_id' => $data['licence']['ward_mstr_id'],
                        'emp_details_id' => $login_emp_details_id,
                        'created_on' => date('Y-m-d H:i:s'),
                        'forward_date' => date('Y-m-d'),
                        'forward_time' => date('H:i:s'),
                        'sender_user_type_id' => $sender_user_type_id,
                        'receiver_user_type_id' => 20,
                        'level_pending_status' => 5, //Approve
                        "approved_by"=>$login_emp_details_id,
                    ];


                    if ($updateverify = $this->model_trade_level_pending_dtl->updatelevelpendingById($level_update)) 
                    {
                        if ($updatependingstts = $this->model_apply_licence->update_level_pending_status($level_update)) 
                        {
                            $purpose="";
                            # 1 NEW LICENSE
                            if ($application_type_id == 1) 
                            {
                                $purpose = strtoupper('Application Approved');
                                // update new license_no
                                $this->TradeApplyLicenceModel->updateLicenseNo($level_update);

                                // update license validity
                                $level_update['valid_from']=$apply_date;
                                $level_update['valid_upto'] = date("Y-m-d", strtotime("+$licence_for_years years", strtotime($apply_date)));
                                $this->TradeApplyLicenceModel->updateLicenseValidity($level_update);
                            }

                            # 2 RENEWAL
                            if ($application_type_id == 2) 
                            {
                                $purpose = strtoupper('RENEWAL');
                                $sql = " select * from tbl_apply_licence 
                                                where update_status=$licence_id and status = 1 
                                                order by id desc limit 1 ";
                                $prive_licence = $this->TradeApplyLicenceModel->rowQury($sql);
                                if (!empty($prive_licence)) 
                                {
                                    $prive_licence_id = $prive_licence['id'];
                                    $license_no = $prive_licence['license_no'];
                                    $valid_from = $prive_licence['valid_upto'];

                                    $datef = date('Y-m-d', strtotime($valid_from));
                                    $datefrom = date_create($datef);

                                    $datea = date('Y-m-d', strtotime($apply_date));

                                    $dateapply = date_create($datea);

                                    $year_diff = date_diff($datefrom, $dateapply);

                                    $year_diff =  $year_diff->format('%y') . "";

                                    $priv_m_d = date('m-d', strtotime($valid_from));
                                    $date = date('Y', strtotime($valid_from)) . '-' . $priv_m_d;
                                   
                                    $licence_for_years2 = $licence_for_years + $year_diff;
                                    $vali_upto = date('Y-m-d', strtotime($date . "+" . $licence_for_years2 . " years"));

                                    $level_update['valid_from']=$valid_from;
                                    $level_update['valid_upto'] = $vali_upto;
                                    $this->TradeApplyLicenceModel->updateLicenseValidity($level_update);
                                    $this->TradeApplyLicenceModel->approveLicense($licence_id);
                                    $sql_updae_l_no = "update tbl_apply_licence set license_no = '$license_no' where id = $licence_id ";
                                    $this->TradeApplyLicenceModel->rowQury($sql_updae_l_no);

                                } 
                                else 
                                {
                                    $this->db->transRollback();
                                    continue;                                    
                                }
                            }

                            # 3 AMENDMENT
                            if ($application_type_id == 3) 
                            {
                                $purpose = strtoupper('AMENDMENT');
                                $old_license = $this->model_view_trade_licence->getDatabyid(md5($old_license_id));
                                $oneYear_validity = date("Y-m-d", strtotime("+1 years", strtotime('now')));
                                $license_no = $old_license['license_no'];
                                $previous_validity = $old_license['valid_upto'];
                                if ($previous_validity > $oneYear_validity)
                                    $level_update['valid_upto'] = $previous_validity;
                                else
                                    $level_update['valid_upto'] = $oneYear_validity;
                                $level_update['valid_from'] = date('Y-m-d');
                                $this->TradeApplyLicenceModel->updateLicenseValidity($level_update);

                                // update tbl_apply_license set status=5
                                $this->TradeApplyLicenceModel->approveLicense($licence_id);

                                
                            }

                            # 4 SURRENDER
                            if ($application_type_id == 4) 
                            {
                                $purpose = strtoupper('SURRENDER');
                                $old_license = $this->model_view_trade_licence->getDatabyid(md5($old_license_id));
                                $license_no = $old_license['license_no'];
                                $level_update['valid_from']=$old_license['valid_from'];
                                $level_update['valid_upto'] = $old_license['valid_upto'];
                                $licence_no = $old_license['license_no'];
                                $this->TradeApplyLicenceModel->updateLicenseValidity($data);

                                // update tbl_apply_license set status=5
                                $this->TradeApplyLicenceModel->approveLicense($licence_id);
                                
                            }

                            /***********sms send*********************/
                            $sms = Trade(array('application_no' => $application_no, 'licence_no' => $license_no, 'ulb_name' => session()->get('ulb_dtl')['ulb_name']), 'Application Approved');
                            if ($sms['status'] == true) 
                            {
                                foreach ($owner_for_sms as $val) {
                                    $message = $sms['sms'];
                                    $templateid = $sms['temp_id'];
                                    $sms_data = [
                                        'emp_id' => $login_emp_details_id,
                                        'ref_id' => $licence_id,
                                        'ref_type' => 'tbl_apply_licence',
                                        'mobile_no' => $val['mobile'],
                                        'purpose' => $purpose,
                                        'template_id' => $templateid,
                                        'message' => $message
                                    ];
                                    $sms_id = $this->model_trade_sms_log->insert_sms_log($sms_data);
                                    if ($sms_id) {
                                        $res = send_sms($val['mobile'], $message, $templateid);
                                        if ($res) {
                                            $update = [
                                                'response' => $res['response'],
                                                'smgid' => $res['msg'],
                                            ];
                                            $where = ['id' => $sms_id];
                                            $update = $this->model_trade_sms_log->update_sms_log($where, $update);
                                        }
                                    }
                                }
                            }
                            /***********end sms send*********************/

                            #------------sws push------------------
                            $sws_whare = ['apply_license_id' => $licence_id];
                            $sws = $this->Citizensw_trade_model->getData($sws_whare);
                            // print_var($sws);
                            if ($data['licence']['apply_from'] == 'sws' && !empty($sws)) 
                            {
                                $sw = [];
                                $sw['sw_status'] = 20;
                                $sw['application_statge'] = 5;
                                $where_sw = ['apply_license_id' => $licence_id, 'id' => $sws['id']];
                                $this->Citizensw_trade_model->updateData($sw, $where_sw);

                                $push_sw = array();
                                $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
                                $path = base_url('citizenPaymentReceipt/municipal_licence/' . $ulb_mstr_id . '/' . md5($licence_id));
                                $push_sw['application_stage'] = 20;
                                $push_sw['status'] = 'Application Aprroved';
                                $push_sw['acknowledgment_no'] = $application_no;
                                $push_sw['service_type_id'] = $sws['service_id'];
                                $push_sw['caf_unique_no'] = $sws['caf_no'];
                                $push_sw['department_id'] = $sws['department_id'];
                                $push_sw['Swsregid'] = $sws['cust_id'];
                                $push_sw['payable_amount '] = '';
                                $push_sw['payment_validity'] = '';
                                $push_sw['payment_other_details'] = '';
                                $push_sw['certificate_url'] = $path;
                                $push_sw['approval_date'] = date('Y-m-d H:i:s');
                                $push_sw['expire_date'] =  $level_update['valid_upto'];
                                $push_sw['licence_no'] = $license_no;
                                $push_sw['certificate_no'] = $data['licence']['provisional_license_no'];
                                $push_sw['customer_id'] = $sws['cust_id'];
                                $post_url = getenv('single_indow_push_url');
                                $http = getenv('single_indow_push_http');
                                $resp = httpPostJson($post_url, $push_sw, $http);
                                // print_var($push_sw);
                                // print_var($resp);
                                $respons_data = [];
                                $respons_data['apply_license_id'] = $licence_id;
                                $respons_data['response_msg'] = json_encode([
                                    'url' => $http . '/' . $post_url,
                                    'data' => $push_sw
                                ]);
                                $respons_data['tbl_single_window_id'] = $sws['id'];
                                $respons_data['emp_id'] = $login_emp_details_id;
                                $respons_data['response_status'] = json_encode($resp);
                                $this->Citizensw_trade_model->insertResponse($respons_data);
                            }
                            #-----------------end sws push---------------------
                        }
                    }
                }
                if($this->db->transStatus()===FALSE)
                {
                    $this->db->transRollback();     
                    flashToast('licence', "Somthig Erro On $application_no!!!");              
                }
                else
                {
                    $this->db->transCommit();
                    flashToast('licence', "$application_no Approved Successfully!!!");
                }
                
            }
        }
    }

    public function bulkApproveEO_copy()
    {
        $data = (array)null;

        $data['item'] = $_POST;
        // print_var($data['item']);
        foreach($data['item']['selecteditems'] as $my_level_ids){
            // echo $val." ";
        
            {   
                    $Session = Session();
                    $ulb_mstr = $Session->get("ulb_dtl");
                    $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
                    $emp_mstr = $Session->get("emp_details");
                    $data['ulb_dtl'] = $this->model_ulb_mstr->getulb_list($data['ulb_mstr_id']);

                    $login_emp_details_id = $emp_mstr["id"];
                    $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
                    $data['form'] = $this->model_view_trade_level_pending->tradelevelpendingdetailbyid(md5($my_level_ids));
                    $data['basic_details'] = $this->model_view_trade_licence->getDatabyid(md5($data['form']['apply_licence_id']));
                    $licence = $data['basic_details'];
                    $data['ward']  = $this->model_ward_mstr->getdatabyid($data['form']['ward_mstr_id']);
                    $data['form']['ward_no'] = $data['ward']['ward_no'];
                    $ward_nm = $data['ward']['ward_no'];
                    $ward_id = $data['form']['ward_mstr_id'];
                    $application_type_id = $data['form']['application_type_id'];
                    $licence_id = $data['form']['apply_licence_id'];

                    $data['owner_details'] = $this->model_firm_owner_name->applicantdetails($data['form']['apply_licence_id']);
                    // data for sms
                    $owner_for_sms = $data['owner_details'];
                    $application_no = $data['basic_details']['application_no'];
                    //end sms data
                    $data['holding'] = $this->model_apply_licence->getholding($data['basic_details']['application_no']);
                    $data['dd'] = array();
                 }
                    if ($data['holding']['nature_of_bussiness'])
                        $data['nature_business'] = $this->model_trade_items_mstr->nature_business($data['holding']['nature_of_bussiness']);
                    foreach ($data['nature_business'] as $val) {
                        $data['dd'] = $val;
                    }

                    $data['nature_business']['trade_item'] = is_array($data['dd']) ? implode('<b>,</b><br>', $data['dd']) : $data['dd'];
                    $data['payment_dtls'] = $this->TradeTransactionModel->payment_details($data['basic_details']['id']);
                    $data['payment_dtls'] = array_reverse($data['payment_dtls']);
                    //print_var($data['payment_dtls']);die;
                    //$data['cheque_dtls']=[];
                    if (!empty($data['payment_dtls'])) {
                        //$data['payment_dtls']=$data['payment_dtls'][0];
                        //$data['cheque_dtls'] = $this->TradeChequeDtlModel->cheque_details($data['payment_dtls'][0]['id']);

                    }

                    $apply_licence_id = $data['basic_details']['id'];

                    $data['documents'] = $this->trade_view_application_doc_model->getAllActiveDocuments($apply_licence_id);

                    $data['siteInspectionRemark'] = $this->TradeTaxdarogaVerificationModel->siteInspectionRemarks($apply_licence_id);
                    $data['area_in_sqft'] = $data['siteInspectionRemark']['area_in_sqft'];

                    // //Get All Level Remarks
                    $data['dealingLevel'] = $this->model_trade_level_pending_dtl->getDealingLevelData($apply_licence_id);
                    $data['delingReceiveDate'] = $this->model_trade_level_pending_dtl->getDealingReceiveDate($apply_licence_id);
                    //Tax Daroga
                    $data['taxDarogaLevel'] = $this->model_trade_level_pending_dtl->getTaxDarogaLevelData($apply_licence_id);
                    $data['taxDarogaReceiveDate'] = $this->model_trade_level_pending_dtl->getTaxDarogaReceiveDate($apply_licence_id);
                    //End Tax Daroga
                    //start Section Head
                    $data['sectionHeadLevel'] = $this->model_trade_level_pending_dtl->getSectionHeadLevelData($apply_licence_id);
                    $data['sectionHeadReceiveDate'] = $this->model_trade_level_pending_dtl->getSectionHeadReceiveDate($apply_licence_id);
                    //End Section Head
                    //Start Executive 
                    $data['executiveLevel'] = $this->model_trade_level_pending_dtl->getExecutiveLevelData($apply_licence_id);
                    $data['executiveReceiveDate'] = $this->model_trade_level_pending_dtl->getExecutiveReceiveDate($apply_licence_id);
                    //End Executive

                    //get Trade Item List
                    // $nature_of_business = $this->TradeTradeItemsModel->getTradeItemDetais($apply_licence_id);
                    echo $apply_date = $data['basic_details']['apply_date'] . " ";
                    $old_license_id = $data['basic_details']['update_status'];
                    if ($this->request->getMethod() == 'post') {


                        # Approve Application
                        if (isset($_POST['btn_approved_submit'])) {
                            echo $licence_for_years = $data['holding']["licence_for_years"] . " ";
                            # Making a License No
                            $ulbShortName = strtoupper(substr($data['ulb_dtl']['ulb_name'], 0, 3));
                            $ward_no = $data['ward']['ward_no'];
                            $license_no = $ulbShortName . $ward_no . date("mdY") . $apply_licence_id;
                            $data = [
                                'remarks' => 'license approved by EO',
                                'radio_fapproval' => '',
                                'level_pending_dtl_id' => md5($my_level_ids),
                                'apply_license_id' => $apply_licence_id,
                                'apply_licence_id' => $apply_licence_id,
                                'license_no' => $license_no,
                                'license_date' => date('Y-m-d'),
                                'ward_mstr_id' => $data['form']['ward_mstr_id'],
                                'emp_details_id' => $login_emp_details_id,
                                'created_on' => date('Y-m-d H:i:s'),
                                'forward_date' => date('Y-m-d'),
                                'forward_time' => date('H:i:s'),
                                'sender_user_type_id' => $sender_user_type_id,
                                'receiver_user_type_id' => 20,
                                'level_pending_status' => 5, //Approve
                            ];


                            if ($updateverify = $this->model_trade_level_pending_dtl->updatelevelpendingById($data)) {
                                if ($updatependingstts = $this->model_apply_licence->update_level_pending_status($data)) {
                                    # 1 NEW LICENSE
                                    if ($application_type_id == 1) {
                                        // update new license_no
                                        $this->TradeApplyLicenceModel->updateLicenseNo($data);

                                        // update license validity
                                        $data['valid_upto'] = date("Y-m-d", strtotime("+$licence_for_years years", strtotime($apply_date)));
                                        $this->TradeApplyLicenceModel->updateLicenseValidity($data);

                                        // update tbl_apply_license set status=5
                                        $this->TradeApplyLicenceModel->approveLicense($apply_licence_id);
                                        /**********sms send *************/
                                        $sms = Trade(array('application_no' => $application_no, 'licence_no' => $license_no, 'ulb_name' => session()->get('ulb_dtl')['ulb_name']), 'Application Approved');
                                        if ($sms['status'] == true) {
                                            foreach ($owner_for_sms as $val) {
                                                $message = $sms['sms'];
                                                $templateid = $sms['temp_id'];
                                                $sms_data = [
                                                    'emp_id' => $login_emp_details_id,
                                                    'ref_id' => $apply_licence_id,
                                                    'ref_type' => 'tbl_apply_licence',
                                                    'mobile_no' => $val['mobile'],
                                                    'purpose' => strtoupper('Application Approved'),
                                                    'template_id' => $templateid,
                                                    'message' => $message
                                                ];
                                                $sms_id = $this->model_trade_sms_log->insert_sms_log($sms_data);
                                                if ($sms_id) {
                                                    $res = send_sms($val['mobile'], $message, $templateid);
                                                    if ($res) {
                                                        $update = [
                                                            'response' => $res['response'],
                                                            'smgid' => $res['msg'],
                                                        ];
                                                        $where = ['id' => $sms_id];
                                                        $update = $this->model_trade_sms_log->update_sms_log($where, $update);
                                                    }
                                                }
                                            }
                                        }
                                        $this->db->transRollback();                    
                                        // print_var($sms);die; 
                                        /***********end sms send*********************/
                                        #------------sws push------------------
                                        $sws_whare = ['apply_license_id' => $apply_licence_id];
                                        $sws = $this->Citizensw_trade_model->getData($sws_whare);
                                        //print_var($sws);
                                        //die;
                                        if ($licence['apply_from'] == 'sws' && !empty($sws)) {
                                            $sw = [];
                                            $sw['sw_status'] = 20;
                                            $sw['application_statge'] = 5;
                                            $where_sw = ['apply_license_id' => $apply_licence_id, 'id' => $sws['id']];
                                            $this->Citizensw_trade_model->updateData($sw, $where_sw);

                                            $push_sw = array();
                                            $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
                                            $path = base_url('citizenPaymentReceipt/municipal_licence/' . $ulb_mstr_id . '/' . md5($apply_licence_id));
                                            $push_sw['application_stage'] = 20;
                                            $push_sw['status'] = 'Application Aprroved';
                                            $push_sw['acknowledgment_no'] = $licence['application_no'];
                                            $push_sw['service_type_id'] = $sws['service_id'];
                                            $push_sw['caf_unique_no'] = $sws['caf_no'];
                                            $push_sw['department_id'] = $sws['department_id'];
                                            $push_sw['Swsregid'] = $sws['cust_id'];
                                            $push_sw['payable_amount '] = '';
                                            $push_sw['payment_validity'] = '';
                                            $push_sw['payment_other_details'] = '';
                                            $push_sw['certificate_url'] = $path;
                                            $push_sw['approval_date'] = date('Y-m-d H:i:s');
                                            $push_sw['expire_date'] = $data['valid_upto'];
                                            $push_sw['licence_no'] = $license_no;
                                            $push_sw['certificate_no'] = $licence['provisional_license_no'];
                                            $push_sw['customer_id'] = $sws['cust_id'];
                                            $post_url = getenv('single_indow_push_url');
                                            $http = getenv('single_indow_push_http');
                                            $resp = httpPostJson($post_url, $push_sw, $http);
                                            // print_var($push_sw);
                                            // print_var($resp);die;
                                            $respons_data = [];
                                            $respons_data['apply_license_id'] = $apply_licence_id;
                                            $respons_data['response_msg'] = json_encode([
                                                'url' => $http . '/' . $post_url,
                                                'data' => $push_sw
                                            ]);
                                            $respons_data['tbl_single_window_id'] = $sws['id'];
                                            $respons_data['emp_id'] = $login_emp_details_id;
                                            $respons_data['response_status'] = json_encode($resp);
                                            $this->Citizensw_trade_model->insertResponse($respons_data);
                                        }
                                        #--------------------------------------
                                        flashToast('licence', 'Application Approved Successfully!!!');
                                        // return $this->response->redirect(base_url('Trade_EO/municipal_licence/' . md5($apply_licence_id)));
                                    }

                                    # 2 RENEWAL
                                    if ($application_type_id == 2) {
                                        $sql = " select * from tbl_apply_licence 
                                                        where update_status=$apply_licence_id and status = 1 
                                                        order by id desc limit 1 ";
                                        $prive_licence = $this->TradeApplyLicenceModel->rowQury($sql);
                                        if (!empty($prive_licence)) {
                                            $prive_licence_id = $prive_licence['id'];
                                            $licence_no = $prive_licence['license_no'];
                                            $valid_from = $prive_licence['valid_upto'];
                                            // $test_data = re_day_diff($valid_from,$licence_for_years);
                                            //if($test_data['diff_day']>0)
                                            {
                                                $datef = date('Y-m-d', strtotime($valid_from));
                                                $datefrom = date_create($datef);
                                                $datea = date('Y-m-d', strtotime($apply_date));
                                                $dateapply = date_create($datea);
                                                $year_diff = date_diff($datefrom, $dateapply);
                                                $year_diff =  $year_diff->format('%y') . "";

                                                $priv_m_d = date('m-d', strtotime($valid_from));
                                                $date = date('Y', strtotime($valid_from)) . '-' . $priv_m_d;
                                                $licence_for_years2 = $licence_for_years + $year_diff;
                                                $vali_upto = date('Y-m-d', strtotime($date . "+" . $licence_for_years2 . " years"));

                                                $data['valid_upto'] = $vali_upto;
                                                // print_r($data['valid_upto']);
                                                // die();
                                                // $data['valid_upto']=$test_data['valid_upto'];
                                                // update license validity
                                                $this->TradeApplyLicenceModel->updateLicenseValidity($data);
                                                // update tbl_apply_license set status=5
                                                $this->TradeApplyLicenceModel->approveLicense($apply_licence_id);

                                                //$sql_update_priv_l = "update tbl_apply_licence set status = 0 where id = $prive_licence_id ";
                                                //$this->TradeApplyLicenceModel->rowQury($sql_update_priv_l);
                                                $sql_updae_l_no = "update tbl_apply_licence set license_no = '$licence_no' where id = $apply_licence_id ";
                                                $this->TradeApplyLicenceModel->rowQury($sql_updae_l_no);

                                                /***********sms send*********************/
                                                $sms = Trade(array('application_no' => $application_no, 'licence_no' => $licence_no, 'ulb_name' => session()->get('ulb_dtl')['ulb_name']), 'Application Approved');
                                                if ($sms['status'] == true) {
                                                    foreach ($owner_for_sms as $val) {
                                                        $message = $sms['sms'];
                                                        $templateid = $sms['temp_id'];
                                                        $sms_data = [
                                                            'emp_id' => $login_emp_details_id,
                                                            'ref_id' => $apply_licence_id,
                                                            'ref_type' => 'tbl_apply_licence',
                                                            'mobile_no' => $val['mobile'],
                                                            'purpose' => strtoupper('RENEWAL'),
                                                            'template_id' => $templateid,
                                                            'message' => $message
                                                        ];
                                                        $sms_id = $this->model_trade_sms_log->insert_sms_log($sms_data);
                                                        if ($sms_id) {
                                                            $res = send_sms($val['mobile'], $message, $templateid);
                                                            if ($res) {
                                                                $update = [
                                                                    'response' => $res['response'],
                                                                    'smgid' => $res['msg'],
                                                                ];
                                                                $where = ['id' => $sms_id];
                                                                $update = $this->model_trade_sms_log->update_sms_log($where, $update);
                                                            }
                                                        }
                                                    }
                                                }
                                                // $this->db->transRollback();                    
                                                // print_var($sms);die; 
                                                /***********end sms send*********************/
                                                #------------sws push------------------
                                                $sws_whare = ['apply_license_id' => $apply_licence_id];
                                                $sws = $this->Citizensw_trade_model->getData($sws_whare);
                                                //print_var($sws);
                                                //die;
                                                if ($licence['apply_from'] == 'sws' && !empty($sws)) {
                                                    $sw = [];
                                                    $sw['sw_status'] = 20;
                                                    $sw['application_statge'] = 5;
                                                    $where_sw = ['apply_license_id' => $apply_licence_id, 'id' => $sws['id']];
                                                    $this->Citizensw_trade_model->updateData($sw, $where_sw);

                                                    $push_sw = array();
                                                    $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
                                                    $path = base_url('citizenPaymentReceipt/municipal_licence/' . $ulb_mstr_id . '/' . md5($apply_licence_id));
                                                    $push_sw['application_stage'] = 20;
                                                    $push_sw['status'] = 'Application Aprroved';
                                                    $push_sw['acknowledgment_no'] = $licence['application_no'];
                                                    $push_sw['service_type_id'] = $sws['service_id'];
                                                    $push_sw['caf_unique_no'] = $sws['caf_no'];
                                                    $push_sw['department_id'] = $sws['department_id'];
                                                    $push_sw['Swsregid'] = $sws['cust_id'];
                                                    $push_sw['payable_amount '] = '';
                                                    $push_sw['payment_validity'] = '';
                                                    $push_sw['payment_other_details'] = '';
                                                    $push_sw['certificate_url'] = $path;
                                                    $push_sw['approval_date'] = date('Y-m-d H:i:s');
                                                    $push_sw['expire_date'] = $data['valid_upto'];
                                                    $push_sw['licence_no'] = $licence_no;
                                                    $push_sw['certificate_no'] = $licence['provisional_license_no'];
                                                    $push_sw['customer_id'] = $sws['cust_id'];
                                                    $post_url = getenv('single_indow_push_url');
                                                    $http = getenv('single_indow_push_http');
                                                    $resp = httpPostJson($post_url, $push_sw, $http);
                                                    // print_var($push_sw);
                                                    // print_var($resp);die;
                                                    $respons_data = [];
                                                    $respons_data['apply_license_id'] = $apply_licence_id;
                                                    $respons_data['response_msg'] = json_encode([
                                                        'url' => $http . '/' . $post_url,
                                                        'data' => $push_sw
                                                    ]);
                                                    $respons_data['tbl_single_window_id'] = $sws['id'];
                                                    $respons_data['emp_id'] = $login_emp_details_id;
                                                    $respons_data['response_status'] = json_encode($resp);
                                                    $this->Citizensw_trade_model->insertResponse($respons_data);
                                                }
                                                #--------------------------------------

                                                flashToast('licence', 'Application Approved Successfully!!!');
                                            }
                                            // else
                                            // {
                                            //     flashToast('licence','Some Error Occurred On Licence Validity Please Contact to Admin!!!');
                                            // }

                                        } else {
                                            $this->db->transRollback();
                                            flashToast('licence', 'Some Error Occurred Please Contact to Admin!!!');
                                            // return $this->response->redirect(base_url('Trade_EO/view/' . md5($my_level_ids)));
                                        }

                                        // return $this->response->redirect(base_url('Trade_EO/municipal_licence/' . md5($apply_licence_id)));
                                    }

                                    # 3 AMENDMENT
                                    if ($application_type_id == 3) {
                                        // update license validity
                                        # Previous validity or one year, whichever geater
                                        $old_license = $this->model_view_trade_licence->getDatabyid(md5($old_license_id));
                                        $oneYear_validity = date("Y-m-d", strtotime("+1 years", strtotime('now')));
                                        $previous_validity = $old_license['valid_upto'];
                                        if ($previous_validity > $oneYear_validity)
                                            $data['valid_upto'] = $previous_validity;
                                        else
                                            $data['valid_upto'] = $oneYear_validity;
                                        $data['valid_from'] = date('Y-m-d');
                                        $this->TradeApplyLicenceModel->updateLicenseValidity($data);

                                        // update tbl_apply_license set status=5
                                        $this->TradeApplyLicenceModel->approveLicense($apply_licence_id);

                                        /***********sms send*********************/
                                        $licence_no = $old_license['license_no'];
                                        $sms = Trade(array('application_no' => $application_no, 'licence_no' => $licence_no, 'ulb_name' => session()->get('ulb_dtl')['ulb_name']), 'Application Approved');
                                        if ($sms['status'] == true) {
                                            foreach ($owner_for_sms as $val) {
                                                $message = $sms['sms'];
                                                $templateid = $sms['temp_id'];
                                                $sms_data = [
                                                    'emp_id' => $login_emp_details_id,
                                                    'ref_id' => $apply_licence_id,
                                                    'ref_type' => 'tbl_apply_licence',
                                                    'mobile_no' => $val['mobile'],
                                                    'purpose' => strtoupper('AMENDMENT'),
                                                    'template_id' => $templateid,
                                                    'message' => $message
                                                ];
                                                $sms_id = $this->model_trade_sms_log->insert_sms_log($sms_data);
                                                if ($sms_id) {
                                                    $res = send_sms($val['mobile'], $message, $templateid);
                                                    if ($res) {
                                                        $update = [
                                                            'response' => $res['response'],
                                                            'smgid' => $res['msg'],
                                                        ];
                                                        $where = ['id' => $sms_id];
                                                        $update = $this->model_trade_sms_log->update_sms_log($where, $update);
                                                    }
                                                }
                                            }
                                        }
                                        // $this->db->transRollback();                    
                                        // print_var($sms);die; 
                                        /***********end sms send*********************/
                                        #------------sws push------------------
                                        $sws_whare = ['apply_license_id' => $apply_licence_id];
                                        $sws = $this->Citizensw_trade_model->getData($sws_whare);
                                        //print_var($sws);
                                        //die;
                                        if ($licence['apply_from'] == 'sws' && !empty($sws)) {
                                            $sw = [];
                                            $sw['sw_status'] = 20;
                                            $sw['application_statge'] = 5;
                                            $where_sw = ['apply_license_id' => $apply_licence_id, 'id' => $sws['id']];
                                            $this->Citizensw_trade_model->updateData($sw, $where_sw);

                                            $push_sw = array();
                                            $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
                                            $path = base_url('citizenPaymentReceipt/municipal_licence/' . $ulb_mstr_id . '/' . md5($apply_licence_id));
                                            $push_sw['application_stage'] = 20;
                                            $push_sw['status'] = 'Application Aprroved';
                                            $push_sw['acknowledgment_no'] = $licence['application_no'];
                                            $push_sw['service_type_id'] = $sws['service_id'];
                                            $push_sw['caf_unique_no'] = $sws['caf_no'];
                                            $push_sw['department_id'] = $sws['department_id'];
                                            $push_sw['Swsregid'] = $sws['cust_id'];
                                            $push_sw['payable_amount '] = '';
                                            $push_sw['payment_validity'] = '';
                                            $push_sw['payment_other_details'] = '';
                                            $push_sw['certificate_url'] = $path;
                                            $push_sw['approval_date'] = date('Y-m-d H:i:s');
                                            $push_sw['expire_date'] = $data['valid_upto'];
                                            $push_sw['licence_no'] = $licence_no;
                                            $push_sw['certificate_no'] = $licence['provisional_license_no'];
                                            $push_sw['customer_id'] = $sws['cust_id'];
                                            $post_url = getenv('single_indow_push_url');
                                            $http = getenv('single_indow_push_http');
                                            $resp = httpPostJson($post_url, $push_sw, $http);
                                            // print_var($push_sw);
                                            // print_var($resp);die;
                                            $respons_data = [];
                                            $respons_data['apply_license_id'] = $apply_licence_id;
                                            $respons_data['response_msg'] = json_encode([
                                                'url' => $http . '/' . $post_url,
                                                'data' => $push_sw
                                            ]);
                                            $respons_data['tbl_single_window_id'] = $sws['id'];
                                            $respons_data['emp_id'] = $login_emp_details_id;
                                            $respons_data['response_status'] = json_encode($resp);
                                            $this->Citizensw_trade_model->insertResponse($respons_data);
                                        }
                                        #--------------------------------------

                                        flashToast('licence', 'Application Approved Successfully!!!');
                                        // return $this->response->redirect(base_url('Trade_EO/municipal_licence/' . md5($apply_licence_id)));
                                    }

                                    # 4 SURRENDER
                                    if ($application_type_id == 4) {
                                        // Incase of surrender valid upto is previous license validity
                                        $old_license = $this->model_view_trade_licence->getDatabyid(md5($old_license_id));
                                        $data['valid_upto'] = $old_license['valid_upto'];

                                        // update license validity
                                        $this->TradeApplyLicenceModel->updateLicenseValidity($data);

                                        // update tbl_apply_license set status=5
                                        $this->TradeApplyLicenceModel->approveLicense($apply_licence_id);

                                        /***********sms send*********************/
                                        $licence_no = $old_license['license_no'];
                                        $sms = Trade(array('application_no' => $application_no, 'licence_no' => $licence_no, 'ulb_name' => session()->get('ulb_dtl')['ulb_name']), 'Application Approved');
                                        if ($sms['status'] == true) {
                                            foreach ($owner_for_sms as $val) {
                                                $message = $sms['sms'];
                                                $templateid = $sms['temp_id'];
                                                $sms_data = [
                                                    'emp_id' => $login_emp_details_id,
                                                    'ref_id' => $apply_licence_id,
                                                    'ref_type' => 'tbl_apply_licence',
                                                    'mobile_no' => $val['mobile'],
                                                    'purpose' => strtoupper('SURRENDER'),
                                                    'template_id' => $templateid,
                                                    'message' => $message
                                                ];
                                                $sms_id = $this->model_trade_sms_log->insert_sms_log($sms_data);
                                                if ($sms_id) {
                                                    $res = send_sms($val['mobile'], $message, $templateid);
                                                    if ($res) {
                                                        $update = [
                                                            'response' => $res['response'],
                                                            'smgid' => $res['msg'],
                                                        ];
                                                        $where = ['id' => $sms_id];
                                                        $update = $this->model_trade_sms_log->update_sms_log($where, $update);
                                                    }
                                                }
                                            }
                                        }
                                        // $this->db->transRollback();                    
                                        // print_var($sms);die; 
                                        /***********end sms send*********************/
                                        #------------sws push------------------
                                        $sws_whare = ['apply_license_id' => $apply_licence_id];
                                        $sws = $this->Citizensw_trade_model->getData($sws_whare);
                                        //print_var($sws);
                                        //die;
                                        if ($licence['apply_from'] == 'sws' && !empty($sws)) {
                                            $sw = [];
                                            $sw['sw_status'] = 20;
                                            $sw['application_statge'] = 5;
                                            $where_sw = ['apply_license_id' => $apply_licence_id, 'id' => $sws['id']];
                                            $this->Citizensw_trade_model->updateData($sw, $where_sw);

                                            $push_sw = array();
                                            $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
                                            $path = base_url('TradeCitizen/trade_licence_view/' . md5($apply_licence_id));
                                            $push_sw['application_stage'] = 20;
                                            $push_sw['status'] = 'Application Aprroved';
                                            $push_sw['acknowledgment_no'] = $licence['application_no'];
                                            $push_sw['service_type_id'] = $sws['service_id'];
                                            $push_sw['caf_unique_no'] = $sws['caf_no'];
                                            $push_sw['department_id'] = $sws['department_id'];
                                            $push_sw['Swsregid'] = $sws['cust_id'];
                                            $push_sw['payable_amount '] = '';
                                            $push_sw['payment_validity'] = '';
                                            $push_sw['payment_other_details'] = '';
                                            $push_sw['certificate_url'] = $path;
                                            $push_sw['approval_date'] = date('Y-m-d H:i:s');
                                            $push_sw['expire_date'] = $data['valid_upto'];
                                            $push_sw['licence_no'] = $licence_no;
                                            $push_sw['certificate_no'] = $licence['provisional_license_no'];
                                            $push_sw['customer_id'] = $sws['cust_id'];
                                            $post_url = getenv('single_indow_push_url');
                                            $http = getenv('single_indow_push_http');
                                            $resp = httpPostJson($post_url, $push_sw, $http);
                                            // print_var($push_sw);
                                            // print_var($resp);die;
                                            $respons_data = [];
                                            $respons_data['apply_license_id'] = $apply_licence_id;
                                            $respons_data['response_msg'] = json_encode([
                                                'url' => $http . '/' . $post_url,
                                                'data' => $push_sw
                                            ]);
                                            $respons_data['tbl_single_window_id'] = $sws['id'];
                                            $respons_data['emp_id'] = $login_emp_details_id;
                                            $respons_data['response_status'] = json_encode($resp);
                                            $this->Citizensw_trade_model->insertResponse($respons_data);
                                        }
                                        #--------------------------------------

                                        flashToast('licence', 'License Surrendered Successfully!!!');
                                        // return $this->response->redirect(base_url('Trade_EO/index/'));
                                    }
                                }
                            }
                        }
            }
        }
    }

    public function eo_approved_view($id)
	{
        $data =(array)null;
        $Session = Session();
        
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
    public function municipal_licence($id)
	{
        $data =(array)null;
        $Session = Session();
        
        $ulb_mstr = $Session->get("ulb_dtl");
        
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
		$ulb_mstr_id = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $data['emp_mstr_type_id']=$emp_mstr['user_type_mstr_id'];
		$path=base_url('citizenPaymentReceipt/municipal_licence/'.$ulb_mstr_id.'/'.$id);
        $data['ss']=qrCodeGeneratorFun($path);
		
        
        $login_emp_details_id = $emp_mstr["id"];
        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        $data['ulb'] = $this->model_ulb_mstr->getulb_list($data['ulb_mstr_id']);
        $data['basic_details'] = $this->model_view_trade_licence->getDatabyid($id);
        $apply_licence_id=$data['basic_details']['id'];
        $data['ward']  = $this->model_ward_mstr->getdatabyid($data['basic_details']['ward_mstr_id']);
        $data['counter']='yeas';
        
        $sign = "dmcsign.png"; 
        if($basic_details['license_date']>='2025-01-16'){
            $sign = "gautam.png"; 
        }
        $data["signature_path"]=base_url("/writable/eo_sign/$sign");
        if($data["basic_details"]["approved_by"]){
            $empDtl = $this->dbSystem->query("select * from tbl_emp_details where id=".$data["basic_details"]["approved_by"])->getFirstRow("array");
            $data["signature_path"]= $empDtl && $empDtl["signature_path"]? base_url("/getImageLink.php?path=/emp_signature/".$empDtl["signature_path"]):$data["signature_path"] ;
        }
        
        return view('trade/Connection/municipal_licence', $data);

    }

    public function trade_licence_list()
	{
        $data =(array)null;
        $Session = Session();
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
        
        //Transaction Mode List
        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['wardList'] = $wardList;

        $receiver_user_type_id = $emp_mstr["user_type_mstr_id"];
        $ward= [];

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
                $data['posts'] = $this->ModelTradeLicense->get_wardwiselicence_list($data['from_date'],$data['to_date'],$data['ward_mstr_id']);
            }
            else
            {
                $data['posts'] = $this->ModelTradeLicense->get_licence_list($data['from_date'],$data['to_date'],$ward);
            }

            $j=0;
            foreach($data['posts'] as $key => $value)
            {
                $wardd = $this->model_ward_mstr->getdatabyid($value['ward_mstr_id']);
                $owner = $this->model_firm_owner_name->applicantdetails($value['id']);
                $j=0;
                foreach($owner as $keyy => $val)
                {
                    if($j==0)
                    {
                        $data['posts'][$key]['owner_name']=array($val["owner_name"]);
                        $data['posts'][$key]['mobile_no']=array($val["mobile"]);
                    }
                    else
                    {
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
            $data['posts'] = $this->ModelTradeLicense->get_licence_list($data['from_date'],$data['to_date'], $ward);

            $j=0;
            
            foreach($data['posts'] as $key => $value)
            {
                $wardd = $this->model_ward_mstr->getdatabyid($value['ward_mstr_id']);
                $owner = $this->model_firm_owner_name->applicantdetails($value['id']);
                $j=0;
                foreach($owner as $keyy => $val)
                {
                    if($j==0)
                    {
                        $data['posts'][$key]['owner_name']=array($val["owner_name"]);
                        $data['posts'][$key]['mobile_no']=array($val["mobile"]);
                    }
                    else
                    {
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
    public function debarredTradeLicence($id){
        $Session = Session();
        $data=(array)null;
        $input=(array)null;
        $emp_details = $Session->get("emp_details");
        $emp_details_id = $emp_details['id'];
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $data['ulb_dtl'] = $this->model_ulb_mstr->getulb_list($data['ulb_mstr_id']);
        $ulb_short_nm=$data['ulb_dtl']['short_ulb_name'];
        $ulb_nm = substr($ulb_short_nm, 0, 3);
        //get Firm Owner Details
        $firmOwnerDetails = $this->model_firm_owner_name->getFirmOwnerDetailsByApplyId($id);
        $applyLicenceData = $this->model_apply_licence->getData($id);
        //get ward Details
        $data['ward']  = $this->model_ward_mstr->getdatabyid($applyLicenceData['ward_mstr_id']);
        $ward_nm=$data['ward']['ward_no'];
        //data preparation for tbl_licence
        $data=[
                'apply_licence_id' => $applyLicenceData['id'],
                'ward_mstr_id' => $applyLicenceData['ward_mstr_id'],
                'application_no' => $applyLicenceData['application_no'],
                'firm_type_id' => $applyLicenceData['firm_type_id'],
                'application_type_id' => $applyLicenceData['application_type_id'],
                'ownership_type_id' => $applyLicenceData['ownership_type_id'],
                'prop_dtl_id' => $applyLicenceData['prop_dtl_id'],
                'firm_name' => $applyLicenceData['firm_name'],
                'firm_address' => $applyLicenceData['address'],
                'landmark' => $applyLicenceData['landmark'],
                'pin_code' => $applyLicenceData['pin_code'],
                'property_type' => $applyLicenceData['property_type'],
                'k_no' => $applyLicenceData['k_no'],
                'bind_book_no' => $applyLicenceData['bind_book_no'],
                'account_no' => $applyLicenceData['account_no'],
                'holding_no' => $applyLicenceData['holding_no'],
                'establishment_date' => $applyLicenceData['establishment_date'],
                'emp_details_id' => $emp_details_id,
                'created_on'=>date('Y-m-d H:i:s'),
                'debarred_date'=>date('Y-m-d'),
                'level_pending_status'=>1,
                'verification_status'=>1
            ];
            $lic_for_year = $this->model_apply_licence->getDatabyid($data['apply_licence_id']);
            //get Site Inspection Details
        $data['siteInspectionData'] = $this->TradeTaxdarogaVerificationModel->siteInspectionRemarks($data['apply_licence_id']);
        $data['area_in_sqft']=$data['siteInspectionData']['area_in_sqft'];
        //get Level Pending Details Id
        $level_pending_dtl_id = $this->model_trade_level_pending_dtl->getLevelDendingDetailsIdForEo($id);
        $data['level_pending_dtl_id'] = md5($level_pending_dtl_id);
        //Licence Number Generation
        $data['ward_count']=$this->model_trade_licence->count_ward_by_wardid($data['ward_mstr_id']);
        $sl_no = $data['ward_count']['ward_cnt'];
        $sl_noo = $sl_no+1;
        $serial_no = str_pad($sl_noo, 4, "0", STR_PAD_LEFT);
        $ward_nmm = str_pad($ward_nm, 2, "0", STR_PAD_LEFT);
        $licence_no = $ulb_nm.$ward_nmm.$serial_no;
        $data['licence_no'] = $licence_no;
        //caculat licence validity
        $lyear = 365 * $lic_for_year["licence_for_years"];
        $valid_upto = date('Y-m-d',strtotime(date("Y-m-d", mktime()) . " + $lyear day"));
        if($Update_level_pending_status = $this->model_trade_level_pending_dtl->updatelevelpendingById($data)){
            if($update_verification_status = $this->model_apply_licence->update_level_pending_status($data)){
                if($licence_id = $this->model_trade_licence->insertLicenceData($data)){
                    //insert data into tbl_debarred_dtl
                    if($inserted_id = $this->TradeDebarredDtlModel->insertData($data)){
                        foreach ($firmOwnerDetails as $value) {
                            $input=[
                                    'licence_id' => $licence_id,
                                    'owner_name' => $value['owner_name'],
                                    'address' => $value['address'],
                                    'mobile' => $value['mobile'],
                                    'city' => $value['city'],
                                    'district' => $value['district'],
                                    'emp_details_id' => $emp_details_id,
                                    'guardian_name' => $value['guardian_name'],
                                    'state' => $value['state'],
                                    'created_on'=>date('Y-m-d H:i:s')
                                ];
                            $this->model_trade_licence_owner_name->insertLicenceOwnerData($input);
                        }
                        $data['licence_id'] = $licence_id;
                        $data['generated_date'] = date('Y-m-d');
                        $data['validity']=$valid_upto;
                        $data['from_date']=date('Y-m-d');
                        $data['to_date']=$valid_upto;
                        $this->model_trade_licence_validity->insertdata($data);
                        $this->model_trade_licence->debarredLicence(md5($licence_id));
                        flashToast('licence','Trade Licence Debarred Successfully!!!');
                        return $this->response->redirect(base_url('Trade_EO/index'));
                    }else{
                        flashToast('licence','Fail To Debarred Trade Licence!!');
                        return $this->response->redirect(base_url('Trade_EO/index'));
                    }
                }else{
                    flashToast('licence','Fail To Debarred Trade Licence!!');
                    return $this->response->redirect(base_url('Trade_EO/index'));
                }
                
            }
        }
    }
    public function approveTradeLicence($id=null){
        $Session = Session();
        $data=(array)null;
        $input=(array)null;
        $emp_details = $Session->get("emp_details");
        $emp_details_id = $emp_details['id'];
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $data['ulb_dtl'] = $this->model_ulb_mstr->getulb_list($data['ulb_mstr_id']);
        $ulb_short_nm=$data['ulb_dtl']['short_ulb_name'];
        $ulb_nm = substr($ulb_short_nm, 0, 3);
        //get Firm Owner Details
        $firmOwnerDetails = $this->model_firm_owner_name->getFirmOwnerDetailsByApplyId($id);
        $applyLicenceData = $this->model_apply_licence->getData($id);
        //get ward Details
        $data['ward']  = $this->model_ward_mstr->getdatabyid($applyLicenceData['ward_mstr_id']);
        $ward_nm=$data['ward']['ward_no'];
        //data preparation for tbl_licence
        $data=[
                'apply_licence_id' => $applyLicenceData['id'],
                'ward_mstr_id' => $applyLicenceData['ward_mstr_id'],
                'application_no' => $applyLicenceData['application_no'],
                'firm_type_id' => $applyLicenceData['firm_type_id'],
                'application_type_id' => $applyLicenceData['application_type_id'],
                'ownership_type_id' => $applyLicenceData['ownership_type_id'],
                'prop_dtl_id' => $applyLicenceData['prop_dtl_id'],
                'firm_name' => $applyLicenceData['firm_name'],
                'firm_address' => $applyLicenceData['address'],
                'landmark' => $applyLicenceData['landmark'],
                'pin_code' => $applyLicenceData['pin_code'],
                'property_type' => $applyLicenceData['property_type'],
                'k_no' => $applyLicenceData['k_no'],
                'bind_book_no' => $applyLicenceData['bind_book_no'],
                'account_no' => $applyLicenceData['account_no'],
                'holding_no' => $applyLicenceData['holding_no'],
                'establishment_date' => $applyLicenceData['establishment_date'],
                'emp_details_id' => $emp_details_id,
                'created_on'=>date('Y-m-d H:i:s'),
                'level_pending_status'=>1,
                'verification_status'=>1
            ];
            $lic_for_year = $this->model_apply_licence->getDatabyid($data['apply_licence_id']);
            //get Site Inspection Details
        $data['siteInspectionData'] = $this->TradeTaxdarogaVerificationModel->siteInspectionRemarks($data['apply_licence_id']);
        $data['area_in_sqft']=$data['siteInspectionData']['area_in_sqft'];
            //get Level Pending Details Id
            $level_pending_dtl_id = $this->model_trade_level_pending_dtl->getLevelDendingDetailsIdForEo($id);
            $data['level_pending_dtl_id'] = md5($level_pending_dtl_id);
        //Licence Number Generation
        $data['ward_count']=$this->model_trade_licence->count_ward_by_wardid($data['ward_mstr_id']);
        $sl_no = $data['ward_count']['ward_cnt'];
        $sl_noo = $sl_no+1;
        $serial_no = str_pad($sl_noo, 4, "0", STR_PAD_LEFT);
        $ward_nmm = str_pad($ward_nm, 2, "0", STR_PAD_LEFT);
        $licence_no = $ulb_nm.$ward_nmm.$serial_no;
        $data['licence_no'] = $licence_no;
        //caculat licence validity
        $lyear = 365 * $lic_for_year["licence_for_years"];
        $valid_upto = date('Y-m-d',strtotime(date("Y-m-d", mktime()) . " + $lyear day"));
        //insert Data into tbl_licence
        if($Update_level_pending_status = $this->model_trade_level_pending_dtl->updatelevelpendingById($data)){
            if($update_verification_status = $this->model_apply_licence->update_level_pending_status($data)){
                
                $inserted_id = $this->model_trade_licence->insertLicenceData($data);
                if($inserted_id){
                    foreach ($firmOwnerDetails as $value) {
                        $input=[
                                'licence_id' => $inserted_id,
                                'owner_name' => $value['owner_name'],
                                'address' => $value['address'],
                                'mobile' => $value['mobile'],
                                'city' => $value['city'],
                                'district' => $value['district'],
                                'emp_details_id' => $emp_details_id,
                                'guardian_name' => $value['guardian_name'],
                                'state' => $value['state'],
                                'created_on'=>date('Y-m-d H:i:s')
                            ];
                        $this->model_trade_licence_owner_name->insertLicenceOwnerData($input);
                    }
                    $data['licence_id'] = $inserted_id;
                    $data['generated_date'] = date('Y-m-d');
                    $data['validity']=$valid_upto;
                    $data['from_date']=date('Y-m-d');
                    $data['to_date']=$valid_upto;
                    $this->model_trade_licence_validity->insertdata($data);
                    flashToast('licence','Licence Generated Successfully!!!');
                }else{
                    flashToast('licence','Fail To Approve Licence!!!');
                }
                return $this->response->redirect(base_url('Trade_EO/index'));
            }
        }
    }

    public function denialInbox()
	{
        $data =(array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];

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
        
         $mailStatus = 1; //status
         $data['listName'] = "";
        $data['mailStatus']=$mailStatus ;
        if($this->request->getMethod()=='post')
        { 
            $data['ward_mstr_id'] = $this->request->getVar('ward_mstr_id');
             $data['from_date'] = $this->request->getVar('from_date');
             $data['to_date'] = $this->request->getVar('to_date');
            if($data['ward_mstr_id']!="")
            {
                $data['posts'] = $this->model_view_trade_level_pending->getDenialListByWard($receiver_user_type_id,$data['from_date'],$data['to_date'],$data['ward_mstr_id'],$mailStatus);
            }
            else{
                $data['posts'] = $this->model_view_trade_level_pending->getDenialList($receiver_user_type_id,$data['from_date'],$data['to_date'],$ward,$mailStatus);
            }

            $j=0;
            foreach($data['posts'] as $key => $value){
                $wardd = $this->model_ward_mstr->getdatabyid($value['ward_id']);
 				
                $data['posts'][$key]['ward_no'] = $wardd['ward_no'];
             }
            return view('trade/Connection/trade_denial_eo_list', $data);
        }
        else
        {
            $data['from_date'] = date('Y-m-d');
            $data['to_date'] = date('Y-m-d');
            $data['posts'] = $this->model_view_trade_level_pending->getDenialList($receiver_user_type_id,$data['from_date'],$data['to_date'],$ward,$mailStatus);
            $j=0;
            foreach($data['posts'] as $key => $value){
                $wardd = $this->model_ward_mstr->getdatabyid($value['ward_id']);
 				
                $data['posts'][$key]['ward_no'] = $wardd['ward_no'];
             }
            return view('trade/Connection/trade_denial_eo_list', $data);
        }
	}

    public function denialview($id,$mailID)
	{
        $data =(array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $data['ulb_dtl'] = $this->model_ulb_mstr->getulb_list($data['ulb_mstr_id']);
        $login_emp_details_id = $emp_mstr["id"];
        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        $data['denial_details']  = $this->TradeApplyDenialModel->getDenialDetailsByID($id);
        $data['ward']  = $this->model_ward_mstr->getdatabyid($data['denial_details']['ward_id']);
        $data['form']['ward_no']=$data['ward']['ward_no'];
        $denialID =  $data['denial_details']['id'];
        $data['noticeDetails']  = $this->TradeApplyDenialModel->getNoticeDetails($denialID);
        $data['approvedDocDetails']  = $this->TradeApplyDenialModel->getapprovedDocDetails($denialID);
        if($this->request->getMethod()=='post')
        {
            # Approve Application
            if(isset($_POST['btn_approved_submit']))
            {  

                $this->db->transBegin();   
                          
                $data = [
                        'remarks' => $this->request->getVar('remarks'),
                         'mail_id' => $mailID,
                         'denial_id' => $id,
                         'forward_date' => date('Y-m-d'),
                         'forward_time' => date('H:i:s'),
                         'emp_details_id' => $login_emp_details_id,
                         'created_on' => date('Y-m-d H:i:s'),
                         'denial_ID'  => $data['denial_details']['id'],
                         'status' => 5
                     ];
                
                    if($updateMail = $this->model_trade_level_pending_dtl->updateMail($data))  //  update  mail table
                    {
                       if($updateConsumer = $this->TradeApplyDenialModel->updateStatus($data)) // update status of consumer table
                       {   
                          $insertID =  $this->TradeApplyDenialModel->insertNoticeData($data);   //insert data into notice table  
                          $noticeNO = "NOT/".date('dmy').$denialID.$insertID ;
                          $this->TradeApplyDenialModel->updateNoticeNo($insertID,$noticeNO); 
                       }                            

                    }

                    if($this->db->transStatus() === FALSE){
                        $this->db->transRollback();
                        flashToast("denialForm", "Something Went Wrong. Please Try Again!");
                        return $this->response->redirect(base_url('Trade_EO/denialview/'.$id.'/'.$mailID));
                    }else{
                        $this->db->transCommit();
                        flashToast("denialForm", "Approved   Succesfully!");
                        return $this->response->redirect(base_url('Trade_EO/denialview/'.$id.'/'.$mailID));
                    }
            }
 

            if(isset($_POST['btn_upload'])) 
            {
                $this->db->transBegin();   
                          
                $data = [
                         'notice_id' => $this->request->getVar('notice_id'),
                         'remarks' => $this->request->getVar('remarks'),
                         'emp_details_id' => $login_emp_details_id,
                         'created_on' => date('Y-m-d H:i:s'),
                         'denial_ID'  => $data['denial_details']['id'],
                      ];
                
           
                    if($insertId = $this->TradeApplyDenialModel->insertDocDetails($data))   
                    {
                        $doc_path_image = $this->request->getFile('approvedoc');
                        try {
                           
                            $newFileName = md5($insertId);
                            $file_ext = $doc_path_image->getExtension();
                            $path_images = 'RANCHI'."/"."denialApprovedImage";
                            $doc_path_image->move(WRITEPATH.'uploads/'.$path_images.'/',$newFileName.'.'.$file_ext);
                            $doc_path_save = $path_images."/".$newFileName.'.'.$file_ext;
                            $this->TradeApplyDenialModel->updatedocpathByIdapprove($insertId, $doc_path_save);
                            
                        } catch (Exception $e) { }  
                       
                    }

                    if($this->db->transStatus() === FALSE){
                        $this->db->transRollback();
                        flashToast("denialForm", "Something Went Wrong. Please Try Again!");
                        return $this->response->redirect(base_url('Trade_EO/denialview/'.$id.'/'.$mailID));
                    }else{
                        $this->db->transCommit();
                        flashToast("denialForm", "Rejected   Succesfully!");
                        return $this->response->redirect(base_url('Trade_EO/denial/verifiedMail/'));
                    }
            }

            if(isset($_POST['btn_reject']))
            {
                $this->db->transBegin();   
                          
                $data = [
                        'remarks' => $this->request->getVar('remarks'),
                         'mail_id' => $mailID,
                         'denial_id' => $id,
                         'forward_date' => date('Y-m-d'),
                         'forward_time' => date('H:i:s'),
                         'emp_details_id' => $login_emp_details_id,
                         'created_on' => date('Y-m-d H:i:s'),
                         'denial_ID'  => $data['denial_details']['id'],
                         'status' => 4
                     ];
                
           
                    if($updateMail = $this->model_trade_level_pending_dtl->updateMail($data))  //  update  mail table
                    {
                       $this->TradeApplyDenialModel->updateStatus($data); // update status of consumer table
                       
                    }

                    if($this->db->transStatus() === FALSE){
                        $this->db->transRollback();
                        flashToast("denialForm", "Something Went Wrong. Please Try Again!");
                        return $this->response->redirect(base_url('Trade_EO/denialview/'.$id.'/'.$mailID));
                    }else{
                        $this->db->transCommit();
                        flashToast("denialForm", "Rejected   Succesfully!");
                        return $this->response->redirect(base_url('Trade_EO/denial/rejectedMail/'));
                    }
            }
        }
        else
        {
            // print_var($data);return;
            return view('trade/Connection/trade_eo_denial_view', $data);
        }
    }

    public function denial($mailStatus)
	{ 
        $data =(array)null;
        $data['mailStatus']=$mailStatus;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];

        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['wardList'] = $wardList;

        $receiver_user_type_id = $emp_mstr["user_type_mstr_id"];
        $ward="";

        $i=0;
        foreach($wardList as $key => $value)
        {
            if($i==0){
                $ward=array($value['ward_mstr_id']);
            }else{
                array_push($ward, $value['ward_mstr_id']);
            }
            $i++;
         }
        
        if($mailStatus =="rejectedMail")
        {
            $mailStatus = 4;
            $data['listName'] = "Rejected";
        }
        else
        {
            $mailStatus = 5; 
            $data['listName']  = "Approved";

        }
        if($this->request->getMethod()=='post')
        { 
            $data['ward_mstr_id'] = $this->request->getVar('ward_mstr_id');
             $data['from_date'] = $this->request->getVar('from_date');
             $data['to_date'] = $this->request->getVar('to_date');
            if($data['ward_mstr_id']!="")
            {
                $data['posts'] = $this->model_view_trade_level_pending->getDenialListByWard($receiver_user_type_id,$data['from_date'],$data['to_date'],$data['ward_mstr_id'],$mailStatus);
            }
            else
            {
                $data['posts'] = $this->model_view_trade_level_pending->getDenialList($receiver_user_type_id,$data['from_date'],$data['to_date'],$ward,$mailStatus);
            }

            $j=0;
            foreach($data['posts'] as $key => $value){
                $wardd = $this->model_ward_mstr->getdatabyid($value['ward_id']);
 				
                $data['posts'][$key]['ward_no'] = $wardd['ward_no'];
             }
            return view('trade/Connection/trade_denial_eo_list', $data);
        }
        else
        {  
            $data['from_date'] = date('Y-m-d');
            $data['to_date'] = date('Y-m-d');
            $data['posts'] = $this->model_view_trade_level_pending->getDenialList($receiver_user_type_id,$data['from_date'],$data['to_date'],$ward,$mailStatus);
            $j=0;
            foreach($data['posts'] as $key => $value){
                $wardd = $this->model_ward_mstr->getdatabyid($value['ward_id']);
                $data['posts'][$key]['ward_no'] = $wardd['ward_no'];
             }
            return view('trade/Connection/trade_denial_eo_list', $data);
        }
	}

    public function uploadSign()
    {
        if($this->request->getMethod()=='post'){ 
            if(isset($_POST['uploadsign']))
            {
                $doc_path_image = $this->request->getFile('sign');
                
                    $newFileName = rand(5, 15);
                    $file_ext = $doc_path_image->getExtension();
                    $path_images = 'RANCHI'."/"."EoSign";
                    $doc_path_image->move(WRITEPATH.'uploads/'.$path_images.'/',$newFileName.'.'.$file_ext);      
            }
        }
        return view('trade/Connection/uploadSign');
    }

}