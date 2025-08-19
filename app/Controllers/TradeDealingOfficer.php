<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_ulb_mstr;
use App\Models\model_view_ward_permission;
use App\Models\model_view_trade_level_pending;
use App\Models\model_apply_licence;
use App\Models\model_ward_mstr;
use App\Models\model_firm_owner_name;
use App\Models\model_application_doc;
use App\Models\model_trade_level_pending_dtl;
use App\Models\model_view_trade_licence;
use App\Models\model_view_application_doc;
use App\Models\model_user_type_mstr;

class TradeDealingOfficer extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $property_db;
    protected $model_ulb_mstr;
    protected $model_view_ward_permission;
    protected $model_view_trade_level_pending;
    protected $model_apply_licence;
    protected $model_ward_mstr;
    protected $model_firm_owner_name;
    protected $model_application_doc;
    protected $model_trade_level_pending_dtl;
    protected $model_view_trade_licence;
    protected $model_view_applicant_doc;
    protected $model_view_application_doc;
    protected $model_user_type_mstr;

    public function __construct(){
        parent::__construct();
    	helper(['db_helper','form']);
        if($db_name = dbConfig("trade")){
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
        $this->model_apply_licence = new model_apply_licence($this->db);
        $this->model_firm_owner_name = new model_firm_owner_name($this->db);
        $this->model_application_doc = new model_application_doc($this->db);
        $this->model_trade_level_pending_dtl = new model_trade_level_pending_dtl($this->db);
        $this->model_view_trade_licence = new model_view_trade_licence($this->db);
        $this->model_view_application_doc = new model_view_application_doc($this->db);
        $this->model_user_type_mstr = new model_user_type_mstr($this->dbSystem);
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
                $data['posts'] = $this->model_view_trade_level_pending->tradedareceivebywardidList($receiver_user_type_id,$data['from_date'],$data['to_date'],$data['ward_mstr_id']);
            }
            else{
                $data['posts'] = $this->model_view_trade_level_pending->tradedareceiveList($receiver_user_type_id,$data['from_date'],$data['to_date'],$ward);
            }

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
            return view('trade/Connection/trade_da_list', $data);
        }else{
            $data['from_date'] = date('Y-m-d');
            $data['to_date'] = date('Y-m-d');
            $data['posts'] = $this->model_view_trade_level_pending->tradedareceiveList($receiver_user_type_id,$data['from_date'],$data['to_date'],$ward);
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
            //print_r($data['posts']);
            return view('trade/Connection/trade_dealing_assistant_list', $data);
        }
	}
    public function view($id)
	{
        $data =(array)null;
        $Session = Session();
        date_default_timezone_set('Asia/Kolkata');
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");

        $login_emp_details_id = $emp_mstr["id"];
        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        $data['form'] = $this->model_view_trade_level_pending->tradelevelpendingdetailbyid($id);
        $data['basic_details'] = $this->model_view_trade_licence->getDatabyid(md5($data['form']['apply_licence_id']));
        $ward = $this->model_ward_mstr->getdatabyid($data['form']['ward_mstr_id']);
        $data['form']['ward_no']=$ward['ward_no'];
		$data['ward']['ward_no']=$ward['ward_no'];
        $data['owner_list'] = $this->model_firm_owner_name->applicantdetails($data['form']['apply_licence_id']);
		$data['holding'] = $this->model_apply_licence->getholding($data['basic_details']['application_no']);
        //documents
        $verified_status='1';
        $verify_status='0';
        foreach($data['owner_list'] as $key => $value){
                $app_other_doc='Consumer Photo';
                $app_doc_type="photo_id_proof";

                /*****applicant img code*************/
                $app_img_verified = $this->model_application_doc->get_details_by_connid($data['form']['apply_licence_id'],$value['id'],$app_other_doc,$verified_status);
                if($app_img_verified['firm_owner_dtl_id']!="")
                {
                   $data['owner_list'][$key]['img_stts']='1';
                   $data['owner_list'][$key]['applicant_img'] = $app_img_verified['document_path'];
                   $data['owner_list'][$key]['applicant_img_id'] = $app_img_verified['id'];
                   $data['owner_list'][$key]['applicant_img_verify_status'] = $app_img_verified['verify_status'];
                }
                else{
                     $app_img = $this->model_application_doc->get_details_by_connid($data['form']['apply_licence_id'],$value['id'],$app_other_doc,$verify_status);
                    if($app_img['firm_owner_dtl_id']!="")
                    {
                        $data['owner_list'][$key]['img_stts']='1';
                        $data['owner_list'][$key]['applicant_img'] = $app_img['document_path'];
                        $data['owner_list'][$key]['applicant_img_id'] = $app_img['id'];
                        $data['owner_list'][$key]['applicant_img_verify_status'] = $app_img['verify_status'];
                    }
                }
                /*****applicant doc code*************/
                $app_doc_verified = $this->model_application_doc->conownerdocnamebydoctype($data['form']['apply_licence_id'],$value['id'],$app_doc_type,$verified_status);
                if($app_doc_verified['firm_owner_dtl_id']!="")
                {
                   $data['owner_list'][$key]['doc_stts']='1';
                   $data['owner_list'][$key]['applicant_doc'] = $app_doc_verified['document_path'];
                   $data['owner_list'][$key]['applicant_doc_name'] = $app_doc_verified['doc_name'];
                   $data['owner_list'][$key]['applicant_doc_id'] = $app_doc_verified['id'];
                   $data['owner_list'][$key]['applicant_doc_verify_status'] = $app_doc_verified['verify_status'];
                }
                else{
                     $app_doc = $this->model_application_doc->conownerdocnamebydoctype($data['form']['apply_licence_id'],$value['id'],$app_doc_type,$verify_status);
                    //print_r($app_doc);
                    if($app_doc['firm_owner_dtl_id']!="")
                    {
                        $data['owner_list'][$key]['doc_stts']='1';
                        $data['owner_list'][$key]['applicant_doc'] = $app_doc['document_path'];
                        $data['owner_list'][$key]['applicant_doc_name'] = $app_doc['doc_name'];
                        $data['owner_list'][$key]['applicant_doc_id'] = $app_doc['id'];
                        $data['owner_list'][$key]['applicant_doc_verify_status'] = $app_doc['verify_status'];
                    }
                }

                /******************/

            }

        //Business Premises
		
        $business_doc="Business Premises";
        $data['business_doc'] = $this->model_application_doc->getdocumentdet_by_conid($data['form']['apply_licence_id'],$business_doc,$verified_status);
        if($data['business_doc']['id']!="")
        {
            $data['bu_stts']='1';
            $data['bu_doc_nm'] = 'Business Premises';
            $data['bu_doc_name'] = $data['business_doc']['document_path'];
            $data['bu_doc_id'] = $data['business_doc']['id'];
            $data['bu_doc_verify_status'] = $data['business_doc']['verify_status'];
        }
        else{
             $data['business_docc'] = $this->model_application_doc->getdocumentdet_by_conid($data['form']['apply_licence_id'],$business_doc,$verify_status);
            if($data['business_docc']['id']!="")
            {
                $data['bu_stts']='1';
                $data['bu_doc_nm'] = 'Business Premises';
                $data['bu_doc_name'] = $data['business_docc']['document_path'];
                $data['bu_doc_id'] = $data['business_docc']['id'];
                $data['bu_doc_verify_status'] = $data['business_docc']['verify_status'];
            }
        }
		
        //NOC And NOC Affidavit Document
		
        $noc_doc="NOC And NOC Affidavit Document";
        $data['noc_doc'] = $this->model_application_doc->getdocumentdet_by_conid($data['form']['apply_licence_id'],$noc_doc,$verified_status);
        if($data['noc_doc']['id']!="")
        {
            $data['noc_stts']='1';
            $data['noc_doc_nm'] = 'NOC And NOC Affidavit Document';
            $data['noc_doc_name'] = $data['noc_doc']['document_path'];
            $data['noc_doc_id'] = $data['noc_doc']['id'];
            $data['noc_doc_verify_status'] = $data['noc_doc']['verify_status'];
        }
        else{
             $data['noc_docc'] = $this->model_application_doc->getdocumentdet_by_conid($data['form']['apply_licence_id'],$noc_doc,$verify_status);
            if($data['noc_docc']['id']!="")
            {
                $data['noc_stts']='1';
                $data['noc_doc_nm'] = 'NOC And NOC Affidavit Document';
                $data['noc_doc_name'] = $data['noc_docc']['document_path'];
                $data['noc_doc_id'] = $data['noc_docc']['id'];
                $data['noc_doc_verify_status'] = $data['noc_docc']['verify_status'];
            }
        }

		//Partnership Document
		
        $partnership_doc="Partnership Document";
        $data['partnership_doc'] = $this->model_application_doc->getdocumentdet_by_conid($data['form']['apply_licence_id'],$partnership_doc,$verified_status);
        if($data['partnership_doc']['id']!="")
        {
            $data['Par_stts']='1';
            $data['Par_doc_nm'] = 'Partnership Document';
            $data['Par_doc_name'] = $data['partnership_doc']['document_path'];
            $data['Par_doc_id'] = $data['partnership_doc']['id'];
            $data['Par_doc_verify_status'] = $data['partnership_doc']['verify_status'];
        }
        else{
             $data['partnership_docc'] = $this->model_application_doc->getdocumentdet_by_conid($data['form']['apply_licence_id'],$partnership_doc,$verify_status);
            if($data['partnership_docc']['id']!="")
            {
                $data['Par_stts']='1';
                $data['Par_doc_nm'] = 'Partnership Document';
                $data['Par_doc_name'] = $data['partnership_docc']['document_path'];
                $data['Par_doc_id'] = $data['partnership_docc']['id'];
                $data['Par_doc_verify_status'] = $data['partnership_docc']['verify_status'];
            }
        }
		
		//Sapat Patra
		
        $sapat_patra_doc="Sapat Patra";
        $data['sapat_patra_doc'] = $this->model_application_doc->getdocumentdet_by_conid($data['form']['apply_licence_id'],$sapat_patra_doc,$verified_status);
        if($data['sapat_patra_doc']['id']!="")
        {
            $data['sap_stts']='1';
            $data['sap_doc_nm'] = 'Sapat Patra';
            $data['sap_doc_name'] = $data['sapat_patra_doc']['document_path'];
            $data['sap_doc_id'] = $data['sapat_patra_doc']['id'];
            $data['sap_doc_verify_status'] = $data['sapat_patra_doc']['verify_status'];
        }
        else{
             $data['sapat_patra_docc'] = $this->model_application_doc->getdocumentdet_by_conid($data['form']['apply_licence_id'],$sapat_patra_doc,$verify_status);
            if($data['sapat_patra_docc']['id']!="")
            {
                $data['sap_stts']='1';
                $data['sap_doc_nm'] = 'Sapat Patra';
                $data['sap_doc_name'] = $data['sapat_patra_docc']['document_path'];
                $data['sap_doc_id'] = $data['sapat_patra_docc']['id'];
                $data['sap_doc_verify_status'] = $data['sapat_patra_docc']['verify_status'];
            }
        }
		
		//Solid Waste User Charge Document
		
        $solid_waste_doc="Solid Waste User Charge Document";
        $data['solid_waste_doc'] = $this->model_application_doc->getdocumentdet_by_conid($data['form']['apply_licence_id'],$solid_waste_doc,$verified_status);
        if($data['solid_waste_doc']['id']!="")
        {
            $data['sol_stts']='1';
            $data['sol_doc_nm'] = 'Solid Waste User Charge Document';
            $data['sol_doc_name'] = $data['solid_waste_doc']['document_path'];
            $data['sol_doc_id'] = $data['solid_waste_doc']['id'];
            $data['sol_doc_verify_status'] = $data['solid_waste_doc']['verify_status'];
        }
        else{
             $data['solid_waste_docc'] = $this->model_application_doc->getdocumentdet_by_conid($data['form']['apply_licence_id'],$solid_waste_doc,$verify_status);
            if($data['solid_waste_docc']['id']!="")
            {
                $data['sol_stts']='1';
                $data['sol_doc_nm'] = 'Solid Waste User Charge Document';
                $data['sol_doc_name'] = $data['solid_waste_docc']['document_path'];
                $data['sol_doc_id'] = $data['solid_waste_docc']['id'];
                $data['sol_doc_verify_status'] = $data['solid_waste_docc']['verify_status'];
            }
        }
		
		//Electricity Bill
		
        $electricity_doc="Electricity Bill";
        $data['electricity_doc'] = $this->model_application_doc->getdocumentdet_by_conid($data['form']['apply_licence_id'],$electricity_doc,$verified_status);
        if($data['electricity_doc']['id']!="")
        {
            $data['ele_stts']='1';
            $data['ele_doc_nm'] = 'Electricity Bill';
            $data['ele_doc_name'] = $data['electricity_doc']['document_path'];
            $data['ele_doc_id'] = $data['electricity_doc']['id'];
            $data['ele_doc_verify_status'] = $data['electricity_doc']['verify_status'];
        }
        else{
             $data['electricity_docc'] = $this->model_application_doc->getdocumentdet_by_conid($data['form']['apply_licence_id'],$electricity_doc,$verify_status);
            if($data['electricity_docc']['id']!="")
            {
                $data['ele_stts']='1';
                $data['ele_doc_nm'] = 'Electricity Bill';
                $data['ele_doc_name'] = $data['electricity_docc']['document_path'];
                $data['ele_doc_id'] = $data['electricity_docc']['id'];
                $data['ele_doc_verify_status'] = $data['electricity_docc']['verify_status'];
            }
        }
		
		//Application Form
		
        $application_doc="Application Form";
        $data['application_doc'] = $this->model_application_doc->getdocumentdet_by_conid($data['form']['apply_licence_id'],$application_doc,$verified_status);
        if($data['application_doc']['id']!="")
        {
            $data['app_stts']='1';
            $data['app_doc_nm'] = 'Application Form';
            $data['app_doc_name'] = $data['application_doc']['document_path'];
            $data['app_doc_id'] = $data['application_doc']['id'];
            $data['app_doc_verify_status'] = $data['application_doc']['verify_status'];
        }
        else{
             $data['application_docc'] = $this->model_application_doc->getdocumentdet_by_conid($data['form']['apply_licence_id'],$application_doc,$verify_status);
            if($data['application_docc']['id']!="")
            {
                $data['app_stts']='1';
                $data['app_doc_nm'] = 'Application Form';
                $data['app_doc_name'] = $data['application_docc']['document_path'];
                $data['app_doc_id'] = $data['application_docc']['id'];
                $data['app_doc_verify_status'] = $data['application_docc']['verify_status'];
            }
        }
        


        //count document
        $photo_doc='Consumer Photo';
        $consumer_photo_doc="photo_id_proof";
        $data['photo_doc_cnt'] = $this->model_application_doc->count_uploaded_document($data['form']['apply_licence_id'],$photo_doc);
        $data['consumer_photo_doc_cnt'] = $this->model_application_doc->count_uploaded_document($data['form']['apply_licence_id'],$consumer_photo_doc);
		
        $data['business_doc_cnt'] = $this->model_application_doc->count_uploaded_document($data['form']['apply_licence_id'],$business_doc);
		
		$data['noc_doc_cnt'] = $this->model_application_doc->count_uploaded_document($data['form']['apply_licence_id'],$noc_doc);
		
		$data['partnership_doc_cnt'] = $this->model_application_doc->count_uploaded_document($data['form']['apply_licence_id'],$partnership_doc);
		
		$data['sapat_patra_doc_cnt'] = $this->model_application_doc->count_uploaded_document($data['form']['apply_licence_id'],$sapat_patra_doc);
		
		$data['solid_waste_doc_cnt'] = $this->model_application_doc->count_uploaded_document($data['form']['apply_licence_id'],$solid_waste_doc);
		
		$data['electricity_doc_cnt'] = $this->model_application_doc->count_uploaded_document($data['form']['apply_licence_id'],$electricity_doc);
		
		$data['application_doc_cnt'] = $this->model_application_doc->count_uploaded_document($data['form']['apply_licence_id'],$application_doc);
        
        $apply_licence_id=$data['form']['apply_licence_id'];
        //echo $photo_doc;
        //print_r($data['bpl_cnt']);
        $data['app_cnt']=$data['photo_doc_cnt']['doc_cnt'] + $data['consumer_photo_doc_cnt']['doc_cnt'] + $data['business_doc_cnt']['doc_cnt']  + $data['noc_doc_cnt']['doc_cnt']  + $data['partnership_doc_cnt']['doc_cnt']  + $data['sapat_patra_doc_cnt']['doc_cnt']  + $data['solid_waste_doc_cnt']['doc_cnt']  + $data['electricity_doc_cnt']['doc_cnt']  + $data['application_doc_cnt']['doc_cnt'];
        
//echo $data['app_cnt'];

        if($this->request->getMethod()=='post'){
            if(isset($_POST['btn_app_submit']))
            {

                $data = [
                        'bu_document_id' => $this->request->getVar('bu_document_id'),
                        'noc_document_id' => $this->request->getVar('noc_document_id'),
                        'Par_document_id' => $this->request->getVar('Par_document_id'),
                        'sap_document_id' => $this->request->getVar('sap_document_id'),
						'sol_document_id' => $this->request->getVar('sol_document_id'),
						'ele_document_id' => $this->request->getVar('ele_document_id'),
						'app_document_id' => $this->request->getVar('app_document_id'),
						
                        'bu_remarks' => $this->request->getVar('bu_remarks'),
                        'noc_remarks' => $this->request->getVar('noc_remarks'),
                        'Par_remarks' => $this->request->getVar('Par_remarks'),
                        'sap_remarks' => $this->request->getVar('sap_remarks'),
						'sol_remarks' => $this->request->getVar('sol_remarks'),
						'ele_remarks' => $this->request->getVar('ele_remarks'),
						'app_remarks' => $this->request->getVar('app_remarks'),
						
                        'bu_verify' => $this->request->getVar('bu_verify'),
                        'noc_verify' => $this->request->getVar('noc_verify'),
                        'Par_verify' => $this->request->getVar('Par_verify'),
                        'sap_verify' => $this->request->getVar('sap_verify'),
						'sol_verify' => $this->request->getVar('sol_verify'),
						'ele_verify' => $this->request->getVar('ele_verify'),
						'app_verify' => $this->request->getVar('app_verify'),
						
                        'remarks' => $this->request->getVar('remarks'),
                        'level_pending_dtl_id' => $id,
                        'apply_licence_id' => $apply_licence_id,
                        'emp_details_id' => $login_emp_details_id,
                        'receiver_user_type_id'=>11,
                        'created_on' =>date('Y-m-d H:i:s'),
                        'forward_date' => date('Y-m-d'),
                        'forward_time' => date('H:i:s'),
                        'sender_user_type_id' => $sender_user_type_id,
                        'verification_status'=>2,
                        'level_pending_status'=>2
                        

                    ];


                if($updatebacktocitizen = $this->model_trade_level_pending_dtl->updatebacktocitizenById($data)){
                    if($updatesafpendingstts = $this->model_apply_licence->update_level_pending_status($data)){
						$insrtlevelpending = $this->model_trade_level_pending_dtl->insrtlevelpendingdtl($data);
                        $updateverifystts = $this->model_apply_licence->update_verify_status($data);
                        ///applicant image stts
						
                        $app_img_verify = $this->request->getVar('app_img_verify');
                        $app_img_remarks = $this->request->getVar('app_img_remarks');
                        $applicant_img_id = $this->request->getVar('applicant_img_id');
                        if(isset($applicant_img_id)){
                        $app_img_len = sizeof($applicant_img_id);
                        for($iv=0;$iv<$app_img_len;$iv++)
                        {
                            $data_up = [
                                'applicant_img_id' => $applicant_img_id[$iv],
                                'app_img_verify' => $app_img_verify[$iv],
                                'app_img_remarks' => $app_img_remarks[$iv],
                                'emp_details_id'=>$login_emp_details_id,
                                'created_on'=>date('Y-m-d H:i:s')
                            ];

                             $updateappimgdoc = $this->model_application_doc->updateappimgdocById($data_up);
                        }
                            }
                        ///applicant doc stts
                        $app_doc_verify = $this->request->getVar('app_doc_verify');
                        $app_doc_remarks = $this->request->getVar('app_doc_remarks');
                        $applicant_doc_id = $this->request->getVar('applicant_doc_id');
                        if(isset($applicant_doc_id)){
                        $app_doc_len = sizeof($applicant_doc_id);

							for($ivn=0;$ivn<$app_doc_len;$ivn++)
							{
								$data_u = [
									'applicant_doc_id' => $applicant_doc_id[$ivn],
									'app_doc_verify' => $app_doc_verify[$ivn],
									'app_doc_remarks' => $app_doc_remarks[$ivn],
									'emp_details_id'=>$login_emp_details_id,
									'created_on'=>date('Y-m-d H:i:s')
								];
								$updateappdoc = $this->model_application_doc->updateappdocById($data_u);
							}
						}
                         $updatebudoc = $this->model_application_doc->updatebudocById($data);
                         $updatenocdoc = $this->model_application_doc->updatenocdocById($data);
                         $updatepardoc = $this->model_application_doc->updatepardocById($data);
                         $updatesapdoc = $this->model_application_doc->updatesapdocById($data);
						 $updatesoldoc = $this->model_application_doc->updatesoldocById($data);
                         $updateeledoc = $this->model_application_doc->updateeledocById($data);
                         $updateapdoc = $this->model_application_doc->updateapdocById($data);

                        return $this->response->redirect(base_url('trade_da/index/'));
                    }
                }
            }
            /**********/
            if(isset($_POST['btn_approve_submit']))
            {
                $data = [ 
                        'bu_document_id' => $this->request->getVar('bu_document_id'),
                        'noc_document_id' => $this->request->getVar('noc_document_id'),
                        'Par_document_id' => $this->request->getVar('Par_document_id'),
                        'sap_document_id' => $this->request->getVar('sap_document_id'),
						'sol_document_id' => $this->request->getVar('sol_document_id'),
						'ele_document_id' => $this->request->getVar('ele_document_id'),
						'app_document_id' => $this->request->getVar('app_document_id'),
						
                        'bu_remarks' => $this->request->getVar('bu_remarks'),
                        'noc_remarks' => $this->request->getVar('noc_remarks'),
                        'Par_remarks' => $this->request->getVar('Par_remarks'),
                        'sap_remarks' => $this->request->getVar('sap_remarks'),
						'sol_remarks' => $this->request->getVar('sol_remarks'),
						'ele_remarks' => $this->request->getVar('ele_remarks'),
						'app_remarks' => $this->request->getVar('app_remarks'),
						
                        'bu_verify' => $this->request->getVar('bu_verify'),
                        'noc_verify' => $this->request->getVar('noc_verify'),
                        'Par_verify' => $this->request->getVar('Par_verify'),
                        'sap_verify' => $this->request->getVar('sap_verify'),
						'sol_verify' => $this->request->getVar('sol_verify'),
						'ele_verify' => $this->request->getVar('ele_verify'),
						'app_verify' => $this->request->getVar('app_verify'),
						
                        'remarks' => $this->request->getVar('remarks'),
                        'level_pending_dtl_id' => $id,
                        'apply_licence_id' => $apply_licence_id,
                        'emp_details_id' => $login_emp_details_id,
                        'sender_user_type_id' => $sender_user_type_id,
                        'receiver_user_type_id'=>20,
                        'created_on' =>date('Y-m-d H:i:s'),
                        'forward_date' => date('Y-m-d'),
                        'forward_time' => date('H:i:s'),
                        'verification_status'=>1,
                        'level_pending_status'=>0,
                        'doc_verify_status' => 1,
                        'doc_verify_date' => date('Y-m-d'),
                        'doc_verify_emp_details_id' => $login_emp_details_id
                    ];

                if($updatelevelpending = $this->model_trade_level_pending_dtl->updatelevelpendingById($data)){
                    if($updatesafpendingstts = $this->model_apply_licence->update_level_pending_status($data)){
                        $insrtlevelpending = $this->model_trade_level_pending_dtl->insrtlevelpendingdtl($data);
                         $updateverifystts = $this->model_apply_licence->update_verify_status($data);
                        ///applicant image stts
                        $app_img_verify = $this->request->getVar('app_img_verify');
                        $app_img_remarks = $this->request->getVar('app_img_remarks');
                        $applicant_img_id = $this->request->getVar('applicant_img_id');
                        if(isset($applicant_img_id)){
                        $app_img_len = sizeof($applicant_img_id);
                        for($iv=0;$iv<$app_img_len;$iv++)
                        {
                            $data_up = [
                                'applicant_img_id' => $applicant_img_id[$iv],
                                'app_img_verify' => $app_img_verify[$iv],
                                'app_img_remarks' => $app_img_remarks[$iv],
                                'emp_details_id'=>$login_emp_details_id,
                                'created_on'=>date('Y-m-d H:i:s')
                            ];

                             $updateappimgdoc = $this->model_application_doc->updateappimgdocById($data_up);
                        }
                            }
                        ///applicant doc stts
                        $app_doc_verify = $this->request->getVar('app_doc_verify');
                        $app_doc_remarks = $this->request->getVar('app_doc_remarks');
                        $applicant_doc_id = $this->request->getVar('applicant_doc_id');
                        if(isset($applicant_doc_id)){
                        $app_doc_len = sizeof($applicant_doc_id);

                        for($ivn=0;$ivn<$app_doc_len;$ivn++)
                        {
                            $data_u = [
                                'applicant_doc_id' => $applicant_doc_id[$ivn],
                                'app_doc_verify' => $app_doc_verify[$ivn],
                                'app_doc_remarks' => $app_doc_remarks[$ivn],
                                'emp_details_id'=>$login_emp_details_id,
                                'created_on'=>date('Y-m-d H:i:s')
                            ];
                            $updateappdoc = $this->model_application_doc->updateappdocById($data_u);
                        }
                            }
                         $updatebudoc = $this->model_application_doc->updatebudocById($data);
                         $updatenocdoc = $this->model_application_doc->updatenocdocById($data);
                         $updatepardoc = $this->model_application_doc->updatepardocById($data);
                         $updatesapdoc = $this->model_application_doc->updatesapdocById($data);
						 $updatesoldoc = $this->model_application_doc->updatesoldocById($data);
                         $updateeledoc = $this->model_application_doc->updateeledocById($data);
                         $updateapdoc = $this->model_application_doc->updateapdocById($data);

                        return $this->response->redirect(base_url('trade_da/index/'));
                    }
                }

            }            

        }
        else
        {
             return view('trade/Connection/trade_da_view', $data);
        }
    }

    public function da_back_to_citizen_list()
	{
        $data =(array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        //print_r($emp_mstr);
        $login_emp_details_id = $emp_mstr["id"];
        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['wardList'] = $wardList;

        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
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
                $data['posts'] = $this->model_apply_licence->wardwiseboc_saf_list($data['from_date'],$data['to_date'],$data['ward_mstr_id']);
            }
            else{
                $data['posts'] = $this->model_apply_licence->boc_saf_list($data['from_date'],$data['to_date'],$ward);
            }

        //print_r($data['posts']);
        $j=0;
        foreach($data['posts'] as $key => $value){
                   $wardd = $this->model_ward_mstr->getdatabyid($value['ward_mstr_id']);
                   $owner = $this->model_firm_owner_name->applicantdetails($value['id']);
                    $j=0;
                   foreach($owner as $keyy => $val){
                        //$ow[$key][$keyy]['owner']= $val["owner_name"];
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
            return view('trade/Connection/da_back_to_citizen_list', $data);
            }
        else{
            $data['from_date'] = date('Y-m-d');
            $data['to_date'] = date('Y-m-d');
            $data['posts'] = $this->model_apply_licence->boc_saf_list($data['from_date'],$data['to_date'],$ward);
//print_r($data['posts']);
        $j=0;
        foreach($data['posts'] as $key => $value){
                   $wardd = $this->model_ward_mstr->getdatabyid($value['ward_mstr_id']);

                   $owner = $this->model_firm_owner_name->applicantdetails($value['id']);
                    $j=0;
                   foreach($owner as $keyy => $val){
                        //$ow[$key][$keyy]['owner']= $val["owner_name"];
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
            //print_r($data['posts']);
            return view('trade/Connection/da_back_to_citizen_list', $data);
        }
    }

    public function boc_document_verification_view($id)
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
        $data['ward'] = $this->model_ward_mstr->getdatabyid($data['basic_details']['ward_mstr_id']);
        $data['owner_details'] = $this->model_firm_owner_name->applicantdetails($data['basic_details']['id']);
		$data['holding'] = $this->model_apply_licence->getholding($data['basic_details']['application_no']);
        $verify_status='0';
        foreach($data['owner_details'] as $key => $value){
                $app__doc='Consumer Photo';
                $data['owner_details'][$key]['saf_owner_img_list'] = $this->model_application_doc->get_ownerimgdetails_by_safid($data['basic_details']['id'],$value['id'],$app__doc);

                $app_doc_type="photo_id_proof";
                $data['owner_details'][$key]['saf_owner_doc_list'] = $this->model_application_doc->conownerdocdetbyid($data['basic_details']['id'],$value['id'],$app_doc_type);

            }
        //print_r($data['owner_details']);
        $apply_licence_id=$data['basic_details']['id'];

		$business_doc="Business Premises";
		$data['business_doc_exists']=$this->model_application_doc->getdocdet_by_conid($apply_licence_id,$business_doc);
		
		$noc_doc="NOC And NOC Affidavit Document";
		$data['noc_doc_exists']=$this->model_application_doc->getdocdet_by_conid($apply_licence_id,$noc_doc);
		
		$partnership_doc="Partnership Document";
		$data['partnership_doc_exists']=$this->model_application_doc->getdocdet_by_conid($apply_licence_id,$partnership_doc);
		
		$sapat_patra_doc="Sapat Patra";
		$data['sapat_patra_doc_exists']=$this->model_application_doc->getdocdet_by_conid($apply_licence_id, $sapat_patra_doc);
		
		$solid_waste_doc="Solid Waste User Charge Document";
		$data['solid_waste_doc_exists']=$this->model_application_doc->getdocdet_by_conid($apply_licence_id,$solid_waste_doc);
		
		$electricity_doc="Electricity Bill";
		$data['electricity_doc_exists']=$this->model_application_doc->getdocdet_by_conid($apply_licence_id,$electricity_doc);
		
		$application_doc="Application Form";
		$data['application_doc_exists']=$this->model_application_doc->getdocdet_by_conid($apply_licence_id,$application_doc);
		
        $data['remark'] = $this->model_trade_level_pending_dtl->backtocitizen_dl_remarks_by_con_id($apply_licence_id);
        $data['basic_details']['remarks'] = $data['remark']['remarks'];

        return view('trade/Connection/da_back_to_citizen_view', $data);
}
    public function da_approved_list()
	{
        $data =(array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];

        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['wardList'] = $wardList;
        //print_r($emp_mstr);

        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
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
                $data['posts'] = $this->model_view_trade_level_pending->wardwise_daapprovedList($sender_user_type_id,$data['from_date'],$data['to_date'],$data['ward_mstr_id']);
            }
            else{
                $data['posts'] = $this->model_view_trade_level_pending->daapprovedList($sender_user_type_id,$data['from_date'],$data['to_date'],$ward);
            }

        //print_r($data['posts']);
        $j=0;
        foreach($data['posts'] as $key => $value){
                   $wardd = $this->model_ward_mstr->getdatabyid($value['ward_mstr_id']);
                   $owner = $this->model_firm_owner_name->applicantdetails($value['apply_connection_id']);
                    $j=0;
                   foreach($owner as $keyy => $val){
                        //$ow[$key][$keyy]['owner']= $val["owner_name"];
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
            return view('trade/Connection/da_approved_list', $data);
            }
        else
            {
            $data['from_date'] = date('Y-m-d');
            $data['to_date'] = date('Y-m-d');
            $data['posts'] = $this->model_view_trade_level_pending->daapprovedList($sender_user_type_id,$data['from_date'],$data['to_date'],$ward);
        //print_r($data['posts']);
        $j=0;
        foreach($data['posts'] as $key => $value){
                   $wardd = $this->model_ward_mstr->getdatabyid($value['ward_mstr_id']);
                  $owner = $this->model_firm_owner_name->applicantdetails($value['apply_licence_id']);
                    $j=0;
                   foreach($owner as $keyy => $val){
                        //$ow[$key][$keyy]['owner']= $val["owner_name"];
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
            return view('trade/Connection/da_approved_list', $data);
        }
       // print_r($data['posts']);
	}
    public function da_approved_view($id)
	{
        $data =(array)null;
        $Session = Session();
        date_default_timezone_set('Asia/Kolkata');
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");

        $login_emp_details_id = $emp_mstr["id"];
        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        $data['form'] = $this->model_view_trade_level_pending->tradelevelpendingdetailbyid($id);
        $data['basic_details'] = $this->model_view_trade_licence->getDatabyid(md5($data['form']['apply_licence_id']));
        $data['ward']  = $this->model_ward_mstr->getdatabyid($data['form']['ward_mstr_id']);
        $data['form']['ward_no']=$ward['ward_no'];
        $data['owner_details'] = $this->model_firm_owner_name->applicantdetails($data['form']['apply_licence_id']);
		$data['holding'] = $this->model_apply_licence->getholding($data['basic_details']['application_no']);
        $verify_status='1';
        foreach($data['owner_details'] as $key => $value){
                $app__doc='Consumer Photo';
                $data['owner_details'][$key]['saf_owner_img_list'] = $this->model_application_doc->get_verifiedownerimgdetails_by_safid($data['basic_details']['id'],$value['id'],$app__doc);

                $app_doc_type="photo_id_proof";
                $data['owner_details'][$key]['saf_owner_doc_list'] = $this->model_view_application_doc->get_verifiedownerdocdetails_by_conid($data['basic_details']['id'],$value['id'],$app_doc_type);

            }
        //print_r($data['owner_details']);
        $apply_licence_id=$data['basic_details']['id'];

        $business_doc="Business Premises";
		$data['business_doc_exists']=$this->model_application_doc->getdocdet_by_conid($apply_licence_id,$business_doc);
		//print_r($data['business_doc_exists']);
		$noc_doc="NOC And NOC Affidavit Document";
		$data['noc_doc_exists']=$this->model_application_doc->getdocdet_by_conid($apply_licence_id,$noc_doc);
		
		$partnership_doc="Partnership Document";
		$data['partnership_doc_exists']=$this->model_application_doc->getdocdet_by_conid($apply_licence_id,$partnership_doc);
		
		$sapat_patra_doc="Sapat Patra";
		$data['sapat_patra_doc_exists']=$this->model_application_doc->getdocdet_by_conid($apply_licence_id, $sapat_patra_doc);
		
		$solid_waste_doc="Solid Waste User Charge Document";
		$data['solid_waste_doc_exists']=$this->model_application_doc->getdocdet_by_conid($apply_licence_id,$solid_waste_doc);
		
		$electricity_doc="Electricity Bill";
		$data['electricity_doc_exists']=$this->model_application_doc->getdocdet_by_conid($apply_licence_id,$electricity_doc);
		
		$application_doc="Application Form";
		$data['application_doc_exists']=$this->model_application_doc->getdocdet_by_conid($apply_licence_id,$application_doc);

        $data['remark'] = $this->model_trade_level_pending_dtl->approved_dl_remarks_by_con_id($apply_licence_id);
        $data['basic_details']['remarks'] = $data['remark']['remarks'];

        return view('trade/Connection/da_approved_view', $data);
}
    public function forward_list()
	{
        $data =(array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];

        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['wardList'] = $wardList;
        //print_r($emp_mstr);

        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
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
                $data['posts'] = $this->model_view_trade_level_pending->wardwise_forwardList($sender_user_type_id,$data['from_date'],$data['to_date'],$data['ward_mstr_id']);
            }
            else{
                $data['posts'] = $this->model_view_trade_level_pending->forwardList($sender_user_type_id,$data['from_date'],$data['to_date'],$ward);
            }

        //print_r($data['posts']);
        $j=0;
        foreach($data['posts'] as $key => $value){
                   $wardd = $this->model_ward_mstr->getdatabyid($value['ward_mstr_id']);
                   $user_type_nm = $this->model_user_type_mstr->getdatabyid(md5($value['receiver_user_type_id']));
                   $owner = $this->model_firm_owner_name->applicantdetails($value['apply_connection_id']);
                    $j=0;
                   foreach($owner as $keyy => $val){
                        //$ow[$key][$keyy]['owner']= $val["owner_name"];
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
                  $data['posts'][$key]['user_type'] = $user_type_nm['user_type'];

               }
            return view('trade/Connection/trade_forward_list', $data);
            }
        else
            {
            $data['from_date'] = date('Y-m-d');
            $data['to_date'] = date('Y-m-d');
            $data['posts'] = $this->model_view_trade_level_pending->forwardList($sender_user_type_id,$data['from_date'],$data['to_date'],$ward);
        //print_r($data['posts']);
        $j=0;
        foreach($data['posts'] as $key => $value){
                   $wardd = $this->model_ward_mstr->getdatabyid($value['ward_mstr_id']);
            $user_type_nm = $this->model_user_type_mstr->getdatabyid(md5($value['receiver_user_type_id']));
                  $owner = $this->model_firm_owner_name->applicantdetails($value['apply_licence_id']);
                    $j=0;
                   foreach($owner as $keyy => $val){
                        //$ow[$key][$keyy]['owner']= $val["owner_name"];
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
                  $data['posts'][$key]['user_type'] = $user_type_nm['user_type'];

               }
            return view('trade/Connection/trade_forward_list', $data);
        }
       // print_r($data['posts']);
	}
    public function forward_view($id)
	{
        $data =(array)null;
        $Session = Session();
        date_default_timezone_set('Asia/Kolkata');
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");

        $login_emp_details_id = $emp_mstr["id"];
        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        $data['form'] = $this->model_view_trade_level_pending->tradelevelpendingdetailbyid($id);
        $data['basic_details'] = $this->model_view_trade_licence->getDatabyid(md5($data['form']['apply_licence_id']));
        $data['ward']  = $this->model_ward_mstr->getdatabyid($data['form']['ward_mstr_id']);
        $data['form']['ward_no']=$ward['ward_no'];
        $data['owner_details'] = $this->model_firm_owner_name->applicantdetails($data['form']['apply_licence_id']);
		$data['holding'] = $this->model_apply_licence->getholding($data['basic_details']['application_no']);
        $verify_status='1';
        foreach($data['owner_details'] as $key => $value){
                $app__doc='Consumer Photo';
                $data['owner_details'][$key]['saf_owner_img_list'] = $this->model_application_doc->get_verifiedownerimgdetails_by_safid($data['basic_details']['id'],$value['id'],$app__doc);

                $app_doc_type="photo_id_proof";
                $data['owner_details'][$key]['saf_owner_doc_list'] = $this->model_view_application_doc->get_verifiedownerdocdetails_by_conid($data['basic_details']['id'],$value['id'],$app_doc_type);

            }
        //print_r($data['owner_details']);
        $apply_licence_id=$data['basic_details']['id'];

        $business_doc="Business Premises";
		$data['business_doc_exists']=$this->model_application_doc->getdocdet_by_conid($apply_licence_id,$business_doc);
		
		$noc_doc="NOC And NOC Affidavit Document";
		$data['noc_doc_exists']=$this->model_application_doc->getdocdet_by_conid($apply_licence_id,$noc_doc);
		
		$partnership_doc="Partnership Document";
		$data['partnership_doc_exists']=$this->model_application_doc->getdocdet_by_conid($apply_licence_id,$partnership_doc);
		
		$sapat_patra_doc="Sapat Patra";
		$data['sapat_patra_doc_exists']=$this->model_application_doc->getdocdet_by_conid($apply_licence_id, $sapat_patra_doc);
		
		$solid_waste_doc="Solid Waste User Charge Document";
		$data['solid_waste_doc_exists']=$this->model_application_doc->getdocdet_by_conid($apply_licence_id,$solid_waste_doc);
		
		$electricity_doc="Electricity Bill";
		$data['electricity_doc_exists']=$this->model_application_doc->getdocdet_by_conid($apply_licence_id,$electricity_doc);
		
		$application_doc="Application Form";
		$data['application_doc_exists']=$this->model_application_doc->getdocdet_by_conid($apply_licence_id,$application_doc);

        $data['remark'] = $this->model_trade_level_pending_dtl->forward_remarks_by_con_id($apply_licence_id,$sender_user_type_id);
        $data['basic_details']['remarks'] = $data['remark']['remarks'];

        return view('trade/Connection/trade_forward_view', $data);
}
    }
