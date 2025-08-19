<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\model_ulb_mstr;
use App\Models\model_view_ward_permission;
use App\Models\model_view_water_level_pending;
use App\Models\model_applicant_details;
use App\Models\model_applicant_doc;
use App\Models\model_water_level_pending_dtl;
use App\Models\WaterApplyNewConnectionModel;
use App\Models\model_view_water_connection;
use App\Models\model_view_applicant_doc;

class Water_JE extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $property_db;
    protected $model_ward_mstr;
    protected $model_ulb_mstr;
    protected $model_view_ward_permission;
    protected $model_view_water_level_pending;
    protected $model_applicant_details;
    protected $model_applicant_doc;
    protected $model_water_level_pending_dtl;
    protected $WaterApplyNewConnectionModel;
    protected $model_view_water_connection;
    protected $model_view_applicant_doc;

    public function __construct(){
        parent::__construct();
    	helper(['db_helper']);
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
        $this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_view_ward_permission = new model_view_ward_permission($this->dbSystem);
        $this->model_view_water_level_pending = new model_view_water_level_pending($this->db);
        $this->model_applicant_details = new model_applicant_details($this->db);
        $this->model_applicant_doc = new model_applicant_doc($this->db);
        $this->model_water_level_pending_dtl = new model_water_level_pending_dtl($this->db);
        $this->WaterApplyNewConnectionModel = new WaterApplyNewConnectionModel($this->db);
        $this->model_view_water_connection = new model_view_water_connection($this->db);
        $this->model_view_applicant_doc = new model_view_applicant_doc($this->db);
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
        helper(['form']);
        if($this->request->getMethod()=='post')
        {
            $data['ward_mstr_id'] = $this->request->getVar('ward_mstr_id');
             $data['from_date'] = $this->request->getVar('from_date');
             $data['to_date'] = $this->request->getVar('to_date');

            if($data['ward_mstr_id']!="")
            {
                $data['posts'] = $this->model_view_water_level_pending->waterjereceivebywardidList($receiver_user_type_id,$data['from_date'],$data['to_date'],$data['ward_mstr_id']);
            }
            else{
                $data['posts'] = $this->model_view_water_level_pending->waterjereceiveList($receiver_user_type_id,$data['from_date'],$data['to_date'],$ward);
            }

            $j=0;
            foreach($data['posts'] as $key => $value){
                $wardd = $this->model_ward_mstr->getdatabyid($value['ward_id']);
                $owner = $this->model_applicant_details->applicantdetails($value['apply_connection_id']);
                $j=0;
                foreach($owner as $keyy => $val){
                    if($j==0){
                        $data['posts'][$key]['owner_name']=array($val["applicant_name"]);
                        $data['posts'][$key]['mobile_no']=array($val["mobile_no"]);
                    }else{
                        array_push($data['posts'][$key]['owner_name'], $val["applicant_name"]);
                        array_push($data['posts'][$key]['mobile_no'], $val["mobile_no"]);
                    }
                    $j++;
                }
                $data['posts'][$key]['ward_no'] = $wardd['ward_no'];
            }
            return view('water/water_connection/water_je_list', $data);
        }
        else
        {
            $data['from_date'] = date('Y-m-d');
            $data['to_date'] = date('Y-m-d');
            $data['posts'] = $this->model_view_water_level_pending->waterjereceiveList($receiver_user_type_id,$data['from_date'],$data['to_date'],$ward);
            $j=0;
            foreach($data['posts'] as $key => $value){
                $wardd = $this->model_ward_mstr->getdatabyid($value['ward_id']);
                $owner = $this->model_applicant_details->applicantdetails($value['apply_connection_id']);
                $j=0;
                foreach($owner as $keyy => $val){
                    if($j==0){
                        $data['posts'][$key]['owner_name']=array($val["applicant_name"]);
                        $data['posts'][$key]['mobile_no']=array($val["mobile_no"]);
                    }else{
                        array_push($data['posts'][$key]['owner_name'], $val["applicant_name"]);
                        array_push($data['posts'][$key]['mobile_no'], $val["mobile_no"]);
                    }
                    $j++;
                }
                $data['posts'][$key]['ward_no'] = $wardd['ward_no'];
            }
            return view('water/water_connection/water_je_list', $data);
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
        $data['form'] = $this->model_view_water_level_pending->waterlevelpendingdetailbyid($id);
        $data['basic_details'] = $this->model_view_water_connection->getDatabyid(md5($data['form']['apply_connection_id']));
        $data['ward']  = $this->model_ward_mstr->getdatabyid($data['form']['ward_id']);
        $data['form']['ward_no']=$ward['ward_no'];
        $data['owner_details'] = $this->model_applicant_details->applicantdetails($data['form']['apply_connection_id']);

        $verify_status='1';
        foreach($data['owner_details'] as $key => $value){
                $app__doc='consumer_photo';
                $data['owner_details'][$key]['saf_owner_img_list'] = $this->model_applicant_doc->get_verifiedownerimgdetails_by_safid($data['basic_details']['id'],$value['id'],$app__doc);

                $app_doc_type="photo_id_proof";
                $data['owner_details'][$key]['saf_owner_doc_list'] = $this->model_view_applicant_doc->get_verifiedownerdocdetails_by_conid($data['basic_details']['id'],$value['id'],$app_doc_type);

            }

        $apply_connection_id=$data['basic_details']['id'];

        $payment_doc="payment_receipt";
        $data['payment_receipt_doc']=$this->model_applicant_doc->getverifieddocdet_by_conid($apply_connection_id,$payment_doc);
        $add_doc="address_proof";
        $data['address_proof_doc']=$this->model_view_applicant_doc->get_verifiedaddressdocdetails_by_conid($apply_connection_id,$add_doc);
        $connection_doc="connection_form";
        $data['connection_doc']=$this->model_applicant_doc->getverifieddocdet_by_conid($apply_connection_id,$connection_doc);
        $electricity_doc="electricity_bill";
        $data['electricity_doc']=$this->model_applicant_doc->getverifieddocdet_by_conid($apply_connection_id,$electricity_doc);
        $meter_bill_doc="meter_bill";
        $data['meter_bill_doc']=$this->model_applicant_doc->getverifieddocdet_by_conid($apply_connection_id,$meter_bill_doc);
        $bpl_doc="bpl";
        $data['bpl_doc']=$this->model_applicant_doc->getverifieddocdet_by_conid($apply_connection_id,$bpl_doc);

        $data['remark'] = $this->model_water_level_pending_dtl->approved_dl_remarks_by_con_id($apply_connection_id);
        $data['basic_details']['remarks'] = $data['remark']['remarks'];
        if($this->request->getMethod()=='post'){            
            if(isset($_POST['btn_verify_submit']))
            {
                $data = [
                        'remarks' => $this->request->getVar('remarks'),
                         'level_pending_dtl_id' => $id,
                         'apply_connection_id' => $apply_connection_id,
                         'emp_details_id' => $login_emp_details_id,
                         'created_on' =>date('Y-m-d H:i:s'),
                        'forward_date' =>date('Y-m-d'),
                        'forward_time' =>date('H:i:s'),
                         'sender_user_type_id' => $sender_user_type_id,
                         'receiver_user_type_id'=>14,
                        'verification_status'=>1
                    ];
                if($updateverify = $this->model_water_level_pending_dtl->updatelevelpendingById($data)){
                    if($insertverify = $this->model_water_level_pending_dtl->insrtlevelpendingdtl($data)){
                        return $this->response->redirect(base_url('Water_JE/index/'));
                    }
                }
            }

        }
        else
        {
             return view('water/water_connection/water_je_view', $data);
        }


}
    }
