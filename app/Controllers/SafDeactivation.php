<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_saf_dtl;
use App\Models\model_saf_owner_detail;
use App\Models\model_saf_demand;
use App\Models\model_saf_tax;
use App\Models\model_saf_floor_details;
use App\Models\model_prop_saf_deactivation;
use App\Models\model_ward_mstr;
use App\Models\model_ulb_mstr;

class SafDeactivation extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $model_saf_dtl;
    protected $model_saf_owner_detail;
	protected $model_saf_demand;
	protected $model_saf_tax;
	protected $model_saf_floor_details;
    protected $model_prop_saf_deactivation;
    protected $model_ward_mstr;
    protected $model_ulb_mstr;
    public function __construct(){
        parent::__construct();
        helper(['db_helper','form']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name); 
        }
        if($db_name = dbSystem()){
            $this->dbSystem = db_connect($db_name); 
        }
        $this->model_saf_dtl = new model_saf_dtl($this->db);
        $this->model_saf_owner_detail = new model_saf_owner_detail($this->db);
		$this->model_saf_demand = new model_saf_demand($this->db);
		$this->model_saf_tax = new model_saf_tax($this->db);
		$this->model_saf_floor_details = new model_saf_floor_details($this->db);
        $this->model_prop_saf_deactivation = new model_prop_saf_deactivation($this->db);
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
    }
	
	/*
    public function detail($saf_no=null)
    {
        $data =(array)null;
        $propertyDetailsList = [];
        if($this->request->getMethod()=='post')
        {
            //Cheque Details
           $data['saf_no'] = $this->request->getVar('saf_no');
            if ($propertyDetails = $this->model_saf_dtl->safDetails($data['saf_no'])) {
                $propertyDetailsList[0]['id'] = $propertyDetails['id'];
                $propertyDetailsList[0]['saf_no'] = $propertyDetails['saf_no'];
                $propertyDetailsList[0]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($propertyDetails['ward_mstr_id']);
                $property = $this->model_saf_owner_detail->ownerDetailsData($propertyDetails['id']);
                $propertyDetailsList[0]['owner_name'] = $property['owner_name'];
                $propertyDetailsList[0]['mobile_no'] = $property['mobile_no'];
                $data['propertyDetailsList'] = $propertyDetailsList;
                //print_r($propertyDetailsList);
               
            } else {
                $data['validation'] = "Record Does Not Exists";
            }
            return view('property/saf_deactivation', $data);
        }
        else if(isset($saf_no)){
            if ($propertyDetails = $this->model_saf_dtl->propertyDetailsSaf($saf_no)) {
                $propertyDetailsList[0]['id'] = $propertyDetails['id'];
                $propertyDetailsList[0]['saf_no'] = $propertyDetails['saf_no'];
                $propertyDetailsList[0]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($propertyDetails['ward_mstr_id']);
                $property = $this->model_saf_owner_detail->ownerDetailsData($propertyDetails['id']);
                $propertyDetailsList[0]['owner_name'] = $property['owner_name'];
                $propertyDetailsList[0]['mobile_no'] = $property['mobile_no'];
                $data['propertyDetailsList'] = $propertyDetailsList;
                $data['saf_no'] = $propertyDetails['saf_no'];
               
            } else {
                $data['validation'] = "Record Does Not Exists";
            }
            return view('property/saf_deactivation', $data);
        }
        else
        {
           return view('property/saf_deactivation',$data);
        } 
    }
	*/
    public function create(){
        $session = session();
        $emp_details = $session->get('emp_details');
        $ulb_dtl = $session->get('ulb_dtl');
        $emp_details_id = $emp_details['id'];
        $city = $this->model_ulb_mstr->getCity($ulb_dtl['ulb_mstr_id']);
        if($this->request->getMethod()=='post'){
            $input = [
                        'remark' => $this->request->getVar('remark'),
                        'deactivation_date' =>date('Y-m-d'),
                        'created_on' =>date('Y-m-d H:i:s'),
                        'prop_type' =>'Saf',
                        'ward_mstr_id' => $this->request->getVar('ward_mstr_id')
                    ];
            $prop_dtl_id = $this->request->getVar('saf_dtl_id');
            $input['prop_dtl_id'] = $prop_dtl_id;
            $input['emp_details_id'] = $emp_details_id;
            //Prevent Double Posting
            if($prevent_id = $this->model_prop_saf_deactivation->getSafDeactivationPreventData($input['prop_dtl_id'])){
                echo '<script>alert("SAF Already Deactivated!!!")</script>'; 
				return $this->response->redirect(base_url('safDemandPayment/saf_due_details/'.md5($prop_dtl_id)));
            }else{
                $insert_id = $this->model_prop_saf_deactivation->insertData($input);
                if($insert_id){
                    $rules = ['doc_path' => 'uploaded[doc_path]|max_size[doc_path,5124]|ext_in[doc_path,pdf]'];
                    if($this->validate($rules))
                    {
                        $file = $this->request->getFile('doc_path');
                        
                        $extension = $file->getExtension();
                        
                        if($file->isValid() && !$file->hasMoved()){
                            $newName = md5($insert_id).".".$extension;
                           
                            if($file->move(WRITEPATH.'uploads/'.$city['city'].'/saf_deactivate_doc',$newName))
                            {
                                $this->model_prop_saf_deactivation->uploadDocument($newName,$insert_id);
                            }
                        }
                    }
                    $this->model_saf_dtl->updateSafDtlStatus($prop_dtl_id);
					$this->model_saf_demand->safdemand_deactive($prop_dtl_id);
					$this->model_saf_owner_detail->safowndtl_deactive($prop_dtl_id);
					$this->model_saf_floor_details->safflrdtl_deactive($prop_dtl_id);
					$this->model_saf_tax->saftax_deactive($prop_dtl_id);
					
                    flashToast('saf','SAF Deactivated Successfully!!');
                    return $this->response->redirect(base_url('safDemandPayment/saf_due_details/'.md5($prop_dtl_id)));
                }else{
                    flashToast('saf','SomeThing Is Wrong!!!');
					return $this->response->redirect(base_url('safDemandPayment/saf_due_details/'.md5($prop_dtl_id)));
                }
            }
        }
    }
    public function view($id=null){
        $data =(array)null;
        $data['id'] = $id;
        $data['basic_details'] = $this->model_saf_dtl->basic_details($data);
        $data['owner_details'] = $this->model_saf_owner_detail->ownerdetails($data['basic_details']['saf_dtl_id']);
        return view('property/saf_deactivation_view',$data);
    }
}
?>
