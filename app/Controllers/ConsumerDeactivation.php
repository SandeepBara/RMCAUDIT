<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_view_water_consumer;
use App\Models\water_consumer_details_model;
use App\Models\model_ward_mstr;
use App\Models\model_ulb_mstr;
use App\Models\model_water_consumer;
use App\Models\water_consumer_deactivation_model;
use App\Models\water_consumer_demand_model;

class ConsumerDeactivation extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $water;
    protected $model_view_water_consumer;
    protected $water_consumer_details_model;
    protected $model_ward_mstr;
    protected $model_ulb_mstr;
    protected $model_water_consumer;
    protected $water_consumer_deactivation_model;
    protected $water_consumer_demand_model;

    public function __construct()
    {
        parent::__construct();
        session();
        helper(['db_helper', 'form']);
        if ($db_name = dbConfig("property")) {
            $this->db = db_connect($db_name);
        }
        if ($db_name = dbSystem()) {
            $this->dbSystem = db_connect($db_name);
        }
        if ($db_name = dbConfig("water")) {
            $this->water = db_connect($db_name);
        }
        $this->model_view_water_consumer = new model_view_water_consumer($this->water);
        $this->water_consumer_details_model = new water_consumer_details_model($this->water);
        $this->model_water_consumer = new model_water_consumer($this->water);
        $this->water_consumer_deactivation_model = new water_consumer_deactivation_model($this->water);
        $this->water_consumer_demand_model = new water_consumer_demand_model($this->water);
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
    }

    public function detail($consumer_no = null)
    {
        $session=session();
        $emp_details=$session->get('emp_details');
        $emp_details_id=$emp_details['user_type_mstr_id'];
        if($emp_details_id!="2" && $emp_details_id!="1")
        {
            return redirect()->to('/home');
        }
        
        $data = (array)null;
        $consumerDetailsList = [];

        if ($this->request->getMethod() == 'post') {
            //Cheque Details
            $data['consumer_no'] = strtoupper($this->request->getVar('consumer_no'));
            if ($consumerDetails = $this->model_water_consumer->waterConsumerDetails($data['consumer_no'])) {
                foreach ($consumerDetails as $key => $value) {
                    $consumerDetailsList[$key]['id'] = $value['id'];
                    $consumerDetailsList[$key]['consumer_no'] = $value['consumer_no'];
                    $consumerDetailsList[$key]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($value['ward_mstr_id']);
                    $consumer = $this->water_consumer_details_model->consumerDetailsData($value['id']);
                    $consumerDetailsList[$key]['applicant_name'] = $consumer['applicant_name'];
                    $consumerDetailsList[$key]['mobile_no'] = $consumer['mobile_no'];
                }
                $data['consumerDetailsList'] = $consumerDetailsList;
            } else {
                $data['validation'] = "Record Does Not Exists";
            }
            return view('water/deactivate/consumer_deactivation', $data);
        } else if (isset($consumer_no)) {
            if ($consumerDetails = $this->model_water_consumer->waterConsumerDetailsByConsumerNo($consumer_no)) {
                $consumerDetailsList[0]['id'] = $consumerDetails['id'];
                $consumerDetailsList[0]['consumer_no'] = $consumerDetails['consumer_no'];
                $consumerDetailsList[0]['ward_no'] = $this->model_ward_mstr->getWardByIdWithUlbId($consumerDetails['ward_mstr_id']);
                $consumer = $this->water_consumer_details_model->consumerDetailsData($consumerDetails['id']);
                $consumerDetailsList[0]['applicant_name'] = $consumer['applicant_name'];
                $consumerDetailsList[0]['mobile_no'] = $consumer['mobile_no'];
                $data['consumer_no'] = $consumerDetails['consumer_no'];
                $data['consumerDetailsList'] = $consumerDetailsList;
            } else {
                $data['validation'] = "Record Does Not Exists";
            }
            return view('water/deactivate/consumer_deactivation', $data);
        } else {
            return view('water/deactivate/consumer_deactivation', $data);
        }
    }
    public function create()
    {
        $session = session();
        $emp_details = $session->get('emp_details');
        $ulb_dtl = $session->get('ulb_dtl');
        $emp_details_id = $emp_details['id'];
        $user_type_mstr_id = $emp_details['user_type_mstr_id']??0;
        $city = $this->model_ulb_mstr->getCity($ulb_dtl['ulb_mstr_id']);
        if ($this->request->getMethod() == 'post') 
        { 
            $rules = ['remark'=>[
                        'rules'=>'required|min_length[10]|alpha_numeric_space',
                        'errors'=>[
                            'reuired'=>'Remark is Required',
                            'min_length[10]'=>'Remarks At list 10 Charecter',
                            'alpha_numeric_space'=>'Remarks Invalid Text'
                        ],
                    ],
                    'doc_path'=>[
                        'rules'=>'uploaded[doc_path]|max_size[doc_path,2048]|ext_in[doc_path,pdf,jpg,jpge]',
                        'errors'=>[
                            'uploaded[doc_path]'=>'Document is Required',
                            'max_size[doc_path,2048]'=>'Document can less than 2 mb',
                            'ext_in[doc_path,pdf,jpg,jpge]'=>'Document Invalid'
                        ],
                    ],
                    'reason'=>[
                        'rules'=>'required',
                        'errors'=>[
                            'reuired'=>'Reason is Required',
                            
                        ],
                    ],
                    ];
            if(!$this->validate($rules))
            {
                $data['validation']=$rules;
                flashToast('consumer',$this->validator->getErrors());                
                return redirect()->back()->with('error',$this->validator->getErrors());
            }
            else
            {
                $input = [
                    'remark' => $this->request->getVar('remark'),
                    'deactivation_date' => date('Y-m-d'),
                    'created_on' => date('Y-m-d H:i:s'),
                    'ward_mstr_id' => $this->request->getVar('ward_mstr_id'),
                    'reason'=>$this->request->getVar('reason'),
                ];
                $input['id'] = $this->request->getVar('id');
                $input['doc_path'] = $this->request->getVar('doc_path');
                $input['emp_details_id'] = $emp_details_id;                
                $count = $this->water_consumer_demand_model->countPaidStatus(md5($input['id']));
                if($count && $this->request->getVar('reason')!='Duble Connection' && !in_array($user_type_mstr_id,[1,2]))
                {
                    flashToast("message", "Payment Not Clear!!!");
                    return $this->response->redirect(base_url('ConsumerDeactivation/view/'.(md5($input['id']))));
                }
                $insert_id = $this->water_consumer_deactivation_model->insertConsumerDeactivationData($input);
                if ($insert_id) 
                {
                    //$rules = ['doc_path' => 'uploaded[doc_path]|max_size[doc_path,5124]|ext_in[doc_path,pdf]'];
                    //if ($this->validate($rules)) 
                    {
                        $file = $this->request->getFile('doc_path');
                        $extension = $file->getExtension();
                        if ($file->isValid() && !$file->hasMoved()) {
                            $newName = md5($insert_id) . "." . $extension;
                            $forlder = $city['city'] . '/water_consumer_deactivation'.'/';
    
                            if ($file->move(WRITEPATH . 'uploads/' .$forlder, $newName)) {
                                $this->water_consumer_deactivation_model->uploadDocument($forlder.$newName, $insert_id);
                            }
                        }
                    }
                    $this->model_water_consumer->updateConsumerStatus($input['id']);
                    flashToast('consumer', 'Consumer Deactivated Successfully!!');                    
                    return $this->response->redirect(base_url('ConsumerDeactivation/detail'));
                } 
                else 
                {
                    flashToast('consumer', 'SomeThing Is Wrong!!!');
                    return view('water/deactivate/consumer_deactivation');
                }
            }
           
            
        }
    }
    public function view($id = null)
    {
        $data = (array)null;
        $data['id'] = $id;
        $data['basic_details'] = $this->model_view_water_consumer->waterConsumerDetailsById($data['id']);
        $data['count'] = $this->water_consumer_demand_model->countPaidStatus($id);
        $data['consumer_owner_details'] = $this->water_consumer_details_model->consumerDetailsbyMd5(md5($data['basic_details']['id']));
        //echo"<pre>";print_r($data);echo"</pre>";
        return view('water/deactivate/consumer_deactivation_view', $data);
    }
}
