<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_ulb_mstr;
use App\Models\model_view_ward_permission;
use App\Models\WaterApplyNewConnectionModel;
use App\Models\model_ward_mstr;
use App\Models\model_applicant_details;
use App\Models\model_applicant_doc;
use App\Models\model_water_level_pending_dtl;
use App\Models\model_view_water_connection;
use App\Models\model_document_mstr;
use App\Models\WaterApplicantDocModel;


class WaterDocumentNew extends AlphaController
{
    protected $db;
    protected $property_db;
    protected $dbSystem;
    protected $model_ulb_mstr;
    protected $model_view_ward_permission;
    protected $WaterApplyNewConnectionModel;
    protected $model_ward_mstr;
    protected $model_applicant_details;
    protected $model_applicant_doc;
    protected $model_water_level_pending_dtl;
    protected $model_view_water_connection;
    protected $model_document_mstr;
    
    public function __construct(){
        parent::__construct();

        $session=session();
        $emp_details=$session->get('emp_details');
        $this->emp_id=$emp_details['id'];

    	helper(['db_helper', 'upload_helper']);
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
        $this->WaterApplyNewConnectionModel = new WaterApplyNewConnectionModel($this->db);
        $this->model_applicant_details = new model_applicant_details($this->db);
        $this->model_applicant_doc = new model_applicant_doc($this->db);
        $this->model_water_level_pending_dtl = new model_water_level_pending_dtl($this->db);
        $this->model_view_water_connection = new model_view_water_connection($this->db);
        $this->model_document_mstr = new model_document_mstr($this->db);
        $this->applicant_doc_model=new WaterApplicantDocModel($this->db);
    }

    public function index()
	{
        $data =(array)null;
        helper(['form']);
        $session = session();
        $ulb_dtl = $session->get('ulb_dtl');
        $ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];
        //Transaction Mode List
        $emp_mstr = $session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];

        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['wardList'] = $wardList;

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
                $data['posts'] = $this->WaterApplyNewConnectionModel->wardwise_water_con_list($data['from_date'],$data['to_date'],$data['ward_mstr_id']);
            }
            else{
                $data['posts'] = $this->WaterApplyNewConnectionModel->water_con_list($data['from_date'],$data['to_date'],$ward);
            }


        $j=0;
        foreach($data['posts'] as $key => $value){
                    $wardd = $this->model_ward_mstr->getdatabyid($value['ward_id']);
                   $owner = $this->model_applicant_details->applicantdetails($value['id']);
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
            return view('water/water_connection/water_conn_list', $data);
            }
        else
            {
            $data['from_date'] = date('Y-m-d');
            $data['to_date'] = date('Y-m-d');


            $data['wardList'];
            $data['posts'] = $this->WaterApplyNewConnectionModel->water_con_list($data['from_date'],$data['to_date'],$ward);
        //print_r($data['posts']);

        $j=0;
        foreach($data['posts'] as $key => $value){
                   $wardd = $this->model_ward_mstr->getdatabyid($value['ward_id']);
                   $owner = $this->model_applicant_details->applicantdetails($value['id']);
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
           // print_r($data['posts']);
            return view('water/water_connection/water_conn_list', $data);
            }
	}

    public function doc_upload($id=null)
    {   

        $data=array();
        $data['applicant_details']=$this->WaterApplyNewConnectionModel->water_conn_details($id);
        $data['owner_list']=$this->WaterApplyNewConnectionModel->water_owner_details($id);
        $data['id_proof_doc']=$this->model_document_mstr->getDocumentList('ID Proof');
        $data['address_proof_doc']=$this->model_document_mstr->getDocumentList('Address Proof');
        

        foreach($data['owner_list'] as $key=>$val)
        {   
            $get_onwer_image=$this->model_applicant_doc->check_owner_img($data['applicant_details']['id'],$val['id']);
            $get_owner_doc=$this->model_applicant_doc->check_owner_doc($data['applicant_details']['id'],$val['id']);
            $data['owner_list'][$key]['owner_image']=$get_onwer_image['document_path'];
            $data['owner_list'][$key]['owner_doc']=$get_owner_doc['document_path'];
            
        }
        //print_r($data['owner_list']);
        $data['address_proof_document_list ']=array();

        if($this->request->getMethod()=='post')
        {
            $inputs=arrFilterSanitizeString($this->request->getVar());
            print_r($inputs);

            if($inputs['btn_owner_doc'])
            {

                $applicant_id=$inputs['owner_dtl_id'];
                $apply_connection_id=$inputs['apply_connection_id'];
                $owner_doc_mstr_id=$inputs['owner_doc_mstr_id'];
                                
                $owner_image=$this->request->getFile('consumer_photo_doc_path');
                $photo_id_proof_doc_path=$this->request->getFile('photo_id_proof_doc_path');

                $rules=[
                        'consumer_photo_doc_path'=>'uploaded[consumer_photo_doc_path]|max_size[consumer_photo_doc_path,10240]|ext_in[consumer_photo_doc_path,png,jpg,jpeg]',
                        'photo_id_proof_doc_path'=>'uploaded[photo_id_proof_doc_path]|max_size[photo_id_proof_doc_path,1024000]|ext_in[photo_id_proof_doc_path,pdf]',
                ];

                 if($this->validate($rules))
                 { 


                    if(isset($owner_image))
                    {
                        
                        $owner_image=array();
                        $owner_image['apply_connection_id']=$apply_connection_id;
                        $owner_image['applicant_detail_id']=$applicant_id;
                        $owner_image['doc_for']='CONSUMER_PHOTO';
                        $owner_image['document_id']=21;
                        $owner_image['emp_details_id']=$emp_id;
                        
                        $owner_image_insert_id=$this->applicant_doc_model->insertData($owner_image);

                        if($owner_image_insert_id)
                        {    
                             if($owner_image->IsValid() && !$owner_image->hasMoved())
                             {  
                                
                                $newFileName = md5($owner_image_insert_id);
                                $file_ext = $onwer_image->getExtension();
                                
                                $path = $ulb_city_nm."/"."water_doc_dtl";
                                
                                if($onwer_image->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext))
                                {
                                    $owner_doc_path = $path."/".$newFileNamee.'.'.$file_extt;

                                    $this->applicant_doc_model->updateDocumentPath($owner_image_insert_id,$owner_doc_path);
                                }
                                
                                
                             }

                        }

                    }
                 }



            }
        }

        return view('water/water_connection/water_document_upload', $data);

    }

}