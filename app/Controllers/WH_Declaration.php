<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\model_prop_dtl;
use App\Models\model_prop_owner_detail;
use App\Models\model_prop_tax;
use App\Models\model_prop_demand;
use App\Models\model_fy_mstr;
use App\Models\model_ulb_mstr;
use App\Models\model_harvesting_declaration_dtl;

class WH_Declaration extends AlphaController
{
    protected $db;
    protected $dbSystem;
    public function __construct(){
        parent::__construct();
    	helper(['db_helper']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name);            
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        }
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_fy_mstr = new model_fy_mstr($this->dbSystem);
        $this->model_prop_dtl = new model_prop_dtl($this->db);
        $this->model_prop_owner_detail = new model_prop_owner_detail($this->db);
        $this->model_prop_tax = new model_prop_tax($this->db);
        $this->model_prop_demand = new model_prop_demand($this->db);
        $this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
        $this->model_harvesting_declaration_dtl = new model_harvesting_declaration_dtl($this->db);
    }

    public function search_list()
	{
        $data =(array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        //print_r($emp_mstr);
        $wardList = $this->model_ward_mstr->getWardListForReport($data['ulb_mstr_id']);
        $data['wardList'] = $wardList;
        $login_emp_details_id = $emp_mstr["id"];

        helper(['form']);
        if($this->request->getMethod()=='post'){
            $data['holding_no'] = $this->request->getVar('holding_no');
            $data['owner_name'] = $this->request->getVar('owner_name');
            $data['mobile_no'] = $this->request->getVar('mobile_no');
            $data['ward_mstr_id'] = $this->request->getVar('ward_mstr_id');
            if($data['holding_no']!="")
            {
                $data['posts'] = $this->model_prop_dtl->propertyDetailsListbyHoldingNo($data['holding_no']);
            }
            else if($data['owner_name']!="")
            {
                $data['prop_owner'] = $this->model_prop_owner_detail->propertyidbyownername($data['owner_name']);
                $data['posts'] = $this->model_prop_dtl->propertyDetailsListbypropid($data['prop_owner']['prop_dtl_id']);
            }
            else if($data['mobile_no']!="")
            {
                $data['prop_id'] = $this->model_prop_owner_detail->propertyidbyownermobno($data['mobile_no']);
                $data['posts'] = $this->model_prop_dtl->propertyDetailsListbypropid($data['prop_id']['prop_dtl_id']);
            }
            else if($data['ward_mstr_id']!="")
            {
                $data['posts'] = $this->model_prop_dtl->propertyDetailsListbyWardid($data['ward_mstr_id']);
            }            
            else{
                $data['posts'] = $this->model_prop_dtl->propertyDetailsList();
            }

            foreach($data['posts'] as $key => $value){
                   $ward = $this->model_ward_mstr->getdatabyid($value['ward_mstr_id']);
                   $owner = $this->model_prop_owner_detail->propownerdetails($value['id']);
                   $declaration = $this->model_harvesting_declaration_dtl->declaration_dtl_by_propdtlid($value['id']);
                    $j=0;
                   foreach($owner as $keyy => $val){
                        //$ow[$key][$keyy]['owner']= $val["owner_name"];
                       if($j==0){
                           $data['posts'][$key]['owner_name']=array($val["owner_name"]);
                           $data['posts'][$key]['mobile_no']=array($val["mobile_no"]);
                       }else{
                           array_push($data['posts'][$key]['owner_name'], $val["owner_name"]);
                           array_push($data['posts'][$key]['mobile_no'], $val["mobile_no"]);
                       }
                       $j++;
                   }
                  $data['posts'][$key]['ward_no'] = $ward['ward_no'];
                  $data['posts'][$key]['declaration_id'] = $declaration['id'];
           }
            //print_r($data['posts']);
             return view('property/jsk/wh_decl_list', $data);
            }
        else
            {
            $data['posts'] = $this->model_prop_dtl->propertyDetailsList();

            foreach($data['posts'] as $key => $value){
                   $ward = $this->model_ward_mstr->getdatabyid($value['ward_mstr_id']);
                   $owner = $this->model_prop_owner_detail->propownerdetails($value['id']);
                   $declaration = $this->model_harvesting_declaration_dtl->declaration_dtl_by_propdtlid($value['id']);
                    $j=0;
                   foreach($owner as $keyy => $val){
                        //$ow[$key][$keyy]['owner']= $val["owner_name"];
                       if($j==0){
                           $data['posts'][$key]['owner_name']=array($val["owner_name"]);
                           $data['posts'][$key]['mobile_no']=array($val["mobile_no"]);
                       }else{
                           array_push($data['posts'][$key]['owner_name'], $val["owner_name"]);
                           array_push($data['posts'][$key]['mobile_no'], $val["mobile_no"]);
                       }
                       $j++;
                   }
                  $data['posts'][$key]['ward_no'] = $ward['ward_no'];
                  $data['posts'][$key]['declaration_id'] = $declaration['id'];
           }
            //print_r($data['posts']);
            return view('property/jsk/wh_decl_list', $data);
            }


	}
  public function view($id=null)
	{
        $data =(array)null;
        $data['id']=$id;
        $Session = Session();
        date_default_timezone_set('Asia/Kolkata');
        $ulb_mstr = $Session->get("ulb_dtl");
        $ulb_mstr_id = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
        $data['ulb_dtl'] = $this->model_ulb_mstr->getulb_list($ulb_mstr_id);
        $ulb_city_nm=$data['ulb_dtl']['city'];
        $data['basic_details'] = $this->model_prop_dtl->prop_basic_details($data);
        $data['owner_details'] = $this->model_prop_owner_detail->propownerdetails($data['basic_details']['prop_dtl_id']);
        $prop_dtl_id=$data['basic_details']['prop_dtl_id'];
        $ward_mstr_id=$data['basic_details']['ward_mstr_id'];
        if($this->request->getMethod()=='post'){
            if(isset($_POST['btn_submit']))
            {
                $data = [
                        'declaration_date' => $this->request->getVar('declaration_date'),
                        'remarks' => $this->request->getVar('remarks'),
                         'prop_dtl_id' => $prop_dtl_id,
                         'ward_mstr_id' => $ward_mstr_id,
                         'created_by_emp_details_id' => $login_emp_details_id,
                         'created_on' =>date('Y-m-d H:i:s')
                    ];
                //get financial year
                $declaration_month=date('n',strtotime($data['declaration_date']));
                $declaration_year=date('Y',strtotime($data['declaration_date']));
                if($declaration_month>3)
                {
                    $decl_fi_yr=$declaration_year."-".($declaration_year+1);
                }
                else{
                    $decl_fi_yr=($declaration_year-1)."-".$declaration_year;
                }

                //get quarter

                if($declaration_month>=1 && 3>=$declaration_month){ // X1
					$decl_qtr = 4;
				}else if($declaration_month>=4 && 6>=$declaration_month){ // X4
					$decl_qtr = 1;
				}else if($declaration_month>=7 && 9>=$declaration_month){ // X3
					$decl_qtr = 2;
				}else if($declaration_month>=10 && 12>=$declaration_month){ // X2
					$decl_qtr = 3;
				}
                //echo $decl_fi_yr;
                //echo $decl_qtr;
                //die();
                //get financial year id from fy mstr
                $data['decl_fi'] = $this->model_fy_mstr->getFiidByfyyr($decl_fi_yr);
                $decl_fi_yr_id=$data['decl_fi']['id'];
                $rules=[
                        'declaration_doc_path'=>'uploaded[declaration_doc_path]|max_size[declaration_doc_path,1024000]|ext_in[declaration_doc_path,png,jpg,jpeg,pdf]',
                ];

                //print_r($data['prop_tx_dtl']);
                if($this->validate($rules)){
                    if($harvesting_declaration_last_id = $this->model_harvesting_declaration_dtl->insrtdeclarationdtl($data)){
                        $declaration_doc_file=$this->request->getFile('declaration_doc_path');
                        if($declaration_doc_file->IsValid() && !$declaration_doc_file->hasMoved()){
                             $newFileName = md5($harvesting_declaration_last_id);
                            $file_ext = $declaration_doc_file->getExtension();
                            $path = $ulb_city_nm."/"."declaration_doc_dtl";

                            if($declaration_doc_file->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext)){

                                $declaration_doc_path = $path."/".$newFileName.'.'.$file_ext;
                                if($updaterow = $this->model_harvesting_declaration_dtl->updatedocpathById($harvesting_declaration_last_id,$declaration_doc_path))
                                {
                                    //check fy id and qtr exist in prop tax

                                    if($data['prop_tx_dtl'] = $this->model_prop_tax->getdetByfyid_qtr_propdtlid($prop_dtl_id,$decl_fi_yr_id,$decl_qtr))
                                    {


                                        if($updtadditionaltax = $this->model_prop_tax->updateadditionaltaxById($data['prop_tx_dtl']['id']))
                                        {
                                            $updatedemandnonpaid = $this->model_prop_demand->updateamt_balanceByproptaxId($data['prop_tx_dtl']['id'],$data['prop_tx_dtl']['holding_tax']);
                                        }
                                    }
                                    else
                                    { 

                                        //get previous data

                                        if($data['prop_tax_dtl'] = $this->model_prop_tax->get_previous_fyid_qtr_byproptaxid($prop_dtl_id,$decl_fi_yr_id,$decl_qtr))
                                        {


                                            $water_tax=0;
                                            $education_cess=0;
                                            $health_cess=0;
                                            $latrine_tax=0;
                                            $additional_tax=0;
                                            if($insrtproptax = $this->model_prop_tax->insertpropaxdetbysafid($prop_dtl_id,$decl_fi_yr_id,$decl_qtr,$data['prop_tax_dtl']['arv'],$data['prop_tax_dtl']['holding_tax'],$water_tax,$education_cess,$health_cess,$latrine_tax,$additional_tax,$data['created_on']))
                                            {

                                                $updatedemandnotpaid = $this->model_prop_demand->updateproptaxidByfyid_qtr_propid($insrtproptax,$data['prop_tax_dtl']['id'],$decl_fi_yr_id,$decl_qtr,$data['prop_tax_dtl']['holding_tax']);
                                                //update additional tax all next fi yr and qtr
                                                $updatenextproptax = $this->model_prop_tax->updateadditionaltaxByfyIdqtr($prop_dtl_id,$decl_fi_yr_id,$decl_qtr);
                                            }
                                        }
                                    }
                                    return $this->response->redirect(base_url('wh_Declaration/dlview/'.$id.''));
                                }
                            }
                        }
                     }
                }
                else{
                    $data['err_msg']='Error Occurs!!';
                    return view('property/jsk/wh_declaration_view', $data);
                }
            }
        }
        else{
            return view('property/jsk/wh_declaration_view', $data);
        }
    }
    
    public function dlview($id=null)
	{
        $data =(array)null;
        $data['id']=$id;
        $Session = Session();
        date_default_timezone_set('Asia/Kolkata');
        $ulb_mstr = $Session->get("ulb_dtl");
        $ulb_mstr_id = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
        $data['basic_details'] = $this->model_prop_dtl->prop_basic_details($data);
        $data['owner_details'] = $this->model_prop_owner_detail->propownerdetails($data['basic_details']['prop_dtl_id']);
        $data['tax_list'] = $this->model_prop_tax->tax_list($data['basic_details']['prop_dtl_id']);
        $data['declaration_dtl'] = $this->model_harvesting_declaration_dtl->declaration_dtl_by_propdtlid($data['basic_details']['prop_dtl_id']);
        //print_r($data['declaration_dtl']);

            return view('property/jsk/declaration_view', $data);

    }

}