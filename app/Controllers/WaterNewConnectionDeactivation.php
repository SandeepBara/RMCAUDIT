<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\water_applicant_details_model;
use App\Models\model_ward_mstr;
use App\Models\model_ulb_mstr;
use App\Models\WaterApplyNewConnectionModel;
use App\Models\water_property_type_mstr_model;
use App\Models\WaterConnectionThroughModel;
use App\Models\WaterPipelineModel;
use App\Models\WaterConnectionTypeModel;
use App\Models\water_new_connection_deactivation_model;


class WaterNewConnectionDeactivation extends AlphaController
{
    protected $dbSystem;
    protected $water;
    protected $water_property_type_mstr_model;
    protected $WaterApplyNewConnectionModel;
    protected $water_applicant_details_model;
    protected $model_ward_mstr;
    protected $model_ulb_mstr;
    protected $WaterConnectionThroughModel;
    protected $WaterPipelineModel;
    protected $WaterConnectionTypeModel;
    protected $water_new_connection_deactivation_model;
    public function __construct()
    {
        parent::__construct();
        helper(['db_helper','form']);

        if($db_name = dbSystem())
        {
            $this->dbSystem = db_connect($db_name); 
        }
        if($db_name = dbConfig("water"))
        {
            $this->water = db_connect($db_name); 
        }

        $this->water_applicant_details_model = new water_applicant_details_model($this->water);
        $this->water_property_type_mstr_model = new water_property_type_mstr_model($this->water);
        $this->WaterApplyNewConnectionModel = new WaterApplyNewConnectionModel($this->water);
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
        $this->WaterConnectionThroughModel = new WaterConnectionThroughModel($this->water);
        $this->WaterPipelineModel = new WaterPipelineModel($this->water);
        $this->WaterConnectionTypeModel = new WaterConnectionTypeModel($this->water);
        $this->water_new_connection_deactivation_model = new water_new_connection_deactivation_model($this->water);
    }


    public function detail($application_no=null)
    {
        $session=session();
        $emp_details=$session->get('emp_details');
        $emp_details_id=$emp_details['user_type_mstr_id'];
        if($emp_details_id!="2" && $emp_details_id!="1")
        {
            return redirect()->to('/home');
        }
        
        $data =(array)null;
        $applicationDetailsList = [];
        if($this->request->getMethod()=='post')
        {
            //Cheque Details
            $data['application_no'] = trim(strtoupper($this->request->getVar('application_no')));
            if ($applicationDetails = $this->WaterApplyNewConnectionModel->getAllNewConnection($data['application_no']))
            {
                foreach ($applicationDetails as $key => $value)
                {
                    $applicationDetailsList[$key]['id'] = $value['id'];
                    $applicationDetailsList[$key]['application_no'] = $value['application_no'];
                    $applicationDetailsList[$key]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($value['ward_id']);
                    $applicant = $this->water_applicant_details_model->applicationDetailsData($value['id']);
                    $applicationDetailsList[$key]['applicant_name'] = $applicant['applicant_name'];
                    $applicationDetailsList[$key]['mobile_no'] = $applicant['mobile_no'];
                }
                $data['applicationDetailsList'] = $applicationDetailsList;
            }
            else
            {
                $data['validation'] = "Record Does Not Exists";
            }
            return view('water/deactivate/new_connection_deactivation', $data);
        }
        else if(isset($application_no))
        {
            if ($applicationDetails = $this->WaterApplyNewConnectionModel->getAllNewConnectionByApplicationNo($application_no))
            {
                $applicationDetailsList[0]['id'] = $applicationDetails['id'];
                $applicationDetailsList[0]['application_no'] = $applicationDetails['application_no'];
                $applicationDetailsList[0]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($applicationDetails['ward_id']);
                $applicant = $this->water_applicant_details_model->applicationDetailsData($applicationDetails['id']);
                $applicationDetailsList[0]['applicant_name'] = $applicant['applicant_name'];
                $applicationDetailsList[0]['mobile_no'] = $applicant['mobile_no'];
                $data['application_no'] = $applicationDetails['application_no'];
                $data['applicationDetailsList'] = $applicationDetailsList;
            }
            else
            {
                $data['validation'] = "Record Does Not Exists";
            }
            return view('water/deactivate/new_connection_deactivation', $data);
        }
        else
        {
           return view('water/deactivate/new_connection_deactivation',$data);
        } 
    }


    public function create()
    {
        $session = session();
        $emp_details = $session->get('emp_details');
        $ulb_dtl = $session->get('ulb_dtl');
        $emp_details_id = $emp_details['id'];
        $city = $this->model_ulb_mstr->getCity($ulb_dtl['ulb_mstr_id']);
        if($this->request->getMethod()=='post')
        {
            $input = [
                        'remark' => $this->request->getVar('remark'),
                        'deactivation_date'=>date('Y-m-d'),
                        'created_on' =>date('Y-m-d H:i:s'),
                        'ward_mstr_id' => $this->request->getVar('ward_id')
                    ];
            $input['id'] = $this->request->getVar('id');
            $input['doc_path'] = $this->request->getVar('doc_path');
            $input['emp_details_id'] = $emp_details_id;//print_var($_POST);die;
            $insert_id = $this->water_new_connection_deactivation_model->insertNewConnectionDeactivationData($input);
            if($insert_id)
            {
                $rules = ['doc_path' => 'uploaded[doc_path]|max_size[doc_path,5124]|ext_in[doc_path,pdf]'];
                if($this->validate($rules))
                {
                    $file = $this->request->getFile('doc_path');
                    /*echo $file;*/
                    $extension = $file->getExtension();
                    if($file->isValid() && !$file->hasMoved())
                    {
                        $newName = md5($insert_id).".".$extension;
                        if($file->move(WRITEPATH.'uploads/'.$city['city'].'/water_new_apply_connection_deactivation',$newName))
                        {
                            $this->water_new_connection_deactivation_model->uploadDocument($newName,$insert_id);
                        }
                    }
                }
                $this->WaterApplyNewConnectionModel->updateNewApplyConnectionStatus($input['id']);
                flashToast('connection','Apply Water Connection Deactivated Successfully!!');
                return $this->response->redirect(base_url('WaterNewConnectionDeactivation/detail'));
            }
            else
            {
                flashToast('connection','SomeThing Is Wrong!!!');
                return view('water/deactivate/new_connection_deactivation');
            }
        }
    }
    public function view($id=null)
    {
        $data =(array)null;
        $data['basic_details'] = $this->WaterApplyNewConnectionModel->newConnectionDetailsById($id);
        $data['applicant_details'] = $this->water_applicant_details_model->applicantData($id);
        $data['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($data['basic_details']['ward_id']);
        $data['property_type'] = $this->water_property_type_mstr_model->getPropertyType($data['basic_details']['property_type_id']);
        $data['connection_through'] = $this->WaterConnectionThroughModel->getConnectionThrough($data['basic_details']['connection_through_id']);
        $data['pipeline'] = $this->WaterPipelineModel->getPipelineType($data['basic_details']['pipeline_type_id']);
        /*$data['connection_type'] = $this->WaterConnectionTypeModel->getconnectionType($data['basic_details']['connection_type_id']);*/
        //print_var($data);die;
        return view('water/deactivate/new_connection_deactivation_view', $data);
    }
}
?>
